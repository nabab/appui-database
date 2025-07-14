(() => {
  return {
    props: {
      host: {
        type: String,
        required: true
      },
      database: {
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
        action: appui.plugins['appui-database'] + '/actions/database/drop',
        formSource: {
          host_id: this.host,
          db: this.database,
          options: !!this.options
        },
        message: bbn.fn.isString(this.database) ?
          bbn._("Are you sure you want to drop the database \"%s\"?", this.database) :
          bbn._("Are you sure you want to drop the databases %s?", bbn.fn.map(this.database, d => '"' + d + '"').join(", "))
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