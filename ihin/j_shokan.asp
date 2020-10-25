<html>

<!--#include file="common.asp"-->

<head>
<meta http-equiv="Content-Type" content="text/html; charset=shift_jis">
<meta name="GENERATOR" content="Microsoft FrontPage 4.0">
<meta name="ProgId" content="FrontPage.Editor.Document">
<title>書簡：検索条件入力</title>


<meta name="Microsoft Theme" content="mstheme22243 011, default"><meta name="Microsoft Border" content="t, default"></head>

<body background="_themes/mstheme22243/sumtextb.jpg" bgcolor="#FFFFFF" text="#000066" link="#3333CC" vlink="#3333CC" alink="#990099"><!--msnavigation--><table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td><!--mstheme--><font face="ＭＳ Ｐゴシック">

<p align="left"><font size="6"><strong><img src="_derived/j_shokan.asp_cmp_mstheme22243010_bnr.gif" width="600" height="60" border="0" alt="書簡"></strong></font></p>

<!--mstheme--></font></td></tr><!--msnavigation--></table><!--msnavigation--><table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><!--msnavigation--><td valign="top"><!--mstheme--><font face="ＭＳ Ｐゴシック"><form method="POST" action="ichiran.asp">
  <p>　</p>
  <!--mstheme--></font><table border="0" width="357" height="127" style="margin-left: 70">
    <tr>
      <td width="92" height="23"><!--mstheme--><font face="ＭＳ Ｐゴシック">宛先<!--mstheme--></font></td>
      <td width="249" height="23"><!--mstheme--><font face="ＭＳ Ｐゴシック"><select size="1" name="ZOKUSEI1">
      	<%Call SetItemList("3", Request("cb"))%>
        </select><!--mstheme--></font></td>
    </tr>
    <tr>
      <td width="92" height="23"><!--mstheme--><font face="ＭＳ Ｐゴシック">年月日<!--mstheme--></font></td>
      <td width="249" height="23"><!--mstheme--><font face="ＭＳ Ｐゴシック"><input type="text" name="Zokusei2F" size="14">～<input type="text" name="Zokusei2T" size="14"><!--mstheme--></font></td>
    </tr>
    <tr>
      <td width="92" height="63"><!--mstheme--><font face="ＭＳ Ｐゴシック"><!--mstheme--></font></td>
      <td width="249" height="63"><!--mstheme--><font face="ＭＳ Ｐゴシック"><input type="submit" value="検索実行" name="B1">
      <input type="reset" value="リセット" name="B2"><!--mstheme--></font></td> 
    </tr> 
  </table><!--mstheme--><font face="ＭＳ Ｐゴシック"><input type="hidden" name="Chubunrui" value="<%=Request("cb")%>"><input type="hidden" name="Daibunrui" value="3"> 
</form> 
<!--msthemeseparator--><p align="left"><img src="_themes/mstheme22243/sumhorsa.gif" width="600" height="10"></p> 
<a href="default.asp"><img border="0" src="images/b11_036.gif" width="90" height="22"></a> 
<p>　</p> 
<!--mstheme--></font><!--msnavigation--></td></tr><!--msnavigation--></table></body> 
 
</html> 
