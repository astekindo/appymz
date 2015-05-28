<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">
    /**
     * store untuk grid pengunjung
     */
    var pengunjungDataStore = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['tanggal',
                'kd_cabang',
                'nama_cabang',
                'jumlah'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("input_jumlah_pengunjung_controller/finalGetRows") ?>',
            method: 'POST'
        }),
        listeners: {
            loadexception: function(event, options, response, error) {

            }
        }
    });
    //pengunjungDataStore.load();

    /**
     * store combo box lokasi cabang
     */
    var comboLokasiCabangStore = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'kd_cabang', 'nama_cabang', 'status'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("input_jumlah_pengunjung_controller/finalGetCabang") ?>',
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
     * deklarasi combobox lokasi cabang
     */
    var comboLokasiCabang = new Ext.form.ComboBox({
        fieldLabel: 'Nama Cabang <span class="asterix">*</span>',
        id: 'id_combo_cabang',
        store: comboLokasiCabangStore,
        valueField: 'kd_cabang',
        displayField: 'nama_cabang',
        typeAhead: true,
        triggerAction: 'all',
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_cabang',
        emptyText: 'Pilih Cabang'
    });

    var comboFilterCabang = new Ext.form.ComboBox({
        fieldLabel: 'Nama Cabang <span class="asterix">*</span>',
        id: 'id_combo_filter_cabang',
        store: comboLokasiCabangStore,
        valueField: 'nama_cabang',
        displayField: 'nama_cabang',
        typeAhead: true,
        triggerAction: 'all',
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_cabang',
        emptyText: 'Pilih Cabang'
    });
    /**
     * deklarasi pengunjung form
     */
    Ext.ns('pengunjungForm');
    pengunjungForm.Form = Ext.extend(Ext.form.FormPanel, {
        // defaults - can be changed from outside
        border: false,
        frame: true,
        labelWidth: 130,
        url: '<?= site_url("input_jumlah_pengunjung_controller/finalInsertOrUpdate") ?>',
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
            pengunjungForm.Form.superclass.constructor.call(this, config);
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
                        name: 'action',
                        id: 'mep_action'
                    }, {
                        xtype: 'datefield',
                        fieldLabel: 'Tanggal <span class="asterix">*</span>',
                        name: 'txt_tanggal',
                        id: 'id_txt_tanggal',
                        format: 'Y-m-d',
                        anchor: '90%'
                    }, comboLokasiCabang, {
                        xtype: 'numberfield',
                        fieldLabel: 'Jumlah Pengunjung <span class="asterix">*</span>',
                        name: 'txt_jumlah_pengunjung',
                        id: 'id_txt_jumlah_pengunjung',
                        anchor: '90%',
                        allowblank: false
                    }
                ],
                buttons: [{
                        text: 'Submit',
                        id: 'btn_submit_pengunjung',
                        formBind: true,
                        scope: this,
                        handler: this.submit
                    }, {
                        text: 'Reset',
                        id: 'btn_reset_pengunjung',
                        scope: this,
                        handler: this.reset
                    }, {
                        text: 'Close',
                        id: 'btn_close_pengunjung',
                        scope: this,
                        handler: function() {
                            windowPengunjung.hide();
                        }
                    }]
            }; // eo config object
            // apply config
            Ext.apply(this, Ext.apply(this.initialConfig, config));
            // call parent
            pengunjungForm.Form.superclass.initComponent.apply(this, arguments);
        } // eo function initComponent  
        ,
        onRender: function() {

            // call parent
            pengunjungForm.Form.superclass.onRender.apply(this, arguments);
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
            var text = Ext.getCmp('btn_submit_pengunjung').getText();
            if (text == 'update') {
                Ext.Msg.show({
                    title: 'Confirm',
                    msg: 'Are you sure update selected row ?',
                    buttons: Ext.Msg.YESNO,
                    fn: function(btn) {
                        if (btn == 'yes') {
                            updateRecordPengunjung();
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
            pengunjungDataStore.reload();
            windowPengunjung.hide();
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

    /**
     * register pengunjung form
     */
    Ext.reg('pengunjung_form', pengunjungForm.Form);
    /**
     * deklatasi window pengunjung
     */
    var windowPengunjung = new Ext.Window({
        id: 'id_window_pengunjung',
        closeAction: 'hide',
        width: 450,
        height: 350,
        layout: 'fit',
        border: false,
        items: {
            id: 'id_pengunjung_form',
            xtype: 'pengunjung_form'
        },
        onHide: function() {
            Ext.getCmp('id_pengunjung_form').getForm().reset();
        }
    });
    /**
     * deklarasi toolbar untuk grid pengunjung
     */
    var toolbarGridPengunjung = new Ext.Toolbar({
        items: [{
                text: 'Add',
                icon: BASE_ICONS + 'add.png',
                onClick: function() {
                    Ext.getCmp('btn_submit_pengunjung').setText('save');
                    Ext.getCmp('id_txt_tanggal').setValue(new Date());
                    Ext.getCmp('id_txt_tanggal').setReadOnly(false);
                    Ext.getCmp('id_combo_cabang').setReadOnly(false);
                    Ext.getCmp('id_txt_tanggal').removeClass('readonly-input');
                    Ext.getCmp('id_combo_cabang').removeClass('readonly-input');
                    Ext.getCmp('id_txt_tanggal').setValue('');
                    Ext.getCmp('id_combo_cabang').setValue('');
                    Ext.getCmp('id_txt_jumlah_pengunjung').setValue('');
                    windowPengunjung.show();
                }
            }//, searchPengunjung
        ]
    });
    /**
     * deklarasi selection model untuk grid pengunjung
     */
    var smGridPengunjung = new Ext.grid.CheckboxSelectionModel();
    /**
     * deklarasi row action
     */
    var actionEdit = new Ext.ux.grid.RowActions({
        header: 'Edit',
        autoWidth: false,
        locked: true,
        width: 50,
        actions: [{iconCls: 'icon-edit-record', qtip: 'Edit'}],
        widthIntercept: Ext.isSafari ? 4 : 2
    });
    var actionDelete = new Ext.ux.grid.RowActions({
        header: 'Delete',
        autoWidth: false,
        locked: true,
        width: 50,
        actions: [{iconCls: 'icon-delete-record', qtip: 'Edit'}],
        widthIntercept: Ext.isSafari ? 4 : 2
    });
    actionEdit.on('action', function(grid, record, action, row, col) {
        var tanggal = record.get('tanggal');
        var kdCabang = record.get('kd_cabang');
        var jumlah = record.get('jumlah');
        switch (action) {
            case 'icon-edit-record':
                showEditForm(tanggal, kdCabang, jumlah);
                break;
            case 'icon-delete-record':
                Ext.Msg.show({
                    title: 'Confirm',
                    msg: 'Are you sure delete selected row ?',
                    buttons: Ext.Msg.YESNO,
                    fn: function(btn) {
                        if (btn == 'yes') {
                            Ext.Ajax.request({
                                url: '<?= site_url("input_jumlah_pengunjung_controller/finalDelete") ?>',
                                method: 'POST',
                                params: {
                                    txt_tanggal: tanggal,
                                    kd_cabang: kdCabang
                                },
                                callback: function(opt, success, responseObj) {
                                    var de = Ext.util.JSON.decode(responseObj.responseText);
                                    if (de.success == true) {
                                        pengunjungDataStore.removeAll();
                                        pengunjungDataStore.reload();
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
                break;
        }
    });
    /**
     * deklarasi grid panel pengunjung
     */
    var pengunjungDataGrid = new Ext.grid.EditorGridPanel({
        id: 'pengunjung_data_grid',
        frame: true,
        border: true,
        stripeRows: true,
        sm: smGridPengunjung,
        store: pengunjungDataStore,
        loadMask: false,
        style: 'margin:0 auto;',
        title: 'Jumlah Pengunjung',
        height: 400,
        columns: [actionEdit,
            actionDelete,
            {
                header: "Tanggal",
                dataIndex: 'tanggal',
                sortable: true,
                width: 150
            }, {
                header: "Kode Cabang",
                dataIndex: 'kd_cabang',
                sortable: true,
                width: 150
            }, {
                header: "Nama Cabang",
                dataIndex: 'nama_cabang',
                sortable: true,
                width: 150
            }, {
                header: "Jumlah Pengunjung",
                dataIndex: 'jumlah',
                sortable: true,
                width: 150
            }],
        plugins: [actionEdit, actionDelete],
        tbar: toolbarGridPengunjung,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: pengunjungDataStore,
            displayInfo: true
        })
    });

    function showEditForm(tanggal, kdCabang, jumlah) {
        Ext.getCmp('btn_submit_pengunjung').setText('update');
        win_add_npwp.setTitle('View Data Form');
        if (tanggal != '') {
            Ext.getCmp('id_txt_tanggal').setValue(tanggal);
            Ext.getCmp('id_combo_cabang').setValue(kdCabang);
            Ext.getCmp('id_txt_jumlah_pengunjung').setValue(jumlah);
            Ext.getCmp('id_txt_tanggal').setReadOnly(true);
            Ext.getCmp('id_txt_tanggal').addClass('readonly-input');
            Ext.getCmp('id_combo_cabang').setReadOnly(true);
            Ext.getCmp('id_combo_cabang').addClass('readonly-input');
        }
        windowPengunjung.show();
    }


    var headerInputPengunjung = {
        layout: 'column',
        border: false,
        buttonAlign: 'left',
        items: [{
                columnWidth: .5,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: {labelSeparator: ''},
                items: [
                    {
                        xtype: 'datefield',
                        fieldLabel: 'Tgl Input',
                        emptyText: 'Tanggal Input',
                        name: 'tgl_input_pengunjung',
                        id: 'id_tgl_input_pengunjung',
                        maxLength: 255,
                        anchor: '90%',
                        value: '',
                        format: 'Y-m-d'
                    }
                ]
            }, {
                columnWidth: .5,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: {labelSeparator: ''},
                items: [
                    comboFilterCabang
                ]
            }],
        buttons: [{
                text: 'Filter',
                formBind: true,
                handler: function() {
                    pengunjungDataGrid.store.removeAll();
                    pengunjungDataGrid.store.load({
                        params: {
                            tanggalInput: Ext.getCmp('id_tgl_input_pengunjung').getValue(),
                            namaCabang: Ext.getCmp('id_combo_filter_cabang').getValue()
                        }
                    });
                }
            }, {
                text: 'Reset',
                formBind: true,
                handler: function() {
                    tanggalInput: Ext.getCmp('id_tgl_input_pengunjung').setValue('');
                    Ext.getCmp('id_combo_filter_cabang').setValue('');
                    pengunjungDataStore.removeAll();
                }
            }
        ]
    };
    /**
     * deklarasi form panel utama
     */
    var inputJumlahPengunjung = new Ext.FormPanel({
        id: 'id_input_jumlah_pengunjung',
        border: false,
        frame: true,
        bodyStyle: 'p adding-right:20px;',
        labelWidth: 130,
        items: [{
                bodyStyle: {
                    margin: '0px 0px 15px 0px'
                },
                items: [headerInputPengunjung]
            }, pengunjungDataGrid]
    });

    function updateRecordPengunjung() {
        Ext.getCmp('id_pengunjung_form').getForm().submit({
            url: '<?= site_url("input_jumlah_pengunjung_controller/finalInsertOrUpdate") ?>',
            success: function(form, action) {
                Ext.Msg.show({
                    title: 'Success',
                    msg: 'Form submitted successfully',
                    modal: true,
                    icon: Ext.Msg.INFO,
                    buttons: Ext.Msg.OK
                });
                pengunjungDataStore.reload();
                windowPengunjung.hide();
            },
            failure: this.onFailure,
            params: {
                cmd: 'update'
            },
            waitMsg: 'Updating Data...'
        });
    }


</script>
