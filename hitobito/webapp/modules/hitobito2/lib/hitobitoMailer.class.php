<?php
/**
 * hitobito専用メール送信クラス
 * 事務局画面より設定したメールテンプレートを使用してメールを送信する
 * 
 * @author Ryuji
 * @version $Id: hitobitoMailer.class.php,v 1.9 2009/04/23 02:32:03 aizawa Exp $
 */

define('HITOBITO_MAILER_DEBUG', FALSE);
class hitobitoMailer 
{
	protected $attributes = array();
	private $bcc_list = array();
	
	public function loadTemplate($template_id)
	{
		$handler = HNb::getDBHandler('m_mail_template');
		$template = $handler->get($template_id);
		$this->subject = $template->getAttribute('mtm_subject');
		$this->from = $template->getAttribute('mtm_from');
		$this->cc = $template->getAttribute('mtm_cc');
		$this->bcc = $template->getAttribute('mtm_bcc');
		$this->reply_to = $template->getAttribute('mtm_reply_to');
		$this->body_template = $template->getAttribute('mtm_body');
	}

	public function fetchBody()
	{
		$body = $this->body_template;
		foreach($this->attributes as $key => $var){
			$body = preg_replace('/%'.$key.'%/', $var, $body);
		}
		$body = mb_convert_encoding($body, "ISO-2022-JP", 'EUC-JP');
		return $body;
	}
	
	public function setAttribute($key, $var)
	{
		$this->attributes[$key] = $var;
	}
	
	protected function getHeader()
	{
		$headerList = array();
		if(strlen($this->from) > 0){
			$headerList[] = 'From: '.$this->encodeHeaderMailAddress($this->from);
		}
		if(strlen($this->cc)>0){
			$headerList[] = 'Cc: '.$this->encodeHeaderMailAddress($this->cc);
		}

		$bccHeader = $this->getBccHeader();
		if ($bccHeader != '')
			$headerList[] = $bccHeader;
		
		if(strlen($this->reply_to)> 0){
			$headerList[] = 'Reply-To: '.$this->encodeHeaderMailAddress($this->reply_to);
		}
		$headerList[] = "MIME-Version: 1.0";
		$headerList[] = "Content-Type: text/plain; charset=iso-2022-jp";
		$headerList[] = "Content-Transfer-Encoding: 7bit";

		if(count($headerList) > 0){
			return implode("\r\n", $headerList);
		}else{
			return '';
		}
	}
	
	private function getBccHeader()
	{
		$bcc_header = '';
		if(strlen($this->bcc)>0){
			$bcc_header = $this->encodeHeaderMailAddress($this->bcc);
		}
		if(count($this->bcc_list) > 0){
			if(!empty($bcc_header)){
				$bcc_header .=',';
			}
			$bcc_header .= implode(',', $this->bcc_list);
		}
		if(!empty($bcc_header)){
			$bcc_header = 'Bcc: ' . $bcc_header;
		}
		return $bcc_header;
	}
	
	protected function encodeHeaderMailAddress($str)
 	{
	 	//カンマ分割
	 	$mailList = explode(',', $str);
	 	if(count($mailList) == 1){
		 	// ; 分割
		 	$mailList = explode(';', $str);
	 	}
	 	// 名前をエンコード
	 	$encodeMailList = array();
	 	foreach($mailList as $mailset){
	 		$split_mailset = explode('<', $mailset);
	 		if(count($split_mailset) == 2){
                $name = $split_mailset[0];
                $mailpart = $split_mailset[1];	 		    
		 		$encodeMailList[] = mb_encode_mimeheader(trim($name)) . ' <' . $mailpart;
	 		}else{
	 			$encodeMailList[] = $mailset;
	 		}
	 	}
	 	return implode(',', $encodeMailList);
 	}
 	
	public function send($to)
	{
		$header = $this->getHeader();
		$body = $this->fetchBody();
		$subject = $this->fetchSubject();
		$result = mail($to, $subject, $body, $header);
		if(HITOBITO_MAILER_DEBUG){ //
			$this->WriteMailLog($to, $subject, $body, $header);
		}
		return $result;
	}
	
	/**
	 * メール送信内容の確認用に、webapp/mail.logに書き出す
	 */
	private function WriteMailLog($to, $subject, $body, $header)
	{
		$fp = fopen(MO_WEBAPP_DIR.'/mail.log', 'a');
		$line = sprintf("■%s, %s\n%s\n----\n%s\n----\n%s\n", $to, $subject, $header, $body, date("Y-m-d H:i:s"));
		fwrite($fp, $line);
		fclose($fp);
	}
	private function fetchSubject()
	{
		$subject = $this->subject;
		foreach($this->attributes as $key => $var){
			$subject = preg_replace('/%'.$key.'%/', $var, $subject);
		}
		$subject = mb_encode_mimeheader($subject);
		return $subject;
	}
	// TODO
	public function addBcc($bcc_mail)
	{
		$this->bcc_list[] = $bcc_mail; 
	}
}
?>