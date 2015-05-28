<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript"> 
    var strcbkpopsuplier = new Ext.data.ArrayStore({
        fields: ['nama_supplier'],
        data : []
    });
	
    var strgridkpopsuplier = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_supplier', 'nama_supplier'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("pembelian_create_request/search_supplier") ?>',
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
	
    strgridkpopsuplier.on('load', function(){
        Ext.getCmp('id_searchgridkpopsuplier').focus();
    });
	
    var searchgridkpopsuplier = new Ext.app.SearchField({
        store: strgridkpopsuplier,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE			
        },
        width: 350,
        id: 'id_searchgridkpopsuplier'
    });
	
    var gridkpopsuplier = new Ext.grid.GridPanel({
        store: strgridkpopsuplier,
        stripeRows: true,
        frame: true,
        border:true,
        columns: [{
                header: 'Kode Supplier',
                dataIndex: 'kd_supplier',
                width: 80,
                sortable: true,			
            
            },{
                header: 'Nama Supplier',
                dataIndex: 'nama_supplier',
                width: 300,
                sortable: true,         
            }],
        tbar: new Ext.Toolbar({
            items: [searchgridkpopsuplier]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridkpopsuplier,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function(){			
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {				
                    // Ext.getCmp('kpop_kd_supplier').setValue(sel[0].get('kd_supplier'));
                    Ext.getCmp('id_cbkpopsuplier').setValue(sel[0].get('nama_supplier'));
                    strkonsinyasipurchaseorderprint.load({
                        params:{
                            kd_supplier: sel[0].get('kd_supplier')
                        }
                    });       
                    menukpopsuplier.hide();
                }
            }
        }
    });
	
    var menukpopsuplier = new Ext.menu.Menu();
    menukpopsuplier.add(new Ext.Panel({
        title: 'Pilih Supplier',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridkpopsuplier],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menukpopsuplier.hide();
                }
            }]
    }));
    
    Ext.ux.TwinCombokpopSuplier = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            //load store grid
            strgridkpopsuplier.load();
            menukpopsuplier.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
	
    menukpopsuplier.on('hide', function(){
        var sf = Ext.getCmp('id_searchgridkpopsuplier').getValue();
        if( sf != ''){
            Ext.getCmp('id_searchgridkpopsuplier').setValue('');
            searchgridkpopsuplier.onTrigger2Click();
        }
    });
	
    var cbkpopsuplier = new Ext.ux.TwinCombokpopSuplier({
        fieldLabel: 'Supplier',
        id: 'id_cbkpopsuplier',
        store: strcbkpopsuplier,
        mode: 'local',
        valueField: 'nama_supplier',
        displayField: 'nama_supplier',
        typeAhead: true,
        triggerAction: 'all',
        // allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'nama_supplier',
        emptyText: 'Pilih Supplier'
    });
	   
    var headerkonsinyasipurchaseorderprint = {
        layout: 'column',
        border: false,
        items: [{
                columnWidth: .5,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: { labelSeparator: ''},
                items: [cbkpopsuplier]
            }]
    }

    /* START GRID */
    var strkonsinyasipurchaseorderprint = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'kd_suplier_po',
                'nama_supplier',
                'no_po',
                'tanggal_po',
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({			
               
            url: '<?= site_url("konsinyasi_purchase_order_print/get_rows") ?>',
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
	
    // search field
    var search_konsinyasi_purchase_order_print = new Ext.app.SearchField({
        store: strkonsinyasipurchaseorderprint,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        id: 'idsearch_konsinyasi_purchase_order_print'
    });
    
    // top toolbar
    var tb_konsinyasi_purchase_order_print = new Ext.Toolbar({
        items: [search_konsinyasi_purchase_order_print, '->', '<i>Klik row untuk melihat detail PO</i>']
    });
    
    // checkbox grid
    var smgridkpoprint = new Ext.grid.CheckboxSelectionModel();
    var smgridDetkpoprint = new Ext.grid.CheckboxSelectionModel();
    
    // data store
    var strkonsinyasipurchaseorderprintdetail = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'kd_produk',
                'nama_produk',
                'qty_po',
                'nm_satuan'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("konsinyasi_purchase_order_print/get_rows_detail") ?>',
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
	
    strkonsinyasipurchaseorderprint.on('load', function(){
        strkonsinyasipurchaseorderprintdetail.removeAll();
    })
	

	
    var gridkpoprint = new Ext.grid.EditorGridPanel({
        id: 'gridkpoprint',
        frame: true,
        border: true,
        stripeRows: true,
        sm: smgridkpoprint,
        store: strkonsinyasipurchaseorderprint,
        loadMask: true,
        title: 'Surat Pesanan',
        style: 'margin:0 auto;',
        height: 225,
        // width: 550,
        columns: [{
                header: "Kode Supplier",
                dataIndex: 'kd_suplier_po',
                sortable: true,
                width: 150
            },{
                header: "Nama Supplier",
                dataIndex: 'nama_supplier',
                sortable: true,
                width: 250
            },{
                header: "No Surat Pesanan",
                dataIndex: 'no_po',
                // hidden: true,
                sortable: true,
                width: 150
            },{
                header: "Tanggal",
                dataIndex: 'tanggal_po',
                sortable: true,
                width: 80
            }],
        listeners: {
            'rowclick': function(){              
                var sm = gridkpoprint.getSelectionModel();                
                var sel = sm.getSelections(); 				
                gridDetkpoprint.store.proxy.conn.url = '<?= site_url("konsinyasi_purchase_order_print/get_rows_detail") ?>/' + sel[0].get('no_po');
                gridDetkpoprint.store.reload();
            }          
        }
        // tbar: tb_konsinyasi_purchase_order_print,
        //bbar: new Ext.PagingToolbar({
        //    pageSize: ENDPAGE,
        //    store: strkonsinyasipurchaseorderprint,
        //    displayInfo: true
        //})
    });

    // shorthand alias
    var fm = Ext.form;

    var cmm = new Ext.ux.grid.LockingColumnModel({
        // specify any defaults for each column
        defaults: {
            sortable: true // columns are not sortable by default           
        },
        columns: [ {
                header: "Kode Barang",
                dataIndex: 'kd_produk',
                sortable: true,
                width: 250
            },{
                header: "Nama Barang",
                dataIndex: 'nama_produk',
                sortable: true,
                width: 250
            },{
                header: "Qty",
                dataIndex: 'qty_po',
                sortable: true,
                width: 50
            },{
                header: "Satuan",
                dataIndex: 'nm_satuan',
                sortable: true,
                width: 50
            }],
        
    });	
	
    var gridDetkpoprint = new Ext.grid.EditorGridPanel({
        id: 'gridDetkpoprint',
        store: strkonsinyasipurchaseorderprintdetail,
        stripeRows: true,
        style: 'margin-bottom:5px;',
        height: 225,
        frame: true,
        border:true,
        loadMask: true,
        sm: smgridDetkpoprint,
        plugins: [action_approval_detail_approve_manager,action_approval_detail_reject_manager],
        view: new Ext.ux.grid.LockingGridView(),
        cm: cmm,
    });	
	
	
    var winprintkonsinyasipurchaseorderprint = new Ext.Window({
        id: 'id_winprintkonsinyasipurchaseorderprint',
        title: 'Print Purchase order Form',
        closeAction: 'hide',
        width: 900,
        height: 450,
        layout: 'fit',
        border: false,
        html:'<iframe style="width:100%;height:100%;" id="printkonsinyasipurchaseorderprint" src=""></iframe>'
    });
     var winprintkonsinyasisuratpesananprint = new Ext.Window({
        id: 'id_winprintkonsinyasisuratpesananprint',
        title: 'Print Surat Pesanan Form',
        closeAction: 'hide',
        width: 900,
        height: 450,
        layout: 'fit',
        border: false,
        html:'<iframe style="width:100%;height:100%;" id="printkonsinyasisuratpesananprint" src=""></iframe>'
    });
    
    var winprintkonsinyasipurchaseordernonhargaprint = new Ext.Window({
        id: 'id_winprintkonsinyasipurchaseordernonhargaprint',
        title: 'Print Purchase Order Non Harga Form',
        closeAction: 'hide',
        width: 900,
        height: 450,
        layout: 'fit',
        border: false,
        html:'<iframe style="width:100%;height:100%;" id="printkonsinyasipurchaseordernonhargaprint" src=""></iframe>'
    });
	
    var konsinyasipurchaseorderprint = new Ext.FormPanel({
        id: 'konsinyasipurchaseorderprint',
        border: false,
        frame: true,
        monitorValid: true,
        labelWidth: 130,
        items: [{
                bodyStyle: {
                    margin: '0px 0px 15px 0px'
                },					
                items: [headerkonsinyasipurchaseorderprint]
            },gridkpoprint, gridDetkpoprint
				
        ],
        buttons: [{
                text: 'Cetak PO Non Harga',
                handler: function(){
                    var sm = gridkpoprint.getSelectionModel();                
                    var sel = sm.getSelections(); 	
                    winprintkonsinyasipurchaseordernonhargaprint.show();
                    Ext.getDom('printkonsinyasipurchaseordernonhargaprint').src = '<?= site_url("pembelian_create_po/print_form_non_harga") ?>'+'/'+sel[0].get('no_po');
                }
            },{
                text: 'Cetak PO',
                handler: function(){
						 
                    var sm = gridkpoprint.getSelectionModel();                
                    var sel = sm.getSelections(); 	
                    winprintkonsinyasipurchaseorderprint.show();
                    Ext.getDom('printkonsinyasipurchaseorderprint').src = '<?= site_url("pembelian_purchase_order_print/print_form") ?>'+'/'+sel[0].get('no_po');
                }
            },{
                text: 'Cetak Surat Pesanan',
                handler: function(){
						 
                    var sm = gridkpoprint.getSelectionModel();                
                    var sel = sm.getSelections(); 	
                    winprintkonsinyasisuratpesananprint.show();
                    Ext.getDom('printkonsinyasisuratpesananprint').src = '<?= site_url("pembelian_purchase_order_print/print_form_surat_pesanan") ?>'+'/'+sel[0].get('no_po');
                }
            },{
                text: 'Reset',
                handler: function(){
                    clearkonsinyasipurchaseorderprint(); 
                }
            }]
    });
		
    function clearkonsinyasipurchaseorderprint(){
        Ext.getCmp('konsinyasipurchaseorderprint').getForm().reset();
        strkonsinyasipurchaseorderprint.removeAll();
        strkonsinyasipurchaseorderprintdetail.removeAll();
    }
</script>