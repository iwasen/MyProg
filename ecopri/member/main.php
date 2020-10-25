<?
/******************************************************
' System :Eco-footprint 会員ページ
' Content:会員トップページ
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/decode.php");
$inc = "$top/inc";
include("$inc/mypage.php");

// セッション変数削除
unset($_SESSION['ss_result_input']);
session_unregister('ss_result_input');
unset($_SESSION['ss_fiducial1']);
session_unregister('ss_fiducial1');

// Welcomeメッセージ表示
$sql = "SELECT wm_text from t_welcome_msg where 'now' between wm_start_date and wm_end_date order by wm_regist_date DESC";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$welcome_msg = $fetch->wm_text;
}
// News!!表示
$sql = "SELECT ns_text from t_news where 'now' between ns_start_date and ns_end_date order by ns_regist_date DESC";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$news = $fetch->ns_text;
}
// 事務局からのお知らせ表示
$sql = "SELECT an_text from t_admin_msg where 'now' between an_start_date and an_end_date order by an_regist_date DESC";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$info = $fetch->an_text;
}

// 今月の年月取得
$ym = date('Ym');

// ガンバリストの内容表示
$sql = "SELECT gm_eng_bunrui, gm_bunrui1, COALESCE(gn_advice,gm_advice) AS advice,EXISTS (SELECT * FROM t_master_mark WHERE mm_mb_seq_no=gn_seq_no AND mm_ganbalist_id=gn_ganbalist_id) AS master_mark"
		. " FROM t_ganbalist JOIN m_ganbalist ON gm_ganbalist_id=gn_ganbalist_id AND gm_eng_bunrui=gn_eng_bunrui"
		. " WHERE gn_seq_no=" . $_SESSION['ss_seq_no'] . " AND gn_ym='$ym'"
		. " ORDER BY gn_eng_bunrui,gn_ganbalist_id";
$result = db_exec($sql);
$nrow = pg_numrows($result);
if ($nrow > 0) {
	$advice = "今月のガンバリスト + ";
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$advice .= "+ " . ($i+1) . " ：【" . deocde_eng($fetch->gm_eng_bunrui) . "・" . decode_bunrui($fetch->gm_bunrui1) . "】" .$fetch->advice . " + ";
	}
} else {
	$advice = '今月のガンバリストが作成されていません';
}
// Flashパラメータ編集
$fv[] = "effect={$_SESSION['ss_yet_flag']}";
$fv[] = "thisMonth={$_SESSION['ss_cr_m']}";
$fv[] = "year=" . date(Y);
$fv[] = "month=" . date(m);
$fv[] = "dateNum=" . date(d);
$fv[] = "userName={$_SESSION['ss_name1']}";
$fv[] = "news=$news";
$fv[] = "info=$info";
$fv[] = "comment=$welcome_msg";
$fv[] = "elc_spendRank={$_SESSION['ss_ele_rank']}";
$fv[] = "gas_spendRank={$_SESSION['ss_gas_rank']}";
$fv[] = "wtr_spendRank={$_SESSION['ss_wtr_rank']}";
$fv[] = "oil_spendRank={$_SESSION['ss_oil_gso_rank']}";
$fv[] = "dst_spendRank={$_SESSION['ss_dst_rank']}";
$fv[] = "co2_spendRank={$_SESSION['ss_rank']}";
$fv[] = "scrollText=$advice";
$fv = join('&', $fv);
?>

<HTML>
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>☆★☆xxxゾウさんClub☆★☆</title>
<script type="text/javascript">
<!--
function open_window(url) {
 window.open(url, "_blank","width=750,scrollbars=yes,resizable=yes,status=no,menubar=no,toolbar=no");
}
//-->
</script>
</HEAD>
<BODY bgcolor="#FF0000">
<Div Align="center">
<!-- URL's used in the movie-->
<!-- text used in the movie-->
<OBJECT ID=SWFlash classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0"
WIDTH="760" HEIGHT="550">
<PARAM NAME=movie VALUE="flash/main.swf">
<PARAM NAME=quality VALUE=high>
<PARAM NAME=bgcolor VALUE=#FFFFFF>
<PARAM NAME=play VALUE=TRUE>
<PARAM NAME=loop VALUE=FALSE>
<PARAM NAME=menu VALUE=FALSE>
<PARAM NAME=FlashVars VALUE="<?=$fv?>">
<EMBED src="flash/main.swf" play=TRUE loop=FALSE quality=high bgcolor=#FFFFFF  WIDTH="760" HEIGHT="550" NAME="SWFlash" 
FlashVars="<?=$fv?>" 
TYPE="application/x-shockwave-flash" PLUGINSPAGE="http://www.macromedia.com/go/getflashplayer"></EMBED>
</OBJECT>
</Div>

</BODY>

</HTML>
