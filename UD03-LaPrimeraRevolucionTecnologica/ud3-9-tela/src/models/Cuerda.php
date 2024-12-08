<?php
include_once 'Invento.php';
include_once 'Material.php';
include_once 'src/traits/CalculosGeometricos.php';
include_once 'src/interfaces/Medible.php';

class Cuerda extends Invento implements Medible{
    use CalculosGeometricos;
    private const FIGURA = 'Cilindro';
    protected const NIVEL = 1;
    protected float $longitud = 0;
    protected int $altura;
    protected static array $inventosPrevios=[];
    public static array $campos=[
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
        $this->altura = $tamanyo['altura'];
        $this->eficiencia = $this->calcularEficiencia();
        $this->tiempoCreacion = $this->calcularTiempoCreacion();
        $this->tiempoFinal = $this->calcularTiempoTotal($this->tiempoCreacion);
        $this->puntuacion = $this->calcularPuntuacion();

    }
    public  static function getCampos(): array{
        return self::$campos;
    }
    public static function getInventosPrevios(): array {
        return self::$inventosPrevios;
    }
    public function getFigura():string{
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
    
    public function encriptar($text): string{
        $salt = '!&';
        $textoEncriptado = crypt($text, $salt);
        
        if ($textoEncriptado === "*0") {
            return $textoEncriptado;
        }
        
        $longitudCuerda = $this->longitud;
        
        if (strlen($textoEncriptado) < $longitudCuerda) {
            $textoEncriptado = str_pad($textoEncriptado, $longitudCuerda, $salt);
        } else {
            $textoEncriptado = substr($textoEncriptado, 0, $longitudCuerda);
        }     
        return $textoEncriptado;
    }
    public function calcularEficiencia(): float{
        try {

            return $this->material->calcularEficiencia([
                'beneficiosos' => ['resistenciaTraccion', 'flexibilidad'],
                'perjudiciales' => ['densidad', 'coeficienteDesgaste']
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

    public function calcularPuntuacion(): int{
        if($this->eficiencia <= 0){
            return 0;
        }
        $nombreEncriptado = $this->encriptar($this->material->nombre);
        $factorEncriptacion = $this->longitud % 10;
        $ajustarEficiencia = $this->eficiencia + strlen($nombreEncriptado) + $factorEncriptacion;
        $this->puntuacion = round($ajustarEficiencia);

    return $this->puntuacion;
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
        $html .= '<tr><th>Resistencia a la tracción</th><td>' . htmlspecialchars($this->material->resistenciaTraccion) . ' sobre 100</td></tr>';
        $html .= '<tr><th>Flexibilidad</th><td>' . htmlspecialchars($this->material->flexibilidad) . ' sobre 100</td></tr>';
        $html .= '<tr><th>Densidad</th><td>' . htmlspecialchars($this->material->densidad) . ' sobre 100</td></tr>';
        $html .= '<tr><th>Coeficiente de desgaste</th><td>' . htmlspecialchars($this->material->coeficienteDesgaste) . ' sobre 100</td></tr>';
        $html .= '</table></table>';
        
        return $html;
    }

    public static function probarInvento(array $argumentos = []): void{
        $cuerda = new Cuerda(...$argumentos);

        echo $cuerda->__toString();
    }

}

?>