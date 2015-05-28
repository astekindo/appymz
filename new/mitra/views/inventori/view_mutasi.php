<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<script>
    // start COMBOBOX PRODUK
	var str_cb_vm_produk = new Ext.data.ArrayStore({
		fields: ['nama_produk'],
		data: []
	});
	var str_grid_vm_produk = new Ext.data.Store({
		reader: new Ext.data.JsonReader({
			fields: ['kd_produk', 'nama_produk'],
			root: 'data',
			totalProperty: 'record'
		}),
		proxy: new Ext.data.HttpProxy({
			url: '<?= site_url("view_mutasi/search_produk") ?>',
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
	var search_grid_vm_produk = new Ext.app.SearchField({
		store: str_grid_vm_produk,
		params: {
			start: STARTPAGE,
			limit: ENDPAGE
		},
		width: 350,
		id: 'id_searchgridvm_produk'
	});
	var grid_vm_produk = new Ext.grid.GridPanel({
		store: str_grid_vm_produk,
		stripeRows: true,
		frame: true,
		border: true,
		columns: [{
			header: 'Kode Produk',
			dataIndex: 'kd_produk',
			width: 100,
			sortable: true

        }, {
			header: 'Nama Produk',
			dataIndex: 'nama_produk',
			width: 300,
			sortable: true
        }],
		tbar: new Ext.Toolbar({
			items: [search_grid_vm_produk]
		}),
		bbar: new Ext.PagingToolbar({
			pageSize: ENDPAGE,
			store: str_grid_vm_produk,
			displayInfo: true
		}),
		listeners: {
			'rowdblclick': function () {
				var sm = this.getSelectionModel();
				var sel = sm.getSelections();
				if (sel.length > 0) {
					Ext.getCmp('id_cbvmproduk').setValue(sel[0].get('kd_produk'));
					menu_vm_produk.hide();
				}
			}
		}
	});
	var menu_vm_produk = new Ext.menu.Menu();
	menu_vm_produk.add(new Ext.Panel({
		title: 'Pilih Produk',
		layout: 'fit',
		buttonAlign: 'left',
		modal: true,
		width: 400,
		height: 300,
		closeAction: 'hide',
		plain: true,
		items: [grid_vm_produk],
		buttons: [{
			text: 'Close',
			handler: function () {
				menu_vm_produk.hide();
			}
		}]
	}));
	Ext.ux.TwinComboSuppliermpr = Ext.extend(Ext.form.ComboBox, {
		initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
		getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
		initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
		onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
		trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
		onTrigger2Click: function () {
			//load store grid
			str_grid_vm_produk.load();
			menu_vm_produk.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
		},
		validationEvent: false,
		validateOnBlur: false,
		trigger1Class: 'x-form-clear-trigger',
		trigger2Class: 'x-form-search-trigger',
		hideTrigger1: true
	});
	menu_vm_produk.on('hide', function () {
		var sf = Ext.getCmp('id_searchgridvm_produk').getValue();
		if (sf != '') {
			Ext.getCmp('id_searchgridvm_produk').setValue('');
			search_grid_vm_produk.onTrigger2Click();
		}
	});
	var cb_vm_produk = new Ext.ux.TwinComboSuppliermpr({
		fieldLabel: 'Kode Produk',
		id: 'id_cbvmproduk',
		store: str_cb_vm_produk,
		mode: 'local',
		valueField: 'nama_produk',
		displayField: 'nama_produk',
		typeAhead: true,
		triggerAction: 'all',
		editable: false,
		anchor: '90%',
		hiddenName: 'nama_produk',
		emptyText: 'Pilih Kode Produk'
	});
	// end COMBOBOX Produk

   // twin combo lokasi awal
    var str_cb_vm_lokasi_asal = new Ext.data.ArrayStore({
        fields: ['kd_lokasi','nama_lokasi'],
        data : []
    });

    var str_grid_vm_lokasi_asal = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                {name: 'kd_lokasi', allowBlank: false, type: 'text'},
                {name: 'nama_lokasi', allowBlank: false, type: 'text'}
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("mutasi_barang/search_lokasi") ?>',
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

    var search_grid_vm_lokasi_asal = new Ext.app.SearchField({
        store: str_grid_vm_lokasi_asal,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgrid_vm_lokasi_asal'
    });


    var grid_vm_lokasi_asal = new Ext.grid.GridPanel({
        store: str_grid_vm_lokasi_asal,
        stripeRows: true,
        frame: true,
        border:true,
        columns: [{
            header: 'Kode Lokasi',
            dataIndex: 'kd_lokasi',
            width: 100,
            sortable: true

        },{
            header: 'Nama Lokasi',
            dataIndex: 'nama_lokasi',
            width: 250,
            sortable: true
        }],
        tbar: new Ext.Toolbar({
            items: [search_grid_vm_lokasi_asal]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: str_grid_vm_lokasi_asal,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function(){
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('id_cb_vmlokasi_asal').setValue(sel[0].get('kd_lokasi'));
                    menu_vm_lokasi_asal.hide();
                 }
            }
        }
    });

    var menu_vm_lokasi_asal = new Ext.menu.Menu();
    menu_vm_lokasi_asal.add(new Ext.Panel({
        title: 'Pilih lokasi asal',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [grid_vm_lokasi_asal],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menu_vm_lokasi_asal.hide();
                }
            }]
    }));

    Ext.ux.TwinComboReturJualSalesOrder = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            //load store grid
            str_grid_vm_lokasi_asal.load();
            menu_vm_lokasi_asal.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    menu_vm_lokasi_asal.on('hide', function(){
        var sf = Ext.getCmp('id_searchgrid_vm_lokasi_asal').getValue();
        if( sf != ''){
            Ext.getCmp('id_searchgrid_vm_lokasi_asal').setValue('');
            search_grid_vm_lokasi_asal.onTrigger2Click();
        }
    });

    var cb_vm_lokasi_asal = new Ext.ux.TwinComboReturJualSalesOrder({
        fieldLabel: 'Lokasi asal',
        id: 'id_cb_vmlokasi_asal',
        store: str_cb_vm_lokasi_asal,
        mode: 'local',
        valueField: 'kd_lokasi',
        displayField: 'nama_lokasi',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: true,
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_lokasi',
        emptyText: 'Pilih lokasi'

    });
    //end twincombo lokasi awal

    // twin combo lokasi tujuan
    var str_cb_vm_lokasi_tujuan = new Ext.data.ArrayStore({
        fields: ['kd_lokasi','nama_lokasi'],
        data : []
    });

    var str_grid_vm_lokasi_tujuan = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                {name: 'kd_lokasi', allowBlank: false, type: 'text'},
                {name: 'nama_lokasi', allowBlank: false, type: 'text'}
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("mutasi_barang/search_lokasi") ?>',
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

    var search_grid_vm_lokasi_tujuan = new Ext.app.SearchField({
        store: str_grid_vm_lokasi_tujuan,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgrid_vm_lokasi_tujuan'
    });


    var grid_vm_lokasi_tujuan = new Ext.grid.GridPanel({
        store: str_grid_vm_lokasi_tujuan,
        stripeRows: true,
        frame: true,
        border:true,
        columns: [{
            header: 'Kode Lokasi',
            dataIndex: 'kd_lokasi',
            width: 100,
            sortable: true

        },{
            header: 'Nama Lokasi',
            dataIndex: 'nama_lokasi',
            width: 250,
            sortable: true
        }],
        tbar: new Ext.Toolbar({
            items: [search_grid_vm_lokasi_tujuan]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: str_grid_vm_lokasi_tujuan,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function(){
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('id_cb_vmlokasi_tujuan').setValue(sel[0].get('kd_lokasi'));
                    menu_vm_lokasi_tujuan.hide();
                }
            }
        }
    });

    var menu_vm_lokasi_tujuan = new Ext.menu.Menu();
    menu_vm_lokasi_tujuan.add(new Ext.Panel({
        title: 'Pilih lokasi tujuan',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [grid_vm_lokasi_tujuan],
        buttons: [{
            text: 'Close',
            handler: function(){
                menu_vm_lokasi_tujuan.hide();
            }
        }]
    }));

    Ext.ux.TwinComboReturJualSalesOrder = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            //load store grid
            str_grid_vm_lokasi_tujuan.load();
            menu_vm_lokasi_tujuan.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    menu_vm_lokasi_tujuan.on('hide', function(){
        var sf = Ext.getCmp('id_searchgrid_vm_lokasi_tujuan').getValue();
        if( sf != ''){
            Ext.getCmp('id_searchgrid_vm_lokasi_tujuan').setValue('');
            search_grid_vm_lokasi_tujuan.onTrigger2Click();
        }
    });

    var cb_vm_lokasi_tujuan = new Ext.ux.TwinComboReturJualSalesOrder({
        fieldLabel: 'Lokasi tujuan',
        id: 'id_cb_vmlokasi_tujuan',
        store: str_cb_vm_lokasi_tujuan,
        mode: 'local',
        valueField: 'kd_lokasi',
        displayField: 'nama_lokasi',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: true,
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_lokasi',
        emptyText: 'Pilih lokasi'

    });
  // start COMBOBOX No mutasi
	var str_cb_vm_nomutasi = new Ext.data.ArrayStore({
        fields: ['no_mutasi_stok','tgl_mutasi','keterangan','no_ref'],
        data : []
	});
	var str_grid_vm_nomutasi = new Ext.data.Store({
		reader: new Ext.data.JsonReader({
            fields: ['no_mutasi_stok','no_ref','tgl_mutasi','nama_pengambil','keterangan','tgl_mutasi_in'],
			root: 'data',
			totalProperty: 'record'
		}),
		proxy: new Ext.data.HttpProxy({
			url: '<?= site_url("view_mutasi/get_no_mutasi") ?>',
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
	var search_grid_vm_nomutasi = new Ext.app.SearchField({
		store: str_grid_vm_nomutasi,
		params: {
			start: STARTPAGE,
			limit: ENDPAGE
		},
		width: 350,
		id: 'id_searchgrid_vm_nomutasi'
	});
	var grid_vm_nomutasi = new Ext.grid.GridPanel({
		store: str_grid_vm_nomutasi,
		stripeRows: true,
		frame: true,
		border: true,
        columns: [{
            header: 'No.Mutasi',
            dataIndex: 'no_mutasi_stok',
            width: 100,
            sortable: true
        },{
            header: 'Tanggal',
            dataIndex: 'tgl_mutasi',
            width: 90,
            sortable: true
        },{
            header: 'No.Referensi',
            dataIndex: 'no_ref',
            width: 100,
            sortable: true
        },{
            header: 'Keterangan',
            dataIndex: 'keterangan',
            width: 400,
            sortable: true
        }],
		tbar: new Ext.Toolbar({
			items: [search_grid_vm_nomutasi]
		}),
		bbar: new Ext.PagingToolbar({
			pageSize: ENDPAGE,
			store: str_grid_vm_nomutasi,
			displayInfo: true
		}),
		listeners: {
			'rowdblclick': function () {
				var sm = this.getSelectionModel();
				var sel = sm.getSelections();
				if (sel.length > 0) {
					Ext.getCmp('id_cbvm_nomutasi').setValue(sel[0].get('no_mutasi_stok'));
					menu_vm_nomutasi.hide();
				}
			}
		}
	});
	var menu_vm_nomutasi = new Ext.menu.Menu();
	menu_vm_nomutasi.add(new Ext.Panel({
		title: 'Pilih No Bukti',
		layout: 'fit',
		buttonAlign: 'left',
		modal: true,
		width: 400,
		height: 300,
		closeAction: 'hide',
		plain: true,
		items: [grid_vm_nomutasi],
		buttons: [{
			text: 'Close',
			handler: function () {
				menu_vm_nomutasi.hide();
			}
		}]
	}));
	Ext.ux.TwinCombonoreturjual = Ext.extend(Ext.form.ComboBox, {
		initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
		getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
		initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
		onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
		trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
		onTrigger2Click: function () {
			//load store grid
			str_grid_vm_nomutasi.load();
			menu_vm_nomutasi.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
		},
		validationEvent: false,
		validateOnBlur: false,
		trigger1Class: 'x-form-clear-trigger',
		trigger2Class: 'x-form-search-trigger',
		hideTrigger1: true
	});
	menu_vm_nomutasi.on('hide', function () {
		var sf = Ext.getCmp('id_searchgrid_vm_nomutasi').getValue();
		if (sf !== '') {
			Ext.getCmp('id_searchgrid_vm_nomutasi').setValue('');
			search_grid_vm_nomutasi.onTrigger2Click();
		};
            }
        );
	var cb_vm_nomutasi = new Ext.ux.TwinCombonoreturjual({
		fieldLabel: 'No. Bukti',
		id: 'id_cbvm_nomutasi',
		store: str_cb_vm_nomutasi,
		mode: 'local',
		valueField: 'no_bukti',
		displayField: 'no_bukti',
		typeAhead: true,
		triggerAction: 'all',
		// allowBlank: false,
		editable: false,
		anchor: '90%',
		hiddenName: 'no_bukti',
		emptyText: 'Pilih No Bukti Mutasi'
	});
	// end COMBOBOX NO Mutasi

    // Start Header Retur Jual
    var header_view_mutasi = {
		layout: 'column',
		border: false,
		buttonAlign: 'left',
		items: [{
			columnWidth: .5,
			layout: 'form',
			border: false,
			labelWidth: 100,
			defaults: {labelSeparator: ''},
			items: [
                cb_vm_nomutasi,
                {
					xtype: 'datefield',
					fieldLabel: 'Tgl Awal',
					emptyText: 'Tanggal Awal',
					name: 'tgl_awal',
					id: 'vm_tgl_mutasi_awal',
					maxLength: 255,
					anchor: '90%',
					value: '',
					format: 'd-M-Y'
				},
                cb_vm_lokasi_asal
			]
		},{
			columnWidth: .5,
			layout: 'form',
			border: false,
			labelWidth: 100,
			defaults: {labelSeparator: ''},
			items: [
                cb_vm_produk,
                {
					xtype: 'datefield',
					fieldLabel: 'Tgl Akhir',
					emptyText: 'Tanggal Akhir',
					name: 'tgl_akhir',
					id: 'vm_tgl_mutasi_akhir',
					maxLength: 255,
					anchor: '90%',
					value: '',
					format: 'd-M-Y'
				},
                cb_vm_lokasi_tujuan
			]
		}],
		buttons: [{
			text: 'Filter',
			formBind: true,
			handler: function () {
				grid_view_mutasi.store.load({params: {
                    no_mutasi       : Ext.getCmp('id_cbvm_nomutasi').getValue(),
                    tgl_awal        : Ext.getCmp('vm_tgl_mutasi_awal').getValue(),
                    tgl_akhir       : Ext.getCmp('vm_tgl_mutasi_akhir').getValue(),
                    kd_produk       : Ext.getCmp('id_cbvmproduk').getValue(),
                    lokasi_awal     : Ext.getCmp('id_cb_vmlokasi_asal').getValue(),
                    lokasi_tujuan   : Ext.getCmp('id_cb_vmlokasi_tujuan').getValue()
                }});
			}
		}, {
			text: 'Reset',
			formBind: true,
			handler: function () {
				Ext.getCmp('id_cbvm_nomutasi').setValue('');
                Ext.getCmp('vm_tgl_mutasi_awal').setValue('');
                Ext.getCmp('vm_tgl_mutasi_akhir').setValue('');
                Ext.getCmp('id_cbvmproduk').setValue('');
                Ext.getCmp('id_cb_vmlokasi_asal').setValue('');
                Ext.getCmp('id_cb_vmlokasi_tujuan').setValue('');
				grid_view_mutasi.store.removeAll();
			}
		}]
	};
    //End Header view retur jual

    // start GRID VIEW RETUR JUAL
    var str_view_mutasi = new Ext.data.Store({
		reader: new Ext.data.JsonReader({
			fields: [
                'no_mutasi_stok',
                'tgl_mutasi',
                'keterangan',
                'created_by',
                'no_ref',
                'status',
                'nama_pengambil',
                'kd_lokasi_awal',
                'nama_lokasi_awal',
                'kd_lokasi_tujuan',
                'nama_lokasi_tujuan'
            ],
			root: 'data',
			totalProperty: 'record'
		}),
		proxy: new Ext.data.HttpProxy({
			url: '<?= site_url("view_mutasi/get_rows") ?>',
			method: 'POST'
		}),
		listeners: {
			loadexception: function (event, options, response, error) {

			}
		}
	});
	var search_view_mutasi = new Ext.app.SearchField({
		store: str_view_mutasi,
		params: {
			start: STARTPAGE,
			limit: ENDPAGE
		},
		width: 220,
		emptyText: 'No. Bukti, No. Referensi',
		id: 'id_search_viewmutasi'
	});
	var tb_view_mutasi = new Ext.Toolbar({
		items: [search_view_mutasi]
	});
	var sm_view_mutasi = new Ext.grid.CheckboxSelectionModel();

	var grid_view_mutasi = new Ext.grid.EditorGridPanel({
		id: 'grid_view_mutasi',
		frame: true,
		border: true,
		stripeRows: true,
		sm: sm_view_mutasi,
		store: str_view_mutasi,
		loadMask: false,
		style: 'margin:0 auto;',
		height: 400,
		columns: [{
			header: "No. Bukti",
			dataIndex: 'no_mutasi_stok',
			sortable: true,
			width: 100
        },{
            header: "Tanggal Mutasi",
            dataIndex: 'tgl_mutasi',
            sortable: true,
            width: 100
		},{
			header: "Dibuat",
			dataIndex: 'created_by',
			sortable: true,
			width: 80
		}, {
			header: "No. Referensi",
			dataIndex: 'no_ref',
			sortable: true,
			width: 80
		}, {
			header: "Status",
			dataIndex: 'status',
			sortable: true,
			width: 50
        },{
            header: "Keterangan",
            dataIndex: 'keterangan',
            sortable: true,
            width: 250
        }, {
            header: "Nama Pengambil",
            dataIndex: 'nama_pengambil',
            sortable: true,
            width: 100
        }, {
            header: "Lokasi awal",
            dataIndex: 'nama_lokasi_awal',
            sortable: true,
            width: 200
        }, {
            header: "Lokasi tujuan",
            dataIndex: 'nama_lokasi_tujuan',
            sortable: true,
            width: 200
		}],
		listeners: {
			'rowdblclick': function () {
				var sm = grid_view_mutasi.getSelectionModel();
				var sel = sm.getSelections();

				if (sel.length > 0) {

                    Ext.Ajax.request({
                        url: '<?= site_url("view_mutasi/get_data_mutasi") ?>/' + sel[0].get('no_mutasi_stok'),
                        method: 'POST',
                        params: {},
                        callback: function (opt, success, responseObj) {
                            var win_view_mutasi = new Ext.Window({
                                title: 'View Mutasi',
                                width: 850,
                                height: 400,
                                autoScroll: true,
                                html: responseObj.responseText
                            });

                            win_view_mutasi.show();

                        }
                    });
                }
			}
		},
		tbar: tb_view_mutasi,
		bbar: new Ext.PagingToolbar({ pageSize: ENDPAGE, store: str_view_mutasi, displayInfo: true })
	});
	// end Grid View Retur Jual

   var view_mutasi = new Ext.FormPanel({
		id: 'inv_view_mutasi',
		border: false,
		frame: true,
		//autoScroll:true,
		bodyStyle: 'padding-right:20px;',
		labelWidth: 130,
		items: [{
            bodyStyle: { margin: '10px 0px 15px 0px'},
            items: [header_view_mutasi, grid_view_mutasi ]
        }]
	});
</script>
