?<php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">
    // Start Combo Colector
    var strcbcolector = new Ext.data.ArrayStore({
        fields: ['nama_collector'],
        data : []
    });
	
    var strgridcolector = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_collector', 'nama_collector'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("bukti_serah_terima_tagihan/search_colector") ?>',
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
	
    strgridcolector.on('load', function(){
        Ext.getCmp('id_searchgridcolector').focus();
    });
	
    var searchgridcolector = new Ext.app.SearchField({
        store: strgridcolector,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE			
        },
        width: 350,
        id: 'id_searchgridcolector'
    });
	
	
    var gridcolector = new Ext.grid.GridPanel({
        store: strgridcolector,
        stripeRows: true,
        frame: true,
        border:true,
        columns: [{
                header: 'Kode Kolektor',
                dataIndex: 'kd_collector',
                width: 120,
                sortable: true		
            
            },{
                header: 'Nama Kolektor',
                dataIndex: 'nama_collector',
                width: 300,
                sortable: true         
            }],
        tbar: new Ext.Toolbar({
            items: [searchgridcolector]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridcolector,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function(){			
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {				
                    Ext.getCmp('id_kdcolector').setValue(sel[0].get('kd_collector'));                   
                    Ext.getCmp('id_cbcolector').setValue(sel[0].get('nama_collector'));
                                       
                    //strfakturpenjualan.removeAll();       
                    menucolector.hide();
                   cleartotalfaktur();
                }
            }
        }
    });
	
    var menucolector = new Ext.menu.Menu();
    menucolector.add(new Ext.Panel({
        title: 'Pilih Kolektor',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridcolector],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menucolector.hide();
                }
            }]
    }));
    
    Ext.ux.TwinComboColector = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            //load store grid
            strgridcolector.load();
            menucolector.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
	
    menucolector.on('hide', function(){
        var sf = Ext.getCmp('id_searchgridcolector').getValue();
        if( sf != ''){
            Ext.getCmp('id_searchgridcolector').setValue('');
            searchgridcolector.onTrigger2Click();
        }
    });
	
    var cbcolector = new Ext.ux.TwinComboColector({
        fieldLabel: 'Kolektor <span class="asterix">*</span>',
        id: 'id_cbcolector',
        store: strcbcolector,
        mode: 'local',
        valueField: 'nama_collector',
        displayField: 'nama_collector',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'nama_collector',
        emptyText: 'Pilih Kolektor'
    });
    //End Combo Colector
    
    // Header Faktur Penjualan
    var header_bstt = {
        layout: 'column',
        border: false,
        items: [{
                columnWidth: .5,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: { labelSeparator: ''},
                items: [{
                        xtype: 'datefield',
                        fieldLabel: 'Tanggal',
                        name: 'tgl_bstt',
                        id: 'id_tgl_bstt', 
                        format: 'd-m-Y',
                        emptyText: 'Tanggal',
                        value: new Date(), 
                        maxValue: (new Date()).clearTime() ,   
                        editable: false,           
                        anchor: '90%'
                    },cbcolector,{
                        xtype: 'hidden',
                        fieldLabel: 'kd collector',
                        name: 'kd_collector',
                        readOnly:true,
                        fieldClass:'readonly-input',
                        id: 'id_kdcolector',                
                        anchor: '90%',
                        value:''
                    }
                    ]
            }]
    };
    //end header
    //Twin No Faktur
  var strcbbsttnofaktur = new Ext.data.ArrayStore({
        fields: ['no_faktur'],
        data: []
    });

    var strgridbsttnofaktur = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                {name: 'no_faktur', allowBlank: false, type: 'text'},
                {name: 'tgl_faktur', allowBlank: false, type: 'text'},
                {name: 'tgl_jatuh_tempo', allowBlank: false, type: 'text'},
                {name: 'rp_faktur', allowBlank: false, type: 'int'},
                {name: 'rp_potongan', allowBlank: false, type: 'int'},
                {name: 'rp_uang_muka', allowBlank: false, type: 'int'},
                {name: 'cash_diskon', allowBlank: false, type: 'int'},
                {name: 'rp_bayar', allowBlank: false, type: 'int'},
                {name: 'rp_kurang_bayar', allowBlank: false, type: 'int'}
       
               ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("bukti_serah_terima_tagihan/search_no_faktur") ?>',
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

    strgridbsttnofaktur.on('load', function() {
        Ext.getCmp('search_query_nofaktur').focus();
    });

    var searchfieldbsttnofaktur = new Ext.app.SearchField({
        width: 220,
        id: 'search_query_nofaktur',
        store: strgridbsttnofaktur
    });



    // top toolbar
    var tbsearchfieldbsttnofaktur = new Ext.Toolbar({
        items: [searchfieldbsttnofaktur]
    });

    var gridbsttnofaktur = new Ext.grid.GridPanel({
        store: strgridbsttnofaktur,
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
        tbar: tbsearchfieldbsttnofaktur,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridbsttnofaktur,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                     Ext.getCmp('bstt_no_faktur').setValue(sel[0].get('no_faktur'));                   
                     Ext.getCmp('bstt_tgl_faktur').setValue(sel[0].get('tgl_faktur'));
                     Ext.getCmp('bstt_rp_faktur').setValue(sel[0].get('rp_faktur'));
                     Ext.getCmp('bstt_tgl_jatuh_tempo').setValue(sel[0].get('tgl_jatuh_tempo'));
                     Ext.getCmp('bstt_rp_potongan').setValue(sel[0].get('rp_potongan'));                   
                     Ext.getCmp('bstt_rp_uang_muka').setValue(sel[0].get('rp_uang_muka'));
                     Ext.getCmp('bstt_rp_cash_diskon').setValue(sel[0].get('cash_diskon'));
                     Ext.getCmp('bstt_rp_bayar').setValue(sel[0].get('rp_bayar'));
                     Ext.getCmp('bstt_rp_kurang_bayar').setValue(sel[0].get('rp_kurang_bayar'));
                     menubsttnofaktur.hide();                  
                }
            }
        }
    });

    var menubsttnofaktur = new Ext.menu.Menu();
    menubsttnofaktur.add(new Ext.Panel({
        title: 'Pilih No Faktur',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 630,
        height: 250,
        closeAction: 'hide',
        plain: true,
        items: [gridbsttnofaktur],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menubsttnofaktur.hide();
                }
            }]
    }));

    menubsttnofaktur.on('hide', function() {
        var sf = Ext.getCmp('search_query_nofaktur').getValue();
        if (sf !== '') {
            Ext.getCmp('search_query_nofaktur').setValue('');
            searchfieldbsttnofaktur.onTrigger2Click();
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
            strgridbsttnofaktur.load({
                params: {
                    kd_pelanggan: Ext.getCmp('bstt_kd_pelanggan').getValue()                                 
                }
            });
            menubsttnofaktur.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
    //End Twin No Faktur
    
    //Twin Pelanggan
  var strcbbsttpelanggan = new Ext.data.ArrayStore({
        fields: ['kd_pelanggan'],
        data: []
    });

    var strgridbsttpelanggan = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                {name: 'kd_pelanggan', allowBlank: false, type: 'text'},
                {name: 'nama_pelanggan', allowBlank: false, type: 'text'}
                ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("bukti_serah_terima_tagihan/search_pelanggan") ?>',
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

    strgridbsttpelanggan.on('load', function() {
        Ext.getCmp('search_query_pelanggan').focus();
    });

    var searchfieldbsttpelanggan = new Ext.app.SearchField({
        width: 220,
        id: 'search_query_pelanggan',
        store: strgridbsttpelanggan
    });



    // top toolbar
    var tbsearchfieldbsttpelanggan = new Ext.Toolbar({
        items: [searchfieldbsttpelanggan]
    });

    var gridbsttpelanggan = new Ext.grid.GridPanel({
        store: strgridbsttpelanggan,
        stripeRows: true,
        frame: true,
        border: true,
        columns: [{
                header: 'Kode Pelanggan',
                dataIndex: 'kd_pelanggan',
                width: 100,
                sortable: true
            }, {
                header: 'Nama Pelanggan',
                dataIndex: 'nama_pelanggan',
                width: 200,
                sortable: true
            }],
        tbar: tbsearchfieldbsttpelanggan,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridbsttpelanggan,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                     Ext.getCmp('bstt_kd_pelanggan').setValue(sel[0].get('kd_pelanggan'));                   
                     Ext.getCmp('bstt_nama_pelanggan').setValue(sel[0].get('nama_pelanggan'));
                     menubsttpelanggan.hide();                  
                }
            }
        }
    });

    var menubsttpelanggan = new Ext.menu.Menu();
    menubsttpelanggan.add(new Ext.Panel({
        title: 'Pilih Pelanggan',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 350,
        height: 250,
        closeAction: 'hide',
        plain: true,
        items: [gridbsttpelanggan],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menubsttpelanggan.hide();
                }
            }]
    }));

    menubsttpelanggan.on('hide', function() {
        var sf = Ext.getCmp('search_query_pelanggan').getValue();
        if (sf !== '') {
            Ext.getCmp('search_query_pelanggan').setValue('');
            searchfieldbsttpelanggan.onTrigger2Click();
        }
    });


    Ext.ux.TwinComboPelanggan = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            //load store grid
            strgridbsttpelanggan.load({
                params: {
                    kd_colector: Ext.getCmp('id_kdcolector').getValue()                                 
                }
            });
            //strgridbsttpelanggan.load();
            menubsttpelanggan.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
    //End Twin Pelanggan

  // checkbox grid No TTF
    var cbGridBstt = new Ext.grid.CheckboxSelectionModel();
	
    var strcbbstt_nottf = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['no_ttf', 'tanggal'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("bukti_serah_terima_tagihan/search_no_ttf") ?>',
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
     strcbbstt_nottf.on('load', function(){
        Ext.getCmp('pcisearchbstt_nottf').focus();
    });
    var searchbstt_nottf = new Ext.app.SearchField({
        store: strcbbstt_nottf,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
            },
        
        width: 220,
        id: 'pcisearchbstt_nottf'
    });
    
    var tbbstt_nottf = new Ext.Toolbar({
        items: [searchbstt_nottf]
    });

