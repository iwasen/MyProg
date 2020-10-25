#include "stdafx.h"
#include "StdioFileEX.h"

#define	SIZE_READ_BUF	(1024*1024*13)	// 13M

CStdioFileEX::CStdioFileEX(void)
: m_ReadBuf(NULL)
{
	m_ReadCnt = 0;
	m_CurPos = 0;
	m_ReadBuf = NULL;

// 2008/07/22 appended by yG for adjust a/d converter error ->
	memset(m_caSecondPreviousData, NULL, 3);
	memset(m_caFirstPreviousData, NULL, 3);
	memset(m_caCurrentData, NULL, 3);
// <- 2008/07/22 appended by yG for adjust a/d converter error
}

CStdioFileEX::~CStdioFileEX(void)
{
}

// OPEN����
BOOL CStdioFileEX::OpenEX(LPCSTR lpszFileName, UINT nOpenFlags, CFileException * pError)
{
	BOOL	rt;

	if ((rt = Open(lpszFileName, nOpenFlags, pError)) != FALSE) {
		// OPEN����
		m_ReadBuf = new char [SIZE_READ_BUF];
	} else {
		// OPEN���s
		m_ReadBuf = NULL;
	}
	return rt;
}

// CLOSE����
void CStdioFileEX::CloseEX(void)
{
	Close();
	if (m_ReadBuf != NULL)
		delete [] m_ReadBuf;
	m_ReadBuf = NULL;
}


// �ǂݍ��ݏ���
UINT CStdioFileEX::ReadEX(void * lpBuf, UINT nCount)
{
	UINT	size, cnt;

	if (m_ReadBuf == NULL)
		return 0;

	// �c�f�[�^���w��ǂݍ��݃T�C�Y�������ꍇ�V���ɓǂݍ���
	size = m_ReadCnt - m_CurPos;
	if (size < nCount) {
		// �c��f�[�^��擪�ɃR�s�[
		if (size > 0)
			memcpy(m_ReadBuf, &m_ReadBuf[m_CurPos], size);
		// �V���Ƀf�[�^��ǂ�
		m_ReadCnt = Read(&m_ReadBuf[size], SIZE_READ_BUF - size);
		// READ�J�E���g�͓ǂݍ��݃J�E���g�{�R�s�[�����J�E���g
		m_ReadCnt += size;
		m_CurPos = 0;
		size = m_ReadCnt - m_CurPos;
	}
	// �ǂݍ��݌㑫��Ȃ��ꍇ������̂ŁA�ă`�F�b�N
	if (size >= nCount)
		cnt = nCount;
	else
		cnt = size;

	// �w��ǂݍ��݃o�b�t�@�Ƀf�[�^���R�s�[
	memcpy(lpBuf, &m_ReadBuf[m_CurPos], cnt);

// 2008/07/22 appended by yG for adjust a/d converter error ->
	memcpy(m_caSecondPreviousData, m_caFirstPreviousData, 3);	// 2�O�̃f�[�^���X�V
	memcpy(m_caFirstPreviousData, m_caCurrentData, 3);			// 1�O�̃f�[�^���X�V
	memcpy(m_caCurrentData, &m_ReadBuf[m_CurPos], 3);			// ���݂̃f�[�^
// <- 2008/07/22 appended by yG for adjust a/d converter error

	m_CurPos += cnt;

	// �R�s�[�����T�C�Y��Ԓl
	return cnt;
}

