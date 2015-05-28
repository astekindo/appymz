<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>

<script type="text/javascript">
    var storeCboProduk_ibp = new Ext.data.ArrayStore({
        fields: ['kd_produk', 'nama_produk', 'nm_satuan'],
        data: []
    });

    var storeGrdiCboProduk_ibp = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_produk', 'nama_produk', 'nm_satuan'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("input_barang_pecah_controller/finalGetDataProduk") ?>',
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
     * deklarasi search grid pelanggan
     */
    var searchGridProduk_ibp = new Ext.app.SearchField({
        store: storeGrdiCboProduk_ibp,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_search_grid_produk_ibp'
    });

    var smGridProduk_ibp = new Ext.grid.CheckboxSelectionModel();

    /**
     * deklarasi grid pelanggan
     */
    var gridProduk_ibp = new Ext.grid.GridPanel({
        store: storeGrdiCboProduk_ibp,
        stripeRows: true,
        frame: true,
        border: true,
        sm: smGridProduk_ibp,
        columns: [{
                header: 'Kode Produk',
                dataIndex: 'kd_produk',
                width: 120,
                sortable: true
            }, {
                header: 'Nama Produk',
                dataIndex: 'nama_produk',
                width: 120,
                sortable: true
            }, {
                header: 'Nama Satuan',
                dataIndex: 'nm_satuan',
                width: 150,
                sortable: true
            }],
        tbar: new Ext.Toolbar({
            items: [searchGridProduk_ibp]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: storeGrdiCboProduk_ibp,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('id_combo_produk_ibp').setValue(sel[0].get('kd_produk'));
                    Ext.getCmp('id_grid_nama_produk_ibp').setValue(sel[0].get('nama_produk'));
                    Ext.getCmp('id_grid_nm_satuan_ibp').setValue(sel[0].get('nm_satuan'));
                    menuProduk_ibp.hide();
                }
            }
        }
    });
    /**
     * deklarasi menu pelanggan
     */
    var menuProduk_ibp = new Ext.menu.Menu();
    menuProduk_ibp.add(new Ext.Panel({
        title: 'Pilih Produk',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridProduk_ibp],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menuProduk_ibp.hide();
                }
            }]
    }));
    /**
     * deklarasi twin combo pelanggan
     * @returns {undefined} */
    Ext.ux.TwinComboProduk_ibp = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            //load store grid
            storeGrdiCboProduk_ibp.load();
            menuProduk_ibp.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
                trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
    menuProduk_ibp.on('hide', function() {
        var sf = Ext.getCmp('id_search_grid_produk_ibp').getValue();
        if (sf !== '') {
            Ext.getCmp('id_search_grid_produk_ibp').setValue('');
            searchGridProduk_ibp.onTrigger2Click();
        }
    });
    var comboProduk_ibp = new Ext.ux.TwinComboProduk_ibp({
        fieldLabel: 'Produk <span class="asterix">*</span>',
        id: 'id_combo_produk_ibp',
        store: storeCboProduk_ibp,
        mode: 'local',
        valueField: 'kd_produk',
        displayField: 'nama_produk',
        typeAhead: true,
        triggerAction: 'all',
        editable: false,
        anchor: '95%',
        width: 200,
        hiddenName: 'kd_produk',
        emptyText: 'Pilih Produk'
    });


    /**
     * start combo lokasi
     */

    var storeGridCboLokasi_ibp = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_lokasi', 'nama_lokasi'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("input_barang_pecah_controller/finalGetDataLokasi") ?>',
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
     * deklarasi search grid pelanggan
     */
    var searchGridLokasi_ibp = new Ext.app.SearchField({
        store: storeGridCboLokasi_ibp,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_search_grid_lokasi_ibp'
    });

    var smGridLokasi_ibp = new Ext.grid.CheckboxSelectionModel();

    /**
     * deklarasi grid pelanggan
     */
    var gridLokasi_ibp = new Ext.grid.GridPanel({
        store: storeGridCboLokasi_ibp,
        stripeRows: true,
        frame: true,
        border: true,
        sm: smGridLokasi_ibp,
        columns: [{
                header: 'Kode Lokasi',
                dataIndex: 'kd_lokasi',
                width: 120,
                sortable: true
            }, {
                header: 'Nama Lokasi',
                dataIndex: 'nama_lokasi',
                width: 120,
                sortable: true
            }],
        tbar: new Ext.Toolbar({
            items: [searchGridLokasi_ibp]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: storeGridCboLokasi_ibp,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('id_combo_kd_lokasi_awal_ibp').setValue(sel[0].get('kd_lokasi'));
                    storeCboBlok_ibp.load({
                        params: {
                            kd_lokasi: sel[0].get('kd_lokasi')
                        }
                    });
                    menuLokasi_ibp.hide();
                }
            }
        }
    });
    /**
     * deklarasi menu pelanggan
     */
    var menuLokasi_ibp = new Ext.menu.Menu();
    menuLokasi_ibp.add(new Ext.Panel({
        title: 'Pilih Lokasi',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridLokasi_ibp],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menuLokasi_ibp.hide();
                }
            }]
    }));
    /**
     * deklarasi twin combo pelanggan
     * @returns {undefined} */
    Ext.ux.TwinComboLokasi_ibp = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            //load store grid
            storeGridCboLokasi_ibp.load();
            menuLokasi_ibp.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
                trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
    menuLokasi_ibp.on('hide', function() {
        var sf = Ext.getCmp('id_search_grid_lokasi_ibp').getValue();
        if (sf !== '') {
            Ext.getCmp('id_search_grid_lokasi_ibp').setValue('');
            searchGridLokasi_ibp.onTrigger2Click();
        }
    });
    var comboLokasiAsal_ibp = new Ext.ux.TwinComboLokasi_ibp({
        fieldLabel: 'Lokasi <span class="asterix">*</span>',
        id: 'id_combo_kd_lokasi_awal_ibp',
        store: storeGridCboLokasi_ibp,
        mode: 'local',
        valueField: 'kd_lokasi',
        displayField: 'nama_lokasi',
        typeAhead: true,
        triggerAction: 'all',
        editable: false,
        anchor: '95%',
        width: 200,
        hiddenName: 'kd_lokasi',
        emptyText: 'Pilih Lokasi Asal'
    });

    /**
     * end of cbo lokasi asal
     */

    /**
     * start cbo lokasi tujuan
     */
    var searchGridLokasiTujuan_ibp = new Ext.app.SearchField({
        store: storeGridCboLokasi_ibp,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_search_grid_kd_lokasi_akhir_ibp'
    });

    var smGridLokasiTujuan_ibp = new Ext.grid.CheckboxSelectionModel();

    /**
     * deklarasi grid pelanggan
     */
    var gridLokasiTujuan_ibp = new Ext.grid.GridPanel({
        store: storeGridCboLokasi_ibp,
        stripeRows: true,
        frame: true,
        border: true,
        sm: smGridLokasiTujuan_ibp,
        columns: [{
                header: 'Kode Lokasi',
                dataIndex: 'kd_lokasi',
                width: 120,
                sortable: true
            }, {
                header: 'Nama Lokasi',
                dataIndex: 'nama_lokasi',
                width: 120,
                sortable: true
            }],
        tbar: new Ext.Toolbar({
            items: [searchGridLokasiTujuan_ibp]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: storeGridCboLokasi_ibp,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('id_combo_kd_lokasi_akhir_ibp').setValue(sel[0].get('kd_lokasi'));
                    menuLokasiTujuan_ibp.hide();
                }
            }
        }
    });
    /**
     * deklarasi menu pelanggan
     */
    var menuLokasiTujuan_ibp = new Ext.menu.Menu();
    menuLokasiTujuan_ibp.add(new Ext.Panel({
        title: 'Pilih Lokasi',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridLokasiTujuan_ibp],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menuLokasiTujuan_ibp.hide();
                }
            }]
    }));
    /**
     * deklarasi twin combo pelanggan
     * @returns {undefined} */
    Ext.ux.TwinComboLokasiTujuan_ibp = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            //load store grid
            storeGridCboLokasi_ibp.load();
            menuLokasiTujuan_ibp.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
                trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
    menuLokasiTujuan_ibp.on('hide', function() {
        var sf = Ext.getCmp('id_search_grid_kd_lokasi_akhir_ibp').getValue();
        if (sf !== '') {
            Ext.getCmp('id_search_grid_kd_lokasi_akhir_ibp').setValue('');
            searchGridLokasiTujuan_ibp.onTrigger2Click();
        }
    });

    var comboLokasiTujuan_ibp = new Ext.ux.TwinComboLokasiTujuan_ibp({
        fieldLabel: 'Lokasi <span class="asterix">*</span>',
        id: 'id_combo_kd_lokasi_akhir_ibp',
        store: storeGridCboLokasi_ibp,
        mode: 'local',
        valueField: 'kd_lokasi',
        displayField: 'nama_lokasi',
        typeAhead: true,
        triggerAction: 'all',
        editable: false,
        anchor: '95%',
        width: 200,
        hiddenName: 'kd_lokasi',
        emptyText: 'Pilih Lokasi Tujuan'
    });
    /**
     * start combo status
     */
    var valStoreCboTindakLanjut_ibp = [
        ['0', "Dimusnahkan"],
        ['1', "Dipindahkan"]
    ];
    var storeCboTindakLanjut_ibp = new Ext.data.ArrayStore({
        fields: [{
                name: 'key'
            }, {
                name: 'value'
            }],
        data: valStoreCboTindakLanjut_ibp
    });

    var cboTindakLanjut_ibp = new Ext.form.ComboBox({
        id: 'id_cbo_tndak_lanjut_ibp',
        name: 'tindak_lanjut',
        store: storeCboTindakLanjut_ibp,
        valueField: 'key',
        displayField: 'value',
        emptyText: 'Tindak Lanjut',
        mode: 'local',
        forceSelection: true,
        triggerAction: 'all',
    });


    var storeCboBlok_ibp = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_blok', 'nama_blok'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("input_barang_pecah_controller/finalGetDataBlok") ?>',
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


    var cboBlokAwal_ibp = new Ext.form.ComboBox({
        id: 'id_cbo_blok_awal_ibp',
        name: 'blok_awal',
        store: storeCboBlok_ibp,
        valueField: 'kd_blok',
        displayField: 'nama_blok',
        emptyText: 'Tindak Lanjut',
        mode: 'local',
        forceSelection: true,
        triggerAction: 'all',
        listeners: {
            'change': function() {
                storeCboSubBlok_ibp.load({
                    params: {
                        kd_lokasi: Ext.getCmp('id_combo_kd_lokasi_awal_ibp').getValue(),
                        kd_blok: this.getValue()
                    }
                });
            }
        }
    });

    var cboBlokAkhir_ibp = new Ext.form.ComboBox({
        id: 'id_cbo_blok_akhir_ibp',
        name: 'blok_akhir',
        store: storeCboBlok_ibp,
        valueField: 'kd_blok',
        displayField: 'nama_blok',
        emptyText: 'Tindak Lanjut',
        mode: 'local',
        forceSelection: true,
        triggerAction: 'all',
        listeners: {
            'change': function() {
                storeCboSubBlok_ibp.load({
                    params: {
                        kd_lokasi: Ext.getCmp('id_combo_kd_lokasi_akhir_ibp').getValue(),
                        kd_blok: this.getValue()
                    }
                });
            }
        }
    });

    var storeCboSubBlok_ibp = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_sub_blok', 'nama_sub_blok'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("input_barang_pecah_controller/finalGetDataSubBlok") ?>',
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


    var cboSubBlokAwal_ibp = new Ext.form.ComboBox({
        id: 'id_cbo_sub_blok_awal_ibp',
        name: 'blok_awal',
        store: storeCboSubBlok_ibp,
        valueField: 'kd_sub_blok',
        displayField: 'nama_sub_blok',
        emptyText: 'Sub Blok',
        mode: 'local',
        forceSelection: true,
        triggerAction: 'all',
        listeners: {
            'change': function() {

            }
        }
    });


    var cboSubBlokAkhir_ibp = new Ext.form.ComboBox({
        id: 'id_cbo_sub_blok_akhir_ibp',
        name: 'blok_akhir',
        store: storeCboSubBlok_ibp,
        valueField: 'kd_sub_blok',
        displayField: 'nama_sub_blok',
        emptyText: 'Sub Blok',
        mode: 'local',
        forceSelection: true,
        triggerAction: 'all',
        listeners: {
            'change': function() {

            }
        }
    });


    /**
     * header 
     */
    var headerInputBarangPecah_ibp = {
        layout: 'column',
        border: false,
        buttonAlign: 'left',
        style: 'padding:5px',
        frame: true,
        items: [{
                columnWidth: .5,
                layout: 'form',
                border: false,
                labelWidth: 100,
                buttonAlign: 'left',
                defaults: {labelSeparator: ''},
                items: [
                    //cboPelanggan_cpd
                    {xtype: 'textfield',
                        anchor: '90%',
                        fieldLabel: 'No Bukti'},
                    {
                        xtype: 'datefield',
                        fieldLabel: 'Tanggal',
                        emptyText: 'Tanggal',
                        name: 'tanggal',
                        id: 'id_tanggal_input__barang_pecah_ibp',
                        maxLength: 255,
                        anchor: '90%',
                        value: '',
                        format: 'Y-m-d'

                    }
                ]
            }, {
                columnWidth: .5,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: {labelSeparator: ''},
                items: [
                    {
                        xtype: 'textfield',
                        fieldLabel: 'No.Ref',
                        anchor: '90%'
                    }
                ]
            }]
    };

    //grid data store
    var storeInputBarangPecah_ibp = new Ext.data.Store({
        autoSave: false,
        reader: new Ext.data.JsonReader({
            fields: [
                {name: 'kd_produk', allowBlank: false, type: 'text'},
                {name: 'nama_produk', allowBlank: false, type: 'text'},
                {name: 'nm_satuan', allowBlank: false, type: 'text'},
                {name: 'qty', allowBlank: false, type: 'int'},
                {name: 'tindak_lanjut', allowBlank: false, type: 'int'},
                {name: 'kd_lokasi_awal', allowBlank: false, type: 'text'},
                {name: 'kd_blok_awal', allowBlank: false, type: 'text'},
                {name: 'kd_sub_blok_awal', allowBlank: false, type: 'text'},
                {name: 'kd_lokasi_akhir', allowBlank: false, type: 'text'},
                {name: 'kd_blok_akhir', allowBlank: false, type: 'text'},
                {name: 'kd_sub_blok_akhir', allowBlank: false, type: 'text'},
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
    var editorInputBarangPecah_ibp = new Ext.ux.grid.RowEditor({saveText: 'Update'});

    var gridInputBarangPecah_ibp = new Ext.grid.GridPanel({
        store: storeInputBarangPecah_ibp,
        stripeRows: true,
        height: 250,
        frame: true,
        border: true,
        plugins: [editorInputBarangPecah_ibp],
        columns: [{
                header: 'Kode Produk',
                dataIndex: 'kd_produk',
                width: 150,
                editor: comboProduk_ibp
            }, {
                header: 'Nama Produk',
                dataIndex: 'nama_produk',
                width: 350,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'id_grid_nama_produk_ibp',
                    fieldClass: 'readonly-input'
                })
            }, {
                header: 'Satuan',
                dataIndex: 'nm_satuan',
                width: 110,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'id_grid_nm_satuan_ibp',
                    fieldClass: 'readonly-input'
                })
            }, {
                header: 'Qty',
                dataIndex: 'qty',
                width: 70,
                editor: new Ext.form.TextField({
                    readOnly: false,
                    id: 'id_grid_qty_ibp'
                })
            }, {
                header: 'Tindak Lanjut',
                dataIndex: 'tindak_lanjut',
                width: 200,
                editor: cboTindakLanjut_ibp
            }, {
                header: 'Lokasi Awal',
                dataIndex: 'kd_lokasi_awal',
                width: 200,
                editor: comboLokasiAsal_ibp
            }, {
                header: 'Blok Awal',
                dataIndex: 'kd_blok_awal',
                width: 200,
                editor: cboBlokAwal_ibp
            }, {
                header: 'Sub Blok Awal',
                dataIndex: 'kd_sub_blok_awal',
                width: 200,
                editor: cboSubBlokAwal_ibp
            }, {
                header: 'Lokasi Akhir',
                dataIndex: 'kd_lokasi_akhir',
                width: 200,
                editor: comboLokasiTujuan_ibp
            }, {
                header: 'Blok Akhir',
                dataIndex: 'kd_blok_akhir',
                width: 200,
                editor: cboBlokAkhir_ibp
            }, {
                header: 'Sub Blok Akhir',
                dataIndex: 'kd_sub_blok_akhir',
                width: 200,
                editor: cboSubBlokAkhir_ibp
            }], tbar: [
            {
                icon: BASE_ICONS + 'add.png',
                text: 'Add',
                handler: function() {
                    var rowmutasi = new gridInputBarangPecah_ibp.store.recordType({
                        kd_produk: '',
                        nama_produk: '',
                        nm_satuan: '',
                        qty: '',
                        tindak_lanjut: '',
                        kd_lokasi_awal: '',
                        kd_blok_awal: '',
                        kd_sub_blok_awal: '',
                        kd_lokasi_akhir: '',
                        kd_blok_akhir: '',
                        kd_sub_blok_akhir: ''
                    });
                    editorInputBarangPecah_ibp.stopEditing();
                    storeInputBarangPecah_ibp.insert(0, rowmutasi);
                    gridInputBarangPecah_ibp.getView().refresh();
                    gridInputBarangPecah_ibp.getSelectionModel().selectRow(0);
                    editorInputBarangPecah_ibp.startEditing(0);

                }
            }, {
                ref: '../removeBtn',
                icon: BASE_ICONS + 'delete.gif',
                text: 'Remove',
                disabled: true,
                handler: function() {
                    editorInputBarangPecah_ibp.stopEditing();
                    var s = gridInputBarangPecah_ibp.getSelectionModel().getSelections();
                    for (var i = 0, r; r = s[i]; i++) {
                        storeInputBarangPecah_ibp.remove(r);
                    }
                }
            }]
    });

    /**
     * main panel container
     */
    var inputBarangPecah_ibp = new Ext.FormPanel({
        id: 'input_barang_pecah',
        monitorValid: true,
        border: false,
        frame: true,
        bodyStyle: 'padding-right:20px;',
        labelWidth: 130,
        autoScroll: true,
        items: [headerInputBarangPecah_ibp, gridInputBarangPecah_ibp],
        buttons: [
            {
                text: 'save',
                handler: function() {
                    inputBarangPecahDataProcessing();
                }
            }
        ]
    });


    /**
     * processing form method declaration
     */
    function inputBarangPecahDataProcessing() {
        var box = Ext.MessageBox.wait('saving ' + 'data', 'Please Wait.....');
        var detailBarangPecah = new Array();
        storeInputBarangPecah_ibp.each(function(node) {
            detailBarangPecah.push(node.data)
        });
        Ext.Ajax.request({
            url: '<?= site_url("input_barang_pecah_controller/finalGetDatas") ?>',
            method: 'POST',
            waitMsg: 'Processing Data...',
            params: {
                cmd: 'save',
                no_bukti: Ext.getCmp('').getValue(),
                no_ref: Ext.getCmp('').getValue(),
                tanggal: Ext.getCmp('').getValue(),
                keterangan: Ext.getCmp('').getValue(),
                data: Ext.util.JSON.encode(detailBarangPecah),
            },
            callback: function(opt, success, responseObj) {
                var de = Ext.util.JSON.decode(responseObj.responseText);
                if (de.success == true) {
                    box.hide();
                    Ext.Msg.show({
                        title: 'Success',
                        msg: 'Submitted Successfully',
                        modal: true,
                        icon: Ext.Msg.INFO,
                        buttons: Ext.Msg.OK,
                        fn: function(btn) {
                            if (btn == 'ok') {
//                                winentrypenukaranpoint.show();
//                                Ext.getDom('entrypenukaranpointprint').src = de.printUrl;
                            }
                        }
                    });
                } else {
                    box.hide();
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
            }
        });
    }


</script>