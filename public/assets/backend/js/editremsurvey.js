		  
$(function() {
    var fromDate = $("#startdate").datepicker({
    	dateFormat: 'dd/mm/yy',
        defaultDate: "+1w",
        changeMonth: true,
        numberOfMonths: 2,
        minDate: new Date(),
        onSelect: function(selectedDate) {
            var instance = $(this).data("datepicker");
            var date = $.datepicker.parseDate(instance.settings.dateFormat || $.datepicker._defaults.dateFormat, selectedDate, instance.settings);
            date.setDate(date.getDate()+0);
            toDate.datepicker("option", "minDate", date);
        }
    });
    
    var toDate = $("#enddate").datepicker({
    	dateFormat: 'dd/mm/yy',
        defaultDate: "+1w",
        changeMonth: true,
        numberOfMonths: 2,
        
    });
});
	  
$(document).ready(function() {	
    $('form').on('submit',function(){

    	var purchase = $('#purchase').val();
		var install = $('#install').val();
		var running = $('#running').val();
		var startdate = $('#startdate').val();
		var enddate = $('#enddate').val();
		
		if( purchase == ""){	
			alert("Please Check, purchase is empty!");
			return false;
		}
		
		if(install == ""){
			alert('Please Check, install is empty!');
			return false;
		}
		
		if( running == ""){
			alert('Please Check, running is empty!');	     		
     		return false;
		}
		if( startdate == ""){
			alert('Please Check, startdate is empty!');	     		
     		return false;
		}
		if( enddate == ""){
			alert('Please Check, enddate is empty!');	     		
     		return false;
		}
    });
});