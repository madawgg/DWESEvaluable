<?php
// Función para poder probar la aplicación en el navegador. No participa en los tests.
function principal() {
    $texto = "&nbsp;&nbsp;cuerda&nbsp;de&nbsp;sisal:&nbsp;&nbsp;ideal&nbsp;para&nbsp;trabajos&nbsp;de&nbsp;jardiner&iacute;a&nbsp;y&nbsp;manualidades.&nbsp;cuerda&nbsp;de&nbsp;c&aacute;&ntilde;amo:&nbsp;&nbsp;muy&nbsp;resistente,&nbsp;ideal&nbsp;para&nbsp;escalada&nbsp;y&nbsp;construcci&oacute;n&nbsp;de&nbsp;herramientas.&nbsp;cuerda&nbsp;de&nbsp;lino:&nbsp;&nbsp;perfecta&nbsp;para&nbsp;pesca&nbsp;y&nbsp;confecci&oacute;n&nbsp;de&nbsp;redes.&nbsp;cuerda&nbsp;de&nbsp;esparto:&nbsp;&nbsp;utilizada&nbsp;en&nbsp;cester&iacute;a&nbsp;y&nbsp;fabricaci&oacute;n&nbsp;de&nbsp;sandalias.";
    imprimirCuerda($texto);
}



// Función para procesar una parte de cuerda
function procesarParteCuerda($parte) {
    // Reemplazar los caracteres &nbsp; (espacio no separable) con espacios normales
    // \xC2\xA0 = representación hexadecimal del caracter &nbsp
    $parte = str_replace("\xC2\xA0", ' ', $parte);

    // Elimina los espacios al principio y al final.
    $parteLimpia = trim($parte);

    // Si la parte no está vacía y contiene un delimitador ':', divide en nombre y descripción.
    if ($parteLimpia !== '' && strpos($parteLimpia, ':') !== false) {
        $resultados = explode(':', $parteLimpia, 2); 
     
            $nombre = trim(strtoupper($resultados[0]));  
            $descripcion = ucfirst(trim($resultados[1]));

            return [
                'nombre' => $nombre,
                'descripcion'=> $descripcion];   
    } else {

        return null;
    }
}


// Función para encriptar nombre y descripción
function encriptarCuerda($nombre, $descripcion, $salt) {
    // Encriptar nombre y descripción
    $nombreEncriptado = crypt($nombre, $salt);
    $descripcionEncriptada = crypt($descripcion, $salt);

    return [
        'nombre' => $nombreEncriptado,
        'descripcion' => $descripcionEncriptada
    ];
}

// Función para procesar el texto de entrada
function procesarTextoCuerda($texto) {
    // Decodifica las entidades HTML en el texto.
    $textoDecodificado = html_entity_decode($texto);
    
    // Dividir el texto, delimitador = '.'.
    $partes = explode('.', $textoDecodificado);
    $partesLimpias = [];
    
    return $partes;
}


function imprimirCuerda($texto, $encriptar = false) {
   
    $partes = procesarTextoCuerda($texto);

    if (!empty($partes)) {
        $resultado = procesarCuerdas($partes, $encriptar);

        // Imprimir resultados si existen
        if (!empty($resultado)) {
            echo $encriptar ? 'Resultado encriptado:<br>' : 'Resultado sin encriptar:<br>';
            echo $resultado;
        } else {
            // Si el resultado está vacío
            echo 'No hay cuerdas válidas.';
        }
    } else {
        // No hay partes válidas.
        echo 'No hay cuerdas válidas.';
    }
}


function procesarCuerdas($cuerdas, $encriptar = false) {
    $cadenaResultado = '';
    $salt = 'Cuerda12345678910';

    foreach ($cuerdas as $parte) {
        $procesado = procesarParteCuerda($parte);
        
        if ($procesado !== null) { 
            $nombre = $procesado['nombre'];
            $descripcion = $procesado['descripcion'];
            
            // Encriptar si es necesario
            if ($encriptar) {
                $encriptado = encriptarCuerda($nombre, $descripcion, $salt);
                $nombre = $encriptado['nombre'];
                $descripcion = $encriptado['descripcion'];
            }
           
            $cadenaResultado .= "Tipo de cuerda:$nombre.<br>Descripción:$descripcion.<br><br>";
        }
    }

    return $cadenaResultado;
}

?>
