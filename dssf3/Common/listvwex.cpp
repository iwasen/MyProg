// ListVwEx.cpp : CListViewEx �N���X�̃C���v�������e�[�V����
//
// This is a part of the Microsoft Foundation Classes C++ library.
// Copyright (C) 1992-1998 Microsoft Corporation
// All rights reserved.
//
// ���̃\�[�X �R�[�h�� Microsoft Foundation Class ���t�@�����X����сA
// �֘A����I�����C�� �h�L�������g�̕⑫�݂̂�ړI�Ƃ��Ă��܂��B
// Microsoft Foundation Class ���i�Ɋւ���ڂ������͂����̃h�L�������g
// ���Q�Ƃ��Ă��������B
//

#include "stdafx.h"
#include "ListVwEx.h"

/////////////////////////////////////////////////////////////////////////////
// CListViewEx

IMPLEMENT_DYNCREATE(CListViewEx, CListView)

BEGIN_MESSAGE_MAP(CListViewEx, CListView)
	ON_WM_SIZE()
	ON_WM_PAINT()
	ON_WM_SETFOCUS()
	ON_WM_KILLFOCUS()
	ON_MESSAGE(LVM_SETIMAGELIST, OnSetImageList)
	ON_MESSAGE(LVM_SETTEXTCOLOR, OnSetTextColor)
	ON_MESSAGE(LVM_SETTEXTBKCOLOR, OnSetTextBkColor)
	ON_MESSAGE(LVM_SETBKCOLOR, OnSetBkColor)
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CListViewEx �N���X�̍\�z/����

CListViewEx::CListViewEx()
{
	m_bFullRowSel = FALSE;
	m_bClientWidthSel = TRUE;

	m_cxClient = 0;
	m_cxStateImageOffset = 0;

	m_clrText = ::GetSysColor(COLOR_WINDOWTEXT);
	m_clrTextBk = ::GetSysColor(COLOR_WINDOW);
	m_clrBkgnd = ::GetSysColor(COLOR_WINDOW);
}

BOOL CListViewEx::PreCreateWindow(CREATESTRUCT& cs)
{
	// �f�t�H���g�ł̓��|�[�g �r���[����эs�S�̂̑I���ł��B
	cs.style &= ~LVS_TYPEMASK;
	cs.style |= LVS_REPORT | LVS_OWNERDRAWFIXED;
	m_bFullRowSel = TRUE;

	return(CListView::PreCreateWindow(cs));
}

BOOL CListViewEx::SetFullRowSel(BOOL bFullRowSel)
{
	// �ύX���͕`�悵�܂���B
	LockWindowUpdate();

	m_bFullRowSel = bFullRowSel;

	BOOL bRet;

	if (m_bFullRowSel)
		bRet = ModifyStyle(0L, LVS_OWNERDRAWFIXED);
	else
		bRet = ModifyStyle(LVS_OWNERDRAWFIXED, 0L);

	// �r���[ �^�C�v�̕ύX���łȂ���΁A�E�B���h�E���ĕ`�悵�܂��B
	if (bRet && (GetStyle() & LVS_TYPEMASK) == LVS_REPORT)
		Invalidate();

	// �ύX���ĕ`�悵�܂��B
	UnlockWindowUpdate();

	return(bRet);
}

BOOL CListViewEx::GetFullRowSel()
{
	return(m_bFullRowSel);
}

/////////////////////////////////////////////////////////////////////////////
// CListViewEx �N���X�̕`��

// �ŏ��Ƃ��̑��̗�p�̃I�t�Z�b�g
#define OFFSET_FIRST	2
#define OFFSET_OTHER	6

