// stdafx.h : �W���̃V�X�e�� �C���N���[�h �t�@�C���A
//            �܂��͎Q�Ɖ񐔂������A�����܂�ύX����Ȃ�
//            �v���W�F�N�g��p�̃C���N���[�h �t�@�C�����L�q���܂��B
//

#if !defined(AFX_STDAFX_H__A28ECD55_497A_4837_8F67_19433FBAA3F9__INCLUDED_)
#define AFX_STDAFX_H__A28ECD55_497A_4837_8F67_19433FBAA3F9__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000

#define VC_EXTRALEAN		// Windows �w�b�_�[����w�ǎg�p����Ȃ��X�^�b�t�����O���܂��B

#include <afxwin.h>         // MFC �̃R�A����ѕW���R���|�[�l���g
#include <afxext.h>         // MFC �̊g������
#include <afxdisp.h>        // MFC �̃I�[�g���[�V���� �N���X
#include <afxdtctl.h>		// MFC �� Internet Explorer 4 �R���� �R���g���[�� �T�|�[�g
#ifndef _AFX_NO_AFXCMN_SUPPORT
#include <afxcmn.h>			// MFC �� Windows �R���� �R���g���[�� �T�|�[�g
#endif // _AFX_NO_AFXCMN_SUPPORT

#include <afxtempl.h>
#include <comdef.h>

// ���e�F�����t���̒l��������ϐ��ɂ́A�����t���̌^�ɃL���X�g���Ȃ���΂Ȃ�܂���
#pragma warning( disable : 4146 )	// ���r���h����Warning���\���ɂ��邽��
// ���e�F'name' �������I�ɏ��O���A�^�C�v ���C�u���� 'library' ���C���|�[�g���܂�
#pragma warning( disable : 4192 )	// ���r���h����Warning���\���ɂ��邽��
// ���e�F�R���p�C���̐��� : �s�ԍ��̏o�͂𒆎~���܂��B
#pragma warning( disable : 4049 )	// ���r���h����Warning���\���ɂ��邽��

// Excel
#import "C:\Program Files\Microsoft Office\Office\Mso9.dll" no_namespace rename("DocumentProperties", "DocumentPropertiesXL")
#import "C:\Program Files\Common Files\Microsoft Shared\VBA\VBA6\Vbe6ext.olb" no_namespace
#import "C:\Program Files\Microsoft Office\Office\excel9.olb" rename("DialogBox", "DialogBoxXL") rename("RGB", "RBGXL") rename("DocumentProperties", "DocumentPropertiesXL") no_dual_interfaces

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ �͑O�s�̒��O�ɒǉ��̐錾��}�����܂��B

#endif // !defined(AFX_STDAFX_H__A28ECD55_497A_4837_8F67_19433FBAA3F9__INCLUDED_)
