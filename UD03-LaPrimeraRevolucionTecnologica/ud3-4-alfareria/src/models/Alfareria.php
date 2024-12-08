<?php
    
    include_once 'Invento.php';
    include_once 'Material.php';
    include_once 'Fuego.php';
    include_once 'Ceramica.php';
    include_once 'Refugio.php';
    include_once 'src/traits/CalculosGeometricos.php';
    include_once 'src/interfaces/Medible.php';
    
    class Alfareria extends Invento implements Medible{
        use CalculosGeometricos;
        private const FIGURA = 'Prisma Rectangular';
        private Material $material;
        private Fuego $fuego;
        private Ceramica $ceramica;
        private Refugio $refugio;  
        private int $puntuacion;
        private float $eficiencia;
        private array $tamanyo = [];
        protected static array $inventosPrevios=[];
    
        protected string $zonaCreacion;
        protected string $metodoCreacion;
        protected int $tiempoCreacion;
    
        public function __construct(string $nombre,  Material $material, Fuego $fuego, Ceramica $ceramica, Refugio $refugio, string $zonaCreacion = null, string $metodoCreacion = null){
            parent::__construct($nombre, 2);
            $this->material = $material;
            $this->fuego = $fuego;
            $this->ceramica = $ceramica;
            $this->refugio = $refugio;
            $this->tamanyo = $this->obtenerTamanyo();
            self::$inventosPrevios = [
                'fuego' => 1,
                'ceramica' => 1,
                'refugio' => 1
            ];
            $this->zonaCreacion = $zonaCreacion ?? 'pradera';
            $this->metodoCreacion = $metodoCreacion ?? 'tradicional';
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
        public function getFigura():string{
            return self::FIGURA;
        }
        public function getLongitud(): float {
            return $this->nombre;
        }
        public function getMaterial(): Material {
            return $this->material;
        }
        public function getAltura():int{
            return $this->altura;
        }
        public function getPuntuacion(): int {
            return $this->puntuacion;
        }
        public function getEficiencia(): float {
            return $this->eficiencia;
        }
    
        //setters
        public function setLongitud(float $longitud): void{
            $this->longitud = $longitud;
        }
        public function setMaterial(Material $material): void{
            $this->material = $material;
        }
        public function setPuntuacion(int $puntuacion): void{
            $this->puntuacion = $puntuacion;
        }
        public function setEficiencia(float $eficiencia): void{
            $this->eficiencia = $eficiencia;
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
            if($this->eficiencia <= 0){
                return 0;
            }
            $puntuacion = round($this->eficiencia);
            return $puntuacion;
        }

        public function calcularPeso(): float{
            $densidad = $this->material->densidad;
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
            $figura = $this->getFigura();
            $longitud = $this->tamanyo['longitud'];
            $altura = $this->tamanyo['altura'];
            $ancho = $this->tamanyo['ancho'];
            $grosor = $this->tamanyo['grosor'];
            $peso = $this->calcularPeso();
            $volumen = $this->calcularVolumen();
            $area = $this->calcularArea();
            $superficie = $this->calcularSuperficie();
    
            $html = parent::__toString();
            $html .= '<tr><th>Eficiencia</th><td>' . htmlspecialchars($this->calcularEficiencia()) . '</td></tr>';
            $html .= '<tr><th>Puntos</th><td>' . htmlspecialchars($this->calcularPuntuacion()) . '</td></tr>';
            
            $html .= '<tr><th>Zona de creación</th><td>' . htmlspecialchars($this->zonaCreacion) . '</td></tr>';
            $html .= '<tr><th>Metodo de creación</th><td>' . htmlspecialchars($this->metodoCreacion) . '</td></tr>';
            $html .= '<tr><th>Tiempo de inicio</th><td>' . htmlspecialchars($this->tiempoInicial->format('Y-m-d H:i:s')) . '</td></tr>';
            $html .= '<tr><th>Tiempo de creación</th><td>' . htmlspecialchars($this->tiempoCreacion) . '</td></tr>';
            $html .= '<tr><th>Tiempo de fin</th><td>' . htmlspecialchars($this->tiempoFinal->format('Y-m-d H:i:s')) . '</td></tr>';
            
            $html .= '<tr><th colspan=2>Inventos previos</th></tr>';
            foreach (self::$inventosPrevios as $invento => $valor) {
            $html .= '<tr><th>'.$invento.'</th><td>' . $valor . '</td></tr>';
            }

            $html .= '<tr><th>Figura</th><td>' . htmlspecialchars($figura) . '</td></tr>';
            $html .= '<tr><th>Tamaño (longitud x altura x ancho x grosor)</th><td>' . htmlspecialchars($longitud .' x '.$altura . ' x '. $ancho . ' x ' . $grosor) . '</td></tr>';
            $html .= '<tr><th>Peso</th><td>' . htmlspecialchars($peso) . '</td></tr>';
            $html .= '<tr><th>Volumen</th><td>' . htmlspecialchars($volumen) . '</td></tr>';
            $html .= '<tr><th>Área</th><td>' . htmlspecialchars($area) . '</td></tr>';
            $html .= '<tr><th>Superficie</th><td>' . htmlspecialchars($superficie) . '</td></tr>';
            $html .= '<tr><th>Material</th><td>';
            $html .=  $this->material->__toString();
            $html .= '<tr><th>Resistencia Temperatura</th><td>' . htmlspecialchars($this->material->resistenciaTemperatura) . '</td></tr>';
            $html .= '<tr><th>Coeficiente Desgaste</th><td>' . htmlspecialchars($this->material->coeficienteDesgaste) . '</td></tr>';
            $html .= '<tr><th>Fragilidad</th><td>' . htmlspecialchars($this->material->fragilidad) . '</td></tr>';
            $html .= '<tr><th>Inflamabilidad</th><td>' . htmlspecialchars($this->material->inflamabilidad) . '</td></tr>';
            $html .= '</table>';
            $html .= '<tr><th>Fuego</th><td>';
            $html .= $this->fuego->__toString();
            $html .= '<tr><th>Cerámica</th><td>';
            $html .= $this->ceramica->__toString();
            $html .= '</td><tr><th>Refugio</th><td>';
            $html .= $this->refugio->__toString();
            $html .= '</td></table>';
            
            return $html;
        }
    
        public static function probarInvento(array $argumentos = []): void{
            $alfareria = new Alfareria(...$argumentos);
    
            echo $alfareria->__toString();
        }
    
    }
    
  
?>