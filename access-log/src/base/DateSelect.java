package base;

import java.util.*;
import java.util.Date;
import java.text.*;
import java.sql.*;

/**
 * <p>タイトル: 期間選択リスト表示処理</p>
 * <p>説明: </p>
 * <p>著作権: Copyright (c) 2003</p>
 * <p>会社名: </p>
 * @author aizawa
 * @version 1.0
 */

public class DateSelect {
	static final String defaultText = "選択";

	// 年の選択ドロップダウン作成
	static public String getSelectYear(DataBase db, String table, String year, String name, int width) throws SQLException {
		GregorianCalendar cal = new GregorianCalendar();
		StringBuffer strbuf = new StringBuffer();

		// コントロールマスタ取得
		ResultSet rs = db.getContrxxxaster();

		// 開始年取得
		cal.setTime(rs.getDate("MCR_" + table +"FROM_DATE"));
		int fromYear = cal.get(Calendar.YEAR);

		// 終了年取得
		cal.setTime(rs.getDate("MCR_" + table +"TO_DATE"));
		int toYear = cal.get(Calendar.YEAR);

		// <select>タグ
		strbuf.append("<select name='" + name + "' size=1 style='width:" + width + "' onchange='changeYear(this)'>\n");

		// "選択"をセット
		strbuf.append("<option value=''>").append(defaultText).append("</option>\n");

		// 開始年～終了年をセット
		for (int i = toYear; i >= fromYear; i--) {
			strbuf.append(CommonFunc.getOptionTag(CommonFunc.numToString(i), year));
		}

		strbuf.append("</select>\n");

		return strbuf.toString();
	}

	// 月の選択ドロップダウン作成
	static public String getSelectMonth(String month, String name, int width) {
		StringBuffer strbuf = new StringBuffer();

		// <select>タグ
		strbuf.append("<select name='" + name + "' size=1 style='width:" + width + "'>\n");

		// "選択"をセット
		strbuf.append("<option value=''>").append(defaultText).append("</option>\n");

		// １月～12月セット
		for (int i = 1; i <= 12; i++) {
			strbuf.append(CommonFunc.getOptionTag(CommonFunc.numToString(i), month));
		}

		strbuf.append("</select>\n");

		return strbuf.toString();
	}


	// 週の選択ドロップダウン作成
	static public String getSelectWeek(DataBase db, String week, String name, int width) throws SQLException {
		GregorianCalendar cal = new GregorianCalendar();
		StringBuffer strbuf = new StringBuffer();
		SimpleDateFormat fmt = new SimpleDateFormat("yyyy/MM/dd");

		// コントロールマスタ取得
		ResultSet rs = db.getContrxxxaster();

		// 開始年取得
		cal.setTime(rs.getDate("MCR_WEEKFROM_DATE"));
		int fromYear = cal.get(Calendar.YEAR);

		// 終了年取得
		cal.setTime(rs.getDate("MCR_WEEKTO_DATE"));
		int toYear = cal.get(Calendar.YEAR);

		// ダミーの週リスト（年が未選択の時に表示）
		strbuf.append("<select name='" + name + "' size=1 style='width:" + width + "'>\n");
		strbuf.append("<option value=''>").append(defaultText).append("</option>\n");
		strbuf.append("</select>\n");

		// 開始年～終了年までの週リスト作成
		for (int i = fromYear; i <= toYear; i++) {
			// <select>タグ
			strbuf.append("<select name='" + name + i + "' size=1 style='width:" + width + ";display:none'>\n");

			// "選択"を追加
			strbuf.append("<option value=''>").append(defaultText).append("</option>\n");

			// 週の開始年月日取得
			cal.set(i, 0, 1);
			cal.add(Calendar.DATE, 2 - cal.get(Calendar.DAY_OF_WEEK));

			// 年内の週をセット
			int w = 1;
			while (cal.get(Calendar.YEAR) <= i) {
				String ymd = fmt.format(cal.getTime());
				strbuf.append("<option value='" + ymd + "'" +  CommonFunc.selectedFunc(ymd, week) + ">");
				strbuf.append(CommonFunc.numToString(w++) + "週");
				strbuf.append("(" + (cal.get(Calendar.MONTH) + 1) + "/" + cal.get(Calendar.DATE) + "-");
				cal.add(Calendar.DATE, 6);
				strbuf.append(cal.get(Calendar.DATE) + ")");
				cal.add(Calendar.DATE, 1);
				strbuf.append("</option>\n");
			}

			strbuf.append("</select>\n");
		}

		return strbuf.toString();
	}

	// 日の選択ドロップダウン作成
	static public String getSelectDay(String day, String name, int width) {
		StringBuffer strbuf = new StringBuffer();

		// <select>タグ
		strbuf.append("<select name='" + name + "' size=1 style='width:" + width + "'>\n");

		// "選択"を追加
		strbuf.append("<option value=''>").append(defaultText).append("</option>\n");

		for (int i = 1; i <= 31; i++) {
			strbuf.append(CommonFunc.getOptionTag(CommonFunc.numToString(i), day));
		}

		strbuf.append("</select>\n");

		return strbuf.toString();
	}

	// 時の選択ドロップダウン作成
	static public String getSelectHour(String hour, String name, int width) {
		StringBuffer strbuf = new StringBuffer();

		// <select>タグ
		strbuf.append("<select name='" + name + "' size=1 style='width:" + width + "'>\n");

		// "選択"を追加
		strbuf.append("<option value=''>").append(defaultText).append("</option>\n");

		for (int i = 0; i <= 23; i++) {
			strbuf.append(CommonFunc.getOptionTag(CommonFunc.numToString(i), hour));
		}

		strbuf.append("</select>\n");

		return strbuf.toString();
	}
}