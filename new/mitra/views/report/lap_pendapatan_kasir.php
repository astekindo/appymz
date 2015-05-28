<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript"> 
        var strcblpkuser = new Ext.data.ArrayStore({
        fields: ['kd_user'],
        data : []
        });
	
        var strgridlpkuser = new Ext.data.Store({
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
	
        var searchgridlpkuser = new Ext.app.SearchField({
        store: strgridlpkuser,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE			
        },
        width: 350,
        id: 'id_searchgridlpkuser'
    });

        var gridlpkuser = new Ext.grid.GridPanel({
        store: strgridlpkuser,
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
	        items: [searchgridlpkuser]
	    }),
            bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridlpkuser,
            displayInfo: true
        }),
		listeners: {
			'rowdblclick': function(){			
				var sm = this.getSelectionModel();
				var sel = sm.getSelections();
				if (sel.length > 0) {	
                    Ext.getCmp('id_cblpkuser').setValue(sel[0].get('username'));
                    menulpkuser.hide();
				}
			}
		}
    });

        var menulpkuser = new Ext.menu.Menu();
        menulpkuser.add(new Ext.Panel({
        title: 'Pilih User',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridlpkuser],
        buttons: [{
            text: 'Close',
            handler: function(){
                menulpkuser.hide();
            }
        }]
    }));
    
    Ext.ux.TwinComboUserlpk = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
			//load store grid
            strgridlpkuser.load();
            menulpkuser.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
	
	menulpkuser.on('hide', function(){
		var sf = Ext.getCmp('id_searchgridlpkuser').getValue();
		if( sf != ''){
			Ext.getCmp('id_searchgridlpkuser').setValue('');
			searchgridlpkuser.onTrigger2Click();
		}
	});
	
     
        var cblpkuser = new Ext.ux.TwinComboUserlpk({
        fieldLabel: 'User ID',
        id: 'id_cblpkuser',
        store: strcblpkuser,
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
    
    
    // SHIFT 
    var strcblpkshift = new Ext.data.ArrayStore({
        fields: ['no_open_saldo'],
        data : []
        });
    var strgridlpkshift = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
        fields: ['no_open_saldo', 'username'],
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
	
        var searchgridlpkshift = new Ext.app.SearchField({
            store: strgridlpkshift,
            params: {
            start: STARTPAGE,
            limit: ENDPAGE			
            },
            width: 350,
            id: 'id_searchgridlpkshift'
        });

        var gridlpkshift = new Ext.grid.GridPanel({
        store: strgridlpkshift,
        stripeRows: true,
        frame: true,
        border:true,
        columns: [{
            header: 'Kode Shift',
            dataIndex: 'no_open_saldo',
            width: 150,
            sortable: true			
            
        },{
            header: 'Nama Shift',
            dataIndex: 'username',
            width: 250,
            sortable: true        
        }],
            tbar: new Ext.Toolbar({
            items: [searchgridlpkshift]
	    }),
            bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridlpkshift,
            displayInfo: true
        }),
		listeners: {
			'rowdblclick': function(){			
				var sm = this.getSelectionModel();
				var sel = sm.getSelections();
				if (sel.length > 0) {			
                    Ext.getCmp('id_cblpkshift').setValue(sel[0].get('no_open_saldo'));
                    menulpkshift.hide();
				}
			}
		}
    });
    Ext.ux.TwinCombolpkShift = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
           strgridlpkshift.load();
           menulpkshift.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

        var menulpkshift = new Ext.menu.Menu();
        menulpkshift.add(new Ext.Panel({
        title: 'Pilih Shift',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridlpkshift],
        buttons: [{
            text: 'Close',
            handler: function(){
                menulpkshift.hide();
            }
        }]
    }));
    
   
	
	menulpkshift.on('hide', function(){
		var sf = Ext.getCmp('id_searchgridlpkshift').getValue();
		if( sf != ''){
			Ext.getCmp('id_searchgridlpkshift').setValue('');
			searchgridlpkshift.onTrigger2Click();
		}
	});
    
    
    
    var cblpkshift = new Ext.ux.TwinCombolpkShift({
        id: 'id_cblpkshift',
        fieldLabel: 'Shift',
        store: strcblpkshift,
        mode: 'local',
        anchor: '90%',
        valueField: 'no_open_saldo',
        displayField: 'username',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: true ,
        editable: false,
        hiddenName: 'no_open_saldo',
        emptyText: 'Pilih Shift' 
    });
        
        // CHECKBOX Sort Order
        var lpksortorder = new Ext.form.Checkbox({
                xtype: 'checkbox',
                fieldLabel: 'Sort Order',
                boxLabel:'Descending',
                name:'sort_order',
                id:'id_lpksortorder',
                checked: true,
                inputValue: '1',
                autoLoad : true
        });
	
        
    
    	var headerlpktanggal = {
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
                                                        fieldLabel: 'Dari Tgl',
                                                        name: 'dari_tgl',				
                                                       // allowBlank:false,   
                                                        format:'d-m-Y',  
                                                        editable:false,           
                                                        id: 'id_dari_tgl_lpk',                
                                                        anchor: '90%',
                                                        value: ''
                                                    }, cblpkshift,		
                                                    cblpkuser
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
                                                        //allowBlank:false,   
                                                        editable:false,                
                                                        format:'d-m-Y',  
                                                        id: 'id_smp_tgl_lpk',										
                                                        anchor: '90%',
                                                        value: ''										
                                                    },lpksortorder
                                                ]
                                        }
							
						]
					}
				]
			}]
        }
        ]
    }
   

    var headerlappendapatankasir = {
            buttonAlign: 'left',
            layout: 'form',
            border: false,
            labelWidth: 100,
            defaults: { labelSeparator: ''},
            items: [headerlpktanggal],
            buttons: [{
            text: 'Print',
			formBind:true,
            handler: function(){
                                var kd_user= Ext.getCmp('id_cblpkuser').getValue();
                                var kd_shift= Ext.getCmp('id_cblpkshift').getValue();
                                var dari_tgl= Ext.getCmp('id_dari_tgl_lpk').getRawValue();
                                var sampai_tgl= Ext.getCmp('id_smp_tgl_lpk').getRawValue();
				winlappendapatankasirprint.show();
                                Ext.getDom('lappendapatankasirprint').src = '<?= site_url("laporan_penjualan1/print_form") ?>' + '/' + kd_user + '/' + kd_shift + '/' +  dari_tgl + '/' + sampai_tgl;
				//Ext.getDom('laporanpenjualan1print').src = '<?= site_url("laporan_penjualan1/print_form") ?>';			
			}
        },{
			text: 'Cancel',
			handler: function(){
				clearlappendapatankasir();
			}
		}]
    };
        var winlappendapatankasirprint = new Ext.Window({
        id: 'id_winlappendapatankasirprint',
	Title: 'Print Laporan Pendapatan Kasir',
        closeAction: 'hide',
        width: 900,
        height: 450,
        layout: 'fit',
        border: false,
        html:'<iframe style="width:100%;height:390px;" id="lappendapatankasirprint" src=""></iframe>'
    });
	
          
       var lappendapatankasir = new Ext.FormPanel({        
	 	id: 'rpt_pendapatan_kasir',		
		border: false,
		frame: true,
		monitorValid: true,
		labelWidth: 130,
        items: [{
                    bodyStyle: {
                        margin: '0px 0px 15px 0px'
                    },					
                    items: [headerlappendapatankasir]
                }
        ]
    });
	
	function clearlappendapatankasir(){
		Ext.getCmp('rpt_pendapatan_kasir').getForm().reset();
		
	}
</script>