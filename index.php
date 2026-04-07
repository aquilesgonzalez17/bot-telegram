<?php
// Evitar que errores de PHP rompan la comunicación
error_reporting(E_ALL);
ini_set('display_errors', 0);

$token = "8547369590:AAFnITTBYETjRopmY7U7hJREcnnBEKR5S3o";

// Leer datos de Telegram
$input = file_get_contents("php://input");
$update = json_decode($input, true);

// Respuesta visual para ti en el navegador
if (!$update) {
    echo "Servidor de Pasillos Online ✅. Telegram está conectado.";
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

    // ENVÍO DE RESPUESTA (Método ultra-directo)
    $url = "https://api.telegram.org/bot$token/sendMessage?chat_id=$chatId&text=" . urlencode($response);
    
    // Esto ejecuta la URL de envío
    file_get_contents($url);
}
