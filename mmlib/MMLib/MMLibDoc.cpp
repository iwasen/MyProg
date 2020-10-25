// MMLibDoc.cpp : CMMLibDoc クラスの動作の定義を行います。
//

#include "stdafx.h"
#include "MMLib.h"

#include "MMLibDoc.h"
#include "LibOpenDlg.h"
#include "LibNewDlg.h"
#include "FolderDlg.h"
#include "DataDlg.h"
#include "FindDlg.h"
#include "FindSimpleDlg.h"
#include "MMLibDB.h"
#include "OptProperty.h"
#include "BackupDlg.h"
#include "RestoreDlg.h"
#include "TransRegDlg.h"
#include "PictRotateDlg.h"
#include "PasswordDlg.h"
#include "PermissionDlg.h"
#include "FindFrame.h"
#include "FindDoc.h"
#include "MailAttDlg.h"

#include "MainFrm.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

/////////////////////////////////////////////////////////////////////////////
// CMMLibDoc

IMPLEMENT_DYNCREATE(CMMLibDoc, CDocument)

BEGIN_MESSAGE_MAP(CMMLibDoc, CDocument)
	ON_COMMAND(ID_FILE_NEW, OnFileNew)
	ON_COMMAND(ID_FILE_OPEN, OnFileOpen)
	ON_COMMAND(ID_FILE_CLOSE, OnFileClose)
	ON_COMMAND(IDM_VIEW_PICT, OnViewPict)
	ON_COMMAND(IDM_VIEW_LIST, OnViewList)
	ON_COMMAND(IDM_VIEW_DETAIL, OnViewDetail)
	ON_UPDATE_COMMAND_UI(ID_FILE_NEW, OnUpdateFileNew)
	ON_UPDATE_COMMAND_UI(ID_FILE_OPEN, OnUpdateFileOpen)
	ON_UPDATE_COMMAND_UI(ID_FILE_CLOSE, OnUpdateFileClose)
	ON_UPDATE_COMMAND_UI(IDM_VIEW_PICT, OnUpdateViewPict)
	ON_UPDATE_COMMAND_UI(IDM_VIEW_LIST, OnUpdateViewList)
	ON_UPDATE_COMMAND_UI(IDM_VIEW_DETAIL, OnUpdateViewDetail)
	ON_COMMAND(ID_FILE_UPDATE, OnFileUpdate)
	ON_COMMAND(IDM_FILE_DELETE, OnFileDelete)
	ON_COMMAND(IDM_PICT_OPEN, OnPictOpen)
	ON_COMMAND(IDM_VIEW_RELOAD, OnViewReload)
	ON_COMMAND(IDM_VIEW_PREV, OnViewPrev)
	ON_UPDATE_COMMAND_UI(IDM_VIEW_PREV, OnUpdateViewPrev)
	ON_COMMAND(IDM_VIEW_NEXT, OnViewNext)
	ON_UPDATE_COMMAND_UI(IDM_VIEW_NEXT, OnUpdateViewNext)
	ON_COMMAND(IDM_PICT_ENLARGE, OnPictEnlarge)
	ON_UPDATE_COMMAND_UI(IDM_PICT_ENLARGE, OnUpdatePictEnlarge)
	ON_COMMAND(IDM_PICT_REDUCE, OnPictReduce)
	ON_UPDATE_COMMAND_UI(IDM_PICT_REDUCE, OnUpdatePictReduce)
	ON_COMMAND(IDM_PICT_RIGHT_ROTATE, OnPictRightRotate)
	ON_UPDATE_COMMAND_UI(IDM_PICT_RIGHT_ROTATE, OnUpdatePictRightRotate)
	ON_COMMAND(IDM_PICT_LEFT_ROTATE, OnPictLeftRotate)
	ON_UPDATE_COMMAND_UI(IDM_PICT_LEFT_ROTATE, OnUpdatePictLeftRotate)
	ON_COMMAND(IDM_PICT_AUTOSIZE, OnPictAutosize)
	ON_UPDATE_COMMAND_UI(IDM_PICT_AUTOSIZE, OnUpdatePictAutosize)
	ON_COMMAND(IDM_DETAIL_NAME, OnDetailName)
	ON_UPDATE_COMMAND_UI(IDM_DETAIL_NAME, OnUpdateDetailName)
	ON_COMMAND(IDM_DETAIL_CODE, OnDetailCode)
	ON_UPDATE_COMMAND_UI(IDM_DETAIL_CODE, OnUpdateDetailCode)
	ON_COMMAND(IDM_DETAIL_CDATE, OnDetailCdate)
	ON_UPDATE_COMMAND_UI(IDM_DETAIL_CDATE, OnUpdateDetailCdate)
	ON_COMMAND(IDM_DETAIL_UDATE, OnDetailUdate)
	ON_UPDATE_COMMAND_UI(IDM_DETAIL_UDATE, OnUpdateDetailUdate)
	ON_COMMAND(IDM_DETAIL_CREATOR, OnDetailCreator)
	ON_UPDATE_COMMAND_UI(IDM_DETAIL_CREATOR, OnUpdateDetailCreator)
	ON_COMMAND(IDM_DETAIL_REMARK, OnDetailRemark)
	ON_UPDATE_COMMAND_UI(IDM_DETAIL_REMARK, OnUpdateDetailRemark)
	ON_COMMAND(IDM_DETAIL_KEYWORD, OnDetailKeyword)
	ON_UPDATE_COMMAND_UI(IDM_DETAIL_KEYWORD, OnUpdateDetailKeyword)
	ON_COMMAND(IDM_DETAIL_SERVER, OnDetailServer)
	ON_UPDATE_COMMAND_UI(IDM_DETAIL_SERVER, OnUpdateDetailServer)
	ON_COMMAND(IDM_DETAIL_DIRECTORY, OnDetailDirectory)
	ON_UPDATE_COMMAND_UI(IDM_DETAIL_DIRECTORY, OnUpdateDetailDirectory)
	ON_COMMAND(IDM_VIEW_UPFOLDER, OnViewUpfolder)
	ON_UPDATE_COMMAND_UI(IDM_VIEW_UPFOLDER, OnUpdateViewUpfolder)
	ON_UPDATE_COMMAND_UI(ID_FILE_UPDATE, OnUpdateFileUpdate)
	ON_UPDATE_COMMAND_UI(IDM_FILE_DELETE, OnUpdateFileDelete)
	ON_COMMAND(IDM_FIND, OnFind)
	ON_COMMAND(ID_CACHE_READ, OnCacheRead)
	ON_UPDATE_COMMAND_UI(ID_CACHE_READ, OnUpdateCacheRead)
	ON_COMMAND(ID_CACHE_CLEAR, OnCacheClear)
	ON_UPDATE_COMMAND_UI(ID_CACHE_CLEAR, OnUpdateCacheClear)
	ON_COMMAND(IDM_OPTION, OnOption)
	ON_COMMAND(ID_FILE_SEND_MAIL, OnFileSendMail)
	ON_UPDATE_COMMAND_UI(ID_FILE_SEND_MAIL, OnUpdateFileSendMail)
	ON_COMMAND(ID_EDIT_SELECT_ALL, OnEditSelectAll)
	ON_COMMAND(ID_FILE_SAVE, OnFileSave)
	ON_UPDATE_COMMAND_UI(ID_FILE_SAVE, OnUpdateFileSave)
	ON_COMMAND(IDM_PICT_AUTOSIZE2, OnPictAutosize2)
	ON_UPDATE_COMMAND_UI(IDM_PICT_AUTOSIZE2, OnUpdatePictAutosize2)
	ON_CBN_SELCHANGE(IDW_COMBO, OnSelchangeCombo)
	ON_CBN_EDITUPDATE(IDW_COMBO, OnEditchangeCombo)
	ON_UPDATE_COMMAND_UI(IDW_COMBO, OnUpdateCombo)
	ON_COMMAND(IDM_REFIND, OnRefind)
	ON_UPDATE_COMMAND_UI(IDM_REFIND, OnUpdateRefind)
	ON_COMMAND(IDM_FILE_PACK, OnFilePack)
	ON_UPDATE_COMMAND_UI(IDM_FILE_PACK, OnUpdateFilePack)
	ON_COMMAND(ID_FILE_EXPORT, OnFileExport)
	ON_UPDATE_COMMAND_UI(ID_FILE_EXPORT, OnUpdateFileExport)
	ON_COMMAND(IDM_BACKUP, OnBackup)
	ON_COMMAND(IDM_RESTORE, OnRestore)
	ON_COMMAND(IDM_TRANS_REG, OnTransReg)
	ON_COMMAND(IDM_IMPORT_BMP, OnImportBmp)
	ON_UPDATE_COMMAND_UI(IDM_IMPORT_BMP, OnUpdateImportBmp)
	ON_COMMAND(IDM_IMPORT_MML, OnImportMml)
	ON_UPDATE_COMMAND_UI(IDM_IMPORT_MML, OnUpdateImportMml)
	ON_COMMAND(IDM_IMPORT_PPM, OnImportPpm)
	ON_UPDATE_COMMAND_UI(IDM_IMPORT_PPM, OnUpdateImportPpm)
	ON_COMMAND(IDM_PICT_ALL_ROTATE, OnPictAllRotate)
	ON_UPDATE_COMMAND_UI(IDM_PICT_ALL_ROTATE, OnUpdatePictAllRotate)
	ON_COMMAND(IDM_PICT_WINDOW, OnPictWindow)
	ON_UPDATE_COMMAND_UI(IDM_PICT_WINDOW, OnUpdatePictWindow)
	ON_COMMAND(IDM_FILE_PASSWORD, OnFilePassword)
	ON_COMMAND(IDM_FILE_PERMISSION, OnFilePermission)
	ON_COMMAND(ID_EDIT_COPY, OnEditCopy)
	ON_UPDATE_COMMAND_UI(ID_EDIT_COPY, OnUpdateEditCopy)
	ON_COMMAND(ID_EDIT_CUT, OnEditCut)
	ON_UPDATE_COMMAND_UI(ID_EDIT_CUT, OnUpdateEditCut)
	ON_COMMAND(ID_EDIT_PASTE, OnEditPaste)
	ON_UPDATE_COMMAND_UI(ID_EDIT_PASTE, OnUpdateEditPaste)
	ON_COMMAND(ID_NEW_DATA, OnNewData)
	ON_COMMAND(IDM_VIEW_MAX, OnViewMax)
	ON_UPDATE_COMMAND_UI(IDM_VIEW_MAX, OnUpdateViewMax)
	ON_COMMAND(IDM_KEYWORD, OnKeyword)
	ON_COMMAND(IDM_EDIT_IMAGE, OnEditImage)
	ON_UPDATE_COMMAND_UI(IDM_EDIT_IMAGE, OnUpdateEditImage)
	ON_COMMAND(IDM_DETAIL_FIRSTDATA, OnDetailFirstdata)
	ON_UPDATE_COMMAND_UI(IDM_DETAIL_FIRSTDATA, OnUpdateDetailFirstdata)
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CMMLibDoc クラスの構築/消滅

CMMLibDoc::CMMLibDoc()
{
	static int defaultFlag[3][DETAIL_NUM] = {
		{1, 0, 1, 0, 1, 0, 0, 1, 1, 0},
		{1, 0, 1, 0, 1, 0, 0, 0, 0, 0},
		{1, 1, 1, 0, 1, 0, 0, 0, 0, 0}
	};
	static int defaultWidth[DETAIL_NUM] = {
		200, 100, 110, 110, 100, 100, 100, 80, 200, 200
	};
	int i, j;
	char entry[16];

	m_nViewMode = theApp.GetProfileInt(g_SectionSettings, "ViewMode", VIEWMODE_DETAIL);
	m_nAutoSize = theApp.GetProfileInt(g_SectionSettings, "AutoSize", 0);
	m_bPictView = FALSE;
	m_bMaxPict = FALSE;

	for (i = 0; i < 3; i++) {
		for (j = 0; j < DETAIL_NUM; j++) {
			sprintf(entry, "Flag%d-%d", i, j);
			m_bDetailFlag[i][j] = theApp.GetProfileInt(g_SectionDetails, entry, defaultFlag[i][j]);

			sprintf(entry, "Width%d-%d", i, j);
			m_nDetailWidth[i][j] = theApp.GetProfileInt(g_SectionDetails, entry, defaultWidth[j]);
		}
	}

	theApp.m_pDoc = this;
}

CMMLibDoc::~CMMLibDoc()
{
	int i, j;
	char entry[16];

	theApp.WriteProfileInt(g_SectionSettings, "ViewMode", m_nViewMode);
	theApp.WriteProfileInt(g_SectionSettings, "AutoSize", m_nAutoSize);

	for (i = 0; i < 3; i++) {
		for (j = 0; j < DETAIL_NUM; j++) {
			sprintf(entry, "Flag%d-%d", i, j);
			theApp.WriteProfileInt(g_SectionDetails, entry, m_bDetailFlag[i][j]);

			sprintf(entry, "Width%d-%d", i, j);
			theApp.WriteProfileInt(g_SectionDetails, entry, m_nDetailWidth[i][j]);
		}
	}
}

