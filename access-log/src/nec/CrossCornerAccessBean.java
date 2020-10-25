package xxx;
import java.sql.*;
import base.*;
import java.text.*;

/**
 * �R�[�i�[�ʋƎ�ʃA�N�Z�X���z�\���쐬
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
	 * �R�[�i�[�ʋƎ�ʃA�N�Z�X���z�\�̒��g���쐬
	 */
	public void searchDB(DataBase db, SelectCondBean selectcondBean) {
		try {
			Statement st = db.createStatement();
			ResultSet rs;
			NumberFormat numFormat = NumberFormat.getNumberInstance();
			int i;

			// �R�[�i�[���A�R�[�i�[�K�w�A�J�e�S�����擾
			String corner = selectcondBean.getCorner();
			int cornerDepth = Integer.parseInt(selectcondBean.getCornerdepth());
			String category = selectcondBean.getCategory();

			// �����i�Ǝ�j�̏����i�[���邽�߂̔z��m��
			int num;
			if (category.equals("/"))
				num = CommonSql.getCategoryCount(st, category);
			else
				num = selectcondBean.getPageSize2();
			String categoryCodes[] = new String[num];   // �Ǝ�i��Ɓj�R�[�h
			String categoryNames[] = new String[num];   // �Ǝ�i��Ɓj��

			// �e�[�u���ʂ̃w�b�_�@���E�T�E���ʕω�
			String table = selectcondBean.getTable();
			String prefix = "";
			if (table.equals("MONTH")) {
				prefix = "SMCC";
			} else if (table.equals("WEEK")) {
				prefix = "SWCC";
			} else if (table.equals("DATE")) {
				prefix = "SDCC";
			}

			// where����
			StringBuffer where = new StringBuffer();
			where.append("where [PF]_ACCESS[TB]_DATE between '" + selectcondBean.getDateFrom() + "' and '" + selectcondBean.getDateTo() + "'\n");
			where.append("and [PF]_CORNERPARENT_CODE='" + corner + "'\n");
			where.append("and [PF]_CORNER_CODE<>'/' \n");
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

			int[] counterTbl = new int[num];            // �A�N�Z�X�J�E���^
			int categoryTotals[] = new int[num];        // �Ǝ�i��Ɓj�̃A�N�Z�X���v
			String cornerCodes[] = new String[num];     // �R�[�i�[�R�[�h

			// �Ǝ�i��Ɓj�R�[�h�̃J������
			String s_code;
			if (category.equals("/"))
				s_code = "[PF]_BUSINESSCATEGORY_CODE";
			else
				s_code = "[PF]_COMPANY_CODE";

			// �J�e�S���A�R�[�i�[�ʂ̃A�N�Z�X���W�v�p�T�u�N�G��
			StringBuffer q01 = new StringBuffer();
			q01.append("select ");
			q01.append("[PF]_CORNER_CODE as S_CORNER_CODE,");
			q01.append(s_code + " as S_CATEGORY_CODE,");
			q01.append("sum([PF]_ACCESSCOUNT_NUM) as S_ACCESSCOUNT_NUM \n");
			q01.append("from S_[TB]_CORNER_COMPANY \n");
			q01.append(where);
			q01.append("group by [PF]_CORNER_CODE," + s_code + " \n");

			// �J�����̐ړ����ƃe�[�u����ʖ���u��
			CommonFunc.substitute(q01, "[PF]", prefix);
			CommonFunc.substitute(q01, "[TB]", table);

			// �����ʂɏW�v���邽�߂�select��
			StringBuffer select = new StringBuffer();
			select.append("sum(S_ACCESSCOUNT_NUM) as ALLCOUNT \n");
			for (i = 0; i < num; i++)
				select.append(",sum(CASE S_CATEGORY_CODE when '" + categoryCodes[i] + "' then S_ACCESSCOUNT_NUM else 0 end) as COUNT" + i + "\n");

			// ���v���擾���邽�߂�SQL
			StringBuffer sql_ttl = new StringBuffer();
			sql_ttl.append("select " + select);
			sql_ttl.append("from (" + q01 + ") \n");

			// �s���Ƃ̃A�N�Z�X�����擾���邽�߂̃T�u�N�G��
			StringBuffer q02 = new StringBuffer();
			q02.append("select " + select + ",S_CORNER_CODE \n");
			q02.append("from (" + q01 + ") \n");
			q02.append("group by S_CORNER_CODE");

			// �\����
			String c_sort;
			if (selectcondBean.getOrder().equals("ALLCOUNT"))
				c_sort = "nvl(ALLCOUNT,0) DESC";
			else
				c_sort = "MCN_SORT" + cornerDepth + "_NUM";

			// �R�[�i�����擾���邽�߂̃T�u�N�G����
			StringBuffer q03 = new StringBuffer();
			q03.append("select MCN_CORNER_CODE as C_CODE,MCN_CORNERNAME_TXT as C_TEXT,decode(MCN_LASTLEVEL_FLG,'0',1,0) as CHILD,q02.* \n");
			q03.append("from M_CORNER t1,(" + q02 + ") q02 \n");
			q03.append("where comm.getParentCornerCode(MCN_CORNER_CODE)='" + corner + "' \n");
			q03.append(" and S_CORNER_CODE(+)=MCN_CORNER_CODE \n");
			q03.append(" and MCN_DEPTH_NUM=" + cornerDepth + "\n");
			q03.append("order by " + c_sort + "\n");

			// �K�v�Ȕ͈͂̃f�[�^�݂̂𒊏o
			StringBuffer sql_row = new StringBuffer();
			int pageSize = selectcondBean.getPageSize();
			int offset = selectcondBean.getPageNo() * pageSize;
			sql_row.append(CommonSql.RowRange(q03.toString(), offset, pageSize + 1)); // ���y�[�W�����邩�`�F�b�N���邽�߂P�����ǂ�

			// html�e�[�u���̃w�b�_���쐬
			StringBuffer tags= new StringBuffer();
			tags.append("<tr class='header'>");
			tags.append("<td class='header_no'>No.</td>\n");
			tags.append("<td class='header_txt' nowrap>�R�[�i�[��</td>\n");
			tags.append("<td class='header_txt' nowrap>�A�N�Z�X</td>\n");
			tags.append("<td class='header_txt'>��</td>\n");
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

			// html�e�[�u���̍��v���쐬
			int total = 0;
			System.out.println(sql_ttl);
			rs = st.executeQuery(sql_ttl.toString());
			if (rs.next()) {
				// �S�A�N�Z�X���擾
				total = rs.getInt("ALLCOUNT");

				// �s�J�n
				tags.append("<tr class='total'>");

				// �ԍ��i�󔒁j
				tags.append("<td>�@</td>\n");

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

			// ��Ƃ̏ꍇ�A�擪��"."��t��
			if (!category.equals("/")) {
				for (i = 0; i < num; i++)
					categoryCodes[i] = "." + categoryCodes[i];
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

				// �s�J�n
				tags.append("<tr class='data'>");

				// �ԍ�
				tags.append("<td class='data_no'>").append(no + offset).append("</td>\n");

				// �R�[�i��
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

				// ���v�A�N�Z�X���ƃp�[�Z���g
				int allCount = rs.getInt("ALLCOUNT");
				tags.append("<td class='data_access'>").append(numFormat.format(allCount)).append("</td>\n");
				tags.append("<td class='data_percent'>").append(CommonFunc.getParcent(total, allCount)).append("</td>\n");
				subTotal += allCount;

				// �Ǝ�i��Ɓj���Ƃ̃A�N�Z�X��
				for(i = 0; i < num; i++) {
					int count = rs.getInt("COUNT" + i);
					counterTbl[i] = count;
					categoryTotals[i] += count;
					cornerCodes[i] = cornerCode;
				}
				tags.append(CommonFunc.getCountValue(counterTbl, cornerCodes, categoryCodes, true, rankFlag));  //�A�N�Z�X���̑����P-3�ʂɐF������

				// �s�I��
				tags.append("</tr>\n");

				no++;
			}

			// html�e�[�u���̏��v���쐬
			{
				// �s�J�n
				tags.append("<tr class='subtotal'>");

				// �ԍ��i�󔒁j
				tags.append("<td>�@</td>\n");

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

}//class