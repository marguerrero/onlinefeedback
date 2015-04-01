Ext.namespace('pantry_user_management_js');

// Declare a custom VType
Ext.apply(Ext.form.VTypes, {
             // This function validates input text
    AlphaNumU:  function(v) {                                               
        return /^[a-z0-9_\.]+$/i.test(v);
    },
            // This is the tooltip message displayed when invalid input occurs
    AlphaNumUText: 'Username must be underscore, dot and alphanumeric.',          
            // This mask filter invalid keystrokes
    AlphaNumUMask: /[a-z0-9_\.]/i                                         
});

Ext.apply(Ext.form.VTypes, {
             // This function validates input text
    password :  function(val, field) {
        if (field.initialPassField) {
        	var pwd = Ext.getCmp(field.initialPassField);
        	return val === pwd.getValue();
        }
        return true;
    },
            // This is the tooltip message displayed when invalid input occurs
    passwordText: 'Passwords did not match.'
});
    

/************************
* Extjs Models
************************/
Ext.define('User', {
    extend: 'Ext.data.Model',
    fields: [{
        name: 'id',
        type: 'int',
        useNull: true
    }, 'username', 'email', 'user_role', 'active']/*,
    validations: [{
        type: 'length',
        field: 'email',
        min: 1
    }, {
        type: 'length',
        field: 'first',
        min: 1
    }, {
        type: 'length',
        field: 'last',
        min: 1
    }]*/
});

//static store
var user_roles = Ext.create('Ext.data.Store', {
    fields: ['key', 'name'],
    data : [
        {"key":"admin", "name":"Admin"},
        {"key":"report", "name":"Report"}
    ]
});

var user_activity = Ext.create('Ext.data.Store', {
    fields: ['key', 'name'],
    data : [
        {"key":"true", "name":"True"},
        {"key":"false", "name":"False"}
    ]
});


