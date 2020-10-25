#pragma once
//*****************************************************************************************************
//  1. ファイル名
//		TimeRangeDlg.h
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		時間範囲設定ダイアログクラスの定義
//----------------------------------------------------------------------------------------------------
//  3. 備考
//----------------------------------------------------------------------------------------------------
//  4. 履歴
//		2007.08.09 S.Aizawa 新規作成
//*****************************************************************************************************

#include "MyCtrl.h"
#include "datadatetime.h"


// CTimeRangeDlg ダイアログ

class CTimeRangeDlg : public CDialog
{
	DECLARE_DYNAMIC(CTimeRangeDlg)

public:
	CTimeRangeDlg(CWnd* pParent = NULL);   // 標準コンストラクタ

	enum { IDD = IDD_TIME_RANGE };

public:
	int m_nDataSec;
	int	m_nDispBegin;
	int	m_nDispEnd;
	bool m_bDspFlag;
	CDataDateTime m_dDspFrom;
	CDataDateTime m_dDspTo;
	CDataDateTime m_dStarttime;
	CDataDateTime m_dEndtime;
	CDataDateTime m_dInStarttime;
	CDataDateTime m_dInEndtime;
	CString m_sKind;

protected:
	CToolTipCtrl m_tooltip;

protected:
	CString ConvertSecondToHMS(int nSecond);
	BOOL CheckInputData();

	DECLARE_MESSAGE_MAP()
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート
	virtual BOOL OnInitDialog();
	virtual void OnOK();
	virtual BOOL PreTranslateMessage(MSG* pMsg);

	CEditEx m_cEditDataBegin;
	CEditEx m_cEditDataEnd;
	CEditEx m_Edit_s_yy;
	CEditEx m_Edit_s_mm;
	CEditEx m_Edit_s_dd;
	CEditEx m_Edit_s_h;
	CEditEx m_Edit_s_m;
	CEditEx m_Edit_s_s;
	CEditEx m_Edit_e_yy;
	CEditEx m_Edit_e_mm;
	CEditEx m_Edit_e_dd;
	CEditEx m_Edit_e_h;
	CEditEx m_Edit_e_m;
	CEditEx m_Edit_e_s;
};
