<?php

include_once 'Invento.php';
include_once 'Material.php';
include_once 'Rueda.php';
include_once 'Hacha.php';
include_once 'Agricultura.php';
include_once 'src/interfaces/Medible.php';
include_once 'src/traits/CalculosGeometricos.php';

Class Arado extends Invento implements Medible{
    use CalculosGeometricos;
    protected const FIGURA = 'Prisma Rectangular';
    protected const NIVEL = 2;
    protected static array $inventosPrecios = [
        'Rueda' => 2,
        'Hacha' => 1,
        'Agricultura' => 1
    ];
    public static array $campos = [
        ['nombre' => 'material', 'tipo' => 'select', 'variable' => 'materiales'],
        ['nombre' => 'rueda', 'tipo' => 'select', 'variable' => 'Rueda'],
        ['nombre' => 'hacha', 'tipo' => 'select', 'variable' => 'Hacha'],
        ['nombre' => 'agricultura', 'tipo' => 'select', 'variable' => 'Agricultura'],
        ['nombre' => 'nombre', 'tipo' => 'text'],
        ['nombre' => 'zona', 'tipo' => 'select', 'variable' => 'zonas'],
        ['nombre' => 'tecnica', 'tipo' => 'select', 'variable' => 'tecnicas']
    ];

    protected array $tamanyo;
    public function __construct(
        protected Material $material,
        protected array $ruedas,
        protected Hacha $hacha,
        protected Agricultura $agricultura,
        string $nombre,
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
            echo "El atributo $atributo no existe en Arado";
        }
    }
    public function __set($atributo, $valor){
        if(property_exists($this, $atributo)){
            $this->$atributo = $valor;
        }else {
            echo "El atributo $atributo no se ha podido establecer en Arado.";
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

            $eficienciaMaterial =  $this->material->calcularEficiencia([
                'beneficiosos' => ['dureza', 'resistenciaOxidacion', 'tenacidad'],
                'perjudiciales' => ['densidad', 'coeficienteDesgaste']
            ]);

            $eficienciaRueda0 = $this->ruedas[0]->eficiencia;
            $eficienciaRueda1 = $this->ruedas[1]->eficiencia;
            $eficienciaHacha = $this->hacha->eficiencia;
            $eficienciaAgricultura = $this->agricultura->eficiencia;

            $eficiencia = ($eficienciaMaterial + $eficienciaRueda0 + $eficienciaRueda1 + $eficienciaHacha + $eficienciaAgricultura) / 5;

            return round($eficiencia, 2);

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
        $elementosGanaderia = $this->agricultura->ganaderia->numeroElementos;
        $elementosAgricultura = $this->agricultura->numeroElementos;
        $puntos = $this->eficiencia + ($elementosGanaderia - $elementosAgricultura);

        return round($puntos);
    }
    public function calcularPeso(): float{
        $densidad = $this->material->getDensidadReal();
        $volumen = $this->calcularVolumen();

        $pesoMaterial = $densidad * $volumen;
        $pesoRueda1 = $this->ruedas[0]->calcularPeso();
        $pesoRueda2 = $this->ruedas[1]->calcularPeso();
        $pesoHacha = $this->hacha->calcularPeso();
        $peso = $pesoMaterial + $pesoRueda1 + $pesoRueda2 + $pesoHacha;

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
        $anchoRuedas = $this->ruedas[0]->tamanyo['altura'] * 2;
        $diametroHacha = $this->hacha->tamanyo['radio']* 2;
        
        $ancho = $anchoRuedas + $diametroHacha;
        $altura = $this->ruedas[0]->tamanyo['radio'] * 2;
        $longitud = $this->hacha->tamanyo['altura'] * 2;

        $tamanyo = [
            'longitud' => $longitud,
            'altura' => $altura,
            'ancho' => $ancho
        ];

        return $tamanyo;
    }
    public function __toString(): string{
        $html = parent::__toString();
        $html .= $this->calculosGeometricos(); 
        $html .= '<tr><th>Material</th><td>';
        $html .= $this->material;
        $html .= '<tr><th>Dureza</th><td>' . htmlspecialchars($this->material->dureza) . ' sobre 100</td></tr>';
        $html .= '<tr><th>Resistencia a la corrosión</th><td>' . htmlspecialchars($this->material->resistenciaCorrosion) . ' sobre 100</td></tr>';
        $html .= '<tr><th>Tenacidad</th><td>' . htmlspecialchars($this->material->tenacidad) . ' sobre 100</td></tr>';
        $html .= '<tr><th>Densidad</th><td>' . htmlspecialchars($this->material->densidad) . ' sobre 100</td></tr>';
        $html .= '<tr><th>Coeficiente de desgaste</th><td>' . htmlspecialchars($this->material->coeficienteDesgaste) . ' sobre 100</td></tr>';
        $html .= '</table>';
       
        $html .= '</td></tr>';
        for ($i=0; $i < count($this->ruedas) ; $i++) { 
            $html .= "<tr><th>Rueda ". $i+1 ." </th><td>".  $this->ruedas[$i] . "</td><tr>";
        }
        $html .= '<tr><th>Hacha</th><td>';
        $html .= $this->hacha;
        $html .= '<tr><th>Agricultura</th><td>';
        $html .= $this->agricultura;
        $html .= '</td></table>';

        return $html;
    }
    public static function probarInvento(array $argumentos=[]): void{
        $agricultura = new Agricultura(...$argumentos);
        echo $agricultura;
    }
}

?>