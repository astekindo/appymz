<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">    
    /* START FORM */ 
    Ext.ns('cabangform');
    cabangform.Form = Ext.extend(Ext.form.FormPanel, {
    
        // defaults - can be changed from outside
        border: false,
        frame: true,
        labelWidth: 100,
		waitMsg:'Loading...',
        url: '<?= site_url("wilayah_cabang/update_row") ?>',
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
            cabangform.Form.superclass.constructor.call(this, config);
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
                    name: 'kd_cabang'
                },{
                    type: 'textfield',
                    fieldLabel: 'Cabang <span class="asterix">*</span>',
                    name: 'nama_cabang',
                    allowBlank: false,
                    id: 'id_mwc_nama_cabang',
                    anchor: '90%',
                    maxLength: 40,
					style:'text-transform: uppercase'
                },{
                    xtype: 'radiogroup',
                    cls: 'x-check-group-alt',
                    fieldLabel: 'Status Aktif <span class="asterix">*</span>',
                    name: 'status',
                    anchor: '90%',
                    allowBlank:false,
                    items: [{
                        boxLabel: 'Ya',
                        name: 'status',
                        inputValue: '1',
                        id: 'id_mwc_statY'
                    }, {
                        boxLabel: 'Tidak',
                        name: 'status',
                        inputValue: '0',
                        id: 'id_mwc_statT'
                    }]
                }],
                buttons: [{
                    text: 'Submit',
                    id: 'btnsubmitcabang',
                    formBind: true,
                    scope: this,
                    handler: this.submit
                }, {
                    text: 'Reset',
                    id: 'btnresetcabang',
                    scope: this,
                    handler: this.reset
                }, {
                    text: 'Close',
                    id: 'btnClosecabang',
                    scope: this,
                    handler: function(){
                        winaddcabang.hide();
                    }
                }]
            }; // eo config object
            // apply config
            Ext.apply(this, Ext.apply(this.initialConfig, config));
            
            // call parent
            cabangform.Form.superclass.initComponent.apply(this, arguments);
            
        }, // eo function initComponent
        onRender: function(){
        
            // call parent
            cabangform.Form.superclass.onRender.apply(this, arguments);
            
            // set wait message target
            this.getForm().waitMsgTarget = this.getEl();
            
            // loads form after initial layout
            // this.on('afterlayout', this.onLoadClick, this, {single:true});
        
        }, // eo function onRender
        reset: function(){
            this.getForm().reset();
        },
        submit: function(){
			var text = Ext.getCmp('btnsubmitcabang').getText();
			if (text == 'Update'){
				Ext.Msg.show({
					title: 'Confirm',
					msg: 'Are you sure update selected row ?',
					buttons: Ext.Msg.YESNO,
					fn: function(btn){
						if (btn == 'yes') {
							Ext.getCmp('id_mwc_formaddcabang').getForm().submit({
								url: Ext.getCmp('id_mwc_formaddcabang').url,
								scope: this,
								success: Ext.getCmp('id_mwc_formaddcabang').onSuccess,
								failure: Ext.getCmp('id_mwc_formaddcabang').onFailure,
								params: {
									cmd: 'save'
								},
								waitMsg: 'Saving Data...'
							});
						}
					}
				})
			}else{
				Ext.getCmp('id_mwc_formaddcabang').getForm().submit({
					url: Ext.getCmp('id_mwc_formaddcabang').url,
					scope: this,
					success: Ext.getCmp('id_mwc_formaddcabang').onSuccess,
					failure: Ext.getCmp('id_mwc_formaddcabang').onFailure,
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
            
            
            strcabang.reload();
            Ext.getCmp('id_mwc_formaddcabang').getForm().reset();
            winaddcabang.hide();
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
    Ext.reg('formaddcabang', cabangform.Form);
    
    var winaddcabang = new Ext.Window({
        id: 'id_winaddcabang',
        closeAction: 'hide',
        width: 450,
        height: 350,
        layout: 'fit',
        border: false,
        items: {
            id: 'id_mwc_formaddcabang',
            xtype: 'formaddcabang'
        },
        onHide: function(){
            Ext.getCmp('id_mwc_formaddcabang').getForm().reset();
        }
    });
    
	/* START GRID */    
	
	// data store
	var strcabang = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
				'kd_cabang',
                'nama_cabang',
                'status'
			],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("wilayah_cabang/get_rows") ?>',
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
    strcabang.load();

	// search field
    var searchcabang = new Ext.app.SearchField({
        store: strcabang,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        id: 'idsearchcabang'
    });
    
	// top toolbar
    var tbcabang = new Ext.Toolbar({
        items: [{
            text: 'Add',
            icon: BASE_ICONS + 'add.png',
            onClick: function(){				
                Ext.getCmp('btnresetcabang').show();
                Ext.getCmp('btnsubmitcabang').setText('Submit');
                winaddcabang.setTitle('Add Form');
                winaddcabang.show();                
            }            
        }, '-', searchcabang]
    });
	
	// checkbox grid
    var cbGrid = new Ext.grid.CheckboxSelectionModel();
    
	// row actions
	var actioncabang = new Ext.ux.grid.RowActions({
		header: 'Edit',
		autoWidth: false,
		width: 30,
	    actions:[{iconCls: 'icon-edit-record', qtip: 'Edit'}],
	    widthIntercept: Ext.isSafari ? 4 : 2
	});	

//	var actioncabangdel = new Ext.ux.grid.RowActions({
//		header: 'Delete',
//		autoWidth: false,
//		width: 40,
//	    actions:[{iconCls: 'icon-delete-record', qtip: 'Delete'}],
//	    widthIntercept: Ext.isSafari ? 4 : 2
//	});
	
	actioncabang.on('action', function(grid, record, action, row, col) {
		var kd_cabang = record.get('kd_cabang');
		switch(action) {
			case 'icon-edit-record':	        	
				editcabang(kd_cabang);
	        	break;
	      	case 'icon-delete-record':
				deletecabang();
	        	break;	      
	      	
	    }
	});  
  	
	var multisearchcabang = new Ext.ux.grid.Search({
				iconCls:'icon-zoom'
				//,readonlyIndexes:['note']
				//,disableIndexes:['pctChange']
				,minChars:3
				,autoFocus:true
				,width:250
			});
			
	// grid
    var cabang = new Ext.grid.EditorGridPanel({
        //id: 'id-cabang-gridpanel',
        id: 'cabang',
	    frame: true,
        border: true,
        stripeRows: true,
        sm: cbGrid,
        store: strcabang,
		//closable:true,
        loadMask: true,
        style: 'margin:0 auto;',
        height: 450,
        columns: [actioncabang,
            {
                header: "Kode Cabang",
                dataIndex: 'kd_cabang',
                sortable: true,
                width: 150
            },{
                header: "Nama Cabang",
                dataIndex: 'nama_cabang',
                sortable: true,
                width: 300
            },{
                header: "Status",
                dataIndex: 'status',
                sortable: true,
                width: 50
            }
        ],
		plugins: [actioncabang,multisearchcabang],
        listeners: {
            'rowdblclick': function(){				
                var sm = cabang.getSelectionModel();                
                var sel = sm.getSelections();                
                if (sel.length > 0) {
					editcabang(sel[0].get('kd_cabang'));                    
                }                 
            }
        },
        tbar: tbcabang,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strcabang,
            displayInfo: true
        })
    });
	/**
	var cabangpanel = new Ext.FormPanel({
	 	id: 'cabang',
		border: false,
        frame: false,
		autoScroll:true,	
        items: [cabang]
	});
   **/
	function editcabang(kd_cabang){
		Ext.getCmp('btnresetcabang').hide();
        Ext.getCmp('btnsubmitcabang').setText('Update');
        winaddcabang.setTitle('Edit Form');
        Ext.getCmp('id_mwc_formaddcabang').getForm().load({
            url: '<?= site_url("wilayah_cabang/get_row") ?>',
            params: {
                id: kd_cabang,
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
        winaddcabang.show();
	}
	
    function deletecabang(){		
        var sm = cabang.getSelectionModel();
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
                            data = data + sel[i].get('kd_cabang') + ';';
                        }
                        
                        Ext.Ajax.request({
                            url: '<?= site_url("wilayah_cabang/delete_rows") ?>',
                            method: 'POST',
                            params: {
                                postdata: data
                            },
							callback:function(opt,success,responseObj){
								var de = Ext.util.JSON.decode(responseObj.responseText);
								if(de.success==true){
									strcabang.reload();
	                                strcabang.load({
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
