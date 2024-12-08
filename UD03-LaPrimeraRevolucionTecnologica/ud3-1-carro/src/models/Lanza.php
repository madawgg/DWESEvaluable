<?php
    include_once 'Invento.php';
    include_once 'Material.php';
    include_once 'PiedraAfilada.php';
    include_once 'Cuerda.php';

    class Lanza extends Invento{

        private float $longitud;
        private Material $material;
        private PiedraAfilada $piedraAfilada;
        private Cuerda $cuerda;
        private float $eficienciaBaston;
        private int $puntuacion;
        private float $eficiencia;
        protected static array $inventosPrevios=[];

        public function __construct(string $nombre, float $longitud, Material $material, PiedraAfilada $piedraAfilada, Cuerda $cuerda){
            parent::__construct($nombre, 1);
            $this->longitud = $longitud;
            $this->material = $material;
            $this->piedraAfilada = $piedraAfilada;
            $this->cuerda = $cuerda;
            self::$inventosPrevios = [
                'PiedraAfilada' =>1,
                'Cuerda' => 1
            ];
            $this->eficiencia = $this->calcularEficiencia();
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
        public function calcularPuntuacion(): int{
            $puntuacion = round($this->calcularEficiencia());
            return $puntuacion;
        }
        public function __toString():string{
            // $nombrePiedra = $this->piedraAfilada->getNombre();
            // $nombreCuerda = $this->cuerda->getNombre();
            $html = parent::__toString();
            $html .= '<tr><th>Eficiencia</th><td>' . htmlspecialchars($this->calcularEficiencia()) . '</td></tr>';
            $html .= '<tr><th>Puntos</th><td>' . htmlspecialchars($this->calcularPuntuacion()) . '</td></tr>';
            $html .= '<tr><th>Longitud</th><td>' . htmlspecialchars($this->longitud) . '</td></tr>';
            $html .= '<tr><th colspan=2>Inventos previos</th></tr>';
            foreach (self::$inventosPrevios as $invento => $valor) {
            $html .= '<tr><th>'.$invento.'</th><td>' . $valor . '</td></tr>';
            }
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