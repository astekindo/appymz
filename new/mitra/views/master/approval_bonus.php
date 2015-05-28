<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">
    /*START TWIN NO BUKTI FILTER*/

    var strABPComboNoBukti = new Ext.data.ArrayStore({
        fields: ['kd_bonus_sales'],
        data: []
    });

    var strABPGridNoBukti = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'kd_bonus_sales',
                'tgl_start_bonus',
                'tgl_end_bonus',
                // 'keterangan',
                'created_by'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("setting_harga_jual/get_kd_bonus_sales") ?>',
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

    var searchGridNoBukti = new Ext.app.SearchField({
        store: strABPGridNoBukti,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350
    });


    var gridABPNoBukti = new Ext.grid.GridPanel({
        store: strABPGridNoBukti,
        stripeRows: true,
        frame: true,
        border: true,
        columns: [{
                header: 'No Bukti',
                dataIndex: 'kd_bonus_sales',
                width: 100,
                sortable: true,
            }, {
                header: 'Tanggal Start Bonus',
                dataIndex: 'tgl_start_bonus',
                width: 125,
                sortable: true,
            }, {
                header: 'Tanggal End Bonus',
                dataIndex: 'tgl_end_bonus',
                width: 125,
                sortable: true,
            }, {
                header: 'Request By',
                dataIndex: 'created_by',
                width: 80,
                sortable: true
            }],
        tbar: new Ext.Toolbar({
            items: [searchGridNoBukti]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strABPGridNoBukti,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('id_abp_created_by').setValue(sel[0].get('created_by'));
                    Ext.getCmp('id_abp_no_bukti').setValue(sel[0].get('kd_bonus_sales'));

                    Ext.getCmp('id_abp_tgl_start_bonus').setValue(sel[0].get('tgl_start_bonus'));
                    Ext.getCmp('id_abp_tgl_end_bonus').setValue(sel[0].get('tgl_end_bonus'));
                    menuABPGridNoBukti.hide();
                }
            }
        }
    });

    var menuABPGridNoBukti = new Ext.menu.Menu();
    menuABPGridNoBukti.add(new Ext.Panel({
        title: 'Pilih No Bukti',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 500,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridABPNoBukti],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menuABPGridNoBukti.hide();
                }
            }]
    }));

    Ext.ux.TwinComboABPNoBukti = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            //load store grid
            strABPGridNoBukti.load();
            menuABPGridNoBukti.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
                trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    var comboABPNoBukti = new Ext.ux.TwinComboABPNoBukti({
        fieldLabel: 'No Bukti',
        id: 'id_abp_no_bukti',
        store: strABPComboNoBukti,
        mode: 'local',
        valueField: 'kd_bonus_sales',
        displayField: 'kd_bonus_sales',
        typeAhead: true,
        triggerAction: 'all',
        // allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_bonus_sales',
        emptyText: 'Pilih No Bukti'
    });

    var headerApprovalBonusPenjualan = {
        layout: 'form',
        border: false,
        labelWidth: 120,
        buttonAlign: 'left',
        defaults: {labelSeparator: ''},
        items: [ {
            layout: 'column',
            items: [{
                layout: 'form',
                border: false,
                columnWidth: .5,
                defaults: { labelSeparator: '' },
                items: [
                    comboABPNoBukti,
                {
                    xtype: 'datefield',
                    fieldLabel: 'Tanggal Start Periode',
                    name: 'tanggal',
                    format: 'd-m-Y',
                    editable: false,
                    id: 'id_abp_tgl_start_bonus',
                    anchor: '90%',
                }]
            }, {
                layout: 'form',
                border: false,
                columnWidth: .5,
                defaults: { labelSeparator: '' },
                items: [{
                    xtype: 'textfield',
                    fieldLabel: 'Request By',
                    name: 'user',
                    readOnly: true,
                    fieldClass: 'readonly-input',
                    id: 'id_abp_created_by',
                    anchor: '90%',
                    value: ''
                }, {
                    xtype: 'datefield',
                    fieldLabel: 'Tanggal End Periode',
                    name: 'tanggal',
                    format: 'd-m-Y',
                    editable: false,
                    id: 'id_abp_tgl_end_bonus',
                    anchor: '90%',
                }]
            }]
        }

        ],
        buttons: [{
            text: 'Filter',
            formBind: true,
            handler: function() {
                gridApprovalBonusPenjualan.store.load({
                    params: {
                        no_bukti: Ext.getCmp('id_abp_no_bukti').getValue(),
                    }
                });
            }
        }, {
            text: 'Submit',
            handler: function() {
                var detail = new Array();
                strApprovalBonusPenjualan.each(function(node){
                    detail.push(node.data);
                });
                approvalBonus.getForm().submit({
                    url: '<?= site_url("setting_harga_jual/proses_approval") ?>',
                    scope: this,
                    params: {
                        detail: Ext.util.JSON.encode(detail)
                    },
                    waitMsg: 'Saving Data...',
                    success: function(form, action){
                        Ext.Msg.show({
                            title: 'Success',
                            msg: 'Form submitted successfully',
                            modal: true,
                            icon: Ext.Msg.INFO,
                            buttons: Ext.Msg.OK
                        });

                        clearApprovalBP();
                    },
                    failure: function(form, action){
                        var fe = Ext.util.JSON.decode(action.response.responseText);
                        Ext.Msg.show({
                            title: 'Error',
                            msg: fe.errMsg,
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK,
                            fn: function(btn){
                                if (btn == 'ok' && fe.errMsg == 'Session Expired') {
                                    window.location = '<?= site_url("auth/login") ?>';
                                }
                            }
                        });
                    }
                });
            }
        }, {
            text: 'Reset',
            handler: function() {
                clearApprovalBP();
            }
        }]
    }

    /***/
    var strApprovalBonusPenjualan = new Ext.data.Store({
        autoSave: false,
        reader: new Ext.data.JsonReader({
            fields: [
                'kd_bonus_sales',
                'status_approval',
                'kd_produk',
                'nama_produk',
                'tanggal',
                'koreksi_ke',
                'tgl_start_bonus',
                'tgl_end_bonus',
                'is_bonus',
                'is_bonus_paket',
                'bonus_type',
                'qty_beli_bonus',
                'is_bonus_kelipatan',
                'kd_produk_bonus',
                'nama_produk_bonus',
                'qty_bonus',
                'kd_kategori1_bonus',
                'kd_kategori2_bonus',
                'kd_kategori3_bonus',
                'kd_kategori4_bonus',
                'kategori1_bonus',
                'kategori2_bonus',
                'kategori3_bonus',
                'kategori4_bonus',
                'qty_beli_member',
                'is_member_kelipatan',
                'kd_produk_member',
                'nama_produk_member',
                'qty_member',
                'kd_kategori1_member',
                'kd_kategori2_member',
                'kd_kategori3_member',
                'kd_kategori4_member',
                'kategori1_member',
                'kategori2_member',
                'kategori3_member',
                'kategori4_member',
                'keterangan',
                'tgl_approve',
                'approve_by',
                'status',
                'created_by',
                'created_date',
                'updated_by',
                'updated_date',
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("setting_harga_jual/get_row_grid_approval") ?>',
            method: 'POST'
        }),
        writer: new Ext.data.JsonWriter({
            encode: true,
            writeAllFields: true
        })
    });

    var abpSelectionModel   = new Ext.grid.CheckboxSelectionModel();

    var abpRowActionDelete  = new Ext.ux.grid.RowActions({
        header: 'Remove',
        autoWidth: false,
        locked: true,
        width: 50,
        actions: [{iconCls: 'icon-delete-record', qtip: 'Remove'}],
        widthIntercept: Ext.isSafari ? 4 : 2
    });

    abpRowActionDelete.on('action', function(grid, record, action, row, col) {
        if(action == 'icon-delete-record') {
            Ext.Msg.show({
                title: 'Confirm',
                msg: 'Konfirmasi tunda approval barang?',
                buttons: Ext.Msg.YESNO,
                fn: function(btn) {
                    if (btn == 'yes') {}
                }
            });
        }
    });

    var editorApprovalBonusPenjualan = new Ext.ux.grid.RowEditor({
        saveText: 'Update'
    });

    var gridApprovalBonusPenjualan = new Ext.grid.GridPanel({
        store: strApprovalBonusPenjualan,
        stripeRows: true,
        frame: true,
        border: true,
        height: 400,
        sm: abpSelectionModel,
        plugins: [ editorApprovalBonusPenjualan],
        tbar: new Ext.Toolbar({
            items: [{
                ref: '../removeBtn',
                icon: BASE_ICONS + 'delete.gif',
                text: 'Remove',
                handler: function(){
                    editorApprovalBonusPenjualan.stopEditing();
                    var s = gridApprovalBonusPenjualan.getSelectionModel().getSelections();
                    for(var i = 0, r; r = s[i]; i++){
                        strApprovalBonusPenjualan.remove(r);
                    }
                }
            }]
        }),
        columns: [
        {
            header: 'Status',
            dataIndex: 'status_approval',
            width: 80,
            sortable: true,
            editor: {
                xtype: 'combo',
                store: new Ext.data.JsonStore({
                    fields: ['name'],
                    data: [
                        { name: 'Approve'},
                        { name: 'Reject'}
                    ]
                }),
                id: 'id_abp_approval',
                mode: 'local',
                name: 'status_approval',
                value: 1,
                width: 80,
                hiddenName: 'name',
                valueField: 'name',
                displayField: 'name',
                triggerAction: 'all',
                forceSelection: true
            }
        }, {
            header: 'Kd. Produk',
            dataIndex: 'kd_produk',
            width: 100,
            sortable: true,
        }, {
            header: 'Nama Produk',
            dataIndex: 'nama_produk',
            width: 200,
            sortable: true,
        }, {
            header: 'Kd. Produk Bonus',
            dataIndex: 'kd_produk_bonus',
            width: 100,
            sortable: true,
        }, {
            header: 'Nama Produk Bonus',
            dataIndex: 'nama_produk_bonus',
            width: 200,
            sortable: true,
        }, {
            header: 'Qty Beli',
            dataIndex: 'qty_beli_bonus',
            width: 50,
            sortable: true,
        }, {
            header: 'Qty Bonus',
            dataIndex: 'qty_bonus',
            width: 50,
            sortable: true,
        }, {
            header: 'Kelipatan',
            dataIndex: 'is_bonus_kelipatan',
            width: 50,
            sortable: true,
        }, {
            header: 'Kategori 1',
            dataIndex: 'kategori1_bonus',
            width: 200,
            sortable: true,
        }, {
            header: 'Kategori 2',
            dataIndex: 'kategori2_bonus',
            width: 200,
            sortable: true,
        }, {
            header: 'Kategori 3',
            dataIndex: 'kategori3_bonus',
            width: 200,
            sortable: true,
        }, {
            header: 'Kategori 4',
            dataIndex: 'kategori4_bonus',
            width: 200,
            sortable: true,
        }, {
            header: 'Kd. Produk (Member)',
            dataIndex: 'kd_produk_member',
            width: 100,
            sortable: true,
        }, {
            header: 'Nama Produk (Member)',
            dataIndex: 'nama_produk_member',
            width: 200,
            sortable: true
        }, {
            header: 'Qty Beli (Member)',
            dataIndex: 'qty_beli_member',
            width: 50,
            sortable: true,
        }, {
            header: 'Qty Bonus (Member)',
            dataIndex: 'qty_member',
            width: 50,
            sortable: true,
        }, {
            header: 'Kelipatan (Member)',
            dataIndex: 'is_member_kelipatan',
            width: 50,
            sortable: true,
        }, {
            header: 'Kategori 1 (Member)',
            dataIndex: 'kategori1_member',
            width: 200,
            sortable: true,
        }, {
            header: 'Kategori 2 (Member)',
            dataIndex: 'kategori2_member',
            width: 200,
            sortable: true,
        }, {
            header: 'Kategori 3 (Member)',
            dataIndex: 'kategori3_member',
            width: 200,
            sortable: true,
        }, {
            header: 'Kategori 4 (Member)',
            dataIndex: 'kategori4_member',
            width: 200,
            sortable: true,

        }]
    });

    /***/
    var approvalBonus = new Ext.FormPanel({
        id: 'approval_bonus',
        buttonAlign: 'left',
        border: false,
        frame: true,
        monitorValid: true,
        labelWidth: 130,
        items: [headerApprovalBonusPenjualan, gridApprovalBonusPenjualan]
    });

    function clearApprovalBP() {
        Ext.getCmp('approval_bonus').getForm().reset();
        strApprovalBonusPenjualan.removeAll();
    }
</script>
