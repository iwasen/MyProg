// MainListView.cpp : �����t�@�C��
//

#include "stdafx.h"
#include "MMA_G.h"
#include "MainListView.h"
#include "MMA_GDoc.h"
#include <math.h>


// CMainListView

IMPLEMENT_DYNCREATE(CMainListView, CListView)

CMainListView::CMainListView()
{

}

CMainListView::~CMainListView()
{
}

BEGIN_MESSAGE_MAP(CMainListView, CListView)
END_MESSAGE_MAP()


// CMainListView �f�f

#ifdef _DEBUG
void CMainListView::AssertValid() const
{
	CListView::AssertValid();
}

#ifndef _WIN32_WCE
void CMainListView::Dump(CDumpContext& dc) const
{
	CListView::Dump(dc);
}
#endif
#endif //_DEBUG


// CMainListView ���b�Z�[�W �n���h��

// �E�B���h�E�쐬�O����
BOOL CMainListView::PreCreateWindow(CREATESTRUCT& cs)
{
	cs.style |= LVS_REPORT | LVS_SHOWSELALWAYS;

	return CListView::PreCreateWindow(cs);
}

// �r���[����������
void CMainListView::OnInitialUpdate()
{
	CListView::OnInitialUpdate();

	// �I�������Ƃ��s�S�̂����]�\������悤�ɐݒ�
	GetLixxxtrl().SetExtendedStyle(LVS_EX_FULLROWSELECT);

	// ���X�g�w�b�_��ݒ�
	SetListHeader();
}

// �r���[�X�V����
void CMainListView::OnUpdate(CView* /*pSender*/, LPARAM /*lHint*/, CObject* /*pHint*/)
{
	CMMA_GDoc *pDoc = (CMMA_GDoc *)GetDocument();

	// ���X�g�f�[�^�ݒ�
	if ((pDoc->m_nSelectedItem & ID_KIND) == ID_DATA)
		SetListData();
	else
		ResetListData();
}

// ���X�g�w�b�_�ݒ菈��
void CMainListView::SetListHeader()
{
	struct LIxxxOLUMN {
		char *pText;
		int fmt;
		int width;
	} tColumnData[] = {
		{"��    ��", LVCFMT_LEFT, 200},
		{"���@�@�e", LVCFMT_LEFT, 650}
	}, *pColumn;
	int i;
	int nColumn;
	CString str;
	LV_COLUMN lvc;
	CLixxxtrl &lc = GetLixxxtrl();
	CHeaderCtrl &hc = *lc.GetHeaderCtrl();

	// �S�ẴA�C�e�����폜
	lc.DeleteAllItems();

	// �S�ẴJ�������폜
	while (hc.GetItemCount() != 0)
		lc.DeleteColumn(0);

	// �J�����f�[�^
	pColumn = tColumnData;
	nColumn  = sizeof(tColumnData) / sizeof(LIxxxOLUMN);

	// �J������ݒ�
	for (i = 0; i < nColumn; i++) {
		lvc.mask = LVCF_FMT | LVCF_WIDTH | LVCF_TEXT;
		lvc.fmt = pColumn->fmt;
		lvc.pszText = pColumn->pText;
		lvc.cx = pColumn->width;
		lc.InsertColumn(i, &lvc);
		pColumn++;
	}
}

// ���X�g�f�[�^�ݒ菈��
void CMainListView::SetListData()
{
	CMMA_GDoc *pDoc = (CMMA_GDoc *)GetDocument();
	CLixxxtrl &lixxxtrl = GetLixxxtrl();
	CString path;

	// �S�ẴA�C�e�����폜
	lixxxtrl.DeleteAllItems();

	// �I��No���������AFULLPath���擾����
	if (! pDoc->m_TreeDir.SearchIdx(pDoc->m_nSelectedItem, path))
		return;

	// FULLPath��IDB��ǂ�
	CIdbFile idb;
	if (! idb.Read((LPCSTR)path))
		return;

	switch (pDoc->m_nSelectedItem & ID_DATA_TYPE) {
	case DATA_TYPE_MMA:
		SetListDataMma(lixxxtrl, idb);
		break;
	case DATA_TYPE_MME:
		SetListDataMme(lixxxtrl, idb);
		break;
	case DATA_TYPE_ASU:
		SetListDataAsu(lixxxtrl, idb);
		break;
	}
}

