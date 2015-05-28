<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">    
    /* START FORM */ 
    Ext.ns('propinsiform');
    propinsiform.Form = Ext.extend(Ext.form.FormPanel, {
    
        // defaults - can be changed from outside
        border: false,
        frame: true,
        labelWidth: 100,
		waitMsg:'Loading...',
        url: '<?= site_url("wilayah_propinsi/update_row") ?>',
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
            propinsiform.Form.superclass.constructor.call(this, config);
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
                    name: 'kd_propinsi'
                }, {
                    type: 'textfield',
                    fieldLabel: 'Propinsi <span class="asterix">*</span>',
                    name: 'nama_propinsi',
                    allowBlank: false,
                    id: 'id_nama_propinsi',
                    anchor: '90%',
                    maxLength: 40,
					style:'text-transform: uppercase',  
					       
                }],
                buttons: [{
                    text: 'Submit',
                    id: 'btnsubmitpropinsi',
                    formBind: true,
                    scope: this,
                    handler: this.submit
                }, {
                    text: 'Reset',
                    id: 'btnresetpropinsi',
                    scope: this,
                    handler: this.reset
                }, {
                    text: 'Close',
                    id: 'btnClosepropinsi',
                    scope: this,
                    handler: function(){
                        winaddpropinsi.hide();
                    }
                }]
            }; // eo config object
            // apply config
            Ext.apply(this, Ext.apply(this.initialConfig, config));
            
            // call parent
            propinsiform.Form.superclass.initComponent.apply(this, arguments);
            
        } // eo function initComponent	
        ,
        onRender: function(){
        
            // call parent
            propinsiform.Form.superclass.onRender.apply(this, arguments);
            
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
			var text = Ext.getCmp('btnsubmitpropinsi').getText();
			if (text == 'Update'){
				Ext.Msg.show({
					title: 'Confirm',
					msg: 'Are you sure update selected row ?',
					buttons: Ext.Msg.YESNO,
					fn: function(btn){
						if (btn == 'yes') {
							Ext.getCmp('id_formaddpropinsi').getForm().submit({
								url: Ext.getCmp('id_formaddpropinsi').url,
								scope: this,
								success: Ext.getCmp('id_formaddpropinsi').onSuccess,
								failure: Ext.getCmp('id_formaddpropinsi').onFailure,
								params: {
									cmd: 'save'
								},
								waitMsg: 'Saving Data...'
							});
						}
					}
				})
			}else{
				Ext.getCmp('id_formaddpropinsi').getForm().submit({
					url: Ext.getCmp('id_formaddpropinsi').url,
					scope: this,
					success: Ext.getCmp('id_formaddpropinsi').onSuccess,
					failure: Ext.getCmp('id_formaddpropinsi').onFailure,
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
            
            
            strpropinsi.reload();
            Ext.getCmp('id_formaddpropinsi').getForm().reset();
            winaddpropinsi.hide();
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
    Ext.reg('formaddpropinsi', propinsiform.Form);
    
    var winaddpropinsi = new Ext.Window({
        id: 'id_winaddpropinsi',
        closeAction: 'hide',
        width: 450,
        height: 350,
        layout: 'fit',
        border: false,
        items: {
            id: 'id_formaddpropinsi',
            xtype: 'formaddpropinsi'
        },
        onHide: function(){
            Ext.getCmp('id_formaddpropinsi').getForm().reset();
        }
    });
    
	/* START GRID */    
	
	// data store
	var strpropinsi = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
				'kd_propinsi',
				'nama_propinsi'
			],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("wilayah_propinsi/get_rows") ?>',
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
	
	// search field
    var searchpropinsi = new Ext.app.SearchField({
        store: strpropinsi,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        id: 'idsearchpropinsi'
    });
    
	// top toolbar
    var tbpropinsi = new Ext.Toolbar({
        items: [{
            text: 'Add',
            icon: BASE_ICONS + 'add.png',
            onClick: function(){				
                Ext.getCmp('btnresetpropinsi').show();
                Ext.getCmp('btnsubmitpropinsi').setText('Submit');
                winaddpropinsi.setTitle('Add Form');
                winaddpropinsi.show();                
            }            
        }, '-', searchpropinsi]
    });
	
	// checkbox grid
    var cbGrid = new Ext.grid.CheckboxSelectionModel();
    
	// row actions
	var actionpropinsi = new Ext.ux.grid.RowActions({
		header: 'Edit',
		autoWidth: false,
		width: 30,
	    actions:[{iconCls: 'icon-edit-record', qtip: 'Edit'}],
	    widthIntercept: Ext.isSafari ? 4 : 2
	});	
	var actionpropinsidel = new Ext.ux.grid.RowActions({
		header: 'Delete',
		autoWidth: false,
		width: 40,
	    actions:[{iconCls: 'icon-delete-record', qtip: 'Delete'}],
	    widthIntercept: Ext.isSafari ? 4 : 2
	});	
	
	actionpropinsi.on('action', function(grid, record, action, row, col) {
		var kd_propinsi = record.get('kd_propinsi');
		switch(action) {
			case 'icon-edit-record':	        	
				editpropinsi(kd_propinsi);
	        	break;
	      	case 'icon-delete-record':
				deletepropinsi();
	        	break;	      
	      	
	    }
	});  
  	
	var multisearchpropinsi = new Ext.ux.grid.Search({
				iconCls:'icon-zoom'
				//,readonlyIndexes:['note']
				//,disableIndexes:['pctChange']
				,minChars:3
				,autoFocus:true
				,width:250
			});
			
	// grid
    var propinsi = new Ext.grid.EditorGridPanel({
        //id: 'id-propinsi-gridpanel',
        id: 'propinsi',
		frame: true,
        border: true,
        stripeRows: true,
        sm: cbGrid,
        store: strpropinsi,
		//closable:true,
        loadMask: true,
        style: 'margin:0 auto;',
        height: 450,
        columns: [actionpropinsi,
	{
            header: "Kode Propinsi",
            dataIndex: 'kd_propinsi',
            sortable: true,
            width: 150
        },{
            header: "Nama Propinsi",
            dataIndex: 'nama_propinsi',
            sortable: true,
            width: 300
        }],
		plugins: [actionpropinsi,multisearchpropinsi],
        listeners: {
            'rowdblclick': function(){				
                var sm = propinsi.getSelectionModel();                
                var sel = sm.getSelections();                
                if (sel.length > 0) {
					editpropinsi(sel[0].get('kd_propinsi'));                    
                }                 
            }          
        },
        tbar: tbpropinsi,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strpropinsi,
            displayInfo: true
        })
    });
	/**
	var propinsipanel = new Ext.FormPanel({
	 	id: 'propinsi',
		border: false,
        frame: false,
		autoScroll:true,	
        items: [propinsi]
	});
   **/
	function editpropinsi(kd_propinsi){
		Ext.getCmp('btnresetpropinsi').hide();
        Ext.getCmp('btnsubmitpropinsi').setText('Update');
        winaddpropinsi.setTitle('Edit Form');
        Ext.getCmp('id_formaddpropinsi').getForm().load({
            url: '<?= site_url("wilayah_propinsi/get_row") ?>',
            params: {
                id: kd_propinsi,
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
        winaddpropinsi.show();
	}
	
    function deletepropinsi(){		
        var sm = propinsi.getSelectionModel();
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
                            data = data + sel[i].get('kd_propinsi') + ';';
                        }
                        
                        Ext.Ajax.request({
                            url: '<?= site_url("wilayah_propinsi/delete_rows") ?>',
                            method: 'POST',
                            params: {
                                postdata: data
                            },
							callback:function(opt,success,responseObj){
								var de = Ext.util.JSON.decode(responseObj.responseText);
								if(de.success==true){
									strpropinsi.reload();
	                                strpropinsi.load({
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
