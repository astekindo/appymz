<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">
    var strcbcspsuplier = new Ext.data.ArrayStore({
        fields: ['nama_supplier'],
        data: []
    });

    var strgridcspsuplier = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_supplier', 'nama_supplier'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("pembelian_create_request/search_supplier") ?>',
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

    strgridcspsuplier.on('load', function() {
        Ext.getCmp('id_searchgridcspsuplier').focus();
    });

    var searchgridcspsuplier = new Ext.app.SearchField({
        store: strgridcspsuplier,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgridcspsuplier'
    });

    var gridcspsuplier = new Ext.grid.GridPanel({
        store: strgridcspsuplier,
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
            items: [searchgridcspsuplier]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridcspsuplier,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('id_cbcspsuplier').setValue(sel[0].get('nama_supplier'));
                    strcetaksuratpesanan.load({
                        params: {
                            kd_supplier: sel[0].get('kd_supplier')
                        }
                    });
                    menucspsuplier.hide();
                }
            }
        }
    });

    var menucspsuplier = new Ext.menu.Menu();
    menucspsuplier.add(new Ext.Panel({
        title: 'Pilih Supplier',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridcspsuplier],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menucspsuplier.hide();
                }
            }]
    }));

    Ext.ux.TwinCombocspSuplier = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            //load store grid
            strgridcspsuplier.load();
            menucspsuplier.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
                trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    menucspsuplier.on('hide', function() {
        var sf = Ext.getCmp('id_searchgridcspsuplier').getValue();
        if (sf != '') {
            Ext.getCmp('id_searchgridcspsuplier').setValue('');
            searchgridcspsuplier.onTrigger2Click();
        }
    });

    var cbcspsuplier = new Ext.ux.TwinCombocspSuplier({
        fieldLabel: 'Supplier',
        id: 'id_cbcspsuplier',
        store: strcbcspsuplier,
        mode: 'local',
        valueField: 'nama_supplier',
        displayField: 'nama_supplier',
        typeAhead: true,
        triggerAction: 'all',
        // allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'nama_supplier',
        emptyText: 'Pilih Supplier'
    });

    var headercetaksuratpesanan = {
        layout: 'column',
        border: false,
        items: [{
                columnWidth: .5,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: {labelSeparator: ''},
                items: [cbcspsuplier]
            }]
    }

    /* START GRID */
    var strcetaksuratpesanan = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'kd_suplier',
                'nama_supplier',
                'no_sp',
                'tgl_sp',
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("cetak_surat_pesanan_controller/finalGetDataSuratPesanan") ?>',
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
    var search_konsinyasi_purchase_order_print = new Ext.app.SearchField({
        store: strcetaksuratpesanan,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        id: 'idsearch_konsinyasi_purchase_order_print'
    });

    // top toolbar
    var tb_konsinyasi_purchase_order_print = new Ext.Toolbar({
        items: [search_konsinyasi_purchase_order_print, '->', '<i>Klik row untuk melihat detail PO</i>']
    });

    // checkbox grid
    var smgridcspprint = new Ext.grid.CheckboxSelectionModel();
    var smgridDetkpoprint = new Ext.grid.CheckboxSelectionModel();

    // data store
    var strcetaksuratpesanandetail = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'kd_produk',
                'nama_produk',
                'qty_sp',
                'nm_satuan',
                'no_sp'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("cetak_surat_pesanan_controller/finalGetDataSuratPesananDetail") ?>',
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

    strcetaksuratpesanan.on('load', function() {
        strcetaksuratpesanandetail.removeAll();
    })



    var gridcspprint = new Ext.grid.EditorGridPanel({
        id: 'gridcspprint',
        frame: true,
        border: true,
        stripeRows: true,
        sm: smgridcspprint,
        store: strcetaksuratpesanan,
        loadMask: true,
        title: 'Surat Pesanan',
        style: 'margin:0 auto;',
        height: 225,
        // width: 550,
        columns: [{
                header: "Kode Supplier",
                dataIndex: 'kd_suplier',
                sortable: true,
                width: 150
            }, {
                header: "Nama Supplier",
                dataIndex: 'nama_supplier',
                sortable: true,
                width: 250
            }, {
                header: "No Surat Pesanan",
                dataIndex: 'no_sp',
                // hidden: true,
                sortable: true,
                width: 150
            }, {
                header: "Tanggal",
                dataIndex: 'tgl_sp',
                sortable: true,
                width: 80
            }],
        listeners: {
            'rowclick': function() {
                var sm = gridcspprint.getSelectionModel();
                var sel = sm.getSelections();
                //gridDetkpoprint.store.proxy.conn.url = '<?= site_url("konsinyasi_purchase_order_print/get_rows_detail") ?>/' + sel[0].get('no_po');
                //gridDetkpoprint.store.reload();
                strcetaksuratpesanandetail.reload({
                    params: {
                        no_surat: sel[0].get('no_sp')
                    }
                });
            }
        }
        // tbar: tb_konsinyasi_purchase_order_print,
        //bbar: new Ext.PagingToolbar({
        //    pageSize: ENDPAGE,
        //    store: strcetaksuratpesanan,
        //    displayInfo: true
        //})
    });

    // shorthand alias
    var fm = Ext.form;

    var cmm = new Ext.ux.grid.LockingColumnModel({
        // specify any defaults for each column
        defaults: {
            sortable: true // columns are not sortable by default           
        },
        columns: [{
                header: "No SP",
                dataIndex: 'no_sp',
                sortable: true,
                width: 250
            }, {
                header: "Kode Barang",
                dataIndex: 'kd_produk',
                sortable: true,
                width: 250
            }, {
                header: "Nama Barang",
                dataIndex: 'nama_produk',
                sortable: true,
                width: 250
            }, {
                header: "Qty",
                dataIndex: 'qty_sp',
                sortable: true,
                width: 50
            }, {
                header: "Satuan",
                dataIndex: 'nm_satuan',
                sortable: true,
                width: 50
            }],
    });

    var gridDetkpoprint = new Ext.grid.EditorGridPanel({
        id: 'gridDetkpoprint',
        store: strcetaksuratpesanandetail,
        stripeRows: true,
        style: 'margin-bottom:5px;',
        height: 225,
        frame: true,
        border: true,
        loadMask: true,
        sm: smgridDetkpoprint,
        plugins: [action_approval_detail_approve_manager, action_approval_detail_reject_manager],
        view: new Ext.ux.grid.LockingGridView(),
        cm: cmm,
        listeners: {
            'rowclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                winprintkonsinyasipurchaseordernonhargaprint.show();
                Ext.getDom('printkonsinyasipurchaseordernonhargaprint').src = '<?= site_url("cetak_surat_pesanan_controller/finalPrint") ?>' + '/' + sel[0].get('no_sp');

            }
        }
    });


    var winprintcetaksuratpesanan = new Ext.Window({
        id: 'id_winprintcetaksuratpesanan',
        title: 'Print Purchase order Form',
        closeAction: 'hide',
        width: 900,
        height: 450,
        layout: 'fit',
        border: false,
        html: '<iframe style="width:100%;height:100%;" id="printcetaksuratpesanan" src=""></iframe>'
    });
    var winprintkonsinyasisuratpesananprint = new Ext.Window({
        id: 'id_winprintkonsinyasisuratpesananprint',
        title: 'Print Surat Pesanan Form',
        closeAction: 'hide',
        width: 900,
        height: 450,
        layout: 'fit',
        border: false,
        html: '<iframe style="width:100%;height:100%;" id="printkonsinyasisuratpesananprint" src=""></iframe>'
    });

    var winprintkonsinyasipurchaseordernonhargaprint = new Ext.Window({
        id: 'id_winprintkonsinyasipurchaseordernonhargaprint',
        title: 'Print Purchase Order Non Harga Form',
        closeAction: 'hide',
        width: 900,
        height: 450,
        layout: 'fit',
        border: false,
        html: '<iframe style="width:100%;height:100%;" id="printkonsinyasipurchaseordernonhargaprint" src=""></iframe>'
    });

    var cetaksuratpesanan = new Ext.FormPanel({
        id: 'cetaksuratpesanan',
        border: false,
        frame: true,
        monitorValid: true,
        labelWidth: 130,
        items: [{
                bodyStyle: {
                    margin: '0px 0px 15px 0px'
                },
                items: [headercetaksuratpesanan]
            }, gridcspprint, gridDetkpoprint

        ],
        buttons: [{
                text: 'cetak',
                handler: function() {
                    var sm = gridcspprint.getSelectionModel();
                    var sel = sm.getSelections();
                    winprintkonsinyasipurchaseordernonhargaprint.show();
                    Ext.getDom('printkonsinyasipurchaseordernonhargaprint').src = '<?= site_url("cetak_surat_pesanan_controller/finalPrint") ?>' + '/' + sel[0].get('no_sp');
                }
            }, {
                text: 'Reset',
                handler: function() {
                    clearcetaksuratpesanan();
                }
            }]
    });

    function clearcetaksuratpesanan() {
        Ext.getCmp('cetaksuratpesanan').getForm().reset();
        strcetaksuratpesanan.removeAll();
        strcetaksuratpesanandetail.removeAll();
    }
</script>