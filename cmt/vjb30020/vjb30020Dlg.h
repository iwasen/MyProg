// vjb30020Dlg.h : ヘッダー ファイル
//

#if !defined(AFX_VJB30020DLG_H__36752E37_6F02_439D_B346_3C43E7E5420A__INCLUDED_)
#define AFX_VJB30020DLG_H__36752E37_6F02_439D_B346_3C43E7E5420A__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000

#define LEN_SHUBETSU_CODE	3			// 媒体種別コード長
#define LEN_SHIKIBETSU_CODE	6			// 媒体識別コード長
#define LEN_VOLUME_LABEL	6			// ボリュームラベル長
#define LEN_TOTAL	(LEN_SHUBETSU_CODE + LEN_SHIKIBETSU_CODE + LEN_VOLUME_LABEL)
#define MAX_VOLUME_LABEL	7			// 最大ボリュームラベル数

// キューファイル情報
struct Queue {
	CString sFileName;
	CString sShubetsuCode;
	CString sShikibetsuCode;
	CString sVolLabel;
	CString sCreateDate;
	char cStatus;
	BOOL bAvail;
};

/////////////////////////////////////////////////////////////////////////////
// CVjb30020Dlg ダイアログ

class CVjb30020Dlg : public CDialog
{
// 構築
public:
	CVjb30020Dlg(CWnd* pParent = NULL);	// 標準のコンストラクタ

// ダイアログ データ
	//{{AFX_DATA(CVjb30020Dlg)
	enum { IDD = IDD_VJB30020_DIALOG };
	CButton	m_cNonCheck;
	CLixxxtrl	m_cQueueList;
	//}}AFX_DATA

	// ClassWizard は仮想関数のオーバーライドを生成します。
	//{{AFX_VIRTUAL(CVjb30020Dlg)
	protected:
	virtual void DoDataExchange(CDataExchange* pDX);	// DDX/DDV のサポート
	//}}AFX_VIRTUAL

// インプリメンテーション
protected:
	HICON m_hIcon;

	// 生成されたメッセージ マップ関数
	//{{AFX_MSG(CVjb30020Dlg)
	virtual BOOL OnInitDialog();
	afx_msg void OnPaint();
	afx_msg HCURSOR OnQueryDragIcon();
	virtual void OnOK();
	afx_msg void OnNoncheck();
	afx_msg void OnReload();
	afx_msg void OnTimer(UINT nIDEvent);
	afx_msg void OnDestroy();
	afx_msg void OnClickQueueList(NMHDR* pNMHDR, LRESULT* pResult);
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()

private:
	void Initialize();
	void SetLixxxolumn();
	void SetQueueList(BOOL nSendMessage);
	Queue *GetQueueData(CString sFileName);
	void DeleteQueueList();
	void AddQueueList(Queue *pQueue);
	CString GetStatusText(char status);
	void Execute(char status);
	void ExecuteCopyProgram(Queue *pQueue);
	void ChangeStatus(Queue *pQueue, char status, int nItem);
	CString FormatDate(Queue *pQueue);
	void EnableNonCheck();
};

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ は前行の直前に追加の宣言を挿入します。

#endif // !defined(AFX_VJB30020DLG_H__36752E37_6F02_439D_B346_3C43E7E5420A__INCLUDED_)
