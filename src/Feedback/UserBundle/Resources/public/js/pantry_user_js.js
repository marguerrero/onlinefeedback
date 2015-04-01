Ext.namespace('pantry_user_js');

/************************
* Extjs Models
************************/

pantry_user_js.app = function()
{
    return {
        init: function()
        {
            pantry_user_js.app.initPantryUserPanel();
        },
        /************************
        *
        * Initialize Main Panel
        *
        ************************/
        initPantryUserPanel: function()
        {
        	// Define autocomplete model
			Ext.define('Concessionaire', {
			    extend: 'Ext.data.Model',
			    fields: [
			        { name: 'description' }
			    ]
			});
			pantry_user_js.app.conc_store = new Ext.data.Store(
            {
                model: 'Concessionaire',
                pageSize: 15,
                autoLoad: true,
                proxy:
                {
                    type: 'ajax',
                    url: 'load-login-concessionaire',
                    reader: 
                    {
                        type: 'json',
                        root: 'data',
                        totalProperty: 'totalCount'
                    }
                }
            });

            
            pantry_user_js.app.combobox = new Ext.form.ComboBox({
				model : "Concessionaire",
				id : "selected_values",
            	emptyText: 'Select Concessionaire',
			    //multiSelect: true,
			    multiSelect: false,
			    displayField: 'description',
			    width: 250,
			    labelWidth: 130,
			    store: pantry_user_js.app.conc_store,
			    queryMode: 'local',
			    renderTo: "comboboxRow",
			    allowBlank: false
            });

			pantry_user_js.app.combobox = new Ext.form.ComboBox({
                model : "Concessionaire",
                id : "selected_values",
                emptyText: 'Select Concessionaire',
                //multiSelect: true,
                multiSelect: false,
                displayField: 'description',
                width: 250,
                labelWidth: 130,
                store: pantry_user_js.app.conc_store,
                queryMode: 'local',
                renderTo: "comboboxRowAnonym",
                allowBlank: false
            });
        }
    }
}();

Ext.onReady(pantry_user_js.app.init, pantry_user_js.app);
