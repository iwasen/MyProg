// ChangeInfoDlg.cpp : �����t�@�C��
//

#include "stdafx.h"
#include "MMA_G.h"
#include "ChangeInfoDlg.h"
#include "GlobalData.h"
#include "General.h"
#include <io.h>


// CChangeInfoDlg �_�C�A���O

IMPLEMENT_DYNAMIC(CChangeInfoDlg, CDialog)

CChangeInfoDlg::CChangeInfoDlg(CWnd* pParent /*=NULL*/)
	: CDialog(CChangeInfoDlg::IDD, pParent)
{

}

CChangeInfoDlg::~CChangeInfoDlg()
{
}

void CChangeInfoDlg::DoDataExchange(CDataExchange* pDX)
{
	CDialog::DoDataExchange(pDX);
	DDX_Control(pDX, IDC_COMBO_SENSOR, m_cComboSensor);
	DDX_Control(pDX, IDC_EDIT_DOWNLINK, m_cEditDownlink);
	DDX_Control(pDX, IDC_EDIT_QUALITY, m_cEditQuality);
	DDX_Control(pDX, IDC_EDIT_ISS, m_cEditIss);
	DDX_Control(pDX, IDC_EDIT_ISS2, m_cEditIss2);
	DDX_Control(pDX, IDC_EDIT_COMMENT, m_cEditComment);
	DDX_Control(pDX, IDC_COMBO_CATEGORY, m_cComboCategory);
	DDX_Control(pDX, IDC_COMBO_MEASUREMENT_KIND, m_cComboMeasurementKind);
}


BEGIN_MESSAGE_MAP(CChangeInfoDlg, CDialog)
END_MESSAGE_MAP()

// �c�[���`�b�v�\���f�[�^
static const SToolTipData s_aToolTip[] = {
	{IDOK, "�ݒ肳�ꂽ���e�Ƀf�[�^�t�@�C�������������܂��B"},
	{IDCANCEL, "���̃_�C�A���O����܂��B"},
	{0, NULL}
};

//*****************************************************************************************************
//  1. �֐���
//		CChangeInfoDlg::ChangeInfo
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���ύX�_�C�A���O�\������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CString		&sIdbPathName			[I] Idb�t�@�C���p�X
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		INT_PTR		�_�C�A���O�̕Ԓl
//*****************************************************************************************************
INT_PTR CChangeInfoDlg::ChangeInfo(const CString &sIdbPathName)
{
	m_sIdbPathName = sIdbPathName;

	return DoModal();
}

// CChangeInfoDlg ���b�Z�[�W �n���h��

//*****************************************************************************************************
//  1. �֐���
//		CChangeInfoDlg::OnInitDialog
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�_�C�A���O����������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		BOOL	TRUE
//*****************************************************************************************************
BOOL CChangeInfoDlg::OnInitDialog()
{
	CDialog::OnInitDialog();

	// idb�t�@�C���ǂݍ���
	m_oIdbFile.Read(m_sIdbPathName);

	// �Z���T�ݒ�ʒu�R���{�{�b�N�X�ݒ�
	for (int i = 0; g_pSensorInfo[i].idx != -1; i++) {
		m_cComboSensor.SetItemData(m_cComboSensor.InsertString(g_pSensorInfo[i].idx, g_pSensorInfo[i].sel_dat), i);
		if (m_oIdbFile.m_sensorcosys == g_pSensorInfo[i].dat)
			m_cComboSensor.SetCurSel(g_pSensorInfo[i].idx);
	}

	// ISS Conf�𕪉�
	CStringArray saIss;
	CString sIss;
	CString sIss2;
	CGeneral::StrSplit(m_oIdbFile.m_issconfig, ",", saIss);
	if (saIss.GetSize() >= 2) {
		int nPos;
		nPos = saIss[0].Find(':');
		if (nPos != -1)
			sIss = saIss[0].Mid(nPos + 1).Trim();
		nPos = saIss[1].Find(':');
		if (nPos != -1)
			sIss2 = saIss[1].Mid(nPos + 1).Trim();
	}

	// �_�C�A���O�ɕ\��
	m_cEditDownlink = m_oIdbFile.m_downlink;
	m_cEditQuality = m_oIdbFile.m_dataquality;
	m_cEditIss = sIss;
	m_cEditIss2 = sIss2;
	m_cEditComment = m_oIdbFile.m_comment;
	CGeneral::SetComboCategory(m_cComboCategory, m_oIdbFile.m_sCategory);
	CGeneral::SetComboMeasurementKind(m_cComboMeasurementKind, m_oIdbFile.m_sMeasurementKind);

	// �c�[���`�b�v�\���ݒ�
	CGeneral::SetToolTip(m_tooltip, s_aToolTip, this);

	return TRUE;
}

