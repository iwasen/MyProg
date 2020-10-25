<?
/******************************************************
' System :きかせて・netエージェント用ページ
' Content:送信メールコンテンツ表示
'******************************************************/

$top = '../../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/enquete.php");
include("$inc/enq_mail.php");

//メイン処理
set_global2('project', 'プロジェクト管理', '質問文テンプレート表示', BACK_TOP);

$sql = "SELECT pq_title,pq_enquete_id FROM m_pjt_question WHERE pq_seq_no=$pq_no";
$result = db_exec($sql);
$fetch = pg_fetch_object($result, 0);

$body = "%ENQUETE%";

$enquete = new enquete_class;
$enquete->read_db($fetch->pq_enquete_id);
$jb_body = make_enq_mail($enquete, $header, $body, $footer);

?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<? menu_script() ?>
</head>
<body>
<center>
<table border=0 cellspacing=1 cellpadding=0>
	<tr>
		<td class="m5">■<?=htmlspecialchars($fetch->pq_title)?></td>
	</tr>
	<tr>
		<td colspan=2>
			<table border=1 cellspacing=0 cellpadding=3 bordercolor="navy" width="100%">
				<tr>
					<td><xmp><?=$jb_body?></xmp>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</center>

<center>
<input type="button" value=" 閉じる " onclick="window.close()">
</center>
</body>
</html>
