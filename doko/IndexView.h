//
//  IndexView.h
//  Doko
//
//  Created by 相沢 伸一 on 11/05/06.
//  Copyright 2011 TRC. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "ThumbnailView.h"
#import "DataManager.h"

@protocol IndexViewDelegete
- (void)selectPage:(int)page;
@end

@interface IndexView : UIView {
    NSObject <IndexViewDelegete> *delegate;
    ThumbnailView *thumbnailViews[PAGE_NUM];
    bool openFlag;
    SEL endHideIndexMethod;
    int currentPage;
    bool animationFlag;
    SEL endAnimationSel;
}

@property (assign, nonatomic) id delegate;
@property (assign, nonatomic) bool openFlag;

- (void)touchThumbnail:(int)page;
- (void)openIndex:(int)page:(bool)animation;
- (void)endOpenIndex;
- (void)closeIndex:(bool)animation:(SEL)endMethod;
- (void)endCloseIndex;
- (UIImageView *)getThumbnailView:(int)page;
- (void)setThumbnailsPosition:(int)page;
- (void)setCurrentPage:(int)page;

@end
