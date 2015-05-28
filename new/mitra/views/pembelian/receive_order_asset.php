<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">		
	var strcbproasuplier = new Ext.data.ArrayStore({
        fields: ['kd_supplier'],
        data : []
    });
	
	var strgridproasuplier = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_supplier', 'nama_supplier'],
            root: 'data',
            totalproperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("pembelian_receive_order_asset/search_supplier") ?>',
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
	
	var searchgridproasuplier = new Ext.app.SearchField({
        store: strgridproasuplier,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE			
        },
        width: 350,
		id: 'id_searchgridproasuplier'
    });
	
	
	var gridproasuplier = new Ext.grid.GridPanel({
        store: strgridproasuplier,
        stripeRows: true,
        frame: true,
        border:true,
        columns: [{
            header: 'Kode Supplier',
            dataIndex: 'kd_supplier',
            width: 80,
            sortable: true			
            
        },{
            header: 'Nama Supplier',
            dataIndex: 'nama_supplier',
            width: 300,
            sortable: true         
        }],
		tbar: new Ext.Toolbar({
	        items: [searchgridproasuplier]
	    }),
            bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridproasuplier,
            displayInfo: true
        }),
		listeners: {
			'rowdblclick': function(){			
				var sm = this.getSelectionModel();
				var sel = sm.getSelections();
				if (sel.length > 0) {				
                                        Ext.getCmp('id_cbproasuplier').setValue(sel[0].get('kd_supplier'));
					Ext.getCmp('proa_nama_supplier').setValue(sel[0].get('nama_supplier'));
                   
					menuproasuplier.hide();
				}
			}
		}
    });
	
	var menuproasuplier = new Ext.menu.Menu();
        menuproasuplier.add(new Ext.Panel({
        title: 'Pilih Supplier',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridproasuplier],
        buttons: [{
            text: 'Close',
            handler: function(){
                menuproasuplier.hide();
            }
        }]
    }));
    
    Ext.ux.TwinComboproaSuplier = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
			//load store grid
            strgridproasuplier.load();
            menuproasuplier.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
	
	menuproasuplier.on('hide', function(){
		var sf = Ext.getCmp('id_searchgridproasuplier').getValue();
		if( sf != ''){
			Ext.getCmp('id_searchgridproasuplier').setValue('');
			searchgridproasuplier.onTrigger2Click();
		}
	});
	
	var cbproasuplier = new Ext.ux.TwinComboproaSuplier({
        fieldLabel: 'Supplier <span class="asterix">*</span>',
        id: 'id_cbproasuplier',
        store: strcbproasuplier,
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
	// HEADER Pembelian Receive Order Asset
    var headerpembelianreceiveorderasset = {
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
	                fieldLabel: 'RO No.',
	                name: 'no_do',
	                readOnly:true,
			fieldClass:'readonly-input',
	                id: 'proa_no_do',                
	                anchor: '90%',
	                value:''
	            },cbproasuplier
			]
        }, {
            columnWidth: .4,
            layout: 'form',
            border: false,
            labelWidth: 100,
            defaults: { labelSeparator: ''},
            items: [ {
		xtype: 'datefield',
                fieldLabel: 'Tanggal Terima <span class="asterix">*</span>',
                name: 'tanggal_terima',				
                allowBlank:false,   
		format:'d-m-Y',  
		editable:false,           
                id: 'proa_tanggal_terima',                
                anchor: '90%',
                value: '',
                maxValue: (new Date()).clearTime()
			},{
	                xtype: 'textfield',
	                fieldLabel: 'Nama Supplier',
	                name: 'nama_supplier',
	                readOnly:true,
			fieldClass:'readonly-input',
	                id: 'proa_nama_supplier',                
	                anchor: '90%',
	                value:''
	            }]
        },{
            columnWidth: .3,
            layout: 'form',
            border: false,
            labelWidth: 120,
            defaults: { labelSeparator: ''},
            items: [ {
                xtype: 'textfield',
                fieldLabel: 'Tanggal Input',
                name: 'tanggal',
		fieldClass:'readonly-input',
                readOnly:true,
                id: 'proa_tanggal',                
                anchor: '90%',
                value: ''
            }, {
	                xtype: 'textfield',
	                fieldLabel: 'No. Bukti Supplier<span class="asterix">*</span>',
	                name: 'bukti_supplier', 
                        allowBlank: false,
	                id: 'proa_bukti_supplier',                
	                anchor: '90%'
            }]
        }]
    };
    
	var strcbkdsubblokproa = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['sub', 'nama_sub'],
            root: 'data',
            totalproperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("pembelian_receive_order_asset/get_sub_blok") ?>',
            method: 'POST'
        }),
		listeners: {
			
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg === 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });
	
	var strgridsubblokproa = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
		'sub',
		'nama_sub',
                'kd_sub_blok', 
		'kd_blok',
                'kd_lokasi',
		'nama_lokasi',
                'nama_blok',
                'nama_sub_blok',
                'kapasitas'
		],
            root: 'data',
            totalproperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("pembelian_receive_order_asset/get_rows_lokasi") ?>',
            method: 'POST'
        }),
        listeners: {
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg === 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });
	
	// search field
    var searchgridproasubblok = new Ext.app.SearchField({
        store: strgridsubblokproa,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        id: 'idsearchgridproasubblok'
    });
	
	// top toolbar
    var tbgridproasubblok = new Ext.Toolbar({
        items: [searchgridproasubblok]
    });
	
	var gridproasubblok = new Ext.grid.GridPanel({
        store: strgridsubblokproa,
        stripeRows: true,
        frame: true,
        border:true,
	tbar: tbgridproasubblok,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridsubblokproa,
            displayInfo: true
        }),
        columns: [{
			dataIndex: 'kd_lokasi',
			hidden: true
		},{
			dataIndex: 'kd_blok',
			hidden: true
		},{
			dataIndex: 'kd_sub_blok',
			hidden: true
		},{
                        header: 'Kode',
                        dataIndex: 'sub',
                        width: 90,
                        sortable: true			

                    },{
                        header: 'Sub Blok Lokasi',
                        dataIndex: 'nama_sub',
                        width: 250,
                        sortable: true        
                    }],
		listeners: {
			'rowdblclick': function(){			
				var sm = this.getSelectionModel();
				var sel = sm.getSelections();
				if (sel.length > 0) {
					Ext.getCmp('eproa_sub').setValue(sel[0].get('sub'));
					Ext.getCmp('eproa_nama_sub').setValue(sel[0].get('nama_sub'));
										
					menusubblokreceiveorderasset.hide();
				}
			}
		}
    });
	
    var menusubblokreceiveorderasset = new Ext.menu.Menu();
    menusubblokreceiveorderasset.add(new Ext.Panel({
        title: 'Pilih Sub Blok Lokasi',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 350,
        height: 250,
        closeAction: 'hide',
        plain: true,
        items: [gridproasubblok],
        buttons: [{
            text: 'Close',
            handler: function(){
                menusubblokreceiveorderasset.hide();
            }
        }]
    }));
    
    Ext.ux.TwinComboproaSubBlok = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
			//load store grid
