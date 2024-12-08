<?php
include_once 'Invento.php';
include_once 'Material.php';
include_once 'PiedraAfilada.php';
include_once 'src/traits/CalculosGeometricos.php';
include_once 'src/interfaces/Medible.php';


class Hacha extends invento implements Medible{
    use CalculosGeometricos;
    private const FIGURA = 'Cilindro';
    private float $longitud = 0;
    private Material $material;
    private PiedraAfilada $piedraAfilada;
    private float $eficienciaMango;
    private float $eficiencia;
    private int $puntuacion;
    private array $tamanyo;
    protected static array $invetosPrevios = [];

    protected string $zonaCreacion;
    protected string $metodoCreacion;
    protected int $tiempoCreacion;
    
    public function __construct(string $nombre,  Material $material, PiedraAfilada $piedraAfilada, array $tamanyo, string $zonaCreacion = null, string $metodoCreacion = null){
        parent::__construct($nombre,1);

        //$this->longitud = $longitud;
        $this->material = $material;
        $this->piedraAfilada = $piedraAfilada;
        $this->tamanyo = $tamanyo;
        self::$inventosPrevios = [
            'PiedraAfilada' => 1
        ];
        $this->zonaCreacion = $zonaCreacion ?? 'pradera';
        $this->metodoCreacion = $metodoCreacion ?? 'tradicional';
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
    public function getLongitud(): float{
        return $this->longitud;
    }
    public function getMaterial(): Material{
        return $this->material;
    }
    public function getPiedraAfilada(): PedraAfilada{
        return $this->piedraAfilada;
    }
    public function getEficienciaMango(): float{
        return $this->eficienciaMango;
    }
    public function getEficiencia(): float{
        return $this->eficiencia;
    }
    public function getPuntuacion(): int{
        return $this->puntuacion;
    }

    //setters
    public function setLongitud(float $longitud): void{
        $this->longitud = $longitud;
    }
    public function setMaterial(Material $material): void{
        $this->material = $material;
    }
    public function setPiedraAfilada(PiedraAfialda $piedraAfilada): void{
        $this->piedraAfilada = $piedraAfilada;
    }
    public function setEficienciaMango(float $eficienciaMango): void{
        $this->eficienciaMango = $eficienciaMango;
    }
    public function setEficiencia(float $eficiencia): void{
        $this->eficiencia = $eficiencia;
    }
    public function setPuntuacion(float $puntuacion): void{
        $this->puntuacion = $puntuacion;
    }

    public function calcularEficiencia(): float{
        try {
           
            $eficienciaMango = $this->material->calcularEficiencia([
                'beneficiosos' => ['dureza', 'tenacidad', 'resistenciaTraccion'],
                'perjudiciales' => ['densidad', 'fragilidad']
            ]);
            $this->eficienciaMango = $eficienciaMango;
            
            $eficienciaPiedraAfilada = $this->piedraAfilada->getEficiencia();

            $eficiencia = round(($eficienciaPiedraAfilada + $eficienciaMango)/2, 2);
           
            return $eficiencia;
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


    public function calcularPuntuacion(): int{
        if($this->eficiencia <= 0){
            return 0;
        }
        $puntuacion = $this->eficiencia + 10;
        return round($puntuacion);
    }
    //medible.php
    public function calcularPeso(): float{
        $densidadMango = $this->material->densidad;
        $volumenMango = $this->calcularVolumen();
        $pesoMango = $densidadMango * $volumenMango;
        
        $pesoPiedra= $this->piedraAfilada->calcularPeso();

        $peso = $pesoMango + $pesoPiedra;
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
       
        $html .= '<tr><th colspan=2>Inventos previos</th></tr>';
        foreach (self::$inventosPrevios as $invento => $valor) {
            $html .= '<tr><th>'.$invento.'</th><td>' . $valor . '</td></tr>';
        } 

        $html .= '<tr><th>Figura</th><td>' . htmlspecialchars($figura) . '</td></tr>';
        $html .= '<tr><th>Tamaño (radio x altura)</th><td>' . htmlspecialchars($radio .' x '.$altura) . '</td></tr>';
        $html .= '<tr><th>Peso</th><td>' . htmlspecialchars($peso) . '</td></tr>';
        $html .= '<tr><th>Volumen</th><td>' . htmlspecialchars($volumen) . '</td></tr>';
        $html .= '<tr><th>Área</th><td>' . htmlspecialchars($area) . '</td></tr>';
        $html .= '<tr><th>Superficie</th><td>' . htmlspecialchars($superficie) . '</td></tr>';
        $html .= '<tr><th>Material</th><td>';
        $html .=  $this->material->__toString();
        $html .= '<tr><th>Dureza</th><td>' . htmlspecialchars($this->material->dureza) . '</td></tr>';
        $html .= '<tr><th>Tenacidad</th><td>' . htmlspecialchars($this->material->tenacidad) . '</td></tr>';
        $html .= '<tr><th>Resistencia a la tracción</th><td>' . htmlspecialchars($this->material->resistenciaTraccion) . '</td></tr>';
        $html .= '<tr><th>Densidad</th><td>' . htmlspecialchars($this->material->densidad) . '</td></tr>';
        $html .= '<tr><th>Fragilidad</th><td>' . htmlspecialchars($this->material->fragilidad) . '</td></tr>';
        $html .= '<tr><th>Eficacia del mango</th><td>' . htmlspecialchars($this->eficienciaMango) . '</td></tr>';
        $html .= ' </table>';
        $html .= '<tr><th>Piedra Afilada</th><td>';
        $html .= $this->piedraAfilada->__toString();
        $html.= '</td></table>';

        return $html;
    }

    public static function probarInvento(array $argumentos= []): void{
        $hacha = new Hacha(...$argumentos);
        echo $hacha->__toString();
    }   
}
?>