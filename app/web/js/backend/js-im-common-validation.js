//This function displays a confirmation message when a user selects an action[reply,report,mark as unread, delete]
//[v = selected icon] [id = message id]

function performAction(v,id,reported){
    if(v==0){
        var r=confirm('Are you sure you want to mark message as unread?'); 
        if (r==true){
            window.location='./index.php?c=im&a=performSingleAction&v=M&m_id='+id;
        } 
        return false;
    }else if(v==1){
        if(reported==1){
            alert('Message have already been reported to the administrator');
        }else{
            var r=confirm('Are you sure you want to report message?'); 
            if (r==true){
                window.location='./index.php?c=im&a=performSingleAction&v=R&m_id='+id;
            } 
        }
        return false;
    }else if(v==2){
        var r=confirm('Once you delete message, action cannot be undone. Are you sure you want to delete message?'); 
        if (r==true){
            window.location='./index.php?c=im&a=performSingleAction&v=D&m_id='+id;
        } 
        return false;
    }
}

//This function displays the action dropdown when the user clicks and hides it when the user clicks out
$('#btn-action').live('click',function() {
    $('#action-menu').show();
    $('html').click(function(event) {
        var $target = $(event.target);
        if($target.parents('#action-menu').length == 0) {
            $('#action-menu').hide();
        }
    });
});

//This function displays the success message and hides it after 7 secs
$(document).ready(function(){
    if( $("#success-msg").is(":visible")){
        $("#success-msg").fadeOut(7000);
    }
});