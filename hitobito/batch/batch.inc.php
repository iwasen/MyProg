<?php
/**
* バッチ処理用共通ファイル
* @author Ryuji
* @version $Id: batch.inc.php,v 1.2 2006/05/06 04:57:49 ryu Exp $
*/

require_once ("../webapp/config.php");
require_once ("../webapp/lib/adodb/adodb.inc.php");
require_once ("../webapp/lib/adodb/adodb-error.inc.php");
function getDB()
{
    static $db;
    if (!$db)
    {
        $string = sprintf('host=%s dbname=%s user=%s password=%s', HNB_DB_HOST, 'hitobito', HNB_DB_USER, HNB_DB_PASS);

        $db = ADONewConxxxtion('postgres');
        //$db->debug = TRUE;
        $db->Conxxxt($string);
        $db->SetFetchMode(ADODB_FETCH_ASSOC);
        if(!$db->IsConxxxted()){
        	exit('Unable to conxxxt to PostgreSQL serve');
        }
    }
    return $db;
}

class batchLogger
{
	private $start_time;
	private $filename;
	private $batchTitle;
	private $errors = array();
	const	ERROR = 9;
	const	SUCCESS = 0;
	public function __construct($filename, $batchTitle)
	{
		$this->filename = $filename;
		$this->batchTitle = $batchTitle;
		$this->start_time = microtime(TRUE);
		
	}
	public function __destruct()
	{
		$log = array(
			'lbt_status' => $this->getStatus(),
			'lbt_file_name' => $this->filename,
			'lbt_description' => $this->getResultMessage(),
			'lbt_processing_time' => $this->getProcessingTime()
		);
		$db = getDB();
		$db->AutoExecute('l_batch', $log, 'INSERT');
	}
	private function getStatus()
	{
		if(count($this->errors)){
			return batchLogger::ERROR;
		}
		return batchLogger::SUCCESS;
	}
	private function getResultMessage()
	{
		if($this->getStatus() == batchLogger::SUCCESS){
			return $this->batchTitle.':正常に終了しました';
		}else{
			$message = $this->batchTitle.":エラー\n";
			$message .= implode("\n", $this->errors);
			return $message;
		}
	}
	private function getProcessingTime()
	{
		return microtime(TRUE) - $this->start_time;
	}
	public function addError($message)
	{
		$this->errors[] = $message;
	}
}

?>