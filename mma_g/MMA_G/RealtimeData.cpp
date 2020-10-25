//*****************************************************************************************************
//  1. �t�@�C����
//		RealtimeData.cpp
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���A���^�C���\���N���X�̎���
//----------------------------------------------------------------------------------------------------
//  3. ���l
//----------------------------------------------------------------------------------------------------
//  4. ����
//		2008.12.01 S.Aizawa �V�K�쐬
//*****************************************************************************************************

#include "StdAfx.h"
#include "MMA_G.h"
#include "RealtimeData.h"
#include "GlobalData.h"
#include "Define.h"
#include "General.h"
#include "BinFile.h"
#include "DataFile.h"
#include "CalcFile.h"
#include "RsuTaaTbl.h"
#include <shlwapi.h>

// define��`
#define CYCLE_TIME	1000		// ���A���^�C���f�[�^�Ď�����
#define BIN_HEADER_SIZE	44		// �o�C�i���t�@�C���w�b�_�T�C�Y

// ���f�[�^��int�ɕϊ�����}�N��
#define	GetAd(x) ((*(BYTE *)(x) << 16) | *(BYTE *)((x) + 1) << 8 | *(BYTE *)((x) + 2))

// �����f�[�^
#define LOSS_DATA	-1

// ��bin�t�@�C���ړ���t�H���_
#define	OLD_BIN_FILE	"OLD_BIN_FILE"

// ���A���^�C���\���N���X�̃C���X�^���X
CRealtimeData g_RealtimeData;

// RSU_ID��TAA_ID�̑Ή��e�[�u��
static CRsuTaaTbl g_RsuTaaTbl;

//*****************************************************************************************************
//  1. �֐���
//		CRealtimeBinFile::CRealtimeBinFile
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�o�C�i���t�@�C���N���X�̃R���X�g���N�^
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
CRealtimeBinFile::CRealtimeBinFile()
{
	m_nBinDataSize = 0;
}

