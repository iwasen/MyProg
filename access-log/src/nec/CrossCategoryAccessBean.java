package xxx;
import java.sql.*;
import base.*;
import java.text.*;

/**
* �Ǝ�ʃR�[�i�[�ʃA�N�Z�X���z�\���쐬
*/
public class CrossCategoryAccessBean implements I_Access_Page {
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
	* �Ǝ�ʃR�[�i�[�ʃA�N�Z�X���z�\�̒��g���쐬
	*/
	public void searchDB(DataBase db, SelectCondBean selectcondBean) {
		try {
			Statement st = db.createStatement();
			ResultSet rs;
			NumberFormat numFormat = NumberFormat.getNumberInstance();
			int i;

			// �R�[�i�[���A�K�w�A�J�e�S�����擾
			String corner = selectcondBean.getCorner();
			int cornerDepth = Integer.parseInt(selectcondBean.getCornerdepth());
			String category = selectcondBean.getCategory();

			// ���及�L�̃R�[�i�[���`�F�b�N
			String groupCorner = selectcondBean.getGroupCorner(db);
			if (groupCorner.length() > corner.length() || !groupCorner.equals(corner.substring(0, groupCorner.length()))) {
				corner = groupCorner;
				selectcondBean.setCorner(corner);
				cornerDepth = CommonSql.getCornerDepth(st, corner) + 1;
				selectcondBean.setCornerdepth(String.valueOf(cornerDepth));
			}

			// �����i�R�[�i�[�j�̏����i�[���邽�߂̔z��m��
			int num = CommonSql.getCornerCount(st, corner, cornerDepth) + 1;
			String cornerCodes[] = new String[num];       // �R�[�i�R�[�h
			String cornerNames[] = new String[num];       // �R�[�i����
			boolean cornerLinks[] = new boolean[num];     // �R�[�i�̃����N�t���O
			int cornerTotals[] = new int[num];            // �R�[�i�[�̃A�N�Z�X���v
			String categoryCodes[] = new String[num];     // �J�e�S���R�[�h
			int[] counterTbl = new int[num];              // �A�N�Z�X�J�E���^

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
			String prefix = "";
			if (selectcondBean.getTable().equals("MONTH")) {
				prefix = "SMCC";
			} else if (selectcondBean.getTable().equals("WEEK")) {
				prefix = "SWCC";
			} else if (selectcondBean.getTable().equals("DATE")) {
				prefix = "SDCC";
			}

			// �Ǝ�i��Ɓj�R�[�h�̃J�������擾
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
			q01.append("where ");
			q01.append("[PF]_ACCESS[TB]_DATE between '" + selectcondBean.getDateFrom() + "' and '" + selectcondBean.getDateTo() + "'\n");
			q01.append("and [PF]_CORNERPARENT_CODE='" + corner + "'\n");
			q01.append("and [PF]_CORNER_CODE<>'/'\n");
			if (!category.equals("/"))
				q01.append("and [PF]_BUSINESSCATEGORY_CODE='" + category + "' \n");
			CommonSql.setAttributeSql(q01, selectcondBean);
			q01.append("group by [PF]_CORNER_CODE," + s_code + " \n");

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
			q02.append("select " + select + ",S_CATEGORY_CODE \n");
			q02.append("from (" + q01 + ") \n");
			q02.append("group by S_CATEGORY_CODE");

			// �Ǝ�i��Ɓj�����擾���邽�߂̏���
			String c_table, c_code, c_text, c_sort, c_where;
			if (category.equals("/")) {
				c_table = "M_BUSINESSCATEGORY";
				c_code = "MBC_BUSINESSCATEGORY_CODE";
				c_text = "MBC_DISPNAME_TXT";
				c_sort = "MBC_SORT_NUM";
				c_where = "";
			} else {
				c_table = "M_COMPANY";
				c_code = "MCM_COMPANY_CODE";
				c_text = "MCM_DISPNAME_TXT";
				c_sort = "MCM_SORT_NUM";
				c_where = "and MCM_BUSINESSCATEGORY_CODE='" + category + "' \n" + CommonSql.setAttributeSql2(selectcondBean);
			}

			// �A�N�Z�X���̏ꍇ�i���̑��͈�ԉ��ɂ���j
			if (selectcondBean.getOrder().equals("ALLCOUNT"))
				c_sort = "DECODE(" + c_code + ",'UNDEFINED', -1, NVL(ALLCOUNT,0)) DESC";

			// �Ǝ�i��Ɓj�����擾���邽�߂̃T�u�N�G����
			StringBuffer q03 = new StringBuffer();
			q03.append("select " + c_code + " as C_CODE," + c_text + " as C_TEXT,q02.* \n");
			q03.append("from " + c_table + ",(" + q02 + ") q02 \n");
			q03.append("where S_CATEGORY_CODE(+)=" + c_code + "\n");
			q03.append(c_where);
			q03.append("order by " + c_sort + "\n");

			// �K�v�Ȕ͈͂̃f�[�^�݂̂𒊏o
			int pageSize;
			int offset;
			StringBuffer sql_row = new StringBuffer();
			if (category.equals("/")) {
				pageSize = 0;
				offset = 0;
				sql_row.append(q03);
			} else {
				pageSize = selectcondBean.getPageSize();
				offset = selectcondBean.getPageNo() * pageSize;
				sql_row.append(CommonSql.RowRange(q03.toString(), offset, pageSize + 1)); // ���y�[�W�����邩�`�F�b�N���邽�߂P�����ǂ�
			}

			// html�e�[�u���̃w�b�_���쐬
			StringBuffer tags = new StringBuffer();
			tags.append("<tr class='header'>");
			tags.append("<td class='header_no'>No.</td>\n");
			tags.append("<td class='header_txt' nowrap>�� �� ��</td>\n");
			tags.append("<td class='header_txt' nowrap>�A�N�Z�X</td>\n");
			tags.append("<td class='header_txt'>��</td>\n");
			for(i = 0; i < num; i++){
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

			// html�e�[�u���̃f�[�^���쐬
			System.out.println(sql_row);
			rs = st.executeQuery(sql_row.toString());
			int subTotal = 0; // ���v
			int no = 1; // �s�ԍ�
			forwardPage = false;
			backPage = (offset != 0);
			while(rs.next()){
				// ���y�[�W�����邩�`�F�b�N
				if (pageSize != 0 && no > pageSize) {
					forwardPage = true;
					break;
				}

				// �s�J�n
				tags.append("<tr class='data'>");

				// �ԍ�
				tags.append("<td class='data_no'>").append(no + offset).append("</td>\n");

				// �Ǝ�i��Ɓj��
				String categoryCode = rs.getString("C_CODE").trim();
				tags.append("<td class='data_category'>");
				tags.append(CommonFunc.linkCategoryValue(
						category,
						"CrossCategoryAccess",
						categoryCode,
						rs.getString("C_TEXT").trim()
					)
				);
				tags.append("</td>\n");

				// ���v�A�N�Z�X���ƃp�[�Z���g
				int allCount = rs.getInt("ALLCOUNT");
				tags.append("<td class='data_access'>").append(numFormat.format(allCount)).append("</td>\n");
				tags.append("<td class='data_percent'>").append(CommonFunc.getParcent(total, allCount)).append("</td>\n");
				subTotal += allCount;

				// �R�[�i���Ƃ̃A�N�Z�X��
				for(i = 0; i < num; i++) {
					int count = rs.getInt("COUNT" + i);
					counterTbl[i] = count;
					cornerTotals[i] += count;
					if (!category.equals("/"))   // �Ǝ�R�[�h�Ɗ�ƃR�[�h����ʂ��邽�ߊ�ƃR�[�h�̐擪��"."��t��
						categoryCodes[i] = "." + categoryCode;
					else
						categoryCodes[i] = categoryCode;
				}
				tags.append(CommonFunc.getCountValue(counterTbl, categoryCodes, cornerCodes, true, true));  //�A�N�Z�X���̑����P-3�ʂɐF������

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
					tags.append("<td class='subtotal_num'>").append(numFormat.format(cornerTotals[i])).append("</td>\n");

				// �s�I��
				tags.append("</tr>\n");
			}

			// html��ۑ�
			dbdata = tags.toString();
		} catch (SQLException e) {
			dbdata = "<font color='red'>�W�v�������ɃG���[���������܂����B<br>" + e + "</font><br>";
			e.printStackTrace();
		}//try
	}
}//class