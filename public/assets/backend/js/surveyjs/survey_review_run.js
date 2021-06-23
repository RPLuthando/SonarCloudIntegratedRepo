
var id = getUrlVars()["id"];
var en = getUrlVars()['en'];

console.log('Entity Data', en);

// console.log('chekekekkekeke');
$(document).ready(function(){
$.ajax({ 
    "_token": "{{ csrf_token() }}",
    type: "get",
    url: '/surveyreviewnew/' + id + '/entity/' + en,
        context: document.body,
        success: function(dataResult){            
        
          var result = dataResult.non_comp_json;
          var partial = dataResult.partial_json;
         //console.log(result);
           
  Survey
    .StylesManager
    .applyTheme("modern"); 

var json = result;
// New Coding sturtcure 
var survey = new Survey.Model(result);
console.log('New Survey', survey);
function getSurveyResults(result) {
    var resultData = [];
   // var selectedItem = null;
    for (var key in survey.data) {
        console.log('Key', key);
        var question = survey.getQuestionByValueName(key);  
        console.log('Question', question);
        if (!!question) {
            var item = { name: question.name, value: question.value };
            if (question.name !== question.title) {
                item.title = question.title;
            }
            if (!!question.QID) {
                item.QID = question.QID; 
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
        url: "/surveyreviewparametersdata/" + en,
        data: {customReviewJson : JSON.stringify(resultData), postId: id},
        cache: false,
        success: function(resultData){            
           $("#resultarea").text(resultData);
        }
      });
    console.log('Data Results', resultData);
    return resultData;
}

//end
survey
    .onComplete 
    .add(function (result) {
        document
            .querySelector('#surveyResult')
           .textContent = "Result JSON:\n" + JSON.stringify(getSurveyResults(result), null, 3);
    });


$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

var storageName = 'Partial Data';

if (typeof window.localStorage !== 'undefined') {  
   var checkStorage = window.localStorage.getItem(storageName); 

    if(checkStorage === 'null' || checkStorage === 'undefined'){
        console.log("storage check in");
        if(partial !== 'no data') {
        
            // window.localStorage.setItem(storageName, JSON.stringify(partial));
            window.localStorage.setItem(storageName, partial);
          var zdz = window.localStorage.getItem(storageName);
          
          console.log(zdz);
         } else {
          console.log("No Data Available");
         }
    } else {

        if(partial !== 'no data') {
            console.log("Partial Data Not Null");
           // window.localStorage.setItem(storageName, JSON.stringify(partial));
            window.localStorage.setItem(storageName, partial);
           var partialdata = window.localStorage.getItem(storageName);
            console.log(partialdata);
        }
        console.log("No Data Errors");
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
    console.log('Saved Survey Data', survey);
    var data = survey.data;
   var dataCamp = survey.data; //new
   
    var campPageNO = 0; //new
   
    console.log('Survey Data', data);
   //console.log(campPageNO);
    data.pageNo = survey.currentPageNo;    
    dataCamp.pageNo = campPageNO; //New
 
    if(option === 'partial'){
        $.ajax({
            "_token": "{{ csrf_token() }}",
            type: "POST",
            url: "/partialsurveydata/" + en,
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
            url: "/completesurveydata/" + en,
            data: {resultsJson : JSON.stringify(dataCamp), postId: id},
            cache: false,
            success: function(dataCamp){            
               $("#resultarea").text(dataCamp);
            }
          });

          


    }
    // Completed Survey
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
    
    console.log('Send Result On Page', prevData);

if (prevData) {
    console.log('Previous Data Available', prevData);
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

