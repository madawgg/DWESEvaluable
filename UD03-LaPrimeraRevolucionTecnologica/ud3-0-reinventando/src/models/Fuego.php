<?php

include_once('Invento.php');
include_once('Material.php');


class Fuego extends Invento{
    
    private string $metodo;
    private Material $material;
    private int $tiempoBase = 0;
    private int $tiempoEstimado;
    private DateTime $inicio;
    private DateTime $fin;
    private float $eficiencia;
    private int $puntuacion;
   
    public function __construct(string $nombre,string $metodo, Material $material) {
        parent::__construct($nombre, 1);
        $this->metodo = $metodo;
        $this->material = $material;
        $this->inicio = new DateTime(); 
        $this->tiempoBase = $this->seleccionarMetodoFuego();
        $this->tiempoEstimado = round($this->tiempoBase + (100 - (($this->material->inflamabilidad * $this->material->resistenciaTemperatura)/100))); 
        $this->fin = $this->calcularTiempoFinalFuego();
        $this->eficiencia = $this->calcularEficiencia();
        $this->putnuacion = $this->calcularPuntuacion();
        
        
    }
    //getter magico
    public function __get(string $property) {
        if (property_exists($this, $property)) {
            return $this->$property;
        } else {
            throw new Exception("La propiedad '$property' no existe en la clase " . __CLASS__);
        }
    }
    //getters
    public function getMetodo(): string{
        return $this->metodo;
    }
    public function getMaterial(): Material{
        return $this->material;
    }
    public function getTiempoBase(): float{
        return $this->tiempoBase;
    }
    public function getInicio(): DateTime{
        return $this->inicio;
    }
    public function getFin(): DateTime{
        return $this->fin;
    }
    public function getEficiencia(): float{
        return $this->eficiencia;
    }
    public function getPuntuacion(): int{
        return $this->puntuacion;
    }

    //setters
    public function setMetodo(string $metodo): void{
        $this->metodo = $metodo;
    }
    public function setMaterial(Material $material): void{
        $this->material = $material;
    }
    public function setTiempoBase(DateInterval $tiempoBase):void{
        $this->tiempoBase = $tiempoBase;
    }
    public function setInicio(DateTime $inicio): void{
        $this->inicio = $inicio;
    }
    public function setFin(DateTime $fin): void{
        $this->fin = $fin;
    }
    public function setEficiencia(float $eficiencia): void{
        $this->eficiencia = $eficiencia;
    }
    public function setPuntuacion(int $puntuacion): void{
        $this->puntuacion = $puntuacion;
    }

    private function seleccionarMetodoFuego(): int{
        $metodos = ['fricción', 'chispa', 'lupa'];
        $valorMetodo = match ($this->metodo) {
             'fricción'=> rand(10, 30),
             'chispa'=> rand(5,20),
             'lupa' => rand(7,25),
             default => 100,
        };        
        return $valorMetodo;
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
        $eficiencia = $this->calcularEficiencia([]);
        
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
        $html .= '<tr><th>Tiempo base</th><td>' . htmlspecialchars($this->tiempoBase).' min' . '</td></tr>';
        $html .= '<tr><th>Tiempo estimado</th><td>' . htmlspecialchars($this->tiempoEstimado).' min' . '</td></tr>';
        $html .= '<tr><th>Inicio</th><td>' . htmlspecialchars($this->inicio->format('Y-m-d H:i:s')) . '</td></tr>';
        $html .= '<tr><th>Fin</th><td>' . htmlspecialchars($this->fin->format('Y-m-d H:i:s')) . '</td></tr>';
        $html .= '<tr><th>Método</th><td>' . htmlspecialchars($this->metodo) . '</td></tr>';
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