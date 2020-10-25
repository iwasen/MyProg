<?php
/**
 * BLOG画像処理クラス
 * 
 * @package Blog
 * @author Ryuji
 * @version $Id: BlogImage.class.php,v 1.1 2005/12/05 21:29:16 ryu Exp $
 */
 require_once MO_WEBAPP_DIR . '/hitobito/db/t_blog_image.class.php';
class BlogImage extends t_blog_imageObject
{

	public function __construct()
	{
		parent::__construct();
	}
}

class BlogImageManager extends t_blog_imageObjectHandler
{
	public function __construct()
	{
		parent::__construct();
	}
	
	function insert($obj)
	{
		if($obj->getId() > 0){
			$sql = sprintf("UPDATE t_blog_image SET bli_title=%s WHERE bli_blog_image_id=%d",
				$this->db->qstr($obj->getAttribute('bli_title')),
				$obj->getId()
				);
			$result = $this->db->Execute($sql);
			return $result;
		}else{
			$sql = sprintf("INSERT INTO t_blog_image (bli_data_size, bli_file_name, bli_content_type, bli_image_data, bli_blog_id, bli_title, bli_width, bli_height)" .
					" VALUES (%d, %s, %s, '%s', %d, %s, %d, %d)",
				$obj->getAttribute('bli_data_size'),
				$this->db->qstr($obj->getAttribute('bli_file_name')),
				$this->db->qstr($obj->getAttribute('bli_content_type')),
				pg_escape_bytea($obj->getAttribute('bli_image_data')),
				$obj->getAttribute('bli_blog_id'),
				$this->db->qstr($obj->getAttribute('bli_title')),
				$obj->getAttribute('bli_width'),
				$obj->getAttribute('bli_height')
				);
			$result = $this->db->Execute($sql);
			$oid = $this->db->Insert_ID();
			$id = $this->db->GetOne('SELECT bli_blog_image_id FROM t_blog_image WHERE oid='.$oid);
			$obj->setAttribute('bli_image_id', $id);
			return $result;
		}
	}
	
	

}

?>