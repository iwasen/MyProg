<?
/******************************************************
' System :きかせて・net事務局・エージェント用ページ
' Content:Myパートナー・閲覧者登録
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/icp_db.php");
include("$inc/icp_func.php");
include("$inc/icp_regist.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

//メイン処理
set_global2('mypartner', 'Myパートナー管理', 'Myパートナー実施ジョブ　閲覧者登録', BACK_TOP);

icp_db_begin_trans();

if (!$msg) {
	// ICPプロジェクトID取得
	$icp_pjt_id = get_icp_pjt_id();

	for ($i = 0; $i < count($name); $i++) {
		if ($name[$i] != "") {
			$data = array();
			$data['mail_addr'] = strtolower($mail_addr[$i]);
			$data['password'] = $password[$i];
			$data['nickname'] = $name[$i];
			regist_icp_member($icp_pjt_id, $icp_job_id, $data, '0000000000', '0001100000', 0);
		}
	}
}

icp_db_commit_trans();
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<? menu_script() ?>
</head>
<body>
<? page_header() ?>
<? menu_start() ?>

<table border=0 cellspacing=2 cellpadding=3 width=700>
	<tr>
		<td align="center" class="m4"><?=$g_title?></td>
	</tr>
	<tr>
		<td align="center"><br>
			<p class="msg">閲覧者を登録しました。</p>
			<p><input type="button" value="　戻る　" onclick="location.href='pjt_job.php?pjt_id=<?=$pjt_id?>'"></p>
		</td>
	</tr>
</table>

<? menu_end() ?>
<? page_footer() ?>
</body>
</html>
