<?php
$order = $db->query("SELECT * FROM orders WHERE id = ".$_GET['order_id'])->fetch(PDO::FETCH_OBJ);
$errors = $db->query("SELECT * FROM order_errors WHERE order_id = ".$_GET['order_id'])->fetchAll(PDO::FETCH_OBJ);
?>
<div class="alert alert-danger">
    <p>Ödemeniz sırasında bir sorun oluştu!</p>

    <table class="table table-striped table-bordered table-hover">
        <thead>
        <tr>
            <th>Hata Kodu</th>
            <th>Hata Mesajı</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($errors as $error): ?>
            <tr>
                <td><?php echo $error->code; ?></td>
                <td><?php echo $error->message; ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
