<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">
 //Start Combo Pelanggan
var strcbpum_pelanggan = new Ext.data.ArrayStore({
        fields: ['kd_pelanggan'],
        data: []
    });

    var strgridpum_pelanggan = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_pelanggan', 'nama_pelanggan', 'tipe','nama_tipe', 'alamat_kirim', 'no_telp'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("penjualan_distribusi/search_pelanggan") ?>',
            method: 'POST'
        }),
        listeners: {
            loadexception: function(event, options, response, error) {
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg === 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    var searchgridpum_pelanggan = new Ext.app.SearchField({
        store: strgridpum_pelanggan,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgridpum_pelanggan'
    });


    var gridpum_pelanggan = new Ext.grid.GridPanel({
        store: strgridpum_pelanggan,
        stripeRows: true,
        frame: true,
        border: true,
        columns: [{
                header: 'Kode Pelanggan',
                dataIndex: 'kd_pelanggan',
                width: 80,
                sortable: true
            }, {
                header: 'Nama Pelanggan',
                dataIndex: 'nama_pelanggan',
                width: 120,
                sortable: true
            }, {
                header: 'Jenis Pelanggan',
                dataIndex: 'nama_tipe',
                width: 100,
                sortable: true
            },{
                header: 'Kode Tipe',
                dataIndex: 'tipe',
                width: 100,
                sortable: true
            }],
        tbar: new Ext.Toolbar({
            items: [searchgridpum_pelanggan]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridpum_pelanggan,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('pum_kd_pelanggan').setValue(sel[0].get('kd_pelanggan'));
                    Ext.getCmp('id_cbpum_pelanggan').setValue(sel[0].get('nama_pelanggan'));
                    menupum_pelanggan.hide();
                }
            }
        }
    });

    var menupum_pelanggan = new Ext.menu.Menu();
    menupum_pelanggan.add(new Ext.Panel({
        title: 'Pilih Pelanggan',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridpum_pelanggan],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menupum_pelanggan.hide();
                }
            }]
    }));

    Ext.ux.TwinCombofppelanggan = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            //load store grid
            strgridpum_pelanggan.load();
            menupum_pelanggan.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
                trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    menupum_pelanggan.on('hide', function() {
        var sf = Ext.getCmp('id_searchgridpum_pelanggan').getValue();
        if (sf !== '') {
            Ext.getCmp('id_searchgridpum_pelanggan').setValue('');
            searchgridpum_pelanggan.onTrigger2Click();
        }
    });

    var cbpum_pelanggan = new Ext.ux.TwinCombofppelanggan({
        fieldLabel: 'Pelanggan <span class="asterix">*</span>',
        id: 'id_cbpum_pelanggan',
        store: strcbpum_pelanggan,
        mode: 'local',
        valueField: 'nama_pelanggan',
        displayField: 'nama_pelanggan',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'nama_pelanggan',
        emptyText: 'Pilih Pelanggan'
    });
    // End Combo Pelanggan
