<?php
class authorize{
	var $auth_net_login_id = '';
	var $auth_net_tran_key = '';
	var $g_apihost = "apitest.authorize.net";
	var $g_apipath = "/xml/v1/request.api";
	var $validationMode = 'testMode';
	var $customerProfileId = '';
	var $customerPaymentProfileId = '';
	var $customerAddressId = '';
	var $merchantCustomerId = '';
	var $profile_mail = '';
	var $error = '';
	var $resultCode = '';
	
	var $billTo_firstName = '';
	var $billTo_lastName = '';
	var $billTo_company = '';
	var $billTo_address = '';
	var $billTo_city = '';
	var $billTo_state = '';
	var $billTo_zip = '';
	var $billTo_country = '';
	var $billTo_phoneNumber = '';
	
	var $cardNumber = '';
	var $creditCardNumberMasked = '';
	var $expirationDate = '';
	var $cardCode = '';
	
	var $accountType = '';
	var $routingNumber = '';
	var $accountNumber = '';
	var $nameOnAccount = '';
	var $echeckType = 'PPD';
	var $bankName = '';
	var $bankRoutingNumberMasked = '';
	var $bankAccountNumberMasked = '';
	
	var $shipToList_firstName = '';
	var $shipToList_lastName = '';
	var $shipToList_address = '';
	var $shipToList_city = '';
	var $shipToList_state = '';
	var $shipToList_zip = '';
	var $shipToList_country = '';
	var $shipToList_phoneNumber = '';
	
	var $transId = '';
	var $responseCode = 3;
	var $responseReasonText = '';
	var $parsedresponse;
	var $payment_type = 'creditCard';
	var $code_error = '';
	
	function __construct($auth_net_login_id, $auth_net_tran_key, $validationMode=NULL, $profile_mail=NULL, $merchantCustomerId=NULL, $customerProfileId=NULL) {
		$this->auth_net_login_id = $auth_net_login_id;
		$this->auth_net_tran_key = $auth_net_tran_key;
		if($validationMode !== NULL){
			$this->validationMode = $validationMode;
			if($this->validationMode == 'liveMode'){
				$this->g_apihost = 'api.authorize.net';	
			}		
		}
		if($profile_mail !== NULL){
			$this->profile_mail = trim($profile_mail);		
		}
		if($merchantCustomerId !== NULL){
			$this->merchantCustomerId = trim($merchantCustomerId);		
		}
		if($customerProfileId !== NULL){
			$this->customerProfileId = trim($customerProfileId);		
		}
    }
	function set_ProfileInfo($billInfo=array()){
		if(is_array($billInfo) && count($billInfo) > 0){
			if(isset($billInfo['billTo_firstName'])) $this->billTo_firstName = $billInfo['billTo_firstName'];
			if(isset($billInfo['billTo_lastName'])) $this->billTo_lastName = $billInfo['billTo_lastName'];
			if(isset($billInfo['billTo_company'])) $this->billTo_company = $billInfo['billTo_company'];
			if(isset($billInfo['billTo_address'])) $this->billTo_address = $billInfo['billTo_address'];
			if(isset($billInfo['billTo_city'])) $this->billTo_city = $billInfo['billTo_city'];
			if(isset($billInfo['billTo_state'])) $this->billTo_state = $billInfo['billTo_state'];
			if(isset($billInfo['billTo_zip'])) $this->billTo_zip = $billInfo['billTo_zip'];
			if(isset($billInfo['billTo_country'])) $this->billTo_country = $billInfo['billTo_country'];
			if(isset($billInfo['billTo_phoneNumber'])) $this->billTo_phoneNumber = $billInfo['billTo_phoneNumber'];	
			
			if(isset($billInfo['cardNumber'])) $this->cardNumber = trim($billInfo['cardNumber']);
			if(isset($billInfo['expirationDate'])) $this->expirationDate = $billInfo['expirationDate'];
			if(isset($billInfo['cardCode'])) $this->cardCode = $billInfo['cardCode'];
			
			if(isset($billInfo['shipToList_firstName'])) $this->shipToList_firstName = $billInfo['shipToList_firstName'];
			if(isset($billInfo['shipToList_lastName'])) $this->shipToList_lastName = $billInfo['shipToList_lastName'];
			if(isset($billInfo['shipToList_address'])) $this->shipToList_address = $billInfo['shipToList_address'];
			if(isset($billInfo['shipToList_city'])) $this->shipToList_city = $billInfo['shipToList_city'];
			if(isset($billInfo['shipToList_state'])) $this->shipToList_state = $billInfo['shipToList_state'];
			if(isset($billInfo['shipToList_zip'])) $this->shipToList_zip = $billInfo['shipToList_zip'];
			if(isset($billInfo['shipToList_country'])) $this->shipToList_country = $billInfo['shipToList_country'];
			if(isset($billInfo['shipToList_phoneNumber'])) $this->shipToList_phoneNumber = $billInfo['shipToList_phoneNumber'];							
		}	
	}
	
