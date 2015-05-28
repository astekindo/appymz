<?php if (!defined( 'BASEPATH')) exit( 'No direct script access allowed'); ?>
<script type="text/javascript">
    
	// start COMBOBOX SUPPLIER
	var str_cb_vdro_supplier = new Ext.data.ArrayStore({
        fields: ['nama_supplier'],
        data: []
    });
    var str_grid_vdro_supplier = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_supplier', 'nama_supplier'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("pembelian_receive_order/search_supplier") ?>',
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
    var search_grid_vdro_supplier = new Ext.app.SearchField({
        store: str_grid_vdro_supplier,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgrid_vdro_supplier'
    });
    var grid_vdro_supplier = new Ext.grid.GridPanel({
        store: str_grid_vdro_supplier,
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
            items: [search_grid_vdro_supplier]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: str_grid_vdro_supplier,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function () {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('id_cb_vdro_supplier').setValue(sel[0].get('kd_supplier'));
                    Ext.getCmp('vdro_nama_supplier').setValue(sel[0].get('nama_supplier'));
                    // strlaporanpenerimaanbarang.removeAll();       
                    menu_vdro_supplier.hide();
                }
            }
        }
    });
    var menu_vdro_supplier = new Ext.menu.Menu();
    menu_vdro_supplier.add(new Ext.Panel({
        title: 'Pilih Supplier',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [grid_vdro_supplier],
        buttons: [{
            text: 'Close',
            handler: function () {
                menu_vdro_supplier.hide();
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
            str_grid_vdro_supplier.load();
            menu_vdro_supplier.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
    menu_vdro_supplier.on('hide', function () {
        var sf = Ext.getCmp('id_searchgrid_vdro_supplier').getValue();
        if (sf != '') {
            Ext.getCmp('id_searchgrid_vdro_supplier').setValue('');
            search_grid_vdro_supplier.onTrigger2Click();
        }
    });
    var cb_vdro_supplier = new Ext.ux.TwinComboSupplierDetailPO({
        fieldLabel: 'Kode Supplier',
        id: 'id_cb_vdro_supplier',
        store: str_cb_vdro_supplier,
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
                cb_vdro_supplier,
                {
                    xtype: 'datefield',
                    fieldLabel: 'Tgl RO',
                    emptyText: 'Tanggal Awal',
                    name: 'tgl_awal',
                    id: 'vdro_tgl_awal',
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
                    id: 'vdro_nama_supplier',
                    anchor: '90%',
                    value: '',
                    emptyText: 'Nama Supplier'
                }, {
                    xtype: 'datefield',
                    fieldLabel: 's/d',
                    emptyText: 'Tanggal Akhir',
                    name: 'tgl_akhir',
                    id: 'vdro_tgl_akhir',
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

                grid_pembelian_vdro.store.load({
                    params: {
                        kd_supplier: Ext.getCmp('id_cb_vdro_supplier').getValue(),
                        tgl_awal: Ext.getCmp('vdro_tgl_awal').getValue(),
                        tgl_akhir: Ext.getCmp('vdro_tgl_akhir').getValue()
                    }
                });
            }
        }, {
            text: 'Reset',
            formBind: true,
            handler: function () {
                Ext.getCmp('id_cb_vdro_supplier').setValue('');
                Ext.getCmp('vdro_nama_supplier').setValue('');
                Ext.getCmp('vdro_tgl_awal').setRawValue('');
                Ext.getCmp('vdro_tgl_akhir').setRawValue('');
                grid_pembelian_vdro.store.removeAll();
            }
        }]
    };

    // start GRID MONITORING PO
    var str_pembelian_vdro = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'no_ro',
                'kd_supplier',
                'nama_supplier',
                'tanggal',
                'tanggal_terima',
                'no_bukti_supplier'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("pembelian_receive_order_detail/get_rows") ?>',
            method: 'POST'
        }),
        listeners: {
            loadexception: function (event, options, response, error) {}
        }
    });
    var search_pembelian_vdro = new Ext.app.SearchField({
        store: str_pembelian_vdro,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        emptyText: 'No RO',
        id: 'id_search_vdro'
    });

    var tb_pembelian_vdro = new Ext.Toolbar({ items: [search_pembelian_vdro] });

    var sm_pembelian_vdro = new Ext.grid.CheckboxSelectionModel();

    var grid_pembelian_vdro = new Ext.grid.EditorGridPanel({
        id: 'id_grid_pembelian_vdro',
        frame: true,
        border: true,
        stripeRows: true,
        sm: sm_pembelian_vdro,
        store: str_pembelian_vdro,
        loadMask: false,
        style: 'margin:0 auto;',
        height: 400,
        columns: [{
            header: "No RO",
            dataIndex: 'no_ro',
            sortable: true,
            width: 100
        }, {
            header: "Kode Supplier",
            dataIndex: 'kd_supplier',
            sortable: true,
            width: 100
        }, {
            header: "Nama Supplier",
            dataIndex: 'nama_supplier',
            sortable: true,
            width: 200
        }, {
            header: "Tanggal Input",
            dataIndex: 'tanggal',
            sortable: true,
            width: 75
        }, {
            header: "Tgl Terima",
            dataIndex: 'tanggal_terima',
            sortable: true,
            width: 80
        }, {
            header: "No Bukti Supplier",
            dataIndex: 'no_bukti_supplier',
            sortable: true,
            width: 150
        }],
        listeners: {
            'rowdblclick': function () {
                var sm = grid_pembelian_vdro.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {

                    Ext.Ajax.request({
                        url: '<?= site_url("pembelian_receive_order_detail/get_data_ro") ?>/' + sel[0].get('no_ro'),
                        method: 'POST',
                        params: {},
                        callback: function (opt, success, responseObj) {
                            var windowmonitoringpo = new Ext.Window({
                                title: 'View Receive Order Detail',
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

        tbar: tb_pembelian_vdro,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: str_pembelian_vdro,
            displayInfo: true
        })
    });
	// end GRID MONITORING PO

	// PANEL MONITORING PO
    var pembelian_vdro_panel = new Ext.FormPanel({
        id: 'pembelian_view_detail_ro',
        border: false,
        frame: true,
        autoScroll:true,
        bodyStyle: 'padding-right:20px;',
        labelWidth: 130,
        items: [
            {
                bodyStyle: { margin: '10px 0px 15px 0px'},
                items: [header_vdpo]
            },grid_pembelian_vdro
        ]
    });

</script>