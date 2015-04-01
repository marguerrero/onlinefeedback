Ext.namespace('pantry_logs_js');
/**
 *	Ext MODELS 
 */

Ext.define('Logs', {
	extend : "Ext.data.Model",
	fields : [
		{ name : "module" },
		{ name : "affected_id" },
		{ name : "affected_data" },
		{ name : "username" },
		{ name : "actionstamp" }
	]
});

pantry_logs_js.app = function()
{
    return {
        init: function()
        {
            pantry_logs_js.app.initPantryLogs();
        },
        
        initPantryLogs: function() {
        	/**
        	 * Store
        	 */
        	pantry_logs_js.app.logs_store = new Ext.data.Store({
        		autoLoad: true,
        		pageSize: 1,
        		remoteSort: true,
        		model: "Logs",
        		proxy: {
        			type : "ajax",
        			url: "load-logs",
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
        	
        	/**
        	 * Grid 
        	 */
        	
        	pantry_logs_js.app.logs_grid = Ext.create('Ext.grid.Panel', {
        		width: 900,
        		height: 900,
        		frame: true,
		        title: 'Logs',
		        store: pantry_logs_js.app.logs_store,
		        iconCls: 'icon-logs',
    			renderTo: "form_content",
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
		            header: 'Module',
		            width: 150,
		            sortable: true,
		            dataIndex: 'module',
		            field: {
		                xtype: 'textfield'
		            }
		        }, {
		            header: 'Affected ID',
		            width: 100,
		            sortable: true,
		            dataIndex: 'affected_id',
		            field: {
		                xtype: 'textfield'
		            }
		        }, {
		            header: 'Affected Data',
		            width: 250,
		            sortable: true,
		            dataIndex: 'affected_data',
		            field: {
		                xtype: 'textfield'
		            }
		        }, {
		            header: 'Username',
		            width: 150,
		            sortable: true,
		            dataIndex: 'username',
		            field: {
		                xtype: 'textfield'
		            }
		        }, {
		            header: 'actionstamp',
		            width: 130,
		            sortable: true,
		            dataIndex: 'actionstamp'
		        }]
        	});
        }
    }
}();

Ext.onReady(pantry_logs_js.app.init, pantry_logs_js.app);