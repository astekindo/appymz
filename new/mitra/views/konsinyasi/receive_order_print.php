<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript"> 
    var strcbkropsuplier = new Ext.data.ArrayStore({
        fields: ['nama_supplier'],
        data : []
    });
	
    var strgridkropsuplier = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_supplier', 'nama_supplier'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("konsinyasi_receive_order/search_supplier") ?>',
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
	
    strgridkropsuplier.on('load', function(){
        Ext.getCmp('id_searchgridkropsuplier').focus();
    });
	
    var searchgridkropsuplier = new Ext.app.SearchField({
        store: strgridkropsuplier,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE			
        },
        width: 350,
        id: 'id_searchgridkropsuplier'
    });
	
    var gridkropsuplier = new Ext.grid.GridPanel({
        store: strgridkropsuplier,
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
            items: [searchgridkropsuplier]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridkropsuplier,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function(){			
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {				
                    // Ext.getCmp('krop_kd_supplier').setValue(sel[0].get('kd_supplier'));
                    Ext.getCmp('id_cbkropsuplier').setValue(sel[0].get('nama_supplier'));
                    strkonsinyasireceiveorderprint.load({
                        params:{
                            kd_supplier: sel[0].get('kd_supplier')
                        }
                    });       
                    menukropsuplier.hide();
                }
            }
        }
    });
	
    var menukropsuplier = new Ext.menu.Menu();
    menukropsuplier.add(new Ext.Panel({
        title: 'Pilih Supplier',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridkropsuplier],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menukropsuplier.hide();
                }
            }]
    }));
    
    Ext.ux.TwinCombokropSuplier = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            //load store grid
            strgridkropsuplier.load();
            menukropsuplier.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
	
    menukropsuplier.on('hide', function(){
        var sf = Ext.getCmp('id_searchgridkropsuplier').getValue();
        if( sf != ''){
            Ext.getCmp('id_searchgridkropsuplier').setValue('');
            searchgridkropsuplier.onTrigger2Click();
        }
    });
	
    var cbkropsuplier = new Ext.ux.TwinCombokropSuplier({
        fieldLabel: 'Supplier',
        id: 'id_cbkropsuplier',
        store: strcbkropsuplier,
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
	   
    var headerkonsinyasireceiveorderprint = {
        layout: 'column',
        border: false,
        items: [{
                columnWidth: .5,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: { labelSeparator: ''},
                items: [cbkropsuplier]
            }]
    }

    /* START GRID */
    var strkonsinyasireceiveorderprint = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'no_do',
                'kd_supplier',
                'nama_supplier',
                'tanggal',
                'tanggal_terima',
                'no_bukti_supplier',
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({			
               
            url: '<?= site_url("konsinyasi_receive_order_print/get_rows") ?>',
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
    var search_receive_order_print = new Ext.app.SearchField({
        store: strkonsinyasireceiveorderprint,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        id: 'idsearch_receive_order_print'
    });
    
    // top toolbar
    var tb_receive_order_print = new Ext.Toolbar({
        items: [search_receive_order_print, '->', '<i>Klik row untuk melihat detail RS</i>']
    });
    
    // checkbox grid
    var smgridkroprint = new Ext.grid.CheckboxSelectionModel();
    var smgridDetkroprint = new Ext.grid.CheckboxSelectionModel();
    
    // data store
    var strkonsinyasireceiveorderprintdetail = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'kd_produk',
                'nama_produk',
                'qty_beli',
                'qty_terima',
                'nm_satuan'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("konsinyasi_receive_order_print/get_rows_detail") ?>',
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
	
    strkonsinyasireceiveorderprint.on('load', function(){
        strkonsinyasireceiveorderprintdetail.removeAll();
    })
	
	
    var gridkroprint = new Ext.grid.EditorGridPanel({
        id: 'gridkroprint',
        frame: true,
        border: true,
        stripeRows: true,
        sm: smgridkroprint,
        store: strkonsinyasireceiveorderprint,
        loadMask: true,
        title: 'RS',
        style: 'margin:0 auto;',
        height: 225,
        // width: 550,
        columns: [{
                header: "No RS",
                dataIndex: 'no_do',
                // hidden: true,
                sortable: true,
                width: 150
            },{
                header: "Kode Supplier",
                dataIndex: 'kd_supplier',
                sortable: true,
                width: 150
            },{
                header: "Nama Supplier",
                dataIndex: 'nama_supplier',
                sortable: true,
                width: 250
            },{
                header: "Tanggal Input",
                dataIndex: 'tanggal',
                sortable: true,
                width: 80
            },{
                header: "Tanggal Terima",
                dataIndex: 'tanggal_terima',
                sortable: true,
                width: 90
            },{
                header: "No Bukti Supplier",
                dataIndex: 'no_bukti_supplier',
                sortable: true,
                width: 300
            }],
        listeners: {
            'rowclick': function(){              
                var sm = gridkroprint.getSelectionModel();                
                var sel = sm.getSelections(); 				
                gridDetkroprint.store.proxy.conn.url = '<?= site_url("konsinyasi_receive_order_print/get_rows_detail") ?>/' + sel[0].get('no_do');
                gridDetkroprint.store.reload();
            }          
        }
        // tbar: tb_purchase_request_print,
        //bbar: new Ext.PagingToolbar({
        //    pageSize: ENDPAGE,
        //    store: strkonsinyasireceiveorderprint,
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
                header: "Qty Beli",
                dataIndex: 'qty_beli',
                sortable: true,
                width: 50
            },{
                header: "Qty Terima",
                dataIndex: 'qty_terima',
                sortable: true,
                width: 90
            },{
                header: "Satuan",
                dataIndex: 'nm_satuan',
                sortable: true,
                width: 100
            }],
        
    });	
	
    var gridDetkroprint = new Ext.grid.EditorGridPanel({
        id: 'gridDetkroprint',
        store: strkonsinyasireceiveorderprintdetail,
        stripeRows: true,
        style: 'margin-bottom:5px;',
        height: 225,
        frame: true,
        border:true,
        loadMask: true,
        sm: smgridDetkroprint,
        plugins: [action_approval_detail_approve_manager,action_approval_detail_reject_manager],
        view: new Ext.ux.grid.LockingGridView(),
        cm: cmm,
    });	
	
	
    var winprintkonsinyasireceiveorderprint = new Ext.Window({
        id: 'id_winprintkonsinyasireceiveorderprint',
        title: 'Print Receive Order Form',
        closeAction: 'hide',
        width: 900,
        height: 450,
        layout: 'fit',
        border: false,
        html:'<iframe style="width:100%;height:100%;" id="printkonsinyasireceiveorderprint" src=""></iframe>'
    });
	
    var konsinyasireceiveorderprint = new Ext.FormPanel({
        id: 'konsinyasireceiveorderprint',
        border: false,
        frame: true,
        monitorValid: true,
        labelWidth: 130,
        items: [{
                bodyStyle: {
                    margin: '0px 0px 15px 0px'
                },					
                items: [headerkonsinyasireceiveorderprint]
            },gridkroprint, gridDetkroprint
				
        ],
        buttons: [{
                text: 'Cetak',
                handler: function(){
						 
                    var sm = gridkroprint.getSelectionModel();                
                    var sel = sm.getSelections(); 	
                    winprintkonsinyasireceiveorderprint.show();
                    Ext.getDom('printkonsinyasireceiveorderprint').src = '<?= site_url("konsinyasi_receive_order/print_form") ?>'+'/'+sel[0].get('no_do');
                }
            },{
                text: 'Reset',
                handler: function(){
                    clearkonsinyasireceiveorderprint(); 
                }
            }]
    });
		
    function clearkonsinyasireceiveorderprint(){
        Ext.getCmp('konsinyasireceiveorderprint').getForm().reset();
        strkonsinyasireceiveorderprint.removeAll();
        strkonsinyasireceiveorderprintdetail.removeAll();
    }
</script>