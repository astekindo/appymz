<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">
    /**
     * declarationn the of store for collection grid
     */
    var storeDataCollection_cp = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_collector', 'kd_cabang', 'nama_collector', 'alamat', 'no_telp', 'no_telp2', 'kd_area', 'status'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("collection_pelanggan_controller/finalGetDataCollection") ?>',
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

    storeDataCollection_cp.load();

    /**
     *declaration of the store for collection pelanggan grid
     */
    var storeDataCollectionArea_cp = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_collector', 'kd_area', 'nama_collector', 'nama_area', 'created_by', 'created_date', 'updated_by', 'updated_date'], root: 'data', totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("collection_pelanggan_controller/finalGetDataCollectionPelanggan") ?>',
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

    storeDataCollectionArea_cp.on('load', function() {
        storeDataCollectionArea_cp.setBaseParam('txt_kd_collector_cp', Ext.getCmp('id_txt_kd_collector_cp').getValue());
    });
    /**
     *declaration of store for combo box pelanggan grid 
     */
    var storeDataAreaDist_cp = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_area', 'nama_area'], root: 'data', totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("collection_pelanggan_controller/finalGetDataArea") ?>',
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
     * declaration of the search field of  data collection grid
     */
    var searchGridDataCollection_cp = new Ext.app.SearchField({
        store: storeDataCollection_cp,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        id: 'search_field_data_collection_cp'
    });

    /**
     * declaration of the search field of  data collection pelanggan grid
     */
    var searchGridDataCollectionArea_cp = new Ext.app.SearchField({
        store: storeDataCollectionArea_cp,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        emptyText: 'Nama Area',
        id: 'search_field_data_collection_pelanggan_cp'
    });

    /**
     * declaration search field of grid pelanggan dist
     */
    var searchgridDataAreaDist_cp = new Ext.app.SearchField({
        store: storeDataAreaDist_cp,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        id: 'search_field_data_pelanggan_dist_cp'
    });

    /**
     * declaration of the top toolbar collection grid
     */
    var toolBarSearchDataCollection_cp = new Ext.Toolbar({
        items: [searchGridDataCollection_cp]
    });

    /**
     * declaration of the top toolbar collection pelanggan grid
     */
    var topToolBarDataCollectionPelangganGrid_cp = new Ext.Toolbar({
        items: [{
                text: 'Add',
                icon: BASE_ICONS + 'add.png',
                onClick: function() {
                    var idCollector = Ext.getCmp('id_txt_kd_collector_cp').getValue();
                    var namaCollector = Ext.getCmp('id_txt_nama_collector_cp').getValue();
                    if (idCollector == '' || namaCollector == '' || idCollector == null || namaCollector == null) {
                        Ext.Msg.show({
                            title: 'Warning',
                            msg: 'Silahkan Klik Data Collection Terlebih Dahulu',
                            modal: true,
                            icon: Ext.Msg.WARNING,
                            buttons: Ext.Msg.OK
                        });
                        return;
                    } else {
                        Ext.getCmp('id_btn_submit_collection_cp').setText('save');
                        windowSettingCollectionPelanggan_cp.show();
                    }
                }
            }, '-', searchGridDataCollectionArea_cp]
    });

    /**
     * deklarasi top toolbar grid pelanggan dist 
     */
    var toolBarSearchDataAreaDist_cp = new Ext.Toolbar({
        items: [searchgridDataAreaDist_cp]
    });

    /**
     * declaration of grid data collection's selection model
     */
    var smGridDataCollection_cp = new Ext.grid.CheckboxSelectionModel();

    /**
     * declaration of grid data collection's selection model
     */
    var smgridDataCollectionArea_cp = new Ext.grid.CheckboxSelectionModel();

    /**
     *declaration of grid pelanggan dis selection model
     */
    var smGridDataAreaDist_cp = new Ext.grid.CheckboxSelectionModel();

    /**
     * declaration of first grid/grid collection
     */
    var gridDataCollection_cp = new Ext.grid.GridPanel({
        frame: true,
        border: true,
        stripeRows: true,
        id: 'id_grid_data_collection_cp',
        store: storeDataCollection_cp,
        sm: smGridDataCollection_cp,
        loadMask: true,
        title: 'Data Collection',
        height: 250,
        columns: [{
                header: "Kode Collector",
                dataIndex: 'kd_collector',
                sortable: true,
                width: 100
            }, {
                header: "Kode Cabang",
                dataIndex: 'kd_cabang',
                sortable: true,
                width: 80
            }, {
                header: "Nama Collector",
                dataIndex: 'nama_collector',
                sortable: true,
                width: 150
            }, {
                header: "Alamat",
                dataIndex: 'alamat',
                sortable: true,
                width: 150
            }, {
                header: "No Telp",
                dataIndex: 'no_telp',
                sortable: true,
                width: 150
            }, {
                header: "No Telp2",
                dataIndex: 'no_telp2',
                sortable: true,
                width: 150
            },{
                header: "Status",
                dataIndex: 'status',
                sortable: true,
                width: 80
            }],
        tbar: toolBarSearchDataCollection_cp,
        listeners: {
            'rowclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                storeDataCollectionArea_cp.reload({
                    params: {
                        txt_kd_collector_cp: sel[0].get('kd_collector')
                    }
                });
                Ext.getCmp('id_txt_kd_collector_cp').setValue(sel[0].get('kd_collector'));
                Ext.getCmp('id_txt_nama_collector_cp').setValue(sel[0].get('nama_collector'));
            }
        },
        bbar: new Ext.PagingToolbar({
            pageSize: 10,
            store: storeDataCollection_cp,
            displayInfo: true
        })
    });

    /**
     * deklarasi grid sales pelanggan row action
     */
    var gridCollectionPelangganEdit_cp = new Ext.ux.grid.RowActions({
        header: 'Edit',
        autoWidth: false,
        locked: true,
        width: 50,
        actions: [{iconCls: 'icon-edit-record', qtip: 'Edit'}],
        widthIntercept: Ext.isSafari ? 4 : 2
    });
    var gridCollectionPelangganDelete_cp = new Ext.ux.grid.RowActions({
        header: 'Delete',
        autoWidth: false,
        locked: true,
        width: 50,
        actions: [{iconCls: 'icon-delete-record', qtip: 'Delete'}],
        widthIntercept: Ext.isSafari ? 4 : 2
    });

    gridCollectionPelangganEdit_cp.on('action', function(grid, record, action, row, col) {
        var kdArea = record.get('kd_area');
        var kdCollector = record.get('kd_collector');
        var namaPelanggan = record.get('nama_area');
        var namaCollector = record.get('nama_collector');
        switch (action) {
            case 'icon-edit-record':
                generateUpdateForm(kdCollector, namaCollector, kdArea, namaPelanggan);
                break;
            case 'icon-delete-record':
                deleteCollectionPelanggan(kdCollector, kdArea);
                break;
        }
    });


    /**
     * declaration of collection pelanggan grid / second grid
     */
    var gridDataCollectionArea_cp = new Ext.grid.GridPanel({
        frame: true,
        border: true,
        stripeRows: true,
        store: storeDataCollectionArea_cp,
        sm: smgridDataCollectionArea_cp,
        loadMask: true,
        title: 'Data Collection Area',
        height: 250,
        columns: [//gridCollectionPelangganEdit_cp, 
            gridCollectionPelangganDelete_cp,
            {
                header: "Kode Collector",
                dataIndex: 'kd_collector',
                sortable: true,
                width: 150
            }, {
                header: "Nama Collector",
                dataIndex: 'nama_collector',
                sortable: true,
                width: 150
            }, {
                header: "Kode Area",
                dataIndex: 'kd_area',
                sortable: true,
                width: 150
            }, {
                header: "Nama Area",
                dataIndex: 'nama_area',
                sortable: true,
                width: 150
            }, {
                header: "Created By",
                dataIndex: 'created_by',
                sortable: true,
                width: 150
            }, {
                header: "Created Date",
                dataIndex: 'created_date',
                sortable: true,
                width: 150
            }, {
                header: "Updated By",
                dataIndex: 'updated_by',
                sortable: true,
                width: 150
            }, {
                header: "Updated Date",
                dataIndex: 'updated_date',
                sortable: true,
                width: 150
            }],
        listeners: {
            'rowclick': function() {
            }
        },
        plugins: [gridCollectionPelangganEdit_cp, gridCollectionPelangganDelete_cp],
        tbar: topToolBarDataCollectionPelangganGrid_cp,
        //plugins: [gridSalesPelangganEdit_cp, gridSalesPelangganDelete_cp],
        bbar: new Ext.PagingToolbar({
            pageSize: 10,
            store: storeDataCollectionArea_cp,
            displayInfo: true
        })
    });

    /**
     * deklarasi grid data pelanggan dist
     */
    var gridDataAreaDist_cp = new Ext.grid.GridPanel({
        frame: true,
        border: true,
        stripeRows: true,
        store: storeDataAreaDist_cp,
        sm: smGridDataAreaDist_cp,
        id: 'id_grid_area_dist_cp',
        loadMask: true,
        title: 'Data Data',
        height: 250,
        columns: [{
                header: "Kode Area",
                dataIndex: 'kd_area',
                sortable: true,
                width: 150
            }, {
                header: "Nama Area",
                dataIndex: 'nama_area',
                sortable: true,
                width: 150
            }],
        listeners: {
            'rowclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('id_combo_area_dist_cp').setValue(sel[0].get('kd_area'));
                    Ext.getCmp('id_txt_nama_area_dist_cp').setValue(sel[0].get('nama_area'));
                    menuAreaDist_cp.hide();
                }
            }
        },
        tbar: toolBarSearchDataAreaDist_cp,
        bbar: new Ext.PagingToolbar({
            pageSize: 10,
            store: storeDataAreaDist_cp,
            displayInfo: true
        })
    });

    /**
     *combo pelanggan dist menu declaration
     */
    var menuAreaDist_cp = new Ext.menu.Menu();
    menuAreaDist_cp.add(new Ext.Panel({
        title: 'Pilih Area',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridDataAreaDist_cp],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menuAreaDist_cp.hide();
                }
            }]
    }));

    /**
     * deklarasi twin combo box
     */
    Ext.ux.TwincomboAreaDist_cp = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            //load store grid
            storeDataAreaDist_cp.load();
            menuAreaDist_cp.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
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
    var comboAreaDist_cp = new Ext.ux.TwincomboAreaDist_cp({
        fieldLabel: 'Area <span class="asterix">*</span>',
        id: 'id_combo_area_dist_cp',
        store: storeDataAreaDist_cp,
        mode: 'local',
        valueField: 'kd_area_cp',
        displayField: 'kd_area',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        anchor: '95%',
        width: 200,
        hiddenName: 'combo_kd_area_cp',
        emptyText: 'Pilih Area'
    });

    /**
     * declaration of add form setting sales pelanggan
     */
    Ext.ns('collectionPelangganForm_cp');
    collectionPelangganForm_cp.Form = Ext.extend(Ext.form.FormPanel, {
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
            collectionPelangganForm_cp.Form.superclass.constructor.call(this, config);
        },
        initComponent: function() {

            // hard coded - cannot be changed from outsid
            var config = {
                defaultType: 'textfield',
                defaults: {labelSeparator: ''},
                monitorValid: true,
                autoScroll: false // ,buttonAlign:'right'
                ,
                items: [{
                        xtype: 'hidden',
                        id: 'id_txt_kd_area_lama_cp',
                        name: 'txt_kd_area_lama_cp'
                    }, {
                        xtype: 'textfield',
                        anchor: '95%',
                        fieldLabel: 'Kode Collector',
                        id: 'id_txt_kd_collector_cp',
                        name: 'txt_kd_collector_cp',
                        readOnly: true,
                        fieldClass: 'readonly-input'
                    }, {
                        xtype: 'textfield',
                        anchor: '95%',
                        fieldLabel: 'Nama Collector',
                        id: 'id_txt_nama_collector_cp',
                        name: 'txt_nama_collector_cp',
                        readOnly: true,
                        fieldClass: 'readonly-input'
                    }, comboAreaDist_cp, {
                        xtype: 'textfield',
                        anchor: '95%',
                        fieldLabel: 'Nama Area',
                        id: 'id_txt_nama_area_dist_cp',
                        name: 'txt_nama_area_dist_cp',
                        readOnly: true,
                        fieldClass: 'readonly-input'
                    }],
                buttons: [{
                        text: 'submit',
                        id: 'id_btn_submit_collection_cp',
                        formBind: true,
                        scope: this,
                        handler: this.submit
                    }, {
                        text: 'reset',
                        id: 'id_btn_reset_collection_cp',
                        scope: this,
                        handler: this.reset
                    }, {
                        text: 'close',
                        id: 'id_btn_close_collection_cp',
                        scope: this,
                        handler: function() {
                            windowSettingCollectionPelanggan_cp.hide();
                        }
                    }]
            }; // eo config object
            // apply config
            Ext.apply(this, Ext.apply(this.initialConfig, config));
            // call parent
            collectionPelangganForm_cp.Form.superclass.initComponent.apply(this, arguments);
        } // eo function initComponent  
        ,
        onRender: function() {

            // call parent
            collectionPelangganForm_cp.Form.superclass.onRender.apply(this, arguments);
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
            var text = Ext.getCmp('id_btn_submit_collection_cp').getText();
            if (text == 'update') {
                Ext.Msg.show({
                    title: 'Confirm',
                    msg: 'Are you sure update selected row ?',
                    buttons: Ext.Msg.YESNO,
                    fn: function(btn) {
                        if (btn == 'yes') {
                            updateCollectionPelanggan();
                        }
                    }
                });
            } else {
                insertCollectionPelanggan();
            }
        } // eo function submit
    });

    /**
     * register the form
     */
    Ext.reg('collectionPelangganForm_cp', collectionPelangganForm_cp.Form);

    /**
     * declaration of the window
     */
    var windowSettingCollectionPelanggan_cp = new Ext.Window({
        id: 'id_window_collection_pelanggan_cp',
        closeAction: 'hide',
        width: 450,
        height: 350,
        layout: 'fit',
        border: false,
        items: {
            id: 'id_collection_pelanggan_form_cp',
            xtype: 'collectionPelangganForm_cp'
        },
        onHide: function() {
            Ext.getCmp('id_collection_pelanggan_form_cp').getForm().reset();
        }
    });

    /**
     * declaration of the main panel of this form
     */
    Ext.ns('id_collection_pelanggan');
    var collectionPelangganPanel_cp = new Ext.FormPanel({
        id: 'id_collection_pelanggan',
        border: false,
        frame: true,
        autoScroll: true,
        bodyStyle: 'padding:5px;',
        items: [
            gridDataCollection_cp, gridDataCollectionArea_cp
                    //gridDataSales_cp, gridDataSalesPelanggan_cp
        ]
    });

    function generateUpdateForm(kdCollector, namaCollector, kdArea, namaPelanggan) {
        Ext.getCmp('id_btn_submit_collection_cp').setText('update');
        windowSettingCollectionPelanggan_cp.setTitle('Edit Data Form');
        Ext.getCmp('id_txt_kd_collector_cp').setValue(kdCollector);
        Ext.getCmp('id_txt_nama_collector_cp').setValue(namaCollector);
        Ext.getCmp('id_txt_nama_area_dist_cp').setValue(namaPelanggan);
        Ext.getCmp('id_combo_area_dist_cp').setValue(kdArea);
        Ext.getCmp('id_txt_kd_area_lama_cp').setValue(kdArea);
        windowSettingCollectionPelanggan_cp.show();
    }

    /**
     * processing form method declaration
     */
    function collectionPelangganProcessing_cp(command, kdCollector, kdArea, kdAreaLama, Msg) {
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
            url: '<?= site_url("collection_pelanggan_controller/finalProcessing") ?>',
            method: 'POST',
            waitMsg: 'Processing Data...',
            params: {
                cmd: command,
                combo_kd_area_cp: kdArea,
                txt_kd_collector_cp: kdCollector,
                txt_kd_area_lama_cp: kdAreaLama
            },
            callback: function(opt, success, responseObj) {
                var de = Ext.util.JSON.decode(responseObj.responseText);
                if (de.success == true) {
                    Ext.Msg.show({
                        title: 'Success',
                        msg: Msg,
                        modal: true,
                        icon: Ext.Msg.INFO,
                        buttons: Ext.Msg.OK,
                        fn: function(btn) {
                            if (btn == 'ok' && de.errMsg == 'Session Expired') {
                                window.location = '<?= site_url("auth/login") ?>';
                            }
                        }
                    });
                    box.hide();
                    if (command != 'delete') {
                        Ext.getCmp('id_collection_pelanggan_form_cp').getForm().reset();
                        windowSettingCollectionPelanggan_cp.hide();
                    }
                    storeDataCollectionArea_cp.reload();
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

    /**
     * declaration of insert method
     */
    function insertCollectionPelanggan() {
        var kdCollector = Ext.getCmp('id_txt_kd_collector_cp').getValue();
        var kdArea = Ext.getCmp('id_combo_area_dist_cp').getValue();
        var kdAreaLama = "";
        collectionPelangganProcessing_cp("save", kdCollector, kdArea, kdAreaLama, 'saved successfully');
    }
    /**
     * declaration of update method
     */
    function updateCollectionPelanggan() {
        var kdCollector = Ext.getCmp('id_txt_kd_collector_cp').getValue();
        var kdArea = Ext.getCmp('id_combo_area_dist_cp').getValue();
        var kdAreaLama = Ext.getCmp('id_txt_kd_area_lama_cp').getValue();
        collectionPelangganProcessing_cp("update", kdCollector, kdArea, kdAreaLama, 'updated successfully');
    }

    /**
     * declaration of delete method
     */
    function deleteCollectionPelanggan(kdCollector, kdArea) {
        Ext.Msg.show({
            title: 'Confirm',
            msg: 'Are you sure delete selected row ?',
            buttons: Ext.Msg.YESNO,
            fn: function(btn) {
                if (btn == 'yes') {
                    collectionPelangganProcessing_cp("delete", kdCollector, kdArea, "", 'deleted successfully');
                }
            }
        });
    }

</script>
