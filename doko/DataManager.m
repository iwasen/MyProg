//
//  DataManager.m
//  Doko
//
//  Created by 相沢 伸一 on 11/05/01.
//  Copyright 2011 TRC. All rights reserved.
//

#import "DataManager.h"

#define numBerOfItem(itemData)  (sizeof(itemData) / sizeof(PlayItemData))

// 1ページ
static PlayItemData playItemDataP01[] = { 
    {{@"あひる2ひき", @"two ducks"}, {171 , 51, 29 , 26}, {@"kddk1j_wo0001_01ahirunxxxki", @"kddk1e_wo0001_01twoducks"}},
    {{@"なかよしのうさぎ", @"a chain of rabbits"}, {501, 347, 68 , 35}, {@"kddk1j_wo0002_01nakayosinousagi", @"kddk1e_wo0002_01achainofrabbits"}},
    {{@"オレンジのかぼちゃ", @"an orange pumpkin"}, {584, 219, 16 , 17}, {@"kddk1j_wo0003_01orenjinokabocha", @"kddk1e_wo0003_01anorangepumpkin"}},
    {{@"あかずきん", @"Little Red Riding Hood"}, {525, 268, 48 , 69}, {@"kddk1j_wo0004_01akazukin", @"kddk1e_wo0004_01littleredridinghood"}},
    {{@"きいろいクレヨン", @"a yellow crayon"}, {104 , 267 , 16 , 23}, {@"kddk1j_wo0005_01kiiroikureyon", @"kddk1e_wo0005_01ayellowcrayon"}},
    {{@"さいころ", @"a dice"}, {640 , 395 , 15 , 13}, {@"kddk1j_wo0006_01saikoro", @"kddk1e_wo0006_01adice"}},
};

// 2ページ
static PlayItemData playItemDataP02[] = { 
    {{@"ゆきだるま", @"a snowman"}, {648 , 311 , 22 , 34}, {@"kddk1j_wo0017_02yukidaruma", @"kddk1e_wo0017_02asnowman"}},
    {{@"バケツ", @"a bucket"}, {347 , 629 , 48 , 34}, {@"kddk1j_wo0018_02baketu", @"kddk1e_wo0018_02abucket"}},
    {{@"シャベル", @"a shovel "}, {275 , 692 , 48 , 42}, {@"kddk1j_wo0019_02shaberu", @"kddk1e_wo0019_02ashovel"}},
    {{@"タイヤ", @"a tire"}, {546 , 225 , 38 , 41}, {@"kddk1j_wo0020_02taiya", @"kddk1e_wo0020_02atire"}},
    {{@"ひこうき", @"a plane"}, {592 , 109 , 41 , 38}, {@"kddk1j_wo0021_02hikouki", @"kddk1e_wo0021_02aplane"}},
    {{@"ぼうし", @"a hat "}, {729 , 325 , 28 , 36}, {@"kddk1j_wo0023_02bousi", @"kddk1e_wo0023_02ahat"}},
    {{@"にんじん", @"a carrot"}, {235 , 555 , 59 , 28}, {@"kddk1j_wo0025_02ninjin", @"kddk1e_wo0025_02acarrot"}},
    {{@"たまねぎ", @"an onion"}, {568 , 312 , 20 , 25}, {@"kddk1j_wo0026_02tamanegi", @"kddk1e_wo0026_02anonion"}},
    {{@"じゃがいも", @"a potato"}, {345 , 601 , 45 , 26}, {@"kddk1j_wo0027_02jagaimo", @"kddk1e_wo0027_02apotato"}},
    {{@"しろいベンチ", @"a white bench"}, {308 , 331 , 47 , 30}, {@"kddk1j_wo0028_02siroibenti", @"kddk1e_wo0028_02awhitebench"}},
};

// 3ページ
static PlayItemData playItemDataP03[] = { 
    {{@"しか", @"a deer"}, {472 , 506 , 68 , 95}, {@"kddk1j_wo0033_03sika", @"kddk1e_wo0033_03adeer"}},
    {{@"なかよしのりす", @"a chain of squirrels"}, {541 , 555 , 172 , 55}, {@"kddk1j_wo0035_03nakayosinorisu", @"kddk1e_wo0035_03achainofsquirrels"}},
    {{@"どんぐり", @"an acorn"}, {452 , 590 , 18 , 28}, {@"kddk1j_wo0036_03donguri", @"kddk1e_wo0036_03anacorm"}},
    {{@"てんとうむし", @"a ladybug"}, {195 , 640 , 30 , 22}, {@"kddk1j_wo0037_03tentoumusi", @"kddk1e_wo0037_03aladybug"}},
    {{@"ハートのマーク", @"a heart"}, {454 , 317 , 56 , 49}, {@"kddk1j_wo0038_03haatonomaaku", @"kddk1e_wo0038_03aheart"}},
    {{@"きつつき", @"a woodpecker"}, {371 , 194 , 35 , 58}, {@"kddk1j_wo0039_03kitutuki", @"kddk1e_wo0039_03awoodpecker"}},
    {{@"へび", @"a snake"}, {754 , 531 , 168 , 51}, {@"kddk1j_wo0041_03hebi", @"kddk1e_wo0041_03asnake"}},
};

