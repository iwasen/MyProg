<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/format.php");
include("$inc/mr_permission.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");
?>
<? marketer_header('FAQ', PG_CENTER) ?>

<!--contents start-->
<table border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
<tr> 
<td width="788"> 
<table width="788" border="0" cellpadding="0" cellspacing="0">
<tr> 
<td valign="top"><img src="images/common/spacer.gif" width="10" height="15"></td>
<td bgcolor="#dbdbdb"> 
<td width="12" rowspan="2"> <img src="images/common/spacer.gif" width="18" height="10"> 
<td valign="top"><img src="images/common/spacer.gif" width="10" height="15"></td>
</tr>
<tr> 
<td width="170" valign="top" bgcolor="#e5eaf0"> 

<? marketer_menu(M_OFFICE); ?>

</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="10" /> 
<td valign="top"> 
            <table width="553" border="0" cellpadding="0" cellspacing="0">
              <tr> 
                <td width="553"><img src="images/common_my/title_06.gif" alt="COMMON" width="553" height="25"></td>
              </tr>
              <tr> 
                <td><img src="images/common/spacer.gif" width="10" height="10" /></td>
              </tr>
              <tr> 
                <td class="footer_text"> 
                  <table width="553" border="0" cellpadding="0" cellspacing="0">
                    <tr> 
                      <td><img src="images/mkk_kkstfrom/title_05.gif" alt="FAQ" width="553" height="24"></td>
                    </tr>
                    <tr> 
                      <td><img src="images/common/spacer.gif" width="10" height="10" /></td>
                    </tr>
                    <tr> 
                      <td><img src="images/mkk_faq/title01.gif" alt="きかせて・net について " width="553" height="25"></td>
                    </tr>
                    <tr> 
                      <td><img src="images/common/spacer.gif" width="10" height="10" /></td>
                    </tr>
                    <tr> 
                      <td > 
                        <table width="100%" border="0" cellspacing="2" cellpadding="0" class="nor_text">
                          <tr> 
                            <td width="5%" class="right_menu_title">Q.</td>
                            <td width="95%" ><a href="#1"> Myページとは？</a></td>
                          </tr>
                          <tr> 
                            <td class="right_menu_title">Q.</td>
                            <td width="90%" ><a href="#2">Myページ でできることは？</a></td>
                          </tr>
                          <tr> 
                            <td class="right_menu_title">Q.</td>
                            <td width="90%" ><a href="#3">リサーチパートナーとは？</a></td>
                          </tr>
                        </table>                      </td>
                    </tr>
                    <tr> 
                      <td><img src="images/common/spacer.gif" width="10" height="15" /></td>
                    </tr>
                    <tr> 
                      <td><img src="images/mkk_faq/title02.gif" alt="登録について" width="553" height="25"></td>
                    </tr>
                    <tr> 
                      <td><img src="images/common/spacer.gif" width="10" height="10" /></td>
                    </tr>
                    <tr> 
                      <td> 
                        <table width="100%" border="0" cellspacing="2" cellpadding="0" class="nor_text">
                          <tr> 
                            <td width="5%" class="right_menu_title">Q.</td>
                            <td width="95%" ><a href="#4">登録にはどんな種類がある？</a></td>
                          </tr>
                          <tr> 
                            <td class="right_menu_title">Q.</td>
                            <td width="90%" ><a href="#5">登録に費用はかかる？</a></td>
                          </tr>
                          <tr> 
                            <td class="right_menu_title">Q.</td>
                            <td width="90%" ><a href="#6">エラーメッセージが出て先に進めない</a></td>
                          </tr>
                          <tr> 
                            <td class="right_menu_title">Q.</td>
                            <td width="90%" ><a href="#7">登録情報は変更できる？</a></td>
                          </tr>
                          <tr> 
                            <td class="right_menu_title">Q.</td>
                            <td width="90%" ><a href="#8">登録を解除するには？</a></td>
                          </tr>
                        </table>                      </td>
                    </tr>
                    <tr> 
                      <td><img src="images/common/spacer.gif" width="10" height="15" /></td>
                    </tr>
                    <tr> 
                      <td><img src="images/mkk_faq/title03.gif" alt="エージェントについて" width="553" height="25"></td>
                    </tr>
                    <tr> 
                      <td><img src="images/common/spacer.gif" width="10" height="10" /></td>
                    </tr>
                    <tr> 
                      <td> 
                        <table width="100%" border="0" cellspacing="2" cellpadding="0" class="nor_text">
                          <tr> 
                            <td width="5%" class="right_menu_title">Q.</td>
                            <td width="95%" ><a href="#9">エージェントとは？</a></td>
                          </tr>
                          <tr> 
                            <td class="right_menu_title">Q.</td>
                            <td width="90%" ><a href="#10">エージェントは何をしてもらえる？</a></td>
                          </tr>
                          <tr> 
                            <td class="right_menu_title">Q.</td>
                            <td width="90%" ><a href="#11">専属エージェントを代えることはできる？</a></td>
                          </tr>
                        </table>                      </td>
                    </tr>
                    <tr> 
                      <td><img src="images/common/spacer.gif" width="10" height="15" /></td>
                    </tr>
                    <tr> 
                      <td><img src="images/mkk_faq/title04.gif" alt="調査実施について" width="553" height="25"></td>
                    </tr>
                    <tr> 
                      <td><img src="images/common/spacer.gif" width="10" height="10" /></td>
                    </tr>
                    <tr>
                      <td>
					  <table width="100%" border="0" cellspacing="2" cellpadding="0" class="nor_text">
                          <tr> 
                            <td width="5%" class="right_menu_title">Q.</td>
                            <td width="95%" ><a href="#12">調査は誰に依頼すればいい？</a></td>
                          </tr>
                          <tr> 
                            <td class="right_menu_title">Q.</td>
                            <td width="90%" ><a href="#13">調査は誰が実施する？</a></td>
                          </tr>
                        </table>					  　</td>
                    </tr>
                    <tr>
                      <td><img src="images/common/spacer.gif" width="10" height="15" /></td>
                    </tr>
                    <tr>
                      <td><img src="images/mkk_faq/title05.gif" alt="アンケート結果の印刷方法について" width="553" height="25" /></td>
                    </tr>
                    <tr> 
                      <td><img src="images/common/spacer.gif" width="10" height="15" /></td>
                    </tr>
                    <tr>
                      <td>
					  <table width="100%" border="0" cellspacing="2" cellpadding="0" class="nor_text">
                          <tr> 
                            <td width="5%" class="right_menu_title">Q.</td>
                            <td width="95%" ><a href="#14">Web画面に表示されているアンケート結果をA4で印刷しようとすると、ページの右端が切れてしまう。</a></td>
                          </tr>
                          <tr> 
                            <td width="5%" class="right_menu_title">Q.</td>
                            <td width="95%" ><a href="#15">Web画面の線や背景が印刷されない。</a></td>
                          </tr>
                        </table>					  </td>
                    </tr>
                    <tr>
                      <td><img src="images/common/spacer.gif" width="10" height="15" /></td>
                    </tr>
                    <tr>
                      <td><img src="images/mkk_faq/title06.gif" alt="動作環境について" width="553" height="25" /></td>
                    </tr>
                    <tr>
                      <td><img src="images/common/spacer.gif" width="10" height="15" /></td>
                    </tr>
                    <tr>
                      <td><table width="100%" border="0" cellspacing="2" cellpadding="0" class="nor_text">
                        <tr>
                          <td width="5%" class="right_menu_title">Q.</td>
                          <td width="95%" ><a href="#16">対応しているブラウザは？</a></td>
                        </tr>
                        
                      </table></td>
                    </tr>
                    <tr> 
                      <td><img src="images/common/spacer.gif" width="10" height="30" /></td>
                    </tr>
                    <tr> 
                      <td> 
                        <hr size="1" noshade>                      </td>
                    </tr>
                    <tr> 
                      <td><img src="images/common/spacer.gif" width="10" height="15" /></td>
                    </tr>
                    <tr> 
                      <td class="right_menu_title">■ Myページとは？<a name="1"></a></td>
                    </tr>
                    <tr> 
                      <td><img src="images/mkk_faq/img_dot.gif" width="553" height="11"></td>
                    </tr>
                    <tr> 
                      <td> 
                        <p>きかせて・netがご提供するサービスを使用することができるマーケター専用ページです。</p>                      </td>
                    </tr>
                    <tr> 
                      <td><img src="images/common/spacer.gif" width="10" height="15" /></td>
                    </tr>
                    <tr> 
                      <td align="right"><a href="#top"><img src="images/common/go_top.gif" alt="GO TOP" width="57" height="11" border="0"></a></td>
                    </tr>
                    <tr> 
                      <td><img src="images/common/spacer.gif" width="10" height="15" /></td>
                    </tr>
                    <tr> 
                      <td class="right_menu_title">■ Myページ でできることは？<a name="2"></a></td>
                    </tr>
                    <tr> 
                      <td><img src="images/mkk_faq/img_dot.gif" width="553" height="11"></td>
                    </tr>
                    <tr> 
                      <td> 
                        <p>以下のサービスがこのページでできます。</p>
                        <p>&gt;&gt; Myアンケート<br>
                          あなたのパソコンから直接、アンケートを発信しデータを収集することができます<br>
                          &gt;&gt; Myパートナー<br>
                          あなたのパソコンから直接、話題の投げかけを行えるオンライン会議室です。<br>
                          &gt;&gt; Myサーチ<br>
                          ここに条件を登録しておけば、刻一刻と変化するモニター数をウォッチできます。 <br>
                          &gt;&gt; Myエージェント<br>
                          あなたの専属のエージェントです。お呼び頂ければ、いつでもお返事いたします。<br>
                          &gt;&gt; Proアンケート<br>
                          スクリーニング（事前）調査後に条件を満たす回答者のみに本調査ができます。</p>                      </td>
                    </tr>
                    <tr> 
                      <td><img src="images/common/spacer.gif" width="10" height="15" /></td>
                    </tr>
                    <tr> 
                      <td align="right"><a href="#top"><img src="images/common/go_top.gif" alt="GO TOP" width="57" height="11" border="0"></a></td>
                    </tr>
                    <tr> 
                      <td><img src="images/common/spacer.gif" width="10" height="15" /></td>
                    </tr>
                    <tr> 
                      <td class="right_menu_title">■ リサーチパートナーとは？<a name="3"></a></td>
                    </tr>
                    <tr> 
                      <td><img src="images/mkk_faq/img_dot.gif" width="553" height="11"></td>
                    </tr>
                    <tr> 
                      <td> 
                        <p>きかせて・net リサーチパートナーは、「定性情報」と「デジタルネットワーキングリテラシー」について、トレーニングを受けています。<br>
                          <br>
                          定性情報を書く時、「事実＋仮説＝意見」という事を理解していて、マーケターの望むモードで定性データを書き分けられる人たちです。<br>
                          また、インターネット上でコミュニケーションする時のリテラシーについても理解しています。<br>
                          <br>
                          リサーチパートナーは、 安心して聞くことができる／生々しい声を聞くことができる、そんな1ランク上のパートナーです。</p>                      </td>
                    </tr>
                    <tr> 
                      <td><img src="images/common/spacer.gif" width="10" height="15" /></td>
                    </tr>
                    <tr> 
                      <td align="right"><a href="#top"><img src="images/common/go_top.gif" alt="GO TOP" width="57" height="11" border="0"></a></td>
                    </tr>
                    <tr> 
                      <td><img src="images/common/spacer.gif" width="10" height="15" /></td>
                    </tr>
                    <tr> 
                      <td class="right_menu_title">■ 登録にはどんな種類がある？<a name="4"></a></td>
                    </tr>
                    <tr> 
                      <td><img src="images/mkk_faq/img_dot.gif" width="553" height="11"></td>
                    </tr>
                    <tr> 
                      <td> 
                        <p><a href="mk/regist/mk_regist.php#mypage" target="_blank">&gt;&gt;Myページ登録</a><br>
                          専属エージェント・Myメニューなど、自分仕様のサービスが受けられます。 メールニュースも届きます。<br>
                          <br>
                          <a href="mk/regist/mk_regist.php#mail" target="_blank">&gt;&gt;メールニュース登録                          </a><br>
                          メールアドレスを登録するだけで、きかせて・net エージェントの発行するメールニュース「きかせて・net 
                          通信 for eMarketers」が届きます。</p>                      </td>
                    </tr>
                    <tr> 
                      <td><img src="images/common/spacer.gif" width="10" height="15" /></td>
                    </tr>
                    <tr> 
                      <td align="right"><a href="#top"><img src="images/common/go_top.gif" alt="GO TOP" width="57" height="11" border="0"></a></td>
                    </tr>
                    <tr> 
                      <td><img src="images/common/spacer.gif" width="10" height="15" /></td>
                    </tr>
                    <tr> 
                      <td class="right_menu_title">■ 登録に費用はかかる？<a name="5"></a></td>
                    </tr>
                    <tr> 
                      <td><img src="images/mkk_faq/img_dot.gif" width="553" height="11"></td>
                    </tr>
                    <tr> 
                      <td> 
                        <p>登録は、無料でできます。</p>                      </td>
                    </tr>
                    <tr> 
                      <td><img src="images/common/spacer.gif" width="10" height="15" /></td>
                    </tr>
                    <tr> 
                      <td align="right"><a href="#top"><img src="images/common/go_top.gif" alt="GO TOP" width="57" height="11" border="0"></a></td>
                    </tr>
                    <tr> 
                      <td><img src="images/common/spacer.gif" width="10" height="15" /></td>
                    </tr>
                    <tr> 
                      <td class="right_menu_title">■ エラーメッセージが出て先に進めない<a name="6"></a></td>
                    </tr>
                    <tr> 
                      <td><img src="images/mkk_faq/img_dot.gif" width="553" height="11"></td>
                    </tr>
                    <tr> 
                      <td> 
                        <p>お手数ですが、詳細を事務局までご連絡下さい。<br>
                          その際、<br>
                          <br>
                          ・エラーメッセージの内容<br>
                          ・お使いのＰＣのＯＳ(＋バージョン)<br>
                          ・お使いのブラウザ(＋バージョン)<br>
                          ・通信環境<br>
                          を併せてお知らせ下さい。<br>
                          <br>
                          事務局メールアドレス<br>
                          <a href="mailto:k-agent@kikasete.net?subject=%81%a1%82%a8%96%e2%82%a2%8d%87%82%ed%82%b9">k-agent@kikasete.net</a></p>                      </td>
                    </tr>
                    <tr> 
                      <td><img src="images/common/spacer.gif" width="10" height="15" /></td>
                    </tr>
                    <tr> 
                      <td align="right"><a href="#top"><img src="images/common/go_top.gif" alt="GO TOP" width="57" height="11" border="0"></a></td>
                    </tr>
                    <tr> 
                      <td><img src="images/common/spacer.gif" width="10" height="15" /></td>
                    </tr>
                    <tr> 
                      <td class="right_menu_title">■ 登録情報は変更できる？<a name="7"></a></td>
                    </tr>
                    <tr> 
                      <td><img src="images/mkk_faq/img_dot.gif" width="553" height="11"></td>
                    </tr>
                    <tr> 
                      <td> 
                        <p>はい、登録情報の更新から変更して下さい。<br>
                          ご登録の全ての情報を変更することができます。 <br>
                          <br>
                        </p>
                        <p>メールニュース登録の方は、お手数ですが、一旦登録を解除されてから新しいメールアドレスでご登録下さい。                        </p>                      </td>
                    </tr>
                    <tr> 
                      <td><img src="images/common/spacer.gif" width="10" height="15" /></td>
                    </tr>
                    <tr> 
                      <td align="right"><a href="#top"><img src="images/common/go_top.gif" alt="GO TOP" width="57" height="11" border="0"></a></td>
                    </tr>
                    <tr> 
                      <td><img src="images/common/spacer.gif" width="10" height="15" /></td>
                    </tr>
                    <tr> 
                      <td class="right_menu_title">■ 登録を解除するには？<a name="8"></a></td>
                    </tr>
                    <tr> 
                      <td><img src="images/mkk_faq/img_dot.gif" width="553" height="11"></td>
                    </tr>
                    <tr> 
                      <td> 
                        <p>それぞれの退会メニューから手続きを行ってください。手続きはWEB上で完了します。<br>
                          <br>
                          ・Myページユーザーの方 <br>
                          Myページの「退会」メニューから手続きを行ってください。<br>
                          （メールニュースのみの解約はできませんのでご了承ください。）<br>
                          <br>
                          ・メールニュース登録のみ方 <br>
                          <a href="mk/regist/mk_mnresign.php" target="_blank">こちら</a>から手続きを行ってください。</p>                      </td>
                    </tr>
                    <tr> 
                      <td><img src="images/common/spacer.gif" width="10" height="15" /></td>
                    </tr>
                    <tr> 
                      <td align="right"><a href="#top"><img src="images/common/go_top.gif" alt="GO TOP" width="57" height="11" border="0"></a></td>
                    </tr>
                    <tr> 
                      <td><img src="images/common/spacer.gif" width="10" height="15" /></td>
                    </tr>
                    <tr> 
                      <td class="right_menu_title">■ エージェントとは？<a name="9"></a></td>
                    </tr>
                    <tr> 
                      <td><img src="images/mkk_faq/img_dot.gif" width="553" height="11"></td>
                    </tr>
                    <tr> 
                      <td> 
                        <p>株式会社○○○○○○のスタッフです。<br>
                          マーケターの問題意識や課題について、日々お話しをお聞きしながら 定量調査はもちろん、定性調査を軸にベストなリサーチプランを 
                          ご提案いたします。</p>                      </td>
                    </tr>
                    <tr> 
                      <td><img src="images/common/spacer.gif" width="10" height="15" /></td>
                    </tr>
                    <tr> 
                      <td align="right"><a href="#top"><img src="images/common/go_top.gif" alt="GO TOP" width="57" height="11" border="0"></a></td>
                    </tr>
                    <tr> 
                      <td><img src="images/common/spacer.gif" width="10" height="15" /></td>
                    </tr>
                    <tr> 
                      <td class="right_menu_title">■ エージェントは何をしてもらえる？<a name="10"></a></td>
                    </tr>
                    <tr> 
                      <td><img src="images/mkk_faq/img_dot.gif" width="553" height="11"></td>
                    </tr>
                    <tr> 
                      <td> 
                        <p>サイト内のサービスについてのサポートから、あなたのマーケティングのお手伝 いまでさせて頂きます。<br>
                          <br>
                          <strong>『専属エージェント』とは ・・・</strong> <br>
                          あなたの専属のエージェントです。<br>
                          Myページからお呼び頂ければ、いつでもお返事いたします。<br>
                          継続的にお話を聞かせていただき、話題やテーマの共有をさせていただくことが可能です。<br>
                          その中でベストなリサーチプランをご提案し、マーケティング業務の一部をサポートさせていただきます。</p>                      </td>
                    </tr>
                    <tr> 
                      <td><img src="images/common/spacer.gif" width="10" height="15" /></td>
                    </tr>
                    <tr> 
                      <td align="right"><a href="#top"><img src="images/common/go_top.gif" alt="GO TOP" width="57" height="11" border="0"></a></td>
                    </tr>
                    <tr> 
                      <td><img src="images/common/spacer.gif" width="10" height="15" /></td>
                    </tr>
                    <tr> 
                      <td class="right_menu_title">■ 専属エージェントを代えることはできる？<a name="11"></a></td>
                    </tr>
                    <tr> 
                      <td><img src="images/mkk_faq/img_dot.gif" width="553" height="11"></td>
                    </tr>
                    <tr> 
                      <td> 
                        <p>エージェントは変更をしていただくことができます。 <br>
                          エージェントの変更よりお好きなエージェントをお選びください。 </p>                      </td>
                    </tr>
                    <tr> 
                      <td><img src="images/common/spacer.gif" width="10" height="15" /></td>
                    </tr>
                    <tr> 
                      <td align="right"><a href="#top"><img src="images/common/go_top.gif" alt="GO TOP" width="57" height="11" border="0"></a></td>
                    </tr>
                    <tr> 
                      <td><img src="images/common/spacer.gif" width="10" height="15" /></td>
                    </tr>
                    <tr> 
                      <td class="right_menu_title">■ 調査は誰に依頼すればいい？<a name="12"></a></td>
                    </tr>
                    <tr> 
                      <td><img src="images/mkk_faq/img_dot.gif" width="553" height="11"></td>
                    </tr>
                    <tr> 
                      <td> 
                        <p>きかせて・net のエージェントにお気軽にご相談ください。<br>
                          調査のご相談でも、それ以前のお悩みでも結構です。<br>
                          日々インターネットマーケティングの現場にいるエージェントに是非ご相談ください。<br>
                          <br>
                          <a href="#9">&gt;&gt;エージェントとは</a><br>
                          <a href="mk/kkstfrom/mk_contact.php" target="_blank">&gt;&gt;お問い合わせ窓口</a></p>                      </td>
                    </tr>
                    <tr> 
                      <td><img src="images/common/spacer.gif" width="10" height="15" /></td>
                    </tr>
                    <tr> 
                      <td align="right"><a href="#top"><img src="images/common/go_top.gif" alt="GO TOP" width="57" height="11" border="0"></a></td>
                    </tr>
                    <tr> 
                      <td><img src="images/common/spacer.gif" width="10" height="15" /></td>
                    </tr>
                    <tr> 
                      <td class="right_menu_title">■ 調査は誰が実施する？<a name="13"></a></td>
                    </tr>
                    <tr> 
                      <td><img src="images/mkk_faq/img_dot.gif" width="553" height="11"></td>
                    </tr>
                    <tr> 
                      <td> 
                        <p>きかせて・net を運営している、株式会社○○○○○○の調査実施部門が担当いたします。<br>
                          エージェント同様、インターネットマーケティングに日々携わっているプロフェッショナルです。<br>
                          <br>
                          株式会社○○○○○○が提供するサービスについては<a href="mk/about/mk_about.php" target="_blank">こちら</a>をご覧下さい。<br>
                          <a href="http://www.xxxxxxx.co.jp/" target="_blank">&gt;&gt;株式会社○○○○○○について</a></p>                      </td>
                    </tr>
                    <tr> 
                      <td><img src="images/common/spacer.gif" width="10" height="20" /></td>
                    </tr>
                    <tr> 
                      <td align="right"><a href="#top"><img src="images/common/go_top.gif" alt="GO TOP" width="57" height="11" border="0"></a></td>
                    </tr>
                    <tr> 
                      <td><img src="images/common/spacer.gif" width="10" height="15" /></td>
                    </tr>
                    <tr> 
                      <td class="right_menu_title">■ <a name="14"></a>Web画面に表示されているアンケート結果をA4で印刷しようとすると、ページの右端が切れてしまう。</td>
                    </tr>
                    <tr> 
                      <td><img src="images/mkk_faq/img_dot.gif" width="553" height="11"></td>
                    </tr>
                    <tr>
                      <td><p>Web画面に表示されているアンケート結果の印刷プレビューを見ると、<br />
                        印刷用紙の余白を最小限にしたにも関わらず、右端が切れてしまっている場合、<br />
  <br />
                        印刷方法1：
  <br />
                        ページ設定をA4横にして印刷する。<br />
  <br />
                        印刷方法2：
  <br />
                      印刷プレビューのページ設定で、原稿サイズを<strong>A3</strong>に、出力用紙サイズを<strong>A4</strong>にして印刷してください。</p>                      </td>
                    </tr>
                    <tr> 
                      <td><img src="images/common/spacer.gif" width="10" height="20" /></td>
                    </tr>
                    <tr> 
                      <td align="right"><a href="#top"><img src="images/common/go_top.gif" alt="GO TOP" width="57" height="11" border="0"></a></td>
                    </tr>
                    <tr> 
                      <td><img src="images/common/spacer.gif" width="10" height="15" /></td>
                    </tr>
                    <tr> 
                      <td class="right_menu_title">■ Web画面の線や背景が印刷されない。<a name="15"></a></td>
                    </tr>
                    <tr> 
                      <td><img src="images/mkk_faq/img_dot.gif" width="553" height="11"></td>
                    </tr>
                    <tr>
                      <td><p>インターネットエクスプローラの、メニューバーの、「ツール」の中の、「インターネットオプション」をクリック。</p>
                        <p>「詳細設定」タブの中の、印刷「背景の色とイメージを印刷する」にチェックを入れてください。<br />
                          <img src="images/mkk_faq/img15.gif" alt="インターネットオプション" width="300" height="267" /></p></td>
                    </tr>
                    <tr> 
                      <td><img src="images/common/spacer.gif" width="10" height="20" /></td>
                    </tr>
                    <tr> 
                      <td align="right"><a href="#top"><img src="images/common/go_top.gif" alt="GO TOP" width="57" height="11" border="0"></a></td>
                    </tr>
                    <tr>
                      <td><img src="images/common/spacer.gif" width="10" height="15" /></td>
                    </tr>
                    <tr>
                      <td class="right_menu_title">■ 対応しているブラウザは？<a name="16" id="16"></a></td>
                    </tr>
                    <tr>
                      <td><img src="images/mkk_faq/img_dot.gif" width="553" height="11" /></td>
                    </tr>
                    <tr>
                      <td><p>Internet Explorer バージョン5.0〜6.Xを推奨ブラウザとしております。</p>
                        <p>※2006年11月現在、 Internet Explorer7（IE 7）は推奨ブラウザとしておりません。<br />
                          IE 7においては、デザインの崩れや、アンケート作成が正常にできない場合もございます。<br />
                          あらかじめご了承ください。<br />
                        </p>
                        <p> ■Internet Explorer 7 のサポートページ<br />
                          <a href="http://www.microsoft.com/japan/windows/ie/support/default.mspx" target="_blank">http://www.microsoft.com/japan/windows/ie/support/default.mspx</a></p></td>
                    </tr>
                    <tr>
                      <td><img src="images/common/spacer.gif" width="10" height="20" /></td>
                    </tr>
                    <tr>
                      <td align="right"><a href="#top"><img src="images/common/go_top.gif" alt="GO TOP" width="57" height="11" border="0" /></a></td>
                    </tr>					

                  </table>
                </td>
              </tr>
              <tr> 
                <td><img src="images/common/spacer.gif" width="10" height="30"></td>
              </tr>
            </table>
</td>
</tr>
</table>
</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="10"></td>
</tr>
</table>
<!--footer start-->

<? marketer_footer(BLUE) ?>
