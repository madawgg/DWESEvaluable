<?php

include_once 'Material.php';
include_once 'Tela.php';
include_once 'Cuerda.php';
include_once 'Hacha.php';
include_once 'Carro.php';
include_once 'Almacenamiento.php';
include_once 'src/traits/CalculosGeometricos.php';
include_once 'src/interfaces/Medible.php';


Class Barco extends Almacenamiento implements Medible{
    use CalculosGeometricos;
    protected const FIGURA = 'Prisma Rectangular';
    protected const NIVEL = 2;
    protected const TAMANYO_ELEMENTO = 160;
    protected static array $inventosPrevios = [
        'Tela' => 3,
        'Cuerda' => 10,
        'Hacha' => 1,
        'Carro' => 1
    ];
    public static array $campos = [
        ['nombre' => 'material', 'tipo' => 'select', 'variable' => 'materiales'],
        ['nombre' => 'tela', 'tipo' => 'select', 'variable' => 'Tela'],
        ['nombre' => 'cuerda', 'tipo' => 'select', 'variable' => 'Cuerda'],
        ['nombre' => 'hacha', 'tipo' => 'select', 'variable' => 'Hacha'],
        ['nombre' => 'carro', 'tipo' => 'select', 'variable' => 'Carro'],
        ['nombre' => 'nombre', 'tipo' => 'text'],
        ['nombre' => 'longitud', 'tipo' => 'number'],
        ['nombre' => 'ancho', 'tipo' => 'number'],
        ['nombre' => 'altura', 'tipo' => 'number'],
        ['nombre' => 'grosor', 'tipo' => 'number'],
        ['nombre' => 'zona', 'tipo' => 'select', 'variable' => 'zonas'],
        ['nombre' => 'tecnica', 'tipo' => 'select', 'variable' => 'tecnicas'],
        ['nombre' => 'numeroElementos', 'tipo' => 'number']
    ];

    public function __construct(
        protected Material $material,
        protected array $telas,
        protected array $cuerdas,
        protected Hacha $hacha,
        protected Carro $carro,
        string $nombre,
        protected array $tamanyo,
        protected string $zonaCreacion = 'pradera',
        protected string $metodoCreacion = 'tradicional',
        int $numeroElementos = 0
    ){
        parent::__construct($nombre, $tamanyo, $numeroElementos);
        $this->tamanyo = $this->obtenerTamanyo();
        $this->capacidad = Almacenamiento::calcularCapacidad(); 
        $this->eficiencia = $this->calcularEficiencia();
        $this->tiempoCreacion = $this->calcularTiempoCreacion();
        $this->tiempoFinal = $this->calcularTiempoTotal($this->tiempoCreacion);
        $this->puntuacion = $this->calcularPuntuacion();
       
    }
    public function getFigura(): string{
        return self::FIGURA;
    }
    
    public static function getInventosPrevios(): array {
        return self::$inventosPrevios;
    }
   
    public static function getCampos(): array {
        return self::$campos;
    }

    public function __get( $atributo){
      
        if(property_exists($this, $atributo)){
            return $this->$atributo;
         }
         else{
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
   
    
    public function calcularEficiencia(): float{
        $eficienciaMaterial = $this->material->calcularEficiencia([
            'beneficiosos' => ['resistecinaTraccion', 'resistenciaOxidacion', 'resistenciaUV', 'resistenciaCompresion'],
            'perjudiciales' => ['densidad', 'coeficienteDesgaste', 'coeficienteFriccion', 'fragilidad']
        ]);

        $eficienciaTelas = $this->sumarEficiencias($this->telas);
        $eficienciaCuerdas = $this->sumarEficiencias($this->cuerdas);
        $eficienciaHacha = $this->hacha->eficiencia;
        $eficienciaCarro = $this->carro->eficiencia;
        
        $totalEficiencias = $eficienciaTelas +
                            $eficienciaCuerdas +
                            $eficienciaHacha +
                            $eficienciaCarro +
                            $eficienciaMaterial;

        $cantidadTotal = count($this->telas) +
                        count($this->cuerdas) +
                        3;
        
        $eficiencia = $totalEficiencias / $cantidadTotal; 
        

        return round($eficiencia, 2);
    }

    public function calcularTiempoCreacion(): int {
        
        $tiempoBase = 60 * $this->nivel; 
       
        switch ($this->metodoCreacion) {
            case 'tradicional':
                $tiempoBase;
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
        if($this->eficiencia <= 0 ){
            return 0;
        }
        $eficienciaBarco = round($this->eficiencia);
        $altura = $this->tamanyo['altura'];
        $capacidad = $this->calcularCapacidad();  
        $puntuacion = $eficienciaBarco;
        
        if ($altura <= 100){
            if($capacidad > 30){
                $puntuacion += 20;
            }elseif ($capacidad > 20) {
                $puntuacion += 15;
            }elseif ($capacidad >10) {
                $puntuacion += 10;
            }
        }

        return round($puntuacion);
    }

   
    //medible.php
    public function calcularPeso(): float{
        $densidadBarco = $this->material->getDensidadReal();
        $volumenBarco = $this->volumen($this->getfigura(), $this->tamanyo);
        $pesoMaterialBarco = $densidadBarco * $volumenBarco;
        $pesoRuedas = $this->carro->rueda1->calcularPeso() + $this->carro->rueda2->calcularPeso();
        $pesoCarro = $this->carro->calcularPeso() - $pesoRuedas;
        
        $peso = $this->sumarPesos($this->telas) +
                $this->sumarPesos($this->cuerdas) +
                $this->hacha->calcularPeso() + 
                $pesoCarro + 
                $pesoMaterialBarco;

        return round($peso, 2);
    }

    public function calcularVolumen(): float{
       $volumen =  $this->volumen($this->getFigura(), $this->tamanyo);
       return round($volumen,2);
    }

    public function calcularArea(): float{
        $area = $this->area($this->getFigura(), $this->tamanyo);
        return round($area,2);
    }

    public function calcularSuperficie(): float{
        $superficie = $this->superficie($this->getFigura(), $this->tamanyo);
        return round($superficie,2);
    }

    public function obtenerTamanyo(): array{
        $tamanyo = $this->tamanyo;
        return $tamanyo;
    }

    public function __toString(): string{
        $html = parent::__toString();
        $html .= $this->calculosGeometricos(); 
        $html .= '<tr><th>Material</th><td>';
        $html .= $this->material;
        $html .= '<tr><th>Resistencia a la tracción</th><td>' . htmlspecialchars($this->material->resistenciaTraccion) . ' sobre 100</td></tr>';
        $html .= '<tr><th>Resistencia a la humedad</th><td>' . htmlspecialchars($this->material->resistenciaHumedad) . ' sobre 100</td></tr>';
        $html .= '<tr><th>Resistencia UV</th><td>' . htmlspecialchars($this->material->resistenciaUV) . ' sobre 100</td></tr>';
        $html .= '<tr><th>Resistencia Compresión</th><td>' . htmlspecialchars($this->material->resistenciaCompresion) . ' sobre 100</td></tr>';
        $html .= '<tr><th>Densidad</th><td>' . htmlspecialchars($this->material->densidad) . ' sobre 100</td></tr>';
        $html .= '<tr><th>Coeficiente de Fricción</th><td>' . htmlspecialchars($this->material->coeficienteFriccion) . ' sobre 100</td></tr>';
        $html .= '<tr><th>Coeficiente de Desgaste</th><td>' . htmlspecialchars($this->material->coeficienteDesgaste) . ' sobre 100</td></tr>';
        $html .= '<tr><th>Fragilidad</th><td>' . htmlspecialchars($this->material->fragilidad) . ' sobre 100</td></tr>';
        $html .= '</table>';
      
        for ($i=0; $i < count($this->telas) ; $i++) { 
            $html .= "<tr><th>Tela $i</th><td>".  $this->telas[$i] . "</td><tr>";
        }
        for ($i=0; $i < count($this->cuerdas) ; $i++) { 
            $html .= "<tr><th>Cuerda $i</th><td>".  $this->cuerdas[$i] . "</td><tr>";
        }
        $html .= '<tr><th>Tela</th><td>';
        $html .= $this->hacha;
        $html .= '</td></tr>';
        $html .= '<tr><th>Tela</th><td>';
        $html .= $this->carro;
        $html .= '</td></tr>';
        $html .= '</td></table>';

        return $html;
    }
    public static function probarInvento(array $argumentos=[]): void{
        $barco = new Barco(...$argumentos);
        echo $barco;
    }
 }

?>