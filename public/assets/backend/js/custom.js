$(document).ready(function(){
    //below code is  for the otp verification
    $('#submit_otp').on('click',function(e){
        e.preventDefault();
        var otp = $('#otp_val').val();
        var otpemail = $('#otp_email').val();
        var token = $('#superToken').val();
        $.ajax({
            url: "/confirmation-view",
            type: "post",
            data: {
                otp:otp,
                otpemail:otpemail
            } ,
            headers: {
                'X-CSRF-TOKEN': $('#superTokenBackend').attr('content')
            },
            success: function (res) {
                console.log(res);
                if(res.success){
                    console.log(res.message);            
                    $('#show_alert').addClass('d-none');
                    $('#show_alert').text(res.message); 
                    var otpemail = $('#otp_email').val();
                    window.location.href = "loginUser/"+otpemail;
                }else{    
                    $('#show_alert').removeClass('d-none');                    
                    $('#show_alert').text(res.message);
                    $('#show_alert').css('display', 'block');
                    // console.log(res.message);
                }
               // You will get response from your PHP page (what you echo or print)
            },
            error: function(jqXHR, textStatus, errorThrown) {
               console.log(textStatus, errorThrown);
            }
        });

    });
    
   
});