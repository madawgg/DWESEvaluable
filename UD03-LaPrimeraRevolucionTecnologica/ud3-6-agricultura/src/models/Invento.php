<?php
    include_once 'Material.php';

 abstract class Invento {
    protected string $nombre;
    protected int $nivel;
    protected static array $inventosPrevios = [];
    protected int $puntos = 0;
    protected string $zonaCreacion;
    protected string $metodoCreacion;
    protected DateTime $tiempoInicial;
    protected int $tiempoCreacion = 0;
    protected DateTime $tiempoFinal;
    protected float $eficiencia;
    protected int $puntuacion;

    abstract public function calcularEficiencia(): float; 
    abstract public function calcularPuntuacion(): int;
    abstract public function calcularTiempoCreacion(): int;
    

    public function __construct( string $nombre ){
        $this->nombre = $nombre;
        $this->nivel = static::NIVEL;
        $this->tiempoInicial = new DateTime();    
    }
    
    public function __get($atributo){
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
   
    
    public function calcularTiempoTotal($tiempoCreacion): DateTime{
        $intervalo = new DateInterval('PT' . $tiempoCreacion . 'S');
        $horaFinal = (clone $this->tiempoInicial)->add($intervalo);
        return $horaFinal;
    }
    public static function probarInvento(array $argumentos = []): void{
        echo htmlspecialchars( 'El invento se ha probado.') .'</br>';
    }

    public function __toString(): string{
        
        $html = "<table class='invento-table'>";
        $html .= "<tr><th>Nombre</td><td>{$this->nombre}</th></tr>";
        $html .= "<tr><th>Nivel</td><td>{$this->nivel}</th></tr>";
        $html .= '<tr><th>Eficiencia</th><td>' . htmlspecialchars($this->eficiencia) . ' %</td></tr>';
        $html .= '<tr><th>Puntos</th><td>' . htmlspecialchars($this->puntuacion) . '</td></tr>';
        $html .= '<tr><th>Zona de creación</th><td>' . htmlspecialchars(ucfirst($this->zonaCreacion)). '</td></tr>';
        $html .= '<tr><th>Metodo de creación</th><td>' . htmlspecialchars(ucfirst($this->metodoCreacion)) . '</td></tr>';
        $html .= '<tr><th>Tiempo de inicio</th><td>' . htmlspecialchars($this->tiempoInicial->format('Y-m-d H:i:s')). '</td></tr>';
        $html .= '<tr><th>Tiempo de creación</th><td>' . htmlspecialchars($this->tiempoCreacion). ' segundos</td></tr>';
        $html .= '<tr><th>Tiempo de fin</th><td>' . htmlspecialchars($this->tiempoFinal->format('Y-m-d H:i:s')). '</td></tr>'; 
        if(!empty(static::$inventosPrevios)){
            $html .= '<tr><th colspan=2>Inventos previos</th></tr>';
            foreach (static::$inventosPrevios as $invento => $valor) {
                $html .= '<tr><th>'.$invento.'</th><td>' . $valor . ' unidades</td></tr>';
            }
        }
         // Cerrar la tabla    
        return $html;
    }
}
?>