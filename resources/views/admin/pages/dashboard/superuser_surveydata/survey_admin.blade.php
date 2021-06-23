@extends('layouts.survey_backend')

@section('contents')
   
<div class="container">
       
    <div class="row justify-content-center">
            @include('admin.sidebar')
       
            <div class="col-md-9 sv_body"> 
                <div id="surveys-list" class="surveys-list"> 
                    <section>
                        <button data-bind="click: function() { createSurvey('NewSurvey' + Date.now(), loadSurveys); }">Create New Survey</button>
                    </section>
                    <table id="survey_view" class="table table-striped" >
                      
                        <tbody class="active_surveys" style="display:none;">
                            <!-- ko foreach: availableSurveys -->                            
                                <tr>
                                    <td data-bind="text: name"></td>
                                 
                                    <td>
                                        <a class="sv_button_link" data-bind="attr: { href: 'editor?id=' + ko.unwrap(Number(id)) }" >Edit</a>
                                        <!-- <a class="sv_button_link" data-bind="attr: { href: 'survey?id=' + ko.unwrap(id) }">Run</a> -->
                                        {{-- <a class="sv_button_link" data-bind="attr: { href: 'editor.html?id=' + ko.unwrap(id) }">Edit</a> --}}
                                        {{-- <a class="sv_button_link" data-bind="attr: { href: 'results.html?id=' + ko.unwrap(id) }">Results</a>--}}
                                        <span class="sv_button_link sv_button_delete" data-bind="click: function() { $parent.deleteSurvey(ko.unwrap(id), $parent.loadSurveys); }"> <a href="">Delete</a></span> 
                                        
                                    </td>
                                </tr>

                            <!-- /ko -->
                        </tbody>
                        
                    </table>
                    
                </div>
            </div>
    </div>
</div>
@endsection
