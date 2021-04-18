// Javascript Document

(() => {
  return {
    data(){
      return {
        widgets: [
          {
            title: bbn._("Table properties"),
            component: "appui-database-table-info-widgets-config",
            closable: false
          }, {
            title: bbn._("Table options"),
            component: "appui-database-table-info-widgets-option",
            closable: false
          }
        ]
      }
    }
  }
})();