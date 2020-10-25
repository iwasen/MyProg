#pragma once

// FindDoc.h : ヘッダー ファイル
//

#include "FindFrame.h"

/////////////////////////////////////////////////////////////////////////////
// CFindDoc ドキュメント

class CFindDoc : public CDocument
{
protected:
	CFindDoc();           // 動的生成に使用されるプロテクト コンストラクタ。
	DECLARE_DYNCREATE(CFindDoc)

// アトリビュート
public:

// オペレーション
public:

// オーバーライド
	// ClassWizard は仮想関数のオーバーライドを生成します。
	public:
	virtual void Serialize(CArchive& ar);   // ドキュメント I/O に対してオーバーライドされます。
	virtual BOOL OnOpenDocument(LPCTSTR lpszPathName);
	protected:
	virtual BOOL OnNewDocument();

// インプリメンテーション
public:
	virtual ~CFindDoc();
#ifdef _DEBUG
	virtual void AssertValid() const;
	virtual void Dump(CDumpContext& dc) const;
#endif

	// メッセージ マップ関数の生成
protected:
	afx_msg void OnCloseFind();
	afx_msg void OnMakeIndex();
	afx_msg void OnFind();
	DECLARE_MESSAGE_MAP()

public:
	CFindFrame *m_pFindFrame;
	CMMLibDoc *m_pMMLibDoc;
	CString m_sKeyword;
	int m_nAndOr;
};
