<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript"> 
       
        var strcblmutasistoksuplier = new Ext.data.ArrayStore({
        fields: ['kd_supplier'],
        data : []
        });

        var strgridlmssuplier = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
        fields: ['kd_supplier', 'nama_supplier'],
        root: 'data',
        totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
        url: '<?= site_url("laporan_purchase_order/search_supplier") ?>',
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
	

        var searchgridlmssuplier = new Ext.app.SearchField({
        store: strgridlmssuplier,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE			
        },
        width: 350,
        id: 'id_searchgridlmssuplier'
    });


        var gridlmssuplier = new Ext.grid.GridPanel({
        store: strgridlmssuplier,
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
	        items: [searchgridlmssuplier]
	    }),
            bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridlmssuplier,
            displayInfo: true
        }),
		listeners: {
			'rowdblclick': function(){			
				var sm = this.getSelectionModel();
				var sel = sm.getSelections();
				if (sel.length > 0) {				
                    Ext.getCmp('id_cblmssuplier').setValue(sel[0].get('kd_supplier'));
                    menulmssuplier.hide();
				}
			}
		}
    });

        var menulmssuplier = new Ext.menu.Menu();
        menulmssuplier.add(new Ext.Panel({
        title: 'Pilih Supplier',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridlmssuplier],
        buttons: [{
            text: 'Close',
            handler: function(){
                menulmssuplier.hide();
            }
        }]
    }));
    
    Ext.ux.TwinCombolmsSuplier = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            strgridlmssuplier.load();
            menulmssuplier.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
	
        menulmssuplier.on('hide', function(){
            var sf = Ext.getCmp('id_searchgridlmssuplier').getValue();
            if( sf != ''){
                    Ext.getCmp('id_searchgridlmssuplier').setValue('');
                    searchgridlmssuplier.onTrigger2Click();
            }
	});

        var cblmssuplier = new Ext.ux.TwinCombolmsSuplier({
        fieldLabel: 'Supplier',
        id: 'id_cblmssuplier',
        store: strcblmutasistoksuplier,
	mode: 'local',
        valueField: 'kd_supplier',
        displayField: 'kd_supplier',
        typeAhead: true,
        triggerAction: 'all',
        editable: false,
	anchor: '90%',
        hiddenName: 'kd_supplier',
        emptyText: 'Pilih Supplier'
    });


    var strcblmsproduk = new Ext.data.ArrayStore({
        fields: ['kd_produk'],
        data : []
        });

    var strgridlmsproduk = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
        fields: ['kd_produk', 'nama_produk'],
        root: 'data',
        totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
        url: '<?= site_url("laporan_purchase_order/search_produk") ?>',
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

        var searchgridlmsproduk = new Ext.app.SearchField({
            store: strgridlmsproduk,
            params: {
            start: STARTPAGE,
            limit: ENDPAGE			
            },
            width: 350,
            id: 'id_searchgridlmsproduk'
        });

        var gridlmsproduk = new Ext.grid.GridPanel({
        store: strgridlmsproduk,
        stripeRows: true,
        frame: true,
        border:true,
        columns: [{
            header: 'Kode Produk',
            dataIndex: 'kd_produk',
            width: 80,
            sortable: true			
            
        },{
            header: 'Nama Produk',
            dataIndex: 'nama_produk',
            width: 300,
            sortable: true        
        }],
            tbar: new Ext.Toolbar({
            items: [searchgridlmsproduk]
	    }),
            bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridlmsproduk,
            displayInfo: true
        }),
		listeners: {
			'rowdblclick': function(){			
				var sm = this.getSelectionModel();
				var sel = sm.getSelections();
				if (sel.length > 0) {
                    Ext.getCmp('id_cblmsproduk').setValue(sel[0].get('kd_produk'));
                    menulmsproduk.hide();
				}
			}
		}
    });
    Ext.ux.TwinCombolmsProduk = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
           strgridlmsproduk.load();
           menulmsproduk.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

        var menulmsproduk = new Ext.menu.Menu();
        menulmsproduk.add(new Ext.Panel({
        title: 'Pilih Produk',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridlmsproduk],
        buttons: [{
            text: 'Close',
            handler: function(){
                menulmsproduk.hide();
            }
        }]
    }));
    
   
	
	menulmsproduk.on('hide', function(){
		var sf = Ext.getCmp('id_searchgridlmsproduk').getValue();
		if( sf != ''){
			Ext.getCmp('id_searchgridlmsproduk').setValue('');
			searchgridlmsproduk.onTrigger2Click();
		}
	});

    var cblmsproduk = new Ext.ux.TwinCombolmsProduk({
        id: 'id_cblmsproduk',
        fieldLabel: 'Produk',
        store: strcblmsproduk,
        mode: 'local',
        anchor: '90%',
        valueField: 'kd_produk',
        displayField: 'kd_produk',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: true ,
        editable: false,
        hiddenName: 'kd_produk',
        emptyText: 'Pilih Produk' 
    });
    
       // combobox Ukuran
       
        var str_lms_cbukuran = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_ukuran', 'nama_ukuran'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("master_barang/get_ukuran") ?>',
            method: 'POST'
        }),
            listeners: {
            load: function() {
                var r = new (str_lms_cbukuran.recordType)({
                    'kd_ukuran': '',
                    'nama_ukuran': '-----'
                });
                str_lms_cbukuran.insert(0, r);
            },
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });
     var lms_cbukuran = new Ext.form.ComboBox({
        fieldLabel: 'Ukuran ',
        id: 'id_lms_cbukuran',
        store: str_lms_cbukuran,
        valueField: 'kd_ukuran',
        displayField: 'nama_ukuran',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_ukuran',
        emptyText: 'Pilih Ukuran'
       
    });
    
   // combobox Satuan
        var str_lms_cbsatuan = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_satuan', 'nm_satuan'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("master_barang/get_satuan") ?>',
            method: 'POST'
        }),
            listeners: {
            load: function() {
                var r = new (str_lms_cbsatuan.recordType)({
                    'kd_satuan': '',
                    'nm_satuan': '-----'
                });
                str_lms_cbsatuan.insert(0, r);
            },
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

     var lms_cbsatuan = new Ext.form.ComboBox({
        fieldLabel: 'Satuan ',
        id: 'id_lms_cbsatuan',
        store: str_lms_cbsatuan,
        valueField: 'kd_satuan',
        displayField: 'nm_satuan',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_satuan',
        emptyText: 'Pilih Satuan'
       
    });
     // combobox Status
     /*
        var str_lms_cbsatuan = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_satuan', 'nm_satuan'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("master_barang/get_satuan_produk") ?>',
            method: 'POST'
        }),
            listeners: {
            load: function() {
                var r = new (str_lms_cbsatuan.recordType)({
                    'kd_satuan': '',
                    'nm_satuan': '-----'
                });
                str_lms_cbsatuan.insert(0, r);
            },
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });
*/
     var lms_cbstatus = new Ext.form.ComboBox({
        fieldLabel: 'Status ',
        id: 'id_lms_cbstatus',
      //  store: str_lms_cbsatuan,
      //   valueField: 'kd_satuan',
      //  displayField: 'nm_satuan',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        anchor: '90%',
      //  hiddenName: 'kd_satuan',
        emptyText: 'Pilih Status'
       
    });


// cb kategori1

  var str_cblmskategori1 = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_kategori1', 'nama_kategori1'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("kategori2/get_kategori1") ?>',
            method: 'POST'
        }),
        listeners: {
            load: function() {
                var r = new (str_cblmskategori1.recordType)({
                    'kd_kategori1': '',
                    'nama_kategori1': '-----'
                });
                str_cblmskategori1.insert(0, r);
            },
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });
    
        var cblmskategori1 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 1',
        id: 'id_cblmskategori1',
        store: str_cblmskategori1,
        valueField: 'kd_kategori1',
        displayField: 'nama_kategori1',
        typeAhead: true,
        triggerAction: 'all',
        editable: false,
        width: 170,
        anchor: '90%',
        hiddenName: 'nama_kategori1',
        emptyText: 'Pilih kategori 1',
        listeners: {
            'select': function(combo, records) {
                var kdhp_cbkategori1 = cblmskategori1.getValue();
                cblmskategori2.store.proxy.conn.url = '<?= site_url("kategori3/get_kategori2") ?>/' + kdhp_cbkategori1;
                cblmskategori2.store.reload();            
            }
        }
    });
   
    // combobox kategori2

        var str_cblmskategori2 = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_kategori2', 'nama_kategori2'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({			
            url: '<?= site_url("kategori3/get_kategori2") ?>',
            method: 'POST'
        }),
        listeners: {
            load: function() {
                var r = new (str_cblmskategori2.recordType)({
                    'kd_kategori2': '',
                    'nama_kategori2': '-----'
                });
                str_cblmskategori2.insert(0, r);
            },
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

        var cblmskategori2 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 2 ',
        id: 'id_cblmskategori2',
        mode: 'local',
        store: str_cblmskategori2,
        valueField: 'kd_kategori2',
        displayField: 'nama_kategori2',
        typeAhead: true,
        triggerAction: 'all',
        editable: false,
        width: 170,
        anchor: '90%',
        hiddenName: 'nama_kategori2',
        emptyText: 'Pilih kategori 2',
        listeners: {
            select: function(combo, records) {
                var kd_hp_cbkategori1 = cblmskategori1.getValue();
                var kd_hp_cbkategori2 = this.getValue();
                cblmskategori3.setValue();
                cblmskategori3.store.proxy.conn.url = '<?= site_url("kategori4/get_kategori3") ?>/' + kd_hp_cbkategori1 +'/'+ kd_hp_cbkategori2;
                cblmskategori3.store.reload();          
            }
        }
    });
   
     // combobox kategori3

    var str_cblmskategori3 = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_kategori3', 'nama_kategori3'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("kategori4/get_kategori3") ?>',
            method: 'POST'
        }),
        listeners: {
            load: function() {
                var r = new (str_cblmskategori3.recordType)({
                    'kd_kategori3': '',
                    'nama_kategori3': '-----'
                });
                str_cblmskategori3.insert(0, r);
            },
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    var cblmskategori3 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 3 ',
        id: 'id_cblmskategori3',
        mode: 'local',
        store: str_cblmskategori3,
        valueField: 'kd_kategori3',
        displayField: 'nama_kategori3',
        typeAhead: true,
        triggerAction: 'all',
        editable: false,
        width: 170,
        anchor: '90%',
        hiddenName: 'nama_kategori3',
        emptyText: 'Pilih kategori 3',
        listeners: {
            select: function(combo, records) {
                var kd_hp_cbkategori1 = cblmskategori1.getValue();
                var kd_hp_cbkategori2 = cblmskategori2.getValue();
                var kd_hp_cbkategori3 = this.getValue();
                cblmskategori4.setValue();
                cblmskategori4.store.proxy.conn.url = '<?= site_url("master_barang/get_kategori4") ?>/' + kd_hp_cbkategori1 +'/'+ kd_hp_cbkategori2 +'/'+ kd_hp_cbkategori3;
                cblmskategori4.store.reload();     
            }
        }
    });
    
    // combobox kategori4

    var str_cblmskategori4 = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_kategori4', 'nama_kategori4'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("master_barang/get_kategori4") ?>',
            method: 'POST'
        }),
        listeners: {
            load: function() {
                var r = new (str_cblmskategori4.recordType)({
                    'kd_kategori4': '',
                    'nama_kategori4': '-----'
                });
                str_cblmskategori4.insert(0, r);
            },
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });
  
    var cblmskategori4 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 4 ',
        id: 'id_cblmskategori4',
        mode: 'local',
        store: str_cblmskategori4,
        valueField: 'kd_kategori4',
        displayField: 'nama_kategori4',
        typeAhead: true,
        triggerAction: 'all',
        editable: false,
        width: 170,
        anchor: '90%',
        hiddenName: 'nama_kategori4',
        emptyText: 'Pilih kategori 4'
    });

        var strcblmsgudang = new Ext.data.ArrayStore({
        fields: ['kd_lokasi'],
        data : []
        });

        var strgridlmsgudang = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
        fields: ['kd_lokasi', 'nama_lokasi'],
        root: 'data',
        totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
        url: '<?= site_url("laporan_mutasi_stok_lengkap/search_gudang") ?>',
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

        var searchgridlmsgudang = new Ext.app.SearchField({
        store: strgridlmsgudang,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE			
        },
        width: 350,
        id: 'id_searchgridlmsgudang'
    });

        var gridlmsgudang = new Ext.grid.GridPanel({
        store: strgridlmsgudang,
        stripeRows: true,
        frame: true,
        border:true,
        columns: [{
            header: 'Kode Lokasi',
            dataIndex: 'kd_lokasi',
            width: 80,
            sortable: true			
            
        },{
            header: 'Nama Lokasi',
            dataIndex: 'nama_lokasi',
            width: 300,
            sortable: true        
        }],
            tbar: new Ext.Toolbar({
            items: [searchgridlmsgudang]
	    }),
            bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridlmsgudang,
            displayInfo: true
        }),
		listeners: {
			'rowdblclick': function(){			
				var sm = this.getSelectionModel();
				var sel = sm.getSelections();
				if (sel.length > 0) {                   
                    Ext.getCmp('id_cbmsgudang').setValue(sel[0].get('kd_lokasi'));    
                    menulmsgudang.hide();
				}
			}
		}
    });

        var menulmsgudang = new Ext.menu.Menu();
        menulmsgudang.add(new Ext.Panel({
        title: 'Pilih Gudang',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridlmsgudang],
        buttons: [{
            text: 'Close',
            handler: function(){
                menulmsgudang.hide();
            }
        }]
    }));
    
    Ext.ux.TwinCombolmsGudang = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            strgridlmsgudang.load();
            menulmsgudang.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
	
	menulmsgudang.on('hide', function(){
		var sf = Ext.getCmp('id_searchgridlmsgudang').getValue();
		if( sf != ''){
			Ext.getCmp('id_searchgridlmsgudang').setValue('');
			searchgridlmsgudang.onTrigger2Click();
		}
	});

        var cblmsgudang = new Ext.ux.TwinCombolmsGudang({
        fieldLabel: 'Gudang',
        id: 'id_cbmsgudang',
        store: strcblmsgudang,
	mode: 'local',
        valueField: 'kd_lokasi',
        displayField: 'kd_lokasi',
        typeAhead: true,
        triggerAction: 'all',
        editable: false,
	anchor: '90%',
        hiddenName: 'kd_lokasi',
        emptyText: 'Pilih Gudang'
    });

        var headerlmstanggal = {
        layout: 'column',
        border: false,
        items: [{
            columnWidth: .8,
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
					            labelWidth: 100,
								defaults: { labelSeparator: ''},
								items:[	{
                                                                            xtype: 'datefield',
                                                                            fieldLabel: 'Dari Tgl <span class="asterix">*</span>',
                                                                            name: 'dari_tgl',				
                                                                            allowBlank:false,   
                                                                            format:'d-m-Y',  
                                                                            editable:false,           
                                                                            id: 'id_dari_tgl_ms',                
                                                                            anchor: '90%',
                                                                            value: ''
                                                                        }
								]
							},
							{
                                                    columnWidth: .5,
					            layout: 'form',
					            border: false,
					            labelWidth: 100,
								defaults: { labelSeparator: ''},
								items:[
									{
										xtype: 'datefield',
										fieldLabel: 'Sampai Tgl',
										name: 'sampai_tgl',			
										allowBlank:false,   
										editable:false,                
										format:'d-m-Y',  
										id: 'id_smp_tgl_ms',										
										anchor: '90%',
										value: ''										
									}
								]
							},
							
						]
					}
				]
			}]
        }
        ]
    }

    var headerlmskategori = {
        layout: 'column',
        border: false,
        items: [{
            columnWidth: .8,
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
					            labelWidth: 100,
								defaults: { labelSeparator: ''},
								items:[									
									cblmskategori1,
                                                                        cblmskategori2,
                                                                        cblmskategori3,
                                                                        cblmskategori4,
                                                                        lms_cbsatuan
                                                                ]},
                                         {
						
				
                                                    columnWidth: .5,
					            layout: 'form',
					            border: false,
					            labelWidth: 100,
								defaults: { labelSeparator: ''},
								items:[	 
                                                                        cblmsproduk,
                                                                        cblmssuplier,
                                                                        lms_cbstatus,
                                                                        cblmsgudang,
                                                                        lms_cbukuran
								]
														
							
						
                                            }
                                          ]
					}
                                    ]
                        }
                        ]
			}]
      
    }
	   
	

    var headerlms = {
            buttonAlign: 'left',
            layout: 'form',
            border: false,
            labelWidth: 100,
			defaults: { labelSeparator: ''},
                items: [headerlmstanggal,headerlmskategori
                      
                ],
            buttons: [{
            text: 'Print',
			formBind:true,
            handler: function(){				
				winlaporanmutasiprint.show();
				Ext.getDom('laporanmutasiprint').src = '<?= site_url("laporan_purchase_order/print_form") ?>';			
			}
        },{
			text: 'Cancel',
			handler: function(){
				clearlaporanmutasi();
			}
		}]
    };
    var winlaporanmutasiprint = new Ext.Window({
        id: 'id_winlaporanmutasiprint',
	title: 'Print Laporan Usulan Mutasi Stok Lengkap',
        closeAction: 'hide',
        width: 900,
        height: 450,
        layout: 'fit',
        border: false,
        html:'<iframe style="width:100%;height:390px;" id="laporanmutasiprint" src=""></iframe>'
    });
        
    var laporanmutasi = new Ext.FormPanel({        
        id: 'rpt_mutasi_stok_lengkap',		
        border: false,
        frame: true,
        monitorValid: true,
        labelWidth: 130,
        items: [{
                    bodyStyle: {
                        margin: '0px 0px 15px 0px'
                    },					
                    items: [headerlms]
                }
        ]
    });
	
	function clearlaporanmutasi(){
		Ext.getCmp('rpt_mutasi_stok_lengkap').getForm().reset();
		
	}
</script>