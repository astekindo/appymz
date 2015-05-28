<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript"> 
       

        var strcblnssuplier = new Ext.data.ArrayStore({
        fields: ['kd_supplier'],
        data : []
        });

        var strgridlnssuplier = new Ext.data.Store({
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
	

        var searchgridlnssuplier = new Ext.app.SearchField({
        store: strgridlnssuplier,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE			
        },
        width: 350,
        id: 'id_searchgridlnssuplier'
    });


        var gridlnssuplier = new Ext.grid.GridPanel({
        store: strgridlnssuplier,
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
	        items: [searchgridlnssuplier]
	    }),
            bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridlnssuplier,
            displayInfo: true
        }),
		listeners: {
			'rowdblclick': function(){			
				var sm = this.getSelectionModel();
				var sel = sm.getSelections();
				if (sel.length > 0) {				
                    Ext.getCmp('id_cblnssuplier').setValue(sel[0].get('kd_supplier'));
                    menulnssuplier.hide();
				}
			}
		}
    });

        var menulnssuplier = new Ext.menu.Menu();
        menulnssuplier.add(new Ext.Panel({
        title: 'Pilih Supplier',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridlnssuplier],
        buttons: [{
            text: 'Close',
            handler: function(){
                menulnssuplier.hide();
            }
        }]
    }));
    
    Ext.ux.TwinCombolnsSuplier = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            strgridlnssuplier.load();
            menulnssuplier.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
	
        menulnssuplier.on('hide', function(){
            var sf = Ext.getCmp('id_searchgridlnssuplier').getValue();
            if( sf != ''){
                    Ext.getCmp('id_searchgridlnssuplier').setValue('');
                    searchgridlnssuplier.onTrigger2Click();
            }
	});

        var cblnssuplier = new Ext.ux.TwinCombolnsSuplier({
        fieldLabel: 'Supplier',
        id: 'id_cblnssuplier',
        store: strcblnssuplier,
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


    var strcblnsproduk = new Ext.data.ArrayStore({
        fields: ['kd_produk'],
        data : []
        });

    var strgridlnsproduk = new Ext.data.Store({
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

        var searchgridlnsproduk = new Ext.app.SearchField({
            store: strgridlnsproduk,
            params: {
            start: STARTPAGE,
            limit: ENDPAGE			
            },
            width: 350,
            id: 'id_searchgridlnsproduk'
        });

        var gridlnsproduk = new Ext.grid.GridPanel({
        store: strgridlnsproduk,
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
            items: [searchgridlnsproduk]
	    }),
            bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridlnsproduk,
            displayInfo: true
        }),
		listeners: {
			'rowdblclick': function(){			
				var sm = this.getSelectionModel();
				var sel = sm.getSelections();
				if (sel.length > 0) {
                    Ext.getCmp('id_cblnsproduk').setValue(sel[0].get('kd_produk'));
                    menulnsproduk.hide();
				}
			}
		}
    });
    Ext.ux.TwinCombolnsProduk = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
           strgridlnsproduk.load();
           menulnsproduk.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

        var menulnsproduk = new Ext.menu.Menu();
        menulnsproduk.add(new Ext.Panel({
        title: 'Pilih Produk',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridlnsproduk],
        buttons: [{
            text: 'Close',
            handler: function(){
                menulnsproduk.hide();
            }
        }]
    }));
    
   
	
	menulnsproduk.on('hide', function(){
		var sf = Ext.getCmp('id_searchgridlnsproduk').getValue();
		if( sf != ''){
			Ext.getCmp('id_searchgridlnsproduk').setValue('');
			searchgridlnsproduk.onTrigger2Click();
		}
	});

    var cblnsproduk = new Ext.ux.TwinCombolnsProduk({
        id: 'id_cblnsproduk',
        fieldLabel: 'Produk',
        store: strcblnsproduk,
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
    
      
   
     
     // combobox Status
     /*
        var str_lns_cbsatuan = new Ext.data.Store({
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
                var r = new (str_lns_cbsatuan.recordType)({
                    'kd_satuan': '',
                    'nm_satuan': '-----'
                });
                str_lns_cbsatuan.insert(0, r);
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
     var lns_cbstatus = new Ext.form.ComboBox({
        fieldLabel: 'Status ',
        id: 'id_lns_cbstatus',
      //  store: str_lns_cbsatuan,
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

  var str_cblnskategori1 = new Ext.data.Store({
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
                var r = new (str_cblnskategori1.recordType)({
                    'kd_kategori1': '',
                    'nama_kategori1': '-----'
                });
                str_cblnskategori1.insert(0, r);
            },
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });
    
        var cblnskategori1 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 1',
        id: 'id_cblnskategori1',
        store: str_cblnskategori1,
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
                var kdhp_cbkategori1 = cblnskategori1.getValue();
                cblnskategori2.store.proxy.conn.url = '<?= site_url("kategori3/get_kategori2") ?>/' + kdhp_cbkategori1;
                cblnskategori2.store.reload();            
            }
        }
    });
   
    // combobox kategori2

        var str_cblnskategori2 = new Ext.data.Store({
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
                var r = new (str_cblnskategori2.recordType)({
                    'kd_kategori2': '',
                    'nama_kategori2': '-----'
                });
                str_cblnskategori2.insert(0, r);
            },
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

        var cblnskategori2 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 2 ',
        id: 'id_cblnskategori2',
        mode: 'local',
        store: str_cblnskategori2,
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
                var kd_hp_cbkategori1 = cblnskategori1.getValue();
                var kd_hp_cbkategori2 = this.getValue();
                cblnskategori3.setValue();
                cblnskategori3.store.proxy.conn.url = '<?= site_url("kategori4/get_kategori3") ?>/' + kd_hp_cbkategori1 +'/'+ kd_hp_cbkategori2;
                cblnskategori3.store.reload();          
            }
        }
    });
   
     // combobox kategori3

    var str_cblnskategori3 = new Ext.data.Store({
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
                var r = new (str_cblnskategori3.recordType)({
                    'kd_kategori3': '',
                    'nama_kategori3': '-----'
                });
                str_cblnskategori3.insert(0, r);
            },
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    var cblnskategori3 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 3',
        id: 'id_cblnskategori3',
        mode: 'local',
        store: str_cblnskategori3,
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
                var kd_hp_cbkategori1 = cblnskategori1.getValue();
                var kd_hp_cbkategori2 = cblnskategori2.getValue();
                var kd_hp_cbkategori3 = this.getValue();
                cblnskategori4.setValue();
                cblnskategori4.store.proxy.conn.url = '<?= site_url("master_barang/get_kategori4") ?>/' + kd_hp_cbkategori1 +'/'+ kd_hp_cbkategori2 +'/'+ kd_hp_cbkategori3;
                cblnskategori4.store.reload();     
            }
        }
    });
    
    // combobox kategori4

    var str_cblnskategori4 = new Ext.data.Store({
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
                var r = new (str_cblnskategori4.recordType)({
                    'kd_kategori4': '',
                    'nama_kategori4': '-----'
                });
                str_cblnskategori4.insert(0, r);
            },
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });
  
    var cblnskategori4 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 4 ',
        id: 'id_cblnskategori4',
        mode: 'local',
        store: str_cblnskategori4,
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

       
        var headerlnstanggal = {
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

    var headerlnskategori = {
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
									cblnskategori1,
                                                                        cblnskategori2,
                                                                        cblnskategori3,
                                                                        cblnskategori4
                                                                ]},
                                         {
						
				
                                                    columnWidth: .5,
					            layout: 'form',
					            border: false,
					            labelWidth: 100,
								defaults: { labelSeparator: ''},
								items:[	 
                                                                        cblnsproduk,
                                                                        cblnssuplier,
                                                                        lns_cbstatus
								]
														
							
						
                                            }
                                          ]
					}
                                    ]
                        }
                        ]
			}]
      
    }
	   
	

    var headerlns = {
            buttonAlign: 'left',
            layout: 'form',
            border: false,
            labelWidth: 100,
			defaults: { labelSeparator: ''},
                items: [headerlnstanggal,headerlnskategori
                      
                ],
            buttons: [{
            text: 'Print',
			formBind:true,
            handler: function(){				
				winlaporannilaistokprint.show();
				Ext.getDom('laporannilaistokprint').src = '<?= site_url("laporan_purchase_order/print_form") ?>';			
			}
        },{
			text: 'Cancel',
			handler: function(){
				clearlaporannilaistok();
			}
		}]
    };
    var winlaporannilaistokprint = new Ext.Window({
        id: 'id_winlaporanmutasiprint',
	title: 'Print Laporan Nilai Stok',
        closeAction: 'hide',
        width: 900,
        height: 450,
        layout: 'fit',
        border: false,
        html:'<iframe style="width:100%;height:390px;" id="laporannilaistokprint" src=""></iframe>'
    });
        
    var laporannilaistok = new Ext.FormPanel({        
        id: 'rpt_nilai_stok',		
        border: false,
        frame: true,
        monitorValid: true,
        labelWidth: 130,
        items: [{
                    bodyStyle: {
                        margin: '0px 0px 15px 0px'
                    },					
                    items: [headerlns]
                }
        ]
    });
	
	function clearlaporannilaistok(){
		Ext.getCmp('rpt_nilai_stok').getForm().reset();
		
	}
</script>