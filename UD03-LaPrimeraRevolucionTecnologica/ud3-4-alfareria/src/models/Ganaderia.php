<?php

include_once 'Invento.php';
include_once 'Refugio.php';
include_once 'Cuerda.php';
include_once 'PiedraAfilada.php';
include_once 'Trampa.php';
include_once 'src/traits/CalculosGeometricos.php';
include_once 'src/interfaces/Medible.php';

class Ganaderia extends Invento implements Medible{
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

    protected string $zonaCreacion;
    protected string $metodoCreacion;
    protected int $tiempoCreacion;

    public function __construct(string $nombre, array $cuerdas, array $piedrasAfiladas, array $trampas, array $refugios, int $cantidadAnimales, string $zonaCreacion = null, string $metodoCreacion = null){
        parent::__construct($nombre, 2);
        
        $this->cuerdas = $cuerdas;
        $this->piedrasAfiladas = $piedrasAfiladas;
        $this->trampas = $trampas;
        $this->refugios = $refugios;
        $this->cantidadAnimales = $cantidadAnimales;
        $this->tamanyo = $this->conjuntarTamanyo();
        self::$inventosPrevios = [
            'cuerdas' => 10,
            'piedras afiladas' => 10,
            'trampas' => 5,
            'refugios' => 2
        ];
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
    private function conjuntarTamanyo(): array{
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

    private function sumarPesos(array $inventos): float{
        $sumaPeso = 0;
        foreach ($inventos as $invento) {
            $sumaPeso += $invento->calcularPeso();
        }

        return $sumaPeso;
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
        if($this->eficiencia <= 0 ){
            return 0;
        }

        $puntuacion = $this->eficiencia + $this->cantidadAnimales;
        return round($puntuacion);
    }

   
    //medible.php
    public function calcularPeso(): float{
        
        $peso = $this->sumarPesos($this->cuerdas) +
                $this->sumarPesos($this->piedrasAfiladas) +
                $this->sumarPesos($this->trampas) +
                $this->sumarPesos($this->refugios);

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
        $longitud = $this->tamanyo['longitud'];
        $ancho = $this->tamanyo['ancho'];
        $altura = $this->tamanyo['altura'];
        $grosor = $this->tamanyo['grosor'];
        $peso = $this->calcularPeso();
        $volumen = $this->calcularVolumen();
        $area = $this->calcularArea();
        $superficie = $this->calcularSuperficie();

        $html = parent::__toString();
        $html .= '<tr><th>Eficiencia</th><td>' . htmlspecialchars($this->eficiencia) . '</td></tr>';
        $html .= '<tr><th>Puntos</th><td>' . htmlspecialchars($this->puntuacion). '</td></tr>';
        
        $html .= '<tr><th>Zona de creación</th><td>' . htmlspecialchars($this->zonaCreacion) . '</td></tr>';
        $html .= '<tr><th>Metodo de creación</th><td>' . htmlspecialchars($this->metodoCreacion) . '</td></tr>';
        $html .= '<tr><th>Tiempo de inicio</th><td>' . htmlspecialchars($this->tiempoInicial->format('Y-m-d H:i:s')) . '</td></tr>';
        $html .= '<tr><th>Tiempo de creación</th><td>' . htmlspecialchars($this->tiempoCreacion) . '</td></tr>';
        $html .= '<tr><th>Tiempo de fin</th><td>' . htmlspecialchars($this->tiempoFinal->format('Y-m-d H:i:s')) . '</td></tr>';

        $html .= '<tr><th colspan=2>Inventos previos</th></tr>';
        foreach (self::$inventosPrevios as $invento => $valor) {
            $html .= '<tr><th>'.$invento.'</th><td>' . $valor . '</td></tr>';
        }
        
        $html .= '<tr><th>Cantidad de Animales</th><td>' . htmlspecialchars($this->cantidadAnimales) . '</td></tr>';
        $html .= '<tr><th>Figura</th><td>' . htmlspecialchars($figura) . '</td></tr>';
        $html .= '<tr><th>Tamaño (longitud x ancho x altura x grosor)</th><td>' . htmlspecialchars($longitud . ' x '. $ancho . ' x ' . $altura . ' x ' . $grosor) . '</td></tr>';
        $html .= '<tr><th>Peso</th><td>' . htmlspecialchars($peso) . '</td></tr>';
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