//
//  RootViewController.h
//  DSSF3 SA
//
//  Created by 相沢伸一 on 2015/07/13.
//  Copyright (c) 2015年 TRC. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface RootViewController : UINavigationController

- (void)showGraphView:(int)folderId :(int)dataId;
- (void)showCalcView:(int)folderId :(int)dataId;

@end
