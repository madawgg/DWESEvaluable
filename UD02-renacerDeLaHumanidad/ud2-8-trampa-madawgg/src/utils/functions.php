<?php
    //Función para poder probar la aplicación en el navagador. No participa en el los tests
    function principal() {
        
        $pesoBase = 1;
        $volumenBase = 0.15;
        
        $trampas = [
            'Trampa de Roca' => ['material' => 'Roca', 'peso_max' => 900, 'volumen_max' => 1.4],
            'Trampa de Madera' => ['material' => 'Madera', 'peso_max' => 50, 'volumen_max' => 1.0],
            'Trampa de Hueso' => ['material' => 'Hueso', 'peso_max' => 30, 'volumen_max' => 0.5],
            'Trampa de Lianas' => ['material' => 'Lianas', 'peso_max' => 20, 'volumen_max' => 0.3],
        ];

        $condiciones = [
            'Bosque' => ['humedad' => 70, 'temperatura' => 15],
            'Selva' => ['humedad' => 90, 'temperatura' => 25],
            'Pradera' => ['humedad' => 50, 'temperatura' => 20],
            'Desierto' => ['humedad' => 20, 'temperatura' => 35],
        ];

        $animales = [
            'Conejo' => ['peso' => rand($pesoBase, $pesoBase * 2), 'volumen' => rand(1, 3) * $volumenBase],
            'Ciervo' => ['peso' => rand($pesoBase * 20, $pesoBase * 40), 'volumen' => rand(6, 10) * $volumenBase],
            'Jabalí' => ['peso' => rand($pesoBase * 10, $pesoBase * 20), 'volumen' => rand(6, 8) * $volumenBase],
            'Mamut' => ['peso' => rand($pesoBase * 250, $pesoBase * 500), 'volumen' => rand(9, 15) * $volumenBase]
        ];

        $zonas = array_keys($condiciones); 
        $zona = $zonas[array_rand($zonas)];

        imprimirPaginaTrampa($trampas, $zona, $condiciones, $animales);

    }


    //Funciones: aquí va la solución del ejercicio
    function imprimirPaginaTrampa($trampas, $zona,  $condiciones, $animales ) {
        
        echo    "<!DOCTYPE html>
                <html lang=\"es\">
                <head>
                    <meta charset=\"UTF-8\">
                    <title>Evaluación de trampas prehistóricas</title>
                    <style>
                        .si { color: green;}
                        .no { color: red;}
                    </style>
                </head>
                    <body>
                        <h1>Evaluación de la Efectividad de Trampas Prehistóricas</h1>";
                        imprimirCondicionesTrampa($zona, $condiciones, $trampas, $animales);
            echo"</body>
                </html>";
    }

    function imprimirCondicionesTrampa($zona, $condiciones, $trampas, $animales) {
        $condiciones = $condiciones[$zona];
       
        $humedad = $condiciones['humedad'];
        $temperatura = $condiciones['temperatura'];
        
        echo "
        <h2>Condiciones en $zona</h2>
        <p>Humedad: $humedad%, Temperatura: $temperatura".'°C</p>
        ';

        foreach ($trampas as $trampa => $caracteristicas) {
            imprimirTrampa($trampa, $caracteristicas, $animales);
        }
    
    }

    function imprimirTrampa($trampa, $caracteristicas, $animales) {
        $peso_max = $caracteristicas['peso_max'];
        $volumen_max = $caracteristicas['volumen_max'];

        echo "<h3>Trampa: $trampa (Material: ". $caracteristicas['material'] . ")</h3>";

        foreach ($animales as $animal => $caracteristicas_animal) {
            imprimirEvaluacionAnimalTrampa($animal, $caracteristicas_animal, $peso_max, $volumen_max);
        }
        
    }

    function imprimirEvaluacionAnimalTrampa($animal, $caracteristicas_animal, $peso_max, $volumen_max) {
        $pesoAnimal = $caracteristicas_animal['peso'];
        $volumenAnimal = $caracteristicas_animal['volumen'];

        $evaluacion = ($pesoAnimal <= $peso_max && $volumenAnimal <= $volumen_max) ? 'Sí' : 'No';
        $evalClase =  ($pesoAnimal <= $peso_max && $volumenAnimal <= $volumen_max) ? 'si' : 'no';

        echo "<p class=\"$evalClase\">Animal: $animal, Peso: $pesoAnimal kg, Volumen: ". number_format($volumenAnimal,1) . " m³ - Puede atrapar: $evaluacion</p>";
    }
?>
