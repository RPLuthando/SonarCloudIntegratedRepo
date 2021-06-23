
function setSurveyName() {
  var name = jQuery("#surveyName").val();
  var $titleTitle = jQuery("#sjs_survey_creator_title_show");
  $titleTitle.find("span:first-child").text(name);
}
function startEdit() {
  var $titleCreator = jQuery("#sjs_survey_creator_title_edit");
  var $titleTitle = jQuery("#sjs_survey_creator_title_show");
  $titleTitle.hide();
  $titleCreator.show();
  $titleCreator.find("input")[0].value = jQuery("#surveyName").val();
  $titleCreator.find("input").focus();
}
function cancelEdit() {
  var $titleCreator = jQuery("#sjs_survey_creator_title_edit");
  var $titleTitle = jQuery("#sjs_survey_creator_title_show");
  $titleCreator.hide();
  $titleTitle.show();
}
function postEdit() {
  cancelEdit();
  var oldName = surveyName;
  var $titleCreator = jQuery("#sjs_survey_creator_title_edit");
  surveyName = $titleCreator.find("input")[0].value;
  alert(surveyName);
  setSurveyName(surveyName);
  $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
        }
    });
  jQuery
    .post("/changeName?id=" + surveyId + "&name=" + surveyName, function(data) {
      surveyId = data.Id;
    })
    .fail(function(error) {
      surveyName = oldName;
      setSurveyName(surveyName);
      alert(JSON.stringify(error));
    });
}

function getParams() {
  var url = window.location.href
    .slice(window.location.href.indexOf("?") + 1)
    .split("&");
  var result = {};
  url.forEach(function(item) {
    var param = item.split("=");
    result[param[0]] = param[1];
  });
  return result;
}

Survey.dxSurveyService.serviceUrl = "";
var accessKey = '10071cef2d7b4f5eab22981c2b49368b';
var surveyCreator = new SurveyCreator.SurveyCreator("survey-creator-container");
var surveyId = decodeURI(getParams()["id"]);
surveyCreator.loadSurvey(surveyId);
surveyCreator.saveSurveyFunc = function(saveNo, callback) {
  var xhr = new XMLHttpRequest();
  xhr.open(
    "POST",
    Survey.dxSurveyService.serviceUrl + "/changeJson/" + accessKey
  );
  xhr.setRequestHeader("X-CSRF-TOKEN", document.head.querySelector("[name=csrf-token]").content );
  xhr.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
  xhr.onload = function() {
    var result = xhr.response ? JSON.parse(xhr.response) : null;
    if (xhr.status === 200) {
      callback(saveNo, true);
    }
  };
  xhr.send(
    JSON.stringify({
      Id: surveyId,
      Json: surveyCreator.text,
      Text: surveyCreator.text
    })
  );
};



//add a property to the base question class and as result to all questions
Survey
    .Serializer
    .addProperty("question", {
        name: "QID:string",
        default: '1',
        category: "general"
    });

Survey
    .Serializer
    .addProperty("question", {
        name: "Framework:text",
        default: 'Framework1',
        category: "general"
    });

//add custom standard property to all choices (answers)

//add custom score property to all choices (answers)
Survey.JsonObject.metaData.addProperty("itemvalue", {name: "Score"});
  //add custom standard property to all choices (answers)

Survey.JsonObject.metaData.addProperty("itemvalue", {name: "Standard",choices: [
            "Ideal", "Acceptable", "Non-Standard", "Optimized", "Managed", "Defined", 'Emerging', "Initial"
        ]});

Survey.sendResultOnPageNext = true;
surveyCreator.showToolbox = "right";
surveyCreator.showPropertyGrid = "right";
surveyCreator.rightContainerActiveItem("toolbox");

surveyCreator.isAutoSave = true;
surveyCreator.showState = true;
surveyCreator.showOptions = true;

surveyName = surveyId;
setSurveyName(surveyName);
