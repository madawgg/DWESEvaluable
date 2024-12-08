<?php
require_once('Invento.php');
require_once('Material.php');


class PiedraAfilada extends Invento{
    private Material $material;
    private array $tamanyo = [
        'base'=> null, 
        'altura'=> null, 
        'longitud' => null];
    
    private float $filo;
    private float $eficiencia;
    private int $puntuacion;
    protected static array $inventosPrevios=[];

    public function __construct(string $nombre,Material $material, array $tamanyo, float $filo ){
        parent::__construct($nombre,1);
        
        $this->material = $material;
        $this->tamanyo = $tamanyo;
        $this->filo = $filo;
        $this->eficiencia  = $this->calcularEficiencia();
        $this->puntuacion = $this->calcularPuntuacion(); 
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
    public function getMaterial(): Material {
        return $this->material;
    }
    
    public function getTamanyo(): array{
        return $this->tamanyo;
    }
    
    public function getFilo(): float{
        return $this->filo;
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
    public function setTamanyo(array $tamanyo): void {
        $this->tamanyo = $tamanyo;
    }
    public function setFilo(float $filo): void{
        $this->filo = $filo;
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
                'beneficiosos' => ['dureza', 'tenacidad'],
                'perjudiciales' => ['densidad', 'fragilidad']
            ]);
        } catch (Exception $e) {
            return 0.00;
        }
    }
    
    public function calcularPuntuacion():int{
        $eficiencia = round($this->calcularEficiencia([]));
        $baseSize = $this->tamanyo['base'] ?? 1; 
        if ($this->filo <= 0.8 * $baseSize && $this->filo >= 0.2 * $baseSize) {
            return $eficiencia + 10;
        }
        return $eficiencia;
    }

    public function __toString(): string {
     
        $html = parent::__toString();
        $html .= '<tr><th>Eficiencia</th><td>' . htmlspecialchars($this->calcularEficiencia()) . '</td></tr>';
        $html .= '<tr><th>Puntos</th><td>' . htmlspecialchars($this->calcularPuntuacion()). '</td></tr>';
        $html .= '<tr><th>Longitud</th><td>' . htmlspecialchars($this->tamanyo['longitud']) . '</td></tr>';
        $html .= '<tr><th>Filo</th><td>' . htmlspecialchars($this->filo) . '</td></tr>';
        $html .= '<tr><th>Material</th><td>';
        $html .=  $this->material->__toString();
        $html .= '<tr><th>Dureza</th><td>' . htmlspecialchars($this->material->dureza) . '</td></tr>';
        $html .= '<tr><th>Tenacidad</th><td>' . htmlspecialchars($this->material->tenacidad) . '</td></tr>';
        $html .= '<tr><th>Fragilidad</th><td>' . htmlspecialchars($this->material->fragilidad) . '</td></tr>';
        $html .= '<tr><th>Densidad</th><td>' . htmlspecialchars($this->material->densidad) . '</td></tr>';
        $html .= '</td></tr>';
        $html .= '</table> </table>';
       
        return $html;
    }

    public static function probarInvento(array $argumentos=[]): void{
        $piedraAfilada = new PiedraAfilada(...$argumentos);
        echo $piedraAfilada->__toString();
    }
}
?>