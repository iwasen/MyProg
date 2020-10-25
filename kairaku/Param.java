//**********************************************************************
//		���y�s��.co.jp �o�X�P�b�g�V�X�e��
//
//		Title:		�p�����[�^���͏���
//		FileName:	Param.java
//		Creator:	Shinichi Aizawa
//		CreateDate:	2000/04/14
//**********************************************************************

import java.io.*;
import java.util.*;
import javax.servlet.*;
import javax.servlet.http.*;

// ���̓p�����[�^�擾�N���X
public class Param
{
	private HttpServletRequest req;

	public Param(HttpServletRequest req) {
		this.req = req;
	}

	// �p�����[�^�擾����
	public String GetParam(String paramname)
		throws ServletException, IOException
	{
		String value;

		value = req.getParameter(paramname);
		if (value != null)
			value = new String(value.getBytes("8859_1"), "JISAutoDetect");

		return value;
	}

	// �����p�����[�^�擾����
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

