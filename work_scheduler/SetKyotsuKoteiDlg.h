//{{AFX_INCLUDES()
#include "spreadsheet.h"
//}}AFX_INCLUDES
#if !defined(AFX_SETKYOTSUKOTEIDLG_H__D295C20D_28DF_4AAC_A339_16AFCE488CCD__INCLUDED_)
#define AFX_SETKYOTSUKOTEIDLG_H__D295C20D_28DF_4AAC_A339_16AFCE488CCD__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000
// SetKyotsuKoteiDlg.h : �w�b�_�[ �t�@�C��
//

#include "DialogEx.h"
#include "LixxxtrlEx.h"

/////////////////////////////////////////////////////////////////////////////
// CSetKyotsuKoteiDlg �_�C�A���O

class CSetKyotsuKoteiDlg : public CDialogEx
{
// �R���X�g���N�V����
public:
	CSetKyotsuKoteiDlg(CWnd* pParent = NULL);   // �W���̃R���X�g���N�^

// �_�C�A���O �f�[�^
	//{{AFX_DATA(CSetKyotsuKoteiDlg)
	enum { IDD = IDD_SET_KYOTSU_KOTEI };
	CComboBox	m_cComboSubline;
//	CSpreadEx	m_cSpread;
	CLixxxtrlEx	m_cList;
	//}}AFX_DATA


// �I�[�o�[���C�h
	// ClassWizard �͉��z�֐��̃I�[�o�[���C�h�𐶐����܂��B
	//{{AFX_VIRTUAL(CSetKyotsuKoteiDlg)
	protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g
	//}}AFX_VIRTUAL

// �C���v�������e�[�V����
protected:

	// �������ꂽ���b�Z�[�W �}�b�v�֐�
	//{{AFX_MSG(CSetKyotsuKoteiDlg)
	virtual BOOL OnInitDialog();
	afx_msg void OnSelchangeComboSubline();
//	afx_msg void OnRightClickSpread(short ClickType, long Col, long Row, long MouseX, long MouseY);
	afx_msg void OnKyotsuKoteiZenSettei();
	afx_msg void OnKyotsuKoteiZenKaijo();
	afx_msg void OnKyotsuKoteiKaijo();
//	afx_msg void OnChangeSpread(long Col, long Row);
	virtual void OnCancel();
	virtual void OnOK();
	afx_msg void OnSize(UINT nType, int cx, int cy);
	afx_msg void OnRclickList001(NMHDR* pNMHDR, LRESULT* pResult);
	afx_msg LRESULT OnChangeCombo(WPARAM wp, LPARAM lp);
	afx_msg LRESULT CreateComboList(WPARAM wp, LPARAM lp);
	DECLARE_EVENTSINK_MAP()
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()

	int m_nSublineId;
	int m_nRightClickRow;
	int m_nRightClickCol;
	CIntArrayEx m_aErrorLine[MAX_SUBLINE];

//	void SetSpread();
	void SetList();
	void SetGroupNo();
	int CountGroupNo(int nGroupNo);
	BOOL DataCheck();
	BOOL KoteiCheck();
};

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ �͑O�s�̒��O�ɒǉ��̐錾��}�����܂��B

#endif // !defined(AFX_SETKYOTSUKOTEIDLG_H__D295C20D_28DF_4AAC_A339_16AFCE488CCD__INCLUDED_)
