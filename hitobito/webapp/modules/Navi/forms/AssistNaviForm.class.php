<?php
/**
 * @package	Member
 * @author Ryuji
 * @version $Id: AssistNaviForm.class.php,v 1.1 2005/12/15 00:20:18 ryu Exp $
 */
 require_once MO_WEBAPP_DIR .'/lib/HNbActionForm.class.php';
 require_once MO_WEBAPP_DIR.'/hitobito/hitobitoImage.class.php';
class AssistNaviForm extends HNbSemiAutoActionForm
{
	public
			$navi_name1,
			$navi_name2,
			$navi_image_id,
			$navi_self_introduction,
			$navi_status;
	
	protected function getStringParameterKeys()
	{
		return array(
			'navi_name1',
			'navi_name2',
			'navi_self_introduction');
	}
	/**
	 * 整数パラメータキーの配列を返す
	 */
	protected function getIntegerParameterKeys()
	{
		return array('navi_status');
	}
	/**
	 * 入力必須パラメータをキー、値にエラーメセッージを入れた連想配列を返す
	 * @example return array('email' => 'メールアドレスが入力されていません');
	 */	
	protected function getRequireParameterKeys()
	{
		return array(
			'navi_name1' => HNb::tr('名前を入力してください'),
			'navi_name2' => HNb::tr('名前を入力してください'),
			'navi_self_introduction' => HNb::tr('紹介文を入力してください')
			);
	}
	public function fetch($master , $request)
	{
		parent::fetch($master, $request);
		
		// 画像UPLOAD
		// TODO hitobitoImage クラスにまとめる？
		if($request->hasFile('navi_photo') && $request->getFileError('navi_photo') != UPLOAD_ERR_NO_FILE ){
			if($request->getFileError('navi_photo')){
				$this->addError(HNb::tr('ナビ写真のアップロードに失敗しました。'));
				return;
				// TODO より詳細にする？
			}
			// 横80pxにリサイズする
			$uploadFilePath = $request->getFilePath('navi_photo');
			list($imageWidth, $imageHeight, $imageType, $imageHtmlAttribute) = getimagesize($uploadFilePath);
			if($imageType > 3){
				$this->addError(HNb::tr('ナビ写真としてアップロードできるのは、GIF, JPG, PNG画像だけです'));
				return ;
			}
			$fileName = $request->getFileName('navi_photo');
			$fileType = $request->getFileType('navi_photo');
			
			// 縦サイズを計算
			$hiritu = $imageWidth / 80;
			$targetHeight = $imageHeight / $hiritu;
			$targetId = imagecreatetruecolor(80, $targetHeight);
			
			switch($imageType){
				case IMG_GIF:
					$sourceId = imagecreatefromgif($uploadFilePath);
					break;
				case IMG_JPG:
					$sourceId = imagecreatefromjpeg($uploadFilePath);
					break;
				case IMG_PNG:
					$sourceId = imagecreatefrompng($uploadFilePath);
					break;
			}
			imagecopyresampled($targetId, $sourceId, 0,0,0,0, 80,$targetHeight, $imageWidth, $imageHeight);
			$tmpPath = MO_WEBAPP_DIR.'/tmp/'.uniqid();
			switch($imageType){
				case IMG_GIF:
					imagegif($targetId, $tmpPath);
					break;
				case IMG_JPG:
					imagejpeg($targetId, $tmpPath);
					break;
				case IMG_PNG:
					imagepng($targetId, $tmpPath);
					break;
			}
//			imagegd2($targetId, $tmpPath);
			$filesize = filesize($tmpPath);
			$fp = fopen($tmpPath, 'r');
			$data = fread($fp, $filesize);
			fclose($fp);
			
			$imageHandler = new hitobitoImageManager();
			$image = $imageHandler->get($this->navi_image_id);
			$image->setAttribute('img_data_size', $filesize);
			$image->setAttribute('img_file_name', $fileName);
			$image->setAttribute('img_content_type',$fileType);
			$image->setAttribute('img_image_data',($data));
			$imageHandler->insert($image);
			$this->navi_image_id = $image->getId();
					
		}
			

	}
	
	public function load($master, $requset)
	{
		$this->navi_name1 = $master->getAttribute('nav_name1');
		$this->navi_name2 = $master->getAttribute('nav_name2');
		$this->navi_self_introduction = $master->getAttribute('anv_self_introduction');
		$this->navi_image_id = $master->getAttribute('anv_image_id');
		$this->navi_status = $master->getAttribute('anv_status');
	}
	
	public function update($master, $request)
	{
		
		$master->setAttribute('nav_name1', $this->navi_name1);
		$master->setAttribute('nav_name2', $this->navi_name2);
		$master->setAttribute('anv_self_introduction', $this->navi_self_introduction);
		$master->setAttribute('anv_image_id', $this->navi_image_id);
		$master->setAttribute('anv_status', $this->navi_status);
	}
}

class NewAssistNaviForm extends AssistNaviForm
{
	public $navi_email;
	private $navi_member_id;
	protected function getStringParameterKeys()
	{
		return array_merge(parent::getStringParameterKeys(), array('navi_email'));
	}
	/**
	 * 入力必須パラメータをキー、値にエラーメセッージを入れた連想配列を返す
	 * @example return array('email' => 'メールアドレスが入力されていません');
	 */	
	protected function getRequireParameterKeys()
	{
		return array_merge(parent::getRequireParameterKeys(), array('navi_email' => HNb::tr('アシストナビに指定するメンバーのメールアドレスを入力してください')));
	}
	public function fetch($master , $request)
	{
		parent::fetch($master, $request);
		$db = HNb::getAdodb();
		$sql = 'SELECT rmm_member_id FROM t_room_member JOIN t_member ON rmm_member_id=mbr_member_id ' .
				'WHERE mbr_mail_addr_low='.$db->qstr(strtolower($this->navi_email)).
				' AND rmm_room_id='.hitobito::getNaviPageInfo()->getWaiwaiId();
				
		$memberId = $db->GetOne($sql);
		if(!$memberId){
			$this->addError(HNb::tr('このメールアドレスのメンバーは、このナビページに登録していません'));
		}else{
			$this->navi_member_id = $memberId;
		}
		
	}	
	public function update($master, $request)
	{
		parent::update($master, $request);
		$master->setAttribute('nav_member_id', $this->navi_member_id);	
	}
	
}

?>