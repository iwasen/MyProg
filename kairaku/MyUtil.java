//**********************************************************************
//		快楽市場.co.jp バスケットシステム
//
//		Title:		共通ユーティリティ
//		FileName:	MyUtil.java
//		Creator:	Shinichi Aizawa
//		CreateDate:	2000/04/14
//**********************************************************************

import java.io.PrintWriter;
import java.io.IOException;
import java.text.SimpleDateFormat;
import java.util.Date;
import java.text.ParseException;
import java.sql.*;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.ServletException;
import javax.servlet.http.Cookie;

// 共通ユーティリティクラス
public class MyUtil
{
	// 商品分類選択リスト作成処理
	public void BunruiSelect(PrintWriter pw, String def, String top, String bottom)
			throws ServletException, IOException, SQLException {

		String sql = "select MSB_BUNRUI_CODE,MSB_BUNRUI_NAME from M_SBUNRUI order by MSB_ORDER_NO";

		pw.println("<select size=1 name='BunruiCode'>");
		OutputOptionDB(pw, sql, def, top, bottom);
		pw.println("</select>");
	}

	// カテゴリー選択リスト作成処理
	public void CategorySelect(PrintWriter pw, String def, String top, String bottom)
			throws ServletException, IOException, SQLException {

		String sql = "select MCT_CATEGORY_CODE,MCT_CATEGORY_NAME from M_CATEGORY order by MCT_ORDER_NO";

		pw.println("<select size=1 name='CategoryCode'>");
		OutputOptionDB(pw, sql, def, top, bottom);
		pw.println("</select>");
	}

	// 職業選択リスト作成処理
	public void ShokugyouSelect(PrintWriter pw, String def, String top, String bottom)
			throws ServletException, IOException, SQLException {

		String sql = "select MSG_SHOKUGYOU_CODE,MSG_SHOKUGYOU_NAME from M_SHOKUGYOU order by MSG_ORDER_NO";

		pw.println("<select size=1 name='ShokugyouCode'>");
		OutputOptionDB(pw, sql, def, top, bottom);
		pw.println("</select>");
	}

	// 支払方法選択リスト作成処理
	public void ShiharaiSelect(PrintWriter pw, String def, String top, String bottom)
			throws ServletException, IOException, SQLException {

		String sql = "select MSI_SHIHARAI_CODE,MSI_SHIHARAI_NAME from M_SHIHARAI order by MSI_ORDER_NO";

		pw.println("<select size=1 name='ShiharaiCode'>");
		OutputOptionDB(pw, sql, def, top, bottom);
		pw.println("</select>");
	}

	// 選択リスト作成共通処理（ＤＢから）
	private void OutputOptionDB(PrintWriter pw, String sql, String def, String top, String bottom)
			throws ServletException, IOException, SQLException {

		String code;
		String name;
		String sel;

		// 先頭に追加する項目
		if (top != null) {
			if (def.equals(""))
				sel = " selected";
			else
				sel = "";
			pw.println("<option value=''" + sel + ">" + top + "</option>");
		}

		// データベース接続
		DataBase db = new DataBase();
		Statement st = db.createStatement();
		ResultSet rs = st.executeQuery(sql);

		// 全レコードを読み込み、<option>タグを付けて出力
		while (rs.next()) {
			code = rs.getString(1).trim();
			name = rs.getString(2).trim();

			if (def.equals(code))
				sel = " selected";
			else
				sel = "";

			pw.println("<option value='" + code + "'" + sel + ">" + name + "</option>");
		}

		// 最後に追加する項目
		if (bottom != null) {
			if (def.equals(""))
			sel = " selected";
			else
				sel = "";
			pw.println("<option value=''" + sel + ">" + bottom + "</option>");
		}

		// データベースクローズ
		rs.close();
		st.close();
	}

	// 性別選択リスト作成処理
	public void SeibetsuSelect(PrintWriter pw, String def, String top, String bottom) {

		final String[][] table = {
			{"男", "男"},
			{"女", "女"}
		};

		pw.println("<select size=1 name='Seibetsu'>");
		OutputOptionTable(pw, table, def, top, bottom);
		pw.println("</select>");
	}

