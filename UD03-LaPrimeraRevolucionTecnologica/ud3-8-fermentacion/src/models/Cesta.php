<?php

include_once 'Almacenamiento.php';
include_once 'Material.php';
include_once 'src/traits/CalculosGeometricos.php';
include_once 'src/interfaces/Medible.php';

class Cesta extends Almacenamiento implements Medible{
    use CalculosGeometricos;
    protected const NIVEL = 1;
    protected const FIGURA = 'Cilindro';
    protected const TAMANYO_ELEMENTO = 20; 
    protected static array $inventosPrevios=[];
    protected static array $campos = [
        ['nombre' => 'material', 'tipo' => 'select', 'variable' => 'materiales'],
        ['nombre' => 'nombre', 'tipo' => 'text'],
        ['nombre' => 'radio', 'tipo' => 'number'],
        ['nombre' => 'altura', 'tipo' => 'number'],
        ['nombre' => 'grosor', 'tipo' => 'number'],
        ['nombre' => 'zona', 'tipo' => 'select', 'variable' => 'zonas'],
        ['nombre' => 'tecnica', 'tipo' => 'select', 'variable' => 'tecnicas'],
        ['nombre' => 'numeroElementos', 'tipo' => 'number']
    ];
    protected int $tiempoCreacion;

    public function __construct(
        protected Material $material, 
        string $nombre,  
        protected array $tamanyo, 
        protected string $zonaCreacion = 'pradera', 
        protected string $metodoCreacion = 'tradicional',
        int $numeroElementos = 0
    ){
        $this->tamanyo = $this->obtenerTamanyo();
        parent::__construct($nombre, $tamanyo, $numeroElementos);
        $this->eficiencia = $this->calcularEficiencia();
        $this->tiempoCreacion = $this->calcularTiempoCreacion();
        $this->tiempoFinal = $this->calcularTiempoTotal($this->tiempoCreacion);
        $this->puntuacion = $this->calcularPuntuacion();
        $this->capacidad = Almacenamiento::calcularCapacidad();
    }

    public static function getInventosPrevios(): array {
        return self::$inventosPrevios;
    }

    public static function getCampos(): array {
        return self::$campos;
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

    public function calcularEficiencia(): float {
        try {
            return $this->material->calcularEficiencia([
                'beneficiosos' => ['flexibilidad'],
                'perjudiciales' => ['densidad', 'fragilidad']
            ]);
        } catch (Exception $e) {
            return 0.00;
        }
    }

    public function calcularPuntuacion(): int{
        if($this->eficiencia <=0){
            return 0;
        }
        $puntuacion = round($this->eficiencia);

        return $puntuacion;
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

    public function calcularPeso(): float{
        $densidad = $this->material->getDensidadReal();
        $volumen = $this->calcularVolumen();
        $peso = $densidad * $volumen;
        return round($peso, 2);
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
        $tamanyo['lado'] = self::TAMANYO_ELEMENTO;
        return $tamanyo;
    }

    public function __toString():string{
      
        $peso = $this->calcularPeso() / 1000;
        $volumen = $this->calcularVolumen() / 1000000;
        $area = $this->calcularArea() / 10000;
      

        $html = parent::__toString();
        $html.= $this->calculosGeometricos();
        $html .= '<tr><th>Material</th><td>';
        $html .=  $this->material->__toString();
        $html .= '<tr><th>Flexibilidad</th><td>' . htmlspecialchars($this->material->flexibilidad) . ' sobre 100</td></tr>';
        $html .= '<tr><th>Densidad</th><td>' . htmlspecialchars($this->material->densidad) . ' sobre 100</td></tr>';
        $html .= '<tr><th>Fragilidad</th><td>' . htmlspecialchars($this->material->fragilidad) . ' sobre 100</td></tr>';
        $html .= '</td></tr>';
        $html .= '</table> </table>';
       
        return $html;
    }

    public static function probarInvento(array $argumentos = []): void{
        $cesta = new Cesta(...$argumentos);
        echo $cesta->__toString();
    }

}

?>