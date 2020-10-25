#include <stdio.h>
#include <graph.h>
#include <stdlib.h>
#include <string.h>
#include <conio.h>
#include <ctype.h>
#include <dos.h>
#include <math.h>
#include "sg_cpu.h"
#include "test.h"

extern	void	dsp_TEST1(short);
extern	void	dsp_TEST2(short);
extern	void	dsp_TEST3(short);
extern	void	dsp_TEST4(short);
extern	void	dsp_TEST7A(short);
extern	void	dsp_TEST7B(short);
extern	void	dsp_TEST51(short);
extern	void	dsp_TEST52(short);
extern	void	dsp_TEST53(short);
extern	void	dsp_TEST54(short);
extern	void	dsp_TEST55(short);
extern	void	dsp_TEST56(short);
extern	void	dsp_TEST57(short);
extern	void	dsp_TEST58(short);
extern	void	dsp_TEST59(short);
extern	void	dsp_TEST510(short);
extern	void	dsp_TEST511(short);
extern	void	dsp_TEST512(short);
extern	void	dsp_TEST513(short);
extern	void	dsp_TEST514(short);
extern	void	dsp_TEST515(short);
extern	void	dsp_TEST516(short);
extern	void	dsp_TEST517(short);
extern	void	dsp_TEST518(short);
extern	void	dsp_TEST519(short);
extern	void	dsp_TEST520(short);
extern	void	dsp_TEST521(short);
extern	void	dsp_TEST522(short);
extern	void	dsp_TEST523(short);
extern	void	dsp_TEST524(short);
extern	void	dsp_TEST525(short);
extern	void	dsp_TEST526(short);
extern	void	dsp_TEST527(short);
extern	void	dsp_TEST528(short);
extern	void	dsp_TEST529(short);
extern	void	dsp_M001(short);
extern	void	dsp_M002(short);
extern	void	dsp_M003A0(short);
extern	void	dsp_M003A1(short);
extern	void	dsp_M003B0(short);
extern	void	dsp_M003B1(short);
extern	void	dsp_M004A0(short);
extern	void	dsp_M004A1(short);
extern	void	dsp_M004B0(short);
extern	void	dsp_M004B1(short);
extern	void	dsp_M0050(short);
extern	void	dsp_M0051(short);
extern	void	dsp_M006(short);
extern	void	dsp_M006B(short);
extern	void	dsp_M007(short);
extern	void	dsp_M008A(short);
extern	void	dsp_M008B(short);
extern	void	dsp_M008C(short);
extern	void	dsp_M0090(short);
extern	void	dsp_M0091(short);
extern	void	dsp_M010(short);
extern	void	dsp_M011(short);
extern	void	dsp_M012(short);
extern	void	dsp_M0130(short);
extern	void	dsp_M0131(short);
extern	void	dsp_M014A(short);
extern	void	dsp_M014B(short);
extern	void	dsp_M014C(short);
extern	void	dsp_M015(short);
extern	void	dsp_M016(short);
extern	void	dsp_M017A(short);
extern	void	dsp_M017B(short);
extern	void	dsp_M017C(short);
extern	void	dsp_M017D(short);
extern	void	dsp_MB18(short);
extern	void	dsp_M018(short);
extern	void	dsp_M019(short);
extern	void	dsp_M020(short);
extern	void	dsp_M0210(short);
extern	void	dsp_M0211(short);
extern	void	dsp_M022(short);
extern	void	dsp_M0230(short);
extern	void	dsp_M0231(short);
extern	void	dsp_M0240(short);
extern	void	dsp_M0241(short);
extern	void	dsp_M031(short);
extern	void	dsp_M032(short);
extern	void	dsp_M033(short);
extern	void	dsp_M034(short);
extern	void	dsp_M035(short);
extern	void	dsp_M036(short);
extern	void	dsp_M041A(short);
extern	void	dsp_M041B(short);
extern	void	dsp_M0420(short);
extern	void	dsp_M0421(short);
extern	void	dsp_M043(short);
extern	void	dsp_M044(short);
extern	void	dsp_M051A(short);
extern	void	dsp_M051B(short);
extern	void	dsp_M052A(short);
extern	void	dsp_M052B(short);
extern	void	dsp_M052C(short);
extern	void	dsp_M052D(short);
extern	void	dsp_M052E(short);
extern	void	dsp_M052F(short);
extern	void	dsp_M052G(short);
extern	void	dsp_M053(short);
extern	void	dsp_M054(short);
extern	void	dsp_M055(short);
extern	void	dsp_M056A0(short);
extern	void	dsp_M056A1(short);
extern	void	dsp_M056A2(short);
extern	void	dsp_M056A3(short);
extern	void	dsp_M056A4(short);
extern	void	dsp_M056A5(short);
extern	void	dsp_M056B0(short);
extern	void	dsp_M056B1(short);
extern	void	dsp_M057A0(short);
extern	void	dsp_M057A1(short);
extern	void	dsp_M057B(short);
extern	void	dsp_M058A0(short);
extern	void	dsp_M058A1(short);
extern	void	dsp_M058A2(short);
extern	void	dsp_M058A3(short);
extern	void	dsp_M058A4(short);
extern	void	dsp_M058A5(short);
extern	void	dsp_M058B0(short);
extern	void	dsp_M058B1(short);
extern	void	dsp_M059A0(short);
extern	void	dsp_M059A1(short);
extern	void	dsp_M059B0(short);
extern	void	dsp_M059B1(short);
extern	void	dsp_M059B2(short);
extern	void	dsp_M059B3(short);
extern	void	dsp_M060(short);
extern	void	dsp_M0610(short);
extern	void	dsp_M0611(short);
extern	void	dsp_M062A(short);
extern	void	dsp_M062B(short);
extern	void	dsp_M062C(short);
extern	void	dsp_M062D(short);
extern	void	dsp_M062E(short);
extern	void	dsp_M065A(short);
extern	void	dsp_M065B(short);
extern	void	dsp_M0660(short);
extern	void	dsp_M0661(short);
extern	void	dsp_M067A(short);
extern	void	dsp_M067B0(short);
extern	void	dsp_M067B1(short);
extern	void	dsp_M0680(short);
extern	void	dsp_M0681(short);
extern	void	dsp_M0690(short);
extern	void	dsp_M0691(short);
extern	void	dsp_M070A(short);
extern	void	dsp_M070B(short);
extern	void	dsp_M071A0(short);
extern	void	dsp_M071A1(short);
extern	void	dsp_M071A2(short);
extern	void	dsp_M071A3(short);
extern	void	dsp_M071A4(short);
extern	void	dsp_M071A5(short);
extern	void	dsp_M071B0(short);
extern	void	dsp_M071B1(short);
extern	void	dsp_M072A0(short);
extern	void	dsp_M072A1(short);
extern	void	dsp_M072B(short);
extern	void	dsp_M072C(short);
extern	void	dsp_M072D0(short);
extern	void	dsp_M072D1(short);
extern	void	dsp_M073A0(short);
extern	void	dsp_M073A1(short);
extern	void	dsp_M073A2(short);
extern	void	dsp_M073A3(short);
extern	void	dsp_M073A4(short);
extern	void	dsp_M073A5(short);
extern	void	dsp_M073B0(short);
extern	void	dsp_M073B1(short);
extern	void	dsp_M074A0(short);
extern	void	dsp_M074A1(short);
extern	void	dsp_M074B(short);
extern	void	dsp_M074C(short);
extern	void	dsp_M074D0(short);
extern	void	dsp_M074D1(short);
extern	void	dsp_M075A(short);
extern	void	dsp_M075B(short);
extern	void	dsp_M0760(short);
extern	void	dsp_M0761(short);
extern	void	dsp_M077A(short);
extern	void	dsp_M077B(short);
extern	void	dsp_M077C(short);
extern	void	dsp_M077D(short);
extern	void	dsp_M077E(short);
extern	void	dsp_window1(short);
extern	void	dsp_window2(short);
static	void	dsp_dummy(short);

