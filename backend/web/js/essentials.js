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
        if($(this).find("input[data-krajee-bootstrapswitch]").data("bootstrapSwitch")){
            $(this).find("input[data-krajee-bootstrapswitch]").bootstrapSwitch("destroy");
        }

        $(this).find("input[data-krajee-bootstrapswitch]").bootstrapSwitch({"onText":"Да","offText":"Нет","animate":true,"indeterminate":false,"disabled":false,"readonly":false});
    };
})(jQuery);