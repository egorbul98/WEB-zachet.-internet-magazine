<?php
session_start();

?>


<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<title>Document</title>
	<link rel="stylesheet" href="style.css">
	<script src='https://code.jquery.com/jquery-3.4.1.min.js'></script>
	
</head>

<body>
	<div id='divMessage'></div>
	<header><?php include("include/header.php")?>
	
	</header>
	<nav id='nav'>
		<?php include("include/navigationMenu.php")?>
	</nav>

	<main id='main'>
		<section id="content">
			<?php include('include/contacts-content.php');?>
		</section>
	</main>

	<footer id='footer'>
		<?php include('include/main-footer.php');?>
	</footer>

	<script src="scriptAnimation.js"></script>
	<?php include('include/popup_log.php');?>
	<script src="scriptMiniCart.js"></script>
</body>

</html>