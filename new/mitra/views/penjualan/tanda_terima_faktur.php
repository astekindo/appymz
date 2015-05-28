?<php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">
    // Start Combo Pelanggan
    var strcbttfpelanggan = new Ext.data.ArrayStore({
        fields: ['nama_pelanggan'],
        data : []
    });
	
    var strgridttfpelanggan = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_pelanggan', 'nama_pelanggan'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("tanda_terima_faktur/search_pelanggan") ?>',
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
	
    strgridttfpelanggan.on('load', function(){
        Ext.getCmp('id_searchgridttfpelanggan').focus();
    });
	
    var searchgridttfpelanggan = new Ext.app.SearchField({
        store: strgridttfpelanggan,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE			
        },
        width: 350,
        id: 'id_searchgridttfpelanggan'
    });
	
	
    var gridttfpelanggan = new Ext.grid.GridPanel({
        store: strgridttfpelanggan,
        stripeRows: true,
        frame: true,
        border:true,
        columns: [{
                header: 'Kode Pelanggan',
                dataIndex: 'kd_pelanggan',
                width: 120,
                sortable: true		
            
            },{
                header: 'Nama Pelanggan',
                dataIndex: 'nama_pelanggan',
                width: 300,
                sortable: true         
            }],
        tbar: new Ext.Toolbar({
            items: [searchgridttfpelanggan]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridttfpelanggan,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function(){			
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {				
                    Ext.getCmp('id_ttfkd_pelanggan').setValue(sel[0].get('kd_pelanggan'));                   
                    Ext.getCmp('id_cbttfpelanggan').setValue(sel[0].get('nama_pelanggan'));
                                       
                    //strfakturpenjualan.removeAll();       
                    menuttfpelanggan.hide();
                   //cleartotalfaktur();
                }
            }
        }
    });
	
    var menuttfpelanggan = new Ext.menu.Menu();
    menuttfpelanggan.add(new Ext.Panel({
        title: 'Pilih Kolektor',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridttfpelanggan],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menuttfpelanggan.hide();
                }
            }]
    }));
    
    Ext.ux.TwinComboTtfPelanggan = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            //load store grid
            strgridttfpelanggan.load();
            menuttfpelanggan.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
	
    menuttfpelanggan.on('hide', function(){
        var sf = Ext.getCmp('id_searchgridttfpelanggan').getValue();
        if( sf != ''){
            Ext.getCmp('id_searchgridttfpelanggan').setValue('');
            searchgridttfpelanggan.onTrigger2Click();
        }
    });
	
    var cbttfpelanggan = new Ext.ux.TwinComboTtfPelanggan({
        fieldLabel: 'Pelanggan <span class="asterix">*</span>',
        id: 'id_cbttfpelanggan',
        store: strcbttfpelanggan,
        mode: 'local',
        valueField: 'nama_pelanggan',
        displayField: 'nama_pelanggan',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: true,
        editable: false,
        anchor: '90%',
        hiddenName: 'nama_pelanggan',
        emptyText: 'Pilih Pelanggan'
    });
    //End Combo Pelanggan
    
    // Header Tanda Terima Faktur
    var header_ttf = {
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
                        fieldLabel: 'No TTF',
                        name: 'no_ttf',
                        readOnly:true,
                        fieldClass:'readonly-input',
                        id: 'id_no_ttf',                
                        anchor: '90%',
                        value:''
                    },cbttfpelanggan,{
                        xtype: 'hidden',
                        fieldLabel: 'kd pelanggan',
                        name: 'kd_pelanggan',
                        readOnly:true,
                        fieldClass:'readonly-input',
                        id: 'id_ttfkd_pelanggan',                
                        anchor: '90%',
                        value:''
                    },{
                        xtype: 'textfield',
                        fieldLabel: 'Di Serahkan <span class="asterix">*</span>',
                        name: 'diserahkan',
                        readOnly:false,
                        id: 'ttf_diserahkan',                
                        anchor: '90%',
                        allowBlank:true,
                        value:''
                    },{
                        xtype: 'textfield',
                        fieldLabel: 'Di Terima <span class="asterix">*</span>',
                        name: 'diterima',
                        readOnly:false,
                        id: 'ttf_diterima',                
                        anchor: '90%',
                        allowBlank:true,
                        value:''
                    }
                    ]
            },{
                columnWidth: .5,
                layout: 'form',
                border: false,
                labelWidth: 120,
                defaults: { labelSeparator: ''},
                items: [{
                        xtype: 'datefield',
                        fieldLabel: 'Tanggal TTF',
                        name: 'tgl_ttf',
                        id: 'id_tgl_ttf', 
                        format: 'd-m-Y',
                        emptyText: 'Tanggal TTF',
                        value: new Date(), 
                        maxValue: (new Date()).clearTime() ,   
                        editable: false,           
                        anchor: '90%'
                    },{
                        xtype: 'textfield',
                        fieldLabel: 'Keterangan <span class="asterix">*</span>',
                        name: 'keterangan',
                        readOnly:false,
                        id: 'ttf_keterangan',                
                        anchor: '90%',
                        allowBlank:true,
                        value:''
                    },{
                        xtype: 'datefield',
                        fieldLabel: 'Tanggal Diserahkan',
                        name: 'tgl_diserahkan',
                        id: 'id_tgl_diserahkan', 
                        format: 'd-m-Y',
                        emptyText: 'Tanggal Diserahkan',
                        value: new Date(), 
                        maxValue: (new Date()).clearTime() ,   
                        editable: false,           
                        anchor: '90%'
                    },{
                        xtype: 'datefield',
                        fieldLabel: 'Tanggal Diterima',
                        name: 'tgl_diterima',
                        id: 'id_tgl_diterima', 
                        format: 'd-m-Y',
                        emptyText: 'Tanggal Diterima',
                        value: new Date(), 
                        maxValue: (new Date()).clearTime() ,   
                        editable: false,           
                        anchor: '90%'
                    }
                    ]
            }]
    };
    //end header
    //Twin No Faktur
  var strcbttfnofaktur = new Ext.data.ArrayStore({
        fields: ['no_faktur'],
        data: []
    });

    var strgridttfnofaktur = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                {name: 'no_faktur', allowBlank: false, type: 'text'},
                {name: 'tgl_faktur', allowBlank: false, type: 'text'},
                {name: 'tgl_jatuh_tempo', allowBlank: false, type: 'text'},
                {name: 'rp_faktur', allowBlank: false, type: 'int'},
                {name: 'rp_uang_muka', allowBlank: false, type: 'int'},
                {name: 'rp_cash_diskon', allowBlank: false, type: 'int'},
                {name: 'rp_potongan', allowBlank: false, type: 'int'},
                {name: 'rp_faktur_net', allowBlank: false, type: 'int'},
                {name: 'rp_ppn', allowBlank: false, type: 'int'},
                {name: 'rp_dpp', allowBlank: false, type: 'int'},
                {name: 'rp_kurang_bayar', allowBlank: false, type: 'int'},
                {name: 'rp_total_faktur', allowBlank: false, type: 'int'}
        ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("tanda_terima_faktur/search_no_faktur") ?>',
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

    strgridttfnofaktur.on('load', function() {
        Ext.getCmp('search_query_nofaktur').focus();
    });

    var searchfieldttfnofaktur = new Ext.app.SearchField({
        width: 220,
        id: 'search_query_nofaktur',
        store: strgridttfnofaktur
    });



    // top toolbar
    var tbsearchfieldttfnofaktur = new Ext.Toolbar({
        items: [searchfieldttfnofaktur]
    });

    var gridttfnofaktur = new Ext.grid.GridPanel({
        store: strgridttfnofaktur,
        stripeRows: true,
        frame: true,
        border: true,
        columns: [{
                header: 'No Faktur',
                dataIndex: 'no_faktur',
                width: 100,
                sortable: true
            }, {
                header: 'Tgl Faktur',
                dataIndex: 'tgl_faktur',
                width: 100,
                sortable: true
            },{
                header: 'Rp Faktur',
                dataIndex: 'rp_faktur',
                width: 100,
                sortable: true
            },{
                header: 'Tgl Jatuh Tempo',
                dataIndex: 'tgl_jatuh_tempo',
                width: 100,
                sortable: true
            }],
        tbar: tbsearchfieldttfnofaktur,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridttfnofaktur,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                     
                     Ext.getCmp('ttf_no_faktur').setValue(sel[0].get('no_faktur'));                   
                     Ext.getCmp('ttf_tgl_faktur').setValue(sel[0].get('tgl_faktur'));
                     Ext.getCmp('ttf_rp_faktur').setValue(sel[0].get('rp_faktur'));
                     Ext.getCmp('ttf_tgl_jatuh_tempo').setValue(sel[0].get('tgl_jatuh_tempo'));
                     Ext.getCmp('ttf_rp_uang_muka').setValue(sel[0].get('rp_uang_muka'));
                     Ext.getCmp('ttf_rp_cash_diskon').setValue(sel[0].get('rp_cash_diskon'));
                     Ext.getCmp('ttf_rp_potongan').setValue(sel[0].get('rp_potongan'));
                     Ext.getCmp('ttf_rp_faktur_net').setValue(sel[0].get('rp_faktur_net'));
                     Ext.getCmp('ttf_rp_dpp').setValue(sel[0].get('rp_dpp'));
                     Ext.getCmp('ttf_rp_ppn').setValue(sel[0].get('rp_ppn'));
                     Ext.getCmp('ttf_rp_total_faktur').setValue(sel[0].get('rp_total_faktur'));
                     Ext.getCmp('ttf_rp_kurang_bayar').setValue(sel[0].get('rp_kurang_bayar'));
                     menuttfnofaktur.hide();                  
                }
            }
        }
    });

    var menuttfnofaktur = new Ext.menu.Menu();
    menuttfnofaktur.add(new Ext.Panel({
        title: 'Pilih No Faktur',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 630,
        height: 250,
        closeAction: 'hide',
        plain: true,
        items: [gridttfnofaktur],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menuttfnofaktur.hide();
                }
            }]
    }));

    menuttfnofaktur.on('hide', function() {
        var sf = Ext.getCmp('search_query_nofaktur').getValue();
        if (sf !== '') {
            Ext.getCmp('search_query_nofaktur').setValue('');
            searchfieldttfnofaktur.onTrigger2Click();
        }
    });


    Ext.ux.TwinComboNoFaktur = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            //load store grid
            strgridttfnofaktur.load({
                params: {
                    kd_pelanggan: Ext.getCmp('id_ttfkd_pelanggan').getValue()                                 
                }
            });
            menuttfnofaktur.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
    //End Twin No Faktur
    
    //Grid Panel    
