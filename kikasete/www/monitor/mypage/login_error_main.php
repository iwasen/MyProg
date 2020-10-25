<?
$top = './..';
$inc = "$top/inc";
$img = "$top/image";
include("$inc/mn_header.php");
?>
<?monitor_menu()?>
<!-- コンテンツ本体のソースはこの間に表記 -->
<form name="form1" method="post" action="<?=$top?>/index.php">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td valign="top" width="7" align="left"><img src="<?=$img?>/contents2.gif" width="7" height="69" alt="CONTENTS"></td>
		<td width="100%" align="center" valign="top"><br>
			<br>
			<table border="0" cellpadding="15" cellspacing="0" width="500" bgcolor="white">
				<tr>
					<td><font size="2">
						<table border="0" cellpadding="3" cellspacing="2" width="462">
							<tr>
								<td><font color="#2b2b63"><b>ログインできませんでした。</b></font>
									<hr>
									<font size="2"><br>ご登録いただているアドレスとパスワードを入力してください。<br>パスワードを忘れた方は<a href="<?=$top?>/guide/pwrequest.php">こちら</a>よりお問い合わせください。<br><br></font>
									<center><hr><input type="button" value="戻る" onclick="history.back()"></center>
								</td>
							</tr>
						</table><br>
						<br>
						</font>
					</td>
				</tr>
			</table>
			<br><br>
		</td>
	</tr>
</table>
</form>
<!-- コンテンツ本体のソースはこの間に表記 -->
			</TD>
		</TR>
	</TBODY>
</TABLE>
</BODY>
</HTML>