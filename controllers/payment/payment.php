<?php
session_start();

// Satın alınacak ürünler
$products = $_SESSION['products'];
/* ben burada ürünleri veritabanına kaydetmedim, toplam tutarı kaydettim sadece, sen veritabanına kaydedebilirsin (kim ne almış görmek için) */

// Kart bilgileri
$card = $_POST['card'];

// Adres Bilgileri
$address = $_POST['address'];

// Kişi Bilgileri (bu zaten normalde giriş yapan kullanıcıdan geliyor, ziyaretçi ise elbette onun bilgilerini yine bu şekilde alacaksın)
$buyer = $_POST['buyer'];

// Toplam tutar
$totalPrice = 0;
foreach($products as $product)
    $totalPrice += $product['price'];

/**
 * Veritabanımıza ödeme ile ilgili bilgileri kaydedelim...
 */
// Kargo bilgileri
$shippingInsert = $db->prepare("INSERT INTO addresses(name, city, country, address, zipcode) VALUES(:name, :city, :country, :address, :zipcode)");
$shippingInsert->execute([
    'name' => $address['name'],
    'city' => $address['city'],
    'country' => $address['country'],
    'address' => $address['address'],
    'zipcode' => $address['zipcode'],
]);
$shippingModel = $db->query("SELECT * FROM addresses WHERE id = ".$db->lastInsertId())->fetch(PDO::FETCH_OBJ);
// Fatura bilgileri
$billingInsert = $db->prepare("INSERT INTO addresses(name, city, country, address, zipcode) VALUES(:name, :city, :country, :address, :zipcode)");
$billingInsert->execute([
    'name' => $address['name'],
    'city' => $address['city'],
    'country' => $address['country'],
    'address' => $address['address'],
    'zipcode' => $address['zipcode'],
]);
$billingModel = $db->query("SELECT * FROM addresses WHERE id = ".$db->lastInsertId())->fetch(PDO::FETCH_OBJ);
// Kullanıcı bilgileri (zaten bunu giriş yapan kullanıcıdan alacaksın, eğer giriş yapan kullanıcı değilse order_user şeklinde ayrı bir tabloda ziyaretçiyi tutabilirsin)
$userInsert = $db->prepare("INSERT INTO users(name, surname, phone, email, identity, city, country) VALUES(:name, :surname, :phone, :email, :identity, :city, :country)");
$userInsert->execute([
    'name' => $buyer['name'],
    'surname' => $buyer['surname'],
    'phone' => $buyer['phone'],
    'email' => $buyer['email'],
    'identity' => $buyer['identity'],
    'city' => $buyer['city'],
    'country' => $buyer['country'],
]);
$userModel = $db->query("SELECT * FROM users WHERE id = ".$db->lastInsertId())->fetch(PDO::FETCH_OBJ);
// Ödeme bilgileri (hepsinin toplandığı yer)
$orderInsert = $db->prepare("INSERT INTO orders(user_id, shipping_id, billing_id, price, status, order_at, order_ip) VALUES(:user, :shipping, :billing, :price, :status, :orderdate, :ip)");
$orderInsert->execute([
    'user' => $userModel->id,
    'shipping' => $shippingModel->id,
    'billing' => $billingModel->id,
    'price' => $totalPrice,
    'status' => 0, // başlangıç olarak ödemenin durumu 0 (sonuçlandırılmadı)
    'orderdate' => date('Y-m-d H:i:s'),
    'ip' => $buyer['ip'], // kullanıcının o anki ip sini alırsın...
]);
$orderModel = $db->query("SELECT * FROM orders WHERE id = ".$db->lastInsertId())->fetch(PDO::FETCH_OBJ);

/**
 * Kart hakkında bilgi alalım...
 *
 * Bize kartın türünü verecek, buna göre 3D'ye veya normal pos işlemine yönlendireceğiz.
 */
$cardInfoRequest = new \Iyzipay\Request\RetrieveInstallmentInfoRequest();
$cardInfoRequest->setLocale(\Iyzipay\Model\Locale::TR);
$cardInfoRequest->setConversationId($orderModel->id);
$cardInfoRequest->setBinNumber(substr($card['number'],0, 6));
$cardInfoRequest->setPrice($totalPrice);
$cardInfo = \Iyzipay\Model\InstallmentInfo::retrieve($cardInfoRequest, $iyziOptions);

/**
 * Hata var mı bir bakalım...
 */
if($cardInfo->getStatus() != "success"){
    createErrorMessage($orderModel->id, $cardInfo->getErrorCode(), $cardInfo->getErrorMessage());
    redirect("index.php?c=payment&a=error&order_id=".$orderModel->id);
    return;
}
/**
 * Kart bilgilerini şöyle bir özetleyelim...
 */
$cardDetails = $cardInfo->getInstallmentDetails()[0];
$cardType = $cardDetails->getCardType(); // Kartın tipi: CREDIT_CARD, DEBIT_CARD, PREPAID_CARD
$cardAssociation = $cardDetails->getCardAssociation(); // Kartın kuruluşu: TROY, VISA, MASTER_CARD, AMERICAN_EXPRESS
$cardFamily = $cardDetails->getCardFamilyName(); // Kartın ailesi: Bonus, Axess, World, Maximum, Paraf, CardFinans, Advantage
$force3d = (bool) $cardDetails->getForce3ds(); // Kartın 3D zorunluluğunun olup olmadığı. Eğer zorunlu ise işlem kesinlikle 3D ile yapılmalıdır, zorunlu değilse 3D'siz istenirse yapılabilir.
$bankName = $cardDetails->getBankName(); // Kartın ait olduğu bankanın adı

