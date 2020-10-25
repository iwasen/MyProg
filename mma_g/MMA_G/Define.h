#pragma once
//*****************************************************************************************************
//  1. �t�@�C����
//		Define.h
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		define��`
//----------------------------------------------------------------------------------------------------
//  3. ���l
//----------------------------------------------------------------------------------------------------
//  4. ����
//		2007.08.09 S.Aizawa �V�K�쐬
//*****************************************************************************************************

// �O���t�Ŏg���F
#define COLOR_GRAPH_DATA_X	RGB(0xff,0x00,0x80)
#define COLOR_GRAPH_DATA_Y	RGB(0x00,0xc0,0x00)
#define COLOR_GRAPH_DATA_Z	RGB(0x00,0x80,0xff)
#define COLOR_GRAPH_DATA_G	RGB(0x40,0x40,0x40)
#define COLOR_GRAPH_DATA_D	RGB(0x60,0x00,0xc0)
#define COLOR_GRAPH_SCALE	RGB(0x00,0x00,0x00)
#define COLOR_GRAPH_BG		RGB(0xff,0xff,0xff)
#define COLOR_GRAPH_CURSOR	RGB(0xff,0x00,0x00)
#define COLOR_GRAPH_SELECT	RGB(0xb0,0xff,0xff)
#define COLOR_LIMIT_LINE	RGB(0xff,0x00,0x00)
#define COLOR_WHITE			RGB(0xff,0xff,0xff)
#define COLOR_LIGHT_GRAY	RGB(0xb0,0xb0,0xb0)
#define COLOR_MID_GRAY		RGB(0xaa,0xaa,0xaa)
#define COLOR_GRAY			RGB(0x80,0x80,0x80)
#define COLOR_BLACK			RGB(0x00,0x00,0x00)

#define PI 3.14159265358979323846

// �ΐ��ϊ�
#define dB10(x)	(log10((double)(x)) * 10)
#define dB20(x)	(log10((double)(x)) * 20)

// ���[�U��`�E�B���h�E���b�Z�[�W
#define WM_USER_OPEN_GRAPH			(WM_APP + 1)	// ��̓O���t�E�B���h�E�I�[�v���ʒm
#define WM_USER_CLOSE_GRAPH			(WM_APP + 2)	// ��̓O���t�E�B���h�E�N���[�Y�ʒm
#define WM_USER_REALTIME_GRAPH		(WM_APP + 3)	// ���A���^�C���O���t�E�B���h�E�ʒm
#define WM_USER_MESSAGE_BOX			(WM_APP + 4)	// ���b�Z�[�W�{�b�N�X�\���ʒm
#define WM_USER_AUTO_CONV			(WM_APP + 5)	// �����H�w�l�ϊ��ʒm
#define WM_USER_CLOSE_ZERO_OFFSET	(WM_APP + 6)	// 0�_�␳�l�O���t�E�B���h�E�N���[�Y�ʒm

// �O���t���
#define GRAPH_ACCEL		0		// �����x�O���t
#define GRAPH_TEMP		1		// ���x�O���t
#define GRAPH_ZERO_OFFSET	2	// 0�_�␳�l�O���t
