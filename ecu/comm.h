// comm.h : �C���v�������e�[�V���� �t�@�C��
//

#if !defined(_COMM_H_)
#define _COMM_H_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000

// ���M���b�Z�[�W
#define WM_RECV_NOTIFY	(WM_USER + 1000)

// ���b�Z�[�W�ʒm�R�[�h
#define NOTIFY_TExxxOUNT	1
#define NOTIFY_RECVCOUNT	2
#define NOTIFY_TESTEND		3
#define NOTIFY_RECVSTATUS	4

extern BOOL StartCommHandler();
extern void StartTest(CWnd *pWnd, LPCTSTR pSaveFolder);
extern void StopTest();

#endif // _COMM_H_