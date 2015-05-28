<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript"> 
        var strcbldssuplier = new Ext.data.ArrayStore({
        fields: ['kd_supplier'],
        data : []
    });
	
     
        var strgridldssuplier = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_supplier', 'nama_supplier'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("laporan_daftar_supplier/search_supplier") ?>',
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
	
        var searchgridldssuplier = new Ext.app.SearchField({
        store: strgridldssuplier,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE			
        },
        width: 350,
        id: 'id_searchgridldssuplier'
    });
	
	var gridldssuplier = new Ext.grid.GridPanel({
        store: strgridldssuplier,
        stripeRows: true,
        frame: true,
        border:true,
        columns: [{
            header: 'Kode Supplier',
            dataIndex: 'kd_supplier',
            width: 80,
            sortable: true			
            
        },{
            header: 'Nama Supplier',
            dataIndex: 'nama_supplier',
            width: 300,
            sortable: true        
        }],
		tbar: new Ext.Toolbar({
	        items: [searchgridldssuplier]
	    }),
            bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridldssuplier,
            displayInfo: true
        }),
		listeners: {
			'rowdblclick': function(){			
				var sm = this.getSelectionModel();
				var sel = sm.getSelections();
				if (sel.length > 0) {				
                    Ext.getCmp('lds_kd_supplier').setValue(sel[0].get('kd_supplier'));
                    Ext.getCmp('id_dftrbsuplier').setValue(sel[0].get('kd_supplier'));
                    // strlaporanpenerimaanbarang.removeAll();       
                    menuldssuplier.hide();
				}
			}
		}
    });
	
        var menuldssuplier = new Ext.menu.Menu();
        menuldssuplier.add(new Ext.Panel({
        title: 'Pilih Supplier',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridldssuplier],
        buttons: [{
            text: 'Close',
            handler: function(){
                menuldssuplier.hide();
            }
        }]
    }));
    
    Ext.ux.TwinComboSuplier = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
			//load store grid
            strgridldssuplier.load();
            menuldssuplier.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
	
	menuldssuplier.on('hide', function(){
		var sf = Ext.getCmp('id_searchgridldssuplier').getValue();
		if( sf != ''){
			Ext.getCmp('id_searchgridldssuplier').setValue('');
			searchgridldssuplier.onTrigger2Click();
		}
	});
	
        var cbldsuplier = new Ext.ux.TwinComboSuplier({
        fieldLabel: 'Kode Supplier',
        id: 'id_dftrbsuplier',
        store: strcbldssuplier,
	mode: 'local',
        valueField: 'kd_supplier',
        displayField: 'kd_supplier',
        typeAhead: true,
        triggerAction: 'all',
        // allowBlank: false,
        editable: false,
	anchor: '42%',
        hiddenName: 'nama_supplier',
        emptyText: 'Pilih Supplier'
    });
	

    var headerlaporandaftarsupplier = {
        
            layout: 'form',
            border: false,
            labelWidth: 100,
            buttonAlign: 'left',
			defaults: { labelSeparator: ''},
                items: [{
				xtype: 'hidden',
				name: 'kd_supplier',
				id: 'lds_kd_supplier',
				value: ''
			},cbldsuplier
			],
                buttons: [{
            text: 'Print',
			formBind:true,
            handler: function(){
                                                              
                   /* Ext.getCmp('rpt_daftar_supplier').getForm().submit({
                        url: '<?= site_url("laporan_daftar_supplier/print_form") ?>',
                        scope: this,
                        params: {						
                                                                               					
                                    }
                          
                    }); */
                   var kd_supplier= Ext.getCmp('id_dftrbsuplier').getValue();
                               // Ext.getCmp('rpt_daftar_supplier').getForm().submit;			
				winlaporandaftarsupplierprint.show();
                  Ext.getDom('laporandaftarsupplierprint').src = '<?= site_url("laporan_daftar_supplier/print_form") ?>'+'/'+kd_supplier;
				//Ext.getDom('laporandaftarsupplierprint').src = '<?= site_url("laporan_daftar_supplier/print_form") ?>';			
			
                        }
        },{
			text: 'Cancel',
			handler: function(){
				clearlaporandaftarsupplier();
			}
		}]
    };
    
	
	
	var winlaporandaftarsupplierprint = new Ext.Window({
        id: 'id_winlaporandaftarsupplierprint',
	Title: 'Print Laporan Daftar Supplier',
        closeAction: 'hide',
        width: 900,
        height: 450,
        layout: 'fit',
        border: false,
        html:'<iframe style="width:100%;height:390px;" id="laporandaftarsupplierprint" src=""></iframe>'
    });
	
            var laporandaftarsupplier = new Ext.FormPanel({
        
	 	id: 'rpt_daftar_supplier',
		border: false,
		frame: true,
		monitorValid: true,
		labelWidth: 130,
        items: [{
                    bodyStyle: {
                        margin: '0px 0px 15px 0px'
                    },					
                    items: [headerlaporandaftarsupplier]
                }
        ]
        /*,
        buttons: [{
            text: 'Print',
			formBind:true,
            handler: function(){
                
                               // Ext.getCmp('rpt_daftar_supplier').getForm().submit;			
				winlaporandaftarsupplierprint.show();
				Ext.getDom('laporandaftarsupplierprint').src = '<?= site_url("laporan_daftar_supplier/print_form") ?>';			
			}
        },{
			text: 'Cancel',
			handler: function(){
				clearlaporandaftarsupplier();
			}
		}]*/
    });
	
	function clearlaporandaftarsupplier(){
		Ext.getCmp('rpt_daftar_supplier').getForm().reset();
		// strlaporanpenerimaanbarang.removeAll();
	}
</script>