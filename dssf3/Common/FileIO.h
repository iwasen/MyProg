#pragma once

#include "pcscl.h"

// �t�H���_�t�@�C����
#define FN_FOLDER_DBF	"FOLDER.DBF"
#define FN_FOLDER1_EDX	"FOLDER1.EDX"

// �C���p���X��������f�[�^�t�@�C����
#define FN_IMPULSE_DBF	"IMPULSE.DBF"
#define FN_IMPULSE_BIN	"IMPULSE.BIN"
#define FN_IMPULSE1_EDX	"IMPULSE1.EDX"
#define FN_IMPULSE2_EDX	"IMPULSE2.EDX"

// Running ACF ����f�[�^�t�@�C��
#define FN_ACF_DBF		"ACF.DBF"
#define FN_ACF_BIN		"ACF.BIN"
#define FN_ACF1_EDX		"ACF1.EDX"
#define FN_ACF2_EDX		"ACF2.EDX"

// ��������f�[�^�t�@�C��
#define FN_NMS_DBF		"NMS.DBF"
#define FN_NMS_BIN		"NMS.BIN"
#define FN_NMS1_EDX		"NMS1.EDX"
#define FN_NMS2_EDX		"NMS2.EDX"

// �������e���v���[�g�t�@�C��
#define FN_NSTMP_DBF	"NSTMP.DBF"
#define FN_NSTMP_BIN	"NSTMP.BIN"
#define FN_NSTMP1_EDX	"NSTMP1.EDX"
#define FN_NSTMP2_EDX	"NSTMP2.EDX"

// ����������d�݌W���t�@�C��
#define FN_NSWEIGHT_DAT	"NSWEIGHT.DAT"

// �����p�����[�^�v�Z���ʃt�@�C����
#define FN_ACPARAM_DBF	"ACPARAM.DBF"
#define FN_ACPARAM1_EDX	"ACPARAM1.EDX"

// Running ACF �v�Z���ʃt�@�C����
#define FN_ACFFACT_DBF	"ACFFACT.DBF"
#define FN_ACFFACT_BIN	"ACFFACT.BIN"
#define FN_ACFFACT1_EDX	"ACFFACT1.EDX"

// Running ACF �}�[�J�[
#define FN_ACFMARKER_DBF	"ACFMARK.DBF"
#define FN_ACFMARKER1_EDX	"ACFMARK1.EDX"
#define FN_ACFMARKER2_EDX	"ACFMARK2.EDX"

// �t�t�B���^�t�@�C����
#define FN_IFILTER_DBF	"IFILTER.DBF"
#define FN_IFILTER1_EDX	"IFILTER1.EDX"

// �}�C�N�r���t�@�C����
#define FN_MICCAL_DBF	"MICCAL.DBF"
#define FN_MICCAL1_EDX	"MICCAL1.EDX"

// �v���Z�b�g�t�@�C����
#define FN_PRESET_DBF	"PRESET5.DBF"
#define FN_PRESET_BIN	"PRESET5.BIN"
#define FN_PRESET1_EDX	"PRESET5.EDX"

// �g�`�f�[�^�t�@�C����
#define FN_WAVEFORM_DBF	"WAVEFORM.DBF"
#define FN_WAVEFORM_BIN	"WAVEFORM.BIN"
#define FN_WAVEFORM1_EDX	"WAVEFORM1.EDX"

// �e���|�����t�@�C����
#define FN_TEMP_DBF		"TEMP.DBF"
#define FN_TEMP_BIN		"TEMP.BIN"

// �t�H���_�^�C�v
#define FOLDER_TYPE_IR	'I'		// �C���p���X����
#define FOLDER_TYPE_ACF	'A'		// Running ACF
#define FOLDER_TYPE_NMS	'N'		// �����v��

#define ACPARAM_NFREQ	34		// �����p�����[�^���g���ш�ʃf�[�^��
#define MICCAL_NFREQ	20		// �}�C�N�r�����g���f�[�^��

// ����������������[�h
#define IDENT_MODE_MAXSPL	0	// �ő�SPL
#define IDENT_MODE_MINTAUE	1	// �ŏ���e

