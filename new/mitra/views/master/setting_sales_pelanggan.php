<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">

    /**
     * deklarasi store untuk grid data sales/ grid pertama
     */
    var storeDataSales_ssp = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_sales', 'kd_cabang', 'nama_sales', 'alamat', 'no_telp', 'no_telp2', 'kd_area', 'status'], root: 'data', totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("setting_sales_pelanggan_controller/finalGetDataSales") ?>',
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

    storeDataSales_ssp.load();

    /**
     *deklarasi store untuk data sales area /grid kedua 
     */
    var storeDataSalesPelanggan_ssp = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_sales', 'kd_area', 'nama_sales', 'nama_area', 'created_by', 'created_date', 'updated_by', 'updated_date'], root: 'data', totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("setting_sales_pelanggan_controller/finalGetDataSalesPelanggan") ?>',
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

    storeDataSalesPelanggan_ssp.on('load', function() {
        storeDataSalesPelanggan_ssp.setBaseParam('txt_kd_sales_ssp', Ext.getCmp('id_txt_kd_sales_ssp').getValue());
    });

    /**
     *deklarsi store data pelanggan dist 
     */
    var storeDataAreaDist_ssp = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_area', 'nama_area'], root: 'data', totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("setting_sales_pelanggan_controller/finalGetDataAreaDist") ?>',
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
     * deklarsi selection model utuk grid data sales
     */
    var smGridDataPelanggan_ssp = new Ext.grid.CheckboxSelectionModel();

    /**
     * deklarasi selection model untuk grid data sales pelanggan
     */
    var smGridDataSalesPelanggan_ssp = new Ext.grid.CheckboxSelectionModel();

    /**
     *deklarasi selection model untuk grid data pelanggan dist 
     */
    var smGridDataAreaDist_spp = new Ext.grid.CheckboxSelectionModel();

    /**
     * deklarasi search field untuk grid data sales
     */
    var searchGridDataSales_ssp = new Ext.app.SearchField({
        store: storeDataSales_ssp,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        id: 'search_field_data_sales_ssp'
    });

    /**
     * deklarasi search field untuk grid data sales pelanggan
     */
    var searchGridDataSalesPelanggan_ssp = new Ext.app.SearchField({
        store: storeDataSalesPelanggan_ssp,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        emptyText :'Nama Area',
        id: 'search_field_data_sales_pelanggan_ssp'
    });

    /**
     * deklarasi search field untuk grid data pelanggan dist
     */
    var searchGridDataPelangganDist_ssp = new Ext.app.SearchField({
        store: storeDataAreaDist_ssp,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        id: 'search_field_data_pelanggan_dist_ssp'
    });

    /**
     * deklarasi top toolbar grid data sales
     */
    var toolBarSearchDataSales_ssp = new Ext.Toolbar({
        items: [searchGridDataSales_ssp]
    });
    /**
     * deklarasi top toolbar grid data sales pelanggan
     */
    var toolBarSearchDataSalesPelanggan_ssp = new Ext.Toolbar({
        items: [{
                text: 'Add',
                icon: BASE_ICONS + 'add.png',
                onClick: function() {
                    var kdSales = Ext.getCmp('id_txt_kd_sales_ssp').getValue();
                    if (kdSales == '' || kdSales == null) {
                        Ext.Msg.show({
                            title: 'Warning',
                            msg: 'Silahkan Klik Data Sales Terlebih Dahulu',
                            modal: true,
                            icon: Ext.Msg.WARNING,
                            buttons: Ext.Msg.OK
                        });
                        return;
                    } else {
                        Ext.getCmp('btn_submit_ssp').setText('save');
                        Ext.getCmp('id_txt_nama_area_dist_ssp').setValue('');
                        Ext.getCmp('id_txt_nama_area_dist_ssp').reset();
                        Ext.getCmp('id_combo_area_dist_ssp').setValue('');
                        windowSettingSalesPelanggan_ssp.show();
                    }
                }
            }, '-', searchGridDataSalesPelanggan_ssp]
    });

    /**
     * deklarasi top toolbar grid pelanggan dist 
     */
    var toolBarSearchDataAreaDist_ssp = new Ext.Toolbar({
        items: [searchGridDataPelangganDist_ssp]
    });

    /**
     * deklarasi grid sales pelanggan row action
     */
    var gridSalesPelangganEdit_ssp = new Ext.ux.grid.RowActions({
        header: 'Edit',
        autoWidth: false,
        locked: true,
        width: 50,
        actions: [{iconCls: 'icon-edit-record', qtip: 'Edit'}],
        widthIntercept: Ext.isSafari ? 4 : 2
    });
    var gridSalesPelangganDelete_ssp = new Ext.ux.grid.RowActions({
        header: 'Delete',
        autoWidth: false,
        locked: true,
        width: 50,
        actions: [{iconCls: 'icon-delete-record', qtip: 'Delete'}],
        widthIntercept: Ext.isSafari ? 4 : 2
    });

    gridSalesPelangganEdit_ssp.on('action', function(grid, record, action, row, col) {
        var kdArea = record.get('kd_area');
        var kdSales = record.get('kd_sales');
        var namaArea = record.get('nama_area');
        var namaSales = record.get('nama_sales');
        switch (action) {
            case 'icon-edit-record':
                generateUpdateForm_ssp(kdArea, namaArea, kdSales, namaSales);
                break;
            case 'icon-delete-record':
                deleteDataSalesPelanggan_ssp(kdSales, kdArea);
                break;
        }
    });

    /**
     * deklarasi grid data sales / grid pertama
     */
    var gridDataSales_ssp = new Ext.grid.GridPanel({
        frame: true,
        border: true,
        stripeRows: true,
        id: 'id_grid_data_sales_ssp',
        store: storeDataSales_ssp,
        sm: smGridDataPelanggan_ssp,
        loadMask: true,
        title: 'Data Sales',
        height: 250,
        columns: [{
                header: "Kode Sales",
                dataIndex: 'kd_sales',
                sortable: true,
                width: 100
            }, {
                header: "Kode Cabang",
                dataIndex: 'kd_cabang',
                sortable: true,
                width: 80
            }, {
                header: "Nama Sales",
                dataIndex: 'nama_sales',
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
        listeners: {
            'rowclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                storeDataSalesPelanggan_ssp.reload({
                    params: {
                        txt_kd_sales_ssp: sel[0].get('kd_sales')
                    }
                });
                Ext.getCmp('id_txt_kd_sales_ssp').setValue(sel[0].get('kd_sales'));
                Ext.getCmp('id_txt_nama_sales_ssp').setValue(sel[0].get('nama_sales'));
            }
        },
        tbar: toolBarSearchDataSales_ssp,
        bbar: new Ext.PagingToolbar({
            pageSize: 10,
            store: storeDataSales_ssp,
            displayInfo: true
        })
    });

    /**
     * deklarasi grid data sales pelanggan / grid kedua
     */
    var gridDataSalesPelanggan_ssp = new Ext.grid.GridPanel({
        frame: true,
        border: true,
        stripeRows: true,
        store: storeDataSalesPelanggan_ssp,
        sm: smGridDataSalesPelanggan_ssp,
        loadMask: true,
        title: 'Data Sales Area',
        height: 250,
        columns: [//gridSalesPelangganEdit_ssp, 
            gridSalesPelangganDelete_ssp,
            {
                header: "Kode Sales",
                dataIndex: 'kd_sales',
                sortable: true,
                width: 150
            }, {
                header: "Nama Sales",
                dataIndex: 'nama_sales',
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
        tbar: toolBarSearchDataSalesPelanggan_ssp,
        plugins: [gridSalesPelangganEdit_ssp, gridSalesPelangganDelete_ssp],
        bbar: new Ext.PagingToolbar({
            pageSize: 10,
            store: storeDataSalesPelanggan_ssp,
            displayInfo: true
        })
    });

    /**
     * deklarasi grid data pelanggan dist
     */
    var gridDataAreaDist_ssp = new Ext.grid.GridPanel({
        frame: true,
        border: true,
        stripeRows: true,
        store: storeDataAreaDist_ssp,
        sm: smGridDataAreaDist_spp,
        id: 'id_grid_area_dist_ssp',
        loadMask: true,
        height: 250,
        columns: [{
                header: "Kode Area",
                dataIndex: 'kd_area',
                sortable: true,
                width: 80
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
                    Ext.getCmp('id_combo_area_dist_ssp').setValue(sel[0].get('kd_area'));
                    Ext.getCmp('id_txt_nama_area_dist_ssp').setValue(sel[0].get('nama_area'));
                    menuAreaDist_ssp.hide();
                }
            }
        },
        tbar: toolBarSearchDataAreaDist_ssp,
        bbar: new Ext.PagingToolbar({
            pageSize: 10,
            store: storeDataAreaDist_ssp,
            displayInfo: true
        })
    });

    /**
     * deklarasi  menu combo pelanggan dist
     */
    var menuAreaDist_ssp = new Ext.menu.Menu();
    menuAreaDist_ssp.add(new Ext.Panel({
        title: 'Pilih Area',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridDataAreaDist_ssp],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menuAreaDist_ssp.hide();
                }
            }]
    }));

    /**
     * deklarasi twin combo box
     */
    Ext.ux.TwincomboAreaDist_ssp = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            //load store grid
            storeDataAreaDist_ssp.load();
            menuAreaDist_ssp.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
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
    var comboAreaDist_ssp = new Ext.ux.TwincomboAreaDist_ssp({
        fieldLabel: 'Area <span class="asterix">*</span>',
        id: 'id_combo_area_dist_ssp',
        store: storeDataAreaDist_ssp,
        mode: 'local',
        valueField: 'kd_area_ssp',
        displayField: 'kd_area',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        anchor: '95%',
        width: 200,
        hiddenName: 'combo_kd_area_ssp',
        emptyText: 'Pilih Area'
    });
    /**
     * declaration of add form setting sales pelanggan
     */
    Ext.ns('settingSalesPelangganForm');
    settingSalesPelangganForm.Form = Ext.extend(Ext.form.FormPanel, {
        // defaults - can be changed from outside
        border: false,
        frame: true,
        labelWidth: 130,
        url: '<?= site_url("setting_sales_pelanggan_controller/finalProcessing") ?>',
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
            settingSalesPelangganForm.Form.superclass.constructor.call(this, config);
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
                        id: 'id_txt_kd_pelanggan_lama_ssp',
                        name: 'txt_kd_pelanggan_lama_ssp'
                    }, {
                        xtype: 'textfield',
                        anchor: '95%',
                        fieldLabel: 'Kode Sales',
                        id: 'id_txt_kd_sales_ssp',
                        name: 'txt_kd_sales_ssp',
                        readOnly: true,
                        fieldClass: 'readonly-input'
                    }, {
                        xtype: 'textfield',
                        anchor: '95%',
                        fieldLabel: 'Nama Sales',
                        id: 'id_txt_nama_sales_ssp',
                        name: 'txt_nama_sales_ssp',
                        readOnly: true,
                        fieldClass: 'readonly-input'
                    }, comboAreaDist_ssp, {
                        xtype: 'textfield',
                        anchor: '95%',
                        fieldLabel: 'Nama Area',
                        id: 'id_txt_nama_area_dist_ssp',
                        name: 'txt_nama_area_dist_ssp',
                        readOnly: true,
                        fieldClass: 'readonly-input'
                    }],
                buttons: [{
                        text: 'submit',
                        id: 'btn_submit_ssp',
                        formBind: true,
                        scope: this,
                        handler: this.submit
                    }, {
                        text: 'reset',
                        id: 'btn_reset_ssp',
                        scope: this,
                        handler: this.reset
                    }, {
                        text: 'close',
                        id: 'btn_close_ssp',
                        scope: this,
                        handler: function() {
                            windowSettingSalesPelanggan_ssp.hide();
                        }
                    }]
            }; // eo config object
            // apply config
            Ext.apply(this, Ext.apply(this.initialConfig, config));
            // call parent
            settingSalesPelangganForm.Form.superclass.initComponent.apply(this, arguments);
        } // eo function initComponent  
        ,
        onRender: function() {

            // call parent
            settingSalesPelangganForm.Form.superclass.onRender.apply(this, arguments);
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
            var text = Ext.getCmp('btn_submit_ssp').getText();
            if (text == 'update') {
                Ext.Msg.show({
                    title: 'Confirm',
                    msg: 'Are you sure update selected row ?',
                    buttons: Ext.Msg.YESNO,
                    fn: function(btn) {
                        if (btn == 'yes') {
                            updateDataSalesPelanggan_ssp();
                        }
                    }
                });
            } else {
                this.getForm().submit({
                    url: this.url,
                    scope: this,
                    success: this.onSuccess,
                    failure: this.onFailure,
                    params: {
                        cmd: 'save'
                    },
                    waitMsg: 'Saving Data...',
                });
            }
        } // eo function submit
        ,
        onSuccess: function(form, action) {
            Ext.Msg.show({
                title: 'Success',
                msg: 'Form submitted successfully',
                modal: true,
                icon: Ext.Msg.INFO,
                buttons: Ext.Msg.OK,
            });
            storeDataSalesPelanggan_ssp.load();
            storeDataSalesPelanggan_ssp.reload();
            windowSettingSalesPelanggan_ssp.hide();
        } // eo function onSuccess
        ,
        onFailure: function(form, action) {
            var fe = Ext.util.JSON.decode(action.response.responseText);
            this.showError(fe.errMsg || '');
        } // eo function onFailure
        ,
        showError: function(msg, title) {
            title = title || 'Error';
            Ext.Msg.show({
                title: title,
                msg: msg,
                modal: true,
                icon: Ext.Msg.ERROR,
                buttons: Ext.Msg.OK,
                fn: function(btn) {
                    if (btn == 'ok' && msg == 'Session Expired') {
                        window.location = '<?= site_url("auth/login") ?>';
                    }
                }
            });
        }
    });

    /**
     * register the form
     */
    Ext.reg('settingSalesPelangganForm', settingSalesPelangganForm.Form);

    /**
     * declaration of the window
     */
    var windowSettingSalesPelanggan_ssp = new Ext.Window({
        id: 'id_window_setting_sales_pelanggan_ssp',
        closeAction: 'hide',
        width: 450,
        height: 350,
        layout: 'fit',
        border: false,
        items: {
            id: 'id_setting_sales_pelanggan_form',
            xtype: 'settingSalesPelangganForm'
        },
        onHide: function() {
            Ext.getCmp('id_setting_sales_pelanggan_form').getForm().reset();
        }
    });

    /**
     * declaration of the main panel of this form
     */
    Ext.ns('id_setting_sales_pelanggan');
    var settingSalesPelanggan_ssp = new Ext.FormPanel({
        id: 'id_setting_sales_pelanggan',
        border: false,
        frame: true,
        autoScroll: true,
        bodyStyle: 'padding:5px;',
        items: [
            gridDataSales_ssp, gridDataSalesPelanggan_ssp
        ]
    });

    /**
     * generate update form
     */
    function generateUpdateForm_ssp(kdArea, namaArea, kdSales, namaSales) {
        Ext.getCmp('btn_submit_ssp').setText('update');
        windowSettingSalesPelanggan_ssp.setTitle('Edit Data Form');
        Ext.getCmp('id_txt_kd_sales_ssp').setValue(kdSales);
        Ext.getCmp('id_txt_nama_sales_ssp').setValue(namaSales);
        Ext.getCmp('id_txt_nama_area_dist_ssp').setValue(namaArea);
        Ext.getCmp('id_combo_area_dist_ssp').setValue(kdArea);
        //Ext.getCmp('id_txt_kd_pelanggan_lama_ssp').setValue(kdArea);
        windowSettingSalesPelanggan_ssp.show();
    }

    /**
     * update form method declaration
     */

    function updateDataSalesPelanggan_ssp() {
        Ext.getCmp('id_setting_sales_pelanggan_form').getForm().submit({
            url: '<?= site_url("setting_sales_pelanggan_controller/finalProcessing") ?>',
            success: function(form, action) {
                Ext.Msg.show({
                    title: 'Success',
                    msg: 'Form submitted successfully',
                    modal: true,
                    icon: Ext.Msg.INFO,
                    buttons: Ext.Msg.OK
                });
                storeDataSalesPelanggan_ssp.load();
                storeDataSalesPelanggan_ssp.reload();
                windowSettingSalesPelanggan_ssp.hide();
            },
            failure: function(form, action) {
                var de = Ext.util.JSON.decode(action.response.responseText);
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
            },
            params: {
                cmd: 'update'
            },
            waitMsg: 'Updating Data...'
        });
    }

    /**
     * delete form method declaration
     */
    function deleteDataSalesPelanggan_ssp(kdSales, kdArea) {
        Ext.Msg.show({
            title: 'Confirm',
            msg: 'Are you sure delete selected row ?',
            buttons: Ext.Msg.YESNO,
            fn: function(btn) {
                if (btn == 'yes') {
                    Ext.Ajax.request({
                        url: '<?= site_url("setting_sales_pelanggan_controller/finalProcessing") ?>',
                        method: 'POST',
                        params: {
                            cmd: 'delete',
                            combo_kd_area_ssp: kdArea,
                            txt_kd_sales_ssp: kdSales,
                        },
                        callback: function(opt, success, responseObj) {
                            var de = Ext.util.JSON.decode(responseObj.responseText);
                            if (de.success == true) {
                                storeDataSalesPelanggan_ssp.reload();
                            } else {
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
            }
        });
    }

</script>
