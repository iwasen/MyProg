package base;
import java.io.*;
import java.util.*;
import javax.servlet.*;
import javax.servlet.http.*;

/**
 * 入力パラメータ取得クラス
 * 説明:    パラメータの取得
 * 著作権:   Copyright (c) 2002
 * 会社名:   xxxx
 * @author
 * @version
 */
public class Param{
	private HttpServletRequest req;

	/**
	 * コンストラクタ
	 * @param req
	 */
	public Param(HttpServletRequest req) {
		this.req = req;
	}

	/**
	 * パラメータ取得処理
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
	 * 複数パラメータ取得処理
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

//20020703追加
	/**
	 * 数値パラメータの取得
	 * @param paramname パラメータの名前
	 * @param DefaultValue パラメータ取得できないときの代理値
	 * @return パラメータの値が数値になる場合はその値、ならない場合はDefaultValue
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
	 * 数値パラメータの取得
	 * @param paramname パラメータの名前
	 * @param DefaultValue パラメータ取得できないときの代理値
	 * @return パラメータの値が数値になる場合はその値、ならない場合はDefaultValue
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
	 * 文字化け回避処理（珠樹の処理）
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
