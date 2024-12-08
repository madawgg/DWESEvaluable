# UD3.1-Carro

Finalmente, Alba decidió que los cazadores necesitaban una forma más precisa de transportar cargas pesadas a largas distancias. Con la ayuda de un script PHP, Alba pudo construir un carro usando inventos anteriores. El script calculaba la eficiencia del carro a partir de los materiales e inventos necesarios para la construcción del carro, como la rueda, la cuerda, el hacha y la cesta. Gracias a este avance, los cazadores pudieron transportar más recursos, mejorando la eficiencia de la recolección y aumentando las capacidades de la tribu.

## Objetivos de la Actividad

- Implementar una estructura de clases que represente los inventos y sus dependencias.
- Utilizar un array estático que almacene los inventos ya construidos, organizados por nivel.
- Aprender a manejar herencia, interfaces y métodos estáticos en PHP.
- Simular la construcción de un invento complejo basado en dependencias de otros inventos.

## Pasos

1. **Preparación del proyecto**
   - Partir de la solución obtenida en el ejercicio anterior.
   - Material ha sido modificado añadiendo atributos.

2. **Crear la Clase Carro (Nivel 2):**

   - Hereda de la clase `Invento`.
     - Los inventos previos necesarios para construir el carro son: `Rueda`, `Cesta`, `Cuerda`, `Hacha`. Se requieren 2 ruedas, 1 cesta, 1 cuerda y 1 hacha.
  
   - Atributos adicionales:
     - `rueda1`, `rueda2`, `cesta`, `cuerda`, `hacha`: Los objetos creados de cada clase.
  
   - Métodos:
     - `__construct($material, $cuerda, $cesta, $ruedas, $hacha)`: Constructor que inicializa los atributos.
     - `Getters` y `setters` mágicos para los atributos. (Estos deben estar en todas las clases).
     - `toString()`: Retorna una cadena representando los atributos del carro. En caso de ser objeto, se debe llamar al método `toString()` de cada objeto.
     - `calcularEficiencia()`: Método que a partir de la clase material calcula la eficiencia de ensamblaje de los otros objetos con dos decimales. Para calcular la eficiencia el método de la clase material `calcularEficiencia()` debe ser llamado pasandole una matriz asociativa. La matriz asociativa debe tener las siguientes claves:
       - beneficiosos: resistenciaCompresión, resistenciaTraccion, tenacidad.
       - perjudiciales: densidad, coeficienteFriccion.
       - La eficiencia será la media (con dos decimales) entre la eficiencia del ensamblaje, la eficiencia de cada uno de sus componentes.
       - Si se produce algun error será capturado devolviendo una eficiencia de 0.
     - `calcularPuntuacion()`: Método que calcula la puntuación del invento. La puntuación se calcula de la siguiente manera:
       - Redondear la eficiencia.
     - `probarInvento(array $argumentos = [])`: Crea un objeto de la clase y lo imprime. Este metodo no se testea pero es el que se usa en functions y mostrara el resultado de la creación del invento.
  
3. **Invento Previos**
   - Realizar los cambios necesarios en la clases para que en las clases que esten compuestas por otros inventos se indiquen en los inventos previos como un array asociativo con el nombre del invento como clave y la cantidad necesaria como valor. El resto de inventos que no tengan dependencias se mantendrán como un array vacío.

4. **Función principal**
   - Mostrar en la función principal los inventos previos necesarios para la creación del carro.
   - Añadir el invento Carro para que se muestre la ejecucion de la función `probarInvento` en la función principal.

## Testing

Los tests se ejecutarán en la rama **main**, cuando en el commit se inicie el mensaje como '**deploy:** + MENSAJE DEL COMMIT' y se realice un push.

Los tests que deberá pasar el código para conseguir el descubrimiento serán los siguientes:

Para todas las clases de inventos deben tener los siguientes tests:

- testInventosPrevios
- testToString

CarroTest

- testCalcularEficiencia
- testCalcularPuntuacion
- testToString
- testInventosPrevios