static	void	(*dsp_func[])(short) = {
	dsp_TEST1,		/* 0 */
	dsp_TEST2,		/* 1 */
	dsp_TEST3,		/* 2 */
	dsp_TEST4,		/* 3 */
	dsp_TEST7A,		/* 4 */
	dsp_TEST7B,		/* 5 */
	dsp_dummy,		/* 6 */
	dsp_dummy,		/* 7 */
	dsp_dummy,		/* 8 */
	dsp_dummy,		/* 9 */
	dsp_dummy,		/* 10 */
	dsp_dummy,		/* 11 */
	dsp_dummy,		/* 12 */
	dsp_dummy,		/* 13 */
	dsp_dummy,		/* 14 */
	dsp_dummy,		/* 15 */
	dsp_TEST51,		/* 16 */
	dsp_TEST52,		/* 17 */
	dsp_TEST53,		/* 18 */
	dsp_TEST54,		/* 19 */
	dsp_TEST55,		/* 20 */
	dsp_TEST56,		/* 21 */
	dsp_TEST57,		/* 22 */
	dsp_TEST58,		/* 23 */
	dsp_TEST59,		/* 24 */
	dsp_TEST510,		/* 25 */
	dsp_TEST511,		/* 26 */
	dsp_TEST512,		/* 27 */
	dsp_TEST513,		/* 28 */
	dsp_TEST514,		/* 29 */
	dsp_TEST515,		/* 30 */
	dsp_TEST516,		/* 31 */
	dsp_TEST517,		/* 32 */
	dsp_TEST518,		/* 33 */
	dsp_TEST519,		/* 34 */
	dsp_TEST520,		/* 35 */
	dsp_TEST521,		/* 36 */
	dsp_TEST522,		/* 37 */
	dsp_TEST523,		/* 38 */
	dsp_TEST524,		/* 39 */
	dsp_TEST525,		/* 40 */
	dsp_TEST526,		/* 41 */
	dsp_TEST527,		/* 42 */
	dsp_TEST528,		/* 43 */
	dsp_TEST529,		/* 44 */
	dsp_dummy,		/* 45 */
	dsp_dummy,		/* 46 */
	dsp_dummy,		/* 47 */
	dsp_window1,		/* 48 */
	dsp_window1,		/* 49 */
	dsp_window1,		/* 50 */
	dsp_window1,		/* 51 */
	dsp_window1,		/* 52 */
	dsp_window1,		/* 53 */
	dsp_window1,		/* 54 */
	dsp_window1,		/* 55 */
	dsp_window1,		/* 56 */
	dsp_window1,		/* 57 */
	dsp_window1,		/* 58 */
	dsp_window1,		/* 59 */
	dsp_window1,		/* 60 */
	dsp_window1,		/* 61 */
	dsp_window1,		/* 62 */
	dsp_window1,		/* 63 */
	dsp_window1,		/* 64 */
	dsp_window1,		/* 65 */
	dsp_window1,		/* 66 */
	dsp_window1,		/* 67 */
	dsp_window1,		/* 68 */
	dsp_window1,		/* 69 */
	dsp_window1,		/* 70 */
	dsp_window1,		/* 71 */
	dsp_window1,		/* 72 */
	dsp_window1,		/* 73 */
	dsp_window1,		/* 74 */
	dsp_window1,		/* 75 */
	dsp_window1,		/* 76 */
	dsp_window1,		/* 77 */
	dsp_window1,		/* 78 */
	dsp_window1,		/* 79 */
	dsp_window1,		/* 80 */
	dsp_window1,		/* 81 */
	dsp_window1,		/* 82 */
	dsp_window1,		/* 83 */
	dsp_window1,		/* 84 */
	dsp_window1,		/* 85 */
	dsp_window1,		/* 86 */
	dsp_window1,		/* 87 */
	dsp_window1,		/* 88 */
	dsp_window1,		/* 89 */
	dsp_window1,		/* 90 */
	dsp_window1,		/* 91 */
	dsp_window1,		/* 92 */
	dsp_window1,		/* 93 */
	dsp_window1,		/* 94 */
	dsp_window1,		/* 95 */
	dsp_window1,		/* 96 */
	dsp_window1,		/* 97 */
	dsp_window1,		/* 98 */
	dsp_window1,		/* 99 */
	dsp_window1,		/* 100 */
	dsp_window1,		/* 101 */
	dsp_window1,		/* 102 */
	dsp_window1,		/* 103 */
	dsp_window1,		/* 104 */
	dsp_window1,		/* 105 */
	dsp_window1,		/* 106 */
	dsp_window1,		/* 107 */
	dsp_window1,		/* 108 */
	dsp_window1,		/* 109 */
	dsp_window1,		/* 110 */
	dsp_window1,		/* 111 */
	dsp_window1,		/* 112 */
	dsp_window1,		/* 113 */
	dsp_window1,		/* 114 */
	dsp_window1,		/* 115 */
	dsp_window1,		/* 116 */
	dsp_window1,		/* 117 */
	dsp_window1,		/* 118 */
	dsp_window1,		/* 119 */
	dsp_window1,		/* 120 */
	dsp_window1,		/* 121 */
	dsp_window1,		/* 122 */
	dsp_window1,		/* 123 */
	dsp_dummy,		/* 124 */
	dsp_dummy,		/* 125 */
	dsp_dummy,		/* 126 */
	dsp_dummy,		/* 127 */
	dsp_window2,		/* 128 */
	dsp_window2,		/* 129 */
	dsp_window2,		/* 130 */
	dsp_window2,		/* 131 */
	dsp_window2,		/* 132 */
	dsp_window2,		/* 133 */
	dsp_window2,		/* 134 */
	dsp_window2,		/* 135 */
	dsp_window2,		/* 136 */
	dsp_window2,		/* 137 */
	dsp_window2,		/* 138 */
	dsp_window2,		/* 139 */
	dsp_window2,		/* 140 */
	dsp_window2,		/* 141 */
	dsp_window2,		/* 142 */
	dsp_dummy,		/* 143 */
	dsp_dummy,		/* 144 */
	dsp_dummy,		/* 145 */
	dsp_dummy,		/* 146 */
	dsp_dummy,		/* 147 */
	dsp_dummy,		/* 148 */
	dsp_dummy,		/* 149 */
	dsp_dummy,		/* 150 */
	dsp_dummy,		/* 151 */
	dsp_dummy,		/* 152 */
	dsp_dummy,		/* 153 */
	dsp_dummy,		/* 154 */
	dsp_dummy,		/* 155 */
	dsp_dummy,		/* 156 */
	dsp_dummy,		/* 157 */
	dsp_dummy,		/* 158 */
	dsp_dummy,		/* 159 */
	dsp_M031,		/* 160 */
	dsp_M032,		/* 161 */
	dsp_M033,		/* 162 */
	dsp_M034,		/* 163 */
	dsp_M035,		/* 164 */
	dsp_M036,		/* 165 */
	dsp_dummy,		/* 166 */
	dsp_dummy,		/* 167 */
	dsp_dummy,		/* 168 */
	dsp_dummy,		/* 169 */
	dsp_dummy,		/* 170 */
	dsp_dummy,		/* 171 */
	dsp_dummy,		/* 172 */
	dsp_dummy,		/* 173 */
	dsp_dummy,		/* 174 */
	dsp_dummy,		/* 175 */
	dsp_dummy,		/* 176 */
	dsp_dummy,		/* 177 */
	dsp_dummy,		/* 178 */
	dsp_dummy,		/* 179 */
	dsp_dummy,		/* 180 */
	dsp_dummy,		/* 181 */
	dsp_dummy,		/* 182 */
	dsp_dummy,		/* 183 */
	dsp_dummy,		/* 184 */
	dsp_dummy,		/* 185 */
	dsp_dummy,		/* 186 */
	dsp_dummy,		/* 187 */
	dsp_dummy,		/* 188 */
	dsp_dummy,		/* 189 */
	dsp_dummy,		/* 190 */
	dsp_dummy,		/* 191 */
	dsp_M041A,		/* 192 */
	dsp_M041B,		/* 193 */
	dsp_M043,		/* 194 */
	dsp_M0420,		/* 195 */
	dsp_M0421,		/* 196 */
	dsp_M044,		/* 197 */
	dsp_dummy,		/* 198 */
	dsp_dummy,		/* 199 */
	dsp_dummy,		/* 200 */
	dsp_dummy,		/* 201 */
	dsp_dummy,		/* 202 */
	dsp_dummy,		/* 203 */
	dsp_dummy,		/* 204 */
	dsp_dummy,		/* 205 */
	dsp_dummy,		/* 206 */
	dsp_dummy,		/* 207 */
	dsp_dummy,		/* 208 */
	dsp_dummy,		/* 209 */
	dsp_dummy,		/* 210 */
	dsp_dummy,		/* 211 */
	dsp_dummy,		/* 212 */
	dsp_dummy,		/* 213 */
	dsp_dummy,		/* 214 */
	dsp_dummy,		/* 215 */
	dsp_dummy,		/* 216 */
	dsp_dummy,		/* 217 */
	dsp_dummy,		/* 218 */
	dsp_dummy,		/* 219 */
	dsp_dummy,		/* 220 */
	dsp_dummy,		/* 221 */
	dsp_dummy,		/* 222 */
	dsp_dummy,		/* 223 */
	dsp_M001,		/* 224 */
	dsp_M010,		/* 225 */
	dsp_M011,		/* 226 */
	dsp_M014A,		/* 227 */
	dsp_M014B,		/* 228 */
	dsp_M014C,		/* 229 */
	dsp_M015,		/* 230 */
	dsp_M017A,		/* 231 */
	dsp_M017B,		/* 232 */
	dsp_M017C,		/* 233 */
	dsp_M017D,		/* 234 */
	dsp_M002,		/* 235 */
	dsp_M006,		/* 236 */
	dsp_M007,		/* 237 */
	dsp_M006B,		/* 238 */
	dsp_dummy,		/* 239 */
	dsp_M008A,		/* 240 */
	dsp_M008B,		/* 241 */
	dsp_dummy,		/* 242 */
	dsp_M008C,		/* 243 */
	dsp_M016,		/* 244 */
	dsp_M019,		/* 245 */
	dsp_M020,		/* 246 */
	dsp_M022,		/* 247 */
	dsp_M012,		/* 248 */
	dsp_dummy,		/* 249 */
	dsp_dummy,		/* 250 */
	dsp_dummy,		/* 251 */
	dsp_dummy,		/* 252 */
	dsp_dummy,		/* 253 */
	dsp_dummy,		/* 254 */
	dsp_dummy,		/* 255 */
	dsp_dummy,		/* 256 */
	dsp_dummy,		/* 257 */
	dsp_dummy,		/* 258 */
	dsp_dummy,		/* 259 */
	dsp_dummy,		/* 260 */
	dsp_dummy,		/* 261 */
	dsp_dummy,		/* 262 */
	dsp_dummy,		/* 263 */
	dsp_dummy,		/* 264 */
	dsp_dummy,		/* 265 */
	dsp_dummy,		/* 266 */
	dsp_dummy,		/* 267 */
	dsp_dummy,		/* 268 */
	dsp_dummy,		/* 269 */
	dsp_dummy,		/* 270 */
	dsp_dummy,		/* 271 */
	dsp_MB18,		/* 272 */
	dsp_M018,		/* 273 */
	dsp_MB18,		/* 274 */
	dsp_M018,		/* 275 */
	dsp_MB18,		/* 276 */
	dsp_M018,		/* 277 */
	dsp_MB18,		/* 278 */
	dsp_M018,		/* 279 */
	dsp_MB18,		/* 280 */
	dsp_M018,		/* 281 */
	dsp_MB18,		/* 282 */
	dsp_M018,		/* 283 */
	dsp_MB18,		/* 284 */
	dsp_M018,		/* 285 */
	dsp_MB18,		/* 286 */
	dsp_M018,		/* 287 */
	dsp_MB18,		/* 288 */
	dsp_M018,		/* 289 */
	dsp_MB18,		/* 290 */
	dsp_M018,		/* 291 */
	dsp_MB18,		/* 292 */
	dsp_M018,		/* 293 */
	dsp_MB18,		/* 294 */
	dsp_M018,		/* 295 */
	dsp_MB18,		/* 296 */
	dsp_M018,		/* 297 */
	dsp_MB18,		/* 298 */
	dsp_M018,		/* 299 */
	dsp_MB18,		/* 300 */
	dsp_M018,		/* 301 */
	dsp_MB18,		/* 302 */
	dsp_M018,		/* 303 */
	dsp_MB18,		/* 304 */
	dsp_M018,		/* 305 */
	dsp_MB18,		/* 306 */
	dsp_M018,		/* 307 */
	dsp_MB18,		/* 308 */
	dsp_M018,		/* 309 */
	dsp_MB18,		/* 310 */
	dsp_M018,		/* 311 */
	dsp_MB18,		/* 312 */
	dsp_M018,		/* 313 */
	dsp_MB18,		/* 314 */
	dsp_M018,		/* 315 */
	dsp_MB18,		/* 316 */
	dsp_M018,		/* 317 */
	dsp_MB18,		/* 318 */
	dsp_M018,		/* 319 */
	dsp_MB18,		/* 320 */
	dsp_M018,		/* 321 */
	dsp_MB18,		/* 322 */
	dsp_M018,		/* 323 */
	dsp_MB18,		/* 324 */
	dsp_M018,		/* 325 */
	dsp_MB18,		/* 326 */
	dsp_M018,		/* 327 */
	dsp_MB18,		/* 328 */
	dsp_M018,		/* 329 */
	dsp_MB18,		/* 330 */
	dsp_M018,		/* 331 */
	dsp_MB18,		/* 332 */
	dsp_M018,		/* 333 */
	dsp_MB18,		/* 334 */
	dsp_M018,		/* 335 */
	dsp_MB18,		/* 336 */
	dsp_M018,		/* 337 */
	dsp_MB18,		/* 338 */
	dsp_M018,		/* 339 */
	dsp_MB18,		/* 340 */
	dsp_M018,		/* 341 */
	dsp_MB18,		/* 342 */
	dsp_M018,		/* 343 */
	dsp_MB18,		/* 344 */
	dsp_M018,		/* 345 */
	dsp_MB18,		/* 346 */
	dsp_M018,		/* 347 */
	dsp_MB18,		/* 348 */
	dsp_M018,		/* 349 */
	dsp_MB18,		/* 350 */
	dsp_M018,		/* 351 */
	dsp_MB18,		/* 352 */
	dsp_M018,		/* 353 */
	dsp_MB18,		/* 354 */
	dsp_M018,		/* 355 */
	dsp_MB18,		/* 356 */
	dsp_M018,		/* 357 */
	dsp_MB18,		/* 358 */
	dsp_M018,		/* 359 */
	dsp_MB18,		/* 360 */
	dsp_M018,		/* 361 */
	dsp_MB18,		/* 362 */
	dsp_M018,		/* 363 */
	dsp_MB18,		/* 364 */
	dsp_M018,		/* 365 */
	dsp_MB18,		/* 366 */
	dsp_M018,		/* 367 */
	dsp_MB18,		/* 368 */
	dsp_M018,		/* 369 */
	dsp_MB18,		/* 370 */
	dsp_M018,		/* 371 */
	dsp_MB18,		/* 372 */
	dsp_M018,		/* 373 */
	dsp_MB18,		/* 374 */
	dsp_M018,		/* 375 */
	dsp_MB18,		/* 376 */
	dsp_M018,		/* 377 */
	dsp_MB18,		/* 378 */
	dsp_M018,		/* 379 */
	dsp_MB18,		/* 380 */
	dsp_M018,		/* 381 */
	dsp_MB18,		/* 382 */
	dsp_M018,		/* 383 */
	dsp_MB18,		/* 384 */
	dsp_M018,		/* 385 */
	dsp_MB18,		/* 386 */
	dsp_M018,		/* 387 */
	dsp_MB18,		/* 388 */
	dsp_M018,		/* 389 */
	dsp_MB18,		/* 390 */
	dsp_M018,		/* 391 */
	dsp_MB18,		/* 392 */
	dsp_M018,		/* 393 */
	dsp_MB18,		/* 394 */
	dsp_M018,		/* 395 */
	dsp_MB18,		/* 396 */
	dsp_M018,		/* 397 */
	dsp_MB18,		/* 398 */
	dsp_M018,		/* 399 */
	dsp_MB18,		/* 400 */
	dsp_dummy,		/* 401 */
	dsp_dummy,		/* 402 */
	dsp_dummy,		/* 403 */
	dsp_dummy,		/* 404 */
	dsp_dummy,		/* 405 */
	dsp_dummy,		/* 406 */
	dsp_dummy,		/* 407 */
	dsp_dummy,		/* 408 */
	dsp_dummy,		/* 409 */
	dsp_dummy,		/* 410 */
	dsp_dummy,		/* 411 */
	dsp_dummy,		/* 412 */
	dsp_dummy,		/* 413 */
	dsp_dummy,		/* 414 */
	dsp_dummy,		/* 415 */
	dsp_dummy,		/* 416 */
	dsp_dummy,		/* 417 */
	dsp_dummy,		/* 418 */
	dsp_dummy,		/* 419 */
	dsp_dummy,		/* 420 */
	dsp_dummy,		/* 421 */
	dsp_dummy,		/* 422 */
	dsp_dummy,		/* 423 */
	dsp_dummy,		/* 424 */
	dsp_dummy,		/* 425 */
	dsp_dummy,		/* 426 */
	dsp_dummy,		/* 427 */
	dsp_dummy,		/* 428 */
	dsp_dummy,		/* 429 */
	dsp_dummy,		/* 430 */
	dsp_dummy,		/* 431 */
	dsp_M052A,		/* 432 */
	dsp_M052B,		/* 433 */
	dsp_M052C,		/* 434 */
	dsp_M052D,		/* 435 */
	dsp_M052E,		/* 436 */
	dsp_M052F,		/* 437 */
	dsp_M052G,		/* 438 */
	dsp_dummy,		/* 439 */
	dsp_M062A,		/* 440 */
	dsp_M062B,		/* 441 */
	dsp_M062C,		/* 442 */
	dsp_M062D,		/* 443 */
	dsp_M062E,		/* 444 */
	dsp_M052D,		/* 445 */
	dsp_M052E,		/* 446 */
	dsp_M052F,		/* 447 */
	dsp_M056A0,		/* 448 */
	dsp_M056A1,		/* 449 */
	dsp_M056A2,		/* 450 */
	dsp_M056A3,		/* 451 */
	dsp_M056A4,		/* 452 */
	dsp_M056A5,		/* 453 */
	dsp_M056B0,		/* 454 */
	dsp_M056B1,		/* 455 */
	dsp_M058A0,		/* 456 */
	dsp_M058A1,		/* 457 */
	dsp_M058A2,		/* 458 */
	dsp_M058A3,		/* 459 */
	dsp_M058A4,		/* 460 */
	dsp_M058A5,		/* 461 */
	dsp_M058B0,		/* 462 */
	dsp_M058B1,		/* 463 */
	dsp_dummy,		/* 464 */
	dsp_dummy,		/* 465 */
	dsp_dummy,		/* 466 */
	dsp_dummy,		/* 467 */
	dsp_dummy,		/* 468 */
	dsp_dummy,		/* 469 */
	dsp_dummy,		/* 470 */
	dsp_dummy,		/* 471 */
	dsp_dummy,		/* 472 */
	dsp_dummy,		/* 473 */
	dsp_dummy,		/* 474 */
	dsp_dummy,		/* 475 */
	dsp_dummy,		/* 476 */
	dsp_dummy,		/* 477 */
	dsp_dummy,		/* 478 */
	dsp_dummy,		/* 479 */
	dsp_M0610,		/* 480 */
	dsp_M0611,		/* 481 */
	dsp_M055,		/* 482 */
	dsp_M053,		/* 483 */
	dsp_M060,		/* 484 */
	dsp_dummy,		/* 485 */
	dsp_dummy,		/* 486 */
	dsp_dummy,		/* 487 */
	dsp_M057A0,		/* 488 */
	dsp_M057A1,		/* 489 */
	dsp_M057B,		/* 490 */
	dsp_M051A,		/* 491 */
	dsp_M051B,		/* 492 */
	dsp_dummy,		/* 493 */
	dsp_dummy,		/* 494 */
	dsp_dummy,		/* 495 */
	dsp_dummy,		/* 496 */
	dsp_dummy,		/* 497 */
	dsp_dummy,		/* 498 */
	dsp_dummy,		/* 499 */
	dsp_dummy,		/* 500 */
	dsp_dummy,		/* 501 */
	dsp_dummy,		/* 502 */
	dsp_dummy,		/* 503 */
	dsp_dummy,		/* 504 */
	dsp_dummy,		/* 505 */
	dsp_dummy,		/* 506 */
	dsp_dummy,		/* 507 */
	dsp_dummy,		/* 508 */
	dsp_dummy,		/* 509 */
	dsp_dummy,		/* 510 */
	dsp_dummy,		/* 511 */
	dsp_M074B,		/* 512 */
	dsp_M074C,		/* 513 */
	dsp_M074A0,		/* 514 */
	dsp_M074A1,		/* 515 */
	dsp_M074D0,		/* 516 */
	dsp_M074D1,		/* 517 */
	dsp_dummy,		/* 518 */
	dsp_dummy,		/* 519 */
	dsp_M077A,		/* 520 */
	dsp_M077B,		/* 521 */
	dsp_M077C,		/* 522 */
	dsp_M077D,		/* 523 */
	dsp_M077E,		/* 524 */
	dsp_dummy,		/* 525 */
	dsp_dummy,		/* 526 */
	dsp_dummy,		/* 527 */
	dsp_M071A0,		/* 528 */
	dsp_M071A1,		/* 529 */
	dsp_M071A2,		/* 530 */
	dsp_M071A3,		/* 531 */
	dsp_M071A4,		/* 532 */
	dsp_M071A5,		/* 533 */
	dsp_M071B0,		/* 534 */
	dsp_M071B1,		/* 535 */
	dsp_M072B,		/* 536 */
	dsp_M072C,		/* 537 */
	dsp_M072A0,		/* 538 */
	dsp_M072A1,		/* 539 */
	dsp_M072D0,		/* 540 */
	dsp_M072D1,		/* 541 */
	dsp_dummy,		/* 542 */
	dsp_dummy,		/* 543 */
	dsp_M073A0,		/* 544 */
	dsp_M073A1,		/* 545 */
	dsp_M073A2,		/* 546 */
	dsp_M073A3,		/* 547 */
	dsp_M073A4,		/* 548 */
	dsp_M073A5,		/* 549 */
	dsp_M073B0,		/* 550 */
	dsp_M073B1,		/* 551 */
	dsp_dummy,		/* 552 */
	dsp_dummy,		/* 553 */
	dsp_dummy,		/* 554 */
	dsp_dummy,		/* 555 */
	dsp_dummy,		/* 556 */
	dsp_dummy,		/* 557 */
	dsp_dummy,		/* 558 */
	dsp_dummy,		/* 559 */
	dsp_M067B0,		/* 560 */
	dsp_M067B1,		/* 561 */
	dsp_M067A,		/* 562 */
	dsp_M0680,		/* 563 */
	dsp_M0681,		/* 564 */
	dsp_M0690,		/* 565 */
	dsp_M0691,		/* 566 */
	dsp_dummy,		/* 567 */
	dsp_M065A,		/* 568 */
	dsp_M065B,		/* 569 */
	dsp_M0660,		/* 570 */
	dsp_M0661,		/* 571 */
	dsp_dummy,		/* 572 */
	dsp_dummy,		/* 573 */
	dsp_dummy,		/* 574 */
	dsp_dummy,		/* 575 */
	dsp_dummy,		/* 576 */
	dsp_dummy,		/* 577 */
	dsp_dummy,		/* 578 */
	dsp_dummy,		/* 579 */
	dsp_dummy,		/* 580 */
	dsp_dummy,		/* 581 */
	dsp_dummy,		/* 582 */
	dsp_dummy,		/* 583 */
	dsp_dummy,		/* 584 */
	dsp_dummy,		/* 585 */
	dsp_dummy,		/* 586 */
	dsp_dummy,		/* 587 */
	dsp_dummy,		/* 588 */
	dsp_dummy,		/* 589 */
	dsp_dummy,		/* 590 */
	dsp_dummy,		/* 591 */
	dsp_dummy,		/* 592 */
	dsp_dummy,		/* 593 */
	dsp_dummy,		/* 594 */
	dsp_dummy,		/* 595 */
	dsp_dummy,		/* 596 */
	dsp_dummy,		/* 597 */
	dsp_dummy,		/* 598 */
	dsp_dummy,		/* 599 */
	dsp_dummy,		/* 600 */
	dsp_dummy,		/* 601 */
	dsp_dummy,		/* 602 */
	dsp_dummy,		/* 603 */
	dsp_dummy,		/* 604 */
	dsp_dummy,		/* 605 */
	dsp_dummy,		/* 606 */
	dsp_dummy,		/* 607 */
	dsp_dummy,		/* 608 */
	dsp_dummy,		/* 609 */
	dsp_dummy,		/* 610 */
	dsp_dummy,		/* 611 */
	dsp_dummy,		/* 612 */
	dsp_dummy,		/* 613 */
	dsp_dummy,		/* 614 */
	dsp_dummy,		/* 615 */
	dsp_dummy,		/* 616 */
	dsp_dummy,		/* 617 */
	dsp_dummy,		/* 618 */
	dsp_dummy,		/* 619 */
	dsp_dummy,		/* 620 */
	dsp_dummy,		/* 621 */
	dsp_dummy,		/* 622 */
	dsp_dummy,		/* 623 */
	dsp_dummy,		/* 624 */
	dsp_dummy,		/* 625 */
	dsp_dummy,		/* 626 */
	dsp_dummy,		/* 627 */
	dsp_dummy,		/* 628 */
	dsp_dummy,		/* 629 */
	dsp_dummy,		/* 630 */
	dsp_dummy,		/* 631 */
	dsp_dummy,		/* 632 */
	dsp_dummy,		/* 633 */
	dsp_dummy,		/* 634 */
	dsp_dummy,		/* 635 */
	dsp_dummy,		/* 636 */
	dsp_dummy,		/* 637 */
	dsp_dummy,		/* 638 */
	dsp_dummy,		/* 639 */
	dsp_dummy,		/* 640 */
	dsp_dummy,		/* 641 */
	dsp_dummy,		/* 642 */
	dsp_dummy,		/* 643 */
	dsp_dummy,		/* 644 */
	dsp_dummy,		/* 645 */
	dsp_dummy,		/* 646 */
	dsp_dummy,		/* 647 */
	dsp_dummy,		/* 648 */
	dsp_dummy,		/* 649 */
	dsp_dummy,		/* 650 */
	dsp_dummy,		/* 651 */
	dsp_dummy,		/* 652 */
	dsp_dummy,		/* 653 */
	dsp_dummy,		/* 654 */
	dsp_dummy,		/* 655 */
	dsp_dummy,		/* 656 */
	dsp_dummy,		/* 657 */
	dsp_dummy,		/* 658 */
	dsp_dummy,		/* 659 */
	dsp_dummy,		/* 660 */
	dsp_dummy,		/* 661 */
	dsp_dummy,		/* 662 */
	dsp_dummy,		/* 663 */
	dsp_dummy,		/* 664 */
	dsp_dummy,		/* 665 */
	dsp_dummy,		/* 666 */
	dsp_dummy,		/* 667 */
	dsp_dummy,		/* 668 */
	dsp_dummy,		/* 669 */
	dsp_dummy,		/* 670 */
	dsp_dummy,		/* 671 */
	dsp_dummy,		/* 672 */
	dsp_dummy,		/* 673 */
	dsp_dummy,		/* 674 */
	dsp_dummy,		/* 675 */
	dsp_dummy,		/* 676 */
	dsp_dummy,		/* 677 */
	dsp_dummy,		/* 678 */
	dsp_dummy,		/* 679 */
	dsp_dummy,		/* 680 */
	dsp_dummy,		/* 681 */
	dsp_dummy,		/* 682 */
	dsp_dummy,		/* 683 */
	dsp_dummy,		/* 684 */
	dsp_dummy,		/* 685 */
	dsp_dummy,		/* 686 */
	dsp_dummy,		/* 687 */
	dsp_dummy,		/* 688 */
	dsp_dummy,		/* 689 */
	dsp_dummy,		/* 690 */
	dsp_dummy,		/* 691 */
	dsp_dummy,		/* 692 */
	dsp_dummy,		/* 693 */
	dsp_dummy,		/* 694 */
	dsp_dummy,		/* 695 */
	dsp_dummy,		/* 696 */
	dsp_dummy,		/* 697 */
	dsp_dummy,		/* 698 */
	dsp_dummy,		/* 699 */
	dsp_dummy,		/* 700 */
	dsp_dummy,		/* 701 */
	dsp_dummy,		/* 702 */
	dsp_dummy,		/* 703 */
	dsp_dummy,		/* 704 */
	dsp_dummy,		/* 705 */
	dsp_dummy,		/* 706 */
	dsp_dummy,		/* 707 */
	dsp_dummy,		/* 708 */
	dsp_dummy,		/* 709 */
	dsp_dummy,		/* 710 */
	dsp_dummy,		/* 711 */
	dsp_dummy,		/* 712 */
	dsp_dummy,		/* 713 */
	dsp_dummy,		/* 714 */
	dsp_dummy,		/* 715 */
	dsp_dummy,		/* 716 */
	dsp_dummy,		/* 717 */
	dsp_dummy,		/* 718 */
	dsp_dummy,		/* 719 */
	dsp_M003A0,		/* 720 */
	dsp_M003A1,		/* 721 */
	dsp_M003B0,		/* 722 */
	dsp_M003B1,		/* 723 */
	dsp_M004A0,		/* 724 */
	dsp_M004A1,		/* 725 */
	dsp_M004B0,		/* 726 */
	dsp_M004B1,		/* 727 */
	dsp_M0050,		/* 728 */
	dsp_M0051,		/* 729 */
	dsp_M0090,		/* 730 */
	dsp_M0091,		/* 731 */
	dsp_M0130,		/* 732 */
	dsp_M0131,		/* 733 */
	dsp_dummy,		/* 734 */
	dsp_dummy,		/* 735 */
	dsp_M0210,		/* 736 */
	dsp_M0211,		/* 737 */
	dsp_M0230,		/* 738 */
	dsp_M0231,		/* 739 */
	dsp_dummy,		/* 740 */
	dsp_dummy,		/* 741 */
	dsp_dummy,		/* 742 */
	dsp_dummy,		/* 743 */
	dsp_M0240,		/* 744 */
	dsp_M0241,		/* 745 */
	dsp_dummy,		/* 746 */
	dsp_dummy,		/* 747 */
	dsp_dummy,		/* 748 */
	dsp_dummy,		/* 749 */
	dsp_dummy,		/* 750 */
	dsp_dummy,		/* 751 */
	dsp_dummy,		/* 752 */
	dsp_dummy,		/* 753 */
	dsp_dummy,		/* 754 */
	dsp_dummy,		/* 755 */
	dsp_dummy,		/* 756 */
	dsp_dummy,		/* 757 */
	dsp_dummy,		/* 758 */
	dsp_dummy,		/* 759 */
	dsp_dummy,		/* 760 */
	dsp_dummy,		/* 761 */
	dsp_dummy,		/* 762 */
	dsp_dummy,		/* 763 */
	dsp_dummy,		/* 764 */
	dsp_dummy,		/* 765 */
	dsp_dummy,		/* 766 */
	dsp_dummy,		/* 767 */
	dsp_M054,		/* 768 */
	dsp_dummy,		/* 769 */
	dsp_dummy,		/* 770 */
	dsp_dummy,		/* 771 */
	dsp_dummy,		/* 772 */
	dsp_dummy,		/* 773 */
	dsp_dummy,		/* 774 */
	dsp_dummy,		/* 775 */
	dsp_M059B0,		/* 776 */
	dsp_M059B1,		/* 777 */
	dsp_M059B2,		/* 778 */
	dsp_M059B3,		/* 779 */
	dsp_M059A0,		/* 780 */
	dsp_M059A1,		/* 781 */
	dsp_dummy,		/* 782 */
	dsp_dummy,		/* 783 */
	dsp_dummy,		/* 784 */
	dsp_dummy,		/* 785 */
	dsp_dummy,		/* 786 */
	dsp_dummy,		/* 787 */
	dsp_dummy,		/* 788 */
	dsp_dummy,		/* 789 */
	dsp_dummy,		/* 790 */
	dsp_dummy,		/* 791 */
	dsp_dummy,		/* 792 */
	dsp_dummy,		/* 793 */
	dsp_dummy,		/* 794 */
	dsp_dummy,		/* 795 */
	dsp_dummy,		/* 796 */
	dsp_dummy,		/* 797 */
	dsp_dummy,		/* 798 */
	dsp_dummy,		/* 799 */
	dsp_dummy,		/* 800 */
	dsp_dummy,		/* 801 */
	dsp_dummy,		/* 802 */
	dsp_dummy,		/* 803 */
	dsp_dummy,		/* 804 */
	dsp_dummy,		/* 805 */
	dsp_dummy,		/* 806 */
	dsp_dummy,		/* 807 */
	dsp_dummy,		/* 808 */
	dsp_dummy,		/* 809 */
	dsp_dummy,		/* 810 */
	dsp_dummy,		/* 811 */
	dsp_dummy,		/* 812 */
	dsp_dummy,		/* 813 */
	dsp_dummy,		/* 814 */
	dsp_dummy,		/* 815 */
	dsp_M070B,		/* 816 */
	dsp_M070A,		/* 817 */
	dsp_dummy,		/* 818 */
	dsp_dummy,		/* 819 */
	dsp_dummy,		/* 820 */
	dsp_dummy,		/* 821 */
	dsp_dummy,		/* 822 */
	dsp_dummy,		/* 823 */
	dsp_M075B,		/* 824 */
	dsp_M075A,		/* 825 */
	dsp_dummy,		/* 826 */
	dsp_dummy,		/* 827 */
	dsp_dummy,		/* 828 */
	dsp_dummy,		/* 829 */
	dsp_dummy,		/* 830 */
	dsp_dummy,		/* 831 */
	dsp_M0760,		/* 832 */
	dsp_M0761,		/* 833 */
};

