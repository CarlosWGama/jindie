<div>
	<style scoped>
		.ji_scene_text {padding: 10px;}
		.ji_scene_text_image {border-radius: 10px; max-width: 80%; margin: 0 auto; display: block;}
		.ji_scene_text_text {margin-top: 10px; text-align: justify;}

		#ji_scene_dialog_question {clear:both;}
	</style>


	<div class="ji_scene_text">	
		<?php if (!empty($image)): ?>
			<img src="<?php echo $image ?>" class="ji_scene_text_image"/>
		<?php endif; ?>
		<p class="ji_scene_text_text"><?php echo $text ?></p>
	</div>

	<!-- QUESTION -->
	<?php if (!empty($question)): ?>
		<div class="ji_scene_dialog_question">
			<fieldset>
				<?php echo $question?>
			</fieldset>
		</div>
	<?php endif; ?>

</div>