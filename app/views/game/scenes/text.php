<div>
	<style scoped>
		.ji_scene_text {padding: 10px;}
		.ji_scene_text_image {border-radius: 10px;}
		.ji_scene_text_text {margin-top: 10px; text-align: justify;}
	</style>


	<div class="ji_scene_text">	
		<?php if (!empty($image)): ?>
			<img src="<?php echo $image ?>" class="ji_scene_text_image"/>
		<?php endif; ?>
		<p class="ji_scene_text_text"><?php echo $text ?></p>
	</div>

</div>