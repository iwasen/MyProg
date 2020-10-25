//**********************************************************************
//		快楽市場.co.jp バスケットシステム
//
//		Title:		マスターメンテナンス初期画面
//		FileName:	master.java
//		Creator:	Shinichi Aizawa
//		CreateDate:	2000/04/14
//**********************************************************************

import java.io.IOException;
import java.io.PrintWriter;
import java.io.*;
import java.sql.SQLException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.ServletException;

// マスターメンテナンス初期画面処理クラス
public class master extends BaseServlet1
{
	// "GET"メソッド処理
	public void doGet(HttpServletRequest request, HttpServletResponse response) 
			throws ServletException, IOException {

		try {
			PrintWriter pw = GetPrintWriter(response);

			// ログインチェック
			if (!LoginCheck(request)) {
				if (!Authorization(request, response)) {
					LoginFailure(pw);
					return;
				}
			}

			Header(pw, "マスターメンテナンス");
			Body(pw);
			Script(pw);
			Footer(pw);

		} catch (Exception e) {
			throw new ServletException(e.toString());
		}
	}

	// 認証処理
	private boolean Authorization(HttpServletRequest request, HttpServletResponse response)
			throws ServletException, IOException, SQLException {

		MyUtil myutil = new MyUtil();
		boolean loginok = false;

		// ＩＤ／パスワードチェック
		String auth = request.getHeader("Authorization");
		if (auth == null) {
			// ID/Password未入力
			response.setHeader("WWW-Authenticate", "BASIC realm=\"Kairaku Ichiba\"");
			response.setStatus(HttpServletResponse.SC_UNAUTHORIZED);
		} else {
			// ID/Password入力済み
			String realm = auth.substring(auth.lastIndexOf(' ')+1);
			byte b[] = realm.getBytes();
			int l = b.length;
			ByteArrayOutputStream bout = Base64.decode(new ByteArrayInputStream(b),l);
			String dec = bout.toString();
			String pid = dec.substring(0,dec.indexOf(':'));
			String password = dec.substring(dec.indexOf(':')+1);

			// パスワードチェック
			Contrxxxaster cm = new Contrxxxaster();
			if (cm.CheckAdmin(pid, password)) {
				loginok = true;
				myutil.PutCookie(response, "MasterLogin", "ok");
			} else {
				// 正しくなければ再入力
				response.setHeader("WWW-Authenticate", "BASIC realm=\"Kairaku Ichiba\"");
				response.setStatus(HttpServletResponse.SC_UNAUTHORIZED);
			}
		}

		return loginok;
	}

