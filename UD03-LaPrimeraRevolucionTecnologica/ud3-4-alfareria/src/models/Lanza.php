<?php
    include_once 'Invento.php';
    include_once 'Material.php';
    include_once 'PiedraAfilada.php';
    include_once 'Cuerda.php';
    require_once 'src/traits/CalculosGeometricos.php';
    include_once 'src/interfaces/Medible.php';

    class Lanza extends Invento implements Medible{
        use CalculosGeometricos;
        private const FIGURA = 'Cilindro';
        private array $tamanyo = [];
        private float $longitud;
        private Material $material;
        private PiedraAfilada $piedraAfilada;
        private Cuerda $cuerda;
        private float $eficienciaBaston;
        private int $puntuacion;
        private float $eficiencia;
        protected static array $inventosPrevios=[];
        protected string $zonaCreacion;
        protected string $metodoCreacion;
        protected int $tiempoCreacion;

        public function __construct(string $nombre, Material $material, PiedraAfilada $piedraAfilada, Cuerda $cuerda, array $tamanyo, string $zonaCreacion = null, string $metodoCreacion = null){
            parent::__construct($nombre, 1);
            $this->material = $material;
            $this->piedraAfilada = $piedraAfilada;
            $this->cuerda = $cuerda;
            $this->tamanyo = $tamanyo;
            self::$inventosPrevios = [
                'PiedraAfilada' =>1,
                'Cuerda' => 1
            ];
            $this->zonaCreacion = $zonaCreacion ?? 'pradera';
            $this->metodoCreacion = $metodoCreacion ?? 'tradicional';
            $this->eficiencia = $this->calcularEficiencia();
            $this->tiempoCreacion = $this->calcularTiempoCreacion();
            $this->tiempoFinal = $this->calcularTiempoTotal($this->tiempoCreacion);
            $this->puntuacion = $this->calcularPuntuacion();
           
        }


        public function getFigura():string{
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
        public function getLongitud(): float {
            return $this->longitud;
        }
        public function getMaterial(): Material {
            return $this->material;
        }
        public function getPiedraAfilada(): PiedraAfilada {
            return $this->piedraAfilada;
        }
        public function getCuerda(): Cuerda {
            return $this->cuerda;
        }
        public function getEficienciaBaston(): float {
            return $this->eficienciaBaston;
        }
        public function getEficiencia(): float{
            return $this->eficiencia;
        }
        public function getPuntuacion(): int {
            return $this->puntuacion;
        }


        //setters
        public function setLongitud(float $longitud): void{
            $this->longitud = $longitud;
        }
        public function setMaterial(Material $material): void{
            $this->material = $material;
        }
        public function setPiedraAfilada(PiedraAfilada $piedraAfilada): void{
            $this->piedraAfilada = $piedraAfilada;
        }
        public function setCuerda(Cuerda $cuerda): void{
            $this->cuerda = $cuerda;
        }
        public function setPuntuacion(int $puntuacion): void{
            $this->puntuacion = $puntuacion;
        }
        public function setEficienciaBaston(float $eficienciaBaston): void{
            $this->eficienciaBaston = $eficienciaBaston;
        }

        public function calcularEficiencia(): float{
            try {
                $eficienciaLanza=0.00;
                $eficienciaBaston = $this->material->calcularEficiencia([
                    'beneficiosos' => ['dureza', 'tenacidad', 'resistenciaTraccion'],
                    'perjudiciales' => ['densidad', 'fragilidad']
                ]);
                $this->eficienciaBaston = $eficienciaBaston;
                $eficienciaPiedraAfilada = $this->piedraAfilada->getEficiencia();
                $eficienciaCuerda = $this->cuerda->getEficiencia();

                $eficienciaLanza = round(($eficienciaBaston+$eficienciaCuerda+$eficienciaPiedraAfilada)/3,2);
                
                return $eficienciaLanza;
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
            $puntuacion = round($this->calcularEficiencia());
            return $puntuacion;
        }

        public function calcularPeso(): float{
            $densidadLanza = $this->material->densidad;
            $volumenLanza = $this->calcularVolumen();
            $pesoLanza = $densidadLanza * $volumenLanza;
            
          
            $pesoPiedra = $this->piedraAfilada->calcularPeso();

          
            $pesoCuerda = $this->cuerda->calcularPeso();
            $peso = $pesoLanza + $pesoPiedra +$pesoCuerda;
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

        public function __toString():string{
            $figura = $this->getFigura();
            $radio = $this->tamanyo['radio'];
            $altura = $this->tamanyo['altura'];
            $peso = $this->calcularPeso();
            $volumen = $this->calcularVolumen();
            $area = $this->calcularArea();
            $superficie = $this->calcularSuperficie();
            
            $html = parent::__toString();
            $html .= '<tr><th>Eficiencia</th><td>' . htmlspecialchars($this->eficiencia) . '</td></tr>';
            $html .= '<tr><th>Puntos</th><td>' . htmlspecialchars($this->puntuacion) . '</td></tr>';
            $html .= '<tr><th>Zona de creación</th><td>' . htmlspecialchars($this->zonaCreacion) . '</td></tr>';
            $html .= '<tr><th>Método de creación</th><td>' . htmlspecialchars($this->metodoCreacion) . '</td></tr>';
            $html .= '<tr><th>Tiempo de inicio</th><td>' . htmlspecialchars($this->tiempoInicial->format('Y-m-d H:i:s')) . '</td></tr>';
            $html .= '<tr><th>Tiempo de creación</th><td>' . htmlspecialchars($this->tiempoCreacion) . '</td></tr>';
            $html .= '<tr><th>Tiempo de fin</th><td>' . htmlspecialchars($this->tiempoFinal->format('Y-m-d H:i:s')) . '</td></tr>';
            
            $html .= '<tr><th colspan=2>Inventos previos</th></tr>'; 
            foreach (self::$inventosPrevios as $invento => $valor) {
            $html .= '<tr><th>'.$invento.'</th><td>' . $valor . '</td></tr>';
            }
            
            $html .= '<tr><th>Figura</th><td>' . htmlspecialchars($figura) . '</td></tr>';
            $html .= '<tr><th>Tamaño (radio x altura)</th><td>' . htmlspecialchars($radio .' x '.$altura) . '</td></tr>';
            $html .= '<tr><th>Peso</th><td>' . htmlspecialchars($peso) . '</td></tr>';
            $html .= '<tr><th>Volumen</th><td>' . htmlspecialchars($volumen) . '</td></tr>';
            $html .= '<tr><th>Área</th><td>' . htmlspecialchars($area) . '</td></tr>';
            $html .= '<tr><th>Superficie</th><td>' . htmlspecialchars($superficie) . '</td></tr>';
            $html .= '<tr><th>Material</th><td>';
            $html .=  $this->material->__toString();
            $html .= '<tr><th>Dureza</th><td>' . htmlspecialchars($this->material->dureza) . '</td></tr>';
            $html .= '<tr><th>Tenacidad</th><td>' . htmlspecialchars($this->material->tenacidad) . '</td></tr>';
            $html .= '<tr><th>Resistencia a la tracción</th><td>' . htmlspecialchars($this->material->resistenciaTraccion) . '</td></tr>';
            $html .= '<tr><th>Flexibilidad</th><td>' . htmlspecialchars($this->material->flexibilidad) . '</td></tr>';
            $html .= '<tr><th>Densidad</th><td>' . htmlspecialchars($this->material->densidad) . '</td></tr>';
            $html .= '<tr><th>Eficacia del bastón</th><td>' . htmlspecialchars($this->eficienciaBaston) . '</td></tr>';
            $html .= ' </table>';
            $html .= '<tr><th>Piedra Afilada</th><td>';
            $html .= $this->piedraAfilada->__toString();
            $html .= '</td><tr><th>Cuerda</th><td>';
            $html .= $this->cuerda->__toString();
            $html.= '</td></table>';

            return $html;
        }
        public static function probarInvento(array $argumentos = []):void{
            $lanza = new Lanza(...$argumentos);
            echo $lanza->__toString();
        }
      
    }
?>