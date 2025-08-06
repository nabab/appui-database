(() => {
  return {
    mixins: [bbn.cp.mixins.basic],
    data(){
      return {
        root: appui.plugins['appui-database'] + '/',
        isLoading: false
      }
    },
    computed: {
      mainMenu(){
        const r = [];
        if (!this.source.history) {
          r.push({
            text: bbn._("Integrate History system"),
            icon: 'nf nf-md-creation',
            action: () => {
              this.integrateHistory();
            }
          });
        }
        return r;
      }
    },
    methods: {
      integrateHistory(){
        if (!this.source.history) {
          if (this.source.count) {
            this.getPopup({
              label: bbn._("Integrate History system"),
              component: 'appui-database-table-history-integrate',
              componentOptions: {
                host: this.source.id_host,
                db: this.source.database,
                table: this.source.name
              },
              maxWidth: '35rem'
            });
          }
          else {
            this.confirm(bbn._("Do you want to integrate the history system for this table?"), () => {
              this.isLoading = true;
              this.post(this.root + 'actions/table/history', {
                host: this.source.id_host,
                db: this.source.database,
                table: this.source.name
              }, d => {
                if (d.success) {
                  this.main.reload();
                  appui.success(bbn._("The history system has been successfully integrated for this table."));
                }
                else {
                  appui.error(d.error || bbn._("An error occurred while integrating the history system for this table."));
                }
                this.isLoading = false;
              }, () => {
                appui.error(d.error || bbn._("An error occurred while integrating the history system for this table."));
                this.isLoading = false;
              });
            });
          }
        }
      }
    }
  };
})();