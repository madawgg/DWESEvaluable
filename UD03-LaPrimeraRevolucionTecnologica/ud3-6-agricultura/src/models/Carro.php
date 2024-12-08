<?php

include_once 'Material.php';
include_once 'Invento.php';
include_once 'Rueda.php';
include_once 'Cesta.php';
include_once 'Cuerda.php';
include_once 'Hacha.php';
include_once 'Almacenamiento.php';
include_once 'src/traits/CalculosGeometricos.php';
include_once 'src/interfaces/Medible.php';

class Carro extends Almacenamiento implements Medible{
    use CalculosGeometricos;
    protected const FIGURA = 'Cilindro';
    protected const NIVEL = 2;
    protected const TAMANYO_ELEMENTO = 100;
    protected Rueda $rueda1;
    protected Rueda $rueda2;
    protected float $eficienciaEnsamblaje;
    protected float $eficiencia;
    protected int $puntuacion;
    protected array $tamanyo;
    protected static array $inventosPrevios = [
        'Cuerda'=> 1, 
        'Cesta'=> 1, 
        'Rueda'=> 2, 
        'Hacha'=> 1
    ];
    
    protected int $tiempoCreacion;


    public function __construct(
        protected Material $material, 
        protected Cuerda $cuerda, 
        protected Cesta $cesta, 
        protected array $ruedas, 
        protected Hacha $hacha, 
        string $nombre,  
        protected string $zonaCreacion = 'pradera', 
        protected string $metodoCreacion = 'tradicional',
        int $numeroElementos= 0
    ){
        $this->tamanyo = $this->obtenerTamanyo();
        parent::__construct($nombre, $this->tamanyo, $numeroElementos);
        $this->rueda1 = $ruedas[0];
        $this->rueda2 = $ruedas[1];
    
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
            
        try{
            $eficienciaEnsamblaje = $this->material->calcularEficiencia([
                'beneficiosos' => ['resistenciaCompresion', 'resistenciaTraccion', 'tenacidad'],
                'perjudiciales' => ['densidad', 'coeficienteFriccion']
            ]);
            $eficienciaRueda1 = $this->rueda1->eficiencia;
            $eficienciaRueda2 = $this->rueda2->eficiencia;
            $eficienciaHacha = $this->hacha->eficiencia;
            $eficienciaCuerda = $this->cuerda->eficiencia;
            $eficienciaCesta = $this->cesta->eficiencia;
            $eficiencia = round(($eficienciaEnsamblaje + $eficienciaRueda1 + $eficienciaRueda2 + $eficienciaHacha + $eficienciaCuerda + $eficienciaCesta)/6,2);
            $this->eficienciaEnsamblaje = $eficienciaEnsamblaje;
            $this->eficiencia = $eficiencia;
    
            return $eficiencia;

        }catch (Exception $e){
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
        if($this->eficiencia <= 0){
            return 0;
        }
        return round($this->eficiencia);
    }

    public function calcularPeso(): float{
        $densidad = $this->material->getDensidadReal();
        $volumen = $this->calcularVolumen();
        $pesoCarro = $densidad * $volumen;
        $pesoCuerda = $this->cuerda->calcularPeso();
        $pesoCesta = $this->cesta->calcularPeso();
        $pesoRueda1 = $this->rueda1->calcularPeso();
        $pesoRueda2 = $this->rueda2->calcularPeso();
        $pesoHacha = $this->hacha->calcularPeso();
        $peso = $pesoCarro + $pesoCuerda + $pesoCesta + $pesoRueda1 + $pesoRueda2  + $pesoHacha;
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

    public function __toString(): string{
       

        $html = parent::__toString();
        $html .= $this->calculosGeometricos();
        $html .= '<tr><th>Material</th><td>';
        $html .=  $this->material;
        $html .= '<tr><th>Dureza</th><td>' . htmlspecialchars($this->material->dureza) . '</td></tr>';
        $html .= '<tr><th>Flexibilidad</th><td>' . htmlspecialchars($this->material->flexibilidad) . '</td></tr>';
        $html .= '<tr><th>Resistencia Tracción</th><td>' . htmlspecialchars($this->material->resistenciaTraccion) . '</td></tr>';
        $html .= '<tr><th>Densidad</th><td>' . htmlspecialchars($this->material->densidad) . '</td></tr>';
        $html .= '<tr><th>Coeficiente de Desgaste</th><td>' . htmlspecialchars($this->material->coeficienteDesgaste) . '</td></tr>';
        $html .= '</td></tr>';
        $html .= '</table>';
        $html .= '</td><tr><th>cuerda</th><td>';
        $html .= $this->cuerda;
        $html .= '</td><tr><th>Cesta</th><td>';
        $html .= $this->cesta;
        $html .= '</td><tr><th>Rueda 1</th><td>';
        $html .= $this->rueda1;
        $html .= '</td><tr><th>Rueda 2</th><td>';
        $html .= $this->rueda2;
        $html .= '</td><tr><th>Hacha</th><td>';
        $html .= $this->hacha;
        $html .= '</td></table>';
          
        return $html;
    }

    public static function probarInvento(array $argumentos=[]): void{
        $carro = new Carro(...$argumentos);
        echo $carro->__toString();
    }
}

?>