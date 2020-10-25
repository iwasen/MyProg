//**********************************************************************
//		快楽市場.co.jp バスケットシステム
//
//		Title:		ユーザ登録処理
//		FileName:	UserReg.java
//		Creator:	Shinichi Aizawa
//		CreateDate:	2000/04/14
//**********************************************************************

import java.util.*;
import java.text.*;
import java.io.IOException;
import java.io.PrintWriter;
import java.sql.SQLException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.http.HttpSession;
import javax.servlet.ServletException;

// ユーザ登録ページ処理クラス
public class UserReg extends BaseServlet2
{
	// "GET"メソッド処理
	protected void doGetHandler(HttpServletResponse response, PrintWriter pw, HttpSession session, Param param, Order order)
			throws ServletException, IOException, SQLException {

		Header(pw, "ユーザ登録", "", "bgcolor='#CCFFFF'");
		Body(pw);
		Script(pw);
		Footer(pw);
	}

	// 本体部html出力処理
	private void Body(PrintWriter pw)
			throws ServletException, IOException, SQLException {

		MyUtil myutil = new MyUtil();

		// ユーザ登録情報
		pw.println("<p align='center'><b class='s5'><font color='#FF0000'>快楽市場総合デパート</font>　無料会員登録</b></p>");
		pw.println("<p align='center' class='s1'><b> <span class='s5'>注</span><br>");
		pw.println("</b>18歳未満の方は、ご購入できません。<br>");
		pw.println("快楽市場で商品を購入する際には、18歳以上であるか確認するために、<br>");
		pw.println("あなたの年齢を確認できる物のコピーをＦＡＸ、又は郵送で<br>");
		pw.println("送っていただき、年齢の確認が出来てから、商品の発送となります。<br>");
		pw.println("なお、年齢の確認証明が会員登録後１カ月たってもできない場合には<br>");
		pw.println("ＩＤとパスワードは使用できなくなります。<br>");
		pw.println("なお、年齢が18歳以上と確認されれば、２度目以降はＩＤとパスワードのみで<br>");
		pw.println("買い物が出来るようになります。 </p>");
		pw.println("<form name='form1' method='post' onSubmit='return OnSubmit();' class='s1' >");
		pw.println("<div align='center'>");
		pw.println("<p>※印の項目は、必ず正確にご記入ください。 <br>");
		pw.println("半角カナは使用しないで下さい。<br>");
		pw.println("記入に、間違いがありますと、ご購入できない場合があります。<br>");
		pw.println("</p>");
		pw.println("<table width='700' border='2' cellspacing='1' cellpadding='6' height='400' bgcolor='#FFCCCC' bordercolordark='#666666' bordercolorlight='#FFFFFF' class='s1'>");
		pw.println("<tr>");
		pw.println("<td width='239' nowrap>");
		pw.println("<div align='right'>※ＩＤ</div>");
		pw.println("</td>");
		pw.println("<td width='572'>");
		pw.println("<input type='text' name='uid'>");
		pw.println("８桁以内の半角英数字でお願いします。　例　MYID1234</td>");
		pw.println("</tr>");
		pw.println("<tr>");
		pw.println("<td width='239' nowrap>");
		pw.println("<div align='right'>※ふりがな</div>");
		pw.println("</td>");
		pw.println("<td width='572'>");
		pw.println("<input type='text' name='furigana'>");
		pw.println("全角ひらがなでお願いします。　例　やまだ　たろう</td>");
		pw.println("</tr>");
		pw.println("<tr>");
		pw.println("<td width='239' nowrap>");
		pw.println("<div align='right'>※氏名</div>");
		pw.println("</td>");
		pw.println("<td width='572'>");
		pw.println("<input type='text' name='name'>");
		pw.println("全角漢字でお願いします　　　　例　山田　太郎</td>");
		pw.println("</tr>");
		pw.println("<tr>");
		pw.println("<td width='239' nowrap>");
		pw.println("<div align='right'>※性別</div>");
		pw.println("</td>");
		pw.println("<td width='572'>");
		pw.println("<select name='seibetsu'>");
		pw.println("<option value='男' selected>男</option>");
		pw.println("<option value='女'>女</option>");
		pw.println("</select>");
		pw.println("</td>");
		pw.println("</tr>");
		pw.println("<tr>");
		pw.println("<td width='239' nowrap>");
		pw.println("<div align='right'>※生年月日</div>");
		pw.println("</td>");
		pw.println("<td width='572'>　西暦");
		pw.println("<input type='text' name='seinen' size='10'>");
		pw.println("年");
		pw.println("<select name='seituki'>");
		pw.println("<option>1</option>");
		pw.println("<option>2</option>");
		pw.println("<option>3</option>");
		pw.println("<option>4</option>");
		pw.println("<option>5</option>");
		pw.println("<option>6</option>");
		pw.println("<option>7</option>");
		pw.println("<option>8</option>");
		pw.println("<option>9</option>");
		pw.println("<option>10</option>");
		pw.println("<option>11</option>");
		pw.println("<option>12</option>");
		pw.println("</select>");
		pw.println("月");
		pw.println("<select name='sexxx'>");
		pw.println("<option>1</option>");
		pw.println("<option>2</option>");
		pw.println("<option>3</option>");
		pw.println("<option>4</option>");
		pw.println("<option>5</option>");
		pw.println("<option>6</option>");
		pw.println("<option>7</option>");
		pw.println("<option>8</option>");
		pw.println("<option>9</option>");
		pw.println("<option>10</option>");
		pw.println("<option>11</option>");
		pw.println("<option>12</option>");
		pw.println("<option>13</option>");
		pw.println("<option>14</option>");
		pw.println("<option>15</option>");
		pw.println("<option>16</option>");
		pw.println("<option>17</option>");
		pw.println("<option>18</option>");
		pw.println("<option>19</option>");
		pw.println("<option>20</option>");
		pw.println("<option>21</option>");
		pw.println("<option>22</option>");
		pw.println("<option>23</option>");
		pw.println("<option>24</option>");
		pw.println("<option>25</option>");
		pw.println("<option>26</option>");
		pw.println("<option>27</option>");
		pw.println("<option>28</option>");
		pw.println("<option>29</option>");
		pw.println("<option>30</option>");
		pw.println("<option>31</option>");
		pw.println("</select>");
		pw.println("日 </td>");
		pw.println("</tr>");
		pw.println("<tr>");
		pw.println("<td width='239' nowrap>");
		pw.println("<div align='right'>職業</div>");
		pw.println("</td>");
		pw.println("<td width='572'>");
		myutil.ShokugyouSelect(pw, "", null, null);
		pw.println("</td>");
		pw.println("</tr>");
		pw.println("<tr>");
		pw.println("<td width='239' nowrap>");
		pw.println("<div align='right'>※郵便番号</div>");
		pw.println("</td>");
		pw.println("<td width='572'>");
		pw.println("<input type='text' name='postnum' size='15'>");
		pw.println("</td>");
		pw.println("</tr>");
		pw.println("<tr>");
		pw.println("<td width='239' nowrap>");
		pw.println("<div align='right'>※住所（都道府県から）</div>");
		pw.println("</td>");
		pw.println("<td width='572'>");
		pw.println("<input type='text' name='juusyo' size='80' value=''>");
		pw.println("</td>");
		pw.println("</tr>");
		pw.println("<tr>");
		pw.println("<td width='239' nowrap height='38'>");
		pw.println("<div align='right'>建物・マンション名</div>");
		pw.println("</td>");
		pw.println("<td width='572' height='38'>");
		pw.println("<input type='text' name='tatemono' size='40'>");
		pw.println("</td>");
		pw.println("</tr>");
		pw.println("<tr>");
		pw.println("<td width='239' nowrap>");
		pw.println("<div align='right'>※連絡先電話番号</div>");
		pw.println("</td>");
		pw.println("<td width='572'>");
		pw.println("<input type='text' name='tel'>");
		pw.println("</td>");
		pw.println("</tr>");
		pw.println("<tr>");
		pw.println("<td width='239' nowrap>");
		pw.println("<div align='right'>FAX番号</div>");
		pw.println("</td>");
		pw.println("<td width='572'>");
		pw.println("<input type='text' name='fax'>");
		pw.println("</td>");
		pw.println("</tr>");
		pw.println("<tr>");
		pw.println("<td width='239' nowrap>");
		pw.println("<div align='right'>※Eメールアドレス</div>");
		pw.println("</td>");
		pw.println("<td width='572'>");
		pw.println("<input type='text' name='youremail' size='40'>");
		pw.println("</td>");
		pw.println("</tr>");
		pw.println("<tr>");
		pw.println("<td width='239' nowrap>");
		pw.println("<div align='right'>あなたのホームページアドレス</div>");
		pw.println("</td>");
		pw.println("<td width='572'>");
		pw.println("<input type='text' name='yoururl' size='50'>");
		pw.println("</td>");
		pw.println("</tr>");
		pw.println("</table>");
		pw.println("<br>");

		// アンケート
		pw.println("<table width='820' border='2' cellspacing='0' cellpadding='10' height='200' bgcolor='#FFFFCC'>");
		pw.println("<tr>");
		pw.println("<td>");
		pw.println("<p align='center'><b class='s5'>快楽市場アンケートにご協力ください。</b></p>");
		pw.println("<hr>");
		pw.println("<p><b class='s2'>");
		pw.println("1.このホームページを、どう思いましたか？</b></p>");
		pw.println("<p align='left'><span class='s1'>");
		pw.println("<select name='anhpkanso' class='s1'>");
		pw.println("<option value='5' selected>べつに普通</option>");
		pw.println("<option value='4'>面白い</option>");
		pw.println("<option value='3'>まあまあ</option>");
		pw.println("<option value='2'>いまいち</option>");
		pw.println("<option value='1'>つまらない</option>");
		pw.println("</select>");
		pw.println("</span></p>");
		pw.println("<p align='left'><b class='s2'><br>");
		pw.println("2.今後、このホームページで 取り扱ってもらいたい物があれば、ご記入お願いします。</b></p>");
		pw.println("<p align='left'><span class='s1'>");
		pw.println("<textarea name='anatukai' cols='100' rows='4'></textarea>");
		pw.println("</span></p>");
		pw.println("<p align='left'><b class='s2'><br>");
		pw.println("3.あなたがこのページの中で、一番面白いと思った項目は何ですか？</b></p>");
		pw.println("<p align='left'><span class='s1'>");
		pw.println("<select name='aniikai' class='s1'>");
		pw.println("<option value='1FF'>1階</option>");
		pw.println("<option value='B1F'>地下1階</option>");
		pw.println("<option value='B2F'>地下2階</option>");
		pw.println("<option value='B3F'>地下3階</option>");
		pw.println("<option value='B4F'>地下4階</option>");
		pw.println("<option value='B5F'>地下5階</option>");
		pw.println("<option value='B6F'>地下6階</option>");
		pw.println("<option value='B7F'>地下7階</option>");
		pw.println("<option value='B8F'>地下8階</option>");
		pw.println("<option value='not' selected>なし</option>");
		pw.println("</select>");
		pw.println("</span></p>");
		pw.println("<p align='left'><span class='s1'>項目“あれば記入してください”<br>");
		pw.println("<input type='text' name='aniikou' size='80' value=''>");
		pw.println("</span></p>");
		pw.println("<p align='left'><b class='s2'><br>");
		pw.println("4.あなたがこのページの中で、あまり面白くないと思った項目は何ですか？</b></p>");
		pw.println("<p align='left'>");
		pw.println("<select name='anyanakai' class='s1'>");
		pw.println("<option value='1FF'>1階</option>");
		pw.println("<option value='B1F'>地下1階</option>");
		pw.println("<option value='B2F'>地下2階</option>");
		pw.println("<option value='B3F'>地下3階</option>");
		pw.println("<option value='B4F'>地下4階</option>");
		pw.println("<option value='B5F'>地下5階</option>");
		pw.println("<option value='B6F'>地下6階</option>");
		pw.println("<option value='B7F'>地下7階</option>");
		pw.println("<option value='B8F'>地下8階</option>");
		pw.println("<option value='not' selected>なし</option>");
		pw.println("</select>");
		pw.println("</p>");
		pw.println("<p align='left'><span class='s1'>項目“あれば記入してください”<br>");
		pw.println("<input type='text' name='anyanakou' size='80' value=''>");
		pw.println("</span></p>");
		pw.println("<p align='left'><b class='s2'><br>");
		pw.println("5.あなたがよく利用するホームページを、教えてください。</b></p>");
		pw.println("<table>");
		pw.println("<tr><td align='right'>タイトル</td><td><input type='text' name='anurltetle' size='50'></td></tr>");
		pw.println("<tr><td align='right'>ＵＲＬ</td><td><input type='text' name='anurl' size='40'></td></tr>");
		pw.println("</table>");
		pw.println("<hr>");
		pw.println("<p align='center'><b class='s2'>ご協力ありがとうございました。</b></p>");
		pw.println("</td>");
		pw.println("</tr>");
		pw.println("</table>");
		pw.println("");
		pw.println("</div>");
		pw.println("<p><center>");
		pw.println("<input type='submit' name='Submit' value=' 送信 '>");
		pw.println("<input type='reset' name='reset' value='リセット'>");
		pw.println("</center></p>");
		pw.println("</form>");
	}

