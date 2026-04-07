<?php
// Desactivar errores para evitar que interfieran con la respuesta
error_reporting(0);
ini_set('display_errors', 0);

$token = "8547369590:AAFnITTBYETjRopmY7U7hJREcnnBEKR5S3o";

// 1. Capturar los datos de Telegram
$input = file_get_contents("php://input");
$update = json_decode($input, true);

// Respuesta simple para humanos que entren por el navegador
if (!$update) {
    echo "Servidor de Bot activo. Funcionando correctamente.";
    exit;
}

// 2. Extraer información del mensaje
$chatId = $update["message"]["chat"]["id"] ?? null;
$message = $update["message"]["text"] ?? "";

if ($chatId) {
    // 3. Diccionario de productos
    $productos = [
        "pasillo 1" => ["carne", "queso", "jamon", "jamón"],
        "pasillo 2" => ["leche", "yogurth", "yogurt", "cereal"],
        "pasillo 3" => ["bebidas", "jugos", "jugo"],
        "pasillo 4" => ["pan", "pasteles", "tortas", "torta"],
        "pasillo 5" => ["detergente", "lavaloza"]
    ];

    $mensajeLimpio = mb_strtolower(trim($message));
    $response = "Lo siento, no encuentro ese producto. Prueba con: Carne, Leche, Bebidas o Pan.";

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

    // 4. ENVÍO DE RESPUESTA (Usando el método más robusto posible)
    $url = "https://api.telegram.org/bot$token/sendMessage";
    
    $postData = json_encode([
        'chat_id' => $chatId,
        'text' => $response
    ]);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    
    curl_exec($ch);
    curl_close($ch);
}
