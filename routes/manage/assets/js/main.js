$(document).ready(function(){
	$(".q").on('click', "button", function(e){
        e.preventDefault();

        var el = $(this),
			p = el.closest(".q");

		var cl = el.attr('class');

        var data = {
			path: 'moderate',
			status: el.data('status'),
			item: p.data('id'),
			left_text: p.find(".left").val(),
			right_text: p.find(".right").val()
		};

		if(data.status == 3 && !confirm("Точно удалить?"))
			return false;

		$.post('/manage/', data, function(response){
			if(!response.success)
				return alert('Ошибка выполнения запроса');
			
			if(cl === 'remove')
				return p.fadeOut();

			p.addClass("m-" + cl);
		}, 'json');
	});
});
