<?php

// Bu simülasyonda sepetimiz $request değişkeni oluyor
$request = $_POST["product"];
// Sepeti önbellekte tut
session_start();
$_SESSION['products'] = $request;

?>
<form action="index.php?c=payment&a=payment" method="post">

    <div class="row">
        <div class="col-sm">
            <div class="card">
                <h5 class="card-header">Kullanıcı Bilgileri</h5>
                <div class="card-body">
                    <div class="form-group">
                        <label for="buyer-id">Kullanıcı ID</label>
                        <input type="text" class="form-control" value="U01" name="buyer[id]" id="buyer-id" placeholder="U01">
                    </div>
                    <div class="form-group">
                        <label for="buyer-name">Kullanıcı Ad</label>
                        <input type="text" class="form-control" value="Mehmet" name="buyer[name]" id="buyer-name" placeholder="Mehmet">
                    </div>
                    <div class="form-group">
                        <label for="buyer-surname">Kullanıcı Soyad</label>
                        <input type="text" class="form-control" value="Baz" name="buyer[surname]" id="buyer-surname" placeholder="Baz">
                    </div>
                    <div class="form-group">
                        <label for="buyer-phone">Telefon</label>
                        <input type="text" class="form-control" value="+905071234567" name="buyer[phone]" id="buyer-phone" placeholder="+90...">
                    </div>
                    <div class="form-group">
                        <label for="buyer-email">E-Posta Adresi</label>
                        <input type="text" class="form-control" value="mehmet@baz.com" name="buyer[email]" id="buyer-email" placeholder="mehmet@baz.com">
                    </div>
                    <div class="form-group">
                        <label for="buyer-identity">TC Kimlik No</label>
                        <input type="text" class="form-control" value="11417654675" name="buyer[identity]" id="buyer-identity" placeholder="TC No">
                    </div>
                    <div class="form-group">
                        <label for="buyer-city">Şehir</label>
                        <input type="text" class="form-control" value="Konya" name="buyer[city]" id="buyer-city" placeholder="Satın Alan Şehir">
                    </div>
                    <div class="form-group">
                        <label for="buyer-country">Ülke</label>
                        <input type="text" class="form-control" value="Turkey" name="buyer[country]" id="buyer-country" placeholder="Satın Alan Ülke">
                    </div>
                    <input type="hidden" name="buyer[ip]" value="56.43.12.122" />
                </div>
            </div>
        </div>
        <div class="col-sm">
            <div class="card">
                <h5 class="card-header">Adres Bilgileri</h5>
                <div class="card-body">
                    <div class="form-group">
                        <label for="address-name">Alıcı Adı</label>
                        <input type="text" class="form-control" value="Mehmet Baz" name="address[name]" id="address-name" placeholder="Mehmet Baz">
                    </div>
                    <div class="form-group">
                        <label for="address-city">Alıcı Şehir</label>
                        <input type="text" class="form-control" value="Istanbul" name="address[city]" id="address-city" placeholder="Şehir">
                    </div>
                    <div class="form-group">
                        <label for="address-country">Alıcı Ülke</label>
                        <input type="text" class="form-control" value="Turkey" name="address[country]" id="address-country" placeholder="Ülke">
                    </div>
                    <div class="form-group">
                        <label for="address-address">Alıcı Adres</label>
                        <textarea class="form-control" rows="1" name="address[address]" id="address-address" placeholder="Adres Bilgisi">Nidakule Göztepe, Merdivenköy Mah. Bora Sok. No:1</textarea>
                    </div>
                    <div class="form-group">
                        <label for="address-zipcode">Posta Kodu</label>
                        <input type="text" class="form-control" value="34732" name="address[zipcode]" id="address-zipcode" placeholder="Posta Kodu">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-sm">
            <div class="card">
                <h5 class="card-header">Kart Bilgileri</h5>
                <div class="card-body">

                    <div class="form-group">
                        <label for="card-name">Ad Soyad</label>
                        <input type="text" class="form-control" value="Mehmet Baz" name="card[name]" id="card-name" placeholder="Mehmet Baz">
                    </div>

                    <div class="form-group">
                        <label for="card-number">Kart No</label>
                        <input type="text" class="form-control" value="5528790000000008" name="card[number]" id="card-number" placeholder="5528790000000008">
                    </div>

                    <div class="form-group">
                        <label for="card-expire">Son Kullanma Tarihi</label>
                        <input type="text" class="form-control" value="12/2030" name="card[expire]" id="card-expire" placeholder="AA/YYYY">
                    </div>

                    <div class="form-group">
                        <label for="card-cvc">CVC</label>
                        <input type="text" class="form-control" value="123" name="card[cvc]" id="card-cvc" placeholder="123">
                    </div>

                </div>
            </div>
        </div>
    </div>

    <p class="text-center mt-5">
        <button type="submit" class="btn btn-success">Ödemeyi Yap</button>
    </p>
</form>