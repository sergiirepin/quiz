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
            console.log(elapsedTime);
            $('.timer').css('width', remainingWidth(elapsedTime)+'%');
            console.log(remainingWidth(elapsedTime));
            console.log($('.timer').css('width'));
        } else {
            clearInterval(counterBack);
            document.location.href = resultPage;
        }
    }, 1000);

})(jQuery);