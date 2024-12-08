<?php

include_once 'Invento.php';
include_once 'Material.php';
include_once 'Cuerda.php';
include_once 'Lanza.php';
include_once 'src/traits/CalculosGeometricos.php';
include_once 'src/interfaces/Medible.php';

class ArcoFlecha extends Invento implements Medible{
    use CalculosGeometricos;
    private const FIGURA = 'Cilindro';
    private Material $material;
    private Lanza $flecha;
    private Cuerda $cuerda;
    private string $tecnica;
    private float $eficiencia;
    private int $puntuacion;
    private float $eficienciaArco;
    
    private array $tamanyo=[];
    protected static array $inventosPrevios= [];
    
    public function __construct(string $nombre, Material $materialArco, Lanza $flecha, Cuerda $cuerda, string $tecnica, array $tamanyo){
        parent::__construct($nombre, 1);
        $this->material = $materialArco;
        $this->flecha = $flecha;
        $this->cuerda = $cuerda;
        $this->tecnica = $tecnica;
        $this->tamanyo = $tamanyo;
        
        $this->tamanyo['altura'] = $cuerda->getAltura();
        self::$inventosPrevios = [
            'Lanza' => 1,
            'Cuerda' => 1
        ];

        $this->eficiencia = $this->calcularEficiencia();
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

        //getters
        public function getMaterial(): Material{
            return $this->material;
        }
        public function getFlecha(): Lanza{
            return $this->flecha;
        }
        public function getCuerda(): Cuerda{
            return $this->cuerda;
        }
        public function getTecnica(): string{
            return $this->tecnica;
        }
        public function getEficiencia(): float{
            return $this->eficiencia;
        }
        public function getPuntuacion(): int{
            return $this->puntuacion;
        }

        //setters
        public function setMaterial(Material $material): void{
            $this->material = $material;
        }
        public function setFlecha(Lanza $flecha): void{
            $this->flecha = $flecha;
        }
        public function setCuerda(Cuerda $cuerda): void{
            $this->cuerda = $cuerda;
        }
        public function setTecnica(string $tecnica): void{
            $this->tecnica = $tecnica;
        }
        public function setEficiencia(float $eficiencia): void{
            $this->eficiencia = $eficiencia;
        }
        public function setPuntuacion(int $puntuacion): void{
            $this->putuacion = $putuacion;
        }

        public function calcularEficiencia(): float{
            try {
                
                $eficienciaArco = $this->material->calcularEficiencia([
                    'beneficiosos' => ['dureza', 'flexibilidad', 'resistenciaTraccion'],
                    'perjudiciales' => ['densidad', 'coeficienteDesgaste']
                ]);
                $this->eficienciaArco = $eficienciaArco;
                $eficienciaFlecha = $this->flecha->getEficiencia();
                $eficienciaCuerda = $this->cuerda->getEficiencia();

                $eficiencia = $this->eficiencia = round(($eficienciaArco + $eficienciaFlecha + $eficienciaCuerda) / 3, 2);
                return $eficiencia;

            } catch (Exception $e) {
                return 0.00;
            }
        }

        public function calcularPuntuacion(): int{
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
            $densidadArco = $this->material->densidad;
            $volumenArco = $this->calcularVolumen();
            $pesoArco = $densidadArco * $volumenArco;

            $pesoFlecha = $this->flecha->calcularPeso();
            $pesoCuerda = $this->cuerda->calcularPeso();

            $peso = $pesoArco + $pesoFlecha + $pesoCuerda;

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

        public function __toString(){
            $figura = $this->getFigura();
            $radio = $this->tamanyo['radio'];
            $altura = $this->tamanyo['altura'];
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
            
            $html .= '<tr><th>Técnica</th><td>' . htmlspecialchars($this->tecnica) . '</td></tr>';
            $html .= '<tr><th>Figura</th><td>' . htmlspecialchars($figura) . '</td></tr>';
            $html .= '<tr><th>Tamaño (radio x altura)</th><td>' . htmlspecialchars($radio . ' x ' . $altura) . '</td></tr>';
            $html .= '<tr><th>Peso</th><td>' . htmlspecialchars($peso) . '</td></tr>';
            $html .= '<tr><th>Volumen</th><td>' . htmlspecialchars($volumen) . '</td></tr>';
            $html .= '<tr><th>Área</th><td>' . htmlspecialchars($area) . '</td></tr>';
            $html .= '<tr><th>Superficie</th><td>' . htmlspecialchars($superficie) . '</td></tr>';   
            $html .= '<tr><th>Material Arco</th><td>';
            $html .=  $this->material->__toString();
            $html .= '<tr><th>Dureza</th><td>' . htmlspecialchars($this->material->dureza) . '</td></tr>';
            $html .= '<tr><th>Flexibilidad</th><td>' . htmlspecialchars($this->material->flexibilidad) . '</td></tr>';
            $html .= '<tr><th>Resistencia tracción</th><td>' . htmlspecialchars($this->material->resistenciaTraccion) . '</td></tr>';
            $html .= '<tr><th>Densidad</th><td>' . htmlspecialchars($this->material->densidad) . '</td></tr>';
            $html .= '<tr><th>Coeficiente desgaste</th><td>' . htmlspecialchars($this->material->coeficienteDesgaste) . '</td></tr>';
            $html .= '<tr><th>Eficiencia Arco</th><td>' . htmlspecialchars($this->eficienciaArco) . '</td></tr>';
            $html .= ' </table>';
            $html .= '</td><tr><th>Flecha</th><td>';
            $html .= $this->flecha->__toString();
            $html .= '</td><tr><th>Cuerda</th><td>';

            $html .= $this->cuerda->__toString();
            $html.= '</td></table>';

            return $html;
        }
    
        public static function probarInvento(array $argumentos = []):void{
            $arcoFlecha = new ArcoFlecha(...$argumentos);
            echo $arcoFlecha->__toString();
        }
}

?>