<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">

    Ext.ns('mac_addresform');
    mac_addresform.Form = Ext.extend(Ext.form.FormPanel, {
        border: false,
        closeable: true,
        frame: true,
        labelWidth: 100,
        waitMsg: 'Loading...',
        url: '<?= site_url("mac_address/update_row") ?>',
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
            mac_addresform.Form.superclass.constructor.call(this, config);
        },
        initComponent: function() {
            var config = {
                defaultType: 'textfield',
                defaults: {labelSeparator: ''},
                monitorValid: true,
                autoScroll: false // ,buttonAlign:'right'
                        ,
                items: [ {
                        type: 'textfield',
                        fieldLabel: 'Mac Address <span class="asterix">*</span>',
                        name: 'mac_address',
                        allowBlank: false,
                        id: 'id_mac_address',
                        anchor: '90%',
                        maxLength: 40,
                        style: 'text-transform: uppercase'
                    },{
                        type: 'textfield',
                        fieldLabel: 'Nama <span class="asterix">*</span>',
                        name: 'nama',
                        allowBlank: false,
                        id: 'id_nama',
                        anchor: '90%',
                        maxLength: 40,
                        style: 'text-transform: uppercase'
                    }, {
                        xtype: 'radiogroup',
                        fieldLabel: 'Aktif',
                        name: 'aktif',						               
                        id: 'id_aktif',						                
                        anchor: '90%',
                        items:[{boxLabel: 'YA', name: 'rb-aktif', inputValue: "1", checked: true},
                               {boxLabel: 'Tidak', name: 'rb-aktif', inputValue: "0"}
                           ]
                    },{
                        type: 'textfield',
                        fieldLabel: 'Keterangan <span class="asterix">*</span>',
                        name: 'keterangan',
                        allowBlank: false,
                        id: 'id_keterangan',
                        anchor: '90%',
                        maxLength: 40,
                        style: 'text-transform: uppercase'
                    }
                ],
                buttons: [{
                        text: 'Submit',
                        id: 'btnsubmitmac_addres',
                        formBind: true,
                        scope: this,
                        handler: this.submit
                    }, {
                        text: 'Reset',
                        id: 'btnresetmac_addres',
                        scope: this,
                        handler: this.reset
                    }, {
                        text: 'Close',
                        id: 'btnclosemac_addres',
                        scope: this,
                        handler: function() {
                            winaddmac_addres.hide();
                        }
                    }]
            }; // eo config object
            // apply config
            Ext.apply(this, Ext.apply(this.initialConfig, config));
            mac_addresform.Form.superclass.initComponent.apply(this, arguments);
        },
        onRender: function() {

            // call parent
            mac_addresform.Form.superclass.onRender.apply(this, arguments);

            // set wait message target
            this.getForm().waitMsgTarget = this.getEl();

            // loads form after initial layout
            // this.on('afterlayout', this.onLoadClick, this, {single:true});

        },
        reset: function() {
            this.getForm().reset();
        },
        submit: function() {

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


            strmac_addres.reload();
            Ext.getCmp('id_formaddmac_addres').getForm().reset();
            winaddmac_addres.hide();
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
    });
    Ext.reg('formaddmac_addres', mac_addresform.Form);

    var winaddmac_addres = new Ext.Window({
        id: 'id_winaddmac_addres',
        closeAction: 'hide',
        width: 450,
        height: 350,
        layout: 'fit',
        border: false,
        items: {
            id: 'id_formaddmac_addres',
            xtype: 'formaddmac_addres'
        },
        onHide: function() {
            Ext.getCmp('id_formaddmac_addres').getForm().reset();
        }
    });
    var strmac_addres = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'mac_address',
                'nama',
                'status',
                'keterangan'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("mac_address/get_rows") ?>',
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

