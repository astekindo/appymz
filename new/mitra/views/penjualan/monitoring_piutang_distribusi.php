<?php if (!defined( 'BASEPATH')) exit( 'No direct script access allowed'); ?>
<script type="text/javascript">

// start COMBOBOX Pelanggan
var str_cb_mon_piutang_pelanggan = new Ext.data.ArrayStore({
    fields: ['kd_pelanggan'],
    data: []
});
var str_grid_mon_piutang_pelanggan = new Ext.data.Store({
    reader: new Ext.data.JsonReader({
        fields: ['kd_pelanggan', 'nama_pelanggan'],
        root: 'data',
        totalProperty: 'record'
    }),
    proxy: new Ext.data.HttpProxy({
        url: '<?= site_url("monitoring_piutang_distribusi/search_pelanggan") ?>',
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
var search_grid_mon_piutang_pelanggan = new Ext.app.SearchField({
    store: str_grid_mon_piutang_pelanggan,
    params: {
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 350,
    id: 'id_search_mon_piutang_pelanggan'
});
var grid_mon_piutang_pelanggan = new Ext.grid.GridPanel({
    store: str_grid_mon_piutang_pelanggan,
    stripeRows: true,
    frame: true,
    border: true,
    columns: [{
        header: 'Kode Pelanggan',
        dataIndex: 'kd_pelanggan',
        width: 80,
        sortable: true
    }, {
        header: 'Nama Pelanggan',
        dataIndex: 'nama_pelanggan',
        width: 300,
        sortable: true
    }],
    tbar: new Ext.Toolbar({
        items: [search_grid_mon_piutang_pelanggan]
    }),
    bbar: new Ext.PagingToolbar({
        pageSize: ENDPAGE,
        store: str_grid_mon_piutang_pelanggan,
        displayInfo: true
    }),
    listeners: {
        'rowdblclick': function () {
            var sm = this.getSelectionModel();
            var sel = sm.getSelections();
            if (sel.length > 0) {
                Ext.getCmp('id_cb_mon_piutang_pelanggan').setValue(sel[0].get('kd_pelanggan'));
                Ext.getCmp('mon_piutang_dist_nama_pelanggan').setValue(sel[0].get('nama_pelanggan'));
                // strlaporanpenerimaanbarang.removeAll();
                menu_mon_piutang_pelanggan.hide();
            }
        }
    }
});
var menu_mon_piutang_pelanggan = new Ext.menu.Menu();
menu_mon_piutang_pelanggan.add(new Ext.Panel({
    title: 'Pilih Pelanggan',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 400,
    height: 300,
    closeAction: 'hide',
    plain: true,
    items: [grid_mon_piutang_pelanggan],
    buttons: [{
        text: 'Close',
        handler: function () {
            menu_mon_piutang_pelanggan.hide();
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
        str_grid_mon_piutang_pelanggan.load();
        menu_mon_piutang_pelanggan.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
    },
    validationEvent: false,
    validateOnBlur: false,
    trigger1Class: 'x-form-clear-trigger',
    trigger2Class: 'x-form-search-trigger',
    hideTrigger1: true
});
menu_mon_piutang_pelanggan.on('hide', function () {
    var sf = Ext.getCmp('id_search_mon_piutang_pelanggan').getValue();
    if (sf != '') {
        Ext.getCmp('id_search_mon_piutang_pelanggan').setValue('');
        search_grid_mon_piutang_pelanggan.onTrigger2Click();
    }
});
var cbmpdist_pelanggan = new Ext.ux.TwinComboSupplierPO({
    fieldLabel: 'Kode Pelanggan',
    id: 'id_cb_mon_piutang_pelanggan',
    store: str_cb_mon_piutang_pelanggan,
    mode: 'local',
    valueField: 'kd_pelanggan',
    displayField: 'kd_pelanggan',
    typeAhead: true,
    triggerAction: 'all',
    // allowBlank: false,
    editable: false,
    anchor: '90%',
    hiddenName: 'kd_pelanggan',
    emptyText: 'Pilih Kode Pelanggan'
});
// end COMBOBOX SUPPLIER

// start COMBOBOX Status
var arr_cb_status_piutang_dist = [
    [1, "All"],
    [0, "BELUM LUNAS"],
    [2, "LUNAS"]
];
var str_cb_status_piutang_dist = new Ext.data.ArrayStore({
    fields: [{
        name: 'key'
    }, {
        name: 'value'
    }],
    data: arr_cb_status_piutang_dist
});
var cb_status_piutang_dist = new Ext.form.ComboBox({
    fieldLabel: 'Status Piutang',
    id: 'id_cb_status_piutang_dist',
    name: 'status',
    store: str_cb_status_piutang_dist,
    valueField: 'key',
    displayField: 'value',
    mode: 'local',
    forceSelection: true,
    triggerAction: 'all',
    anchor: '90%'
});
// end COMBOBOX STATUS 

// start COMBOBOX FAKTUR
var str_cb_mon_piutang_dist_so = new Ext.data.ArrayStore({
    fields: ['no_so'],
    data : []
});

var str_grid_mon_piutang_dist_so = new Ext.data.Store({
    reader: new Ext.data.JsonReader({
        fields: ['no_faktur','tgl_faktur'],
        root: 'data',
        totalProperty: 'record'
    }),
    proxy: new Ext.data.HttpProxy({
        url: '<?= site_url("monitoring_piutang_distribusi/search_faktur") ?>',
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

var search_grid_mon_piutang_dist_so = new Ext.app.SearchField({
    store: str_grid_mon_piutang_dist_so,
    params: {
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 350,
    id: 'id_search_grid_mon_piutang_dist_so'
});


var grid_mon_piutang_dist_so = new Ext.grid.GridPanel({
    store: str_grid_mon_piutang_dist_so,
    stripeRows: true,
    frame: true,
    border:true,
    columns: [{
        header: 'No Faktur',
        dataIndex: 'no_faktur',
        width: 150,
        sortable: true
    },{
        header: 'Tanggal Faktur',
        dataIndex: 'tgl_faktur',
        width: 200,
        sortable: true
    }],
    tbar: new Ext.Toolbar({
        items: [search_grid_mon_piutang_dist_so]
    }),
    bbar: new Ext.PagingToolbar({
        pageSize: ENDPAGE,
        store: str_grid_mon_piutang_dist_so,
        displayInfo: true
    }),
    listeners: {
        'rowdblclick': function(){
            var sm = this.getSelectionModel();
            var sel = sm.getSelections();
            if (sel.length > 0) {
                Ext.getCmp('id_cb_mon_piutang_dist_so').setValue(sel[0].get('no_faktur'));
                menu_mon_piutang_dist_so.hide();
            }
        }
    }
});

var menu_mon_piutang_dist_so = new Ext.menu.Menu();
menu_mon_piutang_dist_so.add(new Ext.Panel({
    title: 'Pilih No Faktur',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 400,
    height: 300,
    closeAction: 'hide',
    plain: true,
    items: [grid_mon_piutang_dist_so],
    buttons: [{
        text: 'Close',
        handler: function(){
            menu_mon_piutang_dist_so.hide();
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
        str_grid_mon_piutang_dist_so.load();
        menu_mon_piutang_dist_so.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
    },
    validationEvent: false,
    validateOnBlur: false,
    trigger1Class: 'x-form-clear-trigger',
    trigger2Class: 'x-form-search-trigger',
    hideTrigger1: true
});

menu_mon_piutang_dist_so.on('hide', function(){
    var sf = Ext.getCmp('id_search_grid_mon_piutang_dist_so').getValue();
    if( sf != ''){
        Ext.getCmp('id_search_grid_mon_piutang_dist_so').setValue('');
        search_grid_mon_piutang_dist_so.onTrigger2Click();
    }
});

var cb_mon_piutang_dist_so = new Ext.ux.TwinComboMonPiutangSO({
    fieldLabel: 'No Faktur',
    id: 'id_cb_mon_piutang_dist_so',
    store: str_cb_mon_piutang_dist_so,
    mode: 'local',
    valueField: 'no_faktur',
    displayField: 'no_faktur',
    typeAhead: true,
    triggerAction: 'all',
    allowBlank: true,
    editable: false,
    anchor: '90%',
    hiddenName: 'no_faktur',
    emptyText: 'Pilih No Faktur'

});
// end COMBOBOX NO Faktur
// start COMBOBOX No Bayar
var str_cb_mon_piutang_nb = new Ext.data.ArrayStore({
    fields: ['no_so'],
    data : []
});

var str_grid_mon_piutang_nb = new Ext.data.Store({
    reader: new Ext.data.JsonReader({
        fields: ['no_pembayaran_piutang','tgl_bayar','rp_bayar'],
        root: 'data',
        totalProperty: 'record'
    }),
    proxy: new Ext.data.HttpProxy({
        url: '<?= site_url("monitoring_piutang_distribusi/search_no_bayar") ?>',
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

var search_grid_mon_piutang_nb = new Ext.app.SearchField({
    store: str_grid_mon_piutang_nb,
    params: {
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 350,
    id: 'id_search_grid_mon_piutang_nb'
});


var grid_mon_piutang_nb = new Ext.grid.GridPanel({
    store: str_grid_mon_piutang_nb,
    stripeRows: true,
    frame: true,
    border:true,
    columns: [{
        header: 'No Bayar',
        dataIndex: 'no_pembayaran_piutang',
        width: 150,
        sortable: true
    },{
        header: 'Tanggal Bayar',
        dataIndex: 'tgl_bayar',
        width: 100,
        sortable: true
    },{
        header: 'Total Bayar',
        dataIndex: 'rp_bayar',
        width: 100,
        sortable: true
    }],
    tbar: new Ext.Toolbar({
        items: [search_grid_mon_piutang_nb]
    }),
    bbar: new Ext.PagingToolbar({
        pageSize: ENDPAGE,
        store: str_grid_mon_piutang_nb,
        displayInfo: true
    }),
    listeners: {
        'rowdblclick': function(){
            var sm = this.getSelectionModel();
            var sel = sm.getSelections();
            if (sel.length > 0) {
                Ext.getCmp('id_cb_mon_piutang_nb').setValue(sel[0].get('no_pembayaran_piutang'));
                Ext.getCmp('mon_piutang_dist_rp_bayar').setValue(sel[0].get('rp_bayar'));
                menu_mon_piutang_nb.hide();
            }
        }
    }
});

var menu_mon_piutang_nb = new Ext.menu.Menu();
menu_mon_piutang_nb.add(new Ext.Panel({
    title: 'Pilih No Bayar',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 500,
    height: 300,
    closeAction: 'hide',
    plain: true,
    items: [grid_mon_piutang_nb],
    buttons: [{
        text: 'Close',
        handler: function(){
            menu_mon_piutang_nb.hide();
        }
    }]
}));

Ext.ux.TwinComboMonPiutangNB = Ext.extend(Ext.form.ComboBox, {
    initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
    getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
    initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
    onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
    trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
    onTrigger2Click: function(){
        //load store grid
        str_grid_mon_piutang_nb.load();
        menu_mon_piutang_nb.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
    },
    validationEvent: false,
    validateOnBlur: false,
    trigger1Class: 'x-form-clear-trigger',
    trigger2Class: 'x-form-search-trigger',
    hideTrigger1: true
});

menu_mon_piutang_nb.on('hide', function(){
    var sf = Ext.getCmp('id_search_grid_mon_piutang_nb').getValue();
    if( sf != ''){
        Ext.getCmp('id_search_grid_mon_piutang_nb').setValue('');
        search_grid_mon_piutang_nb.onTrigger2Click();
    }
});

var cb_mon_piutang_nb = new Ext.ux.TwinComboMonPiutangNB({
    fieldLabel: 'No Bayar',
    id: 'id_cb_mon_piutang_nb',
    store: str_cb_mon_piutang_nb,
    mode: 'local',
    valueField: 'no_bayar',
    displayField: 'no_bayar',
    typeAhead: true,
    triggerAction: 'all',
    allowBlank: true,
    editable: false,
    anchor: '90%',
    hiddenName: 'no_bayar',
    emptyText: 'Pilih No Bayar'

});
// end COMBOBOX No Bayar

// HEADER MONITORING PIUTANG
var header_monitoring_piutang_dist = {
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
            cbmpdist_pelanggan,
            {
                xtype: 'datefield',
                fieldLabel: 'Periode',
                emptyText: 'Dari Tanggal',
                name: 'tgl_min',
                id: 'mon_piutang_dist_tgl_min',
                maxLength: 255,
                anchor: '90%',
                value: '',
                format: 'd-M-Y'
            },
            cb_status_piutang_dist,
            cb_mon_piutang_nb,
             {
                xtype: 'numericfield',
                currencySymbol: '',
                fieldLabel: 'Rp No Bayar',
                name: 'rp_bayar',
                readOnly: true,
                fieldClass: 'readonly-input',
                id: 'mon_piutang_dist_rp_bayar',
                anchor: '90%',
                value: '',
                emptyText: 'Rp No Bayar'
            }
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
                fieldLabel: 'Nama Pelanggan',
                name: 'nm_pelanggan',
                readOnly: true,
                fieldClass: 'readonly-input',
                id: 'mon_piutang_dist_nama_pelanggan',
                anchor: '90%',
                value: '',
                emptyText: 'Nama Pelanggan'
            },{
                xtype: 'datefield',
                fieldLabel: 's/d',
                emptyText: 'Sampai Tanggal',
                name: 'tgl_max',
                id: 'mon_piutang_dist_tgl_max',
                maxLength: 255,
                anchor: '90%',
                value: '',
                format: 'd-M-Y'
            }
            ,cb_mon_piutang_dist_so,
            {
                xtype: 'numericfield',
                currencySymbol: '',
                fieldLabel: 'Total Pembayaran',
                name: 'total_pembayaran',
                readOnly: true,
                fieldClass: 'readonly-input',
                id: 'mon_piutang_dist_total_bayar',
                anchor: '90%',
                value: '',
                emptyText: 'Total Pembayaran'
            }
        ]
    }],
    buttons: [{
        text: 'Filter',
        formBind: true,
        handler: function () {
            var kd_pelanggan     = Ext.getCmp('id_cb_mon_piutang_pelanggan').getValue();
            var tgl_min         = Ext.getCmp('mon_piutang_dist_tgl_min').getValue();
            var tgl_max         = Ext.getCmp('mon_piutang_dist_tgl_max').getValue();
            var status_piutang  = Ext.getCmp('id_cb_status_piutang_dist').getValue();
            var no_faktur           = Ext.getCmp('id_cb_mon_piutang_dist_so').getValue();
            var no_bayar           = Ext.getCmp('id_cb_mon_piutang_nb').getValue();

            grid_penjualan_monitoring_piutang_dist.store.load({
                params: {
                    'kd_pelanggan' : kd_pelanggan,
                    'tgl_min'     : tgl_min,
                    'tgl_max'     : tgl_max,
                    'status'      : status_piutang,
                    'no_faktur'       : no_faktur,
                    'no_bayar'       : no_bayar
                }
            });
        
        }
    }, {
        text: 'Reset',
        formBind: true,
        handler: function () {
            Ext.getCmp('id_cb_mon_piutang_pelanggan').setValue('');
            Ext.getCmp('mon_piutang_dist_nama_pelanggan').setValue('');
            Ext.getCmp('mon_piutang_dist_tgl_min').setRawValue('');
            Ext.getCmp('mon_piutang_dist_tgl_max').setRawValue('');
            Ext.getCmp('id_cb_status_piutang_dist').setValue('');
            Ext.getCmp('id_cb_mon_piutang_dist_so').setValue('');
            Ext.getCmp('id_cb_mon_piutang_nb').setValue('');
            Ext.getCmp('mon_piutang_dist_rp_bayar').setValue('');
            Ext.getCmp('mon_piutang_dist_total_bayar').setValue('');
            grid_penjualan_monitoring_piutang_dist.store.removeAll();
        }
    }]
};

// start GRID MONITORING PIUTANG
var str_penjualan_monitoring_piutang_dist = new Ext.data.Store({
    reader: new Ext.data.JsonReader({
        fields: [
            'tgl_faktur',
            'no_faktur',
            'kd_pelanggan',
            'nama_pelanggan',
            'nm_penerima',
            'rp_faktur',
            'rp_uang_muka',
            'cash_diskon',
            'rp_bayar',
            'rp_kurang_bayar',
            'status',
            {name: 'pembayaran', allowBlank: false, type: 'int'}
        ],
        root: 'data',
        totalProperty: 'record'
    }),
    proxy: new Ext.data.HttpProxy({
        url: '<?= site_url("monitoring_piutang_distribusi/get_rows") ?>',
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

str_penjualan_monitoring_piutang_dist.on('load', function() {
    str_penjualan_monitoring_piutang_dist.setBaseParam('kd_pelanggan', Ext.getCmp('id_cb_mon_piutang_pelanggan').getValue());
    str_penjualan_monitoring_piutang_dist.setBaseParam('tgl_min', Ext.getCmp('mon_piutang_dist_tgl_min').getValue());
    str_penjualan_monitoring_piutang_dist.setBaseParam('tgl_max', Ext.getCmp('mon_piutang_dist_tgl_max').getValue());
    str_penjualan_monitoring_piutang_dist.setBaseParam('status', Ext.getCmp('id_cb_status_piutang_dist').getValue());
    str_penjualan_monitoring_piutang_dist.setBaseParam('no_faktur', Ext.getCmp('id_cb_mon_piutang_dist_so').getValue());
    var pembayaran = 0;  
        str_penjualan_monitoring_piutang_dist.each(function(node){			
            pembayaran += parseInt(node.data.pembayaran);
            
        });
        pembayaran = Math.round(pembayaran);
        Ext.getCmp('mon_piutang_dist_total_bayar').setValue(pembayaran);
});

var search_penjualan_monitoring_piutang_dist = new Ext.app.SearchField({
    store: str_penjualan_monitoring_piutang_dist,
    params: {
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 220,
    emptyText: 'Tanggal, No SO, Nama Member',
    id: 'id_str_penjualan_monitoring_piutang_dist'
});
var tb_penjualan_monitoring_piutang_dist = new Ext.Toolbar({
    items: [search_penjualan_monitoring_piutang_dist]
});
var sm_penjualan_monitoring_piutang_dist = new Ext.grid.CheckboxSelectionModel();
var grid_penjualan_monitoring_piutang_dist = new Ext.grid.EditorGridPanel({
    id: 'grid_penjualan_monitoring_piutang_dist',
    frame: true,
    border: true,
    stripeRows: true,
    sm: sm_penjualan_monitoring_piutang_dist,
    store: str_penjualan_monitoring_piutang_dist,
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
        header: "Tanggal Faktur",
        dataIndex: 'tgl_faktur',
        sortable: true,
        width: 100
    }, {
        header: "No Faktur",
        dataIndex: 'no_faktur',
        sortable: true,
        width: 120
    },{
        header: "Nama Pelanggan",
        dataIndex: 'nama_pelanggan',
        sortable: true,
        width: 120
    }, {
        xtype: 'numbercolumn',
        header: "Total Faktur",
        dataIndex: 'rp_faktur',
        align: 'right',
        format: '0,0',
        sortable: true,
        width: 100
    }, {
        xtype: 'numbercolumn',
        header: "Uang Muka",
        dataIndex: 'rp_uang_muka',
        align: 'right',
        format: '0,0',
        sortable: true,
        width: 100
    },{
        xtype: 'numbercolumn',
        header: "Cash Diskon",
        dataIndex: 'cash_diskon',
        align: 'right',
        format: '0,0',
        sortable: true,
        width: 100
    },{
        xtype: 'numbercolumn',
        header: "Pembayaran",
        dataIndex: 'pembayaran',
        align: 'right',
        format: '0,0',
        sortable: true,
        width: 100
    }, {
        xtype: 'numbercolumn',
        header: "Total Bayar",
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
    },{
        header: "Status Piutang",
        dataIndex: 'status',
        align: 'right',
        sortable: true,
        width: 100
    }, {
        header: " ",
        dataIndex: '',
        width: 20
    }],
    listeners: {
        'rowdblclick': function () {
            var sm = grid_penjualan_monitoring_piutang_dist.getSelectionModel();
            var sel = sm.getSelections();
            if (sel.length > 0) {

                Ext.Ajax.request({
                    url: '<?= site_url("monitoring_piutang_distribusi/get_data_faktur") ?>/' + sel[0].get('no_faktur'),
                    method: 'POST',
                    params: {},
                    callback: function (opt, success, responseObj) {
                        var win_monitoring_piutang_detail = new Ext.Window({
                            title: 'Monitoring Piutang Distribusi',
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

    tbar: tb_penjualan_monitoring_piutang_dist,
    bbar: new Ext.PagingToolbar({
        pageSize: ENDPAGE,
        store: str_penjualan_monitoring_piutang_dist,
        displayInfo: true
    })
});
// end GRID MONITORING PO

// PANEL MONITORING PO
var monitoring_piutang_distribusi = new Ext.FormPanel({
    id: 'monitoring_piutang_distribusi',
    border: false,
    frame: true,
    autoScroll:true,
    bodyStyle: 'padding-right:20px;',
    labelWidth: 130,
    items: [
        {
            bodyStyle: {margin: '10px 0px 15px 0px'},
            items: [header_monitoring_piutang_dist]
        },
        grid_penjualan_monitoring_piutang_dist
    ]
});

</script>
