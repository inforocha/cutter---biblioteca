<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Teste cutter</title>
	</head>
	<body>
		<h1>Teste cutter</h1>
		<form action="exemplo.php" method="post" target="_blank">
			<label for="">Autor</label>
			<input type="text" name="autor" value="" placeholder="Insira o nome do autor" style="width: 50%;">
			<br>
			<br>
			<label for="">Obra</label>
			<input type="text" name="obra" value="" placeholder="Insira o nome da obra. OPCIONAL" style="width: 50%;">
			<br>
			<input type="checkbox" name="usar_obra"> Usar obra no cutter
			<br>
			<input type="submit" value="enviar">
		</form>
	</body>
</html>