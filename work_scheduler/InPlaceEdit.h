/**
 * @file	InPlaceEdit.h
 * @brief	CInPlaceEdit�N���X�̃C���^�[�t�F�C�X
 *
 * @author	K.kimura (xxxxx, Inc.)
 * @date	2005-08-18
 * @version	InPlaceEdit.h, v 1.1 2005/08/18 $
 *			Copyright(C) 2005 xxxxx, Inc. All rights reserved.
 */

#if !defined(AFX_INPLACEEDIT_H__5834B600_4FAF_4275_9207_60AEE0B675B7__INCLUDED_)
#define AFX_INPLACEEDIT_H__5834B600_4FAF_4275_9207_60AEE0B675B7__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000
// InPlaceEdit.h : �w�b�_�[ �t�@�C��
//

/////////////////////////////////////////////////////////////////////////////
// CInPlaceEdit �E�B���h�E

class CInPlaceEdit : public CEdit
{
// �R���X�g���N�V����
public:
	CInPlaceEdit();		/// �R���X�g���N�^
	CInPlaceEdit(int iItem, int iSubItem, CString sInitText); /// �R���X�g���N�^

// �A�g���r���[�g
public:

// �I�y���[�V����
public:

// �I�[�o�[���C�h
	// ClassWizard �͉��z�֐��̃I�[�o�[���C�h�𐶐����܂��B
	//{{AFX_VIRTUAL(CInPlaceEdit)
	public:
	virtual BOOL PreTranslateMessage(MSG* pMsg);	/// ���b�Z�[�W�ϊ��O����
	//}}AFX_VIRTUAL

// �C���v�������e�[�V����
public:
	virtual ~CInPlaceEdit();		/// �f�X�g���N�^

	// �������ꂽ���b�Z�[�W �}�b�v�֐�
protected:
	//{{AFX_MSG(CInPlaceEdit)
		// ���� - ClassWizard �͂��̈ʒu�Ƀ����o�֐���ǉ��܂��͍폜���܂��B
	afx_msg void OnKillFocus(CWnd* pNewWnd);					/// ���̓t�H�[�J�X�����O����
	afx_msg void OnNcDestroy();									/// �N���C�A���g�̈�j������
	afx_msg void OnChar(UINT nChar, UINT nRepCnt, UINT nFlags);	/// �L�[���͕ϊ�����
	afx_msg int OnCreate(LPCREATESTRUCT lpCreateStruct);		/// �I�u�W�F�N�g�쐬������
	//}}AFX_MSG

	DECLARE_MESSAGE_MAP()
private:
	int m_iItem;			/// ���x���s�ԍ�
	int m_iSubItem;			/// ���x����ԍ�
	CString m_sInitText;	/// ������������
	BOOL    m_bESC;	 	// To indicate whether ESC key was pressed
};

/////////////////////////////////////////////////////////////////////////////

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ �͑O�s�̒��O�ɒǉ��̐錾��}�����܂��B

#endif // !defined(AFX_INPLACEEDIT_H__5834B600_4FAF_4275_9207_60AEE0B675B7__INCLUDED_)
