(function($){
    $('button').click(function(e){

    	var checked = $('#answers li input').map(function() {
            return $(this).prop('checked')
        }).toArray();

        if(this.id == 'app_question_next') {
            $('#answers li input').removeAttr('required');
            Cookies.remove('checked');
        } else if ($.inArray(true, checked) == -1) {
            $('#answers li input').first().prop('required', true);
        } else {
            $('#answers li input').removeAttr('required');
            Cookies.set('checked', checked);
        }
    });

    $('#app_quiz_question_next').click(function(e){
        var checked = $('#answers li input').map(function(){ return $(this).prop('checked')});
        $('#answers li input').removeAttr('required');
        Cookies.remove('checked');
        if($.inArray(true, checked) == -1){
            $('#answers li input').first().prop('required', true);
        }
    });

    var checked = $.makeArray(Cookies.getJSON('checked'));

	$.map(checked, function(el, i){
		$($('#answers li input')[i]).prop('checked', el)
	});

})(jQuery);