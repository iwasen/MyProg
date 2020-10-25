// LBView.cpp : インプリメンテーション ファイル
//

#include "stdafx.h"
#include "PcVideo.h"
#include "LBView.h"
#include "pcvcomm.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

/////////////////////////////////////////////////////////////////////////////
// CLBView

CLBView::CLBView()
{
	m_brushWindow.CreateSolidBrush(GetSysColor(COLOR_WINDOW));
	m_brushHighLight.CreateSolidBrush(GetSysColor(COLOR_HIGHLIGHT));

	m_penWindowText.CreatePen(PS_SOLID, 1, GetSysColor(COLOR_WINDOWTEXT));
	m_penHighLightText.CreatePen(PS_SOLID, 1, GetSysColor(COLOR_HIGHLIGHTTEXT));

	m_bitmapVideo.LoadBitmap(IDB_VIDEO);
	m_bitmapVideoMask.LoadBitmap(IDB_VIDEO_MASK);

	m_bitmapChat.LoadBitmap(IDB_CHAT);
	m_bitmapChatMask.LoadBitmap(IDB_CHAT_MASK);

	m_bitmapVoice.LoadBitmap(IDB_VOICE);
	m_bitmapVoiceMask.LoadBitmap(IDB_VOICE_MASK);

	m_bitmapReflect.LoadBitmap(IDB_REFLECT);
	m_bitmapReflectMask.LoadBitmap(IDB_REFLECT_MASK);

	m_bitmapDll.LoadBitmap(IDB_DLL);
	m_bitmapDllMask.LoadBitmap(IDB_DLL_MASK);

	m_bitmapNone.LoadBitmap(IDB_NONE);
	m_bitmapNoneMask.LoadBitmap(IDB_NONE_MASK);

	m_dc.CreateCompatibleDC(NULL);
}

CLBView::~CLBView()
{
}


BEGIN_MESSAGE_MAP(CLBView, CListBox)
	//{{AFX_MSG_MAP(CLBView)
	//}}AFX_MSG_MAP
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CLBView メッセージ ハンドラ