//*****************************************************************************************************
//  1. �֐���
//		CChangeInfoDlg::OnOK
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		OK�{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CChangeInfoDlg::OnOK()
{
	CString str;

	// �Z���T�ݒ�ʒu���̓`�F�b�N
	if (m_cComboSensor.GetCurSel() == -1) {
		CGeneral::Alert("�Z���T�ݒ�ʒu��I�����Ă��������B");
		return;
	}

	// �J�e�S�����̓`�F�b�N
	m_cComboCategory.GetWindowText(str);
	if (str.IsEmpty()) {
		CGeneral::Alert("�J�e�S����I�����Ă��������B");
		return;
	}
	if (!CGeneral::CheckFileName("�J�e�S��", str))
		return;

	// �v����ޓ��̓`�F�b�N
	m_cComboMeasurementKind.GetWindowText(str);
	if (str.IsEmpty()) {
		CGeneral::Alert("�v����ނ�I�����Ă��������B");
		return;
	}
	if (!CGeneral::CheckFileName("�v�����", str))
		return;

	// ���s�m�F
	if (AfxMessageBox("�ݒ肵�����e�ɏ���ύX���܂����H", MB_OKCANCEL | MB_ICONQUESTION) == IDCANCEL)
		return;

	// �_�C�A���O����idb�f�[�^�ɐݒ�
	m_oIdbFile.m_sensorcosys = g_pSensorInfo[m_cComboSensor.GetItemData(m_cComboSensor.GetCurSel())].dat;
// 2009/07/07 Version 3.02 appended by m.hama ->
	m_oIdbFile.SetSensorIdx((int)m_cComboSensor.GetItemData(m_cComboSensor.GetCurSel()));
	m_oIdbFile.m_datacosys = m_oIdbFile.m_sensorcosys;
// <- 2009/07/07 Version 3.02 appended by m.hama
	m_oIdbFile.m_downlink = m_cEditDownlink;
	m_oIdbFile.m_dataquality = m_cEditQuality;
	if (!m_cEditIss.IsEmpty() || !m_cEditIss2.IsEmpty())
		m_oIdbFile.m_issconfig.Format("Increment: %s, Flight: %s", (CString)m_cEditIss, (CString)m_cEditIss2);
	else
		m_oIdbFile.m_issconfig.Empty();
	m_oIdbFile.m_comment = m_cEditComment;
	m_cComboCategory.GetWindowText(m_oIdbFile.m_sCategory);
	m_cComboMeasurementKind.GetWindowText(m_oIdbFile.m_sMeasurementKind);

	// �t�H���_���X�V
	if (!ChangeFolder())
		return;

	// idb�t�@�C���X�V
	CString sFileName;
	if (FindFileName("idb", sFileName))
		m_oIdbFile.Write(sFileName.Left(sFileName.GetLength() - 4));

	// XML�t�@�C���X�V
// 2009/07/07 Version 3.02 modified by m.hama ->
//	if (FindFileName("header", sFileName)) {
//		m_oIdbFile.SetTimeZeroXML(sFileName.Left(23));
//		m_oIdbFile.WriteXml(sFileName.Left(sFileName.GetLength() - 7));
//	}
	WriteXmlFile();
// <- 2009/07/07 Version 3.02 modified by m.hama

	AfxMessageBox("����ύX���܂����B", MB_OK | MB_ICONINFORMATION);

	CDialog::OnOK();
}

//*****************************************************************************************************
//  1. �֐���
//		CChangeInfoDlg::ChangeFolder
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�t�H���_����ύX����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		BOOL	TRUE�F����@FALSE�F�G���[
//*****************************************************************************************************
BOOL CChangeInfoDlg::ChangeFolder()
{
	CStringArray saPath;
	CString sNewPath;

	// ���p�X���̃Z���T�ʒu��ύX���ĐV�����p�X�����쐬
	CGeneral::StrSplit(m_sIdbPathName, "\\", saPath);
	CString sMaiFileName;
	if (saPath[saPath.GetSize() - 2].Right(4).MakeLower() == ".mai")
		sMaiFileName = saPath[saPath.GetSize() - 2];
	else if (saPath[saPath.GetSize() - 1].Right(4).MakeLower() == ".mai")
		sMaiFileName = saPath[saPath.GetSize() - 1];
	CGeneral::StrSplit(gRootData, "\\", saPath);
	saPath.Add(m_oIdbFile.m_sensorcosys);
	saPath.Add(m_oIdbFile.m_sCategory);
	saPath.Add(m_oIdbFile.m_sMeasurementKind);
	saPath.Add(sMaiFileName);
	CGeneral::StrJoin(saPath, "\\", sNewPath);

	// ���p�X���Ɠ����Ȃ牽�����Ȃ�
	if (sNewPath == m_sIdbPathName)
		return TRUE;

	// ���p�X���Ƀ��C���h�J�[�h��t���i������'\0'���t���j
	CString sOldPath;
	sOldPath.Format("%s\\*%c", m_sIdbPathName, '\0');

	// �t�@�C���ړ�����
	SHFILEOPSTRUCT sf;
	memset(&sf, 0, sizeof(sf));
	sf.hwnd = m_hWnd;
	sf.wFunc = FO_MOVE;
	sf.pFrom = sOldPath;
	sf.pTo = sNewPath;
	sf.fFlags = FOF_SILENT | FOF_NOCONFIRMATION | FOF_NOCONFIRMMKDIR;
	if (SHFileOperation(&sf) != 0) {
		CGeneral::Alert("�t�@�C���̈ړ��Ɏ��s���܂����B");
		return FALSE;
	}

	// ���p�X���폜
	CGeneral::StrSplit(m_sIdbPathName, "\\", saPath);
	for (int i = 0; i < 3; i++) {
		CGeneral::StrJoin(saPath, "\\", sOldPath);
		if (!::RemoveDirectory(sOldPath))
			break;

		saPath.RemoveAt(saPath.GetSize() - 1);
	}

	// �V�����p�X��ۑ�
	m_sIdbPathName = sNewPath;
	m_oIdbFile.m_path = sNewPath;

	return TRUE;
}

//*****************************************************************************************************
//  1. �֐���
//		CChangeInfoDlg::FindFileName
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�w�肳�ꂽ�g���q�̃t�@�C������������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		LPCTSTR		pExt			[I] �g���q
//		CString		&sFileName		[O] �������ꂽ�t�@�C����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		BOOL		TRUE�F����@FALSE�F�G���[
//*****************************************************************************************************
BOOL CChangeInfoDlg::FindFileName(LPCTSTR pExt, CString &sFileName)
{
	CString sPath;
	struct _finddata_t c_file;
    intptr_t hFile;

	sPath.Format("%s\\*.%s", m_sIdbPathName, pExt);
	if ((hFile = _findfirst(sPath, &c_file )) != -1L) {
		sFileName = c_file.name;
		_findclose(hFile);
		return TRUE;
	}

	return FALSE;
}

// 2009/07/07 Version 3.02 appended by m.hama ->
//*****************************************************************************************************
//  1. �֐���
//		CChangeInfoDlg::WriteXmlFile
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�w�肳�ꂽ�g���q�̃t�@�C������������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//*****************************************************************************************************
void	CChangeInfoDlg::WriteXmlFile(void)
{
	CString sPath, sFileName;
	struct _finddata_t c_file;
    intptr_t hFile;

	sPath.Format("%s\\*.header", m_sIdbPathName);
	if ((hFile = _findfirst(sPath, &c_file )) != -1L) {
		do {
			sFileName = c_file.name;
			m_oIdbFile.WriteXml2(sFileName.Left(sFileName.GetLength() - 7));
		} while(_findnext( hFile, &c_file) == 0);
		_findclose(hFile);
	}
}
// <- 2009/07/07 Version 3.02 appended by m.hama


//*****************************************************************************************************
//  1. �֐���
//		CChangeInfoDlg::PreTranslateMessage
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�E�B���h�E���b�Z�[�W����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		MSG*	pMsg			[I] MSG�\���̂ւ̃|�C���^
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		BOOL	�e�N���X�̕Ԓl�����̂܂ܕԂ�
//*****************************************************************************************************
BOOL CChangeInfoDlg::PreTranslateMessage(MSG* pMsg)
{
	// �c�[���`�b�v��\�����邽�߂̏���
	switch(pMsg->message){
	case WM_LBUTTONDOWN:            
	case WM_LBUTTONUP:              
	case WM_MOUSEMOVE:
		if (m_tooltip.m_hWnd)
			m_tooltip.RelayEvent(pMsg);
		break;
	}

	return CDialog::PreTranslateMessage(pMsg);
}
