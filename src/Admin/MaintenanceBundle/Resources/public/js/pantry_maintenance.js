Ext.namespace('pantry_maintenance_js');
Ext.define('Category', {
    extend : 'Ext.data.Model',
    fields : [{
        name : 'category'
    }, {
        name : 'id'
    }]
});
Ext.define('Question', {
    extend : 'Ext.data.Model',
    fields : [{
        name : 'description'
    }, {
        name : 'id'
    },{
        name: 'type'
    }]
});
Ext.define('Survey', {
    extend : 'Ext.data.Model',
    fields : [{
        name : 'description'
    }]
});
Ext.define('Option', {
    extend : 'Ext.data.Model',
    fields : [{
        name : 'description',
    }, {
        name : 'id'
    }, {
        name : 'order'
    }]
});

/************************
 * Extjs Models
 ************************/

pantry_maintenance_js.app = function() {
    return {
        init : function() {
            pantry_maintenance_js.app.initPantryMaintenance();
        },

        /************************
         * popup functions - START
         ************************/

        openQuestionForm : function() {
            pantry_maintenance_js.app.questionFormPanel = new Ext.FormPanel({
                width : '100%',
                height : '100%',
                autoHeight : true,
                defaults : {
                    anchor : '95%',
                    allowBlank : false,
                    labelWidth : 150,
                    msgTarget : 'side',
                    xtype : 'fieldset'
                },
                items : [{
                    id : 'question_form',
                    title : 'Question Details',
                    style : 'margin: 15px 15px 15px 15px; background: none repeat scroll 0 0 #EEEEEE;',
                    defaults : {
                        anchor : '95%',
                        xtype : 'textfield',
                        msgTarget : 'side'
                    },
                    items : [{
                        allowBlank : false,
                        xtype : 'textfield',
                        id : 'question_name',
                        name : 'question_name',
                        emptyText : 'Question',
                    }, {
                        xtype : 'hidden',
                        value : '',
                        id : 'question_id',
                        name : 'question_id'
                    }, {
                        xtype : 'radiofield',
                        boxLabel : 'Rating',
                        inputValue : 'rating',
                        name : 'type',
                        id : 'rating_id',
                        checked : true
                    }, {
                        xtype : 'radiofield',
                        boxLabel : 'Comment',
                        inputValue : 'comment',
                        name : 'type',
                        id : 'comment_id',
                        handler: function(){
                            var type = this.getValue(),
                                textfield = Ext.getCmp('textfield_id').getValue();

                            if(type || textfield)
                                Ext.getCmp('optional').enable();
                            else
                                Ext.getCmp('optional').disable();
                        }
                    },{
                        xtype : 'radiofield',
                        boxLabel : 'Text Field',
                        inputValue : 'textfield',
                        name : 'type',
                        id : 'textfield_id',
                        handler: function(){
                            var type = this.getValue(),
                                comment = Ext.getCmp('comment_id').getValue();

                            if(type || comment)
                                Ext.getCmp('optional').enable();
                            else
                                Ext.getCmp('optional').disable();
                        }
                    },{
                        xtype : 'radiofield',
                        boxLabel : 'Combobox',
                        inputValue : 'combobox',
                        name : 'type',
                        id : 'combobox_id',
                        handler: function(){
                            var combo = this.getValue();
                            if(combo)
                                Ext.getCmp('grouping_container').enable();
                            else
                                Ext.getCmp('grouping_container').disable();
                        }
                    },{
                        xtype : 'checkbox',
                        boxLabel : 'Optional',
                        inputValue : '1',
                        disabled: true,
                        name : 'optional',
                        id : 'optional',
                    },{
                        xtype: 'fieldcontainer',
                        id: 'grouping_container',
                        fieldLabel: 'Grouping',
                        disabled: true,
                        labelStyle: 'font-weight:bold;padding:0',
                        layout: 'hbox',
                        defaultType: 'radio',
                        fieldDefaults: {
                            labelAlign: 'top'
                        },
                        items: [{
                            name: 'grouping',
                            labelAlign: 'right',
                            fieldLabel: 'None',
                            checked : true,
                            inputValue: 'none',
                            id: 'grouping_none'
                        },
                        {
                            name: 'grouping',
                            labelAlign: 'right',
                            fieldLabel: 'Grouped',
                            inputValue: 'grouped',
                            id: 'grouping_grouped'
                        },
                        {
                            name: 'grouping',
                            fieldLabel: 'Separated',
                            labelAlign: 'right',
                            inputValue: 'separated',
                            id: 'grouping_separated'
                        }]
                    }]
                }],
                buttons : [{
                    text : 'Save',
                    handler : function() {
                        var q_id;

                        if (pantry_maintenance_js.app.questionFormPanel.isValid()) {
                            Ext.MessageBox.show({
                                title : 'Saving Question',
                                progressText : 'Please wait..',
                                width : 300,
                                wait : true,
                                waitConfig : {
                                    interval : 200
                                },
                                animateTarget : 'waitButton'
                            });

                            if (pantry_maintenance_js.app.question_grid.store.data.length === 0)
                                q_id = 'false';

                            if (!pantry_maintenance_js.app.question_grid.getSelectionModel().hasSelection())
                                q_id = 'false';
                            else if (pantry_maintenance_js.app.add === 'add')
                                q_id = 'false';
                            else
                                q_id = pantry_maintenance_js.app.question_grid.getSelectionModel().getSelection()[0].data.id;

                            pantry_maintenance_js.app.questionFormPanel.submit({
                                url : 'save-question',
                                params : {
                                    cat_id : pantry_maintenance_js.app.category_grid.getSelectionModel().getSelection()[0].data.id,
                                    q_id : q_id
                                },
                                success : function(res, opt) {
                                    Ext.MessageBox.hide();
                                    if (opt.result.status === 'success') {
                                        pantry_maintenance_js.app.question_store.reload();
                                        pantry_maintenance_js.app.questionFormWindow.destroy();
                                        Ext.MessageBox.show({
                                            title : 'Question',
                                            msg : opt.result.msg,
                                            buttons : Ext.MessageBox.OK,
                                            icon : Ext.MessageBox.INFO
                                        });

                                        pantry_maintenance_js.app.add = "";
                                        return;
                                    } else {
                                        Ext.MessageBox.show({
                                            title : 'Question',
                                            msg : opt.result.msg,
                                            buttons : Ext.MessageBox.OK,
                                            icon : Ext.MessageBox.INFO
                                        });
                                        return;
                                    }
                                }
                            });
                        }
                        else
                        {
                            Ext.MessageBox.show({
                                title : 'Error Message',
                                msg : 'The Question field is required.',
                                buttons : Ext.MessageBox.OK,
                                icon : Ext.MessageBox.ERROR
                            });
                        }
                    }
                }, {
                    text : 'Cancel',
                    handler : function() {
                        pantry_maintenance_js.app.questionFormWindow.destroy();
                        pantry_maintenance_js.app.add = null;
                        Ext.MessageBox.hide();
                    }
                }],
            });

            pantry_maintenance_js.app.questionFormWindow = new Ext.Window({
                title : 'Question Form',
                layout : 'fit',
                width : 500,
                minHeight : 100,
                closeAction : 'destroy',
                plain : 'true',
                modal : 'true',
                items : pantry_maintenance_js.app.questionFormPanel
            });

            pantry_maintenance_js.app.questionFormWindow.show();
        },

        /************************
         * Categoy Forms
         ************************/

        openCategoryForm : function() {
            pantry_maintenance_js.app.categoryFormPanel = new Ext.FormPanel({
                width : '100%',
                height : '100%',
                autoHeight : true,
                defaults : {
                    anchor : '95%',
                    allowBlank : false,
                    labelWidth : 150,
                    msgTarget : 'side',
                    xtype : 'fieldset'
                },
                items : [{
                    id : 'contract_extension_form',
                    title : 'Category Details',
                    style : 'margin: 15px 15px 15px 15px; background: none repeat scroll 0 0 #EEEEEE;',
                    defaults : {
                        anchor : '95%',
                        xtype : 'textfield',
                        msgTarget : 'side'
                    },
                    items : [{
                        allowBlank : false,
                        xtype : 'textfield',
                        id : 'category_name',
                        name : 'category_name',
                        emptyText : 'Category',
                    }, {
                        xtype : 'hidden',
                        value : '',
                        id : 'category_id',
                        name : 'category_id'
                    }]
                }],
                buttons : [{
                    text : 'Save',
                    handler : function() {
                        if (pantry_maintenance_js.app.categoryFormPanel.isValid()) {
                            Ext.MessageBox.show({
                                title : 'Saving Category',
                                progressText : 'Please wait..',
                                width : 300,
                                wait : true,
                                waitConfig : {
                                    interval : 200
                                },
                                animateTarget : 'waitButton'

                            });

                            var idSurvey;
                            if (( idSurvey = pantry_maintenance_js.app.concessionaire_grid.getSelectionModel().getSelection()[0].data['id']) == null) {
                                console.error('error');
                                return false;
                            }
                            pantry_maintenance_js.app.categoryFormPanel.submit({
                                url : 'save-category',
                                params : {
                                    "idconcessionaire" : idSurvey
                                },
                                success : function(res, opt) {
                                    Ext.MessageBox.hide();
                                    if (opt.result.status === 'success') {

                                        pantry_maintenance_js.app.category_store.reload();
                                        pantry_maintenance_js.app.categoryFormWindow.destroy();
                                        Ext.MessageBox.show({
                                            title : 'Category',
                                            msg : opt.result.msg,
                                            buttons : Ext.MessageBox.OK,
                                            icon : Ext.MessageBox.INFO
                                        });

                                        return;
                                    } else {
                                        Ext.MessageBox.show({
                                            title : 'Category',
                                            msg : opt.result.msg,
                                            buttons : Ext.MessageBox.OK,
                                            icon : Ext.MessageBox.INFO
                                        });
                                        return;
                                    }
                                }
                            });
                        }
                        else
                        {
                            Ext.MessageBox.show({
                                title : 'Error Message',
                                msg : 'The Category field is required.',
                                buttons : Ext.MessageBox.OK,
                                icon : Ext.MessageBox.ERROR
                            });
                        }
                    }
                }, {
                    text : 'Cancel',
                    handler : function() {
                        pantry_maintenance_js.app.categoryFormWindow.destroy();
                        Ext.MessageBox.hide();
                    }
                }],
            });

            pantry_maintenance_js.app.categoryFormWindow = new Ext.Window({
                title : 'Category Form',
                layout : 'fit',
                width : 300,
                minHeight : 100,
                closeAction : 'destroy',
                plain : 'true',
                modal : 'true',
                items : pantry_maintenance_js.app.categoryFormPanel
            });

            pantry_maintenance_js.app.categoryFormWindow.show();
        },

        /************************
         * Survey Forms
         ************************/
        deleteOption : function(){
            var sm = pantry_maintenance_js.app.option_grid.getSelectionModel();
            if(!sm.hasSelection()){
                Ext.MessageBox.show({
                    title : 'Survey Option',
                    msg : 'Please select an option',
                    buttons : Ext.MessageBox.OK,
                    icon : Ext.MessageBox.ERROR
                });
                return false;
            }
            var id = sm.getSelection()[0].data.id;
            var qDescription = sm.getSelection()[0].data.description;

            Ext.MessageBox.confirm({
                title : 'Delete Option',
                msg : 'Are you sure you want to delete the option ' + '(' + qDescription + ') ?',
                buttons : Ext.Msg.YESNO,
                fn : function(btn) {
                    if (btn === 'yes') {
                        Ext.Ajax.request({
                            url : 'delete-option',
                            method : 'POST',
                            waitTitle : 'Connecting',
                            waitMsg : 'Deleting data...',
                            params : {
                                'id' : id
                            },
                            timeout : 60000,
                            success : function(res, opt) {
                                Ext.Msg.alert({
                                    title : 'Success Message',
                                    msg : Ext.JSON.decode(res.responseText).msg,
                                    buttons : Ext.Msg.OK
                                });
                                pantry_maintenance_js.app.option_store.reload();
                                pantry_maintenance_js.app.option_store.loadPage(1);
                            }
                        });
                    } else
                        return;
                }
            });
         },
        openOptionForm: function(){
             if (! btnValidation('editQuestion'))
                return false;
            var selection = pantry_maintenance_js.app.question_grid.getSelectionModel().getSelection()[0].data,
                question = selection.description;

            if(selection.type != 'combobox'){
                Ext.MessageBox.show({
                    title : 'Invalid Question Type',
                    msg : 'Only combobox type questions can only add answers/options. ',
                    buttons : Ext.MessageBox.OK,
                    icon : Ext.MessageBox.ERROR
                });
                return false;
            }
            //-- Grid Store
            pantry_maintenance_js.app.option_store = new Ext.data.Store({
                model : 'Option',
                pageSize : 15,
                autoLoad : true,
                proxy : {
                    type : 'ajax',
                    url : 'option-list',
                    reader : {
                        type : 'json',
                        root : 'data',
                        totalProperty : 'totalCount'
                    }
                }
            });

             //-- Grid Panel
            pantry_maintenance_js.app.option_grid = new Ext.grid.GridPanel({
                id : 'category_grid',
                store : pantry_maintenance_js.app.option_store,
                height : 450,
                width : '33%',
                region : 'west',
                viewConfig : {

                },
                bbar: ['->',{
                    text: 'Add',
                    handler : function(){
                        pantry_maintenance_js.app.openAnswerForm();
                    }
                },{
                    text: 'Edit',
                    handler : function(){
                        var sm = pantry_maintenance_js.app.option_grid.getSelectionModel();
                        if(!sm.hasSelection()){
                            Ext.MessageBox.show({
                                title : 'Survey Option',
                                msg : 'Please select an option',
                                buttons : Ext.MessageBox.OK,
                                icon : Ext.MessageBox.ERROR
                            });
                            return false;
                        }
                        pantry_maintenance_js.app.openAnswerForm();
                        pantry_maintenance_js.app.optionForm.load({url: 'load-option', params: {'o_id':sm.getSelection()[0].data.id} });
                    }
                },{
                    text: 'Delete',
                    handler: function(){
                        pantry_maintenance_js.app.deleteOption();
                    }
                }],
                tbar: [{
                    xtype: 'box',
                    html: '<h1>'+question+'</h1>'
                }],
                
                columns : [{
                    hidden : true,
                    sortable : true,
                    dataIndex : 'id'
                },{
                    header : "Order",
                    width : 50,
                    sortable : true,
                    dataIndex : 'order'
                }, {
                    header : "Options",
                    width : 430,
                    sortable : true,
                    dataIndex : 'description'
                }]
            });

            pantry_maintenance_js.app.optionWindow = new Ext.Window({
                title : 'Answers',
                layout : 'fit',
                width : 507,
                minHeight : 410,
                closeAction : 'destroy',
                plain : 'true',
                items : pantry_maintenance_js.app.option_grid,
                bbar : [{
                    xtype : 'pagingtoolbar',
                    pageSize : 15,
                    store : pantry_maintenance_js.app.option_store,
                    displayInfo : true,
                    displayMsg : 'Displaying {0} - {1} of {2} records',
                    emptyMsg : 'No options found',
                }],
                modal : 'true'
            });
            pantry_maintenance_js.app.option_store.getProxy().setExtraParam('q_id', selection.id);
            pantry_maintenance_js.app.option_store.load();
            pantry_maintenance_js.app.optionWindow.show();
        
        
        },
        openAnswerForm: function(){
            var selection = pantry_maintenance_js.app.question_grid.getSelectionModel().getSelection()[0].data;

            pantry_maintenance_js.app.optionForm = new Ext.FormPanel({
                width : '100%',
                height : '100%',
                autoHeight : true,
                defaults : {
                    anchor : '95%',
                    allowBlank : false,
                    labelWidth : 150,
                    msgTarget : 'side',
                    xtype : 'fieldset'
                },
                items : [{
                    id : 'option_form',
                    title : "Answer Form",
                    style : 'margin: 15px 15px 15px 15px; background: none repeat scroll 0 0 #EEEEEE;',
                    defaults : {
                        anchor : '95%',
                        xtype : 'textfield',
                        msgTarget : 'side'
                    },
                    items : [{
                        hidden: true,
                        id: 'option_id',
                        name: 'option_id'
                    },{
                        hidden: true,
                        id: 'question_id',
                        name: 'question_id',
                        value: selection.id
                    },{
                        allowBlank : false,
                        selectOnFocus: true,
                        fieldLabel: 'Option',
                        id : 'option_desc',
                        name : 'option_desc',
                        emptyText : 'Possible Answer'
                    }]
                }],
                buttons : [{
                    text: 'Save',
                    handler: function(){
                        var form = pantry_maintenance_js.app.optionForm;
                        var valid = form.isValid();

                        if(valid){
                            Ext.MessageBox.show({
                                title : 'Saving option',
                                progressText : 'Please wait..',
                                width : 300,
                                wait : true,
                                waitConfig : {
                                    interval : 200
                                },
                                animateTarget : 'waitButton'
                            });
                            form.submit({
                                url: 'save-option',
                                success: function(res, opt){
                                    Ext.MessageBox.hide();
                                    if (opt.result.status === 'success') {
                                        pantry_maintenance_js.app.optionFormWindow.destroy();
                                        pantry_maintenance_js.app.option_store.load();
                                        Ext.MessageBox.show({
                                            title : 'Survey',
                                            msg : opt.result.msg,
                                            buttons : Ext.MessageBox.OK,
                                            icon : Ext.MessageBox.INFO
                                        });
                                        return;
                                    } else {
                                        Ext.MessageBox.show({
                                            title : 'Survey',
                                            msg : opt.result.msg,
                                            buttons : Ext.MessageBox.OK,
                                            icon : Ext.MessageBox.INFO
                                        });
                                        return;
                                    }
                                }
                            })
                        }
                    }
                },{
                    text : 'Close',
                    handler : function() {
                        pantry_maintenance_js.app.optionFormWindow.destroy();
                        Ext.MessageBox.hide();
                    }
                }],
                listeners: [{
                    'click' : function(){
                        $('#conc_url-inputEl').select()
                    }

                }]
            });

            pantry_maintenance_js.app.optionFormWindow = new Ext.Window({
                title : 'Survey Form',
                layout : 'fit',
                width : 500,
                minHeight : 200,
                closeAction : 'destroy',
                plain : 'true',
                modal : 'true',
                items : pantry_maintenance_js.app.optionForm
            });

            pantry_maintenance_js.app.optionFormWindow.show();
        },

        openUrlForm: function(){
            pantry_maintenance_js.app.concUrlForm = new Ext.FormPanel({
                width : '100%',
                height : '100%',
                autoHeight : true,
                defaults : {
                    anchor : '95%',
                    allowBlank : false,
                    labelWidth : 150,
                    msgTarget : 'side',
                    xtype : 'fieldset'
                },
                items : [{
                    id : 'conc_url_form',
                    title : "Generated Survey URL",
                    style : 'margin: 15px 15px 15px 15px; background: none repeat scroll 0 0 #EEEEEE;',
                    defaults : {
                        anchor : '95%',
                        xtype : 'textfield',
                        msgTarget : 'side'
                    },
                    items : [{
                        allowBlank : false,
                        selectOnFocus: true,
                        xtype : 'textarea',
                        id : 'conc_url',
                        name : 'conc_url',
                        readOnly: true,
                        emptyText : 'Unable to generate URL. Try again.'
                    }]
                }, {
                    text : 'Close',
                    handler : function() {
                        pantry_maintenance_js.app.concFormWindow.destroy();
                        Ext.MessageBox.hide();
                    }
                }],
                buttons : [{
                    text : 'Close',
                    handler : function() {
                        pantry_maintenance_js.app.concUrlWindow.destroy();
                        Ext.MessageBox.hide();
                    }
                }],
                listeners: [{
                    'click' : function(){
                        $('#conc_url-inputEl').select()
                    }

                }]
            });

            pantry_maintenance_js.app.concUrlWindow = new Ext.Window({
                title : 'Survey Form',
                layout : 'fit',
                width : 500,
                minHeight : 200,
                closeAction : 'destroy',
                plain : 'true',
                modal : 'true',
                items : pantry_maintenance_js.app.concUrlForm
            });

            pantry_maintenance_js.app.concUrlWindow.show();
        
        },
        openConcAddForm : function() {
            pantry_maintenance_js.app.concFormPanel = new Ext.FormPanel({
                width : '100%',
                height : '100%',
                autoHeight : true,
                defaults : {
                    anchor : '95%',
                    allowBlank : false,
                    labelWidth : 150,
                    msgTarget : 'side',
                    xtype : 'fieldset'
                },
                items : [{
                    id : 'conc_add_form',
                    title : 'Survey\'s Details',
                    style : 'margin: 15px 15px 15px 15px; background: none repeat scroll 0 0 #EEEEEE;',
                    defaults : {
                        anchor : '95%',
                        xtype : 'textfield',
                        msgTarget : 'side'
                    },
                    items : [{
                        allowBlank : false,
                        xtype : 'textfield',
                        id : 'conc_add_id',
                        name : 'conc_add_name',
                        emptyText : 'Survey',
                    }, {
                        xtype : 'textfield',
                        hidden : true,
                        id : 'conc_add_conc_id'
                    }]
                }, {
                    text : 'Cancel',
                    handler : function() {
                        pantry_maintenance_js.app.concFormWindow.destroy();
                        Ext.MessageBox.hide();
                    }
                }],
                buttons : [{
                    text : 'Save',
                    handler : function() {
                        var data = {}, isModeEdit = pantry_maintenance_js.app.isConcEdit;
                        data = {
                            cid : (isModeEdit) ? Ext.getCmp('conc_add_conc_id').getValue() : -1
                        };

                        if (pantry_maintenance_js.app.concFormPanel.isValid()) {
                            Ext.MessageBox.show({
                                title : 'Saving Survey',
                                progressText : 'Please wait..',
                                width : 300,
                                wait : true,
                                waitConfig : {
                                    interval : 200
                                },
                                animateTarget : 'waitButton'
                            });

                            pantry_maintenance_js.app.concFormPanel.submit({
                                url : 'save-concessionaire',
                                params : data,
                                success : function(res, opt) {
                                    Ext.MessageBox.hide();
                                    if (opt.result.status === 'success') {
                                        pantry_maintenance_js.app.concFormWindow.destroy();
                                        pantry_maintenance_js.app.conc_store.reload();
                                        Ext.MessageBox.show({
                                            title : 'Survey',
                                            msg : opt.result.msg,
                                            buttons : Ext.MessageBox.OK,
                                            icon : Ext.MessageBox.INFO
                                        });
                                        return;
                                    } else {
                                        Ext.MessageBox.show({
                                            title : 'Survey',
                                            msg : opt.result.msg,
                                            buttons : Ext.MessageBox.OK,
                                            icon : Ext.MessageBox.INFO
                                        });
                                        return;
                                    }
                                }
                            });
                        }
                        else
                        {
                            Ext.MessageBox.show({
                                title : 'Error Message',
                                msg : 'The Survey field is required.',
                                buttons : Ext.MessageBox.OK,
                                icon : Ext.MessageBox.ERROR
                            });
                        }
                    }
                }, {
                    text : 'Cancel',
                    handler : function() {
                        pantry_maintenance_js.app.concFormWindow.destroy();
                        pantry_maintenance_js.app.add = null;
                        Ext.MessageBox.hide();
                    }
                }]
            });

            pantry_maintenance_js.app.concFormWindow = new Ext.Window({
                title : 'Survey Form',
                layout : 'fit',
                width : 300,
                minHeight : 100,
                closeAction : 'destroy',
                plain : 'true',
                modal : 'true',
                items : pantry_maintenance_js.app.concFormPanel
            });

            pantry_maintenance_js.app.concFormWindow.show();
        },
        /************************
         * popup functions - END
         ************************/
        /************************
         *
         * Initialize Main Panel
         *
         ************************/
        initPantryMaintenance : function() {

            //-- Category Store
            pantry_maintenance_js.app.category_store = new Ext.data.Store({
                model : 'Category',
                pageSize : 15,
                autoLoad : true,
                proxy : {
                    type : 'ajax',
                    url : 'category-list',
                    reader : {
                        type : 'json',
                        root : 'data',
                        totalProperty : 'totalCount'
                    }
                }
            });

            //-- Question Store
            pantry_maintenance_js.app.question_store = new Ext.data.Store({
                model : 'Question',
                pageSize : 15,
                autoLoad : true,
                proxy : {
                    type : 'ajax',
                    url : 'load-question',
                    reader : {
                        type : 'json',
                        root : 'data',
                        totalProperty : 'totalCount'
                    }
                }
            });

            //-- Grid Panel
            pantry_maintenance_js.app.category_grid = new Ext.grid.GridPanel({
                title : 'Categories',
                id : 'category_grid',
                store : pantry_maintenance_js.app.category_store,
                height : 450,
                width : '33%',
                region : 'west',
                viewConfig : {

                },
                tbar : ['->', {
                    text : 'Add',
                    handler : function() {
                        if (! btnValidation('addCategory')) {
                            return;
                        }
                        pantry_maintenance_js.app.openCategoryForm();
                    }
                }, {
                    text : 'Edit',
                    handler : function() {
                        //if(!pantry_maintenance_js.app.category_grid.getSelectionModel().hasSelection())
                        if (! btnValidation('editCategory')) {
                            return;
                        } else {
                            pantry_maintenance_js.app.openCategoryForm();
                            var catId = pantry_maintenance_js.app.category_grid.getSelectionModel().getSelection()[0].data.id;
                            var catName = pantry_maintenance_js.app.category_grid.getSelectionModel().getSelection()[0].data.category;

                            Ext.getCmp('category_id').setValue(catId);
                            Ext.getCmp('category_name').setValue(catName);
                        }
                    }
                }, {
                    text : 'Delete',
                    handler : function() {
                        if (! btnValidation('deleteCategory')) {
                            return;
                        }
                        if (!pantry_maintenance_js.app.category_grid.getSelectionModel().hasSelection()) {
                            Ext.MessageBox.show({
                                title : 'No Category Selected',
                                msg : 'Please Select a Category to Delete',
                                buttons : Ext.MessageBox.OK,
                                icon : Ext.MessageBox.INFO
                            });

                            return;
                        } else {
                            var catId = pantry_maintenance_js.app.category_grid.getSelectionModel().getSelection()[0].data.id;
                            var catName = pantry_maintenance_js.app.category_grid.getSelectionModel().getSelection()[0].data.category;
                            Ext.MessageBox.confirm({
                                title : 'Delete Category',
                                msg : 'Are you sure you want to delete the category ' + '(' + catName + ') ?',
                                buttons : Ext.Msg.YESNO,
                                fn : function(btn) {
                                    if (btn === 'yes') {
                                        var questionList = pantry_maintenance_js.app.question_grid.getStore().data.items;

                                        if (questionList.length > 0) {
                                            Ext.Msg.alert('Deletion Error', 'Cannot Delete Category with Questions');
                                        } else {
                                            Ext.Ajax.request({
                                                url : 'delete-category',
                                                method : 'POST',
                                                waitTitle : 'Connecting',
                                                waitMsg : 'Deleting category...',
                                                params : {
                                                    'id' : catId
                                                },
                                                timeout : 60000,
                                                success : function(res, opt) {
                                                    Ext.Msg.alert({
                                                        title : 'Success',
                                                        msg : Ext.JSON.decode(res.responseText).data,
                                                        buttons : Ext.Msg.OK
                                                    });
                                                    pantry_maintenance_js.app.question_store.reload();
                                                    pantry_maintenance_js.app.category_store.loadPage(1);
                                                }
                                            });
                                        }

                                    } else
                                        return;
                                }
                            });

                        }
                    }
                }],

                bbar : {
                    xtype : 'pagingtoolbar',
                    pageSize : 15,
                    store : pantry_maintenance_js.app.category_store,
                    displayInfo : true,
                    displayMsg : 'Displaying {0} - {1} of {2} records',
                    emptyMsg : ' ',
                },
                columns : [{
                    hidden : true,
                    sortable : true,
                    dataIndex : 'id'
                }, {
                    header : "Category",
                    width : 288,
                    sortable : true,
                    dataIndex : 'category'
                }],
                listeners : {
                    cellclick : function(view, td, cellIndex, record, tr, rowIndex, e, eOpts) {
                        pantry_maintenance_js.app.question_store.getProxy().setExtraParam('c_id', record.get('id'));
                        pantry_maintenance_js.app.question_grid.view.show();
                        pantry_maintenance_js.app.question_store.load();
                    }
                }
            });

            //-- Question Grid Panel
            pantry_maintenance_js.app.question_grid = new Ext.grid.GridPanel({
                title : 'Questions',
                id : 'question_grid',
                store : pantry_maintenance_js.app.question_store,
                height : 450,
                width : '34%',
                region : 'west',
                viewConfig : {
                    emptyText : 'No Data Found'
                },
                tbar : [{
                    text: 'Answers',
                    handler: function(){
                       
                        pantry_maintenance_js.app.openOptionForm();
                    }
                }, '->', {
                    text : 'Add',
                    handler : function() {
                        if (! btnValidation('addQuestion'))
                            return false;
                        if (!pantry_maintenance_js.app.category_grid.getSelectionModel().hasSelection()) {
                            Ext.MessageBox.show({
                                title : 'No Category Selected',
                                msg : 'Select A Category First Before Adding Questions',
                                buttons : Ext.MessageBox.OK,
                                icon : Ext.MessageBox.INFO
                            });

                            return;
                        } else {
                            pantry_maintenance_js.app.openQuestionForm();
                            pantry_maintenance_js.app.add = 'add';
                        }
                    }
                }, {
                    text : 'Edit',
                    handler : function() {
                        if (! btnValidation('editQuestion'))
                            return false;
                        if (!pantry_maintenance_js.app.question_grid.getSelectionModel().hasSelection()) {
                            Ext.MessageBox.show({
                                title : 'No Question Selected',
                                msg : 'Please Select a Question to Edit',
                                buttons : Ext.MessageBox.OK,
                                icon : Ext.MessageBox.INFO
                            });

                            return;
                        } else {
                            pantry_maintenance_js.app.openQuestionForm();
                            var qId = pantry_maintenance_js.app.question_grid.getSelectionModel().getSelection()[0].data.id;

                            Ext.Ajax.request({
                                url : 'edit-question',
                                method : 'POST',
                                waitTitle : 'Connecting',
                                waitMsg : 'Editing Data...',
                                params : {
                                    'q_id' : qId
                                },
                                timeout : 60000,
                                success : function(res, opt) {
                                    var response = Ext.JSON.decode(res.responseText);
                                    if (response.status === 'success') {
                                        Ext.getCmp('question_name').setValue(response.data[1]);

                                        if (response.data[2] === 'rating')
                                            Ext.getCmp('rating_id').setValue(response.data[2]);
                                        else
                                            Ext.getCmp('comment_id').setValue(response.data[2]);

                                        if(response.data[3] == true)
                                            Ext.getCmp('optional').setValue(true);
                                        switch(response.data[4]){
                                            case 'grouped':
                                                Ext.getCmp('grouping_grouped').setValue(true);
                                                break;
                                            case 'separated':
                                                Ext.getCmp('grouping_separated').setValue(true);
                                                break;
                                            case 'none':
                                            default:
                                                Ext.getCmp('grouping_none').setValue(true);
                                        }
                                    } else
                                        return;
                                }
                            });

                        }
                    }
                }, {
                    text : 'Delete',
                    handler : function() {
                        if (! btnValidation('deleteQuestion'))
                            return false;
                        if (!pantry_maintenance_js.app.question_grid.getSelectionModel().hasSelection()) {
                            Ext.MessageBox.show({
                                title : 'No Question Selected',
                                msg : 'Please Select a Question to Delete',
                                buttons : Ext.MessageBox.OK,
                                icon : Ext.MessageBox.INFO
                            });

                            return;
                        } else {
                            var qId = pantry_maintenance_js.app.question_grid.getSelectionModel().getSelection()[0].data.id;
                            var qDescription = pantry_maintenance_js.app.question_grid.getSelectionModel().getSelection()[0].data.description;

                            Ext.MessageBox.confirm({
                                title : 'Delete Question',
                                msg : 'Are you sure you want to delete the question ' + '(' + qDescription + ') ?',
                                buttons : Ext.Msg.YESNO,
                                fn : function(btn) {
                                    if (btn === 'yes') {
                                        Ext.Ajax.request({
                                            url : 'delete-question',
                                            method : 'POST',
                                            waitTitle : 'Connecting',
                                            waitMsg : 'Deleting data...',
                                            params : {
                                                'id' : qId
                                            },
                                            timeout : 60000,
                                            success : function(res, opt) {
                                                Ext.Msg.alert({
                                                    title : 'Success Message',
                                                    msg : Ext.JSON.decode(res.responseText).data,
                                                    buttons : Ext.Msg.OK
                                                });
                                                pantry_maintenance_js.app.question_store.reload();
                                                pantry_maintenance_js.app.question_store.loadPage(1);
                                            }
                                        });
                                    } else
                                        return;
                                }
                            });
                        }
                    }
                }],

                bbar : {
                    xtype : 'pagingtoolbar',
                    pageSize : 15,
                    store : pantry_maintenance_js.app.question_store,
                    //displayInfo : true,
                    displayInfo : false,
                    //displayMsg : 'Displaying {0} - {1} of {2} records',
                    emptyMsg : ' ',
                    prependButtons:true
                },
                columns : [{
                    hidden : true,
                    sortable : true,
                    dataIndex : 'id'
                }, {

                    hidden : true,
                    sortable : true,
                    dataIndex : 'type'
                }, {
                    header : "Question",
                    width : 298,
                    sortable : true,
                    dataIndex : 'description'
                }]
            });

            pantry_maintenance_js.app.conc_store = new Ext.data.Store({
                model : 'Survey',
                pageSize : 15,
                autoLoad : true,
                proxy : {
                    type : 'ajax',
                    url : 'load_conc',
                    reader : {
                        type : 'json',
                        root : 'data',
                        totalProperty : 'totalCount'
                    }
                }
            });

            // concessionaires grid
            pantry_maintenance_js.app.concessionaire_grid = new Ext.create('Ext.grid.Panel', {
                title : 'Surveys',
                store : pantry_maintenance_js.app.conc_store,
                region : 'west',
                bbar : {
                    xtype : 'pagingtoolbar',
                    pageSize : 15,
                    store : pantry_maintenance_js.app.conc_store,
                    displayInfo : true,
                    displayMsg : 'Displaying {0} - {1} of {2} records',
                    emptyMsg : ' '
                },
                columns : [{
                    hidden : true,
                    sortable : true,
                    dataIndex : 'id'
                }, {
                    header : "Survey",
                    width : 288,
                    sortable : true,
                    dataIndex : 'description'
                }],
                listeners : {
                    cellclick : function(view, td, cellIndex, record, tr, rowIndex, e, eOpts) {
                        pantry_maintenance_js.app.category_store.getProxy().setExtraParam('c_id', record.get('id'));
                        pantry_maintenance_js.app.category_store.load();
                        pantry_maintenance_js.app.question_grid.view.hide();
                    }
                },
                width : "33%",
                tbar : [{
                    text: 'Generate Link',
                    handler : function(){
                        if (!btnValidation('editSurvey'))
                            return;
                        pantry_maintenance_js.app.openUrlForm();
                        var id = pantry_maintenance_js.app.concessionaire_grid.getSelectionModel().getSelection()[0].data.id
                        pantry_maintenance_js.app.concUrlForm.load({
                            url: 'generate-survey-url',
                            params: {'id' : id}
                        });
                    }
                }, '->', {
                    text : 'Add',
                    handler : function() {
                        pantry_maintenance_js.app.isConcEdit = 0;
                        pantry_maintenance_js.app.openConcAddForm();
                    }
                }, {
                    text : 'Edit',
                    handler : function() {
                        pantry_maintenance_js.app.isConcEdit = 1;
                        if (!btnValidation('editSurvey'))
                            return;
                        pantry_maintenance_js.app.openConcAddForm();
                        var concId = pantry_maintenance_js.app.concessionaire_grid.getSelectionModel().getSelection()[0].data.id;
                        var concDescription = pantry_maintenance_js.app.concessionaire_grid.getSelectionModel().getSelection()[0].data.description;
                        var selectedModel = pantry_maintenance_js.app.concessionaire_grid.getSelectionModel();
                        var index = selectedModel.getSelection()[0].index % pantry_maintenance_js.app.concessionaire_grid.getStore().pageSize; 
                        selectedModel.deselectAll(true);
                        selectedModel.select(index, false, true);
                        var selectedRow = selectedModel.getSelection()[0];

                        //Ext.getCmp('conc_add_id').setValue(concDescription);
                        Ext.getCmp('conc_add_id').setValue(selectedRow.getData().description);
                        Ext.getCmp('conc_add_conc_id').setValue(concId);
                    }
                }, {
                    text : 'Delete',
                    handler : function() {
                        if (!btnValidation('deleteSurvey'))
                            return;
                        else {
                            var concId = pantry_maintenance_js.app.concessionaire_grid.getSelectionModel().getSelection()[0].data.id;
                            var concName = pantry_maintenance_js.app.concessionaire_grid.getSelectionModel().getSelection()[0].data.description;
                            Ext.MessageBox.confirm({
                                title : 'Delete Survey',
                                msg : 'Are you sure you want to delete the concessionaire' + ' (' + concName + ') ?',
                                buttons : Ext.Msg.YESNO,
                                fn : function(btn) {
                                    if (btn === 'yes') {
                                        var catList = pantry_maintenance_js.app.category_grid.getStore().data.items;
                                        if (catList.length > 0) {
                                            Ext.Msg.alert('Deletion Error', 'Cannot Delete Survey with Categories');
                                        } else {
                                            Ext.Ajax.request({
                                                url : 'delete-concessionaire',
                                                method : 'POST',
                                                waitTitle : 'Connecting',
                                                waitMsg : 'Deleting Survey...',
                                                params : {
                                                    'id' : concId
                                                },
                                                timeout : 60000,
                                                success : function(res, opt) {
                                                    Ext.Msg.alert({
                                                        title : 'Success',
                                                        msg : Ext.JSON.decode(res.responseText).data,
                                                        buttons : Ext.Msg.OK
                                                    });
                                                    pantry_maintenance_js.app.conc_store.reload();
                                                    pantry_maintenance_js.app.question_grid.view.hide();
                                                }
                                            });
                                        }

                                    } else
                                        return;
                                }
                            });
                        }
                    }
                }]
            });

            pantry_maintenance_js.app.main_panel = new Ext.create('Ext.Panel', {
                layout : 'border',
                defaults : {
                    split : true,
                    bodyStyle : 'padding:15px'
                },
                width : 1000,
                height : 600,
                center : true,
                frame : true,
                title : 'Survey Form Maintenance',
                items : [pantry_maintenance_js.app.concessionaire_grid, pantry_maintenance_js.app.category_grid, pantry_maintenance_js.app.question_grid]
            });

            pantry_maintenance_js.app.main_panel.render('form_content');
            pantry_maintenance_js.app.main_panel.show();

        }
    }
}();

