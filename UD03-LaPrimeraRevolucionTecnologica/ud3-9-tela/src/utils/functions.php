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
require_once './src/models/Fermentacion.php';
require_once './src/models/Tela.php';

session_start();

function obtenerZonas(){
	$arrayZonas = [
		'bosque',
		'selva',
		'pradera',
		'desierto',
		'montaña',
		'polo'
	];

	return $arrayZonas;
}

function obtenerTecnicas(){
	$arrayTecnicas = ['tradicional', 'rapido','detallado'];

	return $arrayTecnicas;
}

function obtenerMateriales(){
	$arrayMateriales = Material::getMateriales();
	return $arrayMateriales;
}

function obtenerInventos(): array{
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
		'Arado' => [],
		'Fermentacion' => [],
		'Tela' => []
	];

	foreach ($inventos as $invento => $_) {
        if (isset($_SESSION[$invento])) {
            $inventoSer = $_SESSION[$invento];
            $inventoDes = [];
            foreach ($inventoSer as $key => $inventoSerializado) {
                $inventoDes[$key] = unserialize($inventoSerializado);
            }
            $inventos[$invento] = $inventoDes;
        }
    }

	return $inventos;
}

function cargarVistaTipoInventos(): void{
	$html = '<h1>Tipos de Inventos</h1>';
	$html .= '<table class="invento-table">';
    $html .= '<tr><th>Nombre del Invento</th><th>Acciones</th></tr>';
	$inventos = obtenerInventos();
    foreach ($inventos as $nombreInvento => $_) {
        $html .= '<tr>';
        $html .= '<td>' . htmlspecialchars($nombreInvento) . '</td>';
		$html .= "<td><a href='./inventos.php?tipoInvento=" . urlencode($nombreInvento) . "'>Listar Inventos</a><span> </span><a href='./formulario.php?tipoInvento=" . urlencode($nombreInvento) . "'>Crear Invento</a></td>";
        $html .= '</tr>';
    }
    $html .= '</table>';
	echo $html;
}

function cargarVistaInventos(): void{
	$tipoInvento = $_GET['tipoInvento'];
	$inventos = obtenerInventos();
	
	if(isset($tipoInvento) && array_key_exists($tipoInvento, $inventos)){
		$html = '<a href="./index.php">Volver</a>';
		$html .= "<h1>Inventos ".urldecode($tipoInvento)."</h1>";
		$html .= '<table class="invento-table">';
		$html .= '<tr><th>Nombre del Invento</th><th>Acciones</th></tr>';
	
		$invetosTipo = $inventos[$tipoInvento];
		foreach ($invetosTipo as $invento) {
			$html .= '<tr>';
			$html .= '<td>' . htmlspecialchars($invento->getNombre()) . '</td>';
			$html .= "<td><a href='./invento.php?tipoInvento=" . urlencode($tipoInvento) . "&nombreInvento=" . urlencode($invento->getNombre()) . "'>Ver Invento</a></td>"; 
			$html .= '</tr>';
		}
		$html .= '</table>';
		echo $html;
	} else {
		$html .= '<p>Invento no válido</p>';
		$html .= "<a href='./index.php'>Volver a la página principal</a>";
		echo $html;
	}
}

function cargarVistaInvento(){
	$tipoInvento = $_GET['tipoInvento'];
	$nombreInvento = $_GET['nombreInvento'];

	$inventos = obtenerInventos();

	if(isset($tipoInvento) && $nombreInvento != null){
		$invento = null;
		foreach ($inventos[$tipoInvento] as $inventoEncontrado) {
			if ($inventoEncontrado->getNombre() === $nombreInvento) {
				$invento = $inventoEncontrado;
				break;
			}
		}
		echo "<a href='./inventos.php?tipoInvento=". urldecode($tipoInvento)."'>Volver</a>";
		echo '<table class="invento-table">';
		echo $invento;
		echo '</table>';
		return;
	} else {
		echo '<p>Invento no válido</p>';
		echo "<a href='./inventos.php?tipoInvento=". urldecode($tipoInvento)."'>Volver</a>";
		
	}
}

