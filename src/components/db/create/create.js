// Javascript Document
(() => {
  return {
    props: {
      host: {
        type: String,
        required: true
      },
      engine: {
        type: String,
        required: true
      },
      charset: {
        type: String,
        default: ''
      },
      collation: {
        type: String,
        default: ''
      }
    },
    data(){
      return {
        root: appui.plugins['appui-database'] + '/',
        formData: {
          name: '',
          host_id: this.host,
          engine: this.engine,
          charset: this.charset,
          collation: this.collation,
          options: 0
        }
      }
    },
    methods: {
      onSuccess(d) {
        if (d.success) {
          let db = this.formData.name;
          if ((this.engine === 'sqlite')
            && (!db.endsWith('.sqlite'))
            && (!db.endsWith('.db'))
          ) {
            db += '.sqlite';
          }

          appui.success();
          this.closest('bbn-container').reload();
          this.closest('bbn-router').route(db);
        }
        else {
          appui.error(d.error || bbn._('An error occurred'));
        }
      },
      onError(d) {
        appui.error(d.error || bbn._('An error occurred'));
      }
    }
  }
})();