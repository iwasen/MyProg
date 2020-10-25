<?
/******************************************************
' System :ICPオペレータページ
' Content:プロジェクト登録完了
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/auth.php");
include("$inc/status.php");

$inc = "$top/inc";
include("$inc/ope_common.php");
include("$inc/login_check.php");
include("$inc/header.php");
include("$inc/err_msg.php");

// ソースファイルをシンボリックリンクまたはコピー
function make_src_file($method, $pjt_cd) {
	global $top;

	$path = realpath(dirname($_SERVER['SCRIPT_FILENAME']) . "/$top/..");

	switch ($method) {
	case 'link':
		system("ln -s pjt $path/$pjt_cd");
		break;
	case 'copy':
		system("cp -R $path/pjt $path/$pjt_cd");
		break;
	}
}

// プロジェクトコード重複チェック
$sql = "SELECT COUNT(*) FROM t_pjt WHERE pj_pjt_cd=" . sql_char($_POST['pjt_cd']);
if (db_fetch1($sql) > 0)
	$msg[] = 'プロジェクトコード　' . htmlspecialchars($_POST['pjt_cd']) . '　は既に登録されています。';
else {
	// ソースファイル生成
	make_src_file($_POST['src_file'], $_POST['pjt_cd']);

	db_begin_trans();

	// プロジェクト登録
	$rec['pj_status'] = sql_number($g_pjt_status['稼動中']);
	$rec['pj_pjt_cd'] = sql_char($_POST['pjt_cd']);
	$rec['pj_name'] = sql_char($_POST['pj_name']);
	$rec['pj_description'] = sql_char($_POST['description']);
	$rec['pj_operator_id'] = sql_number($g_operator_id);
	db_insert('t_pjt', $rec);
	$pjt_id = get_current_seq('t_pjt', 'pj_pjt_id');

	// プロジェクトメンバ登録
	$rec['pm_status'] = sql_number(1);
	$rec['pm_pjt_id'] = sql_number($pjt_id);
	$rec['pm_operator_flag'] = sql_number(1);
	$rec['pm_staff_auth'] = sql_char(operator_staff_auth());
	$rec['pm_mail_addr'] = sql_char($_POST['mail_addr']);
	$rec['pm_password'] = sql_char($_POST['password']);
	$rec['pm_org_id'] = sql_number($_POST['org_id']);
	$rec['pm_nickname'] = sql_char($_POST['nickname']);
	$rec['pm_name1'] = sql_char($_POST['op_name1']);
	$rec['pm_name2'] = sql_char($_POST['op_name2']);
	$rec['pm_name1_kana'] = sql_char($_POST['op_name1_kana']);
	$rec['pm_name2_kana'] = sql_char($_POST['op_name2_kana']);
	db_insert('t_pjt_member', $rec);

	db_commit_trans();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="ja">

<head>
	<meta http-equiv="content-type" content="text/html; charset=EUC-JP">
	<meta http-equiv="Pragma" content="no-cache">
	<title>icp.net オペレーターページ</title>
	<link rel="stylesheet" type="text/css" href="<?=$top?>/default.css">
</head>

<body>
<div align="center">

<? ope_header() ?>

<table border="0" cellspacing="0" cellpadding="0" width="100%">
<tr>

<? ope_menu() ?>

	<!-- 右コンテンツ -->
	<td valign="top">

<?
if (!$msg) {
?>
		<table border="0" cellspacing="0" cellpadding="10" width="100%">
		<tr>
			<td>
				<table border="0" cellspacing="0" cellpadding="3" width="100%" class="pagetitle">
				<tr>
					<td>■新規プロジェクト登録完了</td>
				</tr>
				</table>

				<table border="0" cellspacing="0" cellpadding="10" width="100%" class="card">
				<tr>
					<td>新規プロジェクト　<?=htmlspecialchars($_POST['pj_name'])?>　を登録しました。</td>
				</tr>
				</table>

				<table border="0" cellspacing="0" cellpadding="10" width="100%" class="contents">
				<tr>
					<td align="center"><a href="../top.php">トップページに戻る</a>　｜　<a href="list.php">プロジェクト一覧に戻る</a>
					</td>
				</tr>
				</table>
<?
} else
	disp_err('新規プロジェクト登録エラー', $msg);
?>
			</td>
		</tr>
		</table>

	</td>
	<!-- 右コンテンツ -->
</tr>
</table>

<? ope_footer() ?>

</div>
</body>

</html>
