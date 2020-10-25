#pragma once

#include "jpegimage.h"
#include "MyCtrl.h"
#include "DlgCommon.h"
#include "afxwin.h"


// CDlgPhoto ダイアログ

class CDlgPhoto : public CDlgCommon
{
	DECLARE_DYNAMIC(CDlgPhoto)

public:
	CDlgPhoto(CWnd* pParent = NULL);   // 標準コンストラクタ

// ダイアログ データ
	enum { IDD = IDD_DLG_PHOTO };

	int m_nSelectIndex;

protected:
	void DispData();
	void FindJpegFile(LPCTSTR pFileName, CStringArray &asFileName);
	static int CompareFile(const void *arg1, const void *arg2);
	void PreloadPhoto(int nIndex);
	CTime GetPhotoDate(const CString &sPathName);
	void UpdateComment();
	void DeletePhoto(CString sWhere, BOOL bDeleteFile);
	Gdiplus::PropertyItem *GetPropertyItem(Gdiplus::Image &oImage, int nTag);

	BOOL m_bUpdate;
	CSize m_sizeImage;
	int m_nCurrentSeqNo;
	CString m_sCurrentComment;
	BOOL m_bOriginal;

	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート

	DECLARE_MESSAGE_MAP()
	virtual BOOL OnInitDialog();
	virtual void OnCancel();
	afx_msg void OnDropFiles(HDROP hDropInfo);
	afx_msg void OnCbnSelchangeComboPhoto();
	afx_msg void OnBnClickedButtonDelete();
	afx_msg void OnBnClickedButtonMap();
	afx_msg void OnBnClickedButtonPhotoPrev();
	afx_msg void OnBnClickedButtonPhotoNext();
	afx_msg void OnGetMinMaxInfo(MINMAXINFO* lpMMI);
	afx_msg void OnStnDblclickStaticPhoto();
	afx_msg void OnSize(UINT nType, int cx, int cy);
	afx_msg BOOL OnMouseWheel(UINT nFlags, short zDelta, CPoint pt);

	CComboBox m_cComboPhoto;
	CJpegImage m_cStaticPhoto;
	CEditEx m_cEditDate;
	CButton m_cButtonPhotoPrev;
	CButton m_cButtonPhotoNext;
	CStatic m_cStaticPhotoNum;
	CEditEx m_cEditPhotoTime;
	CEditEx m_cEditPhotoFolder;
	CEditEx m_cEditPhotoComment;
	CButton m_cButtonMap;
	CButton m_cButtonDelete;
};
