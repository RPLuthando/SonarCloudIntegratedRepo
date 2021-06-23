$('body').append('<div id="loading-bar-spinner" class="spinner"><div class="spinner-icon"></div></div>');
$(window).on('load', function(){
setTimeout(removeLoader, 2000); //wait for page load PLUS two seconds.
});
function removeLoader(){
    $( "#loading-bar-spinner" ).fadeOut(500, function() {
    // fadeOut complete. Remove the loading div
    $( "#loading-bar-spinner" ).remove(); //makes page more lightweight 
});  
}