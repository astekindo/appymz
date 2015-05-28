<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">
    var strcblp2suplier = new Ext.data.ArrayStore({
        fields: ['kd_supplier'],
        data: []
    });

    var strgridlp2suplier = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_supplier', 'nama_supplier'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("laporan_purchase_order/search_supplier") ?>',
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

    var searchgridlp2suplier = new Ext.app.SearchField({
        store: strgridlp2suplier,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgridlp2suplier'
    });

    var gridlp2suplier = new Ext.grid.GridPanel({
        store: strgridlp2suplier,
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
            }],
        tbar: new Ext.Toolbar({
            items: [searchgridlp2suplier]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridlp2suplier,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('id_cblp2suplier').setValue(sel[0].get('kd_supplier'));
                    menulp2suplier.hide();
                }
            }
        }
    });

    var menulp2suplier = new Ext.menu.Menu();
    menulp2suplier.add(new Ext.Panel({
        title: 'Pilih Supplier',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridlp2suplier],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menulp2suplier.hide();
                }
            }]
    }));

    Ext.ux.TwinComboSuplier = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            //load store grid
            strgridlp2suplier.load();
            menulp2suplier.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    menulp2suplier.on('hide', function() {
        var sf = Ext.getCmp('id_searchgridlp2suplier').getValue();
        if (sf != '') {
            Ext.getCmp('id_searchgridlp2suplier').setValue('');
            searchgridlp2suplier.onTrigger2Click();
        }
    });

    var cblp2suplier = new Ext.ux.TwinComboSuplier({
        fieldLabel: 'Supplier',
        id: 'id_cblp2suplier',
        store: strcblp2suplier,
        mode: 'local',
        valueField: 'kd_supplier',
        displayField: 'kd_supplier',
        typeAhead: true,
        triggerAction: 'all',
        // allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_supplier',
        emptyText: 'Pilih Supplier'
    });

    var strcblpenjualan2produk = new Ext.data.ArrayStore({
        fields: ['kd_produk'],
        data: []
    });

    var strgridlpenjualan2produk = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_produk', 'nama_produk'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("laporan_purchase_order/search_produk") ?>',
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
    var searchgridlpenjualan2produk = new Ext.app.SearchField({
        store: strgridlpenjualan2produk,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgridlpenjualan2produk'
    });

    var gridlpenjualan2produk = new Ext.grid.GridPanel({
        store: strgridlpenjualan2produk,
        stripeRows: true,
        frame: true,
        border: true,
        columns: [{
                header: 'Kode Produk',
                dataIndex: 'kd_produk',
                width: 80,
                sortable: true

            }, {
                header: 'Nama Produk',
                dataIndex: 'nama_produk',
                width: 300,
                sortable: true
            }],
        tbar: new Ext.Toolbar({
            items: [searchgridlpenjualan2produk]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridlpenjualan2produk,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    // Ext.getCmp('lpo_kd_supplier').setValue(sel[0].get('kd_supplier'));
                    Ext.getCmp('id_cblp2_kd_produk').setValue(sel[0].get('kd_produk'));
                    // strlaporanpenerimaanbarang.removeAll();       
                    menulpenjualan2produk.hide();
                }
            }
        }
    });
    Ext.ux.TwinCombolpoProduk = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            //load store grid
            strgridlpenjualan2produk.load();
            menulpenjualan2produk.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
    var menulpenjualan2produk = new Ext.menu.Menu();
    menulpenjualan2produk.add(new Ext.Panel({
        title: 'Pilih Produk',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridlpenjualan2produk],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menulpenjualan2produk.hide();
                }
            }]
    }));



    menulpenjualan2produk.on('hide', function() {
        var sf = Ext.getCmp('id_searchgridlpenjualan2produk').getValue();
        if (sf != '') {
            Ext.getCmp('id_searchgridlpenjualan2produk').setValue('');
            searchgridlpenjualan2produk.onTrigger2Click();
        }
    });

    var cblpenjualan2produk = new Ext.ux.TwinCombolpoProduk({
        id: 'id_cblp2_kd_produk',
        fieldLabel: 'Produk',
        store: strcblpenjualan2produk,
        mode: 'local',
        anchor: '90%',
        valueField: 'kd_produk',
        displayField: 'kd_produk',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: true,
        editable: false,
        hiddenName: 'kd_produk',
        emptyText: 'Pilih Produk'
    });

    // combobox Ukuran
    var str_lp2_cbukuran = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_ukuran', 'nama_ukuran'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("master_barang/get_ukuran_produk") ?>',
            method: 'POST'
        }),
        listeners: {
            load: function() {
                var r = new (str_lp2_cbukuran.recordType)({
                    'kd_ukuran': '',
                    'nama_ukuran': '-----'
                });
                str_lp2_cbukuran.insert(0, r);
            },
            loadexception: function(event, options, response, error) {
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });
    var lp2_cbukuran = new Ext.form.ComboBox({
        fieldLabel: 'Ukuran ',
        id: 'id_lp2_cbukuran',
        store: str_lp2_cbukuran,
        valueField: 'kd_ukuran',
        displayField: 'nama_ukuran',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_ukuran',
        emptyText: 'Pilih Ukuran'

    });

    // combobox Satuan
    var str_lp2_cbsatuan = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_satuan', 'nm_satuan'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("master_barang/get_satuan_produk") ?>',
            method: 'POST'
        }),
        listeners: {
            load: function() {
                var r = new (str_lp2_cbsatuan.recordType)({
                    'kd_ukuran': '',
                    'nama_ukuran': '-----'
                });
                str_lp2_cbsatuan.insert(0, r);
            },
            loadexception: function(event, options, response, error) {
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    var lp2_cbsatuan = new Ext.form.ComboBox({
        fieldLabel: 'Satuan ',
        id: 'id_lp2_cbsatuan',
        store: str_lp2_cbsatuan,
        valueField: 'kd_satuan',
        displayField: 'nm_satuan',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_satuan',
        emptyText: 'Pilih Satuan'

    });


// cb kategori1
    var str_cblp2kategori1 = new Ext.data.Store({
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
                var r = new (str_cblp2kategori1.recordType)({
                    'kd_kategori1': '',
                    'nama_kategori1': '-----'
                });
                str_cblp2kategori1.insert(0, r);
            },
            loadexception: function(event, options, response, error) {
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    var cblp2kategori1 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 1 ',
        id: 'id_cblp2kategori1',
        store: str_cblp2kategori1,
        valueField: 'kd_kategori1',
        displayField: 'nama_kategori1',
        typeAhead: true,
        triggerAction: 'all',
        editable: false,
        width: 170,
        anchor: '90%',
        hiddenName: 'nama_kategori1',
        emptyText: 'Pilih kategori 1',
        listeners: {
            'select': function(combo, records) {
                var kdhp_cbkategori1 = cblp2kategori1.getValue();
                cblp2kategori2.store.proxy.conn.url = '<?= site_url("kategori3/get_kategori2") ?>/' + kdhp_cbkategori1;
                cblp2kategori2.store.reload();
            }
        }
    });

    // combobox kategori2

    var str_cblp2kategori2 = new Ext.data.Store({
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
                var r = new (str_cblp2kategori2.recordType)({
                    'kd_kategori2': '',
                    'nama_kategori2': '-----'
                });
                str_cblp2kategori2.insert(0, r);
            },
            loadexception: function(event, options, response, error) {
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    var cblp2kategori2 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 2',
        id: 'id_cblp2kategori2',
        mode: 'local',
        store: str_cblp2kategori2,
        valueField: 'kd_kategori2',
        displayField: 'nama_kategori2',
        typeAhead: true,
        triggerAction: 'all',
        editable: false,
        width: 170,
        anchor: '90%',
        hiddenName: 'nama_kategori2',
        emptyText: 'Pilih kategori 2',
        listeners: {
            select: function(combo, records) {
                var kd_hp_cbkategori1 = cblp2kategori1.getValue();
                var kd_hp_cbkategori2 = this.getValue();
                cblp2kategori3.setValue();
                cblp2kategori3.store.proxy.conn.url = '<?= site_url("kategori4/get_kategori3") ?>/' + kd_hp_cbkategori1 + '/' + kd_hp_cbkategori2;
                cblp2kategori3.store.reload();
            }
        }
    });

    // combobox kategori3

    var str_cblp2kategori3 = new Ext.data.Store({
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
                var r = new (str_cblp2kategori3.recordType)({
                    'kd_kategori3': '',
                    'nama_kategori3': '-----'
                });
                str_cblp2kategori3.insert(0, r);
            },
            loadexception: function(event, options, response, error) {
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    var cblp2kategori3 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 3',
        id: 'id_cblp2kategori3',
        mode: 'local',
        store: str_cblp2kategori3,
        valueField: 'kd_kategori3',
        displayField: 'nama_kategori3',
        typeAhead: true,
        triggerAction: 'all',
        editable: false,
        width: 170,
        anchor: '90%',
        hiddenName: 'nama_kategori3',
        emptyText: 'Pilih kategori 3',
        listeners: {
            select: function(combo, records) {
                var kd_hp_cbkategori1 = cblp2kategori1.getValue();
                var kd_hp_cbkategori2 = cblp2kategori2.getValue();
                var kd_hp_cbkategori3 = this.getValue();
                cblp2kategori4.setValue();
                cblp2kategori4.store.proxy.conn.url = '<?= site_url("master_barang/get_kategori4") ?>/' + kd_hp_cbkategori1 + '/' + kd_hp_cbkategori2 + '/' + kd_hp_cbkategori3;
                cblp2kategori4.store.reload();
            }
        }
    });

    // combobox kategori4

    var str_cblp2kategori4 = new Ext.data.Store({
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
                var r = new (str_cblp2kategori4.recordType)({
                    'kd_kategori4': '',
                    'nama_kategori4': '-----'
                });
                str_cblp2kategori4.insert(0, r);
            },
            loadexception: function(event, options, response, error) {
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    var cblp2kategori4 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 4 ',
        id: 'id_cblp2kategori4',
        mode: 'local',
        store: str_cblp2kategori4,
        valueField: 'kd_kategori4',
        displayField: 'nama_kategori4',
        typeAhead: true,
        triggerAction: 'all',
        // allowBlank: false,
        editable: false,
        width: 170,
        anchor: '90%',
        hiddenName: 'nama_kategori4',
        emptyText: 'Pilih kategori 4'
    });

    var strcblp2member = new Ext.data.ArrayStore({
        fields: ['kd_member'],
        data: []
    });

    var strgridlp2member = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_member', 'nmmember'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("laporan_purchase_order/search_member") ?>',
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

    var searchgridlp2member = new Ext.app.SearchField({
        store: strgridlp2member,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgridlp2member'
    });

    var gridlp2member = new Ext.grid.GridPanel({
        store: strgridlp2member,
        stripeRows: true,
        frame: true,
        border: true,
        columns: [{
                header: 'Kode Member',
                dataIndex: 'kd_member',
                width: 80,
                sortable: true

            }, {
                header: 'Nama Member',
                dataIndex: 'nmmember',
                width: 300,
                sortable: true
            }],
        tbar: new Ext.Toolbar({
            items: [searchgridlp2member]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridlp2member,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('id_cblp2member').setValue(sel[0].get('kd_supplier'));
                    menulp2member.hide();
                }
            }
        }
    });

    var menulp2member = new Ext.menu.Menu();
    menulp2member.add(new Ext.Panel({
        title: 'Pilih Member',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridlp2member],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menulp2member.hide();
                }
            }]
    }));

    Ext.ux.TwinComboMember = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            strgridlp2member.load();
            menulp2member.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    menulp2member.on('hide', function() {
        var sf = Ext.getCmp('id_searchgridlp2member').getValue();
        if (sf != '') {
            Ext.getCmp('id_searchgridlp2member').setValue('');
            searchgridlp2member.onTrigger2Click();
        }
    });

    var cblp2member = new Ext.ux.TwinComboMember({
        fieldLabel: 'Member',
        id: 'id_cblp2member',
        store: strcblp2member,
        mode: 'local',
        valueField: 'kd_member',
        displayField: 'kd_member',
        typeAhead: true,
        triggerAction: 'all',
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_memberr',
        emptyText: 'Pilih Member'
    });

    var headerlp2tanggal = {
        layout: 'column',
        border: false,
        items: [{
                columnWidth: .8,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: {labelSeparator: ''},
                items: [{
                        xtype: 'fieldset',
                        autoHeight: true,
                        items: [
                            {
                                layout: 'column',
                                items: [
                                    {
                                        columnWidth: .5,
                                        layout: 'form',
                                        border: false,
                                        labelWidth: 100,
                                        defaults: {labelSeparator: ''},
                                        items: [{
                                                xtype: 'datefield',
                                                fieldLabel: 'Dari Tgl ',
                                                name: 'dari_tgl',
                                                allowBlank: false,
                                                format: 'd-m-Y',
                                                editable: false,
                                                id: 'id_dari_tgl_lp2',
                                                anchor: '90%',
                                                value: ''
                                            }
                                        ]
                                    },
                                    {
                                        columnWidth: .5,
                                        layout: 'form',
                                        border: false,
                                        labelWidth: 100,
                                        defaults: {labelSeparator: ''},
                                        items: [
                                            {
                                                xtype: 'datefield',
                                                fieldLabel: 'Sampai Tgl',
                                                name: 'sampai_tgl',
                                                allowBlank: false,
                                                editable: false,
                                                format: 'd-m-Y',
                                                id: 'id_smp_tgl_lp2',
                                                anchor: '90%',
                                                value: ''
                                            }
                                        ]
                                    },
                                ]
                            }
                        ]
                    }]
            }
        ]
    }

    var headerlp2kategori = {
        layout: 'column',
        border: false,
        items: [{
                columnWidth: .8,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: {labelSeparator: ''},
                items: [{
                        xtype: 'fieldset',
                        autoHeight: true,
                        items: [
                            {
                                layout: 'column',
                                items: [
                                    {
                                        columnWidth: .5,
                                        layout: 'form',
                                        border: false,
                                        labelWidth: 100,
                                        defaults: {labelSeparator: ''},
                                        items: [
                                            cblp2kategori1,
                                            cblp2kategori2,
                                            cblp2kategori3,
                                            cblp2kategori4,
                                            lp2_cbsatuan
                                        ]},
                                    {
                                        columnWidth: .5,
                                        layout: 'form',
                                        border: false,
                                        labelWidth: 100,
                                        defaults: {labelSeparator: ''},
                                        items: [
                                            cblpenjualan2produk,
                                            cblp2suplier,
                                            cblp2member,
                                            lp2_cbukuran
                                        ]



                                    }
                                ]
                            }
                        ]
                    }
                ]
            }]

    }



    var headerlaporanpenjualan2 = {
        buttonAlign: 'left',
        layout: 'form',
        border: false,
        labelWidth: 100,
        defaults: {labelSeparator: ''},
        items: [headerlp2tanggal, headerlp2kategori, {
                xtype: 'hidden',
                name: 'kd_supplier',
                id: 'lpo_kd_supplier',
                value: ''
            }

        ],
        buttons: [{
                text: 'Print',
                formBind: true,
                handler: function() {
                    var kd_kategori1 = Ext.getCmp('id_cblp2kategori1').getValue();
                    var kd_kategori2 = Ext.getCmp('id_cblp2kategori2').getValue();
                    var kd_kategori3 = Ext.getCmp('id_cblp2kategori3').getValue();
                    var kd_kategori4 = Ext.getCmp('id_cblp2kategori4').getValue();
                    var kd_satuan = Ext.getCmp('id_lp2_cbsatuan').getValue();
                    var kd_produk = Ext.getCmp('id_cblp2_kd_produk').getValue();
                    var kd_supplier = Ext.getCmp('id_cblp2suplier').getValue();
                    var dari_tgl = Ext.getCmp('id_dari_tgl_lp2').getRawValue();
                    var sampai_tgl = Ext.getCmp('id_smp_tgl_lp2').getRawValue();
                    winlaporanpenjualan2print.show();
                    Ext.getDom('laporanpenjualan2print').src = '<?= site_url("laporan_penjualan2/print_form") ?>';
                }
            }, {
                text: 'Cancel',
                handler: function() {
                    clearlaporanpenjualan2();
                }
            }]
    };
    var winlaporanpenjualan2print = new Ext.Window({
        id: 'id_winlaporanpenjualan2print',
        Title: 'Print Laporan Penjualan 2',
        closeAction: 'hide',
        width: 900,
        height: 450,
        layout: 'fit',
        border: false,
        html: '<iframe style="width:100%;height:390px;" id="laporanpenjualan2print" src=""></iframe>'
    });

    var laporanpenjualan2 = new Ext.FormPanel({
        id: 'rpt_penjualan2',
        border: false,
        frame: true,
        monitorValid: true,
        labelWidth: 130,
        items: [{
                bodyStyle: {
                    margin: '0px 0px 15px 0px'
                },
                items: [headerlaporanpenjualan2]
            }
        ]
    });

    function clearlaporanpenjualan2() {
        Ext.getCmp('rpt_penjualan2').getForm().reset();

    }
</script>