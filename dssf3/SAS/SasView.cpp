// ChasView.cpp : CSasView �N���X�̓���̒�`���s���܂��B
//

#include "stdafx.h"
#include "Sas.h"

#include "SasDoc.h"
#include "SasView.h"
#include "ImpFactor.h"
#include "AcfFactor.h"
#include "NmsFactor.h"

#define HEADER_DB		0
#define HEADER_FOLDER	1
#define HEADER_IR		2
#define HEADER_ACF		3
#define HEADER_NMS		4

/////////////////////////////////////////////////////////////////////////////
// CSasView

IMPLEMENT_DYNCREATE(CSasView, CListView)

BEGIN_MESSAGE_MAP(CSasView, CListView)
	ON_WM_CREATE()
	ON_NOTIFY_REFLECT(NM_DBLCLK, OnDblclk)
	ON_COMMAND(IDM_PARAM_DISP, OnParamDisp)
	ON_UPDATE_COMMAND_UI(IDM_PARAM_DISP, OnUpdateParamDisp)
	ON_NOTIFY_REFLECT(LVN_COLUMNCLICK, OnColumnclick)
	ON_COMMAND(ID_FILE_OPEN, OnFileOpen)
	ON_UPDATE_COMMAND_UI(ID_FILE_OPEN, OnUpdateFileOpen)
	ON_COMMAND(IDM_FILE_DELETE, &CSasView::OnFileDelete)
	ON_UPDATE_COMMAND_UI(IDM_FILE_DELETE, &CSasView::OnUpdateFileDelete)
	ON_NOTIFY_REFLECT(NM_RCLICK, &CSasView::OnNMRclick)
	ON_COMMAND(IDM_FILE_CHANGE_TITLE, &CSasView::OnFileChangeTitle)
	ON_UPDATE_COMMAND_UI(IDM_FILE_CHANGE_TITLE, &CSasView::OnUpdateFileChangeTitle)
	ON_NOTIFY_REFLECT(LVN_BEGINLABELEDIT, &CSasView::OnLvnBeginlabeledit)
	ON_NOTIFY_REFLECT(LVN_ENDLABELEDIT, &CSasView::OnLvnEndlabeledit)
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CSasView �N���X�̍\�z/����

CSasView::CSasView()
{
	m_nSortItem = 0;
	m_nSortDir = 1;
}

BOOL CSasView::PreCreateWindow(CREATESTRUCT& cs)
{
	cs.style |= LVS_REPORT | LVS_SHOWSELALWAYS;

	return CListView::PreCreateWindow(cs);
}

/////////////////////////////////////////////////////////////////////////////
// CSasView �N���X�̕`��

void CSasView::OnDraw(CDC* /*pDC*/)
{
	CSasDoc* pDoc = GetDocument();
	ASSERT_VALID(pDoc);
}

void CSasView::OnInitialUpdate()
{
	CListView::OnInitialUpdate();
}

