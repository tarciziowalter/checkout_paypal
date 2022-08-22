<?php 

class Paypal{
    
    /*
    * OBTER O TOKEN DA CONTA PAYPAL
    * Esse método é necessário ser sempre invocado para que consiga obter o token para então criar os produtos, planos e assinaturas.
    */

    public static function getToken(){

        $clientID = "";
        $secret = "";
	
		if($clientID != "" && $secret != ""){
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, "https://api-m.sandbox.paypal.com/v1/oauth2/token");
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS,"grant_type=client_credentials");
			curl_setopt($curl, CURLOPT_USERPWD, $clientID.":".$secret);        

			$headers = array();
			$headers[] = 'Accept: application/json';

			curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

			$result = curl_exec($curl);

			if (curl_errno($curl)) {
				echo 'Error:' . curl_error($curl);
			}

			curl_close($curl);

			return json_decode($result)->access_token;
			
		}else{
			
			return json_encode('Informe os dados de autenticação do PayPal');
			
		}       

    }

    
    /*
    * CRIAR UM PRODUTO PARA O PLANO DE ASSINATURAS
    * Esse método é necessário ser sempre invocado para que consiga criar os planos de assinatura
    * Para criar um plano no paypal é necessário ter um produto
    * Precisa estar autenticado
    *
    * @param string $access_token Token do paypal
    */

    public static function createProduct($access_token,$product_data){

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, 'https://api-m.sandbox.paypal.com/v1/catalogs/products');
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $product_data);
        curl_setopt($curl, CURLOPT_POST, 1);

        $headers = array();
        $headers[] = 'Accept: application/json';
        $headers[] = 'Authorization: Bearer '.$access_token.'';
        $headers[] = 'Prefer: return=representation';
        $headers[] = 'Content-Type: application/json';

        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($curl);

        if (curl_errno($curl)) {
            echo 'Error:' . curl_error($curl);
        }

        curl_close($curl);

        return json_decode($result);

    }

    /*
    * BUSCAR PRODUTO
    * Esse método é necessário caso queira consultar um produto criado para os planos de assinatura
    * Precisa estar autenticado, ter um produto inserido
    *
    * @param string $access_token Token do paypal
    * @param string $product_id ID do produto criado
    */

    public static function getProduct($access_token,$product_id){

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "https://api.sandbox.paypal.com/v1/catalogs/products/".$product_id);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $headers = array();
        $headers[] = 'Accept: application/json';
        $headers[] = 'Authorization: Bearer '.$access_token.'';
        $headers[] = 'Prefer: return=representation';
        $headers[] = 'Content-Type: application/json';

        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($curl);

        if (curl_errno($curl)) {
            echo 'Error:' . curl_error($curl);
        }

        curl_close($curl);

        return json_decode($result);

    }

    /*
    * LISTAR PLANOS
    * Esse método lista todos os planos criados para um produto, as métricas do plano, por exemplo: Total de Itens.
    * Precisa estar autenticado, ter um produto inserido
    *
    * @param string $access_token Token do paypal
    * @param string $product_id ID do produto criado
    */

    public static function listPlans($access_token,$product_id){

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "https://api-m.sandbox.paypal.com/v1/billing/plans?product_id=".$product_id."&page_size=2&page=1&total_required=true");
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $headers = array();
        $headers[] = 'Accept: application/json';
        $headers[] = 'Authorization: Bearer '.$access_token.'';
        $headers[] = 'Prefer: return=representation';
        $headers[] = 'Content-Type: application/json';

        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($curl);

        if (curl_errno($curl)) {
            echo 'Error:' . curl_error($curl);
        }

        curl_close($curl);

        return json_decode($result);

    }

    /*
    * CRIAR PLANO
    * Esse método é necessário para que consiga criar as assinaturas.
    * Precisa estar autenticado, ter um produto inserido e configurar os dados de pagamento do plano
    *
    * @param string $access_token Token do paypal
    * @param array $product_data Dados do produto
    * @param array $payment_data Dados de pagamento
    */

    public static function createPlan($access_token,$product_data,$payment_data){

        $plan_data = json_encode( 
            [ 
                "product_id" => $product_data->id,
                "name" => $product_data->name,
                "description" => $product_data->description,
                "status" => "ACTIVE",
                "billing_cycles"=> [
                    [
                        "frequency"=> [
                            "interval_unit" => $payment_data['frequency'],
                            "interval_count" => $payment_data['interval']
                        ],
                        "tenure_type" => "REGULAR", 
                        "sequence" => 1, 
                        "total_cycles" => 24,
                        "pricing_scheme" => [
                            "fixed_price" => [
                                "value" => $payment_data['pricing_value'], 
                                "currency_code"=> $payment_data['currency_code']
                            ]
                        ] 
                    ]],
                    "payment_preferences" => [
                        "auto_bill_outstanding" => true,
                        "setup_fee" => [
                            "value" => $payment_data['pricing_fee'],
                            "currency_code"=> $payment_data['currency_code']
                        ],
                        "setup_fee_failure_action" => "CONTINUE", 
                        "payment_failure_threshold"=> 3
                    ],
                    "taxes" => [
                        "percentage" => $payment_data['taxes'],
                        "inclusive" => false
                    ]
            ]);


        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, 'https://api-m.sandbox.paypal.com/v1/billing/plans');
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $plan_data);
        curl_setopt($curl, CURLOPT_POST, 1);

        $headers = array();
        $headers[] = 'Accept: application/json';
        $headers[] = 'Authorization: Bearer '.$access_token.'';
        $headers[] = 'Prefer: return=representation';
        $headers[] = 'Content-Type: application/json';

        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($curl);

        if (curl_errno($curl)) {
            echo 'Error:' . curl_error($curl);
        }

        curl_close($curl);

        return json_decode($result);

    }

    /*
    * EXIBIR DETALHES DO PLANO
    * Esse método exibe os detalhes de um plano criado
    * Precisa estar autenticado, ter um plano inserido
    *
    * @param string $access_token Token do paypal
    * @param string $plan_id ID do plano criado
    */

    public static function showPlanDetails($access_token, $plan_id){

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "https://api-m.sandbox.paypal.com/v1/billing/plans/".$plan_id);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $headers = array();
        $headers[] = 'Accept: application/json';
        $headers[] = 'Authorization: Bearer '.$access_token.'';
        $headers[] = 'Prefer: return=representation';
        $headers[] = 'Content-Type: application/json';

        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($curl);

        if (curl_errno($curl)) {
            echo 'Error:' . curl_error($curl);
        }

        curl_close($curl);

        return json_decode($result);

    }

    /*
    * ATIVAR UM PLANO
    * Esse método ativa um plano caso exista
    * Precisa estar autenticado, ter um plano inserido
    *
    * @param string $access_token Token do paypal
    * @param string $plan_id ID do plano criado
    */

    public static function activatePlan($access_token, $plan_id){

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "https://api-m.sandbox.paypal.com/v1/billing/plans/".$plan_id."/activate");
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, 1);        

        $headers = array();
        $headers[] = 'Accept: application/json';
        $headers[] = 'Authorization: Bearer '.$access_token.'';

        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($curl);

        if (curl_errno($curl)) {
            echo 'Error:' . curl_error($curl);
        }

        curl_close($curl);

        return json_decode($result);


    }

    /*
    * DESATIVAR UM PLANO
    * Esse método desativa um plano caso exista e esteja ativo
    * Precisa estar autenticado, ter um plano inserido
    *
    * @param string $access_token Token do paypal
    * @param string $plan_id ID do plano criado
    */

    public static function deactivatePlan($access_token, $plan_id){

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "https://api-m.sandbox.paypal.com/v1/billing/plans/".$plan_id."/deactivate");
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, 1);        

        $headers = array();
        $headers[] = 'Accept: application/json';
        $headers[] = 'Authorization: Bearer '.$access_token.'';

        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($curl);

        if (curl_errno($curl)) {
            echo 'Error:' . curl_error($curl);
        }

        curl_close($curl);

        return json_decode($result);


    }

    public static function createSubscription($access_token, $plan_id, $subscriber_data, $custom_id){    
        
        $subscription_data = json_encode( 
            [ 
                "plan_id" => $plan_id,
                "start_time" =>  date("Y-m-d\TH:i:s.000\Z", strtotime(date('Y-m-d H:i:s'))),
                "quantity" => "1",
                "shipping_amount" => [
                    "currency_code" => $subscriber_data['currency_code'],
                    "value" => "0"
                ],
                "subscriber" => [
                    "name" => [
                        "given_name" => $subscriber_data['given_name'],
                        "surname" => $subscriber_data['surname']
                    ],
                    "email_address" => $subscriber_data['email_address'],
                    "shipping_address" => [
                        "name" => [
                            "full_name" => $subscriber_data['given_name'] . ' ' . $subscriber_data['surname']
                        ],
                        "address" => [
                            "address_line_1" => $subscriber_data['address_line_1'],
                            "address_line_2" => $subscriber_data['address_line_2'],
                            "admin_area_2" => $subscriber_data['admin_area_2'],
                            "admin_area_1" => $subscriber_data['admin_area_1'],
                            "country_code" => $subscriber_data['country_code']
                        ]
                    ]
                ],
                "application_context" => [
                    "brand_name" => $subscriber_data['brand_name'],
                    "locale" => "pt-BR",
                    "shipping_preference" => "NO_SHIPPING",
                    "user_action" => "CONTINUE",
                    "payment_method" => [
                        "payer_selected" => "PAYPAL",
                        "payee_preferred" => "UNRESTRICTED",
                    ],
                    "return_url" => $subscriber_data['return_url'],
                    "cancel_url" => $subscriber_data['cancel_url']
                ],
                "custom_id" => $custom_id

        ]);

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, 'https://api-m.sandbox.paypal.com/v1/billing/subscriptions');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $subscription_data);
        curl_setopt($curl, CURLOPT_POST, 1);

        $headers = array();
        $headers[] = 'Accept: application/json';
        $headers[] = 'Authorization: Bearer '.$access_token.'';
        $headers[] = 'Prefer: return=representation';
        $headers[] = 'Content-Type: application/json';

        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($curl);

        if (curl_errno($curl)) {
            echo 'Error:' . curl_error($curl);
        }

        curl_close($curl);

        return json_decode($result);

    }

    public static function showSubscriptionDetails(){

    }

    public static function activateSubscription($access_token, $subscription_id){

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "https://api-m.sandbox.paypal.com/v1/billing/subscriptions/".$subscription_id."/activate");
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode(['reason' => 'Reativação da Assinatura']));
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: Bearer '.$access_token.'';

        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($curl);

        if (curl_errno($curl)) {
            echo 'Error:' . curl_error($curl);
        }

        curl_close($curl);

        return json_decode($result);

    }

    public static function cancelSubscription($access_token, $subscription_id){

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "https://api-m.sandbox.paypal.com/v1/billing/subscriptions/".$subscription_id."/cancel");        
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode(['reason' => 'Insatisfeito com o serviço']));
        curl_setopt($curl, CURLOPT_POST, 1);

        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: Bearer '.$access_token.'';

        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($curl);

        if (curl_errno($curl)) {
            echo 'Error:' . curl_error($curl);
        }

        curl_close($curl);

        return json_decode($result);

    }

    public static function listTransactionsSubscription($access_token, $subscription_id, $start_time, $end_time){

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "https://api-m.sandbox.paypal.com/v1/billing/subscriptions/".$subscription_id."/transactions?start_time=".$start_time."&end_time=".$end_time);        
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: Bearer '.$access_token.'';

        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($curl);

        var_dump($result);
        die;

        if (curl_errno($curl)) {
            echo 'Error:' . curl_error($curl);
        }

        curl_close($curl);

        return json_decode($result);

    }



}