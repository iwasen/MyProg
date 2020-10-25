<?
/******************************************************
' System :きかせて・net事務局・エージェント用ページ
' Content:Myパートナー・メール文詳細
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/icp_db.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

//メイン処理
set_global2('mypartner', 'Myパートナー管理', 'メール文詳細', BACK_CLOSE);

$sql = "SELECT rm_subject,rm_content,rm_date FROM l_remark WHERE rm_remark_id=$remark_id";
$result = icp_db_exec($sql);
if (pg_numrows($result))
	$fetch = pg_fetch_object($result, 0);
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

<form method="post" name="form1">
<table border=0 cellspacing=0 cellpadding=0>
	<tr>
		<td>
			<table border=0 cellspacing=2 cellpadding=3 width=700>
				<tr>
					<td class="m4" align="center">メール文詳細</td>
				</tr>
			</table>
			<table border=0 cellspacing=2 cellpadding=3 width=700>
				<tr>
					<td class="m0" colspan=2 align="left">■メール内容</td>
				</tr>
				<tr>
					<td class="m1">配信開始日時</td>
					<td class="n1"><?=mb_convert_kana(get_datepart('M', $fetch->rm_date), 'N')?>月<?=mb_convert_kana(get_datepart('D', $fetch->rm_date), 'N')?>日<?=mb_convert_kana(get_datepart('h', $fetch->rm_date), 'N')?>時
					</td>
				</tr>
				<tr>
					<td class="m1">なげかけタイトル</td>
					<td class="n1"><?=htmlspecialchars($fetch->rm_subject)?></td>
				</tr>
				<tr>
					<td class="m1" width=140>本文</td>
					<td class="n1" height=200><pre><?=htmlspecialchars($fetch->rm_content)?></pre></td>
				</tr>
			</table><br>
			<div align="center">
				<input type="button" value="閉じる" onclick="window.close()">
			</div>
		</td>
	</tr>
</table>
</form>

<? menu_end() ?>
<? page_footer() ?>

</body>
</html>
