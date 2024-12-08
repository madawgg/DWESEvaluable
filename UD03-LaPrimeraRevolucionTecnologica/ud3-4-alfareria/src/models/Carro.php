<?php

include_once 'Material.php';
include_once 'Invento.php';
include_once 'Rueda.php';
include_once 'Cesta.php';
include_once 'Cuerda.php';
include_once 'Hacha.php';
include_once 'src/traits/CalculosGeometricos.php';
include_once 'src/interfaces/Medible.php';

class Carro extends Invento implements Medible{
    use CalculosGeometricos;
    private const FIGURA = 'Cilindro';
    private Material $material;
    private Rueda $rueda1;
    private Rueda $rueda2;
    private array $ruedas;
    private Cesta $cesta;
    private Cuerda $cuerda;
    private Hacha $hacha;
    private float $eficienciaEnsamblaje;
    private float $eficiencia;
    private int $puntuacion;
    private array $tamanyo = [];
    protected static array $inventosPrevios = [];
   
    protected string $zonaCreacion;
    protected string $metodoCreacion;
    protected int $tiempoCreacion;


    public function __construct(string $nombre, Material $material, Cuerda $cuerda, Cesta $cesta, array $ruedas , Hacha $hacha, string $zonaCreacion = null, string $metodoCreacion = null){
        parent::__construct($nombre, 2);
        $this->material = $material;
        $this->rueda1 = $ruedas[0];
        $this->rueda2 = $ruedas[1];
        $this->ruedas = [$this->rueda1, $this->rueda2];
        $this->cesta = $cesta;
        $this->cuerda = $cuerda;
        $this->hacha = $hacha;
        $this->tamanyo = $cesta->getTamanyo();
        self::$inventosPrevios = ['Cuerda'=> 1, 'Cesta'=> 1, 'Rueda'=> 2, 'Hacha'=> 1];

        $this->zonaCreacion = $zonaCreacion ?? 'pradera';
        $this->metodoCreacion = $metodoCreacion ?? 'tradicional';
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
            $eficienciaRueda1 = $this->rueda1->getEficiencia();
            $eficienciaRueda2 = $this->rueda2->getEficiencia();
            $eficienciaHacha = $this->hacha->getEficiencia();
            $eficienciaCuerda = $this->cuerda->getEficiencia();
            $eficienciaCesta = $this->cesta->getEficiencia();
            $eficiencia = round(($eficienciaEnsamblaje + $eficienciaRueda1 + $eficienciaRueda2 + $eficienciaHacha + $eficienciaCuerda + $eficienciaCesta)/6,2);
            $this->eficienciaEnsamblaje = $eficienciaEnsamblaje;
            $this->eficiencia = $eficiencia;
    
            return $eficiencia;

        }catch (Exception $e){
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
                $tiempoFinal = 60 * $this->nivel * 0.75;
                $this->eficiencia -= 10;
                break;

            case 'detallado':
                $tiempoFinal = 60 * $this->nivel * 1.5;
                $this->eficiencia += 10;
                break;
            default:
                echo 'El metodo de creacion no existe';
                return 0;
                break;
        }
        return round($tiempoFinal);
    }

    public function calcularPuntuacion(): int{
        if($this->eficiencia <= 0){
            return 0;
        }
        return round($this->eficiencia);
    }

    public function calcularPeso(): float{
        $densidad = $this->material->densidad;
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
        $tamanyo = $this->tamanyo;
        return $tamanyo;
    }

    public function __toString(): string{
        $figura = $this->getFigura();
        $radio = $this->tamanyo['radio'];
        $altura = $this->tamanyo['altura'];
        $grosor = $this->tamanyo['grosor'];
        $peso = $this->calcularPeso();
        $volumen = $this->calcularVolumen();
        $area = $this->calcularArea();
        $superficie = $this->calcularSuperficie();

        $html = parent::__toString();
        $html .= '<tr><th>Eficiencia</th><td>' . htmlspecialchars($this->calcularEficiencia()) . '</td></tr>';
        $html .= '<tr><th>Puntos</th><td>' . htmlspecialchars($this->calcularPuntuacion()). '</td></tr>';
        
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
        $html .= '<tr><th>Tamaño (radio x altura x grosor)</th><td>' . htmlspecialchars($radio . ' x ' . $altura . ' x ' . $grosor) . '</td></tr>';
        $html .= '<tr><th>Peso</th><td>' . htmlspecialchars($peso) . '</td></tr>';
        $html .= '<tr><th>Volumen</th><td>' . htmlspecialchars($volumen) . '</td></tr>';
        $html .= '<tr><th>Área</th><td>' . htmlspecialchars($area) . '</td></tr>';
        $html .= '<tr><th>Superficie</th><td>' . htmlspecialchars($superficie) . '</td></tr>';
        $html .= '<tr><th>Material</th><td>';
        $html .=  $this->material->__toString();
        $html .= '<tr><th>Dureza</th><td>' . htmlspecialchars($this->material->dureza) . '</td></tr>';
        $html .= '<tr><th>Flexibilidad</th><td>' . htmlspecialchars($this->material->flexibilidad) . '</td></tr>';
        $html .= '<tr><th>Resistencia Tracción</th><td>' . htmlspecialchars($this->material->resistenciaTraccion) . '</td></tr>';
        $html .= '<tr><th>Densidad</th><td>' . htmlspecialchars($this->material->densidad) . '</td></tr>';
        $html .= '<tr><th>Coeficiente de Desgaste</th><td>' . htmlspecialchars($this->material->coeficienteDesgaste) . '</td></tr>';
        $html .= '</td></tr>';
        $html .= '</table>';
        $html .= '</td><tr><th>cuerda</th><td>';
        $html .= $this->cuerda->__toString();
        $html .= '</td><tr><th>Cesta</th><td>';
        $html .= $this->cesta->__toString();
        $html .= '</td><tr><th>Rueda 1</th><td>';
        $html .= $this->rueda1->__toString();
        $html .= '</td><tr><th>Rueda 2</th><td>';
        $html .= $this->rueda2->__toString();
        $html .= '</td><tr><th>Hacha</th><td>';
        $html .= $this->hacha->__toString();
        $html .= '</td></table>';
          
        return $html;
    }

    public static function probarInvento(array $argumentos=[]): void{
        $carro = new Carro(...$argumentos);
        echo $carro->__toString();
    }
}

?>