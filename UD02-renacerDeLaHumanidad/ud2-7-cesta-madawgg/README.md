# UD2.7-Cesta

Para ayudar a Isabel a calcular la capacidad de carga de las diferentes cestas que construía, se necesita un script PHP que le permitiera analizar y comparar distintos materiales y técnicas de tejido. Isabel diseñó un script que le permitía ingresar las propiedades de cada material, como la durabilidad y la resistencia, y las condiciones ambientales en las que se usarían las cestas. Con esta herramienta, Isabel podía seleccionar aleatoriamente los materiales y zonas de prueba, calcular la probabilidad de encontrar los materiales necesarios en cada zona, y generar un documento HTML con la información detallada sobre cada cesta.

## Objetivos de la Actividad

- Practicar el uso de arrays asociativos para almacenar información.
- Seleccionar elementos aleatorios de un array.
- Utilizar la expresión match para realizar cálculos basados en condiciones.
- Generar un documento HTML con información dinámica desde PHP.

## Pasos

1. **Definir Arrays Asociativos:**

   - Crea un array asociativo llamado `$materiales` que contenga los diferentes materiales de las cestas y sus descripciones.
   - Define un array `$zonas` con las posibles zonas donde se pueden encontrar las cestas.
   - Crea arrays asociativos `$humedad` y `$temperatura` que relacionen cada zona con su respectivo valor de humedad y temperatura.

2. **Función `seleccionarAleatorioCesta`:**

   - Implementa una función que reciba un array como parámetro y devuelva un elemento aleatorio de ese array.
   - Si el array está vacío, la función debe devolver null.

3. **Función `obtenerDescripcionCesta`:**

   - Crea una función que reciba el array `$materiales` y el nombre de un `$material` específico como parámetros.
   - La función debe devolver la descripción correspondiente al material proporcionado.
   - Si el material no existe en el array, debe devolver un mensaje indicando 'Material no disponible.'.

4. **Función `calcularProbabilidadMaterialCesta`:**

   - Implementa una función que reciba el `$material`, la `$zona`, y los arrays `$humedad` y `$temperatura` como parámetros.
   - Calcula la probabilidad de encontrar el material en la zona especificada.
   - La probabilidad se calcula utilizando la fórmula siguiente:
     - `probabilidad = humedad * probabilidadHumedad + temperatura * probabilidadTemperatura`
     - La probabilidad varia segun el material:
       - Madera: probabilidadHumedad (0.5), probabilidadTemperatura (0.1)
       - Bambú: probabilidadHumedad (0.7), probabilidadTemperatura (0.2)
       - Mimbre: probabilidadHumedad (0.6), probabilidadTemperatura (0.15)
       - Rattan: probabilidadHumedad (0.8), probabilidadTemperatura (0.3)
       - Hojas de Palma: probabilidadHumedad (0.9), probabilidadTemperatura (0.25)
   - Si el materia no esta incluido entre los anteriores la probabilidad sera 0.

5. **Función `imprimirCesta`:**

   - Crea una función que reciba el `$material`, la descripción de la `$cesta`, la `$zona` y la `$probabilidad` como parámetros.
   - La función debe generar un documento HTML que muestre la información de la cesta, incluyendo el material, la descripción, la zona y la probabilidad de encontrar el material.
   - Utiliza una clase CSS para resaltar visualmente la probabilidad según sea alta, media o baja.

6. **Función `principal`:**

   - Esta función ejecuta el flujo principal del programa.
   - Define los arrays asociativos necesarios (`$materiales`, `$zonas`, `$humedad`, `$temperatura`).
   - Selecciona aleatoriamente un material y una zona utilizando la función `seleccionarAleatorioCesta`.
   - Obtiene la descripción de la cesta utilizando la función `obtenerDescripcionCesta`.
   - Calcula la probabilidad de encontrar el material en la zona utilizando la función `calcularProbabilidadMaterialCesta`.
   - Imprime el documento HTML con la información de la cesta utilizando la función `imprimirCesta`.

## Ejemplo

### Datos proporcionados en el programa

