//*****************************************************************************************************
//  1. ファイル名
//		SetMonitoringDlg.cpp
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		正常温度・電圧範囲設定ダイアログクラスの実装
//----------------------------------------------------------------------------------------------------
//  3. 備考
//		無し
//*****************************************************************************************************

#include "stdafx.h"
#include "MmeRecv.h"
#include "SetMonitoringDlg.h"


// CSetMonitoringDlg ダイアログ

IMPLEMENT_DYNAMIC(CSetMonitoringDlg, CDialog)

//*****************************************************************************************************
//  1. 関数名
//		CSetMonitoringDlg::CSetMonitoringDlg
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		コンストラクタ
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CWnd		*pParent			[I] 親ウィンドウポインタ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
CSetMonitoringDlg::CSetMonitoringDlg(CWnd* pParent /*=NULL*/)
	: CDialog(CSetMonitoringDlg::IDD, pParent)
{
}

void CSetMonitoringDlg::DoDataExchange(CDataExchange* pDX)
{
	CDialog::DoDataExchange(pDX);
	DDX_Control(pDX, IDC_EDIT_TEMP_UNIT1_X1, m_cEditTempRange1[UNIT_1][TEMP_X]);
	DDX_Control(pDX, IDC_EDIT_TEMP_UNIT1_Y1, m_cEditTempRange1[UNIT_1][TEMP_Y]);
	DDX_Control(pDX, IDC_EDIT_TEMP_UNIT1_Z1, m_cEditTempRange1[UNIT_1][TEMP_Z]);
	DDX_Control(pDX, IDC_EDIT_TEMP_UNIT1_SB1, m_cEditTempRange1[UNIT_1][TEMP_SB]);
	DDX_Control(pDX, IDC_EDIT_TEMP_UNIT2_X1, m_cEditTempRange1[UNIT_2][TEMP_X]);
	DDX_Control(pDX, IDC_EDIT_TEMP_UNIT2_Y1, m_cEditTempRange1[UNIT_2][TEMP_Y]);
	DDX_Control(pDX, IDC_EDIT_TEMP_UNIT2_Z1, m_cEditTempRange1[UNIT_2][TEMP_Z]);
	DDX_Control(pDX, IDC_EDIT_TEMP_UNIT2_SB1, m_cEditTempRange1[UNIT_2][TEMP_SB]);
	DDX_Control(pDX, IDC_EDIT_TEMP_UNIT3_X1, m_cEditTempRange1[UNIT_3][TEMP_X]);
	DDX_Control(pDX, IDC_EDIT_TEMP_UNIT3_Y1, m_cEditTempRange1[UNIT_3][TEMP_Y]);
	DDX_Control(pDX, IDC_EDIT_TEMP_UNIT3_Z1, m_cEditTempRange1[UNIT_3][TEMP_Z]);
	DDX_Control(pDX, IDC_EDIT_TEMP_UNIT3_SB1, m_cEditTempRange1[UNIT_3][TEMP_SB]);
	DDX_Control(pDX, IDC_EDIT_TEMP_UNIT1_X2, m_cEditTempRange2[UNIT_1][TEMP_X]);
	DDX_Control(pDX, IDC_EDIT_TEMP_UNIT1_Y2, m_cEditTempRange2[UNIT_1][TEMP_Y]);
	DDX_Control(pDX, IDC_EDIT_TEMP_UNIT1_Z2, m_cEditTempRange2[UNIT_1][TEMP_Z]);
	DDX_Control(pDX, IDC_EDIT_TEMP_UNIT1_SB2, m_cEditTempRange2[UNIT_1][TEMP_SB]);
	DDX_Control(pDX, IDC_EDIT_TEMP_UNIT2_X2, m_cEditTempRange2[UNIT_2][TEMP_X]);
	DDX_Control(pDX, IDC_EDIT_TEMP_UNIT2_Y2, m_cEditTempRange2[UNIT_2][TEMP_Y]);
	DDX_Control(pDX, IDC_EDIT_TEMP_UNIT2_Z2, m_cEditTempRange2[UNIT_2][TEMP_Z]);
	DDX_Control(pDX, IDC_EDIT_TEMP_UNIT2_SB2, m_cEditTempRange2[UNIT_2][TEMP_SB]);
	DDX_Control(pDX, IDC_EDIT_TEMP_UNIT3_X2, m_cEditTempRange2[UNIT_3][TEMP_X]);
	DDX_Control(pDX, IDC_EDIT_TEMP_UNIT3_Y2, m_cEditTempRange2[UNIT_3][TEMP_Y]);
	DDX_Control(pDX, IDC_EDIT_TEMP_UNIT3_Z2, m_cEditTempRange2[UNIT_3][TEMP_Z]);
	DDX_Control(pDX, IDC_EDIT_TEMP_UNIT3_SB2, m_cEditTempRange2[UNIT_3][TEMP_SB]);
	DDX_Control(pDX, IDC_EDIT_VOLTAGE_UNIT1L, m_cEditVoltageRange1[UNIT_1]);
	DDX_Control(pDX, IDC_EDIT_VOLTAGE_UNIT1H, m_cEditVoltageRange2[UNIT_1]);
	DDX_Control(pDX, IDC_EDIT_VOLTAGE_UNIT2L, m_cEditVoltageRange1[UNIT_2]);
	DDX_Control(pDX, IDC_EDIT_VOLTAGE_UNIT2H, m_cEditVoltageRange2[UNIT_2]);
	DDX_Control(pDX, IDC_EDIT_VOLTAGE_UNIT3L, m_cEditVoltageRange1[UNIT_3]);
	DDX_Control(pDX, IDC_EDIT_VOLTAGE_UNIT3H, m_cEditVoltageRange2[UNIT_3]);
}


