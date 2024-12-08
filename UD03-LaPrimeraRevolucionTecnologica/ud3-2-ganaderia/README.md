# UD3.2 - Ganadería

Después de mejorar la capacidad de transporte, Alba observó la necesidad de una fuente constante de alimentos para su tribu. La **Ganadería** permitiría la cría de animales y la producción sostenible de alimentos. Con un nuevo script PHP, Alba unificó todos los inventos para poder calcular sus atributos a partir de su forma y desarrolló los principios geométricos para calcular propiedades como volumen, área y superficie de los inventos.

## Objetivos de la Actividad

- Implementar una estructura de clases que represente un sistema de ganadería y sus dependencias.
- Crear y aplicar un trait que incluya cálculos geométricos para varios inventos.
- Utilizar herencia, interfaces y traits para mejorar la organización y el cálculo de propiedades en los inventos.
- Aplicar conocimientos de figuras geométricas para calcular las propiedades físicas de la cerámica.

## Pasos

1. **Preparación del proyecto**
   - Partir de la solución obtenida en el ejercicio anterior.

2. **Crear el Trait `CalculosGeometricos`:**

   En esta actividad, se implementará un **trait** llamado `CalculosGeometricos` que provee métodos comunes para el cálculo de propiedades geométricas, el cual será utilizado por varias clases de inventos. Este trait incluye métodos para calcular:

   - **Volumen**: El espacio total que ocupa la figura, calculado a partir de sus dimensiones.
   - **Área**: El área ocupada en el plano por una figura geométrica.
   - **Superficie**: La superficie total que recubre una figura geométrica tridimensional.

   - Crea una carpeta llamada `traits` dentro de `src` y dentro de ella un archivo llamado `CalculosGeometricos.php`.

   ### Métodos del Trait

   - Todos los métodos del trait deben devolver un número flotante con dos decimales de precisión.

   #### `volumen(figura, dimensiones)`

   Calcula el volumen de diferentes figuras usando sus dimensiones clave. Las fórmulas utilizadas son:

   - **Cubo**: El volumen se calcula elevando al cubo la longitud de su lado: `lado^3`.
   - **Esfera**: Se calcula usando la fórmula: `(4/3) x PI x radio^3`.
   - **Tetraedro**: Calculado como `1/3 x (0.5 x base x longitud) x altura`.
   - **Cilindro**: Se calcula usando la fórmula: `PI x radio^2 x altura`.
   - **Prisma Rectangular**: Se calcula multiplicando largo, ancho y alto: `longitud x ancho x altura`.
   - En caso de que la figura no esté definida, se lanzará una excepción indicando que la figura no es válida 'Figura no soportada para volumen'.

   #### `area(figura, dimensiones)`

   Calcula el área de varias figuras geométricas en el plano. Las fórmulas aplicadas son:

   - **Cubo**: El área se calcula elevando al cuadrado la longitud de uno de sus lados: `lado^2 * 6`.
   - **Esfera**: El área se obtiene multiplicando 4 por PI por el radio al cuadrado: `4 x PI x radio^2`.
   - **Tetraedro**: Calculada como la suma de las áreas de las cuatro caras triangulares: `base x longitud / 2 + base x altura / 2 + longitud x altura / 2`
   - **Cilindro**: El área del cilindro incluye las dos bases y el área lateral: `2 x PI x radio x altura + (2 x PI x radio^2)`.
   - **Prisma Rectangular**: El área se calcula sumando las áreas de todas las caras: `2 x (longitud x ancho + ancho x altura + longitud x altura)`.
   - En caso de que la figura no esté definida, se lanzará una excepción indicando que la figura no es válida 'Figura no soportada para área'.

   #### `superficie(figura, dimensiones)`

   Calcula la superficie total que recubre la figura tridimensional. Las fórmulas para las figuras son:

   - **Cubo**: La superficie es 6 veces el área de una de sus caras: `6 x lado^2`.
   - **Esfera**: La superficie se calcula con: `4 x PI x radio^2`.
   - **Tetraedro**: La superficie es la suma de las áreas de todas las caras triangulares: `(0.5 x base x longitud) + (0.5 x base x altura) + (0.5 x altura x longitud)`
   - **Cilindro**: Se calcula con la suma de las áreas de las bases y la superficie lateral: `2 x PI x radio x (radio + altura)`.
   - **Prisma Rectangular**: La superficie se obtiene sumando el área de todas las caras: `2 x (longitud x ancho + ancho x altura + longitud x altura)`.
   - En caso de que la figura no esté definida, se lanzará una excepción indicando que la figura no es válida 'Figura no soportada para superficie'.

   ---

   ### Redefinir las Figuras Geométricas y sus Dimensiones

   Cada invento de la siguiente lista deberá implementar el trait `CalculosGeometricos` y disponer de una constante llamada `FIGURA` (y su método get()) de tipo string que indicará el tipo de figura, así como un array llamado `tamanyo` que contenga las dimensiones necesarias para realizar los cálculos geométricos. A continuación, se muestran las asignaciones de figuras y los atributos específicos para cada uno:

   - **Piedra Afilada**: Tetraedro (requiere `base`, `altura`, `longitud`).
   - **Cuerdas**: Cilindro (requiere `radio` que es la mitad del grosor de la cuerda y `altura` es la longitud para su extensión).
   - **Lanza**: Cilindro (requiere `radio` y `altura` para el cilindro "el bastón" y una piedra afilada para la punta).
   - **Arco y Flechas**: Cilindro (requiere `radio` y `altura` para el cilindro que simula el arco). La altura viene marcada por la altura de la cuerda.
   - **Hacha**: Cilindro (requiere `radio` y `altura` para el cilindro "el mango" y una piedra afilada para la cabeza).
   - **Cestas**: Cilindro (requiere `radio`, `altura` y `grosor` para el cilindro, donde `grosor` representa el espesor de las paredes).
   - **Rueda**: Cilindro (requiere `radio` y `altura` que representa la altura de la rueda compacta).
   - **Trampa**: Tamaño equivalente al de la cesta (cilindro) que la contiene.
   - **Refugios**: Prisma rectangular (requiere `longitud`, `ancho`, `altura` y `grosor` para simular el espesor de las paredes).
   - **Carro**: Tamaño equivalente al de la cesta (cilindro) que la contiene.

   > [!WARNING] PD: El array `tamanyo` debe ser sobrescrito en aquellas figuras donde ya exista.

   > [!CAUTION] PD2: La nueva forma de la piedra afilada es un tetraedro. Por lo que el filo no tiene sentido en este caso. Se debe eliminar el atributo `filo` de la clase `PiedraAfilada`. La puntuación de la piedra afilada se calculará usando la siguiente comparación "Si la altura es mas del doble de la base, la puntuación se incrementa en 10, si no, la puntuación es la eficiencia en valor entero".
  
