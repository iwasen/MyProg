#pragma once
//*****************************************************************************************************
//  1. ファイル名
//		SetMonitoringDlg.h
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		正常温度・電圧範囲設定ダイアログクラスの定義
//----------------------------------------------------------------------------------------------------
//  3. 備考
//		無し
//*****************************************************************************************************

#include "myctrl.h"
#include "MmeData.h"


// CSetMonitoringDlg ダイアログ

class CSetMonitoringDlg : public CDialog
{
	DECLARE_DYNAMIC(CSetMonitoringDlg)

public:
	CSetMonitoringDlg(CWnd* pParent = NULL);   // 標準コンストラクタ

// ダイアログ データ
	enum { IDD = IDD_SET_MONITORING };

	double (*m_pNormalTempRange1)[N_UNIT][N_TEMP];
	double (*m_pNormalTempRange2)[N_UNIT][N_TEMP];
	double (*m_pNormalVoltageRange1)[N_UNIT];
	double (*m_pNormalVoltageRange2)[N_UNIT];

protected:
	CEditEx m_cEditTempRange1[N_UNIT][N_TEMP];
	CEditEx m_cEditTempRange2[N_UNIT][N_TEMP];
	CEditEx m_cEditVoltageRange1[N_UNIT];
	CEditEx m_cEditVoltageRange2[N_UNIT];

	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート

	DECLARE_MESSAGE_MAP()
	virtual BOOL OnInitDialog();
	virtual void OnOK();
};
