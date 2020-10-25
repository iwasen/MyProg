// vjb30040Dlg.h : ヘッダー ファイル
//

#if !defined(AFX_VJB30040DLG_H__3FD25357_9ABD_11D4_ADFB_00105A67EE03__INCLUDED_)
#define AFX_VJB30040DLG_H__3FD25357_9ABD_11D4_ADFB_00105A67EE03__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000

/////////////////////////////////////////////////////////////////////////////
// CVjb30040Dlg ダイアログ

class CVjb30040Dlg : public CDialog
{
// 構築
public:
	CVjb30040Dlg(CWnd* pParent = NULL);	// 標準のコンストラクタ

// ダイアログ データ
	//{{AFX_DATA(CVjb30040Dlg)
	enum { IDD = IDD_VJB30040_DIALOG };
	CEdit	m_EDIT_SHIKIBETSU_CODE;
	CComboBox	m_COMBO_SRV_NAME;
	CComboBox	m_COMBO_DEVICE_NAME;
	CEdit	m_EDIT_VOL_SU;
	CStatic	m_LABEL_VOL_SU;
	CEdit	m_EDIT_VOL_FILE;
	CStatic	m_LABEL_FILE_NAME;
	CStatic	m_LABEL_VOL_NAME;
	CButton	m_RADIO_LABEL_ARI;
	CButton	m_RADIO_LABEL_NASHI;
	int		m_iRadioLabel;
	int		m_iComboDeviceName;
	CString	m_sEditVolFile;
	CString	m_sEditShikibetsuCode;
	CString	m_sEditVolSu;
	//}}AFX_DATA

	// ClassWizard は仮想関数のオーバーライドを生成します。
	//{{AFX_VIRTUAL(CVjb30040Dlg)
	protected:
	virtual void DoDataExchange(CDataExchange* pDX);	// DDX/DDV のサポート
	//}}AFX_VIRTUAL

// インプリメンテーション
protected:
	HICON m_hIcon;

	// 生成されたメッセージ マップ関数
	//{{AFX_MSG(CVjb30040Dlg)
	virtual BOOL OnInitDialog();
	afx_msg void OnPaint();
	afx_msg HCURSOR OnQueryDragIcon();
	afx_msg void OnSelchangeComboDeviceName();
	afx_msg void OnRadioLabelAri();
	afx_msg void OnRadioLabelNashi();
	afx_msg void OnButtonZikou();
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()

private:
	void SetInitInstance();										// 画面初期表示状態設定処理
	void SetDeviceName();										// DEVICE名設定処理
	void SetServerName();										// 出力先バッチサーバ名設定処理
	bool CheckData();											// 入力媒体、出力先バッチサーバ、媒体識別コード入力チェック処理
	void Execute();												// 実行処理
	int InputDevice(Param *pParam, class CInputDevice *pInputDevice);	// 外部媒体入力処理
	void ExecuteFileCopyPrg(Param *pParam);						// ファイルコピープログラム起動処理
	bool CheckDeviceInfo();										// DEVICEごとの入力チェック処理
	void DeleteEditInfo(int p_iCode);							// エディット内容消去処理
	void SetInitRadio();										// ラジオボタン初期状態設定処理
	bool CheckNyuryokuData(CWnd *p_Wnd);						// オブジェクトデータ入力チェック処理
	bool CheckDeviceCodeData();									// 媒体識別コード入力値妥当性チェック
	bool CheckVolFileVolsuData();								// 入力値妥当性チェック処理
	bool CheckVolNyuryokuData(CString &p_csVolLabelData); 
	bool CheckLabelAriVolData(CString &p_csVolLabelData);		// Volラベル名入力値妥当性チェック処理
	bool CheckLabelNashiVolSu();								// Vol本数入力値妥当性チェック処理
	bool CheckLabelNashiFileNameRecord();						// ファイル名/レコード長入力値妥当性チェック処理
	bool CheckSlashData(CString &p_csFileNameRecord);			// "/"文字列チェック処理
	bool CheckLabelAriNashiKanmaData(CString &p_csFileNameRecord, int p_nFlg); // ファイル名/レコード長、VOLラベル名の入力値チェック処理
	bool CheckStartEndSlashData(CString &p_csNyuryokuData, int &p_nFileLen); // 先頭、及び、末尾文字が"/"文字列かどうかチェック処理
	void WriteLogFile(Param *pParam, LPCTSTR pLogFile, LPCTSTR pStatus);	// ログファイル書き込み
};

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ は前行の直前に追加の宣言を挿入します。

#endif // !defined(AFX_VJB30040DLG_H__3FD25357_9ABD_11D4_ADFB_00105A67EE03__INCLUDED_)
