// AnalogMonitor.cpp : 実装ファイル
//

#include "stdafx.h"
#include "MmeSend.h"
#include "AnalogMonitor.h"


// CAnalogMonitor ダイアログ

IMPLEMENT_DYNAMIC(CAnalogMonitor, CDialog)

CAnalogMonitor::CAnalogMonitor(CWnd* pParent, SAnalogData &oAnalogData)
	: CDialog(CAnalogMonitor::IDD, pParent)
	, m_oAnalogData(oAnalogData)
	, m_sEditUnit1AccX(_T(""))
	, m_sEditUnit1AccY(_T(""))
	, m_sEditUnit1AccZ(_T(""))
	, m_sEditUnit1TempX(_T(""))
	, m_sEditUnit1TempY(_T(""))
	, m_sEditUnit1TempZ(_T(""))
	, m_sEditUnit1TempSB(_T(""))
	, m_sEditUnit1Heater(_T(""))
	, m_sEditUnit2AccX(_T(""))
	, m_sEditUnit2AccY(_T(""))
	, m_sEditUnit2AccZ(_T(""))
	, m_sEditUnit2TempX(_T(""))
	, m_sEditUnit2TempY(_T(""))
	, m_sEditUnit2TempZ(_T(""))
	, m_sEditUnit2TempSB(_T(""))
	, m_sEditUnit2Heater(_T(""))
	, m_sEditUnit3AccX(_T(""))
	, m_sEditUnit3AccY(_T(""))
	, m_sEditUnit3AccZ(_T(""))
	, m_sEditUnit3TempX(_T(""))
	, m_sEditUnit3TempY(_T(""))
	, m_sEditUnit3TempZ(_T(""))
	, m_sEditUnit3TempSB(_T(""))
	, m_sEditUnit3Heater(_T(""))
{

}

CAnalogMonitor::~CAnalogMonitor()
{
}

void CAnalogMonitor::DoDataExchange(CDataExchange* pDX)
{
	CDialog::DoDataExchange(pDX);
	DDX_Text(pDX, IDC_EDIT_UNIT1_ACC_X, m_sEditUnit1AccX);
	DDX_Text(pDX, IDC_EDIT_UNIT1_ACC_Y, m_sEditUnit1AccY);
	DDX_Text(pDX, IDC_EDIT_UNIT1_ACC_Z, m_sEditUnit1AccZ);
	DDX_Text(pDX, IDC_EDIT_UNIT1_TEMP_X, m_sEditUnit1TempX);
	DDX_Text(pDX, IDC_EDIT_UNIT1_TEMP_Y, m_sEditUnit1TempY);
	DDX_Text(pDX, IDC_EDIT_UNIT1_TEMP_Z, m_sEditUnit1TempZ);
	DDX_Text(pDX, IDC_EDIT_UNIT1_TEMP_SB, m_sEditUnit1TempSB);
	DDX_Text(pDX, IDC_EDIT_UNIT1_HEATER, m_sEditUnit1Heater);
	DDX_Text(pDX, IDC_EDIT_UNIT2_ACC_X, m_sEditUnit2AccX);
	DDX_Text(pDX, IDC_EDIT_UNIT2_ACC_Y, m_sEditUnit2AccY);
	DDX_Text(pDX, IDC_EDIT_UNIT2_ACC_Z, m_sEditUnit2AccZ);
	DDX_Text(pDX, IDC_EDIT_UNIT2_TEMP_X, m_sEditUnit2TempX);
	DDX_Text(pDX, IDC_EDIT_UNIT2_TEMP_Y, m_sEditUnit2TempY);
	DDX_Text(pDX, IDC_EDIT_UNIT2_TEMP_Z, m_sEditUnit2TempZ);
	DDX_Text(pDX, IDC_EDIT_UNIT2_TEMP_SB, m_sEditUnit2TempSB);
	DDX_Text(pDX, IDC_EDIT_UNIT2_HEATER, m_sEditUnit2Heater);
	DDX_Text(pDX, IDC_EDIT_UNIT3_ACC_X, m_sEditUnit3AccX);
	DDX_Text(pDX, IDC_EDIT_UNIT3_ACC_Y, m_sEditUnit3AccY);
	DDX_Text(pDX, IDC_EDIT_UNIT3_ACC_Z, m_sEditUnit3AccZ);
	DDX_Text(pDX, IDC_EDIT_UNIT3_TEMP_X, m_sEditUnit3TempX);
	DDX_Text(pDX, IDC_EDIT_UNIT3_TEMP_Y, m_sEditUnit3TempY);
	DDX_Text(pDX, IDC_EDIT_UNIT3_TEMP_Z, m_sEditUnit3TempZ);
	DDX_Text(pDX, IDC_EDIT_UNIT3_TEMP_SB, m_sEditUnit3TempSB);
	DDX_Text(pDX, IDC_EDIT_UNIT3_HEATER, m_sEditUnit3Heater);
}


