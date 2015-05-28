<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script>
     // start COMBOBOX PRODUK
	var strcbvrbproduk = new Ext.data.ArrayStore({
		fields: ['nama_produk'],
		data: []
	});
	var strgridvrbproduk = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
			fields: ['kd_produk', 'nama_produk'],
			root: 'data',
			totalProperty: 'record'
		}),
		proxy: new Ext.data.HttpProxy({
			url: '<?= site_url("view_retur_beli/search_produk") ?>',
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
	var searchgridvrbproduk = new Ext.app.SearchField({
		store: strgridvrbproduk,
		params: {
			start: STARTPAGE,
			limit: ENDPAGE
		},
		width: 350,
		id: 'id_searchgridvrbproduk'
	});
	var gridvrbproduk = new Ext.grid.GridPanel({
		store: strgridvrbproduk,
		stripeRows: true,
		frame: true,
		border: true,
		columns: [{
			header: 'Kode Produk',
			dataIndex: 'kd_produk',
			width: 100,
			sortable: true,

		}, {
			header: 'Nama Produk',
			dataIndex: 'nama_produk',
			width: 300,
			sortable: true,
		}],
		tbar: new Ext.Toolbar({
			items: [searchgridvrbproduk]
		}),
		bbar: new Ext.PagingToolbar({
			pageSize: ENDPAGE,
			store: strgridvrbproduk,
			displayInfo: true,
		}),
		listeners: {
			'rowdblclick': function () {
				var sm = this.getSelectionModel();
				var sel = sm.getSelections();
				if (sel.length > 0) {
					Ext.getCmp('id_cbvrbproduk').setValue(sel[0].get('kd_produk'));
					menuvrbproduk.hide();
				}
			}
		}
	});
	var menuvrbproduk = new Ext.menu.Menu();
	menuvrbproduk.add(new Ext.Panel({
		title: 'Pilih Produk',
		layout: 'fit',
		buttonAlign: 'left',
		modal: true,
		width: 400,
		height: 300,
		closeAction: 'hide',
		plain: true,
		items: [gridvrbproduk],
		buttons: [{
			text: 'Close',
			handler: function () {
				menuvrbproduk.hide();
			}
		}]
	}));
	Ext.ux.TwinComboVRBProduk = Ext.extend(Ext.form.ComboBox, {
		initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
		getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
		initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
		onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
		trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
		onTrigger2Click: function () {
			//load store grid
			strgridvrbproduk.load();
			menuvrbproduk.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
		},
		validationEvent: false,
		validateOnBlur: false,
		trigger1Class: 'x-form-clear-trigger',
		trigger2Class: 'x-form-search-trigger',
		hideTrigger1: true
	});
	menuvrbproduk.on('hide', function () {
		var sf = Ext.getCmp('id_searchgridvrbproduk').getValue();
		if (sf != '') {
			Ext.getCmp('id_searchgridvrbproduk').setValue('');
			searchgridvrbproduk.onTrigger2Click();
		}
	});
	var cbvrbproduk = new Ext.ux.TwinComboVRBProduk({
		fieldLabel: 'Kode Produk',
		id: 'id_cbvrbproduk',
		store: strcbvrbproduk,
		mode: 'local',
		valueField: 'nama_produk',
		displayField: 'nama_produk',
		typeAhead: true,
		triggerAction: 'all',
		// allowBlank: false,
		editable: false,
		anchor: '90%',
		hiddenName: 'nama_produk',
		emptyText: 'Pilih Kode Produk'
	});
     // end COMBOBOX Produk
     // twin combo supplier
    var str_vrb_supplier = new Ext.data.ArrayStore({
        fields: ['nama_supplier'],
        data: []
    });

    var strgrid_vrb_supplier = new Ext.data.Store({
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

    var searchgrid_vrb_supplier = new Ext.app.SearchField({
        store: strgrid_vrb_supplier,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgrid_vrb_supplier'
    });


    var grid_vrb_supplier = new Ext.grid.GridPanel({
        store: strgrid_vrb_supplier,
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
            items: [searchgrid_vrb_supplier]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgrid_vrb_supplier,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('id_cbvrbsuplier').setValue(sel[0].get('kd_supplier'));
                    Ext.getCmp('vrb_nama_supplier').setValue(sel[0].get('nama_supplier'));
                    //strpembelianretur.removeAll();
                    menu_vrb_supplier.hide();
                }
            }
        }
    });

    var menu_vrb_supplier = new Ext.menu.Menu();
    menu_vrb_supplier.add(new Ext.Panel({
        title: 'Pilih Supplier',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [grid_vrb_supplier],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menu_vrb_supplier.hide();
                }
            }]
    }));

    Ext.ux.TwinComboVRBSupplier = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            //load store grid
            strgrid_vrb_supplier.load();
            menu_vrb_supplier.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
                trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    menu_vrb_supplier.on('hide', function() {
        var sf = Ext.getCmp('id_searchgrid_vrb_supplier').getValue();
        if (sf != '') {
            Ext.getCmp('id_searchgrid_vrb_supplier').setValue('');
            searchgrid_vrb_supplier.onTrigger2Click();
        }
    });

    var cbvrbsuplier = new Ext.ux.TwinComboVRBSupplier({
        fieldLabel: 'Kode Supplier',
        id: 'id_cbvrbsuplier',
        store: str_vrb_supplier,
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
    
    // start COMBOBOX No Retur Beli
	var strcbvrbnoreturbeli = new Ext.data.ArrayStore({
		fields: ['no_retur'],
		data: []
	});
	var strgridvrbnoretur = new Ext.data.Store({
		reader: new Ext.data.JsonReader({
			fields: ['no_retur','tgl_retur'],
			root: 'data',
			totalProperty: 'record'
		}),
		proxy: new Ext.data.HttpProxy({
			url: '<?= site_url("view_retur_beli/search_noretur") ?>',
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
	var searchgridvrbnoretur = new Ext.app.SearchField({
		store: strgridvrbnoretur,
		params: {
			start: STARTPAGE,
			limit: ENDPAGE
		},
		width: 350,
		id: 'id_searchgridvrbnoretur'
	});
	var gridvrbnoretur = new Ext.grid.GridPanel({
		store: strgridvrbnoretur,
		stripeRows: true,
		frame: true,
		border: true,
		columns: [ {
			header: 'No Retur',
			dataIndex: 'no_retur',
			width: 100,
			sortable: true
		},{
			header: 'Tanggal Retur',
			dataIndex: 'tgl_retur',
			width: 80,
			sortable: true

		},],
		tbar: new Ext.Toolbar({
			items: [searchgridvrbnoretur]
		}),
		bbar: new Ext.PagingToolbar({
			pageSize: ENDPAGE,
			store: strgridvrbnoretur,
			displayInfo: true
		}),
		listeners: {
			'rowdblclick': function () {
				var sm = this.getSelectionModel();
				var sel = sm.getSelections();
				if (sel.length > 0) {
					Ext.getCmp('id_cbvrbnoreturbeli').setValue(sel[0].get('no_retur'));
					menuvrbnoretur.hide();
				}
			}
		}
	});
	var menuvrbnoretur = new Ext.menu.Menu();
	menuvrbnoretur.add(new Ext.Panel({
		title: 'Pilih No Retur',
		layout: 'fit',
		buttonAlign: 'left',
		modal: true,
		width: 400,
		height: 300,
		closeAction: 'hide',
		plain: true,
		items: [gridvrbnoretur],
		buttons: [{
			text: 'Close',
			handler: function () {
				menuvrbnoretur.hide();
			}
		}]
	}));
	Ext.ux.TwinCombonoreturbeli = Ext.extend(Ext.form.ComboBox, {
		initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
		getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
		initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
		onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
		trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
		onTrigger2Click: function () {
			//load store grid
			strgridvrbnoretur.load();
			menuvrbnoretur.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
		},
		validationEvent: false,
		validateOnBlur: false,
		trigger1Class: 'x-form-clear-trigger',
		trigger2Class: 'x-form-search-trigger',
		hideTrigger1: true
	});
	menuvrbnoretur.on('hide', function () {
		var sf = Ext.getCmp('id_searchgridvrbnoretur').getValue();
		if (sf !== '') {
			Ext.getCmp('id_searchgridvrbnoretur').setValue('');
			searchgridvrbnoretur.onTrigger2Click();
		};
            }
        );
	var cbvrbnoreturbeli = new Ext.ux.TwinCombonoreturbeli({
		fieldLabel: 'NO Retur',
		id: 'id_cbvrbnoreturbeli',
		store: strcbvrbnoreturbeli,
		mode: 'local',
		valueField: 'no_retur',
		displayField: 'no_retur',
		typeAhead: true,
		triggerAction: 'all',
		// allowBlank: false,
		editable: false,
		anchor: '90%',
		hiddenName: 'no_retur',
		emptyText: 'Pilih No Retur'
	});
    // end COMBOBOX NO Retur Beli
    
    // start GRID VIEW RETUR BELI 	
	var strviewreturbeli = new Ext.data.Store({
		reader: new Ext.data.JsonReader({
			fields: [
				'no_retur',
				'tgl_retur',
				'kd_produk',
				'nama_produk',
                'kd_suplier',
                'nama_supplier',
				'qty',
                'no_so',
                'kode_member',
                'nama_member'
			],
			root: 'data',
			totalProperty: 'record'
		}),
		proxy: new Ext.data.HttpProxy({
			url: '<?= site_url("view_retur_beli/get_rows") ?>',
			method: 'POST'
		}),
		listeners: {
			loadexception: function (event, options, response, error) {

			}
		}
	});
	var searchviewreturbeli = new Ext.app.SearchField({
		store: strviewreturbeli,
		params: {
			start: STARTPAGE,
			limit: ENDPAGE
		},
		width: 220,
		emptyText: 'No Retur, Kode Produk',
		id: 'idsearchviewreturbeli'
	});
	var tbviewreturbeli = new Ext.Toolbar({
		items: [searchviewreturbeli]
	});
	var smviewreturbeli = new Ext.grid.CheckboxSelectionModel();
        
	var gridviewreturbeli = new Ext.grid.EditorGridPanel({
		id: 'gridviewreturbeli',
		frame: true,
		border: true,
		stripeRows: true,
		sm: smviewreturbeli,
		store: strviewreturbeli,
		loadMask: false,
		style: 'margin:0 auto;',
		height: 400,
		columns: [{
			header: "Kode Supplier",
			dataIndex: 'kd_suplier',
			sortable: true,
			width: 120
		},{
			header: "Nama Supplier",
			dataIndex: 'nama_supplier',
			sortable: true,
			width: 170
		},{
			header: "No Retur",
			dataIndex: 'no_retur',
			sortable: true,
			width: 100
		},{
			header: "Tanggal Retur",
			dataIndex: 'tgl_retur',
			sortable: true,
			width: 100
		}, {
			header: "Kode Produk",
			dataIndex: 'kd_produk',
			sortable: true,
			width: 100
		}, {
			header: "Nama Produk",
			dataIndex: 'nama_produk',
			sortable: true,
			width: 250
		}, {
			header: "QTY Retur",
			dataIndex: 'qty',
			sortable: true,
			width: 100
		}],
		listeners: {
			'rowdblclick': function () {
				var sm = gridviewreturbeli.getSelectionModel();
				var sel = sm.getSelections();
				
				if (sel.length > 0) {

                        Ext.Ajax.request({
                        url: '<?= site_url("view_retur_beli/get_data_rb") ?>/' + sel[0].get('no_retur'),
                        method: 'POST',
                        params: {},
                        callback: function (opt, success, responseObj) {
                            var windowviewreturbeli = new Ext.Window({
                                title: 'View Retur Pembelian',
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
		tbar: tbviewreturbeli,
		bbar: new Ext.PagingToolbar({
			pageSize: ENDPAGE,
			store: strviewreturbeli,
			displayInfo: true,
            doRefresh: function() {
                gridviewreturbeli.store.load({
                    params: {
                        no_retur: Ext.getCmp('id_cbvrbnoreturbeli').getValue(),
                        tgl_awal: Ext.getCmp('vrb_tgl_retur_awal').getValue(),
                        tgl_akhir: Ext.getCmp('vrb_tgl_retur_akhir').getValue(),
                        kd_produk: Ext.getCmp('id_cbvrbproduk').getValue(),
                        kd_supplier: Ext.getCmp('id_cbvrbsuplier').getValue()

                    }
                });
            }
		})
	});
	// end Grid View Retur Beli
    
    // Start Header View Retur Beli
    var headerviewreturbeli = {
		layout: 'column',
		border: false,
		buttonAlign: 'left',
		items: [{
			columnWidth: .5,
			layout: 'form',
			border: false,
			labelWidth: 100,
			defaults: {labelSeparator: ''},
			items: [ cbvrbnoreturbeli,cbvrbproduk,
                                {
					xtype: 'datefield',
					fieldLabel: 'Tgl Awal',
					emptyText: 'Tanggal Awal',
					name: 'tgl_retur_awal',
					id: 'vrb_tgl_retur_awal',
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
			items: [ cbvrbsuplier,
                                {
					xtype: 'textfield',
					fieldLabel: 'Nama Supplier',
					emptyText: 'Nama Supplier',
					name: 'nama_supplier',
					id: 'vrb_nama_supplier',
                                        anchor: '90%',
                                        fieldClass:'readonly-input'
					
				},
                                {
					xtype: 'datefield',
					fieldLabel: 'Tgl Akhir',
					emptyText: 'Tanggal Akhir',
					name: 'tgl_retur_akhir',
					id: 'vrb_tgl_retur_akhir',
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
				gridviewreturbeli.store.load({
					params: {
						no_retur: Ext.getCmp('id_cbvrbnoreturbeli').getValue(),
						tgl_awal: Ext.getCmp('vrb_tgl_retur_awal').getValue(),
						tgl_akhir: Ext.getCmp('vrb_tgl_retur_akhir').getValue(),
						kd_produk: Ext.getCmp('id_cbvrbproduk').getValue(),
						kd_supplier: Ext.getCmp('id_cbvrbsuplier').getValue()
						
					}
				});
			}
		}, {
			text: 'Reset',
			formBind: true,
			handler: function () {
				Ext.getCmp('id_cbvrbproduk').setValue('');
                                Ext.getCmp('id_cbvrbnoreturbeli').setValue('');
                                Ext.getCmp('id_cbvrbsuplier').setValue('');
                                Ext.getCmp('vrb_tgl_retur_awal').setValue('');
                                Ext.getCmp('vrb_tgl_retur_akhir').setValue('');
                                Ext.getCmp('vrb_nama_supplier').setValue('');
				gridviewreturbeli.store.removeAll();
			}
		}]
    };
    //End Header View Retur Beli
    // FORM PANEL
    var viewreturbeli = new Ext.FormPanel({
        id: 'viewreturbeli',
        border: false,
        frame: true,
        //autoScroll:true,
        bodyStyle: 'padding-right:20px;',
        labelWidth: 130,
        items: [{bodyStyle: {margin: '10px 0px 15px 0px'}, items: [headerviewreturbeli]},gridviewreturbeli]
    });
</script>
