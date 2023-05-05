// Javascript Document

(() => {
  return {
    data() {
      return {
        code: ''
      };
    },
    methods: {
      exec() {
        this.post(appui.plugins["appui-database"] + '/console', {
          code: this.code
        });
      }
    }
  };
})();