function procesarNuevoInvento(): void{
		
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		$datosEntrada = [
			'invento'           => $_POST['invento'] ?? '',
			'nombre'            => $_POST['nombre'] ?? '',
			'base'              => $_POST['base'] ?? '',
			'altura'            => $_POST['altura'] ?? '',
			'longitud'          => $_POST['longitud'] ?? '',
			'radio'             => $_POST['radio'] ?? '',
			'ancho'             => $_POST['ancho'] ?? '',
			'grosor'            => $_POST['grosor'] ?? '',
			'metodo'            => $_POST['metodo'] ?? '',
			'visibilidad'       => $_POST['visibilidad'] ?? '',
			'velocidadRotacion' => $_POST['velocidadRotacion'] ?? '',
			'numeroElementos'   => $_POST['numeroElementos'] ?? '',
			'precision'         => $_POST['precision'] ?? '',
			'tiempoMinimo'      => $_POST['tiempoMinimo'] ?? '',
			'tiempoMaximo'      => $_POST['tiempoMaximo'] ?? '',
			'zona'              => $_POST['zona'] ?? '',
			'tecnica'           => $_POST['tecnica'] ?? '',
			'tecnicaArco'       => $_POST['tecnicaArco'] ?? '',
			'material'          => $_POST['material'] ?? '',
			'materialTecho'     => $_POST['materialTecho'] ?? '',
			'materialParedes'   => $_POST['materialParedes'] ?? '',
			'materialSuelo'     => $_POST['materialSuelo'] ?? '',
			'piedraAfilada'     => $_POST['piedraAfilada'] ?? '',
			'cuerda'            => $_POST['cuerda'] ?? '',
			'fuego'             => $_POST['fuego'] ?? '',
			'cesta'             => $_POST['cesta'] ?? '',
			'lanza'             => $_POST['lanza'] ?? '',
			'arcoFlecha'        => $_POST['arcoFlecha'] ?? '',
			'hacha'             => $_POST['hacha'] ?? '',
			'trampa'            => $_POST['trampa'] ?? '',
			'rueda'             => $_POST['rueda'] ?? '',
			'refugio'           => $_POST['refugio'] ?? '',
			'carro'             => $_POST['carro'] ?? '',
			'ganaderia'         => $_POST['ganaderia'] ?? '',
			'ceramica'          => $_POST['ceramica'] ?? '',
			'alfareria'         => $_POST['alfareria'] ?? '',
			'torno'             => $_POST['torno'] ?? '',
			'agricultura'       => $_POST['agricultura'] ?? '',
			'arado'             => $_POST['arado'] ?? '',
			'fermentacion'      => $_POST['fermentacion'] ?? '',
		];
	}

	if(empty(validarDatos($datosEntrada))){
		$inventoNuevo = crearInvento($datosEntrada);
		$tipoInventos = obtenerInventos();
		$datosInvento = urlencode($datosEntrada['invento']);
		$datosNombre = urlencode($datosEntrada['nombre']);
		$tipoInventos[$datosEntrada['invento']][$datosEntrada['nombre']] = $inventoNuevo;
		guardarInventos($tipoInventos);
		header('Location: invento.php?tipoInvento=' .  $datosInvento . '&nombreInvento=' . $datosNombre);
	} else {
		foreach (validarDatos($datosEntrada) as $error) {
			echo '<p>' . $error . '</p>';
		}
		echo "<a href='./index.php?invento=" . $datosInvento . "'>Volver al formulario</a>";
	}

}

