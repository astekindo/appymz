<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">    
	
	// input ukuran
    var strcbNamaUkuran = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_ukuran', 'nama_ukuran'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("master_ukuran/get_rows") ?>',
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

    var cbNamaUkuran = new Ext.form.ComboBox({
        fieldLabel: 'Nama Ukuran <span class="asterix">*</span>',
        id: 'id_nama_ukuran',
        triggerAction: 'query',
        store: strcbNamaUkuran,
        valueField: 'nama_ukuran',
        displayField: 'nama_ukuran',
        // typeAhead: true,
        allowBlank: false,
		width: 170,
        anchor: '90%',
        hiddenName: 'nama_ukuran',
		style:'text-transform: uppercase',
		minChars: 1,
		hideTrigger:true,
    });
  

    /* START FORM */ 
    Ext.ns('master_ukuranform');
    master_ukuranform.Form = Ext.extend(Ext.form.FormPanel, {
    
        // defaults - can be changed from outside
        border: false,
        frame: true,
        labelWidth: 100,
		waitMsg:'Loading...',
        url: '<?= site_url("master_ukuran/update_row") ?>',
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
            master_ukuranform.Form.superclass.constructor.call(this, config);
        },
        initComponent: function(){
        
            // hard coded - cannot be changed from outsid
            var config = {
                defaultType: 'textfield',
                defaults: { labelSeparator: ''},
                monitorValid: true,
                autoScroll: false // ,buttonAlign:'right'
                ,
                items: [ {
                    type: 'textfield',
                    fieldLabel: 'Kode Ukuran',
                    name: 'kd_ukuran',
                    id: 'id_kd_ukuran',
                    anchor: '90%',
					fieldClass: 'readonly-input',
                },cbNamaUkuran
				// {
                    // type: 'textfield',
                    // fieldLabel: 'Ukuran <span class="asterix">*</span>',
                    // name: 'nama_ukuran',
                    // allowBlank: false,
                    // id: 'id_nama_ukuran',
                    // anchor: '90%',
                    // maxLength: 40,
					// style:'text-transform: uppercase',  
					       
                // }
				, new Ext.form.Checkbox({
						xtype: 'checkbox',
						fieldLabel: 'Status Aktif <span class="asterix">*</span>',
                    	boxLabel:'Ya',
						name:'aktif',
						id:'mu_aktif',
						inputValue: '1',
						autoLoad : true
				})],
                buttons: [{
                    text: 'Submit',
                    id: 'btnsubmitmaster_ukuran',
                    formBind: true,
                    scope: this,
                    handler: this.submit
                }, {
                    text: 'Reset',
                    id: 'btnresetmaster_ukuran',
                    scope: this,
                    handler: this.reset
                }, {
                    text: 'Close',
                    id: 'btnClosemaster_ukuran',
                    scope: this,
                    handler: function(){
                        winaddmaster_ukuran.hide();
                    }
                }]
            }; // eo config object
            // apply config
            Ext.apply(this, Ext.apply(this.initialConfig, config));
            
            // call parent
            master_ukuranform.Form.superclass.initComponent.apply(this, arguments);
            
        } // eo function initComponent	
        ,
        onRender: function(){
        
            // call parent
            master_ukuranform.Form.superclass.onRender.apply(this, arguments);
            
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
				var text = Ext.getCmp('btnsubmitmaster_ukuran').getText();
				if (text == 'Update'){
					Ext.Msg.show({
						title: 'Confirm',
						msg: 'Are you sure update selected row ?',
						buttons: Ext.Msg.YESNO,
						fn: function(btn){
							if (btn == 'yes') {
								Ext.getCmp('id_formaddmaster_ukuran').getForm().submit({
									url: Ext.getCmp('id_formaddmaster_ukuran').url,
									scope: this,
									success: Ext.getCmp('id_formaddmaster_ukuran').onSuccess,
									failure: Ext.getCmp('id_formaddmaster_ukuran').onFailure,
									params: {
										cmd: 'save'
									},
									waitMsg: 'Saving Data...'
								});
							}
						}
					})
				}else{
					Ext.getCmp('id_formaddmaster_ukuran').getForm().submit({
						url: Ext.getCmp('id_formaddmaster_ukuran').url,
						scope: this,
						success: Ext.getCmp('id_formaddmaster_ukuran').onSuccess,
						failure: Ext.getCmp('id_formaddmaster_ukuran').onFailure,
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
            
            
            strmaster_ukuran.reload();
            Ext.getCmp('id_formaddmaster_ukuran').getForm().reset();
            winaddmaster_ukuran.hide();
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
    Ext.reg('formaddmaster_ukuran', master_ukuranform.Form);
    
    var winaddmaster_ukuran = new Ext.Window({
        id: 'id_winaddmaster_ukuran',
        closeAction: 'hide',
        width: 450,
        height: 350,
        layout: 'fit',
        border: false,
        items: {
            id: 'id_formaddmaster_ukuran',
            xtype: 'formaddmaster_ukuran'
        },
        onHide: function(){
            Ext.getCmp('id_formaddmaster_ukuran').getForm().reset();
        }
    });
    
	/* START GRID */    
	
	// data store
	var strmaster_ukuran = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
				'kd_ukuran',
				'nama_ukuran',
				'aktif'
			],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("master_ukuran/get_rows") ?>',
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
    var searchmaster_ukuran = new Ext.app.SearchField({
        store: strmaster_ukuran,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        id: 'idsearchmaster_ukuran'
    });
    
	// top toolbar
    var tbmaster_ukuran = new Ext.Toolbar({
        items: [{
            text: 'Add',
            icon: BASE_ICONS + 'add.png',
            onClick: function(){				
                Ext.getCmp('btnresetmaster_ukuran').show();
                Ext.getCmp('btnsubmitmaster_ukuran').setText('Submit');
                winaddmaster_ukuran.setTitle('Add Form');
                winaddmaster_ukuran.show();                
            }            
        }, '-', searchmaster_ukuran]
    });
	
	// checkbox grid
    var cbGrid = new Ext.grid.CheckboxSelectionModel();
    
	// row actions
	var actionmaster_ukuran = new Ext.ux.grid.RowActions({
		locked: true,
		header: 'Edit',
		autoWidth: false,
		width: 30,
	    actions:[{iconCls: 'icon-edit-record', qtip: 'Edit'}],
	    widthIntercept: Ext.isSafari ? 4 : 2
	});	
	var actionmaster_ukurandel = new Ext.ux.grid.RowActions({
		header: 'Delete',
		autoWidth: false,
		width: 40,
	    actions:[{iconCls: 'icon-delete-record', qtip: 'Delete'}],
	    widthIntercept: Ext.isSafari ? 4 : 2
	});	
	
	actionmaster_ukuran.on('action', function(grid, record, action, row, col) {
		var kd_ukuran = record.get('kd_ukuran');
		switch(action) {
			case 'icon-edit-record':	        	
				editmaster_ukuran(kd_ukuran);
	        	break;
	      	case 'icon-delete-record':
				deletemaster_ukuran();
	        	break;	      
	      	
	    }
	});  
  	
	var multisearchmaster_ukuran = new Ext.ux.grid.Search({
				iconCls:'icon-zoom'
				//,readonlyIndexes:['note']
				//,disableIndexes:['pctChange']
				,minChars:3
				,autoFocus:true
				,width:250
			});
			
	// grid
    var master_ukuran = new Ext.grid.EditorGridPanel({
        //id: 'id-master_ukuran-grid',
		id: 'master_ukuran',
        frame: false,
        border: false,
        stripeRows: true,
        sm: cbGrid,
        store: strmaster_ukuran,
		//closable:true,
        loadMask: true,
        //title: 'Ukuran 1',
        style: 'margin:0 auto;',
        height: 450,
        //width: 550,
		view: new Ext.ux.grid.LockingGridView(),
        colModel: new Ext.ux.grid.LockingColumnModel([actionmaster_ukuran,
		{
            header: "Kode Ukuran",
            dataIndex: 'kd_ukuran',
            sortable: true,
			locked: true,
            width: 150
        },{
            header: "Nama Ukuran",
            dataIndex: 'nama_ukuran',
            sortable: true,
            width: 300
        },{
            header: "Status Aktif",
            dataIndex: 'aktif',
            sortable: true,
            width: 100
        }]),
		plugins: [actionmaster_ukuran,multisearchmaster_ukuran],
        listeners: {
            'rowdblclick': function(){				
                var sm = master_ukuran.getSelectionModel();                
                var sel = sm.getSelections();                
                if (sel.length > 0) {
					editmaster_ukuran(sel[0].get('kd_ukuran'));                    
                }                 
            }          
        },
        tbar: tbmaster_ukuran,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strmaster_ukuran,
            displayInfo: true
        })
    });
   
    /**
	var ukuranpanel = new Ext.FormPanel({
	 	//id: 'master_ukuran',
		border: false,
        frame: false,
		autoScroll:true,	
        items: [master_ukuran]
	});
	**/
	function editmaster_ukuran(kd_ukuran){
		Ext.getCmp('btnresetmaster_ukuran').hide();
		Ext.getCmp('btnsubmitmaster_ukuran').setText('Update');
		winaddmaster_ukuran.setTitle('Edit Form');
		Ext.getCmp('id_formaddmaster_ukuran').getForm().load({
			url: '<?= site_url("master_ukuran/get_row") ?>',
			params: {
				id: kd_ukuran,
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
		winaddmaster_ukuran.show();
	}
	
    function deletemaster_ukuran(){		
        var sm = master_ukuran.getSelectionModel();
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
                            data = data + sel[i].get('kd_ukuran') + ';';
                        }
                        
                        Ext.Ajax.request({
                            url: '<?= site_url("master_ukuran/delete_rows") ?>',
                            method: 'POST',
                            params: {
                                postdata: data
                            },
							callback:function(opt,success,responseObj){
								var de = Ext.util.JSON.decode(responseObj.responseText);
								if(de.success==true){
									strmaster_ukuran.reload();
	                                strmaster_ukuran.load({
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
