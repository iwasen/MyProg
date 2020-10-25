//
//  Index.m
//  Doko
//
//  Created by 相沢 伸一 on 11/05/06.
//  Copyright 2011 TRC. All rights reserved.
//

#import "IndexView.h"
#import "DataManager.h"

#define THUMBNAIL_WIDTH             50
#define THUMBNAIL_HEIGHT            50
#define SELECTED_THUMBNAIL_WIDTH    75
#define SELECTED_THUMBNAIL_HEIGHT   75
#define THUMBNAIL_SPACE             10
#define THUMBNAIL_OFFSET            80
#define INDEX_HEIGHT                70

@implementation IndexView

@synthesize delegate, openFlag;

- (id)initWithFrame:(CGRect)frame
{
    self = [super initWithFrame:frame];
    if (self) {
        // Initialization code
    }
    return self;
}

- (void)awakeFromNib
{
    // サムネイルを作成
    for (int i = 0; i < PAGE_NUM; i++) {
        NSString *fileName = [NSString stringWithFormat:@"kddk1a_subber_thum_%02d", i + 1];
        NSString *path = [[NSBundle mainBundle] pathForResource:fileName ofType:@"png"];
        ThumbnailView *view = [[ThumbnailView alloc] initWithImage:[UIImage imageWithContentsOfFile:path]];
        view.page = i;
        view.userInteractionEnabled = YES;
        [self addSubview:view];
        thumbnailViews[i] = view;
        [view release];
    }
    [self setThumbnailsPosition:-1];
}

/*
// Only override drawRect: if you perform custom drawing.
// An empty implementation adversely affects performance during animation.
- (void)drawRect:(CGRect)rect
{
    // Drawing code
}
*/

- (void)dealloc
{
    [super dealloc];
}

// サムネイルタッチ処理
- (void)touchThumbnail:(int)page
{
    if (page == currentPage)
        [delegate selectPage:page];
    else
        [self setCurrentPage:page];
}

// インデックス表示処理
- (void)openIndex:(int)page:(bool)animation
{
    if (animationFlag)
        return;
    
    openFlag = YES;
    
    [self setThumbnailsPosition:page];

    self.hidden = NO;

    CGSize indexSize = self.frame.size;
    if (animation) {
        animationFlag = YES;
        if (currentPage != -1) {
            CGRect rect = thumbnailViews[currentPage].frame;
            thumbnailViews[currentPage].frame = CGRectMake(rect.origin.x, rect.origin.y + (SELECTED_THUMBNAIL_HEIGHT - THUMBNAIL_HEIGHT), rect.size.width, rect.size.height);
        }
        self.frame = CGRectMake(0, SCREEN_HEIGHT - (indexSize.height - INDEX_HEIGHT), indexSize.width, indexSize.height);
        [UIView animateWithDuration:0.5
                         animations:^ {
                             self.frame = CGRectMake(0, SCREEN_HEIGHT - indexSize.height - 20, indexSize.width, indexSize.height);
                             if (currentPage != -1) {
                                 CGRect rect = thumbnailViews[currentPage].frame;
                                 thumbnailViews[currentPage].frame = CGRectMake(rect.origin.x, rect.origin.y - (SELECTED_THUMBNAIL_HEIGHT - THUMBNAIL_HEIGHT), rect.size.width, rect.size.height);
                             }
                             
                         }
                         completion:^ (BOOL finished) {
                             [self endOpenIndex];
                         }];
    } else
        self.frame = CGRectMake(0, SCREEN_HEIGHT - indexSize.height, indexSize.width, indexSize.height);
}

// インデックス表示完了処理
- (void)endOpenIndex
{
    CGSize indexSize = self.frame.size;
    [UIView animateWithDuration:0.2 
                     animations:^ {
                         self.frame = CGRectMake(0, SCREEN_HEIGHT - indexSize.height, indexSize.width, indexSize.height);                         
                     }
                     completion:^ (BOOL finished) {
                         animationFlag = NO;
                     }];
}

// インデックス消去処理
- (void)closeIndex:(bool)animation:(SEL)endMethod
{
    if (animationFlag)
        return;
    
    openFlag = NO;
    endHideIndexMethod = endMethod;
    endAnimationSel = endMethod;

    CGSize indexSize = self.frame.size;
    if (animation) {
        animationFlag = YES;
        [UIView animateWithDuration:0.5
                         animations:^ {
                             self.frame = CGRectMake(0, SCREEN_HEIGHT - (indexSize.height - INDEX_HEIGHT), indexSize.width, indexSize.height);
                             if (currentPage != -1) {
                                 CGRect rect = thumbnailViews[currentPage].frame;
                                 thumbnailViews[currentPage].frame = CGRectMake(rect.origin.x, rect.origin.y + (SELECTED_THUMBNAIL_HEIGHT - THUMBNAIL_HEIGHT), rect.size.width, rect.size.height);
                             }                             
                         }
                         completion:^ (BOOL finished) {
                             [self endCloseIndex];
                         }];
    } else
        self.frame = CGRectMake(0, SCREEN_HEIGHT - (indexSize.height - INDEX_HEIGHT), indexSize.width, indexSize.height);
}

- (void)endCloseIndex
{
    animationFlag = NO;
    if (delegate != nil && endAnimationSel != nil)
        [delegate performSelector:endAnimationSel];
}

// ページに対応したサムネイルを取得
- (UIImageView *)getThumbnailView:(int)page
{
    return thumbnailViews[page];
}

// サムネイルの表示位置を設定
- (void)setThumbnailsPosition:(int)page
{
    currentPage = page;
    
    CGSize indexSize = self.frame.size;
    int offset = (page == -1) ? (SCREEN_WIDTH - (THUMBNAIL_WIDTH * PAGE_NUM + THUMBNAIL_SPACE * (PAGE_NUM - 1))) / 2 : THUMBNAIL_OFFSET;
    for (int i = 0; i < PAGE_NUM; i++) {
        ThumbnailView *thumbnail = thumbnailViews[i];
        if (thumbnail.page == page) {
            thumbnail.frame = CGRectMake(offset, indexSize.height - SELECTED_THUMBNAIL_HEIGHT - 10, SELECTED_THUMBNAIL_WIDTH, SELECTED_THUMBNAIL_HEIGHT);
            offset += SELECTED_THUMBNAIL_WIDTH + THUMBNAIL_SPACE;
        } else {
            thumbnail.frame = CGRectMake(offset, indexSize.height - THUMBNAIL_HEIGHT - 10, THUMBNAIL_WIDTH, THUMBNAIL_HEIGHT);
            offset += THUMBNAIL_WIDTH + THUMBNAIL_SPACE;
        }   
    }    
}

// 現在ページを設定
- (void)setCurrentPage:(int)page
{
    [UIView animateWithDuration:0.2
                     animations:^ {
                         [self setThumbnailsPosition:page];                         
                     }];
}

@end
