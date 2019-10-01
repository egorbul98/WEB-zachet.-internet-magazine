/*eslint-env browser*/

function delMsg(container, message) {
	var p = $(container).children();
	for (var i = 0; i < p.length; i++) {
		if($(p[i]).text()==message){
			$(p[i]).remove();
		}
	}
}
function showMsg(container, message){
	var p = $(container).children();
	for (var i = 0; i < p.length; i++) {
		if($(p[i]).text()==message){
			return;
		}
	}
	$(container).append("<p>" + message + "</p>");
	p = $(container).children();
	for (var i = 0; i < p.length; i++) {
		if($(p[i]).text()==message){
			$(p[i]).show('slow');
		}
	}
	setTimeout(delMsg,4000,container,message);
}

function isNumeric(n) {
	return !isNaN(parseFloat(n)) && isFinite(n);
}