typedef	struct	{
	short	font;
	char	ch;
	char	font_size;
} FONTCNV;
FONTCNV	fontcnv[] = {
	{FONT6_SP,	' ',	6},
	{FONT6_0,	'0',	6},
	{FONT6_01,	'.',	6},
	{FONT6_03,	'+',	6},
	{FONT6_04,	'-',	6},
	{FONT6_07,	'/',	6},
	{FONT6_1,	'1',	6},
	{FONT6_12,	'%',	6},
	{FONT6_13,	'_',	6},
	{FONT6_14,	'(',	6},
	{FONT6_15,	')',	6},
	{FONT6_16,	'*',	6},
	{FONT6_19,	'=',	6},
	{FONT6_2,	'2',	6},
	{FONT6_20,	'>',	6},
	{FONT6_21,	'<',	6},
	{FONT6_22,	'&',	6},
	{FONT6_23,	':',	6},
	{FONT6_3,	'3',	6},
	{FONT6_4,	'4',	6},
	{FONT6_5,	'5',	6},
	{FONT6_6,	'6',	6},
	{FONT6_7,	'7',	6},
	{FONT6_8,	'8',	6},
	{FONT6_9,	'9',	6},
	{FONT6_A,	'A',	6},
	{FONT6_B,	'B',	6},
	{FONT6_C,	'C',	6},
	{FONT6_D,	'D',	6},
	{FONT6_E,	'E',	6},
	{FONT6_F,	'F',	6},
	{FONT6_G,	'G',	6},
	{FONT6_H,	'H',	6},
	{FONT6_I,	'I',	6},
	{FONT6_J,	'J',	6},
	{FONT6_K,	'K',	6},
	{FONT6_L,	'L',	6},
	{FONT6_M,	'M',	6},
	{FONT6_N,	'N',	6},
	{FONT6_O,	'O',	6},
	{FONT6_P,	'P',	6},
	{FONT6_Q,	'Q',	6},
	{FONT6_R,	'R',	6},
	{FONT6_S,	'S',	6},
	{FONT6_T,	'T',	6},
	{FONT6_U,	'U',	6},
	{FONT6_V,	'V',	6},
	{FONT6_W,	'W',	6},
	{FONT6_X,	'X',	6},
	{FONT6_Y,	'Y',	6},
	{FONT6_Z,	'Z',	6},
	{FONT6_30,	'ﾒ',	6},
	{FONT6_31,	'a',	6},
	{FONT6_32,	'b',	6},
	{FONT6_33,	'c',	6},
	{FONT6_34,	'x',	6},
	{FONT6_40,	'o',	6},
	{FONT6_41,	'u',	6},
	{FONT6_42,	'd',	6},
	{FONT6_43,	'#',	6},
	{FONT7_SP,	' ',	7},
	{FONT7_0,	'0',	7},
	{FONT7_01,	'.',	7},
	{FONT7_03,	'+',	7},
	{FONT7_04,	'-',	7},
	{FONT7_07,	'/',	7},
	{FONT7_1,	'1',	7},
	{FONT7_12,	'%',	7},
	{FONT7_13,	'_',	7},
	{FONT7_14,	'(',	7},
	{FONT7_15,	')',	7},
	{FONT7_16,	'*',	7},
	{FONT7_19,	'=',	7},
	{FONT7_2,	'2',	7},
	{FONT7_20,	'>',	7},
	{FONT7_21,	'<',	7},
	{FONT7_22,	'&',	7},
	{FONT7_23,	':',	7},
	{FONT7_3,	'3',	7},
	{FONT7_4,	'4',	7},
	{FONT7_5,	'5',	7},
	{FONT7_6,	'6',	7},
	{FONT7_7,	'7',	7},
	{FONT7_8,	'8',	7},
	{FONT7_9,	'9',	7},
	{FONT7_A,	'A',	7},
	{FONT7_B,	'B',	7},
	{FONT7_C,	'C',	7},
	{FONT7_D,	'D',	7},
	{FONT7_E,	'E',	7},
	{FONT7_F,	'F',	7},
	{FONT7_G,	'G',	7},
	{FONT7_H,	'H',	7},
	{FONT7_I,	'I',	7},
	{FONT7_J,	'J',	7},
	{FONT7_K,	'K',	7},
	{FONT7_L,	'L',	7},
	{FONT7_M,	'M',	7},
	{FONT7_N,	'N',	7},
	{FONT7_O,	'O',	7},
	{FONT7_P,	'P',	7},
	{FONT7_Q,	'Q',	7},
	{FONT7_R,	'R',	7},
	{FONT7_S,	'S',	7},
	{FONT7_T,	'T',	7},
	{FONT7_U,	'U',	7},
	{FONT7_V,	'V',	7},
	{FONT7_W,	'W',	7},
	{FONT7_X,	'X',	7},
	{FONT7_Y,	'Y',	7},
	{FONT7_Z,	'Z',	7},
	{FONT7_30,	'ﾒ',	7},
	{FONT7_31,	'a',	7},
	{FONT7_32,	'b',	7},
	{FONT7_33,	'c',	7},
	{FONT7_34,	'x',	7},
	{FONT7_40,	'o',	7},
	{FONT7_41,	'u',	7},
	{FONT7_42,	'd',	7},
	{FONT7_43,	'#',	7},
	{FONT8_SP,	' ',	8},
	{FONT8_0,	'0',	8},
	{FONT8_01,	'.',	8},
	{FONT8_03,	'+',	8},
	{FONT8_04,	'-',	8},
	{FONT8_07,	'/',	8},
	{FONT8_1,	'1',	8},
	{FONT8_12,	'%',	8},
	{FONT8_13,	'_',	8},
	{FONT8_14,	'(',	8},
	{FONT8_15,	')',	8},
	{FONT8_16,	'*',	8},
	{FONT8_19,	'=',	8},
	{FONT8_2,	'2',	8},
	{FONT8_20,	'>',	8},
	{FONT8_21,	'<',	8},
	{FONT8_22,	'&',	8},
	{FONT8_23,	':',	8},
	{FONT8_3,	'3',	8},
	{FONT8_4,	'4',	8},
	{FONT8_5,	'5',	8},
	{FONT8_6,	'6',	8},
	{FONT8_7,	'7',	8},
	{FONT8_8,	'8',	8},
	{FONT8_9,	'9',	8},
	{FONT8_A,	'A',	8},
	{FONT8_B,	'B',	8},
	{FONT8_C,	'C',	8},
	{FONT8_D,	'D',	8},
	{FONT8_E,	'E',	8},
	{FONT8_F,	'F',	8},
	{FONT8_G,	'G',	8},
	{FONT8_H,	'H',	8},
	{FONT8_I,	'I',	8},
	{FONT8_J,	'J',	8},
	{FONT8_K,	'K',	8},
	{FONT8_L,	'L',	8},
	{FONT8_M,	'M',	8},
	{FONT8_N,	'N',	8},
	{FONT8_O,	'O',	8},
	{FONT8_P,	'P',	8},
	{FONT8_Q,	'Q',	8},
	{FONT8_R,	'R',	8},
	{FONT8_S,	'S',	8},
	{FONT8_T,	'T',	8},
	{FONT8_U,	'U',	8},
	{FONT8_V,	'V',	8},
	{FONT8_W,	'W',	8},
	{FONT8_X,	'X',	8},
	{FONT8_Y,	'Y',	8},
	{FONT8_Z,	'Z',	8},
	{FONT8_30,	'ﾒ',	8},
	{FONT8_31,	'a',	8},
	{FONT8_32,	'b',	8},
	{FONT8_33,	'c',	8},
	{FONT8_34,	'x',	8},
	{FONT8_40,	'o',	8},
	{FONT8_41,	' ',	8},
	{FONT8_42,	' ',	8},
	{FONT8_43,	'#',	8},
	{0, 0, 0}
};


