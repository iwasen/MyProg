/**
 * @file	InPlaceEdit.h
 * @brief	CInPlaceEditクラスのインターフェイス
 *
 * @author	K.kimura (xxxxx, Inc.)
 * @date	2005-08-18
 * @version	InPlaceEdit.h, v 1.1 2005/08/18 $
 *			Copyright(C) 2005 xxxxx, Inc. All rights reserved.
 */

#if !defined(AFX_INPLACEEDIT_H__5834B600_4FAF_4275_9207_60AEE0B675B7__INCLUDED_)
#define AFX_INPLACEEDIT_H__5834B600_4FAF_4275_9207_60AEE0B675B7__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000
// InPlaceEdit.h : ヘッダー ファイル
//

/////////////////////////////////////////////////////////////////////////////
// CInPlaceEdit ウィンドウ

class CInPlaceEdit : public CEdit
{
// コンストラクション
public:
	CInPlaceEdit();		/// コンストラクタ
	CInPlaceEdit(int iItem, int iSubItem, CString sInitText); /// コンストラクタ

// アトリビュート
public:

// オペレーション
public:

// オーバーライド
	// ClassWizard は仮想関数のオーバーライドを生成します。
	//{{AFX_VIRTUAL(CInPlaceEdit)
	public:
	virtual BOOL PreTranslateMessage(MSG* pMsg);	/// メッセージ変換前処理
	//}}AFX_VIRTUAL

// インプリメンテーション
public:
	virtual ~CInPlaceEdit();		/// デストラクタ

	// 生成されたメッセージ マップ関数
protected:
	//{{AFX_MSG(CInPlaceEdit)
		// メモ - ClassWizard はこの位置にメンバ関数を追加または削除します。
	afx_msg void OnKillFocus(CWnd* pNewWnd);					/// 入力フォーカス消失前処理
	afx_msg void OnNcDestroy();									/// クライアント領域破棄処理
	afx_msg void OnChar(UINT nChar, UINT nRepCnt, UINT nFlags);	/// キー入力変換処理
	afx_msg int OnCreate(LPCREATESTRUCT lpCreateStruct);		/// オブジェクト作成時処理
	//}}AFX_MSG

	DECLARE_MESSAGE_MAP()
private:
	int m_iItem;			/// ラベル行番号
	int m_iSubItem;			/// ラベル列番号
	CString m_sInitText;	/// 初期化文字列
	BOOL    m_bESC;	 	// To indicate whether ESC key was pressed
};

/////////////////////////////////////////////////////////////////////////////

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ は前行の直前に追加の宣言を挿入します。

#endif // !defined(AFX_INPLACEEDIT_H__5834B600_4FAF_4275_9207_60AEE0B675B7__INCLUDED_)
