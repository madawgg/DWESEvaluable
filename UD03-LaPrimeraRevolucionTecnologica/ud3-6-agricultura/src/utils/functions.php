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

//Función para poder probar la aplicación en el navagador. No participa en el los tests
function principal()
{
	//Materiales
	$materiales = Material::getMateriales();

	$materialTierra = $materiales['arena_cuarzo'];
	$materialFibras = $materiales['yute'];
	$materialMadera = $materiales['roble'];
	$materialRoca   = $materiales['obsidiana'];

	//Inventos (gr, cm)
	$piedraAfilada    = new PiedraAfilada($materialRoca, 'PiedraAfiladaPrueba', ['base' => 30, 'altura' => 25, 'longitud' => 30]);
	$cuerda           = new Cuerda($materialFibras, 'CuerdaPrueba', ['radio' => 1, 'altura' => 100]);
	$fuego            = new Fuego($materialRoca, 'FuegoPrueba');
	$lanza            = new Lanza($materialMadera, $piedraAfilada, $cuerda, 'LanzaPrueba', ['radio' => 1.5, 'altura' => 120]);
	$arcoFlecha       = new ArcoFlecha($materialMadera, $lanza, $cuerda, 'ArcoFlechaPrueba', 'tallado', ['radio' => 2]);
	$cesta            = new Cesta($materialFibras, 'CestaPrueba', ['radio' => 30, 'altura' => 15, 'grosor' => 1]);
	$cestaCarro       = new Cesta($materialFibras, 'CestaPrueba', ['radio' => 100, 'altura' => 200, 'grosor' => 5]);
	$refugio          = new Refugio($materialMadera, $materialRoca, $materialTierra, 'RefugioPrueba', ['ancho' => 1000, 'altura' => 200, 'longitud' => 1000, 'grosor' => 1]);
	$refugioAlfareria = new Refugio($materialMadera, $materialRoca, $materialTierra, 'RefugioPrueba', ['ancho' => 100, 'altura' => 100, 'longitud' => 100, 'grosor' => 1]);
	$rueda            = new Rueda($materialRoca, 'RuedaPrueba', ['radio' => 50, 'altura' => 6]);
	$trampa           = new Trampa($cuerda, $cesta, $arcoFlecha, 'TrampaPrueba', 0.1);
	$hacha            = new Hacha($materialMadera, $piedraAfilada, 'HachaPruena', ['radio' => 2, 'altura' => 75]);
	$carro            = new Carro($materialMadera, $cuerda, $cestaCarro, [$rueda, $rueda], $hacha, 'CarroPrueba');
	$cuerdas          = array_fill(0, 10, $cuerda);
	$piedrasAfiladas  = array_fill(0, 10, $piedraAfilada);
	$trampas          = array_fill(0, 5, $trampa);
	$refugios         = array_fill(0, 2, $refugio);
	$ganaderia        = new Ganaderia($cuerdas, $piedrasAfiladas, $trampas, $refugios, 'GanaderiaPrueba');
	$ceramica         = new Ceramica($materialTierra, $fuego, $cesta, 'CeraicaPrueba');
	$alfareria        = new Alfareria($materialTierra, $fuego, $ceramica, $refugioAlfareria, 'AlfareriaPrueba');
	$torno            = new Torno($alfareria, $ceramica, $rueda, 'TornoPrueba', 10, 10);
	$lanzas           = array_fill(0, 20, $lanza);
	$cestas           = array_fill(0, 10, $cesta);
	$agricultura      = new Agricultura($materialTierra, $cestas, $lanzas, $ganaderia, 'AgriculturaPrueba', ['longitud' => 1000, 'ancho' => 500, 'altura' => 200, 'grosor' => 0.1]);

	//Probar inventos
	echo '<h1>Probar inventos</h1>';
	echo '<h2>Invento</h2>';
	Invento::probarInvento();
	echo '<h2>Piedra afilada</h2>';
	PiedraAfilada::probarInvento([$piedraAfilada->material, $piedraAfilada->nombre, $piedraAfilada->tamanyo, 'bosque', 'detallado']);
	echo '<h2>Fuego</h2>';
	Fuego::probarInvento([$fuego->material, $fuego->nombre, 'selva', 'detallado']);
	echo '<h2>Cuerda</h2>';
	Cuerda::probarInvento([$cuerda->material, $cuerda->nombre, $cuerda->tamanyo, 'desierto', 'detallado']);
	echo '<h2>Lanza</h2>';
	Lanza::probarInvento([$lanza->material, $lanza->piedraAfilada, $lanza->cuerda, $lanza->nombre, $lanza->tamanyo, 'montaña', 'detallado']);
	echo '<h2>Arco y flecha</h2>';
	ArcoFlecha::probarInvento([$arcoFlecha->material, $arcoFlecha->lanza, $arcoFlecha->cuerda, $arcoFlecha->nombre, $arcoFlecha->tecnica, $arcoFlecha->tamanyo, 'selva', 'detallado']);
	echo '<h2>Hacha</h2>';
	Hacha::probarInvento([$hacha->material, $hacha->piedraAfilada, $hacha->nombre, $hacha->tamanyo, 'polo', 'detallado']);
	echo '<h2>Cesta</h2>';
	Cesta::probarInvento([$cesta->material, $cesta->nombre, $cesta->tamanyo, 'bosque', 'rapido', 1]);
	echo '<h2>Trampa</h2>';
	Trampa::probarInvento([$trampa->cuerda, $trampa->cesta, $trampa->arcoFlecha, $trampa->nombre, $trampa->visibilidad, 'selva', 'rapido', 2]);
	echo '<h2>Rueda</h2>';
	Rueda::probarInvento([$rueda->material, $rueda->nombre, $rueda->tamanyo, 'selva', 'rapido']);
	echo '<h2>Refugio</h2>';
	Refugio::probarInvento([$refugio->materialTecho, $refugio->materialParedes, $refugio->materialSuelo, $refugio->nombre, $refugio->tamanyo, 'desierto', 'rapido', 3]);
	echo '<h2>Carro</h2>';
	Carro::probarInvento([$carro->material, $carro->cuerda, $carro->cesta, [$carro->rueda1, $carro->rueda2], $carro->hacha, $carro->nombre, 'montaña', 'rapido', 1]);
	echo '<h2>Ganadería</h2>';
	Ganaderia::probarInvento([$ganaderia->cuerdas, $ganaderia->piedrasAfiladas, $ganaderia->trampas, $ganaderia->refugios, $ganaderia->nombre, 'polo', 'rapido', 2]);
	echo '<h2>Cerámica</h2>';
	Ceramica::probarInvento([$ceramica->material, $ceramica->fuego, $ceramica->cesta, $ceramica->nombre, 'bosque', 'detallado', 3]);
	echo '<h2>Alfarería</h2>';
	Alfareria::probarInvento([$alfareria->material, $alfareria->fuego, $alfareria->ceramica, $alfareria->refugio, $alfareria->nombre, 'selva', 'rapido', 1]);
	echo '<h2>Torno</h2>';
	Torno::probarInvento([$torno->alfareria, $torno->ceramica, $torno->rueda, $torno->nombre, $torno->velocidadRotacion, $torno->precision, 'desierto', 'detallado']);
	echo '<h2>Agricultura</h2>';
	Agricultura::probarInvento([$agricultura->material, $agricultura->cestas, $agricultura->lanzas, $agricultura->ganaderia, $agricultura->nombre, $agricultura->tamanyo, 'montaña', 'rapido', 2]);
}
