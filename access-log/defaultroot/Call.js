// 条件検索 月選択
function Form1Submit ( theForm, servlet )
{
  if(ymdnumerical (  theForm.dateFrom.value.substring(0,4),
                     theForm.dateFrom.value.substring(5,7),
                     theForm.dateFrom.value.substring(8,10),
                     theForm.dateTo.value.substring(0,4),
                     theForm.dateTo.value.substring(5,7),
                     theForm.dateTo.value.substring(8,10),
                     "月の順番が" )

  )return false;

  theForm.attribute[0].value=document.attribute.attribute[0].value;
  theForm.attribute[1].value=document.attribute.attribute[1].value;
  theForm.attribute[2].value=document.attribute.attribute[2].value;
  theForm.attribute[3].value=document.attribute.attribute[3].value;
  theForm.attribute[4].value=document.attribute.attribute[4].value;

  //日付・フィルター以外の値はHTMLにうめこまれた値を利用（HISTORY　BACK対策）
  theForm.corner.value=document.postval.corner.value;
  theForm.order.value=document.postval.order.value;
  theForm.cornerdepth.value=document.postval.cornerdepth.value;
  theForm.category.value=document.postval.category.value;
  theForm.page.value=document.postval.page.value;



  //alert(theForm.attribute[0].value);
  if ( postForm ( theForm,servlet ) ) return false;
  return true;
}

// 条件検索 週選択
function Form2Submit ( theForm, servlet )
{
//alert(theForm.dateFrom.value);
//alert(theForm.table.value);
  if(ymdnumerical (  theForm.dateFrom.value.substring(0,4),
                     theForm.dateFrom.value.substring(5,7),
                     theForm.dateFrom.value.substring(8,10),
                     theForm.dateTo.value.substring(0,4),
                     theForm.dateTo.value.substring(5,7),
                     theForm.dateTo.value.substring(8,10),
                     "週の順番が" )

  )return false;
  theForm.attribute[0].value=document.attribute.attribute[0].value;
  theForm.attribute[1].value=document.attribute.attribute[1].value;
  theForm.attribute[2].value=document.attribute.attribute[2].value;
  theForm.attribute[3].value=document.attribute.attribute[3].value;
  theForm.attribute[4].value=document.attribute.attribute[4].value;

  //日付・フィルター以外の値はHTMLにうめこまれた値を利用（HISTORY　BACK対策）
  theForm.corner.value=document.postval.corner.value;
  theForm.order.value=document.postval.order.value;
  theForm.cornerdepth.value=document.postval.cornerdepth.value;
  theForm.category.value=document.postval.category.value;
  theForm.page.value=document.postval.page.value;

//  alert(theForm.WEEKSELECT.value);
//  alert(theForm.WEEKSELECT.value.indexOf(":"));
//  theForm.dateFrom.value=theForm.WEEKSELECT.value.substring(0,theForm.WEEKSELECT.value.indexOf(":"));
//  theForm.dateTo.value=theForm.WEEKSELECT.value.substring(theForm.WEEKSELECT.value.indexOf(":")+1);
  if ( postForm ( theForm,servlet ) ) return false;
  return true;
}

// 条件検索 日選択
function Form3Submit ( theForm, servlet )
{
//alert(theForm.fromDateY.value);
  if(!isValidateDate(theForm.fromDateY, theForm.fromDateM, theForm.fromDateD, true, true)) return false;
  if(!isValidateDate(theForm.toDateY, theForm.toDateM, theForm.toDateD, true, true)) return false;
  if(ymdnumerical ( theForm.fromDateY.value, theForm.fromDateM.value, theForm.fromDateD.value,
                      theForm.toDateY.value, theForm.toDateM.value, theForm.toDateD.value,
                     "日付の順番が" )

  )return false;
  theForm.attribute[0].value=document.attribute.attribute[0].value;
  theForm.attribute[1].value=document.attribute.attribute[1].value;
  theForm.attribute[2].value=document.attribute.attribute[2].value;
  theForm.attribute[3].value=document.attribute.attribute[3].value;
  theForm.attribute[4].value=document.attribute.attribute[4].value;

  //日付・フィルター以外の値はHTMLにうめこまれた値を利用（HISTORY　BACK対策）
  theForm.corner.value=document.postval.corner.value;
  theForm.order.value=document.postval.order.value;
  theForm.cornerdepth.value=document.postval.cornerdepth.value;
  theForm.category.value=document.postval.category.value;
  theForm.page.value=document.postval.page.value;

  theForm.dateFrom.value=theForm.fromDateY.value + "/" + theForm.fromDateM.value +"/"+ theForm.fromDateD.value;
  theForm.dateTo.value=theForm.toDateY.value + "/" + theForm.toDateM.value +"/"+ theForm.toDateD.value;
//  alert(theForm.dateFrom.value);
//  alert(theForm.dateTo.value);
  if ( postForm ( theForm,servlet ) ) return false;
  return true;
}

// ソート順・アクセス順切り替え
function orderpost ( orderVal )
{
//  alert(orderVal);
//  alert(document.postval.order.value);
  document.postval.order.value=orderVal;
  if ( postForm ( document.postval,'AllAccess') ) return false;
  return true;
}

// コーナドリルダウンする
function cornerpost ( cornerVal,cornerdepthVal )
{
  //document.location.reload();
  document.postval.corner.value=cornerVal;
  document.postval.cornerdepth.value=cornerdepthVal;
//  alert(document.postval.category.value);
  if ( postForm ( document.postval,'AllAccess') ) return false;
  return true;
}

// 業種ドリルダウンする
function categorypost ( categoryVal )
{
  document.postval.category.value=categoryVal;
  if ( postForm ( document.postval,'AllAccess') ) return false;
  return true;
}
