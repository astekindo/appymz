<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript"> 
     
    // Kasir
    var strcblstkkasir = new Ext.data.ArrayStore({
        fields: ['no_open_saldo'],
        data : []
        });
    var strgridlstkkasir = new Ext.data.Store({
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
	
        var searchgridlstkkasir = new Ext.app.SearchField({
            store: strgridlstkkasir,
            params: {
            start: STARTPAGE,
            limit: ENDPAGE			
            },
            width: 350,
            id: 'id_searchgridlstkkasir'
        });

        var gridlstkkasir = new Ext.grid.GridPanel({
        store: strgridlstkkasir,
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
            items: [searchgridlstkkasir]
	    }),
            bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridlstkkasir,
            displayInfo: true
        }),
		listeners: {
			'rowdblclick': function(){			
				var sm = this.getSelectionModel();
				var sel = sm.getSelections();
				if (sel.length > 0) {			
                    Ext.getCmp('id_cblstkkasir').setValue(sel[0].get('no_open_saldo'));
                    menulstkkasir.hide();
				}
			}
		}
    });
    Ext.ux.TwinCombolstkkasir = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
           strgridlstkkasir.load();
           menulstkkasir.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

        var menulstkkasir = new Ext.menu.Menu();
        menulstkkasir.add(new Ext.Panel({
        title: 'Pilih Kasir',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridlstkkasir],
        buttons: [{
            text: 'Close',
            handler: function(){
                menulstkkasir.hide();
            }
        }]
    }));
    
   
	
	menulstkkasir.on('hide', function(){
		var sf = Ext.getCmp('id_searchgridlstkkasir').getValue();
		if( sf != ''){
			Ext.getCmp('id_searchgridlstkkasir').setValue('');
			searchgridlstkkasir.onTrigger2Click();
		}
	});
    
    
    
    var cblstkkasir = new Ext.ux.TwinCombolstkkasir({
        id: 'id_cblstkkasir',
        fieldLabel: 'Kasir',
        store: strcblstkkasir,
        mode: 'local',
        anchor: '90%',
        valueField: 'no_open_saldo',
        displayField: 'username',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: true ,
        editable: false,
        hiddenName: 'no_open_saldo',
        emptyText: 'Pilih Kasir' 
    });
        
        // CHECKBOX Sort Order
        var lstksortorder = new Ext.form.Checkbox({
                xtype: 'checkbox',
                fieldLabel: 'Sort Order',
                boxLabel:'Descending',
                name:'sort_order',
                id:'id_lstksortorder',
                checked: true,
                inputValue: '1',
                autoLoad : true
        });
	
        
    
    	var headerlstktanggal = {
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
                                                        id: 'id_dari_tgl',                
                                                        anchor: '90%',
                                                        value: ''
                                                    }, cblstkkasir,
                                                    
                                                    {
                                                        xtype: 'textfield',
                                                        fieldLabel: 'Nama Bank Supplier',
                                                        name: 'nama_bank',			
                                                        //allowBlank:false,   
                                                        editable:false,                
                                                        //format:'d-m-Y',  
                                                        id: 'id_nama_bank',										
                                                        anchor: '90%',
                                                        value: ''										
                                                    },
                                                    {
                                                        xtype: 'textfield',
                                                        fieldLabel: 'No Rekening Penerima',
                                                        name: 'no_rek',			
                                                        //allowBlank:false,   
                                                        editable:false,                
                                                        //format:'d-m-Y',  
                                                        id: 'id_no_rek',										
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
                                                        //allowBlank:false,   
                                                        editable:false,                
                                                        format:'d-m-Y',  
                                                        id: 'id_smp_tgl',										
                                                        anchor: '90%',
                                                        value: ''										
                                                    },{
                                                        xtype: 'textfield',
                                                        fieldLabel: 'No Bukti',
                                                        name: 'no_bukti',			
                                                        //allowBlank:false,   
                                                        editable:false,                
                                                        //format:'d-m-Y',  
                                                        id: 'id_no_bukti',										
                                                        anchor: '90%',
                                                        value: ''										
                                                    },{
                                                        xtype: 'datefield',
                                                        fieldLabel: 'Tanggal Jatuh Tempo',
                                                        name: 'tgl_jth_tempo',			
                                                        //allowBlank:false,   
                                                        editable:false,                
                                                        format:'d-m-Y',  
                                                        id: 'id_jth_tempo',										
                                                        anchor: '90%',
                                                        value: ''										
                                                    },lstksortorder
                                                     
                                                ]
                                        }
							
						]
					}
				]
			}]
        }
        ]
    }
   

    var headerlapsettransaksikasir = {
            buttonAlign: 'left',
            layout: 'form',
            border: false,
            labelWidth: 100,
            defaults: { labelSeparator: ''},
            items: [headerlstktanggal],
            buttons: [{
            text: 'Print',
			formBind:true,
            handler: function(){
                                var kd_user= Ext.getCmp('id_cblpkuser').getValue();
                                var kd_shift= Ext.getCmp('id_cblpkshift').getValue();
                                var dari_tgl= Ext.getCmp('id_dari_tgl_lpk').getRawValue();
                                var sampai_tgl= Ext.getCmp('id_smp_tgl_lpk').getRawValue();
				winlapsettransaksikasirprint.show();
                                Ext.getDom('lapsettransaksikasirprint').src = '<?= site_url("laporan_penjualan1/print_form") ?>' + '/' + kd_user + '/' + kd_shift + '/' +  dari_tgl + '/' + sampai_tgl;
				//Ext.getDom('laporanpenjualan1print').src = '<?= site_url("laporan_penjualan1/print_form") ?>';			
			}
        },{
			text: 'Cancel',
			handler: function(){
				clearlapsettransaksikasir();
			}
		}]
    };
        var winlapsettransaksikasirprint = new Ext.Window({
        id: 'id_winlapsettransaksikasirprint',
	Title: 'Print Laporan Setoran Transaksi Kasir',
        closeAction: 'hide',
        width: 900,
        height: 450,
        layout: 'fit',
        border: false,
        html:'<iframe style="width:100%;height:390px;" id="lapsettransaksikasirprint" src=""></iframe>'
    });
	
          
       var lapsettransaksikasir= new Ext.FormPanel({        
	 	id: 'rpt_setoran_transaksi_kasir',		
		border: false,
		frame: true,
		monitorValid: true,
		labelWidth: 130,
        items: [{
                    bodyStyle: {
                        margin: '0px 0px 15px 0px'
                    },					
                    items: [headerlapsettransaksikasir]
                }
        ]
    });
	
	function clearlapsettransaksikasir(){
		Ext.getCmp('rpt_setoran_transaksi_kasir').getForm().reset();
		
	}
</script>