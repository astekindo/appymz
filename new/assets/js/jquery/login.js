$(document).ready(function() {
	$(".username").focus(function() {
		$(".user-icon").css("left","-48px");
	});
	$(".username").blur(function() {
		$(".user-icon").css("left","0px");
	});
	
	$(".password").focus(function() {
		$(".pass-icon").css("left","-48px");
	});
	$(".password").blur(function() {
		$(".pass-icon").css("left","0px");
	});
	
	$('.autoF').focus();
    $("#frmLogin").validationEngine();
    
	$('input:text').bind('keypress', function (e) {
        if (e.keyCode == 13) //Enter key
        {
            var tabindex = $(this).attr('tabindex');
            tabindex++;
            $('[tabindex=' + tabindex + ']').focus();

            return false;
        }
    });

    $("#frmLogin").submit(function(event){
        $(".login-form").mask("Please wait...");
        
		/* stop form from submitting normally */
        event.preventDefault();
        
        /* get some values from elements on the page: */
        var $form = $(this), 
			uname = $form.find('input[name="username"]').val(), 
			pwd = $form.find('input[name="password"]').val(), 
			url = $form.attr('action');
        
        if (uname == '' || pwd == '') {
            $(".login-form").unmask();
            return false;
        }
        
        /* Send the data using post  */
        $.post(url, $("#frmLogin").serialize(), function(result){
            if (result.error) {
				$(".login-form").unmask();
                jAlert(result.message);
                
                return;
            }
            else {  
				$(".login-form").unmask();          
                //jAlert(result.message, "Alert", function(s){
                //    if (s) {
                        window.location = result.redirect;
                //    }
                //});
                return;
            }
			
        }, "json");
		
    });
    
});
