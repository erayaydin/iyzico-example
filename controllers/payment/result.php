<?php

session_start();

// Bu değerlerin boş olup olmadığını kontrol et.... (kullanıcı işlemin ortasında F5 basabilir veya saldırgan olabilir)
$status = $_POST['status'];
$paymentId = $_POST['paymentId'];
$orderData = $_POST['conversationData'];
$orderId = $_POST['conversationId'];
$mdStatus = $_POST['mdStatus']; // Bu değer 1 olarak gelecektir. Henüz para çekme işlemini yapmadık, sadece kullanıcının doğru 3D girdiğini teyit ettik. Şimdi para gelsin!

// Bize gelen orderId ile sistemimizdeki ödemeyi çekelim
$orderModel = $db->query("SELECT * FROM orders WHERE id = ".$orderId)->fetch(PDO::FETCH_OBJ);
// $orderModel'i kontrol et, eğer yoksa = SALDIRGAN! (Bankadan gelmedi)
$db->exec("UPDATE orders SET payment_id = '".$paymentId."' WHERE id = ".$orderModel->id);

if($status == "failure"){
    $db->exec("UPDATE orders SET status = 2 WHERE id = ".$orderModel->id);
    echo "<div class='alert alert-danger'>3D İşlemi Gerçekleştirilmedi! Lütfen işlemi gerçekleştiriniz...</div>";
    return;
}

if($mdStatus != 1){
    $mdErrors = [
        0 => "3-D Secure şifreniz geçersiz!",
        2 => "Kart sahibi kayıtlı değil!",
        3 => "Banka kayıtlı değil!",
        4 => "Kart sahibi henüz bankaya üyeliğini doğrulamamış",
        5 => "Doğrulama yapılamadı!",
        6 => "3-D Secure Hatası!",
        7 => "Sistem Hatası!",
        8 => "Kart numarası geçersiz veya bilinmiyor",
    ];

    // Hatayı sisteme kaydet
    $db->exec("UPDATE orders SET status = 2 WHERE id = ".$orderModel->id);
    $db->exec("INSERT INTO order_errors(order_id, code, message) VALUES('".$orderModel->id."', 'M".$mdStatus."', '".$mdErrors[$mdStatus]."')");

    echo "<div class='alert alert-danger'>".$mdErrors[$mdStatus]."</div>";
} else {

    // ödemeyi yapalım!
    $paymentRequest = new \Iyzipay\Request\CreateThreedsPaymentRequest();
    $paymentRequest->setLocale(\Iyzipay\Model\Locale::TR);
    $paymentRequest->setConversationId($orderId);
    $paymentRequest->setPaymentId($paymentId); // ZORUNLU
    $paymentRequest->setConversationData($orderData);

    $getPayment = \Iyzipay\Model\ThreedsPayment::create($paymentRequest, $iyziOptions);

    if($getPayment->getStatus() == "failure"){
        $errCode = $getPayment->getErrorCode();
        $errMsg = $getPayment->getErrorMessage();
        createErrorMessage($orderModel->id, $errCode, $errMsg);
        redirect("index.php?c=payment&a=error&order_id=".$orderModel->id);
        return;
    }

    // Ödeme TAMAMEN başarılı
    $db->exec("UPDATE orders SET status = 1 WHERE id = ".$orderModel->id);

    $price = $getPayment->getPrice(); // Alınan tutar
    $installment = $getPayment->getInstallment(); // yapılan taksit
    $returnPaymentId = $getPayment->getPaymentId(); // PaymentID (Bunu sakla, Iyzico en ufak şeyde bunu sorar)

    // İşlem sonrasında "başarılı olduğuna dair" bir adrese yönlendiriyoruz. Kullanıcı F5 basmasın. Bu adrese
    // banka POST gönderiyor. Aktif olarak kullanmaya gerek yok

    // Kısaca: YÖNLENDİR

    // Burada GET parametresi ile tutarı paymentId'yi orderId'yi vermemizdeki sebep, debug amaçlı sadece göstermek
    // gerçek ortamda, bunları göndermene gerek yok, kullanıcıya sadece "Ödemeniz için Teşekkür ederiz" desen yeter
    // Detay verme.

    // Sebep: kullanıcı F5 basabilir.
    redirect("index.php?c=payment&a=success&order_id=".$orderId);

}