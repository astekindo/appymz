<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">
 // START GRID Pelunasan Piutang Print
    var strpelunasanpiutangprint = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'no_pelunasan_piutang',
                'tanggal',
                'rp_faktur',
                'rp_total_dibayar',
                'keterangan'
                
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("cetak_pelunasan_piutang/get_rows") ?>',
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
    strpelunasanpiutangprint.on('load', function(){
        Ext.getCmp('idsearch_pelunasan_piutang_print').focus();
    });
    // search field
    var search_pelunasan_piutang_print = new Ext.app.SearchField({
        store: strpelunasanpiutangprint,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        emptyText : 'Cari No Bukti',
        id: 'idsearch_pelunasan_piutang_print'
    });

    // top toolbar
    var tb_invoice_order_print = new Ext.Toolbar({
        items: [search_pelunasan_piutang_print]
    });
     search_pelunasan_piutang_print.onTrigger1Click = function(evt) {
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
	
    search_pelunasan_piutang_print.onTrigger2Click = function(evt) {
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
    var smgridpelunasanpiutangprint = new Ext.grid.CheckboxSelectionModel();
    var smgridDetailCppprint = new Ext.grid.CheckboxSelectionModel();

    // data store
    var strpelunasanpiutangprintdetail = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'no_pelunasan_piutang',
                'no_faktur',
                'rp_faktur',
                'tgl_faktur',
                'rp_bayar',
                               
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("cetak_pelunasan_piutang/get_rows_detail") ?>',
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

    strpelunasanpiutangprint.on('load', function() {
        strpelunasanpiutangprintdetail.removeAll();
    })

    var gridpelunasanpiutangprint = new Ext.grid.EditorGridPanel({
        id: 'gridpelunasanpiutangprint',
        frame: true,
        border: true,
        stripeRows: true,
        sm: smgridpelunasanpiutangprint,
        store: strpelunasanpiutangprint,
        loadMask: true,
        title: 'Data Pembayaran Piutang',
        style: 'margin:0 auto;',
        height: 225,
        // width: 550,
        columns: [{
                header: "No Bukti",
                dataIndex: 'no_pelunasan_piutang',
                // hidden: true,
                sortable: true,
                width: 150
            }, {
                header: "Tanggal Pembayaran",
                dataIndex: 'tanggal',
                sortable: true,
                width: 150
            }, {
                xtype: 'numbercolumn',
                header: "Jumlah Faktur / Struk",
                dataIndex: 'rp_faktur',
                sortable: true,
                width: 160
            }, {
                xtype: 'numbercolumn',
                header: "Total Bayar",
                dataIndex: 'rp_total_dibayar',
                sortable: true,
                format :'0,0',
                width: 120
            }, {
                //xtype: 'numbercolumn',
                header: "Keterangan",
                dataIndex: 'keterangan',
                sortable: true,
                width: 200
            }],
        listeners: {
            'rowclick': function() {
                var sm = gridpelunasanpiutangprint.getSelectionModel();
                var sel = sm.getSelections();
                gridDetailCppprint.store.proxy.conn.url = '<?= site_url("cetak_pelunasan_piutang/get_rows_detail") ?>/' + sel[0].get('no_pelunasan_piutang');
                gridDetailCppprint.store.reload();
            }
        },
        tbar: [tb_invoice_order_print,{
                icon: BASE_ICONS + 'grid.png',
                text: 'View Pembayaran',
                handler: function() {
                    
				var sm = gridpelunasanpiutangprint.getSelectionModel();
				var sel = sm.getSelections();
				
				if (sel.length > 0) {

                                    Ext.Ajax.request({
                                    url: '<?= site_url("cetak_pelunasan_piutang/get_data_pembayaran") ?>/' + sel[0].get('no_pelunasan_piutang'),
                                    method: 'POST',
                                    params: {},
                                    callback: function (opt, success, responseObj) {
                                        var windowviewpembayaranpiutang = new Ext.Window({
                                            title: 'View Pembayaran Piutang',
                                            width: 850,
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
            store: strpelunasanpiutangprint,
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
                header: "No Faktur/Struk",
                dataIndex: 'no_faktur',
                sortable: true,
                width: 150
            }, 
            {
                header: "Tanggal Faktur/Struk",
                dataIndex: 'tgl_faktur',
                sortable: true,
                width: 130
            }, {
                xtype: 'numbercolumn',
                header: "Rp Faktur/Struk",
                dataIndex: 'rp_faktur',
                sortable: true,
                format :'0,0',
                width: 120
            }, {
                xtype: 'numbercolumn',
                header: 'Rp Bayar',
                dataIndex: 'rp_bayar',           
                width: 120,
                sortable: true,
                format :'0,0',
                align: 'right'
                
            }]
    });

    var gridDetailCppprint = new Ext.grid.EditorGridPanel({
        id: 'gridDetailCppprint',
        store: strpelunasanpiutangprintdetail,
        stripeRows: true,
        style: 'margin-bottom:5px;',
        height: 225,
        frame: true,
        border: true,
        loadMask: true,
        sm: smgridDetailCppprint,
        // plugins: [action_approval_detail_approve_manager,action_approval_detail_reject_manager],
        view: new Ext.ux.grid.LockingGridView(),
        cm: cmm,
    });
 // twin combo no sales order
    var strcb_cpp_salesorder = new Ext.data.ArrayStore({
        fields: ['no_so'],
        data : []
    });
	
    var strgrid_cpp_salesorder = new Ext.data.Store({
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
	
    var searchgrid_cpp_salesorder = new Ext.app.SearchField({
        store: strgrid_cpp_salesorder,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE			
        },
        width: 350,
        id: 'id_searchgrid_cpp_salesorder'
    });
	
	
    var grid_cpp_salesorder = new Ext.grid.GridPanel({
        store: strgrid_cpp_salesorder,
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
            items: [searchgrid_cpp_salesorder]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgrid_cpp_salesorder,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function(){			
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {				
                    Ext.getCmp('id_cbcppsalesorder').setValue(sel[0].get('no_so'));
                   // Ext.getCmp('id_pjret_tglso').setValue(sel[0].get('tgl_so'));
                    //strpenjualanretur.removeAll();       
                    menu_cpp_salesorder.hide();
                 }
            }
        }
    });
	
    var menu_cpp_salesorder = new Ext.menu.Menu();
    menu_cpp_salesorder.add(new Ext.Panel({
        title: 'Pilih No Sales Order',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 700,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [grid_cpp_salesorder],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menu_cpp_salesorder.hide();
                }
            }]
    }));
    
    Ext.ux.TwinComboCPPSalesOrder = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            //load store grid
            strgrid_cpp_salesorder.load();
            menu_cpp_salesorder.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
	
    menu_cpp_salesorder.on('hide', function(){
        var sf = Ext.getCmp('id_searchgrid_cpp_salesorder').getValue();
        if( sf != ''){
            Ext.getCmp('id_searchgrid_cpp_salesorder').setValue('');
            searchgrid_cpp_salesorder.onTrigger2Click();
        }
    });
	
    //var mask =new Ext.LoadMask(Ext.getBody(),{msg:'Loading data...', store: strpenjualanretur});
        
    var cbcppsalesorder = new Ext.ux.TwinComboCPPSalesOrder({
        fieldLabel: 'No Faktur/Struk',
        id: 'id_cbcppsalesorder',
        store: strcb_cpp_salesorder,
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
    var headerpelunasanpiutangprint = {
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
					id: 'cpp_tgl_pembayaran_awal',
					maxLength: 255,
					anchor: '90%',
					value: '',
					format: 'd-M-Y'
				},cbcppsalesorder
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
					id: 'cpp_tgl_pembayaran_akhir',
					maxLength: 255,
					anchor: '90%',
					value: '',
					format: 'd-M-Y'
				}//,cbvrjmember
			]
		}],buttons: [{
			text: 'Filter',
			formBind: true,
			handler: function () {
				gridpelunasanpiutangprint.store.load({
					params: {
						tgl_awal: Ext.getCmp('cpp_tgl_pembayaran_awal').getValue(),
						tgl_akhir: Ext.getCmp('cpp_tgl_pembayaran_akhir').getValue(),
						no_so: Ext.getCmp('id_cbcppsalesorder').getValue(),					
					}
				});
			}
		}]
    }

// Form Panel
    var cetakpelunasanpiutang = new Ext.FormPanel({
        id: 'cetakpelunasanpiutang',
        border: false,
        frame: true,
        monitorValid: true,
        labelWidth: 130,
        items: [{
                bodyStyle: {
                    margin: '0px 0px 15px 0px'
                },
                items: [headerpelunasanpiutangprint]
            },gridpelunasanpiutangprint,
            gridDetailCppprint

        ],
        buttons: [{
                text: 'Cetak',
                handler: function() {

                    var sm = gridpelunasanpiutangprint.getSelectionModel();                
                    var sel = sm.getSelections(); 	
                    wincetakpelunasanpiutangprint.show();
                    Ext.getDom('cetakpelunasanpiutangprint').src = '<?= site_url("cetak_pelunasan_piutang/print_form") ?>'+'/'+sel[0].get('no_pelunasan_piutang');
                }
            }, {
                text: 'Reset',
                handler: function() {
                    clearpelunasanpiutangprint(); 
                }
            }]
    });
     var wincetakpelunasanpiutangprint = new Ext.Window({
        id: 'id_wincetakpelunasanpiutangprint',
	title: 'Pembayaran Hutang Form',
        closeAction: 'hide',
        width: 900,
        height: 450,
        layout: 'fit',
        border: false,
        html:'<iframe style="width:100%;height:100%;" id="cetakpelunasanpiutangprint" src=""></iframe>'
    });
    function clearpelunasanpiutangprint(){
		Ext.getCmp('cetakpelunasanpiutang').getForm().reset();
		strpelunasanpiutangprint.removeAll();
		strpelunasanpiutangprintdetail.removeAll();
            }
</script>
