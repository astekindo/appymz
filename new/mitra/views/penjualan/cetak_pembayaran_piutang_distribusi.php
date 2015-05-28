<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">


    //============================================start combo  sales oder=================================================================

    /**
     * deklarasi store sales order
     */
    var storeGridComboSalesOrder = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['no_so', 'tgl_so'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("cetak_pembayaran_piutang_distribusi_controller/finalGetSODistRows") ?>',
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
    /**
     * deklarasi search grid pelanggan
     */
    var searchGridSalesOrder = new Ext.app.SearchField({
        store: storeGridComboSalesOrder,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_search_grid_sales_order'
    });
    /**
     * deklarasi grid pelanggan
     */
    var gridSalesOrder = new Ext.grid.GridPanel({
        store: storeGridComboSalesOrder,
        stripeRows: true,
        frame: true,
        border: true,
        columns: [{
                header: 'No Sales Order',
                dataIndex: 'no_so',
                width: 150,
                sortable: true
            }, {
                header: 'Tanggal Sales Order',
                dataIndex: 'tgl_so',
                width: 300,
                sortable: true
            }],
        tbar: new Ext.Toolbar({
            items: [searchGridSalesOrder]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: storeGridComboSalesOrder,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                //var kdPelanggan = sel[0].get('kd_pelanggan');
                if (sel.length > 0) {
                    //kwitansiPenjualanGridStore.reload(
                    //{params: {kd_pelanggan: kdPelanggan}, }
                    //);
                    //Ext.getCmp('id_combo_pelanggan').setValue(sel[0].get('kd_pelanggan'));
                    Ext.getCmp('id_combo_sales_order').setValue(sel[0].get('no_so'));
                    menuSalesOrder.hide();
                }
            }
        }
    });

    /**
     * deklarasi menu pelanggan
     */
    var menuSalesOrder = new Ext.menu.Menu();
    menuSalesOrder.add(new Ext.Panel({
        title: 'Pilih Sales Order',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridSalesOrder],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menuSalesOrder.hide();
                }
            }]
    }));
    /**
     * deklarasi twin combo pelanggan
     * @returns {undefined} */
    Ext.ux.TwincomboKdPelanggan = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            //load store grid
            storeGridComboSalesOrder.load();
            menuSalesOrder.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
                trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
    menuSalesOrder.on('hide', function() {
        var sf = Ext.getCmp('id_search_grid_sales_order').getValue();
        if (sf !== '') {
            Ext.getCmp('id_search_grid_sales_order').setValue('');
            searchGridSalesOrder.onTrigger2Click();
        }
    });
    var comboSalesOrder = new Ext.ux.TwincomboKdPelanggan({
        fieldLabel: 'Sales Order <span class="asterix">*</span>',
        id: 'id_combo_sales_order',
        store: storeGridComboSalesOrder,
        mode: 'local',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        anchor: '90%',
        //width: 150,
        emptyText: 'Pilih Sales Order'
    });
