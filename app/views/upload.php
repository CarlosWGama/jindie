<html>
	<head></head>
	<body>
		<form action="" method="post" enctype="multipart/form-data">
			<p>FILE</p>
			<input type="file" name="arquivo"/><br/>

			<p>FILES</p>
			<input type="file" name="arquivos[]" multiple/><br/>

			<input type="hidden" name="enviar" value="1"/>
			<input type="submit" value="Enviar"/>
		</form>
	</body>
</html>