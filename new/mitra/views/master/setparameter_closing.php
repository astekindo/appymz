<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">


/* START FORM */

/**
 * Custom namespace untuk pop up form
 */
Ext.ns('spc_form');
spc_form.Form = Ext.extend(Ext.form.FormPanel, {

    // defaults - can be changed from outside
    border: false,
    frame: true,
    labelWidth: 100,
    url: '<?= site_url("setparameter_closing/update_row") ?>',
    constructor: function(config){
        config = config || {};
        config.listeners = config.listeners || {};
        Ext.applyIf(config.listeners, {
            actioncomplete: function(){},
            actionfailed: function(){}
        });
        spc_form.Form.superclass.constructor.call(this, config);
    },
    initComponent: function(){

        // hard coded - cannot be changed from outside
        var config = {
            defaultType: 'textfield',
            defaults: { labelSeparator: '', labelWidth: 100},
            monitorValid: true,
            autoScroll: false,
            items: [
                {
                    type: 'textfield',
                    fieldLabel: 'Periode <span class="asterix">*</span>',
                    name: 'periode',
                    labelStyle: 'white-space: nowrap;',
                    labelWidth: 150,
                    allowBlank: false,
                    id: 'id_periode_input',
                    minLength: 6,
                    maxLength: 6,
                    anchor: '90%'
                },
                {
                    xtype: 'datefield',
                    fieldLabel: 'Tgl. Closing Pembelian',
                    name: 'tgl_closing_pembelian',
                    allowBlank:true,
                    format:'Y-m-d',
                    id: 'id_tgl_closing_pembelian_input',
                    anchor: '90%'
                },
                {
                    xtype: 'datefield',
                    fieldLabel: 'Tgl. Closing Penjualan',
                    name: 'tgl_closing_penjualan',
                    allowBlank:true,
                    format:'Y-m-d',
                    id: 'id_tgl_closing_penjualan_input',
                    anchor: '90%'
                },
                {
                    xtype: 'datefield',
                    fieldLabel: 'Tgl. Closing Inventory',
                    name: 'tgl_closing_inventory',
                    allowBlank:true,
                    format:'Y-m-d',
                    id: 'id_tgl_closing_inventory_input',
                    anchor: '90%'
                },
                {
                    xtype: 'datefield',
                    fieldLabel: 'Tgl. Closing Accounting',
                    name: 'tgl_closing_accounting',
                    allowBlank:true,
                    format:'Y-m-d',
                    id: 'id_tgl_closing_accounting_id',
                    anchor: '90%'
               },
                new Ext.form.Checkbox({
                    xtype: 'checkbox',
                    fieldLabel: 'Aktif?',
                    boxLabel:'Ya',
                    name:'is_aktif',
                    id:'id_is_aktif_input',
                    inputValue: '1',
                    autoLoad : true
                }),
                new Ext.form.Checkbox({
                    xtype: 'checkbox',
                    fieldLabel: 'Closing?',
                    boxLabel:'Ya',
                    name:'is_closing',
                    id:'id_is_closing_input',
                    inputValue: '1',
                    autoLoad : true
                })
            ],
            buttons: [
                {text: 'Submit', id: 'id_btn_submit_spc', formBind: true, scope: this, handler: this.submit},
                {text: 'Reset', id: 'id_btn_reset_spc', scope: this, handler: this.reset},
                {text: 'Close', id: 'id_btn_close_spc', scope: this, handler:
                    function(){
                        win_add_spc.hide();
                    }
                }
            ]
        }; // eo config object

        Ext.apply(this, Ext.apply(this.initialConfig, config));

        // call parent
        spc_form.Form.superclass.initComponent.apply(this, arguments);

    },
    onRender: function(){
        // call parent
        spc_form.Form.superclass.onRender.apply(this, arguments);
        // set wait message target
        this.getForm().waitMsgTarget = this.getEl();
        // loads form after initial layout
        // this.on('afterlayout', this.onLoadClick, this, {single:true});
    },
    //function u/ reset
    reset: function(){
        this.getForm().reset();
    },
    //function u/ submit
    submit: function(){
        this.getForm().submit({
            url: this.url,
            scope: this,
            success: this.onSuccess,
            failure: this.onFailure,
            params: { cmd: 'save'},
            waitMsg: 'Saving Data...'
        });
    },
    onSuccess: function(form, action){
        Ext.Msg.show({
            title: 'Success',
            msg: 'Form submitted successfully',
            modal: true,
            icon: Ext.Msg.INFO,
            buttons: Ext.Msg.OK
        });
        str_spc.reload();
        Ext.getCmp('id_form_add_spc').getForm().reset();
        win_add_spc.hide();
    },
    onFailure: function(form, action){
        var fe = Ext.util.JSON.decode(action.response.responseText);
        this.showError(fe.errMsg || '');
    },
    showError: function(msg, title){
        title = title || 'Error';
        Ext.Msg.show({
            title: title,
            msg: msg,
            modal: true,
            icon: Ext.Msg.ERROR,
            buttons: Ext.Msg.OK,
            fn: function(btn){
                if (btn == 'ok' && msg == 'Session Expired') {
                    window.location = '<?= site_url("auth/login") ?>';
                }
            }
        });
    }
});

