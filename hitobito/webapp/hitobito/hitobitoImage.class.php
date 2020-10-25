<?php
/**
 * 
 */
require_once MO_WEBAPP_DIR.'/hitobito/db/t_image.class.php';

class hitobitoImageManager extends t_imageObjectHandler
{
	function insert($obj)
	{
		if($obj->getId() > 0){
			$sql = sprintf("UPDATE t_image SET img_data_size=%d, img_file_name=%s, img_content_type=%s, img_image_data='%s' WHERE img_image_id=%d",
				$obj->getAttribute('img_data_size'),
				$this->db->qstr($obj->getAttribute('img_file_name')),
				$this->db->qstr($obj->getAttribute('img_content_type')),
				pg_escape_bytea($obj->getAttribute('img_image_data')),
				$obj->getId()
				);
			$result = $this->db->Execute($sql);
			return $result;
		}else{
			$sql = sprintf("INSERT INTO t_image (img_data_size, img_file_name, img_content_type, img_image_data) VALUES (%d, %s, %s, '%s')",
				$obj->getAttribute('img_data_size'),
				$this->db->qstr($obj->getAttribute('img_file_name')),
				$this->db->qstr($obj->getAttribute('img_content_type')),
				pg_escape_bytea($obj->getAttribute('img_image_data'))
				);
			$result = $this->db->Execute($sql);
			$oid = $this->db->Insert_ID();
			$id = $this->db->GetOne('SELECT img_image_id FROM t_image WHERE oid='.$oid);
			$obj->setAttribute('img_image_id', $id);
			return $result;
		}
	}
}
?>