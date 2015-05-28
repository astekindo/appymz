<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">
    var storeCboSupplier_cpob = new Ext.data.ArrayStore({
        fields: ['kd_supplier'],
        data: []
    });

    var storeGridCboSupplier_cpob = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_supplier', 'nama_supplier', 'alamat', 'pic', 'pkp', 'PICPenerimaPO', 'AlamatPenerimaPO'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("pembelian_create_po_bonus_controller/search_supplier") ?>',
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


    //Pelanggan 
    var storeCboPelanggan_cpob = new Ext.data.ArrayStore({
        fields: ['kd_pelanggan'],
        data: []
    });

    var storeGridCboPelanggan_cpob = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_pelanggan', 'nama_pelanggan', 'tipe', 'nama_tipe', 'alamat_kirim', 'no_telp', 'nama_sales', 'kd_sales'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("pembelian_create_po_bonus_controller/finalGetCustomers") ?>',
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

    storeGridCboSupplier_cpob.on('load', function() {
        Ext.getCmp('id_search_grid_cbo_supplier_cpob').focus();
    });

    var searchGridCboSupplier_cpob = new Ext.app.SearchField({
        store: storeGridCboSupplier_cpob,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_search_grid_cbo_supplier_cpob'
    });

    /**
     * deklarasi grid combo supplier
     */
    var gridCboSupplier_cpob = new Ext.grid.GridPanel({
        store: storeGridCboSupplier_cpob,
        stripeRows: true,
        frame: true,
        border: true,
        columns: [{
                header: 'Kode Supplier',
                dataIndex: 'kd_supplier',
                width: 80,
                sortable: true,
            }, {
                header: 'Nama Supplier',
                dataIndex: 'nama_supplier',
                width: 300,
                sortable: true,
            }, {
                header: 'Alamat',
                dataIndex: 'alamat',
                width: 200,
                sortable: true,
            }, {
                header: 'PIC',
                dataIndex: 'pic',
                width: 100,
                sortable: true,
            }, {
                header: 'PKP',
                dataIndex: 'pkp',
                width: 100,
                sortable: true,
            }],
        tbar: new Ext.Toolbar({
            items: [searchGridCboSupplier_cpob]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: storeGridCboSupplier_cpob,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('id_cbo_supplier_cpob').setValue(sel[0].get('kd_supplier'));
                    Ext.getCmp('id_nama_supplier_cpob').setValue(sel[0].get('nama_supplier'));
                    Ext.getCmp('id_pic_supplier_cpob').setValue(sel[0].get('pic'));
                    if (sel[0].get('pkp') == 1) {
                        Ext.getCmp('id_pkp_supplier_cpob').setValue('YA');
                        Ext.getCmp('id_ppn_persen_cpob').setValue('10');
                    } else {
                        Ext.getCmp('id_pkp_supplier_cpob').setValue('TIDAK');
                        Ext.getCmp('id_ppn_persen_cpob').setValue('0');
                    }
                    Ext.getCmp('id_alamat_supplier_cpob').setValue(sel[0].get('alamat'));

                    storePembelianCreateaPoBonus_cpob.removeAll();

                    Ext.getCmp('id_jumlah_cpob').setValue("0");
                    Ext.getCmp('id_diskon_persen_cpob').setValue("0");
                    Ext.getCmp('id_diskon_rp_cpob').setValue("0");
                    Ext.getCmp('id_sub_jumlah_cpob').setValue("0");
                    Ext.getCmp('id_ppn_rp_cpob').setValue("0");
                    Ext.getCmp('id_total_cpob').setValue("0");

                    Ext.Ajax.request({
                        url: '<?= site_url("pembelian_create_po_bonus_controller/get_nilai_parameter_pic") ?>',
                        method: 'POST',
                        callback: function(opt, success, responseObj) {
                            var de = Ext.util.JSON.decode(responseObj.responseText);
                            if (de.success == true) {
                                Ext.getCmp('id_pic_penerima_cpob').setValue(de.data.nilai_parameter);
                            }
                        }
                    });
//
                    Ext.Ajax.request({
                        url: '<?= site_url("pembelian_create_po_bonus_controller/get_nilai_parameter_alamat") ?>',
                        method: 'POST',
                        callback: function(opt, success, responseObj) {
                            var de = Ext.util.JSON.decode(responseObj.responseText);
                            if (de.success == true) {
                                Ext.getCmp('id_alamat_penerima_cpob').setValue(de.data.nilai_parameter);
                            }
                        }
                    });
//
                    Ext.Ajax.request({
                        url: '<?= site_url("pembelian_create_po_bonus_controller/get_nilai_parameter_remark") ?>',
                        method: 'POST',
                        callback: function(opt, success, responseObj) {
                            var de = Ext.util.JSON.decode(responseObj.responseText);
                            if (de.success == true) {
                                Ext.getCmp('id_remark_cpob').setValue(de.data.nilai_parameter);
                            }
                        }
                    });
//
                    cboWaktuTop_cpob.setValue();
                    cboWaktuTop_cpob.store.removeAll();
                    cboWaktuTop_cpob.store.proxy.conn.url = '<?= site_url("pembelian_create_po_bonus_controller/get_term_of_payment_by_supplier") ?>/' + sel[0].get('kd_supplier');
                    cboWaktuTop_cpob.store.reload();

                    menuCboSupplier_cpob.hide();
                    Ext.getCmp('id_cbo_waktu_top_cpob').focus();
                }
            }
        }
    });
    var menuCboSupplier_cpob = new Ext.menu.Menu();
    menuCboSupplier_cpob.add(new Ext.Panel({
        title: 'Pilih Supplier',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridCboSupplier_cpob],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menuCboSupplier_cpob.hide();
                }
            }]
    }));

    Ext.ux.TwinComboSupplier_cpob = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            //load store grid
            storeGridCboSupplier_cpob.load();
            menuCboSupplier_cpob.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
                trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    menuCboSupplier_cpob.on('hide', function() {
        var sf = Ext.getCmp('id_search_grid_cbo_supplier_cpob').getValue();
        if (sf != '') {
            Ext.getCmp('id_search_grid_cbo_supplier_cpob').setValue('');
            searchGridCboSupplier_cpob.onTrigger2Click();
        }
    });

    var cboSupplier_cpob = new Ext.ux.TwinComboSupplier_cpob({
        fieldLabel: 'Supplier <span class="asterix">*</span>',
        id: 'id_cbo_supplier_cpob',
        store: storeCboSupplier_cpob,
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


    /**
     * deklarasi combo pelanggan
     */
    var searchGridCboPelanggan_cpob = new Ext.app.SearchField({
        store: storeGridCboPelanggan_cpob,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_search_grid_cbo_pelanggan_cpob'
    });


    var gridCboPelanggan_cpob = new Ext.grid.GridPanel({
        store: storeGridCboPelanggan_cpob,
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
                width: 200,
                sortable: true
            }],
        tbar: new Ext.Toolbar({
            items: [searchGridCboPelanggan_cpob]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: storeGridCboPelanggan_cpob,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('id_pic_penerima_cpob').setValue(sel[0].get('nama_pelanggan'));
                    Ext.getCmp('id_cbo_pelanggan_cpob').setValue(sel[0].get('kd_pelanggan'));
                    Ext.getCmp('id_alamat_penerima_cpob').setValue(sel[0].get('alamat_kirim'));
                    menuCboPelanggan_cpob.hide();
                }
            }
        }
    });

    var menuCboPelanggan_cpob = new Ext.menu.Menu();
    menuCboPelanggan_cpob.add(new Ext.Panel({
        title: 'Pilih Pelanggan',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 300,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridCboPelanggan_cpob],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menuCboPelanggan_cpob.hide();
                }
            }]
    }));

    Ext.ux.TwinComboPelanggan_cpob = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            //load store grid
            storeGridCboPelanggan_cpob.load();
            menuCboPelanggan_cpob.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
                trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    menuCboPelanggan_cpob.on('hide', function() {
        var sf = Ext.getCmp('id_search_grid_cbo_pelanggan_cpob').getValue();
        if (sf !== '') {
            Ext.getCmp('id_search_grid_cbo_pelanggan_cpob').setValue('');
            searchGridCboPelanggan_cpob.onTrigger2Click();
        }
    });

    var cboPelanggan_cpob = new Ext.ux.TwinComboPelanggan_cpob({
        fieldLabel: 'Pelanggan',
        id: 'id_cbo_pelanggan_cpob',
        store: storeCboPelanggan_cpob,
        mode: 'local',
        valueField: 'kd_pelanggan',
        displayField: 'kd_pelanggan',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: true,
        editable: false,
        width: 300,
        hiddenName: 'kd_pelanggan',
        emptyText: 'Pilih Pelanggan'
    });
    /**
     * end of combo pelanggan
     */


    var storeCboWaktuTop = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['waktu_top'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("pembelian_create_po_bonus_controller/get_term_of_payment_by_supplier") ?>',
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

    var mask = new Ext.LoadMask(Ext.getBody(), {msg: 'Loading data...', store: storePembelianCreateaPoBonus_cpob});

    var cboWaktuTop_cpob = new Ext.form.ComboBox({
        fieldLabel: 'Term Of Payment <span class="asterix">*</span>',
        id: 'id_cbo_waktu_top_cpob',
        store: storeCboWaktuTop,
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
                var kd_supplier = Ext.getCmp('id_cbo_supplier_cpob').getValue();
                var wkt_top = this.getValue();
                console.log(wkt_top);
                var pkp = Ext.getCmp('id_pkp_supplier_cpob').getValue();
                if (pkp == 'YA') {
                    pkp = 1;
                } else {
                    pkp = 2;
                }

                var kd_peruntukan = 0;
                if (Ext.getCmp('id_peruntukan_distribusi_cpob').getValue()) {
                    kd_peruntukan = 1;
                }

                var urlgetrodetail = '<?= site_url("pembelian_create_po_bonus_controller/get_ro_detail_by_supplier") ?>/' + kd_supplier + '/' + wkt_top + '/' + pkp + '/' + kd_peruntukan;
                gridPembelianCreatePoBonus_cpob.store.proxy = new Ext.data.HttpProxy({
                    url: urlgetrodetail,
                    method: 'POST'
                });

                gridPembelianCreatePoBonus_cpob.store.load({
                    callback: function(r, options, response) {
                        if (r.length == 0) {

                            Ext.getCmp('id_jumlah_cpob').setValue("0");
                            Ext.getCmp('id_diskon_persen_cpob').setValue("0");
                            Ext.getCmp('id_diskon_rp_cpob').setValue("0");
                            Ext.getCmp('id_sub_jumlah_cpob').setValue("0");
                            Ext.getCmp('id_ppn_rp_cpob').setValue("0");
                            Ext.getCmp('id_total_cpob').setValue("0");
                            Ext.getCmp('id_sisa_bayar_cpob').setValue("0");
                        } else {

                            var hrg_nett_exc = Ext.getCmp('grid_harga_cpob').getValue();

                            var jumlah = 0;
                            var diskon_persen = 0;
                            var diskon_rp = 0;
                            var sub_jumlah = 0;
                            //var ppn_persen = 10;
                            var ppn_rp = 0;
                            var grand_total = 0;
                            storePembelianCreateaPoBonus_cpob.each(function(node) {
                                jumlah += parseFloat(node.data.jumlah);
                            });

                            sub_jumlah = jumlah;
                            ppn_rp = Math.floor(Ext.getCmp('id_ppn_persen_cpob').getValue() * sub_jumlah) / 100;
                            grand_total = sub_jumlah + ppn_rp;


                            jumlah = Math.round(jumlah);
                            sub_jumlah = Math.round(sub_jumlah);
                            ppn_rp = Math.round(ppn_rp);
                            grand_total = Math.round(grand_total);

                            Ext.getCmp('id_jumlah_cpob').setValue(jumlah);
                            Ext.getCmp('id_diskon_persen_cpob').setValue(diskon_persen);
                            Ext.getCmp('id_diskon_rp_cpob').setValue(diskon_rp);
                            Ext.getCmp('id_sub_jumlah_cpob').setValue(sub_jumlah);
                            //Ext.getCmp('pcpo_ppn_persen').setValue(ppn_persen);
                            Ext.getCmp('id_ppn_rp_cpob').setValue(ppn_rp);
                            Ext.getCmp('id_total_cpob').setValue(grand_total);
                            var sisa_bayar = grand_total - Ext.getCmp('id_dp_cpob').getValue();
                            Ext.getCmp('id_sisa_bayar_cpob').setValue(sisa_bayar);

                            storePembelianCreateaPoBonus_cpob.each(function(node) {
                                if (node.data.validasi_pr == 1) {
                                    Ext.Msg.show({
                                        title: 'Error',
                                        msg: "Ada Outstanding PR dengan Kode Produk " + node.data.kd_produk,
                                        modal: true,
                                        icon: Ext.Msg.ERROR,
                                        buttons: Ext.Msg.OK,
                                    });
                                }
                                if (node.data.validasi_hj == 1) {
                                    Ext.Msg.show({
                                        title: 'Error',
                                        msg: "Harga Jual Untuk Kode Produk " + node.data.kd_produk + " masih kosong",
                                        modal: true,
                                        icon: Ext.Msg.ERROR,
                                        buttons: Ext.Msg.OK,
                                    });
                                }
                            });
                        }
                    }
                });
            }
        }
    });


    var storePembelianCreateaPoBonus_cpob = new Ext.data.Store({
        autoSave: false,
        reader: new Ext.data.JsonReader({
            fields: [
                {name: 'no_ro', allowBlank: false, type: 'text'},
                {name: 'tgl_ro', allowBlank: false, type: 'text'},
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
                {name: 'validasi_hj', allowBlank: false, type: 'int'},
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




    var headerCreateaPoBonus_cpob = {
        layout: 'column',
        border: false,
        items: [{
                columnWidth: .4,
                layout: 'form',
                border: false,
                labelWidth: 110,
                defaults: {labelSeparator: ''},
                items: [cboSupplier_cpob,
                    {
                        xtype: 'textfield',
                        fieldLabel: 'Nama Supplier',
                        name: 'nama_supplier_cpob',
                        fieldClass: 'readonly-input',
                        readOnly: true,
                        id: 'id_nama_supplier_cpob',
                        anchor: '90%',
                        value: ''
                    }, {
                        xtype: 'textfield',
                        fieldLabel: 'PIC Supplier',
                        name: 'pic_cpob',
                        fieldClass: 'readonly-input',
                        readOnly: true,
                        id: 'id_pic_supplier_cpob',
                        anchor: '90%',
                        value: ''
                    }, {
                        xtype: 'textfield',
                        fieldLabel: 'Status PKP',
                        name: 'pkp_cpob',
                        fieldClass: 'readonly-input',
                        readOnly: true,
                        id: 'id_pkp_supplier_cpob',
                        anchor: '90%',
                        value: ''
                    }, {
                        xtype: 'hidden',
                        fieldLabel: 'Alamat Kirim',
                        name: 'alamat_cpob',
                        id: 'id_alamat_supplier_cpob',
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
                        fieldLabel: 'No PO',
                        name: 'no_po_cpob',
                        readOnly: true,
                        fieldClass: 'readonly-input',
                        id: 'id_no_po_cpob',
                        maxLength: 255,
                        anchor: '90%',
                        value: ''
                    },
                    cboWaktuTop_cpob,
                    {
                        xtype: 'datefield',
                        fieldLabel: 'Masa Berlaku <span class="asterix">*</span>',
                        name: 'tgl_berlaku_po_cpob',
                        allowBlank: false,
                        format: 'd-m-Y',
                        editable: false,
                        id: 'id_tgl_berlaku_po_cpob',
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
                        xtype: 'datefield',
                        format: 'd-m-Y',
                        value: '',
                        fieldLabel: 'Tanggal',
                        name: 'tanggal_po_cpob',
                        //fieldClass:'readonly-input',
                        //readOnly:true,
                        allowBlank: false,
                        id: 'id_tanggal_po_cpob',
                        anchor: '90%',
                        //minValue : (new Date()).clearTime() ,
                        maxValue: (new Date()).clearTime()
                    }, {
                        fieldLabel: 'Peruntukan <span class="asterix">*</span>',
                        xtype: 'radiogroup',
                        columnWidth: [.5, .5],
                        allowBlank: false,
                        items: [{
                                boxLabel: 'Supermarket',
                                name: 'kd_peruntukan_cpob',
                                inputValue: '0',
                                id: 'id_peruntukan_supermarket_cpob',
                                checked: true
                            }, {
                                boxLabel: 'Distribusi',
                                name: 'kd_peruntukan_cpob',
                                inputValue: '1',
                                id: 'id_peruntukan_distribusi_cpob'
                            }]
                    }, {
                        xtype: 'textfield',
                        fieldLabel: 'Order By',
                        name: 'order_by_cpob',
                        readOnly: false,
                        fieldClass: 'readonly-input',
                        id: 'id_order_by_cpob',
                        anchor: '90%',
                        value: ''
                    }, ]
            }
        ]
    }


    /**
     * deklarasi main grid
     */
    var editorPembelianCreatePoBonus_cpob = new Ext.ux.grid.RowEditor({
        saveText: 'Update'
    });

    var gridPembelianCreatePoBonus_cpob = new Ext.grid.GridPanel({
        store: storePembelianCreateaPoBonus_cpob,
        stripeRows: true,
        height: 200,
        frame: true,
        border: true,
        loadMask: true,
        plugins: [editorPembelianCreatePoBonus_cpob],
        columns: [new Ext.grid.RowNumberer({width: 30}),
            {
                header: 'No PR',
                dataIndex: 'no_ro',
                width: 110,
                sortable: true,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'grid_no_ro_cpob'
                })

            }, {
                header: 'Tanggal PR',
                dataIndex: 'tgl_ro',
                width: 110,
                sortable: true,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    format: 'd-m-Y',
                    id: 'grid_tgl_ro_cpob'
                })

            }, {
                header: 'Kode',
                dataIndex: 'kd_produk',
                width: 90,
                sortable: true,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'grid_kode_cpob'
                })

            }, {
                header: 'Nama Barang',
                dataIndex: 'nama_produk',
                width: 250,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'grid_nama_produk_cpob'
                })
            }, {
                header: 'Satuan',
                dataIndex: 'nm_satuan',
                width: 60,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'grid_nm_satuan_cpob'
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
                    id: 'grid_qty_adj_cpob',
                    readOnly: true,
                }
            }, {
                xtype: 'numbercolumn',
                header: 'Qty PO',
                dataIndex: 'qty_po',
                width: 50,
                align: 'center',
                sortable: true,
                format: '0,0',
                editor: {
                    xtype: 'numberfield',
                    id: 'grid_qty_cpob',
                    readOnly: true,
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
                    id: 'grid_qty_cpob',
                    allowBlank: false,
                    selectOnFocus: true,
                    listeners: {
                        'change': function() {
                            var qty_realisasi = Ext.getCmp('grid_qty_adj_cpob').getValue() - Ext.getCmp('grid_qty_cpob').getValue();
                            if (this.getValue() > qty_realisasi) {
                                Ext.Msg.show({
                                    title: 'Error',
                                    msg: 'Qty sisa tidak boleh lebih besar dari Qty PR-Qty PO',
                                    modal: true,
                                    icon: Ext.Msg.ERROR,
                                    buttons: Ext.Msg.OK,
                                    fn: function(btn) {
                                        if (btn == 'ok') {
                                            Ext.getCmp('grid_qty_adj_cpob').reset()
                                        }
                                    }
                                });
                                return;
                            }

                            var jumlah = this.getValue() * Ext.getCmp('grid_harga_exc_cpob').getRawValue();
                            Ext.getCmp('grid_jumlah_cpob').setValue(jumlah);
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
                    id: 'grid_hrg_supplier_cpob',
                    readOnly: true,
                }
            }, {
                hidden: true,
                dataIndex: 'disk_persen_supp1_po',
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'grid_disk_persen_supp1_cpob'
                })
            }, {
                hidden: true,
                dataIndex: 'disk_persen_supp2_cpob',
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'grid_disk_persen_supp2_po_cpob'
                })
            }, {
                hidden: true,
                dataIndex: 'disk_persen_supp3_po',
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'grid_disk_persen_supp3_po_cpob'
                })
            }, {
                hidden: true,
                dataIndex: 'disk_persen_supp4_po',
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'grid_disk_persen_supp4_po_cpob'
                })
            }, {
                hidden: true,
                dataIndex: 'disk_amt_supp1_po',
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'grid_disk_amt_supp1_po_cpob'
                })
            }, {
                hidden: true,
                dataIndex: 'disk_amt_supp2_po',
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'grid_disk_amt_supp2_po_cpob'
                })
            }, {
                hidden: true,
                dataIndex: 'disk_amt_supp3_po',
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'grid_disk_amt_supp3_po_cpob'
                })
            }, {
                hidden: true,
                dataIndex: 'disk_amt_supp4_po',
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'grid_disk_amt_supp4_po_cpob'
                })
            }, {
                hidden: true,
                dataIndex: 'disk_amt_supp5_po',
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'grid_disk_amt_supp5_po_cpob'
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
                    id: 'grid_disk_persen_supp1_cpob',
                    readOnly: true,
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
                    id: 'grid_disk_persen_supp2_cpob',
                    readOnly: true,
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
                    id: 'grid_disk_persen_supp3_cpob',
                    readOnly: true,
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
                    id: 'grid_disk_persen_supp4_cpob',
                    readOnly: true,
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
                    id: 'grid_disk_persen_supp5_cpob',
                    readOnly: true,
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
                    id: 'grid_total_diskon_cpob',
                    readOnly: true,
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
                    id: 'grid_harga_cpob',
                    readOnly: true,
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
                    id: 'grid_harga_exc_cpob',
                    readOnly: true,
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
                    id: 'grid_jumlah_cpob',
                    readOnly: true,
                }
            }],
        tbar: [{
                ref: '../removeBtn',
                icon: BASE_ICONS + 'delete.gif',
                text: 'Remove',
                disabled: true,
                handler: function() {

                    editorPembelianCreatePoBonus_cpob.stopEditing();
                    var s = gridPembelianCreatePoBonus_cpob.getSelectionModel().getSelections();
                    for (var i = 0, r; r = s[i]; i++) {
                        storePembelianCreateaPoBonus_cpob.remove(r);
                    }

                    var jumlah = 0;

                    storePembelianCreateaPoBonus_cpob.each(function(node) {
                        jumlah += parseFloat(node.data.jumlah);
                    });

                    var diskon_persen = Ext.getCmp('id_diskon_persen_cpob').getValue();
                    var diskon_rp = diskon_persen * jumlah;
                    var sub_jumlah = jumlah - diskon_rp;
                    var ppn_persen = Ext.getCmp('id_ppn_persen_cpob').getValue();
                    var ppn_rp = Math.floor(ppn_persen * sub_jumlah) / 100;
                    var grand_total = sub_jumlah + ppn_rp;

                    jumlah = Math.round(jumlah);
                    sub_jumlah = Math.round(sub_jumlah);
                    ppn_rp = Math.round(ppn_rp);
                    grand_total = Math.round(grand_total);

                    Ext.getCmp('id_jumlah_cpob').setValue(jumlah);
                    Ext.getCmp('id_diskon_persen_cpob').setValue(diskon_persen);
                    Ext.getCmp('id_diskon_rp_cpob').setValue(diskon_rp);
                    Ext.getCmp('id_sub_jumlah_cpob').setValue(sub_jumlah);
                    Ext.getCmp('id_ppn_persen_cpob').setValue(ppn_persen);
                    Ext.getCmp('id_ppn_rp_cpob').setValue(ppn_rp);
                    Ext.getCmp('id_total_cpob').setValue(grand_total);
                    var sisa_bayar = grand_total - Ext.getCmp('id_dp_cpob').getValue();
                    Ext.getCmp('id_sisa_bayar_cpob').setValue(sisa_bayar);
                }
            }]
    });

    gridPembelianCreatePoBonus_cpob.getSelectionModel().on('selectionchange', function(sm) {
        gridPembelianCreatePoBonus_cpob.removeBtn.setDisabled(sm.getCount() < 1);
    });

    var footerCreatePoBOnus_cpob = {
        layout: 'column',
        border: false,
        items: [{
                columnWidth: .6,
                style: 'margin:6px 3px 0 0;',
                layout: 'form',
                labelWidth: 120,
                items: [cboPelanggan_cpob,
                    {
                        xtype: 'textfield',
                        name: 'pic_penerima_cpob',
                        id: 'id_pic_penerima_cpob',
                        fieldLabel: 'PIC Penerima <span class="asterix">*</span>',
                        allowBlank: false,
                        width: 300,
                        value: ''
                    }, {
                        xtype: 'textarea',
                        fieldLabel: 'Alamat Penerima <span class="asterix">*</span>',
                        allowBlank: false,
                        name: 'alamat_penerima_cpob',
                        id: 'id_alamat_penerima_cpob',
                        width: 300
                    }, {
                        xtype: 'textarea',
                        fieldLabel: 'Remark',
                        name: 'remark_cpob',
                        id: 'id_remark_cpob',
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
                                name: 'jumlah_cpob',
                                readOnly: true,
                                id: 'id_jumlah_cpob',
                                anchor: '90%',
                                fieldClass: 'readonly-input number',
                                value: '0'
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
                                        name: 'diskon_persen_cpob',
                                        id: 'id_diskon_persen_cpob',
                                        fieldClass: 'number',
                                        width: 50,
                                        value: '0',
                                        maxValue: 100,
                                        selectOnFocus: true,
                                        listeners: {
                                            'change': function() {


                                                var jumlah = Ext.getCmp('grid_jumlah_cpob').getValue();
                                                var diskon_rp = (this.getValue() * jumlah) / 100;
                                                var sub_jumlah = jumlah - diskon_rp;
                                                var ppn_persen = Ext.getCmp('id_ppn_persen_cpob').getValue();
                                                var ppn_rp = Math.floor(ppn_persen * sub_jumlah) / 100;
                                                var grand_total = sub_jumlah + ppn_rp;

                                                Ext.getCmp('id_diskon_rp_cpob').setValue(diskon_rp);
                                                Ext.getCmp('id_sub_jumlah_cpob').setValue(sub_jumlah);
                                                Ext.getCmp('id_ppn_rp_cpob').setValue(ppn_rp);
                                                Ext.getCmp('id_total_cpob').setValue(grand_total);
                                                var sisa_bayar = grand_total - Ext.getCmp('id_dp_cpob').getValue();
                                                Ext.getCmp('id_sisa_bayar_cpob').setValue(sisa_bayar);
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
                                        name: 'diskon_rp_cpob',
                                        id: 'id_diskon_rp_cpob',
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
                                name: 'sub_jumlah_cpob',
                                id: 'id_sub_jumlah_cpob',
                                anchor: '90%',
                                cls: 'vertical-space',
                                readOnly: true,
                                fieldClass: 'readonly-input number',
                                labelStyle: 'margin-top:10px;',
                                value: '0',
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
                                        name: 'ppn_persen_cpob',
                                        id: 'id_ppn_persen_cpob',
                                        readOnly: true,
                                        fieldClass: 'readonly-input number',
                                        width: 50,
                                        selectOnFocus: true,
                                        value: '10',
                                        maxValue: 100,
                                        listeners: {
                                            'change': function() {
                                                var sub_jumlah = Ext.getCmp('id_sub_jumlah_cpob').getValue();
                                                var ppn_rp = Math.floor(this.getValue() * sub_jumlah) / 100;
                                                var grand_total = sub_jumlah + ppn_rp;

                                                Ext.getCmp('id_ppn_rp_cpob').setValue(ppn_rp);
                                                Ext.getCmp('id_total_cpob').setValue(grand_total);
                                                var sisa_bayar = grand_total - Ext.getCmp('id_dp_cpob').getValue();
                                                Ext.getCmp('id_sisa_bayar_cpob').setValue(sisa_bayar);

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
                                        name: 'ppn_rp_cpob',
                                        id: 'id_ppn_rp_cpob',
                                        currencySymbol: '',
                                        fieldClass: 'readonly-input number',
                                        readOnly: true,
                                        //anchor: '100%',
                                        value: '0',
                                    }
                                ]
                            }, {
                                xtype: 'numericfield',
                                currencySymbol: '',
                                fieldLabel: '<b>Grand Total</b>',
                                name: 'total_cpob',
                                cls: 'vertical-space',
                                readOnly: true,
                                id: 'id_total_cpob',
                                anchor: '90%',
                                fieldClass: 'readonly-input bold-input number',
                                labelStyle: 'margin-top:10px;',
                                value: '0'
                            }, {
                                xtype: 'numericfield',
                                currencySymbol: '',
                                fieldLabel: '',
                                name: 'dp_cpob',
                                hidden: true,
                                id: 'id_dp_cpob',
                                anchor: '90%',
                                cls: 'vertical-space',
                                labelStyle: 'margin-top:10px;',
                                fieldClass: 'number',
                                value: '0',
                                listeners: {
                                    'change': function() {
                                        var total = Ext.getCmp('id_total_cpob').getValue();
                                        var sisa_bayar = total - this.getValue();

                                        Ext.getCmp('id_sisa_bayar_cpob').setValue(sisa_bayar);
                                    }
                                }
                            }, {
                                xtype: 'numericfield',
                                currencySymbol: '',
                                fieldLabel: 'Sisa Bayar',
                                name: 'sisa_bayar_cpob',
                                readOnly: true,
                                fieldClass: 'readonly-input number',
                                id: 'id_sisa_bayar_cpob',
                                anchor: '90%',
                                value: '0'
                            }
                        ]
                    }
                ]
            }]
    }


    //Form Panel
    var createPoBonus_cpob = new Ext.FormPanel({
        id: 'id_create_purchase_order_bonus',
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
                items: [headerCreateaPoBonus_cpob]
            }, gridPembelianCreatePoBonus_cpob,
            footerCreatePoBOnus_cpob],
        buttons: [
            {
                text: 'Save',
                formBind: true,
                handler: function() {
                    if (Ext.getCmp('id_total_cpob').getValue() === 0) {
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

                                var detailpembeliancreatepo = new Array();
                                var error = 0;
                                var error_top = 0;
                                var error_tgl = 0;
                                var top = Ext.getCmp('id_cbo_waktu_top_cpob').getValue();
                                var tgl = Ext.getCmp('id_tanggal_po_cpob').getValue();
                                storePembelianCreateaPoBonus_cpob.each(function(node) {
                                    if (node.data.waktu_top == top) {
                                        if (node.data.qty > (node.data.qty_adj - node.data.qty_po)) {
                                            error++;
                                        }
                                        /*if(node.data.tgl_ro > tgl){
                                         error_tgl++;
                                         }*/ else {
                                            if (node.data.jumlah > 0) {
                                                detailpembeliancreatepo.push(node.data);
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
                                        buttons: Ext.Msg.OK,
                                    });
                                    return;
                                }

                                if (error_top > 0) {
                                    Ext.Msg.show({
                                        title: 'Error',
                                        msg: error_top + top + 'Cek waktu TOP masing-masing produk!<br/>Produk yang bisa dipilih hanya untuk TOP yang sama',
                                        modal: true,
                                        icon: Ext.Msg.ERROR,
                                        buttons: Ext.Msg.OK
                                    });
                                    return;
                                }
                                /* if(error_tgl > 0){
                                 Ext.Msg.show({
                                 title: 'Error',
                                 msg: 'Cek Tanggal !<br/>Tanggal Receive tidak boleh lebih rendah dari Tanggal Request',
                                 modal: true,
                                 icon: Ext.Msg.ERROR,
                                 buttons: Ext.Msg.OK	                           
                                 });
                                 return;
                                 }*/
                                Ext.getCmp('id_create_purchase_order_bonus').getForm().submit({
                                    url: '<?= site_url("pembelian_create_po_bonus_controller/update_row") ?>',
                                    scope: this,
                                    params: {
                                        detail: Ext.util.JSON.encode(detailpembeliancreatepo),
                                        _dp: Ext.getCmp('id_dp_cpob').getValue(),
                                        _jumlah: Ext.getCmp('id_jumlah_cpob').getValue(),
                                        _diskon_rp: Ext.getCmp('id_diskon_rp_cpob').getValue(),
                                        _ppn_persen: Ext.getCmp('id_ppn_persen_cpob').getValue(),
                                        _ppn_rp: Ext.getCmp('id_ppn_rp_cpob').getValue(),
                                        _total: Ext.getCmp('id_total_cpob').getValue()
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
                                                    // winpembeliancreatepoprint.show();
                                                    // Ext.getDom('pembeliancreatepoprint').src = r.printUrl;
                                                }
                                            }
                                        });

                                        clearPembelianCreatePOBonus();
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
                        }
                    });

                }
            }, {
                text: 'reset',
                handler: function() {
                    clearPembelianCreatePOBonus();
                }
            }
        ]
    });


    createPoBonus_cpob.on('afterrender', function() {
        this.getForm().load({
            url: '<?= site_url("pembelian_create_po_bonus_controller/get_form") ?>',
            success: function(form, action) {
                var r = Ext.util.JSON.decode(action.response.responseText);
                if (r.data.user_peruntukan === "0") {
                    Ext.getCmp('id_peruntukan_supermarket_cpob').setValue(true);
                    Ext.getCmp('id_peruntukan_supermarket_cpob').show();
                    Ext.getCmp('id_peruntukan_distribusi_cpob').hide();
                } else if (r.data.user_peruntukan === "1") {
                    Ext.getCmp('pcpo_peruntukan_distribusi').setValue(true);
                    Ext.getCmp('id_peruntukan_supermarket_cpob').hide();
                    Ext.getCmp('id_peruntukan_distribusi_cpob').show();
                } else {
                    Ext.getCmp('id_peruntukan_supermarket_cpob').setValue(true);
                    Ext.getCmp('id_peruntukan_supermarket_cpob').show();
                    Ext.getCmp('id_peruntukan_distribusi_cpob').show();
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
                        if (btn == 'ok' && de.errMsg == 'Session Expired') {
                            window.location = '<?= site_url("auth/login") ?>';
                        }
                    }
                });
            }
        });
    });


    function clearPembelianCreatePOBonus() {
        Ext.getCmp('id_create_purchase_order_bonus').getForm().reset();
        Ext.getCmp('id_create_purchase_order_bonus').getForm().load({
            url: '<?= site_url("pembelian_create_po_bonus_controller/get_form") ?>',
            success: function(form, action) {
                var r = Ext.util.JSON.decode(action.response.responseText);
                if (r.data.user_peruntukan === "0") {
                    Ext.getCmp('id_peruntukan_supermarket_cpob').setValue(true);
                    Ext.getCmp('id_peruntukan_supermarket_cpob').show();
                    Ext.getCmp('id_peruntukan_distribusi_cpob').hide();
                } else if (r.data.user_peruntukan === "1") {
                    Ext.getCmp('pcpo_peruntukan_distribusi').setValue(true);
                    Ext.getCmp('id_peruntukan_supermarket_cpob').hide();
                    Ext.getCmp('id_peruntukan_distribusi_cpob').show();
                } else {
                    Ext.getCmp('id_peruntukan_supermarket_cpob').setValue(true);
                    Ext.getCmp('id_peruntukan_supermarket_cpob').show();
                    Ext.getCmp('id_peruntukan_distribusi_cpob').show();
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
                        if (btn == 'ok' && de.errMsg == 'Session Expired') {
                            window.location = '<?= site_url("auth/login") ?>';
                        }
                    }
                });
            }
        });
        storePembelianCreateaPoBonus_cpob.removeAll();
    }

</script>
