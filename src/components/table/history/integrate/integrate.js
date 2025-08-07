(() => {
  return {
    props: {
      host: {
        type: String,
        required: true
      },
      db: {
        type: String,
        required: true
      },
      table: {
        type: String,
        required: true
      },
      columns: {
        type: Array
      }
    },
    data() {
      return {
        root: appui.plugins['appui-database'] + '/',
        users: bbn.fn.order(appui.users, 'text'),
        formSource: {
          host: this.host,
          db: this.db,
          table: this.table,
          user: appui.user.id,
          date: bbn.fn.dateSQL(),
          activeColumn: null
        }
      }
    },
    methods: {
      onSuccess(d){
        if (d.success) {
          appui.success();
          this.table.reload();
        }
        else {
          appui.error(d?.error || bbn._('An error occurred'));
        }
      },
      onFailure(d){
        appui.error(d?.error || bbn._('An error occurred'));
      }
    }
  }
})();