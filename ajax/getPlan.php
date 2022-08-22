<?php 

require_once "../config.php";


if(isset($_POST['plan_id'])){

    $access_token = Paypal::getToken();
    $plan_id = $_POST['plan_id'];
    $result = (array) Paypal::showPlanDetails($access_token,$plan_id);
    $price = number_format($result['billing_cycles'][0]->pricing_scheme->fixed_price->value,2,',','.');
    
    echo json_encode(['result' => $result, 'price' => $price]);
    die;
    
}