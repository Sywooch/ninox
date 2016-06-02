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
    $.fn.orderPreviewListeners = function(id) {
        var selector = this.selector + ".kv-expand-detail-row";

        kvInitPlugin(selector + ' select[name="OrderPreviewForm[deliveryParam]"]', function(){
            if (jQuery(selector + ' select[name="OrderPreviewForm[deliveryParam]"]').data('depdrop')) {
                jQuery(selector + ' select[name="OrderPreviewForm[deliveryParam]"]').depdrop('destroy');
            }

            jQuery(selector + ' select[name="OrderPreviewForm[deliveryParam]"]').depdrop({
                "depends":[
                    "deliveryTypeInput-" + id
                ],
                "initialize": true,
                "params": [
                    "deliveryParamInput-" + id
                ],
                "emptyMsg": "варианты отсутствуют",
                "initDepends":[
                    "deliveryTypeInput-" + id
                ],
                "url":'/orders/get-deliveries'
            });
        });


        kvInitPlugin(selector + ' select[name="OrderPreviewForm[paymentParam]"]', function(){
            if (jQuery(selector + ' select[name="OrderPreviewForm[paymentParam]"]').data('depdrop')) {
                jQuery(selector + ' select[name="OrderPreviewForm[paymentParam]"]').depdrop('destroy');
            }

            jQuery(selector + ' select[name="OrderPreviewForm[paymentParam]"]').depdrop({
                "depends":[
                    "paymentTypeInput-" + id
                ],
                "initialize": true,
                "params":[
                    "paymentParamInput-" + id
                ],
                "emptyMsg":"варианты отсутствуют",
                "initDepends":[
                    "paymentTypeInput-" + id
                ],
                "url":'/orders/get-payments'
            });
        });
    };
})(jQuery);