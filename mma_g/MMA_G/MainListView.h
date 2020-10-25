#pragma once

#include "IdbFile.h"

// CMainListView �r���[

class CMainListView : public CListView
{
	DECLARE_DYNCREATE(CMainListView)

protected:
	CMainListView();           // ���I�����Ŏg�p����� protected �R���X�g���N�^
	virtual ~CMainListView();

public:
#ifdef _DEBUG
	virtual void AssertValid() const;
#ifndef _WIN32_WCE
	virtual void Dump(CDumpContext& dc) const;
#endif
#endif

protected:
	void SetListHeader();
	void SetListData();
	void SetListDataMma(CLixxxtrl &lixxxtrl, const CIdbFile &idb);
	void SetListDataMme(CLixxxtrl &lixxxtrl, const CIdbFile &idb);
	void SetListDataAsu(CLixxxtrl &lixxxtrl, const CIdbFile &idb);
	void ResetListData();

protected:
	DECLARE_MESSAGE_MAP()
	virtual void OnInitialUpdate();
	virtual BOOL PreCreateWindow(CREATESTRUCT& cs);
	virtual void OnUpdate(CView* /*pSender*/, LPARAM /*lHint*/, CObject* /*pHint*/);
};


