?<php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">
//Start Combo Pelanggan
var strcbfpumpelanggan = new Ext.data.ArrayStore({
        fields: ['kd_pelanggan'],
        data: []
    });

    var strgridfpumpelanggan = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_pelanggan', 'nama_pelanggan', 'tipe', 'alamat_npwp', 'npwp'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("faktur_pajak/search_pelanggan") ?>',
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

    var searchgridfpumpelanggan = new Ext.app.SearchField({
        store: strgridfpumpelanggan,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgridfpumpelanggan'
    });


    var gridfpumpelanggan = new Ext.grid.GridPanel({
        store: strgridfpumpelanggan,
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
            }, {
                header: 'Jenis Pelanggan',
                dataIndex: 'tipe',
                width: 100,
                sortable: true
            },{
                header: 'NPWP',
                dataIndex: 'npwp',
                width: 100,
                sortable: true
            },{
                header: 'Alamat NPWP',
                dataIndex: 'alamat_npwp',
                width: 100,
                sortable: true
            }],
        tbar: new Ext.Toolbar({
            items: [searchgridfpumpelanggan]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridfpumpelanggan,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('fpum_kd_pelanggan').setValue(sel[0].get('kd_pelanggan'));
                    Ext.getCmp('id_cbfpumpelanggan').setValue(sel[0].get('nama_pelanggan'));
                    Ext.getCmp('fpum_npwp').setValue(sel[0].get('npwp'));
                    Ext.getCmp('fpum_alamat_npwp').setValue(sel[0].get('alamat_npwp'));
                    menufpumpelanggan.hide();
                }
            }
        }
    });

    var menufpumpelanggan = new Ext.menu.Menu();
    menufpumpelanggan.add(new Ext.Panel({
        title: 'Pilih Pelanggan',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridfpumpelanggan],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menufpumpelanggan.hide();
                }
            }]
    }));

    Ext.ux.TwinCombofpumpelanggan = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            //load store grid
            strgridfpumpelanggan.load();
            menufpumpelanggan.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
                trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    menufpumpelanggan.on('hide', function() {
        var sf = Ext.getCmp('id_searchgridfpumpelanggan').getValue();
        if (sf !== '') {
            Ext.getCmp('id_searchgridfpumpelanggan').setValue('');
            searchgridfpumpelanggan.onTrigger2Click();
        }
    });

    var cbfpumpelanggan = new Ext.ux.TwinCombofpumpelanggan({
        fieldLabel: 'Pelanggan <span class="asterix">*</span>',
        id: 'id_cbfpumpelanggan',
        store: strcbfpumpelanggan,
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
// Start Combo Uang Muka
    var strcbfpumuangmuka = new Ext.data.ArrayStore({
        fields: ['no_faktur'],
        data : []
    });
	
    var strgridfpumuangmuka = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['no_bayar', 'tgl_bayar','rp_bayar'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("faktur_pajak/search_uang_muka") ?>',
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
	
    strgridfpumuangmuka.on('load', function(){
        Ext.getCmp('id_searchgridfpuangmuka').focus();
    });
	
    var searchgridfpuangmuka = new Ext.app.SearchField({
        store: strgridfpumuangmuka,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE			
        },
        width: 350,
        id: 'id_searchgridfpuangmuka'
    });
	
	
    var gridfpuangmuka = new Ext.grid.GridPanel({
        store: strgridfpumuangmuka,
        stripeRows: true,
        frame: true,
        border:true,
        columns: [{
                header: 'No Bayar',
                dataIndex: 'no_bayar',
                width: 120,
                sortable: true		
            
            },{
                header: 'Tanggal',
                dataIndex: 'tgl_bayar',
                width: 80,
                sortable: true         
            },{xtype: 'numbercolumn',
                header: 'Rp Uang Muka',
                dataIndex: 'rp_bayar',
                width: 100,
                format: '0,0',
                sortable: true          
            },{
                dataIndex: 'nama_pelanggan',
                hidden: true         
            }],
        tbar: new Ext.Toolbar({
            items: [searchgridfpuangmuka]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridfpumuangmuka,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function(){			
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {				
                    Ext.getCmp('id_cbfpumuangmuka').setValue(sel[0].get('no_bayar'));
                    Ext.getCmp('fpum_tgl_bayar').setValue(sel[0].get('tgl_bayar'));
                    strfaktur_pajak_uang_muka.load({
                        params: {
                            no_bayar: sel[0].get('no_bayar')
                        }
                    });
                    menufpuangmuka.hide();
                   //cleartotalfaktur();
                }
            }
        }
    });
	
    var menufpuangmuka = new Ext.menu.Menu();
    menufpuangmuka.add(new Ext.Panel({
        title: 'Pilih Faktur',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridfpuangmuka],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menufpuangmuka.hide();
                }
            }]
    }));
    
    Ext.ux.TwinComboFakturJual = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            //load store grid
            strgridfpumuangmuka.load({
                params: {
                    kd_pelanggan: Ext.getCmp('fpum_kd_pelanggan').getValue(),
                    }
            });
            menufpuangmuka.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
	
    menufpuangmuka.on('hide', function(){
        var sf = Ext.getCmp('id_searchgridfpuangmuka').getValue();
        if( sf != ''){
            Ext.getCmp('id_searchgridfpuangmuka').setValue('');
            searchgridfpuangmuka.onTrigger2Click();
        }
    });
	
    var cbfpumuangmuka = new Ext.ux.TwinComboFakturJual({
        fieldLabel: 'No Bayar <span class="asterix">*</span>',
        id: 'id_cbfpumuangmuka',
        store: strcbfpumuangmuka,
        mode: 'local',
        valueField: 'no_bayar',
        displayField: 'no_bayar',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'no_bayar',
        emptyText: 'Pilih No Bayar'
    });
    //End Combo Faktur Jual
     //NPWP PIHAK KE 3
  var strcbfpumnpwppihak = new Ext.data.ArrayStore({
        fields: ['kd_pelanggan'],
        data: []
    });

    var strgridfpumnpwp = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_pelanggan', 'nama_npwp', 'alamat_npwp','no_npwp','nama_pelanggan','kd_npwp'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("faktur_pajak/search_pelanggan_npwp") ?>',
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

    var searchgridfpumnpwp = new Ext.app.SearchField({
        store: strgridfpumnpwp,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgridfpumnpwp'
    });


    var gridfpumnpwp = new Ext.grid.GridPanel({
        store: strgridfpumnpwp,
        stripeRows: true,
        frame: true,
        border: true,
        columns: [{
                header: 'Nama Pelanggan',
                dataIndex: 'nama_pelanggan',
                width: 120,
                sortable: true
            }, {
                header: 'Nama NPWP',
                dataIndex: 'nama_npwp',
                width: 120,
                sortable: true
            },{
                header: 'No NPWP',
                dataIndex: 'no_npwp',
                width: 100,
                sortable: true
            },{
                header: 'Alamat NPWP',
                dataIndex: 'alamat_npwp',
                width: 100,
                sortable: true
            }],
        tbar: new Ext.Toolbar({
            items: [searchgridfpumnpwp]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridfpumnpwp,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('id_cbfpumnpwppihak').setValue(sel[0].get('nama_npwp'));
                    Ext.getCmp('fpum_npwp').setValue(sel[0].get('no_npwp'));
                    Ext.getCmp('fpum_alamat_npwp').setValue(sel[0].get('alamat_npwp'));
                    Ext.getCmp('fpum_kd_npwp').setValue(sel[0].get('kd_npwp'));
                    menufpumnpwp.hide();
                }
            }
        }
    });

    var menufpumnpwp = new Ext.menu.Menu();
    menufpumnpwp.add(new Ext.Panel({
        title: 'Pilih Nama NPWP',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 450,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridfpumnpwp],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menufpumnpwp.hide();
                }
            }]
    }));

    Ext.ux.TwinComboFpumNpwp = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            //load store grid
            strgridfpumnpwp.load({
                params: {
                    kd_pelanggan: Ext.getCmp('fpum_kd_pelanggan').getValue(),
                    }
            });
            menufpumnpwp.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
                trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    menufpumnpwp.on('hide', function() {
        var sf = Ext.getCmp('id_searchgridfpumnpwp').getValue();
        if (sf !== '') {
            Ext.getCmp('id_searchgridfpumnpwp').setValue('');
            searchgridfpumnpwp.onTrigger2Click();
        }
    });

    var cbfpumnpwppihak = new Ext.ux.TwinComboFpumNpwp({
        fieldLabel: 'NPWP Pihak Ke 3',
        id: 'id_cbfpumnpwppihak',
        store: strcbfpumnpwppihak,
        mode: 'local',
        valueField: 'nama_npwp',
        displayField: 'nama_npwp',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: true,
        editable: false,
        width: 300,
        hiddenName: 'nama_npwp',
        emptyText: 'Pilih Nama NPWP'
    });
    //End NPWP PIHAK KE 3
