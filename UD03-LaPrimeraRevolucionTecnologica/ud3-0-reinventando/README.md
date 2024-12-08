# ACTIVIDAD

## Enunciado

Alba decidió comenzar adaptando los viejos scripts de isabel que usaban funciones y variables únicamente, por un nuevo paradigma más claro y que referenciaba a los objetos que se creaban a partir de los inventos que se realizaban. Para ello, decidió crear una estructura de clases que representara los inventos y sus dependencias.

## Objetivos de la Actividad

- Implementar una estructura de clases que represente los inventos y sus dependencias.
- Aprender a manejar herencia, interfaces y métodos estáticos en PHP.
- Aprender a manejar excepciones en PHP.
- Simular la construcción de un invento complejo basado en dependencias de otros inventos.
- Representar los inventos y sus dependencias en una tabla HTML.

## Pasos

0. **Usar la Clase Material:**
   - La clase Material representa los materiales que se utilizan en los inventos.
   - Esta clase ya esta creada y no se debe modificar.
   - La clase cuenta con el metodo `calcularEficiencia($atributos): float` que recibe una matriz asociativa con los atributos del material beneficiosos y perjudiciales y retorna la eficiencia del material. El formato de la matriz asociativa es el siguiente:

      ```php
        [
          'beneficiosos' => ['atributo 1', 'atributo 2', ...],
          'perjudiciales' => ['atributo 3','atributo 4', ...]
        ]
      ```

   - La clase cuenta con el metodo `getMateriales()` que tiene una matriz de materiales prediseñados que seran los que se usaran en los inventos.

1. **Crear la Clase Invento:**
   - Esta clase no se puede instanciar directamente, solo se puede heredar.
  
   - Atributos comunes a todos los inventos:
     - `nombre`: El nombre del invento.
     - `nivel`: El nivel del invento.
     - `inventos_previos`: atributo de clase que se compone de un array asociativo donde la clave será los **nombres de las clases** de los inventos previos necesarios para construir el invento y el valor será el número de inventos previos necesarios.
     - `puntos`: Los puntos que proporciona el invento. Inicialmente es 0.

   - Métodos:
     - `__construct()`: Constructor que inicializa los atributos.
     - `getters` y `setters` para los atributos. Los atributos de clase no tienen metodo de modificación.
     - `toString(): string`: Retorna una cadena con formato de tabla HTML representando el invento con sus atributos y sus inventos previos.
     - `calcularEficiencia(): float`: Método que calcula la eficiencia del invento. Este método no tiene implementación en la clase padre y debe ser implementado en las clases hijas.
     - `calcularPuntuacion(): int`: Método que calcula la puntuación del invento. Este método no tiene implementación en la clase padre y debe ser implementado en las clases hijas.
     - `probarInvento(array $argumentos = []): void`: Método de clase que verifica el funcionamiento del invento. En la clase padre, este método solo imprime un mensaje `"El invento ha sido probado.<br>"`.
  
2. **Crear las Clase PiedraAfilada:**
   - Esta clase viene de la clase Invento.
  
   - Atributos específicos de la clase:
     - `nombre`: El nombre del invento.
     - `material`: El material de la piedra afilada.
     - `tamanyo`: EL tamño de la piedra afilada, se define como un array asociativo con las claves 'base', 'altura' y 'longitud' con valores reales expresados en cm.
     - `filo`: El filo de la piedra afilada como un número real.

   - Métodos:
     - `__construct(string $nombre, private Material $material, private float $ancho, private float $filo)`: Constructor que inicializa los atributos, calcula la eficiencia y la puntuación.
     - `getters` y `setters` para los atributos.
     - `toString()`: Retorna una cadena representando el invento sus atributos y el material con sus atributos usados.
     - `calcularEficiencia()`: Método que a partir de la clase material calcula la eficiencia del invento con dos decimales. Para calcular la eficiencia el método de la clase material `calcularEficiencia()` debe ser llamado pasandole una matriz asociativa. La matriz asociativa debe tener las siguientes claves:
       - beneficiosos: dureza, tenacidad.
       - perjudiciales: densidad, fragilidad.
       - Si se produce algun error sera capturado devolviendo una eficiencia de 0.
     - `calcularPuntuacion()`: Método que calcula la puntuación del invento. La puntuación se calcula de la siguiente manera:
       - Si el filo de la piedra afilada es menor o igual al 80% del tamaño de la base y mayor o igual al 20% del tamaño de la base, la puntuación es la eficiencia + 10.
       - En caso contrario, la puntuación es la eficiencia.
     - `probarInvento(array $argumentos = [])`: Crea un objeto de la clase y lo imprime. Este metodo no se testea pero es el que se usa en functions y mostrara el resultado de la creación del invento.

