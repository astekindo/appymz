<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">		
	
    var headermonitoringpro = {
        layout: 'column',
        border: false,
        items: [{
            columnWidth: .3,
            layout: 'form',
            border: false,
            labelWidth: 100,
            defaults: { labelSeparator: ''},
            items: [{
	                xtype: 'textfield',
	                fieldLabel: 'RO No.',
	                name: 'no_do',	                
	                id: 'mpro_no_do',                
	                anchor: '90%',
					enableKeyEvents: true,
	                value:'',
					allowBlank:false,
					selectOnFocus: true,
					listeners: {
						keypress: function(key, e){
							if(e.keyCode == 13){
								Ext.Ajax.request({
		                        url: '<?= site_url("monitoring_receive_order/get_ro") ?>',
		                        method: 'POST',
		                        params: {
		                            no_ro: this.getValue()
		                        },
								callback:function(opt,success,responseObj){
									var de = Ext.util.JSON.decode(responseObj.responseText);
									if(de.success==true){
										Ext.getCmp('mpro_kd_supplier').setValue(de.data.kd_supplier);
										Ext.getCmp('mpro_tanggal_terima').setValue(de.data.tanggal_terima);
										Ext.getCmp('mpro_nama_supplier').setValue(de.data.nama_supplier);
										Ext.getCmp('mpro_tanggal').setValue(de.data.tanggal);
										Ext.getCmp('mpro_bukti_supplier').setValue(de.data.no_bukti_supplier);
										strmonitoringpro.reload({params:{no_ro:de.data.no_do}});
										
									}else{
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
								}
								});
							}
						}
					}
	            },{
	                xtype: 'textfield',
	                fieldLabel: 'Kode Supplier',
	                name: 'kd_supplier',
	                readOnly:true,
					fieldClass:'readonly-input',
	                id: 'mpro_kd_supplier',                
	                anchor: '90%',
	                value:''
	            },
			]
        }, {
            columnWidth: .4,
            layout: 'form',
            border: false,
            labelWidth: 100,
            defaults: { labelSeparator: ''},
            items: [ {
				xtype: 'textfield',
                fieldLabel: 'Tanggal Terima',
                name: 'tanggal_terima',				
                readOnly:true,
				fieldClass:'readonly-input',				          
                id: 'mpro_tanggal_terima',                
                anchor: '90%',
                value: ''
			},{
	                xtype: 'textfield',
	                fieldLabel: 'Nama Supplier',
	                name: 'nama_supplier',
	                readOnly:true,
					fieldClass:'readonly-input',
	                id: 'mpro_nama_supplier',                
	                anchor: '90%',
	                value:''
	            }]
        },{
            columnWidth: .3,
            layout: 'form',
            border: false,
            labelWidth: 100,
            defaults: { labelSeparator: ''},
            items: [ {
                xtype: 'textfield',
                fieldLabel: 'Tanggal Input',
                name: 'tanggal',
				fieldClass:'readonly-input',
                readOnly:true,
                id: 'mpro_tanggal',                
                anchor: '90%',
                value: ''
            }, {
	            xtype: 'textfield',
	            fieldLabel: 'No. Bukti Supplier',
	            name: 'bukti_supplier', 
				fieldClass:'readonly-input',
                readOnly:true,              
	            id: 'mpro_bukti_supplier',                
	            anchor: '90%'
            }]
        }]
    }
	var strmonitoringpro = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
				{name: 'no_po', allowBlank: false, type: 'text'},
				{name: 'kd_produk', allowBlank: false, type: 'text'},
				{name: 'nama_produk', allowBlank: false, type: 'text'},				
				{name: 'nm_satuan', allowBlank: false, type: 'text'},
                {name: 'qty_beli', allowBlank: false, type: 'int'},
				{name: 'qty_terima', allowBlank: false, type: 'int'},				
				{name: 'sub', allowBlank: false, type: 'text'},
				{name: 'nama_sub', allowBlank: false, type: 'text'},				
				{name: 'berat_ekspedisi', allowBlank: false, type: 'int'},								
				{name: 'nm_satuan_eksp', allowBlank: false, type: 'text'},
				{name: 'nama_ekspedisi', allowBlank: false, type: 'text'},
				
			],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("monitoring_receive_order/get_ro_detail") ?>',
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
	

    var gridmonitoringpro = new Ext.grid.GridPanel({
        store: strmonitoringpro,
        stripeRows: true,
        height: 300,
        frame: true,
        border:true,
        columns: [{
            header: 'No PO',
            dataIndex: 'no_po',
            width: 140,
			          
        },{
            header: 'Kode',
            dataIndex: 'kd_produk',
            width: 110,
			
           
        },{
            header: 'Nama Barang',
            dataIndex: 'nama_produk',
            width: 300,
           
        },{
            header: 'Satuan',
            dataIndex: 'nm_satuan',
            width: 80,
            
        },{
       		header: 'Qty PO',
            dataIndex: 'qty_beli',
			width: 50,
            
        },{
            header: 'Qty',
            dataIndex: 'qty_terima',           
            width: 50,
           
        },{
            header: 'Kode Sub Blok',
            dataIndex: 'sub',
            width: 100,
				
        },{
            header: 'Sub Blok',
            dataIndex: 'nama_sub',
            width: 200,
           
        },{
			header: 'Nama Ekspedisi',
            dataIndex: 'nama_ekspedisi',
            width: 100,
			
		},{
			header: 'Satuan Ekspedisi',
            dataIndex: 'nm_satuan_eksp',
            width: 100,
			
		},{
			header: 'Berat Ekspedisi',
			dataIndex: 'berat_ekspedisi',
			
		}]
    });

	
    var monitoringpembelianreceiveorder = new Ext.FormPanel({
        id: 'monitoringpembelianreceiveorder',
        border: false,
        frame: true,
        autoScroll:true, 
		monitorValid: true,       
        bodyStyle:'padding-right:20px;',
        labelWidth: 130,
        items: [{
                    bodyStyle: {
                        margin: '0px 0px 15px 0px'
                    },                  
                    items: [headermonitoringpro]
                },
                gridmonitoringpro,
        ],
        buttons: [{
            text: 'Reset',
            handler: function(){
                clearmonitoringpro();
            }
        }]
    });
    
    
    function clearmonitoringpro(){
        Ext.getCmp('monitoringpembelianreceiveorder').getForm().reset();
        strmonitoringpro.removeAll();
    }
</script>
