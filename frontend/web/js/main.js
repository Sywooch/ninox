/**
 * Created by alone on 10/18/15.
 */
var hasTouch = window.DocumentTouch && document instanceof DocumentTouch;
var isMobile = {
	Android: function() {
		return navigator.userAgent.match(/Android/i);
	},
	BlackBerry: function() {
		return navigator.userAgent.match(/BlackBerry|BB10; Touch/i);
	},
	iOS: function() {
		return navigator.userAgent.match(/iPhone|iPad|iPod/i);
	},
	Opera: function() {
		return navigator.userAgent.match(/Opera Mini|Opera Mobi|Opera Tablet/i);
	},
	Windows: function() {
		return navigator.userAgent.match(/IEMobile/i);
	},
	any: function() {
		return navigator.userAgent.match(/Android|BlackBerry|BB10; Touch|iPhone|iPad|iPod|Opera Mini|Opera Mobi|Opera Tablet|IEMobile/i);
	}
};

String.prototype.isJSON = function(){
	if(this.length && (/^[\],:{}\s]*$/.test(this.replace(/\\["\\\/bfnrtu]/g, '@').
		replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, ']').
		replace(/(?:^|:|,)(?:\s*\[)+/g, '')))){
		return true;
	}else{
		return false;
	}
}

function isTouchMoved(e){
	if(typeof(e.target.isTouchMoved) !== undefined && e.target.isTouchMoved === true){
		e.target.isTouchMoved = false;
		return true;
	}
	return false;
}

function addToCart(item){
	item = $(item);
	var itemId = item.data('itemid');
	var count = item.data('count');
	console.log(count);
	if(itemId && count){
		$.ajax({
			type: 'POST',
			url: '/modifycart',
			data: {
				"itemID": itemId,
				"count": count
			},
			success: function(data){
				$('.buy[data-itemId='+ itemId +']').each(function(){
					$(this).val(texts.itemText.inCart).toggleClass('yellow-button green-button buy open-cart');
				});
				$('.count[data-itemId='+ itemId +']').each(function(){
					$(this).data('incart', data.inCart);
				});
				//updateMinicartInfo();
			}
		});
	}
}

function changeItemCount(item){
	item = $(item);
	var itemId = item.data('itemid');
	var counter = $('.count[data-itemid=\''+ itemId +'\']');
	var maxItemsCount = parseInt(counter.data('store'));
	var itemsCount = parseInt(counter.val().replace(/\D+/g, ''));
	var count = parseInt(item.data('count'));
	var incart = parseInt(counter.data('incart'));

	if((maxItemsCount > itemsCount && count > 0) || (1 < itemsCount && count < 0)){
		itemsCount += count;
		if(incart){
			console.log(incart);
			$.ajax({
				type: 'POST',
				url: '/modifycart',
				data: {
					'itemID': itemId,
					'count': count
				},
				success: function(data){
					console.log(data);
					//updateCart(this, data);
				}
			});
		}else{
			$('.buy[data-itemId='+ itemId +']').each(function(){
				$(this).data('count', itemsCount);
				console.log('dtcnt: ' + $(this).data('count'));
			});
		}
		$('.count[data-itemId='+ itemId +']').each(function(){
			$(this).val(itemsCount).data('incart', incart ? itemsCount : incart);
		});
	}else if(maxItemsCount < itemsCount){
		if(!$(item).data('tooltipsy')){
			$(item).tooltipsy({
				alignTo: 'element',
				offset: [0, -10],
				className: 'noMoreItemTooltip',
				content: texts.itemText.noMoreItems,
				showEvent: '',
				hideEvent: hasTouch? 'touchstart' : 'mouseleave',
				show: function(e, $el){
					$el.fadeIn(100);
				},
				hide: function(e, $el){
					$el.fadeOut(100);
				}
			});
		}
		$(item).data('tooltipsy').show();
	}else if(count == 0){
		item.disabled = true;
		$.ajax({
			type: 'POST',
			url: '/modifycart',
			data: {
				'itemID': itemId,
				'count': count
			},
			success: function(data){
				//updateCart(itemId, true);
				$('.open-cart[data-itemId='+ itemId +']').each(function(){
					$(this).val(texts.itemText.buy).toggleClass('green-button yellow-button open-cart buy').data('count', 1);
				});
				$('.count[data-itemId='+ itemId +']').each(function(){
					$(this).val(1).data('incart', 0);
				});
				$('#modal-cart .grid-view [data-key="' + itemId + '"]').each(function(){
					$(this).remove();
				});
			}
		});
	}
}

function openCart(){
	document.location = document.location.href.replace(/#.*/, '') + '#modalCart';
}

function getCart(){
	$.pjax.reload({container: '#cart-gridview-pjax'});
}

function cartScroll(){
	$('#modal-cart .grid-view').perfectScrollbar({maxScrollbarLength:20});
}