	function createTransaction($profileTransAuthCapture){
		$authnet_values				= array(
			"x_login"				=> $this->auth_net_login_id,
			"x_version"				=> "3.1",
			"x_duplicate_window"	=> 5, // 5 sec
			"x_delim_char"			=> "|",
			"x_delim_data"			=> "TRUE",
			"x_type"				=> "AUTH_CAPTURE",
			"x_method"				=> "CC",
			"x_tran_key"			=> $this->auth_net_tran_key,
			"x_relay_response"		=> "FALSE",
			"x_card_num"			=> $profileTransAuthCapture['card_num'],
			"x_exp_date"			=> $profileTransAuthCapture['exp_date'],
			"x_description"			=> 'Online orders.',
			"x_amount"				=> $profileTransAuthCapture['amount'],
			"x_invoice_num"			=> $profileTransAuthCapture['invoiceNumber'],
	
			"x_cust_id"				=> '',
			"x_first_name"			=> $profileTransAuthCapture['first_name'],
			"x_last_name"			=> $profileTransAuthCapture['last_name'],
			"x_address"				=> $profileTransAuthCapture['address'],
			"x_city"				=> $profileTransAuthCapture['city'],
			"x_state"				=> $profileTransAuthCapture['state'],
			"x_zip"					=> $profileTransAuthCapture['zip'],
			"x_country"				=> $profileTransAuthCapture['country'],
			
			"x_phone"				=> $profileTransAuthCapture['phone'],
			"x_email"				=> $profileTransAuthCapture['email']
		);
		$fields = "";
		foreach( $authnet_values as $key => $value ) $fields .= "$key=" . urlencode( $value ) . "&";
		
		$auth_net_url = 'https://test.authorize.net/gateway/transact.dll';
		if($this->validationMode == 'liveMode'){
			$auth_net_url = 'https://secure.authorize.net/gateway/transact.dll';
		}
	
		$ch = curl_init($auth_net_url); 

		curl_setopt($ch, CURLOPT_HEADER, 0); // set to 0 to eliminate header info from response
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // Returns response data instead of TRUE(1)
		curl_setopt($ch, CURLOPT_POSTFIELDS, rtrim( $fields, "& " )); // use HTTP POST to send form data
		### curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // uncomment this line if you get no gateway response. ###
		$resp = curl_exec($ch); //execute post and get results
		curl_close ($ch);
		if($resp){
			$arr_mes = explode("|",$resp);
			if(is_array($arr_mes) && count($arr_mes) > 0){
				if($arr_mes[0] == '1'){//Approved
					if(isset($arr_mes[6]) && $arr_mes[6] != '') $this->transId = $arr_mes[6];
				}elseif(isset($arr_mes[3])){
					$this->error = $arr_mes[3];	
				}	
			}
		}
	}
	
	function deleteCustomerProfileRequest($customerProfileId){
		if($customerProfileId == ''){
			$this->error = "customerProfileId is not null.";
			return false;
		}
		$content = "<?xml version=\"1.0\" encoding=\"utf-8\"?>" .
		"<deleteCustomerProfileRequest xmlns=\"AnetApi/xml/v1/schema/AnetApiSchema.xsd\">" .
		  $this->MerchantAuthenticationBlock().
		  "<customerProfileId>".$customerProfileId."</customerProfileId>".
		"</deleteCustomerProfileRequest>";
		$response = $this->send_request_via_fsockopen($content);
		$parsedresponse = $this->parse_api_response($response);
		$this->resultCode = $parsedresponse->messages->resultCode;
		if ("Ok" == $this->resultCode) {
			return true;
		}else{
			foreach ($parsedresponse->messages->message as $msg) {
				$this->error .= htmlspecialchars($msg->text)."\n";	
			}
		}
		return false;		
	}
	
