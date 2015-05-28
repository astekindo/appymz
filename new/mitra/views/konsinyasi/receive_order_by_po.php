<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">
    var strcbrobyposuplier = new Ext.data.ArrayStore({
        fields: ['kd_supplier'],
        data: []
    });

    var strgridrobyposuplier = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_supplier', 'nama_supplier'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("konsinyasi_receive_order_by_po/search_supplier") ?>',
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

    var searchgridrobyposuplier = new Ext.app.SearchField({
        store: strgridrobyposuplier,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgridrobyposuplier'
    });

    strgridrobyposuplier.on('load', function() {
        Ext.getCmp('id_searchgridrobyposuplier').focus();
    });

    var gridrobyposuplier = new Ext.grid.GridPanel({
        store: strgridrobyposuplier,
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
            }],
        tbar: new Ext.Toolbar({
            items: [searchgridrobyposuplier]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridrobyposuplier,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('id_cbrobyposuplier').setValue(sel[0].get('kd_supplier'));
                    Ext.getCmp('robypo_nama_supplier').setValue(sel[0].get('nama_supplier'));

                    menurobyposuplier.hide();
                }
            }
        }
    });

    var menurobyposuplier = new Ext.menu.Menu();
    menurobyposuplier.add(new Ext.Panel({
        title: 'Pilih Supplier',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridrobyposuplier],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menurobyposuplier.hide();
                }
            }]
    }));

    Ext.ux.TwinComborobypoSuplier = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            //load store grid
            strgridrobyposuplier.load();
            menurobyposuplier.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
                trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    menurobyposuplier.on('hide', function() {
        var sf = Ext.getCmp('id_searchgridrobyposuplier').getValue();
        if (sf != '') {
            Ext.getCmp('id_searchgridrobyposuplier').setValue('');
            searchgridrobyposuplier.onTrigger2Click();
        }
    });

    var cbrobyposuplier = new Ext.ux.TwinComborobypoSuplier({
        fieldLabel: 'Supplier <span class="asterix">*</span>',
        id: 'id_cbrobyposuplier',
        store: strcbrobyposuplier,
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

    // ekspedisi
    var strcbrobypoekspedisi = new Ext.data.ArrayStore({
        fields: ['kd_ekspedisi'],
        data: []
    });

    var strgridrobypoekspedisi = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_ekspedisi', 'nama_ekspedisi'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("konsinyasi_receive_order_by_po/search_ekspedisi") ?>',
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

    var searchgridrobypoekspedisi = new Ext.app.SearchField({
        store: strgridrobypoekspedisi,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgridrobypoekspedisi'
    });


    var gridrobypoekspedisi = new Ext.grid.GridPanel({
        store: strgridrobypoekspedisi,
        stripeRows: true,
        frame: true,
        border: true,
        columns: [{
                header: 'Kode Ekspedisi',
                dataIndex: 'kd_ekspedisi',
                width: 80,
                sortable: true
            }, {
                header: 'Nama Ekspedisi',
                dataIndex: 'nama_ekspedisi',
                width: 300,
                sortable: true
            }],
        tbar: new Ext.Toolbar({
            items: [searchgridrobypoekspedisi]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridrobypoekspedisi,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('id_cbrobypoekspedisi').setValue(sel[0].get('kd_ekspedisi'));
                    Ext.getCmp('nama_cbrobypoekspedisi').setValue(sel[0].get('nama_ekspedisi'));
                    menurobypoekspedisi.hide();
                }
            }
        }
    });

    var menurobypoekspedisi = new Ext.menu.Menu();
    menurobypoekspedisi.add(new Ext.Panel({
        title: 'Pilih Ekspedisi',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridrobypoekspedisi],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menurobypoekspedisi.hide();
                }
            }]
    }));

    Ext.ux.TwinComborobypoekspedisi = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            //load store grid
            strgridrobypoekspedisi.load();
            menurobypoekspedisi.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
                trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    menurobypoekspedisi.on('hide', function() {
        var sf = Ext.getCmp('id_searchgridrobypoekspedisi').getValue();
        if (sf != '') {
            Ext.getCmp('id_searchgridrobypoekspedisi').setValue('');
            searchgridrobypoekspedisi.onTrigger2Click();
        }
    });

    // SATUAN ekspedisi
    var strcbrobyposatuanekspedisi = new Ext.data.ArrayStore({
        fields: ['kd_satuan'],
        data: []
    });

    var strgridrobyposatuanekspedisi = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_satuan', 'nm_satuan_eksp'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("konsinyasi_receive_order_by_po/search_satuan") ?>',
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

    var searchgridrobyposatuanekspedisi = new Ext.app.SearchField({
        store: strgridrobyposatuanekspedisi,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgridrobyposatuanekspedisi'
    });


    var gridrobyposatuanekspedisi = new Ext.grid.GridPanel({
        store: strgridrobyposatuanekspedisi,
        stripeRows: true,
        frame: true,
        border: true,
        columns: [{
                header: 'Kode Satuan',
                dataIndex: 'kd_satuan',
                width: 80,
                sortable: true
            }, {
                header: 'Nama Satuan',
                dataIndex: 'nm_satuan_eksp',
                width: 300,
                sortable: true
            }],
        tbar: new Ext.Toolbar({
            items: [searchgridrobyposatuanekspedisi]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridrobyposatuanekspedisi,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('id_cbrobyposatuanekspedisi').setValue(sel[0].get('kd_satuan'));
                    Ext.getCmp('nama_cbrobyposatuanekspedisi').setValue(sel[0].get('nm_satuan_eksp'));
                    menurobyposatuanekspedisi.hide();
                }
            }
        }
    });

    var menurobyposatuanekspedisi = new Ext.menu.Menu();
    menurobyposatuanekspedisi.add(new Ext.Panel({
        title: 'Pilih Satuan',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridrobyposatuanekspedisi],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menurobyposatuanekspedisi.hide();
                }
            }]
    }));

    Ext.ux.TwinComborobyposatuanekspedisi = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            //load store grid
            strgridrobyposatuanekspedisi.load({
                params: {
                    kd_ekspedisi: Ext.getCmp('id_cbrobypoekspedisi').getValue(),
                }
            });
            menurobyposatuanekspedisi.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
                trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    menurobyposatuanekspedisi.on('hide', function() {
        var sf = Ext.getCmp('id_searchgridrobyposatuanekspedisi').getValue();
        if (sf != '') {
            Ext.getCmp('id_searchgridrobyposatuanekspedisi').setValue('');
            searchgridrobyposatuanekspedisi.onTrigger2Click();
        }
    });

    var headerreceiveorder_by_po = {
        layout: 'column',
        border: false,
        items: [{
                columnWidth: .3,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: {labelSeparator: ''},
                items: [{
                        xtype: 'textfield',
                        fieldLabel: 'RO No.',
                        name: 'no_do',
                        readOnly: true,
                        fieldClass: 'readonly-input',
                        id: 'robpo_no_do',
                        anchor: '90%',
                        value: ''
                    }, cbrobyposuplier,
                    new Ext.form.Checkbox({
                        xtype: 'checkbox',
                        fieldLabel: 'Scan Barcode',
                        boxLabel: 'Ya',
                        name: 'scan_barcode',
                        id: 'robypo_scan_barcode',
                        checked: false,
                        inputValue: '1',
                        autoLoad: true
                    })
                ]
            }, {
                columnWidth: .4,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: {labelSeparator: ''},
                items: [{
                        xtype: 'datefield',
                        fieldLabel: 'Tanggal Terima <span class="asterix">*</span>',
                        name: 'tanggal_terima',
                        allowBlank: false,
                        format: 'd-m-Y',
                        editable: false,
                        id: 'robypo_tanggal_terima',
                        anchor: '90%',
                        value: ''
                    }, {
                        xtype: 'textfield',
                        fieldLabel: 'Nama Supplier',
                        name: 'nama_supplier',
                        readOnly: true,
                        fieldClass: 'readonly-input',
                        id: 'robypo_nama_supplier',
                        anchor: '90%',
                        value: ''
                    }, {
                        xtype: 'textfield',
                        fieldLabel: 'Tanggal Input',
                        name: 'tanggal',
                        fieldClass: 'readonly-input',
                        readOnly: true,
                        id: 'robypo_tanggal',
                        anchor: '90%',
                        value: ''


                    }]
            }, {
                columnWidth: .3,
                layout: 'form',
                border: false,
                labelWidth: 120,
                defaults: {labelSeparator: ''},
                items: [{
                        xtype: 'textfield',
                        fieldLabel: 'No. Bukti Supplier<span class="asterix">*</span>',
                        name: 'bukti_supplier',
                        allowBlank: false,
                        id: 'robypo_bukti_supplier',
                        anchor: '90%'
                    }, {
                        xtype: 'datefield',
                        fieldLabel: 'Tgl Bukti Supp <span class="asterix">*</span>',
                        name: 'tanggal_bukti',
                        allowBlank: false,
                        format: 'd-m-Y',
                        editable: false,
                        id: 'robypo_tanggal_bukti',
                        anchor: '90%',
                        value: ''
                    }, {
                        fieldLabel: 'Peruntukan <span class="asterix">*</span>',
                        xtype: 'radiogroup',
                        name: 'kd_peruntukan',
                        columnWidth: [.5, .5],
                        allowBlank: false,
                        items: [{
                                boxLabel: 'Supermarket',
                                name: 'kd_peruntukan',
                                inputValue: '0',
                                id: 'robypo_peruntukan_supermarket',
                                checked: true
                            }, {
                                boxLabel: 'Distribusi',
                                name: 'kd_peruntukan',
                                inputValue: '1',
                                id: 'robypo_peruntukan_distribusi'
                            }]
                    }]
            }]
    };

    /* SubBlok */
    var strcbkdsubblokrobypo = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['sub', 'nama_sub'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("konsinyasi_receive_order_by_po/get_sub_blok") ?>',
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

    var strgridsubblokrobypo = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'sub',
                'nama_sub',
                'kd_sub_blok',
                'kd_blok',
                'kd_lokasi',
                'nama_lokasi',
                'nama_blok',
                'nama_sub_blok',
                'kapasitas'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("konsinyasi_receive_order_by_po/get_rows_lokasi") ?>',
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

    // search field
    var searchgridrobyposubblok = new Ext.app.SearchField({
        store: strgridsubblokrobypo,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        id: 'idsearchgridrobyposubblok'
    });

    // top toolbar
    var tbgridrobyposubblok = new Ext.Toolbar({
        items: [searchgridrobyposubblok]
    });

    var gridrobyposubblok = new Ext.grid.GridPanel({
        store: strgridsubblokrobypo,
        stripeRows: true,
        frame: true,
        border: true,
        tbar: tbgridrobyposubblok,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridsubblokrobypo,
            displayInfo: true
        }),
        columns: [{
                dataIndex: 'kd_lokasi',
                hidden: true
            }, {
                dataIndex: 'kd_blok',
                hidden: true
            }, {
                dataIndex: 'kd_sub_blok',
                hidden: true
            }, {
                header: 'Kode',
                dataIndex: 'sub',
                width: 90,
                sortable: true
            }, {
                header: 'Sub Blok Lokasi',
                dataIndex: 'nama_sub',
                width: 200,
                sortable: true
            }],
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('erobypo_sub').setValue(sel[0].get('sub'));
                    Ext.getCmp('erobypo_nama_sub').setValue(sel[0].get('nama_sub'));

                    menusubblokrobypo.hide();
                }
            }
        }
    });

    var menusubblokrobypo = new Ext.menu.Menu();
    menusubblokrobypo.add(new Ext.Panel({
        title: 'Pilih Sub Blok Lokasi',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 350,
        height: 250,
        closeAction: 'hide',
        plain: true,
        items: [gridrobyposubblok],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menusubblokrobypo.hide();
                }
            }]
    }));

    Ext.ux.TwinComborobypoSubBlok = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            //load store grid
            strgridsubblokrobypo.load({
                params: {
                    kd_produk: Ext.getCmp('erobypo_kd_produk').getValue()
                }
            });
            menusubblokrobypo.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
                trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
    /* END SubBlok*/

    var strreceiveorder_by_po = new Ext.data.Store({
        autoSave: false,
        reader: new Ext.data.JsonReader({
            fields: [
                {name: 'no_po', allowBlank: false, type: 'text'},
                {name: 'kd_produk', allowBlank: false, type: 'text'},
                {name: 'nama_produk', allowBlank: false, type: 'text'},
                {name: 'nm_satuan', allowBlank: false, type: 'text'},
                {name: 'qty_po', allowBlank: false, type: 'int'},
                {name: 'qty_do', allowBlank: false, type: 'int'},
                {name: 'qty_terima', allowBlank: false, type: 'int'},
                {name: 'qty_retur', allowBlank: false, type: 'int'},
                {name: 'sub', allowBlank: false, type: 'text'},
                {name: 'nama_sub', allowBlank: false, type: 'text'},
                {name: 'kd_ekspedisi', allowBlank: false, type: 'text'},
                {name: 'kd_satuan', allowBlank: false, type: 'text'},
                {name: 'nm_satuan_eksp', allowBlank: false, type: 'text'},
                {name: 'jumlah_barcode', allowBlank: false, type: 'text'},
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

    strreceiveorder_by_po.on('update', function() {

        var qty_po = Ext.getCmp('erobypo_qty_po').getValue();
        var qty = Ext.getCmp('erobypo_qty').getValue();
        var qty_terima = Ext.getCmp('erobypo_qty_terima').getValue();
        var qty_realisasi = qty_po - qty_terima;
        var jumlah_barcode = Ext.getCmp('robypo_jumlah_barcode').getValue();

        if (qty > qty_realisasi) {
            Ext.Msg.show({
                title: 'Error',
                msg: 'Qty RO + Qty tidak boleh lebih besar dari Qty PK',
                modal: true,
                icon: Ext.Msg.ERROR,
                buttons: Ext.Msg.OK,
                fn: function(btn) {
                    if (btn == 'ok') {
                        Ext.getCmp('erobypo_qty').reset()
                    }
                }
            });
            Ext.MessageBox.getDialog().getEl().setStyle('z-index', '80000');
            return;
        }
        // if(jumlah_barcode > qty){
        // Ext.Msg.show({
        // title: 'Error',
        // msg: 'Jumlah Barcode tidak boleh lebih besar dari Qty Terima',
        // modal: true,
        // icon: Ext.Msg.ERROR,
        // buttons: Ext.Msg.OK,
        // fn: function(btn){
        // if (btn == 'ok') {
        // Ext.getCmp('robypo_jumlah_barcode').reset()
        // }
        // }                          
        // });
        // Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');  
        // return;
        // }
    });
    var strcbrobyponopo = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['no_po'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("konsinyasi_receive_order_by_po/get_all_po") ?>',
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

    var strcbrobypoproduk = new Ext.data.ArrayStore({
        fields: ['kd_produk'],
        data: []
    });

    var strgridrobypoproduk = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_produk', 'kd_produk_supp', 'kd_produk_lama', 'nama_produk', 'qty_po', 'qty_do', 'qty_terima','qty_retur', 'nm_satuan', 'jumlah_barcode'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("konsinyasi_receive_order_by_po/search_produk_by_no_po") ?>',
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

    var searchFieldRO = new Ext.app.SearchField({
        width: 220,
        id: 'search_query_kro',
        store: strgridrobypoproduk
    });

    searchFieldRO.onTrigger1Click = function(evt) {
        if (this.hasSearch) {
            this.el.dom.value = '';

            // Get the value of search field
            var fid = Ext.getCmp('erobypo_no_po').getValue();
            var o = {start: 0, no_po: fid};

            this.store.baseParams = this.store.baseParams || {};
            this.store.baseParams[this.paramName] = '';
            this.store.reload({
                params: o
            });
            this.triggers[0].hide();
            this.hasSearch = false;
        }
    };

    searchFieldRO.onTrigger2Click = function(evt) {
        var text = this.getRawValue();
        if (text.length < 1) {
            this.onTrigger1Click();
            return;
        }

        // Get the value of search field
        var fid = Ext.getCmp('erobypo_no_po').getValue();
        var o = {start: 0, no_po: fid};

        this.store.baseParams = this.store.baseParams || {};
        this.store.baseParams[this.paramName] = text;
        this.store.reload({params: o});
        this.hasSearch = true;
        this.triggers[0].show();
    };

    // top toolbar
    var tbsearchbarang = new Ext.Toolbar({
        items: [searchFieldRO]
    });

    var gridkroproduk = new Ext.grid.GridPanel({
        store: strgridrobypoproduk,
        stripeRows: true,
        frame: true,
        border: true,
        columns: [{
                header: 'Kode Produk',
                dataIndex: 'kd_produk',
                width: 100,
                sortable: true
            }, {
                header: 'Kode Produk Supp',
                dataIndex: 'kd_produk_supp',
                width: 120,
                sortable: true
            }, {
                header: 'Kode Produk Lama',
                dataIndex: 'kd_produk_lama',
                width: 120,
                sortable: true
            }, {
                header: 'Nama Produk',
                dataIndex: 'nama_produk',
                width: 400,
                sortable: true
            }, {
                header: 'Satuan',
                dataIndex: 'nm_satuan',
                width: 80
            }, {
                header: 'Qty PK',
                dataIndex: 'qty_po',
                width: 80,
                sortable: true
            }, {
                header: 'Qty RO',
                dataIndex: 'qty_terima',
                width: 80,
                sortable: true
            }, {
                header: 'Qty',
                dataIndex: 'qty_do',
                width: 80,
                sortable: true
            }, {
                header: 'Jumlah Barcode',
                dataIndex: 'jumlah_barcode',
                width: 80,
                sortable: true
            }],
        tbar: tbsearchbarang,
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('erobypo_kd_produk').setValue(sel[0].get('kd_produk'));
                    Ext.getCmp('erobypo_nama_produk').setValue(sel[0].get('nama_produk'));
                    Ext.getCmp('erobypo_qty_po').setValue(sel[0].get('qty_po'));
                    Ext.getCmp('erobypo_qty_terima').setValue(sel[0].get('qty_terima'));
                    Ext.getCmp('erobypo_qty_retur').setValue(sel[0].get('qty_retur'));
                    Ext.getCmp('erobypo_qty').setValue(sel[0].get('qty_do'));
                    Ext.getCmp('erobypo_nm_satuan').setValue(sel[0].get('nm_satuan'));
                    Ext.getCmp('robypo_jumlah_barcode').setValue(sel[0].get('jumlah_barcode'));
                    Ext.getCmp('erobypo_qty').setValue(0);
                    Ext.getCmp('erobypo_qty').focus();
                    menurobypoproduk.hide();
                }
            }
        }
    });

    var menurobypoproduk = new Ext.menu.Menu();
    menurobypoproduk.add(new Ext.Panel({
        title: 'Pilih Barang',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 600,
        height: 250,
        closeAction: 'hide',
        plain: true,
        items: [gridkroproduk],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menurobypoproduk.hide();
                }
            }]
    }));

    Ext.ux.TwinComborobypoproduk = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            if (Ext.getCmp('erobypo_no_po').getValue() == '') {
                Ext.Msg.show({
                    title: 'Error',
                    msg: 'Silahkan pilih No PO terlebih dulu',
                    modal: true,
                    icon: Ext.Msg.ERROR,
                    buttons: Ext.Msg.OK
                });
                return;
            }
            //load store grid
            strgridrobypoproduk.load({
                params: {
                    no_po: Ext.getCmp('erobypo_no_po').getValue()
                }
            });
            menurobypoproduk.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
                trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    // TWIN SCAN
    var strcbkroscanbarang = new Ext.data.ArrayStore({
        fields: ['kd_produk'],
        data: []
    });

    var strgridkroscanbarang = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_produk', 'kd_produk_supp', 'kd_produk_lama', 'nama_produk', 'qty_po', 'qty_do', 'qty_terima','qty_retur', 'nm_satuan', 'jumlah_barcode'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("konsinyasi_receive_order_by_po/search_produk_by_no_po") ?>',
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

    strgridkroscanbarang.on('load', function() {
        // var searchString = Ext.getCmp('search_query_scan').getValue();
        // if(searchString == ''){
        // Ext.getCmp('search_query_scan').focus();
        // }else{
        // Ext.getCmp('kro_kd_produk_scan').focus();
        // // Ext.getCmp('kro_submit_button').focus();
        // }
        Ext.getCmp('robypo_scan_barcode_kode').focus();
    });

    var searchFieldROScan = new Ext.app.SearchField({
        width: 220,
        id: 'search_query_scan',
        store: strgridkroscanbarang
    });

    searchFieldROScan.onTrigger1Click = function(evt) {
        if (this.hasSearch) {
            this.el.dom.value = '';

            // Get the value of search field
            var fid = Ext.getCmp('erobypo_no_po').getValue();
            var o = {start: 0, no_po: fid};

            this.store.baseParams = this.store.baseParams || {};
            this.store.baseParams[this.paramName] = '';
            this.store.reload({
                params: o
            });
            this.triggers[0].hide();
            this.hasSearch = false;
        }
    };

    searchFieldROScan.onTrigger2Click = function(evt) {
        var text = this.getRawValue();
        if (text.length < 1) {
            this.onTrigger1Click();
            return;
        }

        // Get the value of search field
        var fid = Ext.getCmp('erobypo_no_po').getValue();
        var o = {start: 0, no_po: fid};

        this.store.baseParams = this.store.baseParams || {};
        this.store.baseParams[this.paramName] = text;
        this.store.reload({params: o});
        this.hasSearch = true;
        this.triggers[0].show();
    };

    // top toolbar
    var tbsearchscanbarang = new Ext.Toolbar({
        items: [searchFieldROScan]
    });

    var gridkroscanbarang = new Ext.grid.GridPanel({
        store: strgridkroscanbarang,
        stripeRows: true,
        frame: true,
        border: true,
        columns: [{
                header: 'Kode Produk',
                dataIndex: 'kd_produk',
                width: 100,
                sortable: true,
                id: 'kro_scan_kd_produk'
            }, {
                header: 'Kode Produk Supp',
                dataIndex: 'kd_produk_supp',
                width: 120,
                sortable: true
            }, {
                header: 'Kode Produk Lama',
                dataIndex: 'kd_produk_lama',
                width: 120,
                sortable: true
            }, {
                header: 'Nama Produk',
                dataIndex: 'nama_produk',
                width: 400,
                sortable: true
            }, {
                header: 'Satuan',
                dataIndex: 'nm_satuan',
                width: 80
            }, {
                header: 'Qty PK',
                dataIndex: 'qty_po',
                width: 80,
                sortable: true
            }, {
                header: 'Qty RO',
                dataIndex: 'qty_do',
                width: 80,
                sortable: true
            }, {
                header: 'Qty',
                dataIndex: 'qty_terima',
                width: 80,
                sortable: true
            }, {
                header: 'Jumlah Barcode',
                dataIndex: 'jumlah_barcode',
                width: 80,
                sortable: true
            }],
        tbar: tbsearchscanbarang,
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('erobypo_kd_produk').setValue(sel[0].get('kd_produk'));
                    Ext.getCmp('erobypo_nama_produk').setValue(sel[0].get('nama_produk'));
                    Ext.getCmp('erobypo_qty_po').setValue(sel[0].get('qty_po'));
                    Ext.getCmp('erobypo_qty_terima').setValue(sel[0].get('qty_terima'));
                    Ext.getCmp('erobypo_qty_retur').setValue(sel[0].get('qty_retur'));
                    Ext.getCmp('erobypo_qty').setValue(sel[0].get('qty_do'));
                    Ext.getCmp('erobypo_nm_satuan').setValue(sel[0].get('nm_satuan'));
                    Ext.getCmp('robypo_jumlah_barcode').setValue(sel[0].get('jumlah_barcode'));
                    Ext.getCmp('erobypo_qty').setValue(0);
                    Ext.getCmp('erobypo_qty').focus();
                    menukroscanbarang.hide();
                }
            }
        }
    });

    var menukroscanbarang = new Ext.Window();
    menukroscanbarang.add(new Ext.Panel({
        title: 'Scan Barcode Produk',
        layout: 'form',
        border: false,
        frame: true,
        autoScroll: true,
        //monitorValid: true,       
        bodyStyle: 'padding-right:20px;',
        labelWidth: 130,
        buttonAlign: 'left',
        //modal: true,
        width: 400,
        height: 250,
        closeAction: 'hide',
        //plain: true,
        items: [{
                xtype: 'textfield',
                fieldLabel: 'Scan Barcode',
                name: 'scan_barcode',
                id: 'robypo_scan_barcode_kode',
                anchor: '90%',
                value: '',
                listeners: {
                    specialKey: function(field, e) {
                        if (e.getKey() === e.RETURN || e.getKey() === e.ENTER) {
                            Ext.Ajax.request({
                                url: '<?= site_url("konsinyasi_receive_order_by_po/search_produk_by_no_po") ?>',
                                method: 'POST',
                                params: {
                                    no_po: Ext.getCmp('erobypo_no_po').getValue(),
                                    query: Ext.getCmp('robypo_scan_barcode_kode').getValue(),
                                    sender: 'scan'
                                },
                                callback: function(opt, success, responseObj) {
                                    var scn = Ext.util.JSON.decode(responseObj.responseText);
                                    if (scn.success === true) {
                                        Ext.getCmp('kro_kd_produk_scan').setValue(scn.data.kd_produk);
                                        Ext.getCmp('kro_kd_produk_supp_scan').setValue(scn.data.kd_produk_supp);
                                        Ext.getCmp('kro_kd_produk_lama_scan').setValue(scn.data.kd_produk_lama);
                                        Ext.getCmp('kro_nama_produk_scan').setValue(scn.data.nama_produk);
                                    }
                                }
                            });
                            if (Ext.getCmp('kro_kd_produk_scan').getValue() !== '') {
                                Ext.getCmp('kro_submit_button').focus();
                            }

                        }
                    }
                }
            }, {
                xtype: 'textfield',
                fieldLabel: 'Kode Produk',
                name: 'kd_produk',
                readOnly: true,
                fieldClass: 'readonly-input',
                id: 'kro_kd_produk_scan',
                anchor: '90%',
                value: ''
            }, {
                xtype: 'textfield',
                fieldLabel: 'Kode Produk Supplier',
                name: 'kd_produk_supp',
                readOnly: true,
                fieldClass: 'readonly-input',
                id: 'kro_kd_produk_supp_scan',
                anchor: '90%',
                value: ''
            }, {
                xtype: 'textfield',
                fieldLabel: 'Kode Produk Lama',
                name: 'kd_produk_lama',
                readOnly: true,
                fieldClass: 'readonly-input',
                id: 'kro_kd_produk_lama_scan',
                anchor: '90%',
                value: ''
            }, {
                xtype: 'textfield',
                fieldLabel: 'Nama Produk',
                name: 'nama_produk',
                readOnly: true,
                fieldClass: 'readonly-input',
                id: 'kro_nama_produk_scan',
                anchor: '90%',
                value: ''
            }
        ],
        buttons: [{
                text: 'Submit',
                formBind: true,
                id: 'kro_submit_button',
                handler: function() {
                    Ext.Ajax.request({
                        url: '<?= site_url("konsinyasi_receive_order_by_po/search_produk_by_no_po") ?>',
                        method: 'POST',
                        params: {
                            no_po: Ext.getCmp('erobypo_no_po').getValue(),
                            query: Ext.getCmp('robypo_scan_barcode_kode').getValue(),
                            sender: 'scan'
                        },
                        callback: function(opt, success, responseObj) {
                            var scn = Ext.util.JSON.decode(responseObj.responseText);
                            if (scn.success === true) {
                                Ext.getCmp('kro_kd_produk_scan').setValue(scn.data.kd_produk);
                                Ext.getCmp('erobypo_kd_produk').setValue(scn.data.kd_produk);
                                Ext.getCmp('erobypo_nama_produk').setValue(scn.data.nama_produk);
                                Ext.getCmp('erobypo_qty_po').setValue(scn.data.qty_po);
                                Ext.getCmp('erobypo_qty_terima').setValue(scn.data.qty_terima);
                                Ext.getCmp('erobypo_qty_retur').setValue(scn.data.qty_retur); 
                                Ext.getCmp('erobypo_qty').setValue(scn.data.qty_do);
                                Ext.getCmp('erobypo_nm_satuan').setValue(scn.data.nm_satuan);
                                Ext.getCmp('robypo_jumlah_barcode').setValue(scn.data.jumlah_barcode);
                                Ext.getCmp('erobypo_qty').setValue(0);
                                Ext.getCmp('erobypo_qty').focus();
                                menukroscanbarang.hide();
                            }
                        }
                    });
                }
            }, {
                text: 'Close',
                handler: function() {
                    menukroscanbarang.hide();
                }
            }]
    }));


    // TWIN NO PK
    var strcbrobyponopo = new Ext.data.ArrayStore({
        fields: ['no_po'],
        data: []
    });

    function validasi_qty() {
        var qty_po = Ext.getCmp('erobypo_qty_po').getValue();
        var qty = Ext.getCmp('erobypo_qty').getValue();
        var qty_realisasi = qty_po - Ext.getCmp('erobypo_qty_terima').getValue();

        if (qty > qty_realisasi) {
            Ext.Msg.show({
                title: 'Error',
                msg: 'Qty tidak boleh lebih besar dari Qty PK',
                modal: true,
                icon: Ext.Msg.ERROR,
                buttons: Ext.Msg.OK,
                fn: function(btn) {
                    if (btn === 'ok') {
                        Ext.getCmp('erobypo_qty').reset();
                    }
                }
            });
            Ext.MessageBox.getDialog().getEl().setStyle('z-index', '80000');
            return;
        }
    }
    ;

    var strgridrobyponopo = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['no_po'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("konsinyasi_receive_order_by_po/get_all_po") ?>',
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

    strgridrobyponopo.on('load', function() {
        Ext.getCmp('search_query_no_po').focus();
    });

    var searchFieldROByPoNoPO = new Ext.app.SearchField({
        width: 220,
        id: 'search_query_no_po',
        store: strgridrobyponopo
    });
    searchFieldROByPoNoPO.onTrigger1Click = function(evt) {
        if (this.hasSearch) {
            this.el.dom.value = '';

            // Get the value of search field
            var fid = Ext.getCmp('id_cbrobyposuplier').getValue();
            var o = {start: 0, kd_supplier: fid};

            this.store.baseParams = this.store.baseParams || {};
            this.store.baseParams[this.paramName] = '';
            this.store.reload({
                params: o
            });
            this.triggers[0].hide();
            this.hasSearch = false;
        }
    };

    searchFieldROByPoNoPO.onTrigger2Click = function(evt) {
        var text = this.getRawValue();
        if (text.length < 1) {
            this.onTrigger1Click();
            return;
        }

        // Get the value of search field
        var fid = Ext.getCmp('id_cbrobyposuplier').getValue();
        var o = {start: 0, kd_supplier: fid};

        this.store.baseParams = this.store.baseParams || {};
        this.store.baseParams[this.paramName] = text;
        this.store.reload({params: o});
        this.hasSearch = true;
        this.triggers[0].show();
    };

    // top toolbar
    var tbsearchrobyponopo = new Ext.Toolbar({
        items: [searchFieldROByPoNoPO]
    });

    var gridrobyponopo = new Ext.grid.GridPanel({
        store: strgridrobyponopo,
        stripeRows: true,
        frame: true,
        border: true,
        columns: [{
                header: 'No PO',
                dataIndex: 'no_po',
                width: 200,
                sortable: true
            }],
        tbar: tbsearchrobyponopo,
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('erobypo_no_po').setValue(sel[0].get('no_po'));
                    menurobyponopo.hide();

                    var scan = Ext.getCmp('robypo_scan_barcode').getValue();

                    if (scan) {

                        // strgridkroscanbarang.load({
                        // params: {
                        // no_po: Ext.getCmp('erobypo_no_po').getValue()                                 
                        // }
                        // });
                        strgridkroscanbarang.load();
                        Ext.getCmp('robypo_scan_barcode_kode').setValue('');
                        Ext.getCmp('kro_kd_produk_scan').setValue('');
                        Ext.getCmp('kro_kd_produk_supp_scan').setValue('');
                        Ext.getCmp('kro_kd_produk_lama_scan').setValue('');
                        Ext.getCmp('kro_nama_produk_scan').setValue('');
                        //menukroscanbarang.showAt([300, 266 + 20]);
                        var win = Ext.WindowMgr;
                        // win.zseed='90000';
                        win.get(menukroscanbarang).show();

                        // menukroscanbarang.show();
                    }
                }
            }
        }
    });

    var menurobyponopo = new Ext.menu.Menu();
    menurobyponopo.add(new Ext.Panel({
        title: 'Pilih No PO',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 300,
        height: 250,
        closeAction: 'hide',
        plain: true,
        items: [gridrobyponopo],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menurobyponopo.hide();
                }
            }]
    }));

    Ext.ux.TwinComborobypoNoPO = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            //load store grid
            strgridrobyponopo.load({
                params: {
                    kd_supplier: Ext.getCmp('id_cbrobyposuplier').getValue()
                }
            });
            menurobyponopo.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
                trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    var editorreceiveorder_by_po = new Ext.ux.grid.RowEditor({
        saveText: 'Update'
    });

    var gridreceiveorder_by_po = new Ext.grid.GridPanel({
        store: strreceiveorder_by_po,
        stripeRows: true,
        height: 400,
        frame: true,
        border: true,
        plugins: [editorreceiveorder_by_po],
        tbar: [{
                icon: BASE_ICONS + 'add.png',
                text: 'Add',
                handler: function() {
                    if (Ext.getCmp('id_cbrobyposuplier').getValue() === '') {
                        Ext.Msg.show({
                            title: 'Error',
                            msg: 'Silahkan pilih supplier terlebih dulu',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK
                        });
                        return;
                    }
                    var rowreceiveorder_by_po = new gridreceiveorder_by_po.store.recordType({
                        no_po: '',
                        kd_produk: '',
                        qty: ''
                    });
                    editorreceiveorder_by_po.stopEditing();
                    strreceiveorder_by_po.insert(0, rowreceiveorder_by_po);
                    gridreceiveorder_by_po.getView().refresh();
                    gridreceiveorder_by_po.getSelectionModel().selectRow(0);
                    editorreceiveorder_by_po.startEditing(0);
                }
            }, {
                ref: '../removeBtn',
                icon: BASE_ICONS + 'delete.gif',
                text: 'Remove',
                disabled: true,
                handler: function() {
                    editorreceiveorder_by_po.stopEditing();
                    var s = gridreceiveorder_by_po.getSelectionModel().getSelections();
                    for (var i = 0, r; r = s[i]; i++) {
                        strreceiveorder_by_po.remove(r);
                    }
                }
            }],
        columns: [new Ext.grid.RowNumberer({width: 30}), {
                header: 'No PO',
                dataIndex: 'no_po',
                width: 140,
                editor: new Ext.ux.TwinComborobypoNoPO({
                    id: 'erobypo_no_po',
                    store: strcbrobyponopo,
                    mode: 'local',
                    valueField: 'no_po',
                    displayField: 'no_po',
                    typeAhead: true,
                    triggerAction: 'all',
                    //allowBlank: false,
                    editable: false,
                    hiddenName: 'no_po',
                    emptyText: 'Pilih No PK'

                })
            }, {
                header: 'Kode',
                dataIndex: 'kd_produk',
                width: 110,
                editor: new Ext.ux.TwinComborobypoproduk({
                    id: 'erobypo_kd_produk',
                    store: strcbrobypoproduk,
                    mode: 'local',
                    valueField: 'kd_produk',
                    displayField: 'kd_produk',
                    typeAhead: true,
                    triggerAction: 'all',
                    // allowBlank: false,
                    editable: false,
                    hiddenName: 'kd_produk',
                    emptyText: 'Pilih produk'

                })

            }, {
                header: 'Nama Barang',
                dataIndex: 'nama_produk',
                width: 300,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'erobypo_nama_produk'
                })
            }, {
                header: 'Satuan',
                dataIndex: 'nm_satuan',
                width: 80,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'erobypo_nm_satuan'
                })
            }, {
                header: 'Qty Pesanan',
                dataIndex: 'qty_po',
                width: 90,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'erobypo_qty_po'
                })
            }, {
                header: 'Qty RO',
                dataIndex: 'qty_terima',
                width: 50,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'erobypo_qty_terima'
                })
            },{
                header: 'Qty Retur',
                dataIndex: 'qty_retur',
                width: 80,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'erobypo_qty_retur'
                })
            }, {
                xtype: 'numbercolumn',
                header: 'Qty',
                dataIndex: 'qty_do',
                width: 50,
                align: 'center',
                sortable: true,
                format: '0,0',
                editor: {
                    xtype: 'numberfield',
                    id: 'erobypo_qty',
                    //allowBlank: false,
                    selectOnFocus: true,
                    listeners: {
                        'render': function(c) {
                            c.getEl().on('keyup', function() {
                                
				Ext.getCmp('robypo_jumlah_barcode').setValue(qty);
                                
                                var max = parseFloat (Ext.getCmp('erobypo_qty_po').getValue());
                                var jml = parseFloat(Ext.getCmp('erobypo_qty_terima').getValue());
                                var retur = parseFloat(Ext.getCmp('erobypo_qty_retur').getValue());
                                var qty = this.getValue();
                                var validasi = max - jml + retur;
                                console.log(validasi);
                                console.log(max);
                                if(qty > validasi){
                                    Ext.Msg.show({
                                        title: 'Error',
                                        msg: 'Qty RO + Qty - Qty Retur tidak boleh lebih besar dari Qty PK',
                                        modal: true,
                                        icon: Ext.Msg.ERROR,
                                        buttons: Ext.Msg.OK,
                                        fn: function(btn){
                                            if (btn === 'ok') {
                                                
                                                Ext.getCmp('erobypo_qty').reset();
                                            }
                                        }                            
                                    });
                                    Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');
                                    return;
                                }
                            }, c);
                        }
                    }
                }
            }, {
                header: 'Kode Sub Blok',
                dataIndex: 'sub',
                width: 100,
                editor: new Ext.ux.TwinComborobypoSubBlok({
                    id: 'erobypo_sub',
                    store: strcbkdsubblokrobypo,
                    valueField: 'sub',
                    displayField: 'sub',
                    typeAhead: true,
                    triggerAction: 'all',
                    //allowBlank: false,
                    editable: false,
                    hiddenName: 'sub',
                    emptyText: 'Pilih Sub Blok',
                    listeners: {
                        'expand': function() {
                            strcbkdsubblokrobypo.load();
                        }
                    }
                })
            }, {
                header: 'Sub Blok',
                dataIndex: 'nama_sub',
                width: 200,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'erobypo_nama_sub'
                })
            }, {
                header: 'Nama Ekspedisi',
                dataIndex: 'nama_ekspedisi',
                width: 100,
                editor: new Ext.ux.TwinComborobypoekspedisi({
                    id: 'nama_cbrobypoekspedisi',
                    store: strcbrobypoekspedisi,
                    mode: 'local',
                    valueField: 'nama_ekspedisi',
                    displayField: 'nama_ekspedisi',
                    typeAhead: true,
                    triggerAction: 'all',
                    editable: false,
                    anchor: '90%',
                    hiddenName: 'nama_ekspedisi',
                    emptyText: 'Pilih Kode Ekspedisi'
                })
            }, {
                header: 'Satuan Ekspedisi',
                dataIndex: 'nm_satuan_eksp',
                width: 100,
                editor: new Ext.ux.TwinComborobyposatuanekspedisi({
                    id: 'nama_cbrobyposatuanekspedisi',
                    store: strcbrobyposatuanekspedisi,
                    mode: 'local',
                    valueField: 'nm_satuan_eksp',
                    displayField: 'nm_satuan_eksp',
                    typeAhead: true,
                    triggerAction: 'all',
                    editable: false,
                    anchor: '90%',
                    hiddenName: 'nm_satuan_eksp',
                    emptyText: 'Pilih Kode Satuan'
                })
            }, {
                header: 'Berat Ekspedisi',
                dataIndex: 'berat_ekspedisi',
                editor: {
                    xtype: 'numberfield',
                    id: 'robypo_berat_ekspedisi'
                    //allowBlank: false,
                }
            }, {
                header: '',
                width: 0,
                dataIndex: 'jumlah_barcode',
                editor: {
                    xtype: 'numberfield',
                    id: 'robypo_jumlah_barcode'
                    //allowBlank: false,
                }
            }, {
                header: '',
                width: 0,
                dataIndex: 'kd_ekspedisi',
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'id_cbrobypoekspedisi'
                })
            }, {
                header: '',
                width: 0,
                dataIndex: 'kd_satuan_ekspedisi',
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'id_cbrobyposatuanekspedisi'
                })
            }]
    });

    gridreceiveorder_by_po.getSelectionModel().on('selectionchange', function(sm) {
        gridreceiveorder_by_po.removeBtn.setDisabled(sm.getCount() < 1);
    });

    var winreceiveorder_by_poprint = new Ext.Window({
        id: 'id_winreceiveorder_by_poprint',
        title: 'Print Receive Order Form',
        closeAction: 'hide',
        width: 900,
        height: 450,
        layout: 'fit',
        border: false,
        html: '<iframe style="width:100%;height:100%;" id="receiveorder_by_poprint" src=""></iframe>'
    });

    var receiveorder_by_po = new Ext.FormPanel({
        id: 'receiveorder_by_po',
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
                items: [headerreceiveorder_by_po]
            },
            gridreceiveorder_by_po
        ],
        buttons: [{
                text: 'Save',
                formBind: true,
                id: 'kons_button-save',
                handler: function() {
                    var detailreceiveorder_by_po = new Array();
                    strreceiveorder_by_po.each(function(node) {
                        detailreceiveorder_by_po.push(node.data)
                    });
                    Ext.getCmp('receiveorder_by_po').getForm().submit({
                        url: '<?= site_url("konsinyasi_receive_order_by_po/update_row") ?>',
                        scope: this,
                        params: {
                            detail: Ext.util.JSON.encode(detailreceiveorder_by_po)
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
                                        // winreceiveorder_by_poprint.show();
                                        // Ext.getDom('receiveorder_by_poprint').src = r.printUrl;
                                    }
                                }
                            });

                            clearreceiveorder_by_po();
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
                            Ext.MessageBox.getDialog().getEl().setStyle('z-index', '80000');
                        }
                    });
                }
            }, {
                text: 'Reset',
                handler: function() {
                    clearreceiveorder_by_po();
                }
            }],
        keys: [
            {
                key: [Ext.EventObject.ENTER], handler: function() {
                    Ext.getCmp('kons_button-save').focus();
                }
            }]
    });

    receiveorder_by_po.on('afterrender', function() {
        this.getForm().load({
            url: '<?= site_url("konsinyasi_receive_order_by_po/get_form") ?>',
            success: function(form, action) {
                var r = Ext.util.JSON.decode(action.response.responseText);
                if (r.data.user_peruntukan === "0") {
                    Ext.getCmp('robypo_peruntukan_supermarket').setValue(true);
                    Ext.getCmp('robypo_peruntukan_supermarket').show();
                    Ext.getCmp('robypo_peruntukan_distribusi').hide();
                } else if (r.data.user_peruntukan === "1") {
                    Ext.getCmp('robypo_peruntukan_distribusi').setValue(true);
                    Ext.getCmp('robypo_peruntukan_supermarket').hide();
                    Ext.getCmp('robypo_peruntukan_distribusi').show();
                } else {
                    Ext.getCmp('robypo_peruntukan_supermarket').setValue(true);
                    Ext.getCmp('robypo_peruntukan_supermarket').show();
                    Ext.getCmp('robypo_peruntukan_distribusi').show();
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

    function clearreceiveorder_by_po() {
        Ext.getCmp('receiveorder_by_po').getForm().reset();
        Ext.getCmp('receiveorder_by_po').getForm().load({
            url: '<?= site_url("konsinyasi_receive_order_by_po_by_po/get_form") ?>',
            success: function(form, action) {
                var r = Ext.util.JSON.decode(action.response.responseText);
                if (r.data.user_peruntukan === "0") {
                    Ext.getCmp('robypo_peruntukan_supermarket').setValue(true);
                    Ext.getCmp('robypo_peruntukan_supermarket').show();
                    Ext.getCmp('robypo_peruntukan_distribusi').hide();
                } else if (r.data.user_peruntukan === "1") {
                    Ext.getCmp('robypo_peruntukan_distribusi').setValue(true);
                    Ext.getCmp('robypo_peruntukan_supermarket').hide();
                    Ext.getCmp('robypo_peruntukan_distribusi').show();
                } else {
                    Ext.getCmp('robypo_peruntukan_supermarket').setValue(true);
                    Ext.getCmp('robypo_peruntukan_supermarket').show();
                    Ext.getCmp('robypo_peruntukan_distribusi').show();
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
                Ext.MessageBox.getDialog().getEl().setStyle('z-index', '80000');
            }
        });
        strreceiveorder_by_po.removeAll();
    }
</script>
