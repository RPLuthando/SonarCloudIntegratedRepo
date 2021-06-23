@extends('layouts.responsible_backend') 

@section('content')
{!!Html::style('assets/backend/css/dash_style.css')!!}
<table id="currentSur" class="display cell-border datatable_content" cellspacing="0" width="100%">
    <thead> 
        <tr>
            <th>Question</th>
            <th>Upload Evidence</th>
        </tr>
    </thead>
    <tbody>
        <td></td>
        <td></td>
    </tbody>
</table>



@endsection