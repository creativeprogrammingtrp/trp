<?php
class upsShip {
	var $buildRequestXML;
	var $xmlSent;
	var $responseXML;
	var $ShipmentDigest;
	var $shipperXML;
	var $shipToXML;
	var $shipFromXML;
	var $shipmentXML;		
	var $packageXML;	
	function upsShip_start($upsObj) {
		// Must pass the UPS object to this class for it to work
		$this->ups = $upsObj;
	}
	function buildRequestXML() {
		$xml = $this->ups->access();
		$xml .= '<?xml version="1.0" encoding="utf-8"?>  
		<ShipmentConfirmRequest>
			<Request>
				<TransactionReference>
					<CustomerContext>Web Artillery UPS Shipping v1.0</CustomerContext>
					<XpciVersion>1.0001</XpciVersion>
				</TransactionReference>
				<RequestAction>ShipConfirm</RequestAction>
				<RequestOption>nonvalidate</RequestOption>
			</Request>';
		$xml .= $this->shipmentXML;
		$xml .= '</ShipmentConfirmRequest>';
		
		$responseXML = $this->ups->request('ShipConfirm', $xml);

		$this->xmlSent = $xml;
		$this->responseXML = $responseXML;
		return $responseXML;
	}
	function buildShipmentAcceptXML($ShipmentDigest) {
		$xml = new xmlBuilder();		
		$xml->push('ShipmentAcceptRequest');
			$xml->push('Request');
				$xml->push('TransactionReference');
					$xml->element('CustomerContext', 'guidlikesubstance');
					$xml->element('XpciVersion', '1.0001');
				$xml->pop(); // end TransactionReference
			$xml->element('RequestAction', 'ShipAccept');
			$xml->pop(); // end Request
		$xml->element('ShipmentDigest', $ShipmentDigest);
		$xml->pop(); // end ShipmentAcceptRequest

		$ShipmentAcceptXML = $this->ups->access();
		$ShipmentAcceptXML .= $xml->getXml();
		
		$responseXML = $this->ups->request('ShipAccept', $ShipmentAcceptXML);
		$this->responseXML = $responseXML;

		return $ShipmentAcceptXML;
	}
	function responseArray() {
		$xmlParser = new upsxmlParser();
		$responseArray = $xmlParser->xmlParser($this->responseXML);
		$responseArray = $xmlParser->getData();
		return $responseArray;	
	}
	function shipper($params) {
		$shipper = '<Shipper>
			<Name>'.$params['name'].'</Name>
			<AttentionName>'.$params['name'].'</AttentionName>
			<PhoneNumber>'.$params['phone'].'</PhoneNumber>
			<ShipperNumber>'.$params['shipperNumber'].'</ShipperNumber>
			<Address>
				<AddressLine1>'.$params['address1'].'</AddressLine1>
				<AddressLine2></AddressLine2>
				<AddressLine3></AddressLine3>
				<City>'.$params['city'].'</City>
				<StateProvinceCode>'.$params['state'].'</StateProvinceCode>
				<PostalCode>'.$params['postalCode'].'</PostalCode>
				<PostcodeExtendedLow></PostcodeExtendedLow>
				<CountryCode>'.$params['country'].'</CountryCode>
			</Address>
		</Shipper>';
		$this->shipperXML = $shipper;
		return $shipper;
	}
	function shipTo($params) {
		$shipTo = '<ShipTo>
				<CompanyName>'.$params['companyName'].'</CompanyName>
				<AttentionName>'.$params['attentionName'].'</AttentionName>
				<PhoneNumber>'.$params['phone'].'</PhoneNumber>
				<Address>
					<AddressLine1>'.$params['address1'].'</AddressLine1>
					<AddressLine2>'.$params['address2'].'</AddressLine2>
					<AddressLine3>'.$params['address3'].'</AddressLine3>
					<City>'.$params['city'].'</City>
					<StateProvinceCode>'.$params['state'].'</StateProvinceCode>
					<PostalCode>'.$params['postalCode'].'</PostalCode>
					<CountryCode>'.$params['countryCode'].'</CountryCode>
				</Address>
			</ShipTo>
		';
		$this->shipToXML = $shipTo;
		return $shipTo;
	}
	function shipFrom($params) {
		$ShipFrom = '<ShipFrom>
			<CompanyName>'.$params['companyName'].'</CompanyName>
			<AttentionName>'.$params['attentionName'].'</AttentionName>
			<PhoneNumber>'.$params['phone'].'</PhoneNumber>
			<Address>
				<AddressLine1>'.$params['address1'].'</AddressLine1>
				<AddressLine2>'.$params['address2'].'</AddressLine2>
				<AddressLine3>'.$params['address3'].'</AddressLine3>
				<City>'.$params['city'].'</City>
				<StateProvinceCode>'.$params['state'].'</StateProvinceCode>
				<PostalCode>'.$params['postalCode'].'</PostalCode>
				<CountryCode>'.$params['countryCode'].'</CountryCode>
			</Address>
		</ShipFrom>';
		$this->shipFromXML = $ShipFrom;
		return $shipTo;
	}
	function package($params) {
		$package .= '<Package>
			<PackagingType>
				<Code>'.$params['code'].'</Code>
			</PackagingType>
			<Dimensions>
				<UnitOfMeasurement>
					<Code>'.$params['UnitOfDimensions'].'</Code>
				</UnitOfMeasurement>
				<Length>'.$params['length'].'</Length>
				<Width>'.$params['width'].'</Width>
				<Height>'.$params['height'].'</Height>
			</Dimensions>
			<PackageWeight>  
				<UnitOfMeasurement>  
				<Code>'.$params['UnitOfPackageWeight'].'</Code>  
				</UnitOfMeasurement>  
				<Weight>'.$params['weight'].'</Weight>  
			</PackageWeight>';
		$package .= '</Package>';
		$this->packageXML .= $package;
		return $package;
	}
	function shipment($params) {
		$shipment = '<Shipment>
			<Description>'.$params['description'].'</Description>
			<Service>
				<Code>'.$params['serviceType'].'</Code>
			</Service>';
		$shipment .= $this->shipperXML. $this->shipToXML. $this->shipFromXML. $this->packageXML;
		$shipment .= '</Shipment>';
		$shipment .= '<LabelSpecification>
			<LabelPrintMethod>
				<Code>GIF</Code>
			</LabelPrintMethod>
			<LabelStockSize>
				<Height>400</Height>
				<Width>300</Width>
			</LabelStockSize>
			<HTTPUserAgent>Mozilla/4.5</HTTPUserAgent>
			<LabelImageFormat>
				<Code>GIF</Code>
			</LabelImageFormat>
		</LabelSpecification>';
		$this->shipmentXML = $shipment;
		return $shipment;
	}
}
?>