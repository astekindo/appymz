<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">

    // input supplier
    var strcbNamaSupplier = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_supplier', 'nama_supplier'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("master_supplier/get_rows") ?>',
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

    var cbNamaSupplier = new Ext.form.ComboBox({
        fieldLabel: 'Nama Supplier <span class="asterix">*</span>',
        id: 'id_nama_supplier',
        triggerAction: 'query',
        store: strcbNamaSupplier,
        valueField: 'nama_supplier',
        displayField: 'nama_supplier',
        // typeAhead: true,
        allowBlank: false,
        width: 170,
        anchor: '90%',
        hiddenName: 'nama_supplier',
        style: 'text-transform: uppercase',
        minChars: 1,
        hideTrigger: true,
    });


    /* START FORM */
    Ext.ns('mastersupplierform');
    mastersupplierform.Form = Ext.extend(Ext.form.FormPanel, {
        // defaults - can be changed from outside
        border: false,
        frame: true,
        labelWidth: 100,
        url: '<?= site_url("master_supplier/update_row") ?>',
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
            mastersupplierform.Form.superclass.constructor.call(this, config);
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
                        name: 'kd_supplier'
                    }, cbNamaSupplier
                            // {
                            // type: 'textfield',
                            // fieldLabel: 'Nama Supplier <span class="asterix">*</span>',
                            // name: 'nama_supplier',
                            // allowBlank: false,
                            // id: 'id_nama_supplier',
                            // maxLength: 255,
                            // anchor: '90%'                
                            // }
                            , {
                                type: 'textfield',
                                fieldLabel: 'Alias <span class="asterix">*</span>',
                                name: 'alias_supplier',
                                id: 'id_alias_supplier',
                                maxLength: 5,
                                anchor: '50%'
                            }, {
                        type: 'textfield',
                        fieldLabel: 'PIC <span class="asterix">*</span>',
                        name: 'pic',
                        id: 'id_pic_supplier',
                        maxLength: 255,
                        anchor: '90%'
                    }, {
                        xtype: 'textarea',
                        fieldLabel: 'Alamat <span class="asterix">*</span>',
                        name: 'alamat',
                        id: 'id_alamat_supplier',
                        maxLength: 255,
                        anchor: '90%'
                    }, {
                        type: 'textfield',
                        fieldLabel: 'Telpon <span class="asterix">*</span>',
                        name: 'telpon',
                        id: 'id_telpon_supplier',
                        maxLength: 255,
                        anchor: '50%'
                    }, {
                        type: 'textfield',
                        fieldLabel: 'FAX <span class="asterix">*</span>',
                        name: 'fax',
                        id: 'id_fax_supplier',
                        maxLength: 255,
                        anchor: '50%'
                    }, {
                        vtype: 'email',
                        fieldLabel: 'Email <span class="asterix">*</span>',
                        name: 'email',
                        id: 'id_email_supplier',
                        maxLength: 255,
                        anchor: '90%'
                    }, {
                        type: 'textfield',
                        fieldLabel: 'NPWP <span class="asterix">*</span>',
                        name: 'npwp',
                        id: 'id_npwp_supplier',
                        maxLength: 20,
                        anchor: '90%',
                        listeners: {
                            'blur': function() {
                                var no_npwp = this.getValue();
                                if (no_npwp.length >= 15) {
                                    Ext.getCmp('id_npwp_supplier').setValue(no_npwp.substring(0, 2) + '.' +
                                            no_npwp.substring(2, 5) + '.' + no_npwp.substring(5, 8) + '.' + no_npwp.substring(8, 9) + '-' +
                                            no_npwp.substring(9, 12) + '.' + no_npwp.substring(12, 15));
                                }
                            }
                        }
                    }, {
                        xtype: 'radiogroup',
                        cls: 'x-check-group-alt',
                        fieldLabel: 'PKP <span class="asterix">*</span>',
                        name: 'pkp',
                        anchor: '90%',
                        allowBlank: false,
                        items: [{
                                boxLabel: 'Ya',
                                name: 'pkp',
                                inputValue: '1',
                                id: 'id_pkpY',
                            }, {
                                boxLabel: 'Tidak',
                                name: 'pkp',
                                inputValue: '0',
                                id: 'id_pkpT'
                            }]
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
                                id: 'id_statY',
                            }, {
                                boxLabel: 'Tidak',
                                name: 'status',
                                inputValue: '0',
                                id: 'id_statT'
                            }]
                    }, {
                        xtype: 'compositefield',
                        fieldLabel: 'Top',
                        items: [
                            {
                                xtype: 'numberfield',
                                name: 'top',
                                allowBlank: false,
                                id: 'id_top',
                                maxLength: 11,
                                style: 'text-align:right;',
                                value: 0,
                                width: 50
                            },
                            {
                                xtype: 'displayfield',
                                value: 'Hari'
                            }
                        ]
                    }, new Ext.form.Checkbox({
                        xtype: 'checkbox',
                        fieldLabel: '',
                        boxLabel: 'Update TOP ke Barang Supplier ??',
                        name: 'update_top',
                        id: 'update_top',
                        inputValue: '1',
                        autoLoad: true
                    }), new Ext.form.Checkbox({
                        xtype: 'checkbox',
                        fieldLabel: '',
                        boxLabel: 'Update Harga Jual Konsinyasi ??',
                        name: 'flag_hj_konsinyasi',
                        id: 'update_harga_jual_konsinyasi',
                        inputValue: '1',
                        autoLoad: true
                    })],
                buttons: [{
                        text: 'Submit',
                        id: 'btnsubmitmastersupplier',
                        formBind: true,
                        scope: this,
                        handler: this.submit
                    }, {
                        text: 'Reset',
                        id: 'btnresetmastersupplier',
                        scope: this,
                        handler: this.reset
                    }, {
                        text: 'Close',
                        id: 'btnClose',
                        scope: this,
                        handler: function() {
                            winaddmastersupplier.hide();
                        }
                    }]
            }; // eo config object
            // apply config
            Ext.apply(this, Ext.apply(this.initialConfig, config));

            // call parent
            mastersupplierform.Form.superclass.initComponent.apply(this, arguments);

        } // eo function initComponent  
        ,
        onRender: function() {

            // call parent
            mastersupplierform.Form.superclass.onRender.apply(this, arguments);

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
            var text = Ext.getCmp('btnsubmitmastersupplier').getText();
            if (text == 'Update') {
                Ext.Msg.show({
                    title: 'Confirm',
                    msg: 'Are you sure update selected row ?',
                    buttons: Ext.Msg.YESNO,
                    fn: function(btn) {
                        if (btn == 'yes') {
                            Ext.getCmp('id_formaddmastersupplier').getForm().submit({
                                url: Ext.getCmp('id_formaddmastersupplier').url,
                                scope: this,
                                success: Ext.getCmp('id_formaddmastersupplier').onSuccess,
                                failure: Ext.getCmp('id_formaddmastersupplier').onFailure,
                                params: {
                                    cmd: 'save'
                                },
                                waitMsg: 'Saving Data...'
                            });
                        }
                    }
                })
            } else {
                Ext.getCmp('id_formaddmastersupplier').getForm().submit({
                    url: Ext.getCmp('id_formaddmastersupplier').url,
                    scope: this,
                    success: Ext.getCmp('id_formaddmastersupplier').onSuccess,
                    failure: Ext.getCmp('id_formaddmastersupplier').onFailure,
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


            strmastersupplier.reload();
            Ext.getCmp('id_formaddmastersupplier').getForm().reset();
            winaddmastersupplier.hide();
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
    Ext.reg('formaddmastersupplier', mastersupplierform.Form);

    var winaddmastersupplier = new Ext.Window({
        id: 'id_winaddmastersupplier',
        closeAction: 'hide',
        width: 450,
        height: 450,
        layout: 'fit',
        border: false,
        items: {
            id: 'id_formaddmastersupplier',
            xtype: 'formaddmastersupplier'
        },
        onHide: function() {
            Ext.getCmp('id_formaddmastersupplier').getForm().reset();
        }
    });

    /* START GRID */
    var strmastersupplier = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'kd_supplier',
                'nama_supplier',
                'pic',
                'alamat',
                'telpon',
                'fax',
                'email',
                'pkp',
                'status',
                'top',
                'npwp',
                'created_date',
                'flag_hj_konsinyasi'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("master_supplier/get_rows") ?>',
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

    var searchmastersupplier = new Ext.app.SearchField({
        store: strmastersupplier,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        id: 'idsearchmastersupplier'
    });

    var tbmastersupplier = new Ext.Toolbar({
        items: [{
                text: 'Add',
                icon: BASE_ICONS + 'add.png',
                onClick: function() {
                    Ext.getCmp('btnresetmastersupplier').show();
                    Ext.getCmp('btnsubmitmastersupplier').setText('Submit');
                    winaddmastersupplier.setTitle('Add Form');
                    winaddmastersupplier.show();
                }
            }, '-', searchmastersupplier]
    });

    var cbGrid = new Ext.grid.CheckboxSelectionModel();

    // row actions
    var actionmastersupplier = new Ext.ux.grid.RowActions({
        header: 'Edit',
        autoWidth: false,
        locked: true,
        width: 30,
        actions: [{iconCls: 'icon-edit-record', qtip: 'Edit'}],
        widthIntercept: Ext.isSafari ? 4 : 2
    });
    var actionmastersupplierdel = new Ext.ux.grid.RowActions({
        header: 'Delete',
        autoWidth: false,
        width: 40,
        actions: [{iconCls: 'icon-delete-record', qtip: 'Delete'}],
        widthIntercept: Ext.isSafari ? 4 : 2
    });
    actionmastersupplier.on('action', function(grid, record, action, row, col) {
        var kd_supplier = record.get('kd_supplier');
        switch (action) {
            case 'icon-edit-record':
                editmastersupplier(kd_supplier);
                break;
            case 'icon-delete-record':
                Ext.Msg.show({
                    title: 'Confirm',
                    msg: 'Are you sure delete selected row ?',
                    buttons: Ext.Msg.YESNO,
                    fn: function(btn) {
                        if (btn == 'yes') {
                            Ext.Ajax.request({
                                url: '<?= site_url("master_supplier/delete_row") ?>',
                                method: 'POST',
                                params: {
                                    kd_supplier: kd_supplier
                                },
                                callback: function(opt, success, responseObj) {
                                    var de = Ext.util.JSON.decode(responseObj.responseText);
                                    if (de.success == true) {
                                        strmastersupplier.reload();
                                        strmastersupplier.load({
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
    var mastersupplier = new Ext.grid.EditorGridPanel({
        //id: 'id-mastersupplier-gridpanel',
        id: 'mastersupplier',
        frame: true,
        border: true,
        stripeRows: true,
        sm: cbGrid,
        store: strmastersupplier,
        loadMask: true,
        style: 'margin:0 auto;',
        height: 450,
        view: new Ext.ux.grid.LockingGridView(),
        colModel: new Ext.ux.grid.LockingColumnModel([actionmastersupplier, {
                header: "Kode Supplier",
                dataIndex: 'kd_supplier',
                locked: true,
                sortable: true,
                width: 100
            }, {
                header: "Nama Supplier",
                dataIndex: 'nama_supplier',
                sortable: true,
                locked: true,
                width: 300
            }, {
                header: "Nama PIC",
                dataIndex: 'pic',
                sortable: true,
                width: 200
            }, {
                header: "Alamat",
                dataIndex: 'alamat',
                sortable: true,
                width: 450
            }, {
                header: "Telpon",
                dataIndex: 'telpon',
                sortable: true,
                width: 200
            }, {
                header: "FAX",
                dataIndex: 'fax',
                sortable: true,
                width: 150
            }, {
                header: "Email",
                dataIndex: 'email',
                sortable: true,
                width: 200
            }, {
                header: "PKP",
                dataIndex: 'pkp',
                sortable: true,
                width: 50
            }, {
                header: "Status",
                dataIndex: 'status',
                sortable: true,
                width: 50
            }, {
                header: "TOP",
                dataIndex: 'top',
                sortable: true,
                width: 50
            }, {
                header: "NPWP",
                dataIndex: 'npwp',
                sortable: true,
                width: 200
            }, {
                header: "Flag Konsinyasi",
                dataIndex: 'flag_hj_konsinyasi',
                sortable: true,
                width: 200
            }, {
                header: "Tanggal Entry",
                dataIndex: 'created_date',
                sortable: true,
                width: 100
            }]),
        plugins: [actionmastersupplier],
        listeners: {
            'rowdblclick': function() {
                var sm = mastersupplier.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    editmastersupplier(sel[0].get('kd_supplier'))
                }
            }
        },
        tbar: tbmastersupplier,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strmastersupplier,
            displayInfo: true
        })
    });
    /**
     var mastersupplierpanel = new Ext.FormPanel({
     id: 'mastersupplier',
     border: false,
     frame: false,
     autoScroll:true,	
     items: [mastersupplier]
     });
     **/
    function editmastersupplier(kd_supplier) {
        Ext.Msg.show({
            title: 'Confirm',
            msg: 'Are you sure to update selected row ?',
            buttons: Ext.Msg.YESNO,
            fn: function(btn) {
                if (btn == 'yes') {
                    Ext.getCmp('btnresetmastersupplier').hide();
                    Ext.getCmp('btnsubmitmastersupplier').setText('Update');
                    winaddmastersupplier.setTitle('Edit Form');
                    Ext.getCmp('id_formaddmastersupplier').getForm().load({
                        url: '<?= site_url("master_supplier/get_row") ?>',
                        params: {
                            id: kd_supplier,
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
                    winaddmastersupplier.show();
                }
            }
        })
    }
    ;
    function deletemastersupplier() {
        var sm = mastersupplier.getSelectionModel();
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
                            data = data + sel[i].get('kd_supplier') + ';';
                        }

                        Ext.Ajax.request({
                            url: '<?= site_url("master_supplier/delete_rows") ?>',
                            method: 'POST',
                            params: {
                                postdata: data
                            },
                            callback: function(opt, success, responseObj) {
                                var de = Ext.util.JSON.decode(responseObj.responseText);
                                if (de.success == true) {
                                    strmastersupplier.reload();
                                    strmastersupplier.load({
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
