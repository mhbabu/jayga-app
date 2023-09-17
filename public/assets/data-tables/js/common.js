/***********************
 DATEPICKER START HERE
 **********************/
$('.date-picker').datepicker({
    format: 'dd/mm/yyyy',
    autoclose: true,
    todayHighlight: true
});

/*************************************
 DATEPICKER TOP POSITION CHANGE HERE
 ***********************************/
let originalCoordinate = 0;
$('.date-picker,#dob,#vehicle-model-year,#vehicle-manufacturing-year').click(function(e){
  if(originalCoordinate != $('.datepicker').position().top)
  {
    $('.datepicker').css({'top':$('.datepicker').position().top+55});
    originalCoordinate = $('.datepicker').position().top;
  }

});

/**********************************************
 CATEGORY WISE SUBCATEGORIES ONCHANGE SELECT
 **********************************************/
$(".categoryId").change(function () {
    let route = "/admin/product/subcategories-by-category";
    subcategoriesByCategory(this,route);
});