// 4ページ
static PlayItemData playItemDataP04[] = { 
    {{@"とりのはね", @"a bird feather"}, {705 , 231 , 80 , 73}, {@"kddk1j_wo0043_04torinohane", @"kddk1e_wo0043_04abirdfeather"}},
    {{@"あしあと", @"a footprint"}, {276 , 672 , 55 , 37}, {@"kddk1j_wo0044_04asiato", @"kddk1e_wo0044_04footprint"}},
    {{@"ネックレス", @"a xxxklace"}, {700 , 336 , 51 , 44}, {@"kddk1j_wo0045_04nekkuresu", @"kddk1e_wo0045_04axxxklace"}},
    {{@"いつつぼしのてんとうむし", @"a ladybug with five dots"}, {357 , 138 , 22 , 22}, {@"kddk1j_wo0046_04itutubosinotentoumusi", @"kddk1e_wo0046_04aladybugwithfivedots"}},
    {{@"ほしじるしのてんとうむし", @"a ladybug with stars on it"}, {284 , 520 , 19 , 21}, {@"kddk1j_wo0047_04hosijirusinotentoumusi", @"kddk1e_wo0047_04aladybugwithstarsonit"}},
    {{@"くわがたむし", @"a horned beetle"}, {740 , 407 , 55 , 57}, {@"kddk1j_wo0048_04kuwagatamusi", @"kddk1e_wo0048_04ahornedbeetle"}},
    {{@"かえる", @"a frog"}, {89 , 401 , 55 , 61}, {@"kddk1j_wo0049_04kaeru", @"kddk1e_wo0049_04sfrog"}},
    {{@"ヘリコプター", @"a helicopter"}, {59 , 366 , 38 , 38}, {@"kddk1j_wo0050_04herikoputaa", @"kddk1e_wo0050_04ahelcopter"}},
};

// 5ページ
static PlayItemData playItemDataP05[] = { 
    {{@"ピザ", @"a pizza"}, {529 , 516 , 53 , 32}, {@"kddk1j_wo0051_05piza", @"kddk1e_wo0051_05apizza"}},
    {{@"オムライス", @"an omelette"}, {502 , 491 , 57 , 23}, {@"kddk1j_wo0052_05omuraisu", @"kddk1e_wo0052_05aomelette"}},
    {{@"サンドイッチ", @"a sandwich"}, {776 , 517 , 52 , 34}, {@"kddk1j_wo0053_05sandoitti", @"kddk1e_wo0053_05asandwich"}},
    {{@"かたつむりのおやこ", @"a snail and its little one"}, {423 , 671 , 95 , 36}, {@"kddk1j_wo0054_05katatumurinooyako", @"kddk1e_wo0054_05asnailanditslittleone"}},
    {{@"かぼちゃのティーセット", @"a lovely pumpkin tea set"}, {327 , 615 , 69 , 60}, {@"kddk1j_wo0057_05kabochanotiisetto", @"kddk1e_wo0057_05alovelypumpkinteaset"}},
    {{@"むしくいりんご", @"an apple with a worm in it"}, {409 , 452 , 53 , 45}, {@"kddk1j_wo0058_05musikuiringo", @"kddk1e_wo0058_05anapplewithaworminit"}},
    {{@"？のマーク", @"a question mark"}, {631 , 645 , 11 , 24}, {@"kddk1j_wo0059_05hatenanomaaku", @"kddk1e_wo0059_05aquestionmark"}},
    {{@"トランプ", @"a playing card"}, {671 , 497 , 22 , 18}, {@"kddk1j_wo0060_05toranpu", @"kddk1e_wo0060_05aplayingcard"}},
    {{@"よつばのクローバー", @"a four-leaf clover"}, {575 , 75 , 32 , 33}, {@"kddk1j_wo0061_05yotubanokuroobaa", @"kddk1e_wo0061_05afourleafclover"}},
};