3. **Crear las Clase Fuego:**
   - Esta clase viene de la clase Invento.
  
   - Atributos específicos de la clase:
     - `nombre`: El nombre del invento.
     - `metodo`: El método de creación del fuego.
     - `material`: El material de combustión.
   - Atributos calculados:
     - `tiempoBase`: El tiempo base de creación del fuego segun el método.
     - `tiempoEstimado`: El tiempo estimado de creación del fuego. Se calcula multiplicando el tiempo base con el atributo de resistencia de temperatura del material. El valor se redondea al entero más cercano.
     - `inicio`: La hora de inicio de la creación del fuego.
     - `fin`: La hora de fin de la creación del fuego.

   - Métodos:
     - `__construct(string $nombre, private string $metodo, private Material $material)`: Constructor que inicializa los atributos, calcula la eficiencia y la puntuación.
     - `getters` y `setters` para los atributos.
     - `toString()`: Retorna una cadena representando el invento sus atributos y el material con sus atributos usados.
     - `seleccionarMetodoFuego(): int`: Método que selecciona el método de creación del fuego. El método se selecciona de forma aleatoria entre los siguientes métodos: "fricción" (con valores de 10 a 30), "chispa" (con valores de 5 a 20), "lupa" (con valores de 7 a 25). En caso de no existir el método, se asignan 100 minutos.
     - `calcularTiempoFinalFuego(): DateTime`: Método que calcula el tiempo final de creación del fuego. Se calcula sumando el tiempo base con el tiempo estimado.
     - `calcularEficiencia()`: Método que a partir de la clase material calcula la eficiencia del invento con dos decimales. Para calcular la eficiencia el método de la clase material `calcularEficiencia()` debe ser llamado pasandole una matriz asociativa. La matriz asociativa debe tener las siguientes claves:
       - beneficiosos: inflamabilidad, densidadEnergetica.
       - perjudiciales: resistenciaHumedad, fragilidad, resistenciaTemperatura.
       - Si se produce algun error sera capturado devolviendo una eficiencia de 0.
     - `calcularPuntuacion()`: Método que calcula la puntuación del invento. La puntuación se calcula de la siguiente manera:
       - Si el tiempo estimado es menor a 10 minutos, la puntuación es la eficiencia + 10.
       - Si el tiempo estimado es menor a 20 minutos, la puntuación es la eficiencia + 8.
       - Si el tiempo estimado es menor a 30 minutos, la puntuación es la eficiencia + 5.
       - En caso contrario, la puntuación es la eficiencia.
     - `probarInvento(array $argumentos = [])`: Crea un objeto de la clase y lo imprime. Este metodo no se testea pero es el que se usa en functions y mostrara el resultado de la creación del invento.

