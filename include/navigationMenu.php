<?php
include_once('settings/function.php');

$ArrayOfCategories=getArrayOfCategories();
$categories = array();
transformTreeOfCategories($categories, $ArrayOfCategories);

?>
        
<ul>
            <li id='liCatalog'><a href="catalog.php">Каталог</a>
            <?php
                vivodMenu($categories);            
            ?>
            </li><!--
            --><li><a href="index.php">Главная</a></li><!--
            --><li><a href="discount.php">Акции и скидки</a></li><!--
            --><li><a href="#">Новости</a></li><!--
            --><li><a href="contacts.php">Контакты</a></li>
</ul> 
    