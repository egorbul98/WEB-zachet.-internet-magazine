/*eslint-env browser*/

var cart= {};
	
	$(document).ready(function(){
		checkCart();
		showMiniCart();
	});
	function checkCart(){
		//Проверка корзины
		if(localStorage.getItem('cart')!=null){
			cart = JSON.parse(localStorage.getItem('cart'));
		}
	}
	function showMiniCart(){
        var sum=0;
		for(var i in cart){
            sum+=cart[i];
		}
		$('#miniCart p').html("В корзине "+sum+" товаров");
	}