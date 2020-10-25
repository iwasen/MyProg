<?php

/**
 * @package Blog
 * @author Ryuji
 * @version $Id: imageForm.class.php,v 1.1 2005/12/05 21:29:16 ryu Exp $
 */
require_once MO_WEBAPP_DIR.'/lib/HNbActionForm.class.php';

class BlogImageForm extends HNbActionForm
{
    public $blog_image_title;
    public $id;

    public function fetch($master, $request)
    {
        parent :: fetch($master, $request);

        $strParamsKey = array ('blog_image_title');
        //		$intParamsKey = array('bla_open_flag', 'bla_navi_select_flag', 'bla_comment_flag', 'bla_trackback_flag', 'bla_blog_category_id');

        foreach ($strParamsKey as $key)
        {
            $this-> $key = trim($request->getParameter($key, ''));
        }

        $validater = new HNbValidater();

        // ����ɬ�ܹ��ܤΥ����å� 
        if (strlen($this->blog_image_title) == 0)
        {
            $this->addError('�����ȥ�����Ϥ��Ƥ�������');
        }

    }

    public function load($master, $request)
    {
        $this->blog_image_title = $master->getAttribute('bli_title');
    }

    public function update($master, $request)
    {
        $master->setAttribute('bli_title', $this->blog_image_title);
    }
}

class BlogNewImageForm extends blogImageForm
{
	public $blog_execute_resize = 0;
	
    private $form_file = 'blog_image_file'; // �ե�������Υե��������򥨥���Ȥ�name
    private $form_title = 'blog_image_title'; // �ե�������Υե����륿���ȥ�
    private $errors = array ();
    private $uploadFilePath, $originalWidth, $originalHeight, $type, $filename, $mimetype;
    private $permitedImageTypes = array (IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG);
    private $resizedId = NULL; // �ꥵ������Υ��᡼���꥽����
    // �ꥵ������Υ�����
    private $resizedWidth, $resizedHeight;
    // �ꥵ������Υե����륵����
    private $filesize;
    // �ꥵ������Υե�����ǡ���
    private $data;
    
    public function fetch($master, $request)
    {
    	parent::fetch($master, $request);
    	$this->blog_execute_resize = intval($request->getParameter('blog_execute_resize'));
		if(!$request->hasFile($this->form_file)){
			$this->addError(HNb::tr('�ե����뤬���ꤵ��Ƥ��ޤ���'));
			return ;
		}
		switch($request->getFileError($this->form_file)){
			case UPLOAD_ERR_OK:
				// ���顼̵��
				break;
			case UPLOAD_ERR_INI_SIZE:
				$this->addError(HNb::tr('�����Ф����¤ˤ�ꥢ�åץ��ɤ˼��Ԥ��ޤ���'));
				return;
			case UPLOAD_ERR_FORM_SIZE:
				$this->addError(HNb::tr('�ե����륵�������礭�����ޤ�'));
				return;
			default:
				$this->addError(HNb::tr('�ե�����Υ��åץ��ɤ˼��Ԥ��ޤ���'));
				return;
		}

		$this->uploadFilePath = $request->getFilePath($this->form_file);
		
		list($this->originalWidth, $this->originalHeight, $this->type, $imageHtmlAttribute) = getimagesize($this->uploadFilePath);
		if(!in_array($this->type, $this->permitedImageTypes)){
			$this->addError(HNb::tr('���μ���Υե�����ϥ��åץ��ɤǤ��ޤ���'));
		}
		$this->filename = $request->getFileName($this->form_file);
		$this->mimetype = $request->getFileType($this->form_file);
		
		switch($this->blog_execute_resize)
		{
			case 1:
				$this->resize(60, 60);
				break;
			case 2:
				$this->resize(75, 50);
				break;
			case 3:
				$this->resize(80, 80);
				break;
			case 4:
				$this->resize(100, 100);
				break;
			case 5:
				$this->resize(200, 200);
				break;
			case 6:
				$this->resize(300, 300);
				break;
			case 7:
				$this->resize(400, 400);
				break;
			case 8:
				$this->resize(500, 500);
				break;
			default:
				$this->resizedWidth = $this->originalWidth;
				$this->resizedHeight = $this->originalHeight;
		}
		
		
    	
    }
    public function load($master, $request)
    {
    	parent::load($master, $request);
    }
    public function update($master, $request)
    {
    	parent::update($master, $request);

		

		$master->setAttribute('bli_image_data', $this->getImageData());
    	$master->setAttribute('bli_data_size', $this->filesize);
		$master->setAttribute('bli_file_name', $this->filename);
		$master->setAttribute('bli_content_type', $this->mimetype);
		$master->setAttribute('bli_width', $this->resizedWidth);
		$master->setAttribute('bli_height', $this->resizedHeight);
		$master->setAttribute('bli_title', $this->blog_image_title);
		
    	
    }
	private function resize($maxWidth, $maxHeight)
	{
		$widthRatio = $this->originalWidth / $maxWidth;
		$heightRatio = $this->originalHeight / $maxHeight;
		if($widthRatio < 1 && $heightRatio < 1){
			// �����⤵�Ȥ�˺��祵��������ʤΤǥꥵ������ɬ�פʤ�
			$this->resizedWidth = $this->originalWidth;
			$this->resizedHeight = $this->originalHeight;
			return ;
		}
		$this->isModify = TRUE;
		$ratio = ($widthRatio > $heightRatio) ? $widthRatio : $heightRatio;
		$targetWidth = $this->originalWidth / $ratio;
		$targetHeight = $this->originalHeight / $ratio;
		
		$this->resizedId = imagecreatetruecolor($targetWidth, $targetHeight);
		$sourceId = $this->getSourceId();
		
		imagecopyresampled($this->resizedId, $sourceId, 0,0,0,0, $targetWidth, $targetHeight, $this->originalWidth, $this->originalHeight);
		$this->resizedWidth = $targetWidth;
		$this->resizedHeight = $targetHeight;
	}
	
	private function getSourceId()
	{
		switch($this->type){
			case IMAGETYPE_GIF:
				$sourceId = imagecreatefromgif($this->uploadFilePath);
				break;
			case IMAGETYPE_JPEG:
				$sourceId = imagecreatefromjpeg($this->uploadFilePath);
				break;
			case IMAGETYPE_PNG:
				$sourceId = imagecreatefrompng($this->uploadFilePath);
			case IMAGETYPE_BMP:
			default:
				$sourceId = imagecreatefromstring(file_get_contents($this->uploadFilePath));
				break;
		}
		return $sourceId;
	}
	
	private function getImageData()
	{
		if($this->resizedId){
			$tmpPath = MO_WEBAPP_DIR.'/tmp/'.uniqid();
			switch($this->type){
				case IMAGETYPE_GIF:
					imagegif($this->resizedId, $tmpPath);
					break;
				case IMAGETYPE_JPEG:
					imagejpeg($this->resizedId, $tmpPath);
					break;
				case IMAGETYPE_BMP:
				case IMAGETYPE_PNG:
					imagepng($this->resizedId, $tmpPath);
					break;
			}
		}else{
			$tmpPath = $this->uploadFilePath;
		}
		$this->filesize = filesize($tmpPath);
		$fp = fopen($tmpPath, 'r');
		$this->data = fread($fp, $this->filesize);
		fclose($fp);
		@unlink($tmpPath);
		
		return $this->data;
	}

}
?>