	//------------ Create Transaction
	function profileTransAuthCapture($profileTransAuthCapture){
		if($this->customerProfileId == ''){
			$this->error = 'customerProfileId is NULL';	
			return false;
		}
		if($this->customerPaymentProfileId == ''){
			$this->error = 'customerPaymentProfileId is NULL';	
			return false;
		}
		
		$content = "<?xml version=\"1.0\" encoding=\"utf-8\"?>" .
		"<createCustomerProfileTransactionRequest xmlns=\"AnetApi/xml/v1/schema/AnetApiSchema.xsd\">" .
		$this->MerchantAuthenticationBlock().
		"<transaction>".
		"<profileTransAuthCapture>".
		"<amount>" . $profileTransAuthCapture['amount'] . "</amount>". // should include tax, shipping, and everything.
		"<customerProfileId>" . $this->customerProfileId . "</customerProfileId>".
		"<customerPaymentProfileId>" . $this->customerPaymentProfileId . "</customerPaymentProfileId>";
		if($this->customerAddressId != '')
			$content .= "<customerShippingAddressId>" . $this->customerAddressId . "</customerShippingAddressId>";
		$content .= "<order>".
		"<invoiceNumber>".$profileTransAuthCapture['invoiceNumber']."</invoiceNumber>".
		"</order>".
		"</profileTransAuthCapture>".
		"</transaction>".
		"</createCustomerProfileTransactionRequest>";
		
		$response = $this->send_request_via_fsockopen($content);
		$parsedresponse = $this->parse_api_response($response);
		$this->resultCode = $parsedresponse->messages->resultCode;
		if ("Ok" == $this->resultCode) {
			if (isset($parsedresponse->directResponse)) {
				$directResponseFields = explode(",", $parsedresponse->directResponse);
				$this->responseCode = $directResponseFields[0];
				$this->responseReasonText = $directResponseFields[3];
				$this->transId = $directResponseFields[6];	
			}
		}else{
			foreach ($parsedresponse->messages->message as $msg) {
				$this->error .= htmlspecialchars($msg->text)."\n";	
			}
		}			
	}
	function createCustomerPaymentProfileRequest($customerProfileId=NULL){
		if($customerProfileId !== NULL){
			$this->customerProfileId = trim($customerProfileId);		
		}
		$this->getCustomerProfileRequest();
		$this->GetPaymentAddressProfileId();
		if($this->customerPaymentProfileId == ''){
			$content = "<?xml version=\"1.0\" encoding=\"utf-8\"?>" .
			"<createCustomerPaymentProfileRequest xmlns=\"AnetApi/xml/v1/schema/AnetApiSchema.xsd\">" .
			$this->MerchantAuthenticationBlock().
			"<customerProfileId>" . $this->customerProfileId . "</customerProfileId>".
			"<paymentProfile>".$this->xmlpaymentProfile()."</paymentProfile>".
			"<validationMode>".$this->validationMode."</validationMode>".
			"</createCustomerPaymentProfileRequest>";
			$response = $this->send_request_via_fsockopen($content);
			$parsedresponse = $this->parse_api_response($response);
			$this->resultCode = $parsedresponse->messages->resultCode;
			if ("Ok" == $this->resultCode) {
				$this->customerPaymentProfileId = htmlspecialchars($parsedresponse->customerPaymentProfileId);
			}else{
				foreach ($parsedresponse->messages->message as $msg) {
					$this->error .= htmlspecialchars($msg->text)."\n";	
				}
			}
		}
	}
	function GetPaymentAddressProfileId(){
		$this->resultCode = $this->parsedresponse->messages->resultCode;
		if ("Ok" == $this->resultCode) {
			if(isset($this->parsedresponse->profile->paymentProfiles) && count($this->parsedresponse->profile->paymentProfiles) > 0){
				if($this->payment_type == 'creditCard'){
					foreach ($this->parsedresponse->profile->paymentProfiles as $paymentProfile) {
						if('XXXX'.substr($this->cardNumber,strlen($this->cardNumber)-4,strlen($this->cardNumber)) == $paymentProfile->payment->creditCard->cardNumber && 
							strcasecmp($this->billTo_firstName, $paymentProfile->billTo->firstName) == 0 && 
							strcasecmp($this->billTo_lastName, $paymentProfile->billTo->lastName) == 0 && 
							strcasecmp($this->billTo_address, $paymentProfile->billTo->address) == 0 && 
							strcasecmp($this->billTo_zip, $paymentProfile->billTo->zip) == 0){
							$this->customerPaymentProfileId = $paymentProfile->customerPaymentProfileId;
							break;		
						}
					}		
				}else{
					foreach ($this->parsedresponse->profile->paymentProfiles as $paymentProfile) {
						if('XXXX'.substr($this->accountNumber,strlen($this->accountNumber)-4,strlen($this->accountNumber)) == $paymentProfile->payment->bankAccount->accountNumber && 
							strcasecmp($this->billTo_firstName, $paymentProfile->billTo->firstName) == 0 && 
							strcasecmp($this->billTo_lastName, $paymentProfile->billTo->lastName) == 0 && 
							strcasecmp($this->billTo_address, $paymentProfile->billTo->address) == 0 && 
							strcasecmp($this->billTo_zip, $paymentProfile->billTo->zip) == 0){
							$this->customerPaymentProfileId = $paymentProfile->customerPaymentProfileId;
							break;		
						}
					}	
				}
			}
			if(isset($this->parsedresponse->profile->shipToList) && count($this->parsedresponse->profile->shipToList) > 0){
				foreach ($this->parsedresponse->profile->shipToList as $shipTo) {
					if(strcasecmp($this->shipToList_firstName, $shipTo->firstName) == 0 && 
						strcasecmp($this->shipToList_lastName, $shipTo->lastName) == 0 && 
						strcasecmp($this->shipToList_address, $shipTo->address) == 0 && 
						strcasecmp($this->shipToList_zip, $shipTo->zip) == 0 && 
						strcasecmp($this->shipToList_phoneNumber, $shipTo->phoneNumber) == 0){
						$this->customerAddressId = $shipTo->customerAddressId;
						break;
					}	
				}
			}
		}else{
			$this->createCustomerProfileFullRequest();
		}		
	}
	
