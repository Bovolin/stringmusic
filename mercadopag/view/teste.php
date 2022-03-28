<?php
include("../../class/ClassCompra.php");
$compra = new Comprar();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

<p><?php echo $compra->get_Value('interpretacao', "1", 2); ?></p>
    
</body>
</html>