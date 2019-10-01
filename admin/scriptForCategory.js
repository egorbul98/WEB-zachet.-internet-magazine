/*eslint-env browser*/

var body_name = document.body.getAttribute('id');

function isNumeric(n) {
	return !isNaN(parseFloat(n)) && isFinite(n);
}

function showAndHidenTextParametr(obj) { //Показывает либо скрывает текстовое поле textParametr
	var parent_tr = obj.parentElement.parentElement; //Получем родительский tr
	var textParametr = parent_tr.querySelector('.textParametr'); //Ищем в нем текстовое поле

	var style = window.getComputedStyle(textParametr);
	if (style.visibility == 'hidden') {
		textParametr.style.visibility = 'visible';
	} else if (style.visibility == 'visible') {
		textParametr.style.visibility = 'hidden';
	}
}

function delCategory(obj) {
	var del = window.confirm("Помимо данной категории будут также удалены вложенные в нее подкатегории. Вы уверены, что хотите удалить категорию?");
	if (del) {
		var delProducts = window.confirm("Вы хотите удалить товары связанные с данной категорией и её подкатегориями?");
		var category_id = obj.getAttribute('data-categoryId');
		var xhr = new XMLHttpRequest();
		xhr.open("GET", "obr_category.php?body_name=" + body_name + "&category_id=" + category_id + "&delProducts=" + delProducts);
		xhr.onreadystatechange = function () {
			if (xhr.readyState == 4 && xhr.status == 200) {
				document.getElementById('divMessage').innerHTML = xhr.responseText;
			}
		}
		xhr.send();
	}
}
if (body_name != 'category_del') {
	window.document.getElementById('btnAddParametr').onclick = function () { //Создаем новые поля для ввода характеристик категории
		var table = document.getElementById('table-parametrsCategory');
		var table_body = table.querySelector('tbody');
		if(table_body==null){
			table.append(document.createElement('tbody'));
			table_body = table.querySelector('tbody');
		}
		var newtr = document.createElement('tr');
		newtr.className = 'tr-parametr';

		var xhr = new XMLHttpRequest();
		xhr.open("GET", "obr_selectParametrs.php?body_name=" + body_name + "&selectTypeParametr=true");
		xhr.onreadystatechange = function () {
			if (xhr.readyState == 4 && xhr.status == 200) {
				newtr.innerHTML = xhr.responseText;
				table_body.prepend(newtr);
			}
		}
		xhr.send();

	}
	//    window.document.getElementById('btnAddParametrBottom').onclick = function () { //Создаем новые поля для ввода характеристик категории
	//		var table = document.getElementById('table-parametrsCategory');
	//		var table_body = table.querySelector('tbody');
	//		var tr = table_body.getElementsByClassName('tr-parametr');
	//		var newtr = document.createElement('tr');
	//		newtr.className = 'tr-parametr';
	//		newtr.innerHTML = tr[0].innerHTML;
	//		newtr.querySelector('.textParametr').style.visibility = 'hidden';
	//		newtr.querySelector('.selectParametr').value = '';
	//		if (body_name == 'category_update') {
	//			newtr.querySelector('.oldParametr').value = '';
	//		}
	//		table_body.append(newtr);
	////		table_body.prepend(newtr);
	//	}
}

function btnDelParametr(obj) { //Удаление параметра категории (нажатие на кнопку)
	var parent_tr = obj.parentElement.parentElement;
	var table_body = parent_tr.parentElement;
	//	if (table_body.childElementCount == 1) {
	//		document.getElementById('divMessage').innerHTML = "У категории должен быть минимум один параметр";
	//	} 
	if (body_name == 'category_update') {
		var del = window.confirm("Вы точно хотите удалить параметр?");
		if (del == true) {
			var oldParametr = parent_tr.querySelector('.oldParametr').value;
			if (oldParametr != '') {
				var category_id = document.getElementById('category_id').value;
				var xhr = new XMLHttpRequest();
				xhr.open('get', 'obr_category.php?parametr_id_forDelete=' + oldParametr + "&category_id_forDelete=" + category_id);
				xhr.onreadystatechange = function () {
					if (xhr.readyState == 4 && xhr.status == 200) {
						document.getElementById('divMessage').innerHTML = xhr.responseText;
					}
				}
				xhr.send();
			}
		} else {
			return;
		}
	}
	console.log('Парметр успешно удален');
	parent_tr.remove();
}

if (body_name != 'category_del') {
	window.document.getElementById('category_form').onsubmit = function (e) {
		e.preventDefault();
		var msg = document.getElementById('divMessage');

		var tr_parametr = document.getElementsByClassName('tr-parametr');
		var name = document.getElementById('name');
		var parent_id = document.getElementById('parent_id').value;
		var parametrs = Array();
		var oldParametrs = Array();

		var isValid = true; //Результат проверки на ошибки
		name.addEventListener('input', function () {
			name.classList.remove('errorColor');
			delError(document.getElementById('divMessage'), "Необходимо заполнить поле 'Название'");
		})
		name.addEventListener('input', function () {
			name.classList.remove('errorColor');
			delError(document.getElementById('divMessage'), "Название категории не может состоять только из цифр");
		})

		if (name.value == '') {
			name.classList.add('errorColor');
			showError(msg, "Необходимо заполнить поле 'Название'");
			isValid = false;
		} else if (isNumeric(name.value)) {
			name.classList.add('errorColor');
			showError(msg, "Название категории не может состоять только из цифр");
			isValid = false;
		}

		for (var i = 0; i < tr_parametr.length; i++) {
			var cb = tr_parametr[i].querySelector('.cbAddParametr');
			var elem;
			if (cb.checked) {
				elem = tr_parametr[i].querySelector('.textParametr');
			} else {
				elem = tr_parametr[i].querySelector('.selectParametr');
			}
			if (elem.value != '') {
				if (body_name == 'category_update') {
					var oldElem = tr_parametr[i].querySelector('.oldParametr');
					if (oldElem.value == '') {
						oldParametrs.push("NULL");
					} else {
						oldParametrs.push(oldElem.value);
					}
				}
				parametrs.push(elem.value);
			} else {
				isValid = false; //Форма не прошла валидацию, т.к. не все полязаполнены
				elem.classList.add('errorColor');
				if (elem.classList.contains('textParametr')) {
					showError(msg, 'Необходимо заполнить все поля параметров');
					delError(msg, 'Необходимо выбрать параметры');
				} else {
					showError(msg, 'Необходимо выбрать параметры');
					delError(msg, 'Необходимо заполнить все поля параметров');
				}
			}
		}

		if (isValid) {
			var category = {};
			if (body_name == 'category_add') {
				category = {
					'name': name.value,
					"parent_id": parent_id,
					"parametrs": parametrs
				};
			} else if (body_name == 'category_update') {
				var category_id = document.getElementById('category_id').value;
				category = {
					'name': name.value,
					"parent_id": parent_id,
					"parametrs": parametrs,
					"category_id": category_id,
					"oldParametrs": oldParametrs
				};
			}
			console.log("oldParametrs =" + oldParametrs);
			var json = JSON.stringify(category);

			var xhr = new XMLHttpRequest();
			xhr.open('post', 'obr_category.php?body_name=' + body_name);
			xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
			xhr.onreadystatechange = function () {
				if (xhr.readyState == 4 && xhr.status == 200) {
					showError(divMessage, xhr.responseText);
				}
			}
			xhr.send(json);
		}
	}
}
