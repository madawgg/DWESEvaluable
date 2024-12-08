<?php
include_once 'Invento.php';
include_once 'Material.php';
include_once 'Fuego.php';
include_once 'Cesta.php';
include_once 'src/traits/CalculosGeometricos.php';
include_once 'src/interfaces/Medible.php';
include_once 'Almacenamiento.php';

class Ceramica extends Almacenamiento implements Medible{
    use CalculosGeometricos;
    protected const FIGURA = 'Cilindro';
    protected const NIVEL = 2;
    protected const TAMANYO_ELEMENTO = 10;
    protected array $tamanyo;
    protected static array $inventosPrevios = [
        'Fuego'=> 1, 
        'Cesta'=> 1
    ];
    protected static array $campos = [
        ['nombre' => 'material', 'tipo' => 'select', 'variable' => 'materiales'],
        ['nombre' => 'fuego', 'tipo' => 'select', 'variable' => 'Fuego'],
        ['nombre' => 'cesta', 'tipo' => 'select', 'variable' => 'Cesta'],
        ['nombre' => 'nombre', 'tipo' => 'text'],
        ['nombre' => 'zona', 'tipo' => 'select', 'variable' => 'zonas'],
        ['nombre' => 'tecnica', 'tipo' => 'select', 'variable' => 'tecnicas'],
        ['nombre' => 'numeroElementos', 'tipo' => 'number']
    ];
    protected int $tiempoCreacion;

    public function __construct(
        protected Material $material,
        protected Fuego $fuego,
        protected Cesta $cesta,
        string $nombre, 
        protected string $zonaCreacion = 'pradera',
        protected string $metodoCreacion = 'tradicional',
        int $numeroElementos = 0
    ){
        $this->tamanyo = $this->obtenerTamanyo();
        parent::__construct($nombre, $this->tamanyo, $numeroElementos);
        $this->eficiencia = $this->calcularEficiencia();
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

    public static function getCampos(): array {
        return self::$campos;
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
            $eficienciaFuego = $this->fuego->eficiencia;
            $eficienciaCesta = $this->cesta->eficiencia;

            $eficiencia = round(($eficienciaCeramica + $eficienciaFuego + $eficienciaCesta) / 3, 2);

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

    public function calcularPuntuacion():int{
        if($this->eficiencia <= 0){
            return 0;
        }
        $puntuacion = round($this->eficiencia);

        return $puntuacion;
    }
    //medible.php
    public function calcularPeso(): float{
        $densidad = $this->material->getDensidadReal();
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
        $tamanyo = $this->cesta->tamanyo;
        return $tamanyo;
    }

    public function __toString():string{
        $resistenciaTemperatura = $this->material->resistenciaTemperatura;
        $coeficienteDesgaste = $this->material->coeficienteDesgaste;
        $fragilidad = $this->material->fragilidad;
        
        $html = parent::__toString();
        $html .= $this->calculosGeometricos();
        $html .= '<tr><th>Material</th><td>';
        $html .=  $this->material;
        $html .= '<tr><th>Resistencia a la temperatura</th><td>' . htmlspecialchars($resistenciaTemperatura) . ' sobre 100</td></tr>';
        $html .= '<tr><th>Coeficiente de Desgaste</th><td>' . htmlspecialchars($coeficienteDesgaste) . ' sobre 100</td></tr>';
        $html .= '<tr><th>Fragilidad</th><td>' . htmlspecialchars($fragilidad) . ' sobre 100</td></tr>';
        $html .= ' </table>';
        $html .= '<tr><th>Fuego</th><td>';
        $html .= $this->fuego;
        $html .= '</td><tr><th>Cesta</th><td>';
        $html .= $this->cesta;
        $html.= '</td></table>';

        return $html;
    }

    public static function probarInvento(array $argumentos=[]): void{
        $ceramica = new Ceramica(...$argumentos);
        echo $ceramica->__toString();
    }
}

?>