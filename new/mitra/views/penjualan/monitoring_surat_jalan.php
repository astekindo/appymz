<?php if (!defined( 'BASEPATH')) exit( 'No direct script access allowed'); ?>
<script type="text/javascript">

	// start COMBOBOX Sales Order
    var strCbMsjSalesOrder = new Ext.data.ArrayStore({
        fields: ['no_so'],
        data: []
    });
    var strgridCbMsjSalesOrder = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['no_so', 'tgl_so'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("monitoring_surat_jalan/get_no_so") ?>',
            method: 'POST'
        }),
        listeners: {

            loadexception: function (event, options, response, error) {
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });
    var searchgridCbMsjSalesOrder = new Ext.app.SearchField({
        store: strgridCbMsjSalesOrder,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgridmsjsalesorder'
    });
    var gridCbMsjSalesOrder = new Ext.grid.GridPanel({
        store: strgridCbMsjSalesOrder,
        stripeRows: true,
        frame: true,
        border: true,
        columns: [{
            header: 'NO SO',
            dataIndex: 'no_so',
            width: 120,
            sortable: true,

        }, {
            header: 'Tanggal So',
            dataIndex: 'tgl_so',
            width: 200,
            sortable: true,
        }],
        tbar: new Ext.Toolbar({
            items: [searchgridCbMsjSalesOrder]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridCbMsjSalesOrder,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function () {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('idCbMsjSalesOrder').setValue(sel[0].get('no_so'));
                    // Ext.getCmp('mpo_nama_supplier').setValue(sel[0].get('nama_supplier'));
                    // strlaporanpenerimaanbarang.removeAll();       
                    menuMsjSO.hide();
                }
            }
        }
    });
    var menuMsjSO = new Ext.menu.Menu();
    menuMsjSO.add(new Ext.Panel({
        title: 'Pilih Sales Order',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridCbMsjSalesOrder],
        buttons: [{
            text: 'Close',
            handler: function () {
                menuMsjSO.hide();
            }
        }]
    }));
    Ext.ux.TwinComboSalesOrderMSJ = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function () {
            //load store grid
            strgridCbMsjSalesOrder.load();
            menuMsjSO.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
    menuMsjSO.on('hide', function () {
        var sf = Ext.getCmp('id_searchgridmsjsalesorder').getValue();
        if (sf != '') {
            Ext.getCmp('id_searchgridmsjsalesorder').setValue('');
            searchgridCbMsjSalesOrder.onTrigger2Click();
        }
    });
    var cbMsjSalesOrder = new Ext.ux.TwinComboSalesOrderMSJ({
        fieldLabel: 'No Sales Order',
        id: 'idCbMsjSalesOrder',
        store: strCbMsjSalesOrder,
        mode: 'local',
        valueField: 'no_so',
        displayField: 'no_so',
        typeAhead: true,
        triggerAction: 'all',
        // allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'no_so',
        emptyText: 'Pilih No SO'
    });
	// end COMBOBOX Sales 
	
	// start GRID Sales Order	
	// GRID Sales Order Data Store
    var strmsjSOGrid = new Ext.data.GroupingStore({
        reader: new Ext.data.JsonReader({
            fields: [
                'no_so',
                'kd_member',
                'tgl_so',
                'kirim_so',
                'kirim_alamat_so',
                'kirim_telp_so',
                'kasir',
                'keterangan',
                'rp_total_bayar',
                'rp_kurang_bayar',
                'no_do',
                'tgl_do',
                'user_do',
                'keterangan_do',
                'is_do',
                'is_kirim',
                'rp_total',
                'rp_diskon',
                'rp_ekstra_diskon',
                'rp_harga',
                'kd_produk',
                'nama_produk',
                'qty',
                'nm_satuan'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("monitoring_surat_jalan/get_rows") ?>',
            method: 'POST'
        }),
        listeners: {
            loadexception: function (event, options, response, error) {
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        },
                groupField: 'no_so'
    });
	strmsjSOGrid.on('load',function(){
        strmsjSOGrid.setBaseParam('no_so',Ext.getCmp('idCbMsjSalesOrder').getValue());
        strmsjSOGrid.setBaseParam('tgl_so',Ext.getCmp('id_msj_tgl_so').getValue());
        strmsjSOGrid.setBaseParam('tgl_do',Ext.getCmp('id_msj_tgl_do').getValue());
		strmsjSOGrid.setBaseParam('tgl_surat_jalan',Ext.getCmp('id_msj_tgl_surat_jalan').getValue());
    });
	// GRID Sales Order Search Field
    var searchmsjSOGrid = new Ext.app.SearchField({
        store: strmsjSOGrid,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        emptyText: 'Kirim SO, Kasir, No Kendaraan, Sopir',
        id: 'idsearchmsjSOGridorder'
    });
    // GRID Top Toolbar
    var tbmsjSOGridorder = new Ext.Toolbar({
        items: [searchmsjSOGrid]
    });
    
    var cbGrid = new Ext.grid.CheckboxSelectionModel();
	// GRID Sales Order
    var msjSOGrid = new Ext.grid.EditorGridPanel({
        id: 'id-msjSOGrid-gridpanel',
        frame: true,
        border: true,
        stripeRows: true,
        store: strmsjSOGrid,
        sm: cbGrid,
        loadMask: true,
        style: 'margin:20 auto;',
        height: 250,
	title: 'Sales Order',
        collapsed: false,
        collapsible: true,
        anchor: '95%',
        view: new Ext.grid.GroupingView({
            forceFit: true,
            groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "Items" : "Item"]})'
        }),
        columns: [{
            header: "No SO",
            dataIndex: 'no_so',
            sortable: true,
            width: 150
        }, {
            header: "Rp.Total Bayar",
            dataIndex: 'rp_total_bayar',
            sortable: true,
            width: 100
        },{
            header: "Rp. Kurang Bayar",
            dataIndex: 'rp_kurang_bayar',
            sortable: true,
            width: 100
        },{
            header: "Kode Produk",
            dataIndex: 'kd_produk',
            sortable: true,
            width: 100
        },{
            header: "Nama Produk",
            dataIndex: 'nama_produk',
            sortable: true,
            width: 100
        },{
            header: "Qty",
            dataIndex: 'qty',
            sortable: true,
            width: 100
        },{
            header: "Satuan",
            dataIndex: 'nm_satuan',
            sortable: true,
            width: 100
        },{
            header: "Harga",
            dataIndex: 'rp_harga',
            sortable: true,
            width: 100
        }, {
            header: "Rp Diskon",
            dataIndex: 'rp_diskon',
            sortable: true,
            width: 80
        }, {
            header: "Rp Total",
            dataIndex: 'rp_total',
            sortable: true,
            width: 140
        }, {
            header: "Kirim",
            dataIndex: 'is_kirim',
            sortable: true,
            width: 100
        },{
            header: "Telepon Kirim",
            dataIndex: 'kirim_telp_so',
            sortable: true,
            width: 70
        },  {
            header: "Ekstra Diskon",
            dataIndex: 'rp_ekstra_diskon',
            sortable: true,
            width: 70
        }, {
            header: "Is DO",
            dataIndex: 'is_do',
            sortable: true,
            width: 80
        }, {
            header: "Tgl SO",
            dataIndex: 'tgl_so',
            sortable: true,
            width: 200
        },   {
            header: "Kode Member",
            dataIndex: 'kd_member',
            sortable: true,
            width: 100
        }, {
            header: "Kirim So",
            dataIndex: 'kirim_so',
            sortable: true,
            width: 80
        }, {
            header: "Kirim Alamat SO",
            dataIndex: 'kirim_alamat_so',
            sortable: true,
            width: 80
        }, {
            header: "Kirim Telepon SO",
            dataIndex: 'kirim_telp_so',
            sortable: true,
            width: 150
        }, {
            header: "Kasir",
            dataIndex: 'kasir',
            sortable: true,
            width: 80
        }, {
            header: "Keterangan",
            dataIndex: 'keterangan',
            sortable: true,
            width: 100
        }],
         listeners: {
                'rowclick': function(){              
                    var sm = msjSOGrid.getSelectionModel();                
                    var sel = sm.getSelections(); 
                    //Ext.getCmp('no_so').setValue(sel[0].get('no_so'));
                    msjDOGrid.store.proxy.conn.url = '<?= site_url("monitoring_surat_jalan/get_rows") ?>/' + sel[0].get('no_so');
                    msjDOGrid.store.reload();
                
                }          
            },
        tbar: tbmsjSOGridorder,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strmsjSOGrid,
            displayInfo: true
        })
    });
	// end GRID Sales Order
        
	// start GRID Delivery Order	
	// GRID Delivery Order Data Store
    var strmsjDOGrid = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'no_so',
                'kd_member',
                'tgl_so',
                'kirim_so',
                'kirim_alamat_so',
                'kirim_telp_so',
                'kasir',
                'keterangan',
                'rp_total_bayar',
                'rp_kurang_bayar',
                'no_do',
                'tgl_do',
                'user_do',
                'keterangan_do',
                'tanggal_kirim',
                'no_sj',
                'tgl_sj',
                'kd_ekspedisi',
                'no_kendaraan',
                'sopir'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("monitoring_surat_jalan/get_rows") ?>',
            method: 'POST'
        }),
        listeners: {
            loadexception: function (event, options, response, error) {
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });
	strmsjDOGrid.on('load',function(){
      /*  strmsjDOGrid.setBaseParam('no_so',Ext.getCmp('idCbMsjDeliveryOrder').getValue());
        strmsjDOGrid.setBaseParam('tgl_so',Ext.getCmp('id_msj_tgl_so').getValue());
        strmsjDOGrid.setBaseParam('tgl_do',Ext.getCmp('id_msj_tgl_do').getValue());
	strmsjDOGrid.setBaseParam('tgl_surat_jalan',Ext.getCmp('id_msj_tgl_surat_jalan').getValue());
    */});
	// GRID Delivery Order Search Field
    var searchmsjDOGrid = new Ext.app.SearchField({
        store: strmsjDOGrid,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        emptyText: 'Kirim SO, Kasir, No Kendaraan, Sopir',
        id: 'idsearchmsjDOGridorder'
    });
    // GRID Top Toolbar
    var tbmsjDOGridorder = new Ext.Toolbar({
        items: [searchmsjDOGrid]
    });
    var smmsjDOGrid = new Ext.grid.CheckboxSelectionModel();
	// GRID Delivery Order
    var msjDOGrid = new Ext.grid.EditorGridPanel({
        id: 'id-msjDOGrid-gridpanel',
        frame: true,
        border: true,
        stripeRows: true,
        store: strmsjDOGrid,
        loadMask: true,
        style: 'margin:20 auto;',
        height: 250,
        sm : smmsjDOGrid,
	title: 'Delivery Order',
        collapsed: false,
        collapsible: true,
	anchor: '95%',
        columns: [{
            header: "No SO",
            dataIndex: 'no_so',
            sortable: true,
            width: 150
        }, {
            header: "Member",
            dataIndex: 'kd_member',
            sortable: true,
            width: 100
        }, {
            header: "Tanggal SO",
            dataIndex: 'tgl_so',
            sortable: true,
            width: 80
        }, {
            header: "Kirim SO",
            dataIndex: 'kirim_so',
            sortable: true,
            width: 140
        }, {
            header: "Kirim Alamat SO",
            dataIndex: 'kirim_alamat_so',
            sortable: true,
            width: 500
        }, {
            header: "Kirim Telp SO",
            dataIndex: 'kirim_telp_so',
            sortable: true,
            width: 150
        }, {
            header: "Kasir",
            dataIndex: 'kasir',
            sortable: true,
            width: 80
        }, {
            header: "Keterangan",
            dataIndex: 'keterangan',
            sortable: true,
            width: 200
        }, {
            header: "Rp. Total Bayar",
            dataIndex: 'rp_total_bayar',
            sortable: true,
            width: 100
        }, {
            header: "Rp. Kurang Bayar",
            dataIndex: 'rp_kurang_bayar',
            sortable: true,
            width: 100
        }, {
            header: "No. DO",
            dataIndex: 'no_do',
            sortable: true,
            width: 100
        }, {
            header: "Tgl. DO",
            dataIndex: 'tgl_do',
            sortable: true,
            width: 80
        }, {
            header: "User DO",
            dataIndex: 'user_do',
            sortable: true,
            width: 80
        }, {
            header: "Keterangan DO",
            dataIndex: 'keterangan_do',
            sortable: true,
            width: 150
        }, {
            header: "Tanggal Kirim",
            dataIndex: 'tanggal_kirim',
            sortable: true,
            width: 80
        }, {
            header: "No. SJ",
            dataIndex: 'no_sj',
            sortable: true,
            width: 100
        }, {
            header: "Tgl. SJ",
            dataIndex: 'tgl_sj',
            sortable: true,
            width: 80
        }, {
            header: "Kode Ekspedisi",
            dataIndex: 'kd_ekspedisi',
            sortable: true,
            width: 100
        }, {
            header: "No Kendaraan",
            dataIndex: 'no_kendaraan',
            sortable: true,
            width: 100
        }, {
            header: "Sopir",
            dataIndex: 'sopir',
            sortable: true,
            width: 100
        }],
            listeners: {
                'rowclick': function(){              
                    var sm = msjDOGrid.getSelectionModel();                
                    var sel = sm.getSelections(); 
                    //Ext.getCmp('no_so').setValue(sel[0].get('no_so'));
                    //msjDOGrid.store.proxy.conn.url = '<?= site_url("monitoring_surat_jalan/get_rows") ?>/' + sel[0].get('no_so');
                   msjSJGrid.store.proxy.conn.url = '<?= site_url("monitoring_surat_jalan/get_sj_rows") ?>' + sel[0].get('no_do');
                    msjSJGrid.store.reload();
                
                }          
            },
        tbar: tbmsjDOGridorder,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strmsjDOGrid,
            displayInfo: true
        })
    });
	// end GRID Delivery Order

	// start GRID Surat Jalan	
	// GRID Surat Jalan Data Store
    var strmsjSJGrid = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'no_so',
                'kd_member',
                'tgl_so',
                'kirim_so',
                'kirim_alamat_so',
                'kirim_telp_so',
                'kasir',
                'keterangan',
                'rp_total_bayar',
                'rp_kurang_bayar',
                'no_do',
                'tgl_do',
                'user_do',
                'keterangan_do',
                'tanggal_kirim',
                'no_sj',
                'tgl_sj',
                'kd_ekspedisi',
                'no_kendaraan',
                'sopir'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("monitoring_surat_jalan/get_rows") ?>',
            method: 'POST'
        }),
        listeners: {
            loadexception: function (event, options, response, error) {
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });
	strmsjSJGrid.on('load',function(){
      /*  strmsjSJGrid.setBaseParam('no_so',Ext.getCmp('idCbMsjDeliveryOrder').getValue());
        strmsjSJGrid.setBaseParam('tgl_so',Ext.getCmp('id_msj_tgl_so').getValue());
        strmsjSJGrid.setBaseParam('tgl_do',Ext.getCmp('id_msj_tgl_do').getValue());
	strmsjSJGrid.setBaseParam('tgl_surat_jalan',Ext.getCmp('id_msj_tgl_surat_jalan').getValue());
    */});
	// GRID Delivery Order Search Field
    var searchmsjSJGrid = new Ext.app.SearchField({
        store: strmsjSJGrid,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        emptyText: 'Kirim SO, Kasir, No Kendaraan, Sopir',
        id: 'idsearchmsjSJGridorder'
    });
    // GRID Top Toolbar
    var tbmsjSJGridorder = new Ext.Toolbar({
        items: [searchmsjSJGrid]
    });
    
    var smmsjSJGrid = new Ext.grid.CheckboxSelectionModel();
	// GRID Surat Jalan
    var msjSJGrid = new Ext.grid.EditorGridPanel({
        id: 'id-msjSJGrid-gridpanel',
        frame: true,
        border: true,
        stripeRows: true,
        store: strmsjSJGrid,
        loadMask: true,
        style: 'margin:20 auto;',
        height: 250,
        sm: smmsjSJGrid,
	title: 'Surat Jalan',
        collapsed: false,
        collapsible: true,
	anchor: '95%',
        columns: [{
            header: "No. DO",
            dataIndex: 'no_do',
            sortable: true,
            width: 100
        }, {
            header: "Tgl. DO",
            dataIndex: 'tgl_do',
            sortable: true,
            width: 80
        }, {
            header: "User DO",
            dataIndex: 'user_do',
            sortable: true,
            width: 80
        }, {
            header: "Keterangan DO",
            dataIndex: 'keterangan_do',
            sortable: true,
            width: 150
        }, {
            header: "Tanggal Kirim",
            dataIndex: 'tanggal_kirim',
            sortable: true,
            width: 80
        }, {
            header: "No. SJ",
            dataIndex: 'no_sj',
            sortable: true,
            width: 100
        }, {
            header: "Tgl. SJ",
            dataIndex: 'tgl_sj',
            sortable: true,
            width: 80
        }, {
            header: "Kode Ekspedisi",
            dataIndex: 'kd_ekspedisi',
            sortable: true,
            width: 100
        }, {
            header: "No Kendaraan",
            dataIndex: 'no_kendaraan',
            sortable: true,
            width: 100
        }, {
            header: "Sopir",
            dataIndex: 'sopir',
            sortable: true,
            width: 100
        }],

        tbar: tbmsjSJGridorder,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strmsjSJGrid,
            displayInfo: true
        })
    });
	// end GRID Surat Jalan

	// HEADER Monitoring Surat Jalan
    var headerMonitoringSuratJalan = {
        layout: 'column',
        border: false,
        items: [{
            columnWidth: .8,
            layout: 'form',
            border: false,
            labelWidth: 100,
            defaults: {
                labelSeparator: ''
            },
            items: [{
                xtype: 'fieldset',
                autoHeight: true,				
                items: [{
                    layout: 'column',
					buttonAlign:'left',
					border: false,
                    items: [{
                            columnWidth: .5,
                            layout: 'form',
                            border: false,
                            labelWidth: 100,
                            defaults: {
                                labelSeparator: ''
                            },
                            items: [
								cbMsjSalesOrder,
								{
									xtype: 'datefield',
									fieldLabel: 'Tanggal SO',
									name: 'tgl_so',
									id: 'id_msj_tgl_so',
									format: 'd-M-Y',
									editable: false,
									anchor: '90%'
								}								
                            ]
                        }, {
                            columnWidth: .5,
                            layout: 'form',
                            border: false,
                            labelWidth: 100,
                            defaults: {
                                labelSeparator: ''
                            },
                            items: [
								{
									xtype: 'datefield',
									fieldLabel: 'Tanggal DO',
									name: 'tgl_do',
									id: 'id_msj_tgl_do',
									format: 'd-M-Y',
									editable: false,
									anchor: '90%'
								},
								{
									xtype: 'datefield',
									fieldLabel: 'Tanggal SJ',
									name: 'tgl_surat_jalan',
									id: 'id_msj_tgl_surat_jalan',
									format: 'd-M-Y',
									editable: false,
									anchor: '90%'
								}								
							]
                        }

                    ],
					buttons: [
						{
							text: 'Filter',
							formBind: true,
							handler: function() {
								var kd_msj_tgl_do = Ext.getCmp('id_msj_tgl_do').getValue();
								var kd_msj_tgl_surat_jalan = Ext.getCmp('id_msj_tgl_surat_jalan').getValue();
								var kd_msj_tgl_so = Ext.getCmp('id_msj_tgl_so').getValue();
								var kd_CbMsjSalesOrder = Ext.getCmp('idCbMsjSalesOrder').getValue();
								
								msjSOGrid.store.reload({
									params: {
										start: STARTPAGE,
										limit: ENDPAGE,
										tgl_do: kd_msj_tgl_do,
										tgl_surat_jalan: kd_msj_tgl_surat_jalan,
										tgl_so: kd_msj_tgl_so,
										no_so: kd_CbMsjSalesOrder
									}
								});
							}
						},
						{
							text: 'Reset',
							formBind: true,
							handler: function() {
								Ext.getCmp('id_msj_tgl_do').setValue('');
								Ext.getCmp('id_msj_tgl_surat_jalan').setValue('');
								Ext.getCmp('id_msj_tgl_so').setValue('');
								Ext.getCmp('idCbMsjSalesOrder').setValue('');
								msjSOGrid.store.removeAll();
							}
						}
					]
                }]
            }]
        }]
    }


    // PANEL Monitoring SJ
    var monitoringSuratJalan = new Ext.FormPanel({
        id: 'monitoring_surat_jalan',
        border: false,
        frame: true,
        autoScroll: true,
        items: [{
                bodyStyle: {
                    margin: '10px 0px 15px 0px'
                },
                items: [headerMonitoringSuratJalan]
            },
                        msjSOGrid,
			msjDOGrid,
                        msjSJGrid
        ]
    });


</script>