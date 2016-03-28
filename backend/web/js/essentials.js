$.urlParam = function(name){
    var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
    if (results==null){
        return null;
    }
    else{
        return results[1] || 0;
    }
};

(function($){
    $.fn.orderPreviewListeners = function() {
        if($(this).find("#history-globalmoney, #history-moneyconfirmed").data("bootstrapSwitch")){
            $(this).find("#history-globalmoney, #history-moneyconfirmed").bootstrapSwitch("destroy");
        }

        $(this).find("#history-globalmoney, #history-moneyconfirmed").bootstrapSwitch({"onText":"Да","offText":"Нет","animate":true,"indeterminate":false,"disabled":false,"readonly":false});
    };
})(jQuery);