<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">
    var strcbpbposuplier = new Ext.data.ArrayStore({
        fields: ['kd_supplier'],
        data: []
    });

    var strgridpbposuplier = new Ext.data.Store({
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

    strgridpbposuplier.on('load', function() {
        Ext.getCmp('id_searchgridpbposuplier').focus();
    });

    var searchgridpbposuplier = new Ext.app.SearchField({
        store: strgridpbposuplier,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgridpbposuplier'
    });


    var gridpbposuplier = new Ext.grid.GridPanel({
        store: strgridpbposuplier,
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
            items: [searchgridpbposuplier]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridpbposuplier,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('id_cbpbposuplier').setValue(sel[0].get('kd_supplier'));
                    Ext.getCmp('pbpo_nama_supplier').setValue(sel[0].get('nama_supplier'));

                    menupbposuplier.hide();
                }
                strpembayaranPO.removeAll();
                //strjenispembayaranPO.removeAll();
            }
        }
    });

    var menupbposuplier = new Ext.menu.Menu();
    menupbposuplier.add(new Ext.Panel({
        title: 'Pilih Supplier',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridpbposuplier],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menupbposuplier.hide();
                }
            }]
    }));

    Ext.ux.TwinCombopbpoSuplier = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            //load store grid
            strgridpbposuplier.load();
            menupbposuplier.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    menupbposuplier.on('hide', function() {
        var sf = Ext.getCmp('id_searchgridpbposuplier').getValue();
        if (sf !== '') {
            Ext.getCmp('id_searchgridpbposuplier').setValue('');
            searchgridpbposuplier.onTrigger2Click();
        }
    });

    var cbpbposuplier = new Ext.ux.TwinCombopbpoSuplier({
        fieldLabel: 'Supplier <span class="asterix">*</span>',
        id: 'id_cbpbposuplier',
        store: strcbpbposuplier,
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

    var headerpembayaranPO = {
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
                        id: 'pbpo_no_bukti',
                        maxLength: 255,
                        anchor: '90%',
                        value: ''
                    },
                    cbpbposuplier,
                    {
                        xtype: 'textfield',
                        fieldLabel: 'Keterangan',
                        name: 'keterangan',
                        id: 'pbpo_keterangan',
                        anchor: '90%'
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
                        id: 'pbpo_tanggal',
                        anchor: '90%',
                        value: '',
                        maxValue: (new Date()).clearTime()
                    }, {
                        xtype: 'textfield',
                        fieldLabel: 'Nama Supplier',
                        name: 'nama_supplier',
                        fieldClass: 'readonly-input',
                        readOnly: true,
                        id: 'pbpo_nama_supplier',
                        anchor: '90%',
                        value: ''
                    } ]
            }
        ]
    };

    var strcbpponoPO = new Ext.data.ArrayStore({
        fields: ['no_po'],
        data: []
    });

    var strgridpponoPO = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['no_po', 'tanggal_po', 'rp_jumlah_po', 'rp_ppn_po', 'rp_diskon_po', 'rp_total_po','rp_pembayaran_po'],
            root: 'data',
            totalproperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("pembayaran_po/get_no_po_bysupplier") ?>',
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

    var gridpponoPO = new Ext.grid.GridPanel({
        store: strgridpponoPO,
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
                dataIndex: 'rp_pembayaran_po',
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
                    Ext.getCmp('eppo_no_po').setValue(sel[0].get('no_po'));
                    Ext.getCmp('eppo_tgl_po').setValue(sel[0].get('tanggal_po'));
                    Ext.getCmp('eppo_rp_jumlah').setValue(sel[0].get('rp_jumlah_po'));
                    Ext.getCmp('eppo_rp_ppn').setValue(sel[0].get('rp_ppn_po'));
                    Ext.getCmp('eppo_rp_diskon').setValue(sel[0].get('rp_diskon_po'));
                    Ext.getCmp('eppo_rp_total').setValue(sel[0].get('rp_total_po'));
                    
                    var diskon = sel[0].get('rp_diskon_po');
                    var total = sel[0].get('rp_total_po');
                    var rp_bayar = parseInt(total) - parseInt(diskon);
                    Ext.getCmp('eppo_rp_pembayaran').setValue(rp_bayar);
                    
                    var total_bayar = 0;
                    strpembayaranPO.each(function(node) {
                        total_bayar += parseInt(node.data.rp_pembayaran_po);
                    });
                    Ext.getCmp('ppo_rp_total_bayar').setValue(total_bayar);
                    
                    var _ada = false;
                    strpembayaranPO.each(function(record){
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
                                                Ext.getCmp('eppo_no_po').reset();
                                            }
                                        }                            
                                    });
                                    Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');
                                    Ext.getCmp('eppo_no_po').focus();	
                                    return;
                                }
                    
                    menupponoPO.hide();
                }
            }
        }
    });

    var menupponoPO = new Ext.menu.Menu();
    menupponoPO.add(new Ext.Panel({
        title: 'Pilih No PO',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 450,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridpponoPO],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menupponoPO.hide();
                }
            }]
    }));

    Ext.ux.TwinCombopponoPO = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            //load store grid
            strgridpponoPO.load({
                params: {
                    kd_supplier: Ext.getCmp('id_cbpbposuplier').getValue()
                }
            });
            menupponoPO.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur : false,
        trigger1Class  : 'x-form-clear-trigger',
        trigger2Class  : 'x-form-search-trigger',
        hideTrigger1: true
    });

    var strpembayaranPO = new Ext.data.Store({
        autoSave: false,
        reader: new Ext.data.JsonReader({
            fields: [
                {name: 'no_po', allowBlank: false, type: 'text'},
                {name: 'tanggal_po', allowBlank: false, type: 'text'},
                {name: 'rp_jumlah_po', allowBlank: false, type: 'text'},
                {name: 'rp_ppn_po', allowBlank: false, type: 'int'},
                {name: 'rp_diskon_po', allowBlank: false, type: 'int'},
                {name: 'rp_total_po', allowBlank: false, type: 'text'},
                {name: 'rp_pembayaran_po', allowBlank: false, type: 'int'}
                
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

    strpembayaranPO.on('update', function() {
        var total = 0;
        var total_potongan = 0;
        var total_dibayar = 0;
        var total_bayar = 0;//Ext.getCmp('pph_rp_total').getValue();
        strpembayaranPO.each(function(node) {
            total += parseInt(node.data.rp_pembayaran_po);
            //total_bayar += parseInt(node.data.rp_bayar);
            //total_potongan += parseInt(node.data.rp_diskon);
            //total_dibayar += parseInt(node.data.rp_dibayar);
            //selisih += parseInt(node.data.rp_sisa_invoice);
        });

        strjenispembayaranPO.each(function(node) {
            total_bayar += parseInt(node.data.rp_bayar);
        });


        Ext.getCmp('ppo_rp_total_bayar').setValue(total);
        //Ext.getCmp('ppo_rp_jumlah_bayar').setValue(total_bayar)

        //Ext.getCmp('ppo_rp_total_bayar').setValue(total_invoice);
        //Ext.getCmp('pph_rp_total').setValue(total_bayar);
        //Ext.getCmp('pph_rp_total_potongan').setValue(total_potongan);
        //Ext.getCmp('pph_rp_total_dibayar').setValue(total_dibayar);
        //Ext.getCmp('pph_rp_selisih').setValue(total_bayar - total_dibayar);
    });



    var editorpembayaranPO = new Ext.ux.grid.RowEditor({
        saveText: 'Update'
    });


    var gridpembayaranPO = new Ext.grid.GridPanel({
        store: strpembayaranPO,
        stripeRows: true,
        height: 200,
        frame: true,
        border: true,
        tbar: [{
                icon: BASE_ICONS + 'add.png',
                text: 'Add PO',
                handler: function() {

                    if (Ext.getCmp('id_cbpbposuplier').getValue() === '') {
                        Ext.Msg.show({
                            title: 'Error',
                            msg: 'Silahkan pilih supplier terlebih dulu',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK
                        });
                        return;
                    }

                    var rowpembayaran_po = new gridpembayaranPO.store.recordType({
                        no_invoice: '',
                        rp_total: '',
                        rp_bayar: '',
                        rp_diskon: ''
                    });
                    editorpembayaranPO.stopEditing();
                    strpembayaranPO.insert(0, rowpembayaran_po);
                    gridpembayaranPO.getView().refresh();
                    gridpembayaranPO.getSelectionModel().selectRow(0);
                    editorpembayaranPO.startEditing(0);
                }
            }, {
                ref: '../removeBtn',
                icon: BASE_ICONS + 'delete.gif',
                text: 'Remove',
                disabled: true,
                handler: function() {

                    editorpembayaranPO.stopEditing();
                    var s = gridpembayaranPO.getSelectionModel().getSelections();
                    for (var i = 0, r; r = s[i]; i++) {
                        strpembayaranPO.remove(r);
                    }
                    var jumlah = 0;

                    strpembayaranPO.each(function(node) {
                        jumlah += parseInt(node.data.rp_total);
                    });

                    Ext.getCmp('ppo_rp_total_bayar').setValue(jumlah);
                }
            }],
        plugins: [editorpembayaranPO],
        columns: [{
                header: 'No PO',
                dataIndex: 'no_po',
                width: 150,
                sortable: true,
                editor: new Ext.ux.TwinCombopponoPO({
                    id: 'eppo_no_po',
                    store: strcbpponoPO,
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
                    id: 'eppo_tgl_po',
                    fieldClass: 'readonly-input number'
                })
            }, {
                header: 'Rp Jumlah',
                dataIndex: 'rp_jumlah_po',
                width: 100,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'eppo_rp_jumlah',
                    fieldClass: 'readonly-input number'
                })
            }, {
                xtype: 'numbercolumn',
                header: 'Rp PPN PO',
                dataIndex: 'rp_ppn_po',
                width: 150,
                align: 'right',
                sortable: true,
                format: '0,0',
                editor: {
                    xtype: 'numberfield',
                    id: 'eppo_rp_ppn',
                    fieldClass: 'readonly-input number',
                    readOnly: true
                }
            }, {
                xtype: 'numbercolumn',
                header: 'Rp Diskon PO',
                dataIndex: 'rp_diskon_po',
                width: 110,
                sortable: true,
                align: 'right',
                format: '0,0',
                editor: {
                    xtype: 'numberfield',
                    id: 'eppo_rp_diskon',
                    fieldClass: 'readonly-input number',
                    readOnly: true
                    
                }
            }, {
                xtype: 'numbercolumn',
                header: 'Rp Total',
                dataIndex: 'rp_total_po',
                width: 100,
                sortable: true,
                align: 'right',
                format: '0,0',
                editor: {
                    xtype: 'numberfield',
                    id: 'eppo_rp_total',
                    fieldClass: 'readonly-input number',
                    readOnly: true
                   
                }
            }, {
                xtype: 'numbercolumn',
                header: 'Rp Pembayaran',
                dataIndex: 'rp_pembayaran_po',
                width: 100,
                sortable: true,
                align: 'right',
                format: '0,0',
                editor: {
                    xtype: 'numberfield',
                    id: 'eppo_rp_pembayaran'
                    
                }
            }]
    });

    gridpembayaranPO.getSelectionModel().on('selectionchange', function(sm) {
        gridpembayaranPO.removeBtn.setDisabled(sm.getCount() < 1);
    });

// Twin Jenis Pembayaran
    var _strcbppojnspembayaran = new Ext.data.ArrayStore({
        fields: ['kd_jenis_bayar'],
        data: []
    });

    var _strgridppojnspembayaran = new Ext.data.Store({
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

    var _gridppojnspembayaran = new Ext.grid.GridPanel({
        store: _strgridppojnspembayaran,
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
                    Ext.getCmp('eppo_kd_jenis_bayar').setValue(sel[0].get('kd_jenis_bayar'));
                    Ext.getCmp('eppo_nama_pembayaran').setValue(sel[0].get('nm_pembayaran'));
                   // Ext.getCmp('epph_is_validasi_card').setValue(sel[0].get('is_validasi_card'));

                    menuppojnspembayaran.hide();
                }
            }
        }
    });

    var menuppojnspembayaran = new Ext.menu.Menu();
    menuppojnspembayaran.add(new Ext.Panel({
        title: 'Pilih Jenis Pembayaran',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 300,
        height: 250,
        closeAction: 'hide',
        plain: true,
        items: [_gridppojnspembayaran],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menuppojnspembayaran.hide();
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
            _strgridppojnspembayaran.load();
            menuppojnspembayaran.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });


    var strjenispembayaranPO = new Ext.data.Store({
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

    strjenispembayaranPO.on('update', function() {
        var total_bayar = 0;
        var total_dibayar = Ext.getCmp('pph_rp_total_dibayar').getValue();

        strjenispembayaranPO.each(function(node) {
            total_bayar += parseInt(node.data.rp_bayar);
        });

        total_bayar = +total_bayar || 0;
        Ext.getCmp('pph_rp_total').setValue(total_bayar);
        Ext.getCmp('pph_rp_selisih').setValue(total_bayar - total_dibayar);

    });

    strjenispembayaranPO.on('remove', function() {
        var total_bayar = 0;
        var total_dibayar = Ext.getCmp('pph_rp_total_dibayar').getValue();

        strjenispembayaranPO.each(function(node) {
            total_bayar += parseInt(node.data.rp_bayar);
        });

        total_bayar = +total_bayar || 0;
        Ext.getCmp('pph_rp_total').setValue(total_bayar);
        Ext.getCmp('pph_rp_selisih').setValue(total_bayar - total_dibayar);

    });

    var editorjenispembayaranPO = new Ext.ux.grid.RowEditor({
        saveText: 'Update'
    });

    var gridjenispembayaranPO = new Ext.grid.GridPanel({
        id: 'idgridjenispembayaranPO',
        store: strjenispembayaranPO,
        stripeRows: true,
        height: 200,
        border: true,
        frame: true,
        plugins: [editorjenispembayaranPO],
        tbar: [{
                icon: BASE_ICONS + 'add.png',
                text: 'Add Pembayaran',
                handler: function() {
                    if (Ext.getCmp('ppo_rp_total_bayar').getValue() === 0) {
                        Ext.Msg.show({
                            title: 'Error',
                            msg: 'Total masih kosong!',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK,
                        });
                        return;
                    }
                    var rowpphjenispembayaran = new gridjenispembayaranPO.store.recordType({
                        kd_jenis_bayar: '',
                        rp_bayar: '',
                        nomor_bank: '',
                        nomor_ref: '',
                        tgl_jth_tempo: ''
                    });
                    editorjenispembayaranPO.stopEditing();
                    strjenispembayaranPO.insert(0, rowpphjenispembayaran);
                    gridjenispembayaranPO.getView().refresh();
                    gridjenispembayaranPO.getSelectionModel().selectRow(0);
                    editorjenispembayaranPO.startEditing(0);
                }
            }, {
                ref: '../removeBtn',
                icon: BASE_ICONS + 'delete.gif',
                text: 'Remove',
                disabled: true,
                handler: function() {

                    editorjenispembayaranPO.stopEditing();
                    var s = gridjenispembayaranPO.getSelectionModel().getSelections();
                    for (var i = 0, r; r = s[i]; i++) {
                        strjenispembayaranPO.remove(r);
                    }

                }
            }],
        columns: [{
                header: 'Kode',
                dataIndex: 'kd_jenis_bayar',
                width: 100,
                editor: new Ext.ux.TwinComboppojnspembayaran({
                    id: 'eppo_kd_jenis_bayar',
                    store: _strcbppojnspembayaran,
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
                    id: 'eppo_nama_pembayaran',
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
                    id: 'eppo_rp_bayar_jns',
                    selectOnFocus: true
                }
            }, {
                header: 'No Bank',
                dataIndex: 'nomor_bank',
                width: 100,
                editor: new Ext.form.TextField({
                    id: 'eppo_nomor_bank'
                })
            }, {
                header: 'No Ref',
                dataIndex: 'nomor_ref',
                width: 100,
                editor: new Ext.form.TextField({
                    id: 'eppo_nomor_ref'
                })
            }, {
                xtype: 'datecolumn',
                header: 'Tgl Jatuh Tempo',
                dataIndex: 'tgl_jth_tempo',
                format: 'd/m/Y',
                width: 120,
                editor: new Ext.form.DateField({
                    id: 'eppo_tgl_jth_tempo',
                    format: 'd/m/Y'
                })
            }, {
                header: 'Validasi Card',
                dataIndex: 'is_validasi_card',
                width: 100,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    fieldClass: 'readonly-input',
                    id: 'eppo_is_validasi_card'
                })
            }
        ]
    });

    gridjenispembayaranPO.getSelectionModel().on('selectionchange', function(sm) {
        gridjenispembayaranPO.removeBtn.setDisabled(sm.getCount() < 1);
    });

    var pembayaranPO = new Ext.FormPanel({
        id: 'pembayaran_po',
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
                items: [headerpembayaranPO]
            },
            gridpembayaranPO,
            {
                layout: 'column',
                border: false,
                items: [{
                        columnWidth: .7,
                        style: 'margin:6px 3px 0 0;',
                        layout: 'fit',
                        items: [
                            gridjenispembayaranPO
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
                                        name: 'rp_total_bayar',
                                        readOnly: true,
                                        id: 'ppo_rp_total_bayar',
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

                    if (Ext.getCmp('ppo_rp_total_bayar').getValue() === 0) {
                        Ext.Msg.show({
                            title: 'Error',
                            msg: 'Total Bayar Masih Kosong!',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK
                        });
                        return;
                    }

                    var detailpembayaranpo = new Array();
                    var jumlah = 0;
                    var rp_sisa_invoice = 0;

                    strpembayaranPO.each(function(node) {
                        detailpembayaranpo.push(node.data);
                        jumlah += parseInt(node.data.rp_pembayaran_po);
                        //rp_sisa_invoice += parseInt(node.data.rp_sisa_invoice);
                    });

                    var detailbayarpembayaranpo = new Array();
                    var jumlah_byr = 0;

                    function isEmpty(str) {
                        return (!str || 0 === str.length);
                    }

                    var nomor_bank = 0;
                    var nomor_ref = 0;
                    var tgl_jth_tempo = 0;

                    strjenispembayaranPO.each(function(node) {
                        detailbayarpembayaranpo.push(node.data);
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

                    // alert(nomor_bank+'-'+nomor_ref+'-'+tgl_jth_tempo);
                    // return;

                    // if((nomor_ref > 0) || (nomor_bank > 0) || (tgl_jth_tempo > 0)){
                    // Ext.Msg.show({
                    // title: 'Error',
                    // msg: 'Pembayaran dengan Cek dan Giro, No Bank, No Ref, dan Tgl Jth Tempo tidak boleh kosong!',	
                    // modal: true,
                    // icon: Ext.Msg.ERROR,
                    // buttons: Ext.Msg.OK,
                    // });
                    // return;
                    // }

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

                    Ext.getCmp('pembayaran_po').getForm().submit({
                        url: '<?= site_url("pembayaran_po/update_row") ?>',
                        scope: this,
                        params: {
                            detail: Ext.util.JSON.encode(detailpembayaranpo),
                            detailbayar: Ext.util.JSON.encode(detailbayarpembayaranpo),
                            _total_bayar: Ext.getCmp('ppo_rp_total_bayar').getValue()
                            
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

                            clearpembayaranpo();
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
                    clearpembayaranpo();
                }
            }]
    });

    pembayaranPO.on('afterrender', function() {
        this.getForm().load({
            url: '<?= site_url("pembayaran_po/get_form") ?>',
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

    function clearpembayaranpo() {
        Ext.getCmp('pembayaran_po').getForm().reset();
        Ext.getCmp('pembayaran_po').getForm().load({
            url: '<?= site_url("pembayaran_po/get_form") ?>',
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
        strpembayaranPO.removeAll();
        strjenispembayaranPO.removeAll();
    }
</script>