BEGIN_MESSAGE_MAP(CAnalogMonitor, CDialog)
END_MESSAGE_MAP()


// CAnalogMonitor メッセージ ハンドラ

BOOL CAnalogMonitor::OnInitDialog()
{
	m_sEditUnit1AccX.Format("%d", m_oAnalogData.aUnit[UNIT_1].nAcc[ACC_X]);
	m_sEditUnit1AccY.Format("%d", m_oAnalogData.aUnit[UNIT_1].nAcc[ACC_Y]);
	m_sEditUnit1AccZ.Format("%d", m_oAnalogData.aUnit[UNIT_1].nAcc[ACC_Z]);
	m_sEditUnit1TempX.Format("%d", m_oAnalogData.aUnit[UNIT_1].nTemp[TEMP_X]);
	m_sEditUnit1TempY.Format("%d", m_oAnalogData.aUnit[UNIT_1].nTemp[TEMP_Y]);
	m_sEditUnit1TempZ.Format("%d", m_oAnalogData.aUnit[UNIT_1].nTemp[TEMP_Z]);
	m_sEditUnit1TempSB.Format("%d", m_oAnalogData.aUnit[UNIT_1].nTemp[TEMP_SB]);
	m_sEditUnit1Heater.Format("%d", m_oAnalogData.aUnit[UNIT_1].nHeater);

	m_sEditUnit2AccX.Format("%d", m_oAnalogData.aUnit[UNIT_2].nAcc[ACC_X]);
	m_sEditUnit2AccY.Format("%d", m_oAnalogData.aUnit[UNIT_2].nAcc[ACC_Y]);
	m_sEditUnit2AccZ.Format("%d", m_oAnalogData.aUnit[UNIT_2].nAcc[ACC_Z]);
	m_sEditUnit2TempX.Format("%d", m_oAnalogData.aUnit[UNIT_2].nTemp[TEMP_X]);
	m_sEditUnit2TempY.Format("%d", m_oAnalogData.aUnit[UNIT_2].nTemp[TEMP_Y]);
	m_sEditUnit2TempZ.Format("%d", m_oAnalogData.aUnit[UNIT_2].nTemp[TEMP_Z]);
	m_sEditUnit2TempSB.Format("%d", m_oAnalogData.aUnit[UNIT_2].nTemp[TEMP_SB]);
	m_sEditUnit2Heater.Format("%d", m_oAnalogData.aUnit[UNIT_2].nHeater);

	m_sEditUnit3AccX.Format("%d", m_oAnalogData.aUnit[UNIT_3].nAcc[ACC_X]);
	m_sEditUnit3AccY.Format("%d", m_oAnalogData.aUnit[UNIT_3].nAcc[ACC_Y]);
	m_sEditUnit3AccZ.Format("%d", m_oAnalogData.aUnit[UNIT_3].nAcc[ACC_Z]);
	m_sEditUnit3TempX.Format("%d", m_oAnalogData.aUnit[UNIT_3].nTemp[TEMP_X]);
	m_sEditUnit3TempY.Format("%d", m_oAnalogData.aUnit[UNIT_3].nTemp[TEMP_Y]);
	m_sEditUnit3TempZ.Format("%d", m_oAnalogData.aUnit[UNIT_3].nTemp[TEMP_Z]);
	m_sEditUnit3TempSB.Format("%d", m_oAnalogData.aUnit[UNIT_3].nTemp[TEMP_SB]);
	m_sEditUnit3Heater.Format("%d", m_oAnalogData.aUnit[UNIT_3].nHeater);

	CDialog::OnInitDialog();

	return TRUE;
}

