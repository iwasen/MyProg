package base;

import java.util.*;
import java.util.Date;
import java.text.*;
import java.sql.*;

/**
 * <p>�^�C�g��: ���ԑI�����X�g�\������</p>
 * <p>����: </p>
 * <p>���쌠: Copyright (c) 2003</p>
 * <p>��Ж�: </p>
 * @author aizawa
 * @version 1.0
 */

public class DateSelect {
	static final String defaultText = "�I��";

	// �N�̑I���h���b�v�_�E���쐬
	static public String getSelectYear(DataBase db, String table, String year, String name, int width) throws SQLException {
		GregorianCalendar cal = new GregorianCalendar();
		StringBuffer strbuf = new StringBuffer();

		// �R���g���[���}�X�^�擾
		ResultSet rs = db.getContrxxxaster();

		// �J�n�N�擾
		cal.setTime(rs.getDate("MCR_" + table +"FROM_DATE"));
		int fromYear = cal.get(Calendar.YEAR);

		// �I���N�擾
		cal.setTime(rs.getDate("MCR_" + table +"TO_DATE"));
		int toYear = cal.get(Calendar.YEAR);

		// <select>�^�O
		strbuf.append("<select name='" + name + "' size=1 style='width:" + width + "' onchange='changeYear(this)'>\n");

		// "�I��"���Z�b�g
		strbuf.append("<option value=''>").append(defaultText).append("</option>\n");

		// �J�n�N�`�I���N���Z�b�g
		for (int i = toYear; i >= fromYear; i--) {
			strbuf.append(CommonFunc.getOptionTag(CommonFunc.numToString(i), year));
		}

		strbuf.append("</select>\n");

		return strbuf.toString();
	}

	// ���̑I���h���b�v�_�E���쐬
	static public String getSelectMonth(String month, String name, int width) {
		StringBuffer strbuf = new StringBuffer();

		// <select>�^�O
		strbuf.append("<select name='" + name + "' size=1 style='width:" + width + "'>\n");

		// "�I��"���Z�b�g
		strbuf.append("<option value=''>").append(defaultText).append("</option>\n");

		// �P���`12���Z�b�g
		for (int i = 1; i <= 12; i++) {
			strbuf.append(CommonFunc.getOptionTag(CommonFunc.numToString(i), month));
		}

		strbuf.append("</select>\n");

		return strbuf.toString();
	}


	// �T�̑I���h���b�v�_�E���쐬
	static public String getSelectWeek(DataBase db, String week, String name, int width) throws SQLException {
		GregorianCalendar cal = new GregorianCalendar();
		StringBuffer strbuf = new StringBuffer();
		SimpleDateFormat fmt = new SimpleDateFormat("yyyy/MM/dd");

		// �R���g���[���}�X�^�擾
		ResultSet rs = db.getContrxxxaster();

		// �J�n�N�擾
		cal.setTime(rs.getDate("MCR_WEEKFROM_DATE"));
		int fromYear = cal.get(Calendar.YEAR);

		// �I���N�擾
		cal.setTime(rs.getDate("MCR_WEEKTO_DATE"));
		int toYear = cal.get(Calendar.YEAR);

		// �_�~�[�̏T���X�g�i�N�����I���̎��ɕ\���j
		strbuf.append("<select name='" + name + "' size=1 style='width:" + width + "'>\n");
		strbuf.append("<option value=''>").append(defaultText).append("</option>\n");
		strbuf.append("</select>\n");

		// �J�n�N�`�I���N�܂ł̏T���X�g�쐬
		for (int i = fromYear; i <= toYear; i++) {
			// <select>�^�O
			strbuf.append("<select name='" + name + i + "' size=1 style='width:" + width + ";display:none'>\n");

			// "�I��"��ǉ�
			strbuf.append("<option value=''>").append(defaultText).append("</option>\n");

			// �T�̊J�n�N�����擾
			cal.set(i, 0, 1);
			cal.add(Calendar.DATE, 2 - cal.get(Calendar.DAY_OF_WEEK));

			// �N���̏T���Z�b�g
			int w = 1;
			while (cal.get(Calendar.YEAR) <= i) {
				String ymd = fmt.format(cal.getTime());
				strbuf.append("<option value='" + ymd + "'" +  CommonFunc.selectedFunc(ymd, week) + ">");
				strbuf.append(CommonFunc.numToString(w++) + "�T");
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

	// ���̑I���h���b�v�_�E���쐬
	static public String getSelectDay(String day, String name, int width) {
		StringBuffer strbuf = new StringBuffer();

		// <select>�^�O
		strbuf.append("<select name='" + name + "' size=1 style='width:" + width + "'>\n");

		// "�I��"��ǉ�
		strbuf.append("<option value=''>").append(defaultText).append("</option>\n");

		for (int i = 1; i <= 31; i++) {
			strbuf.append(CommonFunc.getOptionTag(CommonFunc.numToString(i), day));
		}

		strbuf.append("</select>\n");

		return strbuf.toString();
	}

	// ���̑I���h���b�v�_�E���쐬
	static public String getSelectHour(String hour, String name, int width) {
		StringBuffer strbuf = new StringBuffer();

		// <select>�^�O
		strbuf.append("<select name='" + name + "' size=1 style='width:" + width + "'>\n");

		// "�I��"��ǉ�
		strbuf.append("<option value=''>").append(defaultText).append("</option>\n");

		for (int i = 0; i <= 23; i++) {
			strbuf.append(CommonFunc.getOptionTag(CommonFunc.numToString(i), hour));
		}

		strbuf.append("</select>\n");

		return strbuf.toString();
	}
}