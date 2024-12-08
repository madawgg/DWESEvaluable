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
    private const FIGURA = 'Cilindro';
    private Alfareria $alfareria;
    private Ceramica $ceramica;
    private Rueda $rueda;
    private float $velocidadRotacion;
    private float $precision;
    
    private float $eficiencia;
    private int $puntuacion;
    private array $tamanyo = [];
    protected static array $inventosPrevios=[];

    protected string $zonaCreacion;
    protected string $metodoCreacion;
    protected int $tiempoCreacion;

    public function __construct(string $nombre, float $velocidadRotacion, float $precision, Alfareria $alfareria, Ceramica $ceramica, Rueda $rueda, string $zonaCreacion = null, string $metodoCreacion = null ){
        parent::__construct($nombre, 2);
        $this->alfareria = $alfareria;
        $this->ceramica = $ceramica;
        $this->rueda = $rueda;
        $this->velocidadRotacion = $velocidadRotacion;
        $this->precision = $precision;
        $this->tamanyo = $this->rueda->tamanyo;
        self::$inventosPrevios = [
            'Alfarería' => 1,
            'Ceramica' => 1,
            'Rueda' => 1
        ];
        $this->zonaCreacion = $zonaCreacion ?? 'pradera';
        $this->metodoCreacion = $metodoCreacion ?? 'tradicional';
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
        $tamanyo = $this->refugio->obtenerTamanyo();
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
        $html .= '<tr><th>Eficiencia</th><td>' . htmlspecialchars($this->eficiencia) . '</td></tr>';
        $html .= '<tr><th>Puntos</th><td>' . htmlspecialchars($this->puntuacion) . '</td></tr>';
        
        $html .= '<tr><th>Zona de creación</th><td>' . htmlspecialchars($this->zonaCreacion) . '</td></tr>';
        $html .= '<tr><th>Metodo de creación</th><td>' . htmlspecialchars($this->metodoCreacion) . '</td></tr>';
        $html .= '<tr><th>Tiempo de inicio</th><td>' . htmlspecialchars($this->tiempoInicial->format('Y-m-d H:i:s')) . '</td></tr>';
        $html .= '<tr><th>Tiempo de creación</th><td>' . htmlspecialchars($this->tiempoCreacion) . '</td></tr>';
        $html .= '<tr><th>Tiempo de fin</th><td>' . htmlspecialchars($this->tiempoFinal->format('Y-m-d H:i:s')) . '</td></tr>';
        
        $html .= '<tr><th colspan=2>Inventos previos</th></tr>';
        foreach (self::$inventosPrevios as $invento => $valor) {
        $html .= '<tr><th>'.$invento.'</th><td>' . $valor . '</td></tr>';
        }

        $html .= '<tr><th>Velocidad de Rotación</th><td>' . htmlspecialchars($this->velocidadRotacion) . '</td></tr>';
        $html .= '<tr><th>Precisión</th><td>' . htmlspecialchars($this->precision) . '</td></tr>';
        $html .= '<tr><th>Figura</th><td>' . htmlspecialchars($figura) . '</td></tr>';
        $html .= '<tr><th>Tamaño (radio x altura)</th><td>' . htmlspecialchars($radio .' x '.$altura) . '</td></tr>';
        $html .= '<tr><th>Peso</th><td>' . htmlspecialchars($peso) . '</td></tr>';
        $html .= '<tr><th>Volumen</th><td>' . htmlspecialchars($volumen) . '</td></tr>';
        $html .= '<tr><th>Área</th><td>' . htmlspecialchars($area) . '</td></tr>';
        $html .= '<tr><th>Superficie</th><td>' . htmlspecialchars($superficie) . '</td></tr>';
        $html .= '<tr><th>Alfareria</th><td>';
        $html .= $this->alfareria->__toString();
        $html .= '<tr><th>Cerámica</th><td>';
        $html .= $this->ceramica->__toString();
        $html .= '</td><tr><th>Rueda</th><td>';
        $html .= $this->rueda->__toString();
        $html .= '</td></table>';
        
        return $html;
    }

    public static function probarInvento(array $argumentos = []): void{
        $torno = new Torno(...$argumentos);

        echo $torno->__toString();
    }
}

?>