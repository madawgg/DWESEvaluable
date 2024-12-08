<?php

include_once 'Invento.php';
include_once 'Refugio.php';
include_once 'Cuerda.php';
include_once 'PiedraAfilada.php';
include_once 'Trampa.php';
include_once 'src/traits/CalculosGeometricos.php';
include_once 'src/interfaces/Medible.php';

class Ganaderia extends Almacenamiento implements Medible{
    use CalculosGeometricos;
    protected const FIGURA = 'Prisma Rectangular';
    protected const NIVEL = 2;
    protected const TAMANYO_ELEMENTO = 350;
    protected array $tamanyo;
    protected static array $inventosPrevios = [
        'cuerdas' => 10,
        'piedras afiladas' => 10,
        'trampas' => 5,
        'refugios' => 2
    ];
    public static array $campos = [
        ['nombre' => 'cuerda', 'tipo' => 'select', 'variable' => 'Cuerda'],
        ['nombre' => 'piedraAfilada', 'tipo' => 'select', 'variable' => 'PiedraAfilada'],
        ['nombre' => 'trampa', 'tipo' => 'select', 'variable' => 'Trampa'],
        ['nombre' => 'refugio', 'tipo' => 'select', 'variable' => 'Refugio'],
        ['nombre' => 'nombre', 'tipo' => 'text'],
        ['nombre' => 'zona', 'tipo' => 'select', 'variable' => 'zonas'],
        ['nombre' => 'tecnica', 'tipo' => 'select', 'variable' => 'tecnicas'],
        ['nombre' => 'numeroElementos', 'tipo' => 'number']
    ];
    protected int $tiempoCreacion;

    public function __construct(
        protected array $cuerdas,
        protected array $piedrasAfiladas,
        protected array $trampas,
        protected array $refugios,
        string $nombre, 
        protected string $zonaCreacion = 'pradera',
        protected string $metodoCreacion = 'tradicional',
        int $numeroElementos = 0
    ){
        $this->tamanyo = $this->obtenerTamanyo();
        parent::__construct($nombre, $this->tamanyo, $numeroElementos);  
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
    
    public static function getCampos():array {
        return self::$campos;
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
        $tiempoBase = 60 * $this->nivel; 
        $tiempoFinal = $tiempoBase;

        switch ($this->metodoCreacion) {
            case 'tradicional':
                $tiempoFinal;
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
        if($this->eficiencia <= 0 ){
            return 0;
        }
        //arreglar para agricultura
        $puntuacion = $this->eficiencia ;
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
       return round($volumen,2);
    }

    public function calcularArea(): float{
        $area = $this->area($this->getFigura(), $this->tamanyo);
        return round($area,2);
    }

    public function calcularSuperficie(): float{
        $superficie = $this->superficie($this->getFigura(), $this->tamanyo);
        return round($superficie,2);
    }

    public function obtenerTamanyo(): array{
        $longitudPromedio = 0;
        $anchoSuma = 0;
        $alturaSuma = 0;
        $grosorSuma = 0;
        $contador = 0;
    
        foreach ($this->refugios as $refugio) {
            $contador++;
            $refugioTamanyo = $refugio->tamanyo;
            
            $longitudPromedio += $refugioTamanyo['longitud'];
            $anchoSuma += $refugioTamanyo['ancho'];
            $alturaSuma += $refugioTamanyo['altura'];
            $grosorSuma += $refugioTamanyo['grosor'];
        }
        
        $anchoPromedio = $anchoSuma / $contador ;
        $alturaPromedio =  $alturaSuma / $contador;
        $grosorPromedio =  $grosorSuma / $contador;
    
        $tamanyo = [
            'longitud' => $longitudPromedio,
            'ancho' => $anchoPromedio,
            'altura' => $alturaPromedio,
            'grosor' => $grosorPromedio
        ];
        return $tamanyo;
    }

    public function __toString(): string{
       

        $html = parent::__toString();
        $html .= $this->calculosGeometricos(); 
        $html .= '</td></tr>';
        for ($i=0; $i < count($this->cuerdas) ; $i++) { 
            $html .= "<tr><th>Cuerda $i</th><td>".  $this->cuerdas[$i] . "</td><tr>";
        }
        for ($i=0; $i < count($this->piedrasAfiladas) ; $i++) { 
            $html .= "<tr><th>Piedra Afilada $i</th><td>".  $this->piedrasAfiladas[$i] . "</td><tr>";
        }
        for ($i=0; $i < count($this->trampas) ; $i++) { 
            $html .= "<tr><th>Trampa $i</th><td>".  $this->trampas[$i] . "</td><tr>";
        }
        for ($i=0; $i < count($this->refugios) ; $i++) { 
            $html .= "<tr><th>Refugio $i</th><td>".  $this->refugios[$i] . "</td><tr>";
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