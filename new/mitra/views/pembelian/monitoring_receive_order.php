<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">    
	
	// START COMBOBOX SUPPLIER
	var strcbmrosuplier = new Ext.data.ArrayStore({
        fields: ['nama_supplier'],
        data : []
    });
	var strgridmrosuplier = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_supplier', 'nama_supplier'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("laporan_penerimaan_barang/search_supplier") ?>',
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
	var searchgridmrosuplier = new Ext.app.SearchField({
        store: strgridmrosuplier,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE			
        },
        width: 350,
		id: 'id_searchgridmrosuplier'
    });
	var gridmrosuplier = new Ext.grid.GridPanel({
        store: strgridmrosuplier,
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
        }],
		tbar: new Ext.Toolbar({
	        items: [searchgridmrosuplier]
	    }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridmrosuplier,
            displayInfo: true
        }),
		listeners: {
			'rowdblclick': function(){			
				var sm = this.getSelectionModel();
				var sel = sm.getSelections();
				if (sel.length > 0) {				
                    Ext.getCmp('id_cbmrosuplier').setValue(sel[0].get('kd_supplier'));
		    Ext.getCmp('mro_nama_supplier').setValue(sel[0].get('nama_supplier'));
                    // strlaporanpenerimaanbarang.removeAll();       
					menumrosuplier.hide();
				}
			}
		}
    });
	var menumrosuplier = new Ext.menu.Menu();
    menumrosuplier.add(new Ext.Panel({
        title: 'Pilih Supplier',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridmrosuplier],
        buttons: [{
            text: 'Close',
            handler: function(){
                menumrosuplier.hide();
            }
        }]
    }));
    Ext.ux.TwinComboSuppliermro = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
			//load store grid
            strgridmrosuplier.load();
            menumrosuplier.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
	menumrosuplier.on('hide', function(){
		var sf = Ext.getCmp('id_searchgridmrosuplier').getValue();
		if( sf != ''){
			Ext.getCmp('id_searchgridmrosuplier').setValue('');
			searchgridmrosuplier.onTrigger2Click();
		}
	});
	var cbmrosuplier = new Ext.ux.TwinComboSuppliermro({
            fieldLabel: 'Kode Supplier',
            id: 'id_cbmrosuplier',
            store: strcbmrosuplier,
            mode: 'local',
            valueField: 'nama_supplier',
            displayField: 'nama_supplier',
            typeAhead: true,
            triggerAction: 'all',
            // allowBlank: false,
            editable: false,
            anchor: '90%',
            hiddenName: 'nama_supplier',
            emptyText: 'Pilih Kode Supplier'
    });
    
    // combobox lokasi
	var strcblokasimro = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_lokasi', 'nama_lokasi'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("blok_lokasi/get_all") ?>',
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
    var cblokasimro = new Ext.form.ComboBox({
        fieldLabel: 'Nama Lokasi <span class="asterix">*</span>',
        id: 'id_lokasi_mro',
        store: strcblokasimro,
        valueField: 'kd_lokasi',
        displayField: 'nama_lokasi',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_lokasi',
        emptyText: 'Pilih Lokasi',
        listeners: {
			expand: function(){
					strinitstokopname.load({
						params: {
							kdLokasi: '',
							kdBlok: '',
							kdSubBlok: ''
						}
					})
			},
            select: function(combo, records) {
                var kd_cblokasiopname = this.getValue();
                cbblokmro.setValue();
                cbblokmro.store.proxy.conn.url = '<?= site_url("sub_blok_lokasi/get_blok") ?>/' + kd_cblokasiopname;
                cbblokmro.store.reload();
            }
        }
    });

    // combobox blok
    var strcbblokmro = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_blok', 'nama_blok'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("sub_blok_lokasi/get_blok") ?>',
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
	var cbblokmro = new Ext.form.ComboBox({
        fieldLabel: 'Nama Blok <span class="asterix">*</span>',
        id: 'id_blok_mro',
        mode: 'local',
        store: strcbblokmro,
        valueField: 'kd_blok',
        displayField: 'nama_blok',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_blok',
        emptyText: 'Pilih Blok',
		listeners: {
            select: function(combo, records) {
                var kd_cblokasiopname = Ext.getCmp('id_lokasi_mro').getValue();
                var kd_cbblokopname = this.getValue();
		cbinitsubblokopname.setValue();
                cbsubblokmro.store.proxy.conn.url = '<?= site_url("sub_blok_lokasi/get_sub_blok")?>/'+kd_cblokasiopname+'/'+kd_cbblokopname;
                cbsubblokmro.store.reload();
            }
        }
    });
	
    // combobox sub_blok
    var strcbsubblokmro = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_sub_blok', 'nama_sub_blok'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("sub_blok_lokasi/get_sub_blok") ?>',
            method: 'POST'
        })
    });
    var cbsubblokmro = new Ext.form.ComboBox({
        fieldLabel: 'Nama Sub Blok <span class="asterix">*</span>',
        id: 'id_cbsubblok_mro',
        mode: 'local',
        store: strcbsubblokmro,
        valueField: 'kd_sub_blok',
        displayField: 'nama_sub_blok',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_sub_blok',
        emptyText: 'Pilih Sub Blok'
    });
