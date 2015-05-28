<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">
    // TWIN COMBO NO FAKTUR
    var strcbpppnofaktur = new Ext.data.ArrayStore({
        fields: ['no_faktur'],
        data: []
    });

    var strgridpppnofaktur = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['no_so',
                      'no_bukti_pelanggan',
                      'tgl_so',
                      'rp_total',
                      'rp_total_bayar',
                      'sisa_faktur',
                      'rp_kurang_bayar',
                      'rp_grand_total'
                  ],
            root: 'data',
            totalproperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("penjualan_pelunasan_piutang/get_all_faktur") ?>',
            method: 'POST'
        }),
        listeners: {
            loadexception: function(event, options, response, error) {
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });
     strgridpppnofaktur.on('load', function() {
        Ext.getCmp('id_searchnofaktur').focus();
    });

    var searchnofaktur = new Ext.app.SearchField({
        store: strgridpppnofaktur,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchnofaktur'
    });

    var gridpppnofaktur = new Ext.grid.GridPanel({
        store: strgridpppnofaktur,
        stripeRows: true,
        frame: true,
        border: true,
        columns: [{
                header: 'No Faktur',
                dataIndex: 'no_so',
                width: 120,
                sortable: true
            }, {
                header: 'Tgl Faktur',
                dataIndex: 'tgl_so',
                width: 100,
                sortable: true
            }, {
                xtype: 'numbercolumn',
                header: 'Rp Total Struk',
                dataIndex: 'rp_grand_total',
                width: 130,
                align: 'right',
                format: '0,0',
                sortable: true
            }, {
                xtype: 'numbercolumn',
                header: 'Rp Total Bayar',
                dataIndex: 'rp_total_bayar',
                width: 130,
                align: 'right',
                format: '0,0',
                sortable: true
            },{
                xtype: 'numbercolumn',
                header: 'Rp Kurang Bayar',
                dataIndex: 'rp_kurang_bayar',
                width: 130,
                align: 'right',
                format: '0,0',
                sortable: true
            }],
        tbar: new Ext.Toolbar({
            items: [searchnofaktur]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridpppnofaktur,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
//                    gridpenjualanpelunasanpiutang.store.reload({
//                        params: {
//                            no_faktur: sel[0].get('no_so')
//                        }
//                    });

                    Ext.getCmp('eppp_no_faktur').setValue(sel[0].get('no_so'));
                    Ext.getCmp('eppp_rp_grand_total').setValue(sel[0].get('rp_grand_total'));
                    Ext.getCmp('eppp_rp_total_bayar').setValue(sel[0].get('rp_total_bayar'));
                    Ext.getCmp('eppp_rp_kurang_bayar').setValue(sel[0].get('rp_kurang_bayar'));
                    Ext.getCmp('eppp_tgl_so').setValue(sel[0].get('tgl_so'));

                    var rp_bayar = sel[0].get('rp_grand_total') - sel[0].get('rp_total_bayar');
                    Ext.getCmp('eppp_rp_bayar').setValue(rp_bayar);
                    Ext.getCmp('eppp_rp_potongan').setValue(0);
                    Ext.getCmp('eppp_rp_dibayar').setValue(rp_bayar);
                    Ext.getCmp('eppp_sisa_bayar').setValue(0);

                    Ext.getCmp('eppp_rp_bayar').focus();
                    var _ada = false;
                    strpenjualanpelunasanpiutang.each(function(record){
                    if(record.get('no_so') === sel[0].get('no_so')){
                             _ada = true;
                         }
                     });
                    if (_ada){
                                    Ext.Msg.show({
                                        title: 'Error',
                                        msg: 'No Faktur / Struk yang sama tidak boleh dipilih lebih dari satu kali',
                                        modal: true,
                                        icon: Ext.Msg.ERROR,
                                        buttons: Ext.Msg.OK,
                                        fn: function(btn){
                                            if (btn == 'ok') {
                                                Ext.getCmp('eppp_no_faktur').reset();
                                            }
                                        }
                                    });
                                    Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');
                                    Ext.getCmp('eppp_no_faktur').focus();
                                    return;
                                }
                    menupppnofaktur.hide();
                }
            }
        }
    });

    var menupppnofaktur = new Ext.menu.Menu();
    menupppnofaktur.add(new Ext.Panel({
        title: 'Pilih No Faktur',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 450,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridpppnofaktur],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menupppnofaktur.hide();
                }
            }]
    }));

    Ext.ux.TwinCombopppnofaktur = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            //load store grid
            strgridpppnofaktur.load();
            menupppnofaktur.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });


