<?
/******************************************************
' System :きかせて・netアンケートページ
' Content:リクエスト先を判断し
'******************************************************/
include("mn_define.php");

session_start();

//infoQから
if (isset($_REQUEST['id'])){
    //idの値「g〜g〜」をgを区切り文字とする配列に変換
    unset($ar);
    $ar = explode('g',strtolower($_REQUEST['id']));
    $_SESSION['ss_req_flg'] = 'g';

    // 2006/04/24 URL?enquete_id=<enqueteID>&id=g<monitorID>g<enqueteID>対応
    $enquete_id = $_REQUEST['enquete_id'];
    $enqId =  (isset($ar[2])) ? $ar[2] : '';
    $_SESSION['enquete_id'] = $enquete_id;
    $mon =  (isset($ar[1])) ? $ar[1] : '';
    $_SESSION['ss_monitor_id'] = $mon;
//    $_SESSION['ss_ret_url'] = INFOQ_URL."?id=".$_REQUEST['id'];
//    $_SESSION['ss_ret_url'] = INFOQ_URL."?monitorId=".$mon."&enqId=".$enquete_id;
    $_SESSION['ss_ret_url'] = INFOQ_URL."?monitorId=".$mon."&enqId=".$enqId;
    if (isset($_REQUEST['k'])){
        // ファインディング→本アンケート
        $_SESSION['ss_prev_kind'] = $_REQUEST['k'];
    } else {
        $_SESSION['ss_prev_kind'] = '';
    }
//きかせて.net
}elseif (isset($_REQUEST['enquete_id'])){
    // 回答格納テストの場合、管理者のＩＤを使用 BTI
    if( !isset($_SESSION['ss_monitor_id']) && isset($_SESSION['admin_login_id']) && $_SESSION['admin_login_id'] != '' ) {
        $admin_login_id = $_SESSION['admin_login_id'];
        $sql = "SELECT mn_monitor_id FROM m_admin JOIN t_test_monitor ON mn_mail_addr=ad_mail_addr WHERE ad_admin_id=$admin_login_id";
        $result = db_exec($sql);
        if (pg_numrows($result)) {
            $fetch = pg_fetch_object($result, 0);
            $_SESSION['ss_monitor_id'] = $fetch->mn_monitor_id;
        }
    }
/*
    if (!isset($_SESSION['ss_monitor_id'])) {
//          header("location: $top/mypage/login_error_main.php");
            header("location: $top/mypage/login_error_close.php");
            exit;
    }
*/
	if (isset($_REQUEST['monitor_id']))
	    $_SESSION['ss_monitor_id'] = $_REQUEST['monitor_id'];

    $_SESSION['ss_req_flg'] = 't';
    $_SESSION['ss_ret_url'] = KIKASETE_URL."?enquete_id=".$_REQUEST['enquete_id'];
}elseif ($_SESSION['ss_req_flg'] == 'g'){
    $enquete_id = $_SESSION['enquete_id'];
}

?>