BOOL CMMLibDoc::OnNewDocument()
{
//	if (!CDocument::OnNewDocument())
//		return FALSE;

	return TRUE;
}



/////////////////////////////////////////////////////////////////////////////
// CMMLibDoc シリアライゼーション

void CMMLibDoc::Serialize(CArchive& ar)
{
	if (ar.IsStoring())
	{
		// TODO: この位置に保存用のコードを追加してください。
	}
	else
	{
		// TODO: この位置に読み込み用のコードを追加してください。
	}
}

/////////////////////////////////////////////////////////////////////////////
// CMMLibDoc クラスの診断

#ifdef _DEBUG
void CMMLibDoc::AssertValid() const
{
	CDocument::AssertValid();
}

void CMMLibDoc::Dump(CDumpContext& dc) const
{
	CDocument::Dump(dc);
}
#endif //_DEBUG

/////////////////////////////////////////////////////////////////////////////
// CMMLibDoc コマンド

void CMMLibDoc::OpenCurrentLibrary()
{
	CWinApp *pWinApp = AfxGetApp();
	CString entry;
	CString server, directory;
	CString libraryID;
	CString title;
	int chType;
	int cacheMode;
	int i, n;

	n = pWinApp->GetProfileInt(g_SectionLibrary, "Num", 0);
	for (i = 0; i < n; i++) {
		entry.Format("Server%d", i);
		server = pWinApp->GetProfileString(g_SectionLibrary, entry);
		entry.Format("ChType%d", i);
		chType = pWinApp->GetProfileInt(g_SectionLibrary, entry, 0);
		entry.Format("Directory%d", i);
		directory = pWinApp->GetProfileString(g_SectionLibrary, entry);
		entry.Format("CacheMode%d", i);
		cacheMode = pWinApp->GetProfileInt(g_SectionLibrary, entry, 0);
		entry.Format("Title%d", i);
		title = pWinApp->GetProfileString(g_SectionLibrary, entry);
		entry.Format("LibraryID%d", i);
		libraryID = pWinApp->GetProfileString(g_SectionLibrary, entry);

		CAddItemPending addItem;

		addItem.m_Title = title;
		addItem.m_ServerName = server;
		addItem.m_nChType = chType;
		addItem.m_DirName = directory;
		addItem.m_LibraryID = libraryID;
		addItem.m_nCacheMode = cacheMode;
		UpdateAllViews(NULL, VIEWHINT_ADDITEM, &addItem);
	}
}

void CMMLibDoc::OpenFind(CItemFolder *pItem)
{
	CFindDB findDB;
	FINDREC findRec;
	CAddItemFind addItem;
	CItemFolder *pItemFolder;
	LPCTSTR pFindID;

	if (!findDB.Open(FALSE))
		return;

	if (pItem->m_nItemType == ITEM_FOLDER) {
		pItemFolder = (CItemFolder *)pItem;

		pFindID = pItemFolder->m_FolderRec.parentFolderID;
		while (findDB.ReadFind(pFindID, &findRec)) {
			addItem.m_pItemFolder = pItemFolder;
			addItem.m_pFindRec = &findRec;
			addItem.m_bSelect = FALSE;
			addItem.m_bSave = TRUE;

			UpdateAllViews(NULL, VIEWHINT_ADDITEM, &addItem);

			pFindID = NULL;
		}
	}
}

void CMMLibDoc::OnFileNew() 
{
	CItem *pItem;

	if ((pItem = GetSelectedItem()) != NULL) {
		switch (pItem->m_nItemType) {
		case ITEM_ROOT:
			CreateLibrary();
			break;
		case ITEM_FOLDER:
		case ITEM_INBOX:
			switch (((CItemFolder *)pItem)->m_nFolderType) {
			case FTYPE_LIBRARY:
			case FTYPE_GFOLDER:
				CreateFolder((CItemFolder *)pItem);
				break;
			case FTYPE_DFOLDER:
				CreateData((CItemFolder *)pItem);
				break;
			}
			break;
		}
	}
}

void CMMLibDoc::OnUpdateFileNew(CCmdUI* pCmdUI) 
{
	pCmdUI->Enable(CheckSelectedItemType(ITEM_ROOT | ITEM_FOLDER | ITEM_INBOX));
}

void CMMLibDoc::CreateLibrary()
{
	CLibNewDlg dlg;

	if (dlg.DoModal() != IDOK)
		return;

	CWaitCursor wait;

	if (dlg.m_iChType == CHT_LOCAL)
		CreateDirectoryAll(dlg.m_sDirectory);

	FOLDERREC folderRec;
	CMMPcsDB *pPcsDB;

	folderRec.folderType =  FTYPE_LIBRARY;
	folderRec.folderName = dlg.m_sLibName;
	folderRec.creator = dlg.m_sCreator;
	folderRec.remark = dlg.m_sRemark;
	folderRec.keyword = dlg.m_sKeyword;

	if ((pPcsDB = g_pMMLibDB->CreateMMLibrary(dlg.m_iChType, dlg.m_sServerName, dlg.m_sDirectory, dlg.m_bCache, &folderRec)) == NULL)
		return;

	CAddItemLibrary addItem;
	addItem.m_pPcsDB = pPcsDB;
	UpdateAllViews(NULL, VIEWHINT_ADDITEM, &addItem);
}

void CMMLibDoc::CreateFolder(CItemFolder *pItemFolder)
{
	if (!Offlixxxheck(pItemFolder))
		return;

	CFolderNewDlg dlg;

	DWORD userNameSize = MAXUSERNAME;

	GetUserName(dlg.m_sCreator.GetBuffer(MAXUSERNAME), &userNameSize);
	dlg.m_sCreator.ReleaseBuffer();
	dlg.m_FolderRec = m_FolderRec;

	if (dlg.DoModal() != IDOK)
		return;

	FOLDERREC folderRec;

	folderRec.parentFolderID = pItemFolder->m_FolderRec.folderID;
	folderRec.folderType = (dlg.m_iFolderType == 0) ? FTYPE_GFOLDER : FTYPE_DFOLDER;
	folderRec.folderName = dlg.m_sFolderName;
	folderRec.creator = dlg.m_sCreator;
	folderRec.remark = dlg.m_sRemark;
	folderRec.keyword = dlg.m_sKeyword;

	if (pItemFolder->m_pPcsDB->AddFolderEx(&folderRec)) {
		pItemFolder->m_pPcsDB->WriteTextFolderEx(folderRec.folderID, &dlg.m_sTextData);

		CAddItemFolder addItem;
		addItem.m_pItemFolder = pItemFolder;
		addItem.m_pFolderRec = &folderRec;
		UpdateAllViews(NULL, VIEWHINT_ADDITEM, &addItem);

		m_FolderRec = folderRec;
	}
}

void CMMLibDoc::CreateData(CItemFolder *pItemFolder)
{
	if (!Offlixxxheck(pItemFolder))
		return;

	CDataDlg	dlg;

	dlg.m_pItemFolder = pItemFolder;
	dlg.m_pDoc = this;
	dlg.m_Caption.LoadString(IDS_NEW_DATA2);
	dlg.m_DataRec = m_DataRec;

	dlg.DoModal();

	m_DataRec = dlg.m_DataRec;
}

void CMMLibDoc::OnFileOpen() 
{
	CItem *pItem;

	if ((pItem = GetSelectedItem()) != NULL) {
		switch (pItem->m_nItemType) {
		case ITEM_ROOT:
			OpenLibrary();
			break;
		case ITEM_FOLDER:
		case ITEM_FIND:
		case ITEM_INBOX:
			UpdateAllViews(NULL, VIEWHINT_OPENITEM, pItem);
			break;
		}
	}
}

void CMMLibDoc::OnUpdateFileOpen(CCmdUI* pCmdUI) 
{
	pCmdUI->Enable(CheckSelectedItemType(ITEM_ROOT | ITEM_FOLDER | ITEM_FIND | ITEM_INBOX));
}

void CMMLibDoc::OpenLibrary()
{
	CLibOpenDlg dlg;

	if (dlg.DoModal() != IDOK)
		return;

	CWaitCursor wait;

	CMMPcsDB *pPcsDB;

	if ((pPcsDB = g_pMMLibDB->OpenMMLibrary(dlg.m_iChType, dlg.m_sServerName, dlg.m_sDirectory, dlg.m_bCache)) != NULL) {
		CAddItemLibrary addItem;
		addItem.m_pPcsDB = pPcsDB;
		UpdateAllViews(NULL, VIEWHINT_ADDITEM, &addItem);
	}
}

void CMMLibDoc::OnFileClose() 
{
	CItem *pItem, *pNextSelected;
	CItemFolder *pItemFolder;
	CItemFind *pItemFind;

	for (pItem = GetSelectedItem(); pItem != NULL; pItem = pNextSelected) {
		pNextSelected = pItem->m_pNextSelected;

		switch (pItem->m_nItemType) {
		case ITEM_FOLDER:
		case ITEM_INBOX:
			pItemFolder = (CItemFolder *)pItem;
			if (pItemFolder->m_nFolderType == FTYPE_LIBRARY) {
				CMMPcsDB *pPcsDB = pItemFolder->m_pPcsDB;
				UpdateAllViews(NULL, VIEWHINT_REMOVEITEM, pItem);
				g_pMMLibDB->CloseMMLibrary(pPcsDB);
			} else
				UpdateAllViews(NULL, VIEWHINT_CLOSEITEM, pItem);
			break;
		case ITEM_FIND:
			pItemFind = (CItemFind *)pItem;
			if (pItemFind->m_bSave) {
				CFindDB findDB;
				if (findDB.Open(FALSE))
					findDB.DeleteFind(pItemFind->m_FindRec.findID);
			}
			UpdateAllViews(NULL, VIEWHINT_REMOVEITEM, pItem);
			break;
		case ITEM_PENDING:
			UpdateAllViews(NULL, VIEWHINT_REMOVEITEM, pItem);
			break;
		}
	}
}

void CMMLibDoc::OnUpdateFileClose(CCmdUI* pCmdUI) 
{
	pCmdUI->Enable(CheckSelectedItemType(ITEM_ROOT | ITEM_FOLDER | ITEM_PENDING | ITEM_FIND | ITEM_INBOX));
}

void CMMLibDoc::OnViewPict() 
{
	m_nViewMode = VIEWMODE_PICT;
	UpdateAllViews(NULL, VIEWHINT_DRAWALL);
}

void CMMLibDoc::OnUpdateViewPict(CCmdUI* pCmdUI) 
{
	pCmdUI->SetRadio(m_nViewMode == VIEWMODE_PICT);
}

void CMMLibDoc::OnViewList() 
{
	m_nViewMode = VIEWMODE_LIST;
	UpdateAllViews(NULL, VIEWHINT_DRAWLIST);
}

void CMMLibDoc::OnUpdateViewList(CCmdUI* pCmdUI) 
{
	pCmdUI->SetRadio(m_nViewMode == VIEWMODE_LIST);
}

void CMMLibDoc::OnViewDetail() 
{
	m_nViewMode = VIEWMODE_DETAIL;
	UpdateAllViews(NULL, VIEWHINT_DRAWLIST);
}

void CMMLibDoc::OnUpdateViewDetail(CCmdUI* pCmdUI) 
{
	pCmdUI->SetRadio(m_nViewMode == VIEWMODE_DETAIL);
}

void CMMLibDoc::OnFileUpdate() 
{
	CItem *pItem, *pNextSelected;

	for (pItem = GetSelectedItem(); pItem != NULL; pItem = pNextSelected) {
		pNextSelected = pItem->m_pNextSelected;

		switch (pItem->m_nItemType) {
		case ITEM_FOLDER:
			UpdateFolder((CItemFolder *)pItem);
			break;
		case ITEM_DATA:
			UpdateData((CItemData *)pItem);
			break;
		case ITEM_FIND:
			UpdateFind((CItemFind *)pItem);
			break;
		}
	}
}

void CMMLibDoc::OnUpdateFileUpdate(CCmdUI* pCmdUI) 
{
	pCmdUI->Enable(CheckSelectedItemType(ITEM_FOLDER | ITEM_DATA | ITEM_FIND));
}

void CMMLibDoc::UpdateFolder(CItemFolder *pItemFolder)
{
	if (!Offlixxxheck(pItemFolder))
		return;

	FOLDERREC &folderRec = pItemFolder->m_FolderRec;
	CFolderDlg dlg;

	dlg.m_sFolderName = folderRec.folderName;
	dlg.m_sCreator = folderRec.creator;
	dlg.m_sRemark = folderRec.remark;
	dlg.m_sKeyword = folderRec.keyword;
	pItemFolder->m_pPcsDB->ReadTextFolderEx(pItemFolder->GetID(), &dlg.m_sTextData);
	dlg.m_Caption.LoadString(pItemFolder->m_nFolderType == FTYPE_LIBRARY ? IDS_LIBRARY_UPDATE : IDS_FOLDER_UPDATE);
	dlg.m_FolderRec = m_FolderRec;

	if (dlg.DoModal() != IDOK)
		return;

	folderRec.folderName = dlg.m_sFolderName;
	folderRec.creator = dlg.m_sCreator;
	folderRec.remark = dlg.m_sRemark;
	folderRec.keyword = dlg.m_sKeyword;

	if (pItemFolder->m_pPcsDB->UpdateFolderEx(&pItemFolder->m_FolderRec)) {
		pItemFolder->m_pPcsDB->WriteTextFolderEx(pItemFolder->GetID(), &dlg.m_sTextData);
		UpdateAllViews(NULL, VIEWHINT_UPDATEITEM, pItemFolder);

		m_FolderRec = folderRec;
	}
}