	// 選択リスト作成共通処理（テーブルから）
	private void OutputOptionTable(PrintWriter pw, String[][] table, String def, String top, String bottom) {

		String code;
		String name;
		String sel;

		// 先頭に追加する項目
		if (top != null) {
			if (def.equals(""))
				sel = " selected";
			else
				sel = "";
			pw.println("<option value=''" + sel + ">" + top + "</option>");
		}

		// 全レコードを読み込み、<option>タグを付けて出力
		for (int i = 0; i < table.length; i++) {
			code = table[i][0];
			name = table[i][1];

			if (def.equals(code))
				sel = " selected";
			else
				sel = "";

			pw.println("<option value='" + code + "'" + sel + ">" + name + "</option>");
		}

		// 最後に追加する項目
		if (bottom != null) {
			if (def.equals(""))
			sel = " selected";
			else
				sel = "";
			pw.println("<option value=''" + sel + ">" + bottom + "</option>");
		}
	}

	// 数値選択リスト作成処理
	public void NumberSelect(PrintWriter pw, String name, int start, int end, int def) {

		String sel;

		pw.println("<select size=1 name='" + name + "'>");

		for (int i = start; i <= end; i++) {
			if (def == i)
				sel = " selected";
			else
				sel = "";

			pw.println("<option value='" + i + "'" + sel + ">" + i + "</option>");
		}

		pw.println("</select>");
	}

	// boolean型選択用ラジオボタン作成処理
	public void BooleanRadio(PrintWriter pw, String name, boolean flag, String f, String t) {

		pw.println("<input type='radio' name='" + name + "' value='false'" + (!flag ? " checked" : "") + ">" + f);
		pw.println("<input type='radio' name='" + name + "' value='true'" + (flag ? " checked" : "") + ">" + t);
	}

	// 職業名取得処理
	public String GetShokugyouName(String code)
			throws ServletException, IOException, SQLException {

		String sql = "select MSG_SHOKUGYOU_NAME from M_SHOKUGYOU where MSG_SHOKUGYOU_CODE='" + code + "'";

		return LookupMaster(sql);
	}

	// 支払方法名取得処理
	public String GetShiharaiName(String code)
			throws ServletException, IOException, SQLException {

		String sql = "select MSI_SHIHARAI_NAME from M_SHIHARAI where MSI_SHIHARAI_CODE='" + code + "'";

		return LookupMaster(sql);
	}

	// コードから名称取得共通処理
	private String LookupMaster(String sql)
			throws ServletException, IOException, SQLException {

		// データベース接続
		DataBase db = new DataBase();
		Statement st = db.createStatement();
		ResultSet rs = st.executeQuery(sql);
		String name = null;

		// 見つかれば名称を取得
		if (rs.next())
			name = rs.getString(1);

		// データベースクローズ
		rs.close();
		st.close();

		return name;
	}

	// 部分文字列置換処理
	public String ReplaceString(String str, String src, String dst) {

		int pos1 = 0;
		int pos2;
		int len = src.length();
		String buf = "";

		while (true) {
			pos2 = str.indexOf(src, pos1);
			if (pos2 == -1)
				break;

			buf += str.substring(pos1, pos2) + dst;
			pos1 = pos2 + len;
		}

		buf += str.substring(pos1);

		return buf;
	}

	// 日付を文字列に変換（nullはブランク）
	public String DateToString(Date date) {

		if (date == null)
			return "";
		else
			return new SimpleDateFormat("yyyy/MM/dd").format(date);
	}

	// 文字列を日付に変換（変換できない場合はnull）
	public Date StringToDate(String date) {

		try {
			return new SimpleDateFormat("yyyy/MM/dd").parse(date);
		} catch (ParseException e) {
			return null;
		}
	}

	// null の場合、"null"という文字に変換
	public String NullAble(Object value) {

		if (value == null)
			return "null";
		else
			return "'" + value.toString() + "'";
	}

	// Cookie書き込み
	public void PutCookie(HttpServletResponse response, String name, String value) {

		Cookie cookie = new Cookie(name, value);
		response.addCookie(cookie);
	}

	// Cookie取得
	public String GetCookieValue(HttpServletRequest request, String name) {

		Cookie[] cookies;
		Cookie cookie;

		if ((cookies = request.getCookies()) != null) {
			for (int i = 0; i < cookies.length; i++) {
				if (cookies[i].getName().equals(name))
      				return cookies[i].getValue();
			}
		}

		return null;
	}
}
