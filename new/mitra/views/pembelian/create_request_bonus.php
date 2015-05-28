<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">

    /**
     * declaraition of grid kd supplier
     */
    var storeGridSupplier_crb = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_supplier', 'nama_supplier'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("create_request_bonus_controller/search_supplier") ?>',
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

    /**
     * declaration of grid create request bonus data store
     */
    var storePembelianCreateRequestBonus_crb = new Ext.data.Store({
        autoSave: false,
        reader: new Ext.data.JsonReader({
            fields: [
                {name: 'kd_produk', allowBlank: false, type: 'int'},
                {name: 'qty', allowBlank: false, type: 'int'}
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

    /**
     * declaration of grid produk on row combo (copied from pembelian/create_request)
     */
    var storeGridProduk_crb = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['waktu_top', 'kd_produk', 'kd_produk_supp', 'kd_produk_lama', 'nama_produk', 'min_stok', 'max_stok', 'jml_stok', 'nm_satuan', 'min_order', 'is_kelipatan_order'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("create_request_bonus_controller/search_produk_by_supplier") ?>',
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

    /**
     * search field declaration for grid combo produk
     */
    var searchGridCboProduk_crb = new Ext.app.SearchField({
        store: storeGridProduk_crb,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_search_grid_combo_produk'
    });


    var gridCboProduk_crb = new Ext.grid.GridPanel({
        store: storeGridProduk_crb,
        stripeRows: true,
        frame: true,
        border: true,
        columns: [{
                header: 'Kode produk',
                dataIndex: 'kd_produk',
                width: 100,
                sortable: true
            }, {
                header: 'Kode produk supplier',
                dataIndex: 'kd_produk_supp',
                width: 100,
                sortable: true
            }, {
                header: 'Kode produk lama',
                dataIndex: 'kd_produk_lama',
                width: 100,
                sortable: true
            }, {
                header: 'Nama produk',
                dataIndex: 'nama_produk',
                width: 400,
                sortable: true
            }, {
                header: 'Satuan',
                dataIndex: 'nm_satuan',
                width: 80
            }, {
                header: 'Min.Stok',
                dataIndex: 'min_stok',
                width: 80,
                sortable: true
            }, {
                header: 'Max.Stok',
                dataIndex: 'max_stok',
                width: 80,
                sortable: true
            }, {
                header: 'Jml.Stok Pot.SO',
                dataIndex: 'jml_stok',
                width: 120,
                sortable: true
            }, {
                header: 'TOP',
                dataIndex: 'waktu_top',
                width: 50,
                sortable: true
            }],
        tbar: new Ext.Toolbar({
            items: [searchGridCboProduk_crb]
        }),
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();

                var supermarket = Ext.getCmp('id_radio_peruntukan_supermarket_crb').getValue();
                var distribusi = Ext.getCmp('id_radio_peruntukan_distribusi_crb').getValue();

                if (supermarket) {
                    kd_peruntukkan = '0';
                } else if (distribusi) {
                    kd_peruntukkan = '1';
                }
                if (sel.length > 0) {
                    Ext.Ajax.request({
                        url: '<?= site_url("create_request_bonus_controller/search_produk_by_supplier") ?>',
                        method: 'POST',
                        params: {
                            kd_supplier: Ext.getCmp('id_combo_supplier_crb').getValue(),
                            kd_peruntukan: kd_peruntukkan,
                            kd_produk: sel[0].get('kd_produk'),
                            action: 'validate'
                        },
                        callback: function(opt, success, responseObj) {
                            var de = Ext.util.JSON.decode(responseObj.responseText);
                            if (de.success == true) {

                                var _ada = false;

                                storePembelianCreateRequestBonus_crb.each(function(record) {
                                    if (record.get('kd_produk') === sel[0].get('kd_produk')) {
                                        _ada = true;
                                    }
                                });

                                if (_ada) {
                                    Ext.Msg.show({
                                        title: 'Error',
                                        msg: 'Kode Barang yang sama tidak boleh dipilih lebih dari satu kali',
                                        modal: true,
                                        icon: Ext.Msg.ERROR,
                                        buttons: Ext.Msg.OK,
                                        fn: function(btn) {
                                            if (btn == 'ok') {
                                                Ext.getCmp('id_combo_produk_crb').reset();
                                            }
                                        }
                                    });
                                    Ext.MessageBox.getDialog().getEl().setStyle('z-index', '80000');
                                    Ext.getCmp('id_combo_produk_crb').focus();
                                    return;
                                }

                                Ext.getCmp('id_combo_produk_crb').setValue(sel[0].get('kd_produk'));
                                Ext.getCmp('id_grid_txt_nama_produk_crb').setValue(sel[0].get('nama_produk'));
                                Ext.getCmp('id_grid_txt_min_stock_crb').setValue(sel[0].get('min_stok'));
                                Ext.getCmp('id_grid_txt_max_stock_crb').setValue(sel[0].get('max_stok'));
                                Ext.getCmp('id_grid_txt_min_order_crb').setValue(sel[0].get('min_order'));
                                Ext.getCmp('id_grid_txt_order_kelipatan_crb').setValue(sel[0].get('is_kelipatan_order'));
                                Ext.getCmp('id_grid_txt_jml_stock_crb').setValue(sel[0].get('jml_stok'));
                                Ext.getCmp('id_grid_txt_satuan_crb').setValue(sel[0].get('nm_satuan'));
                                Ext.getCmp('id_grid_txt_waktu_top_crb').setValue(sel[0].get('waktu_top'));
                                Ext.getCmp('id_grid_txt_qty_crb').setValue(0);
                                Ext.getCmp('id_grid_txt_qty_crb').focus();
                            } else {
                                Ext.getCmp('id_combo_produk_crb').setValue('');
                                Ext.getCmp('id_grid_txt_nama_produk_crb').setValue('');
                                Ext.getCmp('id_grid_txt_min_stock_crb').setValue('');
                                Ext.getCmp('id_grid_txt_max_stock_crb').setValue('');
                                Ext.getCmp('id_grid_txt_min_order_crb').setValue('');
                                Ext.getCmp('id_grid_txt_order_kelipatan_crb').setValue('');
                                Ext.getCmp('id_grid_txt_jml_stock_crb').setValue('');
                                Ext.getCmp('id_grid_txt_satuan_crb').setValue('');
                                Ext.getCmp('id_grid_txt_waktu_top_crb').setValue('');
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
                                Ext.MessageBox.getDialog().getEl().setStyle('z-index', '80000');

                            }
                        }
                    });

                    menuCmbProduk_crb.hide();
                }
            }
        }
    });


    var menuCmbProduk_crb = new Ext.menu.Menu();
    menuCmbProduk_crb.add(new Ext.Panel({
        title: 'Pilih Barang',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 600,
        height: 250,
        closeAction: 'hide',
        plain: true,
        items: [gridCboProduk_crb],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menuCmbProduk_crb.hide();
                }
            }]
    }));


    var menuProdukScan_crb = new Ext.Window();
    menuProdukScan_crb.add(new Ext.Panel({
        title: 'Scan Barcode Produk',
        layout: 'form',
        border: false,
        frame: true,
        autoScroll: true,
        bodyStyle: 'padding-right:20px;',
        labelWidth: 130,
        buttonAlign: 'left',
        width: 400,
        height: 250,
        closeAction: 'hide',
        //plain: true,
        //modal: true,
        //monitorValid: true,       
        items: [{
                xtype: 'textfield',
                fieldLabel: 'Scan Barcode',
                name: 'scan_barcode',
                id: 'id_txt_scan_barcode_kode_crb',
                anchor: '90%',
                value: '',
                listeners: {
                    specialKey: function(field, e) {
                        if (e.getKey() == e.RETURN || e.getKey() == e.ENTER) {

                            Ext.Ajax.request({
                                url: '<?= site_url("create_request_bonus_controller/search_produk_by_supplier") ?>',
                                method: 'POST',
                                params: {
                                    //kd_supplier: Ext.getCmp('pcr_kd_supplier').getValue(),
                                    query: Ext.getCmp('id_txt_scan_barcode_kode_crb').getValue(),
                                    sender: 'scan'
                                },
                                callback: function(opt, success, responseObj) {
                                    var scn = Ext.util.JSON.decode(responseObj.responseText);
                                    if (scn.success == true) {

                                        var _ada = false;

                                        storePembelianCreateRequestBonus_crb.each(function(record) {
                                            if (record.get('kd_produk') === scn.data.kd_produk) {
                                                _ada = true;
                                            }
                                        });

                                        if (_ada) {
                                            Ext.Msg.show({
                                                title: 'Error',
                                                msg: 'Kode Barang yang sama tidak boleh dipilih lebih dari satu kali',
                                                modal: true,
                                                icon: Ext.Msg.ERROR,
                                                buttons: Ext.Msg.OK,
                                                fn: function(btn) {
                                                    if (btn == 'ok') {
                                                        Ext.getCmp('id_txt_scan_barcode_kode_crb').reset();
                                                    }
                                                }
                                            });
                                            Ext.MessageBox.getDialog().getEl().setStyle('z-index', '80000');
                                            Ext.getCmp('id_txt_scan_barcode_kode_crb').focus();
                                            return;
                                        }

                                        Ext.getCmp('id_txt_kd_produk_scan_crb').setValue(scn.data.kd_produk);
                                        Ext.getCmp('id_txt_kd_produk_supplier_scan_crb').setValue(scn.data.kd_produk_supp);
                                        Ext.getCmp('id_txt_kd_produk_lama_crb').setValue(scn.data.kd_produk_lama);
                                        Ext.getCmp('id_txt_nama_produk_scan_crb').setValue(scn.data.nama_produk);
                                    }
                                }
                            });
                            if (Ext.getCmp('id_txt_kd_produk_scan_crb').getValue() != '') {
                                Ext.getCmp('id_submit_button_scan_barcode_crb').focus();
                            }

                        }
                    }
                }
            }, {
                xtype: 'textfield',
                fieldLabel: 'Kode Produk',
                name: 'txt_kd_produk_scan_crb',
                readOnly: true,
                fieldClass: 'readonly-input',
                id: 'id_txt_kd_produk_scan_crb',
                anchor: '90%',
                value: ''
            }, {
                xtype: 'textfield',
                fieldLabel: 'Kode Produk Supplier',
                name: 'txt_kd_produk_supplier_scan_crb',
                readOnly: true,
                fieldClass: 'readonly-input',
                id: 'id_txt_kd_produk_supplier_scan_crb',
                anchor: '90%',
                value: ''
            }, {
                xtype: 'textfield',
                fieldLabel: 'Kode Produk Lama',
                name: 'txt_kd_produk_lama_crb',
                readOnly: true,
                fieldClass: 'readonly-input',
                id: 'id_txt_kd_produk_lama_crb',
                anchor: '90%',
                value: ''
            }, {
                xtype: 'textfield',
                fieldLabel: 'Nama Produk',
                name: 'txt_nama_produk_scan_crb',
                readOnly: true,
                fieldClass: 'readonly-input',
                id: 'id_txt_nama_produk_scan_crb',
                anchor: '90%',
                value: ''
            }
        ],
        buttons: [{
                text: 'Submit',
                formBind: true,
                id: 'id_submit_button_scan_barcode_crb',
                handler: function() {
                    Ext.Ajax.request({
                        url: '<?= site_url("create_request_bonus_controller/search_produk_by_supplier") ?>',
                        method: 'POST',
                        params: {
                            kd_supplier: Ext.getCmp('id_combo_supplier_crb').getValue(),
                            query: Ext.getCmp('id_txt_scan_barcode_kode_crb').getValue(),
                            kd_produk: Ext.getCmp('id_txt_kd_produk_scan_crb').getValue(),
                            action: 'validate',
                            sender: 'scan'
                        },
                        callback: function(opt, success, responseObj) {
                            var scn = Ext.util.JSON.decode(responseObj.responseText);
                            if (scn.success == true) {
                                Ext.getCmp('id_combo_produk_crb').setValue(scn.data.kd_produk);
                                Ext.getCmp('id_grid_txt_nama_produk_crb').setValue(scn.data.nama_produk);
                                Ext.getCmp('id_grid_txt_min_stock_crb').setValue(scn.data.min_stok);
                                Ext.getCmp('id_grid_txt_max_stock_crb').setValue(scn.data.max_stok);
                                Ext.getCmp('id_grid_txt_min_order_crb').setValue(scn.data.min_order);
                                Ext.getCmp('id_grid_txt_order_kelipatan_crb').setValue(scn.data.is_kelipatan_order);
                                Ext.getCmp('id_grid_txt_jml_stock_crb').setValue(scn.data.jml_stok);
                                Ext.getCmp('id_grid_txt_satuan_crb').setValue(scn.data.nm_satuan);
                                Ext.getCmp('id_grid_txt_waktu_top_crb').setValue(scn.data.waktu_top);
                                Ext.getCmp('id_grid_txt_qty_crb').setValue(0);
                                Ext.getCmp('id_grid_txt_qty_crb').focus();
                            } else {
                                Ext.getCmp('id_combo_produk_crb').setValue('');
                                Ext.getCmp('id_grid_txt_nama_produk_crb').setValue('');
                                Ext.getCmp('id_grid_txt_min_stock_crb').setValue('');
                                Ext.getCmp('id_grid_txt_max_stock_crb').setValue('');
                                Ext.getCmp('id_grid_txt_min_order_crb').setValue('');
                                Ext.getCmp('id_grid_txt_order_kelipatan_crb').setValue('');
                                Ext.getCmp('id_grid_txt_jml_stock_crb').setValue('');
                                Ext.getCmp('id_grid_txt_satuan_crb').setValue('');
                                Ext.getCmp('id_grid_txt_waktu_top_crb').setValue('');
                                Ext.Msg.show({
                                    title: 'Error',
                                    msg: scn.errMsg,
                                    modal: true,
                                    icon: Ext.Msg.ERROR,
                                    buttons: Ext.Msg.OK,
                                    fn: function(btn) {
                                        if (btn == 'ok' && scn.errMsg == 'Session Expired') {
                                            window.location = '<?= site_url("auth/login") ?>';
                                        }
                                    }
                                });
                                Ext.MessageBox.getDialog().getEl().setStyle('z-index', '80000');

                            }

                            menuProdukScan_crb.hide();
                        }
                    });
                }
            }, {
                text: 'Close',
                handler: function() {
                    menuProdukScan_crb.hide();
                }
            }]
    }));

    /**
     * deklaration of twin combobox produk_crb
     */
    Ext.ux.TwinComboProduk_crb = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            //load store grid
            storeGridProduk_crb.load({
                params: {
                    kd_supplier: Ext.getCmp('id_combo_supplier_crb').getValue()
                }
            });

            var scan = Ext.getCmp('id_chk_scan_barcode_crb').getValue();
            if (scan) {
                //Ext.getCmp('id_chk_scan_barcode_crb').setValue('');

                Ext.getCmp('id_txt_kd_produk_supplier_scan_crb').setValue('');
                Ext.getCmp('id_txt_kd_produk_lama_crb').setValue('');
                Ext.getCmp('id_txt_nama_produk_scan_crb').setValue('');
                Ext.getCmp('id_txt_kd_produk_scan_crb').setValue('');
                //Ext.getCmp('id_txt_kd_produk_scan_crb').reset()
                var win = Ext.WindowMgr;
                // win.zseed='80000';
                win.get(menuProdukScan_crb).show();
                ;
            } else {
                menuCmbProduk_crb.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
            }
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
                trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    menuCmbProduk_crb.on('hide', function() {
        var sf = Ext.getCmp('id_search_grid_combo_produk').getValue();
        if (sf != '') {
            Ext.getCmp('id_search_grid_combo_produk').setValue('');
            searchGridCboProduk_crb.onTrigger2Click();
        }
    });

    /**
     * declaration of produk combo box
     */
//    var comboProduk_crb = new Ext.ux.TwinComboProduk_crb({
//        id: 'id_combo_produk_crb',
//        store: storeGridProduk_crb,
//        mode: 'local',
//        valueField: 'kd_produk',
//        displayField: 'kd_produk',
//        typeAhead: true,
//        triggerAction: 'all',
//        allowBlank: true,
//        editable: false,
//        anchor: '95%',
//        hiddenName: 'kd_produk',
//        emptyText: 'kd_produk'
//    });

    /**
     * declaration search field of grid pelanggan dist
     */
    var searchGridSupplier_crb = new Ext.app.SearchField({
        store: storeGridSupplier_crb,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        id: 'id_search_field_grid_supplier_crb'
    });

    /**
     * grid supplier selection model
     */
    var smGridSupplier_crb = new Ext.grid.CheckboxSelectionModel();

    /**
     * supplier grid declaration
     */
    var gridSupplier_crb = new Ext.grid.GridPanel({
        store: storeGridSupplier_crb,
        stripeRows: true,
        frame: true,
        border: true,
        sm: smGridSupplier_crb,
        columns: [{
                header: 'Kode Supplier',
                dataIndex: 'kd_supplier',
                width: 120,
                sortable: true
            }, {
                header: 'Nama Supplier',
                dataIndex: 'nama_supplier',
                width: 120,
                sortable: true
            }],
        tbar: new Ext.Toolbar({
            items: [searchGridSupplier_crb]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: storeGridSupplier_crb,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('id_combo_supplier_crb').setValue(sel[0].get('kd_supplier'));
                    menuSupplier_crb.hide();
                }
            }
        }
    });

    /**
     * deklarasi menu supplier
     */
    var menuSupplier_crb = new Ext.menu.Menu();
    menuSupplier_crb.add(new Ext.Panel({
        title: 'Pilih Supplier',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridSupplier_crb],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menuSupplier_crb.hide();
                }
            }]
    }));

    /**
     * deklarasi twin combo supplier
     */
    Ext.ux.TwincomboSupplier_crb = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            //load store grid
            storeGridSupplier_crb.load();
            menuSupplier_crb.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
                trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
    menuSupplier_crb.on('hide', function() {
        var sf = Ext.getCmp('id_search_field_grid_supplier_crb').getValue();
        if (sf !== '') {
            Ext.getCmp('id_search_field_grid_supplier_crb').setValue('');
            menuSupplier_crb.onTrigger2Click();
        }
    });

    var comboSupplier_crb = new Ext.ux.TwincomboSupplier_crb({
        fieldLabel: 'Supplier <span class="asterix">*</span>',
        id: 'id_combo_supplier_crb',
        store: storeGridSupplier_crb,
        mode: 'local',
        valueField: 'kd_supplier',
        displayField: 'kd_supplier',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        anchor: '95%',
        hiddenName: 'kd_supplier',
        emptyText: 'Pilih Supplier'
    });
    /**
     * top  header create request bonus
     */
    var headerCreateRequestBonus_crp = {
        layout: 'column',
        border: false,
        buttonAlign: 'left',
        style: 'padding:5px',
        frame: true,
        items: [{
                columnWidth: .5,
                layout: 'form',
                border: false,
                frame: true,
                labelWidth: 100,
                defaults: {labelSeparator: ''},
                items: [
                    {
                        xtype: 'textfield',
                        name: 'txt_no_rb_crb',
                        id: 'id_txt_no_rb_crb',
                        fieldLabel: 'No PR',
                        fieldClass: 'readonly-input',
                        readOnly: true,
                        anchor: '95%'
                    }, comboSupplier_crb, {
                        xtype: 'checkbox',
                        name: 'chk_scan_barcode_crb',
                        id: 'id_chk_scan_barcode_crb',
                        fieldLabel: 'Scan Barcode',
                        anchor: '95%'
                    }
                ]
            }, {
                columnWidth: .5,
                layout: 'form',
                border: false,
                frame: true,
                labelWidth: 100,
                defaults: {labelSeparator: ''},
                items: [
                    {
                        xtype: 'datefield',
                        name: 'date_tanggal_crb',
                        id: 'id_date_tanggal_crb',
                        fieldLabel: 'Tanggal',
                        anchor: '95%',
                        format: 'd-m-Y',
                        value: '',
                        maxValue: (new Date()).clearTime() 
                    }, {
                        xtype: 'textfield',
                        name: 'txt_subject_crb',
                        id: 'id_txt_subject_crb',
                        fieldLabel: 'Subject',
                        anchor: '95%',
                        allowBlank: false,
                        value: ''
                    }, {
                        fieldLabel: 'Peruntukan <span class="asterix">*</span>',
                        xtype: 'radiogroup',
                        name: 'kd_peruntukan',
                        columnWidth: [.5, .5],
                        allowBlank: false,
                        anchor: '90%',
                        items: [{
                                boxLabel: 'Supermarket',
                                name: 'kd_peruntukan_crb',
                                inputValue: '0',
                                id: 'id_radio_peruntukan_supermarket_crb',
                                checked: true
                            }, {
                                boxLabel: 'Distribusi',
                                name: 'kd_peruntukan_crb',
                                inputValue: '1',
                                id: 'id_radio_peruntukan_distribusi_crb'
                            }]
                    }
                ]
            }]
    };

    /**
     * declaraion of the selection model of gridCreateRequestBonus_crb
     */
    var smGridCreateRequestBonus_crb = new Ext.grid.CheckboxSelectionModel();

    var topToolBarGridCreateRequestBonus_crb = new Ext.Toolbar({
        items: [
            {
                icon: BASE_ICONS + 'add.png',
                text: 'Add',
                handler: function() {
                    if (Ext.getCmp('id_combo_supplier_crb').getValue() == '') {
                        Ext.Msg.show({
                            title: 'Error',
                            msg: 'Silahkan pilih supplier terlebih dulu',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK
                        });
                        return;
                    }
                    var rowPembelianCreateRequestBonus = new gridpembeliancreaterequest.store.recordType({
                        kd_produk: '',
                        qty: ''
                    });
                    editorGridCreateRequestBonus_crb.stopEditing();
                    storePembelianCreateRequestBonus_crb.insert(0, rowPembelianCreateRequestBonus);
                    gridCreateRequestBonus_crb.getView().refresh();
                    gridCreateRequestBonus_crb.getSelectionModel().selectRow(0);
                    editorGridCreateRequestBonus_crb.startEditing(0);
                }
            }, {
                ref: '../removeBtn',
                icon: BASE_ICONS + 'delete.gif',
                text: 'Remove',
                disabled: true,
                handler: function() {
                }
            }
        ]
    });

    /**
     * declaration of row editor of gridCreateRequestBonus_crb
     */
    var editorGridCreateRequestBonus_crb = new Ext.ux.grid.RowEditor({
        saveText: 'Update'
    });

    /**
     * declaration of main grid
     */
    var gridCreateRequestBonus_crb = new Ext.grid.GridPanel({
        frame: true,
        border: true,
        stripeRows: true,
        store: storePembelianCreateRequestBonus_crb,
        //sm: smGridCreateRequestBonus_crb,
        plugins: [editorGridCreateRequestBonus_crb],
        id: 'id_create_request_bonus_crb',
        loadMask: true,
        //title: 'Data Pelanggan',
        height: 300,
        columns: [{
                header: "Kode Produk",
                dataIndex: 'kd_produk',
                sortable: true,
                width: 150,
                editor: new Ext.ux.TwinComboProduk_crb({
                    id: 'id_combo_produk_crb',
                    store: storeGridProduk_crb,
                    mode: 'local',
                    valueField: 'kd_produk',
                    displayField: 'kd_produk',
                    typeAhead: true,
                    triggerAction: 'all',
                    allowBlank: true,
                    editable: false,
                    anchor: '95%',
                    hiddenName: 'kd_produk',
                    emptyText: 'kd_produk'

                })
            }, {
                header: "Nama Barang",
                dataIndex: 'nama_barang',
                sortable: true,
                width: 150,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'id_grid_txt_nama_produk_crb'
                })
            }, {
                xtype: 'numbercolumn',
                header: 'Quantity',
                dataIndex: 'qty',
                width: 80,
                sortable: true,
                format: '0,0',
                editor: {
                    xtype: 'numberfield',
                    id: 'id_grid_txt_qty_crb',
                    allowBlank: true,
                    listeners: {
                        'render': function(c) {
                            c.getEl().on('keyup', function() {
                                var max = Ext.getCmp('id_grid_txt_max_stock_crb').getValue();
                                var jml = Ext.getCmp('id_grid_txt_jml_stock_crb').getValue();
                                var qty = this.getValue();
                                var validasi = qty + jml;
                                if (validasi > max) {
                                    Ext.Msg.show({
                                        title: 'Error',
                                        msg: 'Qty + Jml Stok tidak boleh lebih besar dari Max. Stok',
                                        modal: true,
                                        icon: Ext.Msg.ERROR,
                                        buttons: Ext.Msg.OK,
                                        fn: function(btn) {
                                            if (btn == 'ok') {
                                                Ext.getCmp('id_grid_txt_qty_crb').reset();
                                            }
                                        }
                                    });
                                    Ext.MessageBox.getDialog().getEl().setStyle('z-index', '80000');
                                    return;
                                }
                            }, c);
                        }
                        // 'change': function(){
                        // }
                    }
                }
            }, {
                header: "Satuan",
                dataIndex: 'satuan',
                sortable: true,
                width: 150,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'id_grid_txt_satuan_crb'
                })
            }, {
                header: "Min.Stock",
                dataIndex: 'min_stock',
                sortable: true,
                width: 150,
                editor: new Ext.form.NumberField({
                    readOnly: true,
                    id: 'id_grid_txt_min_stock_crb'
                })
            }, {
                header: "Max.Stock",
                dataIndex: 'max_stock',
                sortable: true,
                width: 150,
                format: '0,0',
                editor: new Ext.form.NumberField({
                    readOnly: true,
                    id: 'id_grid_txt_max_stock_crb'
                })
            }, {
                header: "Min.Order",
                dataIndex: 'min_order',
                sortable: true,
                width: 150,
                editor: new Ext.form.NumberField({
                    readOnly: true,
                    id: 'id_grid_txt_min_order_crb'
                })
            }, {
                header: "Order Kelipatan",
                dataIndex: 'order_kelipatan',
                sortable: true,
                width: 150,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'id_grid_txt_order_kelipatan_crb'
                })
            }, {
                header: "Jml Stock Pot. SO",
                dataIndex: 'jml_stock_pos_so',
                sortable: true,
                width: 150,
                editor: new Ext.form.NumberField({
                    readOnly: true,
                    id: 'id_grid_txt_jml_stock_crb'
                })
            }, {
                header: "TOP",
                dataIndex: 'waktu_top',
                sortable: true,
                width: 150,
                editor: new Ext.form.NumberField({
                    readOnly: true,
                    id: 'id_grid_txt_waktu_top_crb'
                })
            }],
        listeners: {
            'rowclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                }
            }
        },
        tbar: topToolBarGridCreateRequestBonus_crb//,
