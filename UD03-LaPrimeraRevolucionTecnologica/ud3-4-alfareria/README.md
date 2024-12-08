# UD3.4 - Alfarería

Alba, tras dominar la creación de recipientes de cerámica básicos, observó que podía mejorar el proceso de cocción utilizando un horno especializado. Esto le permitiría alcanzar temperaturas más altas y controlar mejor el proceso de cocción. Con este propósito, creó la **Alfarería**, utilizando como base la **Cerámica**, el **Fuego** y los **Refugios**. Durante sus experimentos, Alba descubrió que el tiempo de creación de las piezas variaba significativamente según el método de creación utilizado.

## Objetivos de la Actividad

- Implementar una estructura de clases que represente el sistema de alfarería y sus dependencias.
- Implementar un método abstracto para calcular el tiempo de creación en todos los inventos.
- Gestionar el tiempo de creación basado en la zona y el método de construcción.
- Simular la implementación de un invento basado en componentes de otros inventos.

## Pasos

1. **Actualizar la Clase Invento:**

   Añadir los siguientes atributos protegidos:
   - `zonaCreacion`: string
   - `metodoCreacion`: string
   - `tiempoInicial`: DateTime - Inicializar con la fecha y hora de creación
   - `tiempoCreacion`: int - Tiempo de creación en segundos
   - `tiempoFinal`: DateTime

   Añadir el método abstracto:
   - `abstract public function calcularTiempoCreacion(): int;`

   Añadir los siguientes métodos:
   - `public function calcularTiempoTotal($tiempoCreacion): DateTime`: Calcula el tiempo total de creación. 
  
      > [!TIP] CONSEJO
      >
      >El metodo `calcularTiempoTotal($tiempoCreacion)` se puede generar partiendo del metodo `calcularTiempoFinalFuego` añadir el método a la clase Invento para que este disponible para todos los inventos que hereden de ella. Actualizar Fuego para que use el nuevo método.

2. **Modificar Constructores de todas las Clases Hijas:**

   Actualizar los constructores para incluir:
   - Parámetros adicionales:
     - `zonaCreacion`: string - Valor por defecto: null
     - `metodoCreacion`: string - Valor por defecto: null
   - Utilizar el operador `??` para asignar valores en caso de null.
     - `zonaCreacion`: "pradera"
     - `metodoCreacion`: "tradicional"
   - Calcular y establecer `tiempoFinal` usando el método `calcularTiempoCreacion()`
   - Asegurarse que eficiencia se calcula antes de la creacion del tiempo o establecer la eficiencia a 0 en el padre, e incrementar la eficiencia con el metodo `calcularEficiencia()`.

   > [!TIP] CONSEJO
   > ¿Por qué no asignamos directamente la zonaCreacion y el metodoCreacion en el constructor y usamos el operador ?? para asignar valores por defecto? Esto se debe a que si asignamos valores directamente en el constructor, nos obliga a pasar estos valores en el constructor de las clases hijas, lo que no es necesario. Al usar el operador ??, si no se pasa un valor, se asigna el valor por defecto.
   >
   >Del mismo modo, para evitar errores 'Warning: Undefined array key...' en `probarInvento(array $argumentos = [])` se puede asignar en el constructor, `$argumentos[X] ?? null`. Esto lo hacemos para poder enviar o no las varibles de `zonaCreacion` y `metodoCreacion` y que no de error al intentar acceder a una clave que no existe.

3. **Modificar clases Fuego:**

   - El metodo `calcularTiempoCreacion()` sera el metodo que se encargue de calcular el tiempo de creación de cada invento, este metodo podrá ser diferente para cada invento, por lo que deberemos implementarlo en cada clase hija. En la clase **Fuego** dicho método substituira al metodo `seleccionarMetodoFuego();` que se usaba para calcular el tiempo base. El tiempo estimado de fuego seguira calculandose de la misma forma que antes.

   - Los atributos `$inicio`, `$fin`, `$tiempoBase` ($tiempoCreacion) se heredaran desde la clase padre. El atributo `$metodoFuego` se eliminara de la clase Fuego y se usara el atributo `$metodoCreacion`.

4. **Implementación del Cálculo de Tiempo:**

   Cada clase hija debe implementar `calcularTiempoCreacion()` considerando:

   1. **Tiempo Base según Método:**
      - Tradicional: 60 segundos x nivel del invento.
      - Rápido: -25% del tradicional, pero -10 puntos de eficiencia.
      - Detallado: +50% del tradicional, pero +10 puntos de eficiencia.

5. **Metodos calcularPuntuacion:**

   Los métodos `calcularPuntuacion()` de las clases hijas deben ser actualizados para considerar la posibilidad de obtener una eficiencia negativa a poderse decrementar por el metodo de creación. Por ello, se debe comprobar que la eficiencia no sea menor que 0. En caso de serlo la puntuacion ha de ser 0.

6. **Crear la Clase Alfarería (Nivel 2):**

   - Hereda de la clase `Invento`
   - Usa el trait `CalculosGeometricos`
   - Implementara la interfaz `Medible`
   - Dispone de los siguientes atributos:
     - `$material`: 1 Objeto de la clase `Material`.
     - `$fuego`: 1 Objeto de la clase `Fuego`.
     - `$ceramica`: 1 Objeto de la clase `Ceramica`.
     - `$refugio`: 1 Objeto de la clase `Refugio`.  
   - La FIGURA será un prisma rectangular con las dimensiones igual que el refugio.
   - La matriz asociativa incluye:
      - beneficiosos: `resistenciaTemperatura`, `coeficienteDesgaste`
      - perjudiciales: `fragilidad`, `inflamabilidad`
   - Los puntos se calculan a partir de la eficiencia en valores enteros.

7. **Función principal:**
   - Añadir el invento Alfarería para que se ejecute la función `probarInvento` en la función principal.
   - Mostrar tiempos de creación para diferentes inventos el método de creación.

## Testing

Además de los tests existentes, añadir:

1. **Test de Tiempo de Creación en cada clase:**
   - `testCalcularTiempoCreacion`: Verificar el tiempo total de creación segun el método de creación.
   - `testCalcularTiempoTotal`: Verificar el tiempo total de creación.

2. **Test de Alfarería:**
   - `testCalcularEficiencia`: Verificar eficiencia.
   - `testCalcularPuntuacion`: Verificar tiempo total.
   - `testToString`: Verificar detalles de la alfarería.
   - `testInventosPrevios`: Verificar dependencias.