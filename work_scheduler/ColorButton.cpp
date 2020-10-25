//*****************************************************************************************************
//  1. ファイル名
//		ColorButton.cpp
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		CColorButton クラスのインプリメンテーション
//----------------------------------------------------------------------------------------------------
//  3. 備考
//----------------------------------------------------------------------------------------------------
//  4. 履歴
//		2004.12.16 S.Imura(xxxxx) 新規作成
//*****************************************************************************************************

#include "stdafx.h"
#include "Control.h"
#include "ColorButton.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

IMPLEMENT_DYNAMIC(CColorButton, CButton)

/////////////////////////////////////////////////////////////////////////////
// CColorButton

CColorButton::CColorButton()
{
	// ディフォルトカラー
	m_fg = ::GetSysColor(COLOR_BTNTEXT);
	m_bg = ::GetSysColor(COLOR_BTNFACE);
	m_disabled = ::GetSysColor(COLOR_GRAYTEXT);

	m_checkState = 0;

	m_fSizeRatio = 1.4f;
	m_lfWeight = FW_SEMIBOLD;
}

CColorButton::~CColorButton()
{
}


BEGIN_MESSAGE_MAP(CColorButton, CButton)
	//{{AFX_MSG_MAP(CColorButton)
	ON_WM_CREATE()
	//}}AFX_MSG_MAP
	ON_MESSAGE(BM_SETCHECK, OnSetCheck)
END_MESSAGE_MAP()

//*****************************************************************************************************
//  1. 関数名
//		DrawItem
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		ボタン描画処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		LPDRAWITEMSTRUCT lpDIS	[I]
//----------------------------------------------------------------------------------------------------
//　4. 戻り値
//----------------------------------------------------------------------------------------------------
//　5. 処理概要
//		ボタンの描画
//			・ボタン背景の塗潰し
//			・フォーカス時の点線描画
//			・ボタン縁の描画
//			・テキストの描画
//----------------------------------------------------------------------------------------------------
//　6. 備考
//		オーナー描画のボタンの表示上の外観が変化すると、フレームワークが呼び出します
//		CButton::DrawItem参照
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2004.12.16 S.Imura(xxxxx) 新規作成
//*****************************************************************************************************
void CColorButton::DrawItem(LPDRAWITEMSTRUCT lpDIS)
{
	CDC* pDC = CDC::FromHandle(lpDIS->hDC);

	UINT state = lpDIS->itemState;
	CRect btnRect;
	btnRect.CopyRect(&lpDIS->rcItem);

	//
	// ボタン塗潰し
	//
	bool bStateButton = false;
	DWORD style = GetStyle();

	if( (style&BS_AUTOCHECKBOX)||(style&BS_AUTORADIOBUTTON)||(style&BS_AUTO3STATE)||
		(style&BS_CHECKBOX)||(style&BS_RADIOBUTTON)||(style&&BS_3STATE) )
	{
		bStateButton = true;
	}

	if(bStateButton && GetCheck()==1)
	{
		int rcol = GetRValue(GetBackGroundColor());
		int gcol = GetGValue(GetBackGroundColor());
		int bcol = GetBValue(GetBackGroundColor());
		rcol += 127;
		gcol += 127;
		bcol += 127;
		if(rcol>255) rcol = 255;
		if(gcol>255) gcol = 255;
		if(bcol>255) bcol = 255;
		pDC->FillSolidRect(&btnRect, RGB(rcol,gcol,bcol));
	}
	else
	{
		pDC->FillSolidRect(&btnRect, GetBackGroundColor());
	}

	//
	// フォーカス時の処理
	//
	if (state & ODS_FOCUS)
	{
		CRect focusRect(btnRect);
		focusRect.DeflateRect(4,4);
		pDC->DrawFocusRect(&focusRect);

		pDC->Draw3dRect(btnRect, RGB(0,0,0), RGB(0,0,0));
	}


	// 縁の描画
	if (style & BS_FLAT)	// 平面ボタン
	{
		pDC->Draw3dRect(&btnRect, RGB(0,0,0), RGB(0,0,0));

		BYTE rcol = (GetRValue(GetBackGroundColor())+255)/2;
		BYTE gcol = (GetGValue(GetBackGroundColor())+255)/2;
		BYTE bcol = (GetBValue(GetBackGroundColor())+255)/2;

		CRect wkRect = btnRect;
		wkRect.DeflateRect(2,2);
		pDC->Draw3dRect(&wkRect, RGB(rcol, gcol, bcol), RGB(rcol, gcol, bcol));
	}
	else				// 立体ボタン
	{
		if (state & ODS_SELECTED)
		{
			pDC->Draw3dRect(&btnRect, RGB(0,0,0), RGB(0,0,0));

		}
		else
		{
			if(bStateButton && GetCheck())
			{
				pDC->Draw3dRect(&btnRect, RGB(0,0,0), RGB(255,255,255));
			}
			else
			{
				pDC->Draw3dRect(&btnRect, RGB(255,255,255), RGB(0,0,0));
			}

			BYTE rcol = GetRValue(GetBackGroundColor())/2;
			BYTE gcol = GetGValue(GetBackGroundColor())/2;
			BYTE bcol = GetBValue(GetBackGroundColor())/2;

			CRect wkRect = btnRect;
			wkRect.DeflateRect(1,1);
			pDC->Draw3dRect(&wkRect, RGB(127+rcol,127+gcol,127+bcol), RGB(rcol,gcol,bcol));

		}
	}

	//
	// テキストの描画
	//
    const int bufSize = 512;
    TCHAR buffer[bufSize];
    GetWindowText(buffer, bufSize);

	CRect textRect(btnRect);
	COLORREF textcolor = GetTextColor();
	if (state & ODS_SELECTED)
	{
		// ボタン押下時のテキスト矩形
		textRect.DeflateRect(2,2,0,0);
	}
	if (state & ODS_DISABLED)
	{
		// 少し ズラシて白色でテキスト描画（立体的に）
		CRect wkRect(textRect);
		wkRect.OffsetRect(1,1);
		DrawButtonText(pDC, wkRect, buffer, RGB(224,224,224));
		textcolor = GetDisabledColor();
    }
	DrawButtonText(pDC, textRect, buffer, textcolor);
}