// Header Faktur Pajak
var headerfaktur_pajak_uang_muka = {
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
                        fieldLabel: 'No Faktur Pajak <span class="asterix">*</span>',
                        name: 'no_faktur_pajak',
                        readOnly:false,
                        allowBlank: false,
                        id: 'fpum_no_faktur',                
                        anchor: '90%',
                        minLength: 16,
                        maxLength: 19,
                        listeners: {
                            'blur':function(){
                                var no_faktur = this.getValue();
                                if(no_faktur.length == 16){
                                    
                                    no_faktur = no_faktur.replace("-","");
                                    no_faktur = no_faktur.replace(".","");
                                    
                                    console.log(no_faktur);
                                    Ext.getCmp('fpum_no_faktur').setValue(no_faktur.substring(0, 3) + '.' + 
                                    no_faktur.substring(3, 6) + '-' + no_faktur.substring(6, 8) + '.' + no_faktur.substring(8, 16));
                                }
                            }
                        }
                    },cbfpumpelanggan,cbfpumuangmuka,
                     {
                        xtype: 'textfield',
                        fieldLabel: 'Tgl Bayar',
                        name: 'tgl_bayar',
                        id: 'fpum_tgl_bayar', 
                        format: 'd-m-Y',
                        fieldClass:'readonly-input',
                        emptyText: 'Tgl Bayar',
                        anchor: '90%'
                    },{
                        xtype: 'textfield',
                        hidden : true,
                        name: 'kd_pelanggan',
                        readOnly:true,
                        fieldClass:'readonly-input',
                        id: 'fpum_kd_pelanggan',                
                        anchor: '90%',
                        value:''
             }]
           },{
                columnWidth: .5,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: { labelSeparator: ''},
                items: [cbfpumnpwppihak,{
                        xtype: 'textfield',
                        name: 'kd_npwp',
                        readOnly:true,
                        fieldClass:'readonly-input',
                        id: 'fpum_kd_npwp',                
                        width:300,
                        hidden : true,
                        value:'' 
                        
                    },{
                        xtype: 'textfield',
                        fieldLabel: 'NPWP',
                        name: 'npwp',
                        readOnly:true,
                        fieldClass:'readonly-input',
                        id: 'fpum_npwp',                
                        anchor: '90%',
                        value:'' 
                        
                    },{
                        xtype: 'textarea',
                        fieldLabel: 'Alamat',
                        name: 'Alamat',
                        readOnly:true,
                        fieldClass:'readonly-input',
                        id: 'fpum_alamat_npwp',                
                        anchor: '90%',
                        value:''
             }]
           }] 
    };
 // End Header 
 
