<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">    
    
    //    form add user
    Ext.ns('userForm');
    var dataagama= [
     ['Islam', 'Islam']
    ,['Kristen', 'Kristen']
    ,['Katholik', 'Katholik']
    ,['Hindu', 'Hindu']
    ,['Budha', 'Budha']    
    ];
    
    var stragama=new Ext.data.ArrayStore({
					        fields: [
                                                    {name: 'mid'},
                                                    {name: 'mtext'},
                                                ]});

    var strcmbuserjabatan = new Ext.data.Store({
    reader: new Ext.data.JsonReader({
        fields: [
            'kd_jabatan',
            'kd_parent_jabatan',
            'nama_jabatan',
            'lvl_jabatan',
            'kd_divisi',
            'aktif'
        ],
        root: 'data',
        totalProperty: 'record'
    }),
    proxy: new Ext.data.HttpProxy({
    url: '<?= site_url("jabatan/get_rows") ?>',
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

var strcbucabang = new Ext.data.Store({
    reader: new Ext.data.JsonReader({
        fields: [
            'kd_cabang',
            'nama_cabang',
            
        ],
        root: 'data',
        totalProperty: 'record'
    }),
    proxy: new Ext.data.HttpProxy({
    url: '<?= site_url("jabatan/get_cabang") ?>',
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

var strcmbugroup = new Ext.data.Store({
    reader: new Ext.data.JsonReader({
        fields: [
            'kd_group',
            'nama_group',
        ],
        root: 'data',
        totalProperty: 'record'
    }),
    proxy: new Ext.data.HttpProxy({
    url: '<?= site_url("user/get_group") ?>',
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

var strcbuperuntukan = [
    [0, 'Supermarket'],
    [1, 'Distribusi'],
    [2, 'All']
];

var strcmbuserkategori1 = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
				'kd_kategori1',
				'nama_kategori1'
			],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("kategori2/get_kategori1") ?>',
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
 var strcmbuserkategori2 = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
           fields: ['kd_kategori2', 'nama_kategori2'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("kategori3/get_kategori2") ?>',
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
    
    var strcmbuserkategori3 = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_kategori3', 'nama_kategori3'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("kategori4/get_kategori3") ?>',
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
    var strcmbuserkategori4 = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                
                'kd_kategori4',                
                'nama_kategori4'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("kategori4/get_kategori4") ?>',
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
    var cbuseragama = new Ext.form.ComboBox({
        fieldLabel: 'Agama <span class="asterix">*</span>',
        name: 'agama',						               
        id: 'id_in_agama',	
       
        store: dataagama,
            
        mode:'local',
//        valueField: 'midagama',
//        displayField: 'mtextagama',
        typeAhead: true,
        triggerAction: 'all',
        forceSelection: true,
        selectOnFocus:true,
        allowBlank: false,
        editable: false,
        anchor: '90%',
        
//        hiddenName: 'midagama',
        emptyText: 'Pilih Agama'
//        ,
//        listeners:{
//            render: function(){
//				      this.store.loadData(dataagama);
//				        	
//                                }
//        }
		
    });
    
    var cbuserjabatan = new Ext.form.ComboBox({
        fieldLabel: 'Jabatan <span class="asterix">*</span>',
                                name: 'kd_jabatan',						               
                                id: 'id_in_kd_jabatan',	
       
        store: strcmbuserjabatan,
        valueField: 'kd_jabatan',
        displayField: 'nama_jabatan',
        typeAhead: true,
        triggerAction: 'all',
        forceSelection: true,
        selectOnFocus:true,
        allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_jabatan',
        emptyText: 'Pilih Jabatan',
		hideMode: 'Visibility'
    });

    var cbugroup = new Ext.form.ComboBox({
        fieldLabel: 'Group Access <span class="asterix">*</span>',
        name: 'kd_group',
        id: 'id_in_kd_group',

        store: strcmbugroup,
        valueField: 'kd_group',
        displayField: 'nama_group',
        typeAhead: true,
        triggerAction: 'all',
        forceSelection: true,
        selectOnFocus:true,
        allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_group',
        emptyText: 'Pilih Group',
        hideMode: 'Visibility'
    });

    var cbuperuntukan = new Ext.form.ComboBox({
        fieldLabel: 'Peruntukan <span class="asterix">*</span>',
        name: 'kd_peruntukan',
        id: 'id_in_kd_peruntukan',

        store: strcbuperuntukan,
        mode:'local',
        typeAhead: true,
        triggerAction: 'all',
        forceSelection: true,
        selectOnFocus:true,
        allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_peruntukan',
        emptyText: 'Tentukan Peruntukan',
        hideMode: 'Visibility'
    });
    
    var cbusercabang = new Ext.form.ComboBox({
        fieldLabel: 'Cabang <span class="asterix">*</span>',
        name: 'kd_cabang',						               
        id: 'id_in_kd_cabang',	
       
        store: strcbucabang,
        valueField: 'kd_cabang',
        displayField: 'nama_cabang',
        typeAhead: true,
        triggerAction: 'all',
        forceSelection: true,
        selectOnFocus:true,
        allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_cabang',
        emptyText: 'Pilih cabang',
		hideMode: 'Visibility'
    });
    

    var cbuserkategori1 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 1 ',        
        name: 'kd_kategori1',						               
        id: 'id_in_kd_kategori1',
        store: strcmbuserkategori1,
        valueField: 'kd_kategori1',
        displayField: 'nama_kategori1',
        typeAhead: true,
        triggerAction: 'all',
        forceSelection: true,
        selectOnFocus:true,
//        mode: 'local',
        allowBlank: true,
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_kategori1',
        emptyText: 'Pilih kategori 1',
        listeners: {
            select: function(combo, records) {
                var vkdcbkategori1 = this.getValue();
                cbuserkategori2.setValue();
                cbuserkategori2.store.proxy.conn.url = '<?= site_url("kategori3/get_kategori2") ?>/' + vkdcbkategori1;
                cbuserkategori2.store.reload();
            }
        }
    });
    var cbuserkategori2 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 2 ',        
        name: 'kd_kategori2',						               
        id: 'id_in_kd_kategori2',
        store: strcmbuserkategori2,
        valueField: 'kd_kategori2',
        displayField: 'nama_kategori2',
        typeAhead: true,
        mode: 'local',        
        triggerAction: 'all',
        forceSelection: true,
        selectOnFocus:true,
        allowBlank: true,
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_kategori2',
        emptyText: 'Pilih kategori 2',
        listeners: {
            select: function(combo, records) {
                var vkdcbkategori1 = cbuserkategori1.getValue();
                var vkdcbkategori2 = this.getValue();
                cbuserkategori3.setValue();
                cbuserkategori3.store.proxy.conn.url = '<?= site_url("kategori4/get_kategori3") ?>/' + vkdcbkategori1+'/' + vkdcbkategori2;
                cbuserkategori3.store.reload();
            }
        }
    });
    var cbuserkategori3 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 3 ',        
        name: 'kd_kategori3',						               
        id: 'id_in_kd_kategori3',
        mode: 'local',
        store: strcmbuserkategori3,
        valueField: 'kd_kategori3',
        displayField: 'nama_kategori3',
        typeAhead: true,
        triggerAction: 'all',
        forceSelection: true,
        selectOnFocus:true,
//        allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_kategori3',
        emptyText: 'Pilih kategori 3',
        listeners: {
            select: function(combo, records) {
                var vkdcbkategori1 = cbuserkategori1.getValue();
                var vkdcbkategori2 = cbuserkategori2.getValue();
                var vkdcbkategori3 = this.getValue();
                cbuserkategori4.setValue();
                cbuserkategori4.store.proxy.conn.url = '<?= site_url("kategori4/get_kategori4") ?>/' + vkdcbkategori1+'/' + vkdcbkategori2+'/'+vkdcbkategori3;
                cbuserkategori4.store.reload();
            }
        }
    });
    var cbuserkategori4 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 4 ',        
        name: 'kd_kategori4',						               
        id: 'id_in_kd_kategori4',
        mode: 'local',
        store: strcmbuserkategori4,
        valueField: 'kd_kategori4',
        displayField: 'nama_kategori4',
        typeAhead: true,
        triggerAction: 'all',
        forceSelection: true,
        selectOnFocus:true,
//        allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_kategori4',
        emptyText: 'Pilih kategori 4'
    });
    var fsUserLogin = {
        layout: 'column',
        border: false,
        items: [{
                columnWidth: .7,
                style:'margin:6px 3px 0 0;',	
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: { labelSeparator: ''},
                items: [
                    {xtype: 'fieldset',
                        autoWidth: true,
                        title: 'USER LOGIN',
                        collapsible: false,								
                        items: [
                            {
                                xtype: 'textfield',
                                fieldLabel: 'Username <span class="asterix">*</span>',
                                name: 'username',	
                                allowBlank: false,
                                id: 'id_in_username',						                
                                anchor: '90%'}
                            ,{
                                xtype: 'textfield',
                                fieldLabel: 'Password <span class="asterix">*</span>',
                                inputType: 'password',
                                allowBlank: false,
                                name: 'passwd',						               
                                id: 'id_in_passwd',						                
                                anchor: '90%'} ,{
                                xtype: 'textfield',
                                fieldLabel: 'Password 2',
                                inputType: 'password',
                                allowBlank: true,
                                name: 'passwd2',						               
                                id: 'id_in_passwd2',						                
                                anchor: '90%'}
                            ,{
                                xtype: 'textfield',
                                fieldLabel: 'Email ',
                                vtype:'email',
                                allowBlank: true,
                                name: 'email',						               
                                id: 'id_in_email',						                
                                anchor: '90%'}
                            ,cbuserjabatan
                            ,cbuserkategori1
                            ,cbuserkategori2
                            ,cbuserkategori3
                            ,cbuserkategori4
                            ,cbugroup
                            ,cbuperuntukan
                            ,cbusercabang
                           ,{
                                xtype: 'radiogroup',
                                fieldLabel: 'Is Bazar',
                                name: 'is_bazar',						               
                                id: 'id_in_is_bazar',						                
                                anchor: '90%',
                                items:[{boxLabel: 'Tidak', name: 'rb-bazar', inputValue: "0", checked: true},
                                       {boxLabel: 'YA', name: 'rb-bazar', inputValue: "1"}
                                   ]
                            }
                        ]}
                    
                ]
            }, {
                columnWidth: .3,
                layout: 'form',
                border: false,
                style:'margin:6px 3px 0 0;',	
                //                align:'right',
                labelWidth: 50,
                defaults: { labelSeparator: ''},
                //                extraCls : 'text-align:right;border:1px solid;',
                items: [ {
                        xtype: 'fieldset',
                        autoHeight: true,
                        title: 'USER FOTO',
                        collapsible: false,	
                        items:[
                            {
                                xtype: 'textfield',				
                                name: 'foto',					
                                id: 'id_user_foto',
                                fieldLabel: 'Foto',
                                anchor: '90%'
                                //                        labelStyle:'font-size:35px;text-align:left;padding-left:50px;',
                                //                        style: 'font-size:35px;text-align:right;padding-right:10px;margin-top:10px;'
                            }
                        ]
                        
                    }
                    
                        
                ]
            }]
    }
    var fsUserInfo = {
        xtype: 'fieldset',
        autoWidth: true,
        title: 'USER INFO',
        collapsible: true,								
        items: [
            {
                layout: 'column',
                border: false,
                items: [{
                        columnWidth: .5,
                        //                style:'margin:6px 3px 0 0;',	
                        layout: 'form',
                        border: false,
                        labelWidth: 100,
                        defaults: { labelSeparator: ''},
                        items: [                   
                            {
                                xtype: 'textfield',
                                fieldLabel: 'Nama Lengkap <span class="asterix">*</span>',
                                name: 'nama_lengkap',						               
                                id: 'id_in_nama_lengkap',
                                allowBlank: false,
                                anchor: '90%'}
                            ,{
                                xtype: 'textfield',
                                fieldLabel: 'Gelar Akademis <span class="asterix">*</span>',
                                name: 'gelar_akademis',						               
                                id: 'id_in_gelar_akademis',						                
                                anchor: '90%'}
                            ,{
                                xtype: 'radiogroup',
                                fieldLabel: 'Jenis Kelamin',
                                name: 'jns_kelamin',						               
                                id: 'id_in_jns_kelamin',						                
                                anchor: '90%',
                                items:[{boxLabel: 'Laki-laki', name: 'rb-kelamin', inputValue: "L", checked: true},
                                       {boxLabel: 'Perempuan', name: 'rb-kelamin', inputValue: "P"}
                                   ]
                            }
                            ,{
                                xtype: 'textfield',
                                fieldLabel: 'Tempat Lahir <span class="asterix">*</span>',
                                name: 'tmp_lahir',						               
                                id: 'id_in_tmp_lahir',						                
                                anchor: '90%'}
                            ,{
                                xtype: 'datefield',
                                fieldLabel: 'Tanggal Lahir <span class="asterix">*</span>',
                                name: 'tgl_lahir',						               
                                id: 'id_in_tgl_lahir',						                
                                anchor: '90%'}
                            ,cbuseragama
                            ,{
                                xtype: 'textfield',
                                fieldLabel: 'Nomor KTP ',
                                name: 'no_ktp',						               
                                id: 'id_in_no_ktp',	
//                                allowBlank: false,
                                anchor: '90%'}
                            ,{
                                xtype: 'textfield',
                                fieldLabel: 'Nomor NPWP ',
                                name: 'no_npwp',						               
                                id: 'id_in_no_npwp',						                
                                anchor: '90%'}
                            
                        
                    
                        ]
                    },
                    {
                        columnWidth: .5,
                        //                style:'margin:6px 3px 0 0;',	
                        layout: 'form',
                        border: false,
                        labelWidth: 100,
                        defaults: { labelSeparator: ''},
                        items: [                   
                            {
                                xtype: 'textarea',
                                fieldLabel: 'Alamat',
                                name: 'alamat',						               
                                id: 'id_in_alamat',
                                flex: 1,
                                anchor: '90%'}
                            ,{
                                xtype: 'textfield',
                                fieldLabel: 'Nomor Telepon',
                                name: 'no_telp',						               
                                id: 'id_in_no_telp',						                
                                anchor: '90%'}
                            ,{
                                xtype: 'textfield',
                                fieldLabel: 'Nomor HP',
                                name: 'no_hp',						               
                                id: 'id_in_no_hp',						                
                                anchor: '90%'}                        
                    
                        ]
                    }
                ]
            }
        ]
        
        
    }
    
    var pjab=null;
    var pk1=null;
    var pk2=null;
    var pk3=null;
    var pk4=null;
    var pexec=null;
    var pcab=null;
    
    userForm.Form = Ext.extend(Ext.form.FormPanel, {
    
        // defaults - can be changed from outside
        border: false,
        closeable: true,
        frame: true,
//        autoScroll:true,	
        labelWidth: 100,
		waitMsg:'Loading...',
        url: '<?= site_url("user/update_row") ?>',
        constructor: function(config){
            config = config || {};
            config.listeners = config.listeners || {};
            Ext.applyIf(config.listeners, {
                actioncomplete: function(){
                    //if (console && console.log) {
                    //    console.log('actioncomplete:', arguments);
                    //}
                },
                actionfailed: function(){
                    //if (console && console.log) {
                    //    console.log('actionfailed:', arguments);
                    //}
                }
            });
            userForm.Form.superclass.constructor.call(this, config);
        },
        initComponent: function(){
        
            // hard coded - cannot be changed from outsid
            var config = {
//                defaultType: 'textfield',
                defaults: { labelSeparator: ''},
                monitorValid: true,
                autoScroll: true // ,buttonAlign:'right'
                ,
                items: [fsUserLogin,fsUserInfo],
                buttons: [{
                    text: 'Submit',
                    id: 'btnsubmituser',
                    formBind: true,
                    scope: this,
                    handler: this.submit
                }, {
                    text: 'Reset',
                    id: 'btnresetuser',
                    scope: this,
                    handler: this.reset
                }, {
                    text: 'Close',
                    id: 'btnCloseuser',
                    scope: this,
                    handler: function(){
                        winadduser.hide();
                    }
                }]
            }; // eo config object
            // apply config
            Ext.apply(this, Ext.apply(this.initialConfig, config));
            
            // call parent
            userForm.Form.superclass.initComponent.apply(this, arguments);
            
        } // eo function initComponent	
        ,
        onRender: function(){
        
            // call parent
            userForm.Form.superclass.onRender.apply(this, arguments);
            
            // set wait message target
            this.getForm().waitMsgTarget = this.getEl();
            
            // loads form after initial layout
            // this.on('afterlayout', this.onLoadClick, this, {single:true});
        
        } // eo function onRender
        ,
        reset: function(){
            this.getForm().reset();
        },
        submit: function(){
            pjab=this.getForm().findField('kd_jabatan').getValue();
            pk1=this.getForm().findField('kd_kategori1').getValue();
            pk2=this.getForm().findField('kd_kategori2').getValue();
            pk3=this.getForm().findField('kd_kategori3').getValue();
            pk4=this.getForm().findField('kd_kategori4').getValue();
            pcab=this.getForm().findField('kd_cabang').getValue();
            
            this.getForm().submit({
                url: this.url,
                scope: this,
                success: this.onSuccess,
                failure: this.onFailure,
                params: {
                    cmd: 'save',
                    pexec:pexec,
                    pkd_jabatan:pjab,
                    pkd_kategori1:pk1,
                    pkd_kategori2:pk2,
                    pkd_kategori3:pk3,
                    pkd_kategori4:pk4,
                    pkd_cabang:pcab,

                },
                waitMsg: 'Saving Data...'
            });
        } // eo function submit
        ,
        onSuccess: function(form, action){
            var r = Ext.util.JSON.decode(action.response.responseText);
            Ext.Msg.show({
                title: 'Success',
                msg: r.errMsg,
                //msg: 'Form submitted successfully',
                modal: true,
                icon: Ext.Msg.INFO,
                buttons: Ext.Msg.OK
            });
            
            
            struser.reload();
            Ext.getCmp('id_formadduser').getForm().reset();
            winadduser.hide();
        } // eo function onSuccess
        ,
        onFailure: function(form, action){
        
            var fe = Ext.util.JSON.decode(action.response.responseText);
            this.showError(fe.errMsg || '');
            
            
        } // eo function onFailure
        ,
        showError: function(msg, title){
            title = title || 'Error';
            Ext.Msg.show({
                title: title,
                msg: msg,
                modal: true,
                icon: Ext.Msg.ERROR,
                buttons: Ext.Msg.OK,
                fn: function(btn){
                    if (btn == 'ok' && msg == 'Session Expired') {
                        window.location = '<?= site_url("auth/login") ?>';
                    }
                }
            });
        }
    }); // eo extend
    // register xtype
    Ext.reg('formadduser', userForm.Form);
    

    var winadduser = new Ext.Window({
        id: 'id_winadduser',
        closeAction: 'hide',
        width: 800,
        height: 600,
        layout: 'fit',
        border: false,
        items: {
            id: 'id_formadduser',
            xtype: 'formadduser'
        },
        onHide: function(){
            Ext.getCmp('id_formadduser').getForm().reset();
        }
    });
    var struser = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'username',
                'passwd',
                'passwd2',
                'email',
                'kd_jabatan',
                'kd_kategori1',
                'kd_kategori2',
                'kd_kategori3',
                'kd_kategori4',
                'kd_group',
                'kd_peruntukan',
                'kd_cabang',
                'is_bazar',
                'kd_peruntukan_alias',
                'aktif',
                'aktif_alias'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("user/get_rows") ?>',
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
	
    // search field
    var searchuser = new Ext.app.SearchField({
        store: struser,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        id: 'idsearchuser'
    });
    
    // top toolbar
    var tbuser = new Ext.Toolbar({
        items: [{
                text: 'Add',
                icon: BASE_ICONS + 'add.png',
                onClick: function(){				
                    Ext.getCmp('btnresetuser').show();
                    Ext.getCmp('btnsubmituser').setText('Submit');
                    pexec='insert';
                    winadduser.setTitle('Add Form');
                    winadduser.show();                
                }            
            }, '-', searchuser]
    });
	
    // checkbox grid
    var cbuserGrid = new Ext.grid.CheckboxSelectionModel();
    
    // row actions
    var actionuser = new Ext.ux.grid.RowActions({
        header: 'Edit',
        autoWidth: false,
        width: 30,
        actions:[{iconCls: 'icon-edit-record', qtip: 'Edit'}],
        widthIntercept: Ext.isSafari ? 4 : 2
    });	
    var actionuserdel = new Ext.ux.grid.RowActions({
        header: 'Delete',
        autoWidth: false,
        width: 40,
        actions:[{iconCls: 'icon-delete-record', qtip: 'Delete'}],
        widthIntercept: Ext.isSafari ? 4 : 2
    });	
    var actionuserinfo = new Ext.ux.grid.RowActions({
        header: 'User Info',
        autoWidth: false,
        width: 50,
        actions:[{iconCls: 'icon-main-menu', qtip: 'Info'}],
        widthIntercept: Ext.isSafari ? 4 : 2
    });	
	
    actionuser.on('action', function(grid, record, action, row, col) {
        var kd_user = record.get('username');
        var kd_kat1 = record.get('kd_kategori1');
        var kd_kat2 = record.get('kd_kategori2');
        var kd_kat3 = record.get('kd_kategori3');
        var kd_kat4 = record.get('kd_kategori4');
        switch(action) {
            case 'icon-edit-record':	        	
                edituser(kd_user,kd_kat1,kd_kat2,kd_kat3,kd_kat4);
                break;
            case 'icon-delete-record':
                deleteuser1(kd_user);
                break;	      
	      	
        }
    });  
    
    // grid
    var user = new Ext.grid.EditorGridPanel({
        //id: 'user-id-grid',
        id: 'user',
		frame: true,
        border: true,
        stripeRows: true,
        sm: cbuserGrid,
        store: struser,
        closable:true,
        loadMask: true,
        style: 'margin:0 auto;',
		height: 450,    
        columns: [actionuser,actionuserdel,{
                header: "Username",
                dataIndex: 'username',
                sortable: true,
                width: 150
            },{
                header: "Password",
                dataIndex: 'passwd',
                sortable: true,
                width: 150,
				hidden:true
            },{
                header: "Password2",
                dataIndex: 'passwd2',
                sortable: true,
                width: 150,
				hidden:true
            },{
                header: "Email",
                dataIndex: 'email',
                sortable: true,
                width: 150
            },{
                header: "Jabatan",
                dataIndex: 'kd_jabatan',
                sortable: true,
                width: 100
            },{
                header: "Kategori 1",
                dataIndex: 'kd_kategori1',
                sortable: true,
                width: 150
            },{
                header: "Kategori 2",
                dataIndex: 'kd_kategori2',
                sortable: true,
                width: 150
            },{
                header: "Kategori 3",
                dataIndex: 'kd_kategori3',
                sortable: true,
                width: 150
            },{
                header: "Kategori 4",
                dataIndex: 'kd_kategori4',
                sortable: true,
                width: 150
            },{
                header: "Kode Group",
                dataIndex: 'kd_group',
                sortable: true,
                width: 120
            },{
                header: "Peruntukan",
                dataIndex: 'kd_peruntukan_alias',
                sortable: true,
                width: 120
            },{
                header: "Status",
                dataIndex: 'aktif_alias',
                sortable: true,
                width: 120
            }],
        plugins: [actionuser,actionuserdel],
        listeners: {
            'rowdblclick': function(){				
                var sm = user.getSelectionModel();                
                var sel = sm.getSelections();                
                if (sel.length > 0) {
                    edituser(sel[0].get('username'));                    
                }                 
            }          
        },
        tbar: tbuser,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: struser,
            displayInfo: true
        })
    });
	/**
	var userpanel = new Ext.FormPanel({
	 	id: 'user',
		border: false,
        frame: false,
		autoScroll:true,	
        items: [user]
	});
   **/
    function edituser(kd_user,kd_kat1,kd_kat2,kd_kat3,kd_kat4){
        pexec='update';
        strcmbuserjabatan.load();
        strcbucabang.load();
        strcmbugroup.load();
		
        strcmbuserkategori1.load();
        
        strcmbuserkategori2.proxy.conn.url = '<?= site_url("kategori3/get_kategori2") ?>/' + kd_kat1;
        strcmbuserkategori2.load();
        
        strcmbuserkategori3.proxy.conn.url = '<?= site_url("kategori4/get_kategori3") ?>/' + kd_kat1+'/' + kd_kat2;
        strcmbuserkategori3.load();
        
        strcmbuserkategori4.proxy.conn.url = '<?= site_url("kategori4/get_kategori4") ?>/' + kd_kat1+'/' + kd_kat2+'/' + kd_kat3;
        strcmbuserkategori4.load();
        
        Ext.getCmp('btnresetuser').hide();
        Ext.getCmp('btnsubmituser').setText('Update');
        winadduser.setTitle('Edit Form');
        Ext.getCmp('id_formadduser').getForm().load({
            url: '<?= site_url("user/get_row") ?>',
            params: {
                id: kd_user,
                cmd: 'get'
            },                  
            failure: function(form, action){
                var de = Ext.util.JSON.decode(action.response.responseText);
                Ext.Msg.show({
                    title: 'Error',
                    msg: de.errMsg,
                    modal: true,
                    icon: Ext.Msg.ERROR,
                    buttons: Ext.Msg.OK,
                    fn: function(btn){
                        if (btn == 'ok' && de.errMsg == 'Session Expired') {
                            window.location = '<?= site_url("auth/login") ?>';
                        }
                    }
                });
            }
        });
        winadduser.show();
    }
    function deleteuser1(kd_user){
    Ext.Msg.show({
	                title: 'Confirm',
	                msg: 'Are you sure delete selected row ?',
	                buttons: Ext.Msg.YESNO,
	                fn: function(btn){
	                    if (btn == 'yes') {
	                        Ext.Ajax.request({
	                            url: '<?= site_url("user/delete_row") ?>',
	                            method: 'POST',
	                            params: {
	                                username: kd_user
	                            },
								callback:function(opt,success,responseObj){
									var de = Ext.util.JSON.decode(responseObj.responseText);
									if(de.success==true){
										struser.reload();
		                                struser.load({
		                                    params: {
		                                        start: STARTPAGE,
		                                        limit: ENDPAGE
		                                    }
	                                	});
									}else{
										Ext.Msg.show({
	                                        title: 'Error',
	                                        msg: de.errMsg,
	                                        modal: true,
	                                        icon: Ext.Msg.ERROR,
	                                        buttons: Ext.Msg.OK,
	                                        fn: function(btn){
	                                            if (btn == 'ok' && de.errMsg == 'Session Expired') {
	                                                window.location = '<?= site_url("auth/login") ?>';
	                                            }
	                                        }
	                                    });
									}
								}
	                        });                 
	                    } 
	                }
	            });
    }
    function deleteuser(){		
        var sm = user.getSelectionModel();
        var sel = sm.getSelections();
        if (sel.length > 0) {
            Ext.Msg.show({
                title: 'Confirm',
                msg: 'Are you sure delete selected row ?',
                buttons: Ext.Msg.YESNO,
                fn: function(btn){
                    if (btn == 'yes') {
                    
                        var data = '';
                        for (i = 0; i < sel.length; i++) {
                            data = data + sel[i].get('username') + ';';
                        }
                        
                        Ext.Ajax.request({
                            url: '<?= site_url("user/delete_rows") ?>',
                            method: 'POST',
                            params: {
                                postdata: data
                            },
                            callback:function(opt,success,responseObj){
                                var de = Ext.util.JSON.decode(responseObj.responseText);
                                if(de.success==true){
                                    struser.reload();
                                    struser.load({
                                        params: {
                                            start: STARTPAGE,
                                            limit: ENDPAGE
                                        }
                                    });
                                }else{
                                    Ext.Msg.show({
                                        title: 'Error',
                                        msg: de.errMsg,
                                        modal: true,
                                        icon: Ext.Msg.ERROR,
                                        buttons: Ext.Msg.OK,
                                        fn: function(btn){
                                            if (btn == 'ok' && de.errMsg == 'Session Expired') {
                                                window.location = '<?= site_url("auth/login") ?>';
                                            }
                                        }
                                    });
                                }
                            }
                        });                 
                    } 
                }
            });
        }
        else {
            Ext.Msg.show({
                title: 'Info',
                msg: 'Please selected row',
                modal: true,
                icon: Ext.Msg.ERROR,
                buttons: Ext.Msg.OK
            });
        }
        
    }
    
    
</script>