<?php
// Reportar todos los errores
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
		'Arado' => [],
		'Fermentacion' => []
	];
	return $inventos;
}

function validarDatos($datosEntrada): array {
	$errores = [];

	if(empty($datosEntrada['invento'])){
		$errores[] = "Error del campo invento" . $datosEntrada['invento']. "vacio" ;
		
		}else{

			$invento = $datosEntrada['invento'];

	if (!class_exists($invento)){
		$errores[] = "El invento no existe (clase)";
		return $errores;
	}

	$camposInvento = $invento::getCampos();
	$inventosPrevios = $invento::getInventosPrevios();

	foreach ($camposInvento as $campo) {
		$nombreCampo = $campo['nombre'];
		if (!isset($datosEntrada[$nombreCampo]) && $datosEntrada[$nombreCampo] == "") {
			$errores[] = "El campo $nombreCampo del invento $invento es obligatorio.";
		} else {
			if ($campo['tipo'] === 'select' && isset($inventosPrevios[ucfirst($nombreCampo)])) {
				$cantidadRequerida = $inventosPrevios[ucfirst($nombreCampo)];

				if (is_array($datosEntrada[$nombreCampo])) {
					$cantidadSeleccionada = count($datosEntrada[$nombreCampo]);

					if ($cantidadSeleccionada != $cantidadRequerida) {
						$errores[] = "El campo '$nombreCampo' requiere exactamente $cantidadRequerida elementos. Elegidos: $cantidadSeleccionada.";
					}
				} else {
					$errores[] = "El campo '$nombreCampo' debería ser múltiple y requerir $cantidadRequerida elementos.";
				}
			}

			if ($campo['tipo'] === 'number' && !is_numeric($datosEntrada[$nombreCampo])) {
				$errores[] = "El campo '$campo' debe ser un valor numérico.";
			}

			if ($campo['tipo'] === 'text' && !is_string($datosEntrada[$nombreCampo])) {
				$errores[] = "El campo '$campo' debe ser un texto.";
			} 
		}
		}
	}
	
	return $errores;
}


