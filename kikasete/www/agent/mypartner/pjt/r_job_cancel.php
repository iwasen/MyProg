<?
/******************************************************
' System :きかせて・net事務局・エージェント用ページ
' Content:Myパートナー・リクルートジョブ承認取消し
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
set_global2('mypartner', 'Myパートナー管理', 'Myパートナーリクルートジョブ承認取消し', BACK_TOP);

db_begin_trans();

// Myパートナーのステータスを作成中に設定
$rec['ppj_status'] = MPS_REC_JUNBI;
db_update('t_mp_pjt', $rec, "ppj_pjt_id=$pjt_id");

// アンケートのステータスを作成中に設定
$sql = "SELECT ppj_enquete_id FROM t_mp_pjt WHERE ppj_pjt_id=$pjt_id";
$enquete_id = db_fetch1($sql);
if ($enquete_id) {
	$rec['en_status'] = 0;
	db_update('t_enquete', $rec, "en_enquete_id=$enquete_id");
}

db_commit_trans();
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
			<p class="msg">リクルートジョブの承認を取り消しました。</p>
			<p><input type="button" value="　戻る　" onclick="location.href='r_job.php?pjt_id=<?=$pjt_id?>'"></p>
		</td>
	</tr>
</table>

<? menu_end() ?>
<? page_footer() ?>
</body>
</html>
