$(document).ready(function(){

    var total_questions = $('#total_questions').val(); 
    if( total_questions == 1){
        $('#reviewResponseBtn,.cls').show();
        $('.nextBtn').hide();
      }
      else if(total_questions<1){
      $('#reviewResponseBtn').hide();
      $('.nextBtn').show();
      }

    $('.current_question1').show();
    
    for(var i =2;i<=total_questions;i++){ 
      $('.current_question'+i).hide();      
      $('#reviewResponseBtn').hide();
      if(i==total_questions){
      $('#reviewResponseBtn').show();
      }
    } 
    $('.nextBtn').on('click',function(){

      var optionId =  $(this).attr('options_id');
      var question_id = $(this).attr('question_id'); 
       var atLeastOneChecked = false;

        if($("input:radio."+optionId+":checked").length == 0) {
            alert("Please select atleast one");
            return false;
        }
        if($("input:radio."+optionId+":checked").attr('question_text')=="Other" &&  $("#otherField"+question_id).val() == ''){
            alert('Please fill the empty text box');
            return false;
        }
        

        $('#fieldlist'+question_id).hide();
        $('#fieldlist'+(parseInt(question_id)+parseInt(1))).show();
        console.log(question_id);
        if(question_id == (parseInt(total_questions)-parseInt(1))){
                $('#reviewResponseBtn').show();
                $('.cls').show();
                $('.nextBtn').hide();               
            }
            else{
                $('#reviewResponseBtn').hide();
            }
    });
    $('.questionOption').on('click',function(){

      if($(this).is(':checked') && ($(this).attr('question_text'))=="Other") { 
        //console.log('abc');
        var question_id = $(this).attr('ques_id'); 
        //console.log(question_id);
        $('#otherField'+question_id).css('display','block');        
      }else
      {
        var question_id = $(this).attr('ques_id');
        $('#otherField'+question_id).hide();
      }
    });
    
     
  });