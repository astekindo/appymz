<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">    
    /* START FORM */ 

    // combobox kategori1
    var str_list_brg_cbkategori1 = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_kategori1', 'nama_kategori1'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("kategori2/get_kategori1") ?>',
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

    var list_brg_cbkategori1 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 1 <span class="asterix">*</span>',
        id: 'id_list_brg_cbkategori1',
        store: str_list_brg_cbkategori1,
        valueField: 'kd_kategori1',
        displayField: 'nama_kategori1',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'nama_kategori1',
        emptyText: 'Pilih kategori 1',
        listeners: {
            select: function(combo, records) {
                var kdlist_brg_cbkategori1 = this.getValue();
                list_brg_cbkategori2.setValue();
                list_brg_cbkategori2.store.proxy.conn.url = '<?= site_url("kategori3/get_kategori2") ?>/' + kdlist_brg_cbkategori1;
                list_brg_cbkategori2.store.reload();
            }
        }
    });
    // combobox kategori2
    var str_list_brg_cbkategori2 = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_kategori2', 'nama_kategori2'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("kategori3/get_kategori2") ?>',
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

    var list_brg_cbkategori2 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 2 <span class="asterix">*</span>',
        id: 'id_list_brg_cbkategori2',
        mode: 'local',
        store: str_list_brg_cbkategori2,
        valueField: 'kd_kategori2',
        displayField: 'nama_kategori2',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'nama_kategori2',
        emptyText: 'Pilih kategori 2',
        listeners: {
            select: function(combo, records) {
                var kd_list_brg_cbkategori1 = list_brg_cbkategori1.getValue();
                var kd_list_brg_cbkategori2 = this.getValue();
                list_brg_cbkategori3.setValue();
                list_brg_cbkategori3.store.proxy.conn.url = '<?= site_url("kategori4/get_kategori3") ?>/' + kd_list_brg_cbkategori1 +'/'+ kd_list_brg_cbkategori2;
                list_brg_cbkategori3.store.reload();
            }
        }
    });
	
    // combobox kategori3
    var str_list_brg_cbkategori3 = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_kategori3', 'nama_kategori3'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("kategori4/get_kategori3") ?>',
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

    var list_brg_cbkategori3 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 3 <span class="asterix">*</span>',
        id: 'id_list_brg_cbkategori3',
        mode: 'local',
        store: str_list_brg_cbkategori3,
        valueField: 'kd_kategori3',
        displayField: 'nama_kategori3',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'nama_kategori3',
        emptyText: 'Pilih kategori 3',
        listeners: {
            select: function(combo, records) {
                var kd_list_brg_cbkategori1 = list_brg_cbkategori1.getValue();
                var kd_list_brg_cbkategori2 = list_brg_cbkategori2.getValue();
                var kd_list_brg_cbkategori3 = this.getValue();
                list_brg_cbkategori4.setValue();
                list_brg_cbkategori4.store.proxy.conn.url = '<?= site_url("list_barang/get_kategori4") ?>/' + kd_list_brg_cbkategori1 +'/'+ kd_list_brg_cbkategori2 +'/'+ kd_list_brg_cbkategori3;
                list_brg_cbkategori4.store.reload();
            }
        }
    });
	
    // combobox kategori4
    var str_list_brg_cbkategori4 = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_kategori4', 'nama_kategori4'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("list_barang/get_kategori4") ?>',
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

    var list_brg_cbkategori4 = new Ext.form.ComboBox({   
        fieldLabel: 'Kategori 4 <span class="asterix">*</span>',
        id: 'id_list_brg_cbkategori4',
        mode: 'local',
        store: str_list_brg_cbkategori4,
        valueField: 'kd_kategori4',
        displayField: 'nama_kategori4',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'nama_kategori4',
        emptyText: 'Pilih kategori 4'
    });
	
    // combobox satuan
    var strcbsatuan = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_satuan', 'nm_satuan'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("list_barang/get_satuan") ?>',
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
    
    var cbsatuan = new Ext.form.ComboBox({
        fieldLabel: 'Satuan <span class="asterix">*</span>',
        id: 'id_cbsatuan',
        store: strcbsatuan,
        valueField: 'kd_satuan',
        displayField: 'nm_satuan',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_satuan',
        emptyText: 'Pilih Satuan'
    });
	
    Ext.ns('listbarangform');
    listbarangform.Form = Ext.extend(Ext.form.FormPanel, {

        // defaults - can be changed from outside
        border: false,
        frame: true,
        labelWidth: 140,
        url: '<?= site_url("list_barang/update_row") ?>',
        constructor: function(config){
            config = config || {};
            config.listeners = config.listeners || {};
            Ext.applyIf(config.listeners, {
                actioncomplete: function(){
                    //if (console && console.log) {
                    //    console.log('actioncomplete:', arguments);
                    //}
                },
                actionfailed: function(){
                    //if (console && console.log) {
                    //    console.log('actionfailed:', arguments);
                    //}
                }
            });
            listbarangform.Form.superclass.constructor.call(this, config);
        },
        initComponent: function(){
        
            // hard coded - cannot be changed from outsid
            var config = {
                defaults: { labelSeparator: '', value :0},
                monitorValid: true,
                //
                autoScroll: false,// ,buttonAlign:'right'
                items: [{
                        xtype: 'tabpanel',
                        height: 400,
                        activeTab: 0,
                        deferredRender: false,
                        items: [{
                                title:'Produk',
                                layout:'form',		 
                                items: [{
                                        layout: 'column',
                                        border: false,
                                        style:'padding:10px',
                                        items: [{
                                                columnWidth: 0.5,
                                                layout: 'form',
                                                border: false,
                                                defaultType: 'textfield',
                                                items:[{
                                                        type: 'textfield',
                                                        fieldLabel: 'Kode Produk',
                                                        name: 'kd_produk'
                                                    },list_brg_cbkategori1,list_brg_cbkategori2,list_brg_cbkategori3,list_brg_cbkategori4,
                                                    {
                                                        type: 'textfield',
                                                        fieldLabel: 'Nama Produk',
                                                        name: 'nama_produk'       
                                                    },{
                                                        type: 'textfield',
                                                        fieldLabel: 'Kode Produk Lama</span>',
                                                        name: 'kd_produk_lama'   
                                                    },{
                                                        type: 'textfield',
                                                        fieldLabel: 'Kode Produk Supplier</span>',
                                                        name: 'kd_produk_supp'
                                                    },cbsatuan, new Ext.form.Checkbox({
                                                        xtype: 'checkbox',
                                                        boxLabel:'Is Distribusi',
                                                        fieldLabel: 'Kode Peruntukkan',
                                                        name:'kd_peruntukkan',
                                                        id:'id_kd_peruntukkan',
                                                        inputValue: '1',
                                                        autoLoad : true
                                                    })
                                                ]
                                            },{
                                                columnWidth: 0.5,
                                                layout: 'form',
                                                border: false,
                                                defaultType: 'textfield',
                                                items:[
                                                    {
                                                        xtype: 'numberfield',
                                                        fieldLabel: 'Min Stok',
                                                        style: 'text-align:right;',
                                                        name: 'min_stok'        
                                                    },{
                                                        xtype: 'numberfield',
                                                        fieldLabel: 'Max Stok',
                                                        style: 'text-align:right;',
                                                        name: 'max_stok'             
                                                    },{
                                                        xtype: 'numberfield',
                                                        fieldLabel: 'Min Order',
                                                        name: 'min_order',
                                                        style: 'text-align:right;'  
                                                    },{
                                                        xtype: 'numberfield',
                                                        fieldLabel: 'Harga Supplier',
                                                        name: 'hrg_supplier',
                                                        id: 'id_hrg_supplier',
                                                        style: 'text-align:right;' 
                                                    },{
                                                        xtype: 'numberfield',
                                                        fieldLabel: 'Harga Jual Supermarket',
                                                        name: 'rp_jual_supermarket',
                                                        style: 'text-align:right;' 
                                                    },{
                                                        xtype: 'numberfield',
                                                        fieldLabel: 'Harga Jual Distribusis',
                                                        name: 'rp_jual_distribusi',
                                                        style: 'text-align:right;' 
                                                    }
                                                ]
                                            }]
                                    }]
                            }, {
                                title:'Diskon',
                                layout:'form',		      
                                style:'padding:10px',
                                items: [{
                                        xtype: 'numberfield',
                                        fieldLabel: 'Disc Konsumen1 (%)',
                                        name: 'disk_persen_kons1',
                                        id: 'id_disk_persen_kons1',
                                        style: 'text-align:right;'                
                                    },{
                                        xtype: 'numberfield',
                                        fieldLabel: 'Disc Konsumen1 (Rp)',
                                        name: 'disk_amt_kons1',
                                        id: 'id_disk_amt_kons1',
                                        style: 'text-align:right;'                
                                    },{
                                        xtype: 'numberfield',
                                        fieldLabel: 'Disc Konsumen2 (%)',
                                        name: 'disk_persen_kons2',
                                        id: 'id_disk_persen_kons2',
                                        style: 'text-align:right;'  
                                    },{
                                        xtype: 'numberfield',
                                        fieldLabel: 'Disc Konsumen2 (Rp)',
                                        name: 'disk_amt_kons2',
                                        id: 'id_disk_amt_kons2',
                                        style: 'text-align:right;' 
                                    },{
                                        xtype: 'numberfield',
                                        fieldLabel: 'Disc Konsumen3 (%)',
                                        name: 'disk_persen_kons3',
                                        id: 'id_disk_persen_kons3',
                                        style: 'text-align:right;'                
                                    },{
                                        xtype: 'numberfield',
                                        fieldLabel: 'Disc Konsumen3 (Rp)',
                                        name: 'disk_amt_kons3',
                                        id: 'id_disk_amt_kons3',
                                        style: 'text-align:right;'   
                                    },{
                                        xtype: 'numberfield',
                                        fieldLabel: 'Disc Konsumen4 (%)',
                                        name: 'disk_persen_kons4',
                                        id: 'id_disk_persen_kons4',
                                        style: 'text-align:right;'   
                                    },{
                                        xtype: 'numberfield',
                                        fieldLabel: 'Qty Beli (Bonus)',
                                        name: 'qty_beli_bonus',
                                        id: 'id_qty_beli_bonus',
                                        style: 'text-align:right;'     
                                    },{
                                        xtype: 'numberfield',
                                        fieldLabel: 'Kode Produk (Bonus)',
                                        name: 'kd_produk_bonus',
                                        id: 'id_kd_produk_bonus',
                                        style: 'text-align:right;'  
                                    },{
                                        xtype: 'numberfield',
                                        fieldLabel: 'Qty (Bonus)',
                                        name: 'qty_bonus',
                                        id: 'id_qty_bonus',
                                        style: 'text-align:right;'   
                                    }, new Ext.form.Checkbox({
                                        xtype: 'checkbox',
                                        boxLabel:'',
                                        fieldLabel: 'Bonus Kelipatan',
                                        name:'is_bonus_kelipatan',
                                        id:'id_is_bonus_kelipatan',
                                        inputValue: '1',
                                        autoLoad : true
                                    })]
                            }, {
                                title:'Diskon Member',
                                layout:'form',		       
                                style:'padding:10px',
                                defaultType: 'textfield',
                                items: [{
                                        xtype: 'numberfield',
                                        fieldLabel: 'Disc Member1 (%)',
                                        name: 'disk_persen_member1',
                                        id: 'id_disk_persen_member1',
                                        maxLength: 3,
                                        style: 'text-align:right;'               
                                    },{
                                        xtype: 'numberfield',
                                        fieldLabel: 'Disc Member1 (Rp)',
                                        name: 'disk_amt_member1',
                                        id: 'id_disk_amt_member1',
                                        maxLength: 11,
                                        style: 'text-align:right;'   
                                    },{
                                        xtype: 'numberfield',
                                        fieldLabel: 'Disc Member2 (%)',
                                        name: 'disk_persen_member2',
                                        id: 'id_disk_persen_member2',
                                        maxLength: 3,
                                        style: 'text-align:right;'   
                                    },{
                                        xtype: 'numberfield',
                                        fieldLabel: 'Disc Member2 (Rp)',
                                        name: 'disk_amt_member2',
                                        id: 'id_disk_amt_member2',
                                        maxLength: 11,
                                        style: 'text-align:right;'   
                                    },{
                                        xtype: 'numberfield',
                                        fieldLabel: 'Disc Member3 (%)',
                                        name: 'disk_persen_member3',
                                        id: 'id_disk_persen_member3',
                                        maxLength: 3,
                                        style: 'text-align:right;'  
                                    },{
                                        xtype: 'numberfield',
                                        fieldLabel: 'Disc Member3 (Rp)',
                                        name: 'disk_amt_member3',
                                        id: 'id_disk_amt_member3',
                                        maxLength: 11,
                                        style: 'text-align:right;'  
                                    },{
                                        xtype: 'numberfield',
                                        fieldLabel: 'Disc Member4 (%)',
                                        name: 'disk_persen_member4',
                                        id: 'id_disk_persen_member4',
                                        maxLength: 3,
                                        style: 'text-align:right;'   
                                    },{
                                        xtype: 'numberfield',
                                        fieldLabel: 'Qty Beli (Member)',
                                        name: 'qty_beli_member',
                                        id: 'id_qty_beli_member',
                                        maxLength: 11,
                                        style: 'text-align:right;'               
                                    },{
                                        xtype: 'numberfield',
                                        fieldLabel: 'Kode Produk (Member)',
                                        name: 'kd_produk_member',
                                        id: 'id_kd_produk_member',
                                        maxLength: 11,
                                        style: 'text-align:right;'   
                                    },{
                                        xtype: 'numberfield',
                                        fieldLabel: 'Qty (Member)',
                                        name: 'qty_member',
                                        id: 'id_qty_member',
                                        maxLength: 11,
                                        style: 'text-align:right;'   
                                    }, new Ext.form.Checkbox({
                                        xtype: 'checkbox',
                                        boxLabel:'',
                                        fieldLabel: 'Kelipatan',
                                        name:'is_member_kelipatan',
                                        id:'id_is_member_kelipatan',
                                        inputValue: '1',
                                        autoLoad : true
                                    }), new Ext.form.Checkbox({
                                        xtype: 'checkbox',
                                        boxLabel:'',
                                        fieldLabel: 'Bonus',
                                        name:'is_bonus',
                                        id:'id_is_bonus',
                                        inputValue: '1',
                                        autoLoad : true
                                    })]
                            }],
                        buttons: [{
                                text: 'Submit',
                                id: 'btnsubmitlistbarang',
                                formBind: true,
                                scope: this,
                                handler: this.submit
                            }, {
                                text: 'Reset',
                                id: 'btnresetlistbarang',
                                scope: this,
                                handler: this.reset
                            }, {
                                text: 'Close',
                                id: 'btnClose',
                                scope: this,
                                handler: function(){
                                    winaddlistbarang.hide();
                                }
                            }]
                    }]
            }; // eo config object
            // apply config
            Ext.apply(this, Ext.apply(this.initialConfig, config));
            
            // call parent
            listbarangform.Form.superclass.initComponent.apply(this, arguments);
            
        } // eo function initComponent	
        ,
        onRender: function(){
        
            // call parent
            listbarangform.Form.superclass.onRender.apply(this, arguments);
            
            // set wait message target
            this.getForm().waitMsgTarget = this.getEl();
            
            // loads form after initial layout
            // this.on('afterlayout', this.onLoadClick, this, {single:true});
        
        } // eo function onRender
        ,
        reset: function(){
            this.getForm().reset();
        },
        submit: function(){
        
            this.getForm().submit({
                url: this.url,
                scope: this,
                success: this.onSuccess,
                failure: this.onFailure,
                params: {
                    cmd: 'save'
                },
                waitMsg: 'Saving Data...'
            });
        } // eo function submit
        ,
        onSuccess: function(form, action){
            Ext.Msg.show({
                title: 'Success',
                msg: 'Form submitted successfully',
                modal: true,
                icon: Ext.Msg.INFO,
                buttons: Ext.Msg.OK
            });
            
            
            strlistbarang.reload();
            Ext.getCmp('id_formaddlistbarang').getForm().reset();
            winaddlistbarang.hide();
        } // eo function onSuccess
        ,
        onFailure: function(form, action){
        
            var fe = Ext.util.JSON.decode(action.response.responseText);
            this.showError(fe.errMsg || '');
            
            
        } // eo function onFailure
        ,
        showError: function(msg, title){
            title = title || 'Error';
            Ext.Msg.show({
                title: title,
                msg: msg,
                modal: true,
                icon: Ext.Msg.ERROR,
                buttons: Ext.Msg.OK,
                fn: function(btn){
                    if (btn == 'ok' && msg == 'Session Expired') {
                        window.location = '<?= site_url("auth/login") ?>';
                    }
                }
            });
        }
    }); // eo extend
    // register xtype
    Ext.reg('formaddlistbarang', listbarangform.Form);
    
    var winaddlistbarang = new Ext.Window({
        id: 'id_winaddlistbarang',
        closeAction: 'hide',
        width: 800,
        height: 450,
        layout: 'fit',
        border: false,
        items: {
            id: 'id_formaddlistbarang',
            xtype: 'formaddlistbarang'
        },
        onHide: function(){
            Ext.getCmp('id_formaddlistbarang').getForm().reset();
        }
    });
    
    /* START GRID */    
    var strlistbarang = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'kd_produk',
                'kd_produk_lama',
                'nama_produk',
                'nama_kategori',
                'nama_supplier',
                'kd_supplier',
                'kd_kategori1',
                'kd_kategori2',
                'kd_kategori3',
                'kd_kategori4',
                'nama_kategori1',
                'nama_kategori2',
                'nama_kategori3',
                'nama_kategori4',
                'nama_kategori',
                'thn_reg',
                'no_urut',
                'kd_produk_lama',
                'kd_produk_supp',
                'nm_satuan',
                'kd_peruntukkan',
                'is_konsinyasi',
                'aktif_purchase',
                'aktif',
                'is_harga_lepas',
                'pct_alert',
                'is_barang_paket',
                'hrg_supplier',
                'hrg_beli_sup',
                'hrg_beli_dist',
                'rp_cogs',
                'rp_cogs_dist',
                'pct_margin',
                'pct_margin_dist',
                'rp_margin',
                'rp_margin_dist',
                'margin_cogs',
                'margin_cogs_dist',
                'rp_ongkos_kirim',
                'rp_ongkos_kirim_dist',
                'rp_het_harga_beli',
                'rp_het_harga_beli_dist',
                'rp_het_cogs',
                'rp_het_cogs_dist',
                'rp_jual_supermarket',
                'rp_jual_distribusi',
                'disk_persen_kons1',
                'disk_amt_kons1',
                'disk_persen_kons2',
                'disk_amt_kons2',
                'disk_persen_kons3',
                'disk_amt_kons3',
                'disk_persen_kons4',
                'disk_amt_kons4',
                'disk_amt_kons5',
                'disk_persen_member1',
                'disk_amt_member2',
                'disk_persen_member2',
                'disk_amt_member2',
                'disk_persen_member3',
                'disk_amt_member3',
                'disk_persen_member4',
                'disk_amt_member4',
                'disk_amt_member5',
                'qty_beli_bonus',
                'qty_beli_member',
                'kd_produk_bonus',
                'kd_produk_member',
                'qty_bonus',
                'qty_member',
                'is_bonus_kelipatan',
                'is_member_kelipatan',
                'min_stok',
                'max_stok',
                'min_order',
                'no_urut'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("list_barang/get_rows") ?>',
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
	
    var searchlistbarang = new Ext.app.SearchField({
        store: strlistbarang,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        id: 'idsearchlistbarang'
    });
    strlistbarang.on('load',function(){
        strlistbarang.setBaseParam('kd_ukuran',Ext.getCmp('id_listbarang_cbukuran').getValue());
        strlistbarang.setBaseParam('kd_satuan', Ext.getCmp('id_listbarang_cbsatuan').getValue());
        strlistbarang.setBaseParam('kd_kategori1', Ext.getCmp('id_listbarang_cbkategori1').getValue());
        strlistbarang.setBaseParam('kd_kategori2', Ext.getCmp('id_listbarang_cbkategori2').getValue());
        strlistbarang.setBaseParam('kd_kategori3', Ext.getCmp('id_listbarang_cbkategori3').getValue());
        strlistbarang.setBaseParam('kd_kategori4', Ext.getCmp('id_listbarang_cbkategori4').getValue());
        strlistbarang.setBaseParam('kd_supplier', Ext.getCmp('id_listbarang_cbsuplier').getValue());
        strlistbarang.setBaseParam('is_konsinyasi', Ext.getCmp('id_is_konsinyasi').getValue());
    });
                      
    var tblistbarang = new Ext.Toolbar({
        items: [searchlistbarang]
    });

    var cbGrid = new Ext.grid.CheckboxSelectionModel();
    
    // row actions
    var actionlistbarang = new Ext.ux.grid.RowActions({
        header :'Edit',
        autoWidth: false,
        width: 30,
        actions:[{iconCls: 'icon-edit-record', qtip: 'Edit'}],
        widthIntercept: Ext.isSafari ? 4 : 2
    });	
	
    var actionlistbarangdel = new Ext.ux.grid.RowActions({
        header: 'Delete',
        autoWidth: false,
        width: 40,
        actions:[{iconCls: 'icon-delete-record', qtip: 'Delete'}],
        widthIntercept: Ext.isSafari ? 4 : 2
    });	
	
    actionlistbarang.on('action', function(grid, record, action, row, col) {
        var kd_produk = record.get('kd_produk');
        var nm_kategori1 = record.get('nama_kategori1');
        var nm_kategori2 = record.get('nama_kategori2');
        var nm_kategori3 = record.get('nama_kategori3');
        var nm_kategori4 = record.get('nama_kategori4');
        var nm_satuan = record.get('nm_satuan');
        switch(action) {
            case 'icon-edit-record':	        	
                editlistbarang(kd_produk,nm_kategori1,nm_kategori2,nm_kategori3,nm_kategori4,nm_satuan);
                break;
            case 'icon-delete-record':
                Ext.Msg.show({
                    title: 'Confirm',
                    msg: 'Are you sure delete selected row ?',
                    buttons: Ext.Msg.YESNO,
                    fn: function(btn){
                        if (btn == 'yes') {
                            Ext.Ajax.request({
                                url: '<?= site_url("list_barang/delete_row") ?>',
                                method: 'POST',
                                params: {
                                    kd_produk: kd_produk
                                },
                                callback:function(opt,success,responseObj){
                                    var de = Ext.util.JSON.decode(responseObj.responseText);
                                    if(de.success==true){
                                        strlistbarang.reload();
                                        strlistbarang.load({
                                            params: {
                                                start: STARTPAGE,
                                                limit: ENDPAGE
                                            }
                                        });
                                    }else{
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
                                }
                            });                 
                        } 
                    }
                });
                break;	      
	      	
            }
        });  
	
        //grid
        var listbarang = new Ext.grid.EditorGridPanel({
            id: 'id-listbarang-gridpanel',
            //id: 'listbarang',
            frame: true,
            border: true,
            stripeRows: true,
            sm: cbGrid,
            store: strlistbarang,
            loadMask: true,
            style: 'margin:0 auto;',
            height: 450,
            columns: [{
                    dataIndex: 'nama_kategori1',
                    hidden: true
                },{
                    dataIndex: 'nama_kategori2',
                    hidden: true
                },{
                    dataIndex: 'nama_kategori3',
                    hidden: true
                },{
                    dataIndex: 'nama_kategori4',
                    hidden: true
                },{
                    header: "NO Urut",
                    dataIndex: 'no_urut',
                    sortable: true,
                    width: 70
                },{
                    header: "Kd Produk",
                    dataIndex: 'kd_produk',
                    sortable: true,
                    width: 100
                },{
                    header: "Kd Produk Lama",
                    dataIndex: 'kd_produk_lama',
                    sortable: true,
                    width: 110
                },{
                    header: "Nama Produk",
                    dataIndex: 'nama_produk',
                    sortable: true,
                    width: 230
                },{
                    header: "Kd Supplier",
                    dataIndex: 'kd_supplier',
                    sortable: true,
                    width: 80
                },{
                    header: "Nama Supplier",
                    dataIndex: 'nama_supplier',
                    sortable: true,
                    width: 200
                },{
                    header: "Kategori",
                    dataIndex: 'nama_kategori',
                    sortable: true,
                    width: 320
                },{
                    header: "Peruntukkan",
                    dataIndex: 'kd_peruntukkan',
                    sortable: true,
                    width: 100
                },{
                    header: "Konsinyasi",
                    dataIndex: 'is_kosinyasi',
                    sortable: true,
                    width: 100
                },{
                    header: "Status Purchase",
                    dataIndex: 'aktif_purchase',
                    sortable: true,
                    width: 100
                },{
                    header: "Status Aktif",
                    dataIndex: 'aktif',
                    sortable: true,
                    width: 100
                },{
                    header: "Harga Lepas",
                    dataIndex: 'is_harga_lepas',
                    sortable: true,
                    width: 100
                },{
                    header: "Kd Produk Lm",
                    dataIndex: 'kd_produk_lama',
                    sortable: true,
                    width: 80
                },{
                    header: "Kd Produk Supp",
                    dataIndex: 'kd_produk_supp',
                    sortable: true,
                    width: 80
                },{
                    header: "Satuan",
                    dataIndex: 'nm_satuan',
                    sortable: true,
                    width: 50
                },{
                    header: "Min Stok",
                    dataIndex: 'min_stok',
                    sortable: true,
                    width: 100
                },{
                    header: "Max Stok",
                    dataIndex: 'max_stok',
                    sortable: true,
                    width: 100
                },{
                    header: "Min Order",
                    dataIndex: 'min_order',
                    sortable: true,
                    width: 100
                },{
                    header: "Alert (%)",
                    dataIndex: 'pct_alert',
                    sortable: true,
                    width: 100
                },{
                    header: "Barang Paket",
                    dataIndex: 'is_barang_paket',
                    sortable: true,
                    width: 100
                },{
                    header: "Harga Beli",
                    dataIndex: 'hrg_supplier',
                    sortable: true,
                    width: 100
                },{
                    header: "Net Price Beli Supermarket",
                    dataIndex: 'hrg_beli_sup',
                    sortable: true,
                    width: 100
                },{
                    header: "Net Price Beli Distribusi",
                    dataIndex: 'hrg_beli_dist',
                    sortable: true,
                    width: 100
                },{
                    header: "COGS Supermarket",
                    dataIndex: 'rp_cogs',
                    sortable: true,
                    width: 100
                },{
                    header: "COGS Distribusi",
                    dataIndex: 'rp_cogs_dist',
                    sortable: true,
                    width: 100
                },{
                    header: "Margin Supermarket %",
                    dataIndex: 'pct_margin',
                    sortable: true,
                    width: 100
                },{
                    header: "Margin Distribusi %",
                    dataIndex: 'pct_margin_dist',
                    sortable: true,
                    width: 100
                },{
                    header: "Margin Supermarket Rp",
                    dataIndex: 'rp_margin',
                    sortable: true,
                    width: 100
                },{
                    header: "Margin Distribusi Rp",
                    dataIndex: 'rp_margin_dist',
                    sortable: true,
                    width: 100
                },{
                    header: "Margin COGS Supermarket Rp",
                    dataIndex: 'margin_cogs',
                    sortable: true,
                    width: 100
                },{
                    header: "Margin COGS Distribusi Rp",
                    dataIndex: 'margin_cogs_dist',
                    sortable: true,
                    width: 100
                },{
                    header: "Ongkos Kirim Supermarket",
                    dataIndex: 'rp_ongkos_kirim',
                    sortable: true,
                    width: 100
                },{
                    header: "Ongkos Kirim Distribusi",
                    dataIndex: 'rp_ongkos_kirim_dist',
                    sortable: true,
                    width: 100
                },{
                    header: "HET Beli Supermarket",
                    dataIndex: 'rp_het_harga_beli',
                    sortable: true,
                    width: 100
                },{
                    header: "HET Beli Distribusi ",
                    dataIndex: 'rp_het_harga_beli_dist',
                    sortable: true,
                    width: 100
                },{
                    header: "HET COGS Supermarket",
                    dataIndex: 'rp_het_cogs',
                    sortable: true,
                    width: 100
                },{
                    header: "HET COGS Distribusi ",
                    dataIndex: 'rp_het_cogs_dist',
                    sortable: true,
                    width: 100
                },{
                    header: "Harga Jual Supermarket",
                    dataIndex: 'rp_jual_supermarket',
                    sortable: true,
                    width: 100
                },{
                    header: "Harga Jual Distribusi ",
                    dataIndex: 'rp_jual_distribusi',
                    sortable: true,
                    width: 100
                },{
                    header: "Disk Konsumen 1 %",
                    dataIndex: 'disk_persen_kons1',
                    sortable: true,
                    width: 100
                },{
                    header: "Disk konsumen 1 Rp ",
                    dataIndex: 'disk_amt_kons1',
                    sortable: true,
                    width: 100
                },{
                    header: "Disk Konsumen 2 %",
                    dataIndex: 'disk_persen_kons2',
                    sortable: true,
                    width: 100
                },{
                    header: "Disk konsumen 2 Rp ",
                    dataIndex: 'disk_amt_kons2',
                    sortable: true,
                    width: 100
                },{
                    header: "Disk Konsumen 3 %",
                    dataIndex: 'disk_persen_kons3',
                    sortable: true,
                    width: 100
                },{
                    header: "Disk konsumen 3 Rp ",
                    dataIndex: 'disk_amt_kons3',
                    sortable: true,
                    width: 100
                },{
                    header: "Disk Konsumen 4 %",
                    dataIndex: 'disk_persen_kons4',
                    sortable: true,
                    width: 100
                },{
                    header: "Disk konsumen 4 Rp ",
                    dataIndex: 'disk_amt_kons4',
                    sortable: true,
                    width: 100
                },{
                    header: "Disk Konsumen 5 Rp",
                    dataIndex: 'disk_amt_kons5',
                    sortable: true,
                    width: 100
                },{
                    header: "Disk Member 1 %",
                    dataIndex: 'disk_persen_member1',
                    sortable: true,
                    width: 100
                },{
                    header: "Disk Member 1 Rp ",
                    dataIndex: 'disk_amt_member2',
                    sortable: true,
                    width: 100
                },{
                    header: "Disk Member 2 %",
                    dataIndex: 'disk_persen_member2',
                    sortable: true,
                    width: 100
                },{
                    header: "Disk Member 2 Rp ",
                    dataIndex: 'disk_amt_member2',
                    sortable: true,
                    width: 100
                },{
                    header: "Disk Member 1 3%",
                    dataIndex: 'disk_persen_member3',
                    sortable: true,
                    width: 100
                },{
                    header: "Disk Member 3 Rp ",
                    dataIndex: 'disk_amt_member3',
                    sortable: true,
                    width: 100
                },{
                    header: "Disk Member 4 %",
                    dataIndex: 'disk_persen_member4',
                    sortable: true,
                    width: 100
                },{
                    header: "Disk Member 4 Rp ",
                    dataIndex: 'disk_amt_member4',
                    sortable: true,
                    width: 100
                },{
                    header: "Disk Member 5 Rp ",
                    dataIndex: 'disk_amt_member5',
                    sortable: true,
                    width: 100
                },{
                    header: "QTY Beli Bonus Konsumen",
                    dataIndex: 'qty_beli_bonus',
                    sortable: true,
                    width: 100
                },{
                    header: "QTY Beli Bonus Member",
                    dataIndex: 'qty_beli_member',
                    sortable: true,
                    width: 100
                },{
                    header: "Kode Produk Bonus Konsumen",
                    dataIndex: 'kd_produk_bonus',
                    sortable: true,
                    width: 100
                },{
                    header: "Kode Produk Bonus Member",
                    dataIndex: 'kd_produk_member',
                    sortable: true,
                    width: 100
                },{
                    header: "QTY Bonus Konsumen",
                    dataIndex: 'qty_bonus',
                    sortable: true,
                    width: 100
                },{
                    header: "QTY Bonus Member",
                    dataIndex: 'qty_member',
                    sortable: true,
                    width: 100
                },{
                    header: "Kelipatan Bonus Konsumen",
                    dataIndex: 'is_bonus_kelipatan',
                    sortable: true,
                    width: 100
                },{
                    header: "Kelipatan Bonus Member",
                    dataIndex: 'is_member_kelipatan',
                    sortable: true,
                    width: 100
                }],
            plugins: [actionlistbarang],
            listeners: {
                'rowdblclick': function(){				
                    var sm = listbarang.getSelectionModel();                
                    var sel = sm.getSelections();                
                    if (sel.length > 0) {
                        editlistbarang(sel[0].get('kd_produk'),sel[0].get('nama_kategori1'),
                        sel[0].get('nama_kategori2'),sel[0].get('nama_kategori3'),sel[0].get('nama_kategori4'),
                        sel[0].get('nm_satuan'));                    
                    }                 
                }          
            },
            tbar: tblistbarang,
            bbar: new Ext.PagingToolbar({
                pageSize: ENDPAGE,
                store: strlistbarang,
                displayInfo: true
            })
        });
        
         // cb kategori1
    var str_listbarang_cbkategori1 = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_kategori1', 'nama_kategori1'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("kategori2/get_kategori1") ?>',
            method: 'POST'
        }),
        listeners: {
            load: function() {
                var r = new (str_listbarang_cbkategori1.recordType)({
                    'kd_kategori1': '',
                    'nama_kategori1': '-----'
                });
                str_listbarang_cbkategori1.insert(0, r);
            },
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });
    
    var listbarang_cbkategori1 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 1',
        id: 'id_listbarang_cbkategori1',
        store: str_listbarang_cbkategori1,
        valueField: 'kd_kategori1',
        displayField: 'nama_kategori1',
        typeAhead: true,
        triggerAction: 'all',
        // allowBlank: false,
        editable: false,
        width: 170,
        anchor: '90%',
        hiddenName: 'nama_kategori1',
        emptyText: 'Pilih kategori 1',
        listeners: {
            'select': function(combo, records) {
                var kdhp_cbkategori1 = listbarang_cbkategori1.getValue();
                // hp_cbkategori2.setValue();
                listbarang_cbkategori2.store.proxy.conn.url = '<?= site_url("kategori3/get_kategori2") ?>/' + kdhp_cbkategori1;
                listbarang_cbkategori2.store.reload();            
            }
        }
    });
    
    // combobox kategori2
    var str_listbarang_cbkategori2 = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_kategori2', 'nama_kategori2'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({			
            url: '<?= site_url("kategori3/get_kategori2") ?>',
            method: 'POST'
        }),
        listeners: {
            load: function() {
                var r = new (str_listbarang_cbkategori2.recordType)({
                    'kd_kategori2': '',
                    'nama_kategori2': '-----'
                });
                str_listbarang_cbkategori2.insert(0, r);
            },
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    var listbarang_cbkategori2 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 2',
        id: 'id_listbarang_cbkategori2',
        mode: 'local',
        store: str_listbarang_cbkategori2,
        valueField: 'kd_kategori2',
        displayField: 'nama_kategori2',
        typeAhead: true,
        triggerAction: 'all',
        // allowBlank: false,
        editable: false,
        width: 170,
        anchor: '90%',
        hiddenName: 'nama_kategori2',
        emptyText: 'Pilih kategori 2',
        listeners: {
            select: function(combo, records) {
                var kd_hp_cbkategori1 = listbarang_cbkategori1.getValue();
                var kd_hp_cbkategori2 = this.getValue();
                listbarang_cbkategori3.setValue();
                listbarang_cbkategori3.store.proxy.conn.url = '<?= site_url("kategori4/get_kategori3") ?>/' + kd_hp_cbkategori1 +'/'+ kd_hp_cbkategori2;
                listbarang_cbkategori3.store.reload();          
            }
        }
    });
    
     // combobox kategori3
    var str_listbarang_cbkategori3 = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_kategori3', 'nama_kategori3'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("kategori4/get_kategori3") ?>',
            method: 'POST'
        }),
        listeners: {
            load: function() {
                var r = new (str_listbarang_cbkategori3.recordType)({
                    'kd_kategori3': '',
                    'nama_kategori3': '-----'
                });
                str_listbarang_cbkategori3.insert(0, r);
            },
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });
	
    var listbarang_cbkategori3 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 3',
        id: 'id_listbarang_cbkategori3',
        mode: 'local',
        store: str_listbarang_cbkategori3,
        valueField: 'kd_kategori3',
        displayField: 'nama_kategori3',
        typeAhead: true,
        triggerAction: 'all',
        // allowBlank: false,
        editable: false,
        width: 170,
        anchor: '90%',
        hiddenName: 'nama_kategori3',
        emptyText: 'Pilih kategori 3',
        listeners: {
            select: function(combo, records) {
                var kd_hp_cbkategori1 = listbarang_cbkategori1.getValue();
                var kd_hp_cbkategori2 = listbarang_cbkategori2.getValue();
                var kd_hp_cbkategori3 = this.getValue();
                listbarang_cbkategori4.setValue();
                listbarang_cbkategori4.store.proxy.conn.url = '<?= site_url("master_barang/get_kategori4") ?>/' + kd_hp_cbkategori1 +'/'+ kd_hp_cbkategori2 +'/'+ kd_hp_cbkategori3;
                listbarang_cbkategori4.store.reload();     
            }
        }
    });
    
    // combobox kategori4
    var str_listbarang_cbkategori4 = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_kategori4', 'nama_kategori4'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("master_barang/get_kategori4") ?>',
            method: 'POST'
        }),
        listeners: {
            load: function() {
                var r = new (str_listbarang_cbkategori4.recordType)({
                    'kd_kategori4': '',
                    'nama_kategori4': '-----'
                });
                str_listbarang_cbkategori4.insert(0, r);
            },
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    var listbarang_cbkategori4 = new Ext.form.ComboBox({   
        fieldLabel: 'Kategori 4',
        id: 'id_listbarang_cbkategori4',
        mode: 'local',
        store: str_listbarang_cbkategori4,
        valueField: 'kd_kategori4',
        displayField: 'nama_kategori4',
        typeAhead: true,
        triggerAction: 'all',
        // allowBlank: false,
        editable: false,
        width: 170,
        anchor: '90%',
        hiddenName: 'nama_kategori4',
        emptyText: 'Pilih kategori 4'
    });
    
    // combobox Ukuran
	var str_listbarang_cbukuran = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_ukuran', 'nama_ukuran'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("master_barang/get_ukuran_produk") ?>',
            method: 'POST'
        }),
		listeners: {
            load: function() {
                var r = new (str_listbarang_cbukuran.recordType)({
                    'kd_ukuran': '',
                    'nama_ukuran': '-----'
                });
                str_listbarang_cbukuran.insert(0, r);
            },
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });
    
    var listbarang_cbukuran = new Ext.form.ComboBox({
        fieldLabel: 'Ukuran',
        id: 'id_listbarang_cbukuran',
        store: str_listbarang_cbukuran,
        valueField: 'kd_ukuran',
        displayField: 'nama_ukuran',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_ukuran',
        emptyText: 'Pilih Ukuran'
       
    });
    
    // combobox Satuan
	var str_listbarang_cbsatuan = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_satuan', 'nm_satuan'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("master_barang/get_satuan_produk") ?>',
            method: 'POST'
        }),
		listeners: {
            load: function() {
                var r = new (str_listbarang_cbsatuan.recordType)({
                    'kd_ukuran': '',
                    'nama_ukuran': '-----'
                });
                str_listbarang_cbsatuan.insert(0, r);
            },
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });
    
    var listbarang_cbsatuan = new Ext.form.ComboBox({
        fieldLabel: 'Satuan',
        id: 'id_listbarang_cbsatuan',
        store: str_listbarang_cbsatuan,
        valueField: 'kd_satuan',
        displayField: 'nm_satuan',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_satuan',
        emptyText: 'Pilih Satuan'
       
    });
    
    //combo supplier
    var strlistbarang_cbsuplier = new Ext.data.ArrayStore({
        fields: ['kd_supplier'],
        data : []
        });
	
       var strgridlbsuplier = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_supplier', 'nama_supplier'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
       url: '<?= site_url("laporan_purchase_order/search_supplier") ?>',
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
	
        var searchgridlbsuplier = new Ext.app.SearchField({
        store: strgridlbsuplier,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE			
        },
        width: 350,
        id: 'id_searchgridlbsuplier'
    });

        var gridlbsuplier = new Ext.grid.GridPanel({
        store: strgridlbsuplier,
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
	        items: [searchgridlbsuplier]
	    }),
            bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridlbsuplier,
            displayInfo: true
        }),
		listeners: {
			'rowdblclick': function(){			
				var sm = this.getSelectionModel();
				var sel = sm.getSelections();
				if (sel.length > 0) {				
                    Ext.getCmp('id_listbarang_cbsuplier').setValue(sel[0].get('kd_supplier'));
                    menulbsuplier.hide();
				}
			}
		}
    });

        var menulbsuplier = new Ext.menu.Menu();
        menulbsuplier.add(new Ext.Panel({
        title: 'Pilih Supplier',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridlbsuplier],
        buttons: [{
        text: 'Close',
        handler: function(){
            menulbsuplier.hide();
            }
        }]
    }));
    
    Ext.ux.TwinComboSuplier = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
			//load store grid
            strgridlbsuplier.load();
            menulbsuplier.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
	
	menulbsuplier.on('hide', function(){
		var sf = Ext.getCmp('id_searchgridlbsuplier').getValue();
		if( sf != ''){
			Ext.getCmp('id_searchgridlbsuplier').setValue('');
			searchgridlbsuplier.onTrigger2Click();
		}
	});
	
        var listbarang_cbsuplier = new Ext.ux.TwinComboSuplier({
        fieldLabel: 'Supplier',
        id: 'id_listbarang_cbsuplier',
        store: strlistbarang_cbsuplier,
	mode: 'local',
        valueField: 'kd_supplier',
        displayField: 'kd_supplier',
        typeAhead: true,
        triggerAction: 'all',
        // allowBlank: false,
        editable: false,
	anchor: '90%',
        hiddenName: 'kd_supplier',
        emptyText: 'Pilih Supplier'
    });
  
  var headerlistbarang = {
        layout: 'column',
        border: false,
        buttonAlign:'left',
        items: [{
                columnWidth: .5,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: { labelSeparator: ''},
                items: [listbarang_cbkategori1,listbarang_cbkategori2,listbarang_cbkategori3,listbarang_cbkategori4
                ]
            }, {
                columnWidth: .5,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: { labelSeparator: ''},
                items: [listbarang_cbsuplier,listbarang_cbukuran,listbarang_cbsatuan,
                                {       
                                        xtype: 'checkbox',
                                        fieldLabel: 'Is Konsinyasi',
                                        boxLabel:'Ya',
                                        name:'is_konsinyasi',
                                        id:'id_is_konsinyasi',
                                        //checked: true,
                                        inputValue: 1,
                                        autoLoad : true
                                }]
            }],
        buttons: [{
                text: 'Filter',
                formBind: true,
                handler: function(){
					/*var kd_supplier =  Ext.getCmp('id_cbhpsuplier').getValue();
					if(!kd_supplier){
						 Ext.Msg.show({
							title: 'Error',
							msg: 'Silahkan Pilih Supplier Terlebih Dahulu',
							modal: true,
							icon: Ext.Msg.ERROR,
							buttons: Ext.Msg.OK,
							// fn: function(btn){
								// if (btn == 'ok' && msg == 'Session Expired') {
									// window.location = '<?= site_url("auth/login") ?>';
								// }
							// }
						});
						return;
					}*/
                    strlistbarang.load({
                        params:{
                            start: STARTPAGE,
                            limit: ENDPAGE,
                            kd_ukuran: Ext.getCmp('id_listbarang_cbukuran').getValue(),
                            kd_satuan: Ext.getCmp('id_listbarang_cbsatuan').getValue(),
                            kd_kategori1: Ext.getCmp('id_listbarang_cbkategori1').getValue(),
                            kd_kategori2: Ext.getCmp('id_listbarang_cbkategori2').getValue(),
                            kd_kategori3: Ext.getCmp('id_listbarang_cbkategori3').getValue(),
                            kd_kategori4: Ext.getCmp('id_listbarang_cbkategori4').getValue(),
                            kd_supplier: Ext.getCmp('id_listbarang_cbsuplier').getValue(),
                            is_konsinyasi: Ext.getCmp('id_is_konsinyasi').getValue()
                        }
                    });   
                }
            },{
                text: 'Reset',
                handler: function(){
                    clearlistbarang();
                }
            }]
    };
  
       var list_barang_panel = new Ext.FormPanel({
        id: 'listbarang',
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
                items: [headerlistbarang]
            },
            listbarang
        ]
    });
    
        function editlistbarang(kd_produk,nm_kategori1,nm_kategori2,nm_kategori3,nm_kategori4,nm_satuan){
            str_list_brg_cbkategori1.load();
            str_list_brg_cbkategori2.load();
            str_list_brg_cbkategori3.load();
            str_list_brg_cbkategori4.load();
            strcbsatuan.load();
            // Ext.getCmp('id_list_brg_cbkategori1').setValue(nm_kategori1);
            // Ext.getCmp('id_list_brg_cbkategori2').setValue(nm_kategori2);   
            // Ext.getCmp('id_list_brg_cbkategori3').setValue(nm_kategori3); 
            // Ext.getCmp('id_list_brg_cbkategori4').setValue(nm_kategori4); 
            Ext.getCmp('id_cbsatuan').setValue(nm_satuan); 
            Ext.getCmp('id_list_brg_cbkategori1').setDisabled(true);
            Ext.getCmp('id_list_brg_cbkategori2').setDisabled(true);
            Ext.getCmp('id_list_brg_cbkategori3').setDisabled(true);
            Ext.getCmp('id_list_brg_cbkategori4').setDisabled(true);
            Ext.getCmp('id_cbsatuan').setDisabled(true);
            Ext.getCmp('btnresetlistbarang').hide();
            Ext.getCmp('btnsubmitlistbarang').setText('Update');
            winaddlistbarang.setTitle('Edit Form');
            Ext.getCmp('id_formaddlistbarang').getForm().load({
                url: '<?= site_url("list_barang/get_row") ?>',
                params: {
                    id: kd_produk,
                    cmd: 'get'
                },                  
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
            winaddlistbarang.show();
        }
        function deletelistbarang(){		
            var sm = listbarang.getSelectionModel();
            var sel = sm.getSelections();
            if (sel.length > 0) {
                Ext.Msg.show({
                    title: 'Confirm',
                    msg: 'Are you sure delete selected row ?',
                    buttons: Ext.Msg.YESNO,
                    fn: function(btn){
                        if (btn == 'yes') {
                    
                            var data = '';
                            for (i = 0; i < sel.length; i++) {
                                data = data + sel[i].get('kd_produk') + ';';
                            }
                        
                            Ext.Ajax.request({
                                url: '<?= site_url("list_barang/delete_rows") ?>',
                                method: 'POST',
                                params: {
                                    postdata: data
                                },
                                callback:function(opt,success,responseObj){
                                    var de = Ext.util.JSON.decode(responseObj.responseText);
                                    if(de.success==true){
                                        strlistbarang.reload();
                                        strlistbarang.load({
                                            params: {
                                                start: STARTPAGE,
                                                limit: ENDPAGE
                                            }
                                        });
                                    }else{
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
                                }
                            });                 
                        } 
                    }
                });
            }
            else {
                Ext.Msg.show({
                    title: 'Info',
                    msg: 'Please selected row',
                    modal: true,
                    icon: Ext.Msg.ERROR,
                    buttons: Ext.Msg.OK
                });
            }
        
        }
        function clearlistbarang(){
        Ext.getCmp('listbarang').getForm().reset();
        Ext.getCmp('listbarang').getForm().load({
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
        strlistbarang.removeAll();
    }
</script>
