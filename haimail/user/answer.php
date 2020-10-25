<?
include('inc/database.php');

define('ANS_START', '------�ʲ����Ϥ��β��ιԤ����------');
define('ANS_END', '------�ʲ����Ϥ��ξ�ιԤޤǡ�------');

//=== ��å�����ɽ�� ===
function ShowMessage() {
	global $_member_id;

	switch ($_member_id) {
	case '%MEMBER_ID%':
		ShowMessage1();
		break;
	case '0':
		ShowMessage1();
		break;
	default:
		ShowMessage3();
		break;
	}
}

function ShowMessage1() {
	global $kind;

	$answer = get_answer();

	$message = "${kind}�Υƥ���������̤Ǥ�������ǧ����������";

	Writeln("<font color='#2f2180'><b>$message</b></font><br><br>");
	Writeln("<table width='100%' border='0' cellspacing='2' cellpadding='0'>");

	if (is_array($answer)) {
		foreach ($answer as $name => $values) {
			$str = join(', ', $values);
			Writeln("<tr>");
			Writeln("<td width='144' bgcolor='#b2cfef' align='left' valign='middle'><font size='2'>��" . ToZenkaku($name) . "</font></td>");
			Writeln("<td width='433' bgcolor='#fff6ea'><font size='2'>" . htmlspecialchars($str) . "</font></td>");
			Writeln("</tr>");
		}
	}

	Writeln("</table>");
}

function ShowMessage2() {
	global $kind, $_answer, $_mail_id;

	$answer = get_answer();

	$message = "${kind}�Υƥ���������̤Ǥ�������ǧ����������";

	Writeln("<font color='#2f2180'><b>$message</b></font><br><br>");
	Writeln("<table width='100%' border='0' cellspacing='2' cellpadding='0'>");

	$sql = "SELECT * FROM T_QUESTION WHERE QU_mail_id='$_mail_id' AND QU_question_type='$_answer' ORDER BY QU_question_no";
	$ds = CreateDynaset($sql, ORADYN_READONLY);
	while (!$ds->EOF()) {
		$name = 'Q' . $ds->GetValue('QU_question_no');
		if (is_array($answer[$name])) {
			$str = join(', ', $answer[$name]);
			if ($str == '')
				$str = "[̵����]";

			Writeln("<tr>");
			Writeln("<td width='30%' bgcolor='#b2cfef' align='left' valign='middle'><font size='2'><nobr>" . nl2br(htmlspecialchars($ds->GetValue('QU_question_text'))) . "</nobr></font></td>");
			Writeln("<td bgcolor='#fff6ea'><font size='2'><nobr>" . htmlspecialchars($str) . "</nobr></font></td>");
			Writeln("</tr>");
		}
		$ds->MoveNext();
	}

	Writeln("</table>");
}

function ShowMessage3() {
	global $kind;

	if (!LimitCheck())
		$message = "����������ޤ��󤬡��������¤�᤮�Ƥ��뤿�ᡢ<br>${kind}�μ����դ��ϤǤ��ޤ���Ǥ�����";
	elseif (AlreadyCheck())
		$message = "${kind}�Τ������Ϥ��Ǥ˼����դ��Ƥ���ޤ������꤬�Ȥ��������ޤ�����";
	elseif (CheckAnswer())
		$message = "${kind}�ˤ������������������꤬�Ȥ��������ޤ�����";
	else
		$message = "${kind}����˲�������Ƥ��ʤ����ܤ�����褦�Ǥ���<br>�������������줿�塢�����������Ƥ���������";

	Writeln("<font color='#2f2180'><b>$message</b></font>");
}

// �����ǡ�������
function get_answer() {
	global $HTTP_RAW_POST_DATA, $QUERY_STRING;

	if ($HTTP_RAW_POST_DATA != '')
		$raw_data = $HTTP_RAW_POST_DATA;
	elseif ($QUERY_STRING != '')
		$raw_data = $QUERY_STRING;

	$ary = explode('&', $raw_data);
	if (is_array($ary)) {
		foreach ($ary as $data) {
			if ($data{0} == 'Q') {
				$eq = strpos($data, '=');
				if ($eq > 0) {
					$name = urldecode(substr($data, 0, $eq));
					$value = mb_convert_encoding(urldecode(substr($data, $eq + 1)), 'EUC-JP', 'ISO-2022-JP,SJIS,EUC-JP');
					$answer[$name][] = $value;
				}
			}
		}
	}
	return $answer;
}

