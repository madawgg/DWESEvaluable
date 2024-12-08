<?php

require_once './src/models/Invento.php';
require_once './src/models/ArcoFlecha.php';
require_once './src/models/Cesta.php';
require_once './src/models/Cuerda.php';
require_once './src/models/Fuego.php';
require_once './src/models/Hacha.php';
require_once './src/models/Lanza.php';
require_once './src/models/PiedraAfilada.php';
require_once './src/models/Refugio.php';
require_once './src/models/Rueda.php';
require_once './src/models/Trampa.php';
require_once './src/models/Material.php';
require_once './src/models/Carro.php';
require_once './src/models/Ganaderia.php';
require_once './src/models/Ceramica.php';
require_once './src/models/Alfareria.php';
require_once './src/models/Torno.php';
require_once './src/models/Agricultura.php';
require_once './src/models/Arado.php';




function obtenerZonas(): array {
	$zonas = ['bosque', 'selva', 'pradera', 'desierto', 'montaña', 'polo'];
	return $zonas;
}

function obtenerTecnicas(): array {
	$tecnicas = ['tradicional', 'rapido', 'detallado'];
	return $tecnicas;
}

function obtenerMateriales(): array {
	$materiales = Material::getMateriales();
	return $materiales;
}

function obtenerInventos(): array {
	$inventos = [
		'PiedraAfilada' => [],
		'Cuerda' => [],
		'Fuego' => [],
		'Lanza' => [],
		'ArcoFlecha' => [],
		'Cesta' => [],
		'Refugio' => [],
		'Rueda' => [],
		'Trampa' => [],
		'Hacha' => [],
		'Carro' => [],
		'Ganaderia' => [],
		'Ceramica' => [],
		'Alfareria' => [],
		'Torno' => [],
		'Agricultura' => [],
		'Arado' => []
	];
	return $inventos;
}

function inicializarInventos(array $inventos, array $materiales, array $zonas, array $tecnicas, int $numero): array{

	foreach ($inventos as $nombreInvento => $_) {
		$instanciaInvento = generarInvento($nombreInvento, $numero, $inventos, $materiales, $zonas, $tecnicas);
		
		$inventos[$nombreInvento] =array_merge($inventos[$nombreInvento],  $instanciaInvento);
	}
	return $inventos;
}

function cargarVistaInventos($inventos): void {

	$html = '<table class="invento-table">';
    $html .= '<thead><tr><th>Nombre del Invento</th><th>Acciones</th></tr></thead>';
    $html .= '<tbody>';
    
	foreach ($inventos as $invento => $_) { 
        $html .= '<tr><td>' . htmlspecialchars($invento) . '</td>';
        $html .= '<td><a href=\'./formulario.php?invento=' . urlencode($invento) . '\'>Crear</a></td></tr>';
    }
    
	$html .= '</tbody></table>';
	
	echo $html;
}

function cargarVistaFormulario(array $inventos, array $materiales, array $zonas, array $tecnicas){
	$materiales = obtenerMateriales();
	$inventos = obtenerInventos();
	$zonas = obtenerZonas();
	$tecnicas = obtenerTecnicas();
	$nombreInvento = $_GET['invento'];

	if($nombreInvento && array_key_exists($nombreInvento, $inventos)){
		$campos = $nombreInvento::getCampos();
		$inventosInicializados = inicializarInventos($inventos, $materiales, $zonas, $tecnicas, 1);
		generarFormulario($nombreInvento, $campos, $inventosInicializados, $materiales, $zonas, $tecnicas);
		
	}else{
		echo '<p>Error: Invento no válido.</p>';
        echo "<a href='./index.php'>Volver a la página principal</a>";
	}
}

