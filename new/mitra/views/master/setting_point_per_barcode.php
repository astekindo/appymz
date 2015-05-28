<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">
    var strsetting_point_per_barcode = new Ext.data.Store({
        autoSave: false,
        reader: new Ext.data.JsonReader({
            fields: [
                {name: 'kd_produk', allowBlank: false, type: 'text'},
                {name: 'kd_produk_lama', allowBlank: false, type: 'text'},
                {name: 'nama_produk', allowBlank: false, type: 'text'},
                {name: 'kd_kategori1', allowBlank: false, type: 'text'},
                {name: 'kd_kategori2', allowBlank: false, type: 'text'},
                {name: 'kd_kategori3', allowBlank: false, type: 'text'},
                {name: 'kd_kategori4', allowBlank: false, type: 'text'},
                {name: 'nama_kategori1', allowBlank: true, type: 'text'},
                {name: 'nama_kategori2', allowBlank: true, type: 'text'},
                {name: 'nama_kategori3', allowBlank: true, type: 'text'},
                {name: 'nama_kategori4', allowBlank: true, type: 'text'},
                {name: 'point', allowBlank: true, type: 'int'},
                {name: 'tgl_awal', allowBlank: true, type: 'text'},
                {name: 'tgl_akhir', allowBlank: true, type: 'text'},
                {name: 'kd_point_setting', allowBlank: true, type: 'text'}
                ],
            root: 'data',
            totalproperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("setting_point_per_barcode/search_kategori") ?>',
            method: 'POST'
        }),
        writer: new Ext.data.JsonWriter(
                {
                    encode: true,
                    writeAllFields: true
                })
    });

    
    // combobox kategori1
    var str_sppb_cbkategori1 = new Ext.data.Store({
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
                var r = new (str_sppb_cbkategori1.recordType)({
                    'kd_kategori1': '',
                    'nama_kategori1': '-----'
                });
                str_sppb_cbkategori1.insert(0, r);
            },
            loadexception: function(event, options, response, error) {
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    var sppb_cbkategori1 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 1 <span class="asterix">*</span>',
        id: 'sppb_cbkategori1',
        store: str_sppb_cbkategori1,
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
                var kdsppb_cbkategori1 = sppb_cbkategori1.getValue();
                // sppb_cbkategori2.setValue();
                sppb_cbkategori2.store.proxy.conn.url = '<?= site_url("kategori3/get_kategori2") ?>/' + kdsppb_cbkategori1;
                sppb_cbkategori2.store.reload();
                strsetting_point_per_barcode.load({
                    params: {
                        kd_kategori1: Ext.getCmp('sppb_cbkategori1').getValue(),
                        kd_kategori2: Ext.getCmp('sppb_cbkategori2').getValue(),
                        kd_kategori3: Ext.getCmp('sppb_cbkategori3').getValue(),
                        kd_kategori4: Ext.getCmp('sppb_cbkategori4').getValue(),
                        }
                });
            }
        }
    });
    // combobox kategori2
    var str_sppb_cbkategori2 = new Ext.data.Store({
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
                var r = new (str_sppb_cbkategori2.recordType)({
                    'kd_kategori2': '',
                    'nama_kategori2': '-----'
                });
                str_sppb_cbkategori2.insert(0, r);
            },
            loadexception: function(event, options, response, error) {
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg === 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    var sppb_cbkategori2 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 2 <span class="asterix">*</span>',
        id: 'sppb_cbkategori2',
        mode: 'local',
        store: str_sppb_cbkategori2,
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
                var kd_sppb_cbkategori1 = sppb_cbkategori1.getValue();
                var kd_sppb_cbkategori2 = this.getValue();
                sppb_cbkategori3.setValue();
                sppb_cbkategori3.store.proxy.conn.url = '<?= site_url("kategori4/get_kategori3") ?>/' + kd_sppb_cbkategori1 + '/' + kd_sppb_cbkategori2;
                sppb_cbkategori3.store.reload();
                strsetting_point_per_barcode.load({
                    params: {
                        kd_kategori1: Ext.getCmp('sppb_cbkategori1').getValue(),
                        kd_kategori2: Ext.getCmp('sppb_cbkategori2').getValue(),
                        kd_kategori3: Ext.getCmp('sppb_cbkategori3').getValue(),
                        kd_kategori4: Ext.getCmp('sppb_cbkategori4').getValue(),
                        }
                });
            }
        }
    });

    // combobox kategori3
    var str_sppb_cbkategori3 = new Ext.data.Store({
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
                var r = new (str_sppb_cbkategori3.recordType)({
                    'kd_kategori3': '',
                    'nama_kategori3': '-----'
                });
                str_sppb_cbkategori3.insert(0, r);
            },
            loadexception: function(event, options, response, error) {
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg === 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    var sppb_cbkategori3 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 3 <span class="asterix">*</span>',
        id: 'sppb_cbkategori3',
        mode: 'local',
        store: str_sppb_cbkategori3,
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
                var kd_sppb_cbkategori1 = sppb_cbkategori1.getValue();
                var kd_sppb_cbkategori2 = sppb_cbkategori2.getValue();
                var kd_sppb_cbkategori3 = this.getValue();
                sppb_cbkategori4.setValue();
                sppb_cbkategori4.store.proxy.conn.url = '<?= site_url("master_barang/get_kategori4") ?>/' + kd_sppb_cbkategori1 + '/' + kd_sppb_cbkategori2 + '/' + kd_sppb_cbkategori3;
                sppb_cbkategori4.store.reload();
                strsetting_point_per_barcode.load({
                    params: {
                        kd_kategori1: Ext.getCmp('sppb_cbkategori1').getValue(),
                        kd_kategori2: Ext.getCmp('sppb_cbkategori2').getValue(),
                        kd_kategori3: Ext.getCmp('sppb_cbkategori3').getValue(),
                        kd_kategori4: Ext.getCmp('sppb_cbkategori4').getValue(),
                        }
                });

            }
        }
    });

    // combobox kategori4
    var str_sppb_cbkategori4 = new Ext.data.Store({
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
                var r = new (str_sppb_cbkategori4.recordType)({
                    'kd_kategori4': '',
                    'nama_kategori4': '-----'
                });
                str_sppb_cbkategori4.insert(0, r);
            },
            loadexception: function(event, options, response, error) {
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg === 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    var sppb_cbkategori4 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 4 <span class="asterix">*</span>',
        id: 'sppb_cbkategori4',
        mode: 'local',
        store: str_sppb_cbkategori4,
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
                strsetting_point_per_barcode.load({
                    params: {
                        kd_kategori1: Ext.getCmp('sppb_cbkategori1').getValue(),
                        kd_kategori2: Ext.getCmp('sppb_cbkategori2').getValue(),
                        kd_kategori3: Ext.getCmp('sppb_cbkategori3').getValue(),
                        kd_kategori4: Ext.getCmp('sppb_cbkategori4').getValue(),
                        }
                });

            }
        }
    });

   
    var headersetting_point_per_barcode = {
        layout: 'column',
        border: false,
        items: [{
                columnWidth: .5,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: {labelSeparator: ''},
                items: [{
                        xtype: 'hidden',
                        name: 'gridsender',
                        id: 'sppb_gridsender'
                    }, sppb_cbkategori1, sppb_cbkategori2
                ]
            }, {
                columnWidth: .5,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: {labelSeparator: ''},
                items: [sppb_cbkategori3, sppb_cbkategori4]
            }]
    };

    var editorsetting_point_per_barcode = new Ext.ux.grid.RowEditor({
        saveText: 'Update'
    });

    var gridsetting_point_per_barcode = new Ext.grid.GridPanel({
        store: strsetting_point_per_barcode,
        stripeRows: true,
        height: 200,
        frame: true,
        border: true,
        plugins: [editorsetting_point_per_barcode],
        columns: [{
                dataIndex: 'edited',
                header: 'Edited',
                width: 50,
                sortable: true,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'editedsppbGrid'
                })
            },{
                dataIndex: 'kd_point_setting',
                header: 'Kode Point',
                width: 90,
                sortable: true,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'sppb_kd_point'
                })
            },{
            header: 'Kode Barang',
            dataIndex: 'kd_produk',
            width: 100,
            sortable: true,
            editor: new Ext.form.TextField({
                readOnly: true,
                id: 'sppb_kd_produk'
            })
            },{
                header: 'Kode Barang Lama',
                dataIndex: 'kd_produk_lama',
                width: 110,
                sortable: true,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'sppb_kd_produk_lama'
                })
            },{
                header: 'Nama Barang',
                dataIndex: 'nama_produk',
                width: 300,
                sortable: true,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'sppb_nama_produk'
                })
            }, {
                xtype: 'numbercolumn',
                header: 'Point',
                dataIndex: 'point',
                width: 80,
                format: '0,0',
                align: 'right',
                editor: {
                    xtype: 'numberfield',
                    readOnly: false,
                    id: 'sppb_point',
                     listeners: {
                            'change': function(field, selectedValue) {
                                Ext.getCmp('editedsppbGrid').setValue('Y');
                            }
                        }
                }
            },{
                xtype: 'datecolumn',
                header: 'Tanggal Mulai Berlaku',
                dataIndex: 'tgl_awal',
                width: 130,
                align: 'right',
                editor: new Ext.form.DateField({
                        id: 'sppb_tgl_mulai_berlaku',
                        format: 'd/m/Y',
                        //minValue: (new Date()).clearTime(),
                         listeners:{			
                            'change': function() {
                               	  Ext.getCmp('editedsppbGrid').setValue('Y');
                            }
                        }
                    })
            },{
                xtype: 'datecolumn',
                header: 'Tanggal Akhir Berlaku',
                dataIndex: 'tgl_akhir',
                width: 130,
                align: 'right',
                editor: new Ext.form.DateField({
                        id: 'sppb_tgl_akhir_berlaku',
                        format: 'd/m/Y',
                        //minValue: (new Date()).clearTime(),
                         listeners:{			
                            'change': function() {
                               	  Ext.getCmp('editedsppbGrid').setValue('Y');
                            }
                        }
                    })
            }],
