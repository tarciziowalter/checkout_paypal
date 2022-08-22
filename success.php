<?php 

    require_once "config.php";

    if(isset($_GET['plan_id']) && isset($_GET['subscription_id'])){

        $plan_id = (string)$_GET['plan_id'];
        $subscription_id = (string)$_GET['subscription_id'];

    }

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Checkout com Paypal</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.min.css">
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
    <script type="text/javascript" src="assets/js/scripts.js"></script>

    <style>
    .img-flag{
        width:25px
    }
    </style>

</head>
<body>

    <div class="container">
        <div class="py-5 text-center">
            <img class="d-block mx-auto mb-4" src="https://upload.wikimedia.org/wikipedia/commons/3/39/PayPal_logo.svg" alt="" width="160">
            <div class="alert alert-success" role="alert">
                <strong>ASSINATURA ATIVADA COM SUCESSO!</strong><br><br>
                <strong>Código do plano:</strong> <i><?=$plan_id;?></i><br>
                <strong>Código da assinatura:</strong> <i><?=$subscription_id;?></i><br><br>
                <a href="index.php" class="btn btn-success mb-3">Efetuar nova compra</a>
            </div>

        </div>

    </div>
    
    
    
</body>
</html>