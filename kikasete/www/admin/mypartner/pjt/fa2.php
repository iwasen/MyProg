<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:Myパートナー・該当者選定
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");

//メイン処理
set_global2('mypartner', 'Myパートナー管理', 'Myパートナー　該当者選定', BACK_TOP);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--

//-->
</script>
<? menu_script() ?>
</head>
<body>

<? page_header() ?>
<? menu_start() ?>

<form method="post" name="form1">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">■○○についての会議室（R承認済み）（999）</td>
	</tr>
</table>

</form>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onsubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m0" colspan=2>■集計結果</td>
	</tr>
	<tr>
		<td class="n1">
			<table border="1" cellspacing="0" cellpadding="0" bgcolor="#ffffff" bordercolor="#c0c0c0" bordercolorlight="#c0c0c0" bordercolordark="#c0c0c0" frame="box">
				<tr>
					<td align="center" colspan=4>
						<table>
							<tr>
								<td width="100" align="right" valign="top">
								<font size="2" class="honbun2">Ｑ１(質問文)　：　</font>
								</td>
								<td width="550">
								<font size="2" class="honbun2">今のあなたのマイブームはなんですか？<br />
どんな事でも結構ですのでご自由にお答えください。<br />
複数ある方は箇条書きでお書きください。</font>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<th width="6%"><font size="2" class="honbun2"><nobr>回答受付連番</nobr></font></th>
					<th width="6%"><font size="2" class="honbun2"><nobr>性別</nobr></font></th>
					<th><font size="2" class="honbun2"><nobr>回答</nobr></font></th>
				</tr>
			<tr>
				<td align="center"><font size="2" class="honbun2">1</font></td>
				<td align="center"><font size="2" class="honbun2">男性</font></td>
				<td><font size="2" class="honbun2">入浴剤、インテリア雑誌<br />
 <br />
 <br />
 　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　</font></td>
			</tr>
			</table>
		</td>
	</tr>
</table>
<br>
</form>
</div>

<? menu_end() ?>
<? page_footer() ?>

</body>
</html>
