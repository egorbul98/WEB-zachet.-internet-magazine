/*eslint-env browser*/

var body_name = document.body.getAttribute('id');

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

if (body_name == 'product_update') {
	
}

if (body_name == 'product_del') {
	document.getElementById('btnDelProduct').addEventListener('click', function () {
		var cbDelProduct = document.getElementsByClassName('cbDelProduct');
		var products_id = Array();
		for (var i = 0; i < cbDelProduct.length; i++) {
			if (cbDelProduct[i].checked) {
				products_id.push(cbDelProduct[i].value);
			}
		}
		var product = {
			'products_id': products_id
		};
		if (products_id.length != 0) {
			var json = JSON.stringify(product);

			xhr = new XMLHttpRequest();
			xhr.open("POST", "obr_product.php?body_name=" + body_name);
			xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
			xhr.onreadystatechange = function () {
				if (xhr.readyState == 4 && xhr.status == 200) {
					document.getElementById('divMessage').innerHTML = xhr.responseText;
				}
			}
			xhr.send(json);
		} else {
			alert('Сначала необходимо выбрать эелементы для удаления');
		}
	})

	document.getElementById('cbSelectAll').addEventListener('change', function () {
		var cb = document.getElementsByClassName('cbDelProduct');
		var checkeValue = this.checked;
		for (var i = 0; i < cb.length; i++) {
			cb[i].checked=checkeValue;
		}
	});

}

