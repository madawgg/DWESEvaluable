<?php
    include_once 'Invento.php';
    include_once 'Material.php';
    include_once 'PiedraAfilada.php';
    include_once 'Cuerda.php';
    require_once 'src/traits/CalculosGeometricos.php';
    include_once 'src/interfaces/Medible.php';

    class Lanza extends Invento implements Medible{
        use CalculosGeometricos;
        protected const FIGURA = 'Cilindro';
        protected const NIVEL = 1;
        protected float $longitud;
        protected float $eficienciaBaston;
        protected static array $campos = [
            ['nombre' => 'material', 'tipo' => 'select', 'variable' => 'materiales'],
            ['nombre' => 'piedraAfilada', 'tipo' => 'select', 'variable' => 'PiedraAfilada'],
            ['nombre' => 'cuerda', 'tipo' => 'select', 'variable' => 'Cuerda'],
            ['nombre' => 'nombre', 'tipo' => 'text'],
            ['nombre' => 'radio', 'tipo' => 'number'],
            ['nombre' => 'altura', 'tipo' => 'number'],
            ['nombre' => 'zona', 'tipo' => 'select', 'variable' => 'zonas'],
            ['nombre' => 'tecnica', 'tipo' => 'select', 'variable' => 'tecnicas'],
        ];
        protected static array $inventosPrevios=[ 
            'PiedraAfilada' => 1,
            'Cuerda' => 1
        ];
        protected int $tiempoCreacion;

        public function __construct(
            protected Material $material,
            protected PiedraAfilada $piedraAfilada,
            protected Cuerda $cuerda,
            string $nombre, 
            protected array $tamanyo, 
            protected string $zonaCreacion = 'pradera',
            protected string $metodoCreacion = 'tradicional'
        ){
            parent::__construct($nombre);
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
        public static function getCampos(): array {
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
                $eficienciaPiedraAfilada = $this->piedraAfilada->eficiencia;
                $eficienciaCuerda = $this->cuerda->eficiencia;

                $eficienciaLanza = round(($eficienciaBaston+$eficienciaCuerda+$eficienciaPiedraAfilada)/3,2);
                
                return $eficienciaLanza;
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
            $densidadLanza = $this->material->getDensidadReal();
            $volumenLanza = $this->calcularVolumen();
            $pesoLanza = $densidadLanza * $volumenLanza;
            
          
            $pesoPiedra = $this->piedraAfilada->calcularPeso();

          
            $pesoCuerda = $this->cuerda->calcularPeso();
            $peso = $pesoLanza + $pesoPiedra +$pesoCuerda;
            
            return round($peso,2);
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
            $radio = $this->tamanyo['radio'] /100;
            $altura = $this->tamanyo['altura']/100;

            $html = parent::__toString();         
            $html .= '<tr><th>Figura</th><td>' . htmlspecialchars($figura) . '</td></tr>';
            $html .= '<tr><th>Tamaño</th><td>' . htmlspecialchars("Radio: $radio m, Altura: $altura m") . '</td></tr>';
            $html .= $this->calculosGeometricos();
            $html .= '<tr><th>Material</th><td>';
            $html .=  $this->material;
            $html .= '<tr><th>Dureza</th><td>' . htmlspecialchars($this->material->dureza) . ' sobre 100</td></tr>';
            $html .= '<tr><th>Tenacidad</th><td>' . htmlspecialchars($this->material->tenacidad) . ' sobre 100</td></tr>';
            $html .= '<tr><th>Resistencia a la tracción</th><td>' . htmlspecialchars($this->material->resistenciaTraccion) . ' sobre 100</td></tr>';
            $html .= '<tr><th>Flexibilidad</th><td>' . htmlspecialchars($this->material->flexibilidad) . ' sobre 100</td></tr>';
            $html .= '<tr><th>Densidad</th><td>' . htmlspecialchars($this->material->densidad) . ' sobre 100</td></tr>';
            $html .= '<tr><th>Eficacia del bastón</th><td>' . htmlspecialchars($this->eficienciaBaston) . ' sobre 100</td></tr>';
            $html .= ' </table>';
            $html .= '<tr><th>Piedra Afilada</th><td>';
            $html .= $this->piedraAfilada;
            $html .= '</td><tr><th>Cuerda</th><td>';
            $html .= $this->cuerda;
            $html.= '</td></table>';

            return $html;
        }
        public static function probarInvento(array $argumentos = []):void{
            $lanza = new Lanza(...$argumentos);
            echo $lanza->__toString();
        }
      
    }
?>