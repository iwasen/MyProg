#pragma once

// MMLibTextView.h : ヘッダー ファイル
//

/////////////////////////////////////////////////////////////////////////////
// CMMLibTextView ビュー

class CMMLibTextView : public CEditView
{
protected:
	CMMLibTextView();           // 動的生成に使用されるプロテクト コンストラクタ
	DECLARE_DYNCREATE(CMMLibTextView)

// アトリビュート
public:

// オペレーション
public:

// オーバーライド
	// ClassWizard は仮想関数のオーバーライドを生成します。

	public:
	virtual DROPEFFECT OnDragOver(COleDataObject* pDataObject, DWORD dwKeyState, CPoint point);
	virtual BOOL OnDrop(COleDataObject* pDataObject, DROPEFFECT dropEffect, CPoint point);
	protected:
	virtual void OnDraw(CDC* pDC);      // このビューを描画するためにオーバーライドしました。
	virtual BOOL PreCreateWindow(CREATESTRUCT& cs);
	virtual void OnUpdate(CView* pSender, LPARAM lHint, CObject* pHint);

// インプリメンテーション
protected:
	virtual ~CMMLibTextView();
#ifdef _DEBUG
	virtual void AssertValid() const;
	virtual void Dump(CDumpContext& dc) const;
#endif

	// 生成されたメッセージ マップ関数
protected:
	afx_msg int OnCreate(LPCREATESTRUCT lpCreateStruct);
	afx_msg void OnChange();
	afx_msg void OnSize(UINT nType, int cx, int cy);
	afx_msg LRESULT OnGetSelectedItem(WPARAM wParam, LPARAM lParam);
	DECLARE_MESSAGE_MAP()

private:
	CItem *m_pCurrentItem;
	CFont m_Font;
	COleDropTarget m_DropTarget;

	void SaveData(CItem *pItem);
	void DrawItems();
	void DrawFolderText();
	void DrawDataText();
	void DrawOtherText();
	void SetViewFont();
	void LoadTextFile(CFile *pFile);
	BOOL DropFile(COleDataObject* pDataObject, DROPEFFECT dropEffect, CPoint point);
public:
	virtual void OnInitialUpdate();
};