// 6ページ
static PlayItemData playItemDataP06[] = { 
    {{@"うさぎ", @"a rabbit"}, {456 , 514 , 62 , 51}, {@"kddk1j_wo0062_06usagi", @"kddk1e_wo0062_06arabbit"}},
    {{@"さる", @"a monkey"}, {560 , 317 , 121 , 65}, {@"kddk1j_wo0063_06saru", @"kddk1e_wo0063_06amonkey"}},
    {{@"へび", @"a snake"}, {267 , 300 , 114 , 93}, {@"kddk1j_wo0064_06hebi", @"kddk1e_wo0064_06asnake"}},
    {{@"くじら", @"a whale"}, {406 , 93 , 56 , 24}, {@"kddk1j_wo0065_06kujira", @"kddk1e_wo0065_06awhale"}},
    {{@"かめ", @"a turtle"}, {424 , 327 , 33 , 43}, {@"kddk1j_wo0066_06kame", @"kddk1e_wo0066_06atuetle"}},
    {{@"ねこ", @"a cat"}, {604 , 128 , 58 , 65}, {@"kddk1j_wo0068_06neko", @"kddk1e_wo0068_06acat"}},
    {{@"ねずみ", @"a mouse"}, {527 , 466 , 64 , 54}, {@"kddk1j_wo0069_06nezumi", @"kddk1e_wo0069_06amouse"}},
    {{@"ペリカン", @"a pelican"}, {271 , 401 , 68 , 54}, {@"kddk1j_wo0070_06perikan", @"kddk1e_wo0070_06apelican"}},
    {{@"きりん", @"a giraffe"}, {338 , 408 , 102 , 148}, {@"kddk1j_wo0072_06kirin", @"kddk1e_wo0072_06agiraffe"}},
    {{@"しか", @"a deer"}, {614 , 442 , 95 , 126}, {@"kddk1j_wo0074_06sika", @"kddk1e_wo0074_06adeer"}},
};

// 7ページ
static PlayItemData playItemDataP07[] = { 
    {{@"つくえ", @"a desk"}, {687 , 597 , 27 , 24}, {@"kddk1j_wo0080_07tukue", @"kddk1e_wo0080_07adesk"}},
    {{@"とびら", @"a door"}, {41 , 470 , 39 , 39 }, {@"kddk1j_wo0081_07tobira", @"kddk1e_wo0081_07adoor"}},
    {{@"かぎ", @"a key"}, {474 , 638 , 85 , 48}, {@"kddk1j_wo0082_07kagi", @"kddk1e_wo0082_07akey"}},
    {{@"とんぼ", @"a dragonfly"}, {784 , 320 , 24 , 19}, {@"kddk1j_wo0084_07tonbo", @"kddk1e_wo0084_07adragonfly"}},
    {{@"どんぐり", @"an acorn"}, {266 , 422 , 41 , 42}, {@"kddk1j_wo0085_07donguri", @"kddk1e_wo0085_07anacorn"}},
    {{@"まつぼっくり", @"a pixxxone"}, {51 , 627 , 80 , 73}, {@"kddk1j_wo0086_07matubokkuri", @"kddk1e_wo0086_07apixxxone"}},
    {{@"くろいきのこ", @"a black mushroom"}, {923 , 526 , 21 , 21}, {@"kddk1j_wo0087_07kuroikinoko", @"kddk1e_wo0087_07ablackmushroom"}},
    {{@"ぼうし", @"a hat"}, {281 , 177 , 35 , 15}, {@"kddk1j_wo0088_07bousi", @"kddk1e_wo0088_07ahat"}},
    {{@"かごのなかのきのこ", @"a mushroom in the basket"}, {876 , 484 , 45 , 45 }, {@"kddk1j_wo0089_07kagononakanokinoko", @"kddk1e_wo0089_07amushroominthebasket"}},
};

