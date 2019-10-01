/*eslint-env browser*/

function errorChecking(obj, container, message) { //проверяемый объект, контайнер куда будут выводиться ошибки, сообщение, которое будет выводить ошибка
	var isValid = true; //обхект Валидный или нет
	if (obj.value == '') {
		obj.classList.add('errorColor');
		showError(container, message);
		isValid = false;
	}

	return isValid;
}


function showError(container, message) {
	var p = document.createElement('p');
	p.textContent=message;
	p.style.display='block';
	var elems = container.getElementsByTagName("p");
	for (var i = 0; i < elems.length; i++){
		if(elems[i].textContent==message){
			container.replaceChild(p, elems[i]);
			return;
		}
	}
	container.appendChild(p);
	setTimeout(delError, 5000, container, message);
}

function delError(container, message) {
	var elems = container.getElementsByTagName("p");
	for (var i = 0; i < elems.length; i++){
		if(elems[i].textContent==message){
			elems[i].textContent='';
			break;
		}
	}
}

function clearOfErrors(obj, message) {
	obj.classList.remove('errorColor');
	delError(document.getElementById('divMessage'),message);
}


function clearAllErrors(){
	document.getElementById('divMessage').innerHTML="";
	var textParametr = document.getElementsByClassName('textParametr');
	for(var i =0 ; i<textParametr.length; i++){
		textParametr[i].style.visibility='hidden';
	}
}
		
		
		