//==============================================end combo kode pelanggan==================================================================


    /**
     * deklarasi store sales order
     */
    var storeGridPembayaranPiutangDistPrint = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['no_pembayaran_piutang', 'tgl_bayar', 'nomor_faktur', 'rp_faktur', 'rp_kurang_bayar', 'rp_bayar', 'keterangan', 'total_potongan'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("cetak_pembayaran_piutang_distribusi_controller/finalGetRows") ?>',
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


    /**
     * deklarasi store untuk grid detail pembayaran piutang
     */
    var storeDetailPembayaranPiutangDistPrint = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'no_pelunasan_piutang',
                'nof',
                'rp_faktur',
                'tgl_faktur',
                'rp_bayar',
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("cetak_pembayaran_piutang_distribusi_controller/finalGetRowDetail") ?>',
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


    /**
     *deklarasi search field 
     */
    var searchPembayaranPiutang = new Ext.app.SearchField({
        store: storeGridPembayaranPiutangDistPrint,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        id: 'id_search_pembayaran_piutang'
    });

    searchPembayaranPiutang.onTrigger1Click = function(evt) {
        if (this.hasSearch) {
            this.el.dom.value = '';
            // Get the value of search field
            var fid = Ext.getCmp('id_search_pembayaran_piutang').getValue();
            var o = {start: 0, fieldId: fid};
            this.store.baseParams = this.store.baseParams || {};
            this.store.baseParams[this.paramName] = '';
            this.store.reload({
                params: o
            });
            this.triggers[0].hide();
            this.hasSearch = false;
        }
    };
    searchPembayaranPiutang.onTrigger2Click = function(evt) {
        var text = this.getRawValue();
        if (text.length < 1) {
            this.onTrigger1Click();
            return;
        }
        // Get the value of search field
        var fid = Ext.getCmp('id_search_pembayaran_piutang').getValue();
        var o = {start: 0, fieldId: fid};
        this.store.baseParams = this.store.baseParams || {};
        this.store.baseParams[this.paramName] = text;
        this.store.reload({params: o});
        this.hasSearch = true;
        this.triggers[0].show();
    };

    //toolbar grid 1
    var topToolbarGridPembayaranPiutangDistPrint = new Ext.Toolbar({
        items: [searchPembayaranPiutang,
            {
                icon: BASE_ICONS + 'grid.png',
                text: 'View Pembayaran',
                handler: function() {

                    var sm = gridPembayaranPiutangDistPrint.getSelectionModel();
                    var sel = sm.getSelections();

                    if (sel.length > 0) {

                        Ext.Ajax.request({
                            url: '<?= site_url("cetak_pembayaran_piutang_distribusi_controller/finalGetDataPembayaran") ?>',
                            method: 'POST',
                            params: {
                                no_pembayaran: sel[0].get('no_pembayaran_piutang')
                            },
                            callback: function(opt, success, responseObj) {
                                var windowviewpembayaranpiutang = new Ext.Window({
                                    title: 'View Pembayaran Piutang',
                                    width: 850,
                                    height: 500,
                                    autoScroll: true,
                                    html: responseObj.responseText
                                });

                                windowviewpembayaranpiutang.show();

                            }
                        });
                    }

                }
            }]
    });


    /**
     * deklarasi selection model gridPembayaranPiutangDistPrint
     */
    var smGridPembayaranPiutangDistPrint = new Ext.grid.CheckboxSelectionModel();
    var smGridDetailPembayaranPiutangDistPrint = new Ext.grid.CheckboxSelectionModel();

    /**
     * deklarasi grid cetak pembayaran piutang distribusi
     */

    var gridPembayaranPiutangDistPrint = new Ext.grid.EditorGridPanel({
        id: 'id_grid_pembayaran_piutang_dist_print',
        title: 'Data Pembayaran Piutang Distribusi',
        store: storeGridPembayaranPiutangDistPrint,
        stripeRows: true,
        style: 'margin-bottom:5px;',
        height: 225,
        frame: true,
        border: true,
        loadMask: true,
        sm: smGridPembayaranPiutangDistPrint,
        // plugins: [action_approval_detail_approve_manager,action_approval_detail_reject_manager],
        columns: [{
                header: "No Bukti",
                dataIndex: 'no_pembayaran_piutang',
                sortable: true,
                width: 150
            },
            {
                header: "Tanggal Pembayaran",
                dataIndex: 'tgl_bayar',
                sortable: true,
                width: 130
            }, {
                xtype: 'numbercolumn',
                header: 'Jumlah Faktur/Struk',
                dataIndex: 'rp_faktur',
                width: 120,
                sortable: true,
                format: '0,0',
                align: 'right'

            }, {
                xtype: 'numbercolumn',
                header: 'Rp Bayar',
                dataIndex: 'rp_bayar',
                width: 180,
                sortable: true,
                format: '0,0',
                align: 'right'

            }, {
                header: 'Keterangan',
                dataIndex: 'keterangan',
                width: 200,
                sortable: true,
                align: 'right'

            }],
        listeners: {
            rowclick: function() {
                var sm = gridPembayaranPiutangDistPrint.getSelectionModel();
                var sel = sm.getSelections();
                storeDetailPembayaranPiutangDistPrint.reload({
                    params: {
                        no_pembayaran: sel[0].get('no_pembayaran_piutang')
                    }
                });
            }
        },
        tbar: topToolbarGridPembayaranPiutangDistPrint,
        bbar: new Ext.PagingToolbar({
            pageSize: 10,
            store: storeGridPembayaranPiutangDistPrint,
            displayInfo: true
        })
    });
    /**
     * 
     */
    var cmGridDetailPembayaranPiutangDistPrint = new Ext.ux.grid.LockingColumnModel({
        // specify any defaults for each column
        defaults: {
            sortable: true // columns are not sortable by default           
        }, columns: [{
                header: "No Faktur",
                dataIndex: 'nof',
                sortable: true,
                width: 150
            },
            {
                header: "Tanggal Faktur",
                dataIndex: 'tgl_faktur',
                sortable: true,
                width: 130
            }, {
                xtype: 'numbercolumn',
                header: 'Rp Faktur/Struk',
                dataIndex: 'rp_faktur',
                width: 120,
                sortable: true,
                format: '0,0',
                align: 'right'

            }, {
                xtype: 'numbercolumn',
                header: 'Rp Bayar',
                dataIndex: 'rp_bayar',
                width: 180,
                sortable: true,
                format: '0,0',
                align: 'right'
            }
        ]
    });


    /**
     * deklarasi grid cetak detail pembayaran piutang distribusi
     */

    var gridDetailPembayaranPiutangDistPrint = new Ext.grid.EditorGridPanel({
        id: 'id_grid_detail_pembayaran_piutang_dist_print',
        store: storeDetailPembayaranPiutangDistPrint,
        stripeRows: true,
        style: 'margin-bottom:5px;',
        height: 150,
        frame: true,
        border: true,
        loadMask: true,
        view: new Ext.ux.grid.LockingGridView(),
        sm: smGridDetailPembayaranPiutangDistPrint,
        cm: cmGridDetailPembayaranPiutangDistPrint
                // plugins: [action_approval_detail_approve_manager,action_approval_detail_reject_manager],

    });

    var headerPembayaranPiutangDistribusiPrint = {
        layout: 'column',
        border: false,
        buttonAlign: 'left',
        items: [{
                columnWidth: .5,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: {labelSeparator: ''},
                items: [{
                        xtype: 'datefield',
                        fieldLabel: 'Tgl Awal',
                        emptyText: 'Tanggal Awal',
                        name: 'cppd_tgl_pelunasan_awal',
                        id: 'id_cppd_tgl_pembayaran_awal',
                        maxLength: 255,
                        anchor: '90%',
                        value: '',
                        format: 'd-M-Y'
                    }, comboSalesOrder
                ]
            }, {
                columnWidth: .5,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: {labelSeparator: ''},
                items: [
                    {
                        xtype: 'datefield',
                        fieldLabel: 'Tgl Akhir',
                        emptyText: 'Tanggal Akhir',
                        name: 'cppd_tgl_pelunasan_akhir',
                        id: 'id_cppd_tgl_pembayaran_akhir',
                        maxLength: 255,
                        anchor: '90%',
                        value: '',
                        format: 'd-M-Y'
                    }//,comboSalesOrder
                ]
            }], buttons: [{
                text: 'Filter',
                formBind: true,
                handler: function() {
                    storeGridPembayaranPiutangDistPrint.load({
                        params: {
                            tgl_awal: Ext.getCmp('id_cppd_tgl_pembayaran_awal').getValue(),
                            tgl_akhir: Ext.getCmp('id_cppd_tgl_pembayaran_akhir').getValue(),
                            no_faktur: Ext.getCmp('id_combo_sales_order').getValue()
                        }
                    });
                }
            }]
    };

    /**
     * deklarasi window cetak form
     * @type Ext.FormPanel     */
    var winCetakPembayaranPiutangPrint = new Ext.Window({
        id: 'id_win_cetak_pembayaran_piutang_dist_print',
        title: 'Pembayaran Hutang Form',
        closeAction: 'hide',
        width: 900,
        height: 450,
        layout: 'fit',
        border: false,
        html: '<iframe style="width:100%;height:100%;" id="cetak_pembayaran_piutang_dist_print" src=""></iframe>'
    });


    var cetakPembayaranPiutangDistribusi = new Ext.FormPanel({
        id: 'id_cetak_pembayaran_piutang_distribusi',
        border: false,
        frame: true,
        //autoScroll: true,
        monitorValid: true,
        //bodyStyle: 'padding:0px;',
        items: [{
                bodyStyle: {
                    margin: '0px 0px 15px 0px'
                },
                items: [headerPembayaranPiutangDistribusiPrint]
            }, gridPembayaranPiutangDistPrint,
            gridDetailPembayaranPiutangDistPrint
        ],
        buttons: [
            {
                text: 'cetak',
                handler: function() {
                    var sm = gridPembayaranPiutangDistPrint.getSelectionModel();
                    var sel = sm.getSelections();
                    winCetakPembayaranPiutangPrint.show();
                    Ext.getDom('cetak_pembayaran_piutang_dist_print').src = '<?= site_url("cetak_pembayaran_piutang_distribusi_controller/finalPrint") ?>' + '/' + sel[0].get('no_pembayaran_piutang');
                }

            }, {
                text: 'reset',
                handler: function() {
                    clearPembayaranPiutangDistPrint();
                }
            }
        ]
    });


    /**
     * deklarasi store grid detail pembayaran piutang
     */
    function clearPembayaranPiutangDistPrint() {
        Ext.getCmp('id_cetak_pembayaran_piutang_distribusi').getForm().reset();
        storeGridPembayaranPiutangDistPrint.removeAll();
        storeDetailPembayaranPiutangDistPrint.removeAll();
    }
</script>
