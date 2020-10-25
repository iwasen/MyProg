// FindDlg.cpp : インプリメンテーション ファイル
//

#include "stdafx.h"
#include "MMLib.h"
#include "FindDlg.h"
#include "Item.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

/////////////////////////////////////////////////////////////////////////////
// CFindDlg ダイアログ


CFindDlg::CFindDlg(CWnd* pParent /*=NULL*/)
	: CDialog(CFindDlg::IDD, pParent)
{
	m_sCode = _T("");
	m_sCreateDateBefore = _T("");
	m_sCreator = _T("");
	m_sFindFolder = _T("");
	m_sRemark = _T("");
	m_sKeyword = _T("");
	m_sTitle = _T("");
	m_sUpdateDateBefore = _T("");
	m_bNoCase = FALSE;
	m_iMatching = -1;
	m_iFindType = -1;
	m_bSave = FALSE;
	m_sText = _T("");

	m_pItemFolder = NULL;
	m_bUpdateMode = FALSE;
	m_nFindType = FIND_TYPE_DATA;
}


void CFindDlg::DoDataExchange(CDataExchange* pDX)
{
	CDialog::DoDataExchange(pDX);
	DDX_Control(pDX, IDC_TEXT, m_cText);
	DDX_Control(pDX, IDC_CODE, m_cCode);
	DDX_Control(pDX, IDC_FINDNUM, m_cFindNum);
	DDX_Control(pDX, IDC_UPDATE_DATE_TO, m_cUpdateDateTo);
	DDX_Control(pDX, IDC_UPDATE_DATE_FROM, m_cUpdateDateFrom);
	DDX_Control(pDX, IDC_CREATE_DATE_TO, m_cCreateDateTo);
	DDX_Control(pDX, IDC_CREATE_DATE_FROM, m_cCreateDateFrom);
	DDX_Control(pDX, IDC_CONDITION_TAB, m_cConditionTab);
	DDX_Control(pDX, IDC_LIBRARY, m_cLibrary);
	DDX_Text(pDX, IDC_CODE, m_sCode);
	DDX_Text(pDX, IDC_CREATE_DATE_BEFORE, m_sCreateDateBefore);
	DDX_Text(pDX, IDC_CREATOR, m_sCreator);
	DDX_Text(pDX, IDC_FIND_FOLDER, m_sFindFolder);
	DDX_Text(pDX, IDC_REMARK, m_sRemark);
	DDX_Text(pDX, IDC_KEYWORD, m_sKeyword);
	DDX_Text(pDX, IDC_TITLE, m_sTitle);
	DDX_Text(pDX, IDC_UPDATE_DATE_BEFORE, m_sUpdateDateBefore);
	DDX_Check(pDX, IDC_NOCASE, m_bNoCase);
	DDX_Radio(pDX, IDC_FRONT_MATCHING, m_iMatching);
	DDX_Radio(pDX, IDC_DATA, m_iFindType);
	DDX_Check(pDX, IDC_SAVE, m_bSave);
	DDX_Text(pDX, IDC_TEXT, m_sText);
}


BEGIN_MESSAGE_MAP(CFindDlg, CDialog)
	ON_NOTIFY(TCN_SELCHANGING, IDC_CONDITION_TAB, OnSelchangingConditionTab)
	ON_NOTIFY(TCN_SELCHANGE, IDC_CONDITION_TAB, OnSelchangeConditionTab)
	ON_BN_CLICKED(IDC_GETFINDNUM, OnGetfindnum)
	ON_BN_CLICKED(IDC_FOLDER, OnFolder)
	ON_BN_CLICKED(IDC_DATA, OnData)
	ON_WM_CTLCOLOR()
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CFindDlg メッセージ ハンドラ

BOOL CFindDlg::OnInitDialog() 
{
	CDialog::OnInitDialog();

	int i;
	CString str;
	int n;

	n = SetLibraryComboBox(m_cLibrary);
	if (n == 1)
		m_cLibrary.SetCurSel(0);
	else if (n != 0) {
		for (i = 0; i < n; i++) {
			if (m_cLibrary.GetItemDataPtr(i) == m_pItemFolder) {
				m_cLibrary.SetCurSel(i);
				break;
			}
		}
	}

	for (i = 0; i < N_FIND_CONDITIONS; i++) {
		str.Format(IDS_CONDITION, i + 1);
		m_cConditionTab.InsertItem(i, str);
	}

	m_iFindType = (m_nFindType == FIND_TYPE_DATA) ? 0 : 1;

	if (m_bUpdateMode)
		m_cLibrary.EnableWindow(FALSE);
	else
		m_sFindFolder.LoadString(IDS_FIND_FOLDER);

	m_cCode.EnableWindow(m_nFindType == FIND_TYPE_DATA);

	SetFindCondition();

	return TRUE;
}

void CFindDlg::OnOK() 
{
	int nIndex;

	if ((nIndex = m_cLibrary.GetCurSel()) == LB_ERR) {
		AfxMessageBox(IDS_SELECT_LIBRARY);
		return;
	}

	GetFindCondition();

	m_pItemFolder = (CItemFolder *)m_cLibrary.GetItemDataPtr(nIndex);

	EndDialog(IDOK);
}

