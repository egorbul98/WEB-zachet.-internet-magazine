<style>
	#bgPopupUserData {
		position: fixed;
		top: 0;
		width: 100%;
		min-height: 100%;
		background-color: rgba(0, 0, 0, 0.3);
		overflow: hidden;
		z-index: 1;
		display: none;
	}

	#PopupUserData {
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

	#PopupUserData input,
	#PopupUserData button,
	#PopupUserData p {
		font-size: 20px;
	}

	#PopupUserData h1 {
		font-size: 28px;
		margin: 10px 0 0 0;
	}

	#PopupUserData hr {
		height: 2px;
		background-color: #f7f0f0;
		/*		background-color: rgba(62, 57, 54, 1);*/
		margin: 10px 0 15px;
	}

	#PopupUserData input {
		background-color: rgba(255, 255, 255, 1);
		width: 250px;
		margin: 0 0 15px 0;
		padding: 5px;
		border-radius: 10px;
		border: 1px solid #747770;
	}

	#PopupUserData span {
		margin: 5px 5px 0 0;
	}

	#PopupUserData button {
		margin-bottom: 15px;
		padding: 5px;
		width: 260px;
		border-radius: 10px;
		background-color: #c11729;
		color: #fbfbfb;
		background-color: #e62236;
	}

	#PopupUserData button:hover {
		background-color: #c11729;
	}

	#PopupUserData input[type='radio'] {
		width: auto;
		margin: 0 10px 15px 10px;
	}

	.error {
		border: 1px solid red;
	}

</style>
<?php
$user_id = $_SESSION['user_id'];
$queryUser = mysql_query("SELECT * FROM user WHERE id = '$user_id'");
$masUser = mysql_fetch_array($queryUser);
?>
<div id='bgPopupUserData'>

	<div id='PopupUserData'>
        <form action="#" method="post">
            <h1 id=''>Личные данные</h1>
            <span class="close" id='spanClose'></span>
            <hr>
            <p><input type="hidden" name="user_id" id='user_id' value="<?php echo $masUser['id']; ?>"></p>
            <p><input type="text" name="nameUser" id='nameUser' placeholder="Введите имя" autofocus required value="<?php echo $masUser['name']; ?>"></p>
            <p><input type="text" name="lnameUser" id='lnameUser' placeholder="Введите фамилию" required value="<?php echo $masUser['lname']; ?>"></p>
            <p><input type="text" name="loginUser" id='loginUser' placeholder="Введите логин" required value="<?php echo $masUser['login']; ?>"></p>
            <p><input type="email" name="emailUser" id='emailUser' placeholder="Введите E-mail" required value="<?php echo $masUser['email']; ?>"></p>
            <p><button type='button' id='btnShowChangePass'>Изменить пароль</button></p>
            <div id='divChangePassword' style="display:none">
                <p><input type="password" name="passwordOld" id='passwordOld' placeholder="Введите старый пароль" required></p>
                <p><input type="password" name="passwordNew" id='passwordNew' placeholder="Введите новый пароль" required></p>
            </div>
            <p><input type="radio" name='rPolUser' value="1" checked>Мужчина <input type="radio" name='rPol' value="2">Женщина</p>

            <button type="submit" name='btnSubmitUser' id='btnSubmitUser' class=".animateBgColor">Сохранить данные</button>
        </form>
	</div>
	
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
	$(document).on('click', "#btnShowChangePass", function(){
        if($('#divChangePassword').css('display')=='none'){
			$('#divChangePassword').fadeIn(500);
		}else{
			$('#divChangePassword').fadeOut(200);
		}
        
    });
	$(document).on('click', '#PopupUserData #btnSubmitUser', function(e) {
		var valid = true;
		var dat = {};
//		var valid = this.form.checkValidity();
//		console.log(valid);
        valid = true;
		if(valid){
		e.preventDefault();
        var name = $('#PopupUserData #nameUser');
        var lname = $('#PopupUserData #lnameUser');
        var login = $('#PopupUserData #loginUser');
        var email = $('#PopupUserData #emailUser');
        var user_id = $('#PopupUserData #user_id');
        if($('#divChangePassword').css('display')=='none'){
            var passwordNew = $('#PopupUserData #passwordNew');
            var passwordOld = $('#PopupUserData #passwordOld');
        }
        
        var pol = $("#PopupUserData input[name='rPolUser']:checked").val();

        $(name).on('input',function(){
            $(this).removeClass('error');
        });$(lname).on('input',function(){
            $(this).removeClass('error');
        });$(login).on('input',function(){
            $(this).removeClass('error');
        });$(email).on('input',function(){
            $(this).removeClass('error');
        });
            if($('#divChangePassword').css('display')=='none'){
                $(passwordNew).on('input',function(){
                    $(this).removeClass('error');
                });
                $(passwordOld).on('input',function(){
                    $(this).removeClass('error');
                });
            }
            

        valid = checkField(name)&&checkField(lname)&&checkField(login)&&checkField(email);
        
         

        if($('#divChangePassword').css('display')=='none'){   
            dat = { 
                'name': name.val(),
                'lname': lname.val(),
                'login': login.val(),
                'email': email.val(),
                'pol': pol,
                'update': 'update',
                'passwordNew': passwordNew.val(),
                'passwordOld': passwordOld.val(),
                'user_id': user_id,
            }
        }else{
            dat = {
                'name': name.val(),
                'lname': lname.val(),
                'login': login.val(),
                'email': email.val(),
                'pol': pol,
                'update': 'update',
                'user_id': user_id,
            };
        }
//        dat = {
//            'name': name.val(),
//            'lname': lname.val(),
//            'login': login.val(),
//            'email': email.val(),
//            'passwordNew': passwordNew.val(),
//            'passwordOld': passwordOld.val(),
//            'pol': pol,
//            'update': 'update',
//        };
            console.log(dat);
		if (valid==true) {
			$.ajax({
				url: 'obr_log.php',
				type: 'POST',
				data: dat,
				success: function(output) {
					showMsg($('#divMessage'),output);
//					if(output=='Регистрация прошла успешно'||output=='Вы авторизовались'){
//						hidePopupUserData();
//						
//					}
				}
			});
		}
	}
	});
	
    
	$(document).on('click', '#PopupUserData #spanClose', function() {
		hidePopupUserData();
	});
    $(document).on('click', '#showUserData', function() {
		showPopupUserData();
	});

	function showPopupUserData() {
		$('#bgPopupUserData').show();
	}

	function hidePopupUserData() {
		$('#bgPopupUserData').hide();
	}

</script>