<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">

    /**
     * deklarasi store grid kwitansi penjualan
     */
    var kwitansiPenjualanGridStore = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'no_kwitansi',
                'no_ref',
                'trx_type',
                'rp_total',
                'terbilang_total',
                'kd_pelanggan',
                'tanggal',
                'terima_dari',
                'keterangan',
                'created_by',
                'created_date',
                'updated_by',
                'updated_date'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("cetak_kwitansi_penjualan_controller/finalGetRows") ?>',
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

    kwitansiPenjualanGridStore.on('load', function() {
        kwitansiPenjualanGridStore.setBaseParam('kd_pelanggan', Ext.getCmp('id_combo_pelanggan_cetak_kwitansi').getValue());
    });


    //============================================start combo kode pelanggan=================================================================

    /**
     * deklarasi store kode pelanggan
     */
    var storeComboPelanggan_cetak_kwitansi = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_pelanggan', 'nama_pelanggan', 'npwp'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("create_kwitansi_penjualan_controller/finalGetDataPelanggan") ?>',
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
    var searchGridPelanggan_cetak_kwitansi = new Ext.app.SearchField({
        store: storeComboPelanggan_cetak_kwitansi,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_search_grid_pelanggan'
    });

    /**
     * deklarasi grid pelanggan
     */
    var gridPelanggan_cetak_kwitansi = new Ext.grid.GridPanel({
        store: storeComboPelanggan_cetak_kwitansi,
        stripeRows: true,
        frame: true,
        border: true,
        columns: [{
                header: 'Kode Pelanggan',
                dataIndex: 'kd_pelanggan',
                width: 120,
                sortable: true
            }, {
                header: 'Nama Pelanggan',
                dataIndex: 'nama_pelanggan',
                width: 120,
                sortable: true
            }, {
                header: 'NPWP',
                dataIndex: 'npwp',
                width: 150,
                sortable: true
            }],
        tbar: new Ext.Toolbar({
            items: [searchGridPelanggan_cetak_kwitansi]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: storeComboPelanggan_cetak_kwitansi,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                var kdPelanggan = sel[0].get('kd_pelanggan');
                if (sel.length > 0) {
                    kwitansiPenjualanGridStore.reload(
                            {params: {kd_pelanggan: kdPelanggan}, }
                    );
                    Ext.getCmp('id_combo_pelanggan_cetak_kwitansi').setValue(sel[0].get('kd_pelanggan'));
                    //Ext.getCmp('id_txt_kd_nama_pelanggan').setValue(sel[0].get('nama_pelanggan'));
                    menuPelanggan_cetak_kwitansi.hide();
                }
            }
        }
    });

    /**
     * deklarasi menu pelanggan
     */
    var menuPelanggan_cetak_kwitansi = new Ext.menu.Menu();
    menuPelanggan_cetak_kwitansi.add(new Ext.Panel({
        title: 'Pilih Pelanggan',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridPelanggan_cetak_kwitansi],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menuPelanggan_cetak_kwitansi.hide();
                }
            }]
    }));
    /**
     * deklarasi twin combo pelanggan
     * @returns {undefined} */
    Ext.ux.TwincomboKdPelanggan_cetak_kwitansi = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            //load store grid
            storeComboPelanggan_cetak_kwitansi.load();
            menuPelanggan_cetak_kwitansi.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
                trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
    menuPelanggan_cetak_kwitansi.on('hide', function() {
        var sf = Ext.getCmp('id_search_grid_pelanggan').getValue();
        if (sf !== '') {
            Ext.getCmp('id_search_grid_pelanggan').setValue('');
            searchGridPelanggan_cetak_kwitansi.onTrigger2Click();
        }
    });
    var comboPelanggan_cetak_kwitansi = new Ext.ux.TwincomboKdPelanggan_cetak_kwitansi({
        fieldLabel: 'Pelanggan <span class="asterix">*</span>',
        id: 'id_combo_pelanggan_cetak_kwitansi',
        store: storeComboPelanggan_cetak_kwitansi,
        mode: 'local',
        valueField: 'kd_pelanggan',
        displayField: 'kd_pelanggan',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        anchor: '95%',
        width: 200,
        hiddenName: 'kd_pelanggan',
        emptyText: 'Pilih Pelanggan'
    });
