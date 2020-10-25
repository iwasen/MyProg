#if !defined(AFX_MANUALEDITDLG_H__A4E9233D_2CFE_415F_8624_F1D6FEB6BC7A__INCLUDED_)
#define AFX_MANUALEDITDLG_H__A4E9233D_2CFE_415F_8624_F1D6FEB6BC7A__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000
// ManualEditDlg.h : �w�b�_�[ �t�@�C��
//
#include "DialogEx.h"
#include "ManualEditDocument.h"
#include "LixxxtrlEx1.h"

/////////////////////////////////////////////////////////////////////////////
// CManualEditDlg �_�C�A���O

class CManualEditDlg : public CDialogEx
{
	// �v�f��ƃ��X�g�f�[�^
	struct SYosoSagyoList {
		int nIndex;					// �C���f�b�N�X�ԍ�
		int nEditFlag;				// �ҏW�t���O
		BOOL bErrorFlag;			// �G���[�t���O
		int nYosoSagyoId;			// �v�f���ID
		CString sYosoSagyoName;		// ��Ɠ��e
		CString sYosoSagyoNo;		// �v�f���NO
// --Add 2007/02/26 aizawa �s��D-187
		int nSagyoNo;				// ���No
// --Add
		CString sEnzanShiki;		// ���Z��
		double fS1;					// S1
		double fS2;					// S2
		double fM;					// M
		BOOL bM;					// M���̓t���O
		double fN;					// N
		BOOL bN;					// N���̓t���O
		int nHassexxxndo;			// �����p�x
		BOOL bHassexxxndo;			// �����p�x���̓t���O
		int nKurikaeshiKaisu;		// �J��Ԃ���
		BOOL bKurikaeshiKaisu;		// �J��Ԃ��񐔓��̓t���O
		CString sKoteibuHendobu;	// �Œ�^�ϓ�
		CString sSagyoFlag;			// ��ƃt���O
		int nKijunKotei;			// ��^�H��
		BOOL bKijunKotei;			// ��^�H�����̓t���O
	};
	typedef CArrayEx <SYosoSagyoList, SYosoSagyoList&> CYosoSagyoListArray;
	
	// �v�f��ƌQ���X�g�f�[�^
	struct SYosoSagyogunList {
		int nIndex;					// �C���f�b�N�X�ԍ�
		int nEditFlag;				// �ҏW�t���O
		BOOL bErrorFlag;			// �G���[�t���O
		int nYosoSagyogunId;		// �v�f��ƌQID
		CString sYosoSagyogunName;
		BOOL bKyotsuKotei;
		CYosoSagyoListArray aYosoSagyoList;
	};
	typedef CArrayEx <SYosoSagyogunList, SYosoSagyogunList&> CYosoSagyogunListArray;

	// �H�����X�g�f�[�^
	struct SKoteiList {
		int nIndex;					// �C���f�b�N�X�ԍ�
		int nEditFlag;				// �ҏW�t���O
		BOOL bErrorFlag;			// �G���[�t���O
		CString sSubline;			// �T�u���C����
		CString sKoteiKigo;			// �H���L��
		CString sKoteiNo;			// �H���ԍ�
		CString sKoteiName;			// �H����
		CString sJidoShudo;			// �����^�蓮
		BOOL bKyotsuKotei;
		CYosoSagyogunListArray aYosoSagyogunList;
	};
	typedef CArrayEx2 <SKoteiList, SKoteiList&> CKoteiListArray;

	// ��Ǝ҃��X�g�f�[�^
	struct SSagyoshaList {
		int nSublineId;
		int nSagyoshaId;
		CString sSagyoshaName;
		CString sKoteiKigo;
		CKoteiListArray aKoteiList;
	};
	typedef CArrayEx <SSagyoshaList, SSagyoshaList&> CSagyoshaListArray;

// �R���X�g���N�V����
public:
	CManualEditDlg(CWnd* pParent = NULL);   // �W���̃R���X�g���N�^

// �_�C�A���O �f�[�^
	//{{AFX_DATA(CManualEditDlg)
	enum { IDD = IDD_MANUAL_EDIT };
	CButton	m_cButtonYosoSagyoTsuika;
	CButton	m_cButtonYosoSagyoSakujo;
	CMyEdit	m_cEditKishuName;
	CLixxxtrlEx	m_cListKotei;
	CLixxxtrlEx	m_cListYosoSagyogun;
	CLixxxtrlEx	m_cListYosoSagyo;
	CComboBox	m_cComboSagyosha;
	//}}AFX_DATA

