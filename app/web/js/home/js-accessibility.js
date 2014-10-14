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
    if(e.which == 18) isAlt=false; 
}

document.onkeydown=function(e){ 
    
        if(e.which == 18) isAlt=true; 
            if(e.which == 76 && isAlt == true) { //opens Option Menu when alt-L is pressed
                 $(function(){
                     $("#login-form").toggle();
                     document.getElementById('login-email-address').focus();
                 });
                return false;
            }
            
}
