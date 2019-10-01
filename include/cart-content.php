<?php
require('settings/connectDB.php');
require_once('settings/function.php')

?>


<style>
	#divCart {
		padding: 5px;
	}
	#divCart table{
		border-spacing: 8px;
	}

	#divCart #divListProducts,
	#divCart #divOrder {
		display: inline-block;
	}

	#divCart #divListProducts {

		width: 65%;
		vertical-align: top;
	}

	#divCart #divOrder {
		width: 35%;
		text-align: right;
	}

	#divOrderInputs {
		text-align: left;
	}

	#divOrder {
		box-sizing: border-box;
		padding: 20px;
		background-color: #746a65;
	}

	#divCart .btnMinusProductCart,
	#divCart .btnPlusProductCart,
	#divCart .spanCount {
		display: inline-block;
		width: 32%;
	}

	#divCart td[colspan='7'],
	#divCart .tdSum {
		text-align: right;
	}

	#divCart td[colspan='2'],
	#divCart td[colspan='3'],
	#divCart .tdCountProduct {
		text-align: center;
	}

	#divCart #divEmptyCart p a {
		font-size: 26px;
	}
	#divEmptyCart{
		margin-top: 15px;
		text-align: center;
	}
	#divOrder input,
	#divOrder button,
	#divOrder p,
	#divListProducts p {
		font-size: 20px;
	}

	#divOrder input {
		background-color: rgba(255, 255, 255, 1);
		box-sizing: border-box;
		width: 100%;
		margin: 0 0 15px 0;
		padding: 5px;
		border-radius: 10px;
		border: 1px solid #747770;
	}

	#divOrder input[type='checkbox'] {
		width: auto;
		margin: 0px 10px 0 0;
	}

	#btnOrderBuy {
		margin-top: 10px;
		width: 50%;
	}

	#divOrder hr {
		height: 2px;
		background-color: #f7f0f0;
		/*		background-color: rgba(62, 57, 54, 1);*/
		margin: 10px 0 15px;
	}

	.error {
		border: 1px solid red;
	}

	.btnRemoveProductFromCart,
	.btnPlusProductCart,
	.btnMinusProductCart {
		border-radius: 20px;
		padding: 0 5px;
		/*		height: 20px;*/
		/*		vertical-align: top;*/
	}

	.btnRemoveProductFromCart {
		margin-right: 10px;
		padding: 0 5px;
	}


	#divOrderInputs input[type='number'] {
		-moz-appearance: textfield;
	}

	#divOrderInputs input[type='number']::-webkit-inner-spin-button {
		display: none;
	}

</style>

<div id='divCart'>
	<div id='divListProducts'></div><!--
	--><div id='divOrder'>
		<form method='post' action="cart.php" id='formOrderCart'>
			<div id='divOrderInputs'>
				<?php
				if(isset($_SESSION['user_id'])&&!empty($_SESSION['user_id'])){
					$id=$_SESSION['user_id'];
					$query=mysql_query("SELECT * FROM user WHERE id='$id'");
					$mas=mysql_fetch_array($query);
				}
			?>
				<p><input type="text" name="orderName" id='orderName' required placeholder="Введите имя" value="<?php echo $mas['name'];?>"></p>
				<p><input type="text" name="orderLname" id='orderLname' required placeholder="Введите фамилию" value="<?php echo $mas['lname'];?>"></p>
				<p><input type="text" name="orderAddress" id='orderAddress' required placeholder="Введите адрес"></p>
				<p><input type="email" name="orderEmail" id='orderEmail' required placeholder="Введите E-mail" value="<?php echo $mas['email'];?>"></p>
				<p><input type="number" name="orderTel" id='orderTel' required placeholder="Введите телефон"></p>
				<p><input type="checkbox" name='orderPersonalData' id='orderPersonalData' value="1" checked required>Я принимаю публичную оферту и даю согласие на обработку и хранение персональных данных</p>
			</div>
			<hr>
			<p>Товары(<span id='spanSumCountCart'></span>) Итого: <span class='spanSumPriceCart'></span> руб.</p>
			<button type="submit" id='btnOrderBuy'>Оформить заказ</button>
		</form>

	</div>
</div>


