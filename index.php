<?php
$token = "8547369590:AAFnITTBYETjRopmY7U7hJREcnnBEKR5S3o";

// Leer la entrada de Telegram
$input = file_get_contents("php://input");
$update = json_decode($input, true);

// Esto ayuda a saber si Replit está recibiendo algo
if (!$update) {
    echo "Bot funcionando en Replit. Esperando datos...";
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
    $response = "No encontré ese producto. Intenta con 'carne', 'leche' o 'detergente'.";

    if ($mensajeLimpio == "/start") {
        $response = "¡Hola! Dime qué producto buscas y te diré el pasillo.";
    } else {
        foreach ($productos as $pasillo => $lista) {
            if (in_array($mensajeLimpio, $lista)) {
                $response = "El producto " . ucfirst($mensajeLimpio) . " está en el " . ucfirst($pasillo) . ". 🛒";
                break;
            }
        }
    }

    // Envío de respuesta
    $url = "https://api.telegram.org/bot$token/sendMessage";
    $data = [
        'chat_id' => $chatId,
        'text' => $response
    ];

    $options = [
        'http' => [
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data),
        ],
    ];
    $context  = stream_context_create($options);
    file_get_contents($url, false, $context);
}
