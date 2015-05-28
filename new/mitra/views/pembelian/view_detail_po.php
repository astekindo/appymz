<?php if (!defined( 'BASEPATH')) exit( 'No direct script access allowed'); ?>
<script type="text/javascript">
    
	// start COMBOBOX SUPPLIER
	var str_cb_vdpo_supplier = new Ext.data.ArrayStore({
        fields: ['nama_supplier'],
        data: []
    });
    var str_grid_vdpo_supplier = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_supplier', 'nama_supplier'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("laporan_penerimaan_barang/search_supplier") ?>',
            method: 'POST'
        }),
        listeners: {

            loadexception: function (event, options, response, error) {
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });
    var search_grid_vdpo_supplier = new Ext.app.SearchField({
        store: str_grid_vdpo_supplier,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgrid_vdpo_supplier'
    });
    var grid_vdpo_supplier = new Ext.grid.GridPanel({
        store: str_grid_vdpo_supplier,
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
            items: [search_grid_vdpo_supplier]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: str_grid_vdpo_supplier,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function () {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('id_cb_vdpo_supplier').setValue(sel[0].get('kd_supplier'));
                    Ext.getCmp('vdpo_nama_supplier').setValue(sel[0].get('nama_supplier'));
                    // strlaporanpenerimaanbarang.removeAll();       
                    menu_vdpo_supplier.hide();
                }
            }
        }
    });
    var menu_vdpo_supplier = new Ext.menu.Menu();
    menu_vdpo_supplier.add(new Ext.Panel({
        title: 'Pilih Supplier',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [grid_vdpo_supplier],
        buttons: [{
            text: 'Close',
            handler: function () {
                menu_vdpo_supplier.hide();
            }
        }]
    }));
    Ext.ux.TwinComboSupplierDetailPO = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function () {
            //load store grid
            str_grid_vdpo_supplier.load();
            menu_vdpo_supplier.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
    menu_vdpo_supplier.on('hide', function () {
        var sf = Ext.getCmp('id_searchgrid_vdpo_supplier').getValue();
        if (sf != '') {
            Ext.getCmp('id_searchgrid_vdpo_supplier').setValue('');
            search_grid_vdpo_supplier.onTrigger2Click();
        }
    });
    var cb_vdpo_supplier = new Ext.ux.TwinComboSupplierDetailPO({
        fieldLabel: 'Kode Supplier',
        id: 'id_cb_vdpo_supplier',
        store: str_cb_vdpo_supplier,
        mode: 'local',
        valueField: 'nama_supplier',
        displayField: 'nama_supplier',
        typeAhead: true,
        triggerAction: 'all',
        // allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'nama_supplier',
        emptyText: 'Pilih Kode Supplier'
    });
	// end COMBOBOX SUPPLIER

	// HEADER MONITORING PO
    var header_vdpo = {
        layout: 'column',
        border: false,
        buttonAlign: 'left',
        items: [{
            columnWidth: .5,
            layout: 'form',
            border: false,
            labelWidth: 100,
            defaults: {labelSeparator: ''},
            items: [
                cb_vdpo_supplier,
                {
                    xtype: 'datefield',
                    fieldLabel: 'Tgl PO',
                    emptyText: 'Tanggal Awal',
                    name: 'tgl_awal',
                    id: 'vdpo_tgl_awal',
                    maxLength: 255,
                    anchor: '90%',
                    value: '',
                    format: 'd-M-Y'
                }
            ]
        }, {
            columnWidth: .5,
            layout: 'form',
            border: false,
            labelWidth: 100,
            defaults: {
                labelSeparator: ''
            },
            items: [{
                    xtype: 'textfield',
                    fieldLabel: 'Nama Supplier',
                    name: 'nama_supplier',
                    readOnly: true,
                    fieldClass: 'readonly-input',
                    id: 'vdpo_nama_supplier',
                    anchor: '90%',
                    value: '',
                    emptyText: 'Nama Supplier'
                }, {
                    xtype: 'datefield',
                    fieldLabel: 's/d',
                    emptyText: 'Tanggal Akhir',
                    name: 'tgl_akhir',
                    id: 'vdpo_tgl_akhir',
                    maxLength: 255,
                    anchor: '90%',
                    value: '',
                    format: 'd-M-Y'
                }
            ]
        }],
        buttons: [{
            text: 'Filter',
            formBind: true,
            handler: function () {

                grid_pembelian_vdpo.store.load({
                    params: {
                        kd_supplier: Ext.getCmp('id_cb_vdpo_supplier').getValue(),
                        tgl_awal: Ext.getCmp('vdpo_tgl_awal').getValue(),
                        tgl_akhir: Ext.getCmp('vdpo_tgl_akhir').getValue()
                    }
                });
            }
        }, {
            text: 'Reset',
            formBind: true,
            handler: function () {
                Ext.getCmp('id_cb_vdpo_supplier').setValue('');
                Ext.getCmp('vdpo_nama_supplier').setValue('');
                Ext.getCmp('vdpo_tgl_awal').setRawValue('');
                Ext.getCmp('vdpo_tgl_akhir').setRawValue('');
                grid_pembelian_vdpo.store.removeAll();
            }
        }]
    };

    // start GRID MONITORING PO
    var str_pembelian_vdpo = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'no_po',
                'no_ro',
                'tanggal_po',
                'tgl_berlaku_po',
                'kd_supplier',
                'nama_supplier',
                'status_po',
                'is_close_po',
                'type_purchase',
                'no_do',
                'tanggal_do'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("monitoring_purchase_order/get_rows") ?>',
            method: 'POST'
        }),
        listeners: {
            loadexception: function (event, options, response, error) {}
        }
    });
    var search_pembelian_vdpo = new Ext.app.SearchField({
        store: str_pembelian_vdpo,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        emptyText: 'No PO, No PR',
        id: 'id_search_vdpo'
    });

    var tb_pembelian_vdpo = new Ext.Toolbar({ items: [search_pembelian_vdpo] });

    var sm_pembelian_vdpo = new Ext.grid.CheckboxSelectionModel();

    var grid_pembelian_vdpo = new Ext.grid.EditorGridPanel({
        id: 'id_grid_pembelian_vdpo',
        frame: true,
        border: true,
        stripeRows: true,
        sm: sm_pembelian_vdpo,
        store: str_pembelian_vdpo,
        loadMask: false,
        style: 'margin:0 auto;',
        height: 400,
        columns: [{
            header: "No PO",
            dataIndex: 'no_po',
            sortable: true,
            width: 100
        }, {
            header: "No PR",
            dataIndex: 'no_ro',
            sortable: true,
            width: 100
        }, {
            header: "Tanggal PO",
            dataIndex: 'tanggal_po',
            sortable: true,
            width: 75
        }, {
            header: "Tgl Berlaku PO",
            dataIndex: 'tgl_berlaku_po',
            sortable: true,
            width: 80
        }, {
            header: "Kode Supplier",
            dataIndex: 'kd_supplier',
            sortable: true,
            width: 100
        }, {
            header: "Nama Supplier",
            dataIndex: 'nama_supplier',
            sortable: true,
            width: 150
        }, {
            header: "Status PO",
            dataIndex: 'status_po',
            sortable: true,
            width: 100
        }, {
            header: "Close PO",
            dataIndex: 'is_close_po',
            sortable: true,
            width: 60
        }, {
            header: "Type Purchase",
            dataIndex: 'type_purchase',
            sortable: true,
            width: 90
        }],
        listeners: {
            'rowdblclick': function () {
                var sm = grid_pembelian_vdpo.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {

                    Ext.Ajax.request({
                        url: '<?= site_url("monitoring_purchase_order/get_data_po") ?>/' + sel[0].get('no_po'),
                        method: 'POST',
                        params: {},
                        callback: function (opt, success, responseObj) {
                            var windowmonitoringpo = new Ext.Window({
                                title: 'Monitoring Purchase Order',
                                width: 1050,
                                height: 500,
                                autoScroll: true,
                                html: responseObj.responseText
                            });
                            windowmonitoringpo.show();
                        }
                    });
                }
            }
        },

        tbar: tb_pembelian_vdpo,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: str_pembelian_vdpo,
            displayInfo: true
        })
    });
	// end GRID MONITORING PO

	// PANEL MONITORING PO
    var pembelian_vdpo_panel = new Ext.FormPanel({
        id: 'pembelian_view_detail_po',
        border: false,
        frame: true,
        autoScroll:true,
        bodyStyle: 'padding-right:20px;',
        labelWidth: 130,
        items: [
            {
                bodyStyle: { margin: '10px 0px 15px 0px'},
                items: [header_vdpo]
            },grid_pembelian_vdpo
        ]
    });

</script>