function crearInvento($datosEntrada): Invento{
	$nombreInvento = $datosEntrada['invento'] ?? null;
	$inventos = obtenerInventos();
	
	$invento = null;
	if (isset($nombreInvento) && array_key_exists($nombreInvento, obtenerInventos())) {
		switch ($nombreInvento) {
			case 'PiedraAfilada':
				$material = obtenerMateriales()[$datosEntrada['material']];
				$nombre = $datosEntrada['nombre'];
				$tamanyo = [
					'base' => $datosEntrada['base'], 
					'altura' => $datosEntrada['altura'], 
					'longitud' => $datosEntrada['longitud']
				];
				$zona = $datosEntrada['zona'];
				$tecnica = $datosEntrada['tecnica'];

				$invento = new PiedraAfilada(
					$material,
					$nombre,
					$tamanyo,
					$zona,
					$tecnica			
				);
				break;

			case 'Cuerda':
				$material = obtenerMateriales()[$datosEntrada['material']];
				$nombre = $datosEntrada['nombre'];
				$tamanyo = [
					'radio' => $datosEntrada['radio'],
					'altura' => $datosEntrada['altura']
				];
				$zona = $datosEntrada['zona'];
				$tecnica = $datosEntrada['tecnica'];

				$invento = new Cuerda(
					$material,
					$nombre,
					$tamanyo, 
					$zona,
					$tecnica
				);
				break;

			case 'Fuego':
				$material = obtenerMateriales()[$datosEntrada['material']];
				$nombre = $datosEntrada['nombre'];
				$zona = $datosEntrada['zona'];
				$tecnica = $datosEntrada['tecnica'];

			$invento = new Fuego(
				$material,
				$nombre,
				$zona,
				$tecnica
				);
				break;

			case 'Lanza':
				$material =	obtenerMateriales()[$datosEntrada['material']];
				$piedra = $inventos['PiedraAfilada'][$datosEntrada['piedraAfilada'][0]];
				$cuerda = $inventos['Cuerda'][$datosEntrada['cuerda'][0]];
				$nombre = $datosEntrada['nombre'];
				$tamanyo = [
					'radio' => $datosEntrada['radio'], 
					'altura' => $datosEntrada['altura']
				];
				$zona =	$datosEntrada['zona'];
				$tecnica = $datosEntrada['tecnica'];

				$invento = new Lanza(
					$material,
					$piedra,
					$cuerda,
					$nombre,
					$tamanyo,
					$zona,
					$tecnica
				);
				eliminarInvento('PiedraAfilada', $datosEntrada['piedraAfilada'][0]);
				eliminarInvento('Cuerda', $datosEntrada['cuerda'][0]);
				break;

			case 'ArcoFlecha':
				$material = obtenerMateriales()[$datosEntrada['material']];
				$lanza = $inventos['Lanza'][$datosEntrada['lanza'][0]];
				$cuerda = $inventos['Cuerda'][$datosEntrada['cuerda'][0]];
				$nombre = $datosEntrada['nombre'];
				$tecnicaCuerda = $datosEntrada['metodo'];
				$tamanyo = ['radio' => $datosEntrada['radio']];
				$zona = $datosEntrada['zona'];
				$tecnica = $datosEntrada['tecnica'];

				$invento = new ArcoFlecha(
					$material,
					$lanza,
					$cuerda,
					$nombre,
					$tecnicaCuerda,
					$tamanyo,
					$zona,
					$tecnica
				);
				eliminarInvento('Lanza', $datosEntrada['lanza'][0]);
				eliminarInvento('Cuerda', $datosEntrada['cuerda'][0]);
				break;

			case 'Cesta':
				$material = obtenerMateriales()[$datosEntrada['material']];
				$nombre = $datosEntrada['nombre'];
				$tamanyo = [
					'radio' => $datosEntrada['radio'], 
					'altura' => $datosEntrada['altura'],
					'grosor' => $datosEntrada['grosor']
					];
				$zona = $datosEntrada['zona'];
				$tecnica = $datosEntrada['tecnica'];
				$numeroElementos = $datosEntrada['numeroElementos'];
				
				$invento = new Cesta(
					$material,
					$nombre,
					$tamanyo, 
					$zona,
					$tecnica,
					$numeroElementos
				);
				break;

			case 'Refugio':
				$materialTecho = obtenerMateriales()[$datosEntrada['materialTecho']];
				$materialParedes = obtenerMateriales()[$datosEntrada['materialParedes']];
				$materialSuelo = obtenerMateriales()[$datosEntrada['materialSuelo']];
				$nombre = $datosEntrada['nombre'];
				$tamanyo = [
					'ancho' => $datosEntrada['ancho'], 
					'altura' => $datosEntrada['altura'], 
					'longitud' => $datosEntrada['longitud'], 
					'grosor' => $datosEntrada['grosor']
				];
				$zona = $datosEntrada['zona'];
				$tecnica = $datosEntrada['tecnica'];
				$numeroElementos = $datosEntrada['numeroElementos'];

				$invento = new Refugio(
					$materialTecho,
					$materialParedes,
					$materialSuelo,
					$nombre,
					$tamanyo,
					$zona,
					$tecnica,
					$numeroElementos
				);
				break;
			case 'Rueda':
				$material = obtenerMateriales()[$datosEntrada['material']];
				$nombre = $datosEntrada['nombre'];
				$tamanyo = [
					'radio' => $datosEntrada['radio'], 
					'altura' => $datosEntrada['altura']
				];
				$zona = $datosEntrada['zona'];
				$tecnica = $datosEntrada['tecnica'];

				$invento = new Rueda(
					$material,
					$nombre,
					$tamanyo,
					$zona,
					$tecnica
				);
				break;
			case 'Trampa':
				$cuerda = $inventos['Cuerda'][$datosEntrada['cuerda'][0]];
				$cesta = $inventos['Cesta'][$datosEntrada['cesta'][0]];
				$arcoFlecha = $inventos['ArcoFlecha'][$datosEntrada['arcoFlecha'][0]];
				$nombre = $datosEntrada['nombre'];
				$visibilidad = $datosEntrada['visibilidad'];
				$zona = $datosEntrada['zona'];
				$tecnica = $datosEntrada['tecnica'];
				$numeroElementos = $datosEntrada['numeroElementos'];

				$invento = new Trampa(
					$cuerda,
					$cesta,
					$arcoFlecha,
					$nombre,
					$visibilidad,
					$zona,
					$tecnica,
					$numeroElementos
				);
				eliminarInvento('Cuerda', $datosEntrada['cuerda'][0]);
				eliminarInvento('Cesta', $datosEntrada['cesta'][0]);
				eliminarInvento('ArcoFlecha', $datosEntrada['arcoFlecha'][0]);
				break;
			case 'Hacha':
				$material = obtenerMateriales()[$datosEntrada['material']];
				$piedraAfilada = $inventos['PiedraAfilada'][$datosEntrada['piedraAfilada'][0]];
				$nombre = $datosEntrada['nombre'];
				$tamanyo = [
					'radio' => $datosEntrada['radio'], 
					'altura' => $datosEntrada['altura']
				];
				$zona = $datosEntrada['zona'];
				$tecnica = $datosEntrada['tecnica'];

				$invento = new Hacha(
					$material,
					$piedraAfilada,
					$nombre,
					$tamanyo,
					$zona,
					$tecnica
				);
				eliminarInvento('PiedraAfilada', $datosEntrada['piedraAfilada'][0]);
				break;
			case 'Carro':
				$material = obtenerMateriales()[$datosEntrada['material']]; 
				$cuerda = $inventos['Cuerda'][$datosEntrada['cuerda'][0]];
				$cesta = $inventos['Cesta'][$datosEntrada['cesta'][0]];
				$ruedas = [
					$inventos['Rueda'][$datosEntrada['rueda'][0]], 
					$inventos['Rueda'][$datosEntrada['rueda'][1]]
				];
				$hacha = $inventos['Hacha'][$datosEntrada['hacha'][0]];
				$nombre = $datosEntrada['nombre'];
				$zona = $datosEntrada['zona'];
				$tecnica = $datosEntrada['tecnica'];
				$numeroElementos = $datosEntrada['numeroElementos'];
				$invento = new Carro(
					$material,
					$cuerda,
					$cesta,
					$ruedas,
					$hacha,
					$nombre,
					$zona,
					$tecnica,
					$numeroElementos
				);
				eliminarInvento('Cuerda', $datosEntrada['cuerda'][0]);
				eliminarInvento('Cesta', $datosEntrada['cesta'][0]);
				eliminarInvento('Rueda', $datosEntrada['rueda'][0]);
				eliminarInvento('Rueda', $datosEntrada['rueda'][1]);
				eliminarInvento('Hacha', $datosEntrada['hacha'][0]);
				
				break;
			case 'Ganaderia':
				$cuerda =[];
				$piedraAfilada = [];
				$trampa = [];
				$refugio = [];
				for ($i=0; $i < 10; $i++) { 
					$cuerdas[] = $inventos['Cuerda'][$datosEntrada['cuerda'][$i]];
					$piedrasAfiladas[] = $inventos['PiedraAfilada'][$datosEntrada['piedraAfilada'][$i]];
				}
				for ($i=0; $i < 5; $i++) { 
					$trampas[]=$inventos['Trampa'][$datosEntrada['trampa'][$i]];
				}
				for ($i=0; $i < 2; $i++) { 
					$refugios[] = $inventos['Refugio'][$datosEntrada['refugio'][$i]];
				}
				$nombre = $datosEntrada['nombre'];
				$zona = $datosEntrada['zona'];
				$tecnica = $datosEntrada['tecnica'];
				$numeroElementos = $datosEntrada['numeroElementos'];
				
				$invento = new Ganaderia(
					$cuerdas,
					$piedrasAfiladas,
					$trampas,
					$refugios,
					$nombre,
					$zona,
					$tecnica,
					$numeroElementos
				);

				for($i = 0; $i < count($cuerdas); $i++){
					eliminarInvento('Cuerda', $datosEntrada['cuerda'][$i]);
				}
				for($i = 0; $i < count($piedrasAfiladas); $i++){
					eliminarInvento('PiedraAfilada', $datosEntrada['piedraAfilada'][$i]);
				}

				for($i = 0; $i < 5; $i++){
					eliminarInvento('Trampa', $datosEntrada['trampa'][$i]);
				}

				for($i = 0; $i < 2; $i++){
					eliminarInvento('Refugio', $datosEntrada['refugio'][$i]);
				}
				break;
			case 'Ceramica':
				$material = obtenerMateriales()[$datosEntrada['material']];
				$fuego = $inventos['Fuego'][$datosEntrada['fuego'][0]];
				$cesta = $inventos['Cesta'][$datosEntrada['cesta'][0]];
				$nombre = $datosEntrada['nombre'];
				$zona = $datosEntrada['zona'];
				$tecnica = $datosEntrada['tecnica'];
				$numeroElementos = $datosEntrada['numeroElementos'];
				$invento = new Ceramica(
					$material,
					$fuego,
					$cesta,
					$nombre,
					$zona,
					$tecnica,
					$numeroElementos
				);
				eliminarInvento('Fuego', $datosEntrada['fuego'][0]);
				eliminarInvento('Cesta', $datosEntrada['cesta'][0]);
				break;
			case 'Alfareria':
				$material = obtenerMateriales()[$datosEntrada['material']];
				$fuego = $inventos['Fuego'][$datosEntrada['fuego'][0]];
				$ceramica = $inventos['Ceramica'][$datosEntrada['ceramica'][0]];
				$refugio = $inventos['Refugio'][$datosEntrada['refugio'][0]];
				$nombre = $datosEntrada['nombre'];
				$zona = $datosEntrada['zona'];
				$tecnica = $datosEntrada['tecnica'];
				$numeroElementos = $datosEntrada['numeroElementos'];

				$invento = new Alfareria(
					$material,
					$fuego,
					$ceramica,
					$refugio,
					$nombre,
					$zona,
					$tecnica,
					$numeroElementos
				);
				eliminarInvento('Fuego', $datosEntrada['fuego'][0]);
				eliminarInvento('Ceramica', $datosEntrada['ceramica'][0]);
				eliminarInvento('Refugio', $datosEntrada['refugio'][0]);
				break;
			case 'Torno':
				$alfareria = $inventos['Alfareria'][$datosEntrada['alfareria'][0]];
				$ceramica = $inventos['Ceramica'][$datosEntrada['ceramica'][0]];
				$rueda = $inventos['Rueda'][$datosEntrada['rueda'][0]];
				$nombre = $datosEntrada['nombre'];
				$velocidadRotacion = $datosEntrada['velocidadRotacion'];
				$precision = $datosEntrada['precision'];
				$zona = $datosEntrada['zona'];
				$tecnica = $datosEntrada['tecnica'];

				$invento = new Torno(
					$alfareria,
					$ceramica,
					$rueda,
					$nombre,
					$velocidadRotacion,
					$precision,
					$zona,
					$tecnica
				);
				eliminarInvento('Alfareria', $datosEntrada['alfareria'][0]);
				eliminarInvento('Ceramica', $datosEntrada['ceramica'][0]);
				eliminarInvento('Rueda', $datosEntrada['rueda'][0]);
				break;
			case 'Agricultura':
				$cestas = [];
				$lanzas = [];

				$material = obtenerMateriales()[$datosEntrada['material']];
				for ($i=0; $i < 10; $i++) { 
					$cestas[] = $inventos['Cesta'][$datosEntrada['cesta'][$i]];
				}
				for ($i=0; $i < 20; $i++) { 
					$lanzas[] =  $inventos['Lanza'][$datosEntrada['lanza'][$i]];
				}
				$ganaderia = $inventos['Ganaderia'][$datosEntrada['ganaderia'][0]];
				$nombre = $datosEntrada['nombre'];
				$tamanyo = [
					'longitud' => $datosEntrada['longitud'], 
					'ancho' => $datosEntrada['ancho'], 
					'altura' => $datosEntrada['altura'], 
					'grosor' => $datosEntrada['grosor']
				];
				$zona = $datosEntrada['zona'];
				$tecnica = $datosEntrada['tecnica'];
				$numeroElementos = $datosEntrada['numeroElementos'];
				
				$invento = new Agricultura(
					$material,
					$cestas,
					$lanzas,
					$ganaderia,
					$nombre,
					$tamanyo,
					$zona,
					$tecnica,
					$numeroElementos
				);
				
				for($i = 0; $i < count($cestas); $i++){
					eliminarInvento('Cesta', $datosEntrada['cesta'][$i]);
				}
				for($i = 19; $i >= 0; $i--){
					eliminarInvento('Lanza', $datosEntrada['lanza'][$i]);
				}
				eliminarInvento('Ganaderia', $datosEntrada['ganaderia'][0]);
				break;
			case 'Arado':
				$material = obtenerMateriales()[$datosEntrada['material']];
				$ruedas = [
					$inventos['Rueda'][$datosEntrada['rueda'][0]], 
					$inventos['Rueda'][$datosEntrada['rueda'][1]]
				];
				$hacha = $inventos['Hacha'][$datosEntrada['hacha'][0]];
				$agricultura = $inventos['Agricultura'][$datosEntrada['agricultura'][0]];
				$nombre = $datosEntrada['nombre'];
				$zona = $datosEntrada['zona'];
				$tecnica = $datosEntrada['tecnica'];

				$invento = new Arado(
					$material,
					$ruedas,
					$hacha,
					$agricultura,
					$nombre,
					$zona,
					$tecnica
				);
				eliminarInvento('Rueda', $datosEntrada['rueda'][0]);
				eliminarInvento('Rueda', $datosEntrada['rueda'][1]);
				eliminarInvento('Hacha', $datosEntrada['hacha'][0]);
				eliminarInvento('Agricultura', $datosEntrada['agricultura'][0]);
				break;
			case 'Fermentacion':
				$material = obtenerMateriales()[$datosEntrada['material']];
				$alfarerias = [
					$inventos['Alfareria'][$datosEntrada['alfareria'][0]],
					$inventos['Alfareria'][$datosEntrada['alfareria'][1]]
						];
				$torno = $inventos['Torno'][$datosEntrada['torno'][0]];
				$agriculturas = [
					$inventos['Agricultura'][$datosEntrada['agricultura'][0]],
					$inventos['Agricultura'][$datosEntrada['agricultura'][1]]
						];
				$nombre = $datosEntrada['nombre'];
				$tiempoMaximo = $datosEntrada['tiempoMinimo'];
				$tiempoMinimo = $datosEntrada['tiempoMaximo'];
				$zona = $datosEntrada['zona'];
				$tecnica = $datosEntrada['tecnica'];

				$invento = new Fermentacion(
					$material,
					$alfarerias,
					$torno,
					$agriculturas,
					$nombre,
					$tiempoMaximo,
					$tiempoMinimo,
					$zona,
					$tecnica
				);
				for ($i=0; $i < count($alfarerias); $i++) { 
					eliminarInvento('Alfareria', $datosEntrada['alfareria'][$i]);
				}
				for ($i=0; $i < count($agriculturas); $i++) { 
					eliminarInvento('Agricultura', $datosEntrada['agricultura'][$i]);
				}
				eliminarInvento('Torno', $datosEntrada['torno'][0]);
				break;
			case 'Tela':
				$cuerdas = [];

				$material = obtenerMateriales()[$datosEntrada['material']];
				for ($i=0; $i < 10; $i++) { 
					$cuerdas[] = $inventos['Cuerda'][$datosEntrada['cuerda'][$i]];
				}	
				$ganaderias =[ 
					$inventos['Ganaderia'][$datosEntrada['ganaderia'][0]],
					$inventos['Ganaderia'][$datosEntrada['ganaderia'][1]]
				];
				$tornos =[ 
					$inventos['Torno'][$datosEntrada['torno'][0]],
					$inventos['Torno'][$datosEntrada['torno'][1]]
				];
				$nombre = $datosEntrada['nombre'];
				$tamanyo = [
					'longitud' => $datosEntrada['longitud'], 
					'ancho' => $datosEntrada['ancho'], 
					
					'altura' => $datosEntrada['altura'], 
					'grosor' => $datosEntrada['grosor']
						];
				$zona = $datosEntrada['zona'];
				$tecnica = $datosEntrada['tecnica'];

				$invento = new Tela(
					$material,
					$cuerdas,
					$ganaderias,
					$tornos,
					$nombre,
					$zona,
					$tecnica,
					
				);

				for($i = 19; $i >= 0; $i--){
					eliminarInvento('Cuerda', $datosEntrada['cuerda'][$i]);
				}
				for ($i=0; $i < count($ganaderias); $i++) {
					eliminarInvento('Ganaderia', $datosEntrada['ganaderia'][$i]);
				}
				for ($i=0; $i < count($tornos); $i++) {
					eliminarInvento('Torno', $datosEntrada['torno'][$i]);
				}
				break;
		}
	}
	return $invento;
}