// 8ページ
static PlayItemData playItemDataP08[] = { 
    {{@"フォーク", @"a fork"}, {302 , 352 , 35 , 30}, {@"kddk1j_wo0092_08fouku", @"kddk1e_wo0092_08afork"}},
    {{@"ドーナツ", @"a doughnut"}, {116 , 411 , 38 , 27}, {@"kddk1j_wo0093_08dounatu", @"kddk1e_wo0093_08adoughnut"}},
    {{@"とけい", @"a clock"}, {644 , 679 , 54 , 58}, {@"kddk1j_wo0094_08tokei", @"kddk1e_wo0094_08aclock"}},
    {{@"にんぎょひめ", @"a mermaid"}, {800 , 430 , 30 , 34}, {@"kddk1j_wo0095_08ningyohime", @"kddk1e_wo0095_08amermaid"}},
    {{@"きんのりんご", @"a golden apple"}, {832 , 404 , 30 , 30}, {@"kddk1j_wo0096_08kinnoringo", @"kddk1e_wo0096_08agoldenapple"}},
    {{@"かさ", @"an umbrella"}, {784 , 270 , 30 , 107}, {@"kddk1j_wo0097_08kasa", @"kddk1e_wo0097_08anumbrella"}},
    {{@"やかん", @"a tea kettle"}, {240 , 220 , 49 , 53}, {@"kddk1j_wo0098_08yakan", @"kddk1e_wo0098_08ateakettle"}},
    {{@"ろうそく", @"a candle"}, {360 , 270 , 44 , 55}, {@"kddk1j_wo0099_08rousoku", @"kddk1e_wo0099_08acandle"}},
    {{@"たからばこ", @"a treasure chest"}, {684 , 341 , 90 , 80}, {@"kddk1j_wo0100_08takarabako", @"kddk1e_wo0100_08atreasurechest"}},
};

// 9ページ
static PlayItemData playItemDataP09[] = { 
    {{@"さんかくのまど", @"a triangular window "}, {696 , 511 , 29 , 28}, {@"kddk1j_wo0101_09sankakunomado", @"kddk1e_wo0101_09atriangularwindow"}},
    {{@"むらさきのとびら", @"a purple door"}, {272, 562, 24, 34}, {@"kddk1j_wo0102_09murasakinotobira", @"kddk1e_wo0102_09apurpledoor"}},
    {{@"フォーク", @"a fork"}, {63, 485, 31, 12}, {@"kddk1j_wo0103_09fouku", @"kddk1e_wo0103_09afork"}},
    {{@"コーヒーカップ", @"a coffee cup"}, {36, 559, 25, 13}, {@"kddk1j_wo0104_09koohiikappu", @"kddk1e_wo0104_09acoffeecup"}},
    {{@"パラソル", @"a parasol"}, {953, 444, 29, 31}, {@"kddk1j_wo0105_09parasoru", @"kddk1e_wo0105_09aparasol"}},
    {{@"みずたまドア", @"a door with the polka-dots"}, {824, 507, 19, 23}, {@"kddk1j_wo0106_09mizutamadoa", @"kddk1e_wo0106_09adoorwiththepolkadots"}},
    {{@"しましまドア", @"a door with the stripes"}, {387, 418, 22, 37}, {@"kddk1j_wo0107_09simasimadoa", @"kddk1e_wo0107_09adoorwiththestripes"}},
    {{@"ボート", @"a row boat"}, {325, 613, 40, 19}, {@"kddk1j_wo0108_09bouto", @"kddk1e_wo0108_09arowboat"}},
    {{@"ボール", @"a ball"}, {596, 398, 13, 14}, {@"kddk1j_wo0109_09bouru", @"kddk1e_wo0109_09aball"}},
    {{@"ひまわり", @"a sunflower"}, {844, 432, 21, 41}, {@"kddk1j_wo0110_09himawari", @"kddk1e_wo0110_09asunflower"}},
};

// 10ページ
static PlayItemData playItemDataP10[] = { 
    {{@"つき", @"a moon"}, {24 , 473 , 125 , 81}, {@"kddk1j_wo0111_10tuki", @"kddk1e_wo0111_10amoon"}},
    {{@"さかな", @"a fish"}, {227 , 509 , 76 , 50}, {@"kddk1j_wo0114_10sakana", @"kddk1e_wo0114_10afish"}},
    {{@"こうもり", @"a bat"}, {831 , 67 , 43 , 23}, {@"kddk1j_wo0115_10koumori", @"kddk1e_wo0115_10abat"}},
    {{@"あかいくつ", @"a red shoe"}, {413 , 60 , 26 , 16}, {@"kddk1j_wo0116_10akaikutu", @"kddk1e_wo0116_10aredshoe"}},
    {{@"りんご", @"an apple"}, {349 , 481 , 58 , 34}, {@"kddk1j_wo0118_10ringo", @"kddk1e_wo0118_10anapple"}},
    {{@"じゃぐち", @"a water spigot"}, {564 , 426 , 26 , 31}, {@"kddk1j_wo0121_10jaguti", @"kddk1e_wo0121_10awaterspigot"}},
    {{@"ＵＦＯ", @"an UFO"}, {341 , 169 , 20 , 17}, {@"kddk1j_wo0122_10yufo", @"kddk1e_wo0122_10anufo"}},
    {{@"ひこうき", @"an airplane"}, {626 , 204 , 40 , 34}, {@"kddk1j_wo0123_10hikouki", @"kddk1e_wo0123_10anairplane"}},
    {{@"くま", @"a bear"}, {581 , 378 , 38 , 41}, {@"kddk1j_wo0124_10kuma", @"kddk1e_wo0124_10abear"}},
};

