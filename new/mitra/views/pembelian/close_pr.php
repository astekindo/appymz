<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">

    /**
     * data untuk supplier pre-flter
     */
    var strcbsuplierclose_request = new Ext.data.ArrayStore({
        fields: ['nama_supplier'],
        data : []
    });

    var strgridpopsuplier_close_request = new Ext.data.Store({
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

    strgridpopsuplier_close_request.on('load', function(){
        Ext.getCmp('id_searchgridpopsuplier_close_request').focus();
    });

    var searchgridpopsuplier_close_request = new Ext.app.SearchField({
        store: strgridpopsuplier_close_request,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgridpopsuplier_close_request'
    });

    var gridpopsuplier_close_request = new Ext.grid.GridPanel({
        store: strgridpopsuplier_close_request,
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
            items: [searchgridpopsuplier_close_request]
        }),
        listeners: {
            'rowdblclick': function(){
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('cpr_kd_supplier').setValue(sel[0].get('kd_supplier'));
                    Ext.getCmp('id_cbpopsuplier_close_request').setValue(sel[0].get('nama_supplier'));
                    menupopsuplier_close_request.hide();
                }
            }
        }
    });

    /**
     * data popup pemilihan supplier
     */
    var menupopsuplier_close_request = new Ext.menu.Menu();

    menupopsuplier_close_request.add(new Ext.Panel({
        title: 'Pilih Supplier',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridpopsuplier_close_request],
        buttons: [{
            text: 'Close',
            handler: function(){
                menupopsuplier_close_request.hide();
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
            strgridpopsuplier_close_request.load();
            menupopsuplier_close_request.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    menupopsuplier_close_request.on('hide', function(){
        var sf = Ext.getCmp('id_searchgridpopsuplier_close_request').getValue();
        if( sf != ''){
            Ext.getCmp('id_searchgridpopsuplier_close_request').setValue('');
            searchgridpopsuplier_close_request.onTrigger2Click();
        }
    });

    var cbpopsuplierclose_request = new Ext.ux.TwinCombopopSuplier({
        fieldLabel: 'Supplier',
        id: 'id_cbpopsuplier_close_request',
        store: strcbsuplierclose_request,
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

    var headerclose_request = {
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
                id: 'cpr_kd_supplier',
                anchor: '90%',
                value: '',
                emptyText: 'Kode Supplier'
            },cbpopsuplierclose_request
            ]
        }],buttons: [{
            text: 'Filter',
            formBind: true,
            handler: function () {
                gridclose_request.store.load({
                    params: {
                        kd_supplier: Ext.getCmp('cpr_kd_supplier').getValue()

                    }
                });
            }
        }]
    }

    /* data grid */
    var strclose_request = new Ext.data.Store({
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

            url: '<?= site_url("pembelian_close_pr/get_rows") ?>' ,
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

    //custom searchField
//    Ext.app.SearchFieldWithId = Ext.extend(Ext.form.TwinTriggerField, {
//        initComponent : function() {
//            Ext.app.SearchField.superclass.initComponent.call(this);
//            this.on('specialkey', function(f, e) {
//                if (e.getKey() == e.ENTER) {
//                    this.onTrigger2Click();
//                }
//            }, this);
//        },
//
//        validationEvent : false,
//        validateOnBlur : false,
//        trigger1Class : 'x-form-clear-trigger',
//        trigger2Class : 'x-form-search-trigger',
//        hideTrigger1 : true,
//        width : 180,
//        emptyText : 'Quick Search ...',
//        hasSearch : false,
//        paramName : 'query',
//
//        onTrigger1Click : function() {
//            if (this.hasSearch) {
//                this.el.dom.value = '';
//                var o = {
//                    kd_supplier: Ext.getCmp('cpr_kd_supplier').getValue(),
//                    start : STARTPAGE,
//                    limit : ENDPAGE
//                };
//                this.store.baseParams = this.store.baseParams || {};
//                this.store.baseParams[this.paramName] = '';
//                this.store.reload({
//                    params : o
//                });
//                this.triggers[0].hide();
//                this.hasSearch = false;
//            }
//        },
//
//        onTrigger2Click : function() {
//            var v = this.getRawValue();
//            if (v.length < 1) {
//                this.onTrigger1Click();
//                return;
//            }
//            var o = {
//                kd_supplier: Ext.getCmp('cpr_kd_supplier').getValue(),
//                start : STARTPAGE,
//                limit : ENDPAGE
//            };
//            this.store.baseParams = this.store.baseParams || {};
//            this.store.baseParams[this.paramName] = v;
//            this.store.reload({
//                params : o
//            });
//            this.hasSearch = true;
//            this.triggers[0].show();
//        }
//    });
//    // search field
    var search_close_request = new Ext.app.SearchField({
        store: strclose_request,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE,
            },
        width: 220,
        emptyText: 'No PR',
        id: 'idsearch_close_request'
    });
    strclose_request.on('load',function(){
        strclose_request.setBaseParam('kd_supplier',Ext.getCmp('cpr_kd_supplier').getValue());
     });
    // top toolbar
    var tb_close_request = new Ext.Toolbar({
        items: [search_close_request, '->', '<i>Klik row untuk melihat detail  PR</i>']
    });
    // checkbox grid
    var smgridclose_request = new Ext.grid.CheckboxSelectionModel();
    var smgridDetclose_request = new Ext.grid.CheckboxSelectionModel();

    // data store
    var strclose_requestdetail = new Ext.data.Store({
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
            url: '<?= site_url("pembelian_close_pr/get_rows_detail") ?>',
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

    strclose_request.on('load', function(){
        strclose_requestdetail.removeAll();
    })

//    var editorpembelianapprovalordermanager = new Ext.ux.grid.RowEditor({
//        saveText: 'Update'
//    });

    var gridclose_request = new Ext.grid.EditorGridPanel({
        id: 'gridclose_request',
        frame: true,
        border: true,
        stripeRows: true,
        sm: smgridclose_request,
        store: strclose_request,
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
                var sm = gridclose_request.getSelectionModel();
                var sel = sm.getSelections();
                gridDetclose_request.store.proxy.conn.url = '<?= site_url("pembelian_close_pr/get_rows_detail") ?>/' + sel[0].get('no_ro');
                gridDetclose_request.store.reload();
            }
        },
        tbar: tb_close_request,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strclose_request,
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

    var gridDetclose_request = new Ext.grid.EditorGridPanel({
        id: 'gridDetclose_request',
        store: strclose_requestdetail,
        stripeRows: true,
        style: 'margin-bottom:5px;',
        height: 225,
        frame: true,
        border:true,
        loadMask: true,
        sm: smgridDetclose_request,
       // plugins: [action_approval_detail_approve_manager,action_approval_detail_reject_manager],
        view: new Ext.ux.grid.LockingGridView(),
        cm: cmm
    });

    var close_request = new Ext.FormPanel({
        id: 'closepurchaserequest',
        border: false,
        frame: true,
        monitorValid: true,
        labelWidth: 130,
        items: [{
            bodyStyle: {
                margin: '0px 0px 15px 0px'
            },
            items: [headerclose_request]
        },
        gridclose_request, 
        gridDetclose_request

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
                            var sm = gridclose_request.getSelectionModel();
                            var sel = gridclose_request.getSelectionModel().getSelections();

                            Ext.getCmp('closepurchaserequest').getForm().submit({
                                url: '<?= site_url("pembelian_close_pr/update_row") ?>',
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

                                    clearclose_request();
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
                clearclose_request();
            }
        }]
    });

    function clearclose_request(){
        Ext.getCmp('closepurchaserequest').getForm().reset();
        strclose_request.removeAll();
        strclose_requestdetail.removeAll();
    }

</script>