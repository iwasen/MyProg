<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:My�ѡ��ȥʡ���My�ѡ��ȥʡ������ѹ�
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");

//�ᥤ�����
set_global2('mypartner', 'My�ѡ��ȥʡ�����', 'My�ѡ��ȥʡ������ѹ�', BACK_TOP);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
function onsubmit_form1(f) {
	return confirm("���Σͣ����󥱡��Ȥ򹹿����ޤ���������Ǥ�����");
}
//-->
</script>
<? menu_script() ?>
</head>
<body>

<? page_header() ?>
<? menu_start() ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onsubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m0" colspan=2>��������������</td>
	</tr>
	<tr>
		<td class="m1" width="20%">���󥱡��ȼ���</td>
		<td class="n1" width="80%">
			<input type="radio" name="enq_type" value='1'>�᡼�륢�󥱡���
			<input type="radio" name="enq_type" value='2' checked>�ף�⥢�󥱡���
		</td>
	</tr>
	<tr>
		<td class="m1">��������</td>
		<td class="n1">
			<input class="number" type="text" name="start_date_y" size="4" maxlength="4" value="2004">ǯ
			<input class="number" type="text" name="start_date_m" size="2" maxlength="2" value="4">��
			<input class="number" type="text" name="start_date_d" size="2" maxlength="2" value="22">��
		</td>
	</tr>
	<tr>
		<td class="m1">��λ����</td>
		<td class="n1">
			<input class="number" type="text" name="end_date_y" size="4" maxlength="4" value="2004">ǯ
			<input class="number" type="text" name="end_date_m" size="2" maxlength="2" value="4">��
			<input class="number" type="text" name="end_date_d" size="2" maxlength="2" value="26">��
			<input class="number" type="text" name="end_date_h" size="2" maxlength="2" value="17">��
		</td>
	</tr>
	<tr>
		<td class="m1">�ݥ���ȿ�</td>
		<td class="n1">
			<input class="number" type="text" name="point" size="3" maxlength="3" value="40">�ݥ����
		</td>
	</tr>
	<tr>
		<td class="m1">����ȯ����</td>
		<td class="n1">
			<input class="number" type="text" name="send_num" size="3" maxlength="3" value="300">��
		</td>
	</tr>
	<tr>
		<td class="m1">�»ܾ���</td>
		<td class="n1"><select name="status"><option value='5'>�»���</option><option value='6'>������</option><option value='7' selected>��λ</option><option value='9'>����Ѥ�</option></select></td>
	</tr>
	<tr>
		<td class="m0" colspan=2>�����󥱡�������</td>
	</tr>
	<tr>
		<td class="m1">���󥱡��ȥ����ȥ�</td>
		<td class="n1">
			<input class="kanji" type="text" name="title" size="50" maxlength="20" value="�֥���饯�����פ˴ؤ��륢�󥱡���">
		</td>
	</tr>
	<tr>
		<td class="m1">����ʸ</td>
		<td class="n1">
			<textarea class="kanji" rows="20" cols="70" name="description">����ϡ��֤��ʤ��ι����ʥ���饯�����פˤĤ��Ƥ�ʹ���������Ȼפ��ޤ���
�����ڻ��þ��ۤˤ��ƤϤޤ����ʤ�ɤʤ��Ǥ⤪�������������ޤ��Τǡ�
�����ϤΤۤɡ���������ꤤ���ޤ���

����������������������������������������������������
���������ڤꡧ��������ʷ�ˣ���������ɬ��
����������������������������������������������������

�ܨ����ڻ��þ��ۨ�����������������

������ �����С������Фν���
������ ���Υ��󥱡���URL���Τ餻�᡼���������줿���ܿͤ���


���󥱡��Ȥˤ����������������������ˡڣ����ݥ���ȡۤ��դ��������ޤ���

