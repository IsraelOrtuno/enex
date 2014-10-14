function clearSearchField(textBoxID,containerID){
	searchField = document.getElementById(textBoxID);
	searchContainer = document.getElementById(containerID);
        searchContainer.style.border='1px solid #FF2929'; 	
	if(searchField.value== 'Search......') { 
		searchField.value= ''; 
	}
        
  }
  
function setSearchField(textBoxID,containerID){
	searchField = document.getElementById(textBoxID);
	searchContainer = document.getElementById(containerID);
        searchContainer.style.border='1px solid #cccccc'; 
	
        if(searchField.value.length == '') { 
		searchField.value= 'Search......';
	}
        
}
