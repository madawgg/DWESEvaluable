# UD3.8 - Fermentación

Alba, en su búsqueda de mejorar las técnicas de conservación de alimentos, decidió desarrollar un proceso de fermentación. Este invento aprovecharía diversos recursos y conocimientos adquiridos previamente para crear productos fermentados de manera eficiente. Alba se dio cuenta del coste y la complejidad de este proceso, por lo que decidió crear una interfaz web que le permitiera gestionar la creación de sus inventos de forma más eficiente. Para ello, necesitaría gestionar los inventos a partir de los inventos previos de forma automática y dinámica.

## Objetivos de la Actividad

- Crear la clase Fermentación como parte de la evolución agrícola.
- Implementar una interfaz web para la gestión de inventos.
- Implementar un controlador para la creación de inventos, utilizando inventos previos.

## Pasos

1. **Crear la Clase Fermentación (Nivel 2):**
    - Hereda de la clase `Invento`
    - Dispone de los siguientes atributos:
       - `$material`: 1 Objeto de la clase `Material`.
       - `$alfareria`: 2 Objetos de la clase `Alfareria`.
       - `$trono`: 1 Objetos de la clase `Torno`.
       - `$agricultura`: 2 Objeto de la clase `Agricultura`.
       - `$tiempoMinimo`: el tiempo mínimo de fermentación desde la creación. Por defecto 3600 segundos.
       - `$tiempoMaximo`: el tiempo máximo de consumo del producto. Por defecto 172800 segundos.
       - El resto de atributos que requiere el padre.
    - El `NIVEL` será `2`.
    - La matriz asociativa incluye:
          - beneficiosos: `resistenciaQuimica`, `resistenciaHumedad`.
          - perjudiciales: `porosidad`, `toxicidad`.
    - Los puntos se calculan a partir de la eficiencia a la que se le incrementan o reducen tantos puntos como la media de árboles (número de elementos) que contengan las agriculturas.

2. **Añadir función `function validarDatos($datosEntrada): array` en `functions.php`:**
    - Comprueba si el campo 'invento' existe y no esta vacío.
      - Cargar los datos de los campos del invento correspondiente.
      - Carga los inventos previos.
      - Para cada campo, validar todos los datos recibidos:
        - Comprobamos si el campo existe y no esta vacío.
        - De ser así, comprobamos si es un select y si esta en el array de inventos previos del invento a crear.
        - De ser así, comprobamos si el numero de inventos recibidos coincide con el numero de inventos previos necesarios.
    - Devuelve un array vacío si todos los datos son correctos.
    - En caso de cualquier error lo almacena en un array que devolverá al final.

3. **Añadir función `function crearInvento($datosEntrada): Invento` en `functions.php`:**
     - Inicializa los inventos: puesto que no se guardan en la base de datos, se crean en cada carga de la página. No serán los mismos objetos ya que se generar con datos aleatorios pero el nombre sera el mismo.
     - Como mínimo debe inicializar 50 inventos de cada tipo.
     - Según el invento recibido por POST, se creara un objeto con los datos recibidos.
     - Devuelve el invento creado.

