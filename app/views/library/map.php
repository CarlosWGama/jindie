
<div>
	<style scoped>
		.ji_map {
			border-radius: 10px;
		}

		.ji_map ul {
			margin-bottom: 0px;
			width: <?php echo $imageSize?>px;
			float:left;
			padding: 0px;
			margin: auto;
		}

		.ji_map li {
			list-style-type:none;
			width: <?php echo $imageSize?>px;

		}

		.ji_map img {
			position: relative;
			top: 0px;
			left: 0px;
			width: <?php echo $imageSize?>px;
			height: <?php echo $imageSize?>px;	
		}

		.ji_map .field {
			width: <?php echo $imageSize?>px;
			height: <?php echo $imageSize?>px;		
		}
	</style>

	<div class="ji_map">
	  	
    </div>
</div>
  
<!-- JQUERY -->
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>


<!-- SCRIPT -->
<script type="text/javascript">


	var map = JSON.parse('<?=$map?>');
	reloadMap(map);

	function doActionJI(url) {
  		$.post(url, function(json) { 
  			doResultJI (json);
		});
	}

	function doResultJI (json) {
		data = JSON.parse(json);
		if (data.type == "redirect") {
  				window.location.href = data.url;
  		}

		if (data.type == "alert") {
			alert(data.message);
		}

		if (data.type == "confirm") {
			var result = confirm(data.question);

			$.post(data.url, {result: Boolean(result)}, function(json) { 
				doResultJI (json);
			});
		}
	}

	function reloadMap(json) {
		
		html = '';

		$.each(json.tiles, function (x, row) {
  		  	
  		  	html += '<ul>';
    		$.each(row, function (y, tile) {
        		html += '<li data-posicao="' + x  +',' + y + '">';
	          	
	          	
	          	if (tile.url != null && tile.url != "" && json.clickable) { 
	          		/* ACTION */
	          		html += '<a href="javascript:doActionJI(\'' + tile.url  + '\')">';
	          	}
	          	
	          	/* FIELD */
	          	if (tile.field != null && tile.field != "") { 
	          		html += '<div class="field"  style="background:url('+ tile.field + ');">';
	            } else { 
					html += '<div class="field">';
				}
	              
	            /* OBJECT */
	            if (tile.object != null && tile.object != "") { 
	               	html += '<img src="' +  tile.object + '"/>';
	            }
	            /* END OBJECT */
	                  
	            html += '</div>';
	            /* END FIELD */

	            if (tile.url != null && tile.url != "" && json.clickable) { 
	            	html += '</a>';
	            	/* END ACTION */
	            }
	            
	            html += '</li>';
    		});

    		html += '</ul>';
		});
		$('.ji_map').html(html);
	}
</script>