(function($){

    var remainingWidth = function () {
        return Math.round(100 - elapsedTime*100/quizOverallTime);
    };

    var elapsedTime = getElapsedTime();
    var quizOverallTime = getQuizOverallTime();
    var resultPage = getResultPage();

    var counterBack = setInterval(function(){
        elapsedTime++;
        if(elapsedTime <= quizOverallTime){
            var d = new Date((quizOverallTime - elapsedTime)*1000);
            $('#time').html('Remaining time: ' + d.toISOString().substr(11,8));
            $('.timer').css('width', remainingWidth(elapsedTime)+'%');
        } else {
            clearInterval(counterBack);
            document.location.href = resultPage;
        }
    }, 1000);

})(jQuery);