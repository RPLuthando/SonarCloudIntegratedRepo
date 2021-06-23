$(".start").each(function(){
	var startdate = $(this).attr('id');
	var splitEnddate = $(this).attr('id').split('_')[1];
    var fromDate = $("#"+startdate).datepicker({
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
    var toDate = $("#enddate_"+splitEnddate).datepicker({
    	dateFormat: 'dd/mm/yy',
        defaultDate: "+1w",
        changeMonth: true,
        numberOfMonths: 2
    });
});
 

jQuery(document).on("input", ".numeric", function() {
    this.value = this.value.replace(/\D/g,'');
});
jQuery(document).ready(function(){
	
     var total_questions = $('#total_questions_count').val();
     if( total_questions == 1){
     	$('#reviewRemResponse,.cls').show();
     	 $('.next_step_rem').hide();
     }
     else if(total_questions<1){
		$('#reviewRemResponse').hide();
		 $('.next_step_rem').show();
     }
     $('.field-set1').show();
     for(var i =2;i<=total_questions;i++){ 
		$('.field-set'+i).hide();			
		$('#reviewRemResponse').hide();
		if(i==total_questions){
			$('#reviewRemResponse').show();
		}
	}
	$('.next_step_rem').on('click',function(){
		var countOption = $('#countOption').val();
		var question_id = $(this).attr('question_id');
		var j = $(this).attr('j'); 
		var a = $(this).attr('a'); 
		var iterate = $(this).attr('iterate');
		for(var i=0;i<=1000;){
			var purchase = $('#purchase_'+i+j+question_id).val();
			var install = $('#install_'+i+j+question_id).val();
			var running = $('#running_'+i+j+question_id).val();
			var startdate = $('#startdate_'+i+j+question_id).val();
			var enddate = $('#enddate_'+i+j+question_id).val();
			console.log('i',i);
			console.log('j',j);
			console.log('question_id',question_id);
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
			i++;
		}
		 
	
		$('#field-set'+iterate).hide();
		$('#field-set'+(parseInt(iterate)+parseInt(1))).show();
		//if($('.maincontainer::last-of-type')){
		if(iterate == (parseInt(total_questions)-parseInt(1))){
            $('#reviewRemResponse').show();
            $('.cls').show();
            $('.next_step_rem').hide();               
        }
        else{
            $('#reviewRemResponse').hide();
        }
	});  

});
   