	/////////// Get Billing Info
	function getCustomerPaymentProfileRequest($customerProfileId=NULL, $customerPaymentProfileId=NULL){
		if($customerProfileId !== NULL){
			$this->customerProfileId = trim($customerProfileId);		
		}
		if($customerPaymentProfileId !== NULL){
			$this->customerPaymentProfileId = trim($customerPaymentProfileId);		
		}
		$content = "<?xml version=\"1.0\" encoding=\"utf-8\"?>" .
		"<getCustomerPaymentProfileRequest xmlns=\"AnetApi/xml/v1/schema/AnetApiSchema.xsd\">" .
		$this->MerchantAuthenticationBlock().
		"<customerProfileId>" . $this->customerProfileId . "</customerProfileId>".
		"<customerPaymentProfileId>" . $this->customerPaymentProfileId . "</customerPaymentProfileId>".
		"</getCustomerPaymentProfileRequest>";
		$response = $this->send_request_via_fsockopen($content);
		$this->parsedresponse = $this->parse_api_response($response);
		$this->resultCode = $this->parsedresponse->messages->resultCode;
		if ("Ok" == $this->resultCode) {
			$this->billTo_firstName = $this->parsedresponse->profile->paymentProfile->billTo->firstName;
			$this->billTo_lastName = $this->parsedresponse->profile->paymentProfile->billTo->lastName;
			$this->billTo_address = $this->parsedresponse->profile->paymentProfile->billTo->address;
			$this->billTo_city = $this->parsedresponse->profile->paymentProfile->billTo->city;
			$this->billTo_state = $this->parsedresponse->profile->paymentProfile->billTo->state;
			$this->billTo_zip = $this->parsedresponse->profile->paymentProfile->billTo->zip;
			$this->billTo_country = $this->parsedresponse->profile->paymentProfile->billTo->country;
			$this->billTo_phoneNumber = $this->parsedresponse->profile->paymentProfile->billTo->phoneNumber;
			
			if(isset($this->parsedresponse->profile->paymentProfile->payment->creditCard->cardNumber)) $this->cardNumber = $this->parsedresponse->profile->paymentProfile->payment->creditCard->cardNumber;
			if(isset($this->parsedresponse->profile->paymentProfile->payment->bankAccount->accountNumber)) $this->accountNumber = $this->parsedresponse->profile->paymentProfile->payment->bankAccount->accountNumber;
			if(isset($this->parsedresponse->profile->paymentProfile->payment->bankAccount->routingNumber)) $this->routingNumber = $this->parsedresponse->profile->paymentProfile->payment->bankAccount->routingNumber;
			if(isset($this->parsedresponse->profile->paymentProfile->payment->bankAccount->accountType)) $this->accountType = $this->parsedresponse->profile->paymentProfile->payment->bankAccount->accountType;
			if(isset($this->parsedresponse->profile->paymentProfile->payment->bankAccount->nameOnAccount)) $this->nameOnAccount = $this->parsedresponse->profile->paymentProfile->payment->bankAccount->nameOnAccount;
			if(isset($this->parsedresponse->profile->paymentProfile->payment->bankAccount->echeckType)) $this->echeckType = $this->parsedresponse->profile->paymentProfile->payment->bankAccount->echeckType;
		}else{
			foreach ($parsedresponse->messages->message as $msg) {
				$this->error .= htmlspecialchars($msg->text)."\n";	
			}
		}
	}
	
