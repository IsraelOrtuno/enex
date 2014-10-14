// JavaScript Document
/*
 * This JavaScript perform 'drop-down' effect for
 * 'Administration Tools' & 'Volunteering Management'
 * positioned at 'Right-sidebar' on 'backend_template'
 */

$(document).ready(function() {
    $("#left-wid-1-title").click(function () {
        $("#left-wid-1-btn-container").slideToggle("medium");
    });
    
    $("#left-wid-2-title").click(function () {
        $("#left-wid-2-btn-container").slideToggle("medium");
    });
});

$('.dropdown-btn').live('click',function() {
    $('#dropdown-container').show();
	
    $('body').click(function(event) {
    var $target = $(event.target);

    if($target.parents('#dropdown-container').length == 0) {
        $('#dropdown-container').hide();
    }
  });
});

$('.dropdown-btn').live('click',function() {
    $('#dropdown-container').show();
	
    $('body').click(function(event) {
    var $target = $(event.target);

    if($target.parents('#dropdown-container').length == 0) {
        $('#dropdown-container').hide();
    }
  });
});