// FindDoc.cpp : �C���v�������e�[�V���� �t�@�C��
//

#include "stdafx.h"
#include "mmlib.h"
#include "MMLibDoc.h"
#include "FindDoc.h"
#include "MakeIndexDlg.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

/////////////////////////////////////////////////////////////////////////////
// CFindDoc

IMPLEMENT_DYNCREATE(CFindDoc, CDocument)

CFindDoc::CFindDoc()
{
	m_nAndOr = 0;
}

BOOL CFindDoc::OnNewDocument()
{
	return TRUE;
}

CFindDoc::~CFindDoc()
{
}


BEGIN_MESSAGE_MAP(CFindDoc, CDocument)
	ON_COMMAND(IDM_CLOSE_FIND, OnCloseFind)
	ON_COMMAND(IDM_MAKE_INDEX, OnMakeIndex)
	ON_BN_CLICKED(IDC_FIND, OnFind)
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CFindDoc �f�f

#ifdef _DEBUG
void CFindDoc::AssertValid() const
{
	CDocument::AssertValid();
}

void CFindDoc::Dump(CDumpContext& dc) const
{
	CDocument::Dump(dc);
}
#endif //_DEBUG

/////////////////////////////////////////////////////////////////////////////
// CFindDoc �V���A���C�Y

void CFindDoc::Serialize(CArchive& ar)
{
	if (ar.IsStoring())
	{
		// TODO: ���̈ʒu�ɕۑ��p�̃R�[�h��ǉ����Ă�������
	}
	else
	{
		// TODO: ���̈ʒu�ɓǂݍ��ݗp�̃R�[�h��ǉ����Ă�������
	}
}

/////////////////////////////////////////////////////////////////////////////
// CFindDoc �R�}���h

void CFindDoc::OnCloseFind() 
{
	OnFileClose();
}

BOOL CFindDoc::OnOpenDocument(LPCTSTR lpszPathName) 
{
	return TRUE;
}

void CFindDoc::OnMakeIndex() 
{
	CMakeIndexDlg dlg;

	dlg.m_pDoc = m_pMMLibDoc;
	dlg.DoModal();

	UpdateAllViews(NULL, 1);
}

void CFindDoc::OnFind() 
{
	CString text;

	m_pFindFrame->m_wndKeyword.GetWindowText(m_sKeyword);
	if (m_sKeyword.IsEmpty()) {
		AfxMessageBox(IDS_INPUT_KEYWORD);
		m_pFindFrame->m_wndKeyword.SetFocus();
	} else
		UpdateAllViews(NULL, 1);
}
