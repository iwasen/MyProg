#pragma once

// PictFrame.h : ヘッダー ファイル
//

/////////////////////////////////////////////////////////////////////////////
// CPictFrame フレーム

class CPictFrame : public CFrameWnd
{
	DECLARE_DYNCREATE(CPictFrame)
protected:
	CPictFrame();           // 動的生成に使用されるプロテクト コンストラクタ。

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
	virtual ~CPictFrame();

	// 生成されたメッセージ マップ関数
	afx_msg int OnCreate(LPCREATESTRUCT lpCreateStruct);
	DECLARE_MESSAGE_MAP()

public:
	CComboBox &GetComboBox() {return m_wndComboBox;}

private:
	CMyToolBar m_wndPictToolBar;
	CComboBox m_wndComboBox;
};
