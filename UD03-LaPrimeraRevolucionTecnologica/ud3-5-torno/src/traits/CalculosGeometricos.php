<?php

trait CalculosGeometricos{
    
    public function volumen(string $figura, array $dimensiones): float {
        try {
            $volumen = 0.00; // Inicializamos la variable para el retorno
    
            switch ($figura) {
                case 'Cubo':
                    $lado = $dimensiones['lado'];
                    $volumen = pow($lado, 3);
                    break;
                case 'Esfera':
                    $radio = $dimensiones['radio'];
                    $volumen = (4 / 3) * pi() * pow($radio, 3);
                    break;
                case 'Tetraedro':
                    $base= $dimensiones['base'];
                    $longitud = $dimensiones ['longitud'];
                    $altura = $dimensiones ['altura'];
                    
                    $volumen = (1 / 3) * 0.5 * $base * $longitud * $altura;
                    break;
                case 'Cilindro':
                    $radio = $dimensiones['radio'];
                    $altura = $dimensiones['altura'];
                    
                    $volumen = pi() * pow($radio, 2) * $altura;
                    break;
                case 'Prisma Rectangular':
                    $longitud = $dimensiones['longitud'];
                    $ancho = $dimensiones['ancho'];
                    $altura = $dimensiones['altura'];

                    $volumen = $longitud * $ancho * $altura;
                    break;
                default:
                    throw new Exception("Figura no soportada para volumen");
            }
    
            return round($volumen, 2);
    
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return 0.00;
        }
    }
    
    public function area(string $figura, array $dimensiones): float {
        try {
            $area = 0.00; // Inicializamos la variable para el retorno
    
            switch ($figura) {
                case 'Cubo':
                    $lado = $dimensiones['lado'];
                    
                    $area = pow($lado, 2) * 6;
                    break;

                case 'Esfera':
                    $radio = $dimensiones['radio'];

                    $area = 4 * pi() * pow($radio, 2);
                    break;

                case 'Tetraedro':
                    $base = $dimensiones['base'];
                    $longitud = $dimensiones['longitud'];
                    $altura = $dimensiones['altura'];

                    $area = (($base * $longitud) / 2) +
                            (($base * $altura) / 2) +
                            (($longitud * $altura) / 2);
                    break;

                case 'Cilindro':
                    $radio = $dimensiones['radio'];
                    $altura = $dimensiones['altura'];

                    $area = (2 * pi() * $radio * $altura) + 
                            (2 * pi() * pow($radio, 2));
                    break;

                case 'Prisma Rectangular':
                    $longitud = $dimensiones['longitud'];
                    $ancho = $dimensiones['ancho'];
                    $altura = $dimensiones['altura'];

                    $area = 2 * (($longitud * $ancho) +
                                ($ancho * $altura) +
                                ($longitud * $altura));
                    break;

                default:
                    throw new Exception("Figura no soportada para área");
            }
    
            return round($area, 2);
    
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return 0.00;
        }
    }

    public function superficie(string $figura, array $dimensiones): float{
        try {
            $superficie = 0.00;
            switch ($figura) {
                case 'Cubo':
                    $lado = $dimensiones['lado'];

                    $superficie = 6 * pow($lado, 2);
                    break;
                
                case 'Esfera':
                    $radio = $dimensiones['Radio'];

                    $superficie = 4 * pi() * pow($radio, 2);
                    break;

                case 'Tetraedro':
                    $base = $dimensiones['base'];
                    $altura = $dimensiones['altura'];
                    $longitud = $dimensiones['longitud'];

                    $superficie = (0.5 * $base * $longitud) +
                                  (0.5 * $base * $altura) +
                                  (0.5 * $altura * $longitud);
                    break;

                case 'Cilindro':
                    $radio = $dimensiones['radio'];
                    $altura = $dimensiones['altura'];

                    $superficie = 2 * pi() * $radio * ($radio + $altura);
                    break;

                case 'Prisma Rectangular':
                    $longitud = $dimensiones['longitud'];
                    $ancho = $dimensiones['ancho'];
                    $altura = $dimensiones['altura'];

                    $superficie = 2 * (($longitud * $ancho) +
                                      ($ancho * $altura) +
                                      ($longitud * $altura));
                    break;

                default:
                    throw new Exception("Figura no soportada para superficie.");
                    break;
            }
            return round($superficie, 2);
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return 0.00; 
        }
    }
}

?>