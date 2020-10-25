//*****************************************************************************************************
//  1. ファイル名
//		ColorButton.h
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		CColorButton クラスのインターフェイス
//----------------------------------------------------------------------------------------------------
//  3. 備考
//----------------------------------------------------------------------------------------------------
//  4. 履歴
//		2004.12.16 S.Imura(xxxxx) 新規作成
//*****************************************************************************************************
#if !defined(AFX_COLORBUTTON_H__31FBC88C_5C07_4796_9906_0E5A96EBF1C3__INCLUDED_)
#define AFX_COLORBUTTON_H__31FBC88C_5C07_4796_9906_0E5A96EBF1C3__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000
// ColorButton.h : ヘッダー ファイル
//

/////////////////////////////////////////////////////////////////////////////
// CColorButton ウィンドウ
//
// カラーボタン定義ファイル

class CColorButton : public CButton
{
	DECLARE_DYNAMIC(CColorButton)

private:
	COLORREF	m_fg;		// キャプションカラー(通常時)
	COLORREF	m_bg;		// 背景色
	COLORREF	m_disabled;	// ボタンが無効時のキャプションカラー
	int m_checkState;		// チェックボタン時 チェックOn/Off

	CFont m_Font;
	float m_fSizeRatio;		// フォントサイズ係数
	long  m_lfWeight;		// フォント太さ LOGFONT構造体を参照の事

// コンストラクション
public:
	CColorButton();

// アトリビュート
public:
	void SetTextColor(const COLORREF color){m_fg = color;}		// キャプションカラー設定
	void SetBackGroundColor(const COLORREF color){m_bg = color;}	// 背景色設定
	void SetDisabledColor(const COLORREF color){m_disabled = color;}	// ボタン無効時のキャプションカラー設定
	COLORREF GetTextColor() { return m_fg; }				// キャプションカラー取得
	COLORREF GetBackGroundColor() { return m_bg; }		// 背景色取得
	COLORREF GetDisabledColor() { return m_disabled; }			// ボタン無効時のキャプションカラー取得

// オペレーション
public:
	virtual void SetCheck(int nCheck){m_checkState = nCheck;};
	virtual int GetCheck(){return m_checkState;}

protected:
	virtual void DrawItem(LPDRAWITEMSTRUCT lpDIS);			// ボタンのオーナー描画
	void DrawButtonText(CDC *DC, CRect R, const char *Buf, COLORREF TextColor);	// テキスト描画

// オーバーライド
	// ClassWizard は仮想関数のオーバーライドを生成します。
	//{{AFX_VIRTUAL(CColorButton)
	protected:
	virtual void PreSubclassWindow();
	//}}AFX_VIRTUAL

// インプリメンテーション
public:
	virtual ~CColorButton();

	// 生成されたメッセージ マップ関数
protected:
	//{{AFX_MSG(CColorButton)
	afx_msg int OnCreate(LPCREATESTRUCT lpCreateStruct);
	//}}AFX_MSG
	afx_msg LRESULT OnSetCheck(WPARAM wParam, LPARAM lParam);

	DECLARE_MESSAGE_MAP()
};

/////////////////////////////////////////////////////////////////////////////

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ は前行の直前に追加の宣言を挿入します。

#endif // !defined(AFX_COLORBUTTON_H__31FBC88C_5C07_4796_9906_0E5A96EBF1C3__INCLUDED_)
