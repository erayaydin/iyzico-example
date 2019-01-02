# Iyzico 3D Secure Örneği
---

## Gereksinimler

- PHP 7.1^
- Composer
- Bower
- PDO Extension
- Iyzipay-PHP Composer Package

## Kurulum

Bower kurulumu:

```
$ npm install -g bower
```

Bower paketlerinin kurulumu:

```
$ bower install
```

Composer paketlerinin kurulumu:

```
$ composer install
```

## Konfigürasyon

Kurulum sonrası `config.php` dosyasını düzenlemeyi unutmayınız!

## Sayfalar

| Sayfa Adresi | Açıklama |
| --- | --- |
| index.php | Sepetin ayarlandığı sayfadır |
| index.php?c=payment&a=index | Ödeme işleminin yapıldığı, kullanıcı ve kart bilgilerinin alındığı sayfadır |
| index.php?c=payment&a=payment | Kartın kontrol edildiği ve 3D durumuna göre ödeme sayfasına yönlendirildiği sayfadır |
| index.php?c=payment&a=result | Banka tarafından kullanıcıyı geri yönlendirdiğimiz sayfadır. 3D kontrolü ve para çekim işlemi yapılır |
| index.php?c=payment&a=success | Başarıı durumdaki ödeme sayfasıdır |
| index.php?c=payment&a=error | Hata oluşan ödeme sayfasıdır |