3. **Crear la Clase Ganadería (Nivel 2):**
     - Hereda de la clase `Invento`
     - Usa el trait `CalculosGeometricos`
     - La FIGURA de la ganadería será un prisma rectangular con las dimensiones:
       - La longitud sera suma de las longitudes de los tamaños de los refugios.
       - El ancho será la suma de los anchos de los tamaños de los refugios / numero de refugios.
       - La altura será la suma de las alturas de los tamaños de los refugios / numero de refugios.
       - El grosor será la suma de los grosores de los tamaños de los refugios / numero de refugios.
     - Dispone de los siguientes atributos:
         - `$cuerdas`: 10 Objetos de la clase `Cuerda`.
         - `$piedrasAfiladas`: 10 Objetos de la clase `PiedraAfilada`.
         - `$trampas`: 5 Objetos de la clase `Trampa`.
         - `$refugios`: 2 Objetos de la clase `Refugio`. (El establo y el corral)
         - `$cantidadAnimales`: Un entero que representa la cantidad de animales gestionados. No podrá ser mayor a 10 y menor que 0.
     - La efiencia se calcula como el promedio de la eficiencia de se sus componentes.
     - Los puntos se calculan a partir de la eficiencia + la cantidad de animales gestionada.

4. **Implementar en cada clase:**
      - Muestra la FIGURA, el tamanyo, el volumen, área y superficie en el `toString`.

5. **Función principal**
   - Añadir el invento Ganadería para que se ejecute la función `probarInvento` en la función principal.

## Testing

Los tests se ejecutarán en la rama **main**, cuando el mensaje del commit empiece con '**deploy:** + MENSAJE DEL COMMIT' y se haga un push.

**Tests funciones trait y modificaciones de atributos**

En cada clase hija de `Invento` se debe comprobar que los cálculos de volumen, área y superficie son correctos. Para ello, se deben realizar los siguientes tests:

- testVolumen
- testArea
- testSuperficie

**GanaderíaTest**

- testCalcularEficiencia
- testCalcularPuntuacion
- testToString
- testInventosPrevios
