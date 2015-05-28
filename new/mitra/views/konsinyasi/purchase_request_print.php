<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript"> 
    var strcbkrpsuplier = new Ext.data.ArrayStore({
        fields: ['nama_supplier'],
        data : []
    });
	
    var strgridkrpsuplier = new Ext.data.Store({
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
	
    strgridkrpsuplier.on('load', function(){
        Ext.getCmp('id_searchgridkrpsuplier').focus();
    });
	
    var searchgridkrpsuplier = new Ext.app.SearchField({
        store: strgridkrpsuplier,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE			
        },
        width: 350,
        id: 'id_searchgridkrpsuplier'
    });
	
    var gridkrpsuplier = new Ext.grid.GridPanel({
        store: strgridkrpsuplier,
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
            items: [searchgridkrpsuplier]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridkrpsuplier,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function(){			
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {				
                    // Ext.getCmp('krp_kd_supplier').setValue(sel[0].get('kd_supplier'));
                    Ext.getCmp('id_cbkrpsuplier').setValue(sel[0].get('nama_supplier'));
                    strkonsinyasipurchaserequestprint.load({
                        params:{
                            kd_supplier: sel[0].get('kd_supplier')
                        }
                    });       
                    menukrpsuplier.hide();
                }
            }
        }
    });
	
    var menukrpsuplier = new Ext.menu.Menu();
    menukrpsuplier.add(new Ext.Panel({
        title: 'Pilih Supplier',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridkrpsuplier],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menukrpsuplier.hide();
                }
            }]
    }));
    
    Ext.ux.TwinComboPrpSuplier = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            //load store grid
            strgridkrpsuplier.load();
            menukrpsuplier.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
	
    menukrpsuplier.on('hide', function(){
        var sf = Ext.getCmp('id_searchgridkrpsuplier').getValue();
        if( sf != ''){
            Ext.getCmp('id_searchgridkrpsuplier').setValue('');
            searchgridkrpsuplier.onTrigger2Click();
        }
    });
	
    var cbkrpsuplier = new Ext.ux.TwinComboPrpSuplier({
        fieldLabel: 'Supplier',
        id: 'id_cbkrpsuplier',
        store: strcbkrpsuplier,
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
	   
    var headerkonsinyasipurchaserequestprint = {
        layout: 'column',
        border: false,
        items: [{
                columnWidth: .5,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: { labelSeparator: ''},
                items: [cbkrpsuplier]
            }]
    }

    /* START GRID */
    var strkonsinyasipurchaserequestprint = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'no_ro',
                'kd_supplier',
                'nama_supplier',
                'tgl_ro',
                'subject',
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({			
               
            url: '<?= site_url("konsinyasi_purchase_request_print/get_rows") ?>',
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
    var search_purchase_request_print = new Ext.app.SearchField({
        store: strkonsinyasipurchaserequestprint,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        id: 'idsearch_purchase_request_print'
    });
    
    // top toolbar
    var tb_purchase_request_print = new Ext.Toolbar({
        items: [search_purchase_request_print, '->', '<i>Klik row untuk melihat detail RO</i>']
    });
    
    // checkbox grid
    var smgridKPRPrint = new Ext.grid.CheckboxSelectionModel();
    var smgridDetKPRPrint = new Ext.grid.CheckboxSelectionModel();
    
    // data store
    var strkonsinyasipurchaserequestprintdetail = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'kd_produk',
                'nama_produk',
                'qty_adj',
                'nm_satuan'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("konsinyasi_purchase_request_print/get_rows_detail") ?>',
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
	
    strkonsinyasipurchaserequestprint.on('load', function(){
        strkonsinyasipurchaserequestprintdetail.removeAll();
    })
	
	
    var editorpembelianapprovalrequestmanager = new Ext.ux.grid.RowEditor({
        saveText: 'Update'
    });
	
    var gridKPRPrint = new Ext.grid.EditorGridPanel({
        id: 'gridKPRPrint',
        frame: true,
        border: true,
        stripeRows: true,
        sm: smgridKPRPrint,
        store: strkonsinyasipurchaserequestprint,
        loadMask: true,
        title: 'KR',
        style: 'margin:0 auto;',
        height: 225,
        // width: 550,
        columns: [{
                header: "No KR",
                dataIndex: 'no_ro',
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
                header: "Tanggal",
                dataIndex: 'tgl_ro',
                sortable: true,
                width: 80
            },{
                header: "Subject",
                dataIndex: 'subject',
                sortable: true,
                width: 300
            }],
        listeners: {
            'rowclick': function(){              
                var sm = gridKPRPrint.getSelectionModel();                
                var sel = sm.getSelections(); 				
                gridDetKPRPrint.store.proxy.conn.url = '<?= site_url("konsinyasi_purchase_request_print/get_rows_detail") ?>/' + sel[0].get('no_ro');
                gridDetKPRPrint.store.reload();
            }          
        }
        // tbar: tb_purchase_request_print,
        //bbar: new Ext.PagingToolbar({
        //    pageSize: ENDPAGE,
        //    store: strkonsinyasipurchaserequestprint,
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
                dataIndex: 'qty_adj',
                sortable: true,
                width: 50
            },{
                header: "Satuan",
                dataIndex: 'nm_satuan',
                sortable: true,
                width: 50
            }],
        
    });	
	
    var gridDetKPRPrint = new Ext.grid.EditorGridPanel({
        id: 'gridDetKPRPrint',
        store: strkonsinyasipurchaserequestprintdetail,
        stripeRows: true,
        style: 'margin-bottom:5px;',
        height: 225,
        frame: true,
        border:true,
        loadMask: true,
        sm: smgridDetKPRPrint,
        plugins: [action_approval_detail_approve_manager,action_approval_detail_reject_manager],
        view: new Ext.ux.grid.LockingGridView(),
        cm: cmm,
    });	
	
	
    var winprintkonsinyasipurchaserequestprint = new Ext.Window({
        id: 'id_winprintkonsinyasipurchaserequestprint',
        title: 'Print Purchase Request Form',
        closeAction: 'hide',
        width: 900,
        height: 450,
        layout: 'fit',
        border: false,
        html:'<iframe style="width:100%;height:100%;" id="printkonsinyasipurchaserequestprint" src=""></iframe>'
    });
	
    var konsinyasipurchaserequestprint = new Ext.FormPanel({
        id: 'konsinyasipurchaserequestprint',
        border: false,
        frame: true,
        monitorValid: true,
        labelWidth: 130,
        items: [{
                bodyStyle: {
                    margin: '0px 0px 15px 0px'
                },					
                items: [headerkonsinyasipurchaserequestprint]
            },gridKPRPrint, gridDetKPRPrint
				
        ],
        buttons: [{
                text: 'Cetak',
                handler: function(){
						 
                    var sm = gridKPRPrint.getSelectionModel();                
                    var sel = sm.getSelections(); 	
                    winprintkonsinyasipurchaserequestprint.show();
                    Ext.getDom('printkonsinyasipurchaserequestprint').src = '<?= site_url("pembelian_create_request/print_form") ?>'+'/'+sel[0].get('no_ro');
                }
            },{
                text: 'Reset',
                handler: function(){
                    clearkonsinyasipurchaserequestprint(); 
                }
            }]
    });
		
    function clearkonsinyasipurchaserequestprint(){
        Ext.getCmp('konsinyasipurchaserequestprint').getForm().reset();
        strkonsinyasipurchaserequestprint.removeAll();
        strkonsinyasipurchaserequestprintdetail.removeAll();
    }
</script>