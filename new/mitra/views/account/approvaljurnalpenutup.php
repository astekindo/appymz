<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">	
    
    var strapprovaljp_akun = new Ext.data.Store({
//        autoLoad:true,
        reader: new Ext.data.JsonReader({
            fields: [        
                
                'kd_postingjp',                 
                'kd_akun', 
                'nama',
                'dk_akun',
                'dk_transaksi', 
                {name: 'debet', type: 'int'},
                {name: 'kredit', type: 'int'}
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("account_app_jp/get_rows_akun") ?>',
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
    function set_total_ajp(){		
        var totaldebet=0;
        var totalkredit=0;
//        var totalselisih=0;
                
        strapprovaljp_akun.each(function(node){			
            totaldebet += parseInt(node.data.debet);
            totalkredit += parseInt(node.data.kredit);
        });
//        totalselisih=totaldebet-totalkredit;
        Ext.getCmp('ajp_t_debet').setValue(totaldebet);
        Ext.getCmp('ajp_t_kredit').setValue(totalkredit);
//        Ext.getCmp('evr_t_selisih').setValue(totalselisih);
                
    };
    strapprovaljp_akun.on('load', function(){
        set_total_ajp();
		
		
    });
    
    strapprovaljp_akun.on('update', function(){
        set_total_ajp();
		
		
    });
    strapprovaljp_akun.on('remove',  function(){
        set_total_ajp();
		
    });
    var gridjpakun = new Ext.grid.GridPanel({
        //        flex:2, 
        region:'east',
        split:true,
        id: 'idgridjp_akun',
        store: strapprovaljp_akun,
        title:'Detail Voucher',
        stripeRows: true,
        width:420,
        height: 250,		
        border:true,
        frame:true,
        columns: [            
            {
                header: "Kode Akun",
                dataIndex: 'kd_akun',
                sortable: true,
                width: 50
            },{
                header: "Nama Akun",
                dataIndex: 'nama',
                sortable: true,
                width: 150
            },{
                header: "Akun D/K",
                dataIndex: 'dk_akun',
                sortable: true,
                width: 80,hidden:true
            },{
                header: "Transaksi D/K",
                dataIndex: 'dk_transaksi',
                sortable: true,          
                width: 80,hidden:true
            },{
                xtype: 'numbercolumn',
                header: "Debet",
                dataIndex: 'debet',
                sortable: true,  
                format: '0,0',
                width: 80
            },{
                xtype: 'numbercolumn',
                header: "Kredit",
                dataIndex: 'kredit',
                sortable: true,  
                format: '0,0',
                width: 80
            }
        ],
        bbar:[ 'Total Debet :',{xtype:'numberfield',id: 'ajp_t_debet',fieldClass:'number',readOnly:true },
            'Total Kredit :',{xtype:'numberfield',id: 'ajp_t_kredit',fieldClass:'number',  readOnly:true }
    ]
        //		
    });
    

    
    
    
    
    var strapprovaljp = new Ext.data.Store({
//        autoLoad:true,
        reader: new Ext.data.JsonReader({
            fields: [                
                {name: 'approval', type: 'bool'},
                'kd_postingjp', 
                'tgl_posting', 
                'kd_transaksi', 
                'nama_transaksi', 
                'keterangan', 
                'referensi'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("account_app_jp/get_rows") ?>',
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
    var cbGridjp = new Ext.grid.CheckboxSelectionModel({
        id:'id_sel_jp',
        singleSelect:false 
    }
);    
    var checkApprovaljp=new Ext.grid.CheckColumn({
        header:'Approval',      
        id:'id_jp_approval',       
        dataIndex: 'approval',             
        width: 55
      
    });
    
    var colmodeljp=new Ext.grid.ColumnModel({
        columns:[
           checkApprovaljp,
            {
                header:'No.Posting',                          
                dataIndex: 'kd_postingjp',
                width: 80                
            },{header:'Tanggal',             
                dataIndex: 'tgl_posting',
                width: 80                
            }
            ,{header:'Kode',             
                dataIndex: 'kd_transaksi',
                width: 80
//                ,hidden:true                
            },{header:'Nama Transaksi',             
                dataIndex: 'nama_transaksi',
                width: 80
//                ,hidden:true                
            }
            ,{header:'Keterangan',             
                dataIndex: 'keterangan',
                width: 200                
            },{header:'Referensi',             
                dataIndex: 'referensi',
                width: 80                
            }
           
            
        ]
        
    });
     var searchjp = new Ext.app.SearchField({
        store: strapprovaljp,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        id: 'idsearchjp'
    });
    
    var tbjp = new Ext.Toolbar({
        items: [searchjp]
    });
    
    var lay_approvaljp =  new Ext.grid.EditorGridPanel({
        region:'center',
        store: strapprovaljp,
        cm: colmodeljp,        
        width: 580,
//        height: 300,        
        title: 'Jurnal Penutup To Approve',
        frame: true,
        stripeRows: true,
                sm:cbGridjp,
        loadMask:true,
        // specify the check column plugin on the grid so the plugin is initialized
        plugins:[checkApprovaljp] ,
        clicksToEdit: 1,
        tb:tbjp,
        listeners:{
            show:function(){
                
            },
            'rowclick': function(){			
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                var kdtrans=null;   
                if (sel.length > 0) {
                    kdtrans=sel[0].get('kd_postingjp'); 
                }
                strapprovaljp_akun.reload({params:{query:kdtrans}});				
            }
        },
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strapprovaljp,
            displayInfo: true
        })
    });
    
    var approvaljp_frm = new Ext.FormPanel({
        id: 'approvaljurnalpenutup',
        border: false,
        frame: true,
        autoScroll:true,
        //		tbar: tbtransaksimenu,		
        bodyStyle:'padding-right:20px;',
        labelWidth: 130,
        layout: 'border',
        items:[
//            panelavr
            lay_approvaljp
//            ,panelavrakun
            ,gridjpakun
        ],
        buttons:[{
                text: 'Approve',
                formBind: true,
                handler: function(){    
                                   
                    var dataapvr = new Array();				
                    strapprovaljp.each(function(node){
                        if(node.data.approval){
                            dataapvr.push(node.data);    
                        }                        
                    });
                    
                    if(dataapvr.length==0){
                        Ext.Msg.show({
                            title: 'Execute Approval Voucher',
                            msg: 'No Selected Data To Approve',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK,
                            fn: function(btn){
                                if (btn == 'ok' ) {
                                    return;
                                }
                            }
                        });
                        
                    }
                    Ext.getCmp('approvaljurnalpenutup').getForm().submit({
                        url: '<?= site_url("account_app_jp/update_row") ?>',
                        scope: this,
                        params: {
                            data: Ext.util.JSON.encode(dataapvr)
					  												
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
                            clearJp();				
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
            ,
                {
                text: 'Reset',
                handler: function(){
                    clearJp();	
                }
            }
            ]
    });
    function clearJp(){
        Ext.getCmp('approvaljurnalpenutup').getForm().reset();        
        strapprovaljp.reload();
        strapprovaljp_akun.removeAll();
    }
</script>