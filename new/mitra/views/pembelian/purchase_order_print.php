<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript"> 
    var strcbpopsuplier = new Ext.data.ArrayStore({
        fields: ['nama_supplier'],
        data : []
    });
	
    var strgridpopsuplier = new Ext.data.Store({
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
	
    strgridpopsuplier.on('load', function(){
        Ext.getCmp('id_searchgridpopsuplier').focus();
    });
	
    var searchgridpopsuplier = new Ext.app.SearchField({
        store: strgridpopsuplier,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE			
        },
        width: 350,
        id: 'id_searchgridpopsuplier'
    });
	
    var gridpopsuplier = new Ext.grid.GridPanel({
        store: strgridpopsuplier,
        stripeRows: true,
        frame: true,
        border:true,
        columns: [{
                header: 'Kode Supplier',
                dataIndex: 'kd_supplier',
                width: 80,
                sortable: true			
            
            },{
                header: 'Nama Supplier',
                dataIndex: 'nama_supplier',
                width: 300,
                sortable: true     
            }],
        tbar: new Ext.Toolbar({
            items: [searchgridpopsuplier]
        }),bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridpopsuplier,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function(){			
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {				
                    // Ext.getCmp('pop_kd_supplier').setValue(sel[0].get('kd_supplier'));
                    Ext.getCmp('id_cbpopsuplier').setValue(sel[0].get('nama_supplier'));
                    strpurchaseorderprint.load({
                        params:{
                            kd_supplier: sel[0].get('kd_supplier')
                        }
                    });       
                    menupopsuplier.hide();
                }
            }
        }
    });
	
    var menupopsuplier = new Ext.menu.Menu();
    menupopsuplier.add(new Ext.Panel({
        title: 'Pilih Supplier',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridpopsuplier],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menupopsuplier.hide();
                }
            }]
    }));
    
    Ext.ux.TwinCombopopSuplier = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            //load store grid
            strgridpopsuplier.load();
            menupopsuplier.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
	
    menupopsuplier.on('hide', function(){
        var sf = Ext.getCmp('id_searchgridpopsuplier').getValue();
        if( sf != ''){
            Ext.getCmp('id_searchgridpopsuplier').setValue('');
            searchgridpopsuplier.onTrigger2Click();
        }
    });
	
    var cbpopsuplier = new Ext.ux.TwinCombopopSuplier({
        fieldLabel: 'Supplier',
        id: 'id_cbpopsuplier',
        store: strcbpopsuplier,
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
	   
    var headerpurchaseorderprint = {
        layout: 'column',
        border: false,
        items: [{
                columnWidth: .5,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: { labelSeparator: ''},
                items: [cbpopsuplier]
            }]
    }

    /* START GRID */
    var strpurchaseorderprint = new Ext.data.Store({
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
               
            url: '<?= site_url("pembelian_purchase_order_print/get_rows") ?>' ,
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
    var search_purchase_order_print = new Ext.app.SearchField({
        store: strpurchaseorderprint,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        id: 'idsearch_purchase_order_print'
    });
    
    // top toolbar
    var tb_purchase_order_print = new Ext.Toolbar({
        items: [search_purchase_order_print, '->', '<i>Klik row untuk melihat detail PO</i>']
    });
    
    // checkbox grid
    var smgridpoprint = new Ext.grid.CheckboxSelectionModel();
    var smgridDetpoprint = new Ext.grid.CheckboxSelectionModel();
    
    // data store
    var strpurchaseorderprintdetail = new Ext.data.Store({
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
            url: '<?= site_url("pembelian_purchase_order_print/get_rows_detail") ?>',
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
	
    strpurchaseorderprint.on('load', function(){
        strpurchaseorderprintdetail.removeAll();
    })
	
	
    var editorpembelianapprovalordermanager = new Ext.ux.grid.RowEditor({
        saveText: 'Update'
    });
	
    var gridpoprint = new Ext.grid.EditorGridPanel({
        id: 'gridpoprint',
        frame: true,
        border: true,
        stripeRows: true,
        sm: smgridpoprint,
        store: strpurchaseorderprint,
        loadMask: true,
        title: 'PO',
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
                header: "No PO",
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
                var sm = gridpoprint.getSelectionModel();                
                var sel = sm.getSelections(); 				
                gridDetpoprint.store.proxy.conn.url = '<?= site_url("pembelian_purchase_order_print/get_rows_detail") ?>/' + sel[0].get('no_po');
                gridDetpoprint.store.reload();
            }          
        }
        // tbar: tb_purchase_order_print,
        //bbar: new Ext.PagingToolbar({
        //    pageSize: ENDPAGE,
        //    store: strpurchaseorderprint,
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
            }]
        
    });	
	
    var gridDetpoprint = new Ext.grid.EditorGridPanel({
        id: 'gridDetpoprint',
        store: strpurchaseorderprintdetail,
        stripeRows: true,
        style: 'margin-bottom:5px;',
        height: 225,
        frame: true,
        border:true,
        loadMask: true,
        sm: smgridDetpoprint,
        plugins: [action_approval_detail_approve_manager,action_approval_detail_reject_manager],
        view: new Ext.ux.grid.LockingGridView(),
        cm: cmm
    });	
	
	
    var winprintpurchaseorderprint = new Ext.Window({
        id: 'id_winprintpurchaseorderprint',
        title: 'Print Purchase Order Form',
        closeAction: 'hide',
        width: 900,
        height: 450,
        layout: 'fit',
        border: false,
        html:'<iframe style="width:100%;height:100%;" id="printpurchaseorderprint" src=""></iframe>'
    });
	
    var winprintpurchaseordernonhargaprint = new Ext.Window({
        id: 'id_winprintpurchaseordernonhargaprint',
        title: 'Print Purchase Order Non Harga Form',
        closeAction: 'hide',
        width: 900,
        height: 450,
        layout: 'fit',
        border: false,
        html:'<iframe style="width:100%;height:100%;" id="printpurchaseordernonhargaprint" src=""></iframe>'
    });
	
    var purchaseorderprint = new Ext.FormPanel({
        id: 'purchaseorderprint',
        border: false,
        frame: true,
        monitorValid: true,
        labelWidth: 130,
        items: [{
                bodyStyle: {
                    margin: '0px 0px 15px 0px'
                },					
                items: [headerpurchaseorderprint]
            },gridpoprint, gridDetpoprint
				
        ],
        buttons: [{
                text: 'Cetak PO Non Harga',
                handler: function(){
                    var sm = gridpoprint.getSelectionModel();                
                    var sel = sm.getSelections(); 	
                    winprintpurchaseordernonhargaprint.show();
                    Ext.getDom('printpurchaseordernonhargaprint').src = '<?= site_url("pembelian_create_po/print_form_non_harga") ?>'+'/'+sel[0].get('no_po');
                }
            },{
                text: 'Cetak',
                handler: function(){
                    var sm = gridpoprint.getSelectionModel();                
                    var sel = sm.getSelections(); 	
                    winprintpurchaseorderprint.show();
<!--                    Ext.getDom('printpurchaseorderprint').src = '--><?//= site_url("pembelian_create_po/print_form") ?><!--'+'/'+sel[0].get('no_po');-->
                    Ext.getDom('printpurchaseorderprint').src = '<?= site_url("pembelian_purchase_order_print/print_form") ?>'+'/'+sel[0].get('no_po');
                }
            },{
                text: 'Reset',
                handler: function(){
                    clearpurchaseorderprint(); 
                }
            }]
    });
		
    function clearpurchaseorderprint(){
        Ext.getCmp('purchaseorderprint').getForm().reset();
        strpurchaseorderprint.removeAll();
        strpurchaseorderprintdetail.removeAll();
    }
</script>