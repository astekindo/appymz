<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">
    // combobox Jenis Pembayaran
    /*    
    var str_lmsk_cbjenisbyr = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['jenis_pembayaran'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("master_barang/get_satuan") ?>',
            method: 'POST'
        }),
            listeners: {
            load: function() {
                var r = new (str_lks_cbsatuan.recordType)({
                    'kd_satuan': '',
                    'nm_satuan': '-----'
                });
                str_lks_cbsatuan.insert(0, r);
            },
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });
    */
     var lmsk_cbjnsbayar = new Ext.form.ComboBox({
        fieldLabel: 'Jenis Pembayaran',
        id: 'id_lmsk_cbjnsbayar ',
        //store: str_lks_cbsatuan,
        //valueField: 'kd_satuan',
        //displayField: 'nm_satuan',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        anchor: '90%',
        //hiddenName: 'kd_satuan',
        emptyText: ''
       
    });
    var cboxpetugas = new Ext.form.Checkbox({
        //fieldLabel: 'Petugas Kasir',
        xtype: 'checkbox',
        boxLabel:'Petugas Kasir',
        name:'petugas_kasir',
        id:'cb_petugas',
        inputValue: '0',
        autoLoad : true,
        //checkboxAlign:left,
        anchor: '90%',
                        listeners:{
                                check: function(){
                                               /*
                                                var ppn = this.getValue();
                                                var pkp = Ext.getCmp('pcpob_pkp_supplier').getValue();
                                                if(ppn){
                                                        if(pkp == 'YA'){
                                                                Ext.getCmp('pcpob_ppn_persen').setValue('10');
                                                        }
                                                }else{
                                                        Ext.getCmp('pcpob_ppn_persen').setValue('0');
                                                }
                                                var sub_jumlah = Ext.getCmp('pcpob_sub_jumlah').getValue();	
                                                var ppn_rp = (Ext.getCmp('pcpob_ppn_persen').getValue() * sub_jumlah)/100;
                                                var grand_total = sub_jumlah + ppn_rp;						

                                                Ext.getCmp('pcpob_ppn_rp').setValue(ppn_rp);
                                                Ext.getCmp('pcpob_total').setValue(grand_total);
                                                var sisa_bayar = grand_total - Ext.getCmp('pcpob_dp').getValue();
                                                Ext.getCmp('pcpob_sisa_bayar').setValue(sisa_bayar);
                                                */
                                }
                        }
                })
    var headerlsetoran = {
        buttonAlign: 'left',
        layout: 'column',
        border: false,
        items: [{
            columnWidth: .9,
            layout: 'form',
            border: false,
            labelWidth: 50,
            //labelAlign:left,
            defaults: { labelSeparator: ''},
            items: [/*{
                        columnWidth: .4,
                        layout: 'form',
                        border: false,
                        labelWidth: 100,
                        
                        defaults: { labelSeparator: ''},
                        items:[
                                {                
                                xtype: 'radiogroup',
                                //columnWidth: [.5, .5],
                                allowBlank:false,
                                
                                items: [{
                                    boxLabel: 'Bulan',
                                    name: 'bulan',
                                    inputValue: '1',
                                    id: 'id_bulan'
                                }]}
                        ]
                }*/{		
                    layout: 'column',
                    items:[{
                        
                        columnWidth: .3,
                        layout: 'form',
                        border: false,
                        labelWidth: 100,
                        
                        defaults: { labelSeparator: ''},
                        items:[
                                {                
                                xtype: 'radiogroup',
                                //columnWidth: [.5, .5],
                                allowBlank:false,
                                
                                items: [{
                                    boxLabel: 'Bulan',
                                    name: 'bulan',
                                    inputValue: '1',
                                    id: 'id_bulan'
                                }]}
                        ]
                },{
                        columnWidth: .3,
                        layout: 'form',
                        border: false,
                        labelWidth: 100,
                        defaults: { labelSeparator: ''},
                        items:[{
                                xtype: 'datefield',
                                fieldLabel: '',
                                name: 'tgl',				
                                allowBlank:false,   
                                format:'m-Y',  
                                editable:false,           
                                id: 'tgl_setoran',                
                                anchor: '70%',
                                value: ''
                                 }
                        ]
                }
                

        ]
					
				
			}
            ,	{		
                    layout: 'column',
                    items:[{
                        
                        columnWidth: .3,
                        layout: 'form',
                        border: false,
                        labelWidth: 100,
                        
                        defaults: { labelSeparator: ''},
                        items:[
                                {                
                                xtype: 'radiogroup',
                                //columnWidth: [.5, .5],
                                allowBlank:false,
                                
                                items: [{
                                    boxLabel: 'Harian',
                                    name: 'harian',
                                    inputValue: '1',
                                    id: 'id_harian'
                                }]},cboxpetugas,lmsk_cbjnsbayar
                        ]
                },{
                        columnWidth: .3,
                        layout: 'form',
                        border: false,
                        labelWidth: 100,
                        defaults: { labelSeparator: ''},
                        items:[{
                                xtype: 'datefield',
                                fieldLabel: 'Dari Tgl ',
                                name: 'dari_tgl',				
                                allowBlank:false,   
                                format:'d-m-Y',  
                                editable:false,           
                                id: 'id_dari_tgl_setoran',                
                                anchor: '70%',
                                value: ''
                                 }
                        ]
                },
                {
                        columnWidth: .3,
                        layout: 'form',
                        border: false,
                        labelWidth: 100,
                        defaults: { labelSeparator: ''},
                        items:[
                                {
                                xtype: 'datefield',
                                fieldLabel: 'Sampai Tgl',
                                name: 'sampai_tgl',		
                                allowBlank:false,   
                                editable:false,                
                                format:'d-m-Y',  
                                id: 'id_smp_tgl_setoran',										
                                anchor: '70%',										
                                value: ''										
                                }
                        ]
                }
                

        ]
					
				
			}]
        }
        ],
        buttons: [{
            text: 'Filter Data',
            handler: function(){
               strpembeliancreatepobonus.load({
                        params:{
                            start: STARTPAGE,
                            limit: ENDPAGE,
                            kd_ukuran: Ext.getCmp('id_ios_cbukuran').getValue(),
                            kd_satuan: Ext.getCmp('id_ios_cbsatuan').getValue(),
                            kd_kategori1: Ext.getCmp('id_ios_cbkategori1').getValue(),
                            kd_kategori2: Ext.getCmp('id_ios_cbkategori2').getValue(),
                            kd_kategori3: Ext.getCmp('id_ios_cbkategori3').getValue(),
                            kd_kategori4: Ext.getCmp('id_ios_cbkategori4').getValue(),
                            tanggal: Ext.getCmp('ios_tanggal').getValue(),
                            //list: Ext.getCmp('ehp_list').getValue(),
			
                        }
                    }); 
            }
        }]
    }
    
   
	

    var gridpembeliancreatepobonus = new Ext.grid.GridPanel({
        store: strpembeliancreatepobonus,
        stripeRows: true,
        height: 200,
        frame: true,
        border:true,
	tbar: [],
        plugins: [editorpembeliancreatepobonus],
        columns: [{
            header: 'Tanggal',
            dataIndex: 'tanggal',
            width: 150,
            sortable: true,
            id:'tgl'
        },{
            header: 'Jenis Pembayaran',
            dataIndex: 'jns_pembayaran',
            width: 250,
            editor: new Ext.form.TextField({
            readOnly: true,
            id: 'jns_pmbyrn'
            })
        },{
            xtype: 'numbercolumn',
            header:'Total Tagihan',
            dataIndex: 'total_tagihan',      
            width: 150,
            align: 'center',
            sortable: true,
            format: '0,0'
        },{
            xtype: 'numbercolumn',
            header:'Total Setoran',
            dataIndex: 'total_setoran',      
            width: 150,
            align: 'center',
            sortable: true,
            format: '0,0'
        },
        {
            header: 'No. Setoran',
            dataIndex: 'no_setoran',
            width: 150,
            editor: new Ext.form.TextField({
            readOnly: true,
            id: 'no_setoran'
            })
        },
        {
            header: 'Kasir',
            dataIndex: 'kasir',
            width: 150,
            editor: new Ext.form.TextField({
            readOnly: true,
            id: 'kasir'
            })
        }
]
    });
	
	
	
    var pembeliancreatepobonus = new Ext.FormPanel({
        id: 'rpt_lap_setoran',
        buttonAlign: 'left',
        border: false,
        frame: true,
        autoScroll:true, 
		monitorValid: true,       
        bodyStyle:'padding-right:20px;',
        //labelWidth: 130,
        items: [{
                    bodyStyle: {
                        margin: '0px 0px 15px 0px'
                    },                  
                    items: [headerlsetoran]
                },
                gridpembeliancreatepobonus
                
        ],
        buttons: [{
            text: 'Tutup',
            handler: function(){
                clearpembeliancreatepobonus();
            }
        }]
    });
    
    
</script>