//==============================================end combo kode pelanggan==================================================================

    //kwitansiPenjualanGridStore.load();

    /**
     *deklarasi search field 
     */
    var searchCetakKwitansi = new Ext.app.SearchField({
        store: kwitansiPenjualanGridStore,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        id: 'id_search_field_pelanggan'
    });

    searchCetakKwitansi.onTrigger1Click = function(evt) {
        if (this.hasSearch) {
            this.el.dom.value = '';
            // Get the value of search field
            var fid = Ext.getCmp('id_search_field_pelanggan').getValue();
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
    searchCetakKwitansi.onTrigger2Click = function(evt) {
        var text = this.getRawValue();
        if (text.length < 1) {
            this.onTrigger1Click();
            return;
        }
        // Get the value of search field
        var fid = Ext.getCmp('id_search_field_pelanggan').getValue();
        var o = {start: 0, fieldId: fid};
        this.store.baseParams = this.store.baseParams || {};
        this.store.baseParams[this.paramName] = text;
        this.store.reload({params: o});
        this.hasSearch = true;
        this.triggers[0].show();
    };

    //toolbar grid 1
    var toolbarSearchKwitansi = new Ext.Toolbar({
        items: [searchCetakKwitansi]
    });

    /**
     * deklarasi grid cetak kwitansi selection model
     */

    var smGridCetakKwitansiPenjualan = new Ext.grid.CheckboxSelectionModel();
    /**
     * deklarasi grid cetak kwitansi penjualan
     */
    var gridCetakKwitansiPenjualan = new Ext.grid.EditorGridPanel({
        frame: true,
        border: true,
        stripeRows: true,
        sm: smGridCetakKwitansiPenjualan,
        store: kwitansiPenjualanGridStore,
        loadMask: true,
        title: 'Kwitansi Penjualan',
        style: 'margin:0 auto;', height: 400,
        columns: [
            {
                header: "No Kwitansi",
                dataIndex: 'no_kwitansi',
                sortable: true,
                width: 150
            }, {
                header: "No.Ref",
                dataIndex: 'no_ref',
                sortable: true,
                width: 250
            }, {
                header: "Kode Pelanggan",
                dataIndex: 'kd_pelanggan',
                sortable: true,
                width: 250
            }, {
                header: "Type",
                dataIndex: 'trx_type',
                sortable: true,
                width: 150
            }, {
                header: "Rp. Total",
                dataIndex: 'rp_total',
                sortable: true,
                width: 70
            }, {
                header: "Terbilang Total",
                dataIndex: 'terbilang_total',
                sortable: true,
                width: 250
            }, {
                header: "Tanggal",
                dataIndex: 'tanggal',
                sortable: true,
                width: 250
            }, {
                header: "Terima Dari",
                dataIndex: 'terima_dari',
                sortable: true,
                width: 250
            }, {
                header: "Keterangan",
                dataIndex: 'keterangan',
                sortable: true,
                width: 250
            }, {
                header: "Created By",
                dataIndex: 'created_by',
                sortable: true,
                width: 250
            }, {
                header: "Created Date",
                dataIndex: 'created_date',
                sortable: true,
                width: 250
            }, {
                header: "Updated By",
                dataIndex: 'updated_by',
                sortable: true,
                width: 250
            }, {
                header: "Updated Date",
                dataIndex: 'updated_date',
                sortable: true,
                width: 250
            }],
        listeners: {
            'rowdblclick': function() {
                var sm = gridCetakKwitansiPenjualan.getSelectionModel();
                var sel = sm.getSelections();
                windowCetakKwitansiPrint.show();
                Ext.getDom('id_cetak_kwitansi_penjualan_print').src = '<?= site_url("create_kwitansi_penjualan_controller/printForm") ?>' + '/' + sel[0].get('no_kwitansi');
            }
        },
        tbar: toolbarSearchKwitansi,
        bbar: new Ext.PagingToolbar({
            pageSize: 10,
            store: kwitansiPenjualanGridStore,
            displayInfo: true
        })
    });
    var headerCetakKwitansiPenjualan = {
        layout: 'column',
        border: false,
        buttonAlign: 'left',
        style: 'padding:0px;margin-bottom:5px',
        frame: false,
        items: [{
                columnWidth: .5,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: {labelSeparator: ''},
                items: [comboPelanggan_cetak_kwitansi
                ]
            }]
    };
    /**
     * deklarai window print
     */
    var windowCetakKwitansiPrint = new Ext.Window({
        id: 'id_window_cetak_kwitansi_print',
        title: 'Print Kwitansi Penjualan',
        closeAction: 'hide',
        width: 900,
        height: 450,
        layout: 'fit',
        border: false,
        //html: 'div id="lap_do" />'
        html: '<iframe style="width:100%;height:100%;" id="id_cetak_kwitansi_penjualan_print" src=""></iframe>'
    });
    /**
     * deklarasi form panel utama
     */
    var cetakKwitansiPenjualanForm = new Ext.FormPanel({
        id: 'id_cetak_kwitansi_penjualan',
        border: false,
        frame: true,
        bodyStyle: 'p adding-right:20px;',
        labelWidth: 130,
        items: [headerCetakKwitansiPenjualan, gridCetakKwitansiPenjualan
        ],
        buttons: [
            {
                text: 'cetak',
                handler: function() {
                    var sm = gridCetakKwitansiPenjualan.getSelectionModel();
                    var sel = sm.getSelections();
                    windowCetakKwitansiPrint.show();
                    Ext.getDom('id_cetak_kwitansi_penjualan_print').src = '<?= site_url("create_kwitansi_penjualan_controller/printForm") ?>' + '/' + sel[0].get('no_kwitansi');
                }
            }, {
                text: 'reset',
                handler: function() {
                    clearFormCetakKwitansiPenjualan();
                }
            }
        ]
    });


    function clearFormCetakKwitansiPenjualan() {
        Ext.getCmp('id_cetak_kwitansi_penjualan').getForm().reset();
        kwitansiPenjualanGridStore.removeAll();
        storeComboPelanggan_cetak_kwitansi.removeAll();
    }
//    function print() {
//        var sm = gridcrjprint.getSelectionModel();
//        var sel = sm.getSelections();
//        wincetakreturjualprint.show();
//        Ext.getDom('printcetakreturjual').src = '<?= site_url("cetak_retur_penjualan/print_form") ?>' + '/' + sel[0].get('no_retur');
//    }
</script>