void CMMLibDoc::UpdateData(CItemData *pItemData)
{
	if (!Offlixxxheck(pItemData))
		return;

	CDataDlg	dlg;

	dlg.m_pItemFolder = (CItemFolder *)pItemData->GetParent();
	dlg.m_pItemData = pItemData;
	dlg.m_pDoc = this;
	dlg.m_Caption.LoadString(IDS_DATA_UPDATE);
	dlg.m_DataRec = m_DataRec;

	dlg.DoModal();

	m_DataRec = dlg.m_DataRec;
}

void CMMLibDoc::UpdateFind(CItemFind *pItemFind)
{
	if (pItemFind->m_nFindType == FIND_TYPE_SIMPLE)
		UpdateFindSimple(pItemFind);
	else
		UpdateFindExpert(pItemFind);
}

void CMMLibDoc::UpdateFindSimple(CItemFind *pItemFind)
{
	CFindSimpleDlg dlg;

	dlg.m_bUpdateMode = TRUE;
	dlg.m_pItemFolder = (CItemFolder *)pItemFind->GetParent();
	dlg.m_sFindFolder = pItemFind->m_FindRec.folderName;
	dlg.m_bSave = pItemFind->m_bSave;

	dlg.m_sSearchText = pItemFind->m_FindRec.conditions[0].keyword;

	if (dlg.DoModal() == IDCANCEL)
		return;

	if (pItemFind->m_bSave && !dlg.m_bSave) {
		CFindDB findDB;
		if (findDB.Open(FALSE))
			findDB.DeleteFind(pItemFind->m_FindRec.findID);
	}

	pItemFind->m_FindRec.conditions[0].keyword = dlg.m_sSearchText;

	if (pItemFind->m_FindRec.folderName != dlg.m_sFindFolder) {
		pItemFind->m_FindRec.folderName = dlg.m_sFindFolder;
		UpdateAllViews(NULL, VIEWHINT_UPDATEITEM, pItemFind);
	}

	if (dlg.m_bSave) {
		CFindDB findDB;
		if (findDB.Open(TRUE)) {
			if (pItemFind->m_bSave)
				findDB.UpdateFind(&pItemFind->m_FindRec);
			else
				findDB.AddFind(&pItemFind->m_FindRec);
		}
	}

	UpdateAllViews(NULL, VIEWHINT_REFINDITEM, pItemFind);
}

void CMMLibDoc::UpdateFindExpert(CItemFind *pItemFind)
{
	int i;
	CFindDlg dlg;

	dlg.m_bUpdateMode = TRUE;
	dlg.m_pItemFolder = (CItemFolder *)pItemFind->GetParent();
	dlg.m_sFindFolder = pItemFind->m_FindRec.folderName;
	dlg.m_nFindType = pItemFind->m_nFindType;
	dlg.m_bSave = pItemFind->m_bSave;

	for (i = 0; i < N_FIND_CONDITIONS; i++)
		dlg.m_FindConditions[i] = pItemFind->m_FindRec.conditions[i];

	if (dlg.DoModal() == IDCANCEL)
		return;

	if (pItemFind->m_bSave && !dlg.m_bSave) {
		CFindDB findDB;
		if (findDB.Open(FALSE))
			findDB.DeleteFind(pItemFind->m_FindRec.findID);
	}

	for (i = 0; i < N_FIND_CONDITIONS; i++)
		pItemFind->m_FindRec.conditions[i] = dlg.m_FindConditions[i];

	if (pItemFind->m_FindRec.folderName != dlg.m_sFindFolder || pItemFind->m_nFindType != dlg.m_nFindType) {
		pItemFind->m_nFindType = dlg.m_nFindType;
		pItemFind->m_FindRec.findType = (char)pItemFind->m_nFindType;
		pItemFind->m_FindRec.folderName = dlg.m_sFindFolder;
		UpdateAllViews(NULL, VIEWHINT_UPDATEITEM, pItemFind);
	}

	if (dlg.m_bSave) {
		CFindDB findDB;
		if (findDB.Open(TRUE)) {
			if (pItemFind->m_bSave)
				findDB.UpdateFind(&pItemFind->m_FindRec);
			else
				findDB.AddFind(&pItemFind->m_FindRec);
		}
	}

	UpdateAllViews(NULL, VIEWHINT_REFINDITEM, pItemFind);
}

void CMMLibDoc::NameChange(CItem *pItem, LPCTSTR pName)
{
	CItemFolder *pItemFolder;
	CItemData *pItemData;
	CItemFind *pItemFind;

	switch (pItem->m_nItemType) {
	case ITEM_FOLDER:
	case ITEM_INBOX:
		if (!Offlixxxheck(pItem))
			return;

		pItemFolder = (CItemFolder *)pItem;
		pItemFolder->m_FolderRec.folderName = pName;
		if (!pItemFolder->m_pPcsDB->UpdateFolderEx(&pItemFolder->m_FolderRec))
			return;
		break;
	case ITEM_DATA:
		if (!Offlixxxheck(pItem))
			return;

		pItemData = (CItemData *)pItem;
		pItemData->m_DataRec.title = pName;
		if (!pItemData->m_pPcsDB->UpdateDataEx(&pItemData->m_DataRec))
			return;
		break;
	case ITEM_FIND:
		pItemFind = (CItemFind *)pItem;
		pItemFind->m_FindRec.folderName = pName;
		if (pItemFind->m_bSave) {
			CFindDB findDB;
			if (findDB.Open(FALSE))
				findDB.UpdateFind(&pItemFind->m_FindRec);
		}
		break;
	default:
		return;
	}

	UpdateAllViews(NULL, VIEWHINT_UPDATEITEM, pItem);
}

void CMMLibDoc::OnFileDelete() 
{
	CItem *pItem, *pNextSelected;

	if ((pItem = GetSelectedItem()) == NULL)
		return;

	if (!Offlixxxheck(pItem))
		return;

	LPCTSTR text;

	switch (pItem->m_nItemType) {
	case ITEM_FOLDER:
		switch (((CItemFolder *)pItem)->m_nFolderType) {
		case FTYPE_LIBRARY:
			text = GetString(IDS_LIBRARY);
			break;
		default:
			text = GetString(IDS_FOLDER);
			break;
		}
		break;
	case ITEM_DATA:
		text = GetString(IDS_DATA);
		break;
	default:
		PROGRAM_ERROR;
		return;
	}

	CString msg;
	msg.Format(IDS_DELETE_SELECT, text);
	if (AfxMessageBox(msg, MB_ICONQUESTION | MB_OKCANCEL) == IDCANCEL)
		return;

	for ( ; pItem != NULL; pItem = pNextSelected) {
		pNextSelected = pItem->m_pNextSelected;

		switch (pItem->m_nItemType) {
		case ITEM_FOLDER:
			DeleteFolder((CItemFolder *)pItem);
			break;
		case ITEM_DATA:
			DeleteData((CItemData *)pItem);
			break;
		}
	}
}

void CMMLibDoc::OnUpdateFileDelete(CCmdUI* pCmdUI) 
{
	pCmdUI->Enable(CheckSelectedItemType(ITEM_FOLDER | ITEM_DATA));
}

void CMMLibDoc::DeleteFolder(CItemFolder *pItemFolder)
{
	if (!Offlixxxheck(pItemFolder))
		return;

	if (pItemFolder->m_nFolderType == FTYPE_LIBRARY) {
		if (g_pMMLibDB->DeleteMMLibrary(pItemFolder->m_pPcsDB))
			UpdateAllViews(NULL, VIEWHINT_REMOVEITEM, pItemFolder);
		else
			AfxMessageBox(IDS_DELETE_LIBRARY_FAIL);
	} else {
		if (pItemFolder->m_pPcsDB->DeleteFolderEx(pItemFolder->m_FolderRec.folderID))
			UpdateAllViews(NULL, VIEWHINT_REMOVEITEM, pItemFolder);
	}
}

void CMMLibDoc::DeleteData(CItemData *pItemData)
{
	if (!Offlixxxheck(pItemData))
		return;

	if (pItemData->m_pPcsDB->DeleteDataEx(pItemData->m_DataRec.dataID))
		UpdateAllViews(NULL, VIEWHINT_REMOVEITEM, pItemData);
}

void CMMLibDoc::CopyItem(CItem *pItemSrc, CItem *pItemDst)
{
	if (!Offlixxxheck(pItemDst))
		return;

	if (pItemDst->m_nItemType != ITEM_FOLDER && pItemDst->m_nItemType != ITEM_INBOX)
		return;

	CWaitCursor wait;

	while (pItemSrc != NULL) {
		if (pItemSrc != pItemDst) {
			switch (pItemSrc->m_nItemType) {
			case ITEM_FOLDER:
			case ITEM_INBOX:
				CopyFolder((CItemFolder *)pItemSrc, (CItemFolder *)pItemDst);
				break;
			case ITEM_DATA:
				CopyData((CItemData *)pItemSrc, (CItemFolder *)pItemDst);
				break;
			}
		}

		pItemSrc = pItemSrc->m_pNextSelected;
	}
}

void CMMLibDoc::CopyFolder(CItemFolder *pItemSrc, CItemFolder *pItemDst)
{
	FOLDERREC folderRec;

	if (pItemDst->m_nFolderType == FTYPE_DFOLDER)
		return;

	if (CMMPcsDB::CopyFolderEx(pItemSrc->m_pPcsDB, pItemSrc->m_FolderRec.folderID, pItemDst->m_pPcsDB, pItemDst->m_FolderRec.folderID, &folderRec)) {
		CAddItemFolder addItem;
		addItem.m_pItemFolder = pItemDst;
		addItem.m_pFolderRec = &folderRec;
		UpdateAllViews(NULL, VIEWHINT_ADDITEM, &addItem);
	}
}

void CMMLibDoc::CopyData(CItemData *pItemSrc, CItemFolder *pItemDst)
{
	DATAREC dataRec;

	if (pItemDst->m_nFolderType != FTYPE_DFOLDER)
		return;

	if (CMMPcsDB::CopyDataEx(pItemSrc->m_pPcsDB, pItemSrc->m_DataRec.dataID, pItemDst->m_pPcsDB, pItemDst->m_FolderRec.folderID, &dataRec)) {
		CAddItemData addItem;
		addItem.m_pItemFolder = pItemDst;
		addItem.m_pDataRec = &dataRec;
		UpdateAllViews(NULL, VIEWHINT_ADDITEM, &addItem);
	}
}

void CMMLibDoc::MoveItem(CItem *pItemSrc, CItem *pItemDst)
{
	if (!Offlixxxheck(pItemDst))
		return;

	if (pItemDst->m_nItemType != ITEM_FOLDER && pItemDst->m_nItemType != ITEM_INBOX)
		return;

	CWaitCursor wait;
	CItem *pNextSelected;

	while (pItemSrc != NULL) {
		pNextSelected = pItemSrc->m_pNextSelected;

		if (!CheckParent(pItemSrc, pItemDst)) {
			switch (pItemSrc->m_nItemType) {
			case ITEM_FOLDER:
			case ITEM_INBOX:
				MoveFolder((CItemFolder *)pItemSrc, (CItemFolder *)pItemDst);
				break;
			case ITEM_DATA:
				MoveData((CItemData *)pItemSrc, (CItemFolder *)pItemDst);
				break;
			}
		}

		pItemSrc = pNextSelected;
	}
}

void CMMLibDoc::MoveFolder(CItemFolder *pItemSrc, CItemFolder *pItemDst)
{
	FOLDERREC folderRec;
	BOOL rc;

	if (pItemDst->m_nFolderType == FTYPE_DFOLDER)
		return;

	if (pItemSrc->m_pPcsDB == pItemDst->m_pPcsDB)
		rc = pItemSrc->m_pPcsDB->MoveFolderEx(pItemSrc->m_FolderRec.folderID, pItemDst->m_FolderRec.folderID, &folderRec);
	else {
		rc = CMMPcsDB::CopyFolderEx(pItemSrc->m_pPcsDB, pItemSrc->m_FolderRec.folderID, pItemDst->m_pPcsDB, pItemDst->m_FolderRec.folderID, &folderRec);
		if (rc)
			rc = pItemSrc->m_pPcsDB->DeleteFolderEx(pItemSrc->m_FolderRec.folderID);
	}

	if (rc) {
		UpdateAllViews(NULL, VIEWHINT_REMOVEITEM, pItemSrc);

		CAddItemFolder addItem;
		addItem.m_pItemFolder = pItemDst;
		addItem.m_pFolderRec = &folderRec;
		UpdateAllViews(NULL, VIEWHINT_ADDITEM, &addItem);
	}
}