pantry_user_management_js.app = function()
{
    return {
        init: function()
        {
            pantry_user_management_js.app.initPantryUser();
        },
        /*user_grid : new Ext.FormPanel({
		    id: 'fLogin',
		    title: 'theForm!'
		}),*/
        
        initPantryUser : function() {
        	
        	var user_store = Ext.create('Ext.data.Store', {
        		pageSize: 15,
		        autoLoad: true,
		        autoSync: true,
		        remoteSort: true,
		        model: 'User',
		        proxy: {
		            type: 'rest',
		            url: 'load-user',
		            reader: {
		                type: 'json',
		                root: 'data',
		                totalProperty: 'totalCount'
		            },
		            writer: {
		                type: 'json'
		            }
		        },
		        listeners: {
		            write: function(store, operation){
		            	return;
		                var record = operation.getRecords()[0],
		                    name = Ext.String.capitalize(operation.action),
		                    verb;
		                    
		                    
		                if (name == 'Destroy') {
		                    record = operation.records[0];
		                    verb = 'Destroyed';
		                } else {
		                    verb = name + 'd';
		                }
		                Ext.Msg.alert(name, Ext.String.format("{0} user: {1}", verb, record.getId()));
		                
		            }
		        }
		    });
		    
        	// form
        	pantry_user_management_js.app.openForm = function(mode) {
        		var formUrl = 'save-user',
        			formParams = {},
        			allowBlankPass = false;
        		if ( mode === 'update' ) {
        			var selection = pantry_user_management_js.app.user_grid.getView().getSelectionModel().getSelection()[0];
                    if ( selection ) {
                    	var editData = selection.data;
                    	formUrl= 'update-user';
                    	formParams.mode = 'update';
                    	formParams.oldUsername = editData.username;
                    	formParams.oldEmail = editData.email;
                    	allowBlankPass = true;
                    }
        		}
        		pantry_user_management_js.app.addForm = Ext.create('Ext.form.Panel', {
			        //frame:true,
			        bodyStyle:'padding:5px 5px 0',
			        width: 350,
			        center:true,
			        fieldDefaults: {
			            msgTarget: 'side',
			            labelWidth: 110
			        },
			        defaultType: 'textfield',
			        defaults: {
			            anchor: '100%'
			        },
			
			        items: [{
			        	xtype: "textfield",
			            id: 'username',
			            fieldLabel: 'Username',
			            name: 'username',
			            blankText: "The Username field is required.",
			            vtype: 'AlphaNumU',
			            allowBlank:false
			        }, {
			        	xtype: "textfield",
			            id : "pass",
			        	inputType: "password",
			            fieldLabel: 'Password',
			            name: 'password',
			            allowBlank: allowBlankPass,
			            blankText: "The Password field is required.",
			            listeners : {
			            	'change' : function() {
			            		var pass_confirm = Ext.getCmp('pass_confirm');
			            		pass_confirm.allowBlank = (this.getValue() === '');
  								pass_confirm.validate();
			            	}
			            }
			        }, {
			        	xtype: "textfield",
			            id : "pass_confirm",
			        	inputType: "password",
			            fieldLabel: 'Confirm Password',
			            name: 'confirm_password',
			            vtype : "password",
			            initialPassField : "pass",
			            blankText: "The Confirm Password field is required.",
			            allowBlank: allowBlankPass
			        }, {
			        	xtype:"combo",
			        	id: "user_role",
						store: user_roles,
						valueField : "key",
						displayField : "name",
			            fieldLabel: 'User Role',
			            name: 'user_role',
			            blankText: "The User Role field is required.",
			            allowBlank:false,
			            editable:false
			        }, {
			            fieldLabel: 'Email',
			        	id: "email",
			            name: 'email',
			            blankText: "The Email field is required.",
			            emailText: "The Email field is required.",
			            alphaText: "The Email field should be an e-mail address in the format \"user@example.com\"",
			            vtype:'email',
			            allowBlank:false
			        }, {
			            xtype:"combo",
			        	id: "active",
			            store: user_activity,
			            fieldLabel: 'Active',
			            valueField : "key",
			            displayField : "name",
			            name: 'active',
			            blankText: "The Active field is required.",
			            allowBlank:false,
						editable: false
			        }],
			
			        buttons: [{
			            text: 'Save',
			            handler : function()
						{
							if ( pantry_user_management_js.app.addForm.getForm().isValid() ) {
								var that = this;
								var loadingMask = new Ext.LoadMask(Ext.getBody(), {msg:"Please wait..."});
								that.disable();
								loadingMask.show();
								pantry_user_management_js.app.addForm.getForm().submit({
							        url: formUrl,
							        params: formParams, 
							        method : "POST",
									success : function(action, res) {
										if ( res.success ) 
										{
											Ext.Msg.alert({
	                                           title: 'Success Message',
	                                           msg: "User " + pantry_user_management_js.app.addForm.items.getByKey('username').getValue() + " saved.",
	                                           buttons: Ext.Msg.OK
	                                      	});
										} else {
											Ext.Msg.alert({
	                                           title: 'Error while saving',
	                                           msg: "Please contact administrator.",
	                                           buttons: Ext.Msg.OK
	                                      	});
										}
										pantry_user_management_js.app.addFormWindow.destroy();
										user_store.reload();
										loadingMask.destroy();
										that.enable();
									},
									failure : function(form, action) {
										var response = Ext.JSON.decode(action.response.responseText);
										if ( response.msg )
										{
											Ext.Msg.alert({
	                                           title: 'Error while saving',
	                                           msg: response.msg,
	                                           buttons: Ext.Msg.OK
	                                      	});
										}
										else if ( response.status == 400 )
										{
											Ext.Msg.alert({
	                                           title: 'Error while saving',
	                                           msg: "Invalid characters in input.",
	                                           buttons: Ext.Msg.OK
	                                      	});
										} else {
											Ext.Msg.alert({
	                                           title: 'Error while saving',
	                                           msg: "Please contact administrator.",
	                                           buttons: Ext.Msg.OK
	                                      	});
										}													
										loadingMask.destroy();
										that.enable();
									}
								});
							}
							else
							{
								var msg = "All fields are required.";
								if ( msg = getErrorMessages(pantry_user_management_js.app.addForm.items.items) )
									;
								
								Ext.Msg.alert({
                                   title: 'Error while saving',
                                   msg: msg,
                                   buttons: Ext.Msg.OK
                              	});
							}
			            }
			        },{
			            text: 'Cancel',
			            handler: function() {
							pantry_user_management_js.app.addFormWindow.destroy();
			            }
			        }]
			    });
	
				pantry_user_management_js.app.addFormWindow = Ext.create('Ext.window.Window', {
			        title: ((mode === 'update') ? 'Update' : 'Add') + ' User',
				    height: 300,
				    width: 450,
				    modal: 'true', 
				    layout: 'fit',
				    items: pantry_user_management_js.app.addForm
				});
				
				if ( mode === 'update' ) 
				{
					Ext.getCmp('username').setValue(editData.username);
                    Ext.getCmp('user_role').setValue(editData.user_role);
                    Ext.getCmp('email').setValue(editData.email);
                    Ext.getCmp('active').setValue(editData.active);
				}
				
			    pantry_user_management_js.app.addFormWindow.center();
			    pantry_user_management_js.app.addFormWindow.show();
        	}        	
    
    		var pluginExpanded = true;
		    pantry_user_management_js.app.user_grid = Ext.create('Ext.grid.Panel', {
		        width: 850,
		        height: 650,
		        frame: true,
		        title: 'Users',
		        store: user_store,
		        iconCls: 'icon-user',
    			renderTo: "form_content",
    			listeners : {
    				celldblclick : function() { 
    					pantry_user_management_js.app.openForm('update')
    				}
    			},
		        columns: [{
				    text : '#',
				    dataIndex: 'rowIndex',
				    width: 50,
				    sortable : false,
				    // other config you need..
				    renderer : function(value, metaData, record, rowIndex)
				    {
				        return rowIndex + 1;
				    }
				}, {
		            header: 'Username',
		            flex:1,
		            sortable: true,
		            dataIndex: 'username',
		            field: {
		                xtype: 'textfield'
		            }
		        }, {
		            text: 'Email',
		            flex:1,
		            sortable: true,
		            dataIndex: 'email',
		            field: {
		                xtype: 'textfield'
		            }
		        }, {
		            text: 'Role',
		            flex:1,
		            sortable: true,
		            dataIndex: 'user_role',
		            field: {
		                xtype: 'textfield'
		            }
		        }, {
		            text: 'Active',
		            flex:1,
		            sortable: true,
		            dataIndex: 'active',
		            field: {
		                xtype: 'textfield'
		            }
		        }],
		        dockedItems: [{
		            xtype: 'toolbar',
		            items: [{
		                text: 'Add',
		                id: "user_add_btn",
		                iconCls: 'icon-add',
		                handler: function(e)
						{
							pantry_user_management_js.app.openForm();
		                }
		            }, '-', {
		                itemId: 'update',
		                text: 'Update',
		                iconCls: 'icon-update',
		                disabled: true,
		                handler: function() {
						    pantry_user_management_js.app.openForm('update');
		                	return;
		                    var selection = pantry_user_management_js.app.user_grid.getView().getSelectionModel().getSelection()[0];
		                    if ( selection ) {
		                    	var selectedUsername = selection.data.username;
		                    	Ext.MessageBox.confirm({
	                                title: 'Delete User',
	                                msg: 'Are you sure you want to delete user ' + '(' + selectedUsername + ') ?',
	                                buttons: Ext.Msg.YESNO,
	                                fn: function (btn) {
	                                    if(btn === 'yes')
	                                    {
											Ext.Ajax.request({
					                    		method : "POST",
					                    		url : "delete-user",
												waitTitle: 'Connecting',
	                                            waitMsg: 'Deleting user...',
												params: { 'username': selectedUsername },
												timeout : 12000,
												success: function(res, opt) {
													var response = Ext.JSON.decode(res.responseText);
													if ( response.success ) {
														Ext.Msg.alert({
															title: 'Delete Message',
															msg: "Successfully Deleted " + selectedUsername,
															buttons: Ext.Msg.OK
														});
													} else {
														Ext.Msg.alert({
															title: 'Failed',
															msg: response.msg,
															buttons: Ext.Msg.OK
														});
													}
														user_store.reload();
														user_store.loadPage(1);
													
												},
												failure : function(form, action) {
													var response = Ext.JSON.decode(action.response.responseText);
													if ( response.msg )
													{
														Ext.Msg.alert({
                                                           title: 'Error while deleting',
                                                           msg: response.msg,
                                                           buttons: Ext.Msg.OK
                                                      	});
													}
													else
													{
														Ext.Msg.alert({
                                                           title: 'Error while saving',
                                                           msg: "Please contact administrator",
                                                           buttons: Ext.Msg.OK
                                                      	});
													}													
													loadingMask.destroy();
													that.enable();
												}
					                    	});	                                           
	                                   }
	                                   else 
	                                       return;         
	                                }
	                            });
		                   	}
		                   	return;
		                }
		            }, '-', {
		                itemId: 'delete',
		                text: 'Delete',
		                iconCls: 'icon-delete',
		                disabled: true,
		                handler: function() {
		                    var selection = pantry_user_management_js.app.user_grid.getView().getSelectionModel().getSelection()[0];
		                    if ( selection ) {
		                    	var selectedUsername = selection.data.username;
								var that = this;
								var loadingMask = new Ext.LoadMask(Ext.getBody(), {msg:"Deleting..."});
								
		                    	Ext.MessageBox.confirm({
	                                title: 'Delete User',
	                                msg: 'Are you sure you want to delete user ' + '(' + selectedUsername + ') ?',
	                                buttons: Ext.Msg.YESNO,
	                                fn: function (btn) {
	                                    if(btn === 'yes')
	                                    {
											that.disable();
											loadingMask.show();
											Ext.Ajax.request({
					                    		method : "POST",
					                    		url : "delete-user",
												waitTitle: 'Connecting',
	                                            waitMsg: 'Deleting user...',
												params: { 'username': selectedUsername },
												timeout : 12000,
												success: function(res, opt) {
													var response = Ext.JSON.decode(res.responseText);
													if ( response.success ) {
														Ext.Msg.alert({
															title: 'Delete Message',
															msg: "Successfully Deleted " + selectedUsername,
															buttons: Ext.Msg.OK
														});
													} else {
														Ext.Msg.alert({
															title: 'Failed',
															msg: response.msg,
															buttons: Ext.Msg.OK
														});
													}
													user_store.reload();
													user_store.loadPage(1);
													loadingMask.destroy();
													that.enable();
												},
												failure : function(form, action) {
													var response = Ext.JSON.decode(action.response.responseText);
													if ( response.msg )
													{
														Ext.Msg.alert({
                                                           title: 'Error while deleting',
                                                           msg: response.msg,
                                                           buttons: Ext.Msg.OK
                                                      	});
													}
													else
													{
														Ext.Msg.alert({
                                                           title: 'Error while saving',
                                                           msg: "Please contact administrator",
                                                           buttons: Ext.Msg.OK
                                                      	});
													}													
													loadingMask.destroy();
													that.enable();
												}
					                    	});	                                           
	                                   }
	                                   else 
	                                       return;         
	                                }
	                            });
		                   	}
		                   	return;
		                }
		            }]
		        }],
		        bbar: Ext.create('Ext.PagingToolbar', {
		            store: user_store,
		            displayInfo: true,
		            displayMsg: 'Displaying users {0} - {1} of {2}',
		            emptyMsg: "No users to display"
		        }) 
		    });
		    
		    pantry_user_management_js.app.user_grid.getSelectionModel().on('selectionchange', function(selModel, selections) {
		        pantry_user_management_js.app.user_grid.down('#delete').setDisabled(selections.length === 0);
		        pantry_user_management_js.app.user_grid.down('#update').setDisabled(selections.length === 0);
		    });
		    

        	/*pantry_user_management_js.app.main_panel = new Ext.create('Ext.Panel', 
            {
                layout: 'border',
                defaults: {
                    split: true,
                    bodyStyle: 'padding:15px'
                },
                width: 900,
                height: 500,
                center: true,
                frame: true,
                title: 'User Management',
                items: [pantry_user_management_js.app.myForm, grid]
            });
    
            pantry_user_management_js.app.main_panel.render('form_content');
            pantry_user_management_js.app.main_panel.show();*/
            
        }
    }
}();

