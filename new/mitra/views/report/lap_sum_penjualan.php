<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript"> 
     var strcblsumjualuser = new Ext.data.ArrayStore({
        fields: ['kd_user'],
        data : []
        });

         var strgridlsumjualuser = new Ext.data.Store({
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
	
        var searchgridlsumjualuser = new Ext.app.SearchField({
        store: strgridlsumjualuser,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE			
        },
        width: 350,
        id: 'id_searchgridlsumjualuser'
    });

        var gridlsumjualuser = new Ext.grid.GridPanel({
        store: strgridlsumjualuser,
        stripeRows: true,
        frame: true,
        border:true,
        columns: [{
            header: 'ID User',
            dataIndex: 'kd_user',
            width: 80,
            sortable: true			
            
        },{
            header: 'User Name',
            dataIndex: 'username',
            width: 300,
            sortable: true        
        }],
		tbar: new Ext.Toolbar({
	        items: [searchgridlsumjualuser]
	    }),
            bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridlsumjualuser,
            displayInfo: true
        }),
		listeners: {
			'rowdblclick': function(){			
				var sm = this.getSelectionModel();
				var sel = sm.getSelections();
				if (sel.length > 0) {				
                    Ext.getCmp('id_cblsumjualuser').setValue(sel[0].get('username'));
                    menulsumjualuser.hide();
				}
			}
		}
    });

        var menulsumjualuser = new Ext.menu.Menu();
        menulsumjualuser.add(new Ext.Panel({
        title: 'Pilih User ID',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridlsumjualuser],
        buttons: [{
            text: 'Close',
            handler: function(){
                menulsumjualuser.hide();
            }
        }]
    }));
    
    Ext.ux.TwinCombouser = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
			//load store grid
            strgridlsumjualuser.load();
            menulsumjualuser.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
	
	menulsumjualuser.on('hide', function(){
		var sf = Ext.getCmp('id_searchgridlsumjualuser').getValue();
		if( sf != ''){
			Ext.getCmp('id_searchgridlsumjualuser').setValue('');
			searchgridlsumjualuser.onTrigger2Click();
		}
	});
	
     
        var cbljualuser = new Ext.ux.TwinCombouser({
        fieldLabel: 'User ID',
        id: 'id_cblsumjualuser',
        store: strcblsumjualuser,
	mode: 'local',
        valueField: 'kd_user',
        displayField: 'username',
        typeAhead: true,
        triggerAction: 'all',
        // allowBlank: false,
        editable: false,
	anchor: '90%',
        hiddenName: 'kd_user',
        emptyText: 'Pilih User ID'
    });
     
 // cb userid
    
    var str_cblsumjualuser = new Ext.data.Store({
    reader: new Ext.data.JsonReader({
    fields: ['username'],
    root: 'data',
    totalProperty: 'record'
    }),
    proxy: new Ext.data.HttpProxy({
    url: '<?= site_url("laporan_sum_penjualan/get_user_id") ?>',
    method: 'POST'
    }),
        listeners: {
            load: function() {
                var r = new (str_cblsumjualuser.recordType)({
                    'username': ''
                });
                str_cblsumjualuser.insert(0, r);
            },
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });
   
     var cblsumjualuser = new Ext.form.ComboBox({
        fieldLabel: 'User ID',
        id: 'id_cblsumjualuser',
        store: str_cblsumjualuser,
        valueField: 'username',
        displayField: 'username',
        typeAhead: true,
        triggerAction: 'all',
        editable: false,
        width: 170,
        anchor: '90%',
        hiddenName: 'username',
        emptyText: 'Pilih User ID'
    });
    
        var headerlsumjualtanggal = {
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
                                                                            id: 'id_dari_tgl_sumjual',                
                                                                            anchor: '90%',
                                                                            value: ''
                                                                        },cbljualuser
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
										id: 'id_smp_tgl_sumjual',										
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

    var headerlapsumjual = {
            buttonAlign: 'left',
            layout: 'form',
            border: false,
            labelWidth: 100,
			defaults: { labelSeparator: ''},
                items: [{
                        fieldLabel: 'Tanggal Input : '
                }, headerlsumjualtanggal                      
                ],
            buttons: [{
            text: 'Print',
			formBind:true,
            handler: function(){				
            winlsumjualprint.show();
            Ext.getDom('lapsumjualprint').src = '<?= site_url("laporan_sum_penjualan/print_form") ?>';			
            }
        },{
			text: 'Cancel',
			handler: function(){
                        clearlapsumpenjualan();
			}
		}]
    };
       var winlsumjualprint = new Ext.Window({ 
        id: 'id_winlsumjualprint',
	title: 'Laporan Summary Penjualan ',
        closeAction: 'hide',
        width: 900,
        height: 450,
        layout: 'fit',
        border: false,
        html:'<iframe style="width:100%;height:390px;" id="lapsumjualprint" src=""></iframe>'
       
    });   
    var laporansumpenjualan = new Ext.FormPanel({    
        id: 'rpt_sum_penjualan',
        border: false,
        frame: true,
        monitorValid: true,
        labelWidth: 130,
        items: [{
                    bodyStyle: {
                        margin: '0px 0px 15px 0px'
                    },					
                    items: [headerlapsumjual]
                }
        ]
    });
	
	function clearlapsumpenjualan(){
		Ext.getCmp('rpt_sum_penjualan').getForm().reset();
		
	}
</script>