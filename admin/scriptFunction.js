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
	$(container).append("<p>" + message + "</p>");
	var p = $(container).children();
	for (var i = 0; i < p.length; i++) {
		if($(p[i]).text()==message){
			$(p[i]).show('slow');
		}
	}
	setTimeout(delMsg,3000,container,message);
}

