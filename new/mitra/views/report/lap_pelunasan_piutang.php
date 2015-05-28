<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript"> 
        var strcblppkasir = new Ext.data.ArrayStore({
        fields: ['kd_user'],
        data : []
        });
	
        var strgridlppkasir = new Ext.data.Store({
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
	
        var searchgridlppkasir = new Ext.app.SearchField({
        store: strgridlppkasir,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE			
        },
        width: 350,
        id: 'id_searchgridlppkasir'
    });

        var gridlppkasir = new Ext.grid.GridPanel({
        store: strgridlppkasir,
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
	        items: [searchgridlppkasir]
	    }),
            bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridlppkasir,
            displayInfo: true
        }),
		listeners: {
			'rowdblclick': function(){			
				var sm = this.getSelectionModel();
				var sel = sm.getSelections();
				if (sel.length > 0) {	
                    Ext.getCmp('id_cblppkasir').setValue(sel[0].get('username'));
                    menulppkasir.hide();
				}
			}
		}
    });

        var menulppkasir = new Ext.menu.Menu();
        menulppkasir.add(new Ext.Panel({
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
                menulppkasir.hide();
            }
        }]
    }));
    
    Ext.ux.TwinCombolppKasir = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            strgridlppkasir.load();
            menulppkasir.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
	
	menulppkasir.on('hide', function(){
		var sf = Ext.getCmp('id_searchgridlppkasir').getValue();
		if( sf != ''){
			Ext.getCmp('id_searchgridlppkasir').setValue('');
			searchgridlppkasir.onTrigger2Click();
		}
	});
	
     
        var cblppkasir = new Ext.ux.TwinCombolppKasir({
        fieldLabel: 'Kasir',
        id: 'id_cblppkasir',
        store: strcblppkasir,
	mode: 'local',
        valueField: 'kd_user',
        displayField: 'username',
        typeAhead: true,
        triggerAction: 'all',
        editable: false,
	anchor: '90%',
        hiddenName: 'kd_user',
        emptyText: 'Pilih Kasir'
    });
   
   // twin combo MEMBER
    var strcblppmember = new Ext.data.ArrayStore({
        fields: ['kd_member'],
        data: []
    });

    var strgridlppmember = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_member', 'nmmember'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("laporan_purchase_order/search_member") ?>',
            method: 'POST'
        }),
        listeners: {
            loadexception: function(event, options, response, error) {
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    var searchgridlppmember = new Ext.app.SearchField({
        store: strgridlppmember,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgridlppmember'
    });

    var gridlppmember = new Ext.grid.GridPanel({
        store: strgridlppmember,
        stripeRows: true,
        frame: true,
        border: true,
        columns: [{
                header: 'Kode Member',
                dataIndex: 'kd_member',
                width: 80,
                sortable: true

            }, {
                header: 'Nama Member',
                dataIndex: 'nmmember',
                width: 300,
                sortable: true
            }],
        tbar: new Ext.Toolbar({
            items: [searchgridlppmember]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridlppmember,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('id_cblppmember').setValue(sel[0].get('kd_supplier'));
                    menulppmember.hide();
                }
            }
        }
    });

    var menulppmember = new Ext.menu.Menu();
    menulppmember.add(new Ext.Panel({
        title: 'Pilih Member',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridlppmember],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menulppmember.hide();
                }
            }]
    }));

    Ext.ux.TwinCombolppMember = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            strgridlppmember.load();
            menulppmember.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    menulppmember.on('hide', function() {
        var sf = Ext.getCmp('id_searchgridlppmember').getValue();
        if (sf != '') {
            Ext.getCmp('id_searchgridlppmember').setValue('');
            searchgridlppmember.onTrigger2Click();
        }
    });

    var cblppmember = new Ext.ux.TwinCombolppMember({
        fieldLabel: 'Member',
        id: 'id_cblppmember',
        store: strcblppmember,
        mode: 'local',
        valueField: 'kd_member',
        displayField: 'kd_member',
        typeAhead: true,
        triggerAction: 'all',
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_memberr',
        emptyText: 'Pilih Member'
    });
    
    // No. Bukti 

    var strcblppnobukti = new Ext.data.ArrayStore({
        fields: ['no_setor_kasir'],
        data : []
        });

    var strgridlppnobukti = new Ext.data.Store({
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

         var searchgridlppnobukti = new Ext.app.SearchField({
            store: strgridlppnobukti,
            params: {
            start: STARTPAGE,
            limit: ENDPAGE			
            },
            width: 350,
            id: 'id_searchgridlppnobukti'
        });

        var gridlppnobukti = new Ext.grid.GridPanel({
        store: strgridlppnobukti,
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
            items: [searchgridlppnobukti]
	    }),
            bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridlppnobukti,
            displayInfo: true
        }),
		listeners: {
			'rowdblclick': function(){			
				var sm = this.getSelectionModel();
				var sel = sm.getSelections();
				if (sel.length > 0) {			
                    Ext.getCmp('id_cblppnobukti').setValue(sel[0].get('no_setor_kasir'));
                    menulppnobukti.hide();
				}
			}
		}
    });
    Ext.ux.TwinCombolppnoBukti = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
           strgridlppnobukti.load();
           menulppnobukti.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

        var menulppnobukti = new Ext.menu.Menu();
        menulppnobukti.add(new Ext.Panel({
        title: 'Pilih No. Bukti',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridlppnobukti],
        buttons: [{
            text: 'Close',
            handler: function(){
                menulppnobukti.hide();
            }
        }]
    }));
    
   
	
	menulppnobukti.on('hide', function(){
		var sf = Ext.getCmp('id_searchgridlppnobukti').getValue();
		if( sf != ''){
			Ext.getCmp('id_searchgridlppnobukti').setValue('');
			searchgridlppnobukti.onTrigger2Click();
		}
	});
    
    
    
    var cblppnobukti = new Ext.ux.TwinCombolppnoBukti({
        id: 'id_cblppnobukti',
        fieldLabel: 'No. Bukti',
        store: strcblppnobukti,
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
    
    // twin combo jenis pembayaran 
    
    var strblppjnsbayar = new Ext.data.ArrayStore({
        fields: ['kd_jenis_bayar'],
        data: []
    });

    var strgridlppjnspembayaran = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_jenis_bayar', 'nm_pembayaran'],
            root: 'data',
            totalproperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("penjualan_pelunasan_piutang/get_all_jenis_pembayaran") ?>',
            method: 'POST'
        }),
        listeners: {
            loadexception: function(event, options, response, error) {
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    var gridlppjnspembayaran = new Ext.grid.GridPanel({
        store: strgridlppjnspembayaran,
        stripeRows: true,
        frame: true,
        border: true,
        columns: [{
                header: 'Kode',
                dataIndex: 'kd_jenis_bayar',
                width: 70,
                sortable: true,
            }, {
                header: 'Jenis Pembayaran',
                dataIndex: 'nm_pembayaran',
                width: 200,
                sortable: true,
            }],
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('id_cblppjnsbayar').setValue(sel[0].get('kd_jenis_bayar'));
                    menulppjnspembayaran.hide();
                }
            }
        }
    });

    var menulppjnspembayaran = new Ext.menu.Menu();
    menulppjnspembayaran.add(new Ext.Panel({
        title: 'Pilih Jenis Pembayaran',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 300,
        height: 250,
        closeAction: 'hide',
        plain: true,
        items: [gridlppjnspembayaran],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menulppjnspembayaran.hide();
                }
            }]
    }));

    Ext.ux.TwinCombolppjnspembayaran = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            //load store grid
            strgridlppjnspembayaran.load();
            menulppjnspembayaran.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
    
    var cblppjnsbayar = new Ext.ux.TwinCombolppjnspembayaran({
        id: 'id_cblppjnsbayar',
        fieldLabel: 'Jenis Pembayaran',
        store: strblppjnsbayar,
        mode: 'local',
        anchor: '90%',
        valueField: 'kd_jenis_bayar',
        displayField: 'kd_jenis_bayar',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: true ,
        editable: false,
        hiddenName: 'kd_jenis_bayar',
        emptyText: 'Jenis Pembayaran' 
    });
      // End Twin combo Jenis Pembayaran 
      
  // COMBOBOX status
      var valcblppstatus=[
		['D',"Distribusi"],
		['B',"Bazar"],
		['S',"Supermarket"]
	];
  
      var strcblppstatus = new Ext.data.ArrayStore({
        fields: [
            {name: 'key'},
            {name: 'value'}
        ],
        data:valcblppstatus
    });

	
    var cblppstatus = new Ext.form.ComboBox({
        fieldLabel: 'Status',
        id: 'cblppstatus',
        name:'status',
        // allowBlank:false,
        store: strcblppstatus,
        valueField:'key',
        displayField:'value',
        mode:'local',
        forceSelection: true,
        triggerAction: 'all',
	anchor: '90%'
    });
    
    // COMBOBOX Bank
      var valcblppbank=[
		['BCA',"BCA"],
		['Mandiri',"Mandiri"],
		['BTN',"BTN"]
	];
  
      var strcblppbank = new Ext.data.ArrayStore({
        fields: [
            {name: 'key'},
            {name: 'value'}
        ],
        data:valcblppbank
    });

	
    var cblppbank = new Ext.form.ComboBox({
        fieldLabel: 'Bank',
        id: 'cblppbank',
        name:'status',
        // allowBlank:false,
        store: strcblppbank,
        valueField:'key',
        displayField:'value',
        mode:'local',
        forceSelection: true,
        triggerAction: 'all',
	anchor: '90%'
    });
    
        // CHECKBOX Sort Order
        var lppsortorder = new Ext.form.Checkbox({
                xtype: 'checkbox',
                fieldLabel: 'Sort Order',
                boxLabel:'Descending',
                name:'sort_order',
                id:'id_lppsortorder',
                checked: true,
                inputValue: '1',
                autoLoad : true
        });
	
        
    
    	var headerlpptanggal = {
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
                                                    },cblppnobukti,cblppkasir,cblppmember,cblppjnsbayar,
                                                    {
                                                        xtype: 'textfield',
                                                        fieldLabel: 'No SO',
                                                        name: 'nama_bank',			
                                                        //allowBlank:false,   
                                                        editable:false,                
                                                        //format:'d-m-Y',  
                                                        id: 'id_no_so',										
                                                        anchor: '90%',
                                                        value: ''										
                                                    },
                                                    
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
                                                    },cblppbank,
                                                    {
                                                        xtype: 'textfield',
                                                        fieldLabel: 'No Rek.Penerima ',
                                                        name: 'no_rek_penerima',				
                                                        //allowBlank:false,   
                                                        //format:'d-m-Y',  
                                                        editable:false,           
                                                        id: 'id_rek_penerima_lpp',                
                                                        anchor: '90%',
                                                        value: ''
                                                    },{
                                                        xtype: 'datefield',
                                                        fieldLabel: 'Tgl Jatuh Tempo',
                                                        name: 'tgl_jth_tempo',			
                                                        //allowBlank:false,   
                                                        editable:false,                
                                                        format:'d-m-Y',  
                                                        id: 'id_tgl_jth_tempo',										
                                                        anchor: '90%',
                                                        value: ''										
                                                    },cblppstatus,lppsortorder
                                                ]
                                        }
							
						]
					}
				]
			}]
        }
        ]
    }
   

    var headerlappelunasanpiutang = {
            buttonAlign: 'left',
            layout: 'form',
            border: false,
            labelWidth: 100,
            defaults: { labelSeparator: ''},
            items: [headerlpptanggal],
            buttons: [{
            text: 'Print',
			formBind:true,
            handler: function(){
                                var kd_user= Ext.getCmp('id_cblppkasir').getValue();
                                var kd_shift= Ext.getCmp('id_cblppnobukti').getValue();
                                var dari_tgl= Ext.getCmp('id_dari_tgl_lssk').getRawValue();
                                var sampai_tgl= Ext.getCmp('id_smp_tgl_lssk').getRawValue();
				winlappendapatankasirprint.show();
                                Ext.getDom('lappelunasanpiutangprint').src = '<?= site_url("laporan_penjualan1/print_form") ?>' + '/' + kd_user + '/' + kd_shift + '/' +  dari_tgl + '/' + sampai_tgl;
				//Ext.getDom('laporanpenjualan1print').src = '<?= site_url("laporan_penjualan1/print_form") ?>';			
			}
        },{
			text: 'Cancel',
			handler: function(){
				clearlappelunasanpiutang();
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
        html:'<iframe style="width:100%;height:390px;" id="lappelunasanpiutangprint" src=""></iframe>'
    });
	
          
       var lappelunasanpiutang = new Ext.FormPanel({        
	 	id: 'rpt_pelunasan_piutang',		
		border: false,
		frame: true,
		monitorValid: true,
		labelWidth: 130,
        items: [{
                    bodyStyle: {
                        margin: '0px 0px 15px 0px'
                    },					
                    items: [headerlappelunasanpiutang]
                }
        ]
    });
	
	function clearlappelunasanpiutang(){
		Ext.getCmp('rpt_selisih_setoran_kasir').getForm().reset();
		
	}
</script>