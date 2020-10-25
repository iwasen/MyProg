// EcuRecvDlg.h : ヘッダー ファイル
//

#if !defined(AFX_ECURECVDLG_H__8CC9B147_B139_11D3_8056_7A2C79000000__INCLUDED_)
#define AFX_ECURECVDLG_H__8CC9B147_B139_11D3_8056_7A2C79000000__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000

/////////////////////////////////////////////////////////////////////////////
// CEcuRecvDlg ダイアログ

class CEcuRecvDlg : public CDialog
{
// 構築
public:
	CEcuRecvDlg(CWnd* pParent = NULL);	// 標準のコンストラクタ

// ダイアログ データ
	//{{AFX_DATA(CEcuRecvDlg)
	enum { IDD = IDD_ECURECV_DIALOG };
	CEdit	m_cSaveFolder;
	CButton	m_cExit;
	CButton	m_cStop;
	CButton	m_cStart;
	CString	m_sReceiveStatus;
	CString	m_sSaveFolder;
	CString	m_sTexxxount;
	CString	m_sReceiveCount;
	//}}AFX_DATA

	// ClassWizard は仮想関数のオーバーライドを生成します。
	//{{AFX_VIRTUAL(CEcuRecvDlg)
	protected:
	virtual void DoDataExchange(CDataExchange* pDX);	// DDX/DDV のサポート
	//}}AFX_VIRTUAL

// インプリメンテーション
protected:
	HICON m_hIcon;

	// 生成されたメッセージ マップ関数
	//{{AFX_MSG(CEcuRecvDlg)
	virtual BOOL OnInitDialog();
	afx_msg void OnSysCommand(UINT nID, LPARAM lParam);
	afx_msg void OnPaint();
	afx_msg HCURSOR OnQueryDragIcon();
	afx_msg void OnStart();
	afx_msg void OnStop();
	virtual void OnCancel();
	//}}AFX_MSG
	afx_msg LONG OnReceiveNotify(WPARAM wParam, LPARAM lParam);
	DECLARE_MESSAGE_MAP()

private:
	void EnableControl(BOOL bStart);
	BOOL SaveFolderCheck(CString &sSaveFolder);
	void DeleteSaveFile(CString &sSaveFolder);
};

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ は前行の直前に追加の宣言を挿入します。

#endif // !defined(AFX_ECURECVDLG_H__8CC9B147_B139_11D3_8056_7A2C79000000__INCLUDED_)