void CLBView::DrawItem(LPDRAWITEMSTRUCT lpDrawItemStruct) 
{
	PUSER	pUser = (PUSER)lpDrawItemStruct->itemData;
	char	ipaddress[20];
	char	chNo[10];
	char	buf[128];
	CDC	dc;
	TEXTMETRIC tm;
	int	left, top;
	BOOL	bMode;
	CPen	*pOldPen;

	if (pUser == NULL)
		return;

	if (lpDrawItemStruct->itemAction == ODA_FOCUS)
		return;

	dc.Attach(lpDrawItemStruct->hDC);
	dc.GetTextMetrics(&tm);
	dc.SetBkMode(TRANSPARENT);
	if (lpDrawItemStruct->itemState & ODS_SELECTED) {
		dc.FillRect(&lpDrawItemStruct->rcItem, &m_brushHighLight);
		pOldPen = dc.SelectObject(&m_penHighLightText);
		dc.SetTextColor(GetSysColor(COLOR_HIGHLIGHTTEXT));
	} else {
		dc.FillRect(&lpDrawItemStruct->rcItem, &m_brushWindow);
		pOldPen = dc.SelectObject(&m_penWindowText);
		dc.SetTextColor(GetSysColor(COLOR_WINDOWTEXT));
	}

	left = lpDrawItemStruct->rcItem.left;
	top = lpDrawItemStruct->rcItem.top;

	switch (pUser->indent) {
	case 1:
		dc.MoveTo(8, top);
		dc.LineTo(8, top + m_itemHeight);
		dc.MoveTo(8, top + 8);
		dc.LineTo(15, top + 8);
		left += 16;
		break;
	case 2:
		dc.MoveTo(8, top);
		dc.LineTo(8, top + 8);
		dc.MoveTo(8, top + 8);
		dc.LineTo(15, top + 8);
		left += 16;
		break;
	}

	bMode = FALSE;
	if (pUser->modeFlag & MODE_REFLECT) {
		m_dc.SelectObject(&m_bitmapReflectMask);
		dc.BitBlt(left, top, 16, 16, &m_dc, 0, 0, MERGEPAINT);
		m_dc.SelectObject(&m_bitmapReflect);
		dc.BitBlt(left, top, 16, 16, &m_dc, 0, 0, SRCAND);
		left += 16;
		bMode = TRUE;
	}
	if (pUser->modeFlag & MODE_SENDVIDEO) {
		m_dc.SelectObject(&m_bitmapVideoMask);
		dc.BitBlt(left, top, 16, 16, &m_dc, 0, 0, MERGEPAINT);
		m_dc.SelectObject(&m_bitmapVideo);
		dc.BitBlt(left, top, 16, 16, &m_dc, 0, 0, SRCAND);
		left += 16;
		bMode = TRUE;
	}
	if (pUser->modeFlag & MODE_CHAT) {
		m_dc.SelectObject(&m_bitmapChatMask);
		dc.BitBlt(left, top, 16, 16, &m_dc, 0, 0, MERGEPAINT);
		m_dc.SelectObject(&m_bitmapChat);
		dc.BitBlt(left, top, 16, 16, &m_dc, 0, 0, SRCAND);
		left += 16;
		bMode = TRUE;
	}
	if (pUser->modeFlag & MODE_SENDVOICE) {
		m_dc.SelectObject(&m_bitmapVoiceMask);
		dc.BitBlt(left, top, 16, 16, &m_dc, 0, 0, MERGEPAINT);
		m_dc.SelectObject(&m_bitmapVoice);
		dc.BitBlt(left, top, 16, 16, &m_dc, 0, 0, SRCAND);
		left += 16;
		bMode = TRUE;
	}
	if (pUser->modeFlag & MODE_DLLCALL) {
		m_dc.SelectObject(&m_bitmapDllMask);
		dc.BitBlt(left, top, 16, 16, &m_dc, 0, 0, MERGEPAINT);
		m_dc.SelectObject(&m_bitmapDll);
		dc.BitBlt(left, top, 16, 16, &m_dc, 0, 0, SRCAND);
		left += 16;
		bMode = TRUE;
	}
	if (!bMode) {
		m_dc.SelectObject(&m_bitmapNoneMask);
		dc.BitBlt(left, top, 16, 16, &m_dc, 0, 0, MERGEPAINT);
		m_dc.SelectObject(&m_bitmapNone);
		dc.BitBlt(left, top, 16, 16, &m_dc, 0, 0, SRCAND);
		left += 16;
	}

	left += 4;

	if (iniData.mainWindow.bDispAddress) {
		wsprintf(ipaddress, "%s ", inet_ntoa(*(struct in_addr *)&pUser->id));
	} else
		ipaddress[0] = '\0';

	if (pUser->indent == 0)
		wsprintf(chNo, "[%d] ", pUser->channelNo);
	else
		chNo[0] = '\0';

	wsprintf(buf, "%s%s%s", (LPSTR)chNo,
				(LPSTR)ipaddress,
				(LPSTR)pUser->userName);
	if (16 - tm.tmHeight > 0)
		top += (16 - tm.tmHeight) / 2;
	dc.TextOut(left, top, buf, strlen(buf));

	dc.SelectObject(pOldPen);
	dc.Detach();
}

void CLBView::MeasureItem(LPMEASUREITEMSTRUCT lpMeasureItemStruct) 
{
	CDC	*pDC;
	TEXTMETRIC tm;

	pDC = GetDC();
	pDC->GetTextMetrics(&tm);
	m_itemHeight = tm.tmHeight;
	if (m_itemHeight < 16)
		m_itemHeight = 16;
	lpMeasureItemStruct->itemHeight = m_itemHeight;
	ReleaseDC(pDC);
}

BOOL CLBView::PreCreateWindow(CREATESTRUCT& cs) 
{
	cs.dwExStyle |= WS_EX_CLIENTEDGE;
	
	return CListBox::PreCreateWindow(cs);
}
