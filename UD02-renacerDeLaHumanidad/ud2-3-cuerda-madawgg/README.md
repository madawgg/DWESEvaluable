# UD2.3-Cuerda

Con el tiempo, Isabel se dio cuenta de que podía optimizar aún más la fabricación de cuerdas entendiendo mejor la resistencia de diferentes fibras. Decidió crear un script PHP que le permitiera procesar y evaluar las propiedades de cada tipo de cuerda. Al introducir descripciones detalladas de las fibras y sus características, el script podía procesar y encriptar los datos, proporcionando resultados claros sobre la resistencia y flexibilidad de cada cuerda. Con esta herramienta, Isabel logró identificar las mejores combinaciones de fibras para crear cuerdas más fuertes y duraderas.

## Objetivos de la Actividad

- Practicar utilizando funciones y manejo de cadenas en PHP.
- Procesar y encriptar texto.
- Presentar los resultados de manera legible para el usuario.

## Pasos

1. **Definir el Texto de Entrada:**

   - **$texto:** Un string que contiene información sobre diferentes tipos de cuerdas y sus descripciones, con entidades HTML.

2. **Función `procesarParteCuerda`:**

   - Esta función recibe una parte del texto.
   - Elimina los espacios al principio y al final.
   - Si la parte no está vacía y contiene un delimitador ':', divide la parte en nombre y descripción. De lo contrario, devolverá null.
   - Convierte el nombre a mayúsculas y la primera letra de la descripción a mayúscula.
   - Devuelve un array con el nombre y la descripción.

3. **Función `encriptarCuerda`:**

   - Esta función recibe el nombre, la descripción y un salt.
   - Encripta el nombre y la descripción utilizando la función `crypt`.
   - Devuelve un array asociativo (nombre, descripción) cuyos valores serán el nombre y la descripción encriptados.

4. **Función `procesarTextoCuerda`:**

   - Esta función recibe el texto de entrada.
   - Decodifica las entidades HTML en el texto.
   - Divide el texto en partes utilizando el delimitador '.'.
   - Devuelve un array con las partes del texto.

5. **Función `imprimirCuerda`:**

   - Esta función recibe el texto de entrada y un indicador de encriptación de tipo booleano.
   - Procesa el texto utilizando la función `procesarTextoCuerda`.
   - Si hay partes válidas, procesa cada parte utilizando la función `procesarCuerdas`.
   - Si el resultado no esta vacío, imprime el 'Resultado encriptado:' o 'Resultado sin encriptar:' según el indicador de encriptación.
   - Si no hay partes válidas o bien el resultado esta vacío imprime 'No hay cuerdas válidas.'

6. **Función `procesarCuerdas`:**

   - Esta función recibe un array de partes del texto y un indicador de encriptación.
   - Inicializa una cadena de resultado vacía y la salt para la encriptación con 'Cuerda12345678910'.
   - Procesa cada parte utilizando la función `procesarParteCuerda`.
   - Si el indicador de encriptación está activado, encripta el nombre y la descripción utilizando la función `encriptarCuerda`.
   - Añade el nombre y la descripción procesados al resultado.
   - Devuelve la cadena de resultado.

      ```text
      Tipo de cuerda:CUERDA.
      Descripción:DESCRIPCION.
      ```

7. **Función `principal`:**

   - Define el texto de entrada "&nbsp;&nbsp;cuerda&nbsp;de&nbsp;sisal:&nbsp;&nbsp;ideal&nbsp;para&nbsp;trabajos&nbsp;de&nbsp;jardiner&iacute;a&nbsp;y&nbsp;manualidades.&nbsp;cuerda&nbsp;de&nbsp;c&aacute;&ntilde;amo:&nbsp;&nbsp;muy&nbsp;resistente,&nbsp;ideal&nbsp;para&nbsp;escalada&nbsp;y&nbsp;construcci&oacute;n&nbsp;de&nbsp;herramientas.&nbsp;cuerda&nbsp;de&nbsp;lino:&nbsp;&nbsp;perfecta&nbsp;para&nbsp;pesca&nbsp;y&nbsp;confecci&oacute;n&nbsp;de&nbsp;redes.&nbsp;cuerda&nbsp;de&nbsp;esparto:&nbsp;&nbsp;utilizada&nbsp;en&nbsp;cester&iacute;a&nbsp;y&nbsp;fabricaci&oacute;n&nbsp;de&nbsp;sandalias.".
   - Llama a la función `imprimirCuerda` con el texto de entrada.

