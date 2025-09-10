// Javascript Document

(() => {
  return {
    props: {
      host: {
        type: String,
      },
      engine: {
        type: String,
        default: 'mysql'
      },
      database: {
        type: String,
      }
    },
    data() {
      return {
        code: '',
        codes: [],
        database: '',
        result: [],
        numberOfQueries: 1,
        isRunning: false
      };
    },
    methods: {
      save(item) {
        bbn.fn.post(appui.plugins["appui-database"] + '/console', {
          engine: this.source.engine,
          code: item.request,
          action: 'save'
        }, data => {
          bbn.fn.log("on save", data)
        });
        
      },
      exec() {
        if (this.isRunning) {
          return;
        }

        this.isRunning = true;
        const d = {
          code: this.code,
          database: this.source.database
        };
        bbn.fn.post(appui.plugins["appui-database"] + '/console', d, data => {
          this.result = data.data;
          let res = data.query + "\n\n" + (data.error || data.str_tab);
          this.codes.unshift({code: res, request: data.query});
          this.code = "";
          this.isRunning = false;
        });
      }

    },
  };
})();
