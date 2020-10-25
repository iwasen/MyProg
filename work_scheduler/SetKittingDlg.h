#pragma once
//*****************************************************************************************************
//  1. �t�@�C����
//		SetKittingDlg.h
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		CSetKittingDlg �N���X�̃C���^�[�t�F�C�X
//----------------------------------------------------------------------------------------------------
//  3. ���l
//----------------------------------------------------------------------------------------------------
//  4. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************

#include "DialogEx.h"
#include "SpreadEx.h"
#include "LixxxtrlEx.h"

// �L�b�g���ݒ�_�C�A���O
class CSetKittingDlg : public CDialogEx
{
// �R���X�g���N�V����
public:
	CSetKittingDlg(CWnd* pParent = NULL);   // �W���̃R���X�g���N�^

// �_�C�A���O �f�[�^
	//{{AFX_DATA(CSetKittingDlg)
	enum { IDD = IDD_SET_KITTING };
	CLixxxtrlEx	m_cListBuhin;
	CLixxxtrlEx	m_cListKotei;
	CLixxxtrlEx	m_cListYosoSagyogun;
	CLixxxtrlEx	m_cListYosoSagyo;
	CMyButton	m_cButtonOK;
	CMyEdit	m_cEditKitSaJikan;
	CMyEdit	m_cEditKitNashiGokeiJikan;
	CMyEdit	m_cEditKitAriGokeiJikan;
	CComboBox	m_cComboSubline;
//	CSpreadEx	m_cSpreadBuhin;
//	CSpreadEx	m_cSpreadKotei;
//	CSpreadEx	m_cSpreadYosoSagyo;
//	CSpreadEx	m_cSpreadYosoSagyogun;
	//}}AFX_DATA


// �I�[�o�[���C�h
	// ClassWizard �͉��z�֐��̃I�[�o�[���C�h�𐶐����܂��B
	//{{AFX_VIRTUAL(CSetKittingDlg)
	protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g
	//}}AFX_VIRTUAL

// �C���v�������e�[�V����
protected:

	// �������ꂽ���b�Z�[�W �}�b�v�֐�
	//{{AFX_MSG(CSetKittingDlg)
	virtual BOOL OnInitDialog();
	virtual void OnOK();
	afx_msg void OnSize(UINT nType, int cx, int cy);
	afx_msg void OnSelchangeComboSubline();
	afx_msg void OnClickSpreadYosoSagyo(long Col, long Row);
	afx_msg void OnButtonNext();
	afx_msg void OnButtonBack();
	afx_msg void OnButtonZensentaku();
	afx_msg void OnButtonZenkaijo();
	afx_msg void OnButtonKittingTaishoBuhinhyo();
	virtual void OnCancel();
	afx_msg void OnItemchangedListBuhin(NMHDR* pNMHDR, LRESULT* pResult);
	afx_msg void OnItemchangedListKotei(NMHDR* pNMHDR, LRESULT* pResult);
	afx_msg void OnItemchangedListYosoSagyogun(NMHDR* pNMHDR, LRESULT* pResult);
	afx_msg void OnItemchangedListYosoSagyo(NMHDR* pNMHDR, LRESULT* pResult);
	//}}AFX_MSG
	afx_msg LRESULT OnListExClick(WPARAM wParam, LPARAM lParam);
	DECLARE_MESSAGE_MAP()

	struct SKoteiList {
		CString sKoteiName;
		BOOL bFuyoSagyo;
	};
	typedef CArrayEx2 <SKoteiList, SKoteiList&> CKoteiListArray;

	struct SYosoSagyogunList {
		CString sYosoSagyogunName;
		int nIndex;
		BOOL bFuyoSagyo;
	};
	typedef CArrayEx <SYosoSagyogunList, SYosoSagyogunList&> CYosoSagyogunListArray;

	int m_nSublineId;
	SKittingBuhin *m_pKittingBuhin;						// �L�b�g�����i�f�[�^�ւ̃|�C���^
	CString m_sKoteiName;								// �H����
	int m_nKittingYosoSagyogunIndex;					// �L�b�g���v�f��ƌQ�C���f�b�N�X
	CIntArrayEx m_aKittingSublineId;					// �L�b�g�����s�T�u���C��
	int	m_nSelectOff;									// ���i�`�F�b�N�t���O�Z�b�g�̈ꎞ����

	void SelectBuhin(int nRow);
	void SelectKotei(int nRow);
	void SelectYosoSagyogun(int nRow);
	void SetBuhinList();
	void SetKoteiList(BOOL bInitFlag = TRUE);
	void SetYosoSagyogunList(BOOL bInitFlag = TRUE);
	void SetYosoSagyoList();
	void SetCheckAll(BOOL bCheck);
	void DispTotalTime(double fKitNashiJikan, double fKitAriJikan);
};

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ �͑O�s�̒��O�ɒǉ��̐錾��}�����܂��B