/**
 * Ödeme işlemine başlayalım...
 */

// Ödeme ile ilgili bilgiler...
$paymentRequest = new \Iyzipay\Request\CreatePaymentRequest();
$paymentRequest->setLocale(\Iyzipay\Model\Locale::TR);
$paymentRequest->setConversationId($orderModel->id);
$paymentRequest->setPrice($totalPrice);
$paymentRequest->setPaidPrice($totalPrice);
$paymentRequest->setCurrency(\Iyzipay\Model\Currency::TL);
$paymentRequest->setInstallment(1); // Taksit sayısı değiştirilebilir, yukardaki kart bilgilerine göre ekstra taksit seçeneği de üzerine koyabilirsin
//$paymentRequest->setBasketId("B67832"); // Sepeti ayrı bir yerde tutuyorsan onun ID'sini verebilirsin. Zorunlu bir değer değil, tamamen bizim kendi sistemimize yardımcı olsun diye eklenmiş bir değer
$paymentRequest->setPaymentChannel(\Iyzipay\Model\PaymentChannel::WEB); // Ödemenin yapıldığı ortam: WEB, MOBILE, MOBILE_WEB, MOBILE_IOS, MOBILE_ANDROID, MOBILE_WINDOWS, MOBILE_TABLET, MOBILE_PHONE
$paymentRequest->setPaymentGroup(\Iyzipay\Model\PaymentGroup::PRODUCT); // Neyin ödemesi yapılıyor? Abonelik vs? Değerler: PRODUCT, LISTING, SUBSCRIPTION, OTHER (Zorunlu değil)
$paymentRequest->setCallbackUrl($config['app']['url']."/index.php?c=payment&a=result"); // Ödeme sonucunda; banka kullanıcıyı hangi adrese yönlendirsin? (bize gelince kontrol yapıcaz)

// Kart ile ilgili bilgiler...
$paymentCard = new \Iyzipay\Model\PaymentCard();
$paymentCard->setCardHolderName($card['name']);
$paymentCard->setCardNumber($card['number']);
$paymentCard->setExpireMonth(explode("/", $card['expire'])[0]);
$paymentCard->setExpireYear(explode("/", $card['expire'])[1]);
$paymentCard->setCvc($card['cvc']);
$paymentCard->setRegisterCard(0); // Kartı kaydetmene gerek yok. (abonelik gibi durumlarda 1 olması işe yarar)
$paymentRequest->setPaymentCard($paymentCard);

// Satın alan kişi bilgileri...
$paymentBuyer = new \Iyzipay\Model\Buyer();
$paymentBuyer->setId($userModel->id);
$paymentBuyer->setName($userModel->name);
$paymentBuyer->setSurname($userModel->surname);
$paymentBuyer->setGsmNumber($userModel->phone);
$paymentBuyer->setEmail($userModel->email);
$paymentBuyer->setIdentityNumber($userModel->identity);
$paymentBuyer->setIp($orderModel->order_ip);
$paymentBuyer->setCity($userModel->city);
$paymentBuyer->setCountry($userModel->country);
$paymentBuyer->setRegistrationAddress($billingModel->address);
//$paymentBuyer->setZipCode("34732"); // zorunlu değil, satın alanın postakodu
$paymentRequest->setBuyer($paymentBuyer);

// Kargo bilgileri
$shippingAddress = new \Iyzipay\Model\Address();
$shippingAddress->setContactName($shippingModel->name);
$shippingAddress->setCity($shippingModel->city);
$shippingAddress->setCountry($shippingModel->country);
$shippingAddress->setAddress($shippingModel->address);
$shippingAddress->setZipCode($shippingModel->zipcode);
$paymentRequest->setShippingAddress($shippingAddress);

// Fatura bilgileri
$billingAddress = new \Iyzipay\Model\Address();
$billingAddress->setContactName($billingModel->name);
$billingAddress->setCity($billingModel->city);
$billingAddress->setCountry($billingModel->country);
$billingAddress->setAddress($billingModel->address);
$billingAddress->setZipCode($billingModel->zipcode);
$paymentRequest->setBillingAddress($billingAddress);

// Satın alınacak ürünler bilgileri (SEPET)
$basketItems = [];
foreach($products as $product){
    $basketItem = new \Iyzipay\Model\BasketItem();
    $basketItem->setId($product['id']);
    $basketItem->setName($product['name']);
    if($product['type'] == "Fiziksel")
        $basketItem->setItemType(\Iyzipay\Model\BasketItemType::PHYSICAL);
    else
        $basketItem->setItemType(\Iyzipay\Model\BasketItemType::VIRTUAL);
    $basketItem->setPrice($product['price']);
    $basketItem->setCategory1("Deneme Kat1"); // zorunlu alan, ana kategori
    $basketItems[] = $basketItem;
}
$paymentRequest->setBasketItems($basketItems);

/**
 * Yapılacak işlem 3D POS ise...
 */
if($force3d || $cardType == "DEBIT_CARD" || true){ // Burada "|| true" ekledim çünkü her işlemde 3D'ye yönlendirmek istedim
    $securePayment = \Iyzipay\Model\ThreedsInitialize::create($paymentRequest, $iyziOptions);
    echo $securePayment->getHtmlContent(); // 3D ödeme ekranını kullanıcıya göster...
}

/**
 * Yapılacak işlem POS işlemi ise...
 */
//...