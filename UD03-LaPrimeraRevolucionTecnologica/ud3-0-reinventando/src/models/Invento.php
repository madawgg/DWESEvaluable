<?php
    require_once( 'Material.php');

 abstract class Invento {
    protected string $nombre;
    protected int $nivel;
    protected static array $inventos_previos = [];
    protected int $puntos = 0;

    abstract public function calcularEficiencia(): float; 
    abstract public function calcularPuntuacion(): int;

    public function __construct(string $nombre, int $nivel = 1,){
        $this->nombre = $nombre;
        $this->nivel = $nivel;
    }

    //getters
    public function getNombre(): string {
        return $this->nombre;
    }
    
    public function getNivel(): int{
        return $this->nivel;
    }

    public function getPuntos(): int{
        return $this->puntos;
    }

    //setters
    public function setNombre(string $nombre): void{
        $this->nombre = $nombre;
        //return $this;
    }

    public function setNivel(int $nivel): void{
        $this->nivel = $nivel;
        //return $this;
    }
    public function setPuntos(int $puntos): void{
        //WARNING
        //si es necesario sumar puntos sobre puntos existentes, necesito modificar esta funcion
        $this->puntos=$puntos;
        //return $this; 
    }

   public static function probarInvento(array $argumentos = []): void{
        echo htmlspecialchars( 'El invento se ha probado.') .'</br>';
    }

    public function __toString(): string{
        $html = "<table class='invento-table'>";
        $html .= "<tr><th>Nombre</td><td>{$this->nombre}</th></tr>";
        $html .= "<tr><th>Nivel</td><td>{$this->nivel}</th></tr>"; 
         // Cerrar la tabla    
        return $html;
    }
}
?>