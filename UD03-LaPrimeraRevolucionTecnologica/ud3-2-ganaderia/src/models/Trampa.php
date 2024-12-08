<?php

include_once 'Invento.php';
include_once 'Material.php';
include_once 'Cuerda.php';
include_once 'ArcoFlecha.php';
include_once 'Cesta.php';
include_once 'src/traits/CalculosGeometricos.php';

class Trampa extends Invento{
    use CalculosGeometricos;
    private const FIGURA = 'Cilindro';
    private Cuerda $cuerda;
    private Cesta $cesta;
    private ArcoFlecha $arcoFlecha; 
    private float $visibilidad;
    private float $eficiencia;
    private int $puntuacion;
    private array $tamanyo;
    protected static array $inventosPrevios = [];

    public function __construct(string $nombre,Cuerda $cuerda, Cesta $cesta, ArcoFlecha $arcoFlecha, float $visibilidad){
        parent::__construct($nombre, 1);

        $this->cuerda = $cuerda;
        $this->cesta = $cesta;
        $this->arcoFlecha = $arcoFlecha;
        $this->visibilidad = $visibilidad;
        self::$inventosPrevios = [
            'Cuerda' => 1,
            'Cesta' => 1,
            'ArcoFlecha' =>1
        ];
        $this->tamanyo = $cesta->getTamanyo();
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
    public function getCuerda(): Cuerda{
        return $this->cuerda;
    }
    public function getCesta(): Cesta{
        return $this->cesta;
    }
    public function getArcoFlecha(): ArcoFlecha{
        return $this->arcoFlecha;
    }
    public function getVisibilidad(): float{
        return $this->visibilidad;
    }
    public function getEficiencia(): float{
        return $this->eficiencia;
    }
    public function getPuntuacion(): int{
        return $this->puntuacion;
    }
    
    //setters
    public function setCuerda(Cuerda $cuerda): void{
        $this->cuerda = $cuerda;
    }
    public function setCesta(Cesta $cesta): void{
        $this->cesta = $cesta;
    }
    public function setArcoFlecha(ArcoFlecha $arcoFlecha): void{
        $this->arcoFlecha = $arcoFlecha;
    }
    public function setVisibilidad(float $visibilidad): void{
        $this->visibilidad = $visibilidad;
    }
    public function setEficiencia(float $eficiencia): void{
        $this->eficiencia = $eficiencia;
    }
    public function setPuntuacion(int $puntuacion): void{
        $this->puntuacion = $puntuacion;
    }
    public function calcularEficiencia(): float{
        try{
            $eficienciaCuerda = $this->cuerda->getEficiencia();
            $eficienciaCesta = $this->cesta->getEficiencia();
            $eficienciaArcoFlecha = $this->arcoFlecha->getEficiencia();

            $eficienciaTrampa = round(($eficienciaCuerda+$eficienciaCesta+$eficienciaArcoFlecha)/3, 2);
            return $eficienciaTrampa;

        } catch (Exception $e) {
            return 0.00;
        }
    }
    public function calcularPuntuacion(): int{
        $eficienciaRedondeada = round($this->eficiencia);
        $restaVisibilidad = 1 - $this->visibilidad;
        
        $puntuacion = $eficienciaRedondeada * $restaVisibilidad;
        
        return floor($puntuacion);
    }

    public function __toString(): string{
        $figura = $this->getFigura();
        $radio = $this->tamanyo['radio'];
        $altura = $this->tamanyo['altura'];
        $grosor = $this->tamanyo['grosor'];
        $volumen = $this->volumen($figura, $this->tamanyo);
        $area = $this->area($figura, $this->tamanyo);
        $superficie = $this->superficie($figura, $this->tamanyo);

        $html = parent::__toString();
        $html .= '<tr><th>Eficiencia</th><td>' . htmlspecialchars($this->eficiencia) . '</td></tr>';
        $html .= '<tr><th>Puntos</th><td>' . htmlspecialchars($this->puntuacion) . '</td></tr>';
        $html .= '<tr><th colspan=2>Inventos previos</th></tr>';
        foreach (self::$inventosPrevios as $invento => $valor) {
            $html .= '<tr><th>'.$invento.'</th><td>' . $valor . '</td></tr>';
        }
        $html .= '<tr><th>Visibilidad</th><td>' . htmlspecialchars($this->visibilidad) . '</td></tr>';
        $html .= '<tr><th>Figura</th><td>' . htmlspecialchars($figura) . '</td></tr>';
        $html .= '<tr><th>Tamaño (radio x altura x grosor)</th><td>' . htmlspecialchars($radio . ' x ' . $altura . ' x ' . $grosor) . '</td></tr>';
        $html .= '<tr><th>Volumen</th><td>' . htmlspecialchars($volumen) . '</td></tr>';
        $html .= '<tr><th>Área</th><td>' . htmlspecialchars($area) . '</td></tr>';
        $html .= '<tr><th>Superficie</th><td>' . htmlspecialchars($superficie) . '</td></tr>';
        $html .= '</td><tr><th>Cuerda</th><td>';
        $html .= $this->cuerda->__toString(); 
        $html .= '</td><tr><th>Cesta</th><td>';
        $html .= $this->cesta->__toString(); 
        $html .= '</td><tr><th>Arco y flecha</th><td>';
        $html .= $this->arcoFlecha->__toString();
        $html.= '</td></table>';

        return $html;
    }

    public static function probarInvento(array $argumentos= []): void{
        $trampa = new Trampa(...$argumentos);
        echo $trampa->__toString();
    }

}
?>