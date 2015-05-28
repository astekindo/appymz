<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>

<script type="text/javascript">
    // twin combo lokasi awal
    var storeCboLokasi_cios = new Ext.data.ArrayStore({
        fields: ['kd_lokasi', 'nama_lokasi'],
        data: []
    });

    var storeGridCboLokasi_cios = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                {name: 'kd_lokasi', allowBlank: false, type: 'text'},
                {name: 'nama_lokasi', allowBlank: false, type: 'text'}
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("mutasi_barang/search_lokasi") ?>',
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

    storeGridCboLokasi_cios.on('load', function() {
        storeGridCboLokasi_cios.setBaseParam('sender', 'monitoring');
    });


    var searchGridLokasiAsal_cios = new Ext.app.SearchField({
        store: storeGridCboLokasi_cios,
        params: {
            sender: 'monitoring',
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_search_grid_lokasi_cios'
    });


    var gridLokasi_cios = new Ext.grid.GridPanel({
        store: storeGridCboLokasi_cios,
        stripeRows: true,
        frame: true,
        border: true,
        columns: [{
                header: 'Kode Lokasi',
                dataIndex: 'kd_lokasi',
                width: 100,
                sortable: true

            }, {
                header: 'Nama Lokasi',
                dataIndex: 'nama_lokasi',
                width: 400,
                sortable: true
            }],
        tbar: new Ext.Toolbar({
            items: [searchGridLokasiAsal_cios]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: storeGridCboLokasi_cios,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('id_cbo_lokasi_asal_cios').setValue(sel[0].get('kd_lokasi'));
                    menuLokasi_cios.hide();
                }
            }
        }
    });

    var menuLokasi_cios = new Ext.menu.Menu();
    menuLokasi_cios.add(new Ext.Panel({
        title: 'Pilih lokasi asal',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 700,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridLokasi_cios],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menuLokasi_cios.hide();
                }
            }]
    }));

    Ext.ux.TwinComboLokasi_cios = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            //load store grid
            storeGridCboLokasi_cios.load({
                params: {
                    sender: 'monitoring'
                }
            });
            menuLokasi_cios.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
                trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    menuLokasi_cios.on('hide', function() {
        var sf = Ext.getCmp('id_search_grid_lokasi_cios').getValue();
        if (sf != '') {
            Ext.getCmp('id_search_grid_lokasi_cios').setValue('');
            searchGridLokasiAsal_cios.onTrigger2Click();
        }
    });

    var cboLokasiAsal_cios = new Ext.ux.TwinComboLokasi_cios({
        fieldLabel: 'Lokasi',
        id: 'id_cbo_lokasi_asal_cios',
        store: storeCboLokasi_cios,
        mode: 'local',
        valueField: 'kd_lokasi',
        displayField: 'nama_lokasi',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: true,
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_lokasi',
        emptyText: 'Pilih lokasi'

    });


    //start cbo lokasi tujuan

    var searchGridLokasiTujuan_cios = new Ext.app.SearchField({
        store: storeGridCboLokasi_cios,
        params: {
            sender: 'monitoring',
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_search_grid_lokasi_tujuan__cios'
    });


    var gridLokasiTujuan_cios = new Ext.grid.GridPanel({
        store: storeGridCboLokasi_cios,
        stripeRows: true,
        frame: true,
        border: true,
        columns: [{
                header: 'Kode Lokasi',
                dataIndex: 'kd_lokasi',
                width: 100,
                sortable: true

            }, {
                header: 'Nama Lokasi',
                dataIndex: 'nama_lokasi',
                width: 400,
                sortable: true
            }],
        tbar: new Ext.Toolbar({
            items: [searchGridLokasiTujuan_cios]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: storeGridCboLokasi_cios,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('id_cbo_lokasi_tujuan_cios').setValue(sel[0].get('kd_lokasi'));
                    menuLokasiTujuan_cios.hide();
                }
            }
        }
    });

    var menuLokasiTujuan_cios = new Ext.menu.Menu();
    menuLokasiTujuan_cios.add(new Ext.Panel({
        title: 'Pilih lokasi asal',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 700,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridLokasiTujuan_cios],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menuLokasiTujuan_cios.hide();
                }
            }]
    }));

    Ext.ux.TwinComboLokasiTujuan_cios = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            //load store grid
            storeGridCboLokasi_cios.load({
                params: {
                    sender: 'monitoring'
                }
            });
            menuLokasiTujuan_cios.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
                trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    menuLokasiTujuan_cios.on('hide', function() {
        var sf = Ext.getCmp('id_search_grid_lokasi_tujuan_cios').getValue();
        if (sf != '') {
            Ext.getCmp('id_search_grid_lokasi_tujuan_cios').setValue('');
            searchGridLokasiTujuan_cios.onTrigger2Click();
        }
    });

    var cboLokasiTujuan_cios = new Ext.ux.TwinComboLokasiTujuan_cios({
        fieldLabel: 'Lokasi Tujuan',
        id: 'id_cbo_lokasi_tujuan_cios',
        store: storeCboLokasi_cios,
        mode: 'local',
        valueField: 'kd_lokasi',
        displayField: 'nama_lokasi',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: true,
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_lokasi',
        emptyText: 'Pilih lokasi'

    });


    // start COMBOBOX No mutasi
    var storeCboMutasi_cios = new Ext.data.ArrayStore({
        fields: ['no_bukti', 'tanggal', 'keterangan'],
        data: []
    });
    var storeGridMutasi_cios = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['no_bukti', 'tanggal', 'keterangan'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("cetak_in_out_stok_controller/finalGetDataNoBukti") ?>',
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
    var searchGridMutasi_cios = new Ext.app.SearchField({
        store: storeGridMutasi_cios,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_search_grid_mutasi_mutasi'
    });
    var gridMutasi_cios = new Ext.grid.GridPanel({
        store: storeGridMutasi_cios,
        stripeRows: true,
        frame: true,
        border: true,
        columns: [{
                header: 'No.Mutasi',
                dataIndex: 'no_bukti',
                width: 100,
                sortable: true
            }, {
                header: 'Tanggal',
                dataIndex: 'tanggal',
                width: 90,
                sortable: true
            }, {
                header: 'Keterangan',
                dataIndex: 'keterangan',
                width: 400,
                sortable: true
            }],
        tbar: new Ext.Toolbar({
            items: [searchGridMutasi_cios]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: storeGridMutasi_cios,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('id_cbo_mutasi_cios').setValue(sel[0].get('no_bukti'));
                    menuCboMutasi_cios.hide();
                }
            }
        }
    });

    var menuCboMutasi_cios = new Ext.menu.Menu();
    menuCboMutasi_cios.add(new Ext.Panel({
        title: 'Pilih No Bukti',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridMutasi_cios],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menuCboMutasi_cios.hide();
                }
            }]
    }));
    Ext.ux.TwinCboMutasi_cios = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            //load store grid
            storeGridMutasi_cios.load();
            menuCboMutasi_cios.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
                trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
    menuCboMutasi_cios.on('hide', function() {
        var sf = Ext.getCmp('id_search_grid_mutasi_mutasi').getValue();
        if (sf !== '') {
            Ext.getCmp('id_search_grid_mutasi_mutasi').setValue('');
            searchGridMutasi_cios.onTrigger2Click();
        }
    }
    );
    var cboNoMutasi_cios = new Ext.ux.TwinCboMutasi_cios({
        fieldLabel: 'No. Bukti',
        id: 'id_cbo_mutasi_cios',
        store: storeCboMutasi_cios,
        mode: 'local',
        valueField: 'no_bukti',
        displayField: 'no_bukti',
        typeAhead: true,
        triggerAction: 'all',
        // allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'no_bukti',
        emptyText: 'Pilih No Bukti Mutasi'
    });
    // end COMBOBOX NO Mutasi

    var searchGridInOutStok_cios = new Ext.app.SearchField({
        store: storeGridMutasi_cios,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_search_grid_in_out_stok_cios'
    });

    var storeGridInOutStok_cios = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['no_bukti', 'tanggal', 'kd_produk', 'qty_in', 'qty_out', 'keterangan', 'nama_lokasi'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("cetak_in_out_stok_controller/finalGetDataInOutStok") ?>',
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

    var gridInOutStok_cios = new Ext.grid.GridPanel({
        store: storeGridInOutStok_cios,
        stripeRows: true,
        frame: true,
        border: true,
        height: 350,
        columns: [{
                header: 'No.Bukti',
                dataIndex: 'no_bukti',
                width: 100,
                sortable: true
            }, {
                header: 'Tanggal',
                dataIndex: 'tanggal',
                width: 90,
                sortable: true
            }, {
                header: 'Kd Produk',
                dataIndex: 'kd_produk',
                width: 100,
                sortable: true
            }, {
                header: 'QTY IN',
                dataIndex: 'qty_in',
                width: 70,
                sortable: true
            }, {
                header: 'QTY OUT',
                dataIndex: 'qty_out',
                width: 70,
                sortable: true
            }, {
                header: 'Lokasi',
                dataIndex: 'nama_lokasi',
                width: 200,
                sortable: true
            }, {
                header: 'Keterangan',
                dataIndex: 'keterangan',
                width: 200,
                sortable: true
            }],
        tbar: new Ext.Toolbar({
            items: [searchGridInOutStok_cios]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: storeGridInOutStok_cios,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    windowInOutStokPrint_cios.show();
                    Ext.getDom('id_cetak_in_out_stok_print_cios').src = '<?= site_url("in_out_stok/print_ios") ?>' + '/' + sel[0].get('no_bukti');
                }
            }
        }
    });

    /**
     * header 
     */
    var headerCetakInOutStok_cios = {
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
                    {
                        xtype: 'datefield',
                        fieldLabel: 'Tgl Awal',
                        emptyText: 'Tanggal Awal',
                        name: 'tanggal_awal_cios',
                        id: 'id_tanggal_awal_cios',
                        maxLength: 255,
                        anchor: '90%',
                        format: 'Y-m-d',
                        value: ''
                    }, cboLokasiAsal_cios
                ],
                buttons: [{
                        text: 'filter',
                        handler: function() {
                            storeGridInOutStok_cios.reload({
                                params: {
                                    tgl_awal: Ext.getCmp('id_tanggal_awal_cios').getValue(),
                                    tgl_akhir: Ext.getCmp('id_tanggal_akhir_cios').getValue(),
                                    no_bukti: Ext.getCmp('id_cbo_mutasi_cios').getValue(),
                                    lokasi: Ext.getCmp('id_cbo_lokasi_asal_cios').getValue()
                                }
                            });
                        }
                    }, {
                        text: 'reset',
                        handler: function() {
                            storeGridInOutStok_cios.removeAll();
                            Ext.getCmp('cetak_in_out_stok').getForm().reset();
                        }
                    }]
            }, {
                columnWidth: .5,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: {labelSeparator: ''},
                items: [
                    {
                        xtype: 'datefield',
                        fieldLabel: 'Tgl Akhir',
                        emptyText: 'Tanggal Akhir',
                        name: 'tanggal_akhir_cios',
                        id: 'id_tanggal_akhir_cios',
                        maxLength: 255,
                        anchor: '90%',
                        value: '',
                        format: 'Y-m-d'

                    }, cboNoMutasi_cios
                ]
            }]
    };

    var cetakInOutStok_cios = new Ext.FormPanel({
        id: 'cetak_in_out_stok',
        monitorValid: true,
        border: false,
        frame: true,
        bodyStyle: 'padding-right:20px;overflowY: auto',
        labelWidth: 130,
        autoScroll: true,
        items: [
            headerCetakInOutStok_cios,
            gridInOutStok_cios
        ],
        buttons: [
            {
                text: 'cetak',
                handler: function() {
                    var sm = gridInOutStok_cios.getSelectionModel();
                    var sel = sm.getSelections();
                    windowInOutStokPrint_cios.show();
                    Ext.getDom('id_cetak_in_out_stok_print_cios').src = '<?= site_url("in_out_stok/print_ios") ?>' + '/' + sel[0].get('no_bukti');
                }
            }
        ]

    });
    
    /**
     * deklarai window print
     */
    var windowInOutStokPrint_cios = new Ext.Window({
        id: 'id_window_in_out_stok_print',
        title: 'Print Barter Barang',
        closeAction: 'hide',
        width: 900,
        height: 450,
        layout: 'fit',
        border: false,
        //html: 'div id="lap_do" />'
        html: '<iframe style="width:100%;height:100%;" id="id_cetak_in_out_stok_print_cios" src=""></iframe>'
    });


</script>