�����䥢�󥱡��ȳ��Ϣ㨬��������������������������������������������</textarea>
		</td>
	</tr>
	<tr>
		<td class="m1" rowspan=3>Q��</td>
		<td class="n1">
			<textarea rows="3" cols="70" name="question_text1">���ˤ����륭��饯�����Τ��������ʤ�����¸�ΤΥ���饯�����Ϥɤ�Ǥ������ΤäƤ��������Ƥ˥����å��򤷤Ƥ���������</textarea>
		<td>
	</tr>
	<tr>
		<td class="n1">
			<select name="question_type1">
				<option value='1'>ñ������(SA)
				<option value='2' selected>ʣ������(MA)
				<option value='3'>��ͳ����(FA)
			</select>
		</td>
	</tr>
	<tr>
		<td class="n1">
			<textarea name="" cols="50" rows="10"></textarea>
			<div class="small"><input type="checkbox" name="fa_flag1" value='t'>�Ǹ�������ǥե꡼���������</div>
		</td>
	</tr>
	<tr>
		<td class="m1" rowspan=3>Q��</td>
		<td class="n1">
			<textarea rows="3" cols="70" name="question_text2">���ˤ����륭��饯�����Τ��������ʤ��������ʥ���饯�����Ϥɤ�Ǥ����������ʤ�����Ƥ˥����å��򤷤Ƥ���������</textarea>
		</td>
	</tr>
	<tr>
		<td class="n1">
			<select name="question_type2">
				<option value='1'>ñ������(SA)
				<option value='2' selected>ʣ������(MA)
				<option value='3'>��ͳ����(FA)
			</select>
		</td>
	</tr>
	<tr>
		<td class="n1">
			<textarea name="" cols="50" rows="10"></textarea>
			<div class="small"><input type="checkbox" name="fa_flag1" value='t'>�Ǹ�������ǥե꡼���������</div>
		</td>
	</tr>
	<tr>
		<td class="m1" rowspan=3>Q��</td>
		<td class="n1">
			<textarea rows="3" cols="70" name="question_text3">���ˤ����륭��饯�����Τ��������ʤ������ֹ����ʥ���饯�����Ϥɤ�Ǥ����������ʤ�Σ��Ĥ����˥����å��򤷤Ƥ���������</textarea>
		</td>
	</tr>
	<tr>
		<td class="n1">
			<select name="question_type3">
				<option value='1' selected>ñ������(SA)
				<option value='2'>ʣ������(MA)
				<option value='3'>��ͳ����(FA)
			</select>
		</td>
	</tr>
	<tr>
		<td class="n1">
			<textarea name="" cols="50" rows="10"></textarea>
			<div class="small"><input type="checkbox" name="fa_flag1" value='t'>�Ǹ�������ǥե꡼���������</div>
		</td>
	</tr>
	<tr>
		<td class="m1" rowspan=3>Q��</td>
		<td class="n1">
			<textarea rows="3" cols="70" name="question_text4">���ˤ����륭��饯�����Τ��������ʤ��������ʥ���饯�����Ϥɤ�Ǥ����������ʤ�����Ƥ˥����å��򤷤Ƥ���������</textarea>
		</td>
	</tr>
	<tr>
		<td class="n1">
			<select name="question_type4">
				<option value='1'>ñ������(SA)
				<option value='2' selected>ʣ������(MA)
				<option value='3'>��ͳ����(FA)
			</select>
		</td>
	</tr>
	<tr>
		<td class="n1">
			<textarea name="" cols="50" rows="10"></textarea>
			<div class="small"><input type="checkbox" name="fa_flag1" value='t'>�Ǹ�������ǥե꡼���������</div>
		</td>
	</tr>
	<tr>
		<td class="m1" rowspan=3>Q��</td>
		<td class="n1">
			<textarea rows="3" cols="70" name="question_text5"></textarea>
		</td>
	</tr>
	<tr>
		<td class="n1">
			<select name="question_type5">
				<option value='1'>ñ������(SA)
				<option value='2'>ʣ������(MA)
				<option value='3'>��ͳ����(FA)
			</select>
		</td>
	</tr>
	<tr>
		<td class="n1">
			<textarea name="" cols="50" rows="10"></textarea>
			<div class="small"><input type="checkbox" name="fa_flag1" value='t'>�Ǹ�������ǥե꡼���������</div>
		</td>
	</tr>
	<tr>
		<td class="m1" rowspan=3>Q��</td>
		<td class="n1">
			<textarea rows="3" cols="70" name="question_text6"></textarea>
		</td>
	</tr>
	<tr>
		<td class="n1">
			<select name="question_type6">
				<option value='1'>ñ������(SA)
				<option value='2'>ʣ������(MA)
				<option value='3'>��ͳ����(FA)
			</select>
		</td>
	</tr>
	<tr>
		<td class="n1">
			<textarea name="" cols="50" rows="10"></textarea>
			<div class="small"><input type="checkbox" name="fa_flag1" value='t'>�Ǹ�������ǥե꡼���������</div>
		</td>
	</tr>
	<tr>
		<td class="m1" rowspan=3>Q��</td>
		<td class="n1">
			<textarea rows="3" cols="70" name="question_text7"></textarea>
		</td>
	</tr>
	<tr>
		<td class="n1">
			<select name="question_type7">
				<option value='1'>ñ������(SA)
				<option value='2'>ʣ������(MA)
				<option value='3'>��ͳ����(FA)
			</select>
		</td>
	</tr>
	<tr>
		<td class="n1">
			<textarea name="" cols="50" rows="10"></textarea>
			<div class="small"><input type="checkbox" name="fa_flag1" value='t'>�Ǹ�������ǥե꡼���������</div>
		</td>
	</tr>
	<tr>
		<td class="m1" rowspan=3>Q��</td>
		<td class="n1">
			<textarea rows="3" cols="70" name="question_text8"></textarea>
		</td>
	</tr>
	<tr>
		<td class="n1">
			<select name="question_type8">
				<option value='1'>ñ������(SA)
				<option value='2'>ʣ������(MA)
				<option value='3'>��ͳ����(FA)
			</select>
		</td>
	</tr>
	<tr>
		<td class="n1">
			<textarea name="" cols="50" rows="10"></textarea>
			<div class="small"><input type="checkbox" name="fa_flag1" value='t'>�Ǹ�������ǥե꡼���������</div>
		</td>
	</tr>
	<tr>
		<td class="m1" rowspan=3>Q��</td>
		<td class="n1">
			<textarea rows="3" cols="70" name="question_text9"></textarea>
		</td>
	</tr>
	<tr>
		<td class="n1">
			<select name="question_type9">
				<option value='1'>ñ������(SA)
				<option value='2'>ʣ������(MA)
				<option value='3'>��ͳ����(FA)
			</select>
		</td>
	</tr>
	<tr>
		<td class="n1">
			<textarea name="" cols="50" rows="10"></textarea>
			<div class="small"><input type="checkbox" name="fa_flag1" value='t'>�Ǹ�������ǥե꡼���������</div>
		</td>
	</tr>
	<tr>
		<td class="m1" rowspan=3>Q����</td>
		<td class="n1">
			<textarea rows="3" cols="70" name="question_text10"></textarea>
		</td>
	</tr>
	<tr>
		<td class="n1">
			<select name="question_type10">
				<option value='1'>ñ������(SA)
				<option value='2'>ʣ������(MA)
				<option value='3'>��ͳ����(FA)
			</select>
		</td>
	</tr>
	<tr>
		<td class="n1">
			<textarea name="" cols="50" rows="10"></textarea>
			<div class="small"><input type="checkbox" name="fa_flag1" value='t'>�Ǹ�������ǥե꡼���������</div>
		</td>
	</tr>
	<tr>
		<td class="m0" colspan=2>�������������</td>
	</tr>
	<tr>
		<td class="m1">����</td>
		<td class="n1">
			<input type="checkbox" name="sex[]" value='1'>����&nbsp;
			<input type="checkbox" name="sex[]" value='2' checked>����
		</td>
	</tr>
	<tr>
		<td class="m1">ǯ��</td>
		<td class="n1">
