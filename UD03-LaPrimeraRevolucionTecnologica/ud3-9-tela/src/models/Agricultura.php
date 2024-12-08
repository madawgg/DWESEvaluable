<?php

include_once 'Material.php';
include_once 'Cesta.php';
include_once 'Lanza.php';
include_once 'Ganaderia.php';
include_once 'Almacenamiento.php';
include_once 'src/traits/CalculosGeometricos.php';
include_once 'src/interfaces/Medible.php';


Class Agricultura extends Almacenamiento implements Medible{
    use CalculosGeometricos;
    protected const FIGURA = 'Prisma Rectangular';
    protected const NIVEL = 2;
    protected const TAMANYO_ELEMENTO = 300;
    protected static array $inventosPrevios = [
        'Cesta' => 10,
        'Lanza' => 20,
        'Ganaderia'=> 1
    ];
    public static array $campos = [
        ['nombre' => 'material', 'tipo' => 'select', 'variable' => 'materiales'],
        ['nombre' => 'cesta', 'tipo' => 'select', 'variable' => 'Cesta'],
        ['nombre' => 'lanza', 'tipo' => 'select', 'variable' => 'Lanza'],
        ['nombre' => 'ganaderia', 'tipo' => 'select', 'variable' => 'Ganaderia'],
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
        protected array $cestas,
        protected array $lanzas,
        protected Ganaderia $ganaderia,
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
            'beneficiosos' => ['dureza', 'resistenciaUV', 'tenacidad'],
            'perjudiciales' =>['densidad', 'coeficienteDesgaste']
        ]);

        $sumaEficiencias = $this->sumarEficiencias($this->cestas) + 
                           $this->sumarEficiencias($this->lanzas) + 
                           $this->ganaderia->eficiencia +
                           $eficienciaMaterial;
                           

        
        $sumaCantidad = count($this->cestas) + 
                            count($this->lanzas) + 
                            2;
        
       
        $eficiencia = $sumaEficiencias / $sumaCantidad;

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
        $puntuacion = $this->eficiencia ;
        return round($puntuacion);
    }

   
    //medible.php
    public function calcularPeso(): float{
        $densidadAgricultura = $this->material->getDensidadReal();
        $volumenAgricultura = $this->volumen($this->getfigura(), $this->tamanyo);
        $pesoAgricultura = $densidadAgricultura * $volumenAgricultura;
        
        $peso = $this->sumarPesos($this->cestas) +
                $this->sumarPesos($this->lanzas) +
                $this->ganaderia->calcularPeso() + 
                $pesoAgricultura;

        return round($peso, 2);
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
        $tamanyo['lado'] = self::TAMANYO_ELEMENTO;
        return $tamanyo;
    }

    public function __toString(): string{
        $html = parent::__toString();
        $html .= $this->calculosGeometricos(); 
        $html .= '<tr><th>Material</th><td>';
        $html .= $this->material;
        $html .= '<tr><th>Resistencia UV</th><td>' . htmlspecialchars($this->material->resistenciaUV) . ' sobre 100</td></tr>';
        $html .= '<tr><th>Dureza</th><td>' . htmlspecialchars($this->material->dureza) . ' sobre 100</td></tr>';
        $html .= '<tr><th>Tenacidad</th><td>' . htmlspecialchars($this->material->tenacidad) . ' sobre 100</td></tr>';
        $html .= '<tr><th>Densidad</th><td>' . htmlspecialchars($this->material->densidad) . ' sobre 100</td></tr>';
        $html .= '<tr><th>Coeficiente de Desgaste</th><td>' . htmlspecialchars($this->material->coeficienteDesgaste) . ' sobre 100</td></tr>';
        $html .= '</table>';
        $html .= '<tr><th>Ganaderia</th><td>';
        $html .= $this->ganaderia;
        $html .= '</td></tr>';
        for ($i=0; $i < count($this->cestas) ; $i++) { 
            $html .= "<tr><th>Cesta $i</th><td>".  $this->cestas[$i] . "</td><tr>";
        }
        for ($i=0; $i < count($this->lanzas) ; $i++) { 
            $html .= "<tr><th>Lanza $i</th><td>".  $this->lanzas[$i] . "</td><tr>";
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