// Javascript Document

(() => {
  return {
    computed: {
      widgets() {
        let widgets = [
          {
            label: bbn._("Table properties"),
            component: "appui-database-table-info-widgets-config",
            closable: false
          }, {
            label: bbn._("Table options"),
            component: "appui-database-table-info-widgets-option",
            closable: false
          },
        ];
        if (!this.source.table_id) {
          widgets[1].buttonsLeft = [{
            text: bbn._('Create Option'),
            action: this.createOption,
            icon: 'nf nf-fa-plus'
          }];
        }
        return widgets;
      }
    },
    methods: {
      createOption(row) {
        bbn.fn.post(appui.plugins['appui-database'] + "/actions/table/option", {
          db_id: this.source.db_id,
          table: this.source.table,
          host: this.source.host
        }, res => {
          bbn.fn.log(res);
          if (res.success) {
            this.$set(this.source, "option", res.option);
            this.$set(this.source, "table_id", res.table_id);
          }
        });
      },
    }
  };
})();