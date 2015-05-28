<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script>
    // start COMBOBOX PRODUK
	var strcbvrjproduk = new Ext.data.ArrayStore({
		fields: ['nama_produk'],
		data: []
	});
	var strgridvrjproduk = new Ext.data.Store({
		reader: new Ext.data.JsonReader({
			fields: ['kd_produk', 'nama_produk'],
			root: 'data',
			totalProperty: 'record'
		}),
		proxy: new Ext.data.HttpProxy({
			url: '<?= site_url("view_retur_jual/search_produk") ?>',
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
	var searchgridvrjproduk = new Ext.app.SearchField({
		store: strgridvrjproduk,
		params: {
			start: STARTPAGE,
			limit: ENDPAGE
		},
		width: 350,
		id: 'id_searchgridvrjproduk'
	});
	var gridvrjproduk = new Ext.grid.GridPanel({
		store: strgridvrjproduk,
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
			items: [searchgridvrjproduk]
		}),
		bbar: new Ext.PagingToolbar({
			pageSize: ENDPAGE,
			store: strgridvrjproduk,
			displayInfo: true
		}),
		listeners: {
			'rowdblclick': function () {
				var sm = this.getSelectionModel();
				var sel = sm.getSelections();
				if (sel.length > 0) {
					Ext.getCmp('id_cbvrjproduk').setValue(sel[0].get('kd_produk'));
					menuvrjproduk.hide();
				}
			}
		}
	});
	var menuvrjproduk = new Ext.menu.Menu();
	menuvrjproduk.add(new Ext.Panel({
		title: 'Pilih Produk',
		layout: 'fit',
		buttonAlign: 'left',
		modal: true,
		width: 400,
		height: 300,
		closeAction: 'hide',
		plain: true,
		items: [gridvrjproduk],
		buttons: [{
			text: 'Close',
			handler: function () {
				menuvrjproduk.hide();
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
			strgridvrjproduk.load();
			menuvrjproduk.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
		},
		validationEvent: false,
		validateOnBlur: false,
		trigger1Class: 'x-form-clear-trigger',
		trigger2Class: 'x-form-search-trigger',
		hideTrigger1: true
	});
	menuvrjproduk.on('hide', function () {
		var sf = Ext.getCmp('id_searchgridvrjproduk').getValue();
		if (sf != '') {
			Ext.getCmp('id_searchgridvrjproduk').setValue('');
			searchgridvrjproduk.onTrigger2Click();
		}
	});
	var cbvrjproduk = new Ext.ux.TwinComboSuppliermpr({
		fieldLabel: 'Kode Produk',
		id: 'id_cbvrjproduk',
		store: strcbvrjproduk,
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
   // twin combo no sales order
    var strcb_vrj_salesorder = new Ext.data.ArrayStore({
        fields: ['no_so'],
        data : []
    });
	
    var strgrid_vrj_salesorder = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['no_so','tgl_so','rp_total','rp_diskon','rp_ekstra_diskon','rp_grand_total','rp_diskon_tambahan'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("penjualan_retur/search_salesorder") ?>',
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
	
    var searchgrid_vrj_salesorder = new Ext.app.SearchField({
        store: strgrid_vrj_salesorder,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE			
        },
        width: 350,
        id: 'id_searchgrid_vrj_salesorder'
    });
	
	
    var grid_vrj_salesorder = new Ext.grid.GridPanel({
        store: strgrid_vrj_salesorder,
        stripeRows: true,
        frame: true,
        border:true,
        columns: [{
                header: 'No Sales Order',
                dataIndex: 'no_so',
                width: 150,
                sortable: true		
            },{
                header: 'Tanggal Sales Order',
                dataIndex: 'tgl_so',
                width: 300,
                sortable: true        
            }],
        tbar: new Ext.Toolbar({
            items: [searchgrid_vrj_salesorder]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgrid_vrj_salesorder,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function(){			
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {				
                    Ext.getCmp('id_cbvrjsalesorder').setValue(sel[0].get('no_so'));
                    Ext.getCmp('id_pjret_tglso').setValue(sel[0].get('tgl_so'));
                    //strpenjualanretur.removeAll();       
                    menu_vrj_salesorder.hide();
                 }
            }
        }
    });
	
    var menu_vrj_salesorder = new Ext.menu.Menu();
    menu_vrj_salesorder.add(new Ext.Panel({
        title: 'Pilih No Sales Order',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 700,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [grid_vrj_salesorder],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menu_vrj_salesorder.hide();
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
            strgrid_vrj_salesorder.load();
            menu_vrj_salesorder.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
	
    menu_vrj_salesorder.on('hide', function(){
        var sf = Ext.getCmp('id_searchgrid_vrj_salesorder').getValue();
        if( sf != ''){
            Ext.getCmp('id_searchgrid_vrj_salesorder').setValue('');
            searchgrid_vrj_salesorder.onTrigger2Click();
        }
    });
	
    //var mask =new Ext.LoadMask(Ext.getBody(),{msg:'Loading data...', store: strpenjualanretur});
        
    var cbvrjsalesorder = new Ext.ux.TwinComboReturJualSalesOrder({
        fieldLabel: 'No SO/Struk',
        id: 'id_cbvrjsalesorder',
        store: strcb_vrj_salesorder,
        mode: 'local',
        valueField: 'no_so',
        displayField: 'no_so',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: true,
        editable: false,
        anchor: '90%',
        hiddenName: 'no_so',
        emptyText: 'Pilih Sales Order'
         
    });
    //end twincombo no sales order
  // start COMBOBOX No Retur Jual
	var strcbvrjnoreturjual = new Ext.data.ArrayStore({
		fields: ['no_retur'],
		data: []
	});
	var strgridvrjnoretur = new Ext.data.Store({
		reader: new Ext.data.JsonReader({
			fields: ['no_retur','tgl_retur'],
			root: 'data',
			totalProperty: 'record'
		}),
		proxy: new Ext.data.HttpProxy({
			url: '<?= site_url("view_retur_jual/search_noretur") ?>',
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
	var searchgridvrjnoretur = new Ext.app.SearchField({
		store: strgridvrjnoretur,
		params: {
			start: STARTPAGE,
			limit: ENDPAGE
		},
		width: 350,
		id: 'id_searchgridvrjnoretur'
	});
	var gridvrjnoretur = new Ext.grid.GridPanel({
		store: strgridvrjnoretur,
		stripeRows: true,
		frame: true,
		border: true,
		columns: [ {
			header: 'No Retur',
			dataIndex: 'no_retur',
			width: 200,
			sortable: true
		},{
			header: 'Tanggal Retur',
			dataIndex: 'tgl_retur',
			width: 80,
			sortable: true

		},],
		tbar: new Ext.Toolbar({
			items: [searchgridvrjnoretur]
		}),
		bbar: new Ext.PagingToolbar({
			pageSize: ENDPAGE,
			store: strgridvrjnoretur,
			displayInfo: true
		}),
		listeners: {
			'rowdblclick': function () {
				var sm = this.getSelectionModel();
				var sel = sm.getSelections();
				if (sel.length > 0) {
					Ext.getCmp('id_cbvrjnoreturjual').setValue(sel[0].get('no_retur'));
					menuvrjnoretur.hide();
				}
			}
		}
	});
	var menuvrjnoretur = new Ext.menu.Menu();
	menuvrjnoretur.add(new Ext.Panel({
		title: 'Pilih No Retur',
		layout: 'fit',
		buttonAlign: 'left',
		modal: true,
		width: 400,
		height: 300,
		closeAction: 'hide',
		plain: true,
		items: [gridvrjnoretur],
		buttons: [{
			text: 'Close',
			handler: function () {
				menuvrjnoretur.hide();
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
			strgridvrjnoretur.load();
			menuvrjnoretur.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
		},
		validationEvent: false,
		validateOnBlur: false,
		trigger1Class: 'x-form-clear-trigger',
		trigger2Class: 'x-form-search-trigger',
		hideTrigger1: true
	});
	menuvrjnoretur.on('hide', function () {
		var sf = Ext.getCmp('id_searchgridvrjnoretur').getValue();
		if (sf !== '') {
			Ext.getCmp('id_searchgridvrjnoretur').setValue('');
			searchgridvrjnoretur.onTrigger2Click();
		};
            }
        );
	var cbvrjnoreturjual = new Ext.ux.TwinCombonoreturjual({
		fieldLabel: 'NO Retur',
		id: 'id_cbvrjnoreturjual',
		store: strcbvrjnoreturjual,
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
	// end COMBOBOX NO Retur Jual
  // start COMBOBOX Kode Member
	var strcbvrjmember = new Ext.data.ArrayStore({
		fields: ['nmmember'],
		data: []
	});
	var strgridvrjmember = new Ext.data.Store({
		reader: new Ext.data.JsonReader({
			fields: ['kd_member', 'nmmember'],
			root: 'data',
			totalProperty: 'record'
		}),
		proxy: new Ext.data.HttpProxy({
			url: '<?= site_url("view_retur_jual/search_member") ?>',
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
	var searchgridvrjmember = new Ext.app.SearchField({
		store: strgridvrjmember,
		params: {
			start: STARTPAGE,
			limit: ENDPAGE
		},
		width: 350,
		id: 'id_searchgridvrjmember'
	});
	var gridvrjmember = new Ext.grid.GridPanel({
		store: strgridvrjmember,
		stripeRows: true,
		frame: true,
		border: true,
		columns: [{
			header: 'Kode Member',
			dataIndex: 'kd_member',
			width: 80,
			sortable: true,

		}, {
			header: 'Nama Member',
			dataIndex: 'nmmember',
			width: 300,
			sortable: true,
		}],
		tbar: new Ext.Toolbar({
			items: [searchgridvrjmember]
		}),
		bbar: new Ext.PagingToolbar({
			pageSize: ENDPAGE,
			store: strgridvrjmember,
			displayInfo: true
		}),
		listeners: {
			'rowdblclick': function () {
				var sm = this.getSelectionModel();
				var sel = sm.getSelections();
				if (sel.length > 0) {
					Ext.getCmp('id_cbvrjmember').setValue(sel[0].get('kd_member'));
					menuvrjmember.hide();
				}
			}
		}
	});
	var menuvrjmember = new Ext.menu.Menu();
	menuvrjmember.add(new Ext.Panel({
		title: 'Pilih Member',
		layout: 'fit',
		buttonAlign: 'left',
		modal: true,
		width: 400,
		height: 300,
		closeAction: 'hide',
		plain: true,
		items: [gridvrjmember],
		buttons: [{
			text: 'Close',
			handler: function () {
				menuvrjmember.hide();
			}
		}]
	}));
	Ext.ux.TwinComboMember = Ext.extend(Ext.form.ComboBox, {
		initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
		getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
		initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
		onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
		trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
		onTrigger2Click: function () {
			//load store grid
			strgridvrjmember.load();
			menuvrjmember.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
		},
		validationEvent: false,
		validateOnBlur: false,
		trigger1Class: 'x-form-clear-trigger',
		trigger2Class: 'x-form-search-trigger',
		hideTrigger1: true
	});
	menuvrjmember.on('hide', function () {
		var sf = Ext.getCmp('id_searchgridvrjmember').getValue();
		if (sf != '') {
			Ext.getCmp('id_searchgridvrjmember').setValue('');
			searchgridvrjmember.onTrigger2Click();
		}
	});
	var cbvrjmember = new Ext.ux.TwinComboMember({
		fieldLabel: 'Kode Member',
		id: 'id_cbvrjmember',
		store: strcbvrjmember,
		mode: 'local',
		valueField: 'nmmember',
		displayField: 'nmmember',
		typeAhead: true,
		triggerAction: 'all',
		// allowBlank: false,
		editable: false,
		anchor: '90%',
		hiddenName: 'nmmember',
		emptyText: 'Pilih Kode Member'
	});
    // end COMBOBOX Kode Member
    // Start Header Retur Jual
   var headerretursalesorder = {
		layout: 'column',
		border: false,
		buttonAlign: 'left',
		items: [{
			columnWidth: .5,
			layout: 'form',
			border: false,
			labelWidth: 100,
			defaults: {labelSeparator: ''},
			items: [ cbvrjnoreturjual,
                                {
					xtype: 'datefield',
					fieldLabel: 'Tgl Awal',
					emptyText: 'Tanggal Awal',
					name: 'tgl_retur_awal',
					id: 'vrj_tgl_retur_awal',
					maxLength: 255,
					anchor: '90%',
					value: '',
					format: 'd-M-Y'
				},cbvrjproduk
			]
		},{
			columnWidth: .5,
			layout: 'form',
			border: false,
			labelWidth: 100,
			defaults: {labelSeparator: ''},
			items: [ cbvrjsalesorder,
                                {
					xtype: 'datefield',
					fieldLabel: 'Tgl Akhir',
					emptyText: 'Tanggal Akhir',
					name: 'tgl_retur_akhir',
					id: 'vrj_tgl_retur_akhir',
					maxLength: 255,
					anchor: '90%',
					value: '',
					format: 'd-M-Y'
				},cbvrjmember
			]
		}],
		buttons: [{
			text: 'Filter',
			formBind: true,
			handler: function () {
				gridviewretursalesorder.store.load({
					params: {
						no_retur: Ext.getCmp('id_cbvrjnoreturjual').getValue(),
						tgl_awal: Ext.getCmp('vrj_tgl_retur_awal').getValue(),
						tgl_akhir: Ext.getCmp('vrj_tgl_retur_akhir').getValue(),
						kd_produk: Ext.getCmp('id_cbvrjproduk').getValue(),
						no_so: Ext.getCmp('id_cbvrjsalesorder').getValue(),
						kd_member: Ext.getCmp('id_cbvrjmember').getValue()
					}
				});
			}
		}, {
			text: 'Reset',
			formBind: true,
			handler: function () {
				Ext.getCmp('id_cbvrjproduk').setValue('');
                                Ext.getCmp('id_cbvrjmember').setValue('');
                                Ext.getCmp('id_cbvrjnoreturjual').setValue('');
                                Ext.getCmp('id_cbvrjsalesorder').setValue('');
                                Ext.getCmp('vrj_tgl_retur_awal').setValue('');
                                Ext.getCmp('vrj_tgl_retur_akhir').setValue('');
				gridviewretursalesorder.store.removeAll();
			}
		}]
	};
    //End Header view retur jual
    
    // start GRID VIEW RETUR JUAL  	
	var strviewretursalesorder = new Ext.data.Store({
		reader: new Ext.data.JsonReader({
			fields: [
				'no_retur',
				'tgl_retur',
				'kd_produk',
                                'kd_produk_supp',
				'nama_produk',
				'qty',
                                'no_so',
                                'kode_member',
                                'nama_member'
			],
			root: 'data',
			totalProperty: 'record'
		}),
		proxy: new Ext.data.HttpProxy({
			url: '<?= site_url("view_retur_jual/get_rows") ?>',
			method: 'POST'
		}),
		listeners: {
			loadexception: function (event, options, response, error) {

			}
		}
	});
	var searchviewretursalesorder = new Ext.app.SearchField({
		store: strviewretursalesorder,
		params: {
			start: STARTPAGE,
			limit: ENDPAGE
		},
		width: 220,
		emptyText: 'No Retur, Kode Produk',
		id: 'idsearchviewretursalesorder'
	});
	var tbviewretursalesorder = new Ext.Toolbar({
		items: [searchviewretursalesorder]
	});
	var smviewretursalesorder = new Ext.grid.CheckboxSelectionModel();
        
	var gridviewretursalesorder = new Ext.grid.EditorGridPanel({
		id: 'gridviewretursalesorder',
		frame: true,
		border: true,
		stripeRows: true,
		sm: smviewretursalesorder,
		store: strviewretursalesorder,
		loadMask: false,
		style: 'margin:0 auto;',
		height: 400,
		columns: [{
			header: "No Struk/SO",
			dataIndex: 'no_so',
			sortable: true,
			width: 120
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
			header: "Kode Produk Supplier",
			dataIndex: 'kd_produk_supp',
			sortable: true,
			width: 150
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
		},{
			header: "Kode Member",
			dataIndex: 'kd_member',
			sortable: true,
			width: 100
		},{
			header: "Nama Member",
			dataIndex: 'nama_member',
			sortable: true,
			width: 100
		}],
		listeners: {
			'rowdblclick': function () {
				var sm = gridviewretursalesorder.getSelectionModel();
				var sel = sm.getSelections();
				
				if (sel.length > 0) {

                    Ext.Ajax.request({
                        url: '<?= site_url("view_retur_jual/get_data_rj") ?>/' + sel[0].get('no_retur'),
                        method: 'POST',
                        params: {},
                        callback: function (opt, success, responseObj) {
                            var windowviewreturjual = new Ext.Window({
                                title: 'View Retur Penjualan',
                                width: 850,
                                height: 500,
                                autoScroll: true,
                                html: responseObj.responseText
                            });

                            windowviewreturjual.show();

                        }
                    });
                }
			}
		},
		tbar: tbviewretursalesorder,
		bbar: new Ext.PagingToolbar({
			pageSize: ENDPAGE,
			store: strviewretursalesorder,
			displayInfo: true
		})
	});
	// end Grid View Retur Jual
        
   var viewretursalesorder = new Ext.FormPanel({
		id: 'viewretursalesorder',
		border: false,
		frame: true,
		//autoScroll:true,	 
		bodyStyle: 'padding-right:20px;',
		labelWidth: 130,
		items: [{
				bodyStyle: {
					margin: '10px 0px 15px 0px'
				},
				items: [headerretursalesorder, 
                                    gridviewretursalesorder
                                ]
			},
			
		]
	}); 
</script>