void CMMLibDoc::MoveData(CItemData *pItemSrc, CItemFolder *pItemDst)
{
	DATAREC dataRec;
	BOOL rc;

	if (pItemDst->m_nFolderType != FTYPE_DFOLDER)
		return;

	if (pItemSrc->m_pPcsDB == pItemDst->m_pPcsDB)
		rc = pItemSrc->m_pPcsDB->MoveDataEx(pItemSrc->m_DataRec.dataID, pItemDst->m_FolderRec.folderID, &dataRec);
	else {
		rc = CMMPcsDB::CopyDataEx(pItemSrc->m_pPcsDB, pItemSrc->m_DataRec.dataID, pItemDst->m_pPcsDB, pItemDst->m_FolderRec.folderID, &dataRec);
		if (rc)
			rc = pItemSrc->m_pPcsDB->DeleteDataEx(pItemSrc->m_DataRec.dataID);
	}

	if (rc) {
		UpdateAllViews(NULL, VIEWHINT_REMOVEITEM, pItemSrc);

		CAddItemData addItem;
		addItem.m_pItemFolder = pItemDst;
		addItem.m_pDataRec = &dataRec;
		UpdateAllViews(NULL, VIEWHINT_ADDITEM, &addItem);
	}
}

BOOL CMMLibDoc::CheckParent(CItem *pItemSrc, CItem *pItemDst)
{
	HTREEITEM hItemSrc = pItemSrc->m_hItem;
	HTREEITEM hItemDst = pItemDst->m_hItem;

	for (;;) {
		if (hItemSrc == hItemDst)
			return TRUE;

		if ((hItemDst = g_pTreeCtrl->GetParentItem(hItemDst)) == NULL)
			return FALSE;
	}
}

CItem *CMMLibDoc::GetSelectedItem()
{
	CWinApp *pWinApp = AfxGetApp();

	CView *pView = ((CFrameWnd *)pWinApp->m_pMainWnd)->GetActiveView();

	return (CItem *)pView->SendMessage(WM_GETSELECTEDITEM);
}

void CMMLibDoc::OnPictOpen() 
{
	CItem *pItem;

	if ((pItem = GetSelectedItem()) != NULL) {
		switch (pItem->m_nItemType) {
		case ITEM_DATA:
			UpdateAllViews(NULL, VIEWHINT_SELECTITEM, pItem);
			break;
		}
	}
}

void CMMLibDoc::OnViewReload() 
{
	CWaitCursor wait;

	UpdateAllViews(NULL, VIEWHINT_RELOADITEM);
}

void CMMLibDoc::OnViewPrev() 
{
	CItem *pItem;

	if ((pItem = GetSelectedItem()) != NULL) {
		HTREEITEM hItem = g_pTreeCtrl->GetPrevSiblingItem(pItem->m_hItem);
		if (hItem != NULL)
			UpdateAllViews(NULL, VIEWHINT_SELECTITEM, (CItem *)g_pTreeCtrl->GetItemData(hItem));
	}
}

void CMMLibDoc::OnUpdateViewPrev(CCmdUI* pCmdUI) 
{
	CItem *pItem;
	BOOL bEnable = FALSE;

	if ((pItem = GetSelectedItem()) != NULL) {
		HTREEITEM hItem = g_pTreeCtrl->GetPrevSiblingItem(pItem->m_hItem);
		if (hItem != NULL)
			bEnable = TRUE;
	}

	pCmdUI->Enable(bEnable);
}

void CMMLibDoc::OnViewNext() 
{
	CItem *pItem;

	if ((pItem = GetSelectedItem()) != NULL) {
		HTREEITEM hItem = g_pTreeCtrl->GetNextSiblingItem(pItem->m_hItem);
		if (hItem != NULL)
			UpdateAllViews(NULL, VIEWHINT_SELECTITEM, (CItem *)g_pTreeCtrl->GetItemData(hItem));
	}
}

void CMMLibDoc::OnUpdateViewNext(CCmdUI* pCmdUI) 
{
	CItem *pItem;
	BOOL bEnable = FALSE;

	if ((pItem = GetSelectedItem()) != NULL) {
		HTREEITEM hItem = g_pTreeCtrl->GetNextSiblingItem(pItem->m_hItem);
		if (hItem != NULL)
			bEnable = TRUE;
	}

	pCmdUI->Enable(bEnable);
}

void CMMLibDoc::OnPictAutosize() 
{
	m_nAutoSize = (m_nAutoSize == AUTOSIZE_BOTH ? AUTOSIZE_NONE : AUTOSIZE_BOTH);
	UpdateAllViews(NULL, VIEWHINT_DRAWPICT);
}

void CMMLibDoc::OnUpdatePictAutosize(CCmdUI* pCmdUI) 
{
	pCmdUI->Enable(m_bPictView);
	pCmdUI->SetCheck(m_nAutoSize == AUTOSIZE_BOTH);
}

void CMMLibDoc::OnPictAutosize2() 
{
	m_nAutoSize = (m_nAutoSize == AUTOSIZE_WIDTH ? AUTOSIZE_NONE : AUTOSIZE_WIDTH);
	UpdateAllViews(NULL, VIEWHINT_DRAWPICT);
}

void CMMLibDoc::OnUpdatePictAutosize2(CCmdUI* pCmdUI) 
{
	pCmdUI->Enable(m_bPictView);
	pCmdUI->SetCheck(m_nAutoSize == AUTOSIZE_WIDTH);
}

void CMMLibDoc::OnPictEnlarge() 
{
	m_nAutoSize = AUTOSIZE_NONE;
	UpdateAllViews(NULL, VIEWHINT_ENLARGE);
}

void CMMLibDoc::OnUpdatePictEnlarge(CCmdUI* pCmdUI) 
{
	pCmdUI->Enable(m_bPictView);
}

void CMMLibDoc::OnPictReduce() 
{
	m_nAutoSize = AUTOSIZE_NONE;
	UpdateAllViews(NULL, VIEWHINT_REDUCE);
}

void CMMLibDoc::OnUpdatePictReduce(CCmdUI* pCmdUI) 
{
	pCmdUI->Enable(m_bPictView);
}

void CMMLibDoc::OnPictRightRotate() 
{
	UpdateAllViews(NULL, VIEWHINT_RROTATION);
}

void CMMLibDoc::OnUpdatePictRightRotate(CCmdUI* pCmdUI) 
{
	pCmdUI->Enable(m_bPictView);
}

void CMMLibDoc::OnPictLeftRotate() 
{
	UpdateAllViews(NULL, VIEWHINT_LROTATION);
}

void CMMLibDoc::OnUpdatePictLeftRotate(CCmdUI* pCmdUI) 
{
	pCmdUI->Enable(m_bPictView);
}

int CMMLibDoc::GetSelectedItemType()
{
	CItem *pItem;

	if ((pItem = GetSelectedItem()) == NULL)
		return ITEM_NONE;

	return pItem->m_nItemType;
}

BOOL CMMLibDoc::CheckSelectedItemType(int nItemType)
{
	return (GetSelectedItemType() & nItemType) ? TRUE : FALSE;
}

void CMMLibDoc::OnDetailName() 
{
	InverseDetailDisp(DETAIL_NAME);
	UpdateAllViews(NULL, VIEWHINT_DRAWLIST);
}

void CMMLibDoc::OnUpdateDetailName(CCmdUI* pCmdUI) 
{
	pCmdUI->SetCheck(CheckDetailDisp(DETAIL_NAME));
}

void CMMLibDoc::OnDetailCode() 
{
	InverseDetailDisp(DETAIL_CODE);
	UpdateAllViews(NULL, VIEWHINT_DRAWLIST);
}

void CMMLibDoc::OnUpdateDetailCode(CCmdUI* pCmdUI) 
{
	pCmdUI->SetCheck(CheckDetailDisp(DETAIL_CODE));
}

void CMMLibDoc::OnDetailCdate() 
{
	InverseDetailDisp(DETAIL_CDATE);
	UpdateAllViews(NULL, VIEWHINT_DRAWLIST);
}

void CMMLibDoc::OnUpdateDetailCdate(CCmdUI* pCmdUI) 
{
	pCmdUI->SetCheck(CheckDetailDisp(DETAIL_CDATE));
}

void CMMLibDoc::OnDetailUdate() 
{
	InverseDetailDisp(DETAIL_UDATE);
	UpdateAllViews(NULL, VIEWHINT_DRAWLIST);
}

void CMMLibDoc::OnUpdateDetailUdate(CCmdUI* pCmdUI) 
{
	pCmdUI->SetCheck(CheckDetailDisp(DETAIL_UDATE));
}

void CMMLibDoc::OnDetailCreator() 
{
	InverseDetailDisp(DETAIL_CREATOR);
	UpdateAllViews(NULL, VIEWHINT_DRAWLIST);
}

void CMMLibDoc::OnUpdateDetailCreator(CCmdUI* pCmdUI) 
{
	pCmdUI->SetCheck(CheckDetailDisp(DETAIL_CREATOR));
}

void CMMLibDoc::OnDetailRemark() 
{
	InverseDetailDisp(DETAIL_REMARK);
	UpdateAllViews(NULL, VIEWHINT_DRAWLIST);
}

void CMMLibDoc::OnUpdateDetailRemark(CCmdUI* pCmdUI) 
{
	pCmdUI->SetCheck(CheckDetailDisp(DETAIL_REMARK));
}

void CMMLibDoc::OnDetailKeyword() 
{
	InverseDetailDisp(DETAIL_KEYWORD);
	UpdateAllViews(NULL, VIEWHINT_DRAWLIST);
}

void CMMLibDoc::OnUpdateDetailKeyword(CCmdUI* pCmdUI) 
{
	pCmdUI->SetCheck(CheckDetailDisp(DETAIL_KEYWORD));
}

void CMMLibDoc::OnDetailServer() 
{
	InverseDetailDisp(DETAIL_SERVER);
	UpdateAllViews(NULL, VIEWHINT_DRAWLIST);
}

void CMMLibDoc::OnUpdateDetailServer(CCmdUI* pCmdUI) 
{
	pCmdUI->SetCheck(CheckDetailDisp(DETAIL_SERVER));
}

void CMMLibDoc::OnDetailDirectory() 
{
	InverseDetailDisp(DETAIL_DIRECTORY);
	UpdateAllViews(NULL, VIEWHINT_DRAWLIST);
}

void CMMLibDoc::OnUpdateDetailDirectory(CCmdUI* pCmdUI) 
{
	pCmdUI->SetCheck(CheckDetailDisp(DETAIL_DIRECTORY));
}

void CMMLibDoc::OnDetailFirstdata() 
{
	InverseDetailDisp(DETAIL_FIRSTDATA);
	UpdateAllViews(NULL, VIEWHINT_DRAWLIST);
}

void CMMLibDoc::OnUpdateDetailFirstdata(CCmdUI* pCmdUI) 
{
	pCmdUI->SetCheck(CheckDetailDisp(DETAIL_FIRSTDATA));
}

void CMMLibDoc::InverseDetailDisp(int nDetailDisp)
{
	CItem *pItem;
	int nIndex;

	if ((pItem = GetSelectedItem()) == NULL)
		return;

	switch (pItem->m_nItemType) {
	case ITEM_ROOT:
		nIndex = DETAIL_LIBRARY;
		break;
	case ITEM_FOLDER:
	case ITEM_INBOX:
		if (((CItemFolder *)pItem)->m_nFolderType != FTYPE_DFOLDER)
			nIndex = DETAIL_FOLDER;
		else
			nIndex = DETAIL_DATA;
		break;
	case ITEM_FIND:
		if (((CItemFind *)pItem)->m_nFindType == FIND_TYPE_FOLDER)
			nIndex = DETAIL_FOLDER;
		else
			nIndex = DETAIL_DATA;
		break;
	default:
		return;
	}

	if (nDetailDisp == DETAIL_FIRSTDATA && nIndex != DETAIL_FOLDER && !m_bDetailFlag[nIndex][nDetailDisp])
		return;

	m_bDetailFlag[nIndex][nDetailDisp] = !m_bDetailFlag[nIndex][nDetailDisp];
}

BOOL CMMLibDoc::CheckDetailDisp(int nDetailDisp)
{
	CItem *pItem;
	int nIndex;

	if ((pItem = GetSelectedItem()) == NULL)
		return FALSE;

	switch (pItem->m_nItemType) {
	case ITEM_ROOT:
		nIndex = DETAIL_LIBRARY;
		break;
	case ITEM_FOLDER:
	case ITEM_INBOX:
		if (((CItemFolder *)pItem)->m_nFolderType != FTYPE_DFOLDER)
			nIndex = DETAIL_FOLDER;
		else
			nIndex = DETAIL_DATA;
		break;
	case ITEM_FIND:
		if (((CItemFind *)pItem)->m_nFindType == FIND_TYPE_FOLDER)
			nIndex = DETAIL_FOLDER;
		else
			nIndex = DETAIL_DATA;
		break;
	default:
		return FALSE;
	}

	return m_bDetailFlag[nIndex][nDetailDisp];
}

void CMMLibDoc::OnViewUpfolder() 
{
	HTREEITEM hItem = g_pTreeCtrl->GetSelectedItem();
	if (hItem == NULL)
		return;

	CItem *pItem = (CItem *)g_pTreeCtrl->GetItemData(hItem);

	if ((pItem = (CItem *)pItem->GetParent()) == NULL)
		return;

	UpdateAllViews(NULL, VIEWHINT_SELECTITEM, pItem);
}

