(function ($) {

  'use strict';
  
  $(function () {       // document.ready
    
    // Hide the jobs blocks
    $('#block-areajobsblock').hide();
    $('#block-internshipsblock').hide();
  
    // When user clicks the button
    $('#buttonAreaJobs').click(function(event) {
    
    // if checkbox is checked, display the jobs blocks
    if($("#checkAreaJobs").is(":checked")) {
      $('#block-areajobsblock').show();
      $('#block-internshipsblock').show();
    }
    
    // if checkbox is not checked, display a message to the user
    else {
      alert("Please check the job scam awareness statement to view the job listings.");
    }
    
    event.preventDefault();
    });
  
  });
})(jQuery);