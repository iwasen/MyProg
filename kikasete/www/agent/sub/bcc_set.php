<?
/******************************************************
' System :きかせて・netエージェント用ページ
' Content:エージェントＢＣＣ設定
'******************************************************/

$top = '..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/define.php");

//window_size 400:250

//BCC情報表示
function ag_bcc_tok() {
	global $agent_id;
	$sql = "SELECT ag_bcc_addr FROM t_agent WHERE ag_agent_id=$agent_id";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$ag_bcc = strtok($fetch->ag_bcc_addr, ",");
		while($ag_bcc) {
			echo "$ag_bcc\r\n";
			$ag_bcc = strtok(",");
		}
	}
}

//更新処理
if ($update) {
	$ag_bcc_addr = str_replace("\r\n",',',trim($ag_bcc_addr));
	$sql = "UPDATE t_agent SET ag_bcc_addr='$ag_bcc_addr' WHERE ag_agent_id=$agent_id";
	db_exec($sql);
}

//メイン処理
set_global('ＢＣＣ設定情報','', 1);

?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<SCRIPT LANGUAGE=javascript>
<!--
function onSubmit_form1() {
	var s;
	s = document.form1.ag_bcc_addr.value;
	if (confirm("登録情報を更新します。よろしいですか？")) {
		opener.document.form1.ag_bcc_addr.value = s;
		window.close();
	} else {
		document.form1.ag_bcc_addr.focus();
		return false;
	}
}
//-->
</SCRIPT>
</head>
<body>
<form method="post" name="form1" onsubmit="return onSubmit_form1()">
	<center>
		<table width='350'>
			<tr>
				<td align=right valign=bottom norap><small><font color=blue>■</font><A href='JavaScript:window.close()'>閉じる</A></small></td>
				</td>
			</tr>
		</table>
		<table width='350' <?=LIST_TABLE?>>
			<tr>
				<td class="m3s">BCC登録情報</td>
			</tr>
			<tr>
				<td width="35%" class="n3">
					<textarea cols=50 rows=8 name="ag_bcc_addr"><?=ag_bcc_tok()?></textarea>
					<div class="note">※メールアドレスを改行又はカンマ区切りで入力してください。</div>
				</td>
			</tr>
		</table>
	<input type="submit" name="update" value="　更新　">
	<input type="button" value="　戻る　" onclick="window.close()">
</center>
</form>
</body>
</html>