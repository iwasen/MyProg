<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
$inc = "$top/inc";
include("$inc/mku_mypage.php");

include("$inc/mku_header.php");

?><? marketer_header('登録', PG_REGIST) ?>

<table width="720" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td rowspan="3"><img src="images/common/spacer.gif" width="10" height="20"></td>
		<td><a href="mku_regist.php"><img src="images/mk_kregi_b.gif" width="80" height="16" border="0"></a></td>
		<td width="640" height="16" bgcolor="#292864"></td>
	</tr>
	<tr>
		<td width="710" colspan="4"><img src="images/oline.gif" width="710" height="2" border="0"></td>
	</tr>
	<tr>
		<td width="710" colspan="4"><img src="images/bline.gif" width="710" height="2" border="0"></td>
	</tr>
</table>
<table width="720" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td><img src="images/common/spacer.gif" width="1" height="10" border="0"></td>
	</tr>
</table>
<table width="720" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
		<td width="538" valign="top"><img src="images/t_mk_kregi2.gif" width="538" height="20"><br><br></td>
	</tr>
</table>
<table width="720" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td rowspan="2" width="20"><img src="images/common/spacer.gif" width="20" height="1" border="0"></td>
		<td width="700"><img src="images/common/spacer.gif" width="1" height="20" border="0"></td>
	</tr>
	<tr>
		<td>
			<table width="700" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td colspan="2">ご契約ユーザーに登録頂きますと<p></td>
				</tr>
				<tr>
					<td width="20"><img src="images/arrow20.gif" width="20" height="20"></td>
					<td width="560" valign="middle">きかせて・net モニターの最新情報が分かるメールニュースの配信</td>
				</tr>
				<tr>
					<td width="20"><img src="images/arrow20.gif" width="20" height="20"></td>
					<td width="560" valign="middle">マーケター同士のディスカッションの場「Marketer's Cafe」に登録</td>
				</tr>
				<tr>
					<td width="20"><img src="images/arrow20.gif" width="20" height="20"></td>
					<td width="560" valign="middle">きかせて・net 無料メールアドレスを進呈</td>
				</tr>
				<tr>
					<td colspan="2">　<br>の他に、<p></td>
				</tr>
				<tr>
					<td width="20"><img src="images/arrow20.gif" width="20" height="20"></td>
					<td width="560" valign="middle">専用エージェントが、担当としていつでもサポート</td>
				</tr>
				<tr>
					<td width="20"><img src="images/arrow20.gif" width="20" height="20"></td>
					<td width="560" valign="middle">『Myメニュー』機能で、モニターに直接アンケート・インタビュー</td>
				</tr>
				<tr>
					<td colspan="2">　<br>などのサービスを受けることができます。</td>
				</tr>
				<tr>
					<td colspan="2">　<p>登録にあたっては、会費を頂きます。</td>
				</tr>
			</table>
			<br>
			<table width="300" border="1" bordercolor="#C0C0C0" bordercolorlight="#C0C0C0" bordercolordark="#C0C0C0" cellspacing="3" cellpadding="3">
				<tr>
					<td>会費・・・3,000円／月</td>
				</tr>
			</table>
			<br>
			<table width="580" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td colspan="3">
						ご契約ユーザーとしてご登録頂くために、下記のフォームに必要事項を記入の上、登録ボタンを押して下さい。(
						<u>所要時間は10分程度</u>です。)<p>また、きかせて・net の個人情報に対する考え方・ユーザー規約に関しましては、それぞれをご確認下さい。<p>
					</td>
				</tr>
				<tr>
					<td colspan="3" align="center">
						〔 <a href="mku_privacy.php">Privacy Policy</a> 〕〔 <a href="mku_krules.php">ユーザー規約</a> 〕 
						 (登録前に必ずご確認下さい)
					</td>
				</tr>
				<tr>
					<td>
						<b><br><br>【SSLによる登録情報の暗号化について】</b>
						<br><br>皆さんに安心して登録して頂くために、きかせて・net ではSSLによる情報の暗号化を行っていますが、ブラウザに
						よってはSSLに対応していないものもあります。ブラウザのバージョンをご確認の上、当てはまる方をクリックして登録ページへとお進みください。
						（<a href="mku_privacy.php" target="_blank">SSLについて　[Privacy Policy]</a>
						）
					</td>
				</tr>
				<tr>
					<td>
						<br><br>
						<table width="540" border="1" cellpadding="3" cellspacing="3" bordercolor="#c0c0c0" bordercolorlight="#c0c0c0" bordercolordark="#c0c0c0">
							<tr>
								<td width="470">
									【対応しているブラウザ】<br><br>・Netscape Communicator 4.06以降<br>・Microsoft Internet Explorer 4.0以降
									<br><br>(お使いのパソコンがMacintoshの場合は 4.5以降になります)
								</td>
								<td width="70"><a href="<?=https_url('mk_kregi1.php')?>"><img src="images/regist_s.gif" border="0"></a>
								</td>
							</tr>
							<tr>
								<td width="470">
									【対応していないブラウザ】<br><br>上記に当てはまらない場合は、SSLに対応していません。恐れ入りますが
									SSL非対応ページより登録下さい。 
								</td>
								<td width="70"><a href="<?=http_url('mk_kregi1.php')?>"><img src="images/regist_ns.gif" border="0"></a>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>

<? marketer_footer() ?>
