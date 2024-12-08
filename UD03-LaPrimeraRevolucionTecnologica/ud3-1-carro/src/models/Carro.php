<?php

include_once 'Material.php';
include_once 'Invento.php';
include_once 'Rueda.php';
include_once 'Cesta.php';
include_once 'Cuerda.php';
include_once 'Hacha.php';

class Carro extends Invento{
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
    protected static array $inventosPrevios = [];

    public function __construct(string $nombre, Material $material, Cuerda $cuerda, Cesta $cesta, array $ruedas , Hacha $hacha){
        parent::__construct($nombre, 2);
        $this->material = $material;
        $this->rueda1 = $ruedas[0];
        $this->rueda2 = $ruedas[1];
        $this->ruedas = [$this->rueda1, $this->rueda2];
        $this->cesta = $cesta;
        $this->cuerda = $cuerda;
        $this->hacha = $hacha;
        $this->eficiencia = $this->calcularEficiencia();
        $this->puntuacion = $this->calcularPuntuacion(); 
        self::$inventosPrevios = ['Cuerda'=> 1, 'Cesta'=> 1, 'Rueda'=> 2, 'Hacha'=> 1];
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
            echo $eficienciaEnsamblaje;
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

    public function calcularPuntuacion(): int{
        return round($this->eficiencia);
    }

    public function __toString(): string{
        
        $html = parent::__toString();
        $html .= '<tr><th>Eficiencia</th><td>' . htmlspecialchars($this->calcularEficiencia()) . '</td></tr>';
        $html .= '<tr><th>Puntos</th><td>' . htmlspecialchars($this->calcularPuntuacion()). '</td></tr>';
        $html .= '<tr><th colspan=2>Inventos previos</th></tr>';
        foreach (self::$inventosPrevios as $invento => $valor) {
            $html .= '<tr><th>'.$invento.'</th><td>' . $valor . '</td></tr>';
        }
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