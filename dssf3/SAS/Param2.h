#pragma once

#include "MyCtrl.h"

class CParam2 : public CDialog
{
public:
	CParam2(CWnd* pParent = NULL);   // �W���R���X�g���N�^
	virtual ~CParam2();

	int		m_iFreqBand;
	BOOL	m_bAFilter;
	CString	m_sStartFreq;
	CString	m_sEndFreq;
	CMyEdit m_cGRefData;
	CMyEdit m_cTCustom1;
	CMyEdit m_cTCustom2;
	CMyEdit m_cCCustom;

protected:
	enum { IDD = IDD_PARAM2 };

	DECLARE_MESSAGE_MAP()
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g
};
