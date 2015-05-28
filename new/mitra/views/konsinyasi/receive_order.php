<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">
    var strcbkrosuplier = new Ext.data.ArrayStore({
        fields: ['kd_supplier'],
        data: []
    });

    var strgridkrosuplier = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_supplier', 'nama_supplier'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("konsinyasi_receive_order/search_supplier") ?>',
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

    var searchgridkrosuplier = new Ext.app.SearchField({
        store: strgridkrosuplier,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgridkrosuplier'
    });

    strgridkrosuplier.on('load', function() {
        Ext.getCmp('id_searchgridkrosuplier').focus();
    });

    var gridkrosuplier = new Ext.grid.GridPanel({
        store: strgridkrosuplier,
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
            items: [searchgridkrosuplier]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridkrosuplier,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('id_cbkrosuplier').setValue(sel[0].get('kd_supplier'));
                    Ext.getCmp('kro_nama_supplier').setValue(sel[0].get('nama_supplier'));

                    menukrosuplier.hide();
                }
            }
        }
    });

    var menukrosuplier = new Ext.menu.Menu();
    menukrosuplier.add(new Ext.Panel({
        title: 'Pilih Supplier',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridkrosuplier],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menukrosuplier.hide();
                }
            }]
    }));

    Ext.ux.TwinCombokroSuplier = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            //load store grid
            strgridkrosuplier.load();
            menukrosuplier.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
                trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    menukrosuplier.on('hide', function() {
        var sf = Ext.getCmp('id_searchgridkrosuplier').getValue();
        if (sf != '') {
            Ext.getCmp('id_searchgridkrosuplier').setValue('');
            searchgridkrosuplier.onTrigger2Click();
        }
    });

    var cbkrosuplier = new Ext.ux.TwinCombokroSuplier({
        fieldLabel: 'Supplier <span class="asterix">*</span>',
        id: 'id_cbkrosuplier',
        store: strcbkrosuplier,
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
    var strcbkroekspedisi = new Ext.data.ArrayStore({
        fields: ['kd_ekspedisi'],
        data: []
    });

    var strgridkroekspedisi = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_ekspedisi', 'nama_ekspedisi'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("konsinyasi_receive_order/search_ekspedisi") ?>',
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

    var searchgridkroekspedisi = new Ext.app.SearchField({
        store: strgridkroekspedisi,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgridkroekspedisi'
    });


    var gridkroekspedisi = new Ext.grid.GridPanel({
        store: strgridkroekspedisi,
        stripeRows: true,
        frame: true,
        border: true,
        columns: [{
                header: 'Kode Ekspedisi',
                dataIndex: 'kd_ekspedisi',
                width: 80,
                sortable: true,
            }, {
                header: 'Nama Ekspedisi',
                dataIndex: 'nama_ekspedisi',
                width: 300,
                sortable: true,
            }],
        tbar: new Ext.Toolbar({
            items: [searchgridkroekspedisi]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridkroekspedisi,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('id_cbkroekspedisi').setValue(sel[0].get('kd_ekspedisi'));
                    Ext.getCmp('nama_cbkroekspedisi').setValue(sel[0].get('nama_ekspedisi'));
                    menukroekspedisi.hide();
                }
            }
        }
    });

    var menukroekspedisi = new Ext.menu.Menu();
    menukroekspedisi.add(new Ext.Panel({
        title: 'Pilih Ekspedisi',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridkroekspedisi],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menukroekspedisi.hide();
                }
            }]
    }));

    Ext.ux.TwinCombokroekspedisi = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            //load store grid
            strgridkroekspedisi.load();
            menukroekspedisi.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
                trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    menukroekspedisi.on('hide', function() {
        var sf = Ext.getCmp('id_searchgridkroekspedisi').getValue();
        if (sf != '') {
            Ext.getCmp('id_searchgridkroekspedisi').setValue('');
            searchgridkroekspedisi.onTrigger2Click();
        }
    });

    // SATUAN ekspedisi
    var strcbkrosatuanekspedisi = new Ext.data.ArrayStore({
        fields: ['kd_satuan'],
        data: []
    });

    var strgridkrosatuanekspedisi = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_satuan', 'nm_satuan_eksp'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("konsinyasi_receive_order/search_satuan") ?>',
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

    var searchgridkrosatuanekspedisi = new Ext.app.SearchField({
        store: strgridkrosatuanekspedisi,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgridkrosatuanekspedisi'
    });


    var gridkrosatuanekspedisi = new Ext.grid.GridPanel({
        store: strgridkrosatuanekspedisi,
        stripeRows: true,
        frame: true,
        border: true,
        columns: [{
                header: 'Kode Satuan',
                dataIndex: 'kd_satuan',
                width: 80,
                sortable: true,
            }, {
                header: 'Nama Satuan',
                dataIndex: 'nm_satuan_eksp',
                width: 300,
                sortable: true,
            }],
        tbar: new Ext.Toolbar({
            items: [searchgridkrosatuanekspedisi]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridkrosatuanekspedisi,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('id_cbkrosatuanekspedisi').setValue(sel[0].get('kd_satuan'));
                    Ext.getCmp('nama_cbkrosatuanekspedisi').setValue(sel[0].get('nm_satuan_eksp'));
                    menukrosatuanekspedisi.hide();
                }
            }
        }
    });

    var menukrosatuanekspedisi = new Ext.menu.Menu();
    menukrosatuanekspedisi.add(new Ext.Panel({
        title: 'Pilih Satuan',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridkrosatuanekspedisi],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menukrosatuanekspedisi.hide();
                }
            }]
    }));

    Ext.ux.TwinCombokrosatuanekspedisi = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            //load store grid
            strgridkrosatuanekspedisi.load({
                params: {
                    kd_ekspedisi: Ext.getCmp('id_cbkroekspedisi').getValue(),
                }
            });
            menukrosatuanekspedisi.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
                trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    menukrosatuanekspedisi.on('hide', function() {
        var sf = Ext.getCmp('id_searchgridkrosatuanekspedisi').getValue();
        if (sf != '') {
            Ext.getCmp('id_searchgridkrosatuanekspedisi').setValue('');
            searchgridkrosatuanekspedisi.onTrigger2Click();
        }
    });

    var headerkonsinyasireceiveorder = {
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
                        id: 'kro_no_do',
                        anchor: '90%',
                        value: ''
                    }, cbkrosuplier,
                    new Ext.form.Checkbox({
                        xtype: 'checkbox',
                        fieldLabel: 'Scan Barcode',
                        boxLabel: 'Ya',
                        name: 'scan_barcode',
                        id: 'kro_scan_barcode',
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
                        id: 'kro_tanggal_terima',
                        anchor: '90%',
                        value: ''
                    }, {
                        xtype: 'textfield',
                        fieldLabel: 'Nama Supplier',
                        name: 'nama_supplier',
                        readOnly: true,
                        fieldClass: 'readonly-input',
                        id: 'kro_nama_supplier',
                        anchor: '90%',
                        value: ''
                    }, {
                        xtype: 'textfield',
                        fieldLabel: 'Tanggal Input',
                        name: 'tanggal',
                        fieldClass: 'readonly-input',
                        readOnly: true,
                        id: 'kro_tanggal',
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
                        id: 'kro_bukti_supplier',
                        anchor: '90%'
                    }, {
                        xtype: 'datefield',
                        fieldLabel: 'Tgl Bukti Supp <span class="asterix">*</span>',
                        name: 'tanggal_bukti',
                        allowBlank: false,
                        format: 'd-m-Y',
                        editable: false,
                        id: 'kro_tanggal_bukti',
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
                                id: 'kro_peruntukan_supermarket',
                                checked: true
                            }, {
                                boxLabel: 'Distribusi',
                                name: 'kd_peruntukan',
                                inputValue: '1',
                                id: 'kro_peruntukan_distribusi'
                            }]
                    }]
            }]
    }

    /* SubBlok */
    var strcbkdsubblokkro = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['sub', 'nama_sub'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("konsinyasi_receive_order/get_sub_blok") ?>',
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

    var strgridsubblokkro = new Ext.data.Store({
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
            url: '<?= site_url("konsinyasi_receive_order/get_rows_lokasi") ?>',
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
    var searchgridkrosubblok = new Ext.app.SearchField({
        store: strgridsubblokkro,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        id: 'idsearchgridkrosubblok'
    });

    // top toolbar
    var tbgridkrosubblok = new Ext.Toolbar({
        items: [searchgridkrosubblok]
    });

    var gridkrosubblok = new Ext.grid.GridPanel({
        store: strgridsubblokkro,
        stripeRows: true,
        frame: true,
        border: true,
        tbar: tbgridkrosubblok,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridsubblokkro,
            displayInfo: true
        }),
        columns: [{
                dataIndex: 'kd_lokasi',
                hidden: true,
            }, {
                dataIndex: 'kd_blok',
                hidden: true,
            }, {
                dataIndex: 'kd_sub_blok',
                hidden: true,
            }, {
                header: 'Kode',
                dataIndex: 'sub',
                width: 90,
                sortable: true,
            }, {
                header: 'Sub Blok Lokasi',
                dataIndex: 'nama_sub',
                width: 200,
                sortable: true,
            }],
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('ekro_sub').setValue(sel[0].get('sub'));
                    Ext.getCmp('ekro_nama_sub').setValue(sel[0].get('nama_sub'));

                    menusubblokreceiveorderkonsinyasi.hide();
                }
            }
        }
    });

    var menusubblokreceiveorderkonsinyasi = new Ext.menu.Menu();
    menusubblokreceiveorderkonsinyasi.add(new Ext.Panel({
        title: 'Pilih Sub Blok Lokasi',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 350,
        height: 250,
        closeAction: 'hide',
        plain: true,
        items: [gridkrosubblok],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menusubblokreceiveorderkonsinyasi.hide();
                }
            }]
    }));

    Ext.ux.TwinCombokroSubBlok = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            //load store grid
            strgridsubblokkro.load({
                params: {
                    kd_produk: Ext.getCmp('ekro_kd_produk').getValue()
                }
            });
            menusubblokreceiveorderkonsinyasi.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
                trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
    /* END SubBlok*/

    var strkonsinyasireceiveorder = new Ext.data.Store({
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

    strkonsinyasireceiveorder.on('update', function() {

        var qty_po = Ext.getCmp('ekro_qty_po').getValue();
        var qty = Ext.getCmp('ekro_qty').getValue();
        var qty_terima = Ext.getCmp('ekro_qty_terima').getValue();
        var qty_realisasi = qty_po - qty_terima;
        var jumlah_barcode = Ext.getCmp('kro_jumlah_barcode').getValue();

        if (qty > qty_realisasi) {
            Ext.Msg.show({
                title: 'Error',
                msg: 'Qty RO + Qty tidak boleh lebih besar dari Qty PK',
                modal: true,
                icon: Ext.Msg.ERROR,
                buttons: Ext.Msg.OK,
                fn: function(btn) {
                    if (btn == 'ok') {
                        Ext.getCmp('ekro_qty').reset()
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
        // Ext.getCmp('kro_jumlah_barcode').reset()
        // }
        // }                          
        // });
        // Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');  
        // return;
        // }
    });
    var strcbkronopo = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['no_po'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("konsinyasi_receive_order/get_all_po") ?>',
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

    var strcbkroproduk = new Ext.data.ArrayStore({
        fields: ['kd_produk'],
        data: []
    });

    var strgridkroproduk = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_produk', 'kd_produk_supp', 'kd_produk_lama', 'nama_produk', 'qty_po', 'qty_do', 'qty_terima','qty_retur', 'nm_satuan', 'jumlah_barcode'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("konsinyasi_receive_order/search_produk_by_no_po") ?>',
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
        store: strgridkroproduk
    });

    searchFieldRO.onTrigger1Click = function(evt) {
        if (this.hasSearch) {
            this.el.dom.value = '';

            // Get the value of search field
            var fid = Ext.getCmp('ekro_no_po').getValue();
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
        var fid = Ext.getCmp('ekro_no_po').getValue();
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
        store: strgridkroproduk,
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
                    Ext.getCmp('ekro_kd_produk').setValue(sel[0].get('kd_produk'));
                    Ext.getCmp('ekro_nama_produk').setValue(sel[0].get('nama_produk'));
                    Ext.getCmp('ekro_qty_po').setValue(sel[0].get('qty_po'));
                    Ext.getCmp('ekro_qty_terima').setValue(sel[0].get('qty_terima'));
                    Ext.getCmp('ekro_qty_retur').setValue(sel[0].get('qty_retur'));
                    Ext.getCmp('ekro_qty').setValue(sel[0].get('qty_do'));
                    Ext.getCmp('ekro_nm_satuan').setValue(sel[0].get('nm_satuan'));
                    Ext.getCmp('kro_jumlah_barcode').setValue(sel[0].get('jumlah_barcode'));
                    Ext.getCmp('ekro_qty').setValue(0);
                    Ext.getCmp('ekro_qty').focus();
                    menukroproduk.hide();
                }
            }
        }
    });

    var menukroproduk = new Ext.menu.Menu();
    menukroproduk.add(new Ext.Panel({
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
                    menukroproduk.hide();
                }
            }]
    }));

    Ext.ux.TwinCombokroproduk = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            if (Ext.getCmp('ekro_no_po').getValue() == '') {
                Ext.Msg.show({
                    title: 'Error',
                    msg: 'Silahkan pilih No Surat Pesanan terlebih dulu',
                    modal: true,
                    icon: Ext.Msg.ERROR,
                    buttons: Ext.Msg.OK
                });
                return;
            }
            //load store grid
            strgridkroproduk.load({
                params: {
                    no_po: Ext.getCmp('ekro_no_po').getValue()
                }
            });
            menukroproduk.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
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
            url: '<?= site_url("konsinyasi_receive_order/search_produk_by_no_po") ?>',
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
        Ext.getCmp('kro_scan_barcode_kode').focus();
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
            var fid = Ext.getCmp('ekro_no_po').getValue();
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
        var fid = Ext.getCmp('ekro_no_po').getValue();
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
                    Ext.getCmp('ekro_kd_produk').setValue(sel[0].get('kd_produk'));
                    Ext.getCmp('ekro_nama_produk').setValue(sel[0].get('nama_produk'));
                    Ext.getCmp('ekro_qty_po').setValue(sel[0].get('qty_po'));
                    Ext.getCmp('ekro_qty_terima').setValue(sel[0].get('qty_terima'));
                    Ext.getCmp('ekro_qty_retur').setValue(sel[0].get('qty_retur'));
                    Ext.getCmp('ekro_qty').setValue(sel[0].get('qty_do'));
                    Ext.getCmp('ekro_nm_satuan').setValue(sel[0].get('nm_satuan'));
                    Ext.getCmp('kro_jumlah_barcode').setValue(sel[0].get('jumlah_barcode'));
                    Ext.getCmp('ekro_qty').setValue(0);
                    Ext.getCmp('ekro_qty').focus();
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
                id: 'kro_scan_barcode_kode',
                anchor: '90%',
                value: '',
                listeners: {
                    specialKey: function(field, e) {
                        if (e.getKey() === e.RETURN || e.getKey() === e.ENTER) {
                            Ext.Ajax.request({
                                url: '<?= site_url("konsinyasi_receive_order/search_produk_by_no_po") ?>',
                                method: 'POST',
                                params: {
                                    no_po: Ext.getCmp('ekro_no_po').getValue(),
                                    query: Ext.getCmp('kro_scan_barcode_kode').getValue(),
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
                        url: '<?= site_url("konsinyasi_receive_order/search_produk_by_no_po") ?>',
                        method: 'POST',
                        params: {
                            no_po: Ext.getCmp('ekro_no_po').getValue(),
                            query: Ext.getCmp('kro_scan_barcode_kode').getValue(),
                            sender: 'scan'
                        },
                        callback: function(opt, success, responseObj) {
                            var scn = Ext.util.JSON.decode(responseObj.responseText);
                            if (scn.success === true) {
                                Ext.getCmp('kro_kd_produk_scan').setValue(scn.data.kd_produk);
                                Ext.getCmp('ekro_kd_produk').setValue(scn.data.kd_produk);
                                Ext.getCmp('ekro_nama_produk').setValue(scn.data.nama_produk);
                                Ext.getCmp('ekro_qty_po').setValue(scn.data.qty_po);
                                Ext.getCmp('ekro_qty_terima').setValue(scn.data.qty_terima);
                                Ext.getCmp('ekro_qty_retur').setValue(scn.data.qty_retur); 
                                Ext.getCmp('ekro_qty').setValue(scn.data.qty_do);
                                Ext.getCmp('ekro_nm_satuan').setValue(scn.data.nm_satuan);
                                Ext.getCmp('kro_jumlah_barcode').setValue(scn.data.jumlah_barcode);
                                Ext.getCmp('ekro_qty').setValue(0);
                                Ext.getCmp('ekro_qty').focus();
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
    var strcbkronopo = new Ext.data.ArrayStore({
        fields: ['no_po'],
        data: []
    });

    function validasi_qty() {
        var qty_po = Ext.getCmp('ekro_qty_po').getValue();
        var qty = Ext.getCmp('ekro_qty').getValue();
        var qty_realisasi = qty_po - Ext.getCmp('ekro_qty_terima').getValue();

        if (qty > qty_realisasi) {
            Ext.Msg.show({
                title: 'Error',
                msg: 'Qty tidak boleh lebih besar dari Qty PK',
                modal: true,
                icon: Ext.Msg.ERROR,
                buttons: Ext.Msg.OK,
                fn: function(btn) {
                    if (btn === 'ok') {
                        Ext.getCmp('ekro_qty').reset();
                    }
                }
            });
            Ext.MessageBox.getDialog().getEl().setStyle('z-index', '80000');
            return;
        }
    }
    ;

    var strgridkronopo = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['no_po'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("konsinyasi_receive_order/get_all_po") ?>',
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

    strgridkronopo.on('load', function() {
        Ext.getCmp('search_query_no_po').focus();
    });

    var searchFieldRONoPO = new Ext.app.SearchField({
        width: 220,
        id: 'search_query_no_po',
        store: strgridkronopo
    });
    searchFieldRONoPO.onTrigger1Click = function(evt) {
        if (this.hasSearch) {
            this.el.dom.value = '';

            // Get the value of search field
            var fid = Ext.getCmp('id_cbkrosuplier').getValue();
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

    searchFieldRONoPO.onTrigger2Click = function(evt) {
        var text = this.getRawValue();
        if (text.length < 1) {
            this.onTrigger1Click();
            return;
        }

        // Get the value of search field
        var fid = Ext.getCmp('id_cbkrosuplier').getValue();
        var o = {start: 0, kd_supplier: fid};

        this.store.baseParams = this.store.baseParams || {};
        this.store.baseParams[this.paramName] = text;
        this.store.reload({params: o});
        this.hasSearch = true;
        this.triggers[0].show();
    };

    // top toolbar
    var tbsearchnopo = new Ext.Toolbar({
        items: [searchFieldRONoPO]
    });

    var gridkronopo = new Ext.grid.GridPanel({
        store: strgridkronopo,
        stripeRows: true,
        frame: true,
        border: true,
        columns: [{
                header: 'No Surat Pesanan',
                dataIndex: 'no_po',
                width: 200,
                sortable: true
            }],
        tbar: tbsearchnopo,
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('ekro_no_po').setValue(sel[0].get('no_po'));
                    menukronopo.hide();

                    var scan = Ext.getCmp('kro_scan_barcode').getValue();

                    if (scan) {

                        // strgridkroscanbarang.load({
                        // params: {
                        // no_po: Ext.getCmp('ekro_no_po').getValue()                                 
                        // }
                        // });
                        strgridkroscanbarang.load();
                        Ext.getCmp('kro_scan_barcode_kode').setValue('');
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

    var menukronopo = new Ext.menu.Menu();
    menukronopo.add(new Ext.Panel({
        title: 'Pilih No Surat Pesanan',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 300,
        height: 250,
        closeAction: 'hide',
        plain: true,
        items: [gridkronopo],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menukronopo.hide();
                }
            }]
    }));

    Ext.ux.TwinCombokroNoPO = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            //load store grid
            strgridkronopo.load({
                params: {
                    kd_supplier: Ext.getCmp('id_cbkrosuplier').getValue()
                }
            });
            menukronopo.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
                trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    var editorkonsinyasireceiveorder = new Ext.ux.grid.RowEditor({
        saveText: 'Update'
    });

    var gridkonsinyasireceiveorder = new Ext.grid.GridPanel({
        store: strkonsinyasireceiveorder,
        stripeRows: true,
        height: 400,
        frame: true,
        border: true,
        plugins: [editorkonsinyasireceiveorder],
        tbar: [{
                icon: BASE_ICONS + 'add.png',
                text: 'Add',
                handler: function() {
                    if (Ext.getCmp('id_cbkrosuplier').getValue() === '') {
                        Ext.Msg.show({
                            title: 'Error',
                            msg: 'Silahkan pilih supplier terlebih dulu',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK
                        });
                        return;
                    }
                    var rowkonsinyasireceiveorder = new gridkonsinyasireceiveorder.store.recordType({
                        no_po: '',
                        kd_produk: '',
                        qty: ''
                    });
                    editorkonsinyasireceiveorder.stopEditing();
                    strkonsinyasireceiveorder.insert(0, rowkonsinyasireceiveorder);
                    gridkonsinyasireceiveorder.getView().refresh();
                    gridkonsinyasireceiveorder.getSelectionModel().selectRow(0);
                    editorkonsinyasireceiveorder.startEditing(0);
                }
            }, {
                ref: '../removeBtn',
                icon: BASE_ICONS + 'delete.gif',
                text: 'Remove',
                disabled: true,
                handler: function() {
                    editorkonsinyasireceiveorder.stopEditing();
                    var s = gridkonsinyasireceiveorder.getSelectionModel().getSelections();
                    for (var i = 0, r; r = s[i]; i++) {
                        strkonsinyasireceiveorder.remove(r);
                    }
                }
            }],
        columns: [new Ext.grid.RowNumberer({width: 30}), {
                header: 'No Surat Pesanan',
                dataIndex: 'no_po',
                width: 140,
                editor: new Ext.ux.TwinCombokroNoPO({
                    id: 'ekro_no_po',
                    store: strcbkronopo,
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
                editor: new Ext.ux.TwinCombokroproduk({
                    id: 'ekro_kd_produk',
                    store: strcbkroproduk,
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
                    id: 'ekro_nama_produk'
                })
            }, {
                header: 'Satuan',
                dataIndex: 'nm_satuan',
                width: 80,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'ekro_nm_satuan'
                })
            }, {
                header: 'Qty Pesanan',
                dataIndex: 'qty_po',
                width: 90,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'ekro_qty_po'
                })
            }, {
                header: 'Qty RO',
                dataIndex: 'qty_terima',
                width: 50,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'ekro_qty_terima'
                })
            },{
                header: 'Qty Retur',
                dataIndex: 'qty_retur',
                width: 80,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'ekro_qty_retur'
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
                    id: 'ekro_qty',
                    //allowBlank: false,
                    selectOnFocus: true,
                    listeners: {
                        'render': function(c) {
                            c.getEl().on('keyup', function() {
                                
				Ext.getCmp('kro_jumlah_barcode').setValue(qty);
                                
                                var max = parseFloat (Ext.getCmp('ekro_qty_po').getValue());
                                var jml = parseFloat(Ext.getCmp('ekro_qty_terima').getValue());
                                var retur = parseFloat(Ext.getCmp('ekro_qty_retur').getValue());
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
                                                
                                                Ext.getCmp('ekro_qty').reset();
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
                editor: new Ext.ux.TwinCombokroSubBlok({
                    id: 'ekro_sub',
                    store: strcbkdsubblokkro,
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
                            strcbkdsubblokkro.load();
                        }
                    }
                })
            }, {
                header: 'Sub Blok',
                dataIndex: 'nama_sub',
                width: 200,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'ekro_nama_sub'
                })
            }, {
                header: 'Nama Ekspedisi',
                dataIndex: 'nama_ekspedisi',
                width: 100,
                editor: new Ext.ux.TwinCombokroekspedisi({
                    id: 'nama_cbkroekspedisi',
                    store: strcbkroekspedisi,
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
                editor: new Ext.ux.TwinCombokrosatuanekspedisi({
                    id: 'nama_cbkrosatuanekspedisi',
                    store: strcbkrosatuanekspedisi,
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
                    id: 'kons_berat_ekspedisi',
                    //allowBlank: false,
                }
            }, {
                header: '',
                width: 0,
                dataIndex: 'jumlah_barcode',
                editor: {
                    xtype: 'numberfield',
                    id: 'kro_jumlah_barcode',
                    //allowBlank: false,
                }
            }, {
                header: '',
                width: 0,
                dataIndex: 'kd_ekspedisi',
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'id_cbkroekspedisi'
                })
            }, {
                header: '',
                width: 0,
                dataIndex: 'kd_satuan_ekspedisi',
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'id_cbkrosatuanekspedisi'
                })
            }]
    });

    gridkonsinyasireceiveorder.getSelectionModel().on('selectionchange', function(sm) {
        gridkonsinyasireceiveorder.removeBtn.setDisabled(sm.getCount() < 1);
    });

    var winkonsinyasireceiveorderprint = new Ext.Window({
        id: 'id_winkonsinyasireceiveorderprint',
        title: 'Print Receive Order Form',
        closeAction: 'hide',
        width: 900,
        height: 450,
        layout: 'fit',
        border: false,
        html: '<iframe style="width:100%;height:100%;" id="konsinyasireceiveorderprint" src=""></iframe>'
    });

    var konsinyasireceiveorder = new Ext.FormPanel({
        id: 'konsinyasireceiveorder',
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
                items: [headerkonsinyasireceiveorder]
            },
            gridkonsinyasireceiveorder,
        ],
        buttons: [{
                text: 'Save',
                formBind: true,
                id: 'kons_button-save',
                handler: function() {
                    var detailkonsinyasireceiveorder = new Array();
                    strkonsinyasireceiveorder.each(function(node) {
                        detailkonsinyasireceiveorder.push(node.data)
                    });
                    Ext.getCmp('konsinyasireceiveorder').getForm().submit({
                        url: '<?= site_url("konsinyasi_receive_order/update_row") ?>',
                        scope: this,
                        params: {
                            detail: Ext.util.JSON.encode(detailkonsinyasireceiveorder)
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
                                        // winkonsinyasireceiveorderprint.show();
                                        // Ext.getDom('konsinyasireceiveorderprint').src = r.printUrl;
                                    }
                                }
                            });

                            clearkonsinyasireceiveorder();
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
                    clearkonsinyasireceiveorder();
                }
            }],
        keys: [
            {
                key: [Ext.EventObject.ENTER], handler: function() {
                    Ext.getCmp('kons_button-save').focus();
                }
            }]
    });

    konsinyasireceiveorder.on('afterrender', function() {
        this.getForm().load({
            url: '<?= site_url("konsinyasi_receive_order/get_form") ?>',
            success: function(form, action) {
                var r = Ext.util.JSON.decode(action.response.responseText);
                if (r.data.user_peruntukan === "0") {
                    Ext.getCmp('kro_peruntukan_supermarket').setValue(true);
                    Ext.getCmp('kro_peruntukan_supermarket').show();
                    Ext.getCmp('kro_peruntukan_distribusi').hide();
                } else if (r.data.user_peruntukan === "1") {
                    Ext.getCmp('kro_peruntukan_distribusi').setValue(true);
                    Ext.getCmp('kro_peruntukan_supermarket').hide();
                    Ext.getCmp('kro_peruntukan_distribusi').show();
                } else {
                    Ext.getCmp('kro_peruntukan_supermarket').setValue(true);
                    Ext.getCmp('kro_peruntukan_supermarket').show();
                    Ext.getCmp('kro_peruntukan_distribusi').show();
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

    function clearkonsinyasireceiveorder() {
        Ext.getCmp('konsinyasireceiveorder').getForm().reset();
        Ext.getCmp('konsinyasireceiveorder').getForm().load({
            url: '<?= site_url("konsinyasi_receive_order/get_form") ?>',
            success: function(form, action) {
                var r = Ext.util.JSON.decode(action.response.responseText);
                if (r.data.user_peruntukan === "0") {
                    Ext.getCmp('kro_peruntukan_supermarket').setValue(true);
                    Ext.getCmp('kro_peruntukan_supermarket').show();
                    Ext.getCmp('kro_peruntukan_distribusi').hide();
                } else if (r.data.user_peruntukan === "1") {
                    Ext.getCmp('kro_peruntukan_distribusi').setValue(true);
                    Ext.getCmp('kro_peruntukan_supermarket').hide();
                    Ext.getCmp('kro_peruntukan_distribusi').show();
                } else {
                    Ext.getCmp('kro_peruntukan_supermarket').setValue(true);
                    Ext.getCmp('kro_peruntukan_supermarket').show();
                    Ext.getCmp('kro_peruntukan_distribusi').show();
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
        strkonsinyasireceiveorder.removeAll();
    }
</script>
