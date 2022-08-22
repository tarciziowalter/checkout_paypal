<?php 

    require_once "config.php";

    if(isset($_GET['plan_id'])){

        $plan_id = (string)$_GET['plan_id'];

    }
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hotflix - Checkout com Paypal</title>
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
            <img class="d-block mx-auto mb-4" src="assets/img/logo.png" alt="" width="160">
            <h2 class="titulo_plano"></h2>
            <h2 class="valor_plano"></h2>
            <br>
            <input type="hidden" id="plan_id" value="<?=$plan_id;?>">
            <input type="hidden" id="id_meus_cursos" value="123456">
            <input type="hidden" id="nome_escola" value="Escola Digital">
            <div id="paypal-button-container"></div>
            

        </div>

       
    </div>
    
    <script src="https://www.paypal.com/sdk/js?client-id=AfFUmuzlCmvWeMAGdM8o9CLyR57tw4uc2T_ZKVod9WHhW8xDm82M5mypWGBQeyT9RbBcwnmbyaYTQRAl&components=buttons&vault=true&intent=subscription"></script>
    
    <script type="text/javascript">
        $(function(){

            var plan_id = $("#plan_id").val();
            var id_meus_cursos = $("#id_meus_cursos").val();
            var nome_escola = $("#nome_escola").val();

            buscarDadosPlano(plan_id);

            paypal.Buttons({
                style: {
                    layout: 'vertical',
                    color:  'black',
                    shape:  'rect',
                    label:  'checkout'
                },

                createSubscription: function(data, actions) {
                    return actions.subscription.create({
                        'plan_id': plan_id,
                        'custom_id': id_meus_cursos,
                        "application_context" : {
                            "brand_name" : nome_escola,
                            "shipping_preference":"NO_SHIPPING"
                        }
                    });
                },

                onApprove: function(data, actions) {
                    $(location).attr('href','success.php?plan_id='+plan_id+'&subscription_id='+data.subscriptionID);
                }
            }).render('#paypal-button-container');


        })

        function buscarDadosPlano(plan_id){

            $.ajax({
                url:'ajax/getPlan.php',
                type:'post',
                dataType:'json',
                data:{"plan_id":plan_id},
                success: function(retorno){
                    
                    $(".titulo_plano").html(retorno.result.name);
                    $(".valor_plano").html("R$ " + retorno.price);

                },
                error:function(error){
                    console.log(error);
                }
            });

        }
    </script>
</body>
</html>