//*****************************************************************************************************
//  1. �֐���
//		CRealtimeBinData::CRealtimeBinData
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�o�C�i���f�[�^�N���X�̃R���X�g���N�^
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
CRealtimeBinData::CRealtimeBinData()
{
	m_pBinBuf = NULL;
}

//*****************************************************************************************************
//  1. �֐���
//		CRealtimeBinData::~CRealtimeBinData
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�o�C�i���f�[�^�N���X�̃f�X�g���N�^
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
CRealtimeBinData::~CRealtimeBinData()
{
	// �t�@�C���ǂݍ��݃o�b�t�@���
	FreeBuf();
}

//*****************************************************************************************************
//  1. �֐���
//		CRealtimeBinData::FreeBuf
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�o�C�i���f�[�^�ǂݍ��݃o�b�t�@�������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CRealtimeBinData::FreeBuf()
{
	// �t�@�C���ǂݍ��݃o�b�t�@���
	if (m_pBinBuf != NULL) {
		delete [] m_pBinBuf;
		m_pBinBuf = NULL;
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CRealtimeBinData::GetBinDataSize
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�o�C�i���f�[�^�T�C�Y���擾����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		int		�f�[�^�T�C�Y
//*****************************************************************************************************
int CRealtimeBinData::GetBinDataSize()
{
	int nDataSize = 0;

	if (m_aBinFile.GetSize() != 0) {
		int nLastIndex = (int)m_aBinFile.GetSize() - 1;
		nDataSize = m_aBinFile[nLastIndex].m_nBinDataOffset + m_aBinFile[nLastIndex].m_nBinDataSize;
	}

	return nDataSize;
}

//*****************************************************************************************************
//  1. �֐���
//		CRealtimeBinData::SeekBinFile
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�o�C�i���f�[�^�t�@�C�����w�肳�ꂽ�ʒu�ɃV�[�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		int		nSeekSize		[I] �V�[�N����ʒu
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CRealtimeBinData::SeekBinFile(int nSeekSize)
{
	// �V�[�N����ʒu�̃o�C�i���t�@�C�������߂�
	m_nBinFileIndex = 0;
	int nOffset;
	while (true) {
		nOffset = nSeekSize - m_aBinFile[m_nBinFileIndex].m_nBinDataOffset;
		if (nOffset >= m_aBinFile[m_nBinFileIndex].m_nBinDataSize)
			m_nBinFileIndex++;
		else
			break;
	}

	// �o�C�i���t�@�C���o�b�t�@�Ƀf�[�^��ǂݍ���
	ReadBinFile(nOffset);

	// ��s�x���ǂݏo���o�b�t�@�N���A
	memset(m_aPrevNextBuf, 0, sizeof(m_aPrevNextBuf));
	m_bPrevNext = FALSE;
}

//*****************************************************************************************************
//  1. �֐���
//		CRealtimeBinData::GetBinData
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�o�C�i���f�[�^�t�@�C������P�f�[�^���擾����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		int		�擾�����f�[�^
//*****************************************************************************************************
int CRealtimeBinData::GetBinData()
{
	// �o�b�t�@�̃f�[�^��ǂݏI������玟�̃o�C�i���t�@�C����ǂ�
	if (m_nReadPtr >= m_nBufSize) {
		if (m_nBinFileIndex >= m_aBinFile.GetSize() - 1)
			return 0;

		int nCurrentOffset = m_aBinFile[m_nBinFileIndex].m_nBinDataOffset + m_aBinFile[m_nBinFileIndex].m_nBinDataSize;
		ReadBinFile(nCurrentOffset - m_aBinFile[++m_nBinFileIndex].m_nBinDataOffset);
	}

	int nData;
	if (m_nReadPtr >= 0) {
		// �o�C�i���t�@�C���o�b�t�@����P�f�[�^����荞��
		BYTE *p = m_pBinBuf + m_nReadPtr * 3;
		nData = GetAd(p);
	} else {
		// �����t�@�C���f�[�^��Ԃ�
		nData = LOSS_DATA;
	}
	m_nReadPtr++;

	return nData;
}

// 2010/9/24 modified by y.GOTO for ver.4.02 ->
//*****************************************************************************************************
//  1. �֐���
//		CRealtimeBinData::GetBinDataUnsigned
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�o�C�i���f�[�^�t�@�C������P�f�[�^���擾����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		unsigned int		�擾�����f�[�^
//*****************************************************************************************************
unsigned int CRealtimeBinData::GetBinDataUnsigned()
{
	// �o�b�t�@�̃f�[�^��ǂݏI������玟�̃o�C�i���t�@�C����ǂ�
	if (m_nReadPtr >= m_nBufSize) {
		if (m_nBinFileIndex >= m_aBinFile.GetSize() - 1)
			return 0;

		int nCurrentOffset = m_aBinFile[m_nBinFileIndex].m_nBinDataOffset + m_aBinFile[m_nBinFileIndex].m_nBinDataSize;
		ReadBinFile(nCurrentOffset - m_aBinFile[++m_nBinFileIndex].m_nBinDataOffset);
	}

	unsigned int nData;
	if (m_nReadPtr >= 0) {
		// �o�C�i���t�@�C���o�b�t�@����P�f�[�^����荞��
		BYTE *p = m_pBinBuf + m_nReadPtr * 3;
		nData = GetAd(p);
	} else {
		// �����t�@�C���f�[�^��Ԃ�
		nData = LOSS_DATA;
	}
	m_nReadPtr++;

	return nData;
}
// <- 2010/9/24 modified by y.GOTO for ver.4.02

//*****************************************************************************************************
//  1. �֐���
//		CRealtimeBinData::GetAccelData
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�o�C�i���f�[�^�t�@�C����������x�f�[�^���擾����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		int		�擾�����f�[�^
//*****************************************************************************************************
int CRealtimeBinData::GetAccelData()
{
	// ����͐�s�x���ǂݏo���o�b�t�@�ɂQ�f�[�^��ǂݍ���
	if (!m_bPrevNext) {
		m_bPrevNext = TRUE;
		m_aPrevNextBuf[1] = GetBinData();
		m_aPrevNextBuf[0] = GetBinData();
	}

	// �o�C�i���t�@�C���o�b�t�@����P�f�[�^����荞��
	int nData = GetBinData();

	// ��s�x���ǂݏo���o�b�t�@�ɃZ�b�g����
	memmove(m_aPrevNextBuf + 1, m_aPrevNextBuf, sizeof(m_aPrevNextBuf) - sizeof(m_aPrevNextBuf[0]));
	m_aPrevNextBuf[0] = nData;

	return m_aPrevNextBuf[2];
}

//*****************************************************************************************************
//  1. �֐���
//		CRealtimeBinData::GetTempData
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�o�C�i���f�[�^�t�@�C�����牷�x�f�[�^���擾����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		int		nOffset			[I] �f�[�^�S�̂̃I�t�Z�b�g�ʒu
//		int		&tdacm			[O] �擾����tdacm�f�[�^ -> unsigned int�ɕύX(ver.4.02)
//		int		&tx				[O] �擾����tx�f�[�^
//		int		&ty				[O] �擾����ty�f�[�^
//		int		&tz				[O] �擾����tz�f�[�^
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
//void CRealtimeBinData::GetTempData(int nOffset, int &tdacm, int &tx, int &ty, int &tz)		// 2010/9/24 modified by y.GOTO for ver.4.02
void CRealtimeBinData::GetTempData(int nOffset, unsigned int &tdacm, int &tx, int &ty, int &tz)	// 2010/9/24 modified by y.GOTO for ver.4.02
{
	// 40�f�[�^���Ƀf�[�^��ǂ�
	if (nOffset % 40 == 0) {
		m_aTempData[0] = GetBinDataUnsigned();
		m_aTempData[1] = GetBinData();
		m_aTempData[2] = GetBinData();
		m_aTempData[3] = GetBinData();
	}

	// �o�C�i���t�@�C���o�b�t�@���牷�x�f�[�^����荞��
	tdacm = m_aTempData[0];
	tx = m_aTempData[1];
	ty = m_aTempData[2];
	tz = m_aTempData[3];
}

//*****************************************************************************************************
//  1. �֐���
//		CRealtimeBinData::ReadBinFile
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�o�C�i���f�[�^�t�@�C���o�b�t�@�Ƀf�[�^��ǂݍ���
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		int		nOffset			[I] �f�[�^�̃I�t�Z�b�g�ʒu
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CRealtimeBinData::ReadBinFile(int nOffset)
{
	// �o�C�i���t�@�C���o�b�t�@�����
	FreeBuf();

	// �ǂݏo���|�C���^��ݒ�
	m_nReadPtr = min(nOffset, 0);
	int nReadOffset = max(nOffset, 0);

	// �o�C�i���t�@�C���o�b�t�@���m��
	m_nBufSize = m_aBinFile[m_nBinFileIndex].m_nBinDataSize - nReadOffset;
	m_pBinBuf = new BYTE[m_nBufSize * 3];

	// �o�C�i���t�@�C�����I�[�v��
	CFile oBinFile;
	int nRetry = 0;
	while (true) {
		if (!oBinFile.Open(m_aBinFile[m_nBinFileIndex].m_sPathName, CFile::modeRead | CFile::shareDenyNone)) {
			if (++nRetry == 120) {
				CGeneral::Alert("���A���^�C���p�o�C�i���t�@�C�����J���܂���B(%s)", m_aBinFile[m_nBinFileIndex].m_sPathName);
				throw new CUserException;
			}
			::Sleep(500);
		} else
			break;
	}

	// �o�C�i���t�@�C������o�b�t�@�Ƀf�[�^��ǂݍ��ށi�P�f�[�^�͂R�o�C�g�j
	oBinFile.Seek(BIN_HEADER_SIZE + nReadOffset * 3, CFile::begin);
	oBinFile.Read(m_pBinBuf, m_nBufSize * 3);
}

//*****************************************************************************************************
//  1. �֐���
//		CRealtimeGraph::CRealtimeGraph
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���A���^�C���O���t�N���X�̃R���X�g���N�^
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
CRealtimeGraph::CRealtimeGraph()
{
	m_pGraphAccl = NULL;
	m_pGraphTemp = NULL;
	m_nIdbSize = 0;
	m_bUpdateGraph = FALSE;
	m_bExistLossFile = FALSE;
	m_nStartOffset = -1;
}

//*****************************************************************************************************
//  1. �֐���
//		CRealtimeGraph::~CRealtimeGraph
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���A���^�C���O���t�N���X�̃f�X�g���N�^
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
CRealtimeGraph::~CRealtimeGraph()
{
	if (m_pGraphAccl != NULL)
		m_pGraphAccl->DestroyWindow();

	if (m_pGraphTemp != NULL)
		m_pGraphTemp->DestroyWindow();
}

//*****************************************************************************************************
//  1. �֐���
//		CRealtimeGraph::GetIdbName
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		idb�t�@�C���̃t�@�C�������擾����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		CString		idb�t�@�C���̃t�@�C����
//*****************************************************************************************************
CString CRealtimeGraph::GetIdbName()
{
	CString sIdbName;
	sIdbName.Format("%04d%04d%02d%02d%d", m_nRsuId, m_dtStartTime.m_YY, m_dtStartTime.m_MM, m_dtStartTime.m_DD, ((m_dtStartTime.m_hh * 60 + m_dtStartTime.m_mm) * 60 + m_dtStartTime.m_ss) * 1000 + m_dtStartTime.m_ttt);
	return sIdbName;
}

//*****************************************************************************************************
//  1. �֐���
//		CRealtimeGraph::GetIdbPathName
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		idb�t�@�C���̃p�X�����擾����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		CString		idb�t�@�C���̃p�X��
//*****************************************************************************************************
CString CRealtimeGraph::GetIdbPathName()
{
	CString sPathName;
	sPathName.Format("%s\\%s.idb", gRealtimeTempPath, GetIdbName());
	return sPathName;
}

//*****************************************************************************************************
//  1. �֐���
//		CRealtimeGraph::GetBinDataSize
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���x�AXYZ���f�[�^�̍ŏ��T�C�Y���擾����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		int		�ŏ��T�C�Y
//*****************************************************************************************************
int CRealtimeGraph::GetBinDataSize()
{
	// ���x�AXYZ���̃f�[�^����ŏ��T�C�Y���擾
	int nDataSize = m_aBinData[0].GetBinDataSize() * 10;
	for (int i = 1; i < 4; i++) {
		if (m_aBinData[i].GetBinDataSize() < nDataSize)
			nDataSize = m_aBinData[i].GetBinDataSize();
	}

	return nDataSize / 40 * 40;		// ���x�f�[�^�̋�؂�T�C�Y�Ƃ���
}

//*****************************************************************************************************
//  1. �֐���
//		CRealtimeGraph::MakeIdbFile
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		idb�t�@�C���y�эH�w�l�ϊ��ς݃t�@�C�����쐬����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CRealtimeGraph::MakeIdbFile()
{
	if (GetBinDataSize() > 0) {
		if (m_nStartOffset == -1) {
			m_nStartOffset = m_aBinData[0].m_aBinFile[0].m_nBinDataOffset * 10;
			for (int i = 1; i < 4; i++) {
				if (m_aBinData[i].m_aBinFile[0].m_nBinDataOffset > m_nStartOffset)
					m_nStartOffset = m_aBinData[i].m_aBinFile[0].m_nBinDataOffset;
			}
			m_nStartOffset = (m_nStartOffset + 40 - 1) / 40 * 40;
			m_dtStartTime += (long)(m_nStartOffset / m_aBinData[1].m_aBinFile[0].m_fSampleRate) * 1000;
		}

		// idb�t�@�C�������擾����
		CString sIdbPathName = GetIdbPathName();

		// idb�t�@�C�������݂��邩�`�F�b�N����
		if (::PathFileExists(sIdbPathName)) {
			// ���݂����ꍇ�͏���ǂݍ���
			CDataFile oDataFile;
			if (!oDataFile.ReadInfoFile(sIdbPathName))
				throw new CUserException;
			m_nIdbSize = m_nStartOffset + oDataFile.m_nTotalDataSize;
		} else
			m_nIdbSize = m_nStartOffset;

		// �o�C�i���f�[�^�T�C�Y���H�w�l�ϊ��ς݃f�[�^���傫����Βǉ����ꂽ�f�[�^���H�w�l�ϊ�����
		if (GetBinDataSize() > m_nIdbSize) {
			CIdbFile oIdbFile;

			if (m_nIdbSize != 0) {
				// idb�t�@�C�������݂���ꍇ�͂����ǂݍ���
				oIdbFile.Read2(GetIdbPathName());
			}

			// idb�t�@�C����������擾����
			CBinFile oBinFile;
			if (oBinFile.OpenBin(m_aBinData[1].m_aBinFile[0].m_sPathName, TRUE)) {
				oBinFile.ReadHeader();
				oBinFile.CloseBin();

				double fSampleRate = 10e6 / (512 * oBinFile.m_SampleRate);
				oIdbFile.SetSampleRate(fSampleRate);
				oIdbFile.SetPath(gRealtimeTempPath);
				oIdbFile.SetGain((int)(pow(2.0, (double)(oBinFile.m_CalGain & 0x0f))));
				oIdbFile.SetCutoff(oBinFile.m_CutOff);
				oIdbFile.SetStartTime(m_dtStartTime.m_YY, m_dtStartTime.m_MM, m_dtStartTime.m_DD, ((m_dtStartTime.m_hh * 60 + m_dtStartTime.m_mm) * 60 + m_dtStartTime.m_ss) * 1000 + m_dtStartTime.m_ttt);
				oIdbFile.SetRsuId(m_nRsuId);
				oIdbFile.SetNetworkid(0);
				oIdbFile.SetSensorId(m_nTaaId);
				oIdbFile.SetSensorIdx(-1);

				m_nCntPerHour = (int)(fSampleRate * 3600);
				m_nCutOff = oBinFile.m_CutOff;
				m_nOffset = oBinFile.m_Offset;
			}

			// �H�w�l�ϊ����s��
			ConvertFile(oIdbFile);

			// idb�t�@�C���ɏ�������
			oIdbFile.Write(GetIdbName());

			// �O���t�X�V�t���O���Z�b�g����
			m_bUpdateGraph = TRUE;
		}
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CRealtimeGraph::ConvertFile
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�H�w�l�ϊ��ς݃t�@�C�����쐬����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CIdbFile	&oIdbFile			[I] idb�t�@�C���N���X
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CRealtimeGraph::ConvertFile(CIdbFile &oIdbFile)
{
	int i;
	ST_AD ad;
	ST_CALC_DATA calc;
	CFile oFileCalc;
	INFO_FILE_HEADER sHeader;
	CString sPathName;
	CDataDateTime oTime;

	// �v�Z�p�����[�^�ݒ�
	CCalcFile oCalcFile;
	if (!oCalcFile.SetArg(m_nRsuId, m_nTaaId, m_nCntPerHour, m_nOffset, m_nCutOff)) {
		CGeneral::Alert("�H�w�l�ϊ��p�萔�e�[�u���ɕs��������܂��B");
		throw new CUserException;
	}

	// �H�w�l�ϊ��ς݃t�@�C�����I�[�v��
	if (oIdbFile.m_File.GetSize() != 0) {
		sPathName.Format("%s\\%s", gRealtimeTempPath, oIdbFile.m_File[oIdbFile.m_File.GetSize() - 1]);
		if (!oFileCalc.Open(sPathName, CFile::modeReadWrite | CFile::shareDenyNone)) {
			CGeneral::Alert("���A���^�C���p�H�w�l�ϊ��ς݃t�@�C�����J���܂���B(%s)", sPathName);
			throw new CUserException;
		}
		oFileCalc.Read(&sHeader, sizeof(sHeader));
		oFileCalc.Seek(sHeader.nDataSize * sizeof(double) * 7, CFile::current);
	} else {
		memset(&sHeader, 0, sizeof(sHeader));
	}

	// BIN�t�@�C�����V�[�N
	m_aBinData[0].SeekBinFile(m_nIdbSize / 40 * 4);
	for (i = 1; i < 4; i++)
		m_aBinData[i].SeekBinFile(m_nIdbSize);

	// �H�w�l�ϊ�����
	int nDataSize = GetBinDataSize();
	while (m_nIdbSize < nDataSize) {
		// �o�C�i���t�@�C�����牷�x�f�[�^�A�����x�f�[�^�����o��
		m_aBinData[0].GetTempData(m_nIdbSize, ad.tdacm, ad.tx, ad.ty, ad.tz);
		ad.x = m_aBinData[1].GetAccelData();
		ad.y = m_aBinData[2].GetAccelData();
		ad.z = m_aBinData[3].GetAccelData();

		// �G���[�l�̏��u
		if (ad.x == 0x800000)
			ad.x = oCalcFile.AdjustForNegativeFullscale(ad.x, m_aBinData[1].m_aPrevNextBuf[4], m_aBinData[1].m_aPrevNextBuf[3], m_aBinData[1].m_aPrevNextBuf[1], m_aBinData[1].m_aPrevNextBuf[0]);
		if (ad.y == 0x800000)
			ad.y = oCalcFile.AdjustForNegativeFullscale(ad.y, m_aBinData[2].m_aPrevNextBuf[4], m_aBinData[2].m_aPrevNextBuf[3], m_aBinData[2].m_aPrevNextBuf[1], m_aBinData[2].m_aPrevNextBuf[0]);
		if (ad.z == 0x800000)
			ad.z = oCalcFile.AdjustForNegativeFullscale(ad.z, m_aBinData[3].m_aPrevNextBuf[4], m_aBinData[3].m_aPrevNextBuf[3], m_aBinData[3].m_aPrevNextBuf[1], m_aBinData[3].m_aPrevNextBuf[0]);

		// �H�w�l�ϊ��̌v�Z���s��
		oCalcFile.CalcData(&ad, &calc);

		// �����t�@�C���̍H�w�ϊ��l��0�ɂ���
		if (ad.x == LOSS_DATA || ad.tx == LOSS_DATA || ad.tdacm == LOSS_DATA) {
			calc.x = 0;
			calc.tx = 0;
			calc.dacm = 0;
			m_bExistLossFile = TRUE;
		}
		if (ad.y == LOSS_DATA || ad.ty == LOSS_DATA || ad.tdacm == LOSS_DATA) {
			calc.y = 0;
			calc.ty = 0;
			calc.dacm = 0;
			m_bExistLossFile = TRUE;
		}
		if (ad.z == LOSS_DATA || ad.tz == LOSS_DATA || ad.tdacm == LOSS_DATA) {
			calc.z = 0;
			calc.tz = 0;
			calc.dacm = 0;
			m_bExistLossFile = TRUE;
		}

		// �H�w�l�ϊ��ς݃t�@�C���͂P���Ԗ��ɕ�������
		if (sHeader.nDataSize == sHeader.nDataSizeHour) {
			// �P���ԕ����������񂾂�w�b�_�������ăN���[�Y����
			if (oFileCalc.m_hFile != CFile::hFileNull) {
				oFileCalc.Seek(0, CFile::begin);
				oFileCalc.Write(&sHeader, sizeof(sHeader));
				oFileCalc.Close();
			}

			// �w�b�_�̏�����
			sHeader.nSeqNo = (int)oIdbFile.m_File.GetSize() + 1;
			sHeader.nDataSize = 0;
			sHeader.nDataSizeHour = m_nCntPerHour;

			// idb�t�@�C���Ɏ��̍H�w�l�ϊ��ς݃t�@�C����o�^����
			CString sFileName;
			oTime = m_dtStartTime + 3600000 * (int)oIdbFile.m_File.GetSize();
			sFileName.Format("%04d_%02d_%02d_%02d_%02d_%02d.%03d.%04x"
				, oTime.m_YY
				, oTime.m_MM
				, oTime.m_DD
				, oTime.m_hh
				, oTime.m_mm
				, oTime.m_ss
				, oTime.m_ttt
				, m_nTaaId);
			oIdbFile.m_File.Add(sFileName);

			// �H�w�l�ϊ��ς݃t�@�C����V�K�쐬����
			sPathName.Format("%s\\%s", gRealtimeTempPath, sFileName);
			if (!oFileCalc.Open(sPathName, CFile::modeCreate | CFile::modeWrite | CFile::shareDenyNone)) {
				CGeneral::Alert("���A���^�C���p�H�w�l�ϊ��ς݃t�@�C�����J���܂���B(%s)", sPathName);
				throw new CUserException;
			}
			oFileCalc.Write(&sHeader, sizeof(sHeader));
		}

		// �H�w�l�ϊ��ς݃t�@�C���Ƀf�[�^����������
		oFileCalc.Write(&calc, sizeof(calc));

		// �f�[�^�T�C�Y���X�V����
		sHeader.nDataSize++;
		m_nIdbSize++;
	}

	// �w�b�_����������
	oFileCalc.Seek(0, CFile::begin);
	oFileCalc.Write(&sHeader, sizeof(sHeader));

	// �o�C�i���t�@�C���ǂݍ��݃o�b�t�@���������
	for (i = 0; i < 4; i++)
		m_aBinData[i].FreeBuf();
}

//*****************************************************************************************************
//  1. �֐���
//		CRealtimeData::CRealtimeData
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���A���^�C���f�[�^�����N���X�̃R���X�g���N�^
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
CRealtimeData::CRealtimeData()
{
	m_bStartFlag = FALSE;
	m_bStopFlag = FALSE;
	m_bLoop = TRUE;

	// ���[�J�X���b�h�N��
	m_hThread = AfxBeginThread(RealtimeDataThreadEntry, this, THREAD_PRIORITY_BELOW_NORMAL)->m_hThread;
}

//*****************************************************************************************************
//  1. �֐���
//		CRealtimeData::~CRealtimeData
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���A���^�C���f�[�^�����N���X�̃f�X�g���N�^
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
CRealtimeData::~CRealtimeData()
{
	// ���[�J�X���b�h�̃��[�v���I��������
	m_bLoop = FALSE;

	// ���[�J�X���b�h�X���b�h�̏I����҂�
	m_oEvent.PulseEvent();
	::WaitForSingleObject(m_hThread, 1000);

	// �e���|�����t�H���_���̃t�@�C�����폜����
	DeleteTempFiles();
}

//*****************************************************************************************************
//  1. �֐���
//		CRealtimeData::Start
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���A���^�C���\�����J�n����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CRealtimeData::Start()
{
	// RSU_ID-TAA_ID�Ή��e�[�u����ǂݍ���
	if (!g_RsuTaaTbl.SetTbl()) {
		AfxMessageBox("RSU_ID-TAA_ID�Ή��e�[�u��������܂���B", MB_OK | MB_ICONEXCLAMATION);
		return;
	}

	// ������bin�t�@�C�����ړ�
	MoveOldBinData();

	// �����̃��A���^�C����ʂ����
	m_aGraph.RemoveAll();

	// ���[�J�X���b�h�Ń��A���^�C���������J�n����
	m_bStartFlag = TRUE;
	m_bStopFlag = FALSE;
	m_oEvent.PulseEvent();
}

//*****************************************************************************************************
//  1. �֐���
//		CRealtimeData::Stop
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���A���^�C���\�����~����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CRealtimeData::Stop()
{
	// ���[�J�X���b�h�̃��A���^�C���������~����
	m_bStartFlag = FALSE;
	m_bStopFlag = TRUE;
}

//*****************************************************************************************************
//  1. �֐���
//		CRealtimeData::RealtimeDataThreadEntry
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���A���^�C�������X���b�h�̓����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		LPVOID			pParam			[I] CDataFile�N���X�ւ̃|�C���^
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		UINT	0
//*****************************************************************************************************
UINT CRealtimeData::RealtimeDataThreadEntry(LPVOID pParam)
{
	((CRealtimeData *)pParam)->RealtimeDataThread();

	return 0;
}

//*****************************************************************************************************
//  1. �֐���
//		CRealtimeData::RealtimeDataThread
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���A���^�C�������X���b�h�̖{��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CRealtimeData::RealtimeDataThread()
{
	while (m_bLoop) {
		// �����҂�
		m_oEvent.Lock(CYCLE_TIME);

		if (m_bStartFlag) {
			try {
				// �o�C�i���t�@�C���̏���ǂݍ���
				GetBinFileInfo();

				// idb�t�@�C���y�эH�w�l�ϊ��ς݃t�@�C�����쐬����
				for (int i = 0; i < m_aGraph.GetSize(); i++)
					m_aGraph[i].MakeIdbFile();

				// �O���t�E�B���h�E�̓��C���X���b�h�ŊJ��
				CWnd *pMainWnd = theApp.m_pMainWnd;
				if (pMainWnd->GetSafeHwnd() != NULL)
					pMainWnd->SendMessage(WM_USER_REALTIME_GRAPH);
			} catch (CUserException *e) {
				// �G���[���������ꍇ�̓��A���^�C���\�����~����i������ŉ��x���\�������̂�����邽�߁j
				m_bStartFlag = FALSE;
				e->Delete();
			}
		}

		if (m_bStopFlag) {
			DeleteTempFiles();
			m_bStopFlag = FALSE;
		}
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CRealtimeData::GetBinFileInfo
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�o�C�i���t�@�C���̏����擾����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CRealtimeData::GetBinFileInfo()
{
	CFileFind FileFind;

	// ���A���^�C���f�[�^�t�H���_���̃t�@�C��������
	CString sSearchFile;
	sSearchFile.Format("%s\\*.bin", gRealtimeDataPath);
	if (!FileFind.FindFile(sSearchFile)) 
		return; 

	// �������ꂽ�o�C�i���t�@�C������������
	BOOL bContinue = TRUE;
	while (bContinue) { 
		bContinue = FileFind.FindNextFile();

		// �������ꂽ�o�C�i���t�@�C����z��ɒǉ�����
		AddBinFile(FileFind.GetFileName(), FileFind.GetFilePath());
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CRealtimeData::AddBinFile
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�o�C�i���t�@�C���̏���z��ɐݒ肷��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CString		&sBinFileName			[I] �o�C�i���t�@�C���̃t�@�C����
//		CString		&sBinPathName			[I] �o�C�i���t�@�C���̃p�X��
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CRealtimeData::AddBinFile(const CString &sBinFileName, const CString &sBinPathName)
{
	int i, j;

	// BIN�t�@�C�����𕪉�
	CStringArray saFileNameParts;
	CGeneral::StrSplit(sBinFileName, "-.", saFileNameParts);
	if (saFileNameParts.GetSize() < 5)
		return;

	// BIN�t�@�C�������烆�j�b�g�ԍ��Ǝ�ʔԍ����擾
	int nRsuId = atoi(saFileNameParts[0]);
	int nKind = atoi(saFileNameParts[1]);
	CString sStartDateTime = saFileNameParts[2] + saFileNameParts[3];
	int nBinDataOffset = atoi(saFileNameParts[4]);
	if (nKind < 0 || nKind > 3)
		return;

	// BIN�t�@�C���̃w�b�_���擾
	CBinFile oBinFile;
	if (!oBinFile.OpenBin(sBinPathName, TRUE))
		return;
	oBinFile.ReadHeader();

	// BIN�t�@�C������z��ɒǉ�
	for (i = 0; i < m_aGraph.GetSize(); i++) {
		if (m_aGraph[i].m_nRsuId == nRsuId && m_aGraph[i].m_sStartDateTime == sStartDateTime)
			break;
	}
	if (i == m_aGraph.GetSize()) {
		// RSU ID����TAA ID�����߂�
		int nTaaId = g_RsuTaaTbl.GetTaaId(nRsuId);
		if (nTaaId == -1) {
			CGeneral::Alert("���A���^�C���f�[�^��RSU_ID��RSU_ID-TAA_ID�Ή��e�[�u���ɑ��݂��܂���B\n(RSU_ID=%d)", nRsuId);
			throw new CUserException;
		}

		// ���A���^�C���O���t�f�[�^���쐬����
		CRealtimeGraph oRealtimeGraph;
		oRealtimeGraph.m_nRsuId = nRsuId;
		oRealtimeGraph.m_nTaaId = g_RsuTaaTbl.GetTaaId(nRsuId);
		oRealtimeGraph.m_sStartDateTime = sStartDateTime;

		// �v�������𕪉�����
		CStringArray saFileNameParts;
		CGeneral::StrSplit(sStartDateTime, "_", saFileNameParts);
		oRealtimeGraph.m_dtStartTime.SetTime(atoi(saFileNameParts[0]), atoi(saFileNameParts[1]), atoi(saFileNameParts[2]), atoi(saFileNameParts[3]), atoi(saFileNameParts[4]), atoi(saFileNameParts[5]), atoi(saFileNameParts[6]));

		// ���A���^�C���O���t�z��ɒǉ�����
		m_aGraph.Add(oRealtimeGraph);
	}

	// �o�C�i���t�@�C�������t�@�C�������ɔz��ɃZ�b�g����
	CRealtimeGraph &oRealtimeGraph = m_aGraph[i];
	CRealtimeBinData &oRealtimeBinData = oRealtimeGraph.m_aBinData[nKind];
	for (j = 0; j < oRealtimeBinData.m_aBinFile.GetSize(); j++) {
		if (oRealtimeBinData.m_aBinFile[j].m_nBinDataOffset >= nBinDataOffset)
			break;
	}
	if (j < oRealtimeBinData.m_aBinFile.GetSize() && oRealtimeBinData.m_aBinFile[j].m_nBinDataOffset == nBinDataOffset) {
		oRealtimeBinData.m_aBinFile[j].m_nBinDataSize = oBinFile.m_Size;
	} else {
		CRealtimeBinFile oRealtimeBinFile;
		oRealtimeBinFile.m_sPathName = sBinPathName;
		oRealtimeBinFile.m_nBinDataOffset = nBinDataOffset;
		oRealtimeBinFile.m_nBinDataSize = oBinFile.m_Size;
		oRealtimeBinFile.m_fSampleRate = 10e6 / (512 * oBinFile.m_SampleRate);
		oRealtimeBinData.m_aBinFile.InsertAt(j, oRealtimeBinFile);
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CRealtimeData::UpdateGraphWindow
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���A���^�C���f�[�^�E�B���h�E��\������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CRealtimeData::UpdateGraphWindow()
{
	for (int i = 0; i < m_aGraph.GetSize(); i++) {
		CRealtimeGraph &oGraph = m_aGraph[i];

		if (oGraph.m_nIdbSize > 0) {
			// ���x�O���t
			if (oGraph.m_pGraphTemp == NULL) {
				// ���A���^�C���O���t��ʂ��\������Ă��Ȃ��ꍇ�͐V�K�ɃE�B���h�E���J��
				CGraphFrame *pGraphFrame = new CGraphFrame;
				if (pGraphFrame->DispRealtimeGraph(oGraph.GetIdbPathName(), GRAPH_TEMP, oGraph.m_bExistLossFile))
					oGraph.m_pGraphTemp = pGraphFrame;
				else {
					delete pGraphFrame;
					m_aGraph.RemoveAt(i);
				}
			} else if (oGraph.m_bUpdateGraph) {
				// ���A���^�C���O���t��ʂ��\������Ă��čX�V���������ꍇ�̓O���t�ɔ��f����
				oGraph.m_pGraphTemp->UpdateRealtimeGraph(oGraph.m_bExistLossFile);
			}

			// �����x�O���t
			if (oGraph.m_pGraphAccl == NULL) {
				// ���A���^�C���O���t��ʂ��\������Ă��Ȃ��ꍇ�͐V�K�ɃE�B���h�E���J��
				CGraphFrame *pGraphFrame = new CGraphFrame;
				if (pGraphFrame->DispRealtimeGraph(oGraph.GetIdbPathName(), GRAPH_ACCEL, oGraph.m_bExistLossFile))
					oGraph.m_pGraphAccl = pGraphFrame;
				else {
					delete pGraphFrame;
					m_aGraph.RemoveAt(i);
				}
			} else if (oGraph.m_bUpdateGraph) {
				// ���A���^�C���O���t��ʂ��\������Ă��čX�V���������ꍇ�̓O���t�ɔ��f����
				oGraph.m_pGraphAccl->UpdateRealtimeGraph(oGraph.m_bExistLossFile);
			}
		}

		oGraph.m_bUpdateGraph = FALSE;
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CRealtimeData::CloseGraphWindow
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���A���^�C���f�[�^�E�B���h�E���蓮�ŃN���[�Y���ꂽ���̏���
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		HWND		hWnd			[I] �N���[�Y�����E�B���h�E�̃n���h��
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CRealtimeData::CloseGraphWindow(HWND hWnd)
{
	// �O���t�E�B���h�E���蓮�ŃN���[�Y�����ꍇ�͂��̃|�C���^���N���A����
	for (int i = 0; i < m_aGraph.GetSize(); i++) {
		CRealtimeGraph &oGraph = m_aGraph[i];
		if (oGraph.m_pGraphAccl != NULL) {
			if (oGraph.m_pGraphAccl->m_hWnd == hWnd) {
				oGraph.m_pGraphAccl = NULL;
				break;
			}
		}
		if (oGraph.m_pGraphTemp != NULL) {
			if (oGraph.m_pGraphTemp->m_hWnd == hWnd) {
				oGraph.m_pGraphTemp = NULL;
				break;
			}
		}
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CRealtimeData::DeleteTempFiles
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�e���|�����t�@�C���폜����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CRealtimeData::DeleteTempFiles()
{
	if (gRealtimeTempPath[0] != '\n') {
		CFileFind FileFind;

		// �e���|�����t�H���_����idb�t�@�C��������
		CString sSearchFile;
		sSearchFile.Format("%s\\*.idb", gRealtimeTempPath);
		if(!FileFind.FindFile(sSearchFile)) 
			return; 

		// �������ꂽidb�t�@�C������������
		BOOL bContinue = TRUE;
		while(bContinue){ 
			bContinue = FileFind.FindNextFile();

			// �������ꂽidb�t�@�C�����폜����
			DeleteIdbFile(FileFind.GetFilePath());
		}
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CRealtimeData::DeleteIdbFile
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		Idb�t�@�C���폜����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		LPCTSTR		pIdbFilePath			[I] idb�t�@�C���p�X
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CRealtimeData::DeleteIdbFile(LPCTSTR pIdbFilePath)
{
	CDataFile oDataFile;
	if (oDataFile.ReadInfoFile(pIdbFilePath)) {
		CString sDatPath;
		for (int i = 0; i < oDataFile.m_aDataFileName.GetSize(); i++) {
			// �f�[�^�t�@�C�����폜����
			sDatPath.Format("%s\\%s", oDataFile.m_sDatPath, oDataFile.m_aDataFileName[i]);
			::DeleteFile(sDatPath);
		}
	}

	// idb�t�@�C�����폜����
	::DeleteFile(pIdbFilePath);
}

//*****************************************************************************************************
//  1. �֐���
//		CRealtimeData::UpdateTempGraph
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���x�O���t�X�V����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CGraphFrame		*pGraphAccl			[I] �����x�O���t
//		double			fTimeDispEnd		[I] �\���͈�
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CRealtimeData::UpdateTempGraph(CGraphFrame *pGraphAccl, double fTimeDispEnd)
{
	for (int i = 0; i < m_aGraph.GetSize(); i++) {
		CRealtimeGraph &oGraph = m_aGraph[i];
		if (oGraph.m_pGraphAccl == pGraphAccl) {
			if (oGraph.m_pGraphTemp != NULL)
				oGraph.m_pGraphTemp->SetRealtimeDispEnd(fTimeDispEnd);
		}
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CRealtimeData::MoveOldBinData
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�����A���^�C���J�n���Ɋ��ɑ��݂���bin�t�@�C�����ړ�����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CRealtimeData::MoveOldBinData()
{
	CString sOldBinFolder;
	sOldBinFolder.Format("%s\\%s", gRealtimeTempPath, OLD_BIN_FILE);

	// �ړ���t�H���_�쐬
	if (!::PathIsDirectory(sOldBinFolder))
		::CreateDirectory(sOldBinFolder, NULL);

	// bin�t�@�C�����ړ�
	CFileStatus fs;
	CString sFindFile;
	CString sMovePath;
	sFindFile.Format("%s\\*", gRealtimeDataPath);
	CFileFind oFileFind;
	BOOL bFind = oFileFind.FindFile(sFindFile);
	while (bFind) {
		bFind = oFileFind.FindNextFile();

		CFile::GetStatus(oFileFind.GetFilePath(), fs);
		if (!(fs.m_attribute & CFile::directory)) {
			sMovePath.Format("%s\\%s", sOldBinFolder, oFileFind.GetFileName());
			if (::PathFileExists(sMovePath))
				::DeleteFile(sMovePath);

			::MoveFile(oFileFind.GetFilePath(), sMovePath);
		}
	}
}
