<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">
//Combo Supplier
    var strcbsuplierclose_pr_kons = new Ext.data.ArrayStore({
        fields: ['nama_supplier'],
        data : []
    });

    var strgridpopsuplier_close_pr_kons = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_supplier', 'nama_supplier'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("pembelian_create_request/search_supplier") ?>',
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

    strgridpopsuplier_close_pr_kons.on('load', function(){
        Ext.getCmp('id_searchgridpopsuplier_close_pr_kons').focus();
    });

    var searchgridpopsuplier_close_pr_kons = new Ext.app.SearchField({
        store: strgridpopsuplier_close_pr_kons,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgridpopsuplier_close_pr_kons'
    });

    var gridpopsuplier_close_pr_kons = new Ext.grid.GridPanel({
        store: strgridpopsuplier_close_pr_kons,
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
            items: [searchgridpopsuplier_close_pr_kons]
        }),
        listeners: {
            'rowdblclick': function(){
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('id_kd_supplier_kons').setValue(sel[0].get('kd_supplier'));
                    Ext.getCmp('id_suplier_close_pr_kons').setValue(sel[0].get('nama_supplier'));
                    menupopsuplier_close_pr_kons.hide();
                }
            }
        }
    });

    /**
     * data popup pemilihan supplier
     */
    var menupopsuplier_close_pr_kons = new Ext.menu.Menu();

    menupopsuplier_close_pr_kons.add(new Ext.Panel({
        title: 'Pilih Supplier',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridpopsuplier_close_pr_kons],
        buttons: [{
            text: 'Close',
            handler: function(){
                menupopsuplier_close_pr_kons.hide();
            }
        }]
    }));

    Ext.ux.TwinCombopopSuplier = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            //load store grid
            strgridpopsuplier_close_pr_kons.load();
            menupopsuplier_close_pr_kons.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    menupopsuplier_close_pr_kons.on('hide', function(){
        var sf = Ext.getCmp('id_searchgridpopsuplier_close_pr_kons').getValue();
        if( sf != ''){
            Ext.getCmp('id_searchgridpopsuplier_close_pr_kons').setValue('');
            searchgridpopsuplier_close_pr_kons.onTrigger2Click();
        }
    });

    var cbpopsuplierclose_pr_kons = new Ext.ux.TwinCombopopSuplier({
        fieldLabel: 'Supplier',
        id: 'id_suplier_close_pr_kons',
        store: strcbsuplierclose_pr_kons,
        mode: 'local',
        valueField: 'nama_supplier',
        displayField: 'nama_supplier',
        typeAhead: true,
        triggerAction: 'all',
        // allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'nama_supplier',
        emptyText: 'Pilih Supplier'
    });
