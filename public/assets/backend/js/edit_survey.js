 $(document).ready(function(){
         $('.questionOption').on('change',function(){
            let checkedStatus = this.checked;
            let checkedVal = $(this).parent('label').text();
             if(  (checkedStatus == true) && (checkedVal.trim()=="Other") ) {
                    var question_id = $(this).attr('ques_id');  
                    $('#otherFields'+question_id).show();
                    $('#optionActive').show();
             }
             else{
                var question_id = $(this).attr('ques_id');  
                $('#otherFields'+question_id).hide();
                $('#otherFields'+question_id).val(''); 
             }
        });
    });
   
    $(document).ready(function () {
       $('#optionActive').hide();
       var abc = $('.questionOption:checked').parent('label').text();

        if($('.questionOption').is(':checked') &&  (abc.trim()=='Other')) {  
             $('#optionActive').show();
                var question_id = $('#otherFields').attr('ques_id');  
                $('#otherFields'+question_id).css('display', 'block !important');  
        }
        else{
           
             $('#optionActive').hide();
        }
    });

$(document).ready(function() {  
    $('form').on('submit',function(){
        
        var optionValue = $('input[type="text"][name="other"]').val();
        var optionsVlaue = $('.questionOption:checked').parent('label').text();
        if ($('.questionOption').is(':checked') && (optionsVlaue.trim() == 'Other') && optionValue == "") {
            alert('Please fill the empty text box!');
            return false;
        }
        
    });
});