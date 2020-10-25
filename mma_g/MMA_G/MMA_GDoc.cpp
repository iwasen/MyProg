// MMA_GDoc.cpp : CMMA_GDoc �N���X�̎���
//

#include "stdafx.h"
#include "MMA_G.h"
#include "MMA_GDoc.h"
#include "GraphFrm.h"
#include "DigitalDataDlg.h"
#include "ConvDlg.h"
#include "ConvMmeDlg.h"
#include "ConvAsuDlg.h"
#include "IdbFile.h"
#include "CsvData.h"
#include "General.h"
#include "Define.h"
#include "ChangeInfoDlg.h"
#include "ChangeInfoMmeDlg.h"
#include "ChangeInfoAsuDlg.h"
#include "RealtimeData.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#endif


// CMMA_GDoc

IMPLEMENT_DYNCREATE(CMMA_GDoc, CDocument)

BEGIN_MESSAGE_MAP(CMMA_GDoc, CDocument)
	ON_COMMAND(IDM_DISP_GRAPH, &CMMA_GDoc::OnDispGraph)
	ON_COMMAND(IDM_DISP_DIGITAL_DATA, &CMMA_GDoc::OnDispDigitalData)
	ON_COMMAND(IDM_CONVERT, &CMMA_GDoc::OnConvert)
	ON_COMMAND(IDM_OUTPUT_CSV, &CMMA_GDoc::OnOutputCsv)
	ON_COMMAND(IDM_DELETE, &CMMA_GDoc::OnDelete)
	ON_COMMAND(IDM_EXEC, &CMMA_GDoc::OnExec)
	ON_COMMAND(IDM_RELOAD, &CMMA_GDoc::OnReload)
	ON_COMMAND(IDM_DISP_TEMP_GRAPH, &CMMA_GDoc::OnDispTempGraph)
	ON_COMMAND(IDM_CHANGE_INFO, &CMMA_GDoc::OnChangeInfo)
	ON_UPDATE_COMMAND_UI(IDM_CHANGE_INFO, &CMMA_GDoc::OnUpdateChangeInfo)
	ON_UPDATE_COMMAND_UI(IDM_DISP_GRAPH, &CMMA_GDoc::OnUpdateDispGraph)
	ON_UPDATE_COMMAND_UI(IDM_DISP_TEMP_GRAPH, &CMMA_GDoc::OnUpdateDispTempGraph)
	ON_UPDATE_COMMAND_UI(IDM_DISP_DIGITAL_DATA, &CMMA_GDoc::OnUpdateDispDigitalData)
	ON_UPDATE_COMMAND_UI(IDM_OUTPUT_CSV, &CMMA_GDoc::OnUpdateOutputCsv)
	ON_COMMAND(IDM_REALTIME_START, &CMMA_GDoc::OnRealtimeStart)
	ON_UPDATE_COMMAND_UI(IDM_REALTIME_START, &CMMA_GDoc::OnUpdateRealtimeStart)
	ON_COMMAND(IDM_REALTIME_STOP, &CMMA_GDoc::OnRealtimeStop)
	ON_UPDATE_COMMAND_UI(IDM_REALTIME_STOP, &CMMA_GDoc::OnUpdateRealtimeStop)
	ON_COMMAND(IDM_EXPORT, &CMMA_GDoc::OnExport)
	ON_UPDATE_COMMAND_UI(IDM_DELETE, &CMMA_GDoc::OnUpdateDelete)
	ON_COMMAND(IDM_CONVERT_MME, &CMMA_GDoc::OnConvertMme)
	ON_COMMAND(IDM_CONVERT_ASU, &CMMA_GDoc::OnConvertAsu)
END_MESSAGE_MAP()


// CMMA_GDoc �R���X�g���N�V����/�f�X�g���N�V����

CMMA_GDoc::CMMA_GDoc()
{
	m_nSelectedItem = 0;
}

CMMA_GDoc::~CMMA_GDoc()
{
}

BOOL CMMA_GDoc::OnNewDocument()
{
	if (!CDocument::OnNewDocument())
		return FALSE;

	return TRUE;
}




// CMMA_GDoc �V���A����

void CMMA_GDoc::Serialize(CArchive& ar)
{
	if (ar.IsStoring())
	{
		// TODO: �i�[����R�[�h�������ɒǉ����Ă��������B
	}
	else
	{
		// TODO: �ǂݍ��ރR�[�h�������ɒǉ����Ă��������B
	}
}


// CMMA_GDoc �f�f

#ifdef _DEBUG
void CMMA_GDoc::AssertValid() const
{
	CDocument::AssertValid();
}

void CMMA_GDoc::Dump(CDumpContext& dc) const
{
	CDocument::Dump(dc);
}
#endif //_DEBUG


