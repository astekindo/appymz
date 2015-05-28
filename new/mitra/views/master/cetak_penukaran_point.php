<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>

<script type="text/javascript">
    /**
     * deklarasi store combo member
     */
    var storeComboMember_cpp = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_member', 'nmmember', 'alamat_penagihan', 'telepon', 'hp', 'email', 'total_point', 'jenis'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("penukaran_point_member_controller/finalGetDataMember") ?>',
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

    var searchGridMember_cpp = new Ext.app.SearchField({
        store: storeComboMember_cpp,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_search_grid_member_cpp'
    });


    /**
     * deklarasi grid member
     */
    var gridMember_cpp = new Ext.grid.GridPanel({
        store: storeComboMember_cpp,
        stripeRows: true,
        frame: true,
        border: true,
        //sm: smGridMember_cpp,
        columns: [{
                header: 'Kode Member',
                dataIndex: 'kd_member',
                width: 120,
                sortable: true
            }, {
                header: 'Nama Member',
                dataIndex: 'nmmember',
                width: 120,
                sortable: true
            }, {
                header: 'Alamat Tagih',
                dataIndex: 'alamat_tagih',
                width: 150,
                sortable: true
            }, {
                header: 'Jenis Member',
                dataIndex: 'jenis',
                width: 120,
                sortable: true
            }, {
                header: 'Telepon',
                dataIndex: 'telepon',
                width: 150,
                sortable: true
            }, {
                header: 'Hp',
                dataIndex: 'hp',
                width: 150,
                sortable: true
            }, {
                header: 'Email',
                dataIndex: 'email',
                width: 150,
                sortable: true
            }, {
                header: 'Total Point',
                dataIndex: 'total_point',
                width: 150,
                sortable: true
            }],
        tbar: new Ext.Toolbar({
            items: [searchGridMember_cpp]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: storeComboMember_cpp,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                Ext.getCmp('id_combo_member_cpp').setValue(sel[0].get('kd_member'));
                menuMember_cpp.hide();
            }
        }
    });

    /**
     * deklarasi menu combo member
     */
    var menuMember_cpp = new Ext.menu.Menu();
    menuMember_cpp.add(new Ext.Panel({
        title: 'Pilih Member',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridMember_cpp],
        buttons: [{
                text: 'Close',
                handler: function() {
                    //filter berdasar jumlah poin yang dimiliki member
                    //storeGridPenukaranPoint_cpp.load();
                    menuMember_cpp.hide();
                }
            }]
    }));



    /**
     * deklarasi twin combo member
     * @returns {undefined} */
    Ext.ux.TwincomboMember_cpp = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            //load store grid
            storeComboMember_cpp.load();
            menuMember_cpp.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
                trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
    menuMember_cpp.on('hide', function() {
        var sf = Ext.getCmp('id_search_grid_member_cpp').getValue();
        if (sf !== '') {
            Ext.getCmp('id_search_grid_member_cpp').setValue('');
            searchGridMember_cpp.onTrigger2Click();
        }
    });
    var comboMember_cpp = new Ext.ux.TwincomboMember_cpp({
        fieldLabel: 'Member',
        id: 'id_combo_member_cpp',
        store: storeComboMember_cpp,
        mode: 'local',
        valueField: 'kd_member',
        displayField: 'kd_member',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        anchor: '90%',
        //width: 300,
        hiddenName: 'kd_member',
        emptyText: 'Pilih Member'
    });

    var storeGridCetakPenukaranPoint_cpp = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'no_bukti',
                'kd_member',
                'nmmember',
                'kd_produk',
                'nama_produk',
                'qty_produk',
                'qty_point',
                'qty_tukar',
                'qty_point_tukar',
                'tanggal',
                'keterangan',
                'created_by',
                'created_date'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("cetak_penukaran_point_controller/finalGetDataPenukaranPoint") ?>',
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


    /**
     * deklarasi searchgrid penukaran point
     */
    var searchGridCetakPenukaranPoint_cpp = new Ext.app.SearchField({
        store: storeGridCetakPenukaranPoint_cpp,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_search_grid_cetak_penukaran_point_cpp'
    });

    /**
     *grid penukran point / first grid 
     */
    var gridCetakPenukaranPoint_cpp = new Ext.grid.GridPanel({
        frame: true,
        border: true,
        stripeRows: true,
        //sm: smGridBarterBarang_cbb,
        store: storeGridCetakPenukaranPoint_cpp,
        loadMask: true,
        height: 350,
        title: 'Data Penukaran Point',
        style: 'margin:0 auto;',
        columns: [
            {
                header: "No Bukti",
                dataIndex: 'no_bukti',
                sortable: true,
                width: 100
            }, {
                header: "Kode Member",
                dataIndex: 'kd_member',
                sortable: true,
                width: 70
            }, {
                header: "Nama Member",
                dataIndex: 'nmmember',
                sortable: true,
                width: 100
            }, {
                header: "Kode Produk",
                dataIndex: 'kd_produk',
                sortable: true,
                width: 70
            }, {
                header: "Nama Produk",
                dataIndex: 'nama_produk',
                sortable: true,
                width: 250
            }, {
                header: "QTY",
                dataIndex: 'qty_produk',
                sortable: true,
                width: 70
            }, {
                header: "QTY Point",
                dataIndex: 'qty_point',
                sortable: true,
                width: 70
            }, {
                header: "QTY Tukar",
                dataIndex: 'qty_tukar',
                sortable: true,
                width: 70
            }, {
                header: "QTY Point Tukar",
                dataIndex: 'qty_point_tukar',
                sortable: true,
                width: 100
            }, {
                header: "Tanggal",
                dataIndex: 'tanggal',
                sortable: true,
                width: 80
            }, {
                header: "Created By",
                dataIndex: 'created_by',
                sortable: true,
                width: 100
            }, {
                header: "Created Date",
                dataIndex: 'created_date',
                sortable: true,
                width: 80
            }, {
                header: "Keterangan",
                dataIndex: 'keterangan',
                sortable: true,
                width: 250
            }],
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                //if (sel.length() > 0) {
                    windowCetakPenukaranPoint_cpp.show();
                    Ext.getDom('id_cetak_window_penukaran_point_print_cpp').src = '<?= site_url("penukaran_point_member_controller/print_form") ?>' + '/' + sel[0].get('no_bukti');
                //}

            }
        },
        tbar: new Ext.Toolbar({
            items: [searchGridCetakPenukaranPoint_cpp]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: 10,
            store: storeGridCetakPenukaranPoint_cpp,
            displayInfo: true
        })
    });



    /**
     * header penukaran point
     */
    var headerCetakPenukaranPoint_cpp = {
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
                    comboMember_cpp
                ],
                buttons: [{
                        text: 'filter',
                        handler: function() {
                            storeGridCetakPenukaranPoint_cpp.reload({
                                params: {
                                    kd_member: Ext.getCmp('id_combo_member_cpp').getValue(),
                                    tanggal: Ext.getCmp('id_tanggal_penukaran_point_cpp').getValue()
                                }
                            });
                        }
                    }, {
                        text: 'reset',
                        handler: function() {
                            Ext.getCmp('cetak_penukaran_point').getForm().reset();
                            storeGridCetakPenukaranPoint_cpp.removeAll();
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
                        fieldLabel: 'Tanggal',
                        //emptyText: 'Tanggal Kwitansi',
                        name: 'tanggal_penukaran_point',
                        id: 'id_tanggal_penukaran_point_cpp',
                        maxLength: 255,
                        anchor: '90%',
                        value: '',
                        format: 'Y-m-d'

                    }
                ]
            }]
    };
    /**
     * main panel container
     */
    var cetakPenukaranPoint_cpp = new Ext.FormPanel({
        id: 'cetak_penukaran_point',
        monitorValid: true,
        border: false,
        frame: true,
        bodyStyle: 'padding-right:20px;',
        labelWidth: 130,
        autoScroll: true,
        items: [headerCetakPenukaranPoint_cpp, gridCetakPenukaranPoint_cpp],
        buttons: [
            {
                text: 'cetak',
                handler: function() {
                    var sm = gridCetakPenukaranPoint_cpp.getSelectionModel();
                    var sel = sm.getSelections();
                    windowCetakPenukaranPoint_cpp.show();
                    Ext.getDom('id_cetak_window_penukaran_point_print_cpp').src = '<?= site_url("penukaran_point_member_controller/print_form") ?>' + '/' + sel[0].get('no_bukti');
                }
            }
        ]
    });

    var windowCetakPenukaranPoint_cpp = new Ext.Window({
        id: 'id_cetak_window_penukaran_point_cpp',
        title: 'Print Penukaran Point',
        closeAction: 'hide',
        width: 900,
        height: 450,
        layout: 'fit',
        border: false,
        html: '<iframe style="width:100%;height:100%;" id="id_cetak_window_penukaran_point_print_cpp" src=""></iframe>'
    });
</script>