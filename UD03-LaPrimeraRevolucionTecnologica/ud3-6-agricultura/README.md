# UD3.6 - Agricultura

Alba, tras mejorar sus técnicas de almacenamiento y producción con diversos inventos, descubrió que podía optimizar la producción de alimentos mediante el cultivo sistemático de plantas. Este invento, la agricultura, requeriría gestionar eficientemente el espacio disponible y calcular las capacidades de almacenamiento de diversos contenedores. Sin embargo, notó que el tipo de material y las dimensiones afectaban significativamente a la capacidad real de almacenamiento.

## Objetivos de la Actividad

- Implementar un sistema de cálculo de capacidad para diferentes formas geométricas.
- Crear una subclase común para cálculos de almacenamiento.
- Reconocer como hacer clases genericas desde clases especializadas.
- Modificar las clases existentes para estandarizar los atributos y métodos comunes.
- Actualizar el sistema de cálculo de capacidad en todos los inventos relevantes.

## Pasos

1. **Crear la clase Almacenamiento:**

   - La clase `Almacenamiento` debe proveer métodos comunes para el cálculo de capacidad de almacenamiento según diferentes formas geométricas.
   - Esta clase no es un invento, sino una clase base para los inventos que requieran cálculos de capacidad. Por lo tanto, no se instanciará.
   - Usa el trait de `CalculosGeometricos` para calcular las propiedades de las figuras.
   - Implementa las interfaces `Medible`.
   - Dispone de los siguientes atributos:
     - `FIGURA_ELEMENTO`: el tipo de figura geométrica que representa un elemento. Su valor sera el mismo para todos los elementos de los inventos. Por defecto será un cubo.
     - `$numeroElementos`: número de elementos en el invento. Por defecto será 0. Será un número entero.
     - `$tamanyo`: un array con las dimensiones del invento.
     - Además, recibe los atributos de su padre `Invento`.

   ### Método capacidad

   - `capacidad(figura, dimensiones): int`: Calcula la capacidad efectiva considerando el grosor de las paredes.

   #### Fórmulas de Capacidad

   - **Cubo**: `(lado - 2 x grosor)^3`
   - **Esfera**: `(4/3) x PI x (radio - grosor)^3`
   - **Tetraedro**: `(1 / 3) x 0.5 x (base - 2 x grosor) x (longitud - 2 x grosor) x (altura - 2 x grosor)`
   - **Cilindro**: `PI x (radio - grosor)^2 x (altura - 2 x grosor)`
   - **Prisma Rectangular**: `(largo - 2 x grosor) x (ancho - 2 x grosor) x (altura - 2 x grosor)`

   ### Método calcularCapacidad

   - `calcularCapacidad(): int`: Devuelve la capacidad total del invento.
   - El método calcula la capacidad total del invento y la divide por el volumen de un elemento para obtener el número de elementos que caben en el invento.

   ### Método calcularEspacioDisponible

   - `calcularEspacioDisponible(): int`: Devuelve el numero de elementos que aun caben en el invento.
   - A partir de la capacidad total del invento le resta el numero de elementos actuales.

   ### Método calcularPorcentajeLleno

   - `calcularPorcentajeLleno(): int`: Devuelve el porcentaje de llenado del invento.
   - Calcula la capacidad total del invento y le resta el espacio que queda disponible. El resultado lo divide por la capacidad total. Fianlmente multiplica el resultado por 100 y lo redondea a entero.
   - Si la capacidad total es 0, el porcentaje de llenado será 100.
  
   ### Método toString

   - `toString(): string`: Devuelve una cadena con los detalles del invento relacionados con la capacidad. Por ejemplo:
   - Ejemplo de salida:
  
     ```plaintext
     Figura: Prisma rectangular
     Tamaño: Largo: XXX m, Ancho: YYY m, Alto: ZZZ m, Grosor: GG m
     Tamaño elemento (lado): XXX m
     Número de Elementos: Z elementos
     Capacidad: YY elementos
     Espacio Disponible: NN elementos
     Porcentaje Lleno: JJ%
     ```
  
   ### Otros Métodos

    - `getFigura(): string`: Devuelve el tipo de figura geométrica del invento.
    - `getTamanyoElemento(): int`: Devuelve el tamaño de un elemento de los que podran almacenarse en el invento.

2. **Modificar CalculosGeometricos:**

    - Modificar el trait para devolver valores flotantes sin redondear.
    - Asegurarse de tener las fórmulas correctas del volumen para las figuras geométricas:
      - **Cubo**: `lado^3`
      - **Esfera**: `(4/3) x PI x radio^3`
      - **Tetraedro**: `1/3 x (0.5 x base x longitud) x altura`
      - **Cilindro**: `PI x radio^2 x altura`
      - **Prisma Rectangular**: `longitud x ancho x altura`