strmac_addres.load();

    var searchmac_addres = new Ext.app.SearchField({
        store: strmac_addres,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        id: 'idsearchmac_addres'
    });

    var tbmac_addres = new Ext.Toolbar({
        items: [{
                text: 'Add',
                icon: BASE_ICONS + 'add.png',
                onClick: function() {
                    Ext.getCmp('btnresetmac_addres').show();
                    Ext.getCmp('btnsubmitmac_addres').setText('Submit');
                    winaddmac_addres.setTitle('Add Form');
                    winaddmac_addres.show();
                }
            }, '-', searchmac_addres]
    });

    var cbGridmac_addres = new Ext.grid.CheckboxSelectionModel();
    var actionmac_addres = new Ext.ux.grid.RowActions({
        header: 'Edit',
        autoWidth: false,
        width: 30,
        actions: [{iconCls: 'icon-edit-record', qtip: 'Edit'}],
        widthIntercept: Ext.isSafari ? 4 : 2
    });
    var actionmac_addresdel = new Ext.ux.grid.RowActions({
        header: 'Delete',
        autoWidth: false,
        width: 40,
        actions: [{iconCls: 'icon-delete-record', qtip: 'Delete'}],
        widthIntercept: Ext.isSafari ? 4 : 2
    });

    actionmac_addres.on('action', function(grid, record, action, row, col) {
        var kd_mac_addres = record.get('mac_address');
        switch (action) {
            case 'icon-edit-record':
                editmac_addres(kd_mac_addres);
                break;
            case 'icon-delete-record':
                Ext.Msg.show({
                    title: 'Confirm',
                    msg: 'Are you sure delete selected row ?',
                    buttons: Ext.Msg.YESNO,
                    fn: function(btn) {
                        if (btn == 'yes') {
                            Ext.Ajax.request({
                                url: '<?= site_url("mac_address/delete_row") ?>',
                                method: 'POST',
                                params: {
                                    kd_mac_addres: kd_mac_addres
                                },
                                callback: function(opt, success, responseObj) {
                                    var de = Ext.util.JSON.decode(responseObj.responseText);
                                    if (de.success == true) {
                                        strmac_addres.reload();
                                        strmac_addres.load({
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

    var mac_addres = new Ext.grid.EditorGridPanel({
        //id: 'id-mac_addres-gridpanel',
        id: 'mac_addres',
        frame: true,
        border: true,
        stripeRows: true,
        sm: cbGridmac_addres,
        store: strmac_addres,
        closable: true,
        loadMask: true,
        style: 'margin:0 auto;',
        height: 450,
        columns: [actionmac_addres, actionmac_addresdel, {
                header: "Mac Address",
                dataIndex: 'mac_address',
                sortable: true,
                width: 110
            }, {
                header: "Nama ",
                dataIndex: 'nama',
                sortable: true,
                width: 120
            }, {
                header: "Status",
                dataIndex: 'status',
                sortable: true,
                width: 100
            },{
                header: "Keterangan",
                dataIndex: 'keterangan',
                sortable: true,
                width: 150
            }],
        plugins: [actionmac_addres, actionmac_addresdel],
        listeners: {
            'rowdblclick': function() {

                var sm = mac_addres.getSelectionModel();

                var sel = sm.getSelections();

                if (sel.length > 0) {
                    Ext.getCmp('btnresetmac_addres').hide();
                    Ext.getCmp('btnsubmitmac_addres').setText('Update');
                    winaddmac_addres.setTitle('Edit Form');
                    Ext.getCmp('id_formaddmac_addres').getForm().load({
                        url: '<?= site_url("mac_address/get_row") ?>',
                        params: {
                            id: sel[0].get('mac_address'),
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
                    winaddmac_addres.show();
                }

            }

        },
        tbar: tbmac_addres,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strmac_addres,
            displayInfo: true
        })

    });
    /**
     var mac_addrespanel = new Ext.FormPanel({
     id: 'mac_addres',
     border: false,
     frame: false,
     autoScroll:true,	
     items: [mac_addres]
     });
     **/
    function editmac_addres(kd_mac_addres) {
        Ext.getCmp('btnresetmac_addres').hide();
        Ext.getCmp('btnsubmitmac_addres').setText('Update');
        winaddmac_addres.setTitle('Edit Form');
        Ext.getCmp('id_formaddmac_addres').getForm().load({
            url: '<?= site_url("mac_address/get_row") ?>',
            params: {
                id: kd_mac_addres,
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
        winaddmac_addres.show();
    }
    function deletemac_addres() {
        var sm = mac_addres.getSelectionModel();
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
                            data = data + sel[i].get('mac_address') + ';';
                        }

                        Ext.Ajax.request({
                            url: '<?= site_url("mac_address/delete_rows") ?>',
                            method: 'POST',
                            params: {
                                postdata: data
                            },
                            callback: function(opt, success, responseObj) {
                                var de = Ext.util.JSON.decode(responseObj.responseText);
                                if (de.success == true) {
                                    strmac_addres.reload();
                                    strmac_addres.load({
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