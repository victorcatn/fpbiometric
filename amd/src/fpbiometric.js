define(['jquery'], function($) {
 
    return {
	
        init: function(mail) {
 	
            // Put whatever you like here. $ is available
	            // to you as normal.
	
        if ($(".quizstartbuttondiv [type=submit]").length ) {
            $(".quizstartbuttondiv [type=submit]").click( (mailo)=>  this.invocarLectorDeHuella(mail));
        }else{
          this.invocarLectorDeHuella(mail);
        }
        

        },
        invocarLectorDeHuella: function(mail){
	
          $.ajax({
                url: "http://localhost:8084/?id="+mail,
                cache: false
          })
          .done(function( html ) {
                  
                  if(html=="exito"){
                          $('#id_valid').prop('checked', true);
                          $("#id_submitbutton").click();
                  }
          })
	  .fail( function( jqXHR, textStatus, errorThrown ) {

  if (jqXHR.status === 0) {

    alert('Error, intentalo de nuevo.')
	console.log(jqXHR);

  } else if (jqXHR.status == 404) {

    alert('Requested page not found [404]');

  } else if (jqXHR.status == 500) {

    alert('Internal Server Error [500].');

  } else if (textStatus === 'parsererror') {

    alert('Requested JSON parse failed.');

  } else if (textStatus === 'timeout') {

    alert('Time out error.');

  } else if (textStatus === 'abort') {

    alert('Ajax request aborted.');

  } else {

    alert('Uncaught Error: ' + jqXHR.responseText);

  }

});
        }
    };
});