<script>
	$(document).ready(function() {
		showCart();
	});

	function updateCartLS() {
		localStorage.setItem('cart', JSON.stringify(cart));
	}

	$(document).on('click', '#btnClearCart', function() {
		localStorage.removeItem('cart');
		showCart();
	});
	$(document).on('click', '.btnMinusProductCart', function() {
		var id = $(this).attr('data-product_id');
		if ($(this).siblings('.spanCount').text() > 0) {
			cart[id]--;
			$(this).siblings('.spanCount').text(cart[id]);
			updateCartLS();
			showMiniCart();
			setTimeout(showCart, 2500);
		}
	});
	$(document).on('click', '.btnPlusProductCart', function() {//spanCount - количество, которое покупают, data-count - количество на складе
		console.log(parseFloat($(this).siblings('.spanCount').text()));
		console.log(parseFloat($(this).attr('data-sklad')));
		if (parseFloat($(this).siblings('.spanCount').text()) < parseFloat($(this).attr('data-sklad'))) {
			var id = $(this).attr('data-product_id');
			cart[id]++;
			$(this).siblings('.spanCount').text(cart[id]);
			updateCartLS();
			showMiniCart();
			setTimeout(showCart, 2500);
		} else {
			alert('Данного товара больше нет на складе');
		}
	});
	$(document).on('click', '.btnRemoveProductFromCart', function() {//
		
		var p_id = $(this).attr('data-product_id'); 
		delete cart[p_id];
		updateCartLS();
		showMiniCart();
		showCart();
		alert(p_id);
		
	});

	
	$(document).on('click', '#btnOrderBuy', function(e) {
		var valid = this.form.checkValidity();
		if (valid) {
			e.preventDefault();
			var products = $('.trProduct');
			var arrayProducts = {};
			var arrayProduct = {};
			for(var i=0; i<products.length; i++){
				arrayProducts[i]={
					'product_id':$(products[i]).attr('data-product_id'),
					'product_price':$(products[i]).attr('data-product_price'),
					'count':$(products[i]).attr('data-count')
				}
			}
			var json = JSON.stringify(arrayProducts);
			var cost = $('#sumPriceCart').attr('data-cost');
			var Name = $('#orderName').val();
			var Lname = $('#orderLname').val();
			var Address = $('#orderAddress').val();
			var Email = $('#orderEmail').val();
			var Tel = $('#orderTel').val();
			var PersonalData = $('#orderPersonalData').val();
			if(PersonalData==1){
				$.ajax({
					url: 'obr_orderCart.php',
					type: 'POST',
					data: {
						'products': json,
						'cost':cost,
						'name':Name,
						'lname':Lname,
						'address':Address,
						'email':Email,
						'tel':Tel
					},
					success: function(output) {
						alert(output);
						$('#btnClearCart').trigger('click');
					}
				});
			}
			
		}

	});

	function showCart() {
		if ($.isEmptyObject(JSON.parse(localStorage.getItem('cart')))) {
			$('#divCart').html("<div id='divEmptyCart'><p>Ваша корзина пуста</p><p><a href='catalog.php'>Перейти к покупкам</a></p></div>");
		} else {
			$.ajax({
				url: 'obr_getCart.php',
				type: 'POST',
				data: {
					'cart': JSON.parse(localStorage.getItem('cart')),
				},
				success: function(output) {
					var json = JSON.parse(output);
					var out = "";
					var resultSum = 0;
					var resultCount = 0;
					out += "<table>";
					out += "<tr>";
					out += "<td></td>";
					out += "<td><p>Название товара</p></td>";
					out += "<td colspan='2'><p>Цена</p></td>";
					out += "<td class='tdCountProduct'><p>Количество</p></td>";
					out += "<td = colspan='3'><p>Сумма</p></td>";
					out += "</tr>";
					for (value in json) {
						var product_id = json[value]['id'];
						var name = json[value]['name'];
						var price = parseFloat(json[value]['price']);
						var count = parseFloat(json[value]['count']);
						var sklad = parseFloat(json[value]['sklad']);
						var sum = parseFloat(json[value]['sum']);
						resultSum += sum;
						resultCount += count;
						out += "<tr class='trProduct' data-product_id='" + product_id + "' data-product_price='" + price + "' data-count='"+count+"'>";
						out += "<td><button type='button' data-product_id='"+product_id+"' class='btnRemoveProductFromCart bgGrey'>Удалить</button></td>";
						out += "<td><a href='catalog.php?productId=" + product_id + "'>" + name + "</a></td>";
						out += "<td class='tdPriceProduct'><span class='spanPrice'>" + price + "</span> руб.</td>";
						out += "<td>*</td>";
						out += "<td class='tdCountProduct'><button type='button' data-product_id='" + product_id + "'  data-count='" + count + "'class='btnMinusProductCart bgGrey'>-</button><span class='spanCount'>" + count + "</span><button type='button' data-product_id='" + product_id + "' data-sklad='" + sklad + "' class='btnPlusProductCart bgGrey'>+</button></td>";
						out += "<td>=</td>";
						out += "<td class='tdSum'><span class='spanResult'>" + sum + "</span> руб.</td>";
						out += "</tr>";
					}
					out += "<tr><td id='sumPriceCart' data-cost='"+resultSum+"' colspan='7'>Общая сумма: <span class='.spanSumPriceCart'>" + resultSum + "</span> руб.</td></tr>";
					out += "</table>";
					out += "<button type='button' id='btnClearCart' class='bgGrey'>Очистить корзину</button>"
					$('#divCart #divListProducts').html(out);
					$('.spanSumPriceCart').text(resultSum);
					$('#spanSumCountCart').text(resultCount);
				}
			});
		}

	}

</script>
