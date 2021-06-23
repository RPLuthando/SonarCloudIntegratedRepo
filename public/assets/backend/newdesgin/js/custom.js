"use strict";

$(document).ready(function() {

     $('#currentSur').DataTable( {
     
      "paging":         false, 
      aoColumnDefs : [ {
           orderable : false, aTargets : [2,3,4]         
        }],
        order: [] 
    }); 

    $('#reviewedtable').DataTable( {
     
      "paging":         false, 
      aoColumnDefs : [ {
         orderable : false, aTargets : [4,5]        
      }],
      order: [] 
  });


  $('#Completedtable').DataTable( {
    
    "paging":         false,
    aoColumnDefs : [ {
       orderable : false, aTargets : [3,4,5]        
    }],
    order: [] 
    });



  $('#Your_current_status').DataTable( {
   
    "paging":         false,
    aoColumnDefs : [ {
       orderable : false, aTargets : [1,3,5]        
    }],
        order: [] 
    });

    $('#Responses_update').DataTable( { 
       "processing": true,
        "paging":         false,
      aoColumnDefs : [ {
         orderable : false, aTargets : [0,2]        
      }],
      order: [[5, "desc"]] 
    });


  $('#Your_submission').DataTable( {
	"processing": true,
    "paging":         false,
    aoColumnDefs : [ {
       orderable : false, aTargets : [4]        
    }],
    order: [[ 5, "desc" ]] 
} );


        
});