	//////////////  Get Order Details
	function getOrderDetails($customerProfileId=NULL, $customerPaymentProfileId=NULL, $customerAddressId=NULL){
		if($customerProfileId !== NULL){
			$this->customerProfileId = trim($customerProfileId);		
		}
		if($customerPaymentProfileId !== NULL){
			$this->customerPaymentProfileId = trim($customerPaymentProfileId);		
		}	
		if($customerAddressId !== NULL){
			$this->customerAddressId = trim($customerAddressId);		
		}
		$this->getCustomerProfileRequest();
		$this->resultCode = $this->parsedresponse->messages->resultCode;
		if ("Ok" == $this->resultCode) {
			if(isset($this->parsedresponse->profile->paymentProfiles) && count($this->parsedresponse->profile->paymentProfiles) > 0){
				foreach ($this->parsedresponse->profile->paymentProfiles as $paymentProfile) {
					if($paymentProfile->customerPaymentProfileId == $this->customerPaymentProfileId){
						$this->billTo_firstName = $paymentProfile->billTo->firstName;
						$this->billTo_lastName = $paymentProfile->billTo->lastName;
						$this->billTo_address = $paymentProfile->billTo->address;
						$this->billTo_city = $paymentProfile->billTo->city;
						$this->billTo_state = $paymentProfile->billTo->state;
						$this->billTo_zip = $paymentProfile->billTo->zip;
						$this->billTo_country = $paymentProfile->billTo->country;
						$this->billTo_phoneNumber = $paymentProfile->billTo->phoneNumber;
						
						$this->cardNumber = isset($paymentProfile->payment->creditCard->cardNumber) ? $paymentProfile->payment->creditCard->cardNumber : '';
						$this->accountNumber = isset($paymentProfile->payment->bankAccount->accountNumber) ? $paymentProfile->payment->bankAccount->accountNumber : '';
						$this->routingNumber = isset($paymentProfile->payment->bankAccount->routingNumber) ? $paymentProfile->payment->bankAccount->routingNumber : '';
						$this->accountType = isset($paymentProfile->payment->bankAccount->accountType) ? $paymentProfile->payment->bankAccount->accountType : '';
						$this->nameOnAccount = isset($paymentProfile->payment->bankAccount->nameOnAccount) ? $paymentProfile->payment->bankAccount->nameOnAccount : '';
						$this->echeckType = isset($paymentProfile->payment->bankAccount->echeckType) ? $paymentProfile->payment->bankAccount->echeckType : '';
						break;	
					}
				}
			}
			if(isset($this->parsedresponse->profile->shipToList) && count($this->parsedresponse->profile->shipToList) > 0){
				foreach ($this->parsedresponse->profile->shipToList as $shipTo) {
					if($shipTo->customerAddressId == $this->customerAddressId){
						$this->shipToList_firstName = $shipTo->firstName;
						$this->shipToList_lastName = $shipTo->lastName;
						$this->shipToList_address = $shipTo->address;
						$this->shipToList_city = $shipTo->city;
						$this->shipToList_state = $shipTo->state;
						$this->shipToList_zip = $shipTo->zip;
						$this->shipToList_country = $shipTo->country;
						$this->shipToList_phoneNumber = $shipTo->phoneNumber;
						break;	
					}
				}
			}
		}else{
			foreach ($this->parsedresponse->messages->message as $msg) {
				$this->error .= htmlspecialchars($msg->text)."\n";	
			}
		}		
	}
	
