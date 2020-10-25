// MMLibInfoView.cpp : インプリメンテーション ファイル
//

#include "stdafx.h"
#include "MMLib.h"
#include "MainFrm.h"
#include "MMLibInfoView.h"
#include "FindDb.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

/////////////////////////////////////////////////////////////////////////////
// CMMLibInfoView

IMPLEMENT_DYNCREATE(CMMLibInfoView, CFormView)

CMMLibInfoView::CMMLibInfoView()
	: CFormView(CMMLibInfoView::IDD)
{

	m_pCurrentItem = NULL;
	m_nFormHeight = 0;
}

CMMLibInfoView::~CMMLibInfoView()
{
}

void CMMLibInfoView::DoDataExchange(CDataExchange* pDX)
{
	CFormView::DoDataExchange(pDX);
	DDX_Control(pDX, IDC_UPDATEDATE, m_cUpdateDate);
	DDX_Control(pDX, IDC_CREATEDATE, m_cCreateDate);
	DDX_Control(pDX, IDC_CODE, m_cCode);
	DDX_Control(pDX, IDC_UPDATE_INFO, m_cUpdateInfo);
	DDX_Control(pDX, IDC_TITLE, m_cTitle);
	DDX_Control(pDX, IDC_KEYWORD, m_cKeyword);
	DDX_Control(pDX, IDC_REMARK, m_cRemark);
	DDX_Control(pDX, IDC_CREATOR, m_cCreator);
}


BEGIN_MESSAGE_MAP(CMMLibInfoView, CFormView)
	ON_WM_SIZE()
	ON_BN_CLICKED(IDC_UPDATE_INFO, OnUpdateInfo)
	ON_EN_CHANGE(IDC_CODE, OnChangeCode)
	ON_EN_CHANGE(IDC_TITLE, OnChangeTitle)
	ON_EN_CHANGE(IDC_CREATOR, OnChangeCreator)
	ON_EN_CHANGE(IDC_REMARK, OnChangeRemark)
	ON_EN_CHANGE(IDC_KEYWORD, OnChangeKeyword)
	ON_COMMAND(ID_EDIT_COPY, OnEditCopy)
	ON_COMMAND(ID_EDIT_CUT, OnEditCut)
	ON_COMMAND(ID_EDIT_PASTE, OnEditPaste)
	ON_UPDATE_COMMAND_UI(ID_EDIT_PASTE, OnUpdateEditPaste)
	ON_UPDATE_COMMAND_UI(ID_EDIT_COPY, OnUpdateEditCopy)
	ON_UPDATE_COMMAND_UI(ID_EDIT_CUT, OnUpdateEditCut)
	ON_MESSAGE(WM_GETSELECTEDITEM, OnGetSelectedItem)
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CMMLibInfoView 診断

#ifdef _DEBUG
void CMMLibInfoView::AssertValid() const
{
	CFormView::AssertValid();
}

void CMMLibInfoView::Dump(CDumpContext& dc) const
{
	CFormView::Dump(dc);
}
#endif //_DEBUG

/////////////////////////////////////////////////////////////////////////////
// CMMLibInfoView メッセージ ハンドラ

void CMMLibInfoView::OnSize(UINT nType, int cx, int cy) 
{
	CMainFrame *pMainFrame = (CMainFrame *)AfxGetApp()->m_pMainWnd;

	CFormView::OnSize(nType, cx, cy);

	if (pMainFrame != NULL)
		pMainFrame->MaxInfoView(m_nFormHeight);
}

void CMMLibInfoView::OnUpdate(CView* pSender, LPARAM lHint, CObject* pHint) 
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
	case VIEWHINT_SETFONT:
	case VIEWHINT_UPDATEWINDOW:
		DrawItem();
		break;
	case VIEWHINT_MODIFYDATA:
		ModifyData();
		break;
	case VIEWHINT_SAVEDATA:
		SaveData((CItem *)pHint);
		break;
	}
}

void CMMLibInfoView::DrawItem()
{
	HTREEITEM hItem = g_pTreeCtrl->GetSelectedItem();

	if (hItem != NULL) {
		m_pCurrentItem = (CItem *)g_pTreeCtrl->GetItemData(hItem);
		switch (m_pCurrentItem->m_nItemType) {
		case ITEM_ROOT:
			DrawRootInfo();
			break;
		case ITEM_FOLDER:
			DrawFolderInfo();
			break;
		case ITEM_DATA:
			DrawDataInfo();
			break;
		case ITEM_FIND:
			DrawFindInfo();
			break;
		default:
			DrawOtherInfo();
			break;
		}
	}

	m_cUpdateInfo.EnableWindow(FALSE);
}