function generarFormulario(string $invento, array $campos, array $inventos, array $materiales, array $zonas, array $tecnicas){
	
	$html = '<h2>Formulario para ' . $invento . '</h2>';
	$html .= "<form action='InventoController.php' method='POST' class='invento-form'>";
	$html .= '<input type="hidden" name="invento" value="' . $invento . '">';

    foreach ($campos as $campo) {
		$nombreCampo = $campo['nombre'];
		$tipoCampo = $campo['tipo'];
		$arrayCampo = $campo['nombre'] . '[]';
		$html .= '<div class="form-group">';
		$html .= '<label for="' . $nombreCampo . '">' . ucfirst($nombreCampo) . ':</label>';

        if ($campo['tipo'] === 'select') {
            $opciones = [];
            
            if ($campo['variable'] === 'materiales') {
                $nombresMateriales = array_keys($materiales);
                $opciones = $nombresMateriales;
                $html .= '<select name=\''. $nombreCampo .'\' id=' . $nombreCampo .'>';
                foreach ($opciones as $opcion) {
                    $html .= "<option value='$opcion'>" . $opcion . "</option>";
                }

            } elseif ($campo['variable'] === 'zonas') {
                $opciones = $zonas;
                $html .= '<select name=\''. $nombreCampo . '\' id='. $nombreCampo . '>';
                foreach ($opciones as $opcion) {
                    $html .= "<option value='$opcion'>" . $opcion . "</option>";
                }

            } elseif ($campo['variable'] === 'tecnicas') {
                $opciones = $tecnicas;
                $html .= '<select name=\'' . $nombreCampo . '\' id=\'' . $nombreCampo . '\'>';
                foreach ($opciones as $opcion) {
                    $html .= "<option value='$opcion'>" . $opcion . "</option>";
                }

            } else {
                $opciones = [];
                foreach ($inventos[$campo['variable']] as $invento => $objetoInvento) {
                    $opciones[$invento] = $objetoInvento->nombre;
                }
                $html .= '<select name=\'' . $arrayCampo . '\' id=\'' . $nombreCampo . '\' multiple>';
                foreach ($opciones as $key => $opcion) {
                    $html .= "<option value='$key'>" . $opcion . "</option>";
                }
            }

            $html .= "</select>";
        } else {
            $html .= '<input type=\'' . $tipoCampo . '\' name=\'' . $nombreCampo .'\' id=\''. $nombreCampo . '\' required>';
        }

        $html .= "</div>";
    }

    $html .= '<button type="submit">Enviar</button><a href="./index.php">Volver</a></form>';

    echo $html;
}

