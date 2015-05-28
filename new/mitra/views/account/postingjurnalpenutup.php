<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>

<script type="text/javascript">
    var strpjpakuntrx=new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [ 
                'kd_transaksi',
                'nama_transaksi'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("account_master_jp/get_rows") ?>',
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
    var cmb_pjp= new Ext.form.ComboBox({
        fieldLabel:		'Nama Transaksi',
        mode:           'local',
        value:          '',
        triggerAction:  'all',
        forceSelection: true,
        editable:       false,
        name:           'nama_transaksi',
        id:           	'pjp_nama_transaksi',
        hiddenName:     'kd_transaksi',
        displayField:   'nama_transaksi',
        valueField:     'kd_transaksi',
        anchor:			'90%',
        store:  strpjpakuntrx,
        listeners:{
            select: function(combo, records) {
                var vidtrx = this.getValue();                
                strentryjp.reload({params:{query:vidtrx}});
            }
        }
    })
    var headerentryjp = {
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
                        fieldLabel: 'No.Posting',
                        name: 'kd_postingjp',
                        readOnly:true,
                        fieldClass:'readonly-input',
                        id: 'pjp_kd_posting',                
                        anchor: '90%',
                        value:''
                    }, {
                        xtype: 'datefield',
                        fieldLabel: 'Tanggal Transaksi',
                        name: 'tgl_posting',
                        allowBlank:false,   
                        format:'Y-m-d', 
                        id: 'pjp_tgl_posting',                
                        anchor: '90%'
                    }
                    ,cmb_pjp
                ]
            }, {
                columnWidth: .5,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: { labelSeparator: ''},
                items: [  {
                        xtype: 'textfield',
                        fieldLabel: 'Referensi',
                        name: 'referensi',				
                        id: 'pjp_referensi',                
                        anchor: '90%',
                        value: ''
                    }, {
                        xtype: 'textarea',
                        fieldLabel: 'Keterangan',
                        name: 'keterangan',				
                        id: 'pjp_keterangan',                
                        anchor: '90%',
                        value: ''
                    }]
            }]
    }
     var footerentryjp = {
        layout: 'column',
        border: false,
        items: [{
                columnWidth: .3,
                layout: 'form',
                border: false,
                labelWidth: 80,
                defaults: { labelSeparator: ''},
                items: [{
                        xtype: 'numberfield',
                        fieldLabel: 'Total Debet',
                        name: 't_debet',
                        format:'0,0',
                        readOnly:true,
                        fieldClass:'readonly-input',
                        id: 'pjp_t_debet',                
                        anchor: '90%',
                        value:'0'
                    } ]
            }, {
                columnWidth: .3,
                layout: 'form',
                border: false,
                labelWidth: 80,
                defaults: { labelSeparator: ''},
                items: [{
                        xtype: 'numberfield',
                        fieldLabel: 'Total Kredit',
                        name: 't_kredit',
                        //                        allowBlank:false, 
                        readOnly:true,
                        fieldClass:'readonly-input',
                        format:'0,0', 
                        id: 'pjp_t_kredit',                
                        anchor: '90%',
                        value: '0'
                    } ]
            }, {
                columnWidth: .3,
                layout: 'form',
                border: false,
                labelWidth: 80,
                defaults: { labelSeparator: ''},
                items: [ {
                        xtype: 'numberfield',
                        fieldLabel: 'Selisih',
                        name: 't_selisih',
                        readOnly:true,
                        fieldClass:'readonly-input',
                        //                        allowBlank:false,   
                        format:'0,0', 
                        id: 'pjp_t_selisih',                
                        anchor: '90%',
                        value: '0'
                    }]
            }]
    }
    var strentryjp = new Ext.data.Store({
        autoSave:false,
        reader: new Ext.data.JsonReader({
            fields: [
                {name: 'kd_akun', allowBlank: false, type: 'string'},
                {name: 'nama', allowBlank: false, type: 'string'},
                {name: 'dk_akun', allowBlank: false, type: 'string'},
                {name: 'dk_transaksi', allowBlank: false, type: 'string'},                           
                {name: 'debet', allowBlank: false, type: 'int'},
                {name: 'kredit', allowBlank: false, type: 'int'}
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("account_posting_jp/get_rows_akun") ?>',
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
        ,
        writer: new Ext.data.JsonWriter(
        {
            encode: true,
            writeAllFields: true
        })
    });
    
     function set_total_jp(){		
        var totaldebet=0;
        var totalkredit=0;
        var totalselisih=0;
                
        strentryjp.each(function(node){			
            totaldebet += parseInt(node.data.debet);
            totalkredit += parseInt(node.data.kredit);
        });
        totalselisih=totaldebet-totalkredit;
        Ext.getCmp('pjp_t_debet').setValue(totaldebet);
        Ext.getCmp('pjp_t_kredit').setValue(totalkredit);
        Ext.getCmp('pjp_t_selisih').setValue(totalselisih);
                
    };
    strentryjp.on('update', function(){
        set_total_jp();
		
		
    });
    strentryjp.on('remove',  function(){
        set_total_jp();
		
    });
    //==============
    var editorentryjp = new Ext.ux.grid.RowEditor({
        saveText: 'Update'
    });
    
	
    var gridentryjp = new Ext.grid.GridPanel({
        store: strentryjp,
        stripeRows: true,
        height: 300,
        frame: true,
        border:true,
        plugins: [editorentryjp],        
        columns: [{
                //            xtype: 'numbercolumn',
                header: 'Kode Akun',
                dataIndex: 'kd_akun',
                width: 200,
                format: '0',
                sortable: true,	
                editor: new Ext.form.TextField({                
                    readOnly: true,
                    id: 'eg_pjp_kd_akun'
                })               	
			
            },{
                header: 'Nama Akun',
                dataIndex: 'nama',
                width: 400,
                editor: new Ext.form.TextField({                
                    readOnly: true,
                    id: 'eg_pjp_nama_akun'
                })
            },{
                header: 'D/K Akun',
                dataIndex: 'dk_akun',
                width: 120,
                hidden:true,
                editor: new Ext.form.TextField({                
                    readOnly: true,
                    id: 'eg_pjp_dkakun'
                })
            },{
                header: 'D/K',
                dataIndex: 'dk_transaksi',
                width: 120,
                editor: new Ext.form.TextField({                
                    readOnly: true,
                    id: 'eg_pjp_dktransaksi'
                })
            },{
                xtype: 'numbercolumn',
                header: 'Debet',
                dataIndex: 'debet',			
                width: 80,
                sortable: true,
                format: '0,0',
                editor: {
                    xtype: 'numberfield',
                    id: 'eg_pjp_debet',
                    allowBlank: true
                    ,
                    listeners:{
                        'render': function(c) {
                            c.getEl().on('keyup', function() {
                                var dk = Ext.getCmp('eg_pjp_dktransaksi').getValue();                    						
                                if (dk==='K'){
                                    Ext.getCmp('eg_pjp_debet').setValue(0);
                                }
                    						
                    						
                            }, c);
                        }}
                
                }
                
            },{
                xtype: 'numbercolumn',
                header: 'Kredit',
                dataIndex: 'kredit',			
                width: 80,
                sortable: true,
                format: '0,0',
                editor: {
                    xtype: 'numberfield',
                    id: 'eg_pjp_kredit',
                    allowBlank: true,
                    listeners:{
                        'render': function(c) {
                            c.getEl().on('keyup', function() {
                                var dk = Ext.getCmp('eg_pjp_dktransaksi').getValue();                    						
                                if (dk==='D'){
                                    Ext.getCmp('eg_pjp_kredit').setValue(0);
                                }                    						
                    						
                            }, c);
                        }}

                }
            }]
    });
    var postingjurnalpenutup_form = new Ext.FormPanel({
        id: 'postingjurnalpenutup',
        border: false,
        frame: true,
        autoScroll:true, 
        monitorValid: true,       
        bodyStyle:'padding-right:20px;',
        labelWidth: 130,
        items: [
            {
                bodyStyle: {
                    margin: '0px 0px 15px 0px'
                },                  
                items: [headerentryjp]
            }
            ,
            gridentryjp
            ,{
                bodyStyle: {
                    margin: '5px 0px 15px 0px'
                },                  
                items: [footerentryjp]
            }
        ],
        buttons: [{
                text: 'Save',
                formBind: true,
                handler: function(){ 
                if(Ext.getCmp('pjp_t_selisih').getValue()!='0'){
                            Ext.Msg.show({
                                title: 'Error',
                                msg: 'Masih ada Selisih Total Debet dan Total Kredit',
                                modal: true,
                                icon: Ext.Msg.ERROR,
                                buttons: Ext.Msg.OK
//                                fn: function(btn){
//                                    if (btn == 'ok' ) {
//                                        window.location = '<?= site_url("auth/login") ?>';
//                                    }
//                                }
                            });	
                            return;
                }
                    var detailevr = new Array();				
                    strentryjp.each(function(node){
                        detailevr.push(node.data)
                    });
                    Ext.getCmp('postingjurnalpenutup').getForm().submit({
                        url: '<?= site_url("account_posting_jp/update_row") ?>',
                        scope: this,
                        params: {
                            data: Ext.util.JSON.encode(detailevr)
					  												
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
                            clearPjp();				
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
                    clearPjp();
                   
                
                }
                
            }],
        listeners:{
            afterrender:function(){                
                this.getForm().load({
                    url: '<?= site_url("account_posting_jp/get_form") ?>',
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
                strpjpakuntrx.reload();
            }
        }
    });
    function clearPjp(){
        Ext.getCmp('postingjurnalpenutup').getForm().reset();
        Ext.getCmp('postingjurnalpenutup').getForm().load({
            url: '<?= site_url("account_posting_jp/get_form") ?>',
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
        strentryjp.removeAll();
    }
</script>