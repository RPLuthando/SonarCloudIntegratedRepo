@if(check_role()==1)    
<div class="col-md-3"> 
    <div class="card">
        <div class="card-header">
            {{ __('lang.dashboard')}}
        </div>

        <div class="card-body">
            <ul class="nav flex-column" role="tablist">
               
                  {{-- admin --}}
                <li class="nav-item" role="presentation">
                    <a class="nav-link" href="{{ url('userlist/all_users') }}">
                        {{ __('lang.users')}}
                    </a> 
                    <ul>
                            <li><a href="{{url('/userlist/super_user')}}">{{ __('lang.super')}}</a></li>
                            <li><a href="{{url('/userlist/management_user')}}">{{ __('lang.management')}}</a></li>
                            <li><a href="{{url('/userlist/responsible_user')}}">{{ __('lang.responsible')}}</a></li>
                        </ul>
                </li>  
                <li class="nav-item" role="presentation"> 
                    <a class="nav-link" href="{{route('superuser_surveydata')}}">
                        {{ __('lang.survey_data')}}
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" href="{{url('/entitylist')}}">
                        {{ __('lang.entity')}}
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link pt-0" href="{{route('superuser_surveyadmin')}}">
                        {{ __('lang.manage_surveys')}}
                    </a>
                    <ul>
                       {{--  <li>
                        <button data-bind="click: function() { createSurvey('NewSurvey' + Date.now(), loadSurveys); }">Add</button>
                    </li> --}}
                        <li><a>{{ __('lang.add_survey')}}</a></li>
                      {{--  <li><a href="{{route('getSurveyList')}}">{{ __('lang.live_surveys')}}</a></li>--}}
                        <li><a >{{ __('lang.deleted_surveys')}}</a></li>
                    </ul>
                </li>             
            </ul>
        </div>
    </div> 
</div>
@endif    