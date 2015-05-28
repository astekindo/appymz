<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript"> 
        var strcblsskuser = new Ext.data.ArrayStore({
        fields: ['kd_user'],
        data : []
        });
	
        var strgridlsskuser = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_user', 'username'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
        url: '<?= site_url("laporan_penjualan1/search_user") ?>',
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
	
        var searchgridlsskuser = new Ext.app.SearchField({
        store: strgridlsskuser,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE			
        },
        width: 350,
        id: 'id_searchgridlsskuser'
    });

        var gridlsskuser = new Ext.grid.GridPanel({
        store: strgridlsskuser,
        stripeRows: true,
        frame: true,
        border:true,
        columns: [{
            header: 'ID User',
            dataIndex: 'kd_user',
            width: 80,
            sortable: true			
            
        },{
            header: 'Nama User',
            dataIndex: 'username',
            width: 300,
            sortable: true        
        }],
		tbar: new Ext.Toolbar({
	        items: [searchgridlsskuser]
	    }),
            bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridlsskuser,
            displayInfo: true
        }),
		listeners: {
			'rowdblclick': function(){			
				var sm = this.getSelectionModel();
				var sel = sm.getSelections();
				if (sel.length > 0) {	
                    Ext.getCmp('id_cblsskuser').setValue(sel[0].get('username'));
                    menulsskuser.hide();
				}
			}
		}
    });

        var menulsskuser = new Ext.menu.Menu();
        menulsskuser.add(new Ext.Panel({
        title: 'Pilih User',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridlsskuser],
        buttons: [{
            text: 'Close',
            handler: function(){
                menulsskuser.hide();
            }
        }]
    }));
    
    Ext.ux.TwinComboUserlssk = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            strgridlsskuser.load();
            menulsskuser.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
	
	menulsskuser.on('hide', function(){
		var sf = Ext.getCmp('id_searchgridlsskuser').getValue();
		if( sf != ''){
			Ext.getCmp('id_searchgridlsskuser').setValue('');
			searchgridlsskuser.onTrigger2Click();
		}
	});
	
     
        var cblsskuser = new Ext.ux.TwinComboUserlssk({
        fieldLabel: 'User ID',
        id: 'id_cblsskuser',
        store: strcblsskuser,
	mode: 'local',
        valueField: 'kd_user',
        displayField: 'username',
        typeAhead: true,
        triggerAction: 'all',
        editable: false,
	anchor: '90%',
        hiddenName: 'kd_user',
        emptyText: 'Pilih User'
    });
    
    
    // No. Bukti 

    var strcblsskbukti = new Ext.data.ArrayStore({
        fields: ['no_setor_kasir'],
        data : []
        });

    var strgridlsskbukti = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
        fields: ['no_setor_kasir', 'username'],
        root: 'data',
        totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
        url: '<?= site_url("laporan_penjualan1/search_shift") ?>',
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

         var searchgridlsskbukti = new Ext.app.SearchField({
            store: strgridlsskbukti,
            params: {
            start: STARTPAGE,
            limit: ENDPAGE			
            },
            width: 350,
            id: 'id_searchgridlsskbukti'
        });

        var gridlsskbukti = new Ext.grid.GridPanel({
        store: strgridlsskbukti,
        stripeRows: true,
        frame: true,
        border:true,
        columns: [{
            header: 'No. Bukti',
            dataIndex: 'no_setor_kasir',
            width: 150,
            sortable: true			
            
        },{
            header: 'User Name',
            dataIndex: 'username',
            width: 250,
            sortable: true        
        }],
            tbar: new Ext.Toolbar({
            items: [searchgridlsskbukti]
	    }),
            bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridlsskbukti,
            displayInfo: true
        }),
		listeners: {
			'rowdblclick': function(){			
				var sm = this.getSelectionModel();
				var sel = sm.getSelections();
				if (sel.length > 0) {			
                    Ext.getCmp('id_cblsskbukti').setValue(sel[0].get('no_setor_kasir'));
                    menulsskshift.hide();
				}
			}
		}
    });
    Ext.ux.TwinCombolsskBukti = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
           strgridlsskbukti.load();
           menulsskbukti.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

        var menulsskbukti = new Ext.menu.Menu();
        menulsskbukti.add(new Ext.Panel({
        title: 'Pilih No. Bukti',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridlsskbukti],
        buttons: [{
            text: 'Close',
            handler: function(){
                menulsskbukti.hide();
            }
        }]
    }));
    
   
	
	menulsskbukti.on('hide', function(){
		var sf = Ext.getCmp('id_searchgridlsskbukti').getValue();
		if( sf != ''){
			Ext.getCmp('id_searchgridlsskbukti').setValue('');
			searchgridlsskbukti.onTrigger2Click();
		}
	});
    
    
    
    var cblsskbukti = new Ext.ux.TwinCombolsskBukti({
        id: 'id_cblsskbukti',
        fieldLabel: 'No. Bukti',
        store: strcblsskbukti,
        mode: 'local',
        anchor: '90%',
        valueField: 'no_setor_kasir',
        displayField: 'username',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: true ,
        editable: false,
        hiddenName: 'no_setor_kasir',
        emptyText: 'Pilih No. Bukti' 
    });
        
        // CHECKBOX Sort Order
        var lssksortorder = new Ext.form.Checkbox({
                xtype: 'checkbox',
                fieldLabel: 'Sort Order',
                boxLabel:'Descending',
                name:'sort_order',
                id:'id_lssksortorder',
                checked: true,
                inputValue: '1',
                autoLoad : true
        });
	
        
    
    	var headerlssktanggal = {
        layout: 'column',
        border: false,
        items: [{
            columnWidth: .8,
            layout: 'form',
            border: false,
            labelWidth: 100,
            defaults: { labelSeparator: ''},
            items: [{
                        xtype: 'fieldset',
                        autoHeight: true,                               
                        items: [
                                {
                                    layout: 'column',
                                    items:[
                                        {
                                            columnWidth: .5,
                                            layout: 'form',
                                            border: false,
                                            labelWidth: 100,
                                            defaults: { labelSeparator: ''},
                                            items:[ {
                                                        xtype: 'datefield',
                                                        fieldLabel: 'Dari Tgl <span class="asterix">*</span>',
                                                        name: 'dari_tgl',				
                                                        allowBlank:false,   
                                                        format:'d-m-Y',  
                                                        editable:false,           
                                                        id: 'id_dari_tgl_lssk',                
                                                        anchor: '90%',
                                                        value: ''
                                                    },cblsskuser, cblsskbukti		
                                                    
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
                                                        id: 'id_smp_tgl_lssk',										
                                                        anchor: '90%',
                                                        value: ''										
                                                    },lssksortorder
                                                ]
                                        }
							
						]
					}
				]
			}]
        }
        ]
    }
   

    var headerlapselisihsetorankasir = {
            buttonAlign: 'left',
            layout: 'form',
            border: false,
            labelWidth: 100,
            defaults: { labelSeparator: ''},
            items: [headerlssktanggal],
            buttons: [{
            text: 'Print',
			formBind:true,
            handler: function(){
                                var kd_user= Ext.getCmp('id_cblsskuser').getValue();
                                var kd_shift= Ext.getCmp('id_cblsskbukti').getValue();
                                var dari_tgl= Ext.getCmp('id_dari_tgl_lssk').getRawValue();
                                var sampai_tgl= Ext.getCmp('id_smp_tgl_lssk').getRawValue();
				winlappendapatankasirprint.show();
                                Ext.getDom('lapselisihsetorankasirprint').src = '<?= site_url("laporan_penjualan1/print_form") ?>' + '/' + kd_user + '/' + kd_shift + '/' +  dari_tgl + '/' + sampai_tgl;
				//Ext.getDom('laporanpenjualan1print').src = '<?= site_url("laporan_penjualan1/print_form") ?>';			
			}
        },{
			text: 'Cancel',
			handler: function(){
				clearlapselisihsetorankasir();
			}
		}]
    };
        var winlapselisissetorankasirprint = new Ext.Window({
        id: 'id_winlapselisissetorankasirprint',
	Title: 'Print Laporan Selisis Setoran Kasih',
        closeAction: 'hide',
        width: 900,
        height: 450,
        layout: 'fit',
        border: false,
        html:'<iframe style="width:100%;height:390px;" id="lapselisihsetorankasirprint" src=""></iframe>'
    });
	
          
       var lapselisihsetorankasir = new Ext.FormPanel({        
	 	id: 'rpt_selisih_setoran_kasir',		
		border: false,
		frame: true,
		monitorValid: true,
		labelWidth: 130,
        items: [{
                    bodyStyle: {
                        margin: '0px 0px 15px 0px'
                    },					
                    items: [headerlapselisihsetorankasir]
                }
        ]
    });
	
	function clearlapselisihsetorankasir(){
		Ext.getCmp('rpt_selisih_setoran_kasir').getForm().reset();
		
	}
</script>