// HEADER Close PR
    var headerclose_pr_kons = {
        layout: 'column',
        border: false,
        buttonAlign: 'left',
        items: [{
            columnWidth: .5,
            layout: 'form',
            border: false,
            labelWidth: 100,
            defaults: { labelSeparator: ''},
            items: [{
                xtype: 'textfield',
                fieldLabel: 'Kode Supplier',
                name: 'kd_supplier',
                readOnly: true,
                fieldClass: 'readonly-input',
                id: 'id_kd_supplier_kons',
                anchor: '90%',
                value: '',
                emptyText: 'Kode Supplier'
            },cbpopsuplierclose_pr_kons
            ]
        }],buttons: [{
            text: 'Filter',
            formBind: true,
            handler: function () {
                gridclose_pr_kons.store.load({
                    params: {
                        kd_supplier: Ext.getCmp('id_kd_supplier_kons').getValue()

                    }
                });
            }
        }]
    }
 /* data grid */
    var strclose_pr_kons = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'no_ro',
                'tgl_ro',
                'subject',
                'waktu_top',
                'keterangan1',
                'keterangan2'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({

            url: '<?= site_url("konsinyasi_close_pr/get_rows") ?>' ,
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
    
     var search_close_pr_kons = new Ext.app.SearchField({
        store: strclose_pr_kons,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
            emptyText: 'No PR',
        id: 'idsearch_close_pr_kons'
    });
    strclose_pr_kons.on('load',function(){
        strclose_pr_kons.setBaseParam('kd_supplier',Ext.getCmp('id_kd_supplier_kons').getValue());
     });
    
    // top toolbar
    var tb_close_pr_kons = new Ext.Toolbar({
        items: [search_close_pr_kons, '->', '<i>Klik row untuk melihat detail PR</i>']
    });
    // checkbox grid
    var smgridclose_pr_kons = new Ext.grid.CheckboxSelectionModel();
    var smgridDetclose_pr_kons = new Ext.grid.CheckboxSelectionModel();

    // data store
    var strclose_prdetail_kons = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'kd_produk',
                'nama_produk',
                'qty',
                'qty_adj',
                'qty_po'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("konsinyasi_close_pr/get_rows_detail") ?>',
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

    strclose_pr_kons.on('load', function(){
        strclose_prdetail_kons.removeAll();
    })

    var editorpembelianapprovalordermanager = new Ext.ux.grid.RowEditor({
        saveText: 'Update'
    });

    var gridclose_pr_kons = new Ext.grid.EditorGridPanel({
        id: 'gridclose_pr_kons',
        frame: true,
        border: true,
        stripeRows: true,
        sm: smgridclose_pr_kons,
        store: strclose_pr_kons,
        loadMask: true,
        title: 'PR',
        style: 'margin:0 auto;',
        height: 225,
        // width: 550,
        columns: [{
            header: "No. PR",
            dataIndex: 'no_ro',
            sortable: true,
            width: 150
        },{
            header: "Tanggal PR",
            dataIndex: 'tgl_ro',
            sortable: true,
            width: 250
        },{
            header: "Subject",
            dataIndex: 'subject',
            // hidden: true,
            sortable: true,
            width: 150
        },{
            header: "Waktu Top",
            dataIndex: 'waktu_top',
            sortable: true,
            width: 80
        },{
            header: "Keterangan 1",
            dataIndex: 'keterangan1',
            // hidden: true,
            sortable: true,
            width: 150
        },{
            header: "Keterangan 2",
            dataIndex: 'keterangan2',
            sortable: true,
            width: 80
        }],
        listeners: {
            'rowclick': function(){
                var sm = gridclose_pr_kons.getSelectionModel();
                var sel = sm.getSelections();
                gridDetclose_pr_kons.store.proxy.conn.url = '<?= site_url("pembelian_close_pr/get_rows_detail") ?>/' + sel[0].get('no_ro');
                gridDetclose_pr_kons.store.reload();
            }
        },
        tbar: tb_close_pr_kons,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strclose_pr_kons,
            displayInfo: true
        })
    });

    // shorthand alias
    var fm = Ext.form;

    var cmm = new Ext.ux.grid.LockingColumnModel({
        // specify any defaults for each column
        defaults: {
            sortable: true // columns are not sortable by default
        },
        columns: [{
                header: "Kode Barang",
                dataIndex: 'kd_produk',
                sortable: true,
                width: 250
            },{
                header: "Nama Barang",
                dataIndex: 'nama_produk',
                sortable: true,
                width: 250
            },{
                header: "Qty",
                dataIndex: 'qty',
                sortable: true,
                width: 50
            },{
                header: "Qty Adj",
                dataIndex: 'qty_adj',
                sortable: true,
                width: 50
            },{
                header: "Qty PO",
                dataIndex: 'qty_po',
                sortable: true,
                width: 50
            }]

    });

    var gridDetclose_pr_kons = new Ext.grid.EditorGridPanel({
        id: 'gridDetclose_pr_kons',
        store: strclose_prdetail_kons,
        stripeRows: true,
        style: 'margin-bottom:5px;',
        height: 225,
        frame: true,
        border:true,
        loadMask: true,
        sm: smgridDetclose_pr_kons,
        plugins: [action_approval_detail_approve_manager,action_approval_detail_reject_manager],
        view: new Ext.ux.grid.LockingGridView(),
        cm: cmm
    });
  // Form Panel  
var close_pr_kons = new Ext.FormPanel({
        id: 'konsinyasicloserequest',
        border: false,
        frame: true,
        monitorValid: true,
        labelWidth: 130,
        items: [{
            bodyStyle: {
                margin: '0px 0px 15px 0px'
            },
            items: [headerclose_pr_kons]
        },gridclose_pr_kons, gridDetclose_pr_kons

        ],
        buttons: [{
            text: 'Close PR',
            handler: function(){
              Ext.Msg.show({
                     title: 'Confirm',
                     msg: 'Apakah anda akan Close PR ini ??',
                     buttons: Ext.Msg.YESNO,
                     fn: function(btn){
                         if (btn == 'yes') {
                            var sm = gridclose_pr_kons.getSelectionModel();
                            var sel = gridclose_pr_kons.getSelectionModel().getSelections();

                            Ext.getCmp('konsinyasicloserequest').getForm().submit({
                                url: '<?= site_url("konsinyasi_close_pr/update_row") ?>',
                                scope: this,
                                params: {
                                    no_ro: sel[0].get('no_ro')
                                },

                                waitMsg: 'Closing PR...',
                                success: function(form, action){
                                    var r = Ext.util.JSON.decode(action.response.responseText);
                                    Ext.Msg.show({
                                        title: 'Success',
                                        msg: r.errMsg,
                                        modal: true,
                                        icon: Ext.Msg.INFO,
                                        buttons: Ext.Msg.OK,
                                        fn: function(btn){
                                            if (btn == 'ok') {
                                                //winreturpenjualanprint.show();
                                                // Ext.getDom('returpenjualanprint').src = r.printUrl;
                                            }
                                        }
                                    });

                                    clearclose_pr_kons();
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
                    }
              }); 
            }
        },{
            text: 'Reset',
            handler: function(){
                clearclose_pr_kons();
            }
        }]
    });

    function clearclose_pr_kons(){
        Ext.getCmp('konsinyasicloserequest').getForm().reset();
        strclose_pr_kons.removeAll();
        strclose_prdetail_kons.removeAll();
    }
</script>
