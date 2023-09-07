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
        return db ? db.root + 'tabs/' + db.source.engine + '/' + db.source.host + '/' + db.source.db + '/' + this.source.name + '/home' : ''
      }
    }
  }
})();