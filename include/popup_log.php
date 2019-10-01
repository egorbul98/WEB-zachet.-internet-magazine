<style>
	#bgPopupLog {
		position: fixed;
		top: 0;
		width: 100%;
		min-height: 100%;
		background-color: rgba(0, 0, 0, 0.3);
		overflow: hidden;
		z-index: 1;
		display: none;
	}

	#PopupLog {
		text-align: center;
		position: absolute;
		width: 400px;
		background-color: rgba(255, 255, 255, 0.9);
		border-radius: 2px;
		box-shadow: 0px 0px 5px 0px #f5f5f5;
		top: 25%;
		left: 50%;
		transform: translate(-50%, 0);
	}

	#PopupLog input,
	#PopupLog button,
	#PopupLog p {
		font-size: 20px;
	}

	#PopupLog h1 {
		font-size: 28px;
		margin: 10px 0 0 0;
	}

	#PopupLog hr {
		height: 2px;
		background-color: #f7f0f0;
		/*		background-color: rgba(62, 57, 54, 1);*/
		margin: 10px 0 15px;
	}

	#PopupLog input {
		background-color: rgba(255, 255, 255, 1);
		width: 250px;
		margin: 0 0 15px 0;
		padding: 5px;
		border-radius: 10px;
		border: 1px solid #747770;
	}

	#PopupLog span {
		margin: 5px 5px 0 0;
	}

	#PopupLog button {
		margin-bottom: 15px;
		padding: 5px;
		width: 260px;
		border-radius: 10px;
		background-color: #c11729;
		color: #fbfbfb;
		background-color: #e62236;
	}

	#PopupLog button:hover {
		background-color: #c11729;
	}

	#PopupLog input[type='radio'] {
		width: auto;
		margin: 0 10px 15px 10px;
	}

	.error {
		border: 1px solid red;
	}

</style>

<div id='contentPopupReg' style="display:none;">
	<h1 id=''>Регистрация</h1>
	<span class="close"></span>
	<hr>
	<p><input type="text" name="name" id='name' placeholder="Введите имя" autofocus required></p>
	<p><input type="text" name="lname" id='lname' placeholder="Введите фамилию" required></p>
	<p><input type="text" name="login" id='login' placeholder="Введите логин" required></p>
	<p><input type="email" name="email" id='email' placeholder="Введите E-mail" required></p>
	<p><input type="password" name="password" id='password' placeholder="Введите пароль" required></p>
	<p><input type="password" name="passwordCheck" id='passwordCheck' placeholder="Подтвердите пароль" required></p>
	<p><input type="radio" name='rPol' value="1" checked>Мужчина <input type="radio" name='rPol' value="2">Женщина</p>

	<button type="submit" name='btnSubmit' id='btnSubmit' class=".animateBgColor">Зарегистрироваться</button>
</div>
<div id='contentPopupLogin' style="display:none;">
	<h1>Авторизация</h1>
	<span class="close"></span>
	<hr>
	<p><input type="text" name="login" id='login' placeholder="Введите логин или E-mail" required autofocus></p>
	<p><input type="password" name="password" id='password' placeholder="Введите пароль" required></p>
	<button type="submit" name='btnSubmit' id='btnSubmit' class=".animateBgColor">Войти</button>
</div>

<div id='bgPopupLog'>

	<div id='PopupLog'><form action="#" method="post"></form></div>
	
</div>

<script src='scriptFunction.js'></script>
<script>
	function checkField(elem){
		if(elem.val()==''){
			$(elem).addClass('error');
			return false;
		}
		return true;
	}
	
	$(document).on('click', '#PopupLog #btnSubmit', function(e) {
		var body_name = $('#PopupLog h1').text();
		var valid = true;
		var dat = {};
		var valid = this.form.checkValidity();
		console.log(valid);
		if(valid){
		e.preventDefault();
		if (body_name == 'Регистрация') {
			var name = $('#PopupLog #name');
			var lname = $('#PopupLog #lname');
			var login = $('#PopupLog #login');
			var email = $('#PopupLog #email');
			var password = $('#PopupLog #password');
			var passwordCheck = $('#PopupLog #passwordCheck');
			var pol = $("#PopupLog input[name='rPol']:checked").val();
			
			$(name).on('input',function(){
				$(this).removeClass('error');
			});$(lname).on('input',function(){
				$(this).removeClass('error');
			});$(login).on('input',function(){
				$(this).removeClass('error');
			});$(email).on('input',function(){
				$(this).removeClass('error');
			});$(password).on('input',function(){
				$(this).removeClass('error');
			});$(passwordCheck).on('input',function(){
				$(this).removeClass('error');
			});
			
			valid = checkField(name)&&checkField(lname)&&checkField(login)&&checkField(email)&&checkField(password);
			if(password.val()!=passwordCheck.val()){
				valid= false;
				showMsg($('#divMessage'),'Необходимо подтвердить пароль');
			}
			dat = {
				'name': name.val(),
				'lname': lname.val(),
				'login': login.val(),
				'email': email.val(),
				'password': password.val(),
				'pol': pol
			};

		} else {
			var login = $('#PopupLog #login');
			var password = $('#PopupLog #password');
			
			valid = checkField(login)&&checkField(password);//Проверка на валидность
			
			$(login).on('input',function(){//В случае изменения полей, убираем красную обводку
				$(this).removeClass('error');
			});$(password).on('input',function(){
				$(this).removeClass('error');
			});
			
			dat = {
				'login': login.val(),
				'password': password.val()
			};
		}
		console.log(dat);

		if (valid==true) {
			$.ajax({
				url: 'obr_log.php',
				type: 'POST',
				data: dat,
				success: function(output) {
					showMsg($('#divMessage'),output);
					if(output=='Регистрация прошла успешно'||output=='Вы авторизовались'){
						hidePopupLog();
						if(output=='Вы авторизовались'){
							$('#login #btnLogin').text('Выйти');
							
							location.reload();
						}
					}
				}
			});
		}
	}
	});

	$('#btnRegistration').click(function() {
		showPopupLog($('#contentPopupReg').html());
	});
	
	$(document).on('click','#btnLogin',function() {
		if($(this).attr('data-auth')=='login'){
			showPopupLog($('#contentPopupLogin').html());
		}else if($(this).attr('data-auth')=='logout'){
//			$(this).text('Войти в аккаунт');
			$.ajax({
				url: 'obr_log.php?log='+'logout',
				type: 'GET',
				success: function(output) {
					if(output=='Обновить'){
						location.reload();
					}
				}
			});
		}
	});
	
	

	$(document).on('click', '#PopupLog .close', function() {
		hidePopupLog();
	});

	function showPopupLog(content) {
		$('#PopupLog form').html(content);
		$('#bgPopupLog').show();
	}

	function hidePopupLog() {
		$('#PopupLog form').empty();
		$('#bgPopupLog').hide();
	}

</script>
