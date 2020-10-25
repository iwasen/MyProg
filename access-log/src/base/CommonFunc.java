package base;
import java.util.*;
import java.util.Date;
import java.io.*;
import java.text.*;
import javax.servlet.http.*;


/**
 * 汎用的な関数を格納
 */
public class CommonFunc {

	static NumberFormat fmt = NumberFormat.getNumberInstance();

	/**
	 * 小数点以下一桁まででパーセントを返す
	 */
	static public String getParcent(int parent, int child) {
		if (parent == 0 || child == 0)
				return "0";
		else
				return new DecimalFormat("##0.0").format((float)child / parent * 100);
	}

	/**
	 * 文字列が　直下・その他・設定外　の場合その文字に色をつける。
	 */
	static public String textColorValue(String Value) {
		String tags;

		if (Value.equals("その他"))
				tags = "<span class='other'>" + Value + "</span>";
//	else if (Value.equals("設定外"))
//	    tags = "<span class='other'>" + Value + "</span>";
		else if (Value.equals("直下"))
				tags = "<span class='other'>" + Value + "</span>";
		else
				tags = Value;

		return tags;
	}

	/**
	 * コーナーがドリルダウンできるかどうかを場合わけ　flgがfalseの場合リンクなし
	 */
	static public String linkCornerValue(boolean flg, String page, String cornerCode, String cornerName, int cornerdepth) {
		StringBuffer tags = new StringBuffer();

		if (flg)
				tags.append("<a class='link' href=\"JavaScript:cornerpost('").append(cornerCode).append("','").append(cornerdepth).append("');\">");
				// 20030901 xxxxxxy start
				//tags.append(textColorValue(escapeHtml(cornerName)));
				tags.append(textColorValue(addBrEach3Chars(escapeHtml(cornerName))));
				// 20030901 xxxxxxy end
		if (flg)
				tags.append("</a>");

		return tags.toString();
	}

	/**
	 * 業種がドリルダウンできるかどうかを場合わけ
	 */
	static public String linkCategoryValue(String flg, String page, String c_code, String c_name) {
		StringBuffer tags = new StringBuffer();

		if (flg.equals("/") && !c_code.equals("UNDEFINED"))
			tags.append("<a class='link' href=\"JavaScript:categorypost('").append(c_code).append("');\">");
			// 20030901 xxxxxxy start
			//tags.append(CommonFunc.textColorValue(escapeHtml(c_name)));
			tags.append(CommonFunc.textColorValue(addBrEach3Chars(escapeHtml(c_name))));
			// 20030901 xxxxxxy start

		if (flg.equals("/") && !c_code.equals("UNDEFINED"))
			tags.append("</a>");

		return tags.toString();
	}

	/**
	 * 与えられた配列の中の数のうち上位３位に色をつける。
	 */
	static public String getCountValue(int[] Values, String[] vAxis, String[] hAxis, boolean linkFlag, boolean rankFlag) {
		StringBuffer tags = new StringBuffer();
		int rank;

		for(int i = 0; i < Values.length; i++) {
			if (!rankFlag || Values[i] == 0 || hAxis[i].equals("UNDEFINED")) {
				// アクセス数0の場合は4位以下の色とする
				rank = 4;
			} else {
				// 順位を求める
				rank = 1;
				for (int j = 0; j < Values.length; j++) {
					if (Values[j] > Values[i] && !hAxis[j].equals("UNDEFINED")) {
						if (++rank == 4)
						break;
					}
				}
			}

			tags.append("<td class='data_rank" + rank + "'>");

			if (Values[i] != 0 && linkFlag)
				tags.append("<a href='javascript:urlAccess(\"" + vAxis[i] + "\",\"" +  hAxis[i] + "\")' class='link'>");

			tags.append(fmt.format(Values[i]));

			if (Values[i] != 0 && linkFlag)
				tags.append("</a>");

			tags.append("</td>\n");
		}

		return tags.toString();
	}

	/**
	 * ２つの値を比較し　一致したら　selectedをかえす
	 */
	static public String selectedFunc(String optionVal, String sessionVal) {
		if (optionVal.equals(sessionVal))
			return " selected";
		return "";
	}

	/**
	 * int型からString型に変換し　かつ　一桁の数字は　前に0を埋めてやる
	 * 正数のみ対応可能
	 */
	static public String numToString(int i) {
		String num;

		if (9 >= i && i >= 0) {
			num = "0" + String.valueOf(i);
		} else {
			num = String.valueOf(i);
		}
		return num;
	}

