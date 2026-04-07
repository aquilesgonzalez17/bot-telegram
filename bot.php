<?php
$token = "8547369590:AAFnITTBYETjRopmY7U7hJREcnnBEKR5S3o";

$input = file_get_contents("php://input");
file_put_contents("log.txt", "Fecha: " . date("Y-m-d H:i:s") . " - Input: " . $input . PHP_EOL, FILE_APPEND);

$update = json_decode($input, true);
$chatId = $update["message"]["chat"]["id"] ?? null;
$message = $update["message"]["text"] ?? "";

if (!$chatId) {
    exit;
}

$mensajeLimpio = trim($message);
$response = "No tengo una respuesta configurada para eso"; 

if ($mensajeLimpio == "/start") {
    $response = "Bienvenido ...";
} elseif ($mensajeLimpio == "como me ira en la sumativa?") {
    $response = "Te ira muy bien";
}elseif ($mensajeLimpio == "hola") {
    $response = "Hola, espero te encuentres muy bien , en que te puedo ayudar hoy?";
}

enviarMensaje($chatId, $response, $token);

function enviarMensaje($chatId, $text, $token) {
    $url = "https://api.telegram.org/bot" . $token . "/sendMessage";
    
    $data = json_encode([
        'chat_id' => $chatId,
        'text' => $text
    ]);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']); // Especificamos JSON
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $result = curl_exec($ch);
    file_put_contents("log.txt", "Resultado envio: " . $result . PHP_EOL, FILE_APPEND);
    curl_close($ch);
}
