# UD2.1-Piedra afilada

Finalmente, Isabel decidió que necesitaba una forma más precisa de medir la eficiencia de sus piedras afiladas. Con la ayuda de un script PHP, Isabel pudo introducir las propiedades de cada piedra, como el material, la longitud y el peso. El script calculaba la eficiencia de cada piedra basándose en estas propiedades y en un factor de dureza constante. Gracias a este avance, Isabel pudo identificar rápidamente cuáles eran las piedras más eficientes para cortar y tallar.

## Objetivos de la Actividad

- Practicar utilizando variables y constantes en PHP.
- Manejar excepciones en caso de errores en los datos de entrada.
- Presentar los resultados de manera legible para el usuario.

## Pasos

1. **Definir Constantes:**

   - **FACTOR_DUREZA:** Un factor constante que afecta la eficiencia de la piedra afilada.

2. **Definir Variables Globales**

   - **$material:** El material de la piedra afilada.
   - **$longitud:** La longitud de la piedra en centímetros.
   - **$peso:** El peso de la piedra en gramos.

3. **Función `calcularEficienciaPiedraAfilada`:**

   - Esta función utiliza las variables globales para calcular la eficiencia de la piedra afilada.
   - Si el peso es cero, lanza una excepción DivisionByZeroError.
   - Si la longitud o el peso no son numéricos la eficiencia será 0.
   - Si son numéricos, calculará la eficiencia (con una presición de 3 decimales) como la relación entre la longitud y el peso de la piedra, multiplicada por el factor de dureza.
   - Por último, devuelve un array con las características de la piedra y su eficiencia si no hay excepciones.
   - En caso de excepción, devuelve un array con las características de la piedra pero el valor de eficiencia será un mensaje de error indicando "Error: División por cero".

4. **Función `imprimirEficienciaPiedraAfilada`:**

   - Esta función recibe un array con las características de la piedra y lo imprime en pantalla en un formato legible.
   - Imprime el material, longitud, peso y eficiencia de la piedra.

5. **Función `principal`:**

   - Esta función no se testea. Su finalidad es ejecutar el código en un servidor web para obtener la solución.
   - En este ejercicio:
     - Define las constantes y variables globales necesarias.
     - Llama a la función `calcularEficienciaPiedraAfilada` y almacena el resultado.
     - Llama a la función `imprimirEficienciaPiedraAfilada` pasando el resultado obtenido.

## Ejemplo

### Datos proporcionados en el programa

- **FACTOR_DUREZA** 1.5
- **Material:** Granito
- **Longitud:** 15 cm
- **Peso:** 500 g

### Salida del programa

```text
Material: Granito  
Longitud: 15 cm  
Peso: 500 g  
Eficiencia: 0.045 cm/g  
```

## Testing

Los tests se ejecutarán en la rama **main**, cuando en el commit se inicie el mensaje como '**deploy:** + MENSAJE DEL COMMIT' y se realice un push.

Los tests que deberá pasar el código para conseguir el descubrimiento serán los siguientes:

1. testDefaultValues: Verifica que la función `calcularEficienciaPiedraAfilada` maneje correctamente los valores por defecto.
2. testModifiedValues: Verifica que la función `calcularEficienciaPiedraAfilada` maneje correctamente los valores modificados.
3. testExtremelyHighValues: Verifica que la función `calcularEficienciaPiedraAfilada` maneje correctamente los valores extremadamente altos.
4. testExtremelyLowValues: Verifica que la función `calcularEficienciaPiedraAfilada` maneje correctamente los valores extremadamente bajos.
5. testDivisionByZero: Verifica que la función `calcularEficienciaPiedraAfilada` maneje correctamente el caso de división por cero.
6. testImprimirResultado: Verifica que la función `imprimirEficienciaPiedraAfilada` muestre correctamente el resultado.
7. testNegativeLength: Verifica que la función `calcularEficienciaPiedraAfilada` maneje correctamente el caso en que la longitud es negativa.
8. testNegativeWeight: Verifica que la función `calcularEficienciaPiedraAfilada` maneje correctamente el caso en que el peso es negativo.
9. testZeroLength: Verifica que la función `calcularEficienciaPiedraAfilada` maneje correctamente el caso en que la longitud es cero.
10. testNullMaterial: Verifica que la función `calcularEficienciaPiedraAfilada` maneje correctamente el caso en que el material es nulo.
11. testEmptyMaterial: Verifica que la función `calcularEficienciaPiedraAfilada` maneje correctamente el caso en que el material está vacío.
12. testNonNumericLength: Verifica que la función `calcularEficienciaPiedraAfilada` maneje correctamente el caso en que la longitud no es numérica.
13. testNonNumericWeight: Verifica que la función `calcularEficienciaPiedraAfilada` maneje correctamente el caso en que el peso no es numérico.
14. testNegativeEfficiency: Verifica que la función `calcularEficienciaPiedraAfilada` maneje correctamente el caso en que la eficiencia es negativa.

</div>
