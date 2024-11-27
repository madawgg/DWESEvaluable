# UD2.5-Arco y flecha

Al probar ciertos materiales, Isabel se dio cuenta que podría calcular la eficiencia de los arcos y flechas que construía, para ello necesitaba un script PHP que permitiera evaluar y comparar diferentes materiales y técnicas. Este script le permitió a Isabel introducir las propiedades de cada material, como la dureza, flexibilidad, elasticidad y resistencia. Al seleccionar una técnica específica, el script calculaba automáticamente los valores óptimos de cada combinación.

## Objetivos de la Actividad

- Practicar utilizando arrays y funciones en PHP.
- Manejar condiciones y cálculos basados en datos de entrada.
- Presentar los resultados de manera legible para el usuario.

## Pasos

1. **Inicializar Arrays para los Atributos del Arco y la Flecha:**

   - **$materiales:** Una matriz asociativa que contiene diferentes materiales para el arco, la flecha y la cuerda.

2. **Generar Materiales con Valores Aleatorios:**

   - **Función `generarMaterialesArcoFlecha`:**
     - Esta función recibe una categoría y una matriz de materiales.
     - Obtiene las propiedades de cada material con `obtenerPropiedadesPorCategoria`.
     - Genera valores aleatorios del 1 al 9 para cada propiedad de cada material.
     - Devuelve una matriz con los materiales, sus propiedades y los valores para estas.

3. **Definir Arrays para las Técnicas:**

   - **$tecnicas:** Un array con diferentes técnicas ('Técnica de Tallado', 'Técnica de Trenzado', 'Técnica de Secado') y sus métodos de cálculo.

4. **Función `imprimirTecnicasArcoFlecha`:**

   - Esta función recibe el array de técnicas con sus métodos de cálculos.
   - Imprime en pantalla un listado con las técnicas disponibles y sus métodos de cálculo.

        ```text
        Las técnicas disponibles son:  
        - Técnica de Tallado: se calcula a partir de la suma de las propiedades  
        - Técnica de Trenzado: se calcula a partir de la multiplicación de las propiedades  
        - Técnica de Secado: se calcula a partir de la división de las propiedades 
        ```

5. **Función `seleccionarTecnicaAleatoriaArcoFlecha`:**

   - Esta función recibe el array de técnicas.
   - Obtiene las claves del array con el nombre de las técnicas.
   - Selecciona y devuelve una técnica aleatoria.

6. **Función `obtenerPropiedadesPorCategoria`:**

   - Esta función recibe una categoría.
   - Devuelve un array con las propiedades correspondientes a la categoría:
     - Arco (Dureza, Flexibilidad, Resistencia)
     - Flecha (Dureza, Peso, Resistencia)
     - Cuerda (Elasticidad, Resistencia)

7. **Función `mejorMaterialArcoFlecha`:**

   - Esta función recibe una categoría, una matriz de materiales y una técnica.
   - Obtiene las propiedades según la categoría con `obtenerPropiedadesPorCategoria`.
   - Calcula para cada material el valor de dicho material. Para ello, deberá según la técnica seleccionada, sumar, multiplicar o dividir sus propiedades.
   - Para cada material se valorará si su valor es mejor que el de previos materiales, almacenando el mejor valor y material.
   - Devuelve un array con la categoría, el mejor material y su valor.
     - Ejemplo: `['Arco', 'Madera de Tejo', 18]`

8. **Función `imprimirMaterialesArcoFlecha`:**

   - Esta función recibe una categoría y un array de materiales.
   - Imprime en pantalla los materiales y sus propiedades en formato de tabla.

        Materiales de CATEGORÍA:  

        | Material | P1  | P2  | P3  |
        | -------- | --- | --- | --- |
        | M1       | X   | Y   | Z   |
        | M2       | A   | B   | C   |
        | M3       | F   | G   | H   |

9. **Función `imprimirArcoFlecha`:**

   - Esta función recibe un array con el mejor material.
   - Imprime en pantalla el mejor material y su valor.

        ```text
        El mejor material para CATEGORÍA es con el valor XX es: MATERIAL
        ```

10. **Función `principal`:**

    - Inicializa los arrays de materiales para las diferentes categorías:
      - Arco: Madera de Tejo, Bambú, Madera de Sauce
      - Flecha: Madera de Abedul, Madera de Cedro, Madera de Pino
      - Cuerda: Lianas, Tendones de Animales, Fibras de Plantas
    - Genera materiales con valores aleatorios para cada categoría con `generarMaterialesArcoFlecha`.
    - Define las técnicas disponibles.
      - Técnica de Tallado: suma de las propiedades
      - Técnica de Trenzado: multiplicación de las propiedades
      - Técnica de Secado: división de las propiedades
    - Imprime las técnicas disponibles con `imprimirTecnicasArcoFlecha`.
    - Selecciona una técnica aleatoria con `seleccionarTecnicaAleatoriaArcoFlecha`.
    - Imprime los materiales para cada categoría y sus propiedades con `imprimirMaterialesArcoFlecha`.
    - Determina los mejores materiales para el arco, la flecha y la cuerda con `mejorMaterialArcoFlecha`.
    - Imprime los mejores materiales para cada categoría con `imprimirArcoFlecha`.

