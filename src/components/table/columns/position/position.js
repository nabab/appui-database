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
      after: {
        type: [String, null],
        default(){
          return null;
        }
      },
      options: {
        type: Boolean,
        default: false
      }
    },
    data(){
      return {
        action: appui.plugins['appui-database'] + '/actions/column/position',
        formSource: {
          host_id: this.host,
          db: this.database,
          table: this.table,
          column: this.column,
          after: this.after,
          options: !!this.options
        },
        message: this.after ?
          bbn._("Are you sure you want to move the column \"%s\" after the column \"%s\"?", this.column, this.after) :
          bbn._("Are you sure you want to move the column \"%s\" to first position?", this.column)
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