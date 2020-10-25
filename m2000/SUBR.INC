		.xlist

	kmsg	equ	103h		; kanji output to console.
	k16lmsg	equ	106h		; kanji output to CLPT:
	k8lmsg	equ	109h		; ascii output to CLPT: as half of kanji.
	overlay	equ	10ch		; exec overlay.
	klist	equ	10fh		; kanji output to CLPT
	xconst	equ	112h		; console status
	xconin	equ	115h		; console inout
	kbin	equ	118h		; key / barcode inout
	shftinit equ	11bh		; shift initialize

		.list
