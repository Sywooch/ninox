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

var keysdown = {};

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
	if(itemId && count){
		$.ajax({
			type: 'POST',
			url: '/modifycart',
			data: {
				"itemID": itemId,
				"count": count
			},
			success: function(data){
				$('.buy[data-itemId='+ itemId +']')
					.val(texts.itemText.inCart)
					.toggleClass('yellow-button green-button buy open-cart');
				$('.count[data-itemId='+ itemId +']').data('incart', count);
				updateCart(data);
			}
		});
	}
}

function changeItemCount(item){
	var itemId = item.data('itemid');
	var maxItemsCount, itemsCount, incart, count, tempRes, val;
	var classList = item.attr('class').split(/\s+/)
	$.each(classList, function(index, className){
		switch(className){
			case 'remove-item':
				$('.open-cart[data-itemId='+ itemId +']')
					.val(texts.itemText.buy)
					.toggleClass('green-button yellow-button open-cart buy')
					.data('count', 1);
				$('#modal-cart .grid-view [data-key="' + itemId + '"]').remove();
				maxItemsCount = 0;
				itemsCount = 1;
				incart = 0;
				count = 0;
				break;
			case 'plus':
			case 'minus':
				count = className == 'plus' ? 1 : -1;
				item = item.parent().find('.count');
			case 'count':
				tempRes = parseInt(item.val()) || 1;
				maxItemsCount = parseInt(item.data('store'));
				val = parseInt(item.data('value'));
				if((tempRes >= maxItemsCount && maxItemsCount == val || tempRes == val) && className == 'count'){
					item.val(val);
					break;
				}
				count = className == 'count' ?
					(tempRes >= maxItemsCount && maxItemsCount == val ?
						1 : (tempRes == val ? -1 : (maxItemsCount > tempRes ?
							tempRes - val : maxItemsCount - val))) : count;
				itemsCount = val + count;
				incart = parseInt(item.data('incart'));
				break;
		}
	});

	if((maxItemsCount >= itemsCount && count > 0) || (1 <= itemsCount && count < 0) || count == 0){
		if(incart || count == 0){
			$.ajax({
				type: 'POST',
				url: '/modifycart',
				data: {
					'itemID': itemId,
					'count': count
				},
				success: function(data){
					updateCart(data);
				}
			});
		}else{
			$('.buy[data-itemId='+ itemId +']').data('count', itemsCount);
		}
		$('.count[data-itemId='+ itemId +']')
			.val(itemsCount)
			.data('value', itemsCount)
			.data('incart', incart ? itemsCount : incart);
		$('.plus[data-itemId='+ itemId +']').toggleClass('inhibit', maxItemsCount == itemsCount);
		$('.minus[data-itemId='+ itemId +']').toggleClass('inhibit', itemsCount == 1);
	}
}

function openCart(){
	document.location = document.location.href.replace(/#.*/, '') + '#modalCart';
}

function getCart(){
	$.pjax.reload({container: '#cart-gridview-pjax', url: '/getcart', push: false, replace: false});
}

function cartScroll(){
	$('#modal-cart .grid-view').perfectScrollbar({maxScrollbarLength:20});
}

function updateCart(data){
	for(var i in data){
		switch(i){
			case 'wholesale':
				$('#modal-cart').toggleClass('wholesale', data[i]).toggleClass('retail', !data[i]);
				break;
			case 'count':
				$('#modal-cart').toggleClass('empty', !data[i]).removeClass(function(){
					return data[i] ? '' : 'wholesale retail';
				});
			case 'count-ext':
				$('.desire-basket .items-' + i).text(data[i]);
				break;
			case 'items':
				for(var j in data[i]){
					for(var k in data[i][j]){
						var item = $('#modal-cart .grid-view [data-key="' + j + '"]');
						switch(k){
							case 'discount':
								item.find('.item-price-' + k).toggleClass('disabled', data[i][j][k] == 0);
								item.find('.item-prices').toggleClass('discounted', data[i][j][k] != 0);
							default:
								item.find('.item-price-' + k).text(data[i][j][k]);
								break;
						}
					}
				}
				break;
			case 'button':
				$('#modal-cart .cart-' + i).attr('disabled', data[i]);
				break;
			default:
				$('#modal-cart .amount-' + i).text(data[i]);
				break;
		}
	}
}

function setItemRate(item){
	$.ajax({
		type: 'POST',
		url: '/setitemrate',
		data: {
			'itemID': item.data('itemid'),
			'rate': item.data('rate')
		},
		success: function(data){
			$(item.parent().find('.icon-star').get().reverse()).removeClass('current').addClass(function(index){
				return index + 1 <= data && data < index + 2 ? 'current' : '';
			});
		}
	});
}

function addToWishlist(item){
	if(item.hasClass('is-guest')){
		document.location = document.location.href.replace(/#.*/, '') + '#loginModal';
		return false;
	}
	$.ajax({
		type: 'POST',
		url: '/addtowishlist',
		data: {
			'itemID': item.data('itemid')
		},
		success: function(data){
			if(data == true){
				item.addClass('green');
			}
		}
	});
}