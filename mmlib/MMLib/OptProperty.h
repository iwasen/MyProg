#pragma once

// OptProperty.h : ヘッダー ファイル
//

#include "OptFolder.h"
#include "OptFont.h"
#include "OptOrder.h"
#include "OptTwain.h"
#include "OptImageEditor.h"

/////////////////////////////////////////////////////////////////////////////
// COptProperty

class COptProperty : public CPropertySheet
{
	DECLARE_DYNAMIC(COptProperty)

// コンストラクション
public:
	COptProperty(UINT nIDCaption, CWnd* pParentWnd = NULL, UINT iSelectPage = 0);
	COptProperty(LPCTSTR pszCaption, CWnd* pParentWnd = NULL, UINT iSelectPage = 0);

// アトリビュート
public:

// オペレーション
public:

// オーバーライド
	// ClassWizard は仮想関数のオーバーライドを生成します。

// インプリメンテーション
public:
	virtual ~COptProperty();

	// 生成されたメッセージ マップ関数
protected:
		// メモ - ClassWizard はこの位置にメンバ関数を追加または削除します。
	DECLARE_MESSAGE_MAP()

public:
	COptFolder m_OptFolder;
	COptFont m_OptFont;
	COptOrder m_OptOrder;
	COptTwain m_OptTwain;
	COptImageEditor m_OptImageEditor;

private:
	void AddPropertyPage();
};
