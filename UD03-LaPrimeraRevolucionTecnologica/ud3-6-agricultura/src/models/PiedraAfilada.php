<?php
include_once 'Invento.php';
include_once 'Material.php';
include_once 'src/traits/CalculosGeometricos.php';
include_once 'src/interfaces/Medible.php';

class PiedraAfilada extends Invento implements Medible{
    use CalculosGeometricos;
    protected const  FIGURA = 'Tetraedro';
    protected const NIVEL = 1;
    protected static array $inventosPrevios=[];
    protected int $tiempoCreacion;

    public function __construct(
        protected Material $material,
        string $nombre,  
        protected array $tamanyo,
        protected string $zonaCreacion = 'pradera',
        protected string $metodoCreacion = 'tradicional'
        ){
        parent::__construct($nombre);
        $this->eficiencia  = $this->calcularEficiencia();
        $this->tiempoCreacion = $this->calcularTiempoCreacion();
        $this->tiempoFinal = $this->calcularTiempoTotal($this->tiempoCreacion);
        $this->puntuacion = $this->calcularPuntuacion();
    }

    public static function getInventosPrevios(): array {
        return self::$inventosPrevios;
    }

    public function getFigura(): string{
        return self::FIGURA;
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
                'beneficiosos' => ['dureza', 'tenacidad'],
                'perjudiciales' => ['densidad', 'fragilidad']
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
    
    public function calcularPuntuacion():int{
     
        if ($this->eficiencia <= 0) {
            return 0;  
        } 
        $base = $this->tamanyo['base'];
        $altura = $this->tamanyo['altura']; 
        if ($altura / 2 > $base) {
            return $this->eficiencia + 10;  
        }
    
        return $this->eficiencia;  
        
    }

    public function calcularPeso(): float{
        $densidad = $this->material->getDensidadReal();
        $volumen = $this->calcularVolumen();
        $peso = $densidad * $volumen;
        return round($peso,2);
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
  

    public function __toString(): string {
        $figura = $this->getFigura();
        $base = $this->tamanyo['base'] / 100;
        $altura = $this->tamanyo['altura'] / 100;
        $longitud = $this->tamanyo['longitud'] / 100;
        
        $html = parent::__toString();
        $html .= '<tr><th>Figura</th><td>' . htmlspecialchars($figura) . '</td></tr>';
        $html .= '<tr><th>Tamaño</th><td>' . htmlspecialchars('Base: '. $base .' m, Altura: '. $altura . ' m, Longitud: ' . $longitud ) . ' m</td></tr>';
        $html .= $this->calculosGeometricos();
        $html .= '<tr><th>Material</th><td>';
        $html .=  $this->material;
        $html .= '<tr><th>Dureza</th><td>' . htmlspecialchars($this->material->dureza) . ' sobre 100</td></tr>';
        $html .= '<tr><th>Tenacidad</th><td>' . htmlspecialchars($this->material->tenacidad) . ' sobre 100</td></tr>';
        $html .= '<tr><th>Fragilidad</th><td>' . htmlspecialchars($this->material->fragilidad) . ' sobre 100</td></tr>';
        $html .= '<tr><th>Densidad</th><td>' . htmlspecialchars($this->material->densidad) . ' sobre 100</td></tr>';
        $html .= '</td></tr>';
        $html .= '</table> </table>';
       
        return $html;
    }

    public static function probarInvento(array $argumentos=[]): void{
        $piedraAfilada = new PiedraAfilada(...$argumentos);
        echo $piedraAfilada;
    }
}
?>