function generarFormulario(string $invento, array $campos, array $inventos, array $materiales, array $zonas, array $tecnicas){
	$html = '<h1>Formulario para ' . htmlspecialchars($invento) . '</h1>';
    $html .= "<form action='InventoController.php' method='POST' class='invento-form'>";

    $html .=  '<input type="hidden" name="tipo_invento" value="' . $invento . '">';

  
    foreach ($campos as $campo) {
		$html .= "<div class=form-group>";
		$html .= "<label for='{$campo['nombre']}'>" . ucfirst($campo['nombre']) . ":</label>";

		if ($campo['tipo'] === 'select') {

			$opciones = [];

			switch ($campo['variable']) {
				case 'materiales':
					$nombresMateriales = array_keys($materiales);
					$opciones = $nombresMateriales;
					
					$html .=  "<select name='{$campo['nombre']}' id='{$campo['nombre']}'>";
					break;
				case 'zonas':
					$opciones = $zonas;
					
					$html .=  "<select name='{$campo['nombre']}' id='{$campo['nombre']}'>";
					break;
				case 'tecnicas':
					$opciones = $tecnicas;

					$html .=  "<select name='{$campo['nombre']}' id='{$campo['nombre']}'>";
					break;
				default:
					foreach ($inventos[$campo['variable']] as $invento) {
						$opciones[] = $invento->nombre;
					}
					$html .=  "<select name='{$campo['nombre']}' id='{$campo['nombre']}' multiple>";
					break;
			}

			foreach ($opciones as $opcion) {
				$html .=  "<option value='$opcion'>" . ucfirst($opcion) . "</option>";
			}
			
			$html .= "</select>";

		} else {
			$html .= "<input type=\"{$campo['tipo']}\" name=\"{$campo['nombre']}\" id=\"{$campo['nombre']}\" required>";
		}

		$html .=  "</div>";
	}

	$html .=  '<button type="submit">Enviar</button><a class="button-link" href="./index.php">Volver</a></form>';

	echo $html;
	
}



function obtenerObjetoAleatorio(array $objetos)
{
	return $objetos[array_rand($objetos)];
}

