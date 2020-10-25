<?php
/**
 * バナークリック
 * 
 * クリック履歴の記録とリンク先へのリダイレクト
 */
 
require_once '../webapp/config.php';
require_once '../webapp/lib/HNb.class.php';
require_once(MO_WEBAPP_DIR."/lib/adodb/adodb.inc.php");
require_once(MO_WEBAPP_DIR."/lib/adodb/adodb-error.inc.php");
 
 $id = intval($_GET['id']);
 $IP = $_SERVER['REMOTE_ADDR'];
 
 $db = HNb::getAdodb();
 $url = $db->GetOne("SELECT bnr_link_url FROM t_banner WHERE bnr_banner_id=".$id);
 if(empty($url)){
	header('HTTP/1.0 404 Not Found');
	exit();
 }
 // クリック履歴
 $sql = sprintf("INSERT INTO l_banner_click (bnl_banner_id, bnl_ip_adr, bnl_date) VALUES (%d, '%s', now())",
 	$id,
 	$IP);
 $db->Execute($sql);
 
 // clickカウント
 if($db->GetOne('SELECT count(*) FROM c_banner_click WHERE bnc_banner_id='.$id)){
 	$db->Execute('UPDATE c_banner_click SET bnc_click_count=bnc_click_count+1 WHERE bnc_banner_id='.$id);
 }else{
 	$db->Execute('INSERT INTO c_banner_click (bnc_banner_id, bnc_click_count) VALUES('.$id.', 1)');
 }
 header('Location: '.$url);
 exit();
?>
