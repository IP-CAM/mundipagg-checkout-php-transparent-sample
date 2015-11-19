<?php

try
{
    // Carrega dependências
    require_once(dirname(__FILE__) . '/vendor/autoload.php');

    // Configura o ambiente da Mundi a ser usado
    \MundiPagg\ApiClient::setEnvironment(\MundiPagg\Checkout\DataContract\Enum\ApiEnvironmentEnum::STAGING);

    // Configura a MerchantKey a ser utilizada (Chave secreta)
    \MundiPagg\ApiClient::setMerchantKey("54409272-781D-4470-BFA3-C58A5A005B49");

    // Cria objeto de solicitação do token
    $tokenRequest = new \MundiPagg\Checkout\DataContract\Request\TokenRequest();

    // Define dados do pedido
    $tokenRequest->getOrder()
        ->setOrderReference(sprintf("Test #%s", time()))
        ->setAmountInCents(100)
    ;

    // Define opções do pedido
    $tokenRequest->getOptions()
        ->disableAmountInCentsUpdate()
        ->enableCreditCardPayment()
        ->disableBoletoPayment()
        ->disableAntiFraud()
    ;

    //Cria um objeto ApiClient
    $apiClient = new \MundiPagg\ApiClient();

    // Faz a chamada para criação do token
    $tokenResponse = $apiClient->processCheckoutRequest($tokenRequest);

    // Chama a view do form
    require 'form.php';
}
catch (\MundiPagg\Checkout\DataContract\Report\ApiError $error)
{
    http_response_code(500);
    print_r($error);
}
catch (\Exception $ex)
{
    var_dump($ex);
    echo $ex->getMessage();
}