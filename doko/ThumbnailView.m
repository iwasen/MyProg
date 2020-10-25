//
//  ThumbnailView.m
//  Doko
//
//  Created by 相沢 伸一 on 11/05/06.
//  Copyright 2011 TRC. All rights reserved.
//

#import "ThumbnailView.h"
#import "IndexView.h"


@implementation ThumbnailView

@synthesize page;

// サムネイルタッチ処理
- (void)touchesBegan:(NSSet *)touches withEvent:(UIEvent *)event
{
    // 親ビューに通知
    IndexView *indexView = (IndexView *)self.superview;
    [indexView touchThumbnail:page];
}

@end
