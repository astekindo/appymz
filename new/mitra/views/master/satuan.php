<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">    
	// input  Satuan
    var strcbNamaSatuan = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_satuan', 'nm_satuan'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("satuan/get_nm_satuan") ?>',
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

    var cbNamaSatuan = new Ext.form.ComboBox({
        fieldLabel: 'Nama Satuan <span class="asterix">*</span>',
        id: 'id_cbNamaSatuan',
        triggerAction: 'query',
        store: strcbNamaSatuan,
        valueField: 'nm_satuan',
        displayField: 'nm_satuan',
        // typeAhead: true,
        allowBlank: false,
		width: 170,
        anchor: '90%',
        hiddenName: 'nm_satuan',
		style:'text-transform: uppercase',
		minChars: 1,
		hideTrigger:true,
    });
	
    /* START FORM */ 
    Ext.ns('satuanform');
    satuanform.Form = Ext.extend(Ext.form.FormPanel, {
    
        // defaults - can be changed from outside
        border: false,
        frame: true,
        labelWidth: 100,
        url: '<?= site_url("satuan/update_row") ?>',
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
            satuanform.Form.superclass.constructor.call(this, config);
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
                    name: 'kd_satuan'
                }, 
				// {
                    // type: 'textfield',
                    // fieldLabel: 'Nama Satuan <span class="asterix">*</span>',
                    // name: 'nm_satuan',
                    // allowBlank: false,
                    // id: 'id_nm_satuan',
                    // maxLength: 40,
                    // anchor: '90%'                
                // },
				cbNamaSatuan, {
                    type: 'textfield',
                    fieldLabel: 'Keterangan <span class="asterix">*</span>',
                    name: 'keterangan',
                    allowBlank: false,
                    id: 'id_keterangan',
                    maxLength: 100,
					style:'text-transform: uppercase',
                    anchor: '90%'                
                }],
                buttons: [{
                    text: 'Submit',
                    id: 'btnsubmitsatuan',
                    formBind: true,
                    scope: this,
                    handler: this.submit
                }, {
                    text: 'Reset',
                    id: 'btnresetsatuan',
                    scope: this,
                    handler: this.reset
                }, {
                    text: 'Close',
                    id: 'btnClose',
                    scope: this,
                    handler: function(){
                        winaddsatuan.hide();
                    }
                }]
            }; // eo config object
            // apply config
            Ext.apply(this, Ext.apply(this.initialConfig, config));
            
            // call parent
            satuanform.Form.superclass.initComponent.apply(this, arguments);
            
        } // eo function initComponent	
        ,
        onRender: function(){
        
            // call parent
            satuanform.Form.superclass.onRender.apply(this, arguments);
            
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
			var text = Ext.getCmp('btnsubmitsatuan').getText();
			if (text == 'Update'){
				Ext.Msg.show({
					title: 'Confirm',
					msg: 'Are you sure update selected row ?',
					buttons: Ext.Msg.YESNO,
					fn: function(btn){
						if (btn == 'yes') {
							Ext.getCmp('id_formaddsatuan').getForm().submit({
								url: Ext.getCmp('id_formaddsatuan').url,
								scope: this,
								success: Ext.getCmp('id_formaddsatuan').onSuccess,
								failure: Ext.getCmp('id_formaddsatuan').onFailure,
								params: {
									cmd: 'save'
								},
								waitMsg: 'Saving Data...'
							});
						}
					}
				})
			}else{
				Ext.getCmp('id_formaddsatuan').getForm().submit({
					url: Ext.getCmp('id_formaddsatuan').url,
					scope: this,
					success: Ext.getCmp('id_formaddsatuan').onSuccess,
					failure: Ext.getCmp('id_formaddsatuan').onFailure,
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
            
            
            strsatuan.reload();
            Ext.getCmp('id_formaddsatuan').getForm().reset();
            winaddsatuan.hide();
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
    Ext.reg('formaddsatuan', satuanform.Form);
    
    var winaddsatuan = new Ext.Window({
        id: 'id_winaddsatuan',
        closeAction: 'hide',
        width: 450,
        height: 350,
        layout: 'fit',
        border: false,
        items: {
            id: 'id_formaddsatuan',
            xtype: 'formaddsatuan'
        },
        onHide: function(){
            Ext.getCmp('id_formaddsatuan').getForm().reset();
        }
    });
    
	/* START GRID */    
	var strsatuan = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
				'kd_satuan', 
				'nm_satuan',
				'keterangan'
			],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("satuan/get_rows") ?>',
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
	
    var searchsatuan = new Ext.app.SearchField({
        store: strsatuan,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        id: 'idsearchsatuan'
    });
    
    var tbsatuan = new Ext.Toolbar({
        items: [{
            text: 'Add',
            icon: BASE_ICONS + 'add.png',
            onClick: function(){				
                Ext.getCmp('btnresetsatuan').show();
                Ext.getCmp('btnsubmitsatuan').setText('Submit');
                winaddsatuan.setTitle('Add Form');
                winaddsatuan.show();                
            }            
        }, '-', searchsatuan]
    });

    var cbGrid = new Ext.grid.CheckboxSelectionModel();
    	
	// row actions
	var actionsatuan = new Ext.ux.grid.RowActions({
		header :'Edit',
		autoWidth: false,
		width: 30,
	    actions:[{iconCls: 'icon-edit-record', qtip: 'Edit'}],
	    widthIntercept: Ext.isSafari ? 4 : 2
	});	
	var actionsatuandel = new Ext.ux.grid.RowActions({
		header: 'Delete',
		autoWidth: false,
		width: 40,
	    actions:[{iconCls: 'icon-delete-record', qtip: 'Delete'}],
	    widthIntercept: Ext.isSafari ? 4 : 2
	});	
	actionsatuan.on('action', function(grid, record, action, row, col) {
		var kd_satuan = record.get('kd_satuan');
		switch(action) {
			case 'icon-edit-record':	        	
				editsatuan(kd_satuan);
	        	break;
	      	case 'icon-delete-record':
				Ext.Msg.show({
	                title: 'Confirm',
	                msg: 'Are you sure delete selected row ?',
	                buttons: Ext.Msg.YESNO,
	                fn: function(btn){
	                    if (btn == 'yes') {
	                        Ext.Ajax.request({
	                            url: '<?= site_url("satuan/delete_row") ?>',
	                            method: 'POST',
	                            params: {
	                                kd_satuan: kd_satuan
	                            },
								callback:function(opt,success,responseObj){
									var de = Ext.util.JSON.decode(responseObj.responseText);
									if(de.success==true){
										strsatuan.reload();
		                                strsatuan.load({
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
	
	//grid
    var satuan = new Ext.grid.EditorGridPanel({
        //id: 'id-satuan-gridpanel',
        id: 'satuan',
		frame: true,
        border: true,
        stripeRows: true,
        sm: cbGrid,
        store: strsatuan,
        loadMask: true,
        style: 'margin:0 auto;',
        height: 450,
        columns: [actionsatuan,{
            header: "Kode",
            dataIndex: 'kd_satuan',
            sortable: true,
            width: 50
        },{
            header: "Nama Satuan",
            dataIndex: 'nm_satuan',
            sortable: true,
            width: 100
        },{
            header: "Keterangan",
            dataIndex: 'keterangan',
            sortable: true,
            width: 100
        }],
		plugins: [actionsatuan],
        listeners: {
            'rowdblclick': function(){
				
                var sm = satuan.getSelectionModel();
                
                var sel = sm.getSelections();
                
                if (sel.length > 0) {
                    Ext.getCmp('btnresetsatuan').hide();
                    Ext.getCmp('btnsubmitsatuan').setText('Update');
                    winaddsatuan.setTitle('Edit Form');
                    Ext.getCmp('id_formaddsatuan').getForm().load({
                        url: '<?= site_url("satuan/get_row") ?>',
                        params: {
                            id: sel[0].get('kd_satuan'),
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
                    winaddsatuan.show();
                }
                 
            }
          
        },
        tbar: tbsatuan,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strsatuan,
            displayInfo: true
        })
    });
	/**
	var satuanpanel = new Ext.FormPanel({
	 	id: 'satuan',
		border: false,
        frame: false,
		autoScroll:true,	
        items: [satuan]
	});
    **/
	function editsatuan(kd_satuan){
		Ext.getCmp('btnresetsatuan').hide();
        Ext.getCmp('btnsubmitsatuan').setText('Update');
        winaddsatuan.setTitle('Edit Form');
        Ext.getCmp('id_formaddsatuan').getForm().load({
            url: '<?= site_url("satuan/get_row") ?>',
            params: {
                id: kd_satuan,
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
        winaddsatuan.show();
	}
    function deletesatuan(){		
        var sm = satuan.getSelectionModel();
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
                            data = data + sel[i].get('kd_satuan') + ';';
                        }
                        
                        Ext.Ajax.request({
                            url: '<?= site_url("satuan/delete_rows") ?>',
                            method: 'POST',
                            params: {
                                postdata: data
                            },
							callback:function(opt,success,responseObj){
								var de = Ext.util.JSON.decode(responseObj.responseText);
								if(de.success==true){
									strsatuan.reload();
	                                strsatuan.load({
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
