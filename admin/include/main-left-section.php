
   <style>
    #listFrunctions li ul {
        display: none;
       
    }
    
/*
    #listFrunctions li:hover ul{
        transform: scaleY(1);
    }
*/

    #listFrunctions p {
        cursor: pointer;
         
    }

</style>


<ul id='listFrunctions'>
    <li>
        <p>Пользователи</p>
        <ul class="submenu">
            <li><a href="#" id='aAddUser'>Зарегистрировать пользователя</a></li>
            <li><a href="user_list.php">Список пользователей</a></li>
        </ul>
    </li>
    <li>
        <p>Клиенты и заказы</p>
        <ul class="submenu">
            <li><a href="client_list.php">Список клиентов</a></li>
            <li><a href="order_list.php">Список заказов</a></li>
        </ul>
    </li>
    <li>
        <p>Товары</p>
        <ul class="submenu">
            <li><a href="product_add.php">Добавить товар</a></li>
            <li><a href="product_update.php">Изменить товар</a></li>
            <li><a href="product_del.php">Удалить товар</a></li>
        </ul>
    </li>
     <li>
        <p>Скидки</p>
        <ul class="submenu">
            <li><a href="#" id='aAddDiscount'>Добавить скидку</a></li>
            <li><a href="discount_list.php">Список скидок</a></li>
            <li><a href="discount_category.php">Присвоить скидку категории</a></li>
            <li><a href="discount_product.php">Присвоить скидку товару</a></li>
        </ul>
    </li>
    <li>
        <p>Категории</p>
        <ul class="submenu">
            <li><a href="category_add.php">Добавить категорию</a></li>
            <li><a href="category_update.php">Изменить категорию</a></li>
            <li><a href="category_del.php">Удалить категорию</a></li>
        </ul>
    </li>
    <li>
        <p>Характеристики(параметры) категорий</p>
        <ul>
            <li><a href="#" id='aAddTypeParametr'>Добавить параметр </a></li>
            <li><a href="type_parametr_update.php" id='aUpdateTypeParametr'>Изменить параметр</a></li>
            <li><a href="type_parametr_del.php" id='aDelTypeParametr'>Удалить параметр</a></li>
        </ul>
    </li>
    <li>
        <p>Характеристики(параметры) товаров</p>
        <ul>
            <li><a href="#" id='aAddParametr'>Добавить значение параметру</a></li>
            <li><a href="parametr_update.php">Изменить значение параметру</a></li>
            <li><a href="parametr_del.php">Удалить значение параметру</a></li>
        </ul>
    </li>

    <!--
<p>Пользователи</p>
<li><a href="#" id='aAddUser'>Зарегистрировать пользователя</a></li>
<li><a href="user_list.php">Список пользователей</a></li>
<p></p>

<p></p>

<p></p>

<p></p>

<p></p>
-->
</ul>
<p id='exitAdmin'><a href="../index.php" >Выйти из административной панели</a></p>

<script>
    $('#listFrunctions p').click(function() {
		if($(this).parent().children('ul').css('display')=='none'){
			$(this).parent().children('ul').fadeIn(500);
			
		}else{
			$(this).parent().children('ul').fadeOut(200);
		}
        
        
    });

</script>
