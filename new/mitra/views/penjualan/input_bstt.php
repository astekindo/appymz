<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">
  //Twin Collector
  var strcbibsttcolector = new Ext.data.ArrayStore({
        fields: ['nama_collector'],
        data : []
    });
	
    var strgridibsttcolector = new Ext.data.Store({
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
	
    strgridibsttcolector.on('load', function(){
        Ext.getCmp('id_searchgridibsttcolector').focus();
    });
	
    var searchgridibsttcolector = new Ext.app.SearchField({
        store: strgridibsttcolector,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE			
        },
        width: 350,
        id: 'id_searchgridibsttcolector'
    });
	
	
    var gridibsttcolector = new Ext.grid.GridPanel({
        store: strgridibsttcolector,
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
            items: [searchgridibsttcolector]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridibsttcolector,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function(){			
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {				
                    Ext.getCmp('ibstt_kdcolector').setValue(sel[0].get('kd_collector'));                   
                    Ext.getCmp('id_cbibsttcolector').setValue(sel[0].get('nama_collector'));
                                       
                    //strfakturpenjualan.removeAll();       
                    menuibsttcolector.hide();
                   cleartotalfaktur();
                }
            }
        }
    });
	
    var menuibsttcolector = new Ext.menu.Menu();
    menuibsttcolector.add(new Ext.Panel({
        title: 'Pilih Kolektor',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridibsttcolector],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menuibsttcolector.hide();
                }
            }]
    }));
    
    Ext.ux.TwinComboIbsttColector = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            //load store grid
            strgridibsttcolector.load();
            menuibsttcolector.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
	
    menuibsttcolector.on('hide', function(){
        var sf = Ext.getCmp('id_searchgridibsttcolector').getValue();
        if( sf != ''){
            Ext.getCmp('id_searchgridibsttcolector').setValue('');
            searchgridibsttcolector.onTrigger2Click();
        }
    });
	
    var cbibsttcolector = new Ext.ux.TwinComboIbsttColector({
        fieldLabel: 'Kolektor <span class="asterix">*</span>',
        id: 'id_cbibsttcolector',
        store: strcbibsttcolector,
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
      // Start Combo BSTT
    var strcbinbstt = new Ext.data.ArrayStore({
        fields: ['no_bukti'],
        data : []
    });
	
    var strgridinbstt = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['no_bstt', 'tanggal','total_faktur'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("input_bstt/search_bstt") ?>',
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
	
    strgridinbstt.on('load', function(){
        Ext.getCmp('id_searchgridinbstt').focus();
    });
	
    var searchgridinbstt = new Ext.app.SearchField({
        store: strgridinbstt,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE			
        },
        width: 350,
        id: 'id_searchgridinbstt'
    });
	
	
    var gridinbstt = new Ext.grid.GridPanel({
        store: strgridinbstt,
        stripeRows: true,
        frame: true,
        border:true,
        columns: [{
                header: 'No BSTT',
                dataIndex: 'no_bstt',
                width: 120,
                sortable: true		
            
            },{
                header: 'Tanggal',
                dataIndex: 'tanggal',
                width: 100,
                sortable: true         
            },{
                header: 'Total Faktur',
                dataIndex: 'total_faktur',
                width: 150,
                sortable: true         
            }],
        tbar: new Ext.Toolbar({
            items: [searchgridinbstt]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridinbstt,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function(){			
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {				
                                     
                    Ext.getCmp('id_cbinbstt').setValue(sel[0].get('no_bstt'));
                    strinput_bstt.load({
                        params:{
                             no_bstt: sel[0].get('no_bstt')
                        }
                    });  
                    menuinbstt.hide();
                   cleartotalfaktur();
                }
            }
        }
    });
	
    var menuinbstt = new Ext.menu.Menu();
    menuinbstt.add(new Ext.Panel({
        title: 'Pilih BSTT',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridinbstt],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menuinbstt.hide();
                }
            }]
    }));
    
    Ext.ux.TwinComboInBstt = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            //load store grid
            strgridinbstt.load({
                params: {
                    kd_colector: Ext.getCmp('ibstt_kdcolector').getValue()                                 
                }
            });
            menuinbstt.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
	
    menuinbstt.on('hide', function(){
        var sf = Ext.getCmp('id_searchgridinbstt').getValue();
        if( sf != ''){
            Ext.getCmp('id_searchgridinbstt').setValue('');
            searchgridinbstt.onTrigger2Click();
        }
    });
	
    var cbinbstt = new Ext.ux.TwinComboInBstt({
        fieldLabel: 'BSTT <span class="asterix">*</span>',
        id: 'id_cbinbstt',
        store: strcbinbstt,
        mode: 'local',
        valueField: 'no_bstt',
        displayField: 'no_bstt',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'no_bstt',
        emptyText: 'Pilih BSTT'
    });
    //End Combo BSTT
    // TWIN COMBO NO FAKTUR
