<?php

include_once 'Invento.php';
include_once 'Material.php';
include_once 'src/traits/CalculosGeometricos.php';
include_once 'src/interfaces/Medible.php';

class Rueda extends Invento implements Medible{
    use CalculosGeometricos;
    protected const FIGURA = 'Cilindro';
    protected const NIVEL = 1;    
    protected static array $inventosPrevios = [];
    protected int $tiempoCreacion;

    public function __construct(
        protected Material $material,
        string $nombre, 
        protected array $tamanyo,
        protected string $zonaCreacion = 'pradera',
        protected string $metodoCreacion = 'tradicional'
    ){
        parent::__construct($nombre);
        $this->eficiencia = $this->calcularEficiencia();
        $this->tiempoCreacion = $this->calcularTiempoCreacion();
        $this->tiempoFinal = $this->calcularTiempoTotal($this->tiempoCreacion);
        $this->puntuacion = $this->calcularPuntuacion();
    }
    
    public function getFigura(){
        return self::FIGURA;
    }

    public static function getInventosPrevios(): array {
        return self::$inventosPrevios;
    }

    public function __get( $atributo){
        if(property_exists($this, $atributo)){
            return $this->$atributo;
        }else{
            echo 'La propiedad '. $atributo .' no existe';
        }
    }
        
    public function __set($atributo, $valor){
        if(property_exists($this, $atributo)){
            $this->$atributo = $valor;
        }else{
            echo 'No se ha podido introducir el valor de la propiedad '. $atributo;
        }
    }

    public function calcularEficiencia(): float{
        try {
            return $this->material->calcularEficiencia([
                'beneficiosos' => ['resistenciaCompresion', 'coeficienteDesgaste'],
                'perjudiciales' => ['densidad', 'coeficienteFriccion']
            ]);
        } catch (Exception $e) {
            return 0.00;
        }
    }

    public function calcularTiempoCreacion(): int {
        $tiemposMateriales = [];

        $tiempoBase = 60 * $this->nivel; 
       
    
        switch ($this->metodoCreacion) {
            case 'tradicional':
                break; 
            case 'rapido':
                $tiempoBase *= 0.75;
                $this->eficiencia -= 10;
                break;
            case 'detallado':
                $tiempoBase *= 1.5;
                $this->eficiencia += 10;
                break;
            default:
                echo 'El método de creación no existe';
                return 0;
        }
        $tiemposMateriales[] = $this->material->calcularTiempoCreacion($this->zonaCreacion, $tiempoBase);
        $tiempoFinal = max($tiemposMateriales);

        return round($tiempoFinal);
    }

    public function calcularPuntuacion(): int {
        if($this->eficiencia <= 0){
            return 0;
        }
        $puntuacion = round($this->eficiencia);
        return $puntuacion;
    }
    //medible.php
    public function calcularPeso(): float{
        $densidad = $this->material->densidad;
        $volumen = $this->calcularVolumen();
        $peso = $densidad * $volumen;
        return $peso;
    }
    public function calcularVolumen(): float{
       $volumen =  $this->volumen($this->getFigura(), $this->tamanyo);
       return $volumen;
    }
    public function calcularArea(): float{
        $area = $this->area($this->getFigura(), $this->tamanyo);
        return $area;
    }
    public function calcularSuperficie(): float{
        $superficie = $this->superficie($this->getFigura(), $this->tamanyo);
        return $superficie;
    }
    public function obtenerTamanyo(): array{
        $tamanyo = $this->tamanyo;
        return $tamanyo;
    }
    public function __toString(): string{
        $figura = $this->getFigura();
        $radio = $this->tamanyo['radio'];
        $altura = $this->tamanyo['altura'];
        $peso = $this->calcularPeso();
        $volumen = $this->calcularVolumen();
        $area = $this->calcularArea();
        $superficie = $this->calcularSuperficie();

        $html = parent::__toString(); 
        $html .= '<tr><th>Figura</th><td>' . htmlspecialchars($figura) . '</td></tr>';
        $html .= '<tr><th>Tamaño (radio x altura)</th><td>' . htmlspecialchars($radio . ' x ' . $altura) . '</td></tr>';
        $html .= '<tr><th>Peso</th><td>' . htmlspecialchars($peso) . '</td></tr>';
        $html .= '<tr><th>Volumen</th><td>' . htmlspecialchars($volumen) . '</td></tr>';
        $html .= '<tr><th>Área</th><td>' . htmlspecialchars($area) . '</td></tr>';
        $html .= '<tr><th>Superficie</th><td>' . htmlspecialchars($superficie) . '</td></tr>';
        $html .= '<tr><th>Material</th><td>';
        $html .=  $this->material->__toString();
        $html .= '<tr><th>Resistencia a la compresión</th><td>' . htmlspecialchars($this->material->resistenciaCompresion) . '</td></tr>';
        $html .= '<tr><th>Resistencia al desgaste</th><td>' . htmlspecialchars($this->material->coeficienteDesgaste) . '</td></tr>';
        $html .= '<tr><th>Densidad</th><td>' . htmlspecialchars($this->material->densidad) . '</td></tr>';
        $html .= '<tr><th>Coeficiente de fricción</th><td>' . htmlspecialchars($this->material->coeficienteFriccion) . '</td></tr>';
        $html .= '</td></tr>';
        $html .= '</table> </table>';
        
        return $html;
    }

    public static function probarInvento(array $argumentos = []): void{
        $rueda = new Rueda(...$argumentos);
        echo $rueda->__toString();
    }

}

?>