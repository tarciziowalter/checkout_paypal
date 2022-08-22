$(function(){


    $("#btnProcessarPagamento").click(function(e){

        e.preventDefault();

        var container = $('.container');
        var message = container.find('#message');

        $.ajax({
            url:'ajax/form.php',
            type:'post',
            dataType:'html',
            data:$("#frmProcessarPagamento").serialize(),
            beforeSend: function(){
                message.html("<div class='alert alert-success'><i class='fa fa-spinner fa-spin fa-2x fa-fw'></i>Aguarde enquanto verificamos seus dados do cartão.</div>");
            },
            success: function(retorno){

                console.log(retorno);

                if(retorno == 'approved'){
                    message.html("<div class='alert alert-success'><i class='fa fa-spinner fa-spin fa-2x fa-fw'></i>Seu pagamento foi aprovado!</div>");
                }

                if(retorno == 'created'){
                    message.html("<div class='alert alert-success'><i class='fa fa-spinner fa-spin fa-2x fa-fw'></i>Seu pagamento ainda não foi aprovado, quando aprovar lhe mandaremos um email com todos os dados</div>");
                }

                if(retorno == 'failed'){
                    message.html("<div class='alert alert-danger'><i class='fa fa-spinner fa-spin fa-2x fa-fw'></i>Seu pagamento foi rejeitado.</div>");
                }
            },
            error:function(error){
                console.log(error);
            }
        });



    })

    $(".btnCheckout").click(function(e){

        e.preventDefault();

        var container = $('.container');
        var message = container.find('#message');
        var nome_plano = $(this).attr('nome_plano');
        var periodo = $(this).attr('periodo');
        var valor = $(this).attr('valor');

        const dados = {
            "nome_plano":nome_plano,
            "periodo":periodo,
            "valor":valor
        }

        $.ajax({
            url:'ajax/form.php',
            type:'post',
            dataType:'json',
            data:{"dados":dados},
            beforeSend: function(){
                message.html("<div class='alert alert-success'><i class='fa fa-spinner fa-spin fa-2x fa-fw'></i>Aguarde enquanto processamos o pagamento com o Paypal.</div>");
            },
            success: function(retorno){

                $(location).attr('href','checkout.php?plan_id=' + retorno.id);

            },
            error:function(error){
                console.log(error);
            }
        });

    })

})