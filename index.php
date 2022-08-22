<?php require_once "config.php";?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Checkout com Paypal</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.min.css">

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
            <h2>Escolha um plano</h2>
        </div>

        <div class="card-deck">
            <div class="card">
                <h5 class="card-header">Plano Básico</h5>
                <div class="card-body">
                    <h5 class="card-title">A partir de R$ 19,90 /mensal</h5>
                    <p class="card-text">Categorias: Nutrição</p>
                    <a href="escolher-periodo.php?plano=1" class="btn btn-success">Escolher</a>
                </div>
            </div>
            <div class="card">
                <h5 class="card-header">Plano Premium</h5>
                <div class="card-body">
                    <h5 class="card-title">A partir de R$ 39,90 /mensal</h5>
                    <p class="card-text">Categorias: Nutrição, Fisiculturismo</p>
                    <a href="escolher-periodo.php?plano=2" class="btn btn-success">Escolher</a>
                </div>
            </div>
            <div class="card">
                <h5 class="card-header">Plano Ultra</h5>
                <div class="card-body">
                    <h5 class="card-title">A partir de R$ 49,90 /mensal</h5>
                    <p class="card-text">Categorias: Todas</p>
                    <a href="escolher-periodo.php?plano=3" class="btn btn-success">Escolher</a>
                </div>
            </div>
        </div>

    </div>
    
    
    
</body>
</html>