function getErrorMessages(formItems) {
	var allMsg = '<div style="width:200px">',
		counter = 1,
		errCounter = 0,
		emptyCount = 0,
		allFields = 1,
		fieldsTotal = formItems.length,
		oneEmptyMsg;
	$.each(formItems, function() {
		if( !this.validate() ) {
			var msg = $(this.getActiveError()).text();
			if ( this.getValue() === '' ||  this.getValue() === null )
			{
				emptyCount++;
				if ( this.getName() == 'email' )
					msg = 'The Email field should be in the format user@email.com.'
				oneEmptyMsg = msg;
				console.log(" get name", this.getName(), 'count and empty count', errCounter, emptyCount)
			}
			else
			{
				allFields = 0;
			}
			//allMsg += '<p style="margin:auto; text-align:center">' + (counter++) + ".) " + msg + "</p>"
			allMsg += '<p style="margin:auto; text-align:center">' + msg + "</p>"
			errCounter++;
		} else {
			allFields = 0;
		}
	})
	
	if ( allFields ) {
		console.log(" yes here")
		allMsg = '<div style="width:200px"> <p style="margin:auto; text-align:center">' + 'All fields are required.' + "</p>";
	} else if ( errCounter === 1 ) {
		if ( (new RegExp('This field should be an e-mail address in the format "user@example.com"')).test(allMsg) ) {
			allMsg = allMsg.replace('This field','The Email field');
		}
		//allMsg = oneEmptyMsg;
	} else if ( emptyCount ) {
		allMsg = '<div style="width:200px"> <p style="margin:auto; text-align:center">' + 'All fields marked in red are required and need to be valid.' + "</p>";
	}
	
	if ( emptyCount === 1 && errCounter === 1 && (new RegExp('The Email field')).test(allMsg) )
		allMsg = '<div style="width:200px"> <p style="margin:auto; text-align:center">' + 'The Email field is required.' + "</p>";
		
	allMsg += "</div>";	
	return allMsg;
	//console.log($(formItems[4].getActiveError()).text());	
}

Ext.onReady(pantry_user_management_js.app.init, pantry_user_management_js.app);