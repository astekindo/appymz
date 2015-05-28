<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">

	var strcbppmember = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_member', 'nmmember'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("penjualan_proyek/get_all_member") ?>',
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
	
	var cbppmember = new Ext.form.ComboBox({
        fieldLabel: 'Member',
        id: 'id_cbppmember',
        store: strcbppmember,
        valueField: 'kd_member',
        displayField: 'nmmember',
        typeAhead: true,
        triggerAction: 'all',        
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_member',
        emptyText: 'Pilih Member'
    });
	
	var headerpenjualanproyek = {
        layout: 'column',
        border: false,
        items: [{
            columnWidth: .6,
            layout: 'form',
            border: false,
            labelWidth: 100,
			defaults: { labelSeparator: ''},
            items: [{
				xtype: 'fieldset',
				autoHeight: true,
				items: [
					{
						layout: 'column',
						items:[
							{
								columnWidth: .5,
					            layout: 'form',
					            border: false,
					            labelWidth: 70,
								defaults: { labelSeparator: ''},
								items:[									
									{
										xtype: 'textfield',
										fieldLabel: 'SO No.',
										readOnly: true,
										fieldClass:'readonly-input',
										name: 'no_so',										
										id: 'pp_id_no_so',										
										anchor: '90%',										
									},cbppmember
								]
							},
							{
								columnWidth: .5,
					            layout: 'form',
					            border: false,
					            labelWidth: 70,
								defaults: { labelSeparator: ''},
								items:[
									{
										xtype: 'textfield',
										fieldLabel: 'Tanggal',
										name: 'tgl_so',										
										readOnly: true,
										id: 'pp_tgl_so',										
										anchor: '90%',
										fieldClass:'readonly-input',										
									}
								]
							},
							
						]
					}
				]
			}]
        }, {
            columnWidth: .4,
            layout: 'form',
            border: false,
			align:'right',
            labelWidth: 50,
			defaults: { labelSeparator: ''},
			extraCls : 'text-align:right;border:1px solid;',
            items: [ {
                xtype: 'displayfield',				
				name: 'pp_display_grand_total',					
				id: 'pp_display_grand_total',
				fieldLabel: 'Rp.',
				labelStyle:'font-size:35px;text-align:left;padding-left:50px;',
				style: 'font-size:35px;text-align:right;padding-right:10px;margin-top:10px;',
            }]
        }]
    }
	
	var strpenjualanproyek = new Ext.data.Store({
		autoSave:false,
		reader: new Ext.data.JsonReader({
            fields: [
				{name: 'qty', type: 'int'},
			    {name: 'kd_produk', type: 'int'},
			    {name: 'nama_produk', type: 'text'},
				{name: 'satuan', type: 'text'},
				{name: 'hrg_jual', type: 'int'},
				{name: 'rp_diskon', type: 'int'},
				{name: 'rp_jumlah', type: 'int'},
				{name: 'rp_total', type: 'int'},
				{name: 'qty_bonus', type: 'int'},
				{name: 'kd_produk_bonus', type: 'text'},
				{name: 'nama_produk_bonus', type: 'text'},
				{name: 'is_kirim', type: 'bool'},
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
	
    strpenjualanproyek.on('remove',  function(){
		var total_diskon = 0;
		var total_tagihan = 0;
		var grand_total = 0;
		var total_pembelian = 0;

		var bi_kirim = Ext.getCmp('pp_rp_ongkos_kirim').getValue();
		var bi_pasang = Ext.getCmp('pp_rp_ongkos_pasang').getValue();
		var bank_charge = Ext.getCmp('pp_rp_bank_charge').getValue();
		
		strpenjualanproyek.each(function(node){			
			total_pembelian += (node.data.qty * node.data.hrg_jual);
			total_diskon += (node.data.qty * node.data.rp_diskon);			
         });
		 
		if(total_pembelian == 0) Ext.getCmp('id_cbppmember').setReadOnly(false);
		
		total_tagihan = (total_pembelian - total_diskon) + bi_kirim + bi_pasang;
		grand_total = total_tagihan + bank_charge;
		
		
		Ext.getCmp('pp_rp_total').setValue(total_pembelian);
		Ext.getCmp('pp_rp_diskon').setValue(total_diskon);
		Ext.getCmp('pp_rp_total_tagihan').setValue(total_tagihan);
		Ext.getCmp('pp_rp_total_bayar').setValue(grand_total);
		Ext.getCmp('pp_display_grand_total').setValue(Ext.util.Format.number(grand_total, '0,0'));
	});
    strpenjualanproyek.on('update', function(){
		Ext.getCmp('id_cbppmember').setReadOnly(true);
		var total_diskon = 0;
		var total_tagihan = 0;
		var grand_total = 0;
		var total_pembelian = 0;

		var bi_kirim = Ext.getCmp('pp_rp_ongkos_kirim').getValue();
		var bi_pasang = Ext.getCmp('pp_rp_ongkos_pasang').getValue();
		var bank_charge = Ext.getCmp('pp_rp_bank_charge').getValue();
		
		strpenjualanproyek.each(function(node){			
			total_pembelian += (node.data.qty * node.data.hrg_jual);
			total_diskon += (node.data.qty * node.data.rp_diskon);			
         });
		
		total_tagihan = (total_pembelian - total_diskon) + bi_kirim + bi_pasang;
		grand_total = total_tagihan + bank_charge;
		
		
		Ext.getCmp('pp_rp_total').setValue(total_pembelian);
		Ext.getCmp('pp_rp_diskon').setValue(total_diskon);
		Ext.getCmp('pp_rp_total_tagihan').setValue(total_tagihan);
		Ext.getCmp('pp_rp_total_bayar').setValue(grand_total);
		Ext.getCmp('pp_display_grand_total').setValue(Ext.util.Format.number(grand_total,'0,0'));
	});

    var editorpenjualanproyek = new Ext.ux.grid.RowEditor({
        saveText: 'Update'
    });

	var strcbkdprodukpp = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_produk', 'nama_produk'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("penjualan_proyek/get_all_produk/kode") ?>',
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
    
	var strcbnmprodukpp = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_produk', 'nama_produk'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("penjualan_proyek/get_all_produk/nama") ?>',
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
    
    var gridpenjualanproyek = new Ext.grid.GridPanel({
        store: strpenjualanproyek,
		stripeRows: true,
		height: 200,
		frame: true,		
		border:true,
        plugins: [editorpenjualanproyek],
        tbar: [{
            icon: BASE_ICONS + 'add.png',
            text: 'Add',
            handler: function(){
				var rowpenjualanproyek = new gridpenjualanproyek.store.recordType({
		            kd_produk : '',
		            qty: ''
		        });                
                editorpenjualanproyek.stopEditing();
                strpenjualanproyek.insert(0, rowpenjualanproyek);
                gridpenjualanproyek.getView().refresh();
                gridpenjualanproyek.getSelectionModel().selectRow(0);
                editorpenjualanproyek.startEditing(0);
            }
        },{
            ref: '../removeBtn',
            icon: BASE_ICONS + 'delete.gif',
            text: 'Remove',
            disabled: true,
            handler: function(){
                editorpenjualanproyek.stopEditing();
                var s = gridpenjualanproyek.getSelectionModel().getSelections();
                for(var i = 0, r; r = s[i]; i++){
                    strpenjualanproyek.remove(r);
                }
            }
        }],
        columns: [{
            xtype: 'numbercolumn',
            header: 'Qty',
            dataIndex: 'qty',			
            width: 40,
            sortable: true,
			format: '0,0',
            editor: {
                xtype: 'numberfield',
				id: 'pp_qty',
				selectOnFocus: true,
                allowBlank: false,
				listeners:{
					'change': function(){
						var rp_total = this.getValue() * Ext.getCmp('pp_so_jumlah').getValue();
						Ext.getCmp('pp_so_total').setValue(rp_total);
					}
				}
            }
        },{
            xtype: 'numbercolumn',
            header: 'Kode',
            dataIndex: 'kd_produk',
            width: 120,
            sortable: true,
			format: '0',
			editor: new Ext.form.ComboBox({
				id: 'pp_kd_produk',
		        store: strcbkdprodukpp,
		        valueField: 'kd_produk',
		        displayField: 'kd_produk',
		        typeAhead: true,
		        triggerAction: 'all',
		        allowBlank: false,				
		        //editable: false,
		        hiddenName: 'kd_produk',
		        emptyText: 'Pilih Kode Barang',
				listeners: {					
					'select': function(){
						Ext.Ajax.request({
                            url: '<?= site_url("penjualan_proyek/get_row_produk") ?>',
                            method: 'POST',
                            params: {
                                id: this.getValue(),
                                qty: Ext.getCmp('pp_qty').getValue(),
								member: Ext.getCmp('id_cbppmember').getValue(),
								search_by: 'kode'
                            },
							callback:function(opt,success,responseObj){
								var de = Ext.util.JSON.decode(responseObj.responseText);
								if(de.success==true){
									Ext.getCmp('pp_nama_produk').setValue(de.data.nama_produk);
									Ext.getCmp('pp_satuan').setValue(de.data.nm_satuan);
									Ext.getCmp('pp_hrg_jual').setValue(de.data.hrg_jual);	
									Ext.getCmp('pp_so_diskon').setValue(de.data.rp_diskon);	
									Ext.getCmp('pp_so_jumlah').setValue(de.data.rp_jumlah);	
									Ext.getCmp('pp_so_total').setValue(de.data.rp_total);	
									Ext.getCmp('pp_qty_bonus').setValue(de.data.qty_bonus);
									Ext.getCmp('pp_kd_produk_bonus').setValue(de.data.kd_produk_bonus);
									Ext.getCmp('pp_nama_produk_bonus').setValue(de.data.nama_produk_bonus);															
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
		    })
        },{
            header: 'Nama Barang',
            dataIndex: 'nama_produk',
            width: 250,
            editor: new Ext.form.ComboBox({
				id: 'pp_nama_produk',
		        store: strcbnmprodukpp,
		        valueField: 'nama_produk',
		        displayField: 'nama_produk',
		        typeAhead: true,
		        triggerAction: 'all',
		        allowBlank: false,
		        //editable: false,
		        hiddenName: 'nama_produk',
		        emptyText: 'Pilih Barang',
				listeners: {
					'select': function(){										
						Ext.Ajax.request({
                            url: '<?= site_url("penjualan_proyek/get_row_produk") ?>',
                            method: 'POST',
                            params: {
                                id: this.getValue(),
								qty: Ext.getCmp('pp_qty').getValue(),
								member: Ext.getCmp('id_cbppmember').getValue(),
								search_by: 'nama'	
                            },
							callback:function(opt,success,responseObj){
								var de = Ext.util.JSON.decode(responseObj.responseText);
								if(de.success==true){
									Ext.getCmp('pp_kd_produk').setValue(de.data.kd_produk);
									Ext.getCmp('pp_satuan').setValue(de.data.nm_satuan);
									Ext.getCmp('pp_hrg_jual').setValue(de.data.hrg_jual);	
									Ext.getCmp('pp_so_diskon').setValue(de.data.rp_diskon);	
									Ext.getCmp('pp_so_jumlah').setValue(de.data.rp_jumlah);	
									Ext.getCmp('pp_so_total').setValue(de.data.rp_total);	
									Ext.getCmp('pp_qty_bonus').setValue(de.data.qty_bonus);
									Ext.getCmp('pp_kd_produk_bonus').setValue(de.data.kd_produk_bonus);
									Ext.getCmp('pp_nama_produk_bonus').setValue(de.data.nama_produk_bonus);								
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
		    })
        },{
            header: 'Satuan',
            dataIndex: 'satuan',
            width: 50,
            editor: new Ext.form.TextField({               
				readOnly: true,
				id: 'pp_satuan'
            })
        },{
			xtype: 'numbercolumn',
            header: 'Hrg Jual (Rp)',
            dataIndex: 'hrg_jual',
			align: 'right',
            width: 90,
			format: '0,0',
            editor: new Ext.form.TextField({                
				readOnly: true,
				id: 'pp_hrg_jual'
            })
        },{
			xtype: 'numbercolumn',
            header: 'Diskon (Rp)',
            dataIndex: 'rp_diskon',
			align: 'right',
            width: 90,
			format: '0,0',
			editor: {
                xtype: 'numberfield',
				id: 'pp_so_diskon',
				selectOnFocus: true,
                allowBlank: false,
				listeners:{
					'change': function(){
						var rp_jumlah = Ext.getCmp('pp_hrg_jual').getValue() - this.getValue();
						Ext.getCmp('pp_so_jumlah').setValue(rp_jumlah);
						var rp_total = Ext.getCmp('pp_qty').getValue() * rp_jumlah;
						Ext.getCmp('pp_so_total').setValue(rp_total);
					}
				}
            }           
        },{
			xtype: 'numbercolumn',
            header: 'Jumlah (Rp)',
            dataIndex: 'rp_jumlah',
			align: 'right',
            width: 100,
			format: '0,0',
            editor: new Ext.form.TextField({                
				readOnly: true,
				id: 'pp_so_jumlah'
            })
        },{
			xtype: 'numbercolumn',
            header: 'Total (Rp)',
            dataIndex: 'rp_total',
            width: 100,
			align: 'right',
			format: '0,0',
            editor: new Ext.form.TextField({                
				readOnly: true,
				id: 'pp_so_total'
            })
        },{
            xtype: 'booleancolumn',
            header: 'Dikirim',
            dataIndex: 'is_kirim',
            align: 'center',
            width: 50,
            trueText: 'Ya',
            falseText: 'Tidak',
            editor: {
                xtype: 'checkbox'
            }
        },{
            xtype: 'numbercolumn',
            header: 'Qty Bonus',
            dataIndex: 'qty_bonus',			
            width: 70,            
			format: '0,0',
            editor: {
                xtype: 'numberfield',
				id: 'pp_qty_bonus',                
            }
        },{
            header: 'Kode Barang Bonus',
            dataIndex: 'kd_produk_bonus',
            width: 110,
            editor: new Ext.form.TextField({                
				readOnly: true,
				id: 'pp_kd_produk_bonus'
            })
        },{
            header: 'Nama Barang Bonus',
            dataIndex: 'nama_produk_bonus',
            width: 250,
            editor: new Ext.form.TextField({                
				readOnly: true,
				id: 'pp_nama_produk_bonus'
            })
        },
		]
    });
	
	gridpenjualanproyek.getSelectionModel().on('selectionchange', function(sm){
        gridpenjualanproyek.removeBtn.setDisabled(sm.getCount() < 1);
    });
	
	
	var strppjenispembayaran = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
				{name: 'is_pilih', type: 'bool'},
				{name: 'kd_jenis_bayar', type: 'text'},
				{name: 'nm_pembayaran', type: 'text'},
				{name: 'charge', type: 'int'},
				{name: 'rp_jumlah', type: 'int'},
				{name: 'rp_charge', type: 'int'},
				{name: 'rp_total', type: 'int'},
				{name: 'no_kartu', type: 'text'},
				{name: 'tgl_jth_tempo', type: 'text'},				
			],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("penjualan_proyek/get_all_jenis_pembayaran") ?>',
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
	
	strppjenispembayaran.on('update', function(){
		var total_jumlah = 0;
		var total_charge = 0;
		var total_bayar = 0;
		var total_tagihan = Ext.getCmp('pp_rp_total_tagihan').getValue();
		
		
		strppjenispembayaran.each(function(node){			
			if (node.data.is_pilih) {
				total_jumlah += (node.data.rp_jumlah);
				total_charge += (node.data.rp_charge);
				total_bayar += (node.data.rp_total);
			}		
         });
		/*
		if(total_jumlah == total_tagihan){	
		}else{	
		}*/
		Ext.getCmp('pp_rp_bank_charge').setValue(total_charge);
		var grand_total = total_tagihan + total_charge;
		
		Ext.getCmp('pp_rp_total_bayar').setValue(grand_total);
		Ext.getCmp('pp_display_grand_total').setValue(Ext.util.Format.number(grand_total, '0,0'));
		Ext.getCmp('pp_total_bayar').setValue(total_bayar);
		var kembali = grand_total - total_bayar;
		Ext.getCmp('pp_kembali_sisa').setValue(kembali);
	});
	
	var editorppjenispembayaran = new Ext.ux.grid.RowEditor({
        saveText: 'Update'
    });
	
	var gridppjenispembayaran = new Ext.grid.GridPanel({
        id: 'idgridppjenispembayaran',
		store: strppjenispembayaran,
		stripeRows: true,
		height: 150,		
		border:true,
		frame:true,
		plugins: [editorppjenispembayaran],
        columns: [{
            xtype: 'booleancolumn',
            header: 'Pilih',
            dataIndex: 'is_pilih',
            align: 'center',
            width: 40,
            trueText: 'Ya',
            falseText: 'Tidak',
            editor: {
                xtype: 'checkbox'
            }
        },{            
            header: 'Kode',
            dataIndex: 'kd_jenis_bayar',
            width: 50,            		
			editor: new Ext.form.TextField({               
				readOnly: true,
				id: 'pp_kd_jenis_bayar'
            })
        },{            
            header: 'Jenis Pembayaran',
            dataIndex: 'nm_pembayaran',
            width: 160,
            sortable: true,			
			editor: new Ext.form.TextField({               
				readOnly: true,
				id: 'pp_nama_pembayaran'
            })
        },{
			xtype: 'numbercolumn',
            header: 'Charge (%)',
            dataIndex: 'charge',
            width: 70,
			format: '0,0',
			align:'center',
            editor: {
                xtype: 'numberfield',
				id: 'eppjp_charge',
                readOnly: true,				
            }
        },{
            xtype: 'numbercolumn',
            header: 'Jumlah',
            dataIndex: 'rp_jumlah',			
            width: 100,
			format: '0,0',
            editor: {
                xtype: 'numberfield',
				id: 'eppjp_rp_jumlah',
                allowBlank: false,
				selectOnFocus: true,
				listeners:{
					'change': function(){
						var total_tagihan = Ext.getCmp('pp_rp_total_tagihan').getValue();
						
						if(total_tagihan == 0){
							Ext.Msg.show({
		                        title: 'Error',
		                        msg: 'Total tagihan masih kosong',
		                        modal: true,
		                        icon: Ext.Msg.ERROR,
		                        buttons: Ext.Msg.OK
		                    });
						}else{
							var rp_charge = (parseInt(Ext.getCmp('eppjp_charge').getValue()) * parseInt(this.getValue()))/100;
							Ext.getCmp('eppjp_rp_charge').setValue(rp_charge);
							var rp_total = parseInt(this.getValue()) + rp_charge;
							Ext.getCmp('eppjp_rp_total').setValue(rp_total);
						}
						
					}
				}
            }
        },{
            xtype: 'numbercolumn',
            header: 'Charge (Rp)',
            dataIndex: 'rp_charge',			
            width: 100,
			format: '0,0',
            editor: {
                xtype: 'numberfield',
				id: 'eppjp_rp_charge',
                readOnly: true
            }
        },{
            xtype: 'numbercolumn',
            header: 'Total',
            dataIndex: 'rp_total',			
            width: 100,
			format: '0,0',
            editor: {
                xtype: 'numberfield',
				id: 'eppjp_rp_total',
                readOnly: true
            }
        },{
            header: 'No Kartu',
            dataIndex: 'no_kartu',
            width: 100,
            editor: new Ext.form.TextField({
				id: 'eppjp_no_kartu'
            })
        },{
            header: 'Tgl Jatuh Tempo',
            dataIndex: 'tgl_jth_tempo',
            width: 100,
            editor: new Ext.form.DateField({
				id: 'eppjp_tgl_jth_tempo',
				format: 'd/m/Y',
            })
        },
		]
    });
	
	gridppjenispembayaran.on('afterrender', function(){
		strppjenispembayaran.load();
	});
	
	var penjualanproyek = new Ext.FormPanel({
	 	id: 'penjualanproyek',
		border: false,
        frame: true,
		autoScroll:true,		
		bodyStyle:'padding-right:20px;',
        labelWidth: 130,
        items: [headerpenjualanproyek,  
				gridpenjualanproyek,
				{
					layout: 'column',
					border: false,
			        items: [{
			            columnWidth: .7,
						style:'margin:6px 3px 0 0;',			           
			            items: [
							gridppjenispembayaran,
							{
								xtype: 'fieldset',
								autoWidth: true,
								title: 'Dikirim ke?',
								collapsible: true,								
								items: [
									{
						                xtype: 'textfield',
						                fieldLabel: 'Dikirim ke',
						                name: 'kirim_so',						               
						                id: 'pp_kirim_so',						                
						                anchor: '50%',										
						            }, {
										xtype: 'textfield',
										fieldLabel: 'Alamat',
										name: 'kirim_alamat_so',										
										id: 'pp_kirim_alamat_so',										
										anchor: '90%',
									},{
						                xtype: 'textfield',
						                fieldLabel: 'Password',
						                name: 'kirim_passwd_so',						               
						                id: 'pp_kirim_passwd_so',						                
						                anchor: '50%',										
						            }
								]
							}
						]
			        }, {
			            columnWidth: .3,
			            layout: 'form',
			            border: false,
			            labelWidth: 110,
						defaults: { labelSeparator: ''},
			            items: [ 
							{
								xtype: 'fieldset',
								autoHeight: true,
								title: 'Amount Charge',
								items: [
									{
										xtype: 'numericfield',
										currencySymbol: '',
										fieldLabel: 'Total Pembelian',
										name: 'rp_total',										
										readOnly: true,									
										id: 'pp_rp_total',										
										anchor: '95%',		
										fieldClass:'readonly-input number',	
										value:'0',														
									},{
										xtype: 'numericfield',
										currencySymbol: '',
										fieldLabel: 'Diskon',
										name: 'rp_diskon',
										readOnly: true,									
										id: 'pp_rp_diskon',										
										anchor: '95%',	
										fieldClass:'readonly-input number',	
										value:'0',																
									},{
										xtype: 'numericfield',
										currencySymbol: '',
										fieldLabel: 'Biaya Kirim',
										name: 'rp_ongkos_kirim',																
										id: 'pp_rp_ongkos_kirim',								
										anchor: '95%',	
										fieldClass:'number',
										selectOnFocus: true,		
										value:'0',	
										listeners:{
											change: function(){
												var total_pembelian = Ext.getCmp('pp_rp_total').getValue();
												var total_diskon = Ext.getCmp('pp_rp_diskon').getValue();
												var bi_kirim = this.getValue();
												var bi_pasang = Ext.getCmp('pp_rp_ongkos_pasang').getValue();
												var bank_charge = Ext.getCmp('pp_rp_bank_charge').getValue();

												var total_tagihan = (total_pembelian - total_diskon) + bi_kirim + bi_pasang;
												var grand_total = total_tagihan + bank_charge;

												Ext.getCmp('pp_rp_total_tagihan').setValue(total_tagihan);
												Ext.getCmp('pp_rp_total_bayar').setValue(grand_total);
												Ext.getCmp('pp_display_grand_total').setValue(Ext.util.Format.number(grand_total, '0,0'));
											}
										}																																				
									},{
										xtype: 'numericfield',
										currencySymbol: '',
										fieldLabel: 'Biaya Pasang',
										name: 'rp_ongkos_pasang',																		
										id: 'pp_rp_ongkos_pasang',										
										anchor: '95%',	
										fieldClass:'number',
										value:'0',
										selectOnFocus: true,		
										listeners:{
											change: function(){
												var total_pembelian = Ext.getCmp('pp_rp_total').getValue();
												var total_diskon = Ext.getCmp('pp_rp_diskon').getValue();
												var bi_kirim = Ext.getCmp('pp_rp_ongkos_kirim').getValue();
												var bi_pasang = this.getValue();
												var bank_charge = Ext.getCmp('pp_rp_bank_charge').getValue();

												var total_tagihan = (total_pembelian - total_diskon) + bi_kirim + bi_pasang;
												var grand_total = total_tagihan + bank_charge;

												Ext.getCmp('pp_rp_total_tagihan').setValue(total_tagihan);
												Ext.getCmp('pp_rp_total_bayar').setValue(grand_total);
												Ext.getCmp('pp_display_grand_total').setValue(Ext.util.Format.number(grand_total, '0,0'));
											}
										}														
									},{
										xtype: 'numericfield',
										currencySymbol: '',
										fieldLabel: 'Total Tagihan',
										name: 'rp_total_tagihan',
										readOnly: true,									
										id: 'pp_rp_total_tagihan',										
										anchor: '95%',	
										fieldClass:'readonly-input number',
										value:'0',																		
									},{
										xtype: 'numericfield',
										currencySymbol: '',
										fieldLabel: 'Bank Charge',
										name: 'rp_bank_charge',
										readOnly: true,									
										id: 'pp_rp_bank_charge',										
										anchor: '95%',	
										fieldClass:'readonly-input number',	
										value:'0',																	
									},{
										xtype: 'numericfield',
										currencySymbol: '',
										fieldLabel: '<b>Grand Total</b>',
										name: 'rp_total_bayar',
										readOnly: true,									
										id: 'pp_rp_total_bayar',										
										anchor: '95%',	
										fieldClass:'readonly-input bold-input number',	
										value:'0',																																
									},{
										xtype: 'numericfield',
										currencySymbol: '',
										fieldLabel: 'Total Bayar',
										name: 'total_bayar',																		
										id: 'pp_total_bayar',										
										anchor: '95%',	
										cls:'vertical-space',
										fieldClass:'readonly-input number',
										labelStyle:'margin-top:10px;',		
										value:'0',
										readOnly: true,
										selectOnFocus: true,	
										listeners:{
											change: function(){
												var grand_total = Ext.getCmp('pp_rp_total_bayar').getValue();
												var kembalian = grand_total - this.getValue();
												Ext.getCmp('pp_kembali_sisa').setValue(kembalian);
												Ext.getCmp('pp_display_grand_total').setValue(Ext.util.Format.number(kembalian, '0,0'));
											}
										}											
									},{
										xtype: 'numericfield',
										currencySymbol: '',
										fieldLabel: 'Kembali/Sisa',
										name: 'kembali_sisa',
										readOnly: true,									
										id: 'pp_kembali_sisa',										
										anchor: '95%',		
										fieldClass:'readonly-input number',
										value:'0',															
									},{
										xtype: 'numberfield',
										fieldLabel: 'Jumlah Voucher',
										name: 'qty_voucher',
										readOnly: true,									
										id: 'pp_qty_voucher',										
										anchor: '95%',		
										fieldClass:'readonly-input number',
										cls:'vertical-space',
										labelStyle:'margin-top:10px;',	
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
            handler: function(){
				if(Ext.getCmp('pp_kembali_sisa').getValue() > 0){
					Ext.Msg.show({
			                title: 'Error',
			                msg: 'Total bayar masih kurang dari grand total',
			                modal: true,
			                icon: Ext.Msg.ERROR,
			                buttons: Ext.Msg.OK,			                
			            });
						
					return;
				}
				var detailpenjualanproyek = new Array();				
                strpenjualanproyek.each(function(node){
                	detailpenjualanproyek.push(node.data)
                });
				var jenisbayarpenjualanproyek = new Array();
				strppjenispembayaran.each(function(node){
					jenisbayarpenjualanproyek.push(node.data);
				});
				Ext.getCmp('penjualanproyek').getForm().submit({
	                url: '<?= site_url("penjualan_proyek/update_row") ?>',
	                scope: this,
	                params: {
	                  	detail: Ext.util.JSON.encode(detailpenjualanproyek),
					  	jenis_bayar: Ext.util.JSON.encode(jenisbayarpenjualanproyek),
					  	_rp_total: Ext.getCmp('pp_rp_total').getValue(),
						_rp_diskon:Ext.getCmp('pp_rp_diskon').getValue(),
						_rp_ongkos_kirim:Ext.getCmp('pp_rp_ongkos_kirim').getValue(),
						_rp_ongkos_pasang:Ext.getCmp('pp_rp_ongkos_pasang').getValue(),						
						_rp_bank_charge:Ext.getCmp('pp_rp_bank_charge').getValue(),
						_rp_total_bayar:Ext.getCmp('pp_rp_total_bayar').getValue()
	                },
	                waitMsg: 'Saving Data...',
					success: function(form, action){
			            Ext.Msg.show({
			                title: 'Success',
			                msg: 'Form submitted successfully',
			                modal: true,
			                icon: Ext.Msg.INFO,
			                buttons: Ext.Msg.OK
			            });			            
			            
			            clearpenjualanproyek();						
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
        },{
			text: 'Reset',
			handler: function(){
				clearpenjualanproyek();
			}
		}]
    });
	
	penjualanproyek.on('afterrender', function(){
		this.getForm().load({
            url: '<?= site_url("penjualan_proyek/get_form") ?>',
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
	
	function clearpenjualanproyek(){
		Ext.getCmp('id_cbppmember').setReadOnly(false);
		Ext.getCmp('penjualanproyek').getForm().reset();
		Ext.getCmp('penjualanproyek').getForm().load({
            url: '<?= site_url("penjualan_proyek/get_form") ?>',
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
		strpenjualanproyek.removeAll();
		strppjenispembayaran.reload();
	}
</script>