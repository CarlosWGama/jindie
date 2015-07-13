<html>
	<head></head>
	<body>
		<form action="" method="post" enctype="multipart/form-data">
			<?php foreach ($errors as $key => $error): ?>
				<p><?=$error?></p>
			<?php endforeach ?>

			<p>REQUIRED</p>
			<input type="text" name="required" value="<?=form_set_text('required')?>"/><br/>

			<p>MATCH</p>
			<input type="text" name="match"/><br/>

			<p>MIN LENGTH</p>
			<input type="text" name="min_length"/><br/>

			<p>MAX LENGTH</p>
			<input type="text" name="max_length"/><br/>

			<p>EXAC LENGTH</p>
			<input type="text" name="exac_length"/><br/>

			<p>MIN VALUE</p>
			<input type="text" name="min_value"/><br/>

			<p>MAX VALUE</p>
			<input type="text" name="max_value"/><br/>

			<p>EXAC VALUE</p>
			<input type="text" name="exac_value"/><br/>

			<p>EMAIL</p>
			<input type="text" name="email"/><br/>

			<p>NUMBER</p>
			<input type="text" name="number"/><br/>

			<p>INTEGER</p>
			<input type="text" name="integer"/><br/>

			<p>CPF</p>
			<input type="text" name="cpf"/><br/>

			<p>CNPJ</p>
			<input type="text" name="cnpj"/><br/>

			<p>Select</p>
			<select name="select">
				<option value="1" <?=form_set_select(1, 'select')?>>1</option>
				<option value="2" <?=form_set_select(2, 'select', true)?>>2</option>
				<option value="3" <?=form_set_select(3, 'select')?>>3</option>
			</select><br/>
			
			<p>Checkbox</p>
			<input type="checkbox" name="check[]" value="1" <?=form_set_multicheck(1, 'check')?>> 1<br/>
			<input type="checkbox" name="check[]" value="2" <?=form_set_multicheck(2, 'check')?>> 2<br/>
			<input type="checkbox" name="check[]" value="3" <?=form_set_multicheck(3, 'check', true)?>> 3<br/>
			<input type="checkbox" name="check[]" value="4" <?=form_set_multicheck(4, 'check')?>> 4<br/>

			<p>Radio</p>
			<input type="radio" name="radio" value="1" <?=form_set_check(1, 'radio')?>> 1<br/>
			<input type="radio" name="radio" value="2" <?=form_set_check(2, 'radio')?>> 2<br/>
			<input type="radio" name="radio" value="3" <?=form_set_check(3, 'radio')?>> 3<br/>
			<input type="radio" name="radio" value="4" <?=form_set_check(4, 'radio', true)?>> 4<br/>

			<p>FILE</p>
			<input type="file" name="arquivo"/><br/>

			<input type="hidden" name="enviar" value="1"/>
			<input type="submit" value="Enviar"/>
		</form>
	</body>
</html>