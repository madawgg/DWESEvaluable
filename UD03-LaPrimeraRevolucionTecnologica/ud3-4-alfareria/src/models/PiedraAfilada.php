<?php
include_once 'Invento.php';
include_once 'Material.php';
include_once 'src/traits/CalculosGeometricos.php';
include_once 'src/interfaces/Medible.php';

class PiedraAfilada extends Invento implements Medible{
    use CalculosGeometricos;
    private const  FIGURA = 'Tetraedro';
    private Material $material;
    private array $tamanyo = [];
    private float $eficiencia;
    private int $puntuacion;
    protected static array $inventosPrevios=[];
    
    protected string $zonaCreacion;
    protected string $metodoCreacion;
    protected int $tiempoCreacion;
    

    public function __construct(string $nombre, Material $material, array $tamanyo, string $zonaCreacion = null, string $metodoCreacion = null){
        parent::__construct($nombre, 1 );
        
        $this->material = $material;
        $this->tamanyo = $tamanyo;
        $this->zonaCreacion = $zonaCreacion ?? 'pradera';
        $this->metodoCreacion = $metodoCreacion ?? 'tradicional';
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

    //getters
    public function getMaterial(): Material {
        return $this->material;
    }
    
    public function getTamanyo(): array{
        return $this->tamanyo;
    }
    
    
    public function getEficiencia(): float{
        return $this->eficiencia;
    }
    public function getPuntuacion(): int{
        return $this->puntuacion;
    }

    //setters
    public function setMaterial(Material $material): void{
        $this->material = $material;
    } 
    public function setTamanyo(array $tamanyo): void {
        $this->tamanyo = $tamanyo;
    }
   
    public function setEficiencia(float $eficiencia): void{
        $this->eficiencia = $eficiencia;
    }
    public function setPuntuacion(int $puntuacion): void{
        $this->puntuacion = $puntuacion;
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
        $tiempoFinal = 0;
        switch ($this->metodoCreacion) {
            case 'tradicional':
                $tiempoFinal = 60 * $this->nivel;
                break;
            case 'rapido':
                $tiempoFinal = 60 * $this->nivel * 0.75;
                $this->eficiencia -= 10;
                break;

            case 'detallado':
                $tiempoFinal = 60 * $this->nivel * 1.5;
                $this->eficiencia += 10;
                break;
            default:
                echo 'El metodo de creacion no existe';
                return 0;
                break;
        }
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
  

    public function __toString(): string {
        $figura = $this->getFigura();
        $base = $this->tamanyo['base'];
        $altura = $this->tamanyo['altura'];
        $longitud = $this->tamanyo['longitud'];
        $peso = $this->calcularPeso();
        $volumen = $this->calcularVolumen();
        $area = $this->calcularArea();
        $superficie = $this->calcularSuperficie();
        
        $html = parent::__toString();
        $html .= '<tr><th>Eficiencia</th><td>' . htmlspecialchars($this->eficiencia) . '</td></tr>';
        $html .= '<tr><th>Puntos</th><td>' . htmlspecialchars($this->puntuacion). '</td></tr>';
        $html .= '<tr><th>Zona de creación</th><td>' . htmlspecialchars($this->zonaCreacion). '</td></tr>';
        $html .= '<tr><th>Metodo de creación</th><td>' . htmlspecialchars($this->metodoCreacion). '</td></tr>';
        $html .= '<tr><th>Tiempo de inicio</th><td>' . htmlspecialchars($this->tiempoInicial->format('Y-m-d H:i:s')). '</td></tr>';
        $html .= '<tr><th>Tiempo de creación</th><td>' . htmlspecialchars($this->tiempoCreacion). '</td></tr>';
        $html .= '<tr><th>Tiempo de fin</th><td>' . htmlspecialchars($this->tiempoFinal->format('Y-m-d H:i:s')). '</td></tr>';
        $html .= '<tr><th>Figura</th><td>' . htmlspecialchars($figura) . '</td></tr>';
        $html .= '<tr><th>Tamaño (base x altura x longitud)</th><td>' . htmlspecialchars($base .' x '. $altura . ' x ' . $longitud ) . '</td></tr>';
        $html .= '<tr><th>Peso</th><td>' . htmlspecialchars($peso) . '</td></tr>';
        $html .= '<tr><th>Volumen</th><td>' . htmlspecialchars($volumen) . '</td></tr>';
        $html .= '<tr><th>Área</th><td>' . htmlspecialchars($area) . '</td></tr>';
        $html .= '<tr><th>Superficie</th><td>' . htmlspecialchars($superficie) . '</td></tr>';
        $html .= '<tr><th>Material</th><td>';
        $html .=  $this->material->__toString();
        $html .= '<tr><th>Dureza</th><td>' . htmlspecialchars($this->material->dureza) . '</td></tr>';
        $html .= '<tr><th>Tenacidad</th><td>' . htmlspecialchars($this->material->tenacidad) . '</td></tr>';
        $html .= '<tr><th>Fragilidad</th><td>' . htmlspecialchars($this->material->fragilidad) . '</td></tr>';
        $html .= '<tr><th>Densidad</th><td>' . htmlspecialchars($this->material->densidad) . '</td></tr>';
        $html .= '</td></tr>';
        $html .= '</table> </table>';
       
        return $html;
    }

    public static function probarInvento(array $argumentos=[]): void{
        $piedraAfilada = new PiedraAfilada(...$argumentos);
        echo $piedraAfilada->__toString();
    }
}
?>