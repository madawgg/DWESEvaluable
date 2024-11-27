<?php
    //Función para poder probar la aplicación en el navagador. No participa en el los tests
    function principal() {
        $materiales = [
            'Sílex' => 50,
            'Basalto' => 75,
            'Granito' => 100,
            'Obsidiana' => 150 
        ];


        echo 'Se ha encontrado una mina de materiales<br>';

        $capacidadDeRecoleccion = rand(5,25);

        echo "La capacidad de recolección de la mina es de: $capacidadDeRecoleccion intentos<br><br>";

        //depurar
        $iniciarInventario = inicializarInventarioHacha($materiales);
        $recogerMateriales = recogerMaterialesHacha($materiales, $iniciarInventario, $capacidadDeRecoleccion);
        $calcularHachas = calcularHachasFabricadas($materiales, $iniciarInventario);
        imprimirInventarioHacha($recogerMateriales);
        imprimirHacha($calcularHachas, $iniciarInventario);
    }

    //Funciones: aquí va la solución del ejercicio
    function inicializarInventarioHacha($materiales) {
        $inventario = [];
        foreach ($materiales as $key => $material) {
            $inventario[$key] = 0;
        }
        
        return $inventario;
    }

    function recogerMaterialesHacha($materiales, &$inventario, $intentos) {
        
        for ($i=0; $i < $intentos ; $i++) { 
            $piedraAleatoria = array_rand($materiales);
            $cantidadAleatoria = rand(100, 500);
            
            $inventario[$piedraAleatoria] += $cantidadAleatoria;

            echo "Material encontrado: $piedraAleatoria, Cantidad: $cantidadAleatoria<br>";
        }
        return $inventario;
    }

    function calcularHachasFabricadas($materiales, &$inventario) {
        echo '<br>';

        $cantidadHachas = [];
        
        foreach ($materiales as $key => $material) {
            echo "Coste de fabricación del material $key: es $material<br>";
        }
    
        foreach ($materiales as $materialKey => $costeMaterial) {  
            
                $cantidadDisponible = $inventario[$materialKey];
                $hachasPosibles = floor($cantidadDisponible / $costeMaterial);  
                $cantidadHachas[$materialKey] = $hachasPosibles;
                
                $inventario[$materialKey] -= $hachasPosibles * $costeMaterial;
        }
    
        return $cantidadHachas;
    }

    function imprimirInventarioHacha($inventario) {
        echo "Inventario recogido:<br>";
        foreach ($inventario as $material => $cantidad) {
            echo "$material: $cantidad<br>";
        }
    }

    function imprimirHacha($hachasFabricadas, $inventario) {
        echo "Resumen final:<br>";
            foreach ($hachasFabricadas as $material => $cantidadHachas) {
                $cantidadRestante = $inventario[$material];

                if($cantidadHachas > 0 || $cantidadRestante > 0){
                echo "Material: $material, Hachas fabricadas: $cantidadHachas, Restante: $cantidadRestante<br>";
                }
            }   

    }
?>