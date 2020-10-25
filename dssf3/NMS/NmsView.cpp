// NmsView.cpp : CNmsView �N���X�̓���̒�`���s���܂��B
//

#include "stdafx.h"
#include "Nms.h"
#include "NmsDoc.h"
#include "NmsView.h"
#include "NmsFactor.h"
#include "ChangeNoiseSrcDlg.h"
#include "CalcAcf.h"
#include "SetData.h"

/////////////////////////////////////////////////////////////////////////////
// CNmsView

IMPLEMENT_DYNCREATE(CNmsView, CListViewEx)

BEGIN_MESSAGE_MAP(CNmsView, CListViewEx)
	ON_COMMAND(IDM_VIEW_FACTOR, OnViewFactor)
	ON_UPDATE_COMMAND_UI(IDM_VIEW_FACTOR, OnUpdateViewFactor)
	ON_WM_LBUTTONDBLCLK()
	ON_COMMAND(IDM_CHANGE_NOISE_SRC, OnChangeNoiseSrc)
	ON_UPDATE_COMMAND_UI(IDM_CHANGE_NOISE_SRC, OnUpdateChangeNoiseSrc)
	ON_NOTIFY_REFLECT(NM_RCLICK, OnRclick)
	// �W������R�}���h
	ON_COMMAND(ID_FILE_PRINT, CListViewEx::OnFilePrint)
	ON_COMMAND(ID_FILE_PRINT_DIRECT, CListViewEx::OnFilePrint)
	ON_COMMAND(ID_FILE_PRINT_PREVIEW, CListViewEx::OnFilePrintPreview)
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CNmsView �N���X�̍\�z/����

CNmsView::CNmsView()
{
	// TODO: ���̏ꏊ�ɍ\�z�p�̃R�[�h��ǉ����Ă��������B

}

CNmsView::~CNmsView()
{
}

BOOL CNmsView::PreCreateWindow(CREATESTRUCT& cs)
{
	// TODO: ���̈ʒu�� CREATESTRUCT cs ���C������ Window �N���X�܂��̓X�^�C����
	//  �C�����Ă��������B
	cs.style |= LVS_REPORT | LVS_OWNERDRAWFIXED | LVS_SHOWSELALWAYS;
//	cs.style |= LVS_REPORT;

	return CListViewEx::PreCreateWindow(cs);
}

/////////////////////////////////////////////////////////////////////////////
// CNmsView �N���X�̕`��

void CNmsView::OnDraw(CDC* /*pDC*/)
{
	CNmsDoc* pDoc = GetDocument();
	ASSERT_VALID(pDoc);
	// TODO: ���̏ꏊ�Ƀl�C�e�B�u �f�[�^�p�̕`��R�[�h��ǉ����܂��B
}

void CNmsView::OnInitialUpdate()
{
	CListViewEx::OnInitialUpdate();

	CNmsDoc* pDoc = GetDocument();
	pDoc->m_pLixxxtrl = &GetLixxxtrl();

	SetListHeader();
}

/////////////////////////////////////////////////////////////////////////////
// CNmsView �N���X�̈��

BOOL CNmsView::OnPreparePrinting(CPrintInfo* pInfo)
{
	// �f�t�H���g�̈������
	return DoPreparePrinting(pInfo);
}

void CNmsView::OnBeginPrinting(CDC* /*pDC*/, CPrintInfo* /*pInfo*/)
{
	// TODO: ����O�̓��ʂȏ�����������ǉ����Ă��������B
}

void CNmsView::OnEndPrinting(CDC* /*pDC*/, CPrintInfo* /*pInfo*/)
{
	// TODO: �����̌㏈����ǉ����Ă��������B
}

/////////////////////////////////////////////////////////////////////////////
// CNmsView �N���X�̐f�f

#ifdef _DEBUG
void CNmsView::AssertValid() const
{
	CListViewEx::AssertValid();
}

void CNmsView::Dump(CDumpContext& dc) const
{
	CListViewEx::Dump(dc);
}

CNmsDoc* CNmsView::GetDocument() // ��f�o�b�O �o�[�W�����̓C�����C���ł��B
{
	ASSERT(m_pDocument->IsKindOf(RUNTIME_CLASS(CNmsDoc)));
	return (CNmsDoc*)m_pDocument;
}
#endif //_DEBUG

/////////////////////////////////////////////////////////////////////////////
// CNmsView �N���X�̃��b�Z�[�W �n���h��

