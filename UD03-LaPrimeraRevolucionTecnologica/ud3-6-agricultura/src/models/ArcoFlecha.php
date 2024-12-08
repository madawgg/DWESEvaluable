<?php

include_once 'Invento.php';
include_once 'Material.php';
include_once 'Cuerda.php';
include_once 'Lanza.php';
include_once 'src/traits/CalculosGeometricos.php';
include_once 'src/interfaces/Medible.php';

class ArcoFlecha extends Invento implements Medible{
    use CalculosGeometricos;
    protected const FIGURA = 'Cilindro';
    protected const NIVEL = 1;
    protected float $eficienciaArco;
    protected static array $inventosPrevios= [
        'Lanza' => 1,
        'Cuerda' => 1
    ];
    protected int $tiempoCreacion;
    
    public function __construct(
        protected Material $material,
        protected Lanza $lanza, 
        protected Cuerda $cuerda,
        string $nombre,  
        protected string $tecnica,
        protected array $tamanyo,
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
        public function getFigura(){
            return self::FIGURA;
        }

        public static function getInventosPrevios(): array {
            return self::$inventosPrevios;
        }

        public function __get( $atributo){
            if(property_exists($this, $atributo)){
                return $this->$atributo;
            }else{
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
            try {
                
                $eficienciaArco = $this->material->calcularEficiencia([
                    'beneficiosos' => ['dureza', 'flexibilidad', 'resistenciaTraccion'],
                    'perjudiciales' => ['densidad', 'coeficienteDesgaste']
                ]);
                $this->eficienciaArco = $eficienciaArco;
                $eficienciaFlecha = $this->lanza->eficiencia;
                $eficienciaCuerda = $this->cuerda->eficiencia;

                $eficiencia = $this->eficiencia = round(($eficienciaArco + $eficienciaFlecha + $eficienciaCuerda) / 3, 2);
                
                return $eficiencia;

            } catch (Exception $e) {
                return 0.00;
            }
        }

        public function calcularTiempoCreacion(): int {
            $tiemposMateriales = [];

            $tiempoBase = 60 * $this->nivel; 
            //Cambiar despues del switch, cambiar tiempo final por tiempo base
            $tiemposMateriales[] = $this->material->calcularTiempoCreacion($this->zonaCreacion, $tiempoBase);
            
            $tiempoFinal = max($tiemposMateriales);
        
            switch ($this->metodoCreacion) {
                case 'tradicional':
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
            $puntuacion = match($this->tecnica){
                'tallado' => $this->eficiencia + 15,
                'trenzado' => $this->eficiencia + 10,
                'secado' =>  $this->eficiencia + 5,
                default => round($this->eficiencia)
            };
            return round($puntuacion);
        }

         //Medible.php
        public function calcularPeso(): float{
            $densidadArco = $this->material->getDensidadReal();
            $volumenArco = $this->calcularVolumen();
            $pesoArco = $densidadArco * $volumenArco;

            $pesoFlecha = $this->lanza->calcularPeso();
            $pesoCuerda = $this->cuerda->calcularPeso();

            $peso = $pesoArco + $pesoFlecha + $pesoCuerda;

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
            $tamanyo['altura'] = $this->cuerda->altura;
            return $tamanyo;
        }

        public function __toString(){
            $figura = $this->getFigura();
            $radio = $this->tamanyo['radio'] / 100;
            $altura = $this->tamanyo['altura'] / 100;

            $html = parent::__toString();
            $html .= '<tr><th>Técnica</th><td>' . htmlspecialchars($this->tecnica) . '</td></tr>';
            $html .= '<tr><th>Figura</th><td>' . htmlspecialchars($figura) . '</td></tr>';
            $html .= '<tr><th>Tamaño</th><td>' . htmlspecialchars("Radio: $radio m, Altura: $altura m") . '</td></tr>';
            $html .= $this->calculosGeometricos();
            $html .= '<tr><th>Material Arco</th><td>';
            $html .=  $this->material;
            $html .= '<tr><th>Dureza</th><td>' . htmlspecialchars($this->material->dureza) . ' sobre 100</td></tr>';
            $html .= '<tr><th>Flexibilidad</th><td>' . htmlspecialchars($this->material->flexibilidad) . ' sobre 100</td></tr>';
            $html .= '<tr><th>Resistencia tracción</th><td>' . htmlspecialchars($this->material->resistenciaTraccion) . ' sobre 100</td></tr>';
            $html .= '<tr><th>Densidad</th><td>' . htmlspecialchars($this->material->densidad) . ' sobre 100</td></tr>';
            $html .= '<tr><th>Coeficiente desgaste</th><td>' . htmlspecialchars($this->material->coeficienteDesgaste) . ' sobre 100</td></tr>';
            $html .= '<tr><th>Eficiencia Arco</th><td>' . htmlspecialchars($this->eficienciaArco) . ' %</td></tr>';
            $html .= ' </table>';
            $html .= '</td><tr><th>Flecha</th><td>';
            $html .= $this->lanza;
            $html .= '</td><tr><th>Cuerda</th><td>';
            $html .= $this->cuerda;
            $html.= '</td></table>';

            return $html;
        }
    
        public static function probarInvento(array $argumentos = []):void{
            $arcoFlecha = new ArcoFlecha(...$argumentos);
            echo $arcoFlecha->__toString();
        }
}

?>