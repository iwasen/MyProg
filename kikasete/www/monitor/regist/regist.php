<?
$top = './..';
$inc = "$top/inc";
$img = "$top/image";
include("$inc/mn_header.php");
?>

<?monitor_menu()?>
<!-- コンテンツ本体のソースはこの間に表記 -->
<TABLE cellSpacing=0 cellPadding=0 border=0 width="100%">
	<TBODY>
	<TR>
		<TD align="left" bgcolor="#EEEEEE" background="<?=$img?>/tit_bk.gif"><IMG src="<?=$img?>/tit_reg.gif" width=590 height=30 border=0 alt="モニター登録"></TD>
		<TD align="right" bgcolor="#EEEEEE" background="<?=$img?>/tit_bk.gif"><IMG src="<?=$img?>/tit_bk.gif" width=10 height=30 border=0></TD>
	</TR>
</TABLE>
<TABLE cellSpacing=0 cellPadding=0 border=0 width="600">
<TBODY>
	<TR>
		<TD align="left" colspan="2"><IMG src="<?=$img?>/spacer.gif" width=1 height=15 border=0></TD>
	</TR>
	<TR>
		<TD align="left"><IMG src="<?=$img?>/reg1_m.gif" width=600 height=36 border=0 alt="規約の確認"></TD>
	</TR>
	<TR>
		<TD align="left" colspan="2"><IMG src="<?=$img?>/spacer.gif" width=1 height=15 border=0></TD>
	</TR>
	<TR>
		<TD align="left"><SPAN class=fs12_lh120><font color="#666666">登録情報を記入いただく前に、『個人情報の取り扱い』『規約』をご確認ください。<br>ご確認後、同意頂ける方のみ次のページへお進みください。<BR><BR></font></SPAN></TD>
	</TR>
	<TR>
		<TD align="left" bgcolor="#EEEEEE">
			<TABLE cellSpacing=10 cellPadding=0 border=0 width="100%">
				<TBODY>
					<TR>
						<TD align="left"><SPAN class=fs12_lh120><font color="#666666"><B>■&nbsp;個人情報の取り扱いについて</B><BR></font></SPAN></TD>
					</TR>
						<TR>
							<TD align="left"><SPAN class=fs12_lh120><font color="#666666"><TEXTAREA NAME="rules" rows="13" cols="80">
きかせて・netでは、個人情報の取扱いに対しまして個人情報保護法に合わせ、
細心の注意を払っております。

そのため、ご登録によりあなた様より頂戴する個人情報の扱いについて、
事前にお知らせし、ご理解いただいております。

お手数ではございますが、ご登録の前に、この取り組みをご理解いただく上で
下記をお読みになりご確認ください。

なお、この取り組みによるモニター登録は、あなた様ご自身の判断により登録
する事ができます。

何卒ご協力いただけますようよろしくお願い申し上げます。

※　ご確認・同意いただける場合は、
　　文末の「確認・同意する」のボタンをクリックしてください。
※　ご確認・同意いただけない場合は、大変申し訳ございませんが
　　アンケートに参加することができません。

□個人情報の取扱いについて

（個人情報のご提供にあたって）
株式会社○○○○○○（以下、「当社」といいます）の運営するきかせて・net
では、登録から登録後の活動を通じて収集した個人情報を、下記の通り取り扱わ
せていただきます。

（個人情報の利用）
当社が収集した個人情報は、当社からご登録者様への情報の送信や、当該活動に
必要な物品・謝礼等の送付、活動状況の管理/把握といった、マーケティングサ
ービスの提供という目的達成に必要な範囲内で利用し、それ以外の目的で利用さ
れることは決してございません。

また、当該個人情報を第三者へ開示・提供・預託は、以下の場合を除き、一切行
ないません。

・事前にご本人さまに同意をいただけた場合
・法令等により開示が求められた場合

ご自身の情報について、内容を確認したり、削除・訂正を要求される際には、
このページに記載の【個人情報に関する問合せ窓口】までご連絡ください。

ご連絡いただけましたら合理的な範囲で対応いたします。

（個人情報取り扱いの外部委託）
当社がご登録時・ご登録後の活動時に収集した個人情報は、前項の目的の範囲内
において、外部に委託することがございます。