void CMainListView::SetListDataMma(CLixxxtrl &lixxxtrl, const CIdbFile &idb)
{
	int	idx = 0;
	CString	buf;

	// �f�[�^��\��
	// �擾����
	lixxxtrl.InsertItem(idx, "�f�[�^�͈�");
	buf.Format("%s/%s/%s %s:%s:%s - %s/%s/%s %s:%s:%s"
		, idb.m_period.Left(4)
		, idb.m_period.Mid(4,2)
		, idb.m_period.Mid(6,2)
		, idb.m_period.Mid(8,2)
		, idb.m_period.Mid(10,2)
		, idb.m_period.Mid(12,2)
		, idb.m_period.Mid(15,4)
		, idb.m_period.Mid(19,2)
		, idb.m_period.Mid(21,2)
		, idb.m_period.Mid(23,2)
		, idb.m_period.Mid(25,2)
		, idb.m_period.Mid(27,2));
	lixxxtrl.SetItemText(idx, 1, buf);

	// �^�C���[��
	idx++;
	lixxxtrl.InsertItem(idx, "�f�[�^�J�n����");
	buf.Format("%s/%s/%s %s:%s:%s"
		, idb.m_timezero.Left(4)
		, idb.m_timezero.Mid(4,2)
		, idb.m_timezero.Mid(6,2)
		, idb.m_timezero.Mid(8,2)
		, idb.m_timezero.Mid(10,2)
		, idb.m_timezero.Mid(12,2));
	lixxxtrl.SetItemText(idx, 1, buf);

	// ISS Config
	idx++;
	lixxxtrl.InsertItem(idx, "ISSConfiguration");
	lixxxtrl.SetItemText(idx, 1, idb.m_issconfig);

	// �u�����N�s
	idx++;
	lixxxtrl.InsertItem(idx, "");

	// Sensor Co Sys
	idx++;
	lixxxtrl.InsertItem(idx, "�Z���T�ݒu�ʒu");
	lixxxtrl.SetItemText(idx, 1, idb.m_sensorcosys);

	// �l�b�g���[�NID
	idx++;
	buf.Format("%d", idb.m_networkid);
	lixxxtrl.InsertItem(idx, "�l�b�g���[�NID");
	lixxxtrl.SetItemText(idx, 1, buf);

	// ���j�b�gID
	idx++;
	buf.Format("%d", idb.m_rsuid);
	lixxxtrl.InsertItem(idx, "RSU ID");
	lixxxtrl.SetItemText(idx, 1, buf);

	// �Z���T�[ID
	idx++;
	buf.Format("%d", idb.m_sensorid);
	lixxxtrl.InsertItem(idx, "TAA ID");
	lixxxtrl.SetItemText(idx, 1, buf);

	// �T���v�����[�g
	idx++;
	buf.Format("%.2lf [Hz]", idb.m_samplerate);
	lixxxtrl.InsertItem(idx, "SampleRate");
	lixxxtrl.SetItemText(idx, 1, buf);

	// �J�b�g�I�t���g��
	idx++;
	buf.Format("%d [Hz]", idb.m_cutoff);
	lixxxtrl.InsertItem(idx, "Cutoff Frequency");
	lixxxtrl.SetItemText(idx, 1, buf);

	// �Q�C��
	idx++;
	buf.Format("%d", idb.m_gain);
	lixxxtrl.InsertItem(idx, "GAIN");
	lixxxtrl.SetItemText(idx, 1, buf);

	// Bias Co Eff
	idx++;
	buf.Format("%d", idb.m_biascoeff);
	lixxxtrl.InsertItem(idx, "BiasCoeff");
	lixxxtrl.SetItemText(idx, 1, buf);

	// �X�P�[���t�@�N�^�[
	idx++;
	buf.Format("%d", idb.m_scalefactor);
	lixxxtrl.InsertItem(idx, "ScaleFactor");
	lixxxtrl.SetItemText(idx, 1, buf);

	// Data Quality
	idx++;
	lixxxtrl.InsertItem(idx, "Data Quality Measure");
	lixxxtrl.SetItemText(idx, 1, idb.m_dataquality);

	// �u�����N�s
	idx++;
	lixxxtrl.InsertItem(idx, "");

	// �_�E�������N��
	idx++;
	lixxxtrl.InsertItem(idx, "�_�E�������N��");
	lixxxtrl.SetItemText(idx, 1, idb.m_downlink);

	// �ۑ��p�X��
	idx++;
	lixxxtrl.InsertItem(idx, "�f�[�^�ۑ���");
	lixxxtrl.SetItemText(idx, 1, idb.m_path);

	// �R�����g
	idx++;
	lixxxtrl.InsertItem(idx, "���l");
	lixxxtrl.SetItemText(idx, 1, idb.m_comment);
}