// �f�[�^�x�[�X�t�B�[���h��
#define LEN_ID			8		// �h�c
#define LEN_TYPE		1		// �t�H���_�^�C�v
#define LEN_TITLE		30		// �^�C�g��
#define LEN_NAME		20		// ����Ҏ���
#define LEN_PLACE		20		// ����ꏊ
#define LEN_DATE		8		// �쐬���t
#define LEN_COMMENT		100		// �R�����g
#define LEN_SAMPLING	6		// �T���v�����O���[�g
#define LEN_MESTIME		2		// �C���p���X�������莞��
#define LEN_MESNUM		3		// �C���p���X�����������Z��
#define LEN_CHANNEL		1		// �T���v�����O�`���l����
#define LEN_BIT			2		// �T���v�����O�r�b�g��
#define LEN_TIME		14		// �������
#define LEN_MULTIPLIER	5		// �C���p���X�����o�̓��x���{��
#define LEN_NDATA		2		// �����p�����[�^�f�[�^��
#define LEN_FREQBAND	1		// ���g���ш�i1Oct,1/3Oct)
#define LEN_FLOAT		8		// ���������f�[�^
#define LEN_BOOL		1		// BOOL �f�[�^
#define LEN_SHORT		6		// short �f�[�^
#define LEN_RESERVE		8		// ���U�[�u
#define LEN_MICINFO		30		// �}�C�N���i�}�C�N�r���j
#define LEN_MICAMPINFO	30		// �}�C�N�A���v���i�}�C�N�r���j
#define LEN_MAXLEVEL	3		// �t�t�B���^�ő�⏞���x��
#define LEN_SIZE		5		// �f�[�^�T�C�Y
#define LEN_NACFFACT	6		// Running ACF �v�Z���ʃf�[�^��
#define LEN_SAMPLENUM	10		// Running ACF �T���v�����O�f�[�^��
#define LEN_NNMSFACT	6		// �����v�Z���ʃf�[�^��
#define LEN_NSTMPNAME	32		// ����������
#define	LEN_WAVEFORMNUM	4		// �g�`�f�[�^��

#pragma pack(4)

// �t�H���_�t�@�C���o�b�t�@
struct DbFolderBuf {
	char	aFolderID[LEN_ID];
	char	aType[LEN_TYPE];
	char	aName[LEN_NAME];
	char	aPlace[LEN_PLACE];
	char	aDate[LEN_DATE];
	char	aTitle[LEN_TITLE];
	char	aComment[LEN_COMMENT];
	char	aScale[LEN_FLOAT];
};

// �t�H���_�t�@�C�����R�[�h
struct DbFolderRec {
	long	nFolderID;
	CString	sType;
	CString	sName;
	CString	sPlace;
	CString	sDate;
	CString	sTitle;
	CString	sComment;
	double fScale;
};

// �C���p���X��������f�[�^�t�@�C���o�b�t�@
struct DbImpulseBuf {
	char	aFolderID[LEN_ID];
	char	aImpulseID[LEN_ID];
	char	aSampling[LEN_SAMPLING];
	char	aMeasTime[LEN_MESTIME];
	char	aMeasNum[LEN_MESNUM];
	char	aChannel[LEN_CHANNEL];
	char	aBit[LEN_BIT];
	char	aTime[LEN_TIME];
	char	aTitle[LEN_TITLE];
	char	aComment[LEN_COMMENT];
	char	aUseFilter[LEN_BOOL];
	char	aIFilterID[LEN_ID];
	char	aTemperature[LEN_FLOAT];
	char	aHumidity[LEN_FLOAT];
	char	aMultiplier[LEN_MULTIPLIER];
};

// �C���p���X��������f�[�^�t�@�C�����R�[�h
struct DbImpulseRec {
	long	nFolderID;
	long	nImpulseID;
	long	nSampling;
	long	nMeasTime;
	long	nMeasNum;
	long	nChannel;
	long	nBit;
	CString	sTime;
	CString sTitle;
	CString	sComment;
	long	nUseFilter;
	long	nIFilterID;
	double	fTemperature;
	double	fHumidity;
	long	nMultiplier;
	long	bImpulseData;
};

