<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">

    /**
     * declaraion of  main grid's store / store of setting penukaran point grid
     */
    var storePenukaranPoint_spp = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_barang', 'nama_produk', 'nm_satuan', 'qty', 'jumlah_point', 'aktif'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("setting_penukaran_point_controller/finalGetDataPenukaranPoint") ?>',
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

    storePenukaranPoint_spp.load();

    var storeDataBarang_spp = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_produk', 'nama_produk', 'nm_satuan'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("setting_penukaran_point_controller/finalGetDataProduk") ?>',
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
     * declaration of data penukaran point grid's selection model 
     */
    var smGridDataPenukaranPoint_spp = new Ext.grid.CheckboxSelectionModel();

    /**
     * declaration of data penukaran point grid's selection model 
     */
    var smGridDataProduk_spp = new Ext.grid.CheckboxSelectionModel();

    /**
     * declaration of the search field of  gridDataPenukaranPoint_spp
     */
    var searchGridDataPenukaranPoint_spp = new Ext.app.SearchField({
        store: storePenukaranPoint_spp,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        id: 'id_search_field_grid_data_penukaran_point_spp'
    });

    /**
     * declaration of the search field of  gridDataProduk_spp
     */
    var searchGridDataProduk_spp = new Ext.app.SearchField({
        store: storeDataBarang_spp,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        id: 'id_search_field_grid_data_produk_spp'
    });

    /**
     * declaration of the top toolbar collection grid
     */
    var topToolbarGridDataPenukaranPoint_spp = new Ext.Toolbar({
        items: [{
                text: 'Add',
                icon: BASE_ICONS + 'add.png',
                onClick: function() {
                    Ext.getCmp('id_setting_penukaran_point_form_spp').getForm().reset();
                    //windowDataPenukaranPoint_spp.getForm().reset();
                    Ext.getCmp('id_btn_submit_setting_penukaran_point_spp').setText('save');
                    Ext.getCmp('id_txt_quantity_spp').setReadOnly(false);
                    Ext.getCmp('id_txt_quantity_spp').removeClass('readonly-input');
                    windowDataPenukaranPoint_spp.setTitle('Save Data Form');
                    windowDataPenukaranPoint_spp.show();
                }
            }, '-',
            searchGridDataCollection_cp]
    });

    /**
     * declaration of the top toolbar collection grid
     */
    var topToolbarGridDataProduk_spp = new Ext.Toolbar({
        items: [searchGridDataProduk_spp]
    });

    /**
     * declaration of combo box grid / data produk grid
     */
    var gridDataProduk_spp = new Ext.grid.GridPanel({
        frame: true,
        border: true,
        stripeRows: true,
        id: 'id_grid_data_produk_spp',
        store: storeDataBarang_spp,
        sm: smGridDataProduk_spp,
        loadMask: true,
        title: 'Data Produk',
        height: 500,
        columns: [{
                header: "Kode Barang",
                dataIndex: 'kd_produk',
                sortable: true,
                width: 100
            }, {
                header: "Nama Barang",
                dataIndex: 'nama_produk',
                sortable: true,
                width: 150
            }, {
                header: "Satuan",
                dataIndex: 'nm_satuan',
                sortable: true,
                width: 100
            }],
        listeners: {
            'rowclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                Ext.getCmp('id_combo_produk_spp').setValue(sel[0].get('kd_produk'));
                Ext.getCmp('id_txt_nama_barang_spp').setValue(sel[0].get('nama_produk'));
                Ext.getCmp('id_txt_satuan_spp').setValue(sel[0].get('nm_satuan'));
                menuSettingPenukaranPoint_spp.hide();
            }
        },
        tbar: topToolbarGridDataProduk_spp,
        bbar: new Ext.PagingToolbar({
            pageSize: 10,
            store: storeDataBarang_spp,
            displayInfo: true
        })
    });

    /**
     *combo pelanggan dist menu declaration
     */
    var menuSettingPenukaranPoint_spp = new Ext.menu.Menu();
    menuSettingPenukaranPoint_spp.add(new Ext.Panel({
        title: 'Pilih Produk',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 600,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridDataProduk_spp],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menuSettingPenukaranPoint_spp.hide();
                }
            }]
    }));

    /**
     * deklarasi twin combo box
     */
    Ext.ux.TwincomboProduk_ssp = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            //load store grid
            storeDataBarang_spp.load();
            menuSettingPenukaranPoint_spp.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
                trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    /**
     * deklarasi combo boc pelanggan dist
     */
    var comboDataProduk_ssp = new Ext.ux.TwincomboProduk_ssp({
        fieldLabel: 'Kode Barang <span class="asterix">*</span>',
        id: 'id_combo_produk_spp',
        store: storeDataBarang_spp,
        mode: 'local',
        valueField: 'kd_produk_spp',
        displayField: 'kd_produk_spp',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        anchor: '95%',
        width: 200,
        hiddenName: 'combo_produk_spp',
        emptyText: 'Pilih Produk'
    });

    /**
     * deklarasi grid penukaran row action
     */
    var gridDataPenukaranPointEdit_cp = new Ext.ux.grid.RowActions({
        header: 'Edit',
        autoWidth: false,
        locked: true,
        width: 50,
        actions: [{iconCls: 'icon-edit-record', qtip: 'Edit'}],
        widthIntercept: Ext.isSafari ? 4 : 2
    });
    var gridDataPenukaranPointDelete_cp = new Ext.ux.grid.RowActions({
        header: 'Delete',
        autoWidth: false,
        locked: true,
        width: 50,
        actions: [{iconCls: 'icon-delete-record', qtip: 'Delete'}],
        widthIntercept: Ext.isSafari ? 4 : 2
    });

    gridDataPenukaranPointEdit_cp.on('action', function(grid, record, action, row, col) {
        var kdBarang = record.get('kd_barang');
        var namaBarang = record.get('nama_produk');
        var satuan = record.get('nm_satuan');
        var jumlahPoint = record.get('jumlah_point');
        var qty = record.get('qty');
        var aktif = record.get('aktif');
        switch (action) {
            case 'icon-edit-record':
                generateUpdateForm(kdBarang, namaBarang, satuan, jumlahPoint, qty, aktif);
                break;
            case 'icon-delete-record':
                deleteSettingPenukaranPoint_spp(kdBarang, qty);
                break;
        }
    });

    /**
     * declaration of the main grid
     */
    var gridDataPenukaranPoint_spp = new Ext.grid.GridPanel({
        frame: true,
        border: true,
        stripeRows: true,
        id: 'id_grid_data_penukaran_point_spp',
        store: storePenukaranPoint_spp,
        sm: smGridDataPenukaranPoint_spp,
        loadMask: true,
        title: 'Data Penukaran Point',
        height: 500,
        columns: [gridDataPenukaranPointEdit_cp, gridDataPenukaranPointDelete_cp, {
                header: "Kode Barang",
                dataIndex: 'kd_barang',
                sortable: true,
                width: 100
            }, {
                header: "Nama Barang",
                dataIndex: 'nama_produk',
                sortable: true,
                width: 270
            }, {
                header: "Satuan",
                dataIndex: 'nm_satuan',
                sortable: true,
                width: 100
            }, {
                header: "Quantity",
                dataIndex: 'qty',
                sortable: true,
                width: 100
            }, {
                header: "Jumlah Point",
                dataIndex: 'jumlah_point',
                sortable: true,
                width: 100
            }],
        plugins: [gridDataPenukaranPointEdit_cp, gridDataPenukaranPointDelete_cp],
        tbar: topToolbarGridDataPenukaranPoint_spp,
        bbar: new Ext.PagingToolbar({
            pageSize: 10,
            store: storePenukaranPoint_spp,
            displayInfo: true
        })
    });


    Ext.ns('settingPenukaranPointForm_spp');
    settingPenukaranPointForm_spp.Form = Ext.extend(Ext.form.FormPanel, {
        // defaults - can be changed from outside
        border: false,
        frame: true,
        labelWidth: 130,
        url: '<?= site_url("collection_pelanggan_controller/finalProcessing") ?>',
        constructor: function(config) {
            config = config || {};
            config.listeners = config.listeners || {};
            Ext.applyIf(config.listeners, {
                actioncomplete: function() {
                    //if (console && console.log) {
                    //    console.log('actioncomplete:', arguments);
                    //}
                },
                actionfailed: function() {
                    //if (console && console.log) {
                    //    console.log('actionfailed:', arguments);
                    //}
                }
            });
            settingPenukaranPointForm_spp.Form.superclass.constructor.call(this, config);
        },
        initComponent: function() {

            // hard coded - cannot be changed from outsid
            var config = {
                defaultType: 'textfield',
                defaults: {labelSeparator: ''},
                monitorValid: true,
                autoScroll: false // ,buttonAlign:'right'
                ,
                items: [
                    comboDataProduk_ssp, {
                        xtype: 'textfield',
                        anchor: '95%',
                        fieldLabel: 'Nama Barang',
                        id: 'id_txt_nama_barang_spp',
                        name: 'txt_nama_barang_spp',
                        readOnly: true,
                        fieldClass: 'readonly-input'
                    }, {
                        xtype: 'textfield',
                        anchor: '95%',
                        fieldLabel: 'Satuan',
                        id: 'id_txt_satuan_spp',
                        name: 'txt_satuan_spp',
                        readOnly: true,
                        fieldClass: 'readonly-input'
                    }, {
                        xtype: 'numberfield',
                        anchor: '60%',
                        fieldLabel: 'Quantity <span class="asterix">*</span>',
                        id: 'id_txt_quantity_spp',
                        name: 'txt_quantity_spp',
                        allowBlank: false
                    }, {
                        xtype: 'numberfield',
                        anchor: '60%',
                        fieldLabel: 'Jumlah Point <span class="asterix">*</span>',
                        id: 'id_txt_jumlah_point_spp',
                        name: 'txt_jumlah_point_spp',
                        allowBlank: false
                    }, {
                        xtype: 'checkbox',
                        fieldLabel: 'Aktif',
                        name: 'check_aktif_jumlah_point_spp',
                        id: 'id_check_aktif_jumlah_point_spp',
                        anchor: '90%'
                    }
                ],
                buttons: [{
                        text: 'submit',
                        id: 'id_btn_submit_setting_penukaran_point_spp',
                        formBind: true,
                        scope: this,
                        handler: this.submit
                    }, {
                        text: 'reset',
                        id: 'id_btn_reset_setting_penukaran_point_spp',
                        scope: this,
                        handler: this.reset
                    }, {
                        text: 'close',
                        id: 'id_btn_close_setting_penukaran_point_spp',
                        scope: this,
                        handler: function() {
                            windowDataPenukaranPoint_spp.hide();
                        }
                    }]
            }; // eo config object
            // apply config
            Ext.apply(this, Ext.apply(this.initialConfig, config));
            // call parent
            settingPenukaranPointForm_spp.Form.superclass.initComponent.apply(this, arguments);
        } // eo function initComponent  
        ,
        onRender: function() {

            // call parent
            settingPenukaranPointForm_spp.Form.superclass.onRender.apply(this, arguments);
            // set wait message target
            this.getForm().waitMsgTarget = this.getEl();
            // loads form after initial layout
            // this.on('afterlayout', this.onLoadClick, this, {single:true});

        } // eo function onRender
        ,
        reset: function() {
            this.getForm().reset();
        },
        submit: function() {
            var up = Ext.getCmp('id_btn_submit_setting_penukaran_point_spp').getText();
            if (up == 'update') {
                updateSettingPenukaranPoint_spp();
            } else {
                insertSettingPenukaranPoint_spp();
            }
        } // eo function submit
    });

    /**
     * register the form
     */
    Ext.reg('settingPenukaranPointForm_spp', settingPenukaranPointForm_spp.Form);

    /**
     * declaration of the window
     */
    var windowDataPenukaranPoint_spp = new Ext.Window({
        id: 'id_window_penukaran_point_spp',
        closeAction: 'hide',
        width: 550,
        height: 350,
        layout: 'fit',
        border: false,
        items: {
            id: 'id_setting_penukaran_point_form_spp',
            xtype: 'settingPenukaranPointForm_spp'
        },
        onHide: function() {
            Ext.getCmp('id_setting_penukaran_point_form_spp').getForm().reset();
        }
    });


    /**
     * declaration of the main panel of this form
     */
    Ext.ns('id_setting_penukaran_point');
    var settingPenukaranPoint_spp = new Ext.FormPanel({
        id: 'id_setting_penukaran_point',
        border: false,
        frame: true,
        autoScroll: true,
        bodyStyle: 'padding:5px;',
        items: [
            gridDataPenukaranPoint_spp
        ]
    });

    function generateUpdateForm(kdBarang, namaBarang, satuan, jumlahPoint, qty, aktif) {
        Ext.getCmp('id_btn_submit_setting_penukaran_point_spp').setText('update');
        windowDataPenukaranPoint_spp.setTitle('Edit Data Form');
        Ext.getCmp('id_combo_produk_spp').setValue(kdBarang);
        Ext.getCmp('id_txt_nama_barang_spp').setValue(namaBarang);
        Ext.getCmp('id_txt_satuan_spp').setValue(satuan);
        Ext.getCmp('id_txt_jumlah_point_spp').setValue(jumlahPoint);
        Ext.getCmp('id_txt_quantity_spp').setValue(qty);
        Ext.getCmp('id_txt_quantity_spp').setReadOnly(true);
        Ext.getCmp('id_txt_quantity_spp').addClass('readonly-input');
        Ext.getCmp('id_check_aktif_jumlah_point_spp').setValue(aktif);
        windowDataPenukaranPoint_spp.show();
    }
    /**
     * processing form method declaration
     */
    function settingPenukaranPointProcessing_spp(command, kdBarang, jumlahPoint, qty, aktif, msg) {
        var c = '';
        if (command == 'update') {
            c = 'updating';
        } else if (command == 'save') {
            c = 'saving';
        } else {
            c = 'deleting';
        }
        var box = Ext.MessageBox.wait(c + ' ' + 'data', 'Please Wait.....');
        Ext.Ajax.request({
            url: '<?= site_url("setting_penukaran_point_controller/finalProcessing") ?>',
            method: 'POST',
            waitMsg: 'Processing Data...',
            params: {
                cmd: command,
                combo_produk_spp: kdBarang,
                txt_jumlah_point_spp: jumlahPoint,
                check_aktif_jumlah_point_spp: aktif,
                txt_quantity_spp: qty
            },
            callback: function(opt, success, responseObj) {
                var de = Ext.util.JSON.decode(responseObj.responseText);
                //console.log(success);
                if (de.success == true) {
                    Ext.Msg.show({
                        title: 'Success',
                        msg: msg,
                        modal: true,
                        icon: Ext.Msg.INFO,
                        buttons: Ext.Msg.OK,
                        fn: function(btn) {
                            if (btn == 'ok' && de.errMsg == 'Session Expired') {
                                window.location = '<?= site_url("auth/login") ?>';
                            }
                        }
                    });
                    storePenukaranPoint_spp.reload();
                    windowDataPenukaranPoint_spp.hide();
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


    function insertSettingPenukaranPoint_spp() {
        var command = 'save';
        var msg = 'saved succesfully';
        var kdBarang = Ext.getCmp('id_combo_produk_spp').getValue();
        var jumlahPoint = Ext.getCmp('id_txt_jumlah_point_spp').getValue();
        var qty = Ext.getCmp('id_txt_quantity_spp').getValue();
        var aktif = Ext.getCmp('id_check_aktif_jumlah_point_spp').getValue();
        settingPenukaranPointProcessing_spp(command, kdBarang, jumlahPoint, qty, aktif, msg);
    }

    function updateSettingPenukaranPoint_spp() {
        var command = 'update';
        var msg = 'updated succesfully';
        var kdBarang = Ext.getCmp('id_combo_produk_spp').getValue();
        var jumlahPoint = Ext.getCmp('id_txt_jumlah_point_spp').getValue();
        var qty = Ext.getCmp('id_txt_quantity_spp').getValue();
        var aktif = Ext.getCmp('id_check_aktif_jumlah_point_spp').getValue();
        Ext.Msg.show({
            title: 'Confirm',
            msg: 'Are you sure update selected row ?',
            buttons: Ext.Msg.YESNO,
            fn: function(btn) {
                if (btn == 'yes') {
                    settingPenukaranPointProcessing_spp(command, kdBarang, jumlahPoint, qty, aktif, msg);
                }
            }
        });
    }

    function deleteSettingPenukaranPoint_spp(kdBarang, qty) {
        var command = 'delete';
        var msg = 'deleted succesfully';
        Ext.Msg.show({
            title: 'Confirm',
            msg: 'Are you sure delete selected row ?',
            buttons: Ext.Msg.YESNO,
            fn: function(btn) {
                if (btn == 'yes') {
                    settingPenukaranPointProcessing_spp(command, kdBarang, '', qty, '', msg);
                }
            }
        });

    }

</script>
