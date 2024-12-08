<?php
include_once 'Invento.php';
include_once 'Material.php';
include_once 'Fuego.php';
include_once 'Cesta.php';
include_once 'src/traits/CalculosGeometricos.php';
include_once 'src/interfaces/Medible.php';

class Ceramica extends Invento implements Medible{
    use CalculosGeometricos;
    private const FIGURA = 'Cilindro';
    private Material $material;
    private Fuego $fuego;
    private Cesta $cesta;
    private int $puntuacion;
    private array $tamanyo;
    private float $eficiencia;
    protected static array $inventosPrevios;
    
    
    protected string $zonaCreacion;
    protected string $metodoCreacion;
    protected int $tiempoCreacion;

    public function __construct(string $nombre, Material $material, Fuego $fuego, Cesta $cesta, string $zonaCreacion = null, string $metodoCreacion = null){
        parent::__construct($nombre, 2);
        $this->material = $material;
        $this->fuego = $fuego;
        $this->cesta = $cesta;
        $this->tamanyo = $this->obtenerTamanyo();
        self::$inventosPrevios = ['Fuego'=> 1, 'Cesta'=> 1];
        $this->eficiencia = $this->calcularEficiencia();
        
        $this->zonaCreacion = $zonaCreacion ?? 'pradera';
        $this->metodoCreacion = $metodoCreacion ?? 'tradicional';
        $this->tiempoCreacion = $this->calcularTiempoCreacion();
        $this->tiempoFinal = $this->calcularTiempoTotal($this->tiempoCreacion);
        $this->puntuacion = $this->calcularPuntuacion();
    }

    public function getFigura(): string{
        return self::FIGURA;
    }

    public static function getInventosPrevios(): array{
        return self::$inventosPrevios;
    }

    public function getEficiencia(): float {
        return $this->eficiencia;
    }

    public function __get($atributo){
        
        if(property_exists($this, $atributo)){
            
            return $this->$atributo;

        }else{
            echo "El atributo $atributo no existe";
         }
    }

    public function __set($atributo,$valor){
        if(property_exists($this, $atributo)){
            $this->$atributo = $valor;
        }else{
            echo "El atributo $atributo no se ha podido guardar";
        }
    }

    public function calcularEficiencia(): float{
        try {
            $eficienciaCeramica = $this->material->calcularEficiencia([
                'beneficiosos' => ['resistenciaTemperatura', 'coeficienteDesgaste'],
                'perjudiciales' => ['fragilidad']
            ]);
            $eficienciaFuego = $this->fuego->getEficiencia();
            $eficienciaCesta = $this->cesta->getEficiencia();

            $eficiencia = round(($eficienciaCeramica + $eficienciaFuego + $eficienciaCesta) / 3, 2);

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
                $this->eficiencia -= 10;
                $tiempoFinal = 60 * $this->nivel * 0.75;
                break;

            case 'detallado':
                $this->eficiencia += 10;
                $tiempoFinal = 60 * $this->nivel * 1.5;
                break;
            default:
                echo 'El metodo de creacion no existe';
                return 0;
                break;
        }
        return round($tiempoFinal);
    }

    public function calcularPuntuacion():int{
        if($this->eficiencia <= 0){
            return 0;
        }
        $puntuacion = round($this->eficiencia);

        return $puntuacion;
    }
    //medible.php
    public function calcularPeso(): float{
        $densidad = $this->material->densidad;
        $volumen = $this->calcularVolumen();
        $pesoCeramica = $densidad * $volumen;
        $pesoCesta = $this->cesta->calcularPeso();
        $peso = $pesoCeramica + $pesoCesta;
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
        $tamanyo = $this->cesta->getTamanyo();
        return $tamanyo;
    }

    public function __toString():string{
        $figura = $this->getFigura();
        $radio = $this->tamanyo['radio'];
        $altura = $this->tamanyo['altura'];
        $grosor = $this->tamanyo['grosor'];
        $resistenciaTemperatura = $this->material->resistenciaTemperatura;
        $coeficienteDesgaste = $this->material->coeficienteDesgaste;
        $fragilidad = $this->material->fragilidad;
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
        $html .= '<tr><th>Figura</th><td>' . htmlspecialchars($figura) . '</td></tr>';
        $html .= '<tr><th>Tamaño (radio x altura x grosor)</th><td>' . htmlspecialchars($radio .' x '.$altura . ' x ' . $grosor) . '</td></tr>';
        $html .= '<tr><th>Peso</th><td>' . htmlspecialchars($peso) . '</td></tr>';
        $html .= '<tr><th>Volumen</th><td>' . htmlspecialchars($volumen) . '</td></tr>';
        $html .= '<tr><th>Área</th><td>' . htmlspecialchars($area) . '</td></tr>';
        $html .= '<tr><th>Superficie</th><td>' . htmlspecialchars($superficie) . '</td></tr>';
        $html .= '<tr><th>Material</th><td>';
        $html .=  $this->material->__toString();
        $html .= '<tr><th>Resistencia a la temperatura</th><td>' . htmlspecialchars($resistenciaTemperatura) . '</td></tr>';
        $html .= '<tr><th>Coeficiente de Desgaste</th><td>' . htmlspecialchars($coeficienteDesgaste) . '</td></tr>';
        $html .= '<tr><th>Fragilidad</th><td>' . htmlspecialchars($fragilidad) . '</td></tr>';
        
        $html .= ' </table>';
        $html .= '<tr><th>Fuego</th><td>';
        $html .= $this->fuego->__toString();
        $html .= '</td><tr><th>Cesta</th><td>';
        $html .= $this->cesta->__toString();
        $html.= '</td></table>';

        return $html;
    }

    public static function probarInvento(array $argumentos=[]): void{
        $ceramica = new Ceramica(...$argumentos);
        echo $ceramica->__toString();
    }
}

?>