void CMMLibInfoView::DrawRootInfo() 
{
	m_cTitle.LoadString(IDS_MULTIMEDIA_LIBRARY);
	m_cCode = "";
	m_cCreator = "";
	m_cRemark = "";
	m_cKeyword = "";
	m_cCreateDate = "";
	m_cUpdateDate = "";

	m_cTitle.EnableWindow(FALSE);
	m_cCode.EnableWindow(FALSE);
	m_cCreator.EnableWindow(FALSE);
	m_cRemark.EnableWindow(FALSE);
	m_cKeyword.EnableWindow(FALSE);
}

void CMMLibInfoView::DrawFolderInfo() 
{
	CItemFolder *pItemFolder = (CItemFolder *)m_pCurrentItem;

	m_cTitle = pItemFolder->m_FolderRec.folderName;
	m_cCode = "";
	m_cCreator = pItemFolder->m_FolderRec.creator;
	m_cRemark = pItemFolder->m_FolderRec.remark;
	m_cKeyword = pItemFolder->m_FolderRec.keyword;
	m_cCreateDate = pItemFolder->m_FolderRec.createDate;
	m_cUpdateDate = pItemFolder->m_FolderRec.updateDate;

	m_cTitle.EnableWindow(TRUE);
	m_cCode.EnableWindow(FALSE);
	m_cCreator.EnableWindow(TRUE);
	m_cRemark.EnableWindow(TRUE);
	m_cKeyword.EnableWindow(TRUE);
}

void CMMLibInfoView::DrawDataInfo() 
{
	CItemData *pItemData = (CItemData *)m_pCurrentItem;

	m_cTitle = pItemData->m_DataRec.title;
	m_cCode = pItemData->m_DataRec.code;
	m_cCreator = pItemData->m_DataRec.creator;
	m_cRemark = pItemData->m_DataRec.remark;
	m_cKeyword = pItemData->m_DataRec.keyword;
	m_cCreateDate = pItemData->m_DataRec.createDate;
	m_cUpdateDate = pItemData->m_DataRec.updateDate;

	m_cTitle.EnableWindow(TRUE);
	m_cCode.EnableWindow(TRUE);
	m_cCreator.EnableWindow(TRUE);
	m_cRemark.EnableWindow(TRUE);
	m_cKeyword.EnableWindow(TRUE);
}

void CMMLibInfoView::DrawFindInfo() 
{
	CItemFind *pItemFind = (CItemFind *)m_pCurrentItem;

	m_cTitle = pItemFind->m_FindRec.folderName;
	m_cCode = "";
	m_cCreator = "";
	m_cRemark = "";
	m_cKeyword = "";
	m_cCreateDate = "";
	m_cUpdateDate = "";

	m_cTitle.EnableWindow(TRUE);
	m_cCode.EnableWindow(FALSE);
	m_cCreator.EnableWindow(FALSE);
	m_cRemark.EnableWindow(FALSE);
	m_cKeyword.EnableWindow(FALSE);
}

void CMMLibInfoView::DrawOtherInfo() 
{
	m_cTitle = "";
	m_cCode = "";
	m_cCreator = "";
	m_cRemark = "";
	m_cKeyword = "";
	m_cCreateDate = "";
	m_cUpdateDate = "";

	m_cTitle.EnableWindow(FALSE);
	m_cCode.EnableWindow(FALSE);
	m_cCreator.EnableWindow(FALSE);
	m_cRemark.EnableWindow(FALSE);
	m_cKeyword.EnableWindow(FALSE);
}

void CMMLibInfoView::OnUpdateInfo() 
{
	CDocument *pDoc = GetDocument();

	if (m_pCurrentItem != NULL) {
		pDoc->UpdateAllViews(NULL, VIEWHINT_SAVEDATA, m_pCurrentItem);
		pDoc->UpdateAllViews(NULL, VIEWHINT_UPDATEITEM, m_pCurrentItem);
	}
}

void CMMLibInfoView::SaveData(CItem *pItem)
{
	if (pItem != NULL) {
		switch (pItem->m_nItemType) {
		case ITEM_FOLDER:
			SaveFolderInfo((CItemFolder *)pItem);
			break;
		case ITEM_DATA:
			SaveDataInfo((CItemData *)pItem);
			break;
		case ITEM_FIND:
			SaveFindInfo((CItemFind *)pItem);
			break;
		}
	}
}