<table border=0 width="100%">
<tr>
<td width='10%'><input type='checkbox' name='age_cd[]' value='1'><font size=2 class='honbun2'>������̤��</font></td>
<td width='10%'><input type='checkbox' name='age_cd[]' value='2'><font size=2 class='honbun2'>��������Ⱦ</font></td>
<td width='10%'><input type='checkbox' name='age_cd[]' value='3'><font size=2 class='honbun2'>�������Ⱦ</font></td>
<td width='10%'><input type='checkbox' name='age_cd[]' value='4'><font size=2 class='honbun2'>��������Ⱦ</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='age_cd[]' value='5'><font size=2 class='honbun2'>�������Ⱦ</font></td>
<td width='10%'><input type='checkbox' name='age_cd[]' value='6' checked><font size=2 class='honbun2'>��������Ⱦ</font></td>
<td width='10%'><input type='checkbox' name='age_cd[]' value='7' checked><font size=2 class='honbun2'>�������Ⱦ</font></td>
<td width='10%'><input type='checkbox' name='age_cd[]' value='8'><font size=2 class='honbun2'>��������Ⱦ</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='age_cd[]' value='9'><font size=2 class='honbun2'>�������Ⱦ</font></td>
<td width='10%'><input type='checkbox' name='age_cd[]' value='10'><font size=2 class='honbun2'>��������Ⱦ</font></td>
<td width='10%'><input type='checkbox' name='age_cd[]' value='11'><font size=2 class='honbun2'>�������Ⱦ</font></td>
<td width='10%'><input type='checkbox' name='age_cd[]' value='12'><font size=2 class='honbun2'>��������Ⱦ</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='age_cd[]' value='13'><font size=2 class='honbun2'>�������Ⱦ</font></td>
<td width='10%'><input type='checkbox' name='age_cd[]' value='14'><font size=2 class='honbun2'>�����Ͱʾ�</font></td>
</tr>
</table>
		</td>
	</tr>
	<tr>
		<td class="m1">̤����</td>
		<td class="n1">
			<input type="checkbox" name="mikikon[]" value='1'>�ȿ�&nbsp;
			<input type="checkbox" name="mikikon[]" value='2'>����&nbsp;
			<input type="checkbox" name="mikikon[]" value='3'>����¾
		</td>
	</tr>
	<tr>
		<td class="m1">�ｻ�ϰ�</td>
		<td class="n1">