var strfaktur_pajak_uang_muka = new Ext.data.Store({
        autoSave: false,
        reader: new Ext.data.JsonReader({
            fields: [
                {name: 'no_bayar', allowBlank: false, type: 'text'},
                {name: 'tgl_bayar', allowBlank: false, type: 'text'},
                {name: 'no_so', allowBlank: false, type: 'text'},
                {name: 'rp_jumlah', allowBlank: false, type: 'int'},
                {name: 'rp_uang_muka', allowBlank: false, type: 'int'},
                {name: 'rp_faktur_net', allowBlank: false, type: 'int'},
                {name: 'rp_ppn', allowBlank: false, type: 'int'},
                {name: 'rp_uang_muka', allowBlank: false, type: 'int'},
                {name: 'rp_dpp', allowBlank: false, type: 'int'}
                ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("faktur_pajak/search_uang_muka_detail") ?>',
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
// strfaktur_pajak_uang_muka.on('load', function(){
//        var jumlah = 0;
//        var jumlah_grid = 0;
//        var jumlah_net = 0;
//        var ppn = 0;
//        var grand_total = 0;
//		
//        strfaktur_pajak_uang_muka.each(function(node){			
//            jumlah += (node.data.rp_total);
//            
//        });
//
//        jumlah = Math.round(jumlah);
//        jumlah_net = parseInt(jumlah) - Ext.getCmp('pfj_potongan').getValue();
//        jumlah_net = Math.round(jumlah_net);
//        var ppn = (parseInt(jumlah_net)) * Ext.getCmp('pfj_ppn').getValue()/ 100;
//        ppn = Math.round(ppn);
//        var grand_total = parseInt(jumlah_net) + parseInt(ppn);
//        grand_total = Math.round(grand_total);
//	
//        Ext.getCmp('pfj_rp_jumlah').setValue(jumlah);
//        Ext.getCmp('pfj_jumlah_net').setValue(jumlah_net);
//        Ext.getCmp('pfj_rp_ppn').setValue(ppn);
//        Ext.getCmp('pfj_total_faktur').setValue(grand_total);
//        
//    });

//var editorfakturpenjualan = new Ext.ux.grid.RowEditor({
//        saveText: 'Update'
//    });
    
var gridfaktur_pajak_uang_muka = new Ext.grid.GridPanel({
        store: strfaktur_pajak_uang_muka,
        stripeRows: true,
        height: 250,
        frame: true,
        border:true,
        //plugins: [editorfakturpenjualan],
        columns: [{
                header: 'No SO ',
                dataIndex: 'no_so',
                width: 120,
                format: '0,0'
                },{xtype: 'numbercolumn',
                header: 'Rp SO ',
                dataIndex: 'rp_jumlah',
                width: 120,
                format: '0,0'
                }				
                 ,{xtype: 'numbercolumn',
                header: 'Uang Muka',
                dataIndex: 'rp_uang_muka',
                width: 200,
                sortable: true,
                format: '0,0'
            
            },{xtype: 'numbercolumn',
                header: 'Rp DPP',
                dataIndex: 'rp_dpp',
                width: 70,
                format: '0,0'
            },{xtype: 'numbercolumn',
                header: 'PPN',
                dataIndex: 'rp_ppn',
                width: 70,
                format: '0,0'
            }]
			
			
    });
var faktur_pajak_uang_muka = new Ext.FormPanel({
        id: 'faktur_pajak_uang_muka',
        border: false,
        frame: true,
        autoScroll:true,        
        bodyStyle:'padding-right:20px;',
        labelWidth: 130,
        items: [{
                bodyStyle: {
                    margin: '0px 0px 15px 0px'
                },                  
                items: [headerfaktur_pajak_uang_muka]
            },gridfaktur_pajak_uang_muka
          ],  
        buttons: [{
                text: 'Save',
                formBind: true,
                handler: function(){
                    
                    var gridfaktur_pajak_uang_muka = new Array();				
                    strfaktur_pajak_uang_muka.each(function(node){
                        gridfaktur_pajak_uang_muka.push(node.data)
                    });
                    Ext.getCmp('faktur_pajak_uang_muka').getForm().submit({
                        url: '<?= site_url("faktur_pajak/update_row_uang_muka") ?>',
                        scope: this,
                        params: {
                            detail: Ext.util.JSON.encode(gridfaktur_pajak_uang_muka)
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
									
                                    winfaktur_pajak_uang_mukaprint.show();
                                    Ext.getDom('faktur_pajak_uang_mukaprint').src = r.printUrl;
                                }
                            });			            
			            
                            clearfaktur_pajak_uang_muka();						
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
                    clearfaktur_pajak_uang_muka();
                }
            }
            ]
    });
    var winfaktur_pajak_uang_mukaprint = new Ext.Window({
        id: 'id_winfaktur_pajak_uang_mukaprint',
        title: 'Print Faktur Pajak',
        closeAction: 'hide',
        width: 900,
        height: 450,
        layout: 'fit',
        border: false,
        html:'<iframe style="width:100%;height:100%;" id="faktur_pajak_uang_mukaprint" src=""></iframe>'
    });
    
    function clearfaktur_pajak_uang_muka(){
        Ext.getCmp('faktur_pajak_uang_muka').getForm().reset();
        Ext.getCmp('faktur_pajak_uang_muka').getForm().load({
            
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
        strfaktur_pajak_uang_muka.removeAll();
    }
    
</script>