<?
$top = './..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/encrypt.php");
$inc = "$top/inc";
$img = "$top/image";
include("$inc/mn_enquete.php");

if (strlen($id) == 14)
    decrypt_param($id, $enquete_id, $monitor_id);

$ok = true;

// ���󥱡��Ƚ�λ�����å�
if ($monitor_id != '') {
    $sql = "SELECT mn_mail_addr FROM t_monitor WHERE mn_monitor_id=$monitor_id";
    $result = db_exec($sql);
    if (pg_numrows($result)) {
        $fetch = pg_fetch_object($result, 0);
        $mail_addr = $fetch->mn_mail_addr;

        if ($enquete_id != '') {
            $sql = "SELECT en_enq_kind,en_start_date,en_status FROM t_enquete WHERE en_enquete_id=$enquete_id AND en_enq_kind IN (2,4,5,6) AND en_enq_type=2";
            $result = db_exec($sql);
            if (pg_numrows($result)) {
                $fetch = pg_fetch_object($result, 0);
                if ($fetch->en_status == 5) {
                    if ($fetch->en_enq_kind == 6) {
                        if (!check_pro_enquete($monitor_id, $enquete_id, $fetch->en_start_date))
                            $ok = false;
                    }
                } elseif ($fetch->en_status == 7)
                    $ok = false;
            }
        }
    }
}
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>�������ơ�net</title>
</head>
<body>
<table width="70%" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td width="15">��</td>
        <td colspan="2">
            <table border="0" cellpadding="20" cellspacing="0" width="100%" bgcolor="white">
                <tr>
                    <td><table border="0" cellpadding="3" cellspacing="2" width="100%">
                            <tr>
                                <td bgcolor="#525294"><font color="#ffcc00">��</font><font color="white">Web���󥱡��Ȥ�������</font></td>
                            </tr>
                            <tr>
                                <td>
                                    <form method="post" name="form1" action="login2.php?ok=<?=$ok?>">
                                    <input type="hidden" name="url" value="m_remlenq3a.php?enquete_id=<?=$enquete_id?>">
                                    <font color="#2b2b63"><strong>
<?
if ($ok) {
?>
�������ơ�net �ˤ���Ͽ�Υ᡼�륢�ɥ쥹���ѥ���ɤ����Ϥ��ơ�Web���󥱡��Ȳ����ڡ����إ����󤷤Ƥ��顢����������������
<?
} else {
?>
 �����⺹���夲�����󥱡��Ȥϡ�����ͽ�������������Ƥ��뤫��<br>
�������¤��ڤ�Ƥ��뤿�Ὢλ�����Ƥ��������ޤ�����<br><br>
 �ޤ��Υ��󥱡��Ȥˤ����ϲ�������<br>
 �����٤Ϥ����Ϥ��꤬�Ȥ��������ޤ�����<br>
<?
}
?>
                                        </strong></font><br><br>
                                        <table width="60%" border="0" cellspacing="0" cellpadding="1" bgcolor="#999999" align="center">
                                            <tr>
                                                <td>
                                                    <table border="0" cellpadding="1" cellspacing="1" width="100%">
                                                        <tr>
                                                            <td width="188" bgcolor="#dedede"><font size="2" color="#ffcc00">��</font><font size="2">�᡼�륢�ɥ쥹</font></td>
                                                            <td width="292" bgcolor="white"><font size="2"><input type="text" name="mail_addr" size="30" <?=value($mail_addr)?>> </font></td>
                                                        </tr>
                                                        <tr>
                                                            <td width="188" bgcolor="#dedede"><font size="2" color="#ffcc00">��</font><font size="2">�ѥ����</font></td>
                                                            <td width="292" bgcolor="white"><font size="2"><input type="password" name="password" size="30" maxlength="20"> </font></td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                            <font size="2">
                                        <table border="0" cellpadding="3" cellspacing="2" width="100%">
                                            <tr>
                                                <td>��</td>
                                            </tr>
                                            <tr>
                                                <td><center><input type="submit" value="������"></center></td>
                                            </tr>
                                        </table>
                                    <br>
                                    </form>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
