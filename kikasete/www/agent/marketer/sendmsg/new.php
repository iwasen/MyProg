<?
/******************************************************
' System :きかせて・netエージェント用ページ
' Content:メッセージ送信・履歴の閲覧・削除
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/define.php");

//BCC情報表示
function ag_bcc_tok() {
	global $agent_id;
	$sql = "SELECT ag_bcc_addr FROM t_agent WHERE ag_agent_id=$agent_id";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$ag_bcc = strtok($fetch->ag_bcc_addr, ",");
		while($ag_bcc) {
			echo "$ag_bcc\n";
			$ag_bcc = strtok(",");
		}
	}
}

//メイン処理

set_global('マーケターへのメッセージ送信', BACK_TOP);

$sql = "SELECT mr_marketer_id,mr_name1,mr_name2,mr_mail_addr FROM t_marketer LEFT JOIN t_agent ON  mr_agent_id=ag_agent_id WHERE mr_agent_id=$agent_id AND mr_status=0 ORDER BY mr_marketer_id";
$result = db_exec($sql);
$nrow = pg_numrows($result);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
function onload_body() {
	ShowHide('kind', document.form1.to_chk[1].checked ? 'choice' : 'all');
}
function  ShowHide(id, show) {
	var disp = document.all(id).style.display;
	if (show == 'all')
		document.all(id).style.display = "none";
	else
	if (show == 'choice')
		document.all(id).style.display = "block";
}
function all_check(name, check) {
	var f = document.form1;
	if (f[name]) {
		if (f[name].length) {
			for (var i = 0; i < f[name].length; i++)
				f[name][i].checked = check;
		} else
			f[name].checked = check;
	}
}
function bcc_set(top) {
  var win;
  win = window.open(top + "/sub/bcc_set.php", "bcc_set", "scrollbars=no,resizable=no,width=400,height=250");
  win.focus();
}
function onSubmit_form1(f) {
	if (f.subject.value == "") {
		alert("タイトルを入力してください。");
		f.subject.focus();
		return false;
	}
	if (f.body_org.value == "") {
		alert("本文を入力してください。");
		f.body_org.focus();
		return false;
	}
	switch (f.next_action.value) {
	case "test":
		return confirm("メッセージをテスト送信します。よろしいですか？");
	case "send":
		return confirm("メッセージを送信します。よろしいですか？");
	}
	return false;
}
//-->
</script>
<? menu_script() ?>
</head>
<body onLoad="onload_body()">

<? page_header() ?>
<? menu_start() ?>

<form method="post" name="form1" action="update.php" onSubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=0 cellpadding=0 width="95%">
	<tr>
		<td>
			<table border=0 cellspacing=2 cellpadding=3 width=700>
				<tr>
					<td class="m4" align="center"><?=$g_title?></td>
				</tr>
			</table><br>
			<table border=0 cellspacing=2 cellpadding=3 width=700>
				<tr>
					<td class="m1" width=140>メッセージタイトル<?=MUST_ITEM?></td>
					<td class="n1">
						<input class="kanji" type="text" name="subject" size=70 maxlength=150>
						<span class="note">（全角１５０文字まで）</span>
					</td>
				</tr>
				<tr>
					<td class="m1">送信先<?=MUST_ITEM?></td>
					<td class="n1">
						<input type="radio" name="to_chk" onClick="ShowHide('kind', 'all')" value="all"><font size=-1>担当マーケター全員</font>
						<input type="radio" name="to_chk" onClick="ShowHide('kind', 'choice')" value="choice"><font size=-1>指定する</font>
						<span ID="kind" style="display:none;">
							<font class="note">※送信するマーケターを選択して下さい。　　　</font>
							<input type="button" value="全選択" onClick="all_check('to_ary[]', true)">
							<input type="button" value="全解除" onClick="all_check('to_ary[]', false)">
							<br>
							<table border=0 cellspacing=0 cellpadding=0 style="margin-left:10">
								<tr>
									<td bgcolor="#ffe4e1">
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	print "<input type='checkbox' name='to_ary[]' value='$fetch->mr_marketer_id'><font size=-1>$fetch->mr_marketer_id:$fetch->mr_name1 $fetch->mr_name2 ($fetch->mr_mail_addr)</font><br>";
}
?>
									</td>
								<tr>
							</table>
						</span>
					</td>
				</tr>
				<tr>
					<td class="m1">ＢＣＣ</td>
					<td class="n1">
						<textarea COLS=50 ROWS=3 name="ag_bcc_addr"><?=ag_bcc_tok()?></textarea>
						<input type="button" value="設定変更" OnClick='JavaScript:bcc_set("<?=$top?>")'>
					</td>
				</tr>
				<tr>
					<td class="m1">本文<?=MUST_ITEM?></td>
					<td class="n1">
						<span class="note">※差込み文字が使えます。<br>
							　（氏名= %MARKETER_NAME%、企業名= %KIGYOU_NAME%、部署名= %BUSHO_NAME%）
						</span><br>
						<textarea class="kanji" name="body_org" cols=70 rows=30></textarea>
					</td>
				</tr>
			</table>
			<table width=700>
				<div align="center">
				<input type="hidden" name="agent_id" <?=value($agent_id)?>>
				<input type="hidden" name="next_action">
				<input type="submit" value="テスト送信" onClick="document.form1.next_action.value='test'">
				<input type="submit" value="　送信　" onClick="document.form1.next_action.value='send'">
				<input type="button" value="　戻る　" onClick="history.back()">
				</div>
			</table>
		</td>
	</tr>
</table>
</form>

<? menu_end() ?>
<? page_footer() ?>

</body>
</html>
