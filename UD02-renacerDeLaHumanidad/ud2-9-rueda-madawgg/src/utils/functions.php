<?php
    //Función para poder probar la aplicación en el navegador. No participa en los tests
    function principal() {
        define('G', 9.81);
        define('RHO', 1.225);
        define('CD', 1.0);

        $radios = range(25, 100);
        $grosores = range(5, 50);
        $materiales = ['Madera', 'Piedra', 'Combinado'];
        $cantidad = 3;

        $generarRuedas = generarRuedas($radios, $grosores, $materiales, $cantidad);
        $calcularAtributos = calcularAtributosRueda($generarRuedas);
        imprimirRueda($calcularAtributos);
    }

    //Funciones: aquí va la solución del ejercicio
    function valorAleatorioRueda($array) {
        if (empty($array)) {
            throw new InvalidArgumentException("El array está vacío");
        }

        $claveAleatoria = array_rand($array);
        return $array[$claveAleatoria];
    }

    function generarRuedas($radios, $grosores, $materiales, $cantidad) {
        $ruedas = [];

        if ($cantidad <= 0) {
            return $ruedas;
        }

        for ($i = 0; $i < $cantidad; $i++) {
            $radioAleatorio = valorAleatorioRueda($radios);
            $grosorAleatorio = valorAleatorioRueda($grosores);
            $materialAleatorio = valorAleatorioRueda($materiales);

            $ruedas[] = [
                'radio' => $radioAleatorio,
                'grosor' => $grosorAleatorio,
                'material' => $materialAleatorio
            ];
        }

        return $ruedas;
    }

    function calcularAtributosRueda(&$ruedas) {
        global $G, $RHO, $CD;

        $densidades = ['Madera' => 700, 'Piedra' => 2500, 'Combinado' => 1600];

        foreach ($ruedas as &$rueda) {
            $radio = $rueda['radio'] * 0.01;  
            $grosor = $rueda['grosor'] * 0.01; 

            if ($radio <= 0 || $grosor <= 0) {
                $rueda['peso'] = 0;
                $rueda['durabilidad'] = 0;
                $rueda['velocidad'] = 0;
                continue;
            }

            $material = $rueda['material'];

            if (!array_key_exists($material, $densidades)) {
                throw new InvalidArgumentException("El material no está en la lista");
            }

            $densidad = $densidades[$material];
            $volumenCilindro = pi() * pow($radio, 2) * $grosor; 
            $peso = $volumenCilindro * $densidad; 

            $areaFrontal = pi() * pow($radio, 2);

            if ($areaFrontal > 0) {
                $velocidadTerminal = sqrt((2 * $peso * G) / (CD * RHO * $areaFrontal));
            } else {
                $velocidadTerminal = 0;
            }

            $durabilidad = match ($material) {
                'Madera' => 50,
                'Piedra' => 100,
                'Combinado' => 75,
                default => 0
            };

            $rueda['peso'] = $peso;
            $rueda['velocidad'] = $velocidadTerminal;
            $rueda['durabilidad'] = $durabilidad;
        }

        return $ruedas;
    }

    function imprimirRueda($ruedas) {
        if (empty($ruedas)) {
            return '';
        }

        foreach ($ruedas as $index => $rueda) {
            echo 'Rueda ' . ($index + 1) . ':<br>';
            echo 'Radio: ' . $rueda['radio'] . ' cm<br>';
            echo 'Grosor: ' . $rueda['grosor'] . ' cm<br>';
            echo 'Material: ' . $rueda['material'] . '<br>';
            if (isset($rueda['peso'])) {
                echo 'Peso: ' . round($rueda['peso'], 1) . ' kg<br>';
            }
            if (isset($rueda['durabilidad'])) {
                echo 'Durabilidad: ' . $rueda['durabilidad'] . ' km<br>';
            }
            if (isset($rueda['velocidad'])) {
                echo 'Velocidad: ' . round($rueda['velocidad'], 1) . ' m/s<br>';
            }
            echo '--------------------<br>';
        }
    }
?>
