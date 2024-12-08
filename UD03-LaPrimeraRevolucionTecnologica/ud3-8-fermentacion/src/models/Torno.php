<?php

include_once 'Material.php';
include_once 'Invento.php';
include_once 'Alfareria.php';
include_once 'Ceramica.php';
include_once 'Rueda.php';
include_once 'src/traits/CalculosGeometricos.php';
include_once 'src/interfaces/Medible.php';

class Torno extends Invento implements Medible{
    use CalculosGeometricos;
    protected const FIGURA = 'Cilindro';
    protected const NIVEL = 2;
    protected array $tamanyo;
    protected static array $inventosPrevios=[
        'Alfarería' => 1,
        'Ceramica' => 1,
        'Rueda' => 1
    ];
    protected static array $campos = [
        ['nombre' => 'alfareria', 'tipo' => 'select', 'variable' => 'Alfareria'],
        ['nombre' => 'ceramica', 'tipo' => 'select', 'variable' => 'Ceramica'],
        ['nombre' => 'rueda', 'tipo' => 'select', 'variable' => 'Rueda'],
        ['nombre' => 'nombre', 'tipo' => 'text'],
        ['nombre' => 'velocidadRotacion', 'tipo' => 'number'],
        ['nombre' => 'precision', 'tipo' => 'number'],
        ['nombre' => 'zona', 'tipo' => 'select', 'variable' => 'zonas'],
        ['nombre' => 'tecnica', 'tipo' => 'select', 'variable' => 'tecnicas']
    ];
    protected int $tiempoCreacion;

    public function __construct(
        protected Alfareria $alfareria,
        protected Ceramica $ceramica,
        protected Rueda $rueda,
        string $nombre, 
        protected float $velocidadRotacion,
        protected float $precision,
        protected string $zonaCreacion = 'pradera',
        protected string $metodoCreacion = 'tradicional'
    ){
        parent::__construct($nombre);
        $this->tamanyo = $this->obtenerTamanyo();
        $this->eficiencia = $this->calcularEficiencia();
        $this->tiempoCreacion = $this->calcularTiempoCreacion();
        $this->tiempoFinal = $this->calcularTiempoTotal($this->tiempoCreacion);
        $this->puntuacion = $this->calcularPuntuacion();
    }

    public function __get($atributo){
        if(property_exists($this, $atributo)){
            return $this->$atributo;
        }else{
            echo "El atributo $atributo no existe.";
        }
    }

    public function __set($atributo, $valor){
        if(property_exists($this, $atributo)){
            $this->$atributo = $valor;
        }else{
           echo "El atributo $atributo no se ha podido setear.";
        }
    }

    public static function getInventosPrevios(): array {
        return self::$inventosPrevios;
    }

    public static function getCampos(): array {
        return self::$campos;
    }

    public function getFigura():string{
        return self::FIGURA;
    }
    
    public function calcularEficiencia(): float{
        try {
            $eficienciaAlfareria = $this->alfareria->eficiencia;
            $eficienciaCeramica = $this->ceramica->eficiencia;
            $eficienciaRueda = $this->rueda->eficiencia;
            
            $eficiencia = ($eficienciaAlfareria + $eficienciaCeramica + $eficienciaRueda) / 3;

            return round($eficiencia, 2);

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
        $precision = $this->precision;
        $velocidadRotacion = $this->velocidadRotacion;
        $eficiencia = $this->eficiencia;
    
        $puntuacion = $eficiencia + ($precision / $velocidadRotacion) / 10;
        return round($puntuacion);
    }

    public function calcularPeso(): float{
        $pesoAlfareria = $this->alfareria->calcularPeso();
        $pesoCeramica = $this->ceramica->calcularPeso();
        $pesoRueda = $this->rueda->calcularPeso();
        
        $peso = $pesoAlfareria + $pesoCeramica + $pesoRueda;

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
        $tamanyo = $this->rueda->tamanyo;
        return $tamanyo;
    }

    public function __toString(): string{
        $figura = $this->getFigura();
        $radio = $this->tamanyo['radio'] / 100;
        $altura = $this->tamanyo['altura'] / 100;

        $html = parent::__toString();
        $html .= '<tr><th>Velocidad de Rotación</th><td>' . htmlspecialchars($this->velocidadRotacion) . '</td></tr>';
        $html .= '<tr><th>Precisión</th><td>' . htmlspecialchars($this->precision) . '</td></tr>';
        $html .= '<tr><th>Figura</th><td>' . htmlspecialchars($figura) . '</td></tr>';
        $html .= '<tr><th>Tamaño</th><td>' . htmlspecialchars("Radio: $radio m, Altura: $altura m") . '</td></tr>';
        $html .= $this->calculosGeometricos();
        $html .= '<tr><th>Alfareria</th><td>';
        $html .= $this->alfareria;
        $html .= '<tr><th>Cerámica</th><td>';
        $html .= $this->ceramica;
        $html .= '</td><tr><th>Rueda</th><td>';
        $html .= $this->rueda;
        $html .= '</td></table>';
        
        return $html;
    }

    public static function probarInvento(array $argumentos = []): void{
        $torno = new Torno(...$argumentos);

        echo $torno->__toString();
    }
}

?>