function cargarVistaFormulario(array $inventos, array $materiales, array $zonas, array $tecnicas){
	$tipoInvento = $_GET['tipoInvento'];
	
	if(isset($tipoInvento) && array_key_exists($tipoInvento, $inventos)){
		$campos = $tipoInvento::getCampos();
		generarFormulario($tipoInvento, $campos, $inventos, $materiales, $zonas, $tecnicas);
	}else{
		echo '<p>Invento no válido</p>';
		echo "<a href='./index.php'>Volver a la página principal</a>";
	}
}

function inicializarInventos(array $inventos, array $materiales, array $zonas, array $tecnicas, int $numero): array{
	$inventosInicializados = [];
	foreach ($inventos as $invento => $_) {
			$inventosInicializados[$invento] = generarInvento($invento, $numero, $inventosInicializados, $materiales, $zonas, $tecnicas);
    }

	return $inventosInicializados;
}

function obtenerObjetoAleatorio(array $objetos){
	return $objetos[array_rand($objetos)];
}

function generarInvento(string $nombre, int $numero, array $inventos, array $materiales, array $zonas, array $tecnicas){
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
			'Fermentacion'  => new Fermentacion(obtenerObjetoAleatorio($materiales), [obtenerObjetoAleatorio($inventos['Alfareria']), obtenerObjetoAleatorio($inventos['Alfareria'])], obtenerObjetoAleatorio($inventos['Torno']), [obtenerObjetoAleatorio($inventos['Agricultura']), obtenerObjetoAleatorio($inventos['Agricultura'])], 'Fermentacion' . $i, rand(3600, 86400), rand(86401, 172800), obtenerObjetoAleatorio($zonas), obtenerObjetoAleatorio($tecnicas)),
			'Tela'          => new Tela(obtenerObjetoAleatorio($materiales), array_fill(0, 20, obtenerObjetoAleatorio($inventos['Cuerda'])), array_fill(0, 2, obtenerObjetoAleatorio($inventos['Ganaderia'])), array_fill(0, 2, obtenerObjetoAleatorio($inventos['Torno'])), 'Tela' . $i, obtenerObjetoAleatorio($zonas), obtenerObjetoAleatorio($tecnicas)),
		};
	}

	return $inventosGenerados;
}