4. **Crear las Clase Cuerda:**
   - Esta clase viene de la clase Invento.
  
   - Atributos específicos de la clase:
     - `nombre`: El nombre del invento.
     - `longitud`: La longitud de la cuerda.
     - `material`: El material de la cuerda.

   - Métodos:
     - `__construct(string $nombre, private float $longitud, private Material $material)`: Constructor que inicializa los atributos, calcula la eficiencia y la puntuación.
     - `getters` y `setters` para los atributos.
     - `toString()`: Retorna una cadena representando el invento sus atributos y el material con sus atributos usados.
     - `encriptar($text): string`: Método que encripta el texto pasado como argumento. La encriptación se realiza de la siguiente manera:
       - Crea una cadena de encriptación (Salt) usando el texto con los caracteres '!&%' a ambos lados hasta que la longitud del texto sea igual a la longitud de la cuerda.  
       - Devuelve el texto encriptado.
     - `calcularEficiencia()`: Método que a partir de la clase material calcula la eficiencia del invento con dos decimales. Para calcular la eficiencia el método de la clase material `calcularEficiencia()` debe ser llamado pasandole una matriz asociativa. La matriz asociativa debe tener las siguientes claves:
       - beneficiosos: resistenciaTraccion, flexibilidad.
       - perjudiciales: densidad, coeficienteDesgaste.
       - Si se produce algun error sera capturado devolviendo una eficiencia de 0.
     - `calcularPuntuacion()`: Método que calcula la puntuación del invento. La puntuación se calcula de la siguiente manera:
       - Encriptar el nombre del material.
       - Calcular el factor de encriptación que sera el módulo de la longitud entre 10.
       - Ajustar la eficiencia sumandole el nombre encriptado.
       - Redondear la eficiencia.
     - `probarInvento(array $argumentos = [])`: Crea un objeto de la clase y lo imprime. Este metodo no se testea pero es el que se usa en functions y mostrara el resultado de la creación del invento.

5. **Crear las Clase Lanza:**
   - Esta clase viene de la clase Invento.
  
   - Atributos específicos de la clase:
     - `nombre`: El nombre del invento.
     - `longitud`: La longitud de la lanza.
     - `material`: El material del bastón de la lanza.
     - `piedraAfilada`: La piedra afilada de la lanza.
     - `cuerda`: La cuerda de la lanza.
   - Atributos calculados:
     - `eficienciaBaston`: La eficiencia del bastón de la lanza.
   - Métodos:
     - `__construct(string $nombre, private float $longitud, private Material $material, private PiedraAfilada $piedraAfilada, private Cuerda $cuerda)`: Constructor que inicializa los atributos, calcula la eficiencia y la puntuación.
     - `getters` y `setters` para los atributos.
     - `toString()`: Retorna una cadena representando el invento sus atributos, el material con sus atributos usados e imprime los otros inventos usados.
     - `calcularEficiencia()`: Método que a partir de la clase material calcula la eficiencia del bastón con dos decimales. Para calcular la eficiencia el método de la clase material `calcularEficiencia()` debe ser llamado pasandole una matriz asociativa. La matriz asociativa debe tener las siguientes claves:
       - beneficiosos: dureza, tenacidad, resistenciaTraccion.
       - perjudiciales: densidad, fragilidad.
       - La eficiencia sera la media (con dos decimales) entre la eficiencia del bastón, la eficiencia de la piedra afilada y la eficiencia de la cuerda.
       - Si se produce algun error sera capturado devolviendo una eficiencia de 0.
     - `calcularPuntuacion()`: Método que calcula la puntuación del invento. La puntuación se calcula de la siguiente manera:
       - Redondear la eficiencia.
     - `probarInvento(array $argumentos = [])`: Crea un objeto de la clase y lo imprime. Este metodo no se testea pero es el que se usa en functions y mostrara el resultado de la creación del invento.

6. **Crear las Clase ArcoFlecha:**
   - Esta clase viene de la clase Invento.
  
   - Atributos específicos de la clase:
     - `nombre`: El nombre del invento.
     - `material`: El material del arco.
     - `flecha`: La flecha del arco.
     - `cuerda`: La cuerda del arco.
   - Atributos calculados:
     - `eficienciaArco`: La eficiencia del arco.
   - Métodos:
     - `__construct(string $nombre, private Material $materialArco, private Lanza $flecha, private Cuerda $cuerda, private string $tecnica)`: Constructor que inicializa los atributos, calcula la eficiencia y la puntuación.
     - `getters` y `setters` para los atributos.
     - `toString()`: Retorna una cadena representando el invento sus atributos, el material con sus atributos usados e imprime los otros inventos usados.
     - `calcularEficiencia()`: Método que a partir de la clase material calcula la eficiencia del arco con dos decimales. Para calcular la eficiencia el método de la clase material `calcularEficiencia()` debe ser llamado pasandole una matriz asociativa. La matriz asociativa debe tener las siguientes claves:
       - beneficiosos: dureza, flexibilidad, resistenciaTraccion.
       - perjudiciales: densidad, coeficienteDesgaste.
       - La eficiencia sera la media (con dos decimales) entre la eficiencia del arco, la eficiencia de la flecha y la eficiencia de la cuerda.
       - Si se produce algun error sera capturado devolviendo una eficiencia de 0.
     - `calcularPuntuacion()`: Método que calcula la puntuación del invento. La puntuación se calcula de la siguiente manera:
       - Si la técnica es "tallado", la puntuación es la eficiencia + 15.
       - Si la técnica es "trenzado", la puntuación es la eficiencia + 10.
       - Si la técnica es "secado", la puntuación es la eficiencia + 5.
       - En caso contrario, redondear la eficiencia.
     - `probarInvento(array $argumentos = [])`: Crea un objeto de la clase y lo imprime. Este metodo no se testea pero es el que se usa en functions y mostrara el resultado de la creación del invento.

