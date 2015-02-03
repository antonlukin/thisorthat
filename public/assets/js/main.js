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
	get: function(){
	 	return document.location.pathname.replace('/', '');
	},

	set: function(){

	}
}

var UI = {
	error: function(message, code) {
		alert(message);
	},

	versus: function(show) {
        var obj = $("#ui-versus"),
			cl  = 'onload';

		if(typeof show === 'undefined')
			return obj.toggleClass(cl);

		return show ? obj.addClass(cl) : obj.removeClass(cl);
	},

	result: function(id, sel, vote) {
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

		var countdown = function(el, v, c) {
			$("#ui-questions").addClass('result');

			for (var i = 0; i < v; ++i) {
				setTimeout(function() {
					el.find("h3 > b").html(++c);
				}, 20 * i)
			}
		}

		$(".question-" + sel).addClass('selected');

		countdown(left, vote.l, 0);
		countdown(right, vote.r, 0);

		Application.add_view(views);

   		return $(".question").off('click').on('click', function() {
			UI.change();
		});
	},

	change: function(data) {
		this.versus(true);

		Application.reinit();
	},

	questions: function(response) {
		for(id in response)
			break;

		var q = response[id],
			obj = $("#ui-questions"),
			vote  = {};

		obj.data('id', id);

		vote.l = parseInt(q.left_vote) || 0;
		vote.r = parseInt(q.right_vote) || 0;

		vote.l = (vote.l !== vote.r) ? Math.round(100 / (vote.l + vote.r) * vote.l) : 50;
		vote.r = 100 - vote.l;

        left.find("h2").html(q.left_text);
		right.find("h2").html(q.right_text);

		UI.versus(false);

		return $(".question").off('click').on('click', function() {
			var sel = $(this).attr('class').match(/question-(left|right)/)[1];

			UI.result(id, sel, vote);
		});
	}
}

var Application = {
	init: function() {
		var user = localStorage.getItem("user");

		if(user === null)
			return this.add_user(this.get_item);

		return this.get_item();
	},

	reinit: function() {
		var user = this.get_user();

		window.uiChart.destroy();

		$(".question").removeClass('selected');

		$("#ui-questions").removeClass('result');
		$(".question-cell > h2").html('');
		$(".question-cell > h3 > b").html('0');

		Application.get_item();
	},

	get_item: function(callback) {
		API.query("/items/get/1", 'GET', this.get_user, {}, function(response){
			UI.questions(response);

			if (typeof callback === 'function')
				return callback();
		});
	},

	show_item: function(callback) {

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

		var data = JSON.stringify({client:"web", unique:unique});

		API.query("/users/add/", 'POST', undefined, data, function(response){
			localStorage.setItem("user", JSON.stringify(response));

			if (typeof callback === 'function')
				return callback();
		});
	}
}



$(document).ready(function(){
	Application.init();
//	window.history.replaceState({state:1}, '123', 'myurld.html');
 	var url = document.location.pathname;

//	alert(url);
//	console.log(window.history.state);
});




