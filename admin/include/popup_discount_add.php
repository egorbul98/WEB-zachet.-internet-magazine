<?php
include("settings/connectDB.php");
?>
<style>
	#bg-blockDiscountAdd {
		width: 100%;
		min-height: 100%;
		background-color: rgba(0, 0, 0, 0.5);
		overflow: hidden;
		position: fixed;
		display: none;
		top: 0;
	}

	#bg-blockDiscountAdd button {
		display: inline;
		margin-left: 5px;

	}

	#bg-blockDiscountAdd input {
		border: 1px solid #dedede;

	}

	#blockDiscountAdd {
		text-align: center;
		position: absolute;
		width: 25%;
		padding: 10px;
		background-color: #f5f5f5;
		border-radius: 2px;
		box-shadow: 0px 0px 5px 0px #f5f5f5;
		top: 50%;
		left: 50%;
		transform: translate(-50%, -50%);
	}

</style>

<div id='bg-blockDiscountAdd'>
	<div id="blockDiscountAdd">
		<h1>Добавление скидки</h1>
		<form action="" id='formDiscount'>
			<p>Размер скидки: <input type="number" name="numberDiscount" id='numberDiscount' max="99" min="0"></p>
			<span class="close"></span>
			<button type="button" id='submitAddDiscount'>Добавить данные</button>
			<button type="reset">Очистить</button>
		</form>
	</div>
</div>

<script src='https://code.jquery.com/jquery-3.4.1.min.js'></script>
<script>
	$(document).on('input', '#numberDiscount', function(){
		$(this).css("border", "none");
	});
	
	$("#submitAddDiscount").click(function() {
		var discount = $('#numberDiscount').val();
		if (discount == '') {
			$('#numberDiscount').css("border", "1px solid red");
		} else {
			$.ajax({
				type: 'POST',
				url: 'obr_discount.php',
				data: {
					'discount': discount,
					'body_name': 'add'
				},
				success: function(output) {
//					$('#divMessage').append("<p>" + output + "</p>");
					showMsg($('#divMessage'), output);
					setTimeout(delMsg, 3000, $('#divMessage'), output);
				}
			});

		}

	});

	$(".close").click(function() {
		hideBlockDiscount();
	});
	$('#aAddDiscount').on('click', function() {
		showBlockDiscount();
	});

	function showBlockDiscount() {
		$('#bg-blockDiscountAdd').show();
	}

	function hideBlockDiscount() {
		$('#bg-blockDiscountAdd').hide();
	}

</script>