// Running ACF ����f�[�^�t�@�C���o�b�t�@
struct DbAcfBuf {
	char	aFolderID[LEN_ID];
	char	aAcfID[LEN_ID];
	char	aSampling[LEN_SAMPLING];
	char	aChannel[LEN_CHANNEL];
	char	aSampleNum[LEN_SAMPLENUM];
	char	aDataTime[LEN_FLOAT];
	char	aTime[LEN_TIME];
	char	aTitle[LEN_TITLE];
	char	aComment[LEN_COMMENT];
};

// Running ACF ����f�[�^�t�@�C�����R�[�h
struct DbAcfRec {
	long	nFolderID;
	long	nAcfID;
	long	nSampling;
	long	nChannel;
	long	nSampleNum;
	double	fDataTime;
	CString	sTime;
	CString sTitle;
	CString	sComment;
};

// ��������f�[�^�t�@�C���o�b�t�@
struct DbNmsBuf {
	char	aFolderID[LEN_ID];
	char	aNmsID[LEN_ID];
	char	aSampling[LEN_SAMPLING];
	char	aChannel[LEN_CHANNEL];
	char	aSampleNum[LEN_SAMPLENUM];
	char	aNumber[LEN_SHORT];
	char	aDataTime[LEN_FLOAT];
	char	aTime[LEN_TIME];
	char	aConditionSize[LEN_SIZE];
	char	aFactorSize[LEN_SIZE];
	char	aNmsFactor[LEN_NNMSFACT];
};

// ��������f�[�^�t�@�C�����R�[�h
struct DbNmsRec {
	long	nFolderID;
	long	nNmsID;
	long	nSampling;
	long	nChannel;
	long	nSampleNum;
	long	nNumber;
	double	fDataTime;
	CString	sTime;
	long	nConditionSize;
	long	nFactorSize;
	long	nNmsFactor;
};

// �������e���v���[�g�t�@�C���o�b�t�@
struct DbNsTmpBuf {
	char	aNsTmpID[LEN_ID];
	char	aName[LEN_NSTMPNAME];
};

// �������e���v���[�g�t�@�C�����R�[�h
struct DbNsTmpRec {
	long	nNsTmpID;
	CString	sName;
};

// �������e���v���[�g�f�[�^
struct NsTmpData {
	float fPhi0Standard;
	float fPhi0UpperLimit;
	float fPhi0LowerLimit;
	float fTaueStandard;
	float fTaueUpperLimit;
	float fTaueLowerLimit;
	float fTau1Standard;
	float fTau1UpperLimit;
	float fTau1LowerLimit;
	float fPhi1Standard;
	float fPhi1UpperLimit;
	float fPhi1LowerLimit;
	BOOL bPhi0UpperCheck;
	BOOL bPhi0LowerCheck;
	BOOL bTaueUpperCheck;
	BOOL bTaueLowerCheck;
	BOOL bTau1UpperCheck;
	BOOL bTau1LowerCheck;
	BOOL bPhi1UpperCheck;
	BOOL bPhi1LowerCheck;
	int nSum;
	float fPhi0Sum2;
	float fTaueSum2;
	float fTau1Sum2;
	float fPhi1Sum2;

	// �ȉ��ǉ���
	float fIACCStandard;
	float fIACCUpperLimit;
	float fIACCLowerLimit;
	float fTIACCStandard;
	float fTIACCUpperLimit;
	float fTIACCLowerLimit;
	float fWIACCStandard;
	float fWIACCUpperLimit;
	float fWIACCLowerLimit;
	BOOL bIACCUpperCheck;
	BOOL bIACCLowerCheck;
	BOOL bTIACCUpperCheck;
	BOOL bTIACCLowerCheck;
	BOOL bWIACCUpperCheck;
	BOOL bWIACCLowerCheck;
	float fIACCSum2;
	float fTIACCSum2;
	float fWIACCSum2;
};

// ����������d�݌W���f�[�^
struct NsWeightData {
	float fPhi0;
	float fTaue;
	float fTau1;
	float fPhi1;

	// �ȉ��ǉ���
	float fIACC;
	float fTIACC;
	float fWIACC;
};

