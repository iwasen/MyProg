// LLogDlg.h : ヘッダー ファイル
//

#pragma once

#include "DataBase.h"
#include "JpegImage.h"
#include "MyCtrl.h"
#include "DlgCommon.h"
#include "GoogleMap.h"

// CLLogDlg ダイアログ
class CLLogDlg : public CDialog
{
// コンストラクション
public:
	CLLogDlg(CWnd* pParent = NULL);	// 標準コンストラクタ
	~CLLogDlg();

// ダイアログ データ
	enum { IDD = IDD_LLOG_DIALOG };

	protected:
	virtual void DoDataExchange(CDataExchange* pDX);	// DDX/DDV サポート


// 実装
protected:
	HICON m_hIcon;
	CDataBase m_oDataBase;
	CTime m_oCurrentYM;
	CTime m_oCurrentDate;
	int m_nSortColumn;
	DWORD *m_pSearchFlag;

	void SetMonthListHeader();
	void DispData(CTime oDate, BOOL bForce = FALSE);
	void DispMonthList();
	void MakeLikeSearch(CString sSearch, CString &sCommentLike, CString &sPhotoLike, CString &sKeitaiGpsLike, CString &sGpsLoggerLike);
	void DispDayData();
	void ChangeCalendar(BOOL bForce);
	void DispDialog(CDlgCommon &dlg);
	void DispBoard();
	static int CALLBACK CompareListMonth(LPARAM lParam1, LPARAM lParam2, LPARAM lParamSort);
	int CompareListMonth(LPARAM lParam1, LPARAM lParam2);
	void PreloadPhoto(int nIndex);
	void CheckWalking();

	// 生成された、メッセージ割り当て関数
	virtual BOOL OnInitDialog();
	afx_msg void OnSysCommand(UINT nID, LPARAM lParam);
	afx_msg void OnPaint();
	afx_msg HCURSOR OnQueryDragIcon();
	afx_msg void OnLvnItemchangedListMonth(NMHDR *pNMHDR, LRESULT *pResult);
	afx_msg void OnMcnSelchangeCalendar(NMHDR *pNMHDR, LRESULT *pResult);
	afx_msg void OnCbnSelchangeComboPhoto();
	afx_msg void OnBnClickedButtonGoogleEarth();
	afx_msg void OnCbnSelchangeComboGpsLogger();
	afx_msg HBRUSH OnCtlColor(CDC* pDC, CWnd* pWnd, UINT nCtlColor);
	afx_msg void OnStnDblclickStaticComment();
	afx_msg void OnLbnDblclkListEvent();
	afx_msg void OnLbnDblclkListKeitaiGps();
	afx_msg void OnLbnDblclkListTrain();
	afx_msg void OnStnDblclickStaticGpsLogger();
	afx_msg void OnStnDblclickStaticPhoto();
	afx_msg void OnNMCustomdrawListMonth(NMHDR *pNMHDR, LRESULT *pResult);
	afx_msg void OnBnClickedButtonPhotoPrev();
	afx_msg void OnBnClickedButtonPhotoNext();
	afx_msg void OnBnClickedButtonSearch();
	afx_msg void OnLbnDblclkListBoard();
	afx_msg void OnBnClickedButtonGoogleMap();
	afx_msg void OnStnDblclickEditComment();
	afx_msg void OnBnClickedButtonPhotoMap();
	afx_msg void OnLvnColumnclickListMonth(NMHDR *pNMHDR, LRESULT *pResult);
	afx_msg void OnBnClickedButtonWalking();
	afx_msg void OnBnClickedButtonWalkingSum();
	DECLARE_MESSAGE_MAP()

	CLixxxtrlEx m_cListMonth;
	CMonthCalCtrl m_cCalendar;
	CListBox m_cListEvent;
	CListBox m_cListTrain;
	CJpegImage m_cStaticPhoto;
	CListBox m_cListKeitaiGps;
	CGoogleMap m_cStaticGpsLogger;
	CComboBox m_cComboPhoto;
	CComboBox m_cComboGpsLogger;
	CButton m_cButtonPhotoPrev;
	CButton m_cButtonPhotoNext;
	CStatic m_cStaticPhotoNum;
	CEditEx m_cEditPhotoTime;
	CEditEx m_cEditSearch;
	CListBox m_cListBoard;
	CEditEx m_cEditComment;
	CEditEx m_cEditPhotoFolder;
	CButton m_cButtonGoogleEarth;
	CButton m_cButtonGoogleMap;
	CButton m_cButtonPhotoMap;
	CEditEx m_cEditWalkingStep;
	CEditEx m_cEditWalkingCalorie;
	CEditEx m_cEditWalkingFat;
	CEditEx m_cEditWalkingDistance;
	CEditEx m_cEditWalkingTime;
	CEditEx m_cEditWalkingSpeed;
};
