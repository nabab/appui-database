// Javascript Document

(() => {
  return {
    props: ['source'],
    methods: {
      addHost(engine, widgetKey) {
        this.getPopup({
          component: 'appui-database-host-form',
          source: {
            engine: engine
          },
          close: () => {
            let db = this.getRef('dashboard');
            if (!db) {
              bbn.fn.log("No dashboard");
              return;
            }
            let widget = db.findByKey(widgetKey);
            if (!widget) {
              bbn.fn.log("No widget");
              return;
            }
            widget.reload();
          }
        });
      },
      addMysqlHost(){
        if (this.source.dashboard
          && this.source.dashboard.widgets
          && this.source.dashboard.widgets.hosts_mysql
          && this.source.dashboard.widgets.hosts_mysql.key
        ) {
          this.addHost('mysql', this.source.dashboard.widgets.hosts_mysql.key);
        }
      },
      insert(){
        return this.$refs.table.insert(null,{width: '500px', height: '450px'}, bbn._("New Host"));
      },
      edit(row){
        return this.$refs.table.edit(row,{width: '500px', height: '450px'}, bbn._("Edit Host"));
      },
      removeItem(row){

      },
      renderUser(row){
        this.$nextTick(()=>{
          return row.username
        })
      },
      renderHost(row){
        //return '<a href="' + appui.databases.source.root + 'tabs/' + row.name + '">'+ row.text +'</a>'
        return '<a href="' + appui.databases.source.root + 'tabs/' + row.code + '">'+ row.text +'</a>'
      },
      writeText(data){
        let txt = data.text;
        if ( data.text !== data.name ){
          txt += ' (<em>' + data.name + '</em>)';
        }
        return '<a href="' + this.source.root + 'tabs/host/' + data.name + '">' + txt + '</a>';
      }
    }
  }
})();