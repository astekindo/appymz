<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">
    // Twin Combo Produk
    var strcblaprkpjualproduk = new Ext.data.ArrayStore({
        fields: ['kd_produk'],
        data : []
        });

    var strgridlaprkpjualproduk = new Ext.data.Store({
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

        var searchgridlaprkpjualproduk = new Ext.app.SearchField({
            store: strgridlaprkpjualproduk,
            params: {
            start: STARTPAGE,
            limit: ENDPAGE
            },
            width: 350,
            id: 'id_searchgridlaprkpjualproduk'
        });


        var gridlaprkpjualproduk = new Ext.grid.GridPanel({
        store: strgridlaprkpjualproduk,
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
            items: [searchgridlaprkpjualproduk]
	    }),
            bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridlaprkpjualproduk,
            displayInfo: true
        }),
		listeners: {
			'rowdblclick': function(){
				var sm = this.getSelectionModel();
				var sel = sm.getSelections();
				if (sel.length > 0) {
                    Ext.getCmp('id_cblaprkpjualproduk').setValue(sel[0].get('kd_produk'));
                    menulaprkpjualproduk.hide();
				}
			}
		}
    });
    Ext.ux.TwinComboLrpsProduk = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
        strgridlaprkpjualproduk.load();
        menulaprkpjualproduk.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

        var menulaprkpjualproduk = new Ext.menu.Menu();
        menulaprkpjualproduk.add(new Ext.Panel({
        title: 'Pilih Produk',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridlaprkpjualproduk],
        buttons: [{
            text: 'Close',
            handler: function(){
            menulaprkpjualproduk.hide();
            }
        }]
    }));

	menulaprkpjualproduk.on('hide', function(){
		var sf = Ext.getCmp('id_searchgridlaprkpjualproduk').getValue();
		if( sf != ''){
			Ext.getCmp('id_searchgridlaprkpjualproduk').setValue('');
			searchgridlaprkpjualproduk.onTrigger2Click();
		}
	});

    var cblaprkpjualproduk = new Ext.ux.TwinComboLrpsProduk({
        id: 'id_cblaprkpjualproduk',
        fieldLabel: 'Produk',
        store: strcblaprkpjualproduk,
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
        var strcblaprkpjualpersuplier = new Ext.data.ArrayStore({
        fields: ['kd_supplier'],
        data : []
        });

        var strgridlaprkpjualsuplier = new Ext.data.Store({
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

        var searchgridljualrkpjualsuplier = new Ext.app.SearchField({
        store: strgridlaprkpjualsuplier ,
        params: {
        start: STARTPAGE,
        limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgridljualrkpjualsuplier'
    });
        var gridlaprkpjualsuplier = new Ext.grid.GridPanel({
        store: strgridlaprkpjualsuplier ,
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
	        items: [searchgridljualrkpjualsuplier]
	    }),
            bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridlaprkpjualsuplier,
            displayInfo: true
        }),
		listeners: {
			'rowdblclick': function(){
				var sm = this.getSelectionModel();
				var sel = sm.getSelections();
				if (sel.length > 0) {
                    Ext.getCmp('id_cblaprkpjualpersuplier').setValue(sel[0].get('kd_supplier'));
                    menulaprkpjualsuplier.hide();
				}
			}
		}
    });

        var menulaprkpjualsuplier = new Ext.menu.Menu();
        menulaprkpjualsuplier.add(new Ext.Panel({
        title: 'Pilih Supplier',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridlaprkpjualsuplier],
        buttons: [{
            text: 'Close',
            handler: function(){
                menulaprkpjualsuplier.hide();
            }
        }]
    }));

    Ext.ux.TwinComboSuplierRkpJual = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            strgridlaprkpjualsuplier .load();
            menulaprkpjualsuplier.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

	menulaprkpjualsuplier.on('hide', function(){
		var sf = Ext.getCmp('id_searchgridljualrkpjualsuplier').getValue();
		if( sf != ''){
			Ext.getCmp('id_searchgridljualrkpjualsuplier').setValue('');
			searchgridljualrkpjualsuplier.onTrigger2Click();
		}
	});

        var cblaprkpjualpersuplier = new Ext.ux.TwinComboSuplierRkpJual({
        fieldLabel: 'Supplier',
        id: 'id_cblaprkpjualpersuplier',
        store: strcblaprkpjualpersuplier,
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
  var str_cblaprkpjualpersuppkategori1 = new Ext.data.Store({
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
                var r = new (str_cblaprkpjualpersuppkategori1.recordType)({
                    'kd_kategori1': '',
                    'nama_kategori1': '-----'
                });
                str_cblaprkpjualpersuppkategori1.insert(0, r);
            },
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

        var cblaprkpjualpersuppkategori1 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 1',
        id: 'id_cblaprkpjualpersuppkategori1',
        store: str_cblaprkpjualpersuppkategori1,
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
                var kdhp_cbkategori1 = cblaprkpjualpersuppkategori1.getValue();
                cblaprkpjualpersuppkategori2.store.proxy.conn.url = '<?= site_url("kategori3/get_kategori2") ?>/' + kdhp_cbkategori1;
                cblaprkpjualpersuppkategori2.store.reload();
            }
        }
    });

    // combobox kategori2

        var str_cblaprkpjualpersuppkategori2 = new Ext.data.Store({
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
                var r = new (str_cblaprkpjualpersuppkategori2.recordType)({
                    'kd_kategori2': '',
                    'nama_kategori2': '-----'
                });
                str_cblaprkpjualpersuppkategori2.insert(0, r);
            },
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

        var cblaprkpjualpersuppkategori2 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 2',
        id: 'id_cblaprkpjualpersuppkategori2',
        mode: 'local',
        store: str_cblaprkpjualpersuppkategori2,
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
                var kd_hp_cbkategori1 = cblaprkpjualpersuppkategori1.getValue();
                var kd_hp_cbkategori2 = this.getValue();
                cblaprkpjualpersuppkategori3.setValue();
                cblaprkpjualpersuppkategori3.store.proxy.conn.url = '<?= site_url("kategori4/get_kategori3") ?>/' + kd_hp_cbkategori1 +'/'+ kd_hp_cbkategori2;
                cblaprkpjualpersuppkategori3.store.reload();
            }
        }
    });

     // combobox kategori3

    var str_cblaprkpjualpersuppkategori3 = new Ext.data.Store({
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
                var r = new (str_cblaprkpjualpersuppkategori3.recordType)({
                    'kd_kategori3': '',
                    'nama_kategori3': '-----'
                });
                str_cblaprkpjualpersuppkategori3.insert(0, r);
            },
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    var cblaprkpjualpersuppkategori3 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 3',
        id: 'id_cblaprkpjualpersuppkategori3',
        mode: 'local',
        store: str_cblaprkpjualpersuppkategori3,
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
                var kd_hp_cbkategori1 = cblaprkpjualpersuppkategori1.getValue();
                var kd_hp_cbkategori2 = cblaprkpjualpersuppkategori2.getValue();
                var kd_hp_cbkategori3 = this.getValue();
                cblaprkpjualpersuppkategori4.setValue();
                cblaprkpjualpersuppkategori4.store.proxy.conn.url = '<?= site_url("master_barang/get_kategori4") ?>/' + kd_hp_cbkategori1 +'/'+ kd_hp_cbkategori2 +'/'+ kd_hp_cbkategori3;
                cblaprkpjualpersuppkategori4.store.reload();
            }
        }
    });

    // combobox kategori4

    var str_cblaprkpjualpersuppkategori4 = new Ext.data.Store({
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
                var r = new (str_cblaprkpjualpersuppkategori4.recordType)({
                    'kd_kategori4': '',
                    'nama_kategori4': '-----'
                });
                str_cblaprkpjualpersuppkategori4.insert(0, r);
            },
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    var cblaprkpjualpersuppkategori4 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 4 ',
        id: 'id_cblaprkpjualpersuppkategori4',
        mode: 'local',
        store: str_cblaprkpjualpersuppkategori4,
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
		var lrppssortorder = new Ext.form.Checkbox({
			xtype: 'checkbox',
			fieldLabel: 'Sort Order',
			boxLabel:'Tgl',
			name:'sort_order',
			id:'id_lrppssortorder',
			checked: true,
			inputValue: '1',
			autoLoad : true
		});

        var headerlaprkppenjualan = {
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
                                                                        }, cblaprkpjualpersuppkategori1,
                                                                           cblaprkpjualpersuppkategori2,
                                                                           cblaprkpjualpersuppkategori3,
                                                                           cblaprkpjualpersuppkategori4

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
									},cblaprkpjualpersuplier,
                                                                          cblaprkpjualproduk,
                                                                          lrppssortorder

								]
							}

						]
					}
				]
			}]
        }
        ]
    }


    var headerlaprkpprodukjualpersupp = {
            buttonAlign: 'left',
            layout: 'form',
            border: false,
            labelWidth: 100,
			defaults: { labelSeparator: ''},
                items: [headerlaprkppenjualan,

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
				winlaprkpprodukjualpersuppprint.show();
                               // Ext.getDom('laprkpprodukjualpersuppprint').src = '<?= site_url("laporan_penjualan_per_kategori1/print_form") ?>' + '/' + kd_user + '/' + kd_shift + '/' + kd_member + '/' + dari_tgl + '/' + sampai_tgl;
				Ext.getDom('laprkpprodukjualpersuppprint').src = '<?= site_url("laporan_penjualan_per_kategori1/print_form") ?>';
			}
        },{
			text: 'Cancel',
			handler: function(){
				clearlaprkpprodukjualpersupp();
			}
		}]
    };
        var winlaprkpprodukjualpersuppprint = new Ext.Window({
        id: 'id_winlaprkpprodukjualpersuppprint',
	Title: 'Print Rekap Produk Penjualan Per Supplier',
        closeAction: 'hide',
        width: 900,
        height: 450,
        layout: 'fit',
        border: false,
        html:'<iframe style="width:100%;height:390px;" id="laprkpprodukjualpersuppprint" src=""></iframe>'
    });


       var laprkpprodukjualpersupp = new Ext.FormPanel({
	 	id: 'rpt_rkp_produk_jual_persupplier',
		border: false,
		frame: true,
		monitorValid: true,
		labelWidth: 130,
        items: [{
                    bodyStyle: {
                        margin: '0px 0px 15px 0px'
                    },
                    items: [headerlaprkpprodukjualpersupp]
                }
        ]
    });

	function clearlaprkpprodukjualpersupp(){
		Ext.getCmp('rpt_rkp_produk_jual_persupplier').getForm().reset();
	}
</script>