//        bbar: new Ext.PagingToolbar({
//            pageSize: 10,
//            store: storePembelianCreateRequestBonus_crb,
//            displayInfo: true
//        })
    });


    /**
     * declaration of the main panel of this form
     */
    Ext.ns('id_create_request_bonus');
    var createRequestBonus_crb = new Ext.FormPanel({
        id: 'id_create_request_bonus',
        monitorValid: true,
        border: false,
        frame: true,
        autoScroll: true,
        bodyStyle: 'padding:5px;',
        items: [{
                bodyStyle: {
                    margin: '0px 0px 15px 0px'
                },
                items: [headerCreateRequestBonus_crp]
            }
            , gridCreateRequestBonus_crb
        ],
        buttons: [
            {
                text: 'Save',
                formBind: true,
                handler: function() {
                    savePembelianCreateRequestBonus();
                }
            }, {
                text: 'reset',
                handler: function() {
                    clearPembelianCreateRequestBonus();
                }
            }
        ]
    });

    createRequestBonus_crb.on('afterrender', function() {
        this.getForm().load({
            url: '<?= site_url("create_request_bonus_controller/get_form") ?>',
            success: function(form, action) {
                var r = Ext.util.JSON.decode(action.response.responseText);
                if (r.data.user_peruntukan === "0") {
                    Ext.getCmp('id_radio_peruntukan_supermarket_crb').setValue(true);
                    Ext.getCmp('id_radio_peruntukan_supermarket_crb').show();
                    Ext.getCmp('id_radio_peruntukan_distribusi_crb').hide();
                } else if (r.data.user_peruntukan === "1") {
                    Ext.getCmp('id_radio_peruntukan_distribusi_crb').setValue(true);
                    Ext.getCmp('id_radio_peruntukan_supermarket_crb').hide();
                    Ext.getCmp('id_radio_peruntukan_distribusi_crb').show();
                } else {
                    Ext.getCmp('id_radio_peruntukan_supermarket_crb').setValue(true);
                    Ext.getCmp('id_radio_peruntukan_supermarket_crb').show();
                    Ext.getCmp('id_radio_peruntukan_distribusi_crb').show();
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

    function clearPembelianCreateRequestBonus() {
        Ext.getCmp('id_create_request_bonus').getForm().reset();
        Ext.getCmp('id_create_request_bonus').getForm().load({
            url: '<?= site_url("create_request_bonus_controller/get_form") ?>',
            success: function(form, action) {
                var r = Ext.util.JSON.decode(action.response.responseText);
                if (r.data.user_peruntukan === "0") {
                    Ext.getCmp('id_radio_peruntukan_supermarket_crb').setValue(true);
                    Ext.getCmp('id_radio_peruntukan_supermarket_crb').show();
                    Ext.getCmp('id_radio_peruntukan_distribusi_crb').hide();
                } else if (r.data.user_peruntukan === "1") {
                    Ext.getCmp('id_radio_peruntukan_distribusi_crb').setValue(true);
                    Ext.getCmp('id_radio_peruntukan_supermarket_crb').hide();
                    Ext.getCmp('id_radio_peruntukan_distribusi_crb').show();
                } else {
                    Ext.getCmp('id_radio_peruntukan_supermarket_crb').setValue(true);
                    Ext.getCmp('id_radio_peruntukan_supermarket_crb').show();
                    Ext.getCmp('id_radio_peruntukan_distribusi_crb').show();
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
        storePembelianCreateRequestBonus_crb.removeAll();
    }

    function savePembelianCreateRequestBonus() {
        Ext.Msg.show({
            title: 'Confirm',
            msg: 'Apakah anda akan menyimpan data ini ??',
            buttons: Ext.Msg.YESNO,
            fn: function(btn) {
                if (btn == 'yes') {
                    var detailpembeliancreaterequest = new Array();
                    storePembelianCreateRequestBonus_crb.sort('waktu_top', 'ASC');
                    storePembelianCreateRequestBonus_crb.each(function(node) {
                        detailpembeliancreaterequest.push(node.data)
                    });
                    Ext.getCmp('id_create_request_bonus').getForm().submit({
                        url: '<?= site_url("create_request_bonus_controller/update_row") ?>',
                        scope: this,
                        params: {
                            detail: Ext.util.JSON.encode(detailpembeliancreaterequest)
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
                                        // winpembeliancreaterequestprint.show();
                                        // Ext.getDom('pembeliancreaterequestprint').src = r.printUrl;
                                    }
                                }
                            });

                            clearPembelianCreateRequestBonus();
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

</script>
