<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">

    /* START HEADER*/
    // combobox lokasi
    var strcblokasilpb = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_lokasi', 'nama_lokasi'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("blok_lokasi/get_all") ?>',
            method: 'POST'
        }),
        listeners: {
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    var cblokasilpb = new Ext.form.ComboBox({
        fieldLabel: 'Nama Lokasi',
        id: 'id_lokasi_lpb',
        store: strcblokasilpb,
        valueField: 'kd_lokasi',
        displayField: 'nama_lokasi',
        typeAhead: true,
        triggerAction: 'all',
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_lokasi',
        emptyText: 'Pilih Lokasi',
        listeners: {

            select: function(combo, records) {
                var kd_cblokasiopname = this.getValue();
                cbbloklpb.setValue();
                cbbloklpb.store.proxy.conn.url = '<?= site_url("sub_blok_lokasi/get_blok") ?>/' + kd_cblokasiopname;
                cbbloklpb.store.reload();
            }
        }
    });

    // combobox blok
    var strcbbloklpb = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_blok', 'nama_blok'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("sub_blok_lokasi/get_blok") ?>',
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

    var cbbloklpb = new Ext.form.ComboBox({
        fieldLabel: 'Nama Blok',
        id: 'id_blok_lpb',
        mode: 'local',
        store: strcbbloklpb,
        valueField: 'kd_blok',
        displayField: 'nama_blok',
        typeAhead: true,
        triggerAction: 'all',
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_blok',
        emptyText: 'Pilih Blok',
        listeners: {
            select: function(combo, records) {
                var kd_cblokasiopname = Ext.getCmp('id_lokasi_lpb').getValue();
                var kd_cbblokopname = this.getValue();
                cbsubbloklpb.setValue('');
                cbsubbloklpb.store.proxy.conn.url = '<?= site_url("sub_blok_lokasi/get_sub_blok") ?>/' + kd_cblokasiopname + '/' + kd_cbblokopname;
                cbsubbloklpb.store.reload();
            }
        }
    });

    // combobox sub_blok
    var strcbsubbloklpb = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_sub_blok', 'nama_sub_blok'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("sub_blok_lokasi/get_sub_blok") ?>',
            method: 'POST'
        })
    });

    var cbsubbloklpb = new Ext.form.ComboBox({
        fieldLabel: 'Nama Sub Blok',
        id: 'id_cbsubblok_lpb',
        mode: 'local',
        store: strcbsubbloklpb,
        valueField: 'kd_sub_blok',
        displayField: 'nama_sub_blok',
        typeAhead: true,
        triggerAction: 'all',
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_sub_blok',
        emptyText: 'Pilih Sub Blok'
    });

 // combobox Ukuran
    var str_lpb_cbukuran = new Ext.data.Store({
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
                var r = new (str_lpb_cbukuran.recordType)({
                    'kd_ukuran': '',
                    'nama_ukuran': '-----'
                });
                str_lpb_cbukuran.insert(0, r);
            },
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    var lpb_cbukuran = new Ext.form.ComboBox({
        fieldLabel: 'Ukuran ',
        id: 'id_lpb_cbukuran',
        store: str_lpb_cbukuran,
        valueField: 'kd_ukuran',
        displayField: 'nama_ukuran',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: true,
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_ukuran',
        emptyText: 'Pilih Ukuran'

    });

    // combobox Satuan
    var str_lpb_cbsatuan = new Ext.data.Store({
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
                var r = new (str_lpb_cbsatuan.recordType)({
                    'kd_satuan': '',
                    'nm_satuan': '-----'
                });
                str_lpb_cbsatuan.insert(0, r);
            },
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });
    var lpb_cbsatuan = new Ext.form.ComboBox({
        fieldLabel: 'Satuan',
        id: 'id_lpb_cbsatuan',
        store: str_lpb_cbsatuan,
        valueField: 'kd_satuan',
        displayField: 'nm_satuan',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: true,
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_satuan',
        emptyText: 'Pilih Satuan'

    });

     // combobox kategori1
    var str_lbp_cbkategori1 = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_kategori1', 'nama_kategori1'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("lokasi_per_barang/get_kategori1") ?>',
            method: 'POST'
        }),
        listeners: {
            load: function() {
                var r = new (str_lbp_cbkategori1.recordType)({
                    'kd_kategori1': '',
                    'nama_kategori1': '-----'
                });
                str_lbp_cbkategori1.insert(0, r);
            },
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    var lbp_cbkategori1 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 1 ',
        id: 'lbp_cbkategori1',
        store: str_lbp_cbkategori1,
        valueField: 'kd_kategori1',
        displayField: 'nama_kategori1',
        typeAhead: true,
        triggerAction: 'all',
        // allowBlank: false,
        editable: false,
        width: 170,
        anchor: '90%',
        hiddenName: 'nama_kategori1',
        emptyText: 'Pilih kategori 1'
    });
    // combobox kategori2
    var str_lbp_cbkategori2 = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_kategori2', 'nama_kategori2'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("lokasi_per_barang/get_kategori2") ?>',
            method: 'POST'
        }),
        listeners: {
            load: function() {
                var r = new (str_lbp_cbkategori2.recordType)({
                    'kd_kategori2': '',
                    'nama_kategori2': '-----'
                });
                str_lbp_cbkategori2.insert(0, r);
            },
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    var lbp_cbkategori2 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 2 ',
        id: 'lbp_cbkategori2',
        store: str_lbp_cbkategori2,
        valueField: 'kd_kategori2',
        displayField: 'nama_kategori2',
        typeAhead: true,
        triggerAction: 'all',
        editable: false,
        width: 170,
        anchor: '90%',
        hiddenName: 'nama_kategori2',
        emptyText: 'Pilih kategori 2',
    });

    // combobox kategori3
    var str_lbp_cbkategori3 = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_kategori3', 'nama_kategori3'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("lokasi_per_barang/get_kategori3") ?>',
            method: 'POST'
        }),
        listeners: {
            load: function() {
                var r = new (str_lbp_cbkategori3.recordType)({
                    'kd_kategori3': '',
                    'nama_kategori3': '-----'
                });
                str_lbp_cbkategori3.insert(0, r);
            },
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    var lbp_cbkategori3 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 3 ',
        id: 'lbp_cbkategori3',
        store: str_lbp_cbkategori3,
        valueField: 'kd_kategori3',
        displayField: 'nama_kategori3',
        typeAhead: true,
        triggerAction: 'all',
        editable: false,
        width: 170,
        anchor: '90%',
        hiddenName: 'nama_kategori3',
        emptyText: 'Pilih kategori 3'
    });

    // combobox kategori4
    var str_lbp_cbkategori4 = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_kategori4', 'nama_kategori4'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("lokasi_per_barang/get_kategori4") ?>',
            method: 'POST'
        }),
        listeners: {
            load: function() {
                var r = new (str_lbp_cbkategori4.recordType)({
                    'kd_kategori4': '',
                    'nama_kategori4': '-----'
                });
                str_lbp_cbkategori4.insert(0, r);
            },
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    var lbp_cbkategori4 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 4',
        id: 'lbp_cbkategori4',
        store: str_lbp_cbkategori4,
        valueField: 'kd_kategori4',
        displayField: 'nama_kategori4',
        typeAhead: true,
        triggerAction: 'all',
        editable: false,
        width: 170,
        anchor: '90%',
        hiddenName: 'nama_kategori4',
        emptyText: 'Pilih kategori 4'
    });
    // End Kategori4

    var lpb_cbperuntukan = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_peruntukan', 'nama_peruntukan'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("lokasi_per_barang/get_peruntukan") ?>',
            method: 'POST'
        }),
        listeners: {
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });


    // COMBOBOX status
    var lpb_cbperuntukan = new Ext.form.ComboBox({
        fieldLabel: 'Peruntukkan',
        id: 'id_lpb_cbperuntukan',
        name:'status',
        store: lpb_cbperuntukan,
        valueField: 'kd_peruntukan',
        displayField: 'nama_peruntukan',
        typeAhead: true,
        triggerAction: 'all',
        editable: false,
        width: 170,
        anchor: '90%',
        hiddenName: 'nama_peruntukan',
        emptyText: 'Pilih peruntukan'
    });

    lbp_cbkategori1.addListener('select', function() {
        reloadStore('lbp_cbkategori2','kategori1',this.value);
        reloadStore('lbp_cbkategori3','kategori1',this.value);
        reloadStore('lbp_cbkategori4','kategori1',this.value);
    });

    lbp_cbkategori2.addListener('select', function() {
        reloadStore('lbp_cbkategori3','kategori2',this.value);
        reloadStore('lbp_cbkategori4','kategori2',this.value);
    });

    lbp_cbkategori3.addListener('select', function() {
        reloadStore('lbp_cbkategori4','kategori3',this.value);
    });

    function reloadStore(componentId, param, newValue) {
        var componentObj = Ext.getCmp(componentId);
        if (componentObj.store.lastOptions != undefined)
            componentObj.store.lastOptions.params.kategori3 = newValue;
        componentObj.store.setBaseParam(param,newValue);
        if(componentObj.getValue() != '') {
            componentObj.setValue('');
        }
        componentObj.store.reload();
    }

     // twin combo supplier
    var str_lpb_supplier = new Ext.data.ArrayStore({
        fields: ['nama_supplier'],
        data: []
    });

    var strgrid_lpb_supplier = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_supplier', 'nama_supplier', 'pkp'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("pembelian_retur/search_supplier") ?>',
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

    var searchgrid_lpb_supplier = new Ext.app.SearchField({
        store: strgrid_lpb_supplier,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgrid_lpb_supplier'
    });


    var grid_lpb_supplier = new Ext.grid.GridPanel({
        store: strgrid_lpb_supplier,
        stripeRows: true,
        frame: true,
        border: true,
        columns: [{
                header: 'Kode Supplier',
                dataIndex: 'kd_supplier',
                width: 100,
                sortable: true
            }, {
                header: 'Nama Supplier',
                dataIndex: 'nama_supplier',
                width: 170,
                sortable: true
            }, {
                header: 'Status PKP',
                dataIndex: 'pkp',
                width: 100,
                sortable: true
            }],
        tbar: new Ext.Toolbar({
            items: [searchgrid_lpb_supplier]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgrid_lpb_supplier,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('id_lpb_cbsuplier').setValue(sel[0].get('nama_supplier'));
                    Ext.getCmp('id_kode_supplier').setValue(sel[0].get('kd_supplier'));
                    //strpembelianretur.removeAll();
                    menu_lpb_supplier.hide();
                }
            }
        }
    });

    var menu_lpb_supplier = new Ext.menu.Menu();
    menu_lpb_supplier.add(new Ext.Panel({
        title: 'Pilih Supplier',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [grid_lpb_supplier],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menu_lpb_supplier.hide();
                }
            }]
    }));

    Ext.ux.TwinComboLPBSupplier = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            //load store grid
            strgrid_lpb_supplier.load();
            menu_lpb_supplier.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    menu_lpb_supplier.on('hide', function() {
        var sf = Ext.getCmp('id_searchgrid_lpb_supplier').getValue();
        if (sf != '') {
            Ext.getCmp('id_searchgrid_lpb_supplier').setValue('');
            searchgrid_lpb_supplier.onTrigger2Click();
        }
    });

    var lpb_cbsuplier = new Ext.ux.TwinComboLPBSupplier({
        fieldLabel: 'Nama Supplier',
        id: 'id_lpb_cbsuplier',
        store: str_lpb_supplier,
        mode: 'local',
        valueField: 'nama_supplier',
        displayField: 'nama_supplier',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: true,
        editable: false,
        anchor: '90%',
        hiddenName: 'nama_supplier',
        emptyText: 'Pilih Supplier'
    });
    //end twincombosupplier

    var headerlokasiperbarang = {
        layout: 'column',
        border: false,
        buttonAlign:'left',
        items: [{
            columnWidth: .5,
            layout: 'form',
            border: false,
            labelWidth: 100,
            defaults: {labelSeparator: ''},
            items: [cblokasilpb, cbbloklpb, cbsubbloklpb,lpb_cbukuran,lpb_cbsatuan,lpb_cbperuntukan] //tambah combo peruntukan
        }, {
            columnWidth: .5,
            layout: 'form',
            border: false,
            labelWidth: 100,
            defaults: { labelSeparator: ''},
            items: [lbp_cbkategori1,lbp_cbkategori2,lbp_cbkategori3,lbp_cbkategori4,
            {
                xtype: 'hidden',
                name: 'kd_supplier',
                id: 'id_kode_supplier',
                value: ''
            },lpb_cbsuplier]
        }],
        buttons: [{
                text: 'Filter',
                formBind: true,
                handler: function() {
                    var kd_ccblokasiopname = Ext.getCmp('id_lokasi_lpb').getValue();
                    var kd_cbblokopname = Ext.getCmp('id_blok_lpb').getValue();
                    var kd_cbsubblokopname = Ext.getCmp('id_cbsubblok_lpb').getValue();
                    var kd_ukuran = Ext.getCmp('id_lpb_cbukuran').getValue();
                    var kd_satuan = Ext.getCmp('id_lpb_cbsatuan').getValue();
                    var peruntukan = Ext.getCmp('id_lpb_cbperuntukan').getValue();
                    var kd_kategori1 = Ext.getCmp('lbp_cbkategori1').getValue();
                    var kd_kategori2 = Ext.getCmp('lbp_cbkategori2').getValue();
                    var kd_kategori3 = Ext.getCmp('lbp_cbkategori3').getValue();
                    var kd_kategori4 = Ext.getCmp('lbp_cbkategori4').getValue();
                    var kd_suplier = Ext.getCmp('id_kode_supplier').getValue();
                    gridlokasiperbarang.store.reload({
                        params: {
                            start: STARTPAGE,
                            limit: ENDPAGE,
                            kdLokasi: kd_ccblokasiopname,
                            kdBlok: kd_cbblokopname,
                            kdSubBlok: kd_cbsubblokopname,
                            kdUkuran: kd_ukuran,
                            kdSatuan: kd_satuan,
                            peruntukan: peruntukan,
                            kdKategori1: kd_kategori1,
                            kdKategori2: kd_kategori2,
                            kdKategori3: kd_kategori3,
                            kdKategori4: kd_kategori4,
                            kdSuplier : kd_suplier
                        }
                    });
                }
            }, {
                text: 'Reset',
                formBind: true,
                handler: function() {
                    Ext.getCmp('id_lokasi_lpb').setValue('');
                    Ext.getCmp('id_blok_lpb').setValue('');
                    Ext.getCmp('id_cbsubblok_lpb').setValue('');
                    Ext.getCmp('id_lpb_cbukuran').setValue('');
                    Ext.getCmp('id_lpb_cbsatuan').setValue('');
                    Ext.getCmp('lbp_cbkategori1').setValue('');
                    Ext.getCmp('lbp_cbkategori2').setValue('');
                    Ext.getCmp('lbp_cbkategori3').setValue('');
                    Ext.getCmp('lbp_cbkategori4').setValue('');
                    str_lbp_cbkategori2.reload();
                    str_lbp_cbkategori3.reload();
                    str_lbp_cbkategori4.reload();
                    Ext.getCmp('id_lpb_cbsuplier').setValue('');
                    Ext.getCmp('id_kode_supplier').setValue('');
                    gridlokasiperbarang.store.removeAll();
                }
            }, {
                text: 'View as Report',
                formBind: true,
                handler: function() {
                    Ext.getCmp('lokasiperbarang').getForm().submit({
                        url: '<?= site_url("lokasi_per_barang/get_report") ?>',
                        scope: this,
                        waitMsg: 'Saving Data...',
                        success: function(form, action){
                            var r = Ext.util.JSON.decode(action.response.responseText);
                            Ext.Msg.show({
                                title: 'Success',
                                msg: r.successMsg,
                                modal: true,
                                icon: Ext.Msg.INFO,
                                buttons: Ext.Msg.OK,
                                fn: function(btn){
                                    window.open(r.printUrl, '_blank');
                                }
                            });
                        },
                        failure: function(form, action){
                            var fe = Ext.util.JSON.decode(action.response.responseText);
                            Ext.Msg.show({
                                title: 'Error',
                                msg: fe.errMsg,
                                modal: true,
                                icon: Ext.Msg.ERROR,
                                buttons: Ext.Msg.OK,
                                fn: function(btn){
                                    if (btn == 'ok' && fe.errMsg == 'Session Expired') {
                                        window.location = '<?= site_url("auth/login") ?>';
                                    }
                                }
                            });
                        }
                    })
                }
            }]
    };

    /* START GRID barang*/

    var strlokasiperbarang = new Ext.data.GroupingStore({


        reader: new Ext.data.JsonReader({
            fields: [
                'kd_produk',
                'nama_produk',
                'nama_lokasi',
                'nama_blok',
                'nama_sub_blok',
                'qty_oh',
                'nm_satuan',
                'lokasi',
                'peruntukan'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("lokasi_per_barang/get_lokasi_barang") ?>',
            method: 'POST'
        }),
        listeners: {
            loadexception: function(event, options, response, error) {
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        },
        groupField: 'lokasi'
    });

    var searchlokasiperbarang = new Ext.app.SearchField({
        store: strlokasiperbarang,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        id: 'idsearchlokasiperbarang'
    });

    strlokasiperbarang.on('load',function(){
        strlokasiperbarang.setBaseParam('kdLokasi',Ext.getCmp('id_lokasi_lpb').getValue());
        strlokasiperbarang.setBaseParam('kdBlok',Ext.getCmp('id_blok_lpb').getValue());
        strlokasiperbarang.setBaseParam('kdSubBlok',Ext.getCmp('id_cbsubblok_lpb').getValue());
        strlokasiperbarang.setBaseParam('kdUkuran',Ext.getCmp('id_lpb_cbukuran').getValue());
        strlokasiperbarang.setBaseParam('kdSuplier',Ext.getCmp('id_kode_supplier').getValue());
        strlokasiperbarang.setBaseParam('kdSatuan',Ext.getCmp('id_lpb_cbsatuan').getValue());
        strlokasiperbarang.setBaseParam('kdKategori1',Ext.getCmp('lbp_cbkategori1').getValue());
        strlokasiperbarang.setBaseParam('kdKategori2',Ext.getCmp('lbp_cbkategori2').getValue());
        strlokasiperbarang.setBaseParam('kdKategori3',Ext.getCmp('lbp_cbkategori3').getValue());
        strlokasiperbarang.setBaseParam('kdKategori4',Ext.getCmp('lbp_cbkategori4').getValue());
    });

    var tblokasiperbarang = new Ext.Toolbar({
        items: [searchlokasiperbarang]
    });

    var cbGridbaranglokasi = new Ext.grid.CheckboxSelectionModel();


    var gridlokasiperbarang = new Ext.grid.GridPanel({
        id: 'gridlokasiperbarang',
        frame: true,
        border: true,
        stripeRows: true,
        sm: cbGridbaranglokasi,
        store: strlokasiperbarang,
        loadMask: true,
        title: 'Barang',
        style: 'margin:0 auto;',
        height: 500,
        view: new Ext.grid.GroupingView({
            forceFit: true,
            groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "Items" : "Item"]})'
        }),
        columns: [
             {
                header: "Lokasi",
                dataIndex: 'lokasi',
                sortable: true,
                hidden: true,
                width: 450
             }, {
                header: "Peruntukan",
                dataIndex: 'peruntukan',
                width: 50
             }, {
                header: "Kode Produk",
                dataIndex: 'kd_produk',
                sortable: true,
                width: 100
            }, {
                header: "Nama Produk",
                dataIndex: 'nama_produk',
                sortable: true,
                width: 350
            }, {
                header: "Qty Oh",
                dataIndex: 'qty_oh',
                sortable: true,
                width: 50
            },{
                header: "Satuan",
                dataIndex: 'nm_satuan',
                sortable: true,
                width: 50
            }],
        tbar: tblokasiperbarang,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strlokasiperbarang,
            displayInfo: true
        })
    });


    var lokasiperbarang = new Ext.FormPanel({
        id: 'lokasiperbarang',
        border: false,
        frame: true,
        autoScroll: true,
        bodyStyle: 'padding:2px;',
        items: [headerlokasiperbarang, gridlokasiperbarang]

    });

</script>