// �����p�����[�^�v�Z����
struct DbAcParamCond {
	long	nFreqBand;
	long	bAFilter;
	long	nSplRefData;
	double	fSplRefLevel;
	double	fDT1MinTime;
	double	fTsubEnd;
	long	bTsubAuto;
	double	fTsubNoise;
	double	fWIACCLevel;
	double	fPrefSPL;
	double	fPrefTauE;
	double	fGRefData;
	double	fTCustom1;
	double	fTCustom2;
	double	fCCustom;
};

// �����p�����[�^�v�Z���ʃf�[�^
struct DbAcParamResult {
	double	fDeltaT0L;
	double	fDeltaT0R;
	double	fDeltaT1L;
	double	fDeltaT1R;
	double	fSTI_L;
	double	fSTI_R;
	double	fSTIM_L;
	double	fSTIM_R;
	double	fSTIF_L;
	double	fSTIF_R;
	double	fRASTI_L;
	double	fRASTI_R;
};

// �����p�����[�^�ш�ʌv�Z���ʃf�[�^
struct DbAcParamData {
	long	nFreq;
	double	fSplL;
	double	fSplR;
	double	fAL;
	double	fAR;
	double	tSubL;
	double	tSubR;
	double	fIACC;
	double	tIACC;
	double	wIACC;
	double	fG_L;
	double	fG_R;
	double	fT20_L;
	double	fT20_R;
	double	fT30_L;
	double	fT30_R;
	double	fTCustom_L;
	double	fTCustom_R;
	double	fEDT_L;
	double	fEDT_R;
	double	fC50_L;
	double	fC50_R;
	double	fC80_L;
	double	fC80_R;
	double	fCCustom_L;
	double	fCCustom_R;
	double	fD50_L;
	double	fD50_R;
	double	fTs_L;
	double	fTs_R;
	double	fIACCE;
	double	fIACCL;
};

// �����p�����[�^�v�Z���ʃt�@�C���o�b�t�@
struct DbAcParamBuf {
	char	aImpulseID[LEN_ID];
	char	aConditionSize[LEN_SIZE];
	char	aDataSize[LEN_SIZE];
	char	aDataNum[LEN_NDATA];
};

// �����p�����[�^�v�Z���ʃt�@�C�����R�[�h
struct DbAcParamRec {
	long	nImpulseID;
	long	nConditionSize;
	long	nDataSize;
	long	nDataNum;
	DbAcParamCond dbAcParamCond;
	DbAcParamResult dbAcParamResult;
	DbAcParamData dbAcParamData[ACPARAM_NFREQ];
};

// Running ACF �v�Z���ʃt�@�C���o�b�t�@
struct DbAcfFactorBuf {
	char	aAcfID[LEN_ID];
	char	aConditionSize[LEN_SIZE];
	char	aFactorSize[LEN_SIZE];
	char	aAcfFactor[LEN_NACFFACT];
};

// Running ACF �v�Z���ʃt�@�C�����R�[�h
struct DbAcfFactorRec {
	long	nAcfID;
	long	nConditionSize;
	long	nFactorSize;
	long	nAcfFactor;
};

// Running ACF �v�Z�����f�[�^�i���ʕ��j
struct AcfCondition {
	double fIntegrationTime;
	double fRunningStep;
	int nFreqWeighting;
	double fMaxDelayTime;
	long nPhi0RefData;
	double fPhi0RefLevel;
	double fTaueInterval;
	double fTaueEndLevel;
	double fTaueEndTime;
	short bTaueOrigin;
	short nTau1Peak;
};

// Running IACF �v�Z�����f�[�^�i���ʕ��j
struct IAcfCondition {
	double fIACCWLevel;
};

// Running ACF �v�Z�����f�[�^
struct AcfConditionData {
	long	nStartPos;
	long	nDataNum;
	AcfCondition AcfCondition;
	IAcfCondition IAcfCondition;
};

// Running ACF �v�Z���ʃf�[�^
struct AcfFactor {
	float	fPhi0;
	float	fTauE;
	float	fTau1;
	float	fPhi1;
	float	fTauE0;
};

// Running IACF �v�Z���ʃf�[�^
struct IAcfFactor {
	float	fSPL;
	float	fIACC;
	float	fTauIACC;
	float	fWIACC;
	float	fWIACC1;
};

