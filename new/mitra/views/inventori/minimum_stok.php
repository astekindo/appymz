<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">    
    /* START FORM */ 	
    var strminimumstok = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'kd_produk',
                'nama_produk',
                {name: 'stok'		,type: 'int'},                
                'nm_satuan',
                {name: 'min_stok'		,type: 'int'},
                {name: 'max_stok'		,type: 'int'},
                {name: 'pct_alert'		,type: 'float'},
                {name: 'limit_stok'		,type: 'int'}
                
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("minimum_stok/get_rows") ?>',
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
    var searchminimumstok = new Ext.app.SearchField({
        store: strminimumstok,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        id: 'idsearchminimumstok'
    });
 
    var tbminimumstok = new Ext.Toolbar({
        items: [searchminimumstok]
    });

    var cbGrid = new Ext.grid.CheckboxSelectionModel();
            
    var minimumstok = new Ext.grid.EditorGridPanel({
        id: 'id-minimumstok-gridpanel',
        title: 'MINIMUM STOK',
        frame: true,
        border: true,
        stripeRows: true,
        sm: cbGrid,
        store: strminimumstok,
        loadMask: true,
        style: 'margin:0 auto;',
        height: 400,
        columns: [
            //        cbGrid, 
            {
                header: "Kode Produk",
                dataIndex: 'kd_produk',
                sortable: true,
                width: 100
            },{
                header: "Nama Produk",
                dataIndex: 'nama_produk',
                sortable: true,
                width: 200
            },{ 
                header: "Stok",
                dataIndex: 'stok',
                sortable: true,
                width: 80,renderer: function(value, metaData, record, rowIndex, colIndex, store) {
                    return Ext.util.Format.number(value, '0,000');
                },
                align:'right'
            },{
                header: "Satuan",
                dataIndex: 'nm_satuan',
                sortable: true,
                width: 80
            },{
                header: "Minimal Stok",
                dataIndex: 'min_stok',
                sortable: true,
                width: 100
                ,renderer: function(value, metaData, record, rowIndex, colIndex, store) {
                    return Ext.util.Format.number(value, '0,000');
                },
                align:'right'
            },{
                header: "Maksimal Stok",
                dataIndex: 'max_stok',
                sortable: true,
                width: 100,
                renderer: function(value, metaData, record, rowIndex, colIndex, store) {
                    return Ext.util.Format.number(value, '0,000');
                },
                align:'right'
            },{
                header: "Pct Alert",
                dataIndex: 'pct_alert',
                sortable: true,
                width: 80,
                align:'right'
            }
            ,{
                header: "Limit Stok",
                dataIndex: 'limit_stok',
                sortable: true,
                width: 80,
                renderer: function(value, metaData, record, rowIndex, colIndex, store) {
                    return Ext.util.Format.number(value, '0,000');
                },
                align:'right'
            }
        ],

        tbar: tbminimumstok,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strminimumstok,
            displayInfo: true
        })
    });

    /* notification PO */
    var strnotificationpo = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'no_po',
                'tanggal_po',
                'kd_suplier_po',                
                'nama_supplier',
                'masa_berlaku_po',
                'tgl_berlaku_po',
                'peruntukan',
                'rp_jumlah_po',
                'rp_ppn_po',
                'rp_diskon_po',
                'rp_diskon_po',
                'rp_dp',
                'rp_total_po'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("minimum_stok/get_notificationpo") ?>',
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
    var searchnotificationpo = new Ext.app.SearchField({
        store: strnotificationpo,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        id: 'idsearchnotificationpo'
    });
 
    var tbnotificationpo = new Ext.Toolbar({
        items: [searchnotificationpo]
    });

    var cbGrid = new Ext.grid.CheckboxSelectionModel();
            
    var notificationpo = new Ext.grid.EditorGridPanel({
        id: 'id-notificationpo-gridpanel',
        title: 'NOTIFIKASI PO',
        frame: true,
        border: true,
        stripeRows: true,
        sm: cbGrid,
        store: strnotificationpo,
        loadMask: true,
        style: 'margin:0 auto;',
        height: 400,
        columns: [
            //        cbGrid, 
            {
                header: "No PO",
                dataIndex: 'no_po',
                sortable: true,
                width: 100
            },{
                header: "Tanggal PO",
                dataIndex: 'tanggal_po',
                sortable: true,
                width: 100
            },{ 
                header: "Kode Supp",
                dataIndex: 'kd_suplier_po',
                sortable: true,
                width: 70
            },{
                header: "Nama Supplier",
                dataIndex: 'nama_supplier',
                sortable: true,
                width: 80
            },{
                header: "Masa Berlaku",
                dataIndex: 'masa_berlaku_po',
                sortable: true,
                width: 50
            },{
                header: "Tgl Berlaku",
                dataIndex: 'tgl_berlaku_po',
                sortable: true,
                width: 80
            },{
                header: "Peruntukan",
                dataIndex: 'peruntukan',
                sortable: true,
                width: 80
            },{
                header: "Jumlah PO",
                dataIndex: 'rp_jumlah_po',
                sortable: true,
                width: 80,renderer: function(value, metaData, record, rowIndex, colIndex, store) {
                    return Ext.util.Format.number(value, '0,000');
                },
                align:'right'
            },{
                header: "Jumlah PPN",
                dataIndex: 'rp_ppn_po',
                sortable: true,
                width: 80,
                align:'right'
            },{
                header: "Jml Diskon",
                dataIndex: 'rp_diskon_po',
                sortable: true,
                width: 80,renderer: function(value, metaData, record, rowIndex, colIndex, store) {
                    return Ext.util.Format.number(value, '0,000');
                },
                align:'right'
            },{
                header: "Jumlah DPP",
                dataIndex: 'rp_dp',
                sortable: true,
                width: 80,renderer: function(value, metaData, record, rowIndex, colIndex, store) {
                    return Ext.util.Format.number(value, '0,000');
                },
                align:'right'
            },{
                header: "Total PO",
                dataIndex: 'rp_total_po',
                sortable: true,
                width: 80,renderer: function(value, metaData, record, rowIndex, colIndex, store) {
                    return Ext.util.Format.number(value, '0,000');
                },
                align:'right'
            }
        ],

        tbar: tbnotificationpo,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strnotificationpo,
            displayInfo: true
        })
    });
    	
    /* notification Invoice */
    var strnotificationinvoice = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                {name: 'no_invoice',	type: 'text'},   
                'tgl_invoice',
                'tgl_jth_tempo',
                'kd_supplier',
                'nama_supplier',
                'no_bukti_supplier',
                'tgl_terima_invoice',
                'rp_jumlah',
                'rp_diskon',
                'rp_ppn',
                'rp_total',
                'rp_pelunasan_hutang'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("minimum_stok/get_notificationinvoice") ?>',
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

    var searchnotificationinvoice = new Ext.app.SearchField({
        store: strnotificationinvoice,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        id: 'idsearchnotificationinvoice'
    });
 
    var tbnotificationinvoice = new Ext.Toolbar({
        items: [searchnotificationinvoice]
    });

    var cbGrid = new Ext.grid.CheckboxSelectionModel();
            
    var notificationinvoice = new Ext.grid.EditorGridPanel({
        id: 'id-notificationinvoice-gridpanel',
        title: 'NOTIFIKASI INVOICE',
        frame: true,
        border: true,
        stripeRows: true,
        sm: cbGrid,
        store: strnotificationinvoice,
        loadMask: true,
        style: 'margin:0 auto;',
        height: 400,
        columns: [
            //        cbGrid, 
            {
                header: "No Invoice",
                dataIndex: 'no_invoice',
                sortable: true,
                width: 100
            },{
                header: "Tgl Invoice",
                dataIndex: 'tgl_invoice',
                sortable: true,
                width: 100
            },{ 
                header: "Tgl Jth Tempo",
                dataIndex: 'tgl_jth_tempo',
                sortable: true,
                width: 100
            },{
                header: "Kode Supp",
                dataIndex: 'kd_supplier',
                sortable: true,
                width: 80
            },{
                header: "Nama Supplier",
                dataIndex: 'nama_supplier',
                sortable: true,
                width: 150
            },{
                header: "No Bukti Supp",
                dataIndex: 'no_bukti_supplier',
                sortable: true,
                width: 100
            },{
                header: "Tgl Terima Invoice",
                dataIndex: 'tgl_terima_invoice',
                sortable: true,
                width: 80
            },{
                header: "Jumlah",
                dataIndex: 'rp_jumlah',
                sortable: true,
                width: 80,renderer: function(value, metaData, record, rowIndex, colIndex, store) {
                    return Ext.util.Format.number(value, '0,000');
                },
                align:'right'
            },{
                header: "Discount",
                dataIndex: 'rp_diskon',
                sortable: true,
                width: 80,renderer: function(value, metaData, record, rowIndex, colIndex, store) {
                    return Ext.util.Format.number(value, '0,000');
                },
                align:'right'
            },{
                header: "PPN",
                dataIndex: 'rp_ppn',
                sortable: true,
                width: 80,renderer: function(value, metaData, record, rowIndex, colIndex, store) {
                    return Ext.util.Format.number(value, '0,000');
                },
                align:'right'
            },{
                header: "Total",
                dataIndex: 'rp_total',
                sortable: true,
                width: 80,renderer: function(value, metaData, record, rowIndex, colIndex, store) {
                    return Ext.util.Format.number(value, '0,000');
                },
                align:'right'
            },{
                header: "Pembayaran Hutang",
                dataIndex: 'rp_pelunasan_hutang',
                sortable: true,
                width: 80,renderer: function(value, metaData, record, rowIndex, colIndex, store) {
                    return Ext.util.Format.number(value, '0,000');
                },
                align:'right'
            }
        ],

        tbar: tbnotificationinvoice,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strnotificationinvoice,
            displayInfo: true
        })
    });

    var storenotificationhargajual = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                {name: 'nama_kategori',	type: 'text'},   
                'kd_produk',
                'nama_produk',
                'nm_satuan',
                'nama_ukuran',
                'rp_cogs',
                'rp_het_harga_beli',
                'rp_het_cogs',
                'rp_jual_supermarket',
                'rp_jual_distribusi',
                'net_harga_jual'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("minimum_stok/get_notificationhargajual") ?>',
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
    
    var searchnotificationhargajual = new Ext.app.SearchField({
        store: storenotificationhargajual,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        id: 'idsearchnotificationhargajual'
    });
 
    var tbnotificationhargajual = new Ext.Toolbar({
        items: [searchnotificationhargajual]
    });
    
    var notificationhargajual = new Ext.grid.EditorGridPanel({
        id: 'id-notificationhargajual-gridpanel',
        title: 'NOTIFIKASI NETT HARGA JUAL < (HET BELI / HET COGS) (inc ppn)',
        frame: true,
        border: true,
        stripeRows: true,
        sm: cbGrid,
        store: storenotificationhargajual,
        loadMask: true,
        style: 'margin:0 auto;',
        height: 400,
        columns: [
            {header :"Kategori  ",dataIndex:"nama_kategori",sortable:true,width:100},
            {header :"Kode Produk",dataIndex:"kd_produk",sortable:true,width:100},
            {header :"Nama Produk",dataIndex:"nama_produk",sortable:true,width:100},
            {header :"Satuan",dataIndex:"nm_satuan",sortable:true,width:100},
            {header :"Ukuran",dataIndex:"nama_ukuran",sortable:true,width:100},
            {header :"Rp COGS",dataIndex:"rp_cogs",sortable:true,width:100,renderer: function(value, metaData, record, rowIndex, colIndex, store) {
                    return Ext.util.Format.number(value, '0,000');
                },
                align:'right'},
            {header :"HET Net Price Beli (Inc.PPN)",dataIndex:"rp_het_harga_beli",sortable:true,width:120,renderer: function(value, metaData, record, rowIndex, colIndex, store) {
                    return Ext.util.Format.number(value, '0,000');
                },
                align:'right'},
            {header :"Het COGS (Inc.PPN)",dataIndex:"rp_het_cogs",sortable:true,width:100,renderer: function(value, metaData, record, rowIndex, colIndex, store) {
                    return Ext.util.Format.number(value, '0,000');
                },
                align:'right'},
            {header :"Net Price Jual (Inc.PPN)",dataIndex:"net_harga_jual",sortable:true,width:100,renderer: function(value, metaData, record, rowIndex, colIndex, store) {
                    return Ext.util.Format.number(value, '0,000');
                },
                align:'right'}

        ],

        tbar: tbnotificationhargajual,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: storenotificationhargajual,
            displayInfo: true
        })
    });    
    
    var storelistapprovalhargajual = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                {name: 'no_bukti',	type: 'text'},   
                'tanggal',
                'keterangan',
                'created_by',
                'updated_by'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("minimum_stok/get_listapprovalhargajual") ?>',
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
    
    var searchlistapprovalhargajual = new Ext.app.SearchField({
        store: storelistapprovalhargajual,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        id: 'idsearchlistapprovalhargajual'
    });
 
    var tblistapprovalhargajual = new Ext.Toolbar({
        items: [searchlistapprovalhargajual]
    });
    
    var gridlistapprovalhargajual = new Ext.grid.EditorGridPanel({
        region:'north',
        id: 'id-gridlistapprovalhargajual-gridpanel',
        title:'',
        //title: 'LIST APPROVAL PERUBAHAN HARGA',
        frame: true,
        border: true,
        stripeRows: true,
        sm: cbGrid,
        store: storelistapprovalhargajual,
        loadMask: true,
        //        style: 'margin:0 auto;',
        height: 150,
        columns: [            
            {header :"No.Bukti  ",dataIndex:"no_bukti",sortable:true,width:100},
            {header :"Tanggal",dataIndex:"tanggal",sortable:true,width:100},
            {header :"Keterangan",dataIndex:"keterangan",sortable:true,width:100},
            {header :"Created By",dataIndex:"created_by",sortable:true,width:100},
            {header :"Update By",dataIndex:"updated_by",sortable:true,width:100},
        ],

        tbar: tblistapprovalhargajual,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: storelistapprovalhargajual,
            displayInfo: true
        }),
        listeners:{'rowclick': function(){              
                var sm = gridlistapprovalhargajual.getSelectionModel();                
                var sel = sm.getSelections(); 				//                
                gridlistapprovalhargajual2.store.reload({params:{query:sel[0].get('no_bukti')}});
//                
            }
            }
    });  
    
    var storelistapprovalhargajual2 = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'kd_produk',
                'nama_produk',
                'het_beli_lama',
                'rp_cogscogs_lama',
                'het_cogs_lama',
                'diskon_kons1',
                'diskon_kons2',
                'diskon_kons3',
                'diskon_kons4',
                'diskon_kons5',
                'diskon_member1',
                'diskon_member2',
                'diskon_member3',
                'diskon_member4',
                'diskon_member5',
                'is_bonus',
                'qty_beli_bonus',
                'produk_bonus',
                'qty_bonus',
                'is_bonus_kelipatan',
                'qty_beli_member',
                'produk_bonus_member',
                'qty_member',
                'is_member_kelipatan',
                'net_hrg_supplier_sup_inc',
                'rp_cogs',
                'rp_ongkos_kirim',
                'pct_margin',
                'rp_margin',
                'rp_het_harga_beli',
                'rp_het_cogs',
                'rp_jual_supermarket',
                'rp_jual_distribusi'

            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("minimum_stok/get_listapprovalhargajual2") ?>',
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
    
    var searchlistapprovalhargajual2 = new Ext.app.SearchField({
        store: storelistapprovalhargajual2,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        id: 'idsearchlistapprovalhargajual2'
    });
 
    var tblistapprovalhargajual2 = new Ext.Toolbar({
        items: [searchlistapprovalhargajual2]
    });
    
    var gridlistapprovalhargajual2 = new Ext.grid.EditorGridPanel({
        region:'center',
        id: 'id-gridlistapprovalhargajual2-gridpanel',
        title:'',        
        frame: true,
        border: true,
        stripeRows: true,
        store: storelistapprovalhargajual2,
        loadMask: true,
        style: 'margin:0 auto;',
        minHeight: 200,
        height: 400,
        columns: [            
            {header:"Kd_Produk",dataIndex:"kd_produk",sortable:true,width:100},
            {header:"Nama_Produk",dataIndex:"nama_produk",sortable:true,width:100},
            {header:"Het_Beli_Lama",dataIndex:"het_beli_lama",sortable:true,width:100},
            {header:"Rp_CogsCogs_Lama",dataIndex:"rp_cogscogs_lama",sortable:true,width:100},
            {header:"Het_Cogs_Lama",dataIndex:"het_cogs_lama",sortable:true,width:100},
            {header:"Diskon_Kons1",dataIndex:"diskon_kons1",sortable:true,width:100},
            {header:"Diskon_Kons2",dataIndex:"diskon_kons2",sortable:true,width:100},
            {header:"Diskon_Kons3",dataIndex:"diskon_kons3",sortable:true,width:100},
            {header:"Diskon_Kons4",dataIndex:"diskon_kons4",sortable:true,width:100},
            {header:"Diskon_Kons5",dataIndex:"diskon_kons5",sortable:true,width:100},
            {header:"Diskon_Member1",dataIndex:"diskon_member1",sortable:true,width:100},
            {header:"Diskon_Member2",dataIndex:"diskon_member2",sortable:true,width:100},
            {header:"Diskon_Member3",dataIndex:"diskon_member3",sortable:true,width:100},
            {header:"Diskon_Member4",dataIndex:"diskon_member4",sortable:true,width:100},
            {header:"Diskon_Member5",dataIndex:"diskon_member5",sortable:true,width:100},
            {header:"Is_Bonus",dataIndex:"is_bonus",sortable:true,width:100},
            {header:"Qty_Beli_Bonus",dataIndex:"qty_beli_bonus",sortable:true,width:100},
            {header:"Produk_Bonus",dataIndex:"produk_bonus",sortable:true,width:100},
            {header:"Qty_Bonus",dataIndex:"qty_bonus",sortable:true,width:100},
            {header:"Is_Bonus_Kelipatan",dataIndex:"is_bonus_kelipatan",sortable:true,width:100},
            {header:"Qty_Beli_Member",dataIndex:"qty_beli_member",sortable:true,width:100},
            {header:"Produk_Bonus_Member",dataIndex:"produk_bonus_member",sortable:true,width:100},
            {header:"Qty_Member",dataIndex:"qty_member",sortable:true,width:100},
            {header:"Is_Member_Kelipatan",dataIndex:"is_member_kelipatan",sortable:true,width:100},
            {header:"Net_Hrg_Supplier_Sup_Inc",dataIndex:"net_hrg_supplier_sup_inc",sortable:true,width:100},
            {header:"Rp_Cogs",dataIndex:"rp_cogs",sortable:true,width:100},
            {header:"Rp_Ongkos_Kirim",dataIndex:"rp_ongkos_kirim",sortable:true,width:100},
            {header:"Pct_Margin",dataIndex:"pct_margin",sortable:true,width:100},
            {header:"Rp_Margin",dataIndex:"rp_margin",sortable:true,width:100},
            {header:"Rp_Het_Harga_Beli",dataIndex:"rp_het_harga_beli",sortable:true,width:100},
            {header:"Rp_Het_Cogs",dataIndex:"rp_het_cogs",sortable:true,width:100},
            {header:"Rp_Jual_Supermarket",dataIndex:"rp_jual_supermarket",sortable:true,width:100},
            {header:"Rp_Jual_Distribusi",dataIndex:"rp_jual_distribusi",sortable:true,width:100}
        ],

        //        tbar: tblistapprovalhargajual2,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: storelistapprovalhargajual2,
            displayInfo: true
        })
    });  
    
    var listapprovalhargajual = new Ext.Panel({
        id: 'idlistapprovalhargajual',
        title: 'LIST APPROVAL PERUBAHAN HARGA',
        border: false,
        frame: true,
        autoScroll:true,		
        bodyStyle:'padding-right:20px;',
        layout:'border',
        labelWidth: 130,
        items:[gridlistapprovalhargajual,gridlistapprovalhargajual2]
    });

    /* notifikasi lokasi default */
    var strNotifLokasiDefault = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'nama_kategori1',
                'nama_kategori2',
                'nama_kategori3',
                'nama_kategori4',
                'nm_satuan',
                'kd_produk',
                'nama_produk'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("minimum_stok/get_notif_lokasidefault") ?>',
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
    var searchNotifLokasiDefault = new Ext.app.SearchField({
        store: strNotifLokasiDefault,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        id: 'id_search_notification_po'
    });

    var tbNotifLokasiDefault = new Ext.Toolbar({
        items: [searchNotifLokasiDefault]
    });

    var cbGrid = new Ext.grid.CheckboxSelectionModel();

    var notiflokasidefault = new Ext.grid.EditorGridPanel({
        id: 'id-notiflokasidefault-gridpanel',
        title: 'PRODUK TANPA LOKASI DEFAULT',
        frame: true,
        border: true,
        stripeRows: true,
        sm: cbGrid,
        store: strNotifLokasiDefault,
        loadMask: true,
        style: 'margin:0 auto;',
        height: 400,
        columns: [
            {
                header: "Nama Kategori 1",
                dataIndex: 'nama_kategori1',
                sortable: true,
                width: 150
            },{
                header: "Nama Kategori 2",
                dataIndex: 'nama_kategori2',
                sortable: true,
                width: 150
            },{
                header: "Nama Kategori 3",
                dataIndex: 'nama_kategori3',
                sortable: true,
                width: 150
            },{
                header: "Nama Kategori 4",
                dataIndex: 'nama_kategori4',
                sortable: true,
                width: 150
            },{
                header: "Satuan",
                dataIndex: 'nm_satuan',
                sortable: true,
                width: 50
            },{
                header: "Kode Produk",
                dataIndex: 'kd_produk',
                sortable: true,
                width: 100
            },{
                header: "Nama",
                dataIndex: 'nama_produk',
                sortable: true,
                width: 250
            }
        ],

        tbar: tbNotifLokasiDefault,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strNotifLokasiDefault,
            displayInfo: true
        })
    });

    var minimumstokpanel = new Ext.FormPanel({
		
        id: 'minimumstok',
        border: false,
        frame: false,
        autoScroll:true,
        layout:'fit',
        items: [{
                xtype: 'tabpanel',
                height: 450,
                activeTab: 0,
                deferredRender: false,
                items: [minimumstok, notificationpo, notificationinvoice,notificationhargajual,listapprovalhargajual, notiflokasidefault]
            }]
    });

</script>