// register xtype
Ext.reg('form_add_spc', spc_form.Form);

/**
 * popup window u/ add dan edit data
 */

var win_add_spc = new Ext.Window({
    id: 'id_win_add_spc',
    closeAction: 'hide',
    width: 450,
    height: 350,
    layout: 'fit',
    border: false,
    items: {
        id: 'id_form_add_spc',
        xtype: 'form_add_spc'
    },
    onHide: function(){
        Ext.getCmp('id_form_add_spc').getForm().reset();
    }
});

/**
 * Data store u/ grid
 */
var str_spc = new Ext.data.Store({
    reader: new Ext.data.JsonReader({
        fields: [
            'periode',
            'tgl_closing_pembelian',
            'tgl_closing_penjualan',
            'tgl_closing_inventory',
            'tgl_closing_accounting',
            'is_aktif',
            'created_by',
            'created_date',
            'updated_by',
            'updated_date',
            'is_closing',
            'tgl_closing',
            'closed_by'
        ],
        root: 'data',
        totalProperty: 'record'
    }),
    proxy: new Ext.data.HttpProxy({
        url: '<?= site_url("setparameter_closing/get_rows") ?>',
        method: 'POST'
    }),
    listeners: {
        loadexception: function(event, options, response, error){
            var err = Ext.util.JSON.decode(response.responseText);
            if (err.errMsg == 'Session Expired') {
                session_expired(err.errMsg);
            }
        }
    }
});