	////////// Get Customer Profile
	function getCustomerProfileRequest(){
		$content = "<?xml version=\"1.0\" encoding=\"utf-8\"?>" .
		"<getCustomerProfileRequest xmlns=\"AnetApi/xml/v1/schema/AnetApiSchema.xsd\">" .
		$this->MerchantAuthenticationBlock().
		"<customerProfileId>" . $this->customerProfileId . "</customerProfileId>".
		"</getCustomerProfileRequest>";
		$response = $this->send_request_via_fsockopen($content);
		$this->parsedresponse = $this->parse_api_response($response);
	}
	function createCustomerProfileFullRequest($profile_mail=NULL, $merchantCustomerId=NULL){
		if($profile_mail !== NULL){
			$this->profile_mail = trim($profile_mail);		
		}
		if($merchantCustomerId !== NULL){
			$this->merchantCustomerId = trim($merchantCustomerId);		
		}
		$content = "<?xml version=\"1.0\" encoding=\"utf-8\"?>" .
		"<createCustomerProfileRequest xmlns=\"AnetApi/xml/v1/schema/AnetApiSchema.xsd\">".$this->MerchantAuthenticationBlock().
		"<profile>".
		$this->xmlMerchantCustomerId().
		$this->xmlEmail().
		"<paymentProfiles>".$this->xmlpaymentProfile()."</paymentProfiles>".
		$this->xmlshipToList().
		"</profile>".
		"</createCustomerProfileRequest>";
		$response = $this->send_request_via_fsockopen($content);
		$parsedresponse = $this->parse_api_response($response);
		$this->resultCode = $parsedresponse->messages->resultCode;
		if ("Ok" == $this->resultCode) {
			if(isset($parsedresponse->customerProfileId)) $this->customerProfileId = htmlspecialchars($parsedresponse->customerProfileId);
			if(isset($parsedresponse->customerPaymentProfileIdList->numericString)) $this->customerPaymentProfileId = htmlspecialchars($parsedresponse->customerPaymentProfileIdList->numericString);
			if(isset($parsedresponse->customerShippingAddressIdList->numericString)) $this->customerAddressId = htmlspecialchars($parsedresponse->customerShippingAddressIdList->numericString);
		}else{
			$code = $parsedresponse->messages->message[0]->code;
			if($code == 'E00039'){
				$text = $parsedresponse->messages->message[0]->text;
				$text = str_replace("A duplicate record with ID", "", $text);
				$text = trim(str_replace("already exists.", "", $text));
				if($text != '' && is_numeric($text)){
					$this->customerProfileId = trim($text);
					$this->createCustomerPaymentProfileRequest();
				}else {
					foreach ($parsedresponse->messages->message as $msg) {
						$this->error .= htmlspecialchars($msg->text)."\n";	
					}		
				}
			}else{
				foreach ($parsedresponse->messages->message as $msg) {
					$this->error .= htmlspecialchars($msg->text)."\n";	
				}
			}
		}
	}
	function createCustomerProfileRequest($profile_mail=NULL, $merchantCustomerId=NULL){
		if($profile_mail !== NULL){
			$this->profile_mail = trim($profile_mail);		
		}
		if($merchantCustomerId !== NULL){
			$this->merchantCustomerId = trim($merchantCustomerId);		
		}
		$content = "<?xml version=\"1.0\" encoding=\"utf-8\"?>" .
		"<createCustomerProfileRequest xmlns=\"AnetApi/xml/v1/schema/AnetApiSchema.xsd\">".$this->MerchantAuthenticationBlock().
		"<profile>".$this->xmlMerchantCustomerId()."<description></description>".$this->xmlEmail()."</profile>".
		"</createCustomerProfileRequest>";
		$response = $this->send_request_via_fsockopen($content);
		$parsedresponse = $this->parse_api_response($response);
		$this->resultCode = $parsedresponse->messages->resultCode;
		if ("Ok" == $this->resultCode) {
			$this->customerProfileId = htmlspecialchars($parsedresponse->customerProfileId);
		}else{
			$code = $parsedresponse->messages->message[0]->code;
			if($code == 'E00039'){
				$text = $parsedresponse->messages->message[0]->text;
				$text = str_replace("A duplicate record with ID", "", $text);
				$text = trim(str_replace("already exists.", "", $text));
				if($text != '' && is_numeric($text))
					$this->customerProfileId = trim($text);
				else {
					foreach ($parsedresponse->messages->message as $msg) {
						$this->error .= htmlspecialchars($msg->text)."\n";	
					}	
				}
			}else{
				foreach ($parsedresponse->messages->message as $msg) {
					$this->error .= htmlspecialchars($msg->text)."\n";	
				}
			}
		}
	}
	
