
var id = getUrlVars()["id"];
var en = getUrlVars()['en'];

console.log(en);
localStorage.clear();

$(document).ready(function(){
$.ajax({
    "_token": "{{ csrf_token() }}",
    type: "get",
    url: '/surveyview/' + id + '/entity/' + en,
        context: document.body,
        success: function(dataResult){


          var result = dataResult.survey_json;
          var partial = dataResult.partial_json;


  Survey
    .StylesManager
    .applyTheme("modern");

var json = result;

// New Coding 
var survey = new Survey.Model(result);
console.log(survey);
function getSurveyResults(result) {
    var resultData = [];

    for (var key in survey.data) {
            var question = survey.getQuestionByValueName(key);
        console.log(question);

        if (!!question) {
            var item = { name: question.name, value: question.value };
            if (question.name !== question.title) {
                item.title = question.title;
            }
            if (!!question.NAME) {
                item.NAME = question.NAME;
            }


            if (!!question.Framework) {
                item.Framework = question.Framework;
            }
            if(question.selectedItem != null){
                if (!!question.selectedItem.Standard) {
                    item.Standard = question.selectedItem.Standard;
                }
                if (!!question.selectedItem) {
                    if (!!question.selectedItem.Score) {
                        item.Score = question.selectedItem.Score;
                    }

                }
        }
            resultData.push(item);
        }
    }
    $.ajax({
        type: "POST",
        url: "/surveyParameters/" + en,
        data: {customJson : JSON.stringify(resultData), postId: id},
        cache: false,
        success: function(resultData){
           $("#resultarea").text(resultData);
        }
      });
    console.log(resultData);
    return resultData;
}


//Test file upload
/*
survey
    .onUploadFiles
    .add(function (survey, options) {
        var formData = new FormData();
        options
            .files
            .forEach(function (file) {
                formData.append(file.name, file);
            });
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "/upload");
        xhr.onload = function () {
            var data = JSON.parse(xhr.responseText);
            options.callback("success", options.files.map(function (file) {
                return {
                    file: file,
                    content: data[file.name]
                };
            }));
        };
        xhr.send(formData);
        console.log(formData);
    });

*/
/*
survey.onUploadFiles.add(function(survey, options) {
    var formData = new FormData();
    var file = options.files
    options.files.forEach(function(file) {
        formData.append(file.name, file);

        console.log('file.name', file.name)
        console.log('options | ', options)
    });
    $.ajax({

        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: "/upload",
        type: "POST",
        method: "POST",
        xhr: function () {
            var myXhr = $.ajaxSettings.xhr();
            if (myXhr.upload) {
                myXhr.upload.addEventListener('progress', function (event) {
                    var percent = 0;
                    var position = event.loaded || event.position;
                    var total = event.total;
                    var file = options.files;
                }, false);
            }
            return myXhr;
        },
        success: function (data) {
            options.callback("success",
                options.files.map(function(file) {
                    return { file: file, content: "/" + data[file.name] };
                })
            );
        },
        error: function (error) {
        },
        async: true,
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        timeout: 60000,
    });


    console.log('data | ', options.files)
    console.log('data -  | ', options.files)
});
*/
//end
survey
    .onComplete
    .add(function (result) {
        document
            .querySelector('#surveyResult')
           .textContent = "Result JSON:\n" + JSON.stringify(getSurveyResults(result), null, 3);
    });

survey
    .onComplete
    .add(function (result) {
        document
            .querySelector('#surveyResult')
           .textContent = "Result JSON:\n" + JSON.stringify(result.data, null, 3);
    });

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

var storageName = dataResult.storage_id;

if (typeof window.localStorage !== 'undefined') {  
    console.log("in local storage"); 
    
   var checkStorage = window.localStorage.getItem(storageName);   

   console.log("31");

    if(checkStorage === 'null' || checkStorage === 'undefined'){
        console.log("storage check in");
        if(partial !== 'no data') {
        
            window.localStorage.setItem(storageName, JSON.stringify(partial));
          var zdz = window.localStorage.getItem(storageName);
          
          console.log(zdz);
         } else {
          console.log("elese part 42");
         }
    } else {

        if(partial !== 'no data') {
            console.log("elese part 47");
           // window.localStorage.setItem(storageName, JSON.stringify(data.partial));
            window.localStorage.setItem(storageName, partial);
           var partialdata = window.localStorage.getItem(storageName);
            console.log(partialdata);
        }
        console.log("elese part 52");
    }
} else {
    console.log("storage  not found");
}

survey
    .onComplete
    .add(function (result) {
        document
            .querySelector('#surveyResult')
            .textContent = "Result JSON:\n" + JSON.stringify(getSurveyResults(result), null, 3);

    });

function saveSurveyData(survey,option) {  
    console.log(survey);
    var data = survey.data;
   var dataCamp = survey.data;//new
   // console.log(dataCamp);//new
    var campPageNO = 0;//new
   
    console.log(data);
   //console.log(campPageNO);
    data.pageNo = survey.currentPageNo;    
    dataCamp.pageNo = survey.campPageNO; //New
    //console.log('helloooooo');
  //  console.log(dataCamp.pageNo);
    if(option === 'partial'){
        $.ajax({
            "_token": "{{ csrf_token() }}",
            type: "POST",
            url: "/partialsurvey/" + en,
            data: {resultsJson : JSON.stringify(data), postId: id},
            cache: false,
            success: function(data){            
               $("#resultarea").text(data);
            }
          });
    }

    if(option === 'complete'){       
        $.ajax({
             
            type: "POST",
            url: "/completesurvey/" + en,
            data: {resultsJson : JSON.stringify(dataCamp), postId: id},
            cache: false,
            success: function(dataCamp){            
               $("#resultarea").text(dataCamp);
               window.localStorage.clear();
            }
          });

          


    }
    
    window.localStorage.setItem(storageName, JSON.stringify(data));
}
survey
    .onPartialSend
    .add(function (survey) {
        saveSurveyData(survey,'partial'); 
    });
survey
    .onComplete
    .add(function (survey, options) {
        saveSurveyData(survey,'complete');
    });


survey.sendResultOnPageNext = true;
var prevData = window
    .localStorage
    .getItem(storageName) || null;
if (prevData) {
    var data = JSON.parse(prevData);
    survey.data = data;
    if (data.pageNo) {
        survey.currentPageNo = data.pageNo;
    }
    
   
}
$("#surveyElement").Survey({model: survey});            

        }
    });
});

       
  



       
function getUrlVars(){
var vars = [], hash;
var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
for(var i = 0; i < hashes.length; i++)
{
    hash = hashes[i].split('=');
    vars.push(hash[0]);
    vars[hash[0]] = hash[1];
}
return vars;
}

