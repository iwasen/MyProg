<?php
/**
 * 画像表示
 */
 require_once '../webapp/config.php';
 require_once '../webapp/lib/HNb.class.php';
 require_once(MO_WEBAPP_DIR."/lib/adodb/adodb.inc.php");
require_once(MO_WEBAPP_DIR."/lib/adodb/adodb-error.inc.php");
  define('IMG_CACHE', FALSE);
 
 $id = intval($_GET['id']);
 $db = HNb::getAdodb();
 $image = $db->GetRow("SELECT * FROM t_blog_image WHERE bli_blog_image_id=".$id);
 if($image['bli_blog_image_id']> 0){
 	header('Content-type: '.$image['bli_content_type']);
 	if(IMG_CACHE){
	    header('Cache-control: max-age=31536000');
	    header('Expires: '.gmdate("D, d M Y H:i:s",time()+31536000).'GMT');
 	}else{
		// 日付が過去
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		
		// 常に修正されている
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		 
		// HTTP/1.1
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		
		// HTTP/1.0
		header("Pragma: no-cache");    header('Content-disposition: filename='.$image['img_file_name']);
 	}
    header('Content-disposition: filename='.$image['bli_file_name']);
    header('Content-Length: '.strlen($image['bli_image_data']));
    echo $image['bli_image_data'];
 	
 }else{
 	header('Location: '.'image/hitobito.gif');
 	exit();
//	header('HTTP/1.0 404 Not Found');
//	exit();
 }
?>
