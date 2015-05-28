<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript"> 
    var strcbpcrasuplier = new Ext.data.ArrayStore({
        fields: ['nama_supplier'],
        data : []
    });
	
    var strgridpcrasuplier = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_supplier', 'nama_supplier'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("pembelian_create_request_asset/search_supplier") ?>',
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
	
    var searchgridpcrasuplier = new Ext.app.SearchField({
        store: strgridpcrasuplier,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE			
        },
        width: 350,
        id: 'id_searchgridpcrasuplier'
    });
	
	
    var gridpcrasuplier = new Ext.grid.GridPanel({
        store: strgridpcrasuplier,
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
            items: [searchgridpcrasuplier]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridpcrasuplier,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function(){			
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {				
                    Ext.getCmp('pcra_kd_supplier').setValue(sel[0].get('kd_supplier'));
                    Ext.getCmp('id_cbpcrasuplier').setValue(sel[0].get('nama_supplier'));
                    strpembeliancreaterequestasset.removeAll();       
                    menupcrasuplier.hide();
                }
            }
        }
    });
	
    var menupcrasuplier = new Ext.menu.Menu();
    menupcrasuplier.add(new Ext.Panel({
        title: 'Pilih Supplier',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridpcrasuplier],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menupcrasuplier.hide();
                }
            }]
    }));
    
    Ext.ux.TwinComboSuplier = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            //load store grid
            strgridpcrasuplier.load();
            menupcrasuplier.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
	
    menupcrasuplier.on('hide', function(){
        var sf = Ext.getCmp('id_searchgridpcrasuplier').getValue();
        if( sf != ''){
            Ext.getCmp('id_searchgridpcrasuplier').setValue('');
            searchgridpcrasuplier.onTrigger2Click();
        }
    });
	
    var cbpcrasuplier = new Ext.ux.TwinComboSuplier({
        fieldLabel: 'Supplier <span class="asterix">*</span>',
        id: 'id_cbpcrasuplier',
        store: strcbpcrasuplier,
        mode: 'local',
        valueField: 'nama_supplier',
        displayField: 'nama_supplier',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'nama_supplier',
        emptyText: 'Pilih Supplier'
    });
	   
    var headerpembeliancreaterequestasset = {
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
                        fieldLabel: 'No PR',
                        name: 'no_ro',
                        readOnly:true,
                        fieldClass:'readonly-input',
                        id: 'pcra_no_ro',
                        maxLength: 255,
                        anchor: '90%',
                        value:''
                    },{
                        xtype: 'hidden',
                        name: 'kd_supplier',
                        id: 'pcra_kd_supplier',
                        value: ''
                    },cbpcrasuplier]
            }, {
                columnWidth: .5,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: { labelSeparator: ''},
                items: [ {
                        xtype: 'datefield',
                        format:'d-m-Y',
                        fieldLabel: 'Tanggal',
                        name: 'tgl_ro',
                        //readOnly:true,
                        //fieldClass:'readonly-input',
                        allowBlank: false,
                        id: 'pcra_tgl_ro',
                        maxLength: 255,
                        anchor: '90%',
                        maxValue: (new Date()).clearTime(),
                        value: ''
                    },{
                        xtype: 'textfield',
                        fieldLabel: 'Subject <span class="asterix">*</span>',
                        name: 'subject',
                        allowBlank: false,
                        id: 'pcra_subject',
                        maxLength: 255,
                        anchor: '90%'
                    },]
            }]
    }
	
    var strcbpcraproduk = new Ext.data.ArrayStore({
        fields: ['kd_produk'],
        data : []
    });
	
	
    var strgridpcraproduk = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_produk','nama_produk','min_stok','max_stok','jml_stok','nm_satuan'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("pembelian_create_request_asset/search_produk_by_supplier") ?>',
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
	
    var searchgridpcraproduk = new Ext.app.SearchField({
        store: strgridpcraproduk,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE			
        },
        width: 350,
        id: 'id_searchgridpcraproduk'
    });
	
    searchgridpcraproduk.onTrigger1Click = function(evt) {
        if (this.hasSearch) {
            this.el.dom.value = '';
			
            // Get the value of search field
            var fid = Ext.getCmp('pcra_kd_supplier').getValue();
            var o = { start: 0, kd_supplier: fid };
			
            this.store.baseParams = this.store.baseParams || {};
            this.store.baseParams[this.paramName] = '';
            this.store.reload({
                params : o
            });
            this.triggers[0].hide();
            this.hasSearch = false;
        }
    };
	
    searchgridpcraproduk.onTrigger2Click = function(evt) {
        var text = this.getRawValue();
        if (text.length < 1) {
            this.onTrigger1Click();
            return;
        }
	 
        // Get the value of search field
        var fid = Ext.getCmp('pcra_kd_supplier').getValue();
        var o = { start: 0, kd_supplier: fid };
	 
        this.store.baseParams = this.store.baseParams || {};
        this.store.baseParams[this.paramName] = text;
        this.store.reload({params:o});
        this.hasSearch = true;
        this.triggers[0].show();
    };
	
    var gridpcraproduk = new Ext.grid.GridPanel({
        store: strgridpcraproduk,
        stripeRows: true,
        frame: true,
        border:true,
        columns: [{
                header: 'Kode produk',
                dataIndex: 'kd_produk',
                width: 100,
                sortable: true,		
            },{
                header: 'Nama produk',
                dataIndex: 'nama_produk',
                width: 400,
                sortable: true,         
            },{
                header: 'Satuan',
                dataIndex: 'nm_satuan',
                width: 80,			         
            },{
                header: 'Min.Stok',
                dataIndex: 'min_stok',
                width: 80,
                sortable: true,         
            },{
                header: 'Max.Stok',
                dataIndex: 'max_stok',
                width: 80,
                sortable: true,         
            },{
                header: 'Jml.Stok',
                dataIndex: 'jml_stok',
                width: 80,
                sortable: true,         
            }],
        tbar: new Ext.Toolbar({
            items: [searchgridpcraproduk]
        }),
        // bbar: new Ext.PagingToolbar({
        // pageSize: ENDPAGE,
        // store: strgridpcraproduk,
        // displayInfo: true
        // }),
        listeners: {
            'rowdblclick': function(){			
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {				
                    Ext.getCmp('epcra_kd_produk').setValue(sel[0].get('kd_produk'));
                    Ext.getCmp('epcra_nama_produk').setValue(sel[0].get('nama_produk'));
                    Ext.getCmp('epcra_min_stok').setValue(sel[0].get('min_stok'));
                    Ext.getCmp('epcra_max_stok').setValue(sel[0].get('max_stok'));
                    Ext.getCmp('epcra_jml_stok').setValue(sel[0].get('jml_stok'));   
                    Ext.getCmp('epcra_satuan').setValue(sel[0].get('nm_satuan'));     
                    Ext.getCmp('epcra_qty').setValue(0);
                    Ext.getCmp('epcra_qty').focus();
                    menupcraproduk.hide();
                }
            }
        }
    });
	
    var menupcraproduk = new Ext.menu.Menu();
    menupcraproduk.add(new Ext.Panel({
        title: 'Pilih Barang',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 600,
        height: 250,
        closeAction: 'hide',
        plain: true,
        items: [gridpcraproduk],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menupcraproduk.hide();
                }
            }]
    }));
    
    Ext.ux.TwinComboProduk = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            //load store grid
            strgridpcraproduk.load({
                params: {
                    kd_supplier: Ext.getCmp('pcra_kd_supplier').getValue()                                 
                }
            });
            menupcraproduk.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
	
    menupcraproduk.on('hide', function(){
        var sf = Ext.getCmp('id_searchgridpcraproduk').getValue();
        if( sf != ''){
            Ext.getCmp('id_searchgridpcraproduk').setValue('');
            searchgridpcraproduk.onTrigger2Click();
        }
    });

    var strpembeliancreaterequestasset = new Ext.data.Store({
        autoSave:false,
        reader: new Ext.data.JsonReader({
            fields: [
                {name: 'kd_produk', allowBlank: false, type: 'int'},
                {name: 'qty', allowBlank: false, type: 'int'}
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

    var editorpembeliancreaterequestasset = new Ext.ux.grid.RowEditor({
        saveText: 'Update'
    });
    
	
    var gridpembeliancreaterequestasset = new Ext.grid.GridPanel({
        store: strpembeliancreaterequestasset,
        stripeRows: true,
        height: 300,
        frame: true,
        border:true,
        plugins: [editorpembeliancreaterequestasset],
        tbar: [{
                icon: BASE_ICONS + 'add.png',
                text: 'Add',
                handler: function(){
                    if(Ext.getCmp('pcra_kd_supplier').getValue() == ''){
                        Ext.Msg.show({
                            title: 'Error',
                            msg: 'Silahkan pilih supplier terlebih dulu',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK			               
                        });
                        return;
                    }
                    var rowpembeliancreaterequestasset = new gridpembeliancreaterequestasset.store.recordType({
                        kd_produk : '',
                        qty: ''
                    });                
                    editorpembeliancreaterequestasset.stopEditing();
                    strpembeliancreaterequestasset.insert(0, rowpembeliancreaterequestasset);
                    gridpembeliancreaterequestasset.getView().refresh();
                    gridpembeliancreaterequestasset.getSelectionModel().selectRow(0);
                    editorpembeliancreaterequestasset.startEditing(0);
                }
            },{
                ref: '../removeBtn',
                icon: BASE_ICONS + 'delete.gif',
                text: 'Remove',
                disabled: true,
                handler: function(){
                    editorpembeliancreaterequestasset.stopEditing();
                    var s = gridpembeliancreaterequestasset.getSelectionModel().getSelections();
                    for(var i = 0, r; r = s[i]; i++){
                        strpembeliancreaterequestasset.remove(r);
                    }
                }
            }],
        columns: [{
                xtype: 'numbercolumn',
                header: 'Kode',
                dataIndex: 'kd_produk',
                width: 200,
                format: '0',
                sortable: true,	
                editor: new Ext.ux.TwinComboProduk({
                    id: 'epcra_kd_produk',
                    store: strcbpcraproduk,
                    mode: 'local',
                    valueField: 'kd_produk',
                    displayField: 'kd_produk',
                    typeAhead: true,
                    triggerAction: 'all',
                    allowBlank: false,
                    editable: false,
                    hiddenName: 'kd_produk',
                    emptyText: 'Pilih Produk'
				
                })		
			
            },{
                header: 'Nama Barang',
                dataIndex: 'nama_produk',
                width: 400,
                editor: new Ext.form.TextField({                
                    readOnly: true,
                    id: 'epcra_nama_produk'
                })
            },{
                xtype: 'numbercolumn',
                header: 'Quantity',
                dataIndex: 'qty',			
                width: 80,
                sortable: true,
                format: '0,0',
                editor: {
                    xtype: 'numberfield',
                    id: 'epcra_qty',
                    allowBlank: false,
                    listeners:{
                        'change': function(){
                            var epcra_max = Ext.getCmp('epcra_max_stok').getValue();
                            var epcra_jml = Ext.getCmp('epcra_jml_stok').getValue();
                            var epcra_qty = this.getValue();
                            var epcra_validasi = epcra_qty+epcra_jml;
                            if(epcra_validasi > epcra_max){
                                Ext.Msg.show({
                                    title: 'Error',
                                    msg: 'Qty + Jml Stok tidak boleh lebih besar dari Max. Stok',
                                    modal: true,
                                    icon: Ext.Msg.ERROR,
                                    buttons: Ext.Msg.OK,
                                    fn: function(btn){
                                        if (btn == 'ok') {
                                            Ext.getCmp('epcra_qty').reset()
                                        }
                                    }                            
                                });
                                return;
                            }
                        }
                    }
                }
            },{
                header: 'Satuan',
                dataIndex: 'satuan',
                width: 90,
                editor: new Ext.form.TextField({                
                    readOnly: true,
                    id: 'epcra_satuan'
                })
            },{
                xtype: 'numbercolumn',
                header: 'Min.Stok',
                dataIndex: 'min_stok',			
                width: 80,            
                format: '0,0',
                editor: {
                    xtype: 'numberfield',
                    id: 'epcra_min_stok',
                    readOnly: true,
                }
            },{
                xtype: 'numbercolumn',
                header: 'Max.Stok',
                dataIndex: 'max_stok',			
                width: 80,            
                format: '0,0',
                editor: {
                    xtype: 'numberfield',
                    id: 'epcra_max_stok',
                    readOnly: true,
                }
            },{
                xtype: 'numbercolumn',
                header: 'Jml.Stok',
                dataIndex: 'jml_stok',			
                width: 80,            
                format: '0,0',
                editor: {
                    xtype: 'numberfield',
                    id: 'epcra_jml_stok',
                    readOnly: true,
                }
            },]
    });
	
    gridpembeliancreaterequestasset.getSelectionModel().on('selectionchange', function(sm){
        gridpembeliancreaterequestasset.removeBtn.setDisabled(sm.getCount() < 1);
    });
	
    var pembeliancreaterequestasset = new Ext.FormPanel({
        id: 'pembeliancreaterequestasset',
        border: false,
        frame: true,
        monitorValid: true,
        labelWidth: 130,
        items: [{
                bodyStyle: {
                    margin: '0px 0px 15px 0px'
                },					
                items: [headerpembeliancreaterequestasset]
            }, 
            gridpembeliancreaterequestasset
        ],
        buttons: [{
                text: 'Save',
                formBind:true,
                handler: function(){
                    Ext.Msg.show({
                        title: 'Confirm',
                        msg: 'Apakah anda akan menyimpan data ini ??',
                        buttons: Ext.Msg.YESNO,
                        fn: function(btn){
                            if (btn == 'yes') {

                                var detailpembeliancreaterequestasset = new Array();				
                                strpembeliancreaterequestasset.each(function(node){
                                    detailpembeliancreaterequestasset.push(node.data)
                                });
                                Ext.getCmp('pembeliancreaterequestasset').getForm().submit({
                                    url: '<?= site_url("pembelian_create_request_asset/update_row") ?>',
                                    scope: this,
                                    params: {
                                        detail: Ext.util.JSON.encode(detailpembeliancreaterequestasset)
                                    },
                                    waitMsg: 'Saving Data...',
                                    success: function(form, action){
                                        Ext.Msg.show({
                                            title: 'Success',
                                            msg: 'Form submitted successfully',
                                            modal: true,
                                            icon: Ext.Msg.INFO,
                                            buttons: Ext.Msg.OK
                                        });			            
			            
                                        clearpembeliancreaterequestasset();						
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
                        }
                    });	
			
                }
            },{
                text: 'Reset',
                handler: function(){
                    clearpembeliancreaterequestasset();
                }
            }]
    });
	
    pembeliancreaterequestasset.on('afterrender', function(){
        this.getForm().load({
            url: '<?= site_url("pembelian_create_request_asset/get_form") ?>',
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
    });
	
    function clearpembeliancreaterequestasset(){
        Ext.getCmp('pembeliancreaterequestasset').getForm().reset();
        Ext.getCmp('pembeliancreaterequestasset').getForm().load({
            url: '<?= site_url("pembelian_create_request_asset/get_form") ?>',
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
        strpembeliancreaterequestasset.removeAll();
    }
</script>