void CAnalogMonitor::OnOK()
{
	UpdateData(TRUE);

	m_oAnalogData.aUnit[UNIT_1].nAcc[ACC_X] = GetInt(m_sEditUnit1AccX);
	m_oAnalogData.aUnit[UNIT_1].nAcc[ACC_Y] = GetInt(m_sEditUnit1AccY);
	m_oAnalogData.aUnit[UNIT_1].nAcc[ACC_Z] = GetInt(m_sEditUnit1AccZ);
	m_oAnalogData.aUnit[UNIT_1].nTemp[TEMP_X] = GetInt(m_sEditUnit1TempX);
	m_oAnalogData.aUnit[UNIT_1].nTemp[TEMP_Y] = GetInt(m_sEditUnit1TempY);
	m_oAnalogData.aUnit[UNIT_1].nTemp[TEMP_Z] = GetInt(m_sEditUnit1TempZ);
	m_oAnalogData.aUnit[UNIT_1].nTemp[TEMP_SB] = GetInt(m_sEditUnit1TempSB);
	m_oAnalogData.aUnit[UNIT_1].nHeater = GetInt(m_sEditUnit1Heater);

	m_oAnalogData.aUnit[UNIT_2].nAcc[ACC_X] = GetInt(m_sEditUnit2AccX);
	m_oAnalogData.aUnit[UNIT_2].nAcc[ACC_Y] = GetInt(m_sEditUnit2AccY);
	m_oAnalogData.aUnit[UNIT_2].nAcc[ACC_Z] = GetInt(m_sEditUnit2AccZ);
	m_oAnalogData.aUnit[UNIT_2].nTemp[TEMP_X] = GetInt(m_sEditUnit2TempX);
	m_oAnalogData.aUnit[UNIT_2].nTemp[TEMP_Y] = GetInt(m_sEditUnit2TempY);
	m_oAnalogData.aUnit[UNIT_2].nTemp[TEMP_Z] = GetInt(m_sEditUnit2TempZ);
	m_oAnalogData.aUnit[UNIT_2].nTemp[TEMP_SB] = GetInt(m_sEditUnit2TempSB);
	m_oAnalogData.aUnit[UNIT_2].nHeater = GetInt(m_sEditUnit2Heater);

	m_oAnalogData.aUnit[UNIT_3].nAcc[ACC_X] = GetInt(m_sEditUnit3AccX);
	m_oAnalogData.aUnit[UNIT_3].nAcc[ACC_Y] = GetInt(m_sEditUnit3AccY);
	m_oAnalogData.aUnit[UNIT_3].nAcc[ACC_Z] = GetInt(m_sEditUnit3AccZ);
	m_oAnalogData.aUnit[UNIT_3].nTemp[TEMP_X] = GetInt(m_sEditUnit3TempX);
	m_oAnalogData.aUnit[UNIT_3].nTemp[TEMP_Y] = GetInt(m_sEditUnit3TempY);
	m_oAnalogData.aUnit[UNIT_3].nTemp[TEMP_Z] = GetInt(m_sEditUnit3TempZ);
	m_oAnalogData.aUnit[UNIT_3].nTemp[TEMP_SB] = GetInt(m_sEditUnit3TempSB);
	m_oAnalogData.aUnit[UNIT_3].nHeater = GetInt(m_sEditUnit3Heater);

	CDialog::OnOK();
}
