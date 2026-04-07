<?php
$token = "8574089167:AAEiM5QvD8Ic2b2nNGZry_M4lPIj0I2e_a4";
$input = file_get_contents("php://input");
$update = json_decode($input, true);

$chatId = $update["message"]["chat"]["id"] ?? null;
$mensajeRaw = $update["message"]["text"] ?? "";
$mensaje = strtolower(trim($mensajeRaw));

if ($chatId) {
    // Lógica de pasillos
    if ($mensaje == "/start") {
        $texto = "¡Hola! Bienvenido al buscador de pasillos. ¿Qué producto buscas?";
    } elseif (in_array($mensaje, ["carne", "pollo", "jamon"])) {
        $texto = "Pasillo 1 🥩";
    } elseif (in_array($mensaje, ["leche", "yogurt", "queso"])) {
        $texto = "Pasillo 2 🥛";
    } elseif (in_array($mensaje, ["bebida", "jugo", "agua"])) {
        $texto = "Pasillo 3 🥤";
    } elseif (in_array($mensaje, ["pan", "galletas"])) {
        $texto = "Pasillo 4 🍞";
    } elseif (in_array($mensaje, ["detergente", "cloro"])) {
        $texto = "Pasillo 5 🧼";
    } else {
        $texto = "No encuentro ese producto. Prueba con: carne, leche o pan.";
    }

    // Envío de respuesta
    $url = "https://api.telegram.org/bot$token/sendMessage?chat_id=$chatId&text=" . urlencode($texto);
    file_get_contents($url);
}
?>