void CSasView::SetHeader(int nHeader)
{
	struct LIxxxOLUMN {
		UINT textID;
		int fmt;
		int width;
	};
	static const LIxxxOLUMN tColumnFolder[] = {
		{IDS_TITLE, LVCFMT_LEFT, 150},
		{IDS_NAME, LVCFMT_LEFT, 100},
		{IDS_PLACE, LVCFMT_LEFT, 100},
		{IDS_DATE, LVCFMT_LEFT, 100},
		{IDS_COMMENT, LVCFMT_LEFT, 150},
		{IDS_SCALE, LVCFMT_LEFT, 55}
	}, tColumnIR[] = {
		{IDS_TITLE, LVCFMT_LEFT, 200},
		{IDS_COMMENT, LVCFMT_LEFT, 280},
		{IDS_DATE, LVCFMT_LEFT, 135},
		{IDS_CALC, LVCFMT_CENTER, 45}
	}, tColumnNms[] = {
		{IDS_NUMBER, LVCFMT_LEFT, 50},
		{IDS_MEASUREMENT_TIME, LVCFMT_LEFT, 135},
		{IDS_DECISION, LVCFMT_LEFT, 90},
//		{IDS_NOISE_VALUE, LVCFMT_RIGHT, 60},
		{IDS_CALC, LVCFMT_CENTER, 45}
	};
	const LIxxxOLUMN *pColumn;
	int i;
	int nColumn;
	CString str;
	LV_COLUMN lvc;
	CLixxxtrl &lc = GetLixxxtrl();
	CHeaderCtrl &hc = *lc.GetHeaderCtrl();

	lc.DeleteAllItems();

	while (hc.GetItemCount() != 0)
		lc.DeleteColumn(0);

	switch (nHeader) {
	case HEADER_FOLDER:
		pColumn = tColumnFolder;
		nColumn  = sizeof(tColumnFolder) / sizeof(LIxxxOLUMN);
		break;
	case HEADER_IR:
	case HEADER_ACF:
		pColumn = tColumnIR;
		nColumn  = sizeof(tColumnIR) / sizeof(LIxxxOLUMN);
		break;
	case HEADER_NMS:
		pColumn = tColumnNms;
		nColumn  = sizeof(tColumnNms) / sizeof(LIxxxOLUMN);
		break;
	default:
		return;
	}

	GetLixxxtrl().SetImageList(&imageList, LVSIL_SMALL);
	for (i = 0; i < nColumn; i++) {
		lvc.mask = LVCF_FMT | LVCF_WIDTH | LVCF_TEXT;
		lvc.fmt = pColumn->fmt;
		str.LoadString(pColumn->textID);
		lvc.pszText = (LPTSTR)(LPCTSTR)str;
		lvc.cx = pColumn->width;
		lc.InsertColumn(i, &lvc);
		pColumn++;
	}
}

/////////////////////////////////////////////////////////////////////////////
// CSasView �N���X�̈��

BOOL CSasView::OnPreparePrinting(CPrintInfo* pInfo)
{
	// �f�t�H���g�̈������
	return DoPreparePrinting(pInfo);
}

void CSasView::OnBeginPrinting(CDC* /*pDC*/, CPrintInfo* /*pInfo*/)
{
	// TODO: ����O�̓��ʂȏ�����������ǉ����Ă��������B
}

void CSasView::OnEndPrinting(CDC* /*pDC*/, CPrintInfo* /*pInfo*/)
{
	// TODO: �����̌㏈����ǉ����Ă��������B
}

/////////////////////////////////////////////////////////////////////////////
// CSasView �N���X�̐f�f

#ifdef _DEBUG
void CSasView::AssertValid() const
{
	CListView::AssertValid();
}

void CSasView::Dump(CDumpContext& dc) const
{
	CListView::Dump(dc);
}

CSasDoc* CSasView::GetDocument() // ��f�o�b�O �o�[�W�����̓C�����C���ł��B
{
	ASSERT(m_pDocument->IsKindOf(RUNTIME_CLASS(CSasDoc)));
	return (CSasDoc*)m_pDocument;
}
#endif //_DEBUG

/////////////////////////////////////////////////////////////////////////////
// CSasView �N���X�̃��b�Z�[�W �n���h��

int CSasView::OnCreate(LPCREATESTRUCT lpCreateStruct)
{
	if (CListView::OnCreate(lpCreateStruct) == -1)
		return -1;

	GetLixxxtrl().SetExtendedStyle(LVS_EX_FULLROWSELECT);

	return 0;
}

