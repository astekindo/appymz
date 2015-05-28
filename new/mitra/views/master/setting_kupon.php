<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<script type="text/javascript">
    Ext.ns('settingKupon');

    settingKupon.Form = Ext.extend(Ext.form.FormPanel, {
        border: false,
        frame: true,
        labelWidth: 100,
        url: '<?= site_url("setting_kupon/update_row") ?>',
        constructor: function(config) {
            config = config || {};
            config.listeners = config.listeners || {};
            Ext.applyIf(config.listeners, {
                actionfailed: function() {
                    if (console && console.log) {
                       console.log('actionfailed:', arguments);
                    }
                }
            });
            settingKupon.Form.superclass.constructor.call(this, config);
        },
        initComponent: function() {
            var config = {
                defaultType: 'textfield',
                defaults: {labelSeparator: ''},
                monitorValid: true,
                autoScroll: false,
                items: [
                    {
                        type: 'numberfield',
                        fieldLabel: 'Kode Kupon',
                        name: 'kd_kupon',
                        allowBlank: true,
                        readOnly: true,
                        id: 'id_sku_kd_kupon',
                        maxLength: 40,
                        format:'0,0',
                        fieldClass:'readonly-input',
                        anchor: '70%'
                    }, {
                        type: 'numberfield',
                        fieldLabel: 'Rupiah <span class="asterix">*</span>',
                        name: 'rupiah',
                        allowBlank: false,
                        id: 'id_sku_rp_kupon',
                        maxLength: 40,
                        format:'0,0',
                        style: 'text-align: right',
                        anchor: '70%'
                    }, {
                        type: 'numberfield',
                        fieldLabel: 'Jumlah Kupon <span class="asterix">*</span>',
                        name: 'kupon',
                        allowBlank: false,
                        id: 'id_sku_jml_kupon',
                        maxLength: 40,
                        format:'0,0',
                        style: 'text-align: right',
                        anchor: '70%'
                    }, {
                        xtype: 'datefield',
                        fieldLabel: 'Tanggal Awal <span class="asterix">*</span>',
                        name: 'tgl_awal',
                        id: 'id_sku_tgl_awal',
                        format: 'Y-m-d',
                        value: new Date().format('m/d/Y'),
                        readOnly: false,
                        editable: false,
                        anchor: '70%',
                    }, {
                        xtype: 'datefield',
                        fieldLabel: 'Tanggal Akhir <span class="asterix">*</span>',
                        name: 'tgl_akhir',
                        id: 'id_sku_tgl_akhir',
                        format: 'Y-m-d',
                        value: new Date().format('m/d/Y'),
                        readOnly: false,
                        editable: false,
                        anchor: '70%',
                        minValue: (new Date()).clearTime()
                    }
                ],
                buttons: [{
                        text: 'Submit',
                        id: 'id_sku_form_submit',
                        formBind: true,
                        scope: this,
                        handler: this.submit
                    }, {
                        text: 'Reset',
                        id: 'id_sku_form_reset',
                        scope: this,
                        handler: this.reset
                    }, {
                        text: 'Close',
                        id: 'id_sku_form_close',
                        scope: this,
                        handler: function() {
                            winAddEditKupon.hide();
                        }
                    }]
            };
            Ext.apply(this, Ext.apply(this.initialConfig, config));
            settingKupon.Form.superclass.initComponent.apply(this, arguments);
        } ,
        onRender: function() {
            settingKupon.Form.superclass.onRender.apply(this, arguments);
            this.getForm().waitMsgTarget = this.getEl();
        },
        reset: function() {
            this.getForm().reset();
        },
        submit: function() {
            var text = Ext.getCmp('id_sku_form_submit').getText();
            var ref  = Ext.getCmp('id_sku_form_add');
            if (text == 'Update') {
                    ref.getForm().submit({
                    url: ref.url,
                    scope: this,
                    success: ref.onSuccess,
                    failure: ref.onFailure,
                    params: {
                        cmd: 'update'
                    },
                    waitMsg: 'Saving Data...'
                });
              } else {
                ref.getForm().submit({
                    url: ref.url,
                    scope: this,
                    success: ref.onSuccess,
                    failure: ref.onFailure,
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


            strSetKupon.reload();
            Ext.getCmp('id_sku_form_add').getForm().reset();
            winAddEditKupon.hide();
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
    });
    Ext.reg('skuSettingKuponForm', settingKupon.Form);

    var winAddEditKupon = new Ext.Window({
        id: 'id_sku_win_add',
        closeAction: 'hide',
        width: 450,
        height: 350,
        layout: 'fit',
        border: false,
        items: {
            id: 'id_sku_form_add',
            xtype: 'skuSettingKuponForm'
        },
        onHide: function() {
            Ext.getCmp('id_sku_form_add').getForm().reset();
        }
    });

    /* START GRID */
    var strSetKupon = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'kd_kupon',
                'tgl_awal',
                'tgl_akhir',
                'kupon',
                'rupiah'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("setting_kupon/get_rows") ?>',
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
    strSetKupon.load();
    // search field
    var skuGridSearch = new Ext.app.SearchField({
        store: strSetKupon,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        id: 'sku_grid_search'
    });

    // top toolbar
    var skuGridToolbar = new Ext.Toolbar({
        items: [{
                text: 'Add',
                icon: BASE_ICONS + 'add.png',
                onClick: function() {
                    Ext.getCmp('id_sku_form_reset').show();
                    Ext.getCmp('id_sku_form_submit').setText('Submit');
                    winAddEditKupon.setTitle('Add Form');
                    winAddEditKupon.show();
                }
            }, '-', skuGridSearch]
    });

    // checkbox grid
    var cbGrid = new Ext.grid.CheckboxSelectionModel();
    cbGrid.addListener( 'selectionchange', function () {
        if(this.getCount() > 1) {
            //disable button delete
        } else {
            //enable
        }
    });

    var skuGridActions = new Ext.ux.grid.RowActions({
        header: 'Edit',
        autoWidth: false,
        width: 30,
        actions:[{iconCls: 'icon-edit-record', qtip: 'Edit'}],
        widthIntercept: Ext.isSafari ? 4 : 2
    });

    skuGridActions.on('action', function(grid, record, action, row, col) {
        var kd_kupon = record.get('kd_kupon');
        editDataKupon(kd_kupon);
    });

    var settingKuponUndian = new Ext.grid.EditorGridPanel({
        id: 'setting_kupon_undian',
        frame: true,
        border: true,
        stripeRows: true,
        sm: cbGrid,
        store: strSetKupon,
        loadMask: true,
        style: 'margin:0 auto;',
        height: 450,
        tbar: skuGridToolbar,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strSetKupon,
            displayInfo: true
        }),
        columns: [
            skuGridActions,
            {
                datatype:'textcolumn',
                header: "Kode Kupon",
                dataIndex: 'kd_kupon',
                sortable: true,
                width: 110
            }, {
                datatype:'numbercolumn',
                header: "Jumlah Rupiah",
                dataIndex: 'rupiah',
                align: 'right',
                sortable: true,
                width: 110,
                renderer: function (val) {
                    return "Rp. " + Number(val).toLocaleString('id');
                }
            }, {
                datatype:'numbercolumn',
                header: "Jumlah kupon",
                dataIndex: 'kupon',
                align: 'right',
                sortable: true,
                width: 100,
                renderer: function (val) {
                    return val.toLocaleString('id');
                }
            }, {
                header: "Tanggal Awal",
                dataIndex: 'tgl_awal',
                sortable: true,
                width: 100,
                renderer: function (val) {
                    tanggal = new Date(val);
                    return ( (tanggal.getDate() < 10 ? '0' : '') + tanggal.getDate() ) + "/"
                        + ( (tanggal.getMonth() < 9 ? '0' : '') + (tanggal.getMonth() + 1) ) + "/"
                        + tanggal.getFullYear();
                }
            }, {
                header: "Tanggal Akhir",
                dataIndex: 'tgl_akhir',
                sortable: true,
                width: 100,
                renderer: function (val) {
                    tanggal = new Date(val);
                    return ( (tanggal.getDate() < 10 ? '0' : '') + tanggal.getDate() ) + "/"
                        + ( (tanggal.getMonth() < 9 ? '0' : '') + (tanggal.getMonth() + 1) ) + "/"
                        + tanggal.getFullYear();
                }
            }
        ],
        plugins: [skuGridActions],
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    editDataKupon(sel[0].get('kd_kupon') );

                }
            }
        }
    });

    function editDataKupon(kd_kupon) {

        Ext.getCmp('id_sku_form_reset').hide();
        Ext.getCmp('id_sku_form_submit').setText('Update');
        winAddEditKupon.setTitle('Edit Form');
        Ext.getCmp('id_sku_form_add').getForm().load({
            url: '<?= site_url("setting_kupon/get_row") ?>',
            params: {
                id: kd_kupon,
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
        winAddEditKupon.show();
    }
</script>
