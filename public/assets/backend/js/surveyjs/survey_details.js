function SurveyManager(baseUrl, accessKey) {
  var accessKey = '10071cef2d7b4f5eab22981c2b49368b';
  var baseUrl = window.location.origin;
  var self = this;
  self.availableSurveys = ko.observableArray();
  self.loadSurveys = function() {
      var xhr = new XMLHttpRequest();
      xhr.open("GET", baseUrl + "/getActive");
      xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
      xhr.onload = function() {
          var result = xhr.response ? JSON.parse(xhr.response) : {};
          self.availableSurveys(Object.keys(result).map(function(key) {
              return {
                  id: result[key].id,
                  name: result[key].name || key,
                  survey: result[key].json || result[key]
              };
          }));
      };
      xhr.send();
  };
  self.createSurvey = function(name, onCreate) {
    var myObj;
      var xhr = new XMLHttpRequest();
      xhr.open("GET", baseUrl + "/create/" + accessKey + "/" + name);
      xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
      xhr.onload = function() {
        myObj = JSON.parse(xhr.response);
          var result = xhr.response ? JSON.parse(xhr.response) : null;
          !!onCreate && onCreate(xhr.status == 200, result, xhr.response);
          window.location.href = "editor"+"?id="+myObj.Id;
      };
      xhr.send();
  };
  self.deleteSurvey = function(id, onDelete) {
    
      if (confirm("Are you sure?")) {
          var xhr = new XMLHttpRequest();
          xhr.open("GET", baseUrl + "/delete/" + id);
          xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
          console.log('hel');
          xhr.onload = function() {
              var result = xhr.response ? JSON.parse(xhr.response) : null;
              !!onDelete && onDelete(xhr.status == 200, result, xhr.response);
          };
          xhr.send();
          //window.location = "/";
      }
  };
  self.loadSurveys();
}


$('#cal').change(function() {
var val = document.getElementById('cal').value;
var id = document.getElementById('sid').value;
  $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
      }
  });
  $.ajax({
      type: "POST",
      url: "expdate",
      data: {
        date: val,
        id: id
      },
      success: function(msg) {
          if (msg) {
              alert('success'); //testing purposes
          } else {
              alert('fail'); //testing purposes
          }
      },
      error: function(e) {
          alert("something wrong" + e) // this will alert an error
      }
  });
});

ko.applyBindings(new SurveyManager(""), document.getElementById("surveys-list"));
$(document).ready(function() {
  var tbody = $("#survey_view tbody");
  if ($("#survey_view tbody").is(":empty")) {
      $('.active_surveys').css('display', 'none !important');
      tbody.html("<tr>message foo</tr>");
  } else {
      $('.active_surveys').show();
  }
});