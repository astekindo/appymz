<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">
    // Twin Combo Produk
    var strcblrkpjualpersuppperqtyproduk = new Ext.data.ArrayStore({
        fields: ['kd_produk'],
        data : []
        });

    var strgridlrkpjualpersuppperqtyproduk = new Ext.data.Store({
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

        var searchgridlrkpjualpersuppperqtyproduk = new Ext.app.SearchField({
            store: strgridlrkpjualpersuppperqtyproduk,
            params: {
            start: STARTPAGE,
            limit: ENDPAGE
            },
            width: 350,
            id: 'id_searchgridlrkpjualpersuppperqtyproduk'
        });


        var gridlrkpjualpersuppperqtyproduk = new Ext.grid.GridPanel({
        store: strgridlrkpjualpersuppperqtyproduk,
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
            items: [searchgridlrkpjualpersuppperqtyproduk]
	    }),
            bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridlrkpjualpersuppperqtyproduk,
            displayInfo: true
        }),
		listeners: {
			'rowdblclick': function(){
				var sm = this.getSelectionModel();
				var sel = sm.getSelections();
				if (sel.length > 0) {
                    Ext.getCmp('id_cblrkpjualpersuppperqtyproduk').setValue(sel[0].get('kd_produk'));
                    menulrkpjualpersuppperqtyproduk.hide();
				}
			}
		}
    });
    Ext.ux.TwinComboLrpjpspqProduk = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
        strgridlrkpjualpersuppperqtyproduk.load();
        menulrkpjualpersuppperqtyproduk.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

        var menulrkpjualpersuppperqtyproduk = new Ext.menu.Menu();
        menulrkpjualpersuppperqtyproduk.add(new Ext.Panel({
        title: 'Pilih Produk',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridlrkpjualpersuppperqtyproduk],
        buttons: [{
            text: 'Close',
            handler: function(){
            menulrkpjualpersuppperqtyproduk.hide();
            }
        }]
    }));

	menulrkpjualpersuppperqtyproduk.on('hide', function(){
		var sf = Ext.getCmp('id_searchgridlrkpjualpersuppperqtyproduk').getValue();
		if( sf != ''){
			Ext.getCmp('id_searchgridlrkpjualpersuppperqtyproduk').setValue('');
			searchgridlrkpjualpersuppperqtyproduk.onTrigger2Click();
		}
	});

    var cblrkpjualpersuppperqtyproduk = new Ext.ux.TwinComboLrpjpspqProduk({
        id: 'id_cblrkpjualpersuppperqtyproduk',
        fieldLabel: 'Produk',
        store: strcblrkpjualpersuppperqtyproduk,
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
        var strcblrkpjualpersuppperqtysuplier = new Ext.data.ArrayStore({
        fields: ['kd_supplier'],
        data : []
        });

        var strgridlrkpjualpersupperqtypsuplier = new Ext.data.Store({
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

        var searchgridlrkpjualpersupperqtypsuplier = new Ext.app.SearchField({
        store: strgridlrkpjualpersupperqtypsuplier ,
        params: {
        start: STARTPAGE,
        limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgridlrkpjualpersupperqtypsuplier'
    });
        var gridlrkpjualpersupperqtypsuplier = new Ext.grid.GridPanel({
        store: strgridlrkpjualpersupperqtypsuplier ,
        stripeRows: true,
        frame: true,
        border:true,
        columns: [{
            header: 'Kode Supplier',
            dataIndex: 'kd_supplier',
            width: 100,
            sortable: true

        },{
            header: 'Nama Supplier',
            dataIndex: 'nama_supplier',
            width: 300,
            sortable: true
        }],
		tbar: new Ext.Toolbar({
	        items: [searchgridlrkpjualpersupperqtypsuplier]
	    }),
            bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridlrkpjualpersupperqtypsuplier,
            displayInfo: true
        }),
		listeners: {
			'rowdblclick': function(){
				var sm = this.getSelectionModel();
				var sel = sm.getSelections();
				if (sel.length > 0) {
                    Ext.getCmp('id_cblrkpjualpersuppperqtysuplier').setValue(sel[0].get('kd_supplier'));
                    menulrkpjualpersupperqtysupp.hide();
				}
			}
		}
    });

        var menulrkpjualpersupperqtysupp = new Ext.menu.Menu();
        menulrkpjualpersupperqtysupp.add(new Ext.Panel({
        title: 'Pilih Supplier',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridlrkpjualpersupperqtypsuplier],
        buttons: [{
            text: 'Close',
            handler: function(){
                menulrkpjualpersupperqtysupp.hide();
            }
        }]
    }));

    Ext.ux.TwinComboLaprkpjualSupplier = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            strgridlrkpjualpersupperqtypsuplier .load();
            menulrkpjualpersupperqtysupp.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

	menulrkpjualpersupperqtysupp.on('hide', function(){
		var sf = Ext.getCmp('id_searchgridlrkpjualpersupperqtypsuplier').getValue();
		if( sf != ''){
			Ext.getCmp('id_searchgridlrkpjualpersupperqtypsuplier').setValue('');
			searchgridlrkpjualpersupperqtypsuplier.onTrigger2Click();
		}
	});

        var cblrkpjualpersuppperqtysuplier = new Ext.ux.TwinComboLaprkpjualSupplier({
        fieldLabel: 'Supplier',
        id: 'id_cblrkpjualpersuppperqtysuplier',
        store: strcblrkpjualpersuppperqtysuplier,
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
  var str_cblrkpjualpersuppperqtykat1 = new Ext.data.Store({
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
                var r = new (str_cblrkpjualpersuppperqtykat1.recordType)({
                    'kd_kategori1': '',
                    'nama_kategori1': '-----'
                });
                str_cblrkpjualpersuppperqtykat1.insert(0, r);
            },
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

        var cblrkpjualpersuppperqtykat1 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 1',
        id: 'id_cblrkpjualpersuppperqtykat1',
        store: str_cblrkpjualpersuppperqtykat1,
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
                var kdhp_cbkategori1 = cblrkpjualpersuppperqtykat1.getValue();
                cblrkpjualpersuppperqtykat2.store.proxy.conn.url = '<?= site_url("kategori3/get_kategori2") ?>/' + kdhp_cbkategori1;
                cblrkpjualpersuppperqtykat2.store.reload();
            }
        }
    });

    // combobox kategori2

        var str_cblrkpjualpersuppperqtykat2 = new Ext.data.Store({
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
                var r = new (str_cblrkpjualpersuppperqtykat2.recordType)({
                    'kd_kategori2': '',
                    'nama_kategori2': '-----'
                });
                str_cblrkpjualpersuppperqtykat2.insert(0, r);
            },
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

        var cblrkpjualpersuppperqtykat2 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 2',
        id: 'id_cblrkpjualpersuppperqtykat2',
        mode: 'local',
        store: str_cblrkpjualpersuppperqtykat2,
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
                var kd_hp_cbkategori1 = cblrkpjualpersuppperqtykat1.getValue();
                var kd_hp_cbkategori2 = this.getValue();
                cblrkpjualpersuppperqtykat3.setValue();
                cblrkpjualpersuppperqtykat3.store.proxy.conn.url = '<?= site_url("kategori4/get_kategori3") ?>/' + kd_hp_cbkategori1 +'/'+ kd_hp_cbkategori2;
                cblrkpjualpersuppperqtykat3.store.reload();
            }
        }
    });

     // combobox kategori3

    var str_cblrkpjualpersuppperqtykat3 = new Ext.data.Store({
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
                var r = new (str_cblrkpjualpersuppperqtykat3.recordType)({
                    'kd_kategori3': '',
                    'nama_kategori3': '-----'
                });
                str_cblrkpjualpersuppperqtykat3.insert(0, r);
            },
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    var cblrkpjualpersuppperqtykat3 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 3 ',
        id: 'id_cblrkpjualpersuppperqtykat3',
        mode: 'local',
        store: str_cblrkpjualpersuppperqtykat3,
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
                var kd_hp_cbkategori1 = cblrkpjualpersuppperqtykat1.getValue();
                var kd_hp_cbkategori2 = cblrkpjualpersuppperqtykat2.getValue();
                var kd_hp_cbkategori3 = this.getValue();
                cblrkpjualpersuppperqtykat4.setValue();
                cblrkpjualpersuppperqtykat4.store.proxy.conn.url = '<?= site_url("master_barang/get_kategori4") ?>/' + kd_hp_cbkategori1 +'/'+ kd_hp_cbkategori2 +'/'+ kd_hp_cbkategori3;
                cblrkpjualpersuppperqtykat4.store.reload();
            }
        }
    });

    // combobox kategori4

    var str_cblrkpjualpersuppperqtykat4 = new Ext.data.Store({
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
                var r = new (str_cblrkpjualpersuppperqtykat4.recordType)({
                    'kd_kategori4': '',
                    'nama_kategori4': '-----'
                });
                str_cblrkpjualpersuppperqtykat4.insert(0, r);
            },
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    var cblrkpjualpersuppperqtykat4 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 4 ',
        id: 'id_cblrkpjualpersuppperqtykat4',
        mode: 'local',
        store: str_cblrkpjualpersuppperqtykat4,
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
		var lrpjpspqsortorder = new Ext.form.Checkbox({
			xtype: 'checkbox',
			fieldLabel: 'Sort Order',
			boxLabel:'Tgl',
			name:'sort_order',
			id:'id_lrpjpspqsortorder',
			checked: true,
			inputValue: '1',
			autoLoad : true
		});

        var headerlaprkpprodjualpersupperqty = {
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
                                                                        }, cblrkpjualpersuppperqtykat1,
                                                                           cblrkpjualpersuppperqtykat2,
                                                                           cblrkpjualpersuppperqtykat3,
                                                                           cblrkpjualpersuppperqtykat4

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
									},cblrkpjualpersuppperqtysuplier,
                                                                          cblrkpjualpersuppperqtyproduk,
                                                                          lrpjpspqsortorder

								]
							}

						]
					}
				]
			}]
        }
        ]
    }


    var headerlaprkpprdjualpersupperqty = {
            buttonAlign: 'left',
            layout: 'form',
            border: false,
            labelWidth: 100,
			defaults: { labelSeparator: ''},
                items: [headerlaprkpprodjualpersupperqty,

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
				winlaprkpprdjualpersupperqtyprint.show();
                               // Ext.getDom('laprkpprdjualpersupperqtyprint').src = '<?= site_url("laporan_penjualan_per_kategori1/print_form") ?>' + '/' + kd_user + '/' + kd_shift + '/' + kd_member + '/' + dari_tgl + '/' + sampai_tgl;
				Ext.getDom('laprkpprdjualpersupperqtyprint').src = '<?= site_url("laporan_penjualan_per_kategori1/print_form") ?>';
			}
        },{
			text: 'Cancel',
			handler: function(){
				clearlaprkpprdjualpersupperqty();
			}
		}]
    };
        var winlaprkpprdjualpersupperqtyprint = new Ext.Window({
        id: 'id_winlaprkpprdjualpersupperqtyprint',
	Title: 'Print Rekap Produk Penjualan PerSupplier Per Qty',
        closeAction: 'hide',
        width: 900,
        height: 450,
        layout: 'fit',
        border: false,
        html:'<iframe style="width:100%;height:390px;" id="laprkpprdjualpersupperqtyprint" src=""></iframe>'
    });


       var laprkpprdjualpersupperqty = new Ext.FormPanel({
	 	id: 'rpt_rkp_produk_jual_persupp_qty',
		border: false,
		frame: true,
		monitorValid: true,
		labelWidth: 130,
        items: [{
                    bodyStyle: {
                        margin: '0px 0px 15px 0px'
                    },
                    items: [headerlaprkpprdjualpersupperqty]
                }
        ]
    });

	function clearlaprkpprdjualpersupperqty(){
		Ext.getCmp('rpt_rkp_produk_jual_persupp_qty').getForm().reset();
	}
</script>