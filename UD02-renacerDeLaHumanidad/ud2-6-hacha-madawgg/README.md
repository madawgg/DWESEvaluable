# UD2.6-Hacha

Para ayudar a calcular la cantidad de recursos que se necesitaban para construir diferentes tipos de hachas, Isabel creo un script PHP para evaluar y optimizar el uso de diferentes materiales permitiendo introducir las propiedades de cada tipo de piedra y registrar cuántas hachas podían fabricarse con los materiales disponibles. Usando funciones y cálculos precisos, Isabel podía determinar la cantidad exacta de madera que cada hacha podría cortar antes de desgastarse. Con esta herramienta, Isabel logró maximizar la producción de hachas y asegurar que cada una fuera utilizada de manera óptima. Este avance permitió a su tribu recolectar suficiente madera para sobrevivir los duros inviernos, transformando su forma de vida y asegurando su supervivencia.

## Objetivos de la Actividad

- Practicar utilizando arrays y funciones en PHP.
- Manejar condiciones y cálculos basados en datos de entrada.
- Presentar los resultados de manera legible para el usuario.

## Pasos

1. **Definir los Materiales y sus Propiedades:**

   - **piedras:** Un array asociativo que contiene diferentes tipos de piedras y sus valores.

2. **Mostrar Mensaje Inicial:**

   - Imprimir un mensaje indicando que se ha encontrado una mina de materiales.

3. **Generar Cantidad Aleatoria de Materiales a Recoger:**

   - Generar la capacidad de recolección de la mina a partir de un número aleatorio de intentos de recolección entre 5 y 25.
   - Imprimir la capacidad de recolección de la mina.

4. **Inicializar el Inventario de Materiales Recogidos:**

   - **Función `inicializarInventarioHacha`:**
     - Esta función recibe el array asociativo de piedras.
     - Inicializa un inventario con todos las piedras y sus cantidades en cero.
     - Devuelve el inventario inicializado.
       - Ejemplo `['P1' => 0, 'P2' => 0, 'P3' => 0, 'P4' => 0]`

5. **Recoger Materiales de la Mina:**

   - **Función `recogerMaterialesHacha`:**
     - Esta función recibe el array asociativo de piedras, el inventario y el número de intentos.
     - En cada intento, selecciona una piedra aleatoria y genera una cantidad aleatoria entre 100 y 500.
     - Añade la cantidad encontrada al inventario.
     - Imprime la piedra encontrada y la cantidad recogida en cada intento.

6. **Calcular Cuántas Hachas se Pueden Fabricar con el Inventario Recogido:**

   - **Función `calcularHachasFabricadas`:**
     - Esta función recibe el array asociativo de piedras (materiales) y el inventario.
     - Calcula cuántas hachas se pueden fabricar con cada elemento del inventario basándose en el coste de fabricación de dicho elemento.
     - Imprime para cada hacha su coste de fabricación

        ```text
        Coste de fabricación del material P1: es XXXX
        ```

     - Actualiza el inventario con la cantidad restante después de fabricar las hachas.
     - Devuelve un array con la cantidad de hachas fabricadas para cada material.
       - Ejemplo `['P1' => XX, 'P2' => YY, 'P3' => Z, 'P4' => W]`

7. **Mostrar el Inventario Recogido:**

   - **Función `imprimirInventarioHacha`:**
     - Esta función recibe el inventario.
     - Imprime en pantalla el inventario recogido.

        ```text
        Inventario recogido:  
        P1: XXX  
        P2: YYY  
        P3: ZZZ  
        P3: WWW   
        ```

8. **Mostrar el Resumen Final:**

   - **Función `imprimirHacha`:**
     - Esta función recibe el array de hachas fabricadas y el inventario.
     - Imprime en pantalla el resumen final con la cantidad de hachas fabricadas y el material restante.

        ```text
        Resumen final:  
        Material: P1, Hachas fabricadas: XX, Restante: B 
        Material: P2, Hachas fabricadas: YY, Restante: AA  
        Material: P3, Hachas fabricadas: Z, Restante: VV  
        Material: P4, Hachas fabricadas: W, Restante: 0  
        ```