7. **Crear las Clase Hacha:**
   - Esta clase viene de la clase Invento.
  
   - Atributos específicos de la clase:
     - `nombre`: El nombre del invento.
     - `longitud`: La longitud del mango del hacha.
     - `material`: El material del hacha.
     - `piedraAfilada`: La cabeza del hacha.
   - Atributos calculados:
     - `eficienciaMango`: La eficiencia del material del mango.
   - Métodos:
     - `__construct($nombre, private float $longitud, private Material $material, private PiedraAfilada $piedraAfilada)`: Constructor que inicializa los atributos, calcula la eficiencia y la puntuación.
     - `getters` y `setters` para los atributos.
     - `toString()`: Retorna una cadena representando el invento sus atributos, el material con sus atributos usados e imprime los otros inventos usados.
     - `calcularEficiencia()`: Método que a partir de la clase material calcula la eficiencia del mango con dos decimales. Para calcular la eficiencia el método de la clase material `calcularEficiencia()` debe ser llamado pasandole una matriz asociativa. La matriz asociativa debe tener las siguientes claves:
       - beneficiosos: dureza, tenacidad, resistenciaTraccion.
       - perjudiciales: densidad, fragilidad.
       - La eficiencia sera la media (con dos decimales) entre la eficiencia del mango y la eficiencia de la piedra afilada.
       - Si se produce algun error sera capturado devolviendo una eficiencia de 0.
     - `calcularPuntuacion()`: Método que calcula la puntuación del invento. La puntuación se calcula de la siguiente manera:
       - Si la longitud del mango es mayor o igual a la altura de la cabeza del hacha, la puntuación es la es el redondeo de la eficiencia + 10.
       - En caso contrario, la puntuación es el redondeo de la eficiencia.
     - `probarInvento(array $argumentos = [])`: Crea un objeto de la clase y lo imprime. Este metodo no se testea pero es el que se usa en functions y mostrara el resultado de la creación del invento.

8. **Crear las Clase Cesta:**
   - Esta clase viene de la clase Invento.
  
   - Atributos específicos de la clase:
     - `nombre`: El nombre del invento.
     - `material`: El material del hacha.
     - `diametro`: El diámetro de la cesta.
     - `altura`: La altura de la cesta.
     - `grosorMaterial`: El grosor del material de la cesta.

   - Métodos:
     - `__construct(string $nombre, private Material $material, private float $diametro, private float $altura,
        private float $grosorMaterial)`: Constructor que inicializa los atributos, calcula la eficiencia y la puntuación.
     - `getters` y `setters` para los atributos.
     - `toString()`: Retorna una cadena representando el invento sus atributos, el material con sus atributos usados e imprime los otros inventos usados.
     - `calcularEficiencia()`: Método que a partir de la clase material calcula la eficiencia del invento con dos decimales. Para calcular la eficiencia el método de la clase material `calcularEficiencia()` debe ser llamado pasandole una matriz asociativa. La matriz asociativa debe tener las siguientes claves:
       - beneficiosos: flexibilidad.
       - perjudiciales: densidad, fragilidad.
       - Si se produce algun error sera capturado devolviendo una eficiencia de 0.
     - `calcularPuntuacion()`: Método que calcula la puntuación del invento. La puntuación se calcula de la siguiente manera:
       - La puntuación es el redondeo de la eficiencia.
     - `probarInvento(array $argumentos = [])`: Crea un objeto de la clase y lo imprime. Este metodo no se testea pero es el que se usa en functions y mostrara el resultado de la creación del invento.

