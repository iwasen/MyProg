<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:プロモーション管理
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");

set_global('monitor', 'モニター管理', 'プロモーション管理', BACK_TOP);

db_begin_trans();

$sql = "SELECT pr_contents_pict FROM t_promotion WHERE pr_pr_id=$pr_id";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	if ($contents_pict == 'none' || $contents_pict == '')
		$contents_oid = $fetch->pr_contents_pict;
	else {
		if ($fetch->pr_contents_pict) {
			$err_level = error_reporting(0);
			if (!pg_lounlink($gConn, $fetch->pr_contents_pict)) {
				db_rollback();
				db_begin_trans();
			}
			error_reporting($err_level);
		}
		$contents_oid = pg_loimport($contents_pict, $gConn);
	}
}

switch ($next_action) {
case 'new':
	$sql = sprintf("INSERT INTO t_promotion (pr_pr_id,pr_media_id,pr_pr_name,pr_adv_id,pr_contents_type,pr_contents_text,pr_contents_pict,pr_url,pr_haishin_suu,pr_click_suu,pr_haishin_start,pr_haishin_end,pr_cost,pr_affiliate,pr_bikou,pr_jump_url) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
		sql_number($pr_id),
		sql_number($media_id),
		sql_char($pr_name),
		sql_number($adv_id),
		sql_number($contents_type),
		sql_char($contents_text),
		sql_number($contents_oid),
		sql_char($url),
		sql_number($haishin_suu),
		sql_number($click_suu),
		sql_datetime($haishin_start_y, $haishin_start_y, $haishin_start_m, $haishin_start_d),
		sql_datetime($haishin_end_y, $haishin_end_y, $haishin_end_m, $haishin_end_d),
		sql_number($cost),
		sql_number($affiliate),
		sql_char($bikou),
		sql_char($jump_url_flag == '1' ? '' : $jump_url));
	db_exec($sql);
	$msg = 'プロモーションを登録しました。';
	break;
case 'update':
	$sql = sprintf("UPDATE t_promotion SET pr_media_id=%s,pr_pr_name=%s,pr_adv_id=%s,pr_contents_type=%s,pr_contents_text=%s,pr_contents_pict=%s,pr_url=%s,pr_haishin_suu=%s,pr_click_suu=%s,pr_haishin_start=%s,pr_haishin_end=%s,pr_cost=%s,pr_affiliate=%s,pr_bikou=%s,pr_jump_url=%s WHERE pr_pr_id=$pr_id",
		sql_number($media_id),
		sql_char($pr_name),
		sql_number($adv_id),
		sql_number($contents_type),
		sql_char($contents_text),
		sql_number($contents_oid),
		sql_char($url),
		sql_number($haishin_suu),
		sql_number($click_suu),
		sql_datetime($haishin_start_y, $haishin_start_y, $haishin_start_m, $haishin_start_d),
		sql_datetime($haishin_end_y, $haishin_end_y, $haishin_end_m, $haishin_end_d),
		sql_number($cost),
		sql_number($affiliate),
		sql_char($bikou),
		sql_char($jump_url_flag == '1' ? '' : $jump_url));
	db_exec($sql);
	$msg = 'プロモーション情報を更新しました。';
	break;
case 'delete':
	$sql = "DELETE FROM t_promotion WHERE pr_pr_id=$pr_id";
	db_exec($sql);
	$msg = 'プロモーションを削除しました。';
	break;
default:
	redirect('list.php');
}

db_commit_trans();
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
</head>
<body onLoad="document.all.ok.focus()">
<? page_header() ?>

<div align="center">
<p class="msg"><?=$msg?></p>
<p><input type="button" id="ok" value="　戻る　" onclick="location.href='list.php'"></p>
</div>

<? page_footer() ?>
</body>
</html>
