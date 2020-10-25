// WaveEditDlg.cpp : �����t�@�C��
//

#include "stdafx.h"
#include "RTA.h"
#include "WaveEditDlg.h"
#include "Help\ContextHelp.h"


// CWaveEditDlg �_�C�A���O

CWaveEditDlg::CWaveEditDlg(CWnd* pParent /*=NULL*/)
	: CDialog(CWaveEditDlg::IDD, pParent)
{

}

CWaveEditDlg::~CWaveEditDlg()
{
}

void CWaveEditDlg::DoDataExchange(CDataExchange* pDX)
{
	CDialog::DoDataExchange(pDX);
	DDX_Control(pDX, IDC_TAB_WAVE_EDIT, m_cTabWaveEdit);
}


BEGIN_MESSAGE_MAP(CWaveEditDlg, CDialog)
	ON_BN_CLICKED(ID_OK, &CWaveEditDlg::OnBnClickedOk)
	ON_WM_DESTROY()
	ON_NOTIFY(TCN_SELCHANGE, IDC_TAB_WAVE_EDIT, &CWaveEditDlg::OnTcnSelchangeTabWaveEdit)
	ON_WM_HELPINFO()
END_MESSAGE_MAP()


// CWaveEditDlg ���b�Z�[�W �n���h��
// WaveEditDlg.cpp : �����t�@�C��
//


BOOL CWaveEditDlg::OnInitDialog()
{
	CDialog::OnInitDialog();

	m_pSgDlg->StartStop(FALSE);

	m_wndWaveEditManual.m_oWaveForm = *(WaveFormManual *)m_pWaveForm;
	m_wndWaveEditManual.m_pSgDlg = m_pSgDlg;
	m_cTabWaveEdit.AddPage(&m_wndWaveEditManual, IDD_WAVE_EDIT_MANUAL, IDS_MANUAL_EDIT);

	m_wndWaveEditFM.m_oWaveForm = *(WaveFormFM *)m_pWaveForm;
	m_wndWaveEditFM.m_pSgDlg = m_pSgDlg;
	m_cTabWaveEdit.AddPage(&m_wndWaveEditFM, IDD_WAVE_EDIT_FM, IDS_FM_SYNTH);

	m_cTabWaveEdit.SetPage(m_pWaveForm->nDataType == WAVEFORM_FM ? 1 : 0);

	return TRUE;
}

void CWaveEditDlg::OnBnClickedOk()
{
	if (m_cTabWaveEdit.GetCurSel() == 0)
		*(WaveFormManual *)m_pWaveForm = m_wndWaveEditManual.m_oWaveForm;
	else
		*(WaveFormFM *)m_pWaveForm = m_wndWaveEditFM.m_oWaveForm;

	EndDialog(IDOK);
}

void CWaveEditDlg::OnOK()
{
}

void CWaveEditDlg::OnDestroy()
{
	CDialog::OnDestroy();

	m_pSgDlg->StartStop(FALSE);
	g_oWaveForm.SetTestWaveForm(NULL);
}

void CWaveEditDlg::OnTcnSelchangeTabWaveEdit(NMHDR * /*pNMHDR*/, LRESULT *pResult)
{
	m_pSgDlg->StartStop(FALSE);

	m_wndWaveEditManual.m_cBtnTest = FALSE;
	m_wndWaveEditFM.m_cBtnTest = FALSE;

	*pResult = 0;
}

BOOL CWaveEditDlg::OnHelpInfo(HELPINFO* pHelpInfo)
{
	static const UINT aIDs[] = {
		ID_OK, IDH_SG_EDIT_OK,
		IDCANCEL, IDH_SG_EDIT_CANCEL,
		0
	};

	DispContextHelp(pHelpInfo, aIDs);

	return TRUE;
}
