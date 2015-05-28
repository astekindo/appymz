<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">    
    /* START FORM */ 

    // combobox propinsi
    var str__cbpropinsi = new Ext.data.Store({
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

    var __cbpropinsi = new Ext.form.ComboBox({
        fieldLabel: 'Propinsi <span class="asterix">*</span>',
        id: 'id___cbpropinsi',
        store: str__cbpropinsi,
        valueField: 'kd_propinsi',
        displayField: 'nama_propinsi',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_propinsi',
        emptyText: 'Pilih Propinsi',
        listeners: {
            select: function(combo, records) {
                var kd___cbpropinsi = this.getValue();
                __cbkota.setValue();
                __cbkota.store.proxy.conn.url = '<?= site_url("wilayah_kecamatan/get_kota") ?>/' + kd___cbpropinsi;
                __cbkota.store.reload();
            }
        }
    });
    
    // combobox kota
    var str__cbkota = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_kota', 'nama_kota'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("wilayah_kecamatan/get_kota") ?>',
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

    var __cbkota = new Ext.form.ComboBox({
        fieldLabel: 'Kota <span class="asterix">*</span>',
        id: 'id___cbkota',
        mode: 'local',
        store: str__cbkota,
        valueField: 'kd_kota',
        displayField: 'nama_kota',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_kota',
        emptyText: 'Pilih Kota'
    });

    Ext.ns('kecamatanform');
    kecamatanform.Form = Ext.extend(Ext.form.FormPanel, {
    
        // defaults - can be changed from outside
        border: false,
        frame: true,
        labelWidth: 100,
        url: '<?= site_url("wilayah_kecamatan/update_row") ?>',
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
            kecamatanform.Form.superclass.constructor.call(this, config);
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
                },{
                    xtype: 'hidden',
                    name: 'kd_kota'
                },{
                    xtype: 'hidden',
                    name: 'kd_kecamatan'
                },__cbpropinsi,__cbkota, {
                    type: 'textfield',
                    fieldLabel: 'Kecamatan <span class="asterix">*</span>',
                    name: 'nama_kecamatan',
                    allowBlank: false,
                    id: 'id_nama_kecamatan',
					style:'text-transform: uppercase',  
                    maxLength: 40,
                    anchor: '90%'                
                }],
                buttons: [{
                    text: 'Submit',
                    id: 'btnsubmitkecamatan',
                    formBind: true,
                    scope: this,
                    handler: this.submit
                }, {
                    text: 'Reset',
                    id: 'btnresetkecamatan',
                    scope: this,
                    handler: this.reset
                }, {
                    text: 'Close',
                    id: 'btnClosekecamatan',
                    scope: this,
                    handler: function(){
                        winaddkecamatan.hide();
                    }
                }]
            }; // eo config object
            // apply config
            Ext.apply(this, Ext.apply(this.initialConfig, config));
            
            // call parent
            kecamatanform.Form.superclass.initComponent.apply(this, arguments);
            
        } // eo function initComponent  
        ,
        onRender: function(){
        
            // call parent
            kecamatanform.Form.superclass.onRender.apply(this, arguments);
            
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
			var text = Ext.getCmp('btnsubmitkecamatan').getText();
			if (text == 'Update'){
				Ext.Msg.show({
					title: 'Confirm',
					msg: 'Are you sure update selected row ?',
					buttons: Ext.Msg.YESNO,
					fn: function(btn){
						if (btn == 'yes') {
							Ext.getCmp('id_formaddkecamatan').getForm().submit({
								url: Ext.getCmp('id_formaddkecamatan').url,
								scope: this,
								success: Ext.getCmp('id_formaddkecamatan').onSuccess,
								failure: Ext.getCmp('id_formaddkecamatan').onFailure,
								params: {
									cmd: 'save'
								},
								waitMsg: 'Saving Data...'
							});
						}
					}
				})
			}else{
				Ext.getCmp('id_formaddkecamatan').getForm().submit({
					url: Ext.getCmp('id_formaddkecamatan').url,
					scope: this,
					success: Ext.getCmp('id_formaddkecamatan').onSuccess,
					failure: Ext.getCmp('id_formaddkecamatan').onFailure,
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
            
            
            strkecamatan.reload();
            Ext.getCmp('id_formaddkecamatan').getForm().reset();
            winaddkecamatan.hide();
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
    Ext.reg('formaddkecamatan', kecamatanform.Form);
    
    var winaddkecamatan = new Ext.Window({
        id: 'id_winaddkecamatan',
        closeAction: 'hide',
        width: 450,
        height: 350,
        layout: 'fit',
        border: false,
        items: {
            id: 'id_formaddkecamatan',
            xtype: 'formaddkecamatan'
        },
        onHide: function(){
            Ext.getCmp('id_formaddkecamatan').getForm().reset();
        }
    });
    
    /* START GRID */    
    
    // data store
    var strkecamatan = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'kd_propinsi', 
                'kd_kota', 
                'kd_kecamatan',
                'nama_propinsi',
                'nama_kota',
                'nama_kecamatan'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("wilayah_kecamatan/get_rows") ?>',
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
    var searchkecamatan = new Ext.app.SearchField({
        store: strkecamatan,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        id: 'idsearchkecamatan'
    });
    
    // top toolbar
    var tbkecamatan = new Ext.Toolbar({
        items: [{
            text: 'Add',
            icon: BASE_ICONS + 'add.png',
            onClick: function(){  
                Ext.getCmp('id___cbpropinsi').setDisabled(false);
                Ext.getCmp('id___cbkota').setDisabled(false);   
				Ext.getCmp('id___cbpropinsi').setValue('');
				Ext.getCmp('id___cbkota').setValue('');           
                Ext.getCmp('btnresetkecamatan').show();
                Ext.getCmp('btnsubmitkecamatan').setText('Submit');
                winaddkecamatan.setTitle('Add Form');
                winaddkecamatan.show();                
            }            
        }, '-', searchkecamatan]
    });
    
    // checkbox grid
    var cbGrid = new Ext.grid.CheckboxSelectionModel();
    
    // row actions
    var actionkecamatan = new Ext.ux.grid.RowActions({
		header :'Edit',
		autoWidth: false,
		width: 30,
        actions:[{iconCls: 'icon-edit-record', qtip: 'Edit'}],
        widthIntercept: Ext.isSafari ? 4 : 2
    }); 
    var actionkecamatandel = new Ext.ux.grid.RowActions({
		header: 'Delete',
		autoWidth: false,
		width: 40,
        actions:[{iconCls: 'icon-delete-record', qtip: 'Delete'}],
        widthIntercept: Ext.isSafari ? 4 : 2
    }); 
    
    actionkecamatan.on('action', function(grid, record, action, row, col) {
        var kd_propinsi = record.get('kd_propinsi');
        var kd_kota = record.get('kd_kota');
        var kd_kecamatan = record.get('kd_kecamatan');
        var nm_propinsi = record.get('nama_propinsi');
        var nm_kota = record.get('nama_kota');
        switch(action) {
            case 'icon-edit-record':                
                editkecamatan(kd_propinsi,kd_kota,kd_kecamatan,nm_propinsi,nm_kota);
                break;
            case 'icon-delete-record':
                Ext.Msg.show({
                    title: 'Confirm',
                    msg: 'Are you sure delete selected row ?',
                    buttons: Ext.Msg.YESNO,
                    fn: function(btn){
                        if (btn == 'yes') {
                            Ext.Ajax.request({
                                url: '<?= site_url("wilayah_kecamatan/delete_rows") ?>',
                                method: 'POST',
                                params: {
                                    postdata: kd_kota + '-' + kd_kecamatan 
                                },
                                callback:function(opt,success,responseObj){
                                    var de = Ext.util.JSON.decode(responseObj.responseText);
                                    if(de.success==true){
                                        strkecamatan.reload();
                                        strkecamatan.load({
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
    var kecamatan = new Ext.grid.EditorGridPanel({
        //id: 'id-kecamatan-gridpanel',
        id: 'kecamatan',
		frame: true,
        border: true,
        stripeRows: true,
        sm: cbGrid,
        store: strkecamatan,
        loadMask: true,
        style: 'margin:0 auto;',
        height: 450,
        columns: [actionkecamatan,{
            dataIndex: 'kd_propinsi',
            hidden: true
        },{
            dataIndex: 'kd_kota',
            hidden: true
        },{
            header: "Kode Kecamatan",
            dataIndex: 'kd_kecamatan',
            sortable: true,
            width: 150
        },{
            header: "Nama Kecamatan",
            dataIndex: 'nama_kecamatan',
            sortable: true,
            width: 400
        }],
        plugins: [actionkecamatan],
        listeners: {
            'rowdblclick': function(){              
                var sm = kecamatan.getSelectionModel();                
                var sel = sm.getSelections();                
                if (sel.length > 0) {
                    editkecamatan(sel[0].get('kd_propinsi'),sel[0].get('kd_kota'),
								sel[0].get('kd_kecamatan'),sel[0].get('nama_propinsi'),
								sel[0].get('nama_kota')
					);                    
                }                 
            }          
        },
        tbar: tbkecamatan,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strkecamatan,
            displayInfo: true
        })
    });
	/**
	var kecamatanpanel = new Ext.FormPanel({
	 	id: 'kecamatan',
		border: false,
        frame: false,
		autoScroll:true,	
        items: [kecamatan]
	});
    **/
    function editkecamatan(kd_propinsi,kd_kota,kd_kecamatan,nm_propinsi,nm_kota){
        str__cbpropinsi.load();
        str__cbkota.load();
		Ext.getCmp('id___cbpropinsi').setValue(nm_propinsi);
		Ext.getCmp('id___cbkota').setValue(nm_kota);
        Ext.getCmp('id___cbpropinsi').setDisabled(true);
        Ext.getCmp('id___cbkota').setDisabled(true);
        Ext.getCmp('btnresetkecamatan').hide();
        Ext.getCmp('btnsubmitkecamatan').setText('Update');
        winaddkecamatan.setTitle('Edit Form');
        Ext.getCmp('id_formaddkecamatan').getForm().load({
            url: '<?= site_url("wilayah_kecamatan/get_row") ?>',
            params: {
                id1: kd_propinsi,
                id2: kd_kota,
                id: kd_kecamatan,
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
        winaddkecamatan.show();
    }
    
</script>