// CMMA_GDoc �R�}���h

// �����x�O���t�\��
void CMMA_GDoc::OnDispGraph()
{
	// �I���f�[�^����
	if ((m_nSelectedItem & ID_KIND) == ID_DATA) {
		CString path, fname;
		// �I��No���������AFULLPath���擾����
		if (m_TreeDir.SearchIdx(m_nSelectedItem, path)) {
			// FULLPath��IDB��ǂ�
			CIdbFile idb;
			// IDB�t�@�C�������擾����
			if (idb.GetFname(path, fname)) {
				CGraphFrame* pFrame = new CGraphFrame;
				if (!pFrame->DispGraph(fname, GRAPH_ACCEL, m_nSelectedItem & ID_DATA_TYPE))
					delete pFrame;
			}
		}
	}
}

// ���x�O���t�\��
void CMMA_GDoc::OnDispTempGraph()
{
	// �I���f�[�^����
	if ((m_nSelectedItem & ID_KIND) == ID_DATA) {
		CString path, fname;
		// �I��No���������AFULLPath���擾����
		if (m_TreeDir.SearchIdx(m_nSelectedItem, path)) {
			// FULLPath��IDB��ǂ�
			CIdbFile idb;
			// IDB�t�@�C�������擾����
			if (idb.GetFname(path, fname)) {
				CGraphFrame* pFrame = new CGraphFrame;
				if (!pFrame->DispGraph(fname, GRAPH_TEMP, m_nSelectedItem & ID_DATA_TYPE))
					delete pFrame;
			}
		}
	}
}

// �f�W�^�����l�\��
void CMMA_GDoc::OnDispDigitalData()
{
	// �I���f�[�^����
	if ((m_nSelectedItem & ID_KIND) == ID_DATA) {
		CString path, fname;
		// �I��No���������AFULLPath���擾����
		if (m_TreeDir.SearchIdx(m_nSelectedItem, path)) {
			// FULLPath��IDB��ǂ�
			CIdbFile idb;
			// IDB�t�@�C�������擾����
			if (idb.GetFname(path, fname)) {
				CDigitalDataDlg* pdlg = new CDigitalDataDlg;
				pdlg->DispList(fname, m_nSelectedItem & ID_DATA_TYPE);
			}
		}
	}
}

// MMA�H�w�l�ϊ�����
void CMMA_GDoc::OnConvert()
{
	CConvDlg dlg;
	dlg.SetDocPtr(this);
	dlg.DoModal();
}

// CSV�o�͏���
void CMMA_GDoc::OnOutputCsv()
{
	// �I���f�[�^����
	if ((m_nSelectedItem & ID_KIND) == ID_DATA) {
		CString path, fname;
		// �I��No���������AFULLPath���擾����
		if (m_TreeDir.SearchIdx(m_nSelectedItem, path)) {
			// FULLPath��IDB��ǂ�
			CIdbFile idb;
			// IDB�t�@�C�������擾����
			if (idb.GetFname(path, fname)) {
				CCsvData dlg;
				dlg.DspModal(fname, m_nSelectedItem & ID_DATA_TYPE);
			}
		}
	}
}

// �w�肵���t�H���_���폜����
void CMMA_GDoc::OnDelete()
{
	CString path;

	CString a_strMsg;
	// �\�����镶������쐬����B
	a_strMsg = _T("����PC����C�w��t�H���_�ȉ��́C�H�w�l�ϊ��σt�@�C�����܂ޑS�Ẵt�@�C�����폜����܂��B\n�{���ɍ폜���܂����H");

	// �I��No���������AFULLPath���擾����
	if (m_TreeDir.SearchIdx(m_nSelectedItem, path)) {
		if (AfxMessageBox(a_strMsg, MB_YESNO) == IDYES) {
			CGeneral::DelDir((LPCSTR)path);
			UpdateAllViews(NULL);
		}
	}
}

// ���v���O�����N��
void CMMA_GDoc::OnExec()
{
	WinExec(gToolExe, SW_SHOW);
}

// �ŐV�̏��ɍX�V
void CMMA_GDoc::OnReload()
{
	UpdateAllViews(NULL);
}

// ���ύX����
void CMMA_GDoc::OnChangeInfo()
{
	CString path;

	if (m_TreeDir.SearchIdx(m_nSelectedItem, path)) {
		switch (m_nSelectedItem & ID_DATA_TYPE) {
		case DATA_TYPE_MMA:
			{
				CChangeInfoDlg dlg;
				if (dlg.ChangeInfo(path) == IDOK)
					UpdateAllViews(NULL, (LPARAM)(LPCTSTR)dlg.m_sIdbPathName);
			}
			break;
		case DATA_TYPE_MME:
			{
				CChangeInfoMmeDlg dlg;
				if (dlg.ChangeInfo(path) == IDOK)
					UpdateAllViews(NULL, (LPARAM)(LPCTSTR)dlg.m_sIdbPathName);
			}
			break;
		case DATA_TYPE_ASU:
			{
				CChangeInfoAsuDlg dlg;
				if (dlg.ChangeInfo(path) == IDOK)
					UpdateAllViews(NULL, (LPARAM)(LPCTSTR)dlg.m_sIdbPathName);
			}
			break;
		}
	}
}