	int m_nSagyoshaId;				// ��Ǝ�ID
	int m_nKishuId;					// �@��ID
	CManualEditDocument *m_pDoc;	// �h�L�������g�N���X�ւ̃|�C���^


// �I�[�o�[���C�h
	// ClassWizard �͉��z�֐��̃I�[�o�[���C�h�𐶐����܂��B
	//{{AFX_VIRTUAL(CManualEditDlg)
	protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g
	//}}AFX_VIRTUAL

// �C���v�������e�[�V����
protected:

	// �������ꂽ���b�Z�[�W �}�b�v�֐�
	//{{AFX_MSG(CManualEditDlg)
	virtual BOOL OnInitDialog();
	afx_msg void OnSelchangeComboSagyosha();
	afx_msg void OnItemchangedListKotei(NMHDR* pNMHDR, LRESULT* pResult);
	afx_msg void OnItemchangedListYosoSagyogun(NMHDR* pNMHDR, LRESULT* pResult);
	afx_msg void OnButtonYosoSagyoSakujo();
	afx_msg void OnButtonYosoSagyogunSakujo();
	afx_msg void OnButtonKoteiSakujo();
	afx_msg void OnButtonYosoSagyoTsuika();
	afx_msg void OnButtonYosoSagyogunTsuika();
	afx_msg void OnButtonKoteiTsuika();
	virtual void OnOK();
	virtual void OnCancel();
	afx_msg void OnButtonPreview();
	//}}AFX_MSG
	afx_msg LRESULT OnChangeCombo(WPARAM wp, LPARAM lp);
	afx_msg LRESULT CreateComboList(WPARAM wp, LPARAM lp);
	afx_msg LRESULT OnChangeEdit(WPARAM wp, LPARAM lp);
	DECLARE_MESSAGE_MAP()

	CSagyoshaListArray m_aSagyoshaList;	// ��Ǝ҃��X�g�f�[�^
	CSagyoshaListArray m_aSagyoshaList2;	// ��Ǝ҃��X�g�����f�[�^

	int m_nSublineId;

	void SetSagyoshaList();
	void SetKoteiList(int nSelectIndex = -1);
	void SetYosoSagyogunList(int nSelectIndex = -1);
	void SetYosoSagyoList(int nSelectIndex = -1);
	void EditKotei(int nRow, int nCol);
	void EditYosoSagyogun(int nRow, int nCol);
	void EditYosoSagyo(int nRow, int nCol);
	void SetYosoSagyoTemplate(SYosoSagyoList &cYosoSagyoList);
	CString GetKijunKoteiString(int nKijunKotei);
	void MakeSagyoshaList();
	void MakeKoteiList(int nSublineId, int nSagyoshId, CKoteiListArray &aKoteiList);
	void MakeYosoSagyogunList(int nYosoSagyogunId, SYosoSagyogun &cYosoSagyogun, CYosoSagyogunListArray &aYosoSagyogunList);
	void MakeYosoSagyoList(SYosoSagyogun &cYosoSagyogun, CYosoSagyoListArray &aYosoSagyoList);
	int GetSelectSagyosha();
	int GetSelectKotei();
	int GetSelectYosoSagyogun();
	int GetSelectYosoSagyo();
	void EnableYosoSagyoTsuikaSakujo();
	void DispList(int nSagyoshaIndex, int nKoteiIndex = -1, int nYosoSagyogunIndex = -1);
	BOOL CheckData();
	void ClearErrorFlag();
	void SetChangeFlag();
	BOOL CheckMinyuryoku();
	BOOL CheckInputData();
	BOOL CheckInputDataSub(CDataCheck &cDataCheck, LPCTSTR pData);
	BOOL CheckInputDataSub(CDataCheck &cDataCheck, double fData);
	BOOL CheckSameKotei();
	BOOL CheckSameYosoSagyogun();
	BOOL CheckYosoSagyogun();
	BOOL CheckYosoSagyo();
	BOOL CheckKyotsuKotei();
	void ChangeData();
	void DeleteData();
	void InsertData();
	void SetYosoSagyoData(SYosoSagyo &cYosoSagyo, SYosoSagyoList &cYosoSagyoList);
	double CalcEnzangoSagyoJikan(SYosoSagyoList &cYosoSagyoList);
	double CalcDaiatariSagyoJikan(SYosoSagyoList &cYosoSagyoList, double fEnzangoSagyoJikan);
	CString ConvertHankaku(LPCTSTR pText);
};

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ �͑O�s�̒��O�ɒǉ��̐錾��}�����܂��B

#endif // !defined(AFX_MANUALEDITDLG_H__A4E9233D_2CFE_415F_8624_F1D6FEB6BC7A__INCLUDED_)
