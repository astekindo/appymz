<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">
    /* START FORM */

    Ext.ns('setting_point_rupiahform');
    setting_point_rupiahform.Form = Ext.extend(Ext.form.FormPanel, {
        // defaults - can be changed from outside
        border: false,
        frame: true,
        labelWidth: 100,
        url: '<?= site_url("setting_point_rupiah/update_row") ?>',
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
            setting_point_rupiahform.Form.superclass.constructor.call(this, config);
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
                        type: 'numberfield',
                        fieldLabel: 'Kode Point',
                        name: 'kd_point_setting',
                        allowBlank: true,
                        readOnly: true,
                        id: 'id_kd_point_setting',
                        maxLength: 40,
                        format:'0,0',
                        fieldClass:'readonly-input',
                        anchor: '70%'
                    },{
                        type: 'numberfield',
                        fieldLabel: 'Rupiah <span class="asterix">*</span>',
                        name: 'rupiah',
                        allowBlank: false,
                        id: 'id_rupiah',
                        maxLength: 40,
                        format:'0,0',
                        style: 'text-align: right',
                        anchor: '70%'
                    },{
                        type: 'numberfield',
                        fieldLabel: 'Jumlah Point <span class="asterix">*</span>',
                        name: 'point',
                        allowBlank: false,
                        id: 'id_point',
                        maxLength: 40,
                        format:'0,0',
                        style: 'text-align: right',
                        anchor: '70%'
                    }, {
                        xtype: 'datefield',
                        fieldLabel: 'Tanggal Awal <span class="asterix">*</span>',
                        name: 'tgl_awal',
                        id: 'id_tgl_awal',
                        format: 'Y-m-d',
                        value: new Date().format('m/d/Y'),
                        readOnly: false,
                        editable: false,
                        anchor: '70%',
                      },{
                        xtype: 'datefield',
                        fieldLabel: 'Tanggal Akhir <span class="asterix">*</span>',
                        name: 'tgl_akhir',
                        id: 'id_tgl_akhir',
                        format: 'Y-m-d',
                        value: new Date().format('m/d/Y'),
                        readOnly: false,
                        editable: false,
                        anchor: '70%',
                        minValue: (new Date()).clearTime()
                    }
//                    , new Ext.form.Checkbox({
//                        xtype: 'checkbox',
//                        fieldLabel: 'Status Aktif <span class="asterix">*</span>',
//                        boxLabel: 'Ya',
//                        name: 'aktif',
//                        id: 'mpoint_aktif',
//                        checked: true,
//                        inputValue: '1',
//                        autoLoad: true
//                    })
                    ],
                buttons: [{
                        text: 'Submit',
                        id: 'btnsubmitsetpointrupiah',
                        formBind: true,
                        scope: this,
                        handler: this.submit
                    }, {
                        text: 'Reset',
                        id: 'btnresetsetpointrupiah',
                        scope: this,
                        handler: this.reset
                    }, {
                        text: 'Close',
                        id: 'btnClosesetpointrupiah',
                        scope: this,
                        handler: function() {
                            winaddsetpointrupiah.hide();
                        }
                    }]
            }; // eo config object
            // apply config
            Ext.apply(this, Ext.apply(this.initialConfig, config));

            // call parent
            setting_point_rupiahform.Form.superclass.initComponent.apply(this, arguments);

        } // eo function initComponent  
        ,
        onRender: function() {

            // call parent
            setting_point_rupiahform.Form.superclass.onRender.apply(this, arguments);

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
            var text = Ext.getCmp('btnsubmitsetpointrupiah').getText();
            if (text == 'Update') {
                    Ext.getCmp('id_formaddsetpointrupiah').getForm().submit({
                    url: Ext.getCmp('id_formaddsetpointrupiah').url,
                    scope: this,
                    success: Ext.getCmp('id_formaddsetpointrupiah').onSuccess,
                    failure: Ext.getCmp('id_formaddsetpointrupiah').onFailure,
                    params: {
                        cmd: 'save'
                    },
                    waitMsg: 'Saving Data...'
                });
              } else {
                Ext.getCmp('id_formaddsetpointrupiah').getForm().submit({
                    url: Ext.getCmp('id_formaddsetpointrupiah').url,
                    scope: this,
                    success: Ext.getCmp('id_formaddsetpointrupiah').onSuccess,
                    failure: Ext.getCmp('id_formaddsetpointrupiah').onFailure,
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


            strsetpointrupiah.reload();
            Ext.getCmp('id_formaddsetpointrupiah').getForm().reset();
            winaddsetpointrupiah.hide();
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
    Ext.reg('formaddsetpointrupiah', setting_point_rupiahform.Form);

    var winaddsetpointrupiah = new Ext.Window({
        id: 'id_winaddsetpointrupiah',
        closeAction: 'hide',
        width: 450,
        height: 350,
        layout: 'fit',
        border: false,
        items: {
            id: 'id_formaddsetpointrupiah',
            xtype: 'formaddsetpointrupiah'
        },
        onHide: function() {
            Ext.getCmp('id_formaddsetpointrupiah').getForm().reset();
        }
    });

    /* START GRID */

    // data store
    var strsetpointrupiah = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'kd_point_setting',
                'tgl_awal',
                'tgl_akhir',
                'point',
                'aktif',
                'rupiah'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("setting_point_rupiah/get_rows") ?>',
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
    strsetpointrupiah.load();
    // search field
    var searchsetpointrupiah = new Ext.app.SearchField({
        store: strsetpointrupiah,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        id: 'idsearchsetpointrupiah'
    });

    // top toolbar
    var tbsetpointrupiah = new Ext.Toolbar({
        items: [{
                text: 'Add',
                icon: BASE_ICONS + 'add.png',
                onClick: function() {
                    Ext.getCmp('btnresetsetpointrupiah').show();
                    Ext.getCmp('btnsubmitsetpointrupiah').setText('Submit');
                    winaddsetpointrupiah.setTitle('Add Form');
                    winaddsetpointrupiah.show();
                }
            }, '-', searchsetpointrupiah]
    });

    // checkbox grid
    var cbGrid = new Ext.grid.CheckboxSelectionModel();

    // row actions
    var actionsetpointrupiah = new Ext.ux.grid.RowActions({
        header: 'Edit',
        autoWidth: false,
        width: 30,
        actions: [{iconCls: 'icon-edit-record', qtip: 'Edit'}],
        widthIntercept: Ext.isSafari ? 4 : 2
    });
    var actionsetpointrupiahdel = new Ext.ux.grid.RowActions({
        header: 'Delete',
        autoWidth: false,
        width: 40,
        actions: [{iconCls: 'icon-delete-record', qtip: 'Delete'}],
        widthIntercept: Ext.isSafari ? 4 : 2
    });

    actionsetpointrupiah.on('action', function(grid, record, action, row, col) {
        var kd_point_setting = record.get('kd_point_setting');
        switch (action) {
            case 'icon-edit-record':
                editsetpointrupiah(kd_point_setting);
                break;
            case 'icon-delete-record':
                Ext.Msg.show({
                    title: 'Confirm',
                    msg: 'Are you sure delete selected row ?',
                    buttons: Ext.Msg.YESNO,
                    fn: function(btn) {
                        if (btn == 'yes') {
                            Ext.Ajax.request({
                                url: '<?= site_url("setting_point_rupiah/delete_rows") ?>',
                                method: 'POST',
                                params: {
                                    //postdata: kd_kategori1 + '-' + kd_kategori2 + '-' + kd_kategori3 + '-' + kd_kategori4
                                },
                                callback: function(opt, success, responseObj) {
                                    var de = Ext.util.JSON.decode(responseObj.responseText);
                                    if (de.success == true) {
                                        strsetpointrupiah.reload();
                                        strsetpointrupiah.load({
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
    var setting_point_rupiah = new Ext.grid.EditorGridPanel({
        //id: 'setting_point_rupiah-gridpanel',
        id: 'setting_point_rupiah',
        frame: true,
        border: true,
        stripeRows: true,
        sm: cbGrid,
        store: strsetpointrupiah,
        loadMask: true,
        // title: 'Kategori 4',
        style: 'margin:0 auto;',
        height: 450,
        tbar: tbsetpointrupiah,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strsetpointrupiah,
            displayInfo: true
        }),
        // width: 550,
        columns: [actionsetpointrupiah,  
            {
                header: "Kode Point Setting",
                dataIndex: 'kd_point_setting',
                sortable: true,
            }, {
                datatype:'numbercolumn',
                header: "Rupiah",
                dataIndex: 'rupiah',
                sortable: true,
                format:'0,0',
                width: 110,
                renderer: function (val) {
                    return "Rp. " + Number(val).toLocaleString('id');
                }
                
            }, {
                datatype:'numbercolumn',
                header: "Point",
                dataIndex: 'point',
                sortable: true,
                format:'0,0',
                width: 100,
                renderer: function (val) {
                    return val.toLocaleString('id');
                }
            },  {
                header: "Tanggal Awal",
                dataIndex: 'tgl_awal',
                sortable: true,
                width: 100
            }, {
                header: "Tanggal Akhir",
                dataIndex: 'tgl_akhir',
                sortable: true,
                width: 100
            }],
        plugins: [actionsetpointrupiah],
        listeners: {
            'rowdblclick': function() {
                var sm = setting_point_rupiah.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    editsetpointrupiah(sel[0].get('kd_point_setting') );
                        
                }
            }
        }
        
    });

    /**
     var setting_point_rupiahpanel = new Ext.FormPanel({
     id: 'setting_point_rupiah',
     border: false,
     frame: false,
     autoScroll: true,
     items: [setting_point_rupiah]
     });
     **/

    function editsetpointrupiah(kd_point_setting) {
     
        Ext.getCmp('btnresetsetpointrupiah').hide();
        Ext.getCmp('btnsubmitsetpointrupiah').setText('Update');
        winaddsetpointrupiah.setTitle('Edit Form');
        Ext.getCmp('id_formaddsetpointrupiah').getForm().load({
            url: '<?= site_url("setting_point_rupiah/get_row") ?>',
            params: {
                id: kd_point_setting,
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
        winaddsetpointrupiah.show();
    }

    function deletesetting_point_rupiah() {
        var sm = setting_point_rupiah.getSelectionModel();
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
                            data = data + sel[i].get('kd_kategori1') + '-' + sel[i].get('kd_kategori2') + '-' + sel[i].get('kd_kategori3') + '-' + sel[i].get('kd_kategori4') + ';';
                        }

                        Ext.Ajax.request({
                            url: '<?= site_url("setting_point_rupiah/delete_rows") ?>',
                            method: 'POST',
                            params: {
                                postdata: data
                            },
                            callback: function(opt, success, responseObj) {
                                var de = Ext.util.JSON.decode(responseObj.responseText);
                                if (de.success == true) {
                                    strsetpointrupiah.reload();
                                    strsetpointrupiah.load({
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
