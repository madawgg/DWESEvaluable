# UD2.2-Fuego

Finalmente, Isabel decidió que necesitaba una forma más precisa de calcular la generación de fuego mediante fricción. Con la ayuda de un script PHP, Isabel pudo introducir las propiedades ambientales como la humedad, el viento y la temperatura, así como el método utilizado para encender el fuego. El script calculaba el tiempo estimado necesario para generar la chispa y encender el fuego basándose en estas condiciones. Gracias a este avance, Isabel pudo identificar rápidamente cuáles eran las mejores condiciones y métodos para encender fuego, mejorando significativamente la eficiencia y seguridad en la creación de fuego para su tribu.

## Objetivos de la Actividad

- Practicar utilizando funciones y variables en PHP.
- Usar métodos para trabajar con fechas y horas.
- Usar estructuras condicionales para manejar condiciones y cálculos basados en datos de entrada.
- Presentar los resultados de manera legible para el usuario.

## Pasos

1. **Definir Variables Iniciales:**

   - **$humedad:** El porcentaje de humedad.
   - **$viento:** La velocidad del viento en km/h.
   - **$temperatura:** La temperatura en grados Celsius.
   - **$metodo:** El método utilizado para encender el fuego (puede ser 'friccion', 'chispa', 'lupa').

2. **Función `seleccionarMetodoFuego`:**

   - Esta función recibe el método como parámetro y devuelve el tiempo base en minutos según el método seleccionado.
   - Si el método es 'friccion', el tiempo base es 30 minutos.
   - Si el método es 'chispa', el tiempo base es 20 minutos.
   - Si el método es 'lupa', el tiempo base es 10 minutos.
   - Si el método no es ninguno de los anteriores, el tiempo base es 0.

3. **Función `calcularFactorFuego`:**

   - Esta función recibe la humedad, el viento y la temperatura como parámetros y calcula un factor basado en estas condiciones.
   - El factor inicialmente es de 1.
   - Si la humedad es mayor a 50, el factor aumenta en 0.5.
   - Si el viento es mayor a 20 km/h, el factor aumenta en 0.3.
   - Si la temperatura es menor a 10 grados Celsius, el factor aumenta en 0.2.
   - Devuelve el factor calculado.

4. **Función `calcularTiempoFinalFuego`:**

   - Esta función recibe la hora de inicio y el tiempo estimado en minutos.
   - Calcula la hora final sumando el tiempo estimado a la hora de inicio.
   - Devuelve la hora final.

5. **Función `imprimirFuego`:**

   - Esta función recibe el método, la humedad, el viento, la temperatura, el tiempo estimado, la hora de inicio y la hora final.
   - Imprime en pantalla el método utilizado, las condiciones ambientales, el tiempo estimado para encender el fuego, la hora de inicio y la hora estimada de encendido.

6. **Función `principal`:**

   - Define las variables iniciales necesarias.
   - Llama a la función `seleccionarMetodoFuego` y almacena el tiempo base.
   - Llama a la función `calcularFactorFuego` y almacena el factor calculado.
   - Calcula el tiempo estimado multiplicando el tiempo base por el factor.
   - Define la hora de inicio.
   - Llama a la función `calcularTiempoFinalFuego` y almacena la hora final.
   - Llama a la función `imprimirFuego` pasando todos los datos necesarios.

## Ejemplo

### Datos proporcionados en el programa

- **Humedad:** 60%
- **Viento:** 15 km/h
- **Temperatura:** 5 °C
- **Método:** fricción

### Salida del programa

```text
Método utilizado: fricción  
Condiciones ambientales: 60% de humedad, con un viento de 15 km/h y una temperatura de 5 °C  
Tiempo estimado para encender el fuego: 51 minutos  
Hora de inicio: 12:00:00  
Hora estimada de encendido: 12:51:00
```

## Testing

Los tests se ejecutarán en la rama **main**, cuando en el commit se inicie el mensaje como '**deploy:** + MENSAJE DEL COMMIT' y se realice un push.

Los tests que deberá pasar el código para conseguir el descubrimiento serán los siguientes:

1. testSeleccionarMetodoFuego: Verifica que la función `seleccionarMetodoFuego` seleccione correctamente un método para encender fuego.
2. testSeleccionarMetodoFuegoAdicionales: Verifica que la función `seleccionarMetodoFuego` maneje correctamente los casos adicionales.
3. testCalcularFactorFuego: Verifica que la función `calcularFactorFuego` calcule correctamente el factor de fuego basado en los materiales y condiciones.
4. testCalcularFactorFuegoAdicionales: Verifica que la función `calcularFactorFuego` maneje correctamente los casos adicionales.
5. testCalcularTiempoFinalFuego: Verifica que la función `calcularTiempoFinalFuego` calcule correctamente el tiempo final necesario para encender fuego.
6. testCalcularTiempoFinalFuegoAdicionales: Verifica que la función `calcularTiempoFinalFuego` maneje correctamente los casos adicionales.
7. testImprimirFuego: Verifica que la función `imprimirFuego` muestre correctamente la información sobre el fuego.
8. testImprimirFuegoAdicionales: Verifica que la función `imprimirFuego` maneje correctamente los casos adicionales.
9. testSeleccionarMetodoFuegoMetodoVacio: Verifica que la función `seleccionarMetodoFuego` maneje correctamente el caso en que el método está vacío.
10. testSeleccionarMetodoFuegoMetodoInvalido: Verifica que la función `seleccionarMetodoFuego` maneje correctamente el caso en que el método es inválido.
11. testCalcularFactorFuegoValoresExtremos: Verifica que la función `calcularFactorFuego` maneje correctamente los valores extremos.
12. testCalcularTiempoFinalFuegoTiempoEstimadoCero: Verifica que la función `calcularTiempoFinalFuego` maneje correctamente el caso en que el tiempo estimado es cero.
13. testImprimirFuegoCondicionesAmbientalesExtremas: Verifica que la función `imprimirFuego` maneje correctamente las condiciones ambientales extremas.
14. testImprimirFuegoTiempoEstimadoNegativo: Verifica que la función `imprimirFuego` maneje correctamente el caso en que el tiempo estimado es negativo.
15. testImprimirFuegoFechaInvalida: Verifica que la función `imprimirFuego` maneje correctamente el caso en que la fecha es inválida.
