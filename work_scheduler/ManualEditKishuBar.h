#if !defined(AFX_MANUALEDITKISHUBAR_H__7359734D_9B38_4EAE_91AF_205289D30995__INCLUDED_)
#define AFX_MANUALEDITKISHUBAR_H__7359734D_9B38_4EAE_91AF_205289D30995__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000
// ManualEditKishuBar.h : ヘッダー ファイル
//

/////////////////////////////////////////////////////////////////////////////
// CManualEditKishuBar ダイアログ

class CManualEditKishuBar : public CDialogBar
{
// コンストラクション
public:
	CManualEditKishuBar();   // 標準のコンストラクタ

// ダイアログ データ
	//{{AFX_DATA(CManualEditKishuBar)
	enum { IDD = IDD_MANUAL_KISHU_BAR };
		// メモ: ClassWizard はこの位置にデータ メンバを追加します。
	//}}AFX_DATA


// オーバーライド
	// ClassWizard は仮想関数のオーバーライドを生成します。
	//{{AFX_VIRTUAL(CManualEditKishuBar)
	protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート
	//}}AFX_VIRTUAL

// インプリメンテーション
protected:

	// 生成されたメッセージ マップ関数
	//{{AFX_MSG(CManualEditKishuBar)
	afx_msg void OnUpdateBack(CCmdUI* pCmdUI);
	afx_msg void OnUpdateNext(CCmdUI* pCmdUI);
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()
};

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ は前行の直前に追加の宣言を挿入します。

#endif // !defined(AFX_MANUALEDITKISHUBAR_H__7359734D_9B38_4EAE_91AF_205289D30995__INCLUDED_)
