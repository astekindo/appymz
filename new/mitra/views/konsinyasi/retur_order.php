<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">
   var strcbkretursuplier = new Ext.data.ArrayStore({
        fields: ['kd_supplier'],
        data : []
    });
	
	var strgridkretursuplier = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_supplier', 'nama_supplier','top','pic'],
            root: 'data',
            totalkreturperty: 'record'
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
	
	var searchgridkretursuplier = new Ext.app.SearchField({
        store: strgridkretursuplier,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE			
        },
        width: 350,
		id: 'id_searchgridkretursuplier'
    });
	
	
	var gridkretursuplier = new Ext.grid.GridPanel({
        store: strgridkretursuplier,
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
            header: 'Waktu TOP',
            dataIndex: 'top',
            width: 80,
			sortable: true,         
        }],
		tbar: new Ext.Toolbar({
	        items: [searchgridkretursuplier]
	    }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridkretursuplier,
            displayInfo: true
        }),
		listeners: {
			'rowdblclick': function(){			
				var sm = this.getSelectionModel();
				var sel = sm.getSelections();
				if (sel.length > 0) {				
                    Ext.getCmp('id_cbkretursuplier').setValue(sel[0].get('kd_supplier'));
					Ext.getCmp('kretur_nama_supplier').setValue(sel[0].get('nama_supplier'));
					menukretursuplier.hide();
				}
			}
		}
    });
	
	var menukretursuplier = new Ext.menu.Menu();
    menukretursuplier.add(new Ext.Panel({
        title: 'Pilih Supplier',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridkretursuplier],
        buttons: [{
            text: 'Close',
            handler: function(){
                menukretursuplier.hide();
            }
        }]
    }));
    
    Ext.ux.TwinCombokreturSuplier = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
			//load store grid
            strgridkretursuplier.load();
            menukretursuplier.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
	
	menukretursuplier.on('hide', function(){
		var sf = Ext.getCmp('id_searchgridkretursuplier').getValue();
		if( sf != ''){
			Ext.getCmp('id_searchgridkretursuplier').setValue('');
			searchgridkretursuplier.onTrigger2Click();
		}
	});
	
	var cbkretursuplier = new Ext.ux.TwinCombokreturSuplier({
        fieldLabel: 'Supplier <span class="asterix">*</span>',
        id: 'id_cbkretursuplier',
        store: strcbkretursuplier,
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
	    	
    var headerkonsinyasireturorder = {
        layout: 'column',
        border: false,
        items: [{
            columnWidth: .3,
            layout: 'form',
            border: false,
            labelWidth: 100,
            defaults: { labelSeparator: ''},
            items: [{
					xtype: 'textfield',
					fieldLabel: 'No RB',
					name: 'no_rb',
					fieldClass:'readonly-input',
					readOnly:true,
					allowBlank: false,
					id: 'kretur_no_rb',                
					anchor: '90%',
					value: ''
				},{
				xtype: 'datefield',
                fieldLabel: 'Tanggal <span class="asterix">*</span>',
                name: 'tgl_retur',				
                allowBlank:false,   
				format:'d-m-Y',  
				editable:false,           
                id: 'kretur_tgl_retur',                
                anchor: '90%',
                value: ''
			},
			]
        }, {
            columnWidth: .5,
            layout: 'form',
            border: false,
            labelWidth: 110,
            defaults: { labelSeparator: ''},
            items: [cbkretursuplier,{
                xtype: 'textfield',
                fieldLabel: 'Nama Supplier',
                name: 'nama_supplier',
				fieldClass:'readonly-input',
                readOnly:true,                
                id: 'kretur_nama_supplier',                
                anchor: '90%',
                value: ''
            },{
				xtype: 'hidden',
				name: 'alamat',
				id: 'kretur_alamat_supplier'
			},]
        }
		]
    }
    
     var strkonsinyasireturorder = new Ext.data.Store({
        autoSave:false,
        reader: new Ext.data.JsonReader({
            fields: [
                {name: 'kd_produk', allowBlank: false, type: 'text'},
				{name: 'nama_produk', allowBlank: false, type: 'text'},                
                {name: 'nm_satuan', allowBlank: false, type: 'text'},
                {name: 'qty', allowBlank: false, type: 'int'},				
				{name: 'disk_persen_supp1_po', allowBlank: false, type: 'int'},
                {name: 'disk_persen_supp2_po', allowBlank: false, type: 'int'},
                {name: 'disk_persen_supp3_po', allowBlank: false, type: 'int'},
                {name: 'disk_persen_supp4_po', allowBlank: false, type: 'int'},
				{name: 'disk_amt_supp1_po', allowBlank: false, type: 'int'},
                {name: 'disk_amt_supp2_po', allowBlank: false, type: 'int'},
                {name: 'disk_amt_supp3_po', allowBlank: false, type: 'int'},
                {name: 'disk_amt_supp4_po', allowBlank: false, type: 'int'},	
				{name: 'disk_amt_supp5_po', allowBlank: false, type: 'int'},			
                {name: 'disk_persen_supp1', allowBlank: false, type: 'int'},
                {name: 'disk_persen_supp2', allowBlank: false, type: 'int'},
                {name: 'disk_persen_supp3', allowBlank: false, type: 'int'},
                {name: 'disk_persen_supp4', allowBlank: false, type: 'int'},
				{name: 'disk_persen_supp5', allowBlank: false, type: 'int'},
                {name: 'hrg_supplier', allowBlank: false, type: 'int'},
				{name: 'harga', allowBlank: false, type: 'int'},
				{name: 'jumlah', allowBlank: false, type: 'int'},				
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
    
	strkonsinyasireturorder.on('update', function(){
		var jumlah = 0;
		
		strkonsinyasireturorder.each(function(node){
			jumlah += parseInt(node.data.jumlah);
		});
		
		var ppn_persen = Ext.getCmp('kretur_ppn_persen').getValue();
		var ppn_rp = (ppn_persen * jumlah)/100;
		var grand_total = jumlah + ppn_rp;
		

		Ext.getCmp('kretur_jumlah').setValue(jumlah);
		Ext.getCmp('kretur_ppn_persen').setValue(ppn_persen);
		Ext.getCmp('kretur_ppn_rp').setValue(ppn_rp);
		Ext.getCmp('kretur_total').setValue(grand_total);
	});
	
	var strcbkreturproduk = new Ext.data.ArrayStore({
        fields: ['kd_produk'],
        data : []
    });
	
	var strgridkreturproduk = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
			{name: 'kd_produk', allowBlank: false, type: 'text'},
				{name: 'nama_produk', allowBlank: false, type: 'text'},                
                {name: 'nm_satuan', allowBlank: false, type: 'text'},
				{name: 'waktu_top', allowBlank: false, type: 'int'},
				{name: 'disk_persen_supp1_po', allowBlank: false, type: 'int'},
                {name: 'disk_persen_supp2_po', allowBlank: false, type: 'int'},
                {name: 'disk_persen_supp3_po', allowBlank: false, type: 'int'},
                {name: 'disk_persen_supp4_po', allowBlank: false, type: 'int'},
				{name: 'disk_amt_supp1_po', allowBlank: false, type: 'int'},
                {name: 'disk_amt_supp2_po', allowBlank: false, type: 'int'},
                {name: 'disk_amt_supp3_po', allowBlank: false, type: 'int'},
                {name: 'disk_amt_supp4_po', allowBlank: false, type: 'int'},	
				{name: 'disk_amt_supp5_po', allowBlank: false, type: 'int'},			
                {name: 'disk_persen_supp1', allowBlank: false, type: 'int'},
                {name: 'disk_persen_supp2', allowBlank: false, type: 'int'},
                {name: 'disk_persen_supp3', allowBlank: false, type: 'int'},
                {name: 'disk_persen_supp4', allowBlank: false, type: 'int'},
				{name: 'disk_persen_supp5', allowBlank: false, type: 'int'},
                {name: 'hrg_supplier', allowBlank: false, type: 'int'},
				{name: 'harga', allowBlank: false, type: 'int'},
				{name: 'jumlah', allowBlank: false, type: 'int'},
			],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("konsinyasi_retur_order/search_produk_by_supplier") ?>',
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
	var searchFieldKonsinyasiretur = new Ext.app.SearchField({
		width: 220,
		id: 'search_query_kretur',
		store: strgridkreturproduk
	});
	
	searchFieldKonsinyasiretur.onTrigger1Click = function(evt) {
		if (this.hasSearch) {
			this.el.dom.value = '';
			
			// Get the value of search field
			var fid = Ext.getCmp('id_cbkretursuplier').getValue();
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
	
	searchFieldKonsinyasiretur.onTrigger2Click = function(evt) {
	  var text = this.getRawValue();
	  if (text.length < 1) {
		this.onTrigger1Click();
		return;
	  }
	 
	  // Get the value of search field
	  var fid = Ext.getCmp('id_cbkretursuplier').getValue();
	  var o = { start: 0, kd_supplier: fid };
	 
	  this.store.baseParams = this.store.baseParams || {};
	  this.store.baseParams[this.paramName] = text;
	  this.store.reload({params:o});
	  this.hasSearch = true;
	  this.triggers[0].show();
	};
	
    // top toolbar
    var tbsearchbarangKonsinyasiretur = new Ext.Toolbar({
        items: [searchFieldKonsinyasiretur]
    });
	var gridkreturproduk = new Ext.grid.GridPanel({
        store: strgridkreturproduk,
        stripeRows: true,
        frame: true,
        border:true,
        columns: [{
            header: 'Kode produk',
            dataIndex: 'kd_produk',
            width: 100,
            sortable: true,			
            
        },{
            header: 'Nama produk',
            dataIndex: 'nama_produk',
            width: 400,
			sortable: true,         
        },{
            header: 'Satuan',
            dataIndex: 'nm_satuan',
            width: 80,			         
        },{
            header: 'Waktu TOP',
            dataIndex: 'waktu_top',
            width: 80,
			sortable: true,         
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
            dataIndex: 'hrg_supplier',           
        },{
            header: '',
            dataIndex: 'harga',       
        },{
            header: '',
            dataIndex: 'jumlah',
        },],
		tbar: tbsearchbarangKonsinyasiretur,
		listeners: {
			'rowdblclick': function(){			
				var sm = this.getSelectionModel();
				var sel = sm.getSelections();
				if (sel.length > 0) {	
					
                    Ext.getCmp('ekretur_kd_produk').setValue(sel[0].get('kd_produk'));
					Ext.getCmp('ekretur_nama_produk').setValue(sel[0].get('nama_produk'));                      
					Ext.getCmp('ekretur_nm_satuan').setValue(sel[0].get('nm_satuan'));
													
					Ext.getCmp('ekretur_disk_persen_supp1_po').setValue(sel[0].get('disk_persen_supp1_po'));
					Ext.getCmp('ekretur_disk_persen_supp2_po').setValue(sel[0].get('disk_persen_supp2_po'));	
					Ext.getCmp('ekretur_disk_persen_supp3_po').setValue(sel[0].get('disk_persen_supp3_po'));	
					Ext.getCmp('ekretur_disk_persen_supp4_po').setValue(sel[0].get('disk_persen_supp4_po'));
					
					Ext.getCmp('ekretur_disk_amt_supp1_po').setValue(sel[0].get('disk_amt_supp1_po'));
					Ext.getCmp('ekretur_disk_amt_supp2_po').setValue(sel[0].get('disk_amt_supp2_po'));	
					Ext.getCmp('ekretur_disk_amt_supp3_po').setValue(sel[0].get('disk_amt_supp3_po'));	
					Ext.getCmp('ekretur_disk_amt_supp4_po').setValue(sel[0].get('disk_amt_supp4_po'));
					Ext.getCmp('ekretur_disk_amt_supp5_po').setValue(sel[0].get('disk_amt_supp5_po'));
					
					Ext.getCmp('ekretur_disk_persen_supp1').setValue(sel[0].get('disk_persen_supp1'));
					Ext.getCmp('ekretur_disk_persen_supp2').setValue(sel[0].get('disk_persen_supp2'));	
					Ext.getCmp('ekretur_disk_persen_supp3').setValue(sel[0].get('disk_persen_supp3'));	
					Ext.getCmp('ekretur_disk_persen_supp4').setValue(sel[0].get('disk_persen_supp4'));	
					Ext.getCmp('ekretur_disk_persen_supp5').setValue(sel[0].get('disk_persen_supp5'));
					
					Ext.getCmp('ekretur_hrg_supplier').setValue(sel[0].get('hrg_supplier'));	
					Ext.getCmp('ekretur_harga').setValue(sel[0].get('harga'));
					Ext.getCmp('ekretur_jumlah').setValue(sel[0].get('jumlah'));	  
					Ext.getCmp('ekretur_qty').focus();
					menukreturproduk.hide();
				}
			}
		}
    });
	
	var menukreturproduk = new Ext.menu.Menu();
    menukreturproduk.add(new Ext.Panel({
        title: 'Pilih Barang',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 600,
        height: 250,
        closeAction: 'hide',
        plain: true,
        items: [gridkreturproduk],
        buttons: [{
            text: 'Close',
            handler: function(){
                menukreturproduk.hide();
            }
        }]
    }));
    
    Ext.ux.TwinCombokreturProduk = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
			//load store grid
            strgridkreturproduk.load({
				params: {
                	kd_supplier: Ext.getCmp('id_cbkretursuplier').getValue()                               
                }
			});
            menukreturproduk.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
	
	
    var editorkonsinyasireturorder = new Ext.ux.grid.RowEditor({
        saveText: 'Update'
    });
	    

    var gridkonsinyasireturorder = new Ext.grid.GridPanel({
        store: strkonsinyasireturorder,
        stripeRows: true,
        height: 200,
        frame: true,
        border:true,
		tbar: [{
            icon: BASE_ICONS + 'add.png',
            text: 'Add',
            handler: function(){
				if(Ext.getCmp('id_cbkretursuplier').getValue() == ''){
					Ext.Msg.show({
			                title: 'Error',
			                msg: 'Silahkan pilih supplier terlebih dulu',
			                modal: true,
			                icon: Ext.Msg.ERROR,
			                buttons: Ext.Msg.OK			               
			            });
					return;
				}
				var rowkonsinyasireturorder = new gridkonsinyasireturorder.store.recordType({
		            kd_produk : '',
		            qty: '0'
		        });                
                editorkonsinyasireturorder.stopEditing();
                strkonsinyasireturorder.insert(0, rowkonsinyasireturorder);
                gridkonsinyasireturorder.getView().refresh();
                gridkonsinyasireturorder.getSelectionModel().selectRow(0);
                editorkonsinyasireturorder.startEditing(0);
            }
        },{
            ref: '../removeBtn',
            icon: BASE_ICONS + 'delete.gif',
            text: 'Remove',
            disabled: true,
            handler: function(){
                editorkonsinyasireturorder.stopEditing();
                var s = gridkonsinyasireturorder.getSelectionModel().getSelections();
                for(var i = 0, r; r = s[i]; i++){
                    strkonsinyasireturorder.remove(r);
                }
            }
        }],
        plugins: [editorkonsinyasireturorder],
        columns: [{
            header: 'Kode',
            dataIndex: 'kd_produk',
            width: 110,
            sortable: true,
            editor: new Ext.ux.TwinCombokreturProduk({
				id: 'ekretur_kd_produk',
		        store: strcbkreturproduk,
				mode: 'local',
		        valueField: 'kd_produk',
		        displayField: 'kd_produk',
		        typeAhead: true,
		        triggerAction: 'all',
		        // allowBlank: false,
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
                id: 'ekretur_nama_produk'
            })
        },{
            header: 'Satuan',
            dataIndex: 'nm_satuan',
            width: 60,
            editor: new Ext.form.TextField({
                readOnly: true,
                id: 'ekretur_nm_satuan'
            })
        },{
            xtype: 'numbercolumn',
            header: 'Qty',
            dataIndex: 'qty',           
            width: 50,
			align: 'center',
            sortable: true,
            format: '0,0',
            editor: {
                xtype: 'numberfield',
                id: 'ekretur_qty',
                // allowBlank: false,
				selectOnFocus:true,
				listeners:{
					'change': function(){
						if(Ext.getCmp('ekretur_kd_produk').getValue() == ''){
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
						var jumlah = this.getValue() * Ext.getCmp('ekretur_harga').getValue();
						Ext.getCmp('ekretur_jumlah').setValue(jumlah);
					}
				}
            }
        },{
       		hidden: true,
            dataIndex: 'disk_persen_supp1_po',
            editor: new Ext.form.TextField({
                readOnly: true,
                id: 'ekretur_disk_persen_supp1_po'
            })
        },{
       		hidden: true,
            dataIndex: 'disk_persen_supp2_po',
            editor: new Ext.form.TextField({
                readOnly: true,
                id: 'ekretur_disk_persen_supp2_po'
            })
        },{
       		hidden: true,
            dataIndex: 'disk_persen_supp3_po',
            editor: new Ext.form.TextField({
                readOnly: true,
                id: 'ekretur_disk_persen_supp3_po'
            })
        },{
       		hidden: true,
            dataIndex: 'disk_persen_supp4_po',
            editor: new Ext.form.TextField({
                readOnly: true,
                id: 'ekretur_disk_persen_supp4_po'
            })
        },{
       		hidden: true,
            dataIndex: 'disk_amt_supp1_po',
            editor: new Ext.form.TextField({
                readOnly: true,
                id: 'ekretur_disk_amt_supp1_po'
            })
        },{
       		hidden: true,
            dataIndex: 'disk_amt_supp2_po',
            editor: new Ext.form.TextField({
                readOnly: true,
                id: 'ekretur_disk_amt_supp2_po'
            })
        },{
       		hidden: true,
            dataIndex: 'disk_amt_supp3_po',
            editor: new Ext.form.TextField({
                readOnly: true,
                id: 'ekretur_disk_amt_supp3_po'
            })
        },{
       		hidden: true,
            dataIndex: 'disk_amt_supp4_po',
            editor: new Ext.form.TextField({
                readOnly: true,
                id: 'ekretur_disk_amt_supp4_po'
            })
        },{
       		hidden: true,
            dataIndex: 'disk_amt_supp5_po',
            editor: new Ext.form.TextField({
                readOnly: true,
                id: 'ekretur_disk_amt_supp5_po'
            })
        },{
            xtype: 'numbercolumn',
            header: 'Diskon 1',
            dataIndex: 'disk_persen_supp1',           
            width: 100,
			align: 'right',
            sortable: true,
            format: '0,0',
            editor: {
                xtype: 'numberfield',
                id: 'ekretur_disk_persen_supp1',
                readOnly: true,
            }
        },{
            xtype: 'numbercolumn',
            header: 'Diskon 2',
            dataIndex: 'disk_persen_supp2',           
            width: 100,
            sortable: true,
			align: 'right',
            format: '0,0',
            editor: {
                xtype: 'numberfield',
                id: 'ekretur_disk_persen_supp2',
                readOnly: true,
            }
        },{
            xtype: 'numbercolumn',
            header: 'Diskon 3',
            dataIndex: 'disk_persen_supp3',           
            width: 100,
            sortable: true,
			align: 'right',
            format: '0,0',
            editor: {
                xtype: 'numberfield',
                id: 'ekretur_disk_persen_supp3',
                readOnly: true,
            }
        },{
            xtype: 'numbercolumn',
            header: 'Diskon 4',
            dataIndex: 'disk_persen_supp4',           
            width: 100,
            sortable: true,
			align: 'right',
            format: '0,0',
            editor: {
                xtype: 'numberfield',
                id: 'ekretur_disk_persen_supp4',
                readOnly: true,
            }
        },{
            xtype: 'numbercolumn',
            header: 'Diskon 5',
            dataIndex: 'disk_persen_supp5',           
            width: 100,
            sortable: true,
			align: 'right',
            format: '0,0',
            editor: {
                xtype: 'numberfield',
                id: 'ekretur_disk_persen_supp5',
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
                id: 'ekretur_hrg_supplier',
                readOnly: true,
            }
        },{
            xtype: 'numbercolumn',
            header: 'Harga',
            dataIndex: 'harga',           
            width: 100,
			align: 'right',
            sortable: true,
            format: '0,0',
            editor: {
                xtype: 'numberfield',
                id: 'ekretur_harga',
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
                id: 'ekretur_jumlah',
                readOnly: true,
            }
        },]
    });
	
	gridkonsinyasireturorder.getSelectionModel().on('selectionchange', function(sm){
        gridkonsinyasireturorder.removeBtn.setDisabled(sm.getCount() < 1);
    });
	
    var konsinyasireturorder = new Ext.FormPanel({
        id: 'konsinyasireturorder',
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
                    items: [headerkonsinyasireturorder]
                },
                gridkonsinyasireturorder,
                {
                    layout: 'column',
                    border: false,
                    items: [{
                        columnWidth: .6,
                        style:'margin:6px 3px 0 0;',
                        layout: 'form', 
                        labelWidth: 70,                   
                        items: [{
                                    xtype: 'textarea',
                                    fieldLabel: 'Remark',
                                    name: 'remark',                                    
                                    id: 'kretur_remark',                                      
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
                                        id: 'kretur_jumlah',                                      
                                        anchor: '90%',      
                                        fieldClass:'readonly-input number',											
                                        value:'0',
                                    },{
                                        xtype: 'compositefield',
                                        fieldLabel: 'PPN',
                                        combineErrors: false,
                                        items: [
                                           {
                                               xtype: 'numericfield',
											   currencySymbol:'',
											   format:'0',
                                               name : 'ppn_persen',
                                               id: 'kretur_ppn_persen',
												fieldClass:'number',
                                               width: 60,
											   allowBlank:false,
											   value: '10',
											   maxValue:100,
											   listeners: {
													'change': function(){
														var sub_jumlah = Ext.getCmp('kretur_sub_jumlah').getValue();	
														var ppn_rp = (this.getValue() * sub_jumlah)/100;
														var grand_total = sub_jumlah + ppn_rp;						
												
														Ext.getCmp('kretur_ppn_rp').setValue(ppn_rp);
														Ext.getCmp('kretur_total').setValue(grand_total);
														
												}
											   }
											   
                                           },
                                           {
                                               xtype: 'displayfield',
                                               value: '%',
											   width: 17.5,
                                           },
                                           {
                                               xtype: 'numericfield',
                                               name : 'ppn_rp',
                                               id : 'kretur_ppn_rp',
											   currencySymbol:'',
                                               fieldClass:'readonly-input number',
                                               readOnly: true,  
                                               anchor: '100%',
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
                                        id: 'kretur_total',                                        
                                        anchor: '90%',  
                                        fieldClass:'readonly-input bold-input number',  
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
			formBind: true,
            handler: function(){
                if(Ext.getCmp('kretur_total').getValue() == 0){
					 Ext.Msg.show({
                            title: 'Error',
                            msg: 'Tidak ada konsinyasi!',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK,
                            
                        });
						return;
				}
                var detailkonsinyasireturorder = new Array();              
                strkonsinyasireturorder.each(function(node){
					detailkonsinyasireturorder.push(node.data)                 
                });				
				
                Ext.getCmp('konsinyasireturorder').getForm().submit({
                    url: '<?= site_url("konsinyasi_retur_order/update_row") ?>',
                    scope: this,
                    params: {						
                      	detail: Ext.util.JSON.encode(detailkonsinyasireturorder),
						_jumlah: Ext.getCmp('kretur_jumlah').getValue(),
						_ppn_persen: Ext.getCmp('kretur_ppn_persen').getValue(),
						_ppn_rp: Ext.getCmp('kretur_ppn_rp').getValue(),
						_total: Ext.getCmp('kretur_total').getValue(),						
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
                        
                        clearkonsinyasireturorder();                       
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
                clearkonsinyasireturorder();
            }
        }]
    });
    
    konsinyasireturorder.on('afterrender', function(){
        this.getForm().load({
            url: '<?= site_url("konsinyasi_retur_order/get_form") ?>',
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
    
    function clearkonsinyasireturorder(){
        Ext.getCmp('konsinyasireturorder').getForm().reset();
        Ext.getCmp('konsinyasireturorder').getForm().load({
            url: '<?= site_url("konsinyasi_retur_order/get_form") ?>',
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
        strkonsinyasireturorder.removeAll();
    }
</script>