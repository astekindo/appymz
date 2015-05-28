<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

/* 
* To change this license header, choose License Headers in Project Properties.
* To change this template file, choose Tools | Templates
* and open the template in the editor.
*/

<script type="text/javascript">
    
    // start GRID MONITORING PR   	
	var strPenjOutStrukBintang2 = new Ext.data.Store({
		reader: new Ext.data.JsonReader({
			fields: [
				'no_so',
				'tgl_so',
				'kd_produk',
				'nama_produk',
				'qty_kirim',
				'qty_dikirim'
			],
			root: 'data',
			totalProperty: 'record'
		}),
		proxy: new Ext.data.HttpProxy({
			url: '<?= site_url("outstanding_struk_bintang2/finalGetRows") ?>',
			method: 'POST'
		}),
		listeners: {
			loadexception: function (event, options, response, error) {

			}
		}
	});
        strPenjOutStrukBintang2.load();
        
	var searchPenjOutStrukBintang2 = new Ext.app.SearchField({
		store: strPenjOutStrukBintang2,
		params: {
			start: STARTPAGE,
			limit: ENDPAGE
		},
		width: 220,
		emptyText: 'No PR, Subject',
		id: 'idsearchPenjOutStrukBintang2'
	});
	var tbPenjOutStrukBintang2 = new Ext.Toolbar({
		items: [searchPenjOutStrukBintang2]
	});
	var smPenjOutStrukBintang2 = new Ext.grid.CheckboxSelectionModel();
	var gridPenjOutStrukBintang2 = new Ext.grid.EditorGridPanel({
		id: 'gridPenjOutStrukBintang2',
		frame: true,
		border: true,
		stripeRows: true,
		sm: smPenjOutStrukBintang2,
		store: strPenjOutStrukBintang2,
		loadMask: false,
		style: 'margin:0 auto;',
		height: 400,
		columns: [{
			header: "No SO",
			dataIndex: 'no_so',
			sortable: true,
			width: 200
		}, {
			header: "Tanggal SO",
			dataIndex: 'tgl_so',
			sortable: true,
			width: 150
		}, {
			header: "Kode Produk",
			dataIndex: 'kd_produk',
			sortable: true,
			width: 150
		}, {
			header: "Nama Produk",
			dataIndex: 'nama_produk',
			sortable: true,
			width: 260
		}, {
			header: "Qty Kirim",
			dataIndex: 'qty_kirim',
			sortable: true,
			width: 150
		}, {
			header: "Qty Dikirim",
			dataIndex: 'qty_dikirim',
			sortable: true,
			width: 100
		}],
		tbar: tbPenjOutStrukBintang2,
		bbar: new Ext.PagingToolbar({
			pageSize: ENDPAGE,
			store: strPenjOutStrukBintang2,
			displayInfo: true
		})
	});
	// end GRID MONITORING PR

    // PANEL MONITORING PR
    var pembelianmonitoringPR = new Ext.FormPanel({
        id: 'outstanding_struk_bintang2',
        border: false,
        frame: true,
        //autoScroll:true,	 
        bodyStyle: 'padding-right:20px;',
        labelWidth: 130,
        items: [{
                bodyStyle: {
                    margin: '10px 0px 15px 0px'
                },
                items: []
            }, gridPenjOutStrukBintang2]
    });

</script>
