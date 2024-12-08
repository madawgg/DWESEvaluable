
<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>UD3-7 Formulario de Invento</title>
	<link rel="stylesheet" href="./css/styles.css">
</head>

<body>

	<?php 
		require 'src/utils/functions.php';
		
		$inventos = obtenerInventos();
		$materiales = obtenerMateriales();
		$zonas = obtenerZonas();
		$tecnicas = obtenerTecnicas();

		cargarVistaFormulario($inventos, $materiales, $zonas, $tecnicas);
	?>

</body>

</html>