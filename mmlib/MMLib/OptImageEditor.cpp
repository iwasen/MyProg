// OptImageEditor.cpp : インプリメンテーション ファイル
//

#include "stdafx.h"
#include "mmlib.h"
#include "OptImageEditor.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

/////////////////////////////////////////////////////////////////////////////
// COptImageEditor プロパティ ページ

IMPLEMENT_DYNCREATE(COptImageEditor, CPropertyPage)

COptImageEditor::COptImageEditor() : CPropertyPage(COptImageEditor::IDD)
{
	m_sImageEditor = _T("");
	m_bEditDblClick = FALSE;
}

COptImageEditor::~COptImageEditor()
{
}

void COptImageEditor::DoDataExchange(CDataExchange* pDX)
{
	CPropertyPage::DoDataExchange(pDX);
	DDX_Control(pDX, IDC_IMAGE_EDITOR_ICON, m_cImageEditorIcon);
	DDX_Text(pDX, IDC_IMAGE_EDITOR, m_sImageEditor);
	DDX_Check(pDX, IDC_EDIT_DBLCLICK, m_bEditDblClick);
}


BEGIN_MESSAGE_MAP(COptImageEditor, CPropertyPage)
	ON_BN_CLICKED(IDC_SELECT_FILE, OnSelectFile)
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// COptImageEditor メッセージ ハンドラ

BOOL COptImageEditor::OnInitDialog() 
{
	m_sImageEditor = g_sImageEditor;
	m_bEditDblClick = AfxGetApp()->GetProfileInt(g_SectionOptions, "EditDblClick", 1);

	CPropertyPage::OnInitDialog();
	
	DispIcon();
	
	return TRUE;
}

void COptImageEditor::OnSelectFile() 
{
	CFileDialog dlg(TRUE, "*.exe", NULL,
			OFN_HIDEREADONLY,
			GetString(IDS_PROGRAM) + " (*.exe)|*.exe|" + GetString(IDS_ALL_FILES, 1) + " (*.*)|*.*||", AfxGetMainWnd());

	if (dlg.DoModal() == IDOK) {
		m_sImageEditor = dlg.GetPathName();
		DispIcon();
		UpdateData(FALSE);
	}
}

void COptImageEditor::DispIcon()
{
	HICON hIcon;

	if (!m_sImageEditor.IsEmpty()) {
		UINT nIcon = ::ExtractIconEx(m_sImageEditor, 0, &hIcon, NULL, 1);
		if (nIcon > 0) {
			hIcon = m_cImageEditorIcon.SetIcon(hIcon);
			if (hIcon)
				::DestroyIcon(hIcon);
		}
	}
}

void COptImageEditor::OnOK() 
{
	UpdateData(TRUE);

	g_sImageEditor = m_sImageEditor;
	AfxGetApp()->WriteProfileString(g_SectionOptions, "ImageEditor", g_sImageEditor);
	AfxGetApp()->WriteProfileInt(g_SectionOptions, "EditDblClick", m_bEditDblClick);
	
	CPropertyPage::OnOK();
}