void CMMLibDoc::OnUpdateViewUpfolder(CCmdUI* pCmdUI) 
{
	pCmdUI->Enable(!CheckSelectedItemType(ITEM_ROOT));
}

void CMMLibDoc::OnFind() 
{
	NewFindSimple();
}

void CMMLibDoc::NewFindSimple()
{
	CFindSimpleDlg dlg;

	CItem *pItem = GetSelectedItem();
	while (pItem != NULL) {
		if (pItem->m_nItemType == ITEM_FOLDER) {
			if (((CItemFolder *)pItem)->m_nFolderType == FTYPE_LIBRARY) {
				dlg.m_pItemFolder = (CItemFolder *)pItem;
				break;
			}
		} else if (pItem->m_nItemType != ITEM_DATA && pItem->m_nItemType != ITEM_FIND)
			break;

		pItem = pItem->GetParent();
	}

	switch (dlg.DoModal()) {
	case IDCANCEL:
		return;
	case IDC_FIND_EXPERT:
		NewFindExpert();
		return;
	}

	CAddItemFind addItem;

	FINDREC findRec;
	findRec.libraryID = dlg.m_pItemFolder->m_FolderRec.parentFolderID;
	findRec.findType = FIND_TYPE_SIMPLE;
	findRec.folderName = dlg.m_sFindFolder;
	findRec.conditions[0].keyword = dlg.m_sSearchText;

	if (dlg.m_bSave) {
		CFindDB findDB;
		if (findDB.Open(TRUE))
			findDB.AddFind(&findRec);
	}

	addItem.m_pItemFolder = dlg.m_pItemFolder;
	addItem.m_pFindRec = &findRec;
	addItem.m_bSelect = TRUE;
	addItem.m_bSave = dlg.m_bSave;
	UpdateAllViews(NULL, VIEWHINT_ADDITEM, &addItem);
}

void CMMLibDoc::NewFindExpert()
{
	int i;
	CFindDlg dlg;

	CItem *pItem = GetSelectedItem();
	while (pItem != NULL) {
		if (pItem->m_nItemType == ITEM_FOLDER) {
			if (((CItemFolder *)pItem)->m_nFolderType == FTYPE_LIBRARY) {
				dlg.m_pItemFolder = (CItemFolder *)pItem;
				break;
			}
		} else if (pItem->m_nItemType != ITEM_DATA && pItem->m_nItemType != ITEM_FIND)
			break;

		pItem = pItem->GetParent();
	}

	for (i = 0; i < N_FIND_CONDITIONS; i++)
		dlg.m_FindConditions[i].matching = "N";

	if (dlg.DoModal() == IDCANCEL)
		return;

	CAddItemFind addItem;

	FINDREC findRec;
	findRec.libraryID = dlg.m_pItemFolder->m_FolderRec.parentFolderID;
	findRec.findType = (char)dlg.m_nFindType;
	findRec.folderName = dlg.m_sFindFolder;
	for (i = 0; i < N_FIND_CONDITIONS; i++)
		findRec.conditions[i] = dlg.m_FindConditions[i];

	if (dlg.m_bSave) {
		CFindDB findDB;
		if (findDB.Open(TRUE))
			findDB.AddFind(&findRec);
	}

	addItem.m_pItemFolder = dlg.m_pItemFolder;
	addItem.m_pFindRec = &findRec;
	addItem.m_bSelect = TRUE;
	addItem.m_bSave = dlg.m_bSave;
	UpdateAllViews(NULL, VIEWHINT_ADDITEM, &addItem);
}

void CMMLibDoc::OnRefind() 
{
	CItem *pItem;

	for (pItem = GetSelectedItem(); pItem != NULL; pItem = pItem->m_pNextSelected) {
		if (pItem->m_nItemType == ITEM_FIND)
			UpdateAllViews(NULL, VIEWHINT_REFINDITEM, pItem);
	}
}

void CMMLibDoc::OnUpdateRefind(CCmdUI* pCmdUI) 
{
	pCmdUI->Enable(CheckSelectedItemType(ITEM_FIND));
}

BOOL CMMLibDoc::OnOpenDocument(LPCTSTR lpszPathName) 
{
	OpenCurrentLibrary();
	
	return FALSE;
}

void CMMLibDoc::OnCloseDocument() 
{
	HTREEITEM hItem;
	CItem *pItemChild;
	CItemFolder *pItemFolder;
	CItemPending *pItemPending;
	CMMPcsDB *pPcsDB;
	CString entry;
	int n = 0;
	LPCTSTR server, directory;
	LPCTSTR libraryID;
	LPCTSTR title;
	int chType;
	int cacheMode;

	for (hItem = g_pTreeCtrl->GetChildItem(g_pTreeCtrl->GetRootItem()); hItem != NULL; hItem = g_pTreeCtrl->GetNextSiblingItem(hItem)) {
		pItemChild = (CItem *)g_pTreeCtrl->GetItemData(hItem);

		switch (pItemChild->m_nItemType) {
		case ITEM_FOLDER:
			pItemFolder = (CItemFolder *)pItemChild;
			pPcsDB = pItemFolder->m_pPcsDB;

			server = pPcsDB->m_ServerName;
			chType = pPcsDB->m_nChType;
			directory = pPcsDB->m_DirName;
			cacheMode = pPcsDB->m_nCacheMode;
			title = pPcsDB->m_Title;
			libraryID = pPcsDB->m_LibraryID;
			break;
		case ITEM_PENDING:
			pItemPending = (CItemPending *)pItemChild;
			server = pItemPending->m_ServerName;
			chType = pItemPending->m_nChType;
			directory = pItemPending->m_DirName;
			cacheMode = pItemPending->m_nCacheMode;
			title = pItemPending->m_Title;
			libraryID = pItemPending->m_LibraryID;
			break;
		default:
			continue;
		}

		entry.Format("Server%d", n);
		theApp.WriteProfileString(g_SectionLibrary, entry, server);

		entry.Format("ChType%d", n);
		theApp.WriteProfileInt(g_SectionLibrary, entry, chType);

		entry.Format("Directory%d", n);
		theApp.WriteProfileString(g_SectionLibrary, entry, directory);

		entry.Format("CacheMode%d", n);
		theApp.WriteProfileInt(g_SectionLibrary, entry, (cacheMode == CACHEMODE_OFFLINE ? CACHEMODE_CACHE : cacheMode));

		entry.Format("Title%d", n);
		theApp.WriteProfileString(g_SectionLibrary, entry, title);

		entry.Format("LibraryID%d", n);
		theApp.WriteProfileString(g_SectionLibrary, entry, libraryID);

		n++;
	}

	theApp.WriteProfileInt(g_SectionLibrary, "Num", n);

	CDocument::OnCloseDocument();
}

BOOL CMMLibDoc::Offlixxxheck(CItem *pItem)
{
	switch (pItem->m_nItemType) {
	case ITEM_FOLDER:
		if (((CItemFolder *)pItem)->m_pPcsDB->m_nCacheMode == CACHEMODE_OFFLINE) {
			AfxMessageBox(IDS_OFFLINE_FOLDER_UPDATE);
			return FALSE;
		}
		break;
	case ITEM_DATA:
		if (((CItemData *)pItem)->m_pPcsDB->m_nCacheMode == CACHEMODE_OFFLINE) {
			AfxMessageBox(IDS_OFFLINE_DATA_UPDATE);
			return FALSE;
		}
		break;
	case ITEM_INBOX:
		break;
	default:
		return FALSE;
	}

	return TRUE;	
}

void CMMLibDoc::OnCacheRead() 
{
	CItem *pItem, *pNextSelected;
	CItemFolder *pItemFolder;

	if (AfxMessageBox(IDS_READ_CACHE, MB_ICONQUESTION | MB_OKCANCEL) == IDCANCEL)
		return;

	CWaitCursor wait;

	for (pItem = GetSelectedItem(); pItem != NULL; pItem = pNextSelected) {
		pNextSelected = pItem->m_pNextSelected;

		switch (pItem->m_nItemType) {
		case ITEM_FOLDER:
			pItemFolder = (CItemFolder *)pItem;
			pItemFolder->m_pPcsDB->FillCacheEx(pItemFolder->m_FolderRec.folderID);
			break;
		}
	}
}

void CMMLibDoc::OnUpdateCacheRead(CCmdUI* pCmdUI) 
{
	CItem *pItem;
	BOOL bEnable = FALSE;

	if ((pItem = GetSelectedItem()) != NULL) {
		if (pItem->m_nItemType == ITEM_FOLDER) {
			if (((CItemFolder *)pItem)->m_pPcsDB->m_nCacheMode == CACHEMODE_CACHE)
				bEnable = TRUE;
		}
	}
	pCmdUI->Enable(bEnable);
}

void CMMLibDoc::OnCacheClear() 
{
	CItem *pItem, *pNextSelected;

	if (AfxMessageBox(IDS_DELETE_CACHE, MB_ICONQUESTION | MB_OKCANCEL) == IDCANCEL)
		return;

	CWaitCursor wait;

	for (pItem = GetSelectedItem(); pItem != NULL; pItem = pNextSelected) {
		pNextSelected = pItem->m_pNextSelected;

		switch (pItem->m_nItemType) {
		case ITEM_FOLDER:
			((CItemFolder *)pItem)->m_pPcsDB->ClearCacheEx();
			break;
		}
	}
}

void CMMLibDoc::OnUpdateCacheClear(CCmdUI* pCmdUI) 
{
	CItem *pItem;
	BOOL bEnable = FALSE;

	if ((pItem = GetSelectedItem()) != NULL) {
		if (pItem->m_nItemType == ITEM_FOLDER) {
			if (((CItemFolder *)pItem)->m_pPcsDB->m_nCacheMode == CACHEMODE_CACHE)
				bEnable = TRUE;
		}
	}
	pCmdUI->Enable(bEnable);
}

void CMMLibDoc::OnOption() 
{
	COptProperty dlg(IDS_SET_ENVIRONMENT, AfxGetMainWnd(), 0);
	if (dlg.DoModal() == IDCANCEL)
		return;

	if (dlg.m_OptFont.m_bChangeViewFont)
		UpdateAllViews(NULL, VIEWHINT_SETFONT);

	if (dlg.m_OptOrder.m_bChangeOrder)
		UpdateAllViews(NULL, VIEWHINT_SORTITEM);

	if (dlg.m_OptOrder.m_bChangeText)
		UpdateAllViews(NULL, VIEWHINT_SETTEXT);

	if (dlg.m_OptOrder.m_bChangeSmallSize) {
		UpdateAllViews(NULL, VIEWHINT_RELOADITEM);
		UpdateAllViews(NULL, VIEWHINT_SETSMALLSIZE);
	}
}

void CMMLibDoc::OnFileSendMail() 
{
	CString fileName;
	CMailAttDlg dlg;

	if (dlg.DoModal() == IDCANCEL)
		return;

	if (!MakeTempFile(GetSelectedItem(), fileName, PICTFILE_ALL, TRUE)) {
		AfxMessageBox(IDS_SEND_DATA_FOLDER);
		return;
	}

	if (fileName.IsEmpty()) {
		AfxMessageBox(IDS_NO_IMAGE_DATA);
		return;
	}

	SendDocuments(fileName);
}

void CMMLibDoc::OnUpdateFileSendMail(CCmdUI* pCmdUI) 
{
	BOOL bEnable = FALSE;
	CItem *pItem;

	if ((pItem = GetSelectedItem()) != NULL) {
		switch (pItem->m_nItemType) {
		case ITEM_FOLDER:
			if (((CItemFolder *)pItem)->m_nFolderType == FTYPE_DFOLDER)
				bEnable = TRUE;
			break;
		case ITEM_FIND:
			if (((CItemFind *)pItem)->m_nFindType != FIND_TYPE_FOLDER)
				bEnable = TRUE;
			break;
		case ITEM_DATA:
			bEnable = TRUE;
			break;
		}
	}

	pCmdUI->Enable(bEnable);
}

void CMMLibDoc::OnEditSelectAll() 
{
	UpdateAllViews(NULL, VIEWHINT_SELECTALL);
}

void CMMLibDoc::OnFileSave() 
{
}

void CMMLibDoc::OnUpdateFileSave(CCmdUI* pCmdUI) 
{
	pCmdUI->Enable(FALSE);
}

void CMMLibDoc::OnSelchangeCombo() 
{
	CComboBox &comboBox = ((CMainFrame *)AfxGetMainWnd())->GetComboBox();

	int n = comboBox.GetCurSel();
	if (n != LB_ERR) {
		CString str;

		comboBox.GetLBText(n, str);
		comboBox.SetWindowText(str);
		m_nAutoSize = AUTOSIZE_NONE;
		UpdateAllViews(NULL, VIEWHINT_SETPICTRATIO);
	}
}

void CMMLibDoc::OnEditchangeCombo() 
{
	CComboBox &comboBox = ((CMainFrame *)AfxGetMainWnd())->GetComboBox();
	CString str1, str2;

	comboBox.GetWindowText(str1);
	str2 = str1.SpanIncluding("0123456789%");
	if (str1.GetLength() != str2.GetLength()) {
		comboBox.SetWindowText(str2);
		comboBox.SetEditSel(str2.GetLength(), str2.GetLength());
	}
}