function generarInvento(string $nombre, int $numero, array $inventos, array $materiales, array $zonas, array $tecnicas)
{
	$inventosGenerados = [];
	for ($i = 0; $i < $numero; $i++) {
		$inventosGenerados[] = match ($nombre) {
			'PiedraAfilada' => new PiedraAfilada(obtenerObjetoAleatorio($materiales), 'PiedraAfilada' . $i, ['base' => rand(10, 100), 'altura' => rand(10, 100), 'longitud' => rand(10, 100)], obtenerObjetoAleatorio($zonas), obtenerObjetoAleatorio($tecnicas)),
			'Cuerda'        => new Cuerda(obtenerObjetoAleatorio($materiales), 'Cuerda' . $i, ['radio' => rand(1, 10), 'altura' => rand(10, 100)], obtenerObjetoAleatorio($zonas), obtenerObjetoAleatorio($tecnicas)),
			'Fuego'         => new Fuego(obtenerObjetoAleatorio($materiales), 'Fuego' . $i, obtenerObjetoAleatorio($zonas), obtenerObjetoAleatorio($tecnicas)),
			'Lanza'         => new Lanza(obtenerObjetoAleatorio($materiales), obtenerObjetoAleatorio($inventos['PiedraAfilada']), obtenerObjetoAleatorio($inventos['Cuerda']), 'Lanza' . $i, ['radio' => rand(1, 10), 'altura' => rand(10, 100)], obtenerObjetoAleatorio($zonas), obtenerObjetoAleatorio($tecnicas)),
			'ArcoFlecha'    => new ArcoFlecha(obtenerObjetoAleatorio($materiales), obtenerObjetoAleatorio($inventos['Lanza']), obtenerObjetoAleatorio($inventos['Cuerda']), 'ArcoFlecha' . $i, 'tallado', ['radio' => rand(1, 10)], obtenerObjetoAleatorio($zonas), obtenerObjetoAleatorio($tecnicas)),
			'Cesta'         => new Cesta(obtenerObjetoAleatorio($materiales), 'Cesta' . $i, ['radio' => rand(10, 100), 'altura' => rand(10, 100), 'grosor' => rand(1, 10)], obtenerObjetoAleatorio($zonas), obtenerObjetoAleatorio($tecnicas)),
			'Refugio'       => new Refugio(obtenerObjetoAleatorio($materiales), obtenerObjetoAleatorio($materiales), obtenerObjetoAleatorio($materiales), 'Refugio' . $i, ['ancho' => rand(10, 100), 'altura' => rand(10, 100), 'longitud' => rand(10, 100), 'grosor' => rand(1, 10)], obtenerObjetoAleatorio($zonas), obtenerObjetoAleatorio($tecnicas)),
			'Rueda'         => new Rueda(obtenerObjetoAleatorio($materiales), 'Rueda' . $i, ['radio' => rand(10, 100), 'altura' => rand(10, 100)], obtenerObjetoAleatorio($zonas), obtenerObjetoAleatorio($tecnicas)),
			'Trampa'        => new Trampa(obtenerObjetoAleatorio($inventos['Cuerda']), obtenerObjetoAleatorio($inventos['Cesta']), obtenerObjetoAleatorio($inventos['ArcoFlecha']), 'Trampa' . $i, rand(1, 10), obtenerObjetoAleatorio($zonas), obtenerObjetoAleatorio($tecnicas)),
			'Hacha'         => new Hacha(obtenerObjetoAleatorio($materiales), obtenerObjetoAleatorio($inventos['PiedraAfilada']), 'Hacha' . $i, ['radio' => rand(10, 100), 'altura' => rand(10, 100)], obtenerObjetoAleatorio($zonas), obtenerObjetoAleatorio($tecnicas)),
			'Carro'         => new Carro(obtenerObjetoAleatorio($materiales), obtenerObjetoAleatorio($inventos['Cuerda']), obtenerObjetoAleatorio($inventos['Cesta']), [obtenerObjetoAleatorio($inventos['Rueda']), obtenerObjetoAleatorio($inventos['Rueda'])], obtenerObjetoAleatorio($inventos['Hacha']), 'Carro' . $i, obtenerObjetoAleatorio($zonas), obtenerObjetoAleatorio($tecnicas)),
			'Ganaderia'     => new Ganaderia(array_fill(0, 10, obtenerObjetoAleatorio($inventos['Cuerda'])), array_fill(0, 10, obtenerObjetoAleatorio($inventos['PiedraAfilada'])), array_fill(0, 5, obtenerObjetoAleatorio($inventos['Trampa'])), array_fill(0, 2, obtenerObjetoAleatorio($inventos['Refugio'])), 'Ganaderia' . $i, obtenerObjetoAleatorio($zonas), obtenerObjetoAleatorio($tecnicas)),
			'Ceramica'      => new Ceramica(obtenerObjetoAleatorio($materiales), obtenerObjetoAleatorio($inventos['Fuego']), obtenerObjetoAleatorio($inventos['Cesta']), 'Ceramica' . $i, obtenerObjetoAleatorio($zonas), obtenerObjetoAleatorio($tecnicas)),
			'Alfareria'     => new Alfareria(obtenerObjetoAleatorio($materiales), obtenerObjetoAleatorio($inventos['Fuego']), obtenerObjetoAleatorio($inventos['Ceramica']), obtenerObjetoAleatorio($inventos['Refugio']), 'Alfareria' . $i, obtenerObjetoAleatorio($zonas), obtenerObjetoAleatorio($tecnicas)),
			'Torno'         => new Torno(obtenerObjetoAleatorio($inventos['Alfareria']), obtenerObjetoAleatorio($inventos['Ceramica']), obtenerObjetoAleatorio($inventos['Rueda']), 'Torno' . $i, rand(1, 10), rand(1, 10), obtenerObjetoAleatorio($zonas), obtenerObjetoAleatorio($tecnicas)),
			'Agricultura'   => new Agricultura(obtenerObjetoAleatorio($materiales), array_fill(0, 10, obtenerObjetoAleatorio($inventos['Cesta'])), array_fill(0, 20, obtenerObjetoAleatorio($inventos['Lanza'])), obtenerObjetoAleatorio($inventos['Ganaderia']), 'Agricultura' . $i, ['longitud' => rand(10, 100), 'ancho' => rand(10, 100), 'altura' => rand(10, 100), 'grosor' => rand(1, 10)], obtenerObjetoAleatorio($zonas), obtenerObjetoAleatorio($tecnicas)),
			'Arado'         => new Arado(obtenerObjetoAleatorio($materiales), [obtenerObjetoAleatorio($inventos['Rueda']), obtenerObjetoAleatorio($inventos['Rueda'])], obtenerObjetoAleatorio($inventos['Hacha']), obtenerObjetoAleatorio($inventos['Agricultura']), 'Arado' . $i, obtenerObjetoAleatorio($zonas), obtenerObjetoAleatorio($tecnicas)),
		};
	}

	return $inventosGenerados;
}
