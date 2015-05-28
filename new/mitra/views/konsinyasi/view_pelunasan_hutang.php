<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script>
    // start COMBOBOX NO BUKTI PELUNASAN
	var strcbvinnobuktipelunasan_kons = new Ext.data.ArrayStore({
		fields: ['no_bukti'],
		data: []
	});
	var strgridvphknobukti = new Ext.data.Store({
		reader: new Ext.data.JsonReader({
			fields: ['no_bukti','tanggal'],
			root: 'data',
			totalProperty: 'record'
		}),
		proxy: new Ext.data.HttpProxy({
			url: '<?= site_url("konsinyasi_view_pelunasan/search_nobukti") ?>',
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
	var searchgridvphknobukti = new Ext.app.SearchField({
		store: strgridvphknobukti,
		params: {
			start: STARTPAGE,
			limit: ENDPAGE
		},
		width: 350,
		id: 'id_searchgridvphknobukti'
	});
	var gridvinnobuktikons = new Ext.grid.GridPanel({
		store: strgridvphknobukti,
		stripeRows: true,
		frame: true,
		border: true,
		columns: [ {
			header: 'No Bukti',
			dataIndex: 'no_bukti',
			width: 100,
			sortable: true
		},{
			header: 'Tanggal',
			dataIndex: 'tanggal',
			width: 80,
			sortable: true

		},],
		tbar: new Ext.Toolbar({
			items: [searchgridvphknobukti]
		}),
		bbar: new Ext.PagingToolbar({
			pageSize: ENDPAGE,
			store: strgridvphknobukti,
			displayInfo: true
		}),
		listeners: {
			'rowdblclick': function () {
				var sm = this.getSelectionModel();
				var sel = sm.getSelections();
				if (sel.length > 0) {
					Ext.getCmp('id_cbvinnobuktipelunasan_kons').setValue(sel[0].get('no_bukti'));
					menuvphknobukti.hide();
				}
			}
		}
	});
	var menuvphknobukti = new Ext.menu.Menu();
	menuvphknobukti.add(new Ext.Panel({
		title: 'Pilih No Bukti',
		layout: 'fit',
		buttonAlign: 'left',
		modal: true,
		width: 400,
		height: 300,
		closeAction: 'hide',
		plain: true,
		items: [gridvinnobuktikons],
		buttons: [{
			text: 'Close',
			handler: function () {
				menuvphknobukti.hide();
			}
		}]
	}));
	Ext.ux.TwinCombonobuktiKons = Ext.extend(Ext.form.ComboBox, {
		initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
		getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
		initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
		onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
		trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
		onTrigger2Click: function () {
			//load store grid
			strgridvphknobukti.load();
			menuvphknobukti.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
		},
		validationEvent: false,
		validateOnBlur: false,
		trigger1Class: 'x-form-clear-trigger',
		trigger2Class: 'x-form-search-trigger',
		hideTrigger1: true
	});
	menuvphknobukti.on('hide', function () {
		var sf = Ext.getCmp('id_searchgridvphknobukti').getValue();
		if (sf !== '') {
			Ext.getCmp('id_searchgridvphknobukti').setValue('');
			searchgridvphknobukti.onTrigger2Click();
		};
            }
        );
	var cbvinnobuktipelunasan_kons = new Ext.ux.TwinCombonobuktiKons({
		fieldLabel: 'No Bukti',
		id: 'id_cbvinnobuktipelunasan_kons',
		store: strcbvinnobuktipelunasan_kons,
		mode: 'local',
		valueField: 'no_bukti',
		displayField: 'no_bukti',
		typeAhead: true,
		triggerAction: 'all',
		// allowBlank: false,
		editable: false,
		anchor: '90%',
		hiddenName: 'no_bukti',
		emptyText: 'Pilih No Bukti'
	});
    // end COMBOBOX NO BUKTI PELUNASAN
     // twin combo supplier
    var str_vphk_supplier = new Ext.data.ArrayStore({
        fields: ['nama_supplier'],
        data: []
    });

    var strgrid_vphk_supplier = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_supplier', 'nama_supplier', 'pkp'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("pembelian_retur/search_supplier") ?>',
            method: 'POST'
        }),
        listeners: {
            loadexception: function(event, options, response, error) {
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    var searchgrid_vphk_supplier = new Ext.app.SearchField({
        store: strgrid_vphk_supplier,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgrid_vphk_supplier'
    });


    var grid_vphk_supplier = new Ext.grid.GridPanel({
        store: strgrid_vphk_supplier,
        stripeRows: true,
        frame: true,
        border: true,
        columns: [{
                header: 'Kode Supplier',
                dataIndex: 'kd_supplier',
                width: 100,
                sortable: true
            }, {
                header: 'Nama Supplier',
                dataIndex: 'nama_supplier',
                width: 170,
                sortable: true
            }, {
                header: 'Status PKP',
                dataIndex: 'pkp',
                width: 100,
                sortable: true
            }],
        tbar: new Ext.Toolbar({
            items: [searchgrid_vphk_supplier]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgrid_vphk_supplier,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('id_cbvphksuplier').setValue(sel[0].get('nama_supplier'));
                    Ext.getCmp('vphk_kd_supplier').setValue(sel[0].get('kd_supplier'));
                    //strpembelianretur.removeAll();
                    menu_vphk_supplier.hide();
                }
            }
        }
    });

    var menu_vphk_supplier = new Ext.menu.Menu();
    menu_vphk_supplier.add(new Ext.Panel({
        title: 'Pilih Supplier',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [grid_vphk_supplier],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menu_vphk_supplier.hide();
                }
            }]
    }));

    Ext.ux.TwinComboVPHKSupplier = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            //load store grid
            strgrid_vphk_supplier.load();
            menu_vphk_supplier.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
                trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    menu_vphk_supplier.on('hide', function() {
        var sf = Ext.getCmp('id_searchgrid_vphk_supplier').getValue();
        if (sf != '') {
            Ext.getCmp('id_searchgrid_vphk_supplier').setValue('');
            searchgrid_vphk_supplier.onTrigger2Click();
        }
    });

    var cbvphksuplier = new Ext.ux.TwinComboVPHKSupplier({
        fieldLabel: 'Nama Supplier',
        id: 'id_cbvphksuplier',
        store: str_vphk_supplier,
        mode: 'local',
        valueField: 'nama_supplier',
        displayField: 'nama_supplier',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: true,
        editable: false,
        anchor: '90%',
        hiddenName: 'nama_supplier',
        emptyText: 'Pilih Supplier'
    });
    //end twincombosupplier
    // start GRID VIEW Pelunasan Hutang	
	var strkonsinyasi_view_pelunasan = new Ext.data.Store({
		reader: new Ext.data.JsonReader({
			fields: [
				'no_bukti',
				'tanggal',
				'kd_produk',
				'nama_produk',
                                'rp_sisa_invoice',
                                'no_invoice',
				'qty',
                                'nama_supplier',
                                'rp_total',
                                'rp_bayar',
                                'rp_diskon',
                                'rp_total_dibayar',
                                'kd_supplier',
                                'rp_total_invoice',
                                'rp_total_potongan'
			],
			root: 'data',
			totalProperty: 'record'
		}),
		proxy: new Ext.data.HttpProxy({
			url: '<?= site_url("view_pelunasan_hutang/get_rows") ?>',
			method: 'POST'
		}),
		listeners: {
			loadexception: function (event, options, response, error) {

			}
		}
	});
	var searchkonsinyasi_view_pelunasan = new Ext.app.SearchField({
		store: strkonsinyasi_view_pelunasan,
		params: {
			start: STARTPAGE,
			limit: ENDPAGE
		},
		width: 220,
		emptyText: 'No Bukti, Kode Produk',
		id: 'idsearchkonsinyasi_view_pelunasan'
	});
	var tbkonsinyasi_view_pelunasan= new Ext.Toolbar({
		items: [searchkonsinyasi_view_pelunasan]
	});
	var smkonsinyasi_view_pelunasan = new Ext.grid.CheckboxSelectionModel();
        
	var gridkonsinyasi_view_pelunasan = new Ext.grid.EditorGridPanel({
		id: 'gridkonsinyasi_view_pelunasan',
		frame: true,
		border: true,
		stripeRows: true,
		sm: smkonsinyasi_view_pelunasan,
		store: strkonsinyasi_view_pelunasan,
		loadMask: false,
		style: 'margin:0 auto;',
		height: 400,
		columns: [{
                        header: "No Bukti",
                        dataIndex: 'no_bukti',
                        // hidden: true,
                        sortable: true,
                        width: 150
                        }, {
                            header: "Kode Supplier",
                            dataIndex: 'kd_supplier',
                            sortable: true,
                            width: 150
                        }, {
                            header: "Nama Supplier",
                            dataIndex: 'nama_supplier',
                            sortable: true,
                            width: 250
                        }, {
                            header: "Tanggal Pembayaran",
                            dataIndex: 'tanggal',
                            sortable: true,
                            width: 100
                        }, {
                            xtype: 'numbercolumn',
                            header: "Total Invoice",
                            dataIndex: 'rp_total_invoice',
                            sortable: true,
                            format :'0,0',
                            width: 100
                        }, {
                            xtype: 'numbercolumn',
                            header: "Total Potongan",
                            dataIndex: 'rp_total_potongan',
                            sortable: true,
                            format :'0,0',
                            width: 120
                        },{
                            xtype: 'numbercolumn',
                            header: "Total Dibayar",
                            dataIndex: 'rp_total_dibayar',
                            sortable: true,
                            format :'0,0',
                            width: 90
                        },{
                            xtype: 'numbercolumn',
                            header: "Total Bayar",
                            dataIndex: 'rp_total',
                            sortable: true,
                            format :'0,0',
                            width: 90
                        }],
		listeners: {
			'rowdblclick': function () {
				var sm = gridkonsinyasi_view_pelunasan.getSelectionModel();
				var sel = sm.getSelections();
				
				if (sel.length > 0) {

                    Ext.Ajax.request({
                        url: '<?= site_url("view_pelunasan_hutang/get_data_pelunasan") ?>/' + sel[0].get('no_bukti'),
                        method: 'POST',
                        params: {},
                        callback: function (opt, success, responseObj) {
                            var windowviewreturbeli = new Ext.Window({
                                title: 'View Pelunasan Hutang',
                                width: 850,
                                height: 500,
                                autoScroll: true,
                                html: responseObj.responseText
                            });

                            windowviewreturbeli.show();

                        }
                    });
                }
			}
		},
		tbar: tbkonsinyasi_view_pelunasan,
		bbar: new Ext.PagingToolbar({
			pageSize: ENDPAGE,
			store: strkonsinyasi_view_pelunasan,
			displayInfo: true
		})
	});
    // end Grid View Pelunasan Hutang
    // Start Header View Pelunasan Hutang
   var headerkonsinyasi_view_pelunasan = {
		layout: 'column',
		border: false,
		buttonAlign: 'left',
		items: [{
			columnWidth: .5,
			layout: 'form',
			border: false,
			labelWidth: 100,
			defaults: {labelSeparator: ''},
			items: [ cbvinnobuktipelunasan_kons,//cbvrbproduk,
                                {
					xtype: 'datefield',
					fieldLabel: 'Tgl Awal',
					emptyText: 'Tanggal Awal',
					name: 'tgl_pelunasan_awal',
					id: 'vphk_tgl_retur_awal',
					maxLength: 255,
					anchor: '90%',
					value: '',
					format: 'd-M-Y'
				}
			]
		},{
			columnWidth: .5,
			layout: 'form',
			border: false,
			labelWidth: 100,
			defaults: {labelSeparator: ''},
			items: [ cbvphksuplier,
                               {
                                xtype: 'hidden',
                                name: 'kd_supplier',
                                id: 'vphk_kd_supplier',
                                value: ''
                                },
                                {
					xtype: 'datefield',
					fieldLabel: 'Tgl Akhir',
					emptyText: 'Tanggal Akhir',
					name: 'tgl_pelunasan_akhir',
					id: 'vphk_tgl_retur_akhir',
					maxLength: 255,
					anchor: '90%',
					value: '',
					format: 'd-M-Y'
				}//,cbvrjmember
			]
		}],
		buttons: [{
			text: 'Filter',
			formBind: true,
			handler: function () {
				gridkonsinyasi_view_pelunasan.store.load({
					params: {
						no_bukti: Ext.getCmp('id_cbvinnobuktipelunasan_kons').getValue(),
						tgl_awal: Ext.getCmp('vphk_tgl_retur_awal').getValue(),
						tgl_akhir: Ext.getCmp('vphk_tgl_retur_akhir').getValue(),
						//kd_produk: Ext.getCmp('id_cbvrbproduk').getValue(),
						kd_supplier: Ext.getCmp('vphk_kd_supplier').getValue()
						
					}
				});
			}
		}, {
			text: 'Reset',
			formBind: true,
			handler: function () {
				Ext.getCmp('id_cbvrbproduk').setValue('');
                                Ext.getCmp('id_cbvinnobuktipelunasan_kons').setValue('');
                                Ext.getCmp('id_cbvphksuplier').setValue('');
                                Ext.getCmp('vphk_tgl_retur_awal').setValue('');
                                Ext.getCmp('vphk_tgl_retur_akhir').setValue('');
                                Ext.getCmp('vphk_kd_supplier').setValue('');
				gridkonsinyasi_view_pelunasan.store.removeAll();
			}
		}]
	};
    //End Header View Pelunasan Hutang
    
    // FORM PANEL
    var konsinyasi_view_pelunasan = new Ext.FormPanel({
		id: 'konsinyasi_view_pelunasan',
		border: false,
		frame: true,
		//autoScroll:true,	 
		bodyStyle: 'padding-right:20px;',
		labelWidth: 130,
		items: [{
				bodyStyle: {
					margin: '10px 0px 15px 0px'
				},
				items: [headerkonsinyasi_view_pelunasan]                             
                        },gridkonsinyasi_view_pelunasan ]
		}); 
</script>