void CNmsView::SetListHeader()
{
	LV_COLUMN lvc;
	int i;
	struct LIxxxOLUMN {
		UINT nID;
		char *text;
		int fmt;
		int width;
	};
	static const LIxxxOLUMN tColumnHeader[] = {
		{0, "No.", LVCFMT_LEFT, 50},
		{IDS_MEASUREMENT_TIME, NULL, LVCFMT_LEFT, 128},
		{IDS_STATUS, NULL, LVCFMT_LEFT, 70},
		{IDS_DECISION, NULL, LVCFMT_LEFT, 90},
//		{IDS_NOISE_VALUE, NULL, LVCFMT_RIGHT, 65},
		{IDS_MAX_SPL, NULL, LVCFMT_RIGHT, 65},
		{IDS_TAUE, NULL, LVCFMT_RIGHT, 53},
		{IDS_TAU1, NULL, LVCFMT_RIGHT, 53},
		{IDS_PHI1, NULL, LVCFMT_RIGHT, 53},
		{0, "IACC", LVCFMT_RIGHT, 53},
		{IDS_TIACC, NULL, LVCFMT_RIGHT, 53},
		{0, "WIACC", LVCFMT_RIGHT, 53}
	};
	const LIxxxOLUMN *pColumnHeader;
	int size;
	CString str;

	HDC dc = ::GetDC(m_hWnd);
	int nLogPixel = GetDeviceCaps(dc, LOGPIXELSX);
	::ReleaseDC(m_hWnd, dc);

	pColumnHeader = tColumnHeader;
	size = sizeof(tColumnHeader) / sizeof(LIxxxOLUMN);

	for (i = 0; i < size; i++, pColumnHeader++) {
		lvc.mask = LVCF_FMT | LVCF_WIDTH | LVCF_TEXT;
		lvc.fmt = pColumnHeader->fmt;
		if (pColumnHeader->nID == 0)
			str = pColumnHeader->text;
		else
			str.LoadString(pColumnHeader->nID);
		lvc.pszText = (LPTSTR)(LPCTSTR)str;

		lvc.cx = pColumnHeader->width * nLogPixel / 96;
		GetLixxxtrl().InsertColumn(i, &lvc);
	}
}

void CNmsView::OnUpdate(CView* /*pSender*/, LPARAM lHint, CObject* pHint)
{
	switch (lHint) {
	case VIEWHINT_ADDITEM:
		AddItem((CNmsItem *)pHint);
		break;
	case VIEWHINT_UPDATEITEM:
		UpdateItem((CNmsItem *)pHint);
		break;
	case VIEWHINT_CLEARITEM:
		GetLixxxtrl().DeleteAllItems();
		break;
	}
}

void CNmsView::AddItem(const CNmsItem *pNmsItem)
{
	CLixxxtrl &lc = GetLixxxtrl();
	int nItem;
	CString str;

	nItem = pNmsItem->m_nIndex;
	str.Format("%d", nItem + 1);
	lc.InsertItem(nItem, (LPCTSTR)str);
	lc.SetItemData(nItem, (DWORD_PTR)pNmsItem);

	SetNmsItem(pNmsItem);

	lc.EnsureVisible(nItem, FALSE);
}

void CNmsView::UpdateItem(const CNmsItem *pNmsItem)
{
	SetNmsItem(pNmsItem);
}

