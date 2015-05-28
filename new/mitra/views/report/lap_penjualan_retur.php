<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript"> 
    // combobox Status
        var str_lreturjual_cbstatus = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
        fields: ['kd_status', 'nama_status'],
        root: 'data',
        totalProperty: 'record'
        }),
       // proxy: new Ext.data.HttpProxy({
        //    url: '<?= site_url("master_barang/get_ukuran_produk") ?>',
        //    method: 'POST'
        //}),
		listeners: {            
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

     var lreturjual_cbstatus = new Ext.form.ComboBox({
        fieldLabel: 'Status ',
        id: 'id_ lreturjual_cbstatus',
        store: str_lreturjual_cbstatus,
        valueField: 'kd_status',
        displayField: 'nama_status',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        anchor: '42%',
        hiddenName: 'kd_status',
        emptyText: ''
       
    });
    
       
    // combo member

        var strcblreturjualmember = new Ext.data.ArrayStore({
        fields: ['kd_member'],
        data : []
        });

        var strgridlreturjualmember = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
        fields: ['kd_member', 'nmmember'],
        root: 'data',
        totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
        url: '<?= site_url("laporan_penjualan1/search_member") ?>',
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

        var searchgridlreturjualmember = new Ext.app.SearchField({
        store: strgridlreturjualmember,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE			
        },
        width: 350,
        id: 'id_searchgridlreturjualmember'
    });

        var gridlreturjualmember = new Ext.grid.GridPanel({
        store: strgridlreturjualmember,
        stripeRows: true,
        frame: true,
        border:true,
        columns: [{
            header: 'ID Member',
            dataIndex: 'kd_member',
            width: 80,
            sortable: true			
            
        },{
            header: 'Nama Member',
            dataIndex: 'nmmember',
            width: 300,
            sortable: true        
        }],
		tbar: new Ext.Toolbar({
	        items: [searchgridlreturjualmember]
	    }),
            bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridlreturjualmember,
            displayInfo: true
        }),
		listeners: {
			'rowdblclick': function(){			
				var sm = this.getSelectionModel();
				var sel = sm.getSelections();
				if (sel.length > 0) {	
                    Ext.getCmp('id_cblreturjualmember').setValue(sel[0].get('nmmember'));
                    menulreturjualmember.hide();
				}
			}
		}
    });

        var menulreturjualmember = new Ext.menu.Menu();
        menulreturjualmember.add(new Ext.Panel({
        title: 'Pilih Member',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridlreturjualmember],
        buttons: [{
            text: 'Close',
            handler: function(){
                menulreturjualmember.hide();
            }
        }]
    }));
    
    Ext.ux.TwinComboMemberlreturjual = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            strgridlreturjualmember.load();
            menulreturjualmember.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
	
	menulreturjualmember.on('hide', function(){
		var sf = Ext.getCmp('id_searchgridlreturjualmember').getValue();
		if( sf != ''){
			Ext.getCmp('id_searchgridlreturjualmember').setValue('');
			searchgridlreturjualmember.onTrigger2Click();
		}
	});
	
     

        var cblreturjualmember = new Ext.ux.TwinComboMemberlreturjual({
        fieldLabel: 'Kode Member',
        id: 'id_cblreturjualmember',
        store: strcblreturjualmember,
	mode: 'local',
        valueField: 'kd_member',
        displayField: 'nmmember',
        typeAhead: true,
        triggerAction: 'all',
        editable: false,
	anchor: '42%',
        hiddenName: 'kd_member',
        emptyText: 'Pilih member'
    });

     var headerlaporanreturjual = {
            buttonAlign: 'left',
            layout: 'form',
            border: false,
            labelWidth: 100,
			defaults: { labelSeparator: ''},
                items: [cblreturjualmember,lreturjual_cbstatus
                      
                ],
            buttons: [{
            text: 'Print',
			formBind:true,
            handler: function(){
                                //var kd_user= Ext.getCmp('id_cblp1user').getValue();
                                //var kd_shift= Ext.getCmp('id_cblp1shift').getValue();
                                //var kd_member= Ext.getCmp('id_cblp1member').getValue();
                                //var dari_tgl= Ext.getCmp('id_dari_tgl_lp1').getRawValue();
                                //var sampai_tgl= Ext.getCmp('id_smp_tgl_lp1').getRawValue();
				winlaporanreturjualprint.show();
                                Ext.getDom('laporanreturjualprint').src = '<?= site_url("laporan_penjualan_retur/print_form") ?>' ;
				//Ext.getDom('laporanpenjualan1print').src = '<?= site_url("laporan_penjualan1/print_form") ?>';			
			}
        },{
			text: 'Cancel',
			handler: function(){
				clearlaporanpenjualanretur();
			}
		}]
    };
        var winlaporanreturjualprint = new Ext.Window({
        id: 'id_winlaporanreturjualprint',
	Title: 'Print Laporan Penjualan Retur',
        closeAction: 'hide',
        width: 900,
        height: 450,
        layout: 'fit',
        border: false,
        html:'<iframe style="width:100%;height:390px;" id="laporanreturjualprint" src=""></iframe>'
    });
	
          
       var laporanpenjualanretur = new Ext.FormPanel({        
	 	id: 'rpt_penjualan_retur',		
		border: false,
		frame: true,
		monitorValid: true,
		labelWidth: 130,
        items: [{
                    bodyStyle: {
                        margin: '0px 0px 15px 0px'
                    },					
                    items: [headerlaporanreturjual]
                }
        ]
    });
	
	function clearlaporanpenjualanretur(){
		Ext.getCmp('rpt_penjualan_retur').getForm().reset();
			}
</script>