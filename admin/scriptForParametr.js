/*eslint-env browser*/

var body_name = $(document.body).attr('id');



if (body_name == 'parametr_del') {
	$('.aParametrDel').click(function () {
		var cb = $(this).siblings('.cbPdel');
		if(cb.prop('checked')==true){
			cb.prop('checked',false);
		}else{
			cb.prop('checked',true);
		}
	});
	
	$('#cbSelectAll').change(function(){
		if($(this).prop('checked')==true){
			$('.cbPdel').prop('checked', true);
		}else{
			$('.cbPdel').prop('checked', false);
		}
	});

	$('#btnDel').click(function () {
		var cb = $('.cbPdel');
		var arrayCB = Array();
		
		for (var i = 0; i < cb.length; i++) {
			if ($(cb[i]).prop('checked')==true) {
				var d = {
					'id':$(cb[i]).attr('data-parametr_id'),	
					'type_parametr_id':$(cb[i]).attr('data-type_parametr_id'),	
				};
				arrayCB.push(d);
			}
		}
		
		if(arrayCB.length!=0){
			$.ajax({
			url:'obr_parametr.php',
			type:'GET',
			data:{
				'parametrs':arrayCB,	
				'body_name':'del'
			},
			success: function(output){
				showMsg($('#divMessage'),output);
			}
		});
		}else{
			$('#divMessage').html('Необходимо выбрать элементы!');
		}
		
	});

}
