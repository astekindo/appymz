?<php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">
//Start Combo Pelanggan
var strcbfppelanggan = new Ext.data.ArrayStore({
        fields: ['kd_pelanggan'],
        data: []
    });

    var strgridfppelanggan = new Ext.data.Store({
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

    var searchgridfppelanggan = new Ext.app.SearchField({
        store: strgridfppelanggan,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgridfppelanggan'
    });


    var gridfppelanggan = new Ext.grid.GridPanel({
        store: strgridfppelanggan,
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
            items: [searchgridfppelanggan]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridfppelanggan,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('fp_kd_pelanggan').setValue(sel[0].get('kd_pelanggan'));
                    Ext.getCmp('id_cbfppelanggan').setValue(sel[0].get('nama_pelanggan'));
                    Ext.getCmp('fp_npwp').setValue(sel[0].get('npwp'));
                    Ext.getCmp('fp_alamat_npwp').setValue(sel[0].get('alamat_npwp'));
                    //Ext.getCmp('id_cbfpnpwppihak').setValue('');
                    menufppelanggan.hide();
                }
            }
        }
    });

    var menufppelanggan = new Ext.menu.Menu();
    menufppelanggan.add(new Ext.Panel({
        title: 'Pilih Pelanggan',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridfppelanggan],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menufppelanggan.hide();
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
            strgridfppelanggan.load();
            menufppelanggan.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
                trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    menufppelanggan.on('hide', function() {
        var sf = Ext.getCmp('id_searchgridfppelanggan').getValue();
        if (sf !== '') {
            Ext.getCmp('id_searchgridfppelanggan').setValue('');
            searchgridfppelanggan.onTrigger2Click();
        }
    });

    var cbfppelanggan = new Ext.ux.TwinCombofppelanggan({
        fieldLabel: 'Pelanggan <span class="asterix">*</span>',
        id: 'id_cbfppelanggan',
        store: strcbfppelanggan,
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
// Start Combo Jual
    var strcbfpfakturjual = new Ext.data.ArrayStore({
        fields: ['no_faktur'],
        data : []
    });
	
    var strgridfpfakturjual = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['no_faktur', 
                     'tgl_faktur', 
                     'rp_faktur', 
                     'rp_potongan',
                     'rp_faktur_net',
                     'rp_ppn',
                     'rp_dpp',
                     'rp_uang_muka',
                     'nama_npwp',
                     'no_npwp',
                     'alamat_npwp',
                     'kd_npwp'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("faktur_pajak/search_faktur_jual") ?>',
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
	
    strgridfpfakturjual.on('load', function(){
        Ext.getCmp('id_searchgridfpfakturjual').focus();
    });
	
    var searchgridfpfakturjual = new Ext.app.SearchField({
        store: strgridfpfakturjual,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE			
        },
        width: 350,
        id: 'id_searchgridfpfakturjual'
    });
	
	
    var gridfpfakturjual = new Ext.grid.GridPanel({
        store: strgridfpfakturjual,
        stripeRows: true,
        frame: true,
        border:true,
        columns: [{
                header: 'No Faktur',
                dataIndex: 'no_faktur',
                width: 120,
                sortable: true		
            
            },{
                header: 'Tanggal',
                dataIndex: 'tgl_faktur',
                width: 80,
                sortable: true         
            },{xtype: 'numbercolumn',
                header: 'Jumlah Faktur',
                dataIndex: 'rp_faktur',
                width: 100,
                format: '0,0',
                sortable: true          
            },{xtype: 'numbercolumn',
                header: 'Potongan',
                dataIndex: 'rp_potongan',
                width: 100,
                format: '0,0',
                sortable: true          
            },{xtype: 'numbercolumn',
                header: 'Uang Muka',
                dataIndex: 'rp_uang_muka',
                width: 100,
                format: '0,0',
                sortable: true          
            },{xtype: 'numbercolumn',
                header: 'Faktur Net',
                dataIndex: 'rp_faktur_net',
                width: 100,
                format: '0,0',
                sortable: true          
            },{xtype: 'numbercolumn',
                header: 'Rp DPP',
                dataIndex: 'rp_dpp',
                width: 100,
                format: '0,0',
                sortable: true          
            },{xtype: 'numbercolumn',
                header: 'PPN',
                dataIndex: 'rp_ppn',
                width: 100,
                format: '0,0',
                sortable: true          
            },{
                dataIndex: 'nama_pelanggan',
                hidden: true         
            },{
                dataIndex: 'nama_npwp',
                hidden: true         
            }],
        tbar: new Ext.Toolbar({
            items: [searchgridfpfakturjual]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridfpfakturjual,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function(){			
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {				
                    Ext.getCmp('id_cbfpfakturjual').setValue(sel[0].get('no_faktur'));
                    Ext.getCmp('fp_tgl_faktur').setValue(sel[0].get('tgl_faktur'));
                    if (sel[0].get('nama_npwp') === null ){}
                    else {
                        Ext.getCmp('fp_nama_npwp').setValue(sel[0].get('nama_npwp'));
                        Ext.getCmp('fp_kd_npwp').setValue(sel[0].get('kd_npwp'));
                        Ext.getCmp('fp_npwp').setValue(sel[0].get('no_npwp'));
                        Ext.getCmp('fp_alamat_npwp').setValue(sel[0].get('alamat_npwp'));
                    }
//                    Ext.getCmp('pfp_total_faktur').setValue(sel[0].get('rp_total_faktur'));
                    strfakturpajak.load({
                        params: {
                            no_faktur: sel[0].get('no_faktur')
                        }
                    });
                    menufpfakturjual.hide();
                   //cleartotalfaktur();
                }
            }
        }
    });
	
    var menufpfakturjual = new Ext.menu.Menu();
    menufpfakturjual.add(new Ext.Panel({
        title: 'Pilih Faktur',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridfpfakturjual],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menufpfakturjual.hide();
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
            strgridfpfakturjual.load({
                params: {
                    kd_pelanggan: Ext.getCmp('fp_kd_pelanggan').getValue(),
                    }
            });
            menufpfakturjual.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
	
    menufpfakturjual.on('hide', function(){
        var sf = Ext.getCmp('id_searchgridfpfakturjual').getValue();
        if( sf != ''){
            Ext.getCmp('id_searchgridfpfakturjual').setValue('');
            searchgridfpfakturjual.onTrigger2Click();
        }
    });
	
    var cbfpfakturjual = new Ext.ux.TwinComboFakturJual({
        fieldLabel: 'Faktur Jual <span class="asterix">*</span>',
        id: 'id_cbfpfakturjual',
        store: strcbfpfakturjual,
        mode: 'local',
        valueField: 'no_faktur',
        displayField: 'no_faktur',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'no_faktur',
        emptyText: 'Pilih Faktur'
    });
    //End Combo Faktur Jual
   
// Header Faktur Pajak
var headerfakturpajak = {
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
                        id: 'fp_no_faktur',                
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
                                    Ext.getCmp('fp_no_faktur').setValue(no_faktur.substring(0, 3) + '.' + 
                                    no_faktur.substring(3, 6) + '-' + no_faktur.substring(6, 8) + '.' + no_faktur.substring(8, 16));
                                }
                            }
                        }
                    },cbfppelanggan,cbfpfakturjual,
                     {
                        xtype: 'textfield',
                        fieldLabel: 'Tgl Faktur',
                        name: 'tgl_faktur',
                        id: 'fp_tgl_faktur', 
                        format: 'd-m-Y',
                        fieldClass:'readonly-input',
                        emptyText: 'Tgl Faktur',
                        anchor: '90%'
                    },{
                        xtype: 'textfield',
                        hidden : true,
                        name: 'kd_pelanggan',
                        readOnly:true,
                        fieldClass:'readonly-input',
                        id: 'fp_kd_pelanggan',                
                        anchor: '90%',
                        value:''
             }]
           },{
                columnWidth: .5,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: { labelSeparator: ''},
                items: [{
                        fieldLabel: 'PKP Pihak Ke 3',
                        xtype: 'textfield',
                        name: 'nama_npwp',
                        readOnly:true,
                        fieldClass:'readonly-input',
                        id: 'fp_nama_npwp',                
                        width:300,
                        hidden : false,
                        value:'' 
                        
                    },{
                        xtype: 'textfield',
                        fieldLabel: 'NPWP',
                        name: 'npwp',
                        readOnly:true,
                        fieldClass:'readonly-input',
                        id: 'fp_npwp',                
                        width:300,
                        value:'' 
                        
                    },{
                        xtype: 'textarea',
                        fieldLabel: 'Alamat',
                        name: 'Alamat',
                        readOnly:true,
                        fieldClass:'readonly-input',
                        id: 'fp_alamat_npwp',                
                        width:300,
                        value:''
             },{
                        xtype: 'textfield',
                        name: 'kd_npwp',
                        readOnly:true,
                        fieldClass:'readonly-input',
                        id: 'fp_kd_npwp',                
                        width:300,
                        hidden : true,
                        value:'' 
                        
                    }]
           }] 
    };
 // End Header 
 
