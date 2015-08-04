<!-- SCENE Point-And-Click / Code -->

<!-- MAP -->
<div id="ji_scene_map">
	<!-- MAP -->
	<?php echo $map ?>

	<!-- SCRIPT -->
	<?php if ($hasCode): ?>
		<br clear="both"/>
		<!-- CSS -->
		<style scoped>
			#ji_scene_map #ji_code_navegation {height: 150px; margin-top: 10px; width: 75%; resize:none; }
			#ji_scene_map .btn-submit {position: relative; bottom: 10px;color: #fff; background-color: #f0ad4e; border-color: #eea236; padding: 5px 10px; font-size: 12px; line-height: 1.5; border-radius: 3px;	cursor: pointer; border: 1px solid transparent;}
		</style>

		<!-- TEXTAREA/Code -->
		<textarea id="ji_code_navegation"></textarea>

		<!-- BUTTON -->
		<input type="button" onclick="submitCode()" value=">>>" class="btn-submit">

		<!-- JQUERY -->
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
		
		<!-- SCRIPT -->
		<script type="text/javascript">

			function submitCode() {
				
				$.post('<?php echo $urlSubmitCode?>', {code: $('#ji_code_navegation').val()}, function (json) {
					//alert(json);
					result = JSON.parse(json);

					if (result.success == false) {
						alert(result.error.error)
					} else {
						//alert("Sucesso");
						$('#ji_code_navegation').val('');
						reloadMap(result.map);	
					}
					
				});
			}
		</script>
	<?php endif; ?>

</div>