void CMMLibDoc::OnUpdateCombo(CCmdUI* pCmdUI) 
{
	pCmdUI->Enable(m_bPictView);
	if (!m_bPictView) {
		CComboBox &comboBox = ((CMainFrame *)AfxGetMainWnd())->GetComboBox();
		comboBox.SetEditSel(-1, -1);
	}
}

BOOL CMMLibDoc::OnCmdMsg(UINT nID, int nCode, void* pExtra, AFX_CMDHANDLERINFO* pHandlerInfo) 
{
	if (nID == 1 && nCode == 0) {
		m_nAutoSize = AUTOSIZE_NONE;
		UpdateAllViews(NULL, VIEWHINT_SETPICTRATIO);
	}
	
	// pHandlerInfo が NULL の場合、メッセージを処理します。
	if (pHandlerInfo == NULL)
	{
		CMainFrame *pMainWnd = (CMainFrame *)AfxGetMainWnd();
		int i;

		// フィルター コマンドはテキスト カラー メニュー オプションへ送られます。
		for (i = 0; i < pMainWnd->m_nTransMenu; i++) {
			if (nID == (UINT)(IDM_TRANS_APP + i)) {
				if (nCode == CN_COMMAND) {
					// WM_COMMAND メッセージを処理します。
					OnTransApp(i);
				} else if (nCode == CN_UPDATE_COMMAND_UI) {
					// UI 要素の状態を更新します。
					((CCmdUI*)pExtra)->Enable(TRUE);
				}
				return TRUE;
			}
		}
	}

	return CDocument::OnCmdMsg(nID, nCode, pExtra, pHandlerInfo);
}

void CMMLibDoc::OnFilePack() 
{
	CItem *pItem;

	if ((pItem = GetSelectedItem()) == NULL)
		return;

	if (pItem->m_nItemType == ITEM_FOLDER || pItem->m_nItemType == ITEM_INBOX) {
		if (AfxMessageBox(IDS_FILE_PACK, MB_OKCANCEL | MB_ICONQUESTION) == IDCANCEL)
			return;

		CWaitCursor wait;

		if (((CItemFolder *)pItem)->m_pPcsDB->PackFileEx()) {
			wait.Restore();
			AfxMessageBox(IDS_FILE_PACK_END);
		}
	}
}

void CMMLibDoc::OnUpdateFilePack(CCmdUI* pCmdUI) 
{
	pCmdUI->Enable(CheckSelectedItemType(ITEM_FOLDER | ITEM_INBOX));
}

void CMMLibDoc::OnFileExport() 
{
	CWaitCursor wait;
	CItem *pItem;

	pItem = GetSelectedItem();

	if (pItem == NULL) {
		AfxMessageBox(IDS_SELECT_EXPORT);
		return;
	}

	for ( ; pItem != NULL; pItem = pItem->m_pNextSelected) {
		switch (pItem->m_nItemType) {
		case ITEM_FOLDER:
			if (((CItemFolder *)pItem)->m_nFolderType != FTYPE_DFOLDER)
				goto error;
			ExportMmlFile(pItem);
			break;
		case ITEM_FIND:
			if (((CItemFind *)pItem)->m_nFindType == FIND_TYPE_FOLDER)
				goto error;
			ExportMmlFile(pItem);
			break;
		case ITEM_DATA:
			ExportData((CItemData *)pItem);
			break;
		default:
		error:
			AfxMessageBox(IDS_EXPORT_DATA_FOLDER);
			return;
		}
	}
}

BOOL CMMLibDoc::ExportData(CItemData *pItemData)
{
	HDIB hDIB;
	CString fileName;
	CString ext, ext2;
	CString filter;

	if (!pItemData->m_pPcsDB->ReadPictureDataEx(pItemData->m_DataRec.dataID, &hDIB, NULL))
		return FALSE;

	fileName = pItemData->m_DataRec.title;
	CheckFileName(fileName);

	switch (GetPictureBitCount(hDIB)) {
	case 0:
		return FALSE;
	case 1:
	case 8:
		ext = "png";
		break;
	case 24:
		ext = "jpg";
		break;
	default:
		ext = "bmp";
		break;
	}

	ext2 = "." + ext;
	if (ext2.CompareNoCase(fileName.Right(4)) != 0)
		fileName += ext2;
	filter = GetString(IDS_PICTURE_FILE) + " (*.bmp;*.jpg;*.png)|*.bmp;*.jpg;*.png||";

	CFileDialog dlg(FALSE, ext, fileName, OFN_OVERWRITEPROMPT | OFN_HIDEREADONLY, filter, AfxGetMainWnd());

	dlg.m_ofn.lpstrTitle = GetString(IDS_EXPORT_FILE);
	if (dlg.DoModal() == IDOK) {
		if (MMPictureWriteFile(hDIB, (LPTSTR)(LPCTSTR)dlg.GetPathName()) != 0) {
			::GlobalFree(hDIB);
			return FALSE;
		}
	}

	::GlobalFree(hDIB);

	return TRUE;
}

BOOL CMMLibDoc::ExportMmlFile(CItem *pItem)
{
	CString fileName;
	CString filter;

	switch (pItem->m_nItemType) {
	case ITEM_FOLDER:
		fileName = ((CItemFolder *)pItem)->m_FolderRec.folderName;
		break;
	case ITEM_FIND:
		fileName = ((CItemFind *)pItem)->m_FindRec.folderName;
		break;
	default:
		return FALSE;
	}

	CheckFileName(fileName);
	fileName += ".mml";
	filter = GetString(IDS_MML_FILE) + " (*.mml)|*.mml||";

	CFileDialog dlg(FALSE, "mml", fileName, OFN_OVERWRITEPROMPT | OFN_HIDEREADONLY, filter, AfxGetMainWnd());

	dlg.m_ofn.lpstrTitle = GetString(IDS_EXPORT_FILE);
	if (dlg.DoModal() == IDOK) {
		if (!MakeMmlFile(pItem, dlg.GetPathName(), FALSE))
			return FALSE;
	}

	return TRUE;
}

void CMMLibDoc::OnUpdateFileExport(CCmdUI* pCmdUI) 
{
	BOOL bEnable = FALSE;
	CItem *pItem;

	if ((pItem = GetSelectedItem()) != NULL) {
		switch (pItem->m_nItemType) {
		case ITEM_FOLDER:
			if (((CItemFolder *)pItem)->m_nFolderType == FTYPE_DFOLDER)
				bEnable = TRUE;
			break;
		case ITEM_FIND:
			if (((CItemFind *)pItem)->m_nFindType != FIND_TYPE_FOLDER)
				bEnable = TRUE;
			break;
		case ITEM_DATA:
			bEnable = TRUE;
			break;
		}
	}

	pCmdUI->Enable(bEnable);
}

void CMMLibDoc::OnImportBmp() 
{
	ImportFile(GetString(IDS_PICTURE_FILE) + " (*.bmp;*.jpg;*.png)|*.bmp;*.jpg;*.png|" + GetString(IDS_MML_FILE, 1) + " (*.mml)|*.mml||");
}

void CMMLibDoc::OnUpdateImportBmp(CCmdUI* pCmdUI) 
{
	pCmdUI->Enable(CheckSelectedItemType(ITEM_FOLDER | ITEM_INBOX));
}

void CMMLibDoc::OnImportMml() 
{
	ImportFile(GetString(IDS_MML_FILE) + " (*.mml)|*.mml|" + GetString(IDS_PICTURE_FILE, 1) + " (*.bmp;*.jpg;*.png)|*.bmp;*.jpg;*.png||");
}

void CMMLibDoc::OnUpdateImportMml(CCmdUI* pCmdUI) 
{
	pCmdUI->Enable(CheckSelectedItemType(ITEM_FOLDER | ITEM_INBOX));
}

void CMMLibDoc::OnImportPpm() 
{
	CItem *pItem;

	pItem = GetSelectedItem();

	if (pItem == NULL || (pItem->m_nItemType != ITEM_FOLDER && pItem->m_nItemType != ITEM_INBOX)) {
		AfxMessageBox(IDS_SELECT_IMPORT_FOLDER);
		return;
	}

	if (!Offlixxxheck(pItem))
		return;

	CFileDialog dlg(TRUE, NULL, "docu*", OFN_HIDEREADONLY | OFN_NOVALIDATE, GetString(IDS_PRESTO_PAGEMANAGER_FOLDER) + "|||", AfxGetMainWnd());

	dlg.m_ofn.lpstrTitle = GetString(IDS_IMPORT_FOLDER);
	if (dlg.DoModal() == IDOK) {
		ImportPpmFile(dlg.GetPathName(), (CItemFolder *)pItem);
	}
}

void CMMLibDoc::OnUpdateImportPpm(CCmdUI* pCmdUI) 
{
	pCmdUI->Enable(CheckSelectedItemType(ITEM_FOLDER | ITEM_INBOX));
}

void CMMLibDoc::ImportFile(LPCTSTR pFilter)
{
	CItem *pItem;
	CItemFolder *pItemFolder;

	pItem = GetSelectedItem();

	if (pItem == NULL) {
		AfxMessageBox(IDS_SELECT_IMPORT_FOLDER);
		return;
	}

	if (pItem->m_nItemType != ITEM_FOLDER && pItem->m_nItemType != ITEM_INBOX)
		return;

	if (!Offlixxxheck(pItem))
		return;

	pItemFolder = (CItemFolder *)pItem;

	CFileDialog dlg(TRUE, NULL, NULL, OFN_HIDEREADONLY, pFilter, AfxGetMainWnd());

	dlg.m_ofn.lpstrTitle = GetString(IDS_IMPORT_FILE);
	if (dlg.DoModal() == IDOK) {
		int nFileType = ::CheckFileType(dlg.GetFileName());
		switch (nFileType) {
		case SRCFILETYPE_MML:
			ImportMmlFile(dlg.GetPathName(), pItemFolder);
			break;
		case SRCFILETYPE_PICT:
			if (pItemFolder->m_nFolderType == FTYPE_DFOLDER)
				ImportPictFile(&dlg.GetPathName(), 1, pItemFolder);
			else
				AfxMessageBox(IDS_PICT_FILE_IMPORT);
			break;
		}
	}
}

void CMMLibDoc::ImportPictFile(CString *pFileName, int nFileName, CItemFolder *pItemFolder)
{
	if (!Offlixxxheck(pItemFolder))
		return;

	CDataDlg	dlg;

//	if (MMPictureReadFile(&dlg.m_hDIB, (LPSTR)pFileName) != 0)
//		return;

	((CFrameWnd *)AfxGetMainWnd())->ActivateFrame();

//	CString sTitle = pFileName;
//	sTitle = RemoveDir(sTitle);
//	sTitle = RemoveExt(sTitle);

	dlg.m_pItemFolder = pItemFolder;
	dlg.m_pDoc = this;
	dlg.m_Caption.LoadString(IDS_NEW_DATA2);
//	dlg.m_sTitle = sTitle;
	dlg.m_pReadFileName = pFileName;
	dlg.m_nReadFileName = nFileName;

	dlg.DoModal();
}

void CMMLibDoc::ImportMmlFile(LPCTSTR pFileName, CItemFolder *pItemFolder)
{
	CMmlRead mml;
	FOLDERREC folderRec;
	int nPage;
	HDIB hDIB;
	HDIB hDIB2;
	DATAREC dataRec;
	CString text;
	CAddItemFolder addItemFolder;
	CAddItemData addItemData;
	CWaitCursor wait;

	if (!Offlixxxheck(pItemFolder))
		return;

	if (!mml.Open(pFileName))
		return;

	switch (pItemFolder->m_nFolderType) {
	case FTYPE_LIBRARY:
	case FTYPE_GFOLDER:
		if (!ReadMmlHeader(&mml, &folderRec, &text))
			return;

		folderRec.parentFolderID = pItemFolder->m_FolderRec.folderID;
		folderRec.folderType = FTYPE_DFOLDER;

		if (!pItemFolder->m_pPcsDB->AddFolderEx(&folderRec))
			return;

		if (text.GetLength() != 0)
			pItemFolder->m_pPcsDB->WriteTextFolderEx(folderRec.folderID, &text);

		UpdateAllViews(NULL, VIEWHINT_SELECTITEM, pItemFolder);

		addItemFolder.m_pItemFolder = pItemFolder;
		addItemFolder.m_pFolderRec = &folderRec;
		UpdateAllViews(NULL, VIEWHINT_ADDITEM, &addItemFolder);
		pItemFolder = addItemFolder.m_pItemAddFolder;
		if (pItemFolder == NULL)
			return;
		break;
	case FTYPE_DFOLDER:
		break;
	}

	nPage = 0;
	hDIB = hDIB2 = NULL;
	while (ReadMmlData(++nPage, &mml, &dataRec, &hDIB, &text)) {
		dataRec.folderID = pItemFolder->m_FolderRec.folderID;
		if (!pItemFolder->m_pPcsDB->AddDataEx(&dataRec))
			break;

		if (hDIB != NULL) {
			hDIB2 = MakeSmallPicture(hDIB, PICT_WIDTH, PICT_HEIGHT);
			pItemFolder->m_pPcsDB->WritePictureDataEx(dataRec.dataID, hDIB, hDIB2, GetMMType(hDIB));
		}

		addItemData.m_pItemFolder = pItemFolder;
		addItemData.m_pDataRec = &dataRec;
		UpdateAllViews(NULL, VIEWHINT_ADDITEM, &addItemData);

		if (hDIB != NULL) {
			::GlobalFree(hDIB);
			hDIB = NULL;
		}
		if (hDIB2 != NULL) {
			::GlobalFree(hDIB2);
			hDIB2 = NULL;
		}

		if (text.GetLength() != 0)
			pItemFolder->m_pPcsDB->WriteTextDataEx(dataRec.dataID, &text);
	}
}