static	void	t_manual(void);
static	void	t_save(void);
static	void	t_load(void);
static	void	t_help(void);
void	disp_data(void);

void	initscr()
{
	_setvideomode(_98RESS16COLOR);
	_remappalette(0, 0x000000L);	/* BLACK */
	_remappalette(1, 0x0f0000L);	/* BLUE */
	_remappalette(2, 0x00000fL);	/* RED1 */
	_remappalette(3, 0x0f000fL);	/* MAGENTA */
	_remappalette(4, 0x00060aL);	/* BROWN */
	_remappalette(5, 0x000a0fL);	/* ORANGE */
	_remappalette(6, 0x000c0eL);	/* AMBER */
	_remappalette(7, 0x0f0a00L);	/* CYAN */
	_remappalette(8, 0x000f00L);	/* GREEN1 */
	_remappalette(9, 0x000a00L);	/* GREEN2 */
	_remappalette(10, 0x000600L);	/* GREEN3 */
	_remappalette(11, 0x000400L);	/* GREEN4 */
	_remappalette(12, 0x000f0fL);	/* YELLOW */
	_remappalette(13, 0x00000aL);	/* RED2 */
	_remappalette(14, 0x0b0b0bL);	/* WHITE2 */
	_remappalette(15, 0x0f0f0fL);	/* WHITE1 */
	_registerfonts("PSCOUN.FON");
	_setwritemode(_GPSET);
	_setcolor(COLOR_WHITE1);
	_setactivepage(1);
	_clearscreen(_GCLEARGRAPH);
	_rectangle(_GBORDER,240, 0, 639, 399);
	_setactivepage(0);
	_clearscreen(_GCLEARGRAPH);
	_rectangle(_GBORDER, 240, 0, 639, 399);
	_setviewport(241, 1, 638, 398);
}