//    var strcbpppnofaktur = new Ext.data.ArrayStore({
//        fields: ['no_faktur'],
//        data: []
//    });
//
//    var strgridpppnofaktur_dist = new Ext.data.Store({
//        reader: new Ext.data.JsonReader({
//            fields: ['no_faktur', 
//                      'tgl_faktur', 
//                      'rp_faktur', 
//                      'rp_uang_muka',
//                      'rp_faktur_net', 
//                      'rp_dpp', 
//                      'rp_ppn',
//                      'rp_bayar',
//                      'cash_diskon',
//                      'rp_kurang_bayar'
//                      
//                  ],
//            root: 'data',
//            totalproperty: 'record'
//        }),
//        proxy: new Ext.data.HttpProxy({
//            url: '<?= site_url("input_bsttribusi/get_all_faktur") ?>',
//            method: 'POST'
//        }),
//        listeners: {
//            loadexception: function(event, options, response, error) {
//                var err = Ext.util.JSON.decode(response.responseText);
//                if (err.errMsg == 'Session Expired') {
//                    session_expired(err.errMsg);
//                }
//            }
//        }
//    });
//     strgridpppnofaktur_dist.on('load', function() {
//        Ext.getCmp('id_searchnofaktur').focus();
//    });
//
//    var searchnofaktur = new Ext.app.SearchField({
//        store: strgridpppnofaktur_dist,
//        params: {
//            start: STARTPAGE,
//            limit: ENDPAGE
//        },
//        width: 350,
//        id: 'id_searchnofaktur'
//    });
//
//    var gridpppnofaktur_dist = new Ext.grid.GridPanel({
//        store: strgridpppnofaktur_dist,
//        stripeRows: true,
//        frame: true,
//        border: true,
//        columns: [{
//                header: 'No Faktur',
//                dataIndex: 'no_faktur',
//                width: 120,
//                sortable: true
//            }, {
//                header: 'Tgl Faktur',
//                dataIndex: 'tgl_faktur',
//                width: 100,
//                sortable: true
//            }, {
//                xtype: 'numbercolumn',
//                header: 'Rp Faktur',
//                dataIndex: 'rp_faktur',
//                width: 130,
//                align: 'right',
//                format: '0,0',
//                sortable: true
//            }, {
//                xtype: 'numbercolumn',
//                header: 'Rp Uang Muka',
//                dataIndex: 'rp_uang_muka',
//                width: 130,
//                align: 'right',
//                format: '0,0',
//                sortable: true
//            },{
//                xtype: 'numbercolumn',
//                header: 'Cash Diskon',
//                dataIndex: 'cash_diskon',
//                width: 130,
//                align: 'right',
//                format: '0,0',
//                sortable: true
//            },{
//                xtype: 'numbercolumn',
//                header: 'Rp Bayar',
//                dataIndex: 'rp_bayar',
//                width: 130,
//                align: 'right',
//                format: '0,0',
//                sortable: true
//            },{
//                xtype: 'numbercolumn',
//                header: 'Rp Kurang Bayar',
//                dataIndex: 'rp_kurang_bayar',
//                width: 130,
//                align: 'right',
//                format: '0,0',
//                sortable: true
//            }],
//        tbar: new Ext.Toolbar({
//            items: [searchnofaktur]
//        }),
//        bbar: new Ext.PagingToolbar({
//            pageSize: ENDPAGE,
//            store: strgridpppnofaktur_dist,
//            displayInfo: true
//        }),
//        listeners: {
//            'rowdblclick': function() {
//                var sm = this.getSelectionModel();
//                var sel = sm.getSelections();
//                if (sel.length > 0) {
////                    gridinput_bstt.store.reload({
////                        params: {
////                            no_faktur: sel[0].get('no_so')
////                        }
////                    });
//                    
//                    Ext.getCmp('eppp_no_faktur_dist').setValue(sel[0].get('no_faktur'));
//                    Ext.getCmp('ibstt_rp_faktur').setValue(sel[0].get('rp_faktur'));
//                    Ext.getCmp('eppp_rp_uang_muka').setValue(sel[0].get('rp_uang_muka'));
//                    Ext.getCmp('eppp_cash_diskon').setValue(sel[0].get('cash_diskon'));
//                    Ext.getCmp('ibstt_rp_total_bayar_dist').setValue(sel[0].get('rp_bayar'));
//                    Ext.getCmp('ibstt_rp_kurang_bayar_dist').setValue(sel[0].get('rp_kurang_bayar'));
//                    Ext.getCmp('ibstt_tgl_faktur').setValue(sel[0].get('tgl_faktur'));
//                    
//                    //var rp_bayar = sel[0].get('rp_faktur') - sel[0].get('rp_uang_muka');
//                    Ext.getCmp('ibstt_rp_bayar_dist').setValue(sel[0].get('rp_kurang_bayar'));
//                    Ext.getCmp('ibstt_rp_potongan_dist').setValue(0);
//                    Ext.getCmp('ibstt_rp_dibayar_dist').setValue(sel[0].get('rp_kurang_bayar'));
//                    Ext.getCmp('ibstt_sisa_bayar_dist').setValue(0);
//
//                    Ext.getCmp('ibstt_rp_bayar_dist').focus();
//                    var _ada = false;
//                    strinput_bstt.each(function(record){
//                    if(record.get('no_faktur') === sel[0].get('no_faktur')){
//                             _ada = true;
//                         }
//                     });
//                    if (_ada){
//                                    Ext.Msg.show({
//                                        title: 'Error',
//                                        msg: 'No Faktur / Struk yang sama tidak boleh dipilih lebih dari satu kali',
//                                        modal: true,
//                                        icon: Ext.Msg.ERROR,
//                                        buttons: Ext.Msg.OK,
//                                        fn: function(btn){
//                                            if (btn == 'ok') {
//                                                Ext.getCmp('eppp_no_faktur_dist').reset();
//                                            }
//                                        }                            
//                                    });
//                                    Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');
//                                    Ext.getCmp('eppp_no_faktur_dist').focus();	
//                                    return;
//                                }
//                    menupppnofaktur_dist.hide();
//                }
//            }
//        }
//    });
//
//    var menupppnofaktur_dist = new Ext.menu.Menu();
//    menupppnofaktur_dist.add(new Ext.Panel({
//        title: 'Pilih No Faktur',
//        layout: 'fit',
//        buttonAlign: 'left',
//        modal: true,
//        width: 450,
//        height: 300,
//        closeAction: 'hide',
//        plain: true,
//        items: [gridpppnofaktur_dist],
//        buttons: [{
//                text: 'Close',
//                handler: function() {
//                    menupppnofaktur_dist.hide();
//                }
//            }]
//    }));
//
//    Ext.ux.TwinCombopppnofaktur_dist = Ext.extend(Ext.form.ComboBox, {
//        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
//        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
//        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
//        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
//        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
//        onTrigger2Click: function() {
//            //load store grid
//            strgridpppnofaktur_dist.load({
//                params: {
//                    kd_pelanggan: Ext.getCmp('id_cbibsttcolector').getValue()
//                }
//            });
//            menupppnofaktur_dist.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
//        },
//        validationEvent: false,
//        validateOnBlur: false,
//        trigger1Class: 'x-form-clear-trigger',
//        trigger2Class: 'x-form-search-trigger',
//        hideTrigger1: true
//    });


