<?php

include_once 'Invento.php';
include_once 'Material.php';


class Cesta extends Invento{

    private Material $material;
    private float $diametro;
    private float $altura;
    private float $grosorMaterial;
    private float $eficiencia;
    private int $puntuacion;

    public function __construct(string $nombre, Material $material, float $diametro, float $altura, float $grosorMaterial){
        parent::__construct($nombre,1);
        $this->material = $material;
        $this->diametro = $diametro;
        $this->altura = $altura;
        $this->grosorMaterial = $grosorMaterial;
        $this->eficiencia = $this->calcularEficiencia();
        $this->puntuacion = $this->calcularPuntuacion();  
    }

    //getters
    public function getMaterial(): Material{
        return $this->material;
    }
    public function getDiametro(): float{
        return $this->diametro;
    }
    public function getAltura(): float{
        return $this->altura;
    }
    public function getGrosorMaterial(): float{
        return $this->grosorMaterial;
    }
    public function getEficiencia(): float{
        return $this->eficiencia;
    }
    public function getPuntuacion(): intº{
        return $this->putuacion;
    }

    //setters
    public function setMaterial(Material $material): void{
        $this->material = $material;
    }
    public function setDiametro(float $diametro): void{
        $this->diametro = $diametro;
    }
    public function setAltura(float $altura): void{
        $this->altura = $altura;
    }
    public function setGrosorMaterial(float $grosorMaterial): void{
        $this->grosorMaterial = $grosorMaterial;
    }
    public function setEficiencia(float $eficiencia): void{
        $this->eficiencia = $eficiencia;
    }
    public function setPuntuacion(int $puntuacion): void{
        $this->puntuacion = $puntuacion;
    }

    public function calcularEficiencia(): float {
        try {
            return $this->material->calcularEficiencia([
                'beneficiosos' => ['flexibilidad'],
                'perjudiciales' => ['densidad', 'fragilidad']
            ]);
        } catch (Exception $e) {
            return 0.00;
        }
    }

    public function calcularPuntuacion(): int{
        $puntuacion = round($this->eficiencia);

        return $puntuacion;
    }

    public function __toString():string{
        $html = parent::__toString();
        $html .= '<tr><th>Eficiencia</th><td>' . htmlspecialchars($this->calcularEficiencia()) . '</td></tr>';
        $html .= '<tr><th>Puntos</th><td>' . htmlspecialchars($this->calcularPuntuacion()). '</td></tr>';
        $html .= '<tr><th>Diametro</th><td>' . htmlspecialchars($this->diametro) . '</td></tr>';
        $html .= '<tr><th>Altura</th><td>' . htmlspecialchars($this->altura) . '</td></tr>';
        $html .= '<tr><th>Grosor</th><td>' . htmlspecialchars($this->grosorMaterial) . '</td></tr>';
        $html .= '<tr><th>Material</th><td>';
        $html .=  $this->material->__toString();
        $html .= '<tr><th>Flexibilidad</th><td>' . htmlspecialchars($this->material->flexibilidad) . '</td></tr>';
        $html .= '<tr><th>Densidad</th><td>' . htmlspecialchars($this->material->densidad) . '</td></tr>';
        $html .= '<tr><th>Fragilidad</th><td>' . htmlspecialchars($this->material->fragilidad) . '</td></tr>';
        $html .= '</td></tr>';
        $html .= '</table> </table>';
       
        return $html;
    }

    public static function probarInvento(array $argumentos = []): void{
        $cesta = new Cesta(...$argumentos);
        echo $cesta->__toString();
    }

}

?>