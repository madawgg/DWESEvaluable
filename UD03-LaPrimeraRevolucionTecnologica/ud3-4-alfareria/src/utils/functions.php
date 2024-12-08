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

//Función para poder probar la aplicación en el navagador. No participa en el los tests
function principal()
{
	$materiales = Material::getMateriales();

	echo '<h1>Probar inventos</h1>';
	echo '<h2>Invento</h2>';
	Invento::probarInvento();
	echo '<h2>Piedra afilada</h2>';
	$materialPiedraAfilada = $materiales['carbono'];
	PiedraAfilada::probarInvento(['PiedraAfiladaPrueba', $materialPiedraAfilada, ['base' => 5, 'altura' => 15, 'longitud' => 5], 'pradera', 'rapido']);
	echo '<h2>Fuego</h2>';
	$materialFuego = $materiales['carbono'];
	Fuego::probarInvento(['FuegoPrueba', $materialFuego]);
	echo '<h2>Cuerda</h2>';
	$materialCuerda = $materiales['tendones'];
	Cuerda::probarInvento(['CuerdaPrueba', $materialCuerda, ['radio' => 2, 'altura' => 100]]);
	echo '<h2>Lanza</h2>';
	$materialLanza = $materiales['carbono'];
	$piedraAfilada = new PiedraAfilada('PiedraAfiladaPrueba', $materialPiedraAfilada, ['base' => 5, 'altura' => 10, 'longitud' => 10]);
	$cuerda        = new Cuerda('CuerdaPrueba', $materialCuerda, ['radio' => 2, 'altura' => 100]);
	Lanza::probarInvento(['LanzaPrueba', $materialLanza, $piedraAfilada, $cuerda, ['radio' => 1, 'altura' => 150]]);
	echo '<h2>Arco y flecha</h2>';
	$materialArco = $materiales['carbono'];
	$flecha       = new Lanza('LanzaPrueba', $materialLanza, $piedraAfilada, $cuerda, ['radio' => 1, 'altura' => 50]);
	ArcoFlecha::probarInvento(['ArcoFlechaPrueba', $materialArco, $flecha, $cuerda, 'tallado', ['radio' => 2]]);
	echo '<h2>Hacha</h2>';
	$materialHacha = $materiales['roble'];
	Hacha::probarInvento(['HachaPrueba', $materialHacha, $piedraAfilada, ['radio' => 2, 'altura' => 75]]);
	echo '<h2>Cesta</h2>';
	$materialCesta = $materiales['tendones'];
	Cesta::probarInvento(['CestaPrueba', $materialCesta, ['radio' => 30, 'altura' => 15, 'grosor' => 2]]);
	echo '<h2>Trampa</h2>';
	$cesta      = new Cesta('CestaPrueba', $materialCesta, ['radio' => 30, 'altura' => 15, 'grosor' => 2]);
	$arcoFlecha = new ArcoFlecha('ArcoFlechaPrueba', $materialArco, $flecha, $cuerda, 'tallado', ['radio' => 2]);
	Trampa::probarInvento(['TrampaPrueba', $cuerda, $cesta, $arcoFlecha, 0.1]);
	echo '<h2>Rueda</h2>';
	$materialRueda = $materiales['obsidiana'];
	Rueda::probarInvento(['RuedaPrueba', $materialRueda, ['radio' => 2, 'altura' => 10]]);
	echo '<h2>Refugio</h2>';
	$materialRefugioTecho   = $materiales['cañamo'];
	$materialRefugioParedes = $materiales['granito'];
	$materialRefugioSuelo   = $materiales['caolinita'];
	Refugio::probarInvento(['RefugioPrueba', $materialRefugioTecho, $materialRefugioParedes, $materialRefugioSuelo, ['ancho' => 500, 'altura' => 200, 'longitud' => 500, 'grosor' => 1.5]]);
	echo '<h2>Carro</h2>';
	$materialCarro = $materiales['roble'];
	$rueda         = new Rueda('RuedaPrueba', $materialRueda, ['radio' => 50, 'altura' => 10]);
	$hacha         = new Hacha('HachaPrueba', $materialHacha, $piedraAfilada, ['radio' => 2, 'altura' => 75]);
	Carro::probarInvento(['CarroPrueba', $materialCarro, $cuerda, $cesta, [$rueda, $rueda], $hacha]);
	echo '<h2>Ganadería</h2>';
	$trampa          = new Trampa('TrampaPrueba', $cuerda, $cesta, $arcoFlecha, 0.1);
	$refugio         = new Refugio('RefugioPrueba', $materialRefugioTecho, $materialRefugioParedes, $materialRefugioSuelo, ['longitud' => 5, 'altura' => 2, 'ancho' => 5, 'grosor' => 1.5]);
	$cuerdas         = [];
	$piedrasAfiladas = [];
	$trampas         = [];
	$refugios        = [];
	for ($i = 0; $i < 10; $i++) {
		$cuerdas[]         = $cuerda;
		$piedrasAfiladas[] = $piedraAfilada;
		if ($i < 5) {
			$trampas[] = $trampa;
		}
		if ($i < 2) {
			$refugios[] = $refugio;
		}
	}
	Ganaderia::probarInvento(['GanaderiaPrueba', $cuerdas, $piedrasAfiladas, $trampas, $refugios, 7]);

	echo '<h2>Cerámica</h2>';
	$materialFuego    = $materiales['carbono'];
	$materialCesta    = $materiales['tendones'];
	$materialCeramica = $materiales['caolinita'];
	$fuego            = new Fuego('FuegoPrueba', $materialFuego);
	$cesta            = new Cesta('CestaPrueba', $materialCesta, ['radio' => 30, 'altura' => 15, 'grosor' => 2]);
	Ceramica::probarInvento(['CeramicaPrueba', $materialCeramica, $fuego, $cesta]);

	echo '<h2>Alfarería</h2>';
	$materialCeramica = $materiales['caolinita'];
	$ceramica         = new Ceramica('CeramicaPrueba', $materialCeramica, $fuego, $cesta);
	Alfareria::probarInvento(['CeramicaPrueba', $materialCeramica, $fuego, $ceramica, $refugio]);
}
