#if !defined(AFX_MMTEXTVIEW_H__F30C0803_5617_4073_B422_381EC1D417BB__INCLUDED_)
#define AFX_MMTEXTVIEW_H__F30C0803_5617_4073_B422_381EC1D417BB__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000
// MMTextView.h : ヘッダー ファイル
//

/////////////////////////////////////////////////////////////////////////////
// CMMTextView ビュー

class CMMTextView : public CEditView
{
protected:
	CMMTextView();           // 動的生成に使用されるプロテクト コンストラクタ
	DECLARE_DYNCREATE(CMMTextView)

// アトリビュート
public:

// オペレーション
public:

// オーバーライド
	// ClassWizard は仮想関数のオーバーライドを生成します。

	public:
	virtual void OnInitialUpdate();
	protected:
	virtual void OnDraw(CDC* pDC);      // このビューを描画するためにオーバーライドしました。
	virtual BOOL PreCreateWindow(CREATESTRUCT& cs);
	virtual void OnUpdate(CView* pSender, LPARAM lHint, CObject* pHint);

// インプリメンテーション
protected:
	virtual ~CMMTextView();
#ifdef _DEBUG
	virtual void AssertValid() const;
	virtual void Dump(CDumpContext& dc) const;
#endif

	// 生成されたメッセージ マップ関数
protected:
		// メモ - ClassWizard はこの位置にメンバ関数を追加または削除します。
	DECLARE_MESSAGE_MAP()

private:
	void DrawText();
};

/////////////////////////////////////////////////////////////////////////////

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ は前行の直前に追加の宣言を挿入します。

#endif // !defined(AFX_MMTEXTVIEW_H__F30C0803_5617_4073_B422_381EC1D417BB__INCLUDED_)
