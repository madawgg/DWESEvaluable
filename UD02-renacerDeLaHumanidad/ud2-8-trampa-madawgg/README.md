# UD2.8-Trampa

Finalmente, Isabel se dio cuenta de que necesitaba una forma más precisa de evaluar la efectividad de las trampas que construía. Con la ayuda de un script PHP, Isabel pudo introducir las características de cada trampa, como el material, el peso máximo y el volumen máximo, así como las condiciones ambientales y las características de los animales. El script generaba valores aleatorios para simular diferentes escenarios y calcular la probabilidad de éxito de cada trampa. Gracias a esta herramienta, Isabel pudo identificar rápidamente cuáles eran las trampas más efectivas para atrapar diferentes tipos de animales en distintas condiciones.

## Objetivos de la Actividad

- Practicar el uso de arrays asociativos para almacenar información sobre trampas, condiciones ambientales y animales.
- Generar valores aleatorios para las características de los animales.
- Evaluar la efectividad de las trampas para atrapar diferentes animales según sus características.
- Generar un documento HTML con los resultados de la evaluación de las trampas.

## Pasos

1. **Definir Arrays Asociativos:**

   - Crea un array asociativo llamado `$trampas` que contenga diferentes tipos de trampas y sus características (material, peso máximo y volumen máximo).
   - Define un array asociativo `$condiciones` que relacione cada zona con sus respectivas condiciones ambientales (humedad y temperatura).
   - Crea un array asociativo `$animales` que contenga diferentes animales y sus características (peso y volumen).

2. **Función `principal`:**

   - Esta función ejecuta el flujo principal del programa.
   - Define las variables y arrays asociativos necesarios (`$trampas`, `$condiciones`, `$pesoBase`, `$volumenBase`, `$animales`).
   - Genera valores aleatorios para el peso y volumen de cada animal utilizando las variables `$pesoBase` y `$volumenBase`.
   - Selecciona aleatoriamente el nombre de una zona y obtiene sus condiciones ambientales.
   - Llama a la función `imprimirPaginaTrampa` para generar el documento HTML con los resultados de la evaluación.

3. **Función `imprimirPaginaTrampa`:**

   - Recibe como parámetros el nombre de la `$zona` y los arrays `$trampas`, `$condiciones` y `$animales`.
   - Genera el encabezado y la estructura básica del documento HTML.
   - Llama a la función `imprimirCondicionesTrampa` para mostrar las condiciones ambientales de la zona y evaluar cada trampa.
   - Cierra las etiquetas HTML.

4. **Función `imprimirCondicionesTrampa`:**

   - Recibe como parámetros `$zona`, `$condiciones`, `$trampas` y `$animales`.
   - Imprime las condiciones ambientales de la zona (humedad y temperatura).

        ```html
        <h2>Condiciones en ZONA</h2>
        <p>Humedad: XX%, Temperatura: YY°C</p>
        ```

   - Itera sobre cada trampa en el array `$trampas` y llama a la función `imprimirTrampa` para evaluarla.

5. **Función `imprimirTrampa`:**

   - Recibe como parámetros `$trampa`, `$caracteristicas` y `$animales`.
   - Imprime el nombre de la trampa y su material.

        ```html
        <h3>Trampa: TRAMPA (Material: MATERIAL)</h3>
        ```

   - Llama a la función `imprimirEvaluacionAnimalTrampa` para evaluar si la trampa puede atrapar a cada animal.

6. **Función `imprimirEvaluacionAnimalTrampa`:**

   - Recibe como parámetros `$nombreAnimal`, `$caracteristicasAnimal`, `$pesoMax` y `$volumenMax`.
   - Evalúa si el peso y volumen del animal son menores o iguales a los límites máximos de la trampa.
   - Imprime el nombre del animal, su peso, volumen y si la trampa puede atraparlo o no.

        ```html
        <p>Animal: ANIMAL, Peso: PESO kg, Volumen: VOLUMEN m³ - Puede atrapar: SI/NO</p>
        ...
        ```

   - Utiliza clases CSS para resaltar visualmente si la trampa puede atrapar al animal.

