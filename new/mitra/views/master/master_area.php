<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script type="text/javascript">
    //store grid pelanggan
    var storeMasterPelanggan_ma = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'kd_pelanggan',
                'nama_pelanggan',
                'tipe',
                'nama_tipe',
                'tipe_pelanggan',
                'alamat_kirim',
                'alamat_tagih',
                'is_pkp',
                'pkp',
                'npwp',
                'alamat_npwp',
                'kd_propinsi',
                'nama_propinsi',
                'nama_kota',
                'nama_kecamatan',
                'nama_kalurahan',
                'nama_cabang',
                'kodepos',
                'no_telp',
                'no_fax',
                'email',
                'nama_pic',
                'no_telp_pic',
                'aktif',
                'top_dist',
                'limit_dist',
                'nama_area',
                'status'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("master_area/finalGetCustomers") ?>',
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

    // combobox propinsi
    var strMACbPropinsi = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_propinsi', 'nama_propinsi'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("wilayah_kota/get_propinsi") ?>',
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

    var maCbPropinsi = new Ext.form.ComboBox({
        fieldLabel: 'Propinsi <span class="asterix">*</span>',
        id: 'id_ma_cbpropinsi',
        store: strMACbPropinsi,
        valueField: 'kd_propinsi',
        displayField: 'nama_propinsi',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_propinsi',
        emptyText: 'Pilih propinsi',
        hideMode: 'Visibility'
    });

    /* START FORM */
    Ext.ns('MasterAreaForm');
    MasterAreaForm.Form = Ext.extend(Ext.form.FormPanel, {
        // defaults - can be changed from outside
        border: false,
        frame: true,
        labelWidth: 100,
        waitMsg: 'Loading...',
        url: '<?= site_url("master_area/update_row") ?>',
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
            MasterAreaForm.Form.superclass.constructor.call(this, config);
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
                        name: 'kd_area'
                    }, {
                        type: 'textfield',
                        fieldLabel: 'Nama area <span class="asterix">*</span>',
                        name: 'nama_area',
                        allowBlank: false,
                        id: 'id_ma_nama_area',
                        anchor: '90%',
                        maxLength: 40,
                        style: 'text-transform: uppercase'
                    },
                    maCbPropinsi,
                    {
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
                                id: 'id_ma_statY'
                            }, {
                                boxLabel: 'Tidak',
                                name: 'status',
                                inputValue: '0',
                                id: 'id_ma_statT'
                            }]
                    }],
                buttons: [{
                        text: 'Submit',
                        id: 'id_ma_submit',
                        formBind: true,
                        scope: this,
                        handler: this.submit
                    }, {
                        text: 'Reset',
                        id: 'id_ma_reset',
                        scope: this,
                        handler: this.reset
                    }, {
                        text: 'Close',
                        id: 'id_ma_close',
                        scope: this,
                        handler: function() {
                            MstArea_WinAdd.hide();
                        }
                    }]
            }; // eo config object
            // apply config
            Ext.apply(this, Ext.apply(this.initialConfig, config));

            // call parent
            MasterAreaForm.Form.superclass.initComponent.apply(this, arguments);

        }, // eo function initComponent
        onRender: function() {

            // call parent
            MasterAreaForm.Form.superclass.onRender.apply(this, arguments);

            // set wait message target
            this.getForm().waitMsgTarget = this.getEl();

            // loads form after initial layout
            // this.on('afterlayout', this.onLoadClick, this, {single:true});

        }, // eo function onRender
        reset: function() {
            this.getForm().reset();
        },
        submit: function() {
            var text = Ext.getCmp('id_ma_submit').getText();
            if (text == 'Update') {
                Ext.Msg.show({
                    title: 'Confirm',
                    msg: 'Are you sure update selected row ?',
                    buttons: Ext.Msg.YESNO,
                    fn: function(btn) {
                        if (btn == 'yes') {
                            Ext.getCmp('id_ma_formaddarea').getForm().submit({
                                url: Ext.getCmp('id_ma_formaddarea').url,
                                scope: this,
                                success: Ext.getCmp('id_ma_formaddarea').onSuccess,
                                failure: Ext.getCmp('id_ma_formaddarea').onFailure,
                                params: {
                                    cmd: 'save'
                                },
                                waitMsg: 'Saving Data...'
                            });
                        }
                    }
                })
            } else {
                Ext.getCmp('id_ma_formaddarea').getForm().submit({
                    url: Ext.getCmp('id_ma_formaddarea').url,
                    scope: this,
                    success: Ext.getCmp('id_ma_formaddarea').onSuccess,
                    failure: Ext.getCmp('id_ma_formaddarea').onFailure,
                    params: {
                        cmd: 'save'
                    },
                    waitMsg: 'Saving Data...'
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
                buttons: Ext.Msg.OK
            });


            strarea.reload();
            Ext.getCmp('id_ma_formaddarea').getForm().reset();
            MstArea_WinAdd.hide();
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
    }); // eo extend
    // register xtype
    Ext.reg('formaddarea', MasterAreaForm.Form);

    var MstArea_WinAdd = new Ext.Window({
        id: 'id_MstArea_WinAdd',
        closeAction: 'hide',
        width: 450,
        height: 350,
        layout: 'fit',
        border: false,
        items: {
            id: 'id_ma_formaddarea',
            xtype: 'formaddarea'
        },
        onHide: function() {
            Ext.getCmp('id_ma_formaddarea').getForm().reset();
        }
    });

    /* START GRID */

    // data store
    var strarea = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'kd_perusahaan',
                'kd_cabang',
                'kd_area',
                'nama_area',
                'kd_propinsi',
                'nama_propinsi',
                'status'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("master_area/get_rows") ?>',
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
    strarea.load();

    // search field
    var searcharea = new Ext.app.SearchField({
        store: strarea,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        id: 'idsearcharea'
    });

    // top toolbar
    var tbarea = new Ext.Toolbar({
        items: [{
                text: 'Add',
                icon: BASE_ICONS + 'add.png',
                onClick: function() {
                    Ext.getCmp('id_ma_reset').show();
                    Ext.getCmp('id_ma_submit').setText('Submit');
                    MstArea_WinAdd.setTitle('Add Form');
                    MstArea_WinAdd.show();
                }
            }, '-', searcharea]
    });

    // checkbox grid
    var cbGrid = new Ext.grid.CheckboxSelectionModel();

    // row actions
    var actionarea = new Ext.ux.grid.RowActions({
        header: 'Edit',
        autoWidth: false,
        width: 30,
        actions: [{iconCls: 'icon-edit-record', qtip: 'Edit'}],
        widthIntercept: Ext.isSafari ? 4 : 2
    });

    actionarea.on('action', function(grid, record, action, row, col) {
        var kd_area = record.get('kd_area');
        switch (action) {
            case 'icon-edit-record':
                editarea(kd_area);
                break;
            case 'icon-delete-record':
                deletearea();
                break;

        }
    });

    var multisearcharea = new Ext.ux.grid.Search({
        iconCls: 'icon-zoom'
                //,readonlyIndexes:['note']
                //,disableIndexes:['pctChange']
        , minChars: 3
        , autoFocus: true
        , width: 250
    });

    // grid

    var area = new Ext.grid.EditorGridPanel({
        frame: true,
        border: true,
        stripeRows: true,
        sm: cbGrid,
        store: strarea,
        //closable:true,
        loadMask: true,
        style: 'margin:0 auto;',
        height: 250,
        columns: [actionarea,
            {
                header: "Kode area",
                dataIndex: 'kd_area',
                sortable: true,
                width: 150
            }, {
                header: "Nama area",
                dataIndex: 'nama_area',
                sortable: true,
                width: 300
            }, {
                header: "Propinsi",
                dataIndex: 'nama_propinsi',
                sortable: true,
                width: 300
            }, {
                header: "Status",
                dataIndex: 'status',
                sortable: true,
                width: 50
            }
        ],
        plugins: [actionarea, multisearcharea],
        listeners: {
            'rowdblclick': function() {
                var sm = area.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    editarea(sel[0].get('kd_area'), sel[0].get('nama_propinsi'));
                }
            },
            'rowclick': function() {
                var sm = area.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    storeMasterPelanggan_ma.reload({
                        params: {
                            kd_area: sel[0].get('kd_area')
                        }
                    });
                }
            }
        },
        tbar: tbarea,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strarea,
            displayInfo: true
        })
    });

    var searchMasterPelanggan_ma = new Ext.app.SearchField({
        store: storeMasterPelanggan_ma,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        id: 'id_search_grid_data_pelanggan_ma'
    });

    var topToolBarGridMasterPelanggan_ma = new Ext.Toolbar({
        items: [searchMasterPelanggan_ma]
    });

    /**
     * grid pelanggan selection model declaration
     */
    var cbGridDataPelanggan_ma = new Ext.grid.CheckboxSelectionModel();
    //grid
    var gridDataPelanggan_ma = new Ext.grid.EditorGridPanel({
        frame: true,
        border: true,
        stripeRows: true,
        sm: cbGridDataPelanggan_ma,
        store: storeMasterPelanggan_ma,
        loadMask: true,
        // title: 'Master Member',
        style: 'margin:0 auto;',
        height: 250,
        view: new Ext.ux.grid.LockingGridView(),
        colModel: new Ext.ux.grid.LockingColumnModel([//actionmasterpelanggan,
            {
                header: "Kode Pelanggan",
                dataIndex: 'kd_pelanggan',
                //locked: true,
                sortable: true,
                width: 90
            }, {
                header: "Nama Pelanggan",
                dataIndex: 'nama_pelanggan',
                sortable: true,
                //locked: true,
                width: 150
            }, {
                header: "Tipe",
                dataIndex: 'nama_tipe',
                sortable: true,
                width: 80
            }, {
                header: "Telepon",
                dataIndex: 'no_telp',
                sortable: true,
                width: 100
            }, {
                header: "Cabang",
                dataIndex: 'nama_cabang',
                sortable: true,
                width: 150
            }, {
                header: "Nama PIC",
                dataIndex: 'nama_pic',
                sortable: true,
                width: 150
            }, {
                header: "Telepon PIC",
                dataIndex: 'no_telp_pic',
                sortable: true,
                width: 100
            }, {
                header: "Kalurahan",
                dataIndex: 'nama_kalurahan',
                sortable: true,
                width: 100
            }, {
                header: "Kecamatan",
                dataIndex: 'nama_kecamatan',
                sortable: true,
                width: 100
            }, {
                header: "Kota",
                dataIndex: 'nama_kota',
                sortable: true,
                width: 100
            }, {
                header: "Propinsi",
                dataIndex: 'nama_propinsi',
                sortable: true,
                width: 100
            }, {
                header: "NPWP",
                dataIndex: 'npwp',
                sortable: true,
                width: 100
            }, {
                header: "Alamat NPWP",
                dataIndex: 'alamat_npwp',
                sortable: true,
                width: 100
            }, {
                header: "PKP",
                dataIndex: 'pkp',
                sortable: true,
                width: 100
            }, {
                header: "Alamat Kirim",
                dataIndex: 'alamat_kirim',
                sortable: true,
                width: 100
            }, {
                header: "Alamat Tagih",
                dataIndex: 'alamat_tagih',
                sortable: true,
                width: 100
            }, {
                header: "No. Fax",
                dataIndex: 'no_fax',
                sortable: true,
                width: 100
            }, {
                header: "E-mail",
                dataIndex: 'email',
                sortable: true,
                width: 100
            }, {
                header: "TOP Dist",
                dataIndex: 'top_dist',
                sortable: true,
                width: 100
            }, {xtype: 'numbercolumn',
                header: "Limit Dist",
                dataIndex: 'limit_dist',
                sortable: true,
                format: '0,0',
                width: 100
            }, {
                header: "Area",
                dataIndex: 'nama_area',
                sortable: true,
                width: 100
            }, {
                header: "Status",
                dataIndex: 'status',
                sortable: true,
                width: 100
            }
        ]),
        tbar: topToolBarGridMasterPelanggan_ma,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: storeMasterPelanggan_ma,
            displayInfo: true
        })
    });


    Ext.ns('snc_master_area');
    var masterArea_ma = new Ext.FormPanel({
        id: 'snc_master_area',
        border: false,
        frame: true,
        autoScroll: true,
        bodyStyle: 'padding:5px;',
        items: [
            area, gridDataPelanggan_ma
        ]
    });

    function editarea(kd_area, nm_propinsi) {
        Ext.getCmp('id_ma_cbpropinsi').setValue(nm_propinsi);
        Ext.getCmp('id_ma_reset').hide();
        Ext.getCmp('id_ma_submit').setText('Update');
        MstArea_WinAdd.setTitle('Edit Form');
        Ext.getCmp('id_ma_formaddarea').getForm().load({
            url: '<?= site_url("master_area/get_row") ?>',
            params: {
                id: kd_area,
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
        MstArea_WinAdd.show();
    }

    function deletearea() {
        var sm = area.getSelectionModel();
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
                            data = data + sel[i].get('kd_area') + ';';
                        }

                        Ext.Ajax.request({
                            url: '<?= site_url("master_area/delete_rows") ?>',
                            method: 'POST',
                            params: {
                                postdata: data
                            },
                            callback: function(opt, success, responseObj) {
                                var de = Ext.util.JSON.decode(responseObj.responseText);
                                if (de.success == true) {
                                    strarea.reload();
                                    strarea.load({
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