// Running IACF �}�[�J�[�o�b�t�@
struct DbAcfMarkerBuf {
	char	aMarkerID[LEN_ID];
	char	aAcfID[LEN_ID];
	char	aTime[LEN_FLOAT];
	char	aTitle[LEN_TITLE];
	char	aComment[LEN_COMMENT];
};

// Running IACF �}�[�J�[���R�[�h
struct DbAcfMarkerRec {
	long	nMarkerID;
	long	nAcfID;
	double	fTime;
	CString	sTitle;
	CString	sComment;
};

// �������f�[�^
struct NoiseSrcData {
	float	fdPhi0;
	float	fdTauE;
	float	fdTau1;
	float	fdPhi1;
	char	aName[LEN_NSTMPNAME];
	float	fdIACC;
	float	fdTIACC;
	float	fdWIACC;
};

// ACF,IACF�v�Z���ʃf�[�^
struct AllFactorData {
	AcfFactor	Acf;
	IAcfFactor	IAcf;
};
typedef struct AllFactorData AcfFactorData;
typedef struct AllFactorData NmsFactorData;

// ��n, ��n �v�Z���ʃf�[�^
struct TaunPhinData {
	float	fTaun[10];
	float	fPhin[10];
};

// �}�C�N���g�������f�[�^
struct MicFreq {
	double	freq;
	double	level;
};

// �����v���}�C�N�r���f�[�^
struct NmsMicCal {
	double fInputSens;
	long nFreqData;
	MicFreq freq[MICCAL_NFREQ];
};

// �����v�Z�����f�[�^
struct NmsConditionData {
	long	nStartPos;
	long	nDataNum;
	AcfCondition AcfCondition;
	IAcfCondition IAcfCondition;
	NmsMicCal NmsMicCal;
};

// �����v���������
struct IdentCondition {
	int nIdentMode;
	int nBeforeStep;
	int nAfterStep;
};

// �t�t�B���^�t�@�C���o�b�t�@
struct DbIFilterBuf {
	char	aIFilterID[LEN_ID];
	char	aTitle[LEN_TITLE];
	char	aComment[LEN_COMMENT];
	char	aSampling[LEN_SAMPLING];
	char	aStartPos[LEN_FLOAT];
	char	aEndPos[LEN_FLOAT];
	char	aMaxLevel[LEN_MAXLEVEL];
	char	aPhaseAdj[LEN_BOOL];
};

// �t�t�B���^�t�@�C�����R�[�h
struct DbIFilterRec {
	long	nIFilterID;
	CString	sTitle;
	CString	sComment;
	long	nSampling;
	double	fStartPos;
	double	fEndPos;
	long	nMaxLevel;
	long	nPhaseAdj;
};

// �}�C�N�r���t�@�C���o�b�t�@
struct DbMicCalBuf {
	char	aMicCalID[LEN_ID];
	char	aMicInfo[LEN_MICINFO];
	char	aMicAmpInfo[LEN_MICAMPINFO];
	char	aComment[LEN_COMMENT];
	char	aInputSens[LEN_FLOAT];
	char	aInputSel[LEN_SHORT];
	char	aInputVol[LEN_SHORT];
	char	aFreqData[LEN_SHORT];
	struct	{
		char	aFreq[LEN_FLOAT];
		char	aFevel[LEN_FLOAT];
	} aFreq[MICCAL_NFREQ];
};

// �}�C�N�r���t�@�C�����R�[�h
struct DbMicCalRec {
	long	nMicCalID;
	CString	sMicInfo;
	CString	sMicAmpInfo;
	CString	sComment;
	double fInputSens;
	long nInputSel;
	long nInputVol;
	long nFreqData;
	MicFreq aFreq[MICCAL_NFREQ];
};

// �v���Z�b�g�t�@�C���o�b�t�@
struct DbPresetBuf {
	char	aPresetID[LEN_ID];
	char	aTitle[LEN_TITLE];
	char	aOpenWindow[LEN_SHORT];
};

// �v���Z�b�g�t�@�C�����R�[�h
struct DbPresetRec {
	long	nPresetID;
	CString sTitle;
	long	nOpenWindow;
};