## Ejemplo

### Datos proporcionados en el programa

- **$trampas:**
  - 'Trampa de Roca' => ['material' => 'Roca', 'peso_max' => 900, 'volumen_max' => 1.4]
  - 'Trampa de Madera' => ['material' => 'Madera', 'peso_max' => 50, 'volumen_max' => 1.0]
  - 'Trampa de Hueso' => ['material' => 'Hueso', 'peso_max' => 30, 'volumen_max' => 0.5]
  - 'Trampa de Lianas' => ['material' => 'Lianas', 'peso_max' => 20, 'volumen_max' => 0.3]
- **$condiciones:**
  - 'Bosque' => ['humedad' => 70, 'temperatura' => 15]
  - 'Selva' => ['humedad' => 90, 'temperatura' => 25]
  - 'Pradera' => ['humedad' => 50, 'temperatura' => 20]
  - 'Desierto' => ['humedad' => 20, 'temperatura' => 35]
- **$animales:** (valores generados aleatoriamente)
  - 'Conejo' => ['peso' => rand($pesoBase, $pesoBase * 2), 'volumen' => rand(1, 3) * $volumenBase]
  - 'Ciervo' => ['peso' => rand($pesoBase * 20, $pesoBase * 40), 'volumen' => rand(6, 10) * $volumenBase]
  - 'Jabalí' => ['peso' => rand($pesoBase * 10, $pesoBase * 20), 'volumen' => rand(6, 8) * $volumenBase]
  - 'Mamut' => ['peso' => rand($pesoBase * 250, $pesoBase * 500), 'volumen' => rand(9, 15) * $volumenBase]

### Salida del programa

```html
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Evaluación de Trampas Prehistóricas</title>
    <style>
        .si { color: green; }
        .no { color: red; }
    </style>
</head>
<body>
    <h1>Evaluación de la Efectividad de Trampas Prehistóricas</h1>
    <h2>Condiciones en Pradera</h2>
    <p>Humedad: 50%, Temperatura: 20°C</p>
    <h3>Trampa: Trampa de Roca (Material: Roca)</h3>
    <p class="si">Animal: Conejo, Peso: 3 kg, Volumen: 0.2 m³ - Puede atrapar: Sí</p>
    <p class="si">Animal: Ciervo, Peso: 60 kg, Volumen: 0.8 m³ - Puede atrapar: Sí</p>
    <p class="si">Animal: Jabalí, Peso: 30 kg, Volumen: 0.7 m³ - Puede atrapar: Sí</p>
    <p class="si">Animal: Mamut, Peso: 500 kg, Volumen: 1.2 m³ - Puede atrapar: Sí</p>
    <h3>Trampa: Trampa de Madera (Material: Madera)</h3>
    <p class="si">Animal: Conejo, Peso: 3 kg, Volumen: 0.2 m³ - Puede atrapar: Sí</p>
    <p class="no">Animal: Ciervo, Peso: 60 kg, Volumen: 0.8 m³ - Puede atrapar: No</p>
    <p class="no">Animal: Jabalí, Peso: 30 kg, Volumen: 0.7 m³ - Puede atrapar: No</p>
    <p class="no">Animal: Mamut, Peso: 500 kg, Volumen: 1.2 m³ - Puede atrapar: No</p>
    <h3>Trampa: Trampa de Hueso (Material: Hueso)</h3>
    <p class="si">Animal: Conejo, Peso: 3 kg, Volumen: 0.2 m³ - Puede atrapar: Sí</p>
    <p class="no">Animal: Ciervo, Peso: 60 kg, Volumen: 0.8 m³ - Puede atrapar: No</p>
    <p class="no">Animal: Jabalí, Peso: 30 kg, Volumen: 0.7 m³ - Puede atrapar: No</p>
    <p class="no">Animal: Mamut, Peso: 500 kg, Volumen: 1.2 m³ - Puede atrapar: No</p>
    <h3>Trampa: Trampa de Lianas (Material: Lianas)</h3>
    <p class="no">Animal: Conejo, Peso: 3 kg, Volumen: 0.2 m³ - Puede atrapar: No</p>
    <p class="no">Animal: Ciervo, Peso: 60 kg, Volumen: 0.8 m³ - Puede atrapar: No</p>
    <p class="no">Animal: Jabalí, Peso: 30 kg, Volumen: 0.7 m³ - Puede atrapar: No</p>
    <p class="no">Animal: Mamut, Peso: 500 kg, Volumen: 1.2 m³ - Puede atrapar: No</p>
</body>
</html>
```