//*****************************************************************************************************
//  1. 関数名
//		DrawButtonText
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		ボタンテキスト描画処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CDC			*DC			[I]		デバイスコンテキストへのポインタ
//		CRect		R			[I]		テキストの位置
//		char		*Buf		[I]		テキスト
//		COLORREF	TextColor	[I]		テキスト色
//----------------------------------------------------------------------------------------------------
//　4. 戻り値
//----------------------------------------------------------------------------------------------------
//　5. 処理概要
//		ボタンテキストの描画
//----------------------------------------------------------------------------------------------------
//　6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2004.12.16 S.Imura(xxxxx) 新規作成
//*****************************************************************************************************
void CColorButton::DrawButtonText(CDC *DC, CRect R, const char *Buf, COLORREF TextColor)
{
    COLORREF prevColor = DC->SetTextColor(TextColor);
    DC->SetBkMode(TRANSPARENT);
	DC->DrawText(Buf, strlen(Buf), R, DT_CENTER|DT_VCENTER|DT_SINGLELINE);
	DC->SetTextColor(prevColor);
}

/////////////////////////////////////////////////////////////////////////////
// CColorButton メッセージ ハンドラ

//*****************************************************************************************************
//  1. 関数名
//		PreSubclassWindow
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		サブクラス化 前処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//----------------------------------------------------------------------------------------------------
//　4. 戻り値
//----------------------------------------------------------------------------------------------------
//　5. 処理概要
//		ボタンスタイルにオーナードローを追加
//----------------------------------------------------------------------------------------------------
//　6. 備考
//		ウィンドウがサブクラス化する前に フレームワークより呼出されます
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2004.12.16 S.Imura(xxxxx) 新規作成
//*****************************************************************************************************
void CColorButton::PreSubclassWindow()
{
	DWORD style = GetStyle();

	// オーナードロースタイルを設定
	if( ( style & BS_OWNERDRAW ) != BS_OWNERDRAW)
	{
		ModifyStyle( 0, BS_OWNERDRAW);
	}

	// フォントを 大きく 太文字にする
	CFont* pFont2 = GetFont();
	LOGFONT logfont;
	pFont2->GetLogFont(&logfont);
	logfont.lfHeight = (long)(logfont.lfHeight*m_fSizeRatio);
	logfont.lfWeight = m_lfWeight;
	m_Font.CreateFontIndirect(&logfont);

	SetFont(&m_Font, TRUE);

	CButton::PreSubclassWindow();
}

//*****************************************************************************************************
//  1. 関数名
//		OnCreate
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		WM_CREATEメッセージ通知処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//----------------------------------------------------------------------------------------------------
//　4. 戻り値
//----------------------------------------------------------------------------------------------------
//　5. 処理概要
//----------------------------------------------------------------------------------------------------
//　6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2004.12.16 S.Imura(xxxxx) 新規作成
//*****************************************************************************************************
int CColorButton::OnCreate(LPCREATESTRUCT lpCreateStruct)
{
	if (CButton::OnCreate(lpCreateStruct) == -1)
		return -1;

	// TODO: この位置に固有の作成用コードを追加してください

	return 0;
}

//*****************************************************************************************************
//  1. 関数名
//		OnSetCheck
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		BM_SETCHECK メッセージ通知処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		WPARAM wParam	[I] チェック状態
//		LPARAM lParam	[I] 0 未使用
//----------------------------------------------------------------------------------------------------
//　4. 戻り値
//		LRESULT 常時0を返答
//----------------------------------------------------------------------------------------------------
//　5. 処理概要
//----------------------------------------------------------------------------------------------------
//　6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2004.12.16 S.Imura(xxxxx) 新規作成
//*****************************************************************************************************
LRESULT CColorButton::OnSetCheck(WPARAM wParam, LPARAM lParam)
{
	if(wParam == BST_CHECKED)
	{
		m_checkState = 1;
	}
	else if(wParam == BST_INDETERMINATE)
	{
		m_checkState = 2;
	}
	else if(wParam == BST_UNCHECKED)
	{
		m_checkState = 0;
	}

	return 0;
}
