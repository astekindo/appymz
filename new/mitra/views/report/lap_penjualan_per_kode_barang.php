<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">
    // Twin Combo Produk
    var strcbljualperkbproduk = new Ext.data.ArrayStore({
        fields: ['kd_produk'],
        data : []
        });

    var strgridljualperkbproduk = new Ext.data.Store({
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

        var searchgridljualperkbproduk = new Ext.app.SearchField({
            store: strgridljualperkbproduk,
            params: {
            start: STARTPAGE,
            limit: ENDPAGE
            },
            width: 350,
            id: 'id_searchgridljualperkbproduk'
        });


        var gridljualperkbproduk = new Ext.grid.GridPanel({
        store: strgridljualperkbproduk,
        stripeRows: true,
        frame: true,
        border:true,
        columns: [{
            header: 'Kode Produk',
            dataIndex: 'kd_produk',
            width: 120,
            sortable: true

        },{
            header: 'Nama Produk',
            dataIndex: 'nama_produk',
            width: 300,
            sortable: true
        }],
            tbar: new Ext.Toolbar({
            items: [searchgridljualperkbproduk]
	    }),
            bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridljualperkbproduk,
            displayInfo: true
        }),
		listeners: {
			'rowdblclick': function(){
				var sm = this.getSelectionModel();
				var sel = sm.getSelections();
				if (sel.length > 0) {
                    Ext.getCmp('id_cbljualperkbproduk').setValue(sel[0].get('kd_produk'));
                    menuljualperkbproduk.hide();
				}
			}
		}
    });
    Ext.ux.TwinComboLpkbProduk = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
        strgridljualperkbproduk.load();
        menuljualperkbproduk.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

        var menuljualperkbproduk = new Ext.menu.Menu();
        menuljualperkbproduk.add(new Ext.Panel({
        title: 'Pilih Produk',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridljualperkbproduk],
        buttons: [{
            text: 'Close',
            handler: function(){
            menuljualperkbproduk.hide();
            }
        }]
    }));

	menuljualperkbproduk.on('hide', function(){
		var sf = Ext.getCmp('id_searchgridljualperkbproduk').getValue();
		if( sf != ''){
			Ext.getCmp('id_searchgridljualperkbproduk').setValue('');
			searchgridljualperkbproduk.onTrigger2Click();
		}
	});

    var cbljualperkbproduk = new Ext.ux.TwinComboLpkbProduk({
        id: 'id_cbljualperkbproduk',
        fieldLabel: 'Produk',
        store: strcbljualperkbproduk,
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

       // Twin Supplier
        var strcbljualkdbarangsuplier = new Ext.data.ArrayStore({
        fields: ['kd_supplier'],
        data : []
        });

        var strgridljualkdbarangsuplier = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
        fields: ['kd_supplier', 'nama_supplier'],
        root: 'data',
        totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
        url: '<?= site_url("laporan_rekap_po/search_supplier") ?>',
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

        var searchgridljualkdbarangsuplier = new Ext.app.SearchField({
        store: strgridljualkdbarangsuplier ,
        params: {
        start: STARTPAGE,
        limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgridljualkdbarangsuplier'
    });
        var gridljualkdbarangsuplier = new Ext.grid.GridPanel({
        store: strgridljualkdbarangsuplier ,
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
	        items: [searchgridljualkdbarangsuplier]
	    }),
            bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridljualkdbarangsuplier,
            displayInfo: true
        }),
		listeners: {
			'rowdblclick': function(){
				var sm = this.getSelectionModel();
				var sel = sm.getSelections();
				if (sel.length > 0) {
                    Ext.getCmp('id_cbljualkdbarangsuplier').setValue(sel[0].get('kd_supplier'));
                    menuljualkdbarangsuplier.hide();
				}
			}
		}
    });

        var menuljualkdbarangsuplier = new Ext.menu.Menu();
        menuljualkdbarangsuplier.add(new Ext.Panel({
        title: 'Pilih Supplier',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridljualkdbarangsuplier],
        buttons: [{
            text: 'Close',
            handler: function(){
                menuljualkdbarangsuplier.hide();
            }
        }]
    }));

    Ext.ux.TwinComboSuplierKdBarang = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            strgridljualkdbarangsuplier .load();
            menuljualkdbarangsuplier.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

	menuljualkdbarangsuplier.on('hide', function(){
		var sf = Ext.getCmp('id_searchgridljualkdbarangsuplier').getValue();
		if( sf != ''){
			Ext.getCmp('id_searchgridljualkdbarangsuplier').setValue('');
			searchgridljualkdbarangsuplier.onTrigger2Click();
		}
	});

        var cbljualkdbarangsuplier = new Ext.ux.TwinComboSuplierKdBarang({
        fieldLabel: 'Supplier',
        id: 'id_cbljualkdbarangsuplier',
        store: strcbljualkdbarangsuplier,
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

// cb kategori1
  var str_cbljualkdbarangkategori1 = new Ext.data.Store({
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
                var r = new (str_cbljualkdbarangkategori1.recordType)({
                    'kd_kategori1': '',
                    'nama_kategori1': '-----'
                });
                str_cbljualkdbarangkategori1.insert(0, r);
            },
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

        var cbljualkdbarangkategori1 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 1',
        id: 'id_cbljualkdbarangkategori1',
        store: str_cbljualkdbarangkategori1,
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
                var kdhp_cbkategori1 = cbljualkdbarangkategori1.getValue();
                cbljualkdbarangkategori2.store.proxy.conn.url = '<?= site_url("kategori3/get_kategori2") ?>/' + kdhp_cbkategori1;
                cbljualkdbarangkategori2.store.reload();
            }
        }
    });

    // combobox kategori2

        var str_cbljualkdbarangkategori2 = new Ext.data.Store({
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
                var r = new (str_cbljualkdbarangkategori2.recordType)({
                    'kd_kategori2': '',
                    'nama_kategori2': '-----'
                });
                str_cbljualkdbarangkategori2.insert(0, r);
            },
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

        var cbljualkdbarangkategori2 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 2 ',
        id: 'id_cbljualkdbarangkategori2',
        mode: 'local',
        store: str_cbljualkdbarangkategori2,
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
                var kd_hp_cbkategori1 = cbljualkdbarangkategori1.getValue();
                var kd_hp_cbkategori2 = this.getValue();
                cbljualkdbarangkategori3.setValue();
                cbljualkdbarangkategori3.store.proxy.conn.url = '<?= site_url("kategori4/get_kategori3") ?>/' + kd_hp_cbkategori1 +'/'+ kd_hp_cbkategori2;
                cbljualkdbarangkategori3.store.reload();
            }
        }
    });

     // combobox kategori3

    var str_cbljualkdbarangkategori3 = new Ext.data.Store({
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
                var r = new (str_cbljualkdbarangkategori3.recordType)({
                    'kd_kategori3': '',
                    'nama_kategori3': '-----'
                });
                str_cbljualkdbarangkategori3.insert(0, r);
            },
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    var cbljualkdbarangkategori3 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 3 ',
        id: 'id_cbljualkdbarangkategori3',
        mode: 'local',
        store: str_cbljualkdbarangkategori3,
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
                var kd_hp_cbkategori1 = cbljualkdbarangkategori1.getValue();
                var kd_hp_cbkategori2 = cbljualkdbarangkategori2.getValue();
                var kd_hp_cbkategori3 = this.getValue();
                cbljualkdbarangkategori4.setValue();
                cbljualkdbarangkategori4.store.proxy.conn.url = '<?= site_url("master_barang/get_kategori4") ?>/' + kd_hp_cbkategori1 +'/'+ kd_hp_cbkategori2 +'/'+ kd_hp_cbkategori3;
                cbljualkdbarangkategori4.store.reload();
            }
        }
    });

    // combobox kategori4

    var str_cbljualkdbarangkategori4 = new Ext.data.Store({
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
                var r = new (str_cbljualkdbarangkategori4.recordType)({
                    'kd_kategori4': '',
                    'nama_kategori4': '-----'
                });
                str_cbljualkdbarangkategori4.insert(0, r);
            },
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    var cbljualkdbarangkategori4 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 4 ',
        id: 'id_cbljualkdbarangkategori4',
        mode: 'local',
        store: str_cbljualkdbarangkategori4,
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

    // CHECKBOX Sort Order
		var lppkbsortorder = new Ext.form.Checkbox({
			xtype: 'checkbox',
			fieldLabel: 'Sort Order',
			boxLabel:'Descending',
			name:'sort_order',
			id:'id_lppkbsortorder',
			checked: true,
			inputValue: '1',
			autoLoad : true
		});

        var headerlappenjperkdbarang = {
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
                                                                            fieldLabel: 'Dari Tgl ',
                                                                            name: 'dari_tgl',
                                                                            allowBlank:false,
                                                                            format:'d-m-Y',
                                                                            editable:false,
                                                                            id: 'id_dari_tgl',
                                                                            anchor: '90%',
                                                                            value: ''
                                                                        }, cbljualkdbarangkategori1,
                                                                           cbljualkdbarangkategori2,
                                                                           cbljualkdbarangkategori3,
                                                                           cbljualkdbarangkategori4

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
										id: 'id_smp_tgl',
										anchor: '90%',
										value: ''
									},cbljualkdbarangsuplier,
                                                                          cbljualperkbproduk,
                                                                          lppkbsortorder

								]
							}

						]
					}
				]
			}]
        }
        ]
    }


    var headerlaporanpenjualanperkodebarang = {
            buttonAlign: 'left',
            layout: 'form',
            border: false,
            labelWidth: 100,
			defaults: { labelSeparator: ''},
                items: [headerlappenjperkdbarang,

                ],
            buttons: [{
            text: 'Print',
			formBind:true,
            handler: function(){
                                //var kd_user= Ext.getCmp('id_cblp1user').getValue();
                                //var kd_shift= Ext.getCmp('id_cblp1shift').getValue();
                                //var kd_member= Ext.getCmp('id_cblp1member').getValue();
                                //var dari_tgl= Ext.getCmp('id_dari_tgl_lp1').getRawValue();
                                //var sampai_tgl= Ext.getCmp('id_smp_tgl_lp1').getRawValue();
				winlaporanpenjualanperkodebarangprint.show();
                               // Ext.getDom('laporanpenjualanperkodebarangprint').src = '<?= site_url("laporan_penjualan_per_kategori1/print_form") ?>' + '/' + kd_user + '/' + kd_shift + '/' + kd_member + '/' + dari_tgl + '/' + sampai_tgl;
				Ext.getDom('laporanpenjualanperkodebarangprint').src = '<?= site_url("laporan_penjualan_per_kategori1/print_form") ?>';
			}
        },{
			text: 'Cancel',
			handler: function(){
				clearlaporanpenjualanperkodebarang();
			}
		}]
    };
        var winlaporanpenjualanperkodebarangprint = new Ext.Window({
        id: 'id_winlaporanpenjualanperkodebarangprint',
	Title: 'Print Penjualan per Kategori 4',
        closeAction: 'hide',
        width: 900,
        height: 450,
        layout: 'fit',
        border: false,
        html:'<iframe style="width:100%;height:390px;" id="laporanpenjualanperkodebarangprint" src=""></iframe>'
    });


       var laporanpenjualanperkodebarang = new Ext.FormPanel({
	 	id: 'rpt_penjualan_perkode_barang',
		border: false,
		frame: true,
		monitorValid: true,
		labelWidth: 130,
        items: [{
                    bodyStyle: {
                        margin: '0px 0px 15px 0px'
                    },
                    items: [headerlaporanpenjualanperkodebarang]
                }
        ]
    });

	function clearlaporanpenjualanperkodebarang(){
		Ext.getCmp('rpt_penjualan_perkode_barang').getForm().reset();
	}
</script>