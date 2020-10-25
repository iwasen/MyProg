package xxx;
import base.*;
import java.sql.*;
import java.text.*;


/**
 * コーナー別アクセス推移表を作成
 */
public class CornerAccessBean implements I_Access_Page {
	private String dbdata = "DB value";
	private boolean forwardPage;
	private boolean backPage;

	public String getDbdata() {
		return dbdata;
	}

	public String getDisableForwardPage() {
		return forwardPage ? "" : " disabled";
	}

	public String getDisableBackPage() {
		return backPage ? "" : " disabled";
	}

	/**
	 * コーナー別アクセス推移表の中身を作成
	 */
	public void searchDB(DataBase db, SelectCondBean selectcondBean) {
		try{
			Statement st = db.createStatement();
			ResultSet rs;
			NumberFormat numFormat = NumberFormat.getNumberInstance();
			int i;

			// コーナー、階層、カテゴリを取得
			String corner = selectcondBean.getCorner();
			int cornerDepth = Integer.parseInt(selectcondBean.getCornerdepth());
			String category = selectcondBean.getCategory();

			// 横軸（コーナー）の情報を格納するための配列確保
			int num = CommonSql.getCornerCount(st, corner, cornerDepth) + 1;
			String cornerCodes[] = new String[num];     // コーナーコード
			String cornerNames[] = new String[num];     // コーナー名称
			boolean cornerLinks[] = new boolean[num];   // コーナーのリンクフラグ
			int cornerTotals[] = new int[num];          // コーナーのアクセス合計
			int counterTbl[] = new int[num];            // アクセスカウンタ
			String dates[] = new String[num];           // 期間

			// コーナーの情報（コーナーコード、コーナー名、リンクの有無）を配列に取得
			rs = CommonSql.getCornerResult(st, corner, cornerDepth);
			for (i = 0; i < num && rs.next(); i++) {
				cornerCodes[i] = rs.getString("cornercode").trim();
				cornerNames[i] = CommonFunc.getLaxxxornerName(rs.getString("cornername"));
				if (selectcondBean.getCornerdepth().equals("6"))
					cornerLinks[i] = false;   // コーナの深さが６の場合リンク切れ　0
				else
					cornerLinks[i] = rs.getBoolean("link");
			}

			// 「直下」のリンク情報を追加　（コーナ直下のページへのアクセスは　一括　コーナコード/$　で記録される）
			// つまりコーナ一覧に「直下」というコーナコードを追加している。
			cornerCodes[i] = corner + "$/";
			cornerNames[i] = "直下";
			cornerLinks[i] = false;

			// コーナ１階層目には「設定外」というコーナが存在する（どのコーナにも該当しないアクセス）。
			// プログラムでは「直下」がいちばん右にくるが
			// デザイン上は「設定外」が一番右なので値を入れ替えてやる。
			if (cornerDepth == 1 && i > 1 && cornerCodes[i - 1].equals("/NOCODE/")) {
				CommonFunc.swap(cornerCodes, i, i - 1);
				CommonFunc.swap(cornerNames, i, i - 1);
				CommonFunc.swap(cornerLinks, i, i - 1);
			}

			// テーブル別のヘッダ　日・週・月別変化
			String table = selectcondBean.getTable();
			String prefix = "", dateUnit = "";
			if (table.equals("MONTH")) {
				prefix = "SMCC";
				dateUnit = "月";
			} else if (table.equals("WEEK")) {
				prefix = "SWCC";
				dateUnit = "週";
			} else if (table.equals("DATE")) {
				prefix = "SDCC";
				dateUnit = "日付";
			}

			// 日付、カテゴリ別のアクセス数集計用サブクエリ
			StringBuffer q01 = new StringBuffer();
			q01.append("select ");
			q01.append("[PF]_ACCESS[TB]_DATE as S_DATE,");
			q01.append("[PF]_CORNER_CODE as S_CORNER_CODE,");
			q01.append("sum([PF]_ACCESSCOUNT_NUM) as S_ACCESSCOUNT_NUM \n");
			q01.append("from S_[TB]_CORNER_COMPANY \n");
			q01.append("where ");
			q01.append("[PF]_ACCESS[TB]_DATE between '" + selectcondBean.getDateFrom() + "' and '" + selectcondBean.getDateTo() + "'\n");
			q01.append("and [PF]_CORNERPARENT_CODE='" + corner + "'\n");
			q01.append("and [PF]_CORNER_CODE<>'/' \n");
			if (!category.equals("/"))
				q01.append("and [PF]_BUSINESSCATEGORY_CODE='" + category + "' \n");
			CommonSql.setAttributeSql(q01, selectcondBean);
			q01.append("group by [PF]_ACCESS[TB]_DATE,[PF]_CORNER_CODE \n");

			// カラムの接頭辞とテーブル種別名を置換
			CommonFunc.substitute(q01, "[PF]", prefix);
			CommonFunc.substitute(q01, "[TB]", table);

			// 横軸別に集計するためのselect句
			StringBuffer select = new StringBuffer();
			select.append("sum(S_ACCESSCOUNT_NUM) as ALLCOUNT \n");
			for (i = 0; i < num; i++)
				select.append(",sum(CASE S_CORNER_CODE when '" + cornerCodes[i] + "' then S_ACCESSCOUNT_NUM else 0 end) as COUNT" + i + "\n");

			// 合計を取得するためのSQL
			StringBuffer sql_ttl = new StringBuffer();
			sql_ttl.append("select " + select);
			sql_ttl.append("from (" + q01 + ") \n");

			// 行ごとのアクセス数を取得するためのサブクエリ
			StringBuffer q02 = new StringBuffer();
			q02.append("select " + select + ",S_DATE \n");
			q02.append("from (" + q01 + ") \n");
			q02.append("group by S_DATE");

			// 休日マスタと結合
			StringBuffer q03 = new StringBuffer();
			q03.append("select q02.*,MHD_HOLIDAY_CLS \n");
			q03.append("from (" + q02 + ") q02 \n");
			q03.append(",M_HOLIDAY \n");
			q03.append("where S_DATE=MHD_HOLIDAY_DATE(+) \n");
			q03.append("order by S_DATE \n");

			// 必要な範囲のデータのみを抽出
			StringBuffer sql_row = new StringBuffer();
			int pageSize = selectcondBean.getPageSize();
			int offset = selectcondBean.getPageNo() * pageSize;
			sql_row.append(CommonSql.RowRange(q03.toString(), offset, pageSize + 1)); // 次ページがあるかチェックするため１つ多く読む

			// htmlテーブルのヘッダ部作成
			StringBuffer tags = new StringBuffer();
			tags.append("<tr class='header'>\n");
			tags.append("<td class='header_no' nowrap>" + dateUnit + "／コーナー名</td>\n");
			tags.append("<td class='header_txt' nowrap>アクセス</td>\n");
			tags.append("<td class='header_txt'>％</td>\n");
			for (i = 0; i < num; i++) {
				tags.append("<td class='header_data' nowrap>");
				tags.append(CommonFunc.linkCornerValue(
					cornerLinks[i],
					"CrossCategoryAccess",
					cornerCodes[i],
					cornerNames[i],
					cornerDepth + 1
					)
				);
				tags.append("</td>\n");
			}
			tags.append("</tr>\n");

			// htmlテーブルの合計部作成
			int total = 0;
			System.out.println(sql_ttl);
			rs = st.executeQuery(sql_ttl.toString());
			if (rs.next()) {
				// 全アクセス数取得
				total = rs.getInt("ALLCOUNT");

				// 行開始
				tags.append("<tr class='total'>");

				// "合計"
				tags.append("<td class='total_txt'>合計</td>\n");

				// 合計アクセス数とパーセント
				tags.append("<td class='total_num'>").append(numFormat.format(total)).append("</td>\n");
				tags.append("<td class='total_num'>").append(CommonFunc.getParcent(total, total)).append("</td>\n");

				// コーナごとの合計アクセス数
				for(i = 0; i < num; i++)
					tags.append("<td class='total_num'>").append(numFormat.format(rs.getInt("COUNT" + i))).append("</td>\n");

				// 行終了
				tags.append("</tr>\n");
			}

			// htmlテーブルのデータ部作成
			System.out.println(sql_row);
			rs = st.executeQuery(sql_row.toString());
			int subTotal = 0;
			int no = 1; // 行番号
			forwardPage = false;
			backPage = (offset != 0);
			while (rs.next()) {
				// 次ページがあるかチェック
				if (pageSize != 0 && no > pageSize) {
					forwardPage = true;
					break;
				}

				tags.append("<tr class='data'>");

				// 期間
				tags.append("<td class='data_date'>");
				tags.append(CommonFunc.getTimePeriod(table, rs.getDate("S_DATE"), rs.getString("MHD_HOLIDAY_CLS")));
				tags.append("</td>\n");

				// 合計アクセス数とパーセント
				int allCount = rs.getInt("ALLCOUNT");
				tags.append("<td class='data_access'>").append(numFormat.format(allCount)).append("</td>\n");
				tags.append("<td class='data_percent'>").append(CommonFunc.getParcent(total, allCount)).append("</td>\n");
				subTotal += allCount;

				// 期間ごとのアクセス数
				String date = CommonFunc.getDateRange(rs.getDate("S_DATE"), selectcondBean.getTable());
				for(i = 0; i < num; i++) {
					int count = rs.getInt("COUNT" + i);
					counterTbl[i] = count;
					cornerTotals[i] += count;
					dates[i] = date;
				}
				tags.append(CommonFunc.getCountValue(counterTbl, dates, cornerCodes, true, true));//アクセス数の多い１-3位に色をつける

				tags.append("</tr>\n");

				no++;
			}

			// htmlテーブルの小計部作成
			{
				// 行開始
				tags.append("<tr class='subtotal'>");

				// "小計"
				tags.append("<td class='subtotal_txt'>小計</td>\n");

				// 小計アクセス数とパーセント
				tags.append("<td class='subtotal_num'>").append(numFormat.format(subTotal)).append("</td>\n");
				tags.append("<td class='subtotal_num'>").append(CommonFunc.getParcent(total, subTotal)).append("</td>\n");

				// コーナごとの小計アクセス数
				for(i = 0; i < num; i++)
					tags.append("<td class='subtotal_num'>").append(numFormat.format(cornerTotals[i])).append("</td>\n");

				// 行終了
				tags.append("</tr>\n");
			}

			dbdata = tags.toString();
		} catch (SQLException e) {
			dbdata = "<font color='red'>集計処理中にエラーが発生しました。<br>" + e + "</font><br>";
			e.printStackTrace();
		}//try
	}
}