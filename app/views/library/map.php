
<div class="ji_map">
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

	  	<?php foreach($tiles as $x => $row): ?>
	  	<!-- ROW -->
	      <ul>
	      	<!-- TILE -->
	      	<?php foreach($row as $y => $tile):	?>
	          <li data-posicao="<?php echo $x . ',' . $y ?>">
	          	
	          	<!--  ACTION -->
	          	<?php if (isset($tile['url']) && !empty($tile['url'])): ?>
	          		<a href="javascript:doActionJI('<?php echo $tile['url'] ?>')">
	          	<?php endif; ?>
	          	
	          	<!-- FIELD -->
	          	<?php if (isset($tile['field']) && !empty($tile['field'])): ?>
	          		<div class="field"  style="background:url(<?php echo $tile['field'] ?>);">
	            <?php else: ?>
					<div class="field">
				<?php endif; ?>
	              
	            	<!--  OBJECT -->
	            	<?php if (isset($tile['object']) && !empty($tile['object'])): ?>
	               		<img src="<?php echo $tile['object']?>"/>
	            	<?php endif; ?>
	            	<!--  END OBJECT -->
	                  
	            </div>
	            <!--  END FIELD -->
	              
	            <!-- END ACTION -->
	            <?php if (isset($tile['url']) && !empty($tile['url'])): ?>
	            </a>
	            <?php endif; ?>
	              
	          </li>
	          <!-- END TILE -->
	          <?php endforeach; ?>
	      </ul>
	      <?php endforeach; ?>
	      <!-- END ROW -->
      </div>
  </div>
  
<!-- JQUERY -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>

<!-- SCRIPT -->
<script type="text/javascript">
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
</script>