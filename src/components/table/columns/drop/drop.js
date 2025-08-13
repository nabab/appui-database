(() => {
  return {
    props: {
      host: {
        type: String,
        required: true
      },
      database: {
        type: String,
        required: true
      },
      table: {
        type: String,
        required: true
      },
      column: {
        type: [String, Array],
        required: true
      },
      options: {
        type: Boolean,
        default: false
      }
    },
    data(){
      return {
        action: appui.plugins['appui-database'] + '/actions/column/drop',
        formSource: {
          host_id: this.host,
          db: this.database,
          table: this.table,
          column: this.column,
          options: !!this.options
        },
        message: bbn.fn.isString(this.column) ?
          bbn._("Are you sure you want to drop the column \"%s\"?", this.column) :
          bbn._("Are you sure you want to drop the columns %s?", bbn.fn.map(this.column, d => '"' + d + '"').join(", "))
      }
    },
    methods: {
      onSuccess(d){
        if (d.success) {
          this.$emit('success');
          if (d.error) {
            appui.error(d.error);
          }
          else {
            appui.success();
          }
        }
        else {
          appui.error(d.error || bbn._('An error occurred'));
        }
      },
      onError(d){
        appui.error(d.error || bbn._('An error occurred'));
      }
    }
  };
})();