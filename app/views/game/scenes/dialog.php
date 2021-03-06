<div>
	<style scoped>
		#ji_scene_dialog { width: 90%; padding: 10px; color: <?php echo $colors['text'] ?>;}		
		#ji_scene_dialog .field-text-right {width:75%; float:left;}
		#ji_scene_dialog .field-text-left {width:75%; float:left;}
		#ji_scene_dialog .field-char-right {width:20%; float:left; text-align:center;}
		#ji_scene_dialog .field-char-left {width:20%; float:left; text-align:center}

		#ji_scene_dialog .speech-avatar {position: relative; width: 90%; border-radius: 5px; margin-bottom: -12px; z-index:0;}
		#ji_scene_dialog .speech-name-char {position: relative; font-style: oblique; background-color: <?php echo $colors['backgroundName'] ?>; border-radius: 5px;	padding: 2px; z-index:2;}

		#ji_scene_dialog .speech-balloon-1 {position: relative; width: auto;min-height: 100px; padding: 10px; background: <?php echo $colors['balloon'] ?>;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;border: #000000 solid 3px;margin-bottom: 25px;}
		#ji_scene_dialog .speech-balloon-1:after {content: ''; position: absolute; border-style: solid;border-width: 20px 26px 20px 0;border-color: transparent <?php echo $colors['balloon'] ?>;display: block;width: 0;z-index: 1;left: -26px;top: 22px;}
		#ji_scene_dialog .speech-balloon-1:before {content: '';position: absolute; border-style: solid;border-width: 24px 30px 24px 0;border-color: transparent #000000;display: block;width: 0;z-index: 0;left: -30px;top: 18px;}

		#ji_scene_dialog .speech-balloon-2 {width: auto;position: relative; min-height: 100px; padding: 10px;background: <?php echo $colors['balloon'] ?>;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;border: #000000 solid 3px;text-align: right;margin-bottom: 25px;}
		#ji_scene_dialog .speech-balloon-2:after {content: ''; position: absolute; border-style: solid;	border-width: 20px 0 20px 26px;	border-color: transparent <?php echo $colors['balloon'] ?>; display: block; width: 0; z-index: 1; right: -26px; top: 22px;}
		#ji_scene_dialog .speech-balloon-2:before {content: ''; position: absolute; border-style: solid; border-width: 24px 0 24px 30px; border-color: transparent #000000; display: block; width: 0; z-index: 0; right: -30px; top: 18px;}

		#ji_scene_dialog_question {clear:both;}
	</style>

	<div id="ji_scene_dialog">
		
		<?php foreach ($speechs as $key => $speech): ?>
			<!-- SPEECH <?php echo $key ?> -->
			<?php if ($key % 2 == 0): ?>
				<!-- SPEECH -->
				<div class="field-text-left">
					<div class="speech-balloon-2"><?php echo $speech['text'];?></div>
				</div>

				<!-- IMG -->
				<div class="field-char-left">
					<?php if(!empty($speech['avatar'])): ?>
					<img class="speech-avatar invert-img" src="<?php echo $speech['avatar']?>" /><br/>
					<?php endif; ?>
					<span class="speech-name-char "><?php echo $speech['name']?></span>
				</div>
			<?php else: ?>
				<!-- IMG -->
				<div class="field-char-right">
					<?php if(!empty($speech['avatar'])): ?>
					<img class="speech-avatar" src="<?php echo $speech['avatar']?>" /><br/>
					<?php endif; ?>
					<span class="speech-name-char"><?php echo $speech['name']?></span>
				</div>

				<!-- SPEECH -->
				<div class="field-text-left">
					<div class="speech-balloon-1"><?php echo $speech['text'] ?></div>
				</div>

				
			<?php endif; ?>
			<br clear="both"/>
		<?php endforeach; ?>
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