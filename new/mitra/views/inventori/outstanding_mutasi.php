<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">
    /**
     * Data store u/ grid
     */
    var str_osdmutasi = new Ext.data.GroupingStore({
        reader: new Ext.data.JsonReader({
            fields: [
                'no_mutasi_stok',
                'tgl_mutasi',
                'kd_produk',
                'nama_produk',
                'nm_satuan',
                'lokasi_awal',
                'lokasi_tujuan',
                'qty',
                'userid',
                'no_ref',
                'approval_out',
                'tgl_approval_out'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("mutasi_barang/outstanding_mutasi") ?>',
            method: 'POST'
        }),
        listeners: {
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        },
        groupField: 'no_mutasi_stok'
    });

    // checkbox grid
    var cbGrid = new Ext.grid.CheckboxSelectionModel();

    var osdmutasilokasi = new Ext.grid.GridPanel({
        id: 'osdmutasilokasi',
        frame: true,
        border: true,
        stripeRows: true,
        sm: cbGrid,
        store: str_osdmutasi,
        loadMask: true,
        title: 'Barang',
        style: 'margin:0 auto;',
        height: 500,
        view: new Ext.grid.GroupingView({
            forceFit: true,
            groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "Items" : "Item"]})'
        }),
        columns: [
            {
                header: "No. Mutasi",
                dataIndex:'no_mutasi_stok',
                sortable: true,
                hidden: true,
                width: 50
            },
            {
                header: "Tanggal Mutasi",
                dataIndex:'tgl_mutasi',
                sortable: true,
                width: 90
            },
            {
                header: "Kode Produk",
                dataIndex:'kd_produk',
                sortable: true,
                width: 100
            },
            {
                header: "Nama Produk",
                dataIndex:'nama_produk',
                sortable: true,
                width: 200
            },
            {
                header: "Qty",
                dataIndex:'qty',
                sortable: true,
                width: 30
            },
            {
                header: "Satuan",
                dataIndex:'nm_satuan',
                sortable: true,
                width: 50
            },
            {
                header: "Lokasi awal",
                dataIndex:'lokasi_awal',
                sortable: true,
                width: 250
            },
            {
                header: "Lokasi tujuan",
                dataIndex:'lokasi_tujuan',
                sortable: true,
                width: 250
            },
            {
                header: "User",
                dataIndex:'userid',
                sortable: true,
                width: 100
            },
            {
                header: "No. Ref",
                dataIndex:'no_ref',
                sortable: true,
                width: 50
            },
            {
                header: "Approval",
                dataIndex:'approval_out',
                sortable: true,
                width: 80
            },
            {
                header: "Tgl. Approval",
                dataIndex:'tgl_approval_out',
                sortable: true,
                width: 100
            }
        ],
        bbar: new Ext.PagingToolbar({ pageSize: ENDPAGE, store: str_osdmutasi, displayInfo: true }),
        listeners: {
            'afterRender': function() {
                osdmutasilokasi.store.load();
            }
        }
    });
</script>