// ���ύX���j���[�L�����ݒ�
void CMMA_GDoc::OnUpdateChangeInfo(CCmdUI *pCmdUI)
{
	pCmdUI->Enable((m_nSelectedItem & ID_KIND) == ID_DATA);
}

// �����x�O���t���j���[�L�����ݒ�
void CMMA_GDoc::OnUpdateDispGraph(CCmdUI *pCmdUI)
{
	pCmdUI->Enable((m_nSelectedItem & ID_KIND) == ID_DATA);
}

// ���x�O���t���j���[�L�����ݒ�
void CMMA_GDoc::OnUpdateDispTempGraph(CCmdUI *pCmdUI)
{
	pCmdUI->Enable((m_nSelectedItem & ID_KIND) == ID_DATA && (m_nSelectedItem & ID_DATA_TYPE) == DATA_TYPE_MMA);
}

// ���l�f�[�^���j���[�L�����ݒ�
void CMMA_GDoc::OnUpdateDispDigitalData(CCmdUI *pCmdUI)
{
	pCmdUI->Enable((m_nSelectedItem & ID_KIND) == ID_DATA);
}

// CSV�o�̓��j���[�L�����ݒ�
void CMMA_GDoc::OnUpdateOutputCsv(CCmdUI *pCmdUI)
{
	pCmdUI->Enable((m_nSelectedItem & ID_KIND) == ID_DATA);
}

// ���A���^�C���\���J�n����
void CMMA_GDoc::OnRealtimeStart()
{
	// �J�n�m�F���b�Z�[�W
	if (AfxMessageBox("���A���^�C���\�����J�n���܂����H", MB_OKCANCEL | MB_ICONQUESTION) == IDCANCEL)
		return;

	// ini�t�@�C����REALTIME_DATA_PATH�ݒ�`�F�b�N
	if (gRealtimeDataPath[0] == '\0') {
		AfxMessageBox("ini�t�@�C���� REALTIME_DATA_PATH ���ݒ肳��Ă��Ȃ����߁A���A���^�C���\�����J�n�ł��܂���B", MB_OK | MB_ICONEXCLAMATION);
		return;
	}

	// ini�t�@�C����REALTIME_TEMP_PATH�ݒ�`�F�b�N
	if (gRealtimeTempPath[0] == '\0') {
		AfxMessageBox("ini�t�@�C���� REALTIME_TEMP_PATH ���ݒ肳��Ă��Ȃ����߁A���A���^�C���\�����J�n�ł��܂���B", MB_OK | MB_ICONEXCLAMATION);
		return;
	}

	// ���A���^�C���\���J�n
	g_RealtimeData.Start();
}

// ���A���^�C���\���J�n���j���[�L�����ݒ�
void CMMA_GDoc::OnUpdateRealtimeStart(CCmdUI *pCmdUI)
{
	// ���A���^�C���\�����J�n����Ă��Ȃ���ΗL��
	pCmdUI->Enable(!g_RealtimeData.m_bStartFlag);
}

// ���A���^�C���\���I������
void CMMA_GDoc::OnRealtimeStop()
{
	// �I���m�F���b�Z�[�W
	if (AfxMessageBox("���A���^�C���\�����I�����܂����H", MB_OKCANCEL | MB_ICONQUESTION) == IDCANCEL)
		return;

	// ���A���^�C���\���I��
	g_RealtimeData.Stop();
}

// ���A���^�C���\���I�����j���[�L�����ݒ�
void CMMA_GDoc::OnUpdateRealtimeStop(CCmdUI *pCmdUI)
{
	// ���A���^�C���\�����J�n����Ă���ΗL��
	pCmdUI->Enable(g_RealtimeData.m_bStartFlag);
}

