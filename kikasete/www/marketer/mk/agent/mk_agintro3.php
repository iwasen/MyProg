<?
$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/mail.php");
$inc = "$top/inc";
include("$inc/mk_header.php");

// エージェント名取得
$sql = "SELECT ag_name1 FROM t_agent WHERE ag_agent_id=$agent_id";
$agent_name = db_fetch1($sql);

// メール送信
get_mail_template('inq_mkt', $subject, $from, $cc, $bcc, $repty_to, $body);
$subject = "【{$agent_name}】$subject";
$from = str_replace('%MAIL_ADDR%', "$name1 $name2 <$mail_addr>", $from);
$body = str_replace('%CONTENTS%', $contents, $body);
$body = str_replace('%COMPANY%', $company, $body);
$body = str_replace('%REGIST%', decode_regist($regist), $body);
$fetch = get_system_info('sy_mk_inq_to');

send_mail($subject, $fetch->sy_mk_inq_to, $from, $body, $cc, $bcc, $reply_to);
?>

<? marketer_header("CONTACT US | きかせて・net", PG_CONTACT); ?>

<table border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td width="788">

      <table width="660"  border="0" align="center" cellpadding="0" cellspacing="0">

          <tr> 
            <td><img src="../../images/common/spacer.gif" width="10" height="15"></td>
          </tr>
          <tr> 
            <td><img src="../../images/contact/agent_title2.gif" alt="エージェントへメッセージ送信" ></td>
          </tr>
          <tr> 
            <td><img src="../../images/common/spacer.gif" width="10" height="20"></td>
          </tr>
          <tr> 
            <td class="description">お問い合わせありがとうございました。
						<br>
						頂いたお問い合わせ内容につきましては、追ってエージェントよりご連絡させて頂きます。
						<br>
						よろしくお願い申し上げます。
						<br><br>
						なお、数日たってもエージェントより連絡のない場合は、お手数ですが、
						<a href="mailto:k-agent@kikasete.net?subject=%81%a1%82%a8%96%e2%82%a2%8d%87%82%ed%82%b9">事務局</a>
						までお問い合わせ下さい。</td>
          </tr>
          <tr> 
            <td align="center"><img src="../../images/common/spacer.gif" width="10" height="20"></td>
          <tr> 
            <td align="center"><a href="javascript:window.close();" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image19','','../../images/chart/bt01_ov.gif',1)"><img src="../../images/chart/bt01.gif" alt="閉じる" name="Image19" width="108" height="31" border="0"></a></td>
		　<tr> 
            <td align="center"><img src="../../images/common/spacer.gif" width="10" height="20"></td>
          </tr>
          <tr> 
            <td align="right"><a href="#top"><img src="../../images/common/go_top.gif" alt="" width="57" height="11" border="0"></a></td>
          </tr>
          <tr> 
            <td><img src="../../images/common/spacer.gif" width="10" height="20"></td>
          </tr>

      </table>
<td width="1" bgcolor="#dbdbdb"><img src="../../images/common/spacer.gif" width="1" height="10"></td>
</tr>
</table>


<? marketer_footer(false); ?>


