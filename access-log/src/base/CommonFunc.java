package base;
import java.util.*;
import java.util.Date;
import java.io.*;
import java.text.*;
import javax.servlet.http.*;


/**
 * �ėp�I�Ȋ֐����i�[
 */
public class CommonFunc {

	static NumberFormat fmt = NumberFormat.getNumberInstance();

	/**
	 * �����_�ȉ��ꌅ�܂łŃp�[�Z���g��Ԃ�
	 */
	static public String getParcent(int parent, int child) {
		if (parent == 0 || child == 0)
				return "0";
		else
				return new DecimalFormat("##0.0").format((float)child / parent * 100);
	}

	/**
	 * �����񂪁@�����E���̑��E�ݒ�O�@�̏ꍇ���̕����ɐF������B
	 */
	static public String textColorValue(String Value) {
		String tags;

		if (Value.equals("���̑�"))
				tags = "<span class='other'>" + Value + "</span>";
//	else if (Value.equals("�ݒ�O"))
//	    tags = "<span class='other'>" + Value + "</span>";
		else if (Value.equals("����"))
				tags = "<span class='other'>" + Value + "</span>";
		else
				tags = Value;

		return tags;
	}

	/**
	 * �R�[�i�[���h�����_�E���ł��邩�ǂ������ꍇ�킯�@flg��false�̏ꍇ�����N�Ȃ�
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
	 * �Ǝ킪�h�����_�E���ł��邩�ǂ������ꍇ�킯
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
	 * �^����ꂽ�z��̒��̐��̂�����ʂR�ʂɐF������B
	 */
	static public String getCountValue(int[] Values, String[] vAxis, String[] hAxis, boolean linkFlag, boolean rankFlag) {
		StringBuffer tags = new StringBuffer();
		int rank;

		for(int i = 0; i < Values.length; i++) {
			if (!rankFlag || Values[i] == 0 || hAxis[i].equals("UNDEFINED")) {
				// �A�N�Z�X��0�̏ꍇ��4�ʈȉ��̐F�Ƃ���
				rank = 4;
			} else {
				// ���ʂ����߂�
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
	 * �Q�̒l���r���@��v������@selected��������
	 */
	static public String selectedFunc(String optionVal, String sessionVal) {
		if (optionVal.equals(sessionVal))
			return " selected";
		return "";
	}

	/**
	 * int�^����String�^�ɕϊ����@���@�ꌅ�̐����́@�O��0�𖄂߂Ă��
	 * �����̂ݑΉ��\
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
	* �����O���t�̍쐬
	*/
	static public String genGraphImage(int i,int j) {
		StringBuffer image = new StringBuffer();

		if (i > 0 && j > 0) {
			image.append("<img src=\"images/graph.gif\" width='");
			image.append((((long)i * 300) / j));  //GIF�̕����v�Z
			image.append("' height='18'>");
		}
		return image.toString();
	}

	/**
	* ������̒u�����s��
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
	* null�l������̒l�ɒu��
	*/
	static public String nvl(String val, String def) {
		if (val == null)
				return def;
		return val;
	}

	/**
	* �z����̒l�̓���ւ��iString)
	*/
	static public void swap(String[] array, int a, int b) {
		String temp = array[a];
		array[a] = array[b];
		array[b] = temp;
	}

	/**
	* �z����̒l�̓���ւ��iboolean)
	*/
	static public void swap(boolean[] array, int a, int b) {
		boolean temp = array[a];
		array[a] = array[b];
		array[b] = temp;
	}

	/*
	* ���Ԃ̕\��
	*/
	static public String getTimePeriod(String kind, Date date, String holiday) {
		StringBuffer fmtDate = new StringBuffer();
		SimpleDateFormat formatter;

		if (kind.equals("MONTH")) {
			// ���P�ʁFMM��
			formatter = new SimpleDateFormat("MM��");
			fmtDate.append(formatter.format(date));
		} else if (kind.equals("WEEK")) {
			// �T�P�ʁFYYYY/MM/DD-MM/DD
			GregorianCalendar date2 = new GregorianCalendar();
			date2.setTime(date);
			date2.add(Calendar.DATE, 6);

			formatter = new SimpleDateFormat("yyyy/MM/dd");
			fmtDate.append(formatter.format(date));
			fmtDate.append("-");
			formatter = new SimpleDateFormat("MM/dd");
			fmtDate.append(formatter.format(date2.getTime()));
		} else if (kind.equals("DATE")) {
			// ���P�ʁFYYYY/MM/DD�i�j���j
			GregorianCalendar cal = new GregorianCalendar();
			cal.setTime(date);
			int week = cal.get(Calendar.DAY_OF_WEEK );

			// �y�j���͐A���j�j���͐Ԃŕ\��
			if (week == 7 || holiday != null && holiday.equals("1"))
				fmtDate.append("<font color='blue'>");
			else if (week == 1 || holiday != null && holiday.equals("2"))
				fmtDate.append("<font color='red'>");
			else
				fmtDate.append("<font color='black'>");

			formatter = new SimpleDateFormat("yyyy/MM/dd�iE�j");
			fmtDate.append(formatter.format(date));

			fmtDate.append("</font>");
		} else if (kind.equals("HOUR")) {
			// ���ԒP�ʁFYYYY/MM/DD HH��
			formatter = new SimpleDateFormat("yyyy/MM/dd HH��");
			fmtDate.append(formatter.format(date));
		}

		return fmtDate.toString();
	}

	/*
	* <Option>�^�O�̏o�́ivalue�ƕ\�����قȂ�o�[�W�����j
	*/
	static public String getOptionTag(String value, String text, String selected) {
		return "<option value='" + value + "'" + CommonFunc.selectedFunc(value, selected) + ">" + text + "</option>\n";
	}

	/*
	* <Option>�^�O�̏o�́ivalue�ƕ\���������o�[�W�����j
	*/
	static public String getOptionTag(String value, String selected) {
		return "<option value='" + value + "'" + CommonFunc.selectedFunc(value, selected) + ">" + value + "</option>\n";
	}

	// ���������΍��p�R�[�h�ϊ�
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

	// �e�[�u���̎�ނɂ����t�͈̔͂�"YYYY/MM/DD-YYYY/MM/DD"�ŕԂ�
	static public String getDateRange(Date dateFrom, String table) {
		Date dateTo;

		// �J�n���t���J�����_�[�ɃZ�b�g
		GregorianCalendar cal = new GregorianCalendar();
		cal.setTime(dateFrom);

		// �e�[�u���̎�ނɂ��I�����t���擾
		if (table.equals("MONTH"))
			cal.set(Calendar.DATE, cal.getActualMaximum(Calendar.DATE));
		else if (table.equals("WEEK"))
			cal.add(Calendar.DATE, 6);

		dateTo = cal.getTime();

		// "YYYY/MM/DD-YYYY/MM/DD"�i����̏ꍇ��"YYYY/MM/DD"�j�`���Ƀt�H�[�}�b�g
		SimpleDateFormat df = new SimpleDateFormat("yyyy/MM/dd");
		if (dateFrom.equals(dateTo))
			return df.format(dateFrom);
		else
			return df.format(dateFrom) + "-" + df.format(dateTo);
	}

	// �R�[�i�[���̂���ŉ��ʑw�̖��̂����𒊏o
	static public String getLaxxxornerName(String cornerName) {
		int n = cornerName.lastIndexOf('>');
		if (n != -1)
			return cornerName.substring(n + 1);
		else
			return cornerName;
	}

	// �N�b�L�[�Ɏw�肵���L�[�ƒl������
	static public void setCookie(HttpServletResponse response, String key, String value) {
		Cookie cookie = new Cookie(key, value);
		response.addCookie(cookie);
	}

	// �N�b�L�[����w�肵���L�[�̒l��ǂ�
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

	// html���ꕶ���̃G�X�P�[�v
	static public String escapeHtml(String str) {
		StringBuffer strBuf = new StringBuffer(str);

		substitute(strBuf, "&", "&amp;");
		substitute(strBuf, "<", "&lt;");
		substitute(strBuf, ">", "&gt;");

		return strBuf.toString();
	}

 /**
	* �R�[�i�[�R�[�h����R�[�i�[�K�w���擾����
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

	// ��ʁi�e�j�R�[�i�[�R�[�h���擾
	static public String getParentCorner(String corner) {
		if (!corner.equals("/")) {
			int n = corner.lastIndexOf('/', corner.length() - 2);
			if (n != -1)
				corner = corner.substring(0, n + 1);
		}
		return corner;
	}

	// �v���p�e�B�t�@�C������URL�̃z�X�g�����擾
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

	// ���p�p���L����S�p�ɕϊ�����
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

		// �S�p�R������<br>��t�� 20030826 xxxxxxy
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