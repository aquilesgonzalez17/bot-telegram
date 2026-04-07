<?php
// 1. Configuración del Token
$token = "8547369590:AAFnITTBYETjRopmY7U7hJREcnnBEKR5S3o";

// 2. Capturar lo que envía Telegram
$input = file_get_contents("php://input");
$update = json_decode($input, true);

// Si entras por el navegador, verás este mensaje (útil para pruebas)
if (!$update) {
    echo "Servidor Online. Esperando mensajes de Telegram...";
    exit;
}

// 3. Extraer datos del mensaje
$chatId = $update["message"]["chat"]["id"] ?? null;
$message = $update["message"]["text"] ?? "";

if ($chatId) {
    // 4. Lógica de pasillos
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

    // 5. Enviar respuesta usando cURL (el método más seguro en Apache)
    $url = "https://api.telegram.org/bot$token/sendMessage";
    $postData = [
        'chat_id' => $chatId,
        'text' => $response
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Evita problemas de certificados en Render
    curl_exec($ch);
    curl_close($ch);
}
