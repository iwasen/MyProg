#if !defined(AFX_SEISANJOKEN1DLG_H__F83EA1C1_3C09_4FC8_9455_C1406F4F374A__INCLUDED_)
#define AFX_SEISANJOKEN1DLG_H__F83EA1C1_3C09_4FC8_9455_C1406F4F374A__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000
// SeisanJoken1Dlg.h : �w�b�_�[ �t�@�C��
//

#include "SetSeisanJokenDlg.h"
#include "LixxxtrlEx.h"

/////////////////////////////////////////////////////////////////////////////
// CSetSeisanJoken1Dlg �_�C�A���O

class CSetSeisanJoken1Dlg : public CSetSeisanJokenDlg
{
// �R���X�g���N�V����
public:
	CSetSeisanJoken1Dlg(CWnd* pParent = NULL);   // �W���̃R���X�g���N�^

// �_�C�A���O �f�[�^
	//{{AFX_DATA(CSetSeisanJoken1Dlg)
	enum { IDD = IDD_SEISAN_JOKEN1 };
	//}}AFX_DATA


// �I�[�o�[���C�h
	// ClassWizard �͉��z�֐��̃I�[�o�[���C�h�𐶐����܂��B
	//{{AFX_VIRTUAL(CSetSeisanJoken1Dlg)
	protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g
	//}}AFX_VIRTUAL

// �C���v�������e�[�V����
protected:

	// �������ꂽ���b�Z�[�W �}�b�v�֐�
	//{{AFX_MSG(CSetSeisanJoken1Dlg)
	virtual BOOL OnInitDialog();
	afx_msg void OnSelchangeComboYear();
	afx_msg void OnSelchangeComboMonth();
	afx_msg void OnButtonSettei();
	afx_msg void OnChangeEditDaisu();
	afx_msg void OnButtonIkoJogen();
	afx_msg void OnButtonIkoKagen();
	afx_msg void OnChangeSpread(long Col, long Row);
	afx_msg void OnEditchangeComboYear();
	afx_msg void OnEditchangeComboMonth();
	afx_msg LRESULT OnChangeList(WPARAM wp, LPARAM lp);
	afx_msg LRESULT OnChangeEdit(WPARAM wp, LPARAM lp);
	afx_msg void OnEndlabeleditList001(NMHDR* pNMHDR, LRESULT* pResult);
	virtual void OnCancel();
	DECLARE_EVENTSINK_MAP()
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()

	void SetListView();
	double CalcTact(int nRow);
	void AutoCalc(int nRow);
	void AutoCalcAll();
	BOOL CheckDataInput(void);
	void SaveData();
};

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ �͑O�s�̒��O�ɒǉ��̐錾��}�����܂��B

#endif // !defined(AFX_SEISANJOKEN1DLG_H__F83EA1C1_3C09_4FC8_9455_C1406F4F374A__INCLUDED_)
