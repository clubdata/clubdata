/**
 * Set all containers in a row to equal height, at least minHeight
 * 
 */
function equalheight(container, minHeight){

    var currentTallest = 0,
         currentRowStart = 0,
         rowDivs = new Array(),
         $el,
         topPosition = 0;

    minHeight = minHeight || 0;
     $(container).each(function() {

       $el = $(this);
       $($el).height('auto')
       topPostion = $el.position().top;

       if (currentRowStart != topPostion) {
         for (currentDiv = 0 ; currentDiv < rowDivs.length ; currentDiv++) {
           rowDivs[currentDiv].height(currentTallest);
         }
         rowDivs.length = 0; // empty the array
         currentRowStart = topPostion;
         currentTallest = Math.max(minHeight,$el.height());
         rowDivs.push($el);
       } else {
         rowDivs.push($el);
         currentTallest = (currentTallest < $el.height()) ? ($el.height()) : (currentTallest);
      }
       for (currentDiv = 0 ; currentDiv < rowDivs.length ; currentDiv++) {
         rowDivs[currentDiv].data('height', currentTallest).height(currentTallest);
       }
     });
    }

function equalWidth(container) {

    var greatestWidth = [];   // Stores the greatest width
    var aktLeft = -1;         // Left position of last element
    var aktCol = -1;          // column of selected element
    
    $(container).width('auto');
    $(container).each(function() {    // Select the elements you're comparing

        // Reset column if actual element is left of last element. i.e. a new row has started
        // Else increment column
        aktCol = ( $(this).offset().left <= aktLeft ) ? 0 : (aktCol + 1);
        aktLeft = $(this).offset().left;
        
        var theWidth = $(this).data('col-nr', aktCol)
                              .width('auto')
                              .width();                 // Grab the current width

        if( typeof greatestWidth[aktCol] == 'undefined' || theWidth > greatestWidth[aktCol]) {   // If theWidth > the greatestWidth so far,
            greatestWidth[aktCol] = theWidth;     //    set greatestWidth to theWidth
        }
    });
    $(container).each(function() {
        $(this).width(greatestWidth[$(this).data('col-nr')]);
    }); 
}


$(window).load(function() {
  equalheight('.equalheight .divbox', 120);
  //$('.divbox').each(function() { alert($(this).position().top + " : " + $(this).position().left); });
  
  equalWidth('.tablecol-equal, .col-equal');
  $( ".divbox" ).parent().sortable({
//      connectWith: ".content",
      handle: ".boxhead",
      //cancel: ".portlet-toggle",
      placeholder: ".divbox-placeholder ui-corner-all", 
      stop: function( event, ui ) {
          equalheight('.equalheight .divbox', 120);
      }
    }); //draggable({ containment: $('.content-section'), scroll: false });
  
  $( ".divbox" )
  //.addClass( "ui-widget ui-widget-content ui-helper-clearfix ui-corner-all" )
  .find( ".boxhead" )
    //.addClass( "ui-widget-header ui-corner-all" )
    .append( "<span class='ui-icon ui-icon-minusthick header-toggle'></span>");
  
  $( ".divbox .header-toggle" ).click(function() {
      var icon = $( this );
      var divbox = icon.closest(".divbox");
      if ( icon.hasClass('ui-icon-minusthick')) {
          divbox.height('auto').parent().removeClass('equalheight');
      } else {
          divbox.height(divbox.data('height')).parent().addClass('equalheight');
      }
      icon.toggleClass( "ui-icon-minusthick ui-icon-plusthick" );
      divbox.find( ".boxmiddle" ).toggle();
    });

});


$(window).resize(function(){
  $('.ui-draggable').width('');
  equalheight('.equalheight .divbox', 120);
  equalWidth('.tablecol-equal, .col-equal');
});
