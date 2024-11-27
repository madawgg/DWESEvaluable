<?php

    define('FACTOR_DUREZA', 1.5);
    $material = 'Granito';
    $longitud = 15;
    $peso = 500;
    
    
    //Función para poder probar la aplicación en el navagador. No participa en el los tests
    function principal() {
        imprimirEficienciaPiedraAfilada(calcularEficienciaPiedraAfilada());       
    }

    //Funciones: aquí va la solución del ejercicio
    function calcularEficienciaPiedraAfilada() {
        global $FACTOR_DUREZA, $material, $longitud, $peso;
        $resultado = [];
        $resultado['material'] = $material;
        $resultado['longitud'] = $longitud;
        $resultado['peso'] = $peso;

        try {
            
            if($peso == 0){ 
                throw new DivisionByZeroError("Error: División por cero");
            }

            if(!is_numeric($longitud) || !is_numeric($peso)){
                $resultado['eficiencia'] = 0;
            } else {
                $eficiencia = $longitud/$peso * FACTOR_DUREZA;
                $resultado['eficiencia'] = round($eficiencia, 3);
            }
 

        } catch (DivisionByZeroError $e) {
            $eficiencia =  $e->getMessage();
            $resultado ['eficiencia'] = $eficiencia;
        }
        
        
        return $resultado;

    }

    function imprimirEficienciaPiedraAfilada($resultado) {
        
            echo 'Material: ' . $resultado['material'] . '<br>';
            echo 'Longitud: ' . $resultado['longitud'] . ' cm<br>';
            echo 'Peso: ' . $resultado['peso'] . ' g<br>';
            echo 'Eficiencia: ' . $resultado['eficiencia'] . ' cm/g<br>';
        
    }

?>
