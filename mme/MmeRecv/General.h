#pragma once
//*****************************************************************************************************
//  1. �t�@�C����
//		General.h
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�ėp�֐��N���X�̒�`
//----------------------------------------------------------------------------------------------------
//  3. ���l
//		����
//*****************************************************************************************************

class CGeneral
{
public:
	static void Alert(LPCTSTR pMessage, ...);
	static void GetIniPathName(CString &sIniPathName);
	static double GetPrivateProfileDouble(LPCTSTR pSession, LPCTSTR pKey, double fDefault, LPCTSTR pIniPathName);
	static void WritePrivateProfileInt(LPCTSTR pSession, LPCTSTR pKey, int nData, LPCTSTR pIniPathName);
	static void WritePrivateProfileDouble(LPCTSTR pSession, LPCTSTR pKey, double fData, LPCTSTR pIniPathName);
	static void StrSplit(LPCTSTR pText, LPCTSTR pSeparator, CStringArray &saText);
	static void StrJoin(const CStringArray &saText, LPCTSTR pSeparator, CString &sText);
};
