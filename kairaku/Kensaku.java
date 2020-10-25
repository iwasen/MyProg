//**********************************************************************
//		快楽市場.co.jp バスケットシステム
//
//		Title:		検索条件入力処理
//		FileName:	Kensaku.java
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

// 検索条件指定ページ処理クラス
public class Kensaku extends BaseServlet2
{
	// "GET"メソッド処理
	protected void doGetHandler(HttpServletResponse response, PrintWriter pw, HttpSession session, Param param, Order order)
			throws ServletException, IOException, SQLException {

		Header(pw, "検索条件入力", "", "bgcolor='#FFCCCC'");
		Body(pw, order);
		Footer(pw);
	}

	// 本体部html出力処理
	private void Body(PrintWriter pw, Order order)
			throws ServletException, IOException, SQLException {

		MyUtil myutil = new MyUtil();

		pw.println("<p align='center' class='s5'>快楽市場　購入商品検索 </p>");
		pw.println("<div align='center'>");
		pw.println("<table width='379' border='3' cellspacing='0' cellpadding='2' height='148' align='center' bgcolor='#CCCCCC' bordercolordark='#000000' bordercolorlight='#FFFFFF' class='s1'>");
		pw.println("<tr>");
		pw.println("<td>");
		pw.println("<form method='get' action='KensakuList' class='s1'>");
		pw.println("<br>");
		pw.println("<table width='100%' cellpadding=5 class='s1'>");
		pw.println("<tr>");
		pw.println("<td align='right' width='40%'>商品分類</td>");
		pw.println("<td>"); myutil.BunruiSelect(pw, order.bunrui, null, "すべて"); pw.println("</td>");
		pw.println("</tr><tr>");
		pw.println("<td align='right'>商品カテゴリー</td>");
		pw.println("<td>"); myutil.CategorySelect(pw, "", null, "すべて"); pw.println("</td>");
		pw.println("</tr><tr>");
		pw.println("<td align='right'>登録日</td>");
		pw.println("<td><select name='kikan'>");
		pw.println("<option value='1'>最新１カ月</option>");
		pw.println("<option value='3'>最新３カ月</option>");
		pw.println("<option value='6'>最新６カ月</option>");
		pw.println("<option value='12'>最新１年</option>");
		pw.println("<option value='' selected>すべて</option>");
		pw.println("</select></td>");
		pw.println("</tr><tr>");
		pw.println("<td align='right'>出演・主演者</td>");
		pw.println("<td><input type='text' name='shuen' size='20'></td>");
		pw.println("</tr></table>");
		pw.println("<br>");
		pw.println("<center>");
		pw.println("<input type='submit' value='　検索　'>");
		pw.println("<input type='reset' value='リセット'>");
		pw.println("</center>");
		pw.println("</form>");        
		pw.println("</td>");
		pw.println("</tr>");
		pw.println("</table>");
		pw.println("</div>");
	}
}
