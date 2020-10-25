//**********************************************************************
//		MCDB:新聞:○○BOOK TIMES アンケート収集システム
//
//		Title:		パラメータ入力処理
//		FileName:	Param.java
//		Creator:	Shinichi Aizawa
//		CreateDate:	2000/05/19
//**********************************************************************

import java.io.*;
import java.util.*;
import javax.servlet.*;
import javax.servlet.http.*;

// 入力パラメータ取得クラス
public class Param
{
	private HttpServletRequest req;

	public Param(HttpServletRequest req) {
		this.req = req;
	}

	// パラメータ取得処理
	public String GetParam(String paramname)
		throws ServletException, IOException
	{
		String value;

		value = req.getParameter(paramname);
		if (value != null)
			value = new String(value.getBytes("8859_1"), "JISAutoDetect");

		return value;
	}

	// 複数パラメータ取得処理
	public String[] GetParams(String paramname)
		throws ServletException, IOException
	{
		String[] value;

		value = req.getParameterValues(paramname);
		if (value != null) {
			for (int i = 0; i < value.length; i++) {
				if (value[i] != null)
					value[i] = new String(value[i].getBytes("8859_1"), "JISAutoDetect");
			}
		}

		return value;
	}
}