<table border=0 width="100%">
<tr>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='1'><font size=2 class='honbun2'>�̳�ƻ</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='2'><font size=2 class='honbun2'>�Ŀ���</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='3'><font size=2 class='honbun2'>��긩</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='4'><font size=2 class='honbun2'>�ܾ븩</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='5'><font size=2 class='honbun2'>���ĸ�</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='6'><font size=2 class='honbun2'>������</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='7'><font size=2 class='honbun2'>ʡ�縩</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='8'><font size=2 class='honbun2'>��븩</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='9'><font size=2 class='honbun2'>���ڸ�</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='10'><font size=2 class='honbun2'>���ϸ�</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='11' checked><font size=2 class='honbun2'>��̸�</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='12' checked><font size=2 class='honbun2'>���ո�</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='13' checked><font size=2 class='honbun2'>�����</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='14' checked><font size=2 class='honbun2'>�����</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='15'><font size=2 class='honbun2'>���㸩</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='16'><font size=2 class='honbun2'>�ٻ���</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='17'><font size=2 class='honbun2'>���</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='18'><font size=2 class='honbun2'>ʡ�温</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='19'><font size=2 class='honbun2'>������</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='20'><font size=2 class='honbun2'>Ĺ�</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='21'><font size=2 class='honbun2'>���츩</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='22'><font size=2 class='honbun2'>�Ų���</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='23'><font size=2 class='honbun2'>���θ�</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='24'><font size=2 class='honbun2'>���Ÿ�</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='25'><font size=2 class='honbun2'>���츩</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='26'><font size=2 class='honbun2'>������</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='27'><font size=2 class='honbun2'>�����</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='28'><font size=2 class='honbun2'>ʼ�˸�</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='29'><font size=2 class='honbun2'>���ɸ�</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='30'><font size=2 class='honbun2'>�²λ���</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='31'><font size=2 class='honbun2'>Ļ�踩</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='32'><font size=2 class='honbun2'>�纬��</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='33'><font size=2 class='honbun2'>������</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='34'><font size=2 class='honbun2'>���縩</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='35'><font size=2 class='honbun2'>������</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='36'><font size=2 class='honbun2'>���縩</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='37'><font size=2 class='honbun2'>���</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='38'><font size=2 class='honbun2'>��ɲ��</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='39'><font size=2 class='honbun2'>���θ�</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='40'><font size=2 class='honbun2'>ʡ����</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='41'><font size=2 class='honbun2'>���츩</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='42'><font size=2 class='honbun2'>Ĺ�긩</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='43'><font size=2 class='honbun2'>���ܸ�</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='44'><font size=2 class='honbun2'>��ʬ��</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='45'><font size=2 class='honbun2'>�ܺ긩</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='46'><font size=2 class='honbun2'>�����縩</font></td>
<td width='10%'><input type='checkbox' name='jitaku_area[]' value='47'><font size=2 class='honbun2'>���츩</font></td>
</tr>
</table>
		</td>
	</tr>
	<tr>
		<td class="m1">����</td>
		<td class="n1">
