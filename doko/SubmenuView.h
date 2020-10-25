//
//  SubmenuView.h
//  Doko
//
//  Created by aizawa on 11/05/12.
//  Copyright 2011 TRC. All rights reserved.
//

#import <UIKit/UIKit.h>


@interface SubmenuView : UIView {
    bool openFlag;
}

@property (assign, nonatomic) bool openFlag;

- (void)openSubmenu:(bool)animation;
- (void)endOpenSubmenu;
- (void)closeSubmenu:(bool)animation;

@end
