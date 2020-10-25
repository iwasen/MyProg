// Item.h: CItem �N���X�̃C���^�[�t�F�C�X
//
//////////////////////////////////////////////////////////////////////

#if !defined(AFX_ITEM_H__E7F7D183_F208_11D2_8C20_00104B939DF5__INCLUDED_)
#define AFX_ITEM_H__E7F7D183_F208_11D2_8C20_00104B939DF5__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000

class CItem  
{
public:
	CItem();
	virtual ~CItem();

	BOOL m_bRead;
	CString	m_CreateDate;		// �B�e�N��������
	CString	m_UpdateDate;		// �X�V�N��������
	CString	m_Creator;			// �쐬��
	CString	m_Code;				// �R�[�h
	CString	m_Title;			// �摜�^�C�g��
	CString	m_Remark1;			// ���l�P
	CString	m_Remark2;			// ���l�Q
	HDIB m_hDIB1;
	HDIB m_hDIB2;
	CString m_Text;				// �e�L�X�g�f�[�^
	CRect m_IconRect;

	void ReadItem(CMmlRead *pMmlRead, int nPage);
};

#endif // !defined(AFX_ITEM_H__E7F7D183_F208_11D2_8C20_00104B939DF5__INCLUDED_)
