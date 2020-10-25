#pragma once

#include "MyCtrl.h"
#include "datadatetime.h"

// CAnalyzeRangeDlg ダイアログ

class CAnalyzeRangeDlg : public CDialog
{
	DECLARE_DYNAMIC(CAnalyzeRangeDlg)

public:
	CAnalyzeRangeDlg(CWnd* pParent = NULL);   // 標準コンストラクタ
	virtual ~CAnalyzeRangeDlg();

	CDataDateTime m_dDataBegin;		// データ開始日時
	CDataDateTime m_dDataEnd;		// データ終了日時
	CDataDateTime m_dSelectBegin;	// 選択範囲開始日時
	CDataDateTime m_dSelectEnd;		// 選択範囲終了日時

// ダイアログ データ
	enum { IDD = IDD_ANALYZE_RANGE };

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート

	BOOL CheckInputData();

	DECLARE_MESSAGE_MAP()

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

protected:
	virtual BOOL OnInitDialog();
	virtual void OnOK();
};