	//-------------------- Refund
	function createCustomerProfileTransRefund($transId, $amount){
		if($transId !== NULL){
			$this->transId = trim($transId);		
		}
		if(!is_numeric($amount) || $amount <= 0){
			$this->error = 'Amount is not zero.';
			return false;
		}
		$content = "<?xml version=\"1.0\" encoding=\"utf-8\"?>" .
		"<createCustomerProfileTransactionRequest xmlns=\"AnetApi/xml/v1/schema/AnetApiSchema.xsd\">".$this->MerchantAuthenticationBlock().
		  "<transaction>".
			"<profileTransRefund>".
			  "<amount>".$amount."</amount>".
			  "<customerProfileId>".$this->customerProfileId."</customerProfileId>".
			  "<customerPaymentProfileId>".$this->customerPaymentProfileId."</customerPaymentProfileId>";
			  
			  if($this->customerAddressId != '') $content .= "<customerShippingAddressId>".$this->customerAddressId."</customerShippingAddressId>";
			  if($this->creditCardNumberMasked != '') $content .= "<creditCardNumberMasked>XXXX".$this->creditCardNumberMasked."</creditCardNumberMasked>";
			  if($this->bankAccountNumberMasked != '') $content .= "<bankAccountNumberMasked>XXXX".$this->bankAccountNumberMasked."</bankAccountNumberMasked>";
			  if($this->bankRoutingNumberMasked != '') $content .= "<bankRoutingNumberMasked>XXXX".$this->bankRoutingNumberMasked."</bankRoutingNumberMasked>";
			    
		$content .= "<transId>".$this->transId."</transId>".
			"</profileTransRefund>".
		  "</transaction>".
		"</createCustomerProfileTransactionRequest>";
		
	//	$this->error = $content;
	//	return false;
		
		$response = $this->send_request_via_fsockopen($content);
		$parsedresponse = $this->parse_api_response($response);
		$this->resultCode = $parsedresponse->messages->resultCode;
		if ("Ok" != $this->resultCode) {
			foreach ($parsedresponse->messages->message as $msg) {
				$this->error .= htmlspecialchars($msg->text)."\n";	
			}
		}
	}
	