function validarDatos($datosEntrada): array{
	$errores = [];
	$datosInvento = $datosEntrada['invento'];

	if (!isset($datosInvento) || $datosInvento === '' || $datosInvento === null) {
		$errores[] = "El campo 'invento' es obligatorio y no puede estar vacío.";
		return $errores;
	}
	$nombreInvento = $datosInvento;
	if (!class_exists($nombreInvento)) {
		$errores[] = "La clase del invento '$nombreInvento' no existe.";
		return $errores;
	}
	$camposInvento = $nombreInvento::getCampos();
	$inventosPrevios = $nombreInvento::getInventosPrevios();

	foreach ($camposInvento as $campo) {
		$nombreCampo = $campo['nombre'];
		$datosCampo = $datosEntrada[$nombreCampo];
		if (!isset($datosCampo) || $datosCampo === '' || $datosCampo === null) {
			$errores[] = "El campo '$nombreCampo' es obligatorio.";
			continue;
		}

		if ($campo['tipo'] === 'select') {
			$nombreCampoInvento = $campo['variable'];

			if (array_key_exists($nombreCampoInvento, $inventosPrevios)) {
				$numeroNecesario = $inventosPrevios[$nombreCampoInvento];
				$totalInventos = count($datosEntrada[$nombreCampo]);

				if ($totalInventos != $numeroNecesario) {
					$errores[] = "El campo '$nombreCampo' debe contener '$numeroNecesario' elemento(s).";
				} else {
					return $errores;
				}
			}
		}
	}
	return $errores;
}

function guardarInventos($inventos): void{

	foreach ($inventos as $tipoInvento => $lista){
		if(!empty($lista) && isset($tipoInvento)){
			$inventosNombre = [];
			foreach ($lista as $invento) {
				$inventosNombre[] = serialize($invento);
			}
			$_SESSION[$tipoInvento] = $inventosNombre;	
		} else {
			$_SESSION[$tipoInvento] = [];
		}	
	}
}

function eliminarInvento($tipo, $id): bool{

	$inventos = obtenerInventos();
	
    if (isset($inventos[$tipo]) && isset($inventos[$tipo][$id])) {
        unset($inventos[$tipo][$id]);
        $inventos[$tipo] = array_values($inventos[$tipo]);
        guardarInventos($inventos);
        return true;
    }

    return false;
}

function limpiarSesionInventos(): void{
	$tipoInventos = obtenerInventos();
    foreach ($tipoInventos as $tipoInvento => $value) {
        if (isset($_SESSION[$tipoInvento])) {
            unset($_SESSION[$tipoInvento]); 
        }
    }
}
