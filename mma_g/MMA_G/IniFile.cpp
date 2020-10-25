#include "StdAfx.h"
#include "IniFile.h"
#include "GlobalData.h"
#include "General.h"

#define	ROOT_PATH		"c:\\MMA-G"
#define	DATA_PATH		"\\data"
#define	NAME_INI		"MMA_G.ini"

#define	SEC_PATH		"PATH"
#define	KEY_ROOT_PATH	"ROOT_PATH"
#define	KEY_REAL_TIME_DATA_PATH	"REALTIME_DATA_PATH"
#define	KEY_REAL_TIME_TEMP_PATH	"REALTIME_TEMP_PATH"
#define	KEY_DOWNLINK_REFERENCE_PATH	"DOWNLINK_REFERENCE_PATH"
#define KEY_AUTO_CONV_PATH "AUTO_CONV_PATH"

#define	SEC_TOOL		"TOOL"
#define	KEY_EXE_PATH	"EXE_PATH"

#define	SEC_LAUNCH		"LAUNCH"
#define	KEY_LAUNCH_TIME	"LAUNCH_TIME"

#define SEC_SETTING		"SETTING"
#define KEY_ZERO_OFFSET_SW	"ZERO_OFFSET_SW"
#define KEY_SPECTRUM_PSD_RANGE	"SPECTRUM_PSD_RANGE"
#define KEY_ZERO_OFFSET_SPAN	"ZERO_OFFSET_SPAN"
#define KEY_REALTIME_RANGE	"REALTIME_RANGE"

#define SEC_CHOICE		"CHOICE"
#define KEY_CHOICE_CATEGORY	"CATEGORY"
#define KEY_CHOICE_MEASUREMENT_KIND	"MEASUREMENT_KIND"