void CMainListView::SetListDataMme(CLixxxtrl &lixxxtrl, const CIdbFile &idb)
{
	int	idx = 0;
	CString	buf;
	// �f�[�^��\��
	// �擾����
	lixxxtrl.InsertItem(idx, "�f�[�^�͈�");
	buf.Format("%s/%s/%s %s:%s:%s - %s/%s/%s %s:%s:%s"
		, idb.m_period.Left(4)
		, idb.m_period.Mid(4,2)
		, idb.m_period.Mid(6,2)
		, idb.m_period.Mid(8,2)
		, idb.m_period.Mid(10,2)
		, idb.m_period.Mid(12,2)
		, idb.m_period.Mid(15,4)
		, idb.m_period.Mid(19,2)
		, idb.m_period.Mid(21,2)
		, idb.m_period.Mid(23,2)
		, idb.m_period.Mid(25,2)
		, idb.m_period.Mid(27,2));
	lixxxtrl.SetItemText(idx, 1, buf);
	// �^�C���[��
	idx++;
	lixxxtrl.InsertItem(idx, "�f�[�^�J�n����");
	buf.Format("%s/%s/%s %s:%s:%s"
		, idb.m_timezero.Left(4)
		, idb.m_timezero.Mid(4,2)
		, idb.m_timezero.Mid(6,2)
		, idb.m_timezero.Mid(8,2)
		, idb.m_timezero.Mid(10,2)
		, idb.m_timezero.Mid(12,2));
	lixxxtrl.SetItemText(idx, 1, buf);
	// �u�����N�s
	idx++;
	lixxxtrl.InsertItem(idx, "");
	// Sensor Co Sys
	idx++;
	lixxxtrl.InsertItem(idx, "�Z���T�ݒu�ʒu");
	lixxxtrl.SetItemText(idx, 1, idb.m_sensorcosys);
	// �Z���T�[ID
	idx++;
	buf.Format("%d", idb.m_sensorid);
	lixxxtrl.InsertItem(idx, "Sensor ID");
	lixxxtrl.SetItemText(idx, 1, buf);
	// �T���v�����[�g
	idx++;
	buf.Format("%g [Hz]", idb.m_samplerate);
	lixxxtrl.InsertItem(idx, "Sample Rate");
	lixxxtrl.SetItemText(idx, 1, buf);
	// �u�����N�s
	idx++;
	lixxxtrl.InsertItem(idx, "");
	// �_�E�������N��
	idx++;
	lixxxtrl.InsertItem(idx, "�_�E�������N��");
	lixxxtrl.SetItemText(idx, 1, idb.m_downlink);
	// �ۑ��p�X��
	idx++;
	lixxxtrl.InsertItem(idx, "�f�[�^�ۑ���");
	lixxxtrl.SetItemText(idx, 1, idb.m_path);
	// �R�����g
	idx++;
	lixxxtrl.InsertItem(idx, "���l");
	lixxxtrl.SetItemText(idx, 1, idb.m_comment);
}

void CMainListView::SetListDataAsu(CLixxxtrl &lixxxtrl, const CIdbFile &idb)
{
	int	idx = 0;
	CString	buf;
	// �f�[�^��\��
	// �擾����
	lixxxtrl.InsertItem(idx, "�f�[�^�͈�");
	buf.Format("%s/%s/%s %s:%s:%s - %s/%s/%s %s:%s:%s"
		, idb.m_period.Left(4)
		, idb.m_period.Mid(4,2)
		, idb.m_period.Mid(6,2)
		, idb.m_period.Mid(8,2)
		, idb.m_period.Mid(10,2)
		, idb.m_period.Mid(12,2)
		, idb.m_period.Mid(15,4)
		, idb.m_period.Mid(19,2)
		, idb.m_period.Mid(21,2)
		, idb.m_period.Mid(23,2)
		, idb.m_period.Mid(25,2)
		, idb.m_period.Mid(27,2));
	lixxxtrl.SetItemText(idx, 1, buf);
	// �^�C���[��
	idx++;
	lixxxtrl.InsertItem(idx, "�f�[�^�J�n����");
	buf.Format("%s/%s/%s %s:%s:%s"
		, idb.m_timezero.Left(4)
		, idb.m_timezero.Mid(4,2)
		, idb.m_timezero.Mid(6,2)
		, idb.m_timezero.Mid(8,2)
		, idb.m_timezero.Mid(10,2)
		, idb.m_timezero.Mid(12,2));
	lixxxtrl.SetItemText(idx, 1, buf);
	// �u�����N�s
	idx++;
	lixxxtrl.InsertItem(idx, "");
	// Sensor Co Sys
	idx++;
	lixxxtrl.InsertItem(idx, "�Z���T�ݒu�ʒu");
	lixxxtrl.SetItemText(idx, 1, idb.m_sensorcosys);
	// �Z���T�[ID
	idx++;
	buf.Format("%d", idb.m_sensorid);
	lixxxtrl.InsertItem(idx, "Sensor ID");
	lixxxtrl.SetItemText(idx, 1, buf);
	// �T���v�����[�g
	idx++;
	buf.Format("%g [Hz]", idb.m_samplerate);
	lixxxtrl.InsertItem(idx, "Sample Rate");
	lixxxtrl.SetItemText(idx, 1, buf);
	// �u�����N�s
	idx++;
	lixxxtrl.InsertItem(idx, "");
	// �_�E�������N��
	idx++;
	lixxxtrl.InsertItem(idx, "�_�E�������N��");
	lixxxtrl.SetItemText(idx, 1, idb.m_downlink);
	// �ۑ��p�X��
	idx++;
	lixxxtrl.InsertItem(idx, "�f�[�^�ۑ���");
	lixxxtrl.SetItemText(idx, 1, idb.m_path);
	// �R�����g
	idx++;
	lixxxtrl.InsertItem(idx, "���l");
	lixxxtrl.SetItemText(idx, 1, idb.m_comment);
}

// ���X�g�f�[�^���Z�b�g����
void CMainListView::ResetListData()
{
	CLixxxtrl &lixxxtrl = GetLixxxtrl();

	// �S�ẴA�C�e�����폜
	lixxxtrl.DeleteAllItems();
}
