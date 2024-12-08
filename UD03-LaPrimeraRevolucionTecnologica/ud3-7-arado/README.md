# UD3.7 - Arado

Alba, después de desarrollar la agricultura como un invento útil, se dio cuenta de que necesitaba una herramienta más eficiente para preparar la tierra. Combinando sus conocimientos previos, desarrolló el arado utilizando ruedas, un hacha y su invención de la agricultura. Además, decidió crear una interfaz que le permitiera gestionar mejor la creación de sus inventos.

## Objetivos de la Actividad

- Crear la clase Arado como evolución de la agricultura
- Implementar una interfaz web para la gestión de inventos
- Crear un sistema dinámico de formularios basado en los atributos de cada invento
- Implementar un controlador para la creación de inventos

## Pasos

1. **Crear la Clase Arado (Nivel 2):**

     - Hereda de la clase `Invento`
     - Implementa la interfaz `Medible`
     - Usa el trait `CalculosGeometricos`
     - Dispone de los siguientes atributos:
       - `$material`: 1 Objeto de la clase `Material`.
       - `$ruedas`: 2 Objetos de la clase `Ruedas`.
       - `$hacha`: 1 Objetos de la clase `Hacha`.
       - `$agricultura`: 1 Objeto de la clase `Agricultura`.
       - El resto de atributos que requiere el padre.
     - La `FIGURA` será un `prisma_rectangular`.
     - El `NIVEL` será `2`.
     - La matriz asociativa incluye:
        - beneficiosos: `dureza`, `resistenciaCorrosion`, `tenacidad`.
        - perjudiciales: `densidad`, `coeficienteDesgaste`.
     - Los puntos se calculan a partir de la eficiencia a la que se le incrementan o decrementan tantos puntos como devuelva la operación de:
       - Número animales (elementos de ganadería de la instancia de agricultura) - Número cultivos (elementos de agricultura).
     - El peso se calculara como el peso de los materiales y el peso de las ruedas y el hacha.
     - En el tamaño, la atura será el diámetro de las ruedas, el ancho será la altura de las dos ruedas más el diámetro del hacha y el largo será dos veces la altura del hacha.

    1. **Atributos:**
        - Heredados de la clase `Invento`
        - Inventos previos necesarios: 2 `Ruedas`, 1 `Hacha`, 1 `Agricultura`
        - Implementa la interfaz `Medible`

    2. **Métodos:**
        - `__construct($ruedas, $hacha, $agricultura, $zonaCreacion, $metodoCreacion)`
        - Getters y Setters para cada atributo
        - `toString()`: Retorna los detalles del arado
        - `calcularEficiencia()`: Calcula la eficiencia del arado considerando:
          - beneficiosos: `dureza`, `resistenciaCorrosion`, `tenacidad`.
          - perjudiciales: `densidad`, `coeficienteDesgaste`.
        - `calcularPuntuacion()`: Calcula la puntuación del invento basada en la eficiencia
        - `calcularTiempoCreacion()`: Calcula el tiempo según el número de componentes
        - `probarInvento()`: Crea una instancia y muestra resultados
        - Los métodos de `Medible`

2. **Tipos de campos de la clase**
    - Añadir atributo 'campos' a la clase Invento de tipo estático.
    - Sobre escribir el atributo 'campos' en cada clase hija con los campos necesarios para el formulario de cada invento.

      Ejemplo:

      ```php
        //En la clase Rueda
        $campos = [
          ['nombre' => 'material', 'tipo' => 'select', 'variable' => 'materiales'],
          ['nombre' => 'nombre', 'tipo' => 'text'],
          ['nombre' => 'radio', 'tipo' => 'number'],
          ['nombre' => 'altura', 'tipo' => 'number'],
          ['nombre' => 'zona', 'tipo' => 'select', 'variable' => 'zonas'],
          ['nombre' => 'tecnica', 'tipo' => 'select', 'variable' => 'tecnicas'],
        ];

        //En la clase Trampa
        $campos = [
          ['nombre' => 'cuerda', 'tipo' => 'select', 'variable' => 'Cuerda'],
          ['nombre' => 'cesta', 'tipo' => 'select', 'variable' => 'Cesta'],
          ['nombre' => 'arcoFlecha', 'tipo' => 'select', 'variable' => 'ArcoFlecha'],
          ['nombre' => 'nombre', 'tipo' => 'text'],
          ['nombre' => 'visibilidad', 'tipo' => 'number'],
          ['nombre' => 'zona', 'tipo' => 'select', 'variable' => 'zonas'],
          ['nombre' => 'tecnica', 'tipo' => 'select', 'variable' => 'tecnicas'],
          ['nombre' => 'númeroElementos', 'tipo' => 'number']
        ];

      ```

3. **Crear la Estructura de Inventos en `functions.php`:**

   - Crear la función `obtenerInventos()` que devuelva una matriz asociativa con los nombres de los inventos como clave y un array vació como valor.
     >[!WARNING] Los inventos deben ser creados en el array en el orden en el que se descubrieron para que no haya problemas de dependencias.
   - Crea una función para cada tipo de campo (materiales `obtenerMateriales()`, zonas `obtenerZonas()`, técnicas `obtenerTecnicas()`) que devuelva un array con los valores posibles.
     - El array de materiales se obtendrá de la clase Material con la función `getMateriales()`.
     - El array de zonas mostrará las mismas zonas que existen en la función `calcularTiempoCreacion()` de la clase Material.
     - El array de técnicas será un array con los métodos de creación (tradicional, rápido, detallado).

