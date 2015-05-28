<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">	
    /* START HISTORY */ 
    // cb kategori1
    var str_ios_cbkategori1 = new Ext.data.Store({
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
                var r = new (str_ios_cbkategori1.recordType)({
                    'kd_kategori1': '',
                    'nama_kategori1': '-----'
                });
                str_ios_cbkategori1.insert(0, r);
            },
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });
    
    var ios_cbkategori1 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 1 ',
        id: 'id_ios_cbkategori1',
        store: str_ios_cbkategori1,
        valueField: 'kd_kategori1',
        displayField: 'nama_kategori1',
        typeAhead: true,
        triggerAction: 'all',
        // allowBlank: false,
        editable: false,
        width: 170,
        anchor: '90%',
        hiddenName: 'nama_kategori1',
        emptyText: 'Pilih kategori 1',
        listeners: {
            'select': function(combo, records) {
                var kdhp_cbkategori1 = ios_cbkategori1.getValue();
                // hp_cbkategori2.setValue();
                ios_cbkategori2.store.proxy.conn.url = '<?= site_url("kategori3/get_kategori2") ?>/' + kdhp_cbkategori1;
                ios_cbkategori2.store.reload();            
            }
        }
    });
    
    // combobox kategori2
    var str_ios_cbkategori2 = new Ext.data.Store({
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
                var r = new (str_ios_cbkategori2.recordType)({
                    'kd_kategori2': '',
                    'nama_kategori2': '-----'
                });
                str_ios_cbkategori2.insert(0, r);
            },
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    var ios_cbkategori2 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 2 ',
        id: 'id_ios_cbkategori2',
        mode: 'local',
        store: str_ios_cbkategori2,
        valueField: 'kd_kategori2',
        displayField: 'nama_kategori2',
        typeAhead: true,
        triggerAction: 'all',
        // allowBlank: false,
        editable: false,
        width: 170,
        anchor: '90%',
        hiddenName: 'nama_kategori2',
        emptyText: 'Pilih kategori 2',
        listeners: {
            select: function(combo, records) {
                var kd_hp_cbkategori1 = ios_cbkategori1.getValue();
                var kd_hp_cbkategori2 = this.getValue();
                ios_cbkategori3.setValue();
                ios_cbkategori3.store.proxy.conn.url = '<?= site_url("kategori4/get_kategori3") ?>/' + kd_hp_cbkategori1 +'/'+ kd_hp_cbkategori2;
                ios_cbkategori3.store.reload();          
            }
        }
    });
    
     // combobox kategori3
    var str_ios_cbkategori3 = new Ext.data.Store({
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
                var r = new (str_ios_cbkategori3.recordType)({
                    'kd_kategori3': '',
                    'nama_kategori3': '-----'
                });
                str_ios_cbkategori3.insert(0, r);
            },
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });
	
    var ios_cbkategori3 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 3',
        id: 'id_ios_cbkategori3',
        mode: 'local',
        store: str_ios_cbkategori3,
        valueField: 'kd_kategori3',
        displayField: 'nama_kategori3',
        typeAhead: true,
        triggerAction: 'all',
        // allowBlank: false,
        editable: false,
        width: 170,
        anchor: '90%',
        hiddenName: 'nama_kategori3',
        emptyText: 'Pilih kategori 3',
        listeners: {
            select: function(combo, records) {
                var kd_hp_cbkategori1 = ios_cbkategori1.getValue();
                var kd_hp_cbkategori2 = ios_cbkategori2.getValue();
                var kd_hp_cbkategori3 = this.getValue();
                ios_cbkategori4.setValue();
                ios_cbkategori4.store.proxy.conn.url = '<?= site_url("master_barang/get_kategori4") ?>/' + kd_hp_cbkategori1 +'/'+ kd_hp_cbkategori2 +'/'+ kd_hp_cbkategori3;
                ios_cbkategori4.store.reload();     
            }
        }
    });
    
    // combobox kategori4
    var str_ios_cbkategori4 = new Ext.data.Store({
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
                var r = new (str_ios_cbkategori4.recordType)({
                    'kd_kategori4': '',
                    'nama_kategori4': '-----'
                });
                str_ios_cbkategori4.insert(0, r);
            },
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    var ios_cbkategori4 = new Ext.form.ComboBox({   
        fieldLabel: 'Kategori 4 ',
        id: 'id_ios_cbkategori4',
        mode: 'local',
        store: str_ios_cbkategori4,
        valueField: 'kd_kategori4',
        displayField: 'nama_kategori4',
        typeAhead: true,
        triggerAction: 'all',
        // allowBlank: false,
        editable: false,
        width: 170,
        anchor: '90%',
        hiddenName: 'nama_kategori4',
        emptyText: 'Pilih kategori 4'
    });
    
    // combobox Ukuran
	var str_ios_cbukuran = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_ukuran', 'nama_ukuran'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("master_barang/get_ukuran_produk") ?>',
            method: 'POST'
        }),
		listeners: {
            load: function() {
                var r = new (str_ios_cbukuran.recordType)({
                    'kd_ukuran': '',
                    'nama_ukuran': '-----'
                });
                str_ios_cbukuran.insert(0, r);
            },
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });
    
    var ios_cbukuran = new Ext.form.ComboBox({
        fieldLabel: 'Ukuran <span class="asterix">*</span>',
        id: 'id_ios_cbukuran',
        store: str_ios_cbukuran,
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
	var str_ios_cbsatuan = new Ext.data.Store({
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
                var r = new (str_ios_cbsatuan.recordType)({
                    'kd_satuan': '',
                    'nm_satuan': '-----'
                });
                str_ios_cbsatuan.insert(0, r);
            },
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });
    
    var ios_cbsatuan = new Ext.form.ComboBox({
        fieldLabel: 'Satuan <span class="asterix">*</span>',
        id: 'id_ios_cbsatuan',
        store: str_ios_cbsatuan,
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
    
    var striostok = new Ext.data.Store({
        autoSave:false,		
        reader: new Ext.data.JsonReader({
            fields: [
                {name: 'kd_lokasi_asal', allowBlank: false, type: 'text'},
                {name: 'nama_lokasi_asal', allowBlank: false, type: 'text'},
                {name: 'kd_supplier', allowBlank: false, type: 'text'},
                {name: 'kd_produk', allowBlank: false, type: 'text'},
                {name: 'kd_produk_lama', allowBlank: false, type: 'text'},
                {name: 'nama_produk', allowBlank: false, type: 'text'},
                {name: 'nm_satuan', allowBlank: false, type: 'text'},				
                {name: 'nama_supplier', allowBlank: false, type: 'text'},				
                {name: 'rp_het_harga_beli', allowBlank: false, type: 'int'},				
                {name: 'pct_margin', allowBlank: false, type: 'int'},				
                {name: 'rp_ongkos_kirim', allowBlank: false, type: 'int'},
                {name: 'rp_cogs', allowBlank: false, type: 'int'},
                {name: 'rp_cogs_in', allowBlank: false, type: 'int'},
                {name: 'rp_cogs_out', allowBlank: false, type: 'int'},
                {name: 'qty_in', allowBlank: false, type: 'int'},
        	    {name: 'qty_out', allowBlank: false, type: 'int'},
                {name: 'nama_lokasi', allowBlank: false, type: 'text'},
        	    {name: 'nama_ukuran', allowBlank: false, type: 'text'},
                {name: 'hrg_supplier', allowBlank: false, type: 'int'},
                {name: 'disk_supp1_op', allowBlank: false, type: 'text'},
                {name: 'disk_supp2_op', allowBlank: false, type: 'text'},
                {name: 'disk_supp3_op', allowBlank: false, type: 'text'},
                {name: 'disk_supp4_op', allowBlank: false, type: 'text'},
                {name: 'disk_dist1_op', allowBlank: false, type: 'text'},
                {name: 'disk_dist2_op', allowBlank: false, type: 'text'},
                {name: 'disk_dist3_op', allowBlank: false, type: 'text'},
                {name: 'disk_dist4_op', allowBlank: false, type: 'text'},
                {name: 'disk_supp1', allowBlank: false, type: 'float'},
                {name: 'disk_supp2', allowBlank: false, type: 'float'},
                {name: 'disk_supp3', allowBlank: false, type: 'float'},
                {name: 'disk_supp4', allowBlank: false, type: 'float'},
                {name: 'disk_amt_supp5', allowBlank: false, type: 'int'},
                {name: 'disk_dist1', allowBlank: false, type: 'float'},
                {name: 'disk_dist2', allowBlank: false, type: 'float'},
                {name: 'disk_dist3', allowBlank: false, type: 'float'},
                {name: 'disk_dist4', allowBlank: false, type: 'float'},
                {name: 'disk_amt_dist5', allowBlank: false, type: 'int'},
                {name: 'net_hrg_supplier_dist', allowBlank: false, type: 'int'},				
                {name: 'net_hrg_supplier_sup', allowBlank: false, type: 'int'},	
                {name: 'net_hrg_supplier_dist_inc', allowBlank: false, type: 'int'},				
                {name: 'net_hrg_supplier_sup_inc', allowBlank: false, type: 'int'},				
                {name: 'waktu_top', allowBlank: false, type: 'text'},
                {name: 'keterangan', allowBlank: false, type: 'text'},
                {name: 'qty_oh', allowBlank: false, type: 'int'}
        
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("in_out_stok/search_stok_produk") ?>',
            method: 'POST'
        }),
        writer: new Ext.data.JsonWriter(
        {
            encode: true,
            writeAllFields: true
        })
    });
	
	striostok.on('load',function(){
		
		striostok.setBaseParam('kd_kategori1',Ext.getCmp('id_ios_cbkategori1').getValue());
		striostok.setBaseParam('kd_kategori2',Ext.getCmp('id_ios_cbkategori2').getValue());
		striostok.setBaseParam('kd_kategori3',Ext.getCmp('id_ios_cbkategori3').getValue());
		striostok.setBaseParam('kd_kategori4',Ext.getCmp('id_ios_cbkategori4').getValue());
		striostok.setBaseParam('kd_ukuran',Ext.getCmp('id_ios_cbukuran').getValue());
                striostok.setBaseParam('kd_satuan',Ext.getCmp('id_ios_cbsatuan').getValue());
	})
    // combobox Ukuran
	var str_ios_cbukuran = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_ukuran', 'nama_ukuran'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("master_barang/get_ukuran_produk") ?>',
            method: 'POST'
        }),
		listeners: {
            load: function() {
                var r = new (str_ios_cbukuran.recordType)({
                    'kd_ukuran': '',
                    'nama_ukuran': '-----'
                });
                str_ios_cbukuran.insert(0, r);
            },
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });
    
    var ios_cbukuran = new Ext.form.ComboBox({
        fieldLabel: 'Ukuran ',
        id: 'id_ios_cbukuran',
        store: str_ios_cbukuran,
        valueField: 'kd_ukuran',
        displayField: 'nama_ukuran',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: true,
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_ukuran',
        emptyText: 'Pilih Ukuran'
       
    });
    
    // combobox Satuan
	var str_ios_cbsatuan = new Ext.data.Store({
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
                var r = new (str_ios_cbsatuan.recordType)({
                    'kd_satuan': '',
                    'nm_satuan': '-----'
                });
                str_ios_cbsatuan.insert(0, r);
            },
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });
    var ios_cbsatuan = new Ext.form.ComboBox({
        fieldLabel: 'Satuan',
        id: 'id_ios_cbsatuan',
        store: str_ios_cbsatuan,
        valueField: 'kd_satuan',
        displayField: 'nm_satuan',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: true,
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_satuan',
        emptyText: 'Pilih Satuan'
       
    });
    
     var headeriostok = {
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
                            xtype: 'textfield',
                            fieldLabel: 'No.Bukti',
                            name: 'no_bukti_ios',
                            readOnly:true,
                            fieldClass:'readonly-input',
                            id: 'id_no_bukti_ios',
                            anchor: '90%',
                            value:''
                        }]
                    },{
                        columnWidth: .5,
                        layout: 'form',
                        border: false,
                        labelWidth: 100,
                        defaults: { labelSeparator: ''},
                        items:[{
                            xtype: 'datefield',
                            fieldLabel: 'Tanggal',
                            name: 'tanggal',
                            format:'d-m-Y',
                            editable:false,
                            id: 'id_tgl_ios',
                            anchor: '90%',
                            listeners : {
                                render : function(datefield) {datefield.setValue(new Date());}
                            }
                        }]
                    }]
                }]
			},{
				xtype: 'fieldset',
				autoHeight: true, 
                title :'Filter',
                collapsed: false,
                collapsible: true,
				items: [{
                    layout: 'column',
                    items:[{
                        columnWidth: .5,
                        layout: 'form',
                        border: false,
                        labelWidth: 100,
                        defaults: { labelSeparator: ''},
                        items:[ios_cbkategori1,ios_cbkategori2,{
                            xtype: 'textarea',
                            style:'text-transform: uppercase',
                            fieldLabel: 'Kode Barang, Kode Barang Lama',
                            name: 'list',
                            id: 'eios_list',
                            anchor: '90%'
                        },{
                            xtype: 'label',
                            html: '*) Tidak Boleh Ada Spasi dan Enter.<br/>' +
                                '<div style="margin-top: 15px">Jika kode barang tidak bisa ditampilkan, periksa kembali hal-hal berikut:<br/>' +
                                '<ul>' +
                                '<li>1. Data barang di master barang belum lengkap.</li>' +
                                '<li>2. Status kode barang sudah recieve atau belum (di Lokasi per barang)</li>' +
                                '</ul></div>',
                            style: 'margin-left: 100px'
                        }]
                    },{
                        columnWidth: .5,
                        layout: 'form',
                        border: false,
                        labelWidth: 100,
                        defaults: { labelSeparator: ''},
                        items:[
                            ios_cbkategori3,
                            ios_cbkategori4,
                            ios_cbukuran,
                            ios_cbsatuan,
                            { xtype: 'label', text: ' '}
                        ]
                    }]
                }],buttons: [{
                    text: 'Filter',
                    formBind: true,
                    handler: function(){
        				striostok.load({params:{
                            kd_kategori1: Ext.getCmp('id_ios_cbkategori1').getValue(),
                            kd_kategori2: Ext.getCmp('id_ios_cbkategori2').getValue(),
                            kd_kategori3: Ext.getCmp('id_ios_cbkategori3').getValue(),
                            kd_kategori4: Ext.getCmp('id_ios_cbkategori4').getValue(),
                            kd_ukuran: Ext.getCmp('id_ios_cbukuran').getValue(),
                            kd_satuan: Ext.getCmp('id_ios_cbsatuan').getValue(),
                            no_bukti: Ext.getCmp('id_no_bukti_ios').getValue(),
                            list: Ext.getCmp('eios_list').getValue()
                        }});
                    }
                }]
			}]
        }]
    }
    
    // Twin Combo Lokasi //
      var strcbioslokasi = new Ext.data.ArrayStore({
        fields: ['sub'],
        data : []
        });

    var strgridioslokasi = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
        fields: ['kd_lokasi_asal', 'nama_lokasi_asal'],
        root: 'data',
        totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
        url: '<?= site_url("in_out_stok/search_lokasi") ?>',
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

        var searchgridcbioslokasi = new Ext.app.SearchField({
            store: strgridioslokasi,
            params: {
            start: STARTPAGE,
            limit: ENDPAGE			
            },
            width: 350,
            id: 'id_searchgridcbioslokasi'
        });

        var gridioslokasi = new Ext.grid.GridPanel({
        store: strgridioslokasi,
        stripeRows: true,
        frame: true,
        border:true,
        columns: [{
            header: 'Kode Lokasi',
            dataIndex: 'kd_lokasi_asal',
            width: 80,
            sortable: true			
            
        },{
            header: 'Nama Lokasi',
            dataIndex: 'nama_lokasi_asal',
            width: 300,
            sortable: true        
        }],
            tbar: new Ext.Toolbar({
            items: [searchgridcbioslokasi]
	    }),
            bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridioslokasi,
            displayInfo: true
        }),
		listeners: {
			'rowdblclick': function(){			
				var sm = this.getSelectionModel();
				var sel = sm.getSelections();
				if (sel.length > 0) {
                    Ext.getCmp('id_cbioslokasi').setValue(sel[0].get('kd_lokasi_asal'));
                    menucbioslokasi.hide();
				}
			}
		}
    });
    Ext.ux.TwinCombocbioslokasi = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            var kode = Ext.getCmp('eios_kd_produk').getValue();
            strgridioslokasi.load({params:{kd_produk: kode}});
           menucbioslokasi.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

        var menucbioslokasi = new Ext.menu.Menu();
        menucbioslokasi.add(new Ext.Panel({
        title: 'Pilih Lokasi',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridioslokasi],
        buttons: [{
            text: 'Close',
            handler: function(){
                menucbioslokasi.hide();
            }
        }]
    }));
    
   
	
	menucbioslokasi.on('hide', function(){
		var sf = Ext.getCmp('id_searchgridcbioslokasi').getValue();
		if( sf != ''){
			Ext.getCmp('id_searchgridcbioslokasi').setValue('');
			searchgridcbioslokasi.onTrigger2Click();
		}
	});

    var cbioslokasi = new Ext.ux.TwinCombocbioslokasi({
        id: 'id_cbioslokasi',
        store: strcbioslokasi,
        mode: 'local',
        anchor: '90%',
        valueField: 'sub',
        displayField: 'sub',
        typeAhead: true,
        triggerAction: 'all',
       
        editable: false,
        hiddenName: 'kd_sub',
        emptyText: 'Pilih Sub Lokasi' 
    });
     // End Twin Combo Lokasi //
     
    var searchgridiostok = new Ext.app.SearchField({
        store: striostok,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE			
        },
        width: 350,
        id: 'id_searchgridiostok',
		emptyText: 'Kode Barang, Kode Barang Lama, Nama Barang'
    }); 
    
    
    
    var editoriosgrid = new Ext.ux.grid.RowEditor({
        saveText: 'Update'		
    });
    
    var gridios = new Ext.grid.GridPanel({
        store: striostok,
        stripeRows: true,
        height: 350,
        loadMask: true,
        frame: true,
        border:true,
        layout: 'fit',
        plugins: [editoriosgrid],
        columns: [
            { header: 'Edited',
                dataIndex: 'edited',
                width: 50,
                sortable: true,
                editor: {
                    xtype: 'combo',
                    store: new Ext.data.JsonStore({
                        fields : ['name'],
                        data   : [{name : 'Y'},{name : 'No'}]
                    }),
                    id: 'ios_edited',
                    mode: 'local',
                    name: 'edited',
                    value: 'Y',
                    width: 50,
                    editable: false,
                    hiddenName: 'edited',
                    valueField: 'name',
                    displayField: 'name',
                    triggerAction: 'all',
                    forceSelection: true
			}
            },{
                header: 'Kode Produk',
                dataIndex: 'kd_produk',
                width: 100,
                sortable: true,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'eios_kd_produk'
                })
            },{
                header: 'Kode Produk Lama',
                dataIndex: 'kd_produk_lama',
                width: 100,
                sortable: true
            },{
                header: 'Nama Barang',
                dataIndex: 'nama_produk',
                width: 300,
                sortable: true
            },{
                header: 'Satuan',
                dataIndex: 'nm_satuan',
                width: 80
            },{
                header: 'Ukuran',
                dataIndex: 'nama_ukuran',
                width: 80
            },{
                header: 'Stok OH',
                dataIndex: 'qty_oh',
                width: 80,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'eios_qty_oh'
                })
            },{
                header: 'COGS',
                dataIndex: 'rp_cogs',
                width: 80,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'eios_rp_cogs'
                })
            },{
                header: 'Kd Lokasi Asal',
                dataIndex: 'kd_lokasi_asal',
                width: 100
            },{
                header: 'Lokasi Asal',
                dataIndex: 'nama_lokasi_asal',
                width: 120
            },{
                header: 'Lokasi Tujuan',
                dataIndex: 'nama_lokasi',
                width: 150,
//                id: 'eios_nama_lokasi',
                editor :cbioslokasi
            },{
                header: 'Qty In',
                dataIndex: 'qty_in',
                width: 80,
                editor: {
                    xtype: 'numberfield',
                    id: 'eios_qty_in',
                    readOnly: false,
                    listeners:{
                        'render': function(c) {
                            c.getEl().on('keyup', function() {
                                if (Ext.getCmp('id_cbioslokasi').getValue() === '') {
                                    Ext.Msg.show({
                                        title: 'Error',
                                        msg: 'Lokasi Tujuan harus ditentukan',
                                        modal: true,
                                        icon: Ext.Msg.ERROR,
                                        buttons: Ext.Msg.OK
                                    });
                                    Ext.getCmp('eios_qty_in').setValue(0);
                                    return;
                                }
                                set_edited();
                                Ext.getCmp('eios_qty_out').setValue(0);
                                var rp_cogs_out = parseInt(this.getValue()) * parseInt(Ext.getCmp('eios_rp_cogs').getValue());
                                Ext.getCmp('eios_rp_cogs_in').setValue(rp_cogs_out);
                                Ext.getCmp('eios_rp_cogs_out').setValue(0);
                            }, c);
                        }	  
                    }
                }
            },{
                header: 'COGS IN',
                dataIndex: 'rp_cogs_in',
                width: 80,
                editor: new Ext.form.TextField({ readOnly: true, id: 'eios_rp_cogs_in'})
            },{
                header: 'Qty Out',
                dataIndex: 'qty_out',
                width: 80,
                editor: {
                    xtype: 'numberfield',
                    id: 'eios_qty_out',
                    readOnly: false,
                    listeners:{
                        'render': function(c) {
                            c.getEl().on('keyup', function() {
                            
                            if (parseInt(this.getValue()) > parseInt(Ext.getCmp('eios_qty_oh').getValue())) {
                                Ext.Msg.show({
                                    title: 'Error',
                                    msg: 'Qty Out tidak boleh lebih besar dari Stok',
                                    modal: true,
                                    icon: Ext.Msg.ERROR,
                                    buttons: Ext.Msg.OK
                                });
                                Ext.getCmp('eios_qty_out').setValue(0);
                                return;
                            }
                                set_edited();
                                Ext.getCmp('eios_qty_in').setValue(0);
                                console.log(parseInt(this.getValue()));   
                                console.log(parseInt(Ext.getCmp('eios_rp_cogs').getValue()));
                                var rp_cogs_out = parseInt(this.getValue()) * parseInt(Ext.getCmp('eios_rp_cogs').getValue());
                                Ext.getCmp('eios_rp_cogs_out').setValue(rp_cogs_out);
                                Ext.getCmp('eios_rp_cogs_in').setValue(0);
                                Ext.getCmp('id_cbioslokasi').setValue('');
//                                Ext.getCmp('eios_nama_lokasi').setValue('');
                            }, c);
                        }
					  
                    }
                }
            },{
                header: 'COGS OUT',
                dataIndex: 'rp_cogs_out',
                id: 'eios_rp_cogs_out',
                width: 80,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'eios_rp_cogs_out'
                })
            },{
                header: 'Keterangan',
                dataIndex: 'keterangan',
                width: 150,
                editor: {
                    xtype: 'textfield',
                    id: 'eios_ket',
                    readOnly: false
                    
                }
            }],
        tbar: [searchgridiostok],
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: striostok,
            displayInfo: true
         })
    });

    var win_cetak_ios = new Ext.Window({
        id: 'id_win_cetak_ios',
        title: 'Print Bukti Mutasi Keluar',
        closeAction: 'hide',
        width: 900,
        height: 450,
        layout: 'fit',
        border: false,
        html:'<iframe style="width:100%;height:100%;" id="win_cetak_ios_frame" src=""></iframe>'
    });

    var ios_panel = new Ext.FormPanel({
        id: 'in_out_stok',
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
                items: [headeriostok]
            },
            gridios
        ],
        buttons: [{
                text: 'Save',
                //formBind: true,
                handler: function(){
                
                    var detailiostok = new Array();              
                    striostok.each(function(node){
                        detailiostok.push(node.data)
                    });
                    Ext.getCmp('in_out_stok').getForm().submit({
                        url: '<?= site_url("in_out_stok/update_row") ?>',
                        scope: this,
                        params: {
                            detail: Ext.util.JSON.encode(detailiostok)
                        },
                        waitMsg: 'Saving Data...',
                        success: function(form, action){
                            var r = Ext.util.JSON.decode(action.response.responseText);
                            Ext.Msg.show({
                                title: 'Success',
                                msg: 'Form submitted successfully',
                                modal: true,
                                icon: Ext.Msg.INFO,
                                buttons: Ext.Msg.OK,
                                fn: function(btn){
                                    win_cetak_ios.show();
                                    Ext.getDom('win_cetak_ios_frame').src = r.printUrl;
                                }
                            });
                        cleariostok();
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
                    cleariostok();
                }
            }]
    });
    
   
    function cleariostok(){
        Ext.getCmp('in_out_stok').getForm().reset();
        
        striostok.removeAll();
    }
    
    
    function set_edited() {
        Ext.getCmp('ios_edited').setValue('Y');
    };
</script>