3. **Modificar las Clases Existentes:**
    - Para facilitar la implementación y el seguimiento de los cambios, las clases de los inventos existentes deben ser modificadas con los siguienes cambios:
      - Dispondran de una constante `FIGURA` (si tienen forma fija), y una constante `NIVEL` que indicara su nivel.
      - Los atributos no estáticos seran protected.
      - Los `constructores` de las clases siempre recibiran el material, los objetos necesarios, el nombre, atributos propios (si los tienen), el tamaño (si lo tienen), la zona de creación y el método.
      - El método `calcularPeso()` usara el array de `densidadesReales` de la clase `Material` para obtener el peso mas realista de los materiales. El resto de métodos que usan densida seguiran usando el atributo densidad de la clase `Material`.
      - El método `__toString()` devolverá una cadena con los detalles del invento como se muestra en el PDF. Para unificar los datos de un modo mas comprensible, se devolvera: (***Actualizaciones Opcionales***)
        - El `peso` en kg (esta en gramos)
        - El `volumen` en m3 (esta en cm3)
        - El `area` en m2 (esta en cm2)
        - Las `figuras` se mostraran con la primera letra en mayuscula y sin guiones bajos.
        - Se eliminara la `supeficie` del texto.
        - El `tiempo de creación` se mostrarán en segundos.
        - El resto de tiempos se mostrará como DateTime.
        - El resto de atributos se mostrarán como se indica en el PDF.
  
4. **Crear la Clase Agricultura (Nivel 2):**

     - Hereda de la clase `Almacenamiento`
     - Dispone de los siguientes atributos:
       - `$material`: 1 Objeto de la clase `Material`.
       - `$cestas`: 10 Objetos de la clase `Cesta`.
       - `$lanzas`: 20 Objetos de la clase `Lanza`.
       - `$ganaderia`: 1 Objeto de la clase `Ganadería`.
       - `TAMANYO_ELEMENTO`: constante con el valor del tamaño de un elemento. Será 300.
       - El resto de atributos que requiere el padre.
     - La `FIGURA` será un `prisma_rectangular`.
     - El `NIVEL` será `2`.
     - La matriz asociativa incluye:
       - beneficiosos: `dureza`, `resistenciaUV`, `tenacidad`.
       - perjudiciales: `densidad`, `coeficienteDesgaste`.
     - Los puntos se calculan a partir de la eficiencia del sistema agrícola redondeandolo a entero.

    > [!TIP] Aclaración
    > Todos los elementos de las clases `numeroElementos` son cubos. Por lo tanto, el atributo `TAMANYO_ELEMENTO` será el tamaño de un lado de un cubo en cm.

5. **Modificar las Clases Existentes:**

    - Las siguientes clases deben usar la nueva clase `Almacenamiento` por lo que aquellos atributos, métodos, traits e interfaces comunes deben ser de la clase padre.
    - Además, las clases hijas deben tener un atributo `grosor` en su tamanyo.
    - Todas deberán contar con la constante `TAMANYO_ELEMENTO`: el tamaño que tendrá cada elemento que contendrá en cm. Los tamaños serán los siguientes:

      - `Cesta`: sus elementos tienen un tamaño de 20 cm.
      - `Trampa`: sus elementos tienen un tamaño de 30 cm.
      - `Refugio`: sus elementos tienen un tamaño de 200 cm.
      - `Carro`: sus elementos tienen un tamaño de 100 cm.
      - `Cerámica`: sus elementos tienen un tamaño de 10 cm.
      - `Alfarería`: sus elementos tienen un tamaño de 50 cm.
      - `Ganadería`: sus elementos tienen un tamaño de 350 cm.
      - `Agricultura`: sus elementos tienen un tamaño de 300 cm.

      > [!WARNING] GANADERIA
      > - En Ganadería, el atributo `cantidadAnimales` se substituye por el número de elementos.

      > [!WARNING] REFUGIO
      > - La constante `ESPACIO_PERSONAL` se substituye por el `TAMANYO_ELEMENTO`.
      > - El método `calcularCapacidadPersonas()` se substituye por `calcularCapacidad()`. Los calculos se realizaran en base a las dimensiones del refugio como el resto de las clases, en lugar de la resistencia de los materiales.
      > - La punutación se calculará en base al poercetaje de llenado del refugio:
      >   - Si el porcentaje de llenado es mayor o igual al 75%, la puntuación será la eficiencia multiplicada por 1.2.
      >   - Si el porcentaje de llenado es mayor o igual al 50%, la puntuación será la eficiencia multiplicada por 1.1.
      >   - En cualquier otro caso, la puntuación será la eficiencia.

6. **Función Principal:**

- Añadir el invento Agricultura al sistema principal
- Ejecutar y mostrar resultados de `probarInvento`

## Testing

Los tests se ejecutarán en la rama **main** cuando el mensaje del commit comience con '**deploy:** + MENSAJE DEL COMMIT' y se haga un push.

### Tests Necesarios

1. **Test de Capacidad en la clases que hereden de Almacenamiento:**

   - `testCalcularCapacidad`: Verificar cálculos de capacidad para diferentes formas
   - `testCalcularEspacioDisponible`: Comprobar cálculo de espacio disponible
   - `testCalcularPorcentajeLleno`: Validar cálculo de porcentaje de llenado

2. **Test de Agricultura:**

   - `testCalcularEficiencia`: Verificar cálculo de eficiencia
   - `testCalcularPuntuacion`: Comprobar cálculo de puntuación
   - `testCalcularPeso`: Validar cálculo de peso
   - `testCalcularVolumen`: Verificar cálculo de volumen
   - `testCalcularArea`: Comprobar cálculo de área
   - `testCalcularSuperficie`: Validar cálculo de superficie
   - `testCalcularTiempoCreacion`: Verificar cálculo de tiempo de creación