void CSasView::OnUpdate(CView* /*pSender*/, LPARAM lHint, CObject* pHint)
{
	CSasDoc *pDoc = GetDocument();

	if (pHint == PHINT_DELETE) {
		DeleteItem((long)lHint);
		return;
	} else if (pHint != NULL) {
		CLixxxtrl &lc = GetLixxxtrl();
		int nCount = lc.GetItemCount();
		for (int i = 0; i < nCount; i++) {
			if ((long)lc.GetItemData(i) == (long)lHint) {
				lc.SetItemText(i, 0, (LPCTSTR)pHint);
				break;
			}
		}
	} else {
		switch (lHint & ID_KIND) {
		case ID_SYSTEM:
			DrawDB();
			break;
		default:
			if (pDoc->m_nFolderID == -1) {
				if (pDoc->m_nType == 0)
					DrawDB();
				else
					DrawFolder();
			} else {
				switch (pDoc->m_nType) {
				case FOLDER_TYPE_IR:
					DrawIR();
					break;
				case FOLDER_TYPE_ACF:
					DrawACF();
					break;
				case FOLDER_TYPE_NMS:
					DrawNMS();
					break;
				}
			}
			break;
		}
	}
}

void CSasView::DrawDB()
{
	SetHeader(HEADER_DB);
}

void CSasView::DrawFolder()
{
	CSasDoc *pDoc = GetDocument();
	CLixxxtrl &lc = GetLixxxtrl();
	CDbFolder dbFolder;
	DbFolderRec dbFolderRec;
	CString str;
	int i;

	SetHeader(HEADER_FOLDER);

	if (!dbFolder.Open())
		return;

	str.Format("TYPE=%c", pDoc->m_nType);
	dbFolder.DBSetFilter(str);

	for (i = 0; ; i++) {
		if (!dbFolder.ReadRecNext(&dbFolderRec))
			break;

		switch (dbFolderRec.sType[0]) {
		case FOLDER_TYPE_IR:
			lc.InsertItem(i, dbFolderRec.sTitle, IMAGE_FOLDER_IR);
			break;
		case FOLDER_TYPE_ACF:
			lc.InsertItem(i, dbFolderRec.sTitle, IMAGE_FOLDER_ACF);
			break;
		case FOLDER_TYPE_NMS:
			lc.InsertItem(i, dbFolderRec.sTitle, IMAGE_FOLDER_NMS);
			break;
		}

		lc.SetItemText(i, 1, dbFolderRec.sName);
		lc.SetItemText(i, 2, dbFolderRec.sPlace);
		lc.SetItemText(i, 3, FormatTime(dbFolderRec.sDate));
		lc.SetItemText(i, 4, dbFolderRec.sComment);
		str.Format("1/%g", dbFolderRec.fScale != 0 ? dbFolderRec.fScale : 1.0);
		lc.SetItemText(i, 5, str);

		lc.SetItemData(i, ID_FOLDER | dbFolderRec.nFolderID);
	}

	lc.SortItems(CompareItems, (LPARAM)this);
}

void CSasView::DrawIR()
{
	long folderID = GetDocument()->m_nFolderID;
	CLixxxtrl &lc = GetLixxxtrl();
	int i;
	CDbImpulse dbImpulse;
	DbImpulseRec dbImpulseRec;
	CDbAcParam dbAcParam;
	CString str;
	long nRec;
	int find;

	if (folderID == -1)
		return;

	if (!dbImpulse.Open())
		return;

	if (!dbAcParam.Open())
		return;

	SetHeader(HEADER_IR);
	dbImpulse.DBChgIdx(dbImpulse.m_nIdxImpulseID);
	str.Format("#%d=%ld", dbImpulse.m_nIdxFolderID, folderID);
	dbImpulse.DBSelect(str, &nRec);
	for (i = 0; ; i++) {
		if (!dbImpulse.ReadRecNext(&dbImpulseRec))
			break;

		find = dbAcParam.SearchID(dbImpulseRec.nImpulseID);

		lc.InsertItem(i, dbImpulseRec.sTitle, find != 0 ? IMAGE_IR_RED : IMAGE_IR_BLUE);
		lc.SetItemText(i, 1, dbImpulseRec.sComment);
		lc.SetItemText(i, 2, FormatTime(dbImpulseRec.sTime));
		if (find != 0)
			lc.SetItemText(i, 3, "*");

		lc.SetItemData(i, ID_DATA | dbImpulseRec.nImpulseID);
	}

	lc.SortItems(CompareItems, (LPARAM)this);
}

