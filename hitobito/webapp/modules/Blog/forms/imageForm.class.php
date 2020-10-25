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

        // 入力必須項目のチェック 
        if (strlen($this->blog_image_title) == 0)
        {
            $this->addError('タイトルを入力してください');
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
	
    private $form_file = 'blog_image_file'; // フォーム中のファイル選択エレメントのname
    private $form_title = 'blog_image_title'; // フォーム中のファイルタイトル
    private $errors = array ();
    private $uploadFilePath, $originalWidth, $originalHeight, $type, $filename, $mimetype;
    private $permitedImageTypes = array (IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG);
    private $resizedId = NULL; // リサイズ後のイメージリソース
    // リサイズ後のサイズ
    private $resizedWidth, $resizedHeight;
    // リサイズ後のファイルサイズ
    private $filesize;
    // リサイズ後のファイルデータ
    private $data;
    
    public function fetch($master, $request)
    {
    	parent::fetch($master, $request);
    	$this->blog_execute_resize = intval($request->getParameter('blog_execute_resize'));
		if(!$request->hasFile($this->form_file)){
			$this->addError(HNb::tr('ファイルが指定されていません'));
			return ;
		}
		switch($request->getFileError($this->form_file)){
			case UPLOAD_ERR_OK:
				// エラー無し
				break;
			case UPLOAD_ERR_INI_SIZE:
				$this->addError(HNb::tr('サーバの制限によりアップロードに失敗しました'));
				return;
			case UPLOAD_ERR_FORM_SIZE:
				$this->addError(HNb::tr('ファイルサイズが大きすぎます'));
				return;
			default:
				$this->addError(HNb::tr('ファイルのアップロードに失敗しました'));
				return;
		}

		$this->uploadFilePath = $request->getFilePath($this->form_file);
		
		list($this->originalWidth, $this->originalHeight, $this->type, $imageHtmlAttribute) = getimagesize($this->uploadFilePath);
		if(!in_array($this->type, $this->permitedImageTypes)){
			$this->addError(HNb::tr('この種類のファイルはアップロードできません。'));
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
			// 幅、高さともに最大サイズ以内なのでリサイズの必要なし
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