	// JavaScript出力処理
	private void Script(PrintWriter pw)
			throws ServletException, IOException {
		final String[] script = {
			"function OnSubmit() {",
			" with (document.form1) {",
			"  if (uid.value == '') {",
			"   alert('ＩＤを入力してください。');",
			"   uid.focus();",
			"   return false;",
			"  }",
			"  if (furigana.value == '') {",
			"   alert('ふりがなを入力してください。');",
			"   furigana.focus();",
			"   return false;",
			"  }",
			"  if (name.value == '') {",
			"   alert('氏名を入力してください。');",
			"   name.focus();",
			"   return false;",
			"  }",
			"  if (seibetsu.value == '') {",
			"   alert('性別を入力してください。');",
			"   seibetsu.focus();",
			"   return false;",
			"  }",
			"  if (seinen.value == '') {",
			"   alert('生年月日を入力してください。');",
			"   seinen.focus();",
			"   return false;",
			"  } else {",
			"   if (isNaN(Number(seinen.value))) {",
			"    alert('年が正しくありません。');",
			"    seinen.focus();",
			"    seinen.select();",
			"    return false;",
			"   }",
			"  }",
			"  if (postnum.value == '') {",
			"   alert('郵便番号を入力してください。');",
			"   postnum.focus();",
			"   return false;",
			"  }",
			"  if (juusyo.value == '') {",
			"   alert('住所を入力してください。');",
			"   juusyo.focus();",
			"   return false;",
			"  }",
			"  if (tel.value == '') {",
			"   alert('連絡先電話番号を入力してください。');",
			"   tel.focus();",
			"   return false;",
			"  }",
			"  if (youremail.value == '') {",
			"   alert('Eメールアドレスを入力してください。');",
			"   youremail.focus();",
			"   return false;",
			"  }",
			"  if (youremail.value.indexOf('@') < 1) {",
			"   alert('Eメールアドレスの形式が正しくありません。');",
			"   youremail.focus();",
			"   return false;",
			"  }",
			" }",
			" return true;",
			"}",
/*
			"function MM_findObj(n, d) { //v3.0",
			"var p,i,x;  if(!d) d=document; if((p=n.indexOf('?'))>0&&parent.frames.length) {",
			"d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}",
			"if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];",
			"for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document); return x;",
			"}",
			"",
			"function MM_validateForm() { //v3.0",
			"var i,p,q,nm,test,num,min,max,errors='',args=MM_validateForm.arguments;",
			"for (i=0; i<(args.length-2); i+=3) { test=args[i+2]; val=MM_findObj(args[i]);",
			"if (val) { nm=val.name; if ((val=val.value)!='') {",
			"if (test.indexOf('isEmail')!=-1) { p=val.indexOf('@');",
			"if (p<1 || p==(val.length-1)) errors+='- '+nm+' must contain an e-mail address.\\n';",
			"} else if (test!='R') { num = parseFloat(val);",
			"if (val!=''+num) errors+='- '+nm+' must contain a number.\\n';",
			"if (test.indexOf('inRange') != -1) { p=test.indexOf(':');",
			"min=test.substring(8,p); max=test.substring(p+1);",
			"if (num<min || max<num) errors+='- '+nm+' must contain a number between '+min+' and '+max+'.\\n';",
			"} } } else if (test.charAt(0) == 'R') errors += '- '+nm+' is required.\\n'; }",
			"} if (errors) alert('The following error(s) occurred:\\n'+errors);",
			"document.MM_returnValue = (errors == '');",
			"}",
*/
		};

		JavaScript(pw, script);
	}

