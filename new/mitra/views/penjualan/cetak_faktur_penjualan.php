<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">
 var strcbcfppelanggan = new Ext.data.ArrayStore({
        fields: ['kd_pelanggan'],
        data: []
    });

    var strgridcfppelanggan = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_pelanggan', 'nama_pelanggan', 'tipe','nama_tipe', 'alamat_kirim', 'no_telp', 'nama_sales', 'kd_sales'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("penjualan_distribusi/search_pelanggan") ?>',
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

    var searchgridcfppelanggan = new Ext.app.SearchField({
        store: strgridcfppelanggan,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgridcfppelanggan'
    });


    var gridcfppelanggan = new Ext.grid.GridPanel({
        store: strgridcfppelanggan,
        stripeRows: true,
        frame: true,
        border: true,
        columns: [{
                header: 'Kode Pelanggan',
                dataIndex: 'kd_pelanggan',
                width: 80,
                sortable: true
            }, {
                header: 'Nama Pelanggan',
                dataIndex: 'nama_pelanggan',
                width: 150,
                sortable: true
            },{
                header: 'Jenis Pelanggan',
                dataIndex: 'nama_tipe',
                width: 100,
                sortable: true
            }, {
                header: 'Kode tipe',
                dataIndex: 'tipe',
                width: 100,
                sortable: true
            }],
        tbar: new Ext.Toolbar({
            items: [searchgridcfppelanggan]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridcfppelanggan,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('id_cbcfppelanggan').setValue(sel[0].get('kd_pelanggan'));
                    Ext.getCmp('id_nama_cbcfppelanggan').setValue(sel[0].get('nama_pelanggan'));
                    menucfppelanggan.hide();
                }
            }
        }
    });

    var menucfppelanggan = new Ext.menu.Menu();
    menucfppelanggan.add(new Ext.Panel({
        title: 'Pilih Pelanggan',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridcfppelanggan],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menucfppelanggan.hide();
                }
            }]
    }));

    Ext.ux.TwinCombocfppelanggan = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            //load store grid
            strgridcfppelanggan.load();
            menucfppelanggan.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
                trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    menucfppelanggan.on('hide', function() {
        var sf = Ext.getCmp('id_searchgridcfppelanggan').getValue();
        if (sf !== '') {
            Ext.getCmp('id_searchgridcfppelanggan').setValue('');
            searchgridcfppelanggan.onTrigger2Click();
        }
    });

    var cbcfppelanggan = new Ext.ux.TwinCombocfppelanggan({
        fieldLabel: 'Pelanggan <span class="asterix">*</span>',
        id: 'id_nama_cbcfppelanggan',
        store: strcbcfppelanggan,
        mode: 'local',
        valueField: 'nama_pelanggan',
        displayField: 'nama_pelanggan',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'nama_pelanggan',
        emptyText: 'Pilih Pelanggan'
    });
 // START GRID Cetak Faktur Penjualan
    var strcetakfakturpenjualan = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'no_faktur',
                'tgl_faktur',
                'kd_pelanggan', 
                'rp_faktur',
                'rp_potongan',
                'rp_faktur_net',
                'rp_ppn',
                'rp_total_faktur',
                'no_so',
                'tgl_jatuh_tempo',
                'rp_uang_muka',
                'nama_pelanggan'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("cetak_faktur_penjualan/get_rows") ?>',
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
    strcetakfakturpenjualan.on('load', function(){
        Ext.getCmp('idsearch_cetak_faktur_penjualan').focus();
    });
    // search field
    var search_cetak_faktur_penjualan = new Ext.app.SearchField({
        store: strcetakfakturpenjualan,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        emptyText : 'Cari No Bukti',
        id: 'idsearch_cetak_faktur_penjualan'
    });

    // top toolbar
    var tb_cetak_faktur_jual = new Ext.Toolbar({
        items: [search_cetak_faktur_penjualan]
    });
     search_cetak_faktur_penjualan.onTrigger1Click = function(evt) {
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
	
    search_cetak_faktur_penjualan.onTrigger2Click = function(evt) {
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
    var smgridcetakfakturpenjualan = new Ext.grid.CheckboxSelectionModel();
    var smgridDetailCFPprint = new Ext.grid.CheckboxSelectionModel();

    // data store
    var strcetakfakturpenjualandetail = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                 'no_faktur',
                'no_sj',
                'no_do',
                'kd_produk',
                'qty',
                'rp_harga_jual',
                'rp_total_diskon',
                'rp_harga_net',
                'rp_jumlah',
                'rp_ekstra_diskon',
                'rp_total',
                'rp_diskon_satuan'
                               
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("cetak_faktur_penjualan/get_rows_detail") ?>',
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

    strcetakfakturpenjualan.on('load', function() {
        strcetakfakturpenjualandetail.removeAll();
    })

    var gridcetakfakturpenjualan = new Ext.grid.EditorGridPanel({
        id: 'gridcetakfakturpenjualan',
        frame: true,
        border: true,
        stripeRows: true,
        sm: smgridcetakfakturpenjualan,
        store: strcetakfakturpenjualan,
        loadMask: true,
        title: 'Data Faktur Penjualan',
        style: 'margin:0 auto;',
        height: 225,
        // width: 550,
        columns: [{
                header: "No Faktur",
                dataIndex: 'no_faktur',
                // hidden: true,
                sortable: true,
                width: 120
            }, {
                header: "Tanggal Faktur",
                dataIndex: 'tgl_faktur',
                sortable: true,
                width: 100
            },{
                header: "Pelanggan",
                dataIndex: 'nama_pelanggan',
                sortable: true,
                width: 150
            }, {
                header: "No SO",
                dataIndex: 'no_so',
                sortable: true,
                width: 100
            }, {
                xtype: 'numbercolumn',
                header: "Jumlah Faktur",
                dataIndex: 'rp_faktur',
                sortable: true,
                format :'0,0',
                width: 100
            },{
                xtype: 'numbercolumn',
                header: "Faktur Net",
                dataIndex: 'rp_faktur_net',
                sortable: true,
                format :'0,0',
                width: 100
            },{
                xtype: 'numbercolumn',
                header: "PPN",
                dataIndex: 'rp_ppn',
                sortable: true,
                width: 100,
                format :'0,0'
            },{
                xtype: 'numbercolumn',
                header: "Uang Muka",
                dataIndex: 'rp_uang_muka',
                sortable: true,
                format :'0,0',
                width: 100
            }, {
                xtype: 'numbercolumn',
                header: "Total Faktur",
                dataIndex: 'rp_total_faktur',
                sortable: true,
                format :'0,0',
                width: 120
            }],
        listeners: {
            'rowclick': function() {
                var sm = gridcetakfakturpenjualan.getSelectionModel();
                var sel = sm.getSelections();
                gridDetailCFPprint.store.proxy.conn.url = '<?= site_url("cetak_faktur_penjualan/get_rows_detail") ?>/' + sel[0].get('no_faktur');
                gridDetailCFPprint.store.reload();
            }
        },
        tbar: [tb_cetak_faktur_jual,{
                icon: BASE_ICONS + 'grid.png',
                text: 'View Faktur Jual',
                handler: function() {
                    
				var sm = gridcetakfakturpenjualan.getSelectionModel();
				var sel = sm.getSelections();
				
				if (sel.length > 0) {

                                    Ext.Ajax.request({
                                    url: '<?= site_url("cetak_faktur_penjualan/get_data_faktur") ?>/' + sel[0].get('no_faktur'),
                                    method: 'POST',
                                    params: {},
                                    callback: function (opt, success, responseObj) {
                                        var windowviewpembayaranpiutang = new Ext.Window({
                                            title: 'View Faktur Penjualan',
                                            width: 940,
                                            height: 500,
                                            autoScroll: true,
                                            html: responseObj.responseText
                                        });
                                        windowviewpembayaranpiutang.show();
                                    }
                                });
                            }
	
                        }
            }],
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strcetakfakturpenjualan,
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
        columns: [{
                header: "No Faktur",
                dataIndex: 'no_faktur',
                sortable: true,
                width: 120
            }, 
            {
                header: "No SJ",
                dataIndex: 'no_sj',
                sortable: true,
                width: 100
            }, {
                header: "Kode Produk",
                dataIndex: 'kd_produk',
                sortable: true,
                format :'0,0',
                width: 120
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
                header: 'Harga Jual',
                dataIndex: 'rp_harga_jual',           
                width: 120,
                sortable: true,
                format :'0,0',
                align: 'right'
                
            },{
                xtype: 'numbercolumn',
                header: 'Rp Diskon',
                dataIndex: 'rp_total_diskon',           
                width: 100,
                sortable: true,
                format :'0,0',
                align: 'right'
                
            },{
                xtype: 'numbercolumn',
                header: 'Harga Net',
                dataIndex: 'rp_harga_net',           
                width: 100,
                sortable: true,
                format :'0,0',
                align: 'right'
                
            },{
                xtype: 'numbercolumn',
                header: 'Jumlah',
                dataIndex: 'rp_jumlah',           
                width: 100,
                sortable: true,
                format :'0,0',
                align: 'right'
                
            }]
    });

    var gridDetailCFPprint = new Ext.grid.EditorGridPanel({
        id: 'gridDetailCFPprint',
        store: strcetakfakturpenjualandetail,
        stripeRows: true,
        style: 'margin-bottom:5px;',
        height: 225,
        frame: true,
        border: true,
        loadMask: true,
        sm: smgridDetailCFPprint,
        // plugins: [action_approval_detail_approve_manager,action_approval_detail_reject_manager],
        view: new Ext.ux.grid.LockingGridView(),
        cm: cmm,
    });
 // twin combo no sales order
    var strcb_cfp_salesorder = new Ext.data.ArrayStore({
        fields: ['no_so'],
        data : []
    });
	
    var strgrid_cfp_salesorder = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['no_so','tgl_so','rp_total','rp_diskon','rp_ekstra_diskon','rp_grand_total','rp_diskon_tambahan'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("cetak_faktur_penjualan/search_salesorder") ?>',
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
	
    var searchgrid_cfp_salesorder = new Ext.app.SearchField({
        store: strgrid_cfp_salesorder,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE			
        },
        width: 350,
        id: 'id_searchgrid_cfp_salesorder'
    });
	
	
    var grid_cfp_salesorder = new Ext.grid.GridPanel({
        store: strgrid_cfp_salesorder,
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
            items: [searchgrid_cfp_salesorder]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgrid_cfp_salesorder,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function(){			
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {				
                    Ext.getCmp('id_cbcfpsalesorder').setValue(sel[0].get('no_so'));
                   // Ext.getCmp('id_pjret_tglso').setValue(sel[0].get('tgl_so'));
                    //strpenjualanretur.removeAll();       
                    menu_cfp_salesorder.hide();
                 }
            }
        }
    });
	
    var menu_cfp_salesorder = new Ext.menu.Menu();
    menu_cfp_salesorder.add(new Ext.Panel({
        title: 'Pilih No Sales Order',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 700,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [grid_cfp_salesorder],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menu_cfp_salesorder.hide();
                }
            }]
    }));
    
    Ext.ux.TwinComboCFPSalesOrder = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            //load store grid
            strgrid_cfp_salesorder.load();
            menu_cfp_salesorder.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
	
    menu_cfp_salesorder.on('hide', function(){
        var sf = Ext.getCmp('id_searchgrid_cfp_salesorder').getValue();
        if( sf != ''){
            Ext.getCmp('id_searchgrid_cfp_salesorder').setValue('');
            searchgrid_cfp_salesorder.onTrigger2Click();
        }
    });
	
    //var mask =new Ext.LoadMask(Ext.getBody(),{msg:'Loading data...', store: strpenjualanretur});
        
    var cbcfpsalesorder = new Ext.ux.TwinComboCFPSalesOrder({
        fieldLabel: 'No Faktur',
        id: 'id_cbcfpsalesorder',
        store: strcb_cfp_salesorder,
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
 //Header Pelunasan Piutang Print
    var headercetakfakturpenjualan = {
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
					name: 'tgl_pelunasan_awal',
					id: 'cfp_tgl_pembayaran_awal',
					maxLength: 255,
					anchor: '90%',
					value: '',
					format: 'd-M-Y'
				},cbcfppelanggan,
                                {       xtype: 'textfield',
					name: 'kd_pelanggan',
					id: 'id_cbcfppelanggan',
					maxLength: 255,
					anchor: '90%',
                                        hidden: true
                                }
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
					name: 'tgl_pelunasan_akhir',
					id: 'cfp_tgl_pembayaran_akhir',
					maxLength: 255,
					anchor: '90%',
					value: '',
					format: 'd-M-Y'
				},cbcfpsalesorder
			]
		}],buttons: [{
			text: 'Filter',
			formBind: true,
			handler: function () {
				gridcetakfakturpenjualan.store.load({
					params: {
						tgl_awal: Ext.getCmp('cfp_tgl_pembayaran_awal').getValue(),
						tgl_akhir: Ext.getCmp('cfp_tgl_pembayaran_akhir').getValue(),
						no_so: Ext.getCmp('id_cbcfpsalesorder').getValue(),
                                                kd_pelanggan: Ext.getCmp('id_cbcfppelanggan').getValue(),
					}
				});
			}
		}]
    }

