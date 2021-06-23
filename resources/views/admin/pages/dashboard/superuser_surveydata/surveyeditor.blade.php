@extends('layouts.survey_backend')
@section('contents')
<div class="container-fluid p-5">
    <div class="row">
		<div class="survey-page-header">
	      <div class="sv_main survey-page-header-content">
	        <a href="{{ route('superuser_surveyadmin')}}"><button>&lt&nbspBack</button></a>
	      </div>
	    </div>
	    <div style="margin-left: 40px;">
	    	<span>Survey Deadline Date &nbsp&nbsp</span>
        <input type="date" name="date" id="cal" value="{{$deadline}}">
            <input type="hidden" name="sid" value="{{$id}}" id="sid">
				<span> &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</span>
	    </div>
		
	    <div class="sv_header">
          <h3>
            <span
              id="sjs_survey_creator_title_edit"
              class="editor_title_edit"
              style="display: none;"
            >
              <input type="text" id="surveyName" value="{{$name}}">
              <span
                class="btn btn-success"
                onclick="postEdit()"
                style="border-radius: 2px; margin-top: -8px; background-color: #1ab394; border-color: #1ab394;"
                >Update</span>
              <span
                class="btn btn-warning"
                onclick="cancelEdit()"
                style="border-radius: 2px; margin-top: -8px;"
                >Cancel</span
              >
            </span>
            <span id="sjs_survey_creator_title_show">
              <span
                style="padding-top: 1px; height: 39px; display: inline-block;"
              ></span>
              <span class="edit-survey-name" onclick="startEdit()" title="Change Name">
                <img
                  class="edit-icon"
                  src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz48c3ZnIHZlcnNpb249IjEuMSIgaWQ9IkxheWVyXzEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHg9IjBweCIgeT0iMHB4IiB2aWV3Qm94PSIwIDAgMjQgMjQiIGVuYWJsZS1iYWNrZ3JvdW5kPSJuZXcgMCAwIDI0IDI0IiB4bWw6c3BhY2U9InByZXNlcnZlIj48Zz48cGF0aCBmaWxsLXJ1bGU9ImV2ZW5vZGQiIGNsaXAtcnVsZT0iZXZlbm9kZCIgZmlsbD0iIzFBQjM5NCIgZD0iTTE5LDRsLTksOWw0LDRsOS05TDE5LDR6Ii8+PHBhdGggZmlsbC1ydWxlPSJldmVub2RkIiBjbGlwLXJ1bGU9ImV2ZW5vZGQiIGZpbGw9IiMxQUIzOTQiIGQ9Ik04LDE1djRoNEw4LDE1eiIvPjxwYXRoIGZpbGwtcnVsZT0iZXZlbm9kZCIgY2xpcC1ydWxlPSJldmVub2RkIiBmaWxsPSIjMUFCMzk0IiBkPSJNMSwxN3YyaDR2LTJIMXoiLz48L2c+PC9zdmc+"
                  style="width:24px; height:24px; margin-top: -5px;"
                />
              </span>
            </span>
          </h3>
        </div>
        <div style="margin-left: 40px;">
	    	<span>Group</span> 
        <form action="/group" method="POST" name="postGroup">
        {{ csrf_field() }}
        <select name="postGroup" id="groupName" onchange="this.form.submit()">
          <option value="">Select Group</option>
            <option value="security" {{ $group == 'security'  ? 'selected' : ''}} >Security</option>
            <option value="privacy" {{ $group == 'privacy'  ? 'selected' : ''}} >Privacy</option>
            <option value="privacyuat" {{ $group == 'privacyuat'  ? 'selected' : ''}} >Privacy UAT</option>
            <option value="securityuat" {{ $group == 'securityuat'  ? 'selected' : ''}} >Security UAT</option>
            <option value="admin" {{ $group == 'admin'  ? 'selected' : ''}} >Admin</option>
          </select>
          <input type="hidden" name="sid" value="{{$id}}" id="sid">
          <!-- Chris: Please make this an ajax function like the date option instead of relying on Button Submit to fire form -->
{{--          <button type="submit">Submit</button>--}}
        </form>
          
	    </div>
	    <div class="sv_main sv_frame sv_default_css">

			<div class="sv_custom_header"></div>
			<div class="">
				<div class="sv_body">
		          <div id="survey-creator-container"></div>
		        </div>
			</div>
		</div>
		</div>
   </div>
</div>
@endsection
