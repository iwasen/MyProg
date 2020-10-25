<?
/******************************************************
' System :�֤���������� �������������ӥ��׻�̳���ѥڡ���
' Content:OneToOne�᡼�롦���Ѥ���������
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");
include("$inc/select.php");
include("$inc/result.php");

// ���ϥѥ�᡼��
$user_id = $_GET['user_id'];

// �б���̼���
$sql = "SELECT t_user_result.*,ups_name_kanji,ups_mail_addr"
		. " FROM t_user_personal"
		. " LEFT JOIN t_user_result ON urs_user_id=ups_user_id"
		. " WHERE ups_user_id=$user_id";
$result = db_exec($sql);
if (pg_num_rows($result))
	$fetch = pg_fetch_object($result, 0);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/center.css">
<script type="text/javascript">
<!--
function onSubmit_form1(f) {
	var f = document.form1;
	if (f.hot.value == "1") {
		if (f.rank.value == "") {
			alert("��󥯤����򤷤Ƥ���������");
			f.rank.focus();
			return false;
		}
		return confirm("���Ѥ���Ԥ��ޤ���������Ǥ�����");
	} else
		return confirm("���Ѥ��������Ͽ���ޤ���������Ǥ�����");
}
//-->
</script>
</head>
<body>

<? center_header('OneToOne�᡼��ð��Ѥ���������') ?>

<div align="center">
<form method="post" name="form1" action="result_update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="100%">
	<tr>
		<td class="m0" colspan=2>�����Ѥ���������Ϥ��Ƥ���������</td>
	</tr>
	<tr class="subhead">
		<td colspan=2>�����Ͱո�</td>
	</tr>
	<tr>
		<td class="m1">����¾�ؿ��Τ����</td>
		<td class="n1">
			<select name="sonota_kanshin"><?= select_sonota_kanshin(DEFAULT_SELECT, $fetch->urs_sonota_kanshin) ?></select>
		</td>
	</tr>
	<tr>
		<td class="m1">�ؿ�����᡼����</td>
		<td class="n1">
			<select name="kanshin_maker"><?= select_kanshin_maker(DEFAULT_SELECT, $fetch->urs_kanshin_maker) ?></select>
		</td>
	</tr>
	<tr>
		<td class="m1">�ؿ������̾</td>
		<td class="n1">
			<input class="kanji" type="text" name="kanshin_car" size=30 maxlength=15 <?=value($fetch->urs_kanshin_car)?>>
			<span class="note">�����ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">�������٥��Ͷ��</td>
		<td class="n1">
			<select name="jishuu_enent"><?= select_jishuu_enent(DEFAULT_SELECT, $fetch->urs_jishuu_enent) ?></select>
		</td>
	</tr>
	<tr>
		<td class="m1">��������</td>
		<td class="n1">
			<input type="checkbox" name="shiryou_souhu[]" value="0" <?=checked($fetch->urs_shiryou_souhu{0})?>>���⤻��
			<input type="checkbox" name="shiryou_souhu[]" value="1" <?=checked($fetch->urs_shiryou_souhu{1})?>>�ؿ�����
			<input type="checkbox" name="shiryou_souhu[]" value="2" <?=checked($fetch->urs_shiryou_souhu{2})?>>�������մ�˾
			<input type="checkbox" name="shiryou_souhu[]" value="3" <?=checked($fetch->urs_shiryou_souhu{3})?>>����
		</td>
	</tr>
	<tr>
		<td class="m1">�������ե�����</td>
		<td class="n1">
			<input class="kanji" type="text" name="shiryou_comment" size=30 maxlength=15 <?=value($fetch->urs_shiryou_comment)?>>
			<span class="note">�����ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">�����󶡤η�³</td>
		<td class="n1">
			<select name="teikyou_keizoku"><?= select_teikyou_keizoku(DEFAULT_SELECT, $fetch->urs_teikyou_keizoku) ?></select>
		</td>
	</tr>
	<tr>
		<td class="m1">�����󶡷���</td>
		<td class="n1">
			<input type="checkbox" name="teikyou_keitai[]" value="0" <?=checked($fetch->urs_teikyou_keitai{0})?>>ˬ��
			<input type="checkbox" name="teikyou_keitai[]" value="1" <?=checked($fetch->urs_teikyou_keitai{1})?>>�ԣţ�
			<input type="checkbox" name="teikyou_keitai[]" value="2" <?=checked($fetch->urs_teikyou_keitai{2})?>>�ģ�
			<input type="checkbox" name="teikyou_keitai[]" value="3" <?=checked($fetch->urs_teikyou_keitai{3})?>>�ƣ���
			<input type="checkbox" name="teikyou_keitai[]" value="4" <?=checked($fetch->urs_teikyou_keitai{4})?>>E-mail
			<input type="checkbox" name="teikyou_keitai[]" value="5" <?=checked($fetch->urs_teikyou_keitai{5})?>>��������
		</td>
	</tr>
	<tr>
		<td class="m1">��Ź�ؤ���˾</td>
		<td class="n1">
			<select name="mise_youbou"><?= select_mise_youbou(DEFAULT_SELECT, $fetch->urs_mise_youbou) ?></select>
		</td>
	</tr>
	<tr>
		<td class="m1">������</td>
		<td class="n1">
			<input class="kanji" type="text" name="commant" size=60 maxlength=30 <?=value($fetch->urs_commant)?>>
			<span class="note">�����ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr class="subhead">
		<td colspan=2>��ͭ����</td>
	</tr>
	<tr>
		<td class="m1">��̣�ʤ�</td>
		<td class="n1">
			<input class="kanji" type="text" name="shumi" size=100 maxlength=50 <?=value($fetch->urs_shumi)?>>
			<span class="note">�����ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr class="subhead">
		<td colspan=2>�ƥ쥳������</td>
	</tr>
	<tr>
		<td class="m1">���</td>
		<td class="n1">
			<select name="rank"><?= select_rank(DEFAULT_SELECT, $fetch->urs_rank) ?></select>
		</td>
	</tr>
	<tr class="subhead">
		<td colspan=2>����¾</td>
	</tr>
	<tr>
		<td class="m1">������</td>
		<td class="n1">
			<input class="kanji" type="text" name="commant1" size=100 maxlength=50 <?=value($fetch->urs_commant1)?>>
			<span class="note">�����ѣ���ʸ���ޤǡ�</span>
			<input class="kanji" type="text" name="commant2" size=100 maxlength=50 <?=value($fetch->urs_commant2)?>>
			<span class="note">�����ѣ���ʸ���ޤǡ�</span>
			<input class="kanji" type="text" name="commant3" size=100 maxlength=50 <?=value($fetch->urs_commant3)?>>
			<span class="note">�����ѣ���ʸ���ޤǡ�</span>
			<input class="kanji" type="text" name="commant4" size=100 maxlength=50 <?=value($fetch->urs_commant4)?>>
			<span class="note">�����ѣ���ʸ���ޤǡ�</span><br>
		</td>
	</tr>
	<tr>
		<td class="m1">ʻͭ�־������²����</td>
		<td class="n1">
			<input class="kanji" type="text" name="commant5" size=100 maxlength=50 <?=value($fetch->urs_commant5)?>>
			<span class="note">�����ѣ���ʸ���ޤǡ�</span>
			<input class="kanji" type="text" name="commant6" size=100 maxlength=50 <?=value($fetch->urs_commant6)?>>
			<span class="note">�����ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr class="subhead">
		<td colspan=2>�����Ѥ߼ּ���</td>
	</tr>
	<tr>
		<td class="m1">��������</td>
		<td class="n1">
			<select name="kounyuu_joukyou"><?= select_kounyuu_joukyou(DEFAULT_SELECT, $fetch->urs_kounyuu_joukyou) ?></select>
		</td>
	</tr>
	<tr>
		<td class="m1">������̾</td>
		<td class="n1">
			<input class="kanji" type="text" name="kounyuu_shamei" size=40 maxlength=20 <?=value($fetch->urs_kounyuu_shamei)?>>
			<span class="note">�����ѣ���ʸ���ޤǡ�</span>
		</td>
	</tr>
</table>
<br>
<input type="submit" value="���Ѥ��������Ͽ�Τߤ�Ԥ�" onclick="document.form1.hot.value=''">
<input type="submit" value="��Ͽ���ư��Ѥ���Ԥ�" onclick="document.form1.hot.value='1'">
<input type="button" value="����󥻥�" onclick="window.close()">
<input type="hidden" name="user_id" <?=value($user_id)?>>
<input type="hidden" name="hot">
</form>
</div>

<? center_footer() ?>

</body>
</html>