// start COMBOBOX STATUS INV
	var valcbMroStatusInv = [
		['0', "All"],
		['1', "Belum Invoice"],
		['2', "Sudah Invoice"]
	];
	var strcbMroStatusInv = new Ext.data.ArrayStore({
		fields: [{
			name: 'key'
		}, {
			name: 'value'
		}],
		data: valcbMroStatusInv
	});
	var cbMroStatusInv = new Ext.form.ComboBox({
		fieldLabel: 'Status Invoice',
		id: 'cbMroStatusInv',
		name: 'status',
		// allowBlank:false,
		store: strcbMroStatusInv,
		valueField: 'key',
		displayField: 'value',
		mode: 'local',
		forceSelection: true,
		triggerAction: 'all',
		anchor: '90%'
	});
	// end COMBOBOX STATUS INV
 // start COMBOBOX STATUS Bayar
	var valcbMroStatusBayar = [
		['0', "All"],
		['1', "Belum"],
		['2', "Sudah"]
	];
	var strcbMroStatusBayar = new Ext.data.ArrayStore({
		fields: [{
			name: 'key'
		}, {
			name: 'value'
		}],
		data: valcbMroStatusBayar
	});
	var cbMroStatusBayar = new Ext.form.ComboBox({
		fieldLabel: 'Status Bayar',
		id: 'cbMroStatusBayar',
		name: 'konsinyasi',
		// allowBlank:false,
		store: strcbMroStatusBayar,
		valueField: 'key',
		displayField: 'value',
		mode: 'local',
		forceSelection: true,
		triggerAction: 'all',
		anchor: '90%'
	});
	// end COMBOBOX STATUS PRODUK	
	// HEADER MONTIROING RO
	var headermonitoringRO = {
        layout: 'column',
        border: false,
		buttonAlign:'left',
        items: [{
            columnWidth: .5,
            layout: 'form',
            border: false,
            labelWidth: 100,
            defaults: { labelSeparator: ''},
            items: [cbmrosuplier,
				{
					xtype: 'datefield',
					fieldLabel: 'Tgl RO',
					emptyText: 'Tanggal Awal',
					name: 'tgl_awal',
					id: 'mro_tgl_awal',
					maxLength: 255,
					anchor: '90%',
					value: '',
					format:'d-M-Y'
				},cblokasimro,cbblokmro,cbMroStatusBayar
			]
        }, {
            columnWidth: .5,
            layout: 'form',
            border: false,
            labelWidth: 100,
            defaults: { labelSeparator: ''},
            items: [ {
	                xtype: 'textfield',
	                fieldLabel: 'Nama Supplier',
	                name: 'nama_supplier',
	                readOnly:true,
					fieldClass:'readonly-input',
	                id: 'mro_nama_supplier',                
	                anchor: '90%',
	                value:'',
					emptyText: 'Nama Supplier'
				},{
					xtype: 'datefield',
					fieldLabel: 's/d',
					emptyText: 'Tanggal Akhir',
					name: 'tgl_akhir',
					id: 'mro_tgl_akhir',
					maxLength: 255,
					anchor: '90%',
					value: '',
					format:'d-M-Y'
				},cbsubblokmro,cbMroStatusInv,
                                 {
                                        fieldLabel: 'Peruntukan <span class="asterix">*</span>',
                                        xtype: 'radiogroup',
                                        name: 'kd_peruntukan',
                                        columnWidth: [.5, .5],
                                        allowBlank:false,
                                        anchor: '90%',
                                        items: [{
                                                boxLabel: 'Supermarket',
                                                name: 'kd_peruntukan',
                                                inputValue: '0',
                                                id: 'mro_peruntukan_supermarket',
                                                checked:true
                                            }, {
                                                boxLabel: 'Distribusi',
                                                name: 'kd_peruntukan',
                                                inputValue: '1',
                                                id: 'mro_peruntukan_distribusi'
                                            }]
                                    }]
        }],
		buttons: [{
			text: 'Filter',
			formBind: true,
			handler: function(){
				gridpembelianmonitoringRO.store.load({
					params:{
						kd_supplier: Ext.getCmp('id_cbmrosuplier').getValue(),
						tgl_awal: Ext.getCmp('mro_tgl_awal').getValue(),
						tgl_akhir: Ext.getCmp('mro_tgl_akhir').getValue(),
						lokasi: Ext.getCmp('id_lokasi_mro').getValue(),
						bloklokasi: Ext.getCmp('id_blok_mro').getValue(),
                                                subbloklokasi: Ext.getCmp('id_cbsubblok_mro').getValue(),
						invoice: Ext.getCmp('cbMroStatusInv').getValue(),
                                                bayar: Ext.getCmp('cbMroStatusBayar').getValue(),
                                                peruntukan_sup: Ext.getCmp('mro_peruntukan_supermarket').getValue(),
                                                peruntukan_dist: Ext.getCmp('mro_peruntukan_distribusi').getValue(),
					}
				});      
            }
		},{
			text: 'Reset',
			formBind: true,
			handler: function(){
				Ext.getCmp('id_cbmrosuplier').setValue('');
				Ext.getCmp('mro_nama_supplier').setValue('');		        
				Ext.getCmp('mro_tgl_awal').setRawValue('');		        
				Ext.getCmp('mro_tgl_akhir').setRawValue('');		
				Ext.getCmp('id_lokasi_mro').setValue('');		
				Ext.getCmp('id_blok_mro').setValue('');		
				Ext.getCmp('id_cbsubblok_mro').setValue('');
                                Ext.getCmp('cbMroStatusInv').setValue('');
                                Ext.getCmp('cbMroStatusBayar').setValue('');
				gridpembelianmonitoringRO.store.load();      
            }
		}]
    };
	
    // GRID MONITORING RO   	
	var strpembelianmonitoringRO = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
				'no_do',
				'kd_supplier',
				'tanggal',
				'no_bukti_supplier',
				'konsinyasi',
				'tanggal_terima',
                                'nama_supplier',
                                'no_invoice',
                                'tgl_invoice',
                                'tgl_terima_invoice',
                                'no_bukti',
                                'tanggal',
                                'peruntukan'
				],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("monitoring_receive_order/get_rows") ?>',
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
    var searchpembelianmonitoringRO = new Ext.app.SearchField({
        store: strpembelianmonitoringRO,
        params: {
           start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
		emptyText: 'No RO,NO Invoice,NO Pembayaran',
        id: 'idsearchpembelianmonitoringRO'
    });
    strpembelianmonitoringRO.on('load',function(){
        strpembelianmonitoringRO.setBaseParam('kd_supplier',Ext.getCmp('id_cbmrosuplier').getValue());
        strpembelianmonitoringRO.setBaseParam('tgl_awal',Ext.getCmp('mro_tgl_awal').getValue());
        strpembelianmonitoringRO.setBaseParam('tgl_akhir',Ext.getCmp('mro_tgl_akhir').getValue());
        strpembelianmonitoringRO.setBaseParam('lokasi',Ext.getCmp('id_lokasi_mro').getValue());
        strpembelianmonitoringRO.setBaseParam('bloklokasi',Ext.getCmp('id_blok_mro').getValue());
        strpembelianmonitoringRO.setBaseParam('subbloklokasi',Ext.getCmp('id_cbsubblok_mro').getValue());
        strpembelianmonitoringRO.setBaseParam('invoice',Ext.getCmp('cbMroStatusInv').getValue());
        strpembelianmonitoringRO.setBaseParam('bayar',Ext.getCmp('cbMroStatusBayar').getValue());
    });
    var tbpembelianmonitoringRO = new Ext.Toolbar({
        items: [searchpembelianmonitoringRO]
    });
    var smpembelianmonitoringRO = new Ext.grid.CheckboxSelectionModel();
	var gridpembelianmonitoringRO = new Ext.grid.EditorGridPanel({
        id: 'gridpembelianmonitoringRO',
        frame: true,
        border: true,
        stripeRows: true,
        sm: smpembelianmonitoringRO,
        store: strpembelianmonitoringRO,
        loadMask: true,
        style: 'margin:0 auto;',
        height: 400,
        columns: [{
            header: "No RO",
            dataIndex: 'no_do',
            sortable: true,
            width: 100
        },{
            header: "Tanggal Terima",
            dataIndex: 'tanggal_terima',
            sortable: true,
            width: 100
        },{
            header: "Tanggal Input",
            dataIndex: 'tanggal',
            sortable: true,
            width: 100
        },{
            header: "Kode Supplier",
            dataIndex: 'kd_supplier',
            sortable: true,
            width: 100
        },{
            header: "Nama Supplier",
            dataIndex: 'nama_supplier',
            sortable: true,
            width: 200
        },{
            header: "No Invoice",
            dataIndex: 'no_invoice',
            sortable: true,
            width: 150
        },{
            header: "Tanggal Terima Invoice",
            dataIndex: 'tgl_terima_invoice',
            sortable: true,
            width: 130
        }, {
            header: "Tanggal Invoice",
            dataIndex: 'tgl_invoice',
            sortable: true,
            width: 100
        },{
            header: "No Pembayaran",
            dataIndex: 'no_bukti',
            sortable: true,
            width: 150
        },{
            header: "Tanggal Pembayaran",
            dataIndex: 'tanggal',
            sortable: true,
            width: 150
        },{
            header: "Peruntukan",
            dataIndex: 'peruntukan',
            sortable: true,
            width: 150
        }],
	listeners: {
            'rowdblclick': function(){				
                var sm = gridpembelianmonitoringRO.getSelectionModel();                
                var sel = sm.getSelections();                
                if (sel.length > 0) {

                    Ext.Ajax.request({
                        url: '<?= site_url("monitoring_receive_order/get_data_ro") ?>/' + sel[0].get('no_do'),
                        method: 'POST',
                        params: {},
                        callback: function (opt, success, responseObj) {
                            var windowmonitoringro = new Ext.Window({
                                title: 'Monitoring Receive Order',
                                width: 1020,
                                height: 500,
                                autoScroll: true,
                                html: responseObj.responseText
                            });

                            windowmonitoringro.show();

                        }
                    });
                }                 
            }          
        },			
        tbar: tbpembelianmonitoringRO,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strpembelianmonitoringRO,
            displayInfo: true
        })
    });
   
   
   
   
   var pembelianmonitoringRO = new Ext.FormPanel({
	id: 'pembelianmonitoringRO',
	border: false,
        frame: true,
	autoScroll:true,	 
        bodyStyle:'padding-right:20px;',
        labelWidth: 130,
        items: [{
                    bodyStyle: {
                        margin: '10px 0px 15px 0px'
                    },                  
                    items: [headermonitoringRO]
                },gridpembelianmonitoringRO]
	});
        
     pembelianmonitoringRO.on('afterrender', function(){
        this.getForm().load({
            url: '<?= site_url("pembelian_create_request/get_form") ?>',
            success: function(form, action){
                var r = Ext.util.JSON.decode(action.response.responseText);
                if(r.data.user_peruntukan === "0"){
                    Ext.getCmp('mro_peruntukan_supermarket').setValue(true);
                    Ext.getCmp('mro_peruntukan_supermarket').show();
                    Ext.getCmp('mro_peruntukan_distribusi').hide();
                }else if(r.data.user_peruntukan === "1"){
                    Ext.getCmp('mro_peruntukan_distribusi').setValue(true);
                    Ext.getCmp('mro_peruntukan_supermarket').hide();
                    Ext.getCmp('mro_peruntukan_distribusi').show();
                }else{
                    Ext.getCmp('mro_peruntukan_supermarket').setValue(true);
                    Ext.getCmp('mro_peruntukan_supermarket').show();
                    Ext.getCmp('mro_peruntukan_distribusi').show();
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
	
    function clearmonitoringRO(){
        Ext.getCmp('pembelianmonitoringRO').getForm().reset();
        Ext.getCmp('pembelianmonitoringRO').getForm().load({
            url: '<?= site_url("pembelian_create_request/get_form") ?>',
            success: function(form, action){
                var r = Ext.util.JSON.decode(action.response.responseText);
                if(r.data.user_peruntukan === "0"){
                    Ext.getCmp('mro_peruntukan_supermarket').setValue(true);
                    Ext.getCmp('mro_peruntukan_supermarket').show();
                    Ext.getCmp('mro_peruntukan_distribusi').hide();
                }else if(r.data.user_peruntukan === "1"){
                    Ext.getCmp('mro_peruntukan_distribusi').setValue(true);
                    Ext.getCmp('mro_peruntukan_supermarket').hide();
                    Ext.getCmp('mro_peruntukan_distribusi').show();
                }else{
                    Ext.getCmp('mro_peruntukan_supermarket').setValue(true);
                    Ext.getCmp('mro_peruntukan_supermarket').show();
                    Ext.getCmp('mro_peruntukan_distribusi').show();
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
        strpembelianmonitoringRO.removeAll();
    }
</script>
