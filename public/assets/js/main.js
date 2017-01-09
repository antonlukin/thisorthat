var left  = $("#q-left"),
	right = $("#q-right");

var API = {
 	query: function(uri, method, user, data, callback) {
		var url = API.url(uri);

		$.ajax({
			type: method,
			url: url,
			data: data,
			beforeSend: function(xhr) {
				if(typeof user === 'object')
					xhr.setRequestHeader("Authorization", "Basic " + btoa(user.user + ":" + user.token));
			},
			timeout: 5000,
			dataType: 'json',
			contentType: "application/json; charset=utf-8",
			error: function(xhr){
                if(typeof xhr.responseText === 'undefined')
					return UI.error('undefined ajax error', 500);

				var e = eval("(" + xhr.responseText + ")");
				return UI.error(e.description, e.error);
			},
			success: function(response) {
				if (typeof callback === 'function')
					return callback(response);
			}
		});
	},

	url: function(uri) {
		// NOTE: define api url here
		return  '/api' + uri;
	}
}

var URI = {
    init: function(){
		var path = this.get();
		var qid = path.match(/^q\/(\d+)\/?/);
		
		return (qid === null) ? null : qid[1];
	},

	get: function(){
	 	return document.location.pathname.replace('/', '');
	},

	set: function(){
		
	}
}

var UI = {
	error: function(message, code) {
		console.log(message);
	},

	versus: function(show) {
        var obj = $("#ui-versus"),
			cl  = 'onload';

		if(typeof show === 'undefined')
			return obj.toggleClass(cl);

		return show ? obj.addClass(cl) : obj.removeClass(cl);
	},

	result: function(id, sel, result) {
		var vote = result.vote,
			count = result.count;

		var data = [{
			value: vote.r,
			color: "#5F4886",
		},{
			value: vote.l,
			color: "#D54B33",
		}];

		var views = {};
		views[id] = sel;

		var ctx = document.getElementById("ui-result").getContext("2d");
		window.uiChart = new Chart(ctx).Doughnut(data, {
			responsive: true,
			animationEasing: "easeOutCirc",
			animationSteps: 50,
			segmentShowStroke: false,
			showTooltips: false,
		});

		var countdown = function(arr, more) {
            var dfd = new jQuery.Deferred();

			$("#ui-questions").addClass('result');

			var timer = function(el, v, c, is) {
				for (var i = 0; i < v; ++i) {
					setTimeout(function() {
						el.find("h3 > b").html(++c);
						if(c === v - 1 && is)
							dfd.resolve(); 
					}, 20 * i)
				} 
			}

			$.each(arr, function(i,v){
				timer(window[i], v, 0, i === more);
			});

			return dfd.promise();
		}

		var numbers = function(arr, sel) {
			$.each(arr, function(i, v) {
				var cs = [];
 				if(i === sel)
					cs = [' пользователь ответил как вы', ' пользователя ответили как вы', ' пользователей ответили как вы']; 
				else                 
 					cs = [' пользователь ответил иначе', ' пользователя ответили иначе', ' пользователей ответили иначе'];  

				$("#q-" + i).find("h4").html(Application.correct_number(v, cs));
			});

		}

		numbers(count, sel);

		$(".question").off('click');
		$(".question-" + sel).addClass('selected');

		$.when(countdown({left:vote.l, right:vote.r}, vote.l >= vote.r ? 'left' : 'right')).then(function(status){
 			$(".question").on('click', function() {
				UI.change();
			});     			 
		});

		Application.add_view(views);
	},

	change: function(data) {
		this.versus(true);

		Application.reinit();
	},

	skip: function(id) {
 		var views = {};
		views[id] = 'skip'; 

		Application.add_view(views); 
		UI.change();
	},

	questions: function(response) {
		for(id in response)
			break;

		var q = response[id],
			obj = $("#ui-questions"),
			vote = {}, result = {};

		obj.data('id', id);

		vote.l = parseInt(q.left_vote) || 0;
		vote.r = parseInt(q.right_vote) || 0;

		vote.l = (vote.l !== vote.r) ? Math.round(100 / (vote.l + vote.r) * vote.l) : 50;
		vote.r = 100 - vote.l;

        left.find("h2").html(q.left_text);
		right.find("h2").html(q.right_text);

		if(q.moderate == 0)
			$("#moderate").fadeIn();

		UI.versus(false);

		result.count = {left: q.left_vote, right: q.right_vote};
		result.vote = vote;

		$(".skip").off('click').on('click', function() {
			UI.skip(id);
		});

		$(".question").off('click').on('click', function() {
			var sel = $(this).attr('class').match(/question-(left|right)/)[1];

			UI.result(id, sel, result);
		});
	}
}

var Application = {
	init: function(qid) {
		var user = localStorage.getItem("user");

		var loader = function() {
			if(qid === null)
				return Application.get_item();

			return Application.show_item(qid);
		}

		if(user === null)
			return this.add_user(loader);

		return loader();
	},

	reinit: function() {
		if(window.uiChart)
			window.uiChart.destroy();

		$(".question").removeClass('selected');
 		$("#moderate").hide(); 

		$("#ui-questions").removeClass('result');
		$(".question-cell > h2").html('');
 		$(".question-cell > h4").html(''); 
		$(".question-cell > h3 > b").html('0');

		Application.get_item();
	},

	vk: function(callback) {
		if (window.name.indexOf('fXD') != 0)
			return callback();

//		$(".store-vk").css('visibility', 'hidden');

		$.getScript("//vk.com/js/api/xd_connection.js?2", function(){
			VK.init(function() {
				var user = (function() {
					return decodeURIComponent((new RegExp('[?|&]viewer_id=' + '([^&;]+?)(&|#|;|$)').exec(location.search)||[,""])[1].replace(/\+/g, '%20'))||null
				})();

				if(user.match(/\d+/))
					window.vkid = user;

				return callback();

			}, callback, '5.28');  
		});			
	},

	correct_number: function(number, titles, callback) {
		cases = [2, 0, 1, 1, 1, 2];
		return number + " " + titles[ (number%100>4 && number%100<20)? 2 : cases[(number%10<5)?number%10:5] ];       
	},

	get_item: function(callback) {
		API.query("/items/get/1", 'GET', this.get_user, {}, function(response){
			UI.questions(response);

			if (typeof callback === 'function')
				return callback();
		});
	},

	show_item: function(qid, callback) {
		API.query("/items/show/" + qid, 'GET', this.get_user, {}, function(response){
			UI.questions(response);

			if (typeof callback === 'function')
				return callback();
		});         	
	},

	add_view: function(views, callback) {
 		var data = JSON.stringify({views:views});

 		API.query("/views/add/", 'POST', this.get_user(), data, function(response){
			if (typeof callback === 'function')
				return callback();
		});
	},

	get_user: function() {
		var user = localStorage.getItem("user");

		if(user === null)
			return UI.error('local storage is empty', 500);

		return JSON.parse(user);
	},

	add_user: function(callback) {
		var unique = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
			var r = Math.random()*16|0, v = c == 'x' ? r : (r&0x3|0x8);
			return v.toString(16);
		});

		var data = (typeof window.vkid === 'undefined')
			? JSON.stringify({client:"web", unique:unique})
			: JSON.stringify({client:"vk", unique:window.vkid})

		API.query("/users/add/", 'POST', undefined, data, function(response){
			localStorage.setItem("user", JSON.stringify(response));

			if (typeof callback === 'function')
				return callback();
		});
	}
}


$(document).ready(function(){
	Application.vk(function(){
 		Application.init(URI.init());
	});
});


