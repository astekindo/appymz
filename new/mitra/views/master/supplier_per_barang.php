<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">
    /* START FORM */ 
    var strcbkdprodukspb = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_produk', 'kd_produk_lama', 'nama_produk'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("supplier_per_barang/get_produk") ?>',
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
	
    var searchspbproduk = new Ext.app.SearchField({
        store: strcbkdprodukspb,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        id: 'spbsearchlistbarang'
    });
    
    var tbspbproduk = new Ext.Toolbar({
        items: [searchspbproduk]
    });
	
    var gridspbsearchproduk = new Ext.grid.GridPanel({
        store: strcbkdprodukspb,
        stripeRows: true,
        frame: true,
        border:true,
        columns: [{
                header: 'Kode Produk',
                dataIndex: 'kd_produk',
                width: 90,
                sortable: true,			
            
            },{
                header: 'Kode Produk Lama',
                dataIndex: 'kd_produk_lama',
                width: 110,
                sortable: true,			
            
            },{
                header: 'Nama Produk',
                dataIndex: 'nama_produk',
                width: 340,
                sortable: true,         
            }],
        listeners: {
            'rowdblclick': function(){			
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('spb_kd_produk_add').setValue(sel[0].get('kd_produk'));
                    Ext.getCmp('spb_nama_produk_add').setValue(sel[0].get('nama_produk'));
                }
                menuspb.hide();
            }
        },
        tbar:tbspbproduk,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strcbkdprodukspb,
            displayInfo: true
        })
    });
	
	

    var menuspb = new Ext.menu.Menu();
    menuspb.add(new Ext.Panel({
        title: 'Pilih Produk',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 500,
        height: 400,
        closeAction: 'hide',
        plain: true,
        items: [gridspbsearchproduk],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menuspb.hide();
                }
            }]
    }));
		
    Ext.ux.TwinCombospb = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            //load store grid
            var sm = grid.getSelectionModel();                
            var sel = sm.getSelections(); 				
                
            strcbkdprodukspb.load({
                params:{
                    kd_supplier: sel[0].get('kd_supplier')
                }
            });
            menuspb.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
	
    var str_spb_cbkategori1 = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_kategori1', 'nama_kategori1'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("master_barang/get_kategori1") ?>',
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
	
    //Start Kategori 1
   
    var spb_cbkategori1 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 1',
        id: 'add_spb_cbkategori1',
        store: str_spb_cbkategori1,
        valueField: 'kd_kategori1',
        displayField: 'nama_kategori1',
        typeAhead: true,
        triggerAction: 'all',
        // allowBlank: false,
        editable: false,
        width: 250,
        anchor: '90%',
        hiddenName: 'nama_kategori1',
        emptyText: 'Pilih kategori 1',
        listeners: {
            'select': function(combo, records) {
                var kdspb_cbkategori1 = spb_cbkategori1.getValue();
                spb_cbkategori2.setValue();
                spb_cbkategori3.setValue();
                spb_cbkategori4.setValue();
                spb_cbkategori2.store.proxy.conn.url = '<?= site_url("master_barang/get_kategori2") ?>/' + kdspb_cbkategori1;
                spb_cbkategori2.store.reload();
            }
        }
    });
	
    //End Kategori 1
	
    //Start Kategori 2
	
    var str_spb_cbkategori2 = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_kategori2', 'nama_kategori2'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({			
            url: '<?= site_url("master_barang/get_kategori2") ?>',
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
	
    var spb_cbkategori2 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 2',
        id: 'add_spb_cbkategori2',
        mode: 'local',
        store: str_spb_cbkategori2,
        valueField: 'kd_kategori2',
        displayField: 'nama_kategori2',
        typeAhead: true,
        triggerAction: 'all',
        // allowBlank: false,
        editable: false,
        width: 250,
        anchor: '90%',
        hiddenName: 'nama_kategori2',
        emptyText: 'Pilih kategori 2',
        listeners: {
            select: function(combo, records) {
                var kd_spb_cbkategori1 = spb_cbkategori1.getValue();
                var kd_spb_cbkategori2 = this.getValue();
                spb_cbkategori3.setValue();
                spb_cbkategori4.setValue();
                spb_cbkategori3.store.proxy.conn.url = '<?= site_url("master_barang/get_kategori3") ?>/' + kd_spb_cbkategori1 +'/'+ kd_spb_cbkategori2;
                spb_cbkategori3.store.reload();
            }
        }
    });
	
    //End Kategori 2
	
    //Start Kategori 3
	
    var str_spb_cbkategori3 = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_kategori3', 'nama_kategori3'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("master_barang/get_kategori3") ?>',
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
	
    var spb_cbkategori3 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 3',
        id: 'add_spb_cbkategori3',
        mode: 'local',
        store: str_spb_cbkategori3,
        valueField: 'kd_kategori3',
        displayField: 'nama_kategori3',
        typeAhead: true,
        triggerAction: 'all',
        // allowBlank: false,
        editable: false,
        width: 250,
        anchor: '90%',
        hiddenName: 'nama_kategori3',
        emptyText: 'Pilih kategori 3',
        listeners: {
            select: function(combo, records) {
                var kd_spb_cbkategori1 = spb_cbkategori1.getValue();
                var kd_spb_cbkategori2 = spb_cbkategori2.getValue();
                var kd_spb_cbkategori3 = this.getValue();
                spb_cbkategori4.setValue();
                spb_cbkategori4.store.proxy.conn.url = '<?= site_url("master_barang/get_kategori4") ?>/' + kd_spb_cbkategori1 +'/'+ kd_spb_cbkategori2 +'/'+ kd_spb_cbkategori3;
                spb_cbkategori4.store.reload();
            }
        }
    });
	
    //End Kategori 3
	
    //Start Kategori 4
	
    var str_spb_cbkategori4 = new Ext.data.Store({
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
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    var spb_cbkategori4 = new Ext.form.ComboBox({   
        fieldLabel: 'Kategori 4',
        id: 'add_spb_cbkategori4',
        mode: 'local',
        store: str_spb_cbkategori4,
        valueField: 'kd_kategori4',
        displayField: 'nama_kategori4',
        typeAhead: true,
        triggerAction: 'all',
        // allowBlank: false,
        editable: false,
        width: 250,
        anchor: '90%',
        hiddenName: 'nama_kategori4',
        emptyText: 'Pilih kategori 4'
    });
	
    //End Kategori 4
	
    // Start Grid Produk Add
	
    var str_produkadd_spb = new Ext.data.Store({
        autoSave:false,		
        reader: new Ext.data.JsonReader({
            fields: [
                {name: 'add', allowBlank: false, type: 'text'},
                {name: 'kd_produk', allowBlank: false, type: 'text'},
                {name: 'kd_produk_supp', allowBlank: false, type: 'text'},
                {name: 'kd_produk_lama', allowBlank: false, type: 'text'},
                {name: 'nama_produk', allowBlank: false, type: 'text'},
                {name: 'nm_satuan', allowBlank: false, type: 'text'},
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("supplier_per_barang/search_produk_by_kategori") ?>',
            method: 'POST'
        }),
        writer: new Ext.data.JsonWriter(
        {
            encode: true,
            writeAllFields: true
        })
    });
	
    str_produkadd_spb.on('load',function(){
        str_produkadd_spb.setBaseParam('kd_kategori1',Ext.getCmp('add_spb_cbkategori1').getValue());
        str_produkadd_spb.setBaseParam('kd_kategori2',Ext.getCmp('add_spb_cbkategori2').getValue());
        str_produkadd_spb.setBaseParam('kd_kategori3',Ext.getCmp('add_spb_cbkategori3').getValue());
        str_produkadd_spb.setBaseParam('kd_kategori4',Ext.getCmp('add_spb_cbkategori4').getValue());
    })
	
    var editor_produkadd_spb = new Ext.ux.grid.RowEditor({
        saveText: 'Update'		
    });
	
    var addSpb = new Ext.grid.CheckColumn({
        header:'Add',      
        id:'spb_addGrid_add',       
        dataIndex: 'add',             
        width: 55
      
    });
	
    var grid_produkadd_spb = new Ext.grid.GridPanel({
        store: str_produkadd_spb,
        stripeRows: true,
        height: 200,
        loadMask: true,
        frame: true,
        border:true,
        plugins: [addSpb],
        columns: [addSpb,{
                header: 'Kode Barang',
                dataIndex: 'kd_produk',
                width: 100,
                sortable: true,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    fieldClass:'readonly-input',
                    id: 'spb_addGrid_kd_produk'
                })
            },{
                header: 'Kode Barang Lama',
                dataIndex: 'kd_produk_lama',
                width: 110,
                sortable: true,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    fieldClass:'readonly-input',
                    id: 'spb_addGrid_kd_produk_lama'
                })
            },{
                header: 'Kode Barang Supplier',
                dataIndex: 'kd_produk_supp',
                width: 110,
                sortable: true,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    fieldClass:'readonly-input',
                    id: 'spb_addGrid_kd_produk_supp'
                })
            },{
                header: 'Nama Barang',
                dataIndex: 'nama_produk',
                width: 300,
                sortable: true,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    fieldClass:'readonly-input',
                    id: 'spb_addGrid_nama_produk'
                })
            },{
                header: 'Satuan',
                dataIndex: 'nm_satuan',
                width: 80,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    fieldClass:'readonly-input',
                    id: 'spb_addGrid_satuan'
                })
            }],
    });
    // End Grid Produk Add
	
    Ext.ns('supplier_per_barang_form');
    supplier_per_barang_form.Form = Ext.extend(Ext.form.FormPanel, {
    
        // defaults - can be changed from outside
        border: false,
        frame: true,
        labelWidth: 100,
        waitMsg:'Loading...',
        url: '<?= site_url("supplier_per_barang/update_row") ?>',
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
            supplier_per_barang_form.Form.superclass.constructor.call(this, config);
        },
        initComponent: function(){
        
            // hard coded - cannot be changed from outsid
            var config = {
                defaultType: 'textfield',
                defaults: { labelSeparator: ''},
                monitorValid: true,
                autoScroll: true // ,buttonAlign:'right'
                ,
                items: [{
                        type: 'textfield',
                        fieldLabel: 'Kode Supplier <span class="asterix">*</span>',
                        name: 'kd_supplier',
                        id: 'id_kd_supp_add',
                        readOnly:true,
                        fieldClass:'readonly-input',
                        anchor: '90%'                
                    },{
                        type: 'textfield',
                        fieldLabel: 'Nama Supplier <span class="asterix">*</span>',
                        name: 'nama_supplier',
                        id: 'id_nama_supp_add',
                        readOnly:true,
                        fieldClass:'readonly-input',
                        anchor: '90%'                
                    },{
                        type: 'textfield',
                        fieldLabel: 'Status PKP <span class="asterix">*</span>',
                        name: 'pkp',
                        id: 'spb_pkp_add',
                        readOnly:true,
                        fieldClass:'readonly-input',
                        anchor: '90%'                
                    },{
                        xtype: 'numberfield',
                        fieldLabel: 'Waktu TOP <span class="asterix">*</span>',
                        name: 'waktu_top',
                        // readOnly:true,fieldClass:'readonly-input',
                        allowBlank: false,
                        id: 'id_waktu_top_add',
                        maxLength: 11,
                        style: 'text-align:right;',
                        value: 0,
                        anchor: '90%'                
                    },{
                        xtype:'fieldset',
                        autoheight: true,
                        checkboxToggle:true,
                        id: 'by_kategori',
                        title: 'Add by Kategori',
                        collapsed: true,
                        anchor: '90%',
                        items:[ spb_cbkategori1,spb_cbkategori2,spb_cbkategori3,spb_cbkategori4,
                            {	xtype: 'button',
                                text: 'Filter',
                                formBind: true,
                                handler: function(){
                                    var kategori1 =  Ext.getCmp('add_spb_cbkategori1').getValue();
                                    if(!kategori1){
                                        Ext.Msg.show({
                                            title: 'Error',
                                            msg: 'Silahkan Pilih Kategori1 Terlebih Dahulu',
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
                                    }
                                    str_produkadd_spb.load({
                                        params:{
                                            start: STARTPAGE,
                                            limit: ENDPAGE,
                                            kd_kategori1: Ext.getCmp('add_spb_cbkategori1').getValue(),
                                            kd_kategori2: Ext.getCmp('add_spb_cbkategori2').getValue(),
                                            kd_kategori3: Ext.getCmp('add_spb_cbkategori3').getValue(),
                                            kd_kategori4: Ext.getCmp('add_spb_cbkategori4').getValue(),
                                            // no_bukti: Ext.getCmp('id_cbhpnobuktifilter').getValue(),
                                            // list: Ext.getCmp('spb_addGrid_list').getValue(),
                                            // kd_supplier: Ext.getCmp('id_cbhpsuplier').getValue(),
						
                                        }
                                    });   
                                }
                            },grid_produkadd_spb
                        ]
                    },{
                        xtype:'fieldset',
                        autoheight: true,
                        checkboxToggle:true,
                        collapsed: false,
                        id: 'by_produk',
                        title: 'Add by Produk',
                        anchor: '90%',
                        items:[new Ext.ux.TwinCombospb({
                                id: 'spb_kd_produk_add',
                                fieldLabel: 'Barang <span class="asterix">*</span>',
                                store: strcbkdprodukspb,
                                valueField: 'kd_produk',
                                displayField: 'kd_produk',
                                typeAhead: true,	
                                editable: false,
                                // readOnly:true,
                                // fieldClass:'readonly-input',
                                hiddenName: 'kd_produk',
                                emptyText: 'Pilih Kode Produk',    
                                anchor: '90%',
                                listeners:{
                                    'expand': function(){
                                        strcbkdprodukspb.load();
                                    }
                                }
                            }),{
                                xtype: 'textfield',
                                fieldLabel: 'Nama Produk <span class="asterix">*</span>',
                                name: 'nama_produk',
                                id: 'spb_nama_produk_add',
                                fieldClass:'readonly-input',
                                readOnly:true,
                                anchor: '90%'                
                            }
                        ]
                    },],
                buttons: [{
                        text: 'Submit',
                        id: 'btnsubmitsupplier_per_barang_',
                        formBind: true,
                        scope: this,
                        handler: this.submit
                    }, {
                        text: 'Reset',
                        id: 'btnresetsupplier_per_barang_',
                        scope: this,
                        handler: this.reset
                    }, {
                        text: 'Close',
                        id: 'btnClosesupplier_per_barang_',
                        scope: this,
                        handler: function(){
                            winaddsupplier_per_barang_.hide();
                        }
                    }]
            }; // eo config object
            // apply config
            Ext.apply(this, Ext.apply(this.initialConfig, config));
            
            // call parent
            supplier_per_barang_form.Form.superclass.initComponent.apply(this, arguments);
            
        } // eo function initComponent	
        ,
        onRender: function(){
        
            // call parent
            supplier_per_barang_form.Form.superclass.onRender.apply(this, arguments);
            
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
            var text = Ext.getCmp('btnsubmitsupplier_per_barang_').getText();
            if (text == 'Update'){
                Ext.Msg.show({
                    title: 'Confirm',
                    msg: 'Are you sure update selected row ?',
                    buttons: Ext.Msg.YESNO,
                    fn: function(btn){
                        if (btn == 'yes') {
                            Ext.getCmp('id_formaddsupplier_per_barang_').getForm().submit({
                                url: Ext.getCmp('id_formaddsupplier_per_barang_').url,
                                scope: this,
                                success: Ext.getCmp('id_formaddsupplier_per_barang_').onSuccess,
                                failure: Ext.getCmp('id_formaddsupplier_per_barang_').onFailure,
                                params: {
                                    cmd: 'save'
                                },
                                waitMsg: 'Saving Data...'
                            });
                        }
                    }
                })
            }else{ 
                var detail_produkadd_spb = new Array();              
                str_produkadd_spb.each(function(node){
                    detail_produkadd_spb.push(node.data)
                });
                Ext.getCmp('id_formaddsupplier_per_barang_').getForm().submit({
                    url: Ext.getCmp('id_formaddsupplier_per_barang_').url,
                    scope: this,
                    success: Ext.getCmp('id_formaddsupplier_per_barang_').onSuccess,
                    failure: Ext.getCmp('id_formaddsupplier_per_barang_').onFailure,
                    params: {
                        cmd: 'save',
                        detail: Ext.util.JSON.encode(detail_produkadd_spb)
                    },
                    waitMsg: 'Saving Data...'
                });
            }
		
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
            
            
            // strsupplier_per_barang_.reload();
            strsupplierperbarang.load({
                params:{fieldId:Ext.getCmp('id_kd_supplier_search').getValue()}
            });
            Ext.getCmp('id_formaddsupplier_per_barang_').getForm().reset();
            winaddsupplier_per_barang_.hide();
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
    Ext.reg('formaddsupplier_per_barang_', supplier_per_barang_form.Form);
    
    var winaddsupplier_per_barang_ = new Ext.Window({
        id: 'id_winaddsupplier_per_barang_',
        closeAction: 'hide',
        width: 750,
        height: 600,
        layout: 'fit',
        border: false,
        items: {
            id: 'id_formaddsupplier_per_barang_',
            xtype: 'formaddsupplier_per_barang_'
        },
        onHide: function(){
            Ext.getCmp('id_formaddsupplier_per_barang_').getForm().reset();
        }
    });
	
	
    Ext.ns('supplier_per_barangform');
    supplier_per_barangform.Form = Ext.extend(Ext.form.FormPanel, {
    
        // defaults - can be changed from outside
        border: false,
        frame: true,
        labelWidth: 130,
        url: '<?= site_url("supplier_per_barang/update_row") ?>',
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
            supplier_per_barangform.Form.superclass.constructor.call(this, config);
        },
        initComponent: function(){
        
            // hard coded - cannot be changed from outsid
            var config = {
                layout:'form',
                autoScroll:true,
                items:[{
                        layout: 'column',
                        defaults: {
                            // implicitly create Container by specifying xtype
                            xtype: 'container',
                            monitorValid: true,
                            autoEl: 'div', // This is the default.
                            layout: 'form',
                            defaultType: 'textfield',
                            style: {
                                padding: '10px'
                            }
                        },
                        //  The two items below will be Ext.Containers, each encapsulated by a <DIV> element.
                        items: [{
                                columnWidth: 0.5,
                                items: [{
                                        xtype: 'hidden',
                                        name: 'action',
                                        id: 'id_action'
                                    },{
                                        type: 'textfield',
                                        fieldLabel: 'Kode Supplier <span class="asterix">*</span>',
                                        name: 'kd_supplier',
                                        id: 'id_kd_supp',
                                        readOnly:true,
                                        fieldClass:'readonly-input',
                                        anchor: '90%'                
                                    },{
                                        type: 'textfield',
                                        fieldLabel: 'Nama Supplier <span class="asterix">*</span>',
                                        name: 'nama_supplier',
                                        id: 'id_nama_supp',
                                        readOnly:true,
                                        fieldClass:'readonly-input',
                                        anchor: '90%'                
                                    },{
                                        type: 'textfield',
                                        fieldLabel: 'Status PKP <span class="asterix">*</span>',
                                        name: 'pkp',
                                        id: 'spb_pkp',
                                        readOnly:true,
                                        fieldClass:'readonly-input',
                                        anchor: '90%'                
                                    },new Ext.ux.TwinCombospb({
                                        id: 'spb_kd_produk',
                                        fieldLabel: 'Barang <span class="asterix">*</span>',
                                        store: strcbkdprodukspb,
                                        valueField: 'kd_produk',
                                        displayField: 'kd_produk',
                                        typeAhead: true,	
                                        editable: false,
                                        readOnly:true,
                                        fieldClass:'readonly-input',
                                        hiddenName: 'kd_produk',
                                        emptyText: 'Pilih Kode Produk',    
                                        anchor: '90%',
                                        listeners:{
                                            'expand': function(){
                                                strcbkdprodukspb.load();
                                            }
                                        }
                                    }),{
                                        type: 'textfield',
                                        fieldLabel: 'Nama Produk <span class="asterix">*</span>',
                                        name: 'nama_produk',
                                        id: 'spb_nama_produk',fieldClass:'readonly-input',
                                        readOnly:true,
                                        anchor: '90%'                
                                    },{
                                        xtype: 'fieldset',
                                        title:'SuperMarket',
                                        autoHeight: true,  
                                        style: 'margin-left:0px',
                                        items: [ {
                                                xtype : 'compositefield',
                                                msgTarget: 'side',
                                                fieldLabel: 'Disk Supplier 1',
                                                items : [{
                                                        xtype:          'combo',
                                                        mode:           'local',
                                                        value:          '',
                                                        triggerAction:  'all',
                                                        forceSelection: true,
                                                        readOnly:true,fieldClass:'readonly-input',
                                                        editable:       false,
                                                        name:           'disk_supp1_op',
                                                        id:           	'sb_disk_supp1_op',
                                                        hiddenName:     'disk_supp1_op',
                                                        displayField:   'name',
                                                        valueField:     'value',
                                                        width:	50,
                                                        store:          new Ext.data.JsonStore({
                                                            fields : ['name', 'value'],
                                                            data   : [
                                                                {name : '%',   value: 'persen'},
                                                                {name : 'Rp',  value: 'amount'},
                                                            ]
                                                        }),
                                                        listeners:{
                                                            select:function(){
                                                                if (this.getValue() == 'persen') 
                                                                    Ext.getCmp('sb_disk_supp1').maxValue = 100;
                                                                else Ext.getCmp('sb_disk_supp1').maxLength = 11;
                                                            }
                                                        }
                                                    },{					
                                                        xtype: 'numberfield',
                                                        name: 'disk_supp1',
                                                        allowBlank: false,
                                                        readOnly:true,fieldClass:'readonly-input',
                                                        id: 'sb_disk_supp1',
                                                        style: 'text-align:right;',
                                                        value: 0,
                                                        anchor: '90%'                
                                                    }]
                                            },{
                                                xtype : 'compositefield',
                                                msgTarget: 'side',
                                                fieldLabel: 'Disk Supplier 2',
                                                items : [{
                                                        xtype:          'combo',
                                                        mode:           'local',
                                                        value:          '',
                                                        triggerAction:  'all',
                                                        forceSelection: true,
                                                        editable:       false,
                                                        readOnly:true,fieldClass:'readonly-input',
                                                        name:           'disk_supp2_op',
                                                        id:           	'sb_disk_supp2_op',
                                                        hiddenName:     'disk_supp2_op',
                                                        displayField:   'name',
                                                        valueField:     'value',
                                                        width:	50,
                                                        store:          new Ext.data.JsonStore({
                                                            fields : ['name', 'value'],
                                                            data   : [
                                                                {name : '%',   value: 'persen'},
                                                                {name : 'Rp',  value: 'amount'},
                                                            ]
                                                        }),
                                                        listeners:{
                                                            select:function(){
                                                                if (this.getValue() == 'persen') 
                                                                    Ext.getCmp('sb_disk_supp2').maxValue = 100;
                                                                else Ext.getCmp('sb_disk_supp2').maxLength = 11;
                                                            }
                                                        }
                                                    },{					
                                                        xtype: 'numberfield',
                                                        name: 'disk_supp2',
                                                        allowBlank: false,
                                                        readOnly:true,fieldClass:'readonly-input',
                                                        id: 'sb_disk_supp2',
                                                        style: 'text-align:right;',
                                                        value: 0,
                                                        anchor: '90%'                
                                                    }]
                                            },{
                                                xtype : 'compositefield',
                                                msgTarget: 'side',
                                                fieldLabel: 'Disk Supplier 3',
                                                items : [{
                                                        xtype:          'combo',
                                                        mode:           'local',
                                                        value:          '',
                                                        triggerAction:  'all',
                                                        forceSelection: true,
                                                        editable:       false,
                                                        readOnly:true,fieldClass:'readonly-input',
                                                        name:           'disk_supp3_op',
                                                        id:           	'sb_disk_supp3_op',
                                                        hiddenName:     'disk_supp3_op',
                                                        displayField:   'name',
                                                        valueField:     'value',
                                                        width:	50,
                                                        store:          new Ext.data.JsonStore({
                                                            fields : ['name', 'value'],
                                                            data   : [
                                                                {name : '%',   value: 'persen'},
                                                                {name : 'Rp',  value: 'amount'},
                                                            ]
                                                        }),
                                                        listeners:{
                                                            select:function(){
                                                                if (this.getValue() == 'persen') 
                                                                    Ext.getCmp('sb_disk_supp3').maxValue = 100;
                                                                else Ext.getCmp('sb_disk_supp3').maxLength = 11;
                                                            }
                                                        }
                                                    },{					
                                                        xtype: 'numberfield',
                                                        name: 'disk_supp3',
                                                        allowBlank: false,
                                                        readOnly:true,fieldClass:'readonly-input',
                                                        id: 'sb_disk_supp3',
                                                        style: 'text-align:right;',
                                                        value: 0,
                                                        anchor: '90%'                
                                                    }]
                                            },{
                                                xtype : 'compositefield',
                                                msgTarget: 'side',
                                                fieldLabel: 'Disk Supplier 4',
                                                items : [{
                                                        xtype:          'combo',
                                                        mode:           'local',
                                                        value:          '',
                                                        triggerAction:  'all',
                                                        readOnly:true,fieldClass:'readonly-input',
                                                        forceSelection: true,
                                                        editable:       false,
                                                        name:           'disk_supp4_op',
                                                        id:           	'sb_disk_supp4_op',
                                                        hiddenName:     'disk_supp4_op',
                                                        displayField:   'name',
                                                        valueField:     'value',
                                                        width:	50,
                                                        store:          new Ext.data.JsonStore({
                                                            fields : ['name', 'value'],
                                                            data   : [
                                                                {name : '%',   value: 'persen'},
                                                                {name : 'Rp',  value: 'amount'},
                                                            ]
                                                        }),
                                                        listeners:{
                                                            select:function(){
                                                                if (this.getValue() == 'persen') 
                                                                    Ext.getCmp('sb_disk_supp4').maxValue = 100;
                                                                else Ext.getCmp('sb_disk_supp4').maxLength = 11;
                                                            }
                                                        }
                                                    },{					
                                                        xtype: 'numberfield',
                                                        name: 'disk_supp4',
                                                        allowBlank: false,
                                                        id: 'sb_disk_supp4',
                                                        readOnly:true,fieldClass:'readonly-input',
                                                        style: 'text-align:right;',
                                                        value: 0,
                                                        anchor: '90%'                
                                                    }]
                                            },{
                                                xtype : 'compositefield',
                                                msgTarget: 'side',
                                                fieldLabel: 'Disk Supplier 5',
                                                items : [ {					
                                                        xtype: 'numberfield',
                                                        name: 'disk_supp5',
                                                        allowBlank: false,
                                                        readOnly:true,fieldClass:'readonly-input',
                                                        id: 'sb_disk_supp5',
                                                        style: 'text-align:right;',
                                                        value: 0,
                                                        width: 187,
                                                        anchor: '90%'                
                                                    }]
                                            },{
                                                xtype: 'numberfield',
                                                fieldLabel: 'Harga Supplier SuperMarket (Rp) <span class="asterix">*</span>',
                                                name: 'net_hrg_supplier_sup',
                                                allowBlank: false,
                                                id: 'id_hrg_supp_super',
                                                readOnly:true,fieldClass:'readonly-input',
                                                maxLength: 11,
                                                style: 'text-align:right;',
                                                value: 0,
                                                anchor: '70%'                
                                            }
                                        ]}]
                            },{
                                columnWidth: 0.5,
                                items :[ {
                                        xtype: 'numberfield',
                                        fieldLabel: 'Waktu TOP <span class="asterix">*</span>',
                                        name: 'waktu_top',
                                        readOnly:true,fieldClass:'readonly-input',
                                        allowBlank: false,
                                        id: 'id_waktu_top',
                                        maxLength: 11,
                                        style: 'text-align:right;',
                                        value: 0,
                                        anchor: '90%'                
                                    },{
                                        xtype: 'numberfield',
                                        fieldLabel: 'Harga Supplier (Rp) <span class="asterix">*</span>',
                                        name: 'hrg_supplier',
                                        allowBlank: false,
                                        readOnly:true,fieldClass:'readonly-input',
                                        id: 'id_spb_hrg_supp',
                                        maxLength: 11,
                                        style: 'text-align:right;',
                                        value: 0,
                                        anchor: '90%'
                                    },{
                                        xtype: 'numberfield',
                                        fieldLabel: 'DPP <span class="asterix">*</span>',
                                        name: 'dpp',
                                        decimalSeparator: '.',
                                        readOnly:true,fieldClass:'readonly-input',
                                        id: 'id_spb_dpp',
                                        style: 'text-align:right;',
                                        maxLength: 11,
                                        anchor: '90%'                
                                    },{
                                        xtype: 'fieldset',title: 'Distribusi',
                                        autoHeight: true,  
                                        style: 'margin-left:0px',
                                        items: [ {
                                                xtype : 'compositefield',
                                                msgTarget: 'side',
                                                fieldLabel: 'Disk Distribusi 1',
                                                items : [{
                                                        xtype:          'combo',
                                                        mode:           'local',
                                                        value:          '',
                                                        triggerAction:  'all',
                                                        forceSelection: true,
                                                        editable:       false,
                                                        readOnly:true,fieldClass:'readonly-input',
                                                        name:           'disk_dist1_op',
                                                        id:           	'sb_disk_dist1_op',
                                                        hiddenName:     'disk_dist1_op',
                                                        displayField:   'name',
                                                        valueField:     'value',
                                                        width:	50,
                                                        store:          new Ext.data.JsonStore({
                                                            fields : ['name', 'value'],
                                                            data   : [
                                                                {name : '%',   value: 'persen'},
                                                                {name : 'Rp',  value: 'amount'},
                                                            ]
                                                        }),
                                                        listeners:{
                                                            select:function(){
                                                                if (this.getValue() == 'persen') 
                                                                    Ext.getCmp('sb_disk_dist1').maxValue = 100;
                                                                else Ext.getCmp('sb_disk_dist1').maxLength = 11;
                                                            }
                                                        }
                                                    },{					
                                                        xtype: 'numberfield',
                                                        name: 'disk_dist1',
                                                        allowBlank: false,
                                                        id: 'sb_disk_dist1',
                                                        readOnly:true,fieldClass:'readonly-input',
                                                        style: 'text-align:right;',
                                                        value: 0,
                                                        anchor: '90%'                
                                                    }]
                                            },{
                                                xtype : 'compositefield',
                                                msgTarget: 'side',
                                                fieldLabel: 'Disk Distribusi 2',
                                                items : [{
                                                        xtype:          'combo',
                                                        mode:           'local',
                                                        value:          '',
                                                        triggerAction:  'all',
                                                        forceSelection: true,
                                                        editable:       false,
                                                        readOnly:true,fieldClass:'readonly-input',
                                                        name:           'disk_dist2_op',
                                                        id:           	'sb_disk_dist2_op',
                                                        hiddenName:     'disk_dist2_op',
                                                        displayField:   'name',
                                                        valueField:     'value',
                                                        width:	50,
                                                        store:          new Ext.data.JsonStore({
                                                            fields : ['name', 'value'],
                                                            data   : [
                                                                {name : '%',   value: 'persen'},
                                                                {name : 'Rp',  value: 'amount'},
                                                            ]
                                                        }),
                                                        listeners:{
                                                            select:function(){
                                                                if (this.getValue() == 'persen') 
                                                                    Ext.getCmp('sb_disk_dist2').maxValue = 100;
                                                                else Ext.getCmp('sb_disk_dist2').maxLength = 11;
                                                            }
                                                        }
                                                    },{					
                                                        xtype: 'numberfield',
                                                        name: 'disk_dist2',
                                                        allowBlank: false,
                                                        id: 'sb_disk_dist2',
                                                        readOnly:true,fieldClass:'readonly-input',
                                                        style: 'text-align:right;',
                                                        value: 0,
                                                        anchor: '90%'                
                                                    }]
                                            },{
                                                xtype : 'compositefield',
                                                msgTarget: 'side',
                                                fieldLabel: 'Disk Distribusi 3',
                                                items : [{
                                                        xtype:          'combo',
                                                        mode:           'local',
                                                        value:          '',
                                                        triggerAction:  'all',
                                                        forceSelection: true,
                                                        readOnly:true,fieldClass:'readonly-input',
                                                        editable:       false,
                                                        name:           'disk_dist3_op',
                                                        id:           	'sb_disk_dist3_op',
                                                        hiddenName:     'disk_dist3_op',
                                                        displayField:   'name',
                                                        valueField:     'value',
                                                        width:	50,
                                                        store:          new Ext.data.JsonStore({
                                                            fields : ['name', 'value'],
                                                            data   : [
                                                                {name : '%',   value: 'persen'},
                                                                {name : 'Rp',  value: 'amount'},
                                                            ]
                                                        }),
                                                        listeners:{
                                                            select:function(){
                                                                if (this.getValue() == 'persen') 
                                                                    Ext.getCmp('sb_disk_dist3').maxValue = 100;
                                                                else Ext.getCmp('sb_disk_dist3').maxLength = 11;
                                                            }
                                                        }
                                                    },{					
                                                        xtype: 'numberfield',
                                                        name: 'disk_dist3',
                                                        allowBlank: false,
                                                        id: 'sb_disk_dist3',
                                                        readOnly:true,fieldClass:'readonly-input',
                                                        style: 'text-align:right;',
                                                        value: 0,
                                                        anchor: '90%'                
                                                    }]
                                            },{
                                                xtype : 'compositefield',
                                                msgTarget: 'side',
                                                fieldLabel: 'Disk Distribusi 4',
                                                items : [{
                                                        xtype:          'combo',
                                                        mode:           'local',
                                                        value:          '',
                                                        triggerAction:  'all',
                                                        forceSelection: true,
                                                        editable:       false,
                                                        readOnly:true,fieldClass:'readonly-input',
                                                        name:           'disk_dist4_op',
                                                        id:           	'sb_disk_dist4_op',
                                                        hiddenName:     'disk_dist4_op',
                                                        displayField:   'name',
                                                        valueField:     'value',
                                                        width:	50,
                                                        store:          new Ext.data.JsonStore({
                                                            fields : ['name', 'value'],
                                                            data   : [
                                                                {name : '%',   value: 'persen'},
                                                                {name : 'Rp',  value: 'amount'},
                                                            ]
                                                        }),
                                                        listeners:{
                                                            select:function(){
                                                                if (this.getValue() == 'persen') 
                                                                    Ext.getCmp('sb_disk_dist4').maxValue = 100;
                                                                else Ext.getCmp('sb_disk_dist4').maxLength = 11;
                                                            }
                                                        }
                                                    },{					
                                                        xtype: 'numberfield',
                                                        name: 'disk_dist4',
                                                        allowBlank: false,
                                                        readOnly:true,fieldClass:'readonly-input',
                                                        id: 'sb_disk_dist4',
                                                        style: 'text-align:right;',
                                                        value: 0,
                                                        anchor: '90%'                
                                                    }]
                                            },{
                                                xtype : 'compositefield',
                                                msgTarget: 'side',
                                                fieldLabel: 'Disk Distribusi 5',
                                                items : [ {					
                                                        xtype: 'numberfield',
                                                        name: 'disk_dist5',
                                                        allowBlank: false,
                                                        readOnly:true,fieldClass:'readonly-input',
                                                        id: 'sb_disk_dist5',
                                                        style: 'text-align:right;',
                                                        value: 0,
                                                        width: 187,
                                                        anchor: '90%'                
                                                    }]
                                            },{
                                                xtype: 'numberfield',
                                                fieldLabel: 'Harga Supplier Distribusi (Rp) <span class="asterix">*</span>',
                                                name: 'net_hrg_supplier_dist',
                                                allowBlank: false,
                                                readOnly:true,fieldClass:'readonly-input',
                                                id: 'id_hrg_supp_dist',
                                                maxLength: 11,
                                                style: 'text-align:right;',
                                                value: 0,
                                                anchor: '70%'                
                                            }
                                        ]
                                    },{
                                        xtype: 'checkbox',
                                        fieldLabel: 'Aktif',
                                        boxLabel:'Ya',
                                        name:'aktif',
                                        id:'id_cekaktif',
                                        checked: true,
                                        inputValue: 1,
                                        autoLoad : true
                                }]
                            }]}],
                buttons: [{
                text: 'Save',
                //formBind: true,
                handler: function(){
              var aktif = Ext.getCmp('id_cekaktif').getValue();
                var kd_barang = Ext.getCmp('spb_kd_produk').getValue();
                var kd_suppier_akt = Ext.getCmp('id_kd_supp').getValue();
                //console.log(kd_supplier_akt);
                            Ext.Ajax.request({
                                url: '<?= site_url("supplier_per_barang/update_aktif") ?>',
                                method: 'POST',
                                params: {
                                    kd_supplier_akt: kd_suppier_akt,
                                      aktif: aktif,
                                      kd_barang :kd_barang
                                },
                                callback:function(opt,success,responseObj){
                                    var de = Ext.util.JSON.decode(responseObj.responseText);
                                    if(de.success==true){
                                      strsupplierperbarang.reload();
                                        strsupplierperbarang.load({
                                            params: {
                                                fieldId: kd_suppier_akt,
                                                start: STARTPAGE,
                                                limit: ENDPAGE
                                            }
                                        });
                                        winaddsupplier_per_barang.hide();
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
            },{
                        text: 'Close',
                        id: 'btnClosesupplier_per_barang',
                        scope: this,
                        handler: function(){
                            winaddsupplier_per_barang.hide();
                        }
                    }]
            }; // eo config object
            // apply config
           Ext.apply(this, Ext.apply(this.initialConfig, config));
            
            // call parent
            supplier_per_barangform.Form.superclass.initComponent.apply(this, arguments);
            
        } // eo function initComponent  
        ,
        onRender: function(){
        
            // call parent
            supplier_per_barangform.Form.superclass.onRender.apply(this, arguments);
            
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
            
            
            strsupplierperbarang.load({
                params:{fieldId:Ext.getCmp('id_kd_supplier_search').getValue()}
            });
            Ext.getCmp('id_formaddsupplier_per_barang').getForm().reset();
            winaddsupplier_per_barang.hide();
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
    Ext.reg('formaddsupplier_per_barang', supplier_per_barangform.Form);
    
    var winaddsupplier_per_barang = new Ext.Window({
        id: 'id_winaddsupplier_per_barang',
        closeAction: 'hide',
        width: 900,
        height: 450,
        layout: 'fit',
        border: false,
        items: {
            id: 'id_formaddsupplier_per_barang',
            xtype: 'formaddsupplier_per_barang'
        },
        onHide: function(){
            Ext.getCmp('id_formaddsupplier_per_barang').getForm().reset();
        }
    });
    
    /* START GRID */    
    // data store
	
    var strsupplier_ = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'kd_supplier',
                'nama_supplier',
                'alias_supplier',
                'pkp',
                'alamat',
                'waktu_top'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("supplier_per_barang/supplier_") ?>',
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
    strsupplier_.on('load', function(){		
        Ext.getCmp('id_kd_supp_add').setValue('');
    })
    var strsupplierperbarang = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'kd_supplier',
                'nama_supplier',
                'kd_produk',
                'kd_produk_lama',
                'nama_produk',
                'pkp',
                'waktu_top',
                'disk_persen_supp1',
                'disk_persen_supp2',
                'disk_persen_supp3',
                'disk_persen_supp4',
                'disk_amt_supp1',
                'disk_amt_supp2',
                'disk_amt_supp3',
                'disk_amt_supp4',
                'disk_amt_supp5',
                'hrg_supplier',
                'net_hrg_supplier_sup',
                'net_hrg_supplier_dist',
                'dpp',
                'aktif'

            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({			
            url: '<?= site_url("supplier_per_barang/get_rows") ?>',
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
	
    strsupplierperbarang.on('load', function(){
        strsupplierperbarang.proxy.conn.url = '<?= site_url("supplier_per_barang/get_rows") ?>/' + Ext.getCmp('id_kd_supplier_search').getValue();
    })
	
    // search field
    var searchsupplier_per_barang_supplier = new Ext.app.SearchField({
        store: strsupplier_,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        id: 'idsearchsupplier_per_barang_supplier'
    }); 
	
    var searchsupplier_per_barang = new Ext.app.SearchField({
        store: strsupplierperbarang,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        id: 'idsearchsupplier_per_barang'
    });
    
    // top toolbar
    var tbsupplier_per_barang_supplier = new Ext.Toolbar({
        items: [searchsupplier_per_barang_supplier]
    });
	
    var searchField = new Ext.app.SearchField({
        width: 220,
        id: 'search_query',
        store: strsupplierperbarang
    });
	
    searchField.onTrigger1Click = function(evt) {
        if (this.hasSearch) {
            this.el.dom.value = '';
			
            // Get the value of search field
            var fid = Ext.getCmp('id_kd_supplier_search').getValue();
            var o = { start: 0, fieldId: fid };
			
            this.store.baseParams = this.store.baseParams || {};
            this.store.baseParams[this.paramName] = '';
            this.store.reload({
                params : o
            });
            this.triggers[0].hide();
            this.hasSearch = false;
        }
    };
	
    searchField.onTrigger2Click = function(evt) {
        var text = this.getRawValue();
        if (text.length < 1) {
            this.onTrigger1Click();
            return;
        }
	 
        // Get the value of search field
        var fid = Ext.getCmp('id_kd_supplier_search').getValue();
        var o = { start: 0, fieldId: fid };
	 
        this.store.baseParams = this.store.baseParams || {};
        this.store.baseParams[this.paramName] = text;
        this.store.reload({params:o});
        this.hasSearch = true;
        this.triggers[0].show();
    };
	
    var tbsupplier_per_barang = new Ext.Toolbar({
        items: [{
                text: 'Add',
                icon: BASE_ICONS + 'add.png',
                onClick: function(){
                    str_produkadd_spb.removeAll();
                    var kd_supp_add = Ext.getCmp('id_kd_supp_add').getValue();
                    if(kd_supp_add == ''){
                        Ext.Msg.show({
                            title: 'Warning',
                            msg: 'Silahkan Klik Data Supplier Terlebih Dahulu',
                            modal: true,
                            icon: Ext.Msg.WARNING,
                            buttons: Ext.Msg.OK
                        });
                        return;
                    }
                    strcbkdprodukspb.reload();
                    Ext.getCmp('id_action').setValue('Save');
                    
                    var sm = grid.getSelectionModel();                
                    var sel = sm.getSelections(); 
                    Ext.getCmp('id_kd_supp').setValue(sel[0].get('kd_supplier'));
                    Ext.getCmp('id_kd_supplier_search').setValue(sel[0].get('kd_supplier'));
                    Ext.getCmp('id_nama_supp').setValue(sel[0].get('nama_supplier'));
                    Ext.getCmp('id_waktu_top').setValue(sel[0].get('waktu_top'));
                    Ext.getCmp('spb_pkp').setValue(sel[0].get('pkp'));
                    Ext.getCmp('id_kd_supp_add').setValue(sel[0].get('kd_supplier'));
                    Ext.getCmp('id_nama_supp_add').setValue(sel[0].get('nama_supplier'));
                    Ext.getCmp('id_waktu_top_add').setValue(sel[0].get('waktu_top'));
                    Ext.getCmp('spb_pkp_add').setValue(sel[0].get('pkp'));
                    
                    //Ext.getCmp('btnresetsupplier_per_barang').show();
                    // Ext.getCmp('btnsubmitsupplier_per_barang').setText('Submit');
                    winaddsupplier_per_barang_.setTitle('Add Form');
                    winaddsupplier_per_barang_.show();
                }
            }, '-',searchField,'->', 
            {
                xtype: 'textfield',
                text: 'Kode Supplier',
                hidden: true,
                name: 'kd_supplier_search',
                id: 'id_kd_supplier_search',     
            }]
    });
    
    // checkbox grid
    var cbGrid = new Ext.grid.CheckboxSelectionModel();
    var smGrid = new Ext.grid.CheckboxSelectionModel();
    
    // row actions
    var actionsupplier_per_barang = new Ext.ux.grid.RowActions({
        header :'View',
        autoWidth: false,
        locked: true,
        width: 50,
        actions:[{iconCls: 'icon-edit-record', qtip: 'Edit'}],
        widthIntercept: Ext.isSafari ? 4 : 2
    }); 
    var actionsupplier_per_barangdel = new Ext.ux.grid.RowActions({
        header: 'Delete',
        autoWidth: false,
        width: 40,
        actions:[{iconCls: 'icon-edit-record', qtip: 'Delete'}],
        widthIntercept: Ext.isSafari ? 4 : 2
    }); 
    
    actionsupplier_per_barang.on('action', function(grid, record, action, row, col) {
        var kd_supp = record.get('kd_supplier');
        var kd_prod = record.get('kd_produk');
        var nm_prod = record.get('nama_produk');
        var aktif = record.get('aktif');
        switch(action) {
            case 'icon-edit-record':                
                editsupplier_per_barang(kd_supp,kd_prod,nm_prod,aktif);
                break;
            case 'icon-delete-record':
                Ext.Msg.show({
                    title: 'Confirm',
                    msg: 'Are you sure delete selected row ?',
                    buttons: Ext.Msg.YESNO,
                    fn: function(btn){
                        if (btn == 'yes') {
                            Ext.Ajax.request({
                                url: '<?= site_url("supplier_per_barang/delete_rows") ?>',
                                method: 'POST',
                                params: {
                                    postdata: kd_supp + '-' + kd_prod
                                },
                                callback:function(opt,success,responseObj){
                                    var de = Ext.util.JSON.decode(responseObj.responseText);
                                    if(de.success==true){
                                        strsupplierperbarang.reload();
                                        strsupplierperbarang.load({
                                            params: {
                                                fieldId: kd_supp,
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
	
        var grid = new Ext.grid.EditorGridPanel({
            id: 'grid',
            frame: true,
            border: true,
            stripeRows: true,
            sm: cbGrid,
            store: strsupplier_,
            loadMask: true,
            title: 'Supplier',
            style: 'margin:0 auto;',
            height: 200,
            // width: 550,
            columns: [{
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
                    header: "Alias",
                    dataIndex: 'alias_supplier',
                    sortable: true,
                    width: 150
                },{
                    header: "Status PKP",
                    dataIndex: 'pkp',
                    sortable: true,
                    width: 70
                },{
                    header: "Alamat",
                    dataIndex: 'alamat',
                    sortable: true,
                    width: 250
                }],
            listeners: {
                'rowclick': function(){              
                    var sm = grid.getSelectionModel();                
                    var sel = sm.getSelections(); 				
                    
                    Ext.getCmp('id_kd_supp').setValue(sel[0].get('kd_supplier'));
                    Ext.getCmp('id_kd_supplier_search').setValue(sel[0].get('kd_supplier'));
                    Ext.getCmp('id_nama_supp').setValue(sel[0].get('nama_supplier'));
                    Ext.getCmp('id_waktu_top').setValue(sel[0].get('waktu_top'));
                    Ext.getCmp('spb_pkp').setValue(sel[0].get('pkp'));
                    Ext.getCmp('id_cekaktif').setValue(sel[0].get('aktif'));
                    Ext.getCmp('id_kd_supp_add').setValue(sel[0].get('kd_supplier'));
                    Ext.getCmp('id_nama_supp_add').setValue(sel[0].get('nama_supplier'));
                    Ext.getCmp('id_waktu_top_add').setValue(sel[0].get('waktu_top'));
                    Ext.getCmp('spb_pkp_add').setValue(sel[0].get('pkp'));
                    
                    grid1.store.proxy.conn.url = '<?= site_url("supplier_per_barang/get_rows") ?>/' + sel[0].get('kd_supplier');
                    grid1.store.reload();
                
                }          
            },
            tbar: tbsupplier_per_barang_supplier,
            bbar: new Ext.PagingToolbar({
                pageSize: ENDPAGE,
                store: strsupplier_,
                displayInfo: true
            })
        });
	
        var grid1 = new Ext.grid.EditorGridPanel({
            id: 'grid1',
            frame: true,
            border: true,
            sm: smGrid,
            stripeRows: true,
            store: strsupplierperbarang,
            loadMask: true,
            style: 'margin:0 auto;',
            height: 250,
            view: new Ext.ux.grid.LockingGridView(),
            colModel: new Ext.ux.grid.LockingColumnModel([{
                    dataIndex: 'kd_supplier',
                    hidden: true
                },{
                    dataIndex: 'pkp',
                    hidden: true
                },actionsupplier_per_barang,{
                    dataIndex: 'aktif',
                    header: 'Aktif',
                    locked: true,
                    sortable: true,
                    width: 60
                },{
                    dataIndex: 'kd_produk',
                    header: 'Kode Produk',
                    locked: true,
                    sortable: true,
                    width: 100
                },{
                    dataIndex: 'kd_produk_lama',
                    header: 'Kode Produk Lama',
                    locked: true,
                    sortable: true,
                    width: 110
                },{
                    header: "Nama Barang",
                    dataIndex: 'nama_produk',
                    sortable: true,
                    locked: true,
                    width: 300
                },{
                    header: "Waktu TOP",
                    dataIndex: 'waktu_top',
                    sortable: true,
                    width: 110
                },{
                    header: "DPP",
                    dataIndex: 'dpp',
                    sortable: true,
                    width: 110
                },{
                    header: "Harga Supp",
                    dataIndex: 'hrg_supplier',
                    sortable: true,
                    width: 110
                },{
                    header: "Harga Supp Dist",
                    dataIndex: 'net_hrg_supplier_dist',
                    sortable: true,
                    width: 110
                },{
                    header: "Harga Supp Sup",
                    dataIndex: 'net_hrg_supplier_sup',
                    sortable: true,
                    width: 110
                },{
                    header: "Disc Supplier1 (%)",
                    dataIndex: 'disk_persen_supp1',
                    sortable: true,
                    width: 110
                },{
                    header: "Disc Supplier1 (Rp)",
                    dataIndex: 'disk_amt_supp1',
                    sortable: true,
                    width: 110
                },{
                    header: "Disc Supplier2 (%)",
                    dataIndex: 'disk_persen_supp2',
                    sortable: true,
                    width: 110
                },{
                    header: "Disc Supplier2 (Rp)",
                    dataIndex: 'disk_amt_supp2',
                    sortable: true,
                    width: 110
                },{
                    header: "Disc Supplier3 (%)",
                    dataIndex: 'disk_persen_supp3',
                    sortable: true,
                    width: 110
                },{
                    header: "Disc Supplier3 (Rp)",
                    dataIndex: 'disk_amt_supp3',
                    sortable: true,
                    width: 110
                },{
                    header: "Disc Supplier4 (%)",
                    dataIndex: 'disk_persen_supp4',
                    sortable: true,
                    width: 110
                },{
                    header: "Disc Supplier4 (Rp)",
                    dataIndex: 'disk_amt_supp4',
                    sortable: true,
                    width: 110
                },{
                    header: "Disc Supplier5 (Rp)",
                    dataIndex: 'disk_amt_supp5',
                    sortable: true,
                    width: 110
                }]),
            plugins: [actionsupplier_per_barang],
            listeners: {
                'rowdblclick': function(){              
                    var sm = grid1.getSelectionModel();                
                    var sel = sm.getSelections();                
                    if (sel.length > 0) {
                        editsupplier_per_barang(sel[0].get('kd_supplier'),sel[0].get('kd_produk'),sel[0].get('nama_produk'),sel[0].get('pkp'),sel[0].get('hrg_supplier'));
                    }                 
                }          
            },
            tbar: tbsupplier_per_barang,
            bbar: new Ext.PagingToolbar({
                pageSize: ENDPAGE,
                store: strsupplierperbarang,
                displayInfo: true
            })
        });
    
	
        var supplierperbarang = new Ext.FormPanel({
            id: 'supplierperbarang',
            border: false,
            frame: true,
            autoScroll:true,		
            bodyStyle:'padding:5px;',
            items: [grid,grid1]
        });
        // grid
   
        function editsupplier_per_barang(kd_supp,kd_prod,nm_prod,pkp,harga,aktif){
            // strcbkdprodukspb.load();
            Ext.getCmp('id_action').setValue('Update');
            //Ext.getCmp('btnresetsupplier_per_barang').hide();
            //Ext.getCmp('btnsubmitsupplier_per_barang').setText('Update');
            winaddsupplier_per_barang.setTitle('View Data Form');
            if (pkp != 0){		
                var dpp = harga/1.1;
                Ext.getCmp('id_spb_dpp').setValue(dpp);
            }else{	
                Ext.getCmp('id_spb_dpp').setValue(harga);
            }
            Ext.getCmp('id_formaddsupplier_per_barang').getForm().load({
                url: '<?= site_url("supplier_per_barang/get_row") ?>',
                params: {
                    id: kd_supp,
                    id1: kd_prod,
                    id2: aktif,
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
            winaddsupplier_per_barang.show();
        }
    
</script>