// ini�t�@�C���̏����擾����
bool CIniFile::Read(void)
{
	CString	iniName;

	// �N�����ꂽ�A�v���P�[�V�����̃t���p�X�擾
	SetExeDir();
	iniName = gExePath;
	iniName += '\\';
	iniName += NAME_INI;

	// �f�[�^�i�[�t�H���_���擾����
	GetPrivateProfileString(
		SEC_PATH,			// �Z�N�V������
		KEY_ROOT_PATH,		// �L�[��
		ROOT_PATH,			// �����̕�����
		gRootPath,			// ��񂪊i�[�����o�b�t�@
		sizeof(gRootPath),	// ���o�b�t�@�̃T�C�Y
		(LPCSTR)iniName		// .ini�t�@�C����
	);

	// �Z�~�R�����łȂ��������̃p�X�𕪉�����
	int nPos= 0;
	CString sRootPath = gRootPath;
	CString sToken = sRootPath.Tokenize(";", nPos);
	while (sToken != "") {
		sToken = sToken.TrimRight('\\');
		if (gRootDataList.GetSize() == 0)
			strcpy_s(gRootPath, sToken);
		gRootDataList.Add(sToken + DATA_PATH);
		sToken = sRootPath.Tokenize(";", nPos);
	}

	// �H�w�l�f�[�^�i�[�t�H���_��ҏW����
	strcpy_s(gRootData, gRootPath);
	strcat_s(gRootData, DATA_PATH);

	// �c�[��EXE���擾����
	GetPrivateProfileString(
		SEC_TOOL,					// �Z�N�V������
		KEY_EXE_PATH,				// �L�[��
		"",							// �����̕�����
		gToolExe,					// ��񂪊i�[�����o�b�t�@
		sizeof(gToolExe),			// ���o�b�t�@�̃T�C�Y
		iniName						// .ini�t�@�C����
	);

	// LaunchTime���擾����
	GetPrivateProfileString(
		SEC_LAUNCH,					// �Z�N�V������
		KEY_LAUNCH_TIME,			// �L�[��
		"YYYY_MM_DD_hh_mm_ss.ttt",	// �����̕�����
		gLaunchTime,				// ��񂪊i�[�����o�b�t�@
		sizeof(gLaunchTime),		// ���o�b�t�@�̃T�C�Y
		iniName						// .ini�t�@�C����
	);

	// ���A���^�C���f�[�^�p�X���擾����
	GetPrivateProfileString(
		SEC_PATH,					// �Z�N�V������
		KEY_REAL_TIME_DATA_PATH,	// �L�[��
		"",							// �����̕�����
		gRealtimeDataPath,			// ��񂪊i�[�����o�b�t�@
		sizeof(gRealtimeDataPath),	// ���o�b�t�@�̃T�C�Y
		iniName						// .ini�t�@�C����
	);

	// ���A���^�C���e���|�����p�X���擾����
	GetPrivateProfileString(
		SEC_PATH,					// �Z�N�V������
		KEY_REAL_TIME_TEMP_PATH,	// �L�[��
		"",							// �����̕�����
		gRealtimeTempPath,			// ��񂪊i�[�����o�b�t�@
		sizeof(gRealtimeTempPath),	// ���o�b�t�@�̃T�C�Y
		iniName						// .ini�t�@�C����
	);

	// �_�E�������N�t�H���_�Q�ƃp�X���擾����
	GetPrivateProfileString(
		SEC_PATH,						// �Z�N�V������
		KEY_DOWNLINK_REFERENCE_PATH,	// �L�[��
		"",								// �����̕�����
		gDownlinkReferencePath,			// ��񂪊i�[�����o�b�t�@
		sizeof(gDownlinkReferencePath),	// ���o�b�t�@�̃T�C�Y
		iniName							// .ini�t�@�C����
	);

	// 0�_�␳�L���f�t�H���g�l���擾����
	char tZeroOffsetSw[256];
	GetPrivateProfileString(
		SEC_SETTING,					// �Z�N�V������
		KEY_ZERO_OFFSET_SW,				// �L�[��
		"",								// �����̕�����
		tZeroOffsetSw,					// ��񂪊i�[�����o�b�t�@
		sizeof(tZeroOffsetSw),			// ���o�b�t�@�̃T�C�Y
		iniName							// .ini�t�@�C����
	);
	gZeroOffsetSw = (_stricmp(tZeroOffsetSw, "ON") == 0);

	// �X�y�N�g����PSD�͈̓f�t�H���g�l���擾����
	char tSpectrumPsdRange[256];
	GetPrivateProfileString(
		SEC_SETTING,					// �Z�N�V������
		KEY_SPECTRUM_PSD_RANGE,			// �L�[��
		"0",							// �����̕�����
		tSpectrumPsdRange,				// ��񂪊i�[�����o�b�t�@
		sizeof(tSpectrumPsdRange),		// ���o�b�t�@�̃T�C�Y
		iniName							// .ini�t�@�C����
	);
	gSpectrumPsdRange = atof(tSpectrumPsdRange);

	// 0�_�␳�l�O���t���ԊԊu���擾����
	gZeroOffsetSpan = GetPrivateProfileInt(
		SEC_SETTING,					// �Z�N�V������
		KEY_ZERO_OFFSET_SPAN,			// �L�[��
		3600,							// ����̐��l
		iniName							// .ini�t�@�C����
	);

	// �����H�w�l�ϊ��t�H���_�p�X���擾����
	GetPrivateProfileString(
		SEC_PATH,						// �Z�N�V������
		KEY_AUTO_CONV_PATH,				// �L�[��
		"",								// ����̕�����
		gAutoConvPath,					// ��񂪊i�[�����o�b�t�@
		sizeof(gAutoConvPath),			// ���o�b�t�@�̃T�C�Y
		iniName							// .ini�t�@�C����
	);

	// �����A���^�C���\���͈�
	gRealtimeRange = GetPrivateProfileInt(
		SEC_SETTING,					// �Z�N�V������
		KEY_REALTIME_RANGE,				// �L�[��
		60,								// ����̐��l
		iniName							// .ini�t�@�C����
	);

	// �J�e�S���I�������擾����
	char tChoiceCategory[1024];
	GetPrivateProfileString(
		SEC_CHOICE,						// �Z�N�V������
		KEY_CHOICE_CATEGORY,			// �L�[��
		"",								// �����̕�����
		tChoiceCategory,				// ��񂪊i�[�����o�b�t�@
		sizeof(tChoiceCategory),		// ���o�b�t�@�̃T�C�Y
		iniName							// .ini�t�@�C����
	);
	CGeneral::StrSplit(tChoiceCategory, ";", gChoiceCategory);

	// �v����ޑI�������擾����
	char tChoiceMeasurementKind[1024];
	GetPrivateProfileString(
		SEC_CHOICE,						// �Z�N�V������
		KEY_CHOICE_MEASUREMENT_KIND,	// �L�[��
		"",								// �����̕�����
		tChoiceMeasurementKind,			// ��񂪊i�[�����o�b�t�@
		sizeof(tChoiceMeasurementKind),	// ���o�b�t�@�̃T�C�Y
		iniName							// .ini�t�@�C����
	);
	CGeneral::StrSplit(tChoiceMeasurementKind, ";", gChoiceMeasurementKind);

	return true;
}

// �N�����ꂽ�A�v���P�[�V�����̃t���p�X�擾
void CIniFile::SetExeDir(void)
{
	TCHAR szFull[_MAX_PATH];    // �N�����ꂽ�A�v���P�[�V�����̃t���p�X��
	TCHAR szDrive[_MAX_DRIVE];  // �N�����ꂽ�A�v���P�[�V�����̃h���C�u��
	TCHAR szDir[_MAX_DIR];      // �N�����ꂽ�A�v���P�[�V�����̃f�B���N�g����

	memset(szFull, 0, sizeof(szFull));
	memset(szDrive, 0, sizeof(szDrive));
	memset(szDir, 0, sizeof(szDir));

	// �N�����ꂽ�A�v���P�[�V�����̃t���p�X�����擾
	::GetModuleFileName(NULL, szFull, sizeof(szFull) / sizeof(TCHAR));
	// �t���p�X�����h���C�u����f�B���N�g���������ɕ���
	_splitpath_s(szFull, szDrive, sizeof(szDrive)
		, szDir, sizeof(szDir)
		, NULL, 0, NULL, 0);
	// �h���C�u���ƃf�B���N�g����������A��
	_makepath_s(gExePath, szDrive, szDir, NULL, NULL);
	int len;
	if ((len = (int)strlen(gExePath)) != 0) {
		if (gExePath[len - 1] == '\\')
			gExePath[len - 1] = 0;
	}
}