// 11ページ
static PlayItemData playItemDataP11[] = { 
    {{@"めんどり", @"a hen"}, {259 , 100 , 73 , 53}, {@"kddk1j_wo0125_11mendori", @"kddk1e_wo0125_11ahen"}},
    {{@"あわだてき", @"a wire whisk"}, {227 , 366 , 91 , 86}, {@"kddk1j_wo0126_11awadateki", @"kddk1e_wo0126_11awirewhisk"}},
    {{@"とけい", @"a clock"}, {208 , 453 , 55 , 58}, {@"kddk1j_wo0127_11tokei", @"kddk1e_wo0127_11aclock"}},
    {{@"ショベルカー", @"a steam shovel"}, {599 , 632 , 130 , 94}, {@"kddk1j_wo0128_11shoberukaa", @"kddk1e_wo0128_11asteamshovel"}},
    {{@"しかくいいちご", @"a square strawberry"}, {507 , 489 , 33 , 34}, {@"kddk1j_wo0129_11sikakuiitigo", @"kddk1e_wo0129_11asquarestrawberry"}},
    {{@"さくらんぼ", @"cherries"}, {108 , 263 , 14 , 14}, {@"kddk1j_wo0130_11sakuranbo", @"kddk1e_wo0130_11cherries"}},
    {{@"カスタネット", @"a pair of castanets "}, {740 , 601 , 22 , 21}, {@"kddk1j_wo0131_11kasutanetto", @"kddk1e_wo0131_11apairofcastanets"}},
    {{@"らっぱ", @"a trumpet"}, {622 , 511 , 53 , 64}, {@"kddk1j_wo0132_11rappa", @"kddk1e_wo0132_11atrumpet"}},
    {{@"われたたまご", @"a broken egg"}, {130, 404, 34, 43}, {@"kddk1j_wo0133_11waretatamago", @"kddk1e_wo0133_11abrokenegg"}},
    {{@"なべつかみ", @"a potholder"}, {851 , 368 , 51 , 44}, {@"kddk1j_wo0135_11nabetukami", @"kddk1e_wo0135_11apotholder"}},
};

// 12ページ
static PlayItemData playItemDataP12[] = { 
    {{@"ちきゅうぎ", @"a globe"}, {384 , 316 , 55 , 99}, {@"kddk1j_wo0139_12tikyuugi", @"kddk1e_wo0139_12aglobe"}},
    {{@"けんびきょう", @"a microscope"}, {442 , 394 , 35 , 95}, {@"kddk1j_wo0140_12kenbikyou", @"kddk1e_wo0140_12amicroscope"}},
    {{@"あおえんぴつ", @"a blue pencil"}, {396 , 419 , 16 , 68}, {@"kddk1j_wo0141_12aoenpitu", @"kddk1e_wo0141_12abluepencil"}},
    {{@"さんかくじょうぎ", @"a triangle"}, {687 , 21 , 63 , 55}, {@"kddk1j_wo0142_12sankakujougi", @"kddk1e_wo0142_12atriangle"}},
    {{@"りんご", @"an apple"}, {903 , 658 , 43 , 49}, {@"kddk1j_wo0143_12ringo", @"kddk1e_wo0143_12anapple"}},
    {{@"バナナ", @"a banana"}, {671 , 365 , 34 , 16}, {@"kddk1j_wo0144_12banana", @"kddk1e_wo0144_12abanana"}},
    {{@"みどりのながぐつ", @"a pair of green rain boots"}, {111 , 317 , 40 , 35}, {@"kddk1j_wo0145_12midorinonagagutu", @"kddk1e_wo0145_12apairofgreenrainboots"}},
    {{@"ピアノ", @"a piano"}, {1 , 382 , 160 , 238}, {@"kddk1j_wo0147_12piano", @"kddk1e_wo0147_12apiano"}},
    {{@"かめ", @"a turtle"}, {252 , 617 , 43 , 33}, {@"kddk1j_wo0148_12kame", @"kddk1e_wo0148_12aturtle"}},
    {{@"リボン", @"a ribbon"}, {604 , 184 , 26 , 17}, {@"kddk1j_wo0149_12ribon", @"kddk1e_wo0149_12aribbon"}},
};

