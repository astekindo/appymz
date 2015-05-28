<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script type="text/javascript">

// combobox cabang
    var strMCComboCabang = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_cabang', 'nama_cabang'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("master_collection/get_cabang") ?>',
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

    var comboCabang = new Ext.form.ComboBox({
        fieldLabel: 'cabang <span class="asterix">*</span>',
        id: 'id_mc_cbcabang',
        store: strMCComboCabang,
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
    var strMCComboArea = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_area', 'nama_area'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("master_collection/get_area") ?>',
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

    var comboArea = new Ext.form.ComboBox({
        fieldLabel: 'area <span class="asterix">*</span>',
        id: 'id_mc_cbarea',
        store: strMCComboArea,
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
    Ext.ns('MasterCollectionForm');
    MasterCollectionForm.Form = Ext.extend(Ext.form.FormPanel, {
        // defaults - can be changed from outside
        border: false,
        frame: true,
        labelWidth: 100,
        waitMsg: 'Loading...',
        url: '<?= site_url("master_collection/update_row") ?>',
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
            MasterCollectionForm.Form.superclass.constructor.call(this, config);
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
                        name: 'kd_collector'
                    }, {
                        type: 'textfield',
                        fieldLabel: 'Kd Collection',
                        name: 'kd_collection',
                        //allowBlank: false,
                        id: 'id_mc_kd_collection',
                        anchor: '90%',
                        maxLength: 40,
                        fieldClass:'readonly-input',
                        readOnly:true
                    }, {
                        type: 'textfield',
                        fieldLabel: 'Nama Collection <span class="asterix">*</span>',
                        name: 'nama_collector',
                        allowBlank: false,
                        id: 'id_mc_nama_collector',
                        anchor: '90%',
                        maxLength: 40
                    },
                    comboCabang,
                    {
                        xtype: 'textarea',
                        fieldLabel: 'Alamat <span class="asterix">*</span>',
                        name: 'alamat',
                        allowBlank: false,
                        id: 'id_mc_alamat',
                        maxLength: 255,
                        anchor: '90%'
                    }, {
                        type: 'textfield',
                        fieldLabel: 'Email <span class="asterix">*</span>',
                        name: 'email',
                        allowBlank: false,
                        id: 'id_mc_email',
                        anchor: '90%',
                        maxLength: 40
                    }, {
                        type: 'textfield',
                        fieldLabel: 'Pin BB <span class="asterix">*</span>',
                        name: 'pin_bb',
                        allowBlank: false,
                        id: 'id_mc_pin_bb',
                        anchor: '90%',
                        maxLength: 40
                    }, {
                        xtype: 'textfield',
                        fieldLabel: 'No Telepon <span class="asterix">*</span>',
                        name: 'no_telp',
                        allowBlank: false,
                        id: 'id_mc_notel',
                        maxLength: 15,
                        anchor: '90%'
                    }, {
                        xtype: 'textfield',
                        fieldLabel: 'No Telepon (Alternatif) ',
                        name: 'no_telp2',
                        allowBlank: true,
                        id: 'id_mc_notel2',
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
                                id: 'id_mc_statY'
                            }, {
                                boxLabel: 'Tidak',
                                name: 'status',
                                inputValue: '0',
                                id: 'id_mc_statT'
                            }]
                    }
                ],
                buttons: [
                    {
                        text: 'Submit',
                        id: 'id_mc_submit',
                        formBind: true,
                        scope: this,
                        handler: this.submit
                    }, {
                        text: 'Reset',
                        id: 'id_mc_reset',
                        scope: this,
                        handler: this.reset
                    }, {
                        text: 'Close',
                        id: 'id_mc_close',
                        scope: this,
                        handler: function() {
                            MstCollection_WinAdd.hide();
                        }
                    }
                ]
            }; // eo config object
            // apply config
            Ext.apply(this, Ext.apply(this.initialConfig, config));

            // call parent
            MasterCollectionForm.Form.superclass.initComponent.apply(this, arguments);

        }, // eo function initComponent
        onRender: function() {

            // call parent
            MasterCollectionForm.Form.superclass.onRender.apply(this, arguments);

            // set wait message target
            this.getForm().waitMsgTarget = this.getEl();

            // loads form after initial layout
            // this.on('afterlayout', this.onLoadClick, this, {single:true});

        }, // eo function onRender
        reset: function() {
            this.getForm().reset();
        },
        submit: function() {
            var text = Ext.getCmp('id_mc_submit').getText();
            if (text == 'Update') {
                Ext.Msg.show({
                    title: 'Confirm',
                    msg: 'Are you sure update selected row ?',
                    buttons: Ext.Msg.YESNO,
                    fn: function(btn) {
                        if (btn == 'yes') {
                            Ext.getCmp('id_mc_formaddcollection').getForm().submit({
                                url: Ext.getCmp('id_mc_formaddcollection').url,
                                scope: this,
                                success: Ext.getCmp('id_mc_formaddcollection').onSuccess,
                                failure: Ext.getCmp('id_mc_formaddcollection').onFailure,
                                params: {
                                    cmd: 'save'
                                },
                                waitMsg: 'Saving Data...'
                            });
                        }
                    }
                })
            } else {
                Ext.getCmp('id_mc_formaddcollection').getForm().submit({
                    url: Ext.getCmp('id_mc_formaddcollection').url,
                    scope: this,
                    success: Ext.getCmp('id_mc_formaddcollection').onSuccess,
                    failure: Ext.getCmp('id_mc_formaddcollection').onFailure,
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
            strMasterCollection.reload();
            Ext.getCmp('id_mc_formaddcollection').getForm().reset();
            MstCollection_WinAdd.hide();
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
    Ext.reg('formaddCollection', MasterCollectionForm.Form);

    var MstCollection_WinAdd = new Ext.Window({
        id: 'id_MstCollection_WinAdd',
        closeAction: 'hide',
        width: 450,
        height: 450,
        layout: 'fit',
        border: false,
        items: {
            id: 'id_mc_formaddcollection',
            xtype: 'formaddCollection'
        },
        onHide: function() {
            Ext.getCmp('id_mc_formaddcollection').getForm().reset();
        }
    });

    /* START GRID */

// data store
    var strMasterCollection = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'kd_collector',
                'nama_collector',
                'alamat',
                'no_telp',
                'no_telp2',
                'kd_cabang',
                'nama_cabang',
                'kd_area',
                'nama_area',
                'status'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("master_collection/get_rows") ?>',
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
    strMasterCollection.load();

// search field
    var searchCollection = new Ext.app.SearchField({
        store: strMasterCollection,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        id: 'idsearchCollection'
    });

// top toolbar
    var tbCollection = new Ext.Toolbar({
        items: [{
                text: 'Add',
                icon: BASE_ICONS + 'add.png',
                onClick: function() {
                    Ext.getCmp('id_mc_reset').show();
                    Ext.getCmp('id_mc_submit').setText('Submit');
                    //Ext.getCmp('id_mc_kd_collection').removeClass('readonly-input');
                    Ext.getCmp('id_mc_kd_collection').setValue('');
                    MstCollection_WinAdd.setTitle('Add Form');
                    MstCollection_WinAdd.show();
                }
            }, '-', searchCollection]
    });

// checkbox grid
    var cbGrid = new Ext.grid.CheckboxSelectionModel();

// row actions
    var actionCollection = new Ext.ux.grid.RowActions({
        header: 'Edit',
        autoWidth: false,
        width: 30,
        actions: [{iconCls: 'icon-edit-record', qtip: 'Edit'}],
        widthIntercept: Ext.isSafari ? 4 : 2
    });

    actionCollection.on('action', function(grid, record, action, row, col) {
        var kd_collector = record.get('kd_collector');
        switch (action) {
            case 'icon-edit-record':
                editCollection(kd_collector);
                break;
            case 'icon-delete-record':
                deleteCollection();
                break;

        }
    });

    var multisearchCollection = new Ext.ux.grid.Search({
        iconCls: 'icon-zoom'
                //,readonlyIndexes:['note']
                //,disableIndexes:['pctChange']
        , minChars: 3
        , autoFocus: true
        , width: 250
    });

// grid

    var Collection = new Ext.grid.EditorGridPanel({
        id: 'snc_master_collection',
        frame: true,
        border: true,
        stripeRows: true,
        sm: cbGrid,
        store: strMasterCollection,
        //closable:true,
        loadMask: true,
        style: 'margin:0 auto;',
        // height: 450,
        columns: [actionCollection,
            {
                header: "Kode Collection",
                dataIndex: 'kd_collector',
                sortable: true,
                width: 100
            }, {
                header: "Nama Collection",
                dataIndex: 'nama_collector',
                sortable: true,
                width: 200
            }, {
                header: "Alamat",
                dataIndex: 'alamat',
                sortable: true,
                width: 300
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
                header: "Cabang",
                dataIndex: 'nama_cabang',
                sortable: true,
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
        plugins: [actionCollection, multisearchCollection],
        listeners: {
            'rowdblclick': function() {
                var sm = Collection.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    console.log(sel[0].get('kd_collector'));
                    editCollection(sel[0].get('kd_collector'));
                }
            }
        },
        tbar: tbCollection,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strMasterCollection,
            displayInfo: true
        })
    });

    function editCollection(kd_collector) {
        strMCComboArea.load();
        strMCComboCabang.load();
        Ext.getCmp('id_mc_reset').hide();
        Ext.getCmp('id_mc_submit').setText('Update');
        Ext.getCmp('id_mc_kd_collection').setValue(kd_collector);
        MstCollection_WinAdd.setTitle('Edit Form');
        Ext.getCmp('id_mc_formaddcollection').getForm().load({
            url: '<?= site_url("master_collection/get_row") ?>',
            params: {
                kd_collector: kd_collector,
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
        MstCollection_WinAdd.show();
    }

    function deleteCollection() {
        var sm = Collection.getSelectionModel();
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
                            data = data + sel[i].get('kd_collector') + ';';
                        }

                        Ext.Ajax.request({
                            url: '<?= site_url("master_collection/delete_rows") ?>',
                            method: 'POST',
                            params: {
                                postdata: data
                            },
                            callback: function(opt, success, responseObj) {
                                var de = Ext.util.JSON.decode(responseObj.responseText);
                                if (de.success == true) {
                                    strMasterCollection.reload();
                                    strMasterCollection.load({
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