//=== �������¥����å� ===
function LimitCheck() {
	global $_answer, $_mail_id;

	if ($_answer == 'E')
		$sql = "SM_limit_date";
	elseif ($_answer == 'I')
		$sql = 'SM_imp_limit';
	else
		return false;

	$sql = "SELECT SM_mail_id FROM T_SEND_MAIL WHERE SM_mail_id='$_mail_id' AND ($sql IS NULL OR $sql>=SYSDATE)";
	$ds = CreateDynaset($sql, ORADYN_READONLY);
	if (!$ds->EOF())
		return true;

	return false;
}

//=== �����Ѥߥ����å� ===
function AlreadyCheck() {
	global $_mail_id, $_member_id, $_answer;

	$sql = "SELECT HA_mail_id FROM T_HTML_ANSWER WHERE HA_mail_id='$_mail_id' AND HA_member_id='$_member_id' AND HA_kind='$_answer'";
	$ds = CreateDynaset($sql, ORADYN_READONLY);
	if (!$ds->EOF())
		return true;

	if ($_answer == 'E')
		$cond = "AND RM_enq_valid='1'";
	elseif ($_answer == 'I')
		$cond = "AND RM_imp_valid='1'";

	$sql = "SELECT RM_mail_id FROM T_RECV_MAIL WHERE RM_mail_id='$_mail_id' AND RM_member_id='$_member_id' $cond";
	$ds = CreateDynaset($sql, ORADYN_READONLY);
	if (!$ds->EOF())
		return true;

	return false;
}

//=== �������ƥ����å� ===
function CheckAnswer() {
	global $_mail_id, $_member_id, $_answer;

	$answer = get_answer();

	$text = '';
	$valid = false;

	$sql = "SELECT * FROM T_QUESTION WHERE QU_mail_id='$_mail_id' AND QU_question_type='$_answer' ORDER BY QU_question_no";
	$ds = CreateDynaset($sql, ORADYN_READONLY);
	while (!$ds->EOF()) {
		$name = 'Q' . $ds->GetValue('QU_question_no');
		$str = '����' . $ds->GetValue('QU_question_no') . '��';
		if (is_array($answer[$name])) {
			$sel_num = $ds->GetValue('QU_question_sel');
			$fa_num = $ds->GetValue('QU_question_fa');
			$sel_count = 0;
			$fa_count = 0;
			foreach ($answer[$name] as $value) {
				if ($sel_count < $sel_num && SelCheck($value, $sel_num)) {
					$str .= "��${value}��";
					$sel_count++;
					$valid = true;
				} elseif ($fa_count < $fa_num) {
					$str .= "��${value}��";
					$fa_count++;
					if ($value != '')
						$valid = true;
				} else {
					$str .= "\r\n" . ANS_START . "\r\n" . $value . "\r\n" . ANS_END;
					if ($value != '')
						$valid = true;
				}
			}
		}
		$text .= "$str\r\n";
		$ds->MoveNext();
	}

	if ($valid) {
		$sql = "INSERT INTO T_HTML_ANSWER VALUES('$_mail_id','$_member_id','$_answer','" . addslashes ($text) . "',SYSDATE)";
		ExecuteSQL($sql);
		return true;
	} else
		return false;
}

//=== �������������å� ===
function SelCheck($value, $sel_num) {
	if (is_numeric($value)) {
		if ($value >= 1 && $value <= $sel_num)
			return true;
	}
	return false;
}

//=== �����Ѵ� ===
function ToZenkaku($str) {
	return mb_convert_kana($str, 'A');
}

//=== ���Խ��� ===
function Writeln($str) {
	echo $str, "\n";
}

// ������쥯��
function redirect($url) {
	header("location: $url");
	exit;
}

//=== �ᥤ����� ===
if ($_answer == '' || $_member_id == '' || $_mail_id == '')
	redirect('index.php');

