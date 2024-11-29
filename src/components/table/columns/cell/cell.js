// Javascript Document
(()=> {
  return {
    data() {
      let db = this.closest('appui-database-db');
      return {
        db: null
      }
    },
    mounted() {
      this.db = this.closest('appui-database-db');
    },
    computed: {
      link() {
        const db = this.db;
        if (!db) {
          return '';
        }
        let o = db.source || db;
        return db ? db.root + 'tabs/' + o.engine + '/' + o.host + '/' + o.db + '/' + this.source.name + '/home' : ''
      }
    }
  }
})();
