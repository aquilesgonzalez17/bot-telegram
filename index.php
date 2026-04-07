<?php
// 1. Configuración inicial
$token = "8547369590:AAFnITTBYETjRopmY7U7hJREcnnBEKR5S3o";

// 2. Capturar datos de Telegram
$input = file_get_contents("php://input");
$update = json_decode($input, true);

// Respuesta para el navegador (para saber que el archivo está ahí)
if (!$update) {
    echo "Servidor listo y esperando a Telegram. Token: " . substr($token, 0, 5) . "xxx";
    exit;
}

$chatId = $update["message"]["chat"]["id"] ?? null;
$message = $update["message"]["text"] ?? "";

if ($chatId) {
    $mensajeLimpio = mb_strtolower(trim($message));
    
    // Lógica de pasillos
    $productos = [
        "pasillo 1" => ["carne", "queso", "jamon", "jamón"],
        "pasillo 2" => ["leche", "yogurth", "yogurt", "cereal"],
        "pasillo 3" => ["bebidas", "jugos", "jugo"],
        "pasillo 4" => ["pan", "pasteles", "tortas", "torta"],
        "pasillo 5" => ["detergente", "lavaloza"]
    ];

    $response = "No encuentro ese producto. Prueba con: Carne, Leche o Pan.";

    if ($mensajeLimpio == "/start") {
        $response = "¡Hola! Bienvenido al bot del súper. ¿Qué buscas?";
    } else {
        foreach ($productos as $pasillo => $lista) {
            if (in_array($mensajeLimpio, $lista)) {
                $response = "El producto " . ucfirst($mensajeLimpio) . " está en el " . ucfirst($pasillo) . ". 🛒";
                break;
            }
        }
    }

    // 3. ENVÍO POR cURL (A prueba de fallos)
    $url = "https://api.telegram.org/bot$token/sendMessage";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
        'chat_id' => $chatId,
        'text' => $response
    ]));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Importante para Render
    curl_exec($ch);
    curl_close($ch);
}
