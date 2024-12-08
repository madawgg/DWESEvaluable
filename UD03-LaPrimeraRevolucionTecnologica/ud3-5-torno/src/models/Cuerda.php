<?php
include_once 'Invento.php';
include_once 'Material.php';
include_once 'src/traits/CalculosGeometricos.php';
include_once 'src/interfaces/Medible.php';

class Cuerda extends Invento implements Medible{
    use CalculosGeometricos;
    private const FIGURA = 'Cilindro';
    private float $longitud = 0;
    private Material $material;   
    private int $puntuacion;
    private float $eficiencia;
    private int $altura;
    private array $tamanyo = [];
    protected static array $inventosPrevios=[];

    protected string $zonaCreacion;
    protected string $metodoCreacion;
    protected int $tiempoCreacion;

    public function __construct(string $nombre,  Material $material, array $tamanyo, string $zonaCreacion = null, string $metodoCreacion = null){
        parent::__construct($nombre, 1);
        $this->material = $material;
        $this->tamanyo = $tamanyo;
        $this->altura = $tamanyo['altura'];
        
        $this->zonaCreacion = $zonaCreacion ?? 'pradera';
        $this->metodoCreacion = $metodoCreacion ?? 'tradicional';
        $this->eficiencia = $this->calcularEficiencia();
        $this->tiempoCreacion = $this->calcularTiempoCreacion();
        $this->tiempoFinal = $this->calcularTiempoTotal($this->tiempoCreacion);
        $this->puntuacion = $this->calcularPuntuacion();

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
    public static function getInventosPrevios(): array {
        return self::$inventosPrevios;
    }
    public function getFigura():string{
        return self::FIGURA;
    }
    public function getLongitud(): float {
        return $this->nombre;
    }
    public function getMaterial(): Material {
        return $this->material;
    }
    public function getAltura():int{
        return $this->altura;
    }
    public function getPuntuacion(): int {
        return $this->puntuacion;
    }
    public function getEficiencia(): float {
        return $this->eficiencia;
    }

    //setters
    public function setLongitud(float $longitud): void{
        $this->longitud = $longitud;
    }
    public function setMaterial(Material $material): void{
        $this->material = $material;
    }
    public function setPuntuacion(int $puntuacion): void{
        $this->puntuacion = $puntuacion;
    }
    public function setEficiencia(float $eficiencia): void{
        $this->eficiencia = $eficiencia;
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
        $tiemposMateriales[] = $this->material->calcularTiempoCreacion($this->zonaCreacion, $tiempoBase);
    
        $tiempoFinal = max($tiemposMateriales);
    
        switch ($this->metodoCreacion) {
            case 'tradicional':
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
        $nombreEncriptado = $this->encriptar($this->material->nombre);
        $factorEncriptacion = $this->longitud % 10;
        $ajustarEficiencia = $this->eficiencia + strlen($nombreEncriptado) + $factorEncriptacion;
        $this->puntuacion = round($ajustarEficiencia);

    return $this->puntuacion;
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

    public function __toString(): string{
        $figura = $this->getFigura();
        $radio = $this->tamanyo['radio'];
        $altura = $this->tamanyo['altura'];
        $peso = $this->calcularPeso();
        $volumen = $this->calcularVolumen();
        $area = $this->calcularArea();
        $superficie = $this->calcularSuperficie();

        $html = parent::__toString();
        $html .= '<tr><th>Eficiencia</th><td>' . htmlspecialchars($this->calcularEficiencia()) . '</td></tr>';
        $html .= '<tr><th>Puntos</th><td>' . htmlspecialchars($this->calcularPuntuacion()) . '</td></tr>';
        
        $html .= '<tr><th>Zona de creación</th><td>' . htmlspecialchars($this->zonaCreacion) . '</td></tr>';
        $html .= '<tr><th>Metodo de creación</th><td>' . htmlspecialchars($this->metodoCreacion) . '</td></tr>';
        $html .= '<tr><th>Tiempo de inicio</th><td>' . htmlspecialchars($this->tiempoInicial->format('Y-m-d H:i:s')) . '</td></tr>';
        $html .= '<tr><th>Tiempo de creación</th><td>' . htmlspecialchars($this->tiempoCreacion) . '</td></tr>';
        $html .= '<tr><th>Tiempo de fin</th><td>' . htmlspecialchars($this->tiempoFinal->format('Y-m-d H:i:s')) . '</td></tr>';
        
        $html .= '<tr><th>Figura</th><td>' . htmlspecialchars($figura) . '</td></tr>';
        $html .= '<tr><th>Tamaño (radio x altura)</th><td>' . htmlspecialchars($radio .' x '.$altura) . '</td></tr>';
        $html .= '<tr><th>Peso</th><td>' . htmlspecialchars($peso) . '</td></tr>';
        $html .= '<tr><th>Volumen</th><td>' . htmlspecialchars($volumen) . '</td></tr>';
        $html .= '<tr><th>Área</th><td>' . htmlspecialchars($area) . '</td></tr>';
        $html .= '<tr><th>Superficie</th><td>' . htmlspecialchars($superficie) . '</td></tr>';
        $html .= '<tr><th>Material</th><td>';
        $html .=  $this->material->__toString();
        $html .= '<tr><th>Resistencia a la tracción</th><td>' . htmlspecialchars($this->material->resistenciaTraccion) . '</td></tr>';
        $html .= '<tr><th>Flexibilidad</th><td>' . htmlspecialchars($this->material->flexibilidad) . '</td></tr>';
        $html .= '<tr><th>Densidad</th><td>' . htmlspecialchars($this->material->densidad) . '</td></tr>';
        $html .= '<tr><th>Coeficiente de desgaste</th><td>' . htmlspecialchars($this->material->coeficienteDesgaste) . '</td></tr>';
        $html .= '</table></table>';
        
        return $html;
    }

    public static function probarInvento(array $argumentos = []): void{
        $cuerda = new Cuerda(...$argumentos);

        echo $cuerda->__toString();
    }

}

?>