<?php

include_once 'Invento.php';
include_once 'Material.php';
include_once 'src/traits/CalculosGeometricos.php';
include_once 'src/interfaces/Medible.php';

class Rueda extends Invento implements Medible{
    use CalculosGeometricos;
    private const FIGURA = 'Cilindro';    
    private Material $material;
    private float $eficiencia;
    private int $puntuacion;
    private array $tamanyo = [];
    protected static array $inventosPrevios = [];

    public function __construct(string $nombre,  Material $material, array $tamanyo) {
        parent::__construct($nombre, 1);
        $this->material= $material;
        $this->tamanyo = $tamanyo;
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
    public function getRadio(){
        return $this->radio;
    }
    public function getGrosor(){
        return $this->grosor;
    }
    public function getMaterial(): Material{
        return $this->material;
    }
    public function getEficiencia(): float{
        return $this->eficiencia;
    }
    public function getPuntuacion(): int{
        return $this->puntuacion;
    }

    //setters
    public function setRadio($radio): void{
        $this->radio = $radio;
    }
    public function setGrosor($grosor): void{
        $this->grosor = $grosor;
    }
    public function setMaterial(Material $material): void{
        $this->material = $material;
    }
    public function setEficiencia(float $eficiencia): void{
        $this->eficiencia = $eficiencia;
    }
    public function setPuntuacion(int $puntuacion): void{
        $this->puntuacion = $puntuacion;
    }

    public function calcularEficiencia(): float{
        try {
            return $this->material->calcularEficiencia([
                'beneficiosos' => ['resistenciaCompresion', 'coeficienteDesgaste'],
                'perjudiciales' => ['densidad', 'coeficienteFriccion']
            ]);
        } catch (Exception $e) {
            return 0.00;
        }
    }
    public function calcularPuntuacion(): int {
        $puntuacion = round($this->eficiencia);
        return $puntuacion;
    }
    //medible.php
    public function calcularPeso(): float{
        $densidad = $this->material->densidad;
        $volumen = $this->calcularVolumen();
        $peso = $densidad * $volumen;
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
        $peso = $this->calcularPeso();
        $volumen = $this->calcularVolumen();
        $area = $this->calcularArea();
        $superficie = $this->calcularSuperficie();

        $html = parent::__toString();
        $html .= '<tr><th>Eficiencia</th><td>' . htmlspecialchars($this->eficiencia) . '</td></tr>';
        $html .= '<tr><th>Puntos</th><td>' . htmlspecialchars($this->puntuacion). '</td></tr>';
        $html .= '<tr><th>Figura</th><td>' . htmlspecialchars($figura) . '</td></tr>';
        $html .= '<tr><th>Tamaño (radio x altura)</th><td>' . htmlspecialchars($radio . ' x ' . $altura) . '</td></tr>';
        $html .= '<tr><th>Peso</th><td>' . htmlspecialchars($peso) . '</td></tr>';
        $html .= '<tr><th>Volumen</th><td>' . htmlspecialchars($volumen) . '</td></tr>';
        $html .= '<tr><th>Área</th><td>' . htmlspecialchars($area) . '</td></tr>';
        $html .= '<tr><th>Superficie</th><td>' . htmlspecialchars($superficie) . '</td></tr>';
        $html .= '<tr><th>Material</th><td>';
        $html .=  $this->material->__toString();
        $html .= '<tr><th>Resistencia a la compresión</th><td>' . htmlspecialchars($this->material->resistenciaCompresion) . '</td></tr>';
        $html .= '<tr><th>Resistencia al desgaste</th><td>' . htmlspecialchars($this->material->coeficienteDesgaste) . '</td></tr>';
        $html .= '<tr><th>Densidad</th><td>' . htmlspecialchars($this->material->densidad) . '</td></tr>';
        $html .= '<tr><th>Coeficiente de fricción</th><td>' . htmlspecialchars($this->material->coeficienteFriccion) . '</td></tr>';
        $html .= '</td></tr>';
        $html .= '</table> </table>';
        
        return $html;
    }

    public static function probarInvento(array $argumentos = []): void{
        $rueda = new Rueda(...$argumentos);
        echo $rueda->__toString();
    }

}

?>