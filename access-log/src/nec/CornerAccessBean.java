package xxx;
import base.*;
import java.sql.*;
import java.text.*;


/**
 * �R�[�i�[�ʃA�N�Z�X���ڕ\���쐬
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
	 * �R�[�i�[�ʃA�N�Z�X���ڕ\�̒��g���쐬
	 */
	public void searchDB(DataBase db, SelectCondBean selectcondBean) {
		try{
			Statement st = db.createStatement();
			ResultSet rs;
			NumberFormat numFormat = NumberFormat.getNumberInstance();
			int i;

			// �R�[�i�[�A�K�w�A�J�e�S�����擾
			String corner = selectcondBean.getCorner();
			int cornerDepth = Integer.parseInt(selectcondBean.getCornerdepth());
			String category = selectcondBean.getCategory();

			// �����i�R�[�i�[�j�̏����i�[���邽�߂̔z��m��
			int num = CommonSql.getCornerCount(st, corner, cornerDepth) + 1;
			String cornerCodes[] = new String[num];     // �R�[�i�[�R�[�h
			String cornerNames[] = new String[num];     // �R�[�i�[����
			boolean cornerLinks[] = new boolean[num];   // �R�[�i�[�̃����N�t���O
			int cornerTotals[] = new int[num];          // �R�[�i�[�̃A�N�Z�X���v
			int counterTbl[] = new int[num];            // �A�N�Z�X�J�E���^
			String dates[] = new String[num];           // ����

			// �R�[�i�[�̏��i�R�[�i�[�R�[�h�A�R�[�i�[���A�����N�̗L���j��z��Ɏ擾
			rs = CommonSql.getCornerResult(st, corner, cornerDepth);
			for (i = 0; i < num && rs.next(); i++) {
				cornerCodes[i] = rs.getString("cornercode").trim();
				cornerNames[i] = CommonFunc.getLaxxxornerName(rs.getString("cornername"));
				if (selectcondBean.getCornerdepth().equals("6"))
					cornerLinks[i] = false;   // �R�[�i�̐[�����U�̏ꍇ�����N�؂�@0
				else
					cornerLinks[i] = rs.getBoolean("link");
			}

			// �u�����v�̃����N����ǉ��@�i�R�[�i�����̃y�[�W�ւ̃A�N�Z�X�́@�ꊇ�@�R�[�i�R�[�h/$�@�ŋL�^�����j
			// �܂�R�[�i�ꗗ�Ɂu�����v�Ƃ����R�[�i�R�[�h��ǉ����Ă���B
			cornerCodes[i] = corner + "$/";
			cornerNames[i] = "����";
			cornerLinks[i] = false;

			// �R�[�i�P�K�w�ڂɂ́u�ݒ�O�v�Ƃ����R�[�i�����݂���i�ǂ̃R�[�i�ɂ��Y�����Ȃ��A�N�Z�X�j�B
			// �v���O�����ł́u�����v�������΂�E�ɂ��邪
			// �f�U�C����́u�ݒ�O�v����ԉE�Ȃ̂Œl�����ւ��Ă��B
			if (cornerDepth == 1 && i > 1 && cornerCodes[i - 1].equals("/NOCODE/")) {
				CommonFunc.swap(cornerCodes, i, i - 1);
				CommonFunc.swap(cornerNames, i, i - 1);
				CommonFunc.swap(cornerLinks, i, i - 1);
			}

			// �e�[�u���ʂ̃w�b�_�@���E�T�E���ʕω�
			String table = selectcondBean.getTable();
			String prefix = "", dateUnit = "";
			if (table.equals("MONTH")) {
				prefix = "SMCC";
				dateUnit = "��";
			} else if (table.equals("WEEK")) {
				prefix = "SWCC";
				dateUnit = "�T";
			} else if (table.equals("DATE")) {
				prefix = "SDCC";
				dateUnit = "���t";
			}

			// ���t�A�J�e�S���ʂ̃A�N�Z�X���W�v�p�T�u�N�G��
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

			// �J�����̐ړ����ƃe�[�u����ʖ���u��
			CommonFunc.substitute(q01, "[PF]", prefix);
			CommonFunc.substitute(q01, "[TB]", table);

			// �����ʂɏW�v���邽�߂�select��
			StringBuffer select = new StringBuffer();
			select.append("sum(S_ACCESSCOUNT_NUM) as ALLCOUNT \n");
			for (i = 0; i < num; i++)
				select.append(",sum(CASE S_CORNER_CODE when '" + cornerCodes[i] + "' then S_ACCESSCOUNT_NUM else 0 end) as COUNT" + i + "\n");

			// ���v���擾���邽�߂�SQL
			StringBuffer sql_ttl = new StringBuffer();
			sql_ttl.append("select " + select);
			sql_ttl.append("from (" + q01 + ") \n");

			// �s���Ƃ̃A�N�Z�X�����擾���邽�߂̃T�u�N�G��
			StringBuffer q02 = new StringBuffer();
			q02.append("select " + select + ",S_DATE \n");
			q02.append("from (" + q01 + ") \n");
			q02.append("group by S_DATE");

			// �x���}�X�^�ƌ���
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
			tags.append("<td class='header_no' nowrap>" + dateUnit + "�^�R�[�i�[��</td>\n");
			tags.append("<td class='header_txt' nowrap>�A�N�Z�X</td>\n");
			tags.append("<td class='header_txt'>��</td>\n");
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
			int no = 1; // �s�ԍ�
			forwardPage = false;
			backPage = (offset != 0);
			while (rs.next()) {
				// ���y�[�W�����邩�`�F�b�N
				if (pageSize != 0 && no > pageSize) {
					forwardPage = true;
					break;
				}

				tags.append("<tr class='data'>");

				// ����
				tags.append("<td class='data_date'>");
				tags.append(CommonFunc.getTimePeriod(table, rs.getDate("S_DATE"), rs.getString("MHD_HOLIDAY_CLS")));
				tags.append("</td>\n");

				// ���v�A�N�Z�X���ƃp�[�Z���g
				int allCount = rs.getInt("ALLCOUNT");
				tags.append("<td class='data_access'>").append(numFormat.format(allCount)).append("</td>\n");
				tags.append("<td class='data_percent'>").append(CommonFunc.getParcent(total, allCount)).append("</td>\n");
				subTotal += allCount;

				// ���Ԃ��Ƃ̃A�N�Z�X��
				String date = CommonFunc.getDateRange(rs.getDate("S_DATE"), selectcondBean.getTable());
				for(i = 0; i < num; i++) {
					int count = rs.getInt("COUNT" + i);
					counterTbl[i] = count;
					cornerTotals[i] += count;
					dates[i] = date;
				}
				tags.append(CommonFunc.getCountValue(counterTbl, dates, cornerCodes, true, true));//�A�N�Z�X���̑����P-3�ʂɐF������

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
					tags.append("<td class='subtotal_num'>").append(numFormat.format(cornerTotals[i])).append("</td>\n");

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