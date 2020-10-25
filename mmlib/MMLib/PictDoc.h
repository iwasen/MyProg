#pragma once

// PictDoc.h : ヘッダー ファイル
//

#include "PictFrame.h"

/////////////////////////////////////////////////////////////////////////////
// CPictDoc ドキュメント

class CPictDoc : public CDocument
{
protected:
	CPictDoc();           // 動的生成に使用されるプロテクト コンストラクタ。
	DECLARE_DYNCREATE(CPictDoc)

// アトリビュート
public:

// オペレーション
public:

// オーバーライド
	// ClassWizard は仮想関数のオーバーライドを生成します。
	public:
	virtual void Serialize(CArchive& ar);   // ドキュメント I/O に対してオーバーライドされます。
	virtual BOOL OnOpenDocument(LPCTSTR lpszPathName);
	virtual void SetPathName(LPCTSTR lpszPathName, BOOL bAddToMRU = TRUE);
	virtual BOOL OnCmdMsg(UINT nID, int nCode, void* pExtra, AFX_CMDHANDLERINFO* pHandlerInfo);
	protected:
	virtual BOOL OnNewDocument();

// インプリメンテーション
public:
	virtual ~CPictDoc();
#ifdef _DEBUG
	virtual void AssertValid() const;
	virtual void Dump(CDumpContext& dc) const;
#endif

	// メッセージ マップ関数の生成
protected:
	afx_msg void OnPictAutosize();
	afx_msg void OnPictAutosize2();
	afx_msg void OnPictEnlarge();
	afx_msg void OnPictReduce();
	afx_msg void OnPictLeftRotate();
	afx_msg void OnPictRightRotate();
	afx_msg void OnUpdatePictAutosize(CCmdUI* pCmdUI);
	afx_msg void OnUpdatePictAutosize2(CCmdUI* pCmdUI);
	afx_msg void OnClosePict();
	afx_msg void OnSelchangeCombo();
	afx_msg void OnEditchangeCombo();
	DECLARE_MESSAGE_MAP()

public:
	HDIB m_hDIB;
	int m_nAutoSize;
	double m_fPictRatio;

	void ShowPicture(LPCTSTR pTitle, HDIB hDIB, int nAutoSize, double fPictRatio);

private:
	CPictFrame *GetPictFrame();
};