9. **Función `principal`:**

   - Define los materiales y sus propiedades.
   - Muestra un mensaje inicial.
   - Genera una cantidad aleatoria de materiales a recoger.
   - Inicializa el inventario de materiales recogidos con `inicializarInventarioHacha`.
   - Recoge materiales de la mina con `recogerMaterialesHacha`.
   - Imprime el inventario recogido con `imprimirInventarioHacha`.
   - Calcula cuántas hachas se pueden fabricar con el inventario recogido con `calcularHachasFabricadas`.
   - Imprime el resumen final con `imprimirHacha`.

## Ejemplo

### Datos proporcionados en el programa

- **Piedras:** 'Sílex', 'Basalto', 'Granito', 'Obsidiana'
- **Valores:** 50, 75, 100, 150

### Salida del programa

```text
Se ha encontrado una mina de materiales  
La capacidad de recolección de la mina es de: 15 intentos  

Material encontrado: Sílex, Cantidad: 200  
Material encontrado: Basalto, Cantidad: 300  
Material encontrado: Granito, Cantidad: 150  
...  

Inventario recogido:  
Sílex: 600  
Basalto: 900  
Granito: 450  
Obsidiana: 750  

Coste de fabricación del material Sílex: es 50  
Coste de fabricación del material Basalto: es 75  
Coste de fabricación del material Granito: es 100  
Coste de fabricación del material Obsidiana: es 150  

Resumen final:  
Material: Sílex, Hachas fabricadas: 12, Restante: 0  
Material: Basalto, Hachas fabricadas: 12, Restante: 0  
Material: Granito, Hachas fabricadas: 4, Restante: 50  
Material: Obsidiana, Hachas fabricadas: 5, Restante: 0  
```

## Testing

Los tests se ejecutarán en la rama **main**, cuando en el commit se inicie el mensaje como '**deploy:** + MENSAJE DEL COMMIT' y se realice un push.

Los tests que deberá pasar el código para conseguir el descubrimiento serán los siguientes:

1. testInicializarInventarioHacha: Verifica que la función `inicializarInventarioHacha` inicialice correctamente el inventario de hachas.
2. testRecogerMaterialesHacha: Verifica que la función `recogerMaterialesHacha` recoja correctamente los materiales necesarios para fabricar hachas.
3. testCalcularHachasFabricadas: Verifica que la función `calcularHachasFabricadas` calcule correctamente el número de hachas que se pueden fabricar con los materiales disponibles.
4. testCalcularHachasFabricadasConRestos: Verifica que la función `calcularHachasFabricadasConRestos` calcule correctamente el número de hachas fabricadas y los materiales restantes.
5. testImprimirInventarioHacha: Verifica que la función `imprimirInventarioHacha` muestre correctamente el inventario de hachas.
6. testImprimirHacha: Verifica que la función `imprimirHacha` muestre correctamente la información de una hacha.
7. testInventarioVacio: Verifica que la función `inventarioVacio` maneje correctamente el caso en que el inventario está vacío.
8. testRecogerMaterialesConMinimos: Verifica que la función `recogerMaterialesConMinimos` maneje correctamente la recolección de materiales cuando se tienen los valores mínimos.
9. testRecogerMaterialesConMaximos: Verifica que la función `recogerMaterialesConMaximos` maneje correctamente la recolección de materiales cuando se tienen los valores máximos.
10. testCalcularHachasFabricadasExactas: Verifica que la función `calcularHachasFabricadasExactas` calcule correctamente el número exacto de hachas que se pueden fabricar sin restos.
11. testImprimirInventarioHachaVacio: Verifica que la función `imprimirInventarioHachaVacio` maneje correctamente el caso en que el inventario de hachas está vacío.
12. testImprimirHachaSinFabricar: Verifica que la función `imprimirHachaSinFabricar` maneje correctamente el caso en que no se ha fabricado ninguna hacha.
13. testRecogerMaterialesHachaConCero: Verifica que la función `recogerMaterialesHachaConCero` maneje correctamente la recolección de materiales cuando se tienen valores cero.
14. testCalcularHachasFabricadasSinMateriales: Verifica que la función `calcularHachasFabricadasSinMateriales` maneje correctamente el caso en que no hay materiales disponibles para fabricar hachas.
15. testImprimirHachaSoloUnMaterial: Verifica que la función `imprimirHacha` maneje correctamente el caso en que solo se tiene un tipo de material para fabricar hachas.
