<?php
include ("inc/header_jmk.php");
include ("inc/footer_jmk.php");
include ("inc/common.php");
include ("inc/database_inc.php");

/******************************************************
' System ����Ѥ��̳���ѥڡ���
' Content:������˥塼����
'******************************************************/

$title_text = "����Ѥ��̳�ɥ�˥塼";
$title_color = "#9fc741";

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<title><? echo ("$title_text") ?></title>
<link rel="stylesheet" type="text/css" href="css/melonpan.css">
<style type="text/css">
<!--
TH.m1
{
    BACKGROUND-COLOR: #aa4488;
    COLOR: white;
    FONT-WEIGHT: bolder
}
TH.m2
{
    BACKGROUND-COLOR: #44aa88;
    COLOR: white;
    FONT-WEIGHT: bolder
}
TH.m3
{
    BACKGROUND-COLOR: #aa8844;
    COLOR: white;
    FONT-WEIGHT: bolder
}
TH.m4
{
    BACKGROUND-COLOR: #9f8fc0;
    COLOR: white;
    FONT-WEIGHT: bolder
}
TH.m5
{
    BACKGROUND-COLOR: #888888;
    COLOR: white;
    FONT-WEIGHT: bolder
}

-->
</style>
</head>
<body>

<? header_jmk($title_text,$title_color,2); ?>

