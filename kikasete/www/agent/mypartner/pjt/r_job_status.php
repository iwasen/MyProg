<?
/******************************************************
' System :きかせて・net事務局・エージェント用ページ
' Content:Myパートナー・リクルートジョブステータス変更
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/my_partner.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

//メイン処理
set_global2('mypartner', 'Myパートナー管理', 'Myパートナーリクルートジョブステータス変更', BACK_TOP);

$myp = new my_partner_class;
$myp->read_db($pjt_id);

switch ($myp->status) {
case MPS_REC_JUNBI:
	$myp->status = MPS_REC_KANRYOU;
	$msg = 'リクルートジョブのステータスを作成完了状態に設定しました。';
	break;
case MPS_REC_KANRYOU:
	$myp->status = MPS_REC_JUNBI;
	$msg = 'リクルートジョブのステータスを作成中に戻しました。';
	break;
}
$myp->write_db();
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
			<p class="msg"><?=$msg?></p>
			<p><input type="button" value="　戻る　" onclick="location.href='r_job.php?pjt_id=<?=$pjt_id?>'"></p>
		</td>
	</tr>
</table>

<? menu_end() ?>
<? page_footer() ?>
</body>
</html>
