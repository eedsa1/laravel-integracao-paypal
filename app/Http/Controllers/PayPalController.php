<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;

use PayPal\Api\Refund;
use PayPal\Api\RefundRequest;
use PayPal\Api\Sale;

use \PayPal\Api\VerifyWebhookSignature;
use \PayPal\Api\WebhookEvent;

use Redirect;
use Session;
use URL;

class PayPalController extends Controller
{

	##########################################
	###           pagamentos             #####
	##########################################

	private $_api_context;

	//index que redireciona para a página do paypal
	public function index(){
		return view('index');
	}

    public function __construct(){
    	$paypal_conf = \Config::get('paypal'); 

    	$this->_api_context = new ApiContext(new OAuthTokenCredential($paypal_conf['client_id'], $paypal_conf['secret']));

    	$this->_api_context->setConfig($paypal_conf['settings']);
    }
    

	//função que redireciona para a página do paypal e retorna ao site do cliente
	public function payWithPaypal(Request $request){

		$payer = new Payer();
        $payer->setPaymentMethod('paypal');

		$item_1 = new Item();

		$item_1->setName('Item 1') /** item name **/
            ->setCurrency('BRL')
            ->setQuantity(1)
            ->setPrice($request->get('amount'));

		$item_list = new ItemList();
        $item_list->setItems(array($item_1));

        $amount = new Amount();
        $amount->setCurrency('BRL')
            ->setTotal($request->get('amount'));

	   	$transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($item_list)
            ->setDescription('Descrição da transação');

   	 	$redirect_urls = new RedirectUrls();
        $redirect_urls->setReturnUrl(URL::to('status')) /** Specify return URL **/
            ->setCancelUrl(URL::to('status'));

	  	$payment = new Payment();
        $payment->setIntent('Sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirect_urls)
            ->setTransactions(array($transaction));

	  	try{
	  		$payment->create($this->_api_context);

	  	}	
	  	catch (\PayPal\Exception\PPConnectionException $ex){

	  		if(\Config::get('app.debug')){
	  			\Session::put('error', 'A conexão expirou');
	  			return Redirect::to('/index');
	  		}
	  		else{
	  			\Session::put('error', 'Algum erro ocorreu, desculpe pelo inconveniente');
	  			return Redirect::to('/index');
	  		}
	  	}

	  	// foreach ($payment->getLinks() as $link) {
		//      if ($link->getRel() == 'approval_url') {
	    //          $redirect_url = $link->getHref();
	    //          break;
	    //		}
    	// }
	  	Session::put('paypal_payment_id', $payment->getId());
	  	return Redirect::away($payment->getApprovalLink());

        /** adiciona a id do pagamento para a sessão **/
        // Session::put('paypal_payment_id', $payment->getId());
        // if (isset($redirect_url)) {
        //     * redireciona para o paypal. "away" é um método do laravel que 
        //     redireciona o usuário para um link externo  *
        //     return Redirect::away($redirect_url);
        // }
        // \Session::put('error', 'Unknown error occurred');
        // return Redirect::to('/');

	}

	//função utilizada para retornar para o site do cliente com o status da transação
	public function getPaymentStatus(){
		
		//recupera a id da sessão passada no final do método payWithPaypal
		$payment_id = Session::get('paypal_payment_id');

		Session::forget('paypal_payment_id');

		if(empty(Input::get('PayerID')) || empty(Input::get('token'))){

			\Session::put('error', 'Payment Failed');
			return Redirect::to('/index');

		}

		$payment = Payment::get($payment_id, $this->_api_context);
		$execution = new PaymentExecution();
		$execution->setPayerId(Input::get('PayerID'));

		$result = $payment->execute($execution, $this->_api_context);

		if($result->getState() == 'approved'){
			\Session::put('success', 'Payment success');
			return Redirect::to('/index');
		}

		\Session::put('error', 'Payment Failed');
		return Redirect::to('/index');

	}


	//retorna as informações sobre um pagamento específico
	//utiliza: GET /v1/payments/payments
	public function recuperaPagamento(){

		try{
			//PAY-0MJ5662343912200NLOC7QVA é a id de um pagamento recuperado na função recuperaListaPagamentos()
			$payment = Payment::get('PAY-0MJ5662343912200NLOC7QVA', $this->_api_context);
			dd($payment);
		}
		catch (Exception $ex){
			\Session::put('error', 'Algum erro ocorreu, desculpe pelo inconveniente');
	  		return Redirect::to('/index');
		}

	}


	//recupera a lista de pagamentos do cliente
	//utiliza:  GET /v1/payments/payments
	public function recuperaListaPagamentos(){

		try{
			$params = array('amount' => 25);

    		$payments = Payment::all($params, $this->_api_context);

    		dd($payments);
		}
		catch (Exception $e){
			\Session::put('error', 'Algum erro ocorreu, desculpe pelo inconveniente');
	  		return Redirect::to('/index');
		}
	}

	//recupera as informações da venda. a id da venda podemos pegar em: payment->transactions->related_resources->sale
	//utiliza:  /v1/payments/sale/{sale-id}
	public function recuperaInformacoesVenda(){
		
		$saleId = "8SM83638TL143231A";

		try{
			$sale = Sale::get($saleId, $this->_api_context);
			dd($sale);
		}
		catch(Exception $e){
			\Session::put('error', 'Algum erro ocorreu, desculpe pelo inconveniente');
	  		return Redirect::to('/index');
		}

	}

	//função para executar um reembolso
	//utiliza: /v1/payments/sale/{sale-id}/refund
	public function refund(){

		$amt = new Amount();

		$amt->setCurrency('BRL')
    	    ->setTotal(25.00);

		$refundRequest = new RefundRequest();
		$refundRequest->setAmount($amt);

		$sale = new Sale();
		$sale->setId('8SM83638TL143231A');

		try{
			//$this->_api_context = getApiContext($clientId, $clientSecret);
			$refundedSale = $sale->refundSale($refundRequest, $this->_api_context);

			dd($refundedSale);
		}
		catch(Exception $e){
			\Session::put('error', 'Algum erro ocorreu, desculpe pelo inconveniente');
	  		return Redirect::to('/index');
		}

	}


	############################################
	###           Notificações             #####
	############################################

	//função que retorna todos os tipos de eventos de mudança de status numa transação (Webhooks)
	//utiliza: GET /v1/notifications/webhooks-event-types
	public function listWebHooksEvents(){

		try{
			$output = \PayPal\Api\WebhookEventType::availableEventTypes($this->_api_context);
			dd($output);
		}
		catch(Exception $e){
			\Session::put('error', 'Algum erro ocorreu, desculpe pelo inconveniente');
	  		return Redirect::to('/index');
		}

	}

	//cria um webhook para a aplicação
	//OBS: podemos utilizar mais de um webhook na mesma aplicação
	//OBS: nos testes verifiquei que ao consultar o webhook setado para todos os tipos de eventos
	//o name aparecia representado por "*". Sendo assim podemos criar com o seguinte formato:
	//'{
	//	        "name":"*"
	//}'

	//utiliza: POST /v1/notifications/webhooks
	public function createWebhook(){

		$webhook = new \PayPal\Api\Webhook();
		//URL gerada no slack para recebimento das notificações
		$webhook->setUrl("https://discordapp.com/api/webhooks/484813002655268880/A_KxcIeZ-yswB1I3d0rFK568xpaLHVTZ_FExVPTG7ZOATc82Osgyq5cQK9TA7n-TYRGK" . uniqid());

		//cria um array com os tipos de evento aos quais se deseja receber notificações 
		$webhookEventTypes = array();
		$webhookEventTypes[] = new \PayPal\Api\WebhookEventType(
		    '{
		        "name":"PAYMENT.CAPTURE.REFUNDED"
		    }'
		);
		$webhookEventTypes[] = new \PayPal\Api\WebhookEventType(
		    '{
		        "name":"PAYMENT.PAYOUTS-ITEM.REFUNDED"
		    }'
		);
		$webhookEventTypes[] = new \PayPal\Api\WebhookEventType(
		    '{
		        "name":"PAYMENT.SALE.REFUNDED"
		    }'
		);
		$webhookEventTypes[] = new \PayPal\Api\WebhookEventType(
		    '{
		        "name":"CHECKOUT.ORDER.COMPLETED"
		    }'
		);
		$webhookEventTypes[] = new \PayPal\Api\WebhookEventType(
		    '{
		        "name":"CUSTOMER.PAYOUT.FAILED"
		    }'
		);
		$webhookEventTypes[] = new \PayPal\Api\WebhookEventType(
		    '{
		        "name":"INVOICING.INVOICE.CANCELLED"
		    }'
		);
		$webhookEventTypes[] = new \PayPal\Api\WebhookEventType(
		    '{
		        "name":"INVOICING.INVOICE.CREATED"
		    }'
		);
		
		$webhookEventTypes[] = new \PayPal\Api\WebhookEventType(
		    '{
		        "name":"INVOICING.INVOICE.PAID"
		    }'
		);
		
		$webhookEventTypes[] = new \PayPal\Api\WebhookEventType(
		    '{
		        "name":"INVOICING.INVOICE.REFUNDED"
		    }'
		);
		$webhookEventTypes[] = new \PayPal\Api\WebhookEventType(
		    '{
		        "name":"MERCHANT.ONBOARDING.COMPLETED"
		    }'
		);
		$webhookEventTypes[] = new \PayPal\Api\WebhookEventType(
		    '{
		        "name":"PAYMENT.CAPTURE.COMPLETED"
		    }'
		);
		$webhookEventTypes[] = new \PayPal\Api\WebhookEventType(
		    '{
		        "name":"PAYMENT.CAPTURE.DENIED"
		    }'
		);
		$webhookEventTypes[] = new \PayPal\Api\WebhookEventType(
		    '{
		        "name":"PAYMENT.ORDER.CREATED"
		    }'
		);
		$webhookEventTypes[] = new \PayPal\Api\WebhookEventType(
		    '{
		        "name":"PAYMENT.SALE.COMPLETED"
		    }'
		);
		$webhookEventTypes[] = new \PayPal\Api\WebhookEventType(
		    '{
		        "name":"PAYMENT.SALE.DENIED"
		    }'
		);
		$webhookEventTypes[] = new \PayPal\Api\WebhookEventType(
		    '{
		        "name":"PAYMENT.SALE.PENDING"
		    }'
		);
		$webhookEventTypes[] = new \PayPal\Api\WebhookEventType(
		    '{
		        "name":"VAULT.CREDIT-CARD.CREATED"
		    }'
		);
		
		$webhook->setEventTypes($webhookEventTypes);
		$request = clone $webhook;

		try{

		    $output = $webhook->create($this->_api_context);
		    //id gerada 4GT364992K8296419
		    dd($output);

		} 
		catch (Exception $e) {

			\Session::put('error', 'Algum erro ocorreu, desculpe pelo inconveniente');
	  		return Redirect::to('/index');

		}

	}

	//retorna as informações sobre um webhook criado na função anterior
	//utiliza:  GET /v1/notifications/webhooks/
	public function getWebhook(){

		//OBS: se em algum momento precisarmos recuperar a id do webhook diretamente
		//do objeto, podemos utilizar a função getId(). Ex: $webhookId = $webhook->getId();

		try{
			//id do webhook gerado na função anterior
			$output = \PayPal\Api\Webhook::get("0MT28661HC8147513", $this->_api_context);
			dd($output);
		}
		catch(Exception $e){
			\Session::put('error', 'Algum erro ocorreu, desculpe pelo inconveniente');
	  		return Redirect::to('/index');
		}

	}

	//recupera a lista de todos os webhooks cadastrados para o cliente
	//utiliza: GET /v1/notifications/webhooks
	public function listWebhooks(){

		try{
			$output = \PayPal\Api\Webhook::getAll($this->_api_context);
			dd($output);
		}
		catch(Exception $e){
			\Session::put('error', 'Algum erro ocorreu, desculpe pelo inconveniente');
	  		return Redirect::to('/index');
		}

	}

	//executa mudanças em um webhook gerado para a aplicação
	//OBS: com esse método podemos atualizar tanto a URL quanto os eventos.
	//utiliza: PATCH v1/notifications/webhooks/
	public function updateWebhook(){

		//recupera o webhook que será atualizado
		$webhook = \PayPal\Api\Webhook::get("9P7980678N5935517", $this->_api_context);

		$patch = new \PayPal\Api\Patch();

		$patch->setOp("replace")
    		  ->setPath("/url")
    		  ->setValue("https://hooks.slack.com/services/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". uniqid());

    	$patch2 = new \PayPal\Api\Patch();
    	//Estamos dizendo que o único tipo de evento associado com nosso webhook agora é: "PAYMENT.SALE.REFUNDED".
		$patch2->setOp("replace")
		       ->setPath("/event_types")
		       ->setValue(json_decode('[{"name":"PAYMENT.SALE.REFUNDED"}]'));

       	$patchRequest = new \PayPal\Api\PatchRequest();
		$patchRequest->addPatch($patch)->addPatch($patch2);

		try {
		    $output = $webhook->update($patchRequest, $this->_api_context);
		    dd($output);
		} 
		catch (Exception $ex) {
			\Session::put('error', 'Algum erro ocorreu, desculpe pelo inconveniente');
	  		return Redirect::to('/index');
		}

	}

	//realiza a remoção de um webhook
	//utiliza: DELETE v1/notifications/webhooks/
	public function deleteWebhook(){

		//recupera o webhook pela id
		$webhook = \PayPal\Api\Webhook::get("4GT364992K8296419", $this->_api_context);

		try{
			$output = $webhook->delete($this->_api_context);
			dd($output);
		}
		catch(Exception $e){
			\Session::put('error', 'Algum erro ocorreu, desculpe pelo inconveniente');
	  		return Redirect::to('/index');
		}

	}

	//realiza a remoção de todos os webhooks associados com a aplicação
	public function deleteAllWebhook(){

		$webhookList = \PayPal\Api\Webhook::getAll($this->_api_context);

		try{
			foreach ($webhookList->getWebhooks() as $webhook) {
		        $webhook->delete($this->_api_context);
		    }
		}
		catch(Exception $e){
			\Session::put('error', 'Algum erro ocorreu, desculpe pelo inconveniente');
	  		return Redirect::to('/index');	
		}

	}

	//retorna todos os eventos dos webhooks da aplicação
	//esse pode ser o método que utilizaremos para gravar no banco de dados as mudanças nos status
	//das transações
	//utiliza: GET /v1/notifications/webhooks-events
	public function getWebhookEvents(){

		$params = array();

		try {
		    $output = \PayPal\Api\WebhookEvent::all($params, $this->_api_context);
		    dd($output);
		} 
		catch (Exception $e) {
			\Session::put('error', 'Algum erro ocorreu, desculpe pelo inconveniente');
	  		return Redirect::to('/index');	
		}

	}

	//função que recebe os dados de um evento de mudança de status no paypal
	//não funciona com o webhook simulator. para realizar um teste real é necessário adicionar um
	//webhook na nossa aplicação criada no paypal (item NVP/SOAP API apps).
	//em produção devemos pegar os dados do request atual e passar o id do nosso webhook
	public function eventListener(Request $request){

		//recebimento dos dados da requisição do paypal
		$requestBody = $request->getContent();

		//recebimento dos headers passados pelo paypal
		$headers = getallheaders();

		$headers = array_change_key_case($headers, CASE_UPPER);

		$signatureVerification = new VerifyWebhookSignature();
		$signatureVerification->setAuthAlgo($headers['PAYPAL-AUTH-ALGO']);
		$signatureVerification->setTransmissionId($headers['PAYPAL-TRANSMISSION-ID']);
		$signatureVerification->setCertUrl($headers['PAYPAL-CERT-URL']);
		$signatureVerification->setWebhookId("5YN49624JT7643018");//webhookId		
		$signatureVerification->setTransmissionSig($headers['PAYPAL-TRANSMISSION-SIG']);
		$signatureVerification->setTransmissionTime($headers['PAYPAL-TRANSMISSION-TIME']);

		$signatureVerification->setRequestBody($requestBody);
		$request = clone $signatureVerification;

		try{

			$output = $signatureVerification->post($this->_api_context);
			//$req_dump = print_r( $output, true );
			//cria um arquivo com a resposta da ação
			//o arquivo fica em: /public
			$fp = file_put_contents( 'request.log', $output );
		}
		catch(Exception $e){
			\Session::put('error', 'Algum erro ocorreu, desculpe pelo inconveniente');
	  		return Redirect::to('/index');	
		}
	}

	##################################################
	###           teste com webhooks             #####
	##################################################


	public function testDiscordWebhook(){
		
		$msg = array("content" => "teste", "username" => "Webhook");

		$curl = curl_init("https://discordapp.com/api/webhooks/484813002655268880/A_KxcIeZ-yswB1I3d0rFK568xpaLHVTZ_FExVPTG7ZOATc82Osgyq5cQK9TA7n-TYRGK");
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($msg));

		$retorno = curl_exec($curl);
		//($retorno);

		curl_close($curl);

		dd($retorno);

	}

	public function testSlackWebhook(){

		$msg = array('text' => 'teste 123');

		$curl = curl_init("https://hooks.slack.com/services/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");
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_POSTFIELDS, array('payload' => json_encode($msg)));

		$retorno = curl_exec($curl);
		//($retorno);

		curl_close($curl);

		dd($retorno);

	}

	public function index2(){
		return view('index2');
	}

}
