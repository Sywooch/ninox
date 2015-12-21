var changeState = function(e){
    var target = e.currentTarget;
    $.ajax({
        type: 'POST',
        url: '/goods/changestate',
        data: {
            'GoodID': e.currentTarget.getAttribute("data-attribute-goodID")
        },
        success: function(data){
            if(data.length >= "1"){
                target.innerHTML = data == "1" ? "Отключить" : "Включить";
                if(document.querySelector("#good-show_img") != null && document.querySelector("#good-show_img") != undefined){
                    var el = document.querySelector("#good-show_img");
                    el.querySelector("input[value='" + data + "']").checked = true;
                }
            }
        }
    });
}, changeTrashState = function(e){
    var target = e.currentTarget;
    $.ajax({
        type: 'POST',
        url: '/goods/workwithtrash',
        data: {
            'GoodID': e.currentTarget.getAttribute("data-attribute-goodID")
        },
        success: function(data){
            if(data.length >= "1"){
                target.innerHTML = data == "1" ? "Восстановить" : "Удалить";
            }
        }
    });
};