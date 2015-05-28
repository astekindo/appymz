<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script type="text/javascript">

    // start COMBOBOX SUPPLIER
    var storeCboSupplier_mpob = new Ext.data.ArrayStore({
        fields: ['kd_suplier_po'],
        data: []
    });
    var storeGridCboSupplier_mpob = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_suplier_po', 'no_po_induk', 'nama_supplier'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("monitoring_purchase_order_bonus_controller/finalGetDataSupplierPO") ?>',
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
    var searchGridSupplier_mpob = new Ext.app.SearchField({
        store: storeGridCboSupplier_mpob,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_search_grid_supplier_mpob'
    });
    var gridSupplier_mpob = new Ext.grid.GridPanel({
        store: storeGridCboSupplier_mpob,
        stripeRows: true,
        frame: true,
        border: true,
        columns: [
            {
                header: 'Kode Supplier',
                dataIndex: 'kd_suplier_po',
                width: 80,
                sortable: true
            }, {
                header: 'No Po Induk',
                dataIndex: 'no_po_induk',
                width: 100,
                sortable: true
            }, {
                header: 'Nama Supplier',
                dataIndex: 'nama_supplier',
                width: 300,
                sortable: true
            }
        ],
        tbar: new Ext.Toolbar({
            items: [searchGridSupplier_mpob]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: storeGridCboSupplier_mpob,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('id_cbo_supplier_mpob').setValue(sel[0].get('kd_suplier_po'));
                    if (Ext.getCmp('id_cbo_supplier_mpob').getValue() == '') {
                        Ext.getCmp('id_cbo_no_po_induk_mpob').setReadOnly(true);
                        Ext.getCmp('id_cbo_no_po_induk_mpob').addClass('readonly-input');
                    } else {
                        Ext.getCmp('id_cbo_no_po_induk_mpob').setReadOnly(false);
                        Ext.getCmp('id_cbo_no_po_induk_mpob').removeClass('readonly-input');
                    }
                    storeGridCboNoIndukPO_mpob.reload({
                        params: {
                            kd_supplier: sel[0].get('kd_suplier_po')
                        }
                    });

                    Ext.getCmp('id_nama_supplier_mpob').setValue(sel[0].get('nama_supplier'));
                    menuSupplier_mpob.hide();
                }
            }
        }
    });
    var menuSupplier_mpob = new Ext.menu.Menu();
    menuSupplier_mpob.add(new Ext.Panel({
        title: 'Pilih Supplier',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridSupplier_mpob],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menuSupplier_mpob.hide();
                }
            }]
    }));

    Ext.ux.TwinComboSupplier_mpob = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            //load store grid
            storeGridCboSupplier_mpob.load();
            menuSupplier_mpob.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
                trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    menuSupplier_mpob.on('hide', function() {
        var sf = Ext.getCmp('id_search_grid_supplier_mpob').getValue();
        if (sf != '') {
            Ext.getCmp('id_search_grid_supplier_mpob').setValue('');
            searchGridSupplier_mpob.onTrigger2Click();
        }
    });

    var cboSupplier_mpob = new Ext.ux.TwinComboSupplier_mpob({
        fieldLabel: 'Kode Supplier',
        id: 'id_cbo_supplier_mpob',
        store: storeCboSupplier_mpob,
        mode: 'local',
        valueField: 'kd_supplier_po',
        displayField: 'kd_supplier_po',
        typeAhead: true,
        triggerAction: 'all',
        // allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_supplier_po',
        emptyText: 'Pilih Kode Supplier'
    });
    // end COMBOBOX SUPPLIER


    /**
     * start cbo no induk
     * */
    // start COMBOBOX SUPPLIER
    var storeCboNoIndukPo_mpob = new Ext.data.ArrayStore({
        fields: ['no_po_induk'],
        data: []
    });
    var storeGridCboNoIndukPO_mpob = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_suplier_po', 'no_po_induk'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("monitoring_purchase_order_bonus_controller/finalGetDataNoPoInduk") ?>',
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

    storeGridCboNoIndukPO_mpob.on('load', function() {
        var kdSupplier = Ext.getCmp('id_cbo_supplier_mpob').getValue();
        storeGridCboNoIndukPO_mpob.setBaseParam('kd_supplier', kdSupplier);
    });

    var searchGridNoPoInduk_mpob = new Ext.app.SearchField({
        store: storeGridCboNoIndukPO_mpob,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_search_grid_no_po_induk_mpob'
    });
    var gridNoPoInduk_mpob = new Ext.grid.GridPanel({
        store: storeGridCboNoIndukPO_mpob,
        stripeRows: true,
        frame: true,
        border: true,
        columns: [
            {
                header: 'No PO Induk',
                dataIndex: 'no_po_induk',
                width: 300,
                sortable: true
            }],
        tbar: new Ext.Toolbar({
            items: [searchGridNoPoInduk_mpob]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: storeGridCboNoIndukPO_mpob,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('id_cbo_no_po_induk_mpob').setValue(sel[0].get('no_po_induk'));
                    menuNoPOInduk_mpob.hide();
                }
            }
        }
    });
    var menuNoPOInduk_mpob = new Ext.menu.Menu();
    menuNoPOInduk_mpob.add(new Ext.Panel({
        title: 'Pilih No Induk',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridNoPoInduk_mpob],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menuNoPOInduk_mpob.hide();
                }
            }]
    }));

    Ext.ux.TwinComboNoInduk_mpob = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            //load store grid
            storeGridCboNoIndukPO_mpob.load();
            menuNoPOInduk_mpob.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
                trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    menuNoPOInduk_mpob.on('hide', function() {
        var sf = Ext.getCmp('id_search_grid_no_po_induk_mpob').getValue();
        if (sf != '') {
            Ext.getCmp('id_search_grid_no_po_induk_mpob').setValue('');
            searchGridNoPoInduk_mpob.onTrigger2Click();
        }
    });

    var cboNoPOInduk_mpob = new Ext.ux.TwinComboNoInduk_mpob({
        fieldLabel: 'No Po Induk',
        id: 'id_cbo_no_po_induk_mpob',
        store: storeCboNoIndukPo_mpob,
        mode: 'local',
        valueField: 'no_po_induk',
        displayField: 'no_po_induk',
        typeAhead: true,
        readOnly: true,
        fieldClass: 'readonly-input',
        triggerAction: 'all',
        // allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'cbo_no_po_induk',
        emptyText: 'Pilih No Po Induk'
    });
    /**
     * end of cbo no induk po
     */

    /**
     * start combo status
     */
    var valStoreCboStatus_mpob = [
        ['A', "All"],
        ['0', "Belum Approve"],
        ['1', "Approve"],
        ['9', "Rejected"]
    ];
    var storeCboStatus_mpob = new Ext.data.ArrayStore({
        fields: [{
                name: 'key'
            }, {
                name: 'value'
            }],
        data: valStoreCboStatus_mpob
    });

    var cboStatusPO_mpob = new Ext.form.ComboBox({
        fieldLabel: 'Status PO',
        id: 'id_cbo_status_cpob',
        name: 'status',
        // allowBlank:false,
        store: storeCboStatus_mpob,
        valueField: 'key',
        displayField: 'value',
        mode: 'local',
        forceSelection: true,
        triggerAction: 'all',
        anchor: '90%'
    });
    /**
     * end of cbo status
     */

    /**
     * start cbo close po
     */
    var valcboClosePO_mpob = [
        ['A', "All"],
        ['0', "Open"],
        ['1', "Closed"]
    ];
    var strcboClosePO_mpob = new Ext.data.ArrayStore({
        fields: [{
                name: 'key'
            }, {
                name: 'value'
            }],
        data: valcboClosePO_mpob
    });
    var cboClosePO_mpob = new Ext.form.ComboBox({
        fieldLabel: 'Close PO',
        id: 'id_cbo_close_po_cpob',
        name: 'close_po',
        // allowBlank:false,
        store: strcboClosePO_mpob,
        valueField: 'key',
        displayField: 'value',
        mode: 'local',
        forceSelection: true,
        triggerAction: 'all',
        anchor: '90%'
    });
    /**
     * end of cbo close po
     */

    /**
     * start cbo type purchase
     */
    var valCboTypePurchase_mpob = [
        ['A', "All"],
        ['0', "Normal"],
        ['1', "Konsinyasi"],
        ['2', "Bonus"],
        ['3', "Asset"]
    ];
    var strCboTypePurchase_mpob = new Ext.data.ArrayStore({
        fields: [{
                name: 'key'
            }, {
                name: 'value'
            }],
        data: valCboTypePurchase_mpob
    });
    var cboTypePurchase_mpob = new Ext.form.ComboBox({
        fieldLabel: 'Type Purchase',
        id: 'id_cbo_type_purchase_mpob',
        name: 'konsinyasi',
        // allowBlank:false,
        store: strCboTypePurchase_mpob,
        valueField: 'key',
        displayField: 'value',
        mode: 'local',
        forceSelection: true,
        triggerAction: 'all',
        anchor: '90%'
    });


    // HEADER MONITORING PO
    var headerMonitoringPOBonus_mpob = {
        layout: 'column',
        border: false,
        buttonAlign: 'left',
        items: [{
                columnWidth: .5,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: {
                    labelSeparator: ''
                },
                items: [cboSupplier_mpob, {
                        xtype: 'datefield',
                        fieldLabel: 'Tgl PO',
                        emptyText: 'Tanggal Awal',
                        name: 'tgl_awal',
                        id: 'id_tgl_awal_mpob',
                        maxLength: 255,
                        anchor: '90%',
                        value: '',
                        format: 'd-M-Y'
                    },
                    cboStatusPO_mpob,
                    cboClosePO_mpob,
                    cboNoPOInduk_mpob
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
                        id: 'id_nama_supplier_mpob',
                        anchor: '90%',
                        value: '',
                        emptyText: 'Nama Supplier'
                    }, {
                        xtype: 'datefield',
                        fieldLabel: 's/d',
                        emptyText: 'Tanggal Akhir',
                        name: 'tgl_akhir',
                        id: 'id_tgl_akhir_mpob',
                        maxLength: 255,
                        anchor: '90%',
                        value: '',
                        format: 'd-M-Y'
                    },
                    cboTypePurchase_mpob,
                    {
                        fieldLabel: 'Peruntukan <span class="asterix">*</span>',
                        xtype: 'radiogroup',
                        name: 'kd_peruntukan',
                        columnWidth: [.5, .5],
                        allowBlank: false,
                        anchor: '90%',
                        items: [{
                                boxLabel: 'Supermarket',
                                name: 'kd_peruntukan',
                                inputValue: '0',
                                id: 'id_peruntukan_supermarket_mpob',
                                checked: true
                            }, {
                                boxLabel: 'Distribusi',
                                name: 'kd_peruntukan',
                                inputValue: '1',
                                id: 'id_peruntukan_distribusi_mpob'
                            }]
                    }
                ]
            }],
        buttons: [{
                text: 'Filter',
                formBind: true,
                handler: function() {
                    storeMonitoringPOBonus_mpob.reload({
                        params: {
                            kd_supplier: Ext.getCmp('id_cbo_supplier_mpob').getValue(),
                            tgl_awal: Ext.getCmp('id_tgl_awal_mpob').getValue(),
                            tgl_akhir: Ext.getCmp('id_tgl_akhir_mpob').getValue(),
                            approval_po: Ext.getCmp('id_cbo_status_cpob').getValue(),
                            close_po: Ext.getCmp('id_cbo_close_po_cpob').getValue(),
                            konsinyasi: Ext.getCmp('id_cbo_type_purchase_mpob').getValue(),
                            peruntukan_sup: Ext.getCmp('id_peruntukan_supermarket_mpob').getValue(),
                            peruntukan_dist: Ext.getCmp('id_peruntukan_distribusi_mpob').getValue(),
                            no_po_induk: Ext.getCmp('id_cbo_no_po_induk_mpob').getValue()
                        }
                    });
                }
            }, {
                text: 'Reset',
                formBind: true,
                handler: function() {
                    // clearmonitoringPO();
                    Ext.getCmp('monitoring_po_bonus').getForm().reset();
                    storeMonitoringPOBonus_mpob.removeAll();
                }
            }]
    };

    // start GRID MONITORING PO Bonus
    var storeMonitoringPOBonus_mpob = new Ext.data.Store({
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
                'tanggal_do',
                'peruntukan'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("monitoring_purchase_order_bonus_controller/finalGetRows") ?>',
            method: 'POST'
        }),
        listeners: {
            loadexception: function(event, options, response, error) {
                //                var err = Ext.util.JSON.decode(response.responseText);
                //                if (err.errMsg == 'Session Expired') {
                //                    session_expired(err.errMsg);
                //                }
            }
        }
    });
    var searchPoBonus_mpob = new Ext.app.SearchField({
        store: storeMonitoringPOBonus_mpob,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        emptyText: 'No PO, No PR',
        id: 'id_search_grid_po_bonus_mpob'
    });
    var tbPOBOnus = new Ext.Toolbar({
        items: [searchPoBonus_mpob]
    });
    var smPoBonus_mpob = new Ext.grid.CheckboxSelectionModel();
    var gridMonitoringPOBonus_mpob = new Ext.grid.EditorGridPanel({
        id: 'id_grid_monitoring_po_bonus_mpob',
        frame: true,
        border: true,
        stripeRows: true,
        sm: smPoBonus_mpob,
        store: storeMonitoringPOBonus_mpob,
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
            }, {
                header: "No RO",
                dataIndex: 'no_do',
                sortable: true,
                width: 150
            }, {
                header: "Tanggal RO",
                dataIndex: 'tanggal_do',
                sortable: true,
                width: 75
            }, {
                header: "Peruntukan",
                dataIndex: 'peruntukan',
                sortable: true,
                width: 75
            }],
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.Ajax.request({
                        url: '<?= site_url("monitoring_purchase_order/get_data_po") ?>/' + sel[0].get('no_po'),
                        method: 'POST',
                        params: {},
                        callback: function(opt, success, responseObj) {
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
        tbar: tbPOBOnus,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: storeMonitoringPOBonus_mpob,
            displayInfo: true
        })
    });


    var monitoringPurchaseOrderBonus_mpob = new Ext.FormPanel({
        id: 'monitoring_po_bonus',
        border: false,
        frame: true,
        autoScroll: true,
        bodyStyle: 'padding-right:20px;',
        labelWidth: 130,
        items: [headerMonitoringPOBonus_mpob, gridMonitoringPOBonus_mpob]
    });


</script>