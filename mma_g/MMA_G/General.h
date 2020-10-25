#pragma once
//*****************************************************************************************************
//  1. �t�@�C����
//		General.h
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�ėp�֐��N���X�̒�`
//----------------------------------------------------------------------------------------------------
//  3. ���l
//----------------------------------------------------------------------------------------------------
//  4. ����
//		2007.08.09 S.Aizawa �V�K�쐬
//*****************************************************************************************************

struct SToolTipData {
	UINT nID;
	LPCTSTR pMsg;
};

class CGeneral
{
public:
	static CString FormatTime(double fSecond, int nDecimal);
	static void Alert(LPCTSTR pMessage, ...);
	static void SetSampleRateList(CComboBox &cComboBox, double fSampleRate);
	static bool DelDir(LPCSTR path);
	static void SetFilterBandList(CComboBox &cComboBox, int nCurSel);
	static void SetFilterOrderList(CComboBox &cComboBox, int nCurSel);
	static void SetFilterTypeList(CComboBox &cComboBox, int nCurSel);
	static CString FormatExp(double fData, int nDigit);
	static void SetToolTip(CToolTipCtrl &tooltip, const SToolTipData *pToolTipData, CWnd *pWnd);
	static void StrSplit(LPCTSTR pText, LPCTSTR pSeparator, CStringArray &saText);
	static void StrJoin(const CStringArray &saText, LPCTSTR pSeparator, CString &sText);
	static BOOL CheckFileName(CString sText);
	static void SetComboCategory(CComboBox &oComboBox, CString sChoiceText);
	static void SetComboMeasurementKind(CComboBox &oComboBox, CString sChoiceText);
	static BOOL CheckFileName(CString sItem, CString sText);
	static BOOL CreateDirectoryAll(LPCTSTR pDirPath);
};