//    var cbppnofaktur = new Ext.ux.TwinCombopppnofaktur({
//        fieldLabel: 'No Faktur <span class="asterix">*</span>',
//        id: 'eppp_no_faktur_dist',
//        store: strcbpppnofaktur,
//        mode: 'local',
//        valueField: 'no_faktur',
//        displayField: 'no_faktur',
//        typeAhead: true,
//        triggerAction: 'all',
//        editable: false,
//        anchor: '90%',
//        hiddenName: 'no_faktur',
//        emptyText: 'Pilih No Faktur'
//    });

    var headerinput_bstt = {
        layout: 'column',
        border: false,
        items: [{
                columnWidth: .5,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: {labelSeparator: ''},
                items: [{
                        xtype: 'textfield',
                        fieldLabel: 'No Bukti',
                        name: 'no_bukti',
                        //allowBlank: false,
                        readOnly: true,
                        fieldClass: 'readonly-input',
                        id: 'ibstt_no_bukti_dist',
                        maxLength: 255,
                        anchor: '90%',
                        value: ''
                    },
                    cbibsttcolector,cbinbstt,
                    {
                        xtype: 'hidden',
                        fieldLabel: 'kd collector',
                        name: 'kd_collector',
                        readOnly:true,
                        fieldClass:'readonly-input',
                        id: 'ibstt_kdcolector',                
                        anchor: '90%',
                        value:''
                    }
                ]
            }, {
                columnWidth: .5,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: {labelSeparator: ''},
                items: [{
                        xtype: 'datefield',
                        fieldLabel: 'Tanggal',
                        name: 'tanggal',
                        allowBlank: false,
                        format: 'd-M-Y',
                        emptyText: 'Tgl Pelunasan',
                        value: new Date(),
                        id: 'ibstt_tanggal_dist',
                        anchor: '90%',
                        maxValue: (new Date()).clearTime()
                               
                    },{
                        xtype: 'textfield',
                        fieldLabel: 'Keterangan <span class="asterix">*</span>',
                        allowBlank: false,
                        name: 'keterangan',
                        id: 'ibstt_keterangan_dist',
                        anchor: '90%'
                    }]
            }
        ]
    }


    var strinput_bstt = new Ext.data.Store({
        autoSave: false,
        reader: new Ext.data.JsonReader({
            fields: [
                {name: 'no_faktur', allowBlank: false, type: 'text'},
                {name: 'tanggal', allowBlank: false, type: 'text'},
                {name: 'nama_pelanggan', allowBlank: false, type: 'text'},
                {name: 'rp_faktur', allowBlank: false, type: 'int'},
                {name: 'rp_diskon', allowBlank: false, type: 'int'},
                {name: 'rp_total_bayar', allowBlank: false, type: 'int'},
                {name: 'rp_kurang_bayar', allowBlank: false, type: 'int'},
                {name: 'rp_grand_total', allowBlank: false, type: 'int'},
                {name: 'rp_bayar', allowBlank: false, type: 'int'},
                {name: 'sisa_bayar', allowBlank: false, type: 'int'}
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("input_bstt/get_rows") ?>',
            method: 'POST'
        }),
        listeners: {
            loadexception: function(event, options, response, error) {
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }

        // writer: new Ext.data.JsonWriter(
        // {
        // encode: true,
        // writeAllFields: true
        // })
    });
    strinput_bstt.on('update', function() {
        var total_faktur = 0;
        var total_kurang_bayar = 0;
        var total_dibayar = 0;
        var total_bayar = 0;
        var total_potongan = 0;
         total_bayar = Ext.getCmp('ppp_total_bayar_dist').getValue();
       
        
//        var total_kurang_bayar = Ext.getCmp('ppp_total_bayar_dist').getValue();
//        var bayar = Ext.getCmp('ppp_rp_bayar_dist').getValue();
        strinput_bstt.each(function(node) {
            total_faktur += parseInt(node.data.rp_grand_total);
            total_kurang_bayar += parseInt(node.data.rp_kurang_bayar);
            total_dibayar += parseInt(node.data.rp_dibayar);
            total_potongan += parseInt(node.data.rp_potongan);
        });
        Ext.getCmp('ppp_rp_total_faktur_dist').setValue(total_faktur);
        Ext.getCmp('ppp_rp_kurang_bayar_dist').setValue(total_kurang_bayar);
        Ext.getCmp('ppp_rp_potongan_dist').setValue(total_potongan);
        Ext.getCmp('ppp_rp_bayar_dist').setValue(total_dibayar);
        Ext.getCmp('ppp_rp_selisih_dist').setValue(total_bayar - total_dibayar);

    });



    var editorinput_bstt = new Ext.ux.grid.RowEditor({
        saveText: 'Update'
    });


    var gridinput_bstt = new Ext.grid.GridPanel({
        store: strinput_bstt,
        stripeRows: true,
        height: 200,
        frame: true,
        border: true,
        plugins: [editorinput_bstt],
//        tbar: [{
//                icon: BASE_ICONS + 'add.png',
//                text: 'Add No Faktur',
//                handler: function() {
//                    if (Ext.getCmp('id_cbibsttcolector').getValue() === '') {
//                        Ext.Msg.show({
//                            title: 'Error',
//                            msg: 'Silahkan pilih pelangggan terlebih dulu',
//                            modal: true,
//                            icon: Ext.Msg.ERROR,
//                            buttons: Ext.Msg.OK
//                        });
//                        return;
//                    }
//
//                    var rowinput_bstt = new gridinput_bstt.store.recordType({
//                        no_invoice: '',
//                        rp_total: '',
//                        rp_bayar: '',
//                        rp_diskon: ''
//                    });
//                    editorinput_bstt.stopEditing();
//                    strinput_bstt.insert(0, rowinput_bstt);
//                    gridinput_bstt.getView().refresh();
//                    gridinput_bstt.getSelectionModel().selectRow(0);
//                    editorinput_bstt.startEditing(0);
//                }
//            }, {
//                ref: '../removeBtn',
//                icon: BASE_ICONS + 'delete.gif',
//                text: 'Remove',
//                disabled: true,
//                handler: function() {
//
//                    editorinput_bstt.stopEditing();
//                    var s = gridinput_bstt.getSelectionModel().getSelections();
//                    for (var i = 0, r; r = s[i]; i++) {
//                        strinput_bstt.remove(r);
//                    }
//                    var jumlah = 0;
//                    var rp_kurang_bayar = 0;
//                    var rp_bayar = 0;
//
//                    strinput_bstt.each(function(node) {
//                        jumlah += parseInt(node.data.rp_grand_total);
//                        rp_kurang_bayar += parseInt(node.data.rp_kurang_bayar);
//                        rp_bayar += parseInt(node.data.rp_bayar);
//                    });
//                    
//                    var total_bayar = Ext.getCmp('ppp_total_bayar_dist').getValue();
//                    var selisih = total_bayar - rp_bayar ;
//                    Ext.getCmp('ppp_rp_total_faktur_dist').setValue(jumlah);
//                    Ext.getCmp('ppp_rp_kurang_bayar_dist').setValue(rp_kurang_bayar);
//                    Ext.getCmp('ppp_rp_bayar_dist').setValue(rp_bayar);
//                    Ext.getCmp('ppp_rp_selisih_dist').setValue(selisih);
//                }
//            }],
        columns: [{
                header: 'Pelanggan',
                dataIndex: 'nama_pelanggan',
                width: 150,
                sortable: true,
                 editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'ibstt_nama_pelanggan',
                    fieldClass: 'readonly-input'
                    })
               },{
                header: 'No Faktur',
                dataIndex: 'no_faktur',
                width: 150,
                sortable: true,
                 editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'ibstt_no_faktur',
                    fieldClass: 'readonly-input'
                })
            }, {
                header: 'Tanggal Faktur',
                dataIndex: 'tanggal',
                width: 90,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'ibstt_tgl_faktur',
                    fieldClass: 'readonly-input'
                })
            },
                {
                xtype: 'numbercolumn',
                header: 'Jumlah Faktur',
                dataIndex: 'rp_faktur',
                width: 120,
                sortable: true,
                align: 'right',
                format: '0,0',
                value: 0,
                editor: {
                    xtype: 'numberfield',
                    id: 'ibstt_rp_faktur',
                    fieldClass: 'readonly-input number',
                    readOnly: true
                }
            },
            {
                xtype: 'numbercolumn',
                header: 'Rp Bayar',
                dataIndex: 'rp_bayar',
                width: 120,
                sortable: true,
                align: 'right',
                format: '0,0',
                value: 0,
                editor: {
                    xtype: 'numberfield',
                    id: 'ibstt_rp_bayar_dist',
                    listeners: {
                        'change': function() {
                            
                            var sisa = Ext.getCmp('ibstt_rp_faktur').getValue() - this.getValue();
                            Ext.getCmp('ibstt_sisa_bayar_dist').setValue(sisa);
                            
//                            var dibayar = this.getValue() - Ext.getCmp('ibstt_rp_potongan_dist').getValue();
//                            Ext.getCmp('ibstt_rp_dibayar_dist').setValue(dibayar);
                        },
                        'specialkey': function(field, e){
                            if (e.getKey() == e.ENTER) {
                                var sisa = Ext.getCmp('ibstt_rp_faktur').getValue() - this.getValue();
                                Ext.getCmp('ibstt_sisa_bayar_dist').setValue(sisa);
                                
//                                var dibayar = this.getValue() - Ext.getCmp('ibstt_rp_potongan_dist').getValue();
//                                Ext.getCmp('ibstt_sisa_bayar_dist').setValue(dibayar);
                            }
                        }
                    }
                }
            },
