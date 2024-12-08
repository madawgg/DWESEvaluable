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
    public static array $campos = [
        ['nombre' => 'material', 'tipo' => 'select', 'variable' => 'materiales'],
        ['nombre' => 'nombre', 'tipo' => 'text'],
        ['nombre' => 'radio', 'tipo' => 'number'],
        ['nombre' => 'altura', 'tipo' => 'number'],
        ['nombre' => 'zona', 'tipo' => 'select', 'variable' => 'zonas'],
        ['nombre' => 'tecnica', 'tipo' => 'select', 'variable' => 'tecnicas']
    ];
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
    public static function getCampos(): array {
        return self::$campos;
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
        $densidad = $this->material->getDensidadReal();
        $volumen = $this->volumen($this->getFigura(), $this->tamanyo);
        $peso = $densidad * $volumen;
        return round($peso, 2);
    }
    public function calcularVolumen(): float{
       $volumen =  $this->volumen($this->getFigura(), $this->tamanyo);
       return round($volumen,2);
    }
    public function calcularArea(): float{
        $area = $this->area($this->getFigura(), $this->tamanyo);
        return round($area,2);
    }
    public function calcularSuperficie(): float{
        $superficie = $this->superficie($this->getFigura(), $this->tamanyo);
        return round($superficie,2);
    }
    public function obtenerTamanyo(): array{
        $tamanyo = $this->tamanyo;
        return $tamanyo;
    }
    public function __toString(): string{
        $figura = $this->getFigura();
        $radio = $this->tamanyo['radio'];
        $altura = $this->tamanyo['altura'];
        $html = parent::__toString();
        $html .= '<tr><th>Figura</th><td>' . htmlspecialchars($figura) . '</td></tr>'; 
        $html .= '<tr><th>Tamaño</th><td>' . htmlspecialchars("Radio: $radio m, Altura: $altura m") . '</td></tr>';
        $html .= $this->calculosGeometricos();
        $html .= '<tr><th>Material</th><td>';
        $html .=  $this->material;
        $html .= '<tr><th>Resistencia a la compresión</th><td>' . htmlspecialchars($this->material->resistenciaCompresion) . ' sobre 100</td></tr>';
        $html .= '<tr><th>Resistencia al desgaste</th><td>' . htmlspecialchars($this->material->coeficienteDesgaste) . ' sobre 100</td></tr>';
        $html .= '<tr><th>Densidad</th><td>' . htmlspecialchars($this->material->densidad) . ' sobre 100</td></tr>';
        $html .= '<tr><th>Coeficiente de fricción</th><td>' . htmlspecialchars($this->material->coeficienteFriccion) . ' sobre 100</td></tr>';
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