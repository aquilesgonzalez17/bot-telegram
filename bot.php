<?php
$token = "8547369590:AAFnITTBYETjRopmY7U7hJREcnnBEKR5S3o"; 

$input = file_get_contents("php://input");
$update = json_decode($input, true);

$chatId = $update["message"]["chat"]["id"] ?? null;
$message = $update["message"]["text"] ?? "";

if (!$chatId) exit;

// 1. Definición de la base de datos de pasillos
$productos = [
    "pasillo 1" => ["carne", "queso", "jamon", "jamón"],
    "pasillo 2" => ["leche", "yogurth", "yogurt", "cereal"],
    "pasillo 3" => ["bebidas", "jugos", "jugo"],
    "pasillo 4" => ["pan", "pasteles", "tortas", "torta"],
    "pasillo 5" => ["detergente", "lavaloza"]
];

// 2. Limpieza del mensaje del usuario
$mensajeUsuario = mb_strtolower(trim($message));
$response = "Lo siento, no encuentro ese producto en mi base de datos. Prueba con algo como 'leche' o 'pan'.";

// 3. Lógica de búsqueda
if ($mensajeUsuario == "/start") {
    $response = "¡Hola! Dime qué producto buscas y te diré en qué pasillo está.";
} else {
    // Recorremos el mapa de productos para encontrar la coincidencia
    foreach ($productos as $pasillo => $lista) {
        if (in_array($mensajeUsuario, $lista)) {
            $response = "El producto **" . ucfirst($mensajeUsuario) . "** se encuentra en el **" . ucfirst($pasillo) . "**. 🛒";
            break; 
        }
    }
}

enviarMensaje($chatId, $response, $token);

function enviarMensaje($chatId, $text, $token) {
    $url = "https://api.telegram.org/bot" . $token . "/sendMessage";
    $data = json_encode([
        'chat_id' => $chatId,
        'text' => $text,
        'parse_mode' => 'Markdown' // Para que las negritas funcionen
    ]);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_exec($ch);
    curl_close($ch);
}
