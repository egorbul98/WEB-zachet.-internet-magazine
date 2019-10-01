<style>
	#bg-blockTypeParametr {
		width: 100%;
		min-height: 100%;
		background-color: rgba(0, 0, 0, 0.5);
		overflow: hidden;
		position: fixed;
		display: none;
		top: 0;
	}

	#bg-blockTypeParametr button {
		display: inline;
		margin-left: 5px;

	}

	#bg-blockTypeParametr input {
		border: 1px solid #dedede;

	}

	#blockTypeParametr {
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


<script src='https://code.jquery.com/jquery-3.4.1.min.js'></script>

<div id='bg-blockTypeParametr'>
	<div id="blockTypeParametr">
		<h1>Добаваление типа параметра</h1>
		<form action="" id='formTypeParametr'>
			<p>Название: <input type="text" name="name" id='name' value=""></p>
			<span class="close"></span>
			<button type="button" id='submit'>Добавить в базу данных</button>
			<button type="reset">Очистить</button>
		</form>
	</div>
</div>
<script>
	$("#submit").click(function() {
		var name = $('#name').val();
		if (name != '') {
			$.ajax({
				type: 'GET',
				url: 'obr_type_parametr.php',
				data: {
					'name': name,
					'body_name': 'add'
				},
				success: function(output) {
					$('#divMessage').append("<p>" + output + "</p>");
					showMsg($('#divMessage'),output);
					setTimeout(delMsg,3000,$('#divMessage'),output);
				}
			});
		} else {
			$('#name').css({
				'border': '1px solid red',
			});
		}

	});
	
	$(".close").click(function() {
		hideBlock();
	});
	$('#aAddTypeParametr').on('click', function() {
		showBlock();
	});

	function showBlock() {
		$('#bg-blockTypeParametr').show();
	}

	function hideBlock() {
		$('#bg-blockTypeParametr').hide();
	}
	

</script>
