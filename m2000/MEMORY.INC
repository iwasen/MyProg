		.xlist
;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;
;
;	memory address define
;
;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;
;
M_JMPVCT	equ	100h		; utility jump vector
M_RAMDSk	equ	130h		; RAM disk information
M_CDATA		equ	2b0h		; common data area
M_PARAM		equ	2c0h		; parameter area
M_WORK		equ	2d0h		; common work area
M_ORDER		equ	480h		; order data buffer
M_RBUFF		equ	1100h		; receive data buffer
M_CBUFF		equ	5300h		; common buffer
M_RPROG		equ	5500h		; resident program area
M_OVLY		equ	8000h		; overlay area
M_DUMP		equ	0b000h		; receive data dump area
;
;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;
;
;	RAM disk information
;
;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;
;
R_PASSWDF	equ	M_RAMDSK+0
R_PASSWD	equ	M_RAMDSK+1
R_TANTO		equ	M_RAMDSK+7
R_CHOKUSO	equ	M_RAMDSK+8
R_CLIST		equ	M_RAMDSK+9
R_LNSHR		equ	M_RAMDSK+10
R_LNTYP		equ	M_RAMDSK+11
R_KOURI		equ	M_RAMDSK+12
R_ENQ		equ	M_RAMDSK+20
R_TID		equ	M_RAMDSK+28
R_VANTEL1	equ	M_RAMDSK+41
R_VANTEL2	equ	M_RAMDSK+54
R_TORxxxKI	equ	M_RAMDSK+67
;
;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;
;
;	common data area
;
;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;
;
C_TANTO		equ	M_CDATA+0	; tantousha mei
C_DNO		equ	M_CDATA+5	; denpyou No.
C_SPSAVE	equ	M_CDATA+7	; stack pointer save area
C_SPASS		equ	M_CDATA+9	; super password

		.list