// 13ページ
static PlayItemData playItemDataP13[] = { 
    {{@"ふくろう", @"an owl"}, {701 , 504 , 86 , 52}, {@"kddk1j_wo0150_13hukurou", @"kddk1e_wo0150_13anowl"}},
    {{@"がいこつ", @"a skeleton"}, {34 , 380 , 176 , 159}, {@"kddk1j_wo0152_13gaikotu", @"kddk1e_wo0152_13askeleton"}},
    {{@"くるま", @"a car"}, {301 , 251 , 25 , 26}, {@"kddk1j_wo0154_13kuruma", @"kddk1e_wo0154_13acar"}},
    {{@"かさ", @"an umbrella"}, {935 , 387 , 49 , 50}, {@"kddk1j_wo0155_13kasa", @"kddk1e_wo0155_13anumbrella"}},
    {{@"えんぴつ", @"a pencil"}, {786 , 141 , 45 , 54}, {@"kddk1j_wo0156_13enpitu", @"kddk1e_wo0156_13apencil"}},
    {{@"けんびきょう", @"a microscope"}, {521 , 643 , 39 , 62}, {@"kddk1j_wo0157_13kenbikyou", @"kddk1e_wo0157_13amicroscope"}},
    {{@"あかいきのこ", @"a red mushroom"}, {286 , 595 , 34 , 35}, {@"kddk1j_wo0158_13akaikinoko", @"kddk1e_wo0158_13aredmushroom"}},
    {{@"とけい", @"a clock"}, {862 , 385 , 69 , 104}, {@"kddk1j_wo0159_13tokei", @"kddk1e_wo0159_13aclock"}},
    {{@"とりかご", @"a bird cage"}, {706 , 596 , 53 , 80}, {@"kddk1j_wo0160_13torikago", @"kddk1e_wo0160_13abirdcage"}},
    {{@"てんとうむし", @"a ladybug"}, {112 , 315 , 15 , 17}, {@"kddk1j_wo0161_13tentoumusi", @"kddk1e_wo0161_13aladybug"}},
};

// 14ページ
static PlayItemData playItemDataP14[] = { 
    {{@"5", @"the number of five"}, {201 , 415 , 78 , 54}, {@"kddk1j_wo0162_14go", @"kddk1e_wo0162_14thenumberoffive"}},
    {{@"りす", @"the kissing squirrels"}, {825 , 280 , 77 , 126}, {@"kddk1j_wo0163_14risu", @"kddk1e_wo0163_14thekissingsquirrels"}},
    {{@"かえる", @"a frog"}, {725 , 1 , 103 , 104}, {@"kddk1j_wo0164_14kaeru", @"kddk1e_wo0164_14afrog"}},
    {{@"うさぎ", @"a rabbit"}, {228 , 236 , 52 , 62}, {@"kddk1j_wo0166_14usagi", @"kddk1e_wo0166_14arabbit"}},
    {{@"きのこ", @"a mushroom"}, {496 , 458 , 32 , 42}, {@"kddk1j_wo0167_14kinoko", @"kddk1e_wo0167_14amushroom"}},
    {{@"ねずみ", @"a mouse"}, {863 , 198 , 70 , 39}, {@"kddk1j_wo0168_14nezumi", @"kddk1e_wo0168_14amouse"}},
    {{@"パラソル", @"a parasol"}, {253 , 155 , 58 , 77}, {@"kddk1j_wo0169_14parasoru", @"kddk1e_wo0169_14aparasol"}},
    {{@"ボール", @"a ball"}, {160 , 281 , 66 , 74}, {@"kddk1j_wo0170_14bouru", @"kddk1e_wo0170_14aball"}},
    {{@"カスタネット", @"a pair of castanets"}, {360 , 414 , 30 , 30}, {@"kddk1j_wo0171_14kasutanetto", @"kddk1e_wo0171_14apairofcastanets"}},
    {{@"かめ", @"a turtle"}, {740 , 468 , 85 , 86}, {@"kddk1j_wo0172_14kame", @"kddk1e_wo0172_14aturtle"}},
};

