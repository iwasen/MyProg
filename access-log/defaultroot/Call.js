// �������� ���I��
function Form1Submit ( theForm, servlet )
{
  if(ymdnumerical (  theForm.dateFrom.value.substring(0,4),
                     theForm.dateFrom.value.substring(5,7),
                     theForm.dateFrom.value.substring(8,10),
                     theForm.dateTo.value.substring(0,4),
                     theForm.dateTo.value.substring(5,7),
                     theForm.dateTo.value.substring(8,10),
                     "���̏��Ԃ�" )

  )return false;

  theForm.attribute[0].value=document.attribute.attribute[0].value;
  theForm.attribute[1].value=document.attribute.attribute[1].value;
  theForm.attribute[2].value=document.attribute.attribute[2].value;
  theForm.attribute[3].value=document.attribute.attribute[3].value;
  theForm.attribute[4].value=document.attribute.attribute[4].value;

  //���t�E�t�B���^�[�ȊO�̒l��HTML�ɂ��߂��܂ꂽ�l�𗘗p�iHISTORY�@BACK�΍�j
  theForm.corner.value=document.postval.corner.value;
  theForm.order.value=document.postval.order.value;
  theForm.cornerdepth.value=document.postval.cornerdepth.value;
  theForm.category.value=document.postval.category.value;
  theForm.page.value=document.postval.page.value;



  //alert(theForm.attribute[0].value);
  if ( postForm ( theForm,servlet ) ) return false;
  return true;
}

// �������� �T�I��
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
                     "�T�̏��Ԃ�" )

  )return false;
  theForm.attribute[0].value=document.attribute.attribute[0].value;
  theForm.attribute[1].value=document.attribute.attribute[1].value;
  theForm.attribute[2].value=document.attribute.attribute[2].value;
  theForm.attribute[3].value=document.attribute.attribute[3].value;
  theForm.attribute[4].value=document.attribute.attribute[4].value;

  //���t�E�t�B���^�[�ȊO�̒l��HTML�ɂ��߂��܂ꂽ�l�𗘗p�iHISTORY�@BACK�΍�j
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

// �������� ���I��
function Form3Submit ( theForm, servlet )
{
//alert(theForm.fromDateY.value);
  if(!isValidateDate(theForm.fromDateY, theForm.fromDateM, theForm.fromDateD, true, true)) return false;
  if(!isValidateDate(theForm.toDateY, theForm.toDateM, theForm.toDateD, true, true)) return false;
  if(ymdnumerical ( theForm.fromDateY.value, theForm.fromDateM.value, theForm.fromDateD.value,
                      theForm.toDateY.value, theForm.toDateM.value, theForm.toDateD.value,
                     "���t�̏��Ԃ�" )

  )return false;
  theForm.attribute[0].value=document.attribute.attribute[0].value;
  theForm.attribute[1].value=document.attribute.attribute[1].value;
  theForm.attribute[2].value=document.attribute.attribute[2].value;
  theForm.attribute[3].value=document.attribute.attribute[3].value;
  theForm.attribute[4].value=document.attribute.attribute[4].value;

  //���t�E�t�B���^�[�ȊO�̒l��HTML�ɂ��߂��܂ꂽ�l�𗘗p�iHISTORY�@BACK�΍�j
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

// �\�[�g���E�A�N�Z�X���؂�ւ�
function orderpost ( orderVal )
{
//  alert(orderVal);
//  alert(document.postval.order.value);
  document.postval.order.value=orderVal;
  if ( postForm ( document.postval,'AllAccess') ) return false;
  return true;
}

// �R�[�i�h�����_�E������
function cornerpost ( cornerVal,cornerdepthVal )
{
  //document.location.reload();
  document.postval.corner.value=cornerVal;
  document.postval.cornerdepth.value=cornerdepthVal;
//  alert(document.postval.category.value);
  if ( postForm ( document.postval,'AllAccess') ) return false;
  return true;
}

// �Ǝ�h�����_�E������
function categorypost ( categoryVal )
{
  document.postval.category.value=categoryVal;
  if ( postForm ( document.postval,'AllAccess') ) return false;
  return true;
}
