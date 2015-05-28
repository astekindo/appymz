<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script>
    // start COMBOBOX NO BUKTI PELUNASAN
	var strcbvinnobuktipelunasan = new Ext.data.ArrayStore({
		fields: ['no_bukti'],
		data: []
	});
	var strgridvphnobukti = new Ext.data.Store({
		reader: new Ext.data.JsonReader({
			fields: ['no_bukti','tanggal'],
			root: 'data',
			totalProperty: 'record'
		}),
		proxy: new Ext.data.HttpProxy({
			url: '<?= site_url("view_pelunasan_hutang/search_nobukti") ?>',
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
	var searchgridvphnobukti = new Ext.app.SearchField({
		store: strgridvphnobukti,
		params: {
			start: STARTPAGE,
			limit: ENDPAGE
		},
		width: 350,
		id: 'id_searchgridvphnobukti'
	});
	var gridvinnobukti = new Ext.grid.GridPanel({
		store: strgridvphnobukti,
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
			items: [searchgridvphnobukti]
		}),
		bbar: new Ext.PagingToolbar({
			pageSize: ENDPAGE,
			store: strgridvphnobukti,
			displayInfo: true
		}),
		listeners: {
			'rowdblclick': function () {
				var sm = this.getSelectionModel();
				var sel = sm.getSelections();
				if (sel.length > 0) {
					Ext.getCmp('id_cbvinnobuktipelunasan').setValue(sel[0].get('no_bukti'));
					menuvphnobukti.hide();
				}
			}
		}
	});
	var menuvphnobukti = new Ext.menu.Menu();
	menuvphnobukti.add(new Ext.Panel({
		title: 'Pilih No Bukti',
		layout: 'fit',
		buttonAlign: 'left',
		modal: true,
		width: 400,
		height: 300,
		closeAction: 'hide',
		plain: true,
		items: [gridvinnobukti],
		buttons: [{
			text: 'Close',
			handler: function () {
				menuvphnobukti.hide();
			}
		}]
	}));
	Ext.ux.TwinCombonobukti = Ext.extend(Ext.form.ComboBox, {
		initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
		getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
		initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
		onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
		trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
		onTrigger2Click: function () {
			//load store grid
			strgridvphnobukti.load();
			menuvphnobukti.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
		},
		validationEvent: false,
		validateOnBlur: false,
		trigger1Class: 'x-form-clear-trigger',
		trigger2Class: 'x-form-search-trigger',
		hideTrigger1: true
	});
	menuvphnobukti.on('hide', function () {
		var sf = Ext.getCmp('id_searchgridvphnobukti').getValue();
		if (sf !== '') {
			Ext.getCmp('id_searchgridvphnobukti').setValue('');
			searchgridvphnobukti.onTrigger2Click();
		};
            }
        );
	var cbvinnobuktipelunasan = new Ext.ux.TwinCombonobukti({
		fieldLabel: 'No Bukti',
		id: 'id_cbvinnobuktipelunasan',
		store: strcbvinnobuktipelunasan,
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
    var str_vph_supplier = new Ext.data.ArrayStore({
        fields: ['nama_supplier'],
        data: []
    });

    var strgrid_vph_supplier = new Ext.data.Store({
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

    var searchgrid_vph_supplier = new Ext.app.SearchField({
        store: strgrid_vph_supplier,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgrid_vph_supplier'
    });


    var grid_vph_supplier = new Ext.grid.GridPanel({
        store: strgrid_vph_supplier,
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
            items: [searchgrid_vph_supplier]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgrid_vph_supplier,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('id_cbvphsuplier').setValue(sel[0].get('nama_supplier'));
                    Ext.getCmp('vph_kd_supplier').setValue(sel[0].get('kd_supplier'));
                    //strpembelianretur.removeAll();
                    menu_vph_supplier.hide();
                }
            }
        }
    });

    var menu_vph_supplier = new Ext.menu.Menu();
    menu_vph_supplier.add(new Ext.Panel({
        title: 'Pilih Supplier',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [grid_vph_supplier],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menu_vph_supplier.hide();
                }
            }]
    }));

    Ext.ux.TwinComboVPHSupplier = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            //load store grid
            strgrid_vph_supplier.load();
            menu_vph_supplier.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
                trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    menu_vph_supplier.on('hide', function() {
        var sf = Ext.getCmp('id_searchgrid_vph_supplier').getValue();
        if (sf != '') {
            Ext.getCmp('id_searchgrid_vph_supplier').setValue('');
            searchgrid_vph_supplier.onTrigger2Click();
        }
    });

    var cbvphsuplier = new Ext.ux.TwinComboVPHSupplier({
        fieldLabel: 'Nama Supplier',
        id: 'id_cbvphsuplier',
        store: str_vph_supplier,
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
	var strviewpelunasanhutang = new Ext.data.Store({
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
	var searchviewpelunasanhutang = new Ext.app.SearchField({
		store: strviewpelunasanhutang,
		params: {
			start: STARTPAGE,
			limit: ENDPAGE
		},
		width: 220,
		emptyText: 'No Bukti, Kode Produk',
		id: 'idsearchviewpelunasanhutang'
	});
        strviewpelunasanhutang.on('load',function(){
        strviewpelunasanhutang.setBaseParam('no_bukti',Ext.getCmp('id_cbvinnobuktipelunasan').getValue());
        strviewpelunasanhutang.setBaseParam('tgl_awal',Ext.getCmp('vph_tgl_retur_awal').getValue());
        strviewpelunasanhutang.setBaseParam('tgl_akhir',Ext.getCmp('vph_tgl_retur_akhir').getValue());
        strviewpelunasanhutang.setBaseParam('kd_supplier',Ext.getCmp('vph_kd_supplier').getValue());
        
    });
	var tbviewpelunasanhutang= new Ext.Toolbar({
		items: [searchviewpelunasanhutang]
	});
	var smviewpelunasanhutang = new Ext.grid.CheckboxSelectionModel();
        
	var gridviewpelunasanhutang = new Ext.grid.EditorGridPanel({
		id: 'gridviewpelunasanhutang',
		frame: true,
		border: true,
		stripeRows: true,
		sm: smviewpelunasanhutang,
		store: strviewpelunasanhutang,
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
				var sm = gridviewpelunasanhutang.getSelectionModel();
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
		tbar: tbviewpelunasanhutang,
		bbar: new Ext.PagingToolbar({
			pageSize: ENDPAGE,
			store: strviewpelunasanhutang,
			displayInfo: true
		})
	});
    // end Grid View Pelunasan Hutang
    // Start Header View Pelunasan Hutang
   var headerviewpelunasanhutang = {
		layout: 'column',
		border: false,
		buttonAlign: 'left',
		items: [{
			columnWidth: .5,
			layout: 'form',
			border: false,
			labelWidth: 100,
			defaults: {labelSeparator: ''},
			items: [ cbvinnobuktipelunasan,//cbvrbproduk,
                                {
					xtype: 'datefield',
					fieldLabel: 'Tgl Awal',
					emptyText: 'Tanggal Awal',
					name: 'tgl_pelunasan_awal',
					id: 'vph_tgl_retur_awal',
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
			items: [ cbvphsuplier,
                               {
                                xtype: 'hidden',
                                name: 'kd_supplier',
                                id: 'vph_kd_supplier',
                                value: ''
                                },
                                {
					xtype: 'datefield',
					fieldLabel: 'Tgl Akhir',
					emptyText: 'Tanggal Akhir',
					name: 'tgl_pelunasan_akhir',
					id: 'vph_tgl_retur_akhir',
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
				gridviewpelunasanhutang.store.load({
					params: {
						no_bukti: Ext.getCmp('id_cbvinnobuktipelunasan').getValue(),
						tgl_awal: Ext.getCmp('vph_tgl_retur_awal').getValue(),
						tgl_akhir: Ext.getCmp('vph_tgl_retur_akhir').getValue(),
						//kd_produk: Ext.getCmp('id_cbvrbproduk').getValue(),
						kd_supplier: Ext.getCmp('vph_kd_supplier').getValue()
						
					}
				});
			}
		}, {
			text: 'Reset',
			formBind: true,
			handler: function () {
				Ext.getCmp('id_cbvrbproduk').setValue('');
                                Ext.getCmp('id_cbvinnobuktipelunasan').setValue('');
                                Ext.getCmp('id_cbvphsuplier').setValue('');
                                Ext.getCmp('vph_tgl_retur_awal').setValue('');
                                Ext.getCmp('vph_tgl_retur_akhir').setValue('');
                                Ext.getCmp('vph_kd_supplier').setValue('');
				gridviewpelunasanhutang.store.removeAll();
			}
		}]
	};
    //End Header View Pelunasan Hutang
    
    // FORM PANEL
    var viewpelunasanhutang = new Ext.FormPanel({
		id: 'viewpelunasanhutang',
		border: false,
		frame: true,
		//autoScroll:true,	 
		bodyStyle: 'padding-right:20px;',
		labelWidth: 130,
		items: [{
				bodyStyle: {
					margin: '10px 0px 15px 0px'
				},
				items: [headerviewpelunasanhutang]                             
                        },gridviewpelunasanhutang ]
		}); 
</script>
