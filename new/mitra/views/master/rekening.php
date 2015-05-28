<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">    
    /* START FORM */ 
    Ext.ns('rekeningform');
    rekeningform.Form = Ext.extend(Ext.form.FormPanel, {
    
        // defaults - can be changed from outside
        border: false,
        frame: true,
        labelWidth: 100,
        url: '<?= site_url("rekening/update_row") ?>',
        constructor: function(config){
            config = config || {};
            config.listeners = config.listeners || {};
            Ext.applyIf(config.listeners, {
                actioncomplete: function(){
                    //if (console && console.log) {
                    //    console.log('actioncomplete:', arguments);
                    //}
                },
                actionfailed: function(){
                    //if (console && console.log) {
                    //    console.log('actionfailed:', arguments);
                    //}
                }
            });
            rekeningform.Form.superclass.constructor.call(this, config);
        },
        initComponent: function(){
        
            // hard coded - cannot be changed from outsid
            var config = {
                defaultType: 'textfield',
                defaults: { labelSeparator: ''},
                monitorValid: true,
                autoScroll: false // ,buttonAlign:'right'
                ,
                items: [{
                    xtype: 'hidden',
                    name: 'kd_rekening'
                }, {
                    type: 'textfield',
                    fieldLabel: 'No Rekening <span class="asterix">*</span>',
                    name: 'no_rekening',
                    allowBlank: false,
                    id: 'id_no_rekening',
                    maxLength: 20,
                    anchor: '90%'                
                }, {
                    type: 'textfield',
                    fieldLabel: 'Nama Rekening <span class="asterix">*</span>',
                    name: 'nm_rekening',
                    allowBlank: false,
                    id: 'id_nm_rekening',
                    maxLength: 40,
                    anchor: '90%'                
                }],
                buttons: [{
                    text: 'Submit',
                    id: 'btnsubmitrekening',
                    formBind: true,
                    scope: this,
                    handler: this.submit
                }, {
                    text: 'Reset',
                    id: 'btnresetrekening',
                    scope: this,
                    handler: this.reset
                }, {
                    text: 'Close',
                    id: 'btnClose',
                    scope: this,
                    handler: function(){
                        winaddrekening.hide();
                    }
                }]
            }; // eo config object
            // apply config
            Ext.apply(this, Ext.apply(this.initialConfig, config));
            
            // call parent
            rekeningform.Form.superclass.initComponent.apply(this, arguments);
            
        } // eo function initComponent	
        ,
        onRender: function(){
        
            // call parent
            rekeningform.Form.superclass.onRender.apply(this, arguments);
            
            // set wait message target
            this.getForm().waitMsgTarget = this.getEl();
            
            // loads form after initial layout
            // this.on('afterlayout', this.onLoadClick, this, {single:true});
        
        } // eo function onRender
        ,
        reset: function(){
            this.getForm().reset();
        },
        submit: function(){
			var text = Ext.getCmp('btnsubmitrekening').getText();
			if (text == 'Update'){
				Ext.Msg.show({
					title: 'Confirm',
					msg: 'Are you sure update selected row ?',
					buttons: Ext.Msg.YESNO,
					fn: function(btn){
						if (btn == 'yes') {
							Ext.getCmp('id_formaddrekening').getForm().submit({
								url: Ext.getCmp('id_formaddrekening').url,
								scope: this,
								success: Ext.getCmp('id_formaddrekening').onSuccess,
								failure: Ext.getCmp('id_formaddrekening').onFailure,
								params: {
									cmd: 'save'
								},
								waitMsg: 'Saving Data...'
							});
						}
					}
				})
			}else{
				Ext.getCmp('id_formaddrekening').getForm().submit({
					url: Ext.getCmp('id_formaddrekening').url,
					scope: this,
					success: Ext.getCmp('id_formaddrekening').onSuccess,
					failure: Ext.getCmp('id_formaddrekening').onFailure,
					params: {
						cmd: 'save'
					},
					waitMsg: 'Saving Data...'
				});
			}
        } // eo function submit
        ,
        onSuccess: function(form, action){
            Ext.Msg.show({
                title: 'Success',
                msg: 'Form submitted successfully',
                modal: true,
                icon: Ext.Msg.INFO,
                buttons: Ext.Msg.OK
            });
            
            
            strrekening.reload();
            Ext.getCmp('id_formaddrekening').getForm().reset();
            winaddrekening.hide();
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
    Ext.reg('formaddrekening', rekeningform.Form);
    
    var winaddrekening = new Ext.Window({
        id: 'id_winaddrekening',
        closeAction: 'hide',
        width: 450,
        height: 350,
        layout: 'fit',
        border: false,
        items: {
            id: 'id_formaddrekening',
            xtype: 'formaddrekening'
        },
        onHide: function(){
            Ext.getCmp('id_formaddrekening').getForm().reset();
        }
    });
    
	/* START GRID */    
	var strrekening = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
				'kd_rekening', 
				'nm_rekening',
				'no_rekening'
			],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("rekening/get_rows") ?>',
            method: 'POST'
        }),
        listeners: {
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });
	
    var searchrekening = new Ext.app.SearchField({
        store: strrekening,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        id: 'idsearchrekening'
    });
    
    var tbrekening = new Ext.Toolbar({
        items: [{
            text: 'Add',
            icon: BASE_ICONS + 'add.png',
            onClick: function(){				
                Ext.getCmp('btnresetrekening').show();
                Ext.getCmp('btnsubmitrekening').setText('Submit');
                winaddrekening.setTitle('Add Form');
                winaddrekening.show();                
            }            
        }, '-', searchrekening]
    });

    var cbGrid = new Ext.grid.CheckboxSelectionModel();
    	
	// row actions
	var actionrekening = new Ext.ux.grid.RowActions({
		header :'Edit',
		autoWidth: false,
		width: 30,
	    actions:[{iconCls: 'icon-edit-record', qtip: 'Edit'}],
	    widthIntercept: Ext.isSafari ? 4 : 2
	});	
	
	var actionrekeningdel = new Ext.ux.grid.RowActions({
		header: 'Delete',
		autoWidth: false,
		width: 40,
	    actions:[{iconCls: 'icon-delete-record', qtip: 'Delete'}],
	    widthIntercept: Ext.isSafari ? 4 : 2
	});	
	
	actionrekening.on('action', function(grid, record, action, row, col) {
		var kd_rekening = record.get('kd_rekening');
		switch(action) {
			case 'icon-edit-record':	        	
				editrekening(kd_rekening);
	        	break;
	      	case 'icon-delete-record':
				Ext.Msg.show({
	                title: 'Confirm',
	                msg: 'Are you sure delete selected row ?',
	                buttons: Ext.Msg.YESNO,
	                fn: function(btn){
	                    if (btn == 'yes') {
	                        Ext.Ajax.request({
	                            url: '<?= site_url("rekening/delete_row") ?>',
	                            method: 'POST',
	                            params: {
	                                kd_rekening: kd_rekening
	                            },
								callback:function(opt,success,responseObj){
									var de = Ext.util.JSON.decode(responseObj.responseText);
									if(de.success==true){
										strrekening.reload();
		                                strrekening.load({
		                                    params: {
		                                        start: STARTPAGE,
		                                        limit: ENDPAGE
		                                    }
	                                	});
									}else{
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
								}
	                        });                 
	                    } 
	                }
	            });
	        	break;	      
	      	
	    }
	});  
  	
    var rekening = new Ext.grid.EditorGridPanel({
        //id: 'id-rekening-gridpanel',
        id: 'rekening',
		frame: true,
        border: true,
        stripeRows: true,
        sm: cbGrid,
        store: strrekening,
        loadMask: true,
        style: 'margin:0 auto;',
        height: 450,
        columns: [actionrekening,{
            header: "Kode Rekening",
            dataIndex: 'kd_rekening',
            sortable: true,
            width: 150
        },{
            header: "Nama Rekening",
            dataIndex: 'nm_rekening',
            sortable: true,
            width: 300
        },{
            header: "No Rekening",
            dataIndex: 'no_rekening',
            sortable: true,
            width: 300
        }],
		plugins: [actionrekening],
        listeners: {
            'rowdblclick': function(){
				
                var sm = rekening.getSelectionModel();
                
                var sel = sm.getSelections();
                
                if (sel.length > 0) {
                    Ext.getCmp('btnresetrekening').hide();
                    Ext.getCmp('btnsubmitrekening').setText('Update');
                    winaddrekening.setTitle('Edit Form');
                    Ext.getCmp('id_formaddrekening').getForm().load({
                        url: '<?= site_url("rekening/get_row") ?>',
                        params: {
                            id: sel[0].get('kd_rekening'),
                            cmd: 'get'
                        },                  
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
                    winaddrekening.show();
                }
                 
            }
          
        },
        tbar: tbrekening,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strrekening,
            displayInfo: true
        })
    });
	/**
	var rekeningpanel = new Ext.FormPanel({
	 	id: 'rekening',
		border: false,
        frame: false,
		autoScroll:true,	
        items: [rekening]
	});
	**/
    function editrekening(kd_rekening){
		Ext.getCmp('btnresetrekening').hide();
        Ext.getCmp('btnsubmitrekening').setText('Update');
        winaddrekening.setTitle('Edit Form');
        Ext.getCmp('id_formaddrekening').getForm().load({
            url: '<?= site_url("rekening/get_row") ?>',
            params: {
                id: kd_rekening,
                cmd: 'get'
            },                  
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
        winaddrekening.show();
	}
	
    function deleterekening(){		
        var sm = rekening.getSelectionModel();
        var sel = sm.getSelections();
        if (sel.length > 0) {
            Ext.Msg.show({
                title: 'Confirm',
                msg: 'Are you sure delete selected row ?',
                buttons: Ext.Msg.YESNO,
                fn: function(btn){
                    if (btn == 'yes') {
                    
                        var data = '';
                        for (i = 0; i < sel.length; i++) {
                            data = data + sel[i].get('kd_rekening') + ';';
                        }
                        
                        Ext.Ajax.request({
                            url: '<?= site_url("rekening/delete_rows") ?>',
                            method: 'POST',
                            params: {
                                postdata: data
                            },
							callback:function(opt,success,responseObj){
								var de = Ext.util.JSON.decode(responseObj.responseText);
								if(de.success==true){
									strrekening.reload();
	                                strrekening.load({
	                                    params: {
	                                        start: STARTPAGE,
	                                        limit: ENDPAGE
	                                    }
                                	});
								}else{
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
							}
                        });                 
                    } 
                }
            });
        }
        else {
        	Ext.Msg.show({
                title: 'Info',
                msg: 'Please selected row',
                modal: true,
                icon: Ext.Msg.ERROR,
                buttons: Ext.Msg.OK
            });
        }
        
    }
</script>