// �g�`�f�[�^�t�@�C���o�b�t�@
struct DbWaveFormBuf {
	char	aWaveFormID[LEN_ID];
	char	aSmoothFlag[LEN_BOOL];
	char	aDataNum[LEN_WAVEFORMNUM];
};

// �g�`�f�[�^�t�@�C�����R�[�h
struct DbWaveFormRec {
	long	nWaveFormID;
	long	nSmoothFlag;
	long	nDataNum;
};

// �g�`�f�[�^
struct WaveFormData {
	float	x;
	float	y;
};

#pragma pack()

extern char g_szDefaultDir[];
extern class CMyPcserve g_oPcserve;

// PCSERVE �N���X
class CMyPcserve : public CPcserve
{
public:
	void Initialize();
};

// �t�H���_�t�@�C���N���X
class CDbFolder : public CPSDB
{
public:
	BOOL Open(LPCTSTR dir = g_szDefaultDir);
	BOOL GetNewID(long *pID);
	BOOL ReadRecID(long id, DbFolderRec *pDbRec);
	BOOL ReadRecNext(DbFolderRec *pDbRec);
	BOOL StoreRec(DbFolderRec *pDbRec);
	BOOL UpdateRec(DbFolderRec *pDbRec);
};

// �C���p���X��������f�[�^�t�@�C���N���X
class CDbImpulse : public CPSDB
{
public:
	BOOL Open(LPCTSTR dir = g_szDefaultDir);
	BOOL GetNewID(long *pImpulseID);
	BOOL ReadRecID(long id, DbImpulseRec *pDbRec);
	BOOL ReadRecNext(DbImpulseRec *pDbRec);
	BOOL StoreRec(DbImpulseRec *pDbRec);
	BOOL UpdateRec(DbImpulseRec *pDbRec);

	int m_nIdxFolderID;
	int m_nIdxImpulseID;
};

// Runnning ACF ����f�[�^�t�@�C���N���X
class CDbAcf : public CPSDB
{
public:
	BOOL Open(LPCTSTR dir = g_szDefaultDir);
	BOOL GetNewID(long *pAcfID);
	BOOL ReadRecID(long id, DbAcfRec *pDbRec);
	BOOL ReadRecNext(DbAcfRec *pDbRec);
	BOOL StoreRec(DbAcfRec *pDbRec);
	BOOL UpdateRec(DbAcfRec *pDbRec);

	int m_nIdxFolderID;
	int m_nIdxAcfID;
};

// ��������f�[�^�t�@�C���N���X
class CDbNms : public CPSDB
{
public:
	BOOL Open(LPCTSTR dir = g_szDefaultDir);
	BOOL GetNewID(long *pNmsID);
	BOOL ReadRecID(long id, DbNmsRec *pDbRec, NmsConditionData *pNmsConditionData, NmsFactorData **pNmsFactorData, NoiseSrcData *pNoiseSrcData);
	BOOL ReadRecNext(DbNmsRec *pDbRec);
	BOOL StoreRec(DbNmsRec *pDbRec);
	BOOL UpdateRec(DbNmsRec *pDbRec);

	int m_nIdxFolderID;
	int m_nIdxNmsID;
};

// �������e���v���[�g�t�@�C���N���X
class CDbNsTmp : public CPSDB
{
public:
	BOOL Open(LPCTSTR dir = g_szDefaultDir);
	BOOL GetNewID(long *pNsTmpID);
	BOOL ReadRecID(long id, DbNsTmpRec *pDbRec, NsTmpData *pNsTmpData);
	BOOL ReadRecNext(DbNsTmpRec *pDbRec, NsTmpData *pNsTmpData);
	BOOL StoreRec(DbNsTmpRec *pDbRec, NsTmpData *pNsTmpData);
	BOOL UpdateRec(DbNsTmpRec *pDbRec, NsTmpData *pNsTmpData);
	BOOL DeleteRec(long id);

	int m_nIdxNsTmpID;
	int m_nIdxNsTmpName;
};

// �����p�����[�^�v�Z���ʃt�@�C���N���X
class CDbAcParam : public CPSDB
{
public:
	BOOL Open(LPCTSTR dir = g_szDefaultDir);
	BOOL ReadRecID(long id, DbAcParamRec *pDbRec);
	BOOL SearchID(long id, BOOL bDispErr = TRUE);
	BOOL StoreRec(DbAcParamRec *pDbRec);
	BOOL UpdateRec(DbAcParamRec *pDbRec);
};

