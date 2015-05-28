<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">    
    /* START GRID */    	
	var strpembelianmonitoringorder = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
				'no_ro',
				'subject',
				'tgl_ro',
				'status_ro',
				'is_close_ro',
				'no_po',
				'kd_supplier',
				'nama_supplier',
				'tanggal_po',
				'status_po',
				'is_close_po',
				'no_do',
				'tanggal_do'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("monitoring_order/get_rows") ?>',
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
    var searchpembelianmonitoringorder = new Ext.app.SearchField({
        store: strpembelianmonitoringorder,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        id: 'idsearchpembelianmonitoringorder'
    });
    
    // top toolbar
    var tbpembelianmonitoringorder = new Ext.Toolbar({
        items: [searchpembelianmonitoringorder]
    });
    
    // checkbox grid
    var smpembelianmonitoringorder = new Ext.grid.CheckboxSelectionModel();

	var pembelianmonitoringorder = new Ext.grid.EditorGridPanel({
        id: 'pembelianmonitoringorder',
        frame: true,
        border: true,
        stripeRows: true,
        sm: smpembelianmonitoringorder,
        store: strpembelianmonitoringorder,
        loadMask: true,
        title: 'Monitoring Order',
        style: 'margin:0 auto;',
        height: 200,
        // width: 550,
        columns: [{
            header: "No RO",
            dataIndex: 'no_ro',
            sortable: true,
            width: 100
        },{
            header: "Subject",
            dataIndex: 'subject',
            sortable: true,
            width: 150
        },{
            header: "Tanggal RO",
            dataIndex: 'tgl_ro',
            sortable: true,
            width: 75
        },{
            header: "Status RO",
            dataIndex: 'status_ro',
            sortable: true,
            width: 60
        },{
            header: "Close RO",
            dataIndex: 'is_close_ro',
            sortable: true,
            width: 60
        },{
            header: "No PO",
            dataIndex: 'no_po',
            sortable: true,
            width: 100
        },{
            header: "Kode Supplier",
            dataIndex: 'kd_supplier',
            sortable: true,
            width: 100
        },{
            header: "Nama Supplier",
            dataIndex: 'nama_supplier',
            sortable: true,
            width: 150
        },{
            header: "Tanggal PO",
            dataIndex: 'tanggal_po',
            sortable: true,
            width: 75
        },{
            header: "Status PO",
            dataIndex: 'status_po',
            sortable: true,
            width: 60
        },{
            header: "Close PO",
            dataIndex: 'is_close_po',
            sortable: true,
            width: 60
        },{
            header: "No DO",
            dataIndex: 'no_do',
            sortable: true,
            width: 150
        },{
            header: "Tanggal DO",
            dataIndex: 'tanggal_do',
            sortable: true,
            width: 75
        }],
				
        tbar: tbpembelianmonitoringorder,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strpembelianmonitoringorder,
            displayInfo: true
        })
    });

   
</script>