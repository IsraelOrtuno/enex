//This function get the ajax data and fetch details from server url
//[url represents the server url, imagePath represent the default image path, action represent passes to load]
function getAJAXData(url,imagePath,action){
    if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
    }
    else {// code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange=function(){
        if (xmlhttp.readyState==4 && xmlhttp.status==200){ 
            var status = "";
            var response = eval("(" + xmlhttp.responseText + ")");
            if(action==0){//search and filter user to chat with
                var userdata = '';
                    for(i=0;i<response.length;i++){
                        name = response[i].first_name+ " " +response[i].last_name;
                        if(response[i].is_login==0){
                            status = "none";
                        }else{
                            status = "block";
                        }
                        userdata += '<a href="javascript:void(0)" class="im-btn" onclick="displayChatBox(\''+response[i].is_login+'\',\''+name+'\',\''+response[i].email+'\',\''+response[i].first_name+'\',\''+response[i].user_id+'\')"  }"><img id="im-avatar" src="'+imagePath+response[i].avatar+'" width="32px" height="32px" /><span>'+name+' <img id="im-status" src="'+imagePath+'online.png" width="10px" height="10px" style="display:'+status+';" /></span></a>';
                }
                document.getElementById("im-new-container").innerHTML = userdata;
                if(userdata==0){
                    document.getElementById("im-new-container").innerHTML = "<label style='color:red;'>No record found</label>";
                }
            }else if(action==1){//search and display user info
                var userdetails = '';
                    for(i=0;i<response.length;i++){
                        username = response[i].first_name+ " " +response[i].last_name;
                        userdetails += '<div id="im-to-user-details" onclick="displayUserEmail(\''+response[i].email+'\')"><span id="im-to-img"><img src="'+imagePath+response[i].avatar+'" width="35px" height="35px" /></span><span id="im-to-user-suggest"><strong>'+username+'</strong><br/><label>'+response[i].email+'</label></span></div>';
                    }
                    //document.getElementById("name-autosuggest").style.display="block";
                    document.getElementById("im-name-autosuggest").innerHTML = userdetails;
                    
                    if(userdetails==0){
                        document.getElementById("im-name-autosuggest").style.display='none';
                    }
                }
            }
    }
    xmlhttp.open("GET",url,true);
    xmlhttp.send(null);
    
}

//This function loads the userdetails for the chat when the page loads
//[imagePath represent image path of the server]
function loadUsers(imagePath){
     getAJAXData("./index.php?c=im&a=getAjaxUserDetails&type=1",imagePath,0);   
     document.getElementById("im-new-container").style.display='block';
     
}

//The function showUser call the getAjaxData() function
function showUser(text,imagePath,action){
    if(action==0){
        getAJAXData("./index.php?c=im&a=getAjaxUserDetails&type=1&s=" +text,imagePath,action);    
        document.getElementById("im-new-container").style.display='block';
    }else if(action==1){
        if(text.length<=0){
             document.getElementById("im-name-autosuggest").style.display='none';
        }else{
            getAJAXData("./index.php?c=im&a=getAjaxUserDetails&type=2&s=" +text,imagePath,action); 
            document.getElementById("im-name-autosuggest").style.display='block';
        }
    }
}

//This function displays a confirmation message if the user is not online or otherwise display the chatbox
function displayChatBox(is_login,name,email,firstname,user_id){
    if(is_login==0){ 
        var r=confirm(name+' is currently not online do you wish you send an offline message'); 
        if (r==true){ 
           // window.location = './index.php?c=im&a=newMessage' 
           
          $(document).ready(function(){
	                $("#inline").fancybox({
                            'overlayColor'     : '#000',  
                            'overlayOpacity'    : 0.7, 
                            'background' : '#000',
                            'scrolling'   : 'no',
                            'hideOnContentClick': false
                            
                        });
                        
                        $("#inline").trigger('click');
                         $("#message-texts-send").val(email);
                         $("#message-content").focus();
                         
                       
                    });  
        } 
        return false; 
     }else{ 
        firstname = $.trim(firstname);
        javascript:chatWith(user_id,firstname); 
        return true;
     }
}

//This function sets the user emails when the message box is selected.
function displayUserEmail(email){
    document.getElementById('message-texts-send').value=email;
    document.getElementById("im-name-autosuggest").style.display='none';
    document.getElementById('message-content').focus();
    
    
}






