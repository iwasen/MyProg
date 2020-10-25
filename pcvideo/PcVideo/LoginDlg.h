#if !defined(AFX_LOGINDLG_H__506F2DF3_E5AB_11D2_8C08_00104B939DF5__INCLUDED_)
#define AFX_LOGINDLG_H__506F2DF3_E5AB_11D2_8C08_00104B939DF5__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000
// LoginDlg.h : ヘッダー ファイル
//

extern void ReceiveLoginRequest(PLOGINREQ pLoginReq, PSOCK pSockSend);

/////////////////////////////////////////////////////////////////////////////
// CLoginDlg ダイアログ

class CLoginDlg : public CDialog
{
// コンストラクション
public:
	CLoginDlg(CWnd* pParent = NULL);   // 標準のコンストラクタ

// ダイアログ データ
	//{{AFX_DATA(CLoginDlg)
	enum { IDD = IDD_LOGIN };
	CEdit	m_cUserID;
	CButton	m_cOK;
	CEdit	m_cPassword;
	CString	m_sPassword;
	BOOL	m_bSavePassword;
	CString	m_sServerName;
	CString	m_sUserID;
	//}}AFX_DATA


// オーバーライド
	// ClassWizard は仮想関数のオーバーライドを生成します。
	//{{AFX_VIRTUAL(CLoginDlg)
	protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート
	//}}AFX_VIRTUAL

// インプリメンテーション
protected:

	// 生成されたメッセージ マップ関数
	//{{AFX_MSG(CLoginDlg)
	virtual BOOL OnInitDialog();
	virtual void OnOK();
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()

public:
	DWORD m_ServerID;

protected:
	void EncodePassword(LPCTSTR pPasswordIn, LPSTR pPasswordOut);
	void DecodePassword(LPCTSTR pPasswordIn, LPSTR pPasswordOut);
};

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ は前行の直前に追加の宣言を挿入します。

#endif // !defined(AFX_LOGINDLG_H__506F2DF3_E5AB_11D2_8C08_00104B939DF5__INCLUDED_)
