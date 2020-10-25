<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/encrypt.php");

if (strlen($id) == 14) {
    decrypt_param($id, $seq_no, $monitor_id);
	if ($seq_no == 0 || $monitor_id == 0)
		redirect('index_main.php');

	$sql = "SELECT mn_mail_addr FROM t_monitor WHERE mn_monitor_id=" . sql_number($monitor_id);
	$mail_addr = db_fetch1($sql);
} else
	redirect('index_main.php');
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>きかせて・net</title>
</head>
<body onload="document.form1.<?=$mail_addr == '' ? mail_addr : password?>.focus()">
<table width="70%" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td width="15">　</td>
        <td colspan="2">
            <table border="0" cellpadding="20" cellspacing="0" width="100%" bgcolor="white">
                <tr>
                    <td><table border="0" cellpadding="3" cellspacing="2" width="100%">
                            <tr>
                                <td bgcolor="#525294"><font color="#ffcc00">■</font><font color="white">登録情報を更新する</font></td>
                            </tr>
                            <tr>
                                <td>
                                    <form method="post" name="form1" action="mypage/login.php">
                                    <input type="hidden" name="url" value="m_update.php?birthday_id=<?=$seq_no?>">
                                    <font color="#2b2b63"><strong>
きかせて・net にご登録のメールアドレス・パスワードを入力して、Webアンケート回答ページへログインしてから、登録情報を更新してください。
                                        </strong></font><br><br>
                                        <table width="60%" border="0" cellspacing="0" cellpadding="1" bgcolor="#999999" align="center">
                                            <tr>
                                                <td>
                                                    <table border="0" cellpadding="1" cellspacing="1" width="100%">
                                                        <tr>
                                                            <td width="188" bgcolor="#dedede"><font size="2" color="#ffcc00">●</font><font size="2">メールアドレス</font></td>
                                                            <td width="292" bgcolor="white"><font size="2"><input type="text" name="mail_addr" size="30" <?=value($mail_addr)?>> </font></td>
                                                        </tr>
                                                        <tr>
                                                            <td width="188" bgcolor="#dedede"><font size="2" color="#ffcc00">●</font><font size="2">パスワード</font></td>
                                                            <td width="292" bgcolor="white"><font size="2"><input type="password" name="password" size="30" maxlength="20"> </font></td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                            <font size="2">
                                        <table border="0" cellpadding="3" cellspacing="2" width="100%">
                                            <tr>
                                                <td>　</td>
                                            </tr>
                                            <tr>
                                                <td><center><input type="submit" value="ログイン"></center></td>
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