	// "POST"メソッド処理
	protected void doPostHandler(HttpServletResponse response, PrintWriter pw, HttpSession session, Param param, Order order)
			throws ServletException, IOException, SQLException {

		String uid = param.GetParam("uid");

		// 入力されたユーザＩＤが登録済みかチェック
		if (order.UserIDCheck(uid)) {
			// 登録済みならメッセージを表示
			Header(pw, "ユーザ登録", "", "bgcolor='#CCFFFF'");
			pw.println("<div class='s2'>指定したＩＤはすでに使われています。別のＩＤを入力してください。</div>");
			pw.println("<br><input type='button' value='戻る' onClick='history.back()'>");
			Footer(pw);
		} else {
			// 未登録ならユーザ登録情報保存
			Order.User user = order.user;
			user.UserCode = null;
			user.UserID = uid;
			user.Password = GeneratePassword();
			user.Touroku = new Date();
			user.Furigana = param.GetParam("furigana");
			user.Shimei = param.GetParam("name");
			user.Seibetsu = param.GetParam("seibetsu");
			user.SeiNen = Integer.parseInt(param.GetParam("seinen"));
			user.SeiTsuki = Integer.parseInt(param.GetParam("seituki"));
			user.Sexxx = Integer.parseInt(param.GetParam("sexxx"));
			user.Nenrei = CalcAge(user.SeiNen, user.SeiTsuki, user.Sexxx);
			user.Over18Flag = false;
			user.Over18Date = null;
			user.Shokugyou = param.GetParam("ShokugyouCode");
			user.Zip = param.GetParam("postnum");
			user.Address = param.GetParam("juusyo");
			user.Tatemono = param.GetParam("tatemono");
			user.TelNo = param.GetParam("tel");
			user.FaxNo = param.GetParam("fax");
			user.EMail = param.GetParam("youremail");
			user.MailingList = false;
			user.Url = param.GetParam("yoururl");

			// アンケート結果保存
			Order.Enquete enquete = order.enquete;
			enquete.Setsumon1 = param.GetParam("anhpkanso");
			enquete.Setsumon2 = param.GetParam("anatukai");
			enquete.Setsumon3 = param.GetParam("aniikai");
			enquete.Setsumon3Koumoku = param.GetParam("aniikou");
			enquete.Setsumon4 = param.GetParam("anyanakai");
			enquete.Setsumon4Koumoku = param.GetParam("anyanakou");
			enquete.Setsumon5Title = param.GetParam("anurltetle");
			enquete.Setsumon4Url = param.GetParam("anurl");

			// ユーザ登録情報確認ページへ
			response.sendRedirect("UserVerify");
		}
	}

	// 乱数によるパスワード生成処理
	private String GeneratePassword() {
		//String chr = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		String chr = "abcdefghjkmnpqrstuvwxyz123456789";
		Random rnd = new Random(new Date().getTime());
		String pass = "";
		
		for (int i = 0; i < 8; i++)
			pass += chr.charAt((int)(rnd.nextFloat() * chr.length()));

		return pass;
	}

	// 生年月日から年齢を計算
	private int CalcAge(int year, int month, int date) {

		GregorianCalendar calendar = new GregorianCalendar();
		int age = calendar.get(Calendar.YEAR) - year;
		int cmonth = calendar.get(Calendar.MONTH) + 1;
		int cdate = calendar.get(Calendar.DATE);
		if (cmonth < month)
			age--;
		else if (cmonth == month) {
			if (cdate < date)
				age--;
		}

		return age;
	}
}