void CSasView::DrawACF()
{
	long folderID = GetDocument()->m_nFolderID;
	CLixxxtrl &lc = GetLixxxtrl();
	int i;
	CDbAcf dbAcf;
	DbAcfRec dbAcfRec;
	CDbAcfFactor dbAcfFactor;
	CString str;
	long nRec;
	int find;

	if (folderID == -1)
		return;

	if (!dbAcf.Open())
		return;

	if (!dbAcfFactor.Open())
		return;

	SetHeader(HEADER_ACF);
	dbAcf.DBChgIdx(dbAcf.m_nIdxAcfID);
	str.Format("#%d=%ld", dbAcf.m_nIdxFolderID, folderID);
	dbAcf.DBSelect(str, &nRec);
	for (i = 0; ; i++) {
		if (!dbAcf.ReadRecNext(&dbAcfRec))
			break;

		find = dbAcfFactor.SearchID(dbAcfRec.nAcfID);

		lc.InsertItem(i, dbAcfRec.sTitle, find != 0 ? IMAGE_ACF_RED : IMAGE_ACF_BLUE);
		lc.SetItemText(i, 1, dbAcfRec.sComment);
		lc.SetItemText(i, 2, FormatTime(dbAcfRec.sTime));
		if (find != 0)
			lc.SetItemText(i, 3, "*");

		lc.SetItemData(i, ID_DATA | dbAcfRec.nAcfID);
	}

	lc.SortItems(CompareItems, (LPARAM)this);
}

void CSasView::DrawNMS()
{
	long folderID = GetDocument()->m_nFolderID;
	CLixxxtrl &lc = GetLixxxtrl();
	int i;
	CDbNms dbNms;
	DbNmsRec dbNmsRec;
	CString str;
	long nRec;
	BOOL bEof;
	NoiseSrcData noiseSrcData;
	long nBytes;

	if (folderID == -1)
		return;

	if (!dbNms.Open())
		return;

	SetHeader(HEADER_NMS);
	dbNms.DBChgIdx(dbNms.m_nIdxNmsID);
	str.Format("#%d=%ld", dbNms.m_nIdxFolderID, folderID);
	dbNms.DBSelect(str, &nRec);
	for (i = 0; ; i++) {
		dbNms.DBEof(&bEof);
		if (bEof)
			break;

		memset(&noiseSrcData, 0, sizeof(noiseSrcData));
		dbNms.DBReadBinary("NOISESRC", &noiseSrcData, sizeof(noiseSrcData), &nBytes);

		if (!dbNms.ReadRecNext(&dbNmsRec))
			break;

		str.Format("%ld", dbNmsRec.nNumber + 1);
		lc.InsertItem(i, str, dbNmsRec.nNmsFactor != 0 ? IMAGE_NMS_RED : IMAGE_NMS_BLUE);
		lc.SetItemText(i, 1, FormatTime(dbNmsRec.sTime));
		if (noiseSrcData.aName[0] == '=')
			lc.SetItemText(i, 2, noiseSrcData.aName + 1);
		else
			lc.SetItemText(i, 2, noiseSrcData.aName);

		lc.SetItemData(i, ID_DATA | dbNmsRec.nNmsID);

		if (dbNmsRec.nNmsFactor != 0)
			lc.SetItemText(i, 3, "*");
	}

	lc.SortItems(CompareItems, (LPARAM)this);
}

void CSasView::OnDblclk(NMHDR* /*pNMHDR*/, LRESULT* pResult)
{
	CLixxxtrl &lc = GetLixxxtrl();
	int nItem;
	DWORD_PTR dwItemData;

	if ((nItem = GetSelectedItem()) != -1) {
		dwItemData = lc.GetItemData(nItem);
		switch (dwItemData & ID_KIND) {
		case ID_FOLDER:
			OnFileOpen();
			break;
		case ID_DATA:
			OnParamDisp();
			break;
		}
	}

	*pResult = 0;
}