void CNmsView::SetNmsItem(const CNmsItem *pNmsItem)
{
	CLixxxtrl &lc = GetLixxxtrl();
	static const UINT status[] = {NULL, IDS_NOT_CALC, IDS_IN_CALC, IDS_FINISHED};
	int nItem = pNmsItem->m_nIndex;
	CString str;
	int nItemColor;

	lc.SetItemText(nItem, 1, (LPCTSTR)pNmsItem->m_cTime.Format("%Y/%m/%d %H:%M:%S"));
	str.LoadString(status[pNmsItem->m_nStatus]);
	lc.SetItemText(nItem, 2, str);
	str = pNmsItem->m_NoiseSrcData.aName;
	if (str.GetLength() > 0 && str[0] == '=')
		str = str.Mid(1);
	if (pNmsItem->m_bLearning)
		str = "*" + str;
	lc.SetItemText(nItem, 3, str);
//	lc.SetItemText(nItem, 4, "---");

	if (pNmsItem->m_nStatus == STATUS_FINISH_CALC) {
		float fMaxSPL = -100;
		AcfFactor *pAcfFactor = NULL;
		IAcfFactor *pIAcfFactor = NULL;

		int nIdentPos = GetIdentPos(pNmsItem->m_pNmsFactorData, pNmsItem->m_nNmsFactorData, &g_oSetData.Measurement.IdentCondition);
		fMaxSPL = pNmsItem->m_pNmsFactorData[nIdentPos].IAcf.fSPL;
		pIAcfFactor = &pNmsItem->m_pNmsFactorData[nIdentPos].IAcf;
		pAcfFactor = &pNmsItem->m_pNmsFactorData[nIdentPos].Acf;

		if (pIAcfFactor != NULL) {
			str.Format("%.2f", pIAcfFactor->fSPL);
			lc.SetItemText(nItem, 4, str);

			str.Format("%.2f", pAcfFactor->fTauE * 1000);
			lc.SetItemText(nItem, 5, str);

			str.Format("%.2f", pAcfFactor->fTau1 * 1000);
			lc.SetItemText(nItem, 6, str);

			str.Format("%.2f", pAcfFactor->fPhi1);
			lc.SetItemText(nItem, 7, str);

			str.Format("%.2f", pIAcfFactor->fIACC);
			lc.SetItemText(nItem, 8, str);

			str.Format("%.2f", pIAcfFactor->fTauIACC * 1000);
			lc.SetItemText(nItem, 9, str);

			str.Format("%.2f", pIAcfFactor->fWIACC * 1000);
			lc.SetItemText(nItem, 10, str);
		}
	} else {
		lc.SetItemText(nItem, 5, "---");
		lc.SetItemText(nItem, 6, "---");
		lc.SetItemText(nItem, 7, "---");
		lc.SetItemText(nItem, 8, "---");
	}

	switch (pNmsItem->m_nStatus) {
	case STATUS_WAIT_CALC:
		nItemColor = ITEM_COLOR_BLUE;
		break;
	case STATUS_IN_CALC:
		nItemColor = ITEM_COLOR_RED;
		break;
	case STATUS_FINISH_CALC:
		nItemColor = ITEM_COLOR_BLACK;
		break;
	default:
		nItemColor = ITEM_COLOR_BLACK;
		break;
	}
	SetItemColor(nItem, nItemColor);
}

void CNmsView::OnViewFactor()
{
	CLixxxtrl &lc = GetLixxxtrl();
	POSITION pos;
	int nIndex;
	CNmsItem *pNmsItem;

	if ((pos = lc.GetFirstSelectedItemPosition()) != NULL) {
		nIndex = lc.GetNextSelectedItem(pos);
		pNmsItem = (CNmsItem *)lc.GetItemData(nIndex);
		if (pNmsItem->m_nStatus == STATUS_FINISH_CALC)
			CNmsFactor::OpenFactorWnd(pNmsItem);
	}
}

void CNmsView::OnUpdateViewFactor(CCmdUI* pCmdUI)
{
	CLixxxtrl &lc = GetLixxxtrl();
	POSITION pos;
	int nIndex;
	CNmsItem *pNmsItem;
	BOOL bEnable = FALSE;

	if ((pos = lc.GetFirstSelectedItemPosition()) != NULL) {
		nIndex = lc.GetNextSelectedItem(pos);
		pNmsItem = (CNmsItem *)lc.GetItemData(nIndex);
		if (pNmsItem->m_nStatus == STATUS_FINISH_CALC)
			bEnable = TRUE;
	}

	pCmdUI->Enable(bEnable);
}

void CNmsView::OnLButtonDblClk(UINT /*nFlags*/, CPoint /*point*/)
{
	OnViewFactor();
}

void CNmsView::OnChangeNoiseSrc()
{
	CNmsItem *pNmsItem;

	if ((pNmsItem = GetSelectedItem()) != NULL) {
		if (pNmsItem->m_nStatus == STATUS_FINISH_CALC) {
			CChangeNoiseSrcDlg dlg;
			dlg.m_pNmsItem = pNmsItem;
			if (dlg.DoModal() == IDOK)
				SetNmsItem(pNmsItem);
		}
	}

}

void CNmsView::OnUpdateChangeNoiseSrc(CCmdUI* pCmdUI)
{
	CNmsItem *pNmsItem;
	BOOL bEnable = FALSE;

	if ((pNmsItem = GetSelectedItem()) != NULL) {
		if (pNmsItem->m_nStatus == STATUS_FINISH_CALC)
			bEnable = TRUE;
	}

	pCmdUI->Enable(bEnable);
}

void CNmsView::OnRclick(NMHDR* /*pNMHDR*/, LRESULT* pResult)
{
	OnChangeNoiseSrc();

	*pResult = 0;
}

CNmsItem *CNmsView::GetSelectedItem()
{
	CLixxxtrl &lc = GetLixxxtrl();
	POSITION pos;
	int nIndex;

	if ((pos = lc.GetFirstSelectedItemPosition()) == NULL)
		return NULL;

	nIndex = lc.GetNextSelectedItem(pos);
	return (CNmsItem *)lc.GetItemData(nIndex);
}
