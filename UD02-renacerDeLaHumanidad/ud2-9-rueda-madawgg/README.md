# UD2.9-Rueda

Con el tiempo, Isabel se dio cuenta de que la eficiencia de sus ruedas podía ser optimizada aún más. Decidida a mejorar su invento, desarrolló un ingenioso script PHP que le permitía experimentar con diferentes configuraciones de ruedas. Al introducir el radio, el grosor y el material de cada rueda, el script calculaba atributos clave como el peso, la durabilidad y la velocidad terminal, teniendo en cuenta constantes físicas como la gravedad y la densidad del aire. Con esta herramienta, Isabel podía simular distintas condiciones y encontrar la mejor combinación para cada situación.

## Objetivos de la Actividad

- Practicar utilizando constantes y funciones en PHP.
- Manejar arrays y generar valores aleatorios.
- Calcular atributos basados en datos de entrada y presentar los resultados de manera legible para el usuario.
- Usar funciones y constantes matemáticas.

## Pasos

1. **Definir Constantes:**

   - **G:** La gravedad en m/s².
   - **RHO:** La densidad del aire en kg/m³.
   - **CD:** El coeficiente de arrastre.

2. **Inicializar Arrays para los Atributos de las Ruedas:**

   - **$radios:** Un array con valores de radio desde 25 cm hasta 100 cm.
   - **$grosores:** Un array con valores de grosor desde 5 cm hasta 50 cm.
   - **$materiales:** Un array con los tipos de materiales ('Madera', 'Piedra', 'Combinado').

3. **Función `valorAleatorioRueda`:**

   - Esta función recibe un array y devuelve un valor aleatorio del mismo.
   - Si el array está vacío, lanza una excepción InvalidArgumentException.

4. **Función `generarRuedas`:**

   - Esta función recibe los arrays de radios, grosores y materiales, y la cantidad de ruedas a generar.
   - Genera una matriz con la cantidad especificada de ruedas, cada una con un radio, grosor y material seleccionados aleatoriamente.
   - Devuelve la matriz asociativa de ruedas generadas en el siguiente formato para cada rueda:
     - Ejemplo rueda: `radio -> 50, grosor -> 20, material -> Madera`

5. **Función `calcularAtributosRueda`:**

   - Esta función recibe la matriz de ruedas y calcula los atributos de cada una.
   - inicializa un array con las densidades.
    Por ejemplo: Madera(700kg/m³), Piedra (2500kg/m³) y Combinado (1600kg/m³).
   - Para cada rueda realiza diversos cálculos:
     - Convierte los valores de radio y grosor de cm a m.
     - Calcula el volumen del cilindro en m³ (V = pi *radio^2* grosor).
     - Verifica si el material de la rueda se encuentra en el listado de densidades. De no ser así se lanza un error `InvalidArgumentException`. Si se dispone de la densidad del material se obtiene su densidad en kg/m³.
     - Calcula el peso en kg (M = V * D).
     - Calcula el área frontal del cilindro en m² (A = pi * radio^2).
     - Calcula la velocidad terminal en m/s (v = √((2 *M* G) / (CD *RHO* A))).
     - La durabilidad de las ruedas viene dada por el material empleado.
    Por ejemplo: Madera(50km), Piedra (100km) y Combinado (75km)
     - Asigna los valores de peso, velocidad y durabilidad a cada rueda de la matriz.
   - Devuelve la matriz de ruedas con los atributos calculados.

6. **Función `imprimirRueda`:**

   - Esta función recibe la matriz de ruedas y las imprime en pantalla.
   - Imprime el radio, grosor, material, peso, durabilidad y velocidad de cada rueda.

      ```text
         Rueda I:  
         Radio: RR cm  
         Grosor: GG cm  
         Material: AAAAA  
         Peso: P.P kg  
         Durabilidad: DD km  
         Velocidad: VV.V m/s  
         -------------------------
      ```

