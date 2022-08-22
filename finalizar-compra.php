<?php 

    require_once "config.php";

    if(isset($_GET['plano']) && isset($_GET['periodo']) && isset($_GET['valor'])){

        $plano = (string)$_GET['plano'];
        $nome_plano = "";

        switch ($plano) {
            case '1':
                $nome_plano = "Plano Básico";
                break;
            case '2':
                $nome_plano = "Plano Premium";
                break;
            case '3':
                $nome_plano = "Plano Ultra";
                break;
        }

        $periodo = (string)$_GET['periodo'];
        $valor = (float)$_GET['valor'];
        $desconto = 50;
        $novo_valor = $valor * 50 / 100;

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
            <h2><?=$nome_plano;?> <?=ucfirst($periodo);?> - R$ <?=number_format($valor,2,',','.');?></h2>

            <a href="escolher-periodo.php?plano=<?=$plano;?>" class="btn btn-dark">Voltar para escolher o período</a>
        </div>

        <div class="card-deck">
            <div class="card">
                <h5 class="card-header">Deseja aplicar cupom de desconto?</h5>
                <div class="card-body">
                    <p class="card-text">Cupom de <strong>50%</strong> de desconto</p>
                    <p class="card-text">Valor atual: <strong><strike>R$ <?=number_format($valor,2,',','.');?></strike></strong></p>
                    <p class="card-text">Valor com desconto: <strong>R$ <?=number_format($novo_valor,2,',','.');?></strong></p>
                    
                    <a href="javascript:void(0)" class="btn btn-success btnCheckout" nome_plano="<?=$nome_plano;?>" periodo="<?=$periodo;?>" valor="<?=$novo_valor;?>">Sim quero o desconto</a>
                    <a href="javascript:void(0)" class="btn btn-danger btnCheckout" nome_plano="<?=$nome_plano;?>" periodo="<?=$periodo;?>" valor="<?=$valor;?>">Não obrigado</a>
                </div>
            </div>
        </div>

        <div id="message" class="d-inline-block mt-2"></div>

    </div>
    
    
    
</body>
</html>