	function updateCustomerPaymentProfileRequest($customerProfileId=NULL, $customerPaymentProfileId=NULL){
		if($customerProfileId !== NULL){
			$this->customerProfileId = trim($customerProfileId);		
		}
		if($customerPaymentProfileId !== NULL){
			$this->customerPaymentProfileId = trim($customerPaymentProfileId);		
		}
		$content = "<?xml version=\"1.0\" encoding=\"utf-8\"?>" .
		"<updateCustomerPaymentProfileRequest xmlns=\"AnetApi/xml/v1/schema/AnetApiSchema.xsd\">".$this->MerchantAuthenticationBlock().
			"<customerProfileId>".$this->customerProfileId."</customerProfileId>".
			"<paymentProfile>".$this->xmlpaymentProfile()."<customerPaymentProfileId>".$this->customerPaymentProfileId."</customerPaymentProfileId></paymentProfile>".
		"</updateCustomerPaymentProfileRequest>";
		$response = $this->send_request_via_fsockopen($content);
		$parsedresponse = $this->parse_api_response($response);
		$this->resultCode = $parsedresponse->messages->resultCode;
		if ("Ok" != $this->resultCode) {
			foreach ($parsedresponse->messages->message as $msg) {
				$this->error .= htmlspecialchars($msg->text)."\n";	
			}
			if(isset($parsedresponse->messages->message[0]->code)) $this->code_error = $parsedresponse->messages->message[0]->code;
		}
	}
	function updateCustomerProfileRequest($profile_mail=NULL, $merchantCustomerId=NULL, $customerProfileId=NULL){
		if($profile_mail !== NULL){
			$this->profile_mail = trim($profile_mail);		
		}
		if($merchantCustomerId !== NULL){
			$this->merchantCustomerId = trim($merchantCustomerId);		
		}
		if($customerProfileId !== NULL){
			$this->customerProfileId = trim($customerProfileId);		
		}
		$content = "<?xml version=\"1.0\" encoding=\"utf-8\"?>" .
		"<updateCustomerProfileRequest xmlns=\"AnetApi/xml/v1/schema/AnetApiSchema.xsd\">".$this->MerchantAuthenticationBlock().
		"<profile>".$this->xmlMerchantCustomerId()."<description></description>".$this->xmlEmail().$this->xmlCustomerProfileId()."</profile>".
		"</updateCustomerProfileRequest>";
		$response = $this->send_request_via_fsockopen($content);
		$parsedresponse = $this->parse_api_response($response);
		$this->resultCode = $parsedresponse->messages->resultCode;
		return $this->resultCode;
	}
	function get_customerPaymentProfileId(){
		return $this->customerPaymentProfileId;	
	}
	function get_customerProfileId(){
		return $this->customerProfileId;	
	}
	function get_customerAddressId(){
		return $this->customerAddressId;	
	}
	function get_error(){
		return $this->error;	
	}
	function send_request_via_curl($content){
		$posturl = "https://" . $this->g_apihost . $this->g_apipath;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $posturl);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Content-Type: text/xml"));
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		$response = curl_exec($ch);
		return $response;
	}
	function send_request_via_fsockopen($content){
		//return $this->send_request_via_curl($content);
		
		$posturl = "ssl://" . $this->g_apihost;
		$header = "Host: ".$this->g_apihost."\r\n";
		$header .= "User-Agent: PHP Script\r\n";
		$header .= "Content-Type: text/xml\r\n";
		$header .= "Content-Length: ".strlen($content)."\r\n";
		$header .= "Connection: close\r\n\r\n";
		$fp = fsockopen($posturl, 443, $errno, $errstr, 30);
		if (!$fp){
			$body = false;
		}else{
			error_reporting(E_ERROR);
			fputs($fp, "POST ".$this->g_apipath."  HTTP/1.1\r\n");
			fputs($fp, $header.$content);
			fwrite($fp, $out);
			$response = "";
			while (!feof($fp)){
				$response = $response . fgets($fp, 128);
			}
			fclose($fp);
			error_reporting(E_ALL ^ E_NOTICE);
			$len = strlen($response);
			$bodypos = strpos($response, "\r\n\r\n");
			if ($bodypos <= 0){
				$bodypos = strpos($response, "\n\n");
			}
			while ($bodypos < $len && $response[$bodypos] != '<'){
				$bodypos++;
			}
			$body = substr($response, $bodypos);
		}
		return $body;
	}
	function parse_api_response($content){
		$parsedresponse = simplexml_load_string($content, "SimpleXMLElement", LIBXML_NOWARNING);
		return $parsedresponse;
	}
	function xmlMerchantCustomerId(){
		return "<merchantCustomerId>".$this->merchantCustomerId."</merchantCustomerId>";
	}
	function xmlEmail(){
		return "<email>" . $this->profile_mail . "</email>";
	}
	function xmlCustomerProfileId(){
		return "<customerProfileId>" . $this->customerProfileId . "</customerProfileId>";
	}
	function xmlshipToList() {
		if($this->shipToList_firstName == '' || $this->shipToList_state == '') return '';
		return
			"<shipToList>
			<firstName>".$this->shipToList_firstName."</firstName>
			<lastName>".$this->shipToList_lastName."</lastName>
			<address>".$this->shipToList_address."</address>
			<city>".$this->shipToList_city."</city>
			<state>".$this->shipToList_state."</state>
			<zip>".$this->shipToList_zip."</zip>
			<country>".$this->shipToList_country."</country>
			<phoneNumber>".$this->shipToList_phoneNumber."</phoneNumber>
    	</shipToList>";
	}
	function xmlpaymentProfile() {
		$xml_return = '';
		if($this->billTo_firstName != '' && $this->billTo_state != ''){
			$xml_return .= 	"<billTo>".
	 			"<firstName>".$this->billTo_firstName."</firstName>".
	 			"<lastName>".$this->billTo_lastName."</lastName>".
				"<company>".$this->billTo_company."</company>".
				"<address>".$this->billTo_address."</address>".
				"<city>".$this->billTo_city."</city>".
				"<state>".$this->billTo_state."</state>".
				"<zip>".$this->billTo_zip."</zip>".
				"<country>".$this->billTo_country."</country>".
	 			"<phoneNumber>".$this->billTo_phoneNumber."</phoneNumber>".
			"</billTo>";
		}
		if($this->payment_type == 'creditCard'){
			if($this->cardNumber != '' && $this->expirationDate != ''){
				$xml_return .= "<payment>".
					"<creditCard>".
						"<cardNumber>".$this->cardNumber."</cardNumber>".
						"<expirationDate>".$this->expirationDate."</expirationDate>".	// YYYY-MM
					//	"<cardCode>".$this->cardCode."</cardCode>".
					"</creditCard>".
				"</payment>";
			}	
		}else{
			if($this->accountNumber != '' && $this->routingNumber != ''){
				$xml_return .= "<payment>".
					"<bankAccount>".
						"<accountType>".$this->accountType."</accountType>".
						"<routingNumber>".$this->routingNumber."</routingNumber>".
						"<accountNumber>".$this->accountNumber."</accountNumber>".
						"<nameOnAccount>".$this->nameOnAccount."</nameOnAccount>".
						"<echeckType>".$this->echeckType."</echeckType>".
						"<bankName>".$this->bankName."</bankName>".
					"</bankAccount>".
				"</payment>";	
			}		
		}
		return $xml_return;
	}
	function MerchantAuthenticationBlock() {
		return
			"<merchantAuthentication>".
			"<name>" . $this->auth_net_login_id . "</name>".
			"<transactionKey>" . $this->auth_net_tran_key . "</transactionKey>".
			"</merchantAuthentication>";
	}
}
?>