<?php
  class  ControllerPaymentMundiPagg extends Controller {
    public function index() {
      $this->load->language('payment/mundipagg');

      $data['text_instruction'] = $this->language->get('text_instruction');
      $data['text_payable'] = $this->language->get('text_payable');
      $data['text_address'] = $this->language->get('text_address');
      $data['text_payment'] = $this->language->get('text_payment');
      $data['text_loading'] = $this->language->get('text_loading');

      $data['button_confirm'] = $this->language->get('button_confirm');

      $data['payable'] = $this->config->get('cheque_payable');
      $data['address'] = nl2br($this->config->get('config_address'));

      $data['continue'] = $this->url->link('checkout/success');

      /** Mundipagg Default functions */
      try {
        // Carrega dependências
        require_once( dirname( __FILE__ ) . '/vendor/autoload.php' );
        // Configura o ambiente da Mundi a ser usado
        \MundiPagg\ApiClient::setEnvironment( \MundiPagg\Checkout\DataContract\Enum\ApiEnvironmentEnum::STAGING );
        // Configura a MerchantKey a ser utilizada (Chave secreta)
        \MundiPagg\ApiClient::setMerchantKey( "54409272-781D-4470-BFA3-C58A5A005B49" );
        //\MundiPagg\ApiClient::setMerchantKey( "54409272-781D-4470-BFA3-C58A5A005B49" );
        // Cria objeto de solicitação do token
        $tokenRequest = new \MundiPagg\Checkout\DataContract\Request\TokenRequest();
        // Define dados do pedido
        $tokenRequest->getOrder()->setOrderReference( sprintf( "Test #%s", time() ) )->setAmountInCents( 100 );
        // Define opções do pedido
        $tokenRequest->getOptions()->disableAmountInCentsUpdate()->enableCreditCardPayment()->disableBoletoPayment()->disableAntiFraud();
        //Cria um objeto ApiClient
        $apiClient = new \MundiPagg\ApiClient();
        // Faz a chamada para criação do token
        $tokenResponse = $apiClient->processCheckoutRequest( $tokenRequest );
        // Chama a view do form
        //require 'form.php';

      } catch ( \MundiPagg\Checkout\DataContract\Report\ApiError $error ) {
        http_response_code( 500 );
        print_r( $error );
      } catch ( \Exception $ex ) {
        var_dump( $ex );
        echo $ex->getMessage();
      }
      /** Opencart 2.0.x Default */
      $data['header'] = $this->load->controller('common/header');
      $data['column_left'] = $this->load->controller('common/column_left');
      $data['footer'] = $this->load->controller('common/footer');

      $this->response->setOutput($this->load->view('payment/mundipagg.tpl', $data));

    }
  }


