<?php 

require_once "../config.php";


if(isset($_POST['dados'])){

    $access_token = Paypal::getToken();

    //Criar Pedido


    //Criar Registro em Meus Cursos


    //Criar Produto no Paypal
    $product_data = json_encode([ 
        "name" => $_POST['dados']['nome_plano'] . " " . ucfirst($_POST['dados']['periodo']),
        "description" => "Este é um produto digital",
        "type" => "SERVICE",
        "category" => "SOFTWARE"     
    ]);

    $created_product_data = Paypal::createProduct($access_token,$product_data);

    //Criar Plano no Paypal
    $payment_data = array();
    $payment_data['frequency'] = getPeriod($_POST['dados']['periodo'])['period'];
    $payment_data['interval'] = getPeriod($_POST['dados']['periodo'])['interval'];
    $payment_data['pricing_value'] = $_POST['dados']['valor'];
    $payment_data['currency_code'] = "BRL";
    $payment_data['pricing_fee'] = "0.00";
    $payment_data['taxes'] = "0.00";

    $created_plan_data = Paypal::createPlan(Paypal::getToken(),$created_product_data,$payment_data);

    echo json_encode($created_plan_data);
    die;

}

function getPeriod($str){

    $period = "";
    $interval = 0;

    switch ($str) {
        case 'mensal':
            $period = "MONTH";
            $interval = 1;
            break;
        case 'trimestral':
            $period = "MONTH";
            $interval = 3;
            break;
        case 'semestral':
            $period = "MONTH";
            $interval = 6;
            break;
        case 'anual':
            $period = "YEAR";
            $interval = 1;
            break;
    }

    return ['period' => $period, 'interval' => $interval];

}



