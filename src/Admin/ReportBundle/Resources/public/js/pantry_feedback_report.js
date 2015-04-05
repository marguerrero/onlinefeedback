Ext.namespace('pantry_feedback_report_js');

/************************
* Extjs Models
************************/

pantry_feedback_report_js.app = function()
{
	// Add the additional 'advanced' VTypes
    Ext.apply(Ext.form.field.VTypes, {
        daterange: function(val, field) {
            var date = field.parseDate(val);

            if (!date) {
                return false;
            }
            if (field.startDateField && (!this.dateRangeMax || (date.getTime() != this.dateRangeMax.getTime()))) {
                var start = field.up('form').down('#' + field.startDateField);
                start.setMaxValue(date);
                start.validate();
                this.dateRangeMax = date;
            }
            else if (field.endDateField && (!this.dateRangeMin || (date.getTime() != this.dateRangeMin.getTime()))) {
                var end = field.up('form').down('#' + field.endDateField);
                end.setMinValue(date);
                end.validate();
                this.dateRangeMin = date;
            }
            /*
             * Always return true since we're only using this vtype to set the
             * min/max allowed values (these are tested for after the vtype test)
             */
            return true;
        },

        daterangeText: 'Start date must be less than end date'
    })
	
	
    return {
        init: function()
        {
            pantry_feedback_report_js.app.initPantryFeedbackReportPanel();
        },
        /************************
        *
        * Initialize Main Panel
        *
        ************************/
        initPantryFeedbackReportPanel: function()
        {
        	// Define autocomplete model
			Ext.define('Concessionaire', {
			    extend: 'Ext.data.Model',
			    fields: [
			        { name: 'description' }
			    ]
			});
			
			pantry_feedback_report_js.app.conc_store = new Ext.data.Store(
            {
                model: 'Concessionaire',
                pageSize: 15,
                autoLoad: true,
                proxy:
                {
                    type: 'ajax',
                    url: 'load-report-concessionaire',
                    reader: 
                    {
                        type: 'json',
                        root: 'data',
                        totalProperty: 'totalCount'
                    }
                }
            });

            pantry_feedback_report_js.app.form_panel = Ext.create('Ext.form.Panel', 
            {
                width: 400,
                height: 215,
                center: true,
                frame: true,
                title: 'Report Generation',
                bodyStyle: 'padding: 10px 0 20px 0;',
				defaults:{
                    anchor: '90%',
                    allowBlank: false,
                    labelWidth: 130,
                    msgTarget: 'side',
                    xtype: 'textfield'
                }, items: [
                    {
                        xtype: 'fieldset',
                        title: 'Feedback Summary Report Information',
                        id: 'reportFieldSet',
                        width: '90%',
                        style: 'margin: 0 0 0 20px',
                        items: [{
                            xtype: 'datefield',
                            fieldLabel: 'Date From',
                            id: 'report_article_datefrom',
			                vtype: 'daterange',
			                endDateField: 'report_article_dateto', // id of the end date field
			                maxText : "The Date From field must be equal to or before {0}",
			                minText : "The Date From field must be equal to or after {0}",
                            labelWidth: 120,
                            blankText: "The Date From field is required.",
                            width: 300,
                            name: 'report_article_datefrom',
						    allowBlank:false,
                            listeners:
                            {
                                focus : function() {
                                	// if to is invalid
                                	if ( !Ext.getCmp('report_article_dateto').validate() )
                                	{
                                		Ext.getCmp('report_article_datefrom').setMaxValue(new Date());
                                		Ext.getCmp('report_article_datefrom').setMinValue(0);
                                	}
                                },
                                /*boxready : function() {
                                	Ext.getCmp('report_article_datefrom').setMaxValue(new Date());
                                }*/
                            }
                        },{
                            xtype: 'datefield',
                            fieldLabel: 'Date To',
                            labelWidth: 120,
                            width: 300,
                            id: 'report_article_dateto',
			                vtype: 'daterange',
			                startDateField: 'report_article_datefrom', // id of the start date field
                            name: 'report_article_dateto',
                            blankText: "The Date To field is required.",
			                maxText : "The Date To field must be equal to or before {0}",
			                minText : "The Date To field must be equal to or after {0}",
						    allowBlank:false,
                            listeners:
                            {
                                focus : function() {                                	
                                	// if from is invalid
                                	if ( !Ext.getCmp('report_article_datefrom').validate() )
                                	{
                                		Ext.getCmp('report_article_dateto').setMinValue(0);
                                	}
                                	else
                                	{
                                		Ext.getCmp('report_article_dateto').setMinValue(Ext.getCmp('report_article_datefrom').getValue());
                                	}
                                	Ext.getCmp('report_article_dateto').setMaxValue(new Date());
                                }
                            }
                        },{
							xtype : "combobox",
							model : "Concessionaire",
							id : "selected_values",
	                    	//emptyText: 'Select Concessionaire',
	                    	fieldLabel: "Survey Type",
	                    	width: 300,
	                    	labelWidth:120,
						    //multiSelect: true,
                            blankText: "The Survey Type field is required.",
						    multiSelect: false,
						    displayField: 'description',
						    //valueField: 'description',
						    forceSelection:true,
						    allowBlank:false,
						    store: pantry_feedback_report_js.app.conc_store,
						    queryMode: 'local'
	                    }]
                    }],
                bbar:['->',
                {
                    text: 'Generate Report',
                    style: 'margin: 0 auto',
                    handler: function() 
                    {
                        if(pantry_feedback_report_js.app.form_panel.isValid())
                        {
                           Ext.MessageBox.show(
                            {
                               title: 'Generating Report',
                               progressText: 'Please wait..',
                               width:300,
                               wait:true,
                               waitConfig: {interval:200},
                               animateTarget: 'waitButton'
                            });
                        }
                        else
                        {
                        	var msg = '';
                        	if ( msg = getErrorMessages(Ext.getCmp('reportFieldSet').items.items) )
                        		;
                        		
                        	Ext.MessageBox.show(
                            {
                                title: 'Error',
                                msg: msg,
                                buttons: Ext.MessageBox.OK
                            });
                        	return false;
                        }
                        
                        pantry_feedback_report_js.app.form_panel.submit(
                        {
                        	url: 'generate',
                        	params:
                        	{ 
                           		selects : Ext.getCmp("selected_values").getValue().toString(),
                                date_from: Ext.getCmp('report_article_datefrom'),
                                date_to: Ext.getCmp('report_article_dateto')
                            },
                           success: function(res, opt) {
                               if(opt.result.status == 'success')
                               {
                                    Ext.MessageBox.show({
                                        title: 'Report Generation',
                                        width:300,
                                        msg: 'Report Successfully generated',
                                        buttons: Ext.MessageBox.OK,
                                        icon: Ext.MessageBox.INFO
                                    });
                                    
                                    // window.location.href = 'http://cdo-apps2.concentrix.ph/online_feedback/web/reports/online_feeback_report-' + opt.result.data
                                    window.location.href = global_report_url + '/online_feeback_report-' + opt.result.data
                               }
                               
                               else
                               {
                                     Ext.MessageBox.show({
                                        title: 'Report Generation',
                                        msg: opt.result.data,
                                        buttons: Ext.MessageBox.OK,
                                        icon: Ext.MessageBox.ERROR
                                    });
                               }
                           },
                           failure: function() {
                                Ext.Msg.show({
                                     title: 'Error Alert',
                                     msg: 'Failed to generate report from the selected date.',
                                     icon: Ext.Msg.ERROR,
                                     buttons: Ext.Msg.OK
                                });
                           }
                            
                        });
                        
                    }
                }, '->']
            });

            pantry_feedback_report_js.app.form_panel.render('form_content');
            pantry_feedback_report_js.app.form_panel.show();
        }
    }
}();