// Running ACF �v�Z���ʃt�@�C���N���X
class CDbAcfFactor : public CPSDB
{
public:
	BOOL Open(LPCTSTR dir = g_szDefaultDir);
	BOOL ReadRecID(long id, DbAcfFactorRec *pDbRec,
			AcfConditionData *pAcfConditionData = NULL, AcfFactorData **pAcfFactorData = NULL, TaunPhinData **pTaunPhinData = NULL);
	BOOL SearchID(long id);
	BOOL StoreRec(DbAcfFactorRec *pDbRec);
	BOOL UpdateRec(DbAcfFactorRec *pDbRec);
};

// Runnning ACF �}�[�J�[�N���X
class CDbAcfMarker : public CPSDB
{
public:
	BOOL Open(LPCTSTR dir = g_szDefaultDir);
	BOOL GetNewID(long *pID);
	BOOL ReadRecID(long id, DbAcfMarkerRec *pDbRec);
	BOOL ReadRecNext(DbAcfMarkerRec *pDbRec);
	BOOL StoreRec(DbAcfMarkerRec *pDbRec);
	BOOL UpdateRec(DbAcfMarkerRec *pDbRec);
	BOOL DeleteRec(long id);
	BOOL ReadRecAcfID(long id, DbAcfMarkerRec **pDbRec, int &nDataNum);

	int m_nIdxMarkerID;
	int m_nIdxAcfID;
};

// �t�t�B���^�t�@�C���N���X
class CDbIFilter : public CPSDB
{
public:
	BOOL Open(LPCTSTR dir = g_szDefaultDir);
	BOOL GetNewID(long *pID);
	BOOL ReadRecID(long id, DbIFilterRec *pDbRec);
	BOOL ReadRecNext(DbIFilterRec *pDbRec);
	BOOL StoreRec(DbIFilterRec *pDbRec);
	BOOL UpdateRec(DbIFilterRec *pDbRec);
	BOOL DeleteID(long id);
};

// �}�C�N�r���t�@�C���N���X
class CDbMicCal : public CPSDB
{
public:
	BOOL Open(LPCTSTR dir = g_szDefaultDir);
	BOOL GetNewID(long *pID);
	BOOL ReadRecID(long id, DbMicCalRec *pDbRec);
	BOOL ReadRecNext(DbMicCalRec *pDbRec);
	BOOL StoreRec(DbMicCalRec *pDbRec);
	BOOL UpdateRec(DbMicCalRec *pDbRec);
	BOOL DeleteID(long id);
	BOOL SearchRecID(long id);
};

// �v���Z�b�g�t�@�C���N���X
class CDbPreset : public CPSDB
{
public:
	BOOL Open(LPCTSTR dir = g_szDefaultDir);
	BOOL GetNewID(long *pID);
	BOOL ReadRecID(long id, DbPresetRec *pDbRec);
	BOOL ReadRecNext(DbPresetRec *pDbRec);
	BOOL StoreRec(DbPresetRec *pDbRec);
	BOOL UpdateRec(DbPresetRec *pDbRec);
	BOOL DeleteID(long id);
};

// �g�`�f�[�^�t�@�C���N���X
class CDbWaveForm : public CPSDB
{
public:
	BOOL Open(LPCTSTR dir = g_szDefaultDir);
	BOOL ReadRecID(long id, DbWaveFormRec *pDbRec, WaveFormData *pWaveFormData);
	BOOL WriteRec(DbWaveFormRec *pDbRec, WaveFormData *pWaveFormData);
};

extern void DeleteFolder(long nFolderID);
extern void DeleteImpulse(long nImpulseID);
extern void DeleteAcf(long nAcfID);
extern void DeleteNms(long nNmsID);
extern CString FormatTime(const CString &src);
extern CString UnformatTime(const CString &src);
extern void ReadNsWeightData(NsWeightData *pNsWeightData);
extern void WriteNsWeightData(const NsWeightData *pNsWeightData);
extern void ImportIR(LPCTSTR pDir);
extern void ImportACF(LPCTSTR pDir);