// Form Panel
    var cetakfakturpenjualan = new Ext.FormPanel({
        id: 'cetakfakturpenjualan',
        border: false,
        frame: true,
        monitorValid: true,
        labelWidth: 130,
        items: [{
                bodyStyle: {
                    margin: '0px 0px 15px 0px'
                },
                items: [headercetakfakturpenjualan]
            },gridcetakfakturpenjualan,
            gridDetailCFPprint

        ],
        buttons: [{
                text: 'Cetak',
                handler: function() {

                    var sm = gridcetakfakturpenjualan.getSelectionModel();                
                    var sel = sm.getSelections(); 	
                    wincetakfakturpenjualanprint.show();
                    Ext.getDom('cetakfakturpenjualanprint').src = '<?= site_url("faktur_penjualan/print_form") ?>'+'/'+sel[0].get('no_faktur');
                }
            }, {
                text: 'Reset',
                handler: function() {
                    clearcetakfakturpenjualanprint(); 
                }
            }]
    });
     var wincetakfakturpenjualanprint = new Ext.Window({
        id: 'id_wincetakfakturpenjualanprint',
	title: 'Faktur Penjualan Form',
        closeAction: 'hide',
        width: 900,
        height: 450,
        layout: 'fit',
        border: false,
        html:'<iframe style="width:100%;height:100%;" id="cetakfakturpenjualanprint" src=""></iframe>'
    });
    function clearcetakfakturpenjualanprint(){
		Ext.getCmp('cetakfakturpenjualan').getForm().reset();
		strcetakfakturpenjualan.removeAll();
		strcetakfakturpenjualandetail.removeAll();
            }
</script>
