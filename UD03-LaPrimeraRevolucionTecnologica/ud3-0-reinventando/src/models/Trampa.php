<?php

include_once 'Invento.php';
include_once 'Material.php';
include_once 'Cuerda.php';
include_once 'ArcoFlecha.php';
include_once 'Cesta.php';

class Trampa extends Invento{

    private Cuerda $cuerda;
    private Cesta $cesta;
    private ArcoFlecha $arcoFlecha; 
    private float $visibilidad;
    private float $eficiencia;
    private int $puntuacion;

    public function __construct(string $nombre,Cuerda $cuerda, Cesta $cesta, ArcoFlecha $arcoFlecha, float $visibilidad){
        parent::__construct($nombre, 1);

        $this->cuerda = $cuerda;
        $this->cesta = $cesta;
        $this->arcoFlecha = $arcoFlecha;
        $this->visibilidad = $visibilidad;
        $this->eficiencia = $this->calcularEficiencia();
        $this->puntuacion = $this->calcularPuntuacion();

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
        
        return round($puntuacion);
    }

    public function __toString(): string{
        $html = parent::__toString();
        $html .= '<tr><th>Eficiencia</th><td>' . htmlspecialchars($this->eficiencia) . '</td></tr>';
        $html .= '<tr><th>Puntos</th><td>' . htmlspecialchars($this->puntuacion) . '</td></tr>';
        $html .= '<tr><th>Visibilidad</th><td>' . htmlspecialchars($this->visibilidad) . '</td></tr>';
        $html .= '</td><tr><th>Cuerda</th><td>';
        $html .= $this->cuerda->__toString(); 
        $html .= '</td><tr><th>Cesta</th><td>';
        $html .= $this->cesta->__toString(); 
        $html .= '</td><tr><th>Arco y flecha</th><td>';
        $html .= $this->arcoFlecha->__toString();
        $html.= '</td></table></table>';

        return $html;
    }

    public static function probarInvento(array $argumentos= []): void{
        $trampa = new Trampa(...$argumentos);
        echo $trampa->__toString();
    }

}
?>