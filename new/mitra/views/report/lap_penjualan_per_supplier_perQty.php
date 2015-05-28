<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">
    // Twin Combo Produk
    var strcbljualpersuppqtyproduk = new Ext.data.ArrayStore({
        fields: ['kd_produk'],
        data : []
        });

    var strgridljualpersuppqtyproduk = new Ext.data.Store({
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

        var searchgridljualpersuppqtyproduk = new Ext.app.SearchField({
            store: strgridljualpersuppqtyproduk,
            params: {
            start: STARTPAGE,
            limit: ENDPAGE
            },
            width: 350,
            id: 'id_searchgridljualpersuppqtyproduk'
        });


        var gridljualpersuppqtyproduk = new Ext.grid.GridPanel({
        store: strgridljualpersuppqtyproduk,
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
            items: [searchgridljualpersuppqtyproduk]
	    }),
            bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridljualpersuppqtyproduk,
            displayInfo: true
        }),
		listeners: {
			'rowdblclick': function(){
				var sm = this.getSelectionModel();
				var sel = sm.getSelections();
				if (sel.length > 0) {
                    Ext.getCmp('id_cbljualpersuppqtyproduk').setValue(sel[0].get('kd_produk'));
                    menuljualpersuppqtyproduk.hide();
				}
			}
		}
    });
    Ext.ux.TwinComboLjpsqProduk = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
        strgridljualpersuppqtyproduk.load();
        menuljualpersuppqtyproduk.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

        var menuljualpersuppqtyproduk = new Ext.menu.Menu();
        menuljualpersuppqtyproduk.add(new Ext.Panel({
        title: 'Pilih Produk',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridljualpersuppqtyproduk],
        buttons: [{
            text: 'Close',
            handler: function(){
            menuljualpersuppqtyproduk.hide();
            }
        }]
    }));

	menuljualpersuppqtyproduk.on('hide', function(){
		var sf = Ext.getCmp('id_searchgridljualpersuppqtyproduk').getValue();
		if( sf != ''){
			Ext.getCmp('id_searchgridljualpersuppqtyproduk').setValue('');
			searchgridljualpersuppqtyproduk.onTrigger2Click();
		}
	});

    var cbljualpersuppqtyproduk = new Ext.ux.TwinComboLjpsqProduk({
        id: 'id_cbljualpersuppqtyproduk',
        fieldLabel: 'Produk',
        store: strcbljualpersuppqtyproduk,
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
        var strcbljualpersuppqtysuplier = new Ext.data.ArrayStore({
        fields: ['kd_supplier'],
        data : []
        });

        var strgridljualpersuppqtysuplier = new Ext.data.Store({
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

        var searchgridljualpersuppqtysuplier = new Ext.app.SearchField({
        store: strgridljualpersuppqtysuplier ,
        params: {
        start: STARTPAGE,
        limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgridljualpersuppqtysuplier'
    });
        var gridljualpersuppqtysuplier = new Ext.grid.GridPanel({
        store: strgridljualpersuppqtysuplier ,
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
	        items: [searchgridljualpersuppqtysuplier]
	    }),
            bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridljualpersuppqtysuplier,
            displayInfo: true
        }),
		listeners: {
			'rowdblclick': function(){
				var sm = this.getSelectionModel();
				var sel = sm.getSelections();
				if (sel.length > 0) {
                    Ext.getCmp('id_cbljualpersuppqtysuplier').setValue(sel[0].get('kd_supplier'));
                    menuljualpersuppqtysuplier.hide();
				}
			}
		}
    });

        var menuljualpersuppqtysuplier = new Ext.menu.Menu();
        menuljualpersuppqtysuplier.add(new Ext.Panel({
        title: 'Pilih Supplier',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridljualpersuppqtysuplier],
        buttons: [{
            text: 'Close',
            handler: function(){
                menuljualpersuppqtysuplier.hide();
            }
        }]
    }));

    Ext.ux.TwinComboSupplierPerqty = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            strgridljualpersuppqtysuplier .load();
            menuljualpersuppqtysuplier.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

	menuljualpersuppqtysuplier.on('hide', function(){
		var sf = Ext.getCmp('id_searchgridljualpersuppqtysuplier').getValue();
		if( sf != ''){
			Ext.getCmp('id_searchgridljualpersuppqtysuplier').setValue('');
			searchgridljualpersuppqtysuplier.onTrigger2Click();
		}
	});

        var cbljualpersuppqtysuplier = new Ext.ux.TwinComboSupplierPerqty({
        fieldLabel: 'Supplier',
        id: 'id_cbljualpersuppqtysuplier',
        store: strcbljualpersuppqtysuplier,
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
  var str_cbljualpersuppqtykategori1 = new Ext.data.Store({
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
                var r = new (str_cbljualpersuppqtykategori1.recordType)({
                    'kd_kategori1': '',
                    'nama_kategori1': '-----'
                });
                str_cbljualpersuppqtykategori1.insert(0, r);
            },
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

        var cbljualpersuppqtykategori1 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 1',
        id: 'id_cbljualpersuppqtykategori1',
        store: str_cbljualpersuppqtykategori1,
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
                var kdhp_cbkategori1 = cbljualpersuppqtykategori1.getValue();
                cbljualpersuppqtykategori2.store.proxy.conn.url = '<?= site_url("kategori3/get_kategori2") ?>/' + kdhp_cbkategori1;
                cbljualpersuppqtykategori2.store.reload();
            }
        }
    });

    // combobox kategori2

        var str_cbljualpersuppqtykategori2 = new Ext.data.Store({
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
                var r = new (str_cbljualpersuppqtykategori2.recordType)({
                    'kd_kategori2': '',
                    'nama_kategori2': '-----'
                });
                str_cbljualpersuppqtykategori2.insert(0, r);
            },
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

        var cbljualpersuppqtykategori2 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 2',
        id: 'id_cbljualpersuppqtykategori2',
        mode: 'local',
        store: str_cbljualpersuppqtykategori2,
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
                var kd_hp_cbkategori1 = cbljualpersuppqtykategori1.getValue();
                var kd_hp_cbkategori2 = this.getValue();
                cbljualpersuppqtykategori3.setValue();
                cbljualpersuppqtykategori3.store.proxy.conn.url = '<?= site_url("kategori4/get_kategori3") ?>/' + kd_hp_cbkategori1 +'/'+ kd_hp_cbkategori2;
                cbljualpersuppqtykategori3.store.reload();
            }
        }
    });

     // combobox kategori3

    var str_cbljualpersuppqtykategori3 = new Ext.data.Store({
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
                var r = new (str_cbljualpersuppqtykategori3.recordType)({
                    'kd_kategori3': '',
                    'nama_kategori3': '-----'
                });
                str_cbljualpersuppqtykategori3.insert(0, r);
            },
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    var cbljualpersuppqtykategori3 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 3 ',
        id: 'id_cbljualpersuppqtykategori3',
        mode: 'local',
        store: str_cbljualpersuppqtykategori3,
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
                var kd_hp_cbkategori1 = cbljualpersuppqtykategori1.getValue();
                var kd_hp_cbkategori2 = cbljualpersuppqtykategori2.getValue();
                var kd_hp_cbkategori3 = this.getValue();
                cbljualpersuppqtykategori4.setValue();
                cbljualpersuppqtykategori4.store.proxy.conn.url = '<?= site_url("master_barang/get_kategori4") ?>/' + kd_hp_cbkategori1 +'/'+ kd_hp_cbkategori2 +'/'+ kd_hp_cbkategori3;
                cbljualpersuppqtykategori4.store.reload();
            }
        }
    });

    // combobox kategori4

    var str_cbljualpersuppqtykategori4 = new Ext.data.Store({
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
                var r = new (str_cbljualpersuppqtykategori4.recordType)({
                    'kd_kategori4': '',
                    'nama_kategori4': '-----'
                });
                str_cbljualpersuppqtykategori4.insert(0, r);
            },
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    var cbljualpersuppqtykategori4 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 4 ',
        id: 'id_cbljualpersuppqtykategori4',
        mode: 'local',
        store: str_cbljualpersuppqtykategori4,
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
		var ljpsqsortorder = new Ext.form.Checkbox({
			xtype: 'checkbox',
			fieldLabel: 'Sort Order',
			boxLabel:'Tgl',
			name:'sort_order',
			id:'id_ljpsqsortorder',
			checked: true,
			inputValue: '1',
			autoLoad : true
		});

        var headerlapjualperqty = {
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
                                                                        }, cbljualpersuppqtykategori1,
                                                                           cbljualpersuppqtykategori2,
                                                                           cbljualpersuppqtykategori3,
                                                                           cbljualpersuppqtykategori4

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
									},cbljualpersuppqtysuplier,
                                                                          cbljualpersuppqtyproduk,
                                                                          ljpsqsortorder

								]
							}

						]
					}
				]
			}]
        }
        ]
    }


    var headerlaporanpenjualanpersuppqty = {
            buttonAlign: 'left',
            layout: 'form',
            border: false,
            labelWidth: 100,
			defaults: { labelSeparator: ''},
                items: [headerlapjualperqty,

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
				winlaporanpenjualanpersuppqtyprint.show();
                               // Ext.getDom('laporanpenjualanpersuppqtyprint').src = '<?= site_url("laporan_penjualan_per_kategori1/print_form") ?>' + '/' + kd_user + '/' + kd_shift + '/' + kd_member + '/' + dari_tgl + '/' + sampai_tgl;
				Ext.getDom('laporanpenjualanpersuppqtyprint').src = '<?= site_url("laporan_penjualan_per_kategori1/print_form") ?>';
			}
        },{
			text: 'Cancel',
			handler: function(){
				clearlaporanpenjualanpersuppqty();
			}
		}]
    };
        var winlaporanpenjualanpersuppqtyprint = new Ext.Window({
        id: 'id_winlaporanpenjualanpersuppqtyprint',
	Title: 'Print Penjualan Per Supplier Per Qty',
        closeAction: 'hide',
        width: 900,
        height: 450,
        layout: 'fit',
        border: false,
        html:'<iframe style="width:100%;height:390px;" id="laporanpenjualanpersuppqtyprint" src=""></iframe>'
    });


       var laporanpenjualanpersuppqty= new Ext.FormPanel({
	 	id: 'rpt_penjualan_persupplier_perqty',
		border: false,
		frame: true,
		monitorValid: true,
		labelWidth: 130,
        items: [{
                    bodyStyle: {
                        margin: '0px 0px 15px 0px'
                    },
                    items: [headerlaporanpenjualanpersuppqty]
                }
        ]
    });

	function clearlaporanpenjualanpersuppqty(){
		Ext.getCmp('rpt_penjualan_persupplier_perqty').getForm().reset();
	}
</script>