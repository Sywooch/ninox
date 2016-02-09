function OrdersSearch(form){
    this.constructor = function(){
        this.registerEvents(this.form);
        console.log('wrk');
    };

    this.submitSearchForm = function(){
        e.preventDefault();
        console.log('send');
    };

    this.registerEvents = function(form){
        $(form).on('submit', function(e){
            this.submitSearchForm(e);
        });

        $(form + " input").on('keypress', function(e){
            e.preventDefault();

            if(e.keyCode == 13){
                $(form).submit();
            }
        });
    };
}