Ext.onReady(pantry_maintenance_js.app.init, pantry_maintenance_js.app);

// tyr functions

// button events validation
// expected vars to be defined *consider an automated test
/* pantry_maintenance_js.app.concessionaire_grid
 *
 */
function btnValidation2(mode) {
    var msg = 'Please Select a Survey for category adding', mode = ( typeof mode === 'undefined') ? 'addCategory' : mode;
    pantry_maintenance_js_app = pantry_maintenance_js.app;

    if ( typeof pantry_maintenance_js_app.concessionaire_grid === "undefined" || typeof pantry_maintenance_js_app.category_grid === 'undefined') {
        // error
        console.error("unexpected undefined");
    }

    if (mode === 'addCategory' || mode === 'editCategory' || mode === 'deleteCategory' || mode === 'addQuestion' || mode === 'editQuestion' || mode === 'deleteQuestion') {
        if (! pantry_maintenance_js_app.concessionaire_grid.getSelectionModel().hasSelection()) {
            return;

            msg = 'Please select a Survey first.';
            Ext.MessageBox.show({
                title : 'No Surveys Selected',
                msg : msg,
                buttons : Ext.MessageBox.OK,
                icon : Ext.MessageBox.INFO
            });

            return false;
        } else if (mode !== 'addCategory' && mode !== 'deleteQuestion' && ! pantry_maintenance_js_app.category_grid.getSelectionModel().hasSelection()) {
            msg = 'Please select a Category first';
            Ext.MessageBox.show({
                title : 'No Category selected',
                msg : msg,
                buttons : Ext.MessageBox.OK,
                icon : Ext.MessageBox.INFO
            });

            return false;
        } else {
            if (!pantry_maintenance_js.app.question_grid.getSelectionModel().getSelection()[0]) {
                msg = 'Please Select a Question first.';
                Ext.MessageBox.show({
                    title : 'No Category selected',
                    msg : msg,
                    buttons : Ext.MessageBox.OK,
                    icon : Ext.MessageBox.INFO
                });

                return false;
            }
        }
    } else if (mode === 'editSurvey' || mode === 'deleteSurvey') {
        if (! pantry_maintenance_js_app.concessionaire_grid.getSelectionModel().hasSelection()) {
            msg = (mode === 'editSurvey') ? 'Please Select a Concessioanire to edit' : 'Please Select a Concessioanire to delete';

            Ext.MessageBox.show({
                title : 'No Survey Selected',
                msg : msg,
                buttons : Ext.MessageBox.OK,
                icon : Ext.MessageBox.INFO
            });
            return false;
        }
    }

    return true;
};

