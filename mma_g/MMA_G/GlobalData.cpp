#include "stdafx.h"
#include "GlobalData.h"

const SENSOR_INFO SensorInfo[] = {
	{0, "1:JPM A2 Upper-left", "JPM_A2_Upper-left"
		, "Overhead", "Starboard", "Forward"
		, 387.475748031, -170.754803150, 156.335826772
	},
	{1, "2:JPM A3 Upper-left", "JPM_A3_Upper-left"
		, "Overhead", "Starboard", "Forward"
		, 387.475748031, -212.754803150, 156.335826772
	},
	{2, "3:JPM A3 on FPEF", "JPM_A3_on_FPEF"
		, "Forward", "Port", "Overhead"
		, 393.337952756, -222.707559055, 158.572047244
	},
	{3, "4:JPM A3 SCOF REF-CELL", "JPM_A3_SCOF_REF-CELL"
		, "Forward", "Port", "Overhead"
		, 383.378307087, -236.872913386, 191.977559055
	},
	{-1, NULL, NULL, NULL, NULL, NULL, 0.0, 0.0, 0.0}
};

const char *g_tWindowFunc[] = {
	/*"Rectangular"*/"Boxcar", "Triangular", "Hamming", "Hanning", "Blackman", "Blackman-Harris", "Flat-Top"
};

char	gRootPath[_MAX_PATH];	// �f�[�^�i�[ROOT�p�X
char	gRootData[_MAX_PATH];	// �H�w�l�f�[�^�i�[�p�X
char	gExePath[_MAX_PATH];	// �{�v���O����EXE�̃p�X
char	gToolExe[_MAX_PATH];	// �c�[���p�O���v���O�����p�X
char	gRealtimeDataPath[_MAX_PATH];	// ���A���^�C���f�[�^�p�X
char	gRealtimeTempPath[_MAX_PATH];	// ���A���^�C���e���|�����p�X
char	gDownlinkReferencePath[_MAX_PATH];	// �_�E�������N�t�H���_�Q�ƃp�X
char	gLaunchTime[64];		// LaunchTime
CStringArray	gRootDataList;	// �c���[�\���f�[�^�p�X�i�����Ή��j
BOOL	gZeroOffsetSw;			// 0�_�␳�L���f�t�H���g�l
double	gSpectrumPsdRange;		// �X�y�N�g����PSD�͈̓f�t�H���g�l
int		gZeroOffsetSpan;		// 0�_�␳�l�O���t���ԊԊu
char	gAutoConvPath[_MAX_PATH];	// �����H�w�l�ϊ��p�X
int		gRealtimeRange;			// �����A���^�C���\���͈�
CStringArray	gChoiceCategory;	// �J�e�S���I����
CStringArray	gChoiceMeasurementKind;	// �v����ޑI����

SENSOR_INFO* g_pSensorInfo;
SENSOR_INFO* g_pSensorInfoMme;
SENSOR_INFO* g_pSensorInfoAsu;
int		     g_iInfoNum;

double g_SF_x;
double g_SF_y;
double g_SF_z;
double g_BIAS_x;
double g_BIAS_y;
double g_BIAS_z;
