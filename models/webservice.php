<?php

namespace __zf__;

class PayDirect extends Zedek{

	static function orm(){
		$orm =  new ZORM;
		$orm =  $orm::cxn();
		$orm->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		$orm->pdo->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);		
		return $orm;
	}


	private $authorized_ips = array();
	private $authorized = 0;

	/*function __construct(){
		parent::__construct();
		$authorized_ips[] = "172.16.1.1";
	}*/

	function authorizeIP($ip){
		if(empty($ip)){
			return true;
		}
		array_push($this->authorized_ips, $ip);
	}

	function authorizedIPs($ip){
		if(in_array($ip, $this->authorized_ips)){

			return true;
		} else {
			//exit;
			//return false;
			return false;
		}
	}

	function sos($xml){
		$db = self::orm()->pdo;

		
		header("Content-type: text/xml");
		print $response;
		return $response_status;
	}

	function paymentNotification($xml, $ip){
		//$this->authorizeIp("41.223.145.177");
		$this->authorizeIp("41.223.145.174");
		$this->authorizeIp("154.72.34.174");
		
		//$this->authorizeIp("::1");
		$request = simplexml_load_string($xml);
		$finalxml = $xml;
		$response_status = 0;
		$paymentLogID = 0;
		$should_execute = 1;
		$dbo = self::orm()->pdo;
		if($this->authorizedIPs($ip)) {		
			try{
				$dbo->beginTransaction();
				$IsRepeated = 0;
				$PaymentStatus = 0;
				$sql = "";
				@$RouteId = (string)$request->RouteId; //if ($RouteId == "") //$response_status = 1;
				@$ServiceUrl = (string)$request->ServiceUrl; //if ($ServiceUrl == "") //$response_status = 1; 
				@$IsRepeated = (string)$request->Payments->Payment->IsRepeated; //if ($IsRepeated == "") $response_status = 1;
				@$ProductGroupCode = (string)$request->Payments->Payment->ProductGroupCode; //if ($ProductGroupCode == "") $response_status = 1;
				@$PaymentLogId = (string)$request->Payments->Payment->PaymentLogId; //if ($PaymentLogId == "") $response_status = 1;
				@$PaymentReference = (string)$request->Payments->Payment->PaymentReference; //if ($PaymentReference == "") $response_status = 1;
				@$CustReference = (string)$request->Payments->Payment->CustReference; //if ($CustReference == "") $response_status = 1;
				@$Amount = ((float)$request->Payments->Payment->Amount); //if ($Amount == "") //$response_status = 1;
				@$PaymentStatus = ((string)$request->Payments->Payment->PaymentStatus); //if ($PaymentStatus == "") $response_status = 1;
				@$PaymentMethod = (string)$request->Payments->Payment->PaymentMethod; //if ($PaymentMethod == "") $response_status = 1;
				@$ChannelName = (string)$request->Payments->Payment->ChannelName; //if ($ChannelName == "") $response_status = 1;
				@$IsReversal = (string)$request->Payments->Payment->IsReversal; //if ($IsReversal == "") $response_status = 1;
				@$PaymentDate = strftime("%Y-%m-%d %H:%M:%S", strtotime($request->Payments->Payment->PaymentDate)); //if ($PaymentDate == "") $response_status = 1;
				@$SettlementDate = strftime("%Y-%m-%d %H:%M:%S", strtotime($request->Payments->Payment->SettlementDate)); //if ($SettlementDate == "") $response_status = 1;
				@$InstitutionId = (string)$request->Payments->Payment->InstitutionId; //if ($InstitutionId == "") $response_status = 1;
				@$InstitutionName = (string)$request->Payments->Payment->InstitutionName; //if ($InstitutionName == "") $response_status = 1;
				@$CustomerName = (string)$request->Payments->Payment->CustomerName; //if ($CustomerName == "") $response_status = 1;
				@$ReceiptNo = (string)$request->Payments->Payment->ReceiptNo; //if ($ReceiptNo == "") $response_status = 1;
				@$ItemName = (string)$request->Payments->Payment->PaymentItems->PaymentItem->ItemName; //if ($ItemName == "") $response_status = 1;
				@$ItemCode = (string)$request->Payments->Payment->PaymentItems->PaymentItem->ItemCode; //if ($ItemCode == "") $response_status = 1;
				@$ItemAmount = (string)$request->Payments->Payment->PaymentItems->PaymentItem->ItemAmount; //if ($ItemAmount == "") $response_status = 1;
				@$PaymentCurrency = (string)$request->Payments->Payment->PaymentCurrency; //if ($PaymentCurrency == "") $response_status = 1;
				@$OriginalPaymentLogId = (string)$request->Payments->Payment->OriginalPaymentLogId; //if ($OriginalPaymentLogId == "") $OriginalPaymentLogId = NULL;
				@$OriginalPaymentReference = (string)$request->Payments->Payment->OriginalPaymentReference; //if ($OriginalPaymentReference = "") $OriginalPaymentReference = NULL;
				@$BankName = (string)$request->Payments->Payment->BankName; //if ($PaymentCurrency == "") $response_status = 1;
				@$BankCode = (string)$request->Payments->Payment->BankCode; //if ($PaymentCurrency == "") $response_status = 1;
				$IsRepeated = 0;
				@$Status = "0";
				if (@$IsReversal == "False") {
					@$IsReversal = 0;
					@$sql = "INSERT INTO payments (PaymentLogId, CustReference, Amount, PaymentMethod, PaymentReference, ChannelName,
						IsReversal, PaymentDate, SettlementDate, InstitutionId, InstitutionName, CustomerName, ReceiptNo, ItemName, ItemCode,
						ItemAmount, PaymentCurrency, OriginalPaymentLogId, OriginalPaymentReference, BankName) values (?,?,?,?,?,?,?,?,?,?,?,?,?,?,
						?,?,?,?,?,?) on duplicate key update PaymentLogId = PaymentLogId";
				} else {
					@$IsReversal = 1;
					$chk_reversal = $this->check_reversal($OriginalPaymentLogId);
					if ($chk_reversal == 0) $should_execute = 0;
					@$sql = "INSERT INTO payments (PaymentLogId, CustReference, Amount, PaymentMethod, PaymentReference, ChannelName,
						IsReversal, PaymentDate, SettlementDate, InstitutionId, InstitutionName, CustomerName, ReceiptNo, ItemName, ItemCode,
						ItemAmount, PaymentCurrency, OriginalPaymentLogId, OriginalPaymentReference, BankName) values (?,?,?,?,?,?,?,?,?,?,?,?,?,?,
						?,?,?,?,?,?) on duplicate key update PaymentLogId = PaymentLogId";
				}
				@$IsReversal = (int)$IsReversal;
				@$IsRepeated = (int)$IsRepeated;
				@$PaymentStatus = 0;
				$chks = $this->check_if_customer_exists($CustReference);
				if ($chks == "1") {
					$should_execute = 1;
				} else {
					$should_execute = 0;
				}
				$stmt = $dbo->prepare($sql);
				$stmt->bindValue(1, $PaymentLogId);
				$stmt->bindValue(2, $CustReference);
				$stmt->bindValue(3, $Amount);
				$stmt->bindValue(4, $PaymentMethod);
				$stmt->bindValue(5, $PaymentReference);
				$stmt->bindValue(6, $ChannelName);
				$stmt->bindValue(7, $IsReversal);
				$stmt->bindValue(8, $PaymentDate);
				$stmt->bindValue(9, $SettlementDate);
				$stmt->bindValue(10, $InstitutionId);
				$stmt->bindValue(11, $InstitutionName);
				$stmt->bindValue(12, $CustomerName);
				$stmt->bindValue(13, $ReceiptNo);
				$stmt->bindValue(14, $ItemName);
				$stmt->bindValue(15, $ItemCode);
				$stmt->bindValue(16, $ItemAmount);
				$stmt->bindValue(17, $PaymentCurrency);
				$stmt->bindValue(18, $OriginalPaymentLogId);
				$stmt->bindValue(19, $OriginalPaymentReference);
				$stmt->bindValue(20, $BankName);
				if ($should_execute == 1) {
					$stmt->execute();
				} else {
					$response_status = 1;
				}
				$dbo->commit();	

				$response = "<?xml version='1.0' encoding='utf-8' ?>
				<PaymentNotificationResponse>
					<Payments>
						<Payment>
							<PaymentLogId>{$PaymentLogId}</PaymentLogId>
							<Status>{$response_status}</Status>
						</Payment>
					</Payments>
				</PaymentNotificationResponse>";
				//echo $finalxml;
				if ($this->authorized == 0) {
					ob_end_clean();
					ignore_user_abort();
					ob_start();
					header("Content-type: text/xml");
					print $response;
					header("Content-Length: " . ob_get_length());
					ob_end_flush();
					flush();
				}	

			} catch(\Exception $e){
				//echo $e;
				$response_status = 1;
				$dbo->rollback();
			}
		} else {
			$this->authorized = 1;				//sthrow new \Exception("There was a problem");		
		}
	}


	function customerValidation($xml, $ip){
		//$this->authorizeIp("41.223.145.177");
		$this->authorizeIp("41.223.145.174");
		$this->authorizeIp("154.72.34.174");
		
		//$this->authorizeIp("::1");
		$request = simplexml_load_string($xml);
		//$finalxml = $xml;
		$response_status = 0;
		$paymentLogID = 0;
		if($this->authorizedIPs($ip)) {
			$dbo = self::orm()->pdo;
			try{
				$dbo->beginTransaction();
				$IsRepeated = 0;
				$PaymentStatus = 0;
				$sql = "";
				@$RouteId = (string)$request->RouteId; //if ($RouteId == "") //$response_status = 1;
				@$ServiceUrl = (string)$request->ServiceUrl; //if ($ServiceUrl == "") //$response_status = 1; 
				@$MerchantReference = (string)$request->MerchantReference; //if ($IsRepeated == "") $response_status = 1;
				@$CustReference = (string)$request->CustReference; //if ($ProductGroupCode == "") $response_status = 1;
				
				@$sql1 = "SELECT type from tax_payer where tin = ?";
				$stmt1 = $dbo->prepare($sql1);
				$stmt1->bindValue(1, $CustReference);
				$stmt1->execute();

				$res = $stmt1->fetch();
				$customer_type = $res["type"];

				$sql2 = "";

				if ($customer_type == "Individual") {
					$sql2 = "select concat (firstname,' ',lastname) as name from tax_payer where tin = ?";
				} else {
					$sql2 = "select company_name as name from tax_payer where tin = ?";
				}

				$stmt2 = $dbo->prepare($sql2);
				$stmt2->bindValue(1, $CustReference);
				$stmt2->execute();

				$res2 = $stmt2->fetch();
				$name = $res2["name"];
				$name = preg_replace('/[^A-Za-z0-9\- ]/', '', $name); 
				if ($name == "") $response_status = 1;
				$dbo->commit();		
			} catch(\Exception $e){
				$response_status = 1;
				$dbo->rollback();
			}

			$response = "<?xml version='1.0' encoding='utf-8' ?>
			<CustomerInformationResponse>
			<MerchantReference>{$MerchantReference}</MerchantReference>
			<Customers>
			<Customer>
			<Status>{$response_status}</Status>
			<CustReference>{$CustReference}</CustReference>
			<CustomerReferenceAlternate></CustomerReferenceAlternate>
			<FirstName>{$name}</FirstName>
			<LastName></LastName>
			<Email></Email>
			<Phone></Phone>
			<ThirdPartyCode></ThirdPartyCode>
			<Amount>0</Amount>
			</Customer>
			</Customers>
			</CustomerInformationResponse>"; 
			if ($this->authorized == 0) {
				ob_end_clean();
				ignore_user_abort();
				ob_start();
				header("Content-type: text/xml");
				print $response;
				header("Content-Length: " . ob_get_length());
				ob_end_flush();
				flush();
			//$stamps = $this->generate_stamps($PaymentLogId, $Amount);
			//if ($stamps != 0) $this->send_stamps($stamps, $PaymentLogId, $PaymentDate);
			}

		} else {
			$this->authorized = 1;
				//sthrow new \Exception("There was a problem");		
		}
	}

	function check_if_customer_exists($tin){
		$dbo = self::orm()->pdo;
		$query = "select id from tax_payer where tin = :tin";
		try {
			$query = $dbo->prepare($query);
			$query->bindParam(':tin', $tin);
			$query->execute();
			$res = $query->fetch();
			$res = $res["id"];
			if ($res == "") {
				return "0";
			} else {
				return "1";
			}
		} catch (Exception $e) {
			echo "Cannot Login.".$e->getMessage();
			return "0";
		}

	}

	function check_reversal($paymentlogid) {
		$dbo = self::orm()->pdo;
		$query = "select id from payments where PaymentLogId = :paymentlogid";
		try {
			$query = $dbo->prepare($query);
			$query->bindParam(':paymentlogid', $paymentlogid);
			$query->execute();
			$res = $query->fetch();
			$res = $res["id"];
			if ($res == "") {
				return "0";
			} else {
				return "1";
			}
		} catch (Exception $e) {
			echo "Cannot Login.".$e->getMessage();
			return "0";
		}
	}



	function get_real_ip() {
    	if (!empty($_SERVER['HTTP_CLIENT_IP']))  { //check ip from share internet
    		$ip=$_SERVER['HTTP_CLIENT_IP'];
    	}
    	elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))  { //to check ip is pass from proxy
    		$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    	}
    	else {
    		$ip=$_SERVER['REMOTE_ADDR'];
    	}
    	return $ip;
    }





}
?>