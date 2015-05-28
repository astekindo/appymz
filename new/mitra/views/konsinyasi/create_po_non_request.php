<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">
   var strcbkcponrsuplier = new Ext.data.ArrayStore({
        fields: ['kd_supplier'],
        data : []
    });
	
	var strgridkcponrsuplier = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_supplier', 'nama_supplier','top','pic','pkp','alamat'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("konsinyasi_receive_order/search_supplier") ?>',
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
	
	strgridkcponrsuplier.on('load', function(){
		 Ext.getCmp('id_searchgridkcponrsuplier').focus();
	});
	
	var searchgridkcponrsuplier = new Ext.app.SearchField({
        store: strgridkcponrsuplier,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE			
        },
        width: 350,
		id: 'id_searchgridkcponrsuplier'
    });
	
	
	var gridkcponrsuplier = new Ext.grid.GridPanel({
        store: strgridkcponrsuplier,
        stripeRows: true,
        frame: true,
        border:true,
        columns: [{
            header: 'Kode Supplier',
            dataIndex: 'kd_supplier',
            width: 80,
            sortable: true,           
        },{
            header: 'Nama Supplier',
            dataIndex: 'nama_supplier',
            width: 300,
			sortable: true,         
        },{
            header: 'PIC',
            dataIndex: 'pic',
            width: 100,
			sortable: true,         
        },{
            header: 'Alamat',
            dataIndex: 'alamat',
            width: 200,
			sortable: true,         
        },{
            header: 'Waktu TOP',
            dataIndex: 'top',
            width: 80,
			sortable: true,         
        }],
		tbar: new Ext.Toolbar({
	        items: [searchgridkcponrsuplier]
	    }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridkcponrsuplier,
            displayInfo: true
        }),
		listeners: {
			'rowdblclick': function(){			
				var sm = this.getSelectionModel();
				var sel = sm.getSelections();
				if (sel.length > 0) {				
					Ext.getCmp('id_cbkcponrsuplier').setValue(sel[0].get('kd_supplier'));
					Ext.getCmp('kcponr_nama_supplier').setValue(sel[0].get('nama_supplier'));
					Ext.getCmp('kcponr_pic_supplier').setValue(sel[0].get('pic'));
					// if(sel[0].get('pic') == 'NON PKP'){
						// Ext.getCmp('kcponr_ppn_persen').setValue(0);		
					// }else{
						// Ext.getCmp('kcponr_ppn_persen').setValue(10);	
					// }
					if(sel[0].get('pkp') == 1){
						Ext.getCmp('kcponr_pkp_supplier').setValue('YA');
						Ext.getCmp('kcponr_ppn_persen').setValue(10);	
					}else{
						Ext.getCmp('kcponr_pkp_supplier').setValue('TIDAK');
						Ext.getCmp('kcponr_ppn_persen').setValue(0);	
					}
					Ext.getCmp('kcponr_waktu_top').setValue(sel[0].get('top'));
					Ext.getCmp('kcponr_alamat_supplier').setValue(sel[0].get('alamat'));
					
					cbkcponrtop.setValue();
					cbkcponrtop.store.removeAll();
					cbkcponrtop.store.proxy.conn.url = '<?= site_url("konsinyasi_create_po_non_request/get_term_of_payment_by_supplier") ?>/' + sel[0].get('kd_supplier');
	                cbkcponrtop.store.reload();
					
					Ext.Ajax.request({
						url: '<?= site_url("konsinyasi_create_po_non_request/get_nilai_parameter_pic") ?>',
						method: 'POST',
						callback:function(opt,success,responseObj){
							var de = Ext.util.JSON.decode(responseObj.responseText);
							if(de.success==true){
								Ext.getCmp('kcponr_pic_penerima').setValue(de.data.nilai_parameter);					
							}
						}
					});
					
					Ext.Ajax.request({
						url: '<?= site_url("konsinyasi_create_po_non_request/get_nilai_parameter_alamat") ?>',
						method: 'POST',
						callback:function(opt,success,responseObj){
							var de = Ext.util.JSON.decode(responseObj.responseText);
							if(de.success==true){
								Ext.getCmp('kcponr_alamat_penerima').setValue(de.data.nilai_parameter);					
							}
						}
					});
					
					Ext.Ajax.request({
						url: '<?= site_url("konsinyasi_create_po_non_request/get_nilai_parameter_remark") ?>',
						method: 'POST',
						callback:function(opt,success,responseObj){
							var de = Ext.util.JSON.decode(responseObj.responseText);
							if(de.success==true){
								Ext.getCmp('kcponr_remark').setValue(de.data.nilai_parameter);					
							}
						}
					});
					menukcponrsuplier.hide();
					Ext.getCmp('kcponr_waktu_top').focus();
				}
			}
		}
    });
	
	var menukcponrsuplier = new Ext.menu.Menu();
    menukcponrsuplier.add(new Ext.Panel({
        title: 'Pilih Supplier',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridkcponrsuplier],
        buttons: [{
            text: 'Close',
            handler: function(){
                menukcponrsuplier.hide();
            }
        }]
    }));
    
    Ext.ux.TwinCombokcponrSuplier = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
			//load store grid
            strgridkcponrsuplier.load();
            menukcponrsuplier.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
	
	menukcponrsuplier.on('hide', function(){
		var sf = Ext.getCmp('id_searchgridkcponrsuplier').getValue();
		if( sf != ''){
			Ext.getCmp('id_searchgridkcponrsuplier').setValue('');
			searchgridkcponrsuplier.onTrigger2Click();
		}
	});
	
	var cbkcponrsuplier = new Ext.ux.TwinCombokcponrSuplier({
        fieldLabel: 'Supplier <span class="asterix">*</span>',
        id: 'id_cbkcponrsuplier',
        store: strcbkcponrsuplier,
		mode: 'local',
        valueField: 'kd_supplier',
        displayField: 'kd_supplier',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_supplier',
        emptyText: 'Pilih Supplier'
    });
	    	
		
	var strcbkcponrtop = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['waktu_top'],
            root: 'data',
            totalProperty: 'record'
        }),
		
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("konsinyasi_create_po_non_request/get_term_of_payment_by_supplier") ?>',			
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
		
	var cbkcponrtop = new Ext.form.ComboBox({
        fieldLabel: 'Term Of Payment <span class="asterix">*</span>',
        id: 'kcponr_waktu_top',
        store: strcbkcponrtop,
        valueField: 'waktu_top',
        displayField: 'waktu_top',
        typeAhead: true,
        triggerAction: 'all',
		editable:false,
        allowBlank: false,
		mode:'local',
        anchor: '90%',
        hiddenName: 'waktu_top',
        emptyText: 'Term Of Payment',
    });
	
    var headerkonsinyasicreatepononrequest = {
        layout: 'column',
        border: false,
        items: [{
            columnWidth: .4,
            layout: 'form',
            border: false,
            labelWidth: 110,
            defaults: { labelSeparator: ''},
            items: [
			cbkcponrsuplier,{
                xtype: 'textfield',
                fieldLabel: 'Nama Supplier',
                name: 'nama_supplier',
				fieldClass:'readonly-input',
                readOnly:true,                
                id: 'kcponr_nama_supplier',                
                anchor: '90%',
                value: ''
            },{
                xtype: 'textfield',
                fieldLabel: 'PIC Supplier',
                name: 'pic',
				fieldClass:'readonly-input',
                readOnly:true,                
                id: 'kcponr_pic_supplier',                
                anchor: '90%',
                value: ''
            },{
                xtype: 'textfield',
                fieldLabel: 'Status PKP',
                name: 'pkp',
				fieldClass:'readonly-input',
                readOnly:true,                
                id: 'kcponr_pkp_supplier',                
                anchor: '90%',
                value: ''
            },{
				xtype: 'hidden',
                fieldLabel: 'Alamat Kirim',
                name: 'alamat',                               
                id: 'kcponr_alamat_supplier',                
                anchor: '90%',
                value: ''
			}
			]
        }, {
            columnWidth: .3,
            layout: 'form',
            border: false,
            labelWidth: 110,
            defaults: { labelSeparator: ''},
            items: [{
                xtype: 'textfield',
                fieldLabel: 'No Surat Pesanan',
                name: 'no_po',
                // allowBlank: false,
				readOnly:true,
				fieldClass:'readonly-input',
                id: 'kcponr_no_po',
                maxLength: 255,
                anchor: '90%',
				value:''
            // },{
                // xtype: 'numberfield',
                // fieldLabel: 'Term Of Payment <span class="asterix">*</span>',
                // name: 'waktu_top',                       
                // id: 'kcponr_waktu_top', 
				// allowBlank: false, 
				// selectOnFocus: true,              
                // anchor: '90%',
                // value: ''
            },cbkcponrtop,{
				xtype: 'datefield',
                fieldLabel: 'Masa Berlaku <span class="asterix">*</span>',
                name: 'tgl_berlaku_po',				
                allowBlank:false,   
				format:'d-m-Y',  
				editable:false,           
                id: 'kcponr_tgl_berlaku_po',                
                anchor: '90%',
                value: ''
			},new Ext.form.Checkbox({
				xtype: 'checkbox',
				fieldLabel: 'Scan Barcode',
				boxLabel:'Ya',
				name:'scan_barcode',
				id:'kcponr_scan_barcode',
				checked: false,
				inputValue: '1',
				autoLoad : true
			})]
        },{
            columnWidth: .3,
            layout: 'form',
            border: false,
            labelWidth: 100,
            defaults: { labelSeparator: ''},
            items: [{
                xtype: 'textfield',
                fieldLabel: 'Tanggal',
                name: 'tanggal_po',
				fieldClass:'readonly-input',
                readOnly:true,
                allowBlank: false,
                id: 'kcponr_tanggal_po',                
                anchor: '90%',
                value: ''
            },{
                fieldLabel: 'Peruntukan <span class="asterix">*</span>',
                xtype: 'radiogroup',
                columnWidth: [.5, .5],
				allowBlank:false,
                items: [{
                    boxLabel: 'Supermarket',
                    name: 'kd_peruntukan',
                    inputValue: '0',
                    id: 'kcponr_peruntukan_supermarket',
					checked:true
                }, {
                    boxLabel: 'Distribusi',
                    name: 'kd_peruntukan',
                    inputValue: '1',
                    id: 'kcponr_peruntukan_distribusi'
                }]
            },{
                xtype: 'textfield',
                fieldLabel: 'Order By',
                name: 'order_by',
                readOnly:false,        
				fieldClass:'readonly-input',       
                id: 'kcponr_order_by',                
                anchor: '90%',
                value: ''
            },]
        }
		]
    }
    
     var strkonsinyasicreatepononrequest = new Ext.data.Store({
        autoSave:false,
        reader: new Ext.data.JsonReader({
            fields: [
                {name: 'kd_produk', allowBlank: false, type: 'text'},
				{name: 'nama_produk', allowBlank: false, type: 'text'},                
                {name: 'nm_satuan', allowBlank: false, type: 'text'},
                {name: 'qty', allowBlank: false, type: 'int'},					
				{name: 'disk_persen_supp1_po', allowBlank: false, type: 'float'},
                {name: 'disk_persen_supp2_po', allowBlank: false, type: 'float'},
                {name: 'disk_persen_supp3_po', allowBlank: false, type: 'float'},
                {name: 'disk_persen_supp4_po', allowBlank: false, type: 'float'},
				{name: 'disk_amt_supp1_po', allowBlank: false, type: 'float'},
                {name: 'disk_amt_supp2_po', allowBlank: false, type: 'float'},
                {name: 'disk_amt_supp3_po', allowBlank: false, type: 'float'},
                {name: 'disk_amt_supp4_po', allowBlank: false, type: 'float'},	
				{name: 'disk_amt_supp5_po', allowBlank: false, type: 'int'},			
                {name: 'disk_persen_supp1', allowBlank: false, type: 'text'},
                {name: 'disk_persen_supp2', allowBlank: false, type: 'text'},
                {name: 'disk_persen_supp3', allowBlank: false, type: 'text'},
                {name: 'disk_persen_supp4', allowBlank: false, type: 'text'},
				{name: 'disk_persen_supp5', allowBlank: false, type: 'text'},
				{name: 'total_diskon', allowBlank: false, type: 'int'},
                {name: 'hrg_supplier', allowBlank: false, type: 'int'},
				{name: 'harga', allowBlank: false, type: 'float'},
				{name: 'dpp_po', allowBlank: false, type: 'float'},
				{name: 'jumlah', allowBlank: false, type: 'float'},				
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        writer: new Ext.data.JsonWriter(
        {
            encode: true,
            writeAllFields: true
        })
    });
    
	strkonsinyasicreatepononrequest.on('update', function(){
		var jumlah = 0;
		
		strkonsinyasicreatepononrequest.each(function(node){
			jumlah += parseFloat(node.data.jumlah);
		});
		
		var diskon_persen = Ext.getCmp('kcponr_diskon_persen').getValue();
		var diskon_rp = diskon_persen * jumlah;
		var sub_jumlah = jumlah - diskon_rp;
		var ppn_persen = Ext.getCmp('kcponr_ppn_persen').getValue();
		var ppn_rp = (ppn_persen * sub_jumlah)/100;
		var grand_total = sub_jumlah + ppn_rp;
		
		jumlah = Math.round(jumlah);
		sub_jumlah = Math.round(sub_jumlah);
		ppn_rp = Math.round(ppn_rp);
		grand_total = Math.round(grand_total);
		
		Ext.getCmp('kcponr_jumlah').setValue(jumlah);
		Ext.getCmp('kcponr_diskon_persen').setValue(diskon_persen);
		Ext.getCmp('kcponr_diskon_rp').setValue(diskon_rp);
		Ext.getCmp('kcponr_sub_jumlah').setValue(sub_jumlah);
		Ext.getCmp('kcponr_ppn_persen').setValue(ppn_persen);
		Ext.getCmp('kcponr_ppn_rp').setValue(ppn_rp);
		Ext.getCmp('kcponr_total').setValue(grand_total);
		var sisa_bayar = grand_total - Ext.getCmp('kcponr_dp').getValue();
		Ext.getCmp('kcponr_sisa_bayar').setValue(sisa_bayar);
	});
	
	var strcbkcponrproduk = new Ext.data.ArrayStore({
        fields: ['kd_produk'],
        data : []
    });
	
	var strgridkcponrproduk = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
				{name: 'kd_produk', allowBlank: false, type: 'text'},
				{name: 'kd_produk_supp', allowBlank: false, type: 'text'},
				{name: 'kd_produk_lama', allowBlank: false, type: 'text'},
				{name: 'nama_produk', allowBlank: false, type: 'text'},                
                {name: 'nm_satuan', allowBlank: false, type: 'text'},
				{name: 'waktu_top', allowBlank: false, type: 'int'},		
                {name: 'min_stok', allowBlank: false, type: 'int'},				
                {name: 'max_stok', allowBlank: false, type: 'int'},	
				{name: 'min_order', allowBlank: false, type: 'int'},				
                {name: 'is_kelipatan_order', allowBlank: false, type: 'text'},				
                {name: 'jml_stok', allowBlank: false, type: 'int'},	
                {name: 'kd_peruntukkan', allowBlank: false, type: 'text'},	
				{name: 'disk_persen_supp1_po', allowBlank: false, type: 'float'},
                {name: 'disk_persen_supp2_po', allowBlank: false, type: 'float'},
                {name: 'disk_persen_supp3_po', allowBlank: false, type: 'float'},
                {name: 'disk_persen_supp4_po', allowBlank: false, type: 'float'},
				{name: 'disk_amt_supp1_po', allowBlank: false, type: 'float'},
                {name: 'disk_amt_supp2_po', allowBlank: false, type: 'float'},
                {name: 'disk_amt_supp3_po', allowBlank: false, type: 'float'},
                {name: 'disk_amt_supp4_po', allowBlank: false, type: 'float'},	
				{name: 'disk_amt_supp5_po', allowBlank: false, type: 'int'},			
                {name: 'disk_persen_supp1', allowBlank: false, type: 'text'},
                {name: 'disk_persen_supp2', allowBlank: false, type: 'text'},
                {name: 'disk_persen_supp3', allowBlank: false, type: 'text'},
                {name: 'disk_persen_supp4', allowBlank: false, type: 'text'},
				{name: 'disk_persen_supp5', allowBlank: false, type: 'text'},
                {name: 'total_diskon', allowBlank: false, type: 'int'},
				{name: 'hrg_supplier', allowBlank: false, type: 'int'},
				{name: 'harga', allowBlank: false, type: 'float'},
				{name: 'dpp_po', allowBlank: false, type: 'float'},
				{name: 'jumlah', allowBlank: false, type: 'float'},
			],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("konsinyasi_create_po_non_request/search_produk_by_supplier") ?>',
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
	
	var searchFieldPONonReq = new Ext.app.SearchField({
		width: 220,
		params: {
            start: STARTPAGE,
            limit: ENDPAGE			
        },
		id: 'search_query_pononreq',
		store: strgridkcponrproduk
	});
	
	strgridkcponrproduk.on('load', function(){
		var scan = Ext.getCmp('kcponr_scan_barcode').getValue();
		if(scan){
			Ext.getCmp('kcponr_scan_barcode_kode').focus();
		}else{
			strgridkcponrproduk.setBaseParam('kd_supplier',Ext.getCmp('id_cbkcponrsuplier').getValue());
			strgridkcponrproduk.setBaseParam('waktu_top',Ext.getCmp('kcponr_waktu_top').getValue());
			Ext.getCmp('search_query_pononreq').focus();
		}
	});
	
	searchFieldPONonReq.onTrigger1Click = function(evt) {
		if (this.hasSearch) {
			this.el.dom.value = '';
			
			// Get the value of search field
			var fid = Ext.getCmp('id_cbkcponrsuplier').getValue();
			var top = Ext.getCmp('kcponr_waktu_top').getValue();
			var pkp = Ext.getCmp('kcponr_pkp_supplier').getValue();
			if(pkp == 'YA'){
				pkp = 1;
			}else	pkp = 2;	
			var o = { start: 0, kd_supplier: fid, waktu_top : top, pkp:pkp};
			
			this.store.baseParams = this.store.baseParams || {};
			this.store.baseParams[this.paramName] = '';
			this.store.reload({
						params : o
					});
			this.triggers[0].hide();
			this.hasSearch = false;
		}
	};
	
		searchFieldPONonReq.onTrigger2Click = function(evt) {
			var text = this.getRawValue();
			if (text.length < 1) {
			this.onTrigger1Click();
			return;
			}

			// Get the value of search field
			var fid = Ext.getCmp('id_cbkcponrsuplier').getValue();
			var top = Ext.getCmp('kcponr_waktu_top').getValue();
			var pkp = Ext.getCmp('kcponr_pkp_supplier').getValue();
			if(pkp == 'YA'){
				pkp = 1;
			}else	pkp = 2;	
			
			var o = { start: 0, kd_supplier: fid, waktu_top : top, pkp:pkp};

			this.store.baseParams = this.store.baseParams || {};
			this.store.baseParams[this.paramName] = text;
			this.store.reload({params:o});
			this.hasSearch = true;
			this.triggers[0].show();
		};
	
    // top toolbar
    var tbsearchbarangPONonReq = new Ext.Toolbar({
        items: [searchFieldPONonReq]
    });
	
	var gridkcponrproduk = new Ext.grid.GridPanel({
        store: strgridkcponrproduk,
        stripeRows: true,
        frame: true,
        border:true,
        columns: [{
            header: 'Kode produk',
            dataIndex: 'kd_produk',
            width: 100,
            sortable: true,			
            
        },{
            header: 'Kode Produk Supplier',
            dataIndex: 'kd_produk_supp',
            width: 100,
            sortable: true,			
            
        },{
            header: 'Kode Produk Lama',
            dataIndex: 'kd_produk_lama',
            width: 100,
            sortable: true,			
            
        },{
            header: 'Nama produk',
            dataIndex: 'nama_produk',
            width: 350,
			sortable: true,         
        },{
            header: 'Waktu TOP',
            dataIndex: 'waktu_top',
            width: 70,
			sortable: true,         
        },{
            header: 'Satuan',
            dataIndex: 'nm_satuan',
            width: 70,			         
        },{
       		header: '',
            dataIndex: 'kd_peruntukkan',
        },{
       		header: '',
            dataIndex: 'disk_persen_supp1_po',
        },{
       		header: '',
            dataIndex: 'disk_persen_supp2_po',            
        },{
       		header: '',
            dataIndex: 'disk_persen_supp3_po',            
        },{
       		header: '',
            dataIndex: 'disk_persen_supp4_po',            
        },{
       		header: '',
            dataIndex: 'disk_amt_supp1_po',            
        },{
       		header: '',
            dataIndex: 'disk_amt_supp2_po',            
        },{
       		header: '',
            dataIndex: 'disk_amt_supp3_po',           
        },{
       		header: '',
            dataIndex: 'disk_amt_supp4_po',           
        },{
       		header: '',
            dataIndex: 'disk_amt_supp5_po',            
        },{
           	header: '',
            dataIndex: 'disk_persen_supp1', 
        },{
           	header: '',
            dataIndex: 'disk_persen_supp2',
        },{
            header: '',
            dataIndex: 'disk_persen_supp3',
        },{
            header: '',
            dataIndex: 'disk_persen_supp4',
        },{
            header: '',
            dataIndex: 'disk_persen_supp5',
        },{
            header: '',
            dataIndex: 'total_diskon',
        },{
            header: '',
            dataIndex: 'hrg_supplier',           
        },{
            header: '',
            dataIndex: 'harga',           
        },{
            header: '',
            dataIndex: 'dpp_po',       
        },{
            header: '',
            dataIndex: 'jumlah',
        },],
		tbar: tbsearchbarangPONonReq,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridkcponrproduk,
            displayInfo: true
        }),
		listeners: {
			'rowdblclick': function(){
				var sm = this.getSelectionModel();
				var sel = sm.getSelections();
				if (sel.length > 0) {		
					if(sel[0].get('waktu_top') != Ext.getCmp('kcponr_waktu_top').getValue()){
						Ext.Msg.show({
			                title: 'Error',
			                msg: 'Produk tidak bisa dipilih<br>karena waktu TOP harus sama',
			                modal: true,
			                icon: Ext.Msg.ERROR,
			                buttons: Ext.Msg.OK			               
			            });
						return;
					}	
					   
					var pkp = Ext.getCmp('kcponr_pkp_supplier').getValue();
					if(pkp == 'YA'){
						pkp = 1;
					}else	pkp = 2;	
					
					Ext.Ajax.request({
                        url: '<?= site_url("konsinyasi_create_po_non_request/search_produk_by_supplier") ?>',
                        method: 'POST',
                        params: {
							kd_supplier: Ext.getCmp('id_cbkcponrsuplier').getValue(),
							pkp: pkp,
							waktu_top: Ext.getCmp('kcponr_waktu_top').getValue(),
							kd_peruntukan: sel[0].get('kd_peruntukkan'),
							kd_produk: sel[0].get('kd_produk'),
							action: 'validate'
                        },
						callback:function(opt,success,responseObj){
							var de = Ext.util.JSON.decode(responseObj.responseText);
							if(de.success==true){
								Ext.getCmp('ekcponr_kd_produk').setValue(sel[0].get('kd_produk'));
								Ext.getCmp('ekcponr_nama_produk').setValue(sel[0].get('nama_produk'));                      
								Ext.getCmp('ekcponr_nm_satuan').setValue(sel[0].get('nm_satuan'));
								Ext.getCmp('ekcponr_min_stok').setValue(sel[0].get('min_stok'));
								Ext.getCmp('ekcponr_max_stok').setValue(sel[0].get('max_stok'));
								Ext.getCmp('ekcponr_jml_stok').setValue(sel[0].get('jml_stok'));
																
								Ext.getCmp('ekcponr_disk_persen_supp1_po').setValue(sel[0].get('disk_persen_supp1_po'));
								Ext.getCmp('ekcponr_disk_persen_supp2_po').setValue(sel[0].get('disk_persen_supp2_po'));	
								Ext.getCmp('ekcponr_disk_persen_supp3_po').setValue(sel[0].get('disk_persen_supp3_po'));	
								Ext.getCmp('ekcponr_disk_persen_supp4_po').setValue(sel[0].get('disk_persen_supp4_po'));
								
								Ext.getCmp('ekcponr_disk_amt_supp1_po').setValue(sel[0].get('disk_amt_supp1_po'));
								Ext.getCmp('ekcponr_disk_amt_supp2_po').setValue(sel[0].get('disk_amt_supp2_po'));	
								Ext.getCmp('ekcponr_disk_amt_supp3_po').setValue(sel[0].get('disk_amt_supp3_po'));	
								Ext.getCmp('ekcponr_disk_amt_supp4_po').setValue(sel[0].get('disk_amt_supp4_po'));
								Ext.getCmp('ekcponr_disk_amt_supp5_po').setValue(sel[0].get('disk_amt_supp5_po'));
								
								Ext.getCmp('ekcponr_disk_persen_supp1').setValue(sel[0].get('disk_persen_supp1'));
								Ext.getCmp('ekcponr_disk_persen_supp2').setValue(sel[0].get('disk_persen_supp2'));	
								Ext.getCmp('ekcponr_disk_persen_supp3').setValue(sel[0].get('disk_persen_supp3'));	
								Ext.getCmp('ekcponr_disk_persen_supp4').setValue(sel[0].get('disk_persen_supp4'));	
								Ext.getCmp('ekcponr_disk_persen_supp5').setValue(sel[0].get('disk_persen_supp5'));
								
								Ext.getCmp('ekcponr_total_diskon').setValue(sel[0].get('total_diskon'));
								
								Ext.getCmp('ekcponr_hrg_supplier').setValue(sel[0].get('hrg_supplier'));	
								Ext.getCmp('ekcponr_harga').setValue(sel[0].get('harga'));
								var dpp_po = sel[0].get('dpp_po');
								// dpp_po = Math.round(dpp_po * 100) / 100;
								Ext.getCmp('ekcponr_harga_exc').setValue(dpp_po);
								// Ext.getCmp('ekcponr_harga_exc_view').setValue(dpp_po_view);
								Ext.getCmp('ekcponr_jumlah').setValue(sel[0].get('jumlah'));	
								Ext.getCmp('ekcponr_min_order').setValue(sel[0].get('min_order'));
								Ext.getCmp('ekcponr_is_kelipatan_order').setValue(sel[0].get('is_kelipatan_order'));
								Ext.getCmp('ekcponr_qty').setValue(0);
								Ext.getCmp('ekcponr_qty').focus();
							}else{
								Ext.getCmp('ekcponr_min_order').setValue(sel[0].get('min_order'));
								Ext.getCmp('ekcponr_is_kelipatan_order').setValue(sel[0].get('is_kelipatan_order'));
								Ext.getCmp('ekcponr_kd_produk').setValue('');
								Ext.getCmp('ekcponr_nama_produk').setValue('');                      
								Ext.getCmp('ekcponr_nm_satuan').setValue('');
								Ext.getCmp('ekcponr_min_stok').setValue('');
								Ext.getCmp('ekcponr_max_stok').setValue('');
								Ext.getCmp('ekcponr_jml_stok').setValue('');
																
								Ext.getCmp('ekcponr_disk_persen_supp1_po').setValue('');
								Ext.getCmp('ekcponr_disk_persen_supp2_po').setValue('');	
								Ext.getCmp('ekcponr_disk_persen_supp3_po').setValue('');	
								Ext.getCmp('ekcponr_disk_persen_supp4_po').setValue('');
								
								Ext.getCmp('ekcponr_disk_amt_supp1_po').setValue('');
								Ext.getCmp('ekcponr_disk_amt_supp2_po').setValue('');	
								Ext.getCmp('ekcponr_disk_amt_supp3_po').setValue('');	
								Ext.getCmp('ekcponr_disk_amt_supp4_po').setValue('');
								Ext.getCmp('ekcponr_disk_amt_supp5_po').setValue('');
								
								Ext.getCmp('ekcponr_disk_persen_supp1').setValue('');
								Ext.getCmp('ekcponr_disk_persen_supp2').setValue('');	
								Ext.getCmp('ekcponr_disk_persen_supp3').setValue('');	
								Ext.getCmp('ekcponr_disk_persen_supp4').setValue('');	
								Ext.getCmp('ekcponr_disk_persen_supp5').setValue('');
								
								Ext.getCmp('ekcponr_total_diskon').setValue('');
								
								Ext.getCmp('ekcponr_hrg_supplier').setValue('');	
								Ext.getCmp('ekcponr_harga').setValue('');
								Ext.getCmp('ekcponr_harga_exc').setValue('');
								Ext.getCmp('ekcponr_jumlah').setValue('');
								Ext.getCmp('ekcponr_min_order').setValue('');
								Ext.getCmp('ekcponr_is_kelipatan_order').setValue('');
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
								Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');
								
							}
						}
                    });
					// strgridkcponrproduk.load({
						// params:{
							// kd_supplier: Ext.getCmp('kcponr_kd_supplier').getValue(),
							// kd_peruntukan: sel[0].get('kd_peruntukkan'),
							// kd_produk: sel[0].get('kd_produk'),
							// action: 'validate_pr'
						// }, scope: this,
						// callback: function(records, operation, success) {
							// if (!success) {
								// Ext.getCmp('ekcponr_kd_produk').setValue('');
								// Ext.getCmp('ekcponr_nama_produk').setValue('');                      
								// Ext.getCmp('ekcponr_nm_satuan').setValue('');
								// Ext.getCmp('ekcponr_min_stok').setValue('');
								// Ext.getCmp('ekcponr_max_stok').setValue('');
								// Ext.getCmp('ekcponr_jml_stok').setValue('');
																
								// Ext.getCmp('ekcponr_disk_persen_supp1_po').setValue('');
								// Ext.getCmp('ekcponr_disk_persen_supp2_po').setValue('');	
								// Ext.getCmp('ekcponr_disk_persen_supp3_po').setValue('');	
								// Ext.getCmp('ekcponr_disk_persen_supp4_po').setValue('');
								
								// Ext.getCmp('ekcponr_disk_amt_supp1_po').setValue('');
								// Ext.getCmp('ekcponr_disk_amt_supp2_po').setValue('');	
								// Ext.getCmp('ekcponr_disk_amt_supp3_po').setValue('');	
								// Ext.getCmp('ekcponr_disk_amt_supp4_po').setValue('');
								// Ext.getCmp('ekcponr_disk_amt_supp5_po').setValue('');
								
								// Ext.getCmp('ekcponr_disk_persen_supp1').setValue('');
								// Ext.getCmp('ekcponr_disk_persen_supp2').setValue('');	
								// Ext.getCmp('ekcponr_disk_persen_supp3').setValue('');	
								// Ext.getCmp('ekcponr_disk_persen_supp4').setValue('');	
								// Ext.getCmp('ekcponr_disk_persen_supp5').setValue('');
								
								// Ext.getCmp('ekcponr_total_diskon').setValue('');
								
								// Ext.getCmp('ekcponr_hrg_supplier').setValue('');	
								// Ext.getCmp('ekcponr_harga').setValue('');
								// Ext.getCmp('ekcponr_jumlah').setValue('');
								
								// Ext.Msg.show({
									// title: 'Error',
									// msg: 'Ada PR dengan Kode Produk "'+sel[0].get('kd_produk')+'"',
									// modal: true,
									// icon: Ext.Msg.ERROR,
									// buttons: Ext.Msg.OK
								// });
								// Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');
							// }else{
								
							// }
						// }
					// });
                    
					menukcponrproduk.hide();
				}
			}
		}
    });
	
	var menukcponrproduk = new Ext.menu.Menu();
    menukcponrproduk.add(new Ext.Panel({
        title: 'Pilih Barang',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 630,
        height: 250,
        closeAction: 'hide',
        plain: true,
        items: [gridkcponrproduk],
        buttons: [{
            text: 'Close',
            handler: function(){
                menukcponrproduk.hide();
            }
        }]
    }));
	
	var menukcponrprodukscan = new Ext.Window();
	menukcponrprodukscan.add(new Ext.Panel({
        title: 'Scan Barcode Produk',
        layout: 'form',
        border: false,
        frame: true,
        autoScroll:true, 
        bodyStyle:'padding-right:20px;',
        labelWidth: 130,
        buttonAlign: 'left',
        width: 400,
        height: 250,
        closeAction: 'hide',
        //plain: true,
        //modal: true,
        //monitorValid: true,       
        items: [{
	                xtype: 'textfield',
	                fieldLabel: 'Scan Barcode',
	                name: 'scan_barcode',
	                id: 'kcponr_scan_barcode_kode',                
	                anchor: '90%',
	                value:'',
						listeners:{
						specialKey: function( field, e ) {
							if ( e.getKey() == e.RETURN || e.getKey() == e.ENTER ) {
								var supermarket = Ext.getCmp('kcponr_peruntukan_supermarket').getValue();
								var distribusi = Ext.getCmp('kcponr_peruntukan_distribusi').getValue();
								
								if (supermarket){
									kd_peruntukkan = '0';
								}else if (distribusi) {
									kd_peruntukkan = '1';									
								}
								
								var pkp = Ext.getCmp('kcponr_pkp_supplier').getValue();
								if(pkp == 'YA'){
									pkp = 1;
								}else	pkp = 2;	
															
								Ext.Ajax.request({
									url: '<?= site_url("konsinyasi_create_po_non_request/search_produk_by_supplier") ?>',
									method: 'POST',
									params: {									
										kd_supplier: Ext.getCmp('id_cbkcponrsuplier').getValue(),
										pkp: pkp,
										waktu_top: Ext.getCmp('kcponr_waktu_top').getValue(),
										kd_peruntukan: kd_peruntukkan,
										query: Ext.getCmp('kcponr_scan_barcode_kode').getValue(),
										sender: 'scan'
									},
									callback:function(opt,success,responseObj){
										var scn = Ext.util.JSON.decode(responseObj.responseText);
										// alert(scn.data[0]['kd_produk']);
										if(scn.success==true){
											Ext.getCmp('kcponr_kd_produk_scan').setValue(scn.data[0]['kd_produk']);   
											Ext.getCmp('kcponr_kd_produk_supp_scan').setValue(scn.data[0]['kd_produk_supp']);   
											Ext.getCmp('kcponr_kd_produk_lama_scan').setValue(scn.data[0]['kd_produk_lama']);
											Ext.getCmp('kcponr_nama_produk_scan').setValue(scn.data[0]['nama_produk']);
										}
									}
								});
								if(Ext.getCmp('kcponr_kd_produk_scan').getValue() != ''){
									Ext.getCmp('kcponr_submit_button').focus();
								}
								
							}
						}
					}
	            },{
	                xtype: 'textfield',
	                fieldLabel: 'Kode Produk',
	                name: 'kd_produk',
	                readOnly:true,
					fieldClass:'readonly-input',
	                id: 'kcponr_kd_produk_scan',                
	                anchor: '90%',
	                value:''
	            },{
	                xtype: 'textfield',
	                fieldLabel: 'Kode Produk Supplier',
	                name: 'kd_produk_supp',
	                readOnly:true,
					fieldClass:'readonly-input',
	                id: 'kcponr_kd_produk_supp_scan',                
	                anchor: '90%',
	                value:''
	            },{
	                xtype: 'textfield',
	                fieldLabel: 'Kode Produk Lama',
	                name: 'kd_produk_lama',
	                readOnly:true,
					fieldClass:'readonly-input',
	                id: 'kcponr_kd_produk_lama_scan',                
	                anchor: '90%',
	                value:''
	            },{
	                xtype: 'textfield',
	                fieldLabel: 'Nama Produk',
	                name: 'nama_produk',
	                readOnly:true,
					fieldClass:'readonly-input',
	                id: 'kcponr_nama_produk_scan',                
	                anchor: '90%',
	                value:''
	            }
				],
        buttons: [{
            text: 'Submit',
			formBind: true,
			id:'kcponr_submit_button',
            handler: function(){
					var supermarket = Ext.getCmp('kcponr_peruntukan_supermarket').getValue();
					var distribusi = Ext.getCmp('kcponr_peruntukan_distribusi').getValue();
					
					if (supermarket){
						kd_peruntukkan = '0';
					}else if (distribusi) {
						kd_peruntukkan = '1';									
					}
					var pkp = Ext.getCmp('kcponr_pkp_supplier').getValue();
					if(pkp == 'YA'){
						pkp = 1;
					}else	pkp = 2;	
					
					Ext.Ajax.request({
						url: '<?= site_url("konsinyasi_create_po_non_request/search_produk_by_supplier") ?>',
						method: 'POST',
						params: {							
							kd_supplier: Ext.getCmp('id_cbkcponrsuplier').getValue(),
							pkp: pkp,
							waktu_top: Ext.getCmp('kcponr_waktu_top').getValue(),
							kd_peruntukan: kd_peruntukkan,
							query: Ext.getCmp('kcponr_scan_barcode_kode').getValue(),
							kd_produk: Ext.getCmp('kcponr_kd_produk_scan').getValue(),
							action: 'validate', 
							sender: 'scan'
						},
						callback:function(opt,success,responseObj){
							var scn = Ext.util.JSON.decode(responseObj.responseText);
							if(scn.success==true){
								Ext.getCmp('ekcponr_kd_produk').setValue(scn.data[0]['kd_produk']);
								Ext.getCmp('ekcponr_nama_produk').setValue(scn.data[0]['nama_produk']);                      
								Ext.getCmp('ekcponr_nm_satuan').setValue(scn.data[0]['nm_satuan']);
								Ext.getCmp('ekcponr_min_stok').setValue(scn.data[0]['min_stok']);
								Ext.getCmp('ekcponr_max_stok').setValue(scn.data[0]['max_stok']);
								Ext.getCmp('ekcponr_jml_stok').setValue(scn.data[0]['jml_stok']);
																
								Ext.getCmp('ekcponr_disk_persen_supp1_po').setValue(scn.data[0]['disk_persen_supp1_po']);
								Ext.getCmp('ekcponr_disk_persen_supp2_po').setValue(scn.data[0]['disk_persen_supp2_po']);	
								Ext.getCmp('ekcponr_disk_persen_supp3_po').setValue(scn.data[0]['disk_persen_supp3_po']);	
								Ext.getCmp('ekcponr_disk_persen_supp4_po').setValue(scn.data[0]['disk_persen_supp4_po']);
								
								Ext.getCmp('ekcponr_disk_amt_supp1_po').setValue(scn.data[0]['disk_amt_supp1_po']);
								Ext.getCmp('ekcponr_disk_amt_supp2_po').setValue(scn.data[0]['disk_amt_supp2_po']);	
								Ext.getCmp('ekcponr_disk_amt_supp3_po').setValue(scn.data[0]['disk_amt_supp3_po']);	
								Ext.getCmp('ekcponr_disk_amt_supp4_po').setValue(scn.data[0]['disk_amt_supp4_po']);
								Ext.getCmp('ekcponr_disk_amt_supp5_po').setValue(scn.data[0]['disk_amt_supp5_po']);
								
								Ext.getCmp('ekcponr_disk_persen_supp1').setValue(scn.data[0]['disk_persen_supp1']);
								Ext.getCmp('ekcponr_disk_persen_supp2').setValue(scn.data[0]['disk_persen_supp2']);	
								Ext.getCmp('ekcponr_disk_persen_supp3').setValue(scn.data[0]['disk_persen_supp3']);	
								Ext.getCmp('ekcponr_disk_persen_supp4').setValue(scn.data[0]['disk_persen_supp4']);	
								Ext.getCmp('ekcponr_disk_persen_supp5').setValue(scn.data[0]['disk_persen_supp5']);
								
								Ext.getCmp('ekcponr_total_diskon').setValue(scn.data[0]['total_diskon']);
								
								Ext.getCmp('ekcponr_hrg_supplier').setValue(scn.data[0]['hrg_supplier']);	
								Ext.getCmp('ekcponr_harga').setValue(scn.data[0]['harga']);
								var dpp_po = scn.data[0]['dpp_po'];
								// dpp_po = Math.round(dpp_po * 100) / 100;
								Ext.getCmp('ekcponr_harga_exc').setValue(dpp_po);
								// Ext.getCmp('ekcponr_harga_exc_view').setValue(dpp_po_view);
								Ext.getCmp('ekcponr_jumlah').setValue(scn.data[0]['jumlah']);	
								Ext.getCmp('ekcponr_min_order').setValue(scn.data[0]['min_order']);
								Ext.getCmp('ekcponr_is_kelipatan_order').setValue(scn.data[0]['is_kelipatan_order']);
								Ext.getCmp('ekcponr_qty').setValue(0);
								Ext.getCmp('ekcponr_qty').focus();
							}else{
								Ext.getCmp('ekcponr_min_order').setValue('');
								Ext.getCmp('ekcponr_is_kelipatan_order').setValue('');
								Ext.getCmp('ekcponr_kd_produk').setValue('');
								Ext.getCmp('ekcponr_nama_produk').setValue('');                      
								Ext.getCmp('ekcponr_nm_satuan').setValue('');
								Ext.getCmp('ekcponr_min_stok').setValue('');
								Ext.getCmp('ekcponr_max_stok').setValue('');
								Ext.getCmp('ekcponr_jml_stok').setValue('');
																
								Ext.getCmp('ekcponr_disk_persen_supp1_po').setValue('');
								Ext.getCmp('ekcponr_disk_persen_supp2_po').setValue('');	
								Ext.getCmp('ekcponr_disk_persen_supp3_po').setValue('');	
								Ext.getCmp('ekcponr_disk_persen_supp4_po').setValue('');
								
								Ext.getCmp('ekcponr_disk_amt_supp1_po').setValue('');
								Ext.getCmp('ekcponr_disk_amt_supp2_po').setValue('');	
								Ext.getCmp('ekcponr_disk_amt_supp3_po').setValue('');	
								Ext.getCmp('ekcponr_disk_amt_supp4_po').setValue('');
								Ext.getCmp('ekcponr_disk_amt_supp5_po').setValue('');
								
								Ext.getCmp('ekcponr_disk_persen_supp1').setValue('');
								Ext.getCmp('ekcponr_disk_persen_supp2').setValue('');	
								Ext.getCmp('ekcponr_disk_persen_supp3').setValue('');	
								Ext.getCmp('ekcponr_disk_persen_supp4').setValue('');	
								Ext.getCmp('ekcponr_disk_persen_supp5').setValue('');
								
								Ext.getCmp('ekcponr_total_diskon').setValue('');
								
								Ext.getCmp('ekcponr_hrg_supplier').setValue('');	
								Ext.getCmp('ekcponr_harga').setValue('');
								Ext.getCmp('ekcponr_harga_exc').setValue('');
								Ext.getCmp('ekcponr_jumlah').setValue('');
								Ext.getCmp('ekcponr_min_order').setValue('');
								Ext.getCmp('ekcponr_is_kelipatan_order').setValue('');
								Ext.Msg.show({
                                    title: 'Error',
                                    msg: scn.errMsg,
                                    modal: true,
                                    icon: Ext.Msg.ERROR,
                                    buttons: Ext.Msg.OK,
                                    fn: function(btn){
                                        if (btn == 'ok' && scn.errMsg == 'Session Expired') {
                                            window.location = '<?= site_url("auth/login") ?>';
                                        }
                                    }
                                });
								Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');
								
							}
							
							menukcponrprodukscan.hide();
						}
					});
            }
        },{
            text: 'Close',
            handler: function(){
                menukcponrprodukscan.hide();
            }
        }]
    }));
	
	menukcponrproduk.on('hide', function(){
		var sf = Ext.getCmp('search_query_pononreq').getValue();
		if( sf != ''){
			Ext.getCmp('search_query_pononreq').setValue('');
			searchFieldPONonReq.onTrigger2Click();
		}
	});
	
    
    Ext.ux.TwinCombokcponrProduk = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
			//load store grid
			var pkp = Ext.getCmp('kcponr_pkp_supplier').getValue();
			if(pkp == 'YA'){
				pkp = 1;
			}else	pkp = 2;
            strgridkcponrproduk.load({
				params: {
                	kd_supplier: Ext.getCmp('id_cbkcponrsuplier').getValue(),
                	waktu_top: Ext.getCmp('kcponr_waktu_top').getValue(),
                	pkp: pkp
                }
			});
			
			var scan = Ext.getCmp('kcponr_scan_barcode').getValue();
			if(scan){
				Ext.getCmp('kcponr_scan_barcode_kode').setValue('');   
				Ext.getCmp('kcponr_kd_produk_scan').setValue('');   
				Ext.getCmp('kcponr_kd_produk_supp_scan').setValue('');   
				Ext.getCmp('kcponr_kd_produk_lama_scan').setValue('');
				Ext.getCmp('kcponr_nama_produk_scan').setValue('');
				var win = Ext.WindowMgr;
				// win.zseed='80000';
				win.get(menukcponrprodukscan).show();
			}else{
				menukcponrproduk.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
			}
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
	
	
    var editorkonsinyasicreatepononrequest = new Ext.ux.grid.RowEditor({
        saveText: 'Update'
    });
	    

    var gridkonsinyasicreatepononrequest = new Ext.grid.GridPanel({
        store: strkonsinyasicreatepononrequest,
        stripeRows: true,
        height: 200,
        frame: true,
        border:true,
		tbar: [{
            icon: BASE_ICONS + 'add.png',
            text: 'Add',
            handler: function(){
				if(Ext.getCmp('id_cbkcponrsuplier').getValue() == ''){
					Ext.Msg.show({
			                title: 'Error',
			                msg: 'Silahkan pilih supplier terlebih dulu',
			                modal: true,
			                icon: Ext.Msg.ERROR,
			                buttons: Ext.Msg.OK			               
			            });
					return;
				}
				
				if(Ext.getCmp('kcponr_waktu_top').getValue() == ''){
					Ext.Msg.show({
			                title: 'Error',
			                msg: 'Waktu TOP harus diisi terlebih dulu',
			                modal: true,
			                icon: Ext.Msg.ERROR,
			                buttons: Ext.Msg.OK			               
			            });
					return;
				}
				
				var rowkonsinyasicreatepononrequest = new gridkonsinyasicreatepononrequest.store.recordType({
		            kd_produk : '',
		            qty: '0'
		        });                
                editorkonsinyasicreatepononrequest.stopEditing();
                strkonsinyasicreatepononrequest.insert(0, rowkonsinyasicreatepononrequest);
                gridkonsinyasicreatepononrequest.getView().refresh();
                gridkonsinyasicreatepononrequest.getSelectionModel().selectRow(0);
                editorkonsinyasicreatepononrequest.startEditing(0);
            }
        },{
            ref: '../removeBtn',
            icon: BASE_ICONS + 'delete.gif',
            text: 'Remove',
            disabled: true,
            handler: function(){
                editorkonsinyasicreatepononrequest.stopEditing();
                var s = gridkonsinyasicreatepononrequest.getSelectionModel().getSelections();
                for(var i = 0, r; r = s[i]; i++){
                    strkonsinyasicreatepononrequest.remove(r);
                }
				
				var jumlah = 0;
		
				strkonsinyasicreatepononrequest.each(function(node){
					jumlah += parseFloat(node.data.jumlah);
				});
				
				var diskon_persen = Ext.getCmp('kcponr_diskon_persen').getValue();
				var diskon_rp = diskon_persen * jumlah;
				var sub_jumlah = jumlah - diskon_rp;
				var ppn_persen = Ext.getCmp('kcponr_ppn_persen').getValue();
				var ppn_rp = (ppn_persen * sub_jumlah)/100;
				var grand_total = sub_jumlah + ppn_rp;
				
		
				jumlah = Math.round(jumlah);
				sub_jumlah = Math.round(sub_jumlah);
				ppn_rp = Math.round(ppn_rp);
				grand_total = Math.round(grand_total);
				
				Ext.getCmp('kcponr_jumlah').setValue(jumlah);
				Ext.getCmp('kcponr_diskon_persen').setValue(diskon_persen);
				Ext.getCmp('kcponr_diskon_rp').setValue(diskon_rp);
				Ext.getCmp('kcponr_sub_jumlah').setValue(sub_jumlah);
				Ext.getCmp('kcponr_ppn_persen').setValue(ppn_persen);
				Ext.getCmp('kcponr_ppn_rp').setValue(ppn_rp);
				Ext.getCmp('kcponr_total').setValue(grand_total);
				var sisa_bayar = grand_total - Ext.getCmp('kcponr_dp').getValue();
				Ext.getCmp('kcponr_sisa_bayar').setValue(sisa_bayar);
            }
        }],
        plugins: [editorkonsinyasicreatepononrequest],
        columns: [new Ext.grid.RowNumberer({width: 30}),{
            header: 'Kode',
            dataIndex: 'kd_produk',
            width: 150,
            sortable: true,
            editor: new Ext.ux.TwinCombokcponrProduk({
				id: 'ekcponr_kd_produk',
		        store: strcbkcponrproduk,
				mode: 'local',
		        valueField: 'kd_produk',
		        displayField: 'kd_produk',
		        typeAhead: true,
		        triggerAction: 'all',
		        allowBlank: false,
		        editable: false,
		        hiddenName: 'kd_produk',
		        emptyText: 'Pilih Produk'
				
		    })	
        },{
            header: 'Nama Barang',
            dataIndex: 'nama_produk',
            width: 250,
            editor: new Ext.form.TextField({
                readOnly: true,
                id: 'ekcponr_nama_produk'
            })
        },{
            header: 'Satuan',
            dataIndex: 'nm_satuan',
            width: 60,
            editor: new Ext.form.TextField({
                readOnly: true,
                id: 'ekcponr_nm_satuan'
            })
        },{
            xtype: 'numbercolumn',
            header: 'Qty Order',
            dataIndex: 'qty',           
            width: 80,
			align: 'center',
            sortable: true,
            format: '0,0',
            editor: {
                xtype: 'numberfield',
                id: 'ekcponr_qty',
                allowBlank: false,
				selectOnFocus:true,
				listeners:{
					'render': function(c) {
					  c.getEl().on('keyup', function() {
						if(Ext.getCmp('ekcponr_kd_produk').getValue() == ''){
							Ext.Msg.show({
								title: 'Error',
								msg: 'Silahkan pilih produk terlebih dulu',
								modal: true,
								icon: Ext.Msg.ERROR,
								buttons: Ext.Msg.OK			               
						    	});
							this.setValue('0');
							return;
						}
						var jumlah = this.getValue() * Ext.getCmp('ekcponr_harga_exc').getRawValue();
						Ext.getCmp('ekcponr_jumlah').setValue(jumlah);
						
						var ekcponr_max = Ext.getCmp('ekcponr_max_stok').getValue();
						var ekcponr_min = Ext.getCmp('ekcponr_min_stok').getValue();
						var ekcponr_jml = Ext.getCmp('ekcponr_jml_stok').getValue();
						var ekcponr_qty = this.getValue();
						var ekcponr_validasi = ekcponr_qty + ekcponr_jml;
						if(ekcponr_validasi > ekcponr_max){
							Ext.Msg.show({
								title: 'Error',
								msg: 'Qty Order + Jml Stok tidak boleh lebih besar dari Max. Stok',
								modal: true,
								icon: Ext.Msg.ERROR,
								buttons: Ext.Msg.OK,
								fn: function(btn){
									if (btn == 'ok') {
										Ext.getCmp('ekcponr_qty').reset()
									}
								}                            
							});
							Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');
							this.focus();
							return;
						}

						//if(ekcponr_validasi < ekcponr_min){
						//	Ext.Msg.show({
						//		title: 'Error',
						//		msg: 'Qty Order + Jml Stok tidak boleh lebih kecil dari Min. Stok',
						//		modal: true,
						//		icon: Ext.Msg.ERROR,
						//		buttons: Ext.Msg.OK,
						//		fn: function(btn){
						//			if (btn == 'ok') {
						//				Ext.getCmp('ekcponr_qty').reset()
						//			}
						//		}                            
						//	});
						//	Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');
						//	this.focus();
						//	return;
						//}
					  }, c);
					},
					// 'change': function(){
					// }
				}
            }
        },{
            xtype: 'numbercolumn',
            header: 'Min.Stok',
            dataIndex: 'min_stok',			
            width: 80,            
			format: '0,0',
            editor: {
                xtype: 'numberfield',
				id: 'ekcponr_min_stok',
                readOnly: true,
            }
        },{
            xtype: 'numbercolumn',
            header: 'Max.Stok',
            dataIndex: 'max_stok',			
            width: 80,            
			format: '0,0',
            editor: {
                xtype: 'numberfield',
				id: 'ekcponr_max_stok',
                readOnly: true,
            }
        },{
            xtype: 'numbercolumn',
            header: 'Min Order',
            dataIndex: 'min_order',			
            width: 80,            
			format: '0,0',
            editor: {
                xtype: 'numberfield',
				id: 'ekcponr_min_order',
                readOnly: true,
            }
        },{
            header: 'Order Kelipatan',
            dataIndex: 'is_kelipatan_order',			
            width: 80,          
            editor: new Ext.form.TextField({                
				readOnly: true,
				id: 'ekcponr_is_kelipatan_order'
            })
        },{
            xtype: 'numbercolumn',
            header: 'Jml.Stok Pot. SO',
            dataIndex: 'jml_stok',			
            width: 80,            
			format: '0,0',
            editor: {
                xtype: 'numberfield',
				id: 'ekcponr_jml_stok',
                readOnly: true,
            }
        },{
            xtype: 'numbercolumn',
            header: 'Harga Supplier',
            dataIndex: 'hrg_supplier',           
            width: 100,
			align: 'right',
            sortable: true,
            format: '0,0',
            editor: {
                xtype: 'numberfield',
                id: 'ekcponr_hrg_supplier',
                readOnly: true,
            }
        },{
            // xtype: 'numbercolumn',
            header: 'Diskon 1',
            dataIndex: 'disk_persen_supp1',           
            width: 100,
			align: 'right',
            sortable: true,
            format: '0,0',
            editor: {
                xtype: 'textfield',
                id: 'ekcponr_disk_persen_supp1',
                readOnly: true,
            }
        },{
            // xtype: 'numbercolumn',
            header: 'Diskon 2',
            dataIndex: 'disk_persen_supp2',           
            width: 100,
            sortable: true,
			align: 'right',
            format: '0,0',
            editor: {
                xtype: 'textfield',
                id: 'ekcponr_disk_persen_supp2',
                readOnly: true,
            }
        },{
            // xtype: 'numbercolumn',
            header: 'Diskon 3',
            dataIndex: 'disk_persen_supp3',           
            width: 100,
            sortable: true,
			align: 'right',
            format: '0,0',
            editor: {
                xtype: 'textfield',
                id: 'ekcponr_disk_persen_supp3',
                readOnly: true,
            }
        },{
            // xtype: 'numbercolumn',
            header: 'Diskon 4',
            dataIndex: 'disk_persen_supp4',           
            width: 100,
            sortable: true,
			align: 'right',
            format: '0,0',
            editor: {
                xtype: 'textfield',
                id: 'ekcponr_disk_persen_supp4',
                readOnly: true,
            }
        },{
            // xtype: 'numbercolumn',
            header: 'Diskon 5',
            dataIndex: 'disk_persen_supp5',           
            width: 100,
            sortable: true,
			align: 'right',
            format: '0,0',
            editor: {
                xtype: 'textfield',
                id: 'ekcponr_disk_persen_supp5',
                readOnly: true,
            }
        },{
            xtype: 'numbercolumn',
            header: 'Total Diskon',
            dataIndex: 'total_diskon',           
            width: 100,
            sortable: true,
			align: 'right',
            format: '0,0',
            editor: {
                xtype: 'numberfield',
                id: 'ekcponr_total_diskon',
                readOnly: true,
            }
        },{
            xtype: 'numbercolumn',
            header: 'Harga Nett',
            dataIndex: 'harga',           
            width: 100,
			align: 'right',
            sortable: true,
            format: '0,0',
            editor: {
                xtype: 'numberfield',
                id: 'ekcponr_harga',
                readOnly: true,
            }
        },{
            // xtype: 'numbercolumn',
            // header: 'Harga Nett (Exc.)',
            // dataIndex: 'dpp_po',           
            // width: 130,
			// align: 'right',
            // sortable: true,
            // format: '0,0',
            // editor: {
                // xtype: 'numberfield',
                // id: 'ekcponr_harga_exc_view',
                // readOnly: true,
            // }
        // },{
            xtype: 'numbercolumn',
            header: 'Harga Nett (Exc.)',
            dataIndex: 'dpp_po',           
            width: 130,
			align: 'right',
            // hidden: true,
            format: '0,0',
            editor: {
                xtype: 'numberfield',
                id: 'ekcponr_harga_exc',
                readOnly: true,
            }
        },{
            xtype: 'numbercolumn',
            header: 'Jumlah',
            dataIndex: 'jumlah',           
            width: 100,
            sortable: true,
			align: 'right',
            format: '0,0',
            editor: {
                xtype: 'numberfield',
                id: 'ekcponr_jumlah',
                readOnly: true,
            }
        },{
			header:'1',
            dataIndex: 'disk_persen_supp1_po',      
            width: 0,
            editor: new Ext.form.TextField({
                readOnly: true,
                id: 'ekcponr_disk_persen_supp1_po'
            })
        },{
			header:'2',
            dataIndex: 'disk_persen_supp2_po',  
            width: 0,
            editor: new Ext.form.TextField({
                readOnly: true,
                id: 'ekcponr_disk_persen_supp2_po'
            })
        },{
			header:'3',
            dataIndex: 'disk_persen_supp3_po',  
            width: 0,
            editor: new Ext.form.TextField({
                readOnly: true,
                id: 'ekcponr_disk_persen_supp3_po'
            })
        },{
			header:'4',
            dataIndex: 'disk_persen_supp4_po',  
            width: 0,
            editor: new Ext.form.TextField({
                readOnly: true,
                id: 'ekcponr_disk_persen_supp4_po'
            })
        },{
			header:'5',
            dataIndex: 'disk_amt_supp1_po',  
            width: 0,
            editor: new Ext.form.TextField({
                readOnly: true,
                id: 'ekcponr_disk_amt_supp1_po'
            })
        },{
			header:'6',
            dataIndex: 'disk_amt_supp2_po',  
            width: 0,
            editor: new Ext.form.TextField({
                readOnly: true,
                id: 'ekcponr_disk_amt_supp2_po'
            })
        },{
			header:'7',
            dataIndex: 'disk_amt_supp3_po',  
            width: 0,
            editor: new Ext.form.TextField({
                readOnly: true,
                id: 'ekcponr_disk_amt_supp3_po'
            })
        },{
			header:'8',
            dataIndex: 'disk_amt_supp4_po',  
            width: 0,
            editor: new Ext.form.TextField({
                readOnly: true,
                id: 'ekcponr_disk_amt_supp4_po'
            })
        },{
			header:'9',
            dataIndex: 'disk_amt_supp5_po',  
            width: 0,
            editor: new Ext.form.TextField({
                readOnly: true,
                id: 'ekcponr_disk_amt_supp5_po'
            })
        },]
    });
	
	gridkonsinyasicreatepononrequest.getSelectionModel().on('selectionchange', function(sm){
        gridkonsinyasicreatepononrequest.removeBtn.setDisabled(sm.getCount() < 1);
    });
	
	var winkonsinyasicreatepononrequestprint = new Ext.Window({
        id: 'id_winkonsinyasicreatepononrequestprint',
		title: 'Print Receive Order Form',
        closeAction: 'hide',
        width: 900,
        height: 450,
        layout: 'fit',
        border: false,
        html:'<iframe style="width:100%;height:100%;" id="konsinyasicreatepononrequestprint" src=""></iframe>'
    });
	
    var konsinyasicreatepononrequest = new Ext.FormPanel({
        id: 'konsinyasicreatepononrequest',
        border: false,
        frame: true,
        autoScroll:true, 
		monitorValid: true,       
        bodyStyle:'padding-right:20px;',
        labelWidth: 130,
        items: [{
                    bodyStyle: {
                        margin: '0px 0px 15px 0px'
                    },                  
                    items: [headerkonsinyasicreatepononrequest]
                },
                gridkonsinyasicreatepononrequest,
                {
                    layout: 'column',
                    border: false,
                    items: [{
                        columnWidth: .6,
                        style:'margin:6px 3px 0 0;',
                        layout: 'form', 
                        labelWidth: 100,                   
                        items: [{
									xtype: 'textfield',
									name: 'pic_penerima',                                                                        
									id: 'kcponr_pic_penerima', 
									fieldLabel: 'PIC Penerima',                                      
									width: 300,
									allowBlank: false
								},{
                                    xtype: 'textarea',
                                    fieldLabel: 'Alamat Penerima',
                                    name: 'alamat_penerima',                                    
                                    id: 'kcponr_alamat_penerima',                                      
                                    width: 300,   
									allowBlank: false	                                   
                                },{
                                    xtype: 'textarea',
                                    fieldLabel: 'Remark',
                                    name: 'remark',                                    
                                    id: 'kcponr_remark',                                      
                                    width: 300,                                      
                                }
                        ]
                    }, {
                        columnWidth: .4,
                        layout: 'form',
                        style:'margin:6px 0 0 0;',
                        border: false,
                        labelWidth: 110,
                        defaults: { labelSeparator: ''},
                        items: [ 
                            {
                                xtype: 'fieldset',
                                autoHeight: true,                               
                                items: [
                                    {
                                        xtype: 'numericfield',
										currencySymbol: '',
                                        fieldLabel: 'Jumlah',
                                        name: 'jumlah',
                                        readOnly: true,                                 
                                        id: 'kcponr_jumlah',                                      
                                        anchor: '90%',      
                                        fieldClass:'readonly-input number',											
                                        value:'0',
                                    },{
                                        xtype: 'compositefield',
                                        fieldLabel: 'Diskon',
										anchor: '-10',
                                        combineErrors: false,
                                        items: [
                                           {
                                               xtype: 'numberfield',
											   currencySymbol:'',
											   format:'0',
                                               name : 'diskon_persen',
                                               id: 'kcponr_diskon_persen',
												fieldClass:'number',
                                               width: 50,
											   value: '0',
											   allowBlank:false,
											   maxValue:100,
											   selectOnFocus:true,
											   listeners: {
													'change': function(){
														var jumlah = Ext.getCmp('kcponr_jumlah').getValue();		
														var diskon_rp = (this.getValue() * jumlah)/100;
														var sub_jumlah = jumlah - diskon_rp;
														var ppn_persen = Ext.getCmp('kcponr_ppn_persen').getValue();
														var ppn_rp = (ppn_persen * sub_jumlah)/100;
														var grand_total = sub_jumlah + ppn_rp;								
												
														diskon_rp = Math.round(diskon_rp);
														jumlah = Math.round(jumlah);
														sub_jumlah = Math.round(sub_jumlah);
														ppn_rp = Math.round(ppn_rp);
														grand_total = Math.round(grand_total);
														
														Ext.getCmp('kcponr_diskon_rp').setValue(diskon_rp);
														Ext.getCmp('kcponr_sub_jumlah').setValue(sub_jumlah);														
														Ext.getCmp('kcponr_ppn_rp').setValue(ppn_rp);
														Ext.getCmp('kcponr_total').setValue(grand_total);
														var sisa_bayar = grand_total - Ext.getCmp('kcponr_dp').getValue();
														Ext.getCmp('kcponr_sisa_bayar').setValue(sisa_bayar);
												}
											   }
											   
                                           },
                                           {
                                               xtype: 'displayfield',
                                               value: '%',
											   width: 20
                                           },
                                           {
                                               xtype: 'numericfield',
                                               name : 'diskon_rp',
                                               id : 'kcponr_diskon_rp',
											   currencySymbol:'',
                                               fieldClass:'readonly-input number',
                                               readOnly: true,  
                                               //anchor: '100%',
											   value: '0'
                                               
                                           }
                                        ]
                                    },{
                                        xtype: 'numericfield',
										currencySymbol: '',
                                        fieldLabel: 'Sub Jumlah',
                                        name: 'sub_jumlah',                                                                        
                                        id: 'kcponr_sub_jumlah',                                       
                                        anchor: '90%',  
                                        readOnly: true, 
                                        cls:'vertical-space',
                                        fieldClass:'readonly-input number',
                                        labelStyle:'margin-top:10px;',      
                                        value:'0',                                                                                      
                                    },{
                                        xtype: 'compositefield',
                                        fieldLabel: 'PPN',
                                        combineErrors: false,
										anchor: '-10',
                                        items: [
                                           {
                                               xtype: 'numberfield',
											   currencySymbol:'',
											   format:'0',
                                               name : 'ppn_persen',
                                               id: 'kcponr_ppn_persen',
											   fieldClass:'readonly-input number',
                                               width: 50,
											   allowBlank:false,
											   selectOnFocus: true,
											   value: '10',
											   maxValue:100,
											   listeners: {
													'change': function(){
														var sub_jumlah = Ext.getCmp('kcponr_sub_jumlah').getValue();	
														var ppn_rp = (this.getValue() * sub_jumlah)/100;
														var grand_total = sub_jumlah + ppn_rp;						
												
														Ext.getCmp('kcponr_ppn_rp').setValue(ppn_rp);
														Ext.getCmp('kcponr_total').setValue(grand_total);
														var sisa_bayar = grand_total - Ext.getCmp('kcponr_dp').getValue();
														Ext.getCmp('kcponr_sisa_bayar').setValue(sisa_bayar);
												}
											   }
											   
                                           },
                                           {
                                               xtype: 'displayfield',
                                               value: '%',
											   width: 20
                                           },
                                           {
                                               xtype: 'numericfield',
                                               name : 'ppn_rp',
                                               id : 'kcponr_ppn_rp',
											   currencySymbol:'',
                                               fieldClass:'readonly-input number',
                                               readOnly: true,  
                                               //anchor: '100%',
											   value:'0', 
                                               
                                           }
                                        ]
                                    },{
                                        xtype: 'numericfield',
										currencySymbol: '',
                                        fieldLabel: '<b>Grand Total</b>',
                                        name: 'total',
                                        cls:'vertical-space',
                                        readOnly: true,                                 
                                        id: 'kcponr_total',                                        
                                        anchor: '90%',  
                                        fieldClass:'readonly-input bold-input number',  
                                        labelStyle:'margin-top:10px;',  
                                        value:'0',                                                                                                                              
                                    },{
                                        xtype: 'numericfield',
										currencySymbol: '',
                                        fieldLabel: 'DP',
                                        name: 'dp',                                                                        
                                        id: 'kcponr_dp',                                       
                                        anchor: '90%', 
										cls:'vertical-space',
										labelStyle:'margin-top:10px;',                                         
                                        fieldClass:'number',                                            
                                        value:'0',  
										listeners: {
											'change': function(){
												var total = Ext.getCmp('kcponr_total').getValue();												
												var sisa_bayar = total - this.getValue();
												
												Ext.getCmp('kcponr_sisa_bayar').setValue(sisa_bayar);
												
											}
										}											                                                                                   
                                    },{
                                        xtype: 'numericfield',
										currencySymbol: '',
                                        fieldLabel: 'Sisa Bayar',
                                        name: 'sisa_bayar',   
										readOnly: true,    
										fieldClass:'readonly-input number',                                                                     
                                        id: 'kcponr_sisa_bayar',                                       
                                        anchor: '90%',                                                                             
                                        value:'0',  										                                                                                   
                                    },	
                                ]
                            }
                        ]
                    }]
                }
                
        ],
        buttons: [{
            text: 'Save',
			formBind: true,
            handler: function(){
                if(Ext.getCmp('kcponr_total').getValue() == 0){
					 Ext.Msg.show({
                            title: 'Error',
                            msg: 'Tidak ada konsinyasi!',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK,
                            
                        });
						return;
				}

		Ext.Msg.show({
		        title: 'Confirm',
		        msg: 'Apakah anda akan menyimpan data ini ??',
		        buttons: Ext.Msg.YESNO,
		        fn: function(btn){
		            if (btn == 'yes') {

                var detailkonsinyasicreatepononrequest = new Array();              
                strkonsinyasicreatepononrequest.each(function(node){
					detailkonsinyasicreatepononrequest.push(node.data)                 
                });				
				
                Ext.getCmp('konsinyasicreatepononrequest').getForm().submit({
                    url: '<?= site_url("konsinyasi_create_po_non_request/update_row") ?>',
                    scope: this,
                    params: {						
                      	detail: Ext.util.JSON.encode(detailkonsinyasicreatepononrequest),
						_dp: Ext.getCmp('kcponr_dp').getValue(),
						_jumlah: Ext.getCmp('kcponr_jumlah').getValue(),
						_diskon_rp: Ext.getCmp('kcponr_diskon_rp').getValue(),
						_ppn_persen: Ext.getCmp('kcponr_ppn_persen').getValue(),
						_ppn_rp: Ext.getCmp('kcponr_ppn_rp').getValue(),
						_total: Ext.getCmp('kcponr_total').getValue(),						
                    },
                    waitMsg: 'Saving Data...',
                    success: function(form, action){
                        var r = Ext.util.JSON.decode(action.response.responseText);
                        Ext.Msg.show({
                            title: 'Success',
                            msg: r.errMsg,
                            modal: true,
                            icon: Ext.Msg.INFO,
                            buttons: Ext.Msg.OK,
							fn: function(btn){
			                    if (btn == 'ok') {
			                        //winkonsinyasicreatepononrequestprint.show();
									//Ext.getDom('konsinyasicreatepononrequestprint').src = r.printUrl;
			                    }
			                }
                        });                     
                        
                        clearkonsinyasicreatepononrequest();                       
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
                }
            });
                         
            }
        },{
            text: 'Reset',
            handler: function(){
                clearkonsinyasicreatepononrequest();
            }
        }]
    });
    
    konsinyasicreatepononrequest.on('afterrender', function(){
        this.getForm().load({
            url: '<?= site_url("konsinyasi_create_po_non_request/get_form") ?>',
            success: function(form, action){
                var r = Ext.util.JSON.decode(action.response.responseText);
                if(r.data.user_peruntukan === "0"){
                    Ext.getCmp('kcponr_peruntukan_supermarket').setValue(true);
                    Ext.getCmp('kcponr_peruntukan_supermarket').show();
                    Ext.getCmp('kcponr_peruntukan_distribusi').hide();
                }else if(r.data.user_peruntukan === "1"){
                    Ext.getCmp('pcpo_peruntukan_distribusi').setValue(true);
                    Ext.getCmp('kcponr_peruntukan_supermarket').hide();
                    Ext.getCmp('kcponr_peruntukan_distribusi').show();
                }else{
                    Ext.getCmp('kcponr_peruntukan_supermarket').setValue(true);
                    Ext.getCmp('kcponr_peruntukan_supermarket').show();
                    Ext.getCmp('kcponr_peruntukan_distribusi').show();
                }
            },
            failure: function(form, action){
                var de = Ext.util.JSON.decode(action.response.responseText);
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
        });
    });
    
    function clearkonsinyasicreatepononrequest(){
        Ext.getCmp('konsinyasicreatepononrequest').getForm().reset();
        Ext.getCmp('konsinyasicreatepononrequest').getForm().load({
            url: '<?= site_url("konsinyasi_create_po_non_request/get_form") ?>',
            success: function(form, action){
                var r = Ext.util.JSON.decode(action.response.responseText);
                if(r.data.user_peruntukan === "0"){
                    Ext.getCmp('kcponr_peruntukan_supermarket').setValue(true);
                    Ext.getCmp('kcponr_peruntukan_supermarket').show();
                    Ext.getCmp('kcponr_peruntukan_distribusi').hide();
                }else if(r.data.user_peruntukan === "1"){
                    Ext.getCmp('pcpo_peruntukan_distribusi').setValue(true);
                    Ext.getCmp('kcponr_peruntukan_supermarket').hide();
                    Ext.getCmp('kcponr_peruntukan_distribusi').show();
                }else{
                    Ext.getCmp('kcponr_peruntukan_supermarket').setValue(true);
                    Ext.getCmp('kcponr_peruntukan_supermarket').show();
                    Ext.getCmp('kcponr_peruntukan_distribusi').show();
                }
            },
            failure: function(form, action){
                var de = Ext.util.JSON.decode(action.response.responseText);
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
        });
        strkonsinyasicreatepononrequest.removeAll();
    }
</script>
