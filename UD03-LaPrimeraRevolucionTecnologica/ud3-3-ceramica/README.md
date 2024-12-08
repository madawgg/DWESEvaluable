# UD3.3-Cerámica

Alba observó la necesidad de contenedores resistentes al fuego que permitieran almacenar y cocinar alimentos. Con este propósito, creó la **Cerámica**, utilizando como base el **Fuego** y la **Cesta**, además de materiales de tierra. Este sistema de cerámica eficiente para la producción y almacenamiento de alimentos  Alba se dio cuenta que muchos inventos estaban formados por otros mas pequeños y de los que ya conocia sus propiedades, asi que se implementaron los calculos para conocer los datos de las figuras geométricas compuestas.

## Objetivos de la Actividad

- Implementar una estructura de clases que represente el sistema de cerámica y sus dependencias.
- Implementar una interfaz `Medible` con métodos específicos de cálculo para varios inventos.
- Usar herencia, interfaces, y métodos estáticos para simular la crianza de animales en la tribu.
- Simular la implementación de un invento basado en componentes de otros inventos.

## Pasos

1. **Preparación del proyecto**
   - Partir de la solución obtenida en el ejercicio anterior.

2. **Crear la Interfaz Medible:**
   - Crear un directorio `interfaces` en el directorio `src`.
   - Crear la interfaz `Medible.php` dentro del directorio `interfaces` con los métodos:
     - `calcularPeso(): float`
     - `calcularVolumen: float`
     - `calcularArea: float`
     - `calcularSuperficie: float`
     - `obtenerTamanyo(): array`

3. **Crear la Clase Cerámica (Nivel 2):**
   - Hereda de la clase `Invento`
   - Usa el trait `CalculosGeometricos`
   - Dispone de los siguientes atributos:
     - `$material`: 1 Objeto de la clase `Material`.
     - `$fuego`: 1 Objeto de la clase `Fuego`.
     - `$cesta`: 1 Objeto de la clase `Cesta`.  
   - La FIGURA será un cilindro con las dimensiones igual que la cesta.
   - La matriz asociativa incluye:
     - beneficiosos: `resistenciaTemperatura`, `coeficienteDesgaste`.
     - perjudiciales: `fragilidad`.
   - Los puntos se calculan a partir de la eficiencia en valores enteros.

4. **Implementar la Interfaz Medible con el Trait:**

   - Añadir la interfaz `Medible` en todas las clases menos en la clase `Fuego`.
   - Todos los metodos implementados que devuelven valores reales, deben devolver el resultado con dos decimales.
   - Cada clase que implementa la interfaz, creara los métodos `calcularPeso`, `calcularVolumen`, `calcularArea`, `calcularSuperficie` y `obtenerTamanyo()`.
   - El calculo del peso se realizara sumando los pesos de los objetos que componen el invento.
     - Si existe material para la invento, a partir de la densisdad del material y el volumen de la figura geométrica.
     - Si existen objetos que lo componen, a partir de la densisdad del material de cada invento y el volumen de las figuras geométricas de los objetos.
     - La formula para el peso es la siguiente: `peso = densidad * volumen`.
   - La función de `obtenerTamanyo()` en aquellas clases que tengan tamaño propio devolvera el tamaño de la figura, en caso contrario devolvera el tamaño calculado según la descripción de la clase. Por ejemplo en el caso de la cerámica devolvera el tamaño de la cesta.
   - El resto de métodos se calcularán según las dimensiones de la figura usando los metodos del trait `CalculosGeometricos`.

   - Añadir en el `toString` de cada invento los metodos de la interfaz en lugar de llamar directamente a los métodos del trait.

    > [!WARNING]  REFUGIO
    > El refugio esta compuesto por tres materiales diferentes, por lo que el peso se calculara sumando los pesos de los materiales que lo componen.
    > Entenderemos que el refugio es una casa rectangular con techo plano por lo que forma un prisma rectangular.
    > Para calcular el peso entenderemos que un refugio esta formado por:
    > - Un prisma rectangular para la "pared" del techo.
    > - Cuatro prismas rectangulares para las "paredes" de la casa.
    > - Un prisma rectangular para el suelo.
    > 
    > **La altura de estos prismas sera el grosor del material.**

5. **Función Principal**

   - Añadir el invento Cerámica a la función principal del sistema para que se ejecute y se muestre el resultado de `probarInvento`.

## Testing

Los tests se ejecutarán en la rama **main** cuando el mensaje del commit comience con '**deploy:** + MENSAJE DEL COMMIT' y se haga un push.

Los tests necesarios para el código incluyen:

Para todas las clases de inventos deben tener los siguientes tests:

- **testCalcularPeso**: Verifica que el método `calcularPeso` retorne el peso correcto de la piedra afilada.
- **testCalcularVolumen**: Verifica que el método `calcularVolumen` retorne el volumen correcto de la piedra afilada.
- **testCalcularArea**: Verifica que el método `calcularArea` retorne el área correcta de la piedra afilada.
- **testCalcularSuperficie**: Verifica que el método `calcularSuperficie` retorne la superficie correcta de la piedra afilada.
- **obtenerTamanyo**: Verifica que el método `calcularEficiencia` retorne la eficiencia correcta de la piedra afilada.

CeramicaTest

- **testCalcularEficiencia**: Verifica que el método `calcularEficiencia` retorne la eficiencia correcta de la cerámica.
- **testCalcularPuntuacion**: Verifica que el método `calcularPuntuacion` retorne la puntuación correcta de la cerámica.
- **testToString**: Verifica que el método `toString` retorne la cadena correcta de la cerámica.
- **testInventosPrevios**: Verifica que los inventos previos de la cerámica sean correctos.