## Ejemplo

### Datos proporcionados en el programa

- **Texto de entrada:**

```text
"&nbsp;&nbsp;cuerda&nbsp;de&nbsp;sisal:&nbsp;&nbsp;ideal&nbsp;para&nbsp;trabajos&nbsp;de&nbsp;jardiner&iacute;a&nbsp;y&nbsp;manualidades.&nbsp;cuerda&nbsp;de&nbsp;c&aacute;&ntilde;amo:&nbsp;&nbsp;muy&nbsp;resistente,&nbsp;ideal&nbsp;para&nbsp;escalada&nbsp;y&nbsp;construcci&oacute;n&nbsp;de&nbsp;herramientas.&nbsp;cuerda&nbsp;de&nbsp;lino:&nbsp;&nbsp;perfecta&nbsp;para&nbsp;pesca&nbsp;y&nbsp;confecci&oacute;n&nbsp;de&nbsp;redes.&nbsp;cuerda&nbsp;de&nbsp;esparto:&nbsp;&nbsp;utilizada&nbsp;en&nbsp;cester&iacute;a&nbsp;y&nbsp;fabricaci&oacute;n&nbsp;de&nbsp;sandalias."
```

### Salida del programa

```text
Resultado sin encriptar:  
Tipo de cuerda:CUERDA DE SISAL.  
Descripción:Ideal para trabajos de jardinería y manualidades.  

Tipo de cuerda:CUERDA DE CÁÑAMO.  
Descripción:Muy resistente, ideal para escalada y construcción de herramientas.  

Tipo de cuerda:CUERDA DE LINO.  
Descripción:Perfecta para pesca y confección de redes.  

Tipo de cuerda:CUERDA DE ESPARTO.  
Descripción:Utilizada en cestería y fabricación de sandalias.
```

## Testing

Los tests se ejecutarán en la rama **main**, cuando en el commit se inicie el mensaje como '**deploy:** + MENSAJE DEL COMMIT' y se realice un push.

Los tests que deberá pasar el código para conseguir el descubrimiento serán los siguientes:

1. testProcesarParteCuerda: Verifica que la función `procesarParteCuerda` procese correctamente una parte de la cuerda.
2. testProcesarParteCuerdaVacio: Verifica que la función `procesarParteCuerda` maneje correctamente el caso en que la cuerda está vacía.
3. testEncriptarCuerda: Verifica que la función `encriptarCuerda` encripte correctamente la cuerda.
4. testProcesarTextoCuerda: Verifica que la función `procesarTextoCuerda` procese correctamente el texto de la cuerda.
5. testImprimirCuerdaVacio: Verifica que la función `imprimirCuerda` maneje correctamente el caso en que la cuerda está vacía.
6. testProcesarParteCuerdaSinDelimitador: Verifica que la función `procesarParteCuerda` maneje correctamente el caso en que la cuerda no tiene delimitador.
7. testEncriptarCuerdaDiferenteSalt: Verifica que la función `encriptarCuerda` encripte correctamente la cuerda con un salt diferente.
8. testProcesarTextoCuerdaMultiple: Verifica que la función `procesarTextoCuerda` procese correctamente múltiples textos de la cuerda.
9. testImprimirCuerdaValida: Verifica que la función `imprimirCuerda` maneje correctamente el caso en que la cuerda es válida.
10. testImprimirCuerdaNoValida: Verifica que la función `imprimirCuerda` maneje correctamente el caso en que la cuerda no es válida.
11. testProcesarParteCuerdaConEspaciosAdicionales: Verifica que la función `procesarParteCuerda` maneje correctamente el caso en que la cuerda tiene espacios adicionales.
12. testEncriptarCuerdaVacio: Verifica que la función `encriptarCuerda` maneje correctamente el caso en que la cuerda está vacía.
13. testProcesarTextoCuerdaVacio: Verifica que la función `procesarTextoCuerda` maneje correctamente el caso en que el texto de la cuerda está vacío.
14. testImprimirCuerdaSinDescripcion: Verifica que la función `imprimirCuerda` maneje correctamente el caso en que la cuerda no tiene descripción.
15. testImprimirCuerdaMultiplesValidas: Verifica que la función `imprimirCuerda` maneje correctamente el caso en que hay múltiples cuerdas válidas.
