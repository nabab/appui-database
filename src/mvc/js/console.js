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
        this.post(appui.plugins["appui-database"] + '/console', {
          code: this.code,
					database: this.database
        }, data => {
          this.result = data.data;
          bbn.fn.log("thedata:", data.data);
        });
      }
    }
  };
})();