委託先については、個人情報を適正に取り扱っていると認められる者及び協力会
社を選定し、個人情報を厳重に且つ細心の注意を払って管理することを義務付け、
徹底いたします。

									</TEXTAREA></font></SPAN>
							</TD>
						</TR>
					<TR>
						<TD align="left"><SPAN class=fs12_lh120><font color="#666666"><br><b>（個人情報に関する問合せ窓口）</b><br><br>〒105-0004  東京都港区新橋６−２０−２<br>株式会社　○○○○○○　　 きかせて・net事務局<br>お問い合わせアドレス：<a href="mailto:m-info@kikasete.net">m-info@kikasete.net</a><br>電話：03-5472-7904   FAX：03-5472-7914<br><BR></font></SPAN></TD>
					</TR>
				</TBODY>
			</TABLE>
		</TD>
	</TR>
	<TR>
		<TD align="left" bgcolor="#EEEEEE">
			<TABLE cellSpacing=10 cellPadding=0 border=0 width="100%">
				<TBODY>
					<TR>
						<TD align="left" ><SPAN class=fs12_lh120><font color="#666666"><B>■&nbsp;モニター規約</B><BR></font></SPAN></TD>
					</TR>
					<TR>
						<TD align="left"><SPAN class=fs12_lh120><font color="#666666"><TEXTAREA NAME="rules" rows="13" cols="80">

第１条（規約)

本規約は、株式会社○○○○○○（以下、「当社という」。）が実施、運営する『きかせて・net』（以下、「本サイト」という。）上で提供する諸々のサービス（以下、「本サービス」という。）をモニター（当社に本サービスの利用を申し込み、当社がこれを承諾し、モニター資格を取得した者。）が利用することに関して、モニターと当社との間の一切の関係に適用するものです。


第２条（本規約の範囲及び変更）

当社は当社が定める方法により、モニターの承諾を得ることなく、本規約に新たな規定を追加又は変更できるものとし、モニターは、追加又は変更後の規約内容を承諾するものとします。なお、新たに追加又は変更される規定についても本規約の一部を構成するものとします。追加又は変更された規約は、本サイトに１ヶ月表示した時点で、全員が承認したものとみなします。


第３条（当社からの通知）

前条の場合の他、当社が必要と判断した場合、当社はモニターに対して随時必要な事項を通知します。この通知内容は本サイト上に表示した時点で、直ちに全ての会員が承認したものとします。


第４条（規約の尊守）

モニターは本サイトに参加するにあたり、本規約を遵守するものとします。


第５条（登録）

本サイトを利用しようとするものは当社に対し、当社が定める方法により、登録を行うものとします。登録をした者は、その登録を行った時点で、本規約の内容を承諾したものとします。当社はその登録者に対し、下記のいずれかに該当する場合を除いて、登録の承諾をするものとします。この場合、当社は登録者に対し、その旨を当社が定める方法により通知するものとし、登録者は当社がその通知を発した時点でモニター資格を取得するものとします。

（1）登録をした者が実在しない場合
（2）過去に本モニター規約に違反した事がある場合
（3）登録内容に虚偽の申告があった場合
（4）その他、当社がモニターとして不適切と判断した場合

当社は承認後であっても、承認した会員が上記のいずれかに該当することが判明した場合は、その承認を取り消すことがあります。


第６条（メンバー情報）

本サービスを利用する上で必要となる、モニターの住所、電話番号、本サービスの利用状況等は、当社のデータベースに個人情報として登録されます。当社は登録された情報を個人情報として管理するものとし、次のいずれかに該当する場合を除き、第三者に提供しないものとします
（本サイトのプライバシーポリシーもご覧下さい。）

（1）モニターの同意が得られた場合
（2）法令等により開示が求められた場合
（3）個人識別ができない状態で提供する場合


第７条（回答内容の著作権）

１．モニターが行ったアンケート回答内容の著作権は、全て当社が
　　有するものとします。
　　また、当社がその回答内容を自由に修正・編集、又は、発行・
　　発売するものに転載することをモニターは了承するものとします。

２．アンケート回答内容は当社、その調査を依頼した企業又は個人に
　　公開されます。（本サイトのプライバシーポリシーもご覧下さい。）


第８条（パスワードの管理）

モニターは、本サービスを利用する上で自らが指定するパスワードの管理、使用について一切の責任を持つと同時に、当該パスワードによる本サービスの利用に関しても一切の責任を負うものとします。尚、モニターはパスワードを第三者に譲渡、貸与、売買等をすることはできないものとします。


第９条（モニターの禁止事項）

モニターは本サービス利用にあたり、次に掲げた行為をしてはならないものとします。