void CMMLibDoc::ImportPpmFile(LPCTSTR pFileName, CItemFolder *pItemFolder)
{
	HDIB hDIB;
	HDIB hDIB2;
	DATAREC dataRec;
	CAddItemData addItemData;
	CString creator;
	CWaitCursor wait;
	HANDLE	hdir1, hdir2;
	WIN32_FIND_DATA	findBuf;
	CString dir;
	CString path;
	CString name, date;
	CString folderID;
	int n;

	if (!Offlixxxheck(pItemFolder))
		return;

	DWORD userNameSize = MAXUSERNAME;
	GetUserName(creator.GetBuffer(MAXUSERNAME), &userNameSize);
	creator.ReleaseBuffer();

	dir = pFileName;
	if ((n = dir.ReverseFind('\\')) != -1)
		dir = dir.Left(n);		

	hdir1 = FindFirstFile(pFileName, &findBuf);
	if (hdir1 != INVALID_HANDLE_VALUE) {
		do {
			if (findBuf.dwFileAttributes & FILE_ATTRIBUTE_DIRECTORY) {
				if (strcmp(findBuf.cFileName, ".") != 0 && strcmp(findBuf.cFileName, "..") != 0) {
					path = dir + "\\" + findBuf.cFileName;
					name = findBuf.cFileName;
					date = ConvFileTime(&findBuf.ftLastWriteTime);

					if (pItemFolder->m_nFolderType == FTYPE_DFOLDER)
						folderID = pItemFolder->GetID();
					else
						folderID.Empty();
					hdir2 = FindFirstFile(path + "\\*", &findBuf);
					if (hdir2 != INVALID_HANDLE_VALUE) {
						do {
							if (!(findBuf.dwFileAttributes & FILE_ATTRIBUTE_DIRECTORY)) {
								hDIB = hDIB2 = NULL;
								if (MMPictureReadFile(&hDIB, (LPSTR)(LPCTSTR)(path + "\\" + findBuf.cFileName)) != 0)
									continue;
								if (hDIB != NULL)
									hDIB2 = MakeSmallPicture(hDIB, PICT_WIDTH, PICT_HEIGHT);
								else
									continue;

								if (folderID.IsEmpty()) {
									folderID = CreateDFolder(pItemFolder, name, creator, date);
									if (folderID.IsEmpty()) {
										if (hDIB != NULL)
											::GlobalFree(hDIB);
										if (hDIB2 != NULL)
											::GlobalFree(hDIB2);
										break;
									}
								}

								if (pItemFolder->m_nFolderType == FTYPE_DFOLDER)
									dataRec.code = name;
								dataRec.createDate = ConvFileTime(&findBuf.ftLastWriteTime);
								dataRec.updateDate = ConvFileTime(&findBuf.ftLastWriteTime);
								dataRec.creator = creator;
								dataRec.title = RemoveExt((CString)findBuf.cFileName);
								dataRec.folderID = folderID;
								if (pItemFolder->m_pPcsDB->AddDataEx(&dataRec))
									pItemFolder->m_pPcsDB->WritePictureDataEx(dataRec.dataID, hDIB, hDIB2, GetMMType(hDIB));

								if (hDIB != NULL)
									::GlobalFree(hDIB);
								if (hDIB2 != NULL)
									::GlobalFree(hDIB2);

								if (pItemFolder->m_nFolderType == FTYPE_DFOLDER) {
									addItemData.m_pItemFolder = pItemFolder;
									addItemData.m_pDataRec = &dataRec;
									UpdateAllViews(NULL, VIEWHINT_ADDITEM, &addItemData);
								}
							}
						} while (FindNextFile(hdir2, &findBuf));
						FindClose(hdir2);
					}
				}
			} else {
				hDIB = hDIB2 = NULL;
				if (MMPictureReadFile(&hDIB, (LPSTR)(LPCTSTR)(dir + "\\" + findBuf.cFileName)) != 0)
					continue;
				if (hDIB != NULL)
					hDIB2 = MakeSmallPicture(hDIB, PICT_WIDTH, PICT_HEIGHT);
				else
					continue;

				name = RemoveExt((CString)findBuf.cFileName);
				date = ConvFileTime(&findBuf.ftLastWriteTime);

				if (pItemFolder->m_nFolderType == FTYPE_DFOLDER) {
					folderID = pItemFolder->GetID();
					dataRec.code = name;
				} else {
					folderID = CreateDFolder(pItemFolder, name, creator, date);
					if (folderID.IsEmpty()) {
						if (hDIB != NULL)
							::GlobalFree(hDIB);
						if (hDIB2 != NULL)
							::GlobalFree(hDIB2);
						continue;
					}
				}

				dataRec.createDate = date;
				dataRec.updateDate = date;
				dataRec.creator = creator;
				dataRec.title.LoadString(IDS_NO_TITLE_1);
				dataRec.folderID = folderID;
				if (pItemFolder->m_pPcsDB->AddDataEx(&dataRec))
					pItemFolder->m_pPcsDB->WritePictureDataEx(dataRec.dataID, hDIB, hDIB2, GetMMType(hDIB));

				if (hDIB != NULL)
					::GlobalFree(hDIB);
				if (hDIB2 != NULL)
					::GlobalFree(hDIB2);

				if (pItemFolder->m_nFolderType == FTYPE_DFOLDER) {
					addItemData.m_pItemFolder = pItemFolder;
					addItemData.m_pDataRec = &dataRec;
					UpdateAllViews(NULL, VIEWHINT_ADDITEM, &addItemData);
				}
			}
		} while (FindNextFile(hdir1, &findBuf));

		FindClose(hdir1);
	}
}

CString CMMLibDoc::CreateDFolder(CItemFolder *pItemFolder, LPCTSTR pName, LPCTSTR pCreator, LPCTSTR pDate)
{
	FOLDERREC folderRec;
	CAddItemFolder addItemFolder;
	CString folderID;

	folderRec.parentFolderID = pItemFolder->m_FolderRec.folderID;
	folderRec.folderType = FTYPE_DFOLDER;
	folderRec.folderName = pName;
	folderRec.createDate = pDate;
	folderRec.updateDate = pDate;
	folderRec.creator = pCreator;
	if (pItemFolder->m_pPcsDB->AddFolderEx(&folderRec)) {
		addItemFolder.m_pItemFolder = pItemFolder;
		addItemFolder.m_pFolderRec = &folderRec;
		UpdateAllViews(NULL, VIEWHINT_ADDITEM, &addItemFolder);
		UpdateAllViews(NULL, VIEWHINT_UPDATEWINDOW);
		folderID = addItemFolder.m_pItemAddFolder->m_FolderRec.folderID;
	}

	return folderID;
}

BOOL CMMLibDoc::ReadMmlHeader(CMmlRead *pMmlRead, FOLDERREC *pFolderRec, CString *pText)
{
	char itemName[11];
	LPVOID itemData;
	long itemLen;

	pText->Empty();

	if (!pMmlRead->SetPage(0))
		return FALSE;

	while (pMmlRead->ReadItem(itemName, &itemData, &itemLen)) {
		if (strcmp(itemName, "FNAME") == 0)
			pFolderRec->folderName = CString((LPCTSTR)itemData, itemLen);
		else if (strcmp(itemName, "CDATE") == 0)
			pFolderRec->createDate = CString((LPCTSTR)itemData, itemLen);
		else if (strcmp(itemName, "UDATE") == 0)
			pFolderRec->updateDate = CString((LPCTSTR)itemData, itemLen);
		else if (strcmp(itemName, "CREATOR") == 0)
			pFolderRec->creator = CString((LPCTSTR)itemData, itemLen);
		else if (strcmp(itemName, "REMARK1") == 0)
			pFolderRec->remark = CString((LPCTSTR)itemData, itemLen);
		else if (strcmp(itemName, "REMARK2") == 0)
			pFolderRec->keyword = CString((LPCTSTR)itemData, itemLen);
		else if (strcmp(itemName, "TEXT") == 0) {
			if (itemLen != 0)
				*pText = CString((LPCTSTR)itemData, itemLen);
		}

		delete itemData;
	}

	return TRUE;
}

BOOL CMMLibDoc::ReadMmlData(int nPage, CMmlRead *pMmlRead, DATAREC *pDataRec, HDIB *phDIB, CString *pText)
{
	char itemName[11];
	LPVOID itemData;
	long itemLen;

	*phDIB = NULL;
	pText->Empty();

	if (!pMmlRead->SetPage(nPage))
		return FALSE;

	while (pMmlRead->ReadItem(itemName, &itemData, &itemLen)) {
		if (strcmp(itemName, "CDATE") == 0)
			pDataRec->createDate = CString((LPCTSTR)itemData, itemLen);
		else if (strcmp(itemName, "UDATE") == 0)
			pDataRec->updateDate = CString((LPCTSTR)itemData, itemLen);
		else if (strcmp(itemName, "CREATOR") == 0)
			pDataRec->creator = CString((LPCTSTR)itemData, itemLen);
		else if (strcmp(itemName, "CODE") == 0)
			pDataRec->code = CString((LPCTSTR)itemData, itemLen);
		else if (strcmp(itemName, "TITLE") == 0)
			pDataRec->title = CString((LPCTSTR)itemData, itemLen);
		else if (strcmp(itemName, "REMARK1") == 0)
			pDataRec->remark = CString((LPCTSTR)itemData, itemLen);
		else if (strcmp(itemName, "REMARK2") == 0)
			pDataRec->keyword = CString((LPCTSTR)itemData, itemLen);
		else if (strcmp(itemName, "PICT1") == 0) {
			if (itemLen != 0)
				MMPictureReadMem(phDIB, itemData, itemLen);
		} else if (strcmp(itemName, "TEXT") == 0) {
			if (itemLen != 0)
				*pText = CString((LPCTSTR)itemData, itemLen);
		}
		delete itemData;
	}

	return TRUE;
}

void CMMLibDoc::OnBackup() 
{
	CBackupDlg dlg;

	CItem *pItem = GetSelectedItem();
	while (pItem != NULL) {
		if (pItem->m_nItemType == ITEM_FOLDER) {
			if (((CItemFolder *)pItem)->m_nFolderType == FTYPE_LIBRARY) {
				dlg.m_pItemFolder = (CItemFolder *)pItem;
				break;
			}
		} else if (pItem->m_nItemType != ITEM_DATA && pItem->m_nItemType != ITEM_FIND)
			break;

		pItem = pItem->GetParent();
	}

	dlg.DoModal();
}

void CMMLibDoc::OnRestore() 
{
	CRestoreDlg dlg;

	dlg.DoModal();
}

void CMMLibDoc::OnTransReg() 
{
	CTransRegDlg dlg;

	if (dlg.DoModal() == IDOK) {
		CMainFrame *pMainWnd = (CMainFrame *)AfxGetMainWnd();
		pMainWnd->RefreshTransMenu();
	}
}

void CMMLibDoc::OnTransApp(int nIndex)
{
	CMainFrame *pMainWnd = (CMainFrame *)AfxGetMainWnd();
	CWaitCursor wait;
	CItem *pItem;
	CString fileName;

	pItem = GetSelectedItem();

	if (pItem == NULL) {
		AfxMessageBox(IDS_SELECT_TRANSFER_DATA);
		return;
	}

	for ( ; pItem != NULL; pItem = pItem->m_pNextSelected) {
		switch (pItem->m_nItemType) {
		case ITEM_DATA:
			break;
		default:
			AfxMessageBox(IDS_STANSFER_DATA);
			return;
		}
	}

	if (!MakeTempFile(GetSelectedItem(), fileName, pMainWnd->m_pTransMenu[nIndex].nSupportFile, FALSE, FALSE))
		return;

	if (fileName.IsEmpty()) {
		AfxMessageBox(IDS_NO_IMAGE_DATA);
		return;
	}

	m_sExecFile = pMainWnd->m_pTransMenu[nIndex].fileName;
	m_sTempFile = fileName;

	::AfxBeginThread(ExecApp, this, THREAD_PRIORITY_NORMAL);
}

UINT CMMLibDoc::ExecApp(LPVOID pParam)
{
	CMMLibDoc *pDoc = (CMMLibDoc *)pParam;
	CString sTempFile;
	CString execCmd;

	sTempFile = pDoc->m_sTempFile;
	sTempFile.Replace(';', ' ');
	sTempFile.TrimRight();
	execCmd.Format("\"%s\" %s", (LPCTSTR)pDoc->m_sExecFile, (LPCTSTR)sTempFile);

	ExecProcess(execCmd);

	DeleteTempFile(pDoc->m_sTempFile);

	return 0;
}