//        tbar: new Ext.Toolbar({
//            items: [searchgridsetting_point_per_barcode]
//        }),
        listeners: {
            'rowclick': function() {
                var sm = gridsetting_point_per_barcode.getSelectionModel();
                var sel = sm.getSelections();
                Ext.getCmp('grid_kd_produk').setValue(sel[0].get('kd_produk'));

//                gridsetting_point_per_barcodedetail.store.load({
//					params:{
//						kd_produk:sel[0].get('kd_produk')
//					}
//				})
            }
        }
    });

    
    var setting_point_per_barcode = new Ext.FormPanel({
        id: 'setting_point_per_barcode',
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
                items: [headersetting_point_per_barcode]
            },
            {	
                xtype:'fieldset',
                autoheight: true,
                title: 'Point',
                collapsed: false,
                collapsible: true,
                anchor: '50%',
                items: [ {
                                    xtype: 'numericfield',
                                    currencySymbol:'',
                                    fieldLabel: 'Jumlah Point',
                                    name: 'jumlah_point',				
                                    allowBlank:true,   
                                    id: 'jumlah_point',                
                                    width: 130
                                }]
                    ,buttons: [{
                        text: 'Apply All',
                        formBind: true,
                        handler: function(){
                            var kd_kategori1 =  Ext.getCmp('sppb_cbkategori1').getValue();
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
                            
                            strsetting_point_per_barcode.each(function(record){
                                    
                                record.set('point',Ext.getCmp('jumlah_point').getValue());
                                record.commit();
                                record.set('edited','Y');
                                record.commit();            
                            
                            });

                        }
                    }]
            },{	
                xtype:'fieldset',
                autoheight: true,
                title: 'Periode Point',
                collapsed: false,
                collapsible: true,
                anchor: '70%',
                items:[{
                        xtype : 'compositefield',
                        msgTarget: 'side',
                        fieldLabel: 'Tgl Mulai Point',
                        items : [ {
                                    xtype: 'datefield',
                                    fieldLabel: 'Tgl Mulai Point',
                                    name: 'tgl_mulai_point',				
                                    allowBlank:true,   
                                    format:'d-m-Y',  
                                    //editable:false,           
                                    id: 'tgl_mulai_point',                
                                    width: 150,
                                    minValue: (new Date()).clearTime() 
                                },{
                                xtype: 'displayfield',
                                value: 'Tgl Akhir Point',
                                width: 100
                            },{
                                xtype : 'compositefield',
                                msgTarget: 'side',
                                fieldLabel: 'Tgl Akhir Point',
                                width:150,
                                items : [{
                                        xtype: 'datefield',
                                        fieldLabel: 'Tgl Akhir Point',
                                        name: 'tgl_akhir_point',				
                                        allowBlank:true,   
                                        format:'d-m-Y',  
                                        //editable:false,           
                                        id: 'tgl_akhir_point',                
                                        width: 150,
                                        minValue: (new Date()).clearTime() 
                                    }]
												
                            }]
                    }],buttons: [{
                        text: 'Apply All',
                        formBind: true,
                        handler: function(){
                           var kd_kategori1 =  Ext.getCmp('sppb_cbkategori1').getValue();
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
                            
                            strsetting_point_per_barcode.each(function(record){
                                    
                                record.set('tgl_awal',Ext.getCmp('tgl_mulai_point').getValue());
                                record.set('tgl_akhir',Ext.getCmp('tgl_akhir_point').getValue());
                                
                                record.commit();
                                record.set('edited','Y');
                                record.commit();
                            });

                        }
                    }]
            },
            gridsetting_point_per_barcode
        ],
        buttons: [{
                text: 'Save',
                formBind: true,
                handler: function() {
                    var setting_point_per_barcode = new Array();
                    strsetting_point_per_barcode.each(function(node) {
                        setting_point_per_barcode.push(node.data);
                    });

                    Ext.getCmp('setting_point_per_barcode').getForm().submit({
                        url: '<?= site_url("setting_point_per_barcode/update_row") ?>',
                        scope: this,
                        params: {
                            detail: Ext.util.JSON.encode(setting_point_per_barcode)

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
                            clearsetting_point_per_barcode();
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

            }, {
                text: 'Reset',
                handler: function() {
                    clearsetting_point_per_barcode();
                }
            }]
    });

    function clearsetting_point_per_barcode() {
        Ext.getCmp('setting_point_per_barcode').getForm().reset();
        strsetting_point_per_barcode.removeAll();
    }
</script>