void	endscr()
{
	_setvideomode(_98TEXT80);
}

void	test(int c)
{
	switch (c) {
	case 'L':
		t_load();
		break;
	case 'M':
		t_manual();
		break;
	case 'S':
		t_save();
		break;
	}
}

static	void	t_manual(void)
{
	short	block_no, word_no, data;
	char	buf[80];

	for (;;) {
		printf("BLOCK NO. =");
		gets(buf);
		if (buf[0] == '\0')
			break;
		block_no = atoi(buf) - 1;

		printf("WORD NO.  =");
		gets(buf);
		if (buf[0] == '\0')
			break;
		word_no = atoi(buf) - 1;

		if (block_no >= 0 && block_no < 44
					&& word_no >= 0 && word_no < 32) {
			printf("DATA(%04x)=", mbi_rcvbuf[block_no][word_no]);
			gets(buf);
			printf("\n");
			if (buf[0] != '\0') {
				sscanf(buf, "%x", &data);
				mbi_rcvbuf[block_no][word_no] = data;
				digital_update_flag = 1;
				old_display_mode = 0;
				SET_WORD(pa, 0, N_PABIT);
				get_data();	/* 入力データ取り込み*/
				filter();	/* フィルタ処理 */
				dp_calc();	/* Ｄ／Ｐ計算 */
				copy_pa_dp();
				disp_data();
			}
		}
	}
}

