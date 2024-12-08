<?php

include_once 'Invento.php';
include_once 'Material.php';
include_once 'Cuerda.php';
include_once 'ArcoFlecha.php';
include_once 'Cesta.php';
include_once 'src/traits/CalculosGeometricos.php';
include_once 'src/interfaces/Medible.php';
include_once 'Almacenamiento.php';

class Trampa extends Almacenamiento implements Medible{
    use CalculosGeometricos;
    protected const FIGURA = 'Cilindro';
    protected const NIVEL = 1;
    protected const TAMANYO_ELEMENTO = 30;
    protected array $tamanyo;
    
   
    protected static array $inventosPrevios = [  
        'Cuerda' => 1,
        'Cesta' => 1,
        'ArcoFlecha' =>1
    ];
    protected static array $campos = [
        ['nombre' => 'cuerda', 'tipo' => 'select', 'variable' => 'Cuerda'],
        ['nombre' => 'cesta', 'tipo' => 'select', 'variable' => 'Cesta'],
        ['nombre' => 'arcoFlecha', 'tipo' => 'select', 'variable' => 'ArcoFlecha'],
        ['nombre' => 'nombre', 'tipo' => 'text'],
        ['nombre' => 'visibilidad', 'tipo' => 'number'],
        ['nombre' => 'zona', 'tipo' => 'select', 'variable' => 'zonas'],
        ['nombre' => 'tecnica', 'tipo' => 'select', 'variable' => 'tecnicas'],
        ['nombre' => 'numeroElementos', 'tipo' => 'number']
    ];

    protected int $tiempoCreacion;

    public function __construct(
        protected Cuerda $cuerda, 
        protected Cesta $cesta, 
        protected ArcoFlecha $arcoFlecha, 
        protected string $nombre,
        protected float $visibilidad, 
        protected string $zonaCreacion = 'pradera',
        protected string $metodoCreacion = 'tradicional',
        protected int $numeroElementos = 0
        ){
            
        $this->tamanyo = $this->obtenerTamanyo();
        parent::__construct( $nombre, $this->tamanyo, $numeroElementos );
        $this->eficiencia = $this->calcularEficiencia();
        $this->tiempoCreacion = $this->calcularTiempoCreacion();
        $this->tiempoFinal = $this->calcularTiempoTotal($this->tiempoCreacion);
        $this->puntuacion = $this->calcularPuntuacion();
        $this->capacidad = Almacenamiento::calcularCapacidad();

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
            echo 'No se ha podido introducir el valor de la propiedad '. $atributo . 'trampa';
        }
    }
    public function calcularEficiencia(): float{
        try{
            $eficienciaCuerda = $this->cuerda->eficiencia;
            $eficienciaCesta = $this->cesta->eficiencia;
            $eficienciaArcoFlecha = $this->arcoFlecha->eficiencia;

            $eficienciaTrampa = round(($eficienciaCuerda+$eficienciaCesta+$eficienciaArcoFlecha)/3, 2);
            return $eficienciaTrampa;

        } catch (Exception $e) {
            return 0.00;
        }
    }

    public function calcularTiempoCreacion(): int {
        $tiempoBase = 60 * $this->nivel; 
        $tiempoFinal = $tiempoBase;

        switch ($this->metodoCreacion) {
            case 'tradicional':
                $tiempoFinal;
                break; 
            case 'rapido':
                $tiempoFinal *= 0.75;
                $this->eficiencia -= 10;
                break;
            case 'detallado':
                $tiempoFinal *= 1.5;
                $this->eficiencia += 10;
                break;
            default:
                echo 'El método de creación no existe';
                return 0;
        }
    
        return round($tiempoFinal);
    }

    public function calcularPuntuacion(): int{
        if($this->eficiencia <= 0){
            return 0;
        }
        $eficienciaRedondeada = round($this->eficiencia);
        $restaVisibilidad = 1 - $this->visibilidad;
        
        $puntuacion = $eficienciaRedondeada * $restaVisibilidad;
        
        return floor($puntuacion);
    }

    public function calcularPeso(): float{
        $pesoArco = $this->arcoFlecha->calcularPeso(); 
        $pesoCuerda = $this->cuerda->calcularPeso();
        $pesoCesta = $this->cesta->calcularPeso();
        
        $peso = $pesoArco + $pesoCuerda + $pesoCesta;
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
        $tamanyo = $this->cesta->tamanyo;
        return $tamanyo;
    }

    public function __toString(): string{
        $html = parent::__toString();   
        $html .= $this->calculosGeometricos();
        $html .= '</td><tr><th>Cuerda</th><td>';
        $html .= $this->cuerda; 
        $html .= '</td><tr><th>Cesta</th><td>';
        $html .= $this->cesta; 
        $html .= '</td><tr><th>Arco y flecha</th><td>';
        $html .= $this->arcoFlecha;
        $html.= '</td></table>';

        return $html;
    }

    public static function probarInvento(array $argumentos= []): void{
        $trampa = new Trampa(...$argumentos);
        echo $trampa->__toString();
    }

}
?>