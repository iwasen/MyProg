<?
$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/mail.php");
include("$inc/encrypt.php");
include("$inc/decode.php");
$inc = "$top/inc";
include("$inc/mk_regist.php");
include("$inc/mk_header_sem.php");

// 登録受付けメール送信
function mail_send($reg, $seq_no) {
	$fetch = get_system_info('sy_url_marketer');
	get_mail_template('reg_mkp1', $subject, $from, $cc, $bcc, $repty_to, $body);
	$body = str_replace('%MAIL_ADDR%', $reg->mail_addr, $body);
	$body = str_replace('%REGIST_URL%', "{$fetch->sy_url_marketer}mk_kregicompl.php?key=" . encrypt_number($seq_no, 8), $body);
	send_mail($subject, $reg->mail_addr, $from, $body, $cc, $bcc, $reply_to);
}

// セッション処理
session_start();
if (!isset($_SESSION['ss_regist']))
	redirect('mk_kregi1.php');
$reg = &$_SESSION['ss_regist'];

// 仮登録テーブルに保存
$seq_no = $reg->save_db();

// メール送信
mail_send($reg, $seq_no);

// セッション変数削除
session_destroy();
?>
<? marketer_header('登録受付完了', PG_REGIST) ?>

<table border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td width="788">
    <table width="788" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td valign="top"><img src="../../images/common/spacer.gif" width="10" height="15" /></td>
          <td bgcolor="#dbdbdb">        
          <td valign="top">        
          <td valign="top">&nbsp;</td>
          <td valign="top"><img src="../../images/common/spacer.gif" width="20" height="15"></td>
        </tr>
        <tr>
          <td width="170" valign="top" bgcolor="#e5eaf0">
            <table width="100%"  border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td align="right" bgcolor="#FFFFFF"><img src="../../images/regist/side_regist.gif" alt="ご登録" width="57" height="18"></td>
              </tr>
              <tr>
                <td><img src="../../images/regist/side_mypage_on.gif" alt="Myページ登録" width="170" height="31"></td>
              </tr>
              <tr>
                
                <td><a href="mk_regist.php#mail" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image59','','../../images/regist/side_mailnews_on.gif',1)"><img src="../../images/regist/side_mailnews.gif" alt="メールニュース登録" name="Image59" width="170" height="31" border="0"></a></td>
              </tr>
            </table></td>
          <td width="1" bgcolor="#dbdbdb"><img src="../../images/common/spacer.gif" width="1" height="10" />
          <td width="12" valign="top"><img src="../../images/common/spacer.gif" width="12" height="10">          
          <td valign="top">
            <table width="578" border="0" cellpadding="0" cellspacing="0">
<tr> 
<td class="map"><a href="../../index.php">HOME</a>　＞　<a href="mk_regist.php">登録</a>　＞　Myページ登録</td>
</tr>
<tr>
<td><img src="../../images/common/spacer.gif" width="10" height="10" /></td>
</tr>
              <tr> 
                <td><img src="../../images/regist/title.gif" alt="ご登録について" width="114" height="28"></td>
              </tr>
              <tr> 
                <td><img src="../../images/common/spacer.gif" width="10" height="30"></td>
              </tr>
              <tr> 
                <td width="578"><img src="../../images/regist/header1.gif" alt="Myページ登録" width="578" height="38"></td>
              </tr>
              <tr> 
                <td>&nbsp;</td>
              </tr>
              <tr> 
                <td align="center"><img src="../../images/regist/finish.gif"></td>
              </tr>
              　　 
              <tr> 
                <td><img src="../../images/common/spacer.gif" width="10" height="20"></td>
              </tr>
              <tr> 
                <td> 
                  <p>登録を受付けました。</p>
                  <p>ただいまご登録頂きましたメールアドレス宛てに、【登録確認メール】を送らせて頂いておりますのでご確認ください。</p>
                  <p><Span Style=" color:#FF0000;">メールをご確認の上、そのメールに記載されたＵＲＬをダブルクリックして登録手続きを完了させてください。</Span><br>
                  </p>
                  <p>　 </p>
                  <p> ※ この手続きを完了しませんと、登録されたことにはなりませんのでご注意下さい。 <br>
                    ※ ダブルクリックをしてもページが開かない場合は、ＵＲＬをコピーしてブラウザのアドレスバーに直接貼り付けて下さい。</p>
                  <p>　</p>
                  <p>万が一、１日以上たってもメールが届かない場合は、事務局までお問合わせ下さい。</p>
                  <p>きかせて・net 事務局　<img src="../../images/common/icon_mail.gif" width="14" height="9">　<a href="mailto:k-info@kikasete.net?subject=%81%a1%82%a8%96%e2%82%a2%8d%87%82%ed%82%b9">k-info@kikasete.net</a></p>
                </td>
              </tr>
              <tr> 
                <td><img src="../../images/common/spacer.gif" width="10" height="20"></td>
              </tr>
              <tr> 
                <td align="right"><a href="#top"><img src="../../images/common/go_top.gif" alt="GO TOP" width="57" height="11" border="0"></a></td>
              </tr>
              <tr> 
                <td><img src="../../images/common/spacer.gif" width="10" height="30"></td>
              </tr>
            </table>
          </td>
          <td valign="top">&nbsp;</td>
        </tr>
      </table>
<td width="1" bgcolor="#dbdbdb"><img src="../../images/common/spacer.gif" width="1" height="10"></td>
</tr>
</table>

<? marketer_footer(BLUE); ?>

</body>
</html>