	// 本体部html出力処理
	private void Body(PrintWriter pw)
			throws ServletException, IOException {

		pw.println("<form name='shouhin'>");
		pw.println("<font color='orange'>■</font><b>商品マスター</b><br>");
		pw.println("<table border=0>");
		pw.println("<tr><td>");
		pw.println("<input type='button' value='新規登録' onClick='location.href=\"ShouhinItem\"'>");
		pw.println("</td></tr><tr><td>");
		pw.println("商品番号");
		pw.println("</td><td>");
		pw.println("<input type='text' name='no' size=28>");
		pw.println("</td><td>");
		pw.println("<input type='button' value='　更新　' onClick='OnClick_ShouhinList()'>");
		pw.println("</td></tr>");
		pw.println("<tr><td></td><td><font size=-1>（前方一致検索）</font></td></tr>");
		pw.println("</table>");
		pw.println("</form>");

		pw.println("<form name='user'>");
		pw.println("<font color='orange'>■</font><b>ユーザーマスター</b><br>");
		pw.println("<table border=0>");
		pw.println("<tr><td>");
		pw.println("氏名");
		pw.println("</td><td>");
		pw.println("<input type='text' name='name' size=28>");
		pw.println("</td><td>");
		pw.println("<input type='button' value='　更新　' onClick='OnClick_UserUpdate()'>");
		pw.println("</td></tr>");
		pw.println("<tr><td></td><td><font size=-1>（前方一致検索）</font></td></tr>");
		pw.println("<tr></tr>");
		pw.println("<tr><td>");
		pw.println("登録年月日");
		pw.println("</td><td>");
		pw.println("<input type='text' name='year' size=4 maxlength=4>年");
		pw.println("<input type='text' name='month' size=2 maxlength=2>月");
		pw.println("<input type='text' name='date' size=2 maxlength=2>日");
		pw.println("</td><td>");
		pw.println("<input type='button' value='　表示　' onClick='OnClick_UserInfo()'>");
		pw.println("</td></tr>");
		pw.println("<tr><td></td><td><font size=-1>（指定登録日以降）</font></td></tr>");
		pw.println("</table>");
		pw.println("</form>");

		pw.println("<form name='etc'>");
		pw.println("<table border=0>");
		pw.println("<tr><td width=200>");
		pw.println("<font color='orange'>■</font><b>商品分類マスター</b><br>");
		pw.println("<input type='button' value='　編集　' onClick='location.href=\"BunruiMaster\"'>");
		pw.println("</td><td>");
		pw.println("<font color='orange'>■</font><b>職業マスター</b><br>");
		pw.println("<input type='button' value='　編集　' onClick='location.href=\"ShokugyouMaster\"'>");
		pw.println("</td></tr>");
		pw.println("<tr><td colspan=2><br></td></tr>");
		pw.println("<tr><td>");
		pw.println("<font color='orange'>■</font><b>カテゴリーマスター</b><br>");
		pw.println("<input type='button' value='　編集　' onClick='location.href=\"CategoryMaster\"'>");
		pw.println("</td><td>");
		pw.println("<font color='orange'>■</font><b>支払方法マスター</b><br>");
		pw.println("<input type='button' value='　編集　' onClick='location.href=\"ShiharaiMaster\"'>");
		pw.println("</td></tr>");
		pw.println("</table>");
		pw.println("</form>");
	}

	// JavaScript出力処理
	private void Script(PrintWriter pw)
			throws ServletException, IOException {

		final String[] script = {
			"function OnClick_ShouhinList() {",
			" document.shouhin.action='ShouhinList';",
			" document.shouhin.submit();",
			"}",
			"function OnClick_UserUpdate() {",
			" document.user.action='UserList';",
			" document.user.submit();",
			"}",
			"function OnClick_UserInfo() {",
			" with (document.user) {",
			"  var nyear = Number(year.value);",
			"  if (year.value != '') {",
			"   if (isNaN(nyear)) {",
			"    alert('年が正しくありません。');",
			"    year.focus();",
			"    year.select();",
			"    return;",
			"   }",
			"   if (month.value == '') {",
			"    alert('月を入力してください。');",
			"    month.focus();",
			"    return;",
			"   }",
			"   var nmonth = Number(month.value);",
			"   if (isNaN(nmonth) || nmonth < 1 || nmonth > 12) {",
			"    alert('月が正しくありません。');",
			"    month.focus();",
			"    month.select();",
			"    return;",
			"   }",
			"   if (date.value == '') {",
			"    alert('日を入力してください。');",
			"    date.focus();",
			"    return;",
			"   }",
			"   var ndate = Number(date.value);",
			"   if (isNaN(ndate) || ndate < 1 || ndate > 31) {",
			"    alert('日が正しくありません。');",
			"    date.focus();",
			"    date.select();",
			"    return;",
			"   }",
			"  }",
			" }",
			" document.user.action='UserInfo';",
			" document.user.method='post';",
			" document.user.submit();",
			"}",
		};

		JavaScript(pw, script);
	}

	// ログイン失敗時メッセージ出力
	private void LoginFailure(PrintWriter pw)
			throws ServletException, IOException {

		Header(pw, "マスターメンテナンス");
		pw.println("<h2>ログインできません。</h2>");
		Footer(pw);
	}
}
