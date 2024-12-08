<?php

interface Medible{

    public function calcularPeso(): float;
    public function calcularVolumen(): float;
    public function calcularArea(): float;
    public function calcularSuperficie(): float;
    public function obtenerTamanyo(): array;

}
?>