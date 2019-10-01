<div id='header-logo'><a href="index.php"><img src="image/logoHeader.png" alt=""></a></div>

<div id='header-bottom'>
	<div id='header-bottom-left'>
	<form action="catalog.php?" method="post">
		<div id='divSearch'>
			<input name='searh' type="searh" placeholder='Поиск товара'>
			<button type="submit">Найти</button>
		</div><!--
	    
		--><div id='miniCart'>
			<a href="cart.php"><img src="image/icons8-cart-1.png" alt="Корзина"></a><p id='countCart'>В корзине сейчас: 0</p>
		</div>
	</form>
		
		
	</div><!--
--><div id='header-bottom-right'>
		<div id="divLogin">
				<?php include('include/login.php');?>
	    </div>
	</div>
</div>