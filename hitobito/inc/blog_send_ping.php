<?php 
require_once "XML/RPC.php";

class blog_send_ping {
	private $message;		// XML_RPC_Messageオブジェクト
	private $response;	// XML_RPC_Responseオブジェクト

  // コンストラクタ 
	public function blog_send_ping($blog_name, $blog_url){

		// メッセージの準備
		$params = array(
		new XML_RPC_Value($blog_name, "string"),
		new XML_RPC_Value($blog_url, "string")
		);

		$this->message = new XML_RPC_Message("weblogUpdates.ping", $params);
	}
   
  // ping送信 
	public function send_ping($ping_url){

		// URLをパース
		$parse_url = parse_url($ping_url);

		// ping送信
		$client = new XML_RPC_Client($parse_url["path"], $parse_url["host"], 80);
		$this->response = $client->send($this->message);

		if ($this->response->faultCode() == false) {
			return true;
		} else {
			return false;
		}
	}

	// responseエラー
	public function get_response_error(){
		if (is_a($this->response, "XML_RPC_Response") == false)
			return 1;

		if ($this->response->faultCode() != false)
			return 1;
	}
}
?>
