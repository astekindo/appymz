<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>

<script type="text/javascript">	
    var headercatatjurnal = {
        layout: 'column',
        border: false,
        items: [{
                columnWidth: .5,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: { labelSeparator: ''},
                items: [{
                        xtype: 'textfield',
                        fieldLabel: 'No.Jurnal',
                        name: 'kd_jurnal',
                        readOnly:true,
                        fieldClass:'readonly-input',
                        id: 'cj_kd_jurnal',                
                        anchor: '90%',
                        value:''
                    }, {
                        xtype: 'datefield',
                        fieldLabel: 'Tanggal Jurnal',
                        name: 'tgl_jurnal',
                        allowBlank:false,   
                        format:'d-m-Y', 
                        id: 'cj_tgl_jurnal',                
                        anchor: '90%',
                        value: ''
                    }]
            }, {
                columnWidth: .5,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: { labelSeparator: ''},
                items: [ {
                        xtype: 'textfield',
                        fieldLabel: 'Referensi',
                        name: 'referensi',				
                        id: 'cj_referensi',                
                        anchor: '90%',
                        value: ''
                    }
                    , {
                        xtype: 'textarea',
                        fieldLabel: 'Keterangan',
                        name: 'keterangan',				
                        id: 'cj_keterangan',                
                        anchor: '90%',
                        value: ''
                    }]
            }]
    }
    
    var footercatatjurnal = {
        layout: 'column',
        border: false,
        items: [{
                columnWidth: .3,
                layout: 'form',
                border: false,
                labelWidth: 80,
                defaults: { labelSeparator: ''},
                items: [{
                        xtype: 'numberfield',
                        fieldLabel: 'Total Debet',
                        name: 't_debet',
                        format:'0,0',
                        readOnly:true,
                        fieldClass:'readonly-input',
                        id: 'cj_t_debet',                
                        anchor: '90%',
                        value:'0'
                    } ]
            }, {
                columnWidth: .3,
                layout: 'form',
                border: false,
                labelWidth: 80,
                defaults: { labelSeparator: ''},
                items: [{
                        xtype: 'numberfield',
                        fieldLabel: 'Total Kredit',
                        name: 't_kredit',
//                        allowBlank:false, 
                        readOnly:true,
                        fieldClass:'readonly-input',
                        format:'0,0', 
                        id: 'cj_t_kredit',                
                        anchor: '90%',
                        value: '0'
                    } ]
            }, {
                columnWidth: .3,
                layout: 'form',
                border: false,
                labelWidth: 80,
                defaults: { labelSeparator: ''},
                items: [ {
                        xtype: 'numberfield',
                        fieldLabel: 'Selisih',
                        name: 't_selisih',
                        readOnly:true,
                        fieldClass:'readonly-input',
//                        allowBlank:false,   
                        format:'0,0', 
                        id: 'cj_t_selisih',                
                        anchor: '90%',
                        value: '0'
                    }]
            }]
    }
    
    var strcatatjurnal = new Ext.data.Store({        
        autoSave:false,
        reader: new Ext.data.JsonReader({
            fields: [
                {name: 'kd_akun', allowBlank: false, type: 'string'},
                {name: 'dk', allowBlank: false, type: 'string'},                           
                {name: 'debet', allowBlank: false, type: 'int'},
                {name: 'kredit', allowBlank: false, type: 'int'}
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        writer: new Ext.data.JsonWriter(
        {
            encode: true,
            writeAllFields: true
        })
    });
    // twin master akun
    var strcb_akun_cj = new Ext.data.ArrayStore({
        fields: ['kd_akun'],
        data : []
    });
	
    var strgrid_akun_cj = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_akun', 'nama','dk'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("account_entry_voucher/get_search_akun") ?>',
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
	
    var searchgrid_akun_cj = new Ext.app.SearchField({
        store: strgrid_akun_cj,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE			
        },
        width: 350,
        id: 'id_searchgrid_akun_cj'
    });
	
	
    var grid_akun_cj = new Ext.grid.GridPanel({
        
        //id:'id_searchgrid_akun_cj',
        store: strgrid_akun_cj,
        stripeRows: true,
        frame: true,
        border:true,
        columns: [{
                header: 'Kode Akun',
                dataIndex: 'kd_akun',
                width: 80,
                sortable: true			
            
            },{
                header: 'Nama Akun',
                dataIndex: 'nama',
                width: 300,
                sortable: true         
            },{
                header: 'D/K',
                dataIndex: 'dk',
                width: 50,
                sortable: true         
            }],
        tbar: new Ext.Toolbar({
            items: [searchgrid_akun_cj]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgrid_akun_cj,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function(){			
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {				
                    Ext.getCmp('ecj_kd_akun').setValue(sel[0].get('kd_akun'));
                    Ext.getCmp('ecj_nama_akun').setValue(sel[0].get('nama'));
                    Ext.getCmp('ecj_dk').setValue(sel[0].get('dk'));                       
                    menu_akun_cj.hide();
                }
            }
        }
    });
	
    var menu_akun_cj = new Ext.menu.Menu();
    menu_akun_cj.add(new Ext.Panel({
        title: 'Pilih Akun',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [grid_akun_cj],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menu_akun_cj.hide();
                }
            }]
    }));
    
    Ext.ux.TwinComboAkunJurnal = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            //load store grid
            strgrid_akun_cj.load();
            menu_akun_cj.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
	
    menu_akun_cj.on('hide', function(){
        var sf = Ext.getCmp('id_searchgrid_akun_cj').getValue();
        if( sf != ''){
            Ext.getCmp('id_searchgrid_akun_cj').setValue('');
            searchgrid_akun_cj.onTrigger2Click();
        }
    });
	
   
    
    //==============
    var cmcatatjurnal=new Ext.grid.ColumnModel({
        defaults: {
            sortable: true // columns are not sortable by default           
        },
        columns: [{
                //            xtype: 'numbercolumn',
                header: 'Kode Akun',
                dataIndex: 'kd_akun',
                width: 100,
                format: '0',
                sortable: true,	
                editor: new Ext.ux.TwinComboAkunJurnal({
                    id: 'ecj_kd_akun',
                    store: strcb_akun_cj,
                    mode: 'local',
                    valueField: 'kd_akun',
                    displayField: 'kd_akun',
                    typeAhead: true,
                    triggerAction: 'all',
                    allowBlank: true ,
                    editable: false,
                    hiddenName: 'kd_akun',
                    emptyText: 'Pilih Akun'
				
                })		
			
            },{
                
                header: 'Nama Akun',
                dataIndex: 'nama',
                width: 300,
                // use shorthand alias defined above
                editor: new Ext.form.TextField({
                    id: 'ecj_nama_akun',
//                    allowBlank: false,
                    readOnly: true
                })
            },{
                
                header: 'D/K',
                dataIndex: 'dk',
                width: 50,
                hidden:true,
                // use shorthand alias defined above
                editor: new Ext.form.TextField({
                    id: 'ecj_dk',
                    allowBlank: false,
                    readOnly: true
                })
            },{
                xtype: 'numbercolumn',
                header: 'Debet',
                dataIndex: 'debet',			
                width: 120,
                sortable: true,
                format: '0,0',
                editor: {
                    xtype: 'numberfield',
                    id: 'ecj_debet',
                    allowBlank: true}},{
                xtype: 'numbercolumn',
                header: 'Kredit',
                dataIndex: 'kredit',			
                width: 120,
                sortable: true,
                format: '0,0',
                editor: {
                    xtype: 'numberfield',
                    id: 'ecj_kredit',
                    allowBlank: true}}
        ]
    });
    var editorcatatjurnal = new Ext.ux.grid.RowEditor({
        saveText: 'Update'
    });
    var gridcatatjurnal=new Ext.grid.GridPanel({
        store: strcatatjurnal,
        cm: cmcatatjurnal,        
        
        height: 300,
        //        autoExpandColumn: 'common', // column with this id will be expanded
        //title: 'Edit Plants?',
        stripeRows: true,
        frame: true,
        border:true,
        // specify the check column plugin on the grid so the plugin is initialized
        plugins: [editorcatatjurnal],
        clicksToEdit: 1,
        tbar: [{
                icon: BASE_ICONS + 'add.png',
                text: 'Add',
                handler : function(){
                    // access the Record constructor through the grid's store
                    var Plant = gridcatatjurnal.getStore().recordType;
                    var rowentryvoucher = new Plant({
                        kd_akun: '',
                        nama: '',
                        dk: '',
                        debet:0,
                        kredit:0
                    
                    });
                    editorcatatjurnal.stopEditing();
                    strcatatjurnal.insert(0, rowentryvoucher);
                    gridcatatjurnal.getView().refresh();
                    gridcatatjurnal.getSelectionModel().selectRow(0);
                    editorcatatjurnal.startEditing(0);
                
                  
                }
            },{
                ref: '../removeBtn',
                icon: BASE_ICONS + 'delete.gif',
                text: 'Remove',
                disabled: true,
                handler: function(){
                    editorcatatjurnal.stopEditing();
                    var s = gridcatatjurnal.getSelectionModel().getSelections();
                    for(var i = 0, r; r = s[i]; i++){
                        strcatatjurnal.remove(r);
                    }
                }
            }]
    });
    
    gridcatatjurnal.getSelectionModel().on('selectionchange', function(sm){
        gridcatatjurnal.removeBtn.setDisabled(sm.getCount() < 1);
    });
    var catatjurnal_form = new Ext.FormPanel({
        id: 'catatjurnal',
        border: false,
        frame: true,
        autoScroll:true, 
        monitorValid: true,       
        bodyStyle:'padding-right:20px;',
        labelWidth: 130,
        items: [
            {
                bodyStyle: {
                    margin: '0px 0px 15px 0px'
                },                  
                items: [headercatatjurnal]
            },
            gridcatatjurnal,{
                bodyStyle: {
                    margin: '5px 0px 15px 0px'
                },                  
                items: [footercatatjurnal]
            }
        ],
        buttons: [{
                text: 'Save',
                formBind: true,
                handler: function(){               
                    
                }
            },{
                text: 'Reset',
                handler: function(){
                
                }
            }]
        //            ,
        //        listeners:{
        //            onShow:function(){
        //                
        //            }
        //        }
    });
    
</script>
