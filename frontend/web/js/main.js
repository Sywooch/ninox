/**
 * Created by alone on 10/18/15.
 */
var hasTouch = 'ontouchstart' in document.documentElement;
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

if("document" in self && !("classList" in document.createElement("_"))){
	(function(view){
		"use strict";
		if(!('Element' in view)) return;
		var
			classListProp = "classList"
			, protoProp = "prototype"
			, elemCtrProto = view.Element[protoProp]
			, objCtr = Object
			, strTrim = String[protoProp].trim || function(){
				return this.replace(/^\s+|\s+$/g, "");
			}
			, arrIndexOf = Array[protoProp].indexOf || function(item){
				var
					i = 0
					, len = this.length
					;
				for (; i < len; i++) {
					if (i in this && this[i] === item) {
						return i;
					}
				}
				return -1;
			}
		// Vendors: please allow content code to instantiate DOMExceptions
			, DOMEx = function(type, message){
				this.name = type;
				this.code = DOMException[type];
				this.message = message;
			}
			, checkTokenAndGetIndex = function(classList, token){
				if (token === "") {
					throw new DOMEx(
						"SYNTAX_ERR"
						, "An invalid or illegal string was specified"
					);
				}
				if(/\s/.test(token)){
					throw new DOMEx(
						"INVALID_CHARACTER_ERR"
						, "String contains an invalid character"
					);
				}
				return arrIndexOf.call(classList, token);
			}
			, ClassList = function(elem){
				var
					trimmedClasses = strTrim.call(elem.getAttribute("class") || "")
					, classes = trimmedClasses ? trimmedClasses.split(/\s+/) : []
					, i = 0
					, len = classes.length
					;
				for(; i < len; i++){
					this.push(classes[i]);
				}
				this._updateClassName = function(){
					elem.setAttribute("class", this.toString());
				};
			}
			, classListProto = ClassList[protoProp] = []
			, classListGetter = function(){
				return new ClassList(this);
			}
			;
// Most DOMException implementations don't allow calling DOMException's toString()
// on non-DOMExceptions. Error's toString() is sufficient here.
		DOMEx[protoProp] = Error[protoProp];
		classListProto.item = function(i){
			return this[i] || null;
		};
		classListProto.contains = function(token){
			token += "";
			return checkTokenAndGetIndex(this, token) !== -1;
		};
		classListProto.add = function(){
			var
				tokens = arguments
				, i = 0
				, l = tokens.length
				, token
				, updated = false
				;
			do{
				token = tokens[i] + "";
				if(checkTokenAndGetIndex(this, token) === -1){
					this.push(token);
					updated = true;
				}
			}
			while(++i < l);

			if(updated){
				this._updateClassName();
			}
		};
		classListProto.remove = function(){
			var
				tokens = arguments
				, i = 0
				, l = tokens.length
				, token
				, updated = false
				;
			do{
				token = tokens[i] + "";
				var index = checkTokenAndGetIndex(this, token);
				if (index !== -1) {
					this.splice(index, 1);
					updated = true;
				}
			}
			while(++i < l);

			if(updated){
				this._updateClassName();
			}
		};
		classListProto.toggle = function(token, force){
			token += "";

			var
				result = this.contains(token)
				, method = result ?
					force !== true && "remove"
					:
					force !== false && "add"
				;

			if(method){
				this[method](token);
			}

			return !result;
		};
		classListProto.toString = function(){
			return this.join(" ");
		};

		if(objCtr.defineProperty){
			var classListPropDesc = {
				get: classListGetter
				, enumerable: true
				, configurable: true
			};
			try{
				objCtr.defineProperty(elemCtrProto, classListProp, classListPropDesc);
			}catch(ex){ // IE 8 doesn't support enumerable:true
				if(ex.number === -0x7FF5EC54){
					classListPropDesc.enumerable = false;
					objCtr.defineProperty(elemCtrProto, classListProp, classListPropDesc);
				}
			}
		} else if(objCtr[protoProp].__defineGetter__){
			elemCtrProto.__defineGetter__(classListProp, classListGetter);
		}
	}(self));
}

