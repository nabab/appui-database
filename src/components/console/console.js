// Javascript Document

(() => {
  return {
    data() {
      return {
        code: '',
        database:'',
        result: [],
      };
    },
    methods: {
      exec() {
        this.post(appui.plugins["appui-database"] + '/request', {
          code: this.code,
					database: this.database
        }, data => {
          this.result = data.data;
        });
      }
    }
  };
})();