static	void	t_save(void)
{
	char	buf[80];
	FILE	*fp;

	printf("SAVE FILE NAME ? ");
	gets(buf);
	if (buf[0] == '\0')
		return;
	strcat(buf, ".DAT");
	if ((fp = fopen(buf, "wb")) != NULL) {
		fwrite(mbi_rcvbuf, 44*32, 2, fp);
		fclose(fp);
	}
}

static	void	t_load(void)
{
	char	buf[80];
	FILE	*fp;

	printf("LOAD FILE NAME ? ");
	gets(buf);
	if (buf[0] == '\0')
		return;
	strcat(buf, ".DAT");
	if ((fp = fopen(buf, "rb")) != NULL) {
		fread(mbi_rcvbuf, 44*32, 2, fp);
		fclose(fp);
		digital_update_flag = 1;
		SET_WORD(pa, 0, N_PABIT);
		get_data();	/* 入力データ取り込み*/
		filter();	/* フィルタ処理 */
		dp_calc();	/* Ｄ／Ｐ計算 */
		copy_pa_dp();
		disp_data();
	}
}

static	void	t_help(void)
{
	printf("E : プログラム終了\n");
	printf("H : コマンド一覧表示\n");
	printf("L : データロード\n");
	printf("M : データ入力\n");
	printf("S : データセーブ\n");
	printf("T : 計算時間測定\n");
	printf("\n");
}

