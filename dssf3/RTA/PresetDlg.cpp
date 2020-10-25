// PresetDlg.cpp : �C���v�������e�[�V���� �t�@�C��
//

#include "stdafx.h"
#include "Rta.h"
#include "RtaDlg.h"
#include "PresetDlg.h"
#include "FileIO.h"
#include "Help\ContextHelp.h"

/////////////////////////////////////////////////////////////////////////////
// CPresetDlg �_�C�A���O


CPresetDlg::CPresetDlg(CWnd* pParent /*=NULL*/)
	: CDialog(CPresetDlg::IDD, pParent)
{
}


void CPresetDlg::DoDataExchange(CDataExchange* pDX)
{
	CDialog::DoDataExchange(pDX);
	DDX_Control(pDX, IDC_PRESET_LIST, m_cPresetList);
	DDX_Control(pDX, IDC_OVERWRITE, m_cButtonOverwrite);
	DDX_Control(pDX, IDC_DELETE, m_cButtonDelete);
}


BEGIN_MESSAGE_MAP(CPresetDlg, CDialog)
	ON_BN_CLICKED(IDC_SAVE, OnSave)
	ON_BN_CLICKED(IDC_OVERWRITE, OnOverwrite)
	ON_BN_CLICKED(IDC_DELETE, OnDelete)
	ON_LBN_DBLCLK(IDC_PRESET_LIST, OnDblclkPresetList)
	ON_BN_CLICKED(IDC_MAKE_SHORTCUT, OnBnClickedMakeShortcut)
	ON_WM_HELPINFO()
	ON_LBN_SELCHANGE(IDC_PRESET_LIST, &CPresetDlg::OnLbnSelchangePresetList)
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CPresetDlg ���b�Z�[�W �n���h��

BOOL CPresetDlg::OnInitDialog()
{
	CDialog::OnInitDialog();

	SetPresetList();

	return TRUE;
}

void CPresetDlg::OnOK()
{
	int index;

	if ((index = m_cPresetList.GetCurSel()) == LB_ERR) {
		AfxMessageBox(IDS_MSG_SELECTITEM);
		return;
	}

	m_nPresetID = (int)m_cPresetList.GetItemData(index);

	CDialog::OnOK();
}

void CPresetDlg::OnLbnSelchangePresetList()
{
	if (m_cPresetList.GetCurSel() == 0) {
		m_cButtonOverwrite.EnableWindow(FALSE);
		m_cButtonDelete.EnableWindow(FALSE);
	} else {
		m_cButtonOverwrite.EnableWindow(TRUE);
		m_cButtonDelete.EnableWindow(TRUE);
	}
}

void CPresetDlg::OnSave()
{
	CDbPreset dbPreset;
	DbPresetRec dbPresetRec;
	CPresetSaveDlg dlg(this);

	if (dlg.DoModal() != IDOK)
		return;

	if (!dbPreset.Open())
		return;

	if (!dbPreset.GetNewID(&dbPresetRec.nPresetID))
		return;

	dbPresetRec.sTitle = dlg.m_sTitle;

	CRtaDlg *pMainWnd = ((CRtaApp *)AfxGetApp())->m_pWndRta;
	dbPresetRec.nOpenWindow = 0;
	if (pMainWnd->m_pWndSg != NULL)
		dbPresetRec.nOpenWindow |= WINDOW_SG;
	if (pMainWnd->m_pWndFft != NULL)
		dbPresetRec.nOpenWindow |= WINDOW_FFT;
	if (pMainWnd->m_pWndOs != NULL)
		dbPresetRec.nOpenWindow |= WINDOW_OS;
	if (pMainWnd->m_pWndFre != NULL)
		dbPresetRec.nOpenWindow |= WINDOW_FRE;
	if (pMainWnd->m_pWndDst != NULL)
		dbPresetRec.nOpenWindow |= WINDOW_DST;
	if (pMainWnd->m_pWndImp != NULL)
		dbPresetRec.nOpenWindow |= WINDOW_IMP;
	if (pMainWnd->m_pWndAcf != NULL)
		dbPresetRec.nOpenWindow |= WINDOW_ACF;

	if (!dbPreset.StoreRec(&dbPresetRec))
		return;

	if (dbPresetRec.nOpenWindow & WINDOW_SG)
		dbPreset.DBWriteBinary("SG", &g_oSetData.Sg, sizeof(g_oSetData.Sg));
	if (dbPresetRec.nOpenWindow & WINDOW_FFT)
		dbPreset.DBWriteBinary("FFT", &g_oSetData.Fft, sizeof(g_oSetData.Fft));
	if (dbPresetRec.nOpenWindow & WINDOW_OS)
		dbPreset.DBWriteBinary("OS", &g_oSetData.Os, sizeof(g_oSetData.Os));
	if (dbPresetRec.nOpenWindow & WINDOW_FRE)
		dbPreset.DBWriteBinary("FRE", &g_oSetData.Fre, sizeof(g_oSetData.Fre));
	if (dbPresetRec.nOpenWindow & WINDOW_DST)
		dbPreset.DBWriteBinary("DST", &g_oSetData.Dst, sizeof(g_oSetData.Dst));
	if (dbPresetRec.nOpenWindow & WINDOW_IMP)
		dbPreset.DBWriteBinary("IMP", &g_oSetData.Imp, sizeof(g_oSetData.Imp));
	if (dbPresetRec.nOpenWindow & WINDOW_ACF)
		dbPreset.DBWriteBinary("ACF", &g_oSetData.Acf, sizeof(g_oSetData.Acf));

	SetPresetList();

	SelectListBox(m_cPresetList, dbPresetRec.nPresetID);
}

void CPresetDlg::OnOverwrite()
{
	CDbPreset dbPreset;
	DbPresetRec dbPresetRec;
	int index;

	if ((index = m_cPresetList.GetCurSel()) == LB_ERR) {
		AfxMessageBox(IDS_MSG_SELECTITEM);
		return;
	}

	if (AfxMessageBox(IDS_MSG_SAVESELECTITEM, MB_OKCANCEL | MB_ICONQUESTION) == IDCANCEL)
		return;

	if (!dbPreset.Open())
		return;

	dbPresetRec.nPresetID = (long)m_cPresetList.GetItemData(index);
	m_cPresetList.GetText(index, dbPresetRec.sTitle);

	CRtaDlg *pMainWnd = ((CRtaApp *)AfxGetApp())->m_pWndRta;

	dbPresetRec.nOpenWindow = 0;
	if (pMainWnd->m_pWndSg != NULL)
		dbPresetRec.nOpenWindow |= WINDOW_SG;
	if (pMainWnd->m_pWndFft != NULL)
		dbPresetRec.nOpenWindow |= WINDOW_FFT;
	if (pMainWnd->m_pWndOs != NULL)
		dbPresetRec.nOpenWindow |= WINDOW_OS;
	if (pMainWnd->m_pWndFre != NULL)
		dbPresetRec.nOpenWindow |= WINDOW_FRE;
	if (pMainWnd->m_pWndDst != NULL)
		dbPresetRec.nOpenWindow |= WINDOW_DST;
	if (pMainWnd->m_pWndImp != NULL)
		dbPresetRec.nOpenWindow |= WINDOW_IMP;
	if (pMainWnd->m_pWndAcf != NULL)
		dbPresetRec.nOpenWindow |= WINDOW_ACF;

	if (!dbPreset.UpdateRec(&dbPresetRec))
		return;

	if (dbPresetRec.nOpenWindow & WINDOW_SG)
		dbPreset.DBWriteBinary("SG", &g_oSetData.Sg, sizeof(g_oSetData.Sg));
	else
		dbPreset.DBWriteBinary("SG", NULL, 0);

	if (dbPresetRec.nOpenWindow & WINDOW_FFT)
		dbPreset.DBWriteBinary("FFT", &g_oSetData.Fft, sizeof(g_oSetData.Fft));
	else
		dbPreset.DBWriteBinary("FFT", NULL, 0);

	if (dbPresetRec.nOpenWindow & WINDOW_OS)
		dbPreset.DBWriteBinary("OS", &g_oSetData.Os, sizeof(g_oSetData.Os));
	else
		dbPreset.DBWriteBinary("OS", NULL, 0);

	if (dbPresetRec.nOpenWindow & WINDOW_FRE)
		dbPreset.DBWriteBinary("FRE", &g_oSetData.Fre, sizeof(g_oSetData.Fre));
	else
		dbPreset.DBWriteBinary("FRE", NULL, 0);

	if (dbPresetRec.nOpenWindow & WINDOW_DST)
		dbPreset.DBWriteBinary("DST", &g_oSetData.Dst, sizeof(g_oSetData.Dst));
	else
		dbPreset.DBWriteBinary("DST", NULL, 0);

	if (dbPresetRec.nOpenWindow & WINDOW_IMP)
		dbPreset.DBWriteBinary("IMP", &g_oSetData.Imp, sizeof(g_oSetData.Imp));
	else
		dbPreset.DBWriteBinary("IMP", NULL, 0);

	if (dbPresetRec.nOpenWindow & WINDOW_ACF)
		dbPreset.DBWriteBinary("ACF", &g_oSetData.Acf, sizeof(g_oSetData.Acf));
	else
		dbPreset.DBWriteBinary("ACF", NULL, 0);

	SetPresetList();

	m_cPresetList.SetCurSel(index);
}

void CPresetDlg::OnDelete()
{
	CDbPreset dbPreset;
	int index;

	if ((index = m_cPresetList.GetCurSel()) == LB_ERR) {
		AfxMessageBox(IDS_MSG_SELECTITEM);
		return;
	}

	if (AfxMessageBox(IDS_MSG_DELETESELECTITEM, MB_OKCANCEL | MB_ICONQUESTION) == IDCANCEL)
		return;

	if (!dbPreset.Open())
		return;

	dbPreset.DeleteID((long)m_cPresetList.GetItemData(index));

	SetPresetList();
}

void CPresetDlg::OnDblclkPresetList()
{
	OnOK();
}

void CPresetDlg::SetPresetList()
{
	CDbPreset dbPreset;
	DbPresetRec dbPresetRec;
	int i, nIndex;

	m_cPresetList.ResetContent();

	if (dbPreset.Open()) {
		for (i = 0; ; i++) {
			if (!dbPreset.ReadRecNext(&dbPresetRec))
				break;

			nIndex = m_cPresetList.AddString(dbPresetRec.sTitle);
			m_cPresetList.SetItemData(nIndex, dbPresetRec.nPresetID);
		}
	}

	CString str;
	str.LoadString(IDS_INITIAL_PRESET);
	m_cPresetList.InsertString(0, str);
	m_cPresetList.SetItemData(0, 0);
}

void CPresetDlg::OnBnClickedMakeShortcut()
{
	CDbPreset dbPreset;
	CString sPresetName;
	CString sExeFileName;
	CString sParameter;
	int index;

	if ((index = m_cPresetList.GetCurSel()) == LB_ERR) {
		AfxMessageBox(IDS_MSG_SELECTITEM);
		return;
	}

	if (AfxMessageBox(IDS_MSG_MAKE_SHORTCUT, MB_OKCANCEL | MB_ICONQUESTION) == IDCANCEL)
		return;

	::GetModuleFileName(AfxGetApp()->m_hInstance, sExeFileName.GetBuffer(MAX_PATH), MAX_PATH);
	sExeFileName.ReleaseBuffer();

	m_cPresetList.GetText(index, sPresetName);
	CheckFileName(sPresetName);

	sParameter.Format("/S=%d", m_cPresetList.GetItemData(index));

	CreateShortCut(sPresetName, "", sExeFileName, sParameter);
}

BOOL CPresetDlg::OnHelpInfo(HELPINFO* pHelpInfo)
{
	static const UINT aIDs[] = {
		IDC_PRESET_LIST, IDH_PRESET_LIST,
		IDOK, IDH_PRESET_SET,
		IDCANCEL, IDH_PRESET_CLOSE,
		IDC_SAVE, IDH_PRESET_SAVE,
		IDC_OVERWRITE, IDH_PRESET_OVERWRITE,
		IDC_DELETE, IDH_PRESET_DELETE,
		IDC_MAKE_SHORTCUT, IDH_PRESET_MAKE_SHORTCUT,
		0
	};

	DispContextHelp(pHelpInfo, aIDs);

	return TRUE;
}

/////////////////////////////////////////////////////////////////////////////
// CPresetSaveDlg �_�C�A���O


CPresetSaveDlg::CPresetSaveDlg(CWnd* pParent /*=NULL*/)
	: CDialog(CPresetSaveDlg::IDD, pParent)
{
	m_sTitle = _T("");
}


void CPresetSaveDlg::DoDataExchange(CDataExchange* pDX)
{
	CDialog::DoDataExchange(pDX);
	DDX_Text(pDX, IDC_TITLE, m_sTitle);
}


BEGIN_MESSAGE_MAP(CPresetSaveDlg, CDialog)
	ON_WM_HELPINFO()
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CPresetSaveDlg ���b�Z�[�W �n���h��

void CPresetSaveDlg::OnOK()
{
	UpdateData(TRUE);
	if (m_sTitle.IsEmpty()) {
		AfxMessageBox(IDS_MSG_TITLE);
		return;
	}

	CDialog::OnOK();
}

BOOL CPresetSaveDlg::OnHelpInfo(HELPINFO* pHelpInfo)
{
	static const UINT aIDs[] = {
		IDC_TITLE, IDH_PRESET_SAVE_TITLE,
		IDOK, IDH_PRESET_SAVE_SET,
		IDCANCEL, IDH_PRESET_SAVE_CANCEL,
		0
	};

	DispContextHelp(pHelpInfo, aIDs);

	return TRUE;
}