 /**
	* 横軸グラフの作成
	*/
	static public String genGraphImage(int i,int j) {
		StringBuffer image = new StringBuffer();

		if (i > 0 && j > 0) {
			image.append("<img src=\"images/graph.gif\" width='");
			image.append((((long)i * 300) / j));  //GIFの幅を計算
			image.append("' height='18'>");
		}
		return image.toString();
	}

	/**
	* 文字列の置換を行う
	*/
	static public void substitute(StringBuffer input, String pattern, String replacement) {
		int len = pattern.length();
		int index = 0;

//    while ((index = input.indexOf(pattern, index)) != -1) {
		while ((index = input.toString().indexOf(pattern, index)) != -1) {
			input.replace(index, index + len, replacement);
			index += len;
		}
	}

	/**
	* null値を既定の値に置換
	*/
	static public String nvl(String val, String def) {
		if (val == null)
				return def;
		return val;
	}

	/**
	* 配列内の値の入れ替え（String)
	*/
	static public void swap(String[] array, int a, int b) {
		String temp = array[a];
		array[a] = array[b];
		array[b] = temp;
	}

	/**
	* 配列内の値の入れ替え（boolean)
	*/
	static public void swap(boolean[] array, int a, int b) {
		boolean temp = array[a];
		array[a] = array[b];
		array[b] = temp;
	}

	/*
	* 期間の表示
	*/
	static public String getTimePeriod(String kind, Date date, String holiday) {
		StringBuffer fmtDate = new StringBuffer();
		SimpleDateFormat formatter;

		if (kind.equals("MONTH")) {
			// 月単位：MM月
			formatter = new SimpleDateFormat("MM月");
			fmtDate.append(formatter.format(date));
		} else if (kind.equals("WEEK")) {
			// 週単位：YYYY/MM/DD-MM/DD
			GregorianCalendar date2 = new GregorianCalendar();
			date2.setTime(date);
			date2.add(Calendar.DATE, 6);

			formatter = new SimpleDateFormat("yyyy/MM/dd");
			fmtDate.append(formatter.format(date));
			fmtDate.append("-");
			formatter = new SimpleDateFormat("MM/dd");
			fmtDate.append(formatter.format(date2.getTime()));
		} else if (kind.equals("DATE")) {
			// 日単位：YYYY/MM/DD（曜日）
			GregorianCalendar cal = new GregorianCalendar();
			cal.setTime(date);
			int week = cal.get(Calendar.DAY_OF_WEEK );

			// 土曜日は青、日曜祝日は赤で表示
			if (week == 7 || holiday != null && holiday.equals("1"))
				fmtDate.append("<font color='blue'>");
			else if (week == 1 || holiday != null && holiday.equals("2"))
				fmtDate.append("<font color='red'>");
			else
				fmtDate.append("<font color='black'>");

			formatter = new SimpleDateFormat("yyyy/MM/dd（E）");
			fmtDate.append(formatter.format(date));

			fmtDate.append("</font>");
		} else if (kind.equals("HOUR")) {
			// 時間単位：YYYY/MM/DD HH時
			formatter = new SimpleDateFormat("yyyy/MM/dd HH時");
			fmtDate.append(formatter.format(date));
		}

		return fmtDate.toString();
	}

	/*
	* <Option>タグの出力（valueと表示が異なるバージョン）
	*/
	static public String getOptionTag(String value, String text, String selected) {
		return "<option value='" + value + "'" + CommonFunc.selectedFunc(value, selected) + ">" + text + "</option>\n";
	}

	/*
	* <Option>タグの出力（valueと表示が同じバージョン）
	*/
	static public String getOptionTag(String value, String selected) {
		return "<option value='" + value + "'" + CommonFunc.selectedFunc(value, selected) + ">" + value + "</option>\n";
	}

	// 文字化け対策用コード変換
	static public String mojibakeTaisaku(String s) {
		if (s == null)
			return null;

		StringBuffer sb = new StringBuffer();
		char c;
		for (int i = 0; i < s.length(); i++) {
			c  = s.charAt(i);
			switch (c) {
			case 0xff5e:        // FULLWIDTH TILDE ->
				c = 0x301c;     // WAVE DASH
				break;
			case 0x2225:        // PARALLEL TO ->
				c = 0x2016;     // DOUBLE VERTICAL LINE
				break;
			case 0xff0d:        // FULLWIDTH HYPHEN-MINUS ->
				c = 0x2212;     // MINUS SIGN
				break;
			case 0xffe0:        // FULLWIDTH CENT SIGN ->
				c = 0x00a2;     // CENT SIGN
				break;
			case 0xffe1:        // FULLWIDTH POUND SIGN ->
				c = 0x00a3;     // POUND SIGN
				break;
			case 0xffe2:        // FULLWIDTH NOT SIGN ->
				c = 0x00ac;     // NOT SIGN
				break;
			}
			sb.append(c);
		}
		return new String(sb);
	}