（1） 本サービスに関する情報を改ざんする行為
（2） ID・パスワードを不正に使用する行為
（3） 有害なコンピュータープログラム等を送信又は書きこむ行為
（4） 第三者の著作権、その他の知的財産権を侵害する行為
（5） 第三者を誹謗中傷し、その他名誉を侵害する行為
（6） 第三者の財産、プライバシーを侵害する行為
（7） 第三者に不利益を与える行為
（8） 選挙の事前運動、選挙運動、またはこれらに類似する場合及
　　　び公職選挙法に抵触する行為
（9）『きかせて・net』の運営を妨げる行為またはその虞のある行為
（10）『きかせて・net』の信用を毀損する行為またはその虞のある行為
（11）その他、法令に違反する、または違反する虞のある行為
（12）その他、当社が不適切と判断する行為



第１０条（モニターの守秘義務）

モニターは回答を求められたアンケートに対し、実際にアンケートに回答したか否かにかかわらず、当該アンケートを通じて知り得た情報について守秘義務を負うものとします。


第１１条（電子メールの受発信）

１．モニターとして当社と電子メールの受発信を行う場合、登録情
　　報と同一のメールアドレスを使用するものとします。
２．登録と異なるメールアドレスにて受発信を行った場合、当該モ
　　ニターに不利益、損害が発生しても、当社は一切の責任を負わ
　　ないものとします。
３．モニターは当社からの電子メールに対して返信するにあたり、
　　当社の指定する方法により返信するものとします。
４．当社の指定と異なる方法で返信を行ったことにより、当該モニ
　　ターに不利益、損害が発生しても、当社は一切の責任を負わな
　　いものとします。
５．モニターがモニターとして発信する電子メールの本文中の記載
　　内容に関して、当社は一切の責任を負わないものとします。
６．当社からの電子メールの不達により、当該モニターに不利益、
　　損害が発生しても、当社は一切の責任を負わないものとします。


第１２条（変更の届け出）

モニターは、住所等、当社への届け出事項に変更があった場合には、速やかに当社へ所定の方法で届けるものとします。この届け出がなかったことによりモニターが不利益を被った場合でも、当社は一切の責任を負わないものとします。


第１３条（ポイント）

１．当社がアンケートなどの協力者に配給するポイント数は、その
　　負担の程度に応じて当社が決めるものとし、所定の方法により
　　対象メンバーに事前に知らせるものとします。
２．ポイントの精算は当社が規定した基準ポイントまで達した段階
　　で可能となり、基準ポイント単位で精算することができます。
３．退会する場合は、そのモニターのポイント効力は
　　消失するものとします。
４．ポイントの有効期限は入会後１年となります。
　　有効期限を過ぎたポイントの効力は消失するものとします。
　　ただし、モニターがアンケートへ回答しポイントを獲得したり、
　　登録情報の更新手続きを行った場合、ポイントの有効期限を
　　当社が指定する期間延長するものとします。
５．ポイントを譲渡、売買、営利目的又はその他不正に使用する事
　　を固く禁じます。


第１４条（設備）

モニターは本サービスを利用するために必要な通信機器、ソフトウェア、その他これらに付随して必要となるすべての機器を、自己の費用と責任において準備するものとします。また、自己の費用と責任で、任意の電気通信サービスを経由して本サイトに接続するものとします。また、本サイトに接続中に、当該モニターに不利益、損害が発生しても、当社は一切の責任を負わないものとします。


第１５条（情報の削除）

当社は、モニターが本サービスを利用して掲示板等に掲載した情報が、当社の定める期間又は量を超えると当社が判断した場合、あるいは本サービスの運営、保守管理上の必要性から、または第9条に定める禁止行為に係る情報である場合は、モニターへの事前の通知、承諾なく情報を削除することができるものとします。


第１６条（責任の制限）

１．当社は、モニターが本サービスの利用により被った損害に対し、
　　一切の責任を負わないものとします。モニターは、本サービス
　　の利用によって第三者に対して損害を与えた場合、事故の責任
　　と費用をもって解決し、当社に損害を与えないものとします。
２．当社は、モニターが本サイトまたはインターネット通信サービ
　　スを利用する事により得た情報について、一切の保証責任も負
　　わなのとします。又、これらの情報等に起因して生じた損害等
　　に対しても一切の責任を負わないものとします。

第１７条（著作権の帰属）

