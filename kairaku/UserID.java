//**********************************************************************
//		快楽市場.co.jp バスケットシステム
//
//		Title:		ユーザ指定入力処理
//		FileName:	UserID.java
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

// ユーザＩＤ入力ページ処理クラス
public class UserID extends BaseServlet2
{
	// "GET"メソッド処理
	protected void doGetHandler(HttpServletResponse response, PrintWriter pw, HttpSession session, Param param, Order order)
			throws ServletException, IOException, SQLException {

		Header(pw, "ユーザ指定入力", "", "bgcolor='#000000' text='#FFFFFF' link='#FFFFFF' vlink='#FFFFFF' alink='#FFFFFF'"
				 + (order.AuthError ? "onLoad='alert(\"ＩＤまたはパスワードが違います。\")'" : ""));
		order.AuthError = false;
		Body(pw, order);
		Footer(pw);
	}

	// 本体部html出力処理
	private void Body(PrintWriter pw, Order order)
			throws ServletException, IOException, SQLException {

		MyUtil myutil = new MyUtil();

		pw.println("<center>");
		pw.println("<p class='s2'>あなたのＩＤとパスワードを入力して下さい。</p>");
		pw.println("<form name='form1' method='post' class='s1' >");
		pw.println("<p>ＩＤ<br>");
		pw.println("<input type='text' name='uid' size='10'>");
		pw.println("</p>");
		pw.println("<p> パスワード<br>");
		pw.println("<input type='password' name='password' size='10'>");
		pw.println("</p>");
		pw.println("<p>");
		pw.println("<input type='submit' name='ok' value='送信'>");
		pw.println("<input type='reset' name='reset' value='リセット'>");
		pw.println("</p>");
		pw.println("<p>初めての方は、新規登録へ！<br>");
		pw.println("無料でＩＤとパスワードを発行します。</p>");
		pw.println("<p>");
		pw.println("<input type='submit' name='new' value='新規登録'>");
		pw.println("</p>");
		pw.println("</form>");
		pw.println("</center>");
	}

	// "POST"メソッド処理
	protected void doPostHandler(HttpServletResponse response, PrintWriter pw, HttpSession session, Param param, Order order)
			throws ServletException, IOException, SQLException {

		// クリックされたボタンによる振り分け
		if (param.GetParam("ok") != null) {
			// 「送信」ボタンならユーザＩＤとパスワードをチェック
			if (order.AuthenticationCheck(param.GetParam("uid"), param.GetParam("password"))) {
				// ＯＫなら商品オーダリストぺーじへ
				response.sendRedirect("OrderList");
			} else {
				// ＮＧなら再入力
				order.AuthError = true;
				response.sendRedirect("UserID");
			}
		} else if (param.GetParam("new") != null) {
			// 「新規登録」ボタンならユーザ登録ページへ
			response.sendRedirect("UserReg");
		}
	}
}
