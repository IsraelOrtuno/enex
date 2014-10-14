function clearSearchField(){
	searchField = document.getElementById('search-textbox');
	searchContainer = document.getElementById('search-container');
	if(searchField.value == 'Search......') { 
		searchField.value= ''; 
		searchContainer.style.border='1px solid #FF2929'; 
	}
  }
  
function setSearchField(){
	searchField = document.getElementById('search-textbox');
	searchContainer = document.getElementById('search-container');
	if(searchField.value == '') { 
		searchField.value= 'Search......'; 
		searchContainer.style.border='1px solid #cccccc'; 
	}
}

$('a#login-title').live('click',function() {
    $('#login-form').show();
	
    $('html').click(function(event) {
    var $target = $(event.target);

    if($target.parents('#login-form').length == 0) {
        $('#login-form').hide();
    }
  });
});