9. **Crear las Clase Trampa:**
   - Esta clase viene de la clase Invento.
  
   - Atributos específicos de la clase:
     - `nombre`: El nombre del invento.
     - `cuerda`: La cuerda de la trampa.
     - `cesta`: La cesta de la trampa.
     - `arcoflecha`: El arco y flecha de la trampa.
     - `visibilidad`: La visibilidad de la trampa. Se define como un número real entre 0 y 1.

   - Métodos:
     - `__construct(string $nombre, private Cuerda $cuerda, private Cesta $cesta, private ArcoFlecha $arcoflecha, private float $visibilidad)`: Constructor que inicializa los atributos, calcula la eficiencia y la puntuación.
     - `getters` y `setters` para los atributos.
     - `toString()`: Retorna una cadena representando el invento sus atributos e imprime los otros inventos usados.
     - `calcularEficiencia()`: Método que calcula la eficiencia del invento. La eficiencia se calcula de la siguiente manera:
       - La eficiencia sera la media (con dos decimales) entre la eficiencia de la cuerda, la eficiencia de la cesta y la eficiencia del arco y flecha.
       - Si se produce algun error sera capturado devolviendo una eficiencia de 0.
     - `calcularPuntuacion()`: Método que calcula la puntuación del invento. La puntuación se calcula de la siguiente manera:
       - La puntuación es el redondeo de la eficiencia multiplicado por el resultado de 1 menos la visibilidad.
     - `probarInvento(array $argumentos = [])`: Crea un objeto de la clase y lo imprime. Este metodo no se testea pero es el que se usa en functions y mostrara el resultado de la creación del invento.

10. **Crear las Clase Rueda:**
    - Esta clase viene de la clase Invento.

    - Atributos específicos de la clase:
      - `nombre`: El nombre del invento.
      - `radio`: El radio de la rueda.
      - `grosor`: El grosor de la rueda.
      - `material`: El material de la rueda.

    - Métodos:
      - `__construct($nombre, private $radio, private $grosor, private Material $material)`: Constructor que inicializa los atributos, calcula la eficiencia y la puntuación.
      - `getters` y `setters` para los atributos.
      - `toString()`: Retorna una cadena representando el invento sus atributos, el material con sus atributos usados e imprime los otros inventos usados.
      - `calcularEficiencia()`: Método que a partir de la clase material calcula la eficiencia del invento con dos decimales. Para calcular la eficiencia el método de la clase material `calcularEficiencia()` debe ser llamado pasandole una matriz asociativa. La matriz asociativa debe tener las siguientes claves:
        - beneficiosos: resistenciaCompresion, coeficienteDesgaste.
        - perjudiciales: densidad, coeficienteFriccion.
        - Si se produce algun error sera capturado devolviendo una eficiencia de 0.
      - `calcularPuntuacion()`: Método que calcula la puntuación del invento. La puntuación se calcula de la siguiente manera:
        - La puntuación es el redondeo de la eficiencia.
      - `probarInvento(array $argumentos = [])`: Crea un objeto de la clase y lo imprime. Este metodo no se testea pero es el que se usa en functions y mostrara el resultado de la creación del invento.