void CSasView::OnParamDisp()
{
	CSasDoc *pDoc = GetDocument();
	int nItem;

	if ((nItem = GetSelectedItem()) == -1) {
		AfxMessageBox(IDS_MSG_SELECTDATA, MB_OK | MB_ICONINFORMATION);
		return;
	}

	switch (pDoc->m_nType) {
	case FOLDER_TYPE_IR:
		DispIR(nItem);
		break;
	case FOLDER_TYPE_ACF:
		DispACF(nItem);
		break;
	case FOLDER_TYPE_NMS:
		DispNMS(nItem);
		break;
	}
}

void CSasView::OnUpdateParamDisp(CCmdUI* pCmdUI)
{
	CLixxxtrl &lc = GetLixxxtrl();
	BOOL bEnable = FALSE;
	int nItem;

	if ((nItem = GetSelectedItem()) != -1) {
		if ((lc.GetItemData(nItem) & ID_KIND) == ID_DATA)
			bEnable = TRUE;
	}

	pCmdUI->Enable(bEnable);
}

int CSasView::GetSelectedItem()
{
	CLixxxtrl &lc = GetLixxxtrl();
	int i, nItem;

	nItem = lc.GetItemCount();
	for (i = 0; i < nItem; i++) {
		if (lc.GetItemState(i, LVIS_SELECTED) != 0)
			return i;
	}

	return -1;
}

void CSasView::DispIR(int nItem)
{
	CSasDoc *pDoc = GetDocument();
	CLixxxtrl &lc = GetLixxxtrl();

	if (pDoc->m_nType != FOLDER_TYPE_IR)
		return;

	CImpFactor *pDlg = new CImpFactor((CWnd *)&wndTop);
	pDlg->m_nFolderID = ((CSasDoc *)GetDocument())->m_nFolderID;
	pDlg->m_nImpulseID = (long)lc.GetItemData(nItem) & ID_VALUE;
	pDlg->m_pView = this;
	pDlg->Create();
}

void CSasView::DispACF(int nItem)
{
	CSasDoc *pDoc = GetDocument();
	CLixxxtrl &lc = GetLixxxtrl();

	if (pDoc->m_nType != FOLDER_TYPE_ACF)
		return;

	CAcfFactor *pDlg = new CAcfFactor((CWnd *)&wndTop);
	pDlg->m_nFolderID = ((CSasDoc *)GetDocument())->m_nFolderID;
	pDlg->m_nAcfID = (long)lc.GetItemData(nItem) & ID_VALUE;
	pDlg->m_pView = this;
	pDlg->Create();
}

void CSasView::DispNMS(int nItem)
{
	CSasDoc *pDoc = GetDocument();
	CLixxxtrl &lc = GetLixxxtrl();

	if (pDoc->m_nType != FOLDER_TYPE_NMS)
		return;

	CNmsFactor *pDlg = new CNmsFactor((CWnd *)&wndTop);
	pDlg->m_nFolderID = ((CSasDoc *)GetDocument())->m_nFolderID;
	pDlg->m_NmsID = (long)lc.GetItemData(nItem) & ID_VALUE;
	pDlg->m_pView = this;
	pDlg->Create();
}

BOOL CSasView::CheckSelect(long impulseID)
{
	CLixxxtrl &lc = GetLixxxtrl();
	int i, nItem;

	nItem = lc.GetItemCount();
	for (i = 0; i < nItem; i++) {
		if (lc.GetItemData(i) == (DWORD)(ID_DATA | impulseID)) {
			if (lc.GetItemState(i, LVIS_SELECTED) != 0)
				return TRUE;
			else
				return FALSE;
		}
	}
	return FALSE;
}

