// MMLibTextView.cpp : インプリメンテーション ファイル
//

#include "stdafx.h"
#include "MMLib.h"
#include "MMLibTextView.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

/////////////////////////////////////////////////////////////////////////////
// CMMLibTextView

IMPLEMENT_DYNCREATE(CMMLibTextView, CEditView)

CMMLibTextView::CMMLibTextView()
{
	m_pCurrentItem = NULL;
}

CMMLibTextView::~CMMLibTextView()
{
}


BEGIN_MESSAGE_MAP(CMMLibTextView, CEditView)
	ON_WM_CREATE()
	ON_CONTROL_REFLECT(EN_CHANGE, OnChange)
	ON_MESSAGE(WM_GETSELECTEDITEM, OnGetSelectedItem)
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CMMLibTextView 描画

void CMMLibTextView::OnDraw(CDC* pDC)
{
	CDocument* pDoc = GetDocument();
	// TODO: この位置に描画用のコードを追加してください
}

/////////////////////////////////////////////////////////////////////////////
// CMMLibTextView 診断

#ifdef _DEBUG
void CMMLibTextView::AssertValid() const
{
	CEditView::AssertValid();
}

void CMMLibTextView::Dump(CDumpContext& dc) const
{
	CEditView::Dump(dc);
}
#endif //_DEBUG

/////////////////////////////////////////////////////////////////////////////
// CMMLibTextView メッセージ ハンドラ

BOOL CMMLibTextView::PreCreateWindow(CREATESTRUCT& cs) 
{
	cs.style |= ES_AUTOHSCROLL | ES_AUTOVSCROLL | ES_MULTILINE | WS_VSCROLL;
	return CEditView::PreCreateWindow(cs);
}

int CMMLibTextView::OnCreate(LPCREATESTRUCT lpCreateStruct) 
{
	if (CEditView::OnCreate(lpCreateStruct) == -1)
		return -1;

	m_DropTarget.Register(this);

	return 0;
}

void CMMLibTextView::OnUpdate(CView* pSender, LPARAM lHint, CObject* pHint) 
{
	switch (lHint) {
	case VIEWHINT_ADDITEM:
	case VIEWHINT_SELECTITEM:
	case VIEWHINT_OPENITEM:
	case VIEWHINT_CLOSEITEM:
	case VIEWHINT_REMOVEITEM:
	case VIEWHINT_UPDATEITEM:
	case VIEWHINT_RELOADITEM:
	case VIEWHINT_REFINDITEM:
	case VIEWHINT_DRAWALL:
	case VIEWHINT_UPDATEWINDOW:
		DrawItems();
		break;
	case VIEWHINT_SETFONT:
		SetViewFont();
		break;
	case VIEWHINT_SAVEDATA:
		SaveData((CItem *)pHint);
		break;
	case VIEWHINT_TEXTFILE:
		LoadTextFile((CFile *)pHint);
		break;
	}
}

void CMMLibTextView::SaveData(CItem *pItem)
{
	CItemFolder *pItemFolder;
	CItemData *pItemData;

	if (pItem != NULL && GetEditCtrl().GetModify()) {
		CString text;
		GetWindowText(text);

		switch (pItem->m_nItemType) {
		case ITEM_FOLDER:
			pItemFolder = (CItemFolder *)pItem;
			pItemFolder->m_pPcsDB->WriteTextFolderEx(pItemFolder->m_FolderRec.folderID, &text);
			break;
		case ITEM_DATA:
			pItemData = (CItemData *)pItem;
			pItemData->m_pPcsDB->WriteTextDataEx(pItemData->m_DataRec.dataID, &text);
			break;
		}
	}
}

void CMMLibTextView::DrawItems()
{
	HTREEITEM hItem = g_pTreeCtrl->GetSelectedItem();

	if (hItem != NULL) {
		m_pCurrentItem = (CItem *)g_pTreeCtrl->GetItemData(hItem);
		switch (m_pCurrentItem->m_nItemType) {
		case ITEM_FOLDER:
			DrawFolderText();
			break;
		case ITEM_DATA:
			DrawDataText();
			break;
		default:
			DrawOtherText();
			break;
		}
	}

	GetEditCtrl().SetModify(FALSE);
}

void CMMLibTextView::DrawFolderText() 
{
	CItemFolder *pItemFolder = (CItemFolder *)m_pCurrentItem;
	CString text;

	EnableWindow(TRUE);
	pItemFolder->m_pPcsDB->ReadTextFolderEx(pItemFolder->m_FolderRec.folderID, &text);
	SetWindowText(text);
}

void CMMLibTextView::DrawDataText() 
{
	CItemData *pItemData = (CItemData *)m_pCurrentItem;
	CString text;

	EnableWindow(TRUE);
	pItemData->m_pPcsDB->ReadTextDataEx(pItemData->m_DataRec.dataID, &text);
	SetWindowText(text);
}

void CMMLibTextView::DrawOtherText() 
{
	EnableWindow(FALSE);
	SetWindowText("");
}

void CMMLibTextView::SetViewFont()
{
	m_Font.DeleteObject();
	m_Font.CreatePointFont(g_nViewFontSize, g_ViewFontName);
	SetFont(&m_Font);
}

void CMMLibTextView::LoadTextFile(CFile *pFile)
{
	if (IsWindowEnabled()) {
		DWORD size = (DWORD)pFile->GetLength();
		char *pText = new char[size + 1];
		pText[pFile->Read(pText, size)] = '\0';
		SetWindowText(pText);
		delete [] pText;

		GetEditCtrl().SetModify();
		GetDocument()->UpdateAllViews(NULL, VIEWHINT_MODIFYDATA);
	}
}

DROPEFFECT CMMLibTextView::OnDragOver(COleDataObject* pDataObject, DWORD dwKeyState, CPoint point) 
{
	if (IsWindowEnabled() && pDataObject->IsDataAvailable(CF_HDROP) && !pDataObject->IsDataAvailable(g_nCFMMLibrary))
		return DROPEFFECT_COPY;
	else
		return DROPEFFECT_NONE;
}

BOOL CMMLibTextView::OnDrop(COleDataObject* pDataObject, DROPEFFECT dropEffect, CPoint point) 
{
	if (pDataObject->IsDataAvailable(CF_HDROP))
		return DropFile(pDataObject, dropEffect, point);
	else
		return FALSE;
}

BOOL CMMLibTextView::DropFile(COleDataObject* pDataObject, DROPEFFECT dropEffect, CPoint point)
{
	int nNumFiles;
	char fileNameBuf[256];
	HDROP hDrop;

	if ((hDrop = (HDROP)pDataObject->GetGlobalData(CF_HDROP, NULL)) == NULL)
		return FALSE;

	nNumFiles = ::DragQueryFile(hDrop, -1, NULL, 0);
	if (nNumFiles > 0) {
		::DragQueryFile(hDrop, 0, fileNameBuf, sizeof(fileNameBuf));
		CFile file;
		if (file.Open(fileNameBuf, CFile::modeRead | CFile::shareDenyNone)) {
			LoadTextFile(&file);
			file.Close();
		}
	}

	::GlobalFree(hDrop);

	return TRUE;
}

void CMMLibTextView::OnChange() 
{
	GetDocument()->UpdateAllViews(NULL, VIEWHINT_MODIFYDATA);
}

LRESULT CMMLibTextView::OnGetSelectedItem(WPARAM wParam, LPARAM lParam)
{
	return (LRESULT)m_pCurrentItem;
}

void CMMLibTextView::OnInitialUpdate()
{
	CEditView::OnInitialUpdate();

	SetViewFont();
}
