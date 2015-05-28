<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">  

    var limitapproval_frm = new Ext.FormPanel({
        id: 'limitapproval',
        border: false,
        frame: true,
        autoScroll:true, 
        monitorValid: true,       
        bodyStyle:'padding-right:20px;',
        labelWidth: 130,
        //        layout: 'form',
        items: [
            {
                xtype:'fieldset',
                id:'limitapv_apv1',
                name:'fs_limitapv_apv1',
                title: 'Approval 1',                        
                autoHeight:true,  
                anchor: '40%',
                layout: 'form',
                items :[
                    {xtype: 'numericfield',
                        currencySymbol: '',
                        fieldLabel: 'Limit Approval1 <span class="asterix">*</span>',
                        name: 'startapv1',					
                        id: 'id_startapv1',										
                        anchor: '90%',	
                        fieldClass:'number',	
                        allowBlank: false,
                        value:'0'},
                    {xtype: 'numericfield',
                        currencySymbol: '',
                        fieldLabel: 'End Limit Approval1 <span class="asterix">*</span>',
                        name: 'endapv1',					
                        id: 'id_endapv1',										
                        anchor: '90%',	
                        fieldClass:'number',	
                        allowBlank: false,
                        value:'0'}
                ]
            },
            {
                xtype:'fieldset',
                id:'limitapv_apv2',
                name:'fs_limitapv_apv2',
                title: 'Approval 2',                        
                autoHeight:true,  
                anchor: '40%',
                layout: 'form',
                items :[
                    {xtype: 'numericfield',
                        currencySymbol: '',
                        fieldLabel: 'Limit Approval2 <span class="asterix">*</span>',
                        name: 'startapv2',					
                        id: 'id_startapv2',										
                        anchor: '90%',	
                        fieldClass:'number',	
                        allowBlank: false,
                        value:'0'},
                    {xtype: 'numericfield',
                        currencySymbol: '',
                        fieldLabel: 'End Limit Approval2 <span class="asterix">*</span>',
                        name: 'endapv2',					
                        id: 'id_endapv2',										
                        anchor: '90%',	
                        fieldClass:'number',	
                        allowBlank: false,
                        value:'0'}
                ]
            },
            {
                xtype:'fieldset',
                id:'limitapv_apv3',
                name:'fs_limitapv_apv3',
                title: 'Approval 3',                        
                autoHeight:true,  
                anchor: '40%',
                layout: 'form',
                items :[
                    {xtype: 'numericfield',
                        currencySymbol: '',
                        fieldLabel: 'Limit Approval3 <span class="asterix">*</span>',
                        name: 'startapv3',					
                        id: 'id_startapv3',										
                        anchor: '90%',	
                        fieldClass:'number',	
                        allowBlank: false,
                        value:'0'},
                    {xtype: 'numericfield',
                        currencySymbol: '',
                        fieldLabel: 'End Limit Approval3 <span class="asterix">*</span>',
                        name: 'endapv3',					
                        id: 'id_endapv3',										
                        anchor: '90%',	
                        fieldClass:'number',	
                        allowBlank: false,
                        value:'0'}
                ]
            }
        ] ,
        buttons: [{
                text: 'Save',
                formBind: true,
                handler: function(){    
                    var startapv1=0, endapv1=0,startapv2=0,endapv2=0,startapv3=0,endapv3=0 ;
                    
                    startapv1=Ext.getCmp('id_startapv1').getValue();
                    endapv1=Ext.getCmp('id_endapv1').getValue();
                    startapv2=Ext.getCmp('id_startapv2').getValue();
                    endapv2=Ext.getCmp('id_endapv2').getValue();
                    startapv3=Ext.getCmp('id_startapv3').getValue();
                    endapv3=Ext.getCmp('id_endapv3').getValue();
                    
                    Ext.getCmp('limitapproval').getForm().submit({
                        url: '<?= site_url("account_limit_approval/update_row") ?>',
                        scope: this,
                        params: {
                                                    
                            pstartapv1:startapv1,
                            pendapv1:endapv1,
                            pstartapv2:startapv2,
                            pendapv2:endapv2,
                            pstartapv3:startapv3,
                            pendapv3:endapv3
                        },
                        waitMsg: 'Saving Data...',
                        success: function(form, action){
                            Ext.Msg.show({
                                title: 'Success',
                                msg: 'Form submitted successfully',
                                modal: true,
                                icon: Ext.Msg.INFO,
                                buttons: Ext.Msg.OK
                            });			            
                            load_formlimit();		
                        },
                        failure: function(form, action){        
                            var fe = Ext.util.JSON.decode(action.response.responseText);			            
                            Ext.Msg.show({
                                title: 'Error',
                                msg: fe.errMsg,
                                modal: true,
                                icon: Ext.Msg.ERROR,
                                buttons: Ext.Msg.OK,
                                fn: function(btn){
                                    if (btn == 'ok' && fe.errMsg == 'Session Expired') {
                                        window.location = '<?= site_url("auth/login") ?>';
                                    }
                                }
                            });
			            
                        }			        
                    });
                }
            }
            ,{
                text: 'Reset',
                handler: function(){
                    load_formlimit(); 
                }
            }
        ],
        listeners:{
            afterrender:function(){                
                this.getForm().load({
                    url: '<?= site_url("account_limit_approval/get_form") ?>',
                    failure: function(form, action){
                        var de = Ext.util.JSON.decode(action.response.responseText);
                        Ext.Msg.show({
                            title: 'Error',
                            msg: de.errMsg,
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK,
                            fn: function(btn){
                                if (btn == 'ok' && de.errMsg == 'Session Expired') {
                                    window.location = '<?= site_url("auth/login") ?>';
                                }
                            }
                        });
                    }
                });
            },
            show:function(){
                load_formlimit();
            }
        }
                
    });
    function load_formlimit(){
    Ext.getCmp('limitapproval').getForm().load({
                    url: '<?= site_url("account_limit_approval/get_form") ?>',
                    failure: function(form, action){
                        var de = Ext.util.JSON.decode(action.response.responseText);
                        Ext.Msg.show({
                            title: 'Error',
                            msg: de.errMsg,
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK,
                            fn: function(btn){
                                if (btn == 'ok' && de.errMsg == 'Session Expired') {
                                    window.location = '<?= site_url("auth/login") ?>';
                                }
                            }
                        });
                    }
                });
    }
</script>