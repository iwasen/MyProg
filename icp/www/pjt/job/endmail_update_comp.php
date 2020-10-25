<?
/******************************************************
' System :ICPプロジェクトページ
' Content:終了メール設定完了ページ
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/auth.php");
include("$inc/check.php");

$inc = "$top/inc";
include("$inc/pjt_common.php");
include("$inc/login_check.php");
include("$inc/header.php");
include("$inc/err_msg.php");

// 権限チェック
if (!check_staff_auth('ジョブ管理'))
	redirect("$top/index.php");

// 入力パラメータ
$job_id = $_POST['job_id'];

// ジョブ名（ジョブコード）取得
$job_name_cd = get_job_name_cd($job_id);

// 同報先
$ary = explode(',', $_POST['add_mail']);
foreach ($ary as $mail_addr) {
	$mail_addr = trim($mail_addr);
	if ($mail_addr != '') {
		if (!check_mail_addr($mail_addr)) {
			$msg[] = "同報先に不正なメールアドレスが含まれています。";
			break;
		}
		if ($add_mail != '')
			$add_mail .= ' ';
		$add_mail .= $mail_addr;
	}
}

if (!$msg) {
	// ジョブ更新
	$rec['jb_end_subject'] = sql_char($_POST['subject']);
	$rec['jb_end_from_name'] = sql_char($_POST['from_name']);
	$rec['jb_end_content'] = sql_char($_POST['content']);
	$rec['jb_end_send_date'] = sql_date("{$_POST['send_year']}/{$_POST['send_month']}/{$_POST['send_day']} {$_POST['send_hour']}:{$_POST['send_minute']}");
	$rec['jb_end_add_mail'] = sql_char($add_mail);
	$rec['jb_end_status'] = sql_number(1);
	db_update('t_job', $rec, sprintf("jb_job_id=%s", sql_number($job_id)));
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="ja">

<head>
	<meta http-equiv="content-type" content="text/html; charset=EUC-JP">
	<meta http-equiv="Pragma" content="no-cache">
	<title><?=htmlspecialchars($_SESSION[SCD]['pjt_name'])?>　icp.net</title>
	<link rel="stylesheet" type="text/css" href="<?=$top?>/default.css">
	<script type="text/javascript">
<!--
function goto(page) {
	var f = document.goto1;
	f.action = page;
	f.submit();
}
//-->
	</script>
</head>

<body>
<div align="center">

<form method="post" name="goto1">
<input type="hidden" name="job_id" <?=value($job_id)?>>
</form>

<? pjt_header() ?>

<!-- pankuzu -->
<table border="0" cellspacing="0" cellpadding="3" bgcolor="#fdc92b" width="800" class="pankuzu">
<tr>
	<td><a href="../index.php" title="ホーム">ホーム</a>
		&nbsp;&nbsp;＞&nbsp;&nbsp;
		<a href="javascript:goto('detail.php')" title="このジョブの詳細を表示します"><?=htmlspecialchars($job_name_cd)?>情報</a>
		&nbsp;&nbsp;＞&nbsp;&nbsp;
		終了メール設定更新完了
		</td>
</tr>
</table>
<!-- pankuzu -->

<!-- 新規ジョブ -->
<table border="0" cellspacing="0" cellpadding="0" width="800" class="regist">
<tr>
	<td width="10"><img src="../img/spacer.gif" width="10" height="30" border="0"></td>
	<td width="790" align="right"><img src="../img/spacer.gif" width="10" height="30" border="0"></td>
</tr>
</table>
<!-- 新規ジョブ -->

<?
if (!$msg) {
?>
<!-- main -->
<table border="0" cellspacing="0" cellpadding="0" width="800" class="main">
<tr>
	<td width="20"><img src="../img/spacer.gif" width="20" height="1" border="0"></td>
	<td width="760">
		<table border="0" cellspacing="0" cellpadding="3" width="760" class="contents">
		<tr>
			<td>■終了メール設定を更新しました</td>
		</tr>
		</table>

	</td>
	<td width="20"><img src="../img/spacer.gif" width="20" height="1" border="0"></td>
</tr>
</table>
<!-- main -->

<!-- 戻る -->
<table border="0" cellspacing="0" cellpadding="0" width="800" class="regist">
<tr>
	<td width="10"><img src="../img/spacer.gif" width="10" height="30" border="0"></td>
	<td width="780" align="center"><a href="javascript:goto('detail.php')" title="ジョブ詳細に戻る">ジョブ詳細に戻る</a></td>
	<td width="10"><img src="../img/spacer.gif" width="10" height="30" border="0"></td>
</tr>
</table>
<!-- 戻る -->
<?
} else
	disp_err('開始メール設定で次のエラーがありました。', $msg);
?>

<? pjt_footer() ?>

</div>
</body>

</html>