// search field
var search_spc = new Ext.app.SearchField({
    store: str_spc,
    params: {
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 220,
    id: 'id_search_spc'
});

// checkbox grid
var cbGrid = new Ext.grid.CheckboxSelectionModel();

/**
 * toolbar atas grid
 */
var tb_setparameter = new Ext.Toolbar({
    items: [
        {
            text: 'Add',
            icon: BASE_ICONS + 'add.png',
            onClick: function(){
                Ext.getCmp('id_btn_reset_spc').show();
                Ext.getCmp('id_btn_submit_spc').setText('Submit');
                win_add_spc.setTitle('Add Form');
                win_add_spc.show();
            }
        },
        '-',
        search_spc
    ]
});


/**
 * Actions u/ grid
 */

function f_edit_spc(data){
    var periode = data.get('periode');
    Ext.getCmp('id_btn_reset_spc').hide();
    Ext.getCmp('id_btn_submit_spc').setText('Update');
    win_add_spc.setTitle('Edit Form');
    Ext.getCmp('id_form_add_spc').getForm().loadRecord(data);
    win_add_spc.show();
}

function f_delete_spc(){
    var sm = setparameter.getSelectionModel();
    var sel = sm.getSelections();
    if (sel.length > 0) {
        Ext.Msg.show({
            title: 'Confirm',
            msg: 'Are you sure delete selected row ?',
            buttons: Ext.Msg.YESNO,
            fn: function(btn){
                if (btn == 'yes') {

                    var data = sel[i].get('periode');

                    Ext.Ajax.request({
                        url: '<?= site_url("setparameter_closing/delete_row") ?>',
                        method: 'POST',
                        params: {
                            periode: data
                        },
                        callback:function(opt,success,responseObj){
                            var de = Ext.util.JSON.decode(responseObj.responseText);
                            if(de.success==true){
                                str_spc.reload();
                                str_spc.load({ params: { start: STARTPAGE,limit: ENDPAGE } });
                            }else{
                                Ext.Msg.show({
                                    title: 'Error',
                                    msg: de.errMsg,
                                    modal: true,
                                    icon: Ext.Msg.ERROR,
                                    buttons: Ext.Msg.OK,
                                    fn: function(btn){
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
// row actions
var action_spc = new Ext.ux.grid.RowActions({
    header:'Edit',
    autoWidth: false,
    width: 30,
    actions:[{iconCls: 'icon-edit-record', qtip: 'Edit'}],
    widthIntercept: Ext.isSafari ? 4 : 2
});

action_spc.on('action', function(grid, record, action, row, col) {
    if (action=='icon-edit-record'){
        f_edit_spc(record);
    }
});

var action_spcdel = new Ext.ux.grid.RowActions({
    header:'Delete',
    autoWidth: false,
    width: 40,
    actions:[
        {iconCls: 'icon-delete-record', qtip: 'Delete'}],
    widthIntercept: Ext.isSafari ? 4 : 2
});

action_spcdel.on('action', function(grid, record, action, row, col) {
    var periode_delete = record.get('periode');
    if (action=='icon-delete-record'){
        Ext.Msg.show({
            title: 'Confirm',
            msg: 'Are you sure delete selected row ?',
            buttons: Ext.Msg.YESNO,
            fn: function(btn){
                if (btn == 'yes') {
                    Ext.Ajax.request({
                        url: '<?= site_url("setparameter_closing/delete_row") ?>',
                        method: 'POST',
                        params: { periode: periode_delete },
                        callback:function(opt,success,responseObj){
                            var de = Ext.util.JSON.decode(responseObj.responseText);
                            if(de.success==true){
                                str_spc.reload();
                                str_spc.load({ params: { start: STARTPAGE, limit: ENDPAGE } });
                            }else{
                                Ext.Msg.show({
                                    title: 'Error',
                                    msg: de.errMsg,
                                    modal: true,
                                    icon: Ext.Msg.ERROR,
                                    buttons: Ext.Msg.OK,
                                    fn: function(btn){
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
});


/**
 * preload data
 */
str_spc.load();

/**
 * Main panel, data grid
 * @type {Ext.grid.EditorGridPanel}
 */

var setparameter_closing = new Ext.grid.EditorGridPanel({
    id: 'parameterclosing',
    frame: true,
    border: true,
    stripeRows: true,
    sm: cbGrid,
    store: str_spc,
    loadMask: true,
    style: 'margin:0 auto;',
    height: 450,
    columns: [
        action_spc,
        {
            header: "Periode",
            dataIndex: 'periode',
            sortable: true,
            width: 80
        },{
            header: "Tgl. Closing Pembelian",
            dataIndex: 'tgl_closing_pembelian',
            sortable: true,
            width: 100
        },{
            header: "Tgl. Closing Penjualan",
            dataIndex: 'tgl_closing_penjualan',
            sortable: true,
            width: 100
        },{
            header: "Tgl. Closing Inventory",
            dataIndex: 'tgl_closing_inventory',
            sortable: true,
            width: 100
        },{
            header: "Tgl. Closing Accounting",
            dataIndex: 'tgl_closing_accounting',
            sortable: true,
            width: 100
        },{
            header: "Aktif",
            dataIndex: 'is_aktif',
            sortable: true,
            width: 50
        },{
            header: "Created By",
            dataIndex: 'created_by',
            sortable: true,
            width: 90
        },{
            header: "Created Date",
            dataIndex: 'created_date',
            sortable: true,
            width: 90
        },{
            header: "Updated By",
            dataIndex: 'updated_by',
            sortable: true,
            width: 90
        },{
            header: "Updated Date",
            dataIndex: 'updated_date',
            sortable: true,
            width: 90
        },{
            header: "Closing",
            dataIndex: 'is_closing',
            sortable: true,
            width: 50
        },{
            header: "Closing Date",
            dataIndex: 'tgl_closing',
            sortable: true,
            width: 90
        },{
            header: "Closed By",
            dataIndex: 'closed_by',
            sortable: true,
            width: 90
        }
    ],
    plugins: [action_spc, action_spcdel],
    listeners: {
        'rowdblclick': function(){
            var sm = setparameter_closing.getSelectionModel();
            var sel = sm.getSelections();
            if (sel.length > 0) {
                f_edit_spc(sel[0]);
            }
        }
    },
    tbar: tb_setparameter,
    bbar: new Ext.PagingToolbar({
        pageSize: ENDPAGE,
        store: str_spc,
        displayInfo: true
    })
});

</script>
