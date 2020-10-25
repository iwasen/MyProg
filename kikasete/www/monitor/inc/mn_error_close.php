<?
function error_msg_and_close($msg) {
?>
<table border="0" cellpadding="15" cellspacing="0" width="100%" bgcolor="white">
    <tr>
        <td>
            <table border="0" cellpadding="3" cellspacing="2" width="100%">
                <tr>
                    <td>
                        <form>
                        <font color="#2b2b63"><b>正しく入力されていない項目があるようです。</b></font>
                        <hr>
                        <font size="2"><br><?=disp_msg($msg, '・', '<br><br>')?><br></font>
                        <center>
                            <hr>
                            <input type="button" value="　閉じる　" onclick="window.top.close()">
                        </center>
                        </form>
                    </td>
                </tr>
            </table><br>
            <br>
        </td>
    </tr>
</table>
<?
}
?>