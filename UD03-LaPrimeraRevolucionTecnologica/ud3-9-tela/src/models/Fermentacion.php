<?php

include_once 'Invento.php';
include_once 'Material.php';
include_once 'Alfareria.php';
include_once 'Torno.php';
include_once 'Agricultura.php';

Class Fermentacion extends Invento{
    protected const NIVEL = 2;
    protected static array $inventosPrevios =[
        'Alfareria'=> 2,
        'Torno' => 1,
        'Agricultura' => 2,
    ];

    public static array $campos = [
        ['nombre' => 'material', 'tipo' => 'select', 'variable' => 'materiales'],
        ['nombre' => 'alfareria', 'tipo' => 'select', 'variable' => 'Alfareria'],
        ['nombre' => 'torno', 'tipo' => 'select', 'variable' => 'Torno'],
        ['nombre' => 'agricultura', 'tipo' => 'select', 'variable' => 'Agricultura'],
        ['nombre' => 'nombre', 'tipo' => 'text'],
        ['nombre' => 'tiempoMinimo', 'tipo' => 'number'],
        ['nombre' => 'tiempoMaximo', 'tipo' => 'number'],
        ['nombre' => 'zona', 'tipo' => 'select', 'variable' => 'zonas'],
        ['nombre' => 'tecnica', 'tipo' => 'select', 'variable' => 'tecnicas']
    ];

    public function __construct( 
        protected Material $material,
        protected array $alfarerias,
        protected Torno $torno,
        protected array $agriculturas,
        string $nombre,
        protected int $tiempoMinimo,
        protected int $tiempoMaximo,
        protected string $zonaCreacion = 'pradera',
        protected string $metodoCreacion = 'tradicional'
        ){
            parent::__construct($nombre);
            $this->eficiencia = $this->calcularEficiencia();
            $this->tiempoCreacion = $this->calcularTiempoCreacion();
            $this->tiempoFinal = $this->calcularTiempoTotal($this->tiempoCreacion);
            $this->puntuacion = $this->calcularPuntuacion();
    }

    public static function getInventosPrevios(): array {
        return self::$inventosPrevios;
    }
    
    public static function getCampos(): array {
        return self::$campos;
    }

    public function __get($atributo){
        if (property_exists($this, $atributo)) {
            return $this->$atributo;
        }else{
            echo "El atributo $atributo no existe en fermentacion";
        }
    }

    public function __set($atributo, $valor){
        if (property_exists($this, $atributo)) {
            $this->$atributo = $valor;
        }else{
            echo "El atributo $atributo no se ha seteado en fermentacion";
        }
    }

    public function calcularEficiencia(): float{
        $eficienciaMaterial = $this->material->calcularEficiencia([
            'beneficiosos' => ['resistenciaQuimica', 'resistenciaHumedad'],
            'perjudiciales' => ['porosidad', 'toxicidad']
        ]);

        $sumaEficiencias = $this->sumarEficiencias($this->alfarerias) + 
                        $this->sumarEficiencias($this->agriculturas) + 
                        $this->torno->eficiencia +
                        $eficienciaMaterial;

        $sumaCantidad = count($this->alfarerias) + 
                        count($this->agriculturas) + 
                        2;

        $eficiencia = $sumaEficiencias / $sumaCantidad;

        return round($eficiencia,2);
    }

    public function calcularTiempoCreacion(): int {
        
        $tiempoBase = 60 * $this->nivel; 
       
        switch ($this->metodoCreacion) {
            case 'tradicional':
                $tiempoBase;
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
        $tiempoFinal = $this->material->calcularTiempoCreacion($this->zonaCreacion, $tiempoBase);
    
        return round($tiempoFinal);
    }

    public function calcularPuntuacion(): int{
        $eficiencia = $this->eficiencia;
        $elementosAgricultura1 = $this->agriculturas[0]->numeroElementos;
        $elementosAgricultura2 = $this->agriculturas[1]->numeroElementos;
        $mediaElementos = ($elementosAgricultura1 + $elementosAgricultura2) / 2;

        $puntuacion = $eficiencia + $mediaElementos;

        return round($puntuacion);
    }

    public function __toString(): string {
        $html = parent::__toString();
        $html .= '<tr><th>Tiempo mínimo</th><td>' . htmlspecialchars($this->tiempoMinimo) . ' minutos</td></tr>';
        $html .= '<tr><th>Tiempo máximo</th><td>' . htmlspecialchars($this->tiempoMaximo) . ' minutos</td></tr>';
        $html .= '<tr><th>Material</th><td>';
        $html .= $this->material;
        $html .= '<tr><th>resistenciaQuimica</th><td>' . htmlspecialchars($this->material->resistenciaQuimica) . ' minutos</td></tr>';
        $html .= '<tr><th>resistenciaHumedad</th><td>' . htmlspecialchars($this->material->resistenciaHumedad) . ' minutos</td></tr>';
        $html .= '<tr><th>porosidad</th><td>' . htmlspecialchars($this->material->porosidad) . ' minutos</td></tr>';
        $html .= '<tr><th>toxicidad</th><td>' . htmlspecialchars($this->material->toxicidad) . ' minutos</td></tr>';
        
        $html .= '</td></tr>';

        for ($i=0; $i < count($this->alfarerias) ; $i++) { 
            $html .= "<tr><th>Alfarería". $i + 1 ."</th><td>".  $this->alfarerias[$i] . "</td><tr>";
        }

        $html .= "<tr><th>Torno</th><td>".  $this->torno . "</td><tr>";

        for ($i=0; $i < count($this->agriculturas) ; $i++) { 
            $html .= "<tr><th>Agricultura". $i + 1 ."</th><td>".  $this->agriculturas[$i] . "</td><tr>";
        }

        $html .= '</td></table>';
        
        return $html;
    }


}

    

?>