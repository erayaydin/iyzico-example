</div> <!-- /.container -->

<!-- Bootstrap core JavaScript -->
<script src="assets/jquery/dist/jquery.js"></script>
<script src="assets/popper.js/dist/umd/popper.js"></script>
<script src="assets/bootstrap/dist/js/bootstrap.js"></script>
<script src="assets/handlebars/handlebars.js"></script>
<?php if($controller == "home" && $action == "index"): ?>
<script>
    var iter = 0;
    var productRowSource = document.getElementById("product-template").innerHTML;
    var productRowTemplate = Handlebars.compile(productRowSource);

    function addNewProductRow()
    {
        var context = {iter: iter};
        var html    = productRowTemplate(context);
        iter++;

        $("#products").append(html);
    }

    addNewProductRow();
    $("#newProduct").bind('click', function(){
        addNewProductRow();
    });
</script>
<?php endif; ?>
</body>
</html>