String.prototype.isJSON = function(){
	if(this.length && (/^[\],:{}\s]*$/.test(this.replace(/\\["\\\/bfnrtu]/g, '@').
		replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, ']').
		replace(/(?:^|:|,)(?:\s*\[)+/g, '')))){
		return true;
	}else{
		return false;
	}
}

Element.prototype.remove = function(){
	this.parentElement.removeChild(this);
}

NodeList.prototype.remove = HTMLCollection.prototype.remove = function(){
	for(var i = 0, len = this.length; i < len; i++){
		if(this[i] && this[i].parentElement) {
			this[i].parentElement.removeChild(this[i]);
		}
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
	var itemId = item.getAttribute('data-itemId');
	var count = item.getAttribute('data-count');
	if(itemId && count){
		$.ajax({
			type: 'POST',
			url: '/addtocart',
			data: {
				"itemID": itemId,
				"count": count
			},
			success: function(data){
				$('.buy[data-itemId='+ itemId +']').each(function(){
					this.value = texts.itemText.inCart;
					this.classList.remove('yellow-button');
					this.classList.add('green-button');
					this.classList.remove('buy');
					this.classList.add('open-cart');
				});
				$('.count[data-itemId='+ itemId +']').each(function(){
					this.setAttribute('data-inCart', data);
				});
				//updateMinicartInfo();
			}
		});
	}
}

function changeItemCount(item){
	var itemId = item.getAttribute('data-itemId');
	var counter = document.querySelector('.count[data-itemId=\''+ itemId +'\']');
	var maxItemsCount = parseInt(counter.getAttribute('data-store'));
	var itemsCount = parseInt(counter.value.replace(/\D+/g, ''));
	var count = parseInt(item.getAttribute('data-count'));
	var inCart = parseInt(counter.getAttribute('data-inCart'));
	if((maxItemsCount > itemsCount && count > 0) || (1 < itemsCount && count < 0)){
		itemsCount += count;
		if(inCart){
			$.ajax({
				type: 'POST',
				url: '/addtocart',
				data: {
					'itemID': itemId,
					'count': count
				},
				success: function(data){
					console.log(data);
					//updateCart(0, false);
				}
			});
		}else{
			$('.buy[data-itemId='+ itemId +']').each(function(){
				this.setAttribute('data-count', itemsCount);
			});
		}
		$('.count[data-itemId='+ itemId +']').each(function(){
			this.value = itemsCount;
			this.setAttribute('data-inCart', inCart ? itemsCount : inCart);
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
	}
}

function removeFromCart(item){
	var itemId = item.getAttribute('data-itemId');
	item.disabled = true;
	$.ajax({
		type: 'POST',
		url: '/removefromcart',
		data: {
			'itemID': itemId
		},
		success: function(data){
			console.log(data);
			//updateCart(itemId, true);
			$('.open-cart[data-itemId='+ itemId +']').each(function(){
				this.value = texts.itemText.buy;
				this.classList.remove('green-button');
				this.classList.add('yellow-button');
				this.classList.remove('open-cart');
				this.classList.add('buy');
				this.setAttribute('data-itemId', itemId);
				this.setAttribute('data-count', '1');
			});
			$('.count[data-itemId='+ itemId +']').each(function(){
				this.value = 1;
				this.setAttribute('data-inCart', '0');
			});
			document.querySelector('[data-remodal-id="modalCart"] [data-key="' + itemId + '"]').remove();
		}
	});
}

function openCart(){
	document.location = document.location.href.replace('#', '') + '#modalCart';
}

function getCart(e){
	$.ajax({
		type: 'POST',
		url: '/getcart',
		success: function(data){
			console.log(data);
			e.currentTarget.innerHTML = data;
		}
	});
}