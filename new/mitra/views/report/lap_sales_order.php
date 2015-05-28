<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript"> 

        var headerlsalesordertanggal = {
        layout: 'column',
        border: false,
        items: [{
            columnWidth: .6,
            layout: 'form',
            border: false,
            labelWidth: 100,
            defaults: { labelSeparator: ''},
            items: [{                          
                items: [{
                        layout: 'column',
                        items:[
                                                                    
                            {
                                columnWidth: .5,
                                layout: 'form',
                                border: false,
                                labelWidth: 100,
                                defaults: { labelSeparator: ''},
                                items:[    
                                    {
                                        xtype: 'datefield',
                                        fieldLabel: 'Dari Tgl ',
                                        name: 'dari_tgl',
                                        allowBlank:false,   
                                        format:'d-m-Y',  
                                        editable:false,           
                                        id: 'id_dari_tgl_sales_order',                
                                        anchor: '90%',
                                        value: ''
                                    }
                                ]
                            },
                            {
                                columnWidth: .5,
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
                                        id: 'id_smp_tgl_sales_order',                                        
                                        anchor: '90%',                                        
                                        value: ''                                        
                                    }
                                ]
                            },
                            
                        ]
                    }
                ]
            }]
        }
        ]
    }
    
    
    var headerlsalesorder = {
        buttonAlign: 'left',
        layout: 'form',
        border: false,
        labelWidth: 100,
        defaults: { labelSeparator: ''},
        items: [{fieldLabel: 'Tanggal Input : '}, headerlsalesordertanggal],
        buttons: [{
            text: 'Print',
            formBind:true,
            handler: function(){
                window.open('<?= site_url("laporan_sales_order/print_form") ?>', '_blank');
            }
        },{
            text: 'Cancel',
            handler: function(){clearlsalesorder();}
        }]
    };
    var winlsalesorderprint = new Ext.Window({
        id: 'id_winlsalesorderprint',
        title: 'Laporan Sales Order',
        closeAction: 'hide',
        width: 900,
        height: 450,
        layout: 'fit',
        border: false,
        html:'<iframe style="width:100%;height:390px;" id="lapsalesorderprint" src=""></iframe>'
    });   
    
    var lapsalesorder = new Ext.FormPanel({
        id: 'rpt_sales_order',
        border: false,
        frame: true,
        monitorValid: true,
        labelWidth: 130,
        items: [{
                    bodyStyle: {
                        margin: '0px 0px 15px 0px'
                    },                    
                    items: [headerlsalesorder]
                }
        ]
    });
    
    function clearlsalesorder(){
        Ext.getCmp('rpt_sales_order').getForm().reset();
    }
</script>