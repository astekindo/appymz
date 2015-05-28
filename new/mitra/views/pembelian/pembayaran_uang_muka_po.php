<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">
    var strcbpumposuplier = new Ext.data.ArrayStore({
        fields: ['kd_supplier'],
        data: []
    });

    var strgridpumposuplier = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_supplier', 'nama_supplier', 'pic', 'alamat'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("pembelian_receive_order/search_supplier") ?>',
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

    strgridpumposuplier.on('load', function() {
        Ext.getCmp('id_searchgridpumposuplier').focus();
    });

    var searchgridpumposuplier = new Ext.app.SearchField({
        store: strgridpumposuplier,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgridpumposuplier'
    });


    var gridpumposuplier = new Ext.grid.GridPanel({
        store: strgridpumposuplier,
        stripeRows: true,
        frame: true,
        border: true,
        columns: [{
                header: 'Kode Supplier',
                dataIndex: 'kd_supplier',
                width: 80,
                sortable: true
            }, {
                header: 'Nama Supplier',
                dataIndex: 'nama_supplier',
                width: 300,
                sortable: true
            }, {
                header: 'PIC',
                dataIndex: 'pic',
                width: 100,
                sortable: true
            }, {
                header: 'Alamat',
                dataIndex: 'alamat',
                width: 200,
                sortable: true
            }],
        tbar: new Ext.Toolbar({
            items: [searchgridpumposuplier]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridpumposuplier,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('id_cbpumposuplier').setValue(sel[0].get('kd_supplier'));
                    Ext.getCmp('pumpo_nama_supplier').setValue(sel[0].get('nama_supplier'));

                    menupumposuplier.hide();
                }
                strpembayaran_uang_muka_po.removeAll();
                //strjenispembayaran_uang_muka_po.removeAll();
            }
        }
    });

    var menupumposuplier = new Ext.menu.Menu();
    menupumposuplier.add(new Ext.Panel({
        title: 'Pilih Supplier',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridpumposuplier],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menupumposuplier.hide();
                }
            }]
    }));

    Ext.ux.TwinCombopumpoSuplier = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            //load store grid
            strgridpumposuplier.load();
            menupumposuplier.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    menupumposuplier.on('hide', function() {
        var sf = Ext.getCmp('id_searchgridpumposuplier').getValue();
        if (sf !== '') {
            Ext.getCmp('id_searchgridpumposuplier').setValue('');
            searchgridpumposuplier.onTrigger2Click();
        }
    });

    var cbpumposuplier = new Ext.ux.TwinCombopumpoSuplier({
        fieldLabel: 'Supplier <span class="asterix">*</span>',
        id: 'id_cbpumposuplier',
        store: strcbpumposuplier,
        mode: 'local',
        valueField: 'kd_supplier',
        displayField: 'kd_supplier',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_supplier',
        emptyText: 'Pilih Supplier'
    });

    var headerpembayaran_uang_muka_po = {
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
                        id: 'pumpo_no_bukti',
                        maxLength: 255,
                        anchor: '90%',
                        value: ''
                    },
                    cbpumposuplier,
                    {
                        xtype: 'textfield',
                        fieldLabel: 'Keterangan',
                        name: 'keterangan',
                        id: 'pumpo_keterangan',
                        anchor: '90%',
                        allowBlank: false,
                    }
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
                        id: 'pumpo_tanggal',
                        anchor: '90%',
                        maxValue: (new Date()).clearTime()
                    }, {
                        xtype: 'textfield',
                        fieldLabel: 'Nama Supplier',
                        name: 'nama_supplier',
                        fieldClass: 'readonly-input',
                        readOnly: true,
                        id: 'pumpo_nama_supplier',
                        anchor: '90%',
                        value: ''
                    } ]
            }
        ]
    };

    var strcbpumponoPO = new Ext.data.ArrayStore({
        fields: ['no_po'],
        data: []
    });

    var strgridpumponoPO = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['no_po', 'tanggal_po', 'rp_jumlah_po', 'rp_ppn_po', 'rp_diskon_po', 'rp_total_po','rp_pembayaran_uang_muka_po'],
            root: 'data',
            totalproperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("pembayaran_uang_muka_po/get_no_po_bysupplier") ?>',
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

    var gridpumponoPO = new Ext.grid.GridPanel({
        store: strgridpumponoPO,
        stripeRows: true,
        frame: true,
        border: true,
        columns: [{
                header: 'No PO',
                dataIndex: 'no_po',
                width: 120,
                sortable: true
            }, {
                header: 'Tanggal PO',
                dataIndex: 'tanggal_po',
                width: 100,
                sortable: true
            }, {
                header: 'Rp Jumlah',
                dataIndex: 'rp_jumlah_po',
                width: 80,
                sortable: true
            }, {
                xtype: 'numbercolumn',
                header: 'Rp PPN',
                dataIndex: 'rp_ppn_po',
                width: 100,
                align: 'right',
                format: '0,0',
                sortable: true
            }, {
                xtype: 'numbercolumn',
                header: 'Rp Diskon',
                dataIndex: 'rp_diskon_po',
                width: 100,
                align: 'right',
                format: '0,0',
                sortable: true
            },{
                xtype: 'numbercolumn',
                header: 'Rp Total',
                dataIndex: 'rp_total_po',
                width: 100,
                align: 'right',
                format: '0,0',
                sortable: true
            },{
                xtype: 'numbercolumn',
                header: 'Rp Pembayaran',
                dataIndex: 'rp_pembayaran_uang_muka_po',
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
                    Ext.getCmp('epumpo_no_po').setValue(sel[0].get('no_po'));
                    Ext.getCmp('epumpo_tgl_po').setValue(sel[0].get('tanggal_po'));
                    Ext.getCmp('epumpo_rp_jumlah').setValue(sel[0].get('rp_jumlah_po'));
                    Ext.getCmp('epumpo_rp_ppn').setValue(sel[0].get('rp_ppn_po'));
                    Ext.getCmp('epumpo_rp_diskon').setValue(sel[0].get('rp_diskon_po'));
                    Ext.getCmp('epumpo_rp_total').setValue(sel[0].get('rp_total_po'));
                    Ext.getCmp('epumpo_rp_total_uang_muka').setValue(sel[0].get('rp_pembayaran_uang_muka_po'));
                    
                    var diskon = sel[0].get('rp_diskon_po');
                    var total = sel[0].get('rp_total_po');
                    var uang_muka = sel[0].get('rp_pembayaran_uang_muka_po');
                    var rp_bayar = parseInt(total) - parseInt(diskon) - parseInt(uang_muka);
                    Ext.getCmp('epumpo_rp_pembayaran').setValue(rp_bayar);
                    
                    var total_bayar = 0;
                    strpembayaran_uang_muka_po.each(function(node) {
                        total_bayar += parseInt(node.data.rp_pembayaran_uang_muka_po);
                    });
                    Ext.getCmp('pumpo_rp_total').setValue(total_bayar);
                    
                    var _ada = false;
                    strpembayaran_uang_muka_po.each(function(record){
                    if(record.get('no_po') === sel[0].get('no_po')){
                             _ada = true;
                         }
                     });
                     
                     if (_ada){
                                    Ext.Msg.show({
                                        title: 'Error',
                                        msg: 'No PO yang sama tidak boleh dipilih lebih dari satu kali',
                                        modal: true,
                                        icon: Ext.Msg.ERROR,
                                        buttons: Ext.Msg.OK,
                                        fn: function(btn){
                                            if (btn === 'ok') {
                                                Ext.getCmp('epumpo_no_po').reset();
                                            }
                                        }                            
                                    });
                                    Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');
                                    Ext.getCmp('epumpo_no_po').focus();	
                                    return;
                                }
                    
                    menupumponoPO.hide();
                }
            }
        }
    });

    var menupumponoPO = new Ext.menu.Menu();
    menupumponoPO.add(new Ext.Panel({
        title: 'Pilih No PO',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 450,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridpumponoPO],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menupumponoPO.hide();
                }
            }]
    }));

    Ext.ux.TwinCombopumponoPO = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            //load store grid
            strgridpumponoPO.load({
                params: {
                    kd_supplier: Ext.getCmp('id_cbpumposuplier').getValue()
                }
            });
            menupumponoPO.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur : false,
        trigger1Class  : 'x-form-clear-trigger',
        trigger2Class  : 'x-form-search-trigger',
        hideTrigger1: true
    });

    var strpembayaran_uang_muka_po = new Ext.data.Store({
        autoSave: false,
        reader: new Ext.data.JsonReader({
            fields: [
                {name: 'no_po', allowBlank: false, type: 'text'},
                {name: 'tanggal_po', allowBlank: false, type: 'text'},
                {name: 'rp_jumlah_po', allowBlank: false, type: 'text'},
                {name: 'rp_ppn_po', allowBlank: false, type: 'int'},
                {name: 'rp_diskon_po', allowBlank: false, type: 'int'},
                {name: 'rp_total_po', allowBlank: false, type: 'text'},
                {name: 'rp_pembayaran_uang_muka_po', allowBlank: false, type: 'int'},
                {name: 'rp_total_uang_muka', allawBlank: false, type: 'int'}
                
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

    strpembayaran_uang_muka_po.on('update', function() {
        var total = 0;
        var total_potongan = 0;
        var total_dibayar = 0;
        var total_bayar = 0;
        var total_bayar = Ext.getCmp('pumpo_rp_total_bayar').getValue();
        strpembayaran_uang_muka_po.each(function(node) {
            total += parseInt(node.data.rp_pembayaran_uang_muka_po);
            
        });
        Ext.getCmp('pumpo_rp_total').setValue(total);
        Ext.getCmp('pumpo_rp_selisih').setValue(total - total_bayar);
      
    });
     strpembayaran_uang_muka_po.on('remove', function() {
        var total = 0;
        var total_potongan = 0;
        var total_dibayar = 0;
        var total_bayar = 0;
        var total_bayar = Ext.getCmp('pumpo_rp_total_bayar').getValue();
        strpembayaran_uang_muka_po.each(function(node) {
            total += parseInt(node.data.rp_pembayaran_uang_muka_po);
            
        });
        Ext.getCmp('pumpo_rp_total').setValue(total);
        Ext.getCmp('pumpo_rp_selisih').setValue(total - total_bayar);
              
    });

   
    var editorpembayaran_uang_muka_po = new Ext.ux.grid.RowEditor({
        saveText: 'Update'
    });


    var gridpembayaran_uang_muka_po = new Ext.grid.GridPanel({
        store: strpembayaran_uang_muka_po,
        stripeRows: true,
        height: 200,
        frame: true,
        border: true,
        tbar: [{
                icon: BASE_ICONS + 'add.png',
                text: 'Add PO',
                handler: function() {

                    if (Ext.getCmp('id_cbpumposuplier').getValue() === '') {
                        Ext.Msg.show({
                            title: 'Error',
                            msg: 'Silahkan pilih supplier terlebih dulu',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK
                        });
                        return;
                    }

                    var rowpembayaran_uang_muka_po = new gridpembayaran_uang_muka_po.store.recordType({
                        no_invoice: '',
                        rp_total: '',
                        rp_bayar: '',
                        rp_diskon: ''
                    });
                    editorpembayaran_uang_muka_po.stopEditing();
                    strpembayaran_uang_muka_po.insert(0, rowpembayaran_uang_muka_po);
                    gridpembayaran_uang_muka_po.getView().refresh();
                    gridpembayaran_uang_muka_po.getSelectionModel().selectRow(0);
                    editorpembayaran_uang_muka_po.startEditing(0);
                }
            }, {
                ref: '../removeBtn',
                icon: BASE_ICONS + 'delete.gif',
                text: 'Remove',
                disabled: true,
                handler: function() {

                    editorpembayaran_uang_muka_po.stopEditing();
                    var s = gridpembayaran_uang_muka_po.getSelectionModel().getSelections();
                    for (var i = 0, r; r = s[i]; i++) {
                        strpembayaran_uang_muka_po.remove(r);
                    }
                    var jumlah = 0;

                    strpembayaran_uang_muka_po.each(function(node) {
                        jumlah += parseInt(node.data.rp_total);
                    });

                    Ext.getCmp('pumpo_rp_total').setValue(jumlah);
                }
            }],
        plugins: [editorpembayaran_uang_muka_po],
        columns: [{
                header: 'No PO',
                dataIndex: 'no_po',
                width: 150,
                sortable: true,
                editor: new Ext.ux.TwinCombopumponoPO({
                    id: 'epumpo_no_po',
                    store: strcbpumponoPO,
                    mode: 'local',
                    valueField: 'no_po',
                    displayField: 'no_po',
                    typeAhead: true,
                    triggerAction: 'all',
                    editable: false,
                    hiddenName: 'no_po',
                    emptyText: 'Pilih No PO'

                })
            }, {
                header: 'Tanggal PO',
                dataIndex: 'tanggal_po',
                width: 100,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'epumpo_tgl_po',
                    fieldClass: 'readonly-input number'
                })
            }, {
                xtype: 'numbercolumn',
                header: 'Jumlah',
                dataIndex: 'rp_jumlah_po',
                width: 110,
                align: 'right',
                sortable: true,
                format: '0,0',
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'epumpo_rp_jumlah',
                    fieldClass: 'readonly-input number'
                })
            }, {
                xtype: 'numbercolumn',
                header: 'PPN PO',
                dataIndex: 'rp_ppn_po',
                width: 100,
                align: 'right',
                sortable: true,
                format: '0,0',
                editor: {
                    xtype: 'numberfield',
                    id: 'epumpo_rp_ppn',
                    fieldClass: 'readonly-input number',
                    readOnly: true
                }
            }, {
                xtype: 'numbercolumn',
                header: 'Diskon PO',
                dataIndex: 'rp_diskon_po',
                width: 110,
                sortable: true,
                align: 'right',
                format: '0,0',
                editor: {
                    xtype: 'numberfield',
                    id: 'epumpo_rp_diskon',
                    fieldClass: 'readonly-input number',
                    readOnly: true
                    
                }
            }, {
                xtype: 'numbercolumn',
                header: 'Total PO',
                dataIndex: 'rp_total_po',
                width: 100,
                sortable: true,
                align: 'right',
                format: '0,0',
                editor: {
                    xtype: 'numberfield',
                    id: 'epumpo_rp_total',
                    fieldClass: 'readonly-input number',
                    readOnly: true
                   
                }
            },{
                xtype: 'numbercolumn',
                header: 'Total Uang Muka',
                dataIndex: 'rp_total_uang_muka',
                width: 100,
                sortable: true,
                align: 'right',
                format: '0,0',
                editor: {
                    xtype: 'numberfield',
                    id: 'epumpo_rp_total_uang_muka',
                    fieldClass: 'readonly-input number',
                    readOnly: true
                   
                }
            }, {
                xtype: 'numbercolumn',
                header: 'Pembayaran Uang Muka',
                dataIndex: 'rp_pembayaran_uang_muka_po',
                width: 150,
                sortable: true,
                align: 'right',
                format: '0,0',
                editor: {
                    xtype: 'numberfield',
                    id: 'epumpo_rp_pembayaran'
                    
                }
            }]
    });

    gridpembayaran_uang_muka_po.getSelectionModel().on('selectionchange', function(sm) {
        gridpembayaran_uang_muka_po.removeBtn.setDisabled(sm.getCount() < 1);
    });

// Twin Jenis Pembayaran
    var _strcbpumpojnspembayaran = new Ext.data.ArrayStore({
        fields: ['kd_jenis_bayar'],
        data: []
    });

    var _strgridpumpojnspembayaran = new Ext.data.Store({
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

    var _gridpumpojnspembayaran = new Ext.grid.GridPanel({
        store: _strgridpumpojnspembayaran,
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
                    Ext.getCmp('epumpo_kd_jenis_bayar').setValue(sel[0].get('kd_jenis_bayar'));
                    Ext.getCmp('epumpo_nama_pembayaran').setValue(sel[0].get('nm_pembayaran'));
                    Ext.getCmp('epumpo_is_validasi_card').setValue(sel[0].get('is_validasi_card'));

                    menupumpojnspembayaran.hide();
                }
            }
        }
    });

    var menupumpojnspembayaran = new Ext.menu.Menu();
    menupumpojnspembayaran.add(new Ext.Panel({
        title: 'Pilih Jenis Pembayaran',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 300,
        height: 250,
        closeAction: 'hide',
        plain: true,
        items: [_gridpumpojnspembayaran],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menupumpojnspembayaran.hide();
                }
            }]
    }));

    Ext.ux.TwinCombopumpojnspembayaran = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            //load store grid
            _strgridpumpojnspembayaran.load();
            menupumpojnspembayaran.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });


    var strjenispembayaran_uang_muka_po = new Ext.data.Store({
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

     strjenispembayaran_uang_muka_po.on('update', function() {
        var total_bayar = 0;
        var total_uang_muka = Ext.getCmp('pumpo_rp_total').getValue();

        strjenispembayaran_uang_muka_po.each(function(node) {
            total_bayar += parseInt(node.data.rp_bayar);
        });

        Ext.getCmp('pumpo_rp_total_bayar').setValue(total_bayar);
        Ext.getCmp('pumpo_rp_selisih').setValue(total_bayar - total_uang_muka);

    });

    strjenispembayaran_uang_muka_po.on('remove', function() {
        var total_bayar = 0;
        var total_uang_muka = Ext.getCmp('pumpo_rp_total').getValue();

        strjenispembayaran_uang_muka_po.each(function(node) {
            total_bayar += parseInt(node.data.rp_bayar);
        });
        Ext.getCmp('pumpo_rp_total_bayar').setValue(total_bayar);
        Ext.getCmp('pumpo_rp_selisih').setValue(total_bayar - total_uang_muka);

    });

    var editorjenispembayaran_uang_muka_po = new Ext.ux.grid.RowEditor({
        saveText: 'Update'
    });

    var gridjenispembayaran_uang_muka_po = new Ext.grid.GridPanel({
        id: 'idgridjenispembayaran_uang_muka_po',
        store: strjenispembayaran_uang_muka_po,
        stripeRows: true,
        height: 200,
        border: true,
        frame: true,
        plugins: [editorjenispembayaran_uang_muka_po],
        tbar: [{
                icon: BASE_ICONS + 'add.png',
                text: 'Add Pembayaran',
                handler: function() {
                    if (Ext.getCmp('pumpo_rp_total').getValue() === 0) {
                        Ext.Msg.show({
                            title: 'Error',
                            msg: 'Total masih kosong!',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK,
                        });
                        return;
                    }
                    var rowpphjenispembayaran = new gridjenispembayaran_uang_muka_po.store.recordType({
                        kd_jenis_bayar: '',
                        rp_bayar: '',
                        nomor_bank: '',
                        nomor_ref: '',
                        tgl_jth_tempo: ''
                    });
                    editorjenispembayaran_uang_muka_po.stopEditing();
                    strjenispembayaran_uang_muka_po.insert(0, rowpphjenispembayaran);
                    gridjenispembayaran_uang_muka_po.getView().refresh();
                    gridjenispembayaran_uang_muka_po.getSelectionModel().selectRow(0);
                    editorjenispembayaran_uang_muka_po.startEditing(0);
                }
            }, {
                ref: '../removeBtn',
                icon: BASE_ICONS + 'delete.gif',
                text: 'Remove',
                disabled: true,
                handler: function() {

                    editorjenispembayaran_uang_muka_po.stopEditing();
                    var s = gridjenispembayaran_uang_muka_po.getSelectionModel().getSelections();
                    for (var i = 0, r; r = s[i]; i++) {
                        strjenispembayaran_uang_muka_po.remove(r);
                    }

                }
            }],
        columns: [{
                header: 'Kode',
                dataIndex: 'kd_jenis_bayar',
                width: 100,
                editor: new Ext.ux.TwinCombopumpojnspembayaran({
                    id: 'epumpo_kd_jenis_bayar',
                    store: _strcbpumpojnspembayaran,
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
                    id: 'epumpo_nama_pembayaran',
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
                    id: 'epumpo_rp_bayar_jns',
                    selectOnFocus: true
                }
            }, {
                header: 'No Bank',
                dataIndex: 'nomor_bank',
                width: 100,
                editor: new Ext.form.TextField({
                    id: 'epumpo_nomor_bank'
                })
            }, {
                header: 'No Ref',
                dataIndex: 'nomor_ref',
                width: 100,
                editor: new Ext.form.TextField({
                    id: 'epumpo_nomor_ref'
                })
            }, {
                xtype: 'datecolumn',
                header: 'Tgl Jatuh Tempo',
                dataIndex: 'tgl_jth_tempo',
                format: 'd/m/Y',
                width: 120,
                editor: new Ext.form.DateField({
                    id: 'epumpo_tgl_jth_tempo',
                    format: 'd/m/Y'
                })
            }, {
                header: 'Validasi Card',
                dataIndex: 'is_validasi_card',
                width: 100,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    fieldClass: 'readonly-input',
                    id: 'epumpo_is_validasi_card'
                })
            }
        ]
    });

    gridjenispembayaran_uang_muka_po.getSelectionModel().on('selectionchange', function(sm) {
        gridjenispembayaran_uang_muka_po.removeBtn.setDisabled(sm.getCount() < 1);
    });

    var pembayaran_uang_muka_po = new Ext.FormPanel({
        id: 'pembayaran_uang_muka_po',
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
                items: [headerpembayaran_uang_muka_po]
            },
            gridpembayaran_uang_muka_po,
            {
                layout: 'column',
                border: false,
                items: [{
                        columnWidth: .7,
                        style: 'margin:6px 3px 0 0;',
                        layout: 'fit',
                        items: [
                            gridjenispembayaran_uang_muka_po
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
                                        name: 'rp_total',
                                        readOnly: true,
                                        id: 'pumpo_rp_total',
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
                                        id: 'pumpo_rp_total_bayar',
                                        anchor: '90%',
                                        fieldClass: 'readonly-input number',
                                        value: '0'
                                    },
                                    {
                                        xtype: 'numericfield',
                                        currencySymbol: '',
                                        fieldLabel: 'Selisih',
                                        name: 'rp_selisih',
                                        readOnly: true,
                                        id: 'pumpo_rp_selisih',
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

                    if (Ext.getCmp('pumpo_rp_total').getValue() === 0) {
                        Ext.Msg.show({
                            title: 'Error',
                            msg: 'Total Bayar Masih Kosong!',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK
                        });
                        return;
                    }
                     if (Ext.getCmp('pumpo_rp_selisih').getValue() !== 0) {
                        Ext.Msg.show({
                            title: 'Error',
                            msg: 'Selisih Harus 0 (NOL)!',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK
                        });
                        return;
                    }
                    var detailpembayaran_uang_muka_po = new Array();
                    var jumlah = 0;
                    var rp_sisa_invoice = 0;

                    strpembayaran_uang_muka_po.each(function(node) {
                        detailpembayaran_uang_muka_po.push(node.data);
                        jumlah += parseInt(node.data.rp_pembayaran_uang_muka_po);
                        //rp_sisa_invoice += parseInt(node.data.rp_sisa_invoice);
                    });

                    var detailbayarpembayaran_uang_muka_po = new Array();
                    var jumlah_byr = 0;

                    function isEmpty(str) {
                        return (!str || 0 === str.length);
                    }

                    var nomor_bank = 0;
                    var nomor_ref = 0;
                    var tgl_jth_tempo = 0;

                    strjenispembayaran_uang_muka_po.each(function(node) {
                        detailbayarpembayaran_uang_muka_po.push(node.data);
                        if (node.data.is_validasi_card === 1) {
                            if (node.data.nomor_bank === '') {
                                nomor_bank = nomor_bank + 1;
                            } else {
                                nomor_bank = nomor_bank - 1;
                            }

                            if (node.data.nomor_ref === '') {
                                nomor_ref = nomor_ref + 1;
                            } else {
                                nomor_ref = nomor_ref - 1;
                            }

                            if (node.data.tgl_jth_tempo === '') {
                                tgl_jth_tempo = tgl_jth_tempo + 1;
                            } else {
                                tgl_jth_tempo = tgl_jth_tempo - 1;
                            }
                        }
                        jumlah_byr += parseInt(node.data.rp_bayar);
                    });

                    var nama_pembayaran = "";
                    var no_bank = "";
                    var no_ref = "";
                    var tgl_jth_tempo = "";
                    var is_validasi_card = "";
                    
                    var is_true = true;
                    var is_validasi = true;
                    strjenispembayaran_uang_muka_po.each(function(node) {
                        nama_pembayaran = node.data.nm_pembayaran;
                        no_bank = node.data.nomor_bank;
                        no_ref = node.data.nomor_ref;
                        tgl_jth_tempo = node.data.tgl_jth_tempo;
                        is_validasi_card = node.data.is_validasi_card;
                        console.log(nama_pembayaran);
                        console.log(is_validasi_card);
                        console.log(no_bank.length);
                        if (is_validasi_card === '1'){
                            if (no_bank.length <= 0 || no_ref.length <= 0 || tgl_jth_tempo.length <= 0 ){
                                is_validasi = false;
                            }
                        }
                        if (nama_pembayaran === 'CEK' || nama_pembayaran === 'GIRO' ){
                            if (no_bank.length <= 0 || no_ref.length <= 0 || tgl_jth_tempo.length <= 0 ){
                                is_true = false;
                            }
                         }
                    });
                    console.log(is_true);
                    console.log(is_validasi);
                    if(!is_validasi){
                        Ext.Msg.show({
                            title: 'Error',
                            msg: 'Pembayaran Lewat Bank,No Bank,Nomer Warkat dan Tanggal Jatuh Tempo Harus Diisi !!',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK          
                        });
                        Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');
                        return;
                    }
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

                    if (jumlah_byr < jumlah) {
                        Ext.Msg.show({
                            title: 'Error',
                            msg: 'Jumlah yg dibayar tidak boleh lebih besar dari Jumlah Bayar!',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK
                        });
                        return;
                    }

                    /*if (rp_sisa_invoice < 0) {
                        Ext.Msg.show({
                            title: 'Error',
                            msg: 'Sisa invoice tidak boleh lebih kecil dari Nol',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK,
                        });
                        return;
                    }*/

                    Ext.getCmp('pembayaran_uang_muka_po').getForm().submit({
                        url: '<?= site_url("pembayaran_uang_muka_po/update_row") ?>',
                        scope: this,
                        params: {
                            detail: Ext.util.JSON.encode(detailpembayaran_uang_muka_po),
                            detailbayar: Ext.util.JSON.encode(detailbayarpembayaran_uang_muka_po),
                            _total_bayar: Ext.getCmp('pumpo_rp_total').getValue(),
                            _rp_bayar: Ext.getCmp('pumpo_rp_total_bayar').getValue()
                            
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
                                        //winpembeliancreatepononrequestprint.show();
                                        //Ext.getDom('pembeliancreatepononrequestprint').src = r.printUrl;
                                    }
                                }
                            });

                            clearpembayaran_uang_muka_po();
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
                    clearpembayaran_uang_muka_po();
                }
            }]
    });

    pembayaran_uang_muka_po.on('afterrender', function() {
        this.getForm().load({
            //url: '<?= site_url("pembayaran_uang_muka_po/get_form") ?>',
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
    });

    function clearpembayaran_uang_muka_po() {
        Ext.getCmp('pembayaran_uang_muka_po').getForm().reset();
        Ext.getCmp('pembayaran_uang_muka_po').getForm().load({
            url: '<?= site_url("pembayaran_uang_muka_po/get_form") ?>',
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
        strpembayaran_uang_muka_po.removeAll();
        strjenispembayaran_uang_muka_po.removeAll();
    }
</script>
