<?php

include_once('Invento.php');
include_once('Material.php');


class Fuego extends Invento{
    protected const NIVEL = 1;
    protected string $metodo;
    protected int $tiempoBase = 0;
    protected int $tiempoEstimado;
    protected int $tiempoCreacion;

    protected static array $inventosPrevios = [];

    public function __construct( 
        protected Material $material, 
        string $nombre, 
        protected string $zonaCreacion = 'pradera', 
        protected string $metodoCreacion = 'tradicional'
    ) {
        parent::__construct($nombre);
        $this->eficiencia = $this->calcularEficiencia();
        $this->tiempoCreacion = $this->calcularTiempoCreacion();
        $this->tiempoEstimado = round($this->tiempoCreacion + (100 - (($this->material->inflamabilidad * $this->material->resistenciaTemperatura)/100)));
        $this->tiempoFinal = $this->calcularTiempoTotal($this->tiempoCreacion);
        $this->puntuacion = $this->calcularPuntuacion();
        
        
    }

    public static function getInventosPrevios(): array {
        return self::$inventosPrevios;
    }

    //getter magico
    public function __get($atributo) {
        if (property_exists($this, $atributo)) {
            return $this->$atributo;
        } else {
            throw new Exception("La propiedad '$atributo' no existe en la clase ");
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

    private function calcularTiempoFinalFuego(): DateTime{
    $minutosTotales = $this->tiempoEstimado;
    $intervalo = new DateInterval('PT' . $minutosTotales . 'M');
    $horaFinal = (clone $this->inicio)->add($intervalo);
    return $horaFinal;
    }

    public function calcularEficiencia(): float{
        try {
            return $this->material->calcularEficiencia([
                'beneficiosos' => ['inflamabilidad', 'densidadEnergetica'],
                'perjudiciales' => ['resistenciaHumedad', 'fragilidad', 'resistenciaTemperatura']
            ]);
        } catch (Exception $e) {
            return 0.00;
        }
    }
   
    public function calcularPuntuacion(): int{
        $eficiencia = $this->eficiencia;
        if($eficiencia <= 0){
            return 0;
        }
        $puntuacion = 0;
        match (true) {
            $this->tiempoEstimado < 10 => $puntuacion = $eficiencia + 10,
            $this->tiempoEstimado < 20 && $this->tiempoEstimado >= 10 => $puntuacion = $eficiencia + 8,
            $this->tiempoEstimado < 30 && $this->tiempoEstimado >= 20 => $puntuacion = $eficiencia + 5,
            default => $puntuacion = $eficiencia
        };
        
        return round($puntuacion);
    }

    public function __toString(): string{
        
        $html = parent::__toString();
        $html .= '<tr><th>Material</th><td>';
        $html .=  $this->material->__toString();
        $html .= '<tr><th>Inflamabilidad</th><td>' . htmlspecialchars($this->material->dureza) . ' sobre 100</td></tr>';
        $html .= '<tr><th>Densidad energética</th><td>' . htmlspecialchars($this->material->densidadEnergetica) . ' sobre 100</td></tr>';
        $html .= '<tr><th>Resistencia humedad</th><td>' . htmlspecialchars($this->material->resistenciaHumedad) . ' sobre 100</td></tr>';
        $html .= '<tr><th>Resistencia oxidación</th><td>' . htmlspecialchars($this->material->resistenciaOxidacion) . ' sobre 100</td></tr>';
        $html .= '<tr><th>Resistencia temperatura</th><td>' . htmlspecialchars($this->material->resistenciaTemperatura) . ' sobre 100</td></tr>';
        $html .= '</table> </table>';
       
        return $html;
    }

    public static function probarInvento(array $argumentos = []): void{
        $fuego = new Fuego(...$argumentos);
        echo $fuego->__toString();
    }


}

?>