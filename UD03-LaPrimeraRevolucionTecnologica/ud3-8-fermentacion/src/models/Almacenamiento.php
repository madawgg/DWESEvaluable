<?php

include_once 'Invento.php';
include_once 'src/traits/CalculosGeometricos.php';
include_once 'src/interfaces/Medible.php';

abstract class Almacenamiento extends Invento implements Medible{
    use CalculosGeometricos;
    protected const FIGURA_ELEMENTO = 'Cubo';
    protected int $numeroElementos;
    protected array $tamanyo;
    protected int $capacidad;
    protected int $espacioDisponible;
    protected int $porcentajeLleno;
    
    public function __construct(string $nombre, array $tamanyo, int $numeroElementos){
        parent::__construct($nombre);
        $this->numeroElementos = $numeroElementos;
        $this->tamanyo = $tamanyo;
        $this->tamanyo['lado'] = static::TAMANYO_ELEMENTO;
        $this->capacidad = $this->calcularCapacidad();
        $this->espacioDisponible = $this->calcularEspacioDisponible();
        $this->porcentajeLleno = $this->calcularPorcentajeLleno();

    }
    public function getFiguraElemento(): string{
        return static::FIGURA_ELEMENTO;
    }
    public function getTamanyoElemento(): int{
        return static::TAMANYO_ELEMENTO;
    }
    public function __get($atributo) {
        if(property_exists($this,$atributo)){
            return $this->$atributo;
        }else{
            echo "el atributo $atributo no existe en almacenamiento<br>";
        }
    }

    public function __set($atributo, $valor){
        if(property_exists($this, $atributo)){
            $this->$atributo = $valor;
        }else{
            echo "El atributo $atributo no se ha modificado en almacenamiento<br>";
        }
    }

    public function capacidad(string $figura, array $dimensiones): int{
        
        $grosor = $dimensiones['grosor'] ?? 0;
        $altura = $dimensiones['altura'] ?? 0;
        $base = $dimensiones['base'] ?? 0;
        $radio = $dimensiones['radio'] ?? 0;
        $longitud = $dimensiones['longitud'] ?? 0;
        $ancho = $dimensiones['ancho'] ?? 0;
        $lado = $dimensiones['lado'] ?? 0;
        
        $capacidad = match($figura) {
            'Cubo' => pow($lado - 2 * $grosor, 3),

            'Esfera' => (4 / 3) * pi() * pow($radio - $grosor, 3),

            'Tetraedro' => (1 / 3) * 0.5 * 
                           ($base - 2 * $grosor) * 
                           ($longitud - 2 * $grosor) * 
                           ($altura - 2 * $grosor), 

            'Cilindro' => pi() * pow($radio - $grosor, 2) *
                          ($altura - 2 * $grosor),

            'Prisma Rectangular' => (($longitud - 2) * $grosor) * 
                                    (($ancho - 2) * $grosor) * 
                                    (($altura - 2) * $grosor),

            default => throw new InvalidArgumentException("El elemento figura '$figura' no es válido para capacidad."),
        };

        return round($capacidad);
        
    }

    public function calcularCapacidad(): int {
       
        $capacidadTotal = $this->capacidad(static::FIGURA, $this->tamanyo); 
        $volumenElemento = $this->volumen(self::FIGURA_ELEMENTO, $this->tamanyo);
        $cantidadElementos =  $capacidadTotal / $volumenElemento;


        return round($cantidadElementos);
    }

    public function calcularEspacioDisponible(): int{
        $espacioDisponible = $this->capacidad - $this->numeroElementos;

        return round($espacioDisponible);
    }

    public function calcularPorcentajeLleno(): int{
        $capacidadTotal = $this->capacidad;
        
        if($capacidadTotal === 0){        
            $porcentajeLleno = 100;
        }else{
        
            $espacioDisponible = $this->espacioDisponible;   
        
            $porcentajeLleno = round(($capacidadTotal - $espacioDisponible) / 
                                    $capacidadTotal * 100);       
        }

        return round($porcentajeLleno); 
    
    }

