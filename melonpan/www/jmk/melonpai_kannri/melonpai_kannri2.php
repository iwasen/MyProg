<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :����Ѥ��̳���ѥڡ���
' Content:���ޥ��ʥӴ���
'******************************************************/

$title_text = $TT_melonpai_kannri;
$title_color = $TC_DOKUSHA;

$sub1 = "SELECT MELONPAI_ID AS CHO_WAIT_ID, COUNT(*) AS CHO_WAIT FROM T_CHOICE"
	. " WHERE melonpai_id=$melonpai_id AND cho_flg='0' GROUP BY MELONPAI_ID";
$sub2 = "SELECT MELONPAI_ID AS CHO_END_ID, COUNT(*) AS CHO_END FROM L_MELONPAI_LOG"
	. " WHERE melonpai_id=$melonpai_id AND LOG_PRO_NO=7 GROUP BY MELONPAI_ID";
$sub3 = "SELECT LOG.MELONPAI_ID AS REC_WAIT_ID, COUNT(*) AS REC_WAIT FROM T_RECOMEND REC, L_MELONPAI_LOG LOG"
	. " WHERE LOG.melonpai_id=$melonpai_id AND  REC.OPEN_FLG=0 AND REC.RECOMEND_ID=LOG.RECOMEND_ID GROUP BY MELONPAI_ID";
$sub4 = "SELECT MELONPAI_ID AS REC_END_ID, COUNT(*) AS REC_END FROM L_MELONPAI_LOG"
	. " WHERE melonpai_id=$melonpai_id AND (LOG_PRO_NO=5 OR LOG_PRO_NO=6) GROUP BY MELONPAI_ID";
$sub5 = "SELECT MELONPAI_ID AS OSUSUME_END_ID, COUNT(LOG_NUM) AS OSUSUME_END FROM L_MELONPAI_LOG"
	. " WHERE (LOG_PRO_NO = 1 or LOG_PRO_NO = 2 ) GROUP BY MELONPAI_ID";

$sql = "SELECT MELONPAI_ID, MELONPAI_NAME, MELONPAI_M_FLG, MELONPAI_POINT, MELONPAI_MAIL_ADDR, MELONPAI_PSWD"
	. ", CHO_WAIT, CHO_END, REC_WAIT, REC_END, OSUSUME_END"
	. " FROM M_MELONPAI LEFT JOIN (" . $sub1 . ") as CW ON MELONPAI_ID=CHO_WAIT_ID"
	. " LEFT JOIN (" . $sub2 . ") as CE ON MELONPAI_ID=CHO_END_ID"
	. " LEFT JOIN (" . $sub3 . ") as RW ON MELONPAI_ID=REC_WAIT_ID"
	. " LEFT JOIN (" . $sub4 . ") as RE ON MELONPAI_ID=REC_END_ID"
	. " LEFT JOIN (" . $sub5 . ") as OE ON MELONPAI_ID=OSUSUME_END_ID"
	. " WHERE melonpai_id=$melonpai_id"
	. " ORDER BY MELONPAI_ID";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$melonpai_id = $fetch->melonpai_id;
	$malonpai_mail_addr = $fetch->melonpai_mail_addr;
	$melonpai_pswd = $fetch->melonpai_pswd;
	$melonpai_name = $fetch->melonpai_name;
	$melonpai_m_flg = $fetch->melonpai_m_flg;
	$melonpai_point = $fetch->melonpai_point;
	$cho_wait = $fetch->cho_wait;
	$cho_end = $fetch->cho_end;
	$rec_wait = $fetch->rec_wait;
	$rec_end = $fetch->rec_end;
	$osusume_end = $fetch->osusume_end;
	$abunai_end = $fetch->abunai_end;

}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><?= $title_text ?></title>
<link rel="stylesheet" type="text/css" href="../css/melonpan.css">
<SCRIPT LANGUAGE=javascript>
<!--
function OnSubmit_form1() {
  return confirm("��Ͽ���ޤ���������Ǥ�����");
}
//-->
</SCRIPT>
</head>
<body>

<? header_jmk($title_text,$title_color,0); ?>
<center>
<form method="post" action="melonpai_kannri3.php" name="form1" onSubmit="return OnSubmit_form1();">
	<table border=0 cellspacing=0 cellpadding=0 width='700'>
		<tr>
			<td class="m0">�����ޥ��ʥӳ�ư�ܺ�</td>
		</tr>
	</table>
	<table border=0 cellspacing=1 cellpadding=3 width="700">
		<tr>
			<td class="m5">�ᡡ��̾</td>
			<td class="n5"><?= htmlspecialchars($melonpai_name) ?></td>
		</tr>
		<tr>
			<td class="m5" width="25%">�ɣ�</td>
			<td class="n5"><?= $malonpai_mail_addr ?></td>
		</tr>
		<tr>
			<td class="m5">�ѥ����</td>
			<td class="n5"><?= $melonpai_pswd ?></td>
		</tr>
		<tr>
			<td class="m5">�ߤĤ���</td>
			<td class="n5">�����Ԥ���<?= replace_zero($cho_wait) ?>��ý����Ѥߡ�<?= replace_zero($cho_end) ?>��
			<input type="button" value="��ȥ�"  onclick="location.href='choice_log.php?melonpai_id=<?= $melonpai_id ?>'"></td>
		</tr>
		<tr>
			<td class="m5">����ʸ</td>
			<td class="n5">�����Ԥ���<?= replace_zero($rec_wait) ?>��ý����Ѥߡ�<?= replace_zero($rec_end) ?>��
			<input type="button" value="��ȥ�" onclick="location.href='recommend_log.php?melonpai_id=<?= $melonpai_id ?>'"></td>
		</tr>
		<tr>
			<td class="m5">��������</td>
			<td class="n5">�����Ѥߡ�<?= replace_zero($osusume_end) ?>��
			<input type="button" value="��ȥ�" onclick="location.href='osusume_log.php?melonpai_id=<?= $melonpai_id ?>'"></td>
		</tr>
		<tr>
			<td class="m5">��ư����</td>
			<td class="n5">
				<input type="radio" name="melonpai_m_flg" value="0" <? If ($melonpai_m_flg == "0") {print " checked";} ?>>��ư��
				<input type="radio" name="melonpai_m_flg" value="1" <? If ($melonpai_m_flg == "1") {print " checked";} ?>>�ٻ���
				<input type="radio" name="melonpai_m_flg" value="2" <? If ($melonpai_m_flg == "2") {print " checked";} ?>>���
			</td>
		</tr>
		<tr>
			<td class="m5">�ݥ����</td>
			<td class="n5"><input size=10 name=melonpai_point maxlength=10 DIR=rtl value="<?= $melonpai_point ?>"> <font class="note">��Ⱦ�ѡ�</font></td>
		</tr>
	</table><br>
	<input type="hidden" name="melonpai_id" value="<?= $melonpai_id ?>">
	<input type="submit" value=" ���� ">
	<input type="reset" value="�ꥻ�å�">
	<input type="button" value=" ��� " onclick="location.href='melonpai_kannri1.php'">
</form>
</center>

<? footer_jmk(0); ?>

</body>
</html>
