// HeaderCtrlEx.cpp : インプリメンテーション ファイル
//

#include "stdafx.h"
#include "HeaderCtrlEx.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

#define HEADERTEXT_MAX	256

/////////////////////////////////////////////////////////////////////////////
// CHeaderCtrlEx

CHeaderCtrlEx::CHeaderCtrlEx()
{
}

CHeaderCtrlEx::~CHeaderCtrlEx()
{
}


BEGIN_MESSAGE_MAP(CHeaderCtrlEx, CHeaderCtrl)
	//{{AFX_MSG_MAP(CHeaderCtrlEx)
		// メモ - ClassWizard はこの位置にマッピング用のマクロを追加または削除します。
	//}}AFX_MSG_MAP
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CHeaderCtrlEx メッセージ ハンドラ

void CHeaderCtrlEx::DrawItem( LPDRAWITEMSTRUCT lpDrawItemStruct )
{

   ASSERT(lpDrawItemStruct->CtlType == ODT_HEADER);

   HDITEM hdi;
   TCHAR  lpBuffer[HEADERTEXT_MAX];

   hdi.mask = HDI_TEXT;
   hdi.pszText = lpBuffer;
   hdi.cchTextMax = HEADERTEXT_MAX;

   GetItem(lpDrawItemStruct->itemID, &hdi);

   	
	CDC* pDC;
	pDC = CDC::FromHandle(lpDrawItemStruct->hDC);

	//THIS FONT IS ONLY FOR DRAWING AS LONG AS WE DON'T DO A SetFont(...)
	pDC->SelectObject(GetStockObject(DEFAULT_GUI_FONT));
   // Draw the button frame.

	lpDrawItemStruct->rcItem.bottom += 30;
   ::DrawFrameControl(lpDrawItemStruct->hDC, 
      &lpDrawItemStruct->rcItem, DFC_BUTTON, DFCS_BUTTONPUSH);


	UINT uFormat = DT_CENTER;
	//DRAW THE TEXT
	lpDrawItemStruct->rcItem.top += 3;
   ::DrawText(lpDrawItemStruct->hDC, lpBuffer, strlen(lpBuffer), 
      &lpDrawItemStruct->rcItem, uFormat);

   pDC->SelectStockObject(SYSTEM_FONT);

}