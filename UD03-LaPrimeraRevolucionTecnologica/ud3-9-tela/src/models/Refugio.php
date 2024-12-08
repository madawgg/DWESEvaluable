<?php

include_once 'Material.php';
include_once 'src/traits/CalculosGeometricos.php';
include_once 'src/interfaces/Medible.php';
include_once 'Almacenamiento.php';

class Refugio extends Almacenamiento implements Medible{
    use CalculosGeometricos;
    protected const FIGURA = 'Prisma Rectangular';
    protected const TAMANYO_ELEMENTO = 200;
    protected const NIVEL = 1;
    protected float $eficienciaTecho;
    protected float $eficienciaParedes;
    protected float $eficienciaSuelo;
    protected int $superficie;
    protected float $capacidadBase = 0;
    protected array $tamanyoPrismas = [];
    protected float $volumenPrismas = 0;
    protected static array $inventosPrevios=[];
    public static array $campos = [
        ['nombre' => 'materialTecho', 'tipo' => 'select', 'variable' => 'materiales'],
        ['nombre' => 'materialParedes', 'tipo' => 'select', 'variable' => 'materiales'],
        ['nombre' => 'materialSuelo', 'tipo' => 'select', 'variable' => 'materiales'],
        ['nombre' => 'nombre', 'tipo' => 'text'],
        ['nombre' => 'ancho', 'tipo' => 'number'],
        ['nombre' => 'altura', 'tipo' => 'number'],
        ['nombre' => 'longitud', 'tipo' => 'number'],
        ['nombre' => 'grosor', 'tipo' => 'number'],
        ['nombre' => 'zona', 'tipo' => 'select', 'variable' => 'zonas'],
        ['nombre' => 'tecnica', 'tipo' => 'select', 'variable' => 'tecnicas'],
        ['nombre' => 'numeroElementos', 'tipo' => 'number']
    ];

    protected int $tiempoCreacion;

    public function __construct(
        protected Material $materialTecho,
        protected Material $materialParedes,
        protected Material $materialSuelo,
        string $nombre, 
        protected array $tamanyo,
        protected string $zonaCreacion = 'pradera',
        protected string $metodoCreacion = 'tradicional',
        protected int $numeroElementos = 0
    ){
        parent::__construct($nombre, $tamanyo, $numeroElementos);
        $this->tamanyo = $this->obtenerTamanyo();
        $this->capacidad = Almacenamiento::calcularCapacidad(); 
        $this->volumenPrismas = $this->calcularVolumenPrismas();
        $this->eficiencia = $this->calcularEficiencia();
        $this->superficie = $this->superficie($this->getFigura(), $tamanyo);
        $this->tiempoCreacion = $this->calcularTiempoCreacion();
        $this->tiempoFinal = $this->calcularTiempoTotal($this->tiempoCreacion);
        $this->puntuacion = $this->calcularPuntuacion();
        
    }
    