void CMMLibInfoView::SaveFolderInfo(CItemFolder *pItemFolder)
{
	FOLDERREC &folderRec = pItemFolder->m_FolderRec;

	folderRec.folderName = m_cTitle;
	folderRec.creator = m_cCreator;
	folderRec.remark = m_cRemark;
	folderRec.keyword = m_cKeyword;

	pItemFolder->m_pPcsDB->UpdateFolderEx(&pItemFolder->m_FolderRec);
}

void CMMLibInfoView::SaveDataInfo(CItemData *pItemData)
{
	DATAREC &DataRec = pItemData->m_DataRec;

	DataRec.title = m_cTitle;
	DataRec.code = m_cCode;
	DataRec.creator = m_cCreator;
	DataRec.remark = m_cRemark;
	DataRec.keyword = m_cKeyword;

	pItemData->m_pPcsDB->UpdateDataEx(&pItemData->m_DataRec);
}

void CMMLibInfoView::SaveFindInfo(CItemFind *pItemFind)
{
	FINDREC &FindRec = pItemFind->m_FindRec;

	FindRec.folderName = m_cTitle;

	if (pItemFind->m_bSave) {
		CFindDB findDB;
		if (findDB.Open(FALSE))
			findDB.UpdateFind(&pItemFind->m_FindRec);
	}
}

void CMMLibInfoView::OnInitialUpdate() 
{
	CFormView::OnInitialUpdate();

	m_cCode.LimitText(LEN_CODE);
	m_cTitle.LimitText(LEN_TITLE);
	m_cCreator.LimitText(LEN_CREATOR);
	m_cRemark.LimitText(LEN_REMARK);
	m_cKeyword.LimitText(LEN_KEYWORD);

	SIZE size = GetTotalSize();
	m_nFormHeight = size.cy;

	size.cx = 0;
	SetScrollSizes(MM_TEXT, size);

	CMainFrame *pMainFrame = (CMainFrame *)AfxGetApp()->m_pMainWnd;
	if (pMainFrame != NULL)
		pMainFrame->MaxInfoView(m_nFormHeight);
}

void CMMLibInfoView::ModifyData() 
{
	m_cUpdateInfo.EnableWindow(TRUE);
}

void CMMLibInfoView::OnChangeCode() 
{
	ModifyData();
}

void CMMLibInfoView::OnChangeTitle() 
{
	ModifyData();
}

void CMMLibInfoView::OnChangeCreator() 
{
	ModifyData();
}

void CMMLibInfoView::OnChangeRemark() 
{
	ModifyData();
}

void CMMLibInfoView::OnChangeKeyword() 
{
	ModifyData();
}

LRESULT CMMLibInfoView::OnGetSelectedItem(WPARAM wParam, LPARAM lParam)
{
	return (LRESULT)m_pCurrentItem;
}

void CMMLibInfoView::OnEditCopy() 
{
	CWnd *pWnd;

	if ((pWnd = GetFocus()) != NULL)
		pWnd->SendMessage(WM_COPY);
}

void CMMLibInfoView::OnUpdateEditCopy(CCmdUI* pCmdUI) 
{
	pCmdUI->Enable(TRUE);
}

void CMMLibInfoView::OnEditCut() 
{
	CWnd *pWnd;

	if ((pWnd = GetFocus()) != NULL)
		pWnd->SendMessage(WM_CUT);
}

void CMMLibInfoView::OnUpdateEditCut(CCmdUI* pCmdUI) 
{
	pCmdUI->Enable(TRUE);
}

void CMMLibInfoView::OnEditPaste() 
{
	CWnd *pWnd;

	if ((pWnd = GetFocus()) != NULL)
		pWnd->SendMessage(WM_PASTE);
}

void CMMLibInfoView::OnUpdateEditPaste(CCmdUI* pCmdUI) 
{
	UINT nFormat = 0;
	BOOL bEnable  = FALSE;

	if (OpenClipboard()) {
		while (nFormat = EnumClipboardFormats(nFormat)) {
			if (nFormat == CF_TEXT) {
				bEnable = TRUE;
				break;
			}
		}
		CloseClipboard();
	}

	pCmdUI->Enable(bEnable);
}
