# UD2.4-Lanza

Isabel se dio cuenta de que para maximizar el uso de sus lanzas, necesitaba comprender mejor cómo diferentes materiales y condiciones ambientales afectaban su efectividad. Con la ayuda de un script PHP, Isabel pudo introducir las propiedades de varios materiales y las condiciones climáticas para simular y calcular la efectividad de sus lanzas en diversas situaciones. El script generaba datos aleatorios para diferentes escenarios, permitiéndole probar combinaciones únicas de materiales y longitudes. Este enfoque científico le permitió diseñar lanzas perfectamente equilibradas para cazar desde largas distancias, medias distancias y en combates cuerpo a cuerpo.

## Objetivos de la Actividad

- Practicar utilizando arrays, matrices y funciones en PHP.
- Manejar condiciones múltiples y cálculos basados en datos de entrada.
- Crear y recoger datos aleatorios.
- Concatenar valores.
- Presentar los resultados de manera legible para el usuario.

## Pasos

1. **Inicializar Arrays para los Atributos de la Lanza:**

   - **$materiales:** Una matriz asociativa que contiene diferentes materiales y sus atributos (peso, flexibilidad, dureza).

2. **Definir Arrays para las Condiciones Ambientales:**

   - **$climas:** Un array con diferentes condiciones climáticas ('Lluvia', 'Viento', 'Soleado', 'Nevado').

3. **Función `generarImpactoClimaticoLanza`:**

   - Esta función recibe los arrays de materiales y climas.
   - Genera un impacto climático aleatorio para cada material y clima, con valores entre -10 y 10.
   - Devuelve una matriz asociativa material y clima con el impacto climático generado.

4. **Función `generarLanza`:**

   - Esta función recibe el array de materiales.
   - Selecciona aleatoriamente entre 1 y 3 materiales.
   - Si se han seleccionado solo un material, lo añade dentro de un array.
   - Selecciona aleatoriamente la longitud entre 1 y 3 metros.
   - Devuelve un array asociativo con la longitud de la lanza y con un array con el/los materiales seleccionados.

5. **Función `calcularEfectividadesLanza`:**

   - Esta función recibe la lanza generada y el array de materiales.
   - Calcula el peso total, la flexibilidad total y la dureza total de la lanza sumando las propiedades de cada material.
   - Define una efectividad base de 50.
   - La efectividad a larga distancia, media distancia y cuerpo a cuerpo se duplicará si basándose en las propiedades de la lanza cumple alguna de las siguientes condiciones:
  
        | Condición |
        |-----------|
        | **Efectividad a larga distancia** |
        | - El peso total dividido por la longitud de la lanza es menor o igual a 2 Y la flexibilidad total dividida por la cantidad de materiales es mayor o igual a 3. |
        | - El peso total dividido por la longitud de la lanza es menor o igual a 2 Y la dureza total dividida por la cantidad de materiales es mayor o igual a 6. |
        | - La flexibilidad total dividida por la cantidad de materiales es mayor o igual a 3 Y la dureza total dividida por la cantidad de materiales es mayor o igual a 6. |
        | **Efectividad a media distancia** |
        | - El peso total es menor o igual a 6. |
        | - La flexibilidad total dividida por la cantidad de materiales es mayor o igual a 4. |
        | - La dureza total dividida por la cantidad de materiales es mayor o igual a 5. |
        | **Efectividad en batallas cuerpo a cuerpo** |
        | - El peso total es menor o igual a 8 Y la flexibilidad total dividida por la cantidad de materiales es mayor o igual a 5 Y la dureza total dividida por la cantidad de materiales es mayor o igual a 4. |

   - Devuelve un array con las efectividades calculadas. Ten en cuenta que ninguna efectividad puede ser mayor a 100.

6. **Función `imprimirLanza`:**

   - Esta función recibe la lanza generada.
   - Imprime en pantalla la longitud y los materiales de la lanza.

        ```text
        Lanza:  
        Longitud: XX m  
        Materiales: AAA, BBB  
        ```

7. **Función `imprimirEfectividadLanza`:**

   - Esta función recibe las efectividades calculadas.
   - Imprime en pantalla la efectividad a larga distancia, media distancia y cuerpo a cuerpo.

        ```text
        Efectividad a larga distancia: XX%
        Efectividad a media distancia: YY%
        Efectividad en cuerpo a cuerpo: ZZ%
        -------------------------
        ```

8. **Función `aplicarImpactoClimaticoLanza`:**

   - Esta función recibe la lanza, las efectividades calculadas, el impacto climático y las condiciones climáticas.
   - Aplica las efectividades de la lanza para cada clima y le añade su impacto en cada material para dicho clima.
   - Imprime en pantalla los resultados para cada clima.

        ```text
            Resultados para el clima: CCCCC
        ```

   - Llama a `imprimirEfectividadLanza`.
  
