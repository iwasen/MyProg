#pragma once

// FindFrame.h : ヘッダー ファイル
//

/////////////////////////////////////////////////////////////////////////////
// CFindFrame フレーム

class CFindFrame : public CFrameWnd
{
	DECLARE_DYNCREATE(CFindFrame)
protected:
	CFindFrame();           // 動的生成に使用されるプロテクト コンストラクタ。

// アトリビュート
public:

// オペレーション
public:

// オーバーライド
	// ClassWizard は仮想関数のオーバーライドを生成します。
	protected:
	virtual BOOL PreCreateWindow(CREATESTRUCT& cs);

// インプリメンテーション
protected:
	virtual ~CFindFrame();

	// 生成されたメッセージ マップ関数
	afx_msg void OnClose();
	afx_msg int OnCreate(LPCREATESTRUCT lpCreateStruct);
	DECLARE_MESSAGE_MAP()

public:
	CReBar m_wndReBar;
	CEdit m_wndKeyword;
	CButton m_wndFind;
};
