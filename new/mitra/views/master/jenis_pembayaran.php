<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">    
    /* START FORM */ 
    Ext.ns('jenispembayaranform');
    jenispembayaranform.Form = Ext.extend(Ext.form.FormPanel, {
    
        // defaults - can be changed from outside
        border: false,
        frame: true,
        labelWidth: 110,
        url: '<?= site_url("jenis_pembayaran/update_row") ?>',
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
            jenispembayaranform.Form.superclass.constructor.call(this, config);
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
                    name: 'kd_jenis_bayar'
                }, {
                    type: 'textfield',
                    fieldLabel: 'Nama Pembayaran <span class="asterix">*</span>',
                    name: 'nm_pembayaran',
                    allowBlank: false,
                    id: 'id_nm_pembayaran',
                    maxLength: 40,
                    anchor: '90%'                
                }, {
                    xtype: 'numberfield',
                    fieldLabel: 'Charge <span class="asterix">*</span>',
                    name: 'charge',
                    allowBlank: false,
                    id: 'id_charge',
                    maxLength: 11,
                    anchor: '90%'                
                }, {
                    type: 'textfield',
                    fieldLabel: 'Jenis <span class="asterix">*</span>',
                    name: 'jenis',
                    allowBlank: false,
                    id: 'id__jenis',
                    maxLength: 40,
                    anchor: '90%'                
                },{
					xtype: 'radiogroup',                 
					fieldLabel: 'Status Aktif <span class="asterix">*</span>',
					columnWidth: [.5, .5], 				
					name: 'status_aktif',                     
					allowBlank:false,  
					anchor: '90%',
					items: [{                    
						boxLabel: 'Ya',                     
						name: 'status_aktif',                     
						inputValue: 'Ya',                     
						id: 'id_aktifYa', 					
					}, {                     
						boxLabel: 'Tidak',                     
						name: 'status_aktif',                     
						inputValue: 'Tidak',                    
						id: 'id_aktifTidak'                 
					}]             
				}],
                buttons: [{
                    text: 'Submit',
                    id: 'btnsubmitjenispembayaran',
                    formBind: true,
                    scope: this,
                    handler: this.submit
                }, {
                    text: 'Reset',
                    id: 'btnresetjenispembayaran',
                    scope: this,
                    handler: this.reset
                }, {
                    text: 'Close',
                    id: 'btnClose',
                    scope: this,
                    handler: function(){
                        winaddjenispembayaran.hide();
                    }
                }]
            }; // eo config object
            // apply config
            Ext.apply(this, Ext.apply(this.initialConfig, config));
            
            // call parent
            jenispembayaranform.Form.superclass.initComponent.apply(this, arguments);
            
        } // eo function initComponent	
        ,
        onRender: function(){
        
            // call parent
            jenispembayaranform.Form.superclass.onRender.apply(this, arguments);
            
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
			var text = Ext.getCmp('btnsubmitjenispembayaran').getText();
			if (text == 'Update'){
				Ext.Msg.show({
					title: 'Confirm',
					msg: 'Are you sure update selected row ?',
					buttons: Ext.Msg.YESNO,
					fn: function(btn){
						if (btn == 'yes') {
							Ext.getCmp('id_formaddjenispembayaran').getForm().submit({
								url: Ext.getCmp('id_formaddjenispembayaran').url,
								scope: this,
								success: Ext.getCmp('id_formaddjenispembayaran').onSuccess,
								failure: Ext.getCmp('id_formaddjenispembayaran').onFailure,
								params: {
									cmd: 'save'
								},
								waitMsg: 'Saving Data...'
							});
						}
					}
				})
			}else{
				Ext.getCmp('id_formaddjenispembayaran').getForm().submit({
					url: Ext.getCmp('id_formaddjenispembayaran').url,
					scope: this,
					success: Ext.getCmp('id_formaddjenispembayaran').onSuccess,
					failure: Ext.getCmp('id_formaddjenispembayaran').onFailure,
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
            
            
            strjenispembayaran.reload();
            Ext.getCmp('id_formaddjenispembayaran').getForm().reset();
            winaddjenispembayaran.hide();
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
    Ext.reg('formaddjenispembayaran', jenispembayaranform.Form);
    
    var winaddjenispembayaran = new Ext.Window({
        id: 'id_winaddjenispembayaran',
        closeAction: 'hide',
        width: 450,
        height: 350,
        layout: 'fit',
        border: false,
        items: {
            id: 'id_formaddjenispembayaran',
            xtype: 'formaddjenispembayaran'
        },
        onHide: function(){
            Ext.getCmp('id_formaddjenispembayaran').getForm().reset();
        }
    });
    
	/* START GRID */    
	var strjenispembayaran = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
				'kd_jenis_bayar', 
				'nm_pembayaran',
				'charge',
				'status_aktif',
				'jenis'
			],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("jenis_pembayaran/get_rows") ?>',
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
	
    var searchjenispembayaran = new Ext.app.SearchField({
        store: strjenispembayaran,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        id: 'idsearchjenispembayaran'
    });
    
    var tbjenispembayaran = new Ext.Toolbar({
        items: [{
            text: 'Add',
            icon: BASE_ICONS + 'add.png',
            onClick: function(){				
                Ext.getCmp('btnresetjenispembayaran').show();
                Ext.getCmp('btnsubmitjenispembayaran').setText('Submit');
                winaddjenispembayaran.setTitle('Add Form');
                winaddjenispembayaran.show();                
            }            
        }, '-', searchjenispembayaran]
    });

    var cbGrid = new Ext.grid.CheckboxSelectionModel();
    
	// row actions
	var actionjenispembayaran = new Ext.ux.grid.RowActions({
        header:'Edit',
		autoWidth: false,
		width: 30,
	    actions:[{iconCls: 'icon-edit-record', qtip: 'Edit'}],
	    widthIntercept: Ext.isSafari ? 4 : 2
	});	
	var actionjenispembayarandel = new Ext.ux.grid.RowActions({
        header:'Delete',
		autoWidth: false,
		width: 40,
	    actions:[{iconCls: 'icon-delete-record', qtip: 'Delete'}],
	    widthIntercept: Ext.isSafari ? 4 : 2
	});	
	actionjenispembayaran.on('action', function(grid, record, action, row, col) {
		var kd_jenis_bayar = record.get('kd_jenis_bayar');
		switch(action) {
			case 'icon-edit-record':	        	
				editjenispembayaran(kd_jenis_bayar);
	        	break;
	      	case 'icon-delete-record':
				Ext.Msg.show({
	                title: 'Confirm',
	                msg: 'Are you sure delete selected row ?',
	                buttons: Ext.Msg.YESNO,
	                fn: function(btn){
	                    if (btn == 'yes') {
	                        Ext.Ajax.request({
	                            url: '<?= site_url("jenis_pembayaran/delete_row") ?>',
	                            method: 'POST',
	                            params: {
	                                kd_jenis_bayar: kd_jenis_bayar
	                            },
								callback:function(opt,success,responseObj){
									var de = Ext.util.JSON.decode(responseObj.responseText);
									if(de.success==true){
										strjenispembayaran.reload();
		                                strjenispembayaran.load({
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
    var jenispembayaran = new Ext.grid.EditorGridPanel({
        //id: 'id-jenispembayaran-grid',
        id: 'jenispembayaran',
		frame: true,
        border: true,
        stripeRows: true,
        sm: cbGrid,
        store: strjenispembayaran,
        loadMask: true,
        style: 'margin:0 auto;',
        height: 450,
        columns: [actionjenispembayaran, {
            header: "Nama Pembayaran",
            dataIndex: 'nm_pembayaran',
            sortable: true,
            width: 150
        },{
            header: "Charge",
            dataIndex: 'charge',
            sortable: true,
            width: 50
        },{
            header: "Jenis",
            dataIndex: 'jenis',
            sortable: true,
            width: 75
        },{
            header: "Status Aktif",
            dataIndex: 'status_aktif',
            sortable: true,
            width: 75
        }],
		plugins: [actionjenispembayaran],
        listeners: {
            'rowdblclick': function(){				
                var sm = jenispembayaran.getSelectionModel();                
                var sel = sm.getSelections();                
                if (sel.length > 0) {
					editjenispembayaran(sel[0].get('kd_jenis_bayar'));                    
                }                 
            }          
        },
        tbar: tbjenispembayaran,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strjenispembayaran,
            displayInfo: true
        })
    });
	/**
	var jenispembayaranpanel = new Ext.FormPanel({
	 	id: 'jenispembayaran',
		border: false,
        frame: false,
		autoScroll:true,	
        items: [jenispembayaran]
	});
    **/
	function editjenispembayaran(kd_jenis_bayar){
		Ext.getCmp('btnresetjenispembayaran').hide();
        Ext.getCmp('btnsubmitjenispembayaran').setText('Update');
        winaddjenispembayaran.setTitle('Edit Form');
        Ext.getCmp('id_formaddjenispembayaran').getForm().load({
            url: '<?= site_url("jenis_pembayaran/get_row") ?>',
            params: {
                id: kd_jenis_bayar,
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
        winaddjenispembayaran.show();
	}
    function deletejenispembayaran(){		
        var sm = jenispembayaran.getSelectionModel();
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
                            data = data + sel[i].get('kd_jenis_bayar') + ';';
                        }
                        
                        Ext.Ajax.request({
                            url: '<?= site_url("jenis_pembayaran/delete_rows") ?>',
                            method: 'POST',
                            params: {
                                postdata: data
                            },
							callback:function(opt,success,responseObj){
								var de = Ext.util.JSON.decode(responseObj.responseText);
								if(de.success==true){
									strjenispembayaran.reload();
	                                strjenispembayaran.load({
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