	// テーブルの種類により日付の範囲を"YYYY/MM/DD-YYYY/MM/DD"で返す
	static public String getDateRange(Date dateFrom, String table) {
		Date dateTo;

		// 開始日付をカレンダーにセット
		GregorianCalendar cal = new GregorianCalendar();
		cal.setTime(dateFrom);

		// テーブルの種類により終了日付を取得
		if (table.equals("MONTH"))
			cal.set(Calendar.DATE, cal.getActualMaximum(Calendar.DATE));
		else if (table.equals("WEEK"))
			cal.add(Calendar.DATE, 6);

		dateTo = cal.getTime();

		// "YYYY/MM/DD-YYYY/MM/DD"（同一の場合は"YYYY/MM/DD"）形式にフォーマット
		SimpleDateFormat df = new SimpleDateFormat("yyyy/MM/dd");
		if (dateFrom.equals(dateTo))
			return df.format(dateFrom);
		else
			return df.format(dateFrom) + "-" + df.format(dateTo);
	}

	// コーナー名称から最下位層の名称だけを抽出
	static public String getLaxxxornerName(String cornerName) {
		int n = cornerName.lastIndexOf('>');
		if (n != -1)
			return cornerName.substring(n + 1);
		else
			return cornerName;
	}

	// クッキーに指定したキーと値を書く
	static public void setCookie(HttpServletResponse response, String key, String value) {
		Cookie cookie = new Cookie(key, value);
		response.addCookie(cookie);
	}

	// クッキーから指定したキーの値を読む
	static public String getCookie(HttpServletRequest request, String key) {
		Cookie cookies[] = request.getCookies();
		if (cookies != null) {
			for (int i = 0; i < cookies.length; i++) {
				if (cookies[i].getName().equals(key))
					return cookies[i].getValue();
			}
		}
		return "";
	}

	// html特殊文字のエスケープ
	static public String escapeHtml(String str) {
		StringBuffer strBuf = new StringBuffer(str);

		substitute(strBuf, "&", "&amp;");
		substitute(strBuf, "<", "&lt;");
		substitute(strBuf, ">", "&gt;");

		return strBuf.toString();
	}

 /**
	* コーナーコードからコーナー階層を取得する
	*/
	static public int getCornerDepth(String cornerCode) {
		int len = cornerCode.length();
		int depth = 0;

		for (int i = 1; i < len; i++) {
			if (cornerCode.charAt(i) == '/')
				depth++;
		}

		return depth;
	}

	// 上位（親）コーナーコードを取得
	static public String getParentCorner(String corner) {
		if (!corner.equals("/")) {
			int n = corner.lastIndexOf('/', corner.length() - 2);
			if (n != -1)
				corner = corner.substring(0, n + 1);
		}
		return corner;
	}

	// プロパティファイルからURLのホスト名を取得
	static public String getUrlHost() {
		try {
			FileInputStream fin = new FileInputStream(new File(WebAppInitializer.propertyPath));
			Properties prop = new Properties();
			prop.load(fin);
			String web_host = prop.getProperty("web_host", "").trim();
			fin.close();
			return web_host;
		} catch (Exception e) {
			return "";
		}
	}

	// 半角英数記号を全角に変換する
	public static String toFullANS(String str) {
		StringBuffer ret = new StringBuffer();
		for (int i = 0; i < str.length(); i++){
			int code = str.charAt(i);
			if (code >= 0x21 && code <= 0x7e) {
				ret.append((char)(code + 0xfee0));
			} else {
				ret.append((char)code);
			}
		}
		return ret.toString();
	}

		// 全角３文字で<br>を付加 20030826 xxxxxxy
	public static String addBrEach3Chars(String str) {
		StringBuffer ret = new StringBuffer();
		int i_counter = 0;
		for (int i = 0; i < str.length(); i++){
			i_counter++;
			ret.append(str.charAt(i));
			if (i_counter == 3) {
				ret.append("<br>");
				i_counter = 0;
			}
		}
		return ret.toString();
	}






}