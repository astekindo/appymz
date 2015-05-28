<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">
	var strcbnopokph = new Ext.data.Store({ 
        reader: new Ext.data.JsonReader({
            fields: ['no_po', 'nama_supplier', 'rp_total_po'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("konsinyasi_pelunasan_hutang/get_no_po") ?>',
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
	
	var searchnopokph = new Ext.app.SearchField({
        store: strcbnopokph,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        id: 'kphsearchnopo'
    });
    
    var tbnopokph = new Ext.Toolbar({
        items: [searchnopokph]
    });
	
	var gridnopokph = new Ext.grid.GridPanel({
        store: strcbnopokph,
        stripeRows: true,
        frame: true,
        border:true,
        columns: [{
            header: 'No PO',
            dataIndex: 'no_po',
            width: 100,
            sortable: true,			
            
        },{
            header: 'Nama Supplier',
            dataIndex: 'nama_supplier',
            width: 200,
			sortable: true,         
        },{
            header: 'Total',
            dataIndex: 'rp_total_po',
            width: 100,
			sortable: true,         
        }],
		listeners: {
			'rowdblclick': function(){			
				var sm = this.getSelectionModel();
				var sel = sm.getSelections();
				if (sel.length > 0) {					
					Ext.Ajax.request({
                        url: '<?= site_url("konsinyasi_pelunasan_hutang/get_no_po") ?>',
                        method: 'POST',
                        params: {
                            no_po: sel[0].get('no_po')
                        },
						callback:function(opt,success,responseObj){
							var de = Ext.util.JSON.decode(responseObj.responseText);
							if(de.success==true){
								Ext.getCmp('kph_no_po').setValue(de.data.no_po);
								Ext.getCmp('kph_kd_supplier').setValue(de.data.kd_suplier_po);
								Ext.getCmp('kph_nama_supplier').setValue(de.data.nama_supplier);
								Ext.getCmp('kph_jml_uang').setValue(de.data.uangsejumlah);
								Ext.getCmp('kph_jml').setValue(sel[0].get('rp_total_po'));
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
					menunopokph.hide();
				}
			}
		},
		tbar:tbnopokph,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strcbnopokph,
            displayInfo: true
        })
    });
	

	var menunopokph = new Ext.menu.Menu();
		menunopokph.add(new Ext.Panel({
			title: 'Pilih No PO',
			layout: 'fit',
			buttonAlign: 'left',
			modal: true,
			width: 500,
			height: 400,
			closeAction: 'hide',
			plain: true,
			items: [gridnopokph],
			buttons: [{
				text: 'Close',
				handler: function(){
					menunopokph.hide();
				}
			}]
		}));
		
    Ext.ux.TwinCombo = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
			//load store grid
            strcbnopokph.load();
            menunopokph.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
	
    var headerkonsinyasipelunasanhutang = {
        layout: 'column',
        border: false,
        items: [{
            columnWidth: 1,
            layout: 'form',
            border: false,
            frame: true,
            labelWidth: 120,
            items: [{xtype: 'hidden', name: 'kd_supplier', id: 'kph_kd_supplier'},
					new Ext.ux.TwinCombo({
							fieldLabel: 'No PO',
							id: 'kph_no_po',
							store: strcbnopokph,
							valueField: 'no_po',
							displayField: 'no_po',
							typeAhead: true,	
							editable: false,
							hiddenName: 'no_po',
							emptyText: 'Pilih No PO',    
							width: 375,      
							listeners:{
								'expand': function(){
									strcbnopokph.load();
								}
							}
						}),{
							xtype: 'textfield',
							fieldLabel: 'Nama Supplier',
							name: 'nama_supplier',
							id: 'kph_nama_supplier',
							fieldClass: 'readonly-input',
							readOnly: true,
							value: '',
							width: 375,               
						},{
							xtype : 'compositefield',
							msgTarget: 'side',
							width: 375,
							fieldLabel: 'No Kwitansi <span class="asterix">*</span>',
							items : [{
										xtype: 'textfield',
										fieldLabel: 'No Kwitansi',
										name: 'no_kwitansi',
										readOnly:true,
										fieldClass:'readonly-input',
										id: 'kph_no_kwitansi',
										anchor: '90%',
										value:''
									},{
									   xtype: 'displayfield',
									   value: 'Tanggal <span class="asterix">*</span>',
									   style: 'padding-left:30px',
									   width: 100,
									},{
										xtype: 'datefield',
										name: 'tanggal',
										id: 'kph_tgl', 
										format: 'Y-m-d',
										value: new Date(),   
										editable: false,    
										flex:1,
										anchor: '90%'
						   }]
				}]
        }]
    };
   
   var contentkonsinyasipelunasanhutang = {
        layout: 'column',
        border: false,
        items: [{
            columnWidth: 1,
            layout: 'form',
            border: false,
            frame: true,
            labelWidth: 120,
            items: [
			{
				xtype: 'textfield',
				fieldLabel: 'Sudah terima dari <span class="asterix">*</span>',
				name: 'terima_dari',
                allowBlank: false,
				id: 'kph_sterima',                
				anchor: '30%',
			},{
                xtype: 'textarea',
                fieldLabel: 'Uang Sejumlah <span class="asterix">*</span>',
                name: 'terbilang_total',
                readOnly: true,
				fieldClass: 'readonly-input',
                id: 'kph_jml_uang',                
                anchor: '90%'
            },{
                xtype: 'textarea',
                fieldLabel: 'Untuk Pembayaran <span class="asterix">*</span>',
                name: 'keterangan',
                allowBlank: false,
                id: 'kph_upembayaran',                
                anchor: '90%'
            },{
                xtype: 'numericfield',
				currencySymbol: '',
                fieldLabel: 'Jumlah <span class="asterix">*</span>',
                name: 'rp_total',
				readOnly: true,
				fieldClass:'readonly-input bold-input number',  
                id: 'kph_jml',                
                anchor: '30%',
				style: 'text-align:right',
				value: '0'
            }]
        }]
    };
       
    var konsinyasipelunasanhutang = new Ext.FormPanel({
        id: 'konsinyasipelunasanhutang',
        title: 'Input Kwitansi',
        border: false,
        frame: true,
        autoScroll:true,        
        bodyStyle:'padding-right:20px;',
        labelWidth: 130,
        items: [{
                    bodyStyle: {
                        margin: '0px 0px 15px 0px'
                    },                  
                    items: [headerkonsinyasipelunasanhutang,contentkonsinyasipelunasanhutang]
                },
                {
                    layout: 'column',
                    border: false,
                    items: [{
							columnWidth: .6,
							style:'margin:6px 3px 0 0;',
							layout: 'form', 
							labelWidth: 110,
							buttonAlign: 'left',    
							buttons: [{
								text: 'Cetak'
							},{
								text: 'Save',
								handler: function(){
									
									Ext.getCmp('konsinyasipelunasanhutang').getForm().submit({
										url: '<?= site_url("konsinyasi_pelunasan_hutang/update_row") ?>',
										scope: this,
										params: {
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
											
											clearkonsinyasipelunasanhutang();						
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
									clearkonsinyasipelunasanhutang();
								}
							}],               
							items: []
						}]
                }
                
        ]
    });
	
	konsinyasipelunasanhutang.on('afterrender', function(){
        this.getForm().load({
            url: '<?= site_url("konsinyasi_pelunasan_hutang/get_form") ?>',
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
    });
	function clearkonsinyasipelunasanhutang(){
        Ext.getCmp('konsinyasipelunasanhutang').getForm().reset();
        Ext.getCmp('konsinyasipelunasanhutang').getForm().load({
            url: '<?= site_url("konsinyasi_pelunasan_hutang/get_form") ?>',
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
    }
</script>