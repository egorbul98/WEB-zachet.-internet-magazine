<?php


?>




<?php 
if(isset($_SESSION['user_id'])&&!empty($_SESSION['user_id'])){
	$str = 'Выйти';
	$auth = 'logout';
}else{
	$str = 'Войти в аккаунт';
	$auth = 'login';
}
?>
<div id='divProfile'>
		<?php
			if(isset($_SESSION['user_id'])){
		?>
		<div id='divUL'>
			<ul>
			<li><a href="" id=''>Добро пожаловать, <?php echo $_SESSION['user_name'];?></a>
				<ul style="display:none;">
	<!--				<li><a href="">Личные данные</a></li>-->
					<li><a href="user_orders.php">Заказы</a></li>
					<li><a href="#" id='showUserData'>Личные данные</a></li>
					<?php 
					if(isset($_SESSION['admin'])&&($_SESSION['admin']==1)){
						echo "<li><a href='admin/index.php'>Административная панель</a></li>";
					}
					?>
				</ul>
			</li>
		</ul>
		</div>
	
	<?php
		}
	?>
</div><!--
--><div id='divBtnsLogin'>
    <button type="button" name='btnLogin' id='btnLogin' data-auth='<?php echo $auth;?>'><?php echo $str;?></button><!--
    --><button type="button" name='btnRegistration' id='btnRegistration'>Регистрация</button>
</div>
<script src='https://code.jquery.com/jquery-3.4.1.min.js'></script>
<script>
	$('#divProfile li').hover(function(){
		$(this).children('ul').fadeIn(300);
	},function(){
		$(this).children('ul').fadeOut(300);
	});
</script>

