<?
/******************************************************
' System :�������ơ�net������������ѥڡ���
' Content:��å���������������α��������
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/define.php");

//BCC����ɽ��
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

//�ᥤ�����

set_global('�ޡ��������ؤΥ�å���������', BACK_TOP);

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
		alert("�����ȥ�����Ϥ��Ƥ���������");
		f.subject.focus();
		return false;
	}
	if (f.body_org.value == "") {
		alert("��ʸ�����Ϥ��Ƥ���������");
		f.body_org.focus();
		return false;
	}
	switch (f.next_action.value) {
	case "test":
		return confirm("��å�������ƥ����������ޤ���������Ǥ�����");
	case "send":
		return confirm("��å��������������ޤ���������Ǥ�����");
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
					<td class="m1" width=140>��å����������ȥ�<?=MUST_ITEM?></td>
					<td class="n1">
						<input class="kanji" type="text" name="subject" size=70 maxlength=150>
						<span class="note">�����ѣ�����ʸ���ޤǡ�</span>
					</td>
				</tr>
				<tr>
					<td class="m1">������<?=MUST_ITEM?></td>
					<td class="n1">
						<input type="radio" name="to_chk" onClick="ShowHide('kind', 'all')" value="all"><font size=-1>ô���ޡ�����������</font>
						<input type="radio" name="to_chk" onClick="ShowHide('kind', 'choice')" value="choice"><font size=-1>���ꤹ��</font>
						<span ID="kind" style="display:none;">
							<font class="note">����������ޡ������������򤷤Ʋ�������������</font>
							<input type="button" value="������" onClick="all_check('to_ary[]', true)">
							<input type="button" value="�����" onClick="all_check('to_ary[]', false)">
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
					<td class="m1">�£ã�</td>
					<td class="n1">
						<textarea COLS=50 ROWS=3 name="ag_bcc_addr"><?=ag_bcc_tok()?></textarea>
						<input type="button" value="�����ѹ�" OnClick='JavaScript:bcc_set("<?=$top?>")'>
					</td>
				</tr>
				<tr>
					<td class="m1">��ʸ<?=MUST_ITEM?></td>
					<td class="n1">
						<span class="note">��������ʸ�����Ȥ��ޤ���<br>
							���ʻ�̾= %MARKETER_NAME%�����̾= %KIGYOU_NAME%������̾= %BUSHO_NAME%��
						</span><br>
						<textarea class="kanji" name="body_org" cols=70 rows=30></textarea>
					</td>
				</tr>
			</table>
			<table width=700>
				<div align="center">
				<input type="hidden" name="agent_id" <?=value($agent_id)?>>
				<input type="hidden" name="next_action">
				<input type="submit" value="�ƥ�������" onClick="document.form1.next_action.value='test'">
				<input type="submit" value="��������" onClick="document.form1.next_action.value='send'">
				<input type="button" value="����롡" onClick="history.back()">
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
