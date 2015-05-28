<?php if (!defined( 'BASEPATH')) exit( 'No direct script access allowed'); ?>
<script type="text/javascript">

// start COMBOBOX SUPPLIER
var str_cb_mon_piutang_member = new Ext.data.ArrayStore({
    fields: ['nm_member'],
    data: []
});
var str_grid_mon_piutang_member = new Ext.data.Store({
    reader: new Ext.data.JsonReader({
        fields: ['kd_member', 'nm_member'],
        root: 'data',
        totalProperty: 'record'
    }),
    proxy: new Ext.data.HttpProxy({
        url: '<?= site_url("monitoring_piutang/search_member") ?>',
        method: 'POST'
    }),
    listeners: {

        loadexception: function (event, options, response, error) {
            var err = Ext.util.JSON.decode(response.responseText);
            if (err.errMsg == 'Session Expired') {
                session_expired(err.errMsg);
            }
        }
    }
});
var search_grid_mon_piutang_member = new Ext.app.SearchField({
    store: str_grid_mon_piutang_member,
    params: {
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 350,
    id: 'id_search_mon_piutang_member'
});
var grid_mon_piutang_member = new Ext.grid.GridPanel({
    store: str_grid_mon_piutang_member,
    stripeRows: true,
    frame: true,
    border: true,
    columns: [{
        header: 'Kode Member',
        dataIndex: 'kd_member',
        width: 80,
        sortable: true
    }, {
        header: 'Nama Member',
        dataIndex: 'nm_member',
        width: 300,
        sortable: true
    }],
    tbar: new Ext.Toolbar({
        items: [search_grid_mon_piutang_member]
    }),
    bbar: new Ext.PagingToolbar({
        pageSize: ENDPAGE,
        store: str_grid_mon_piutang_member,
        displayInfo: true
    }),
    listeners: {
        'rowdblclick': function () {
            var sm = this.getSelectionModel();
            var sel = sm.getSelections();
            if (sel.length > 0) {
                Ext.getCmp('id_cb_mon_piutang_member').setValue(sel[0].get('kd_member'));
                Ext.getCmp('mon_piutang_nama_member').setValue(sel[0].get('nm_member'));
                // strlaporanpenerimaanbarang.removeAll();
                menu_mon_piutang_member.hide();
            }
        }
    }
});
var menu_mon_piutang_member = new Ext.menu.Menu();
menu_mon_piutang_member.add(new Ext.Panel({
    title: 'Pilih Member',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 400,
    height: 300,
    closeAction: 'hide',
    plain: true,
    items: [grid_mon_piutang_member],
    buttons: [{
        text: 'Close',
        handler: function () {
            menu_mon_piutang_member.hide();
        }
    }]
}));
Ext.ux.TwinComboSupplierPO = Ext.extend(Ext.form.ComboBox, {
    initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
    getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
    initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
    onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
    trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
    onTrigger2Click: function () {
        //load store grid
        str_grid_mon_piutang_member.load();
        menu_mon_piutang_member.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
    },
    validationEvent: false,
    validateOnBlur: false,
    trigger1Class: 'x-form-clear-trigger',
    trigger2Class: 'x-form-search-trigger',
    hideTrigger1: true
});
menu_mon_piutang_member.on('hide', function () {
    var sf = Ext.getCmp('id_search_mon_piutang_member').getValue();
    if (sf != '') {
        Ext.getCmp('id_search_mon_piutang_member').setValue('');
        search_grid_mon_piutang_member.onTrigger2Click();
    }
});
var cbmposuplier = new Ext.ux.TwinComboSupplierPO({
    fieldLabel: 'Kode Member',
    id: 'id_cb_mon_piutang_member',
    store: str_cb_mon_piutang_member,
    mode: 'local',
    valueField: 'nm_member',
    displayField: 'nm_member',
    typeAhead: true,
    triggerAction: 'all',
    // allowBlank: false,
    editable: false,
    anchor: '90%',
    hiddenName: 'nm_member',
    emptyText: 'Pilih Kode Member'
});
// end COMBOBOX SUPPLIER

// start COMBOBOX CLOSE PO
var arr_cb_status_piutang = [
    [1, "All"],
    [2, "Piutang Belum Lunas"],
    [3, "Piutang Lunas"],
    [4, "Lunas di Kasir"]
];
var str_cb_status_piutang = new Ext.data.ArrayStore({
    fields: [{
        name: 'key'
    }, {
        name: 'value'
    }],
    data: arr_cb_status_piutang
});
var cb_status_piutang = new Ext.form.ComboBox({
    fieldLabel: 'Status Piutang',
    id: 'id_cb_status_piutang',
    name: 'status',
    store: str_cb_status_piutang,
    valueField: 'key',
    displayField: 'value',
    mode: 'local',
    forceSelection: true,
    triggerAction: 'all',
    anchor: '90%'
});
// end COMBOBOX CLOSE PO

// start COMBOBOX NO SO
var str_cb_mon_piutang_so = new Ext.data.ArrayStore({
    fields: ['no_so'],
    data : []
});

var str_grid_mon_piutang_so = new Ext.data.Store({
    reader: new Ext.data.JsonReader({
        fields: ['no_so','tgl_so','rp_total','rp_diskon','rp_ekstra_diskon','rp_grand_total','rp_diskon_tambahan'],
        root: 'data',
        totalProperty: 'record'
    }),
    proxy: new Ext.data.HttpProxy({
        url: '<?= site_url("penjualan_retur/search_salesorder") ?>',
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

var search_grid_mon_piutang_so = new Ext.app.SearchField({
    store: str_grid_mon_piutang_so,
    params: {
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 350,
    id: 'id_search_grid_mon_piutang_so'
});


var grid_mon_piutang_so = new Ext.grid.GridPanel({
    store: str_grid_mon_piutang_so,
    stripeRows: true,
    frame: true,
    border:true,
    columns: [{
        header: 'No Sales Order',
        dataIndex: 'no_so',
        width: 150,
        sortable: true
    },{
        header: 'Tanggal Sales Order',
        dataIndex: 'tgl_so',
        width: 300,
        sortable: true
    }],
    tbar: new Ext.Toolbar({
        items: [search_grid_mon_piutang_so]
    }),
    bbar: new Ext.PagingToolbar({
        pageSize: ENDPAGE,
        store: str_grid_mon_piutang_so,
        displayInfo: true
    }),
    listeners: {
        'rowdblclick': function(){
            var sm = this.getSelectionModel();
            var sel = sm.getSelections();
            if (sel.length > 0) {
                Ext.getCmp('id_cb_mon_piutang_so').setValue(sel[0].get('no_so'));
                menu_mon_piutang_so.hide();
            }
        }
    }
});

var menu_mon_piutang_so = new Ext.menu.Menu();
menu_mon_piutang_so.add(new Ext.Panel({
    title: 'Pilih No Sales Order',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 700,
    height: 300,
    closeAction: 'hide',
    plain: true,
    items: [grid_mon_piutang_so],
    buttons: [{
        text: 'Close',
        handler: function(){
            menu_mon_piutang_so.hide();
        }
    }]
}));

Ext.ux.TwinComboMonPiutangSO = Ext.extend(Ext.form.ComboBox, {
    initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
    getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
    initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
    onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
    trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
    onTrigger2Click: function(){
        //load store grid
        str_grid_mon_piutang_so.load();
        menu_mon_piutang_so.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
    },
    validationEvent: false,
    validateOnBlur: false,
    trigger1Class: 'x-form-clear-trigger',
    trigger2Class: 'x-form-search-trigger',
    hideTrigger1: true
});

menu_mon_piutang_so.on('hide', function(){
    var sf = Ext.getCmp('id_search_grid_mon_piutang_so').getValue();
    if( sf != ''){
        Ext.getCmp('id_search_grid_mon_piutang_so').setValue('');
        search_grid_mon_piutang_so.onTrigger2Click();
    }
});

var cb_mon_piutang_so = new Ext.ux.TwinComboMonPiutangSO({
    fieldLabel: 'No Struk/SO',
    id: 'id_cb_mon_piutang_so',
    store: str_cb_mon_piutang_so,
    mode: 'local',
    valueField: 'no_so',
    displayField: 'no_so',
    typeAhead: true,
    triggerAction: 'all',
    allowBlank: true,
    editable: false,
    anchor: '90%',
    hiddenName: 'no_so',
    emptyText: 'Pilih Sales Order'

});
// end COMBOBOX NO SO


// HEADER MONITORING PIUTANG
var header_monitoring_piutang = {
    layout: 'column',
    border: false,
    buttonAlign: 'left',
    items: [{
        columnWidth: .5,
        layout: 'form',
        border: false,
        labelWidth: 100,
        defaults: {
            labelSeparator: ''
        },
        items: [
            cbmposuplier,
            {
                xtype: 'datefield',
                fieldLabel: 'Periode',
                emptyText: 'Dari Tanggal',
                name: 'tgl_min',
                id: 'mon_piutang_tgl_min',
                maxLength: 255,
                anchor: '90%',
                value: '',
                format: 'd-M-Y'
            },
            cb_status_piutang
        ]
    }, {
        columnWidth: .5,
        layout: 'form',
        border: false,
        labelWidth: 100,
        defaults: {
            labelSeparator: ''
        },
        items: [
            {
                xtype: 'textfield',
                fieldLabel: 'Nama Member',
                name: 'nm_member',
                readOnly: true,
                fieldClass: 'readonly-input',
                id: 'mon_piutang_nama_member',
                anchor: '90%',
                value: '',
                emptyText: 'Pilih Kode Member'
            },{
                xtype: 'datefield',
                fieldLabel: 's/d',
                emptyText: 'Sampai Tanggal',
                name: 'tgl_max',
                id: 'mon_piutang_tgl_max',
                maxLength: 255,
                anchor: '90%',
                value: '',
                format: 'd-M-Y'
            }
            ,cb_mon_piutang_so
        ]
    }],
    buttons: [{
        text: 'Filter',
        formBind: true,
        handler: function () {
            var kd_supplier     = Ext.getCmp('id_cb_mon_piutang_member').getValue();
            var tgl_min         = Ext.getCmp('mon_piutang_tgl_min').getValue();
            var tgl_max         = Ext.getCmp('mon_piutang_tgl_max').getValue();
            var status_piutang  = Ext.getCmp('id_cb_status_piutang').getValue();
            var no_so           = Ext.getCmp('id_cb_mon_piutang_so').getValue();

            grid_penjualan_monitoring_piutang.store.load({
                params: {
                    'kd_supplier' : kd_supplier,
                    'tgl_min'     : tgl_min,
                    'tgl_max'     : tgl_max,
                    'status'      : status_piutang,
                    'no_so'       : no_so
                }
            });
        }
    }, {
        text: 'Reset',
        formBind: true,
        handler: function () {
            Ext.getCmp('id_cb_mon_piutang_member').setValue('');
            Ext.getCmp('mon_piutang_nama_member').setValue('');
            Ext.getCmp('mon_piutang_tgl_min').setRawValue('');
            Ext.getCmp('mon_piutang_tgl_max').setRawValue('');
            Ext.getCmp('id_cb_status_piutang').setValue('');
            Ext.getCmp('id_cb_mon_piutang_so').setValue('');
            grid_penjualan_monitoring_piutang.store.removeAll();
        }
    }]
};

// start GRID MONITORING PO
var str_penjualan_monitoring_piutang = new Ext.data.Store({
    reader: new Ext.data.JsonReader({
        fields: [
            'tgl_so',
            'no_so',
            'kd_member',
            'nm_member',
            'nm_penerima',
            'rp_total',
            'rp_dp',
            'rp_bayar',
            'rp_kurang_bayar'
        ],
        root: 'data',
        totalProperty: 'record'
    }),
    proxy: new Ext.data.HttpProxy({
        url: '<?= site_url("monitoring_piutang/get_rows") ?>',
        method: 'POST'
    }),
    listeners: {
        loadexception: function (event, options, response, error) {
            var err = Ext.util.JSON.decode(response.responseText);
            if (err.errMsg == 'Session Expired') {
                session_expired(err.errMsg);
            }
        }
    }
});

str_penjualan_monitoring_piutang.on('load', function() {
    str_penjualan_monitoring_piutang.setBaseParam('kd_supplier', Ext.getCmp('id_cb_mon_piutang_member').getValue());
    str_penjualan_monitoring_piutang.setBaseParam('tgl_min', Ext.getCmp('mon_piutang_tgl_min').getValue());
    str_penjualan_monitoring_piutang.setBaseParam('tgl_max', Ext.getCmp('mon_piutang_tgl_max').getValue());
    str_penjualan_monitoring_piutang.setBaseParam('status', Ext.getCmp('id_cb_status_piutang').getValue());
    str_penjualan_monitoring_piutang.setBaseParam('no_so', Ext.getCmp('id_cb_mon_piutang_so').getValue());
    
});

var search_penjualan_monitoring_piutang = new Ext.app.SearchField({
    store: str_penjualan_monitoring_piutang,
    params: {
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 220,
    emptyText: 'Tanggal, No SO, Nama Member',
    id: 'id_str_penjualan_monitoring_piutang'
});
var tb_penjualan_monitoring_piutang = new Ext.Toolbar({
    items: [search_penjualan_monitoring_piutang]
});
var sm_penjualan_monitoring_piutang = new Ext.grid.CheckboxSelectionModel();
var grid_penjualan_monitoring_piutang = new Ext.grid.EditorGridPanel({
    id: 'grid_penjualan_monitoring_piutang',
    frame: true,
    border: true,
    stripeRows: true,
    sm: sm_penjualan_monitoring_piutang,
    store: str_penjualan_monitoring_piutang,
    loadMask: false,
    style: 'margin:0 auto;',
    height: 400,
    fields: [
        'rp_total',
        'rp_dp',
        'rp_bayar',
        'rp_kurang_bayar'
    ],
    columns: [{
        header: "Tanggal Struk/SO",
        dataIndex: 'tgl_so',
        sortable: true,
        width: 75
    }, {
        header: "No Struk/SO",
        dataIndex: 'no_so',
        sortable: true,
        width: 120
    }, {
        header: "Kode Member",
        dataIndex: 'kd_member',
        sortable: true,
        width: 120
    }, {
        header: "Nama Member",
        dataIndex: 'nm_member',
        sortable: true,
        width: 120
    }, {
        header: "Nama Penerima",
        dataIndex: 'nm_penerima',
        sortable: true,
        width: 150
    }, {
        xtype: 'numbercolumn',
        header: "Total Tagihan",
        dataIndex: 'rp_total',
        align: 'right',
        format: '0,0',
        sortable: true,
        width: 100
    }, {
        xtype: 'numbercolumn',
        header: "Bayar di Kasir",
        dataIndex: 'rp_dp',
        align: 'right',
        format: '0,0',
        sortable: true,
        width: 100
    }, {
        xtype: 'numbercolumn',
        header: "Bayar Piutang",
        dataIndex: 'rp_bayar',
        align: 'right',
        format: '0,0',
        sortable: true,
        width: 100
    }, {
        xtype: 'numbercolumn',
        header: "Kurang Bayar",
        dataIndex: 'rp_kurang_bayar',
        align: 'right',
        format: '0,0',
        sortable: true,
        width: 100
    }, {
        header: " ",
        dataIndex: '',
        width: 20
    }],
    listeners: {
        'rowdblclick': function () {
            var sm = grid_penjualan_monitoring_piutang.getSelectionModel();
            var sel = sm.getSelections();
            if (sel.length > 0) {

                Ext.Ajax.request({
                    url: '<?= site_url("monitoring_piutang/get_data_po") ?>/' + sel[0].get('no_so'),
                    method: 'POST',
                    params: {},
                    callback: function (opt, success, responseObj) {
                        var win_monitoring_piutang_detail = new Ext.Window({
                            title: 'Monitoring Piutang',
                            width: 1050,
                            height: 500,
                            autoScroll: true,
                            html: responseObj.responseText
                        });

                        win_monitoring_piutang_detail.show();

                    }
                });
            }
        }
    },

    tbar: tb_penjualan_monitoring_piutang,
    bbar: new Ext.PagingToolbar({
        pageSize: ENDPAGE,
        store: str_penjualan_monitoring_piutang,
        displayInfo: true
    })
});
// end GRID MONITORING PO

// PANEL MONITORING PO
var penjualan_monitoring_piutang_panel = new Ext.FormPanel({
    id: 'penjualan_monitoring_piutang',
    border: false,
    frame: true,
    autoScroll:true,
    bodyStyle: 'padding-right:20px;',
    labelWidth: 130,
    items: [
        {
            bodyStyle: {margin: '10px 0px 15px 0px'},
            items: [header_monitoring_piutang]
        },
        grid_penjualan_monitoring_piutang
    ]
});

</script>