// CSV�t�@�C���G�N�X�|�[�g����
void CMMA_GDoc::OnExport()
{
	CFileDialog dlg(FALSE, "csv", NULL, OFN_OVERWRITEPROMPT, _T("CSV �t�@�C�� (*.csv)|*.csv||"));
	if (dlg.DoModal() == IDOK) {
		CStringArray saCsvData;

		POSITION pos = GetFirstViewPosition();
		CTreeView *pView = (CTreeView *)GetNextView(pos);
		CTreeCtrl &treeCtrl = pView->GetTreeCtrl();
		HTREEITEM hTree = treeCtrl.GetSelectedItem();
		if (hTree != NULL)
			GetCsvData(treeCtrl, hTree, saCsvData);

		CStdioFile oFile;
		oFile.Open(dlg.GetPathName(), CFile::modeCreate | CFile::modeWrite | CFile::shareDenyWrite);

		oFile.WriteString(
			"�f�[�^�͈�,"
			"�f�[�^�J�n����,"
			"ISSConfiguration,"
			"�Z���T�ݒu�ʒu,"
			"�l�b�g���[�NID,"
			"RSU ID,"
			"TAA ID,"
			"SampleRate,"
			"Cutoff Frequency,"
			"GAIN,"
			"BiasCoeff,"
			"ScaleFactor,"
			"Data Quality Measure,"
			"�_�E�������N��,"
			"�f�[�^�ۑ���,"
			"���l\n");

		for (int i = 0; i < saCsvData.GetCount(); i++)
			oFile.WriteString(saCsvData[i] + "\n");
	}
}

// CSV�f�[�^�擾����
void CMMA_GDoc::GetCsvData(CTreeCtrl &treeCtrl, HTREEITEM hTree, CStringArray &saCsvData)
{
	ST_TREE *st = m_TreeDir.GetTreeByIdx((int)treeCtrl.GetItemData(hTree));

	if (st != NULL && (st->m_idx & ID_KIND) == ID_DATA) {
		CIdbFile idb;
		idb.Read(st->m_fullPath);

		CString csv;
		CString buf;
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
		AddCsvData(csv, buf);
		// �^�C���[��
		buf.Format("%s/%s/%s %s:%s:%s"
			, idb.m_timezero.Left(4)
			, idb.m_timezero.Mid(4,2)
			, idb.m_timezero.Mid(6,2)
			, idb.m_timezero.Mid(8,2)
			, idb.m_timezero.Mid(10,2)
			, idb.m_timezero.Mid(12,2));
		AddCsvData(csv, buf);
		// ISS Config
		AddCsvData(csv, idb.m_issconfig);
		// Sensor Co Sys
		AddCsvData(csv, idb.m_sensorcosys);
		// �l�b�g���[�NID
		buf.Format("%d", idb.m_networkid);
		AddCsvData(csv, buf);
		// ���j�b�gID
		buf.Format("%d", idb.m_rsuid);
		AddCsvData(csv, buf);
		// �Z���T�[ID
		buf.Format("%d", idb.m_sensorid);
		AddCsvData(csv, buf);
		// �T���v�����[�g
		buf.Format("%.2lf(Hz)", idb.m_samplerate);
		AddCsvData(csv, buf);
		// �J�b�g�I�t���g��
		buf.Format("%d(Hz)", idb.m_cutoff);
		AddCsvData(csv, buf);
		// �Q�C��
		buf.Format("%d", idb.m_gain);
		AddCsvData(csv, buf);
		// Bias Co Eff
		buf.Format("%d", idb.m_biascoeff);
		AddCsvData(csv, buf);
		// �X�P�[���t�@�N�^�[
		buf.Format("%d", idb.m_scalefactor);
		AddCsvData(csv, buf);
		// Data Quality
		AddCsvData(csv, idb.m_dataquality);
		// �_�E�������N��
		AddCsvData(csv, idb.m_downlink);
		// �ۑ��p�X��
		AddCsvData(csv, idb.m_path);
		// �R�����g
		AddCsvData(csv, idb.m_comment);

		saCsvData.Add(csv);
	} else {
		HTREEITEM hTreeChild = treeCtrl.GetChildItem(hTree);
		while (hTreeChild) {
			GetCsvData(treeCtrl, hTreeChild, saCsvData);
			hTreeChild = treeCtrl.GetNextSiblingItem(hTreeChild);
		}
	}
}

void CMMA_GDoc::AddCsvData(CString &csv, CString data)
{
	data.Replace("\"", "\"\"");

	if (data.FindOneOf(",") != -1)
		data = "\"" + data + "\"";

	if (!csv.IsEmpty())
		csv += ",";

	csv += data;
}

void CMMA_GDoc::OnUpdateDelete(CCmdUI *pCmdUI)
{
	pCmdUI->Enable((m_nSelectedItem & ID_KIND) == ID_FOLDER || (m_nSelectedItem & ID_KIND) == ID_DATA);
}

// MMA�H�w�l�ϊ�����
void CMMA_GDoc::OnConvertMme()
{
	CConvMmeDlg dlg;
	dlg.ShowDialog(this);
}

void CMMA_GDoc::OnConvertAsu()
{
	CConvAsuDlg dlg;
	dlg.ShowDialog(this);
}
