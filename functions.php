<?php

function createErrorMessage($orderId, $errCode, $errMsg) {
    $errors = [
        // Buraya hata kodları gelecek...
        '10051' => "Kart limiti yetersiz, yetersiz bakiye",
        '10005' => "İşlem onaylanmadı",
    ];

    $errorText = isset($errors[$errCode]) ? $errors[$errCode] : "Bir Hata Oluştu! Hata Kodu: ".$errCode." Hata Mesajı: ".$errMsg;

    global $db;
    $db->exec("INSERT INTO order_errors(order_id, code, message) VALUES('".$orderId."', '".$errCode."', '".$errorText."')");
    $db->exec("UPDATE orders SET status = 2 WHERE id = ".$orderId);
}

function redirect($url){
    header("Location: ".$url);
    ob_end_flush();
}