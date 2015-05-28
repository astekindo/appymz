<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">
    var strsetting_target_jual = new Ext.data.Store({
        autoSave: false,
        reader: new Ext.data.JsonReader({
            fields: [
                {name: 'kd_kategori1', allowBlank: false, type: 'text'},
                {name: 'kd_kategori2', allowBlank: false, type: 'text'},
                {name: 'kd_kategori3', allowBlank: false, type: 'text'},
                {name: 'kd_kategori4', allowBlank: false, type: 'text'},
                {name: 'nama_kategori1', allowBlank: true, type: 'text'},
                {name: 'nama_kategori2', allowBlank: true, type: 'text'},
                {name: 'nama_kategori3', allowBlank: true, type: 'text'},
                {name: 'nama_kategori4', allowBlank: true, type: 'text'},
                {name: 'target_qty', allowBlank: true, type: 'int'},
                {name: 'target_rupiah', allowBlank: true, type: 'int'}   
            ],
            root: 'data',
            totalproperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("setting_target_jual/search_kategori") ?>',
            method: 'POST'
        }),
        writer: new Ext.data.JsonWriter(
                {
                    encode: true,
                    writeAllFields: true
                })
    });

    
    // combobox kategori1
    var str_stj_cbkategori1 = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_kategori1', 'nama_kategori1'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("kategori2/get_kategori1") ?>',
            method: 'POST'
        }),
        listeners: {
            load: function() {
                var r = new (str_stj_cbkategori1.recordType)({
                    'kd_kategori1': '',
                    'nama_kategori1': '-----'
                });
                str_stj_cbkategori1.insert(0, r);
            },
            loadexception: function(event, options, response, error) {
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    var stj_cbkategori1 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 1 <span class="asterix">*</span>',
        id: 'stj_cbkategori1',
        store: str_stj_cbkategori1,
        valueField: 'kd_kategori1',
        displayField: 'nama_kategori1',
        typeAhead: true,
        triggerAction: 'all',
        // allowBlank: false,
        editable: false,
        width: 170,
        anchor: '90%',
        hiddenName: 'nama_kategori1',
        emptyText: 'Pilih kategori 1',
        listeners: {
            'select': function(combo, records) {
                var kdstj_cbkategori1 = stj_cbkategori1.getValue();
                // stj_cbkategori2.setValue();
                stj_cbkategori2.store.proxy.conn.url = '<?= site_url("kategori3/get_kategori2") ?>/' + kdstj_cbkategori1;
                stj_cbkategori2.store.reload();
//                strsetting_target_jual.load({
//                    params: {
//                        kd_kategori1: Ext.getCmp('stj_cbkategori1').getValue(),
//                        kd_kategori2: Ext.getCmp('stj_cbkategori2').getValue(),
//                        kd_kategori3: Ext.getCmp('stj_cbkategori3').getValue(),
//                        kd_kategori4: Ext.getCmp('stj_cbkategori4').getValue(),
//                        bulan: Ext.getCmp('stj_bulan').getValue(),
//                        tahun: Ext.getCmp('stj_tahun').getValue()
//                    }
//                });
            }
        }
    });
    // combobox kategori2
    var str_stj_cbkategori2 = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_kategori2', 'nama_kategori2'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("kategori3/get_kategori2") ?>',
            method: 'POST'
        }),
        listeners: {
            load: function() {
                var r = new (str_stj_cbkategori2.recordType)({
                    'kd_kategori2': '',
                    'nama_kategori2': '-----'
                });
                str_stj_cbkategori2.insert(0, r);
            },
            loadexception: function(event, options, response, error) {
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    var stj_cbkategori2 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 2 <span class="asterix">*</span>',
        id: 'stj_cbkategori2',
        mode: 'local',
        store: str_stj_cbkategori2,
        valueField: 'kd_kategori2',
        displayField: 'nama_kategori2',
        typeAhead: true,
        triggerAction: 'all',
        // allowBlank: false,
        editable: false,
        width: 170,
        anchor: '90%',
        hiddenName: 'nama_kategori2',
        emptyText: 'Pilih kategori 2',
        listeners: {
            select: function(combo, records) {
                var kd_stj_cbkategori1 = stj_cbkategori1.getValue();
                var kd_stj_cbkategori2 = this.getValue();
                stj_cbkategori3.setValue();
                stj_cbkategori3.store.proxy.conn.url = '<?= site_url("kategori4/get_kategori3") ?>/' + kd_stj_cbkategori1 + '/' + kd_stj_cbkategori2;
                stj_cbkategori3.store.reload();
//                strsetting_target_jual.load({
//                    params: {
//                        kd_kategori1: Ext.getCmp('stj_cbkategori1').getValue(),
//                        kd_kategori2: Ext.getCmp('stj_cbkategori2').getValue(),
//                        kd_kategori3: Ext.getCmp('stj_cbkategori3').getValue(),
//                        kd_kategori4: Ext.getCmp('stj_cbkategori4').getValue(),
//                        bulan: Ext.getCmp('stj_bulan').getValue(),
//                        tahun: Ext.getCmp('stj_tahun').getValue()
//                    }
//                });
            }
        }
    });

    // combobox kategori3
    var str_stj_cbkategori3 = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_kategori3', 'nama_kategori3'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("kategori4/get_kategori3") ?>',
            method: 'POST'
        }),
        listeners: {
            load: function() {
                var r = new (str_stj_cbkategori3.recordType)({
                    'kd_kategori3': '',
                    'nama_kategori3': '-----'
                });
                str_stj_cbkategori3.insert(0, r);
            },
            loadexception: function(event, options, response, error) {
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    var stj_cbkategori3 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 3 <span class="asterix">*</span>',
        id: 'stj_cbkategori3',
        mode: 'local',
        store: str_stj_cbkategori3,
        valueField: 'kd_kategori3',
        displayField: 'nama_kategori3',
        typeAhead: true,
        triggerAction: 'all',
        // allowBlank: false,
        editable: false,
        width: 170,
        anchor: '90%',
        hiddenName: 'nama_kategori3',
        emptyText: 'Pilih kategori 3',
        listeners: {
            select: function(combo, records) {
                var kd_stj_cbkategori1 = stj_cbkategori1.getValue();
                var kd_stj_cbkategori2 = stj_cbkategori2.getValue();
                var kd_stj_cbkategori3 = this.getValue();
                stj_cbkategori4.setValue();
                stj_cbkategori4.store.proxy.conn.url = '<?= site_url("master_barang/get_kategori4") ?>/' + kd_stj_cbkategori1 + '/' + kd_stj_cbkategori2 + '/' + kd_stj_cbkategori3;
                stj_cbkategori4.store.reload();
//                strsetting_target_jual.load({
//                    params: {
//                        kd_kategori1: Ext.getCmp('stj_cbkategori1').getValue(),
//                        kd_kategori2: Ext.getCmp('stj_cbkategori2').getValue(),
//                        kd_kategori3: Ext.getCmp('stj_cbkategori3').getValue(),
//                        kd_kategori4: Ext.getCmp('stj_cbkategori4').getValue(),
//                        bulan: Ext.getCmp('stj_bulan').getValue(),
//                        tahun: Ext.getCmp('stj_tahun').getValue()
//                    }
//                });

            }
        }
    });

    // combobox kategori4
    var str_stj_cbkategori4 = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_kategori4', 'nama_kategori4'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("master_barang/get_kategori4") ?>',
            method: 'POST'
        }),
        listeners: {
            load: function() {
                var r = new (str_stj_cbkategori4.recordType)({
                    'kd_kategori4': '',
                    'nama_kategori4': '-----'
                });
                str_stj_cbkategori4.insert(0, r);
            },
            loadexception: function(event, options, response, error) {
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    var stj_cbkategori4 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 4 <span class="asterix">*</span>',
        id: 'stj_cbkategori4',
        mode: 'local',
        store: str_stj_cbkategori4,
        valueField: 'kd_kategori4',
        displayField: 'nama_kategori4',
        typeAhead: true,
        triggerAction: 'all',
        // allowBlank: false,
        editable: false,
        width: 170,
        anchor: '90%',
        hiddenName: 'nama_kategori4',
        emptyText: 'Pilih kategori 4',
        listeners: {
            select: function(combo, records) {
//                strsetting_target_jual.load({
//                    params: {
//                        kd_kategori1: Ext.getCmp('stj_cbkategori1').getValue(),
//                        kd_kategori2: Ext.getCmp('stj_cbkategori2').getValue(),
//                        kd_kategori3: Ext.getCmp('stj_cbkategori3').getValue(),
//                        kd_kategori4: Ext.getCmp('stj_cbkategori4').getValue(),
//                        bulan: Ext.getCmp('stj_bulan').getValue(),
//                        tahun: Ext.getCmp('stj_tahun').getValue()
//                    }
//                });

            }
        }
    });

   
    var headersetting_target_jual = {
        layout: 'column',
        border: false,
        buttonAlign:'left',
        items: [{
                columnWidth: .5,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: {labelSeparator: ''},
                items: [{
                        xtype: 'datefield',
                        fieldLabel: 'Bulan',
                        name: 'bulan',				
                        allowBlank:false,   
                        format:'m',  
                        anchor: '90%',             
                        id: 'stj_bulan'                
                        
                      },{
                        xtype: 'hidden',
                        name: 'gridsender',
                        id: 'stb_gridsender'
                    }, stj_cbkategori1, stj_cbkategori2
                ]
            }, {
                columnWidth: .5,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: {labelSeparator: ''},
                items: [{
                        xtype: 'datefield',
                        fieldLabel: 'Tahun',
                        name: 'tahun',				
                        allowBlank:false,   
                        format:'Y',  
                        anchor: '90%',            
                        id: 'stj_tahun'                
                        
                       },stj_cbkategori3, stj_cbkategori4]
            }],buttons: [{
			text: 'Filter',
                      	formBind: true,
			handler: function(){
				if (Ext.getCmp('stj_bulan').getValue() === '' || Ext.getCmp('stj_tahun').getValue() === '') {
                                    Ext.Msg.show({
                                        title: 'Error',
                                        msg: 'Pilih Bulan dan Tahun Dahulu!',
                                        modal: true,
                                        icon: Ext.Msg.ERROR,
                                        buttons: Ext.Msg.OK
                                    });
                                    return;
                                }
                                strsetting_target_jual.load({
                                    params: {
                                        kd_kategori1: Ext.getCmp('stj_cbkategori1').getValue(),
                                        kd_kategori2: Ext.getCmp('stj_cbkategori2').getValue(),
                                        kd_kategori3: Ext.getCmp('stj_cbkategori3').getValue(),
                                        kd_kategori4: Ext.getCmp('stj_cbkategori4').getValue(),
                                        bulan: Ext.getCmp('stj_bulan').getValue(),
                                        tahun: Ext.getCmp('stj_tahun').getValue()
                                    }
                                });
            }},{
			text: 'Reset',
                        formBind: true,
			handler: function(){
				clearsetting_target_jual();     
                        }
		}]
    };

    var editorsetting_target_jual = new Ext.ux.grid.RowEditor({
        saveText: 'Update'
    });

    var gridsetting_target_jual = new Ext.grid.GridPanel({
        store: strsetting_target_jual,
        stripeRows: true,
        height: 200,
        frame: true,
        border: true,
        plugins: [editorsetting_target_jual],
        columns: [{
                dataIndex: 'edited',
                header: 'Edited',
                width: 50,
                sortable: true,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'editedStjGrid'
                })
            }, {
                header: 'Kategori1',
                dataIndex: 'nama_kategori1',
                width: 110,
                sortable: true,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'stj_nama_kategori1'
                })
            },{
                dataIndex: 'kd_kategori1',
                width: 110,
                hidden: true
            },  {
                header: 'Nama Kategori 2',
                dataIndex: 'nama_kategori2',
                width: 110,
                sortable: true
            },{
                dataIndex: 'kd_kategori2',
                width: 110,
                hidden: true
            }, {
                header: 'Nama Kategori 3',
                dataIndex: 'nama_kategori3',
                width: 110,
                sortable: true
            },{
                dataIndex: 'kd_kategori3',
                width: 110,
                hidden: true
            }, {
                header: 'Nama Kategori 4',
                dataIndex: 'nama_kategori4',
                width: 180
            },{
                dataIndex: 'kd_kategori4',
                width: 110,
                hidden: true
            },{
                xtype: 'numbercolumn',
                header: 'Target Qty',
                dataIndex: 'target_qty',
                width: 80,
                format: '0,0',
                align: 'right',
                editor: {
                    xtype: 'numberfield',
                    readOnly: false,
                    id: 'stj_target_qty',
                     listeners: {
                            'change': function(field, selectedValue) {
                                Ext.getCmp('editedStjGrid').setValue('Y');
                            }
                        }
                }
            },{ xtype: 'numbercolumn',
                header: 'Target Rupiah',
                dataIndex: 'target_rupiah',
                width: 110,
                format: '0,0',
                align: 'right',
                editor: {
                    xtype: 'numberfield',
                    readOnly: false,
                    id: 'stj_target_rupiah',
                    listeners: {
                            'change': function(field, selectedValue) {
                                Ext.getCmp('editedStjGrid').setValue('Y');
                            }
                        }
                }
            }],