## Ejemplo

### Datos proporcionados en el programa

- **Materiales del arco:** 'Madera de Tejo', 'Bambú', 'Madera de Sauce'
- **Materiales de la flecha:** 'Madera de Abedul', 'Madera de Cedro', 'Madera de Pino'
- **Materiales de la cuerda:** 'Lianas', 'Tendones de Animales', 'Fibras de Plantas'
- **Técnicas:** 'Técnica de Tallado', 'Técnica de Trenzado', 'Técnica de Secado'

### Salida del programa

```text
Las técnicas disponibles son:  
- Técnica de Tallado: se calcula a partir de la suma de las propiedades  
- Técnica de Trenzado: se calcula a partir de la multiplicación de las propiedades  
- Técnica de Secado: se calcula a partir de la división de las propiedades  

Se ha seleccionado la técnica aleatoria de: Técnica de Tallado  
```

Materiales de Arco:  

| Material        | Dureza | Flexibilidad | Resistencia |
| --------------- | ------ | ------------ | ----------- |
| Madera de Tejo  | 7      | 5            | 6           |
| Bambú           | 6      | 7            | 5           |
| Madera de Sauce | 5      | 6            | 4           |

Materiales de Flecha:  

| Material         | Dureza | Peso | Resistencia |
| ---------------- | ------ | ---- | ----------- |
| Madera de Abedul | 6      | 4    | 5           |
| Madera de Cedro  | 5      | 5    | 6           |
| Madera de Pino   | 4      | 6    | 5           |

Materiales de Cuerda:  

| Material             | Elasticidad | Resistencia |
| -------------------- | ----------- | ----------- |
| Lianas               | 7           | 6           |
| Tendones de Animales | 6           | 7           |
| Fibras de Plantas    | 5           | 5           |

```text
El mejor material para arco es con el valor 18 es: Madera de Tejo  
El mejor material para flecha es con el valor 17 es: Madera de Cedro  
El mejor material para cuerda es con el valor 13 es: Tendones de Animales  
```

## Testing

Los tests se ejecutarán en la rama **main**, cuando en el commit se inicie el mensaje como '**deploy:** + MENSAJE DEL COMMIT' y se realice un push.

Los tests que deberá pasar el código para conseguir el descubrimiento serán los siguientes:

1. testGenerarMaterialesArcoFlecha: Verifica que la función `generarMaterialesArcoFlecha` genere correctamente los materiales necesarios para fabricar un arco y flechas.
2. testSeleccionarTecnicaAleatoriaArcoFlecha: Verifica que la función `seleccionarTecnicaAleatoriaArcoFlecha` seleccione correctamente una técnica aleatoria para fabricar un arco y flechas.
3. testMejorMaterialArcoFlecha: Verifica que la función `mejorMaterialArcoFlecha` determine correctamente el mejor material para fabricar un arco y flechas.
4. testMejorMaterialArcoFlechaValoresExtremos: Verifica que la función `mejorMaterialArcoFlecha` maneje correctamente los valores extremos de los materiales.
5. testImprimirMaterialesArcoFlecha: Verifica que la función `imprimirMaterialesArcoFlecha` muestre correctamente los materiales necesarios para fabricar un arco y flechas.
6. testImprimirArcoFlecha: Verifica que la función `imprimirArcoFlecha` muestre correctamente la información de un arco y flechas.
7. testGenerarMaterialesArcoFlechaSinMateriales: Verifica que la función `generarMaterialesArcoFlecha` maneje correctamente el caso en que no hay materiales disponibles.
8. testGenerarMaterialesArcoFlechaInvalidCategory: Verifica que la función `generarMaterialesArcoFlecha` maneje correctamente el caso en que la categoría de materiales es inválida.
9. testMejorMaterialArcoFlechaTallaNegativeValues: Verifica que la función `mejorMaterialArcoFlecha` maneje correctamente los valores negativos de talla.
10. testMejorMaterialArcoFlechaZeroValues: Verifica que la función `mejorMaterialArcoFlecha` maneje correctamente los valores cero.
11. testObtenerPropiedadesPorCategoria: Verifica que la función `obtenerPropiedadesPorCategoria` obtenga correctamente las propiedades de los materiales por categoría.
12. testMejorMaterialArcoFlechaInvalidTechnique: Verifica que la función `mejorMaterialArcoFlecha` maneje correctamente el caso en que la técnica es inválida.
13. testImprimirTecnicasArcoFlecha: Verifica que la función `imprimirTecnicasArcoFlecha` muestre correctamente las técnicas disponibles para fabricar un arco y flechas.
14. testMejorMaterialArcoFlechaEmptyMaterials: Verifica que la función `mejorMaterialArcoFlecha` maneje correctamente el caso en que la lista de materiales está vacía.
15. testMejorMaterialArcoFlechaEmptyProperties: Verifica que la función `mejorMaterialArcoFlecha` maneje correctamente el caso en que las propiedades de los materiales están vacías.