4. **Añadir función `function cargarVistaInvento(): void` en `functions.php`:**
    - Comprueba si el método de envío es POST. De ser asi:
      - Inicializa datosEntrada con los datos recibidos por POST. Adjunto un ejemplo de todos los posibles datos a recibir:
  
        ```php 
          $datosEntrada = [
          'invento'           => $_POST['invento'] ?? '',
          'nombre'            => $_POST['nombre'] ?? '',
          'base'              => $_POST['base'] ?? '',
          'altura'            => $_POST['altura'] ?? '',
          'longitud'          => $_POST['longitud'] ?? '',
          'radio'             => $_POST['radio'] ?? '',
          'ancho'             => $_POST['ancho'] ?? '',
          'grosor'            => $_POST['grosor'] ?? '',
          'metodo'            => $_POST['metodo'] ?? '',
          'visibilidad'       => $_POST['visibilidad'] ?? '',
          'velocidadRotacion' => $_POST['velocidadRotacion'] ?? '',
          'numeroElementos'   => $_POST['numeroElementos'] ?? '',
          'precision'         => $_POST['precision'] ?? '',
          'tiempoMinimo'      => $_POST['tiempoMinimo'] ?? '',
          'tiempoMaximo'      => $_POST['tiempoMaximo'] ?? '',
          'zona'              => $_POST['zona'] ?? '',
          'tecnica'           => $_POST['tecnica'] ?? '',
          'material'          => $_POST['material'] ?? '',
          'materialTecho'     => $_POST['materialTecho'] ?? '',
          'materialParedes'   => $_POST['materialParedes'] ?? '',
          'materialSuelo'     => $_POST['materialSuelo'] ?? '',
          'piedraAfilada'     => $_POST['piedraAfilada'] ?? '',
          'cuerda'            => $_POST['cuerda'] ?? '',
          'fuego'             => $_POST['fuego'] ?? '',
          'cesta'             => $_POST['cesta'] ?? '',
          'lanza'             => $_POST['lanza'] ?? '',
          'arcoFlecha'        => $_POST['arcoFlecha'] ?? '',
          'hacha'             => $_POST['hacha'] ?? '',
          'trampa'            => $_POST['trampa'] ?? '',
          'rueda'             => $_POST['rueda'] ?? '',
          'refugio'           => $_POST['refugio'] ?? '',
          'carro'             => $_POST['carro'] ?? '',
          'ganaderia'         => $_POST['ganaderia'] ?? '',
          'ceramica'          => $_POST['ceramica'] ?? '',
          'alfareria'         => $_POST['alfareria'] ?? '',
          'torno'             => $_POST['torno'] ?? '',
          'agricultura'       => $_POST['agricultura'] ?? '',
          'arado'             => $_POST['arado'] ?? '',
          'fermentacion'      => $_POST['fermentacion'] ?? '',
        ];
        ```

        > [!WARNING] Atención: Los datos recibidos por POST pueden variar según hayáis implementado los campos en el formulario. Aseguraros de que los datos recibidos coinciden con los campos del formulario.

        > [!NOTE] Explicación: Habitualmente, los datos recibidos por POST se utilizan directamente en la creación de objetos. En este caso, se ha creado una variable `$datosEntrada` para almacenar los datos recibidos y facilitar su uso en las funciones posteriores. así como para evitar conflictos con los nombres de las variables a la hora de ejecutar los tests.

      - Llamar a la función `validarDatos($datosEntrada)` para comprobar los datos recibidos y almacenar los errores.
      - Si los datos son correctos:
        - Llamar a la función `crearInvento($datosEntrada)` para crear los objetos necesarios.
        - Mostrar los datos por pantalla.
      - Si los datos no son correctos:
        - Mostrar TODOS los errores por pantalla.

5. **`InventoController.php`:**
      - Usa la función `cargarVistaInvento()` para mostrar los inventos disponibles.

## Testing

Los tests se ejecutarán en la rama main cuando el mensaje del commit comience con 'deploy: + MENSAJE DEL COMMIT' y se haga un push.

### Tests Necesarios

- **Test de Fermentación:**
  - `testCalcularEficiencia`: Verificar cálculo de eficiencia.
  - `testCalcularPuntuacion`: Comprobar cálculo de puntuación.
  - `testToString`: Validar salida de detalles.
  - `testCalcularTiempoCreacion`: Comprobar cálculo de tiempo de creación.

- **Test de Interfaz Web:**
  - `testValidarDatosValido`: Comprobar validación de datos correctos.
  - `testValidarDatosInvalido`: Comprobar validación de datos incorrectos.
  - `testCrearInvento`: Comprobar creación de invento.
