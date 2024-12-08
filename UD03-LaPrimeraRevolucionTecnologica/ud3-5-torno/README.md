# UD3.5 - Torno

Alba, tras mejorar el proceso de cocción con la alfarería, descubrió que podía optimizar la creación de objetos cerámicos mediante un torno. Este invento le permitiría crear piezas más simétricas y en menos tiempo. Sin embargo, notó que las condiciones ambientales de cada zona afectaban significativamente al tiempo de creación y la calidad del producto final.

## Objetivos de la Actividad

- Implementar un sistema de cálculo de tiempo basado en condiciones ambientales.
- Modificar la clase Material para incluir factores ambientales.
- Actualizar el sistema de cálculo de tiempo en todos los inventos.
- Crear la clase Torno como evolución de la alfarería.

## Pasos

1. **Modificar la clase Material:**

   Añadir el método:
   - `calcularTiempoCreacion(string $zona, int $tiempoBase): int`

   ### Cálculo del Tiempo de Creación

   La función `calcularTiempoCreacion` tiene como objetivo ajustar el tiempo base de creación de un objeto `Material` según las condiciones ambientales de una zona específica. La función toma dos parámetros: `zona`, que indica el nombre que representa las condiciones de la zona, y `tiempoBase`, que es el tiempo inicial estimado para la creación del objeto.

   #### Pasos para Implementar la Función:

   1. **Clase anónima de zona:**
      Desarrolla una función anónima en PHP que simule una zona específica basada en un parámetro de entrada. Esta función debe utilizar un `switch` para determinar el tipo de zona y crear una instancia de una clase anónima `Zona` con propiedades específicas. Las propiedades de la clase `Zona` deben incluir `temperatura`, `humedad`, `viento` y `presion`, con valores asignados aleatoriamente dentro de un rango definido para cada tipo de zona. Los tipos de zona a considerar son: 'bosque', 'selva', 'pradera', 'desierto', 'montaña' y 'polo'.
      - 'bosque' = (rand(10, 25), rand(60, 90), rand(0, 15), rand(950, 1050) % 100)
      - 'selva' = (rand(25, 35), rand(80, 100), rand(0, 10), rand(950, 1050) % 100)
      - 'pradera' = (rand(15, 25), rand(40, 70), rand(5, 20), rand(950, 1050) % 100)
      - 'desierto' = (rand(30, 45), rand(10, 30), rand(10, 25), rand(950, 1050) % 100)
      - 'montaña' = (rand(-10, 10), rand(20, 50), rand(20, 40), rand(950, 1050) % 100)
      - 'polo' = (rand(-50, -10), rand(50, 80), rand(10, 30), rand(950, 1050) % 100)

   2. **Definición de Factores de Ajuste:**
      - **Factor de Temperatura:** Calcula el ajuste basado en la diferencia entre la temperatura de la zona y una temperatura de referencia (20 grados). Se utiliza la fórmula: factorTemperatura = max(0.7, min(1.08, 1 + ((temperatura - 20) / 100) * (1 - min(resistenciaTemperatura, 80) / 100)))
      - **Factor de Humedad:** Ajusta el tiempo según la humedad de la zona, con una referencia del 50%. La fórmula es: factorHumedad = max(0.9, min(1.6, 1 + ((humedad - 50) / 100) * (1 - min(resistenciaHumedad, 80) / 100)))
      - **Factor de Viento:** Reduce el tiempo en función de la velocidad del viento: factorViento = max(0.76, min(1.0, 1 - (viento / 100) * (1 - min(resistenciaViento, 80) / 100)))
      - **Factor de Presión:** Ajusta el tiempo según la presión atmosférica, con una referencia de 1013 hPa: factorPresion = max(0.95, min(1.06, 1 + ((presion - 50) / 1000)))

         >[!NOTE] LOS VALORES:
         >- temperatura: Temperatura en grados Celsius
         >- humedad: Porcentaje de humedad
         >- viento: Velocidad del viento
         >- presion: Presión atmosférica
         >- resistenciaX: Valor de 0 a 100 que representa la resistencia al factor correspondiente
         >- Las funciones max() y min() aseguran que los valores se mantengan dentro de los límites establecidos

   3. **Cálculo del Tiempo Ajustado:**
      - Multiplica el `tiempoBase` por todos los factores de ajuste para obtener el `tiempoAjustado`: tiempoAjustado = tiempoBase x factorTemperatura x factorHumedad x factorViento x factorPresion

   4. **Asegurar un Tiempo No Negativo:**
      - Devuelve el máximo entre `tiempoAjustado` y 0 para evitar tiempos negativos.

2. **Crear la Clase Torno (Nivel 2):**

   - Hereda de la clase `Invento`
   - Implementa la interfaz `Medible`
   - Dispone de los siguientes atributos:
     - `$alfareria`: 1 Objeto de la clase `Alfarería`.
     - `$ceramica`: 1 Objeto de la clase `Cerámica`.
     - `$rueda`: 1 Objeto de la clase `Rueda`.
     - `$velocidadRotacion`: float (Valor entre 1 y 100).
     - `$precision`: float (Valor entre 1 y 100).
   - La FIGURA será un cilindro con las dimensiones igual que la rueda.
   - La eficiencia del torno se calcula como la media de la eficiencia de objetos que lo componen.
   - La puntuación es proporcional a la eficiencia, la velocidad de rotación y la precisión. La fórmula es: eficiencia + (precision / velocidadRotacion) / 10. Redondear a valores enteros.

3. **Modificación del Cálculo de Tiempo:**

   Cada clase hija debe modificar `calcularTiempoCreacion()` considerando:

   1. **Modificadores por Zona:**
      - Para cada material que componga el invento, calcular mediante la función `calcularTiempoCreacion` del material el tiempo de creación ajustado según la zona de creación.
  
   2. **Tiempo Final:**
      - Si el invento requiere más de un material, se debe considerar el tiempo de creación de cada uno. Como se pueden fablicar a la vez, se debe considerar el tiempo máximo de creación de los materiales.
  
4. **Función principal:**
   - Añadir el invento Torno para que se ejecute la función `probarInvento` en la función principal.
   - Mostrar tiempos de creación para diferentes inventos añadiendo la zona.

## Testing

Además de los tests existentes, añadir:

1. **Test de Tiempo de Creación en cada clase:**
   - `testTiempoCreacionZonas`: Verificar cálculos según zona.

2. **Test de Torno:**
   - `testCalcularEficiencia`: Verificar eficiencia.
   - `testCalcularPuntuacion`: Verificar tiempo total.
   - `testToString`: Verificar detalles del torno.
   - `testInventosPrevios`: Verificar dependencias.