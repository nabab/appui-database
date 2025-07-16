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
      options: {
        type: Boolean,
        default: false
      }
    },
    data(){
      return {
        action: appui.plugins['appui-database'] + '/actions/table/duplicate',
        formSource: {
          host_id: this.host,
          db: this.database,
          table: this.table,
          name: '',
          options: !!this.options
        }
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
  }
})();