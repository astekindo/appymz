<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<script>
   // twin combo lokasi awal
    var str_cb_pm_lokasi_asal = new Ext.data.ArrayStore({
        fields: ['kd_lokasi','nama_lokasi'],
        data : []
    });

    var str_grid_pm_lokasi_asal = new Ext.data.Store({
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

    str_grid_pm_lokasi_asal.on('load', function() {
        str_grid_pm_lokasi_asal.setBaseParam('sender', 'monitoring');
    });


    var search_grid_pm_lokasi_asal = new Ext.app.SearchField({
        store: str_grid_pm_lokasi_asal,
        params: {
            sender: 'monitoring',
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgrid_pm_lokasi_asal'
    });


    var grid_pm_lokasi_asal = new Ext.grid.GridPanel({
        store: str_grid_pm_lokasi_asal,
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
            width: 400,
            sortable: true
        }],
        tbar: new Ext.Toolbar({
            items: [search_grid_pm_lokasi_asal]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: str_grid_pm_lokasi_asal,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function(){
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('id_cb_pmlokasi_asal').setValue(sel[0].get('kd_lokasi'));
                    menu_pm_lokasi_asal.hide();
                 }
            }
        }
    });

    var menu_pm_lokasi_asal = new Ext.menu.Menu();
    menu_pm_lokasi_asal.add(new Ext.Panel({
        title: 'Pilih lokasi asal',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 700,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [grid_pm_lokasi_asal],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menu_pm_lokasi_asal.hide();
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
            str_grid_pm_lokasi_asal.load({
                params: {
                    sender: 'monitoring'
                }
            });
            menu_pm_lokasi_asal.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    menu_pm_lokasi_asal.on('hide', function(){
        var sf = Ext.getCmp('id_searchgrid_pm_lokasi_asal').getValue();
        if( sf != ''){
            Ext.getCmp('id_searchgrid_pm_lokasi_asal').setValue('');
            search_grid_pm_lokasi_asal.onTrigger2Click();
        }
    });

    var cb_pm_lokasi_asal = new Ext.ux.TwinComboReturJualSalesOrder({
        fieldLabel: 'Lokasi asal',
        id: 'id_cb_pmlokasi_asal',
        store: str_cb_pm_lokasi_asal,
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
    var str_cb_pm_lokasi_tujuan = new Ext.data.ArrayStore({
        fields: ['kd_lokasi','nama_lokasi'],
        data : []
    });

    var str_grid_pm_lokasi_tujuan = new Ext.data.Store({
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

    str_grid_pm_lokasi_tujuan.on('load', function() {
        str_grid_pm_lokasi_tujuan.setBaseParam('sender', 'monitoring');
    });

    var search_grid_pm_lokasi_tujuan = new Ext.app.SearchField({
        store: str_grid_pm_lokasi_tujuan,
        params: {
            sender: 'monitoring',
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgrid_pm_lokasi_tujuan'
    });


    var grid_pm_lokasi_tujuan = new Ext.grid.GridPanel({
        store: str_grid_pm_lokasi_tujuan,
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
            width: 400,
            sortable: true
        }],
        tbar: new Ext.Toolbar({
            items: [search_grid_pm_lokasi_tujuan]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: str_grid_pm_lokasi_tujuan,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function(){
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('id_cb_pmlokasi_tujuan').setValue(sel[0].get('kd_lokasi'));
                    menu_pm_lokasi_tujuan.hide();
                }
            }
        }
    });

    var menu_pm_lokasi_tujuan = new Ext.menu.Menu();
    menu_pm_lokasi_tujuan.add(new Ext.Panel({
        title: 'Pilih lokasi tujuan',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 700,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [grid_pm_lokasi_tujuan],
        buttons: [{
            text: 'Close',
            handler: function(){
                menu_pm_lokasi_tujuan.hide();
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
            str_grid_pm_lokasi_tujuan.load({
                params: {
                    sender: 'monitoring'
                }
            });
            menu_pm_lokasi_tujuan.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    menu_pm_lokasi_tujuan.on('hide', function(){
        var sf = Ext.getCmp('id_searchgrid_pm_lokasi_tujuan').getValue();
        if( sf != ''){
            Ext.getCmp('id_searchgrid_pm_lokasi_tujuan').setValue('');
            search_grid_pm_lokasi_tujuan.onTrigger2Click();
        }
    });

    var cb_pm_lokasi_tujuan = new Ext.ux.TwinComboReturJualSalesOrder({
        fieldLabel: 'Lokasi tujuan',
        id: 'id_cb_pmlokasi_tujuan',
        store: str_cb_pm_lokasi_tujuan,
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
	var str_cb_pm_nomutasi = new Ext.data.ArrayStore({
        fields: ['no_mutasi_stok','tgl_mutasi','keterangan','no_ref'],
        data : []
	});
	var str_grid_pm_nomutasi = new Ext.data.Store({
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
	var search_grid_pm_nomutasi = new Ext.app.SearchField({
		store: str_grid_pm_nomutasi,
		params: {
			start: STARTPAGE,
			limit: ENDPAGE
		},
		width: 350,
		id: 'id_searchgrid_pm_nomutasi'
	});
	var grid_pm_nomutasi = new Ext.grid.GridPanel({
		store: str_grid_pm_nomutasi,
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
			items: [search_grid_pm_nomutasi]
		}),
		bbar: new Ext.PagingToolbar({
			pageSize: ENDPAGE,
			store: str_grid_pm_nomutasi,
			displayInfo: true
		}),
		listeners: {
			'rowdblclick': function () {
				var sm = this.getSelectionModel();
				var sel = sm.getSelections();
				if (sel.length > 0) {
					Ext.getCmp('id_cbpm_nomutasi').setValue(sel[0].get('no_mutasi_stok'));
					menu_pm_nomutasi.hide();
				}
			}
		}
	});
	var menu_pm_nomutasi = new Ext.menu.Menu();
	menu_pm_nomutasi.add(new Ext.Panel({
		title: 'Pilih No Bukti',
		layout: 'fit',
		buttonAlign: 'left',
		modal: true,
		width: 400,
		height: 300,
		closeAction: 'hide',
		plain: true,
		items: [grid_pm_nomutasi],
		buttons: [{
			text: 'Close',
			handler: function () {
				menu_pm_nomutasi.hide();
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
			str_grid_pm_nomutasi.load();
			menu_pm_nomutasi.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
		},
		validationEvent: false,
		validateOnBlur: false,
		trigger1Class: 'x-form-clear-trigger',
		trigger2Class: 'x-form-search-trigger',
		hideTrigger1: true
	});
	menu_pm_nomutasi.on('hide', function () {
		var sf = Ext.getCmp('id_searchgrid_pm_nomutasi').getValue();
		if (sf !== '') {
			Ext.getCmp('id_searchgrid_pm_nomutasi').setValue('');
			search_grid_pm_nomutasi.onTrigger2Click();
		};
            }
        );
	var cb_pm_nomutasi = new Ext.ux.TwinCombonoreturjual({
		fieldLabel: 'No. Bukti',
		id: 'id_cbpm_nomutasi',
		store: str_cb_pm_nomutasi,
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
    var header_print_mutasi = {
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
                {
					xtype: 'datefield',
					fieldLabel: 'Tgl Awal',
					emptyText: 'Tanggal Awal',
					name: 'tgl_awal',
					id: 'pm_tgl_mutasi_awal',
					maxLength: 255,
					anchor: '90%',
					value: '',
					format: 'd-M-Y'
				},
                cb_pm_lokasi_asal,
                cb_pm_nomutasi
            ]
		},{
			columnWidth: .5,
			layout: 'form',
			border: false,
			labelWidth: 100,
			defaults: {labelSeparator: ''},
			items: [
                {
					xtype: 'datefield',
					fieldLabel: 'Tgl Akhir',
					emptyText: 'Tanggal Akhir',
					name: 'tgl_akhir',
					id: 'pm_tgl_mutasi_akhir',
					maxLength: 255,
					anchor: '90%',
					value: '',
					format: 'd-M-Y'
				},
                cb_pm_lokasi_tujuan
			]
		}],
		buttons: [{
			text: 'Filter',
			formBind: true,
			handler: function () {
				grid_print_mutasi.store.load({params: {
                    no_mutasi       : Ext.getCmp('id_cbpm_nomutasi').getValue(),
                    tgl_awal        : Ext.getCmp('pm_tgl_mutasi_awal').getValue(),
                    tgl_akhir       : Ext.getCmp('pm_tgl_mutasi_akhir').getValue(),
                    lokasi_awal     : Ext.getCmp('id_cb_pmlokasi_asal').getValue(),
                    lokasi_tujuan   : Ext.getCmp('id_cb_pmlokasi_tujuan').getValue()
                }});
			}
		}, {
			text: 'Reset',
			formBind: true,
			handler: function () {
				Ext.getCmp('id_cbpm_nomutasi').setValue('');
                Ext.getCmp('pm_tgl_mutasi_awal').setValue('');
                Ext.getCmp('pm_tgl_mutasi_akhir').setValue('');
                Ext.getCmp('id_cb_pmlokasi_asal').setValue('');
                Ext.getCmp('id_cb_pmlokasi_tujuan').setValue('');
				grid_print_mutasi.store.removeAll();
			}
		}]
	};
    //End Header view retur jual

    // start GRID VIEW RETUR JUAL
    var str_print_mutasi = new Ext.data.Store({
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
	var search_print_mutasi = new Ext.app.SearchField({
		store: str_print_mutasi,
		params: {
			start: STARTPAGE,
			limit: ENDPAGE
		},
		width: 220,
		emptyText: 'No. Bukti, No. Referensi',
		id: 'id_search_printmutasi'
	});
	var tb_print_mutasi = new Ext.Toolbar({
		items: [search_print_mutasi]
	});
	var sm_print_mutasi = new Ext.grid.CheckboxSelectionModel();

	var grid_print_mutasi = new Ext.grid.EditorGridPanel({
		id: 'grid_print_mutasi',
		frame: true,
		border: true,
		stripeRows: true,
		sm: sm_print_mutasi,
		store: str_print_mutasi,
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
            width: 100
        }, {
            header: "Lokasi tujuan",
            dataIndex: 'nama_lokasi_tujuan',
            sortable: true,
            width: 100
		}],
		listeners: {
			'rowdblclick': function () {
				var sm = grid_print_mutasi.getSelectionModel();
				var sel = sm.getSelections();

				if (sel.length > 0) {

                    Ext.Ajax.request({
                        url: '<?= site_url("view_mutasi/get_mb_pdf") ?>/' + sel[0].get('no_mutasi_stok'),
                        method: 'POST',
                        params: {},
                        callback: function (opt, success, responseObj) {
                            var r = Ext.util.JSON.decode(responseObj.responseText);
                            pm_cetak_mutasi.show();
                            Ext.getDom('id_pm_win_frame').src = r.printUrl;
                        }
                    });
                }
			}
		},
		tbar: tb_print_mutasi,
		bbar: new Ext.PagingToolbar({ pageSize: ENDPAGE, store: str_print_mutasi, displayInfo: true })
	});
	// end Grid View Retur Jual
    var pm_cetak_mutasi = new Ext.Window({
        id: 'id_pm_win',
        title: 'Cetak Bukti Mutasi',
        closeAction: 'hide',
        width: 1000,
        height: 500,
        layout: 'fit',
        border: false,
        html:'<iframe style="width:100%;height:100%;" id="id_pm_win_frame" src=""></iframe>'
    });

    var print_mutasi = new Ext.FormPanel({
		id: 'inv_print_mutasi',
		border: false,
		frame: true,
		//autoScroll:true,
		bodyStyle: 'padding-right:20px;',
		labelWidth: 130,
		items: [{
            bodyStyle: { margin: '10px 0px 15px 0px'},
            items: [header_print_mutasi, grid_print_mutasi ]
        }]
	});
</script>