//        tbar: new Ext.Toolbar({
//            items: [searchgridsetting_target_jual]
//        }),
        listeners: {
            'rowclick': function() {
                var sm = gridsetting_target_jual.getSelectionModel();
                var sel = sm.getSelections();
                Ext.getCmp('grid_kd_produk').setValue(sel[0].get('kd_produk'));

//                gridsetting_target_jualdetail.store.load({
//					params:{
//						kd_produk:sel[0].get('kd_produk')
//					}
//				})
            }
        }
    });

    
    var setting_target_jual = new Ext.FormPanel({
        id: 'setting_target_jual',
        border: false,
        frame: true,
        autoScroll: true,
        monitorValid: true,
        bodyStyle: 'padding-right:20px;',
        labelWidth: 130,
        items: [{
                bodyStyle: {
                    margin: '0px 0px 15px 0px'
                },
                items: [headersetting_target_jual]
            },
             {	
                xtype:'fieldset',
                autoheight: true,
                title: 'Target',
                collapsed: false,
                collapsible: true,
                anchor: '60%',
                items:[{
                        xtype : 'compositefield',
                        msgTarget: 'side',
                        fieldLabel: 'Target Qty',
                        items : [ {
                                    xtype: 'numericfield',
                                    currencySymbol:'',
                                    fieldLabel: 'Target Qty',
                                    name: 'target_qty',				
                                    allowBlank:true,   
                                    id: 'stj_target_qty',                
                                    width: 130,
                                    //minValue: (new Date()).clearTime() 
                                },{
                                xtype: 'displayfield',
                                value: 'Target Rupiah',
                                width: 100
                            },{
                                xtype : 'compositefield',
                                msgTarget: 'side',
                                fieldLabel: 'Target Rupiah',
                                width:130,
                                items : [{
                                        xtype: 'numericfield',
                                        currencySymbol:'',
                                        fieldLabel: 'Target Rupiah',
                                        name: 'target_rupiah',				
                                        allowBlank:true,   
                                        //format:'Y',  
                                        //editable:false,           
                                        id: 'stj_target_rupiah',                
                                        width: 130,
                                        //minValue: (new Date()).clearTime() 
                                    }]
												
                            }]
                    }],buttons: [{
                        text: 'Apply All',
                        formBind: true,
                        handler: function(){
                            var kd_kategori1 =  Ext.getCmp('stj_cbkategori1').getValue();
                            if(!kd_kategori1){
                                Ext.Msg.show({
                                    title: 'Error',
                                    msg: 'Silahkan Pilih Kategori Terlebih Dahulu',
                                    modal: true,
                                    icon: Ext.Msg.ERROR,
                                    buttons: Ext.Msg.OK
                                });
                                return;
                            }
                            
                            strsetting_target_jual.each(function(record){
                                    
                                record.set('target_qty',Ext.getCmp('stj_target_qty').getValue());
                                record.set('target_rupiah',Ext.getCmp('stj_target_rupiah').getValue());
                                
                                record.set('edited','Y');
                                record.commit();
                            });

                        }
                    }]
            },
            gridsetting_target_jual
        ],
        buttons: [{
                text: 'Save',
                formBind: true,
                handler: function() {
                    var setting_target_jual = new Array();
                    strsetting_target_jual.each(function(node) {
                        setting_target_jual.push(node.data);
                    });

                    Ext.getCmp('setting_target_jual').getForm().submit({
                        url: '<?= site_url("setting_target_jual/update_row") ?>',
                        scope: this,
                        params: {
                            detail: Ext.util.JSON.encode(setting_target_jual)

                        },
                        waitMsg: 'Saving Data...',
                        success: function(form, action) {
                            Ext.Msg.show({
                                title: 'Success',
                                msg: 'Form submitted successfully',
                                modal: true,
                                icon: Ext.Msg.INFO,
                                buttons: Ext.Msg.OK
                            });
                            clearsetting_target_jual();
                        },
                        failure: function(form, action) {
                            var fe = Ext.util.JSON.decode(action.response.responseText);
                            Ext.Msg.show({
                                title: 'Error',
                                msg: fe.errMsg,
                                modal: true,
                                icon: Ext.Msg.ERROR,
                                buttons: Ext.Msg.OK,
                                fn: function(btn) {
                                    if (btn === 'ok' && fe.errMsg === 'Session Expired') {
                                        window.location = '<?= site_url("auth/login") ?>';
                                    }
                                }
                            });

                        }
                    });
                }

            }]
    });

    function clearsetting_target_jual() {
        Ext.getCmp('setting_target_jual').getForm().reset();
        Ext.getCmp('stj_target_qty').setValue('');
        Ext.getCmp('stj_target_rupiah').setValue('');
        strsetting_target_jual.removeAll();
    }
</script>
