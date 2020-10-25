<?
$top = './..';
$inc = "$top/../inc";
include("$inc/enquete.php");
include("$inc/common.php");
include("$inc/mn_tbl.php");
include("$inc/search.php"); // 説明文 2005/03/16 BTI
include("$inc/pro_enquete.php"); // 説明文 2005/03/16 BTI
include("$inc/mye_temp.php"); // 説明文 2005/03/16 BTI
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
            <TITLE><?=htmlspecialchars($enquete->title)?>　についてのアンケート</TITLE>
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
                        <B>クッキーが無効になっています。</B><BR><BR>
                                    この状況では、アンケートに正しく回答出来ません。<BR><BR>
                                    お手数ですが、以下の説明にしたがって操作をしてください。
                        <HR>
                        1.ツールバーから「ツールを選択」<br>
			2.メニューから「インターネットオプション」を選択<br>
			3.プライバシーの「詳細設定」をクリック<br>
			4.自動Cookie処理を上書きをするをチェックする<br>
                        5.サードパーティのCookieを受け入れるをチェックする
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
