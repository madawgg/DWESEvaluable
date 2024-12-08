# UD3.10 - Barco

Alba, en su continuo esfuerzo por innovar, decidió desarrollar un barco. Este invento no solo requerirá la combinación de varios recursos y conocimientos previos, sino también una gestión más organizada de los usuarios que interactúan con su plataforma de inventos. Alba vio la necesidad de siempre tener en el inventario un numero mínimo de inventos. De este modo no tenia que preocuparse de los inventos necesarios para crear un nuevo invento, ya que siempre los tenia disponibles. Para ello utilizo una cookie que almacenaba el numero de inventos mínimos necesarios.

## Objetivos de la Actividad

- Crear la clase Barco como parte de la evolución de los inventos.
- Implementar un formulario para elegir el numero mínimos de inventos que se deben tener en el inventario.
- Almacenar el numero mínimo de inventos en una cookie.
- Generar inventos automáticamente si no se tienen los inventos mínimos necesarios.

## Pasos

1. **Crear la Clase Barco (Nivel 2):**
     - Hereda de la clase `Almacenamiento`
     - Dispone de los siguientes atributos:
       - `$material`: 1 Objeto de la clase `Material`.
       - `$tela`: 3 Objetos de la clase `Tela`.
       - `$cuerdas`: 10 Objetos de la clase `Cuerda`.
       - `$hacha`: 1 Objeto de la clase `Hacha`.
       - `$carro`: 1 Objeto de la clase `Carro`.
       - La `FIGURA` será un `prisma_rectangular`.
       - El `TAMANYO_ELEMENTO` será `160`.
       - El `NIVEL` será `2`.
       - El resto de atributos que requiere el padre.

     - La eficiencia del barco se calcula como la media de los beneficiosos menos la media de los perjudiciales. La matriz asociativa incluye:
        - beneficiosos: `resistenciaTraccion`, `resistenciaHumedad`, `resistenciaUV`, `resistenciaCompresion`.
        - perjudiciales: `densidad`, `coeficienteFriccion`, `coeficienteDesgaste`, `fragilidad`.
     - Los puntos se calculan a partir de la eficiencia del barco redondeándose a entero. A esta puntuación se le añade:
       - 10 puntos si la altura es menor o igual a 100 cm y su capacidad es mayor a 10 elementos.
       - 15 puntos si la altura es menor o igual a 100 cm y su capacidad es mayor a 20 elementos.
       - 20 puntos si la altura es menor o igual a 100 cm y su capacidad es mayor a 30 elementos.
     - El peso se calcula como la suma de los pesos de los materiales y los objetos que componen el barco, descontando el peso de las ruedas del carro.

        >[!WARNING] CUIDADO
        > Recordad que el peso se calcula usando la función volumen() del trait.

2. **Modificar función `cargarVistaTipoInventos(): void` en `functions.php`:**
   - Recibe el número mínimo de inventos de la cookie. Si no existe la cookie, se establece en 0.
   - En la cabecera de la tabla de inventos, añadir un botón de "Borrar Inventos" que recargue la página con el parámetro `borrar` en la URL. Este parámetro se usará para borrar la sesión con el método `limpiarSesionInventos()`.
   - En la primera fila de la tabla de inventos, añadir un formulario para elegir el número mínimo de inventos.
     - En la primera columna, añadir "Número mínimo de registros:".
     - En la segunda columna, añadir un campo de texto para introducir el número mínimo de inventos. El campo debe ser numérico y no puede ser negativo. Ademas debe tener un botón de "Guardar".
     - También ha de tener un botón de "Borrar" para eliminar la cookie.
     - El formulario enviara los datos a la página `cookieController.php`.
     - EL valor por defecto del formulario debe ser el número mínimo de inventos de la cookie.

3. **Añadir función `procesarCookie(): void` en `functions.php`:**
   - Si recibe a través de GET el parámetro `borrar`, se elimina la cookie `minRegistros`.
   - Si recibe a través de un formulario usando el método POST el número mínimo de inventos necesarios.
   - Si el número es válido y es mayor que 0, se almacena en una cookie llamada `minRegistros`. La cookie debe tener una duración de 1 día.
   - Finalmente, redirige a la página principal `index.php`.
  
4. **Añadir función `limpiarCookie(): void` en `functions.php`:**
   - Si existe la Cookie `minRegistros`:
     - La edita eliminando el valor y estableciendo la fecha de expiración en el pasado.
     - La elimina del array de cookies.
   - Redirige a la página principal `index.php`.

5. **Modificar función `obtenerInventos(): array` en `functions.php`:**
   - Después de almacenar en la matriz de inventos, los inventos almacenados en sesión del tipo seleccionado.
   - Comprobar si se tienen los inventos mínimos necesarios.
   - Si no se tienen los inventos mínimos necesarios, se generan automáticamente los inventos faltantes llama a la función `generarInvento()`. Se le pasaran como numero de inventos a crear el numero mínimo de inventos necesarios menos los inventos que ya se tienen.
   - Se añadirán a los inventos nuevos a la matriz de inventos.
   - Se actualizara la sesión de inventos.

## Testing

Los tests se ejecutarán en la rama main cuando el mensaje del commit comience con 'deploy: + MENSAJE DEL COMMIT' y se haga un push.

### Tests Necesarios

- **Test de los inventos:**
  - Se revisan todos los métodos de los inventos.

- **Test de functions:**
  - `testCargarVistaTipoInventos`: Verificar que se carga la vista de los inventos.
  - `testGenerarInventosCuandoFaltan`: Verificar que se generan los inventos automáticamente cuando no se tienen los inventos mínimos necesarios.
  - `testSinSesionNiCookie`: Verificar que se generan los inventos automáticamente cuando no se tienen los inventos mínimos necesarios y no hay sesión ni cookie.