    public static function getInventosPrevios(): array {
        return self::$inventosPrevios;
    }
    public static function getCampos(): array{
        return self::$campos;
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

    // Getters
    public function getFigura(): string{
        return self::FIGURA;
    }
   
    private function calcularPromedioResistencias(Material $material): float {
        $resistencias = [
            $material->resistenciaCompresion,
            $material->resistenciaHumedad,
            $material->resistenciaTemperatura,
            $material->resistenciaViento
        ];
        return array_sum($resistencias) / count($resistencias);
    }
    

    public function calcularEficiencia(): float{
        try {
                
            $eficienciaTecho = $this->materialTecho->calcularEficiencia([
                'beneficiosos' => ['resistenciaCompresion', 'resistenciaHumedad', 'resistenciaTemperatura','resistenciaViento'],
                'perjudiciales' => ['densidad', 'fragilidad']
            ]);
            $eficienciaParedes = $this->materialParedes->calcularEficiencia([
                'beneficiosos' => ['resistenciaCompresion', 'resistenciaHumedad', 'resistenciaTemperatura','resistenciaViento'],
                'perjudiciales' => ['densidad', 'fragilidad']
            ]);
            $eficienciaSuelo = $this->materialSuelo->calcularEficiencia([
                'beneficiosos' => ['resistenciaCompresion', 'resistenciaHumedad', 'resistenciaTemperatura','resistenciaViento'],
                'perjudiciales' => ['densidad', 'fragilidad']
            ]);
            
            $this->eficienciaTecho = $eficienciaTecho;
            $this->eficienciaParedes = $eficienciaParedes;
            $this->eficienciaSuelo = $eficienciaSuelo;
            
            $eficienciaRefugio = round(($eficienciaTecho + $eficienciaParedes + $eficienciaSuelo) / 3,2);
            
            return $eficienciaRefugio;

        } catch (Exception $e) {
            return 0.00;
        }
    }

    public function calcularTiempoCreacion(): int {
        $tiemposMateriales = [];

        $tiempoBase = 60 * $this->nivel; 
        
        switch ($this->metodoCreacion) {
            case 'tradicional':
                break; 
            case 'rapido':
                $tiempoBase *= 0.75;
                $this->eficiencia -= 10;
                break;
            case 'detallado':
                $tiempoBase *= 1.5;
                $this->eficiencia += 10;
                break;
            default:
                echo 'El método de creación no existe';
                return 0;
        }
        $tiemposMateriales[] = $this->materialParedes->calcularTiempoCreacion($this->zonaCreacion, $tiempoBase);
        $tiemposMateriales[] = $this->materialTecho->calcularTiempoCreacion($this->zonaCreacion, $tiempoBase);
        $tiemposMateriales[] = $this->materialSuelo->calcularTiempoCreacion($this->zonaCreacion, $tiempoBase);
        
        $tiempoFinal = max($tiemposMateriales);

        return round($tiempoFinal);
    }

    public function calcularPuntuacion(): int{
        
        if($this->porcentajeLleno >= 75){
            $puntuacion = $this->eficiencia * 1.2;
            return round($puntuacion);

        }elseif($this->porcentajeLleno >= 50){
            $puntuacion = $this->eficiencia*1.1;
            return round($puntuacion);
        }
        
        $puntuacion = $this->eficiencia;
       
        return round($puntuacion);

    }
    //medible.php
    public function calcularPeso(): float{
        $densidadParedes = $this->materialParedes->getDensidadReal() * 4;
        $densidadTecho = $this->materialTecho->getDensidadReal();
        $densidadSuelo = $this->materialSuelo->getDensidadReal();
        $volumenPrismas = $this->volumenPrismas;

        $pesoParedes = $densidadParedes * $volumenPrismas;
        $pesoSuelo = $densidadSuelo * $volumenPrismas;
        $pesoTecho = $densidadTecho * $volumenPrismas;
        $peso = $pesoParedes + $pesoSuelo + $pesoTecho;
        return $peso;
    }
    public function calcularVolumenPrismas():float{
        $tamanyoPrismas = $this->tamanyo;
        $tamanyoPrismas['altura'] = $tamanyoPrismas['grosor'];
        $volumenPrismas = $this->volumen($this->getFigura(), $tamanyoPrismas);
        return $volumenPrismas;
    }
    public function calcularVolumen(): float{
        $volumen = $this->volumen($this->getFigura(), $this->tamanyo);
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
        $tamanyo['lado'] = self::TAMANYO_ELEMENTO;
        return $tamanyo;
    }

    public function __toString(): string{
        
        $html = parent::__toString();
        $html .= $this->calculosGeometricos();
        $html .= '<tr><th>Material Techo</th><td>';
        $html .=  $this->materialTecho;
        $html .= '<tr><th>ResistenciaCompresión</th><td>' . htmlspecialchars($this->materialTecho->resistenciaCompresion) . ' sobre 100</td></tr>';
        $html .= '<tr><th>ResistenciaHumedad</th><td>' . htmlspecialchars($this->materialTecho->resistenciaHumedad) . ' sobre 100</td></tr>';
        $html .= '<tr><th>ResistenciaTemperatura</th><td>' . htmlspecialchars($this->materialTecho->resistenciaTemperatura) . ' sobre 100</td></tr>';
        $html .= '<tr><th>ResistenciaViento</th><td>' . htmlspecialchars($this->materialTecho->resistenciaViento) . ' sobre 100</td></tr>';
        $html .= '<tr><th>Densidad</th><td>' . htmlspecialchars($this->materialTecho->densidad) . ' sobre 100</td></tr>';
        $html .= '<tr><th>Fragilidad</th><td>' . htmlspecialchars($this->materialTecho->fragilidad) . ' sobre 100</td></tr>';
        $html .= '<tr><th>Eficiencia Techo</th><td>' . htmlspecialchars($this->eficienciaTecho) . ' %</td></tr>';
        $html .= '</td></tr>';
        $html .= '</table>';
        $html .= '<tr><th>Material Paredes</th><td>';
        $html .=  $this->materialParedes;
        $html .= '<tr><th>ResistenciaCompresión</th><td>' . htmlspecialchars($this->materialParedes->resistenciaCompresion) . ' sobre 100</td></tr>';
        $html .= '<tr><th>ResistenciaHumedad</th><td>' . htmlspecialchars($this->materialParedes->resistenciaHumedad) . ' sobre 100</td></tr>';
        $html .= '<tr><th>ResistenciaTemperatura</th><td>' . htmlspecialchars($this->materialParedes->resistenciaTemperatura) . ' sobre 100</td></tr>';
        $html .= '<tr><th>ResistenciaViento</th><td>' . htmlspecialchars($this->materialParedes->resistenciaViento) . ' sobre 100</td></tr>';
        $html .= '<tr><th>Densidad</th><td>' . htmlspecialchars($this->materialParedes->densidad) . ' sobre 100</td></tr>';
        $html .= '<tr><th>Fragilidad</th><td>' . htmlspecialchars($this->materialParedes->fragilidad) . ' sobre 100</td></tr>';
        $html .= '<tr><th>Eficiencia Paredes</th><td>' . htmlspecialchars($this->eficienciaParedes) . ' %</td></tr>';
        $html .= '</td></tr>';
        $html .= '</table>';
        $html .= '<tr><th>Material Suelo</th><td>';
        $html .=  $this->materialSuelo;
        $html .= '<tr><th>ResistenciaCompresión</th><td>' . htmlspecialchars($this->materialSuelo->resistenciaCompresion) . ' sobre 100</td></tr>';
        $html .= '<tr><th>ResistenciaHumedad</th><td>' . htmlspecialchars($this->materialSuelo->resistenciaHumedad) . ' sobre 100</td></tr>';
        $html .= '<tr><th>ResistenciaTemperatura</th><td>' . htmlspecialchars($this->materialSuelo->resistenciaTemperatura) . ' sobre 100</td></tr>';
        $html .= '<tr><th>ResistenciaViento</th><td>' . htmlspecialchars($this->materialSuelo->resistenciaViento) . ' sobre 100</td></tr>';
        $html .= '<tr><th>Densidad</th><td>' . htmlspecialchars($this->materialSuelo->densidad) . ' sobre 100</td></tr>';
        $html .= '<tr><th>Fragilidad</th><td>' . htmlspecialchars($this->materialSuelo->fragilidad) . ' sobre 100</td></tr>';
        $html .= '<tr><th>Eficiencia Suelo</th><td>' . htmlspecialchars($this->eficienciaSuelo) . ' %</td></tr>';
        $html .= '</table> </table>';
        
        return $html;
    }

    public static function probarInvento(array $argumentos = []): void{
        $refugio = new Refugio(...$argumentos);
        echo $refugio->__toString();
    }

}

?>