<style>
	#bg-blockTypeParametrUpdate {
		width: 100%;
		min-height: 100%;
		background-color: rgba(0, 0, 0, 0.5);
		overflow: hidden;
		position: fixed;
		display: none;
		top: 0;
	}

	#bg-blockTypeParametrUpdate button {
		display: inline;
		margin-left: 5px;

	}

	#bg-blockTypeParametrUpdate input {
		border: 1px solid #dedede;

	}

	#blockTypeParametrUpdate {
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

<div id='bg-blockTypeParametrUpdate'>
	<div id="blockTypeParametrUpdate">
		<h1>Изменение названия параметра</h1>
		<form action="" id='formTypeParametr'>
			<input type="hidden" name='type_parametr_id' id='type_parametr_id' value="">
			<p>Название: <input type="text" name="type_parametr_name" id='type_parametr_name' value=""></p>
			<span class="close"></span>
			<button type="button" id='submitUpdate'>Сохранить данные</button>
			<button type="reset">Очистить</button>
		</form>
	</div>
</div>

<script src='https://code.jquery.com/jquery-3.4.1.min.js'></script>

<script>
	$('.aTypeParametrUpdate').click(function() {
		var id = this.getAttribute('data-type_parametr_id');
		var name = this.getAttribute('data-type_parametr_name');
		showBlockTypeParametrUpdate(id, name);
	});

	$("#submitUpdate").click(function() {
		//		var dataSer = $('#formTypeParametr').find('input').serialize();
		var id = $('#type_parametr_id').val();
		var name = $('#type_parametr_name').val();
		if (name != '') {
			$.ajax({
				type: 'GET',
				url: 'obr_type_parametr.php',
				data: {
					'id': id,
					'name': name,
					'body_name': 'update'
				},
				success: function(output) {
					$('#divMessage').append("<p>" + output + "</p>");
					showMsg($('#divMessage'),output);
					setTimeout(delMsg,3000,$('#divMessage'),output);
				}
			});
		} else {
			$('#type_parametr_name').css('border','1px solid red');
		}

	});

	$(".close").click(function() {
		hideBlockTypeParametrUpdate();
	});

	function showBlockTypeParametrUpdate(id, name) {
		$('#bg-blockTypeParametrUpdate').show();
		$('#type_parametr_id').val(id);
		$('#type_parametr_name').val(name);

	}

	function hideBlockTypeParametrUpdate() {
		$('#bg-blockTypeParametrUpdate').hide();
	}
	
</script>
