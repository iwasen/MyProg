<?
/******************************************************
' System :�֤���������� �������������ӥ��׻�̳���ѥڡ���
' Content:��̳�ɥڡ������̥���å���������Ͽ
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");
include("$inc/select.php");

// ����å���������URL����
function ClickCountURL($cc_no) {
	$url = get_system_info('url_click_counter');
	return "{$url}?%CTID{$cc_no}%-%UID%";
}
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/center.css">
<script type="text/javascript">
<!--
function SetDate() {
	var f = document.form1;
	var start_date, end_date

	if (f.cc_start_year.value != "")
		start_date = f.cc_start_year.value + "/" + f.cc_start_month.value + "/" + f.cc_start_day.value
	else
		start_date = "";

	if (f.cc_end_year.value != "")
		end_date = f.cc_end_year.value + "/" + f.cc_end_month.value + "/" + f.cc_end_day.value
	else
		end_date = "";

	f = opener.document.form1;
	f.cc_start_date.value = start_date;
	f.cc_end_date.value = end_date;
}

function SetURL() {
	var f = document.form1;
	var f2 = opener.document.form1;
	var line = Number(f.line.value);
	var body = f.body.value;
	var cc_no = f2.cc_no.value;
	var cc_url_name = f2.cc_url_name.value;
	var cc_jump_url = f2.cc_jump_url.value;
	var cc_count = Number(f2.cc_count.value);

	for (var i = 1; i <= line; i++) {
		if (f("cc_no" + i)) {
			if (f("cc_no" + i).checked) {
				if (cc_no != "") {
					cc_no += "||";
					cc_url_name += "||";
					cc_jump_url += "||";
				}
				cc_no += f("cc_no" + i).value;
				cc_url_name += f("cc_url_name" + i).value;
				cc_jump_url += f("cc_jump_url" + i).value;
				body = body.replace(f("cc_jump_url" + i).value, f("click_url" + i).value);
			}
			cc_count++;
		}
	}
	f2.body.value = body;
	f2.cc_no.value = cc_no;
	f2.cc_url_name.value = cc_url_name;
	f2.cc_jump_url.value = cc_jump_url;
	f2.cc_count.value = cc_count;
}
function OnClick_OK() {
	SetDate();
	SetURL();
	window.close();
}
//-->
</SCRIPT>
</head>
<body>

<? center_header('����å���������Ͽ') ?>

<form name="form1">
<center>
<table border=0 cellpadding=2 cellspacing=3 width="100%">
	<tr>
		<td class="m0" colspan=2>������å�����������</td>
		</td>
	</tr>
	<tr>
		<td class="m1" width=15%>�����󥿴���(����)</td>
		<td class="n1">
			<select name="cc_start_year"><? select_year(date(Y),' ','')?></select>ǯ
			<select name="cc_start_month"><? select_month(' ','')?></select>��
			<select name="cc_start_day"><? select_day(' ','')?></select>��
			<select name="cc_start_hour"><? select_hour(' ','')?></select>��
			<span class="note">�ʳ��Ϥ���ꤷ�ʤ�����ǯ��֥�󥯤ˤ��Ƥ���������</span>
		</td>
	</tr>
	<tr>
		<td class="m1">�����󥿴���(��λ)</td>
		<td class="n1">
			<select name="cc_end_year"><? select_year(date(Y),' ','')?></select>ǯ
			<select name="cc_end_month"><? select_month(' ','')?></select>��
			<select name="cc_end_day"><? select_day(' ','')?></select>��
			<select name="cc_end_hour"><? select_hour(' ','')?></select>��
			<span class="note">�ʽ�λ����ꤷ�ʤ�����ǯ��֥�󥯤ˤ��Ƥ���������</span>
		</td>
	</tr>
	<tr>
		<td><br></td>
	</tr>
</table>
<table <?=LIST_TABLE?> width=100%>
	<tr bgcolor="#E0FFE0">
		<th>�ֹ�</th>
		<th>URL̾��</th>
		<th>������URL</th>
		<th>����URL</th>
		<th>����</th>
	</tr>
<?
$i = 0;
$count = 1 + $_POST['cc_count'];
$pieces = explode("http", $_POST["body"]);
foreach ($pieces as $key => $val) {
	if ($key && (substr($val, 0, 3) == '://' || substr($val, 0, 4) == 's://')) {
		$val = strtok($val,"\n");
		$val = strtok($val," ");
		$val = strtok($val,"��");
		$val = strtok($val,'"');
		$val = strtok($val,"'");
		if (strpos ($val, "%UID%"))
			$count = $count - 1;
		else {
			$cc_jump_url = "http" . trim($val);
			$url = ClickCountURL($count);
?>
	<tr class="tc<?=$i % 2?>">
		<td align="center"><?=$count?></td>
		<td><input type="text" name="cc_url_name<?=$count?>" size=50 maxlength=50 value="URL-<?=$count?>"></td>
		<td>
			<input type="hidden" name="cc_jump_url<?=$count?>" <?=value($cc_jump_url)?>>
			<a href="<?=$cc_jump_url?>"  target='_blank'><?=htmlspecialchars($cc_jump_url)?></a>
		</td>
		<td><input type="hidden" name="click_url<?=$count?>" <?=value($url)?>><?=$url?></td>
		<td align="center"><input type="checkbox" name="cc_no<?=$count?>" <?=value($count)?> checked></td>
	</tr>
<?
		}
		$i++;
		$count++;
	}
}
?>
</table>
<br>
<input type="hidden" name="line" value="<?=$count?>">
<textarea name="body" style="display:none"><?=$_POST["body"]?></textarea>
<input type="button" value="��OK��" onclick="OnClick_OK()">
<input type="button" value="����󥻥�" onclick="window.close()">
</center>
</form>

<? center_footer() ?>

</body>
</html>