１．本サイトに掲載されるあらゆる情報、デザイン及びそれを掲載
　　する画面等の著作物の著作権は、特に明記のない限り、全て当
　　社に帰属するものとします。
２．モニターは本サイトを利用する事により知り得た情報を、当社
　　の事前の承諾なしに複製、その他これを出版、放送するなど、
　　その方法のいかんを問わず第三者により利用に供しないものと
　　します。
３．本条の規定に違反して問題が発生した場合、メンバーは、自己
　　の費用と責任においてかかる問題を解決するとともに、当社に
　　何等の迷惑または損害を与えないものとします。


第１８条（本サイトに登録、投稿された情報の変更、複写、移動等）

当社は、モニターから投稿された情報の全部又は一部を、必要に応じて、当社によるモニターへの事前の通知、承認及び対価の支払いを要せず、題名、内容の変更、本サイト内での複写、移動を行うこと、及び本サービスに関し自由に利用する事ができるものとします。また、非公開形式で行われているサービスに関しても、管理及び運営上の理由により、当社がモニターの承諾無しに立ち入ることができるものとします。


第１９条（サービスの一時的な中断）

当社は、次のいずれかに該当すると当社が判断した場合には、モニターへの事前の通知、承諾なく、一時的にサービスを中断できるものとします。

（1）保守管理を定期的に、または緊急に行う場合
（2）火災、停電などにより本サービスが提供できなくなった場合
（3）地震、洪水、津波などの天災により、本サービスが提供でき
　　 なくなった場合
（4）戦争、動乱、暴動、労働協議などにより本サービスが提供で
　　 きなくなった場合
（5）その他、運用上ないし技術上の理由から、当社が本サービス
　　 の一時的中断を必要と判断した場合

当社は、上記または上記以外の理由によって、本サービスの提供の遅延及び中断などが発生した場合、あるいはそれに起因する、モニターまたは第三者が被った損害について、一切の責任を負わないこととします。


第２０条（モニターの退会）

１．モニターが退会する際には、所定の手続きを当社に届け出るも
　　のとします。
２．モニターが退会する場合は、そのモニターのポイント効力は消
　　失するものとします。
３．当社がモニターに付与するモニター資格は一身専属制のものと
　　し、当該モニターが死亡した場合は、その事実を知り得た時点
　　で、当社が退会の手続きを取るものとします。


第２１条（利用停止及び資格剥奪）

当社は、モニターが次のいずれかに該当すると判断した場合は、モニターへの事前の通知又は催告を要せず、本サイトの利用の一時停止又はモニター資格を剥奪できるものとします。

（1）申し込み内容に虚偽があったことが判明した場合
（2）本規約に違反した場合


尚、モニターの行った行為が上記又は第９条の事項のいずれかに該当し、当社が損害を被った場合、当社はモニター資格の剥奪ないしは本サービス利用の一時停止の有無に関係なく、被った損害の賠償を請求できるものとします。


第２２条（本サービスの変更、追加、中止）

１．当社はモニターへの事前の通知、承諾を要せずに、本サービス
　　の提供条件、運用規則、その他本サービスの内容に関する内容
　　の全部又は一部について、変更、追加、中止することができ、
　　モニターはこれを承諾するものとします。
２．サービスの変更・中止に伴い、メンバーに不利益や損害が発生
　　した場合、当社は一切の責任を負わないものとします。


第２３条（合意管轄）

当社及びモニターは、モニターと当社の間で本規約につき訴訟の必要が生じた場合は、東京地方裁判所を弟一審の専属的合意管轄裁判所とすることを合意するものとします。


第２４条（準拠法）

本規約の成立、効力、履行及び解釈に関しては、日本法が適用されるものとします。
							</TEXTAREA></font></SPAN>
						</TD>
					</TR>
				</TBODY>
			</TABLE>
		</TD>
	</TR>
	<tr>
		<td align="center"><a href="regist2.php"><img height="30" width="160" src="<?=$img?>/btn_doi_off.gif" border="0" alt="同意する" vspace="15" onmouseover="this.src='<?=$img?>/btn_doi_on.gif'" onmouseout="this.src='<?=$img?>/btn_doi_off.gif'"></a></TD>
	</tr>
	<tr><td>　</TD></tr>
	<tr>
		<td align="center"><img height="75" width="600" src="<?=$img?>/reg_flow01.gif" border="0" alt="規約の確認" vspace="15"></TD>
	</tr>
</TBODY>
</TABLE>

<!-- コンテンツ本体のソースはこの間に表記 -->
			</TD>
		</TR>
	</TBODY>
</TABLE>
</BODY>
</HTML>
