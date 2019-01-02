<?php
// Önceki sepeti sil
session_start();
session_destroy();
?>
<form action="index.php?c=payment&a=index" method="post">
    <div id="products"></div>
    <p class="text-center">
        <button type="button" class="btn btn-success" id="newProduct">Yeni Ürün Ekle</button>
        <button type="submit" class="btn btn-primary">Sepeti Tamamla</button>
    </p>
</form>

<script id="product-template" type="text/x-handlebars-template">
    <div class="form-group">
        <label for="product-{{iter}}-id">Ürün ID</label>
        <input type="text" class="form-control" value="P01" name="product[{{iter}}][id]" id="product-{{iter}}-id" placeholder="P01">
        <small id="emailHelp" class="form-text text-muted">Diğer ürünlerden başka bir ID girdiğinizden emin olunuz.</small>
    </div>
    <div class="form-group">
        <label for="product-{{iter}}-name">Ürün Adı</label>
        <input type="text" class="form-control" value="Deneme Ürün" name="product[{{iter}}][name]" id="product-{{iter}}-name" placeholder="My Awesome Product">
    </div>
    <div class="form-group">
        <label for="product-{{iter}}-type">Ürün Türü</label>
        <select class="form-control" id="product-{{iter}}-type" name="product[{{iter}}][type]">
            <option>Fiziksel</option>
            <option>Sanal</option>
        </select>
    </div>
    <div class="form-group">
        <label for="product-{{iter}}-price">Tutar</label>
        <input type="number" class="form-control" value="100.1" name="product[{{iter}}][price]" id="product-{{iter}}-price" placeholder="1.55" step="0.1" min="0.1">
    </div>
    <hr class="mb-5">
</script>