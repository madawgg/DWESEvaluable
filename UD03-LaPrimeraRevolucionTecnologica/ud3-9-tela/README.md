# UD3.9 - Tela

Alba, continuando con su serie de innovaciones, decidió desarrollar la tela como un nuevo invento. Este invento requerirá la combinación de varios recursos y conocimientos previos para su creación. Al tener una interfaz era mas sencillo mantener los inventos y gestionarlos de forma eficiente, pero como los inventos no se almacenaban de forma persistente, se debían crear en cada carga de la página. Por ello, evitando de tener que usar complejos sistemas de almacenamiento, Alba pensó en almacenar los inventos creados en una cookie para poder visualizarlos en cualquier momento.

## Objetivos de la Actividad

- Crear la clase Tela como parte de la evolución de los inventos.
- Ampliar la interfaz web para la gestión de inventos.
- Implementar un métodos para la creación de inventos, almacenando los objetos creados en una cookie.

## Pasos

1. **Crear la Clase Tela (Nivel 2):**
     - Hereda de la clase `Invento`
     - Implementa la interfaz `Medible`
     - Usa el trait `CalculosGeometricos`
     - Dispone de los siguientes atributos:
       - `$material`: 1 Objeto de la clase `Material`.
       - `$cuerda`: 20 Objetos de la clase `Cuerda`.
       - `$ganaderia`: 2 Objeto de la clase `Ganaderia`.
       - `$torno`: 2 Objeto de la clase `Torno`.
       - El resto de atributos que requiere el padre.
     - La `FIGURA` será un `prisma_rectangular`.
     - El `NIVEL` será `2`.
     - La matriz asociativa incluye:
        - beneficiosos: `resistenciaTraccion`, `resistenciaHumedad`, `flexibilidad`.
        - perjudiciales: `densidad`, `coeficienteDesgaste`.
     - Los puntos se calculan a partir de la eficiencia a la que se le incrementan o reducen tantos puntos como devuelva la operación del promedio de número animales de las ganaderías.
     - El peso se calculara como el peso de los materiales las cuerdas y la ganadería solo se necesitan para el telar y para conseguir los materiales.
     - En el tamaño, la atura y la longitud será la altura de la cuerda más larga. El ancho sera el grosor de la cuerda más gruesa (diámetro).

2. **Usar sesiones en `functions.php`:**
     - Al inicio de `functions.php`, se debe iniciar la sesión.

      > [!CAUTION] IMPORTANTE
      > Las sesiones se pueden usar para almacenar datos de forma persistente en el servidor. Para almacenar un objeto en una sesión, se debe **serializar** el objeto con la función `serialize` y para recuperar el objeto se debe **deserializar** con la función `unserialize`. Para almacenar un objeto en una sesión, se debe usar la función `$_SESSION['nombre'] = serialize($objeto)` y para recuperar el objeto se debe usar `unserialize($_SESSION['nombre'])`. Para comprobar si existe una sesión con un nombre, se debe usar la función `isset($_SESSION['nombre'])`.

3. **Modificar la función `obtenerInventos()` en `functions.php`:**
   - La función debe crear la matriz de inventos con cada tipo de invento con un array vacío.
   - Para cada tipo de invento, se debe comprobar si existe una sesión con el nombre del tipo de invento.
   - Si existe, se recogerá los datos de la sesión y se añadirá al array de inventos de la matriz.
   - Si no existe:
     - Devolver la matriz de inventos.

4. **Modificar la función `cargarVistaInventos($inventos): void` en `functions.php`:**
    - Cambiar su nombre a `cargarVistaTipoInventos(): void`.
    - Obtendrá los tipos de inventos disponibles con el método `obtenerInventos()` (No los recibe como parámetro de entrada).
    - Las columnas tendrán una cabecera con 'Nombre del tipo de Invento' y 'Acciones'.
    - Cada fila contendrá:
        - Nombre del tipo de invento (el mismo nombre que la clase).
        - Enlace "Listar Inventos" que pasa el nombre del tipo de Invento `tipoInvento` por GET a `inventos.php`.
        - Enlace "Crear Invento" que pasa el nombre del tipo de Invento `tipoInvento` por GET a `formulario.php`.

5. **Modificar función `cargarVistaInvento(): void` en `functions.php`:**
     - Cambiar su nombre a `procesarNuevoInvento(): void`.
  
6. **Modificar función `cargarVistaFormulario(): void` en `functions.php`:**
     - Obtiene el tipo de invento recibido por GET con el nombre de `tipoInvento`.
     - Elimina la llamada a la función `inicializarInventos`.

7. **Añadir una NUEVA función `cargarVistaInventos(): void` en `functions.php`:**
   - Obtiene el tipo de invento recibido por GET.
   - Si el tipo de invento es válido, y existe:
     - Se creara una tabla HTML con los inventos disponibles con el método `obtenerInventos()`.
     - Las columnas tendrán una cabecera con 'Nombre del Invento' y 'Acciones'.
     - Cada fila contendrá:
        - Nombre del invento (El nombre del objeto).
        - Enlace "Ver Invento" que pasa el nombre `nombreInvento` y el tipo de invento `tipoInvento` por GET a `invento.php`
   - Si el tipo de invento no es válido, se mostrará un mensaje de error "Tipo de invento no válido" y un enlace para volver a la lista de tipos de inventos anterior.