BEGIN_MESSAGE_MAP(CSetMonitoringDlg, CDialog)
END_MESSAGE_MAP()


// CSetMonitoringDlg メッセージ ハンドラ

//*****************************************************************************************************
//  1. 関数名
//		CSetMonitoringDlg::OnInitDialog
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		ダイアログ初期化処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		BOOL		TRUE
//*****************************************************************************************************
BOOL CSetMonitoringDlg::OnInitDialog()
{
	CDialog::OnInitDialog();

	// エディットコントロールに表示
	for (int nUnit = 0; nUnit < N_UNIT; nUnit++) {
		for (int nTemp = 0; nTemp < N_TEMP; nTemp++) {
			m_cEditTempRange1[nUnit][nTemp] = (*m_pNormalTempRange1)[nUnit][nTemp];
			m_cEditTempRange2[nUnit][nTemp] = (*m_pNormalTempRange2)[nUnit][nTemp];

			m_cEditTempRange1[nUnit][nTemp].SetValidChar(VC_NUM | VC_POINT | VC_MINUS);
			m_cEditTempRange2[nUnit][nTemp].SetValidChar(VC_NUM | VC_POINT | VC_MINUS);
		}

		m_cEditVoltageRange1[nUnit] = (*m_pNormalVoltageRange1)[nUnit];
		m_cEditVoltageRange2[nUnit] = (*m_pNormalVoltageRange2)[nUnit];

		m_cEditVoltageRange1[nUnit].SetValidChar(VC_NUM | VC_POINT | VC_MINUS);
		m_cEditVoltageRange2[nUnit].SetValidChar(VC_NUM | VC_POINT | VC_MINUS);
	}

	return TRUE;
}

//*****************************************************************************************************
//  1. 関数名
//		CSetMonitoringDlg::OnOK
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		OKボタン押下処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CSetMonitoringDlg::OnOK()
{
	// エディットコントロールからデータを取得
	for (int nUnit = 0; nUnit < N_UNIT; nUnit++) {
		for (int nTemp = 0; nTemp < N_TEMP; nTemp++) {
			(*m_pNormalTempRange1)[nUnit][nTemp] = m_cEditTempRange1[nUnit][nTemp];
			(*m_pNormalTempRange2)[nUnit][nTemp] = m_cEditTempRange2[nUnit][nTemp];
		}

		(*m_pNormalVoltageRange1)[nUnit] = m_cEditVoltageRange1[nUnit];
		(*m_pNormalVoltageRange2)[nUnit] = m_cEditVoltageRange2[nUnit];
	}

	CDialog::OnOK();
}
