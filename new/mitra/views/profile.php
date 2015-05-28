//change password
        Ext.ns('Profile');
        Profile.Form = Ext.extend(Ext.form.FormPanel, {
        
            // defaults - can be changed from outside
            border: false,
            frame: true,
            labelWidth: 150,
            url: '<?= site_url("auth/change_password") ?>',
            constructor: function(config){
                config = config || {};
                config.listeners = config.listeners || {};
                Ext.applyIf(config.listeners, {
                    actioncomplete: function(){
                         //if (console && console.log) {
                         //  console.log('actioncomplete:', arguments);
                        //}
                    },
                    actionfailed: function(){
                        //if (console && console.log) {
                        //    console.log('actionfailed:', arguments);
                        //}
                    }
                });
                Profile.Form.superclass.constructor.call(this, config);
            },
            initComponent: function(){
                var config = {
                    defaultType: 'textfield',
                    defaults: { labelSeparator: ''},
                    monitorValid: true,
                    autoScroll: true // ,buttonAlign:'right'
                    ,
                    items: [{
                        fieldLabel: 'Password Lama',
                        name: 'old_password',
                        id: 'id_old_password',
                        maxLength: <?= $this->config->item('max_password_length') ?>,
                        minLength: <?= $this->config->item('min_password_length') ?>,
                        inputType: 'password',
						anchor: '90%',
                        allowBlank: false
                    },{
                        fieldLabel: 'Password Baru',
                        name: 'new_password',
                        id: 'id_new_password',
                        maxLength: <?= $this->config->item('max_password_length') ?>,
                        minLength: <?= $this->config->item('min_password_length') ?>,
                        inputType: 'password',
						anchor: '90%',
                        allowBlank: false
                    },{
                        fieldLabel: 'Ulangi Password Baru',
                        name: 're_new_password',
                        id: 'id_re_new_password',
                        maxLength: <?= $this->config->item('max_password_length') ?>,
                        minLength: <?= $this->config->item('min_password_length') ?>,
                        inputType: 'password',
						anchor: '90%',
                        allowBlank: false
                    }],
                    buttons: [{
                        text: 'Submit',
                        id: 'btnSubmitProfile',
                        formBind: true,
                        scope: this,
                        handler: this.submit
                    }, {
                        text: 'Close',
                        scope: this,
                        handler: function(){
                            Ext.getCmp('profile-form').getForm().reset();
                            winProfile.hide();
                        }
                    }]
                }; // eo config object
                // apply config
                Ext.apply(this, Ext.apply(this.initialConfig, config));
                
                // call parent
                Profile.Form.superclass.initComponent.apply(this, arguments);
                
            } // eo function initComponent	
            ,
            onRender: function(){
                
                // call parent
                Profile.Form.superclass.onRender.apply(this, arguments);
                
                // set wait message target
                
                this.getForm().waitMsgTarget = this.getEl();
                
                // loads form after initial layout
                // this.on('afterlayout', this.onLoadClick, this, {single:true});
            
            } // eo function onRender
            ,
            
            submit: function(){
            
                this.getForm().submit({
                    url: this.url,
                    scope: this,
                    success: this.onSuccess,
                    failure: this.onFailure,
                    params: {
                        cmd: 'save'
                    },
                    waitMsg: 'Saving Data...'
                });
            } // eo function submit
            ,
            onSuccess: function(form, action){
                Ext.Msg.show({
                    title: 'Success',
                    msg: '<?= $this->lang->line('password_change_successful') ?>',
                    modal: true,
                    icon: Ext.Msg.INFO,
                    buttons: Ext.Msg.OK
                });
                
                Ext.getCmp('profile-form').getForm().reset();
                winProfile.hide();
            } // eo function onSuccess
            ,
            onFailure: function(form, action){
            
                var fe = Ext.util.JSON.decode(action.response.responseText);
            	this.showError(fe.errMsg || '');
                
                
            } // eo function onFailure
            ,
            showError: function(msg, title){
	            title = title || 'Error';
	            Ext.Msg.show({
	                title: title,
	                msg: msg,
	                modal: true,
	                icon: Ext.Msg.ERROR,
	                buttons: Ext.Msg.OK,
	                fn: function(btn){
	                    if (btn == 'ok' && msg == 'Session Expired') {
	                        window.location = '<?= site_url("auth/login") ?>';
	                    }
	                }
	            });
	        }
        }); // eo extend
        // register xtype
        Ext.reg('formProfile', Profile.Form);
        
        var winProfile = new Ext.Window({
            id: 'profile-win',
            title: 'Form Change Password',
            closeAction: 'hide',
            width: 450,
            height: 350,
            layout: 'fit',
            border: false,
            items: {
                id: 'profile-form',
                xtype: 'formProfile'
            },
            onHide: function(){
                Ext.getCmp('profile-form').getForm().reset();
            }
        });