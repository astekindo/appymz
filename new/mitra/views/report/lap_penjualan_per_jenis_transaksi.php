<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript"> 
  
    // No. Bukti 

    var strcblppjtnobukti = new Ext.data.ArrayStore({
        fields: ['no_setor_kasir'],
        data : []
        });

    var strgridlppjtnobukti = new Ext.data.Store({
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

         var searchgridlppjtnobukti = new Ext.app.SearchField({
            store: strgridlppjtnobukti,
            params: {
            start: STARTPAGE,
            limit: ENDPAGE			
            },
            width: 350,
            id: 'id_searchgridlppjtnobukti'
        });

        var gridlppjtnobukti = new Ext.grid.GridPanel({
        store: strgridlppjtnobukti,
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
            items: [searchgridlppjtnobukti]
	    }),
            bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridlppjtnobukti,
            displayInfo: true
        }),
		listeners: {
			'rowdblclick': function(){			
				var sm = this.getSelectionModel();
				var sel = sm.getSelections();
				if (sel.length > 0) {			
                    Ext.getCmp('id_cblppjtnobukti').setValue(sel[0].get('no_setor_kasir'));
                    menulppjtnobukti.hide();
				}
			}
		}
    });
    Ext.ux.TwinCombolppjtnoBukti = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
           strgridlppjtnobukti.load();
           menulppjtnobukti.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

        var menulppjtnobukti = new Ext.menu.Menu();
        menulppjtnobukti.add(new Ext.Panel({
        title: 'Pilih No. Bukti',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridlppjtnobukti],
        buttons: [{
            text: 'Close',
            handler: function(){
                menulppjtnobukti.hide();
            }
        }]
    }));
    
   
	
	menulppjtnobukti.on('hide', function(){
		var sf = Ext.getCmp('id_searchgridlppjtnobukti').getValue();
		if( sf != ''){
			Ext.getCmp('id_searchgridlppjtnobukti').setValue('');
			searchgridlppjtnobukti.onTrigger2Click();
		}
	});
    
    
    
    var cblppjtnobukti = new Ext.ux.TwinCombolppjtnoBukti({
        id: 'id_cblppjtnobukti',
        fieldLabel: 'No. Bukti',
        store: strcblppjtnobukti,
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
      
  // COMBOBOX status
      var valcblppjtstatus=[
		['D',"Distribusi"],
		['B',"Bazar"],
		['S',"Supermarket"]
	];
  
      var strcblppjtstatus = new Ext.data.ArrayStore({
        fields: [
            {name: 'key'},
            {name: 'value'}
        ],
        data:valcblppjtstatus
    });

	
    var cblppjtstatus = new Ext.form.ComboBox({
        fieldLabel: 'Status',
        id: 'cblppjtstatus',
        name:'status',
        // allowBlank:false,
        store: strcblppjtstatus,
        valueField:'key',
        displayField:'value',
        mode:'local',
        forceSelection: true,
        triggerAction: 'all',
	anchor: '90%'
    });
    
    // COMBOBOX Bank
      var valcblppjtbank=[
		['BCA',"BCA"],
		['Mandiri',"Mandiri"],
		['BTN',"BTN"]
	];
  
      var strcblppjtbank = new Ext.data.ArrayStore({
        fields: [
            {name: 'key'},
            {name: 'value'}
        ],
        data:valcblppjtbank
    });

	
    var cblppjtbank = new Ext.form.ComboBox({
        fieldLabel: 'Bank',
        id: 'cblppjtbank',
        name:'status',
        // allowBlank:false,
        store: strcblppjtbank,
        valueField:'key',
        displayField:'value',
        mode:'local',
        forceSelection: true,
        triggerAction: 'all',
	anchor: '90%'
    });
    
        // CHECKBOX Sort Order
        var lppjtsortorder = new Ext.form.Checkbox({
                xtype: 'checkbox',
                fieldLabel: 'Sort Order',
                boxLabel:'Descending',
                name:'sort_order',
                id:'id_lppjtsortorder',
                checked: true,
                inputValue: '1',
                autoLoad : true
        });
	
        
    
    	var headerlppjttanggal = {
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
                                                        fieldLabel: 'Dari Tgl ',
                                                        name: 'dari_tgl',				
                                                        //allowBlank:false,   
                                                        format:'d-m-Y',  
                                                        editable:false,           
                                                        id: 'id_dari_tgl',                
                                                        anchor: '90%',
                                                        value: ''
                                                    },cblppjtnobukti,
                                                    {
                                                        xtype: 'datefield',
                                                        fieldLabel: 'Tgl Jatuh Tempo',
                                                        name: 'tgl_jth_tempo',			
                                                        //allowBlank:false,   
                                                        editable:false,                
                                                        format:'d-m-Y',  
                                                        id: 'id_tgl_jth_tempo',										
                                                        anchor: '90%',
                                                        value: ''										
                                                    },cblppjtstatus,lppjtsortorder
                                                   
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
                                                    },cblppjtbank,
                                                    {
                                                        xtype: 'textfield',
                                                        fieldLabel: 'No Rek.Penerima ',
                                                        name: 'no_rek_penerima',				
                                                        //allowBlank:false,   
                                                        //format:'d-m-Y',  
                                                        editable:false,           
                                                        id: 'id_rek_penerima_lppjt',                
                                                        anchor: '90%',
                                                        value: ''
                                                    }
                                                ]
                                        }
							
						]
					}
				]
			}]
        }
        ]
    }
   

    var headerlappenjualanperjenistransaksi = {
            buttonAlign: 'left',
            layout: 'form',
            border: false,
            labelWidth: 100,
            defaults: { labelSeparator: ''},
            items: [headerlppjttanggal],
            buttons: [{
            text: 'Print',
			formBind:true,
            handler: function(){
                                var kd_user= Ext.getCmp('id_cblppkasir').getValue();
                                var kd_shift= Ext.getCmp('id_cblppjtnobukti').getValue();
                                var dari_tgl= Ext.getCmp('id_dari_tgl_lssk').getRawValue();
                                var sampai_tgl= Ext.getCmp('id_smp_tgl_lssk').getRawValue();
				winlappendapatankasirprint.show();
                                Ext.getDom('lappenjualanperjenistransaksiprint').src = '<?= site_url("laporan_penjualan1/print_form") ?>' + '/' + kd_user + '/' + kd_shift + '/' +  dari_tgl + '/' + sampai_tgl;
				//Ext.getDom('laporanpenjualan1print').src = '<?= site_url("laporan_penjualan1/print_form") ?>';			
			}
        },{
			text: 'Cancel',
			handler: function(){
				clearlappenjualanperjenistransaksi();
			}
		}]
    };
        var winlapselisissetorankasirprint = new Ext.Window({
        id: 'id_winlapselisissetorankasirprint',
	Title: 'Print Laporan Penjualan Per Jenis Transaksi',
        closeAction: 'hide',
        width: 900,
        height: 450,
        layout: 'fit',
        border: false,
        html:'<iframe style="width:100%;height:390px;" id="lappenjualanperjenistransaksiprint" src=""></iframe>'
    });
	
          
       var lappenjualanperjenistransaksi = new Ext.FormPanel({        
	 	id: 'rpt_penjualan_per_jenis_transaksi',		
		border: false,
		frame: true,
		monitorValid: true,
		labelWidth: 130,
        items: [{
                    bodyStyle: {
                        margin: '0px 0px 15px 0px'
                    },					
                    items: [headerlappenjualanperjenistransaksi]
                }
        ]
    });
	
	function clearlappenjualanperjenistransaksi(){
		Ext.getCmp('rpt_selisih_setoran_kasir').getForm().reset();
		
	}
</script>