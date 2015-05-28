Ext.onReady(function() {

    /* 01. Start The Form Register Component */

    // 01. Form Register
	var formRegister = new Ext.FormPanel({
		frame: false, border: false, buttonAlign: 'center',
		url: BASE_URL + 'user/ext_register', method: 'POST', id: 'frmRegister',
		bodyStyle: 'padding:10px 10px 15px 15px;background:#dfe8f6;',
		width: 300, labelWidth: 150,
		items: [{
			xtype: 'textfield',
			fieldLabel: 'Username',
			name: 'username',
			id: 'regUsername',
			allowBlank: false,
            vtype: 'uniqueusername'
		}, {
			xtype: 'textfield',
			fieldLabel: 'Password',
			name: 'password',
			allowBlank: false,
			inputType: 'password',
            vtype: 'passwordlength',
            id: 'pass1'
		}, {
			xtype: 'textfield',
			fieldLabel: 'Confirm Password',
			name: 'cpassword',
			allowBlank: false,
			inputType: 'password',
            id: 'pass2',
            initialPassField: 'pass1',
            vtype: 'password'
		}, {
			xtype: 'textfield',
			fieldLabel: 'Email',
            id: 'regEmail',
			name: 'email',
			vtype:'uniqueemail',
			allowBlank: false,
            validationEvent: ''
		}
		],
		buttons: [
			{ text: 'Register', handler: fnRegister },
			{ text: 'Reset', handler: function() {
					formRegister.getForm().reset();
				}
			}
		]
	});

    function fnRegister() {
        Ext.getCmp('frmRegister').on({
            beforeaction: function() {
                if (formRegister.getForm().isValid()) {
                    Ext.getCmp('winRegister').body.mask();
                    Ext.getCmp('winLogin').body.mask();
                    Ext.getCmp('sbWinRegister').showBusy();
                }
            }
        });
        formRegister.getForm().submit({
            success: function() {
                formRegister.getForm().reset();
                Ext.getCmp('sbWinRegister').setStatus({
                    text: 'Registration Success',
                    iconCls: 'x-status-saved'
                });
                Ext.getCmp('winRegister').body.unmask();
                Ext.getCmp('winLogin').body.unmask();
            }
        });
    }

    // 01. Window Register
	var winRegister = new Ext.Window({
		title: 'CI Extjs &mdash; User Register',
        id: 'winRegister',
		layout: 'fit',
		width: 350,
		height: 210,
		y: 120,
		resizable: false,
		closable: false,
		items: [formRegister],
        bbar: new Ext.ux.StatusBar({
            text: 'Ready',
            id: 'sbWinRegister'
        })
	});

	winRegister.show();

    /* 02. Start The Form Login Component */

    // 02. Form Login
    var formLogin = new Ext.FormPanel({
		frame: false, border: false, buttonAlign: 'center',
		url: BASE_URL + 'user/ext_login', method: 'POST', id: 'frmLogin',
		bodyStyle: 'padding:10px 10px 15px 15px;background:#dfe8f6;',
		width: 300, labelWidth: 150,
		items: [{
			xtype: 'textfield',
			fieldLabel: 'Username',
			name: 'username',
			id: 'logUsername',
			allowBlank: false
		}, {
			xtype: 'textfield',
			fieldLabel: 'Password',
			name: 'password',
            id: 'logPassword',
			allowBlank: false,
			inputType: 'password'
		}
		],
		buttons: [
			{ text: 'Login', handler: fnLogin },
			{ text: 'Reset', handler: function() {
					formLogin.getForm().reset();
				}
			}
		]
	});

    function fnLogin() {
        Ext.getCmp('frmLogin').on({
            beforeaction: function() {
                if (formLogin.getForm().isValid()) {
                    Ext.getCmp('winRegister').body.mask();
                    Ext.getCmp('winLogin').body.mask();
                    Ext.getCmp('sbWinLogin').showBusy();
                }
            }
        });
        formLogin.getForm().submit({
           success: function() {
               window.location = BASE_URL;
           },
           failure: function(form, action) {
               Ext.getCmp('winRegister').body.unmask();
               Ext.getCmp('winLogin').body.unmask();
               if (action.failureType == 'server') {
                    obj = Ext.util.JSON.decode(action.response.responseText);
                    Ext.getCmp('sbWinLogin').setStatus({
                        text: obj.errors.reason,
                        iconCls: 'x-status-error'
                    });
                } else {
                    if (formLogin.getForm().isValid()) {
                        Ext.getCmp('sbWinLogin').setStatus({
                            text: 'Authentication server is unreachable',
                            iconCls: 'x-status-error'
                        });
                    } else {
                        Ext.getCmp('sbWinLogin').setStatus({
                            text: 'Something error in form !',
                            iconCls: 'x-status-error'
                        });
                    }
                }
           }
        });
    }

    // 02. Window Login
	var winLogin = new Ext.Window({
		title: 'CI Extjs &mdash; User Login',
        id: 'winLogin',
		layout: 'fit',
		width: 350,
		height: 160,
		y: 340,
		resizable: false,
		closable: false,
		items: [formLogin],
        bbar: new Ext.ux.StatusBar({
            text: 'Ready',
            id: 'sbWinLogin'
        })
	});

	winLogin.show();
});