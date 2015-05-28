<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">


/**
 * Header
 */
    var strComboKGPOSupplier = new Ext.data.ArrayStore({
        fields: ['kd_supplier'],
        data: []
    });

    var strGridKGPOSupplier = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_supplier', 'nama_supplier', 'pic', 'alamat'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("pembelian_receive_order/search_supplier") ?>',
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

    strGridKGPOSupplier.on('load', function() {
        searchGridKGPOSupplier.focus();
    });

    var searchGridKGPOSupplier = new Ext.app.SearchField({
        store: strGridKGPOSupplier,
        width: 350,
    });


    var gridKGPOSupplier = new Ext.grid.GridPanel({
        store: strGridKGPOSupplier,
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
            }, {
                header: 'PIC',
                dataIndex: 'pic',
                width: 100,
                sortable: true
            }, {
                header: 'Alamat',
                dataIndex: 'alamat',
                width: 200,
                sortable: true
            }],
        tbar: new Ext.Toolbar({
            items: [searchGridKGPOSupplier]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strGridKGPOSupplier,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('id_kgpo_kd_supplier').setValue(sel[0].get('kd_supplier'));
                    Ext.getCmp('id_kgpo_nama_supplier').setValue(sel[0].get('nama_supplier'));

                    menuKGPOSupplier.hide();
                }
            }
        }
    });

    var menuKGPOSupplier = new Ext.menu.Menu();
    menuKGPOSupplier.add(new Ext.Panel({
        title: 'Pilih Supplier',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridKGPOSupplier],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menuKGPOSupplier.hide();
                }
            }]
    }));

    Ext.ux.TwinComboKGPOSupplier = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            //load store grid
            strGridKGPOSupplier.load();
            menuKGPOSupplier.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    var comboKGPOSupplier = new Ext.ux.TwinComboKGPOSupplier({
        fieldLabel: 'Supplier <span class="asterix">*</span>',
        id: 'id_kgpo_kd_supplier',
        store: strComboKGPOSupplier,
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


/**
 * Header
 */
    var headerGridPO = {
        layout: 'column',
        border: false,
        buttonAlign:'left',
        items: [{
            columnWidth: .3,
            layout: 'form',
            border: false,
            labelWidth: 100,
            defaults: {labelSeparator: ''},
            items: [{
                xtype: 'datefield',
                format:'d-m-Y',
                fieldLabel: 'Bulan',
                name: 'tgl_cari',
                id: 'id_grid_kons_gpo_tanggal',
                anchor: '90%',
                allowBlank: false,
                value: new Date(),
                maxDate: new Date()
            }]
        }, {
            columnWidth: .3,
            layout: 'form',
            border: false,
            labelWidth: 100,
            defaults: { labelSeparator: ''},
            items: [comboKGPOSupplier]
        }, {
            columnWidth: .4,
            layout: 'form',
            border: false,
            labelWidth: 100,
            defaults: { labelSeparator: ''},
            items: [{
                xtype: 'textfield',
                fieldLabel: 'Nama Supplier',
                name: 'nama_supplier',
                readOnly:true,
                fieldClass:'readonly-input',
                id: 'id_kgpo_nama_supplier',
                anchor: '90%',
                value:''
            }]
        }],
        buttons: [{
            text: 'Filter',
            formBind: true,
            handler: function() {
                gridKonsGeneratePO.store.load({
                    params: {
                        tgl_cari: Ext.getCmp('id_grid_kons_gpo_tanggal').getValue(),
                        kd_supplier: Ext.getCmp('id_kgpo_kd_supplier').getValue()
                    }
                });
            }
        }, {
            text: 'Reset',
            formBind: true,
            handler: function() {
                Ext.getCmp('id_grid_kons_gpo_tanggal').setValue('');
                gridKonsGeneratePO.store.removeAll();
            }
        }, {
            text: 'Generate',
            formBind: true,
            handler: function() {
                Ext.getCmp('kons_generate_po').getForm().submit({
                    url: '<?= site_url("generate_po/update_row") ?>',
                    scope: this,
                    waitMsg: 'Saving Data...',
                    success: function(form, action){
                        var r = Ext.util.JSON.decode(action.response.responseText);
                        Ext.Msg.show({
                            title: 'Success',
                            msg: r.successMsg,
                            modal: true,
                            icon: Ext.Msg.INFO,
                            buttons: Ext.Msg.OK
                        });
                        clearform('kons_generate_po');
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

/**
 * Main grid
 */
    var strGridGeneratePO = new Ext.data.GroupingStore({
        reader: new Ext.data.JsonReader({
            fields: [
                'kd_supplier',
                'nama_supplier',
                'blth',
                'kd_produk',
                'nama_produk',
                'qty',
                'nm_satuan'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("generate_po/get_rows") ?>',
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
        groupField: 'nama_supplier'
    });

    strGridGeneratePO.on('load', function () {
        this.setBaseParam('tgl_cari', Ext.getCmp('id_grid_kons_gpo_tanggal').getValue());
        this.setBaseParam('kd_supplier', Ext.getCmp('id_kgpo_kd_supplier').getValue());
    })

    var searchKonsGeneratePO = new Ext.app.SearchField({
        store: strGridGeneratePO,
        width: 220
    });

    var toolbarKonsGeneratePO = new Ext.Toolbar({
        items: [searchKonsGeneratePO]
    });

    var smGridKGP = new Ext.grid.CheckboxSelectionModel();


    var gridKonsGeneratePO = new Ext.grid.GridPanel({
        id: 'id_grid_kons_gen_po',
        frame: true,
        border: true,
        stripeRows: true,
        sm: smGridKGP,
        store: strGridGeneratePO,
        loadMask: true,
        title: 'Barang',
        style: 'margin:0 auto;',
        height: 450,
        view: new Ext.grid.GroupingView({
            forceFit: true,
            groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "Items" : "Item"]})'
        }),
        columns: [
            {
                header: "Supplier",
                dataIndex: 'kd_supplier',
                sortable: true,
                hidden: true,
                width: 100
            }, {
                header: "Nama Supplier",
                dataIndex: 'nama_supplier',
                sortable: true,
                hidden: true,
                width: 100
            }, {
                header: "Kode Produk",
                dataIndex: 'kd_produk',
                sortable: true,
                width: 100
            }, {
                header: "Nama Produk",
                dataIndex: 'nama_produk',
                sortable: true,
                width: 400
            }, {
                header: "Qty",
                dataIndex: 'qty',
                sortable: true,
                width: 50
            },{
                header: "Satuan",
                dataIndex: 'nm_satuan',
                sortable: true,
                width: 100
            }],
        tbar: toolbarKonsGeneratePO,
        bbar: new Ext.PagingToolbar({ store: strGridGeneratePO, displayInfo: true })
    });

    var konsinyasiGeneratePO = new Ext.FormPanel({
        id: 'kons_generate_po',
        border: false,
        frame: true,
        autoScroll: true,
        monitorValid: true,
        bodyStyle: 'padding-right:20px;',
        labelWidth: 130,
        items: [headerGridPO, gridKonsGeneratePO]
    });
</script>
