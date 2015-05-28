<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>

<script type="text/javascript"> 
    // twin combo supplier
    var strcb_salesdo_faktur = new Ext.data.ArrayStore({
        fields: ['no_so'],
        data : []
    });
	
    var strgrid_salesdo_faktur = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['no_so',
                'kd_member',
                'tgl_so',
                'kirim_so',
                'kirim_alamat_so',
                'kirim_telp_so',
                'rp_total',
                'rp_diskon',
                'rp_bank_charge',
                'rp_ongkos_kirim',
                'rp_ongkos_pasang',
                'rp_total_bayar',
                'kd_voucher',
                'qty_voucher',
                'no_open_saldo',
                'rp_diskon_tambahan',
                'keterangan',
                'rp_kurang_bayar'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("penjualan_do/search_faktur") ?>',
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
	
    var searchgrid_salesdo_faktur = new Ext.app.SearchField({
        store: strgrid_salesdo_faktur,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE			
        },
        width: 350,
        id: 'id_searchgrid_salesdo_faktur'
    });
	
	
    var grid_salesdo_faktur = new Ext.grid.GridPanel({
        store: strgrid_salesdo_faktur,
        stripeRows: true,
        frame: true,
        border:true,
        columns: [{header:'No SO',dataIndex:'no_so',width: 120,sortable: true},
            {header:'Rp Kurang Bayar',dataIndex:'rp_kurang_bayar',width: 100,sortable: true},
            {header:'Keterangan',dataIndex:'keterangan',width: 200,sortable: true},
            {header:'Tgl So',dataIndex:'tgl_so',width: 80,sortable: true},
            {header:'Kirim',dataIndex:'kirim_so',width: 150,sortable: true},
            {header:'Alamat',dataIndex:'kirim_alamat_so',width: 200,sortable: true},
            {header:'Telp',dataIndex:'kirim_telp_so',width: 100,sortable: true},
            //{header:'rp_total',dataIndex:'rp_total',width: 80,sortable: true},
            //{header:'rp_diskon',dataIndex:'rp_diskon',width: 80,sortable: true},
            //{header:'rp_bank_charge',dataIndex:'rp_bank_charge',width: 80,sortable: true},
            //{header:'rp_ongkos_kirim',dataIndex:'rp_ongkos_kirim',width: 80,sortable: true},
            //{header:'rp_ongkos_pasang',dataIndex:'rp_ongkos_pasang',width: 80,sortable: true},
            //{header:'rp_total_bayar',dataIndex:'rp_total_bayar',width: 80,sortable: true},
            //{header:'kd_voucher',dataIndex:'kd_voucher',width: 80,sortable: true},
            //{header:'qty_voucher',dataIndex:'qty_voucher',width: 80,sortable: true},
            //{header:'no_open_saldo',dataIndex:'no_open_saldo',width: 80,sortable: true},
            //{header:'rp_diskon_tambahan',dataIndex:'rp_diskon_tambahan',width: 80,sortable: true},
            ],
        tbar: new Ext.Toolbar({
            items: [searchgrid_salesdo_faktur]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgrid_salesdo_faktur,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function(){			
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {				
                    Ext.getCmp('id_sales_tglfaktur').setValue(sel[0].get('tgl_so'));
                    Ext.getCmp('id_sales_nofaktur_do').setValue(sel[0].get('no_so'));
                     
                    Ext.getCmp('id_pic_do').setValue(sel[0].get('kirim_so'));
                    Ext.getCmp('penjualandeliveryorder').getForm().findField('alm_penerima').setValue(sel[0].get('kirim_alamat_so'));
                    Ext.getCmp('id_telp_do').setValue(sel[0].get('kirim_telp_so'));
                    var vnoso=sel[0].get('no_so');
                    storesalesdo.reload({params:{no_so:vnoso}});
                                           
                    menu_salesdo_faktur.hide();
                }
            }
        }
    });
	
    var menu_salesdo_faktur = new Ext.menu.Menu();
    menu_salesdo_faktur.add(new Ext.Panel({
        title: 'Pilih No Struk',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [grid_salesdo_faktur],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menu_salesdo_faktur.hide();
                }
            }]
    }));
    
    Ext.ux.TwinComboReturBeliSupplier = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            //load store grid
            strgrid_salesdo_faktur.load();
            menu_salesdo_faktur.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
	
    menu_salesdo_faktur.on('hide', function(){
        var sf = Ext.getCmp('id_searchgrid_salesdo_faktur').getValue();
        if( sf != ''){
            Ext.getCmp('id_searchgrid_salesdo_faktur').setValue('');
            searchgrid_salesdo_faktur.onTrigger2Click();
        }
    });
	
    var cb_sales_nofaktur_do = new Ext.ux.TwinComboReturBeliSupplier({
        fieldLabel: 'No.Struk/SO <span class="asterix">*</span>',
        id: 'id_sales_nofaktur_do',
        store: strcb_salesdo_faktur,
        mode: 'local',
        valueField: 'no_so',
        displayField: 'no_so',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'no_so',
        emptyText: 'Pilih No Struk/SO'
    });
    
    var header_sales_do=
        {layout: 'column',
        border: false,
        items: [{columnWidth: .4,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: { labelSeparator: ''},
                items: [{
                        xtype: 'textfield',
                        fieldLabel: 'No.DO',
                        name: 'no_do',
                        allowBlank: true,
                        readOnly:true,
                        fieldClass:'readonly-input',
                        id: 'id_sales_do',
                        maxLength: 255,
                        anchor: '90%',
                        value:''
                    },{
                        xtype: 'datefield',
                        fieldLabel: 'Tanggal <span class="asterix">*</span>',
                        name: 'tgl_do',
                        id:'id_sales_tgldo',
                        allowBlank:false,   
                        format:'d-M-Y',  
                        editable:false,                                         
                        anchor: '90%',
                        value:new Date()
                    },{
                        xtype: 'datefield',
                        fieldLabel: 'Tanggal Kirim<span class="asterix">*</span>',
                        name: 'tgl_do_kirim',
                        id:'id_sales_tgldo_kirim',
                        allowBlank:false,   
                        format:'d-M-Y',  
                        editable:false,                                         
                        anchor: '90%'                        
                    }
                    
                ]
                
            },{columnWidth: .4,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: { labelSeparator: ''},
                items: [cb_sales_nofaktur_do,
                    {xtype: 'datefield',
                        fieldLabel: 'Tanggal Struk/SO <span class="asterix">*</span>',
                        name: 'tgl_faktur',
                        id:'id_sales_tglfaktur',
                        readOnly:true,
                        allowBlank:false,   
                        format:'d-M-Y',  
                        editable:false,                                         
                        anchor: '90%'   }]
            }]
    };    
    var storesalesdo= new Ext.data.Store({  
        //    autoSave:false,
        reader: new Ext.data.JsonReader({
            fields: [                
                {name: 'kd_produk', type: 'text'},                
                {name: 'nama_produk',  type: 'text'},  
                {name: 'qty', type: 'int'},
                {name: 'qty_oh', type: 'int'},
                {name: 'qty_do', type: 'int'},
                {name: 'qty_so', type: 'int'},
                {name: 'nm_satuan',  type: 'text'},
                //                {name: 'sub', allowBlank: false, type: 'text'}	,
                //                {name: 'nama_sub', allowBlank: false, type: 'text'}	
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        //        writer: new Ext.data.JsonWriter(
        //        {
        //			encode: true,
        //			writeAllFields: true
        //        })
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("penjualan_do/search_produk_nofaktur") ?>',
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
    
    var editorsalesdo = new Ext.ux.grid.RowEditor({
        saveText: 'Update'		
    });    
    var gridsalesdo=new Ext.grid.GridPanel({
        store: storesalesdo,
        stripeRows: true,
        height: 200,
        frame: true,        
        border:true,
        plugins:[editorsalesdo],
        columns: [{
                header: 'Kode produk',
                dataIndex: 'kd_produk',
                width: 110
            },{
                header: 'Nama Barang',
                dataIndex: 'nama_produk',
                width: 300
            },{
                header: 'Stok OH',
                dataIndex: 'qty_oh',
                width: 70,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'epdo_qty_oh'
                })
            },{
                header: 'Qty SO',
                dataIndex: 'qty_so',
                width: 50,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'epdo_qty_so'
                })
            },{
                header: 'Qty DO',
                dataIndex: 'qty_do',
                width: 50,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'epdo_qty_do'
                })
            },{
                header: 'Qty',
                dataIndex: 'qty',
                width: 50,
                editor: {
                    xtype: 'numberfield',
                    id: 'epdo_qty',
                    selectOnFocus:true,
                    listeners:{
                        'change': function(){
                            if(this.getValue() == ''){
                                
                                this.setValue('0');
                                
                            }
                            
                            if(Ext.getCmp('epdo_qty_so').getValue() == ''){                                
                                Ext.getCmp('epdo_qty_so').setValue('0');                                
                            }
                            
                            if(Ext.getCmp('epdo_qty_do').getValue() == ''){                                
                                Ext.getCmp('epdo_qty_do').setValue('0');                                
                            }
                            
                            if(this.getValue() > (Ext.getCmp('epdo_qty_so').getValue() - Ext.getCmp('epdo_qty_do').getValue())){
                                Ext.Msg.show({
                                    title: 'Error',
                                    msg: 'Quantity Melebihi Quantity SO !!',
                                    modal: true,
                                    icon: Ext.Msg.ERROR,
                                    buttons: Ext.Msg.OK			               
                                });
                                this.setValue('0');
                            }
                            
                            
                            if(this.getValue() > Ext.getCmp('epdo_qty_oh').getValue()){
                                Ext.Msg.show({
                                    title: 'Error',
                                    msg: 'Quantity Melebihi Quantity Stok !!',
                                    modal: true,
                                    icon: Ext.Msg.ERROR,
                                    buttons: Ext.Msg.OK			               
                                });
                                this.setValue('0');
                            }
                            
                            
                           
                        }
                    }
                }
            },{
                header: 'Satuan',
                dataIndex: 'nm_satuan',
                width: 80
            }
        ]
        ,tbar: [{
                ref: '../removeBtn',
                icon: BASE_ICONS + 'delete.gif',
                text: 'Remove',
                handler: function(){
                    editorsalesdo.stopEditing();
                    var s = gridsalesdo.getSelectionModel().getSelections();
                    for(var i = 0, r; r = s[i]; i++){
                        storesalesdo.remove(r);
                    }
                }
            }]
    });

    gridsalesdo.getSelectionModel().on('selectionchange', function(sm){
        gridsalesdo.removeBtn.setDisabled(sm.getCount() < 1);
    });
    
    var penjualando= new Ext.FormPanel({
        id: 'penjualandeliveryorder',
        border: false,
        frame: true,
        autoScroll:true,     
        monitorValid: true,       
        bodyStyle:'padding-right:20px;',        
        labelWidth: 130,
        items:[{
                bodyStyle: {
                    margin: '0px 0px 15px 0px'
                },                  
                items: [header_sales_do]},
            gridsalesdo,{
                layout: 'column',
                border: false,
                items: [{
                        columnWidth: .4,
                        style:'margin:6px 3px 0 0;',
                        layout: 'form', 
                        labelWidth: 120,                   
                        items: [{
                                xtype: 'textfield',
                                fieldLabel: 'PIC Penerima',
                                name: 'pic_terima',
                                //                                allowBlank: false,    
                                readOnly:true,
                                //                                fieldClass:'readonly-input',
                                id: 'id_pic_do',
                                maxLength: 255,
                                anchor: '90%',
                                value:''
                            },
                            {
                                xtype: 'textarea',
                                fieldLabel: 'Alamat Penerima',
                                name: 'alm_penerima',                                    
                                id: 'id_alm_penerima',   
                                readOnly:true,
                                //                                fieldClass:'readonly-input',
                                //                                allowBlank: false,
                                width: 300,
                                anchor: '90%'
                            },
                            {
                                xtype: 'textfield',
                                fieldLabel: 'Telepon Penerima',
                                name: 'telp_terima',
                                //                                allowBlank: false,     
                                readOnly:true,
                                //                                fieldClass:'readonly-input',
                                id: 'id_telp_do',
                                maxLength: 255,
                                anchor: '90%',
                                value:''
                            }
                        ]
                    },{
                        columnWidth: .4,
                        style:'margin:6px 3px 0 0;',
                        layout: 'form', 
                        labelWidth: 100,                   
                        items: [{
                                xtype: 'textarea',
                                fieldLabel: 'Keterangan <span class="asterix">*</span>',
                                name: 'keterangan', 
                                allowBlank:false,
                                id: 'id_keterangan_do',   
                                width: 300,
                                anchor: '90%'
                            }]}
                ]
            }
        ],
        buttons: [{
                text: 'Save',
                formBind: true, 
                handler: function(){
                    var detaildo = new Array();              
                    storesalesdo.each(function(node){
                        detaildo.push(node.data)
                    });
                    Ext.getCmp('penjualandeliveryorder').getForm().submit({
                        url: '<?= site_url("penjualan_do/update_row") ?>',
                        scope: this,
                        params: {
                            data: Ext.util.JSON.encode(detaildo)
                        },
                        waitMsg: 'Saving Data...',
                        success: function(form, action){
                            var r = Ext.util.JSON.decode(action.response.responseText);
                            Ext.Msg.show({
                                title: 'Success',
                                msg: 'Form submitted successfully',
                                modal: true,
                                icon: Ext.Msg.INFO,
                                buttons: Ext.Msg.OK,
                                fn: function(btn){
                                    if (btn == 'ok') {
                                        winpembelianreceiveorderprint.show();
                                        Ext.getDom('pembelianreceiveorderprint').src = r.printUrl;
                                    }
                                }
                            });                     
                        
                            clearsalesdo();                       
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
            },
            {
                text: 'Reset', handler: function(){clearsalesdo()}
            }],
        listeners:{
            afterrender:function(){
                
                this.getForm().load({
                    //url: '<?= site_url("penjualan_do/get_form") ?>',
                    failure: function(form, action){
                        var de = Ext.util.JSON.decode(action.response.responseText);
                        Ext.Msg.show({
                            title: 'Error',
                            msg: de.errMsg,
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK,
                            fn: function(btn){
                                if (btn == 'ok' && de.errMsg == 'Session Expired') {
                                    window.location = '<?= site_url("auth/login") ?>';
                                }
                            }
                        });
                    }
                });
            }
        }
    });
    
    var winpembelianreceiveorderprint = new Ext.Window({
        id: 'id_winpembelianreceiveorderprint',
        title: 'Print Delivery Order',
        closeAction: 'hide',
        width: 900,
        height: 450,
        layout: 'fit',
        border: false,
        //html: 'div id="lap_do" />'
        html:'<iframe style="width:100%;height:100%;" id="pembelianreceiveorderprint" src=""></iframe>'
    });

    function clearsalesdo(){
        Ext.getCmp('penjualandeliveryorder').getForm().reset();
        Ext.getCmp('penjualandeliveryorder').getForm().load({
            //url: '<?= site_url("penjualan_do/get_form") ?>',
            failure: function(form, action){
                var de = Ext.util.JSON.decode(action.response.responseText);
                Ext.Msg.show({
                    title: 'Error',
                    msg: de.errMsg,
                    modal: true,
                    icon: Ext.Msg.ERROR,
                    buttons: Ext.Msg.OK,
                    fn: function(btn){
                        if (btn == 'ok' && de.errMsg == 'Session Expired') {
                            window.location = '<?= site_url("auth/login") ?>';
                        }
                    }
                });
            }
        });
        storesalesdo.removeAll();
    }
</script>
