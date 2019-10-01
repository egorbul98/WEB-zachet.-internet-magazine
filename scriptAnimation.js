/*eslint-env browser*/
var body_name = document.body.getAttribute('id');

var i;

//Общее
var button = document.getElementsByTagName('button');

for (i = 0; i < button.length; i++) {
    button[i].classList.add('animate');
}

//main
var main = document.getElementById('main');
var a = main.getElementsByTagName('a');
for (i = 0; i < a.length; i++) {
    a[i].classList.add('animateColor');
}
//footer
$(document).ready(function(){
	var footer = document.getElementById('footer');
	var aFooter = footer.getElementsByTagName('a');
	for (i = 0; i < aFooter.length; i++) {
		aFooter[i].classList.add('animateColor');
	}
});


//Навигационное меню
var nav = document.getElementById('nav');
var nav_a = nav.getElementsByTagName('a');

for (i = 0; i < nav_a.length; i++) {
    nav_a[i].classList.add('animateBgColor');
}



if(body_name=='catalog'){
//КАТАЛОГ
var table = document.getElementById('table_listCategories'); //находим таблицу со списком категорий в каталоге
if(table!=undefined&&table!=null){
	var td = table.getElementsByTagName('td'); //находим ячейки таблицы в каталоге
//var content = document.getElementById('content');
//var content_a = content.getElementsByTagName('a');

for (i = 0; i < td.length; i++) {
    td[i].classList.add('animateBgColor');
}
//for (i = 0; i < content_a.length; i++) {
//    content_a[i].classList.add('animate');
//}
}

}