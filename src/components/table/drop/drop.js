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
        action: appui.plugins['appui-database'] + '/actions/table/drop',
        formSource: {
          host_id: this.host,
          db: this.database,
          table: this.table,
          options: !!this.options
        },
        message: bbn.fn.isString(this.table) ?
          bbn._("Are you sure you want to drop the table \"%s\"?", this.table) :
          bbn._("Are you sure you want to drop the tables %s?", bbn.fn.map(this.table, d => '"' + d + '"').join(", "))
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