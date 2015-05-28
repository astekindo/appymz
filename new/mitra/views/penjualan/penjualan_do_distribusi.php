<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>

<script type="text/javascript"> 
    //Start Combo Pelanggan
var strcbpdodistpelanggan = new Ext.data.ArrayStore({
        fields: ['kd_pelanggan'],
        data: []
    });

    var strgridpdodistpelanggan = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_pelanggan', 'nama_pelanggan', 'tipe','nama_tipe', 'alamat_kirim', 'no_telp'],
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

    var searchgridpdodistpelanggan = new Ext.app.SearchField({
        store: strgridpdodistpelanggan,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgridpdodistpelanggan'
    });


    var gridpdodistpelanggan = new Ext.grid.GridPanel({
        store: strgridpdodistpelanggan,
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
                width: 120,
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
            items: [searchgridpdodistpelanggan]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridpdodistpelanggan,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('pdodist_kd_pelanggan').setValue(sel[0].get('kd_pelanggan'));
                    Ext.getCmp('id_cbpdodistpelanggan').setValue(sel[0].get('nama_pelanggan'));
                    menupdodistpelanggan.hide();
                }
            }
        }
    });

    var menupdodistpelanggan = new Ext.menu.Menu();
    menupdodistpelanggan.add(new Ext.Panel({
        title: 'Pilih Pelanggan',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridpdodistpelanggan],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menupdodistpelanggan.hide();
                }
            }]
    }));

    Ext.ux.TwinCombofppelanggan = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            //load store grid
            strgridpdodistpelanggan.load();
            menupdodistpelanggan.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
                trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    menupdodistpelanggan.on('hide', function() {
        var sf = Ext.getCmp('id_searchgridpdodistpelanggan').getValue();
        if (sf !== '') {
            Ext.getCmp('id_searchgridpdodistpelanggan').setValue('');
            searchgridpdodistpelanggan.onTrigger2Click();
        }
    });

    var cbpdodistpelanggan = new Ext.ux.TwinCombofppelanggan({
        fieldLabel: 'Pelanggan <span class="asterix">*</span>',
        id: 'id_cbpdodistpelanggan',
        store: strcbpdodistpelanggan,
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
    // End Combo Pelanggan
    // twin combo so
    var strcb_do_faktur_dist = new Ext.data.ArrayStore({
        fields: ['no_so'],
        data : []
    });
	
    var strgrid_do_faktur_dist = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['no_so',
                'kd_member',
                'tgl_so',
                'qty_so',
                'qty_do',
                'qty_sisa',
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
            url: '<?= site_url("penjualan_do_distribusi/search_faktur") ?>',
            method: 'POST'
        }),
        listeners: {
            
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg === 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });
	
    var searchgrid_do_faktur_dist = new Ext.app.SearchField({
        store: strgrid_do_faktur_dist,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE			
        },
        width: 350,
        id: 'id_searchgrid_do_faktur_dist'
    });
    searchgrid_do_faktur_dist.onTrigger1Click = function(evt) {
        if (this.hasSearch) {
            this.el.dom.value = '';

            // Get the value of search field
            var fid = Ext.getCmp('pdodist_kd_pelanggan').getValue();
            var o = {start: 0, kd_pelanggan: fid};

            this.store.baseParams = this.store.baseParams || {};
            this.store.baseParams[this.paramName] = '';
            this.store.reload({
                params: o
            });
            this.triggers[0].hide();
            this.hasSearch = false;
        }
    };

    searchgrid_do_faktur_dist.onTrigger2Click = function(evt) {
        var text = this.getRawValue();
        if (text.length < 1) {
            this.onTrigger1Click();
            return;
        }

        // Get the value of search field
        var fid = Ext.getCmp('pdodist_kd_pelanggan').getValue();
        var o = {start: 0, kd_pelanggan: fid};

        this.store.baseParams = this.store.baseParams || {};
        this.store.baseParams[this.paramName] = text;
        this.store.reload({params: o});
        this.hasSearch = true;
        this.triggers[0].show();
    };
	
    var grid_do_faktur_dist = new Ext.grid.GridPanel({
        store: strgrid_do_faktur_dist,
        stripeRows: true,
        frame: true,
        border:true,
        columns: [{header:'No SO',dataIndex:'no_so',width: 120,sortable: true},
            {header:'Tgl SO',dataIndex:'tgl_so',width: 80,sortable: true},
            {header:'Qty SO',dataIndex:'qty_so',width: 70,sortable: true},
//            {header:'Qty DO',dataIndex:'qty_do',width: 70,sortable: true},
//            {header:'Qty Sisa',dataIndex:'qty_sisa',width: 70,sortable: true},
            {header:'Kirim',dataIndex:'kirim_so',width: 150,sortable: true},
            {header:'Alamat',dataIndex:'kirim_alamat_so',width: 200,sortable: true},
            {header:'Telp',dataIndex:'kirim_telp_so',width: 100,sortable: true}
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
            items: [searchgrid_do_faktur_dist]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgrid_do_faktur_dist,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function(){			
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {				
                    Ext.getCmp('id_tglfaktur_dist').setValue(sel[0].get('tgl_so'));
                    Ext.getCmp('id_nofaktur_dist').setValue(sel[0].get('no_so'));
                    
                    Ext.getCmp('id_pic_do_dist').setValue(sel[0].get('kirim_so'));
                    Ext.getCmp('deliveryorderdistribusi').getForm().findField('alm_penerima_dist').setValue(sel[0].get('kirim_alamat_so'));
                    Ext.getCmp('id_telp_do_dist').setValue(sel[0].get('kirim_telp_so'));
                    var vno_so=sel[0].get('no_so');
                    storesalesdo_dist.reload({params:{no_so:vno_so}});
                                           
                    menu_do_faktur_dist.hide();
                }
            }
        }
    });
	
    var menu_do_faktur_dist = new Ext.menu.Menu();
    menu_do_faktur_dist.add(new Ext.Panel({
        title: 'Pilih No SO',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [grid_do_faktur_dist],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menu_do_faktur_dist.hide();
                }
            }]
    }));
    
    Ext.ux.TwinComboNoFakturDist = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            //load store grid
            strgrid_do_faktur_dist.load({
                params: {
                    kd_pelanggan: Ext.getCmp('pdodist_kd_pelanggan').getValue(),
                    }
            });
            menu_do_faktur_dist.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
	
    menu_do_faktur_dist.on('hide', function(){
        var sf = Ext.getCmp('id_searchgrid_do_faktur_dist').getValue();
        if( sf !== ''){
            Ext.getCmp('id_searchgrid_do_faktur_dist').setValue('');
            searchgrid_do_faktur_dist.onTrigger2Click();
        }
    });
	
    var cb_nofaktur_do_dist = new Ext.ux.TwinComboNoFakturDist({
        fieldLabel: 'No.SO <span class="asterix">*</span>',
        id: 'id_nofaktur_dist',
        store: strcb_do_faktur_dist,
        mode: 'local',
        valueField: 'no_so',
        displayField: 'no_so',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'no_so',
        emptyText: 'Pilih No SO'
    });
    
    var header_do_distibusi=
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
                        name: 'no_do_dist',
                        allowBlank: true,
                        readOnly:true,
                        fieldClass:'readonly-input',
                        id: 'id_do_distribusi',
                        maxLength: 255,
                        anchor: '90%',
                        value:''
                    },{
                        xtype: 'datefield',
                        fieldLabel: 'Tanggal <span class="asterix">*</span>',
                        name: 'tgl_do_dist',
                        id:'id_tgldo_distribusi',
                        allowBlank:false,   
                        format:'d-M-Y',  
                        editable:false,                                         
                        anchor: '90%',
                         value:new Date()
                    },cbpdodistpelanggan
                    ,{
                        xtype: 'textfield',
                        hidden : true,
                        name: 'kd_pelanggan',
                        readOnly:true,
                        fieldClass:'readonly-input',
                        id: 'pdodist_kd_pelanggan',                
                        anchor: '90%',
                        value:''
             }
                ]
                
            },{columnWidth: .4,
                layout: 'form',
                border: false,
                labelWidth: 130,
                defaults: { labelSeparator: ''},
                items: [cb_nofaktur_do_dist,
                    {xtype: 'datefield',
                        fieldLabel: 'Tanggal SO <span class="asterix">*</span>',
                        name: 'tgl_faktur_dist',
                        id:'id_tglfaktur_dist',
                        readOnly:true,
                        allowBlank:false,   
                        format:'d-M-Y',  
                        editable:false, 
                        fieldClass:'readonly-input',
                        anchor: '90%'   },
                    {
                        xtype: 'datefield',
                        fieldLabel: 'Tanggal Kirim<span class="asterix">*</span>',
                        name: 'tgl_do_kirim_dist',
                        id:'id_tgldo_distribusi_kirim',
                        allowBlank:false,   
                        format:'d-M-Y',  
                        editable:false,
                        anchor: '90%',
                        minValue: (new Date()).clearTime() 
                    }]
            }]
    };    
    var storesalesdo_dist= new Ext.data.Store({  
        //    autoSave:false,
        reader: new Ext.data.JsonReader({
            fields: [                
                {name: 'kd_produk', type: 'text'},                
                {name: 'nama_produk',  type: 'text'},  
                {name: 'qty', type: 'int'},
                {name: 'qty_oh', type: 'int'},
                {name: 'qty_do', type: 'int'},
                {name: 'qty_so', type: 'int'},
                {name: 'nm_satuan',  type: 'text'}
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
            url: '<?= site_url("penjualan_do_distribusi/search_produk_nofaktur") ?>',
            method: 'POST'
        }),
        listeners: {
			
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg === 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });    
    
    var editorsalesdo_dist = new Ext.ux.grid.RowEditor({
        saveText: 'Update'		
    });    
    var gridsalesdo_dist=new Ext.grid.GridPanel({
        store: storesalesdo_dist,
        stripeRows: true,
        height: 200,
        frame: true,        
        border:true,
        plugins:[editorsalesdo_dist],
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
                    id: 'epdo_dist_qty_oh'
                })
            },{
                header: 'Qty SO',
                dataIndex: 'qty_so',
                width: 50,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'epdo_dist_qty_so'
                })
            },{
                header: 'Qty DO',
                dataIndex: 'qty_do',
                width: 50,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'epdo_dist_qty_do'
                })
            },{
                header: 'Qty',
                dataIndex: 'qty',
                width: 50,
                editor: {
                    xtype: 'numberfield',
                    id: 'epdo_dist_qty',
                    selectOnFocus:true,
                    listeners:{
                        'change': function(){
                            if(this.getValue() === ''){
                                
                                this.setValue('0');
                                
                            }
                            
                            if(Ext.getCmp('epdo_dist_qty_so').getValue() === ''){                                
                                Ext.getCmp('epdo_dist_qty_so').setValue('0');                                
                            }
                            
                            if(Ext.getCmp('epdo_dist_qty_do').getValue() === ''){                                
                                Ext.getCmp('epdo_dist_qty_do').setValue('0');                                
                            }
                            
                            if(this.getValue() > (Ext.getCmp('epdo_dist_qty_so').getValue() - Ext.getCmp('epdo_dist_qty_do').getValue())){
                                Ext.Msg.show({
                                    title: 'Error',
                                    msg: 'Quantity Melebihi Quantity SO !!',
                                    modal: true,
                                    icon: Ext.Msg.ERROR,
                                    buttons: Ext.Msg.OK			               
                                });
                                this.setValue('0');
                            }
                            
                            
                            if(this.getValue() > Ext.getCmp('epdo_dist_qty_oh').getValue()){
                                Ext.Msg.show({
                                    title: 'Error',
                                    msg: 'Quantity Melebihi Quantity Stok OH!!',
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
                    editorsalesdo_dist.stopEditing();
                    var s = gridsalesdo_dist.getSelectionModel().getSelections();
                    for(var i = 0, r; r = s[i]; i++){
                        storesalesdo_dist.remove(r);
                    }
                }
            }]
    });

    gridsalesdo_dist.getSelectionModel().on('selectionchange', function(sm){
        gridsalesdo_dist.removeBtn.setDisabled(sm.getCount() < 1);
    });
    // FORM PANEL DO DISTRIBUSI
    var deliveryorderdistribusi= new Ext.FormPanel({
        id: 'deliveryorderdistribusi',
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
                items: [header_do_distibusi]},
                        gridsalesdo_dist,
                {
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
                                id: 'id_pic_do_dist',
                                maxLength: 255,
                                anchor: '90%',
                                value:''
                            },
                            {
                                xtype: 'textarea',
                                fieldLabel: 'Alamat Penerima',
                                name: 'alm_penerima_dist',                                    
                                id: 'id_alm_penerima_dist',   
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
                                id: 'id_telp_do_dist',
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
                                id: 'id_keterangan_do_dist',   
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
                    storesalesdo_dist.each(function(node){
                        detaildo.push(node.data);
                    });
                    Ext.getCmp('deliveryorderdistribusi').getForm().submit({
                        url: '<?= site_url("penjualan_do_distribusi/update_row") ?>',
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
                                    if (btn === 'ok') {
                                        winpenjualandodistprint.show();
                                        Ext.getDom('penjualandodistprint').src = r.printUrl;
                                    }
                                }
                            });                     
                        
                            clearsalesdo_dist();                       
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
                                    if (btn === 'ok' && fe.errMsg === 'Session Expired') {
                                        window.location = '<?= site_url("auth/login") ?>';
                                    }
                                }
                            });
                        
                        }                   
                    }); 
                }
            },
            {
                text: 'Reset', handler: function(){clearsalesdo_dist();}
            }],
        listeners:{
            afterrender:function(){
                
                this.getForm().load({
                    url: '<?= site_url("penjualan_do_distribusi/get_form") ?>',
                    failure: function(form, action){
                        var de = Ext.util.JSON.decode(action.response.responseText);
                        Ext.Msg.show({
                            title: 'Error',
                            msg: de.errMsg,
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK,
                            fn: function(btn){
                                if (btn === 'ok' && de.errMsg === 'Session Expired') {
                                    window.location = '<?= site_url("auth/login") ?>';
                                }
                            }
                        });
                    }
                });
            }
        }
    });
    
    var winpenjualandodistprint = new Ext.Window({
        id: 'id_winpenjualandodistprint',
        title: 'Print Delivery Order',
        closeAction: 'hide',
        width: 900,
        height: 450,
        layout: 'fit',
        border: false,
        //html: 'div id="lap_do" />'
        html:'<iframe style="width:100%;height:100%;" id="penjualandodistprint" src=""></iframe>'
    });

    function clearsalesdo_dist(){
        Ext.getCmp('deliveryorderdistribusi').getForm().reset();
        Ext.getCmp('deliveryorderdistribusi').getForm().load({
            url: '<?= site_url("penjualan_do_distribusi/get_form") ?>',
            failure: function(form, action){
                var de = Ext.util.JSON.decode(action.response.responseText);
                Ext.Msg.show({
                    title: 'Error',
                    msg: de.errMsg,
                    modal: true,
                    icon: Ext.Msg.ERROR,
                    buttons: Ext.Msg.OK,
                    fn: function(btn){
                        if (btn === 'ok' && de.errMsg === 'Session Expired') {
                            window.location = '<?= site_url("auth/login") ?>';
                        }
                    }
                });
            }
        });
        storesalesdo_dist.removeAll();
    }
</script>