//            strgridsubblokproa.load({
//                params:{
//                    kd_produk: Ext.getCmp('eproa_kd_produk').getValue()
//                }
//            });
            strgridsubblokproa.setBaseParam('kd_produk',Ext.getCmp('eproa_kd_produk').getValue());
            strgridsubblokproa.load();
            menusubblokreceiveorderasset.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
	
	var strpembelianreceiveorderasset = new Ext.data.Store({
		autoSave:false,
        reader: new Ext.data.JsonReader({
            fields: [
				{name: 'no_po', allowBlank: false, type: 'text'},
				{name: 'kd_produk', allowBlank: false, type: 'text'},
				{name: 'nama_produk', allowBlank: false, type: 'text'},				
				{name: 'nm_satuan', allowBlank: false, type: 'text'},
                                {name: 'qty_po', allowBlank: false, type: 'int'},
				{name: 'qty_do', allowBlank: false, type: 'int'},				
				{name: 'sub', allowBlank: false, type: 'text'},
				{name: 'nama_sub', allowBlank: false, type: 'text'}				
			],
            root: 'data',
            totalproperty: 'record'
        }),
        writer: new Ext.data.JsonWriter(
        {
			encode: true,
			writeAllFields: true
        })
    });
	
	var strcbproanopo = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['no_po'],
            root: 'data',
            totalproperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("pembelian_receive_order_asset/get_all_po") ?>',
            method: 'POST'
        }),
        listeners: {
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg === 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });
	
	var strcbproaproduk = new Ext.data.ArrayStore({
        fields: ['kd_produk'],
        data : []
    });
	
	var strgridproaproduk = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_produk','nama_produk','qty_po','nm_satuan','qty_do','qty_terima','jumlah_barcode'],
            root: 'data',
            totalproperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("pembelian_receive_order_asset/search_produk_by_no_po") ?>',
            method: 'POST'
        }),
		listeners: {
			
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg === 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });	
	var searchFieldROAsset = new Ext.app.SearchField({
		width: 220,
		id: 'search_query_rob',
		store: strgridproaproduk
	});
	
	searchFieldROAsset.onTrigger1Click = function(evt) {
		if (this.hasSearch) {
			this.el.dom.value = '';
			
			// Get the value of search field
			var fid = Ext.getCmp('eproa_no_po').getValue();
			var o = { start: 0, no_po: fid };
			
			this.store.baseParams = this.store.baseParams || {};
			this.store.baseParams[this.paramName] = '';
			this.store.reload({
						params : o
					});
			this.triggers[0].hide();
			this.hasSearch = false;
		}
	};
	
	searchFieldROAsset.onTrigger2Click = function(evt) {
	  var text = this.getRawValue();
	  if (text.length < 1) {
		this.onTrigger1Click();
		return;
	  }
	 
	  // Get the value of search field
	  var fid = Ext.getCmp('eproa_no_po').getValue();
	  var o = { start: 0, no_po: fid };
	 
	  this.store.baseParams = this.store.baseParams || {};
	  this.store.baseParams[this.paramName] = text;
	  this.store.reload({params:o});
	  this.hasSearch = true;
	  this.triggers[0].show();
	};
	
    // top toolbar
    var tbsearchbarang = new Ext.Toolbar({
        items: [searchFieldROAsset]
    });
	
	var gridproaproduk = new Ext.grid.GridPanel({
        store: strgridproaproduk,
        stripeRows: true,
        frame: true,
        border:true,
        columns: [{
            header: 'Kode produk',
            dataIndex: 'kd_produk',
            width: 100,
            sortable: true			
            
        },{
            header: 'Nama produk',
            dataIndex: 'nama_produk',
            width: 400,
            sortable: true         
        },{
            header: 'Satuan',
            dataIndex: 'nm_satuan',
            width: 80			         
        },{
            header: 'Qty PO',
            dataIndex: 'qty_po',
            width: 80,
            sortable: true         
        },{
                header: 'Qty RO',
                dataIndex: 'qty_do',
                width: 80,
                sortable: true,         
         },{
                header: 'Qty',
                dataIndex: 'qty_terima',
                width: 80,
                sortable: true,         
            },{
                header: 'Jumlah Barcode',
                dataIndex: 'jumlah_barcode',
                width: 80,
                sortable: true,         
            }],
		tbar: tbsearchbarang,
		listeners: {
			'rowdblclick': function(){			
				var sm = this.getSelectionModel();
				var sel = sm.getSelections();
				if (sel.length > 0) {	
                                    var _ada = false;
                                
                                    strpembelianreceiveorderasset.each(function(record){
                                        if(record.get('kd_produk') === sel[0].get('kd_produk') && (record.get('no_po') === Ext.getCmp('eproa_no_po').getValue())){
                                            _ada = true;
                                        }
                                    });

                                    if (_ada){
                                        Ext.Msg.show({
                                            title: 'Error',
                                            msg: 'Produk Berdasarkan No Po sudah pernah dipilih',
                                            modal: true,
                                            icon: Ext.Msg.ERROR,
                                            buttons: Ext.Msg.OK,
                                            fn: function(btn){
                                                if (btn == 'ok') {
                                                    Ext.getCmp('eproa_kd_produk').reset();
                                                }
                                            }                            
                                        });
                                        Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');
                                        Ext.getCmp('eproa_kd_produk').focus();	
                                        return;
                                    }
                                        Ext.getCmp('eproa_kd_produk').setValue(sel[0].get('kd_produk'));
					Ext.getCmp('eproa_nama_produk').setValue(sel[0].get('nama_produk'));
                                        Ext.getCmp('eproa_qty_po').setValue(sel[0].get('qty_po'));  
                                        Ext.getCmp('eproa_qty').setValue(sel[0].get('qty_do')); 
                                        Ext.getCmp('eproa_qty_terima').setValue(sel[0].get('qty_terima'));
					Ext.getCmp('eproa_nm_satuan').setValue(sel[0].get('nm_satuan'));     
					Ext.getCmp('eproa_qty').setValue(0);
					Ext.getCmp('eproa_qty').focus();
					menuproaproduk.hide();
				}
			}
		}
    });
	
	var menuproaproduk = new Ext.menu.Menu();
    menuproaproduk.add(new Ext.Panel({
        title: 'Pilih Barang',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 600,
        height: 250,
        closeAction: 'hide',
        plain: true,
        items: [gridproaproduk],
        buttons: [{
            text: 'Close',
            handler: function(){
                menuproaproduk.hide();
            }
        }]
    }));
    
    Ext.ux.TwinComboproaproduk = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
			if(Ext.getCmp('eproa_no_po').getValue() === ''){
				Ext.Msg.show({
			                title: 'Error',
			                msg: 'Silahkan pilih No PO terlebih dulu',
			                modal: true,
			                icon: Ext.Msg.ERROR,
			                buttons: Ext.Msg.OK			               
			            });
				return;				
			}
			//load store grid
            strgridproaproduk.load({
				params: {
                	no_po: Ext.getCmp('eproa_no_po').getValue()                                 
                }
			});
            menuproaproduk.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
	
	var strcbproanopo = new Ext.data.ArrayStore({
        fields: ['no_po'],
        data : []
    });
	
	var strgridproanopo = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['no_po'],
            root: 'data',
            totalproperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("pembelian_receive_order_asset/get_all_po") ?>',
            method: 'POST'
        }),
		listeners: {
			
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg === 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });	
	
	var gridproanopo = new Ext.grid.GridPanel({
        store: strgridproanopo,
        stripeRows: true,
        frame: true,
        border:true,
        columns: [{
            header: 'No PO',
            dataIndex: 'no_po',
            width: 200,
            sortable: true			
            
        }],
		
		listeners: {
			'rowdblclick': function(){			
				var sm = this.getSelectionModel();
				var sel = sm.getSelections();
				if (sel.length > 0) {				
                    Ext.getCmp('eproa_no_po').setValue(sel[0].get('no_po'));
					
					menuproanopo.hide();
				}
			}
		}
    });
	
	var menuproanopo = new Ext.menu.Menu();
    menuproanopo.add(new Ext.Panel({
        title: 'Pilih No PO',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 300,
        height: 250,
        closeAction: 'hide',
        plain: true,
        items: [gridproanopo],
        buttons: [{
            text: 'Close',
            handler: function(){
                menuproanopo.hide();
            }
        }]
    }));
    
    Ext.ux.TwinComboproaNoPO = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
			//load store grid
            strgridproanopo.load({
				params: {
                	kd_supplier: Ext.getCmp('id_cbproasuplier').getValue()                                 
                }
			});
            menuproanopo.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
	
    var editorpembelianreceiveorderasset = new Ext.ux.grid.RowEditor({
        saveText: 'Update'		
    });

    var gridpembelianreceiveorderasset = new Ext.grid.GridPanel({
        store: strpembelianreceiveorderasset,
        stripeRows: true,
        height: 300,
        frame: true,
        border:true,
        plugins: [editorpembelianreceiveorderasset],
		tbar: [{
                        icon: BASE_ICONS + 'add.png',
                        text: 'Add',
                        handler: function(){
				if(Ext.getCmp('id_cbproasuplier').getValue() === ''){
					Ext.Msg.show({
			                title: 'Error',
			                msg: 'Silahkan pilih supplier terlebih dulu',
			                modal: true,
			                icon: Ext.Msg.ERROR,
			                buttons: Ext.Msg.OK			               
			            });
					return;
				}
                            var rowpembelianreceiveorderasset = new gridpembelianreceiveorderasset.store.recordType({
		            no_po:'',
                            kd_produk : '',
		            qty: ''
		        });                
                editorpembelianreceiveorderasset.stopEditing();
                strpembelianreceiveorderasset.insert(0, rowpembelianreceiveorderasset);
                gridpembelianreceiveorderasset.getView().refresh();
                gridpembelianreceiveorderasset.getSelectionModel().selectRow(0);
                editorpembelianreceiveorderasset.startEditing(0);
            }
        },{
            ref: '../removeBtn',
            icon: BASE_ICONS + 'delete.gif',
            text: 'Remove',
            disabled: true,
            handler: function(){
                editorpembelianreceiveorderasset.stopEditing();
                var s = gridpembelianreceiveorderasset.getSelectionModel().getSelections();
                for(var i = 0, r; r = s[i]; i++){
                    strpembelianreceiveorderasset.remove(r);
                }
            }
        }],
        columns: [{
            header: 'No PO',
            dataIndex: 'no_po',
            width: 140,
			editor: new Ext.ux.TwinComboproaNoPO({
			id: 'eproa_no_po',
		        store: strcbproanopo,
			mode: 'local',
		        valueField: 'no_po',
		        displayField: 'no_po',
		        typeAhead: true,
		        triggerAction: 'all',
		        //allowBlank: false,
		        editable: false,
		        hiddenName: 'no_po',
		        emptyText: 'Pilih No PO'
				
		    })          
        },{
            header: 'Kode',
            dataIndex: 'kd_produk',
            width: 110,
			editor: new Ext.ux.TwinComboproaproduk({
			id: 'eproa_kd_produk',
		        store: strcbproaproduk,
			mode: 'local',
		        valueField: 'kd_produk',
		        displayField: 'kd_produk',
		        typeAhead: true,
		        triggerAction: 'all',
		        //allowBlank: false,
		        editable: false,
		        hiddenName: 'kd_produk',
		        emptyText: 'Pilih produk'
				
		    })
           
        },{
            header: 'Nama Barang',
            dataIndex: 'nama_produk',
            width: 300,
            editor: new Ext.form.TextField({
                readOnly: true,
                id: 'eproa_nama_produk'
            })
        },{
            header: 'Satuan',
            dataIndex: 'nm_satuan',
            width: 80,
            editor: new Ext.form.TextField({
                readOnly: true,
                id: 'eproa_nm_satuan'
            })
        },{
            header: 'Qty PO',
            dataIndex: 'qty_po',
	    width: 50,
            editor: new Ext.form.TextField({
                readOnly: true,
                id: 'eproa_qty_po'
            })
        },{
            header: 'Qty RO',
            dataIndex: 'qty_terima',
	    width: 50,
            editor: new Ext.form.TextField({
                readOnly: true,
                id: 'eproa_qty_terima'
            })
        },{
            xtype: 'numbercolumn',
            header: 'Qty',
            dataIndex: 'qty_do',           
            width: 50,
            align: 'center',
            sortable: true,
            format: '0,0',
            editor: {
                xtype: 'numberfield',
                id: 'eproa_qty',
                //allowBlank: false,
		selectOnFocus: true,
                 listeners: {
                        'render': function(c) {
                            c.getEl().on('keyup', function() {
                                
				Ext.getCmp('pro_jumlah_barcode').setValue(qty);
                                
                                var max = parseFloat (Ext.getCmp('eproa_qty_po').getValue());
                                var jml = parseFloat(Ext.getCmp('eproa_qty_terima').getValue());
                                var qty = this.getValue();
                                var validasi = qty + jml;
                                console.log(validasi);
                                console.log(max);
                                if(validasi > max){
                                    Ext.Msg.show({
                                        title: 'Error',
                                        msg: 'Qty RO + Qty tidak boleh lebih besar dari Qty PO',
                                        modal: true,
                                        icon: Ext.Msg.ERROR,
                                        buttons: Ext.Msg.OK,
                                        fn: function(btn){
                                            if (btn == 'ok') {
                                                
                                                Ext.getCmp('eproa_qty').reset();
                                            }
                                        }                            
                                    });
                                    Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');
                                    return;
                                }
                            }, c);
                        }
                    }
            }
        },{
            header: 'Kode Sub Blok',
            dataIndex: 'sub',
            width: 100,
            editor: new Ext.ux.TwinComboproaSubBlok({
                    id: 'eproa_sub',
                    store: strcbkdsubblokproa,
                    valueField: 'sub',
                    displayField: 'sub',
                    typeAhead: true,
                    triggerAction: 'all',
                    //allowBlank: false,
                    editable: false,
                    hiddenName: 'sub',
                    emptyText: 'Pilih Sub Blok',
                    listeners: {
                            'expand': function(){
                                    strcbkdsubblokproa.load();
                            }
                    }
            })			
        },{
            header: 'Sub Blok',
            dataIndex: 'nama_sub',
            width: 200,
            editor: new Ext.form.TextField({
                readOnly: true,
                id: 'eproa_nama_sub'
            })
        }]
    });
	
	gridpembelianreceiveorderasset.getSelectionModel().on('selectionchange', function(sm){
        gridpembelianreceiveorderasset.removeBtn.setDisabled(sm.getCount() < 1);
    });
    
    var pembelianreceiveorderasset = new Ext.FormPanel({
        id: 'pembelianreceiveorderasset',
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
                    items: [headerpembelianreceiveorderasset]
                },
                gridpembelianreceiveorderasset
        ],
        buttons: [{
            text: 'Save',
            formBind: true,
            handler: function(){
                if(Ext.getCmp('eproa_sub').getValue() ==''){
                        Ext.Msg.show({
                            title: 'Error',
                            msg: 'kode sub blok harus di isi!',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK
                            
                        });
                        return;
                    }
                var detailpembelianreceiveorderasset = new Array();              
                strpembelianreceiveorderasset.each(function(node){
                 detailpembelianreceiveorderasset.push(node.data)
                });
                Ext.getCmp('pembelianreceiveorderasset').getForm().submit({
                    url: '<?= site_url("pembelian_receive_order_asset/update_row") ?>',
                    scope: this,
                    params: {
                      detail: Ext.util.JSON.encode(detailpembelianreceiveorderasset)
                    },
                    waitMsg: 'Saving Data...',
                    success: function(form, action){
						var r = Ext.util.JSON.decode(action.response.responseText);
                        Ext.Msg.show({
                            title: 'Success',
                            msg: r.errMsg,
                            modal: true,
                            icon: Ext.Msg.INFO,
                            buttons: Ext.Msg.OK
                        });                     
                        
                        clearpembelianreceiveorderasset();                       
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
                clearpembelianreceiveorderasset();
            }
        }]
    });
    
    pembelianreceiveorderasset.on('afterrender', function(){
        this.getForm().load({
            url: '<?= site_url("pembelian_receive_order_asset/get_form") ?>',
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
    
    function clearpembelianreceiveorderasset(){
        Ext.getCmp('pembelianreceiveorderasset').getForm().reset();
        Ext.getCmp('pembelianreceiveorderasset').getForm().load({
            url: '<?= site_url("pembelian_receive_order_asset/get_form") ?>',
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
        strpembelianreceiveorderasset.removeAll();
    }
</script>