//    var cbppnofaktur = new Ext.ux.TwinCombopppnofaktur({
//        fieldLabel: 'No Faktur <span class="asterix">*</span>',
//        id: 'eppp_no_faktur',
//        store: strcbpppnofaktur,
//        mode: 'local',
//        valueField: 'no_faktur',
//        displayField: 'no_faktur',
//        typeAhead: true,
//        triggerAction: 'all',
//        editable: false,
//        anchor: '90%',
//        hiddenName: 'no_faktur',
//        emptyText: 'Pilih No Faktur'
//    });

    var headerpenjualanpelunasanpiutang = {
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
                        //allowBlank: false,
                        readOnly: true,
                        fieldClass: 'readonly-input',
                        id: 'ppp_no_bukti',
                        maxLength: 255,
                        anchor: '90%',
                        value: ''
                    },
                    //cbppnofaktur,
                    {
                        xtype: 'textfield',
                        fieldLabel: 'Keterangan <span class="asterix">*</span>',
                        allowBlank: false,
                        name: 'keterangan',
                        id: 'ppp_keterangan',
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
                        emptyText: 'Tgl Pelunasan',
                        value: new Date(),
                        id: 'ppp_tanggal',
                        anchor: '90%',
                        maxValue: (new Date()).clearTime()

                    } ]
            }
        ]
    }


    var strpenjualanpelunasanpiutang = new Ext.data.Store({
        autoSave: false,
        reader: new Ext.data.JsonReader({
            fields: [
                {name: 'no_so', allowBlank: false, type: 'text'},
                {name: 'tgl_so', allowBlank: false, type: 'text'},
                {name: 'nm_satuan', allowBlank: false, type: 'text'},
                {name: 'rp_total', allowBlank: false, type: 'int'},
                {name: 'rp_diskon', allowBlank: false, type: 'int'},
                {name: 'rp_bank_charge', allowBlank: false, type: 'int'},
                {name: 'rp_ongkos_kirim', allowBlank: false, type: 'int'},
                {name: 'rp_ongkos_pasang', allowBlank: false, type: 'int'},
                {name: 'rp_total_bayar', allowBlank: false, type: 'int'},
                {name: 'rp_kurang_bayar', allowBlank: false, type: 'int'},
                {name: 'rp_grand_total', allowBlank: false, type: 'int'},
                {name: 'rp_bayar', allowBlank: false, type: 'int'},
                {name: 'rp_potongan', allowBlank: false, type: 'int'},
                {name: 'rp_dibayar', allowBlank: false, type: 'int'},
                {name: 'sisa_bayar', allowBlank: false, type: 'int'}
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("penjualan_pelunasan_piutang/get_rows") ?>',
            method: 'POST'
        }),
        listeners: {
            loadexception: function(event, options, response, error) {
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }

        // writer: new Ext.data.JsonWriter(
        // {
        // encode: true,
        // writeAllFields: true
        // })
    });
    strpenjualanpelunasanpiutang.on('update', function() {
        var total_faktur = 0;
        var total_kurang_bayar = 0;
        var total_dibayar = 0;
        var total_bayar = 0;
        var total_potongan = 0;
         total_bayar = Ext.getCmp('ppp_total_bayar').getValue();


//        var total_kurang_bayar = Ext.getCmp('ppp_total_bayar').getValue();
//        var bayar = Ext.getCmp('ppp_rp_bayar').getValue();
        strpenjualanpelunasanpiutang.each(function(node) {
            total_faktur += parseInt(node.data.rp_grand_total);
            total_kurang_bayar += parseInt(node.data.rp_kurang_bayar);
            total_dibayar += parseInt(node.data.rp_dibayar);
            total_potongan += parseInt(node.data.rp_potongan);
        });
        Ext.getCmp('ppp_rp_total_faktur').setValue(total_faktur);
        Ext.getCmp('ppp_rp_kurang_bayar').setValue(total_kurang_bayar);
         Ext.getCmp('ppp_total_potongan').setValue(total_potongan);
        Ext.getCmp('ppp_rp_bayar').setValue(total_dibayar);
        Ext.getCmp('ppp_rp_selisih').setValue(total_bayar - total_dibayar);

    });



    var editorpenjualanpelunasanpiutang = new Ext.ux.grid.RowEditor({
        saveText: 'Update'
    });


    var gridpenjualanpelunasanpiutang = new Ext.grid.GridPanel({
        store: strpenjualanpelunasanpiutang,
        stripeRows: true,
        height: 200,
        frame: true,
        border: true,
        plugins: [editorpenjualanpelunasanpiutang],
        tbar: [{
                icon: BASE_ICONS + 'add.png',
                text: 'Add No Faktur',
                handler: function() {

//                    if (Ext.getCmp('id_cbpphsuplier').getValue() === '') {
//                        Ext.Msg.show({
//                            title: 'Error',
//                            msg: 'Silahkan pilih supplier terlebih dulu',
//                            modal: true,
//                            icon: Ext.Msg.ERROR,
//                            buttons: Ext.Msg.OK
//                        });
//                        return;
//                    }

                    var rowpenjualanpelunasanhutang = new gridpenjualanpelunasanpiutang.store.recordType({
                        no_invoice: '',
                        rp_total: '',
                        rp_bayar: '',
                        rp_diskon: ''
                    });
                    editorpenjualanpelunasanpiutang.stopEditing();
                    strpenjualanpelunasanpiutang.insert(0, rowpenjualanpelunasanhutang);
                    gridpenjualanpelunasanpiutang.getView().refresh();
                    gridpenjualanpelunasanpiutang.getSelectionModel().selectRow(0);
                    editorpenjualanpelunasanpiutang.startEditing(0);
                }
            }, {
                ref: '../removeBtn',
                icon: BASE_ICONS + 'delete.gif',
                text: 'Remove',
                disabled: true,
                handler: function() {

                    editorpenjualanpelunasanpiutang.stopEditing();
                    var s = gridpenjualanpelunasanpiutang.getSelectionModel().getSelections();
                    for (var i = 0, r; r = s[i]; i++) {
                        strpenjualanpelunasanpiutang.remove(r);
                    }
                    var jumlah = 0;
                    var rp_kurang_bayar = 0;
                    var rp_bayar = 0;

                    strpenjualanpelunasanpiutang.each(function(node) {
                        jumlah += parseInt(node.data.rp_grand_total);
                        rp_kurang_bayar += parseInt(node.data.rp_kurang_bayar);
                        rp_bayar += parseInt(node.data.rp_bayar);
                    });

                    var total_bayar = Ext.getCmp('ppp_total_bayar').getValue();
                    var selisih = total_bayar - rp_bayar ;
                    Ext.getCmp('ppp_rp_total_faktur').setValue(jumlah);
                    Ext.getCmp('ppp_rp_kurang_bayar').setValue(rp_kurang_bayar);
                    Ext.getCmp('ppp_rp_bayar').setValue(rp_bayar);
                    Ext.getCmp('ppp_rp_selisih').setValue(selisih);
                }
            }],
        columns: [{
                header: 'No Faktur / Struk',
                dataIndex: 'no_so',
                width: 150,
                sortable: true,
                editor: new Ext.ux.TwinCombopppnofaktur({
                    id: 'eppp_no_faktur',
                    store: strcbpppnofaktur,
                    mode: 'local',
                    valueField: 'no_so',
                    displayField: 'no_so',
                    typeAhead: true,
                    triggerAction: 'all',
                    editable: false,
                    hiddenName: 'no_so',
                    emptyText: 'Pilih No Faktur'

                })
            }, {
                header: 'Tanggal Faktur',
                dataIndex: 'tgl_so',
                width: 90,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'eppp_tgl_so',
                    fieldClass: 'readonly-input'
                })
            },
//                {
//                header: 'No Faktur / Struk',
//                dataIndex: 'no_so',
//                width: 140,
//                sortable: true,
//                editor: new Ext.form.TextField({
//                    readOnly: true,
//                    id: 'eppp_no_so',
//                    fieldClass: 'readonly-input number'
//                })
//            },
//                {
//                header: 'Tanggal Faktur',
//                dataIndex: 'tgl_so',
//                width: 90,
//                editor: new Ext.form.TextField({
//                    readOnly: true,
//                    id: 'eppp_tgl_so',
//                    fieldClass: 'readonly-input'
//                })
//            },
//                {
//                xtype: 'numbercolumn',
//                header: 'Rp Faktur',
//                dataIndex: 'rp_total',
//                width: 90,
//                align: 'right',
//                sortable: true,
//                format: '0,0',
//                value: 0,
//                editor: {
//                    xtype: 'numberfield',
//                    readOnly: true,
//                    id: 'eppp_rp_total',
//                    fieldClass: 'readonly-input number'
//                }
//            }, {
//                xtype: 'numbercolumn',
//                header: 'Rp Diskon',
//                dataIndex: 'rp_diskon',
//                width: 90,
//                align: 'right',
//                sortable: true,
//                format: '0,0',
//                value: 0,
//                editor: {
//                    xtype: 'numberfield',
//                    readOnly: true,
//                    id: 'eppp_rp_diskon',
//                    fieldClass: 'readonly-input number'
//                }
//            }, {
//                xtype: 'numbercolumn',
//                header: 'Rp Bank Charge',
//                dataIndex: 'rp_bank_charge',
//                width: 120,
//                align: 'right',
//                sortable: true,
//                format: '0,0',
//                editor: {
//                    xtype: 'numberfield',
//                    id: 'eppp_rp_bank_charge',
//                    fieldClass: 'readonly-input number',
//                    readOnly: true
//                }
//            },{
//                xtype: 'numbercolumn',
//                header: 'Rp Ongkos Kirim',
//                dataIndex: 'rp_ongkos_kirim',
//                width: 120,
//                align: 'right',
//                sortable: true,
//                format: '0,0',
//                editor: {
//                    xtype: 'numberfield',
//                    id: 'eppp_rp_ongkos_kirim',
//                    fieldClass: 'readonly-input number',
//                    readOnly: true
//                }
//            }, {
//                xtype: 'numbercolumn',
//                header: 'Rp Ongkos Pasang',
//                dataIndex: 'rp_ongkos_pasang',
//                width: 120,
//                sortable: true,
//                align: 'right',
//                format: '0,0',
//                value: 0,
//                editor: {
//                    xtype: 'numberfield',
//                    id: 'eppp_rp_ongkos_pasang',
//                    fieldClass: 'readonly-input number',
//                    readOnly: true,
//                }
//            },
                {
                xtype: 'numbercolumn',
                header: 'Rp Jumlah Faktur',
                dataIndex: 'rp_grand_total',
                width: 120,
                sortable: true,
                align: 'right',
                format: '0,0',
                value: 0,
                editor: {
                    xtype: 'numberfield',
                    id: 'eppp_rp_grand_total',
                    fieldClass: 'readonly-input number',
                    readOnly: true
                }
            },{
                xtype: 'numbercolumn',
                header: 'Total Bayar',
                dataIndex: 'rp_total_bayar',
                width: 100,
                sortable: true,
                align: 'right',
                format: '0,0',
                value: 0,
                editor: {
                    xtype: 'numberfield',
                    id: 'eppp_rp_total_bayar',
                    fieldClass: 'readonly-input number',
                    readOnly: true,
                }
            },{
                xtype: 'numbercolumn',
                header: 'Rp Kurang Bayar',
                dataIndex: 'rp_kurang_bayar',
                width: 130,
                sortable: true,
                align: 'right',
                format: '0,0',
                value: 0,
                editor: {
                    xtype: 'numberfield',
                    id: 'eppp_rp_kurang_bayar',
                    fieldClass: 'readonly-input number',
                    readOnly: true,
                   }
            },{
                xtype: 'numbercolumn',
                header: 'Rp Bayar',
                dataIndex: 'rp_bayar',
                width: 120,
                sortable: true,
                align: 'right',
                format: '0,0',
                value: 0,
                editor: {
                    xtype: 'numberfield',
                    id: 'eppp_rp_bayar',
                    listeners: {
                        'change': function() {
                            var sisa = Ext.getCmp('eppp_rp_kurang_bayar').getValue() - this.getValue();
                            Ext.getCmp('eppp_sisa_bayar').setValue(sisa);

                            var dibayar = this.getValue() - Ext.getCmp('eppp_rp_potongan').getValue();
                            Ext.getCmp('eppp_rp_dibayar').setValue(dibayar);
                        },
                        'specialkey': function(field, e){
                            if (e.getKey() == e.ENTER) {
                                var sisa = Ext.getCmp('eppp_rp_kurang_bayar').getValue() - this.getValue();
                                Ext.getCmp('eppp_sisa_bayar').setValue(sisa);

                                var dibayar = this.getValue() - Ext.getCmp('eppp_rp_potongan').getValue();
                                Ext.getCmp('eppp_rp_dibayar').setValue(dibayar);
                            }
                        }
                    }
                }
            },{
                xtype: 'numbercolumn',
                header: 'Rp Potongan',
                dataIndex: 'rp_potongan',
                width: 120,
                sortable: true,
                align: 'right',
                format: '0,0',
                value: 0,
                editor: {
                    xtype: 'numberfield',
                    id: 'eppp_rp_potongan',
                    listeners: {
                        'change': function() {
                            var sisa = Ext.getCmp('eppp_rp_bayar').getValue() - this.getValue();
                            Ext.getCmp('eppp_rp_dibayar').setValue(sisa);

//                            var dibayar = this.getValue() - Ext.getCmp('epph_rp_diskon').getValue();
//                            Ext.getCmp('epph_rp_dibayar').setValue(dibayar);
                        },
                        'specialkey': function(field, e){
                            if (e.getKey() == e.ENTER) {
                                var sisa = Ext.getCmp('eppp_rp_bayar').getValue() - this.getValue();
                                Ext.getCmp('eppp_rp_dibayar').setValue(sisa);
                            }
                        }
                    }
                }
            },{
                xtype: 'numbercolumn',
                header: 'Rp Dibayar',
                dataIndex: 'rp_dibayar',
                width: 100,
                sortable: true,
                align: 'right',
                format: '0,0',
                value: 0,
                editor: {
                    xtype: 'numberfield',
                    id: 'eppp_rp_dibayar',
                    fieldClass: 'readonly-input number',
                    readOnly: true
                }
            },{
                xtype: 'numbercolumn',
                header: 'Sisa Piutang',
                dataIndex: 'sisa_bayar',
                width: 100,
                sortable: true,
                align: 'right',
                format: '0,0',
                value: 0,
                editor: {
                    xtype: 'numberfield',
                    id: 'eppp_sisa_bayar',
                    fieldClass: 'readonly-input number',
                    readOnly: true
                }
            }]
    });

     gridpenjualanpelunasanpiutang.getSelectionModel().on('selectionchange', function(sm){
     gridpenjualanpelunasanpiutang.removeBtn.setDisabled(sm.getCount() < 1);
     });

    var _strcbpppjnspembayaran = new Ext.data.ArrayStore({
        fields: ['kd_jenis_bayar'],
        data: []
    });

    var _strgridpppjnspembayaran = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_jenis_bayar', 'nm_pembayaran'],
            root: 'data',
            totalproperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("penjualan_pelunasan_piutang/get_all_jenis_pembayaran") ?>',
            method: 'POST'
        }),
        listeners: {
            loadexception: function(event, options, response, error) {
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    var _gridpppjnspembayaran = new Ext.grid.GridPanel({
        store: _strgridpppjnspembayaran,
        stripeRows: true,
        frame: true,
        border: true,
        columns: [{
                header: 'Kode',
                dataIndex: 'kd_jenis_bayar',
                width: 70,
                sortable: true,
            }, {
                header: 'Jenis Pembayaran',
                dataIndex: 'nm_pembayaran',
                width: 200,
                sortable: true,
            }],
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('eppp_kd_jenis_bayar').setValue(sel[0].get('kd_jenis_bayar'));
                    Ext.getCmp('eppp_nama_pembayaran').setValue(sel[0].get('nm_pembayaran'));

                    menupppjnspembayaran.hide();
                }
            }
        }
    });

    var menupppjnspembayaran = new Ext.menu.Menu();
    menupppjnspembayaran.add(new Ext.Panel({
        title: 'Pilih Jenis Pembayaran',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 300,
        height: 250,
        closeAction: 'hide',
        plain: true,
        items: [_gridpppjnspembayaran],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menupppjnspembayaran.hide();
                }
            }]
    }));

    Ext.ux.TwinCombopppjnspembayaran = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            //load store grid
            _strgridpppjnspembayaran.load();
            menupppjnspembayaran.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
                trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    var strpppjenispembayaran = new Ext.data.Store({
        autoSave: false,
        reader: new Ext.data.JsonReader({
            fields: [
                {name: 'kd_jenis_bayar', type: 'text'},
                {name: 'nm_pembayaran', type: 'text'},
                {name: 'rp_bayar', type: 'int'},
                {name: 'nomor_ref', type: 'text'},
                {name: 'nomor_bank', type: 'text'},
                {name: 'tgl_jth_tempo', type: 'text'},
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



    strpppjenispembayaran.on('update', function() {
        var total_bayar = 0;
        var total_kurang_bayar = Ext.getCmp('ppp_rp_kurang_bayar').getValue();
        var rp_bayar = Ext.getCmp('ppp_rp_bayar').getValue();
        var nama_pembayaran = "";
        var no_bank = "";
        var no_ref = "";
        var tgl_jth_tempo = "";
        strpppjenispembayaran.each(function(node) {
            total_bayar += parseInt(node.data.rp_bayar_piutang);
            nama_pembayaran = node.data.nm_pembayaran;
            no_bank = node.data.nomor_bank;
            no_ref = node.data.nomor_ref;
            tgl_jth_tempo = node.data.tgl_jth_tempo;
        });


        Ext.getCmp('ppp_total_bayar').setValue(total_bayar);
        Ext.getCmp('ppp_rp_selisih').setValue(total_bayar - rp_bayar);



    });

    var editorpppjenispembayaran = new Ext.ux.grid.RowEditor({
        saveText: 'Update'
    });

    var gridpppjenispembayaran = new Ext.grid.GridPanel({
        id: 'idgridpppjenispembayaran',
        title: 'JENIS PEMBAYARAN',
        store: strpppjenispembayaran,
        stripeRows: true,
        height: 200,
        border: true,
        frame: true,
        plugins: [editorpppjenispembayaran],
        tbar: [{
                icon: BASE_ICONS + 'add.png',
                text: 'Add Pembayaran',
                handler: function() {
                    if (Ext.getCmp('ppp_rp_total_faktur').getValue() == 0) {
                        Ext.Msg.show({
                            title: 'Error',
                            msg: 'Total faktur masih kosong!',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK,
                        });
                        return;
                    }
                    var rowpppjenispembayaran = new gridpppjenispembayaran.store.recordType({
                        kd_jenis_bayar: '',
                        rp_bayar: '',
                        nomor_bank: '',
                        nomor_ref: '',
                        tgl_jth_tempo: ''
                    });
                    editorpppjenispembayaran.stopEditing();
                    strpppjenispembayaran.insert(0, rowpppjenispembayaran);
                    gridpppjenispembayaran.getView().refresh();
                    gridpppjenispembayaran.getSelectionModel().selectRow(0);
                    editorpppjenispembayaran.startEditing(0);
                }
            }, {
                ref: '../removeBtn',
                icon: BASE_ICONS + 'delete.gif',
                text: 'Remove',
                disabled: true,
                handler: function() {

                    editorpppjenispembayaran.stopEditing();
                    var s = gridpppjenispembayaran.getSelectionModel().getSelections();
                    for (var i = 0, r; r = s[i]; i++) {
                        strpppjenispembayaran.remove(r);
                    }

                }
            }],
        columns: [{
                header: 'Kode',
                dataIndex: 'kd_jenis_bayar',
                width: 100,
                editor: new Ext.ux.TwinCombopppjnspembayaran({
                    id: 'eppp_kd_jenis_bayar',
                    store: _strcbpppjnspembayaran,
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
                    id: 'eppp_nama_pembayaran',
                    fieldClass: 'readonly-input',
                })
            }, {
                xtype: 'numbercolumn',
                header: 'Jumlah Bayar',
                dataIndex: 'rp_bayar_piutang',
                width: 100,
                format: '0,0',
                editor: {
                    xtype: 'numberfield',
                    id: 'eppp_rp_bayar_jns',
                    selectOnFocus: true,
                }
            }, {
                header: 'Nama Bank',
                dataIndex: 'nomor_bank',
                width: 100,
                editor: new Ext.form.TextField({
                    id: 'eppp_nomor_bank'
                })
            }, {
                header: 'No Warkat',
                dataIndex: 'nomor_ref',
                width: 100,
                editor: new Ext.form.TextField({
                    id: 'eppp_nomor_ref'
                })
            }, {
                xtype: 'datecolumn',
                header: 'Tgl Jatuh Tempo',
                dataIndex: 'tgl_jth_tempo',
                format: 'd/m/Y',
                width: 120,
                editor: new Ext.form.DateField({
                    id: 'eppp_tgl_jth_tempo',
                    format: 'd/m/Y',
                })
            },
        ]
    });

    gridpppjenispembayaran.getSelectionModel().on('selectionchange', function(sm) {
        gridpppjenispembayaran.removeBtn.setDisabled(sm.getCount() < 1);
    });

      var penjualanpelunasanpiutang = new Ext.FormPanel({
        id: 'penjualanpelunasanpiutang',
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
                items: [headerpenjualanpelunasanpiutang]
            },
            gridpenjualanpelunasanpiutang,
            {
                layout: 'column',
                border: false,
                items: [{
                        columnWidth: .7,
                        layout: 'fit',
                        style: 'margin:6px 3px 0 0;',
                        items: [
                            {
                                xtype: 'tabpanel',
                                height: 450,
                                activeTab: 0,
                                deferredRender: false,
                                items: [gridpppjenispembayaran]
                            }
                            //gridpppjenispembayaran
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
                                        fieldLabel: 'Total Faktur',
                                        name: 'rp_total_faktur',
                                        readOnly: true,
                                        id: 'ppp_rp_total_faktur',
                                        anchor: '90%',
                                        fieldClass: 'readonly-input number',
                                        value: '0',
                                    }, {
                                        xtype: 'numericfield',
                                        currencySymbol: '',
                                        fieldLabel: 'Rp Kurang Bayar',
                                        name: 'rp_kurang_bayar',
                                        id: 'ppp_rp_kurang_bayar',
                                        anchor: '90%',
                                        readOnly: true,
                                        fieldClass: 'readonly-input number',
                                        value: '0',
                                    },{
                                        xtype: 'numericfield',
                                        currencySymbol: '',
                                        fieldLabel: 'Total Potongan',
                                        name: 'total_potongan',
                                        id: 'ppp_total_potongan',
                                        anchor: '90%',
                                        readOnly: true,
                                        fieldClass: 'readonly-input number',
                                        value: '0',
                                    }, {
                                        xtype: 'numericfield',
                                        currencySymbol: '',
                                        fieldLabel: 'Total Dibayar',
                                        name: 'rp_bayar',
                                        id: 'ppp_rp_bayar',
                                        anchor: '90%',
                                        readOnly: true,
                                        fieldClass: 'readonly-input number',
                                        value: '0',
                                    }, {
                                        xtype: 'numericfield',
                                        currencySymbol: '',
                                        fieldLabel: 'Total bayar',
                                        name: 'total_bayar',
                                        id: 'ppp_total_bayar',
                                        anchor: '90%',
                                        readOnly: true,
                                        fieldClass: 'readonly-input number',
                                        value: '0',
                                    }, {
                                        xtype: 'numericfield',
                                        currencySymbol: '',
                                        fieldLabel: 'Selisih',
                                        name: 'rp_selisih',
                                        readOnly: true,
                                        fieldClass: 'readonly-input number',
                                        id: 'ppp_rp_selisih',
                                        anchor: '90%',
                                        value: '0',
                                    },
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

                    if (Ext.getCmp('ppp_rp_bayar').getValue() == 0) {
                        Ext.Msg.show({
                            title: 'Error',
                            msg: 'Rp Bayar Masih Kosong / NOL!',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK,
                        });
                        return;
                    }
                    if (Ext.getCmp('ppp_total_bayar').getValue() <  Ext.getCmp('ppp_rp_bayar').getValue()) {
                        Ext.Msg.show({
                            title: 'Error',
                            msg: 'Total Bayar Tidak Boleh Lebih Kecil Dari Rp Bayar!',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK,
                        });
                        return;
                    }
                    var nama_pembayaran = "";
                    var no_bank = "";
                    var no_ref = "";
                    var tgl_jth_tempo = "";

                    var is_true = true;
                    var is_card = false;
                    strpppjenispembayaran.each(function(node) {
                        nama_pembayaran = node.data.nm_pembayaran;
                        no_bank = node.data.nomor_bank;
                        no_ref = node.data.nomor_ref;
                        tgl_jth_tempo = node.data.tgl_jth_tempo;
                        if (nama_pembayaran === 'CEK' || nama_pembayaran === 'GIRO' ){
                            if (no_bank.length <= 0 || no_ref.length <= 0 || tgl_jth_tempo.length <= 0 ){
                                is_true = false;
                            }
                        }
                        if ( (nama_pembayaran.indexOf('DEBIT') >= 0 || nama_pembayaran.indexOf('KREDIT') >= 0) && no_ref.length<=0 ) {
                            is_card = true;
                        }
                    });
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
                    if (Ext.getCmp('ppp_rp_bayar').getValue() >  Ext.getCmp('ppp_rp_kurang_bayar').getValue()) {
                        Ext.Msg.show({
                            title: 'Error',
                            msg: 'Total Bayar Tidak Boleh Lebih Besar Dari Rp Kurang Bayar!',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK,
                        });
                        return;
                    }
                    if (Ext.getCmp('ppp_total_bayar').getValue() >  Ext.getCmp('ppp_rp_bayar').getValue()) {
                        if (Ext.getCmp('eppp_sisa_bayar').getValue() > '0' ){
                        Ext.Msg.show({
                            title: 'Error',
                            msg: 'Sisa Piutang Tidak Boleh > 0, Apabila Total Bayar > Total dibayar',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK,
                        });
                        return;
                        }
                    }
                    var detailpenjualanpelunasanpiutang = new Array();

                    strpenjualanpelunasanpiutang.each(function(node) {
                        detailpenjualanpelunasanpiutang.push(node.data);

                    });

                    var detailbayarpenjualanpelunasanpiutang = new Array();

                    strpppjenispembayaran.each(function(node) {
                        detailbayarpenjualanpelunasanpiutang.push(node.data);

                    });


                    Ext.getCmp('penjualanpelunasanpiutang').getForm().submit({
                        url: '<?= site_url("penjualan_pelunasan_piutang/update_row") ?>',
                        scope: this,
                        params: {
                            detail: Ext.util.JSON.encode(detailpenjualanpelunasanpiutang),
                            detailbayar: Ext.util.JSON.encode(detailbayarpenjualanpelunasanpiutang),
                            _total_faktur: Ext.getCmp('ppp_rp_total_faktur').getValue(),
                            _kurang_bayar: Ext.getCmp('ppp_rp_kurang_bayar').getValue(),
                             _total_potongan: Ext.getCmp('ppp_total_potongan').getValue(),
                            _rp_bayar: Ext.getCmp('ppp_rp_bayar').getValue(),
                             _total_bayar: Ext.getCmp('ppp_total_bayar').getValue(),
                            _selisih: Ext.getCmp('ppp_rp_selisih').getValue(),
                        },
                        waitMsg: 'Saving Data...',
                        success: function(form, action) {
                            var r = Ext.util.JSON.decode(action.response.responseText);
                            Ext.Msg.show({
                                title: 'Success',
                                msg: r.errMsg,
                                modal: true,
                                icon: Ext.Msg.INFO,
                                buttons: Ext.Msg.OK,
                                fn: function(btn) {
                                    if (btn == 'ok') {
                                        winpelunasanpiutangprint.show();
                                        Ext.getDom('pelunasanpiutangprint').src = r.printUrl;
                                    }
                                }
                            });

                            clearpenjualanpelunasanpiutang();
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
                                    if (btn == 'ok' && fe.errMsg == 'Session Expired') {
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
                    clearpenjualanpelunasanpiutang();
                }
            }]
    });
    var winpelunasanpiutangprint = new Ext.Window({
        id: 'id_winpelunasanpiutangprint',
        title: 'Print Pelunasan Piutang',
        closeAction: 'hide',
        width: 900,
        height: 450,
        layout: 'fit',
        border: false,
        html:'<iframe style="width:100%;height:100%;" id="pelunasanpiutangprint" src=""></iframe>'
    });
    penjualanpelunasanpiutang.on('afterrender', function() {
        this.getForm().load({
            url: '<?= site_url("penjualan_pelunasan_piutang/get_form") ?>',
            failure: function(form, action) {
                var de = Ext.util.JSON.decode(action.response.responseText);
                Ext.Msg.show({
                    title: 'Error',
                    msg: de.errMsg,
                    modal: true,
                    icon: Ext.Msg.ERROR,
                    buttons: Ext.Msg.OK,
                    fn: function(btn) {
                        if (btn == 'ok' && de.errMsg == 'Session Expired') {
                            window.location = '<?= site_url("auth/login") ?>';
                        }
                    }
                });
            }
        });
    });

    function clearpenjualanpelunasanpiutang() {
        Ext.getCmp('penjualanpelunasanpiutang').getForm().reset();
        Ext.getCmp('penjualanpelunasanpiutang').getForm().load({
            url: '<?= site_url("penjualan_pelunasan_piutang/get_form") ?>',
            failure: function(form, action) {
                var de = Ext.util.JSON.decode(action.response.responseText);
                Ext.Msg.show({
                    title: 'Error',
                    msg: de.errMsg,
                    modal: true,
                    icon: Ext.Msg.ERROR,
                    buttons: Ext.Msg.OK,
                    fn: function(btn) {
                        if (btn == 'ok' && de.errMsg == 'Session Expired') {
                            window.location = '<?= site_url("auth/login") ?>';
                        }
                    }
                });
            }
        });
        strpenjualanpelunasanpiutang.removeAll();
        strpppjenispembayaran.removeAll();
    }
</script>
