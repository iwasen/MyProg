<?php
// $Id: HNb.class.php,v 1.3 2006/02/22 07:57:27 ryu Exp $
class HNb
{
	var $prefix = 'hitobito';
	
	public function getPrefix()
	{
		return 'hitobito';
	}

    public function getAdodb()
	{
		static $adodb;
		if(isset($adodb)){
			return $adodb;
		}
        $string = sprintf('host=%s dbname=%s user=%s password=%s',
                          HNB_DB_HOST,
                          HNB_DB_NAME,
                          HNB_DB_USER,
                          HNB_DB_PASS);
        $adodb = ADONewConxxxtion('postgres');
        $adodb->debug = ADODB_DEBUG;
//        $adodb->debug = TRUE;
        $adodb->Conxxxt($string);
        $adodb->SetFetchMode(ADODB_FETCH_ASSOC);
        return $adodb;
    }
    
    public function setAdodbDebug($boolian)
    {
    	$adodb = HNb::getAdodb();
    	$adodb->debug = $boolian;
    }

    public function getDatabaseResource()
	{
        $adodb = HNb::getAdodb();
        return $adodb->_conxxxtionID;
    }
	
	
	public function getHandler($class)
	{
		$prefix = HNb::getPrefix();
		$className = $prefix.ucfirst($class);
		require_once(MO_WEBAPP_DIR.'/'.$prefix.'/'.$className.'.class.php');
		$db = Hnb::getAdodb();
		$instance = new $className($db);
		
		return $instance;
	}
	
	public function getDBHandler($tableName)
	{
		$prefix = HNb::getPrefix();
		$filename = $tableName.'.class.php';
		require_once(MO_WEBAPP_DIR.'/'.$prefix.'/db/'.$filename);
		
		$className = $tableName.'ObjectHandler';
		$instance = new $className;
		return $instance;
	}
	
	/**
	 * ñ�ȥ����ƥ�ɽ���Υإ�ѡ�
	 * @param context $context ����ƥ����ȥ��饹
	 * @param string $idname ñ�ȥ����ƥ����ꤹ��ID�ꥯ�����ȥѥ�᡼��̾
	 * @param string $managerClassName �ǡ����������뤿��Υޥ͡����㥯�饹̾
	 */
	public function ViewOne($context, $idname, $managerClassName)
	{
		$request = $context->getRequest();
		$id = intval($request->getParameter($idname, 0));
		$manager = new $managerClassName();
		$obj = $manager->get($id);
		$request->setAttribute('obj', $obj);
		if($obj->getId() == $id){
			return TRUE;
		}else{
			return FALSE;
		}	
	}
	
	/**
	 * ��å���������������ʸ������֤�
	 */
	public function tr($message){
		static $messageCatalog;
		if(!isset($messageCatalog)){
			include MO_WEBAPP_DIR.'/lang/message.inc.php';
			$messageCatalog = $mes;
		}
		if(isset($messageCatalog[$message])){
			return $messageCatalog[$message];
		}else{
			// ̵����Х�å��������������ɲ�
			$messageCatalog[$message] = $message;
			$fp = fopen(MO_WEBAPP_DIR.'/lang/message.inc.php', 'w');
			fwrite($fp, "<?php\n");
			foreach($messageCatalog as $key =>$var){
				fwrite($fp, sprintf('$mes["%s"] = "%s";', $key, $var) . "\n");
			}
			fwrite($fp, "?>");
			fclose($fp);
			return $message;
		}
			
	}
}


?>