void CSasView::OnColumnclick(NMHDR* pNMHDR, LRESULT* pResult)
{
	NM_LISTVIEW* pNMListView = (NM_LISTVIEW*)pNMHDR;

	if (m_nSortItem != pNMListView->iSubItem)
		m_nSortDir = 1;
	else
		m_nSortDir *= -1;

	m_nSortItem = pNMListView->iSubItem;
	GetLixxxtrl().SortItems(CompareItems, (LPARAM)this);

	*pResult = 0;
}

int CALLBACK CSasView::CompareItems(LPARAM lParam1, LPARAM lParam2, LPARAM lParamSort)
{
	CSasView *pView = (CSasView *)lParamSort;
	CLixxxtrl &lc = pView->GetLixxxtrl();
	LV_FINDINFO findInfo;
	int index1, index2;

	findInfo.flags = LVFI_PARAM;
	findInfo.lParam = lParam1;
	index1 = lc.FindItem(&findInfo);

	findInfo.flags = LVFI_PARAM;
	findInfo.lParam = lParam2;
	index2 = lc.FindItem(&findInfo);

	return lc.GetItemText(index1, pView->m_nSortItem).Compare(lc.GetItemText(index2, pView->m_nSortItem)) * pView->m_nSortDir;
}

long CSasView::GetNextItem(long impulseID)
{
	CLixxxtrl &lc = GetLixxxtrl();
	LV_FINDINFO findInfo;
	int index;

	findInfo.flags = LVFI_PARAM;
	findInfo.lParam = impulseID | ID_DATA;
	if ((index = lc.FindItem(&findInfo)) == -1)
		return -1;

	if (++index >= lc.GetItemCount())
		return -1;

	return (long)lc.GetItemData(index) & ID_VALUE;
}

long CSasView::GetPrevItem(long impulseID)
{
	CLixxxtrl &lc = GetLixxxtrl();
	LV_FINDINFO findInfo;
	int index;

	findInfo.flags = LVFI_PARAM;
	findInfo.lParam = impulseID | ID_DATA;
	if ((index = lc.FindItem(&findInfo)) == -1)
		return -1;

	if (--index < 0)
		return -1;

	return (long)lc.GetItemData(index) & ID_VALUE;
}

void CSasView::OnFileOpen()
{
	CSasDoc *pDoc = GetDocument();
	CLixxxtrl &lc = GetLixxxtrl();
	int nItem;

	if ((nItem = GetSelectedItem()) != -1)
		pDoc->UpdateAllViews(this, lc.GetItemData(nItem));
}

void CSasView::OnUpdateFileOpen(CCmdUI* pCmdUI)
{
	CSasDoc *pDoc = GetDocument();
	CLixxxtrl &lc = GetLixxxtrl();

	pCmdUI->Enable(pDoc->m_nFolderID == -1 && lc.GetSelectedCount() != 0);
}

void CSasView::OnFileDelete()
{
	CSasDoc *pDoc = GetDocument();
	CLixxxtrl &lc = GetLixxxtrl();
	int i, nItem;

	nItem = lc.GetItemCount();
	if (nItem != 0) {
		long *nIDs = new long[nItem];
		int count = 0;

		for (i = 0; i < nItem; i++) {
			if (lc.GetItemState(i, LVIS_SELECTED) != 0)
				nIDs[count++] = (long)lc.GetItemData(i);
		}

		if (AfxMessageBox((nIDs[0] & ID_KIND) == ID_FOLDER ? IDS_MSG_DELETEFOLDER : IDS_MSG_DELETESELECTDATA, MB_OKCANCEL | MB_ICONQUESTION) == IDOK) {
			for (i = 0; i < count; i++)
				pDoc->DeleteItem(nIDs[i]);
		}

		delete [] nIDs;
	}
}

