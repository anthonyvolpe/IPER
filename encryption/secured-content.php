<?php
include_once ('message-content.php');
include_once ('message-content-signed.php');
include_once ('encrypted-content.php');

if(!class_exists("SecuredContent")){
	date_default_timezone_set('UTC');

	class SecuredContent {
		protected $encrption_key;
		protected $signature_key;

		public function __construct() {
			$this->encrption_key = get_option("encrption_key");
			$this->signature_key = get_option("signature_key");
		}

		public function encode_content($raw_content) 
		{
			$message_content 	   		= new MessageContent();
			$message_content->Body 		= $raw_content;
			$message_content->Timestamp = date('Y-m-d H:i:s', time());
			$message_content->Uid  		= self::guid();

			// create signature & pack message
			$signed_message 		   = new MessageContentSigned();
			$signed_message->Content   = json_encode($message_content);
			$signed_message->Signature = $this->generate_signature($signed_message->Content);

			// create initialization vector & encode data
			$iv_size 	 = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
    		$iv 	 	 = mcrypt_create_iv($iv_size, MCRYPT_RAND);
    		$key 	 	 = base64_decode($this->encrption_key);
    		$data 		 = json_encode($signed_message);
    		$padding 	 = 16 - (strlen($data) % 16);
    		$data 		.= str_repeat(chr($padding), $padding);
    		$cipher_text = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $data, MCRYPT_MODE_CBC, $iv);

    		// store content inside an encrypted container
    		$encrypted_content 	     = new EncryptedContent();
    		$encrypted_content->IV   = base64_encode($iv);
    		$encrypted_content->Data = base64_encode($cipher_text);

			return $encrypted_content;
		}

		public function decode_content($encrypted_string) 
		{
			$encrypted_content = json_decode($encrypted_string);

			// decode data
			$key 	 = base64_decode($this->encrption_key);
			$iv 	 = base64_decode($encrypted_content->IV);
			$message = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, base64_decode($encrypted_content->Data), MCRYPT_MODE_CBC, $iv);
			$padding = ord($message[strlen($message) - 1]);
			$message = json_decode(substr($message, 0, -$padding));
			$signature = $this->generate_signature($message->Content);

			if ($signature != $message->Signature)
				return null;

			return json_decode($message->Content)->Body;
		}

		private function generate_signature($message_content) 
		{
			$private_key = openssl_get_privatekey($this->signature_key);

			openssl_sign($message_content, $signature, $this->signature_key, 'SHA256');

			openssl_free_key($private_key);

			return base64_encode($signature);
		}

		public static function guid() 
		{
		    if (function_exists('com_create_guid') === true)
		    {
		        return trim(com_create_guid(), '{}');
		    }

		    return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
		}
	}
}
?>