void CMMLibDoc::OnPictAllRotate() 
{
	CItem *pItem, *pNextSelected;

	if ((pItem = GetSelectedItem()) == NULL)
		return;

	if (!Offlixxxheck(pItem))
		return;

	CPictRotateDlg dlg;
	if (dlg.DoModal() == IDCANCEL)
		return;

	CWaitCursor wait;

	for ( ; pItem != NULL; pItem = pNextSelected) {
		pNextSelected = pItem->m_pNextSelected;

		switch (pItem->m_nItemType) {
		case ITEM_FOLDER:
		case ITEM_INBOX:
			((CItemFolder *)pItem)->m_pPcsDB->RotateFolderEx(pItem->GetID(), dlg.m_nRotate);
			break;
		case ITEM_DATA:
			((CItemData *)pItem)->m_pPcsDB->RotateDataEx(pItem->GetID(), dlg.m_nRotate);
			break;
		}
	}

	UpdateAllViews(NULL, VIEWHINT_RELOADITEM);
}

void CMMLibDoc::OnUpdatePictAllRotate(CCmdUI* pCmdUI) 
{
	pCmdUI->Enable(CheckSelectedItemType(ITEM_FOLDER | ITEM_DATA | ITEM_INBOX));
}

void CMMLibDoc::OnPictWindow() 
{
	UpdateAllViews(NULL, VIEWHINT_PICTFRAME);
}

void CMMLibDoc::OnUpdatePictWindow(CCmdUI* pCmdUI) 
{
	pCmdUI->Enable(m_bPictView);
}

void CMMLibDoc::OnFilePassword() 
{
	CPasswordDlg dlg;

	CItem *pItem = GetSelectedItem();
	while (pItem != NULL) {
		if (pItem->m_nItemType == ITEM_FOLDER) {
			if (((CItemFolder *)pItem)->m_nFolderType == FTYPE_LIBRARY) {
				dlg.m_pItemFolder = (CItemFolder *)pItem;
				break;
			}
		} else if (pItem->m_nItemType != ITEM_DATA && pItem->m_nItemType != ITEM_FIND)
			break;

		pItem = pItem->GetParent();
	}

	if (dlg.DoModal() == IDOK) {
		dlg.m_pItemFolder->m_pPcsDB->SetPassword(dlg.m_sPassword);
	}
}

void CMMLibDoc::OnFilePermission() 
{
	CPermissionDlg dlg;

	CItem *pItem = GetSelectedItem();
	while (pItem != NULL) {
		if (pItem->m_nItemType == ITEM_FOLDER) {
			if (((CItemFolder *)pItem)->m_nFolderType == FTYPE_LIBRARY) {
				dlg.m_pItemFolder = (CItemFolder *)pItem;
				break;
			}
		} else if (pItem->m_nItemType != ITEM_DATA && pItem->m_nItemType != ITEM_FIND)
			break;

		pItem = pItem->GetParent();
	}

	if (dlg.DoModal() == IDOK) {
		dlg.m_pItemFolder->m_pPcsDB->SetPermission(dlg.m_dwPermission);
	}
}

void CMMLibDoc::OnEditCopy() 
{
	if (CopyToClipboard())
		m_bEditCut = FALSE;
}

void CMMLibDoc::OnUpdateEditCopy(CCmdUI* pCmdUI) 
{
	pCmdUI->Enable(CheckSelectedItemType(ITEM_FOLDER | ITEM_DATA));
}

void CMMLibDoc::OnEditCut() 
{
	if (CopyToClipboard())
		m_bEditCut = TRUE;
}

void CMMLibDoc::OnUpdateEditCut(CCmdUI* pCmdUI) 
{
	pCmdUI->Enable(CheckSelectedItemType(ITEM_FOLDER | ITEM_DATA));
}

void CMMLibDoc::OnEditPaste() 
{
	HANDLE hMem;
	CItem *pItemClip;
	CItem *pItemSelect;

	if (AfxGetMainWnd()->OpenClipboard()) {
		if ((hMem = ::GetClipboardData(g_nCFMMLibrary)) != NULL) {
			pItemClip = *(CItem **)::GlobalLock(hMem);
			if ((pItemSelect = GetSelectedItem()) != NULL) {
				if (m_bEditCut)
					MoveItem(pItemClip, pItemSelect);
				else
					CopyItem(pItemClip, pItemSelect);
			}
			::GlobalUnlock(hMem);
		}
	}
}

void CMMLibDoc::OnUpdateEditPaste(CCmdUI* pCmdUI) 
{
	HANDLE hMem;
	CItem *pItemClip;
	CItem *pItemSelect;
	BOOL bEnable = FALSE;

	if (AfxGetMainWnd()->OpenClipboard()) {
		if ((hMem = ::GetClipboardData(g_nCFMMLibrary)) != NULL) {
			pItemClip = *(CItem **)::GlobalLock(hMem);
			if ((pItemSelect = GetSelectedItem()) != NULL) {
				if (pItemClip != pItemSelect && pItemClip->GetParent() != pItemSelect && !CheckParent(pItemClip, pItemSelect)) {
					if (pItemSelect->m_nItemType == ITEM_FOLDER || pItemSelect->m_nItemType == ITEM_INBOX) {
						switch (((CItemFolder *)pItemSelect)->m_nFolderType) {
						case FTYPE_LIBRARY:
						case FTYPE_GFOLDER:
							if (pItemClip->m_nItemType == ITEM_FOLDER)
								bEnable = TRUE;
							break;
						case FTYPE_DFOLDER:
							if (pItemClip->m_nItemType == ITEM_DATA)
								bEnable = TRUE;
							break;
						}
					}
				}
			}
			::GlobalUnlock(hMem);
		}
		::CloseClipboard();
	}

	pCmdUI->Enable(bEnable);
}

BOOL CMMLibDoc::CopyToClipboard()
{
	CItem *pItem;

	if ((pItem = GetSelectedItem()) != NULL) {
		HANDLE hMem = ::GlobalAlloc(GMEM_DDESHARE, sizeof(CItem *));
		CItem **ppItem = (CItem **)::GlobalLock(hMem);
		*ppItem = pItem;
		::GlobalUnlock(hMem);
		if (AfxGetApp()->m_pMainWnd->OpenClipboard()) {
			BeginWaitCursor();
			::EmptyClipboard();
			::SetClipboardData(g_nCFMMLibrary, hMem);
			::CloseClipboard();
			EndWaitCursor();
			return TRUE;
		}
	}
	return FALSE;
}

BOOL CMMLibDoc::SaveModified() 
{
	return TRUE;
}

void CMMLibDoc::OnNewData() 
{
	if (g_pItemInbox == NULL)
		return;

	CMMPcsDB *pPcsDB = g_pItemInbox->m_pPcsDB;
	FOLDERREC folderRec;
	DATAREC dataRec;
	STIME stime;

	pPcsDB->m_pPcserve->m_Pcserve.GetTime(&stime);

	folderRec.parentFolderID = g_pItemInbox->m_FolderRec.folderID;
	folderRec.folderType = FTYPE_DFOLDER;
	folderRec.folderName.Format("Inbox%04d%02d%02d%02d%02d%02d", stime.year, stime.month, stime.day, stime.hour, stime.minute, stime.second);
	folderRec.creator = GetDefaultUserName();
	folderRec.codeDef = "00000010";
	folderRec.codeStep = 10;

	UpdateAllViews(NULL, VIEWHINT_OPENITEM, g_pItemInbox);
	if (pPcsDB->AddFolderEx(&folderRec)) {
		CAddItemFolder addItem;
		addItem.m_pItemFolder = g_pItemInbox;
		addItem.m_pFolderRec = &folderRec;
		UpdateAllViews(NULL, VIEWHINT_ADDITEM, &addItem);
		UpdateAllViews(NULL, VIEWHINT_SELECTITEM, addItem.m_pItemAddFolder);

		CreateData(addItem.m_pItemAddFolder);

		if (!pPcsDB->ReadChildDataEx(folderRec.folderID, &dataRec, TRUE))
			DeleteFolder(addItem.m_pItemAddFolder);
	}
}

void CMMLibDoc::OnViewMax() 
{
	CMainFrame *pMainWnd = (CMainFrame *)AfxGetMainWnd();

	m_bMaxPict = !m_bMaxPict;
	pMainWnd->MaxPictView(m_bMaxPict);
}

void CMMLibDoc::OnUpdateViewMax(CCmdUI* pCmdUI) 
{
	pCmdUI->SetCheck(m_bMaxPict);
}

void CMMLibDoc::OnKeyword() 
{
	CMMLibApp *pWinApp = (CMMLibApp *)AfxGetApp();
	CDocTemplate *pTemplate = pWinApp->m_pFindDocTemplate;
	CRuntimeClass* pDocClass = RUNTIME_CLASS(CFindDoc);
	CFindDoc *pFindDoc = (CFindDoc *)(pDocClass->CreateObject());
	CFindFrame *pFrame = (CFindFrame *)pTemplate->CreateNewFrame(pFindDoc, NULL);
	pFindDoc->m_pMMLibDoc = this;
	pFindDoc->m_pFindFrame = (CFindFrame *)pFrame;
	pTemplate->InitialUpdateFrame(pFrame, pFindDoc);
	pFrame->m_wndKeyword.SetFocus();
}

void CMMLibDoc::InsertData(LPCTSTR pFolderTitle, LPCTSTR pFolderComment, LPCTSTR pDataTitle, LPCTSTR pDataComment, HDIB hDIB, LPCTSTR pText)
{
	if (g_pItemInbox == NULL)
		return;

	CMMPcsDB *pPcsDB = g_pItemInbox->m_pPcsDB;
	FOLDERREC folderRec;
	DATAREC dataRec;
	BOOL bFirst = TRUE;
	BOOL bFind = FALSE;
	CItem *pItem;
	CItemFolder *pItemFolder;

	UpdateAllViews(NULL, VIEWHINT_OPENITEM, g_pItemInbox);

	for (HTREEITEM hItem = g_pTreeCtrl->GetChildItem(g_pItemInbox->m_hItem); hItem != NULL; hItem = g_pTreeCtrl->GetNextSiblingItem(hItem)) {
		pItem = (CItem *)g_pTreeCtrl->GetItemData(hItem);
		if (pItem->m_nItemType == ITEM_FOLDER) {
			pItemFolder = (CItemFolder *)pItem;
			if (pItemFolder->m_nFolderType == FTYPE_DFOLDER && pItemFolder->m_FolderRec.folderName == pFolderTitle) {
				bFind = TRUE;
				break;
			}
		}
	}

	if (!bFind) {
		folderRec.parentFolderID = g_pItemInbox->m_FolderRec.folderID;
		folderRec.folderType = FTYPE_DFOLDER;
		folderRec.folderName = pFolderTitle;
		folderRec.remark = pFolderComment;
		folderRec.creator = GetDefaultUserName();
		folderRec.codeDef = "00000010";
		folderRec.codeStep = 10;

		if (pPcsDB->AddFolderEx(&folderRec)) {
			CAddItemFolder addItem;
			addItem.m_pItemFolder = g_pItemInbox;
			addItem.m_pFolderRec = &folderRec;
			UpdateAllViews(NULL, VIEWHINT_ADDITEM, &addItem);
			pItemFolder = addItem.m_pItemAddFolder;
		} else
			return;
	}

	UpdateAllViews(NULL, VIEWHINT_OPENITEM, pItemFolder);

	dataRec.folderID = pItemFolder->m_FolderRec.folderID;
	dataRec.title = pDataTitle;
	dataRec.code = pItemFolder->m_FolderRec.codeDef;
	dataRec.creator = GetDefaultUserName();
	dataRec.remark = pDataComment;
	if (!pItemFolder->m_pPcsDB->AddDataEx(&dataRec))
		return;

	if (hDIB != NULL) {
		HDIB hDIB2 = MakeSmallPicture(hDIB, PICT_WIDTH, PICT_HEIGHT);
		pItemFolder->m_pPcsDB->WritePictureDataEx(dataRec.dataID, hDIB, hDIB2, GetMMType(hDIB));
	}

	CAddItemData addItemData;
	addItemData.m_pItemFolder = pItemFolder;
	addItemData.m_pDataRec = &dataRec;
	UpdateAllViews(NULL, VIEWHINT_ADDITEM, &addItemData);

	if (strlen(pText) > 0)
		pItemFolder->m_pPcsDB->WriteTextDataEx(dataRec.dataID, &(CString)pText);

	UpdateAllViews(NULL, VIEWHINT_SELECTITEM, addItemData.m_pItemAddData);

	AutoNextNumbering(pItemFolder->m_FolderRec.codeDef, pItemFolder->m_FolderRec.codeStep);
	pItemFolder->m_pPcsDB->UpdateFolderEx(&pItemFolder->m_FolderRec);

	MessageBeep(0);
}

void CMMLibDoc::OnEditImage() 
{
	UpdateAllViews(NULL, VIEWHINT_EDITPICT);
}

void CMMLibDoc::OnUpdateEditImage(CCmdUI* pCmdUI) 
{
	pCmdUI->Enable(m_bPictView && !g_sImageEditor.IsEmpty());
}
