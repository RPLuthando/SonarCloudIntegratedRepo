$(document).ready(function(){
    $('#framework').multiselect({
     nonSelectedText: 'Select Entity',
     enableFiltering: true,
     enableCaseInsensitiveFiltering: true,
     buttonWidth:'400px'
    });
    
    $('#framework_form').on('submit', function(event){
     event.preventDefault();
     var form_data = $(this).serialize();
      $.ajaxSetup({
       headers: {
         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
       }
     });
     $.ajax({
      url:"{{ route('frameworks.store') }}",
      method:"POST",
      data:form_data,
      success:function(data)
      {
       $('#framework option:selected').each(function(){
        $(this).prop('selected', false);
       });
       $('#framework').multiselect('refresh');
       alert(data['success']);
      }
     });
    });
   });