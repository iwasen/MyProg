// PictDoc.cpp : インプリメンテーション ファイル
//

#include "stdafx.h"
#include "MMLib.h"
#include "PictDoc.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

/////////////////////////////////////////////////////////////////////////////
// CPictDoc

IMPLEMENT_DYNCREATE(CPictDoc, CDocument)

CPictDoc::CPictDoc()
{
}

BOOL CPictDoc::OnNewDocument()
{
//	if (!CDocument::OnNewDocument())
//		return FALSE;
	return TRUE;
}

CPictDoc::~CPictDoc()
{
	if (m_hDIB != NULL)
		MMPictureFree(m_hDIB);
}


BEGIN_MESSAGE_MAP(CPictDoc, CDocument)
	ON_COMMAND(IDM_PICT_AUTOSIZE, OnPictAutosize)
	ON_COMMAND(IDM_PICT_AUTOSIZE2, OnPictAutosize2)
	ON_COMMAND(IDM_PICT_ENLARGE, OnPictEnlarge)
	ON_COMMAND(IDM_PICT_REDUCE, OnPictReduce)
	ON_COMMAND(IDM_PICT_LEFT_ROTATE, OnPictLeftRotate)
	ON_COMMAND(IDM_PICT_RIGHT_ROTATE, OnPictRightRotate)
	ON_UPDATE_COMMAND_UI(IDM_PICT_AUTOSIZE, OnUpdatePictAutosize)
	ON_UPDATE_COMMAND_UI(IDM_PICT_AUTOSIZE2, OnUpdatePictAutosize2)
	ON_COMMAND(IDM_CLOSE_PICT, OnClosePict)
	ON_CBN_SELCHANGE(IDW_COMBO, OnSelchangeCombo)
	ON_CBN_EDITUPDATE(IDW_COMBO, OnEditchangeCombo)
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CPictDoc 診断

#ifdef _DEBUG
void CPictDoc::AssertValid() const
{
	CDocument::AssertValid();
}

void CPictDoc::Dump(CDumpContext& dc) const
{
	CDocument::Dump(dc);
}
#endif //_DEBUG

/////////////////////////////////////////////////////////////////////////////
// CPictDoc シリアライズ

void CPictDoc::Serialize(CArchive& ar)
{
	if (ar.IsStoring())
	{
		// TODO: この位置に保存用のコードを追加してください
	}
	else
	{
		// TODO: この位置に読み込み用のコードを追加してください
	}
}

/////////////////////////////////////////////////////////////////////////////
// CPictDoc コマンド

BOOL CPictDoc::OnOpenDocument(LPCTSTR lpszPathName) 
{
	return TRUE;
}

void CPictDoc::SetPathName(LPCTSTR lpszPathName, BOOL bAddToMRU) 
{
}

void CPictDoc::ShowPicture(LPCTSTR pTitle, HDIB hDIB, int nAutoSize, double fPictRatio)
{
	CPictFrame *pPictFrame;

	if ((pPictFrame = GetPictFrame()) != NULL)
		pPictFrame->SetWindowText(pTitle);

	m_hDIB = hDIB;
	m_nAutoSize = nAutoSize;
	m_fPictRatio = fPictRatio;

	UpdateAllViews(NULL);
}

void CPictDoc::OnPictAutosize() 
{
	m_nAutoSize = (m_nAutoSize == AUTOSIZE_BOTH ? AUTOSIZE_NONE : AUTOSIZE_BOTH);
	UpdateAllViews(NULL);
}

void CPictDoc::OnPictAutosize2() 
{
	m_nAutoSize = (m_nAutoSize == AUTOSIZE_WIDTH ? AUTOSIZE_NONE : AUTOSIZE_WIDTH);
	UpdateAllViews(NULL);
}

void CPictDoc::OnPictEnlarge() 
{
	m_nAutoSize = AUTOSIZE_NONE;
	m_fPictRatio *= 1.5;
	UpdateAllViews(NULL);
}

void CPictDoc::OnPictReduce() 
{
	m_nAutoSize = AUTOSIZE_NONE;
	m_fPictRatio /= 1.5;
	UpdateAllViews(NULL);
}

void CPictDoc::OnPictLeftRotate() 
{
	MMPictureRotate(m_hDIB, 3);
	UpdateAllViews(NULL);
}

void CPictDoc::OnPictRightRotate() 
{
	MMPictureRotate(m_hDIB, 1);
	UpdateAllViews(NULL);
}

void CPictDoc::OnSelchangeCombo() 
{
	CComboBox &comboBox = GetPictFrame()->GetComboBox();

	int n = comboBox.GetCurSel();
	if (n != LB_ERR) {
		CString str;

		comboBox.GetLBText(n, str);
		comboBox.SetWindowText(str);
		m_nAutoSize = AUTOSIZE_NONE;
		m_fPictRatio = atof(str) / 100;
		UpdateAllViews(NULL);
	}
}

void CPictDoc::OnEditchangeCombo() 
{
	CComboBox &comboBox = GetPictFrame()->GetComboBox();
	CString str1, str2;

	comboBox.GetWindowText(str1);
	str2 = str1.SpanIncluding("0123456789%");
	if (str1.GetLength() != str2.GetLength()) {
		comboBox.SetWindowText(str2);
		comboBox.SetEditSel(str2.GetLength(), str2.GetLength());
	}
}

CPictFrame *CPictDoc::GetPictFrame()
{
	CPictFrame *pPictFrame = NULL;

	POSITION pos = GetFirstViewPosition();
	if (pos != NULL) {
		CView *pView = GetNextView(pos);
		if (pView != NULL)
			pPictFrame = (CPictFrame *)pView->GetParent();
	}

	return pPictFrame;
}

BOOL CPictDoc::OnCmdMsg(UINT nID, int nCode, void* pExtra, AFX_CMDHANDLERINFO* pHandlerInfo) 
{
	if (nID == 1 && nCode == 0) {
		CString str;
		m_nAutoSize = AUTOSIZE_NONE;
		GetPictFrame()->GetComboBox().GetWindowText(str);
		m_fPictRatio = atof(str) / 100;
		UpdateAllViews(NULL);
	}
	
	return CDocument::OnCmdMsg(nID, nCode, pExtra, pHandlerInfo);
}

void CPictDoc::OnUpdatePictAutosize(CCmdUI* pCmdUI) 
{
	pCmdUI->SetCheck(m_nAutoSize == AUTOSIZE_BOTH);
}

void CPictDoc::OnUpdatePictAutosize2(CCmdUI* pCmdUI) 
{
	pCmdUI->SetCheck(m_nAutoSize == AUTOSIZE_WIDTH);
}

void CPictDoc::OnClosePict() 
{
	OnFileClose();
}
