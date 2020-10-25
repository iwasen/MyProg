<?php
/**
 * @package	Member
 * @author Ryuji
 * @version $Id: NaviProfileForm.class.php,v 1.6 2005/12/25 21:50:40 ryu Exp $
 */
 require_once MO_WEBAPP_DIR .'/lib/HNbActionForm.class.php';
 require_once MO_WEBAPP_DIR.'/hitobito/hitobitoImage.class.php';
class NaviProfileForm extends HNbActionForm
{
	public
			$navi_photo_id,
			$navi_copy,
			$navi_self_introduction,
			$navi_page_title,
			$navi_page_outline
			;
	
	public function fetch($master , $request)
	{
		parent::fetch($master, $request);
		$strParamsKey = array(
			'navi_page_title',
			'navi_copy',
			'navi_self_introduction',
			'navi_page_outline'
		);
		$intParamsKey = array(
		);
		
		foreach($strParamsKey as $key){
			$this->$key = trim(strip_tags($request->getParameter($key, '')));
		}
		
		foreach($intParamsKey as $key){
			$this->$key = intval($request->getParameter($key, 0));
		}
		
		
		
		$require_params = array(
			'navi_copy' => HNb::tr('�ʥӥ��ԡ���̤���ϤǤ�'),
			'navi_self_introduction' =>HNb::tr('���ʾҲ�̤���ϤǤ�'),
			'navi_page_title' => HNb::tr('�ʥӥڡ��������ȥ뤬̤���ϤǤ�'),
			'navi_page_outline' => HNb::tr('���פ�̤���ϤǤ�')
		);
		// ����ɬ�ܥ����å�
		foreach($require_params as $key=>$message){
			if(in_array($key, $intParamsKey)){
				if($this->$key == 0){
					$this->addError($message);
				}
			}else{
				if(strlen($this->$key) ==0){
					$this->addError($message);
				}
			}
		}
		
		// ����UPLOAD
		// TODO hitobitoImage ���饹�ˤޤȤ�롩
		if($request->hasFile('navi_photo') && $request->getFileError('navi_photo') != UPLOAD_ERR_NO_FILE ){
			if($request->getFileError('navi_photo')){
				$this->addError(HNb::tr('�ʥӼ̿��Υ��åץ��ɤ˼��Ԥ��ޤ�����'));
				return;
				// TODO ���ܺ٤ˤ��롩
			}
			// ��80px�˥ꥵ��������
			$uploadFilePath = $request->getFilePath('navi_photo');
			list($imageWidth, $imageHeight, $imageType, $imageHtmlAttribute) = getimagesize($uploadFilePath);
			if($imageType > 3){
				$this->addError(HNb::tr('�ʥӼ̿��Ȥ��ƥ��åץ��ɤǤ���Τϡ�GIF, JPG, PNG���������Ǥ�'));
				return ;
			}
			$fileName = $request->getFileName('navi_photo');
			$fileType = $request->getFileType('navi_photo');
			
			// �ĥ�������׻�
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
			$image = $imageHandler->get($this->navi_photo_id);
			$image->setAttribute('img_data_size', $filesize);
			$image->setAttribute('img_file_name', $fileName);
			$image->setAttribute('img_content_type',$fileType);
			$image->setAttribute('img_image_data',($data));
			$imageHandler->insert($image);
			$this->navi_photo_id = $image->getId();
					
		}
			

	}
	
	public function load($master, $requset)
	{
		$this->navi_copy = $master->getAttribute('nvp_copy');
		$this->navi_self_introduction = $master->getAttribute('nvp_self_introduction');
		$this->navi_photo_id = $master->getAttribute('nvp_image_id');
		$this->navi_page_title = $master->getAttribute('nvp_title');
		$this->navi_page_outline = $master->getAttribute('nvp_outline');
	}
	
	public function update($master, $request)
	{
		
		$master->setAttribute('nvp_copy', $this->navi_copy);
		$master->setAttribute('nvp_self_introduction', $this->navi_self_introduction);
		$master->setAttribute('nvp_image_id', $this->navi_photo_id);
		$master->setAttribute('nvp_title', $this->navi_page_title);
		$master->setAttribute('nvp_outline', $this->navi_page_outline);
	}
}

?>