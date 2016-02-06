function OrdersSearch(form){
    this.constructor = function(){
        registerEvents(form);
        console.log('wrk');
    };

    submitSearchForm = function(){
        e.preventDefault();
        console.log('send');
    },
        registerEvents = function(form){
        $(form).on('submit', function(e){
            submitSearchForm(e);
        })
    };
}