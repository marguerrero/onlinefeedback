Ext.namespace('pantry_maintenance_js');
Ext.define('Category',
{
    extend: 'Ext.data.Model',
    fields: 
    [
        {name: 'category'},
        {name: 'id'}  
    ]
});
Ext.define('Question',
{
    extend: 'Ext.data.Model',
    fields: 
    [
        {name: 'description'},
        {name: 'id'}  
    ]
});

/************************
* Extjs Models
************************/

pantry_maintenance_js.app = function()
{
    return {
        init: function()
        {
            pantry_maintenance_js.app.initPantryMaintenance();
        },

        /************************
        * popup functions - START
        ************************/
       
        openQuestionForm: function()
        {
            pantry_maintenance_js.app.questionFormPanel = new Ext.FormPanel({
                width: '100%',
                height: '100%',
                autoHeight: true,
                defaults:{
                    anchor: '95%',
                    allowBlank: false,
                    labelWidth: 150,
                    msgTarget: 'side',
                    xtype: 'fieldset'
                }, 
                items: [
                {
                    id: 'question_form',
                    title: 'Question Details',
                    style: 'margin: 15px 15px 15px 15px; background: none repeat scroll 0 0 #EEEEEE;',
                    defaults:
                    {
                        anchor: '95%',
                        xtype: 'textfield',
                        msgTarget: 'side'
                    },
                    items: [{
                            allowBlank: false,
                            xtype: 'textfield',
                            id: 'question_name',
                            name: 'question_name',
                            emptyText: 'Question',
                        },{
                            xtype: 'hidden',
                            value: '',
                            id: 'question_id',
                            name: 'question_id'
                        },{
                            xtype: 'radiofield',
                            boxLabel: 'Rating',
                            inputValue: 'rating',
                            name: 'type',
                            id: 'rating_id',
                            checked: true    
                        },{
                            xtype: 'radiofield',
                            boxLabel: 'Comment',
                            inputValue: 'comment',
                            name: 'type',
                            id: 'comment_id'
                            
                        }]
                }],
                buttons:[
                {
                    text: 'Save',
                    handler: function()
                    {
                        var q_id;
                        
                        if(pantry_maintenance_js.app.questionFormPanel.isValid())
                        {   
                            Ext.MessageBox.show(
                            {
                               title: 'Saving Question',
                               progressText: 'Please wait..',
                               width:300,
                               wait:true,
                               waitConfig: {interval:200},
                               animateTarget: 'waitButton'
                            });
                            
                            if(pantry_maintenance_js.app.question_grid.store.data.length === 0)
                                q_id = 'false';
                                

                            if(!pantry_maintenance_js.app.question_grid.getSelectionModel().hasSelection())
                                q_id = 'false';
                            else if(pantry_maintenance_js.app.add === 'add')
                                q_id = 'false';    
                            else
                                q_id = pantry_maintenance_js.app.question_grid.getSelectionModel().getSelection()[0].data.id;
   
                            
                            pantry_maintenance_js.app.questionFormPanel.submit(
                            {
                                url: 'save-question',
                                params: { cat_id : pantry_maintenance_js.app.category_grid.getSelectionModel().getSelection()[0].data.id,
                                          q_id : q_id
                                        },
                                success: function(res, opt)
                                {
                                    Ext.MessageBox.hide();
                                    if(opt.result.status === 'success') 
                                    {
                                        pantry_maintenance_js.app.question_store.reload();
                                        pantry_maintenance_js.app.questionFormWindow.destroy();
                                         Ext.MessageBox.show(
                                            {
                                                title: 'Question',
                                                msg: opt.result.msg,
                                                buttons: Ext.MessageBox.OK,
                                                icon: Ext.MessageBox.INFO
                                            });
                                         
                                        return;
                                    }
                                    else
                                    {
                                        Ext.MessageBox.show(
                                        {
                                            title: 'Question',
                                            msg: opt.result.msg,
                                            buttons: Ext.MessageBox.OK,
                                            icon: Ext.MessageBox.INFO
                                        });
                                        return;
                                    }
                                }
                            });
                        }
                    }
                },{
                    text: 'Cancel',
                    handler: function()
                    {
                        pantry_maintenance_js.app.questionFormWindow.destroy();
                        pantry_maintenance_js.app.add = null;
                        Ext.MessageBox.hide();
                    }
                }],
            });
            
            pantry_maintenance_js.app.questionFormWindow = new Ext.Window(
            {
                title: 'Question Form',
                layout      : 'fit',
                width       : 300,
                minHeight   : 100,
                closeAction : 'destroy',
                plain       : 'true',
                modal       : 'true',
                items       : pantry_maintenance_js.app.questionFormPanel
            });

            pantry_maintenance_js.app.questionFormWindow.show();       
        },
        
        
        
        /************************
        * Categoy Forms
        ************************/
        
        openCategoryForm: function()
        {
            pantry_maintenance_js.app.categoryFormPanel = new Ext.FormPanel({
                width: '100%',
                height: '100%',
                autoHeight: true,
                defaults:{
                    anchor: '95%',
                    allowBlank: false,
                    labelWidth: 150,
                    msgTarget: 'side',
                    xtype: 'fieldset'
                }, 
                items: [
                {
                    id: 'contract_extension_form',
                    title: 'Category Details',
                    style: 'margin: 15px 15px 15px 15px; background: none repeat scroll 0 0 #EEEEEE;',
                    defaults:
                    {
                        anchor: '95%',
                        xtype: 'textfield',
                        msgTarget: 'side'
                    },
                    items: [{
                            allowBlank: false,
                            xtype: 'textfield',
                            id: 'category_name',
                            name: 'category_name',
                            emptyText: 'Category',
                        },{
                            xtype: 'hidden',
                            value: '',
                            id: 'category_id',
                            name: 'category_id'
                        }]
                }],
                buttons:[
                {
                    text: 'Save',
                    handler: function()
                    {
                        if(pantry_maintenance_js.app.categoryFormPanel.isValid())
                        {
                            Ext.MessageBox.show(
                            {
                               title: 'Saving Category',
                               progressText: 'Please wait..',
                               width:300,
                               wait:true,
                               waitConfig: {interval:200},
                               animateTarget: 'waitButton'
                            });
                            pantry_maintenance_js.app.categoryFormPanel.submit(
                            {
                                url: 'save-category',
                                success: function(res, opt)
                                {
                                    Ext.MessageBox.hide();
                                    if(opt.result.status === 'success') 
                                    {
                                        pantry_maintenance_js.app.category_store.reload();
                                        pantry_maintenance_js.app.categoryFormWindow.destroy();
                                         Ext.MessageBox.show(
                                            {
                                                title: 'Category',
                                                msg: opt.result.msg,
                                                buttons: Ext.MessageBox.OK,
                                                icon: Ext.MessageBox.INFO
                                            });
                                         
                                        return;
                                    }
                                    else
                                    {
                                        Ext.MessageBox.show(
                                        {
                                            title: 'Category',
                                            msg: opt.result.msg,
                                            buttons: Ext.MessageBox.OK,
                                            icon: Ext.MessageBox.INFO
                                        });
                                        return;
                                    }
                                }
                            });
                        }
                    }
                },{
                    text: 'Cancel',
                    handler: function()
                    {
                        pantry_maintenance_js.app.categoryFormWindow.destroy();
                        Ext.MessageBox.hide();
                    }
                }],
            });
            
            pantry_maintenance_js.app.categoryFormWindow = new Ext.Window(
            {
                title: 'Category Form',
                layout      : 'fit',
                width       : 300,
                minHeight   : 100,
                closeAction : 'destroy',
                plain       : 'true',
                modal       : 'true',
                items       : pantry_maintenance_js.app.categoryFormPanel
            });

            pantry_maintenance_js.app.categoryFormWindow.show();
        },  
        /************************
        * popup functions - END
        ************************/
        /************************
        *
        * Initialize Main Panel
        *
        ************************/
        initPantryMaintenance: function()
        {


            //-- Category Store
            pantry_maintenance_js.app.category_store = new Ext.data.Store(
            {
                model: 'Category',
                pageSize: 15,
                autoLoad: true,
                proxy:
                {
                    type: 'ajax',
                    url: 'category-list',
                    reader: 
                    {
                        type: 'json',
                        root: 'data',
                        totalProperty: 'totalCount'
                    }
                }
            });

             //-- Question Store
            pantry_maintenance_js.app.question_store = new Ext.data.Store(
            {
                model: 'Question',
                pageSize: 15,
                autoLoad: true,
                proxy:
                {
                    type: 'ajax',
                    url: 'load-question',
                    reader: 
                    {
                        type: 'json',
                        root: 'data',
                        totalProperty: 'totalCount'
                    }
                }
            });

            //-- Grid Panel
            pantry_maintenance_js.app.category_grid = new Ext.grid.GridPanel(
            {
                title: 'Categories',
                id: 'category_grid',
                store: pantry_maintenance_js.app.category_store,
                height: 450,
                width: '36%',
                region: 'west',
                viewConfig:
                {
                   
                },
                tbar:
                [
                  '->',
                   {
                        text: 'Add',
                        handler: function()
                        {
                            pantry_maintenance_js.app.openCategoryForm();
                        }
                   },{
                        text: 'Edit',
                        handler: function()
                        {
                            if(!pantry_maintenance_js.app.category_grid.getSelectionModel().hasSelection())
                            {
                                 Ext.MessageBox.show(
                                 {
                                    title: 'No Category Selected',
                                    msg: 'Please Select a Category to Edit',
                                    buttons: Ext.MessageBox.OK,
                                    icon: Ext.MessageBox.INFO
                                  });
                                  
                                  return;    
                            }
                            else
                            {
                                pantry_maintenance_js.app.openCategoryForm();
                                var catId = pantry_maintenance_js.app.category_grid.getSelectionModel().getSelection()[0].data.id;
                                var catName = pantry_maintenance_js.app.category_grid.getSelectionModel().getSelection()[0].data.category;
                                
                                Ext.getCmp('category_id').setValue(catId);
                                Ext.getCmp('category_name').setValue(catName);
                            }
                        }
                   },{
                        text: 'Delete',
                        handler: function()
                        {
                            if(!pantry_maintenance_js.app.category_grid.getSelectionModel().hasSelection())
                            {
                                 Ext.MessageBox.show(
                                 {
                                    title: 'No Category Selected',
                                    msg: 'Please Select a Category to Delete',
                                    buttons: Ext.MessageBox.OK,
                                    icon: Ext.MessageBox.INFO
                                  });
                                  
                                  return;    
                            }
                            else
                            {
                                var catId = pantry_maintenance_js.app.category_grid.getSelectionModel().getSelection()[0].data.id;
                                var catName = pantry_maintenance_js.app.category_grid.getSelectionModel().getSelection()[0].data.category;
                                Ext.MessageBox.confirm({
                                    title: 'Delete Category',
                                    msg: 'Are you sure you want to delete the category ' + '(' + catName + ') ?',
                                    buttons: Ext.Msg.YESNO,
                                    fn: function (btn) {
                                       if(btn === 'yes')
                                       {
                                           var questionList = pantry_maintenance_js.app.question_grid.getStore().data.items;
                                           
                                           if(questionList.length > 0)
                                           {
                                               Ext.Msg.alert('Deletion Error', 'Cannot Delete Category with Questions');
                                           }
                                           else
                                           {
                                               Ext.Ajax.request({
                                                   url: 'delete-category',
                                                   method: 'POST',
                                                   waitTitle: 'Connecting',
                                                   waitMsg: 'Deleting category...',
                                                   params: { 'id': catId },
                                                   timeout: 60000,
                                                   success: function(res, opt){
                                                       Ext.Msg.alert({
                                                           title: 'Success',
                                                           msg: Ext.JSON.decode(res.responseText).data,
                                                           buttons: Ext.Msg.OK
                                                      });
                                                      pantry_maintenance_js.app.category_store.reload();
                                                   }    
                                               });
                                           }
                                               
                                       }
                                       else 
                                           return;         
                                    }
                                });
                                
                            }
                        }                    
                   }
                ],
               
                bbar:
                {
                    xtype: 'pagingtoolbar',
                    pageSize: 15,
                    store: pantry_maintenance_js.app.category_store,
                    displayInfo: true,
                    displayMsg: 'Displaying {0} - {1} of {2} records',
                    emptyMsg: ' '
                },
                columns: 
                [
                    {hidden: true, sortable: true, dataIndex: 'id'},
                    {header: "Category", width: 250, sortable: true, dataIndex: 'category'}
                ],
                listeners: {
                    cellclick: function(view, td, cellIndex, record, tr, rowIndex, e, eOpts){
                        pantry_maintenance_js.app.question_store.getProxy().setExtraParam('c_id', record.get('id')); 
                        pantry_maintenance_js.app.question_store.load();   
                    }
                }
            });
            

             //-- Question Grid Panel
            pantry_maintenance_js.app.question_grid=  new Ext.grid.GridPanel(
            {
                title: 'Questions',
                id: 'question_grid',
                store: pantry_maintenance_js.app.question_store,
                height: 450,
                width: '65%',
                region: 'west',
                viewConfig:
                {
                    emptyText: 'No Data Found'
                },
                tbar:
                [
                  '->',
                   {
                        text: 'Add',
                        handler: function()
                        {   
                             if(!pantry_maintenance_js.app.category_grid.getSelectionModel().hasSelection())
                             {
                                  Ext.MessageBox.show(
                                 {
                                    title: 'No Category Selected',
                                    msg: 'Select A Category First Before Adding Questions',
                                    buttons: Ext.MessageBox.OK,
                                    icon: Ext.MessageBox.INFO
                                  });
                                  
                                  return;  
                             }
                             else
                             {
                                 pantry_maintenance_js.app.openQuestionForm();                               
                                 pantry_maintenance_js.app.add = 'add';
                             }
                             
                        }
                   },{
                        text: 'Edit',
                        handler: function()
                        {
                            if(!pantry_maintenance_js.app.question_grid.getSelectionModel().hasSelection())
                            {
                                 Ext.MessageBox.show(
                                 {
                                    title: 'No Question Selected',
                                    msg: 'Please Select a Question to Edit',
                                    buttons: Ext.MessageBox.OK,
                                    icon: Ext.MessageBox.INFO
                                  });
                                  
                                  
                                  return;    
                            }
                            else
                            {
                                pantry_maintenance_js.app.openQuestionForm();
                                var qId = pantry_maintenance_js.app.question_grid.getSelectionModel().getSelection()[0].data.id;
                                
                                Ext.Ajax.request({
                                   url: 'edit-question',
                                   method: 'POST',
                                   waitTitle: 'Connecting',
                                   waitMsg: 'Editing Data...',
                                   params: { 'q_id': qId },
                                   timeout: 60000,
                                   success: function(res, opt){
                                      var response = Ext.JSON.decode(res.responseText);
                                      if(response.status === 'success')
                                      {
                                          Ext.getCmp('question_name').setValue(response.data[1]);
                                          
                                          if(response.data[2] === 'rating')
                                            Ext.getCmp('rating_id').setValue(response.data[2]);
                                          else
                                            Ext.getCmp('comment_id').setValue(response.data[2]);
                                      }
                                      else
                                        return;                                     
                                   }    
                                });
                                
                            }
                        }
                   },{
                        text: 'Delete',
                        handler: function()
                        {
                              if(!pantry_maintenance_js.app.question_grid.getSelectionModel().hasSelection())
                            {
                                 Ext.MessageBox.show(
                                 {
                                    title: 'No Question Selected',
                                    msg: 'Please Select a Question to Delete',
                                    buttons: Ext.MessageBox.OK,
                                    icon: Ext.MessageBox.INFO
                                  });
                                  
                                  return;    
                            }
                            else
                            {
                                var qId = pantry_maintenance_js.app.question_grid.getSelectionModel().getSelection()[0].data.id;
                                var qDescription = pantry_maintenance_js.app.question_grid.getSelectionModel().getSelection()[0].data.description;
                                
                                Ext.MessageBox.confirm({
                                    title: 'Delete Category',
                                    msg: 'Are you sure you want to delete the question ' + '(' + qDescription + ') ?',
                                    buttons: Ext.Msg.YESNO,
                                    fn: function (btn) {
                                       if(btn === 'yes')
                                       {
                                           Ext.Ajax.request({
                                               url: 'delete-question',
                                               method: 'POST',
                                               waitTitle: 'Connecting',
                                               waitMsg: 'Deleting data...',
                                               params: { 'id': qId },
                                               timeout: 60000,
                                               success: function(res, opt){
                                                   Ext.Msg.alert({
                                                       title: 'Success',
                                                       msg: Ext.JSON.decode(res.responseText).data,
                                                       buttons: Ext.Msg.OK
                                                  });
                                                  pantry_maintenance_js.app.question_store.reload();
                                               }    
                                           }); 
                                       }
                                       else
                                           return;
                                    }
                                });
                            }
                        }
                   }
                ],
               
                bbar:
                {
                    xtype: 'pagingtoolbar',
                    pageSize: 15,
                    store: pantry_maintenance_js.app.question_store,
                    displayInfo: true,
                    displayMsg: 'Displaying {0} - {1} of {2} records',
                    emptyMsg: ' '
                },
                columns: 
                [
                    {hidden: true, sortable: true, dataIndex: 'id'},
                    {header: "Question", width: 450, sortable: true, dataIndex: 'description'}
                ]
            });
            pantry_maintenance_js.app.main_panel = new Ext.create('Ext.Panel', 
            {
                layout: 'border',
                defaults: {
                    split: true,
                    bodyStyle: 'padding:15px'
                },
                width: 800,
                height: 500,
                center: true,
                frame: true,
                title: 'Survey Form Maintenance',
                items: [pantry_maintenance_js.app.category_grid,pantry_maintenance_js.app.question_grid]
            });
    
            pantry_maintenance_js.app.main_panel.render('form_content');
            pantry_maintenance_js.app.main_panel.show();

        }
    }
}();

Ext.onReady(pantry_maintenance_js.app.init, pantry_maintenance_js.app);
