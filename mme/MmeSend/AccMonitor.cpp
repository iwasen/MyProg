// AccMonitor.cpp : 実装ファイル
//

#include "stdafx.h"
#include "MmeSend.h"
#include "AccMonitor.h"


// CAccMonitor ダイアログ

IMPLEMENT_DYNAMIC(CAccMonitor, CDialog)

CAccMonitor::CAccMonitor(CWnd* pParent, SDigitalData &oDIgitalData)
	: CDialog(CAccMonitor::IDD, pParent)
	, m_oDIgitalData(oDIgitalData)
	, m_sEditUnit1AccX(_T(""))
	, m_sEditUnit1AccY(_T(""))
	, m_sEditUnit1AccZ(_T(""))
	, m_sEditUnit2AccX(_T(""))
	, m_sEditUnit2AccY(_T(""))
	, m_sEditUnit2AccZ(_T(""))
	, m_sEditUnit3AccX(_T(""))
	, m_sEditUnit3AccY(_T(""))
	, m_sEditUnit3AccZ(_T(""))
	, m_sEditSeqCount(_T(""))
{

}

CAccMonitor::~CAccMonitor()
{
}

void CAccMonitor::DoDataExchange(CDataExchange* pDX)
{
	CDialog::DoDataExchange(pDX);
	DDX_Text(pDX, IDC_EDIT_UNIT1_ACC_X, m_sEditUnit1AccX);
	DDX_Text(pDX, IDC_EDIT_UNIT1_ACC_Y, m_sEditUnit1AccY);
	DDX_Text(pDX, IDC_EDIT_UNIT1_ACC_Z, m_sEditUnit1AccZ);
	DDX_Text(pDX, IDC_EDIT_UNIT2_ACC_X, m_sEditUnit2AccX);
	DDX_Text(pDX, IDC_EDIT_UNIT2_ACC_Y, m_sEditUnit2AccY);
	DDX_Text(pDX, IDC_EDIT_UNIT2_ACC_Z, m_sEditUnit2AccZ);
	DDX_Text(pDX, IDC_EDIT_UNIT3_ACC_X, m_sEditUnit3AccX);
	DDX_Text(pDX, IDC_EDIT_UNIT3_ACC_Y, m_sEditUnit3AccY);
	DDX_Text(pDX, IDC_EDIT_UNIT3_ACC_Z, m_sEditUnit3AccZ);
	DDX_Text(pDX, IDC_EDIT_SEQ_COUNT, m_sEditSeqCount);
}


BEGIN_MESSAGE_MAP(CAccMonitor, CDialog)
END_MESSAGE_MAP()


// CAccMonitor メッセージ ハンドラ

BOOL CAccMonitor::OnInitDialog()
{
	m_sEditSeqCount.Format("%d", m_oDIgitalData.nSequrnceCount);

	m_sEditUnit1AccX.Format("%d", m_oDIgitalData.aUnit[UNIT_1].nAcc[ACC_X]);
	m_sEditUnit1AccY.Format("%d", m_oDIgitalData.aUnit[UNIT_1].nAcc[ACC_Y]);
	m_sEditUnit1AccZ.Format("%d", m_oDIgitalData.aUnit[UNIT_1].nAcc[ACC_Z]);

	m_sEditUnit2AccX.Format("%d", m_oDIgitalData.aUnit[UNIT_2].nAcc[ACC_X]);
	m_sEditUnit2AccY.Format("%d", m_oDIgitalData.aUnit[UNIT_2].nAcc[ACC_Y]);
	m_sEditUnit2AccZ.Format("%d", m_oDIgitalData.aUnit[UNIT_2].nAcc[ACC_Z]);

	m_sEditUnit3AccX.Format("%d", m_oDIgitalData.aUnit[UNIT_3].nAcc[ACC_X]);
	m_sEditUnit3AccY.Format("%d", m_oDIgitalData.aUnit[UNIT_3].nAcc[ACC_Y]);
	m_sEditUnit3AccZ.Format("%d", m_oDIgitalData.aUnit[UNIT_3].nAcc[ACC_Z]);

	CDialog::OnInitDialog();

	return TRUE;
}

void CAccMonitor::OnOK()
{
	UpdateData(TRUE);

	m_oDIgitalData.nSequrnceCount = GetInt(m_sEditSeqCount);

	m_oDIgitalData.aUnit[UNIT_1].nAcc[ACC_X] = GetInt(m_sEditUnit1AccX);
	m_oDIgitalData.aUnit[UNIT_1].nAcc[ACC_Y] = GetInt(m_sEditUnit1AccY);
	m_oDIgitalData.aUnit[UNIT_1].nAcc[ACC_Z] = GetInt(m_sEditUnit1AccZ);

	m_oDIgitalData.aUnit[UNIT_2].nAcc[ACC_X] = GetInt(m_sEditUnit2AccX);
	m_oDIgitalData.aUnit[UNIT_2].nAcc[ACC_Y] = GetInt(m_sEditUnit2AccY);
	m_oDIgitalData.aUnit[UNIT_2].nAcc[ACC_Z] = GetInt(m_sEditUnit2AccZ);

	m_oDIgitalData.aUnit[UNIT_3].nAcc[ACC_X] = GetInt(m_sEditUnit3AccX);
	m_oDIgitalData.aUnit[UNIT_3].nAcc[ACC_Y] = GetInt(m_sEditUnit3AccY);
	m_oDIgitalData.aUnit[UNIT_3].nAcc[ACC_Z] = GetInt(m_sEditUnit3AccZ);

	CDialog::OnOK();
}
