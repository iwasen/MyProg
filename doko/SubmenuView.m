//
//  SubmenuView.m
//  Doko
//
//  Created by aizawa on 11/05/12.
//  Copyright 2011 TRC. All rights reserved.
//

#import "SubmenuView.h"
#import "DataManager.h"

#define INDEX_HEIGHT                70

@implementation SubmenuView

@synthesize openFlag;

- (id)initWithFrame:(CGRect)frame
{
    self = [super initWithFrame:frame];
    if (self) {
        // Initialization code
    }
    return self;
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

// サブメニューを表示
- (void)openSubmenu:(bool)animation
{
    openFlag = YES;

    CGSize indexSize = self.frame.size;
    if (animation) {
        self.frame = CGRectMake(0, SCREEN_HEIGHT - (indexSize.height - INDEX_HEIGHT), indexSize.width, indexSize.height);
        [UIView animateWithDuration:0.5
                         animations:^ {
                             self.frame = CGRectMake(0, SCREEN_HEIGHT - indexSize.height - 20, indexSize.width, indexSize.height);                             
                         }
                         completion:^ (BOOL finished) {
                             [self endOpenSubmenu];
                         }];
    } else
        self.frame = CGRectMake(0, SCREEN_HEIGHT - indexSize.height, indexSize.width, indexSize.height);
}

// サブメニュー表示完了
- (void)endOpenSubmenu
{
    CGSize indexSize = self.frame.size;
    [UIView animateWithDuration:0.2
                     animations:^ {
                         self.frame = CGRectMake(0, SCREEN_HEIGHT - indexSize.height, indexSize.width, indexSize.height);                         
                     }];
}

// サブメニューを閉じる
- (void)closeSubmenu:(bool)animation
{
    openFlag = NO;
    
    CGSize indexSize = self.frame.size;
    if (animation) {
        [UIView animateWithDuration:0.5
                         animations:^ {
                             self.frame = CGRectMake(0, SCREEN_HEIGHT - (indexSize.height - INDEX_HEIGHT), indexSize.width, indexSize.height);                             
                         }];
    } else
        self.frame = CGRectMake(0, SCREEN_HEIGHT - (indexSize.height - INDEX_HEIGHT), indexSize.width, indexSize.height);
}

@end