<table border=0 width="100%">
<tr>
<td width='10%'><input type='checkbox' name='shokugyou[]' value='1'><font size=2 class='honbun2'>��Ұ�</font></td>
<td width='10%'><input type='checkbox' name='shokugyou[]' value='2'><font size=2 class='honbun2'>��ҷбġ����</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='shokugyou[]' value='3'><font size=2 class='honbun2'>��̳��</font></td>
<td width='10%'><input type='checkbox' name='shokugyou[]' value='4'><font size=2 class='honbun2'>���Ķ�</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='shokugyou[]' value='5'><font size=2 class='honbun2'>�ѡ��ȡ�����Х���</font></td>
<td width='10%'><input type='checkbox' name='shokugyou[]' value='6'><font size=2 class='honbun2'>���翦���۸�Ρ���ա���׻�����</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='shokugyou[]' value='7'><font size=2 class='honbun2'>���翦��SOHO���ǥ����ʡ������ꥨ����������</font></td>
<td width='10%'><input type='checkbox' name='shokugyou[]' value='8'><font size=2 class='honbun2'>����</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='shokugyou[]' value='9'><font size=2 class='honbun2'>����</font></td>
<td width='10%'><input type='checkbox' name='shokugyou[]' value='10'><font size=2 class='honbun2'>��ȼ��ء�����</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='shokugyou[]' value='11'><font size=2 class='honbun2'>̵��</font></td>
</tr>
</table>
		</td>
	</tr>
	<tr>
		<td class="m1">����ӥ�</td>
		<td class="n1">
<table border=0 width="100%">
<tr>
<td width='10%'><input type='checkbox' name='conveni[]' value='1' checked><font size=2 class='honbun2'>���֥󥤥�֥�</font></td>
<td width='10%'><input type='checkbox' name='conveni[]' value='2'><font size=2 class='honbun2'>������</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='conveni[]' value='3'><font size=2 class='honbun2'>�ե��ߥ꡼�ޡ���</font></td>
<td width='10%'><input type='checkbox' name='conveni[]' value='4'><font size=2 class='honbun2'>���󥯥�</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='conveni[]' value='5'><font size=2 class='honbun2'>�ߥ˥��ȥå�</font></td>
<td width='10%'><input type='checkbox' name='conveni[]' value='6'><font size=2 class='honbun2'>am.pm.</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='conveni[]' value='7'><font size=2 class='honbun2'>��ޥ���</font></td>
<td width='10%'><input type='checkbox' name='conveni[]' value='8'><font size=2 class='honbun2'>���������</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='conveni[]' value='9'><font size=2 class='honbun2'>���꡼����</font></td>
<td width='10%'><input type='checkbox' name='conveni[]' value='10'><font size=2 class='honbun2'>����¾</font></td>
</tr>
</table>
		</td>
	</tr>
	<tr>
		<td class="m1">�����ѡ�</td>
		<td class="n1">
<table border=0 width="100%">
<tr>
<td width='10%'><input type='checkbox' name='super[]' value='1'><font size=2 class='honbun2'>���ȡ��衼���ɡ�</font></td>
<td width='10%'><input type='checkbox' name='super[]' value='2'><font size=2 class='honbun2'>��������</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='super[]' value='3'><font size=2 class='honbun2'>���㥹��</font></td>
<td width='10%'><input type='checkbox' name='super[]' value='4'><font size=2 class='honbun2'>��ͧ</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='super[]' value='5'><font size=2 class='honbun2'>����</font></td>
<td width='10%'><input type='checkbox' name='super[]' value='6'><font size=2 class='honbun2'>�ޥ�����</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='super[]' value='7'><font size=2 class='honbun2'>�ޥ륨��</font></td>
<td width='10%'><input type='checkbox' name='super[]' value='8'><font size=2 class='honbun2'>��ޥ��ȥ�</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='super[]' value='9'><font size=2 class='honbun2'>��ˡ�</font></td>
<td width='10%'><input type='checkbox' name='super[]' value='10'><font size=2 class='honbun2'>����¾</font></td>
</tr>
</table>
		</td>
	</tr>
	<tr>
		<td class="m1">��̣������</td>
		<td class="n1">
