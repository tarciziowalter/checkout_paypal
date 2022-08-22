<?php 

    require_once "config.php";

    if(isset($_GET['plano'])){

        $plano = (int)$_GET['plano'];
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
            <h2>Escolha um período</h2>
            <a href="index.php" class="btn btn-dark">Voltar para Planos</a>
        </div>

        <div class="card-deck">
            <div class="card">
                <h5 class="card-header">Mensal</h5>
                <div class="card-body">
                    <h5 class="card-title">R$ 19,90</h5>
                    <p class="card-text">Este é o valor do <strong><?=$nome_plano;?></strong> cobrado mensalmente</p>
                    <a href="finalizar-compra.php?plano=<?=$plano;?>&periodo=mensal&valor=19.90" class="btn btn-danger">Escolher</a>
                </div>
            </div>
            <div class="card">
                <h5 class="card-header">Semestral</h5>
                <div class="card-body">
                    <h5 class="card-title">R$ 39,90</h5>
                    <p class="card-text">Este é o valor do <strong><?=$nome_plano;?></strong> cobrado semestralmente</p>
                    <a href="finalizar-compra.php?plano=<?=$plano;?>&periodo=semestral&valor=39.90" class="btn btn-danger">Escolher</a>
                </div>
            </div>
            <div class="card">
                <h5 class="card-header">Anual</h5>
                <div class="card-body">
                    <h5 class="card-title">R$ 49,90</h5>
                    <p class="card-text">Este é o valor do <strong><?=$nome_plano;?></strong> cobrado anualmente</p>
                    <a href="finalizar-compra.php?plano=<?=$plano;?>&periodo=anual&valor=49.90" class="btn btn-danger" nome_plano="<?=$nome_plano;?>" periodo="anual" valor="49.90">Escolher</a>
                </div>
            </div>
        </div>

    </div>
    
    
    
</body>
</html>