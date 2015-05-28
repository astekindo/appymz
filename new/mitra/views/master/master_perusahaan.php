<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script type="text/javascript">
    /**
     * deklarasi grid perusahaan data store
     */
    var gridMasterPerusahaanStore = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'kd_perusahaan',
                'no_perusahaan',
                'nama_perusahaan',
                'tgl_berdiri',
                'no_akta_pendirian',
                'no_siup',
                'no_telp',
                'direktur',
                'no_fax',
                'deskripsi',
                'pkp',
                'npwp',
                'nama_npwp',
                'alamat_npwp',
                'alamat1',
                'alamat2',
                'alamat3'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("master_perusahaan_controller/finalGetRows") ?>',
            method: 'POST'
        }),
        listeners: {
            loadexception: function(event, options, response, error) {

            }
        }
    });

    gridMasterPerusahaanStore.load();

    /**
     * deklarasi form master perusahaan
     */
    Ext.ns('masterPerusahaanForm');
    masterPerusahaanForm.Form = Ext.extend(Ext.form.FormPanel, {
        // defaults - can be changed from outside
        border: false,
        frame: true,
        labelWidth: 130,
        url: '<?= site_url("master_perusahaan_controller/finalInsertUpdate") ?>',
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
            masterPerusahaanForm.Form.superclass.constructor.call(this, config);
        },
        initComponent: function() {

            var config = {
                layout: 'column',
                monitorValid: true,
                autoScroll: true,
                defaults: {
                    xtype: 'container',
                    autoEl: 'div',
                    layout: 'form',
                    columnWidth: 0.5,
                    defaultType: 'textfield',
                    style: {
                        padding: '10px'
                    }
                },
                items: [{
                    items: [{
                        xtype: 'textfield',
                        fieldLabel: 'Kode Perusahaan',
                        name: 'kd_perusahaan',
                        id: 'id_txt_kd_perusahaan',
                        anchor: '60%',
                        minLength: 6,
                        maxLength: 6,
                        allowBlank: false
                    }, {
                        xtype: 'textfield',
                        fieldLabel: 'Nomor Perusahaan <span class="asterix">*</span>',
                        name: 'no_perusahaan',
                        id: 'id_txt_no_perusahaan',
                        anchor: '60%',
                        maxLength: 2,
                        allowBlank: false
                    }, {
                        xtype: 'textfield',
                        fieldLabel: 'Nama Perusahaan <span class="asterix">*</span>',
                        name: 'nama_perusahaan',
                        id: 'id_txt_nama_perusahaan',
                        anchor: '90%',
                        allowBlank: false
                    }, {
                        xtype: 'datefield',
                        fieldLabel: 'Tanggal Pendirian <span class="asterix">*</span>',
                        name: 'tgl_berdiri',
                        id: 'id_txt_tgl_berdiri',
                        anchor: '90%',
                        allowBlank: false
                    }, {
                        xtype: 'textfield',
                        fieldLabel: 'No. Akta Pendirian <span class="asterix">*</span>',
                        name: 'no_akta_pendirian',
                        id: 'id_txt_no_akta_pendirian',
                        minLength: 5,
                        anchor: '90%',
                        allowBlank: false
                    }, {
                        xtype: 'textfield',
                        fieldLabel: 'No. SIUP <span class="asterix">*</span>',
                        name: 'no_siup',
                        id: 'id_txt_no_siup',
                        minLength: 5,
                        anchor: '90%',
                        allowBlank: false
                    }, {
                        xtype: 'textfield',
                        fieldLabel: 'No.Telp <span class="asterix">*</span>',
                        name: 'no_telp_perusahaan',
                        id: 'id_txt_no_telp_perusahaan',
                        minLength: 5,
                        maxLength: 13,
                        anchor: '90%',
                        allowBlank: false
                    }, {
                        xtype: 'textfield',
                        fieldLabel: 'Direktur <span class="asterix">*</span>',
                        name: 'direktur_perusahaan',
                        id: 'id_txt_direktur_perusahaan',
                        anchor: '90%',
                        allowBlank: false
                    }, {
                        xtype: 'numberfield',
                        fieldLabel: 'No.Fax <span class="asterix">*</span>',
                        name: 'no_fax_perusahaan',
                        id: 'id_txt_no_fax_perusahaan',
                        anchor: '90%',
                        allowBlank: false
                    }, {
                        xtype: 'radiogroup',
                        fieldLabel: 'Aktif',
                        items: [{
                                boxLabel: 'ya',
                                name: 'aktif',
                                id: 'id_txt_aktif_y',
                                inputValue: 1,
                                value: 1
                            }, {
                                boxLabel: 'tidak',
                                name: 'aktif',
                                id: 'id_txt_aktif_t',
                                inputValue: 0
                            }]
                    }, {
                        xtype: 'textarea',
                        fieldLabel: 'Keterangan',
                        name: 'deskripsi_perusahaan',
                        id: 'id_txt_deskripsi_perusahaan',
                        height: 50,
                        anchor: '90%'
                    }, {
                        xtype: 'label',
                        html: '*) Format Kode Perusahaan.<br/>' +
                            '<div style="margin-top: 15px"><b>XXYYZZ</b><br/>' +
                            'XX: No urut<br/>' +
                            'YY: tahun pendirian<br/>' +
                            'ZZ: bulan pendirian<br/></div>',
                    }]
                },{
                    items: [{
                        xtype: 'radiogroup',
                        fieldLabel: 'PKP',
                        items: [{
                                boxLabel: 'ya',
                                name: 'radio_pkp',
                                id: 'id_radio_pkp_y',
                                inputValue: 1,
                                value: 1
                            }, {
                                boxLabel: 'tidak',
                                name: 'radio_pkp',
                                id: 'id_radio_pkp_t',
                                inputValue: 0
                            }]
                    }, {
                        xtype: 'textfield',
                        fieldLabel: 'NPWP <span class="asterix">*</span>',
                        name: 'no_npwp',
                        id: 'id_txt_no_npwp',
                        anchor: '90%',
                        allowBlank: false,
                        listeners: {
                            'blur': function() {
                                var no_npwp = Ext.getCmp('id_txt_no_npwp').getValue();
                                if (no_npwp.length == 15) {
                                    no_npwp = no_npwp.replace("-", "");
                                    no_npwp = no_npwp.replace(".", "");
                                    Ext.getCmp('id_txt_no_npwp').setValue(
                                            no_npwp.substr(0, 2) + '.' +
                                            no_npwp.substr(2, 3) + '.' +
                                            no_npwp.substr(5, 3) + '.' +
                                            no_npwp.substr(8, 1) + '-' +
                                            no_npwp.substr(9, 3) + '.' +
                                            no_npwp.substr(12)
                                            );
                                }
                            }
                        }
                    }, {
                        xtype: 'textfield',
                        fieldLabel: 'Nama NPWP <span class="asterix">*</span>',
                        name: 'nama_npwp',
                        id: 'id_txt_nama_npwp',
                        anchor: '90%',
                        allowBlank: false
                    }, {
                        xtype: 'textarea',
                        fieldLabel: 'Alamat NPWP <span class="asterix">*</span>',
                        name: 'alamat_npwp',
                        id: 'id_txt_alamat_npwp',
                        anchor: '90%',
                        height: 50,
                        allowBlank: false
                    }, {
                        xtype: 'textarea',
                        fieldLabel: 'Alamat 1 <span class="asterix">*</span>',
                        name: 'alamat1',
                        id: 'id_txt_alamat1',
                        height: 50,
                        anchor: '90%',
                        allowBlank: false
                    }, {
                        xtype: 'textarea',
                        fieldLabel: 'Alamat 2',
                        name: 'alamat2',
                        id: 'id_txt_alamat2',
                        height: 50,
                        anchor: '90%'
                    }, {
                        xtype: 'textarea',
                        fieldLabel: 'Alamat 3',
                        name: 'alamat3',
                        id: 'id_txt_alamat3',
                        height: 50,
                        anchor: '90%'
                    }]
                }],
                buttons: [{
                    text: 'Submit',
                    id: 'id_btn_submit_master_perusahaan',
                    formBind: true,
                    scope: this,
                    handler: this.submit
                }, {
                    text: 'Reset',
                    id: 'id_btn_reset_master_perusahaan',
                    scope: this,
                    handler: this.reset
                }, {
                    text: 'Close',
                    id: 'id_btn_close_master_perusahaan',
                    scope: this,
                    handler: function() {
                        windowMasterPerusahaan.hide();
                    }
                }]
            };
            Ext.apply(this, Ext.apply(this.initialConfig, config));
            // call parent
            masterPerusahaanForm.Form.superclass.initComponent.apply(this, arguments);
        },
        onRender: function() {
            masterPerusahaanForm.Form.superclass.onRender.apply(this, arguments);
            this.getForm().waitMsgTarget = this.getEl();
        },
        reset: function() {
            this.getForm().reset();
        },
        submit: function() {
            var text = Ext.getCmp('id_btn_submit_master_perusahaan').getText();
            if (text == 'update') {
                Ext.Msg.show({
                    title: 'Confirm',
                    msg: 'Are you sure update selected row ?',
                    buttons: Ext.Msg.YESNO,
                    fn: function(btn) {
                        if (btn == 'yes') {
                            updateMasterPerusahaan();
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
        },
        onSuccess: function(form, action) {
            Ext.Msg.show({
                title: 'Success',
                msg: 'Form submitted successfully',
                modal: true,
                icon: Ext.Msg.INFO,
                buttons: Ext.Msg.OK
            });
            gridMasterPerusahaanStore.reload();
            //Ext.getCmp('id_master_perusahaan_form').getForm().reset();
            windowMasterPerusahaan.hide();
        },
        onFailure: function(form, action) {

            var fe = Ext.util.JSON.decode(action.response.responseText);
            this.showError(fe.errMsg || '');
        },
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
     * register master perusahaan form
     */
    Ext.reg('masterPerusahaanForm', masterPerusahaanForm.Form);

    /**
     * deklarasi window master perusahaan
     */
    var windowMasterPerusahaan = new Ext.Window({
        id: 'id_window_master_perusahaan',
        closeAction: 'hide',
        width: 800,
        height: 500,
        frame: true,
        layout: 'fit',
        border: false,
        items: {
            id: 'id_master_perusahaan_form',
            xtype: 'masterPerusahaanForm'
        },
        onHide: function() {
            //Ext.getCmp('id_pengunjung_form').getForm().reset();
        }
    });


    /**
     * deklarasi search field grid master perusahaan
     */
    var searchField = new Ext.app.SearchField({
        store: gridMasterPerusahaanStore,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 250,
        id: 'id_search_field_grid_master_perusahaan'
    });

    /**
     * deklarasi bottom multi search
     */
    var multiSearchGridMasterPerusahhan = new Ext.ux.grid.Search({
        iconCls: 'icon-zoom',
        minChars: 3,
        autoFocus: true,
        width: 250
    });


    /**
     * deklarasi toolbar grid master perusahaan
     */
    var toolBarGridMasterPerusahaan = new Ext.Toolbar({
        items: [{
                text: 'Add',
                icon: BASE_ICONS + 'add.png',
                onClick: function() {
                    Ext.getCmp('id_btn_submit_master_perusahaan').setText('save');
                    //Ext.getCmp('id_txt_kd_perusahaan').reset();
                    Ext.getCmp('id_master_perusahaan_form').getForm().reset();
                    windowMasterPerusahaan.show();

                }
            }, '-', searchField
        ]
    });


    /**
     * deklarasi grid master perusahaan selection model
     */
    var smGridMasterPerusahaan = new Ext.grid.CheckboxSelectionModel();

    /**
     *deklarasi action edit row
     */
    var actionEdit = new Ext.ux.grid.RowActions({
        header: 'Edit',
        autoWidth: false,
        locked: true,
        width: 50,
        actions: [{iconCls: 'icon-edit-record', qtip: 'Edit'}],
        widthIntercept: Ext.isSafari ? 4 : 2
    });

    /**
     * add event to action edit row
     */
    actionEdit.on('action', function(grid, record, action, row, col) {
        Ext.getCmp('id_btn_submit_master_perusahaan').setText('update');
        Ext.getCmp('id_btn_reset_master_perusahaan').hide();
        windowMasterPerusahaan.setTitle('Edit Data Form');
        windowMasterPerusahaan.show();

        if (record.get('kd_perusahaan') != '') {
            if (record.get('pkp') == 1) {
                Ext.getCmp('id_radio_pkp_y').setValue(true);
                Ext.getCmp('id_radio_pkp_t').setValue(false);
            } else {
                Ext.getCmp('id_radio_pkp_y').setValue(false);
                Ext.getCmp('id_radio_pkp_t').setValue(true);
            }

            if (record.get('aktif') == 1) {
                Ext.getCmp('id_txt_aktif_y').setValue(true);
                Ext.getCmp('id_txt_aktif_t').setValue(false);
            } else {
                Ext.getCmp('id_txt_aktif_y').setValue(false);
                Ext.getCmp('id_txt_aktif_t').setValue(true);
            }

            Ext.getCmp('id_txt_kd_perusahaan').setValue(record.get('kd_perusahaan'));
            Ext.getCmp('id_txt_no_perusahaan').setValue(record.get('no_perusahaan'));
            Ext.getCmp('id_txt_nama_perusahaan').setValue(record.get('nama_perusahaan'));
            Ext.getCmp('id_txt_tgl_berdiri').setValue(record.get('tgl_berdiri'));
            Ext.getCmp('id_txt_no_akta_pendirian').setValue(record.get('no_akta_pendirian'));
            Ext.getCmp('id_txt_no_siup').setValue(record.get('no_siup'));
            Ext.getCmp('id_txt_no_telp_perusahaan').setValue(record.get('no_telp'));
            Ext.getCmp('id_txt_direktur_perusahaan').setValue(record.get('direktur'));
            Ext.getCmp('id_txt_no_fax_perusahaan').setValue(record.get('no_fax'));
            Ext.getCmp('id_txt_deskripsi_perusahaan').setValue(record.get('deskripsi'));
            Ext.getCmp('id_txt_no_npwp').setValue(record.get('npwp'));
            Ext.getCmp('id_txt_nama_npwp').setValue(record.get('nama_npwp'));
            Ext.getCmp('id_txt_alamat_npwp').setValue(record.get('alamat_npwp'));
            Ext.getCmp('id_txt_alamat1').setValue(record.get('alamat1'));
            Ext.getCmp('id_txt_alamat2').setValue(record.get('alamat2'));
            Ext.getCmp('id_txt_alamat3').setValue(record.get('alamat3'));

        }
    });

    /**
     * deklarasi grid panel
     */
    var gridMasterPerusahaan = new Ext.grid.EditorGridPanel({
        id: 'id_master_perusahaan_grid_panel',
        frame: true,
        border: true,
        stripeRows: true,
        sm: smGridMasterPerusahaan,
        store: gridMasterPerusahaanStore,
        loadMask: false,
        style: 'margin:0 auto;',
        title: 'Master Perusahaan',
        height: 500,
        columns: [actionEdit,
            {
                header: 'Kd. Perusahaan',
                dataIndex: 'kd_perusahaan',
                sortable: true,
                width: 150
            }, {
                header: 'No. Perusahaan',
                dataIndex: 'no_perusahaan',
                sortable: true,
                width: 150
            }, {
                header: 'Nama Perusahaan',
                dataIndex: 'nama_perusahaan',
                sortable: true,
                width: 150
            }, {
                header: 'Tanggal Berdiri',
                dataIndex: 'tgl_berdiri',
                sortable: true,
                width: 150
            }, {
                header: 'No. Akta Pendirian',
                dataIndex: 'no_akta_pendirian',
                sortable: true,
                width: 150
            }, {
                header: 'No. SIUP',
                dataIndex: 'no_siup',
                sortable: true,
                width: 150
            }, {
                header: 'No. Telp',
                dataIndex: 'no_telp',
                sortable: true,
                width: 150
            }, {
                header: 'Direktur',
                dataIndex: 'direktur',
                sortable: true,
                width: 150
            }, {
                header: 'No. Fax',
                dataIndex: 'no_fax',
                sortable: true,
                width: 150
            }, {
                header: 'Deskripsi',
                dataIndex: 'deskripsi',
                sortable: true,
                width: 150
            }, {
                header: 'PKP',
                dataIndex: 'pkp',
                sortable: true,
                width: 150
            }, {
                header: 'No. NPWP',
                dataIndex: 'npwp',
                sortable: true,
                width: 150
            }, {
                header: 'Nama NPWP',
                dataIndex: 'nama_npwp',
                sortable: true,
                width: 150
            }, {
                header: 'Alamat NPWP',
                dataIndex: 'alamat_npwp',
                sortable: true,
                width: 150
            }, {
                header: 'Alamat 1',
                dataIndex: 'alamat1',
                sortable: true,
                width: 150
            }, {
                header: 'Alamat 2',
                dataIndex: 'alamat2',
                sortable: true,
                width: 150
            }, {
                header: 'Alamat 3',
                dataIndex: 'alamat3',
                sortable: true,
                width: 150
            }
        ],
        plugins: [actionEdit, multiSearchGridMasterPerusahhan],
        tbar: toolBarGridMasterPerusahaan,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: gridMasterPerusahaanStore,
            displayInfo: true
        })
    });

    function updateMasterPerusahaan() {
        Ext.getCmp('id_master_perusahaan_form').getForm().submit({
            url: '<?= site_url("master_perusahaan_controller/finalInsertUpdate") ?>',
            success: function(form, action) {
                Ext.Msg.show({
                    title: 'Success',
                    msg: 'Form submitted successfully',
                    modal: true,
                    icon: Ext.Msg.INFO,
                    buttons: Ext.Msg.OK
                });
                gridMasterPerusahaanStore.reload();
                windowMasterPerusahaan.hide();
            },
            failure: function(form, action) {

                var fe = Ext.util.JSON.decode(action.response.responseText);
                this.showError(fe.errMsg || '');
            },
            params: {
                cmd: 'update'
            },
            waitMsg: 'Updating Data...'
        });
    }

    /**
     * deklarasi form panel
     */
    Ext.ns('formMasterPerusahaan');
    var formMasterPerusahaan = new Ext.FormPanel({
        id: 'id_master_perusahaan',
        border: false,
        frame: true,
        items: [gridMasterPerusahaan]
    });

</script>