## Testing

Los tests se ejecutarán en la rama **main**, cuando en el commit se inicie el mensaje como '**deploy:** + MENSAJE DEL COMMIT' y se realice un push.

Los tests que deberá pasar el código para conseguir el descubrimiento serán los siguientes:

1. testImprimirEvaluacionAnimalTrampaPuedeAtrapar: Verifica que la función `imprimirEvaluacionAnimalTrampa` genere la salida correcta cuando la trampa puede atrapar al animal.
2. testImprimirEvaluacionAnimalTrampaNoPuedeAtrapar: Verifica que la función `imprimirEvaluacionAnimalTrampa` genere la salida correcta cuando la trampa no puede atrapar al animal.
3. testImprimirTrampaPuedeAtrapar: Verifica que la función `imprimirTrampa` muestre correctamente que la trampa puede atrapar al animal.
4. testImprimirCondicionesTrampa: Verifica que la función `imprimirCondicionesTrampa` muestre correctamente las condiciones de la trampa.
5. testImprimirPaginaTrampa: Verifica que la función `imprimirPaginaTrampa` genere la salida HTML correcta con las condiciones y características de la trampa.
6. testImprimirEvaluacionAnimalTrampaRocaPuedeAtrapar: Verifica que la función `imprimirEvaluacionAnimalTrampa` genere la salida correcta cuando una trampa de roca puede atrapar al animal.
7. testImprimirEvaluacionAnimalTrampaLianasNoPuedeAtrapar: Verifica que la función `imprimirEvaluacionAnimalTrampa` genere la salida correcta cuando una trampa de lianas no puede atrapar al animal.
8. testImprimirTrampaNoPuedeAtrapar: Verifica que la función `imprimirTrampa` muestre correctamente que la trampa no puede atrapar al animal.
9. testImprimirCondicionesTrampaMultiplesTrampas: Verifica que la función `imprimirCondicionesTrampa` muestre correctamente las condiciones de múltiples trampas.
10. testImprimirPaginaTrampaZonaEspecifica: Verifica que la función `imprimirPaginaTrampa` genere la salida HTML correcta para una zona específica.
11. testImprimirEvaluacionAnimalTrampaPesoIgual: Verifica que la función `imprimirEvaluacionAnimalTrampa` genere la salida correcta cuando el peso del animal es igual al límite de la trampa.
12. testImprimirEvaluacionAnimalTrampaVolumenIgual: Verifica que la función `imprimirEvaluacionAnimalTrampa` genere la salida correcta cuando el volumen del animal es igual al límite de la trampa.
13. testImprimirTrampaPuedeAtraparUnSoloAnimal: Verifica que la función `imprimirTrampa` muestre correctamente que la trampa puede atrapar un solo animal.
14. testImprimirCondicionesTrampaAnimalesAleatorios: Verifica que la función `imprimirCondicionesTrampa` muestre correctamente las condiciones de la trampa con animales aleatorios.
15. testImprimirPaginaTrampaMultiplesZonas: Verifica que la función `imprimirPaginaTrampa` genere la salida HTML correcta para múltiples zonas.
