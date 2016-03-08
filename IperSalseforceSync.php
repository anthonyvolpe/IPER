<?php
/**
 * Created by PhpStorm.
 * User: iperdesign
 * Date: 18/02/16
 * Time: 12:28
 */



if(!class_exists("IperSalseforceSync")){
    class IperSalseforceSync{

        //const kSERVICES_URL = "https://lilsand2-connectamerica.cs23.force.com/RestServices/services/apexrest/";
        /*const kSERVICES_URL = get_option(id_api_salesforce);*/
        private $id_api_salesforce;

        const kSERVICES_URL = "https://hypersand3-connectamerica.cs23.force.com/RestServices/services/apexrest/";
        const kAPPEND_URL = "";//"?brand=MedicalAlert";
        const kACTION_ORDER_CREATE ="CreateOrder";
        const kACTION_PROFILE_CREATE ="CreateProfile";


        public $method="POST";
        public $url;
        public $content;
        
        public function __construct($service,$content=array(),$method){

            $this->id_api_salesforce= get_option("id_api_salesforce");

            if($service){
                $this->url=$this->id_api_salesforce.$service.self::kAPPEND_URL;
            }

            if($method){
                $this->method=$method;
            }

            $this->content=$content;
        }


        public function sendRequest(){

            if(!isset($this->url) || !$this->url)
                return ;

            $crypto  = new SecuredContent();
            $request = curl_init($this->url);
            $data    = json_encode($crypto->encode_content(json_encode($this->content)));
            curl_setopt($request, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($request, CURLOPT_RETURNTRANSFER, true);  
            curl_setopt($request, CURLOPT_POSTFIELDS, $data);
            curl_setopt($request, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($data))
            );

            $result = curl_exec($request);
            curl_close($request);

            /*wp_mail("fabrizio.pera@gmail.com","MD ALERT SalesForce","url: ".$this->url."\n\nrequest:\n".$data."\n\nresponse:\n".$result);*/

            $result = json_decode($result);
            $result = json_decode($crypto->decode_content($result));

            return json_encode(json_encode($result));
        }

        public function testData(){
            return "{\"RequestHeader\":{\"RequestId\": \"391d3344-374b-358b-364c-3b67382d3694\"},\"RequestBody\":{\"ShippingInformation\":{\"Name\":\"Name One\", \"Phone\":\"(223) 223-3314\", \"Street2\": null,\"Street1\": \"123 First St\",\"State\": \"NY\",\"PostalCode\": \"14051\",\"Country\": \"United States\",\"City\": \"Buffalo\"}, \"ShippingID\": \"a5q130000004COX\",\"PaymentInformation\": {\"RoutingNumber\": null,\"PaymentType\": \"Credit Card\",\"CVV\": \"223\",\"CardType\": \"Visa\",\"CardNumber\": \"4024007120356927\",\"CardholderName\": \"McGee LastName\",\"AccountType\": null,\"AccountNumber\": null,\"AccountHolderName\": null},\"OrderProducts\":[{\"ProductID\": \"01ta0000004lq71AAA\", \"Quantity\": 1, \"RatePlanID\" : \"a2630000000NaxiAAC\", \"Accessories\":[{\"AccessoryID\": \"a5P13000000GyETEA0\", \"Quantity\": 1}]}],\"MarketingCampaign\": \"Abington Home Care\",\"DiscountCode\": null,\"CustomerPhoneNumber\": \"112-254-5587\",\"CustomerLastName\": \"LastName\",\"CustomerFirstName\": \"McGee\",\"CustomerEmail\": \"McGee@cmail.com\",\"BillingInformation\": {\"Name\":\"Name One\", \"Phone\":\"(223) 223-3314\", \"Street2\": null,\"Street1\": \"123 First St\",\"State\": \"NY\",\"PostalCode\": \"14051\",\"Country\": \"United States\",\"City\": \"Buffalo\"}}}";
        }

        public function testDataProfile(){
            return '{"RequestHeader":{"RequestId":"38143e44-3452-385d-3979-379934793b00"},"RequestBody":{"ServiceTo":{"Name2":"Fabrizio","Email":"fp@iperdesign.com","DateOfBirth2":"1992-01-15","DateOfBirth":"1992-01-15","CrossStreet":"","Address":{"Street2":"","Street1":"","State":"","PostalCode":"","Phone":"","Name":"Fabrizio","Country":"","City":""}},"EmergencyContacts":[{"Relationship":"","Priority":1,"Phone":"","Name":"Andrea"}]},"OpportunityID":""}';
        }
    }
}
?>