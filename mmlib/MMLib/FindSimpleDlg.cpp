// FindSimpleDlg.cpp : インプリメンテーション ファイル
//

#include "stdafx.h"
#include "mmlib.h"
#include "FindSimpleDlg.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

/////////////////////////////////////////////////////////////////////////////
// CFindSimpleDlg ダイアログ


CFindSimpleDlg::CFindSimpleDlg(CWnd* pParent /*=NULL*/)
	: CDialog(CFindSimpleDlg::IDD, pParent)
{
	m_sFindFolder = _T("");
	m_bSave = FALSE;
	m_sSearchText = _T("");

	m_pItemFolder = NULL;
	m_bUpdateMode = FALSE;
}


void CFindSimpleDlg::DoDataExchange(CDataExchange* pDX)
{
	CDialog::DoDataExchange(pDX);
	DDX_Control(pDX, IDC_FIND_EXPERT, m_cFindExpert);
	DDX_Control(pDX, IDC_FINDNUM, m_cFindNum);
	DDX_Control(pDX, IDC_SEARCH_TEXT, m_cSearchText);
	DDX_Control(pDX, IDC_LIBRARY, m_cLibrary);
	DDX_Text(pDX, IDC_FIND_FOLDER, m_sFindFolder);
	DDX_Check(pDX, IDC_SAVE, m_bSave);
	DDX_Text(pDX, IDC_SEARCH_TEXT, m_sSearchText);
}


BEGIN_MESSAGE_MAP(CFindSimpleDlg, CDialog)
	ON_BN_CLICKED(IDC_FIND_EXPERT, OnFindExpert)
	ON_BN_CLICKED(IDC_GETFINDNUM, OnGetfindnum)
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CFindSimpleDlg メッセージ ハンドラ

BOOL CFindSimpleDlg::OnInitDialog() 
{
	CDialog::OnInitDialog();
	
	int i;
	CString str;
	int n;
	BOOL bSelectedLibrary = FALSE;

	n = SetLibraryComboBox(m_cLibrary);
	if (n == 1) {
		m_cLibrary.SetCurSel(0);
		bSelectedLibrary = TRUE;
	} else if (n != 0) {
		for (i = 0; i < n; i++) {
			if (m_cLibrary.GetItemDataPtr(i) == m_pItemFolder) {
				m_cLibrary.SetCurSel(i);
				bSelectedLibrary = TRUE;
				break;
			}
		}
	}

	if (m_bUpdateMode) {
		m_cLibrary.EnableWindow(FALSE);
		m_cFindExpert.EnableWindow(FALSE);
	} else
		m_sFindFolder.LoadString(IDS_FIND_FOLDER);

	UpdateData(FALSE);

	if (bSelectedLibrary) {
		m_cSearchText.SetFocus();
		return FALSE;
	} else
		return TRUE;
}

void CFindSimpleDlg::OnOK() 
{
	int nIndex;

	if ((nIndex = m_cLibrary.GetCurSel()) == LB_ERR) {
		AfxMessageBox(IDS_SELECT_LIBRARY);
		return;
	}

	m_pItemFolder = (CItemFolder *)m_cLibrary.GetItemDataPtr(nIndex);

	UpdateData(TRUE);

	EndDialog(IDOK);
}

void CFindSimpleDlg::OnFindExpert() 
{
	EndDialog(IDC_FIND_EXPERT);
}

void CFindSimpleDlg::OnGetfindnum() 
{
	int nSel;
	if ((nSel = m_cLibrary.GetCurSel()) == LB_ERR) {
		AfxMessageBox(IDS_SELECT_LIBRARY);
		return;
	}

	CWaitCursor wait;

	CMMPcsDB *pPcsDB = ((CItemFolder *)m_cLibrary.GetItemDataPtr(nSel))->m_pPcsDB;

	UpdateData(TRUE);

	long nCount;
	CString filter = MakeFilterString2(m_sSearchText);

	if (pPcsDB->FilterDataEx(filter)) {
		if (pPcsDB->GetDataCountEx(&nCount))
			m_cFindNum = nCount;
		pPcsDB->FilterDataEx(NULL);
	}
}
