// Javascript Document

(() => {
  return {
    mixins: [
      bbn.cp.mixins.basic,
      bbn.cp.mixins.input
    ],
    props: {
      refColumn: {
        type: String
      }
    },
    data() {
      return {
        currentValue: this.value
      };
    },
    methods: {
      browse() {
        this.post(appui.plugins['appui-database'] + '/data/table', {
          host: this.source.host,
          db: this.source.db,
          engine: this.source.engine,
          table: this.source.table
        }, d => {
          if (d.success) {
            this.getPopup({
              title: bbn._("Data Browser") + ' (' + d.data.table + ')',
              source: d.data,
              component: 'appui-database-table-data',
              componentOptions: {
                selector: true,
                source: d.data,
                refColumn: this.source.refColumn
              },
              minWidth: 400,
              minHeight: 500
            });
          }
        });
      }
    },
    watch: {
      currentValue(value) {
        if (this.value !== value) {
          this.emitInput(value);
        }
      }
    }
  };
})();
