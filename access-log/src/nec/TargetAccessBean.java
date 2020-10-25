package xxx;
import base.*;
import java.sql.*;
import java.text.*;

/**
 * ����y�[�W�A�N�Z�X���ڕ\���쐬
 */
public class TargetAccessBean implements I_Access_Page {
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
	 * ����y�[�W�A�N�Z�X���ڕ\�̒��g���쐬
	 */
	public void searchDB(DataBase db, SelectCondBean selectcondBean) {
		try {
			// �ΏۃR�[�i�[���I������Ă��Ȃ���Ή������Ȃ�
			String targetCorner = selectcondBean.getTargetCorner();
			if (targetCorner.length() == 0)
				return;

			Statement st = db.createStatement();
			ResultSet rs;
			NumberFormat numFormat = NumberFormat.getNumberInstance();
			int i;

			// �J�e�S���R�[�h���擾
			String category = selectcondBean.getCategory();

			// �ΏۃR�[�i�[ or URL���擾
			String table = selectcondBean.getTargetCornerTable();
			String corner = selectcondBean.getTargetCornerOrUrl();

			// �����i�Ǝ�j�̏����i�[���邽�߂̔z��m��
			int num;
			if (category.equals("/"))
				num = CommonSql.getCategoryCount(st, category);
			else
				num = selectcondBean.getPageSize2();
			String categoryCodes[] = new String[num];   // �Ǝ�i��Ɓj�R�[�h
			String categoryNames[] = new String[num];   // �Ǝ�i��Ɓj��

			// �e�[�u���ʂ̃w�b�_�iURL�E�R�[�i�[�j
			String prefix = "", urlCorner = "";
			if (table.equals("CORNER")) {
				prefix = "xxxC";
				urlCorner = "[PF]_[TB]_CODE";
			} else {
				prefix = "STUC";
				urlCorner = "[PF]_[TB]_TXT";
			}

			// ���Ԃɂ��O���[�s���O����
			String dateTable = selectcondBean.getTable();
			String dateCol, dateFmt, dateUnit;
			if (dateTable.equals("DATE")) {
				dateCol = "trunc([PF]_ACCESSDH_DATE,'DD')";
				dateFmt = "YYYY/MM/DD";
				dateUnit = "���t";
			} else {
				dateCol = "[PF]_ACCESSDH_DATE";
				dateFmt = "YYYY/MM/DD HH24:MI:SS";
				dateUnit = "����";
			}

			// where����
			StringBuffer where = new StringBuffer();
			where.append("where " + dateCol + " between to_date('" + selectcondBean.getDateFrom() + "','" + dateFmt + "') and to_date('" + selectcondBean.getDateTo() + "','" + dateFmt + "') \n");
			where.append("and " + urlCorner + "='" + corner + "'\n");
			if (!category.equals("/"))
				where.append("and [PF]_BUSINESSCATEGORY_CODE='" + category + "' \n");
			CommonSql.setAttributeSql(where, selectcondBean);

			// �Ǝ�i��Ɓj�̏���z��Ɏ擾
			if (category.equals("/"))
				rs = CommonSql.getCategoryResult(st, category);
			else {
				// ��Ƃ̏ꍇ�A�A�N�Z�X���̑������ɕ\��
				StringBuffer s01 = new StringBuffer();
				s01.append("select [PF]_COMPANY_CODE,sum([PF]_ACCESSCOUNT_NUM) as S_ACCESSCOUNT_NUM \n");
				s01.append("from S_TIME_[TB]_COMPANY \n");
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

			int categoryTotals[] = new int[num];        // �Ǝ�i��Ɓj�̃A�N�Z�X���v
			int counterTbl[] = new int[num];            // �A�N�Z�X�J�E���^
			String dates[] = new String[num];           // ����

			// �Ǝ�i��Ɓj�R�[�h�̃J������
			String c_code;
			if (category.equals("/"))
				c_code = "[PF]_BUSINESSCATEGORY_CODE";
			else
				c_code = "[PF]_COMPANY_CODE";

			// ���t�A�J�e�S���ʂ̃A�N�Z�X���W�v�p�T�u�N�G��
			StringBuffer q01 = new StringBuffer();
			q01.append("select ");
			q01.append(dateCol + " as S_DATE,");
			q01.append(c_code + " as S_CATEGORY_CODE,");
			q01.append("sum([PF]_ACCESSCOUNT_NUM) as S_ACCESSCOUNT_NUM \n");
			q01.append("from S_TIME_[TB]_COMPANY \n");
			q01.append(where);
			q01.append("group by " + dateCol + "," + c_code + " \n");

			// �J�����̐ړ����ƃe�[�u����ʖ���u��
			CommonFunc.substitute(q01, "[PF]", prefix);
			CommonFunc.substitute(q01, "[TB]", table);

			// �����ʂɏW�v���邽�߂�select��
			StringBuffer select = new StringBuffer();
			select.append("sum(S_ACCESSCOUNT_NUM) as ALLCOUNT \n");
			for (i = 0; i < num; i++)
				select.append(",sum(case S_CATEGORY_CODE when '" + categoryCodes[i] + "' then S_ACCESSCOUNT_NUM else 0 end) as COUNT" + i + "\n");

			// ���v���擾���邽�߂�SQL
			StringBuffer sql_ttl = new StringBuffer();
			sql_ttl.append("select " + select);
			sql_ttl.append("from (" + q01 + ") \n");

			// �s���Ƃ̃A�N�Z�X�����擾���邽�߂̃T�u�N�G��
			StringBuffer q02 = new StringBuffer();
			q02.append("select " + select + ",S_DATE \n");
			q02.append("from (" + q01 + ") \n");
			q02.append("group by S_DATE");

			// ���sSQL�쐬
			StringBuffer q03 = new StringBuffer();
			q03.append("select q02.*,MHD_HOLIDAY_CLS \n");
			q03.append("from (" + q02 + ") q02 \n");
			q03.append(",M_HOLIDAY \n");
			q03.append("where S_DATE=MHD_HOLIDAY_DATE(+) \n");
			q03.append("order by S_DATE \n");

			// �K�v�Ȕ͈͂̃f�[�^�݂̂𒊏o
			StringBuffer sql_row = new StringBuffer();
			int pageSize = selectcondBean.getPageSize();
			int offset = selectcondBean.getPageNo() * pageSize;
			sql_row.append(CommonSql.RowRange(q03.toString(), offset, pageSize + 1)); // ���y�[�W�����邩�`�F�b�N���邽�߂P�����ǂ�

			// html�e�[�u���̃w�b�_���쐬
			StringBuffer tags = new StringBuffer();
			tags.append("<tr class='header'>\n");
			tags.append("<td class='header_txt' nowrap>" + dateUnit + "�^�Ǝ햼</td>\n");
			tags.append("<td class='header_txt' nowrap>�A�N�Z�X</td>\n");
			tags.append("<td class='header_txt'>��</td>\n");
			for (i = 0; i < num; i++) {
				 tags.append("<td class='header_data' nowrap>");
				 tags.append(CommonFunc.linkCategoryValue(
							category,
							"CategoryAccess",
							categoryCodes[i],
							categoryNames[i]
						)
					);
				 tags.append("</td>\n");
			}
			tags.append("</tr>\n");

			// html�e�[�u���̍��v���쐬
			int total = 0;
			System.out.println(sql_ttl);
			rs = st.executeQuery(sql_ttl.toString());
			if (rs.next()) {
				// �S�A�N�Z�X���擾
				total = rs.getInt("ALLCOUNT");

				// �s�J�n
				tags.append("<tr class='total'>");

				// "���v"
				tags.append("<td class='total_txt'>���v</td>\n");

				// ���v�A�N�Z�X���ƃp�[�Z���g
				tags.append("<td class='total_num'>").append(numFormat.format(total)).append("</td>\n");
				tags.append("<td class='total_num'>").append(CommonFunc.getParcent(total, total)).append("</td>\n");

				// �R�[�i���Ƃ̍��v�A�N�Z�X��
				for(i = 0; i < num; i++)
					tags.append("<td class='total_num'>").append(numFormat.format(rs.getInt("COUNT" + i))).append("</td>\n");

				// �s�I��
				tags.append("</tr>\n");
			}

			// html�e�[�u���̃f�[�^���쐬
			System.out.println(sql_row);
			rs = st.executeQuery(sql_row.toString());
			int subTotal = 0;
			int no = 1;
			forwardPage = false;
			backPage = (offset != 0);
			boolean rankFlag = (selectcondBean.getPageNo2() == 0);
			while (rs.next()) {
				// ���y�[�W�����邩�`�F�b�N
				if (pageSize != 0 && no > pageSize) {
					forwardPage = true;
					break;
				}

				tags.append("<tr class='data'>");

				// ����
				tags.append("<td class='data_date'>");
				tags.append(CommonFunc.getTimePeriod(dateTable, rs.getTimestamp("S_DATE"), rs.getString("MHD_HOLIDAY_CLS")));
				tags.append("</td>\n");

				// ���v�A�N�Z�X���ƃp�[�Z���g
				int allCount = rs.getInt("ALLCOUNT");
				tags.append("<td class='data_access'>").append(numFormat.format(allCount)).append("</td>\n");
				tags.append("<td class='data_percent'>").append(CommonFunc.getParcent(total, allCount)).append("</td>\n");
				subTotal += allCount;

				// ���Ԃ��Ƃ̃A�N�Z�X��
				String date = CommonFunc.getDateRange(rs.getDate("S_DATE"), dateTable);
				for(i = 0; i < num; i++) {
					int count = rs.getInt("COUNT" + i);
					counterTbl[i] = count;
					categoryTotals[i] += count;
					dates[i] = date;
				}
				tags.append(CommonFunc.getCountValue(counterTbl, dates, categoryCodes, table.equals("CORNER"), rankFlag));  //�A�N�Z�X���̑����P-3�ʂɐF������

				tags.append("</tr>\n");

				no++;
			}

			// html�e�[�u���̏��v���쐬
			{
				// �s�J�n
				tags.append("<tr class='subtotal'>");

				// "���v"
				tags.append("<td class='subtotal_txt'>���v</td>\n");

				// ���v�A�N�Z�X���ƃp�[�Z���g
				tags.append("<td class='subtotal_num'>").append(numFormat.format(subTotal)).append("</td>\n");
				tags.append("<td class='subtotal_num'>").append(CommonFunc.getParcent(total, subTotal)).append("</td>\n");

				// �R�[�i���Ƃ̏��v�A�N�Z�X��
				for(i = 0; i < num; i++)
					tags.append("<td class='subtotal_num'>").append(numFormat.format(categoryTotals[i])).append("</td>\n");

				// �s�I��
				tags.append("</tr>\n");
			}

			dbdata = tags.toString();
		} catch (SQLException e) {
			dbdata = "<font color='red'>�W�v�������ɃG���[���������܂����B<br>" + e + "</font><br>";
			e.printStackTrace();
		}//try
	}


}