//*****************************************************************************************************
//  1. �t�@�C����
//		GraphData.cpp
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�o�C�i���f�[�^�O���t�p�̃f�[�^�N���X�̎���
//----------------------------------------------------------------------------------------------------
//  3. ���l
//		���N���X��CDataFile�̓f�W�^�����l�\���Ƌ��ʂ̋@�\��񋟂��A���̃N���X�ł̓O���t��p��
//		�@�\��񋟂���B
//----------------------------------------------------------------------------------------------------
//  4. ����
//		2007.08.09 S.Aizawa �V�K�쐬
//*****************************************************************************************************

#include "StdAfx.h"
#include "GraphData.h"
#include "Define.h"
#include "GlobalData.h"
#include "MMA_G.h"
#include <math.h>
#include <float.h>

//*****************************************************************************************************
//  1. �֐���
//		CGraphData::CGraphData
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�R���X�g���N�^
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
CGraphData::CGraphData()
{
	// �����o�ϐ���������
	m_fSelectBegin = 0;
	m_fSelectEnd = 0;
	m_pFilterDataX = NULL;
	m_pFilterDataY = NULL;
	m_pFilterDataZ = NULL;
	m_pFilterDataD = NULL;
	m_bDispX = TRUE;
	m_bDispY = TRUE;
	m_bDispZ = TRUE;
	m_bDispD = TRUE;
	m_pAverageX = NULL;
	m_pAverageY = NULL;
	m_pAverageZ = NULL;
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphData::~CGraphData
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�f�X�g���N�^
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
CGraphData::~CGraphData()
{
	FreeFilterBuf();

	if (m_pAverageX != NULL)
		delete [] m_pAverageX;

	if (m_pAverageY != NULL)
		delete [] m_pAverageY;

	if (m_pAverageZ != NULL)
		delete [] m_pAverageZ;
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphData::ReadDataFile
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�f�[�^�ǂݍ��ݏ���
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		int		nBeginTime		[I] �ǂݍ��݊J�n�����i�b���j
//		int		nEndTime		[I] �ǂݍ��ݏI�������i�b���j
//		int		nGraphKind		[I] �O���t�̎�ށi�����x or ���x�j
//		BOOL	bProgressBar	[I] �v���O���X�o�[�\���t���O
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		BOOL	TRUE�F����@FALSE�F�G���[�܂��͒��~
//*****************************************************************************************************
BOOL CGraphData::ReadDataFile(int nBeginTime, int nEndTime, int nGraphKind, BOOL bProgressBar)
{
	#define TEMP_RATE	40

	// �f�[�^�t�@�C���ǂݍ���
	switch (nGraphKind) {
	case GRAPH_ACCEL:
		// �����x�f�[�^�t�@�C���ǂݍ���
		if (!CDataFile::ReadDataFile(nBeginTime, nEndTime, TRUE, FALSE, bProgressBar))
			return FALSE;

		m_pCurrentDataX = m_pDataX;
		m_pCurrentDataY = m_pDataY;
		m_pCurrentDataZ = m_pDataZ;
		m_pCurrentDataD = NULL;
		break;
	case GRAPH_TEMP:
		// ���x�f�[�^�t�@�C���ǂݍ���
		if (!CDataFile::ReadDataFile(nBeginTime, nEndTime, FALSE, TRUE, bProgressBar))
			return FALSE;

		// �T���v�����O���[�g��1/40�ɂ���
		m_nDataSize /= TEMP_RATE;
		m_fSampleRate /= TEMP_RATE;
		for (int i = 0; i < m_nDataSize; i++) {
			int j = i * TEMP_RATE;
			m_pTempX[i] = m_pTempX[j];
			m_pTempY[i] = m_pTempY[j];
			m_pTempZ[i] = m_pTempZ[j];
			m_pTempDACM[i] = m_pTempDACM[j];
		}

		m_pCurrentDataX = m_pTempX;
		m_pCurrentDataY = m_pTempY;
		m_pCurrentDataZ = m_pTempZ;
		m_pCurrentDataD = m_pTempDACM;
		break;
	}

	// �f�[�^�̍ŏ��l�A�ő�l�A���ϒl���擾
	m_fMinData = DBL_MAX;
	m_fMaxData = -DBL_MAX;
	m_fAverageX = 0;
	m_fAverageY = 0;
	m_fAverageZ = 0;
	for (int i = 0; i < m_nDataSize; i++) {
		// �ŏ��l���擾
		if (m_pCurrentDataX[i] < m_fMinData)
			m_fMinData = m_pCurrentDataX[i];
		if (m_pCurrentDataY[i] < m_fMinData)
			m_fMinData = m_pCurrentDataY[i];
		if (m_pCurrentDataZ[i] < m_fMinData)
			m_fMinData = m_pCurrentDataZ[i];
		if (m_pCurrentDataD != NULL && m_pCurrentDataD[i] < m_fMinData)
			m_fMinData = m_pCurrentDataD[i];

		// �ő�l���擾
		if (m_pCurrentDataX[i] > m_fMaxData)
			m_fMaxData = m_pCurrentDataX[i];
		if (m_pCurrentDataY[i] > m_fMaxData)
			m_fMaxData = m_pCurrentDataY[i];
		if (m_pCurrentDataZ[i] > m_fMaxData)
			m_fMaxData = m_pCurrentDataZ[i];
		if (m_pCurrentDataD != NULL && m_pCurrentDataD[i] > m_fMaxData)
			m_fMaxData = m_pCurrentDataD[i];

		// ���v�l���擾
		m_fAverageX += m_pCurrentDataX[i];
		m_fAverageY += m_pCurrentDataY[i];
		m_fAverageZ += m_pCurrentDataZ[i];
	}

	// ���ϒl���擾
	if (m_nDataSize != 0) {
		m_fAverageX /= m_nDataSize;
		m_fAverageY /= m_nDataSize;
		m_fAverageZ /= m_nDataSize;
	}

	// ���ԃf�[�^�ݒ�
	m_stTimeRange.fMin = nBeginTime;
	m_stTimeRange.fSpan = m_nDataSize / m_fSampleRate;
	m_stTimeRange.fMax = m_stTimeRange.fMin + m_stTimeRange.fSpan;
	m_stTimeRange.fBegin = m_stTimeRange.fMin;

	// �����x�f�[�^�ݒ�
	double fLevelMin = floor(m_fMinData);
	double fLevelMax = ceil(m_fMaxData);
	if (fLevelMin == fLevelMax) {
		fLevelMin -= 1;
		fLevelMax += 1;
	}
	m_stLevelRangeX.fMin = fLevelMin;
	m_stLevelRangeY.fMin = fLevelMin;
	m_stLevelRangeZ.fMin = fLevelMin;
	m_stLevelRangeX.fMax = fLevelMax;
	m_stLevelRangeY.fMax = fLevelMax;
	m_stLevelRangeZ.fMax = fLevelMax;
	double fLevelSpan = fLevelMax - fLevelMin;
	m_stLevelRangeX.fSpan = fLevelSpan;
	m_stLevelRangeY.fSpan = fLevelSpan;
	m_stLevelRangeZ.fSpan = fLevelSpan;
	double fLevelBegin = fLevelMin;
	m_stLevelRangeX.fBegin = fLevelBegin;
	m_stLevelRangeY.fBegin = fLevelBegin;
	m_stLevelRangeZ.fBegin = fLevelBegin;

	return TRUE;
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphData::CalcZeroPoint
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���ԊԊu���̕��ϒl���擾
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphData::CalcZeroPoint()
{
	int nSumSize = m_nDataSize / (int)(m_fSampleRate * gZeroOffsetSpan) + 1;

	double *pSumX = new double[nSumSize];
	double *pSumY = new double[nSumSize];
	double *pSumZ = new double[nSumSize];
	memset(pSumX, 0, nSumSize * sizeof(double));
	memset(pSumY, 0, nSumSize * sizeof(double));
	memset(pSumZ, 0, nSumSize * sizeof(double));

	int *pCount = new int[nSumSize];
	memset(pCount, 0, nSumSize * sizeof(int));

	for (int i = 0; i < m_nDataSize; i++) {
		int j = i / (int)(m_fSampleRate * gZeroOffsetSpan);

		pSumX[j] += m_pDataX[i];
		pSumY[j] += m_pDataY[i];
		pSumZ[j] += m_pDataZ[i];

		pCount[j]++;
	}

	for (int i = 0; i < nSumSize; i++) {
		if (pCount[i] != 0) {
			pSumX[i] /= pCount[i];
			pSumY[i] /= pCount[i];
			pSumZ[i] /= pCount[i];
		}
	}

	m_pAverageX = new double[m_nDataSize];
	m_pAverageY = new double[m_nDataSize];
	m_pAverageZ = new double[m_nDataSize];

	for (int i = 0; i < m_nDataSize; i++) {
		int j = i / (int)(m_fSampleRate * gZeroOffsetSpan);

		m_pAverageX[i] = pSumX[j];
		m_pAverageY[i] = pSumY[j];
		m_pAverageZ[i] = pSumZ[j];
	}

	delete [] pSumX;
	delete [] pSumY;
	delete [] pSumZ;
	delete [] pCount;
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphData::AllocFilterBuf
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�t�B���^�����o�b�t�@�m��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		int		nGraphKind		[I] �O���t�̎�ށi�����x or ���x�j
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphData::AllocFilterBuf(int nGraphKind)
{
	FreeFilterBuf();

	m_pFilterDataX = new double[m_nDataSize];
	m_pFilterDataY = new double[m_nDataSize];
	m_pFilterDataZ = new double[m_nDataSize];

	if (nGraphKind == GRAPH_TEMP)
		m_pFilterDataD = new double[m_nDataSize];
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphData::FreeFilterBuf
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�t�B���^�����o�b�t�@���
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphData::FreeFilterBuf()
{
	if (m_pFilterDataX != NULL) {
		delete [] m_pFilterDataX;
		m_pFilterDataX = NULL;
	}

	if (m_pFilterDataY != NULL) {
		delete [] m_pFilterDataY;
		m_pFilterDataY = NULL;
	}

	if (m_pFilterDataZ != NULL) {
		delete [] m_pFilterDataZ;
		m_pFilterDataZ = NULL;
	}

	if (m_pFilterDataD != NULL) {
		delete [] m_pFilterDataD;
		m_pFilterDataD = NULL;
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphData::ZeroOffsetlevelRange
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		0�_�␳�l�O���t�c���͈͐ݒ�
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphData::ZeroOffsetlevelRange()
{
	// �f�[�^�̍ŏ��l�A�ő�l�A���ϒl���擾
	m_fMinData = DBL_MAX;
	m_fMaxData = -DBL_MAX;
	for (int i = 0; i < m_nDataSize; i++) {
		// �ŏ��l���擾
		if (m_pCurrentDataX[i] - m_fAverageX < m_fMinData)
			m_fMinData = m_pCurrentDataX[i] - m_fAverageX;
		if (m_pCurrentDataY[i] - m_fAverageY < m_fMinData)
			m_fMinData = m_pCurrentDataY[i] - m_fAverageY;
		if (m_pCurrentDataZ[i] - m_fAverageZ < m_fMinData)
			m_fMinData = m_pCurrentDataZ[i] - m_fAverageZ;

		// �ő�l���擾
		if (m_pCurrentDataX[i] - m_fAverageX > m_fMaxData)
			m_fMaxData = m_pCurrentDataX[i] - m_fAverageX;
		if (m_pCurrentDataY[i] - m_fAverageY > m_fMaxData)
			m_fMaxData = m_pCurrentDataY[i] - m_fAverageY;
		if (m_pCurrentDataZ[i] - m_fAverageZ > m_fMaxData)
			m_fMaxData = m_pCurrentDataZ[i] - m_fAverageZ;
	}

	// �����x�f�[�^�ݒ�
	double fMargin = (m_fMaxData - m_fMinData) * 0.2;
	double fLevelMin = m_fMinData - fMargin;
	m_stLevelRangeX.fMin = m_fAverageX + fLevelMin;
	m_stLevelRangeY.fMin = m_fAverageY + fLevelMin;
	m_stLevelRangeZ.fMin = m_fAverageZ + fLevelMin;
	double fLevelMax = m_fMaxData + fMargin;
	m_stLevelRangeX.fMax = m_fAverageX + fLevelMax;
	m_stLevelRangeY.fMax = m_fAverageY + fLevelMax;
	m_stLevelRangeZ.fMax = m_fAverageZ + fLevelMax;
	double fLevelSpan = fLevelMax - fLevelMin;
	m_stLevelRangeX.fSpan = fLevelSpan;
	m_stLevelRangeY.fSpan = fLevelSpan;
	m_stLevelRangeZ.fSpan = fLevelSpan;
	m_stLevelRangeX.fBegin = m_fAverageX + fLevelMin;
	m_stLevelRangeY.fBegin = m_fAverageY + fLevelMin;
	m_stLevelRangeZ.fBegin = m_fAverageZ + fLevelMin;
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphData::GetDataTypeText
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�f�[�^��ʂ�\��������Ԃ�
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		CString	������
//*****************************************************************************************************
CString CGraphData::GetDataTypeText()
{
	switch (m_nDataType) {
	case DATA_TYPE_MMA:
		return "MMA";
	case DATA_TYPE_MME:
		return "MME";
	case DATA_TYPE_ASU:
		return "ASU";
	default:
		return "???";
	}
}
