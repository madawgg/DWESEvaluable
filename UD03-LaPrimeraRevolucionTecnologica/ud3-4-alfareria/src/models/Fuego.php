<?php

include_once('Invento.php');
include_once('Material.php');


class Fuego extends Invento{
    
    private string $metodo;
    private Material $material;
    private int $tiempoBase = 0;
    private int $tiempoEstimado;
    private float $eficiencia;
    private int $puntuacion;
    protected string $zonaCreacion;
    protected string $metodoCreacion;
    protected int $tiempoCreacion;

    protected static array $inventosPrevios = [];

    public function __construct(string $nombre, Material $material, string $zonaCreacion = null, string $metodoCreacion = null) {
        parent::__construct($nombre, 1);
        $this->material = $material;
        $this->zonaCreacion = $zonaCreacion ?? 'pradera';
        $this->metodoCreacion = $metodoCreacion ?? 'tradicional';  
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
    //getters
    
    public function getMaterial(): Material{
        return $this->material;
    }
    public function getTiempoBase(): float{
        return $this->tiempoBase;
    }
    public function getEficiencia(): float{
        return $this->eficiencia;
    }
    public function getPuntuacion(): int{
        return $this->puntuacion;
    }

    //setters
    
    public function setMaterial(Material $material): void{
        $this->material = $material;
    }
    public function setTiempoBase(DateInterval $tiempoBase):void{
        $this->tiempoBase = $tiempoBase;
    }
    public function setEficiencia(float $eficiencia): void{
        $this->eficiencia = $eficiencia;
    }
    public function setPuntuacion(int $puntuacion): void{
        $this->puntuacion = $puntuacion;
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
        $html .= '<tr><th>Eficiencia</th><td>' . htmlspecialchars($this->calcularEficiencia()) . '</td></tr>';
        $html .= '<tr><th>Puntos</th><td>' . htmlspecialchars($this->calcularPuntuacion()) . '</td></tr>';
        $html .= '<tr><th>Zona de creación</th><td>' . htmlspecialchars($this->zonaCreacion) . '</td></tr>';
        $html .= '<tr><th>Método de creación</th><td>' . htmlspecialchars($this->metodoCreacion) . '</td></tr>';
        $html .= '<tr><th>Tiempo de inicio</th><td>' . htmlspecialchars($this->tiempoInicial->format('Y-m-d H:i:s')) . '</td></tr>';
        $html .= '<tr><th>Tiempo de creación</th><td>' . htmlspecialchars($this->tiempoCreacion). '</td></tr>';
        $html .= '<tr><th>Tiempo de fin</th><td>' . htmlspecialchars($this->tiempoFinal->format('Y-m-d H:i:s')) . '</td></tr>';
        $html .= '<tr><th>Tiempo estimado</th><td>' . htmlspecialchars($this->tiempoEstimado).' seg' . '</td></tr>';
        $html .= '<tr><th>Material</th><td>';
        $html .=  $this->material->__toString();
        $html .= '<tr><th>Inflamabilidad</th><td>' . htmlspecialchars($this->material->dureza) . '</td></tr>';
        $html .= '<tr><th>Densidad energética</th><td>' . htmlspecialchars($this->material->densidadEnergetica) . '</td></tr>';
        $html .= '<tr><th>Resistencia humedad</th><td>' . htmlspecialchars($this->material->resistenciaHumedad) . '</td></tr>';
        $html .= '<tr><th>Resistencia oxidación</th><td>' . htmlspecialchars($this->material->resistenciaOxidacion) . '</td></tr>';
        $html .= '<tr><th>Resistencia temperatura</th><td>' . htmlspecialchars($this->material->resistenciaTemperatura) . '</td></tr>';
        $html .= '</table> </table>';
       
        return $html;
    }

    public static function probarInvento(array $argumentos = []): void{
        $fuego = new Fuego(...$argumentos);
        echo $fuego->__toString();
    }


}

?>