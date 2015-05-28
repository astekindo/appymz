<?php if (!defined( 'BASEPATH')) exit( 'No direct script access allowed'); ?>
<script type="text/javascript">
    
	// start COMBOBOX SUPPLIER
	var strcbmposuplier = new Ext.data.ArrayStore({
        fields: ['nama_supplier'],
        data: []
    });
    var strgridmposuplier = new Ext.data.Store({
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
    var searchgridmposuplier = new Ext.app.SearchField({
        store: strgridmposuplier,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgridmposuplier'
    });
    var gridmposuplier = new Ext.grid.GridPanel({
        store: strgridmposuplier,
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
            items: [searchgridmposuplier]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridmposuplier,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function () {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('id_cbmposuplier').setValue(sel[0].get('kd_supplier'));
                    Ext.getCmp('mpo_nama_supplier').setValue(sel[0].get('nama_supplier'));
                    // strlaporanpenerimaanbarang.removeAll();       
                    menumposuplier.hide();
                }
            }
        }
    });
    var menumposuplier = new Ext.menu.Menu();
    menumposuplier.add(new Ext.Panel({
        title: 'Pilih Supplier',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridmposuplier],
        buttons: [{
            text: 'Close',
            handler: function () {
                menumposuplier.hide();
            }
        }]
    }));
    Ext.ux.TwinComboSupplierPO = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function () {
            //load store grid
            strgridmposuplier.load();
            menumposuplier.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
    menumposuplier.on('hide', function () {
        var sf = Ext.getCmp('id_searchgridmposuplier').getValue();
        if (sf != '') {
            Ext.getCmp('id_searchgridmposuplier').setValue('');
            searchgridmposuplier.onTrigger2Click();
        }
    });
    var cbmposuplier = new Ext.ux.TwinComboSupplierPO({
        fieldLabel: 'Kode Supplier',
        id: 'id_cbmposuplier',
        store: strcbmposuplier,
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

    // start COMBOBOX STATUS PO
    var valcbMpoStatusPO = [
        ['A', "All"],
        ['0', "Belum Approve"],
        ['1', "Approve"],
        ['9', "Rejected"]
    ];
    var strcbMpoStatusPO = new Ext.data.ArrayStore({
        fields: [{
            name: 'key'
        }, {
            name: 'value'
        }],
        data: valcbMpoStatusPO
    });

    var cbMpoStatusPO = new Ext.form.ComboBox({
        fieldLabel: 'Status PO',
        id: 'cbmpostatusPO',
        name: 'status',
        // allowBlank:false,
        store: strcbMpoStatusPO,
        valueField: 'key',
        displayField: 'value',
        mode: 'local',
        forceSelection: true,
        triggerAction: 'all',
        anchor: '90%'
    });
    // end COMBOBOX STATUS PO

    // start COMBOBOX CLOSE PO
    var valcbMpoClosePO = [
        ['A', "All"],
        ['0', "Open"],
        ['1', "Closed"]
    ];
    var strcbMpoClosePO = new Ext.data.ArrayStore({
        fields: [{
            name: 'key'
        }, {
            name: 'value'
        }],
        data: valcbMpoClosePO
    });
    var cbMpoClosePO = new Ext.form.ComboBox({
        fieldLabel: 'Close PO',
        id: 'cbmpoclosePO',
        name: 'close_po',
        // allowBlank:false,
        store: strcbMpoClosePO,
        valueField: 'key',
        displayField: 'value',
        mode: 'local',
        forceSelection: true,
        triggerAction: 'all',
        anchor: '90%'
    });
    // end COMBOBOX STATUS PO


    // start COMBOBOX TYPE PURCHASE
    var valCbMpoTypePurchase = [
        ['A', "All"],
        ['0', "Normal"],
        ['1', "Konsinyasi"],
        ['2', "Bonus"],
        ['3', "Asset"]
    ];
    var strCbMpoTypePurchase = new Ext.data.ArrayStore({
        fields: [{
            name: 'key'
        }, {
            name: 'value'
        }],
        data: valCbMpoTypePurchase
    });
    var cbMpoTypePurchase = new Ext.form.ComboBox({
        fieldLabel: 'Type Purchase',
        id: 'cbmpotypepurchase',
        name: 'konsinyasi',
        // allowBlank:false,
        store: strCbMpoTypePurchase,
        valueField: 'key',
        displayField: 'value',
        mode: 'local',
        forceSelection: true,
        triggerAction: 'all',
        anchor: '90%'
    });

	// HEADER MONITORING PO
    var headermonitoringPO = {
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
            items: [cbmposuplier, {
                    xtype: 'datefield',
                    fieldLabel: 'Tgl PO',
                    emptyText: 'Tanggal Awal',
                    name: 'tgl_awal',
                    id: 'mpo_tgl_awal',
                    maxLength: 255,
                    anchor: '90%',
                    value: '',
                    format: 'd-M-Y'
                },
                cbMpoStatusPO,
                cbMpoClosePO
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
                    id: 'mpo_nama_supplier',
                    anchor: '90%',
                    value: '',
                    emptyText: 'Nama Supplier'
                }, {
                    xtype: 'datefield',
                    fieldLabel: 's/d',
                    emptyText: 'Tanggal Akhir',
                    name: 'tgl_akhir',
                    id: 'mpo_tgl_akhir',
                    maxLength: 255,
                    anchor: '90%',
                    value: '',
                    format: 'd-M-Y'
                },
                cbMpoTypePurchase,
                {
                    fieldLabel: 'Peruntukan <span class="asterix">*</span>',
                    xtype: 'radiogroup',
                    name: 'kd_peruntukan',
                    columnWidth: [.5, .5],
                    allowBlank:false,
                    anchor: '90%',
                    items: [{
                            boxLabel: 'Supermarket',
                            name: 'kd_peruntukan',
                            inputValue: '0',
                            id: 'mpo_peruntukan_supermarket',
                            checked:true
                        }, {
                            boxLabel: 'Distribusi',
                            name: 'kd_peruntukan',
                            inputValue: '1',
                            id: 'mpo_peruntukan_distribusi'
                        }]
                }
            ]
        }],
        buttons: [{
            text: 'Filter',
            formBind: true,
            handler: function () {

                gridpembelianmonitoringPO.store.load({
                    params: {
                        kd_supplier: Ext.getCmp('id_cbmposuplier').getValue(),
                        tgl_awal: Ext.getCmp('mpo_tgl_awal').getValue(),
                        tgl_akhir: Ext.getCmp('mpo_tgl_akhir').getValue(),
                        status: Ext.getCmp('cbmpostatusPO').getValue(),
                        close_po: Ext.getCmp('cbmpoclosePO').getValue(),
                        konsinyasi: Ext.getCmp('cbmpotypepurchase').getValue(),
                        peruntukan_sup: Ext.getCmp('mpo_peruntukan_supermarket').getValue(),
                        peruntukan_dist: Ext.getCmp('mpo_peruntukan_distribusi').getValue(),
                    }
                });
            }
        }, {
            text: 'Reset',
            formBind: true,
            handler: function () {
                clearmonitoringPO();
//                Ext.getCmp('id_cbmposuplier').setValue('');
//                Ext.getCmp('mpo_nama_supplier').setValue('');
//                Ext.getCmp('mpo_tgl_awal').setRawValue('');
//                Ext.getCmp('mpo_tgl_akhir').setRawValue('');
//                Ext.getCmp('cbmpostatusPO').setValue('');
//                Ext.getCmp('cbmpoclosePO').setValue('');
//                Ext.getCmp('cbmpotypepurchase').setValue('');
//                gridpembelianmonitoringPO.store.removeAll();      
            }
        }]
    };

    // start GRID MONITORING PO
    var strpembelianmonitoringPO = new Ext.data.Store({
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
            url: '<?= site_url("monitoring_purchase_order/get_rows") ?>',
            method: 'POST'
        }),
        listeners: {
            loadexception: function (event, options, response, error) {
                //                var err = Ext.util.JSON.decode(response.responseText);
                //                if (err.errMsg == 'Session Expired') {
                //                    session_expired(err.errMsg);
                //                }
            }
        }
    });
    var searchMpo = new Ext.app.SearchField({
        store: strpembelianmonitoringPO,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        emptyText: 'No PO, No PR',
        id: 'idsearchMpo'
    });
    var tbMpo = new Ext.Toolbar({
        items: [searchMpo]
    });
    var smMpo = new Ext.grid.CheckboxSelectionModel();
    var gridpembelianmonitoringPO = new Ext.grid.EditorGridPanel({
        id: 'gridpembelianmonitoringPO',
        frame: true,
        border: true,
        stripeRows: true,
        sm: smMpo,
        store: strpembelianmonitoringPO,
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
        },{
            header: "Peruntukan",
            dataIndex: 'peruntukan',
            sortable: true,
            width: 75
        }],
        listeners: {
            'rowdblclick': function () {
                var sm = gridpembelianmonitoringPO.getSelectionModel();
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

        tbar: tbMpo,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strpembelianmonitoringPO,
            displayInfo: true
        })
    });
	// end GRID MONITORING PO

	// PANEL MONITORING PO
    var pembelianmonitoringPO = new Ext.FormPanel({
        id: 'pembelianmonitoringPO',
        border: false,
        frame: true,
        autoScroll:true,
        bodyStyle: 'padding-right:20px;',
        labelWidth: 130,
        items: [{
                bodyStyle: {
                    margin: '10px 0px 15px 0px'
                },
                items: [headermonitoringPO]
            },gridpembelianmonitoringPO ]
          });
          
    pembelianmonitoringPO.on('afterrender', function(){
        this.getForm().load({
            url: '<?= site_url("pembelian_create_request/get_form") ?>',
            success: function(form, action){
                var r = Ext.util.JSON.decode(action.response.responseText);
                if(r.data.user_peruntukan === "0"){
                    Ext.getCmp('mpo_peruntukan_supermarket').setValue(true);
                    Ext.getCmp('mpo_peruntukan_supermarket').show();
                    Ext.getCmp('mpo_peruntukan_distribusi').hide();
                }else if(r.data.user_peruntukan === "1"){
                    Ext.getCmp('mpo_peruntukan_distribusi').setValue(true);
                    Ext.getCmp('mpo_peruntukan_supermarket').hide();
                    Ext.getCmp('mpo_peruntukan_distribusi').show();
                }else{
                    Ext.getCmp('mpo_peruntukan_supermarket').setValue(true);
                    Ext.getCmp('mpo_peruntukan_supermarket').show();
                    Ext.getCmp('mpo_peruntukan_distribusi').show();
                }
            },
            failure: function(form, action){
                var de = Ext.util.JSON.decode(action.response.responseText);
                Ext.Msg.show({
                    title: 'Error',
                    msg: de.errMsg,
                    modal: true,
                    icon: Ext.Msg.ERROR,
                    buttons: Ext.Msg.OK,
                    fn: function(btn){
                        if (btn == 'ok' && de.errMsg == 'Session Expired') {
                            window.location = '<?= site_url("auth/login") ?>';
                        }
                    }
                });
            }
        });
    });
	
    function clearmonitoringPO(){
        Ext.getCmp('pembelianmonitoringPO').getForm().reset();
        Ext.getCmp('pembelianmonitoringPO').getForm().load({
            url: '<?= site_url("pembelian_create_request/get_form") ?>',
            success: function(form, action){
                var r = Ext.util.JSON.decode(action.response.responseText);
                if(r.data.user_peruntukan === "0"){
                    Ext.getCmp('mpo_peruntukan_supermarket').setValue(true);
                    Ext.getCmp('mpo_peruntukan_supermarket').show();
                    Ext.getCmp('mpo_peruntukan_distribusi').hide();
                }else if(r.data.user_peruntukan === "1"){
                    Ext.getCmp('mpo_peruntukan_distribusi').setValue(true);
                    Ext.getCmp('mpo_peruntukan_supermarket').hide();
                    Ext.getCmp('mpo_peruntukan_distribusi').show();
                }else{
                    Ext.getCmp('mpo_peruntukan_supermarket').setValue(true);
                    Ext.getCmp('mpo_peruntukan_supermarket').show();
                    Ext.getCmp('mpo_peruntukan_distribusi').show();
                }
            },
            failure: function(form, action){
                var de = Ext.util.JSON.decode(action.response.responseText);
                Ext.Msg.show({
                    title: 'Error',
                    msg: de.errMsg,
                    modal: true,
                    icon: Ext.Msg.ERROR,
                    buttons: Ext.Msg.OK,
                    fn: function(btn){
                        if (btn == 'ok' && de.errMsg == 'Session Expired') {
                            window.location = '<?= site_url("auth/login") ?>';
                        }
                    }
                });
            }
        });
        strpembelianmonitoringPO.removeAll();
    }
</script>