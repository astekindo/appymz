<?php if (!defined( 'BASEPATH')) exit( 'No direct script access allowed'); ?>
<script type="text/javascript">
	// start COMBOBOX SUPPLIER
	var strcbmprsuplier = new Ext.data.ArrayStore({
		fields: ['nama_supplier'],
		data: []
	});
	var strgridmprsuplier = new Ext.data.Store({
		reader: new Ext.data.JsonReader({
			fields: ['kd_supplier', 'nama_supplier'],
			root: 'data',
			totalProperty: 'record'
		}),
		proxy: new Ext.data.HttpProxy({
			url: '<?= site_url("laporan_penerimaan_barang/search_supplier") ?>',
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
	var searchgridmprsuplier = new Ext.app.SearchField({
		store: strgridmprsuplier,
		params: {
			start: STARTPAGE,
			limit: ENDPAGE
		},
		width: 350,
		id: 'id_searchgridmprsuplier'
	});
	var gridmprsuplier = new Ext.grid.GridPanel({
		store: strgridmprsuplier,
		stripeRows: true,
		frame: true,
		border: true,
		columns: [{
			header: 'Kode Supplier',
			dataIndex: 'kd_supplier',
			width: 80,
			sortable: true,

		}, {
			header: 'Nama Supplier',
			dataIndex: 'nama_supplier',
			width: 300,
			sortable: true,
		}],
		tbar: new Ext.Toolbar({
			items: [searchgridmprsuplier]
		}),
		bbar: new Ext.PagingToolbar({
			pageSize: ENDPAGE,
			store: strgridmprsuplier,
			displayInfo: true
		}),
		listeners: {
			'rowdblclick': function () {
				var sm = this.getSelectionModel();
				var sel = sm.getSelections();
				if (sel.length > 0) {
					Ext.getCmp('id_cbmprsuplier').setValue(sel[0].get('kd_supplier'));
					Ext.getCmp('mpr_nama_supplier').setValue(sel[0].get('nama_supplier'));
					// strlaporanpenerimaanbarang.removeAll();       
					menumprsuplier.hide();
				}
			}
		}
	});
	var menumprsuplier = new Ext.menu.Menu();
	menumprsuplier.add(new Ext.Panel({
		title: 'Pilih Supplier',
		layout: 'fit',
		buttonAlign: 'left',
		modal: true,
		width: 400,
		height: 300,
		closeAction: 'hide',
		plain: true,
		items: [gridmprsuplier],
		buttons: [{
			text: 'Close',
			handler: function () {
				menumprsuplier.hide();
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
			strgridmprsuplier.load();
			menumprsuplier.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
		},
		validationEvent: false,
		validateOnBlur: false,
		trigger1Class: 'x-form-clear-trigger',
		trigger2Class: 'x-form-search-trigger',
		hideTrigger1: true
	});
	menumprsuplier.on('hide', function () {
		var sf = Ext.getCmp('id_searchgridmprsuplier').getValue();
		if (sf != '') {
			Ext.getCmp('id_searchgridmprsuplier').setValue('');
			searchgridmprsuplier.onTrigger2Click();
		}
	});
	var cbmprsuplier = new Ext.ux.TwinComboSuppliermpr({
		fieldLabel: 'Kode Supplier',
		id: 'id_cbmprsuplier',
		store: strcbmprsuplier,
		mode: 'local',
		valueField: 'nama_supplier',
		displayField: 'nama_supplier',
		typeAhead: true,
		triggerAction: 'all',
		// allowBlank: false,
		editable: false,
		anchor: '90%',
		hiddenName: 'nama_supplier',
		emptyText: 'Pilih Kode Supplier'
	});
	// end COMBOBOX SUPPLIER

	// start COMBOBOX STATUS PR
	var valCbMprStatusPR = [
		['A', "All"],
		['0', "Belum Approve"],
		['1', "Approve Ass Manager"],
		['2', "Approve Manager"]
	];
	var strCbMprStatusPR = new Ext.data.ArrayStore({
		fields: [{
			name: 'key'
		}, {
			name: 'value'
		}],
		data: valCbMprStatusPR
	});
	var cbMprStatusPR = new Ext.form.ComboBox({
		fieldLabel: 'Approval PR',
		id: 'cbmprstatusPR',
		name: 'status',
		// allowBlank:false,
		store: strCbMprStatusPR,
		valueField: 'key',
		displayField: 'value',
		mode: 'local',
		forceSelection: true,
		triggerAction: 'all',
		anchor: '90%'
	});
	// end COMBOBOX STATUS PR

	// start COMBOBOX STATUS PRODUK
	var valCbMprStatusProduk = [
		['A', "All"],
		['0', "Normal"],
		['1', "Konsinyasi"]
	];
	var strCbMprStatusProduk = new Ext.data.ArrayStore({
		fields: [{
			name: 'key'
		}, {
			name: 'value'
		}],
		data: valCbMprStatusProduk
	});
	var cbMprStatusProduk = new Ext.form.ComboBox({
		fieldLabel: 'Type Purchase',
		id: 'cbmprstatusProduk',
		name: 'konsinyasi',
		// allowBlank:false,
		store: strCbMprStatusProduk,
		valueField: 'key',
		displayField: 'value',
		mode: 'local',
		forceSelection: true,
		triggerAction: 'all',
		anchor: '90%'
	});
	// end COMBOBOX STATUS PRODUK

	// start COMBOBOX CLOSE PR
	var valCbMprClosePR = [
		['A', "All"],
		['0', "Open"],
		['2', "Reject"],
		['1', "Close"]
	];
	var strCbMprClosePR = new Ext.data.ArrayStore({
		fields: [{
			name: 'key'
		}, {
			name: 'value'
		}],
		data: valCbMprClosePR
	});
	var cbMprClosePR = new Ext.form.ComboBox({
		fieldLabel: 'Status PR',
		id: 'cbmprclosepr',
		name: 'close_pr',
		// allowBlank:false,
		store: strCbMprClosePR,
		valueField: 'key',
		displayField: 'value',
		mode: 'local',
		forceSelection: true,
		triggerAction: 'all',
		anchor: '90%'
	});
	// end COMBOBOX CLOSE PR

	// HEADER MONITORING PR
	var headermonitoringPR = {
		layout: 'column',
		border: false,
		buttonAlign: 'left',
		items: [{
			columnWidth: .5,
			layout: 'form',
			border: false,
			labelWidth: 100,
			defaults: {
				labelSeparator: ''
			},
			items: [cbmprsuplier, {
					xtype: 'datefield',
					fieldLabel: 'Tgl PR',
					emptyText: 'Tanggal Awal',
					name: 'tgl_awal',
					id: 'mpr_tgl_awal',
					maxLength: 255,
					anchor: '90%',
					value: '',
					format: 'd-M-Y'
				},
				cbMprStatusPR, cbMprClosePR
			]
		}, {
			columnWidth: .5,
			layout: 'form',
			border: false,
			labelWidth: 100,
			defaults: {
				labelSeparator: ''
			},
			items: [{
					xtype: 'textfield',
					fieldLabel: 'Nama Supplier',
					name: 'nama_supplier',
					readOnly: true,
					fieldClass: 'readonly-input',
					id: 'mpr_nama_supplier',
					anchor: '90%',
					value: '',
					emptyText: 'Nama Supplier'
				}, {
					xtype: 'datefield',
					fieldLabel: 's/d',
					emptyText: 'Tanggal Akhir',
					name: 'tgl_akhir',
					id: 'mpr_tgl_akhir',
					maxLength: 255,
					anchor: '90%',
					value: '',
					format: 'd-M-Y'
				},
				cbMprStatusProduk,
                                {
                                    fieldLabel: 'Peruntukan <span class="asterix">*</span>',
                                    xtype: 'radiogroup',
                                    name: 'kd_peruntukan',
                                    columnWidth: [.5, .5],
                                    allowBlank:false,
                                    anchor: '90%',
                                    items: [{
                                            boxLabel: 'Supermarket',
                                            name: 'kd_peruntukan',
                                            inputValue: '0',
                                            id: 'mpr_peruntukan_supermarket',
                                            checked:true
                                        }, {
                                            boxLabel: 'Distribusi',
                                            name: 'kd_peruntukan',
                                            inputValue: '1',
                                            id: 'mpr_peruntukan_distribusi'
                                        }]
                                }
			]
		}],
		buttons: [{
			text: 'Filter',
			formBind: true,
			handler: function () {
				// var kd_supplier = Ext.getCmp('id_cbmprsuplier').getValue();
				// var tgl_awal = Ext.getCmp('mpr_tgl_awal').getValue();
				// var tgl_akhir = Ext.getCmp('mpr_tgl_akhir').getValue();
				// if (kd_supplier == '' && tgl_awal == '' && tgl_akhir == ''){
				// Ext.Msg.show({
				// title: 'Error',
				// msg: 'Silahkan Search Supplier / Tanggal Terlebih Dulu',
				// modal: true,
				// icon: Ext.Msg.ERROR,
				// buttons: Ext.Msg.OK			               
				// });
				// return;
				// }
				gridpembelianmonitoringPR.store.load({
					params: {
						kd_supplier: Ext.getCmp('id_cbmprsuplier').getValue(),
						tgl_awal: Ext.getCmp('mpr_tgl_awal').getValue(),
						tgl_akhir: Ext.getCmp('mpr_tgl_akhir').getValue(),
						status: Ext.getCmp('cbmprstatusPR').getValue(),
						close_pr: Ext.getCmp('cbmprclosepr').getValue(),
						konsinyasi: Ext.getCmp('cbmprstatusProduk').getValue(),
                                                peruntukan_sup: Ext.getCmp('mpr_peruntukan_supermarket').getValue(),
                                                peruntukan_dist: Ext.getCmp('mpr_peruntukan_distribusi').getValue(),
					}
				});
			}
		}, {
			text: 'Reset',
			formBind: true,
			handler: function () {
//				Ext.getCmp('id_cbmprsuplier').setValue('');
//				Ext.getCmp('mpr_nama_supplier').setValue('');
//				Ext.getCmp('mpr_tgl_awal').setRawValue('');
//				Ext.getCmp('mpr_tgl_akhir').setRawValue('');
//				Ext.getCmp('cbmprstatusPR').setValue('');
//				Ext.getCmp('cbmprclosepr').setValue('');
//				Ext.getCmp('cbmprstatusProduk').setValue('');
//				gridpembelianmonitoringPR.store.removeAll();
                                clearmonitoringpembelianrequest();
			}
		}]
	};

	// start GRID MONITORING PR   	
	var strpembelianmonitoringPR = new Ext.data.Store({
		reader: new Ext.data.JsonReader({
			fields: [
				'no_ro',
				'subject',
				'tgl_ro',
				'status_ro',
				'is_close_ro',
				'kd_supplier',
				'nama_supplier',
				'type_purchase',
                                'no_po',
                                'tanggal_po',
                                'peruntukan'
			],
			root: 'data',
			totalProperty: 'record'
		}),
		proxy: new Ext.data.HttpProxy({
			url: '<?= site_url("monitoring_purchase_request/get_rows") ?>',
			method: 'POST'
		}),
		listeners: {
			loadexception: function (event, options, response, error) {

			}
		}
	});
	var searchpembelianmonitoringPR = new Ext.app.SearchField({
		store: strpembelianmonitoringPR,
		params: {
			start: STARTPAGE,
			limit: ENDPAGE
		},
		width: 220,
		emptyText: 'No PR, Subject',
		id: 'idsearchpembelianmonitoringPR'
	});
	var tbpembelianmonitoringPR = new Ext.Toolbar({
		items: [searchpembelianmonitoringPR]
	});
	var smpembelianmonitoringPR = new Ext.grid.CheckboxSelectionModel();
	var gridpembelianmonitoringPR = new Ext.grid.EditorGridPanel({
		id: 'gridpembelianmonitoringPR',
		frame: true,
		border: true,
		stripeRows: true,
		sm: smpembelianmonitoringPR,
		store: strpembelianmonitoringPR,
		loadMask: false,
		style: 'margin:0 auto;',
		height: 400,
		columns: [{
			header: "No PR",
			dataIndex: 'no_ro',
			sortable: true,
			width: 100
		}, {
			header: "Subject",
			dataIndex: 'subject',
			sortable: true,
			width: 150
		}, {
			header: "Tanggal PR",
			dataIndex: 'tgl_ro',
			sortable: true,
			width: 75
		}, {
			header: "Approval PR",
			dataIndex: 'status_ro',
			sortable: true,
			width: 120
		}, {
			header: "Status PR",
			dataIndex: 'is_close_ro',
			sortable: true,
			width: 70
		}, {
			header: "Kode Supplier",
			dataIndex: 'kd_supplier',
			sortable: true,
			width: 100
		}, {
			header: "Nama Supplier",
			dataIndex: 'nama_supplier',
			sortable: true,
			width: 150
		}, {
			header: "Type Purchase",
			dataIndex: 'type_purchase',
			sortable: true,
			width: 100
		},{
			header: "No PO",
			dataIndex: 'no_po',
			sortable: true,
			width: 100
		},{
			header: "Tanggal PO",
			dataIndex: 'tanggal_po',
			sortable: true,
			width: 100
		},{
			header: "Peruntukan",
			dataIndex: 'peruntukan',
			sortable: true,
			width: 100
		}],
		listeners: {
			'rowdblclick': function () {
				var sm = gridpembelianmonitoringPR.getSelectionModel();
				var sel = sm.getSelections();
				
				if (sel.length > 0) {

                    Ext.Ajax.request({
                        url: '<?= site_url("monitoring_purchase_request/get_data_pr") ?>/' + sel[0].get('no_ro'),
                        method: 'POST',
                        params: {},
                        callback: function (opt, success, responseObj) {
                            var windowmonitoringpr = new Ext.Window({
                                title: 'Monitoring Purchase Request',
                                width: 850,
                                height: 500,
                                autoScroll: true,
                                html: responseObj.responseText
                            });

                            windowmonitoringpr.show();

                        }
                    });
                }
			}
		},
		tbar: tbpembelianmonitoringPR,
		bbar: new Ext.PagingToolbar({
			pageSize: ENDPAGE,
			store: strpembelianmonitoringPR,
			displayInfo: true
		})
	});
	// end GRID MONITORING PR


	// PANEL MONITORING PR
	var pembelianmonitoringPR = new Ext.FormPanel({
		id: 'pembelianmonitoringPR',
		border: false,
		frame: true,
		//autoScroll:true,	 
		bodyStyle: 'padding-right:20px;',
		labelWidth: 130,
		items: [{
				bodyStyle: {
					margin: '10px 0px 15px 0px'
				},
				items: [headermonitoringPR]
			}, gridpembelianmonitoringPR]
		});
                
       pembelianmonitoringPR.on('afterrender', function(){
        this.getForm().load({
            url: '<?= site_url("pembelian_create_request/get_form") ?>',
            success: function(form, action){
                var r = Ext.util.JSON.decode(action.response.responseText);
                if(r.data.user_peruntukan === "0"){
                    Ext.getCmp('mpr_peruntukan_supermarket').setValue(true);
                    Ext.getCmp('mpr_peruntukan_supermarket').show();
                    Ext.getCmp('mpr_peruntukan_distribusi').hide();
                }else if(r.data.user_peruntukan === "1"){
                    Ext.getCmp('mpr_peruntukan_distribusi').setValue(true);
                    Ext.getCmp('mpr_peruntukan_supermarket').hide();
                    Ext.getCmp('mpr_peruntukan_distribusi').show();
                }else{
                    Ext.getCmp('mpr_peruntukan_supermarket').setValue(true);
                    Ext.getCmp('mpr_peruntukan_supermarket').show();
                    Ext.getCmp('mpr_peruntukan_distribusi').show();
                }
            },
            failure: function(form, action){
                var de = Ext.util.JSON.decode(action.response.responseText);
                Ext.Msg.show({
                    title: 'Error',
                    msg: de.errMsg,
                    modal: true,
                    icon: Ext.Msg.ERROR,
                    buttons: Ext.Msg.OK,
                    fn: function(btn){
                        if (btn == 'ok' && de.errMsg == 'Session Expired') {
                            window.location = '<?= site_url("auth/login") ?>';
                        }
                    }
                });
            }
        });
    });
	
    function clearmonitoringpembelianrequest(){
        Ext.getCmp('pembelianmonitoringPR').getForm().reset();
        Ext.getCmp('pembelianmonitoringPR').getForm().load({
            url: '<?= site_url("pembelian_create_request/get_form") ?>',
            success: function(form, action){
                var r = Ext.util.JSON.decode(action.response.responseText);
                if(r.data.user_peruntukan === "0"){
                    Ext.getCmp('mpr_peruntukan_supermarket').setValue(true);
                    Ext.getCmp('mpr_peruntukan_supermarket').show();
                    Ext.getCmp('mpr_peruntukan_distribusi').hide();
                }else if(r.data.user_peruntukan === "1"){
                    Ext.getCmp('mpr_peruntukan_distribusi').setValue(true);
                    Ext.getCmp('mpr_peruntukan_supermarket').hide();
                    Ext.getCmp('mpr_peruntukan_distribusi').show();
                }else{
                    Ext.getCmp('mpr_peruntukan_supermarket').setValue(true);
                    Ext.getCmp('mpr_peruntukan_supermarket').show();
                    Ext.getCmp('mpr_peruntukan_distribusi').show();
                }
            },
            failure: function(form, action){
                var de = Ext.util.JSON.decode(action.response.responseText);
                Ext.Msg.show({
                    title: 'Error',
                    msg: de.errMsg,
                    modal: true,
                    icon: Ext.Msg.ERROR,
                    buttons: Ext.Msg.OK,
                    fn: function(btn){
                        if (btn == 'ok' && de.errMsg == 'Session Expired') {
                            window.location = '<?= site_url("auth/login") ?>';
                        }
                    }
                });
            }
        });
        strpembelianmonitoringPR.removeAll();
    }
</script>
