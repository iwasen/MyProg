#pragma once

typedef struct TAG_KEISUU_INFO {
	double A4;
	double A3;
	double A2;
	double A1;
	double A0;
	double B4;
	double B3;
	double B2;
	double B1;
	double B0;
	double C4;
	double C3;
	double C2;
	double C1;
	double C0;
	double D4;
	double D3;
	double D2;
	double D1;
	double D0;
	double E4;
	double E3;
	double E2;
	double E1;
	double E0;
} KEISUU_INFO;

typedef struct TAG_SENSOR_INFO {
	int	idx;
	char *sel_dat;
	char *dat;
	char *dir_x;
	char *dir_y;
	char *dir_z;
	double	x;
	double	y;
	double	z;
// 2008/4/25 appended by yG for rpw addition ->
	double	roll;
	double	pitch;
	double	yaw;
// <- 2008/4/25 appended by yG for rpw addition
	int rsu_id;
	char *category;
	char *measurement_kind;
} SENSOR_INFO;

extern const SENSOR_INFO SensorInfo[];
extern SENSOR_INFO* g_pSensorInfo;
extern SENSOR_INFO* g_pSensorInfoMme;
extern SENSOR_INFO* g_pSensorInfoAsu;
extern char	gRootPath[_MAX_PATH];	// �f�[�^�i�[ROOT�p�X
extern char	gRootData[_MAX_PATH];	// �H�w�l�f�[�^�i�[�p�X
extern char	gExePath[_MAX_PATH];	// �{�v���O����EXE�̃p�X
extern char	gToolExe[_MAX_PATH];	// �c�[���p�O���v���O�����p�X
extern char	gRealtimeDataPath[_MAX_PATH];	// ���A���^�C���f�[�^�p�X
extern char	gRealtimeTempPath[_MAX_PATH];	// ���A���^�C���e���|�����p�X
extern char	gDownlinkReferencePath[_MAX_PATH];	// �_�E�������N�t�H���_�Q�ƃp�X
extern char	gLaunchTime[64];		// LaunchTime
extern CStringArray	gRootDataList;	// �c���[�\���f�[�^�p�X�i�����Ή��j
extern BOOL	gZeroOffsetSw;			// 0�_�␳�L���f�t�H���g�l
extern double	gSpectrumPsdRange;		// �X�y�N�g����PSD�͈̓f�t�H���g�l
extern int		gZeroOffsetSpan;		// 0�_�␳�l�O���t���ԊԊu
extern char	gAutoConvPath[_MAX_PATH];	// �����H�w�l�ϊ��p�X
extern int		gRealtimeRange;			// �����A���^�C���\���͈�
extern CStringArray	gChoiceCategory;	// �J�e�S���I����
extern CStringArray	gChoiceMeasurementKind;	// �v����ޑI����
extern const char	*g_tWindowFunc[];		// ���֐�
extern int		g_iInfoNum;
extern double g_SF_x;
extern double g_SF_y;
extern double g_SF_z;
extern double g_BIAS_x;
extern double g_BIAS_y;
extern double g_BIAS_z;
