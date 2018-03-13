(function ($) {
  
  $(document).ready( function() {
    if ($('#main').hasClass('not-answered')) {
      $("<div>").text('Et ole vielä vastannut liikuntamotivaatiokyselyyn. Haluaisitko tehdä sen nyt?').dialog({
       resizable: false,
       height: "auto",
       width: 400,
       modal: true,
       title: 'Vastaa liikuntamotivaatio kyselyyn.',
       buttons: {
         "Kyllä": function() {
           location.href = "/metaform/liikuntamotivaatio/";
         },
         "En": function() {
           $( this ).dialog( "close" );
         }
       }
     });
    }
  });

})(jQuery);