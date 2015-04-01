Ext.namespace('pantry_email_recipient_js');
/**
 *	Ext MODELS 
 */

Ext.define('Email', {
	extend : "Ext.data.Model",
	fields : [
		{ name : "id" },
		{ name : "email" },
		{ name : "active" }
	]
});

pantry_email_recipient_js.app = function()
{
    return {
        init: function()
        {
            pantry_email_recipient_js.app.initPantryEmail();
        },
        /*email_grid : new Ext.FormPanel({
		    id: 'fLogin',
		    title: 'theForm!'
		}),*/
        
        initPantryEmail: function() {    
        	/**
        	 * Ext Stores 
        	 */    	
        	pantry_email_recipient_js.app.email_store = new Ext.data.Store({
        		autoLoad: true,
        		pageSize: 15,
        		remoteSort: true,
        		model: "Email",
        		proxy: {
        			type : "ajax",
        			url: "load-email",
        			reader: {
        				type : "json",
        				root: "data",
        				totalProperty : "totalCount"
        			},
        			writer: { 
        				type: "json"
        			}
        		}
        	});
        	  	
        	var recipient_activity = new Ext.data.Store({
        		fields: ['key', 'name'],
			    data : [
			        {"key":"true", "name":"True"},
			        {"key":"false", "name":"False"}
			    ]
        	})       	
        	
        	
        	/**
        	 * Ext Forms 
        	 */
        	pantry_email_recipient_js.app.openForm = function( mode ) {
        		var formParams = {},
        			formUrl = "save-recipient";
        		if ( mode === 'update') {
        			var selection = pantry_email_recipient_js.app.email_grid.getView().getSelectionModel().getSelection()[0];
                    if ( selection ) {
                    	var editData = selection.data;
                    	formUrl= 'update-recipient';
                    	formParams.oldEmail = editData.email;
                    	formParams.id = editData.id;
                    	formParams.active = editData.active;
                    }
        		}
        		pantry_email_recipient_js.app.formPanel = new Ext.form.Panel({
			        bodyStyle:'padding:5px 5px 0',
			        width: 350,
			        center : true,
			        fieldDefaults: {
			            msgTarget: 'side',
			            labelWidth: 45
			        },
			        defaultType: 'textfield',
			        defaults: {
			            anchor: '100%'
			        },
			        method : "POST",
			        url : formUrl,
        			items : [
			        	{
			        		xtype : "textfield",
			        		id : "email",
			        		name : "email",
			        		fieldLabel : "Email:",
			        		displayName : "Email",
			        		emptyText : "Enter email here",
			        		vtype : "email",
                            allowBlank: false,
                            listeners: {
						      afterrender: function(field) {
						        //field.focus(false, 200);
						        setTimeout(function() {$('#widget_window_modal #email-inputEl').select();}, 210); 
						      }
						    }
			        	},
			        	{
			        		xtype : "combo",
			        		id : "active",
			        		name : "active",
			        		fieldLabel : "Active",
			        		value : "False",
			        		invalidText : "The Active field is required",
			        		store : recipient_activity,
			        		valueField : "key",
			        		displayField : "name",
			        		allowBlank : false,
			        		editable: false			        		
			        	}
			        ],
			        buttons : [
			        	{
			        		text : "Save",
			        		handler : function() {
			        			var that = this;
								var loadingMask = new Ext.LoadMask(Ext.getBody(), {msg:"Please wait..."});
			        			if ( pantry_email_recipient_js.app.formPanel.isValid() ) {
									that.disable();
									loadingMask.show();
				        			pantry_email_recipient_js.app.formPanel.submit({
			        					params : formParams,
								        success : function(action, res) {
								        	var jsonResponse = Ext.JSON.decode(res.response.responseText);
								        	if ( jsonResponse.success ) {
								        		Ext.Msg.alert({
		                                           title: 'Success Message',
	                                           	   msg: "Recipient " + pantry_email_recipient_js.app.formPanel.items.getByKey('email').getValue() + " saved.",
		                                           buttons: Ext.Msg.OK
		                                      	});
								        	} else {
								        		Ext.Msg.alert({
		                                           title: 'Error while saving',
		                                           msg: "Please contact administrator",
		                                           buttons: Ext.Msg.OK
		                                      	});
								        	}
											pantry_email_recipient_js.app.widget_window.destroy();
								        	pantry_email_recipient_js.app.email_store.reload();
											loadingMask.destroy();
											that.enable();
								        },
								        failure : function( form, res) {
								        	var response = Ext.JSON.decode(res.response.responseText);
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
		                                           msg: "Invalid input.",
		                                           buttons: Ext.Msg.OK
		                                      	});
											} else {
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
				        		{
				        			var msg = 'All fields are required.';
				        			if ( msg = getErrorMessages(pantry_email_recipient_js.app.formPanel.items.items) )
				        				;
				        			Ext.Msg.alert({
                                       title: 'Error while saving',
                                       msg: msg,
                                       buttons: Ext.Msg.OK
                                  	});
				        		}
			        		}
			        	},
			        	{
			        		text : "Cancel",
			        		handler : function() {
			        			pantry_email_recipient_js.app.widget_window.destroy()
			        		}
			        	}
			        ]
        		});
			    pantry_email_recipient_js.app.widget_window = Ext.create('widget.window', {
			    	id : "widget_window_modal",
			        height: 150,
			        width: 350,
			        title: ((mode === 'update') ? 'Update' : 'Add') + ' Recipient',
			        modal: true,
			        plain: true,
			        layout: 'fit',
			        items: pantry_email_recipient_js.app.formPanel
			    }).center().show();
			    
			    if ( mode === 'update' ) 
				{
					var emailId = pantry_email_recipient_js.app.email_grid.getSelectionModel().getSelection()[0].data.id;
					var emailActive = pantry_email_recipient_js.app.email_grid.getSelectionModel().getSelection()[0].data.active;
					var selectedModel = pantry_email_recipient_js.app.email_grid.getSelectionModel();
					var index = selectedModel.getSelection()[0].index % pantry_email_recipient_js.app.email_grid.getStore().pageSize; 
					selectedModel.deselectAll(true);
					selectedModel.select(index, false, true);
					var selectedRow = selectedModel.getSelection()[0];					
					
					Ext.getCmp('email').setValue(selectedRow.getData().email);
                    Ext.getCmp('active').setValue(selectedRow.getData().active);
				}
        	}
        	
		    pantry_email_recipient_js.app.email_grid = Ext.create('Ext.grid.Panel', {
		        width: 850,
		        height: 650,
		        frame: true,
		        title: 'Recipients',
		        store: pantry_email_recipient_js.app.email_store,
		        iconCls: 'icon-user',
    			renderTo: "form_content",
    			listeners : {
    				celldblclick : function() { 
    					pantry_email_recipient_js.app.openForm('update')
    				},
    				cellclick : function() {
    					var btnFlag = (pantry_email_recipient_js.app.email_grid.getSelectionModel().hasSelection()) ? 0 : 1; 
				        pantry_email_recipient_js.app.email_grid.down('#delete').setDisabled(btnFlag);
				        pantry_email_recipient_js.app.email_grid.down('#update').setDisabled(btnFlag);
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
		            header: 'Email',
		            flex: 1,
		            sortable: true,
		            dataIndex: 'email',
		            field: {
		                xtype: 'textfield'
		            }
		        }, {
		            header: 'Active',
		            flex: 1,
		            sortable: true,
		            dataIndex: 'active'
		        }],
		        dockedItems: [{
		            xtype: 'toolbar',
		            items: [{
		                text: 'Add',
		                id: "user_add_btn",
		                iconCls: 'icon-add',
		                handler: function(e)
						{
							pantry_email_recipient_js.app.openForm();
		                }
		            }, '-', {
		                itemId: 'update',
		                text: 'Update',
		                iconCls: 'icon-update',
		                disabled: true,
		                handler: function() {
							pantry_email_recipient_js.app.openForm('update');
		                }
		            }, '-', {
		                itemId: 'delete',
		                text: 'Delete',
		                iconCls: 'icon-delete',
		                disabled: true,
		                handler: function() {
		                	var that = this;
		                    var selected = pantry_email_recipient_js.app.email_grid.getSelectionModel().getSelection()[0].data;
	    					if ( selected ) {	    						
								var loadingMask = new Ext.LoadMask(Ext.getBody(), {msg:"Please wait..."});
	    						Ext.MessageBox.confirm({
	                                title: 'Delete Recipient',
	                                msg: 'Are you sure you want to delete recipient ' + '(' + selected.email + ') ?',
	                                buttons: Ext.Msg.YESNO,
	                                fn: function (btn) {
	                                    if(btn === 'yes')
	                                    {
				    						that.disable();
				    						loadingMask.show();
				    						Ext.Ajax.request({
				    							url : "delete-recipient",
				    							method : "POST",
				    							params : { id : selected.id },
				    							success : function( action, res) {
				    								try {
				    									var jsonResponse = Ext.JSON.decode(action.responseText);
										        	} catch (e) {
										        		
										        	} finally {			
										        		
										        	}
										        	if ( jsonResponse && jsonResponse.success ) {
														Ext.Msg.alert({
				                                           title: 'Success Message',
				                                           msg: "Deleted Recipient " + selected.email,
				                                           buttons: Ext.Msg.OK
				                                      	});
										        	} else {
										        		Ext.Msg.alert({
				                                           title: 'Error while deleting',
				                                           msg: "Please contact administrator",
				                                           buttons: Ext.Msg.OK
				                                      	});
										        	}
				    								
				    								loadingMask.destroy();
				    								that.enable();
					            					pantry_email_recipient_js.app.email_store.reload();
				    							},
										        failure : function( form, res) {
										        	try {
										        		var response = Ext.JSON.decode(res.response.responseText);
										        	} catch (e) {
										        		
										        	} finally {			
										        		
										        	}
													if ( response && response.msg )
													{
														Ext.Msg.alert({
				                                           title: 'Error while deleting',
				                                           msg: response.msg,
				                                           buttons: Ext.Msg.OK
				                                      	});
													}
													else if ( response && response.status == 400 )
													{
														Ext.Msg.alert({
				                                           title: 'Error while deleting',
				                                           msg: response.msg,
				                                           buttons: Ext.Msg.OK
				                                      	});
													} else {
														Ext.Msg.alert({
				                                           title: 'Error while deleting',
				                                           msg: "Please contact administrator",
				                                           buttons: Ext.Msg.OK
				                                      	});
													}													
													loadingMask.destroy();
													that.enable();
										        }
				    						})
				    					}
				    				}
				    			});
	    					}
		                }
		            }]
		        }],
		        bbar: Ext.create('Ext.PagingToolbar', {
		            store: pantry_email_recipient_js.app.email_store,
		            displayInfo: true,
		            displayMsg: 'Displaying users {0} - {1} of {2}',
		            emptyMsg: "No recipients to display"
		        }) 
		    });
		    
		    pantry_email_recipient_js.app.email_grid.getSelectionModel().on('selectionchange', function(selModel, selections) {
		        pantry_email_recipient_js.app.email_grid.down('#delete').setDisabled(selections.length === 0);
		        pantry_email_recipient_js.app.email_grid.down('#update').setDisabled(selections.length === 0);
		    });
            
        }
    }
}();

function getErrorMessages(formItems) {
	var allMsg = '<div style="width:200px">',
		counter = 1;
	$.each(formItems, function() {
		if( !this.validate() ) {
			var msg = $(this.getActiveError()).text();
			if ( this.getName() == 'email' && this.getValue() != '' )
				msg = 'The Email field should be in the format user@email.com.'
			else
				msg = 'The Email field is required.'
			//allMsg += '<p style="margin:auto; text-align:center">' + (counter++) + ".) " + msg + "</p>"
			allMsg += '<p style="margin:auto; text-align:center">' + msg + "</p>"
		}
	})
	allMsg += "</div>";
	
	return allMsg;
	//console.log($(formItems[4].getActiveError()).text());	
}

Ext.onReady(pantry_email_recipient_js.app.init, pantry_email_recipient_js.app);