void CListViewEx::DrawItem(LPDRAWITEMSTRUCT lpDrawItemStruct)
{
	CLixxxtrl& Lixxxtrl=GetLixxxtrl();
	CDC* pDC = CDC::FromHandle(lpDrawItemStruct->hDC);
	CRect rcItem(lpDrawItemStruct->rcItem);
	UINT uiFlags = ILD_TRANSPARENT;
	CImageList* pImageList;
	int nItem = lpDrawItemStruct->itemID;
	BOOL bFocus = (GetFocus() == this);
	COLORREF clrTextSave, clrBkSave = 0;
	COLORREF clrImage = m_clrBkgnd;
	static _TCHAR szBuff[MAX_PATH];
	LPCTSTR pszText;

// �A�C�e�� �f�[�^���擾���܂��B

	LV_ITEM lvi;
	lvi.mask = LVIF_TEXT | LVIF_IMAGE | LVIF_STATE | LVIF_INDENT;
	lvi.iItem = nItem;
	lvi.iSubItem = 0;
	lvi.pszText = szBuff;
	lvi.cchTextMax = sizeof(szBuff);
	lvi.stateMask = 0xFFFF;		// ���ׂĂ̏�ԃt���O���擾���܂��B
	Lixxxtrl.GetItem(&lvi);

	BOOL bSelected = (bFocus || (GetStyle() & LVS_SHOWSELALWAYS)) && lvi.state & LVIS_SELECTED;
	bSelected = bSelected || (lvi.state & LVIS_DROPHILITED);

// �A�C�e�����I�����ꂽ�ꍇ�A�F��ݒ肵�܂��B

	CRect rcAllLabels;
	Lixxxtrl.GetItemRect(nItem, rcAllLabels, LVIR_BOUNDS);

	CRect rcLabel;
	Lixxxtrl.GetItemRect(nItem, rcLabel, LVIR_LABEL);

	rcAllLabels.left = rcLabel.left;
	if (m_bClientWidthSel && rcAllLabels.right<m_cxClient)
		rcAllLabels.right = m_cxClient;

	if (bSelected)
	{
		clrTextSave = pDC->SetTextColor(::GetSysColor(COLOR_HIGHLIGHTTEXT));
		clrBkSave = pDC->SetBkColor(::GetSysColor(COLOR_HIGHLIGHT));

		CBrush brush(::GetSysColor(COLOR_HIGHLIGHT));
		pDC->FillRect(rcAllLabels, &brush);
	} else {
		clrTextSave = pDC->SetTextColor(RGB(
				(lvi.iIndent & ITEM_COLOR_RED) ? 255 : 0,
				(lvi.iIndent & ITEM_COLOR_GREEN) ? 255 : 0,
				(lvi.iIndent & ITEM_COLOR_BLUE) ? 255 : 0));

		CBrush brush(m_clrTextBk);
		pDC->FillRect(rcAllLabels, &brush);
	}

// �F��ݒ肵�ăA�C�R�����}�X�N���܂��B

	if (lvi.state & LVIS_CUT)
	{
		clrImage = m_clrBkgnd;
		uiFlags |= ILD_BLEND50;
	}
	else if (bSelected)
	{
		clrImage = ::GetSysColor(COLOR_HIGHLIGHT);
		uiFlags |= ILD_BLEND50;
	}

// ��ԃA�C�R����`�悵�܂��B

	UINT nStateImageMask = lvi.state & LVIS_STATEIMAGEMASK;
	if (nStateImageMask)
	{
		int nImage = (nStateImageMask>>12) - 1;
		pImageList = Lixxxtrl.GetImageList(LVSIL_STATE);
		if (pImageList)
		{
			pImageList->Draw(pDC, nImage,
				CPoint(rcItem.left, rcItem.top), ILD_TRANSPARENT);
		}
	}

// �ʏ�̃A�C�R���ƃI�[�o�[���C �A�C�R����`�悵�܂��B

	CRect rcIcon;
	Lixxxtrl.GetItemRect(nItem, rcIcon, LVIR_ICON);

	pImageList = Lixxxtrl.GetImageList(LVSIL_SMALL);
	if (pImageList)
	{
		UINT nOvlImageMask=lvi.state & LVIS_OVERLAYMASK;
		if (rcItem.left<rcItem.right-1)
		{
			ImageList_DrawEx(pImageList->m_hImageList, lvi.iImage,
					pDC->m_hDC,rcIcon.left,rcIcon.top, 16, 16,
					m_clrBkgnd, clrImage, uiFlags | nOvlImageMask);
		}
	}

// �A�C�e���̃��x����`���܂��B

	Lixxxtrl.GetItemRect(nItem, rcItem, LVIR_LABEL);
	rcItem.right -= m_cxStateImageOffset;

	pszText = MakeShortString(pDC, szBuff,
				rcItem.right-rcItem.left, 2*OFFSET_FIRST);

	rcLabel = rcItem;
	rcLabel.left += OFFSET_FIRST;
	rcLabel.right -= OFFSET_FIRST;

	pDC->DrawText(pszText,-1,rcLabel,DT_LEFT | DT_SINGLELINE | DT_NOPREFIX | DT_NOCLIP | DT_VCENTER);

// ���ʂȃJ�����p�̃��x����`���܂��B

	LV_COLUMN lvc;
	lvc.mask = LVCF_FMT | LVCF_WIDTH;

	for(int nColumn = 1; Lixxxtrl.GetColumn(nColumn, &lvc); nColumn++)
	{
		rcItem.left = rcItem.right;
		rcItem.right += lvc.cx;

		int nRetLen = Lixxxtrl.GetItemText(nItem, nColumn,
						szBuff, sizeof(szBuff));
		if (nRetLen == 0)
			continue;

		pszText = MakeShortString(pDC, szBuff,
			rcItem.right - rcItem.left, 2*OFFSET_OTHER);

		UINT nJustify = DT_LEFT;

		if(pszText == szBuff)
		{
			switch(lvc.fmt & LVCFMT_JUSTIFYMASK)
			{
			case LVCFMT_RIGHT:
				nJustify = DT_RIGHT;
				break;
			case LVCFMT_CENTER:
				nJustify = DT_CENTER;
				break;
			default:
				break;
			}
		}

		rcLabel = rcItem;
		rcLabel.left += OFFSET_OTHER;
		rcLabel.right -= OFFSET_OTHER;

		pDC->DrawText(pszText, -1, rcLabel,
			nJustify | DT_SINGLELINE | DT_NOPREFIX | DT_NOCLIP | DT_VCENTER);
	}

// �A�C�e�����t�H�[�J�X�������Ă���ꍇ�ɁA�t�H�[�J�X�̂����`��`�悵�܂��B

	if (lvi.state & LVIS_FOCUSED && bFocus)
		pDC->DrawFocusRect(rcAllLabels);

// �A�C�e�����I�����ꂽ�ꍇ�A�I���W�i�� �J���[�ɐݒ肵�܂��B

	if (bSelected)
	{
        pDC->SetTextColor(clrTextSave);
		pDC->SetBkColor(clrBkSave);
	}
}

