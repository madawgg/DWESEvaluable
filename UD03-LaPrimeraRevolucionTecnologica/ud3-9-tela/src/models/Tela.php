<?php

include_once 'Material.php';
include_once 'Invento.php';
include_once 'Cuerda.php';
include_once 'Ganaderia.php';
include_once 'Torno.php';
include_once 'Almacenamiento.php';
include_once 'src/traits/CalculosGeometricos.php';
include_once 'src/interfaces/Medible.php';


class Tela extends Invento implements Medible {
    use CalculosGeometricos;
    protected const FIGURA = 'Prisma Rectangular';
    protected const NIVEL = 2;
    protected static array $inventosPrevios =[
        'Cuerda'=> 20,
        'Ganaderia' => 2,
        'Torno' => 2,
    ];
    
    public static array $campos = [
        ['nombre' => 'material', 'tipo' => 'select', 'variable' => 'materiales'],
        ['nombre' => 'cuerda', 'tipo' => 'select', 'variable' => 'Cuerda'],
        ['nombre' => 'ganaderia', 'tipo' => 'select', 'variable' => 'Ganaderia'],
        ['nombre' => 'torno', 'tipo' => 'select', 'variable' => 'Torno'],
        ['nombre' => 'nombre', 'tipo' => 'text'],
        ['nombre' => 'zona', 'tipo' => 'select', 'variable' => 'zonas'],
        ['nombre' => 'tecnica', 'tipo' => 'select', 'variable' => 'tecnicas']
    ];
    protected array $tamanyo = [
        'altura',
        'longitud',
        'grosor'
    ];

    public function __construct(
        protected Material $material,
        protected array $cuerdas,
        protected array $ganaderias,
        protected array $tornos,
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

    public static function getInventosPrevios(): array {
        return self::$inventosPrevios;
    }
    public static function getCampos(): array {
        return self::$campos;
    }
    public function getFigura():string{
        return self::FIGURA;
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

   
    public function calcularEficiencia(): float{
        try {

            $eficienciaMaterial =  $this->material->calcularEficiencia([
                'beneficiosos' => ['resistenciaTraccion', 'resistenciaHumedad', 'flexibilidad'],
                'perjudiciales' => ['densidad', 'coeficienteDesgaste']
            ]);

            $eficienciaCuerdas = $this->sumarEficiencias($this->cuerdas);
            $eficienciaGanaderias = $this->sumarEficiencias($this->ganaderias);
            $eficienciaTornos =  $this->sumarEficiencias($this->tornos);
            $eficienciaTotal = $eficienciaCuerdas +
                               $eficienciaGanaderias +
                               $eficienciaTornos +
                               $eficienciaMaterial;

            $cantidadTotal = count($this->cuerdas) +
                             count ($this->ganaderias) +
                             count($this->tornos) +
                             1;

            $eficiencia = $eficienciaTotal / $cantidadTotal;

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

        $elementosGanaderia = 0;
        foreach ($this->ganaderias as $ganaderia) {
            $elementosGanaderia += $ganaderia->numeroElementos;
        }

        $mediaElementosGanaderia = $elementosGanaderia / 
                                count($this->ganaderias);

        $puntos = $this->eficiencia + 
                ($elementosGanaderia - 
                $mediaElementosGanaderia);

        return round($puntos);
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

        $maxAltura = 0;
        $maxLongitud = 0;
        $maxRadio = 0;
    
        foreach ($this->cuerdas as $cuerda) {
            $maxAltura = max($maxAltura, $cuerda->tamanyo['altura']);
            $maxLongitud = max($maxLongitud, $cuerda->tamanyo['altura']);       
            $maxRadio = max($maxRadio, $cuerda->tamanyo['radio']);   
        }
    
        $tamanyo['altura'] = $maxAltura;
        $tamanyo['longitud'] = $maxLongitud;
        $tamanyo['ancho'] = $maxRadio * 2;
    
        return $tamanyo;
    }
    public function __toString(): string{
        
       

        $html = parent::__toString();
        $html .= $this->calculosGeometricos(); 
        $html .= '<tr><th>Material</th><td>';
        $html .= $this->material;
        $html .= '<tr><th>Resistencia a la tracción</th><td>' . htmlspecialchars($this->material->resistenciaTraccion) . ' sobre 100</td></tr>';
        $html .= '<tr><th>Resistencia a la humedad</th><td>' . htmlspecialchars($this->material->resistenciaHumedad) . ' sobre 100</td></tr>';
        $html .= '<tr><th>Fleixibilidad</th><td>' . htmlspecialchars($this->material->flexibilidad) . ' sobre 100</td></tr>';
        $html .= '<tr><th>Densidad</th><td>' . htmlspecialchars($this->material->densidad) . ' sobre 100</td></tr>';
        $html .= '<tr><th>Coeficiente de desgaste</th><td>' . htmlspecialchars($this->material->coeficienteDesgaste) . ' sobre 100</td></tr>';
        $html .= '</table>';
       
        $html .= '</td></tr>';
        for ($i=0; $i < count($this->cuerdas) ; $i++) { 
            $html .= "<tr><th>Cuerda ". $i+1 ." </th><td>".  $this->cuerdas[$i] . "</td><tr>";
        }
        for ($i=0; $i < count($this->ganaderias) ; $i++) { 
            $html .= "<tr><th>Ganadería ". $i+1 ." </th><td>".  $this->ganaderias[$i] . "</td><tr>";
        }
        for ($i=0; $i < count($this->tornos) ; $i++) { 
            $html .= "<tr><th>Torno ". $i+1 ." </th><td>".  $this->tornos[$i] . "</td><tr>";
        }
        $html .= '</td></table>';

        return $html;
    }
    public static function probarInvento(array $argumentos=[]): void{
        $agricultura = new Agricultura(...$argumentos);
        echo $agricultura;
    }


}
?>