switch ($_answer) {
case 'E':
	$kind = '���󥱡���';
	break;
case 'I':
	$kind = '����ʸ';
	break;
}
?>
<HTML>
<HEAD>
<META HTTP-EQUIV="content-type" CONTENT="text/html;charset=EUC-JP">
<TITLE>�Ϥ��᡼�롦�������������դ�</TITLE>
</HEAD>
<BODY BGCOLOR="white" LEFTMARGIN="0" TOPMARGIN="0" MARGINWIDTH="0" MARGINHEIGHT="0">
<A NAME="top"></A>
<TABLE CELLSPACING="0" CELLPADDING="0" BORDER="0">
	<TR HEIGHT="45">
		<TD WIDTH="202" ROWSPAN="2" BGCOLOR="#2f2180" ALIGN="left" VALIGN="top">
			<DIV ALIGN="right">
				<IMG HEIGHT="111" WIDTH="202" SRC="img/title_left.gif" ALT="�Ϥ��᡼�롦net"></DIV>
		</TD>
		<TD HEIGHT="45" VALIGN="bottom" BGCOLOR="#2f2180" ALIGN="left" WIDTH="536">
			<TABLE BORDER="0" CELLSPACING="0" BGCOLOR="#2f2180" HEIGHT="43">
				<FORM NAME="login" ACTION="8/index.php" METHOD="post">
				<TR HEIGHT="27">
					<TD WIDTH="58" ALIGN="left" VALIGN="middle" HEIGHT="27"><IMG SRC="img/mypage.gif" WIDTH="58" HEIGHT="22"></TD>
					<TD WIDTH="67" HEIGHT="27" ALIGN="right" VALIGN="middle"><IMG SRC="img/mailaddress.gif" WIDTH="65" HEIGHT="12"></TD>
					<TD WIDTH="86" HEIGHT="27"><INPUT TYPE="text" NAME="mail_adr" SIZE="23"></TD>
					<TD HEIGHT="27" WIDTH="55" ALIGN="right" VALIGN="middle"><IMG SRC="img/password.gif" WIDTH="49" HEIGHT="12"> </TD>
					<TD HEIGHT="27" WIDTH="61" ALIGN="left" VALIGN="middle"><INPUT TYPE="password" NAME="password" SIZE="10"></TD>
					<TD WIDTH="62" HEIGHT="27" ALIGN="center" VALIGN="middle"><INPUT TYPE="submit" VALUE="������" NAME="login"></TD>
				</TR>
				<TR>
					<TD WIDTH="58"></TD>
					<TD WIDTH="67"></TD>
					<TD COLSPAN="3" ALIGN="right" VALIGN="middle"><A HREF="9/9-1.php"><IMG SRC="img/password_right.gif" WIDTH="185" HEIGHT="14" BORDER=0></A></TD>
					<TD WIDTH="62"></TD>
				</TR>
				</FORM>
			</TABLE>
		</TD>
	</TR>
	<TR>
		<TD BGCOLOR="#5d85b8" ALIGN="left" VALIGN="bottom" WIDTH="536"><IMG SRC="img/head.jpg" WIDTH="535" HEIGHT="66" ALT="interactive communication network"></TD>
	</TR>
	<TR HEIGHT="18">
		<TD COLSPAN="2" BGCOLOR="#3f659e" HEIGHT="18" ALIGN="left" VALIGN="middle"><IMG HEIGHT="16" WIDTH="14" SRC="img/space.gif"><A HREF="index.php"><IMG SRC="img/home.gif" WIDTH="53" HEIGHT="16" BORDER="0" ALT="HOME"></A><A HREF="2/index.html"><IMG SRC="img/himail_net.gif" WIDTH="130" HEIGHT="16" BORDER="0" ALT="�Ϥ��᡼�롦net�äơ�"></A><A HREF="3/index.html"><IMG SRC="img/promise.gif" WIDTH="44" HEIGHT="16" BORDER="0" ALT="����«"></A><A HREF="4/index.html"><IMG SRC="img/policy.gif" WIDTH="119" HEIGHT="16" BORDER="0" ALT="�ץ饤�Х����ݥꥷ��"></A><A HREF="5/index.html"><IMG SRC="img/faq.gif" WIDTH="42" HEIGHT="16" BORDER="0" ALT="FAQ"></A><A HREF="6/index.php"><IMG SRC="img/use_business.gif" WIDTH="108" HEIGHT="16" BORDER="0" ALT="�ӥ��ͥ������Ѥ���"></A><A HREF="7/index.html"><IMG SRC="img/use_personal.gif" WIDTH="88" HEIGHT="16" BORDER="0" ALT="�Ŀͤ����Ѥ���"></A></TD>
	</TR>
</TABLE>
<br><br>
<table border="0" cellspacing="0" width="605" cellpadding="0">
	<tr>
		<td width="25"></td>
		<td width="800">
<? ShowMessage() ?>
			<form>
				<input type="button" value="���Ĥ��롡" onclick="window.close()">
			</form>
		</td>
	</tr>
</table>
</BODY>
</HTML>
