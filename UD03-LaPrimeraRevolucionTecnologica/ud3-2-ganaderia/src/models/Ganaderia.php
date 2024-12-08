<?php

include_once 'Invento.php';
include_once 'Refugio.php';
include_once 'Cuerda.php';
include_once 'PiedraAfilada.php';
include_once 'Trampa.php';
include_once 'src/traits/CalculosGeometricos.php';

class Ganaderia extends Invento{
    use CalculosGeometricos;
    private const FIGURA = 'Prisma Rectangular';
    private array $cuerdas = [];
    private array $piedrasAfiladas = [];
    private array $trampas = [];
    private array $refugios = [];
    private int $cantidadAnimales;
    private float $eficiencia;
    private int $puntuacion;
    private array $tamanyo = [];
    protected static array $inventosPrevios = [];

    public function __construct(string $nombre, array $cuerdas, array $piedrasAfiladas, array $trampas, array $refugios, int $cantidadAnimales ){
        parent::__construct($nombre, 2);
        
        $this->cuerdas = $cuerdas;
        $this->piedrasAfiladas = $piedrasAfiladas;
        $this->trampas = $trampas;
        $this->refugios = $refugios;
        $this->cantidadAnimales = $cantidadAnimales;
        $this->eficiencia = $this->calcularEficiencia();
        $this->puntuacion = $this->calcularPuntuacion();
        $this->tamanyo = $this->obtenerTamanyo();
        self::$inventosPrevios = [
            'cuerdas' => 10,
            'piedras afiladas' => 10,
            'trampas' => 5,
            'refugios' => 2
        ];
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
    private function obtenerTamanyo(): array{
        $longitudPromedio = 0;
        $anchoSuma = 0;
        $alturaSuma = 0;
        $grosorSuma = 0;
        $contador = 0;
    
        foreach ($this->refugios as $refugio) {
            $contador++;
            $refugioTamanyo = $refugio->getTamanyo();
            
            $longitudPromedio += $refugioTamanyo['longitud'];
            $anchoSuma += $refugioTamanyo['ancho'];
            $alturaSuma += $refugioTamanyo['altura'];
            $grosorSuma += $refugioTamanyo['grosor'];
        }
        
        $anchoPromedio = $anchoSuma / $contador ;
        $alturaPromedio =  $alturaSuma / $contador;
        $grosorPromedio =  $grosorSuma / $contador;
    
        return [
            'longitud' => $longitudPromedio,
            'ancho' => $anchoPromedio,
            'altura' => $alturaPromedio,
            'grosor' => $grosorPromedio
        ];

    }
    private function sumarEficiencias(array $inventos): float{
        $sumaEficiencia = 0;
        foreach ($inventos as $invento) {
            $sumaEficiencia += $invento->getEficiencia();
        }

        return $sumaEficiencia;
    }
    
    public function calcularEficiencia(): float{
        
        $sumaEficiencias = $this->sumarEficiencias($this->cuerdas) + 
                           $this->sumarEficiencias($this->piedrasAfiladas) + 
                           $this->sumarEficiencias($this->trampas) + 
                           $this->sumarEficiencias($this->refugios);

        
        $sumaCantidad = count($this->cuerdas) + 
                        count($this->piedrasAfiladas) + 
                        count($this->trampas) + 
                        count($this->refugios);
        
       
        $eficiencia = $sumaEficiencias / $sumaCantidad;

        return round($eficiencia, 2);
    }
    public function calcularPuntuacion(): int{
        $puntuacion = $this->eficiencia + $this->cantidadAnimales;
        return round($puntuacion);
    }

    public function __toString(): string{
        $figura = $this->getFigura();
        $longitud = $this->tamanyo['longitud'];
        $ancho = $this->tamanyo['ancho'];
        $altura = $this->tamanyo['altura'];
        $grosor = $this->tamanyo['grosor'];
        $volumen = $this->volumen($figura, $this->tamanyo);
        $area = $this->area($figura, $this->tamanyo);
        $superficie = $this->superficie($figura, $this->tamanyo);

        $html = parent::__toString();
        $html .= '<tr><th>Eficiencia</th><td>' . htmlspecialchars($this->eficiencia) . '</td></tr>';
        $html .= '<tr><th>Puntos</th><td>' . htmlspecialchars($this->puntuacion). '</td></tr>';
        
        $html .= '<tr><th colspan=2>Inventos previos</th></tr>';
        foreach (self::$inventosPrevios as $invento => $valor) {
            $html .= '<tr><th>'.$invento.'</th><td>' . $valor . '</td></tr>';
        }
        
        $html .= '<tr><th>Cantidad de Animales</th><td>' . htmlspecialchars($this->cantidadAnimales) . '</td></tr>';
        $html .= '<tr><th>Figura</th><td>' . htmlspecialchars($figura) . '</td></tr>';
        $html .= '<tr><th>Tamaño (longitud x ancho x altura x grosor)</th><td>' . htmlspecialchars($longitud . ' x '. $ancho . ' x ' . $altura . ' x ' . $grosor) . '</td></tr>';
        $html .= '<tr><th>Volumen</th><td>' . htmlspecialchars($volumen) . '</td></tr>';
        $html .= '<tr><th>Área</th><td>' . htmlspecialchars($area) . '</td></tr>';
        $html .= '<tr><th>Superficie</th><td>' . htmlspecialchars($superficie) . '</td></tr>';
        $html .= '</td></tr>';
        for ($i=0; $i < count($this->cuerdas) ; $i++) { 
            $html .= "<tr><th>Cuerda $i</th><td>".  $this->cuerdas[$i]->__toString() . "</td><tr>";
        }
        for ($i=0; $i < count($this->piedrasAfiladas) ; $i++) { 
            $html .= "<tr><th>Piedra Afilada $i</th><td>".  $this->piedrasAfiladas[$i]->__toString() . "</td><tr>";
        }
        for ($i=0; $i < count($this->trampas) ; $i++) { 
            $html .= "<tr><th>Trampa $i</th><td>".  $this->trampas[$i]->__toString() . "</td><tr>";
        }
        for ($i=0; $i < count($this->refugios) ; $i++) { 
            $html .= "<tr><th>Refugio $i</th><td>".  $this->refugios[$i]->__toString() . "</td><tr>";
        }
        $html .= '</table></td></table>';

        return $html;
    }
    public static function probarInvento(array $argumentos=[]): void{
        $ganaderia = new Ganaderia(...$argumentos);
        echo $ganaderia->__toString();
    }
 }


?>