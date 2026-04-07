<?php
$token = "8547369590:AAFnITTBYETjRopmY7U7hJREcnnBEKR5S3o";

// 1. Ir a buscar los mensajes a Telegram manualmente
$urlUpdates = "https://api.telegram.org/bot$token/getUpdates?offset=-1";
$response = file_get_contents($urlUpdates);
$data = json_decode($response, true);

echo "<h1>Estado del Bot: Buscando mensajes...</h1>";

if (isset($data["result"][0])) {
    $lastUpdate = $data["result"][0];
    $chatId = $lastUpdate["message"]["chat"]["id"];
    $text = strtolower($lastUpdate["message"]["text"]);
    $updateId = $lastUpdate["update_id"];

    echo "Último mensaje recibido: " . $text . " de Chat ID: " . $chatId;

    // 2. Lógica de pasillos
    $productos = [
        "pasillo 1" => ["carne", "queso", "jamon", "jamón"],
        "pasillo 2" => ["leche", "yogurth", "yogurt", "cereal"],
        "pasillo 3" => ["bebidas", "jugos", "jugo"],
        "pasillo 4" => ["pan", "pasteles", "tortas", "torta"],
        "pasillo 5" => ["detergente", "lavaloza"]
    ];

    $respuesta = "No encuentro ese producto. Prueba con: Carne, Leche o Pan.";
    foreach ($productos as $pasillo => $lista) {
        if (in_array($text, $lista)) {
            $respuesta = "El producto " . ucfirst($text) . " está en el " . ucfirst($pasillo) . ". 🛒";
            break;
        }
    }

    // 3. Enviar la respuesta
    $urlSend = "https://api.telegram.org/bot$token/sendMessage?chat_id=$chatId&text=" . urlencode($respuesta);
    file_get_contents($urlSend);
    
    echo "<br><b>Respuesta enviada con éxito.</b>";
} else {
    echo "<br>No hay mensajes nuevos en Telegram.";
}
?>
