<?php
    //Función para poder probar la aplicación en el navagador. No participa en el los tests
    function principal() {
        $materiales=[
            'Piedra' => [ 'peso'=> 4, 'flexibilidad' => 1, 'dureza' => 7 ],
            'Cuerno' => [ 'peso'=> 2, 'flexibilidad' => 4, 'dureza' => 6 ],
            'Marfil' => [ 'peso'=> 3, 'flexibilidad' => 3, 'dureza' => 5 ],
             'Hueso' => [ 'peso'=> 2, 'flexibilidad' => 4, 'dureza' => 5 ],
            'Madera' => [ 'peso'=> 2, 'flexibilidad' => 5, 'dureza' => 3 ],
             'Bambu' => [ 'peso'=> 1, 'flexibilidad' => 7, 'dureza' => 3 ],
               'Vid' => [ 'peso'=> 1, 'flexibilidad' => 5, 'dureza' => 2 ],
             'Cañas' => [ 'peso'=> 1, 'flexibilidad' => 8, 'dureza' => 2 ],
 'Fibras de plantas' => [ 'peso'=> 1, 'flexibilidad' => 6, 'dureza' => 2 ],
    'Piel de animal' => [ 'peso'=> 1, 'flexibilidad' => 7, 'dureza' => 1 ],
        ];
        $climas = ['Lluvia', 'Viento', 'Soleado', 'Nevado'];

    
    $impactoClimatico = generarImpactoClimaticoLanza($materiales, $climas);
    $lanza = generarLanza($materiales);
    $efectividades = calcularEfectividadesLanza($lanza, $materiales);
    imprimirLanza($lanza);
    imprimirEfectividadLanza($efectividades);
    aplicarImpactoClimaticoLanza($lanza, $efectividades, $impactoClimatico, $climas);
    
    }

    //Funciones: aquí va la solución del ejercicio
    function generarImpactoClimaticoLanza($materiales, $climas) {
        $impactosClima = []; // matriz para almacenar impactos

        // Iterar sobre cada material
        foreach ($materiales as $material => $propiedades) {
            $impactosMaterial = []; // Inicializa el array para el material actual

            // Itera sobre cada clima
            foreach ($climas as $clima) {
                // Generar un impacto aleatorio entre -10 y 10
                $impacto = rand(-10, 10);

                // Asignar el impacto al clima correspondiente
                $impactosMaterial[$clima] = $impacto;
            }

            // Asignar el array de impactos por clima al material
            $impactosClima[$material] = $impactosMaterial;
        }

        return $impactosClima;
    }

    function generarLanza($materiales) {
        // cantidad de materiales aleatorios y longitud aleatoria
        $totalMateriales = count($materiales);
        $seleccionAleatoria = rand(1, min(3, $totalMateriales));
        $longitudAleatoria = rand(1, 3);
        $materialesAleatorios = array_rand($materiales, $seleccionAleatoria);

        // Asegurarse de que $materialesAleatorios sea un array
        if (!is_array($materialesAleatorios)) {
            $materialesAleatorios = [$materialesAleatorios];
        }

        $mat_y_long = [
            'longitud' => $longitudAleatoria,
            'materiales' => $materialesAleatorios
        ];

        return $mat_y_long;
    }

    function calcularEfectividadesLanza($lanza, $materiales) {
        $efectividadBase = 50;
        $efectividades = [];
        $longitudLanza = $lanza['longitud'];
        $materialesLanza = $lanza['materiales'];
        $cantidadMateriales = count($materialesLanza);
        $pesoLanza = 0;
        $flexLanza = 0;
        $durezaLanza = 0;

        foreach ($materialesLanza as $materialLanza) {
            // Verificar si el material de la lanza existe en el array $materiales
            if (array_key_exists($materialLanza, $materiales)) {
                $material = $materiales[$materialLanza]; // Obtener el material del array original
                $pesoLanza += $material['peso'];
                $flexLanza += $material['flexibilidad'];
                $durezaLanza += $material['dureza'];
            }
        }

        // calculos intermedios para los condicionales
        $pesoPorLongitud = $pesoLanza / $longitudLanza;
        $flexPorMateriales = $flexLanza / $cantidadMateriales;
        $durPorMateriales = $durezaLanza / $cantidadMateriales;

        // iniciar efectividades
        $efectMediaDist = $efectividadBase;
        $efectLargaDist = $efectividadBase;
        $efectCuerpoCuerpo = $efectividadBase;

        // efectividades Larga Distancia
        if (($pesoPorLongitud <= 2 && $flexPorMateriales >= 3) ||
            ($pesoPorLongitud <= 2 && $durPorMateriales >= 6) ||
            ($flexPorMateriales >= 3 && $durPorMateriales >= 6)) {
            $efectLargaDist *= 2;
        }

        // Efectividad Media Distancia
        if ($pesoLanza <= 6 || $flexPorMateriales >= 4 || $durPorMateriales >= 5) {
            $efectMediaDist *= 2;
        }

        // Efectividad Cuerpo a Cuerpo
        if ($pesoLanza <= 8 && $flexPorMateriales >= 5 && $durPorMateriales >= 4) {
            $efectCuerpoCuerpo *= 2;
        }

        // limitar a 100
        $efectLargaDist = min($efectLargaDist, 100);
        $efectMediaDist = min($efectMediaDist, 100);
        $efectCuerpoCuerpo = min($efectCuerpoCuerpo, 100);

        $efectTotales = [
            'largaDistancia' => $efectLargaDist,
            'mediaDistancia' => $efectMediaDist,
            'cuerpoACuerpo' => $efectCuerpoCuerpo
        ];

        return $efectTotales;
    }

    function imprimirLanza($lanza) {
        echo 'Lanza: <br>';
        echo 'Longitud: ' . $lanza['longitud'] . ' m<br>';
        echo 'Materiales: ' . implode(', ', $lanza['materiales']) . '<br><br>';
    }

    function imprimirEfectividadLanza($efectividades) {
        echo 'Efectividad a larga distancia: ' . $efectividades['largaDistancia'] . '%<br>';
        echo 'Efectividad a media distancia: ' . $efectividades['mediaDistancia'] . '%<br>';
        echo 'Efectividad en cuerpo a cuerpo: ' . $efectividades['cuerpoACuerpo'] . '%<br>';
        echo '-----------------------------------<br>';
    }

    function aplicarImpactoClimaticoLanza($lanza, $efectividades, $impactoClimatico, $climas) {
        imprimirEfectividadLanza($efectividades);
        foreach ($climas as $clima) {
            // efectividades sin clima
            echo "Resultados para el clima: $clima<br>";

            // guardar los valores en variables
            $largaDistancia = $efectividades['largaDistancia'];
            $mediaDistancia = $efectividades['mediaDistancia'];
            $cuerpoCuerpo = $efectividades['cuerpoACuerpo'];

            // recorrer materiales, calcular el impacto para cada 1 y asignar a cada clima
            foreach ($lanza['materiales'] as $material) {
                $impacto = $impactoClimatico[$material][$clima];

                $largaDistancia += $impacto;
                $mediaDistancia += $impacto;
                $cuerpoCuerpo += $impacto;
            }

            $efectividadesClima = [
                'largaDistancia' => $largaDistancia,
                'mediaDistancia' => $mediaDistancia,
                'cuerpoACuerpo' => $cuerpoCuerpo,
            ];

            imprimirEfectividadLanza($efectividadesClima);
        }
    }
?>