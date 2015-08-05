
<div>
	<style>
		#ji_question {padding: 10px; width: 100%}
		#ji_question h1 {font-size: 20px;}

		#ji_question textarea {resize:none; width: 90%; display:block;height: 100px; margin-bottom: 5px;}
		.ji_type_checkbox {width: 20px;	position: relative; display:inline-block; margin-bottom: 5px}
		.ji_type_checkbox .checkbox { cursor: pointer; position: absolute; width: 20px; height: 20px; top: 0; border-radius: 4px; -webkit-box-shadow: inset 0px 1px 1px rgba(0,0,0,0.5), 0px 1px 0px rgba(255,255,255,.4); -moz-box-shadow: inset 0px 1px 1px rgba(0,0,0,0.5), 0px 1px 0px rgba(255,255,255,.4); box-shadow: inset 0px 1px 1px rgba(0,0,0,0.5), 0px 1px 0px rgba(255,255,255,.4); 	background: -webkit-linear-gradient(top, #222 0%, #45484d 100%); background: -moz-linear-gradient(top, #222 0%, #45484d 100%); background: -o-linear-gradient(top, #222 0%, #45484d 100%); background: -ms-linear-gradient(top, #222 0%, #45484d 100%); background: linear-gradient(top, #222 0%, #45484d 100%); filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#222', endColorstr='#45484d',GradientType=0 ); }
		.ji_type_checkbox .checkbox:after {-ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=0)"; filter: alpha(opacity=0); opacity: 0; content: '';  position: absolute; width: 9px; height: 5px; background: transparent; top: 4px; left: 4px; border: 3px solid #fcfff4; border-top: none; border-right: none; 	-webkit-transform: rotate(-45deg); -moz-transform: rotate(-45deg); -o-transform: rotate(-45deg); -ms-transform: rotate(-45deg); transform: rotate(-45deg);} 
		.ji_type_checkbox .checkbox:hover::after { -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=30)"; filter: alpha(opacity=30); opacity: 0.3; }
		.ji_type_checkbox input[type=checkbox]:checked + .checkbox:after {-ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=100)"; filter: alpha(opacity=100); opacity: 1; }

		.ji_type_radio {margin-bottom: 5px;}
		.ji_type_radio input[type=radio] {position:absolute; z-index:-1000; left:-1000px; overflow: hidden; clip: rect(0 0 0 0); height:1px; width:1px; margin:-1px; padding:0; border:0;}
		.ji_type_radio input[type=radio].css-checkbox + label.css-label {padding-left:23px;	height:19px; display:inline-block; line-height:19px; background-repeat:no-repeat; background-position: 0 0; font-size:19px; vertical-align:middle; cursor:pointer; }
		.ji_type_radio input[type=radio].css-checkbox:checked + label.css-label { background-position: 0 -19px;}
		.ji_type_radio label.css-label { background-image:url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABIAAAAmCAYAAADJJcvsAAABIElEQVRIie2UQUrDQBSGv0zTNIjQ0orpQsSVtnvP0TO46kroDbooeILuPELBE4jrgnSlh7AbqQ1BplGji6YgzQxmmgGz6A9DwuTNl38evN/5Jr9m/dEx0AHOgYv02QFuXEWxnxZsVvfXe0PzD+nO+qPrreJTA5MbhS4w3uHgtiJhAQLwZgtkzVFo1VFcECIvb4exAGRRNwA2rhbaApXPUflA5evREsAFBoBXAPQI4Oyj9i/9Q9Q6QlA58HWfo0yzAYRX5bB7hhe08NtHeEGTWtBkMX1mfvegOhIqQdVWnZOrXmY/WWnD1CxqE6ksNY/aJP5QugHDWdOAzIdW06MI1kOb0de7ZDF9yuyv5q9aR0rQ5zLiZXKf0+c+anPIbtT+ADIOYuQcq465AAAAAElFTkSuQmCC); -webkit-touch-callout: none; -webkit-user-select: none; -khtml-user-select: none; -moz-user-select: none; -ms-user-select: none; user-select: none; }

		#ji_question input[type=submit] { position: relative; color: #fff; background-color: #f0ad4e; border-color: #eea236; padding: 5px 10px;font-size: 12px; line-height: 1.5; border-radius: 3px;	cursor: pointer; border: 1px solid transparent;}
	</style>

	<div id="ji_question">
		<!-- QUESTION -->
		<h1><?php echo $question->getQuestion()?></h1>

		<form method="post" action="<?=$question->getURLToSubmit()?>">
			
			<!-- SHORT ANSWER -->
			<?php if ($question->getTypeQuestion() == 1): ?>
				<textarea name="answer"></textarea>
				

			<!-- MULTIPLE CHOICE -->
			<?php elseif($question->getTypeQuestion() == 2): ?>
				<?php foreach($question->getAlternatives() as $key => $alternative): ?>

					<!-- RADIO -->
					<?php if ($question->checkIfRadio() == true): ?>
						<div class="ji_type_radio">
							<input type="radio" class="css-checkbox" name="answer" value="<?php echo $alternative['value']?>" id="ji_alt_<?php echo $key?>"/>
							<label class="css-label" for="ji_alt_<?php echo $key?>"><?php echo $alternative['alternative'] ?></label>
						</div>

					<!-- CHECKBOX -->
					<?php else: ?>
						<div class="ji_type_checkbox">
							<input type="checkbox" class="css-checkbox" name="answer[]" value="<?php echo $alternative['value']?>" id="ji_alt_<?php echo $key?>"/>
							<label class="checkbox" for="ji_alt_<?php echo $key?>"></label>
						</div>
						<label><?php echo $alternative['alternative'] ?></label><br/>
					<?php endif; ?>
						
				<?php endforeach;?>
			<?php endif; ?>

			<!-- BUTTON SUBMIT -->
			<input type="submit" value=">>>"/>

		</form>
	</div>
<div>