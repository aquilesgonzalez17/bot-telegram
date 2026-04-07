<?php
$token = "8547369590:AAFnITTBYETjRopmY7U7hJREcnnBEKR5S3o";

// 1. Obtener la entrada de Telegram
$input = file_get_contents("php://input");
$update = json_decode($input, true);

// Si entras tú por el navegador
if (!$update) {
    die("Esperando mensaje de Telegram... Si ves esto, el archivo está bien.");
}

// 2. Extraer el Chat ID con seguridad
// Buscamos el ID ya sea en un mensaje nuevo o en un comando
$chatId = $update["message"]["chat"]["id"] ?? null;
$text = $update["message"]["text"] ?? "";

if ($chatId) {
    // Definir la respuesta
    $response = "¡Te encontré! Tu ID es: $chatId. Buscaste: $text";

    // 3. Enviar mensaje de vuelta usando cURL (más seguro en Render)
    $url = "https://api.telegram.org/bot$token/sendMessage";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
        'chat_id' => $chatId,
        'text' => $response
    ]));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $result = curl_exec($ch);
    curl_close($ch);
    
    // Ver el resultado en los logs de Render para saber si falló de nuevo
    error_log("Respuesta de Telegram: " . $result);
} else {
    error_log("No se pudo detectar un Chat ID válido.");
}
