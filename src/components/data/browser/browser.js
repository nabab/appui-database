// Javascript Document

(() => {
  return {
    data() {
      return {};
    },
    method: {
      browse() {
        this.getPopup().load(appui.plugins['appui-database'] + '/data/data-browser', {
          host: this.source.host,
          db: this.source.db,
          engine: this.source.engine,
          table: this.source.table
        })
      };
    },
    computed: {
      link() {
        let res = appui.plugins['appui-database'] + '/table/';
        res += this.source.engine + '/' + this.source.host + '/';
        res += this.source.db + '/' + this.source.table + '/';
        return res + '/data';
      }
    }
  };
})();