LPCTSTR CListViewEx::MakeShortString(CDC* pDC, LPCTSTR lpszLong, int nColumnLen, int nOffset)
{
	static const _TCHAR szThreeDots[] = _T("...");

	int nStringLen = lstrlen(lpszLong);

	if(nStringLen == 0 ||
		(pDC->GetTextExtent(lpszLong, nStringLen).cx + nOffset) <= nColumnLen)
	{
		return(lpszLong);
	}

	static _TCHAR szShort[MAX_PATH];

	lstrcpy(szShort,lpszLong);
	int nAddLen = pDC->GetTextExtent(szThreeDots,sizeof(szThreeDots)).cx;

	for(int i = nStringLen-1; i > 0; i--)
	{
		szShort[i] = 0;
		if((pDC->GetTextExtent(szShort, i).cx + nOffset + nAddLen)
			<= nColumnLen)
		{
			break;
		}
	}

	lstrcat(szShort, szThreeDots);
	return(szShort);
}

void CListViewEx::RepaintSelectedItems()
{
	CLixxxtrl& Lixxxtrl = GetLixxxtrl();
	CRect rcItem, rcLabel;

// �t�H�[�J�X���ꂽ�A�C�e���𖳌��ɂ��邱�Ƃɂ��A�������ĕ`��ł��܂��B

	int nItem = Lixxxtrl.GetNextItem(-1, LVNI_FOCUSED);

	if(nItem != -1)
	{
		Lixxxtrl.GetItemRect(nItem, rcItem, LVIR_BOUNDS);
		Lixxxtrl.GetItemRect(nItem, rcLabel, LVIR_LABEL);
		rcItem.left = rcLabel.left;

		InvalidateRect(rcItem, FALSE);
	}

// �I�����ꂽ�A�C�e�����ۑ�����Ȃ��ꍇ�́A�����𖳌��ɂ��܂��B

	if(!(GetStyle() & LVS_SHOWSELALWAYS))
	{
		for(nItem = Lixxxtrl.GetNextItem(-1, LVNI_SELECTED);
			nItem != -1; nItem = Lixxxtrl.GetNextItem(nItem, LVNI_SELECTED))
		{
			Lixxxtrl.GetItemRect(nItem, rcItem, LVIR_BOUNDS);
			Lixxxtrl.GetItemRect(nItem, rcLabel, LVIR_LABEL);
			rcItem.left = rcLabel.left;

			InvalidateRect(rcItem, FALSE);
		}
	}

// �ύX���X�V���܂��B

	UpdateWindow();
}

/////////////////////////////////////////////////////////////////////////////
// CListViewEx �N���X�̐f�f

#ifdef _DEBUG

