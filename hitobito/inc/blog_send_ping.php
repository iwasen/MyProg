<?php 
require_once "XML/RPC.php";

class blog_send_ping {
	private $message;		// XML_RPC_Message���֥�������
	private $response;	// XML_RPC_Response���֥�������

  // ���󥹥ȥ饯�� 
	public function blog_send_ping($blog_name, $blog_url){

		// ��å������ν���
		$params = array(
		new XML_RPC_Value($blog_name, "string"),
		new XML_RPC_Value($blog_url, "string")
		);

		$this->message = new XML_RPC_Message("weblogUpdates.ping", $params);
	}
   
  // ping���� 
	public function send_ping($ping_url){

		// URL��ѡ���
		$parse_url = parse_url($ping_url);

		// ping����
		$client = new XML_RPC_Client($parse_url["path"], $parse_url["host"], 80);
		$this->response = $client->send($this->message);

		if ($this->response->faultCode() == false) {
			return true;
		} else {
			return false;
		}
	}

	// response���顼
	public function get_response_error(){
		if (is_a($this->response, "XML_RPC_Response") == false)
			return 1;

		if ($this->response->faultCode() != false)
			return 1;
	}
}
?>