4. **Añadir función `inicializarInventos(array $inventos, array $materiales, array $zonas, array $tecnicas, int $numero): array` en `functions.php`:**
   - Recibe los inventos, materiales, zonas y técnicas.
   - Para cada invento, utiliza la función `generarInvento()` incluida en `functions.php` para crear n instancias de cada invento.
  
5. **Añadir función `cargarVistaInventos($inventos): void` en `functions.php`:**
   - Crear una tabla HTML con los inventos disponibles desde el array de Inventos.
   - Las columnas tendran una cabecera con 'Nombre del Invento' y 'Acciones'.
   - Cada fila contendrá:
      - Nombre del invento (el mismo nombre que la clase).
      - Enlace "Crear Invento" que pasa el nombre por GET a `formulario.php`

6. **Añadir función `cargarVistaFormulario(array $inventos, array $materiales, array $zonas, array $tecnicas): void` en `functions.php`:**
   - Capturar el nombre del invento por GET
   - Si el nombre del invento existe en el array de inventos:
     - Usando el nombre del invento recibe los datos del atributo 'campos' de la clase correspondiente.
     - Inicializar los inventos disponibles con la función `inicializarInventos()`.
     - Llama a la función `generarFormulario(string $invento, array $campos, array $inventos, array $materiales, array $zonas, array $tecnicas)` que recibe el invento, los inventos, los materiales, las zonas y las técnicas.
   - Si el nombre del invento no existe:
     - Mostrar un parrafo con un mensaje de error 'Invento no válido'.
     - Mostrar un enlace para volver a la página principal `index.php`.

7. **Añadir función `generarFormulario(string $invento, array $campos, array $inventos, array $materiales, array $zonas, array $tecnicas): void` en `functions.php`:**
    - Crear un formulario con los campos del invento.
    - Cada input debe tener un `name` con el nombre del atributo, y un `id` con el nombre del atributo y un `label` con el nombre del atributo.
    - Botón de submit para enviar a `InventoController.php` mediante POST.
    - Indicar el tipo de invento en un campo oculto.
    - Cargar los datos de los select con los arrays definidos en `functions.php`.
    - Si el campo es de tipo select, y corresponde a un invento, deberá dar la opción de selección múltiple.
    - Las opciones de los select mostraran el nombre de los elementos y recogerán su posición en el array como valor seleccionado.

8. **Crear la Interfaz Web:**

    - **`index.php`:**
      - Cargo los inventos.
      - Usa la función `cargarVistaInventos($inventos)` para mostrar los inventos disponibles.

    - **`formulario.php`:**
      - Cargo los inventos, materiales, zonas y técnicas.
      - Usa la función `cargarVistaFormulario($invento, $materiales, $zonas, $tecnicas)` para mostrar el formulario del invento seleccionado.

9. **Función Principal:**

    - La función principal debe eliminarse del index.

## Ejemplo de flujo de la aplicación

- Primero cargar "cargarVistaInventos()". Aparecerá una tabla con todos los tipos de inventos a poder crear.
- Se selecciona el nombre del tipo de invento (por ejemplo 'PiedraAfilada') se envía a la página de formulario.php
- En la página de formulario.php se carga "cargarVistaFormulario()".
- En "cargarVistaFormulario()" se busca si el tipo de inventos, seleccionado en la página anterior, esta en el array de $inventos (obtenido con el método obtenerInventos()).
- Si existe dicho tipo de invento en el array asociativo (PiedraAfilada=> [], ...) de inventos, se llama a sus campos y se inicializa el array usando la función "inicializarInventos()".
  
  >[!NOTE] Aclaración
  A esta función se le pasa un valor entero como "n" que indica el número de inventos aleatorios que se desean crear. El valor de "n" debe ser superior a los inventos necesarios para construir otros inventos (por ejemplo, si un invento requiere 20 inventos de otro tipo, "n" debería ser 20 o superior).
- Una vez se han inicializado los inventos, se llama a la función "generarFormulario()" quien crea un formulario mostrando los inputs y selects para crear el invento.

  >[!WARNING] En este ejercicio no se espera que se realice un formulario funcional, solo la estructura HTML con los campos necesarios.

## Testing

Los tests se ejecutarán en la rama main cuando el mensaje del commit comience con 'deploy: + MENSAJE DEL COMMIT' y se haga un push.

### Tests Necesarios

- **Test de los métodos de Arado:**
  - `testCalcularPeso()`: Comprobar cálculo de peso
  - `testCalcularVolumen()`: Verificar cálculo de volumen
  - `testCalcularArea()`: Comprobar cálculo de área
  - `testCalcularSuperficie()`: Validar cálculo de superficie
  - `testCalcularEficiencia()`: Verificar cálculo de eficiencia
  - `testCalcularPuntuacion()`: Comprobar cálculo de puntuación
  - `testToString()`: Validar salida de detalles
  - `testObtenerTamanyo()`: Comprobar cálculo de tamaño

- **Test de Interfaz Web:**
  - `testGenerarFormulario()`: Comprobar generación de formulario
  - `testInicializarInventos()`: Verificar inicialización de inventos
  - `testCargarVistaInventos()`: Comprobar carga de inventos
  - `testCargarVistaFormularioInventoValido()`: Validar carga de formulario
  - `testCargarVistaFormularioInventoNoValido()`: Comprobar mensaje de error
