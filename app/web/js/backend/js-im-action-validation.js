//This function checkAll messages in the list
checked = false;
function checkAll(){
    checked = true;
    for (var i = 0; i < document.getElementById('messageForm').elements.length; i++) {
        document.getElementById('messageForm').elements[i].checked = checked;
    }
}
//This function removes all the checked messages in the list
function unCheckAll(){
    checked = false;
    for (var i = 0; i < document.getElementById('messageForm').elements.length; i++) {
        document.getElementById('messageForm').elements[i].checked = checked;
    }
}

//This function checks if the user has selected a message before performing an action
//[value = represents the action either delete/report/mark message as unread ]
function isChecked(value){
    counter = 0;
    for (var i = 0; i < document.getElementById('messageForm').elements.length; i++) {
        if(document.getElementById('messageForm').elements[i].checked){
            counter++; 
        }
    }
    if(counter<=0){
        if(value=="D"){
            alert("Kindly select Message(s) to Delete");
        }else if(value=="M"){
            alert("Kindly select Message(s) to Mark As Unread");
        }else if(value=="R"){
            alert("Kindly select Message(s) to Report");
        }   
        return false;
    }else{
        if(value=="D"){
            var r=confirm('Once you delete message, it cannot be undone. Are you sure you want to delete message?'); 
            if (r==true){ 
                return true;
            }
        }else if(value=="M"){
            var r=confirm('Are you sure you want to mark selected Messages as unread?'); 
            if (r==true){ 
                return true;
            }
        }else if(value=="R"){
            var r=confirm('Are you sure you want to report selected Messages as unread?'); 
            if (r==true){ 
                return true;
            }
        }
        return false;
    }
}

