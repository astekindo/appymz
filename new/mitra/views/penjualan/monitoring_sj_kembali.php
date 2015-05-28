<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">
// data store
var strKembaliDetailSJ = new Ext.data.Store({
    reader: new Ext.data.JsonReader({
        fields: [
            'kd_produk',
            'nama_produk',
            'qty_do',
            'qty_sj',
            'nm_satuan',
            'keterangan',
            'qty_kembali',
            'ket_kembali'
        ],
        root: 'data',
        totalProperty: 'record'
    }),
    proxy: new Ext.data.HttpProxy({
        url: '<?= site_url("penjualan_sj/get_sj_detail") ?>',
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

var smKembaliDetailSJ = new Ext.grid.CheckboxSelectionModel();

var lcmKembaliDetailSJ = new Ext.ux.grid.LockingColumnModel({
    defaults: { sortable: true},
    columns: [ {
        header: "Kode Barang",
        dataIndex: 'kd_produk',
        width: 100
    },{
        header: "Nama Barang",
        dataIndex: 'nama_produk',
        width: 250
    },{
        header: "Qty DO",
        dataIndex: 'qty_do',
        width: 50
    },{
        header: "Qty SJ",
        dataIndex: 'qty_sj',
        width: 50
    },{
        header: "Qty Kembali",
        dataIndex: 'qty_kembali',
        width: 75
    },{
        header: "Satuan",
        dataIndex: 'nm_satuan',
        width: 50
    },{
        header: "Keterangan",
        dataIndex: 'ket_kembali',
        sortable: false,
        width: 400
    }]

});

var gridKembaliDetailSJ = new Ext.grid.EditorGridPanel({
    id: 'id_grid_kembali_detail_sj',
    store: strKembaliDetailSJ,
    stripeRows: true,
    style: 'margin-bottom:5px;',
    height: 225,
    frame: true,
    border:true,
    loadMask: true,
    sm: smKembaliDetailSJ,
    view: new Ext.ux.grid.LockingGridView(),
    cm: lcmKembaliDetailSJ
});

/* START GRID */
var strKembaliListSJ = new Ext.data.Store({
    reader: new Ext.data.JsonReader({
        fields: [
            'no_do',
            'no_sj',
            'tgl_sj',
            'pic_penerima',
            'tgl_kembali',
            'penerima',
            'keterangan'
        ],
        root: 'data',
        totalProperty: 'record'
    }),
    proxy: new Ext.data.HttpProxy({
        url: '<?= site_url("penjualan_sj/search_sj_kembali") ?>',
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

strKembaliListSJ.on('load', function(){
    strKembaliDetailSJ.removeAll();
});

strKembaliListSJ.load();
// search field
var searchKembaliListSJ = new Ext.app.SearchField({
    store: strKembaliListSJ,
    params: {
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 220,
    emptyText: 'No DO',
    id: 'id_search_sjk_main'
});

searchKembaliListSJ.onTrigger1Click = function(evt) {
    if (this.hasSearch) {
        this.el.dom.value = '';

        // Get the value of search field
        var fid = Ext.getCmp('id_search_sjk_main').getValue();
        var o = { start: 0, query: fid };

        this.store.baseParams = this.store.baseParams || {};
        this.store.baseParams[this.paramName] = '';
        this.store.reload({
            params : o
        });
        this.triggers[0].hide();
        this.hasSearch = false;
    }
};

searchKembaliListSJ.onTrigger2Click = function(evt) {
    var text = this.getRawValue();
    if (text.length < 1) {
        this.onTrigger1Click(evt);
        return;
    }

    // Get the value of search field
    var fid = Ext.getCmp('id_search_sjk_main').getValue();
    var o = { start: 0, query: fid };

    this.store.baseParams = this.store.baseParams || {};
    this.store.baseParams[this.paramName] = text;
    this.store.reload({params:o});
    this.hasSearch = true;
    this.triggers[0].show();
};

var tbKembaliListSJ = new Ext.Toolbar({
    items: [searchKembaliListSJ]
});

var smKembaliListSJ = new Ext.grid.CheckboxSelectionModel();

var lcmKembaliListSJ = new Ext.ux.grid.LockingColumnModel({
    defaults: { sortable: true},
    columns: [{
        header: "No. DO",
        dataIndex: 'no_do',
        sortable: true,
        width: 100
    },{
        header: "No. SJ",
        dataIndex: 'no_sj',
        sortable: true,
        width: 100
    },{
        header: "Tanggal SJ",
        dataIndex: 'tgl_sj',
        sortable: true,
        width: 100
    },{
        header: "PIC Penerima",
        dataIndex: 'pic_penerima',
        sortable: true,
        width: 100
    },{
        header: "Tanggal kembali",
        dataIndex: 'tanggal_kembali',
        sortable: true,
        width: 100
    },{
        header: "Penerima",
        dataIndex: 'penerima',
        sortable: true,
        width: 100
    },{
        header: "Keterangan Pengembalian",
        dataIndex: 'keterangan',
        sortable: true,
        width: 400
    }]
});

var gridKembaliListSJ = new Ext.grid.EditorGridPanel({
    id: 'id_grid_kembali_list_sj',
    store: strKembaliListSJ,
    stripeRows: true,
    style: 'margin-bottom:5px;',
    height: 225,
    frame: true,
    border:true,
    loadMask: true,
    sm: smKembaliListSJ,
    view: new Ext.ux.grid.LockingGridView(),
    cm: lcmKembaliListSJ,
    listeners: {
        'rowclick': function(){
            var sm = gridKembaliListSJ.getSelectionModel();
            var sel = sm.getSelections();
            gridKembaliDetailSJ.store.proxy.conn.url = '<?= site_url("penjualan_sj/search_produk_nosj") ?>/' + sel[0].get('no_sj');
            gridKembaliDetailSJ.store.reload();
        }
    },
    tbar: tbKembaliListSJ,
    bbar: new Ext.PagingToolbar({
        pageSize: ENDPAGE,
        store: strKembaliListSJ,
        displayInfo: true
    })
});


var monitoringSuratJalanKembali = new Ext.FormPanel({
    id: 'penjualan_monitoring_sjk',
    border: false,
    frame: true,
    monitorValid: true,
    labelWidth: 130,
    items: [
        gridKembaliListSJ,
        gridKembaliDetailSJ
    ],
    buttons: [{
        text: 'Reset',
        handler: function(){
            clearDataMSJK();
        }
    }]
});

function clearDataMSJK(){
    Ext.getCmp('penjualan_monitoring_sjk').getForm().reset();
    strKembaliListSJ.removeAll();
    strKembaliDetailSJ.removeAll();
}
</script>