void CSasView::OnUpdateFileDelete(CCmdUI *pCmdUI)
{
	CLixxxtrl &lc = GetLixxxtrl();
	int i, nItem;
	BOOL bEnable = FALSE;

	nItem = lc.GetItemCount();
	for (i = 0; i < nItem; i++) {
		if (lc.GetItemState(i, LVIS_SELECTED) != 0) {
			bEnable = TRUE;
			break;
		}
	}

	pCmdUI->Enable(bEnable);
}

void CSasView::DeleteItem(long nDataID)
{
	CLixxxtrl &lc = GetLixxxtrl();

	int nCount = lc.GetItemCount();
	for (int i = 0; i < nCount; i++) {
		if ((long)lc.GetItemData(i) == nDataID) {
			lc.DeleteItem(i);
			break;
		}
	}
}

void CSasView::OnNMRclick(NMHDR * /*pNMHDR*/, LRESULT *pResult)
{
	CPoint posCursor;
	::GetCursorPos(&posCursor);

	CMenu menu;
	menu.LoadMenu(IDR_LISTVIEW);
	CMenu* pPopup = menu.GetSubMenu(0);
	pPopup->TrackPopupMenu(TPM_LEFTALIGN | TPM_RIGHTBUTTON, posCursor.x, posCursor.y, AfxGetMainWnd());

	*pResult = 0;
}

void CSasView::OnFileChangeTitle()
{
	CLixxxtrl &lc = GetLixxxtrl();
	int i, nItem;

	nItem = lc.GetItemCount();
	for (i = 0; i < nItem; i++) {
		if (lc.GetItemState(i, LVIS_SELECTED) != 0) {
			ModifyStyle(0, LVS_EDITLABELS);
			lc.EditLabel(i);
			break;
		}
	}
}

void CSasView::OnUpdateFileChangeTitle(CCmdUI *pCmdUI)
{
	CLixxxtrl &lc = GetLixxxtrl();
	CSasDoc *pDoc = GetDocument();
	int i, nItem;
	BOOL bEnable = FALSE;

	nItem = lc.GetItemCount();
	for (i = 0; i < nItem; i++) {
		if (lc.GetItemState(i, LVIS_SELECTED) != 0) {
			long nKind = (long)lc.GetItemData(i) & ID_KIND;
			if (nKind == ID_FOLDER || nKind == ID_DATA && (pDoc->m_nType == FOLDER_TYPE_IR || pDoc->m_nType == FOLDER_TYPE_ACF))
				bEnable = TRUE;
			break;
		}
	}

	pCmdUI->Enable(bEnable);
}

void CSasView::OnLvnBeginlabeledit(NMHDR *pNMHDR, LRESULT *pResult)
{
	NMLVDISPINFO *pDispInfo = reinterpret_cast<NMLVDISPINFO*>(pNMHDR);
	CLixxxtrl &lc = GetLixxxtrl();
	CSasDoc *pDoc = GetDocument();

	long nKind = (long)lc.GetItemData(pDispInfo->item.iItem) & ID_KIND;
	if (nKind == ID_FOLDER || nKind == ID_DATA && (pDoc->m_nType == FOLDER_TYPE_IR || pDoc->m_nType == FOLDER_TYPE_ACF)) {
		m_cEditTitle.SetMaxLength(LEN_TITLE);
		m_cEditTitle.SubclassWindow(lc.GetEditControl()->GetSafeHwnd());

		*pResult = 0;
	} else
		*pResult = 1;
}

void CSasView::OnLvnEndlabeledit(NMHDR *pNMHDR, LRESULT *pResult)
{
	NMLVDISPINFO *pDispInfo = reinterpret_cast<NMLVDISPINFO*>(pNMHDR);
	CLixxxtrl &lc = GetLixxxtrl();
	CSasDoc *pDoc = GetDocument();

	CString sTitle = m_cEditTitle;
	pDoc->ChangeTitle((long)lc.GetItemData(pDispInfo->item.iItem), sTitle);

	m_cEditTitle.UnsubclassWindow();

	ModifyStyle(LVS_EDITLABELS, 0);

	*pResult = 0;
}
