<?php
// Función para poder probar la aplicación en el navegador. No participa en los tests
function principal() {
    // Inicializar los arrays de materiales para las diferentes categorías
    $materiales = [
        'arco' => ['Madera de Tejo', 'Bambú', 'Madera de Sauce'],
        'flecha' => ['Madera de Abedul', 'Madera de Cedro', 'Madera de Pino'],
        'cuerda' => ['Lianas', 'Tendones de Animales', 'Fibras de Plantas']
    ];

    // Definir las técnicas disponibles
    $tecnicas = [
        'Técnica de Tallado' => 'suma de las propiedades',
        'Técnica de Trenzado' => 'multiplicación de las propiedades',
        'Técnica de Secado' => 'división de las propiedades'
    ];

    // Generar materiales con valores aleatorios para cada categoría
    $materialesArco = generarMaterialesArcoFlecha('arco', $materiales['arco']);
    $materialesFlecha = generarMaterialesArcoFlecha('flecha', $materiales['flecha']);
    $materialesCuerda = generarMaterialesArcoFlecha('cuerda', $materiales['cuerda']);
    

    // Imprimir las técnicas disponibles
    imprimirTecnicasArcoFlecha($tecnicas);

    // Selección de técnica aleatoria
    $tecnicaAleatoria = seleccionarTecnicaAleatoriaArcoFlecha($tecnicas);
    echo "Se ha seleccionado la técnica aleatoria de: $tecnicaAleatoria<br><br>";
    
    // Imprimir los materiales con sus propiedades
    imprimirMaterialesArcoFlecha('arco', $materialesArco);
    imprimirMaterialesArcoFlecha('flecha', $materialesFlecha);
    imprimirMaterialesArcoFlecha('cuerda', $materialesCuerda);

    // Calcular el mejor material para cada categoría
    $mejorArco = mejorMaterialArcoFlecha('arco', $materialesArco, $tecnicaAleatoria);
    $mejorFlecha = mejorMaterialArcoFlecha('flecha', $materialesFlecha, $tecnicaAleatoria);
    $mejorCuerda = mejorMaterialArcoFlecha('cuerda', $materialesCuerda, $tecnicaAleatoria);

    // Imprimir el mejor material para cada categoría
    imprimirArcoFlecha($mejorArco);
    imprimirArcoFlecha($mejorFlecha);
    imprimirArcoFlecha($mejorCuerda);
}

// Funciones: aquí va la solución del ejercicio
function generarMaterialesArcoFlecha($categoria, $materiales) {
    $propiedades = obtenerPropiedadesPorCategoria($categoria);
   
    // Devolver un array vacío si la categoría es inválida
    if (empty($propiedades)) {
        return []; 
    }

    $materialesConPropiedades = [];

    // Generar propiedades aleatorias para los materiales de la categoría
    foreach ($materiales as $material) {
        $atributos = [];
        foreach ($propiedades as $propiedad) {
            $atributos[$propiedad] = rand(1, 9);
        }
        $materialesConPropiedades[$material] = $atributos;
    }

    return $materialesConPropiedades;
}

function imprimirTecnicasArcoFlecha($tecnicas) {
    echo 'Las técnicas disponibles son:'. '<br>';
    foreach ($tecnicas as $key => $tecnica) {
       echo "$key: se calcula a partir de la $tecnica".'<br>';
    }
}

function seleccionarTecnicaAleatoriaArcoFlecha($tecnicas) {
    $claves = array_keys($tecnicas);
    $claveAleatoria = $claves[array_rand($claves)];
    return $claveAleatoria;
}

function obtenerPropiedadesPorCategoria($categoria) {
    $propiedades = [
        'arco' => ['dureza', 'flexibilidad', 'resistencia'],
        'flecha' => ['dureza', 'peso', 'resistencia'],
        'cuerda' => ['elasticidad', 'resistencia']
    ];

    // Devolver las propiedades de la categoría especificada
    if(array_key_exists($categoria, $propiedades)){
        return $propiedades[$categoria];
    } else {
        return [];
    }
}

function mejorMaterialArcoFlecha($categoria, $materiales, $tecnica) {
    $mejorMaterial = null;
    $mejorValor = null;

    // Iterar sobre los materiales para encontrar el mejor basado en la técnica
    foreach ($materiales as $material => $atributos) {
        switch ($tecnica) {
            case 'Técnica de Tallado':
                $sumaTallado = array_sum($atributos);
                if ($mejorValor === null || $sumaTallado > $mejorValor) {
                    $mejorValor = $sumaTallado;
                    $mejorMaterial = $material; 
                }
                break;

            case 'Técnica de Trenzado':
                $sumaTrenzado = array_product($atributos);
                if ($mejorValor === null || $sumaTrenzado > $mejorValor) {
                    $mejorValor = $sumaTrenzado;
                    $mejorMaterial = $material;  
                }
                break;

            case 'Técnica de Secado':
                $sumaSecado = array_reduce($atributos, function($carry, $item) {
                    $carry = (float)$carry;
                    $item = (float)$item;
                    return ($carry == 0) ? $item : $carry / $item;
                }, null);
                if ($mejorValor === null || $sumaSecado > $mejorValor) {
                    $mejorValor = $sumaSecado;
                    $mejorMaterial = $material; 
                }
                break;

            default:
                reset($materiales);
                $mejorMaterial = key($materiales);  
                break;
        }
    }

    // Devolver el mejor material y su valor
    return [
        'categoria' => $categoria,
        'mejorMaterial' => $mejorMaterial,
        'mejorValor' => $mejorValor
    ];
}

function imprimirMaterialesArcoFlecha($categoria, $materiales) {
    echo "Materiales de $categoria:".'<br>';
    echo "<table border='1'>";
    echo "<tr><th>Material</th>";

    // Obtener las propiedades de la categoría
    $propiedades = obtenerPropiedadesPorCategoria($categoria);
    foreach ($propiedades as $propiedad) {
        echo "<th>$propiedad</th>";
    }
    echo "</tr>";

    // Imprimir los materiales y sus propiedades
    foreach ($materiales as $material => $atributos) {
        echo "<tr><td>$material</td>";
        foreach ($propiedades as $propiedad) {
            echo "<td>{$atributos[$propiedad]}</td>";
        }
        echo "</tr>";
    }
    echo "</table>";
}

function imprimirArcoFlecha($mejor) {
    echo "El mejor material para " . $mejor['categoria'] . " es con el valor " . $mejor['mejorValor'] . ' es: ' . $mejor['mejorMaterial'] . "<br>";
}


?>