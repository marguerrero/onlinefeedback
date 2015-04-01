Ext.namespace('pantry_feedback_report_js');

/************************
* Extjs Models
************************/

pantry_feedback_report_js.app = function()
{
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


            pantry_feedback_report_js.app.form_panel = new Ext.create('Ext.FormPanel', 
            {
                width: 400,
                height: 200,
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
                        title: 'Contact Information',
                        width: '90%',
                        style: 'margin: 0 0 0 20px',
                        items: [{
                            xtype: 'datefield',
                            fieldLabel: 'Date From',
                            id: 'report_article_datefrom',
                            labelWidth: 70,
                            name: 'report_article_datefrom',
                            listeners:
                            {
                                select: function()
                                {
                                   
                                }
                            }
                        },{
                            xtype: 'datefield',
                            fieldLabel: 'Date To',
                            labelWidth: 70,
                            id: 'report_article_dateto',
                            name: 'report_article_dateto',
                            listeners:
                            {
                                select: function()
                                {
                                   
                                }
                            }
                        }]
                    }],
                bbar:[
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
                        
                        pantry_feedback_report_js.app.form_panel.submit(
                        {
                            url: 'generate',
                            params: { 
                                    date_from: Ext.getCmp('report_article_datefrom'),
                                    date_to: Ext.getCmp('report_article_dateto') 
                                    },
                           success: function(res, opt) {
                               if(opt.result.status == 'success')
                               {
                                    Ext.MessageBox.show({
                                        title: 'Report Generation',
                                        msg: 'Report Successfully generated',
                                        buttons: Ext.MessageBox.OK,
                                        icon: Ext.MessageBox.INFO
                                    });
                                    
                                    window.location.href = 'http://cdo-apps2.concentrix.ph/online_feedback/web/reports/online_feeback_report-' + opt.result.data
                               }
                               
                               else
                               {
                                     Ext.MessageBox.show({
                                        title: 'Report Generation',
                                        msg: opt.result.data,
                                        buttons: Ext.MessageBox.OK,
                                        icon: Ext.MessageBox.INFO
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
                }]
            });

            pantry_feedback_report_js.app.form_panel.render('form_content');
            pantry_feedback_report_js.app.form_panel.show();
        }
    }
}();

Ext.onReady(pantry_feedback_report_js.app.init, pantry_feedback_report_js.app);
