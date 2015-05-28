<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script type="text/javascript">

    // combobox cabang
    var strMSComboCabang = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_cabang', 'nama_cabang'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("master_sales/get_cabang") ?>',
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


    var mstSalesComboCabang = new Ext.form.ComboBox({
        fieldLabel: 'cabang <span class="asterix">*</span>',
        id: 'id_mst_sales_cbcabang',
        store: strMSComboCabang,
        valueField: 'kd_cabang',
        displayField: 'nama_cabang',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_cabang',
        emptyText: 'Pilih cabang',
        hideMode: 'Visibility'
    });

    // combobox area
    var strMSComboArea = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_area', 'nama_area'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("master_sales/get_area") ?>',
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

    var mstSalesComboArea = new Ext.form.ComboBox({
        fieldLabel: 'area <span class="asterix">*</span>',
        id: 'id_mst_sales_cbarea',
        store: strMSComboArea,
        valueField: 'kd_area',
        displayField: 'nama_area',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_area',
        emptyText: 'Pilih area',
        hideMode: 'Visibility'
    });

    /* START FORM */
    Ext.ns('MasterSalesForm');
    MasterSalesForm.Form = Ext.extend(Ext.form.FormPanel, {
        // defaults - can be changed from outside
        border: false,
        frame: true,
        labelWidth: 100,
        waitMsg: 'Loading...',
        url: '<?= site_url("master_sales/update_row") ?>',
        constructor: function(config) {
            config = config || {};
            config.listeners = config.listeners || {};
            Ext.applyIf(config.listeners, {
                actioncomplete: function() {
                    //TBA
                },
                actionfailed: function() {
                    //TBA
                }
            });
            MasterSalesForm.Form.superclass.constructor.call(this, config);
        },
        initComponent: function() {

            // hard coded - cannot be changed from outsid
            var config = {
                defaultType: 'textfield',
                defaults: {labelSeparator: ''},
                monitorValid: true,
                autoScroll: false,
                items: [
                    {
                        xtype: 'hidden',
                        name: 'kd_sales'
                    }, {
                        xtype: 'textfield',
                        fieldLabel: 'Kd Collection ',
                        name: 'kd_sales',
                        allowBlank: true,
                        id: 'id_mst_sales_kd_sales',
                        maxLength: 15,
                        anchor: '90%',
                        fieldClass:'readonly-input',
                        readOnly:true
                    }, {
                        type: 'textfield',
                        fieldLabel: 'Nama sales <span class="asterix">*</span>',
                        name: 'nama_sales',
                        allowBlank: false,
                        id: 'id_mst_sales_nama_sales',
                        anchor: '90%',
                        maxLength: 40
                    },
                    mstSalesComboCabang,
                    {
                        xtype: 'textarea',
                        fieldLabel: 'Alamat <span class="asterix">*</span>',
                        name: 'alamat',
                        allowBlank: false,
                        id: 'id_mst_sales_alamat',
                        maxLength: 255,
                        anchor: '90%'
                    }, {
                        xtype: 'textfield',
                        fieldLabel: 'Email ',
                        name: 'email',
                        allowBlank: true,
                        id: 'id_mst_sales_email',
                        maxLength: 15,
                        anchor: '90%'
                    }, {
                        xtype: 'textfield',
                        fieldLabel: 'Pin BB ',
                        name: 'pin_bb',
                        allowBlank: true,
                        id: 'id_mst_sales_pin_bb',
                        maxLength: 15,
                        anchor: '90%'
                    }, {
                        xtype: 'textfield',
                        fieldLabel: 'No Telepon <span class="asterix">*</span>',
                        name: 'no_telp',
                        allowBlank: false,
                        id: 'id_mst_sales_notel',
                        maxLength: 15,
                        anchor: '90%'
                    }, {
                        xtype: 'textfield',
                        fieldLabel: 'No Telepon (Alternatif) ',
                        name: 'no_telp2',
                        allowBlank: true,
                        id: 'id_mst_sales_notel2',
                        maxLength: 15,
                        anchor: '90%'
                    }, {
                        xtype: 'radiogroup',
                        cls: 'x-check-group-alt',
                        fieldLabel: 'Status Aktif <span class="asterix">*</span>',
                        name: 'status',
                        anchor: '90%',
                        allowBlank: false,
                        items: [{
                                boxLabel: 'Ya',
                                name: 'status',
                                inputValue: '1',
                                id: 'id_mst_sales_statY'
                            }, {
                                boxLabel: 'Tidak',
                                name: 'status',
                                inputValue: '0',
                                id: 'id_mst_sales_statT'
                            }]
                    }
                ],
                buttons: [
                    {
                        text: 'Submit',
                        id: 'id_mst_sales_submit',
                        formBind: true,
                        scope: this,
                        handler: this.submit
                    }, {
                        text: 'Reset',
                        id: 'id_mst_sales_reset',
                        scope: this,
                        handler: this.reset
                    }, {
                        text: 'Close',
                        id: 'id_mst_sales_close',
                        scope: this,
                        handler: function() {
                            Mstsales_WinAdd.hide();
                        }
                    }
                ]
            }; // eo config object
            // apply config
            Ext.apply(this, Ext.apply(this.initialConfig, config));

            // call parent
            MasterSalesForm.Form.superclass.initComponent.apply(this, arguments);

        }, // eo function initComponent
        onRender: function() {

            // call parent
            MasterSalesForm.Form.superclass.onRender.apply(this, arguments);

            // set wait message target
            this.getForm().waitMsgTarget = this.getEl();

            // loads form after initial layout
            // this.on('afterlayout', this.onLoadClick, this, {single:true});

        }, // eo function onRender
        reset: function() {
            this.getForm().reset();
        },
        submit: function() {
            var text = Ext.getCmp('id_mst_sales_submit').getText();
            if (text == 'Update') {
                Ext.Msg.show({
                    title: 'Confirm',
                    msg: 'Are you sure update selected row ?',
                    buttons: Ext.Msg.YESNO,
                    fn: function(btn) {
                        if (btn == 'yes') {
                            Ext.getCmp('id_mst_sales_formaddsales').getForm().submit({
                                url: Ext.getCmp('id_mst_sales_formaddsales').url,
                                scope: this,
                                success: Ext.getCmp('id_mst_sales_formaddsales').onSuccess,
                                failure: Ext.getCmp('id_mst_sales_formaddsales').onFailure,
                                params: {
                                    cmd: 'update'
                                },
                                waitMsg: 'Updating Data...'
                            });
                        }
                    }
                })
            } else {
                Ext.getCmp('id_mst_sales_formaddsales').getForm().submit({
                    url: Ext.getCmp('id_mst_sales_formaddsales').url,
                    scope: this,
                    success: Ext.getCmp('id_mst_sales_formaddsales').onSuccess,
                    failure: Ext.getCmp('id_mst_sales_formaddsales').onFailure,
                    params: {
                        cmd: 'save'
                    },
                    waitMsg: 'Saving Data...'
                });
            }
        }, // eo function submit
        onSuccess: function(form, action) {
            Ext.Msg.show({
                title: 'Success',
                msg: 'Form submitted successfully',
                modal: true,
                icon: Ext.Msg.INFO,
                buttons: Ext.Msg.OK
            });
            strMasterSales.reload();
            Ext.getCmp('id_mst_sales_formaddsales').getForm().reset();
            Mstsales_WinAdd.hide();
        }, // eo function onSuccess
        onFailure: function(form, action) {
            var fe = Ext.util.JSON.decode(action.response.responseText);
            this.showError(fe.errMsg || '');


        }, // eo function onFailure
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
    }); // eo extend
    // register xtype
    Ext.reg('formaddsales', MasterSalesForm.Form);

    var Mstsales_WinAdd = new Ext.Window({
        id: 'id_Mstsales_WinAdd',
        closeAction: 'hide',
        width: 450,
        height: 450,
        layout: 'fit',
        border: false,
        items: {
            id: 'id_mst_sales_formaddsales',
            xtype: 'formaddsales'
        },
        onHide: function() {
            Ext.getCmp('id_mst_sales_formaddsales').getForm().reset();
        }
    });

    /* START GRID */

    // data store
    var strMasterSales = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'kd_sales',
                'nama_sales',
                'alamat',
                'no_telp',
                'no_telp2',
                'kd_cabang',
                'nama_cabang',
                'kd_area',
                'nama_area',
                'status',
                'email',
                'pin_bb'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("master_sales/get_rows") ?>',
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
    strMasterSales.load();

    // search field
    var searchsales = new Ext.app.SearchField({
        store: strMasterSales,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        id: 'idsearchsales'
    });

    // top toolbar
    var tbsales = new Ext.Toolbar({
        items: [{
                text: 'Add',
                icon: BASE_ICONS + 'add.png',
                onClick: function() {
                    Ext.getCmp('id_mst_sales_reset').show();
                    Ext.getCmp('id_mst_sales_submit').setText('Submit');
                    Ext.getCmp('id_mst_sales_kd_sales').setValue('');
                    Mstsales_WinAdd.setTitle('Add Form');
                    Mstsales_WinAdd.show();
                }
            }, '-', searchsales]
    });

    // checkbox grid
    var cbGrid = new Ext.grid.CheckboxSelectionModel();

    // row actions
    var actionsales = new Ext.ux.grid.RowActions({
        header: 'Edit',
        autoWidth: false,
        width: 30,
        actions: [{iconCls: 'icon-edit-record', qtip: 'Edit'}],
        widthIntercept: Ext.isSafari ? 4 : 2
    });

    actionsales.on('action', function(grid, record, action, row, col) {
        var kd_sales = record.get('kd_sales');
        switch (action) {
            case 'icon-edit-record':
                editsales(kd_sales);
                break;
            case 'icon-delete-record':
                deletesales();
                break;

        }
    });

    var multisearchsales = new Ext.ux.grid.Search({
        iconCls: 'icon-zoom'
                //,readonlyIndexes:['note']
                //,disableIndexes:['pctChange']
        , minChars: 3
        , autoFocus: true
        , width: 250
    });

    // grid

    var sales = new Ext.grid.EditorGridPanel({
        id: 'snc_master_sales',
        frame: true,
        border: true,
        stripeRows: true,
        sm: cbGrid,
        store: strMasterSales,
        //closable:true,
        loadMask: true,
        style: 'margin:0 auto;',
        // height: 450,
        columns: [actionsales,
            {
                header: "Kode sales",
                dataIndex: 'kd_sales',
                sortable: true,
                width: 100
            }, {
                header: "Nama sales",
                dataIndex: 'nama_sales',
                sortable: true,
                width: 200
            }, {
                header: "Alamat",
                dataIndex: 'alamat',
                sortable: true,
                width: 300
            }, {
                header: "Email",
                dataIndex: 'email',
                sortable: true,
                width: 100
            }, {
                header: "Pin BB",
                dataIndex: 'pin_bb',
                sortable: true,
                width: 100
            }, {
                header: "No. Telp",
                dataIndex: 'no_telp',
                sortable: true,
                width: 100
            }, {
                header: "No. Telp 2",
                dataIndex: 'no_telp2',
                sortable: true,
                width: 100
            }, {
                header: "Kd Cabang",
                dataIndex: 'kd_cabang',
                sortable: true,
                hidden: true,
                width: 50
            }, {
                header: "Cabang",
                dataIndex: 'nama_cabang',
                sortable: true,
                width: 75
            }, {
                header: "Kd Area",
                dataIndex: 'kd_area',
                sortable: true,
                hidden: true,
                width: 75
            }, {
                header: "Area",
                dataIndex: 'nama_area',
                sortable: true,
                width: 75
            }, {
                header: "Status",
                dataIndex: 'status',
                sortable: true,
                width: 50
            }
        ],
        plugins: [actionsales, multisearchsales],
        listeners: {
            'rowdblclick': function() {
                var sm = sales.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    editsales(sel[0].get('kd_sales'));
                }
            }
        },
        tbar: tbsales,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strMasterSales,
            displayInfo: true
        })
    });

    function editsales(kd_sales) {
        strMSComboArea.load();
        strMSComboCabang.load();
//        Ext.getCmp('id_mst_sales_cbcabang').setValue(kd_cabang);
//        Ext.getCmp('id_mst_sales_cbarea').setValue(kd_area);
        Ext.getCmp('id_mst_sales_reset').hide();
        Ext.getCmp('id_mst_sales_submit').setText('Update');
        Ext.getCmp('id_mst_sales_kd_sales').setValue(kd_sales);
        Mstsales_WinAdd.setTitle('Edit Form');
        Ext.getCmp('id_mst_sales_formaddsales').getForm().load({
            url: '<?= site_url("master_sales/get_row") ?>',
            params: {
                kd_sales: kd_sales,
                cmd: 'get'
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
            }
        });
        Mstsales_WinAdd.show();
    }

    function deletesales() {
        var sm = sales.getSelectionModel();
        var sel = sm.getSelections();
        if (sel.length > 0) {
            Ext.Msg.show({
                title: 'Confirm',
                msg: 'Are you sure delete selected row ?',
                buttons: Ext.Msg.YESNO,
                fn: function(btn) {
                    if (btn == 'yes') {

                        var data = '';
                        for (i = 0; i < sel.length; i++) {
                            data = data + sel[i].get('kd_sales') + ';';
                        }

                        Ext.Ajax.request({
                            url: '<?= site_url("master_sales/delete_rows") ?>',
                            method: 'POST',
                            params: {
                                postdata: data
                            },
                            callback: function(opt, success, responseObj) {
                                var de = Ext.util.JSON.decode(responseObj.responseText);
                                if (de.success == true) {
                                    strMasterSales.reload();
                                    strMasterSales.load({
                                        params: {
                                            start: STARTPAGE,
                                            limit: ENDPAGE
                                        }
                                    });
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
        } else {
            Ext.Msg.show({
                title: 'Info',
                msg: 'Please selected row',
                modal: true,
                icon: Ext.Msg.ERROR,
                buttons: Ext.Msg.OK
            });
        }

    }
</script>
