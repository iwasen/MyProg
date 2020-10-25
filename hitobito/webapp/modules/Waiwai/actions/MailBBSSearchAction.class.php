<?php
/**
 * [ShortDescription]
 *
 * [Description]
 * * 
 * @package Waiwai
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: MailBBSSearchAction.class.php,v 1.3 2006/06/07 07:32:07 aizawa Exp $
 */
// ������̤��֤���³���ϡֲ����Ϥ�����פȤ���
class MailBBSSearchAction extends Action
{
	private $room_code;
	
    public function execute()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();
        
        $navipage = hitobito::getNaviPageInfo();
        if(!$navipage->getWaiwaiId()){
        	return View::NONE;
        }
        
        $searchWord = htmlspecialchars($request->getParameter('search_word'));
        
        $search = new WaiwaiOldLogSearch();
        $result = $search->getSearchResult($searchWord, $navipage->getWaiwaiId());
        if(empty($result)){
        	return View::NONE;
        }
        $request->setAttribute('result_html', $result);
        
        $logUrl = $search->getLogUrl($navipage->getWaiwaiId());
        $request->setAttribute('logUrl', $logUrl);
        
        return View::SUCCESS;
    }
    
    
    public function isSecure()
    {
        return false;
    }
}

require_once MO_WEBAPP_DIR.'/lib/snoopy/Snoopy.class.php';
class WaiwaiOldLogSearch
{
	private function getSearchUrl($roomId)
    {
    	return $this->getBaseUrl($roomId).'/mbindex.asp';
    }
    
    public function ml_exists($roomId)
    {
    	$url = $this->getSearchUrl($roomId);
    	
		$snoopy = new Snoopy();
		$snoopy->fetch($url);
		$responce_code = $snoopy->response_code;
		if(preg_match('/HTTP\/1\.1[\s]+200[\s]+OK/i', $responce_code)){
			return TRUE;
		}else{
			return FALSE;
		}
    }
    public function getLogUrl($roomId)
    {
    	return $this->getBaseUrl($roomId).'/bbsmain.asp';
    }
    public function getSearchResult($words, $roomId)
    {
    	if(!$this->ml_exists($roomId)){
    		return '';
    	}
    	
    	
		$searchWordList = preg_split('/( |��)/', $words);
    	// SJIS��POST����
		$searchWord = mb_convert_encoding(implode(' ', $searchWordList), 'SJIS', 'EUC-JP');
		$postVars = array(
			'PAGE' => 0 , // ���ڡ����ܤ�ɽ�����뤫��0���Ⱥǿ���5��ˤʤ�褦����
			'INPUTWORD' => $searchWord,
			'submit' => mb_convert_encoding('����', 'SJIS', 'EUC-JP'),
			'SEARCHTARGET' => 0, // �����оݡ�����
			'METHOD' => 1, // AND ����
			'SEARCH' => 'TRUE' , // hidden 
			'SORT' => 1 // ȯ����ɽ����0 �ǥ���å�ɽ��
		);
		
		
		$snoopy = new Snoopy();
		$snoopy->submit($this->getSearchUrl($roomId), $postVars);
		
    	// ��̤�EUC���Ѵ�����
    	$html = mb_convert_encoding($snoopy->results, 'EUC-JP', 'SJIS');

    	// ��̤���ȴ���Ф�<pre>���</pre>
    	preg_match('/<pre>.*<\/pre>/is', $html, $reg);
    	$html = $reg[0];

    	// ��󥯤�񤭴�����
    	$html = preg_replace('/mbcontents/', $this->getBaseUrl($roomId).'/mbcontents', $html);
    	
    	
    	return $html;
    }
    
    private function getBaseUrl($roomId)
    {
    	$roomCode = $this->getRoomCode($roomId);
		return 'http://mb.hitobito.net/MailBBS/hitobito/'.$roomCode;
    }
    private function getRoomCode($roomId)
    {
    	$db = HNb::getAdodb();
    	return $db->GetOne('SELECT rom_room_code FROM t_room WHERE rom_room_id='.$roomId);
    }
	
}
?>