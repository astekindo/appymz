<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript"> 

    var strcbljualpersjproduk = new Ext.data.ArrayStore({
        fields: ['kd_produk'],
        data : []
        });

    var strgridljualpersjproduk = new Ext.data.Store({
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

        var searchgridljualpersjproduk = new Ext.app.SearchField({
            store: strgridljualpersjproduk,
            params: {
            start: STARTPAGE,
            limit: ENDPAGE			
            },
            width: 350,
            id: 'id_searchgridljualpersjproduk'
        });


        var gridljualpersjproduk = new Ext.grid.GridPanel({
        store: strgridljualpersjproduk,
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
            items: [searchgridljualpersjproduk]
	    }),
            bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridljualpersjproduk,
            displayInfo: true
        }),
		listeners: {
			'rowdblclick': function(){			
				var sm = this.getSelectionModel();
				var sel = sm.getSelections();
				if (sel.length > 0) {	           
                    Ext.getCmp('id_cbljualpersjproduk').setValue(sel[0].get('kd_produk'));      
                    menuljualpersjproduk.hide();
				}
			}
		}
    });
    Ext.ux.TwinCombolpoProduk = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
        strgridljualpersjproduk.load();
        menuljualpersjproduk.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
    
        var menuljualpersjproduk = new Ext.menu.Menu();
        menuljualpersjproduk.add(new Ext.Panel({
        title: 'Pilih Produk',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridljualpersjproduk],
        buttons: [{
            text: 'Close',
            handler: function(){
            menuljualpersjproduk.hide();
            }
        }]
    }));
    
   
	
	menuljualpersjproduk.on('hide', function(){
		var sf = Ext.getCmp('id_searchgridljualpersjproduk').getValue();
		if( sf != ''){
			Ext.getCmp('id_searchgridljualpersjproduk').setValue('');
			searchgridljualpersjproduk.onTrigger2Click();
		}
	});

    var cbljualpersjproduk = new Ext.ux.TwinCombolpoProduk({
        id: 'id_cbljualpersjproduk',
        fieldLabel: 'Produk',
        store: strcbljualpersjproduk,
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
    
      

// cb kategori1

   var str_cbljualpersjkategori1 = new Ext.data.Store({
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
                var r = new (str_cbljualpersjkategori1.recordType)({
                    'kd_kategori1': '',
                    'nama_kategori1': '-----'
                });
                str_cbljualpersjkategori1.insert(0, r);
            },
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });
    

        var cbljualpersjkategori1 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 1',
        id: 'id_cbljualpersjkategori1',
        store: str_cbljualpersjkategori1,
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
                var kdhp_cbkategori1 = cbljualpersjkategori1.getValue();
                cbljualpersjkategori2.store.proxy.conn.url = '<?= site_url("kategori3/get_kategori2") ?>/' + kdhp_cbkategori1;
                cbljualpersjkategori2.store.reload();            
            }
        }
    });
   
    // combobox kategori2

        var str_cbljualpersjkategori2 = new Ext.data.Store({
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
                var r = new (str_cbljualpersjkategori2.recordType)({
                    'kd_kategori2': '',
                    'nama_kategori2': '-----'
                });
                str_cbljualpersjkategori2.insert(0, r);
            },
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

        var cbljualpersjkategori2 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 2 ',
        id: 'id_cbljualpersjkategori2',
        mode: 'local',
        store: str_cbljualpersjkategori2,
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
                var kd_hp_cbkategori1 = cbljualpersjkategori1.getValue();
                var kd_hp_cbkategori2 = this.getValue();
                cbljualpersjkategori3.setValue();
                cbljualpersjkategori3.store.proxy.conn.url = '<?= site_url("kategori4/get_kategori3") ?>/' + kd_hp_cbkategori1 +'/'+ kd_hp_cbkategori2;
                cbljualpersjkategori3.store.reload();          
            }
        }
    });
   
     // combobox kategori3

    var str_cbljualpersjkategori3 = new Ext.data.Store({
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
                var r = new (str_cbljualpersjkategori3.recordType)({
                    'kd_kategori3': '',
                    'nama_kategori3': '-----'
                });
                str_cbljualpersjkategori3.insert(0, r);
            },
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

     var cbljualpersjkategori3 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 3 ',
        id: 'id_cbljualpersjkategori3',
        mode: 'local',
        store: str_cbljualpersjkategori3,
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
                var kd_hp_cbkategori1 = cbljualpersjkategori1.getValue();
                var kd_hp_cbkategori2 = cbljualpersjkategori2.getValue();
                var kd_hp_cbkategori3 = this.getValue();
                cbljualpersjkategori4.setValue();
                cbljualpersjkategori4.store.proxy.conn.url = '<?= site_url("master_barang/get_kategori4") ?>/' + kd_hp_cbkategori1 +'/'+ kd_hp_cbkategori2 +'/'+ kd_hp_cbkategori3;
                cbljualpersjkategori4.store.reload();     
            }
        }
    });
    
    // combobox kategori4

    var str_cbljualpersjkategori4 = new Ext.data.Store({
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
                var r = new (str_cbljualpersjkategori4.recordType)({
                    'kd_kategori4': '',
                    'nama_kategori4': '-----'
                });
                str_cbljualpersjkategori4.insert(0, r);
            },
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });
  
    var cbljualpersjkategori4 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 4 ',
        id: 'id_cbljualpersjkategori4',
        mode: 'local',
        store: str_cbljualpersjkategori4,
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


        var headerljualpersjtanggal = {
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
                                                                            fieldLabel: 'Dari Tgl',
                                                                            name: 'dari_tgl',				
                                                                            allowBlank:false,   
                                                                            format:'d-m-Y',  
                                                                            editable:false,           
                                                                            id: 'id_dari_tgl_jual_persj',                
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
										id: 'id_smp_tgl_jual_persj',										
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

     var headerljualpersjkategori = {
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
								items:[	cbljualpersjproduk,								
									cbljualpersjkategori1,
                                                                        cbljualpersjkategori2,
                                                                        cbljualpersjkategori3,
                                                                        cbljualpersjkategori4
                                                                ]}
                                          ]
					}
                                    ]
                        }
                        ]
			}]
      
    }
	   
	

    var headerljualpersj = {
            buttonAlign: 'left',
            layout: 'form',
            border: false,
            labelWidth: 100,
			defaults: { labelSeparator: ''},
                items: [headerljualpersjtanggal,headerljualpersjkategori
                      
                ],
            buttons: [{
            text: 'Print',
			formBind:true,
            handler: function(){				
				winljualpersjprint.show();
				Ext.getDom('lapjualpersjprint').src = '<?= site_url("laporan_penjualan_per_sj/print_form") ?>';			
			}
        },{
			text: 'Cancel',
			handler: function(){
				clearlapjualpersj();
			}
		}]
    };
        var winljualpersjprint = new Ext.Window({
        id: 'id_winljualpersjprint',
	Title: 'Laporan Penjualan per Surat Jalan',
        closeAction: 'hide',
        width: 900,
        height: 450,
        layout: 'fit',
        border: false,
        html:'<iframe style="width:100%;height:390px;" id="lapjualpersjprint" src=""></iframe>'
    });
       

    var lapjualpersj = new Ext.FormPanel({        
        id: 'rpt_penjualan_per_sj',		
        border: false,
        frame: true,
        monitorValid: true,
        labelWidth: 130,
        items: [{
                    bodyStyle: {
                        margin: '0px 0px 15px 0px'
                    },					
                    items: [headerljualpersj]
                }
        ]
    });
	
	function clearlapjualpersj(){
		Ext.getCmp('rpt_penjualan_per_sj').getForm().reset();
		
	}
</script>