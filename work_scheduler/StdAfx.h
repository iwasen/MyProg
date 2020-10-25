// stdafx.h : 標準のシステム インクルード ファイル、
//            または参照回数が多く、かつあまり変更されない
//            プロジェクト専用のインクルード ファイルを記述します。
//

#if !defined(AFX_STDAFX_H__A28ECD55_497A_4837_8F67_19433FBAA3F9__INCLUDED_)
#define AFX_STDAFX_H__A28ECD55_497A_4837_8F67_19433FBAA3F9__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000

#define VC_EXTRALEAN		// Windows ヘッダーから殆ど使用されないスタッフを除外します。

#include <afxwin.h>         // MFC のコアおよび標準コンポーネント
#include <afxext.h>         // MFC の拡張部分
#include <afxdisp.h>        // MFC のオートメーション クラス
#include <afxdtctl.h>		// MFC の Internet Explorer 4 コモン コントロール サポート
#ifndef _AFX_NO_AFXCMN_SUPPORT
#include <afxcmn.h>			// MFC の Windows コモン コントロール サポート
#endif // _AFX_NO_AFXCMN_SUPPORT

#include <afxtempl.h>
#include <comdef.h>

// 内容：符号付きの値を代入する変数には、符号付きの型にキャストしなければなりません
#pragma warning( disable : 4146 )	// リビルド時のWarningを非表示にするため
// 内容：'name' を自動的に除外し、タイプ ライブラリ 'library' をインポートします
#pragma warning( disable : 4192 )	// リビルド時のWarningを非表示にするため
// 内容：コンパイラの制限 : 行番号の出力を中止します。
#pragma warning( disable : 4049 )	// リビルド時のWarningを非表示にするため

// Excel
#import "C:\Program Files\Microsoft Office\Office\Mso9.dll" no_namespace rename("DocumentProperties", "DocumentPropertiesXL")
#import "C:\Program Files\Common Files\Microsoft Shared\VBA\VBA6\Vbe6ext.olb" no_namespace
#import "C:\Program Files\Microsoft Office\Office\excel9.olb" rename("DialogBox", "DialogBoxXL") rename("RGB", "RBGXL") rename("DocumentProperties", "DocumentPropertiesXL") no_dual_interfaces

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ は前行の直前に追加の宣言を挿入します。

#endif // !defined(AFX_STDAFX_H__A28ECD55_497A_4837_8F67_19433FBAA3F9__INCLUDED_)