function btnValidation(mode) {
    var msg = 'Please Select a Survey for category adding', mode = ( typeof mode === 'undefined') ? 'addCategory' : mode;
    pantry_maintenance_js_app = pantry_maintenance_js.app;

    if ( typeof pantry_maintenance_js_app.concessionaire_grid === "undefined" || typeof pantry_maintenance_js_app.category_grid === 'undefined') {
        // error
        console.error("unexpected undefined");
    }

    if (mode === 'addQuestion') {
        if (! pantry_maintenance_js_app.category_grid.getSelectionModel().hasSelection()) {
            //return;

            msg = 'Please select a Category first.';
            Ext.MessageBox.show({
                title : 'No Category Selected',
                msg : msg,
                buttons : Ext.MessageBox.OK,
                icon : Ext.MessageBox.INFO
            });

            return false;
        }
    } else if (mode === 'addCategory') {
        if (! pantry_maintenance_js_app.concessionaire_grid.getSelectionModel().hasSelection()) {
            msg = 'Please select a Survey first.';
            Ext.MessageBox.show({
                title : 'No Survey Selected',
                msg : msg,
                buttons : Ext.MessageBox.OK,
                icon : Ext.MessageBox.INFO
            });
            return false;
        }
    } else if (mode === 'editQuestion' || mode === 'deleteQuestion') {
        if (! pantry_maintenance_js_app.question_grid.getSelectionModel().hasSelection()) {
            msg = 'Please select a Question first.';
            Ext.MessageBox.show({
                title : 'No Question Selected',
                msg : msg,
                buttons : Ext.MessageBox.OK,
                icon : Ext.MessageBox.INFO
            });
            return false;
        }
    } else if (mode === 'deleteCategory') {
        if (! pantry_maintenance_js_app.category_grid.getSelectionModel().hasSelection()) {
            msg = 'Please select a Category first.';
            Ext.MessageBox.show({
                title : 'No Category Selected',
                msg : msg,
                buttons : Ext.MessageBox.OK,
                icon : Ext.MessageBox.INFO
            });
            return false;
        } else if (pantry_maintenance_js.app.question_grid.getStore().data.items.length) {
            Ext.Msg.alert('Deletion Error', 'Cannot Delete Category with Questions');
            return false;
        }
    } else if (mode === 'editCategory') {
        if (! pantry_maintenance_js_app.category_grid.getSelectionModel().hasSelection()) {
            msg = 'Please select a Category first.';
            Ext.MessageBox.show({
                title : 'No Category Selected',
                msg : msg,
                buttons : Ext.MessageBox.OK,
                icon : Ext.MessageBox.INFO
            });
            return false;
        }
    } else if (mode === 'deleteSurvey' || mode === 'editSurvey') {
        if (! pantry_maintenance_js_app.concessionaire_grid.getSelectionModel().hasSelection()) {
            msg = 'Please select a Survey first.';
            Ext.MessageBox.show({
                title : 'No Survey selected',
                msg : msg,
                buttons : Ext.MessageBox.OK,
                icon : Ext.MessageBox.INFO
            });
            return false;
        }
    }
    return true;
};
