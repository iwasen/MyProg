/************************************************************************
*									*
*		�`�b�d�r�@�r�^�f�@�b�o�t�v���O����			*
*									*
*		����		: �X�^�[�g�A�b�v����			*
*		�t�@�C����	: startup.s				*
*									*
************************************************************************/
*
	CHIP	68020
	SECT	startup
*
	XDEF	_main
*
	XREF	_power_on
*
_main
	MOVEA.L	#$FF080000,A7
	ORI.W	#$700,SR		: ���荞�݃}�X�N
*
	MOVEQ	#$8,D0			: �L���b�V���C�l�[�u��
	MOVEC.L	D0,CACR
	MOVEQ	#$1,D0
	MOVEC.L	D0,CACR
*
	MOVEA.L	#$FF080000,A0		: �x�N�^�x�[�X�A�h���X�Z�b�g
	MOVEC.L	A0,VBR
*
	JMP	_power_on		: �p���[�I������
*
	END	_main
