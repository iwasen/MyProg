package xxx;
import java.sql.*;
import base.*;
import java.text.*;

/**
 * コーナー別業種別アクセス分布表を作成
 */
public class CrossCornerAccessBean implements I_Access_Page {
	private String dbdata = "DB value";
	private boolean forwardPage;
	private boolean backPage;
	private boolean forwardPage2;
	private boolean backPage2;

	public String getDbdata() {
		return dbdata;
	}

	public String getDisableForwardPage() {
		return forwardPage ? "" : " disabled";
	}

	public String getDisableBackPage() {
		return backPage ? "" : " disabled";
	}

	public String getDisableForwardPage2() {
		return forwardPage2 ? "" : " disabled";
	}

	public String getDisableBackPage2() {
		return backPage2 ? "" : " disabled";
	}

	/**
	 * コーナー別業種別アクセス分布表の中身を作成
	 */
	public void searchDB(DataBase db, SelectCondBean selectcondBean) {
		try {
			Statement st = db.createStatement();
			ResultSet rs;
			NumberFormat numFormat = NumberFormat.getNumberInstance();
			int i;

			// コーナー名、コーナー階層、カテゴリを取得
			String corner = selectcondBean.getCorner();
			int cornerDepth = Integer.parseInt(selectcondBean.getCornerdepth());
			String category = selectcondBean.getCategory();

			// 横軸（業種）の情報を格納するための配列確保
			int num;
			if (category.equals("/"))
				num = CommonSql.getCategoryCount(st, category);
			else
				num = selectcondBean.getPageSize2();
			String categoryCodes[] = new String[num];   // 業種（企業）コード
			String categoryNames[] = new String[num];   // 業種（企業）名

			// テーブル別のヘッダ　日・週・月別変化
			String table = selectcondBean.getTable();
			String prefix = "";
			if (table.equals("MONTH")) {
				prefix = "SMCC";
			} else if (table.equals("WEEK")) {
				prefix = "SWCC";
			} else if (table.equals("DATE")) {
				prefix = "SDCC";
			}

			// where条件
			StringBuffer where = new StringBuffer();
			where.append("where [PF]_ACCESS[TB]_DATE between '" + selectcondBean.getDateFrom() + "' and '" + selectcondBean.getDateTo() + "'\n");
			where.append("and [PF]_CORNERPARENT_CODE='" + corner + "'\n");
			where.append("and [PF]_CORNER_CODE<>'/' \n");
			if (!category.equals("/"))
				where.append("and [PF]_BUSINESSCATEGORY_CODE='" + category + "' \n");
			CommonSql.setAttributeSql(where, selectcondBean);

			// 業種（企業）の情報を配列に取得
			if (category.equals("/"))
				rs = CommonSql.getCategoryResult(st, category);
			else {
				// 企業の場合、アクセス数の多い順に表示
				StringBuffer s01 = new StringBuffer();
				s01.append("select [PF]_COMPANY_CODE,sum([PF]_ACCESSCOUNT_NUM) as S_ACCESSCOUNT_NUM \n");
				s01.append("from S_[TB]_CORNER_COMPANY \n");
				s01.append(where);
				s01.append("group by [PF]_COMPANY_CODE");

				StringBuffer sql = new StringBuffer();
				sql.append("select MCM_COMPANY_CODE as CODE,MCM_DISPNAME_TXT as NAME,S_ACCESSCOUNT_NUM \n");
				sql.append("from M_COMPANY,(").append(s01).append(") s01 \n");
				sql.append("where MCM_BUSINESSCATEGORY_CODE='").append(category).append("'\n");
				sql.append("and MCM_COMPANY_CODE=[PF]_COMPANY_CODE(+) \n");
        sql.append(CommonSql.setAttributeSql2(selectcondBean));
				sql.append("ORDER BY NVL(S_ACCESSCOUNT_NUM,0) DESC,MCM_SORT_NUM");

				CommonFunc.substitute(sql, "[PF]", prefix);
				CommonFunc.substitute(sql, "[TB]", table);

				int pageSize2 = selectcondBean.getPageSize2();
				rs = st.executeQuery(CommonSql.RowRange(sql.toString(), selectcondBean.getPageNo2() * pageSize2, pageSize2 + 1));
			}
			forwardPage2 = false;
			backPage2 = (selectcondBean.getPageNo2() != 0);
			for (i = 0; rs.next(); i++) {
				if (i == num) {
					forwardPage2 = true;
					break;
				}

				categoryCodes[i] = rs.getString("CODE");
				categoryNames[i] = rs.getString("NAME");
			}
			num = i;

			int[] counterTbl = new int[num];            // アクセスカウンタ
			int categoryTotals[] = new int[num];        // 業種（企業）のアクセス合計
			String cornerCodes[] = new String[num];     // コーナーコード

			// 業種（企業）コードのカラム名
			String s_code;
			if (category.equals("/"))
				s_code = "[PF]_BUSINESSCATEGORY_CODE";
			else
				s_code = "[PF]_COMPANY_CODE";

			// カテゴリ、コーナー別のアクセス数集計用サブクエリ
			StringBuffer q01 = new StringBuffer();
			q01.append("select ");
			q01.append("[PF]_CORNER_CODE as S_CORNER_CODE,");
			q01.append(s_code + " as S_CATEGORY_CODE,");
			q01.append("sum([PF]_ACCESSCOUNT_NUM) as S_ACCESSCOUNT_NUM \n");
			q01.append("from S_[TB]_CORNER_COMPANY \n");
			q01.append(where);
			q01.append("group by [PF]_CORNER_CODE," + s_code + " \n");

			// カラムの接頭辞とテーブル種別名を置換
			CommonFunc.substitute(q01, "[PF]", prefix);
			CommonFunc.substitute(q01, "[TB]", table);

			// 横軸別に集計するためのselect句
			StringBuffer select = new StringBuffer();
			select.append("sum(S_ACCESSCOUNT_NUM) as ALLCOUNT \n");
			for (i = 0; i < num; i++)
				select.append(",sum(CASE S_CATEGORY_CODE when '" + categoryCodes[i] + "' then S_ACCESSCOUNT_NUM else 0 end) as COUNT" + i + "\n");

			// 合計を取得するためのSQL
			StringBuffer sql_ttl = new StringBuffer();
			sql_ttl.append("select " + select);
			sql_ttl.append("from (" + q01 + ") \n");

			// 行ごとのアクセス数を取得するためのサブクエリ
			StringBuffer q02 = new StringBuffer();
			q02.append("select " + select + ",S_CORNER_CODE \n");
			q02.append("from (" + q01 + ") \n");
			q02.append("group by S_CORNER_CODE");

			// 表示順
			String c_sort;
			if (selectcondBean.getOrder().equals("ALLCOUNT"))
				c_sort = "nvl(ALLCOUNT,0) DESC";
			else
				c_sort = "MCN_SORT" + cornerDepth + "_NUM";

			// コーナ名を取得するためのサブクエリ化
			StringBuffer q03 = new StringBuffer();
			q03.append("select MCN_CORNER_CODE as C_CODE,MCN_CORNERNAME_TXT as C_TEXT,decode(MCN_LASTLEVEL_FLG,'0',1,0) as CHILD,q02.* \n");
			q03.append("from M_CORNER t1,(" + q02 + ") q02 \n");
			q03.append("where comm.getParentCornerCode(MCN_CORNER_CODE)='" + corner + "' \n");
			q03.append(" and S_CORNER_CODE(+)=MCN_CORNER_CODE \n");
			q03.append(" and MCN_DEPTH_NUM=" + cornerDepth + "\n");
			q03.append("order by " + c_sort + "\n");

			// 必要な範囲のデータのみを抽出
			StringBuffer sql_row = new StringBuffer();
			int pageSize = selectcondBean.getPageSize();
			int offset = selectcondBean.getPageNo() * pageSize;
			sql_row.append(CommonSql.RowRange(q03.toString(), offset, pageSize + 1)); // 次ページがあるかチェックするため１つ多く読む

			// htmlテーブルのヘッダ部作成
			StringBuffer tags= new StringBuffer();
			tags.append("<tr class='header'>");
			tags.append("<td class='header_no'>No.</td>\n");
			tags.append("<td class='header_txt' nowrap>コーナー名</td>\n");
			tags.append("<td class='header_txt' nowrap>アクセス</td>\n");
			tags.append("<td class='header_txt'>％</td>\n");
			for (i = 0; i < num; i++) {
				tags.append("<td class='header_data' nowrap>");
				tags.append(CommonFunc.linkCategoryValue(
						category,
						"CrossCornerAccess",
						categoryCodes[i],
						categoryNames[i]
					)
				);
				tags.append("</td>\n") ;
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

				// 番号（空白）
				tags.append("<td>　</td>\n");

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

			// 企業の場合、先頭に"."を付加
			if (!category.equals("/")) {
				for (i = 0; i < num; i++)
					categoryCodes[i] = "." + categoryCodes[i];
			}

			// htmlテーブルのデータ部作成
			System.out.println(sql_row);
			rs = st.executeQuery(sql_row.toString());
			int subTotal = 0;
			int no = 1;
			forwardPage = false;
			backPage = (offset != 0);
			boolean rankFlag = (selectcondBean.getPageNo2() == 0);
			while (rs.next()) {
				// 次ページがあるかチェック
				if (pageSize != 0 && no > pageSize) {
					forwardPage = true;
					break;
				}

				// 行開始
				tags.append("<tr class='data'>");

				// 番号
				tags.append("<td class='data_no'>").append(no + offset).append("</td>\n");

				// コーナ名
				String cornerCode = rs.getString("C_CODE").trim();
				tags.append("<td class='data_corner'>");
				tags.append(CommonFunc.linkCornerValue(
							rs.getBoolean("CHILD"),
							"CrossCornerAccess",
							cornerCode,
							CommonFunc.getLaxxxornerName(rs.getString("C_TEXT")),
							cornerDepth + 1
					 )
				);
				tags.append("</td>\n");

				// 合計アクセス数とパーセント
				int allCount = rs.getInt("ALLCOUNT");
				tags.append("<td class='data_access'>").append(numFormat.format(allCount)).append("</td>\n");
				tags.append("<td class='data_percent'>").append(CommonFunc.getParcent(total, allCount)).append("</td>\n");
				subTotal += allCount;

				// 業種（企業）ごとのアクセス数
				for(i = 0; i < num; i++) {
					int count = rs.getInt("COUNT" + i);
					counterTbl[i] = count;
					categoryTotals[i] += count;
					cornerCodes[i] = cornerCode;
				}
				tags.append(CommonFunc.getCountValue(counterTbl, cornerCodes, categoryCodes, true, rankFlag));  //アクセス数の多い１-3位に色をつける

				// 行終了
				tags.append("</tr>\n");

				no++;
			}

			// htmlテーブルの小計部作成
			{
				// 行開始
				tags.append("<tr class='subtotal'>");

				// 番号（空白）
				tags.append("<td>　</td>\n");

				// "小計"
				tags.append("<td class='subtotal_txt'>小計</td>\n");

				// 小計アクセス数とパーセント
				tags.append("<td class='subtotal_num'>").append(numFormat.format(subTotal)).append("</td>\n");
				tags.append("<td class='subtotal_num'>").append(CommonFunc.getParcent(total, subTotal)).append("</td>\n");

				// コーナごとの小計アクセス数
				for(i = 0; i < num; i++)
					tags.append("<td class='subtotal_num'>").append(numFormat.format(categoryTotals[i])).append("</td>\n");

				// 行終了
				tags.append("</tr>\n");
			}

			dbdata = tags.toString();
		} catch (SQLException e) {
			dbdata = "<font color='red'>集計処理中にエラーが発生しました。<br>" + e + "</font><br>";
			e.printStackTrace();
		}//try
	}

}//class