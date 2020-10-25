#pragma once

// OptOrder.h : ヘッダー ファイル
//

/////////////////////////////////////////////////////////////////////////////
// COptOrder ダイアログ

class COptOrder : public CPropertyPage
{
	DECLARE_DYNCREATE(COptOrder)

// コンストラクション
public:
	COptOrder();
	~COptOrder();

// ダイアログ データ
	enum { IDD = IDD_OPT_ORDER };
	CListBox	m_cOrderList;
	int		m_iViewText;
	int		m_iSmallSize;


// オーバーライド
	// ClassWizard は仮想関数のオーバーライドを生成します。

	public:
	virtual void OnOK();
	protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート

// インプリメンテーション
protected:
	// 生成されたメッセージ マップ関数
	virtual BOOL OnInitDialog();
	afx_msg void OnUp();
	afx_msg void OnDown();
	afx_msg void OnDefault();
	DECLARE_MESSAGE_MAP()

public:
	BOOL m_bChangeOrder;
	BOOL m_bChangeText;
	BOOL m_bChangeSmallSize;

private:
	CString m_Order;
	int m_nViewText;
	int m_nSmallSize;

	void SetListBox(LPCTSTR pOrder);
};