function isNumeric(n) {
	return !isNaN(parseFloat(n)) && isFinite(n);
}
if (body_name != 'product_del') {
	$(document).on('input','#imgFile',function(){
		var fd = new FormData();
		var file = $('#imgFile');
		console.log(file.val().split('\\').pop());
		fd.append('image', file.prop('files')[0]);
		$.ajax({
			url:'obr_images.php',
			type:'post',
			data:fd,
			cache: false,
			processData: false, // Не обрабатываем файлы (Don't process the files)
			contentType: false, // Так jQuery скажет серверу что это строковой запрос
			success: function(output){
				showError(divMessage, output);
				$('#showLoad').html('');
			},
			beforeSend:function(){
				$('#showLoad').html('<img src="../image/load.gif" alt="load">');
			}

		});
	});
	
	
	window.document.getElementById('product_form').onsubmit = function (e) {
		e.preventDefault();
		var divMessage = document.getElementById('divMessage');
		var tr_parametr = document.getElementsByClassName('tr-parametr');

		var name = document.getElementById('name');
		var price = document.getElementById('price');
		var description = document.getElementById('description');
		var count = document.getElementById('count');
		var category_id = document.getElementById('category_id');
		var tr_parametr = document.getElementsByClassName('tr-parametr');
		var oldParametrs = {};
		var parametrsId = {}
		var parametrsText = {}
		var product = {};
		
		var imageName = $('#imgFile').val().split('\\').pop();
		console.log(imageName);
		
		
		var isValid = true; //Результат проверки на ошибки

		price.addEventListener('input', function () {
			price.classList.remove('errorColor');
			delError(divMessage, "Необходимо заполнить поле \'Цена\'");
		});
		price.addEventListener('input', function () {
			price.classList.remove('errorColor');
			delError(divMessage, "В поля 'Цена' и 'Количество товара' необходимо вводить только числовые значения.");
		});
		count.addEventListener('input', function () {
			count.classList.remove('errorColor');
			delError(divMessage, "Необходимо заполнить поле 'Количество товара'");
		});
		count.addEventListener('input', function () {
			count.classList.remove('errorColor');
			delError(divMessage, "В поля 'Цена' и 'Количество товара' необходимо вводить только числовые значения.");
		});





		for (var i = 0; i < tr_parametr.length; i++) {
			var cb = tr_parametr[i].querySelector('.cbAddParametr');
			var elem;
			if (cb.checked) {
				elem = tr_parametr[i].querySelector('.textParametr');
			} else {
				elem = tr_parametr[i].querySelector('.selectParametr');
			}
			if (elem.value != '') {
				if (elem.classList.contains('selectParametr')) {
					parametrsId[elem.getAttribute('data-typeParametrId')] = elem.value;
				} else if (elem.classList.contains('textParametr')) {
					parametrsText[elem.getAttribute('data-typeParametrId')] = elem.value;
				}

				if (body_name == 'product_update') {
					var oldElem = tr_parametr[i].querySelector('.oldParametr');
					if (oldElem.value == '') {
						oldParametrs[oldElem.getAttribute('data-typeParametrId')] = 'NULL';
					} else {
						oldParametrs[oldElem.getAttribute('data-typeParametrId')] = oldElem.value;
						//										alert("ОЛДЫ ТУТ "+oldElem.getAttribute('data-typeParametrId') +" валуев"+ oldElem.value);
					}
				}
			} else {
				isValid = false; //Форма не прошла валидацию, т.к. не все поля заполнены
				elem.classList.add('errorColor');
				if (elem.classList.contains('textParametr')) {
					showError(divMessage, 'Необходимо заполнить все поля параметров');
					delError(divMessage, 'Необходимо выбрать параметры');
				} else {
					showError(divMessage, 'Необходимо выбрать параметры');
					delError(divMessage, 'Необходимо заполнить все поля параметров');
				}
			}
		}

		var isValidName = errorChecking(name, divMessage, "Необходимо заполнить поле 'Название'");
		var isValidPrice = errorChecking(price, divMessage, "Необходимо заполнить поле 'Цена'");
		var isValidDescription = errorChecking(description, divMessage, "Необходимо заполнить поле 'Описание'");
		var isValidCount = errorChecking(count, divMessage, "Необходимо заполнить поле 'Количество товара'");
		if(parseFloat(price.value)<0||parseFloat(count.value)<0){
			showError(divMessage, "Поля 'Цена' и 'Количество' не могут принимать отрицательные значения");
			isValid = false;
		}
		if (isNumeric(name.value)) {
			name.classList.add('errorColor');
			showError(divMessage, "Название товара не может состоять только из цифр");
			isValid = false;
		}
		if (!(isNumeric(price.value) && isNumeric(count.value))) {
			showError(divMessage, "В поля 'Цена' и 'Количество товара' необходимо вводить только числовые значения.")
		}

		if (isValidName && isValidPrice && isValidDescription && isValidCount && isValid) {
			product['name'] = name.value;
			product['price'] = price.value;
			product['count'] = count.value;
			product['description'] = description.value;
			product['category_id'] = category_id.value;
			product['parametrsId'] = parametrsId;
			product['parametrsText'] = parametrsText;
			product['imageName'] = imageName;

			if (body_name == 'product_update') {
				var product_id = document.getElementById('product_id').value;
				product['product_id'] = product_id;
				product['oldParametrs'] = oldParametrs;
				console.log(oldParametrs);
			}
////			var file = $('#imgFile');
////			
//			var fd = new FormData();
////			var json = JSON.stringify(product);
//			var form = $('#product_form').serialize();
//			
//			fd.append('form', JSON.stringify(form));
//			
////			fd.append('img', file.prop('files')[0]);
////			fd.append('product[]',product);
////			fd.append('body_name',body_name);
//
////			tags.push({article: 1, gender: 2, brand: 3});
////			var form = $('#product_form').serialize();
////			var json = JSON.stringify(product);
//////			console.log(form);
////			var fromJSON = JSON.stringify(form);
////			console.log(fromJSON);
////			console.log(form);
//////			var a = new FormData($('#product_form').get(0));
////			var a = $('#product_form').serializeJSON;
////			console.log(a);
//			$.ajax({
//				url:'obr_product.php',
//				type:'post',
//				data:fd,
////				data:{
////					'json':json,
////					'form':fromJSON,
////					'cat':2,
////					'body_name':body_name,
////				},
//				cache: false,
//				processData: false, // Не обрабатываем файлы (Don't process the files)
//				contentType: false, // Так jQuery скажет серверу что это строковой запрос
//				success: function(output){
//					showError(divMessage, output);
//				}
//
//			});
			
			var json = JSON.stringify(product);
			var xhr = new XMLHttpRequest();
			xhr.open("POST", "obr_product.php?body_name=" + body_name);
			xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
			xhr.onreadystatechange = function () {
				if (xhr.readyState === 4 && xhr.status === 200) {
					showError(divMessage, xhr.responseText);
				}
			}
			xhr.send(json);
		}

	}
}
