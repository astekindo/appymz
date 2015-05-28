<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">    
    /* START FORM */ 
    Ext.ns('master_dataform');
    master_dataform.Form = Ext.extend(Ext.form.FormPanel, {
    
        // defaults - can be changed from outside
        border: false,
        frame: true,
        labelWidth: 100,
		waitMsg:'Loading...',
        url: '<?= site_url("master_data/update_row") ?>',
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
            master_dataform.Form.superclass.constructor.call(this, config);
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
                    name: 'kd_master_data'
                }, {
                    type: 'textfield',
                    fieldLabel: 'Kategori <span class="asterix">*</span>',
                    name: 'nama_master_data',
                    allowBlank: false,
                    id: 'id_nama_master_data',
                    anchor: '90%',
                    maxLength: 40,
					style:'text-transform: uppercase',  
					       
                }, new Ext.form.Checkbox({
						xtype: 'checkbox',
						fieldLabel: 'Status Aktif <span class="asterix">*</span>',
                    	boxLabel:'Ya',
						name:'aktif',
						id:'k1_aktif',
						inputValue: '1',
						autoLoad : true
				})],
                buttons: [{
                    text: 'Submit',
                    id: 'btnsubmitmaster_data',
                    formBind: true,
                    scope: this,
                    handler: this.submit
                }, {
                    text: 'Reset',
                    id: 'btnresetmaster_data',
                    scope: this,
                    handler: this.reset
                }, {
                    text: 'Close',
                    id: 'btnClosemaster_data',
                    scope: this,
                    handler: function(){
                        winaddmaster_data.hide();
                    }
                }]
            }; // eo config object
            // apply config
            Ext.apply(this, Ext.apply(this.initialConfig, config));
            
            // call parent
            master_dataform.Form.superclass.initComponent.apply(this, arguments);
            
        } // eo function initComponent	
        ,
        onRender: function(){
        
            // call parent
            master_dataform.Form.superclass.onRender.apply(this, arguments);
            
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
				var text = Ext.getCmp('btnsubmitmaster_data').getText();
				if (text == 'Update'){
					Ext.Msg.show({
						title: 'Confirm',
						msg: 'Are you sure update selected row ?',
						buttons: Ext.Msg.YESNO,
						fn: function(btn){
							if (btn == 'yes') {
								Ext.getCmp('id_formaddmaster_data').getForm().submit({
									url: Ext.getCmp('id_formaddmaster_data').url,
									scope: this,
									success: Ext.getCmp('id_formaddmaster_data').onSuccess,
									failure: Ext.getCmp('id_formaddmaster_data').onFailure,
									params: {
										cmd: 'save'
									},
									waitMsg: 'Saving Data...'
								});
							}
						}
					})
				}else{
					Ext.getCmp('id_formaddmaster_data').getForm().submit({
						url: Ext.getCmp('id_formaddmaster_data').url,
						scope: this,
						success: Ext.getCmp('id_formaddmaster_data').onSuccess,
						failure: Ext.getCmp('id_formaddmaster_data').onFailure,
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
            
            
            strmaster_data.reload();
            Ext.getCmp('id_formaddmaster_data').getForm().reset();
            winaddmaster_data.hide();
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
    Ext.reg('formaddmaster_data', master_dataform.Form);
    
    var winaddmaster_data = new Ext.Window({
        id: 'id_winaddmaster_data',
        closeAction: 'hide',
        width: 450,
        height: 350,
        layout: 'fit',
        border: false,
        items: {
            id: 'id_formaddmaster_data',
            xtype: 'formaddmaster_data'
        },
        onHide: function(){
            Ext.getCmp('id_formaddmaster_data').getForm().reset();
        }
    });
    
	/* START GRID */    
	
	// data store
	var strmaster_data = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
				'kd_master_data',
				'nama_master_data',
				'aktif'
			],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("master_data/get_rows") ?>',
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
    var searchmaster_data = new Ext.app.SearchField({
        store: strmaster_data,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        id: 'idsearchmaster_data'
    });
    
	// top toolbar
    var tbmaster_data = new Ext.Toolbar({
        items: [{
            text: 'Add',
            icon: BASE_ICONS + 'add.png',
            onClick: function(){				
                Ext.getCmp('btnresetmaster_data').show();
                Ext.getCmp('btnsubmitmaster_data').setText('Submit');
                winaddmaster_data.setTitle('Add Form');
                winaddmaster_data.show();                
            }            
        }, '-', searchmaster_data]
    });
	
	// checkbox grid
    var cbGrid = new Ext.grid.CheckboxSelectionModel();
    
	// row actions
	var actionmaster_data = new Ext.ux.grid.RowActions({
		locked: true,
		header: 'Edit',
		autoWidth: false,
		width: 30,
	    actions:[{iconCls: 'icon-edit-record', qtip: 'Edit'}],
	    widthIntercept: Ext.isSafari ? 4 : 2
	});	
	var actionmaster_datadel = new Ext.ux.grid.RowActions({
		header: 'Delete',
		autoWidth: false,
		width: 40,
	    actions:[{iconCls: 'icon-delete-record', qtip: 'Delete'}],
	    widthIntercept: Ext.isSafari ? 4 : 2
	});	
	
	actionmaster_data.on('action', function(grid, record, action, row, col) {
		var kd_master_data = record.get('kd_master_data');
		switch(action) {
			case 'icon-edit-record':	        	
				editmaster_data(kd_master_data);
	        	break;
	      	case 'icon-delete-record':
				deletemaster_data();
	        	break;	      
	      	
	    }
	});  
  	
	var multisearchmaster_data = new Ext.ux.grid.Search({
				iconCls:'icon-zoom'
				//,readonlyIndexes:['note']
				//,disableIndexes:['pctChange']
				,minChars:3
				,autoFocus:true
				,width:250
			});
			
	// grid
    var master_data = new Ext.grid.EditorGridPanel({
        //id: 'id-master_data-grid',
		id: 'master_data',
        frame: false,
        border: false,
        stripeRows: true,
        sm: cbGrid,
        store: strmaster_data,
		//closable:true,
        loadMask: true,
        //title: 'Kategori 1',
        style: 'margin:0 auto;',
        height: 450,
        //width: 550,
		view: new Ext.ux.grid.LockingGridView(),
        colModel: new Ext.ux.grid.LockingColumnModel([actionmaster_data,
		{
            header: "Kode Kategori",
            dataIndex: 'kd_master_data',
            sortable: true,
			locked: true,
            width: 150
        },{
            header: "Nama Kategori",
            dataIndex: 'nama_master_data',
            sortable: true,
            width: 300
        },{
            header: "Status Aktif",
            dataIndex: 'aktif',
            sortable: true,
            width: 100
        }]),
		plugins: [actionmaster_data,multisearchmaster_data],
        listeners: {
            'rowdblclick': function(){				
                var sm = master_data.getSelectionModel();                
                var sel = sm.getSelections();                
                if (sel.length > 0) {
					editmaster_data(sel[0].get('kd_master_data'));                    
                }                 
            }          
        },
        tbar: tbmaster_data,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strmaster_data,
            displayInfo: true
        })
    });
   
    /**
	var kategoripanel = new Ext.FormPanel({
	 	//id: 'master_data',
		border: false,
        frame: false,
		autoScroll:true,	
        items: [master_data]
	});
	**/
	function editmaster_data(kd_master_data){
		Ext.getCmp('btnresetmaster_data').hide();
		Ext.getCmp('btnsubmitmaster_data').setText('Update');
		winaddmaster_data.setTitle('Edit Form');
		Ext.getCmp('id_formaddmaster_data').getForm().load({
			url: '<?= site_url("master_data/get_row") ?>',
			params: {
				id: kd_master_data,
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
		winaddmaster_data.show();
	}
	
    function deletemaster_data(){		
        var sm = master_data.getSelectionModel();
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
                            data = data + sel[i].get('kd_master_data') + ';';
                        }
                        
                        Ext.Ajax.request({
                            url: '<?= site_url("master_data/delete_rows") ?>',
                            method: 'POST',
                            params: {
                                postdata: data
                            },
							callback:function(opt,success,responseObj){
								var de = Ext.util.JSON.decode(responseObj.responseText);
								if(de.success==true){
									strmaster_data.reload();
	                                strmaster_data.load({
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
