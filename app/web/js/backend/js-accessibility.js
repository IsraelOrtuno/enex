/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

// This javascript is to implement accessibility on the application
var isCtrl = false; 
var isAlt = false;
var isShift = false;

//sets the shift and ctr and alt key to false
document.onkeyup=function(e){ 
    if(e.which == 16) isShift=false; 
    if(e.which == 17 || e.which == 18) alert(e.which);isCtrlAlt=false;  
    if(e.which == 18) isAlt=false; 
}

document.onkeydown=function(e){ 
    
        if(e.which == 18) isAlt=true; 
            if(e.which == 77 && isAlt == true) { //opens Message when alt-M is pressed
                alert('Message');
                return false;
            }
            if(e.which == 79 && isAlt == true) { //opens Option Menu when alt-O is pressed
                 $(function(){
                     $("#dropdown-container").toggle();
                 });
                return false;
            }
            if(e.which == 80 && isAlt == true) { //opens Pic Gallery when alt-P is pressed
                alert('Picture');
                return false;
            } 
            if(e.which == 81 && isAlt == true) { //Logout when alt-Q is pressed
                var r=confirm("Are you sure you want to exit?");
                if (r==true){
                    window.location = './index.php?c=home'
                }
                return false;
            } 
       else if(e.which == 16) isShift=true; 
            if(e.which == 72 && isShift == true) { //opens Home when ctr-alt-H is pressed
                alert('Home'); 
                return false;
            }
            if(e.which == 83 && isShift == true) { //opens Search when ctr-alt-S is pressed
                searchField = document.getElementById('search-textbox');
                searchContainer = document.getElementById('search-container');
                searchContainer.style.border='1px solid #FF2929'; 
                if(searchField.value.length>0) { 
                    searchField.value= ''; 
                    searchField.focus();
                }
                return false;
            }
            if(e.which == 77 && isShift == true) { //opens Message Search when ctr-alt-M is pressed
                searchField = document.getElementById('im-textbox');
                searchContainer = document.getElementById('im-search-container');
                searchContainer.style.border='1px solid #FF2929'; 
                if(searchField.value.length>0) { 
                    searchField.value= ''; 
                    searchField.focus();
                }
                return false;
            }    
}