8. **Añadir función `cargarVistaInvento(): void` en `functions.php`:**
   - Obtiene el tipo de invento recibido por GET.
   - Obtiene el invento recibido por GET.
   - Obtiene los inventos disponibles con el método `obtenerInventos()`.
   - Si existe el tipo de invento, y el invento no es nulo, se mostrará el invento y un enlace para volver a la lista de inventos anterior.
   - Si no existe el tipo de invento, se mostrará un mensaje de error "Invento no válido" y un enlace para volver a la lista de inventos anterior.

9. **Añadir función `function guardarInventos($inventos): void` en `functions.php`:**
    - Para cada tipo de invento:
      - Si el tipo de invento existe, y no esta vació:
        - Se deben recorrer los inventos y guardarlos en la sesión serializados.
        - Guardar los inventos en la sesión usando el nombre del tipo de invento como clave.
      - Si no existe, o esta vacío, se debe crear una sesión con el nombre del tipo de invento y el valor de la sesión será un array vacío.

10. **Añadir función `function eliminarInvento($tipo, $id): bool` en `functions.php`:**
    - Se comprueba si existe el tipo de invento y el id del invento en la sesión (el id representa la posición del invento en el array).
    - Si existe, se elimina el invento de la sesión y se reindexa el array de inventos.
    - Se devuelve `true` si se ha eliminado correctamente, y `false` en caso contrario.
  
      > [!WARNING] RECUERDA
      > Al eliminar un invento, se debe reindexar los inventos del tipo recibido para que no haya huecos en el array. Para ello, se puede usar la función `array_values($array)`. Puesto que solo se elimina un invento de un subarray `array_values($array[$tipo])`, no es necesario reindexar el array principal.

11. **Añadir función `function limpiarSesionInventos(): void` en `functions.php`:**
    - Se obtienen los tipos de inventos con el método `obtenerInventos()`.
    - Para cada tipo de invento:
      - Si existe el tipo de invento en la sesión, se elimina.

12. **Modificar función `procesarNuevoInvento(): void` en `functions.php`:**
     - Después de crear el invento.
     - Se debe obtener el array de inventos con el método `obtenerInventos()`.
     - Se debe añadir el invento al array de inventos del tipo de invento.
     - Posteriormente, se debe guardar el array de inventos en la sesión.
     - Finalmente, redirigir a `invento.php` con el tipo de invento y el nombre del invento creado por GET.

       > [!WARNING] RECUERDA
       > Para redirigir a una página en PHP, se debe usar la función `header('Location: ejemplo.php')`.
     - El resto de comportamiento de la función se mantiene igual, validando datos y mostrando los errores correspondientes..

13. **Modificar función `crearInvento($datosEntrada): Invento` en `functions.php`:**
     - En aquellos casos que un invento se componga de otros inventos, después de crear el invento, se deben eliminar del array de inventos los inventos usados para la creación del invento actual.

       > [!TIP] POR EJEMPLO
       > Si se crea una trampa con una cuerda, una cesta y un arco y flecha, se deben eliminar del array de inventos la cuerda, la cesta y el arco y flecha usados para la creación de la trampa.

14. **Modificar la Interfaz Web:**

    - **`index.php`:**
      - Llamara a la función `cargarVistaTipoInventos()`.

    - **`InventoController.php`:**
      - Llamara a la función `procesarNuevoInvento()`.

    - **`Inventos.php`:**
      - Llamara a la función `cargarVistaInventos()`.
  
    - **`Invento.php`:**
      - Llamara a la función `cargarVistaInvento()`.

## Testing

Los tests se ejecutarán en la rama main cuando el mensaje del commit comience con 'deploy: + MENSAJE DEL COMMIT' y se haga un push.

### Tests Necesarios:

- **Test de Tela:**
  - `testCalcularEficiencia`: Verificar cálculo de eficiencia.
  - `testCalcularPuntuacion`: Comprobar cálculo de puntuación.
  - `testCalcularPeso`: Validar cálculo de peso.
  - `testCalcularVolumen`: Comprobar cálculo de volumen.
  - `testCalcularArea`: Verificar cálculo de área.
  - `testCalcularSuperficie`: Validar cálculo de superficie.
  - `testObtenerTamanyo`: Comprobar obtención de tamaño.
  - `testCalcularTiempoCreacion`: Verificar cálculo de tiempo de creación.
  - `testToString`: Validar salida de detalles.
  
- **Test de functions:**
- `testObtenerZonas`: Comprobar obtención de zonas.
- `testObtenerTecnicas`: Verificar obtención de técnicas.
- `testObtenerMateriales`: Comprobar obtención de materiales.
- `testObtenerInventos`: Validar obtención de inventos.
- `testGuardarInventos`: Comprobar guardado de inventos.
- `testEliminarInvento`: Verificar eliminación de invento.
- `testLimpiarSesionInventos`: Validar limpieza de sesión de inventos.
- `testValidarDatos`: Comprobar validación de datos.
- `testGenerarInvento`: Verificar generación de invento.
- `testProcesarNuevoInvento`: Validar procesamiento de nuevo invento.
- `testCargarVistaInventos`: Comprobar carga de vista de inventos.
- `testCargarVistaInvento`: Verificar carga de vista de invento.
- `testCargarVistaTipoInventos`: Validar carga de vista de tipo de inventos.
- `testCargarVistaFormulario`: Comprobar carga de vista de formulario.