<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript"> 
        var strcblp4suplier = new Ext.data.ArrayStore({
        fields: ['kd_supplier'],
        data : []
        });
	
       var strgridlp4suplier = new Ext.data.Store({
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
                if (err.errMsg == 'Session Expired') { session_expired(err.errMsg);}
            }
        }
    });
	
        var searchgridlp4suplier = new Ext.app.SearchField({
        store: strgridlp4suplier,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE			
        },
        width: 350,
        id: 'id_searchgridlp4suplier'
    });

        var gridlp4suplier = new Ext.grid.GridPanel({
        store: strgridlp4suplier,
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
	        items: [searchgridlp4suplier]
	    }),
            bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridlp4suplier,
            displayInfo: true
        }),
		listeners: {
			'rowdblclick': function(){			
				var sm = this.getSelectionModel();
				var sel = sm.getSelections();
				if (sel.length > 0) {				
                    Ext.getCmp('id_cblp4suplier').setValue(sel[0].get('kd_supplier'));
                    menulp4suplier.hide();
				}
			}
		}
    });

        var menulp4suplier = new Ext.menu.Menu();
        menulp4suplier.add(new Ext.Panel({
        title: 'Pilih Supplier',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridlp4suplier],
        buttons: [{
            text: 'Close',
            handler: function(){
                menulp4suplier.hide();
            }
        }]
    }));
    
    Ext.ux.TwinComboSuplier = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
			//load store grid
            strgridlp4suplier.load();
            menulp4suplier.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
	
	menulp4suplier.on('hide', function(){
		var sf = Ext.getCmp('id_searchgridlp4suplier').getValue();
		if( sf != ''){
			Ext.getCmp('id_searchgridlp4suplier').setValue('');
			searchgridlp4suplier.onTrigger2Click();
		}
	});
	
    var cblp4suplier = new Ext.ux.TwinComboSuplier({
        fieldLabel: 'Supplier',
        id: 'id_cblp4suplier',
        store: strcblp4suplier,
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

    var strcblpenjualan4produk = new Ext.data.ArrayStore({
        fields: ['kd_produk'],
        data : []
    });

    var strgridlpenjualan4produk = new Ext.data.Store({
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
                if (err.errMsg == 'Session Expired') {session_expired(err.errMsg);}
            }
        }
    });

    var searchgridlpenjualan4produk = new Ext.app.SearchField({
        store: strgridlpenjualan4produk,
        params: {start: STARTPAGE,limit: ENDPAGE},
        width: 350,
        id: 'id_searchgridlpenjualan4produk'
    });

    var gridlpenjualan4produk = new Ext.grid.GridPanel({
        store: strgridlpenjualan4produk,
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
            items: [searchgridlpenjualan4produk]
	    }),
            bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridlpenjualan4produk,
            displayInfo: true
        }),
		listeners: {
			'rowdblclick': function(){			
				var sm = this.getSelectionModel();
				var sel = sm.getSelections();
				if (sel.length > 0) {				
                    Ext.getCmp('id_cblp4_kd_produk').setValue(sel[0].get('kd_produk'));
                    menulpenjualan4produk.hide();
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
			//load store grid
           strgridlpenjualan4produk.load();
            menulpenjualan4produk.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    var menulpenjualan4produk = new Ext.menu.Menu();
        menulpenjualan4produk.add(new Ext.Panel({
        title: 'Pilih Produk',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridlpenjualan4produk],
        buttons: [{
            text: 'Close',
            handler: function(){
                menulpenjualan4produk.hide();
            }
        }]
    }));

	menulpenjualan4produk.on('hide', function(){
		var sf = Ext.getCmp('id_searchgridlpenjualan4produk').getValue();
		if( sf != ''){
			Ext.getCmp('id_searchgridlpenjualan4produk').setValue('');
			searchgridlpenjualan4produk.onTrigger2Click();
		}
	});

    var cblpenjualan4produk = new Ext.ux.TwinCombolpoProduk({
        id: 'id_cblp4_kd_produk',
        fieldLabel: 'Produk',
        store: strcblpenjualan4produk,
        mode: 'local',
        anchor: '90%',
        valueField: 'kd_produk',
        displayField: 'kd_produk',
        typeAhead: true,
        triggerAction: 'all',
        editable: false,
        hiddenName: 'kd_produk',
        emptyText: 'Pilih Produk' 
    });
    
       // combobox Ukuran
    var str_lp4_cbukuran = new Ext.data.Store({
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
                var r = new (str_lp4_cbukuran.recordType)({
                    'kd_ukuran': '',
                    'nama_ukuran': '-----'
                });
                str_lp4_cbukuran.insert(0, r);
            },
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });
     var lp4_cbukuran = new Ext.form.ComboBox({
        fieldLabel: 'Ukuran ',
        id: 'id_lp4_cbukuran',
        store: str_lp4_cbukuran,
        valueField: 'kd_ukuran',
        displayField: 'nama_ukuran',
        typeAhead: true,
        triggerAction: 'all',
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_ukuran',
        emptyText: 'Pilih Ukuran'
       
    });
    
   // combobox Satuan
        var str_lp4_cbsatuan = new Ext.data.Store({
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
                var r = new (str_lp4_cbsatuan.recordType)({
                    'kd_ukuran': '',
                    'nama_ukuran': '-----'
                });
                str_lp4_cbsatuan.insert(0, r);
            },
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

     var lp4_cbsatuan = new Ext.form.ComboBox({
        fieldLabel: 'Satuan ',
        id: 'id_lp4_cbsatuan',
        store: str_lp4_cbsatuan,
        valueField: 'kd_satuan',
        displayField: 'nm_satuan',
        typeAhead: true,
        triggerAction: 'all',
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_satuan',
        emptyText: 'Pilih Satuan'
       
    });


// cb kategori1
  var str_cblp4kategori1 = new Ext.data.Store({
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
                var r = new (str_cblp4kategori1.recordType)({
                    'kd_kategori1': '',
                    'nama_kategori1': '-----'
                });
                str_cblp4kategori1.insert(0, r);
            },
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });
    
        var cblp4kategori1 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 1 ',
        id: 'id_cblp4kategori1',
        store: str_cblp4kategori1,
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
                var kdhp_cbkategori1 = cblp4kategori1.getValue();
                cblp4kategori2.store.proxy.conn.url = '<?= site_url("kategori3/get_kategori2") ?>/' + kdhp_cbkategori1;
                cblp4kategori2.store.reload();            
            }
        }
    });
   
    // combobox kategori2

        var str_cblp4kategori2 = new Ext.data.Store({
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
                var r = new (str_cblp4kategori2.recordType)({
                    'kd_kategori2': '',
                    'nama_kategori2': '-----'
                });
                str_cblp4kategori2.insert(0, r);
            },
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

        var cblp4kategori2 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 2 ',
        id: 'id_cblp4kategori2',
        mode: 'local',
        store: str_cblp4kategori2,
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
                var kd_hp_cbkategori1 = cblp4kategori1.getValue();
                var kd_hp_cbkategori2 = this.getValue();
                cblp4kategori3.setValue();
                cblp4kategori3.store.proxy.conn.url = '<?= site_url("kategori4/get_kategori3") ?>/' + kd_hp_cbkategori1 +'/'+ kd_hp_cbkategori2;
                cblp4kategori3.store.reload();          
            }
        }
    });
   
     // combobox kategori3

    var str_cblp4kategori3 = new Ext.data.Store({
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
                var r = new (str_cblp4kategori3.recordType)({
                    'kd_kategori3': '',
                    'nama_kategori3': '-----'
                });
                str_cblp4kategori3.insert(0, r);
            },
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    var cblp4kategori3 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 3',
        id: 'id_cblp4kategori3',
        mode: 'local',
        store: str_cblp4kategori3,
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
                var kd_hp_cbkategori1 = cblp4kategori1.getValue();
                var kd_hp_cbkategori2 = cblp4kategori2.getValue();
                var kd_hp_cbkategori3 = this.getValue();
                cblp4kategori4.setValue();
                cblp4kategori4.store.proxy.conn.url = '<?= site_url("master_barang/get_kategori4") ?>/' + kd_hp_cbkategori1 +'/'+ kd_hp_cbkategori2 +'/'+ kd_hp_cbkategori3;
                cblp4kategori4.store.reload();     
            }
        }
    });
    
    // combobox kategori4

    var str_cblp4kategori4 = new Ext.data.Store({
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
                var r = new (str_cblp4kategori4.recordType)({
                    'kd_kategori4': '',
                    'nama_kategori4': '-----'
                });
                str_cblp4kategori4.insert(0, r);
            },
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });
  
    var cblp4kategori4 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 4',
        id: 'id_cblp4kategori4',
        mode: 'local',
        store: str_cblp4kategori4,
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

      
        var headerlp4tanggal = {
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
				items: [{
                    layout: 'column',
                    items:[{
                        columnWidth: .5,
                        layout: 'form',
                        border: false,
                        labelWidth: 100,
                        defaults: { labelSeparator: ''},
                        items:[{
                            xtype: 'datefield',
                            fieldLabel: 'Dari Tgl <span class="asterix">*</span>',
                            name: 'dari_tgl',
                            allowBlank:false,
                            format:'d-m-Y',
                            editable:false,
                            id: 'id_dari_tgl',
                            anchor: '90%',
                            value: ''
                        }]
                    },{
                        columnWidth: .5,
                        layout: 'form',
                        border: false,
                        labelWidth: 100,
                        defaults: { labelSeparator: ''},
                        items:[{
                            xtype: 'datefield',
                            fieldLabel: 'Sampai Tgl',
                            name: 'sampai_tgl',
                            allowBlank:false,
                            editable:false,
                            format:'d-m-Y',
                            id: 'id_smp_tgl',
                            anchor: '90%',
                            value: ''
                        }]
                    }]
                }]
			}]
        }]
    }

    var headerlp4kategori = {
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
				items: [{
                    layout: 'column',
                    items:[{
                        columnWidth: .5,
                        layout: 'form',
                        border: false,
                        labelWidth: 100,
                        defaults: { labelSeparator: ''},
                        items:[cblp4kategori1,cblp4kategori2,cblp4kategori3,cblp4kategori4]
                    },{
                        columnWidth: .5,
                        layout: 'form',
                        border: false,
                        labelWidth: 100,
                        defaults: { labelSeparator: ''},
                        items:[cblpenjualan4produk,cblp4suplier,lp4_cbukuran,lp4_cbsatuan]
                    }]
                }]
            }]
        }]
      
    }
	   
	

    var headerlaporanpenjualan4 = {
        buttonAlign: 'left',
        layout: 'form',
        border: false,
        labelWidth: 100,
        defaults: { labelSeparator: ''},
        items: [headerlp4tanggal,headerlp4kategori]
    };

    var winlaporanpenjualan4print = new Ext.Window({
        id: 'id_winlaporanpenjualan4print',
	    Title: 'Print Laporan Penjualan 4',
        closeAction: 'hide',
        width: 900,
        height: 450,
        layout: 'fit',
        border: false,
        html:'<iframe style="width:100%;height:390px;" id="laporanpenjualan4print" src=""></iframe>'
    });

    var laporanpenjualan4 = new Ext.FormPanel({        
        id: 'rpt_penjualan4',
        border: false,
        frame: true,
        monitorValid: true,
        labelWidth: 130,
        items: [{
            bodyStyle: {margin: '0px 0px 15px 0px'},
            items: [headerlaporanpenjualan4]
        }],
        buttons: [{
            text: 'Print',
            formBind:true,
            handler: function () {
                Ext.getCmp('rpt_penjualan4').getForm().submit({
                    url: '<?= site_url("laporan_penjualan4/print_pdf") ?>',
                    scope: this,
                    waitMsg: 'Preparing Data...',
                    success: function(form, action){
                        var r = Ext.util.JSON.decode(action.response.responseText);
                        Ext.Msg.show({
                            title: 'Success',
                            msg: r.successMsg,
                            modal: true,
                            icon: Ext.Msg.INFO,
                            buttons: Ext.Msg.OK,
                            fn: function(btn){
                                winlaporanpenjualan4print.show();
                                Ext.getDom('laporanpenjualan4print').src = r.printUrl;
                            }
                        });

                        clearlaporanpenjualan4();
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
            text: 'Cancel',
            handler: function(){
                clearlaporanpenjualan4();
            }
        }]
    });
	
    function clearlaporanpenjualan4(){
        Ext.getCmp('rpt_penjualan4').getForm().reset();
    }
</script>