var strttf = new Ext.data.Store({
        autoSave: false,
        reader: new Ext.data.JsonReader({
            fields: [
                {name: 'kd_pelanggan', allowBlank: false, type: 'text'},
                {name: 'nama_pelanggan', allowBlank: false, type: 'text'},
                {name: 'rp_faktur', allowBlank: false, type: 'int'},
                {name: 'no_faktur', allowBlank: false, type: 'text'},
                {name: 'tgl_faktur', allowBlank: false, type: 'text'},
                {name: 'tgl_jatuh_tempo', allowBlank: false, type: 'text'}
                 ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("tanda_terima_faktur/search_no_faktur") ?>',
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
    
    
    strttf.on('update', function(){
        var grand_total = 0;
        strttf.each(function(node){			
            grand_total += parseInt(node.data.rp_kurang_bayar);
            
        });
      console.log(grand_total);
      Ext.getCmp('ttf_rp_total').setValue(grand_total);
       
    });
    
var editorgridttf = new Ext.ux.grid.RowEditor({
        saveText: 'Update'
    });

var gridttf = new Ext.grid.GridPanel({
        store: strttf,
        stripeRows: true,
        height: 250,
        frame: true,
        border:true,
        plugins: [editorgridttf],
        tbar: [{
                icon: BASE_ICONS + 'add.png',
                text: 'Add',
                handler: function() {
                    
                    var rowttf = new gridttf.store.recordType({
//                        kd_produk: '',
//                        qty: '0'
                    });
                    editorgridttf.stopEditing();
                    strttf.insert(0, rowttf);
                    gridttf.getView().refresh();
                    gridttf.getSelectionModel().selectRow(0);
                    editorgridttf.startEditing(0);
                }
            }, {
                ref: '../removeBtn',
                icon: BASE_ICONS + 'delete.gif',
                text: 'Remove',
                disabled: false,
                handler: function() {
                    editorgridttf.stopEditing();
                    var s = gridttf.getSelectionModel().getSelections();
                    for (var i = 0, r; r = s[i]; i++) {
                        strttf.remove(r);
                    }
                    var grand_total = 0;
                    strttf.each(function(node){			
                        grand_total += parseInt(node.data.rp_kurang_bayar);

                    });
                    Ext.getCmp('ttf_rp_total').setValue(grand_total);
                }
            }],
        columns: [
                {
                header: 'No Faktur',
                dataIndex: 'no_faktur',
                width: 140,
                sortable: true,
                format: '0,0',
                editor: new Ext.ux.TwinComboNoFaktur({
                    id: 'ttf_no_faktur',
                    store: strcbttfnofaktur,
                    mode: 'local',
                    valueField: 'no_faktur',
                    displayField: 'no_faktur',
                    typeAhead: true,
                    triggerAction: 'all',
                    //allowBlank: false,
                    editable: false,
                    hiddenName: 'no_faktur',
                    emptyText: 'Pilih No Faktur'

                })
             },{
                header: 'Tgl Faktur',
                dataIndex: 'tgl_faktur',
                width: 120,
                format: '0,0',
                editor: new Ext.form.TextField({
                    readOnly: true,
                    fieldClass: 'readonly-input',
                    id: 'ttf_tgl_faktur'
                })
            },{
                header: 'Tgl Jth Tempo',
                dataIndex: 'tgl_jatuh_tempo',
                width: 100,
                format: '0,0',
                editor: new Ext.form.TextField({
                    readOnly: true,
                    fieldClass: 'readonly-input',
                    id: 'ttf_tgl_jatuh_tempo'
                })
            },{xtype: 'numbercolumn',
                header: 'Rp Faktur',
                dataIndex: 'rp_faktur',
                width: 100,
                format: '0,0',
                editor: new Ext.form.TextField({
                    readOnly: true,
                    fieldClass: 'readonly-input',
                    id: 'ttf_rp_faktur'
                })
            },{xtype: 'numbercolumn',
                header: 'Rp Uang Muka',
                dataIndex: 'rp_uang_muka',
                width: 100,
                format: '0,0',
                editor: new Ext.form.TextField({
                    readOnly: true,
                    fieldClass: 'readonly-input',
                    id: 'ttf_rp_uang_muka'
                })
            },{xtype: 'numbercolumn',
                header: 'Rp Cash Diskon',
                dataIndex: 'rp_cash_diskon',
                width: 100,
                format: '0,0',
                editor: new Ext.form.TextField({
                    readOnly: true,
                    fieldClass: 'readonly-input',
                    id: 'ttf_rp_cash_diskon'
                })
            },{xtype: 'numbercolumn',
                header: 'Rp Potongan',
                dataIndex: 'rp_potongan',
                width: 100,
                format: '0,0',
                editor: new Ext.form.TextField({
                    readOnly: true,
                    fieldClass: 'readonly-input',
                    id: 'ttf_rp_potongan'
                })
            },{xtype: 'numbercolumn',
                header: 'Rp Faktur Net',
                dataIndex: 'rp_faktur_net',
                width: 100,
                format: '0,0',
                editor: new Ext.form.TextField({
                    readOnly: true,
                    fieldClass: 'readonly-input',
                    id: 'ttf_rp_faktur_net'
                })
            },{xtype: 'numbercolumn',
                header: 'Rp DPP',
                dataIndex: 'rp_dpp',
                width: 100,
                format: '0,0',
                editor: new Ext.form.TextField({
                    readOnly: true,
                    fieldClass: 'readonly-input',
                    id: 'ttf_rp_dpp'
                })
            },{xtype: 'numbercolumn',
                header: 'Rp PPN',
                dataIndex: 'rp_ppn',
                width: 100,
                format: '0,0',
                editor: new Ext.form.TextField({
                    readOnly: true,
                    fieldClass: 'readonly-input',
                    id: 'ttf_rp_ppn'
                })
            },{xtype: 'numbercolumn',
                header: 'Rp Total Faktur',
                dataIndex: 'rp_total_faktur',
                width: 100,
                format: '0,0',
                editor: new Ext.form.TextField({
                    readOnly: true,
                    fieldClass: 'readonly-input',
                    id: 'ttf_rp_total_faktur'
                })
            },{xtype: 'numbercolumn',
                header: 'Rp Kurang Bayar',
                dataIndex: 'rp_kurang_bayar',
                width: 100,
                format: '0,0',
                editor: new Ext.form.TextField({
                    readOnly: true,
                    fieldClass: 'readonly-input',
                    id: 'ttf_rp_kurang_bayar'
                })
            }]
   });
   
    gridttf.getSelectionModel().on('selectionchange', function(sm){
        gridttfttf.removeBtn.setDisabled(sm.getCount() < 1);
    });
    //End Gridpanel
    var tanda_terima_faktur = new Ext.FormPanel({
        id: 'tanda_terima_faktur',
        border: false,
        frame: true,
        autoScroll:true,        
        bodyStyle:'padding-right:20px;',
        labelWidth: 130,
        items: [{
                bodyStyle: {
                    margin: '0px 0px 15px 0px'
                },                  
                items: [header_ttf]
            },
            gridttf,
            {
                layout: 'column',
                border: false,
                monitorValid: true,
                items: [{
                        columnWidth: .6,
                        style:'margin:6px 3px 0 0;',
                        layout: 'form', 
                        labelWidth: 110
                        
                    },  {
                        columnWidth: .4,
                        layout: 'form',
                        border: false,
                        labelWidth: 110,
                        defaults: {labelSeparator: ''},
                        items: [
                            {
                                xtype: 'fieldset',
                                autoHeight: true,
                                items: [
                                    {
                                        xtype: 'numericfield',
                                        currencySymbol: '',
                                        fieldLabel: 'Total Faktur',
                                        name: 'rp_total_faktur',
                                        readOnly: true,
                                        id: 'ttf_rp_total',
                                        anchor: '95%',
                                        fieldClass: 'readonly-input number',
                                        value: '0'
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
                    
                    var gridttf = new Array();				
                    strttf.each(function(node){
                        gridttf.push(node.data)
                    });
                    Ext.getCmp('tanda_terima_faktur').getForm().submit({
                        url: '<?= site_url("tanda_terima_faktur/update_row") ?>',
                        scope: this,
                        params: {
                            detail: Ext.util.JSON.encode(gridttf)
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
									
                                    winttfprint.show();
                                    Ext.getDom('ttfprint').src = r.printUrl;
                                }
                            });			            
			            
                            clearttf();						
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
                    clearttf();
                }
            }]
    }); 
     var winttfprint = new Ext.Window({
        id: 'id_winttfprint',
        title: 'Print ttf',
        closeAction: 'hide',
        width: 900,
        height: 450,
        layout: 'fit',
        border: false,
        html:'<iframe style="width:100%;height:100%;" id="ttfprint" src=""></iframe>'
    });
    function clearttf(){
        Ext.getCmp('tanda_terima_faktur').getForm().reset();
        Ext.getCmp('ttf_rp_total').setValue(0);
        Ext.getCmp('tanda_terima_faktur').getForm().load({
            
            success: function(form, action){
               
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
        strttf.removeAll();
    }
</script>