function crearInvento($datosEntrada): Invento {
	
	$nombreInvento = $datosEntrada['invento'];
	$inventos = inicializarInventos(obtenerInventos(), obtenerMateriales(), obtenerZonas(), obtenerTecnicas(), 50);
	
	$invento = null;
	if(isset ($nombreInvento)){
		switch ($nombreInvento) {
			case 'PiedraAfilada':
				$material = obtenerMateriales()[$datosEntrada['material']];
				$nombre = $datosEntrada['nombre'];
				$tamanyo = ['base' => $datosEntrada['base'], 
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
					$tamanyo = ['radio' => $datosEntrada['radio'],
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
					$material = obtenerMateriales()[$datosEntrada['material']];
					$piedraAfilada = $inventos['PiedraAfilada'][$datosEntrada['piedraAfilada'][0]];
					$cuerda = $inventos['Cuerda'][$datosEntrada['cuerda'][0]];
					$nombre = $datosEntrada['nombre'];
					$tamanyo = ['radio' => $datosEntrada['radio'], 
								'altura' => $datosEntrada['altura']
							];
					$zona = $datosEntrada['zona'];
					$tecnica = $datosEntrada['tecnica'];

					$invento = new Lanza(
					$material,
					$piedraAfilada,
					$cuerda,
					$nombre,
					$tamanyo, 
					$zona,
					$tecnica

				);
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
				break;
			case 'Cesta':
				$material = obtenerMateriales()[$datosEntrada['material']];
				$nombre = $datosEntrada['nombre'];
				$tamanyo = ['radio' => $datosEntrada['radio'], 
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
					$tamanyo = ['ancho' => $datosEntrada['ancho'], 
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
				break;
			case 'Ganaderia':
				$cuerda =[];
				$piedraAfilada = [];
				$trampa = [];
				$refugio = [];
				for ($i=0; $i < 10; $i++) { 
					$cuerdas[]=$inventos['Cuerda'][$datosEntrada['cuerda'][$i]];
				}
				for ($i=0; $i < 10; $i++) { 
					$piedrasAfiladas[]=$inventos['PiedraAfilada'][$datosEntrada['piedraAfilada'][$i]];
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
				$tamanyo = ['longitud' => $datosEntrada['longitud'], 
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
				break;

			default:
				echo "Error al crear el objeto, no coinciden los datos de entrada.";
				break;
		}
	}
	return $invento;
}



function cargarVistaInvento(): void {


	if($_SERVER['REQUEST_METHOD'] === 'POST'){
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
		  $errores = validarDatos($datosEntrada);
		  if(empty($errores)){
			  $invento = crearInvento($datosEntrada);
			  echo $invento;
		  }else{
			  foreach ($errores as $error) {
				  echo $error;
			  }
		  }

	}else{
		$inventos = obtenerInventos();
		cargarVistaInventos($inventos);
	}

}

function inicializarInventos(array $inventos, array $materiales, array $zonas, array $tecnicas, int $numero): array{

	foreach ($inventos as $nombreInvento => $_) {
		$instanciaInvento = generarInvento($nombreInvento, $numero, $inventos, $materiales, $zonas, $tecnicas);
		
		foreach($instanciaInvento as $invento){

			$inventos[$nombreInvento][] =  $invento;
		}
	}

	return $inventos;
}

function cargarVistaInventos($inventos): void {

	$html = '<table class="invento-table">';
    $html .= '<thead><tr><th>Nombre del Invento</th><th>Acciones</th></tr></thead>';
    $html .= '<tbody>';
    
	foreach ($inventos as $invento => $_) { 
        $html .= '<tr><td>' . $invento . '</td>';
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
		$inventosInicializados = inicializarInventos($inventos, $materiales, $zonas, $tecnicas, 50);
		generarFormulario($nombreInvento, $campos, $inventosInicializados, $materiales, $zonas, $tecnicas);
		
	}else{
		echo '<p>Error: Invento no válido.</p>';
        echo "<a href='./index.php'>Volver a la página principal</a>";
	}
}

function generarFormulario(string $invento, array $campos, array $inventos, array $materiales, array $zonas, array $tecnicas){
	$html = '<h1>Formulario para ' . htmlspecialchars($invento) . '</h1>';
    $html .= "<form action='InventoController.php' method='POST' class='invento-form'>";

    $html .=  '<input type="hidden" name="invento" value="' . $invento . '">';

    foreach ($campos as $campo) {
        $html .= "<div class='form-group'>";
        $html .= "<label for='{$campo['nombre']}'>" . ucfirst($campo['nombre']) . ":</label>";

        if ($campo['tipo'] === 'select') {
            $opciones = [];

            
            if ($campo['variable'] === 'materiales') {
                $nombresMateriales = array_keys($materiales);
                $opciones = $nombresMateriales;
                $html .= "<select name='{$campo['nombre']}' id='{$campo['nombre']}'>";
                foreach ($opciones as $opcion) {
                    $html .= "<option value='$opcion'>" . $opcion . "</option>";
                }

            } elseif ($campo['variable'] === 'zonas') {
                $opciones = $zonas;
                $html .= "<select name='{$campo['nombre']}' id='{$campo['nombre']}'>";
                foreach ($opciones as $opcion) {
                    $html .= "<option value='$opcion'>" . $opcion . "</option>";
                }

            } elseif ($campo['variable'] === 'tecnicas') {
                $opciones = $tecnicas;
                $html .= "<select name='{$campo['nombre']}' id='{$campo['nombre']}'>";
                foreach ($opciones as $opcion) {
                    $html .= "<option value='$opcion'>" . $opcion . "</option>";
                }

            } else {
                $opciones = [];
                foreach ($inventos[$campo['variable']] as $invento => $objetoInvento) {
                    $opciones[$invento] = $objetoInvento->nombre;
                }
                $html .= "<select name='{$campo['nombre']}[]' id='{$campo['nombre']}' multiple>";
                foreach ($opciones as $key => $opcion) {
                    $html .= "<option value='$key'>" . $opcion . "</option>";
                }
            }

            $html .= "</select>";
        } else {
            $html .= "<input type=\"{$campo['tipo']}\" name=\"{$campo['nombre']}\" id=\"{$campo['nombre']}\" required>";
        }

        $html .= "</div>";
    }

    $html .= '<button type="submit">Enviar</button><a href="./index.php">Volver</a></form>';

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
			'Fermentacion'  => new Fermentacion(obtenerObjetoAleatorio($materiales), [obtenerObjetoAleatorio($inventos['Alfareria']), obtenerObjetoAleatorio($inventos['Alfareria'])], obtenerObjetoAleatorio($inventos['Torno']), [obtenerObjetoAleatorio($inventos['Agricultura']), obtenerObjetoAleatorio($inventos['Agricultura'])], 'Fermentacion' . $i, rand(3600, 86400), rand(86401, 172800), obtenerObjetoAleatorio($zonas), obtenerObjetoAleatorio($tecnicas)),
		};
	}

	return $inventosGenerados;
}

