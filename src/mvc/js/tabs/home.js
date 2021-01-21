// Javascript Document

(() => {
  return {
    props: ['source'],
    computed: {
      dashboardSrc(){
        let data = [];
        bbn.fn.iterate(this.source.dashboard, (a, n) => {
          let it = bbn.fn.clone(a);
          if (n === 'hosts_mysql') {
            it.buttonsRight = [{
              icon: "nf nf-fa-plus",
              text: bbn._("Add a new MySQL host"),
              action: () => {
                this.addHost('mysql', a.key);
              }
            }];
          }
          bbn.fn.log("WIDGET", it, n);
          data.push(it);
        });
        return data;
      }
    },
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
      insert(){
        return this.$refs.table.insert(null,{width: '500px', height: '450px'}, bbn._("New Host"));
      },
      edit(row){
        return this.$refs.table.edit(row,{width: '500px', height: '450px'}, bbn._("Edit Host"));
      },
      remove(row){

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