//      searchbstt_nottf.onTrigger1Click = function(evt) {
//        if (this.hasSearch) {
//            this.el.dom.value = '';
//			
//            // Get the value of search field
//            var fid = Ext.getCmp('pci_kd_supplier').getValue();
//            var o = { start: 0, kd_supplier: fid };
//			
//            this.store.baseParams = this.store.baseParams || {};
//            this.store.baseParams[this.paramName] = '';
//            this.store.reload({
//                params : o
//            });
//            this.triggers[0].hide();
//            this.hasSearch = false;
//        }
//    };
//	
//    searchbstt_nottf.onTrigger2Click = function(evt) {
//        var text = this.getRawValue();
//        if (text.length < 1) {
//            this.onTrigger1Click();
//            return;
//        }
//	 
//        // Get the value of search field
//        var fid = Ext.getCmp('pci_kd_supplier').getValue();
//        var o = { start: 0, kd_supplier: fid };
//	 
//        this.store.baseParams = this.store.baseParams || {};
//        this.store.baseParams[this.paramName] = text;
//        this.store.reload({params:o});
//        this.hasSearch = true;
//        this.triggers[0].show();
//    };
    
    var gridbsttsearch_nottf = new Ext.grid.GridPanel({
        store: strcbbstt_nottf,
        stripeRows: true,
        frame: true,
        sm: cbGridBstt,
        border:true,
        columns: [cbGridBstt,{
                header: 'No TTF',
                dataIndex: 'no_ttf',
                width: 150,
                sortable: true			
            
            },{
                header: 'Tanggal',
                dataIndex: 'tanggal',
                width: 150,
                sortable: true         
            }],
        tbar:[tbbstt_nottf]
    });

	
    var menubstt_nottf = new Ext.menu.Menu();
    menubstt_nottf.add(new Ext.Panel({
        title: 'Pilih No TTF',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 350,
        height: 350,
        closeAction: 'hide',
        plain: true,
        items: [gridbsttsearch_nottf],
        buttons: [{
                // icon: BASE_ICONS + 'add.png',
                text: 'Done',
                handler: function(){
//                    if(Ext.getCmp('pci_kd_supplier').getValue() === ''){
//                        Ext.Msg.show({
//                            title: 'Error',
//                            msg: 'Silahkan pilih supplier terlebih dulu',
//                            modal: true,
//                            icon: Ext.Msg.ERROR,
//                            buttons: Ext.Msg.OK			               
//                        });
//                        return;
//                    }
					
                    var sm = gridbsttsearch_nottf.getSelectionModel();
                    var sel = sm.getSelections();
                    if (sel.length > 0) {
                        var data = '';
                        for (i = 0; i < sel.length; i++) {
                            data = data + sel[i].get('no_ttf') + ';';
                        } 
                        strbstt.load({
                            params: {
                                no_ttf: data
                            }
                        });
					
                        menubstt_nottf.hide();
                    }else{
                        Ext.Msg.show({
                            title: 'Error',
                            msg: 'Silahkan pilih no ttf',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK			               
                        });
                        return;
                    }
                }
            },{
                text: 'Close',
                handler: function(){
                    menubstt_nottf.hide();
                }
            }]
    }));
  //End TTF
 //Grid Panel    
