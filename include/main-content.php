<?php
//Получение товаров в корзине
//include_once('settings/function.php');
//
//$ArrayOfCategories=getArrayOfCategories();
////$categories = getTree($ArrayOfCategories);
//$categories = array();
//transformTreeOfCategories($categories, $ArrayOfCategories);
//////$categories1 = transformTreeOfCategories($categories);
//////foreach($categories as $k=>$c){
//////    foreach($c as $a){
//////        echo $a."<br>";
//////    }
//////}
//printArr($categories);






?>
<!--
<table id='table'>
    <tr class='tr'>
        <td>1</td>
        <td>2</td>
    </tr>
    
</table>
<button type="button" onclick="btnClick()" id='btnClick'>Добавить</button>
<button type="button" onclick="funStyle()" id='btnClick'>CSS</button>
-->
<style>
	#divMainContent table{
		border-spacing: 50px;
		margin:auto;
	}
	#divMainContent div{
		width: 100%;
		height: 100%;
		
	}
	#divMainContent{
		padding-bottom: 20px;
	}
	#divMainP p{
		color: whitesmoke;
	}
	#divMainContent #divMainP{
		width: 50%;
		margin: auto;
	}
	#divMainContent #mainP{
		text-align: center;
		font-size: 20px;
		text-transform: uppercase;
	}
	#divMainContent table img{
		width: 100px;
		height: 100px;
		text-align: center;
	}
	#divMainContent td div p{
		color: black;
		text-align: center;
		margin-bottom: 10px;
	}
	#divMainContent td{
		vertical-align: top;
		width: 20%;
		height: 300px;
		box-shadow: 0 0 5px 0 #ebebeb;
		border-radius: 20px;
		padding: 8px;
		background-color: #ebebeb;
	}
/*
	#table_listCategories {
		margin: 20px auto;
		width: 95%;
		
		background-color: #fbfbfb;
		border-spacing: 30px 10px;
	}
*/

	
</style>


<div id='divMainContent'>
	<table>
		<tr>
			<td><div>
				<p><img src="image/icons8-fullCart-64.png" alt=""></p>
				<p><b>Большой ассортимент</b></p>
				<p>В нашем магазине представлен широчайший ассортимент сувенирной продукции большинства марок автомобилей. Мы создали и продолжаем развивать уникальную и самую крупную в мире базу по «автосувенирам» с качественными изображениями и описаниями товаров. Мы постоянно растем, находим новых поставщиков-импортеров.</p>
			</div></td>
			<td><div>
				<p><img src="image/icons8-kachestvo-512.png" alt=""></p>
				<p><b>Высочайшее качество товаров</b></p>
				<p>Мы продаем ИСКЛЮЧИТЕЛЬНО оригинальные товары, официально ввезенные в Россию. Все товары мы закупаем непосредственно у официальных импортеров и дистрибуторов. На все товары представляется гарантия и имеются сертификаты, которые мы можем предъявить по первому требованию покупателя.</p>
			</div></td>
			
			<td><div>
				<p><img src="image/icons8-costBottom-64.png" alt=""></p>
				<p><b>Низкие цены</b></p>
				<p>Мы работаем с минимально возможной доходностью и зарабатываем на большом объеме заказов. Если Вы нашли в интернете более низкие цены на подобные товары, то с высокой вероятностью, это либо подделка, либо товар, привезенный в Россию неофициальными каналами без уплаты таможенных пошлин и без разрешения на продажу в России. Это означает, что на товар нет гарантии и в случае его выхода из строя Вы не сможете расчитывать на ремонт или замену.</p>
			</div></td>
			
			<td><div>
				<p><img src="image/icons8-garant-96.png" alt=""></p>
				<p><b>Гарантия</b></p>
				<p>Все товары, приобретенные в магазинах «Sport Shop», имеют гарантию производителя или гарантию нашей сервисной службы. </p>
			</div></td>
		</tr>
		
	</table>
	<div id='divMainP'>
		<p id='mainP'>Откройте для себя спортивную одежду, обувь и спортивные товары в магазине "Sport Shop". Вы сможете найти все необходимое снаряжение для занятия Вашим любимым спортом.</p>
	</div>
	
</div>










<!--
<form action="" id='formData'>
<table>
    <tr>
        <td>Имя</td>
        <td><input type="text" name="name" id='name'></td>
    </tr>
    <tr>
        <td>Фамилия</td>
        <td><input type="text" name="lname" id='lname'></td>
    </tr>
    <tr>
        <td>Описание</td>
        <td><input name="description" id="description"></td>
    </tr>
    <button type="button" onclick="send()" id='btnClick'>Отправить</button>
</table>
<div id='divMsg'></div>
</form>
-->
<!--
<select name="" id="sel">
	<option value="1">1</option>
	<option value="2">2</option>
	<option value="3" selected>3</option>
	<option value="4">4</option>
	<option value="5">5</option>
</select>
-->
<script>
    function send(){   
        var dataSer=$('#formData').find('input').serialize();
        $.ajax({
            type: 'GET',
            url: 'obr.php',
            data: dataSer,
            success: function(dataOut){
                alert(dataOut);
            }
        });
    }
    
    
    
    
    function btnClick(){
        var tr = document.getElementsByClassName('tr');
        var elem =document.createElement('tr');
        elem.innerHTML=tr[0].innerHTML;
        $('#table').append(elem);
        
    }
    function funStyle(){
        $('#table td').css({
            "background-color":"red",
            "border":"1px solid blue"
        }).hide(1000).show(1000);
    }
    
    
    
</script>