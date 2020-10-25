package base;
import java.io.*;
import java.util.*;
import javax.servlet.*;
import javax.servlet.http.*;

/**
 * ���̓p�����[�^�擾�N���X
 * ����:    �p�����[�^�̎擾
 * ���쌠:   Copyright (c) 2002
 * ��Ж�:   xxxx
 * @author
 * @version
 */
public class Param{
	private HttpServletRequest req;

	/**
	 * �R���X�g���N�^
	 * @param req
	 */
	public Param(HttpServletRequest req) {
		this.req = req;
	}

	/**
	 * �p�����[�^�擾����
	 * @param paramname
	 * @return
	 * @throws ServletException
	 * @throws IOException
	 */
		public String GetParam(String paramname)
		throws ServletException, IOException
	{
		String value;
		value = req.getParameter(paramname);
		if (value != null)
			value = escapeBeta(value);
		return value;
	}

	/**
	 * �����p�����[�^�擾����
	 * @param paramname
	 * @return
	 * @throws ServletException
	 * @throws IOException
	 */
	public String[] GetParams(String paramname)
		throws ServletException, IOException
	{
		String[] value;

		value = req.getParameterValues(paramname);
		if (value != null) {
			for (int i = 0; i < value.length; i++) {
				if (value[i] != null)
					value[i] = escapeBeta(value[i]);
			}
		}
		return value;
	}

//20020703�ǉ�
	/**
	 * ���l�p�����[�^�̎擾
	 * @param paramname �p�����[�^�̖��O
	 * @param DefaultValue �p�����[�^�擾�ł��Ȃ��Ƃ��̑㗝�l
	 * @return �p�����[�^�̒l�����l�ɂȂ�ꍇ�͂��̒l�A�Ȃ�Ȃ��ꍇ��DefaultValue
	 */
	public int getInt(String paramname, int DefaultValue){
		String value="";
		int ret=0;
		try{
			value=GetParam(paramname);
		}catch(Exception e){
			e.printStackTrace();
		}
		try{
			ret=Integer.parseInt(value);
		}catch(Exception e){
			ret=DefaultValue;
		}
		return ret;
	}

	public int getInt(String paramname){
		return getInt(paramname, 0);
	}

	/**
	 * ���l�p�����[�^�̎擾
	 * @param paramname �p�����[�^�̖��O
	 * @param DefaultValue �p�����[�^�擾�ł��Ȃ��Ƃ��̑㗝�l
	 * @return �p�����[�^�̒l�����l�ɂȂ�ꍇ�͂��̒l�A�Ȃ�Ȃ��ꍇ��DefaultValue
	 */
	public long getLong(String paramname, long DefaultValue){
		String value="";
		long ret=0L;
		try{
			value=GetParam(paramname);
		}catch(Exception e){
			e.printStackTrace();
		}
		try{
			ret=Long.parseLong(value);
		}catch(Exception e){
			ret=DefaultValue;
		}
		return ret;
	}

	public long getLong(String paramname){
		return getLong(paramname, 0L);
	}

	public String getString(String ParamName, String Default){
		String ret = null;
		try{
			ret = GetParam(ParamName);
		}catch(Exception e){
		  e.printStackTrace();
		}
		if(ret == null){
		  ret = Default;
		}
	  return ret;
	}

	public String getString(String ParamName){
	  return getString(ParamName, "");
	}

	/**
	 * ����������������i����̏����j
	 * @param ps_data
	 * @return
	 * @throws ServletException
	 * @throws IOException
	 */
	private String escapeBeta(String ps_data)
		throws ServletException, IOException
	{
		String value = ps_data;
		byte[] ba_pr1 = value.getBytes("8859_1");
		byte[] ba_pr2 = new byte[ba_pr1.length + 2];
		for (int i=0; i < ba_pr1.length; i++){
			ba_pr2[i] = ba_pr1[i];
		}
		ba_pr2[ba_pr1.length] = -127;
		ba_pr2[ba_pr1.length + 1] = 64;

		value = new String(ba_pr2, "JISAutoDetect");
		value = value.substring(0, value.length() - 1);
		return value;
	}
}//class