9. **Función `principal`:**

   - Inicializa los arrays para los atributos de la lanza y las condiciones climáticas.
   - Genera el impacto climático en los materiales con `generarImpactoClimaticoLanza`.
   - Genera una lanza de forma aleatoria con `generarLanza`.
   - Calcula la efectividad de la lanza `calcularEfectividadesLanza`.
   - Imprime la lanza seleccionada con `imprimirLanza`.
   - Imprime la efectividad de la lanza seleccionada con `imprimirEfectividadLanza`.
   - Aplica el impacto de las condiciones climáticas a la efectividad de la lanza e imprime su efectividad tras el impacto para cada clima con `aplicarImpactoClimaticoLanza`.

## Ejemplo

### Datos proporcionados en el programa

- **Materiales:**:
  - Piedra: peso (4), flexibilidad (1) y dureza (7)
  - Cuerno: peso (2), flexibilidad (4) y dureza (6)
  - Marfil: peso (3), flexibilidad (3) y dureza (5)
  - Hueso: peso (2), flexibilidad (4) y dureza (5)
  - Madera: peso (2), flexibilidad (5) y dureza (3)
  - Bambú: peso (1), flexibilidad (7) y dureza (3)
  - Vid: peso (1), flexibilidad (5) y dureza (2)
  - Cañas: peso (1), flexibilidad (8) y dureza (2)
  - Fibra de plantas: peso (1), flexibilidad (6) y dureza (2)
  - Piel animal: peso (1), flexibilidad (7) y dureza (1)
- **Climas:** 'Lluvia', 'Viento', 'Soleado', 'Nevado'

### Salida del programa

```text
Lanza:  
Longitud: 2 m  
Materiales: Piedra, Madera  

Efectividad a larga distancia: 80%  
Efectividad a media distancia: 70%  
Efectividad en cuerpo a cuerpo: 60%  
-------------------------
Resultados para el clima: Lluvia  
Efectividad a larga distancia: 75%  
Efectividad a media distancia: 65%  
Efectividad en cuerpo a cuerpo: 55%  
-------------------------
Resultados para el clima: Viento  
Efectividad a larga distancia: 85%  
Efectividad a media distancia: 75%  
Efectividad en cuerpo a cuerpo: 65%  
-------------------------
Resultados para el clima: Soleado  
Efectividad a larga distancia: 80%  
Efectividad a media distancia: 70%  
Efectividad en cuerpo a cuerpo: 60%  
-------------------------
Resultados para el clima: Nevado  
Efectividad a larga distancia: 70%  
Efectividad a media distancia: 60%  
Efectividad en cuerpo a cuerpo: 50%  
-------------------------
```

## Testing

Los tests se ejecutarán en la rama **main**, cuando en el commit se inicie el mensaje como '**deploy:** + MENSAJE DEL COMMIT' y se realice un push.

Los tests que deberá pasar el código para conseguir el descubrimiento serán los siguientes:

1. testGenerarImpactoClimaticoLanza: Verifica que la función `generarImpactoClimaticoLanza` genere correctamente el impacto climático para una lanza.
2. testGenerarLanza: Verifica que la función `generarLanza` genere correctamente una lanza con los materiales proporcionados.
3. testCalcularEfectividadesLanza: Verifica que la función `calcularEfectividadesLanza` calcule correctamente las efectividades de una lanza.
4. testImprimirLanza: Verifica que la función `imprimirLanza` muestre correctamente la información de una lanza.
5. testImprimirEfectividadLanza: Verifica que la función `imprimirEfectividadLanza` muestre correctamente la efectividad de una lanza.
6. testAplicarImpactoClimaticoLanza: Verifica que la función `aplicarImpactoClimaticoLanza` aplique correctamente el impacto climático a una lanza.
7. testGenerarImpactoClimaticoLanzaValoresExtremos: Verifica que la función `generarImpactoClimaticoLanza` maneje correctamente los valores extremos.
8. testGenerarLanzaUnSoloMaterial: Verifica que la función `generarLanza` genere correctamente una lanza con un solo material.
9. testGenerarLanzaMaximoMateriales: Verifica que la función `generarLanza` maneje correctamente el caso en que se utilizan el máximo número de materiales.
10. testCalcularEfectividadesLanzaPesoExtremo: Verifica que la función `calcularEfectividadesLanza` maneje correctamente los valores extremos de peso.
11. testCalcularEfectividadesLanzaFlexibilidadExtrema: Verifica que la función `calcularEfectividadesLanza` maneje correctamente los valores extremos de flexibilidad.
12. testAplicarImpactoClimaticoLanzaValoresExtremos: Verifica que la función `aplicarImpactoClimaticoLanza` maneje correctamente los valores extremos de impacto climático.
13. testGenerarLanzaConMaterialesDuplicados: Verifica que la función `generarLanza` maneje correctamente el caso en que se utilizan materiales duplicados.
14. testCalcularEfectividadesLanzaMaterialNoExistente: Verifica que la función `calcularEfectividadesLanza` maneje correctamente el caso en que se utiliza un material no existente.
15. testGenerarImpactoClimaticoLanzaSinMateriales: Verifica que la función `generarImpactoClimaticoLanza` maneje correctamente el caso en que no hay materiales disponibles.
16. testGenerarImpactoClimaticoLanzaConClimasNoEspecificados: Verifica que la función `generarImpactoClimaticoLanza` maneje correctamente el caso en que los climas no están especificados.