function getErrorMessages2(formItems) {
	var allMsg = '<div style="width:200px; margin-left:8px">',
		counter = 1;
	$.each(formItems, function() {
		if( !this.validate() ) {
			var msgs = $(this.getActiveError()).find('li'),
				msg = '';
			$.each(msgs, function() {
				if ( $(this).text() !== 'Start date must be less than end date' )
					msg += '<p style="margin:auto; text-align:center">' + $(this).text() + "</p>";
				;
			})
			//allMsg += '<p style="margin:auto; text-align:center">' + (counter++) + ".) " + msg + "</p>"
			//allMsg += '<p style="margin:auto; text-align:center">' + msg + "</p>"
			allMsg += msg;
		}
	})
	allMsg += "</div>";
	
	return allMsg;
	//console.log($(formItems[4].getActiveError()).text());	
}

function getErrorMessages(formItems) {
	var allMsg = '<div style="width:200px">',
		counter = 1,
		errCounter = 0,
		emptyCount = 0,
		allFields = 1,
		fieldsTotal = formItems.length,
		oneEmptyMsg,
		firstNotEmptyMsg;
	$.each(formItems, function() {
		if( !this.validate() ) {
			var msgs = $(this.getActiveError()).find('li'),
				msg = '';
			$.each(msgs, function() {
				if ( $(this).text() !== 'Start date must be less than end date' )
					msg += '<p style="margin:auto; text-align:center">' + $(this).text() + "</p>";
				;
			})
			if (!msgs.length) {
				msg = $(this.getActiveError()).text();
			}
			if ( this.getValue() === '' ||  this.getValue() === null )
			{
				emptyCount++;
				oneEmptyMsg = msg;
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
		allMsg = '<div style="width:200px"> <p style="margin:auto; text-align:center">' + 'All fields are required.' + "</p>";
	} else if ( errCounter === 1 ) {
		;//allMsg = oneEmptyMsg;
	} else if ( emptyCount ) {
		allMsg = '<div style="width:200px"> <p style="margin:auto; text-align:center">' + 'All fields marked in red are required and need to be valid.' + "</p>";
	}
		
	allMsg += "</div>";	
	return allMsg;
	//console.log($(formItems[4].getActiveError()).text());	
}


Ext.onReady(pantry_feedback_report_js.app.init, pantry_feedback_report_js.app);
