<style>
	#filterPrice table td {
		font-size: 20px;
	}

	#filterPrice table div {
		width: 70%;
		margin: auto;
	}

	#textRangeFrom,
	#textRangeTo {
		width: 50%;
		background-color: rgba(255, 255, 255, 1);
		padding: 2px;
		border-radius: 10px;
		margin-bottom: 5px;
	}

	#filterPrice input[type='range'] {
		width: 90%;
	}

	#filterProduct ul li {
		display: inline-block;
		min-width: 50%;
	}

	#filterProduct ul {
		text-align: left;
		padding: 5px 10px;
	}
	#btnApplyFilter{
		width: 100%;
	}
</style>

<?php
include("settings/connectDB.php");
include_once("settings/function.php");
//$query=mysql_query("SELECT * FROM type_parametr GROUP BY name HAVING category_id");
if(isset($_GET['id'])){
	$category_id=$_GET['id'];
	$queryPrice=mysql_query("SELECT MAX(price) as max, MIN(price) as min FROM product WHERE category_id='$category_id'");
}else{
	$category_id = 0;
	$queryPrice=mysql_query("SELECT MAX(price) as max, MIN(price) as min FROM product");
}
$masPrice = mysql_fetch_array($queryPrice);
?>
<form>
	<div id='filterProduct'>
		<button type='button' id='btnApplyFilter' data-category_id='<?php echo $category_id?>'>Применить фильтр</button>
		<p>Цена, руб.</p>
		<table id='filterPrice'>
			<tr>
				<td>
					<div><input type="range" min='<?php echo $masPrice[min]?>' max="<?php echo $masPrice[max]?>" id="rangeRangeFrom" value="<?php echo $masPrice[min]?>"></div>
				</td>
				<td>
					<div><input type="range" min='<?php echo $masPrice[min]?>' max="<?php echo $masPrice[max]?>"  id="rangeRangeTo" value="<?php echo $masPrice[max]?>"></div>
				</td>
			</tr>
			<tr>
				<td>
					<div>От <input type="text" id="textRangeFrom" value="<?php echo $masPrice[min]?>"></div>
				</td>
				<td>
					<div>До <input type="text" id="textRangeTo" value="<?php echo $masPrice[max]?>"></div>
				</td>
			</tr>
		</table>
		<?php
		if(isset($_GET['id'])){
			$category_id=$_GET['id'];
			$queryTP=mysql_query("SELECT type_parametr.name as name, type_parametr.id as id FROM type_parametr, type_parametr_category WHERE type_parametr.id=type_parametr_category.type_parametr_id AND type_parametr_category.category_id='$category_id'");
		}else{
			$queryTP=mysql_query("SELECT * FROM type_parametr WHERE name='Производитель' OR name='Гарантия'");
		}
		while($masTP = mysql_fetch_array($queryTP)){
//			$queryP = mysql_query("SELECT * FROM parametr WHERE type_parametr_id='$masTP[id]'");
            
            $queryP = mysql_query("SELECT parametr.* FROM parametr 
			inner join `parametr_category` on `parametr_category`.parametr_id = parametr.id 
            WHERE parametr.type_parametr_id='$masTP[id]' AND parametr_category.category_id = '$category_id' ");
            
            
			if(mysql_num_rows($queryP)>0){
				echo "<p>$masTP[name]</p>";
				echo "<ul>";
				while($masP = mysql_fetch_array($queryP)){
					echo "<li><input type='checkbox' name='cbFilter[]' class='cbFilter' data-parametr_name='$masP[name]' data-type_parametr_id='$masTP[id]'> $masP[name]</li>";
				}
				echo "</ul>";
			}
			
		}
	?>
	</div>
</form>
<script>
	
	$(document).on('input','#rangeRangeFrom',function(){
		$('#textRangeFrom').val($(this).val());
	});
	$(document).on('input','#rangeRangeTo',function(){
		$('#textRangeTo').val($(this).val());
	});
	$(document).on('input','#textRangeFrom',function(){
		$('#rangeRangeFrom').val($(this).val());
	});
	$(document).on('input','#textRangeTo',function(){
		$('#rangeRangeTo').val($(this).val());
	});
	$(document).on('click','#btnApplyFilter',function(){
		var from = $('#rangeRangeFrom').val();
		var to = $('#rangeRangeTo').val();
		var category_id = $(this).attr('data-category_id');
		var cbFilters = $('.cbFilter');
		
		var tempArr = Array();
		var filterArray={};
		var tpId;
		for(var i = 0;i<cbFilters.length;i++){
			if($(cbFilters[i]).prop('checked')){
				tpId = $(cbFilters[i]).attr('data-type_parametr_id');
				if(filterArray[tpId]!=undefined){
					tempArr = filterArray[tpId];
				}
				
				tempArr.push($(cbFilters[i]).attr('data-parametr_name'))
				filterArray[tpId]=tempArr;
				tempArr=[];
			}
		}
		console.log(filterArray);
	
		
		$.ajax({
			url: 'obr_getCatalog.php',
			type: 'POST',
			data: {
				'from':from,
				'to':to,
				'category_id':category_id,
				'filterArray':filterArray,
			},
            beforeSend:function(){
                $('#table_product').html('<img src="image/load.gif" alt="load">');
            },
			success: function(output) {
				$('#table_product').html(output);
			}
		});
		
	});
	
	
	
</script>
