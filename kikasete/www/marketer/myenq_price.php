<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/mr_permission.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");

// ���å�����ѿ����
unset($_SESSION['ss_my_enq']);
session_unregister('ss_my_enq');
?>
<? marketer_header('�������ơ�net �ͥåȥꥵ�����˥ץ�Υ���������Ȥ�', PG_NULL) ?>

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

<? marketer_menu(M_MYENQ); ?>

</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="10" /> 
<td valign="top"> 
<table width="553" border="0" cellpadding="0" cellspacing="0">
<tr> 
<td width="553"><img src="images/mkk_myenq/title01.gif" alt="MY MENU" width="553" height="25"></td>
              </tr>
              <tr> 
                <td><img src="images/common/spacer.gif" width="10" height="10"></td>
              </tr>
              <tr> 
                <td><img src="images/mkk_myenq/title02.gif" alt="��My���󥱡��ȡ۴�ñ�˰¤�����ʤʥͥåȥꥵ���������󥱡���Ĵ����̤ϥޡ����ƥ��󥰤Υץ쥼������Ǹ���Ū��" width="114" height="28"> 
                </td>
              </tr>
              <tr> 
                <td><img src="images/common/spacer.gif" width="10" height="10" /></td>
              </tr>
              <tr> 
                <td><img src="images/mkk_myenq/title08.gif" alt="�ͣ����󥱡�������ɽ" width="553" height="24"></td>
              </tr>
              <tr> 
                <td><img src="images/common/spacer.gif" width="10" height="10"></td>
              </tr>
              <tr> 
                <td>�������Ǝ�net��˥������оݤȤ����ꥵ���������ӥ��Ǥ���<br>
				���ܥץ��ϡ֣��䡢��������ȯ���������ߡפǤ������ץ����Ǽ������ȯ�������ɲä��Ǥ��ޤ���<br>
                  ����Ψ��ʿ�Ѥ�40%�Ǥ�������Ǥ�����������ܰ¤�<a href="mk/service/mk_myenq_price_sample.php" target="_blank">������</a>��������������</td>
              </tr>			  
               <tr> 
                <td><img src="images/common/spacer.gif" width="10" height="20"></td>
              </tr>
              <tr> 
                <td class="footer_text"><img src="images/mp_pjt_list/title06.gif" alt="��������" width="176" height="15"></td>
              </tr>
			                <tr> 
                <td class="footer_text" align="right">(���)</td>
              </tr>
			                <tr> 
                <td bgcolor="#dadada"> 
                  <Table Border="0" Width="100%" CellSpacing="1" CellPadding="5">
                    <Tr> 
                      <Td BgColor="#e5eaf0" ColSpan="2" RowSpan="2">&nbsp;</Td>
                      <Td Align="center" Valign="middle" BgColor="#e5eaf0" ColSpan="10">�����</Td>
                    </Tr>
                    <Tr> 
                      <Td Align="center" BgColor="#e5eaf0">3</Td>
                      <Td Align="center" BgColor="#e5eaf0">4</Td>
                      <Td Align="center" BgColor="#e5eaf0">5</Td>
                      <Td Align="center" BgColor="#e5eaf0">6</Td>
                      <Td Align="center" BgColor="#e5eaf0">7</Td>
                      <Td Align="center" BgColor="#e5eaf0">8</Td>
                      <Td Align="center" BgColor="#e5eaf0">9</Td>
                      <Td Align="center" BgColor="#e5eaf0">10</Td>
                    </Tr>
                    <Tr> 
                      <Td Align="center" Valign="middle" BgColor="#e5eaf0" RowSpan="10">ȯ<br>
                        ��<br>
                        ��<br>                      </Td>
                      <Td BgColor="#FFFFFF" Align="center">300</Td>
                      <Td BgColor="#FFFFFF" align="right">30</Td>
                      <Td BgColor="#FFFFFF" align="right">34</Td>
                      <Td BgColor="#FFFFFF" align="right">38</Td>
                      <Td BgColor="#FFFFFF" align="right">42</Td>
                      <Td BgColor="#FFFFFF" align="right">46</Td>
                      <Td BgColor="#FFFFFF" align="right">51</Td>
                      <Td BgColor="#FFFFFF" align="right">55</Td>
                      <Td BgColor="#FFFFFF" align="right">59</Td>
                    </Tr>
                    <Tr> 
                      <Td BgColor="#FFFFFF" Align="center">600</Td>
                      <Td BgColor="#FFFFFF" align="right">54</Td>
                      <Td BgColor="#FFFFFF" align="right">61</Td>
                      <Td BgColor="#FFFFFF" align="right">68</Td>
                      <Td BgColor="#FFFFFF" align="right">71</Td>
                      <Td BgColor="#FFFFFF" align="right">74</Td>
                      <Td BgColor="#FFFFFF" align="right">77</Td>
                      <Td BgColor="#FFFFFF" align="right">80</Td>
                      <Td BgColor="#FFFFFF" align="right">83</Td>
                    </Tr>
                    <Tr> 
                      <Td BgColor="#FFFFFF" Align="center">900</Td>
                      <Td BgColor="#FFFFFF" align="right">59</Td>
                      <Td BgColor="#FFFFFF" align="right">66</Td>
                      <Td BgColor="#FFFFFF" align="right">73</Td>
                      <Td BgColor="#FFFFFF" align="right">80</Td>
                      <Td BgColor="#FFFFFF" align="right">87</Td>
                      <Td BgColor="#FFFFFF" align="right">94</Td>
                      <Td BgColor="#FFFFFF" align="right">101</Td>
                      <Td BgColor="#FFFFFF" align="right">106</Td>
                    </Tr>
                    <Tr> 
                      <Td BgColor="#FFFFFF" Align="center">1200</Td>
                      <Td BgColor="#FFFFFF" align="right">79</Td>
                      <Td BgColor="#FFFFFF" align="right">88</Td>
                      <Td BgColor="#FFFFFF" align="right">97</Td>
                      <Td BgColor="#FFFFFF" align="right">106</Td>
                      <Td BgColor="#FFFFFF" align="right">115</Td>
                      <Td BgColor="#FFFFFF" align="right">124</Td>
                      <Td BgColor="#FFFFFF" align="right">133</Td>
                      <Td BgColor="#FFFFFF" align="right">141</Td>
                    </Tr>
                    <Tr> 
                      <Td BgColor="#FFFFFF" Align="center">1500</Td>
                      <Td BgColor="#FFFFFF" align="right">94</Td>
                      <Td BgColor="#FFFFFF" align="right">104</Td>
                      <Td BgColor="#FFFFFF" align="right">114</Td>
                      <Td BgColor="#FFFFFF" align="right">124</Td>
                      <Td BgColor="#FFFFFF" align="right">134</Td>
                      <Td BgColor="#FFFFFF" align="right">144</Td>
                      <Td BgColor="#FFFFFF" align="right">154</Td>
                      <Td BgColor="#FFFFFF" align="right">166</Td>
                    </Tr>
                    <Tr> 
                      <Td BgColor="#FFFFFF" Align="center">1800</Td>
                      <Td BgColor="#FFFFFF" align="right">114</Td>
                      <Td BgColor="#FFFFFF" align="right">127</Td>
                      <Td BgColor="#FFFFFF" align="right">140</Td>
                      <Td BgColor="#FFFFFF" align="right">153</Td>
                      <Td BgColor="#FFFFFF" align="right">166</Td>
                      <Td BgColor="#FFFFFF" align="right">179</Td>
                      <Td BgColor="#FFFFFF" align="right">192</Td>
                      <Td BgColor="#FFFFFF" align="right">204</Td>
                    </Tr>
                    <Tr> 
                      <Td BgColor="#FFFFFF" Align="center">2100</Td>
                      <Td BgColor="#FFFFFF" align="right">125</Td>
                      <Td BgColor="#FFFFFF" align="right">139</Td>
                      <Td BgColor="#FFFFFF" align="right">153</Td>
                      <Td BgColor="#FFFFFF" align="right">167</Td>
                      <Td BgColor="#FFFFFF" align="right">181</Td>
                      <Td BgColor="#FFFFFF" align="right">195</Td>
                      <Td BgColor="#FFFFFF" align="right">209</Td>
                      <Td BgColor="#FFFFFF" align="right">223</Td>
                    </Tr>
                    <Tr> 
                      <Td BgColor="#FFFFFF" Align="center">2400</Td>
                      <Td BgColor="#FFFFFF" align="right">145</Td>
                      <Td BgColor="#FFFFFF" align="right">161</Td>
                      <Td BgColor="#FFFFFF" align="right">177</Td>
                      <Td BgColor="#FFFFFF" align="right">193</Td>
                      <Td BgColor="#FFFFFF" align="right">209</Td>
                      <Td BgColor="#FFFFFF" align="right">225</Td>
                      <Td BgColor="#FFFFFF" align="right">241</Td>
                      <Td BgColor="#FFFFFF" align="right">258</Td>
                    </Tr>
                    <Tr> 
                      <Td BgColor="#FFFFFF" Align="center">2700</Td>
                      <Td BgColor="#FFFFFF" align="right">163</Td>
                      <Td BgColor="#FFFFFF" align="right">181</Td>
                      <Td BgColor="#FFFFFF" align="right">199</Td>
                      <Td BgColor="#FFFFFF" align="right">217</Td>
                      <Td BgColor="#FFFFFF" align="right">235</Td>
                      <Td BgColor="#FFFFFF" align="right">253</Td>
                      <Td BgColor="#FFFFFF" align="right">271</Td>
                      <Td BgColor="#FFFFFF" align="right">290</Td>
                    </Tr>
                    <Tr> 
                      <Td BgColor="#FFFFFF" Align="center">3000</Td>
                      <Td BgColor="#FFFFFF" align="right">168</Td>
                      <Td BgColor="#FFFFFF" align="right">187</Td>
                      <Td BgColor="#FFFFFF" align="right">206</Td>
                      <Td BgColor="#FFFFFF" align="right">225</Td>
                      <Td BgColor="#FFFFFF" align="right">244</Td>
                      <Td BgColor="#FFFFFF" align="right">263</Td>
                      <Td BgColor="#FFFFFF" align="right">282</Td>
                      <Td BgColor="#FFFFFF" align="right">301</Td>
                    </Tr>
                  </Table>
                </td>
              </tr>
              <tr> 
                <td><img src="images/common/spacer.gif" width="10" height="20"></td>
              </tr>
              <tr> 
                <td><img src="images/mkk_myenq/title09.gif" alt="���ץ����" width="125" height="15"></td>
              </tr>
              <tr> 
                <td><img src="images/common/spacer.gif" width="10" height="10"></td>
              </tr>
              <tr> 
                <td bgcolor="#dadada"> 
                  <table border=0 cellspacing=1 cellpadding=5 width="100%" class="nor_text">
                    <tr bgcolor="#e5eaf0"> 
                      <td width="20%" align="center">�����ӥ�</td>
                      <td width="60%" align="center">����</td>
                      <td width="20%" align="center">����</td>
                    </tr>
                    <tr> 
                      <td bgcolor="#FFFFFF" width="20%" align="center">����������ȥѥå�</td>
                      <td bgcolor="#FFFFFF" width="60%">My���󥱡��Ȥδ��ܥץ���3�䡢300���ۿ���3���ߡˤΥѥå����������ӥ��Ǥ������ܥץ���5��ʬ�ޤȤ�Ƥ���ᤤ�������ޤ��ȡ�1��ʬ�����ˤʤ�ޤ������Ū�ʥꥵ�����򤴷ײ褵��Ƥ������ˤϤԤä���Ǥ������̾�150,000�ߢ�120,000�ߡ� 
                        �����ץ����ʼ������ȯ�������ɲáˤ����Ѥξ��ˤϡ��嵭����ɽ�Ȥκ���ʬ�Τߤ����ᤵ���Ƥ��������ޤ�����ͭ�����֤ϡ��ѥå��������������ޤ��6����֤Ȥ����Ƥ��������ޤ���</td>
                      <td bgcolor="#FFFFFF" align="center" width="20%">120,000��</td>
                    </tr>
                    <tr> 
                      <td bgcolor="#FFFFFF" width="20%" align="center">ȯ��������<br>
                        �ʥ�������</td>
                      <td bgcolor="#FFFFFF" width="60%">��ǯ���̤�ȯ�������յ�ǽ�����Ѥ�����������������Ǥ��� <br />
                      ���ε�ǽ�򤪻Ȥ��ξ��ˤΤߡ����ץ����ǽ�Ȥ��ơ�̤������ �⤷���ϡֵｻ�ϰ�פ�ȯ����������̵���Ǥ����Ѥ��������ޤ���</td>
                      <td bgcolor="#FFFFFF" align="center" width="20%">5,000��/1����</td>
                    </tr>                    
                    <tr>
                      <td bgcolor="#FFFFFF" align="center">°���ɲ�</td>
                      <td bgcolor="#FFFFFF">�оݾ������ǡֻҶ���̵ͭ�ס�Ʊ���²�פ��оݼԹʹ��߾��Ȥ��� �����Ѥ������������Τ��줾�������Ǥ���</td>
                      <td bgcolor="#FFFFFF" align="center">5,000��/1°��</td>
                    </tr>
                    <tr> 
                      <td bgcolor="#FFFFFF" width="20%" align="center">��������</td>
                      <td bgcolor="#FFFFFF" width="60%">��̳�ɤ���������������ꤹ��������ѤǤ���</td>
                      <td bgcolor="#FFFFFF" align="center" width="20%">20,000��</td>
                    </tr>                    <tr> 
                      <td bgcolor="#FFFFFF" width="20%" align="center" height="51">���ԡ��ɥ��㡼��</td>
                      <td bgcolor="#FFFFFF" width="60%" height="51">AM11����᤮�Ƥο����ǡ�����ȯ���򤷤������ϥ��ԡ��ɥ��㡼���򤤤������ޤ���������16���ʹߤǤΤ������դ�����Ķ�����AMȯ���Ȥʤ�ޤ���</td>
                      <td bgcolor="#FFFFFF" align="center" width="20%" height="51">10,000��</td>
                    </tr>                    <tr> 
                      <td bgcolor="#FFFFFF" width="20%" align="center">�����ù���</td>
                      <td bgcolor="#FFFFFF" width="60%">�����򸫤��ƥ��󥱡��Ȥ򤷤���������̳�ɤǲ�����ù�������ˤ��������ѤǤ���</td>
                      <td bgcolor="#FFFFFF" align="center" width="20%">3,000��</td>
                    </tr>                    <tr> 
                      <td bgcolor="#FFFFFF" width="20%" align="center">������������</td>
                      <td bgcolor="#FFFFFF" width="60%">���󥱡��Ȥǲ����򸫤�����Υ����С��������Ǥ���</td>
                      <td bgcolor="#FFFFFF" align="center" width="20%">5,000��</td>
                    </tr>                    <tr> 
                      <td bgcolor="#FFFFFF" width="20%" align="center">���ý�����</td>
                      <td bgcolor="#FFFFFF" width="60%">2°���֤ޤǤΥ������פ���Ԥ������ޤ�����Ǽ�ʷ��֤ϡ�����ɼ�ȥ���դǤ���Excel�����ˤƤ�Ǽ�ʤ������ޤ���</td>
                      <td bgcolor="#FFFFFF" align="center" width="20%">30,000��</td>
                    </tr>
                  </table>
                </td>
              </tr>
			   <tr> 
                <td class="footer_text" align="right">���嵭���������̤Ǥ���</td>
              </tr>
              <tr> 
                <td><img src="images/common/spacer.gif" width="10" height="20"></td>
              </tr>
              <tr> 
                <td align="right"><a href="#top"><img src="images/common/go_top.gif" alt="GO TOP" width="57" height="11" border="0"></a></td>
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


<? marketer_footer (BLUE) ?>
