<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">    
    /* START FORM */ 
    // combobox propinsi
    var strcbpropinsi = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_propinsi', 'nama_propinsi'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("wilayah_kota/get_propinsi") ?>',
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
 
    var cbpropinsi_kota = new Ext.form.ComboBox({
        fieldLabel: 'Propinsi <span class="asterix">*</span>',
        id: 'id_cbpropinsi_kota',
        store: strcbpropinsi,
        valueField: 'kd_propinsi',
        displayField: 'nama_propinsi',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_propinsi',
        emptyText: 'Pilih propinsi',
		hideMode: 'Visibility'
    });
    Ext.ns('kotaform');

    kotaform.Form = Ext.extend(Ext.form.FormPanel, {
    
        // defaults - can be changed from outside
        border: false,	
        frame: true,
        labelWidth: 100,
        url: '<?= site_url("wilayah_kota/update_row") ?>',
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
            kotaform.Form.superclass.constructor.call(this, config);
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
                    name: 'kd_kota'
                },{
                    xtype: 'hidden',
                    name: 'kd_propinsi'
                },cbpropinsi_kota, {
                    type: 'textfield',
                    fieldLabel: 'Kota <span class="asterix">*</span>',
                    name: 'nama_kota',
                    allowBlank: false,
                    id: 'id_nama_kota',
                    maxLength: 40,
		    style:'text-transform: uppercase',  
                    anchor: '90%'                
                }],
                buttons: [{
                    text: 'Submit',
                    id: 'btnsubmitkota',
                    formBind: true,
                    scope: this,
                    handler: this.submit
                }, {
                    text: 'Reset',
                    id: 'btnresetkota',
                    scope: this,
                    handler: this.reset
                }, {
                    text: 'Close',
                    id: 'btnClosekota',
                    scope: this,
                    handler: function(){
                        winaddkota.hide();
                    }
                }]
            }; // eo config object
            // apply config
            Ext.apply(this, Ext.apply(this.initialConfig, config));
            
            // call parent
            kotaform.Form.superclass.initComponent.apply(this, arguments);
            
        } // eo function initComponent  
        ,
        onRender: function(){
        
            // call parent
            kotaform.Form.superclass.onRender.apply(this, arguments);
            
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
			var text = Ext.getCmp('btnsubmitkota').getText();
			if (text == 'Update'){
				Ext.Msg.show({
					title: 'Confirm',
					msg: 'Are you sure update selected row ?',
					buttons: Ext.Msg.YESNO,
					fn: function(btn){
						if (btn == 'yes') {
							Ext.getCmp('id_formaddkota').getForm().submit({
								url: Ext.getCmp('id_formaddkota').url,
								scope: this,
								success: Ext.getCmp('id_formaddkota').onSuccess,
								failure: Ext.getCmp('id_formaddkota').onFailure,
								params: {
									cmd: 'save'
								},
								waitMsg: 'Saving Data...'
							});
						}
					}
				})
			}else{
				Ext.getCmp('id_formaddkota').getForm().submit({
					url: Ext.getCmp('id_formaddkota').url,
					scope: this,
					success: Ext.getCmp('id_formaddkota').onSuccess,
					failure: Ext.getCmp('id_formaddkota').onFailure,
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
            
            
            strkota.reload();
            Ext.getCmp('id_formaddkota').getForm().reset();
            winaddkota.hide();
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
    Ext.reg('formaddkota', kotaform.Form);
    
    var winaddkota = new Ext.Window({
        id: 'id_winaddkota',
        closeAction: 'hide',
        width: 450,
        height: 350,
        layout: 'fit',
        border: false,
        items: {
            id: 'id_formaddkota',
            xtype: 'formaddkota'
        },
        onHide: function(){
            Ext.getCmp('id_formaddkota').getForm().reset();
        }
    });
    
    /* START GRID */    
    
    // data store
    var strkota = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'kd_kota',
                'kd_propinsi',
                'nama_propinsi',
                'nama_kota'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("wilayah_kota/get_rows") ?>',
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
    var searchkota = new Ext.app.SearchField({
        store: strkota,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },

        width: 220,
        id: 'idsearchkota'
    });
    
    // top toolbar
    var tbkota = new Ext.Toolbar({
        items: [{
            text: 'Add',
            icon: BASE_ICONS + 'add.png',
            onClick: function(){      
		strcbpropinsi.reload();
                Ext.getCmp('id_cbpropinsi_kota').setDisabled(false);
                Ext.getCmp('id_cbpropinsi_kota').setValue('');	
                Ext.getCmp('btnresetkota').show();
                Ext.getCmp('btnsubmitkota').setText('Submit');
                winaddkota.setTitle('Add Form');
                winaddkota.show();
            }            
        }, '-', searchkota]
    });
    
    // checkbox grid
    var cbGrid = new Ext.grid.CheckboxSelectionModel();
    
    // row actions
    var actionkota = new Ext.ux.grid.RowActions({
		header :'Edit',
		autoWidth: false,
		width: 30,
        actions:[{iconCls: 'icon-edit-record', qtip: 'Edit'}],
        widthIntercept: Ext.isSafari ? 4 : 2
    }); 
    var actionkotadel = new Ext.ux.grid.RowActions({
		header: 'Delete',
		autoWidth: false,
		width: 40,
        actions:[{iconCls: 'icon-delete-record', qtip: 'Delete'}],
        widthIntercept: Ext.isSafari ? 4 : 2
    }); 
    
    actionkota.on('action', function(grid, record, action, row, col) {
        var kd_kota = record.get('kd_kota');
        var kd_propinsi = record.get('kd_propinsi');
        var nm_propinsi = record.get('nama_propinsi');
        switch(action) {
            case 'icon-edit-record':
                editkota(kd_kota,kd_propinsi,nm_propinsi);
                break;
            case 'icon-delete-record':
                Ext.Msg.show({
                    title: 'Confirm',
                    msg: 'Are you sure delete selected row ?',
                    buttons: Ext.Msg.YESNO,
                    fn: function(btn){
                        if (btn == 'yes') {
                            Ext.Ajax.request({
                                url: '<?= site_url("wilayah_kota/delete_rows") ?>',
                                method: 'POST',
                                params: {
                                    postdata: kd_kota + '-' + kd_propinsi
                                },
                                callback:function(opt,success,responseObj){
                                    var de = Ext.util.JSON.decode(responseObj.responseText);
                                    if(de.success==true){
                                        strkota.reload();
                                        strkota.load({
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
    
    // grid
    var kota = new Ext.grid.EditorGridPanel({
        //id: 'id-kota-gridpanel',
        id: 'kota',
		frame: true,
        border: true,
        stripeRows: true,
        sm: cbGrid,
        store: strkota,
        loadMask: true,
        style: 'margin:0 auto;',
        height: 450,
        columns: [actionkota,{
            dataIndex: 'kd_propinsi',
            hidden: true
        },{
            dataIndex: 'nama_propinsi',
            hidden: true
        },{
            header: "Kode Propinsi",
            dataIndex: 'kd_propinsi',
            sortable: true,
            width: 150
        },{
            header: "Nama Kota",
            dataIndex: 'nama_kota',
            sortable: true,
            width: 300
        }],
        plugins: [actionkota],
        listeners: {
            'rowdblclick': function(){              
                var sm = kota.getSelectionModel();                
                var sel = sm.getSelections();    		
                if (sel.length > 0) {
                    editkota(sel[0].get('kd_kota'),sel[0].get('kd_propinsi'),sel[0].get('nama_propinsi'));
                }                 
            }          
        },
        tbar: tbkota,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strkota,
            displayInfo: true
        })
    });
	/**
	var kotapanel = new Ext.FormPanel({
	 	id: 'kota',
		border: false,
        frame: false,
		autoScroll:true,	
        items: [kota]
	});
    **/
    function editkota(kd_kota,kd_propinsi,nama_propinsi){
        strcbpropinsi.load();
        Ext.getCmp('id_cbpropinsi_kota').setDisabled(true);
		Ext.getCmp('id_cbpropinsi_kota').setValue(nama_propinsi);			
        Ext.getCmp('btnsubmitkota').setText('Update');
        winaddkota.setTitle('Edit Form');
        Ext.getCmp('id_formaddkota').getForm().load({
            url: '<?= site_url("wilayah_kota/get_row") ?>',
            params: {
                id: kd_kota,
                id1: kd_propinsi,
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
        winaddkota.show();
    }
    
    function deletekota(){     
        var sm = kota.getSelectionModel();
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
                            data = data + sel[i].get('kd_kota') + '-' + sel[i].get('kd_propinsi') + ';';
                        }
                        
                        Ext.Ajax.request({
                            url: '<?= site_url("wilayah_kota/delete_rows") ?>',
                            method: 'POST',
                            params: {
                                postdata: data
                            },
                            callback:function(opt,success,responseObj){
                                var de = Ext.util.JSON.decode(responseObj.responseText);
                                if(de.success==true){
                                    strkota.reload();
                                    strkota.load({
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
