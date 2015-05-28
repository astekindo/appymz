<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">
   
    var headerreturjualnonstruk = {
        layout: 'column',
        border: false,
        items: [{
                columnWidth: .5,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: { labelSeparator: ''},
                items: [{
                        xtype: 'textfield',
                        fieldLabel: 'No.Retur',
                        name: 'no_retur',
                        allowBlank: true,
                        readOnly:true,
                        fieldClass:'readonly-input',
                        id: 'id_pjretns_no_retur',
                        maxLength: 255,
                        anchor: '90%',
                        value:''
                    },{
                        xtype: 'datefield',
                        fieldLabel: 'Tanggal <span class="asterix">*</span>',
                        name: 'tgl_retur',
                        id:'id_pjretns_tglretur',
                        allowBlank:false,   
                        format:'d-M-Y',  
                        editable:false,      
                        anchor: '90%'                        
                    }
                ]
            }
        ]
    };
    
	
    var strpenjualanreturnonstruk = new Ext.data.Store({
        autoSave:false,
        reader: new Ext.data.JsonReader({
            fields: [
                {name: 'no_so', allowBlank: false, type: 'text'},
                {name: 'kd_produk', allowBlank: false, type: 'text'},
                {name: 'kd_produk_supp', allowBlank: false, type: 'text'},
                {name: 'nama_produk', allowBlank: false, type: 'text'},                
                {name: 'nm_satuan', allowBlank: false, type: 'text'},
                {name: 'qty', allowBlank: false, type: 'int'},
                 {name: 'qty_retur', allowBlank: false, type: 'int'},
                 {name: 'qty_input', allowBlank: false, type: 'int'},
                {name: 'rp_harga', allowBlank: false, type: 'int'},
                {name: 'rp_diskon1', allowBlank: false, type: 'text'},
                {name: 'rp_diskon2', allowBlank: false, type: 'text'},
                {name: 'rp_diskon3', allowBlank: false, type: 'text'},
                {name: 'rp_diskon4', allowBlank: false, type: 'text'},
                {name: 'rp_diskon5', allowBlank: false, type: 'text'},
                {name: 'rp_total1', allowBlank: false, type: 'int'},
                {name: 'ekstra_diskon', allowBlank: false, type: 'int'},
                {name: 'rp_diskon_tambahan', allowBlank: false, type: 'int'},
                {name: 'rp_ekstra_diskon', allowBlank: false, type: 'int'},
                {name: 'disk_persen_supp2', allowBlank: false, type: 'int'},
                {name: 'disk_persen_supp3', allowBlank: false, type: 'int'},
                {name: 'disk_persen_supp4', allowBlank: false, type: 'int'},
                {name: 'disk_amt_supp1', allowBlank: false, type: 'int'},
                {name: 'disk_amt_supp2', allowBlank: false, type: 'int'},
                {name: 'disk_amt_supp3', allowBlank: false, type: 'int'},
                {name: 'disk_amt_supp4', allowBlank: false, type: 'int'},	
                {name: 'disk_amt_supp5', allowBlank: false, type: 'int'},			                
                {name: 'hrg_supplier', allowBlank: false, type: 'int'},
                {name: 'harga', allowBlank: false, type: 'int'},
                {name: 'jumlah', allowBlank: false, type: 'int'},
                {name: 'kd_sub_blok', allowBlank: false, type: 'int'},
                {name: 'rp_jual_supermarket', allowBlank: false, type: 'text'}
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        writer: new Ext.data.JsonWriter(
        {
            encode: true,
            writeAllFields: true
        })
    });
  
    strpenjualanreturnonstruk.on('remove',  function(){
		
        var grand_total = 0;
        var dpp = 0;
        var ppn = 0;
        var jumlah = 0;
        var edit = "";
        strpenjualanreturnonstruk.each(function(node){			
            //jumlah += parseInt(node.data.rp_total);
            if (edit === 'Y'){
            grand_total += parseInt(node.data.rp_total1);
            grand_total += parseInt(node.data.rp_total1);
            dpp = parseInt(grand_total / 1.1);
            ppn = parseInt (dpp * 0.1);
            }
            
        });
        Ext.getCmp('ppn_returjual_ns').setValue(ppn);
        Ext.getCmp('dpp_returjual_ns').setValue(dpp);
        Ext.getCmp('jumlah_returjual_non_struk').setValue(grand_total);
        var grandtotal_retur = grand_total - Ext.getCmp('rpns_rp_diskon_tambahan').getValue();
	Ext.getCmp('grandtotal_returjual_ns').setValue(grandtotal_retur);	
    });
    
    strpenjualanreturnonstruk.on('update',  function(){
		
        var grand_total = 0;
        var dpp = 0;
        var ppn = 0;
        var jumlah = 0;
        var edit = "";
        strpenjualanreturnonstruk.each(function(node){			
            grand_total += parseInt(node.data.rp_total1);
            dpp = parseInt(grand_total / 1.1);
            ppn = parseInt (dpp * 0.1);
            
        });
		
        //grand_total = jumlah-rp_diskon-rp_ekstra_diskon;
        //Ext.getCmp('jumlah_returjual_non_struk').setValue(jumlah);
        Ext.getCmp('ppn_returjual_ns').setValue(ppn);
        Ext.getCmp('dpp_returjual_ns').setValue(dpp);
        Ext.getCmp('jumlah_returjual_non_struk').setValue(grand_total);
        var grandtotal_retur = grand_total - Ext.getCmp('rpns_rp_diskon_tambahan').getValue();
	Ext.getCmp('grandtotal_returjual_ns').setValue(grandtotal_retur);
		
    });
  
   /* SubBlok */
    var strcbkdsubblokrpns = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['sub', 'nama_sub'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("penjualan_retur/get_sub_blok") ?>',
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
	
    var strgridsubblokrpns = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'sub',
                'nama_sub',
                'kd_sub_blok', 
                'kd_blok',
                'kd_lokasi',
                'nama_lokasi',
                'nama_blok',
                'nama_sub_blok',
                'kapasitas'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("penjualan_retur/get_rows_lokasi") ?>',
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
    var searchgridrpnssubblok = new Ext.app.SearchField({
        store: strgridsubblokrpns,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        id: 'idsearchgridrpnssubblok'
    });
	
    // top toolbar
    var tbgridrpnssubblok = new Ext.Toolbar({
        items: [searchgridrpnssubblok]
    });
	
    var gridrpnssubblok = new Ext.grid.GridPanel({
        store: strgridsubblokrpns,
        stripeRows: true,
        frame: true,
        border:true,
        tbar: tbgridrpnssubblok,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridsubblokrpns,
            displayInfo: true
        }),
        columns: [{
                dataIndex: 'kd_lokasi',
                hidden: true
            },{
                dataIndex: 'kd_blok',
                hidden: true
            },{
                dataIndex: 'kd_sub_blok',
                hidden: true
            },{
                header: 'Kode',
                dataIndex: 'sub',
                width: 90,
                sortable: true			
            
            },{
                header: 'Sub Blok Lokasi',
                dataIndex: 'nama_sub',
                width: 200,
                sortable: true         
            }],
        listeners: {
            'rowdblclick': function(){			
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('erpns_sub_blok').setValue(sel[0].get('sub'));
                    Ext.getCmp('epr_nama_sub_blok').setValue(sel[0].get('nama_sub'));
										
                    menusubblokrpns.hide();
                }
            }
        }
    });
	
    var menusubblokrpns = new Ext.menu.Menu();
    menusubblokrpns.add(new Ext.Panel({
        title: 'Pilih Sub Blok Lokasi',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 350,
        height: 250,
        closeAction: 'hide',
        plain: true,
        items: [gridrpnssubblok],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menusubblokrpns.hide();
                }
            }]
    }));
    
    Ext.ux.TwinComborpnsSubBlok = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            //load store grid
           /* strgridsubblokrpns.load({
                params:{
                    kd_produk: Ext.getCmp('pjret_kd_produk').getValue()
                }
            });*/
            strgridsubblokrpns.setBaseParam('kd_produk',Ext.getCmp('pjret_kd_produk').getValue());
            strgridsubblokrpns.load();
                
            menusubblokrpns.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
    /* END SubBlok*/
    
    //twin produk
   var strcbrjnsproduk = new Ext.data.ArrayStore({
        fields: ['kd_produk'],
        data: []
    });

    var strgridrjnsproduk = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                {name: 'kd_produk', allowBlank: false, type: 'text'},
                {name: 'nama_produk', allowBlank: false, type: 'text'}
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("retur_penjualan_non_struk/search_produk") ?>',
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

    strgridrjnsproduk.on('load', function() {
        Ext.getCmp('search_query_rjnsproduk').focus();
    });

    var searchfieldrjnsproduk = new Ext.app.SearchField({
        width: 220,
        id: 'search_query_rjnsproduk',
        store: strgridrjnsproduk
    });



    // top toolbar
    var tbsearchfieldrjnsproduk = new Ext.Toolbar({
        items: [searchfieldrjnsproduk]
    });

    var gridrjnsproduk = new Ext.grid.GridPanel({
        store: strgridrjnsproduk,
        stripeRows: true,
        frame: true,
        border: true,
        columns: [{
                header: 'Kode produk',
                dataIndex: 'kd_produk',
                width: 100,
                sortable: true
            }, {
                header: 'Nama produk',
                dataIndex: 'nama_produk',
                width: 350,
                sortable: true
            }],
        tbar: tbsearchfieldrjnsproduk,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridrjnsproduk,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    var _ada = false;
                                
                    strpenjualandistribusi.each(function(record){
                        if(record.get('kd_produk') === sel[0].get('kd_produk')){
                            _ada = true;
                        }
                    });

                    if (_ada){
                        Ext.Msg.show({
                            title: 'Error',
                            msg: 'Produk yang sama tidak bisa di pilih lebih dari 1 kali',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK,
                            fn: function(btn){
                                if (btn == 'ok') {
                                    Ext.getCmp('rjns_kd_produk').reset();
                                }
                            }                            
                        });
                        Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');
                        Ext.getCmp('rjns_kd_produk').focus();	
                        return;
                    }
                    Ext.Ajax.request({
                        url: '<?= site_url("retur_penjualan_non_struk/get_row_produk") ?>',
                        method: 'POST',
                        params: {
                            id: sel[0].get('kd_produk'),
                            qty: Ext.getCmp('pd_qty').getValue(),
                            search_by: 'kode'
                        },
                        callback: function(opt, success, responseObj) {
                            var de = Ext.util.JSON.decode(responseObj.responseText);
                            if (de.success === true) {
                                Ext.getCmp('rjns_kd_produk').setValue(de.data.kd_produk);
                                Ext.getCmp('rpns_nama_produk').setValue(de.data.nama_produk);
                                Ext.getCmp('rpns_nm_satuan').setValue(de.data.nm_satuan);
                                Ext.getCmp('rpns_harga').setValue(de.data.rp_jual_supermarket);
                                Ext.getCmp('rpns_disk1').setValue(de.data.disk_kons1);
                                Ext.getCmp('rpns_disk2').setValue(de.data.disk_kons2);
                                Ext.getCmp('rpns_disk3').setValue(de.data.disk_kons3);
                                Ext.getCmp('rpns_disk4').setValue(de.data.disk_kons4);
                                Ext.getCmp('rpns_disk5').setValue(de.data.disk_kons5);
                                Ext.getCmp('rpns_total_diskon').setValue(de.data.total_diskon);
                                Ext.getCmp('rpns_harga_jual_nett').setValue(de.data.harga_jual_nett);
                                Ext.getCmp('rpns_total').setValue(0);
                  		Ext.getCmp('rpns_qty').setValue(0);
                            } else {
                                Ext.Msg.show({
                                    title: 'Error',
                                    msg: de.errMsg,
                                    modal: true,
                                    icon: Ext.Msg.ERROR,
                                    buttons: Ext.Msg.OK,
                                    fn: function(btn) {
                                        if (btn === 'ok' && de.errMsg === 'Session Expired') {
                                            window.location = '<?= site_url("auth/login") ?>';
                                        }
                                    }
                                });
                            }
                        }
                    });
                    Ext.getCmp('pd_qty').focus();
                    menuprjnsroduk.hide();
                }
            }
        }
    });

    var menuprjnsroduk = new Ext.menu.Menu();
    menuprjnsroduk.add(new Ext.Panel({
        title: 'Pilih Barang',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 630,
        height: 250,
        closeAction: 'hide',
        plain: true,
        items: [gridrjnsproduk],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menuprjnsroduk.hide();
                }
            }]
    }));

    menuprjnsroduk.on('hide', function() {
        var sf = Ext.getCmp('search_query_rjnsproduk').getValue();
        if (sf !== '') {
            Ext.getCmp('search_query_rjnsproduk').setValue('');
            searchfieldrjnsproduk.onTrigger2Click();
        }
    });


    Ext.ux.TwinComboRjns = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            //load store grid
            strgridrjnsproduk.load();
            menuprjnsroduk.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
                trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
    //End Twin Produk
  
    var editorpenjualanreturnonstruk = new Ext.ux.grid.RowEditor({
        saveText: 'Update'
    });
    var gridreturjualnonstruk = new Ext.grid.GridPanel({
        stripeRows: true,
        height: 200,
        store:strpenjualanreturnonstruk,
        frame: true,
        border:true,
        plugins: [editorpenjualanreturnonstruk],
        tbar: [{
                icon: BASE_ICONS + 'add.png',
                text: 'Add',
                handler: function(){
                    
                    var rowpembelianreceiveorder = new gridreturjualnonstruk.store.recordType({
                        no_do:'',
                        kd_produk : '',
                        qty: ''
                    });                
                    editorpenjualanreturnonstruk.stopEditing();
                    strpenjualanreturnonstruk.insert(0, rowpembelianreceiveorder);
                    gridreturjualnonstruk.getView().refresh();
                    gridreturjualnonstruk.getSelectionModel().selectRow(0);
                    editorpenjualanreturnonstruk.startEditing(0);
                }
            },{
                ref: '../removeBtn',
                icon: BASE_ICONS + 'delete.gif',
                text: 'Remove',
                disabled: true,
                handler: function(){
                    editorpenjualanreturnonstruk.stopEditing();
                    var s = gridreturjualnonstruk.getSelectionModel().getSelections();
                    for(var i = 0, r; r = s[i]; i++){
                        strpenjualanreturnonstruk.remove(r);
                    }
                }
            }],
        columns: [{
                header: 'Kode produk',
                dataIndex: 'kd_produk',
                width: 110,
                editor: new Ext.ux.TwinComboRjns({
                    id: 'rjns_kd_produk',
                    store: strcbrjnsproduk,
                    mode: 'local',
                    valueField: 'kd_produk',
                    displayField: 'kd_produk',
                    typeAhead: true,
                    triggerAction: 'all',
                    //allowBlank: false,
                    editable: false,
                    hiddenName: 'kd_produk',
                    emptyText: 'Pilih Produk'

                })		
            
            },{
                header: 'Nama Barang',
                dataIndex: 'nama_produk',
                width: 250,
                editor: {
                    xtype: 'textfield',
                    id:'rpns_nama_produk',
                    readOnly: true
                }
                
            },
            {
                xtype: 'numbercolumn',
                header: 'Qty',
                dataIndex: 'qty_input',           
                width: 80,
                align: 'center',
                sortable: true,
                format: '0',
                fieldClass:'number',
                editor: {
                    xtype: 'numberfield',
                    id: 'rpns_qty',
                    listeners:{
                        'render': function(c) {
                            c.getEl().on('keyup', function() {
                                var harga = parseFloat(Ext.getCmp('rpns_harga_jual_nett').getValue());
                                var rp_total= harga * this.getValue();
                               
                                Ext.getCmp('rpns_total').setValue(rp_total);
                                                               
                            }, c);
                        }
                    }
                }
            },{
                header: 'Satuan',
                dataIndex: 'nm_satuan',
                width: 50,
                editor: {
                    xtype: 'textfield',
                    id:'rpns_nm_satuan',
                    readOnly: true
                }
                
            },{
                header: 'Kode Sub Blok',
                dataIndex: 'sub',
                width: 100,
                editor: new Ext.ux.TwinComborpnsSubBlok({
                    id: 'erpns_sub_blok',
                    store: strcbkdsubblokrpns,
                    valueField: 'sub',
                    displayField: 'sub',
                    typeAhead: true,
                    triggerAction: 'all',
                    //allowBlank: false,
                    editable: false,
                    hiddenName: 'sub',
                    emptyText: 'Pilih Sub Blok',
                    listeners: {
                        'expand': function(){
                            strcbkdsubblokrpns.load();
                        }
                    }
                })			
            },{
                header: 'Sub Blok',
                dataIndex: 'nama_sub',
                width: 200,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'epr_nama_sub_blok'
                })
            },{
                xtype: 'numbercolumn',
                format: '0,0',
                header: 'Harga',
                dataIndex: 'rp_harga',           
                width: 100,
                align: 'right',
                sortable: true,
                 
                editor: {
                    xtype: 'numberfield',
                    id:'rpns_harga',
                    readOnly: true
                }
                
            },{
               
                header: 'Disk1',
                dataIndex: 'rp_diskon1',           
                width: 80,
                align: 'right',
                sortable: true,
                format: '0,0',
                editor: {
                    xtype: 'textfield',
                    id:'rpns_disk1',
                     readOnly: true
                }
            },{
                
                header: 'Disk2',
                dataIndex: 'rp_diskon2',           
                width: 80,
                align: 'right',
                sortable: true,
                format: '0,0',
                editor: {
                    xtype: 'textfield',
                    id:'rpns_disk2',
                     readOnly: true
                }
            },{
                
                header: 'Disk3',
                dataIndex: 'rp_diskon3',           
                width: 80,
                align: 'right',
                sortable: true,
                format: '0,0',
                editor: {
                    xtype: 'textfield',
                    id:'rpns_disk3',
                     readOnly: true
                }
            },{
                
                header: 'Disk4',
                dataIndex: 'rp_diskon4',           
                width: 80,
                align: 'right',
                sortable: true,
                format: '0,0',
                editor: {
                    xtype: 'textfield',
                    id:'rpns_disk4',
                     readOnly: true
                }
            },{
                
                header: 'Disk5',
                dataIndex: 'rp_diskon5',           
                width: 80,
                align: 'right',
                sortable: true,
                format: '0,0',
                editor: {
                    xtype: 'textfield',
                    id:'rpns_disk5',
                     readOnly: true
                }
            },{
                xtype: 'numbercolumn',
                header: 'Total Diskon',
                dataIndex: 'rp_diskon',           
                width: 80,
                align: 'right',
                sortable: true,
                format: '0,0',
                editor: {
                    xtype: 'textfield',
                    id:'rpns_total_diskon',
                     readOnly: true
                }
            },{ xtype: 'numbercolumn',            
                header: 'Harga Jual Nett',
                dataIndex: 'harga_jual_nett',           
                width: 120,
                align: 'right',
                sortable: true,
                format: '0,0',
                editor: {
                    xtype: 'textfield',
                    id:'rpns_harga_jual_nett',
                     readOnly: true
                }
            },{
                xtype: 'numbercolumn',
                header: 'Total',
                dataIndex: 'rp_total1',           
                width: 100,
                sortable: true,
                align: 'right',
                format: '0,0',
                id: 'rpns_total',
                fieldClass:'number',
                editor: {
                    xtype: 'numberfield',
                    id: 'rpns_total'
                    // readOnly: true
                }
            }]
    });
    
    gridreturjualnonstruk.getSelectionModel().on('selectionchange', function(sm){
        gridreturjualnonstruk.removeBtn.setDisabled(sm.getCount() < 1);
    });
    
    var winpembelianreturprint = new Ext.Window({
        id: 'id_winpembelianreturprint',
        title: 'Print Receive Order Form',
        closeAction: 'hide',
        width: 900,
        height: 450,
        layout: 'fit',
        border: false,
        html:'<iframe style="width:100%;height:100%;" id="pembelianreturprint" src=""></iframe>'
    });
	
    var retur_jual_non_struk = new Ext.FormPanel({
        id: 'retur_jual_non_struk',
        border: false,
        frame: true,
        autoScroll:true,    
        monitorValid: true, 
        bodyStyle:'padding-right:20px;',
        labelWidth: 130,
        items: [{
                bodyStyle: {
                    margin: '0px 0px 15px 0px'
                },                  
                items: [headerreturjualnonstruk]
            },
            gridreturjualnonstruk,
            {
                layout: 'column',
                border: false,
                items: [{
                        columnWidth: .6,
                        style:'margin:6px 3px 0 0;',
                        layout: 'form', 
                        labelWidth: 100,                   
                        items: [
                            {
                                xtype: 'textarea',
                                fieldLabel: 'Alasan Retur <span class="asterix">*</span>',
                                allowBlank: false,
                                name: 'remark',                                    
                                id: 'remark_returjual_non_struk',                                      
                                width: 300,
                                value:''
                            }
                        ]
                    }, {
                        columnWidth: .4,
                        layout: 'form',
                        style:'margin:6px 0 0 0;',
                        border: false,
                        labelWidth: 110,
                        defaults: { labelSeparator: ''},
                        items: [ 
                            {
                                xtype: 'fieldset',
                                autoHeight: true,                               
                                items: [
                                  {
                                        xtype: 'numericfield',
                                        currencySymbol: '',
                                        fieldLabel: '<b>Jumlah Retur</b>',
                                        name: 'total',
                                        cls:'vertical-space',
                                        readOnly: true,                                 
                                        id: 'jumlah_returjual_non_struk',                                        
                                        anchor: '90%',  
                                        fieldClass:'readonly-input bold-input number',  
                                        labelStyle:'margin-top:10px;',  
                                        value:''                                                                                                                              
                                    },{
                                        xtype: 'numericfield',
                                        currencySymbol: '',
                                        fieldLabel: 'DPP',
                                        name: 'dpp_returjual',
                                        readOnly: true,                                 
                                        id: 'dpp_returjual_ns',                                      
                                        anchor: '90%',      
                                        fieldClass:'readonly-input number',
                                        value:''
                                    },{
                                        xtype: 'numericfield',
                                        currencySymbol: '',
                                        fieldLabel: 'PPN',
                                        name: 'ppn_returjual',
                                        readOnly: true,                                 
                                        id: 'ppn_returjual_ns',                                      
                                        anchor: '90%',      
                                        fieldClass:'readonly-input number',
                                        value:''
                                    },{
                                        xtype: 'compositefield',
                                        fieldLabel: 'Potongan Retur',
                                        combineErrors: false,
                                        items: [
                                            {
                                                xtype: 'numericfield',
                                                currencySymbol:'',
                                                format:'0',
                                                name : 'pct_diskon_tambahan',
                                                id: 'rpns_pct_diskon_tambahan',
                                                fieldClass:'number',
                                                width: 60,
                                                value: '0',
                                                maxValue:100,
                                                listeners: {
                                                    'change': function(){
                                                        var diskon_tambahan = Ext.getCmp('jumlah_returjual_non_struk').getValue() *  this.getValue() / 100;
                                                        var total = Ext.getCmp('jumlah_returjual_non_struk').getValue() - diskon_tambahan;
							Ext.getCmp('rpns_rp_diskon_tambahan').setValue(diskon_tambahan);
                                                        Ext.getCmp('grandtotal_returjual_ns').setValue(total);
                                                        
                                                    }
                                                }
											   
                                            },
                                            {
                                                xtype: 'displayfield',
                                                value: '%',
                                                width: 17.5
                                            },
                                            {
                                                xtype: 'numericfield',
                                                name : 'rp_diskon_tambahan',
                                                id : 'rpns_rp_diskon_tambahan',
                                                currencySymbol:'',
                                                value : '0',
                                                fieldClass:'number',
                                                readOnly: false, 
                                                width: 120,
                                                anchor: '90%',
                                                listeners: {
                                                    'change': function(){
                                                        var diskon_tambahan = (this.getValue() / Ext.getCmp('jumlah_returjual_non_struk').getValue()) * 100 ;
                                                        var total = Ext.getCmp('jumlah_returjual_non_struk').getValue() - this.getValue();
							Ext.getCmp('rpns_pct_diskon_tambahan').setValue(diskon_tambahan);
                                                        Ext.getCmp('grandtotal_returjual_ns').setValue(total);
                                                        	
                                                    }
                                                }
                                               
                                            }
                                        ]
                                    },{
                                        xtype: 'numericfield',
                                        currencySymbol: '',
                                        fieldLabel: '<b>Grand Total</b>',
                                        name: 'total',
                                        cls:'vertical-space',
                                        readOnly: true,                                 
                                        id: 'grandtotal_returjual_ns',                                        
                                        anchor: '90%',  
                                        fieldClass:'readonly-input bold-input number',  
                                        labelStyle:'margin-top:10px;',  
                                        value:''                                                                                                                              
                                    }
                                ]
                            }
                        ]
                    }]
            }
            
                
        ],
        buttons: [{
                text: 'Save',
                formBind: true,
                handler: function(){
                    if(Ext.getCmp('grandtotal_returjual_ns').getValue() ==0){
                        Ext.Msg.show({
                            title: 'Error',
                            msg: 'Tidak ada retur penjualan!',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK
                            
                        });
                        return;
                    }
                    
                    if(Ext.getCmp('erpns_sub_blok').getValue() ==''){
                        Ext.Msg.show({
                            title: 'Error',
                            msg: 'kode sub blok harus di isi!',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK
                            
                        });
                        return;
                    }
                    var detailreturjual = new Array();              
                    strpenjualanreturnonstruk.each(function(node){
                        detailreturjual.push(node.data)                
                    });	
                                              
                    Ext.getCmp('retur_jual_non_struk').getForm().submit({
                        url: '<?= site_url("retur_penjualan_non_struk/update_row") ?>',
                        scope: this,
                        params: {						
                            detail: Ext.util.JSON.encode(detailreturjual),
                            _grandtotal_returjual: Ext.getCmp('grandtotal_returjual_ns').getValue(),
                            dpp: Ext.getCmp('dpp_returjual_ns').getValue(),
                            ppn: Ext.getCmp('ppn_returjual_ns').getValue(),
                            _jumlah_returjual: Ext.getCmp('jumlah_returjual_non_struk').getValue(),
                            _remark: Ext.getCmp('remark_returjual_non_struk').getValue(),
                            _pct_diskon_tambahan: Ext.getCmp('rpns_pct_diskon_tambahan').getValue(),
                            _rp_diskon_tambahan: Ext.getCmp('rpns_rp_diskon_tambahan').getValue()
                                        					
                        },
                        waitMsg: 'Saving Data...',
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
                                        winreturpenjualanprint.show();
                                        Ext.getDom('returpenjualanprint').src = r.printUrl;
                                    }
                                }
                            });                     
                        
                            clearpenjualanreturnonstruk();                       
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
                    clearpenjualanreturnonstruk();
                }
            }], 
        listeners:{
            afterrender:function(){
                
                this.getForm().load({
                    url: '<?= site_url("penjualan_retur/get_form") ?>',
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
    var winreturpenjualanprint = new Ext.Window({
        id: 'id_winreturpenjualanprint',
        title: 'Print Retur Penjualan',
        closeAction: 'hide',
        width: 900,
        height: 450,
        layout: 'fit',
        border: false,
        html:'<iframe style="width:100%;height:100%;" id="returpenjualanprint" src=""></iframe>'
    });
    
    function clearpenjualanreturnonstruk(){
        Ext.getCmp('retur_jual_non_struk').getForm().reset();
        Ext.getCmp('retur_jual_non_struk').getForm().load({
            url: '<?= site_url("penjualan_retur/get_form") ?>',
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
        strpenjualanreturnonstruk.removeAll();
        strgrid_retjual_salesorder.removeAll();
    }
</script>