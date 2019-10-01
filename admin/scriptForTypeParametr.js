/*eslint-env browser*/

var body_name = $(document.body).attr('id');


if (body_name == 'type_parametr_del') {
	$('#cbSelectAll').change(function(){
		if($(this).prop('checked')==true){
			$('.cbTPdel').prop('checked', true);
		}else{
			$('.cbTPdel').prop('checked', false);
		}
	});
	
	$('.aTypeParametrDel').click(function () {
		var cb = $(this).siblings('.cbTPdel');
		if(cb.prop('checked')==true){
			cb.prop('checked',false);
		}else{
			cb.prop('checked',true);
		}
	});

	$('#btnDel').click(function () {
		var cb = $('.cbTPdel');
		var arrayCB = Array();
		for (var i = 0; i < cb.length; i++) {
			if ($(cb[i]).prop('checked')==true) {
				arrayCB.push($(cb[i]).attr('data-type_parametr_id'));
			}
		}
		
		$.ajax({
			url:'obr_type_parametr.php',
			type:'GET',
			data:{
				'type_parametr_id':arrayCB,	
				'body_name':'del'
			},
			success: function(output){
				showMsg($('#divMessage'),output);
			}
		});
	});

}