void CListViewEx::Dump(CDumpContext& dc) const
{
	CListView::Dump(dc);

	dc << "m_bFullRowSel = " << (UINT)m_bFullRowSel;
	dc << "\n";
	dc << "m_cxStateImageOffset = " << m_cxStateImageOffset;
	dc << "\n";
}

#endif //_DEBUG

/////////////////////////////////////////////////////////////////////////////
// CListViewEx ���b�Z�[�W �n���h��

LRESULT CListViewEx::OnSetImageList(WPARAM wParam, LPARAM lParam)
{
	// Windows �̃o�[�W������ 4 �ȏ�ł���΃A�C�e�� �e�L�X�g�̈ʒu��
	// �I�t�Z�b�g��������K�v�͂���܂���B

	OSVERSIONINFO info;
	info.dwOSVersionInfoSize = sizeof(info);
	VERIFY(::GetVersionEx(&info));

	if( (int) wParam == LVSIL_STATE && info.dwMajorVersion < 4)
	{
		int cx, cy;

		if(::ImageList_GetIconSize((HIMAGELIST)lParam, &cx, &cy))
			m_cxStateImageOffset = cx;
		else
			m_cxStateImageOffset = 0;
	}

	return(Default());
}

LRESULT CListViewEx::OnSetTextColor(WPARAM /*wParam*/, LPARAM lParam)
{
	m_clrText = (COLORREF)lParam;
	return(Default());
}

LRESULT CListViewEx::OnSetTextBkColor(WPARAM /*wParam*/, LPARAM lParam)
{
	m_clrTextBk = (COLORREF)lParam;
	return(Default());
}

LRESULT CListViewEx::OnSetBkColor(WPARAM /*wParam*/, LPARAM lParam)
{
	m_clrBkgnd = (COLORREF)lParam;
	return(Default());
}

void CListViewEx::OnSize(UINT nType, int cx, int cy)
{
	m_cxClient = cx;
	CListView::OnSize(nType, cx, cy);
}

void CListViewEx::OnPaint()
{
	// �s�S�̂̑I�����[�h�ł́A�N���b�v���Ă���̈���g������K�v������܂��B
	// ����ɂ���đI�𕔕��𐳂����h��Ԃ����Ƃ��ł��܂��B
	if (m_bClientWidthSel &&
		(GetStyle() & LVS_TYPEMASK) == LVS_REPORT &&
		GetFullRowSel())
	{
		CRect rcAllLabels;
		GetLixxxtrl().GetItemRect(0, rcAllLabels, LVIR_BOUNDS);

		if(rcAllLabels.right < m_cxClient)
		{
			// �������N���b�v�̈���擾���邽�߂� BeginPaint (CPaintDC c-tor ����)
			// ���Ăяo���K�v������܂��B
			CPaintDC dc(this);

			CRect rcClip;
			dc.GetClipBox(rcClip);

			rcClip.left = min(rcAllLabels.right-1, rcClip.left);
			rcClip.right = m_cxClient;

			InvalidateRect(rcClip, FALSE);
			// EndPaint �� CPaintDC d-tor ���ŌĂяo����܂��B
		}
	}

	CListView::OnPaint();
}

void CListViewEx::OnSetFocus(CWnd* pOldWnd)
{
	CListView::OnSetFocus(pOldWnd);

	// ���x�� �G�f�B�b�g �{�b�N�X����t�H�[�J�X���擾�����ǂ������m�F���܂��B
	if(pOldWnd!=NULL && pOldWnd->GetParent()==this)
		return;

	// �A�C�e�����ĕ`�悵�ĕύX��\�����܂��B
	if(m_bFullRowSel && (GetStyle() & LVS_TYPEMASK)==LVS_REPORT)
		RepaintSelectedItems();
}

void CListViewEx::OnKillFocus(CWnd* pNewWnd)
{
	CListView::OnKillFocus(pNewWnd);

	// ���x�� �G�f�B�b�g �{�b�N�X�ւ̃t�H�[�J�X�������Ă��邩�ǂ������m�F���܂��B
	if(pNewWnd != NULL && pNewWnd->GetParent() == this)
		return;

	// �A�C�e�����ĕ`�悵�ĕύX��\�����܂��B
	if(m_bFullRowSel && (GetStyle() & LVS_TYPEMASK) == LVS_REPORT)
		RepaintSelectedItems();
}

void CListViewEx::SetItemColor(int nItem, int nColor)
{
	LVITEM lvi;

	lvi.mask = LVIF_INDENT;
	lvi.iItem = nItem;
	lvi.iSubItem = 0;
	lvi.iIndent = nColor;
	GetLixxxtrl().SetItem(&lvi);
}
