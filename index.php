<?php
// Desactivar reporte de errores para que no ensucien la respuesta de Telegram
error_reporting(0);

$token = "8547369590:AAFnITTBYETjRopmY7U7hJREcnnBEKR5S3o";

// Leer el mensaje que envía Telegram
$input = file_get_contents("php://input");
$update = json_decode($input, true);

// Si alguien entra por el navegador, verá esto
if (!$update) {
    echo "Bot Online y Webhook configurado correctamente.";
    exit;
}

$chatId = $update["message"]["chat"]["id"] ?? null;
$message = $update["message"]["text"] ?? "";

if ($chatId) {
    $productos = [
        "pasillo 1" => ["carne", "queso", "jamon", "jamón"],
        "pasillo 2" => ["leche", "yogurth", "yogurt", "cereal"],
        "pasillo 3" => ["bebidas", "jugos", "jugo"],
        "pasillo 4" => ["pan", "pasteles", "tortas", "torta"],
        "pasillo 5" => ["detergente", "lavaloza"]
    ];

    $mensajeLimpio = mb_strtolower(trim($message));
    $response = "Lo siento, no encuentro ese producto. Prueba con: Carne, Leche o Pan.";

    if ($mensajeLimpio == "/start") {
        $response = "¡Hola! Bienvenido al buscador de pasillos. ¿Qué producto buscas?";
    } else {
        foreach ($productos as $pasillo => $lista) {
            if (in_array($mensajeLimpio, $lista)) {
                $response = "El producto " . ucfirst($mensajeLimpio) . " está en el " . ucfirst($pasillo) . ". 🛒";
                break;
            }
        }
    }

    // ENVIAR RESPUESTA A TELEGRAM
    $url = "https://api.telegram.org/bot$token/sendMessage";
    $postData = [
        'chat_id' => $chatId,
        'text' => $response
    ];

    // Usamos cURL con opciones de seguridad desactivadas para evitar bloqueos de Render
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_exec($ch);
    curl_close($ch);
}
