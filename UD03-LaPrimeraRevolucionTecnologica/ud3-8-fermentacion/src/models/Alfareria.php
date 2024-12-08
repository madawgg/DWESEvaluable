<?php
    
    include_once 'Invento.php';
    include_once 'Material.php';
    include_once 'Fuego.php';
    include_once 'Ceramica.php';
    include_once 'Refugio.php';
    include_once 'src/traits/CalculosGeometricos.php';
    include_once 'src/interfaces/Medible.php';
    
    class Alfareria extends Almacenamiento implements Medible{
        use CalculosGeometricos;
        protected const FIGURA = 'Prisma Rectangular';
        protected const NIVEL = 2;
        protected const TAMANYO_ELEMENTO = 50;   
        protected array $tamanyo;
        protected static array $inventosPrevios = [
            'fuego' => 1,
            'ceramica' => 1,
            'refugio' => 1
        ];

        protected static array $campos = [

            ['nombre' => 'material', 'tipo' => 'select', 'variable' => 'materiales'],
            ['nombre' => 'fuego', 'tipo' => 'select', 'variable' => 'Fuego'],
            ['nombre' => 'ceramica', 'tipo' => 'select', 'variable' => 'Ceramica'],
            ['nombre' => 'refugio', 'tipo' => 'select', 'variable' => 'Refugio'],
            ['nombre' => 'nombre', 'tipo' => 'text'],
            ['nombre' => 'zona', 'tipo' => 'select', 'variable' => 'zonas'],
            ['nombre' => 'tecnica', 'tipo' => 'select', 'variable' => 'tecnicas'],
            ['nombre' => 'numeroElementos', 'tipo' => 'number']
        ];
    
        protected int $tiempoCreacion;
    
        public function __construct( 
            protected Material $material,
            protected Fuego $fuego,
            protected Ceramica $ceramica,
            protected Refugio $refugio,  
            string $nombre, 
            protected string $zonaCreacion = 'pradera',
            protected string $metodoCreacion = 'tradicional',
            int $numeroElementos = 0
        ){
            $this->tamanyo = $this->obtenerTamanyo();
            parent::__construct($nombre, $this->tamanyo, $numeroElementos );
            $this->eficiencia = $this->calcularEficiencia();
            $this->tiempoCreacion = $this->calcularTiempoCreacion();
            $this->tiempoFinal = $this->calcularTiempoTotal($this->tiempoCreacion);
            $this->puntuacion = $this->calcularPuntuacion();
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
        public static function getInventosPrevios(): array {
            return self::$inventosPrevios;
        }

        public static function getCampos(): array {
            return self::$campos;
        }
        
        public function getFigura():string{
            return self::FIGURA;
        }
        
        public function calcularEficiencia(): float{
            try {
    
                $eficienciaMaterial =  $this->material->calcularEficiencia([
                    'beneficiosos' => ['resistenciaTemperatura', 'coeficienteDesgaste'],
                    'perjudiciales' => ['fragilidad', 'inflamabilidad']
                ]);
                $eficienciaFuego = $this->fuego->eficiencia;
                $eficienciaCeramica = $this->ceramica->eficiencia;
                $eficienciaRefugio = $this->refugio->eficiencia;
                $eficiencia = round(($eficienciaMaterial+ $eficienciaFuego + $eficienciaCeramica + $eficienciaRefugio)/4, 2);
                
                return $eficiencia;

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

            $tiemposMateriales[] = $this->material->calcularTiempoCreacion($this->zonaCreacion, $tiempoBase);  
            $tiempoFinal = max($tiemposMateriales);
            return round($tiempoFinal);
        }
    
        public function calcularPuntuacion(): int{
            if($this->eficiencia <= 0){
                return 0;
            }
            $puntuacion = round($this->eficiencia);
            return $puntuacion;
        }

        public function calcularPeso(): float{
            $densidad = $this->material->getDensidadReal();
            $volumen = $this->calcularVolumen();
            $pesoMaterial = $densidad * $volumen;

            $pesoCeramica = $this->ceramica->calcularPeso();
            $pesoRefugio  = $this->refugio->calcularPeso();

            $peso = $pesoMaterial + $pesoCeramica + $pesoRefugio;

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
            $tamanyo = $this->refugio->obtenerTamanyo();
            return $tamanyo;
        }
    
        public function __toString(): string{

            $html = parent::__toString();
            $html .= $this->calculosGeometricos();
            $html .= '<tr><th>Material</th><td>';
            $html .=  $this->material;
            $html .= '<tr><th>Resistencia Temperatura</th><td>' . htmlspecialchars($this->material->resistenciaTemperatura) . ' sobre 100</td></tr>';
            $html .= '<tr><th>Coeficiente Desgaste</th><td>' . htmlspecialchars($this->material->coeficienteDesgaste) . ' sobre 100</td></tr>';
            $html .= '<tr><th>Fragilidad</th><td>' . htmlspecialchars($this->material->fragilidad) . ' sobre 100</td></tr>';
            $html .= '<tr><th>Inflamabilidad</th><td>' . htmlspecialchars($this->material->inflamabilidad) . ' sobre 100</td></tr>';
            $html .= '</table>';
            $html .= '<tr><th>Fuego</th><td>';
            $html .= $this->fuego;
            $html .= '<tr><th>Cerámica</th><td>';
            $html .= $this->ceramica;
            $html .= '</td><tr><th>Refugio</th><td>';
            $html .= $this->refugio;
            $html .= '</td></table>';
            
            return $html;
        }
    
        public static function probarInvento(array $argumentos = []): void{
            $alfareria = new Alfareria(...$argumentos);
    
            echo $alfareria->__toString();
        }
    
    }
    
  
?>