// 2008/07/22 appended by yG for adjust a/d converter error ->
void
CStdioFileEX::GetSecondPrevious(char* out_caBuffer)
{
	// �{�֐��́Cx�Cy�Cz���f�[�^�̎擾�݂̂�ړI�Ƃ��C
	// �f�[�^�擾�̃o�b�t�@�T�C�Y�́C�K���3��ł��邱�ƂƂ���B

	// 2�O�̃f�[�^���擾����
	memcpy(out_caBuffer, m_caSecondPreviousData, 3);
}
void
CStdioFileEX::GetFirstPrevious(char* out_caBuffer)
{
	// �{�֐��́Cx�Cy�Cz���f�[�^�̎擾�݂̂�ړI�Ƃ��C
	// �f�[�^�擾�̃o�b�t�@�T�C�Y�́C�K���3��ł��邱�ƂƂ���B

	// 1�O�̃f�[�^���擾����
	memcpy(out_caBuffer, m_caFirstPreviousData, 3);
}
void
CStdioFileEX::GetFirstNext(char* out_caBuffer)
{
	// �{�֐��́Cx�Cy�Cz���f�[�^�̎擾�݂̂�ړI�Ƃ��C
	// �f�[�^�擾�̃o�b�t�@�T�C�Y�́C�K���3��ł��邱�ƂƂ���B

	// 1��̃f�[�^���擾����
	// ���݂̃o�b�t�@�ɂ��邩�m�F����
	UINT a_ulSize = m_ReadCnt - m_CurPos;
	if (a_ulSize > 3)
	{
		// ���݂̃o�b�t�@�ɂ���ꍇ�́C
		// ���[�h�o�b�t�@����R�s�[����
		memcpy(out_caBuffer, &m_ReadBuf[m_CurPos], 3);
	}
	else
	{
		// ���݂̃o�b�t�@�ɂȂ��ꍇ�́C�V���ɓǂݍ���
		if (a_ulSize > 0)
		{
			// ���r���[�Ȉʒu�̏ꍇ�C�c��f�[�^��擪�ɃR�s�[
			memcpy(m_ReadBuf, &m_ReadBuf[m_CurPos], a_ulSize);
		}
		// �V���Ƀf�[�^��ǂ�
		m_ReadCnt = Read(&m_ReadBuf[a_ulSize], SIZE_READ_BUF - a_ulSize);
		// READ�J�E���g�͓ǂݍ��݃J�E���g�{�R�s�[�����J�E���g
		m_ReadCnt += a_ulSize;
		m_CurPos = 0;

		// �f�[�^������Ȃ��ꍇ�́C0�Ƃ���
		if ((m_ReadCnt - m_CurPos) < 3)
		{
			memset(out_caBuffer, NULL, 3);
		}
		else
		{
			// ���[�h�o�b�t�@����R�s�[����
			memcpy(out_caBuffer, &m_ReadBuf[m_CurPos], 3);
		}
	}
}
void
CStdioFileEX::GetSecondNext(char* out_caBuffer)
{
	// �{�֐��́Cx�Cy�Cz���f�[�^�̎擾�݂̂�ړI�Ƃ��C
	// �f�[�^�擾�̃o�b�t�@�T�C�Y�́C�K���3��ł��邱�ƂƂ���B

	// 1��̃f�[�^���擾����
	// ���݂̃o�b�t�@�ɂ��邩�m�F����
	UINT a_ulSize = m_ReadCnt - m_CurPos;
	if (a_ulSize > 6)
	{
		// ���݂̃o�b�t�@�ɂ���ꍇ�́C
		// ���[�h�o�b�t�@����R�s�[����
		memcpy(out_caBuffer, &m_ReadBuf[m_CurPos+3], 3);
	}
	else
	{
		// ���݂̃o�b�t�@�ɂȂ��ꍇ�́C�V���ɓǂݍ���
		// �c��f�[�^��擪�ɃR�s�[
		memcpy(m_ReadBuf, &m_ReadBuf[m_CurPos], a_ulSize);
		// �V���Ƀf�[�^��ǂ�
		m_ReadCnt = Read(&m_ReadBuf[a_ulSize], SIZE_READ_BUF - a_ulSize);
		// READ�J�E���g�͓ǂݍ��݃J�E���g�{�R�s�[�����J�E���g
		m_ReadCnt += a_ulSize;
		m_CurPos = 0;

		// �f�[�^������Ȃ��ꍇ�́C0�Ƃ���
		if ((m_ReadCnt - m_CurPos) < 3)
		{
			memset(out_caBuffer, NULL, 3);
		}
		else
		{
			// ���[�h�o�b�t�@����R�s�[����
			memcpy(out_caBuffer, &m_ReadBuf[m_CurPos+3], 3);
		}
	}
}
// <- 2008/07/22 appended by yG for adjust a/d converter error