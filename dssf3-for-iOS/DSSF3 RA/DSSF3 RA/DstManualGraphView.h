//
//  DstManualGraphView.h
//  DSSF3
//
//  Created by 相沢伸一 on 2015/06/11.
//  Copyright (c) 2015年 xxxx. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "DstDataView.h"

@protocol DstDrawManualGraphDelegate

- (void)drawGraphScale:(CGContextRef)context;
- (void)drawGraphData:(CGContextRef)context;

@end

@interface DstManualGraphView : UIView <DstDataViewDelegate>

@property (nonatomic, weak) IBOutlet id<DstDrawManualGraphDelegate> delegate;

- (void)initialize;
- (void)updateGraph;
- (void)updateData;
- (void)drawScale:(CGContextRef)context :(int)nMaxHxxxonics;
- (void)drawData:(CGContextRef)context :(const float *)pLeftDst :(const float *)pRightDst :(int)nChannel :(int)nMaxHxxxonics;

@end