//            {
//                xtype: 'numbercolumn',
//                header: 'Rp Dibayar',
//                dataIndex: 'rp_dibayar',
//                width: 100,
//                sortable: true,
//                align: 'right',
//                format: '0,0',
//                value: 0,
//                editor: {
//                    xtype: 'numberfield',
//                    id: 'ibstt_rp_dibayar_dist',
//                    fieldClass: 'readonly-input number',
//                    readOnly: true
//                }
//            },
            {
                xtype: 'numbercolumn',
                header: 'Sisa Piutang',
                dataIndex: 'sisa_bayar',
                width: 100,
                sortable: true,
                align: 'right',
                format: '0,0',
                value: 0,
                editor: {
                    xtype: 'numberfield',
                    id: 'ibstt_sisa_bayar_dist',
                    fieldClass: 'readonly-input number',
                    readOnly: true
                }
            }]
    });

     gridinput_bstt.getSelectionModel().on('selectionchange', function(sm){
     gridinput_bstt.removeBtn.setDisabled(sm.getCount() < 1);
     });

    var _strcbpppjnsbayar_dist = new Ext.data.ArrayStore({
        fields: ['kd_jenis_bayar'],
        data: []
    });

    var _strgridpppjnsbayar_dist = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_jenis_bayar', 'nm_pembayaran','is_validasi_card'],
            root: 'data',
            totalproperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("penjualan_pelunasan_piutang/get_all_jenis_pembayaran") ?>',
            method: 'POST'
        }),
        listeners: {
            loadexception: function(event, options, response, error) {
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    var _gridpppjnsbayar_dist = new Ext.grid.GridPanel({
        store: _strgridpppjnsbayar_dist,
        stripeRows: true,
        frame: true,
        border: true,
        columns: [{
                header: 'Kode',
                dataIndex: 'kd_jenis_bayar',
                width: 70,
                sortable: true
            }, {
                header: 'Jenis Pembayaran',
                dataIndex: 'nm_pembayaran',
                width: 200,
                sortable: true
            },{
                header: 'Jenis Pembayaran',
                dataIndex: 'is_validasi_card',
                width: 200,
                sortable: true,
                hidden: true
            }],
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('eppp_kd_jenis_bayar_dist').setValue(sel[0].get('kd_jenis_bayar'));
                    Ext.getCmp('eppp_nama_pembayaran_dist').setValue(sel[0].get('nm_pembayaran'));
                    Ext.getCmp('eppp_is_validasi_card').setValue(sel[0].get('is_validasi_card'));
                    menupppjnsbayar_dist.hide();
                }
            }
        }
    });

    var menupppjnsbayar_dist = new Ext.menu.Menu();
    menupppjnsbayar_dist.add(new Ext.Panel({
        title: 'Pilih Jenis Pembayaran',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 300,
        height: 250,
        closeAction: 'hide',
        plain: true,
        items: [_gridpppjnsbayar_dist],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menupppjnsbayar_dist.hide();
                }
            }]
    }));

    Ext.ux.TwinCombopppjnspembayaran = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            //load store grid
            _strgridpppjnsbayar_dist.load();
            menupppjnsbayar_dist.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
                trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    var strjenisbayarinput_bstt = new Ext.data.Store({
        autoSave: false,
        reader: new Ext.data.JsonReader({
            fields: [
                {name: 'kd_jenis_bayar', type: 'text'},
                {name: 'nm_pembayaran', type: 'text'},
                {name: 'rp_bayar', type: 'int'},
                {name: 'nomor_ref', type: 'text'},
                {name: 'nomor_bank', type: 'text'},
                {name: 'tgl_jth_tempo', type: 'text'},
                {name: 'is_validasi_card', type: 'int'}
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



    strjenisbayarinput_bstt.on('update', function() {
        var total_bayar = 0;
        var total_kurang_bayar = Ext.getCmp('ppp_rp_kurang_bayar_dist').getValue();
        var rp_bayar = Ext.getCmp('ppp_rp_bayar_dist').getValue();
        var nama_pembayaran = "";
        var no_bank = "";
        var no_ref = "";
        var tgl_jth_tempo = "";
        strjenisbayarinput_bstt.each(function(node) {
            total_bayar += parseInt(node.data.rp_bayar_piutang);
            nama_pembayaran = node.data.nm_pembayaran;
            no_bank = node.data.nomor_bank;
            no_ref = node.data.nomor_ref;
            tgl_jth_tempo = node.data.tgl_jth_tempo;
        });
        
                
        Ext.getCmp('ppp_total_bayar_dist').setValue(total_bayar);
        Ext.getCmp('ppp_rp_selisih_dist').setValue(total_bayar - rp_bayar);
        
        

    });

    var editorpppjenisbayar_dist = new Ext.ux.grid.RowEditor({
        saveText: 'Update'
    });

    var gridjenisbayarinput_bstt = new Ext.grid.GridPanel({
        id: 'idgridjenisbayarinput_bstt',
        store: strjenisbayarinput_bstt,
        stripeRows: true,
        height: 200,
        border: true,
        frame: true,
        plugins: [editorpppjenisbayar_dist],
        tbar: [{
                icon: BASE_ICONS + 'add.png',
                text: 'Add Pembayaran',
                handler: function() {
                    if (Ext.getCmp('ppp_rp_total_faktur_dist').getValue() == 0) {
                        Ext.Msg.show({
                            title: 'Error',
                            msg: 'Total faktur masih kosong!',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK,
                        });
                        return;
                    }
                    var rowpppjenispembayaran = new gridjenisbayarinput_bstt.store.recordType({
                        kd_jenis_bayar: '',
                        rp_bayar: '',
                        nomor_bank: '',
                        nomor_ref: '',
                        tgl_jth_tempo: '',
                        is_validasi_card:''
                    });
                    editorpppjenisbayar_dist.stopEditing();
                    strjenisbayarinput_bstt.insert(0, rowpppjenispembayaran);
                    gridjenisbayarinput_bstt.getView().refresh();
                    gridjenisbayarinput_bstt.getSelectionModel().selectRow(0);
                    editorpppjenisbayar_dist.startEditing(0);
                }
            }, {
                ref: '../removeBtn',
                icon: BASE_ICONS + 'delete.gif',
                text: 'Remove',
                disabled: true,
                handler: function() {

                    editorpppjenisbayar_dist.stopEditing();
                    var s = gridjenisbayarinput_bstt.getSelectionModel().getSelections();
                    for (var i = 0, r; r = s[i]; i++) {
                        strjenisbayarinput_bstt.remove(r);
                    }

                }
            }],
        columns: [{
                header: 'Kode',
                dataIndex: 'kd_jenis_bayar',
                width: 100,
                editor: new Ext.ux.TwinCombopppjnspembayaran({
                    id: 'eppp_kd_jenis_bayar_dist',
                    store: _strcbpppjnsbayar_dist,
                    mode: 'local',
                    valueField: 'kd_jenis_bayar',
                    displayField: 'kd_jenis_bayar',
                    typeAhead: true,
                    triggerAction: 'all',
                    editable: false,
                    hiddenName: 'kd_jenis_bayar',
                    emptyText: 'Pilih Jenis Pembayaran'

                })
            }, {
                header: 'Jenis Pembayaran',
                dataIndex: 'nm_pembayaran',
                width: 200,
                sortable: true,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'eppp_nama_pembayaran_dist',
                    fieldClass: 'readonly-input',
                })
            }, {
                xtype: 'numbercolumn',
                header: 'Jumlah Bayar',
                dataIndex: 'rp_bayar_piutang',
                width: 100,
                format: '0,0',
                editor: {
                    xtype: 'numberfield',
                    id: 'eppp_rp_bayar_jns_dist',
                    selectOnFocus: true,
                }
            }, {
                header: 'Nama Bank',
                dataIndex: 'nomor_bank',
                width: 100,
                editor: new Ext.form.TextField({
                    id: 'eppp_nomor_bank_dist'
                })
            }, {
                header: 'No Warkat',
                dataIndex: 'nomor_ref',
                width: 100,
                editor: new Ext.form.TextField({
                    id: 'eppp_nomor_ref_dist'
                })
            }, {
                xtype: 'datecolumn',
                header: 'Tgl Jatuh Tempo',
                dataIndex: 'tgl_jth_tempo',
                format: 'd/m/Y',
                width: 120,
                editor: new Ext.form.DateField({
                    id: 'eppp_tgl_jth_tempo_dist',
                    format: 'd/m/Y',
                })
            },{
                header: 'validasi card',
                dataIndex: 'is_validasi_card',
                width: 100,
                hidden : false,
                editor: new Ext.form.TextField({
                    id: 'eppp_is_validasi_card',
                    readOnly : true,
                    fieldClass: 'readonly-input number'
                })
            } 
        ]
    });

    gridjenisbayarinput_bstt.getSelectionModel().on('selectionchange', function(sm) {
        gridjenisbayarinput_bstt.removeBtn.setDisabled(sm.getCount() < 1);
    });

    var input_bstt = new Ext.FormPanel({
        id: 'input_bstt',
        border: false,
        frame: true,
        autoScroll: true,
        monitorValid: true,
        bodyStyle: 'padding-right:20px;',
        labelWidth: 130,
        items: [{
                bodyStyle: {
                    margin: '0px 0px 15px 0px'
                },
                items: [headerinput_bstt]
            },
            gridinput_bstt,
            {
                layout: 'column',
                border: false,
                items: [{
                        columnWidth: .7,
                        layout: 'fit',
                        style: 'margin:6px 3px 0 0;',
                        items: [
                            gridjenisbayarinput_bstt
                        ]
                    }, {
                        columnWidth: .3,
                        layout: 'form',
                        style: 'margin:6px 0 0 0;',
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
                                        id: 'ppp_rp_total_faktur_dist',
                                        anchor: '90%',
                                        fieldClass: 'readonly-input number',
                                        value: '0',
                                    }, {
                                        xtype: 'numericfield',
                                        currencySymbol: '',
                                        fieldLabel: 'Rp Kurang Bayar',
                                        name: 'rp_kurang_bayar',
                                        id: 'ppp_rp_kurang_bayar_dist',
                                        anchor: '90%',
                                        readOnly: true,
                                        fieldClass: 'readonly-input number',
                                        value: '0',
                                    },{
                                        xtype: 'numericfield',
                                        currencySymbol: '',
                                        fieldLabel: 'Total Potongan',
                                        name: 'rp_potongan',
                                        id: 'ppp_rp_potongan_dist',
                                        anchor: '90%',
                                        readOnly: true,
                                        fieldClass: 'readonly-input number',
                                        value: '0',
                                    }, {
                                        xtype: 'numericfield',
                                        currencySymbol: '',
                                        fieldLabel: 'Total Dibayar',
                                        name: 'rp_bayar',
                                        id: 'ppp_rp_bayar_dist',
                                        anchor: '90%',
                                        readOnly: true,
                                        fieldClass: 'readonly-input number',
                                        value: '0',
                                    }, {
                                        xtype: 'numericfield',
                                        currencySymbol: '',
                                        fieldLabel: 'Total bayar',
                                        name: 'total_bayar',
                                        id: 'ppp_total_bayar_dist',
                                        anchor: '90%',
                                        readOnly: true,
                                        fieldClass: 'readonly-input number',
                                        value: '0',
                                    }, {
                                        xtype: 'numericfield',
                                        currencySymbol: '',
                                        fieldLabel: 'Selisih',
                                        name: 'rp_selisih',
                                        readOnly: true,
                                        fieldClass: 'readonly-input number',
                                        id: 'ppp_rp_selisih_dist',
                                        anchor: '90%',
                                        value: '0',
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
                handler: function() {

                    if (Ext.getCmp('ppp_rp_bayar_dist').getValue() == 0) {
                        Ext.Msg.show({
                            title: 'Error',
                            msg: 'Rp Bayar Masih Kosong / NOL!',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK,
                        });
                        return;
                    }
                    if (Ext.getCmp('ppp_total_bayar_dist').getValue() <  Ext.getCmp('ppp_rp_bayar_dist').getValue()) {
                        Ext.Msg.show({
                            title: 'Error',
                            msg: 'Total Bayar Tidak Boleh Lebih Kecil Dari Total Dibayar!',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK,
                        });
                        return;
                    }
                    var nama_pembayaran = "";
                    var no_bank = "";
                    var no_ref = "";
                    var tgl_jth_tempo = "";
                    var is_validasi_card = "";
                    
                    var is_true = true;
                    var is_validasi = true;
                    strjenisbayarinput_bstt.each(function(node) {
                        nama_pembayaran = node.data.nm_pembayaran;
                        no_bank = node.data.nomor_bank;
                        no_ref = node.data.nomor_ref;
                        tgl_jth_tempo = node.data.tgl_jth_tempo;
                        is_validasi_card = node.data.is_validasi_card;
                        console.log(nama_pembayaran);
                        console.log(is_validasi_card);
                        console.log(no_bank.length);
                        if (is_validasi_card === '1'){
                            if (no_bank.length <= 0 || no_ref.length <= 0 || tgl_jth_tempo.length <= 0 ){
                                is_validasi = false;
                            }
                            
                        }
                        if (nama_pembayaran === 'CEK' || nama_pembayaran === 'GIRO' ){
                            if (no_bank.length <= 0 || no_ref.length <= 0 || tgl_jth_tempo.length <= 0 ){
                                is_true = false;
                            }
                         }
                       
                    });
                    console.log(is_true);
                    console.log(is_validasi);
                    if(!is_validasi){
                        Ext.Msg.show({
                            title: 'Error',
                            msg: 'Pembayaran Lewat Bank,No Bank,Nomer Warkat dan Tanggal Jatuh Tempo Harus Diisi !!',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK          
                        });
                        Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');
                        return;
                    }
                    if(!is_true){
                        Ext.Msg.show({
                            title: 'Error',
                            msg: 'Pembayaran Cek atau Giro,No Bank,Nomer Warkat dan Tanggal Jatuh Tempo Harus Diisi !!',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK          
                        });
                        Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');
                        return;
                    }
                    
//                    if (Ext.getCmp('ppp_total_bayar_dist').getValue() >  Ext.getCmp('ppp_rp_bayar_dist').getValue()) {
//                        Ext.Msg.show({
//                            title: 'Error',
//                            msg: 'Total Bayar Tidak Boleh Lebih Besar Dari Rp Bayar!',
//                            modal: true,
//                            icon: Ext.Msg.ERROR,
//                            buttons: Ext.Msg.OK,
//                        });
//                        return;
//                    }
                    if (Ext.getCmp('ppp_rp_bayar_dist').getValue() >  Ext.getCmp('ppp_rp_kurang_bayar_dist').getValue()) {
                        Ext.Msg.show({
                            title: 'Error',
                            msg: 'Total Bayar Tidak Boleh Lebih Besar Dari Rp Kurang Bayar!',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK,
                        });
                        return;
                    }   
                    if (Ext.getCmp('ppp_total_bayar_dist').getValue() >  Ext.getCmp('ppp_rp_bayar_dist').getValue()) {
                        if (Ext.getCmp('ibstt_sisa_bayar_dist').getValue() > '0' ){
                        Ext.Msg.show({
                            title: 'Error',
                            msg: 'Sisa Piutang Tidak Boleh > 0, Apabila Total Bayar > Total dibayar',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK,
                        });
                        return;
                        }
                    }  
//                    if(Ext.getCmp('eppp_nama_pembayaran_dist').getValue() ==='CEK' || Ext.getCmp('eppp_nama_pembayaran_dist').getValue() ==='GIRO'){
//                        if (Ext.getCmp('eppp_nomor_bank_dist').getValue() === '' || Ext.getCmp('eppp_nomor_ref_dist').getValue() === '' || Ext.getCmp('eppp_tgl_jth_tempo_dist').getValue() === '') {
//                        Ext.Msg.show({
//                            title: 'Error',
//                            msg: 'Pembayaran Cek atau Giro,No Bank,Nomer Warkat dan Tanggal Jatuh Tempo Harus Diisi !!',
//                            modal: true,
//                            icon: Ext.Msg.ERROR,
//                            buttons: Ext.Msg.OK
//                            
//                        });
//                        return;
//                        }
//                    }
                    var detailinput_bstt = new Array();
                   
                    strinput_bstt.each(function(node) {
                        detailinput_bstt.push(node.data);
                        
                    });

                    var detailbayarinput_bstt = new Array();
                    
                    strjenisbayarinput_bstt.each(function(node) {
                        detailbayarinput_bstt.push(node.data);
                        
                    });

                   
                    Ext.getCmp('input_bstt').getForm().submit({
                        url: '<?= site_url("input_bsttribusi/update_row") ?>',
                        scope: this,
                        params: {
                            detail: Ext.util.JSON.encode(detailinput_bstt),
                            detailbayar: Ext.util.JSON.encode(detailbayarinput_bstt),
                            _total_faktur: Ext.getCmp('ppp_rp_total_faktur_dist').getValue(),
                            _kurang_bayar: Ext.getCmp('ppp_rp_kurang_bayar_dist').getValue(),
                            _total_potongan: Ext.getCmp('ppp_rp_potongan_dist').getValue(),
                            _rp_bayar: Ext.getCmp('ppp_rp_bayar_dist').getValue(),
                             _total_bayar: Ext.getCmp('ppp_total_bayar_dist').getValue(),
                            _selisih: Ext.getCmp('ppp_rp_selisih_dist').getValue(),
                        },
                        waitMsg: 'Saving Data...',
                        success: function(form, action) {
                            var r = Ext.util.JSON.decode(action.response.responseText);
                            Ext.Msg.show({
                                title: 'Success',
                                msg: r.errMsg,
                                modal: true,
                                icon: Ext.Msg.INFO,
                                buttons: Ext.Msg.OK,
                                fn: function(btn) {
                                    if (btn == 'ok') {
                                        winpembayaranpiutangprint.show();
                                        Ext.getDom('pembayaranpiutangdistribusiprint').src = r.printUrl;
                                    }
                                }
                            });

                            clearinput_bstt();
                        },
                        failure: function(form, action) {
                            var fe = Ext.util.JSON.decode(action.response.responseText);
                            Ext.Msg.show({
                                title: 'Error',
                                msg: fe.errMsg,
                                modal: true,
                                icon: Ext.Msg.ERROR,
                                buttons: Ext.Msg.OK,
                                fn: function(btn) {
                                    if (btn == 'ok' && fe.errMsg == 'Session Expired') {
                                        window.location = '<?= site_url("auth/login") ?>';
                                    }
                                }
                            });

                        }
                    });

                }
            }, {
                text: 'Reset',
                handler: function() {
                    clearinput_bstt();
                }
            }]
    });
    var winpembayaranpiutangprint = new Ext.Window({
        id: 'id_winpembayaranpiutangprint',
        title: 'Print Pembayaran Piutang Distribusi',
        closeAction: 'hide',
        width: 900,
        height: 450,
        layout: 'fit',
        border: false,
        html:'<iframe style="width:100%;height:100%;" id="pembayaranpiutangdistribusiprint" src=""></iframe>'
    });
    penjualanpelunasanpiutang.on('afterrender', function() {
        this.getForm().load({
            url: '<?= site_url("penjualan_pelunasan_piutang/get_form") ?>',
            failure: function(form, action) {
                var de = Ext.util.JSON.decode(action.response.responseText);
                Ext.Msg.show({
                    title: 'Error',
                    msg: de.errMsg,
                    modal: true,
                    icon: Ext.Msg.ERROR,
                    buttons: Ext.Msg.OK,
                    fn: function(btn) {
                        if (btn == 'ok' && de.errMsg == 'Session Expired') {
                            window.location = '<?= site_url("auth/login") ?>';
                        }
                    }
                });
            }
        });
    });

    function clearinput_bstt() {
        Ext.getCmp('input_bstt').getForm().reset();
        Ext.getCmp('input_bstt').getForm().load({
            //url: '<?= site_url("penjualan_pelunasan_piutang/get_form") ?>',
            failure: function(form, action) {
                var de = Ext.util.JSON.decode(action.response.responseText);
                Ext.Msg.show({
                    title: 'Error',
                    msg: de.errMsg,
                    modal: true,
                    icon: Ext.Msg.ERROR,
                    buttons: Ext.Msg.OK,
                    fn: function(btn) {
                        if (btn == 'ok' && de.errMsg == 'Session Expired') {
                            window.location = '<?= site_url("auth/login") ?>';
                        }
                    }
                });
            }
        });
        strinput_bstt.removeAll();
        strjenisbayarinput_bstt.removeAll();
    }
</script>
