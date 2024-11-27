<?php
    //Función para poder probar la aplicación en el navagador. No participa en el los tests
    
    $humedad = 60;
    $viento = 15;
    $temperatura = 5;
    $metodo = 'friccion';
    
    function principal() {
        global $humedad, $viento, $temperatura, $metodo;

        $tiempoBase = seleccionarMetodoFuego($metodo);
        $factorCalculado = calcularFactorFuego($humedad, $viento, $temperatura);
        $tiempoEstimado = $tiempoBase * $factorCalculado;
        $inicio = new DateTime('2024-10-13 12:00:00');
        $fin = calcularTiempoFinalFuego($inicio, $tiempoEstimado);
        imprimirFuego($metodo, $humedad, $viento, $temperatura, $tiempoEstimado, $inicio, $fin);
    }

    //Funciones: aquí va la solución del ejercicio
    function seleccionarMetodoFuego($metodo) {
        
        return match($metodo){
            'friccion'  => 30,
            'chispa'    => 20,
            'lupa'      => 10,
            default     => 0,
        };
    }

    function calcularFactorFuego($humedad, $viento, $temperatura) {
        $factor = 1;
        if($humedad > 50) $factor += 0.5;
        if($viento > 20) $factor += 0.3;
        if($temperatura < 10) $factor += 0.2;
        
        return $factor;
    }

    function calcularTiempoFinalFuego($inicio, $tiempoEstimado) {
       
        $intervaloTiempo = new DateInterval('PT'. $tiempoEstimado . 'M');
        $horaFinal = clone $inicio;
        $horaFinal -> add($intervaloTiempo);

        return $horaFinal;
    }

    function imprimirFuego($metodo, $humedad, $viento, $temperatura, $tiempoEstimado, $inicio, $fin) {
        
        echo "Método utilizado: $metodo<br>";
        echo 'Condiciones ambientales: '. $humedad ."% de humedad, con un viento de $viento km/h y una temperatura de $temperatura °C<br>";
        echo "Tiempo estimado para encender el fuego: $tiempoEstimado minutos<br>";
        echo 'Hora de inicio: ' . $inicio->format('H:i:s') . '<br>';
        echo 'Hora estimada de encendido: ' . $fin->format('H:i:s') .'<br>';
    }

?>
