<?php
/**
 * リンク訪問
 */
 require_once '../webapp/config.php';
 require_once '../webapp/lib/HNb.class.php';
 require_once(MO_WEBAPP_DIR."/lib/adodb/adodb.inc.php");
require_once(MO_WEBAPP_DIR."/lib/adodb/adodb-error.inc.php");
 
 $id = intval($_GET['id']);
 $db = HNb::getAdodb();
 $url = $db->GetOne("SELECT nol_link_url FROM t_navi_osusume_link WHERE nol_osusume_link_id=".$id);
 if(empty($url)){
	header('HTTP/1.0 404 Not Found');
	exit();
 }
 if($db->GetOne('SELECT count(*) FROM c_navi_osusume_link WHERE noc_osusume_link_id='.$id)){
 	$db->Execute('UPDATE c_navi_osusume_link SET noc_click_count=noc_click_count+1 WHERE noc_osusume_link_id='.$id);
 }else{
 	$db->Execute('INSERT INTO c_navi_osusume_link (noc_osusume_link_id, noc_click_count) VALUES('.$id.', 1)');
 }
 header('Location: '.$url);
 exit();
?>
