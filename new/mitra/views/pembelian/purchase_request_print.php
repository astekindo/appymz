<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript"> 
    var strcbprpsuplier = new Ext.data.ArrayStore({
        fields: ['nama_supplier'],
        data : []
    });
	
    var strgridprpsuplier = new Ext.data.Store({
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
	
    strgridprpsuplier.on('load', function(){
        Ext.getCmp('id_searchgridprpsuplier').focus();
    });
	
    var searchgridprpsuplier = new Ext.app.SearchField({
        store: strgridprpsuplier,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE			
        },
        width: 350,
        id: 'id_searchgridprpsuplier'
    });
	
    var gridprpsuplier = new Ext.grid.GridPanel({
        store: strgridprpsuplier,
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
            items: [searchgridprpsuplier]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridprpsuplier,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function(){			
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {				
                    // Ext.getCmp('prp_kd_supplier').setValue(sel[0].get('kd_supplier'));
                    Ext.getCmp('id_cbprpsuplier').setValue(sel[0].get('nama_supplier'));
                    strpurchaserequestprint.load({
                        params:{
                            kd_supplier: sel[0].get('kd_supplier')
                        }
                    });       
                    menuprpsuplier.hide();
                }
            }
        }
    });
	
    var menuprpsuplier = new Ext.menu.Menu();
    menuprpsuplier.add(new Ext.Panel({
        title: 'Pilih Supplier',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridprpsuplier],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menuprpsuplier.hide();
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
            strgridprpsuplier.load();
            menuprpsuplier.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
	
    menuprpsuplier.on('hide', function(){
        var sf = Ext.getCmp('id_searchgridprpsuplier').getValue();
        if( sf != ''){
            Ext.getCmp('id_searchgridprpsuplier').setValue('');
            searchgridprpsuplier.onTrigger2Click();
        }
    });
	
    var cbprpsuplier = new Ext.ux.TwinComboPrpSuplier({
        fieldLabel: 'Supplier',
        id: 'id_cbprpsuplier',
        store: strcbprpsuplier,
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
	   
    var headerpurchaserequestprint = {
        layout: 'column',
        border: false,
        items: [{
                columnWidth: .5,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: { labelSeparator: ''},
                items: [cbprpsuplier]
            }]
    }

    /* START GRID */
    var strpurchaserequestprint = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'no_ro',
                'kd_supplier',
                'nama_supplier',
                'tgl_ro',
                'subject',
                'status'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({			
               
            url: '<?= site_url("pembelian_purchase_request_print/get_rows") ?>',
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
        store: strpurchaserequestprint,
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
    var smgridPRPrint = new Ext.grid.CheckboxSelectionModel();
    var smgridDetPRPrint = new Ext.grid.CheckboxSelectionModel();
    
    // data store
    var strpurchaserequestprintdetail = new Ext.data.Store({
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
            url: '<?= site_url("pembelian_purchase_request_print/get_rows_detail") ?>',
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
	
    strpurchaserequestprint.on('load', function(){
        strpurchaserequestprintdetail.removeAll();
    })
	
	
    var editorpembelianapprovalrequestmanager = new Ext.ux.grid.RowEditor({
        saveText: 'Update'
    });
	
    var gridPRPrint = new Ext.grid.EditorGridPanel({
        id: 'gridPRPrint',
        frame: true,
        border: true,
        stripeRows: true,
        sm: smgridPRPrint,
        store: strpurchaserequestprint,
        loadMask: true,
        title: 'PR',
        style: 'margin:0 auto;',
        height: 225,
        // width: 550,
        columns: [{
                header: "No PR",
                dataIndex: 'no_ro',
                // hidden: true,
                sortable: true,
                width: 120
            },{
                header: "Kode Supplier",
                dataIndex: 'kd_supplier',
                sortable: true,
                width: 120
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
                width: 200
            },{
                header: "Status",
                dataIndex: 'status',
                sortable: true,
                width: 200
            }],
        listeners: {
            'rowclick': function(){              
                var sm = gridPRPrint.getSelectionModel();                
                var sel = sm.getSelections(); 				
                gridDetPRPrint.store.proxy.conn.url = '<?= site_url("pembelian_purchase_request_print/get_rows_detail") ?>/' + sel[0].get('no_ro');
                gridDetPRPrint.store.reload();
            }          
        }
        // tbar: tb_purchase_request_print,
        //bbar: new Ext.PagingToolbar({
        //    pageSize: ENDPAGE,
        //    store: strpurchaserequestprint,
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
	
    var gridDetPRPrint = new Ext.grid.EditorGridPanel({
        id: 'gridDetPRPrint',
        store: strpurchaserequestprintdetail,
        stripeRows: true,
        style: 'margin-bottom:5px;',
        height: 225,
        frame: true,
        border:true,
        loadMask: true,
        sm: smgridDetPRPrint,
        plugins: [action_approval_detail_approve_manager,action_approval_detail_reject_manager],
        view: new Ext.ux.grid.LockingGridView(),
        cm: cmm,
    });	
	
	
    var winprintpurchaserequestprint = new Ext.Window({
        id: 'id_winprintpurchaserequestprint',
        title: 'Print Purchase Request Form',
        closeAction: 'hide',
        width: 900,
        height: 450,
        layout: 'fit',
        border: false,
        html:'<iframe style="width:100%;height:100%;" id="printpurchaserequestprint" src=""></iframe>'
    });
	
    var purchaserequestprint = new Ext.FormPanel({
        id: 'purchaserequestprint',
        border: false,
        frame: true,
        monitorValid: true,
        labelWidth: 130,
        items: [{
                bodyStyle: {
                    margin: '0px 0px 15px 0px'
                },					
                items: [headerpurchaserequestprint]
            },gridPRPrint, gridDetPRPrint
				
        ],
        buttons: [{
                text: 'Cetak',
                handler: function(){
						 
                    var sm = gridPRPrint.getSelectionModel();                
                    var sel = sm.getSelections(); 	
                    winprintpurchaserequestprint.show();
                    Ext.getDom('printpurchaserequestprint').src = '<?= site_url("pembelian_create_request/print_form") ?>'+'/'+sel[0].get('no_ro');
                }
            },{
                text: 'Reset',
                handler: function(){
                    clearpurchaserequestprint(); 
                }
            }]
    });
		
    function clearpurchaserequestprint(){
        Ext.getCmp('purchaserequestprint').getForm().reset();
        strpurchaserequestprint.removeAll();
        strpurchaserequestprintdetail.removeAll();
    }
</script>