<?php
$token = "8547369590:AAFnITTBYETjRopmY7U7hJREcnnBEKR5S3o";
$input = file_get_contents("php://input");
$update = json_decode($input, true);

// Extraer ID y Mensaje
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
    $response = "No encontré ese producto. Intenta con: Carne, Leche, Bebidas o Pan.";

    if ($mensajeLimpio == "/start") {
        $response = "¡Hola! Bienvenido al bot del Supermercado. ¿Qué buscas?";
    } else {
        foreach ($productos as $pasillo => $lista) {
            if (in_array($mensajeLimpio, $lista)) {
                $response = "El producto " . ucfirst($mensajeLimpio) . " está en el " . ucfirst($pasillo) . ".";
                break;
            }
        }
    }

    // Usamos un método de envío que no requiere cURL complejo
    $sendUrl = "https://api.telegram.org/bot$token/sendMessage?chat_id=$chatId&text=" . urlencode($response);
    file_get_contents($sendUrl);
}
?>