void	disp_data(void)
{
	short	i;
	ushort	bit, *pap;
	static	short	screen;

	screen ^= 1;
	_setactivepage(screen);
	_clearscreen(_GVIEWPORT);

	bit = 0x0001;
	pap = pa_tmp;
	for (i = 0; i <= 840; i++) {
		if (*pap & bit)
			(*dsp_func[i])(i);

		bit <<= 1;
		if (bit == 0) {
			pap++;
			bit = 0x0001;
		}
	}

	_setvisualpage(screen);
}

static	void	dsp_dummy(short pa)
{
	printf("DUMMY %d\n", pa);
}

static	void	reserve(short pa)
{
	printf("INVALID P/A BIT (%d)\n", pa);
}

void	dsp_str(short x, short y, char *str, short font_size)
{
	set_font(font_size);
	_moveto(199 + (short)(x * 199L / 5400),
				199 - (short)(y * 199L / 5400));
	_outgtext(str);
}

void	dsp_str2(short x, short y, char *str, short font_size)
{
	set_font(font_size);
	switch (font_size) {
	case 6:
		y += 241;
		break;
	case 7:
		y += 361;
		break;
	case 8:
		y += 481;
		break;
	}
	_moveto(199 + (short)(x * 199L / 5400),
				199 - (short)(y * 199L / 5400));
	_outgtext(str);
}

