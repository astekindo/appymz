<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">
var str_cb_mqr_supplier = new Ext.data.ArrayStore({
    fields: ['nama_supplier'],
    data : []
});

var str_grid_mqr_supplier = new Ext.data.Store({
    reader: new Ext.data.JsonReader({
        fields: ['kd_supplier', 'nama_supplier'],
        root: 'data',
        totalProperty: 'record'
    }),
    proxy: new Ext.data.HttpProxy({
        url: '<?= site_url("pembelian_create_request/search_supplier") ?>',
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

str_grid_mqr_supplier.on('load', function(){
    Ext.getCmp('id_search_grid_mqr_supplier').focus();
});

var search_grid_mqr_supplier = new Ext.app.SearchField({
    store: str_grid_mqr_supplier,
    params: {
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 350,
    id: 'id_search_grid_mqr_supplier'
});

var grid_mqr_supplier = new Ext.grid.GridPanel({
    store: str_grid_mqr_supplier,
    stripeRows: true,
    frame: true,
    border:true,
    columns: [{
        header: 'Kode Supplier',
        dataIndex: 'kd_supplier',
        width: 80,
        sortable: true

    },{
        header: 'Nama Supplier',
        dataIndex: 'nama_supplier',
        width: 300,
        sortable: true
    }],
    tbar: new Ext.Toolbar({
        items: [search_grid_mqr_supplier]
    }),
    bbar: new Ext.PagingToolbar({
        pageSize: ENDPAGE,
        store: str_grid_mqr_supplier,
        displayInfo: true
    }),
    listeners: {
        'rowdblclick': function(){
            var sm = this.getSelectionModel();
            var sel = sm.getSelections();
            if (sel.length > 0) {
//iniini
                Ext.getCmp('id_cb_mqr_supplier').setValue(sel[0].get('nama_supplier'));
                Ext.getCmp('id_ref_kd_supp_mqr').setValue(sel[0].get('kd_supplier'));
                str_mqr_main.load({
                    params:{
                        kd_supplier: sel[0].get('kd_supplier')
                    }
                });
                menu_mqr_supplier.hide();
            }
        }
    }
});

var menu_mqr_supplier = new Ext.menu.Menu();
menu_mqr_supplier.add(new Ext.Panel({
    title: 'Pilih Supplier',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 400,
    height: 300,
    closeAction: 'hide',
    plain: true,
    items: [grid_mqr_supplier],
    buttons: [{
        text: 'Close',
        handler: function(){
            menu_mqr_supplier.hide();
        }
    }]
}));

Ext.ux.TwinComboPrpSuplier = Ext.extend(Ext.form.ComboBox, {
    initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
    getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
    initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
    onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
    trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
    onTrigger2Click: function(){
        //load store grid
        str_grid_mqr_supplier.load();
        menu_mqr_supplier.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
    },
    validationEvent: false,
    validateOnBlur: false,
    trigger1Class: 'x-form-clear-trigger',
    trigger2Class: 'x-form-search-trigger',
    hideTrigger1: true
});

menu_mqr_supplier.on('hide', function(){
    var sf = Ext.getCmp('id_search_grid_mqr_supplier').getValue();
    if( sf != ''){
        Ext.getCmp('id_search_grid_mqr_supplier').setValue('');
        search_grid_mqr_supplier.onTrigger2Click();
    }
});

var cb_mqr_supplier = new Ext.ux.TwinComboPrpSuplier({
    fieldLabel: 'Supplier',
    id: 'id_cb_mqr_supplier',
    store: str_cb_mqr_supplier,
    mode: 'local',
    valueField: 'kd_supplier',
    displayField: 'nama_supplier',
    typeAhead: true,
    triggerAction: 'all',
    editable: false,
    anchor: '90%',
    hiddenName: 'kd_supplier',
    emptyText: 'Pilih Supplier'
});

var header_monitoring_qty_request = {
    layout: 'column',
    border: false,
    items: [{
        layout: 'form',
        columnWidth:.5,
        border: false,
        labelWidth: 100,
        defaults: { labelSeparator: ''},
        items: [
            cb_mqr_supplier,
            {
                xtype: 'hidden',
                id: 'id_ref_kd_supp_mqr',
                name: 'kd_supplier'
            }
        ]
    }]
}

/* START GRID */
var str_mqr_main = new Ext.data.Store({
    reader: new Ext.data.JsonReader({
        fields: [
            'no_ro',
            'tgl_ro',
            'subject',
            'kd_supplier',
            'nama_supplier',
            'is_open',
            'created_by',
            'created_date'
        ],
        root: 'data',
        totalProperty: 'record'
    }),
    proxy: new Ext.data.HttpProxy({

        url: '<?= site_url("pembelian_monitoring_qty_pr/get_rows") ?>',
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

// checkbox grid
var sm_mqr_main = new Ext.grid.CheckboxSelectionModel();
var sm_mqr_detail = new Ext.grid.CheckboxSelectionModel();

// data store
var str_mqr_detail = new Ext.data.Store({
    reader: new Ext.data.JsonReader({
        fields: [
            'kd_produk',
            'kd_produk_supp',
            'nama_produk',
            'qty_pr',
            'qty_po',
            'qty_ro',
            'nm_satuan'
        ],
        root: 'data',
        totalProperty: 'record'
    }),
    proxy: new Ext.data.HttpProxy({
        url: '<?= site_url("pembelian_monitoring_qty_pr/get_rows_detail") ?>',
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

str_mqr_main.on('load', function(){
    str_mqr_detail.removeAll();
})

// search field
var search_mqr_main = new Ext.app.SearchField({
    store: str_mqr_main,
    params: {
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 220,
    emptyText: 'No PR',
    id: 'id_search_mqr_main'
});

search_mqr_main.onTrigger1Click = function(evt) {
    if (this.hasSearch) {
        this.el.dom.value = '';

        // Get the value of search field
        var fid = Ext.getCmp('id_ref_kd_supp_mqr').getValue();
        console.log(fid);
        var o = { start: 0, kd_supplier: fid };

        this.store.baseParams = this.store.baseParams || {};
        this.store.baseParams[this.paramName] = '';
        this.store.reload({
            params : o
        });
        this.triggers[0].hide();
        this.hasSearch = false;
    }
};

search_mqr_main.onTrigger2Click = function(evt) {
    var text = this.getRawValue();
    if (text.length < 1) {
        this.onTrigger1Click();
        return;
    }

    // Get the value of search field
    var fid = Ext.getCmp('id_ref_kd_supp_mqr').getValue();
    console.log(fid);
    var o = { start: 0, kd_supplier: fid };

    this.store.baseParams = this.store.baseParams || {};
    this.store.baseParams[this.paramName] = text;
    this.store.reload({params:o});
    this.hasSearch = true;
    this.triggers[0].show();
};

var tb_mqr_main = new Ext.Toolbar({
    items: [search_mqr_main]
});

var editorpembelianapprovalrequestmanager = new Ext.ux.grid.RowEditor({
    saveText: 'Update'
});

var grid_mqr_main = new Ext.grid.EditorGridPanel({
    id: 'grid_mqr_main',
    frame: true,
    border: true,
    stripeRows: true,
    sm: sm_mqr_main,
    store: str_mqr_main,
    loadMask: false,
    title: 'PR',
    style: 'margin:0 auto;',
    height: 225,
    columns: [{
        header: "No PR",
        dataIndex: 'no_ro',
        // hidden: true,
        sortable: true,
        width: 150
    },{
        header: "Kode Supplier",
        dataIndex: 'kd_supplier',
        sortable: true,
        width: 150
    },{
        header: "Nama Supplier",
        dataIndex: 'nama_supplier',
        sortable: true,
        width: 250
    },{
        header: "Tanggal",
        dataIndex: 'tgl_ro',
        sortable: true,
        width: 80
    },{
        header: "Subject",
        dataIndex: 'subject',
        sortable: true,
        width: 300
    },{
        header: "Status",
        dataIndex: 'is_open',
        sortable: true,
        width: 300
    }],
    listeners: {
        'rowclick': function(){
            var sm = grid_mqr_main.getSelectionModel();
            var sel = sm.getSelections();
            grid_mqr_detail.store.proxy.conn.url = '<?= site_url("pembelian_monitoring_qty_pr/get_rows_detail") ?>/' + sel[0].get('no_ro');
            grid_mqr_detail.store.reload();
        }
    },
    tbar: tb_mqr_main,
    bbar: new Ext.PagingToolbar({
        pageSize: ENDPAGE,
        store: str_mqr_main,
        displayInfo: true
    })
});

// shorthand alias
var fm = Ext.form;

var cmm = new Ext.ux.grid.LockingColumnModel({
    defaults: { sortable: true},
    columns: [ {
        header: "Kode Barang",
        dataIndex: 'kd_produk',
        width: 250
    },{
        header: "Kode Barang Supplier",
        dataIndex: 'kd_produk_supp',
        width: 250
    },{
        header: "Nama Barang",
        dataIndex: 'nama_produk',
        width: 250
    },{
        header: "Qty PR",
        dataIndex: 'qty_pr',
        width: 50
    },{
        header: "Qty PO",
        dataIndex: 'qty_po',
        width: 50
    },{
        header: "Qty RO",
        dataIndex: 'qty_ro',
        width: 50
    },{
        header: "Satuan",
        dataIndex: 'nm_satuan',
        sortable: true,
        width: 50
    }]

});

var grid_mqr_detail = new Ext.grid.EditorGridPanel({
    id: 'grid_mqr_detail',
    store: str_mqr_detail,
    stripeRows: true,
    style: 'margin-bottom:5px;',
    height: 225,
    frame: true,
    border:true,
    loadMask: true,
    sm: sm_mqr_detail,
    plugins: [action_approval_detail_approve_manager,action_approval_detail_reject_manager],
    view: new Ext.ux.grid.LockingGridView(),
    cm: cmm
});


var pembelian_monitoring_qty_pr = new Ext.FormPanel({
    id: 'pembelian_monitoring_qty_pr',
    border: false,
    frame: true,
    monitorValid: true,
    labelWidth: 130,
    items: [{
        bodyStyle: {
            margin: '0px 0px 15px 0px'
        },
        items: [header_monitoring_qty_request]
    },grid_mqr_main, grid_mqr_detail

    ],
    buttons: [{
        text: 'Reset',
        handler: function(){
            clear_data_mqr();
        }
    }]
});

function clear_data_mqr(){
    Ext.getCmp('pembelian_monitoring_qty_pr').getForm().reset();
    str_mqr_main.removeAll();
    str_mqr_detail.removeAll();
}
</script>