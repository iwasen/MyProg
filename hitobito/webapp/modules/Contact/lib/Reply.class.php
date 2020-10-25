<?php

require_once MO_WEBAPP_DIR.'/hitobito/db/t_navi_answer.class.php';
class ContactReply extends t_navi_answerObject
{
	public function __construct()
	{
		parent::__construct();
	}	
}

class ContactReplyManager extends t_navi_answerObjectHandler
{
	protected $class = 'ContactReply';
	public function __construct()
	{
		parent::__construct();
	}
	
	protected function insertObject($obj)
	{
		$last_seq_no = $this->db->GetOne("SELECT nva_seq_no FROM t_navi_answer WHERE nva_inquiry_id=".$obj->getAttribute('nva_inquiry_id').' ORDER BY nva_seq_no DESC');
		$obj->setAttribute('nva_seq_no', $last_seq_no+1);
		$obj->setAttribute('nva_date', time());
		if(parent::insertObject($obj)){
			return $this->db->Execute("UPDATE t_navi_inquiry SET nvi_status=2 WHERE nvi_inquiry_id=".$obj->getAttribute('nva_inquiry_id'));
		}else{
			return FALSE;
		}
		
	}
}

?>