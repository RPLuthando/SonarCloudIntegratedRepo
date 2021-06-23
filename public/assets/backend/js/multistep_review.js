$(document).ready(function() {
    var total_questions = $('#total_questions').val();
    if (total_questions == 1) {
        $('.cls').show();
        $('.review_next').hide();
    }
});
var current_step, next_step, steps;
steps = $("fieldset").length;
$(document).on("click", ".review_yes", function() {
    var total_questions = $('#total_questions').val();
    let id = $(this).attr('idcorrect');
    $('#optionsreview' + id).show();
    $('#textreview' + id).show();
    $('#nextreview' + id).show();
    if (id == (parseInt(total_questions))) {
        $('#nextreview' + id).hide();
        $('.cls, #review_submit').show();
    }
});
$(document).on("click", ".review_next", function() {
    var total_questions = $('#total_questions').val();
    var question_id = $(this).attr('idGet');
    var optionId = $(this).attr('options_id');
    if ($("input:radio." + optionId + ":checked").length == 0) {
        alert("Please select atleast one");
        return false;
    }
    if ($("input:radio." + optionId + ":checked").attr('question_text') == "Other" && $("#otherField" + question_id).val() == '') {
        alert('Please fill the empty text box');
        return false;
    }
    current_step = $(this).parent("fieldset");
    next_step = $(this).parent("fieldset").next();
    next_step.show();
    current_step.hide();
});
$(document).on('click', 'form button[type=submit]', function(event) {
    let dataId = $(this).attr('rev_submit_id');
    if ($("input:radio.option_" + dataId + ":checked").length == 0) {
        alert('Please select atleast one');
        event.preventDefault();
    }
});
$(document).on("click", ".review_no", function() {
    var total_questions = $('#total_questions').val();
    current_step = $(this).parent("div").parent("fieldset");
    next_step = $(this).parent("div").parent("fieldset").next();
    next_step.show();
    current_step.hide();
    let dataSurveyId = $(this).attr('idSurvey');
    let dataQuestionId = $(this).attr('idwrong');
    if (dataQuestionId == total_questions) {
        setTimeout(function() {
            var url = "/review-check/" + dataSurveyId;
            $(location).attr('href', url);
        }, 0);
    }
});
$(document).on("click", ".reviewOption", function() {
    if ($(this).is(':checked') && ($(this).attr('question_text')) == "Other") {
        var question_id = $(this).attr('rev_id');
        $('#otherField' + question_id).show();
    } else {
        var question_id = $(this).attr('rev_id');
        $('#otherField' + question_id).hide();
    }
});