    public function __toString(): string{
        $figura = $this->getFigura();
        $tamanyoElemento = $this->getTamanyoElemento() / 100;
        $html = parent::__toString();
        
        switch($figura){
            case 'Cubo':
                $lado = $this->tamanyo['lado'] / 100;
                $grosor = $this->tamanyo['grosor'] / 100;

                $html .= "<tr><th>Figura</th><td>" . htmlspecialchars(ucfirst($figura)) . "</td></tr>";
                $html .= "<tr><th>Tamaño</th><td>" . htmlspecialchars("Lado: $lado m, Grosor: $grosor m") . "</td></tr>";
                $html .= "<tr><th>Tamaño elemento (lado)</th><td>" . htmlspecialchars($tamanyoElemento) . " m</td></tr>";
                $html .= "<tr><th>Número de Elementos</th><td>" . htmlspecialchars($this->numeroElementos) . " elementos</td></tr>";
                $html .= "<tr><th>Capacidad</th><td>" . htmlspecialchars($this->capacidad) . " elementos</td></tr>";
                $html .= "<tr><th>Espacio Disponible</th><td>" . htmlspecialchars($this->espacioDisponible) . " elementos</td></tr>";
                $html .= "<tr><th>Porcentaje Lleno</th><td>" . htmlspecialchars($this->porcentajeLleno) . "%</td></tr>";
            break;

            case 'Esfera':
                $radio = $this->tamanyo['radio'] / 100;
                $grosor = $this->tamanyo['grosor'] / 100;

                $html .= "<tr><th>Figura</th><td>" . htmlspecialchars(ucfirst($figura)) . "</td></tr>";
                $html .= "<tr><th>Tamaño</th><td>" . htmlspecialchars("Radio $radio m, Grosor: $grosor m") . "</td></tr>";
                $html .= "<tr><th>Tamaño elemento (lado)</th><td>" . htmlspecialchars($lado) . " m</td></tr>";
                $html .= "<tr><th>Número de Elementos</th><td>" . htmlspecialchars($this->numeroElementos) . " elementos</td></tr>";
                $html .= "<tr><th>Capacidad</th><td>" . htmlspecialchars($this->capacidad) . " elementos</td></tr>";
                $html .= "<tr><th>Espacio Disponible</th><td>" . htmlspecialchars($this->espacioDisponible) . " elementos</td></tr>";
                $html .= "<tr><th>Porcentaje Lleno</th><td>" . htmlspecialchars($this->porcentajeLleno) . "%</td></tr>";
            break;

            case 'Tetraedro':
                $grosor = $this->tamanyo['grosor'] / 100;
                $altura = $this->tamanyo['altura'] / 100;
                $base = $this->tamanyo['base'] / 100;
                $longitud = $this->tamanyo['longuitud'] / 100;

                $html .= "<tr><th>Figura</th><td>" . htmlspecialchars(ucfirst($figura)) . "</td></tr>";
                $html .= "<tr><th>Tamaño</th><td>" . htmlspecialchars("Base: $base, Largo: $longitud m, Altura: $altura m, Grosor: $grosor m") . "</td></tr>";
                $html .= "<tr><th>Tamaño elemento (lado)</th><td>" . htmlspecialchars($lado) . " m</td></tr>";
                $html .= "<tr><th>Número de Elementos</th><td>" . htmlspecialchars($this->numeroElementos) . " elementos</td></tr>";
                $html .= "<tr><th>Capacidad</th><td>" . htmlspecialchars($this->capacidad) . " elementos</td></tr>";
                $html .= "<tr><th>Espacio Disponible</th><td>" . htmlspecialchars($this->espacioDisponible) . " elementos</td></tr>";
                $html .= "<tr><th>Porcentaje Lleno</th><td>" . htmlspecialchars($this->porcentajeLleno) . "%</td></tr>";
            break;

            case 'Cilindro':
                $grosor = $this->tamanyo['grosor'] / 100;
                $altura = $this->tamanyo['altura'] / 100;
                $radio = $this->tamanyo['radio'] / 100;

                $html .= "<tr><th>Figura</th><td>" . htmlspecialchars(ucfirst($figura)) . "</td></tr>";
                $html .= "<tr><th>Tamaño</th><td>" . htmlspecialchars("Radio: $radio m, Altura: $altura m, Grosor: $grosor m") . "</td></tr>";
                $html .= "<tr><th>Tamaño elemento (lado)</th><td>" . htmlspecialchars($tamanyoElemento) . " m</td></tr>";
                $html .= "<tr><th>Número de Elementos</th><td>" . htmlspecialchars($this->numeroElementos) . " elementos</td></tr>";
                $html .= "<tr><th>Capacidad</th><td>" . htmlspecialchars($this->capacidad) . " elementos</td></tr>";
                $html .= "<tr><th>Espacio Disponible</th><td>" . htmlspecialchars($this->espacioDisponible) . " elementos</td></tr>";
                $html .= "<tr><th>Porcentaje Lleno</th><td>" . htmlspecialchars($this->porcentajeLleno) . "%</td></tr>";
            break;

            case 'Prisma Rectangular':
                $grosor = $this->tamanyo['grosor'] / 100;
                $altura = $this->tamanyo['altura'] / 100;
                $longitud = $this->tamanyo['longitud'] / 100;
                $ancho = $this->tamanyo['ancho'] / 100;
                $lado = $this->tamanyo['lado'] / 100;
                
                $html .= "<tr><th>Figura</th><td>" . htmlspecialchars(ucfirst($figura)) . "</td></tr>";
                $html .= "<tr><th>Tamaño</th><td>" . htmlspecialchars("Ancho: $ancho m, Altura: $altura m, Largo: $longitud m, Grosor: $grosor m") . "</td></tr>";
                $html .= "<tr><th>Tamaño elemento (lado)</th><td>" . htmlspecialchars($lado) . " m</td></tr>";
                $html .= "<tr><th>Número de Elementos</th><td>" . htmlspecialchars($this->numeroElementos) . " elementos</td></tr>";
                $html .= "<tr><th>Capacidad</th><td>" . htmlspecialchars($this->capacidad) . " elementos</td></tr>";
                $html .= "<tr><th>Espacio Disponible</th><td>" . htmlspecialchars($this->espacioDisponible) . " elementos</td></tr>";
                $html .= "<tr><th>Porcentaje Lleno</th><td>" . htmlspecialchars($this->porcentajeLleno) . "%</td></tr>";
            break;

            default: 
               echo "La figura $figura no es valida en toString";
            break;
        }
        return $html;
    }

}
?>