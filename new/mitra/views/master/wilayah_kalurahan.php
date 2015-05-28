<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">    
    /* START FORM */ 

    // combobox propinsi
    var str_k4_cbpropinsi = new Ext.data.Store({
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

    var ___cbpropinsi = new Ext.form.ComboBox({
        fieldLabel: 'Propinsi <span class="asterix">*</span>',
        id: 'id_k4_cbpropinsi',	
        store: str_k4_cbpropinsi,
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
                var kd____cbpropinsi = this.getValue();
                ___cbkota.setValue();
                ___cbkota.store.proxy.conn.url = '<?= site_url("wilayah_kecamatan/get_kota") ?>/' + kd____cbpropinsi;
                ___cbkota.store.reload();
            }
        }
    });
    // combobox kota
    var str_k4_cbkota = new Ext.data.Store({
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

    var ___cbkota = new Ext.form.ComboBox({
        fieldLabel: 'Kota <span class="asterix">*</span>',
        id: 'id_k4_cbkota',
        mode: 'local',
        store: str_k4_cbkota,
        valueField: 'kd_kota',
        displayField: 'nama_kota',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_kota',
        emptyText: 'Pilih Kota',
        listeners: {
            select: function(combo, records) {
                var kd____cbpropinsi = ___cbpropinsi.getValue();
                var kd____cbkota = this.getValue();
                ___cbkecamatan.setValue();
                ___cbkecamatan.store.proxy.conn.url = '<?= site_url("wilayah_kalurahan/get_kecamatan") ?>/' + kd____cbpropinsi +'/'+ kd____cbkota;
                ___cbkecamatan.store.reload();
            }
        }
    });
	
    // combobox kecamatan
    var str_k4_cbkecamatan = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_kecamatan', 'nama_kecamatan'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("wilayah_kalurahan/get_kecamatan") ?>',
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

    var ___cbkecamatan = new Ext.form.ComboBox({
        fieldLabel: 'Kecamatan <span class="asterix">*</span>',
        id: 'id_k4_cbkecamatan',
        mode: 'local',
        store: str_k4_cbkecamatan,
        valueField: 'kd_kecamatan',
        displayField: 'nama_kecamatan',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_kecamatan',
        emptyText: 'Pilih Kecamatan'
    });

    Ext.ns('kalurahanform');
    kalurahanform.Form = Ext.extend(Ext.form.FormPanel, {
    
        // defaults - can be changed from outside
        border: false,
        frame: true,
        labelWidth: 100,
        url: '<?= site_url("wilayah_kalurahan/update_row") ?>',
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
            kalurahanform.Form.superclass.constructor.call(this, config);
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
                },{
                    xtype: 'hidden',
                    name: 'kd_kalurahan'
                },___cbpropinsi,___cbkota,___cbkecamatan, {
                    type: 'textfield',
                    fieldLabel: 'Kalurahan <span class="asterix">*</span>',
                    name: 'nama_kalurahan',
                    allowBlank: false,
                    id: 'id_nama_kalurahan',
                    maxLength: 40,
					style:'text-transform: uppercase',  
                    anchor: '90%'                
                }],
                buttons: [{
                    text: 'Submit',
                    id: 'btnsubmitkalurahan',
                    formBind: true,
                    scope: this,
                    handler: this.submit
                }, {
                    text: 'Reset',
                    id: 'btnresetkalurahan',
                    scope: this,
                    handler: this.reset
                }, {
                    text: 'Close',
                    id: 'btnClosekalurahan',
                    scope: this,
                    handler: function(){
                        winaddkalurahan.hide();
                    }
                }]
            }; // eo config object
            // apply config
            Ext.apply(this, Ext.apply(this.initialConfig, config));
            
            // call parent
            kalurahanform.Form.superclass.initComponent.apply(this, arguments);
            
        } // eo function initComponent  
        ,
        onRender: function(){
        
            // call parent
            kalurahanform.Form.superclass.onRender.apply(this, arguments);
            
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
			var text = Ext.getCmp('btnsubmitkalurahan').getText();
			if (text == 'Update'){
				Ext.Msg.show({
					title: 'Confirm',
					msg: 'Are you sure update selected row ?',
					buttons: Ext.Msg.YESNO,
					fn: function(btn){
						if (btn == 'yes') {
							Ext.getCmp('id_formaddkalurahan').getForm().submit({
								url: Ext.getCmp('id_formaddkalurahan').url,
								scope: this,
								success: Ext.getCmp('id_formaddkalurahan').onSuccess,
								failure: Ext.getCmp('id_formaddkalurahan').onFailure,
								params: {
									cmd: 'save'
								},
								waitMsg: 'Saving Data...'
							});
						}
					}
				})
			}else{
				Ext.getCmp('id_formaddkalurahan').getForm().submit({
					url: Ext.getCmp('id_formaddkalurahan').url,
					scope: this,
					success: Ext.getCmp('id_formaddkalurahan').onSuccess,
					failure: Ext.getCmp('id_formaddkalurahan').onFailure,
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
            
            
            strkalurahan.reload();
            Ext.getCmp('id_formaddkalurahan').getForm().reset();
            winaddkalurahan.hide();
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
    Ext.reg('formaddkalurahan', kalurahanform.Form);
    
    var winaddkalurahan = new Ext.Window({
        id: 'id_winaddkalurahan',
        closeAction: 'hide',
        width: 450,
        height: 350,
        layout: 'fit',
        border: false,
        items: {
            id: 'id_formaddkalurahan',
            xtype: 'formaddkalurahan'
        },
        onHide: function(){
            Ext.getCmp('id_formaddkalurahan').getForm().reset();
        }
    });
    
    /* START GRID */    
    
    // data store
    var strkalurahan = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'kd_propinsi', 
                'kd_kota', 
                'kd_kecamatan', 
                'kd_kalurahan',
                'nama_propinsi',
                'nama_kota',
                'nama_kecamatan',
                'nama_kalurahan'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("wilayah_kalurahan/get_rows") ?>',
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
    var searchkalurahan = new Ext.app.SearchField({
        store: strkalurahan,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        id: 'idsearchkalurahan'
    });
    
    // top toolbar
    var tbkalurahan = new Ext.Toolbar({
        items: [{
            text: 'Add',
            icon: BASE_ICONS + 'add.png',
            onClick: function(){             
                Ext.getCmp('id_k4_cbpropinsi').setDisabled(false);
                Ext.getCmp('id_k4_cbkota').setDisabled(false);
                Ext.getCmp('id_k4_cbkecamatan').setDisabled(false); 
				Ext.getCmp('id_k4_cbpropinsi').setValue('');
				Ext.getCmp('id_k4_cbkota').setValue('');   
				Ext.getCmp('id_k4_cbkecamatan').setValue('');   
                Ext.getCmp('btnresetkalurahan').show();
                Ext.getCmp('btnsubmitkalurahan').setText('Submit');
                winaddkalurahan.setTitle('Add Form');
                winaddkalurahan.show();                
            }            
        }, '-', searchkalurahan]
    });
    
    // checkbox grid
    var cbGrid = new Ext.grid.CheckboxSelectionModel();
    
    // row actions
    var actionkalurahan = new Ext.ux.grid.RowActions({
		header :'Edit',
		autoWidth: false,
		width: 30,
        actions:[{iconCls: 'icon-edit-record', qtip: 'Edit'}],
        widthIntercept: Ext.isSafari ? 4 : 2
    }); 
    var actionkalurahandel = new Ext.ux.grid.RowActions({
		header: 'Delete',
		autoWidth: false,
		width: 40,
        actions:[{iconCls: 'icon-delete-record', qtip: 'Delete'}],
        widthIntercept: Ext.isSafari ? 4 : 2
    }); 
    
    actionkalurahan.on('action', function(grid, record, action, row, col) {
        var nm_propinsi = record.get('nama_propinsi');
        var nm_kota = record.get('nama_kota');
        var nm_kecamatan = record.get('nama_kecamatan');
        var kd_propinsi = record.get('kd_propinsi');
        var kd_kota = record.get('kd_kota');
        var kd_kecamatan = record.get('kd_kecamatan');
        var kd_kalurahan = record.get('kd_kalurahan');
        switch(action) {
            case 'icon-edit-record':                
                editkalurahan(nm_propinsi,nm_kota,nm_kecamatan,kd_propinsi,kd_kota,kd_kecamatan,kd_kalurahan);
                break;
            case 'icon-delete-record':
                Ext.Msg.show({
                    title: 'Confirm',
                    msg: 'Are you sure delete selected row ?',
                    buttons: Ext.Msg.YESNO,
                    fn: function(btn){
                        if (btn == 'yes') {
                            Ext.Ajax.request({
                                url: '<?= site_url("wilayah_kalurahan/delete_rows") ?>',
                                method: 'POST',
                                params: {
                                    postdata: kd_kecamatan + '-' +kd_kalurahan
                                },
                                callback:function(opt,success,responseObj){
                                    var de = Ext.util.JSON.decode(responseObj.responseText);
                                    if(de.success==true){
                                        strkalurahan.reload();
                                        strkalurahan.load({
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
    var kalurahan = new Ext.grid.EditorGridPanel({
        //id: 'id-kalurahan-gridpanel',
        id: 'kalurahan',
		frame: true,
        border: true,
        stripeRows: true,
        sm: cbGrid,
        store: strkalurahan,
        loadMask: true,
        style: 'margin:0 auto;',
        height: 450,
        columns: [ actionkalurahan,{
            dataIndex: 'kd_propinsi',
            hidden: true
        },{
            dataIndex: 'kd_kota',
            hidden: true
        },{
            dataIndex: 'kd_kecamatan',
            hidden: true
        },{
            dataIndex: 'kd_kalurahan',
            hidden: true
        },{
            header: "Kode Kalurahan",
            dataIndex: 'kd_kalurahan',
            sortable: true,
            width: 150
        },{
            header: "Nama Kalurahan",
            dataIndex: 'nama_kalurahan',
            sortable: true,
            width: 400
        }],
        plugins: [actionkalurahan],
        listeners: {
            'rowdblclick': function(){              
                var sm = kalurahan.getSelectionModel();                
                var sel = sm.getSelections();                
                if (sel.length > 0) {
                    editkalurahan(sel[0].get('nama_propinsi'),sel[0].get('nama_kota'),sel[0].get('nama_kecamatan'),
									sel[0].get('kd_propinsi'),sel[0].get('kd_kota'),sel[0].get('kd_kecamatan'),
									sel[0].get('kd_kalurahan')
								);                    
                }                 
            }          
        },
        tbar: tbkalurahan,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strkalurahan,
            displayInfo: true
        })
    });
	/**
	var kelurahanpanel = new Ext.FormPanel({
	 	id: 'kalurahan',
		border: false,
        frame: false,
		autoScroll:true,	
        items: [kalurahan]
	});
    **/
    function editkalurahan(nama_propinsi,nama_kota,nama_kecamatan,kd_propinsi,kd_kota,kd_kecamatan,kd_kalurahan){
        str_k4_cbpropinsi.load();
        str_k4_cbkota.load();
        str_k4_cbkecamatan.load();
        Ext.getCmp('id_k4_cbpropinsi').setDisabled(true);
        Ext.getCmp('id_k4_cbkota').setDisabled(true);
        Ext.getCmp('id_k4_cbkecamatan').setDisabled(true);
		Ext.getCmp('id_k4_cbpropinsi').setValue(nama_propinsi);
		Ext.getCmp('id_k4_cbkota').setValue(nama_kota);   
		Ext.getCmp('id_k4_cbkecamatan').setValue(nama_kecamatan);   
        Ext.getCmp('btnresetkalurahan').hide();
        Ext.getCmp('btnsubmitkalurahan').setText('Update');
        winaddkalurahan.setTitle('Edit Form');
        Ext.getCmp('id_formaddkalurahan').getForm().load({
            url: '<?= site_url("wilayah_kalurahan/get_row") ?>',
            params: {
                id1: kd_propinsi,
                id2: kd_kota,
                id3: kd_kecamatan,
                id: kd_kalurahan,
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
        winaddkalurahan.show();
    }
    
    function deletekalurahan(){     
        var sm = kalurahan.getSelectionModel();
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
                            data = data + sel[i].get('kd_propinsi') + '-' + sel[i].get('kd_kota') + '-' + sel[i].get('kd_kecamatan') + '-' + sel[i].get('kd_kalurahan') +  ';';
                        }
                        
                        Ext.Ajax.request({
                            url: '<?= site_url("wilayah_kalurahan/delete_rows") ?>',
                            method: 'POST',
                            params: {
                                postdata: data
                            },
                            callback:function(opt,success,responseObj){
                                var de = Ext.util.JSON.decode(responseObj.responseText);
                                if(de.success==true){
                                    strkalurahan.reload();
                                    strkalurahan.load({
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