// 15ページ
static PlayItemData playItemDataP15[] = { 
    {{@"Ａ", @"a striped letter Ａ"}, {32 , 557 , 36 , 44}, {@"kddk1j_wo0173_15a", @"kddk1e_wo0173_15astripedlettera"}},
    {{@"あおいとり", @"a blue bird"}, {161 , 51 , 36 , 25}, {@"kddk1j_wo0174_15aoitori", @"kddk1e_wo0174_15abluebird"}},
    {{@"ヘリコプター", @"a helicopter"}, {36 , 281 , 40 , 27}, {@"kddk1j_wo0175_15herikoputaa", @"kddk1e_wo0175_15ahelicopter"}},
    {{@"ハンバーガー", @"a hamburger"}, {613 , 363 , 41 , 68}, {@"kddk1j_wo0176_15hanbaagaa", @"kddk1e_wo0176_15ahamburger"}},
    {{@"きしゃ", @"a train"}, {581 , 504 , 80 , 51}, {@"kddk1j_wo0178_15kisha", @"kddk1e_wo0178_15atrain"}},
    {{@"きんのりんご", @"a golden apple"}, {703 , 340 , 25 , 26}, {@"kddk1j_wo0179_15kinnoringo", @"kddk1e_wo0179_15agoldenapple"}},
    {{@"ひまわり", @"a sunflower"}, {535 , 203 , 39 , 104}, {@"kddk1j_wo0180_15himawari", @"kddk1e_wo0180_15asunflower"}},
    {{@"なべつかみ", @"a potholder"}, {659 , 178 , 41 , 37}, {@"kddk1j_wo0182_15nabetukami", @"kddk1e_wo0182_15apotholder"}},
    {{@"ちきゅうぎ", @"a globe"}, {454 , 255 , 53 , 85}, {@"kddk1j_wo0183_15tikyuugi", @"kddk1e_wo0183_15aglobe"}},
    {{@"いろえんぴつ", @"a colored pencil"}, {336 , 370 , 44 , 11}, {@"kddk1j_wo0184_15iroenpitu", @"kddk1e_wo0184_15acoloredpencil"}},
};

// ページ毎アイテムデータ
static PlayPageData playPageData[PAGE_NUM] = {
    {playItemDataP01, numBerOfItem(playItemDataP01)},
    {playItemDataP02, numBerOfItem(playItemDataP02)},
    {playItemDataP03, numBerOfItem(playItemDataP03)},
    {playItemDataP04, numBerOfItem(playItemDataP04)},
    {playItemDataP05, numBerOfItem(playItemDataP05)},
    {playItemDataP06, numBerOfItem(playItemDataP06)},
    {playItemDataP07, numBerOfItem(playItemDataP07)},
    {playItemDataP08, numBerOfItem(playItemDataP08)},
    {playItemDataP09, numBerOfItem(playItemDataP09)},
    {playItemDataP10, numBerOfItem(playItemDataP10)},
    {playItemDataP11, numBerOfItem(playItemDataP11)},
    {playItemDataP12, numBerOfItem(playItemDataP12)},
    {playItemDataP13, numBerOfItem(playItemDataP13)},
    {playItemDataP14, numBerOfItem(playItemDataP14)},
    {playItemDataP15, numBerOfItem(playItemDataP15)}
};

// よむモード（中心座標xy,倍率）
static ReadZoomData readZoomData[PAGE_NUM] = {
    {{397, 158}, 2.56},  // 1ページ
    {{333, 494}, 2.56},  // 2ページ
    {{815, 156}, 2.56},  // 3ページ
    {{249, 186}, 2.08},  // 4ページ
    {{216, 370}, 2.56},  // 5ページ
    {{150, 648}, 3.51},  // 6ページ
    {{833, 192}, 2.75},  // 7ページ
    {{515, 215}, 2.11},  // 8ページ
    {{381, 120}, 3.53},  // 9ページ
    {{822, 604}, 2.56},  // 10ページ
    {{556, 337}, 2.10},  // 11ページ
    {{483, 631}, 3.20},  // 12ページ
    {{317, 158}, 2.56},  // 13ページ
    {{770, 580}, 2.40},  // 14ページ
    {{419, 347}, 3.40},  // 15ページ
};

DataManager *dataManager;


@implementation DataManager

@synthesize lang, readContinueFlag, readCurrentPage, playContinueFlag, playCurrentPage;

- (id)init
{
    self = [super init];
    if (self != nil) {
        [self readUserDefault];
    }
    return self;
}