<table align="center" border=0 cellpadding=8 cellspacing=0 width="80%">
  <tr>
    <td>
      <table align="center" border=1 cellpadding=2 cellspacing=0 width="100%">
        <tr><th class="m1" colspan=3 align="left">��ȯ�Լԡ����ޥ�����</th></tr>
        <tr>
          <td width="33%"><A href="kari_touroku/kari_touroku1.php"><nobr>����Ͽ��ǧ����</nobr></a></td>
          <td width="33%"><A href="hon_touroku/hon_touroku1.php"><nobr>����Ͽ��ǧ����</nobr></a></td>
          <td width="33%"><A href="shounin_list/shounin_list.php"><nobr>��ǧ�Ѥߥ��ޥ�����</nobr></A></td>
        </tr>
        <tr>
          <td><A href="teishi/teishi1.php"><nobr>���ޥ�ȯ����ߺ��</nobr></a></td>
          <td><A href="riba_regist/index.php"><nobr>��Фå�����Ͽ</nobr></a></td>
          <td><A href="hinsitsu/index.php"><nobr>�ʼ������å�</nobr></a></td>
        </tr>
        <tr>
          <td><A href="redcard_list/redcard_list.php"><nobr>���Υ�åɥ����ɰ���</nobr></A></td>
          <td><a href="pub_msg/pub_msg1.php"><nobr>�ֻ�̳�ɤ���Τ��Τ餻������</nobr></a></td>
          <td><A href="hakkousha_mainte/hakkousha_mainte1.php"><nobr>ȯ�ԼԾ�����ƥʥ�</nobr></a></td>
        </tr>
        <tr>
          <td><A href="letter_hakkousha/letter_hakkousha.php"><nobr>ȯ�Լ԰��ƥ᡼��</nobr></A></td>
          <td><br></td>
          <td><br></td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td>
      <table align="center" border=1 cellpadding=2 cellspacing=0 width="100%">
        <tr><th class="m2" colspan=3 align="left">���ɼԡ����ޥ��ʥӴ���</nobr></a></th></tr>
        <tr>
          <td width="33%"><A href="dokusha_mainte/dokusha_mainte1.php"><nobr>�ɼԾ�����ƥʥ�</nobr></a></td>
          <td width="33%"><A href="dokusha_ikkatsu/dokusha_ikkatsu1.php"><nobr>�ɼ԰����Ͽ/���</nobr></a></td>
          <td width="33%"><A href="addr_search/addr_search1.php"><nobr>�ɼԸ���/�������</nobr></A></td>
        </tr>
        <tr>
          <td><A href="disabled_adr/index.php"><nobr>���ɶػߥ᡼�륢�ɥ쥹��Ͽ</nobr></a></td>
          <td><br></td>
          <td><br></td>
        </tr>
        <tr>
          <td><A href="melonpai_regist/index.php"><nobr>���ޥ��ʥ���Ͽ</nobr></a></td>
          <td><A href="melonpai_kannri/melonpai_kannri1.php"><nobr>���ޥ��ʥӴ���</nobr></a></td>
          <td><A href="max_setting/index.php"><nobr><nobr>���ޥ��ʥӾ������</nobr></a></td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td>
      <table align="center" border=1 cellpadding=2 cellspacing=0 width="100%">
        <tr><th class="m3" colspan=3 align="left">����̳�ɥ��ƥʥ�</th></tr>
        <tr>
          <td width="33%"><A href="letter_melonpan/index.php"><nobr>����Ѥ�쥿���ۿ�</nobr></a></td>
          <td width="33%"><A href="letter_bakyun/index.php"><nobr>�Ў����塼���ۿ�</nobr></a></td>
          <td width="33%"><A href="letter_tsuushin/index.php"><nobr>����Ѥ��̿��ۿ�</nobr></a></td>
				</tr>
				<tr>
          <td width="33%"><A href="haishin_log/index.php"><nobr>��̳�ɥ᡼���ۿ���</nobr></a></td>
          <td width="33%"><A href="hitorigoto/index.php"><nobr>�ֺ����ΤҤȤꤴ�ȡ�����</nobr></a></td>
          <td><A href="whats_new/index.php"><nobr>��What's new������</nobr></a></td>
        </tr>
        <tr>
          <td><A href="footer/index.php"><nobr>�եå����ǡ�����Ͽ</nobr></a></td>
          <td><a href="haishin_yoyaku/haishin_list.php"><nobr>�ۿ�ͽ�����</nobr></a></td>
          <td><a href="kanri_suuji/kanri_suuji1.php"><nobr>������������</nobr></a></td>
        </tr>
        <tr>
					<td><A href="err_addr_edit/index.php"><nobr>�������ɥ쥹����</nobr></a></td>
					<td><A href="osusume_log/index.php"><nobr>�������������</nobr></a></td>
          <td><a href="henkou_list/index.php"><nobr>��Ͽ�����ѹ���������</nobr></a></td>
        </tr>
        <tr>
					<td><a href="add_point/index.php"><nobr>ȯ�Լԡ��ɼԡ����ޥ��ʥ�/�ݥ������Ϳ</nobr></a></td>
					<td><a href="point_list/index.php"><nobr>�ݥ���Ⱦ�������</nobr></a></td>
					<td><a href="sabun_point/index.php"><nobr>�ɼ���Ͽ����ʬ/�ݥ������Ϳ</nobr></a></td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td>
      <table align="center" border=1 cellpadding=2 cellspacing=0 width="100%">
        <tr><th class="m4" colspan=3 align="left">���ޥ������ƥʥ�</th></tr>
          <td width="33%"><a href="mail_template/index.php "><nobr>�����᡼��ƥ�ץ졼��</nobr></a></td>
          <td width="33%"><br></td>
          <td width="33%"><br></td>
      </table>
    </td>
  </tr>
  <tr>
    <td>
      <table align="center" border=1 cellpadding=2 cellspacing=0 width="100%">
        <tr><th class="m5" colspan=3 align="left">������¾</th></tr>
          <td width="33%"><a href="present/index.php "><nobr>�ץ쥼��ȱ������</nobr></a></td>
          <td width="33%"><a href="cleaning/index.php"><nobr>�᡼�륯�꡼�˥󥰥����ӥ�</nobr></a></td>
          <td width="33%"><a href="banner_set/index.php"><nobr>����Хʡ�����</nobr></a></td>
				</tr>
      </table>
    </td>
  </tr>
</table>

<? footer_jmk(2); ?>

</body>
</html>