11. **Crear las Clase Refugio:**
    - Esta clase viene de la clase Invento.

    - Atributos específicos de la clase:
      - `nombre`: El nombre del invento.
      - `materialTecho`: El material del techo del refugio.
      - `materialParedes`: El material de las paredes del refugio.
      - `materialSuelo`: El material del suelo del refugio.
      - `tamanyo`: El tamaño del refugio, se define como un array asociativo con las claves 'base', 'altura' y 'longitud' con valores reales expresados en cm.
      - `grosor`: El grosor de los materiales del refugio.
        - Constantes:
      - `ESPACIO_PERSONAL`: 2 m2 por persona.
        - Atributos calculados:
      - `eficienciaTecho`: La eficiencia del material del techo.
      - `eficienciaParedes`: La eficiencia del material de las paredes.
      - `eficienciaSuelo`: La eficiencia del material del suelo.
      - `superficie`: La superficie del refugio. Se calcula multiplicando la base por la longitud.
      - `capacidadBase`: La capacidad base del refugio. Será un número real.
      - `capacidadPersonas`: La capacidad de personas del refugio. Será un número entero.

    - Métodos:
      - `__construct(string $nombre, private Material $materialTecho, private Material $materialParedes, private Material $materialSuelo, private array $tamanyo, private float $grosor)`: Constructor que inicializa los atributos, calcula la eficiencia, calcula la capacidad de personas y la puntuación.
      - `getters` y `setters` para los atributos.
      - `toString()`: Retorna una cadena representando el invento sus atributos, los materiales con sus atributos usados.
      - `calcularCapacidadPersonas()`: Método que calcula la capacidad de personas del refugio.
        - La capacidad base se calcula dividiendo la superficie entre el espacio personal.
        - La resistencia de los materiales se calcula como el promedio de las resistencias de los materiales (resistenciaCompresion + resistenciaHumedad + resistenciaTemperatura + resistenciaViento) / 4.
        - La resistencia promedio se calcula como el promedio de las resistencias de los materiales de techo, paredes y suelo.
        - La capacidad de personas se calcula con la siguiente formula: max(0, round(capacidadBase - ((1 - (resistenciaPromedio / 100)) * capacidadBase)))
      - `calcularEficiencia()`: Método que a partir de la clase material calcula la eficiencia del invento con dos decimales. Para calcular la eficiencia el método de la clase material `calcularEficiencia()` debe ser llamado pasandole una matriz asociativa. La matriz asociativa debe tener las siguientes claves:
        - beneficiosos: resistenciaCompresion, resistenciaHumedad, resistenciaTemperatura y resistenciaViento.
        - perjudiciales: densidad, fragilidad.
        - Se debera calcular la eficiencia del techo, de las paredes y del suelo.
        - La eficiencia del refugio sera la media (con dos decimales) entre la eficiencia del techo, la eficiencia de las paredes y la eficiencia del suelo.
        - Si se produce algun error sera capturado devolviendo una eficiencia de 0.
      - `calcularPuntuacion()`: Método que calcula la puntuación del invento. La puntuación se calcula de la siguiente manera:
      - Si la capacidad de personas es mayor que el 75% capacidad base, la puntuación es el redondeo de la eficiencia* 1.2.
      - Si la capacidad de personas es mayor que el 50% capacidad base, la puntuación es el redondeo de la eficiencia* 1.1.
      - En caso contrario, la puntuación es el redondeo de la eficiencia.
      - `probarInvento(array $argumentos = [])`: Crea un objeto de la clase y lo imprime. Este metodo no se testea pero es el que se usa en functions y mostrara el resultado de la creación del invento.

## Testing

Los tests se ejecutarán en la rama **main**, cuando en el commit se inicie el mensaje como '**deploy:** + MENSAJE DEL COMMIT' y se realice un push.

Los tests que deberá pasar el código para conseguir el descubrimiento serán los siguientes:

ArcoFlechaTest

- testCalcularEficiencia
- testCalcularPuntuacion
- testToString

CestaTest

- testCalcularEficiencia
- testCalcularPuntuacion
- testToString

CuerdaTest

- testCalcularEficiencia
- testEncriptar
- testCalcularPuntuacion
- testToString

FuegoTest

- testSeleccionarMetodoFuego
- testCalcularTiempoFinalFuego
- testCalcularEficiencia
- testCalcularPuntuacion
- testToString

HachaTest

- testCalcularEficiencia
- testCalcularPuntuacion
- testToString

LanzaTest

- testCalcularEficiencia
- testCalcularPuntuacion
- testToString

MaterialTest

- testCalcularEficiencia
- testCalcularMaximo
- testCalcularMinimo
- testToString

PiedraAfiladaTest

- testCalcularEficiencia
- testCalcularPuntuacion
- testToString

RefugioTest

- testCalcularEficiencia
- testCalcularCapacidadPersonas
- testCalcularPuntuacion
- testToString

RuedaTest

- testCalcularEficiencia
- testCalcularPuntuacion
- testToString

TrampaTest

- testCalcularEficiencia
- testCalcularPuntuacion
- testToString

</div>
