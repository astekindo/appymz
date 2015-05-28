<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">

    /**
     * store untuk grid pelanggan(grid 1)
     */
    var store_t_pelanggan_dist = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'kd_pelanggan',
                'nama_pelanggan',
                'alamat_kirim',
                'alamat_tagih',
                'npwp',
                'alamat_npwp',
                'kd_propinsi',
                'kd_kota',
                'kd_kecamatan',
                'kd_pos',
                'no_telp',
                'nama_pic',
                'no_telp_pic',
                'aktif',
                'top_dist',
                'limit_dist',
                'tipe',
                'kd_cabang',
                'kd_kelurahan',
                'is_pkp',
                'no_fax',
                'email',
                'kd_sales',
                'kd_npwp'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("setting_npwp_pelanggan_controller/finalGetRows") ?>',
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
     * store untuk grid pelanggan npwp(grid2)
     */
    var store_t_npwp = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'kd_pelanggan',
                'kd_npwp',
                'nama_npwp',
                'alamat_npwp',
                'no_npwp',
                'aktif'

            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("setting_npwp_pelanggan_controller/finalGetNpwpRows") ?>',
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
    //store_t_npwp.load();
    /**
     *deklarasi search field 
     */
    var search_pelanggan = new Ext.app.SearchField({
        store: store_t_pelanggan_dist,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        id: 'spb_search_list_npwp'
    });
    var search_npwp = new Ext.app.SearchField({
        store: store_t_npwp,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        id: 'spb_search_list_npwp_grid2'
    });
    //=========================search grid 1===========================================
    search_pelanggan.onTrigger1Click = function(evt) {
        if (this.hasSearch) {
            this.el.dom.value = '';
            // Get the value of search field
            var fid = Ext.getCmp('spb_search_list_npwp').getValue();
            var o = {start: 0, fieldId: fid};
            this.store.baseParams = this.store.baseParams || {};
            this.store.baseParams[this.paramName] = '';
            this.store.reload({
                params: o
            });
            this.triggers[0].hide();
            this.hasSearch = false;
        }
    };
    search_pelanggan.onTrigger2Click = function(evt) {
        var text = this.getRawValue();
        if (text.length < 1) {
            this.onTrigger1Click();
            return;
        }

        // Get the value of search field
        var fid = Ext.getCmp('spb_search_list_npwp').getValue();
        var o = {start: 0, fieldId: fid};
        this.store.baseParams = this.store.baseParams || {};
        this.store.baseParams[this.paramName] = text;
        this.store.reload({params: o});
        this.hasSearch = true;
        this.triggers[0].show();
    };
    //=========================end search grid 1===========================================

    //=========================search grid 2===========================================
    search_npwp.onTrigger1Click = function(evt) {
        if (this.hasSearch) {
            this.el.dom.value = '';
            // Get the value of search field
            var fid = Ext.getCmp('spb_search_list_npwp_grid2').getValue();
            var o = {start: 0, fieldId: fid};
            this.store.baseParams = this.store.baseParams || {};
            this.store.baseParams[this.paramName] = '';
            this.store.reload({
                params: o
            });
            this.triggers[0].hide();
            this.hasSearch = false;
        }
    };
    search_npwp.onTrigger2Click = function(evt) {
        var text = this.getRawValue();
        if (text.length < 1) {
            this.onTrigger1Click();
            return;
        }

        // Get the value of search field
        var fid = Ext.getCmp('spb_search_list_npwp_grid2').getValue();
        var o = {start: 0, fieldId: fid};
        this.store.baseParams = this.store.baseParams || {};
        this.store.baseParams[this.paramName] = text;
        this.store.reload({params: o});
        this.hasSearch = true;
        this.triggers[0].show();
    };
    //=========================end search grid 2===========================================
    //toolbar grid 1
    var toolbar_search_pelanggan = new Ext.Toolbar({
        items: [search_pelanggan]
    });
    /**
     * toolbar grid 2
     */
    var toolbar_npwp_grid = new Ext.Toolbar({
        items: [{
                text: 'Add',
                icon: BASE_ICONS + 'add.png',
                onClick: function() {
                    //store_t_npwp.removeAll();
                    var kdPelanggan = Ext.getCmp('id_txt_kd_pelanggan').getValue();
                    if (kdPelanggan == '' || kdPelanggan == null) {
                        Ext.Msg.show({
                            title: 'Warning',
                            msg: 'Silahkan Klik Data Pelanggan Terlebih Dahulu',
                            modal: true,
                            icon: Ext.Msg.WARNING,
                            buttons: Ext.Msg.OK
                        });
                        return;
                    } else {
                        Ext.Ajax.request({
                            url: '<?= site_url("setting_npwp_pelanggan_controller/getKodeNpwp") ?>',
                            method: 'POST',
                            callback: function(opt, success, responseObj) {
                                var de = Ext.util.JSON.decode(responseObj.responseText);
                                if (de.success == true) {
                                    Ext.getCmp('id_txt_kd_npwp').setValue(de.data.txt_kd_npwp);
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
                        Ext.getCmp('btn_submit_npwp').setText('Save');
                        win_add_npwp.setTitle('Add Form');
                        //Ext.getCmp('id_txt_kd_npwp').removeClass('readonly-input');
                        //Ext.getCmp('id_txt_kd_npwp').setReadOnly(false);
                        Ext.getCmp('id_txt_nama_npwp').setValue('');
                        Ext.getCmp('id_txt_alamat_npwp').setValue('');
                        Ext.getCmp('id_txt_no_npwp').setValue('');
                        win_add_npwp.show();
                    }
                }
            }, search_npwp]
    });
//====
    Ext.ns('npwp_add_form');
    npwp_add_form.Form = Ext.extend(Ext.form.FormPanel, {
        // defaults - can be changed from outside
        border: false,
        frame: true,
        labelWidth: 130,
        url: '<?= site_url("setting_npwp_pelanggan_controller/finalUpdateOrInsert") ?>',
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
            npwp_add_form.Form.superclass.constructor.call(this, config);
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
                        xtype: 'textfield',
                        fieldLabel: 'Kode Pelanggan <span class="asterix">*</span>',
                        name: 'txt_kd_pelanggan',
                        id: 'id_txt_kd_pelanggan',
                        readOnly: true,
                        fieldClass: 'readonly-input',
                        anchor: '90%'
                    }, {
                        xtype: 'textfield',
                        fieldLabel: 'Kode NPWP <span class="asterix">*</span>',
                        name: 'txt_kd_npwp',
                        readOnly: true,
                        fieldClass: 'readonly-input',
                        id: 'id_txt_kd_npwp',
                        anchor: '90%'
                    }, {
                        xtype: 'textfield',
                        fieldLabel: 'Nama NPWP <span class="asterix">*</span>',
                        name: 'txt_nama_npwp',
                        id: 'id_txt_nama_npwp',
                        //readOnly: true,
                        //fieldClass: 'readonly-input',
                        anchor: '90%'
                    }, {
                        xtype: 'textfield',
                        fieldLabel: 'No NPWP <span class="asterix">*</span>',
                        name: 'txt_no_npwp',
                        id: 'id_txt_no_npwp',
                        allowBlank: false,
                        minLength: 15,
                        maxLength: 20,
                        anchor: '90%',
                        listeners: {
                            'blur': function() {
                                console.log('ini bisa!');
                                var no_npwp = Ext.getCmp('id_txt_no_npwp').getValue();
                                console.log(no_npwp);
                                if (no_npwp.length == 15) {
                                    no_npwp = no_npwp.replace("-", "");
                                    no_npwp = no_npwp.replace(".", "");
                                    //format xx.xxx.xxx.x-xxx.xxx
                                    Ext.getCmp('id_txt_no_npwp').setValue(
                                            no_npwp.substr(0, 2) + '.' +
                                            no_npwp.substr(2, 3) + '.' +
                                            no_npwp.substr(5, 3) + '.' +
                                            no_npwp.substr(8, 1) + '-' +
                                            no_npwp.substr(9, 3) + '.' +
                                            no_npwp.substr(12)
                                            );
                                }
                                console.log('ini selesai!');
                            }
                        }
                    }, {
                        xtype: 'textarea',
                        fieldLabel: 'Alamat NPWP <span class="asterix">*</span>',
                        name: 'txt_alamat_npwp',
                        id: 'id_txt_alamat_npwp',
                        anchor: '90%'
                    }, {
                        xtype: 'checkbox',
                        fieldLabel: 'Aktif <span class="asterix">*</span>',
                        name: 'check_aktif_npwp',
                        id: 'id_check_aktif_npwp',
                        anchor: '90%'
                    }],
                buttons: [{
                        text: 'Submit',
                        id: 'btn_submit_npwp',
                        formBind: true,
                        scope: this,
                        handler: this.submit
                    }, {
                        text: 'Reset',
                        id: 'btn_reset_npwp',
                        scope: this,
                        handler: this.reset
                    }, {
                        text: 'Close',
                        id: 'btn_close_npwp',
                        scope: this,
                        handler: function() {
                            win_add_npwp.hide();
                        }
                    }]
            }; // eo config object
            // apply config
            Ext.apply(this, Ext.apply(this.initialConfig, config));
            // call parent
            npwp_add_form.Form.superclass.initComponent.apply(this, arguments);
        } // eo function initComponent  
        ,
        onRender: function() {

            // call parent
            npwp_add_form.Form.superclass.onRender.apply(this, arguments);
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
            var text = Ext.getCmp('btn_submit_npwp').getText();
            if (text == 'update') {
                Ext.Msg.show({
                    title: 'Confirm',
                    msg: 'Are you sure update selected row ?',
                    buttons: Ext.Msg.YESNO,
                    fn: function(btn) {
                        if (btn == 'yes') {
                            updateRecordNpwp();
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
                buttons: Ext.Msg.OK,
            });
            store_t_npwp.load({
                params: {kodePelanggan: Ext.getCmp('id_txt_kd_pelanggan').getValue()},
            });
            this.getForm().reset();
            win_add_npwp.hide();
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
    Ext.reg('npwp_add_form', npwp_add_form.Form);
    /**
     * form add npwp
     */
    var win_add_npwp = new Ext.Window({
        id: 'id_win_add_npwp',
        closeAction: 'hide',
        width: 450,
        height: 350,
        layout: 'fit',
        border: false,
        items: {
            id: 'id_npwp_add_form',
            xtype: 'npwp_add_form'
        },
        onHide: function() {
            Ext.getCmp('id_npwp_add_form').getForm().reset();
        }
    });
    var cbGrid = new Ext.grid.CheckboxSelectionModel();
    var cbGrid2 = new Ext.grid.CheckboxSelectionModel();
    // row actions
    var action_npwp_edit = new Ext.ux.grid.RowActions({
        header: 'Edit',
        autoWidth: false,
        locked: true,
        width: 50,
        actions: [{iconCls: 'icon-edit-record', qtip: 'Edit'}],
        widthIntercept: Ext.isSafari ? 4 : 2
    });
    var action_npwp_delete = new Ext.ux.grid.RowActions({
        header: 'Delete',
        autoWidth: false,
        locked: true,
        width: 50,
        actions: [{iconCls: 'icon-delete-record', qtip: 'Edit'}],
        widthIntercept: Ext.isSafari ? 4 : 2
    });
    action_npwp_edit.on('action', function(grid, record, action, row, col) {
        var kdPelanggan = record.get('kd_pelanggan');
        var kdNpwp = record.get('kd_npwp');
        var namaNpwp = record.get('nama_npwp');
        var alamatNpwp = record.get('alamat_npwp');
        var noNpwp = record.get('no_npwp');
        var aktif = record.get('aktif');
        switch (action) {
            case 'icon-edit-record':
                editNpwp(kdPelanggan, kdNpwp, namaNpwp, alamatNpwp, noNpwp, aktif);
                break;
            case 'icon-delete-record':
                Ext.Msg.show({
                    title: 'Confirm',
                    msg: 'Are you sure delete selected row ?',
                    buttons: Ext.Msg.YESNO,
                    fn: function(btn) {
                        if (btn == 'yes') {
                            Ext.Ajax.request({
                                url: '<?= site_url("setting_npwp_pelanggan_controller/finalDelete") ?>',
                                method: 'POST',
                                params: {
                                    kdPelanggan: kdPelanggan,
                                    kdNpwp: kdNpwp
                                },
                                callback: function(opt, success, responseObj) {
                                    var de = Ext.util.JSON.decode(responseObj.responseText);
                                    if (de.success == true) {
                                        snp_grid_npwp.store.reload();
//                                        store_t_npwp.load();
//                                        store_t_npwp.load({
//                                            params: {
//                                                fieldId: kdPelanggan,
//                                                start: STARTPAGE,
//                                                limit: ENDPAGE
//                                            }
//                                        });
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
    //store_t_npwp.load();
    var snp_grid_npwp = new Ext.grid.GridPanel({
        frame: true,
        border: true,
        stripeRows: true,
        sm: cbGrid2,
        store: store_t_npwp,
        loadMask: true,
        title: 'NPWP Pelanggan',
        style: 'margin:0 auto;',
        height: 250,
        columns: [action_npwp_edit,
            action_npwp_delete,
            {
                header: "Kode Pelanggan",
                dataIndex: 'kd_pelanggan',
                sortable: true,
                width: 150
            }, {
                header: "Kode NPWP",
                dataIndex: 'kd_npwp',
                sortable: true,
                width: 70
            }, {
                header: "Nama NPWP",
                dataIndex: 'nama_npwp',
                sortable: true,
                width: 250
            }, {
                header: "No NPWP",
                dataIndex: 'no_npwp',
                sortable: true,
                width: 250
            }, {
                header: "Alamat NPWP",
                dataIndex: 'alamat_npwp',
                sortable: true,
                width: 250
            }, {
                header: "Aktif",
                dataIndex: 'aktif',
                sortable: true,
                width: 250
            }],
        plugins: [action_npwp_edit, action_npwp_delete],
        tbar: toolbar_npwp_grid,
        bbar: new Ext.PagingToolbar({
            pageSize: 10,
            store: store_t_npwp,
            displayInfo: true
        })
    });
    //store_t_npwp.reload();
    /**
     * deklarasi grid
     */
    store_t_pelanggan_dist.load();
    var snp_grid_pelanggan = new Ext.grid.GridPanel({
        frame: true,
        border: true,
        stripeRows: true,
        sm: cbGrid,
        store: store_t_pelanggan_dist,
        loadMask: true,
        title: 'Pelanggan',
        style: 'margin:0 auto;', height: 250,
        columns: [
            {
                header: "Kode Pelanggan",
                dataIndex: 'kd_pelanggan',
                sortable: true,
                width: 150
            }, {
                header: "Nama Pelangaan",
                dataIndex: 'nama_pelanggan',
                sortable: true,
                width: 250
            }, {
                header: "Alamat Kirim",
                dataIndex: 'alamat_kirim',
                sortable: true,
                width: 150
            }, {
                header: "Alamat Tagih",
                dataIndex: 'alamat_tagih',
                sortable: true,
                width: 70
            }, {
                header: "NPWP",
                dataIndex: 'npwp',
                sortable: true,
                width: 250
            }, {
                header: "Alamat NPWP",
                dataIndex: 'alamat_npwp',
                sortable: true,
                width: 250
            }, {
                header: "Kode Propinsi",
                dataIndex: 'kd_propinsi',
                sortable: true,
                width: 250
            }, {
                header: "Kode Kota",
                dataIndex: 'kd_kota',
                sortable: true,
                width: 250
            }, {
                header: "Kode Kecamatan",
                dataIndex: 'kd_kecamatan',
                sortable: true,
                width: 250
            }, {
                header: "Kode Post",
                dataIndex: 'kd_pos',
                sortable: true,
                width: 250
            }, {
                header: "No Telp",
                dataIndex: 'no_telp',
                sortable: true,
                width: 250
            }, {
                header: "Nama Pic",
                dataIndex: 'nama_pic',
                sortable: true,
                width: 250
            }, {
                header: "No Telp Pic",
                dataIndex: 'no_telp_pic',
                sortable: true,
                width: 250
            }, {
                header: "Aktif",
                dataIndex: 'aktif',
                sortable: true,
                width: 250
            }, {
                header: "Top Dist",
                dataIndex: 'top_dist',
                sortable: true,
                width: 250
            }, {
                header: "Limit Dist",
                dataIndex: 'limit_dist',
                sortable: true,
                width: 250
            }, {
                header: "Tipe",
                dataIndex: 'tipe',
                sortable: true,
                width: 250
            }, {
                header: "Kode Cabang",
                dataIndex: 'kode_cabang',
                sortable: true,
                width: 250
            }, {
                header: "Kode Kalurhan",
                dataIndex: 'kode_kalurahan',
                sortable: true,
                width: 250
            }, {
                header: "Is Pkp",
                dataIndex: 'is_pkp',
                sortable: true,
                width: 250
            }, {
                header: "Np Fax",
                dataIndex: 'no_fax',
                sortable: true,
                width: 250
            }, {
                header: "Email",
                dataIndex: 'email',
                sortable: true,
                width: 250
            }, {
                header: "Kode Sales",
                dataIndex: 'kd_sales',
                sortable: true,
                width: 250
            }, {
                header: "Kode NPWP",
                dataIndex: 'kd_npwp',
                sortable: true,
                width: 250
            }],
        listeners: {
            'rowclick': function() {
                var sm = snp_grid_pelanggan.getSelectionModel();
                var sel = sm.getSelections();
                Ext.getCmp('id_txt_kd_pelanggan').setValue(sel[0].get('kd_pelanggan'));
                //Ext.getCmp('id_txt_kd_npwp').setValue(sel[0].get('kd_npwp'));
                store_t_npwp.reload({
                    params: {kodePelanggan: sel[0].get('kd_pelanggan')}
                });
            }
        },
        tbar: toolbar_search_pelanggan,
        bbar: new Ext.PagingToolbar({
            pageSize: 10,
            store: store_t_pelanggan_dist,
            displayInfo: true
        })
    });
    function editNpwp(kdPelanggan, kdNpwp, namaNpwp, alamatNpwp, noNpwp, aktif) {
        Ext.getCmp('btn_submit_npwp').setText('update');
        win_add_npwp.setTitle('View Data Form');
        if (kdPelanggan != '' && kdNpwp != '') {
            Ext.getCmp('id_txt_kd_pelanggan').setValue(kdPelanggan);
            Ext.getCmp('id_txt_kd_npwp').setValue(kdNpwp);
            Ext.getCmp('id_txt_kd_npwp').setReadOnly(true);
            Ext.getCmp('id_txt_kd_npwp').addClass('readonly-input');
            //Ext.getCmp('id_txt_nama_npwp').setReadOnly(true);
            //Ext.getCmp('id_txt_nama_npwp').addClass('readonly-input');
            Ext.getCmp('id_txt_nama_npwp').setValue(namaNpwp);
            Ext.getCmp('id_txt_alamat_npwp').setValue(alamatNpwp);
            Ext.getCmp('id_txt_no_npwp').setValue(noNpwp);
            Ext.getCmp('id_check_aktif_npwp').setValue(aktif);
        }
//        Ext.getCmp('id_npwp_add_form').getForm().load({
//            failure: function(form, action) {
//                var de = Ext.util.JSON.decode(action.response.responseText);
//                Ext.Msg.show({
//                    title: 'Error',
//                    msg: de.errMsg,
//                    modal: true,
//                    icon: Ext.Msg.ERROR,
//                    buttons: Ext.Msg.OK,
//                    fn: function(btn) {
//                        if (btn == 'ok' && de.errMsg == 'Session Expired') {
//                            window.location = '<?= site_url("auth/login") ?>';
//                        }
//                    }
//                });
//            }
//        });
        win_add_npwp.show();
    }

    function updateRecordNpwp() {
        Ext.getCmp('id_npwp_add_form').getForm().submit({
            url: '<?= site_url("setting_npwp_pelanggan_controller/finalUpdateOrInsert") ?>',
            success: function(form, action) {
                Ext.Msg.show({
                    title: 'Success',
                    msg: 'Form submitted successfully',
                    modal: true,
                    icon: Ext.Msg.INFO,
                    buttons: Ext.Msg.OK
                });
                store_t_npwp.reload();
                win_add_npwp.hide();
            },
            failure: this.onFailure,
            params: {
                cmd: 'update'
            },
            waitMsg: 'Updating Data...'
        });
    }

    Ext.ns('setting_npwp_pelanggan');
    var setting_npwp_pelanggan = new Ext.FormPanel({
        id: 'setting_npwp_pelanggan',
        border: false,
        frame: true,
        autoScroll: true,
        bodyStyle: 'padding:5px;',
        items: [snp_grid_pelanggan
                    , snp_grid_npwp
        ]
    });

    npwp_add_form.Form.on('afterrender', function() {
        Ext.getCmp('id_npwp_add_form').getForm().load({
            url: '<?= site_url("setting_npwp_pelanggan_controller/getKodeNpwp") ?>',
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
    });

</script>
