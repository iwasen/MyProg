<!--#INCLUDE FILE="../melmel_inc.asp"-->
<!--#INCLUDE FILE="admin_inc.asp"-->
<%
'******************************************************
' System :�߂�߂�Enet�����Ǘp�y�[�W
' Content:�������j���[���
'******************************************************

SetHeaderTitle "�������j���[", "darkblue"
%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta name="GENERATOR" Content="Microsoft Visual Studio 6.0">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="admin.css">
<style type="text/css">
<!--
TH.m1
{
    BACKGROUND-COLOR: #<%=TC_HAKKOUSHA%>;
    COLOR: white;
    FONT-WEIGHT: bolder
}
TH.m2
{
    BACKGROUND-COLOR: #<%=TC_KOUKOKU%>;
    COLOR: white;
    FONT-WEIGHT: bolder
}
TH.m3
{
    BACKGROUND-COLOR: #<%=TC_MASTER%>;
    COLOR: white;
    FONT-WEIGHT: bolder
}
TH.m4
{
    BACKGROUND-COLOR: #<%=TC_OTHER%>;
    COLOR: white;
    FONT-WEIGHT: bolder
}
TD.n1
{
    BACKGROUND-COLOR: #fff0f8;
    padding-left: 10px;
}
TD.n2
{
    BACKGROUND-COLOR: #f0fff8;
    padding-left: 10px;
}
TD.n3
{
    BACKGROUND-COLOR: #fff8f0;
    padding-left: 10px;
}
TD.n4
{
    BACKGROUND-COLOR: #eeeeee;
    padding-left: 10px;
}
-->
</style>
</head>
<body>

<!--#INCLUDE FILE="admin_header.asp"-->

<table align="center" border=0 cellpadding=8 cellspacing=0 width="75%">
  <tr>
    <td>
      <table align="center" border=1 cellpadding=2 cellspacing=1 width="100%">
        <tr><th class="m1" colspan=3 align="left">�����s�ҁE�����}�K�Ǘ�</th></tr>
        <tr>
          <td class="n1" width="33%"><A href="kari_koudoku.asp">���o�^�����}�K�w�ǎ葱��</A></td>
          <td class="n1" width="33%"><A href="kari_print.asp">���o�^�����}�K���</A></td>
          <td class="n1" width="33%"><A href="shinsa.asp">���o�^�R�����ʏ���</A></td>
        </tr>
        <tr>
          <td class="n1" width="33%"><A href="init_check.asp">�{�o�^�����`�F�b�N</A></td>
          <td class="n1"><A href="random_output.asp">�����_���A�E�g�v�b�g</A></td>
          <td class="n1"><A href="cancel.asp">�{�o�^��������</A></td>
        </tr>
        <tr>
          <td class="n1"><A href="busuu_check.asp">���Ȑ\�������`�F�b�N</A></td>
          <td class="n1"><A href="seisan.asp">���Z�v�����X�g</A></td>
          <td class="n1"><A href="h_mail.asp">���s�҈��ă��[��</A></td>
        </tr>
        <tr>
          <td class="n1"><A href="point.asp">�|�C���g�Ǘ�</A></td>
          <td class="n1"><A href="merge.asp">���s��ID����</A></td>
          <td class="n1"><br></A></td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td>
      <table align="center" border=1 cellpadding=2 cellspacing=1 width="100%">
        <tr><th class="m2" colspan=3 align="left">���L�����e�E�f�ڈ˗�</th></tr>
        <tr>
          <td class="n2" width="33%"><A href="akiwaku.asp">�󂫘g�󋵊m�F</A></td>
          <td class="n2" width="33%"><A href="yoyaku.asp">�\��</A></td>
          <td class="n2" width="33%"><A href="nyuukou.asp">�L�����e</A></td>
        </tr>
        <tr>
          <td class="n2"><A href="shukkou.asp">�o�e����</A></td>
          <td class="n2"><A href="sokuhou.asp">�f�ڏ󋵑���</A></td>
          <td class="n2"><A href="keisai_check.asp">�f�ڏ󋵃`�F�b�N</A></td>
        </tr>
        <tr>
          <td class="n2"><A href="tsuika.asp">�ǉ��˗�</A></td>
          <td class="n2"><A href="report.asp">�f�ڎ��ѕ񍐏��쐬</A></td>
          <td class="n2"><A href="cc_koukoku.asp">�N���b�N���\��</A></td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td>
      <table align="center" border=1 cellpadding=2 cellspacing=1 width="100%">
        <tr><th class="m3" colspan=3 align="left">���}�X�^�����e�i���X</th></tr>
        <tr>
          <td class="n3" width="33%"><a href="mel_master.asp">���s�҃}�X�^</a></td>
          <td class="n3" width="33%"><a href="mag_master.asp">�����}�K�}�X�^</a></td>
          <td class="n3" width="33%"><a href="koukoku_master.asp">�L���}�X�^</a></td>
        </tr>
        <tr>
          <td class="n3"><a href="keisen_master.asp">�L���r���}�X�^</a></td>
          <td class="n3"><a href="control_master.asp">�R���g���[���}�X�^</a></td>
          <td class="n3"><a href="mailtemp_master.asp">���M���[���e���v���[�g</a></td>
        </tr>
        <tr>
          <td class="n3"><a href="client_master.asp">�N���C�A���g�}�X�^</a></td>
          <td class="n3"><br></td>
          <td class="n3"><br></td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td>
      <table align="center" border=1 cellpadding=2 cellspacing=1 width="100%">
        <tr><th class="m4" colspan=3 align="left">�����̑�</th></tr>
        <tr>
          <td class="n4" width="33%"><a href="auto_log.asp">�����������O�\��</a></td>
          <td class="n4" width="33%"><a href="kanri_suuji.asp">�ŐV�Ǘ������\��</a></td>
          <td class="n4" width="33%"><br></td>
        </tr>
      </table>
    </td>
  </tr>
</table>

<!--#INCLUDE FILE="admin_footer.asp"-->

</body>
</html>
