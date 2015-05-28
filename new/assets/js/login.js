/**
 * @author cahkaibon
 */
Ext.onReady(function(){

    Ext.QuickTips.init();

    // turn on validation errors beside the field globally
    Ext.form.Field.prototype.msgTarget = 'side';


    var simple = new Ext.FormPanel({
		buttonAlign: 'center',
        labelWidth: 100, // label settings here cascade unless overridden
        url:'save-form.php',
        frame:true,
        title: 'User Login',
        
        width: 300,labelWidth: 100,
        
        defaultType: 'textfield',
		//border:false,
        items: [{
				type:'textfield',
                fieldLabel: 'Username',
                name: 'username',
                allowBlank:false
            },{
                fieldLabel: 'Password',
                name: 'pwd',
				inputType: 'password',
				allowBlank:false
            }
        ],

        buttons: [{
            text: 'Login'
        },{
            text: 'Reset',
			handler:function(){
				this.getForm().reset();
			}
        }]
    });
	
	


    simple.render(document.getElementById("right_main"));

});