void CFindDlg::OnSelchangingConditionTab(NMHDR* pNMHDR, LRESULT* pResult) 
{
	GetFindCondition();
	
	*pResult = 0;
}

void CFindDlg::OnSelchangeConditionTab(NMHDR* pNMHDR, LRESULT* pResult) 
{
	SetFindCondition();

	*pResult = 0;
}

void CFindDlg::GetFindCondition()
{
	int nIndex = m_cConditionTab.GetCurSel();

	UpdateData(TRUE);

	m_FindConditions[nIndex].createDateFrom = m_cCreateDateFrom;
	m_FindConditions[nIndex].createDateTo = m_cCreateDateTo;
	m_FindConditions[nIndex].createDateBefore = m_sCreateDateBefore;
	m_FindConditions[nIndex].updateDateFrom = m_cUpdateDateFrom;
	m_FindConditions[nIndex].updateDateTo = m_cUpdateDateTo;
	m_FindConditions[nIndex].updateDateBefore = m_sUpdateDateBefore;
	m_FindConditions[nIndex].creator = m_sCreator;
	m_FindConditions[nIndex].code = m_sCode;
	m_FindConditions[nIndex].title = m_sTitle;
	m_FindConditions[nIndex].remark = m_sRemark;
	m_FindConditions[nIndex].keyword = m_sKeyword;
	m_FindConditions[nIndex].text = m_sText;

	switch (m_iMatching) {
	default:
		m_FindConditions[nIndex].matching = "";
		break;
	case 1:
		m_FindConditions[nIndex].matching = "F";
		break;
	case 2:
//		m_FindConditions[nIndex].matching = "W";
		m_FindConditions[nIndex].matching = "S";
		break;
	}

	if (!m_bNoCase)
		m_FindConditions[nIndex].matching += "N";
}

void CFindDlg::SetFindCondition()
{
	int nIndex = m_cConditionTab.GetCurSel();

	m_cCreateDateFrom = m_FindConditions[nIndex].createDateFrom;
	m_cCreateDateTo = m_FindConditions[nIndex].createDateTo;
	m_sCreateDateBefore = m_FindConditions[nIndex].createDateBefore;
	m_cUpdateDateFrom = m_FindConditions[nIndex].updateDateFrom;
	m_cUpdateDateTo = m_FindConditions[nIndex].updateDateTo;
	m_sUpdateDateBefore = m_FindConditions[nIndex].updateDateBefore;
	m_sCreator = m_FindConditions[nIndex].creator;
	m_sCode = m_FindConditions[nIndex].code;
	m_sTitle = m_FindConditions[nIndex].title;
	m_sRemark = m_FindConditions[nIndex].remark;
	m_sKeyword = m_FindConditions[nIndex].keyword;
	m_sText = m_FindConditions[nIndex].text;

	if (m_FindConditions[nIndex].matching.Find('F') != -1)
		m_iMatching = 1;
	else if (m_FindConditions[nIndex].matching.Find('S') != -1)
		m_iMatching = 2;
	else
		m_iMatching = 0;

	if (m_FindConditions[nIndex].matching.Find('N') != -1)
		m_bNoCase = FALSE;
	else
		m_bNoCase = TRUE;

	UpdateData(FALSE);
}

void CFindDlg::OnGetfindnum() 
{
	int nSel;
	if ((nSel = m_cLibrary.GetCurSel()) == LB_ERR) {
		AfxMessageBox(IDS_SELECT_LIBRARY);
		return;
	}

	CWaitCursor wait;

	CMMPcsDB *pPcsDB = ((CItemFolder *)m_cLibrary.GetItemDataPtr(nSel))->m_pPcsDB;

	GetFindCondition();

	long nCount;
	CString filter = MakeFilterString(m_FindConditions, m_nFindType);

	switch (m_nFindType) {
	case FIND_TYPE_FOLDER:
		if (pPcsDB->FilterFolderEx(filter)) {
			if (pPcsDB->GetFolderCountEx(&nCount))
				m_cFindNum = nCount;
			pPcsDB->FilterFolderEx(NULL);
		}
		break;
	case FIND_TYPE_DATA:
		if (pPcsDB->FilterDataEx(filter)) {
			if (pPcsDB->GetDataCountEx(&nCount))
				m_cFindNum = nCount;
			pPcsDB->FilterDataEx(NULL);
		}
		break;
	}
}

void CFindDlg::OnFolder() 
{
	m_nFindType = FIND_TYPE_FOLDER;
	m_cCode.EnableWindow(FALSE);
}

void CFindDlg::OnData() 
{
	m_nFindType = FIND_TYPE_DATA;
	m_cCode.EnableWindow(TRUE);
}

HBRUSH CFindDlg::OnCtlColor(CDC* pDC, CWnd* pWnd, UINT nCtlColor) 
{
	HBRUSH hbr = CDialog::OnCtlColor(pDC, pWnd, nCtlColor);
	
	if (pWnd->GetDlgCtrlID() == IDC_STATIC) {
		pDC->SetBkMode(TRANSPARENT);
		return (HBRUSH)::GetStockObject(NULL_BRUSH);
	}
	
	return hbr;
}