<table border=0 width="100%">
<tr>
<td width='10%'><input type='checkbox' name='genre[]' value='1'><font size=2 class='honbun2'>����ᡦ����</font></td>
<td width='10%'><input type='checkbox' name='genre[]' value='2'><font size=2 class='honbun2'>����ʪ�ʤ���ʳ���</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='genre[]' value='3'><font size=2 class='honbun2'>����</font></td>
<td width='10%'><input type='checkbox' name='genre[]' value='4'><font size=2 class='honbun2'>�ե��å����</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='genre[]' value='5'><font size=2 class='honbun2'>�ӥ��ͥ�</font></td>
<td width='10%'><input type='checkbox' name='genre[]' value='6'><font size=2 class='honbun2'>��������ߡ�����ݸ���</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='genre[]' value='7'><font size=2 class='honbun2'>�ع������</font></td>
<td width='10%'><input type='checkbox' name='genre[]' value='8'><font size=2 class='honbun2'>���󥿡��ƥ������</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='genre[]' value='18'><font size=2 class='honbun2'>ι�ԡ��쥸�㡼</font></td>
<td width='10%'><input type='checkbox' name='genre[]' value='9'><font size=2 class='honbun2'>����ԥ塼��</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='genre[]' value='10'><font size=2 class='honbun2'>������</font></td>
<td width='10%'><input type='checkbox' name='genre[]' value='11'><font size=2 class='honbun2'>���ݡ���</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='genre[]' value='12'><font size=2 class='honbun2'>��</font></td>
<td width='10%'><input type='checkbox' name='genre[]' value='13'><font size=2 class='honbun2'>��ʡ��</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='genre[]' value='14'><font size=2 class='honbun2'>����</font></td>
<td width='10%'><input type='checkbox' name='genre[]' value='15'><font size=2 class='honbun2'>����ƥꥢ</font></td>
</tr>
<tr>
<td width='10%'><input type='checkbox' name='genre[]' value='16'><font size=2 class='honbun2'>��ư�֡��Х���</font></td>
<td width='10%'><input type='checkbox' name='genre[]' value='17'><font size=2 class='honbun2'>�����ʡ�������</font></td>
</tr>
</table>
		</td>
	</tr>
<tr>
	<td class="m1">���С��ꥹ��</td>
	<td class="n1">
		<input type="checkbox" name="member_list_flag"  onclick="show_hide('member_list_c', checked)">���ꤹ��
		<span id="member_list_c">&nbsp;
			<font class="note">�ʥ��С����롼��ID�򥫥�ޤǶ��ڤä����Ϥ��뤫��������ץܥ���򥯥�å����Ƥ���������</font>&nbsp;
			<input type="button" value="����" onclick="member_select('member_list')"><br>
			<textarea name="member_list" cols=80 rows=3></textarea>
		</span>
	</td>
</tr>
<tr>
	<td class="m1">�������</td>
	<td class="n1">
		<input type="checkbox" name="member_list_flag"  onclick="show_hide('member_list_c', checked)">���ꤹ��
		<span id="member_list_c">&nbsp;
			<font class="note">�ʥ��С����롼��ID�򥫥�ޤǶ��ڤä����Ϥ��뤫��������ץܥ���򥯥�å����Ƥ���������</font>&nbsp;
			<input type="button" value="����" onclick="member_select('member_list')"><br>
			<textarea name="member_list" cols=80 rows=3></textarea>
		</span>
	</td>
</tr>

	<tr>
		<td class="m0" colspan=2>���᡼������</td>
	</tr>
	<tr>
		<td class="m1">�᡼�륿���ȥ�</td>
		<td class="n1">
			<input type="text" name="mail_title" size="50" maxlength="100" value="�֥���饯�����˴ؤ��륢�󥱡��ȡפ����Ϥ��ꤤ���ޤ�">
		</td>
	</tr>
	<tr>
		<td class="m1">�إå�</td>
		<td class="n1">
			<textarea rows="10" cols="70" wrap="hard" name="mail_header">�������������������������������������������������������� �������ơ�net