7. **Función `principal`:**

   - Define las constantes necesarias.
   - Inicializa los arrays *radios* (con rangos de elementos desde el 25 al 100), *grosores* (con rangos de elementos desde el 5 al 50) y *materiales* (con tres materiales) para las ruedas.
   - Genera una matriz con 3 ruedas de forma aleatoria con `generarRuedas`.
   - Calcula la carga, durabilidad y velocidad de las ruedas con `calcularAtributosRueda`.
   - Llama a la función `imprimirRueda` para mostrar los resultados.

## Ejemplo

### Datos proporcionados en el programa

- **G:** 9.81 m/s²
- **RHO:** 1.225 kg/m³
- **CD:** 1.0
- **Radios:** 25 cm a 100 cm
- **Grosores:** 5 cm a 50 cm
- **Materiales:** 'Madera', 'Piedra', 'Combinado'

### Salida del programa

```text
Rueda 1:  
Radio: 50 cm  
Grosor: 20 cm  
Material: Madera  
Peso: 5.5 kg  
Durabilidad: 50 km  
Velocidad: 10.5 m/s  
-------------------------
Rueda 2:  
Radio: 75 cm  
Grosor: 30 cm  
Material: Piedra  
Peso: 15.0 kg  
Durabilidad: 100 km  
Velocidad: 8.2 m/s  
-------------------------
Rueda 3:  
Radio: 100 cm  
Grosor: 40 cm  
Material: Combinado  
Peso: 25.0 kg  
Durabilidad: 75 km  
Velocidad: 12.3 m/s
```

## Testing

Los tests se ejecutarán en la rama **main**, cuando en el commit se inicie el mensaje como '**deploy:** + MENSAJE DEL COMMIT' y se realice un push.

Los tests que deberá pasar el código para conseguir el descubrimiento serán los siguientes:

1. testValorAleatorioRueda: Verifica que la función `valorAleatorioRueda` genere correctamente un valor aleatorio para una rueda.
2. testValorAleatorioRuedaAdicionales: Verifica que la función `valorAleatorioRueda` maneje correctamente los valores adicionales.
3. testGenerarRuedas: Verifica que la función `generarRuedas` genere correctamente una lista de ruedas con los materiales proporcionados.
4. testGenerarRuedasAdicionales: Verifica que la función `generarRuedas` maneje correctamente los casos adicionales.
5. testCalcularAtributosRueda: Verifica que la función `calcularAtributosRueda` calcule correctamente los atributos de una rueda.
6. testCalcularAtributosRuedaAdicionales: Verifica que la función `calcularAtributosRueda` maneje correctamente los casos adicionales.
7. testImprimirRueda: Verifica que la función `imprimirRueda` muestre correctamente la información de una rueda.
8. testImprimirRuedaAdicionales: Verifica que la función `imprimirRueda` maneje correctamente los casos adicionales.
9. testValorAleatorioRuedaRetornaValorUnico: Verifica que la función `valorAleatorioRueda` retorne correctamente un valor único.
10. testGenerarRuedasCantidadNegativa: Verifica que la función `generarRuedas` maneje correctamente el caso en que se proporciona una cantidad negativa.
11. testCalcularAtributosRuedaRadioCero: Verifica que la función `calcularAtributosRueda` maneje correctamente el caso en que el radio de la rueda es cero.
12. testCalcularAtributosRuedaGrosorCero: Verifica que la función `calcularAtributosRueda` maneje correctamente el caso en que el grosor de la rueda es cero.
13. testCalcularAtributosRuedaMaterialInvalido: Verifica que la función `calcularAtributosRueda` maneje correctamente el caso en que el material de la rueda es inválido.
14. testImprimirRuedaVacia: Verifica que la función `imprimirRueda` maneje correctamente el caso en que la rueda está vacía.
15. testImprimirRuedaAtributosFaltantes: Verifica que la función `imprimirRueda` maneje correctamente el caso en que faltan atributos de la rueda.
