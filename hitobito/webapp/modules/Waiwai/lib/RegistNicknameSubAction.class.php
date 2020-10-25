<?php
/**
 * [ShortDescription]
 *
 * [Description]
 * * 
 * @package Waiwai
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: RegistNicknameSubAction.class.php,v 1.5 2006/06/07 07:32:07 aizawa Exp $
 */

require_once MO_WEBAPP_DIR.'/lib/HNbActionForm.class.php';

require_once MO_WEBAPP_DIR.'/modules/Waiwai/forms/registNicknameForm.class.php';

class RegistNicknameSubAction extends HNbEditAction
{
	protected function fetchID()
	{
		return array('rmm_member_id' => 0, 'rmm_room_id' => 0);// 常にnew
	}

	protected function getManager()
	{
		return HNb::getDBHandler('t_room_member');
	}
	
	protected function getActionForm()
	{
		return new WaiwaiRegistNicknameForm();
	}

	protected function setUpNewObject(&$obj)
	{
		// =====================================================================
		// Object Init Code : オブジェクトの状況にあわせた初期化が必要ならここに書く
		// =====================================================================
		$user = $this->getContext()->getUser();
		$obj->setAttribute('rmm_member_id', $user->getAttribute('mbr_member_id'));
		$obj->setAttribute('rmm_room_id', hitobito::getNaviPageInfo()->getWaiwaiId());
		$obj->setAttribute('rmm_status', 2); // guest
		$obj->setAttribute('rmm_member_code', uniqid()); //  ?
	}

	protected function isEdit($obj)
	{
		// =======================================================================
		// Permission Check etc...  編集権限のチェックなどが必要ならここにコードを書く
		// ======================================================================= 
		$handler = HNb::getDBHandler('t_room_member');
		$criteria = new HNbCriteria();
		$criteria->setWhere('rmm_room_id='.hitobito::getNaviPageInfo()->getWaiwaiId() . ' AND rmm_member_id='.$this->user->getAttribute('mbr_member_id'));
		if($handler->getCount($criteria) > 0){
			$this->request->setAttribute('message', HNb::tr('すでにこの会議室に参加しています'));
			return false;
		}

		return true;
	}

	public function isSecure()
	{
		return true;
	}


	protected function insert($obj)
	{
		HNb::getAdodb()->StartTrans();

		if(!parent::insert($obj)){
			return FALSE;
		}

		// メンバー情報更新
		$this->udpateMember();

		$mailer = new WaiwaiGuestRegistMailer(hitobito::getNaviPageInfo());
		$user = $this->getContext()->getUser();
		$result = $mailer->send($user->getAttribute('mbr_mail_addr'));
		
		HNb::getAdodb()->CompleteTrans($result);
		if(!$result){
			$this->request->setAttribute('message', HNb::tr('ニックネームの登録に失敗しました。時間をあけて、再度登録してください。'));
			return FALSE;
		}
		// ナビへの通知
		$mailer->notify2Navi($obj);
		return TRUE;	
	}

	// メンバー情報更新
	private function udpateMember() {
		$user = $this->getContext()->getUser();
		$member_id =  $user->getAttribute('mbr_member_id');

		$data = $this->request->getAttribute('waiwai_regist');
		$birth = $data['birth_y'] ? $data['birth_y'] . '-' . $data['birth_m'] . '-' . $data['birth_d'] : '';
		$zip = $data['zip'][0] ? $data['zip'][0] . $data['zip'][1] : '';
		$tel = $data['tel'][0] ? implode('-', $data['tel']) : '';

    $db = HNb::getAdodb();
    $sql = "UPDATE t_member SET"
			. " mbr_name=" . $this->sql_data($data['name'], 'char')
			. ",mbr_name_kana=" . $this->sql_data($data['name_kana'], 'char')
			. ",mbr_sex_cd=" . $this->sql_data($data['sex_cd'], 'num')
			. ",mbr_birthday=" . $this->sql_data($birth, 'date')
			. ",mbr_zip_cd=" . $this->sql_data($zip, 'char')
			. ",mbr_address1=" . $this->sql_data($data['address1'], 'num')
			. ",mbr_address2=" . $this->sql_data($data['address2'], 'char')
			. ",mbr_tel=" . $this->sql_data($tel, 'char')
			. ",mbr_self_introduction=" . $this->sql_data($data['self_introduction'], 'char')
			. " WHERE mbr_member_id=$member_id";
		$db->Execute($sql);
	}

	// データ成形
	private function sql_data($data, $type) {
		if (trim($data)) {
			switch($type) {
			case 'char':
				$data = addslashes($data);
				return "'$data'";
 			case 'num':
				return "$data";
			case 'date':
				return "'$data'";
			}
		} else {
			return 'null';
		}
	}
}
require_once MO_WEBAPP_DIR.'/modules/hitobito2/lib/hitobitoMailer.class.php';
class WaiwaiGuestRegistMailer extends hitobitoMailer
{
	private $defaultTemplateId = 'room_guest';
	private $navipage;
	private $room;
	public function __construct($navipage)
	{
		$this->navipage = $navipage;
		$this->loadTemplate($this->defaultTemplateId);
		$handler = HNb::getDBHandler('t_room');
		$this->room = $handler->get($navipage->getWaiwaiId());
		$bodyTemplate = trim($this->room->getAttribute('rom_guest_regist_mail'));
		if(strlen($bodyTemplate) > 0){
			$this->body_template = $bodyTemplate;
		}
		$this->setTemplateVars();
	}
	private function setTemplateVars()
	{
		$this->setAttribute('ナビページ名', $this->navipage->getTitle());
		$this->setAttribute('ナビページURL', hitobito::getUrl()
			. 'index.php?module=Navi&action=ShowNaviPage&navipage_id='
			. $this->navipage->getId());
		$this->setAttribute('会議室名', $this->room->getAttribute('rom_name'));
		$this->setAttribute('ナビ名', $this->navipage->getNaviName());
		$this->setAttribute('投稿用アドレス', $this->getSubmitAddress());
		$this->setAttribute('投稿用URL', $this->getSubmitUrl());
	}
	private function getSubmitAddress()
	{
		return $this->room->getAttribute('rom_room_code').'@'.hitobito::getSystemMaster('room_mail_domain');
	}
	private function getSubmitUrl()
	{
		return hitobito::getUrl().'index.php?module=Waiwai&action=PostNew&navipage_id='.$this->navipage->getId();
	}
	public function notify2Navi($obj)
	{
		if($this->room->getAttribute('rom_guest_regist_notify_flag') == 1){
			// 通知する
			$this->loadTemplate('member_regist4');
			$this->setAttribute('ML_NAME', $this->room->getAttribute('rom_name'));
			$this->setAttribute('NAVI_NAME', $this->navipage->getNaviName());
						
			$this->setAttribute('NICKNAME', $obj->getAttribute('rmm_nickname'));
			$this->setAttribute('GUEST_DATE', $obj->getAttribute('rmm_regist_date'));
			$db = HNb::getAdodb();
			$mail = $db->GetOne('SELECT mbr_mail_addr FROM t_member WHERE mbr_member_id='.$obj->getAttribute('rmm_member_id'));
			$this->setAttribute('MAIL_ADDR', $mail);
			
			$naviMail = $db->GetOne("SELECT mbr_mail_addr FROM t_member" .
					" JOIN t_navi ON mbr_member_id=nav_member_id" .
					" WHERE nav_navi_id=".$this->navipage->getAttribute('nvp_navi_id'));
					
						
			$this->send($naviMail);
		}
		
	}	
}

?>