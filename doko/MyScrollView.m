//
//  PlayScrollView.m
//  Doko
//
//  Created by 相沢 伸一 on 11/04/29.
//  Copyright 2011 TRC. All rights reserved.
//

#import "MyScrollView.h"
#import "DataManager.h"

@implementation MyScrollView

- (void)touchesBegan:(NSSet *)touches withEvent:(UIEvent *)event
{
    [[self nextResponder] touchesBegan:touches withEvent:event];
}

@end
