<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">
    var strcbkcposuplier = new Ext.data.ArrayStore({
        fields: ['kd_supplier'],
        data: []
    });

    var strgridkcposuplier = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_supplier', 'nama_supplier', 'alamat', 'pic', 'pkp', 'PICPenerimaPO', 'AlamatPenerimaPO'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("konsinyasi_create_po/search_supplier") ?>',
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

    strgridkcposuplier.on('load', function() {
        Ext.getCmp('id_searchgridkcposuplier').focus();
    });

    var searchgridkcposuplier = new Ext.app.SearchField({
        store: strgridkcposuplier,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgridkcposuplier'
    });


    var gridkcposuplier = new Ext.grid.GridPanel({
        store: strgridkcposuplier,
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
                header: 'Alamat',
                dataIndex: 'alamat',
                width: 200,
                sortable: true
            }, {
                header: 'PIC',
                dataIndex: 'pic',
                width: 100,
                sortable: true
            }, {
                header: 'PKP',
                dataIndex: 'pkp',
                width: 100,
                sortable: true
            }],
        tbar: new Ext.Toolbar({
            items: [searchgridkcposuplier]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridkcposuplier,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('id_cbkcposuplier').setValue(sel[0].get('kd_supplier'));
                    Ext.getCmp('kcpo_nama_supplier').setValue(sel[0].get('nama_supplier'));
                    Ext.getCmp('kcpo_pic_supplier').setValue(sel[0].get('pic'));
                    if (sel[0].get('pkp') === 1) {
                        Ext.getCmp('kcpo_pkp_supplier').setValue('YA');
                        Ext.getCmp('kcpo_ppn_persen').setValue(10);

                    } else {
                        Ext.getCmp('kcpo_pkp_supplier').setValue('TIDAK');
                        Ext.getCmp('kcpo_ppn_persen').setValue(0);
                    }
                    Ext.getCmp('kcpo_alamat_supplier').setValue(sel[0].get('alamat'));

                    strkonsinyasicreatepo.removeAll();

                    Ext.Ajax.request({
                        url: '<?= site_url("konsinyasi_create_po/get_nilai_parameter_pic") ?>',
                        method: 'POST',
                        callback: function(opt, success, responseObj) {
                            var de = Ext.util.JSON.decode(responseObj.responseText);
                            if (de.success === true) {
                                Ext.getCmp('kcpo_pic_penerima').setValue(de.data.nilai_parameter);
                            }
                        }
                    });

                    Ext.Ajax.request({
                        url: '<?= site_url("konsinyasi_create_po/get_nilai_parameter_alamat") ?>',
                        method: 'POST',
                        callback: function(opt, success, responseObj) {
                            var de = Ext.util.JSON.decode(responseObj.responseText);
                            if (de.success === true) {
                                Ext.getCmp('kcpo_alamat_penerima').setValue(de.data.nilai_parameter);
                            }
                        }
                    });

                    Ext.Ajax.request({
                        url: '<?= site_url("konsinyasi_create_po/get_nilai_parameter_remark") ?>',
                        method: 'POST',
                        callback: function(opt, success, responseObj) {
                            var de = Ext.util.JSON.decode(responseObj.responseText);
                            if (de.success === true) {
                                Ext.getCmp('kcpo_remark').setValue(de.data.nilai_parameter);
                            }
                        }
                    });
                    Ext.getCmp('kcpo_jumlah').setValue("0");
                    Ext.getCmp('kcpo_diskon_persen').setValue("0");
                    Ext.getCmp('kcpo_diskon_rp').setValue("0");
                    Ext.getCmp('kcpo_sub_jumlah').setValue("0");

                    Ext.getCmp('kcpo_ppn_rp').setValue("0");
                    Ext.getCmp('kcpo_total').setValue("0");


                    /*
                     var urlgetrodetail = '<?= site_url("konsinyasi_create_po/get_ro_detail_by_supplier") ?>/' + sel[0].get('kd_supplier');
                     gridkonsinyasicreatepo.store.proxy = new Ext.data.HttpProxy({
                     url: urlgetrodetail,
                     method: 'POST'
                     });

                     gridkonsinyasicreatepo.store.reload({
                     callback: function(r, options, response) {
                     if(r.length == 0){
                     Ext.getCmp('kcpo_jumlah').setValue("0");
                     Ext.getCmp('kcpo_diskon_persen').setValue("0");
                     Ext.getCmp('kcpo_diskon_rp').setValue("0");
                     Ext.getCmp('kcpo_sub_jumlah').setValue("0");
                     Ext.getCmp('kcpo_ppn_persen').setValue("0");
                     Ext.getCmp('kcpo_ppn_rp').setValue("0");
                     Ext.getCmp('kcpo_total').setValue("0");
                     }else{
                     cbkcpotop.setValue();
                     cbkcpotop.store.removeAll();
                     cbkcpotop.store.proxy.conn.url = '<?= site_url("konsinyasi_create_po/get_term_of_payment_by_supplier") ?>/' + sel[0].get('kd_supplier');
                     cbkcpotop.store.reload();
                     }
                     }
                     });
                     */
                    cbkcpotop.setValue();
                    cbkcpotop.store.removeAll();
                    cbkcpotop.store.proxy.conn.url = '<?= site_url("konsinyasi_create_po/get_term_of_payment_by_supplier") ?>/' + sel[0].get('kd_supplier');
                    cbkcpotop.store.reload();

                    menukcposuplier.hide();
                    Ext.getCmp('id_cbkcpotop').focus();
                }
            }
        }
    });

    var menukcposuplier = new Ext.menu.Menu();
    menukcposuplier.add(new Ext.Panel({
        title: 'Pilih Supplier',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridkcposuplier],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menukcposuplier.hide();
                }
            }]
    }));

    Ext.ux.TwinCombokcpoSuplier = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            //load store grid
            strgridkcposuplier.load();
            menukcposuplier.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
                trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    menukcposuplier.on('hide', function() {
        var sf = Ext.getCmp('id_searchgridkcposuplier').getValue();
        if (sf !== '') {
            Ext.getCmp('id_searchgridkcposuplier').setValue('');
            searchgridkcposuplier.onTrigger2Click();
        }
    });

    var cbkcposuplier = new Ext.ux.TwinCombokcpoSuplier({
        fieldLabel: 'Supplier <span class="asterix">*</span>',
        id: 'id_cbkcposuplier',
        store: strcbkcposuplier,
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

    var strcbkcpotop = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['waktu_top'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("konsinyasi_create_po/get_term_of_payment_by_supplier") ?>',
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

    var cbkcpotop = new Ext.form.ComboBox({
        fieldLabel: 'Term Of Payment <span class="asterix">*</span>',
        id: 'id_cbkcpotop',
        store: strcbkcpotop,
        valueField: 'waktu_top',
        displayField: 'waktu_top',
        typeAhead: true,
        triggerAction: 'all',
        editable: false,
        allowBlank: false,
        mode: 'local',
        anchor: '90%',
        hiddenName: 'waktu_top',
        emptyText: 'Term Of Payment',
        listeners: {
            select: function() {
                var kd_supplier = Ext.getCmp('id_cbkcposuplier').getValue();
                var wkt_top = this.getValue();
                var pkp = Ext.getCmp('kcpo_pkp_supplier').getValue();
                if (pkp === 'YA') {
                    pkp = 1;
                } else
                    pkp = 2;
                var urlgetrodetail = '<?= site_url("konsinyasi_create_po/get_ro_detail_by_supplier") ?>/' + kd_supplier + '/' + wkt_top + '/' + pkp;
                gridkonsinyasicreatepo.store.proxy = new Ext.data.HttpProxy({
                    url: urlgetrodetail,
                    method: 'POST'
                });

                gridkonsinyasicreatepo.store.reload({
                    callback: function(r, options, response) {
                        if (r.length === 0) {

                            Ext.getCmp('kcpo_jumlah').setValue("0");
                            Ext.getCmp('kcpo_diskon_persen').setValue("0");
                            Ext.getCmp('kcpo_diskon_rp').setValue("0");
                            Ext.getCmp('kcpo_sub_jumlah').setValue("0");
                            Ext.getCmp('kcpo_ppn_rp').setValue("0");
                            Ext.getCmp('kcpo_total').setValue("0");
                            Ext.getCmp('kcpo_sisa_bayar').setValue("0");
                        } else {

                            var hrg_nett_exc = Ext.getCmp('ekcpo_harga').getValue();

                            var jumlah = 0;
                            var diskon_persen = 0;
                            var diskon_rp = 0;
                            var sub_jumlah = 0;
                            var ppn_persen = 10;
                            var ppn_rp = 0;
                            var grand_total = 0;
                            strkonsinyasicreatepo.each(function(node) {
                                jumlah += parseFloat(node.data.jumlah);
                            });

                            sub_jumlah = jumlah;
                            ppn_rp = Math.floor(ppn_persen * sub_jumlah) / 100;
                            grand_total = sub_jumlah + ppn_rp;

                            jumlah = Math.round(jumlah);
                            sub_jumlah = Math.round(sub_jumlah);
                            ppn_rp = Math.round(ppn_rp);
                            grand_total = Math.round(grand_total);

                            Ext.getCmp('kcpo_jumlah').setValue(jumlah);
                            Ext.getCmp('kcpo_diskon_persen').setValue(diskon_persen);
                            Ext.getCmp('kcpo_diskon_rp').setValue(diskon_rp);
                            Ext.getCmp('kcpo_sub_jumlah').setValue(sub_jumlah);
                            Ext.getCmp('kcpo_ppn_persen').setValue(ppn_persen);
                            Ext.getCmp('kcpo_ppn_rp').setValue(ppn_rp);
                            Ext.getCmp('kcpo_total').setValue(grand_total);
                            var sisa_bayar = grand_total - Ext.getCmp('kcpo_dp').getValue();
                            Ext.getCmp('kcpo_sisa_bayar').setValue(sisa_bayar);

                            strkonsinyasicreatepo.each(function(node) {
                                if (node.data.validasi_pr === 1) {
                                    Ext.Msg.show({
                                        title: 'Error',
                                        msg: "Ada Outstanding PR dengan Kode Produk " + node.data.kd_produk,
                                        modal: true,
                                        icon: Ext.Msg.ERROR,
                                        buttons: Ext.Msg.OK
                                    });
                                }
                                if (node.data.validasi_hj === 1) {
                                    Ext.Msg.show({
                                        title: 'Error',
                                        msg: "Harga Jual Untuk Kode Produk " + node.data.kd_produk + " masih kosong",
                                        modal: true,
                                        icon: Ext.Msg.ERROR,
                                        buttons: Ext.Msg.OK
                                    });
                                }
                            });
                        }
                    }
                });
            }
        }
    });


    var headerkonsinyasicreatepo = {
        layout: 'column',
        border: false,
        items: [{
                columnWidth: .4,
                layout: 'form',
                border: false,
                labelWidth: 110,
                defaults: {labelSeparator: ''},
                items: [cbkcposuplier, {
                        xtype: 'textfield',
                        fieldLabel: 'Nama Supplier',
                        name: 'nama_supplier',
                        fieldClass: 'readonly-input',
                        readOnly: true,
                        id: 'kcpo_nama_supplier',
                        anchor: '90%',
                        value: ''
                    }, {
                        xtype: 'textfield',
                        fieldLabel: 'PIC Supplier',
                        name: 'pic',
                        fieldClass: 'readonly-input',
                        readOnly: true,
                        id: 'kcpo_pic_supplier',
                        anchor: '90%',
                        value: ''
                    }, {
                        xtype: 'textfield',
                        fieldLabel: 'Status PKP',
                        name: 'pkp',
                        fieldClass: 'readonly-input',
                        readOnly: true,
                        id: 'kcpo_pkp_supplier',
                        anchor: '90%',
                        value: ''
                    }, {
                        xtype: 'hidden',
                        fieldLabel: 'Alamat Kirim',
                        name: 'alamat',
                        id: 'kcpo_alamat_supplier',
                        anchor: '90%',
                        value: ''
                    }
                ]
            }, {
                columnWidth: .3,
                layout: 'form',
                border: false,
                labelWidth: 110,
                defaults: {labelSeparator: ''},
                items: [{
                        xtype: 'textfield',
                        fieldLabel: 'No Surat Pesanan',
                        name: 'no_po',
                        readOnly: true,
                        fieldClass: 'readonly-input',
                        id: 'kcpo_no_po',
                        maxLength: 255,
                        anchor: '90%',
                        value: ''
                    },
                    cbkcpotop, {
                        xtype: 'datefield',
                        fieldLabel: 'Masa Berlaku <span class="asterix">*</span>',
                        name: 'tgl_berlaku_po',
                        allowBlank: false,
                        format: 'd-m-Y',
                        editable: false,
                        id: 'kcpo_tgl_berlaku_po',
                        anchor: '90%',
                        value: ''
                    }]
            }, {
                columnWidth: .3,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: {labelSeparator: ''},
                items: [{
                        xtype: 'textfield',
                        fieldLabel: 'Tanggal',
                        name: 'tanggal_po',
                        fieldClass: 'readonly-input',
                        readOnly: true,
                        allowBlank: false,
                        id: 'kcpo_tanggal_po',
                        anchor: '90%',
                        value: ''
                    }, {
                        fieldLabel: 'Peruntukan <span class="asterix">*</span>',
                        xtype: 'radiogroup',
                        columnWidth: [.5, .5],
                        allowBlank: false,
                        items: [{
                                boxLabel: 'Supermarket',
                                name: 'kd_peruntukan',
                                inputValue: '0',
                                id: 'kcpo_peruntukan_supermarket',
                                checked: true
                            }, {
                                boxLabel: 'Distribusi',
                                name: 'kd_peruntukan',
                                inputValue: '1',
                                id: 'kcpo_peruntukan_distribusi'
                            }]
                    }, {
                        xtype: 'textfield',
                        fieldLabel: 'Order By',
                        name: 'order_by',
                        readOnly: false,
                        fieldClass: 'readonly-input',
                        id: 'kcpo_order_by',
                        anchor: '90%',
                        value: ''
                    }]
            }
        ]
    };

    var strkonsinyasicreatepo = new Ext.data.Store({
        autoSave: false,
        reader: new Ext.data.JsonReader({
            fields: [
                {name: 'no_ro', allowBlank: false, type: 'text'},
                {name: 'kd_produk', allowBlank: false, type: 'text'},
                {name: 'nama_produk', allowBlank: false, type: 'text'},
                {name: 'nm_satuan', allowBlank: false, type: 'text'},
                {name: 'qty', allowBlank: false, type: 'int'},
                {name: 'qty_po', allowBlank: false, type: 'int'},
                {name: 'qty_adj', allowBlank: false, type: 'int'},
                {name: 'kd_supplier', allowBlank: false, type: 'text'},
                {name: 'nama_supplier', allowBlank: false, type: 'text'},
                {name: 'pic', allowBlank: false, type: 'text'},
                {name: 'alamat', allowBlank: false, type: 'text'},
                {name: 'disk_persen_supp1_po', allowBlank: false, type: 'float'},
                {name: 'disk_persen_supp2_po', allowBlank: false, type: 'float'},
                {name: 'disk_persen_supp3_po', allowBlank: false, type: 'float'},
                {name: 'disk_persen_supp4_po', allowBlank: false, type: 'float'},
                {name: 'disk_amt_supp1_po', allowBlank: false, type: 'int'},
                {name: 'disk_amt_supp2_po', allowBlank: false, type: 'int'},
                {name: 'disk_amt_supp3_po', allowBlank: false, type: 'int'},
                {name: 'disk_amt_supp4_po', allowBlank: false, type: 'int'},
                {name: 'disk_amt_supp5_po', allowBlank: false, type: 'int'},
                {name: 'disk_grid_supp1', allowBlank: false, type: 'text'},
                {name: 'disk_grid_supp2', allowBlank: false, type: 'text'},
                {name: 'disk_grid_supp3', allowBlank: false, type: 'text'},
                {name: 'disk_grid_supp4', allowBlank: false, type: 'text'},
                {name: 'disk_grid_supp5', allowBlank: false, type: 'text'},
                {name: 'disk_persen_supp1', allowBlank: false, type: 'int'},
                {name: 'disk_persen_supp2', allowBlank: false, type: 'int'},
                {name: 'disk_persen_supp3', allowBlank: false, type: 'int'},
                {name: 'disk_persen_supp4', allowBlank: false, type: 'int'},
                {name: 'disk_persen_supp5', allowBlank: false, type: 'int'},
                {name: 'total_diskon', allowBlank: false, type: 'int'},
                {name: 'hrg_supplier', allowBlank: false, type: 'int'},
                {name: 'harga', allowBlank: false, type: 'float'},
                {name: 'dpp_po', allowBlank: false, type: 'float'},
                {name: 'jumlah', allowBlank: false, type: 'float'},
                {name: 'waktu_top', allowBlank: false, type: 'int'},
                {name: 'validasi_pr', allowBlank: false, type: 'int'},
                {name: 'validasi_hj', allowBlank: false, type: 'int'}
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

    strkonsinyasicreatepo.on('update', function() {
        var jumlah = 0;

        strkonsinyasicreatepo.each(function(node) {
            jumlah += parseFloat(node.data.jumlah);
        });

        var diskon_persen = Ext.getCmp('kcpo_diskon_persen').getValue();
        var diskon_rp = diskon_persen * jumlah;
        var sub_jumlah = jumlah - diskon_rp;
        var ppn_persen = Ext.getCmp('kcpo_ppn_persen').getValue();
        var ppn_rp = Math.floor(ppn_persen * sub_jumlah) / 100;
        var grand_total = sub_jumlah + ppn_rp;

        jumlah = Math.round(jumlah);
        sub_jumlah = Math.round(sub_jumlah);
        ppn_rp = Math.round(ppn_rp);
        grand_total = Math.round(grand_total);

        Ext.getCmp('kcpo_jumlah').setValue(jumlah);
        Ext.getCmp('kcpo_diskon_persen').setValue(diskon_persen);
        Ext.getCmp('kcpo_diskon_rp').setValue(diskon_rp);
        Ext.getCmp('kcpo_sub_jumlah').setValue(sub_jumlah);
        Ext.getCmp('kcpo_ppn_persen').setValue(ppn_persen);
        Ext.getCmp('kcpo_ppn_rp').setValue(ppn_rp);
        Ext.getCmp('kcpo_total').setValue(grand_total);

        var sisa_bayar = grand_total - Ext.getCmp('kcpo_dp').getValue();
        Ext.getCmp('kcpo_sisa_bayar').setValue(sisa_bayar);

    });

    var editorkonsinyasicreatepo = new Ext.ux.grid.RowEditor({
        saveText: 'Update'
    });

    var gridkonsinyasicreatepo = new Ext.grid.GridPanel({
        store: strkonsinyasicreatepo,
        stripeRows: true,
        height: 200,
        frame: true,
        border: true,
        loadMask: true,
        plugins: [editorkonsinyasicreatepo],
        columns: [{
                header: 'No PR',
                dataIndex: 'no_ro',
                width: 110,
                sortable: true,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'ekcpo_no_ro'
                })

            }, {
                header: 'Kode',
                dataIndex: 'kd_produk',
                width: 90,
                sortable: true,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'ekcpo_kode'
                })

            }, {
                header: 'Nama Barang',
                dataIndex: 'nama_produk',
                width: 250,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'ekcpo_nama_produk'
                })
            }, {
                header: 'Satuan',
                dataIndex: 'nm_satuan',
                width: 60,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'ekcpo_nm_satuan'
                })
            }, {
                xtype: 'numbercolumn',
                header: 'Qty PR',
                dataIndex: 'qty_adj',
                width: 50,
                align: 'center',
                sortable: true,
                format: '0,0',
                editor: {
                    xtype: 'numberfield',
                    id: 'ekcpo_qty_adj',
                    readOnly: true
                }
            }, {
                xtype: 'numbercolumn',
                header: 'Qty Pesanan',
                dataIndex: 'qty_po',
                width: 80,
                align: 'center',
                sortable: true,
                format: '0,0',
                editor: {
                    xtype: 'numberfield',
                    id: 'ekcpo_qty_po',
                    readOnly: true
                }
            }, {
                xtype: 'numbercolumn',
                header: 'Qty',
                dataIndex: 'qty',
                width: 70,
                align: 'center',
                sortable: true,
                format: '0,0',
                editor: {
                    xtype: 'numberfield',
                    id: 'ekcpo_qty',
                    allowBlank: false,
                    selectOnFocus: true,
                    listeners: {
                        'change': function() {
                            var qty_realisasi = Ext.getCmp('ekcpo_qty_adj').getValue() - Ext.getCmp('ekcpo_qty_po').getValue();
                            if (this.getValue() > qty_realisasi) {
                                Ext.Msg.show({
                                    title: 'Error',
                                    msg: 'Qty sisa tidak boleh lebih besar dari Qty PR-Qty PO',
                                    modal: true,
                                    icon: Ext.Msg.ERROR,
                                    buttons: Ext.Msg.OK,
                                    fn: function(btn) {
                                        if (btn === 'ok') {
                                            Ext.getCmp('ekcpo_qty').reset();
                                        }
                                    }
                                });
                                return;
                            }

                            var jumlah = this.getValue() * Ext.getCmp('ekcpo_harga_exc').getRawValue();
                            Ext.getCmp('ekcpo_jumlah').setValue(jumlah);
                        }
                    }
                }
            }, {
                xtype: 'numbercolumn',
                header: 'Harga Supplier',
                dataIndex: 'hrg_supplier',
                width: 100,
                align: 'right',
                sortable: true,
                format: '0,0',
                editor: {
                    xtype: 'numberfield',
                    id: 'ekcpo_hrg_supplier',
                    readOnly: true
                }
            }, {
                hidden: true,
                dataIndex: 'disk_persen_supp1_po',
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'ekcpo_disk_persen_supp1_po'
                })
            }, {
                hidden: true,
                dataIndex: 'disk_persen_supp2_po',
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'ekcpo_disk_persen_supp2_po'
                })
            }, {
                hidden: true,
                dataIndex: 'disk_persen_supp3_po',
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'ekcpo_disk_persen_supp3_po'
                })
            }, {
                hidden: true,
                dataIndex: 'disk_persen_supp4_po',
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'ekcpo_disk_persen_supp4_po'
                })
            }, {
                hidden: true,
                dataIndex: 'disk_amt_supp1_po',
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'ekcpo_disk_amt_supp1_po'
                })
            }, {
                hidden: true,
                dataIndex: 'disk_amt_supp2_po',
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'ekcpo_disk_amt_supp2_po'
                })
            }, {
                hidden: true,
                dataIndex: 'disk_amt_supp3_po',
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'ekcpo_disk_amt_supp3_po'
                })
            }, {
                hidden: true,
                dataIndex: 'disk_amt_supp4_po',
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'kcpo_disk_amt_supp4_po'
                })
            }, {
                hidden: true,
                dataIndex: 'disk_amt_supp5_po',
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'kcpo_disk_amt_supp5_po'
                })
            }, {
                // xtype: 'numbercolumn',
                header: 'Diskon 1',
                dataIndex: 'disk_grid_supp1',
                width: 100,
                align: 'right',
                sortable: true,
                format: '0,0',
                editor: {
                    xtype: 'textfield',
                    id: 'ekcpo_disk_persen_supp1',
                    readOnly: true
                }
            }, {
                // xtype: 'numbercolumn',
                header: 'Diskon 2',
                dataIndex: 'disk_grid_supp2',
                width: 100,
                sortable: true,
                align: 'right',
                format: '0,0',
                editor: {
                    xtype: 'numberfield',
                    id: 'ekcpo_disk_persen_supp2',
                    readOnly: true
                }
            }, {
                // xtype: 'numbercolumn',
                header: 'Diskon 3',
                dataIndex: 'disk_grid_supp3',
                width: 100,
                sortable: true,
                align: 'right',
                format: '0,0',
                editor: {
                    xtype: 'numberfield',
                    id: 'ekcpo_disk_persen_supp3',
                    readOnly: true
                }
            }, {
                // xtype: 'numbercolumn',
                header: 'Diskon 4',
                dataIndex: 'disk_grid_supp4',
                width: 100,
                sortable: true,
                align: 'right',
                format: '0,0',
                editor: {
                    xtype: 'numberfield',
                    id: 'ekcpo_disk_persen_supp4',
                    readOnly: true
                }
            }, {
                xtype: 'numbercolumn',
                header: 'Diskon 5',
                dataIndex: 'disk_persen_supp5',
                width: 100,
                sortable: true,
                align: 'right',
                format: '0,0',
                editor: {
                    xtype: 'numberfield',
                    id: 'ekcpo_disk_persen_supp5',
                    readOnly: true
                }
            }, {
                xtype: 'numbercolumn',
                header: 'Total Diskon',
                dataIndex: 'total_diskon',
                width: 100,
                sortable: true,
                align: 'right',
                format: '0,0',
                editor: {
                    xtype: 'numberfield',
                    id: 'ekcpo_total_diskon',
                    readOnly: true
                }
            }, {
                xtype: 'numbercolumn',
                header: 'Harga Nett',
                dataIndex: 'harga',
                width: 100,
                align: 'right',
                sortable: true,
                format: '0,0',
                editor: {
                    xtype: 'numberfield',
                    id: 'ekcpo_harga',
                    readOnly: true
                }
            }, {
                xtype: 'numbercolumn',
                header: 'Harga Nett (Exc.)',
                dataIndex: 'dpp_po',
                width: 130,
                align: 'right',
                sortable: true,
                format: '0,0',
                editor: {
                    xtype: 'numberfield',
                    id: 'ekcpo_harga_exc',
                    readOnly: true
                }
            }, {
                xtype: 'numbercolumn',
                header: 'Jumlah',
                dataIndex: 'jumlah',
                width: 100,
                sortable: true,
                align: 'right',
                format: '0,0',
                editor: {
                    xtype: 'numberfield',
                    id: 'ekcpo_jumlah',
                    readOnly: true
                }
            }],
        tbar: [{
                ref: '../removeBtn',
                icon: BASE_ICONS + 'delete.gif',
                text: 'Remove',
                disabled: true,
                handler: function() {

                    editorkonsinyasicreatepo.stopEditing();
                    var s = gridkonsinyasicreatepo.getSelectionModel().getSelections();
                    for (var i = 0, r; r = s[i]; i++) {
                        strkonsinyasicreatepo.remove(r);
                    }

                    var jumlah = 0;

                    strkonsinyasicreatepo.each(function(node) {
                        jumlah += parseFloat(node.data.jumlah);
                    });

                    var diskon_persen = Ext.getCmp('kcpo_diskon_persen').getValue();
                    var diskon_rp = diskon_persen * jumlah;
                    var sub_jumlah = jumlah - diskon_rp;
                    var ppn_persen = Ext.getCmp('kcpo_ppn_persen').getValue();
                    var ppn_rp = Math.floor(ppn_persen * sub_jumlah) / 100;
                    var grand_total = sub_jumlah + ppn_rp;

                    jumlah = Math.round(jumlah);
                    sub_jumlah = Math.round(sub_jumlah);
                    ppn_rp = Math.round(ppn_rp);
                    grand_total = Math.round(grand_total);

                    Ext.getCmp('kcpo_jumlah').setValue(jumlah);
                    Ext.getCmp('kcpo_diskon_persen').setValue(diskon_persen);
                    Ext.getCmp('kcpo_diskon_rp').setValue(diskon_rp);
                    Ext.getCmp('kcpo_sub_jumlah').setValue(sub_jumlah);
                    Ext.getCmp('kcpo_ppn_persen').setValue(ppn_persen);
                    Ext.getCmp('kcpo_ppn_rp').setValue(ppn_rp);
                    Ext.getCmp('kcpo_total').setValue(grand_total);
                    var sisa_bayar = grand_total - Ext.getCmp('kcpo_dp').getValue();
                    Ext.getCmp('kcpo_sisa_bayar').setValue(sisa_bayar);
                }
            }]
    });

    gridkonsinyasicreatepo.getSelectionModel().on('selectionchange', function(sm) {
        gridkonsinyasicreatepo.removeBtn.setDisabled(sm.getCount() < 1);
    });

    var winkonsinyasicreatepoprint = new Ext.Window({
        id: 'id_winkonsinyasicreatepoprint',
        title: 'Print Receive Order Form',
        closeAction: 'hide',
        width: 900,
        height: 450,
        layout: 'fit',
        border: false,
        html: '<iframe style="width:100%;height:100%;" id="konsinyasicreatepoprint" src=""></iframe>'
    });

    var konsinyasicreatepo = new Ext.FormPanel({
        id: 'konsinyasicreatepo',
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
                items: [headerkonsinyasicreatepo]
            },
            gridkonsinyasicreatepo,
            {
                layout: 'column',
                border: false,
                items: [{
                        columnWidth: .6,
                        style: 'margin:6px 3px 0 0;',
                        layout: 'form',
                        labelWidth: 100,
                        items: [
                            {
                                xtype: 'textfield',
                                name: 'pic_penerima',
                                id: 'kcpo_pic_penerima',
                                fieldLabel: 'PIC Penerima',
                                allowBlank: false,
                                width: 300,
                                value: ''
                            }, {
                                xtype: 'textarea',
                                fieldLabel: 'Alamat Penerima',
                                allowBlank: false,
                                name: 'alamat_penerima',
                                id: 'kcpo_alamat_penerima',
                                width: 300
                            }, {
                                xtype: 'textarea',
                                fieldLabel: 'Remark',
                                name: 'remark',
                                id: 'kcpo_remark',
                                width: 300
                            }
                        ]
                    }, {
                        columnWidth: .4,
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
                                        fieldLabel: 'Jumlah',
                                        name: 'jumlah',
                                        readOnly: true,
                                        id: 'kcpo_jumlah',
                                        anchor: '90%',
                                        fieldClass: 'readonly-input number',
                                        value: '0',
                                    }, {
                                        xtype: 'compositefield',
                                        fieldLabel: 'Diskon',
                                        anchor: '-10',
                                        combineErrors: false,
                                        items: [
                                            {
                                                xtype: 'numberfield',
                                                currencySymbol: '',
                                                format: '0',
                                                name: 'diskon_persen',
                                                id: 'kcpo_diskon_persen',
                                                fieldClass: 'number',
                                                width: 50,
                                                value: '0',
                                                maxValue: 100,
                                                selectOnFocus: true,
                                                listeners: {
                                                    'change': function() {
                                                        var jumlah = Ext.getCmp('kcpo_jumlah').getValue();
                                                        var diskon_rp = (this.getValue() * jumlah) / 100;
                                                        var sub_jumlah = jumlah - diskon_rp;
                                                        var ppn_persen = Ext.getCmp('kcpo_ppn_persen').getValue();
                                                        var ppn_rp = Math.floor(ppn_persen * sub_jumlah) / 100;
                                                        var grand_total = sub_jumlah + ppn_rp;

                                                        diskon_rp = Math.round(diskon_rp);
                                                        jumlah = Math.round(jumlah);
                                                        sub_jumlah = Math.round(sub_jumlah);
                                                        ppn_rp = Math.round(ppn_rp);
                                                        grand_total = Math.round(grand_total);

                                                        Ext.getCmp('kcpo_diskon_rp').setValue(diskon_rp);
                                                        Ext.getCmp('kcpo_sub_jumlah').setValue(sub_jumlah);
                                                        Ext.getCmp('kcpo_ppn_rp').setValue(ppn_rp);
                                                        Ext.getCmp('kcpo_total').setValue(grand_total);

                                                        var sisa_bayar = grand_total - Ext.getCmp('kcpo_dp').getValue();
                                                        Ext.getCmp('kcpo_sisa_bayar').setValue(sisa_bayar);

                                                    }
                                                }

                                            },
                                            {
                                                xtype: 'displayfield',
                                                value: '%',
                                                width: 20
                                            },
                                            {
                                                xtype: 'numericfield',
                                                name: 'diskon_rp',
                                                id: 'kcpo_diskon_rp',
                                                currencySymbol: '',
                                                fieldClass: 'readonly-input number',
                                                readOnly: true,
                                                //anchor: '100%',
                                                value: '0'

                                            }
                                        ]
                                    }, {
                                        xtype: 'numericfield',
                                        currencySymbol: '',
                                        fieldLabel: 'Sub Jumlah',
                                        name: 'sub_jumlah',
                                        id: 'kcpo_sub_jumlah',
                                        anchor: '90%',
                                        cls: 'vertical-space',
                                        readOnly: true,
                                        fieldClass: 'readonly-input number',
                                        labelStyle: 'margin-top:10px;',
                                        value: '0'
                                    }, {
                                        xtype: 'compositefield',
                                        fieldLabel: 'PPN',
                                        anchor: '-10',
                                        combineErrors: false,
                                        items: [
                                            {
                                                xtype: 'numberfield',
                                                currencySymbol: '',
                                                format: '0',
                                                name: 'ppn_persen',
                                                id: 'kcpo_ppn_persen',
                                                readOnly: true,
                                                fieldClass: 'readonly-input number',
                                                width: 50,
                                                selectOnFocus: true,
                                                value: '10',
                                                maxValue: 100,
                                                listeners: {
                                                    'change': function() {
                                                        var sub_jumlah = Ext.getCmp('kcpo_sub_jumlah').getValue();
                                                        var ppn_rp = Math.floor(this.getValue() * sub_jumlah) / 100;
                                                        var grand_total = sub_jumlah + ppn_rp;

                                                        ppn_rp = Math.round(ppn_rp);
                                                        grand_total = Math.round(grand_total);

                                                        Ext.getCmp('kcpo_ppn_rp').setValue(ppn_rp);
                                                        Ext.getCmp('kcpo_total').setValue(grand_total);
                                                        var sisa_bayar = grand_total - Ext.getCmp('kcpo_dp').getValue();
                                                        Ext.getCmp('kcpo_sisa_bayar').setValue(sisa_bayar);

                                                    }

                                                }

                                            },
                                            {
                                                xtype: 'displayfield',
                                                value: '%',
                                                width: 20
                                            },
                                            {
                                                xtype: 'numericfield',
                                                name: 'ppn_rp',
                                                id: 'kcpo_ppn_rp',
                                                currencySymbol: '',
                                                fieldClass: 'readonly-input number',
                                                readOnly: true,
                                                //anchor: '100%',
                                               value: '0'
                                            }
                                        ]
                                    }, {
                                        xtype: 'numericfield',
                                        currencySymbol: '',
                                        fieldLabel: '<b>Grand Total</b>',
                                        name: 'total',
                                        cls: 'vertical-space',
                                        readOnly: true,
                                        id: 'kcpo_total',
                                        anchor: '90%',
                                        fieldClass: 'readonly-input bold-input number',
                                        labelStyle: 'margin-top:10px;',
                                        value: '0'
                                    }, {
                                        xtype: 'numericfield',
                                        currencySymbol: '',
                                        fieldLabel: 'DP',
                                        name: 'dp',
                                        id: 'kcpo_dp',
                                        anchor: '90%',
                                        cls: 'vertical-space',
                                        labelStyle: 'margin-top:10px;',
                                        fieldClass: 'number',
                                        value: '0',
                                        listeners: {
                                            'change': function() {
                                                var total = Ext.getCmp('kcpo_total').getValue();
                                                var sisa_bayar = total - this.getValue();

                                                Ext.getCmp('kcpo_sisa_bayar').setValue(sisa_bayar);

                                            }
                                        }
                                    }, {
                                        xtype: 'numericfield',
                                        currencySymbol: '',
                                        fieldLabel: 'Sisa Bayar',
                                        name: 'sisa_bayar',
                                        readOnly: true,
                                        fieldClass: 'readonly-input number',
                                        id: 'kcpo_sisa_bayar',
                                        anchor: '90%',
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
                    if (Ext.getCmp('kcpo_total').getValue() === 0) {
                        Ext.Msg.show({
                            title: 'Error',
                            msg: 'Tidak ada pembelian!',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK
                        });
                        return;
                    }

                    Ext.Msg.show({
                        title: 'Confirm',
                        msg: 'Apakah anda akan menyimpan data ini ??',
                        buttons: Ext.Msg.YESNO,
                        fn: function(btn) {
                            if (btn === 'yes') {
                                var detailkonsinyasicreatepo = new Array();
                                var error = 0;
                                var error_top = 0;
                                var top = Number(Ext.getCmp('id_cbkcpotop').getValue());
                                strkonsinyasicreatepo.each(function(node) {
                                    if (node.data.waktu_top === top) {
                                        if (node.data.qty > (node.data.qty_adj - node.data.qty_po)) {
                                            error++;
                                        } else {
                                            if (node.data.jumlah > 0) {
                                                detailkonsinyasicreatepo.push(node.data);
                                            }
                                        }
                                    } else {
                                        error_top++;
                                    }
                                });

                                if (error > 0) {
                                    Ext.Msg.show({
                                        title: 'Error',
                                        msg: 'Cek Qty sisa masing-masing produk!<br/>Qty sisa tidak boleh lebih besar dari Qty PR-Qty PO',
                                        modal: true,
                                        icon: Ext.Msg.ERROR,
                                        buttons: Ext.Msg.OK
                                    });
                                    return;
                                }

                                // if (error_top > 0) {
                                //     Ext.Msg.show({
                                //         title: 'Error',
                                //         msg: error_top + top + 'Cek waktu TOP masing-masing produk!<br/>Produk yang bisa dipilih hanya untuk TOP yang sama',
                                //         modal: true,
                                //         icon: Ext.Msg.ERROR,
                                //         buttons: Ext.Msg.OK
                                //     });
                                //     return;
                                // }
                                Ext.getCmp('konsinyasicreatepo').getForm().submit({
                                    url: '<?= site_url("konsinyasi_create_po/update_row") ?>',
                                    scope: this,
                                    params: {
                                        detail: Ext.util.JSON.encode(detailkonsinyasicreatepo),
                                        _dp: Ext.getCmp('kcpo_dp').getValue(),
                                        _jumlah: Ext.getCmp('kcpo_jumlah').getValue(),
                                        _diskon_rp: Ext.getCmp('kcpo_diskon_rp').getValue(),
                                        _ppn_persen: Ext.getCmp('kcpo_ppn_persen').getValue(),
                                        _ppn_rp: Ext.getCmp('kcpo_ppn_rp').getValue(),
                                        _total: Ext.getCmp('kcpo_total').getValue()
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
                                                if (btn === 'ok') {
                                                    // winkonsinyasicreatepoprint.show();
                                                    // Ext.getDom('konsinyasicreatepoprint').src = r.printUrl;
                                                }
                                            }
                                        });

                                        clearkonsinyasicreatepo();
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
                        }
                    });
                }
            }, {
                text: 'Reset',
                handler: function() {
                    clearkonsinyasicreatepo();
                }
            }]
    });

    konsinyasicreatepo.on('afterrender', function() {
        this.getForm().load({
            url: '<?= site_url("konsinyasi_create_po/get_form") ?>',
            success: function(form, action) {
                var r = Ext.util.JSON.decode(action.response.responseText);
                if (r.data.user_peruntukan === "0") {
                    Ext.getCmp('kcpo_peruntukan_supermarket').setValue(true);
                    Ext.getCmp('kcpo_peruntukan_supermarket').show();
                    Ext.getCmp('kcpo_peruntukan_distribusi').hide();
                } else if (r.data.user_peruntukan === "1") {
                    Ext.getCmp('kcpo_peruntukan_distribusi').setValue(true);
                    Ext.getCmp('kcpo_peruntukan_supermarket').hide();
                    Ext.getCmp('kcpo_peruntukan_distribusi').show();
                } else {
                    Ext.getCmp('kcpo_peruntukan_supermarket').setValue(true);
                    Ext.getCmp('kcpo_peruntukan_supermarket').show();
                    Ext.getCmp('kcpo_peruntukan_distribusi').show();
                }
            },
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

    function clearkonsinyasicreatepo() {
        Ext.getCmp('konsinyasicreatepo').getForm().reset();
        Ext.getCmp('konsinyasicreatepo').getForm().load({
            url: '<?= site_url("konsinyasi_create_po/get_form") ?>',
            success: function(form, action) {
                var r = Ext.util.JSON.decode(action.response.responseText);
                if (r.data.user_peruntukan === "0") {
                    Ext.getCmp('kcpo_peruntukan_supermarket').setValue(true);
                    Ext.getCmp('kcpo_peruntukan_supermarket').show();
                    Ext.getCmp('kcpo_peruntukan_distribusi').hide();
                } else if (r.data.user_peruntukan === "1") {
                    Ext.getCmp('kcpo_peruntukan_distribusi').setValue(true);
                    Ext.getCmp('kcpo_peruntukan_supermarket').hide();
                    Ext.getCmp('kcpo_peruntukan_distribusi').show();
                } else {
                    Ext.getCmp('kcpo_peruntukan_supermarket').setValue(true);
                    Ext.getCmp('kcpo_peruntukan_supermarket').show();
                    Ext.getCmp('kcpo_peruntukan_distribusi').show();
                }
            },
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
        strkonsinyasicreatepo.removeAll();
    }
</script>
