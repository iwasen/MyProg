#pragma once
//*****************************************************************************************************
//  1. �t�@�C����
//		DataManager.h
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		CDataManager �N���X�̃C���^�[�t�F�C�X
//----------------------------------------------------------------------------------------------------
//  3. ���l
//----------------------------------------------------------------------------------------------------
//  4. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************

#include "ExcelSeisanDaisu.h"
#include "ExcelKinmuTaisei.h"
#include "ExcelBuhinhyoLink.h"
#include "ExcelDSTRSanshutsu.h"
#include "ExcelYosoSagyoHyo.h"
#include "ExcelLineKiseiMatrix.h"
#include "ExcelKatashikiKiseiMatrix.h"
#include "ExcelKittingTemplate.h"
#include "ExcelSeisanJoken.h"
#include "ExcelBuhinKitting.h"
#include "ExcelKyotsuKotei.h"
#include "ExcelKittingTaishogai.h"
#include "ExcelKittingTaishoBuhin.h"
#include "ExcelYosoSagyoTemplate.h"
#include "ExcelKatashikiKiseiError.h"
#include "ExcelLineKiseiError.h"
#include "ExcelExportGraphTemplate.h"

// �X�e�[�^�X�R�[�h
#define STATUS_NOREAD			0
#define STATUS_READ_DATA		1
#define STATUS_SEISAN_JOKEN		2
// Modify ... ( ADD )
#define STATUS_JIDO_HENSEI		3
#define STATUS_SHUDO_HENSEI		4
// By Y.Itabashi (xxxxx) 2007.03.06

// �L�b�g���H���L��
#define KITTING_KOTEI_KIGO			"VK0K"

// �H���K���t�@�C���ҏW�p���b�Z�[�W�R�[�h
#define WM_OPEN_EXCEL			(WM_USER + 300)
#define WM_CLOSE_EXCEL			(WM_USER + 301)
#define WM_ERROR_EXCEL			(WM_USER + 302)
#define EXCEL_LINE_KISEI_MATRIX			0
#define EXCEL_KATASHIKI_KISEI_MATRIX	1

// ���Y�䐔�f�[�^
struct SSeisanDaisu {
	CString sYexxxonth;			// �N��(YYYYMM)
	CIntArrayEx aSeisanDaisu;	// ���Y�䐔
};
typedef CArrayEx2 <SSeisanDaisu, SSeisanDaisu&> CSeisanDaisuArray;

// ���Y�����f�[�^
struct SSeisanJoken {
	double fKinmuTaisei;	// �Ζ��̐�(H)
	int nKyusokuJikan;		// �x�e����(��)
	int nKadoNissu;			// �ғ�����(Day/��)
	int nKotaiKinmu;		// ���Ζ�(��)
	double fKadoJikan;		// �ғ�����(H/��)
	BOOL bKyoseiKudo;		// �����쓮
	double fKadoRitsu;		// �ғ���(%)
	double fTact;			// �^�N�g(��/��)
	double fJogen;			// ���(+%)
	double fKagen;			// �����i-%)
	BOOL bSaved;			// �ۑ��ς݃t���O

	// �R���X�g���N�^
	SSeisanJoken() {
		fKinmuTaisei = 0;
		nKyusokuJikan = 0;
		nKadoNissu = 0;
		nKotaiKinmu = 0;
		fKadoJikan = 0;
		bKyoseiKudo = FALSE;
		fKadoRitsu = 100;
		fTact = 0;
		fJogen = 0;
		fKagen = 0;
		bSaved = FALSE;
	}
};

// ��Ə��f�[�^
struct SSagyojun {
	int nIndex;					// �z��C���f�b�N�X
	int nYosoSagyogunId;		// �v�f��ƌQID
	int nSagyojun;				// ��Ə�
	int nKishuId;				// �@��ID
};
typedef CArrayEx <SSagyojun, SSagyojun&> CSagyojunArray;

// �v�f��ƃf�[�^
struct SYosoSagyo {
	CString sYosoSagyoName;		// �v�f��Ɩ�
	CString sYosoSagyoNo;		// �v�f���No
	int nSagyoNo;				// ���No
	CString sDeleteFlag;		// �폜�t���O
	CString sInsertFlag;		// �ǉ��t���O
	int nRow;					// �s�ʒu
	double fTime;				// ��Ǝ���
	CString sEnzanShiki;		// ���Z��
	double fS1;					// S1
	double fS2;					// S2
	double fM;					// M
	double fN;					// N
	double fEnzangoSagyoJikan;	// ���Z��̍�Ǝ���
	int nHassexxxndo;			// �����p�x
	int nKurikaeshiKaisu;		// �J��Ԃ���
	CString sKoteibuHendobu;	// �Œ蕔/�ϓ���
	CString sSagyoFlag;			// ��ƃt���O
	int nKijunKotei;			// �/�H��
	BOOL bKittingDelete;		// �L�b�g���ɂ��폜�t���O

	// �R���X�g���N�^
	SYosoSagyo() {
		bKittingDelete = FALSE;
	}
};
typedef CArrayEx <SYosoSagyo, SYosoSagyo&> CYosoSagyoArray;

// �@��ʍ�ƃf�[�^
struct SKishuSagyo {
	int nSagyojun;				// ��Ə�
	int nSagyoshaId;			// ��Ǝ�ID
	int nGroupNo;				// �O���[�v�ԍ�
	BOOL bKyotsuKotei;			// ���ʍH���t���O
	BOOL bNewMove;				// �ŐV�ړ��t���O
};
typedef CArrayEx <SKishuSagyo, SKishuSagyo&> CKishuSagyoArray;

// �v�f��ƌQ�f�[�^
struct SYosoSagyogun {
	CString sKoteiKigo;				// �H���L��
	CString sKoteiNo;				// �H���ԍ�
	CString sKoteiName;				// �H����
	CString sYosoSagyogunName;		// �v�f��ƌQ��
	CString sJidoYosoSagyogun;		// �v�f��ƌQ�̎������
	CString sJidoKotei;				// �H���̎������
	CYosoSagyoArray aYosoSagyo;		// �v�f��ƃf�[�^�z��
	CKishuSagyoArray aKishuSagyo;	// �@��ʍ�ƃf�[�^�z��
	int nMoveSublineId;				// �ړ��O�T�u���C��ID
	BOOL bKittingCancel;			// �L�b�g�������ɂ�镜���t���O

	// �R���X�g���N�^
	SYosoSagyogun() {
		bKittingCancel = FALSE;
	}

	// �R�s�[�R���X�g���N�^
	SYosoSagyogun(SYosoSagyogun &src) {
		*this = src;
	}

	// ��Ǝ��Ԏ擾
	double GetTime() {
		double fTime = 0;
		int nSize = aYosoSagyo.GetSize();
		for (int i = 0; i < nSize; i++) {
			if (aYosoSagyo[i].sDeleteFlag.IsEmpty())
				fTime += aYosoSagyo[i].fTime;
		}
		return fTime;
	}

	// �폜�`�F�b�N
	BOOL CheckDeleted() {
		int nSize = aYosoSagyo.GetSize();
		for (int i = 0; i < nSize; i++) {
			if (aYosoSagyo[i].sDeleteFlag != "1" && aYosoSagyo[i].sDeleteFlag != "2")
				return FALSE;
		}
		return TRUE;
	}
};

class CYosoSagyogunArray : public CArrayEx2 <SYosoSagyogun, SYosoSagyogun&> {
public:
	void MakeKey() {
		m_cMap.RemoveAll();
		int nSize = GetSize();
		for (int i = 0; i < nSize; i++) {
			SYosoSagyogun &c = ElementAt(i);
			m_cMap.SetAt(c.sKoteiName + c.sYosoSagyogunName, i);
		}
	}
};

// �H���f�[�^
struct SKotei {
	CString sKoteiName;			// �H����
	double fTime;				// ��Ǝ���
	CIntArrayEx aYosoSagyogunId;	// �v�f��ƌQID�z��
};
typedef CArrayEx <SKotei, SKotei&> CKoteiArray;

// �^���K���}�g���N�X��f�[�^
struct SKatashikiKiseiCol {
	CString sKishuName;			// �@�햼
	CString sSublineName;		// �T�u���C����
	CString sKoteiNo;			// �H���ԍ�
	CString sKoteiName;			// �H����
	CString sYosoSagyogunName;	// �v�f��ƌQ��
	CString sKisei;				// �K��
	CString sRiyu;				// �K�����R
};
typedef CArrayEx2 <SKatashikiKiseiCol, SKatashikiKiseiCol&> CKatashikiKiseiColArray;

// �^���K���}�g���N�X�s�f�[�^
struct SKatashikiKisei {
	CString sKishuName;			// �@�햼
	CString sSublineName;		// �T�u���C����
	CString sKoteiNo;			// �H���ԍ�
	CString sKoteiName;			// �H����
	CString sYosoSagyogunName;	// �v�f��ƌQ��
	CKatashikiKiseiColArray aKatashikiKiseiCol;	// �^���K���}�g���N�X��f�[�^
};
typedef CArrayEx2 <SKatashikiKisei, SKatashikiKisei&> CKatashikiKiseiArray;

// ���C���K���f�[�^
struct SLineKisei {
	CString sSublineName;		// �T�u���C����
	CString sKoteiNo;			// �H���ԍ�
	CString sKoteiName;			// �H����
	CString sYosoSagyogunName;	// �v�f��ƌQ��
	CString sEngGenjo;			// ENG�p��
	BOOL bEngSeiritsuFr;		// ENG����Fr�t���O
	BOOL bEngSeiritsuRr;		// ENG����Rr�t���O
	BOOL bEngToritsuFr;			// ENG�|��Fr�t���O
	BOOL bEngToritsuRr;			// ENG�|��Rr�t���O
	BOOL bEngChokuritsuTop;		// ENG����Top�t���O
	BOOL bEngChokuritsuBottom;	// ENG����Bottom�t���O
// --Add 2007/03/02 aizawa �s��D-147
	int nRow;					// ���C���K���}�g���N�X�t�@�C���̍s�ʒu
// --Add

	// �R���X�g���N�^
	SLineKisei() {
		bEngSeiritsuFr = FALSE;
		bEngSeiritsuRr = FALSE;
		bEngToritsuFr = FALSE;
		bEngToritsuRr = FALSE;
		bEngChokuritsuTop = FALSE;
		bEngChokuritsuBottom = FALSE;
	}
};
typedef CArrayEx2 <SLineKisei, SLineKisei&> CLineKiseiArray;

// �^���K���ᔽ�f�[�^
struct SKatashikiError {
	int	nKishuID;					// �@��ID
	CString sKishuName;				// �@�햼
	CString sDay;					// ���ɂ�
	CString sTime;					// ����
	CString sSublineFrom;			// �ړ��T�u���C����
	CString sKoteiNoFrom;			// �ړ��H���ԍ�
	CString sKoteiNameFrom;			// �ړ��H����
	CString sYosoSagyogunNameFrom;	// �ړ��v�f��ƌQ��
	CString sSublineTo;				// �ړ���T�u���C����
	CString sKoteiNoTo;				// �ړ���H���ԍ�
	CString sKoteiNameTo;			// �ړ���H����
	CString sYosoSagyogunNameTo;	// �ړ���v�f��ƌQ��
	CString sSublineNg;				// �ᔽ�T�u���C����
	CString sKoteiNoNg;				// �ᔽ�H���ԍ�
	CString sKoteiNameNg;			// �ᔽ�H����
	CString sYosoSagyogunNameNg;	// �ᔽ�v�f��ƌQ��
	CString sRiyuNo;				// �H���K���ᔽ���R�ԍ�
	CString sRiyuMsg;				// �H���K���ᔽ���R
};
typedef CArrayEx2 <SKatashikiError, SKatashikiError&> CKatashikiErrorArray;

// ���C���K���ᔽ�f�[�^
struct SLineError {
	int	nKishuID;					// �@��ID
	CString sDay;					// ���ɂ�
	CString sTime;					// ����
	CString sSublineFrom;			// �ړ��T�u���C����
	CString sKoteiNoFrom;			// �ړ��H���ԍ�
	CString sKoteiNameFrom;			// �ړ��H����
	CString sYosoSagyogunNameFrom;	// �ړ��v�f��ƌQ��
	CString sSublineTo;				// �ړ���T�u���C����
	CString sKoteiNoToB;			// �ړ���̑O�̍H���ԍ�
	CString sKoteiNameToB;			// �ړ���̑O�̍H����
	CString sYosoSagyogunNameToB;	// �ړ���̑O�̗v�f��ƌQ��
	CString sKoteiNoToA;			// �ړ���̌�̍H���ԍ�
	CString sKoteiNameToA;			// �ړ���̌�̍H����
	CString sYosoSagyogunNameToA;	// �ړ���̌�̗v�f��ƌQ��
	CString sRiyuNo;				// �H���K���ᔽ���R�ԍ�
	CString sRiyuMsg;				// �H���K���ᔽ���R
};
typedef CArrayEx2 <SLineError, SLineError&> CLineErrorArray;

// DSTR�Z�o�f�[�^
struct SDSTRSanshutsu {
	double fHyojunJikan;			// �W������
	double fTeikiTenkenJikan;		// ����_������
	double fHinshitsuLoss;			// �i��۽
	double fSetsubiFuguaiLoss;		// �ݔ��s�۽
	double fShujukuLoss;			// �K�n۽
	double fLineTeishiLoss;			// ײݒ�~۽
	double fHenseiLoss;				// �Ґ�۽
	double fHukugouNoritsuBun;		// �����\����
	double fKanriTeishiJikan;		// �Ǘ���~����
	double fDST;					// DST

	// �R���X�g���N�^
	SDSTRSanshutsu() {
		fHyojunJikan = 0;
		fTeikiTenkenJikan = 0;
		fHinshitsuLoss = 0;
		fSetsubiFuguaiLoss = 0;
		fShujukuLoss = 0;
		fLineTeishiLoss = 0;
		fHenseiLoss = 0;
		fHukugouNoritsuBun = 0;
		fKanriTeishiJikan = 0;
		fDST = 0;
	}
};

// ���i�\���H�������N�f�[�^
struct SBuhinhyoLink {
	CString sKatashiki;				// �^��
	CString sKishu;					// �@��
	CString sBlockNo;				// ��ۯ�No
	CString sBuhinNo;				// ���i�ԍ�
	CString sBuhinName;				// ���i����
	int nPC;						// P/C
	BOOL bBoltFlag;					// �����׸�
	CString sSublineName;			// �ݸ����ײݖ�
	CString sKoteiKigo;				// �ݸ��H���L��
	CString sKoteiNo;				// �ݸ��H���ԍ�
	CString sKoteiName;				// �ݸ��H������
	CStringArray aYosoSagyogunName;	// �ݸ��v�f��ƌQ��
};
typedef CArrayEx <SBuhinhyoLink, SBuhinhyoLink&> CBuhinhyoLinkArray;

// �v�f��ƏC���f�[�^
struct SYosoSagyoInfo {
	CString sSubline;			// �T�u���C����
	CString sKoteiKigo;			// �H���L��
	CString sKoteiNo;			// �H���ԍ�
	CString sSagyoshaName;		// ��ƎҖ�
	CString sKoteiName;			// �H����
	CString sYosoSagyogunName;	// �v�f��ƌQ��
	CString sJidoShudo;			// �����^�蓮
};

// �ړ������f�[�^
struct SMoveData {
	CString sName;				// �ړ��Ώۖ�
	int nOrg;					// �ړ��O
};
typedef CArrayEx <SMoveData, SMoveData&> CMoveDataArray;

// �L�b�g���v�f��ƃf�[�^
struct SKittingYosoSagyo {
	CString sYosoSagyoName;		// �v�f��Ɩ�
	int nSagyoNo;				// ���No
	int nYosoSagyoId;			// �v�f���ID
	BOOL bSakujoFlag;			// �폜�t���O
	double fTime;				// ��Ǝ���
};
typedef CArrayEx <SKittingYosoSagyo, SKittingYosoSagyo&> CKittingYosoSagyoArray;

// �L�b�g���v�f��ƌQ�f�[�^
struct SKittingYosoSagyogun {
	CString sKoteiName;			// �H����
	CString sYosoSagyogunName;	// �v�f��ƌQ��
	CKittingYosoSagyoArray aKittingYosoSagyo;	// �L�b�g���v�f��Ɣz��
};
typedef CArrayEx2 <SKittingYosoSagyogun, SKittingYosoSagyogun&> CKittingYosoSagyogunArray;

// �L�b�g�����i�f�[�^
struct SKittingBuhin {
	CString sBlockNo;			// �u���b�NNo
	CString sBuhinNo;			// ���iNo
	CString sBuhinName;			// ���i��
	CIntArrayEx aPC;			// PC
	BOOL bCheck;				// �L�b�g���Ώۃt���O
	CIntArrayEx aKittingYosoSagyogunId;	// �L�b�g���v�f��ƌQ�z��
};
typedef CArrayEx2 <SKittingBuhin, SKittingBuhin&> CKittingBuhinArray;

// ���i�L�b�g���f�[�^
struct SBuhinKitting {
	CString sBlockNo;			// �u���b�NNo
	CString sBuhinNo;			// ���iNo
	CString sBuhinName;			// ���i��
	BOOL bKittingFlag;			// �L�b�g���ݒ�t���O
};
typedef CArrayEx2 <SBuhinKitting, SBuhinKitting&> CBuhinKittingArray;

// �L�b�g���ΏۊO���i
struct SKittingTaishogai {
	CString sBuhinNo;				// ���iNo
};
typedef CArrayEx2 <SKittingTaishogai, SKittingTaishogai&> CKittingTaishogaiArray;

// �T�u���C���f�[�^
struct SSubline {
	CString sSublineName;				// �T�u���C����
	CIntArrayEx aRowArray;				// �s�ʒu�z��
	CIntArrayEx aSeisanKishuFlag;		// ���Y�@��t���O
	SSeisanJoken cSeisanJoken;			// ���Y�����f�[�^
	CStringArrayEx aSagyosha;			// ��Ǝ҃f�[�^�z��
	CYosoSagyogunArray aYosoSagyogun;	// �v�f��ƌQ�f�[�^�z��
	CYosoSagyogunArray aYosoSagyogunKit;	// �L�b�g���v�f��ƌQ�f�[�^�z��
	CKittingYosoSagyogunArray aKittingYosoSagyogun;	// �L�b�g���v�f��ƌQ�f�[�^�z��
	CKittingBuhinArray aKittingBuhin;	// �L�b�g�����i�f�[�^�z��
	CBuhinKittingArray aBuhinKitting;	// ���i�L�b�g���f�[�^�z��
	CStringArrayEx aCheckBuhin;			// �`�F�b�N�ςݕ��i
// Modify ... ( ADD )
	CStringArrayEx aCheckKotei;			// �`�F�b�N�ςݍH��
	CStringArrayEx aCheckYosoSagyogun;	// �`�F�b�N�ςݗv�f��ƌQ
// By Y.Itabashi (xxxxx) 2007.01.29
	CString sSagyoshaName;				// ��ƎҖ��̐擪�P����
	BOOL bSagyoshaWaritsuke;			// ��ƎҊ��t�ς݃t���O
	BOOL bSetKitting;					// �L�b�g���ݒ�ς݃t���O
	BOOL bManualEditFlag;				// �蓮�Ґ��Ώۃt���O

	SSubline() {
		bSagyoshaWaritsuke = FALSE;
		bSetKitting = FALSE;
		bManualEditFlag = FALSE;
	}
};
typedef CArrayEx2 <SSubline, SSubline&> CSublineArray;

// �@��f�[�^
struct SKishu {
	CString sKishuName;				// �@�햼
	CString sKatashiki;				// �^��
	int nSeisanDaisu;				// ���Y�䐔
	SDSTRSanshutsu cDSTRSanshutsu;	// DSTR�Z�o�f�[�^
	CIntArrayEx aHonryuSublineId;	// �{���T�u���C��ID

	// �R���X�g���N�^
	SKishu() {
		nSeisanDaisu = 0;
	}
};
typedef CArrayEx2 <SKishu, SKishu&> CKishuArray;

// �^���f�[�^
struct SKatashiki {
	CString sKatashiki;				// �^����
	CExcelKatashikiKiseiMatrix eKatashikiKiseiMatrix;	// �^���K���}�g���N�X�G�N�Z���f�[�^
	BOOL bEditFlag;
};
typedef CArrayEx2 <SKatashiki, SKatashiki&> CKatashikiArray;

// �H���E�@��ʋ��ʃO���[�v�f�[�^
struct SKyotsuKoteiGroup {
	CString sKoteiName;			// �H����
	struct SKishuGroupNo {
		int nKoteiGroupNo;		// �H���O���[�v�ԍ�
		CIntArrayEx aGroupNo;	// �@��ʃO���[�v�ԍ�
	};
	CArrayEx <SKishuGroupNo, SKishuGroupNo&> aKishuGroupNo;
};
typedef CArrayEx2 <SKyotsuKoteiGroup, SKyotsuKoteiGroup&> CKyotsuKoteiGroupArray;

// �H���ʎ����Ґ��f�[�^
struct SHenseiKotei {
	CString sKoteiNo;			// �H���ԍ�
	CString sKoteiName;			// �H����
	double fKajuHeikinTime;		// ���d���ώ���
	BOOL bJido;					// �����H���t���O
	BOOL bMovable;				// �ړ��\�t���O
	int nKishuId;				// �@��ID
	int nSagyoshaId;			// ��Ǝ�ID
	double fTime;				// ��Ǝ���
	CIntArrayEx aYosoSagyogunId;	// �v�f��ƌQID�z��
	CIntArrayEx aSagyojun;		// ��Ə��C���f�b�N�X�z��
	CIntArrayEx aGroupNo;		// �O���[�v�ԍ��z��
};
typedef CArrayEx <SHenseiKotei, SHenseiKotei&> CHenseiKoteiArray;

// �@��ʎ����Ґ���Ǝ��ԃf�[�^
struct SHenseiTime {
	double fKajuHeikinTime;			// ���d���ώ���
	CArray <double, double> aTime;	// ��Ǝ��Ԕz��
};

// �H�����t�f�[�^
struct SKoteiWaritsuke {
	int nSagyoshaId;			// ��Ǝ�ID
	int nGroupNo;				// �O���[�v�ԍ�
};
typedef CArrayEx2 <SKoteiWaritsuke, SKoteiWaritsuke&> CKoteiWaritsukeArray;

// �T�u���C��Undo�f�[�^
struct SUndoSubline {
	CStringArrayEx aSagyosha;				// ��Ǝ҃f�[�^�z��
	CYosoSagyogunArray aYosoSagyogun;		// �v�f��ƌQ�f�[�^�z��
	CYosoSagyogunArray aYosoSagyogunKit;	// �L�b�g���v�f��ƌQ�f�[�^�z��
	CBuhinKittingArray aBuhinKitting;	// ���i�L�b�g���f�[�^�z��
	CStringArrayEx aCheckBuhin;			// �`�F�b�N�ςݕ��i
// Modify ... ( ADD )
	CStringArrayEx aCheckKotei;			// �`�F�b�N�ςݍH��
	CStringArrayEx aCheckYosoSagyogun;	// �`�F�b�N�ςݗv�f��ƌQ
// By Y.Itabashi (xxxxx) 2007.01.29
};
typedef CArrayEx <SUndoSubline, SUndoSubline&> CSUndoSublineArray;

// �T�u���C��Undo�f�[�^
struct SUndoData {
	int nCounter;						// Undo�J�E���^
	CSUndoSublineArray aUndoSubline;	// �T�u���C��Undo�f�[�^
	CStringArrayEx aKittingSagyosha;	// �L�b�g����Ǝ҃f�[�^�z��
// --Add 2007/03/02 aizawa �s��D-147
	CLineKiseiArray m_aLineKisei;		// ���C���K���f�[�^�z��
// --Add

	// �R���X�g���N�^
	SUndoData() {
		nCounter = -1;
	}
};

// Undo�o�b�t�@
struct SUndoBuf {
	SUndoData aUndoData[MAX_UNDO];		// Undo�f�[�^�o�b�t�@
	int nIndex;							// Undo�f�[�^�o�b�t�@�C���f�b�N�X
	int nCounter;						// Undo�J�E���^

	// �R���X�g���N�^
	SUndoBuf() {
		nIndex = 0;
		nCounter = 1;
	}
};

// �H���K���`�F�b�N���
enum EKojunCheckType {
	eKojunCheckTypeNoMessage,			// �G���[���b�Z�[�W����
	eKojunCheckTypeKotei,				// �H���ړ��`�F�b�N
	eKojunCheckTypeYosoSagyogun			// �v�f��ƌQ�ړ��`�F�b�N
};

// �H���K���G���[�R�[�h
enum EKojunErr {
	eKojunErrNormal,					// �G���[����
	eKojunErrKatashikiJoryuNG,			// �㗬NG
	eKojunErrKatashikiKaryuNG			// ����NG
};

// �H���G���[���
struct SKojunErrInfo {
	EKojunErr nErrCode;					// �G���[�R�[�h
	CString sTargetKoteiName;			// �ړ����H����
	CString sTargetYosoSagyogunName;	// �ړ����v�f��ƌQ��
	CString sErrorKoteiName;			// �ړ���H����
	CString sErrorYosoSagyogunName;		// �ړ���v�f��ƌQ��
};
typedef CArrayEx <SKojunErrInfo, SKojunErrInfo&> CKojunErrInfoArray;

// �N���b�v�{�[�h�f�[�^
typedef char ClipBoardData[48][256];

// �v�f��ƃe���v���[�g�f�[�^
struct SYosoSagyoTemplate {
	CString sYosoSagyoNo;			// �v�f���NO.
	CString sSagyoNaiyo;			// ��Ɠ��e
	CString sEnzanShiki;			// ���Z��
	double fS1;						// S1
	double fS2;						// S2
	double fM;						// M
	double fN;						// N
	double fEnzangoSagyoJikan;		// ���Z��̍�Ǝ���
	int nHassexxxndo;				// �����p�x
	int nKurikaeshiKaisu;			// �J��Ԃ���
	double fDaiatariSagyoJikan;		// �䂠����̗v�f��Ɩ���Ǝ���
	CString sComment;				// ����
	CString sSagyoFlag;				// ����׸�
};
typedef CArrayEx2 <SYosoSagyoTemplate, SYosoSagyoTemplate&> CYosoSagyoTemplateArray;

// �f�[�^�Ǘ��N���X
class CDataManager
{
public:
	CDataManager();

	int m_nStatus;				// �X�e�[�^�X
	int m_nYear;				// �Ώ۔N
	int m_nMonth;				// �Ώی�
	BOOL m_bUpdate;				// �X�V�L���t���O
	BOOL m_bOpenLineKiseiMatrix;		// ���C���K���}�g���N�X�t�@�C���I�[�v�����t���O
	BOOL m_bOpenKatashikiKiseiMatrix;	// �^���K���}�g���N�X�t�@�C���I�[�v�����t���O

	// DataManager.cpp
	int GetSublineNum() { return m_aSubline.GetSize(); }
	int GetKishuNum() { return m_aKishu.GetSize(); }
	int GetKatashikiNum() { return m_aKatashiki.GetSize(); }
	int GetSagyoshaNum(int nSublineId) { return m_aSubline[nSublineId].aSagyosha.GetSize(); }
	int GetKittingSagyoshaNum() { return m_aKittingSagyosha.GetSize(); }
	int GetSeisanDaisuNum() { return m_aSeisanDaisu.GetSize(); }
	int GetYosoSagyoTemplateNum() { return m_aYosoSagyoTemplate.GetSize(); }
	SSubline *GetSublineData(LPCTSTR pSublineName) { return m_aSubline.LookupElement(pSublineName); }
	SSubline *GetSublineData(int nSublineId) { return &m_aSubline[nSublineId]; }
	SKishu *GetKishuData(int nKishuId) { return &m_aKishu[nKishuId]; }
	SKishu *GetKishuData(LPCTSTR pKishuName) { return m_aKishu.LookupElement(pKishuName); }
	SKatashiki *GetKatashikiData(int nKatashikiId) { return &m_aKatashiki[nKatashikiId]; }
	SSeisanDaisu *GetSeisanDaisuData(int nIndex) { return &m_aSeisanDaisu[nIndex]; }
	SSeisanDaisu *GetSeisanDaisuData(LPCTSTR pYexxxonth) { return m_aSeisanDaisu.LookupElement(pYexxxonth); }
	SYosoSagyogun *GetYosoSagyogun(int nSublineId, int nIndex) { return &m_aSubline[nSublineId].aYosoSagyogun.ElementAt(nIndex); }
	SYosoSagyogun *GetYosoSagyogun(int nSublineId, const CString &sKoteiName, const CString &sYosoSagyogunName);
	SYosoSagyoTemplate *GetYosoSagyoTemplateData(int nIndex) { return &m_aYosoSagyoTemplate[nIndex]; }
	SYosoSagyoTemplate *GetYosoSagyoTemplateData(LPCTSTR pYosoSagyoNo) { return m_aYosoSagyoTemplate.LookupElement(pYosoSagyoNo); }
	CString &GetSagyoshaName(int nSublineId, int nIndex) { return m_aSubline[nSublineId].aSagyosha[nIndex]; }
	CString &GetKittingSagyoshaName(int nIndex) { return m_aKittingSagyosha[nIndex]; }
	void SetUpdate() { m_bUpdate = TRUE; }
// Modify ... ( ADD )
	SDSTRSanshutsu *GetTotalDSTRSanshutsu()	{ return &m_cTotalDSTRSanshutsu;	}
	int GetTotalDSTRSetFlag()	{ return m_aTotalDSTRSanshutsu;	}
	void SetTotalDSTRSetFlag( int nFlag )	{ m_aTotalDSTRSanshutsu = nFlag;	}
// By Y.Itabashi (xxxxx) 2007.0214

	void FindSagyoWariate(int nSublineId, int nSagyoshaId, int nKishuId, CIntArrayEx &aYosoSagyogunId);
	void SetSeisanDaisu(SSeisanDaisu &cSeisanDaisu, BOOL bCopy);
// Modify ... ( CHANGE )
	int GetTotalSeisanDaisu( int nSublineId );
//	int GetTotalSeisanDaisu();
// By Y.Itabashi (xxxxx) 2007.02.11
	void GetSagyojun(int nSublineId, int nKishuId, CSagyojunArray &aSagyojun);
	void GetKittingSagyojun(int nSublineId, int nKishuId, CSagyojunArray &aSagyojun);
	void GetSagyojunSub(CYosoSagyogunArray &aYosoSagyogun, int nKishuId, CSagyojunArray &aSagyojun);
	void GetKotei(int nSublineId, const CString &sKoteiName, SKotei &cKotei);
	void GetKoteiArray(int nSublineId, int nKishuId, CKoteiArray &cKoteiArray);
	void GetKittingKoteiArray(int nSublineId, CKoteiArray &cKoteiArray);
	void OrderBySagyojun(int nSublineId, CSagyojunArray &aSagyojun);
	void OrderByKittingSagyojun(int nSublineId, CSagyojunArray &aSagyojun);
	void OrderBySagyojunSub(CYosoSagyogunArray &aYosoSagyogun, CSagyojunArray &aSagyojun);
// Modify ... ( CHANGE )
	BOOL CheckMokuhyoTactJoukagen(int nSublineId, int nSagyoshaId, int nKishuId, int nKishuType);
//	BOOL CheckMokuhyoTactJoukagen(int nSublineId, int nSagyoshaId, int nKishuId);
// By Y.Itabashi (xxxxx) 2007.02.12
	int GetNewGroupNo(int nSublineId);
	int GetSeisanDaisu(int nSublineId);
	void SetKyotsuKoteiFlag();
	void GetKyotsuKoteiGroup(int nSublineId, CKyotsuKoteiGroupArray &aKyotsuKoteiGroup);
	int GetMinTactSubline();
	double GetKittingTact();
	void SetSagyojun();
	void SetSagyojunSub(int nSublineId);
// Modify ... ( ADD )
	CString RemoveUnderbarDigit(const CString &sName);
// By Y.Itabashi (xxxxx) 2007.02.27

	// DataManagerEdit.cpp
	void KoteiBunkatsu(int nSublineId, const CString &sKoteiName, CStringArray &aYosoSagyogunName);
	void YosoSagyogunBunkatsu(int nSublineId, const CString &sKoteiName, const CString &sYosoSagyogunName, CIntArrayEx &aYosoSagyo);
	void InsertSagyosha(int nSublineId, int nSagyoshaId);
	void DeleteSagyosha(int nSublineId, int nSagyoshaId);
	BOOL CheckSagyoshaSagyo(int nSublineId, int nSagyoshaId);
	void CopyClipboard(int nSublineId, CIntArrayEx &aYosoSagyogunId, LPCTSTR pTsuikaFlag);
	BOOL PasteClipboard(int nSublineId, int nYosoSagyogunId, int nKishuId, int nSagyoshaId);
	void GetYosoSagyogun(int nSublineId, CIntArrayEx &aYosoSagyogunId, CYosoSagyogunArray &aYosoSagyogun);
	void DeleteYosoSagyogun(int nSublineId, CIntArrayEx &aYosoSagyogunId, LPCTSTR pDeleteFlag);
	void AddYosoSagyogun(int nSublineId, CYosoSagyogunArray &aYosoSagyogun);
	void ClearNewMoveFlag();
	int AddYosoSagyoHyo();
// --Add 2007/02/26 aizawa �s��D-187
	void RenumberSagyoNo();
// --Add

	// DataManagerKojunKisei.cpp
// Modify ... ( CHANGE )
	BOOL CheckKojunKisei(int nSublineIdFrom, int nSublineId, EKojunCheckType eKojunCheckType);
//	BOOL CheckKojunKisei(int nSublineId, EKojunCheckType eKojunCheckType);
// By Y.Itabashi (xxxxx) 2007.02.07
	BOOL CheckKojunKisei(int nSublineId, CIntArrayEx &aYosoSagyogunId, int nKishuId, CSagyojunArray &aSagyojun);
	void OpenLineKiseiMatrix();
	void OpenKatashikiKiseiMatrix();
	void OpenExcelNotify(UINT nParam);
	void CloseExcelNotify(UINT nParam);
	void ErrorExcelNotify(UINT nParam);
// Modify ... ( ADD )
// Modify ... ( CHANGE )
	// �H��/�v�f��ƌQ�̈ړ����s�O�̈ʒu��ݒ�
	void SetMoveBeforeSagyojun(int nSublineId, int nSublineToid, CString sKoteiName, CString sYosoSagyogunName, CString sCheckKoteiName, CString sCheckYosoSagyogunName, int nYosoSagyogunId, CIntArrayEx &aYosoSagyogunId, int nKishuId, EKojunCheckType eKojunCheckType);
	void SetMoveBeforeSagyojun(int nSublineId, CString sKoteiName, CString sYosoSagyogunName,
								int nKishuId, int nSetSagyojun, EKojunCheckType eKojunCheckType);
// By Y.Itabashi (xxxxx) 2007.02.28
	// �H��/�v�f��ƌQ�̈ړ����s�O�̈ʒu���擾
	int GetMoveBeforeSagyojun(int nSublineId, CString sKoteiName, CString sYosoSagyogunName, int nKishuId);
	// �H��/�v�f��ƌQ�̈ړ����s�O�̈ʒu��������
	void InitMoveBeforeSagyojun();
// By Y.Itabashi (xxxxx) 2007.02.06
// --Add 2007/03/02 aizawa �s��D-147
	void UpdateLineKiseiSubline(int nSublineIdFrom, int nSublineIdTo);
	void ChangeLineKiseiSubline(int nSublineIdFrom, int nSublineIdTo, const CString &sKoteiNo, const CString &sKoteiName, const CString &sYosoSagyogunName);
// --Add

	// DataManagerKitting.cpp
	void GetKittingData();
	void DeleteKittingSagyo(int nSublineId);
	void AddKittingTemplate(int nSublineId);
	void CancelKitting(int nSublineId);
	void CancelKitting(int nSublineId, LPCTSTR pKoteiName);
	void SetBuhinKitting();
	void ClearKittingCancelFlag();

	// DataManagerJidoHensei.cpp
	void JidoHensei(CIntArrayEx &aSublineId, int nHenseiMode, int nTactKeisan, int nKijunKishuId);
	void JidoHensei1Kitting(CIntArrayEx &aSublineId);

	// DataManagerFile.cpp
	BOOL ReadFile(LPCTSTR pFolderName);
	BOOL ReadYosoSagyoTemplate();
	BOOL SaveFile();
	BOOL SaveAsFile(LPCTSTR pFolderName);
	BOOL SaveKittingTaishoBuhin(LPCTSTR sPathName);
	void ConvertHankaku(CString &sName);
	// Modify ... ( ADD )
	BOOL ExportGraphTemplate(LPCTSTR pPathName);
	CString GetNewExportGraphTemplateFileName() {return m_eExportGraphTemplate.GetNewFileName2();};
	// By K.Kimura (xxxxx) 2007.02.07
	// Modify ... ( ADD )
	void SetExportCondition(bool* m_aCheckedSubline, bool* m_aCheckedKishu, bool m_bCheckedWaritsuke, bool m_bCheckedZenkishu, bool m_bCheckedKajuHeikin);
	// By K.Kimura (xxxxx) 2007.02.16
// Modify ... ( ADD )
	void SaveKojunKiseiError();
	void AddKojunKiseiError(SKatashikiError cKatashikiError, SLineError cLineError);
// By Y.Itabashi (xxxxx) 2007.02.19

	// DataManagerUndo.cpp
	void ResetUndoBuf();
	void SaveUndoBuf();
	BOOL CheckUndo();
	BOOL CheckRedo();
	void Undo(BOOL bRedo);
	void Redo();
	void RestoreUndoData();

protected:
	CExcelSeisanDaisu m_eSeisanDaisu;					// ���Y�䐔���G�N�Z���f�[�^
	CExcelKinmuTaisei m_eKinmuTaisei;					// �Ζ��̐����G�N�Z���f�[�^
	CExcelBuhinhyoLink m_eBuhinhyoLink;					// ���i�\���H�������N���G�N�Z���f�[�^
	CExcelDSTRSanshutsu m_eDSTRSanshutsu;				// DSTR�Z�o�p���G�N�Z���f�[�^
	CExcelYosoSagyoHyo m_eYosoSagyoHyo;					// �v�f��ƕ\�G�N�Z���f�[�^
	CExcelLineKiseiMatrix m_eLineKiseiMatrix;			// ���C���K���}�g���N�X�G�N�Z���f�[�^
	CExcelKittingTemplate m_eKittingTemplate;			// �L�b�g���e���v���[�g�G�N�Z���f�[�^
	CExcelSeisanJoken m_eSeisanJoken;					// ���Y�����ݒ�G�N�Z���f�[�^
	CExcelBuhinKitting m_eBuhinKitting;					// ���i�L�b�g�����G�N�Z���f�[�^
	CExcelKyotsuKotei m_eKyotsuKotei;					// ���ʍH�����G�N�Z���f�[�^
	CExcelKittingTaishogai m_eKittingTaishogai;			// �L�b�g�ΏۊO���i���X�g�G�N�Z���f�[�^
// Modify ... ( ADD )
	CExcelKatashikiKiseiError m_eKatashikiError;		// �^���K���ᔽ�G�N�Z���f�[�^
	CExcelLineKiseiError m_eLineError;					// ���C���K���ᔽ�G�N�Z���f�[�^
// By Y.Itabashi (xxxxx) 2007.02.14
	CSublineArray m_aSubline;							// �T�u���C���f�[�^�z��
	CKishuArray m_aKishu;								// �@��f�[�^�z��
	CSeisanDaisuArray m_aSeisanDaisu;					// ���Y�䐔�z��
	CBuhinhyoLinkArray m_aBuhinhyoLink;					// ���i�\���H�������N�f�[�^�z��
	CKittingTaishogaiArray m_aKittingTaishogai;			// �L�b�g���ΏۊO���i�f�[�^�z��
	CLineKiseiArray m_aLineKisei;						// ���C���K���f�[�^�z��
	CKatashikiKiseiArray m_aKatashikiKisei;				// �^���K���}�g���N�X�f�[�^�z��
	CKatashikiArray m_aKatashiki;						// �^���f�[�^�z��
	CStringArrayEx m_aKittingSagyosha;					// �L�b�g����Ǝ҃f�[�^�z��
	CYosoSagyoTemplateArray m_aYosoSagyoTemplate;		// �v�f��ƃe���v���[�g�f�[�^�z��
	SUndoBuf m_aUndoBuf;								// Undo�o�b�t�@
	int m_nCopyCount;									// �R�s�[�J�E���^
// Modify ... ( ADD )
	CArrayEx2 <int, CString&> aBeforeMoveSagyojun;		// �H��/�v�f��ƌQ�ړ����̌��̈ʒu
// By Y.Itabashi (xxxxx) 2007.02.06
// Modfiy ... ( ADD )
	CExcelExportGraphTemplate m_eExportGraphTemplate;	// ���t��ƃO���t�G�N�Z���f�[�^
// By K.Kimura (xxxxx) 2007.02.06
// Modify ... ( ADD )
	SDSTRSanshutsu m_cTotalDSTRSanshutsu;				// �S�@��DSTR�Z�o�f�[�^
	int m_aTotalDSTRSanshutsu;							// �S�@��DSTR�Z�o�f�[�^�Z�b�g�t���O
	CKatashikiErrorArray m_aKatashikiError;				// �^���K���ᔽ�f�[�^
	CLineErrorArray m_aLineError;							// ���C���K���ᔽ�f�[�^
// By Y.Itabashi (xxxxx) 2007.02.14
// Modify ... ( ADD )
	CString m_sKittingKoteiKigo;						// �L�b�g����Ƃ̍H���L��
// By Y.Itabasshi (xxxxx) 2007.03.07

	// DataManager.cpp
	void SortSagyojun(CSagyojunArray &aSagyojun);
	static int CompareSagyojun(const void *arg1, const void *arg2);
	CString NewKoteiName(int nSublineId, const CString &sName, int nPostfix);
	CString NewYosoSagyogunName(int nSublineId, const CString &sName, int nPostfix);
	double GetSagyoJikan(int nSublineId, int nSagyoshaId, int nKishuId);
	void RenumberSagyoshaId(int nSublineId);
	void GetSagyoshaJun(int nSublineId, CIntArrayEx &aSagyoshaJun);
	void UpdateSagyoshaId(int nSublineId, CIntArrayEx &aSagyoshaJun);
	void RenameSagyoshaName(int nSublineId);
	void SetDefaultGroupNo(int nSublineId);
// Modify ... ( DELETE )
//	CString RemoveUnderbarDigit(const CString &sName);
// By Y.Itabashi (xxxxx) 2007.02.27

	// DataManagerEdit.cpp
	void AdjustSagyoshaId(int nSublineId, int nSagyoshaId, int nAdjust);

	// DataManagerKojunKisei.cpp
	BOOL LineKiseiCheck(int nKishuId, int nSublineIdFrom, int nSublineIdTo, int nYosoSagyogunId1, int nYosoSagyogunId2, CSagyojunArray &aSagyojun, BOOL &bNoCheck);
	BOOL LineKiseiError(EKojunCheckType eKojunCheckType);
// Modify ... ( CHANGE )
	BOOL KatashikiKiseiCheck(int nKishuId, int nSublineIdFrom, int nSublineIdTo, int nYosoSagyogunId, CSagyojunArray &aSagyojun, CKojunErrInfoArray &aKojunErrInfo, BOOL bAllCheck, EKojunCheckType eKojunCheckType, BOOL &bNoCheck);
//	BOOL KatashikiKiseiCheck(int nKishuId, int nSublineIdFrom, int nSublineIdTo, int nYosoSagyogunId, CSagyojunArray &aSagyojun, CKojunErrInfoArray &aKojunErrInfo, BOOL bAllCheck);
// By Y.Itabashi (xxxxx) 2007.02.21
	BOOL KatashikiKiseiError(EKojunCheckType eKojunCheckType, CKojunErrInfoArray &aKojunErrInfo);
	SLineKisei *FindLineKisei(int nSublineId, const CString &sKoteiNo, const CString &sKoteiName, const CString &sYosoSagyogunName);
	SKatashikiKisei *FindKatashikiKisei(const CString &sKishuName, int nSublineId, const CString &sKoteiNo, const CString &sKoteiName, const CString &sYosoSagyogunName);
	SKatashikiKiseiCol *FindKatashikiKiseiCol(SKatashikiKisei *pKatashikiKisei, const CString &sKishuName, int nSublineId, const CString &sKoteiNo, const CString &sKoteiName, const CString &sYosoSagyogunName);
	static UINT OpenExcelThread(LPVOID pParam);

	// DataManagerKitting.cpp
	void GetKittingDataSub(int nSublineId);
	BOOL CheckKittingBuhinNo(const SBuhinhyoLink &cBuhinhyoLink);
	BOOL CheckDefaultSakujoSagyo(const CString &sYosoSagyoNo);
	void SetKittingDeleteFlag();
	void SetKittingDeleteFlagSub(int nSublineId);

	// DataManagerJidoHensei.cpp
// --Change 2007/03/01 aizawa �s��D-198
//	void JidoHenseiSub(int nSublineId, int nHenseiMode, int nTactKeisan, int nKijunKishuId);
	void JidoHenseiSub(int nSublineId, int nHenseiMode, int nTactKeisan, int nKijunKishuId, int nProcessSublixxxnt, int nProcessSublineNum);
// --Change
	void JidoHensei1(int nSublineId, int nTactKeisan, int nKijunKishuId, int &nMaxSagyoshaId);
	void JidoHensei1Sub(int nSublineId, int &nMaxSagyoshaId, int nMaxSagyoshaId2, int nKishuId, CKoteiArray &aKotei, CKoteiWaritsukeArray &cKoteiWaritsukeArray, BOOL bReverse, BOOL bNewWaritsuke, BOOL bKijunKishu, int nTactKeisan);
	BOOL JidoHensei1Sub2(int nSublineId);
	void JidoHensei2(int nSublineId, int nTactKeisan);
	static int CompareKishuJun(const void *arg1, const void *arg2);
	void GetHenseiKotei(int nSublineId, CSagyojunArray &aSagyojun, CHenseiKoteiArray &aHenseiKotei);
	void GetHenseiTime(int nSublineId, CHenseiKoteiArray &aHenseiKotei, int nSagyoshaId, SHenseiTime &cHenseiTime);
	void DispProgressBar(int nKishuId, LPCTSTR pProcess, int nCount);
// --Add 2007/03/01 aizawa �s��D-198
	void DispProgressBarSubline(int nSublineId, LPCTSTR pProcess, int nProcessCnt, int ProcessNum);
// --Add

	// DataManagerFile.cpp
	BOOL ReadFileSub();
	BOOL CheckProjectFile();
	BOOL CopyFromServer(LPCTSTR pFileName);
	BOOL CopyFromServer(LPCTSTR pServerFolder, LPCTSTR pFileName);
	BOOL ReadProjectFile();
	void DispReadProcess(CExcelFile &cExcelFile);
	BOOL ReadYosoSagyoHyo();
	BOOL ExtractSubline();
	BOOL ExtractKishuName();
	BOOL ExtractYosoSagyogun();
	BOOL ExtractYosoSagyogunSub(int nSublineId, int nStartRowIndex = 0);
	BOOL CheckYosoSagyoHyo();
	void CheckCopyCount(const CString &sName);
	BOOL ReadSeisanDaisu();
	BOOL ReadKinmuTaisei();
	BOOL ReadSeisanJoken();
	BOOL ReadDSTRSanshutsu();
	BOOL ReadBuhinhyoLink();
	BOOL ReadLineKiseiMatrix();
	BOOL GetLineKiseiFlag(int nRow, int nCol);
	BOOL ReadKatashikiKiseiMatrix();
	BOOL ReadKatashikiKiseiMatrixSub1(CExcelKatashikiKiseiMatrix &cExcelKatashikiKiseiMatrix);
// Modify ( CHANGE )
	BOOL ReadKatashikiKiseiMatrixSub2(CExcelKatashikiKiseiMatrix &cExcelKatashikiKiseiMatrix);
	BOOL ReadKatashikiKiseiMatrixSub3(CExcelKatashikiKiseiMatrix &cExcelKatashikiKiseiMatrix, int nRow);
//	void ReadKatashikiKiseiMatrixSub2(CExcelKatashikiKiseiMatrix &cExcelKatashikiKiseiMatrix);
//	void ReadKatashikiKiseiMatrixSub3(CExcelKatashikiKiseiMatrix &cExcelKatashikiKiseiMatrix, int nRow);
// By Y.Itabashi (xxxxx) 2007.02.20
	BOOL CheckKatashikiKiseiMatrix();
	BOOL ReadKittingTemplate();
	BOOL ReadBuhinKitting();
	BOOL ReadKyotsuKotei();
	BOOL ReadKittingTaishogai();
// Modify ... ( ADD )
	BOOL ReadKatashikiError();
	BOOL ReadLineError();
// By Y.Itabashi (xxxxx) 2007.02.14
	BOOL SaveAsFileSub(LPCTSTR pFolderName);
	BOOL SaveFileSub();
	BOOL SaveProjectFile(LPCTSTR pFolderName, LPCTSTR pErrCode);
	void DispSaveProcess(CExcelFile &cExcelFile);
	BOOL MakeFolder(LPCTSTR pFolderName);
	BOOL CopyProjectFile(LPCTSTR pFolderName);
	BOOL SaveSeisanDaisu(LPCTSTR pFolderName, LPCTSTR pErrCode);
	BOOL SaveKinmuTaisei(LPCTSTR pFolderName, LPCTSTR pErrCode);
	BOOL SaveDSTRSanshutsu(LPCTSTR pFolderName, LPCTSTR pErrCode);
	BOOL SaveYosoSagyoHyo(LPCTSTR pFolderName, LPCTSTR pErrCode);
// Modify ... ( ADD )
	BOOL SaveKatashikiError(LPCTSTR pFolderName, LPCTSTR pErrCode);
	BOOL SaveLineError(LPCTSTR pFolderName, LPCTSTR pErrCode);
// By Y.Itabashi (xxxxx) 2007.02.14
	void UpdateYosoSagyoHyo();
	void UpdateYosoSagyoHyoSub(CString &sSublineName, CYosoSagyogunArray &aYosoSagyogun, CStringArray &aSagyosha, CExcelData &cTempArray, int &nRow);
	BOOL SaveSeisanJoken(LPCTSTR pFolderName, LPCTSTR pErrCode);
	BOOL SaveBuhinKitting(LPCTSTR pFolderName, LPCTSTR pErrCode);
	BOOL SaveKyotsuKotei(LPCTSTR pFolderName, LPCTSTR pErrCode);
// --Add 2007/03/02 aizawa �s��D-147
	BOOL SaveLineKiseiMatrix(LPCTSTR pFolderName, LPCTSTR pErrCode);
// --Add
	// Add 
	BOOL ExportGraphTemplateSub1(CExcelExportGraphTemplate &cExcelGraphTemplate, LPCTSTR pPathName);
	BOOL ExportGraphTemplateSub2(CExcelExportGraphTemplate &cExcelGraphTemplate, LPCTSTR pPathName);
    // By K.Fujii (xxxxx) 2007.02.16
// Modify ... ( ADD )
	BOOL ExportGraphTemplateWaritsuke1(CExcelExportGraphTemplate &cExcelGraphTemplate, LPCTSTR pPathName);
	BOOL ExportGraphTemplateWaritsuke2(CExcelExportGraphTemplate &cExcelGraphTemplate, LPCTSTR pPathName);
// By Y.Itabashi (xxxxx) 2007.02.19

	// DataManagerUndo.cpp
	void SaveUndoData();
};
