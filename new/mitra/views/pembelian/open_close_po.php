<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript"> 
    var strcbsuplieropen_close_po = new Ext.data.ArrayStore({
        fields: ['nama_supplier'],
        data : []
    });
	
    var strgridpopsuplier_open_close_po = new Ext.data.Store({
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
	
    strgridpopsuplier_open_close_po.on('load', function(){
        Ext.getCmp('id_searchgridpopsuplier_open_close_po').focus();
    });
	
    var searchgridpopsuplier_open_close_po = new Ext.app.SearchField({
        store: strgridpopsuplier_open_close_po,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE			
        },
        width: 350,
        id: 'id_searchgridpopsuplier_open_close_po'
    });
    
     var gridpopsuplier_open_close_po = new Ext.grid.GridPanel({
        store: strgridpopsuplier_open_close_po,
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
            items: [searchgridpopsuplier_open_close_po]
        }),
        listeners: {
            'rowdblclick': function(){			
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {				
                     Ext.getCmp('ocp_kd_supplier').setValue(sel[0].get('kd_supplier'));
                    Ext.getCmp('id_cbpopsuplier_open_close_po').setValue(sel[0].get('nama_supplier'));
                   /* stropen_close_po.load({
                        params:{
                            kd_supplier: sel[0].get('kd_supplier')
                        }
                    });       */
                    menupopsuplier_open_close_po.hide();
                }
            }
        }
    });
	
    var menupopsuplier_open_close_po = new Ext.menu.Menu();
    menupopsuplier_open_close_po.add(new Ext.Panel({
        title: 'Pilih Supplier',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridpopsuplier_open_close_po],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menupopsuplier_open_close_po.hide();
                }
            }]
    }));
    
    Ext.ux.TwinComboOcpoSuplier = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            //load store grid
            strgridpopsuplier_open_close_po.load();
            menupopsuplier_open_close_po.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
	
    menupopsuplier_open_close_po.on('hide', function(){
        var sf = Ext.getCmp('id_searchgridpopsuplier_open_close_po').getValue();
        if( sf !== ''){
            Ext.getCmp('id_searchgridpopsuplier_open_close_po').setValue('');
            searchgridpopsuplier_open_close_po.onTrigger2Click();
        }
    });
	
    var cbpopsuplieropen_close_po = new Ext.ux.TwinComboOcpoSuplier({
        fieldLabel: 'Supplier',
        id: 'id_cbpopsuplier_open_close_po',
        store: strcbsuplieropen_close_po,
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
	   
    var headeropen_close_po = {
        layout: 'column',
        border: false,
        buttonAlign: 'left',
        items: [{
                columnWidth: .5,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: { labelSeparator: ''},
                items: [cbpopsuplieropen_close_po,{
                        xtype: 'datefield',
                        fieldLabel: 'Tanggal PO',
                        name: 'tanggal_awal',
                        id: 'ocp_tanggal_awal',
                        anchor: '90%',
                        emptyText: 'Tanggal Awal'
                        },{
                        xtype: 'datefield',
                        fieldLabel: 'Tgl Di Perpanjang',
                        name: 'tanggal_awal_perpanjang',
                        id: 'ocp_tanggal_awal_perpanjang',
                        anchor: '90%',
                        emptyText: 'Tanggal Awal Diperpanjang'
                        }
                    ]
                            
            },{
                columnWidth: .5,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: { labelSeparator: ''},
                items: [{
                            xtype: 'textfield',
                            fieldLabel: 'Kode Supplier',
                            name: 'kd_supplier',
                            readOnly: true,
                            fieldClass: 'readonly-input',
                            id: 'ocp_kd_supplier',
                            anchor: '90%',
                            value: '',
                            emptyText: 'Nama Supplier'
                        },{
                            xtype: 'datefield',
                            fieldLabel: 's/d',
                            name: 'tanggal_akhir',
                            id: 'ocp_tanggal_akhir',
                            anchor: '90%',
                            emptyText: 'Tanggal Akhir'
                        },{
                            xtype: 'datefield',
                            fieldLabel: 's/d',
                            name: 'tanggal_akhir_perpanjang',
                            id: 'ocp_tanggal_akhir_perpanjang',
                            anchor: '90%',
                            emptyText: 'Tanggal Akhir Diperpanjang'
                        }]
                      
            }],buttons: [{
			text: 'Filter',
			formBind: true,
			handler: function () {
				gridopen_close_po.store.load({
                                    params: {
                                            kd_supplier: Ext.getCmp('ocp_kd_supplier').getValue(),
                                            tgl_awal: Ext.getCmp('ocp_tanggal_awal').getValue(),
                                            tgl_akhir: Ext.getCmp('ocp_tanggal_akhir').getValue(),
                                            tgl_awal_diperpanjang: Ext.getCmp('ocp_tanggal_awal_perpanjang').getValue(),
                                            tgl_akhir_diperpanjang: Ext.getCmp('ocp_tanggal_akhir_perpanjang').getValue()
                                    }
				});
			}
		}]
    };

    /* START GRID */
    var stropen_close_po = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                {name: 'kd_suplier_po', allowBlank: true, type: 'text'},
                {name: 'nama_supplier', allowBlank: true, type: 'text'},
                {name: 'no_po', allowBlank: true, type: 'text'},
                {name: 'tanggal_po', allowBlank: true, type: 'text'},
                {name: 'tgl_berlaku_po', allowBlank: true, type: 'text'},
                {name: 'tgl_berlaku_po2', allowBlank: true, type: 'text'},
                {name: 'tgl_perpanjangan', allowBlank: true, type: 'text'}
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({			
               
            url: '<?= site_url("pembelian_open_close_po/get_rows") ?>' ,
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
    var search_open_close_po = new Ext.app.SearchField({
        store: stropen_close_po,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
            emptyText: 'No PO',
        id: 'idsearch_open_close_po'
    });
       stropen_close_po.on('load',function(){
        stropen_close_po.setBaseParam('kd_supplier',Ext.getCmp('ocp_kd_supplier').getValue());
        stropen_close_po.setBaseParam('tgl_awal',Ext.getCmp('ocp_tanggal_awal').getValue());
        stropen_close_po.setBaseParam('tgl_akhir',Ext.getCmp('ocp_tanggal_akhir').getValue());
        stropen_close_po.setBaseParam('tgl_awal_diperpanjang',Ext.getCmp('ocp_tanggal_awal_perpanjang').getValue());
        stropen_close_po.setBaseParam('tgl_akhir_diperpanjang',Ext.getCmp('ocp_tanggal_akhir_perpanjang').getValue());
               
    });
	
        
    // checkbox grid
    var smgridopen_close_po = new Ext.grid.CheckboxSelectionModel();
    var smgridDetopen_close_po = new Ext.grid.CheckboxSelectionModel();
    
    // data store
    var stropen_close_podetail = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'kd_produk',
                'nama_produk',
                'qty_po',
                'nm_satuan',
                'qty_ro',
                'qty_sisa'
                
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("pembelian_open_close_po/get_rows_detail") ?>',
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
	
    stropen_close_po.on('load', function(){
        stropen_close_podetail.removeAll();
    })
	
	
    var editorpembelianapprovalordermanager = new Ext.ux.grid.RowEditor({
        saveText: 'Update'
    });
	
    var gridopen_close_po = new Ext.grid.EditorGridPanel({
        id: 'gridopen_close_po',
        frame: true,
        border: true,
        stripeRows: true,
        sm: smgridopen_close_po,
        store: stropen_close_po,
        loadMask: true,
        style: 'margin:0 auto;',
        height: 225,
        //plugins: [editorpembelianapprovalordermanager],
        columns: [new Ext.grid.RowNumberer({width: 30}),
            {
                header: "Kode Supplier",
                dataIndex: 'kd_suplier_po',
                sortable: true,
                width: 90
            },{
                header: "Nama Supplier",
                dataIndex: 'nama_supplier',
                sortable: true,
                width: 200
            },{
                header: "No PO",
                dataIndex: 'no_po',
                // hidden: true,
                sortable: true,
                width: 100
            },{
                header: "Tanggal",
                dataIndex: 'tanggal_po',
                sortable: true,
                width: 80,
                align:'right'
            },{
                header: "Tanggal Berlaku",
                dataIndex: 'tgl_berlaku_po',
                sortable: true,
                width: 100,
                align:'right'
            },{
                header: "Tanggal Di Perpanjang",
                dataIndex: 'tgl_berlaku_po2',
                sortable: true,
                width: 170,
                align:'right',
                format: 'd/m/Y'
            },{
                xtype: 'datecolumn',
                header: 'Rencana Di Perpanjang',
                dataIndex: 'tgl_perpanjangan',
                width: 180,
                align: 'right',
                editor: new Ext.form.DateField({
                        id: 'ocpo_tgl_perpanjang',
                        format: 'd/m/Y',
                        //minValue: (new Date()).clearTime(),
                         listeners:{			
                            'change': function() {
                               	 // Ext.getCmp('editedsppbGrid').setValue('Y');
                            }
                        }
                    })
            }],
         tbar: [{
                ref: '../removeBtn',
                icon: BASE_ICONS + 'delete.gif',
                text: 'Remove',
                disabled: false,
                handler: function(){
                     //editorpembelianapprovalordermanager.stopEditing();
                        var s = gridopen_close_po.getSelectionModel().getSelections();
                        for(var i = 0, r; r = s[i]; i++){
                            stropen_close_po.remove(r);
                     }
                    stropen_close_podetail.load();
                }
            },search_open_close_po, '->', '<i>Klik row untuk melihat detail PO</i>'],
        listeners: {
            'rowclick': function(){              
                var sm = gridopen_close_po.getSelectionModel();                
                var sel = sm.getSelections(); 				
                gridDetopen_close_po.store.proxy.conn.url = '<?= site_url("pembelian_open_close_po/get_rows_detail") ?>/' + sel[0].get('no_po');
                gridDetopen_close_po.store.reload();
            }          
        },
        bbar: new Ext.PagingToolbar({
          pageSize: ENDPAGE,
           store: stropen_close_po,
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
                header: "Qty PO",
                dataIndex: 'qty_po',
                sortable: true,
                width: 50
            },
            {
                header: "Qty RO",
                dataIndex: 'qty_ro',
                sortable: true,
                width: 50
            },{
                header: "Qty Sisa",
                dataIndex: 'qty_sisa',
                sortable: true,
                width: 50
            },{
                header: "Satuan",
                dataIndex: 'nm_satuan',
                sortable: true,
                width: 50
            }]
        
    });	
	
    var gridDetopen_close_po = new Ext.grid.EditorGridPanel({
        id: 'gridDetopen_close_po',
        store: stropen_close_podetail,
        stripeRows: true,
        style: 'margin-bottom:5px;',
        height: 225,
        frame: true,
        border:true,
        loadMask: true,
        sm: smgridDetopen_close_po,
        //plugins: [action_approval_detail_approve_manager,action_approval_detail_reject_manager],
        view: new Ext.ux.grid.LockingGridView(),
        cm: cmm
    });	
	
    var open_close_po = new Ext.FormPanel({
        id: 'open_close_po',
        border: false,
        frame: true,
        monitorValid: true,
        labelWidth: 130,
        items: [{
                bodyStyle: {
                    margin: '0px 0px 15px 0px'
                },					
                items: [headeropen_close_po]
            },
            gridopen_close_po, 
            gridDetopen_close_po
				
        ],
        buttons: [{
                text: 'Save PO',
                handler: function(){
                    
                 var sm = gridopen_close_po.getSelectionModel();                
                    var sel = sm.getSelections(); 
                    var detailclose_po = new Array();
                    stropen_close_po.each(function(node) {
                        detailclose_po.push(node.data);
                    });
                                   	
                    Ext.getCmp('open_close_po').getForm().submit({
                        url: '<?= site_url("pembelian_open_close_po/update_row") ?>',
                        scope: this,
                        params: {						
                                        no_po: sel[0].get('no_po'),
                                        detail: Ext.util.JSON.encode(detailclose_po),                                      					
                                    },
                        waitMsg: 'Save PO...',
                        success: function(form, action){
                            var r = Ext.util.JSON.decode(action.response.responseText);
                           Ext.Msg.show({
                                            title: 'Success',
                                            msg: r.errMsg,
                                            modal: true,
                                            icon: Ext.Msg.INFO,
                                            buttons: Ext.Msg.OK,
                                            fn: function(btn){
                                                if (btn == 'ok') {
                                                    //winreturpenjualanprint.show();
                                                    // Ext.getDom('returpenjualanprint').src = r.printUrl;
                                                }
                                            }
                            });                     
                        
                            clearopen_close_po();                       
                        },
                        failure: function(form, action){        
                            var fe = Ext.util.JSON.decode(action.response.responseText);                        
                            Ext.Msg.show({
                                title: 'Error',
                                msg: fe.errMsg,
                                modal: true,
                                icon: Ext.Msg.ERROR,
                                buttons: Ext.Msg.OK,
                                fn: function(btn){
                                    if (btn == 'ok' && fe.errMsg == 'Session Expired') {
                                        window.location = '<?= site_url("auth/login") ?>';
                                    }
                                }
                            });
                        
                        }                   
                    }); 
                }
            },{
                text: 'Reset',
                handler: function(){
                    clearopen_close_po(); 
                }
            }]
    });
		
    function clearopen_close_po(){
        Ext.getCmp('open_close_po').getForm().reset();
        stropen_close_po.removeAll();
        stropen_close_podetail.removeAll();
    }
</script>