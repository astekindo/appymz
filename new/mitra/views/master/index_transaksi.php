<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">
    /**
     * deklarasi store grid index transaksi
     */
    var it_storeIndexTransaksi = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_index',
                'nama_index',
                'keterangan'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("index_transaksi/finalGetRows") ?>',
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
     * load store grid index transaksi
     */
    it_storeIndexTransaksi.load();

// search field
    var searchIndexTransaksi_it = new Ext.app.SearchField({
        store: it_storeIndexTransaksi,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        id: 'id_searchindex_transaksi'
    });

    /**
     * deklarasi top toolbar
     */
    var topToolbarIndexTransaksi = new Ext.Toolbar({
        items: [{
                text: 'Add',
                icon: BASE_ICONS + 'add.png',
                onClick: function() {
                    Ext.getCmp('id_index_transaksi_form_it').getForm().reset();
                    Ext.Ajax.request({
                        url: '<?= site_url("index_transaksi/generateKodeIndex") ?>',
                        method: 'POST',
                        callback: function(opt, success, responseObj) {
                            var de = Ext.util.JSON.decode(responseObj.responseText);
                            if (de.success == true) {
                                Ext.getCmp('it_id_kd_index').setValue(de.data.it_txt_kd_index);
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
                    Ext.getCmp('id_btn_submit_index_transaksi').setText('save');
                    windowIndexTransaksi_it.show();
                }
            }, '-', searchIndexTransaksi_it]
    });

    /**
     * deklarasi grid index transaksi row action
     */
    var action_index_transaksi_edit_it = new Ext.ux.grid.RowActions({
        //locked: true,
        header: 'Edit',
        autoWidth: false,
        width: 30,
        actions: [{iconCls: 'icon-edit-record', qtip: 'Edit'}],
        widthIntercept: Ext.isSafari ? 4 : 2
    });
    var action_index_transaksi__delete_it = new Ext.ux.grid.RowActions({
        //locked: true,
        header: 'Delete',
        autoWidth: false,
        width: 40,
        actions: [{iconCls: 'icon-delete-record', qtip: 'Delete'}],
        widthIntercept: Ext.isSafari ? 4 : 2
    });

    /**
     * menambahkan event pada row action
     */
    action_index_transaksi_edit_it.on('action', function(grid, record, action, row, col) {
        var kdIndex = record.get('kd_index');
        var namaIndex = record.get('nama_index');
        var keterangan = record.get('keterangan');
        switch (action) {
            case 'icon-edit-record':
                showEditIndexTransaksiForm(kdIndex, namaIndex, keterangan);
                break;
        }
    });

    action_index_transaksi__delete_it.on('action', function(grid, record, action, row, col) {
        var kdIndex = record.get('kd_index');
        switch (action) {
            case 'icon-delete-record':
                deleteIndexTransaksi(kdIndex);
                break;
        }
    });
    /**
     * deklarasi form index transaksi
     */
    Ext.ns('indexTransaksiForm_it');
    indexTransaksiForm_it.Form = Ext.extend(Ext.form.FormPanel, {
        // defaults - can be changed from outside
        border: false,
        frame: true,
        labelWidth: 100,
        waitMsg: 'Loading...',
        url: '<?= site_url("index_transaksi/finalInsertAndUpdate") ?>',
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
            indexTransaksiForm_it.Form.superclass.constructor.call(this, config);
        },
        initComponent: function() {

            // hard coded - cannot be changed from outsid
            var config = {
                defaultType: 'textfield',
                defaults: {labelSeparator: ''},
                monitorValid: true,
                autoScroll: false // ,buttonAlign:'right'
                ,
                defaults:{anchor: '95%'},
                items: [{
                        xtype: 'textfield',
                        name: 'it_txt_kd_index',
                        id: 'it_id_kd_index',
                        fieldLabel: 'Kode Index',
                        readOnly: true,
                        fieldClass: 'readonly-input'
                    }, {
                        xtype: 'textfield',
                        name: 'it_txt_nama_index',
                        id: 'it_id_nama_index',
                        fieldLabel: 'Nama Index',
                        allowBlank: false
                    }, {
                        xtype: 'textarea',
                        name: 'it_txt_keterangan',
                        id: 'it_id_keterangan',
                        fieldLabel: 'Keterangan Index Transaksi',
                        allowBlank: false
                    }],
                buttons: [{
                        text: 'submit',
                        id: 'id_btn_submit_index_transaksi',
                        formBind: true,
                        scope: this,
                        handler: this.submit
                    }, {
                        text: 'reset',
                        id: 'id_btn_reset_index_transaksi',
                        scope: this,
                        handler: this.reset
                    }, {
                        text: 'close',
                        id: 'id_btn_close_index_transaksi',
                        scope: this,
                        handler: function() {
                            windowIndexTransaksi_it.hide();
                        }
                    }]
            }; // eo config object
            // apply config
            Ext.apply(this, Ext.apply(this.initialConfig, config));

            // call parent
            indexTransaksiForm_it.Form.superclass.initComponent.apply(this, arguments);

        } // eo function initComponent	
        ,
        onRender: function() {

            // call parent
            indexTransaksiForm_it.Form.superclass.onRender.apply(this, arguments);

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
            var text = Ext.getCmp('id_btn_submit_index_transaksi').getText();
            if (text == 'update') {
                Ext.Msg.show({
                    title: 'Confirm',
                    msg: 'Are you sure update selected row ?',
                    buttons: Ext.Msg.YESNO,
                    fn: function(btn) {
                        if (btn == 'yes') {
                            Ext.getCmp('id_index_transaksi_form_it').getForm().submit({
                                url: Ext.getCmp('id_index_transaksi_form_it').url,
                                scope: this,
                                success: Ext.getCmp('id_index_transaksi_form_it').onSuccess,
                                failure: Ext.getCmp('id_index_transaksi_form_it').onFailure,
                                params: {
                                    cmd: 'update'
                                },
                                waitMsg: 'Updating Data...'
                            });
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


            it_storeIndexTransaksi.reload();
            Ext.getCmp('id_index_transaksi_form_it').getForm().reset();
            windowIndexTransaksi_it.hide();
        } // eo function onSuccess
        ,
        onFailure: function(form, action) {

            var fe = Ext.util.JSON.decode(action.response.responseText);
            Ext.getCmp('id_index_transaksi_form_it').showError(fe.errMsg || '');


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
     * register form
     */
    Ext.reg('indexTransaksiForm_it', indexTransaksiForm_it.Form);


    /**
     * window index transaksi
     */
    var windowIndexTransaksi_it = new Ext.Window({
        id: 'id_window_index_transaksi',
        closeAction: 'hide',
        width: 450,
        height: 350,
        layout: 'fit',
        border: false,
        frame: true,
        items: {
            id: 'id_index_transaksi_form_it',
            xtype: 'indexTransaksiForm_it'
        },
        onHide: function() {
            Ext.getCmp('id_index_transaksi_form_it').getForm().reset();
        }
    });

    /**
     * deklarasi selection model
     */
    var cbGridIndexTransaksi = new Ext.grid.CheckboxSelectionModel();
    /**
     * deklarasi grid index transaksi
     */
    var gridIndexTransaksi_it = new Ext.grid.EditorGridPanel({
        id: 'id_grid_index_transaksi',
        frame: false,
        border: false,
        stripeRows: true,
        sm: cbGridIndexTransaksi,
        store: it_storeIndexTransaksi,
        loadMask: true,
        title: 'Data Index Transaksi',
        style: 'margin:0 auto;',
        height: 450,
        view: new Ext.ux.grid.LockingGridView(),
        colModel: new Ext.ux.grid.LockingColumnModel([
            action_index_transaksi_edit_it,
            action_index_transaksi__delete_it,
            {
                header: "Kode Index",
                dataIndex: 'kd_index',
                sortable: true,
                //locked: true,
                width: 90
            }, {
                header: "Nama Index",
                dataIndex: 'nama_index',
                sortable: true,
                width: 300
            }, {
                header: "Keterangan",
                dataIndex: 'keterangan',
                sortable: true,
                width: 300
            }]),
        plugins: [action_index_transaksi_edit_it,
            action_index_transaksi__delete_it
                    //, multisearchindex_transaksi
        ],
        listeners: {
            'rowdblclick': function() {
                var sm = gridIndexTransaksi_it.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    //editindex_transaksi(sel[0].get('kd_index'));
                }
            }
        },
        tbar: topToolbarIndexTransaksi,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: it_storeIndexTransaksi,
            displayInfo: true
        })
    });

    /**
     * deklarasi form panel
     */
    Ext.ns('index_transaksi');
    var index_transaksi_form_panel = new Ext.FormPanel({
        id: 'index_transaksi',
        border: false,
        frame: true,
        autoScroll: true,
        bodyStyle: 'padding:0px;',
        items: [
            gridIndexTransaksi_it
        ]
    });

    function deleteIndexTransaksi(kdIndex) {
        Ext.Msg.show({
            title: 'Confirm',
            msg: 'Are you sure delete selected row ?',
            buttons: Ext.Msg.YESNO,
            fn: function(btn) {
                if (btn == 'yes') {
                    Ext.Ajax.request({
                        url: '<?= site_url("index_transaksi/finalDelete") ?>',
                        method: 'POST',
                        params: {
                            //kd_index=parameter respon data yang didapatkan dari row
                            it_txt_kd_index: kdIndex
                        },
                        callback: function(opt, success, responseObj) {
                            var de = Ext.util.JSON.decode(responseObj.responseText);
                            if (de.success == true) {
                                it_storeIndexTransaksi.reload();
                                it_storeIndexTransaksi.load({
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
    }


    function showEditIndexTransaksiForm(kdIndex, namaIndex, keterangan) {
        Ext.getCmp('id_btn_reset_index_transaksi').hide();
        Ext.getCmp('id_btn_submit_index_transaksi').setText('update');
        Ext.getCmp('it_id_kd_index').setValue(kdIndex);
        Ext.getCmp('it_id_nama_index').setValue(namaIndex);
        Ext.getCmp('it_id_keterangan').setValue(keterangan);
        windowIndexTransaksi_it.setTitle('Edit Form');
        windowIndexTransaksi_it.show();
    }


</script>
