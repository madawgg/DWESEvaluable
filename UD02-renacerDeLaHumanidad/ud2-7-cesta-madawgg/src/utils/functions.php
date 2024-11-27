<?php
    //Función para poder probar la aplicación en el navagador. No participa en el los tests
    function principal() {
        $materiales= [
            'Madera' => 'Ideal para almacenar objetos pesados.',
            'Bambú' => 'Ligera y resistente, perfecta para frutas.',
            'Mimbre' => 'Flexible y duradera, ideal para picnic.',
            'Rattan' => 'Elegante y robusta, perfecto para decoración.',
            'Hojas de palma' => 'Ecológica y biodegrable, ideal para alimentos.' 
        ];
        
        $zonas = ['Bosque', 'selva', 'Pradera', 'Desierto'];
        
        $humedad = [
            'Bosque' => 70,
            'Selva' => 90,
            'Pradera' => 50,
            'Desierto' => 20
        ];
        
        $temperatura = [
            'Bosque' => 15,
            'Selva' => 25,
            'Pradera' => 20,
            'Desierto' => 35
        ];

        $materialSeleccionado = seleccionarAleatorioCesta($materiales);   
        $zonaSeleccionada = seleccionarAleatorioCesta($zonas); 
        $cesta = obtenerDescripcionCesta($materiales, $materialSeleccionado);
        $probabilidad = calcularProbabilidadMaterialCesta($materialSeleccionado, $zonaSeleccionada, $humedad, $temperatura);
        imprimirCesta($materialSeleccionado, $cesta, $zonaSeleccionada, $probabilidad);
    }

    //Funciones: aquí va la solución del ejercicio
    function seleccionarAleatorioCesta($array) {
       
        if ($array !== null && !empty($array)) {
            $claveAleatoria = array_rand($array);

            if (array_keys($array) !== range(0, count($array) - 1)) {
                return $claveAleatoria; 
            } else {
                return $array[$claveAleatoria]; 
            }
        }
        return null;
    }

    function obtenerDescripcionCesta($materiales, $material) {
        
        if(array_key_exists($material, $materiales)){
            $materialEncontrado = $materiales[$material];
            return $materialEncontrado;
        }else{
            return 'Material no disponible.';
        }
    }

    function calcularProbabilidadMaterialCesta($material, $zona, $humedad, $temperatura) {
        $probabilidades = [
            'Madera' => ['probabilidadHumedad' => 0.5, 'probabilidadTemperatura' => 0.1],
            'Bambú' => ['probabilidadHumedad' => 0.7, 'probabilidadTemperatura' => 0.2],
            'Mimbre' => ['probabilidadHumedad' => 0.6, 'probabilidadTemperatura' => 0.15],
            'Rattan' => ['probabilidadHumedad' => 0.8, 'probabilidadTemperatura' => 0.3],
            'Hojas de palma' => ['probabilidadHumedad' => 0.9, 'probabilidadTemperatura' => 0.25]
        ];

        if (array_key_exists($material, $probabilidades) && array_key_exists($zona, $humedad) && array_key_exists($zona, $temperatura)) {
            $probabilidadHumedad = $probabilidades[$material]['probabilidadHumedad'];
            $probabilidadTemperatura = $probabilidades[$material]['probabilidadTemperatura'];
            
            $humedadZona = $humedad[$zona];
            $temperaturaZona = $temperatura[$zona];
            
            $probEncontrarMaterial = ($humedadZona * $probabilidadHumedad) + ($temperaturaZona * $probabilidadTemperatura);

            return $probEncontrarMaterial;
        }else{
            $probEncontrarMaterial = 0;
            
        }
        return $probEncontrarMaterial;

    }

    function imprimirCesta($material, $cesta, $zona, $probabilidad) {
        $probabilidad = round($probabilidad, 0);
        
        if($probabilidad >= 70) {
            $claseProbabilidad = 'alta';
        } elseif ($probabilidad >= 40) {
            $claseProbabilidad = 'media';
        } else {
            $claseProbabilidad = 'baja';
        }

        

        $html = "
            <!DOCTYPE html>
        <html lang='es'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Cestas Naturales</title>
        <style>
            body {
                font-family: Arial, sans-serif;
            }
            .cesta {
                border: 1px solid #ccc;
                padding: 20px;
                margin: 20px;
                width: 400px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            }
            .cesta h2 {
                margin-top: 0;
            }
            .alta {
                color: green;
                font-weight: bold;
            }
            .media {
                color: orange;
                font-weight: bold;
            }
            .baja {
                color: red;
                font-weight: bold;
            }
        </style>
    </head>
    <body>
        <div class='cesta'>
            <h2>Cesta de $material</h2>
            <p>Descripción: $cesta</p>
            <p>Zona: $zona</p>
            <p class=\"$claseProbabilidad\">Probabilidad de encontrar $material: $probabilidad%</p>
        </div>
    </body>
    </html>
    ";
        echo $html;
    }
?>
