<?php
	include("settings/connectDB.php");
?>
<style>
	#bg-blockParametrAdd {
		width: 100%;
		min-height: 100%;
		background-color: rgba(0, 0, 0, 0.5);
		overflow: hidden;
		position: fixed;
		display: none;
		top: 0;
	}

	#bg-blockParametrAdd button {
		display: inline;
		margin-left: 5px;

	}

	#bg-blockParametrAdd input {
		border: 1px solid #dedede;

	}

	#blockParametrAdd {
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

<div id='bg-blockParametrAdd'>
	<div id="blockParametrAdd">
		<h1>Изменение названия параметра</h1>
		<form action="" id='formParametr'>
			<input type="hidden" id='parametr_id' value="">
			<p><select name="selectParametr" id="selectParametr">
					<option value="">Выберите тип параметра</option>
					<?php
					$query=mysql_query("SELECT * FROM type_parametr");
					while($mas=mysql_fetch_array($query)){
						echo "<option value='$mas[id]'>$mas[name]</option>";
					}
					?>
				</select></p>
			<p>Значение: <input type="text" name="parametr_name" id='parametr_name' value=""></p>
			<span class="close"></span>
			<button type="button" id='submitAddParametr'>Сохранить данные</button>
			<button type="reset">Очистить</button>
		</form>
	</div>
</div>

<script src='https://code.jquery.com/jquery-3.4.1.min.js'></script>
<script src='scriptFunction.js'></script>
<script>
	$(document).on('click','#aAddParametr',function(){
		var id = this.getAttribute('data-parametr_id');
		var name = this.getAttribute('data-parametr_name');
		var type_parametr_id = this.getAttribute('data-type_parametr_id');
		showBlockParametrAdd(id, name, type_parametr_id);
		alert('ewfewffew');
	});	

	$("#submitAddParametr").click(function() {
		var id = $('#parametr_id').val();
		var name = $('#parametr_name').val();
		var type_parametr_id = $('#selectParametr').val();
		if (name != '') {

			$.ajax({
				type: 'GET',
				url: 'obr_parametr.php',
				data: {
					'id': id,
					'name': name,
					'type_parametr_id': type_parametr_id,
					'body_name': 'add'
				},
				success: function(output) {
					showMsg($('#divMessage'),output);
					setTimeout(delMsg,3000,$('#divMessage'),output);
				}
			});
		} else {
			$('#parametr_name').css('border', '1px solid red');
		}

	});

	$(".close").click(function() {
		hideBlockParametrAdd();
	});


	function showBlockParametrAdd(id, name, type_parametr_id) {
		$('#bg-blockParametrAdd').show();
		$('#parametr_id').val(id);
		$('#parametr_name').val(name);
		$('#selectParametr').val(type_parametr_id);
	}

	function hideBlockParametrAdd() {
		$('#bg-blockParametrAdd').hide();
	}
	
	

</script>