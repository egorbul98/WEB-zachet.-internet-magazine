<?php
	include("settings/connectDB.php");
?>
<style>
	#bg-blockParametrUpdate {
		width: 100%;
		min-height: 100%;
		background-color: rgba(0, 0, 0, 0.5);
		overflow: hidden;
		position: fixed;
		display: none;
		top: 0;
	}

	#bg-blockParametrUpdate button {
		display: inline;
		margin-left: 5px;

	}

	#bg-blockParametrUpdate input {
		border: 1px solid #dedede;

	}

	#blockParametrUpdate {
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

<div id='bg-blockParametrUpdate'>
	<div id="blockParametrUpdate">
		<h1>Изменение названия параметра</h1>
		<form action="" id='formParametr'>
			<input type="hidden" id='parametr_idUpdate' value="">
			<p><select name="selectParametrUpdate" id="selectParametrUpdate">
					<option value="">Выберите тип параметра</option>
					<?php
					$query=mysql_query("SELECT * FROM type_parametr");
					while($mas=mysql_fetch_array($query)){
						echo "<option value='$mas[id]'>$mas[name]</option>";
					}
					?>
				</select></p>
			<p>Значение: <input type="text" name="parametr_nameUpdate" id='parametr_nameUpdate' value=""></p>
			<span class="close"></span>
			<button type="button" id='submitUpdateParametr'>Сохранить данные</button>
			<button type="reset">Очистить</button>
		</form>
	</div>
</div>

<script src='https://code.jquery.com/jquery-3.4.1.min.js'></script>
<script>
	$('.aParametrUpdate').click(function() {
		var id = this.getAttribute('data-parametr_id');
		var name = this.getAttribute('data-parametr_name');
		var type_parametr_id = this.getAttribute('data-type_parametr_id');
		showBlockParametrUpdate(id, name, type_parametr_id);
	});

	$("#submitUpdateParametr").click(function() {
		var id = $('#parametr_idUpdate').val();
		var name = $('#parametr_nameUpdate').val();
		var type_parametr_id = $('#selectParametrUpdate').val();
		if (name != '') {

			$.ajax({
				type: 'GET',
				url: 'obr_parametr.php',
				data: {
					'id': id,
					'name': name,
					'type_parametr_id': type_parametr_id,
					'body_name': 'update'
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
		hideBlockParametrUpdate();
	});


	function showBlockParametrUpdate(id, name, type_parametr_id) {
		$('#bg-blockParametrUpdate').show();
		$('#parametr_idUpdate').val(id);
		$('#parametr_nameUpdate').val(name);
		$('#selectParametrUpdate').val(type_parametr_id);
	}

	function hideBlockParametrUpdate() {
		$('#bg-blockParametrUpdate').hide();
	}
	
	

</script>
