#if !defined(AFX_SEISANJOKEN2DLG_H__8ACDADEB_610D_4F72_A29D_4DA14F838FDC__INCLUDED_)
#define AFX_SEISANJOKEN2DLG_H__8ACDADEB_610D_4F72_A29D_4DA14F838FDC__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000
// SeisanJoken2Dlg.h : �w�b�_�[ �t�@�C��
//

#include "SetSeisanJokenDlg.h"
#include "LixxxtrlEx.h"

/////////////////////////////////////////////////////////////////////////////
// CSetSeisanJoken2Dlg �_�C�A���O

class CSetSeisanJoken2Dlg : public CSetSeisanJokenDlg
{
// �R���X�g���N�V����
public:
	CSetSeisanJoken2Dlg(CWnd* pParent = NULL);   // �W���̃R���X�g���N�^

// �_�C�A���O �f�[�^
	//{{AFX_DATA(CSetSeisanJoken2Dlg)
	enum { IDD = IDD_SEISAN_JOKEN2 };
		// ����: ClassWizard �͂��̈ʒu�Ƀf�[�^ �����o��ǉ����܂��B
	//}}AFX_DATA


// �I�[�o�[���C�h
	// ClassWizard �͉��z�֐��̃I�[�o�[���C�h�𐶐����܂��B
	//{{AFX_VIRTUAL(CSetSeisanJoken2Dlg)
	protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g
	//}}AFX_VIRTUAL

// �C���v�������e�[�V����
protected:

	// �������ꂽ���b�Z�[�W �}�b�v�֐�
	//{{AFX_MSG(CSetSeisanJoken2Dlg)
	virtual BOOL OnInitDialog();
	afx_msg void OnSelchangeComboYear();
	afx_msg void OnSelchangeComboMonth();
	afx_msg void OnButtonSettei();
	afx_msg void OnChangeEditDaisu();
	afx_msg void OnButtonIkoJogen();
	afx_msg void OnButtonIkoKagen();
	afx_msg void OnChangeSpread(long Col, long Row);
	afx_msg void OnButtonIkoKinmu();
	afx_msg void OnButtonIkoKyusoku();
	afx_msg void OnButtonIkoKado();
	afx_msg void OnButtonIkoKotai();
	afx_msg void OnEditchangeComboYear();
	afx_msg void OnEditchangeComboMonth();
	afx_msg LRESULT OnChangeList(WPARAM wp, LPARAM lp);
	afx_msg LRESULT OnChangeEdit(WPARAM wp, LPARAM lp);
	virtual void OnCancel();
	DECLARE_EVENTSINK_MAP()
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()

	void SetListView();
	double CalcKadoJikan(int nRow);
	double CalcTact(int nRow);
// Modify ... ( CHANGE )
// Lixxxtrl�ɕύX
	int CalcShiftPace(int nRow);
//	double CalcShiftPace(int nRow);
// By Y.Itabashi (xxxxx) 2007.01.26
	void AutoCalc(int nRow);
	void AutoCalcAll();
	BOOL CheckDataInput(void);
	void SaveData();
};

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ �͑O�s�̒��O�ɒǉ��̐錾��}�����܂��B

#endif // !defined(AFX_SEISANJOKEN2DLG_H__8ACDADEB_610D_4F72_A29D_4DA14F838FDC__INCLUDED_)
