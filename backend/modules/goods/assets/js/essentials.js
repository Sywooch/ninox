changeState = function(element){
    var target = $(element);
    $.ajax({
        type: 'POST',
        url: '/goods/toggle',
        data: {
            'goodID': target.attr("data-attribute-goodID"),
            'attribute': 'enabled'
        },
        success: function(data){
            target.html(data == "1" ? "Отключить" : "Включить");

            var enabledCheckbox = $("#goodmainform-enabled");

            if(enabledCheckbox.length >= 1){
                enabledCheckbox.prop('checked', data == 1);
            }
        }
    });
};

changeTrashState = function(element){
    var target = $(element);

    $.ajax({
        type: 'POST',
        url: '/goods/toggle',
        data: {
            'goodID': target.attr("data-attribute-goodID"),
            'attribute': 'deleted'
        },
        success: function(data){
            target.html(data == "1" ? "Восстановить" : "Удалить");
        }
    });
};

(function( $ ){
    $.fn.goods = function(options) {
        if(options == undefined || options == null){
            options = {};
        }

        var defaultOptions = {
                stateButtonSelector: '.changeState-btn',
                deleteButtonSelector: '.delete-btn',
                pickUpButtonSelector: '.'
            },
            items = this;

        options = $.extend(defaultOptions, options);

        var trashState = function(item, button){
            $.ajax({
                type: 'POST',
                url: '/goods/toggle',
                data: {
                    goodID: item.getAttribute('data-key'),
                    attribute: 'deleted'
                },
                success: function(data){
                    var deleted = data == 1,
                        thumb = $(item).find('.thumbnail');

                    if(deleted){
                        thumb.addClass('bg-very-danger');
                        thumb[0].setAttribute('data-attribute-deleted', true);
                    }else{
                        if(thumb[0].getAttribute('data-attribute-deleted') !== null)
                            thumb[0].removeAttribute('data-attribute-deleted');

                        thumb.toggleClass('bg-very-danger');
                    }

                    button.innerHTML = deleted ? 'Восстановить' : 'Удалить';
                }
            });
        }, changeState = function(item, button){
            $.ajax({
                type: 'POST',
                url: '/goods/toggle',
                data: {
                    goodID: item.getAttribute('data-key'),
                    attribute: 'enabled'
                },
                success: function(data){
                    var enabled = data == 1,
                        thumb = $(item).find('.thumbnail');

                    if(item.getAttribute('data-attribute-deleted') !== null){
                        thumb.setAttribute('oldClass', item.getAttribute('class'));
                        thumb.addClass('bg-very-danger');
                    }else{
                        thumb.toggleClass(enabled ? 'bg-danger' : 'bg-success');
                        thumb.addClass(enabled ? 'bg-success' : 'bg-danger');
                    }

                    button.html(enabled ? 'Выключить' : 'Включить');
                }
            });
        }, pickUp = function(item){

        },setEvents = function(item){
            $(item).find(options.stateButtonSelector).on('click', function(){
                changeState(item, this);
            });

            $(item).find(options.deleteButtonSelector).on('click', function(){
                trashState(item, this);
            });
        };

        items.toArray().forEach(function(item, i){
            setEvents(item);
        });
    };
})( jQuery );

$("body").on('click', '#changeState', function(){
    changeState(this);
}).on('click', '#changeTrashState', function(){
    changeTrashState(this);
});