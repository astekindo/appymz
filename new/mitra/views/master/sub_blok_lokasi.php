<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">
    /* START FORM */

    // combobox lokasi
    var str_cblokasi = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_lokasi', 'nama_lokasi'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("blok_lokasi/get_all") ?>',
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

    var _cblokasi = new Ext.form.ComboBox({
        fieldLabel: 'Nama Lokasi <span class="asterix">*</span>',
        id: 'id__cblokasi',
        store: str_cblokasi,
        valueField: 'kd_lokasi',
        displayField: 'nama_lokasi',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_lokasi',
        emptyText: 'Pilih Lokasi',
        listeners: {
            select: function(combo, records) {
                var kd_cblokasi = this.getValue();
                cbblok.setValue();
                cbblok.store.proxy.conn.url = '<?= site_url("sub_blok_lokasi/get_blok") ?>/' + kd_cblokasi;
                cbblok.store.reload();
            }
        }
    });

    // combobox blok
    var strcbblok = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_blok', 'nama_blok'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("sub_blok_lokasi/get_blok") ?>',
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

    var cbblok = new Ext.form.ComboBox({
        fieldLabel: 'Nama Blok <span class="asterix">*</span>',
        id: 'id_cbblok',
        mode: 'local',
        store: strcbblok,
        valueField: 'kd_blok',
        displayField: 'nama_blok',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_blok',
        emptyText: 'Pilih Blok'
    });

    Ext.ns('subbloklokasiform');
    subbloklokasiform.Form = Ext.extend(Ext.form.FormPanel, {
        // defaults - can be changed from outside
        border: false,
        frame: true,
        labelWidth: 100,
        url: '<?= site_url("sub_blok_lokasi/update_row") ?>',
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
            subbloklokasiform.Form.superclass.constructor.call(this, config);
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
                        name: 'kd_lokasi'
                    }, {
                        xtype: 'hidden',
                        name: 'kd_blok'
                    }, {
                        xtype: 'hidden',
                        name: 'kd_sub_blok'
                    }, _cblokasi, cbblok, {
                        type: 'textfield',
                        fieldLabel: 'Nama Sub Blok <span class="asterix">*</span>',
                        name: 'nama_sub_blok',
                        allowBlank: false,
                        id: 'id_nama_sub_blok',
                        maxLength: 40,
                        style: 'text-transform: uppercase',
                        anchor: '90%'
                    }, {
                        type: 'textfield',
                        fieldLabel: 'Nama Sub Blok2 <span class="asterix">*</span>',
                        name: 'nama_sub_blok2',
                        allowBlank: false,
                        id: 'id_nama_sub_blok2',
                        maxLength: 40,
                        style: 'text-transform: uppercase',
                        anchor: '90%'
                    }, {
                        xtype: 'numberfield',
                        fieldLabel: 'Kapasitas <span class="asterix">*</span>',
                        name: 'kapasitas',
                        allowBlank: false,
                        id: 'id_kapasitas',
                        maxLength: 11,
                        anchor: '90%'
                    }],
                buttons: [{
                        text: 'Submit',
                        id: 'btnsubmitsubbloklokasi',
                        formBind: true,
                        scope: this,
                        handler: this.submit
                    }, {
                        text: 'Reset',
                        id: 'btnresetsubbloklokasi',
                        scope: this,
                        handler: this.reset
                    }, {
                        text: 'Close',
                        id: 'btnCloseSubBlok',
                        scope: this,
                        handler: function() {
                            winaddsubbloklokasi.hide();
                        }
                    }]
            }; // eo config object
            // apply config
            Ext.apply(this, Ext.apply(this.initialConfig, config));

            // call parent
            subbloklokasiform.Form.superclass.initComponent.apply(this, arguments);

        } // eo function initComponent	
        ,
        onRender: function() {

            // call parent
            subbloklokasiform.Form.superclass.onRender.apply(this, arguments);

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
            var text = Ext.getCmp('btnsubmitsubbloklokasi').getText();
            if (text == 'Update') {
                Ext.Msg.show({
                    title: 'Confirm',
                    msg: 'Are you sure update selected row ?',
                    buttons: Ext.Msg.YESNO,
                    fn: function(btn) {
                        if (btn == 'yes') {
                            Ext.getCmp('id_formaddsubbloklokasi').getForm().submit({
                                url: Ext.getCmp('id_formaddsubbloklokasi').url,
                                scope: this,
                                success: Ext.getCmp('id_formaddsubbloklokasi').onSuccess,
                                failure: Ext.getCmp('id_formaddsubbloklokasi').onFailure,
                                params: {
                                    cmd: 'save'
                                },
                                waitMsg: 'Saving Data...'
                            });
                        }
                    }
                })
            } else {
                Ext.getCmp('id_formaddsubbloklokasi').getForm().submit({
                    url: Ext.getCmp('id_formaddsubbloklokasi').url,
                    scope: this,
                    success: Ext.getCmp('id_formaddsubbloklokasi').onSuccess,
                    failure: Ext.getCmp('id_formaddsubbloklokasi').onFailure,
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


            strsubbloklokasi.reload();
            Ext.getCmp('id_formaddsubbloklokasi').getForm().reset();
            winaddsubbloklokasi.hide();
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
    Ext.reg('formaddsubbloklokasi', subbloklokasiform.Form);

    var winaddsubbloklokasi = new Ext.Window({
        id: 'id_winaddsubbloklokasi',
        closeAction: 'hide',
        width: 450,
        height: 350,
        layout: 'fit',
        border: false,
        items: {
            id: 'id_formaddsubbloklokasi',
            xtype: 'formaddsubbloklokasi'
        },
        onHide: function() {
            Ext.getCmp('id_formaddsubbloklokasi').getForm().reset();
        }
    });

    /* START GRID */

    // data store
    var strsubbloklokasi = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'sub',
                'nama_sub',
                'nama_sub2',
                'kd_sub_blok',
                'kd_blok',
                'kd_lokasi',
                'nama_lokasi',
                'nama_blok',
                'nama_sub_blok',
                'kapasitas',
                'nama_sub_blok2',
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("sub_blok_lokasi/get_rows") ?>',
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

    // search field
    var searchsubbloklokasi = new Ext.app.SearchField({
        store: strsubbloklokasi,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        id: 'idsearchsubbloklokasi'
    });

    // top toolbar
    var tbsubbloklokasi = new Ext.Toolbar({
        items: [{
                text: 'Add',
                icon: BASE_ICONS + 'add.png',
                onClick: function() {
                    str_cblokasi.load();
                    Ext.getCmp('id__cblokasi').setValue('');
                    Ext.getCmp('id_cbblok').setValue('');
                    Ext.getCmp('id_cbblok').setDisabled(false);
                    Ext.getCmp('id__cblokasi').setDisabled(false);
                    Ext.getCmp('btnresetsubbloklokasi').show();
                    Ext.getCmp('btnsubmitsubbloklokasi').setText('Submit');
                    winaddsubbloklokasi.setTitle('Add Form');
                    winaddsubbloklokasi.show();
                }
            }, '-', searchsubbloklokasi]
    });

    // checkbox grid
    var cbGrid = new Ext.grid.CheckboxSelectionModel();

    // row actions
    var actionsubbloklokasi = new Ext.ux.grid.RowActions({
        header: 'Edit',
        autoWidth: false,
        width: 30,
        actions: [{iconCls: 'icon-edit-record', qtip: 'Edit'}],
        widthIntercept: Ext.isSafari ? 4 : 2
    });
    var actionsubbloklokasidel = new Ext.ux.grid.RowActions({
        header: 'Delete',
        autoWidth: false,
        width: 40,
        actions: [{iconCls: 'icon-delete-record', qtip: 'Delete'}],
        widthIntercept: Ext.isSafari ? 4 : 2
    });

    actionsubbloklokasi.on('action', function(grid, record, action, row, col) {
        var id___lokasi = record.get('kd_lokasi');
        var id___bloklokasi = record.get('kd_blok');
        var id_subbloklokasi = record.get('kd_sub_blok');
        var nama_lokasi = record.get('nama_lokasi');
        var nama_blok = record.get('nama_blok');
        switch (action) {
            case 'icon-edit-record':
                editsubbloklokasi(id___lokasi, id___bloklokasi, id_subbloklokasi, nama_lokasi, nama_blok);
                break;
            case 'icon-delete-record':
                Ext.Msg.show({
                    title: 'Confirm',
                    msg: 'Are you sure delete selected row ?',
                    buttons: Ext.Msg.YESNO,
                    fn: function(btn) {
                        if (btn == 'yes') {
                            Ext.Ajax.request({
                                url: '<?= site_url("sub_blok_lokasi/delete_row") ?>',
                                method: 'POST',
                                params: {
                                    kd_lokasi: id___lokasi,
                                    kd_blok: id___bloklokasi,
                                    kd_sub_blok: id_subbloklokasi
                                },
                                callback: function(opt, success, responseObj) {
                                    var de = Ext.util.JSON.decode(responseObj.responseText);
                                    if (de.success == true) {
                                        strsubbloklokasi.reload();
                                        strsubbloklokasi.load({
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
                break;

        }
    });

    // grid
    var subbloklokasi = new Ext.grid.EditorGridPanel({
        //id: 'id-subbloklokasi-gridpanel',
        id: 'subbloklokasi',
        frame: true,
        border: true,
        stripeRows: true,
        sm: cbGrid,
        store: strsubbloklokasi,
        loadMask: true,
        style: 'margin:0 auto;',
        height: 450,
        columns: [actionsubbloklokasi, {
                header: "Kode Sub Blok",
                dataIndex: 'sub',
                sortable: true,
                width: 90
            }, {
                header: "Nama Sub Blok",
                dataIndex: 'nama_sub',
                sortable: true,
                width: 200
            },{
                header: "Nama Sub Blok2",
                dataIndex: 'nama_sub2',
                sortable: true,
                width: 300
            }, {
                header: "Kapasitas",
                dataIndex: 'kapasitas',
                sortable: true,
                width: 75
            }],
        plugins: [actionsubbloklokasi],
        listeners: {
            'rowdblclick': function() {
                var sm = subbloklokasi.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    editsubbloklokasi(sel[0].get('kd_lokasi'), sel[0].get('kd_blok'), sel[0].get('kd_sub_blok'),
                            sel[0].get('nama_lokasi'), sel[0].get('nama_blok')
                            );
                }
            }
        },
        tbar: tbsubbloklokasi,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strsubbloklokasi,
            displayInfo: true
        })
    });
    /**
     var subbloklokasipanel = new Ext.FormPanel({
     id: 'subbloklokasi',
     border: false,
     frame: false,
     autoScroll:true,	
     items: [subbloklokasi]
     });
     **/
    function editsubbloklokasi(id___lokasi, id___bloklokasi, id_subbloklokasi, nama_lokasi, nama_blok) {
        str_cblokasi.load();
        Ext.getCmp('id__cblokasi').setValue(nama_lokasi);
        Ext.getCmp('id_cbblok').setValue(nama_blok);
        Ext.getCmp('id__cblokasi').setDisabled(true);
        Ext.getCmp('id_cbblok').setDisabled(true);
        Ext.getCmp('btnresetsubbloklokasi').hide();
        Ext.getCmp('btnsubmitsubbloklokasi').setText('Update');
        winaddsubbloklokasi.setTitle('Edit Form');
        Ext.getCmp('id_formaddsubbloklokasi').getForm().load({
            url: '<?= site_url("sub_blok_lokasi/get_row") ?>',
            params: {
                id: id___lokasi,
                id1: id___bloklokasi,
                id2: id_subbloklokasi,
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
        winaddsubbloklokasi.show();
    }

    function deletesubbloklokasi() {
        var sm = subbloklokasi.getSelectionModel();
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
                            data = data + sel[i].get('kd_lokasi') + '-' + sel[i].get('kd_blok') + '-' + sel[i].get('kd_sub_blok') + ';';
                        }

                        Ext.Ajax.request({
                            url: '<?= site_url("sub_blok_lokasi/delete_rows") ?>',
                            method: 'POST',
                            params: {
                                postdata: data
                            },
                            callback: function(opt, success, responseObj) {
                                var de = Ext.util.JSON.decode(responseObj.responseText);
                                if (de.success == true) {
                                    strsubbloklokasi.reload();
                                    strsubbloklokasi.load({
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
        else {
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
