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

    //Función para poder probar la aplicación en el navagador. No participa en el los tests
    function principal() {
        $materiales = Material::getMateriales();

        echo "<h1>Probar inventos</h1>";
        echo "<h2>Invento</h2>";
        Invento::probarInvento();
        echo "<h2>Piedra afilada</h2>";
        $materialPiedraAfilada = $materiales['carbono'];
        PiedraAfilada::probarInvento(['PiedraAfiladaPrueba', $materialPiedraAfilada, ['base' => 5, 'altura' => 5, 'longitud' => 10], 2.5]);
        echo "<h2>Fuego</h2>";
        $materialFuego = $materiales['resinas_inflamables'];
        Fuego::probarInvento(['FuegoPrueba', 'chispa', $materialFuego]);
        echo "<h2>Cuerda</h2>";
        $materialCuerda = $materiales['tendones'];
        Cuerda::probarInvento(['CuerdaPrueba', 100, $materialCuerda]);
        echo "<h2>Lanza</h2>";
        $materialLanza = $materiales['carbono'];
        $piedraAfilada = new PiedraAfilada('PiedraAfiladaPrueba', $materialPiedraAfilada, ['base' => 5, 'altura' => 10, 'longitud' => 10], 2);
        $cuerda = new Cuerda('CuerdaPrueba', 100, $materialCuerda);
        Lanza::probarInvento(['LanzaPrueba', 2, $materialLanza, $piedraAfilada, $cuerda]);
        echo "<h2>Arco y flecha</h2>";
        $materialArco = $materiales['carbono'];
        $lanza = new Lanza('LanzaPrueba', 2, $materialLanza, $piedraAfilada, $cuerda);
        ArcoFlecha::probarInvento(['ArcoFlechaPrueba', $materialArco, $lanza, $cuerda, 'tallado']);
        echo "<h2>Hacha</h2>";
        $materialHacha = $materiales['roble'];
        Hacha::probarInvento(['HachaPrueba', 60, $materialHacha, $piedraAfilada]);
        echo "<h2>Cesta</h2>";
        $materialCesta = $materiales['tendones'];
        Cesta::probarInvento(["CestaPrueba", $materialCesta, 30, 15, 2]);
        echo "<h2>Trampa</h2>";
        $cesta = new Cesta("CestaPrueba", $materialCesta, 30, 15, 2);
        $arcoFlecha = new ArcoFlecha("ArcoFlechaPrueba", $materialArco, $lanza, $cuerda, 'tallado');
        Trampa::probarInvento(['TrampaPrueba', $cuerda, $cesta, $arcoFlecha, 0.1]);
        echo "<h2>Rueda</h2>";
        $materialRueda = $materiales['obsidiana'];
        Rueda::probarInvento(['RuedaPrueba', 50, 10, $materialRueda]);
        echo "<h2>Refugio</h2>";
        $materialRefugioTecho = $materiales['cañamo'];
        $materialRefugioParedes = $materiales['granito'];
        $materialRefugioSuelo = $materiales['caolinita'];
        Refugio::probarInvento(['RefugioPrueba', $materialRefugioTecho, $materialRefugioParedes, $materialRefugioSuelo, ['base' => 5, 'altura' => 2, 'longitud' => 5], 1.5]);
    }
?>