//Header Pembayaran
var headerpembayaran_uang_muka = {
        layout: 'column',
        border: false,
        items: [{
                columnWidth: .5,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: {labelSeparator: ''},
                items: [{
                        xtype: 'textfield',
                        fieldLabel: 'No Bukti',
                        name: 'no_bukti',
                        allowBlank: true,
                        readOnly: true,
                        fieldClass: 'readonly-input',
                        id: 'pum_no_bukti',
                        maxLength: 255,
                        anchor: '90%',
                        value: ''
                    },
                    cbpum_pelanggan
                ]
            }, {
                columnWidth: .5,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: {labelSeparator: ''},
                items: [{
                        xtype: 'datefield',
                        fieldLabel: 'Tanggal',
                        name: 'tanggal',
                        allowBlank: false,
                        format: 'd-M-Y',
                        emptyText: 'Tgl Pembayaran',
                        value: new Date(),
                        id: 'pum_tanggal',
                        anchor: '90%',
                        maxValue: (new Date()).clearTime()
                    },
                    {
                        xtype: 'textfield',
                        fieldLabel: 'Keterangan',
                        name: 'keterangan',
                        id: 'pum_keterangan',
                        anchor: '90%'
                    }, {
                        xtype: 'hidden',
                        name: 'kd_pelanggan',
                        fieldClass: 'readonly-input',
                        readOnly: true,
                        id: 'pum_kd_pelanggan',
                        anchor: '90%',
                        value: ''
                    } ]
            }
        ]
    };
 //End Header
 //Grid Pembayaran Uang Muka
  var strcbPumSO = new Ext.data.ArrayStore({
        fields: ['no_po'],
        data: []
    });

    var strgridPumNoSO = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['no_so', 'tgl_so', 'rp_total', 'rp_ppn', 'rp_dpp','rp_uang_muka','sisa_bayar'],
            root: 'data',
            totalproperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("pembayaran_uang_muka/search_so") ?>',
            method: 'POST'
        }),
        listeners: {
            loadexception: function(event, options, response, error) {
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg === 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    var gridPumNoSO = new Ext.grid.GridPanel({
        store: strgridPumNoSO,
        stripeRows: true,
        frame: true,
        border: true,
        columns: [{
                header: 'No SO',
                dataIndex: 'no_so',
                width: 120,
                sortable: true
            }, {
                header: 'Tanggal SO',
                dataIndex: 'tgl_so',
                width: 100,
                sortable: true
            },  {
                xtype: 'numbercolumn',
                header: 'Rp Total',
                dataIndex: 'rp_total',
                width: 100,
                align: 'right',
                format: '0,0',
                sortable: true
            }, {
                xtype: 'numbercolumn',
                header: 'Rp DPP',
                dataIndex: 'rp_dpp',
                width: 100,
                align: 'right',
                format: '0,0',
                sortable: true
            }, {
                xtype: 'numbercolumn',
                header: 'Rp PPN',
                dataIndex: 'rp_ppn',
                width: 100,
                align: 'right',
                format: '0,0',
                sortable: true
            }],
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('epum_no_so').setValue(sel[0].get('no_so'));
                    Ext.getCmp('epum_tgl_so').setValue(sel[0].get('tgl_so'));
                    Ext.getCmp('epum_rp_jumlah').setValue(sel[0].get('rp_total'));
                    Ext.getCmp('epum_rp_ppn').setValue(sel[0].get('rp_ppn'));
                    Ext.getCmp('epum_rp_dpp').setValue(sel[0].get('rp_dpp'));
                    Ext.getCmp('epum_jumlah_uang_muka').setValue(sel[0].get('rp_uang_muka'));
                    Ext.getCmp('epum_rp_uang_muka').setValue(sel[0].get('sisa_bayar'));
                    
                    var total_bayar = 0;
                    strpembayaranUangMuka.each(function(node) {
                        total_bayar += parseInt(node.data.rp_uang_muka);
                    });
                    Ext.getCmp('pum_rp_total_uang_muka').setValue(total_bayar);
                    
                    var _ada = false;
                    strpembayaranUangMuka.each(function(record){
                    if(record.get('no_so') === sel[0].get('no_so')){
                             _ada = true;
                         }
                     });
                     
                     if (_ada){
                                    Ext.Msg.show({
                                        title: 'Error',
                                        msg: 'No SO yang sama tidak boleh dipilih lebih dari satu kali',
                                        modal: true,
                                        icon: Ext.Msg.ERROR,
                                        buttons: Ext.Msg.OK,
                                        fn: function(btn){
                                            if (btn === 'ok') {
                                                Ext.getCmp('epum_no_so').reset();
                                            }
                                        }                            
                                    });
                                    Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');
                                    Ext.getCmp('epum_no_so').focus();	
                                    return;
                                }
                    
                    menuPumNoSO.hide();
                }
            }
        }
    });

    var menuPumNoSO = new Ext.menu.Menu();
    menuPumNoSO.add(new Ext.Panel({
        title: 'Pilih No SO',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 450,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridPumNoSO],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menuPumNoSO.hide();
                }
            }]
    }));

    Ext.ux.TwinComboPumSO = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            //load store grid
            strgridPumNoSO.load({
                params: {
                    kd_pelanggan: Ext.getCmp('pum_kd_pelanggan').getValue()
                }
            });
            menuPumNoSO.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur : false,
        trigger1Class  : 'x-form-clear-trigger',
        trigger2Class  : 'x-form-search-trigger',
        hideTrigger1: true
    });

    var strpembayaranUangMuka = new Ext.data.Store({
        autoSave: false,
        reader: new Ext.data.JsonReader({
            fields: [
                {name: 'no_so', allowBlank: false, type: 'text'},
                {name: 'tanggal', allowBlank: false, type: 'text'},
                {name: 'rp_jumlah_po', allowBlank: false, type: 'text'},
                {name: 'rp_ppn_po', allowBlank: false, type: 'int'},
                {name: 'rp_diskon_po', allowBlank: false, type: 'int'},
                {name: 'rp_total_po', allowBlank: false, type: 'text'},
                {name: 'rp_uang_muka', allowBlank: false, type: 'int'}
                
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        writer: new Ext.data.JsonWriter(
                {
                    encode: true,
                    writeAllFields: true
                })
    });

    strpembayaranUangMuka.on('update', function() {
        var total = 0;
        var total_potongan = 0;
        var total_dibayar = 0;
        var total_bayar = 0;//Ext.getCmp('pph_rp_total').getValue();
        strpembayaranUangMuka.each(function(node) {
            total += parseInt(node.data.rp_uang_muka);
            
        });
        strjenispembayaranUangMuka.each(function(node) {
            total_bayar += parseInt(node.data.rp_bayar);
            
        });

        //total_bayar = Ext.getCmp('rp_total_bayar').getValue();
        Ext.getCmp('pum_rp_total_uang_muka').setValue(total);
        var selisih = total_bayar - total;
        Ext.getCmp('pum_rp_selisih').setValue(selisih);
        
    });



    var editorpembayaranUangMuka = new Ext.ux.grid.RowEditor({
        saveText: 'Update'
    });


    var gridpembayaranUangMuka = new Ext.grid.GridPanel({
        store: strpembayaranUangMuka,
        stripeRows: true,
        height: 200,
        frame: true,
        border: true,
        tbar: [{
                icon: BASE_ICONS + 'add.png',
                text: 'Add SO',
                handler: function() {

                    if (Ext.getCmp('pum_kd_pelanggan').getValue() === '') {
                        Ext.Msg.show({
                            title: 'Error',
                            msg: 'Silahkan pilih pelanggan terlebih dulu',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK
                        });
                        return;
                    }

                    var rowpembayaran_po = new gridpembayaranUangMuka.store.recordType({
                        no_invoice: '',
                        rp_total: '',
                        rp_bayar: '',
                        rp_diskon: ''
                    });
                    editorpembayaranUangMuka.stopEditing();
                    strpembayaranUangMuka.insert(0, rowpembayaran_po);
                    gridpembayaranUangMuka.getView().refresh();
                    gridpembayaranUangMuka.getSelectionModel().selectRow(0);
                    editorpembayaranUangMuka.startEditing(0);
                }
            }, {
                ref: '../removeBtn',
                icon: BASE_ICONS + 'delete.gif',
                text: 'Remove',
                disabled: true,
                handler: function() {

                    editorpembayaranUangMuka.stopEditing();
                    var s = gridpembayaranUangMuka.getSelectionModel().getSelections();
                    for (var i = 0, r; r = s[i]; i++) {
                        strpembayaranUangMuka.remove(r);
                    }
                    var jumlah = 0;
                    var total_bayar =0;
                    strpembayaranUangMuka.each(function(node) {
                        jumlah += parseInt(node.data.rp_uang_muka);
                    });
                    strjenispembayaranUangMuka.each(function(node) {
                        total_bayar += parseInt(node.data.rp_bayar);
                    });
                    var total_bayar = Ext.getCmp('rp_total_bayar').getValue();
                    Ext.getCmp('pum_rp_total_uang_muka').setValue(jumlah);
                    var selisih = total_bayar - jumlah;
                    Ext.getCmp('pum_rp_selisih').setValue(selisih);
                   
                }
            }],
        plugins: [editorpembayaranUangMuka],
        columns: [{
                header: 'No SO',
                dataIndex: 'no_so',
                width: 150,
                sortable: true,
                editor: new Ext.ux.TwinComboPumSO({
                    id: 'epum_no_so',
                    store: strcbPumSO,
                    mode: 'local',
                    valueField: 'no_so',
                    displayField: 'no_so',
                    typeAhead: true,
                    triggerAction: 'all',
                    editable: false,
                    hiddenName: 'no_so',
                    emptyText: 'Pilih No SO'

                })
            }, {
                header: 'Tanggal SO',
                dataIndex: 'tgl_so',
                width: 100,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'epum_tgl_so',
                    fieldClass: 'readonly-input number'
                })
            }, {
                header: 'Rp Jumlah',
                dataIndex: 'rp_jumlah',
                width: 100,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'epum_rp_jumlah',
                    fieldClass: 'readonly-input number'
                })
            }, {
                xtype: 'numbercolumn',
                header: 'Rp DPP',
                dataIndex: 'rp_dpp',
                width: 100,
                sortable: true,
                align: 'right',
                format: '0,0',
                editor: {
                    xtype: 'numberfield',
                    id: 'epum_rp_dpp',
                    fieldClass: 'readonly-input number',
                    readOnly: true
                   
                }
            },  {
                xtype: 'numbercolumn',
                header: 'Rp PPN',
                dataIndex: 'rp_ppn',
                width: 150,
                align: 'right',
                sortable: true,
                format: '0,0',
                editor: {
                    xtype: 'numberfield',
                    id: 'epum_rp_ppn',
                    fieldClass: 'readonly-input number',
                    readOnly: true
                }
            }, {
                xtype: 'numbercolumn',
                header: 'Jumlah Uang Muka',
                dataIndex: 'jumlah_uang_muka',
                width: 130,
                sortable: true,
                align: 'right',
                format: '0,0',
                editor: {
                    xtype: 'numberfield',
                    id: 'epum_jumlah_uang_muka',
                    fieldClass: 'readonly-input number',
                    readOnly: true
                    
                }
            },{
                xtype: 'numbercolumn',
                header: 'Rp Uang Muka',
                dataIndex: 'rp_uang_muka',
                width: 100,
                sortable: true,
                align: 'right',
                format: '0,0',
                editor: {
                    xtype: 'numberfield',
                    id: 'epum_rp_uang_muka'
                    
                }
            }]
    });

    gridpembayaranUangMuka.getSelectionModel().on('selectionchange', function(sm) {
        gridpembayaranUangMuka.removeBtn.setDisabled(sm.getCount() < 1);
    });
 //End Pembayaran
 // Grid Jenis Pembayaran
    var _strcbpumjnspembayaran = new Ext.data.ArrayStore({
        fields: ['kd_jenis_bayar'],
        data: []
    });

    var _strgridpumjnspembayaran = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_jenis_bayar', 'nm_pembayaran', 'is_validasi_card'],
            root: 'data',
            totalproperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("pembelian_pelunasan_hutang/get_all_jenis_pembayaran") ?>',
            method: 'POST'
        }),
        listeners: {
            loadexception: function(event, options, response, error) {
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg === 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    var _gridpumjnspembayaran = new Ext.grid.GridPanel({
        store: _strgridpumjnspembayaran,
        stripeRows: true,
        frame: true,
        border: true,
        columns: [{
                header: 'Kode',
                dataIndex: 'kd_jenis_bayar',
                width: 70,
                sortable: true
            }, {
                header: 'Jenis Pembayaran',
                dataIndex: 'nm_pembayaran',
                width: 200,
                sortable: true
            }, {
                header: 'Jenis Pembayaran',
                dataIndex: 'is_validasi_card',
                width: 200,
                sortable: true,
                hidden: true
            }],
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('epum_kd_jenis_bayar').setValue(sel[0].get('kd_jenis_bayar'));
                    Ext.getCmp('epum_nama_pembayaran').setValue(sel[0].get('nm_pembayaran'));
                   // Ext.getCmp('epph_is_validasi_card').setValue(sel[0].get('is_validasi_card'));

                    menupumjnspembayaran.hide();
                }
            }
        }
    });

    var menupumjnspembayaran = new Ext.menu.Menu();
    menupumjnspembayaran.add(new Ext.Panel({
        title: 'Pilih Jenis Pembayaran',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 300,
        height: 250,
        closeAction: 'hide',
        plain: true,
        items: [_gridpumjnspembayaran],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menupumjnspembayaran.hide();
                }
            }]
    }));

    Ext.ux.TwinComboppojnspembayaran = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            //load store grid
            _strgridpumjnspembayaran.load();
            menupumjnspembayaran.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });


    var strjenispembayaranUangMuka = new Ext.data.Store({
        autoSave: false,
        reader: new Ext.data.JsonReader({
            fields: [
                {name: 'kd_jenis_bayar', type: 'text'},
                {name: 'nm_pembayaran', type: 'text'},
                {name: 'rp_bayar', type: 'int'},
                {name: 'nomor_ref', type: 'text'},
                {name: 'nomor_bank', type: 'text'},
                {name: 'tgl_jth_tempo', type: 'text'},
                {name: 'is_validasi_card', type: 'int'}
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        writer: new Ext.data.JsonWriter(
                {
                    encode: true,
                    writeAllFields: true
                })
    });

    strjenispembayaranUangMuka.on('update', function() {
        var total_bayar = 0;
        var total_uang_muka = Ext.getCmp('pum_rp_total_uang_muka').getValue();

        strjenispembayaranUangMuka.each(function(node) {
            total_bayar += parseInt(node.data.rp_bayar);
        });

        total_bayar = +total_bayar || 0;
        Ext.getCmp('pum_rp_total_bayar').setValue(total_bayar);
        Ext.getCmp('pum_rp_selisih').setValue(total_bayar - total_uang_muka);

    });

    strjenispembayaranUangMuka.on('remove', function() {
        var total_bayar = 0;
        var total_uang_muka = Ext.getCmp('pum_rp_total_uang_muka').getValue();

        strjenispembayaranUangMuka.each(function(node) {
            total_bayar += parseInt(node.data.rp_bayar);
        });

        total_bayar = +total_bayar || 0;
        Ext.getCmp('pum_rp_total_bayar').setValue(total_bayar);
        Ext.getCmp('pum_rp_selisih').setValue(total_bayar - total_uang_muka);

    });

    var editorjenispembayaranUangMuka = new Ext.ux.grid.RowEditor({
        saveText: 'Update'
    });

    var gridjenispembayaranUangMuka = new Ext.grid.GridPanel({
        id: 'idgridjenispembayaranUangMuka',
        store: strjenispembayaranUangMuka,
        stripeRows: true,
        height: 200,
        border: true,
        frame: true,
        plugins: [editorjenispembayaranUangMuka],
        tbar: [{
                icon: BASE_ICONS + 'add.png',
                text: 'Add Pembayaran',
                handler: function() {
                    if (Ext.getCmp('pum_rp_total_uang_muka').getValue() === 0) {
                        Ext.Msg.show({
                            title: 'Error',
                            msg: 'Total masih kosong!',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK,
                        });
                        return;
                    }
                    var rowpphjenispembayaran = new gridjenispembayaranUangMuka.store.recordType({
                        kd_jenis_bayar: '',
                        rp_bayar: '',
                        nomor_bank: '',
                        nomor_ref: '',
                        tgl_jth_tempo: ''
                    });
                    editorjenispembayaranUangMuka.stopEditing();
                    strjenispembayaranUangMuka.insert(0, rowpphjenispembayaran);
                    gridjenispembayaranUangMuka.getView().refresh();
                    gridjenispembayaranUangMuka.getSelectionModel().selectRow(0);
                    editorjenispembayaranUangMuka.startEditing(0);
                }
            }, {
                ref: '../removeBtn',
                icon: BASE_ICONS + 'delete.gif',
                text: 'Remove',
                disabled: true,
                handler: function() {

                    editorjenispembayaranUangMuka.stopEditing();
                    var s = gridjenispembayaranUangMuka.getSelectionModel().getSelections();
                    for (var i = 0, r; r = s[i]; i++) {
                        strjenispembayaranUangMuka.remove(r);
                    }

                }
            }],
        columns: [{
                header: 'Kode',
                dataIndex: 'kd_jenis_bayar',
                width: 100,
                editor: new Ext.ux.TwinComboppojnspembayaran({
                    id: 'epum_kd_jenis_bayar',
                    store: _strcbpumjnspembayaran,
                    mode: 'local',
                    valueField: 'kd_jenis_bayar',
                    displayField: 'kd_jenis_bayar',
                    typeAhead: true,
                    triggerAction: 'all',
                    editable: false,
                    hiddenName: 'kd_jenis_bayar',
                    emptyText: 'Pilih Jenis Pembayaran'

                })
            }, {
                header: 'Jenis Pembayaran',
                dataIndex: 'nm_pembayaran',
                width: 200,
                sortable: true,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'epum_nama_pembayaran',
                    fieldClass: 'readonly-input'
                })
            }, {
                xtype: 'numbercolumn',
                header: 'Jumlah Bayar',
                dataIndex: 'rp_bayar',
                width: 110,
                format: '0,0',
                editor: {
                    xtype: 'numberfield',
                    id: 'epum_rp_bayar_jns',
                    selectOnFocus: true
                }
            }, {
                header: 'Nama Bank',
                dataIndex: 'nomor_bank',
                width: 100,
                editor: new Ext.form.TextField({
                    id: 'epum_nomor_bank'
                })
            }, {
                header: 'No Ref',
                dataIndex: 'nomor_ref',
                width: 100,
                editor: new Ext.form.TextField({
                    id: 'epum_nomor_ref'
                })
            }, {
                xtype: 'datecolumn',
                header: 'Tgl Jatuh Tempo',
                dataIndex: 'tgl_jth_tempo',
                format: 'd/m/Y',
                width: 120,
                editor: new Ext.form.DateField({
                    id: 'epum_tgl_jth_tempo',
                    format: 'd/m/Y'
                })
            }
        ]
    });

    gridjenispembayaranUangMuka.getSelectionModel().on('selectionchange', function(sm) {
        gridjenispembayaranUangMuka.removeBtn.setDisabled(sm.getCount() < 1);
    });
 //End Grid Jenis pembayaran
// Form Panel     
var pembayaran_uang_muka = new Ext.FormPanel({
        id: 'pembayaran_uang_muka',
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
                items: [headerpembayaran_uang_muka]
            },
            gridpembayaranUangMuka,
            {
                layout: 'column',
                border: false,
                items: [{
                        columnWidth: .7,
                        style: 'margin:6px 3px 0 0;',
                        layout: 'fit',
                        items: [
                            gridjenispembayaranUangMuka
                        ]
                    }, {
                        columnWidth: .3,
                        layout: 'form',
                        style: 'margin:6px 0 0 0;',
                        border: false,
                        labelWidth: 110,
                        defaults: {labelSeparator: ''},
                        items: [
                            {
                                xtype: 'fieldset',
                                autoHeight: true,
                                items: [
                                    {
                                        xtype: 'numericfield',
                                        currencySymbol: '',
                                        fieldLabel: 'Total',
                                        name: 'rp_uang_muka',
                                        readOnly: true,
                                        id: 'pum_rp_total_uang_muka',
                                        anchor: '90%',
                                        fieldClass: 'readonly-input number',
                                        value: '0'
                                    },
                                    {
                                        xtype: 'numericfield',
                                        currencySymbol: '',
                                        fieldLabel: 'Total Bayar',
                                        name: 'rp_total_bayar',
                                        readOnly: true,
                                        id: 'pum_rp_total_bayar',
                                        anchor: '90%',
                                        fieldClass: 'readonly-input number',
                                        value: '0'
                                    },
                                    {
                                        xtype: 'numericfield',
                                        currencySymbol: '',
                                        fieldLabel: 'Selesih',
                                        name: 'rp_selisih',
                                        readOnly: true,
                                        id: 'pum_rp_selisih',
                                        anchor: '90%',
                                        fieldClass: 'readonly-input number',
                                        value: '0'
                                    }
                                ]
                            }
                        ]
                    }]
            }

        ],
        buttons: [{
                text: 'Save',
                formBind: true,
                handler: function() {

                    if (Ext.getCmp('pum_rp_total_uang_muka').getValue() === 0) {
                        Ext.Msg.show({
                            title: 'Error',
                            msg: 'Total Bayar Masih Kosong!',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK
                        });
                        return;
                    }
                    if (Ext.getCmp('pum_rp_selisih').getValue() !== 0) {
                        Ext.Msg.show({
                            title: 'Error',
                            msg: 'Selisih Harus 0 (NOL)!',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK
                        });
                        return;
                    }
                    var nama_pembayaran = "";
                    var no_bank = "";
                    var no_ref = "";
                    var tgl_jth_tempo = "";
                    
                    var is_true = true;
                    var is_card = true;
                    strjenispembayaranUangMuka.each(function(node) {
                        nama_pembayaran = node.data.nm_pembayaran;
                        no_bank = node.data.nomor_bank;
                        no_ref = node.data.nomor_ref;
                        tgl_jth_tempo = node.data.tgl_jth_tempo;
                        console.log(nama_pembayaran);
                        console.log(no_bank.length);
                        if (nama_pembayaran === 'CEK' || nama_pembayaran === 'GIRO' ){
                            if (no_bank.length <= 0 || no_ref.length <= 0 || tgl_jth_tempo.length <= 0 ){
                                is_true = false;
                            }
                         }
                        if ( (nama_pembayaran.indexOf('DEBIT') < 0 || nama_pembayaran.indexOf('KREDIT') < 0) && no_ref.length<=0 ) {
                            is_card = false;
                        }
                    });
                    console.log(is_true);
                    console.log(is_card);
                    if(!is_true){
                        Ext.Msg.show({
                            title: 'Error',
                            msg: 'Pembayaran Cek atau Giro,No Bank,Nomer Warkat dan Tanggal Jatuh Tempo Harus Diisi !!',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK          
                        });
                        Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');
                        return;
                    }
                    if(is_card){
                        Ext.Msg.show({
                            title: 'Error',
                            msg: 'Pembayaran Dengan Kartu Debit/Kredit, Nomor Warkat Harus Diisi !!',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK
                        });
                        Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');
                        return;
                    }
                    
                    var detailpembayaranuangmuka = new Array();
                    var jumlah = 0;
                    var rp_sisa_invoice = 0;
                    var detailbayarpembayaranuangmuka = new Array();
                    strpembayaranUangMuka.each(function(node) {
                        detailpembayaranuangmuka.push(node.data);
                       
                    });
                    strjenispembayaranUangMuka.each(function(node) {
                        detailbayarpembayaranuangmuka.push(node.data);
                        });

                    
                    Ext.getCmp('pembayaran_uang_muka').getForm().submit({
                        url: '<?= site_url("pembayaran_uang_muka/update_row") ?>',
                        scope: this,
                        params: {
                            detail: Ext.util.JSON.encode(detailpembayaranuangmuka),
                            detailbayar: Ext.util.JSON.encode(detailbayarpembayaranuangmuka),
                            _total_uang_muka: Ext.getCmp('pum_rp_total_uang_muka').getValue(),
                            _total_bayar: Ext.getCmp('pum_rp_total_bayar').getValue(),
                            _selisih: Ext.getCmp('pum_rp_selisih').getValue()
                        },
                        waitMsg: 'Saving Data...',
                        success: function(form, action) {
                            var r = Ext.util.JSON.decode(action.response.responseText);
                            Ext.Msg.show({
                                title: 'Success',
                                msg: 'Form submitted successfully',
                                modal: true,
                                icon: Ext.Msg.INFO,
                                buttons: Ext.Msg.OK,
                                fn: function(btn) {
                                    if (btn === 'ok') {
                                        winpembayaranuangmukaprint.show();
                                        Ext.getDom('pembayaranuangmukaprint').src = r.printUrl;
                                    }
                                }
                            });

                            clearpembayaranuangmuka();
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
                    clearpembayaranuangmuka();
                }
           }
           ]
    });
    var winpembayaranuangmukaprint = new Ext.Window({
        id: 'id_winpembayaranuangmukaprint',
        title: 'Print Pembayaran Uang Muka',
        closeAction: 'hide',
        width: 900,
        height: 450,
        layout: 'fit',
        border: false,
        html:'<iframe style="width:100%;height:100%;" id="pembayaranuangmukaprint" src=""></iframe>'
    });
    function clearpembayaranuangmuka() {
        Ext.getCmp('pembayaran_uang_muka').getForm().reset();
        Ext.getCmp('pembayaran_uang_muka').getForm().load({
            url: '<?= site_url("pembayaran_uang_muka/get_form") ?>',
            failure: function(form, action) {
                var de = Ext.util.JSON.decode(action.response.responseText);
                Ext.Msg.show({
                    title: 'Error',
                    msg: de.errMsg,
                    modal: true,
                    icon: Ext.Msg.ERROR,
                    buttons: Ext.Msg.OK,
                    fn: function(btn) {
                        if (btn === 'ok' && de.errMsg === 'Session Expired') {
                            window.location = '<?= site_url("auth/login") ?>';
                        }
                    }
                });
            }
        });
        strpembayaranUangMuka.removeAll();
        strjenispembayaranUangMuka.removeAll();
    }
</script>
