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

// Convertimos a minúsculas para que sea más fácil comparar
$mensajeLimpio = trim(mb_strtolower($message));
$response = "Lo siento, no encuentro ese producto. Intenta con: Carne, Leche o Pan."; 

if ($mensajeLimpio == "/start") {
    $response = "¡Hola! Bienvenido al buscador de pasillos del supermercado. ¿Qué producto estás buscando?";
} 
// Pasillo 1: Carnes
elseif ($mensajeLimpio == "carne" || $mensajeLimpio == "pollo" || $mensajeLimpio == "jamon") {
    $response = "El producto " . ucfirst($mensajeLimpio) . " lo puedes encontrar en el Pasillo 1 🥩";
}
// Pasillo 2: Lácteos
elseif ($mensajeLimpio == "leche" || $mensajeLimpio == "yogurt" || $mensajeLimpio == "queso") {
    $response = "El producto " . ucfirst($mensajeLimpio) . " lo puedes encontrar en el Pasillo 2 🥛";
}
// Pasillo 3: Bebidas
elseif ($mensajeLimpio == "bebida" || $mensajeLimpio == "jugo" || $mensajeLimpio == "agua") {
    $response = "El producto " . ucfirst($mensajeLimpio) . " lo puedes encontrar en el Pasillo 3 🥤";
}
// Pasillo 4: Panadería
elseif ($mensajeLimpio == "pan" || $mensajeLimpio == "galletas" || $mensajeLimpio == "torta") {
    $response = "El producto " . ucfirst($mensajeLimpio) . " lo puedes encontrar en el Pasillo 4 🍞";
}
// Pasillo 5: Limpieza
elseif ($mensajeLimpio == "detergente" || $mensajeLimpio == "jabon" || $mensajeLimpio == "cloro") {
    $response = "El producto " . ucfirst($mensajeLimpio) . " lo puedes encontrar en el Pasillo 5 🧼";
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
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $result = curl_exec($ch);
    file_put_contents("log.txt", "Resultado envio: " . $result . PHP_EOL, FILE_APPEND);
    curl_close($ch);
}