- **$materiales:**
  - 'Madera' => 'Ideal para almacenar objetos pesados.'
  - 'Bambú' => 'Ligera y resistente, perfecta para frutas.'
  - 'Mimbre' => 'Flexible y duradera, ideal para picnic.'
  - 'Rattan' => 'Elegante y robusta, perfecta para decoración.'
  - 'Hojas de Palma' => 'Ecológica y biodegradable, ideal para alimentos.'
- **$zonas:** ['Bosque', 'Selva', 'Pradera', 'Desierto']
- **$humedad:**
  - 'Bosque' => 70
  - 'Selva' => 90
  - 'Pradera' => 50
  - 'Desierto' => 20
- **$temperatura:**
  - 'Bosque' => 15
  - 'Selva' => 25
  - 'Pradera' => 20
  - 'Desierto' => 35

### Salida del programa (ejemplo)

```html
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cestas Naturales</title>
</head>
<body>
    <h1>Cesta de Bambú</h1>
    <p>Descripción: Ligera y resistente, perfecta para frutas.</p>
    <p>Zona: Selva</p>
    <p class="alta">Probabilidad de encontrar Bambú: 81%</p>
</body>
</html>
```

## Testing

Los tests se ejecutarán en la rama **main**, cuando en el commit se inicie el mensaje como '**deploy:** + MENSAJE DEL COMMIT' y se realice un push.

Los tests que deberá pasar el código para conseguir el descubrimiento serán los siguientes:

1. testSeleccionarAleatorioCesta: Verifica que la función `seleccionarAleatorioCesta` seleccione correctamente un elemento aleatorio de la cesta.
2. testObtenerDescripcionCesta: Verifica que la función `obtenerDescripcionCesta` devuelva la descripción correcta de una cesta existente.
3. testObtenerDescripcionCestaNoExistente: Verifica que la función `obtenerDescripcionCesta` maneje correctamente el caso en que la cesta no existe.
4. testCalcularProbabilidadMaterialCestaMaderaBosque: Verifica que la función `calcularProbabilidadMaterialCesta` calcule correctamente la probabilidad de encontrar material de madera en una cesta en el bosque.
5. testCalcularProbabilidadMaterialCestaBambuSelva: Verifica que la función `calcularProbabilidadMaterialCesta` calcule correctamente la probabilidad de encontrar material de bambú en una cesta en la selva.
6. testSeleccionarAleatorioCestaVacio: Verifica que la función `seleccionarAleatorioCesta` maneje correctamente el caso en que la cesta está vacía.
7. testObtenerDescripcionCestaMinusculas: Verifica que la función `obtenerDescripcionCesta` maneje correctamente las descripciones de cestas en minúsculas.
8. testCalcularProbabilidadMaterialCestaNoExistente: Verifica que la función `calcularProbabilidadMaterialCesta` maneje correctamente el caso en que el material no existe en la cesta.
9. testImprimirCestaProbabilidadAlta: Verifica que la función `imprimirCesta` muestre correctamente la probabilidad alta de encontrar un material en la cesta.
10. testImprimirCestaProbabilidadBaja: Verifica que la función `imprimirCesta` muestre correctamente la probabilidad baja de encontrar un material en la cesta.
11. testSeleccionarAleatorioCestaUnElemento: Verifica que la función `seleccionarAleatorioCesta` seleccione correctamente el único elemento de la cesta.
12. testObtenerDescripcionCestaMayusculas: Verifica que la función `obtenerDescripcionCesta` maneje correctamente las descripciones de cestas en mayúsculas.
13. testCalcularProbabilidadMaterialCestaMimbrePradera: Verifica que la función `calcularProbabilidadMaterialCesta` calcule correctamente la probabilidad de encontrar material de mimbre en una cesta en la pradera.
14. testImprimirCestaProbabilidadMedia: Verifica que la función `imprimirCesta` muestre correctamente la probabilidad media de encontrar un material en la cesta.
15. testImprimirCestaMaterialNoExistente: Verifica que la función `imprimirCesta` maneje correctamente el caso en que el material no existe en la cesta.
