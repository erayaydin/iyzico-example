<?php
$order = $db->query("SELECT * FROM orders WHERE id = ".$_GET['order_id'])->fetch(PDO::FETCH_OBJ);
?>
<div class="alert alert-success">
    <p>Ödemeniz için teşekkür ederiz!</p>
    <p>Ödeme Yapılan Tutar: <?php echo number_format($order->price, 2); ?></p>
    <p>Arkaplanda Saklayacağımız ID: <?php echo $order->payment_id; ?></p>
    <p>Bizdeki ödeme ID: <?php echo $order->id; ?></p>
</div>
