<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">

    /**
     * data untuk kolektor pre-flter
     */
    var strcbsuplierclose_bstt = new Ext.data.ArrayStore({
        fields: ['nama_collector'],
        data : []
    });

    var strgridpopkolektor_close_bstt = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_collector', 'nama_collector'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("close_bstt/search_collector") ?>',
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

    strgridpopkolektor_close_bstt.on('load', function(){
        Ext.getCmp('id_searchgridpopkolektor_close_bstt').focus();
    });

    var searchgridpopkolektor_close_bstt = new Ext.app.SearchField({
        store: strgridpopkolektor_close_bstt,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgridpopkolektor_close_bstt'
    });

    var gridpopkolektor_close_bstt = new Ext.grid.GridPanel({
        store: strgridpopkolektor_close_bstt,
        stripeRows: true,
        frame: true,
        border:true,
        columns: [{
            header: 'Kode Kolektor',
            dataIndex: 'kd_collector',
            width: 80,
            sortable: true

        },{
            header: 'Nama Kolektor',
            dataIndex: 'nama_collector',
            width: 300,
            sortable: true
        }],
        tbar: new Ext.Toolbar({
            items: [searchgridpopkolektor_close_bstt]
        }),
        listeners: {
            'rowdblclick': function(){
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('cbstt_kd_collector').setValue(sel[0].get('kd_collector'));
                    Ext.getCmp('id_cbpopkolektor_close_bstt').setValue(sel[0].get('nama_collector'));
                    menupopkolektor_close_bstt.hide();
                }
            }
        }
    });

    var menupopkolektor_close_bstt = new Ext.menu.Menu();

    menupopkolektor_close_bstt.add(new Ext.Panel({
        title: 'Pilih Kolektor',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridpopkolektor_close_bstt],
        buttons: [{
            text: 'Close',
            handler: function(){
                menupopkolektor_close_bstt.hide();
            }
        }]
    }));

    Ext.ux.TwinCombopopCollector = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            //load store grid
            strgridpopkolektor_close_bstt.load();
            menupopkolektor_close_bstt.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    menupopkolektor_close_bstt.on('hide', function(){
        var sf = Ext.getCmp('id_searchgridpopkolektor_close_bstt').getValue();
        if( sf != ''){
            Ext.getCmp('id_searchgridpopkolektor_close_bstt').setValue('');
            searchgridpopkolektor_close_bstt.onTrigger2Click();
        }
    });

    var cbpopsuplierclose_bstt = new Ext.ux.TwinCombopopCollector({
        fieldLabel: 'Kolektor',
        id: 'id_cbpopkolektor_close_bstt',
        store: strcbsuplierclose_bstt,
        mode: 'local',
        valueField: 'nama_collector',
        displayField: 'nama_collector',
        typeAhead: true,
        triggerAction: 'all',
        // allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'nama_collector',
        emptyText: 'Pilih Kolektor'
    });

    var headerclose_bstt = {
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
                fieldLabel: 'Kode Kolektor',
                name: 'kd_collector',
                readOnly: true,
                fieldClass: 'readonly-input',
                id: 'cbstt_kd_collector',
                anchor: '90%',
                value: '',
                emptyText: 'Kode Kolektor'
            },cbpopsuplierclose_bstt
            ]
        }],buttons: [{
            text: 'Filter',
            formBind: true,
            handler: function () {
                gridclose_bstt.store.load({
                    params: {
                        kd_collector: Ext.getCmp('cbstt_kd_collector').getValue()

                    }
                });
            }
        }]
    }

    /* data grid */
    var strclose_bstt = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'no_bstt',
                'tanggal',
                'total_faktur',
                
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({

            url: '<?= site_url("close_bstt/get_rows") ?>' ,
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
//                    kd_supplier: Ext.getCmp('cbstt_kd_collector').getValue(),
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
//                kd_supplier: Ext.getCmp('cbstt_kd_collector').getValue(),
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
    var search_close_bstt = new Ext.app.SearchField({
        store: strclose_bstt,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE,
            },
        width: 220,
        emptyText: 'No BSTT',
        id: 'idsearch_close_bstt'
    });
    strclose_bstt.on('load',function(){
        strclose_bstt.setBaseParam('kd_collector',Ext.getCmp('cbstt_kd_collector').getValue());
     });
    // top toolbar
    var tb_close_bstt = new Ext.Toolbar({
        items: [search_close_bstt, '->', '<i>Klik row untuk melihat detail  BSTT</i>']
    });
    // checkbox grid
    var smgridclose_bstt = new Ext.grid.CheckboxSelectionModel();
    var smgridDetclose_bstt = new Ext.grid.CheckboxSelectionModel();

    // data store
    var strclose_bsttdetail = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'kd_pelanggan',
                'nama_pelanggan',
                'rp_faktur',
                'no_faktur',
                'qty_po'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("close_bstt/get_rows_detail") ?>',
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

    strclose_bstt.on('load', function(){
        strclose_bsttdetail.removeAll();
    })

//    var editorpembelianapprovalordermanager = new Ext.ux.grid.RowEditor({
//        saveText: 'Update'
//    });

    var gridclose_bstt = new Ext.grid.EditorGridPanel({
        id: 'gridclose_bstt',
        frame: true,
        border: true,
        stripeRows: true,
        sm: smgridclose_bstt,
        store: strclose_bstt,
        loadMask: true,
        title: 'BSTT',
        style: 'margin:0 auto;',
        height: 225,
        // width: 550,
        columns: [{
            header: "No.BSTT",
            dataIndex: 'no_bstt',
            sortable: true,
            width: 120
        },{
            header: "Tanggal",
            dataIndex: 'tanggal',
            sortable: true,
            width: 120
        },{
            header: "Rp Faktur",
            dataIndex: 'total_faktur',
            // hidden: true,
            sortable: true,
            width: 150
        }],
        listeners: {
            'rowclick': function(){
                var sm = gridclose_bstt.getSelectionModel();
                var sel = sm.getSelections();
                gridDetclose_bstt.store.proxy.conn.url = '<?= site_url("close_bstt/get_rows_detail") ?>/' + sel[0].get('no_bstt');
                gridDetclose_bstt.store.reload();
            }
        },
        tbar: tb_close_bstt,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strclose_bstt,
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
                header: "Kode Pelanggan",
                dataIndex: 'kd_pelanggan',
                sortable: true,
                width: 120
            },{
                header: "Nama Pelanggan",
                dataIndex: 'nama_pelanggan',
                sortable: true,
                width: 150
            },{
                header: "No Faktur",
                dataIndex: 'no_faktur',
                sortable: true,
                width: 120
            },{
                header: "rp_faktur",
                dataIndex: 'rp_faktur',
                sortable: true,
                width: 100
            }]

    });

    var gridDetclose_bstt = new Ext.grid.EditorGridPanel({
        id: 'gridDetclose_bstt',
        store: strclose_bsttdetail,
        stripeRows: true,
        style: 'margin-bottom:5px;',
        height: 225,
        frame: true,
        border:true,
        loadMask: true,
        sm: smgridDetclose_bstt,
       // plugins: [action_approval_detail_approve_manager,action_approval_detail_reject_manager],
        view: new Ext.ux.grid.LockingGridView(),
        cm: cmm
    });

    var close_bstt = new Ext.FormPanel({
        id: 'close_bstt',
        border: false,
        frame: true,
        monitorValid: true,
        labelWidth: 130,
        items: [{
            bodyStyle: {
                margin: '0px 0px 15px 0px'
            },
            items: [headerclose_bstt]
        },
        gridclose_bstt, 
        gridDetclose_bstt

        ],
        buttons: [{
            text: 'Close BSTT',
            handler: function(){
             Ext.Msg.show({
                     title: 'Confirm',
                     msg: 'Apakah anda akan Close BSTT ini ??',
                     buttons: Ext.Msg.YESNO,
                     fn: function(btn){
                         if (btn == 'yes') {
                            var sm = gridclose_bstt.getSelectionModel();
                            var sel = gridclose_bstt.getSelectionModel().getSelections();

                            Ext.getCmp('close_bstt').getForm().submit({
                                url: '<?= site_url("close_bstt/update_row") ?>',
                                scope: this,
                                params: {
                                    no_bstt: sel[0].get('no_bstt')
                                },

                                waitMsg: 'Closing BSTT...',
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

                                    clearclose_bstt();
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
                clearclose_bstt();
            }
        }]
    });

    function clearclose_bstt(){
        Ext.getCmp('close_bstt').getForm().reset();
        strclose_bstt.removeAll();
        strclose_bsttdetail.removeAll();
    }

</script>