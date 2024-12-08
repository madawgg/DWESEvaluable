<?php

include_once 'Invento.php';
include_once 'Material.php';
include_once 'src/traits/CalculosGeometricos.php';


class Cesta extends Invento{
    use CalculosGeometricos;
    private const FIGURA = 'Cilindro';
    private Material $material;
    private float $eficiencia;
    private int $puntuacion;
    private array $tamanyo = [];
    protected static array $inventosPrevios=[];

    public function __construct(string $nombre, Material $material, array $tamanyo ){
        parent::__construct($nombre,1);
        $this->material = $material;
        $this->tamanyo = $tamanyo;
        $this->eficiencia = $this->calcularEficiencia();
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
    public function getTamanyo(): array{
        return $this->tamanyo;
    }
    public function getEficiencia(): float{
        return $this->eficiencia;
    }
    public function getPuntuacion(): intº{
        return $this->putuacion;
    }

    //setters
    public function setMaterial(Material $material): void{
        $this->material = $material;
    }
    public function setEficiencia(float $eficiencia): void{
        $this->eficiencia = $eficiencia;
    }
    public function setPuntuacion(int $puntuacion): void{
        $this->puntuacion = $puntuacion;
    }

    public function calcularEficiencia(): float {
        try {
            return $this->material->calcularEficiencia([
                'beneficiosos' => ['flexibilidad'],
                'perjudiciales' => ['densidad', 'fragilidad']
            ]);
        } catch (Exception $e) {
            return 0.00;
        }
    }

    public function calcularPuntuacion(): int{
        $puntuacion = round($this->eficiencia);

        return $puntuacion;
    }

    public function __toString():string{
        $figura = $this->getFigura();
        $radio = $this->tamanyo['radio'];
        $altura = $this->tamanyo['altura'];
        $grosor = $this->tamanyo['grosor'];
        $volumen = $this->volumen($figura, $this->tamanyo);
        $area = $this->area($figura, $this->tamanyo);
        $superficie = $this->superficie($figura, $this->tamanyo);

        $html = parent::__toString();
        $html .= '<tr><th>Eficiencia</th><td>' . htmlspecialchars($this->calcularEficiencia()) . '</td></tr>';
        $html .= '<tr><th>Puntos</th><td>' . htmlspecialchars($this->calcularPuntuacion()). '</td></tr>';
        $html .= '<tr><th>Figura</th><td>' . htmlspecialchars($figura) . '</td></tr>';
        $html .= '<tr><th>Tamaño (radio x altura x grosor)</th><td>' . htmlspecialchars($radio . ' x ' . $altura . ' x ' . $grosor) . '</td></tr>';
        $html .= '<tr><th>Volumen</th><td>' . htmlspecialchars($volumen) . '</td></tr>';
        $html .= '<tr><th>Área</th><td>' . htmlspecialchars($area) . '</td></tr>';
        $html .= '<tr><th>Superficie</th><td>' . htmlspecialchars($superficie) . '</td></tr>';
        $html .= '<tr><th>Material</th><td>';
        $html .=  $this->material->__toString();
        $html .= '<tr><th>Flexibilidad</th><td>' . htmlspecialchars($this->material->flexibilidad) . '</td></tr>';
        $html .= '<tr><th>Densidad</th><td>' . htmlspecialchars($this->material->densidad) . '</td></tr>';
        $html .= '<tr><th>Fragilidad</th><td>' . htmlspecialchars($this->material->fragilidad) . '</td></tr>';
        $html .= '</td></tr>';
        $html .= '</table> </table>';
       
        return $html;
    }

    public static function probarInvento(array $argumentos = []): void{
        $cesta = new Cesta(...$argumentos);
        echo $cesta->__toString();
    }

}

?>