void	dsp_font(short x, short y, short *font, short n)
{
	int	i;
	char	buf[2];

	_moveto(199 + (short)(x * 199L / 5400),
				199 - (short)(y * 199L / 5400));
	font += n;
	while (n--) {
		font--;
		for (i = 0; ; i++) {
			if (*font == fontcnv[i].font) {
				buf[0] = fontcnv[i].ch;
				buf[1] = '\0';
				set_font(fontcnv[i].font_size);
				_outgtext(buf);
				break;
			}
			if (fontcnv[i].font == 0) {
				printf("?=%04x\n", *font);
				buf[0] = '?';
				buf[1] = '\0';
				_outgtext(buf);
				break;
			}
		}
	}
}

void	dsp_font2(short x, short y, short *font, short n)
{
	int	i;
	char	buf[2];
	int	flag, w;

	flag = 0;
	while (n--) {
		for (i = 0; ; i++) {
			if (*font == fontcnv[i].font) {
				if (flag == 0) {
					switch (fontcnv[i].font_size) {
					case 6:
						y += 241;
						w = 225;
						break;
					case 7:
						y += 361;
						w = 338;
						break;
					case 8:
						y += 481;
						w = 451;
						break;
					}
					flag = 1;
				}
				_moveto(199 + (short)(x * 199L / 5400),
					    199 - (short)(y * 199L / 5400));
				x += w;
				buf[0] = fontcnv[i].ch;
				buf[1] = '\0';
				set_font(fontcnv[i].font_size);
				_outgtext(buf);
				break;
			}
			if (fontcnv[i].font == 0) {
				printf("?=%04x\n", *font);
				buf[0] = '?';
				buf[1] = '\0';
				_outgtext(buf);
				break;
			}
		}
		font++;
	}
}

void	set_font(int font_size)
{
	static	int	current_font_size;

	if (font_size != current_font_size) {
		switch (font_size) {
		case 6:
			_setfont("w8");
			break;
		case 7:
			_setfont("w10");
			break;
		case 8:
			_setfont("w13");
			break;
		}
		current_font_size = font_size;
	}
}

void	setcolor(ushort color)
{
	_setcolor(color);
}

void	setlinestyle(ushort mask)
{
	_setlinestyle(mask);
}

void	line(short x1, short y1, short x2, short y2)
{
	_moveto(199 + (short)(x1 * 199L / 5400),
				199 - (short)(y1 * 199L / 5400));
	_lineto(199 + (short)(x2 * 199L / 5400),
				199 - (short)(y2 * 199L / 5400));
}

void	box(short x1, short y1, short x2, short y2, short control)
{
	_rectangle(control, 199 + (short)(x1 * 199L / 5400),
			199 - (short)(y1 * 199L / 5400),
			199 + (short)(x2 * 199L / 5400),
			199 - (short)(y2 * 199L / 5400));
}

void	circle(short x, short y, short r, short control)
{
	r = (r * 199L) / 5400;
	x = 199 + (short)(x * 199L / 5400);
	y = 199 - (short)(y * 199L / 5400);

	_ellipse(control, x - r, y - r, x + r, y + r);
}

void	arc(short x, short y, short x2, short y2, short x3, short y3, short r)
{
	r = (r * 199L) / 5400;
	x = 199 + (short)(x * 199L / 5400);
	y = 199 - (short)(y * 199L / 5400);
	x2 = 199 + (short)(x2 * 199L / 5400);
	y2 = 199 - (short)(y2 * 199L / 5400);
	x3 = 199 + (short)(x3 * 199L / 5400);
	y3 = 199 - (short)(y3 * 199L / 5400);

	_arc(x-r, y-r, x+r, y+r, x2, y2, x3, y3);
}

void	triangle(short x1, short y1, short x2, short y2, short x3, short y3, short control)
{
	struct	xycoord	coord[3];

	coord[0].xcoord = 199 + (short)(x1 * 199L / 5400);
	coord[0].ycoord = 199 - (short)(y1 * 199L / 5400),
	coord[1].xcoord = 199 + (short)(x2 * 199L / 5400);
	coord[1].ycoord = 199 - (short)(y2 * 199L / 5400),
	coord[2].xcoord = 199 + (short)(x3 * 199L / 5400);
	coord[2].ycoord = 199 - (short)(y3 * 199L / 5400),
	_polygon(control, coord, 3);
	if (control == _GFILLINTERIOR)
		_polygon(_GBORDER, coord, 3);
}

void	polygon(struct xycoord *coord, short n, short control)
{
	int	i;

	for (i = 0; i < n; i++) {
		coord[i].xcoord = 199 + (short)(coord[i].xcoord * 199L / 5400);
		coord[i].ycoord = 199 - (short)(coord[i].ycoord * 199L / 5400);
	}
	_polygon(control, coord, n);
	if (control == _GFILLINTERIOR)
		_polygon(_GBORDER, coord, n);
}

void	fill(short x, short y, ushort color)
{
	_floodfill(199 + (short)(x * 199L / 5400),
			199 - (short)(y * 199L / 5400), color);
}

void	rotate(short *x, short *y, short angle)
{
	double	a, s, c;
	short	x1, y1;

	a = (ushort)angle / 32768.0 * PI;
	s = sin(a);
	c = cos(a);
	x1 = *x * c - *y * s;
	y1 = *y * c + *x * s;
	*x = x1;
	*y = y1;
}

void	rotate2(struct xycoord *coord, short n, short angle)
{
	double	a;
	long	s, c;
	short	x, y;

	a = (ushort)angle / 32768.0 * PI;
	s = sin(a) * 65536.0;
	c = cos(a) * 65536.0;
	while (n--) {
		x = (coord->xcoord * c - coord->ycoord * s) >> 16;
		y = (coord->ycoord * c + coord->xcoord * s) >> 16;
		coord->xcoord = x;
		coord->ycoord = y;
		coord++;
	}
}

void	setclip(short x1, short y1, short x2, short y2)
{
	_setcliprgn(199 + (short)(x1 * 199L / 5400) + 241,
			199 - (short)(y2 * 199L / 5400) + 1,
			199 + (short)(x2 * 199L / 5400) + 241,
			199 - (short)(y1 * 199L / 5400) + 1);
}

void	resetclip(void)
{
	_setcliprgn(241, 1, 638, 398);
}
