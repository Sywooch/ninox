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
        var selector = this.selector + ".kv-expand-detail-row";

        kvInitPlugin(selector + ' #orderpreviewform-deliveryparam', function(){
            if (jQuery(selector + ' #orderpreviewform-deliveryparam').data('depdrop')) {
                jQuery(selector + ' #orderpreviewform-deliveryparam').depdrop('destroy');
            }

            jQuery(selector + ' #orderpreviewform-deliveryparam').depdrop({
                "depends":[
                    "deliveryTypeInput"
                ],
                "initialize": true,
                "params": [
                    "deliveryTypeInput",
                    "deliveryParamInput"
                ],
                "emptyMsg": "варианты отсутствуют",
                "initDepends":[
                    "deliveryTypeInput"
                ],
                "url":'/orders/get-deliveries'
            });
        });


        kvInitPlugin(selector + ' #orderpreviewform-paymentparam', function(){
            if (jQuery(selector + ' #orderpreviewform-paymentparam').data('depdrop')) {
                jQuery(selector + ' #orderpreviewform-paymentparam').depdrop('destroy');
            }

            jQuery(selector + ' #orderpreviewform-paymentparam').depdrop({
                "depends":[
                    "paymentTypeInput"
                ],
                "initialize": true,
                "params":[
                    "paymentTypeInput",
                    "paymentParamInput"
                ],
                "emptyMsg":"варианты отсутствуют",
                "initDepends":[
                    "paymentTypeInput"
                ],
                "url":'/orders/get-payments'
            });
        });
    };
})(jQuery);