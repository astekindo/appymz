<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript"> 
       

        var strcblmsvsuplier = new Ext.data.ArrayStore({
        fields: ['kd_supplier'],
        data : []
        });

        var strgridlmsvsuplier = new Ext.data.Store({
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

        var searchgridlmsvsuplier = new Ext.app.SearchField({
        store: strgridlmsvsuplier,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE			
        },
        width: 350,
        id: 'id_searchgridlmsvsuplier '
    });

        var gridlmsvsuplier = new Ext.grid.GridPanel({
        store: strgridlmsvsuplier,
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
	        items: [searchgridlmsvsuplier]
	    }),
            bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridlmsvsuplier,
            displayInfo: true
        }),
		listeners: {
			'rowdblclick': function(){			
				var sm = this.getSelectionModel();
				var sel = sm.getSelections();
				if (sel.length > 0) {				
                    Ext.getCmp('id_cblmsvsuplier').setValue(sel[0].get('kd_supplier'));
                    menulmsvsuplier.hide();
				}
			}
		}
    });

        var menulmsvsuplier = new Ext.menu.Menu();
        menulmsvsuplier.add(new Ext.Panel({
        title: 'Pilih Supplier',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridlmsvsuplier],
        buttons: [{
            text: 'Close',
            handler: function(){
                menulmsvsuplier.hide();
            }
        }]
    }));
    
    Ext.ux.TwinCombolmsvSuplier = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            strgridlmsvsuplier.load();
            menulmsvsuplier.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
	
        menulmsvsuplier.on('hide', function(){
            var sf = Ext.getCmp('id_searchgridlmsvsuplier').getValue();
            if( sf != ''){
                    Ext.getCmp('id_searchgridlmsvsuplier').setValue('');
                    searchgridlmsvsuplier.onTrigger2Click();
            }
	});

        var cblmsvsuplier = new Ext.ux.TwinCombolmsvSuplier({
        fieldLabel: 'Supplier',
        id: 'id_cblmsvsuplier',
        store: strcblmsvsuplier,
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

    var strcblmsvproduk = new Ext.data.ArrayStore({
        fields: ['kd_produk'],
        data : []
        });

    var strgridlmsvproduk = new Ext.data.Store({
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

        var searchgridlmsvproduk = new Ext.app.SearchField({
            store: strgridlmsvproduk,
            params: {
            start: STARTPAGE,
            limit: ENDPAGE			
            },
            width: 350,
            id: 'id_searchgridlmsvproduk'
        });

        var gridlmsvproduk = new Ext.grid.GridPanel({
        store: strgridlmsvproduk,
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
            items: [searchgridlmsvproduk]
	    }),
            bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridlmsvproduk,
            displayInfo: true
        }),
		listeners: {
			'rowdblclick': function(){			
				var sm = this.getSelectionModel();
				var sel = sm.getSelections();
				if (sel.length > 0) {
                    Ext.getCmp('id_cblmsvproduk').setValue(sel[0].get('kd_produk'));
                    menulmsvproduk.hide();
				}
			}
		}
    });
    Ext.ux.TwinCombolmsvProduk = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
           strgridlmsvproduk.load();
           menulmsvproduk.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

        var menulmsvproduk = new Ext.menu.Menu();
        menulmsvproduk.add(new Ext.Panel({
        title: 'Pilih Produk',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridlmsvproduk],
        buttons: [{
            text: 'Close',
            handler: function(){
                menulmsvproduk.hide();
            }
        }]
    }));
    
   
	
	menulmsvproduk.on('hide', function(){
		var sf = Ext.getCmp('id_searchgridlmsvproduk').getValue();
		if( sf != ''){
			Ext.getCmp('id_searchgridlmsvproduk').setValue('');
			searchgridlmsvproduk.onTrigger2Click();
		}
	});

    var cblmsvproduk = new Ext.ux.TwinCombolmsvProduk({
        id: 'id_cblmsvproduk',
        fieldLabel: 'Produk',
        store: strcblmsvproduk,
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
       
        var str_lmsv_cbukuran = new Ext.data.Store({
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
                var r = new (str_lmsv_cbukuran.recordType)({
                    'kd_ukuran': '',
                    'nama_ukuran': '-----'
                });
                str_lmsv_cbukuran.insert(0, r);
            },
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });
     var lmsv_cbukuran = new Ext.form.ComboBox({
        fieldLabel: 'Ukuran ',
        id: 'id_lmsv_cbukuran',
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
        var str_lmsv_cbsatuan = new Ext.data.Store({
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
                var r = new (str_lmsv_cbsatuan.recordType)({
                    'kd_satuan': '',
                    'nm_satuan': '-----'
                });
                str_lmsv_cbsatuan.insert(0, r);
            },
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

     var lmsv_cbsatuan = new Ext.form.ComboBox({
        fieldLabel: 'Satuan',
        id: 'id_lmsv_cbsatuan',
        store: str_lmsv_cbsatuan,
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
     var lmsv_cbstatus = new Ext.form.ComboBox({
        fieldLabel: 'Status ',
        id: 'id_lmsv_cbstatus',
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

  var str_cblmsvkategori1 = new Ext.data.Store({
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
                var r = new (str_cblmsvkategori1.recordType)({
                    'kd_kategori1': '',
                    'nama_kategori1': '-----'
                });
                str_cblmsvkategori1.insert(0, r);
            },
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });
    
        var cblmsvkategori1 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 1',
        id: 'id_cblmsvkategori1',
        store: str_cblmsvkategori1,
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
                var kdhp_cbkategori1 = cblmsvkategori1.getValue();
                cblmsvkategori2.store.proxy.conn.url = '<?= site_url("kategori3/get_kategori2") ?>/' + kdhp_cbkategori1;
                cblmsvkategori2.store.reload();            
            }
        }
    });
   
    // combobox kategori2

        var str_cblmsvkategori2 = new Ext.data.Store({
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
                var r = new (str_cblmsvkategori2.recordType)({
                    'kd_kategori2': '',
                    'nama_kategori2': '-----'
                });
                str_cblmsvkategori2.insert(0, r);
            },
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

        var cblmsvkategori2 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 2',
        id: 'id_cblmsvkategori2',
        mode: 'local',
        store: str_cblmsvkategori2,
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
                var kd_hp_cbkategori1 = cblmsvkategori1.getValue();
                var kd_hp_cbkategori2 = this.getValue();
                cblmsvkategori3.setValue();
                cblmsvkategori3.store.proxy.conn.url = '<?= site_url("kategori4/get_kategori3") ?>/' + kd_hp_cbkategori1 +'/'+ kd_hp_cbkategori2;
                cblmsvkategori3.store.reload();          
            }
        }
    });
   
     // combobox kategori3

    var str_cblmsvkategori3 = new Ext.data.Store({
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
                var r = new (str_cblmsvkategori3.recordType)({
                    'kd_kategori3': '',
                    'nama_kategori3': '-----'
                });
                str_cblmsvkategori3.insert(0, r);
            },
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    var cblmsvkategori3 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 3',
        id: 'id_cblmsvkategori3',
        mode: 'local',
        store: str_cblmsvkategori3,
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
                var kd_hp_cbkategori1 = cblmsvkategori1.getValue();
                var kd_hp_cbkategori2 = cblmsvkategori2.getValue();
                var kd_hp_cbkategori3 = this.getValue();
                cblmsvkategori4.setValue();
                cblmsvkategori4.store.proxy.conn.url = '<?= site_url("master_barang/get_kategori4") ?>/' + kd_hp_cbkategori1 +'/'+ kd_hp_cbkategori2 +'/'+ kd_hp_cbkategori3;
                cblmsvkategori4.store.reload();     
            }
        }
    });
    
    // combobox kategori4

    var str_cblmsvkategori4 = new Ext.data.Store({
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
                var r = new (str_cblmsvkategori4.recordType)({
                    'kd_kategori4': '',
                    'nama_kategori4': '-----'
                });
                str_cblmsvkategori4.insert(0, r);
            },
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });
  
    var cblmsvkategori4 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 4',
        id: 'id_cblmsvkategori4',
        mode: 'local',
        store: str_cblmsvkategori4,
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

        var strcblmsvgudang = new Ext.data.ArrayStore({
        fields: ['kd_lokasi'],
        data : []
        });

        var strgridlmsvgudang = new Ext.data.Store({
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

        var searchgridlmsvgudang = new Ext.app.SearchField({
        store: strgridlmsvgudang,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE			
        },
        width: 350,
        id: 'id_searchgridlmsvgudang'
    });

        var gridlmsvgudang = new Ext.grid.GridPanel({
        store: strgridlmsvgudang,
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
            items: [searchgridlmsvgudang]
	    }),
            bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridlmsvgudang,
            displayInfo: true
        }),
		listeners: {
			'rowdblclick': function(){			
				var sm = this.getSelectionModel();
				var sel = sm.getSelections();
				if (sel.length > 0) {                   
                    Ext.getCmp('id_cbmsplgudang').setValue(sel[0].get('kd_lokasi'));    
                    menulmsvgudang.hide();
				}
			}
		}
    });

        var menulmsvgudang = new Ext.menu.Menu();
        menulmsvgudang.add(new Ext.Panel({
        title: 'Pilih Gudang',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridlmsvgudang],
        buttons: [{
            text: 'Close',
            handler: function(){
                menulmsvgudang.hide();
            }
        }]
    }));
    
    Ext.ux.TwinCombolmsvGudang = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            strgridlmsvgudang.load();
            menulmsvgudang.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
	
	menulmsvgudang.on('hide', function(){
		var sf = Ext.getCmp('id_searchgridlmsvgudang').getValue();
		if( sf != ''){
			Ext.getCmp('id_searchgridlmsvgudang').setValue('');
			searchgridlmsvgudang.onTrigger2Click();
		}
	});

        var cblmsvgudang = new Ext.ux.TwinCombolmsvGudang({
        fieldLabel: 'Gudang',
        id: 'id_cbmsplgudang',
        store: strcblmsvgudang,
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
    
    
        // CHECKBOX Sort Order
        var lmsvsortorder = new Ext.form.Checkbox({
        xtype: 'checkbox',
        //fieldLabel: 'Sort Order',
        boxLabel:'Descending',
        name:'sort_order',
        id:'id_lmsvsortorder',
        checked: false,
        inputValue: '1',
        autoLoad : true
        });
        
        var headerlmsvtanggal = {
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
                                                                            id: 'id_dari_tgl_msv',                
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
										id: 'id_smp_tgl_msv',										
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

    var headerlmsvkategori = {
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
									cblmsvkategori1,
                                                                        cblmsvkategori2,
                                                                        cblmsvkategori3,
                                                                        cblmsvkategori4,
                                                                        lmsv_cbsatuan
                                                                ]},
                                         {
						
				
                                                    columnWidth: .5,
					            layout: 'form',
					            border: false,
					            labelWidth: 100,
								defaults: { labelSeparator: ''},
								items:[	 
                                                                        cblmsvproduk,
                                                                        cblmsvsuplier,
                                                                        lmsv_cbstatus,
                                                                        cblmsvgudang,
                                                                        lmsv_cbukuran
								]
														
							
						
                                            }
                                          ]
					}
                                    ]
                        }
                        ]
			}]
      
    }
	   
    // HEADER Sort Order
    var headersortorder = {
        layout: 'column',
        border: false,
        items: [{
            columnWidth: .5,
            layout: 'form',
            border: false,
            //labelWidth: 100,
            defaults: {labelSeparator: ''},
            items: [{
                xtype: 'fieldset',
                title: 'Sort Order',
                autoHeight: true,
                items: [{
                    layout: 'column',
                    items: [{
                        columnWidth: .5,
                        layout: 'form',
                        border: false,
                       // labelWidth: 100,
                        defaults: {labelSeparator: ''},
                        items: [ lmsvsortorder 
							
                        ]
                    }]
                }]
            }]
        }]
    }

    var headerlmsv = {
            buttonAlign: 'left',
            layout: 'form',
            border: false,
            labelWidth: 100,
			defaults: { labelSeparator: ''},
                items: [headerlmsvtanggal,headerlmsvkategori,headersortorder
                      
                ],
            buttons: [{
            text: 'Print',
			formBind:true,
            handler: function(){				
				winlapmutasistokvalueprint.show();
				Ext.getDom('laporanmutasistokvalueprint').src = '<?= site_url("laporan_purchase_order/print_form") ?>';			
			}
        },{
			text: 'Cancel',
			handler: function(){
				clearlapmutasistokvalue();
			}
		}]
    };
    var winlapmutasistokvalueprint = new Ext.Window({
        id: 'id_winlapmutasistokvalueprint',
	title: 'Print Laporan Mutasi Stok dan Value',
        closeAction: 'hide',
        width: 900,
        height: 450,
        layout: 'fit',
        border: false,
        html:'<iframe style="width:100%;height:390px;" id="laporanmutasistokvalueprint" src=""></iframe>'
    });
        
    var laporanmutasistokvalue = new Ext.FormPanel({        
        id: 'rpt_mutasi_stok_value',		
        border: false,
        frame: true,
        monitorValid: true,
        labelWidth: 130,
        items: [{
                    bodyStyle: {
                        margin: '0px 0px 15px 0px'
                    },					
                    items: [headerlmsv]
                }
        ]
    });
	
	function clearlapmutasistokvalue(){
		Ext.getCmp('rpt_mutasi_stok_value').getForm().reset();
		
	}
</script>