var strfakturpajak = new Ext.data.Store({
        autoSave: false,
        reader: new Ext.data.JsonReader({
            fields: [
                {name: 'no_faktur', allowBlank: false, type: 'text'},
                {name: 'tgl_faktur', allowBlank: false, type: 'text'},
                {name: 'rp_faktur', allowBlank: false, type: 'int'},
                {name: 'rp_total_faktur', allowBlank: false, type: 'int'},
                {name: 'rp_potongan', allowBlank: false, type: 'int'},
                {name: 'rp_faktur_net', allowBlank: false, type: 'int'},
                {name: 'rp_ppn', allowBlank: false, type: 'int'},
                {name: 'rp_uang_muka', allowBlank: false, type: 'int'},
                {name: 'rp_dpp', allowBlank: false, type: 'int'}
                ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("faktur_pajak/search_faktur_jual_detail") ?>',
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
// strfakturpajak.on('load', function(){
//        var jumlah = 0;
//        var jumlah_grid = 0;
//        var jumlah_net = 0;
//        var ppn = 0;
//        var grand_total = 0;
//		
//        strfakturpajak.each(function(node){			
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
    
var gridfakturpajak = new Ext.grid.GridPanel({
        store: strfakturpajak,
        stripeRows: true,
        height: 250,
        frame: true,
        border:true,
        //plugins: [editorfakturpenjualan],
        columns: [{xtype: 'numbercolumn',
                header: 'Jumlah Faktur  ',
                dataIndex: 'rp_faktur',
                width: 120,
                format: '0,0'
                }				
                 ,{xtype: 'numbercolumn',
                header: 'Potongan',
                dataIndex: 'rp_potongan',
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
                header: 'Total',
                dataIndex: 'rp_faktur_net',
                width: 120,
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
var fakturpajak = new Ext.FormPanel({
        id: 'fakturpajak',
        border: false,
        frame: true,
        autoScroll:true,        
        bodyStyle:'padding-right:20px;',
        labelWidth: 130,
        items: [{
                bodyStyle: {
                    margin: '0px 0px 15px 0px'
                },                  
                items: [headerfakturpajak]
            },gridfakturpajak
          ],  
        buttons: [{
                text: 'Save',
                formBind: true,
                handler: function(){
                    
                    var gridfakturpajak = new Array();				
                    strfakturpajak.each(function(node){
                        gridfakturpajak.push(node.data)
                    });
                    Ext.getCmp('fakturpajak').getForm().submit({
                        url: '<?= site_url("faktur_pajak/update_row") ?>',
                        scope: this,
                        params: {
                            detail: Ext.util.JSON.encode(gridfakturpajak)
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
									
                                    winfakturpajakprint.show();
                                    Ext.getDom('fakturpajakprint').src = r.printUrl;
                                }
                            });			            
			            
                            clearfakturpajak();						
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
                    clearfakturpajak();
                }
            }
            ]
    });
    var winfakturpajakprint = new Ext.Window({
        id: 'id_winfakturpajakprint',
        title: 'Print Faktur Pajak',
        closeAction: 'hide',
        width: 900,
        height: 450,
        layout: 'fit',
        border: false,
        html:'<iframe style="width:100%;height:100%;" id="fakturpajakprint" src=""></iframe>'
    });
    
    function clearfakturpajak(){
        Ext.getCmp('fakturpajak').getForm().reset();
        Ext.getCmp('fakturpajak').getForm().load({
            
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
        strfakturpajak.removeAll();
    }
    
</script>