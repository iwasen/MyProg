//
//  DataManager.h
//  Doko
//
//  Created by aizawa on 11/05/01.
//  Copyright 2011 TRC. All rights reserved.
//

#import <Foundation/Foundation.h>

#define SCREEN_WIDTH        1024    // 画面幅
#define SCREEN_HEIGHT       768     // 画面高さ
#define LANG_JPN            0       // 日本語
#define LANG_ENG            1       // 英語
#define LANG_NUM            2       // 言語数
#define PAGE_NUM            15      // ページ数
#define FIND_ITEM_NUM       6       // 見つけるアイテム数

typedef struct {
    NSString *itemName[LANG_NUM];
    CGRect atariRect;
    NSString *soundFile[LANG_NUM];
} PlayItemData;

typedef struct {
    PlayItemData *itemData;
    int itemNum;
} PlayPageData;

typedef struct {
    int itemIndex;
    bool findFlag;
} PlayFindItem;

typedef struct {
    CGPoint point;
    float ratio;
} ReadZoomData;

@interface DataManager : NSObject {
    bool dataExistFlag;
    int lang;
    bool readContinueFlag;
    int readCurrentPage;
    bool playContinueFlag;
    int playCurrentPage;
    PlayFindItem playFindItem[PAGE_NUM][FIND_ITEM_NUM];    
}

@property (assign, nonatomic) int lang;
@property (assign, nonatomic) bool readContinueFlag;
@property (assign, nonatomic) int readCurrentPage;
@property (assign, nonatomic) bool playContinueFlag;
@property (assign, nonatomic) int playCurrentPage;

- (void)readUserDefault;
- (void)initPlayFindData;
- (void)initPlayFindPage:(int)page;
- (void)saveData;
- (void)clearData;
- (NSString *)getBgMovieFile:(int)page;
- (NSString *)getBgImageFile:(int)page;
- (NSString *)getBgSoundFile:(int)page;
- (NSString *)getCompleteMovieFile:(int)page;
- (PlayFindItem *)getPlayFindItem:(int)page;
- (NSString *)getPlayItemName:(int)page:(int)index;
- (CGRect)getPlayAtariRect:(int)page:(int)index;
- (NSString *)getPlaySoundFile:(int)page:(int)index;
- (NSString *)getReadSentenceSoundFile:(int)page:(int)viewIndex:(int)lineIndex:(int)sentenceIndex;
- (NSString *)getReadTextFile:(int)page;
- (CGPoint)getReadZoomPoint:(int)page;
- (float)getReadZoomRatio:(int)page;
- (UIImage *)getImage:(NSString *)jpnFileName:(NSString *)engFileName;

@end

extern DataManager *dataManager;
