<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">
    // twin combo no sales order
    var strcb_mrj_salesorder = new Ext.data.ArrayStore({
        fields: ['no_so'],
        data : []
    });
	
    var strgrid_mrj_salesorder = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['no_so','tgl_so'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("monitoring_retur_jual/search_salesorder") ?>',
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
	
    var searchgrid_mrj_salesorder = new Ext.app.SearchField({
        store: strgrid_mrj_salesorder,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE			
        },
        width: 350,
        id: 'id_searchgrid_mrj_salesorder'
    });
	
	
    var grid_mrj_salesorder = new Ext.grid.GridPanel({
        store: strgrid_mrj_salesorder,
        stripeRows: true,
        frame: true,
        border:true,
        columns: [{
                header: 'No Sales Order',
                dataIndex: 'no_so',
                width: 120,
                sortable: true		
            },{
                header: 'Tanggal Sales Order',
                dataIndex: 'tgl_so',
                width: 150,
                sortable: true        
            }],
        tbar: new Ext.Toolbar({
            items: [searchgrid_mrj_salesorder]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgrid_mrj_salesorder,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function(){			
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {				
                    Ext.getCmp('id_cbmrj_salesorder').setValue(sel[0].get('no_so'));
                    menu_mrj_salesorder.hide();
                 }
            }
        }
    });
	
    var menu_mrj_salesorder = new Ext.menu.Menu();
    menu_mrj_salesorder.add(new Ext.Panel({
        title: 'Pilih No Sales Order',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 330,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [grid_mrj_salesorder],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menu_mrj_salesorder.hide();
                }
            }]
    }));
    
    Ext.ux.TwinComboMRJSalesOrder = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            //load store grid
            strgrid_mrj_salesorder.load();
            menu_mrj_salesorder.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
	
    menu_mrj_salesorder.on('hide', function(){
        var sf = Ext.getCmp('id_searchgrid_mrj_salesorder').getValue();
        if( sf != ''){
            Ext.getCmp('id_searchgrid_mrj_salesorder').setValue('');
            searchgrid_mrj_salesorder.onTrigger2Click();
        }
    });
	
    //var mask =new Ext.LoadMask(Ext.getBody(),{msg:'Loading data...', store: strpenjualanretur});
        
    var cbmrj_salesorder = new Ext.ux.TwinComboMRJSalesOrder({
        fieldLabel: 'No SO/Struk',
        id: 'id_cbmrj_salesorder',
        store: strcb_mrj_salesorder,
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
    var strcbmrj_retur = new Ext.data.ArrayStore({
        fields: ['no_retur'],
        data: []
    });

    var strgridmrjretur = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['no_retur', 'tgl_retur', 'no_so'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("monitoring_retur_jual/search_noretur") ?>',
            method: 'POST'
        }),
        listeners: {
            loadexception: function(event, options, response, error) {
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg === 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    var searchgridmrjretur = new Ext.app.SearchField({
        store: strgridmrjretur,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgridmrjretur'
    });


    var gridmrjretur = new Ext.grid.GridPanel({
        store: strgridmrjretur,
        stripeRows: true,
        frame: true,
        border: true,
        columns: [{
                header: 'No Retur',
                dataIndex: 'no_retur',
                width: 100,
                sortable: true
            }, {
                header: 'Tanggal Retur',
                dataIndex: 'tgl_retur',
                width: 110,
                sortable: true
            },{
                header: 'No SO',
                dataIndex: 'no_so',
                width: 130,
                sortable: true
            }],
        tbar: new Ext.Toolbar({
            items: [searchgridmrjretur]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridmrjretur,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('id_cbmrj_retur').setValue(sel[0].get('no_retur'));
                    menumrjretur.hide();
                }
            }
        }
    });

    var menumrjretur = new Ext.menu.Menu();
    menumrjretur.add(new Ext.Panel({
        title: 'Pilih No Retur',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridmrjretur],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menumrjretur.hide();
                }
            }]
    }));

    Ext.ux.TwinComboMrj_Retur = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            //load store grid
            strgridmrjretur.load();
            menumrjretur.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
                trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    menumrjretur.on('hide', function() {
        var sf = Ext.getCmp('id_searchgridmrjretur').getValue();
        if (sf !== '') {
            Ext.getCmp('id_searchgridmrjretur').setValue('');
            searchgridmrjretur.onTrigger2Click();
        }
    });

    var cbmrj_retur = new Ext.ux.TwinComboMrj_Retur({
        fieldLabel: 'No Retur <span class="asterix">*</span>',
        id: 'id_cbmrj_retur',
        store: strcbmrj_retur,
        mode: 'local',
        valueField: 'no_retur',
        displayField: 'no_retur',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'no_retur',
        emptyText: 'Pilih No Retur'
    });
   //Header Monitoring Retur Jual
    var headermonitoring_retur_jual = {
        layout: 'column',
        border: false,
        buttonAlign: 'left',
        items: [{
                columnWidth: .5,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: {labelSeparator: ''},
                items: [ {
					xtype: 'datefield',
					fieldLabel: 'Tgl Awal',
					emptyText: 'Tanggal Awal',
					name: 'tgl_awal_retur',
					id: 'mrj_tgl_awal_retur',
					maxLength: 255,
					anchor: '90%',
					value: '',
					format: 'd-M-Y'
				},cbmrj_retur
                                
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
					name: 'tgl_akhir_retur',
					id: 'mrj_tgl_akhir_retur',
					maxLength: 255,
					anchor: '90%',
					value: '',
					format: 'd-M-Y'
				},cbmrj_salesorder
			]
		}],buttons: [{
			text: 'Filter',
			formBind: true,
			handler: function () {
				gridmonitoring_retur_jual.store.load({
					params: {
						tgl_awal: Ext.getCmp('cfp_tgl_pembayaran_awal').getValue(),
						tgl_akhir: Ext.getCmp('cfp_tgl_pembayaran_akhir').getValue(),
						no_so: Ext.getCmp('id_cbmrj_salesorder').getValue(),
                                                no_retur: Ext.getCmp('id_cbmrj_retur').getValue()
					}
				});
			}
		},{
                text: 'Reset',
                handler: function() {
                    clearmonitoring_retur_jual(); 
                }
            }]
    };
    
    // START GRID Monitoring Retur Jual
    var strmonitoring_retur_jual = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'no_retur',
                'tgl_retur',
                'no_so', 
                'pct_potongan',
                'rp_jumlah',
                'rp_diskon',
                'rp_potongan',
                'rp_total',
                'remark',
                'kd_lokasi',
                'kd_blok',
                'kd_sub_blok',
                'status',
                'no_so_retur',
                'dpp',
                'ppn',
                'rp_grand_total'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("monitoring_retur_jual/get_rows") ?>',
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
    strmonitoring_retur_jual.on('load', function(){
        Ext.getCmp('idsearch_monitoring_retur_jual').focus();
    });
    // search field
    var search_monitoring_retur_jual = new Ext.app.SearchField({
        store: strmonitoring_retur_jual,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        emptyText : 'Cari No Retur',
        id: 'idsearch_monitoring_retur_jual'
    });

    // top toolbar
    var tb_monitoring_retur_jual = new Ext.Toolbar({
        items: [search_monitoring_retur_jual]
    });
     search_monitoring_retur_jual.onTrigger1Click = function(evt) {
        if (this.hasSearch) {
            this.el.dom.value = '';
			
            // Get the value of search field
            var fid = Ext.getCmp('iop_kd_supplier').getValue();
            var o = { start: 0, kd_supplier: fid };
			
            this.store.baseParams = this.store.baseParams || {};
            this.store.baseParams[this.paramName] = '';
            this.store.reload({
                params : o
            });
            this.triggers[0].hide();
            this.hasSearch = false;
        }
    };
	
    search_monitoring_retur_jual.onTrigger2Click = function(evt) {
        var text = this.getRawValue();
        if (text.length < 1) {
            this.onTrigger1Click();
            return;
        }
	 
        // Get the value of search field
        var fid = Ext.getCmp('iop_kd_supplier').getValue();
        var o = { start: 0, kd_supplier: fid };
	 
        this.store.baseParams = this.store.baseParams || {};
        this.store.baseParams[this.paramName] = text;
        this.store.reload({params:o});
        this.hasSearch = true;
        this.triggers[0].show();
    };
    // checkbox grid
    var smgridmonitoring_retur_jual = new Ext.grid.CheckboxSelectionModel();
    var smgridDetaiMRJ = new Ext.grid.CheckboxSelectionModel();

    // data store
    var strmonitoring_retur_jualdetail = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                    'kd_produk',
                    'nama_produk',
                    'no_retur',
                    'qty',
                    'rp_disk',
                    'rp_jumlah',
                    'rp_potongan',
                    'rp_total',
                    'kd_lokasi',
                    'kd_blok',
                    'kd_sub_blok',
                    'qty_retur_do',
                    'qty_retur_so',
                    'no_do'
                     ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("monitoring_retur_jual/get_rows_detail") ?>',
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

    strmonitoring_retur_jual.on('load', function() {
        strmonitoring_retur_jualdetail.removeAll();
    })

    var gridmonitoring_retur_jual = new Ext.grid.EditorGridPanel({
        id: 'gridmonitoring_retur_jual',
        frame: true,
        border: true,
        stripeRows: true,
        sm: smgridmonitoring_retur_jual,
        store: strmonitoring_retur_jual,
        loadMask: true,
        title: 'Data Retur Penjualan',
        style: 'margin:0 auto;',
        height: 225,
        // width: 550,
        columns: [{
                header: "No Retur",
                dataIndex: 'no_retur',
                // hidden: true,
                sortable: true,
                width: 120
            }, {
                header: "Tanggal Retur",
                dataIndex: 'tgl_retur',
                sortable: true,
                width: 100
            },{
                header: "No SO/Struk",
                dataIndex: 'no_so',
                sortable: true,
                width: 120
            }, {
                xtype: 'numbercolumn',
                header: "Rp Jumlah",
                dataIndex: 'rp_jumlah',
                sortable: true,
                format :'0,0',
                width: 100
            }, {
                xtype: 'numbercolumn',
                header: "Rp Potongan",
                dataIndex: 'rp_potongan',
                sortable: true,
                format :'0,0',
                width: 100
            },{
                xtype: 'numbercolumn',
                header: "Rp Total",
                dataIndex: 'rp_total',
                sortable: true,
                format :'0,0',
                width: 100
            },{
                xtype: 'numbercolumn',
                header: "DPP",
                dataIndex: 'dpp',
                sortable: true,
                width: 100,
                format :'0,0'
            },{
                xtype: 'numbercolumn',
                header: "PPN",
                dataIndex: 'ppn',
                sortable: true,
                width: 100,
                format :'0,0'
            },{
                xtype: 'numbercolumn',
                header: "Grand Total",
                dataIndex: 'rp_grand_total',
                sortable: true,
                format :'0,0',
                width: 100
            }],
        listeners: {
            'rowclick': function() {
                var sm = gridmonitoring_retur_jual.getSelectionModel();
                var sel = sm.getSelections();
                gridDetaiMRJ.store.proxy.conn.url = '<?= site_url("monitoring_retur_jual/get_rows_detail") ?>/' + sel[0].get('no_retur');
                gridDetaiMRJ.store.reload();
            }
        },
        tbar: [tb_monitoring_retur_jual],
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strmonitoring_retur_jual,
            displayInfo: true
        })
    });
    // shorthand alias
    var fm = Ext.form;

    var cmm = new Ext.ux.grid.LockingColumnModel({
        // specify any defaults for each column
        defaults: {
            sortable: true // columns are not sortable by default           
        },
        columns: [
            {
                header: "No Retur",
                dataIndex: 'no_retur',
                sortable: true,
                width: 100
            }, {
                header: "Kode Produk",
                dataIndex: 'kd_produk',
                sortable: true,
                format :'0,0',
                width: 120
            }, {
                header: "Nama Produk",
                dataIndex: 'nama_produk',
                sortable: true,
                format :'0,0',
                width: 180
            }, {
                xtype: 'numbercolumn',
                header: 'Qty',
                dataIndex: 'qty',           
                width: 100,
                sortable: true,
                align: 'right',
                format :'0,0',
                
            },{
                xtype: 'numbercolumn',
                header: 'Diskon',
                dataIndex: 'rp_disk',           
                width: 120,
                sortable: true,
                format :'0,0',
                align: 'right'
                
            },{
                xtype: 'numbercolumn',
                header: 'Rp Jumlah',
                dataIndex: 'rp_jumlah',           
                width: 100,
                sortable: true,
                format :'0,0',
                align: 'right'
                
            },{
                xtype: 'numbercolumn',
                header: 'Potongan',
                dataIndex: 'rp_potongan',           
                width: 100,
                sortable: true,
                format :'0,0',
                align: 'right'
                
            },{
                xtype: 'numbercolumn',
                header: 'Total',
                dataIndex: 'rp_total',           
                width: 100,
                sortable: true,
                format :'0,0',
                align: 'right'
                
            }]
    });

    var gridDetaiMRJ = new Ext.grid.EditorGridPanel({
        id: 'gridDetaiMRJ',
        store: strmonitoring_retur_jualdetail,
        stripeRows: true,
        style: 'margin-bottom:5px;',
        height: 225,
        frame: true,
        border: true,
        loadMask: true,
        sm: smgridDetaiMRJ,
        // plugins: [action_approval_detail_approve_manager,action_approval_detail_reject_manager],
        view: new Ext.ux.grid.LockingGridView(),
        cm: cmm,
    });
    // Form Panel
    var monitoring_retur_jual = new Ext.FormPanel({
        id: 'monitoring_retur_jual',
        border: false,
        frame: true,
        monitorValid: true,
        labelWidth: 130,
        items: [{
                bodyStyle: {
                    margin: '0px 0px 15px 0px'
                },
                items: [headermonitoring_retur_jual]
            },
           gridmonitoring_retur_jual,
           gridDetaiMRJ

        ]
    });
    function clearmonitoring_retur_jual(){
		Ext.getCmp('monitoring_retur_jual').getForm().reset();
		strmonitoring_retur_jual.removeAll();
		strmonitoring_retur_jualdetail.removeAll();
            }
</script>
