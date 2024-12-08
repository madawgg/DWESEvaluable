<?php
include_once 'Invento.php';
include_once 'Material.php';
include_once 'PiedraAfilada.php';
include_once 'src/traits/CalculosGeometricos.php';
include_once 'src/interfaces/Medible.php';


class Hacha extends invento implements Medible{
    use CalculosGeometricos;
    protected const FIGURA = 'Cilindro';
    protected const NIVEL = 1;
    protected float $longitud = 0;
    protected float $eficienciaMango;
    protected static array $inventosPrevios = [
        'PiedraAfilada' => 1
    ];
    public static array $campos = [
        ['nombre' => 'material', 'tipo' => 'select', 'variable' => 'materiales'],
        ['nombre' => 'piedraAfilada', 'tipo' => 'select', 'variable' => 'PiedraAfilada'],
        ['nombre' => 'nombre', 'tipo' => 'text'],
        ['nombre' => 'radio', 'tipo' => 'number'],
        ['nombre' => 'altura', 'tipo' => 'number'],
        ['nombre' => 'zona', 'tipo' => 'select', 'variable' => 'zonas'],
        ['nombre' => 'tecnica', 'tipo' => 'select', 'variable' => 'tecnicas']
    ];
    protected int $tiempoCreacion;
    
    public function __construct(
        protected Material $material,
        protected PiedraAfilada $piedraAfilada, 
        string $nombre, 
        protected array $tamanyo,
        protected string $zonaCreacion = 'pradera',
        protected string $metodoCreacion ='tradicional'
    ){
        parent::__construct($nombre);
        $this->eficiencia = $this->calcularEficiencia();
        $this->tiempoCreacion = $this->calcularTiempoCreacion();
        $this->tiempoFinal = $this->calcularTiempoTotal($this->tiempoCreacion);
        $this->puntuacion = $this->calcularPuntuacion();
    }
    
    public function getFigura (): string{
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
           
            $eficienciaMango = $this->material->calcularEficiencia([
                'beneficiosos' => ['dureza', 'tenacidad', 'resistenciaTraccion'],
                'perjudiciales' => ['densidad', 'fragilidad']
            ]);
            $this->eficienciaMango = $eficienciaMango;
            
            $eficienciaPiedraAfilada = $this->piedraAfilada->eficiencia;

            $eficiencia = round(($eficienciaPiedraAfilada + $eficienciaMango)/2, 2);
           
            return $eficiencia;
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


    public function calcularPuntuacion(): int{
        if($this->eficiencia <= 0){
            return 0;
        }
        $puntuacion = $this->eficiencia + 10;
        return round($puntuacion);
    }
    //medible.php
    public function calcularPeso(): float{
        $densidadMango = $this->material->getDensidadReal();
        $volumenMango = $this->calcularVolumen();
        $pesoMango = $densidadMango * $volumenMango;
        
        $pesoPiedra= $this->piedraAfilada->calcularPeso();

        $peso = $pesoMango + $pesoPiedra;
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

    public function __toString(): string{
        
        $figura = $this->getFigura();
        $radio = $this->tamanyo['radio'] / 100;
        $altura = $this->tamanyo['altura'] / 100;
        
        $html = parent::__toString();
        $html .= '<tr><th>Figura</th><td>' . htmlspecialchars($figura) . '</td></tr>';
        $html .= '<tr><th>Tamaño</th><td>' . htmlspecialchars("Radio: $radio m, Altura: $altura m") . '</td></tr>';
        $html .= $this->calculosGeometricos();
        $html .= '<tr><th>Material</th><td>';
        $html .=  $this->material;
        $html .= '<tr><th>Dureza</th><td>' . htmlspecialchars($this->material->dureza) . ' sobre 100</td></tr>';
        $html .= '<tr><th>Tenacidad</th><td>' . htmlspecialchars($this->material->tenacidad) . ' sobre 100</td></tr>';
        $html .= '<tr><th>Resistencia a la tracción</th><td>' . htmlspecialchars($this->material->resistenciaTraccion) . ' sobre 100</td></tr>';
        $html .= '<tr><th>Densidad</th><td>' . htmlspecialchars($this->material->densidad) . ' sobre 100</td></tr>';
        $html .= '<tr><th>Fragilidad</th><td>' . htmlspecialchars($this->material->fragilidad) . ' sobre 100</td></tr>';
        $html .= '<tr><th>Eficacia del mango</th><td>' . htmlspecialchars($this->eficienciaMango) . ' %</td></tr>';
        $html .= ' </table>';
        $html .= '<tr><th>Piedra Afilada</th><td>';
        $html .= $this->piedraAfilada;
        $html.= '</td></table>';

        return $html;
    }

    public static function probarInvento(array $argumentos= []): void{
        $hacha = new Hacha(...$argumentos);
        echo $hacha->__toString();
    }   
}
?>