var strbstt = new Ext.data.Store({
        autoSave: false,
        reader: new Ext.data.JsonReader({
            fields: [
                {name: 'kd_pelanggan', allowBlank: false, type: 'text'},
                {name: 'nama_pelanggan', allowBlank: false, type: 'text'},
                {name: 'rp_faktur', allowBlank: false, type: 'int'},
                {name: 'no_faktur', allowBlank: false, type: 'text'},
                {name: 'tgl_faktur', allowBlank: false, type: 'text'},
                {name: 'tgl_jatuh_tempo', allowBlank: false, type: 'text'},
                {name: 'no_ttf', allowBlank: false, type: 'text'},
                {name: 'rp_kurang_bayar', allowBlank: false, type: 'int'},
                {name: 'rp_bayar', allowBlank: false, type: 'int'},
                {name: 'rp_potongan', allowBlank: false, type: 'int'},
                {name: 'cash_diskon', allowBlank: false, type: 'int'},
                {name: 'rp_uang_muka', allowBlank: false, type: 'int'},
                 ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("bukti_serah_terima_tagihan/search_faktur_by_ttf") ?>',
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
    
    
    strbstt.on('update', function(){
        var grand_total = 0;
        strbstt.each(function(node){			
            grand_total += parseInt(node.data.rp_kurang_bayar);
            
        });
      console.log(grand_total);
      Ext.getCmp('bstt_rp_total').setValue(grand_total);
       
    });
    strbstt.on('load', function(){
        var grand_total = 0;
        strbstt.each(function(node){			
            grand_total += parseInt(node.data.rp_kurang_bayar);
            
        });
      console.log(grand_total);
      Ext.getCmp('bstt_rp_total').setValue(grand_total);
       
    });
    
var editorgridbstt = new Ext.ux.grid.RowEditor({
        saveText: 'Update'
    });

var gridbstt = new Ext.grid.GridPanel({
        store: strbstt,
        stripeRows: true,
        height: 250,
        frame: true,
        border:true,
        plugins: [editorgridbstt],
        tbar: [{
                icon: BASE_ICONS + 'add.png',
                text: 'Add',
                handler: function() {
                    
                    var rowbstt = new gridbstt.store.recordType({
//                        kd_produk: '',
//                        qty: '0'
                    });
                    editorgridbstt.stopEditing();
                    strbstt.insert(0, rowbstt);
                    gridbstt.getView().refresh();
                    gridbstt.getSelectionModel().selectRow(0);
                    editorgridbstt.startEditing(0);
                }
            }, {
                ref: '../removeBtn',
                icon: BASE_ICONS + 'delete.gif',
                text: 'Remove',
                disabled: true,
                handler: function() {
                    editorgridbstt.stopEditing();
                    var s = gridbstt.getSelectionModel().getSelections();
                    for (var i = 0, r; r = s[i]; i++) {
                        strbstt.remove(r);
                    }
                    var grand_total = 0;
                    strbstt.each(function(node){			
                        grand_total += parseInt(node.data.rp_kurang_bayar);

                    });
                    
                    Ext.getCmp('bstt_rp_total').setValue(grand_total);
                }
            },{
                icon: BASE_ICONS + 'add.png',
                text: 'Add No TTF',
                handler: function(){
                    strcbbstt_nottf.load({
//                        params: {
//                            kd_supplier: Ext.getCmp('pci_kd_supplier').getValue()  ,
//                            kd_peruntukkan: kd_peruntukkan
//                        }
                    });
                    menubstt_nottf.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
                }
            }],
        columns: [{
                header: 'Kode Pelanggan  ',
                dataIndex: 'kd_pelanggan',
                width: 120,
                format: '0,0',
                editor: new Ext.ux.TwinComboPelanggan({
                    id: 'bstt_kd_pelanggan',
                    store: strgridbsttpelanggan,
                    mode: 'local',
                    valueField: 'kd_pelanggan',
                    displayField: 'kd_pelanggan',
                    typeAhead: true,
                    triggerAction: 'all',
                    //allowBlank: false,
                    editable: false,
                    hiddenName: 'kd_pelanggan',
                    emptyText: 'Pilih Pelanggan'

                    })
                }				
                 ,{
                header: 'Nama Pelanggan',
                dataIndex: 'nama_pelanggan',
                width: 160,
                format: '0,0',
                editor: new Ext.form.TextField({
                    readOnly: true,
                    fieldClass: 'readonly-input',
                    id: 'bstt_nama_pelanggan'
                })
            },{
                header: 'No TTF',
                dataIndex: 'no_ttf',
                width: 160,
                format: '0,0',
                editor: new Ext.form.TextField({
                    readOnly: true,
                    fieldClass: 'readonly-input',
                    id: 'bstt_no_ttf'
                })
            }
            ,{
                header: 'No Faktur',
                dataIndex: 'no_faktur',
                width: 140,
                sortable: true,
                format: '0,0',
                editor: new Ext.ux.TwinComboNoFaktur({
                    id: 'bstt_no_faktur',
                    store: strcbbsttnofaktur,
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
                    id: 'bstt_tgl_faktur'
                })
            },{
                header: 'Tgl Jth Tempo',
                dataIndex: 'tgl_jatuh_tempo',
                width: 100,
                format: '0,0',
                editor: new Ext.form.TextField({
                    readOnly: true,
                    fieldClass: 'readonly-input',
                    id: 'bstt_tgl_jatuh_tempo'
                })
            },{xtype: 'numbercolumn',
                header: 'Rp Faktur',
                dataIndex: 'rp_faktur',
                width: 100,
                format: '0,0',
                editor: new Ext.form.TextField({
                    readOnly: true,
                    fieldClass: 'readonly-input',
                    id: 'bstt_rp_faktur'
                })
            },{xtype: 'numbercolumn',
                header: 'Rp Uang Muka',
                dataIndex: 'rp_uang_muka',
                width: 100,
                format: '0,0',
                editor: new Ext.form.TextField({
                    readOnly: true,
                    fieldClass: 'readonly-input',
                    id: 'bstt_rp_uang_muka'
                })
            },{xtype: 'numbercolumn',
                header: 'Rp Cash Diskon',
                dataIndex: 'cash_diskon',
                width: 100,
                format: '0,0',
                editor: new Ext.form.TextField({
                    readOnly: true,
                    fieldClass: 'readonly-input',
                    id: 'bstt_rp_cash_diskon'
                })
            },{xtype: 'numbercolumn',
                header: 'Rp Potongan',
                dataIndex: 'rp_potongan',
                width: 100,
                format: '0,0',
                editor: new Ext.form.TextField({
                    readOnly: true,
                    fieldClass: 'readonly-input',
                    id: 'bstt_rp_potongan'
                })
            },{xtype: 'numbercolumn',
                header: 'Rp Bayar',
                dataIndex: 'rp_bayar',
                width: 100,
                format: '0,0',
                editor: new Ext.form.TextField({
                    readOnly: true,
                    fieldClass: 'readonly-input',
                    id: 'bstt_rp_bayar'
                })
            },{xtype: 'numbercolumn',
                header: 'Rp Kurang Bayar',
                dataIndex: 'rp_kurang_bayar',
                width: 100,
                format: '0,0',
                editor: new Ext.form.TextField({
                    readOnly: true,
                    fieldClass: 'readonly-input',
                    id: 'bstt_rp_kurang_bayar'
                })
            }]
   });
   
    gridbstt.getSelectionModel().on('selectionchange', function(sm){
        gridbstt.removeBtn.setDisabled(sm.getCount() < 1);
    });
    //End Gridpanel
    var bukti_serah_terima_tagihan = new Ext.FormPanel({
        id: 'bukti_serah_terima_tagihan',
        border: false,
        frame: true,
        autoScroll:true,        
        bodyStyle:'padding-right:20px;',
        labelWidth: 130,
        items: [{
                bodyStyle: {
                    margin: '0px 0px 15px 0px'
                },                  
                items: [header_bstt]
            },
            gridbstt,
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
                                        id: 'bstt_rp_total',
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
                    
                    var gridbstt = new Array();				
                    strbstt.each(function(node){
                        gridbstt.push(node.data)
                    });
                    Ext.getCmp('bukti_serah_terima_tagihan').getForm().submit({
                        url: '<?= site_url("bukti_serah_terima_tagihan/update_row") ?>',
                        scope: this,
                        params: {
                            detail: Ext.util.JSON.encode(gridbstt),
                            _rp_total: Ext.getCmp('bstt_rp_total').getValue(),
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
									
                                    winbsttprint.show();
                                    Ext.getDom('bsttprint').src = r.printUrl;
                                }
                            });			            
			            
                            clearbstt();						
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
                    clearbstt();
                }
            }]
    }); 
     var winbsttprint = new Ext.Window({
        id: 'id_winbsttprint',
        title: 'Print BSTT',
        closeAction: 'hide',
        width: 900,
        height: 450,
        layout: 'fit',
        border: false,
        html:'<iframe style="width:100%;height:100%;" id="bsttprint" src=""></iframe>'
    });
    function clearbstt(){
        Ext.getCmp('bukti_serah_terima_tagihan').getForm().reset();
        Ext.getCmp('bukti_serah_terima_tagihan').getForm().load({
            
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
        strbstt.removeAll();
    }
</script>