�������ڤꡡ����2004ǯ4��26��17��
���ݥ���ȡ�����40
����������������������������������������������������������������������</textarea>
		</td>
	</tr>
	<tr>
		<td class="m1">��ʸ</td>
		<td class="n1">
			<textarea rows="20" cols="70" wrap="hard" name="mail_contents">%MONITOR_NAME% ���󡢤���ˤ���

����ϡ��֥���饯�����˴ؤ��륢�󥱡��ȡפˤ����Ϥ�������������
�פäƤ��ޤ���
���Υ᡼��������ä����ǻ��þ��ˤ��ƤϤޤ����ʤ�ɤʤ��Ǥ�
���äǤ��륢�󥱡��ȤǤ��Τǡ����Ҥ��Ҥ���������������

����������������������������������������������������������
���������������������ڤꡧ��������ʷ�ˣ�����ɬ��
����������������������������������������������������������

����������������
�����þ��
������������������������������������������������������������������

 ���������������ФޤǤν���
�������Υ᡼���ľ�ܼ����줿��

����������������
�����󥱡��Ⱦܺ�
������������������������������������������������������������������

������������ġġġģ���
�����������ġġġġģ�������ʷ�ˣ�����ɬ��
��������ݥ���ȡġĲ��������������������ˡڣ����ۥݥ����
�������������������������夲�ޤ���

����������������
��ͭ������
������������������������������������������������������������������

ͭ�������ϡ��嵭�������ƤϤޤꡢ���Υ��󥱡��ȤΣգң̤�����
���줿�᡼��������ä����ܿͤ��ޡ�����¤�Ȥ����Ƥ��������ޤ���

��������������������������������������

��������μ�稬�Ĩ��Ĩ��Ĩ��Ĩ��Ĩ��Ĩ��Ĩ��Ĩ��Ĩ��Ĩ��Ĩ��Ĩ��Ĩ�

���ʲ��Σգң̤�������󤷤ơ����󥱡��Ȥˤ�������������
�� http://www.kikasete.net/monitor/m_remlenq3.php?id=%ENQUETE_PARAM%

�������ʤ��Υ����󥢥ɥ쥹��
�� %MAIL_ADDR% �Ǥ���

�����������ը��Ĩ��Ĩ��Ĩ��Ĩ��Ĩ��Ĩ��Ĩ��Ĩ��Ĩ��Ĩ��Ĩ��Ĩ��Ĩ�

�������Υ��󥱡��Ȥ������ä����ܿͤ��ްʳ���������������Ƥ⡢
�����ݥ���Ȳû����оݤȤϤʤ�ޤ���

�����������䡢�������Ƥ����������ä����ʤɤϥݥ���Ȥ򤪤Ĥ�����
�������Ȥ��Ǥ��ʤ��ʤ�ޤ����������κݤϡ�����դ��������ޤ��褦
�������ꤤ�������ޤ���


����Ǥϡ���������������ꤤ���ޤ���</textarea>
		</td>
	</tr>
	<tr>
		<td class="m1">�եå�</td>
		<td class="n1">
			<textarea rows="10" cols="70" wrap="hard" name="mail_footer">����������������������������������������������������������������������
���Υ᡼����ֿ��Ǥ��䤤��碌��ĺ���Ƥ⡢���������뤳�Ȥ�����ޤ���
����դ���������

�����󥱡��Ȥ��Ф��뤪�䤤��碌
�����Υ��󥱡��Ȥ��Ф��뤪���碌�ϡ��������ơ�net ��̳�ɤؤ�Ϣ��
������������askmyenq@kikasete.net��

���ݥ���Ȥγ�ǧ����Ͽ������ѹ����������ơ�net��̳�ɤؤΤ�������
���ͣ��ڡ����إ�����塢�ƥ�˥塼�ؤ��ʤ߲�������
��http://www.kikasete.net/</textarea>
		</td>
	</tr>
</table>

<br>
<input type="submit" value="��������" onclick="document.form1.next_action.value='update'">
<input type="button" value="����롡" onclick="history.back()">
<input type="hidden" name="next_action">
<input type="hidden" name="marketer_id" value="1047">
<input type="hidden" name="my_enq_no" value="2">
</form>
</div>

<? menu_end() ?>
<? page_footer() ?>

</body>
</html>
