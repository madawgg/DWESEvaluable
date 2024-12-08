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
    private float $eficiencia;
    private int $puntuacion;
    private array $tamanyo;
    protected static array $inventosPrevios;


    public function __construct(string $nombre, Material $material, Fuego $fuego, Cesta $cesta){
        parent::__construct($nombre, 2);
        $this->material = $material;
        $this->fuego = $fuego;
        $this->cesta = $cesta;
        $this->eficiencia= $this->calcularEficiencia();
        $this->puntuacion = $this->calcularPuntuacion();
        $this->tamanyo = $this->obtenerTamanyo();
        self::$inventosPrevios = [
            'Fuego'=> 1,
            'Cesta'=> 1
        ];
    }

    public function getFigura(): string{
        return self::FIGURA;
    }

    public static function getInventosPrevios(): array{
        return self::$inventosPrevios;
    }

    public function __get($atributo){
        
        if(property_exists($this, $atributo)){
            
            return $this->atributo;
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
            $eficienciaFuego = $this->fuego->eficiencia;
            $eficienciaCesta = $this->cesta->eficiencia;

            $eficiencia = ($eficienciaCeramica + $eficienciaFuego + $eficienciaCesta) / 3;

            return round($eficiencia, 2);
        
        } catch (Exception $e) {
            return 0.00;
        }
    }

    public function calcularPuntuacion():int{
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
        $html .= '<tr><th>Eficiencia</th><td>' . htmlspecialchars($this->calcularEficiencia()) . '</td></tr>';
        $html .= '<tr><th>Puntos</th><td>' . htmlspecialchars($this->calcularPuntuacion()) . '</td></tr>';
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