- (void)readUserDefault
{
    NSUserDefaults *ud = [NSUserDefaults standardUserDefaults];

    dataExistFlag = [ud boolForKey:@"DataExistFlag"];
    lang = [ud integerForKey:@"Lang"];
    readContinueFlag = [ud boolForKey:@"ReadContinueFlag"];
    readCurrentPage = [ud integerForKey:@"ReadCurrentPage"];
    playContinueFlag = [ud integerForKey:@"PlayContinueFlag"];
    playCurrentPage = [ud integerForKey:@"PlayCurrentPage"];
    NSData *data = [ud objectForKey:@"PlayFindItem"];
    if (data == nil)
        [self initPlayFindData];
    else
        [data getBytes:playFindItem length:sizeof(playFindItem)];

    // 初回は本体のロケール設定に合わせる
    if (!dataExistFlag) {
        NSArray *languages = [NSLocale preferredLanguages];
        lang = [[languages objectAtIndex:0] isEqualToString: @"ja"] ? LANG_JPN : LANG_ENG;
    }
}

- (void)initPlayFindData
{
    // 各ページ毎に６つ選択
    for (int i = 0; i < PAGE_NUM; i++)
        [self initPlayFindPage:i];
}

- (void)initPlayFindPage:(int)page
{
    int i, j, r;
    
    // 乱数初期化
	srand(time(NULL));

    for (i = 0; i < FIND_ITEM_NUM; i++) {
        for (;;) {
            r = rand() % playPageData[page].itemNum;
            for (j = 0; j < i; j++) {
                if (r == playFindItem[page][j].itemIndex)
                    break;
            }
            if (j == i)
                break;
        }
        playFindItem[page][i].itemIndex = r;
        playFindItem[page][i].findFlag = NO;
    }
}

- (void)saveData
{
    dataExistFlag = YES;
    NSUserDefaults *ud = [NSUserDefaults standardUserDefaults];
    [ud setBool:dataExistFlag forKey:@"DataExistFlag"];
    [ud setInteger:lang forKey:@"Lang"];
    [ud setBool:readContinueFlag forKey:@"ReadContinueFlag"];
    [ud setInteger:readCurrentPage forKey:@"ReadCurrentPage"];
    [ud setBool:playContinueFlag forKey:@"PlayContinueFlag"];
    [ud setInteger:playCurrentPage forKey:@"PlayCurrentPage"];
    [ud setObject:[NSData dataWithBytes:playFindItem length:sizeof(playFindItem)] forKey:@"PlayFindItem"];
}

- (void)clearData
{
    readContinueFlag = NO;
    readCurrentPage = 0;
    playContinueFlag = NO;
    playCurrentPage = 0;
    [self initPlayFindData];
    [self saveData];
}

- (NSString *)getBgMovieFile:(int)page
{
    return [NSString stringWithFormat:@"kddk1a_loop_p%02d", page + 1];
}

- (NSString *)getBgImageFile:(int)page
{
    return [NSString stringWithFormat:@"kddk1a_loop_p%02d_stl", page + 1];
}

- (NSString *)getBgSoundFile:(int)page
{
    return [NSString stringWithFormat:@"kddk1a_loop_p%02d", page + 1];
}

- (NSString *)getCompleteMovieFile:(int)page
{
    page = 8;
    return [NSString stringWithFormat:@"doko_%02dbg_complete", page + 1];
}

- (PlayFindItem *)getPlayFindItem:(int)page
{
    return playFindItem[page];
}

- (NSString *)getPlayItemName:(int)page:(int)index
{
    return playPageData[page].itemData[index].itemName[lang];
}

- (CGRect)getPlayAtariRect:(int)page:(int)index
{
    return playPageData[page].itemData[index].atariRect;
}

- (NSString *)getPlaySoundFile:(int)page:(int)index
{
    return playPageData[page].itemData[index].soundFile[lang];    
}

- (NSString *)getReadSentenceSoundFile:(int)page:(int)viewIndex:(int)lineIndex:(int)sentenceIndex
{
    return [NSString stringWithFormat:@"kddk1%s_re%02d_%d%s%d", lang == LANG_JPN ? "j" : "e", page + 1, viewIndex + 1, lineIndex == 0 ? "a" : "b", sentenceIndex + 1];
}

- (NSString *)getReadTextFile:(int)page
{
    return [NSString stringWithFormat:@"kddk1%s_re%02d", lang == LANG_JPN ? "j" : "e", page + 1];    
}

- (CGPoint)getReadZoomPoint:(int)page
{
    return readZoomData[page].point;
}

- (float)getReadZoomRatio:(int)page
{
    return readZoomData[page].ratio;
}

- (UIImage *)getImage:(NSString *)jpnFileName:(NSString *)engFileName
{
    NSString *path = [[NSBundle mainBundle] pathForResource:lang == LANG_JPN ? jpnFileName : engFileName ofType:@"png"];
    return [UIImage imageWithContentsOfFile:path];
}

@end
