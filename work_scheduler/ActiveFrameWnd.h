#if !defined(AFX_ACTIVEFRAMEWND_H__68AEA6AC_893D_43B5_98F2_057EE52B6713__INCLUDED_)
#define AFX_ACTIVEFRAMEWND_H__68AEA6AC_893D_43B5_98F2_057EE52B6713__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000
// ActiveFrameWnd.h : ヘッダー ファイル
//

/////////////////////////////////////////////////////////////////////////////
// CActiveFrameWnd ウィンドウ

class CActiveFrameWnd : public CWnd
{
// コンストラクション
public:

// アトリビュート
public:

// オペレーション
public:

// オーバーライド
	// ClassWizard は仮想関数のオーバーライドを生成します。

	//{{AFX_VIRTUAL(CActiveFrameWnd)
	//}}AFX_VIRTUAL

// インプリメンテーション
public:

	// 生成されたメッセージ マップ関数
protected:
	//{{AFX_MSG(CActiveFrameWnd)
	afx_msg BOOL OnEraseBkgnd(CDC* pDC);
	afx_msg void OnPaint();
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()

	CPen m_penAvtiveFrame;
};

/////////////////////////////////////////////////////////////////////////////

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ は前行の直前に追加の宣言を挿入します。

#endif // !defined(AFX_ACTIVEFRAMEWND_H__68AEA6AC_893D_43B5_98F2_057EE52B6713__INCLUDED_)
