<?php
$token = "8547369590:AAFnITTBYETjRopmY7U7hJREcnnBEKR5S3o";

$input = file_get_contents("php://input");
$update = json_decode($input, true);
$chatId = $update["message"]["chat"]["id"] ?? null;
$message = $update["message"]["text"] ?? "";

if (!$chatId) exit;

$mensajeLimpio = mb_strtolower(trim($message));
$response = "No encuentro ese producto. Prueba con: Carne, Leche o Pan."; 

if ($mensajeLimpio == "/start") {
    $response = "¡Hola! Bienvenido al buscador de pasillos.";
} elseif (in_array($mensajeLimpio, ["carne", "queso", "jamon"])) {
    $response = "Pasillo 1 🥩";
} elseif (in_array($mensajeLimpio, ["leche", "yogurt", "cereal"])) {
    $response = "Pasillo 2 🥛";
} elseif (in_array($mensajeLimpio, ["pan", "galletas"])) {
    $response = "Pasillo 4 🍞";
}

enviarMensaje($chatId, $response, $token);

function enviarMensaje($chatId, $text, $token) {
    $url = "https://api.telegram.org/bot" . $token . "/sendMessage";
    $data = json_encode(['chat_id' => $chatId, 'text' => $text]);
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_exec($ch);
    curl_close($ch);
}
