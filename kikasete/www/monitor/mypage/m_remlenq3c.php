<?
$top = './..';
$inc = "$top/../inc";
include("$inc/enquete.php");
include("$inc/common.php");
include("$inc/mn_tbl.php");
include("$inc/search.php"); // ����ʸ 2005/03/16 BTI
include("$inc/pro_enquete.php"); // ����ʸ 2005/03/16 BTI
include("$inc/mye_temp.php"); // ����ʸ 2005/03/16 BTI
$inc = "$top/inc";
$img = "$top/image";
//include("$inc/mn_mypage.php");
//include("$inc/mn_header2.php");
include("$inc/mn_error_close.php");
include("$inc/mn_enquete.php");
include("$inc/mn_error.php");
include("$inc/mn_request.php");
if(!$_COOKIE["PHPSESSID"]){
?>
        <HTML>
        <HEAD>
            <TITLE><?=htmlspecialchars($enquete->title)?>���ˤĤ��ƤΥ��󥱡���</TITLE>
        <META http-equiv=Content-Type content="text/html; charset=EUC-JP">

        <STYLE type=text/css>
        div { margin:0; }
        .font-qa {FONT-SIZE: 12pt; COLOR: black}
        </STYLE>

        </HEAD>
        <BODY>
        <div id="title">
        <TABLE cellSpacing=0 cellPadding=10 align=center border=1>
            <TBODY>
            <TR bgColor=#c5ff59>
                <TD>
                    <FONT style="FONT-SIZE: 14px">
                        <B>���å�����̵���ˤʤäƤ��ޤ���</B><BR><BR>
                                    ���ξ����Ǥϡ����󥱡��Ȥ���������������ޤ���<BR><BR>
                                    ������Ǥ������ʲ��������ˤ������ä����򤷤Ƥ���������
                        <HR>
                        1.�ġ���С�����֥ġ���������<br>
			2.��˥塼����֥��󥿡��ͥåȥ��ץ����פ�����<br>
			3.�ץ饤�Х����Ρ־ܺ�����פ򥯥�å�<br>
			4.��ưCookie�������񤭤򤹤������å�����<br>
                        5.�����ɥѡ��ƥ���Cookie���������������å�����
                    </FONT>
                </TD>
            </TR>
            </TBODY>
        </TABLE>
        </BODY>
        </HTML>
<? }else{
header("Location:m_remlenq3a.php");
} ?>
